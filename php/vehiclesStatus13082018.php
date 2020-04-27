<?php 
include 'config.php';
require_once 'Mail.php';
include 'pdfcretor.php';
include 'Excelgenerator.php';
date_default_timezone_set('Asia/Kolkata');
//error_log('ramanaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaramana');
$tacconnect = mysqli_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database: " . mysqli_error($tacconnect));
//error_log($tacconnect);
mysqli_select_db($tacconnect,$tacdb);
//error_log('ramanaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaramana');
$vehicleId=$_GET['vehicleID'];
$accountID=$_GET['accountID'];
$fromDate=$_GET['fromDate'];
$userID=$_GET['userID'];
$vuserID=$userID;
$toDate=$_GET['toDate'];
$currentLocation=$_GET['currentLocation'];
$offline=$_GET['offline'];
$crone='';
$selectedgroup=$_GET['selectedgroup']=="ungroup"?"":$_GET['selectedgroup'];

error_log("logdis".''.$vehicleId.'-'.$accountID.'-'.$fromDate.'-'.$userID.'-'.$vuserID.'-'.$toDate.'-'.$currentLocation.'-'.$offline.'-'.$selectedgroup);

if(isset($_GET['crone'])) {
   $crone=$_GET['crone'];
}
$reportFormate=$_GET['formate'];
$sing_or_multiple_records_per_vehicle='multiple';
if($fromDate==$toDate){
  $sing_or_multiple_records_per_vehicle='single';
}
error_log($crone.' cron call');

//INSERT INTO `autocare_gts`.`AssertLastEventStatus` (`accountID`, `deviceID`, `lastLocation`, `latitude`, `longitude`, `odometerReading`, `statusCode`, `speed`, `time`, `direction`, `discription`, `vehicleModel`, `simNumber`, `uniqueID`, `groupID`, `fuelVoltage`) VALUES ('1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1'), ('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
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


error_log('vehicleStatus.php 1');

$fromDate = strtotime($fromDate);
$toDate = strtotime($toDate);

