<?php
include 'config.php';
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/usr/local/apache/logs/error_log.txt');
error_reporting(E_ALL);
error_reporting(0);
session_start();
$accountID=$_GET['accountID'];
$userID=$_GET['userID'];
$formate=$_GET['formate'];


/*
if($_SESSION['start']== ""){
  $_SESSION['start'] = time(); // Taking now logged in time.
// Ending a session in 30 minutes from the starting time.
  $_SESSION['expire'] = $_SESSION['start'] + (1 * 60);
}
$now = time(); // Checking the time now when home page starts.
if ($now > $_SESSION['expire']) {
    session_destroy();
    error_log('dd');
}
*/


$_SESSION['userID']=$userID;

$allGroups=array();
  //if($userID=="admin" || $userID=="administrator"){
     $allGroups=getGroups($gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$userID);
     if($allGroups==''){
         $allGroups=getGroups($gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,'admin');
     }
  //}
 $allDevices=device_info($gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID);



$image='http://track.glovision.co:8080/statictrack/images/custom/'.$accountID.'.png';
 
  if(strpos($accountID,'gvk')>-1){
    $image='http://track.glovision.co:8080/statictrack/images/custom/gvkemri.jpg';
  }
           

$_SESSION['logo']=$image;
$bgimage="";


function getGroups($gtsserver, $gtsusername, $gtspassword,$gtsdb,$accounts,$userID){
    $result='';
   $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error());
   mysqli_select_db($gtsconnect, $gtsdb);
  // $vehicleDetails="select vehicleModel,vehicleMake,lastValidLatitude,lastValidLongitude,deviceID from Device where accountID='$accounts'";
   $vehicleDetails="select DISTINCT groupID from DeviceGroup where accountID='$accounts'";
   if($userID !='admin' &&  $userID !='administrator' && $userID !=''){
        $vehicleDetails="select DISTINCT groupID from GroupList where accountID='$accounts' and userID='$userID'";
   }
   error_log($vehicleDetails);
   $qry1 = mysqli_query($gtsconnect, $vehicleDetails) or die(mysqli_error());
   $rowcount=0;
   while($row = mysqli_fetch_assoc($qry1)){
        //$result[$rowcount]=$row['groupID'];
        //$rowcount++;
        error_log($row['groupID']);
        $result=$result.$row['groupID'].',';
   }
   mysqli_close($gtsconnect);
   return $result;

}
function device_info($gtsserver, $gtsusername, $gtspassword,$gtsdb,$accounts){
   $result='';
   $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error());
   mysqli_select_db($gtsconnect, $gtsdb);
  // $vehicleDetails="select vehicleModel,vehicleMake,lastValidLatitude,lastValidLongitude,deviceID from Device where accountID='$accounts'";
   $vehicleDetails="select groupID,deviceID from DeviceList where accountID='$accounts'";

   error_log($vehicleDetails);
   $qry1 = mysqli_query($gtsconnect, $vehicleDetails) or die(mysqli_error());
   $rowcount=0;
   error_log('Devicessssss');
   while($row = mysqli_fetch_assoc($qry1)){
      
        //$result[$rowcount][0]=$row['deviceID'];
       // $result[$rowcount++][1]=$row['groupID'];
        $result=$result.$row['deviceID'].'*'.$row['groupID'].'^';
        
   }
   mysqli_close($gtsconnect);
   return $result;
}


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<!-- <!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> -->
<!-- <html xmlns='http://www.w3.org/1999/xhtml' xmlns:v='urn:schemas-microsoft-com:vml'>-->
<!-- Copyright(C) 2013-2014 Glovision Techno Services, All rights reserved -->
<html>
<!-- meta -->
<head>
<meta name="author" content="Glovision Techno Services"/>
<meta http-equiv="content-type" content='text/html; charset=UTF-8'/>
<!-- <META Http-Equiv="Expires" Content="1" /> -->
<link rel="shortcut icon" href="../images/glovision.ico"/>
<title>Glovision Techno Services</title>

<link rel="stylesheet" href="../css/fuelbar.css"/>
<style>


 
</style>
<script src="../js/jquery-1.11.1.min.js"></script>
<script type="hacker/JavaScript" hacker="enabled"></script>
<link rel="stylesheet" type="text/css" href="../js/jquery.datetimepicker.css"/>
<script src="../js/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="../js/InmotionReport.js" type="text/javascript"></script>
<script type="text/javascript">
 
