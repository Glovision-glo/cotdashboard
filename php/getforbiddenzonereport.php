<?php 
include 'config.php';
date_default_timezone_set('Asia/Kolkata');

$tacconnect = mysqli_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database: " . mysqli_error($tacconnect));
mysqli_select_db($tacconnect,$tacdb);
$deviceID=$_GET['vehicle'];
$group=$_GET['group'];
$accountID=$_GET['accountID'];
$userID=$_GET['userID'];
$dumpUserID=$userID;
$fromDate=$_GET['fromDate'];
$toDate=$_GET['toDate'];
$reportFormate=$_GET['formate'];
$speedlimit=$_GET['speedlimit'];

if($reportFormate=="word"){

   error_log($reportFormate.' report formate');
   header('Cache-control: no-cache,must revalidate');

   header("Access-Control-Allow-Origin: *");
   header("Content-type: application/vnd.ms-word");
   header("Content-Disposition: attachment;Filename=".$userID.".doc");
  
   }else if($reportFormate=="excel"){
    
   header('Cache-control: no-cache,must revalidate');
  header('Content-type: application/json');
   header("Access-Control-Allow-Origin: *");
  header("Content-type: application/vnd.ms-excel");
   header("Content-Disposition: attachment;Filename=".$userID.".xls");

 
   }else if($reportFormate=="web"){
       
        header('Cache-control: no-cache,must revalidate');
        
        header("Access-Control-Allow-Origin: *");
        header('Content-type: text/html');
        header("Content-Disposition: attachment;Filename=".$userID.'_'.date('d-m-Y').".html");
       
    }

   $groupArray=array();
 //echo 'satrt';   
   if($group=="selectall"){
      if($userID != "admin" && $userID != "administrator"){
          
          $groupArray=getGroups($gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$userID);

          if(count($groupArray)==0){
           $userID='admin';
           $groupArray=getGroups($gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$userID);
          }
         
        
      }else{
           $groupArray=getGroups($gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$userID);
      }
   }else{
        $groupArray[0]=$group;
   }

$ydata2 = array();

$groupLevel='';
$groupID='';
  
$result1='';
 $summaryReport="<html><head><style type='text/css'>
.tablecss {
	
        width:100%;
        border-collapse: collapse;
        color:black;
        background:white;
        font-family:Arial,Verdana,sans-serif;
        font-size:13;
}
</style><script>
</script></head><body><div id='printarea'><center><h3>Geozone Report </h3>User ID:<b style='font-size:16px' >$userID </b>|District:<b style='font-size:16px' >$group</b> <br> From:".date('d-M-Y h:i:s',$fromDate )."  To:".date('d-M-Y h:i:s',$toDate );
 $table="<center><br> <table border='1' class='tablecss'><thead style='background:black;color:white'><th>Vehicle ID</th><th>Group Name</th><th>Base Location</th><th>Geozone ID</th><th>Lat</th><th>Lon</th><th>Address</th><th>Depart Time</th><th>Arrivel Time</th><th>Outside Elapsed</th></thead><tbody>";
$sno=1;

$vehicleGroup=getGroupVehicle();

