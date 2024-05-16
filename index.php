<?php
include("includes/header.php");
include("actions/send_money.php");
?>

<div class="centercard">
    <div class="desc">
        <h2>Make your life easier.</h2>
        <?php
            if(isset($_SESSION['username'])){
                include_once('actions/create_wallet.php');
                echo '<btn class="btn btnCreate" data-bs-toggle="modal" data-bs-target="#create-wallet">Create Wallet</button>';
            }else{
                echo '<p>Simplify your transactions, send money in seconds, and enjoy unparalleled convenience. Waldas - Where convenience meets innovation.</p>';
            }
            ?>
        
    </div>
    <div class="cardsContainer">
        <?php
        if (isset($_SESSION['user_id'])) {
            $username = $_SESSION["username"];
            $user_id = $_SESSION["user_id"];
            $acc_id = getVal($connection, "account_id", "tblacc", "user_id", $user_id);
            $row=getAll($connection,"tblwallet","account_id",$acc_id);
            
            if($row){
                if(getNumberOfWallet($connection, $acc_id)>1){
                    echo'<div class="scrolling-wrapper">';
                    while ($res = mysqli_fetch_assoc($row)) {
                        echo'
                                <div class="waldasCard">
                                    <div class="container">';
                                    if($res["name"]!="Main"){
                                        echo '<button class="btnDelete" data-bs-toggle="modal" data-bs-target="#deleteWallet'.$res["wallet_id"].'">X</button>';
                                    }
                                        echo '<h3><b style="margin-left: 10px">W</b></h3>
                                        <p><b>'.$res["name"].'</b></p>
                                    </div>
                                    <div class="acctNum">
                                        <h2>';
                                echo '<div class="acctNum">PHP '.$res["balance"].'</div>';
                                echo '</br><button class="btnTransfer" data-bs-toggle="modal" data-bs-target="#transferMoney'.$res["wallet_id"].'">Transfer Funds</button>';
                                echo '<span style="margin-left:10px"><button class="btnTransfer" id="send" data-bs-toggle="modal" data-bs-target="#sendMoney'.$res["wallet_id"].'">Send Money</button></span>';
                                echo '</h2>
                                </div>
                                <div class="space"></div>
                            </div>
                            <div class="modal fade" id="sendMoney'.$res["wallet_id"].'" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Send Money</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post" id="sendMoneyForm">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1">Mobile Number | +63</span>
                                            <input type="text" class="form-control" placeholder="..." aria-describedby="basic-addon1" name="mobileNum" id="mobileNum" required maxlength="10">
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1">Amount in PHP</span>
                                            <input type="number" class="form-control" placeholder="0" aria-describedby="basic-addon1" name="sendAmount" id="sendAmount" required="">
                                        </div>
                                        <input type="number" class="form-control" value="'.$res["wallet_id"].'" aria-describedby="basic-addon1" name="wallet_id" id="wallet_id" hidden>
                                        <input class="btn btn-primary" type="submit" value="Send" name="btnSend">
                
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <?php include("includes/footer1.php"); ?>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <!--Transfer Funds-->
                            <div class="modal fade" id="transferMoney'.$res["wallet_id"].'" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Transfer Funds To Another Wallet</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post" id="transferMoneyForm">
                                        <div class="input-group mb-3">
                                        <label class="input-group-text" for="walletName">Choose Wallet</label>
                                        <select class="form-select" id="walletName" name="walletName">';
                                        $wallets=getAll($connection,"tblwallet","account_id",$acc_id);
                                        if ($wallets) { 
                                            while ($walletsRes = mysqli_fetch_assoc($wallets)) {
                                                if($walletsRes["name"]!=$res["name"]){
                                                    echo '<option value="'.$walletsRes["name"].'">'.$walletsRes["name"].'</option>';
                                                }
                                            }
                                        }
                                echo    '</select>
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1">Amount in PHP</span>
                                            <input type="number" class="form-control" placeholder="0" aria-describedby="basic-addon1" name="sendAmount" id="sendAmount" required="">
                                        </div>
                                        <input type="number" class="form-control" value="'.$res["wallet_id"].'" aria-describedby="basic-addon1" name="wallet_id" id="wallet_id" hidden>
                                        <input class="btn btn-primary" type="submit" value="Transfer" name="btnTransfer">
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <?php include("includes/footer1.php"); ?>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            ';
                    }
                    echo'<div style="margin-bottom:50px"></div>
                    </div>';
                }else{
                    $res = mysqli_fetch_assoc($row);
                        echo '<div class="waldasCard">
                                    <div class="container">
                                        <h3><b style="margin-left: 10px">W</b></h3>
                                        <p>PHP<b>'.$res["balance"].'</b></p>
                                    </div>
                                    <div class="acctNum">
                                        <h2>';
                                echo '<div class="acctNum">'.$res["name"].'</div>';
                                echo '</br>';
                                echo '<span style="margin-left:10px"><button class="btnTransfer" id="send" data-bs-toggle="modal" data-bs-target="#sendMoney'.$res["wallet_id"].'">Send Money</button></span>';
                                echo '</h2>
                                </div>
                                <div class="space"></div>
                            </div>
                            <div class="modal fade" id="sendMoney'.$res["wallet_id"].'" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Send Money</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post" id="sendMoneyForm">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1">Mobile Number | +63</span>
                                            <input type="text" class="form-control" placeholder="..." aria-describedby="basic-addon1" name="mobileNum" id="mobileNum" required maxlength="10">
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1">Amount in PHP</span>
                                            <input type="number" class="form-control" placeholder="0" aria-describedby="basic-addon1" name="sendAmount" id="sendAmount" required="">
                                        </div>
                                        <input type="number" class="form-control" value="'.$res["wallet_id"].'" aria-describedby="basic-addon1" name="wallet_id" id="wallet_id" hidden>
                                        <input class="btn btn-primary" type="submit" value="Send" name="btnSend">
                
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <?php include("includes/footer1.php"); ?>
                                    </div>
                                    </div>
                                </div>
                            </div>
                         <div style="margin-bottom:50px"></div>';
                }
            }else {
                echo "No wallet found.";
            }

             
        }else{
            echo'<div class="waldasCard">
                    <div class="container">
                        <h3><b style="margin-left: 10px">W</b></h3>
                        <p>PHP_<b> ***.**</b></p>
                    </div>
                    <div class="acctNum">
                        <h2>************</h2>
                    </div>
                </div>';
        }
        ?>
        
    </div>


</div>

<?php
    $acc_type = getAccType($connection,$acc_id);
    if($acc_type!="Full"){
        echo '<form method="POST">
        <input type="submit" value="Verify Account" name="verifyBtn">
    </form>';
    }

    if(isset($_POST["verifyBtn"])){
        echoMessage("success","successMessage", "Verify Button Clicked");
    }

?>


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

include("includes/footer1.php");
?>