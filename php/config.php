<?php

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/usr/local/apache/logs/error_log.txt');
error_reporting(E_ALL);
error_reporting(0);
session_start();
$acc=$_SESSION["accountID"];
if($acc==""){
   $acc=$_POST['txtacnt'];;
}
$tacserver = "localhost";
$tacusername = "root";
$tacpassword = "gl0v1s10n";
$tacdb = "autocare_gts";

if(!isset($_SESSION)) {
    session_start();
}
$gtsserver="localhost";
$gtsusername="root";
$gtspassword="gl0v1s10n";
$gtsdb ="gts";
if($acc=="gvk-up-102"){
     $tacserver = "94.23.211.49";
//$tacserver = "localhost";
$tacusername = "root";
$tacpassword = "gl0v1s10n";
$tacdb = "autocare_gts";

//$gtsserver ="up102.glovision.co";
$gtsserver ="94.23.211.49";
//$gtsserver ="localhost";
$gtsusername ="root";
$gtspassword="gl0v1s10n";
$gtsdb ="gts";
session_write_close();

}



?>