for($gpindx=0;$gpindx<count($groupArray);$gpindx++){
     $lastLocaton=array(array());
     $groupID=$groupArray[$gpindx];
     getLastLocation();
  
    
     $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($gtsconnect));
     mysqli_select_db($gtsconnect,$gtsdb);
     $countoverspeed=0;
    for($lve=0;$lve<count($lastLocaton);$lve++){
         $deviceLevel='';
         $ydata  =array(array());
         $cumulativeDistance=0;
        $countoverspeed=0; 
         $query = "select timestamp,deviceID,analog0,statusCode,speedKPH,from_unixtime( timestamp, '%d-%m-%Y %h:%i:%s %p' ) as time1,odometerOffsetKM+odometerKM as 'odometerKM',latitude,longitude,address,analog1,heading as direction,geozoneID from EventData where accountID='$accountID' and deviceID='".$lastLocaton[$lve][0]."'  and timestamp between '$fromDate' and '$toDate'  and statusCode in ('61968','62000') and geozoneID='forbiddenzone' order by timestamp asc";//
         $qry_result = mysqli_query($gtsconnect,$query) or die(mysqli_error($gtsconnect));
         $j=0;
        $catchOverspeedEventtime='no';
        $vehiclestatrt='';
        $departStart="";
        $departTime=0;
        $arrivalDepartCount=0;
        $totalTimeInzone=0;
        $tempDepartData="";
        $laststatusCode=0;
       $tempDepartTimeDiff=0;
        while($row = mysqli_fetch_assoc($qry_result))
        {  

         // echo $row['statusCode']."  ".date('d-m-Y H:i:s',$row['timestamp'])." ".$row['deviceID']."<br>"; 
          $laststatusCode=$row['statusCode'];
          //first arrivel
          if($departStart=="" && $row['statusCode']=="61968"){
            $departtime=0;
          //  $departtime= getlastDepart($accountID,$lastLocaton[$lve][0],$fromDate); 
            $diff=0;
           
            if($departtime!=0){
                 $diff=$row['timestamp']-$departtime;
                 $departtime=date('d-m-Y H:i:s',$departtime);
  
            }else{
               $departtime="";
             }
             $table .='<tr><td>'.strtoupper($row['deviceID']).'</td><td>'.strtoupper($vehicleGroup[strtolower($row['deviceID'])]).'</td><td> '.$lastLocaton[$lve][2].' </td><td>  '.strtoupper($row['geozoneID']).'</td><td> '.round($row['latitude'],5).'</td><td>  '.round($row['longitude'],5)."</td><td>".$row['address']."</td><td>".$departtime."</td> <td>".date('d-m-Y H:i:s',$row['timestamp'])."</td><td>".secondsToTimeFormate($diff)."</td></tr>";
           $arrivalDepartCount++;
          }

        //depart after depart
         if($departStart=="find" && $row['statusCode']=="62000"){
             $departStart="";
                                 
              $table .='<tr><td>'.strtoupper($row['deviceID']).'</td><td>'.strtoupper($vehicleGroup[strtolower($row['deviceID'])]).'</td><td> '.$lastLocaton[$lve][2].' </td><td>  '.strtoupper($row['geozoneID']).'</td><td> '.round($row['latitude'],5).'</td><td>  '.round($row['longitude'],5)."</td><td>".$row['address']."</td><td>".date('d-m-Y H:i:s',$departTime)."</td> <td>-------</td><td>--</td></tr>";
              $departTime=0; 
         }
         
        //find arrival event
         if($departStart=="" && $row['statusCode']=="62000"){

             $departStart="find";
             $departTime=$row['timestamp'];
              $diff=($toDate-$row['timestamp']);
              $tempDepartTimeDiff=$diff;
              $tempDepartData='<tr><td>'.strtoupper($row['deviceID']).'</td><td>'.strtoupper($vehicleGroup[strtolower($row['deviceID'])]).'</td><td> '.$lastLocaton[$lve][2].' </td><td>  '.strtoupper($row['geozoneID']).'</td><td> '.round($row['latitude'],5).'</td><td>  '.round($row['longitude'],5)."</td><td>".$row['address']."</td><td>".date('d-m-Y H:i:s',$row['timestamp'])."</td> <td>--------</td><td>".secondsToTimeFormate($diff)."</td></tr>";
          } 
          //find depart after arrival
         if($departStart=="find" && $row['statusCode']=="61968"){
             $departStart=""; 
              $totalTimeInzone=$totalTimeInzone+($row['timestamp']-$departTime);
             $diff=($row['timestamp']-$departTime); 
           $table .='<tr><td>'.strtoupper($row['deviceID']).'</td><td>'.strtoupper($vehicleGroup[strtolower($row['deviceID'])]).'</td><td> '.$lastLocaton[$lve][2].' </td><td>  '.strtoupper($row['geozoneID']).'</td><td> '.round($row['latitude'],5).'</td><td>  '.round($row['longitude'],5)."</td><td>".$row['address']."</td><td>".date('d-m-Y H:i:s',$departTime)."</td> <td>".date('d-m-Y H:i:s',$row['timestamp'])."</td><td>".secondsToTimeFormate($diff)."</td></tr>";
             $departTime=0;
             $arrivalDepartCount++; 
         }  


  


      }//while
       if($laststatusCode=="62000"){
         $table .= $tempDepartData;
         $totalTimeInzone=$totalTimeInzone+$tempDepartTimeDiff;
         $arrivalDepartCount++;
       }

       if($arrivalDepartCount>0)
       $table .='<tr style="background:#a4a2a2;color:black"><th colspan="7" ><center>Total Time in Geozone</center></th><td>'.secondsToTimeFormate($totalTimeInzone).'</td><th >Count</th><td>'.$arrivalDepartCount.'</td></tr>';
     $arrivalDepartCount=0;
       $dis11 = explode("]",$lastLocaton[$lve][2]);
     /*  if($countoverspeed>0)
      $ummaryReport .="<tr><td>".($sno++)."</td><td>".strtoupper($lastLocaton[$lve][0])."</td><td>".strtoupper(trim($dis11[2],'['))."</td><td>".strtoupper(trim($dis11[1],'['))."</td><td>".$countoverspeed."</td></tr>";*/
   }




   

}
 echo $summaryReport.'</tbody></table>'.$table.'</tbody></table></div></center></body></html>';



  function secondsToTimeFormate($TotalAcOnTime){
        $hours=0;
        $min=0;
        //$sec=0;
        if ($TotalAcOnTime==0) {
            return 0;
        }
        if($TotalAcOnTime>=3600){
                $hours=intval($TotalAcOnTime/(60*60));
                $tempmin=$TotalAcOnTime%(60*60);
                $result=secondsToTimeFormate($tempmin);
                return $hours."Hr :".$result;
        }else if($TotalAcOnTime>=60){
                 $min=intval($TotalAcOnTime/60);
                // $tempsec=$TotalAcOnTime%60;
                 return $min." Mins";
                // return $min." Min :".secondsToTimeFormate($tempsec);
        }else{
             return $TotalAcOnTime." sec";
        }

    }

