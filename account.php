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
    $sql = "Select * from tblacc";
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
        $str .= '<td><button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#updateAcc_' . $row["account_id"] . '">Update</button>';

        $str .= '<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAcc_' . $row["account_id"] . '">Delete</button>

        <div class="modal fade" id="deleteAcc_' . $row["account_id"] . '" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="exampleModalLabel" style="color:black;">Are you sure you want to delete this user?</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <form method="post" id="deleteUser">
                    <input type="number" class="form-control" aria-describedby="basic-addon1" name="accID" id="accID" value=' . $row["account_id"] . ' required hidden>
                    <input class="btn btn-danger" type="submit" value="Yes" name="btnDelete">
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
                      <h1 class="modal-title fs-5" id="exampleModalLabel" style="color:black;">Update User Details</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>



                    <div class="modal-body">
                      <form method="post" id="updateAcc_' . $row["account_id"] . '">
                      <input type="number" class="form-control" aria-describedby="basic-addon1" name="accID" id="accID" value="' . $row["account_id"] . '" required hidden>
                        <div class="input-group mb-3">
                          <span class="input-group-text" id="basic-addon1">Account Type</span>
                          <input type="text" class="form-control" placeholder="' . $row["acc_type"] . '"  aria-describedby="basic-addon1" name="updateAccType" id="updateAccType" disabled>
                        </div>

                        <div class="input-group mb-3">
                          <span class="input-group-text" id="basic-addon1">phoneNumber</span>
                          <input type="text" class="form-control" value="' . $row["phonenumber"] . '"  aria-describedby="basic-addon1" name="updatePhone" id="updatePhone" disabled>
                        </div>
                        <div class="input-group mb-3">
                          <span class="input-group-text" id="basic-addon1">Total Balance</span>
                          <input type="number" class="form-control" value="' . $row["total_balance"] . '"
                          aria-describedby="basic-addon1" name="updateBalance" id="updateBalance" disabled >
                        </div>
                        <div class="input-group mb-3">
                          <span class="input-group-text" id="basic-addon1">Link to user Portrait</span>
                          <input type="text" class="form-control" 
                          value="' . $row["user_portrait"] . '"
                          aria-describedby="basic-addon1" name="updatedEmail" id="updatedEmail">
                        </div>
                        <div class="input-group mb-3">
                          <span class="input-group-text" id="basic-addon1">Link to Government ID</span>
                          <input type="text" class="form-control" 
                          value="' . $row["valid_government_id"] . '"
                          aria-describedby="basic-addon1" name="updatedEmail" id="updatedEmail">
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
        $str .= "</tr>";
      }
      echo $str;
    } else {
      echo "<tr><td colspan='7'>No data found</td></tr>";
    }

    if (isset($_POST["btnDelete"])) {
      $accID = $_POST["accID"];
      $sql = "Delete from tblacc where account_id=" . $accID . "";
      if ($connection->query($sql) === TRUE) {
        $success = "Successfully deleted account.";
        echo "<script language='javascript'>
                    $(document).ready(function() {
                    $('#successUpdate .successMessage').append('$success');
                    $('#successUpdate').modal('show');
                    });
                    </script>";
      } else {
        echo "Error deleting record: " . $connection->error;
      }
    }

    ?>
  </tbody>
</table>

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