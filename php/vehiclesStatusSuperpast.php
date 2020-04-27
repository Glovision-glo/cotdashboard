<?php 
include 'config.php';
//require_once 'Mail.php';
date_default_timezone_set('Asia/Kolkata');
//error_log('ramanaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaramana');

//error_log('ramanaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaramana');
$vehicleId=$_GET['vehicleID'];
$accountID=$_GET['accountID'];
$fromDate=$_GET['fromDate'];
$userID=$_GET['userID'];
$vuserID=$userID;
$toDate=$_GET['toDate'];
$currentLocation=$_GET['currentLocation'];
$offline=$_GET['offline'];
$crone=$_GET['crone'];
$reportFormate=$_GET['formate'];
$sing_or_multiple_records_per_vehicle='multiple';
if($fromDate==$toDate){
  $sing_or_multiple_records_per_vehicle='single';
}


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



$fromDate = strtotime($fromDate);
$toDate = strtotime($toDate);

$ydata  =array(array());
$ydata2 = array();

 if($currentLocation!='currentlocation'){  
   header('Content-type: application/json');
}
   $groupID='';
    $userID1=$userID;
    $lastLocaton=array(array());
    if($userID != "admin" || $userID != "administrator"){
          $result=array();
         $result=getGroups($gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$userID);
          $groupID=$result[0];
          //$groupID=$userID;
         if($groupID=='' || $groupID=='all'){$userID='admin';}
        
     }
    

   
    $rows1=0;
    $tacconnect = mysql_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database: " . mysql_error());
    //error_log($tacconnect);
    mysql_select_db($tacdb, $tacconnect);
     $j=0;
    $fastQuery="select accountID,deviceID,lastLocation,latitude,longitude,odometerReading,statusCode,speed,time,direction,discription,vehicleModel,simNumber, uniqueID,groupID,fuelVoltage from AssertLastEventStatus where accountID='$accountID' and groupID='$groupID'";
    if($userID=="admin" || $userID == "administrator"){
         $fastQuery="select accountID,deviceID,lastLocation,latitude,longitude,odometerReading,statusCode,speed,time,direction,discription,vehicleModel,simNumber, uniqueID,groupID,fuelVoltage from AssertLastEventStatus where accountID='$accountID'";
    }
    error_log($fastQuery);
     $qry_result = mysql_query($fastQuery) or die(mysql_error());
     //  error_log('after EventData');
       while($row = mysql_fetch_assoc($qry_result)) {
             $ydata[$j][0]=$row['fuelVoltage'];
             $ydata[$j][1]=$row['statusCode'];
             $ydata[$j][2]=$row['statusCode'];
             $ydata[$j][3]=$row['speed'];
             $ydata[$j][4]=$row['time'];
             $ydata[$j][5]=$row['odometerReading'];
             $ydata[$j][6]=$row['latitude'];
             $ydata[$j][7]=$row['longitude'];
             $ydata[$j][8]=$row['lastLocation'];
             $ydata[$j][9]=$row['deviceID'];
             $ydata[$j][10]="Not Connected";
             $ydata[$j][11]=$row['direction'];
             $ydata[$j][12]=$row['discription'];
             $ydata[$j][13]=$row['vehicleModel'];
             $ydata[$j][14]=$row['simNumber'];
             $ydata[$j][15]=$row['uniqueID'];
             $ydata[$j][16]=$row['groupID'];
             $j++;
       }

   mysql_close($tacconnect);
 error_log('after processss');
   $offlineVehicle24hours='';
   $c24=0;
   $offlineVehicle36hours='';
   $c36=0;
   $offlineVehicle48hours='';
   $c48=0;
   $offlineVehicle56hours='';
   $c56=0;
   $notTrackingVehicles='';
    $cmore=0;
   if($currentLocation=='currentlocation'){
            
            $result="<div  style='background: white;border-style: solid;border-color: #2E2E2E;'><table id='sort' style='width:100%;font-family:sans-serif; font-size: 8pt; color: #000000;align=left;'><thead style='background:#2E2E2E;color:white'><th>S.NO</th><th>VEHICLE NAME</th><th>DISTRICT</th><th>BASE LOCATION</th><th>CREW NUMBER</th><th>STATUS</th><th>SPEED</th><th>CURRENT LOCATION</th><th>LAST UPDATED</th><th>UNIT NO</th></thead><tbody>";
           $status='Idle';
           if($offline=="offline"){
                $result="<div  style='background: white;border-style: solid;border-color: #2E2E2E;'><table id='sort' style='width:100%;font-family:sans-serif; font-size: 8pt; color: #000000;align=left;'><thead style='background:#2E2E2E;color:white'><th>S.NO</th><th>VEHICLE NAME</th><th>DISTRICT</th><th>BASE LOCATION</th><th>CREW NUMBER</th><th>STATUS</th><th>SPEED</th><th>CURRENT LOCATION</th><th>LAST UPDATED</th><th>Since Last Event Time</th></thead><tbody>";
                $offlineVehicle24hours=$result;
                $offlineVehicle36hours=$result;
                $offlineVehicle48hours=$result;
                $offlineVehicle56hours=$result;
                $notTrackingVehicles=$result;
           }
            $offLineCount=0;
            /* $allgroups=array();
            if($userID == "admin" || $userID == "administrator"){
               $allgroups=getGroups($gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$userID);
            }else{
                $res=array();
                $res=getGroups($gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$userID);
                $allgroups[0]=$res[0];
            }
              $allGroupVehicle=array();
              $allIndx=0;*/
           //for($gindx=0;$gindx<count($allgroups);$gindx++){
                     //$result=$result."<thead style='background:#2E2E2E;color:white'><th colspan='10'>".$allgroups[$gindx]."</th><thead>";
             
           if($userID == "admin" || $userID == "administrator"){
                      $userID='avoidDuplecate';
                     // $userID == "administrator";
                      //$unset($lastLocaton);
                      $lastLocaton=array(array());
                      getLastLocation();
            }
           //for($vindx=0;$vindx<count($ydata);$vindx++){
                  
            //}   
                
               
                
               
               for($dc=0;$dc<count($lastLocaton);$dc++){  
                      
                      $status="no"; 
                      for($vindx=0;$vindx<count($ydata);$vindx++){
                             // error_log($ydata[$vindx][9].' ---  '.$lastLocaton[$dc][0]);
                             if($ydata[$vindx][9]==$lastLocaton[$dc][0]){
                                
                                   if($offline=="offline"){ //&& $allgroups[$gindx]==$ydata[$vindx][16]
                                   $hr=intval(abs(strtotime($ydata[$vindx][4])-strtotime(date('d-m-Y H:i:s')))/3600);
                                    //error_log($ydata[$vindx][4].'   '.strtotime(date('d-m-Y H:i:s')));
                                     if($hr >=24){
                                       //if($ydata[$vindx][1]=="61714"){$status='Online';}else{$status='Idle';}
                                        $dis1 = split("/",$ydata[$vindx][12]);
                                       $offLineCount++;
                                     // $allGroupVehicle[$allIndx++]=$ydata[$vindx][9];
                                      $result=$result."<tr><td>$offLineCount</td><td>".strtoupper($ydata[$vindx][9])."</td><td>".trim($dis1[0],'[')."</td><td>".trim($dis1[1],'[')."</td><td>".$ydata[$vindx][14]."</td><td>OffLine</td><td>".$ydata[$vindx][3]." Kmph</td><td>".$ydata[$vindx][8]."</td><td>".$ydata[$vindx][4]."</td><td>".secondsToTimeFormate(abs(strtotime($ydata[$vindx][4])-strtotime(date('d-m-Y H:i:s'))))."</td></tr>";
                                    }
                                    if($hr>24 && $hr <=36 && $crone=='yes'){
                                          $c24++;
                                          $offlineVehicle24hours=$offlineVehicle24hours."<tr><td>$c24</td><td>".strtoupper($ydata[$vindx][9])."</td><td>".trim($dis1[0],'[')."</td><td>".trim($dis1[1],'[')."</td><td>".$ydata[$vindx][14]."</td><td>OffLine</td><td>".$ydata[$vindx][3]." Kmph</td><td>".$ydata[$vindx][8]."</td><td>".$ydata[$vindx][4]."</td><td>".secondsToTimeFormate(abs(strtotime($ydata[$vindx][4])-strtotime(date('d-m-Y H:i:s'))))."</td></tr>";
 
                                    }
                                    if($hr>36 && $hr<=48 && $crone=='yes' ){
                                          $c36++;
                                          $offlineVehicle36hours= $offlineVehicle36hours."<tr><td>$c36</td><td>".strtoupper($ydata[$vindx][9])."</td><td>".trim($dis1[0],'[')."</td><td>".trim($dis1[1],'[')."</td><td>".$ydata[$vindx][14]."</td><td>OffLine</td><td>".$ydata[$vindx][3]." Kmph</td><td>".$ydata[$vindx][8]."</td><td>".$ydata[$vindx][4]."</td><td>".secondsToTimeFormate(abs(strtotime($ydata[$vindx][4])-strtotime(date('d-m-Y H:i:s'))))."</td></tr>";
                                    }
                                    if($hr>48 && $hr<=56 && $crone=='yes'){
                                         $c48++;
                                         $offlineVehicle48hours=$offlineVehicle48hours."<tr><td>$c48</td><td>".strtoupper($ydata[$vindx][9])."</td><td>".trim($dis1[0],'[')."</td><td>".trim($dis1[1],'[')."</td><td>".$ydata[$vindx][14]."</td><td>OffLine</td><td>".$ydata[$vindx][3]." Kmph</td><td>".$ydata[$vindx][8]."</td><td>".$ydata[$vindx][4]."</td><td>".secondsToTimeFormate(abs(strtotime($ydata[$vindx][4])-strtotime(date('d-m-Y H:i:s'))))."</td></tr>";
                                    }
                                    if($hr>56 && $crone=='yes'){
                                         $c56++;
                                         $offlineVehicle56hours=$offlineVehicle56hours."<tr><td>$c56</td><td>".strtoupper($ydata[$vindx][9])."</td><td>".trim($dis1[0],'[')."</td><td>".trim($dis1[1],'[')."</td><td>".$ydata[$vindx][14]."</td><td>OffLine</td><td>".$ydata[$vindx][3]." Kmph</td><td>".$ydata[$vindx][8]."</td><td>".$ydata[$vindx][4]."</td><td>".secondsToTimeFormate(abs(strtotime($ydata[$vindx][4])-strtotime(date('d-m-Y H:i:s'))))."</td></tr>";
                                    }
                                     
                                 }else {//if($allgroups[$gindx]==$ydata[$vindx][16])
                                   $offLineCount++;
                                  //$allGroupVehicle[$allIndx++]=$ydata[$vindx][9];
                                  if($ydata[$vindx][1]=="61714"){$status='Online';}else{$status='Idle';}
                                       $dis1 = split("/",$ydata[$vindx][12]);
                                       $result=$result."<tr><td>$offLineCount</td><td>".strtoupper($ydata[$vindx][9])."</td><td>".trim($dis1[0],'[')."</td><td>".trim($dis1[1],'[')."</td><td>".$ydata[$vindx][14]."</td><td>".$status."</td><td>".$ydata[$vindx][3]." Kmph</td><td>".$ydata[$vindx][8]."</td><td>".$ydata[$vindx][4]."</td><td>".trim($ydata[$vindx][15],'wtd_')."</td></tr>";
                                       
                                   }
                
                                  $status="yes";
                                  break; 
                              }
                       }//end for
                       if($status=="no"){// && $allgroups[$gindx]==$lastLocaton[$dc][6]
                              $g++;
                               
                              $offLineCount++;
                              $dis11 = split("]",$lastLocaton[$dc][2]);
                              //$allGroupVehicle[$allIndx++]=$lastLocaton[$dc][0];
                             //$re=trim($dis11[2],'[').'/'.trim($dis11[1],'[');
                             $result=$result."<tr><td>$offLineCount</td><td>".strtoupper($lastLocaton[$dc][0])."</td><td>".trim($dis11[2],'[')."</td><td>".trim($dis11[1],'[')."</td><td>".$lastLocaton[$dc][4]."</td><td>OffLine</td><td>".$lastLocaton[$dc][6]." </td><td>-----</td><td>No Tracking</td>";               
                             if($offline=="offline"){
                                     $result=$result."<td>----</td></tr>";
                                     $cmore++;
                                     $notTrackingVehicles=$notTrackingVehicles."<tr><td>$cmore</td><td>".strtoupper($lastLocaton[$dc][0])."</td><td>".trim($dis11[2],'[')."</td><td>".trim($dis11[1],'[')."</td><td>".$lastLocaton[$dc][4]."</td><td>OffLine</td><td>".$lastLocaton[$dc][6]." </td><td>-----</td><td>No Tracking</td><td>----</td></tr>";
                             }else{
                                   $result=$result."<td>".trim($lastLocaton[$dc][5],'wtd_')."</td></tr>";
                             }
                             
                       }
               }   //end for
                
                  
          
          // }//end group loop

            /*$result=$result."<thead style='background:#2E2E2E;color:white'><th colspan='10'>No Group Vehicles</th><thead>";
              for($vindx=0;$vindx<count($ydata);$vindx++){
                     if(in_array($ydata[$vindx][9], $alldevices)){$result=$result."<tr><td>$vindx</td><td>".$ydata[$vindx][9]."</td><td>".trim($dis1[0],'[')."</td><td>".trim($dis1[1],'[')."</td><td>".$ydata[$vindx][14]."</td><td>".$status."</td><td>".$ydata[$vindx][3]." Kmph</td><td>".$ydata[$vindx][8]."</td><td>".$ydata[$vindx][4]."</td><td>".trim($ydata[$vindx][15],'wtd_')."</td></tr>";}
              }*/
               
    
        $result1 =$result.'<tbody></table></div>';
        $offlineVehicle24hours=$offlineVehicle24hours.'<tbody></table></div>';
        $offlineVehicle36hours=$offlineVehicle36hours.'<tbody></table></div>';
        $offlineVehicle48hours=$offlineVehicle48hours.'<tbody></table></div>';
        $offlineVehicle56hours=$offlineVehicle56hours.'<tbody></table></div>';
        $notTrackingVehicles=$notTrackingVehicles.'<tbody></table></div>';
        $header="All Vehicle Status Report <br>Reporting Time:".date('d-m-Y h:i:s a')."</h5>";
       if($offline=="offline"){
             $header="OffLine Vehicles <br>Reporting Time:".date('d-m-Y h:i:s a')."</h5>";
        }
           $image=$accountID.'.png';
           if(strpos($accountID,'gvk')>-1){
               $image='gvkemri.jpg';
           }
           if($reportFormate=="excel" || $reportFormate=="word"){
                 echo $result1;
           }else{
             $echoReport= "<html><style>
              table {
                   border-collapse: collapse;
                   }
                   table, td, th {
                       border: 1px solid black;
                    }</style> <script src='https://code.jquery.com/jquery.js'></script>
<script type='text/javascript' src='//cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.13.3/jquery.tablesorter.min.js'></script>
    <script>3
	
$(document).ready(function () {
    $('#sort').tablesorter();
});</script><body><center><div>
          <img alt='' src='http://track.glovision.co:8080/statictrack/images/custom/".$image."'>
         </div><h5>AccountID: $accountID<br>UserID:$vuserID<br>";
          echo $echoReport.$header.$result1."</center></body></html>";
          if($offline=="offline" && $crone=='yes' && $accountID!='gvkrajasthan'){
              if($c24>0){
                $totalReport=$echoReport.'No Events Since More Than 24 hours<br> Reporing Time'.date('d-m-Y h:i:s a').$offlineVehicle24hours."</center></body></html>";
              $mailperAccount=array();
                 $mailperAccount[0]='ramana@glovision.co';
                 $mailperAccount[1]='support@glovision.co';
                 $mailperAccount[2 ]='aathenasolutions@gmail.com';
                 $mailperAccount[3]='yogeshpratap_singh@emri.in';
                 $mailperAccount[4]='sarabjeet_singh@emri.in';
                 $mailperAccount[5]='ramarao_l@emri.in';
                 $mailperAccount[6]='vadali_seshadiri@emri.in';
                 $mailperAccount[7]='utpal_dey@emri.in';
                 $mailperAccount[8]='deepak_kharbanda@emri.in';
                 $mailperAccount[9]='premil_shah@emri.in';
                 
                mailSendToAccount($mailperAccount,$totalReport,$accountID,'No Events Since More Than 24 hours');
              }
              if($c36>0){
               
                $totalReport=$echoReport.'No Events Since More Than 36 hours<br> Reporing Time'.date('d-m-Y h:i:s a').$offlineVehicle36hours."</center></body></html>";
             $mailperAccount=array();
                  $mailperAccount[0]='ramana@glovision.co';
                  $mailperAccount[1]='support@glovision.co';
                  $mailperAccount[2]='aathenasolutions@gmail.com';
                  $mailperAccount[3]='yogeshpratap_singh@emri.in';
                  $mailperAccount[4]='sarabjeet_singh@emri.in';
                  $mailperAccount[5]='ramarao_l@emri.in';
                  $mailperAccount[6]='vadali_seshadiri@emri.in';
                  $mailperAccount[7]='utpal_dey@emri.in';
                  $mailperAccount[8]='deepak_kharbanda@emri.in';
                  $mailperAccount[9]='premil_shah@emri.in';
         
               mailSendToAccount($mailperAccount,$totalReport,$accountID,'No Events Since More Than 36 hours');
              }
              if($c48>0){
                
                $totalReport=$echoReport.'No Events Since More Than 48 hours<br> Reporing Time'.date('d-m-Y h:i:s a').$offlineVehicle48hours."</center></body></html>";
             $mailperAccount=array();
                      $mailperAccount[0]='ramana@glovision.co';
                      $mailperAccount[1]='support@glovision.co';
                      $mailperAccount[2]='aathenasolutions@gmail.com';
                      $mailperAccount[3]='yogeshpratap_singh@emri.in';
                      $mailperAccount[4]='sarabjeet_singh@emri.in';
                      $mailperAccount[5]='ramarao_l@emri.in';
                      $mailperAccount[6]='vadali_seshadiri@emri.in';
                      $mailperAccount[7]='utpal_dey@emri.in';
                      $mailperAccount[8]='deepak_kharbanda@emri.in';
                      $mailperAccount[9]='premil_shah@emri.in';

               mailSendToAccount($mailperAccount,$totalReport,$accountID,'No Events Since More Than 48 hours');
              }
              if($c56>0){
                 
               $totalReport=$echoReport.'No Events Since More Than 56 hours<br> Reporing Time'.date('d-m-Y h:i:s a').$offlineVehicle56hours."</center></body></html>";
               $mailperAccount=array();
                  $mailperAccount[0]='ramana@glovision.co';
                  $mailperAccount[1]='support@glovision.co';
                  $mailperAccount[2]='aathenasolutions@gmail.com';
                  $mailperAccount[3]='yogeshpratap_singh@emri.in';
                  $mailperAccount[4]='sarabjeet_singh@emri.in';
                  $mailperAccount[5]='ramarao_l@emri.in';
                  $mailperAccount[6]='vadali_seshadiri@emri.in';
                  $mailperAccount[7]='utpal_dey@emri.in';
                  $mailperAccount[8]='deepak_kharbanda@emri.in';
                  $mailperAccount[9]='premil_shah@emri.in';

               mailSendToAccount($mailperAccount,$totalReport,$accountID,'No Events Since More Than 56 hours');
              }
              if($cmore>0){
                  
                 $totalReport=$echoReport.'Offline Vehicles Not communicating<br> Reporing Time'.date('d-m-Y h:i:s a').$notTrackingVehicles."</center></body></html>";
               $mailperAccount=array();
                $mailperAccount[0]='ramana@glovision.co';
                $mailperAccount[1]='support@glovision.co';
                $mailperAccount[2]='aathenasolutions@gmail.com';
                $mailperAccount[3]='yogeshpratap_singh@emri.in';
                $mailperAccount[4]='sarabjeet_singh@emri.in';
                $mailperAccount[5]='ramarao_l@emri.in';
                $mailperAccount[6]='vadali_seshadiri@emri.in';
                $mailperAccount[7]='utpal_dey@emri.in';
                $mailperAccount[8]='deepak_kharbanda@emri.in';
                $mailperAccount[9]='premil_shah@emri.in';
                $mailperAccount[10]='sanjay_khosla@emri.in';

                 mailSendToAccount($mailperAccount,$totalReport,$accountID,'Offline Vehicles Not communicating');
              }
          }
             //error_log($result1);
          }
   }else{
     // var_error_log($ydata);
      
      echo '{"markers": ' . json_encode($ydata) .'}';

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

   mysql_close($tacconnect);
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

function getLastLocation(){
  global $gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$lastLocaton,$userID,$groupID;
$gtsconnect = mysql_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysql_error());
mysql_select_db($gtsdb, $gtsconnect);
     $query="select a.deviceID as deviceID,a.lastGPSTimestamp as lastGPSTimestamp,a.description as description,a.vehicleModel as vehicleModel,c.contactPhone as simPhoneNumber,a.uniqueID as uniqueID,b.groupID as groupID from Device a,DeviceList b,Driver c where a.accountID='$accountID' and b.groupID='$groupID' and b.accountID='$accountID' and c.accountID='$accountID' and a.deviceID=b.deviceID and c.deviceID=a.deviceID and c.deviceID=b.deviceID and c.accountID='$accountID' order by b.groupID,a.lastGPSTimestamp";
        
    if($userID == "admin" || $userID == "administrator"){
       // $query = "SELECT deviceID,lastGPSTimestamp,description,vehicleModel,simPhoneNumber,uniqueID FROM Device WHERE accountID ='$accountID'";
        $query="select DISTINCT a.deviceID as deviceID,a.lastGPSTimestamp as lastGPSTimestamp,a.description as description,a.vehicleModel as vehicleModel,c.contactPhone as simPhoneNumber,a.uniqueID as uniqueID,b.groupID as groupID from Device a,DeviceList b,Driver c where a.accountID='$accountID' and b.accountID='$accountID' and c.accountID='$accountID' and a.deviceID=b.deviceID and c.deviceID=a.deviceID and c.deviceID=b.deviceID and c.accountID='$accountID' order by b.groupID,a.lastGPSTimestamp";
       
      }
     if($userID == "avoidDuplecate"){
        //  $query = "SELECT a.deviceID as deviceID,a.lastGPSTimestamp as lastGPSTimestamp,a.description as description,a.vehicleModel as vehicleModel,c.contactPhone as simPhoneNumber,a.uniqueID as uniqueID FROM Device a,Driver c WHERE a.accountID ='$accountID' and a.deviceID=c.deviceID";
         $query = "SELECT a.deviceID as deviceID,a.lastGPSTimestamp as lastGPSTimestamp,a.description as description,a.vehicleModel as vehicleModel,c.contactPhone as simPhoneNumber,a.uniqueID as uniqueID FROM Device a,Driver c WHERE a.accountID ='$accountID' and c.accountID ='$accountID' and a.deviceID=c.deviceID";
     }
    error_log($query);
  $qry_result = mysql_query($query) or die(mysql_error());
   $i=0;
    //error_log('dddddddddddddddddddddddddddddddddddd');
   while($row = mysql_fetch_assoc($qry_result)){
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
mysql_close($gtsconnect);
}

function currentDevices(){
  global $gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$userID,$groupID;
$gtsconnect = mysql_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysql_error());
mysql_select_db($gtsdb, $gtsconnect);
     $query="select a.deviceID as deviceID,a.lastGPSTimestamp as lastGPSTimestamp,a.description as description,a.vehicleModel as vehicleModel,a.uniqueID as uniqueID,b.groupID as groupID from Device a,DeviceList b where a.accountID='$accountID' and b.groupID='$groupID' and b.accountID='$accountID' and a.deviceID=b.deviceID  order by b.groupID,a.lastGPSTimestamp";
        
    if($userID1 == "admin" || $userID1 == "administrator"){
       // $query = "SELECT deviceID,lastGPSTimestamp,description,vehicleModel,simPhoneNumber,uniqueID FROM Device WHERE accountID ='$accountID'";
        $query="select DISTINCT a.deviceID as deviceID,a.lastGPSTimestamp as lastGPSTimestamp,a.description as description,a.vehicleModel as vehicleModel,a.uniqueID as uniqueID,b.groupID as groupID from Device a,DeviceList b,Driver c where a.accountID='$accountID' and b.accountID='$accountID' and a.deviceID=b.deviceID   order by b.groupID,a.lastGPSTimestamp";
       
      }
   $lastLocaton=array(array());
    error_log($query);
  $qry_result = mysql_query($query) or die(mysql_error());
   $i=0;
    //error_log('dddddddddddddddddddddddddddddddddddd');
   while($row = mysql_fetch_assoc($qry_result)){
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
mysql_close($gtsconnect);
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
   $gtsconnect = mysql_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysql_error());
   mysql_select_db($gtsdb, $gtsconnect);
  // $vehicleDetails="select vehicleModel,vehicleMake,lastValidLatitude,lastValidLongitude,deviceID from Device where accountID='$accounts'";
    $vehicleDetails='';
    if($userID == "admin" || $userID == "administrator"){
         $vehicleDetails="select DISTINCT groupID from DeviceGroup where accountID='$accounts'";
    }else{
         $vehicleDetails="select DISTINCT groupID from GroupList where accountID='$accounts' and userID='$userID'";
    }
   error_log($vehicleDetails.' Ramana ssssss');
   $qry1 = mysql_query($vehicleDetails) or die(mysql_error());
   $rowcount=0;
   error_log('hai  ');
   while($row = mysql_fetch_assoc($qry1)){
        $result[$rowcount]=$row['groupID'];
        $rowcount++;
       //error_log($row['groupID'].'   kkkkkkkkkkkkkkkk');
   }
   if($rowcount==0){
       $result[$rowcount]='';
   }
   mysql_close($gtsconnect);
   return $result;

}

