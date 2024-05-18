<?php
// include(__DIR__ . "includes\header.php");
// include("connect.php");
if (file_exists('includes/header.php')) {
  include('includes/header.php');
} else {
  echo "Header file not found!";
}
?>

<table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">Account ID</th>
      <th scope="col">Account Type</th>

      <th scope="col">Phone Number</th>
      <th scope="col">Total Balance</th>

      <th scope="col">User Portrait</th>
      <th scope="col">Valid ID</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $sql = "Select * from tblacc where isFrozen = 1";
    $result = mysqli_query($connection, $sql);

    $str = "";

    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        $str .= "<tr>";
        $str .= "<td>" . $row["account_id"] . "</td>";
        $str .= "<td>" . $row["acc_type"] . "</td>";
        $str .= "<td>" . $row["phonenumber"] . "</td>";
        $str .= "<td>" . $row["total_balance"] . "</td>";
        $str .= "<td>" . $row["user_portrait"] . "</td>";
        $str .= "<td>" . $row["valid_government_id"] . "</td>";

        $str .= '<td><button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAcc_' . $row["account_id"] . '">Unfreeze</button>

        <div class="modal fade" id="deleteAcc_' . $row["account_id"] . '" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="exampleModalLabel" style="color:black;">Are you sure you want to unfreeze this account?</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <form method="post" id="deleteUser">
                    <input type="number" class="form-control" aria-describedby="basic-addon1" name="accID" id="accID" value=' . $row["account_id"] . ' required hidden>
                    <input class="btn btn-primary" type="submit" value="Yes" name="btnDelete">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                </form>
                      </div>
                      <div class="modal-footer">
                        <?php include("includes/footer2.php"); ?>
                      </div>
                    </div>
                  </div>
                </div>


                <div class="modal fade" id="updateAcc_' . $row["account_id"] . '" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="exampleModalLabel" style="color:black;">Update Account Details</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>



                    
        
        
        </td>';
        $str .= "</tr>";
      }
      echo $str;
    } else {
      echo "<tr><td colspan='7'>No data found</td></tr>";
    }

    if (isset($_POST["btnDelete"])) {
      $accID = $_POST["accID"];
      $sql = "UPDATE tblacc SET isFrozen = 0 where account_id='" . $accID . "'";
      if ($connection->query($sql) === TRUE) {
        $success = "Successfully unfroze account_id: " . $accID . ".";
        echoMessage("successUpdate", "successMessage", $success);
      } else {
        echo "Error deleting record: " . $connection->error;
      }
    }

    mysqli_close($connection);
    ?>
  </tbody>
</table>
<a class="btn btn-primary" href="account.php">Go to active accounts</a>
<?php

include("includes/footer3.php");
?>

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