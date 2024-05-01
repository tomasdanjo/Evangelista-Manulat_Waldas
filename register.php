<div class="modal fade" id="register" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Create a wallet now!</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="myRegistration">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Number: +63</span>
                        <input type="text" class="form-control" placeholder="xxx xxx xxxx" aria-describedby="basic-addon1" name="mobileNum" pattern="[0-9]{10}" required="">
                    </div>
                    <!-- <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">Username</span>
                    <input type="text" class="form-control" placeholder="Username"  aria-describedby="basic-addon1"  name="userName" id="userName" required="">
                </div> -->


                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Password</span>
                        <input type="password" class="form-control" placeholder="Password" aria-describedby="basic-addon1" name="inputPass" id="inputPass" required="">
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Confirm Password</span>
                        <input type="password" class="form-control" placeholder="Confirm Password" aria-describedby="basic-addon1" name="inputPass1" id="inputPass1" required="">
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">First name</span>
                        <input type="text" class="form-control" placeholder="Juan" aria-describedby="basic-addon1" name="firstName" id="firstName" required="">
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Last Name</span>
                        <input type="text" class="form-control" placeholder="Dela Cruz" aria-describedby="basic-addon1" name="lastName" id="lastName" required="">
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Email</span>
                        <input type="email" class="form-control" placeholder="example@example.com" aria-describedby="basic-addon1" name="inputEmail" id="inputEmail" required="">
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Birthdate</span>
                        <input type="date" class="form-control" aria-describedby="basic-addon1" name="inputBday" id="inputBday" required="">
                    </div>

                    <input class="btn btn-primary" type="submit" value="Register" name="btnRegister" style="float: right;">
                    <p>Already have an account? <a data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#login" href="#">Log In</a></p>
                </form>

            </div>
            <div class="modal-footer">
                <?php include("includes/footer2.php"); ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="user" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Error!</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="errorMessage"> </p>
            </div>

        </div>
    </div>
</div>


<?php
include_once("includes/functions.php");
$error = "";
$success = "";
if (isset($_POST['btnRegister'])) {
    //error checker;

    //retrieve data from form and save the value to a variable
    //for tbluserprofile
    $fname = $_POST['firstName'];    //error 2	
    $lname = $_POST['lastName'];  //error 3
    $bday = $_POST['inputBday'];
    $phone = $_POST['mobileNum'];

    //for tbluseraccount
    $email = $_POST['inputEmail']; //error 4	
    // $uname = $_POST['userName']; //error 1
    $pword = $_POST['inputPass'];

    $name = $fname . " " . $lname;



    //hash pw
    $hashed_pword = password_hash($pword, PASSWORD_DEFAULT);



    // Function to check if a value exists in a table


    // Check if username already exists
    // $usernameExists = valueExists($connection, 'tbluseraccount', 'username', $uname);

    $phonenumberExists  = valueExists($connection, 'tblacc', 'phonenumber', $phone);

    // // Check if first name already exists
    // $firstnameExists = valueExists($connection, 'tbluserprofile', 'firstname', $fname);

    // // Check if last name already exists
    // $lastnameExists = valueExists($connection, 'tbluserprofile', 'lastname', $lname);

    // Check if email add already exists
    $emailaddExists = valueExists($connection, 'tbluser', 'email', $email);

    if ($phonenumberExists) {
        $error = "Phone Number already exists! ";
    } elseif ($emailaddExists) {
        $error =  "Email already exists! ";
    }

    if ($error) {
        $error .= 'Do you want to login instead? <a data-bs-dismiss="modal"  data-bs-toggle="modal" data-bs-target="#login" href="#">Log In</a>';
        echo "<script language='javascript'>
            $(document).ready(function() {
                $('#user .errorMessage').prepend('$error');
                $('#user').modal('show');
            });
          </script>";
    } else {
        //save data to tbluser			
        $user_id = createUser($connection, $email, $name, $bday);

        if ($user_id == -1) {
            //email already exists
        } else {
            //save to tblacc
            createAcc($connection, $user_id, $phone, $hashed_pword);
        }

        $success = "Welcome! You may now login, " . $name . ". Thanks for joining Waldas.";

        echo "<script language='javascript'>
                    $(document).ready(function() {
                    $('#success .successMessage').append('$success');
                    $('#success').modal('show');
                    });
                    </script>";
    }
}

?>