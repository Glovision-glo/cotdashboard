<?php 
    include 'config.php';
//require_once 'Mail.php';
date_default_timezone_set('Asia/Kolkata');
$tacconnect = mysqli_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database: " . mysqli_error($tacconnect));
mysqli_select_db($tacconnect,$tacdb);


//$accountID=$_GET['accountID'];
$accountID="gvkrajasthan";

//INSERT INTO `autocare_gts`.`AssertLastEventStatus` (`accountID`, `deviceID`, `lastLocation`, `latitude`, `longitude`, `odometerReading`, `statusCode`, `speed`, `time`, `direction`, `discription`, `vehicleModel`, `simNumber`, `uniqueID`, `groupID`, `fuelVoltage`) VALUES ('1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1'), ('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');


$ydata  =array(array());
$ydata2 = array();


   $groupID='';
    $userID1=$userID;
    $lastLocaton=array(array());
     $driverinfo=array(array());
    getLastLocation();//onle for all vehilces with driver info
    getDriverInfo();//onle for all vehilces with driver info
    $rows1=0;
    $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($gtsconnect));
    mysqli_select_db($gtsconnect,$gtsdb);
     $j=0;
    for($x=0;$x<count($lastLocaton);$x++) {
       $simphoneNumber=getsimphoneNumber($lastLocaton[$x][0]);
       $result="no";
       $tmpDev = $lastLocaton[$x][0];
       $tmpTimestamp = $lastLocaton[$x][1];
     // $query="SELECT deviceID,statusCode,speedKPH,from_unixtime(timestamp, '%Y-%m-%d %H:%i:%s' ) as time1,odometerOffsetKM+odometerKM as 'odometerKM',latitude,longitude,address,heading as direction FROM EventData where accountID='$accountID' and deviceID='$tmpDev' and timestamp <='$tmpTimestamp' ORDER BY timestamp Desc Limit 1";
      $query="SELECT t1.deviceID,t1.analog0,t1.statusCode,t1.speedKPH,from_unixtime(t1.timestamp, '%Y-%m-%d %H:%i:%s' ) as time1,t1.odometerOffsetKM+t1.odometerKM as 'odometerKM',t1.latitude,t1.longitude,t1.address,t1.analog1,t1.heading as direction,t3.groupID as groupID FROM EventData t1 JOIN (SELECT deviceID,accountID,MAX(timestamp) timestamp FROM EventData  where accountID='$accountID' and deviceID='$tmpDev') t2 JOIN (SELECT groupID,deviceID from DeviceList where accountID='$accountID' and deviceID='$tmpDev') t3 ON t1.deviceID = t2.deviceID AND t1.timestamp= t2.timestamp and   t1.accountID='$accountID' and t2.accountID='$accountID' and t3.deviceID='$tmpDev' and t1.deviceID='$tmpDev' order by t3.groupID,t1.timestamp ";
  
       error_log($query);
       $qry_result = mysqli_query($gtsconnect,$query) or die(mysqli_error($gtsconnect));
     //  error_log('after EventData');
       while($row = mysqli_fetch_assoc($qry_result)) {
            $result="yes";
           if($row['latitude'] !="0"){
               $ydata[$j][0]=0;//fuel votage
              if( $row['statusCode']=="61715" || $row['statusCode']=="61713" || $row['statusCode']=="64842" || $row['statusCode']=="64840" || $row['statusCode']=="61477"  
){
       
            $ydata[$j][1]="61717";
            $ydata[$j][2]="61717";
          
               
         }else if($row['statusCode']=="61456" || $row['statusCode']=="61716" || $row['statusCode']=="61488" || $row['statusCode']=="64784" || $row['statusCode']=="61472"){
              //$_column="61472";
         
            $ydata[$j][1]="61717";
            $ydata[$j][2]="61717";
         
         }else{
       
              $currenttime=strtotime(date('d-m-Y h:i:s a',strtotime("-0 days")).'');
              $lastEvntTime=strtotime($row['time1']);
              $diffTime=(abs($currenttime-$lastEvntTime))/3600;//convet in to hours if diffrence morethen 12 hour comunication fails
              $ydata[$j][1]=$row['statusCode'];
              $ydata[$j][2]=$row['statusCode'];
               
              if($diffTime>24){//24 hours since not getting any event
                 $ydata[$j][1]="0000";
                 $ydata[$j][2]="0000";
              }
              
         }
        
        
          $ydata[$j][3]= floor($row['speedKPH']);
          $ydata[$j][4]=$row['time1'];
          $ydata[$j][5]=intval($row['odometerKM']);
          $ydata[$j][6]=round($row['latitude'],5);
          $ydata[$j][7]=round($row['longitude'],5);
          $ydata[$j][8]=$row['address'];
          $ydata[$j][9]=$row['deviceID'];
          $ydata[$j][10]="Not Connected";
          $ydata[$j][11]=findDirection($row['direction']); 
            // error_log($ydata[$j][8].' '.$ydata[$j][4].'   '.$diffTime.'   '.$ydata[$j][9]);
          //for($dc=0;$dc<count($lastLocaton);$dc++){
            //  if($lastLocaton[$dc][0]==$ydata[$j][9]){
                  $dis11 = split("]",$lastLocaton[$x][2]);
                  $re=trim($dis11[2],'[').'/'.trim($dis11[1],'[');
                  $ydata[$j][12]=$re;
                  $ydata[$j][13]=$lastLocaton[$x][3];
                  //$ydata[$j][14]=$lastLocaton[$x][4];
                  $ydata[$j][14]=$simphoneNumber;
                  
                       $ydata[$j][15]=$lastLocaton[$x][5];
                  
                  $ydata[$j][16]=$lastLocaton[$x][6];
                 // break;  
                // echo $ydata[$j][9].' '.$lastLocaton[$x][2].' '.$ydata[$j][14].' <br> ';

                 $j++;
                         
             //}
         // } 
         }//end if
          //error_log('after processss');
       }//end while

       if($result=="no"){//no events in event table
             //echo $lastLocaton[$x][0].'   '.$lastLocaton[$x][2].'  '.$simphoneNumber.'<br>';
            $ydata[$j][0]=0;
            $ydata[$j][1]="0000";
            $ydata[$j][2]="0000";
            $ydata[$j][3]=0;
            $ydata[$j][4]=0;
            $ydata[$j][5]=0;
            $ydata[$j][6]=0;
            $ydata[$j][7]=0;
            $ydata[$j][8]=0;
            $ydata[$j][9]=$lastLocaton[$x][0];
            $dis11 = split("]",$lastLocaton[$x][2]);
            $re=trim($dis11[2],'[').'/'.trim($dis11[1],'[');
            $ydata[$j][10]="Not Connected";;
            $ydata[$j][11]="";
            $ydata[$j][12]=$re;
            $ydata[$j][13]=$lastLocaton[$x][3];
            $ydata[$j][14]=$simphoneNumber;
            $ydata[$j][15]=$lastLocaton[$x][5];
            $ydata[$j][16]=$lastLocaton[$x][6];
            $j++;
       }
       
     
   
     
    }//end for

   mysqli_close($gtsconnect);
     //save dbdetails
      $tacconnect = mysqli_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database: " . mysqli_error($tacconnect));
