<?php
include("includes/header.php");
?>

<table class="table table-dark table-hover">
  <thead>
    <tr>
      <th scope="col">Seq Num</th>
      <th scope="col">Sender</th>
      <th scope="col">Receiver</th>
      <th scope="col">Amount</th>
      <th scope="col">Timestamp</th>
    </tr>
  </thead>
  <tbody>
    <?php

    // Check connection
    if (!$connection) {
      die("Connection failed: " . mysqli_connect_error());
    }

    // Query to select all data from example_table
    $sql = "SELECT * FROM tbltransaction";
    $result = mysqli_query($connection, $sql);

    // Display data in table rows
    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["transaction_id"] . "</td>";
        $sender_userId = getVal($connection, "user_id", "tblacc", "account_id", $row["sender_id"]);
        $sender_name = getVal($connection, "name", "tbluser", "user_id", $sender_userId);

        echo "<td>" . $sender_name . "</td>";

        $receiver_userId = getVal($connection, "user_id", "tblacc", "account_id", $row["receiver_id"]);
        $receiver_name = getVal($connection, "name", "tbluser", "user_id", $receiver_userId);


        echo "<td>" . $receiver_name . "</td>";
        echo "<td>" . $row["amount"] . "</td>";
        echo "<td>" . $row["timestamp"] . "</td>";


        echo "</tr>";
      }
    } else {
      echo "<tr><td colspan='5'>No data found</td></tr>";
    }

    // Close connection
    mysqli_close($connection);
    ?>
  </tbody>
</table>

<div>
  <?php include("includes/footer2.php"); ?>
</div>