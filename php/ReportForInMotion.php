<?php 
include 'config.php';
require_once 'Mail.php';
date_default_timezone_set('Asia/Kolkata');

$tacconnect = mysqli_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database1: " . mysqli_error());
mysqli_select_db($tacconnect,$tacdb);

$deviceID=$_GET['vehicle'];
$group=$_GET['group'];
$accountID=$_GET['accountID'];
$cron=$_GET['cron'];
error_log($cron);
//$accountID='gvk-up-108';
$userID=$_GET['userID'];
$fromDate=$_GET['fromDate'];
$toDate=$_GET['toDate'];
//$fromDate='1429228800';
//$$toDate='1429265602';
$currentLocation=$_GET['currentLocation'];
$reportFormate=$_GET['formate'];
error_log($gtsserver.'seeeeeeeeeeeeeeeeeee');
$sing_or_multiple_records_per_vehicle='multiple';
if($fromDate==$toDate){
  $sing_or_multiple_records_per_vehicle='single';
}

$displayAccountID=$accountID;

//if($accountID=="gvk-up-108"){
  // $displayAccountID="Samajwadi Swasthya Sewa";

if($accountID=="gvk-up-102"){
   $displayAccountID="National Ambulance Service";

}



$now=time();
$diff=abs($fromDate-$now);
error_log($fromDate.'fromdate'.$now.'-now');
$diffindays = floor(($diff)/86400);
error_log($diffindays.'diff');
/*
if($diffindays>30){
$gtsserver ="198.100.146.168";
$gtsusername ="gts";
$gtspassword="opengts";
$gtsdb ="gts";

$fromDate=$fromDate+19800;
$toDate=$toDate+19800;
}
error_log($gtsserver.'-'.$gtsusername);
*/
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
       
    }else if($reportFormate=="pdf"){
       header("Content-type:application/pdf");

// It will be called downloaded.pdf
header("Content-Disposition:attachment;filename=downloaded.pdf");

// The PDF source is in original.pdf
//readfile("original.pdf");
   
     }
error_log($sing_or_multiple_records_per_vehicle);
//$fromDate = strtotime($fromDate);
//$toDate = strtotime($toDate);
error_log($fromDate);
$ydata  =array(array());
$ydata2 = array();

if($cron=="yes"){
      $toDayDate=strtotime(date('d-M-Y',strtotime("-1 days")).'');
       $d=date('d',$toDayDate);
       $m=date('m',$toDayDate);
       $y=date('Y',$toDayDate);
       $fromDate=strtotime(date("d-m-Y h:i:s a", mktime(0,0,0,$m,$d,$y)));
       $toDate=strtotime(date("d-m-Y h:i:s a", mktime(23,59,0,$m,$d,$y)));
} 
  


//'61714','61718','62467','61472','61717','62534','62502','
//'61472'   stop ,
//'61718', idle
//'61720' ;long idle;
//61477 loastlocaton
$groupLevel='';
$groupID='';
   $groupArray=array();
    
   if($group=="selectall"){
      if($userID != "admin" && $userID != "administrator"){
          //$result=array();
          $groupArray=getGroups($gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$userID);
          if(count($result)==0){
           $userID='admin';
           $groupArray=getGroups($gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$userID);
          }
         //if($groupID=='' || $groupID=='selectall'){$userID='admin';}
        
      }else{
           $groupArray=getGroups($gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$userID);
      }
   }else{
        $groupArray[0]=$group;
   }
