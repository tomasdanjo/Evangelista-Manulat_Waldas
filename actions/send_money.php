<div class="modal fade" id="sendMoney" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Send Money</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" id="sendMoneyForm">
          <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Mobile Number</span>
            <input type="text" class="form-control" placeholder="09..." aria-describedby="basic-addon1" name="mobileNum" id="mobileNum" required maxlength="10">
          </div>
          <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Amount in PHP</span>
            <input type="number" class="form-control" placeholder="0" aria-describedby="basic-addon1" name="sendAmount" id="sendAmount" required="">
          </div>

          <input class="btn btn-primary" type="submit" value="Send" name="btnSend">

        </form>
      </div>
      <div class="modal-footer">
        <?php include("includes/footer1.php"); ?>
      </div>
    </div>
  </div>
</div>

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
  $receiver_mobileNum = $_POST['mobileNum']; //error 1
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
      $sender_balance = getVal($connection, "total_balance", "tblacc", "user_id", $sender_userid);

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

        // Update sender's balance
        //get sender's main wallet balance
        $sender_accid = getVal($connection, "account_id", "tblacc", "user_id", $sender_userid);
        $sql = "Select balance, wallet_id from tblwallet where name = 'Main' and account_id = " . $sender_accid;
        $retval = mysqli_query($connection, $sql);
        $sender_main_balance;
        $sender_main_wallet_id;
        if (mysqli_num_rows($retval) > 0) {
          $row = mysqli_fetch_assoc($retval);
          $sender_main_balance = $row["balance"];
          $sender_main_wallet_id = $row["wallet_id"];
        }

        $new_sender_main_balance = $sender_main_balance - $amount;

        //update sender's main wallet
        updateVal($connection, "balance", $new_sender_main_balance, "tblwallet", "wallet_id", $sender_main_wallet_id);

        //update account's total balance
        updateAccTotalBalance($connection, $sender_accid);





        //update receiver balance;
        //get current receiver balance
        $receiver_accid = getVal($connection, "account_id", "tblacc", "phonenumber", $receiver_mobileNum);

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



        // Your transaction logic here
        $success = "Transaction successful.";
        echoMessage("success", "successMessage", $success);

        $sql = "Insert into tbltransaction(sender_id,receiver_id,amount) values('" . $sender_accid . "','" . $receiver_accid . "','" . $amount . "')";
        mysqli_query($connection, $sql);
      }
    }
  }
}

?>