function getlastDepart($accountID,$deviceID,$fromdate){
    global $gtsserver, $gtsusername, $gtspassword,$gtsdb;
    $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($gtsconnect));
    mysqli_select_db($gtsconnect,$gtsdb);
     
    $query = "select max(timestamp) as lastdeparttime from EventData where accountID='$accountID' and deviceID='".$deviceID."'  and timestamp < $fromdate  and statusCode in ('62000') and geozoneID='forbiddenzone' order by timestamp asc";//

    $qry_result = mysqli_query($gtsconnect, $query) or die(mysqli_error($gtsconnect));
    $lastdepart=0;
     while($row = mysqli_fetch_assoc($qry_result)){
       $lastdepart=$row['lastdeparttime'];
    }
    if($lastdepart==''){$lastdepart=0;}
    mysqli_close($gtsconnect);
    return $lastdepart;

}
function getLastLocation(){
  global $gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$lastLocaton,$userID,$groupID,$deviceID,$fromDate,$toDate;
  $now=strtotime("10 October 2018");
 $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($gtsconnect));
mysqli_select_db($gtsconnect,$gtsdb);
     $query="select a.isActive as isActive,a.deviceID as deviceID,a.lastGPSTimestamp as lastGPSTimestamp,a.description as description,a.vehicleModel as vehicleModel,c.contactPhone as simPhoneNumber,a.uniqueID as uniqueID from Device a,DeviceList b,Driver c where a.accountID='$accountID' and b.groupID='$groupID' and b.accountID='$accountID' and a.deviceID=b.deviceID and c.deviceID=a.deviceID and c.deviceID=b.deviceID and c.accountID='$accountID'";
        
    if(($userID == "admin" || $userID == "administrator") && $groupID=="selectall"){
        $query = "SELECT a.isActive as isActive,a.deviceID as deviceID,a.lastGPSTimestamp as lastGPSTimestamp,a.description as description,a.vehicleModel as vehicleModel,c.contactPhone as simPhoneNumber,a.uniqueID as uniqueID FROM Device a,Driver c WHERE a.accountID ='$accountID' and a.deviceID=c.deviceID and c.accountID=a.accountID";
       
      }
     if($deviceID != 'selectall'){
          $query = "SELECT a.isActive as isActive,a.deviceID as deviceID,a.lastGPSTimestamp as lastGPSTimestamp,a.description as description,a.vehicleModel as vehicleModel,c.contactPhone as simPhoneNumber,a.uniqueID as uniqueID FROM Device a,Driver c WHERE a.accountID ='$accountID' and a.deviceID='$deviceID' and c.deviceID='$deviceID'";
     }
 // echo $query;
  $qry_result = mysqli_query($gtsconnect, $query) or die(mysqli_error($gtsconnect));
   $i=0;

   while($row = mysqli_fetch_assoc($qry_result)){
   if($row['isActive']==1||($row['isActive']==0 && ($fromDate<$now || $toDate<$now))){
       $lastLocaton[$i][0]=$row['deviceID'];
        $lastLocaton[$i][1]=$row['lastGPSTimestamp'];
       $dis11 =explode("]",$row['description']);
          $vehiclebaselocation=trim($dis11[1],'[');
       $lastLocaton[$i][2]=$vehiclebaselocation;
       $lastLocaton[$i][3]=$row['vehicleModel'];
       $lastLocaton[$i][4]=$row['simPhoneNumber'];
       $lastLocaton[$i][5]=$row['uniqueID'];
    //  echo 'raman';
          $i++;
   }
   }
