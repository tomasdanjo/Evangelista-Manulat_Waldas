<div class="modal fade" id="create-wallet" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Create new wallet now!</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" id="myNewWallet">
          <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Wallet name</span>
            <input type="text" class="form-control" placeholder="wallet name" aria-describedby="basic-addon1" name="wallet_name" id="wallet_name" required="">
          </div>


          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <input class="btn btn-primary" type="submit" value="Create Wallet" name="btnCreate">
          </div>



        </form>

      </div>
      <div class="modal-footer">
        <?php include("includes/footer3.php"); ?>
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
if (isset($_POST['btnCreate'])) {


  if (isset($_SESSION["username"])) {
    $wallet_name = $_POST["wallet_name"];


    $user_id = $_SESSION["user_id"];
    $acc_id = getVal($connection, "account_id", "tblacc", "user_id", $user_id);

    //get user acc type
    $user_acc_type = getVal($connection, "acc_type", "tblacc", "account_id", $acc_id);

    $daily_deposit_limit = getVal($connection, "daily_deposit_limit", "tblaccounttypes", "acctypeid", $user_acc_type);

    $max_balance = getVal($connection, "max_balance", "tblaccounttypes", "acctypeid", $user_acc_type);

    $max_wallet_count = getVal($connection, "max_wallet_count", "tblaccounttypes", "acctypeid", $user_acc_type);


    //count number of wallets a user has given account id
    $user_number_of_wallets =  getNumberOfWallet($connection, $acc_id);

    if ($user_number_of_wallets >= $max_wallet_count) {
      $error = "Maximum number of wallet is reached. ";
      $acc_type = getAccType($connection,$acc_id);
      if($acc_type!="Full"){
        $error.="Verify your account to add more or remove existing ones.";
      }else{
        $error.="Remove existing ones.";
      }

      echoMessage("user", "errorMessage", $error);
      return;
    }



    // Check if wallet already exists 
    $walletExists = valueExists($connection, 'tblwallet', 'name', $wallet_name);

    if ($walletExists) {
      $error = "Cannot have the same wallet name " . $wallet_name . " in the same account hihi ";

      echoMessage("user", "errorMessage", $error);
    } else {
      createWallet($connection, $acc_id, $wallet_name);

      $walletExists = "";

      $success = "Wallet named " . $wallet_name . " was created successfully";
      $error = "";

      // echoMessage("success", "successMessage", $success);
      pushNotification($connection,$acc_id,$success);
    }
  } else {
    $error = "You need to log in first!";
    echoMessage("user", "errorMessage", $error);
  }
  $error = "";
}

?>