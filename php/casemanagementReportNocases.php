<?php
   date_default_timezone_set('Asia/Kolkata');
   include 'config.php';
   $accountID = $_GET['accountID'];
//http://glotrax.glovision.co/casemanagerdashboardpublic/php/casemanagementReport.php?&accountID=gvkrajasthan&vehicle=selectall&group=selectall&fromDate=1469644200&userID=admin&formate=html&toDate=1469770806 
  $fromDate=$_GET['fromDate'];
 $toDate=$_GET['toDate'];
 $group=$_GET['group'];
$vehicleID=$_GET['vehicle'];
$formate=$_GET['formate']; 
  $selectedgroupdevices="''";
  $selectedDevices=array(array());
  $caseDevices=array();
  $caseDevicesCount=0;
 $caseDevicesnew=''; 

$track1conncetion=mysqli_connect("track.glovision.co:50999", "root", "gl0v1s10n") or die ("Unable to connect to the database:-1 " . mysqli_error($track1conncetion));
mysqli_select_db($track1conncetion, "autocare_gts");


   $assignedquery="SELECT DISTINCT deviceID FROM caseManager where caseTimestamp between '$fromDate' and '$toDate'";      
  error_log($assignedquery); 
   //echo $assignedquery;
  $result= mysqli_query($track1conncetion,$assignedquery) or die(mysqli_error($track1conncetion));
   while($row = mysqli_fetch_assoc($result))
    {
      $caseDevicesCount++;
      $caseDevices.="'";
      $caseDevices.=$row['deviceID']."',";
    }  
      $caseDevices= rtrim($caseDevices, ',');
      $caseDevices= ltrim($caseDevices, 'Array');
//   echo $caseDevices.'\n'; 
$selectedDevices= device_info($gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$group,$vehicleID);
  $table="<style>
.tablecss {
        
        width:100%;
        border-collapse: collapse;
        color:black;
        background:white;
        font-family:Arial,Verdana,sans-serif;
        font-size:13;
}


</style><body onload='casecount();'><center>   <h4>CaseManager Report</h4> <font color='red' size='1'>Note:The data shown below is from: ".date('d-m-Y',$fromDate)." to :".date('d-m-Y',$toDate)."</font><div  onMouseOver='this.style.opacity=1' onMouseOut='this.style.opacity=1;' style='overflow: auto;'>";
$table=$table."<table id='search' class='tablecss' border='1' style='font-family:Arial,Verdana,sans-serif;font-size:13;color:black;background:white;'><thead style='background:black;color:white'><th>S.no</th><th>District</th><th>BaseLocation</th><th>Ambulance No</th></thead><tbody>";
         $groupID='';
         $description=''; 
           $caseDevicesn=str_replace("'", "", $caseDevices);
           $caseDevicesnew=explode(",",$caseDevicesn);
//echo count($selectedDevices);
//echo $caseDevicesCount;
     $sno=0;
        for($i=0;$i<count($selectedDevices);$i++){
//       echo "hii".$selectedDevices[$i][4];
$sno++;
            $groupID=$selectedDevices[$i][8];
            $description=$selectedDevices[$i][10];
           //break;
        
          $dis11 = explode("]",$description);
     //     $re=trim($dis11[2],'[').'/'.trim($dis11[1],'[');
         
   // echo trim($dis11[2],'[').'   '.trim($dis11[1],'[');
     //  echo  $row['deviceID'].' '.$row['caseID'].'  '.$row['caseStatus'];// 	incidentLatitude 	incidentLongitude 	caseTimestamp 	caseType 	caseStatus 	caseDistance 	callerName 	callerContact
    $table=$table."<tr><td>".$sno."</td><td>".trim($dis11[2],'[')."</td><td>".trim($dis11[1],'[')."</td><td>".strtoupper($selectedDevices[$i][4])."</td></tr>";
               
}

   mysqli_close($track1conncetion);
 $table=$table."</tbody></table></div></body></center></html>";
