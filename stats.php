<?php
// include_once("connect.php");
include("includes/header.php");

?>

<table class="table table-dark table-hover">
  <thead>
    <tr>
      <th scope="col">Total Number of Basic Accounts</th>
      <th scope="col">Total Number of Partial Accounts</th>
      <th scope="col">Total Number of Full Accounts</th>
    </tr>
  </thead>
  <tbody>
    <?php


    // Check connection
    if (!$connection) {
      die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT 
                COUNT(CASE WHEN acc_type = 'basic' THEN 1 ELSE 0 END) AS basicAccs,
                COUNT(CASE WHEN acc_type = 'partial' THEN 1 ELSE 0 END) AS partialAccs
            FROM tblacc";
    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      echo "<td>" . $row["basicAccs"] . "</td>";
      echo "<td>" . $row["partialAccs"] . "</td>";
    }

    // $sql = "SELECT COUNT(account_id) as partialAccs FROM tblacc where acc_type = 'partial'";
    // $result = mysqli_query($connection, $sql);

    // if (mysqli_num_rows($result) > 0) {
    //   $row = mysqli_fetch_assoc($result);
    //   echo "<td>" . $row["partialAccs"] . "</td>";
    // }
    ?>
  </tbody>
</table>

<table class="table table-dark table-hover">
  <thead>
    <tr>
      <th scope="col">Average Balance</th>
    </tr>
  </thead>
  <tbody>
    <?php
    // Check connection
    if (!$connection) {
      die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT AVG(total_balance) as averageBalance FROM tblacc";
    $result = mysqli_query($connection, $sql);

    // Display data in table rows
    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      echo "<td>" . $row["averageBalance"] . "</td>";
    }
    ?>
  </tbody>
</table>

<table class="table table-dark table-hover">
  <thead>
    <tr>
      <th scope="col">Average Flow of Money</th>
    </tr>
  </thead>
  <tbody>
    <?php
    // Check connection
    if (!$connection) {
      die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT AVG(amount) as averageFlow FROM tbltransaction";
    $result = mysqli_query($connection, $sql);

    // Display data in table rows
    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      echo "<td>" . $row["averageFlow"] . "</td>";
    }
    ?>
  </tbody>
</table>

<table class="table table-dark table-hover">
  <thead>
    <tr>
      <th scope="col">Total Transactions Made Today</th>
      <th scope="col">Total Transactions Made Yesterday</th>
    </tr>
  </thead>
  <tbody>
    <?php
    // Check connection
    if (!$connection) {
      die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT COUNT(transaction_id) as totalToday FROM tbltransaction where DAY(timestamp) = DAY(CURRENT_DATE())";
    $result = mysqli_query($connection, $sql);

    // Display data in table rows
    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      echo "<td>" . $row["totalToday"] . "</td>";
    }

    $sql = "SELECT COUNT(transaction_id) as totalYesterday FROM tbltransaction where DAY(timestamp) = DAY(CURRENT_DATE()-1)";
    $result = mysqli_query($connection, $sql);

    // Display data in table rows
    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      echo "<td>" . $row["totalYesterday"] . "</td>";
    }
    ?>
  </tbody>
</table>

<table class="table table-dark table-hover">
  <thead>
    <tr>
      <th scope="col">Total Transactions Made This Year</th>
    </tr>
  </thead>
  <tbody>
    <?php
    // Check connection
    if (!$connection) {
      die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT COUNT(transaction_id) as totalToday FROM tbltransaction where Year(timestamp) = Year(CURRENT_DATE())";
    $result = mysqli_query($connection, $sql);

    // Display data in table rows
    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      echo "<td>" . $row["totalToday"] . "</td>";
    }
    ?>
  </tbody>
</table>