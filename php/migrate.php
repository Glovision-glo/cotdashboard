<?php
    $account = $_GET['accountID'];
    $user = $_GET['userID'];

    session_start();
    $_SESSION["accountID"]=$account;
    if($user==''){
        $_SESSION["user"]="admin";
    }else{
        $_SESSION["user"]=$user;
    }
    $_SESSION["time"]=time();
    header("Location: ../vehiclesStatus.php");
                 exit;


?>