$ydata  =array(array());
$ydata2 = array();

 if($currentLocation!='currentlocation'){  
   header('Content-type: application/json');
}
   $groupID=$selectedgroup;
    $userID1=$userID;
    $lastLocaton=array(array());
    if($userID != "admin" && $userID != "administrator" && $userID != "tracking"  && $userID != "monitoring"){
          $result=array();
         $result=getGroups($gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$userID);
          $groupID=$result[0];
          //$groupID=$userID;
         if($groupID=='' || $groupID=='all'){$userID='admin';}
        
     }
    getLastLocation();
   
  //for no driver vehiles
    $rows1=0;
    $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($gtsconnect));
    mysqli_select_db($gtsconnect,$gtsdb);
     $j=0;
    for($x=0;$x<count($lastLocaton);$x++) {
       $tmpDev = $lastLocaton[$x][0];
       $tmpTimestamp = $lastLocaton[$x][1];
           $query="SELECT deviceID,statusCode,speedKPH,from_unixtime(timestamp, '%Y-%m-%d %H:%i:%s' ) as time1,odometerOffsetKM+odometerKM as 'odometerKM',latitude,longitude,address,heading as direction FROM EventData where accountID='$accountID' and deviceID='$tmpDev' and timestamp <='$tmpTimestamp' ORDER BY timestamp Desc Limit 1";
      error_log($query);
       $qry_result = mysqli_query($gtsconnect,$query) or die(mysqli_error());
     //  error_log('after EventData');
       while($row = mysqli_fetch_assoc($qry_result)) {
           if($row['latitude'] !="0" && $row['longitude'] !="0"){
               $ydata[$j][0]=0;//fuel votage
              if( $row['statusCode']=="61715" || $row['statusCode']=="61713" || $row['statusCode']=="64842" || $row['statusCode']=="64840" || $row['statusCode']=="61477"
){
            $ydata[$j][1]="61717";
            $ydata[$j][2]="61717";


         }else if($row['statusCode']=="61456" || $row['statusCode']=="61716" || $row['statusCode']=="61488" || $row['statusCode']=="64784" || $row['statusCode']=="61472"){

            $ydata[$j][1]="61717";
            $ydata[$j][2]="61717";

         }
             $currenttime=strtotime(date('d-m-Y h:i:s a',strtotime("-0 days")).'');
              $lastEvntTime=strtotime($row['time1']);
              $diffTime=intval(abs($currenttime-$lastEvntTime)/3600);//convet in to hours if diffrence morethen 12 hour comunication fails
              $ydata[$j][1]=$row['statusCode'];
              $ydata[$j][2]=$row['statusCode'];

              if($diffTime>=24){//24 hours since not getting any event
                 $ydata[$j][1]="0000";
                 $ydata[$j][2]="0000";
              }

       
          if($ydata[$j][2]=="61714"){
              $currenttime=strtotime(date('d-m-Y h:i:s a',strtotime("-0 days")).'');
              $lastEvntTime=strtotime($row['time1']);
              $diffTime=intval(abs($currenttime-$lastEvntTime)/60);
              $ydata[$j][3]= floor($row['speedKPH']);
              if($diffTime>15){ //if vehicle not comunicated more then 15 mints it will under idle
                   $ydata[$j][1]="61717";
                   $ydata[$j][2]="61717";
                   $ydata[$j][3]= 0;

              }
          }else{
              $ydata[$j][3]=0;

          }

            $ydata[$j][21]="";
             if($lastLocaton[$x][16]=="offroad" && $ydata[$j][2]=="0000" && $ydata[$j][1]=="0000"){
               $ydata[$j][1]="offroad";
               $ydata[$j][2]="offroad";
               $ydata[$j][21]="offroad";
            }
           $ydata[$j][4]=$row['time1'];
           $ydata[$j][5]=intval($row['odometerKM']);
           $ydata[$j][6]=round($row['latitude'],5);
           $ydata[$j][7]=round($row['longitude'],5);
           $ydata[$j][8]=strtoupper($row['address']);
           $ydata[$j][9]=$row['deviceID'];
           $ydata[$j][10]="Not Connected";
           $ydata[$j][11]=intval($lastLocaton[$x][14]);;//intval($row['direction']);
           $dis11 =explode("]",$lastLocaton[$x][2]);
           $re=trim($dis11[2],'[').'/'.trim($dis11[1],'[');
           $ydata[$j][12]=$re;
           $ydata[$j][13]=$lastLocaton[$x][3];
           $ydata[$j][14]=$lastLocaton[$x][4];
           if($currentLocation=='currentlocation'){
                  $ydata[$j][15]=$lastLocaton[$x][5];
           }
           $ydata[$j][16]=$lastLocaton[$x][6];
           $ydata[$j][17]=$lastLocaton[$x][7];
           $ydata[$j][18]=$lastLocaton[$x][8];
           $ydata[$j][19]=$lastLocaton[$x][9];
           $ydata[$j][20]="00:00:00";
           if($lastLocaton[$x][9]!=0 && $lastLocaton[$x][8]!=0)
               $ydata[$j][20]=secondsToTimeFormate(abs($lastLocaton[$x][8]-$lastLocaton[$x][9]));
           $j++;        
         }//end if
      }//while
    }
    

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
  $onlinecount=0;
  $runningcount=0;
  $idlecount=0;
   if($currentLocation=='currentlocation'){
            
            $result="<style type='text/css'>
.tablecss {
        
        width:100%;
        border-collapse: collapse;
        color:black;
        background:white;
        font-family:Arial,Verdana,sans-serif;
        font-size:13;
}
</style><div  style='background: white;border-style: solid;border-color: #2E2E2E;'><table id='sort' class='tablecss' border='1' style='width:100%;font-family:Arial,Verdana,sans-serif;font-size:13; color: #000000;align=left;'><tr style='background:#2E2E2E;color:white'><th>S.NO</th><th>VEHICLE NAME</th><th>GROUP</th><th>BRANCH NAME</th><th>SIM NUMBER</th><th>CREW NUMBER</th><th>STATUS</th><th> Lat/Long </th><th>SPEED</th><th>CURRENT LOCATION</th><th>LAST UPDATED</th><th>UNIT NO..</th></tr><tbody>";
           $status='Idle';
           if($offline=="offline"){
                $result="<style type='text/css'>
.tablecss {
        
        width:100%;
        border-collapse: collapse;
        color:black;
        background:white;
        font-family:Arial,Verdana,sans-serif;
        font-size:13;
}
</style><div  style='background: white;border-style: solid;border-color: #2E2E2E;'><table id='sort' class='tablecss' border='1' style='width:100%;font-family:sans-serif; font-size: 8pt; color: #000000;align=left;'><tr style='background:#2E2E2E;color:white'><th>S.NO</th><th>VEHICLE NAME</th><th>GROUP</th><th>BRANCH NAME</th><th>SIM NUMBER</th><th>CREW NUMBER</th><th>STATUS</th><th> Lat/Long </th><th>SPEED</th><th>CURRENT LOCATION</th><th>LAST UPDATED</th><th>Since Last Event Time</th></tr><tbody>";
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
             
           if($userID == "admin" || $userID == "administrator" || $userID == "tracking"  || $userID == "monitoring"){
                      $userID='avoidDuplecate';
                     // $userID == "administrator";
                      //$unset($lastLocaton);
                      $lastLocaton=array(array());
                      getLastLocation();
            }
           //for($vindx=0;$vindx<count($ydata);$vindx++){
                  
            //}   
                
              // saveing current status report all vehicle in db veriables
              $installedDevices=0;
              $trackingDevices=0;
              $runningDevices=0;
              $idleDevices=0;
              $offRoadDevices=0;
              $notTrackingDevices=0;
              $notRespondingDevices=0;

              $installedDevices=count($lastLocaton);
               
                

               for($dc=0;$dc<count($lastLocaton);$dc++){  
                      
                      $status="no"; 
                      for($vindx=0;$vindx<count($ydata);$vindx++){
                             // error_log($ydata[$vindx][9].' ---  '.$lastLocaton[$dc][0]);
                             if(strtolower($ydata[$vindx][9])==strtolower($lastLocaton[$dc][0])){
                                
                                   if($offline=="offline"){ //&& $allgroups[$gindx]==$ydata[$vindx][16]
                                         if($ydata[$vindx][21]=="offroad"){$offRoadDevices++;  continue;}
                                        
                                   $hr=intval(abs(strtotime($ydata[$vindx][4])-strtotime(date('d-m-Y H:i:s')))/3600);
                                    //error_log($ydata[$vindx][4].'   '.strtotime(date('d-m-Y H:i:s')));
                                    if($ydata[$vindx][1]=="61714"){$status='Online';}else{$status='Idle';}
                                     if($hr >=24){ //diff time 24 hours
                                       //if($ydata[$vindx][1]=="61714"){$status='Online';}else{$status='Idle';}
                                        $dis1 = explode("/",$ydata[$vindx][12]);
                                       $offLineCount++;
                                     // $allGroupVehicle[$allIndx++]=$ydata[$vindx][9];
                                      $result=$result."<tr><td>$offLineCount</td><td>".strtoupper($ydata[$vindx][9])."</td><td>".trim($dis1[0],'[')."</td><td>".trim($dis1[1],'[')."</td><td>".$ydata[$vindx][17]."</td><td>".$ydata[$vindx][14]."</td><td>OffLine</td><td>".$ydata[$vindx][6].'/'.$ydata[$vindx][7]."</td><td>".$ydata[$vindx][3]." Kmph</td><td>".$ydata[$vindx][8]."</td><td>".$ydata[$vindx][4]."</td><td>".secondsToTimeFormate(abs(strtotime($ydata[$vindx][4])-strtotime(date('d-m-Y H:i:s'))))."</td></tr>";
                                        $notTrackingDevices++;
                                       //  $offRoadDevices++;
                                    }
                                  else if($status=='Online' && $hr<=4){
                                        $runningDevices++;
                                        $trackingDevices++;
                                   }else if($status=='Idle' && $hr<=4){
                                        $trackingDevices++;
                                        $idleDevices++;
                                   }
  


                                    if($hr>=24 && $hr <=36 && $crone=='yes'){
                                          $c24++;
                                          $offlineVehicle24hours=$offlineVehicle24hours."<tr><td>$c24</td><td>".strtoupper($ydata[$vindx][9])."</td><td>".trim($dis1[0],'[')."</td><td>".trim($dis1[1],'[')."</td><td>".$ydata[$vindx][17]."</td><td>".$ydata[$vindx][14]."</td><td>OffLine</td><td>".$ydata[$vindx][6].'/'.$ydata[$vindx][7]."</td><td>".$ydata[$vindx][3]." Kmph</td><td>".$ydata[$vindx][8]."</td><td>".$ydata[$vindx][4]."</td><td>".secondsToTimeFormate(abs(strtotime($ydata[$vindx][4])-strtotime(date('d-m-Y H:i:s'))))."</td></tr>";
 
                                    }
                                    if($hr>36 && $hr<=48 && $crone=='yes' ){
                                          $c36++;
                                          $offlineVehicle36hours= $offlineVehicle36hours."<tr><td>$c36</td><td>".strtoupper($ydata[$vindx][9])."</td><td>".trim($dis1[0],'[')."</td><td>".trim($dis1[1],'[')."</td><td>".$ydata[$vindx][17]."</td><td>".$ydata[$vindx][14]."</td><td>OffLine</td><td>".$ydata[$vindx][6].'/'.$ydata[$vindx][7]."</td><td>".$ydata[$vindx][3]." Kmph</td><td>".$ydata[$vindx][8]."</td><td>".$ydata[$vindx][4]."</td><td>".secondsToTimeFormate(abs(strtotime($ydata[$vindx][4])-strtotime(date('d-m-Y H:i:s'))))."</td></tr>";
                                    }
                                    if($hr>48 && $hr<=56 && $crone=='yes'){
                                         $c48++;
                                         $offlineVehicle48hours=$offlineVehicle48hours."<tr><td>$c48</td><td>".strtoupper($ydata[$vindx][9])."</td><td>".trim($dis1[0],'[')."</td><td>".trim($dis1[1],'[')."</td><td>".$ydata[$vindx][17]."</td><td>".$ydata[$vindx][14]."</td><td>OffLine</td><td>".$ydata[$vindx][6].'/'.$ydata[$vindx][7]."</td><td>".$ydata[$vindx][3]." Kmph</td><td>".$ydata[$vindx][8]."</td><td>".$ydata[$vindx][4]."</td><td>".secondsToTimeFormate(abs(strtotime($ydata[$vindx][4])-strtotime(date('d-m-Y H:i:s'))))."</td></tr>";
                                    }
                                    if($hr>56 && $crone=='yes'){
                                         $c56++;
                                         $offlineVehicle56hours=$offlineVehicle56hours."<tr><td>$c56</td><td>".strtoupper($ydata[$vindx][9])."</td><td>".trim($dis1[0],'[')."</td><td>".trim($dis1[1],'[')."</td><td>".$ydata[$vindx][17]."</td><td>".$ydata[$vindx][14]."</td><td>OffLine</td><td>".$ydata[$vindx][6].'/'.$ydata[$vindx][7]."</td><td>".$ydata[$vindx][3]." Kmph</td><td>".$ydata[$vindx][8]."</td><td>".$ydata[$vindx][4]."</td><td>".secondsToTimeFormate(abs(strtotime($ydata[$vindx][4])-strtotime(date('d-m-Y H:i:s'))))."</td></tr>";
                                    }
                                     
                                 }else if($offline=="offroad"){
                                       if($ydata[$vindx][21]=="offroad"){    $offLineCount++;
                                            $offRoadDevices++;
                                           $result=$result."<tr><td>$offLineCount</td><td>".strtoupper($ydata[$vindx][9])."</td><td>".trim($dis1[0],'[')."</td><td>".trim($dis1[1],'[')."</td><td>".$ydata[$vindx][17]."</td><td>".$ydata[$vindx][14]."</td><td>Offroad1</td><td>".$ydata[$vindx][6].'/'.$ydata[$vindx][7]."</td><td>".$ydata[$vindx][3]." Kmph</td><td>".$ydata[$vindx][8]."</td><td>".$ydata[$vindx][4]."</td><td>".secondsToTimeFormate(abs(strtotime($ydata[$vindx][4])-strtotime(date('d-m-Y H:i:s'))))."</td></tr>";
                                       }
                                   }else {//if($allgroups[$gindx]==$ydata[$vindx][16])
                                     $offLineCount++;
                                  //$allGroupVehicle[$allIndx++]=$ydata[$vindx][9];
                                     if($ydata[$vindx][1]=="61714"){$status='Online';}else{$status='Idle';}
                                       $dis1 = explode("/",$ydata[$vindx][12]);
                                //       $result=$result."<tr><td>$offLineCount</td><td>".strtoupper($ydata[$vindx][9])."</td><td>".trim($dis1[0],'[')."</td><td>".trim($dis1[1],'[')."</td><td>".$ydata[$vindx][14]."</td><td>".$status."</td><td>".$ydata[$vindx][3]." Kmph</td><td>".$ydata[$vindx][8]."</td><td>".$ydata[$vindx][4]."</td><td>".trim($ydata[$vindx][15],'wtd_')."</td></tr>";
                               
//echo $offline.'  '.$status.'<br>';

                                      $hr=intval(abs(strtotime($ydata[$vindx][4])-strtotime(date('d-m-Y H:i:s')))/3600);


                                     if($offline=="online" && $hr<=4){
                                              $result=$result."<tr><td>".(++$onlinecount)."</td><td>".strtoupper($ydata[$vindx][9])."</td><td>".trim($dis1[0],'[')."</td><td>".trim($dis1[1],'[')."</td><td>".$ydata[$vindx][17]."</td><td>".$ydata[$vindx][14]."</td><td>".$status."</td><td>".$ydata[$vindx][6].'/'.$ydata[$vindx][7]."</td><td>".$ydata[$vindx][3]." Kmph</td><td>".$ydata[$vindx][8]."</td><td>".$ydata[$vindx][4]."</td><td>".trim($ydata[$vindx][15],'wtd_')."</td></tr>";

    
                                      }else if($offline=="running" && $status=="Online"){
                                            $result=$result."<tr><td>".(++$runningcount)."</td><td>".strtoupper($ydata[$vindx][9])."</td><td>".trim($dis1[0],'[')."</td><td>".trim($dis1[1],'[')."</td><td>".$ydata[$vindx][17]."</td><td>".$ydata[$vindx][14]."</td><td>".$status."</td><td>".$ydata[$vindx][6].'/'.$ydata[$vindx][7]."</td><td>".$ydata[$vindx][3]." Kmph</td><td>".$ydata[$vindx][8]."</td><td>".$ydata[$vindx][4]."</td><td>".trim($ydata[$vindx][15],'wtd_')."</td></tr>";
 

                                      }else if($offline=="idle" && $status=="Idle" && $hr<=4){
                                              $result=$result."<tr><td>".(++$idlecount)."</td><td>".strtoupper($ydata[$vindx][9])."</td><td>".trim($dis1[0],'[')."</td><td>".trim($dis1[1],'[')."</td><td>".$ydata[$vindx][17]."</td><td>".$ydata[$vindx][14]."</td><td>".$status."</td><td>".$ydata[$vindx][6].'/'.$ydata[$vindx][7]."</td><td>".$ydata[$vindx][3]." Kmph</td><td>".$ydata[$vindx][8]."</td><td>".$ydata[$vindx][4]."</td><td>".trim($ydata[$vindx][15],'wtd_')."</td></tr>";

                           
                                      }else if($offline==''){
                                            if($hr>=24){
                                               $status='offline';
                                               if($ydata[$vindx][21]=="offroad"){
                                                    $status='offroad';
                                                     $offRoadDevices++;
                                        
                                              }else{
                                                    $notTrackingDevices++;
                                             }                                                                                          


 
                                            }else if($status=='Online' && $hr<4){
                                                $runningDevices++;
                                                $trackingDevices++;
                                            }else if($status=='Idle' && $hr<4){
                                                 $trackingDevices++;
                                                 $idleDevices++;
                                             }
//                                            $result=$result."<tr><td>$offLineCount</td><td>".strtoupper($ydata[$vindx][9])."</td><td>".trim($dis1[0],'[')."</td><td>".trim($dis1[1],'[')."</td><td>".$ydata[$vindx][17]."</td><td>".$ydata[$vindx][14]."</td><td>".$status."</td><td>".$ydata[$vindx][3]." Kmph</td><td>".$ydata[$vindx][8]."</td><td>".$ydata[$vindx][4]."</td><td>".trim($ydata[$vindx][15],'wtd_')."</td></tr>";
                                            $result=$result."<tr><td>$offLineCount</td><td>".strtoupper($ydata[$vindx][9])."</td><td>".strtoupper($lastLocaton[$dc][6])."</td><td>".trim($dis1[1],'[')."</td><td>".$ydata[$vindx][17]."</td><td>".$ydata[$vindx][14]."</td><td>".$status."</td><td>".$ydata[$vindx][6].'/'.$ydata[$vindx][7]."</td><td>".$ydata[$vindx][3]." Kmph.</td><td>".$ydata[$vindx][8]."</td><td>".$ydata[$vindx][4]."</td><td>".trim($ydata[$vindx][15],'wtd_')."</td></tr>";

                                       }



        
                                }//end else if
                
                                  $status="yes";
                                  break; 
                              }
                     }//end for
                     if($status=="no" && ($offline=="offline" || $offline=="")){// && $allgroups[$gindx]==$lastLocaton[$dc][6]
                               if($lastLocaton[$dc][16]=="offroad"){ $offRoadDevices++;  continue; }
                             $resulstofoffline=ConformOfflineVehicle($tacserver,$tacusername,$tacpassword,$tacdb,$accountID,$lastLocaton[$dc][0]);
                            
                              $resulatarray=explode("*",$resulstofoffline);
      
                              $count=$resulatarray[0];
                              $timestamp=$resulatarray[1];

                               $offRoadDevices++; 
                              $g++;
                             if($count==0){

                                  if($lastLocaton[$dc][1]=="" || $lastLocaton[$dc][1]=="0"){
                              $notRespondingDevices++;
                                   }else{

                                 $notTrackingDevices++;
                              }


                                 $offLineCount++;
                                 $dis11 = explode("]",$lastLocaton[$dc][2]);
                                 //$allGroupVehicle[$allIndx++]=$lastLocaton[$dc][0];
                                 //$re=trim($dis11[2],'[').'/'.trim($dis11[1],'[');
                                 $result=$result."<tr><td>$offLineCount</td><td>".strtoupper($lastLocaton[$dc][0])."</td><td>".trim($dis11[2],'[')."</td><td>".trim($dis11[1],'[')."</td><td>".$lastLocaton[$dc][7]."</td><td>".$lastLocaton[$dc][4]."</td><td>OffLine</td><td>".$ydata[$vindx][6].'/'.$ydata[$vindx][7]."</td><td>".$lastLocaton[$dc][6]." </td><td>-----</td><td>No Tracking</td>";               
                                  if($offline=="offline"){
                                     $result=$result."<td>----</td></tr>";
                                     $cmore++;
                                     $notTrackingVehicles=$notTrackingVehicles."<tr><td>$cmore</td><td>".strtoupper($lastLocaton[$dc][0])."</td><td>".trim($dis11[2],'[')."</td><td>".trim($dis11[1],'[')."</td><td>".$lastLocaton[$dc][7]."</td><td>".$lastLocaton[$dc][4]."</td><td>OffLine</td><td>".$ydata[$vindx][6].'/'.$ydata[$vindx][7]."</td><td>".$lastLocaton[$dc][6]." </td><td>-----</td><td>No Tracking</td><td>----</td></tr>";
                                  }else{
                                   $result=$result."<td>".trim($lastLocaton[$dc][5],'wtd_')."</td></tr>";
                                  }//end if
                             }
                             else{
                                 $offLineCount++;
                                 if($lastLocaton[$dc][1]=="" || $lastLocaton[$dc][1]=="0"){
                                    $notRespondingDevices++;
                                 }else{
                                    $notTrackingDevices++;

                                  }

                                 $dis11 =explode("]",$lastLocaton[$dc][2]);
                                 //$allGroupVehicle[$allIndx++]=$lastLocaton[$dc][0];
                                 //$re=trim($dis11[2],'[').'/'.trim($dis11[1],'[');
                                 $result=$result."<tr><td>$offLineCount</td><td>".strtoupper($lastLocaton[$dc][0])."</td><td>".strtoupper($lastLocaton[$dc][6])."</td><td>".trim($dis11[1],'[')."</td><td>".$lastLocaton[$dc][7]."</td><td>".$lastLocaton[$dc][4]."</td><td>Offline</td><td>".$ydata[$vindx][6].'/'.$ydata[$vindx][7]."</td><td>".'0 Kph'." </td><td> </td><td>".date('Y-m-d h:i:s',$timestamp)."</td>";               
                                  if($offline=="offline"){
                                     $result=$result."<td>".secondsToTimeFormate(abs($timestamp-strtotime(date('d-m-Y H:i:s'))))."</td></tr>";
                                    // $cmore++;
                                     $c56++;
                                   
                                     $offlineVehicle56hours=$offlineVehicle56hours."<tr><td>$c56</td><td>".strtoupper($lastLocaton[$dc][0])."</td><td>".trim($dis11[2],'[')."</td><td>".trim($dis11[1],'[')."</td><td>".$lastLocaton[$dc][7]."</td><td>".$lastLocaton[$dc][4]."</td><td>OffLine</td><td>".$ydata[$vindx][6].'/'.$ydata[$vindx][7]."</td><td>".strtoupper($lastLocaton[$dc][6])." </td><td> </td><td>".date('Y-m-d h:i:s',$timestamp)."</td><td>".secondsToTimeFormate(abs($timestamp-strtotime(date('d-m-Y H:i:s'))))."</td></tr>";
                                  }else{
                                   $result=$result."<td>".trim($lastLocaton[$dc][5],'wtd_')."</td></tr>";
                                  }//end if
                                       
                              } //end if count */
                             
                   }else if( $status=="no" && $offline=="offroad"){
                           if($lastLocaton[$dc][16]=="offroad"){
                              $offRoadDevices++;
                                $result=$result."<tr><td>$offLineCount</td><td>".strtoupper($lastLocaton[$dc][0])."</td><td>".strtoupper($lastLocaton[$dc][6])."</td><td>".trim($dis11[1],'[')."</td><td>".$lastLocaton[$dc][7]."</td><td>".$lastLocaton[$dc][4]."</td><td>Offroad</td><td>".$ydata[$vindx][6].'/'.$ydata[$vindx][7]."</td><td>".'0 Kph'." </td><td> </td><td>".date('Y-m-d h:i:s',$timestamp)."</td>";
                           }
                   }
               }   //end for
                
                  
          
          // }//end group loop

            /*$result=$result."<thead style='background:#2E2E2E;color:white'><th colspan='10'>No Group Vehicles</th><thead>";
              for($vindx=0;$vindx<count($ydata);$vindx++){
                     if(in_array($ydata[$vindx][9], $alldevices)){$result=$result."<tr><td>$vindx</td><td>".$ydata[$vindx][9]."</td><td>".trim($dis1[0],'[')."</td><td>".trim($dis1[1],'[')."</td><td>".$ydata[$vindx][14]."</td><td>".$status."</td><td>".$ydata[$vindx][3]." Kmph</td><td>".$ydata[$vindx][8]."</td><td>".$ydata[$vindx][4]."</td><td>".trim($ydata[$vindx][15],'wtd_')."</td></tr>";}
              }*/
       if($offline==''){
          saveStatusReport($accountID,$installedDevices,$trackingDevices,$runningDevices,$idleDevices,$offRoadDevices,$notTrackingDevices,$notRespondingDevices);                 
           }               
   
 
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
             $echoReport= "<html> <script src='https://code.jquery.com/jquery.js'></script>
<script type='text/javascript' src='//cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.13.3/jquery.tablesorter.min.js'></script>
    <body><center><div>
          <img alt='' src='http://track.glovision.co:8080/statictrack/images/custom/".$image."'>
         </div><h5>AccountID: $accountID<br>UserID:$vuserID<br>";
          echo $echoReport.$header.$result1."</center></body></html>";
          
      if($crone=='yes' ){
          //pdf creator       
         $newpdf=new PdfGeneretor();
          $filename=$offline;
           if($offline==""){
              $filename="currentlocation";
            }
          $newpdf->pdfcreation($echoReport.$header.$result1."</center></body></html>",$accountID.'_'.$selectedgroup.'_'.time()."_".$filename,null,"Offline Report",date('d-m-Y'));
         $excel=new ExcelService();
         $excel->generateExcel($echoReport.$header.$result1."</center></body></html>",$accountID.'_'.$selectedgroup.'_'.time()."_".$filename);
       }

          if($offline=="offline" && $crone=='yes' ){
             // saveStatusReport($accountID,$installedDevices,$trackingDevices,$runningDevices,$idleDevices,$offRoadDevices,$notTrackingDevices,$notRespondingDevices);


            error_log($c24."-24 and ".$c36.'-36 and '.$c48.'-48 and '.$c56.'-56 and '.$cmore.'-offline');
              if($c24>0){

                $totalReport=$echoReport.'No Events Since More Than 24 hours<br> Reporing Time'.date('d-m-Y h:i:s a').$offlineVehicle24hours."</center></body></html>";
              $time=24;
              $mailperAccount=getMails($tacserver,$tacusername,$tacpassword,$tacdb,$accountID,$time);
               //echo $totalReport;
                mailSendToAccount($mailperAccount,$totalReport,$accountID,'No Events Since More Than 24 hours');
              }
              if($c36>0){
error_log("hii 36-cat");
                $totalReport=$echoReport.'No Events Since More Than 36 hours<br> Reporing Time'.date('d-m-Y h:i:s a').$offlineVehicle36hours."</center></body></html>";
             $time=36;
              $mailperAccount=getMails($tacserver,$tacusername,$tacpassword,$tacdb,$accountID,$time);
                 //         echo $totalReport;
              mailSendToAccount($mailperAccount,$totalReport,$accountID,'No Events Since More Than 36 hours');
              }
              if($c48>0){

                $totalReport=$echoReport.'No Events Since More Than 48 hours<br> Reporing Time'.date('d-m-Y h:i:s a').$offlineVehicle48hours."</center></body></html>";
             $time=48;
              $mailperAccount=getMails($tacserver,$tacusername,$tacpassword,$tacdb,$accountID,$time);
                   //      echo $totalReport;
             mailSendToAccount($mailperAccount,$totalReport,$accountID,'No Events Since More Than 48 hours');
              }
              if($c56>0){

               $totalReport=$echoReport.'No Events Since More Than 56 hours<br> Reporing Time'.date('d-m-Y h:i:s a').$offlineVehicle56hours."</center></body></html>";
                $time=56;
              $mailperAccount=getMails($tacserver,$tacusername,$tacpassword,$tacdb,$accountID,$time);
              //       echo $totalReport;
              mailSendToAccount($mailperAccount,$totalReport,$accountID,'No Events Since More Than 56 hours');
              }
              if($cmore>0){

                 $totalReport=$echoReport.'Offline Vehicles Not communicating<br> Reporing Time'.date('d-m-Y h:i:s a').$notTrackingVehicles."</center></body></html>";
                $time=0;
              $mailperAccount=getMails($tacserver,$tacusername,$tacpassword,$tacdb,$accountID,$time);
             //            echo $totalReport;
               mailSendToAccount($mailperAccount,$totalReport,$accountID,'Offline Vehicles Not communicating');
              }
          }
             //error_log($result1);
          }
   }else{
     // var_error_log($ydata);
      
      echo '{"markers": ' . json_encode(utf8ize($ydata)) .'}';

   }

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



