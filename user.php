<?php
include("includes/header.php");

?>

<table class="table table-dark table-hover">
  <thead>
    <tr>
      <th scope="col">User ID</th>
      <th scope="col">Name</th>
      <th scope="col">Email</th>
      <th scope="col">Birthdate</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $success = "";
    // Check connection
    if (!$connection) {
      die("Connection failed: " . mysqli_connect_error());
    }

    // Query to select all data from example_table
    $sql = "SELECT * FROM tbluser";
    $result = mysqli_query($connection, $sql);

    // Display data in table rows
    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["user_id"] . "</td>";
        echo "<td>" . $row["name"] . "</td>";
        echo "<td>" . $row["email"] . "</td>";
        echo "<td>" . $row["birthdate"] . "</td>";
        echo '<td><button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#updateUser_' . $row["user_id"] . '">Update</button>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUser_' . $row["user_id"] . '">Delete</button>
                

                <div class="modal fade" id="deleteUser_' . $row["user_id"] . '" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="exampleModalLabel" style="color:black;">Are you sure you want to delete this user?</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <form method="post" id="deleteUser">
                    <input type="number" class="form-control" aria-describedby="basic-addon1" name="userID" id="userID" value="' . $row["user_id"] . '" required hidden>
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


                <div class="modal fade" id="updateUser_' . $row["user_id"] . '" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="exampleModalLabel" style="color:black;">Update User Details</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <form method="post" id="updateUser_' . $row["user_id"] . '">
                      <input type="number" class="form-control" aria-describedby="basic-addon1" name="userID" id="userID" value="' . $row["user_id"] . '" required hidden>
                        <div class="input-group mb-3">
                          <span class="input-group-text" id="basic-addon1">Name</span>
                          <input type="text" class="form-control" value="' . $row["name"] . '"  aria-describedby="basic-addon1" name="updatedName" id="updatedName">
                        </div>
                        <div class="input-group mb-3">
                          <span class="input-group-text" id="basic-addon1">Birthdate</span>
                          <input type="date" class="form-control" value="' . $row["birthdate"] . '"
                          aria-describedby="basic-addon1" name="updatedBdate" id="updatedPhone" >
                        </div>
                        <div class="input-group mb-3">
                          <span class="input-group-text" id="basic-addon1">Email</span>
                          <input type="text" class="form-control" 
                          value="' . $row["email"] . '"
                          aria-describedby="basic-addon1" name="updatedEmail" id="updatedEmail">
                        </div>
              
              
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <input class="btn btn-warning" type="submit" value="Update" name="btnUpdate">
                        </div>
              
              
              
                      </form>
                      </div>
                      <div class="modal-footer">
                        <?php include("includes/footer2.php"); ?>
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
    if (isset($_POST['btnDelete'])) {
      $userID = $_POST['userID'];
      $sql = "DELETE FROM tbluser WHERE user_id='" . $userID . "'";

      // Execute the query
      if ($connection->query($sql) === TRUE) {
        $success = "Successfully deleted user.";
        echoMessage("successUpdate", "successMessage", $success);
      } else {
        echo "Error deleting record: " . $connection->error;
      }
    }

    if (isset($_POST['btnUpdate'])) {
      $user_id = $_POST['userID'];
      $name = $_POST['updatedName'];
      $email = $_POST['updatedEmail'];
      $date = $_POST["updatedBdate"];

      $prev_name = getVal($connection, "name", "tbluser", "user_id", $user_id);
      $prev_email = getVal($connection, "email", "tbluser", "user_id", $user_id);
      $prev_bdate = getVal($connection, "birthdate", "tbluser", "user_id", $user_id);


      updateVal($connection, "name", $name, "tbluser", "user_id", $user_id);
      updateVal($connection, "email", $email, "tbluser", "user_id", $user_id);
      updateVal($connection, "birthdate", $date, "tbluser", "user_id", $user_id);

      if ($prev_name === $name && $prev_email === $email && $date === $prev_bdate) {
        $success = "Nothing is updated.";
        echoMessage('user', 'errorMessage', $success);
      } else if (mysqli_query($connection, $sql)) {
        $success = "Successfully updated user.";
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
  <?php include("includes/footer2.php"); ?>
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