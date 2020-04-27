<?php 
include 'config.php';

date_default_timezone_set('Asia/Kolkata');

$tacconnect = mysql_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database: " . mysql_error());
mysql_select_db($tacdb, $tacconnect);
$vehicleId=$_GET['vehicleID'];
$accountID=$_GET['accountID'];
$fromDate=$_GET['fromDate'];
$toDate=$_GET['toDate'];
error_log($gtsserver.'seeeeeeeeeeeeeeeeeee');
$sing_or_multiple_records_per_vehicle='multiple';
if($fromDate==$toDate){
  $sing_or_multiple_records_per_vehicle='single';
}
error_log($sing_or_multiple_records_per_vehicle);
$fromDate = strtotime($fromDate);
$toDate = strtotime($toDate);
error_log($fromDate);
$ydata  =array(array());
$ydata2 = array();
$vehicleDetails=vehile_Details($tacserver,$tacusername,$tacpassword,$tacdb,$accountID);
$gtsconnect = mysql_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysql_error());
mysql_select_db($gtsdb, $gtsconnect);
// $query = "select deviceID,analog0,statusCode,speedKPH,ADDTIME(from_unixtime( timestamp, '%Y-%m-%d %H:%i:%s' ),'05:30:00') as time1,odometerOffsetKM+odometerKM as 'odometerKM',latitude,longitude,address,analog1 from EventData where accountID='$accountID' and deviceID='$vehicleId' and timestamp BETWEEN '$fromDate' and '$toDate' group by timestamp,deviceID order by timestamp asc";
$query = "select deviceID,analog0,statusCode,speedKPH,ADDTIME(from_unixtime( timestamp, '%Y-%m-%d %H:%i:%s' ),'05:30:00') as time1,odometerOffsetKM+odometerKM as 'odometerKM',latitude,longitude,address,analog1,heading as direction from EventData where accountID='$accountID' and deviceID='$vehicleId' and timestamp BETWEEN '$fromDate' and '$toDate'  order by timestamp asc";
//and statusCode in ('61714','62467','62465','61472','61718','61720','61717','62541','62509','61477','61715','61713','64842','64840','61456','61716','61488')
if($vehicleId=="select"){
   if($sing_or_multiple_records_per_vehicle=="multiple"){
      $query = "select deviceID,analog0,statusCode,speedKPH,ADDTIME(from_unixtime( timestamp, '%Y-%m-%d %H:%i:%s' ),'05:30:00') as time1,odometerOffsetKM+odometerKM as 'odometerKM',latitude,longitude,address,analog1,heading as direction from EventData where accountID='$accountID' and timestamp BETWEEN '$fromDate' and '$toDate' order by timestamp asc";
  }else{

       //$query = "select i1.deviceID,i1.analog0,i1.statusCode,i1.speedKPH,ADDTIME(from_unixtime(i1.timestamp, '%Y-%m-%d %H:%i:%s' ),'05:30:00') as time1,i1.odometerOffsetKM+i1.odometerKM as 'odometerKM',i1.latitude,i1.longitude,i1.address,i1.analog1,i1.accountID from EventData AS i1 INNER JOIN (select deviceID,max(timestamp) as maxdate,accountID from EventData group by deviceID having accountID='$accountID') t1 on i1.deviceID=t1.deviceID and i1.timestamp=t1.maxdate and i1.accountID=t1.accountID";

      $query = "SELECT deviceID,lastValidLatitude as latitude, lastValidLongitude as longitude,ADDTIME(from_unixtime(lastGPSTimestamp, '%Y-%m-%d %H:%i:%s' ),'05:30:00') as time1, lastValidHeading as address,lastValidSpeedKPH as SpeedKPH,description,odometerOffsetKM+lastOdometerKM as 'odometerKM',statusCodeState as statusCode,lastValidHeading as direction FROM Device WHERE accountID ='$accountID'";
     // $query = "select deviceID,analog0,statusCode,speedKPH,ADDTIME(from_unixtime( timestamp, '%Y-%m-%d %H:%i:%s' ),'05:30:00') as time1,odometerOffsetKM+odometerKM as 'odometerKM',latitude,longitude,address,analog1,heading as direction from EventData where accountID='$accountID' and (deviceID,timestamp) in (SELECT deviceID,max(timestamp) FROM `EventData` where accountID='$accountID' group by deviceID)";



       //$query = "select i1.deviceID,i1.analog0,i1.statusCode,i1.speedKPH,ADDTIME(from_unixtime(i1.timestamp, '%Y-%m-%d %H:%i:%s' ),'05:30:00') as time1,i1.odometerOffsetKM+i1.odometerKM as 'odometerKM',i1.latitude,i1.longitude,i1.address,i1.analog1,i1.accountID from EventData AS i1 LEFT JOIN EventData AS i2 ON (i1.deviceID=i2.deviceID AND i1.timestamp < i2.timestamp AND i1.accountID='$accountID') where i2.timestamp IS NULL AND i1.accountID='$accountID'";
// $query = "select deviceID,analog0,statusCode,speedKPH,ADDTIME(from_unixtime( timestamp, '%Y-%m-%d %H:%i:%s' ),'05:30:00') as time1,odometerOffsetKM+odometerKM as 'odometerKM',latitude,longitude,address,analog1 from EventData where accountID='$accountID' and statusCode in ('61714','62467','62465','61472','61718','61720','61717','62541','62509','61477','61715','61713','64842','64840','61456','61716','61488') group by FROM_UNIXTIME(timestamp,'%Y-%d-%m %h:%i:%s'),deviceID,statusCode order by timestamp asc";
      
        
   }
}
//'61714','61718','62467','61472','61717','62534','62502','
//'61472'   stop ,
//'61718', idle
//'61720' ;long idle;
//61477 loastlocaton
$lastLocaton=array(array());
getLastLocation();