function offroadvehicles(){
     global $tacserver,$tacusername,$tacpassword,$tacdb,$accountID,$userID,$groupID;
      $tacconnect = mysqli_connect("localhost", $tacusername, $tacpassword) or die ("Unable to connect to the database: " . mysqli_error($tacconnect));
      mysqli_select_db($tacconnect,$tacdb);
      $timestamp=strtotime(date('d-m-Y',time()));
      $result=array();
      $query="select * from vehicleOffroad where accountID='$accountID' and offlineTime='$timestamp'";
      if($groupID!="selectall" && $groupID!="")
          $query="select * from vehicleOffroad where accountID='$accountID' and offlineTime='$timestamp' and groupID='$groupID'";
      $qry1= mysqli_query($tacconnect,$query) or die(mysqli_error($tacconnect));
      $rowcount=0;
      while($row = mysqli_fetch_assoc($qry1)){
          $result[strtolower($row['vehicleID'])]=$row['groupID'];
      }
      return $result;

}


function  saveStatusReport($accountID,$installedDevices,$trackingDevices,$runningDevices,$idleDevices,$offRoadDevices,$notTrackingDevices,$notRespondingDevices){
       global $tacserver,$tacusername,$tacpassword,$tacdb;
       $tacconnect = mysqli_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database: " . mysqli_error($tacconnect));
       mysqli_select_db($tacconnect,$tacdb);
       $timestamp=strtotime(date('d-m-Y',time()));
       ///////////////////////////////////////////////////////////
         if($offRoadDevices==$notTrackingDevices){
           $offRoadDevices=0;
          }
       $diff=$installedDevices-($trackingDevices+$offRoadDevices+$notTrackingDevices+$notRespondingDevices);
      $idleDevices=$diff+$idleDevices;
      $trackingDevices=$diff+$trackingDevices;
       $checkquery="select * from vehicleStatusReport where accountID='$accountID' and timestamp='$timestamp'";
        $checkresult = mysqli_query($tacconnect,$checkquery) or die(mysqli_error($tacconnect));
        $noofrows= mysqli_num_rows($checkresult);
       if($noofrows>0){
        $vehicleDetails="update vehicleStatusReport set InstalledDevices='$installedDevices',TrackingDevices='$trackingDevices',RunningDevices='$runningDevices',IdleDevices='$idleDevices',OffroadDevices='$offRoadDevices',NotTrackingDevices='$notTrackingDevices',NotRespondingDevices='$notRespondingDevices' where accountID='$accountID' and timestamp='$timestamp'";
       $qry1 = mysqli_query($tacconnect,$vehicleDetails) or die(mysqli_error());

   //  echo  $vehicleDetails;

      }else{
         $vehicleDetails="INSERT INTO vehicleStatusReport (accountID, timestamp, InstalledDevices,TrackingDevices,RunningDevices,IdleDevices,OffroadDevices,NotTrackingDevices,NotRespondingDevices) VALUES ('$accountID','$timestamp','$installedDevices','$trackingDevices','$runningDevices','$idleDevices','$offRoadDevices','$notTrackingDevices','$notRespondingDevices')";
       $qry1 = mysqli_query($tacconnect,$vehicleDetails) or die(mysqli_error($tacconnect));

     //  echo  $vehicleDetails;



      }
       mysqli_close($tacconnect);
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
 $tacconnect = mysqli_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database: " . mysqli_error($tacconnect));
       mysqli_select_db($tacconnect,$tacdb);
       ///////////////////////////////////////////////////////////
       $vehicleDetails="select vehicleID,maxTankVoltage,minTankVoltage,voltageStatus,conversionRate,potentiometer,fuelTracker,acType,minAnalog,maxAnalog,digitalInputOn,digitalInputOff from autoVehicles where accountID='$accounts'";
       $qry1 = mysqli_query($tacconnect,$vehicleDetails) or die(mysqli_error($tacconnect));
        
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

function getLastLocation(){
  global $gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$lastLocaton,$userID,$groupID;

  // $offroadlist=offroadvehicles();

   
$gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($gtsconnect));
mysqli_select_db($gtsconnect,$gtsdb);
   $vehicleGroup=array();
   $vehiclecontact=array(); 
     $vehiclecontact=  vehiclecontact();
     $query="select a.maintNotes,a.statusCodeState,a.lastValidHeading,a.lastValidSpeedKPH,a.lastValidLatitude,a.lastValidLongitude,a.deviceID as deviceID,a.lastGPSTimestamp as lastGPSTimestamp,a.description as description,a.vehicleModel as vehicleModel,a.uniqueID as uniqueID,b.groupID as groupID,a.simPhoneNumber as simnum,a.lastStartTime,a.lastStopTime from Device a,DeviceList b where a.accountID='$accountID' and b.groupID='$groupID' and b.accountID='$accountID' and  a.deviceID=b.deviceID  order by b.groupID,a.lastGPSTimestamp";
    if($userID == "admin" || $userID == "administrator" ||  $userID == "tracking"  || $userID == "monitoring"){
       // $query = "SELECT deviceID,lastGPSTimestamp,description,vehicleModel,simPhoneNumber,uniqueID FROM Device WHERE accountID ='$accountID'";
         $vehicleGroup=getGroupVehicle();
     
        $query="select DISTINCT a.deviceID as deviceID,a.maintNotes,a.statusCodeState,a.lastValidHeading,a.lastValidSpeedKPH,a.lastValidLatitude,a.lastValidLongitude,a.lastGPSTimestamp as lastGPSTimestamp,a.description as description,a.vehicleModel as vehicleModel,a.uniqueID as uniqueID,a.simPhoneNumber as simnum,a.lastStartTime,a.lastStopTime from Device a where a.accountID='$accountID'  order by a.lastGPSTimestamp";
       
      }
     if($userID == "avoidDuplecate" && ($groupID=="selectall" || $groupID=="")){
            $vehicleGroup=getGroupVehicle();
        //  $query = "SELECT a.deviceID as deviceID,a.lastGPSTimestamp as lastGPSTimestamp,a.description as description,a.vehicleModel as vehicleModel,c.contactPhone as simPhoneNumber,a.uniqueID as uniqueID FROM Device a,Driver c WHERE a.accountID ='$accountID' and a.deviceID=c.deviceID";
  //     $query = "SELECT a.deviceID as deviceID,a.lastGPSTimestamp as lastGPSTimestamp,a.description as description,a.vehicleModel as vehicleModel,c.contactPhone as simPhoneNumber,a.uniqueID as uniqueID,a.simPhoneNumber as simnum FROM Device a,Driver c WHERE a.accountID ='$accountID' and c.accountID ='$accountID' and a.deviceID=c.deviceID";
       $query="select DISTINCT a.deviceID as deviceID,a.maintNotes,a.statusCodeState,a.lastValidHeading,a.lastValidSpeedKPH,a.lastValidLatitude,a.lastValidLongitude,a.lastGPSTimestamp as lastGPSTimestamp,a.description as description,a.vehicleModel as vehicleModel,a.uniqueID as uniqueID,a.simPhoneNumber as simnum,a.lastStartTime,a.lastStopTime from Device a where a.accountID='$accountID'  order by a.lastGPSTimestamp"; 

    }
    error_log($query."iiiiiresult");
  $qry_result = mysqli_query($gtsconnect,$query) or die(mysqli_error($gtsconnect));
   $i=0;
    //error_log('dddddddddddddddddddddddddddddddddddd');
   while($row = mysqli_fetch_assoc($qry_result)){
       $lastLocaton[$i][0]=$row['deviceID'];
        $lastLocaton[$i][1]=$row['lastGPSTimestamp'];

          $dis11 =explode("]",$row['description']);
          $vehiclebaselocation=trim($dis11[1],'[');
          if($vehiclebaselocation==""){$vehiclebaselocation=$vehicleGroup[$row['deviceID']];}

     if($userID == "admin" || $userID == "administrator" || $userID=="tracking" || $userID=="monitoring" || $userID == "avoidDuplecate"){
         if($vehicleGroup[$row['deviceID']]=="" || $vehicleGroup[$row['deviceID']]==null){  $vehicleGroup[$row['deviceID']]="ungroup";}
             $lastLocaton[$i][2]="[".$row['deviceID']."][".$vehiclebaselocation."][".$vehicleGroup[strtolower($row['deviceID'])]."]";
        } else{
              if($row['groupID']==""){$row['groupID']="ungroup";} 
              $lastLocaton[$i][2]="[".$row['deviceID']."][".$vehiclebaselocation."][".$row['groupID']."]";
           }

      // $lastLocaton[$i][2]=strtoupper($row['description']);//"[".$row['deviceID']."][".$row['groupID']."][".$row['groupID']."]" ;//strtoupper($row['description']);
       $lastLocaton[$i][3]=$row['vehicleModel'];
       $lastLocaton[$i][4]=$vehiclecontact[$row['deviceID']]==null?"0000000000":$vehiclecontact[$row['deviceID']];//$row['simPhoneNumber'];
       $lastLocaton[$i][5]=$row['uniqueID'];
       $lastLocaton[$i][6]=strtoupper($vehicleGroup[strtolower($row['deviceID'])]);//$row['groupID'];
     $lastLocaton[$i][7]=$row['simnum'];
      $lastLocaton[$i][8]=$row['lastStartTime'];
      $lastLocaton[$i][9]=$row['lastStopTime']; 
     $lastLocaton[$i][10]=round($row['lastValidLatitude'],5);
      $lastLocaton[$i][11]=round($row['lastValidLongitude'],5);

    if( $lastLocaton[$i][10]==0 || $lastLocaton[$i][11]==0 || $lastLocaton[$i][10]=="" || $lastLocaton[$i][11]==""){

           $ulatlng=updateDevicelatlng($row['deviceID']);
           $lastLocaton[$i][10]=explode("**",$ulatlng)[0];
          $lastLocaton[$i][11]=explode("**",$ulatlng)[1];
     }



    $lastLocaton[$i][12]=$row['maintNotes'];
      $lastLocaton[$i][13]=$row['statusCodeState'];
     $lastLocaton[$i][14]=$row['lastValidHeading'];
     $lastLocaton[$i][15]=$row['lastValidSpeedKPH'];
     $groupname= $offroadlist[strtolower($row['deviceID'])];
      $lastLocaton[$i][16]="onlineoroffline";
      if($groupname!=""){
            $lastLocaton[$i][16]="offroad";
       }

          $i++;
   }
   //echo $i.'count';
mysqli_close($gtsconnect);
}

