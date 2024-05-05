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
        echo '<td><button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#updateWallet_' . $row["wallet_id"] . '">Update</button>';

        if ($row["name"] !== "Main") {
          echo '<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteWallet_' . $row["wallet_id"] . '">Delete</button></td>';
        }



        echo '<div class="modal fade" id="updateWallet_' . $row["wallet_id"] . '" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="exampleModalLabel" style="color:black;">Update Wallet Details</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>


                   <div class="modal-body">
                      <form method="post" id="updateWallet_' . $row["wallet_id"] . '">
                      <input type="number" class="form-control" aria-describedby="basic-addon1" name="wallet_id" id="wallet_id" value="' . $row["wallet_id"] . '" required hidden>
                        <div class="input-group mb-3">
                          <span class="input-group-text" id="basic-addon1">Walllet Owner</span>
                          <input type="text" class="form-control" value="' . $name . '"  aria-describedby="basic-addon1" name="updateWalletOwner" id="updateWalletOwner" disabled>
                        </div>

                        <div class="input-group mb-3">
                          <span class="input-group-text" id="basic-addon1">Wallet Name</span>
                          <input type="text" class="form-control" value="' . $row["name"] . '"  aria-describedby="basic-addon1" name="updateWalletName" id="updateWalletName"';
        if ($row["name"] === "Main") {
          echo 'disabled';
        }

        echo '>
                        </div>

                        <div class="input-group mb-3">
                          <span class="input-group-text" id="basic-addon1">Wallet Balance</span>
                          <input type="number" class="form-control" value="' . $row["balance"] . '"
                          aria-describedby="basic-addon1" name="updateBalance" id="updateBalance" >
                        </div>
                        
            
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <input class="btn btn-warning" type="submit" value="Update" name="btnUpdate">
                        </div>
              
              
              
                      </form>
                      </div>
                      <div class="modal-footer">
                        <?php include("includes/footer1.php"); ?>
                      </div>
                    </div>
                  </div>
                </div>
        
        
                </td>';



        echo "</tr>";
      }
    } else {
      echo "<tr><td colspan='5'>No data found</td></tr>";
    }

    if (isset($_POST["btnUpdate"])) {
      $wallet_id = $_POST["wallet_id"];

      $wallet_name = isset($_POST["updateWalletName"]) ? $_POST["updateWalletName"] : "";

      $wallet_balance = $_POST["updateBalance"];

      $prev_walllet_balance = getVal($connection, "balance", "tblwallet", "wallet_id", $wallet_id);

      $prev_walllet_name = $wallet_name !== "" ? getVal($connection, "name", "tblwallet", "wallet_id", $wallet_id) : "";

      if ($wallet_name !== "") updateVal($connection, "name", $wallet_name, "tblwallet", "wallet_id", $wallet_id);

      updateVal($connection, "balance", $wallet_balance, "tblwallet", "wallet_id", $wallet_id);

      $acc_id = getVal($connection, "account_id", "tblwallet", "wallet_id", $wallet_id);
      updateAccTotalBalance($connection, $acc_id);





      if ($prev_walllet_balance === $wallet_balance && $prev_walllet_name === $wallet_name) {
        $success = "Nothing is updated.";
        echoMessage('user', 'errorMessage', $success);
      } else if (mysqli_query($connection, $sql)) {
        $success = "Successfully updated wallet.";
        echoMessage('successUpdate', 'successMessage', $success);
      } else {
        echo "Error updating record: " . mysqli_error($connection);
      }
    }
    // Close connection
    mysqli_close($connection);
    ?>
  </tbody>
</table>



<div>
  <?php include("includes/footer3.php"); ?>
</div>

<div class="modal fade" id="successUpdate" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Success!</h1>
        <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
      </div>
      <div class="modal-body">
        <p class="successMessage"></p>
        <form method="post" id="close">
          <input type="submit" class="btn btn-primary" value="Close" name="btnClose">
        </form>

      </div>

    </div>
  </div>
</div>