mysqli_select_db($tacconnect,$tacdb);
    for($j=0;$j<count($ydata);$j++){
        //echo  $ydata[$j][9].' '.$lastLocaton[$x][2].' '.$ydata[$j][14].' <br> ';
        $saveLastEvent="INSERT INTO AssertLastEventStatus(accountID,deviceID,lastLocation,latitude,longitude,odometerReading,statusCode,speed,time,direction,discription,vehicleModel,simNumber, uniqueID,groupID,fuelVoltage) VALUES ('".$accountID."', '".$ydata[$j][9]."', '".$ydata[$j][8]."', '".$ydata[$j][6]."', '".$ydata[$j][7]."', '".$ydata[$j][5]."', '".$ydata[$j][2]."', '".$ydata[$j][3]."', '".$ydata[$j][4]."', '".$ydata[$j][11]."', '".$ydata[$j][12]."', '".$ydata[$j][13]."', '".$ydata[$j][14]."', '".$ydata[$j][15]."', '".$ydata[$j][16]."', '".$ydata[$j][0]."') ON DUPLICATE KEY UPDATE lastLocation='".$ydata[$j][8]."',latitude='".$ydata[$j][6]."',longitude='".$ydata[$j][7]."',odometerReading='".$ydata[$j][5]."',statusCode='".$ydata[$j][2]."',speed='".$ydata[$j][3]."',time='".$ydata[$j][4]."',direction='".$ydata[$j][11]."',discription='".$ydata[$j][12]."',vehicleModel='".$ydata[$j][13]."',simNumber='".$ydata[$j][14]."', uniqueID='".$ydata[$j][15]."',groupID='".$ydata[$j][16]."',fuelVoltage='".$ydata[$j][0]."'";
        echo $saveLastEvent."<br>";
        $qry_result = mysqli_query($tacconnect,$saveLastEvent) or die(mysqli_error($tacconnect));
        

  }
  mysqli_close($tacconnect);
  
