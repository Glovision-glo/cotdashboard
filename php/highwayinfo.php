<?php
    date_default_timezone_set('Asia/Kolkata');
    require_once('config.php');
    $accountID=$_GET['accountID'];
    session_start();
    $result="<html><style type='text/css'>
.tablecss {
        
        width:100%;
        border-collapse: collapse;
        color:black;
        background:white;
        font-family:Arial,Verdana,sans-serif;
        font-size:13;
}
</style><body><center><h1>Case Manager Report</h1><div  style='background: white;border-style: solid;border-color: #2E2E2E;'><table id='sort' class='tablecss' border='1' style='width:100%;font-family:Arial,Verdana,sans-serif;font-size:13; color: #000000;align=left;'><tr style='background:#2E2E2E;color:white'>";//<th>Case ID</th><th>Hospital Name</th><th>Hospital Village</th><th>Hospital Tehsil</th><th>Hosptal District </th><th>Benificiary Name</th><th>Benificiary Contact </th><th>Vehicle ID</th><th>Call Time</th><th>Back To Base Location</th><th>Destination Hospital</th><th>Route</th></tr><tbody>";
     $tacconnect = mysqli_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database: " . mysqli_error($tacconnect));
      mysqli_select_db($tacconnect,$tacdb);
     
      $Query="select * From lucknowExpressway";
     /* if($vehicleID!="selectall"){
           $Query="select * From caseFlow where deviceID='$vehicleID' and ambyAssignedTime between '$fromDate' and '$toDate'";
      }else if($vehicleID=="selectall" && $groupID!="selectall"){
            $devicesforgroup=getGroupDevices($accountID,$groupID);
            $Query="select * From caseFlow where deviceID in ($devicesforgroup) and ambyAssignedTime between '$fromDate' and '$toDate'";
      }*/
      $rs =mysqli_query($tacconnect,$Query) or die ("Under Progresss ");
       while ($fieldinfo=mysqli_fetch_field($rs))
       {
           $result=$result."<th>".$fieldinfo->name."</th>";
       }
        $result=$result."<th>Route</th></tr></tbody>";
      
      while($row = mysqli_fetch_assoc($rs))
      {
          $result=$result."</tr>";
         foreach($row as $key => $value)
         {
             $result=$result."<td>".$value."</td>";
         }
           $result=$result."<td><a href='php/highwaymap.php?accountID=".$accountID."&ambulanceID=".$row['ambulanceID']."' target='_blank'>Route</a></td></tr>";
      }   
  /*
      while($row = mysqli_fetch_assoc($rs)) {
          $result=$result."<tr><td>".$row['caseID']."</td><td>".$row['hospitalName']."</td><td>".$row['hospitalVillage']."</td><td>".$row['hospitalTehsil']."</td><td>".$row['hospitalDistrict']."</td><td>".$row['beneficiaryName']."</td><td>".$row['beneficiaryContact']."</td><td>".$row['deviceID']."</td><td>".date('d-m-Y H:i:s',$row['ambyDepartTime'])."</td><td>".date('d-m-Y H:i:s',$row['backToBaseDepartTime'])."</td><td>".$row['destinationHospital']."</td><td><a href='php/caseTripMap.php?accountID=".$accountID."&caseID=".$row['caseID']."' target='_blank'>Route</a></td></tr>";
      
      }*/
    $result=$result."</tbody></table></div></center></body></html>";
      mysqli_close($tacconnect);
    echo $result;
/*
 caseID 	hospitalName 	hospitalVillage 	hospitalTehsil 	hospitalDistrict 	beneficiaryName 	beneficiaryContact 	deviceID 	callTime 	ambyAssignedTime 	ambyDepartTime 	pickUpPointArriveTime 	pickUpPointDepartTime 	destinationArriveTime 	destinationDepartTime 	backToBaseDepartTime 	remarks 	destinationHospital 

*/
function getGroupDevices($accountID,$groupID){
 global $gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$geozones;
       $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($gtsconnect));
       mysqli_select_db($gtsconnect,$gtsdb);
       $query = "SELECT deviceID from DeviceList where accountID='$accountID' and groupID='$groupID'";
           error_log($query);
       $qry_result = mysqli_query($gtsconnect,$query) or die(mysqli_error($gtsconnect));
       $i=0;
       $vehicles="''";
       while($row = mysqli_fetch_assoc($qry_result))
       {
         //error_log($row['geozoneID'].'  '.$row['latitude1'].'   '.$row['longitude1'].' '.$row['radius']);
           $vehicles=$vehicles.",'".$row['deviceID']."'";
       }
       mysqli_close($gtsconnect);

       return $vehicles;



}

?>
