<?php
// include_once("connect.php");
include("includes/header.php");

?>

<h2 class="hcenter">Report 1</h2>
<table class="table table-dark table-hover">
  <thead>
    <tr>
      <th scope="col">Total Transactions atleast 1000</th>
    </tr>
  </thead>
  <tbody>
    <?php

    //total number of transaction exceeding 1000
    $success = "";
    // Check connection
    if (!$connection) {
      die("Connection failed: " . mysqli_connect_error());
    }

    // Query to select amount from table transaction
    $sql = "SELECT COUNT(amount) as totalNumber FROM tbltransaction where amount>=1000";
    $result = mysqli_query($connection, $sql);

    // Display data in table rows
    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      echo "<td>" . $row["totalNumber"] . "</td>";
    }
    ?>
  </tbody>
</table>
<!-- username and phone number -->
<h2 class="hcenter">Report 2</h2>
<table class="table table-dark table-hover">
  <thead>
    <tr>
      <th scope="col">Name</th>
      <th scope="col">Phone number</th>
    </tr>
  </thead>
  <tbody>

    <?php
    $sql = 'Select user_id from tblacc';
    $result = mysqli_query($connection, $sql);

    $str = "";
    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        //get phone number
        $user_id = $row["user_id"];
        $name = getVal($connection, "name", "tbluser", "user_id", $user_id);
        $phone = getVal($connection, "phonenumber", "tblacc", "user_id", $user_id);
        $str .= "<tr>";
        $str .= "<td>" . $name . "</td>";
        $str .= "<td>" . $phone . "</td>";
        $str .= "</tr>";
      }
      echo $str;
    }
    ?>
  </tbody>
</table>

<!-- //number of wallets per user -->

<h2 class="hcenter">Report 3</h2>

<table class="table table-dark table-hover">
  <thead>
    <tr>
      <th scope="col">Name</th>
      <th>Number of Wallets</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $sql = 'Select account_id from tblacc';
    $result = mysqli_query($connection, $sql);
    $str = "";
    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        $account_id = $row["account_id"];
        $user_id = getVal($connection, "user_id", "tblacc", "account_id", $account_id);
        $name = getVal($connection, "name", "tbluser", "user_id", $user_id);

        $sql = "Select count(account_id) as numWallets from tblwallet where account_id = " . $account_id;
        $res = mysqli_query($connection, $sql);
        $res1 = mysqli_fetch_assoc($res);
        $str .= "<tr>";
        $str .= "<td>" . $name . "</td>";
        $str .= "<td>" . $res1["numWallets"] . "</td>";
        $str .= "</tr>";
      }

      echo $str;
    }

    ?>
  </tbody>
</table>