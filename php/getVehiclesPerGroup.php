<?php

include 'config.php';

$id = $_GET['accountID'];
$userID = $_GET['userID'];
$groupID = $_GET['groupID'];
$vehicleGroup=array();
          $vehicleGroup=getGroupVehicle();
echo '{"markers": ' . json_encode(utf8ize($vehicleGroup) ) .'}';
function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}


  function getGroupVehicle(){
     global $id,$gtsserver,$gtsusername,$gtspassword,$gtsdb,$accountID,$groupID ;
     $result=array();
     $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($tacconnect));
     mysqli_select_db($gtsconnect,$gtsdb);

     $query="select groupID,deviceID from DeviceList where accountID='$id' and groupID='$groupID'";
      if($groupID=="selectall"){
           $query="select groupID,deviceID from DeviceList where accountID='$id'";

      }

     $qry1 = mysqli_query($gtsconnect,$query) or die(mysqli_error($gtsconnect));
     $rowcount=0;
      while($row = mysqli_fetch_assoc($qry1)){
                   
        $result[$rowcount++]=$row['deviceID'];
      }
      if($rowcount==0){
       $result[]='';
      }
       mysqli_close($gtsconnect);
      return $result;
}


?>
