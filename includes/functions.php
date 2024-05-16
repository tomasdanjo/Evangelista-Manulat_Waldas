<?php

include("connect.php");


function valueExists($connection, $table, $column, $value)
{
  $sql = "Select * from " . $table . " where " . $column . "='" . $value . "'";
  $result = mysqli_query($connection, $sql);
  $row_count = mysqli_num_rows($result);
  return $row_count > 0;
}

function createUser($connection, $email, $name, $bdate)
{
  //check if user email already exists;
  if (valueExists($connection, "tbluser", "email", $email)) {
    return -1;
  }
  $sql = "Insert into tbluser(email,name,birthdate) values('" . $email . "','" . $name . "','" . $bdate . "')";
  mysqli_query($connection, $sql);
  return getVal($connection, "user_id", "tbluser", "email", $email);
}

function createAcc($connection, $user_id, $phone, $hashed_password)
{
  $sql = "Insert into tblacc(user_id,phonenumber,acc_password) values('" . $user_id . "','" . $phone . "','" . $hashed_password . "')";

  mysqli_query($connection, $sql);
  $acc_id = getVal($connection, "account_id", "tblacc", "phonenumber", $phone);

  createWallet($connection, $acc_id, "Main");

  return $acc_id;
}

function createWallet($connection, $acc_id, $name)
{

  //check if walletname already exists

  $sql = "Select wallet_id from tblwallet where account_id = " . $acc_id . " and name = '" . $name . "'";
  $retval = mysqli_query($connection, $sql);
  if (mysqli_num_rows($retval) > 0) return -1;


  $sql = "Insert into tblwallet(account_id,name) values (" . $acc_id . ",'" . $name . "')";
  mysqli_query($connection, $sql);

  return getVal($connection, "wallet_id", "tblwallet", "account_id", $acc_id);
}

function getAll($connection, $table, $column, $value)
{
  $sql = "Select * from " . $table . " where " . $column . " = '" . $value . "'";
  $retval = mysqli_query($connection, $sql);
  if (!$retval) {
    die('Error: ' . mysqli_error($connection));
  }
  if (mysqli_num_rows($retval) > 0) {
    return $retval;
  } else {
  
    return null;
  }
}

function getVal($connection, $desired_val, $table, $column, $value)
{
  $sql = "Select " . $desired_val . " from " . $table . " where " . $column . " = '" . $value . "'";
  $retval = mysqli_query($connection, $sql);
  if (!$retval) {
    // Error handling if the query fails
    die('Error: ' . mysqli_error($connection));
  }
  if (mysqli_num_rows($retval) > 0) {
    // Fetch the user_id
    $row = mysqli_fetch_assoc($retval);
    return $row[$desired_val];
  } else {
    // If no rows are returned, return null or handle it as appropriate for your application
    return null;
  }
}

function updateVal($connection, $setValCol, $setVal, $table, $column, $columnvalue)
{
  $sql = "Update " . $table . " Set " . $setValCol . " = '" . $setVal . "' Where " . $column . " = " . "'" . $columnvalue . "'";

  mysqli_query($connection, $sql);
}

function updateAccTotalBalance($connection, $acc_id)
{
  $sql = "Select sum(balance) as balance from tblwallet where account_id = " . $acc_id;
  $retval = mysqli_query($connection, $sql);

  $total_balance = "";

  if (mysqli_num_rows($retval) > 0) {
    $row = mysqli_fetch_assoc($retval);
    $total_balance = $row["balance"];
  }

  updateVal($connection, "total_balance", $total_balance, "tblacc", "account_id", $acc_id);
}

function echoMessage($modalID, $messageClass, $message)
{
  echo "<script language='javascript'>
                $(document).ready(function() {
                $('#$modalID .$messageClass').append('$message');
                $('#$modalID').modal('show');
                });
                </script>";
}

function walletnameExists($connection, $acc_id, $name)
{
  $sql = "Select wallet_id from tblwallet where name = '" . $name . "' and account_id = " . $acc_id;

  $retval = mysqli_query($connection, $sql);
  $row_count = mysqli_num_rows($retval);
  return $row_count > 0;
}

function getMainWalletID($connection, $wallet_id)
{
  $acc_id = getVal($connection, "account_id", "tblwallet", "wallet_id", $wallet_id);

  $sql = "Select wallet_id from tblwallet where name = 'Main' and account_id=" . $acc_id;
  $result = mysqli_query($connection, $sql);
  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    return $row["wallet_id"];
  } else {
    return -1;
  }
}

function getNumberOfWallet($connection, $acc_id)
{
  $sql = "Select count(account_id) as numOfWallets from tblwallet where account_id=" . $acc_id;
  $result = mysqli_query($connection, $sql);
  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    return $row["numOfWallets"];
  } else {
    return -1;
  }
}

function getAccType($connection, $acc_id)
{
  $acc_type = getVal($connection, "acc_type", "tblacc", "account_id", $acc_id);
  return $acc_type;
}

function getAccMaxBalance($connection, $acc_id)
{
  $acc_type = getAccType($connection, $acc_id);
  $max_balance = getVal($connection, "max_balance", "tblaccounttypes", "acctypeid", $acc_type);
  return $max_balance;
}

function getAccBalance($connection, $acc_id)
{
  $total_balance = getVal($connection, "total_balance", "tblacc", "account_id", $acc_id);
  return $total_balance;
}

function upgradeAccToPartial($connection, $acc_id)
{
  updateVal($connection, "acc_type", "Partial", "tblacc", "account_id", $acc_id);
}

function upgradeAccToFull($connection, $acc_id)
{
  updateVal($connection, "acc_type", "Full", "tblacc", "account_id", $acc_id);
}

function pushNotification($connection, $to_acc_id, $message){
  $sql = "Insert into tblnotifs(account_id, message) values ('".$to_acc_id."','".$message."')";
  return mysqli_query($connection, $sql);
}

function getWalletName($connection, $wallet_id){
  return getVal($connection, "name","tblwallet","wallet_id",$wallet_id);
}

function getWalletBalance($connection,$wallet_id){
  return getVal($connection, "balance","tblwallet","wallet_id",$wallet_id);
}

function getUserName($connection,$acc_id){
  $user_id = getVal($connection,"user_id","tblacc","account_id",$acc_id);
  return getVal($connection,"name","tbluser","user_id",$user_id);
}

function deleteWallet($connection,$wallet_id){
  $wallet_balance = getVal($connection,"balance","tblwallet","wallet_id",$wallet_id);
  $sql = "Delete from tblwallet where wallet_id=" . $wallet_id . "";
  
  if ($connection->query($sql) === TRUE) {
    $success = "Successfully deleted wallet.";
    echoMessage("successUpdate", "successMessage", $success);
  }else{
    echo "Error deleting record: " . $connection->error;
  }
  return $wallet_balance;
}

function readAllNotifs($connection,$acc_id){
  $sql = "Select notif_id from tblnotifs where account_id = ".$acc_id;
  $result = mysqli_query($connection,$sql);
  while($row =  mysqli_fetch_assoc($result)){
    $notif_id = $row["notif_id"];
    readNotif($connection,$notif_id);
  }
}

function readNotif($connection,$notif_id){
  $sql = "UPDATE tblnotifs set isRead = 1 where notif_id = ".$notif_id;
  return mysqli_query($connection,$sql);
}

