<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Monoton&family=Shippori+Antique&display=swap" rel="stylesheet">

</head>
<?php
session_start();
include("connect.php");
include("login.php");
include("register.php");
?>

<div class="nav">
    <header>
        Waldas
    </header>
    <a href="index.php" class="nav-link">HOME</a>
    <a href="about.php" class="nav-link">ABOUT</a>
    <a href="contact.php" class="nav-link">CONTACT</a>
    <?php
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        echo '<li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false" id="profileName">
            ' . $username . '</a>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item"  href="logout.php">Logout</a></li>
        </ul>
        </li>';
    } else {
        echo '<li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false" id="profileName">
            JOIN NOW!
        </a>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#login">LOGIN</a></li>
            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#register">REGISTER</a></li>
        </ul>
    </li>';
    }


    if (isset($_SESSION['username']) && ($_SESSION['username']) == "admin admin") {

        echo '
                    <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                        Menu
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="account.php">Account</a></li>
                        <li><a class="dropdown-item" href="user.php">User</a></li>
                        <li><a class="dropdown-item" href="wallet.php">Wallets</a></li>
                        <li><a class="dropdown-item" href="transactions.php">Transactions</a></li>
                        <li><a class="dropdown-item" href="reports.php">Reports</a></li>
                        <li><a class="dropdown-item" href="stats.php">Statistics</a></li>
                        </ul>
                    </li>';
    }
    ?>



</div>

<script src="js/script.js"></script>