function findDirection($degree){
  if($degree==0){return 'E';}
  else if($degree>0 && $degree<=80){return 'NE';}
  else if($degree>80 && $degree<=100){return 'N';}
  else if($degree>100 && $degree<=170){return 'NW';}
  else if($degree>170 && $degree<=190){return 'W';}
  else if($degree>190 && $degree<=260){return 'SW';}
  else if($degree>260 && $degree<=280){return 'S';}
  else if($degree>280 && $degree<360){return 'SE';}
   
  

}


function vehile_Details($tacserver,$tacusername,$tacpassword,$tacdb,$accounts){
 $result=array(array());
 $tacconnect = mysqli_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database: " . mysqli_error($tacconnect));
       mysqli_select_db($tacconnect,$tacdb);
       ///////////////////////////////////////////////////////////
       $vehicleDetails="select vehicleID,maxTankVoltage,minTankVoltage,voltageStatus,conversionRate,potentiometer,fuelTracker,acType,minAnalog,maxAnalog,digitalInputOn,digitalInputOff from autoVehicles where accountID='$accounts'";
       $qry1 = mysqli_query($tacconnect, $vehicleDetails) or die(mysqli_error($tacconnect));
        
       error_log($vehicleDetails);
       $rowcount=0;
       while($row = mysqli_fetch_assoc($qry1)){
           
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

   mysqli_close($tacconnect);
   return $result;
}
function getGoogleAddress($lat,$lng)
//function getaddress($lat,$lng)
{
//error_log($lat."    ".$lng);
$url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=false';
$json = @file_get_contents($url);
$data=json_decode($json);
$status = $data->status;
//error_log($data->results[0]->formatted_address);
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

function getsimphoneNumber($deviceID){
    global $driverinfo;
    $simphoneNumbers='';
    for($i=0;$i<count($driverinfo);$i++){
         if($deviceID==$driverinfo[$i][0]){
            $simphoneNumbers=$driverinfo[$i][1];
            break;
         }
    }

    return $simphoneNumbers;
}
function getDriverInfo(){
  global $gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$driverinfo,$userID,$groupID;
$gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($gtsconnect));
mysqli_select_db($gtsconnect,$gtsdb);
  
       $query="select DISTINCT deviceID,contactPhone from Driver where accountID='$accountID'";
       
   
    error_log($query);
  $qry_result = mysqli_query($gtsconnect,$query) or die(mysqli_error($gtsconnect));
   $i=0;
    //error_log('dddddddddddddddddddddddddddddddddddd');
   while($row = mysqli_fetch_assoc($qry_result)){
       $driverinfo[$i][0]=$row['deviceID'];
       $driverinfo[$i][1]=$row['contactPhone'];
       
    //error_log('raaaaaaaaaaammmmmmmm'.$lastLocaton[$i][1].'     '.$lastLocaton[$i][0]);
          $i++;
   }
   //echo $i.'count';
mysqli_close($gtsconnect);
}

function getLastLocation(){
  global $gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$lastLocaton,$userID,$groupID;
$gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($gtsconnect));
mysqli_select_db($gtsconnect,$gtsdb);
  
       $query="select DISTINCT a.deviceID as deviceID,a.lastGPSTimestamp as lastGPSTimestamp,a.description as description,a.vehicleModel as vehicleModel,a.uniqueID as uniqueID,b.groupID as groupID from Device a,DeviceList b where a.accountID='$accountID' and b.accountID='$accountID' and a.deviceID=b.deviceID   order by b.groupID,a.lastGPSTimestamp";
       
   
    error_log($query);
  $qry_result = mysqli_query($gtsconnect, $query) or die(mysqli_error($gtsconnect));
   $i=0;
    //error_log('dddddddddddddddddddddddddddddddddddd');
   while($row = mysqli_fetch_assoc($qry_result)){
       $lastLocaton[$i][0]=$row['deviceID'];
        $lastLocaton[$i][1]=$row['lastGPSTimestamp'];
       $lastLocaton[$i][2]=$row['description'];
       $lastLocaton[$i][3]=$row['vehicleModel'];
       $lastLocaton[$i][4]=$row['simPhoneNumber'];
       $lastLocaton[$i][5]=$row['uniqueID'];
       $lastLocaton[$i][6]=$row['groupID'];
    //error_log('raaaaaaaaaaammmmmmmm'.$lastLocaton[$i][1].'     '.$lastLocaton[$i][0]);
          $i++;
   }
   //echo $i.'count';
mysqli_close($gtsconnect);
}