mysqli_close($gtsconnect);
}

function getGroups($gtsserver, $gtsusername, $gtspassword,$gtsdb,$accounts,$userID){
    $result=array();
   $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($gtsconnect));
   mysqli_select_db($gtsconnect,$gtsdb);
  
    $vehicleDetails='';
    if($userID == "admin" || $userID == "administrator"){
         $vehicleDetails="select DISTINCT groupID from DeviceGroup where accountID='$accounts'";
    }else{
         $vehicleDetails="select DISTINCT groupID from GroupList where accountID='$accounts' and userID='$userID'";
    }
   error_log($vehicleDetails);
   $qry1 = mysqli_query($gtsconnect,$vehicleDetails) or die(mysqli_error($gtsconnect));
   $rowcount=0;
  
   while($row = mysqli_fetch_assoc($qry1)){
        $result[$rowcount]=$row['groupID'];
        $rowcount++;
    
   }
   if($rowcount==0){
       $result[$rowcount]='';
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
  return $miles* 1.609344;}
}

  function getGroupVehicle(){
     global $id,$gtsserver,$gtsusername,$gtspassword,$gtsdb,$accountID;
     $result=array();
     $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($tacconnect));
     mysqli_select_db($gtsconnect,$gtsdb);
     $groupsforadmin="'ahmedabad','amreli','anand','aravalli','banaskantha','bharuch','bhavnagar','botad','chhotaudepur','dahod','devbhumidwarka','gandhinagar','girsomnath','jamnagar','junagadh','kheda','kutch','mahesana','mahisagar','morbi','narmada','navsari','panchmahals','patan','porbandar','rajkot','sabarkantha','surat','surendranagar','tapi','thedangs','vadodara','valsad'";
     $query="select groupID,deviceID from DeviceList where accountID='$accountID' and groupID in ($groupsforadmin)";
     $qry1 = mysqli_query($gtsconnect,$query) or die(mysqli_error($gtsconnect));
     $rowcount=0;
      while($row = mysqli_fetch_assoc($qry1)){
        $result[strtolower($row['deviceID'])]=$row['groupID'];
      }
      if($rowcount==0){
       $result[]='';
      }
       mysqli_close($gtsconnect);
      return $result;
}


?>
