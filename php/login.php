<?php
    include 'config.php';
    $account = $_POST['txtacnt'];
    $user = $_POST['txtuser'];
    $pwd = $_POST['txtPwd'];
    //echo $pwd;
    if(isset($account) && isset($user) && isset($pwd)){
         try{
             $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($tacconnect));
             mysqli_select_db($gtsconnect,$gtsdb);
             $divisionquery="select * from User where accountID='$account' and password='$pwd' and userID='$user'";
             if($user=="admin" || $user==""){
                 $divisionquery="select * from Account where accountID='$account' and password='$pwd'";
             }
             $qry1 = mysqli_query($gtsconnect, $divisionquery) or die(mysqli_error());
             $count=0;
             while($row = mysqli_fetch_assoc($qry1)){
                 $count++;
             }
             mysqli_close($gtsconnect);
             if($count>=1){
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
      
             }else{
                 header("Location: ../login.php?errormsg=1");
                 exit;
             }
         }catch(Exception $e){
             header("Location: ../login.php?errormsg=3");
             exit;      
         }
     
    }else{
        header("Location: ../login.php?errormsg=3");
        exit;

    }
