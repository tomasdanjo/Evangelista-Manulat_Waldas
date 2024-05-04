<?php
include("includes/header.php");
include("actions/send_money.php");
?>

<div class="centercard">
    <div class="desc">
        <h2>Make your life easier.</h2>
        <p>Simplify your transactions, send money in seconds, and enjoy unparalleled convenience. Waldas - Where convenience meets innovation.</p>
    </div>
    <div class="waldasCard">
        <div class="container">
            <h3><b>Waldas</b></h3>
            <p>PHP<b>
                    <?php
                    if (isset($_SESSION['user_id'])) {
                        $username = $_SESSION["username"];
                        // Find total balance
                        $user_id = $_SESSION["user_id"];
                        $total_balance = getVal($connection, "total_balance", "tblacc", "user_id", $user_id);

                        echo $total_balance;
                        // Close connection
                        mysqli_close($connection);
                    } else {
                        echo "***.**";
                    }
                    ?>
                </b></p>
        </div>
        <div class="acctNum">
            <h2>
                <?php
                if (isset($_SESSION['username'])) {

                    $username = $_SESSION['username'];
                    echo $username;
                    echo '</br><a class="link-underline-light" data-bs-toggle="modal" data-bs-target="#sendMoney">Send Money</a>';
                } else {
                    echo "****   ****   ****   ****";
                }
                ?>
            </h2>
        </div>
        <div class="space"></div>
    </div>
</div>


<?php
include("includes/footer1.php");
?>