error_log($query);
$qry_result = mysql_query($query) or die(mysql_error());
$j=0;
for($lve=0;$lve<count($lastLocaton);$lve++){
         $query = "select deviceID,analog0,statusCode,speedKPH,ADDTIME(from_unixtime( timestamp, '%Y-%m-%d %H:%i:%s' ),'05:30:00') as time1,odometerOffsetKM+odometerKM as 'odometerKM',latitude,longitude,address,analog1,heading as direction from EventData where accountID='$accountID' and deviceID='$lastLocaton[$lve][0]' and timestamp='$lastLocaton[$lve][1]'";
         $qry_result = mysql_query($query) or die(mysql_error());

}
$vehicle_Separate='';
$ac_on_off='OFF';

while($row = mysql_fetch_assoc($qry_result))
{   
   
   if($row['latitude'] !="0"){
   // $ydata[$j][0]= $row['analog0'];//fuel votage
    
    $ydata[$j][0]=0;//fuel votage
    if( $row['statusCode']=="61715" || $row['statusCode']=="61713" || $row['statusCode']=="64842" || $row['statusCode']=="64840"){
       
         $ydata[$j][1]="61477";
         $ydata[$j][2]="61477";
          
               
    }else if($row['statusCode']=="61456" || $row['statusCode']=="61716" || $row['statusCode']=="61488"){
         //$_column="61472";
         
         $ydata[$j][1]="61717";
         $ydata[$j][2]="61717";
         
    }else{
       
        
        if($sing_or_multiple_records_per_vehicle=="multiple"){
           
             $ydata[$j][1]=$row['statusCode'];
             $ydata[$j][2]=$row['statusCode'];
         }else{
             
              $currenttime=strtotime(date('d-m-Y h:i:s a',strtotime("-0 days")).'');
              $lastEvntTime=strtotime($row['time1']);
              $diffTime=(abs($currenttime-$lastEvntTime))/3600;//convet in to hours if diffrence morethen 12 hour comunication fails
             if(intval($row['SpeedKPH'])>0){
                 
                 $ydata[$j][1]="61714";
                 $ydata[$j][2]="61714";
             }else{
                 
                 $ydata[$j][1]="61477";
                 $ydata[$j][2]="61477";

              }
             if($diffTime>12){
                 $ydata[$j][1]="0000";
                 $ydata[$j][2]="0000";
              }
         }
    }
    
    $ydata[$j][3]= floor($row['SpeedKPH']);
    $ydata[$j][4]=$row['time1'];
    $ydata[$j][5]=$row['odometerKM'];
    
    $ydata[$j][6]=$row['latitude'];
    $ydata[$j][7]=$row['longitude'];

    $ydata[$j][8]=$row['address'];
    if($sing_or_multiple_records_per_vehicle=="single"){
       // $ydata[$j][8]=round($row['latitude'],5).'/'.round($row['longitude'],5).'  '.$row['time1'];
         //$ydata[$j][8]=getGoogleAddress(round($row['latitude'],5),round($row['longitude'],5));
        $ydata[$j][8]=getaddress(round($row['latitude'],5),round($row['longitude'],5));
        error_log( $ydata[$j][8]);
    }
    $ydata[$j][9]=$row['deviceID'];
   
    for($vIndx=0;$vIndx<count($vehicleDetails);$vIndx++){
           
          if($vehicleDetails[$vIndx][0]==$ydata[$j][9]){ 
              
              if($vehicleDetails[$vIndx][7]=='digital'){
                   if($vehicle_Separate=='' || $row['deviceID'] != $vehicle_Separate){
                         $ac_on_off="OFF";
                         
                         $vehicle_Separate=$row['deviceID'];
                   }
                   if($vehicleDetails[$vIndx][10]==$ydata[$j][1]){ // Ac on compare register status code to ac on status code
                         $ac_on_off="ON";
                        
                         
                   }else if($vehicleDetails[$vIndx][11]==$ydata[$j][1]){ // Ac on compare register status code to ac on status code
                        //Ac off
                         $ac_on_off="OFF";
                         
                   }
                   $ydata[$j][10]=$ac_on_off;
                   
                    
              }else if($vehicleDetails[$vIndx][7]=='analog'){ //if analog
                     if(floatval($row['analog1'])>floatval($vehicleDetails[$vIndx][8])){ //ac on analog1 value compare with min analog value if less ac off if graterthen ac on 
                       
                       $ydata[$j][10]="ON";
                     }else{
                       $ydata[$j][10]="OFF";
                     }
 
              }else{
                      $ydata[$j][10]="Not Connected";
              }
             break;
          }
    }
    if(is_null($vehicleDetails[0])){
       $ydata[$j][10]="Not Connected";
    }
    
      $ydata[$j][11]=findDirection($row['direction']);   
 
    $j++;
    }
}