function updateDevicelatlng($vid){
     global $gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$userID,$groupID;
     $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($gtsconnect));
     mysqli_select_db($gtsconnect,$gtsdb);
     $query="select latitude,longitude,timestamp  from EventData  where accountID='$accountID' and  deviceID='$vid' and latitude<>0 and longitude<>0 order by timestamp desc limit 1";
     $qry_result = mysqli_query($gtsconnect,$query) or die(mysqli_error($gtsconnect));
     $ulat=0;
     $ulng=0;
     while($row = mysqli_fetch_assoc($qry_result)){
         $ulat=$row['latitude'];
         $ulng=$row['longitude'];
     }
      $updatequery="update Device set lastValidLatitude=$ulat ,lastValidLongitude=$ulng  where  accountID='$accountID' and deviceID='$vid';";
      mysqli_query($gtsconnect,$updatequery) or die(mysqli_error($gtsconnect));


     mysqli_close($gtsconnect);
     return $ulat."**".$ulng;

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
    error_log('getGroups 1 '.$gtsserver.' '.$gtsusername.' '.$gtspassword.' '.$gtsdb.' '.$accounts.' '.$userID);
    $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($gtsconnect));
    error_log('getGroups 2');
   mysqli_select_db($gtsconnect,$gtsdb);
    error_log('getGroups 3');
  // $vehicleDetails="select vehicleModel,vehicleMake,lastValidLatitude,lastValidLongitude,deviceID from Device where accountID='$accounts'";
    $vehicleDetails='';
     $groupsforadmin="'ahmedabad','amreli','anand','aravalli','banaskantha','bharuch','bhavnagar','botad','chhotaudepur','dahod','devbhumidwarka','gandhinagar','girsomnath','jamnagar','junagadh','kheda','kutch','mahesana','mahisagar','morbi','narmada','navsari','panchmahals','patan','porbandar','rajkot','sabarkantha','surat','surendranagar','tapi','thedangs','vadodara','valsad'";
    if($userID == "admin" || $userID == "administrator" || $userID == "tracking"  || $userID == "monitoring"){
         $vehicleDetails="select groupID from DeviceGroup where accountID='$accounts' and groupID in ($groupsforadmin)";
    }else{
         $vehicleDetails="select DISTINCT groupID from GroupList where accountID='$accounts' and userID='$userID'";
    }
   error_log($vehicleDetails.' Ramana ssssss');
   $qry1 = mysqli_query($gtsconnect,$vehicleDetails) or die(mysqli_error($gtsconnect));
   $rowcount=0;
   error_log('hai  ');
   while($row = mysqli_fetch_assoc($qry1)){
       if($row['groupID']==""){$row['groupID']="ungroup";}
        $result[$rowcount]=$row['groupID'];
        $rowcount++;
       //  echo $row['groupID'];
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
  $qry_result = mysqli_query($gtsconnect,$query) or die(mysqli_error($gtsconnect));
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
    $from = " GLOVISION Support <support@glovision.co>";
    $host ="144.217.228.80";
    $username = "alerts@glovision.co";
    $password = 'Gl0v1$ion12';
    error_log(count($mailperAccount).' num of accounts');
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

function getMails($tacserver,$tacusername,$tacpassword,$tacdb,$accountId,$time){
$tacconnect = mysqli_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database: " . mysqli_error($tacconnect));
mysqli_select_db($tacconnect,$tacdb);

$query1 = "SELECT DISTINCT emailaddress FROM email WHERE accountID ='$accountId' and time='$time'";

error_log($query1);
$qry_result = mysqli_query($tacconnect,$query1) or die(mysqli_error($tacconnect));

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

function ConformOfflineVehicle($tacserver,$tacusername,$tacpassword,$tacdb,$accountId,$vehicleID){
$tacconnect = mysqli_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database: " . mysqli_error($tacconnect));
mysqli_select_db($tacconnect,$tacdb);

$query1 = "SELECT eventsCount,timestamp FROM HealthCycle WHERE accountID ='$accountId' and deviceID='$vehicleID' and eventsCount>0 ORDER BY timestamp DESC limit 1";

//`eventsCount`, `groupID`, `timeStamp`, `accountID`, `deviceID`, `id`

error_log($query1);
$qry_result = mysqli_query($tacconnect,$query1) or die(mysqli_error());

//$mails=Array();
$i=0;
$timestamp;
 while($row = mysqli_fetch_assoc($qry_result))
  {
  
     $count= $row['eventsCount'];
      //echo $vehicleID.'   '.$count.'   '.$row['timestamp'].'<br>';
     $timestamp=$row['timestamp'];
      //error_log($row['emailaddress']);    
        
                                           
  }
//echo $vehicleID.'  '.$count.'   '.$timestamp.'<br>';
mysqli_close($tacconnect);
return $count.'*'.$timestamp;
}

function UpdateEventDataAddress($lat,$long,$accountID,$deviceID,$timestamp,$address,$idleLocStartTime,$idleLocEndTime) {
    global $gtsserver,$gtsdb,$gtsconnect;
    mysqli_select_db($gtsconnect,$gtsdb);
    $addressEscape = mysqli_real_escape_string($address);
    //  echo 'idle start and end :'.$idleLocStartTime.' '.$idleLocEndTime."\n";
    // prevent overwriting geozone Addresses at the same timestamp. Otherwise Arrival,Inzone and Departure events will lose the geozoneIDs.
    if ($idleLocStartTime == 0) {
        $sql = "UPDATE EventData set address='$addressEscape' where accountID='$accountID' and deviceID='$deviceID' and timestamp='$timestamp' and (address is null or address = '')";
    } else {
        $sql = "UPDATE EventData set address='$addressEscape' where accountID='$accountID' and deviceID='$deviceID' and timestamp>='$idleLocStartTime' and timestamp <= '$idleLocEndTime' and (address is null or address = '')";
    }
    error_log($sql);
    mysqli_query($gtsconnect,$sql) or die(mysqli_error($gtsconnect));
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