var accountID='<?php echo $accountID; ?>';
var userID='<?php echo $userID; ?>';
var formateType='<?php echo $formate; ?>';

 var allDevices='<?php echo $allDevices; ?>';
       allDevices=allDevices.split("^");
 var mandal='<?php echo $allGroups; ?>';
  mandal=mandal.split(",");
 
window.localStorage.setItem('accountID',accountID);
window.localStorage.setItem('userID',userID);
 

</script>

</head>
<body onload="dateInit();getmandals();" background="<?php echo $bgimage; ?>">
<center>
        <div>
          <img alt="" src="<?php echo $image; ?>">
         </div>
         

     <div align="center" id='container'>
         
       <div id="logout" class='hello'>
       <table align="center" class="BaseTable" background="#B2E9F9">  
         <thead>  <tr>
            <th> 
            <!-- <a align="right" onclick="logout()" style="text-decoration: none;color:white;"> Back</a> -->
            <font color="white"><u style="color:white;"><a onclick="back()" rel="external" style="color:white;">Back</a></u>  </font>
             </th><th>
                  <div id="Name">AccountID: </div>
                 <!--  <div class="accountDiv" align="left" id="acc1"><input type="button" id="reset" value="Reset Map"/></div>-->
             </th>
                                    
            
             
         </tr></thead>
       </table>
       </div>     
      <br>
       <br>
                <h4 style="color: #086A87;">
                    <b>InMotion Report And Vehicle Health Status For All Vehicles</b>
                </h4>
                 
                <form name="detail">

                    <table style="font-size: 11pt; text-align: center" >
                        <!-- table body -->
                        <tbody>
                        
                             <tr>
                                <td align='left' style="color:#42617C">Groups:</td>
                                <td align='left'><select class='textbox' id="group" onChange="suitableVehicles()" style="max-height:300px;">
                                  <option value="selectall">Select All</option>
                                </select> </td><td align='left'><font color="red"><font color="red"><nowiki>*</nowiki>
                                    </font></font></td>
                                <td align='left'><span id="geoID"></span></td>
                            </tr>

                          
                            <!-- Vehicle field -->
                            <tr>
                                <td align='left' style="color:#42617C">Vehicle:</td>
                                <td align='left'><select id="vehicle" class='textbox'  style="max-height:100px;">
                                        <option value="selectall">All Vehicles</option>
                                </select> </td><td align='left'><font color="red"><font color="red"><nowiki>*</nowiki>
                                    </font></font></td>
                                <td ><span id="id"></span></td>
                            </tr>
                            <!-- date field -->
                            <tr>
                                <td align='left' style="color:#42617C">From Date:</td>
                                <td align='left'><input id="fromDate" class='textbox' name="fromDate"/></td>
                                <td align='left'><font
                                    color="red"> 
                                </font></td>
                                <td><span id="fromDate"></span></td>
                            </tr>

                            <!-- to date field -->
                            <tr>
                                <td align='left' style="color:#42617C">To Date:</td>
                                <td align='left'><input class='textbox' id="toDate" name="toDate"/></td>
                                <td align='left'><font
                                    color="red"> 
                                </font></td>
                                <td><span id="toDate"></span></td>
                            </tr>
                          
                            
                           

                          <!-- submit button -->
                            <tr>
                                <td align='center' >
                                   <input id="report" class="buttons" name="submit" type="button" value="InMotion Report" size="20" onclick="getTripReport()"/></td>
                               <td align='center' >
                                   <input id="health" class="buttons" name="submit" type="button" value="Vehicle's Health Status" size="20" onclick="getVehicleHealthStatus()"/></td>
                             <!-- <td align='center' >
                                   <input id="invoice" class="buttons" name="invoice" type="button" value="Invoice" size="20" onclick="getInvoice()"/></td>-->
                             <td align='center' >
                                   <input id="overspeedreport" class="buttons" name="overspeed" type="button" value="Over Speed" size="20" onclick="getOverSpeedReport()"/></td>
                           </tr><tr>
                             <td align='center' colspan='4' >
                                   <input id="daywiseStatusreport" class="buttons" name="daywisestatusreport" type="button" value="Day Wise Status Report" size="20" onclick="getStatusReportDaywise()"/></td>
                            </tr>
                            
                         
                            <!-- ending table body -->
                        </tbody>
                        <!-- ending table -->
                    </table>
                  
                   
                    <!-- end of form -->
                </form>  <br><br>  <br><br>  <br><br>  <br><br>  <br><br>
<div style="color:#000B12;font-size:10px;">Copyright &copy; 2014 Glovision Techno Services Pvt Ltd. " - All Rights
            Reserved " <a class="footer-link" target="_blank"></a>

   </div>
</div>
</center>
               

    </body>
    </html>