function currentDevices(){
  global $gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$userID,$groupID;
$gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($gtsconnect));
mysqli_select_db($gtsconnect, $gtsdb);
     $query="select a.deviceID as deviceID,a.lastGPSTimestamp as lastGPSTimestamp,a.description as description,a.vehicleModel as vehicleModel,a.uniqueID as uniqueID,b.groupID as groupID from Device a,DeviceList b where a.accountID='$accountID' and b.groupID='$groupID' and b.accountID='$accountID' and a.deviceID=b.deviceID  order by b.groupID,a.lastGPSTimestamp";
        
    if($userID1 == "admin" || $userID1 == "administrator"){
       // $query = "SELECT deviceID,lastGPSTimestamp,description,vehicleModel,simPhoneNumber,uniqueID FROM Device WHERE accountID ='$accountID'";
        $query="select DISTINCT a.deviceID as deviceID,a.lastGPSTimestamp as lastGPSTimestamp,a.description as description,a.vehicleModel as vehicleModel,a.uniqueID as uniqueID,b.groupID as groupID from Device a,DeviceList b,Driver c where a.accountID='$accountID' and b.accountID='$accountID' and a.deviceID=b.deviceID   order by b.groupID,a.lastGPSTimestamp";
       
      }
   $lastLocaton=array(array());
    error_log($query);
  $qry_result = mysqli_query($gtsconnect, $query) or die(mysqli_error($gtsconnect));
   $i=0;
    //error_log('dddddddddddddddddddddddddddddddddddd');
   while($row = mysqli_fetch_assoc($qry_result)){
       $lastLocaton[$i][0]=$row['deviceID'];
        $lastLocaton[$i][1]=$row['lastGPSTimestamp'];
       $lastLocaton[$i][2]=$row['description'];
       $lastLocaton[$i][3]=$row['vehicleModel'];
       $lastLocaton[$i][4]=$row['simPhoneNumber'];
       $lastLocaton[$i][5]=$row['uniqueID'];
       $lastLocaton[$i][6]=$row['groupID'];
    //echo '<br>'.$lastLocaton[$i][1].'     '.$lastLocaton[$i][0];
          $i++;
   }
   //echo $i.'count';
mysqli_close($gtsconnect);
   return $lastLocaton;
}


function secondsToTimeFormate($TotalAcOnTime){
	$hours=0;
	$min=0;
	$sec=0;
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
		 $tempsec=$TotalAcOnTime%60;
		 
		 return $min." Min :".secondsToTimeFormate($tempsec);
	}else{
	     return $TotalAcOnTime." sec";	
	}
	
}


function getGroups($gtsserver, $gtsusername, $gtspassword,$gtsdb,$accounts,$userID){
    $result=array();
   $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($gtsconnect));
   mysqli_select_db($gtsconnect, $gtsdb);
  // $vehicleDetails="select vehicleModel,vehicleMake,lastValidLatitude,lastValidLongitude,deviceID from Device where accountID='$accounts'";
    $vehicleDetails='';
    if($userID == "admin" || $userID == "administrator"){
         $vehicleDetails="select DISTINCT groupID from DeviceGroup where accountID='$accounts'";
    }else{
         $vehicleDetails="select DISTINCT groupID from GroupList where accountID='$accounts' and userID='$userID'";
    }
   error_log($vehicleDetails.' Ramana ssssss');
   $qry1 = mysqli_query($gtsconnect, $vehicleDetails) or die(mysqli_error($gtsconnect));
   $rowcount=0;
   error_log('hai  ');
   while($row = mysqli_fetch_assoc($qry1)){
        $result[$rowcount]=$row['groupID'];
        $rowcount++;
       //error_log($row['groupID'].'   kkkkkkkkkkkkkkkk');
   }
   if($rowcount==0){
       $result[$rowcount]='';
   }
   mysqli_close($gtsconnect);
   return $result;

}

