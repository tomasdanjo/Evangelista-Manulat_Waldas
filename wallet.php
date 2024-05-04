<?php
include("includes/header.php");
// include('create_wallet.php');

?>

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create-wallet">
  Create Wallet
</button>



<table class="table table-dark table-hover">
  <thead>
    <tr>
      <th scope="col">Wallet ID</th>
      <th scope="col">Wallet Owner</th>
      <th scope="col">Wallet Name</th>
      <th scope="col">Balance</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php

    // Check connection
    if (!$connection) {
      die("Connection failed: " . mysqli_connect_error());
    }

    // Query to select all data from example_table
    $sql = "Select * from tblwallet";
    $result = mysqli_query($connection, $sql);

    // Display data in table rows
    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["wallet_id"] . "</td>";


        $acc_id = $row["account_id"];
        $user_id = getVal($connection, "user_id", "tblacc", "account_id", $acc_id);

        $name = getVal($connection, "name", "tbluser", "user_id", $user_id);

        echo "<td>" . $name . "</td>";
        echo "<td>" . $row["name"] . "</td>";
        echo "<td>" . $row["balance"] . "</td>";
        echo '<td><button type="button" class="btn btn-info">View</button><button type="button" class="btn btn-danger">Delete</button></td>';

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
  <?php include("includes/footer3.php"); ?>
</div>