mysql_close($gtsconnect);
echo '{"markers": ' . json_encode($ydata) .'}';


function findDirection($degree){
  if($degree==0){return 'E';}
  else if($degree>0 && $degree<90){return 'NE';}
  else if($degree==90){return 'N';}
  else if($degree>90 && $degree<180){return 'NW';}
  else if($degree==180){return 'W';}
  else if($degree>180 && $degree<270){return 'SW';}
  else if($degree==270){return 'S';}
  else if($degree>270 && $degree<360){return 'SE';}
   
  

}


function vehile_Details($tacserver,$tacusername,$tacpassword,$tacdb,$accounts){
 $result=array(array());
 $tacconnect = mysql_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database: " . mysql_error());
       mysql_select_db($tacdb, $tacconnect);
       ///////////////////////////////////////////////////////////
       $vehicleDetails="select vehicleID,maxTankVoltage,minTankVoltage,voltageStatus,conversionRate,potentiometer,fuelTracker,acType,minAnalog,maxAnalog,digitalInputOn,digitalInputOff from autoVehicles where accountID='$accounts'";
       $qry1 = mysql_query($vehicleDetails) or die(mysql_error());
     
       error_log($vehicleDetails);
       $rowcount=0;
       while($row = mysql_fetch_assoc($qry1)){
           
           $result[$rowcount][0]=$row['vehicleID'];
           $result[$rowcount][1]=$row['voltageStatus'];
           $result[$rowcount][2]=$row['maxTankVoltage'];
           $result[$rowcount][3]=$row['minTankVoltage'];
           $result[$rowcount][4]=$row['potentiometer'];
           $result[$rowcount][5]=$row['fuelTracker'];
           $result[$rowcount][6]=$row['conversionRate'];
           $result[$rowcount][7]=$row['acType'];
           $result[$rowcount][8]=$row['minAnalog'];
           $result[$rowcount][9]=$row['maxAnalog'];
           $result[$rowcount][10]=$row['digitalInputOn'];
           $result[$rowcount][11]=$row['digitalInputOff'];
           $rowcount++;
       }


   return $result;
}
function getGoogleAddress($lat,$lng)
//function getaddress($lat,$lng)
{
error_log($lat."    ".$lng);
$url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=false';
$json = @file_get_contents($url);
$data=json_decode($json);
$status = $data->status;
error_log($data->results[0]->formatted_address);
if($status=="OK")
return $data->results[0]->formatted_address;
else
return " ";
}




function getaddress($lat, $lng) 
//function getOpenAddress($lat, $lng) 
{
    $geocodeXML = simplexml_load_file("http://nominatim.openstreetmap.org/reverse?format=xml&accept-language=en&lat=".trim($lat)."&lon=".trim($lng));
        // error_log(print_r($geocodeXML));
    if ($geocodeXML === FALSE) {
           return "";
    } else  {
            return (string)$geocodeXML->result->formatted_address;
    }
}

function getLastLocation(){
  global $tacserver,$tacusername,$tacpassword,$tacdb,$accountID,$lastLocaton;
$gtsconnect = mysql_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysql_error());
mysql_select_db($gtsdb, $gtsconnect);
    $query = "SELECT deviceID,lastGPSTimestamp FROM Device WHERE accountID ='$accountID'";
  $qry_result = mysql_query($query) or die(mysql_error());
   $i=0;
   while($row = mysql_fetch_assoc($qry_result)){
       $lastLocaton[$i][0]=$row['deviceID'];
        $lastLocaton[$i][1]=$row['lastGPSTimestamp'];
          $i++;
   }
}
?>
