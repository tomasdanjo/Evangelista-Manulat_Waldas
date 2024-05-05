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