$result1='';
for($gpindx=0;$gpindx<count($groupArray);$gpindx++){
  $lastLocaton=array(array());
  $groupID=$groupArray[$gpindx];
getLastLocation();
//$lastLocaton[0][0]='rj14pc7431';

$gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database2: " . mysqli_error());
mysqli_select_db($gtsconnect,$gtsdb);


for($lve=0;$lve<count($lastLocaton);$lve++){
  $deviceLevel='';
  $ydata  =array(array());
  $cumulativeDistance=0;
error_log($toDate.'      '. $fromDate);
         $query = "select deviceID,analog0,statusCode,speedKPH,from_unixtime( timestamp, '%d-%m-%Y %h:%i:%s %p' ) as time1,odometerOffsetKM+odometerKM as 'odometerKM',latitude,longitude,address,analog1,heading as direction,timestamp from EventData where accountID='$accountID' and deviceID='".$lastLocaton[$lve][0]."'  and timestamp between '$fromDate' and '$toDate'  and latitude<>0 and longitude<>0 order by timestamp asc";//timestamp='".$lastLocaton[$lve][1]."' limit 1
 error_log($query);
         $qry_result = mysqli_query($gtsconnect,$query) or die(mysqli_error());
       // error_log($query);

$j=0;
while($row = mysqli_fetch_assoc($qry_result))
{   
   
      if($row['latitude'] !="0"){
     //$ydata[$j][0]= $row['analog0'];//fuel votage
    
         $ydata[$j][0]=0;//fuel votage
         if( $row['statusCode']=="61715" || $row['statusCode']=="61713" || $row['statusCode']=="64842" || $row['statusCode']=="64840"){
       
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
          
         $ydata[$j][1]=$row['statusCode'];
         $ydata[$j][2]=$row['statusCode'];
         $ydata[$j][3]= floor($row['speedKPH']);
         $ydata[$j][4]=$row['time1'];
         //$ydata[$j][5]=intval($row['odometerKM']);
         $ydata[$j][5]=round($row['odometerKM'],1);
         $ydata[$j][6]=round($row['latitude'],5);
         $ydata[$j][7]=round($row['longitude'],5);

         $ydata[$j][8]=$row['address'];
 
         $ydata[$j][9]=$row['deviceID'];
         $ydata[$j][10]="Not Connected";
         $ydata[$j][11]=findDirection($row['direction']); 

          $dis11 = explode("]",$lastLocaton[$lve][2]);
          //$re=trim($dis11[2],'[').'/'.trim($dis11[1],'[');
          $ydata[$j][12]=trim($dis11[2],'[');
          $ydata[$j][13]=trim($dis11[1],'[');
          $ydata[$j][14]=$lastLocaton[$lve][3];
          $ydata[$j][15]=$lastLocaton[$lve][4];
         $ydata[$j][16]=$row['timestamp'];
  if($j>0 && $row['latitude']!= 0 && $row['longitude']!=0 && $ydata[$j-1][6]!=0 && $ydata[$j-1][7]!=0) {

                 $eventtoeventdistance=distance1($row['latitude'],$row['longitude'], $ydata[$j-1][6], $ydata[$j-1][7]);
                 $difftimes=abs($row['timestamp']-$ydata[$j-1][16]);
                 $maxDistanceTravelledperMint=2;//max km travelled per mint
                 $calculatedDistenceTravelledperMint=intval($eventtoeventdistance)/$difftimes;
              // echo $eventtoeventdistance."   ".$difftimes."  ".$row['time1']."   ".$ydata[$j-1][4]."<br>";
                 if(is_nan($eventtoeventdistance) || ($eventtoeventdistance/ $difftimes>0.025)){//if distnace greater than 2 km with 5 minits then distnace=0 bzc drift point in 
                }else{
                   $ydata[$j][5]=$eventtoeventdistance;
                   $travelDistance=$travelDistance+$eventtoeventdistance;
                   $j++;
               }
          }
        if($j==0){$j++;}

  //$j++;
         //echo $ydata[$j][9].'  '.$ydata[$j][2].'  '.$ydata[$j][4].'  '.$ydata[$j][5].'  '.$ydata[$j][8]."<br>";
//         $j++;
    }//end if
}//end while
//}//end for

// Report Generate Locatoin At that Time
  $odometer=0;
  $statuscode='';
  $startIndx=0;
  $result="<div  style='background: white;border-style: solid;border-color: #2E2E2E;width:100%;'><table border='1px solid black' style='width:100%;font-family:sans-serif; font-size: 8pt; color: #000000;align=left;'><thead style='background:#2E2E2E;color:white'><th>Vehicle No</th><th>District</th><th>Base Location</th><th>Crew Number</th><th>Start Date & Time</th><th>Start Locatoin</th><th>End Time</th><th>End Location</th><th>Cumulative Distance Travelled</th></thead><tbody>";
  $cumulativeDistance=0;
  $motionStart="stop";
  $countInmotion=0; 
  $realyDistance=0;
  for($vindx=0;$vindx<count($ydata);$vindx++){
       ///echo $ydata[$vindx][6].' '.$ydata[$vindx][7].' '.$ydata[$vindx][6].'  '.$ydata[$vindx][7].'<br>';
       if(($vindx+1)<(count($ydata)) && ( $ydata[$vindx][6]!=0 && $ydata[$vindx][7]!=0 && $ydata[$vindx+1][6]!=0 && $ydata[$vindx+1][7]!=0)){
           // $realyDistance=$realyDistance+distance1($ydata[$vindx][6],$ydata[$vindx][7],$ydata[$vindx+1][6],$ydata[$vindx+1][7]);
            $diffdis=distance1($ydata[$vindx][6],$ydata[$vindx][7],$ydata[$vindx+1][6],$ydata[$vindx+1][7]);

            
             $difftimes=abs(strtotime($ydata[$vindx][4])-strtotime($ydata[$vindx+1][4]));  
             //get google driving distance if time greater than 2 mints between two events

                  $dis=$diffdis;//distance1($ydata[$vindx][6],$ydata[$vindx][7],$ydata[$vindx+1][6],$ydata[$vindx+1][7]);
                  
                  if($accountID != "gvkrajasthan"){
                      if(is_nan($diffdis) || ($diffdis/$difftimes>0.025)){//if distnace greater than 2 km with 5 minits then distnace=0 bzc drift point  
                         $diffdis=0;
                         $dis=0;//-0.17;
                      }
                  }



            $min= abs(strtotime($ydata[$vindx][4])-strtotime($ydata[$vindx+1][4]))/60;
            $realyDistance=$realyDistance+$dis;

        }else{

              $dis=0;
 
      }
       //echo distance1($ydata[$vindx][6],$ydata[$vindx][7],$ydata[$vindx+1][6],$ydata[$vindx+1][7]).'   '. $ydata[$vindx][6].' '.$ydata[$vindx][7].' '.$ydata[$vindx][6].'  '.$ydata[$vindx][7].'<br>';
      if($ydata[$vindx][2]=='61714' && $motionStart=="stop"){
           $startIndx=$vindx;
           $statuscode=$ydata[$vindx][2];
           $motionStart='start';
        }
      if($statuscode != $ydata[$vindx][2] && $motionStart=="start"){
         //$beforcumulative=$cumulativeDistance;
         $cumulativeDistance=$cumulativeDistance+abs($ydata[$startIndx][5]-$ydata[$vindx][5]);
         //if($beforcumulative<$cumulativeDistance){
             $countInmotion++;
             $result =$result."<tr><td>".strtoupper($ydata[$vindx][9])."</td><td>".$ydata[$vindx][12]."</td><td>".$ydata[$vindx][13]."</td><td>".$ydata[$vindx][15]."</td><td>".$ydata[$startIndx][4]."</td><td>".$ydata[$startIndx][8]."<b>(".$ydata[$startIndx][6]."/".$ydata[$startIndx][7].")</b></td><td>".$ydata[$vindx][4]."</td><td>".$ydata[$vindx][8]."<b>(".$ydata[$vindx][6]."/".$ydata[$vindx][7].")</b></td><td>".round($realyDistance,2)." Km</td></tr>";
             $deviceLevel ="<tr><td>".strtoupper($ydata[$vindx][9])."</td><td>".$ydata[$vindx][12]."</td><td>".$ydata[$vindx][13]."</td><td>".$ydata[$vindx][15]."</td><td>".$ydata[$vindx][4]."</td><td>".$ydata[$vindx][8]."<b>(".$ydata[$vindx][6]."/".$ydata[$vindx][7].")</b></td><td>".round($realyDistance,2)." Km</td></tr>";
             $motionStart="stop";
             $startIndx=0;
             $statuscode='';
         //}
      }
      

  }
   if($motionStart=="start"){
      $countInmotion++;
      //$cumulativeDistance=$cumulativeDistance+abs($ydata[$startIndx][5]-$ydata[count($ydata)-1][5]);
     $result =$result."<tr><td>".strtoupper($ydata[count($ydata)-1][9])."</td><td>".$ydata[$vindx-1][12]."</td><td>".$ydata[$vindx-1][13]."</td><td>".$ydata[$vindx-1][15]."</td><td>".$ydata[$startIndx][4]."</td><td>".$ydata[$startIndx][8]."<b>(".$ydata[$startIndx][6]."/".$ydata[$startIndx][7].")</b></td><td>".$ydata[count($ydata)-1][4]."</td><td>".$ydata[count($ydata)-1][8]."<b>(".$ydata[count($ydata)-1][6]."/".$ydata[count($ydata)-1][7].")</b></td><td>".round($realyDistance,2)." Km</td></tr>";

     $deviceLevel ="<tr><td>".strtoupper($ydata[count($ydata)-1][9])."</td><td>".$ydata[$vindx-1][12]."</td><td>".$ydata[$vindx-1][13]."</td><td>".$ydata[$vindx-1][15]."</td><td>".$ydata[count($ydata)-1][4]."</td><td>".$ydata[count($ydata)-1][8]."<b>(".$ydata[count($ydata)-1][6]."/".$ydata[count($ydata)-1][7].")</b></td><td>".round($realyDistance,2)." Kms</td></tr>";

   }
    if($countInmotion>0){
        $result1 =$result1.$result.'<tbody></table></div>';
    }
   $groupLevel=$groupLevel.$deviceLevel;
    unset($ydata);
}//end for loop for device
   

}//end group    
if($cron=="yes"){
$totalReport='';
$totalReport =  "<html><style>
   table {
    border-collapse: collapse;
}
 table, td, th {
    border: 0px solid black;
}</style><body><center><div>
          <img alt='' src='".$image."'>
         </div><h5>AccountID: ".$displayAccountID."<br>UserID: $userID<br>Distance Travelled Report <br>From :".date('d-m-Y h:i:s a',$fromDate)."To :".date('d-m-Y h:i:s a',$toDate)."</h5><div  style='background: white;border-style: solid;border-color: #2E2E2E;width:100%;'><table style='width:100%;font-family:sans-serif; font-size: 8pt; color: #000000;align=left;'><thead style='background:#2E2E2E;color:white'><th>Vehicle No</th><th>District</th><th>Base Location</th><th>Crew Number</th><th>Trip End Time</th><th>Trip End Location</th><th>Cumulative Distance Travelled</th></thead><tbody>".$groupLevel."</tbody></table></div></center></body></html>";
error_log("hii");
$mailperAccount=array();
$mailperAccount=getMails($tacserver,$tacusername,$tacpassword,$tacdb,$accountID);
mailSendToAccount($mailperAccount,$totalReport,$accountID,'Distance Travelled Report');
echo $totalReport;
}
   $image='http://track.glovision.co:8080/statictrack/images/custom/'.$accountID.'.png';
 
  if(strpos($accountID,'gvk')>-1){
    $image='http://track.glovision.co:8080/statictrack/images/custom/gvkemri.jpg';
  }
if($cron!="yes"){
    if($reportFormate=="excel" || $reportFormate=="word"){
         echo $result1;
    }else{
   echo "<html><style></style><body><center><div>
          <img alt='' src='".$image."'>
         </div><h5>AccountID:".$displayAccountID."<br>UserID: $userID<br>Inmotion Report <br>From :".date('d-m-Y h:i:s a',$fromDate)."To :".date('d-m-Y h:i:s a',$toDate)."</h5>".$result1."</center></body></html>";
    }
}
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
function GetDrivingDistance($lat1, $lat2, $long1, $long2)
{
    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$lat1.",".$long1."&destinations=".$lat2.",".$long2."&mode=driving&language=pl-PL";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    // echo $response;
    $response_a = json_decode($response, true);
    $dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
    $time = $response_a['rows'][0]['elements'][0]['duration']['text'];

    return  $dist;
}

function vehile_Details($tacserver,$tacusername,$tacpassword,$tacdb,$accounts){
 $result=array(array());
 $tacconnect = mysqli_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database3: " . mysqli_error());
       mysqli_select_db($tacconnect,$tacdb);
       ///////////////////////////////////////////////////////////
       $vehicleDetails="select vehicleID,maxTankVoltage,minTankVoltage,voltageStatus,conversionRate,potentiometer,fuelTracker,acType,minAnalog,maxAnalog,digitalInputOn,digitalInputOff from autoVehicles where accountID='$accounts'";
       $qry1 = mysqli_query($tacconnect, $vehicleDetails) or die(mysqli_error());
     
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
  global $gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$lastLocaton,$userID,$groupID,$deviceID;
$gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database4: " . mysqli_error());
mysqli_select_db($gtsconnect,$gtsdb);
    $vehicleGroup=array();
   $vehiclecontact=array(); 
     $vehiclecontact=  vehiclecontact();
      
     $query="select a.deviceID as deviceID,a.lastGPSTimestamp as lastGPSTimestamp,a.description as description,a.vehicleModel as vehicleModel,a.uniqueID as uniqueID from Device a,DeviceList b where a.accountID='$accountID' and b.groupID='$groupID' and b.accountID='$accountID' and a.deviceID=b.deviceID";
        
    if(($userID == "admin" || $userID == "administrator") && $groupID=="selectall"){
          $vehicleGroup=getGroupVehicle();
        $query = "SELECT a.deviceID as deviceID,a.lastGPSTimestamp as lastGPSTimestamp,a.description as description,a.vehicleModel as vehicleModel,a.uniqueID as uniqueID FROM Device a WHERE a.accountID ='$accountID' ";
         
      }
     if($deviceID != 'selectall'){
           $vehicleGroup=getGroupVehicle();
          $query = "SELECT a.deviceID as deviceID,a.lastGPSTimestamp as lastGPSTimestamp,a.description as description,a.vehicleModel as vehicleModel,a.uniqueID as uniqueID FROM Device a WHERE a.accountID ='$accountID' and a.deviceID='$deviceID' ";
     }
    error_log($query);
  $qry_result = mysqli_query($gtsconnect,$query) or die(mysqli_error());
   $i=0;
    //error_log('dddddddddddddddddddddddddddddddddddd');
   while($row = mysqli_fetch_assoc($qry_result)){
       $lastLocaton[$i][0]=$row['deviceID'];
        $lastLocaton[$i][1]=$row['lastGPSTimestamp'];
         if($userID == "admin" || $userID == "administrator" || $userID=="tracking" || $userID=="monitoring" || $userID == "avoidDuplecate"){
              if($vehicleGroup[$row['deviceID']]=="" || $vehicleGroup[$row['deviceID']]==null){  $vehicleGroup[$row['deviceID']]="ungroup";}
             $lastLocaton[$i][2]="[".$row['deviceID']."][".$vehicleGroup[$row['deviceID']]."][".$vehicleGroup[$row['deviceID']]."]";
        } else{
              if($row['groupID']==""){$row['groupID']="ungroup";}
              $lastLocaton[$i][2]="[".$row['deviceID']."][".$row['groupID']."][".$row['groupID']."]";
           }


    //   $lastLocaton[$i][2]="[][][]" ;//$row['description'];
       $lastLocaton[$i][3]=$row['vehicleModel'];
       $lastLocaton[$i][4]=$vehiclecontact[$row['deviceID']]==null?"0000000000":$vehiclecontact[$row['deviceID']];//$row['simPhoneNumber'];
       $lastLocaton[$i][5]=$row['uniqueID'];
      //error_log('raaaaaaaaaaammmmmmmm'.$lastLocaton[$i][1].'     '.$lastLocaton[$i][0]);
          $i++;
   }
mysqli_close($gtsconnect);
}

  function getGroupVehicle(){
     global $id,$gtsserver,$gtsusername,$gtspassword,$gtsdb,$accountID;
     $result=array();
     $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($tacconnect));
     mysqli_select_db($gtsconnect,$gtsdb);
     $query="select groupID,deviceID from DeviceList where accountID='$accountID'";
     $qry1 = mysqli_query($gtsconnect,$query) or die(mysqli_error($gtsconnect));
     $rowcount=0;
      while($row = mysqli_fetch_assoc($qry1)){
        $result[$row['deviceID']]=$row['groupID'];
      }
      if($rowcount==0){
       $result[]='';
      }
       mysqli_close($gtsconnect);
      return $result;
}

