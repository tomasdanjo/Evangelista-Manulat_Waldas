<div class="modal fade" id="login" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Login</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">Username</span>
                    <input type="text" class="form-control" placeholder="Username" name="userName" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                  
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">Password</span>
                    <input type="password" class="form-control" placeholder="Password" name="inputPass" aria-label="Username" aria-describedby="basic-addon1">
                </div>
        
                <input class="btn btn-primary" type="submit" value="Log In" name="btnLogin" style="float: right;">
                <p>Don't have an account yet? <a data-bs-dismiss="modal"  data-bs-toggle="modal" data-bs-target="#register" href="#">Register</a>
                </p>
            </form>
            </div>
            <div class="modal-footer">
                <?php include("includes/footer3.php");?>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="success" data-bs-backdrop="static" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" >
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Success!</h1>
                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
            </div>
            <div class="modal-body" >
            <p class="successMessage"style="display: flex;flex-direction: column;"> Welcome! You have successfully logged in, </p><p>!!</p>
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal" aria-label="continue">Continue</button>
            </div>
            
        </div>
    </div>
</div>

<?php
    if(isset($_POST['btnLogin'])){
		$uname=$_POST['userName'];
		$pword=$_POST['inputPass'];
		//check tbluseraccount if username is existing
		$sql ="Select * from tbluseraccount where username='".$uname."'";
		
		$result = mysqli_query($connection,$sql);	
		
		$count = mysqli_num_rows($result);
			
		if ($count == 0) {
            // Username does not exist
            $error="Username not found. ";
            $error.='Do you want to register instead? <a data-bs-dismiss="modal"  data-bs-toggle="modal" data-bs-target="#register" href="#">Register</a>';
                echo "<script language='javascript'>
                    $(document).ready(function() {
                    $('#user .errorMessage').prepend('$error');
                    $('#user').modal('show');
                });
                </script>";
        } else {
            // Username exists, fetch the user's data
            $row = mysqli_fetch_assoc($result);
        
            // Verify the password
            if (password_verify($pword, $row['password'])) {
                // Password is correct

                // Set session variables
                $_SESSION['username'] = $row['username'];

                echo "<script language='javascript'>
                    $(document).ready(function() {
                    $('#success .successMessage').append('$uname');
                    $('#success').modal('show');
                    });
                    </script>";
            } else {
                // Password is incorrect
                $error = "Password is incorrect. ";
                $error.='Do you want to try again? <a data-bs-dismiss="modal"  data-bs-toggle="modal" data-bs-target="#login" href="#">Log In</a>';
                echo "<script language='javascript'>
                    $(document).ready(function() {
                    $('#user .errorMessage').prepend('$error');
                    $('#user').modal('show');
                });
                </script>";

            }
        }
	}
?>