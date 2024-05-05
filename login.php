<div class="modal fade" id="login" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Login</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1"></span>
                        <input type="text" class="form-control" placeholder="Email or Phone Number" name="emailOrPhone" aria-label="Username" aria-describedby="basic-addon1">
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Password</span>
                        <input type="password" class="form-control" placeholder="Password" name="inputPass" aria-label="Username" aria-describedby="basic-addon1">
                    </div>

                    <input class="btn btn-primary" type="submit" value="Log In" name="btnLogin" style="float: right;">
                    <p>Don't have an account yet? <a data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#register" href="#">Register</a>
                    </p>
                </form>
            </div>
            <div class="modal-footer">
                <?php include("includes/footer3.php"); ?>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="success" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Success!</h1>
                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
            </div>
            <div class="modal-body">
                <p class="successMessage"></p>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" aria-label="continue">Continue</button>
            </div>

        </div>
    </div>
</div>

<?php
include_once("includes/functions.php");


if (isset($_POST['btnLogin'])) {
    $emailOrPhone = $_POST['emailOrPhone'];
    $pword = $_POST['inputPass'];

    $phoneExists = valueExists($connection, "tblacc", "phonenumber", $emailOrPhone);

    $emailExists = valueExists($connection, "tbluser", "email", $emailOrPhone);


    //check if email or phone exists
    if (!$phoneExists && !$emailExists) {
        // Username does not exist
        $error = "Email or Phone not found. ";
        $error .= 'Do you want to register instead? <a data-bs-dismiss="modal"  data-bs-toggle="modal" data-bs-target="#register" href="#">Register</a>';
        echo "<script language='javascript'>
                    $(document).ready(function() {
                    $('#user .errorMessage').prepend('$error');
                    $('#user').modal('show');
                });
                </script>";
    } else {
        // Phone or email exists, fetch the user's data
        $actual_password = "";
        // $user_id = getVal($connection, "user_id", "tbluser", "email", $emailOrPhone);
        $user_id;
        if ($phoneExists) {
            $actual_password =  getVal($connection, "acc_password", "tblacc", "phonenumber", $emailOrPhone);
            $user_id = getVal($connection, "user_id", "tblacc", "phonenumber", $emailOrPhone);
        } else if ($emailExists) {

            $actual_password =  getVal($connection, "acc_password", "tblacc", "user_id", $user_id);

            $user_id = getVal($connection, "user_id", "tbluser", "email", $emailOrPhone);
        }




        // Verify the password
        if (password_verify($pword, $actual_password)) {
            // Password is correct

            // Set session variables
            // $_SESSION['username'] = getVal($connection, "name", "tbluser", "user_id", $user_id);

            $name = getVal($connection, "name", "tbluser", "user_id", $user_id);
            $_SESSION["username"] = $name;
            $_SESSION["user_id"] =  $user_id;

            $success = "Welcome! You have successfully logged in, " .  $name . "!";
            echoMessage("success", "successMessage", $success);
        } else {
            // Password is incorrect
            echoMessage("user", "errorMessage", $error);
        }
    }
}
?>