function vehiclecontact(){
  global $gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$userID,$groupID;
$gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($gtsconnect));
mysqli_select_db($gtsconnect,$gtsdb);
   $query="select deviceID,contactPhone from Driver  where accountID='$accountID'";
   $lastLocaton=array();
  $qry_result = mysqli_query($gtsconnect,$query) or die(mysqli_error($gtsconnect));
   while($row = mysqli_fetch_assoc($qry_result)){
       $lastLocaton[$row['deviceID']]=$row['contactPhone'];
   }
   mysqli_close($gtsconnect);
   return $lastLocaton;
}

function getGroups($gtsserver, $gtsusername, $gtspassword,$gtsdb,$accounts,$userID){
    $result=array();
   $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database5: " . mysqli_error());
   mysqli_select_db($gtsconnect,$gtsdb);
  // $vehicleDetails="select vehicleModel,vehicleMake,lastValidLatitude,lastValidLongitude,deviceID from Device where accountID='$accounts'";
    $vehicleDetails='';
    if($userID == "admin" || $userID == "administrator"){
         $vehicleDetails="select DISTINCT groupID from DeviceGroup where accountID='$accounts'";
    }else{
         $vehicleDetails="select DISTINCT groupID from GroupList where accountID='$accounts' and userID='$userID'";
    }
   error_log($vehicleDetails);
   $qry1 = mysqli_query($gtsconnect,$vehicleDetails) or die(mysqli_error());
   $rowcount=0;
   //error_log('hai2222222222  ');
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

function mailSendToAccount($mailperAccount,$totalReport,$accountID,$reportType){
  //echo $totalReport;
if ($totalReport!=""){
    $mystring=$accountID;
    $findme ='gvk';
    $pos =strpos($mystring, $findme);
    $from="Glovision <support@glovision.co>";
    if($pos >=-1){
    $from = "GLOVISION Support<support@glovision.co>";
    }
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
$tacconnect = mysqli_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database6: " . mysqli_error());
mysqli_select_db($tacconnect,$tacdb);

$query1 = "SELECT DISTINCT emailaddress FROM email WHERE accountID ='$accountId'";

error_log($query1);
$qry_result = mysqli_query($tacconnect,$query1) or die(mysqli_error());

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


?>