echo $table;
function getLastLocation($deviceID){
     global $gtsserver, $gtsusername, $gtspassword,$gtsdb;
   //   echo $gtsserver.$gtsusername.$gtspassword.$gtsdb.'<br>';
     $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database-2:" . mysqli_error($gtsconnect));
     mysqli_select_db($gtsconnect,$gtsdb);
     $query="select deviceID,lastEventTimestamp,description,lastValidLatitude,lastValidLongitude from Device where deviceID='$deviceID'";

    error_log($query);
    $qry_result = mysqli_query($gtsconnect,$query) or die(mysqli_error($gtsconnect));
    $i=0;
    $latlng=array(); 
    while($row = mysqli_fetch_assoc($qry_result)){
       $latlng[0]=$row['deviceID'];
       $latlng[1]=$row['lastGPSTimestamp'];
       $latlng[2]=$row['lastValidLatitude'];
       $latlng[3]=$row['lastValidLongitude'];
     //  echo $row['lastValidLatitude'].' '.$row['lastValidLongitude'];

   
           }
   
    mysqli_close($gtsconnect);
    return $latlng;
}

function device_info($gtsserver, $gtsusername, $gtspassword,$gtsdb,$accounts,$userID,$vehicleID){
global $selectedgroupdevices,$caseDevices;
   $result=array(array());
   $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database-3: " . mysqli_error($gtsconnect));
   mysqli_select_db($gtsconnect,$gtsdb);
 
   $vehicleDetails="select a.vehicleModel as vehicleModel,a.vehicleMake as vehicleMake,a.lastValidLatitude as lastValidLatitude,a.lastValidLongitude as lastValidLongitude,a.deviceID as deviceID,a.imeiNumber as imeinumber,lastEventTimestamp,a.simPhoneNumber as simPhoneNumber,b.groupID as groupID,a.simPhoneNumber as contactPhone,a.description as description  from Device a,DeviceList b where a.accountID='$accounts' and b.groupID='$userID' and b.accountID='$accounts' and a.deviceID=b.deviceID  and a.deviceID NOT IN ($caseDevices)";
   if($userID=="admin" || $userID=="administrator" || $userID=="selectall"){
   $vehicleDetails="select a.vehicleModel as vehicleModel,a.vehicleMake as vehicleMake,a.lastValidLatitude as lastValidLatitude,a.lastValidLongitude as lastValidLongitude,a.deviceID as deviceID,a.imeiNumber as imeinumber,   lastEventTimestamp,a.simPhoneNumber as simPhoneNumber,b.groupID as groupID,a.simPhoneNumber as contactPhone,a.description as description  from Device a,DeviceList b where a.accountID='$accounts' and b.accountID='$accounts' and a.deviceID=b.deviceID and a.deviceID NOT IN ($caseDevices)";
   }
   if($vehicleID!="selectall"){
      $vehicleDetails="select a.vehicleModel as vehicleModel,a.vehicleMake as vehicleMake,a.lastValidLatitude as lastValidLatitude,a.lastValidLongitude as lastValidLongitude,a.deviceID as deviceID,a.imeiNumber as imeinumber,        lastEventTimestamp,a.simPhoneNumber as simPhoneNumber,b.groupID as groupID,a.simPhoneNumber as contactPhone,a.description as description from Device a,DeviceList b where a.accountID='$accounts' and b.groupID='$userID' and b.accountID='$accounts' and a.deviceID=b.deviceID and b.deviceID='$vehicleID' and a.deviceID='$vehicleID' and a.deviceID NOT IN ($caseDevices)";
   }

//echo $vehicleDetails;
   $qry1 = mysqli_query($gtsconnect,$vehicleDetails) or die(mysqli_error($gtsconnect));
   $rowcount=0;
   error_log('Devicessssss');
   while($row = mysqli_fetch_assoc($qry1)){
        
        $result[$rowcount][0]=$row['vehicleMake'];
        $result[$rowcount][1]=$row['vehicleModel'];
        $result[$rowcount][2]=$row['lastValidLatitude'];
        $result[$rowcount][3]=$row['lastValidLongitude'];
        $result[$rowcount][4]=$row['deviceID'];
         $result[$rowcount][5]=$row['imeinumber'];
        $result[$rowcount][6]=$row['lastEventTimestamp'];
        $result[$rowcount][7]=$row['simPhoneNumber'];
        $result[$rowcount][8]=$row['groupID'];
        $result[$rowcount][9]=$row['contactPhone'];
         $result[$rowcount][10]=$row['description'];
       $selectedgroupdevices=$selectedgroupdevices.",'".$row['deviceID']."'";
        $rowcount++;

   }
   mysqli_close($gtsconnect);
   return $result;
}


function distance1($lat1, $lon1, $lat2, $lon2) {
        if($lat1 == $lat2 && $lon1 == $lon2){
            return 0;

        }else{$theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            return $miles* 1.609344;
        }
    }





?>
