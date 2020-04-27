<?php
    session_start();
    function sessionCheck(){
         $acc=$_SESSION["accountID"];
         $user=$_SESSION["user"];
         $time=abs(time()-$_SESSION["time"])/(60);
         // sesstion time 20 mints 
         if($acc=="" || $user=="" || $time>20 ){
             session_unset();
             session_destroy();
             return false;

          }else{
             $_SESSION["time"]=time();
             return true;
          }
           

    }
  

?>