function alldevices(){
  global $gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID;
 $alldevices=array();
$gtsconnect = mysql_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysql_error());
mysql_select_db($gtsdb, $gtsconnect);
     $query="select deviceID from Device  where a.accountID='$accountID'";
   
    error_log($query);
  $qry_result = mysql_query($query) or die(mysql_error());
   $i=0;
    //error_log('dddddddddddddddddddddddddddddddddddd');
   while($row = mysql_fetch_assoc($qry_result)){
       $alldevices[$i][0]=$row['deviceID'];
       
    //error_log('raaaaaaaaaaammmmmmmm'.$lastLocaton[$i][1].'     '.$lastLocaton[$i][0]);
          $i++;
   }
mysql_close($gtsconnect);
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
$tacconnect = mysql_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database: " . mysql_error());
mysql_select_db($tacdb, $tacconnect);

$query1 = "SELECT DISTINCT emailaddress FROM email WHERE accountID ='$accountId'";

error_log($query1);
$qry_result = mysql_query($query1) or die(mysql_error());

$mails=Array();
$i=0;

 while($row = mysql_fetch_assoc($qry_result))
  {
  
    $mails[$i++]= $row['emailaddress'];
      error_log($row['emailaddress']);    
        
                                           
  }
mysql_close($tacconnect);
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