function alldevices(){
  global $gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID;
 $alldevices=array();
$gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($gtsconnect));
mysqli_select_db($gtsconnect,$gtsdb);
     $query="select deviceID from Device  where a.accountID='$accountID'";
   
    error_log($query);
  $qry_result = mysqli_query($gtsconnect, $query) or die(mysqli_error($gtsconnect));
   $i=0;
    //error_log('dddddddddddddddddddddddddddddddddddd');
   while($row = mysqli_fetch_assoc($qry_result)){
       $alldevices[$i][0]=$row['deviceID'];
       
    //error_log('raaaaaaaaaaammmmmmmm'.$lastLocaton[$i][1].'     '.$lastLocaton[$i][0]);
          $i++;
   }
mysqli_close($gtsconnect);
  return $alldevices;
}


function mailSendToAccount($mailperAccount,$totalReport,$accountID,$reportType){
  //echo $totalReport;
if ($totalReport!=""){
    $from = " Glovision <support@glovision.co>";
    $host ="144.217.228.80";
    $username = "alerts@glovision.co";
    $password = 'Gl0v1$ion12';
    error_log(count($mailperAccount).' no accounts');
    for ($mailIndx=0;$mailIndx<count($mailperAccount);$mailIndx++){
        $to ="Recipient <".$mailperAccount[$mailIndx].">";
        $headers = array ('MIME-Version' => '1.0rn',
        'Content-Type' => "text/html; charset=ISO-8859-1rn",
        'From' => $from,
        'To' => $to,
        'Subject' =>$accountID.' '.$reportType
        );
        error_log('1');
        $smtp = Mail::factory('smtp',
        array ('host' => $host,
           'auth' => true,
           'username' => $username,
           'password' => $password));
        
        $mail = $smtp->send($to,$headers,$totalReport);
        error_log('3');
        if (PEAR::isError($mail)) {
           error_log('222222222222222');
           echo("<p>" . $mail->getMessage() . "</p>");
        } else {
          error_log('ddddddddddd');
           echo("<p>Message successfully sent!</p>");
        }
    }
}
}

function getMails($tacserver,$tacusername,$tacpassword,$tacdb,$accountId){
$tacconnect = mysqli_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database: " . mysqli_error());
mysqli_select_db($tacconnect,$tacdb);

$query1 = "SELECT DISTINCT emailaddress FROM email WHERE accountID ='$accountId'";

error_log($query1);
$qry_result = mysqli_query($tacconnect, $query1) or die(mysqli_error());

$mails=Array();
$i=0;

 while($row = mysqli_fetch_assoc($qry_result))
  {
  
    $mails[$i++]= $row['emailaddress'];
      error_log($row['emailaddress']);    
        
                                           
  }
mysqli_close($tacconnect);
return $mails;
}

/*

<script type='text/javascript' src='../js/tablesort.js'></script><script type='text/javascript'>

var TSort_Data = new Array ('sort', 'i', 's', 's',);
tsRegister();

</script>
   'i' - Column contains integer data. If the column data contains a number followed by text then the text will ignored. For example, "54note" will be interpreted as "54".

'n' - Column contains integer number in which all three-digit groups are optionally separated from each other by commas. For example, column data "100,000,000" is treated as "100000000" when type of data is set to 'n', or as "100" when type of data is set to 'i'.

'f' - Column contains floating point numbers in the form ###.###.

'g' - Column contains floating point numbers in the form ###.###. Three-digit groups in the floating-point number may be separated from each other by commas. For example, column data "65,432.1" is treated as "65432.1" when type of data is set to 'g', or as "65" when type of data is set to 'f'.

'h' - column contains HTML code. The script will strip all HTML code before sorting the data.

's' - column contains plain text data.

'c' - column contains dollar amount, prefixed by '$' character. The amount may contain commas, separating three-digit groups, like this: $1,234,567.89

'd' - column contains a date.

'' - do not sort the column.
*/

?>
