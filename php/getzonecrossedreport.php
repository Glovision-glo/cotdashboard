<?php 
include 'config.php';
date_default_timezone_set('Asia/Kolkata');
include 'checkPointLocationInPolygon.php';

$pointLocation = new pointLocation();
/*

gujarath boundary

*/
$polygon= array("24.570402,71.127134
","24.690243,71.654478","24.610362,72.357603","24.570402,72.577329","24.410436,72.75311","24.290328,72.972837","24.089896,73.280454","23.848962,73.368345","23.607581,73.675962","23.446412,73.807798","23.244674,74.15936","23.042631,74.379087","22.75926,74.466978","22.59707,74.335142","22.556492,74.214292","22.536199,74.082456","22.505753,74.07147","22.472525,74.083512","22.462373,74.193375","22.447143,74.23732","22.365888,74.209855","22.360808,74.072525","22.294751,74.078019","22.218492,74.083512","22.213407,74.121964","22.137104,74.14943","22.03021,74.127457","21.958903,74.127457","21.897754,74.023087","21.764601,73.795166","21.764601,73.795166","21.596725,73.797867","21.535422,73.946183","21.535422,74.02858","21.540531,74.13295","21.560968,74.259293","21.520092,74.325211","21.397394,73.973648","21.156814,73.616593","20.94605,73.723755","20.900448,73.902237","20.777237,73.94069","20.576803,73.742936","20.576803,73.649552","20.674483,73.479264","20.648784,73.429825","20.566517,73.468277","20.458475,73.440812","20.412148,73.413346","20.324603,73.418839","20.206081,73.402359","20.175147,73.221085","20.175147,73.138688","20.206081,73.001358","20.123577,72.940934","20.154521,72.781632","20.13905,72.7267","20.47906,72.869523","20.756691,72.84755","21.023559,72.74318","21.458756,72.605851","21.744767,72.51796","22.233747,72.655289","22.299833,72.441056","22.18289,72.342179","22.009841,72.237809","21.709047,72.270768","21.530312,72.254288","21.305304,72.144425","20.828587,71.32045","20.715593,70.804093","20.926105,70.331681","21.60694,69.606583","21.872268,69.315445","22.294751,68.903458","22.457296,69.06276","22.82739,69.068253","23.05503,68.766129","23.337779,68.42006","23.554483,68.183854","23.94666,68.238785","23.97176,68.711197","24.297603,68.826554","24.28759,68.947403","24.26756,69.628556","24.1824,69.787857","24.297603,70.128434","24.4077,70.589859","24.277575,70.578873","24.222482,70.815079","24.367676,70.99086","24.4077,71.139176","24.577661,71.012833","24.667548,71.106217","24.588436,71.197325","24.576976,71.146917");
//$polygon = array("-50 30","50 70","100 50","80 10","110 -10","110 -30","-20 -50","-30 -40","10 -10","-10 10","-30 -20","-50 30");
// The last point's coordinates must be the same as the first one's, to "close the loop"



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
</script></head><body><div id='printarea'><center><h3>Zone Crossed Report </h3>User ID:<b style='font-size:16px' >$userID </b>|District:<b style='font-size:16px' >$group</b> <br> From:".date('d-M-Y h:i:s',$fromDate )."  To:".date('d-M-Y h:i:s',$toDate );
 $table="<center><br> <table border='1' class='tablecss'><thead style='background:black;color:white'><th>Vehicle ID</th><th>Group Name</th><th>Base Location</th><th>Depart From State</th><th>Address</th><th>Arrived to State</th><td>Address</td><th>Outside Elapsed</th></thead><tbody>";
$sno=1;

$vehicleGroup=getGroupVehicle();

for($gpindx=0;$gpindx<count($groupArray);$gpindx++){
     $lastLocaton=array(array());
     $groupID=$groupArray[$gpindx];
     getLastLocation();
  
    
     $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($gtsconnect));
     mysqli_select_db($gtsconnect,$gtsdb);

    for($lve=0;$lve<count($lastLocaton);$lve++){
         $deviceLevel='';
         $ydata  =array(array());
         $cumulativeDistance=0;
        $countoverspeed=0; 
         $query = "select timestamp,deviceID,analog0,statusCode,speedKPH,from_unixtime( timestamp, '%d-%m-%Y %h:%i:%s %p' ) as time1,odometerOffsetKM+odometerKM as 'odometerKM',latitude,longitude,address,analog1,heading as direction,geozoneID from EventData where accountID='$accountID' and deviceID='".$lastLocaton[$lve][0]."'  and timestamp between '$fromDate' and '$toDate'  order by timestamp asc";//
  //address not like '%Gujarat%' and address<>''
         $qry_result = mysqli_query($gtsconnect,$query) or die(mysqli_error($gtsconnect));
         $findoutsidezone="";
         $departtime=0;
         $departaddress="";
         $departlat=0;
         $departlng=0;
        while($row = mysqli_fetch_assoc($qry_result))
        {  
            $status=$pointLocation->pointInPolygon($row['latitude'].",".$row['longitude'], $polygon);
               
 
            if($status=="outside"){
               //  $table .='<tr><td>'.strtoupper($row['deviceID']).'</td><td>'.strtoupper($vehicleGroup[strtolower($row['deviceID'])]).'</td><td> '.$lastLocaton[$lve][2].' </td><td> '.round($row['latitude'],5).'</td><td>  '.round($row['longitude'],5)."</td><td>".$row['address']."</td> <td>".date('d-m-Y H:i:s',$row['timestamp'])."</td></tr>";
            }
    
           if($findoutsidezone=="" && $status=="outside"){
                $findoutsidezone="found";
                $departtime=$row['timestamp'];
                $departaddress=$row['address'];
                $departlat=$row['latitude'];
                $departlng=$row['longitude'];
               
           }
           if($findoutsidezone=="found" && $status=="inside"){
              $findoutsidezone="";
              $outsidezonetime=$row['timestamp']-$departtime; 
               $table .="<tr><td>".strtoupper($row['deviceID'])."</td><td>".strtoupper($vehicleGroup[strtolower($row['deviceID'])])."</td><td> ".$lastLocaton[$lve][2]." </td><td>".date('d-m-Y H:i:s',$departtime)."</td><td>".$departaddress."(".round($departlat,5)."/".round($departlng,5).")</td><td>".date('d-m-Y H:i:s',$row['timestamp'])."</td> <td> ".$row['address']."(".round($row['latitude'],5)."/".round($row['longitude'],5)."</td><td>".secondsToTimeFormate($outsidezonetime)."</td></tr>";
                 
               $departtime=0;
               $departaddress="";
              $departlat=0;
               $departlng=0;

              //echo "Arrived to gj:".date('d-m-Y H:i:s',$row['timestamp'])."<br>";
           }

      }//while

      if($findoutsidezone=="found"){
            $table .="<tr><td>".strtoupper($lastLocaton[$lve][0])."</td><td>".strtoupper($vehicleGroup[strtolower($lastLocaton[$lve][0])])."</td><td> ".$lastLocaton[$lve][2]." </td><td>".date('d-m-Y H:i:s',$departtime)."</td><td>".$departaddress."(".round($departlat,5)."/".round($departlng,5).")</td><td>--</td> <td>-- </td><td>--</td></tr>";

   
      }

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



function getLastLocation(){
  global $gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$lastLocaton,$userID,$groupID,$deviceID,$fromDate,$toDate;
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
