<?php
    include("includes/header.php");
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
                if(isset($_SESSION['username'])) {
                    echo "328.92";
                } else {
                    echo "***.**";
                }    
                ?>
                    </b></p>
            </div>
            <div class="acctNum"><h2>
                <?php
                if(isset($_SESSION['username'])) {
                    $username = $_SESSION['username'];
                    echo $username;
                    echo "</br><a href='logout.php'>Logout</a>";
                } else {
                    echo "****   ****   ****   ****";
                }
                ?>
            </h2></div>
            <div class="space"></div>
        </div>
    </div>
    

    <?php
    include("includes/footer1.php");
?>

