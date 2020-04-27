<?php
  include 'config.php';
  date_default_timezone_set('Asia/Kolkata');
  $vehicleId=$_GET['vehicleID'];
  $accountID=$_GET['accountID'];
  $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($gtsconnect));
  mysqli_select_db($gtsconnect,$gtsdb);
  $query="select lastValidHeading,lastValidSpeedKPH,lastValidLatitude,lastValidLongitude,a.lastEventTimestamp as lastGPSTimestamp,c.contactPhone as simPhoneNumber  from Device a,Driver c where a.accountID='$accountID' and  c.accountID='$accountID' and c.deviceID=a.deviceID and a.deviceID='$vehicleId' and c.deviceID='$vehicleId'";
  $qry_result = mysqli_query($gtsconnect,$query) or die(mysqli_error($gtsconnect));
  $lat='';

  $lng='';
  $speed=0;
  $lastvalidheading='h0';
 $heading=0;
$timestamp=0;
  while($row = mysqli_fetch_assoc($qry_result)){
  // echo $row['lastValidLatitude'].'dddd';
    $lat=$row['lastValidLatitude'];
    $lng=$row['lastValidLongitude'];
    $speed=$row['lastValidSpeedKPH'];
    $timestamp=$row['lastGPSTimestamp'];
    $heading=$row['lastValidHeading'];
   $lastvalidheading=findDirection($row['lastValidHeading']);
  }
 $latlng=array();
 $latlng[0]=$lat;
 $latlng[1]=$lng;
 $latlng[2]=$speed;
 $latlng[3]=$lastvalidheading;
 $latlng[4]=$heading;
 $timediff=(time()-$timestamp)/3600;
  if($timediff>24){
       $latlng[5]="offline";
   }else if($timediff<=24 && $speed==0){
          $latlng[5]="idle";
   }else{

 $latlng[5]="online";
     }
 $latlng[6]=date("Y-m-d H:i:s",$timestamp);



  mysqli_close($gtsconnect);
  echo '{"markers": ' . json_encode($latlng) .'}';
function findDirection($degree){
if($degree>=0 && $degree<10){return 'h0';}
  else if($degree>10 && $degree<80){return 'h1';}

  else if($degree>=80 && $degree<100){return 'h2';}
  else if($degree>=100 && $degree<170){return 'h3';}
  else if($degree>=170 && $degree<190){return 'h4';}
  else if($degree>=190 && $degree<260){return 'h5';}
  else if($degree>=260 && $degree<280){return 'h6';}
  else if($degree>=280 && $degree<350){return 'h7';}
  else if($degree>=350 && $degree<360){return 'h0';}



}

?>

