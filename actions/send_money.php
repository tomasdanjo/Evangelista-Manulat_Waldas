
<div class="modal fade" id="user" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Error!</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="errorMessage"> </p>
      </div>

    </div>
  </div>
</div>

<?php
$error = "";
$success = "";
if (isset($_POST['btnSend'])) {
  //error checker;


  $sender_userid = $_SESSION["user_id"];
  $sender_accid = getVal($connection, "account_id", "tblacc", "user_id", $sender_userid);
  $receiver_mobileNum = $_POST['mobileNum'];
  $wallet_id = $_POST['wallet_id']; //error 1
  $amount = $_POST['sendAmount'];  //error 2


  //check tbluseraccount if username is existing

  $receiver_exists = valueExists($connection, "tblacc", "phonenumber", $receiver_mobileNum);


  if ($receiver_exists == false) {
    // Mobile number does not exist
    $error = "Mobile number not found. ";
    echo "<script language='javascript'>
                $(document).ready(function() {
                $('#user .errorMessage').prepend('$error');
                $('#user').modal('show');
            });
            </script>";
  } else {
    //check if receiver number is same as sender number
    $receiver_userid = getVal($connection, "user_id", "tblacc", "phonenumber", $receiver_mobileNum);
    if ($receiver_userid == $sender_userid) {
      $error = "You cannot send to yourself.";
      echo "<script language='javascript'>
                  $(document).ready(function() {
                  $('#user .errorMessage').prepend('$error');
                  $('#user').modal('show');
              });
              </script>";
    } else {

      // Mobile exists, check if sender balance is sufficient
      $sender_balance = getVal($connection, "balance", "tblwallet", "wallet_id", $wallet_id);
      
      // Compare balance with the amount being sent
      if ($sender_balance < $amount) {
        // Insufficient balance
        $error = "Insufficient balance. ";
        echo "<script language='javascript'>
                $(document).ready(function() {
                $('#user .errorMessage').prepend('$error');
                $('#user').modal('show');
            });
            </script>";
      } else {
        // Sufficient balance, proceed with the transaction

        //check if max balance of receiver will exceed if sent

        //get current receiver balance
        $receiver_accid = getVal($connection, "account_id", "tblacc", "phonenumber", $receiver_mobileNum);

        $receiver_total_balance = getAccBalance($connection, $receiver_accid);

        if ($receiver_total_balance + $amount > getAccMaxBalance($connection, $receiver_accid)) {
          $error = "TRANSACTION UNSUCCESSFUL! Receiver exceeds maximum balance of their account type.";
          echoMessage("user", "errorMessage", $error);
        } else {




          // Update sender's balance
          
          $sql = "Select balance from tblwallet where wallet_id = ".$wallet_id." and account_id = " . $sender_accid;
          $retval = mysqli_query($connection, $sql);
          $sender_main_balance;
          if (mysqli_num_rows($retval) > 0) {
            $row = mysqli_fetch_assoc($retval);
            $sender_main_balance = $row["balance"];
          }

          $new_sender_main_balance = $sender_main_balance - $amount;

          //update sender's main wallet
          updateVal($connection, "balance", $new_sender_main_balance, "tblwallet", "wallet_id", $wallet_id);

          //update account's total balance
          updateAccTotalBalance($connection, $sender_accid);


          //update receiver balance;
          $sql = "Select balance, wallet_id from tblwallet where name = 'Main' and account_id = " . $receiver_accid;
          $receiver_main_balance;
          $receiver_main_wallet_id;
          $retval = mysqli_query($connection, $sql);
          if (mysqli_num_rows($retval) > 0) {
            $row = mysqli_fetch_assoc($retval);
            $receiver_main_balance = $row["balance"];
            $receiver_main_wallet_id = $row["wallet_id"];
          }


          $new_receiver_main_balance = $receiver_main_balance + $amount;

          //update receiver's main wallet
          updateVal($connection, "balance", $new_receiver_main_balance, "tblwallet", "wallet_id", $receiver_main_wallet_id);


          updateAccTotalBalance($connection, $receiver_accid);
          $receiver_name = getUserName($connection, $receiver_accid);
          $sender_name = getUserName($connection,$sender_accid);




          // Your transaction logic here
          $success = "Successfully sent ".$amount." to ". $receiver_name.".";
          $receiver_notif = "You have received ".$amount." from ".$sender_name;

          // echoMessage("success", "successMessage", $success);
          pushNotification($connection,$sender_accid,$success);
          pushNotification($connection,$receiver_accid,$receiver_notif);
          

          $sql = "Insert into tbltransaction(sender_id,receiver_id,amount) values('" . $sender_accid . "','" . $receiver_accid . "','" . $amount . "')";
          mysqli_query($connection, $sql);
        }
      }
    }
  }
}

if (isset($_POST['btnTransfer'])) {
  //error checker;
  $user_id = $_SESSION["user_id"];
  $acc_id = getVal($connection, "account_id", "tblacc", "user_id", $user_id);
  $wallet_id = $_POST['wallet_id']; 
  $new_wallet_name = $_POST['walletName'];
  $new_wallet_id = getVal($connection, "wallet_id", "tblwallet", "name", $new_wallet_name);
  $amount = $_POST['sendAmount']; 

      // Mobile exists, check if sender balance is sufficient
      $wallet_balance = getVal($connection, "balance", "tblwallet", "wallet_id", $wallet_id);
      
      // Compare balance with the amount being sent
      if ($wallet_balance < $amount) {
        // Insufficient balance
        $error = "Insufficient wallet balance. ";
            echoMessage("user","errorMessage", $error);
      } else {
        
          // Update sender's balance
          $new_wallet_balance = $wallet_balance - $amount;

          //update sender's main wallet
          updateVal($connection, "balance", $new_wallet_balance, "tblwallet", "wallet_id", $wallet_id);
          $new_wallet_receiver_balance;
          //update the new wallet
          $wallet_receiver_balance = getVal($connection, "balance", "tblwallet", "wallet_id", $new_wallet_id);
          $new_wallet_receiver_balance = $wallet_receiver_balance + $amount;
          updateVal($connection, "balance", $new_wallet_receiver_balance, "tblwallet", "wallet_id", $new_wallet_id);

          $new_wallet_name = getWalletName($connection,$new_wallet_id);


          // Your transaction logic here
          $success = "Transfer to wallet ". $new_wallet_name." was successful.";
          // echoMessage("success", "successMessage", $success);
          pushNotification($connection, $acc_id,$success);

          // $sql = "Insert into tbltransaction(sender_id,receiver_id,amount) values('" . $acc_id . "','" . $acc_id . "','" . $amount . "')"; no transaction if self
          //mysqli_query($connection, $sql);
        }
      }
  


?>