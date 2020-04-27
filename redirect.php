<?php
    
    $account = $_GET['accountID'];
    $user = $_GET['userID'];
    //echo $pwd;
    if(isset($account) && isset($user)){
         try{
                 session_start();
                 $_SESSION["accountID"]=$account;
                 if($user==''){
                    $_SESSION["user"]="admin";
                 }else{
                    $_SESSION["user"]=$user;
                 }
                 $_SESSION["time"]=time();
                 header("Location: vehiclesStatus.php");
                 exit;
      
         }catch(Exception $e){
             header("Location: login.php?errormsg=3");
             exit;      
         }
     
    }else{
        header("Location: login.php?errormsg=3");
        exit;

    }
