<?php 
include 'config.php';
//require_once 'Mail.php';
date_default_timezone_set('Asia/Kolkata');
set_time_limit(1800); 

// decrease the priority
proc_nice(13);

$accountID=$_GET['accountID'];
$group=$_GET['group'];
$fromDate=$_GET['fromDate'];
$userID=$_GET['userID'];
$crone=$_GET['crone'];

$formate=$_GET['formate'];
$toDate=$_GET['toDate'];
$vehicle=$_GET['vehicle'];
$type=$_GET['type'];
$invoiceNO=$_GET['invoiceNO'];
$tempgroup=$group;
//&vehicle=selectall&group=selectall&fromDate=1435861800&userID=administrator&formate=html&toDate=1435993133&days=5

//http://localhost/dashboard/php/VehicleHealthCycleSuperfast.php?accountID=gvkrajasthan&vehicle=selectall&group=selectall&userID=admin&formate=html&type=health&crone=yes

//http://localhost/dashboard/php/VehicleHealthCycleSuperfast.php?accountID=gvk-up-108&vehicle=selectall&group=selectall&userID=admin&formate=html&type=health&crone=yes

//http://localhost/dashboard/php/VehicleHealthCycleSuperfast.php?accountID=gvk-up-102&vehicle=selectall&group=selectall&userID=admin&formate=html&type=health&crone=yes

if($formate=="excel"){
    
   header('Cache-control: no-cache,must revalidate');
 // header('Content-type: application/json');
   header("Access-Control-Allow-Origin: *");
  header("Content-type: application/vnd.ms-excel");
   header("Content-Disposition: attachment;Filename=".$accountID.'_'.$userID.".xls");

 
   }


if($crone=="yes"){
         $toDayDate=strtotime(date('d-M-Y',strtotime("-1 days")).'');
         //$toDayDate=$fromDate;
        // echo $fromDate;

       $d=date('d',$toDayDate);
       $m=date('m',$toDayDate);
        $y=date('Y',$toDayDate);
     //$d='28';
     //$m='06';
    // $y='2016';   
         
         $fromDate=strtotime(date("d-m-Y h:i:s a", mktime(0,0,0,$m,$d,$y)));
         $toDate=strtotime(date("d-m-Y h:i:s a", mktime(23,59,59,$m,$d,$y)));
         //$fromDate=1471285800;
        // $toDate=1471372199; 

}



if($userID!="admin" && $userID !="administrator"){
   $group=$userID;
}
$backupFromDate=$fromDate;
$healthDays=intval(abs($toDate-$fromDate)/86400)+1;


       $toDayDate=strtotime(date('d-M-Y',$toDate).'');
        $fromDayDate=strtotime(date('d-M-Y',$fromDate).'');
       $d1=date('d',$fromDayDate);
       $m1=date('m',$fromDayDate);
       $y1=date('Y',$fromDayDate);
       $fromDatedisplay=strtotime(date("d-m-Y h:i:s a", mktime(0,0,0,$m1,$d1,$y1)));
       $d=date('d',$toDayDate);
       $m=date('m',$toDayDate);
       $y=date('Y',$toDayDate);
            
       $fromDate=strtotime(date("d-m-Y h:i:s a", mktime(0,0,0,$m,$d,$y)));
       $toDate=strtotime(date("d-m-Y h:i:s a", mktime(23,59,59,$m,$d,$y)));
      
       
       $fromDump=$fromDate;
       $toDump=$toDate;
    if($crone=="yes"){  //if request from crone
    
       
       $gtsconnect = mysqli_connect('localhost', 'root','gl0v1s10n' ) or die ("Unable to connect to the database: " . mysqli_error());
   mysqli_select_db($gtsconnect,'gts');
       $HealthCycle=array(array());
      
       for($dateIndx=0;$dateIndx<$healthDays;$dateIndx++){
            
            $vehicleDetails="SELECT deviceID,count(*) as no,MAX(timestamp) timestamp FROM EventData  where accountID='$accountID' and timestamp between $fromDump and $toDump GROUP BY deviceID ";
            if($group!="selectall"){
                $groupVehicles=device_info($accountID,$group);               
                $UserQuery="";
                $rows1=0;
                for($i=0;$i<count($groupVehicles);$i++){
                   if($rows1==0){
                       $UserQuery .="'".$groupVehicles[$i]."'";
                   }else{
                       $UserQuery .=",'".$groupVehicles[$i]."'";
                   }
                   $rows1++;
               }//end loop


            



               //$UserQuery.=",'".$userID."'";
               if($UserQuery==""){
                   $UserQuery="''";
               }
  
               
                $vehicleDetails="SELECT deviceID,count(*) as no,MAX(timestamp) as timestamp FROM EventData  where accountID='$accountID' and deviceID in($UserQuery) and timestamp between $fromDump and $toDump GROUP BY deviceID ";
            }
            error_log($vehicleDetails);
            $qry1 = mysqli_query($gtsconnect,$vehicleDetails) or die(mysqli_error());
            while($row = mysqli_fetch_assoc($qry1))
            {
                echo $row['deviceID'].'  '.$row['no'].'  '.$row['timestamp'].'<br>';
                $HealthCycle[$fromDump][$row['deviceID']]=$row['no'];
            }  //end loop
            $fromDump=$fromDump-86400;
            $toDump=$toDump-86400;
      }//end loop
      mysqli_close($gtsconnect);
     }else{ // if request not from crone

           $tacconnect = mysqli_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database: " . mysqli_error());
 mysqli_select_db($tacconnect,$tacdb);
       $HealthCycle=array(array());
      
            $vehicleDetails="SELECT  accountID,deviceID,timeStamp,eventsCount,groupID FROM HealthCycle  where accountID='$accountID' and timeStamp >= $fromDayDate and timeStamp <=  $toDayDate ";
            
            error_log($vehicleDetails);
            $qry1 = mysqli_query($tacconnect,$vehicleDetails) or die(mysqli_error($tacconnect));
            while($row = mysqli_fetch_assoc($qry1))
            {
                //echo $row['deviceID'].'  '.$row['no'].'  '.$row['timestamp'].'<br>';
                //echo $row['deviceID'].'   '.$row['timeStamp'].'   '.$row['eventsCount'];
                $HealthCycle[$row['timeStamp']][$row['deviceID']]=$row['eventsCount'];
                
            }  //end loop
            
      
        
      mysqli_close($tacconnect);


   }

     //common for cron or not from crone
      //$deviceQuery="SELECT deviceID,uniqueID,imeiNumber FROM Device WHERE accountID ='$accountID'";
      $deviceQuery="select a.deviceID as deviceID,b.uniqueID as uniqueID,b.imeiNumber as imeiNumber,a.groupID as groupID from DeviceList a,Device b where a.accountID='$accountID' and b.accountID='$accountID' and a.deviceID=b.deviceID order by a.groupID ";
      
      if($group!="selectall"){
           $deviceQuery="select a.deviceID as deviceID,b.uniqueID as uniqueID,b.imeiNumber as imeiNumber,a.groupID as groupID from DeviceList a,Device b where a.accountID='$accountID' and a.groupID='$group' and b.accountID='$accountID' and a.deviceID=b.deviceID";
      }
      
          $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error());
mysqli_select_db($gtsconnect,$gtsdb);
        
       $devices = mysqli_query($gtsconnect,$deviceQuery) or die(mysqli_error()); 
       $totalDevices=array();   
       $TypeOfTrackers=array();
       $imies=array(); 
       $groups=array();
       while($row = mysqli_fetch_assoc($devices))
       {
                
                $totalDevices[count($totalDevices)]=$row['deviceID'];
                $TypeOfTrackers[count($TypeOfTrackers)]=$row['uniqueID'];
                $imies[count($imies)]=$row['imeiNumber'];
                $groups[count($groups)]=$row['groupID'];
                
                
       } //end loop
       mysqli_close($gtsconnect);
      //end common for cron or not from crone

        ////save records when request from ccron
       if($crone=="yes"){
          $tacconnect = mysqli_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database: " . mysqli_error());
mysqli_select_db($tacconnect,$tacdb);
          for($deviceIndx=0;$deviceIndx<count($totalDevices);$deviceIndx++){
              $fromDump=$fromDatedisplay;
              $toDump=$toDate;
              for($dateIndx=0;$dateIndx<intval($healthDays);$dateIndx++){
                    $id=$totalDevices[$deviceIndx].$fromDump;
               $query="insert into HealthCycle(accountID,deviceID,timeStamp,eventsCount,groupID,id) values('".$accountID."','".$totalDevices[$deviceIndx]."','".$fromDump."','".$HealthCycle[$fromDump][$totalDevices[$deviceIndx]]."','".$groups[$deviceIndx]."','".$id."') ON DUPLICATE KEY UPDATE eventsCount='".$HealthCycle[$fromDump][$totalDevices[$deviceIndx]]."'";
              
               mysqli_query($tacconnect,$query) or die(mysqli_error()); 
                $fromDump=$fromDump+86400;
              $toDump=$toDump-86400;
              }
          }     
          mysqli_close($tacconnect);
      }else{//if request not from corn

   
 
       $fromDump=$fromDatedisplay;
       $toDump=$toDate;
       if($group=="selectall"){ $group="Administrator";}
        
        $table="<html></style> <script src='https://code.jquery.com/jquery.js'></script><script type='text/javascript' src='//cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.13.3/jquery.tablesorter.min.js'></script>
    <script>3
	
$(document).ready(function () {
    $('#sort').tablesorter();
});</script><body><center><div  style='background: white;border-style: solid;border-color: #2E2E2E;'><h5>Account: $accountID  <br>User :$group<br>Vehicles Health Cycle <br> From: ".date('d-m-Y',$backupFromDate)." ToDate: ".date('d-m-Y',$toDate)."</h5><table id='sort' style='width:100%;font-family:sans-serif; font-size: 8pt; color: #000000;align=left;border-collapse: collapse;' border='1'><thead style='background:#0080FF;color:white'><th>Last Remarks From M/s GLOVISION</th><th>Penalty</th><th>GLOVISION FINAL  REMARKS FOR THE MONTH </th><th>Remarks from GVK , after consideration of GLOVISION Remarks</th><th>Vehicle ID</th><th>Num of Inactive Days</th>";

         $ttable="<table id='sort' style='width:100%;font-family:sans-serif; font-size: 8pt; color: #000000;align=left;border-collapse: collapse;' border='1'><thead style='background:#0080FF;color:white'><th>vehicleID</th><th>Actual Events to be Received Per Day (Individual Vehicle)</th>";
        


         if($type=="invoice"){
             $table="<html></style> <script src='https://code.jquery.com/jquery.js'></script><script type='text/javascript' src='//cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.13.3/jquery.tablesorter.min.js'></script>
    <script>3
	
$(document).ready(function () {
    $('#sort').tablesorter();
});</script><body><center><div  style='background: white;border-style: solid;border-color: #2E2E2E;'><h5>Account: $accountID  <br>Support Data..</h5><table id='sort' style='width:100%;font-family:sans-serif; font-size: 8pt; color: #000000;align=left;border-collapse: collapse;' border='1'><thead style='background:#0080FF;color:white'><th>AccountID</th><th>GroupID</th><th>vehicleID</th><th>Model</th><th>IMEI</th>";
         }
       if($formate=="excel"){
               $colspan=$healthDays+2;
               
               $table="<html></style> <script src='https://code.jquery.com/jquery.js'></script><script type='text/javascript' src='//cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.13.3/jquery.tablesorter.min.js'></script>
    <script>3
	
$(document).ready(function () {
    $('#sort').tablesorter();
});</script><body><center><table id='sort' style='width:100%;font-family:sans-serif; font-size: 8pt; color: #000000;align=left;border-collapse: collapse;' border='1'><tr><th colspan='".$colspan."'><div  style='background: white;border-style: solid;border-color: #2E2E2E;'><h5>Account: $accountID  <br>User :$group<br>Vehicles Health Cycle</h5></th></tr><thead style='background:#0080FF;color:white'><th>Last Remarks From M/s GLOVISION</th><th>Penalty</th><th>GLOVISION FINAL  REMARKS FOR THE MONTH </th><th>Remarks from GVK , after consideration of GLOVISION Remarks</th><th>Vehicle ID</th><th>Num of Inactive Days</th>";
              if($type=="invoice"){
                 $table="<html></style> <script src='https://code.jquery.com/jquery.js'></script><script type='text/javascript' src='//cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.13.3/jquery.tablesorter.min.js'></script>
    <script>3
	
$(document).ready(function () {
    $('#sort').tablesorter();
});</script><body><center><table id='sort' style='width:100%;font-family:sans-serif; font-size: 8pt; color: #000000;align=left;border-collapse: collapse;' border='1'><tr><th colspan='".$colspan."'><div  style='background: white;border-style: solid;border-color: #2E2E2E;'><h5>Account: $accountID  <br>User :$group<br>Vehicles Health Cycle</h5></th></tr><thead style='background:#0080FF;color:white'><th>AccountID</th><th>GroupID</th><th>vehicleID</th><th>Model</th><th>IMEI</th>";
             }

           }
           
        if($type=="invoice"){
             
             $table=$table.'<th>Active Days</th><th>Amount(Rs)</th></thead><tbody>';
         }else{
              for($dateIndx=0;$dateIndx<$healthDays;$dateIndx++){
               
              $table=$table."<th>".date('d-m-Y',$fromDump)."</th>";
              $ttable=$ttable."<th>2000</th>";
              $fromDump=$fromDump+86400;
              $toDump=$toDump-86400;
             }
             $table=$table.'</thead><tbody>';
             $ttable=$ttable.'<th>Active Days</th><th>In-Active Days</th></thead><tbody>';
        }
        
       $working='';
       $notWorking='';
       $gaps='';
 
       $workingpercent='';
       $notWorkingpercent='';
       $gapspercent='';


       $wtdtrackers='';
       $tfttrackers='';
       $totalRentforAllVehicles=0;
       $serviceTFTrental=0;
       $serviceWTDrental=0;
       $TFTVehicleCount=0;
       $WTDVehicleCount=0;
      
      /////////////////////////////////////////////////////////////////
       $totalDevices1=array();
         //get devices from seleced month 
         $tacconnect = mysqli_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database: " . mysqli_error());
mysqli_select_db($tacconnect,$tacdb);
         $vehicleDetails="SELECT DISTINCT deviceID FROM HealthCycle  where accountID='$accountID' and timeStamp >='$fromDayDate' and timeStamp <='$toDayDate' ";
        if($tempgroup!="selectall"){
               $vehicleDetails="SELECT DISTINCT deviceID FROM HealthCycle  where accountID='$accountID' and timeStamp >='$fromDayDate' and timeStamp <='$toDayDate' and groupID='$group'";
         }
          
        error_log($vehicleDetails);
        $qry1 = mysqli_query($tacconnect,$vehicleDetails) or die(mysqli_error($tacconnect));
            while($row = mysqli_fetch_assoc($qry1))
            {

                $totalDevices1[count($totalDevices1)]=$row['deviceID'];
                
            }  //end loop
         mysqli_close($tacconnect);       


   ////////////////////////////////////////////////////////////////////////////////////////
        $offlineVehiclesCount=array();
       $totalVehiclesCountperday=array();
       offlinevehiclesDaywise($group,$fromDayDate,$toDayDate,$tempgroup);
      $daywiseeventcountforallvehicles=array(); 
       for($deviceIndx=0;$deviceIndx<count($totalDevices1);$deviceIndx++){
           $fromDump=$fromDatedisplay;
           $toDump=$toDate;
           //$table=$table.'<tr><th>'.strtoupper($totalDevices[$deviceIndx]).'</th>';
           $streanth=0;
           $toweak=0;
           $row='';
           $percentrow='';
           $NoOfDaysActive=0;
           $NoOfDaysInactive=0;
           $activeStatusStart='no';
           for($dateIndx=0;$dateIndx<intval($healthDays);$dateIndx++){
               //insertQuery
                $daywiseeventcountforallvehicles[$fromDump]= $daywiseeventcountforallvehicles[$fromDump]+$HealthCycle[$fromDump][$totalDevices1[$deviceIndx]]; 
              $percent=($HealthCycle[$fromDump][$totalDevices1[$deviceIndx]]/2000*100);      
                
              if(intval($HealthCycle[$fromDump][$totalDevices1[$deviceIndx]])>0 && intval($HealthCycle[$fromDump][$totalDevices1[$deviceIndx]])<=145){
                     //$row=$row.'<td style="background:#BDBDBD;color:black">'.$HealthCycle[$fromDump][$totalDevices1[$deviceIndx]].'</td>';                
                     $row=$row.'<td style="color:black"></td>';                
                   
                  $percentrow=$percentrow.'<td style="background:#BDBDBD;color:black">'.$percent.'%</td>';           
                    $activeStatusStart='yes';
                  // if($type !="invoice"){
                         $NoOfDaysActive++;
                    //  }
                   $streanth++;
              }elseif(intval($HealthCycle[$fromDump][$totalDevices1[$deviceIndx]])>145){

                   
                       // $row=$row.'<td style="background:green;color:white">'.$HealthCycle[$fromDump][$totalDevices[$deviceIndx]].'</td>';  
			//$row=$row.'<td style="color:black">'.$HealthCycle[$fromDump][$totalDevices1[$deviceIndx]].'</td>';     
                        $row=$row.'<td style="color:black"></td>';     
                        $percentrow=$percentrow.'<td style="color:black">'.$percent.'%</td>'; 
                      $activeStatusStart='yes';
                    // if($type !="invoice"){
                         $NoOfDaysActive++;
                     // }
                    $streanth++;
              }else{

                   //$row=$row.'<td style="background:red;color:black">0</td>';
                   $row=$row.'<td style="background:red;color:white">0</td>';
                   $percentrow=$percentrow.'<td style="background:black;color:white">0</td>';
                    $toweak++;
              }
            //  if($type =="invoice"){
              //   if($activeStatusStart=='yes'){$NoOfDaysActive++;}
             // }
              $fromDump=$fromDump+86400;
              $toDump=$toDump-86400;
          }//end looop

          $rent=0;
          $model='';
          $group='';
          $imei='';
           for($deviceIndx1=0;$deviceIndx1<count($totalDevices);$deviceIndx1++){
               if($totalDevices[$deviceIndx1]==$totalDevices1[$deviceIndx]){
                   $model=$TypeOfTrackers[$deviceIndx1];
                   $group=$groups[$deviceIndx1];
                   $imei=$imies[$deviceIndx1];
                   break;
                }
           }               
           /*  if($model==''){//if vehicle removed from live server then go to backup server 
                  $result=getModelImei($totalDevices1[$deviceIndx],$accountID);
                  $array=explode(',', $result);
                  $model=$array[0];
                  $group=$array[1];
                  $imei=$array[2];
             }*/ 
           if (strpos($model,"TFT") !== false || strpos($model,"tft") !== false ) {
                $rent=round((325/$healthDays)*$NoOfDaysActive,2);//without tax with tax 370 
                $serviceTFTrental=$serviceTFTrental+$rent;
                // echo "hiii-1 : ".intval($healthDays-$NoOfDaysActive)."-".$healthDays."-".$NoOfDaysActive;
                $tfttrackers=$tfttrackers.'<tr></td><td>'.$accountID.'</td><td>'.$group.'</td><td>'.strtoupper($totalDevices1[$deviceIndx]).'</td><td>'.$model.'</td><td>'.$imei.'</td><td>'.$NoOfDaysActive.'</td><td>'.intval($healthDays-$NoOfDaysActive).'</td><td>'.$rent.'</td></tr>';

                if($NoOfDaysActive>0){
                     $TFTVehicleCount++;
                }
       
           }else{
                $rent=round((43.86/$healthDays)*$NoOfDaysActive,2);
                $serviceWTDrental=$serviceWTDrental+$rent;//without tax with tax 50
                // echo "hiii-2 : ".intval($healthDays-$NoOfDaysActive)."-".$healthDays."-".$NoOfDaysActive;
                $wtdtrackers=$wtdtrackers.'<tr></td><td>'.$accountID.'</td><td>'.$group.'</td><td>'.strtoupper($totalDevices1[$deviceIndx]).'</td><td>'.$model.'</td><td>'.$imei.'</td><td>'.$NoOfDaysActive.'</td><td>'.intval($healthDays-$NoOfDaysActive).'</td><td>'.$rent.'</td></tr>';
                 if($NoOfDaysActive>0){
                     $WTDVehicleCount++;
                }
           }
          $totalRentforAllVehicles +=$rent;
          if($type !="invoice"){
                    
              if($streanth==intval($healthDays)){
                  //$working=$working.'<tr><th></th><th></th><th></th><th>'.strtoupper($totalDevices1[$deviceIndx]).'</th><th style="color:green">Working</th>'.$row.'<td>'.$NoOfDaysActive.'</td><td>'.intval($healthDays-$NoOfDaysActive).'</td></tr>';
                  $working=$working.'<tr><th></th><th></th><th></th><th></th><th>'.strtoupper($totalDevices1[$deviceIndx]).'</th><th>'.intval($healthDays-$NoOfDaysActive).$row.'</td></tr>';
              
                $workingpercent=$workingpercent.'<tr><th></th><th></th><th></th><th></th><th>'.strtoupper($totalDevices1[$deviceIndx]).'</th><th style="color:green">Working</th>'.$percentrow.'<td>'.$NoOfDaysActive.'</td></tr>';

 
             }else if($toweak==intval($healthDays)){
                  //$notWorking=$notWorking.'<tr><th></th><th></th><th></th><th>'.strtoupper($totalDevices1[$deviceIndx]).'</th><th style="color:red">Not Working</th>'.$row.'<td>'.$NoOfDaysActive.'</td><td>'.intval($healthDays-$NoOfDaysActive).'</td></tr>';
                  $notWorking=$notWorking.'<tr><th></th><th></th><th></th><th></th><th>'.strtoupper($totalDevices1[$deviceIndx]).'</th><th>'.intval($healthDays-$NoOfDaysActive).'</th>'.$row.'</td></tr>';
                 $notWorkingpercent=$notWorkingpercent.'<tr><th>'.strtoupper($totalDevices1[$deviceIndx]).'</th><th style="color:red">Not Working</th>'.$percentrow.'<td>'.$NoOfDaysActive.'</td></tr>';
              }else{
                   $gaps=$gaps.'<tr><th></th><th></th><th></th><th></th><th>'.strtoupper($totalDevices1[$deviceIndx]).'</th><th>'.intval($healthDays-$NoOfDaysActive).'</th>'.$row.'</td></tr>';
                    $gapspercent=$gapspercent.'<tr><th>'.strtoupper($totalDevices1[$deviceIndx]).'</th><th style="color:#04B404">Gaps</th>'.$percentrow.'<td>'.$NoOfDaysActive.'</td></tr>';

              } 
         }//end if
          
       }//end device loop
       $serviceTax=round($totalRentforAllVehicles*0.14,2);//tax
       $trackerServiceCharges=$totalRentforAllVehicles;
       if($type=="invoice"){
              if($serviceTFTrental>0)
                  $tfttrackers=$tfttrackers.'<tr><th colspan="6"><center>SubTotal</center></th><th>'.$serviceTFTrental.'</th></tr>';
              if($serviceWTDrental>0)
                  $wtdtrackers=$wtdtrackers.'<tr><th colspan="6"><center>SubTotal</center></th><th>'.$serviceWTDrental.'</th></tr>';
             $table=$table.$tfttrackers.$wtdtrackers.'<tr><th colspan="6" align="right"><center> Tracking Service Charges for '.date('M-Y',$fromDate).'</center>
</th><th>'.$trackerServiceCharges.'</th></tr><tr><th colspan="6" align="right"><center>Service Tax @ 14%</center></th><th>'.$serviceTax.'</th></tr><tr><th colspan="6" align="right"><center>Total Payable Amount</center>
</th><th>'.($totalRentforAllVehicles+$serviceTax).'</th></tr></tbody></table></div></center></body></html>'; 
        echo generateInvoice($backupFromDate,$toDate,$userID,$serviceTax,$trackerServiceCharges,$totalRentforAllVehicles,$accountID,count($totalDevices1),$serviceTFTrental,$serviceWTDrental,$TFTVehicleCount,$WTDVehicleCount,$invoiceNO);
       echo $table;
       }else{

            //adding offline count
             $totalEventsforalldays=0;
             $totalactualforalldays=0;
             $xpercent="<tr><th colspan='2'>Offline Vehicles Day wise %</th>";
             $totalevents="<tr><th colspan='2'> Events Received Per Day </th>";
            $actualevents="<tr><th colspan='2'> Actual Events to be Received Per Day </th>";
              $totaleventpercent="<tr><th colspan='2'> Percentage of Events for a day (% wise) </th>";
              $x=$x."<tr><th colspan='2'>Offline Vehicles Day wise </th>";
               $fromDump=$fromDatedisplay;
              for($dateIndx1=0;$dateIndx1<intval($healthDays);$dateIndx1++){
               $totalEventsforalldays= $totalEventsforalldays+$daywiseeventcountforallvehicles[$fromDump];
                $totalevents=$totalevents."<td>". $daywiseeventcountforallvehicles[$fromDump]."</td>";
                 $actualevents=$actualevents."<td>".(2000*count($totalDevices1))."</td>";
                 $totalactualforalldays=$totalactualforalldays+2000*count($totalDevices1);
                $totaleventpercent=$totaleventpercent."<td>".intval(($daywiseeventcountforallvehicles[$fromDump]/(2000*count($totalDevices1)))*100)."</td>";
                 $x=$x.'<td>'.$offlineVehiclesCount[$fromDump].'/'.$totalVehiclesCountperday[$fromDump].'</td>';
                 $xpercent=$xpercent.'<td>'.intval($offlineVehiclesCount[$fromDump]/$totalVehiclesCountperday[$fromDump]*100).'%</td>'; 
                $fromDump=$fromDump+86400;
              $toDump=$toDump-86400;
              } 
           $totaleventpercent=$totaleventpercent."<td>".intval(($totalEventsforalldays/$totalactualforalldays)*100)."</td></tr>"; 
          $actualevents=$actualevents."<td>".($totalactualforalldays)."</td></tr>";
          $totalevents=$totalevents."<td>".$totalEventsforalldays."</td></tr>";
              $x=$x.'</tr>';
            $xpercemt=$xpercent.'</tr>';
        //  $ttable=$table;
         //echo $table=$table.$x.$notWorking.$working.$gaps.$totalevents.$actualevents.$totaleventpercent.'</tbody></table></div></center><br><br>'.$ttable.$xpercent.$notWorkingpercent.$workingpercent.$gapspercent.'</tbody></table></div></center></body></html>';;
         echo $table=$table.$notWorking.$working.$gaps.'</tbody></table></div></center></body></html>';;
         


     //    echo $ttable.$xpercent.$notWorkingpercent.$workingpercent.$gapspercent.'</tbody></table>';

       }

   }//end crone condition

function getModelImei($vehicleID,$accountID){

   
           $deviceQuery="select a.deviceID as deviceID,b.uniqueID as uniqueID,b.imeiNumber as imeiNumber,a.groupID as groupID from DeviceList a,Device b where a.accountID='$accountID'  and b.accountID='$accountID' and a.deviceID='$vehicleID' and b.deviceID='$vehicleID' ";
      
      
          $connect = mysqli_connect("gj.glovision.co","gts", "opengts") or die ("Unable to connect to the database: " . mysqli_error());
mysqli_select_db($connect,"gts");
        
       $devices = mysqli_query($deviceQuery) or die(mysqli_error()); 
       $result='';
       while($row = mysqli_fetch_assoc($devices))
       {
                
              
                $result=$row['uniqueID'].','.$row['groupID'].','.$row['imeiNumber'];
                
       } //end loop
       mysqli_close(connect);

    return $result;

}
function offlinevehiclesDaywise($group,$fromDayDate,$toDayDate,$tempgroup){
 global $tacserver, $tacusername, $tacpassword,$tacdb,$accountID,$offlineVehiclesCount,$totalVehiclesCountperday;
 //     echo $fromDayDate;
    $tacconnect = mysqli_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database: " . mysqli_error());
mysqli_select_db($tacconnect,$tacdb);
         $vehicleDetails="SELECT count(*) as cn,timeStamp FROM HealthCycle  where accountID='$accountID' and timeStamp >='$fromDayDate' and timeStamp <='$toDayDate' and eventsCount <= 0 group by timeStamp";
        if($tempgroup!="selectall"){
               $vehicleDetails="SELECT count(*) as cn ,timeStamp FROM HealthCycle  where accountID='$accountID' and timeStamp >='$fromDayDate' and timeStamp <='$toDayDate' and groupID='$group' and eventsCount <= 0 group by timeStamp ";
         }
          
        error_log($vehicleDetails);
        $qry1 = mysqli_query($tacconnect,$vehicleDetails) or die(mysqli_error($tacconnect));
            while($row = mysqli_fetch_assoc($qry1))
            {

                $offlineVehiclesCount[$row['timeStamp']]=$row['cn'];
               // echo $row['cn'].'   ';
            }  //end loop

       $vehicleDetails="SELECT count(*) as cn,timeStamp FROM HealthCycle  where accountID='$accountID' and timeStamp >='$fromDayDate' and timeStamp <='$toDayDate' group by timeStamp";
        if($tempgroup!="selectall"){
               $vehicleDetails="SELECT count(*) as cn,timeStamp FROM HealthCycle  where accountID='$accountID' and timeStamp >='$fromDayDate' and timeStamp <='$toDayDate' and groupID='$group'  group by timeStamp ";
         }
          
        error_log($vehicleDetails);
        $qry1 = mysqli_query($tacconnect,$vehicleDetails) or die(mysqli_error($tacconnect));

            while($row = mysqli_fetch_assoc($qry1))
            {

                $totalVehiclesCountperday[$row['timeStamp']]=$row['cn'];
               // echo $row['cn'].'   ';
            }  //end loop

    
     
         mysqli_close($tacconnect); 


}
function device_info($accounts,$group){
   
  // $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error());
  // mysqli_select_db($gtsdb, $gtsconnect);
  // $vehicleDetails="select vehicleModel,vehicleMake,lastValidLatitude,lastValidLongitude,deviceID from Device where accountID='$accounts'";
   $vehicleDetails="select deviceID from DeviceList where accountID='$accounts' and groupID='$group'";

   error_log($vehicleDetails);
   $qry1 = mysqli_query($gtsconnect,$vehicleDetails) or die(mysqli_error($gtsconnect));
   $result=array();
   
   while($row = mysqli_fetch_assoc($qry1)){
      
        //$result[$rowcount][0]=$row['deviceID'];
       // $result[$rowcount++][1]=$row['groupID'];
        $result[count($result)]=$row['deviceID'];
        
   }
   //mysqli_close($gtsconnect);
   return $result;
}

 function generateInvoice($fromDate,$toDate,$userID,$serviceTax,$trackerServiceCharges,$totalRentforAllVehicles,$accountID,$totalVehicles,$serviceTFTrental,$serviceWTDrental,$TFTVehicleCount,$WTDVehicleCount,$invoiceNO){
       $date=date('d-M-Y');
       $fDate=date('d-M-Y',$fromDate);
       $tDate=date('d-M-Y',$toDate);
//       $invoiceNo="RWSS/".strtoupper(substr($userID,0,3)).'/'.date('dhi/M');
       
       //$invoiceNo="GVK/".strtoupper(str_replace('-','',substr($accountID,3,7))).'/'.date('M');
      // echo date('y');
   $invoiceNoo="GLO/".$invoiceNO.'/'.date('y').'-'.(intval(date('y'))+1);
  //$invoiceNoo="GLO/".$invoiceNO.'/'.date('y').'-'.date('y');
      
      return ' <!DOCTYPE html>
<html lang="en">
  <br><br><br>
  <head>
    <meta charset="utf-8">
    
    <link rel="stylesheet" href="../css/style.css" media="all" />
  </head>
  <body>
    <div id="page">
    <header class="clearfix">
      <div id="logo">
        <img src="../images/glovision.png">
      </div>
      <div id="company">
        <a href="mailto:Info@glovision.co"> <h2 class="name">Glovision Techno Services Pvt Ltd</h2></a>
        <div>6-3-1186/A/5, B S Maktha, Begumpet,</div>
        <div>Hyderabad -500 016, </div>
        <div>Phone : 040-65551213,<a href="mailto:Info@glovision.co">Info@glovision.co</a></div>
      </div>
     
    </header>
    <main>
      
       
        <div id="invoice">
          <h4>PROFORMA INVOICE</h4>
         
        </div>
       <div><h4>REF :'.ucwords($accountID).'</h4></div>
     <table border="1" cellspacing="0" cellpadding="0">
         <tbody>
          <tr>
            <th rowspan="4"><h5>Aathena Solution <br>102,Siri Enclave <br>Besides Axis Bank ,Srinagar colony<br> Hyderabad 500073 <br>Phone: 91606066688 <br>Email: info@aathenasolutions.com
          		                
            </th>
            <th >Invoice No</th>
            <td > '.$invoiceNoo.'</td>
            
          </tr>
        
           <tr>
            <th >Date</th>
            <td >'.$date.'</td>
           
          </tr>
          <tr>
            <th>Bill Period From</th>
            
            <td>'.$fDate.' </td>
          </tr>
          <tr>
            <th >Bill Period To</th>
            <td>'.$tDate.'</td>
           
          </tr>
          <tr>
          </tr>
        </tbody>
    
      </table>
      
      <table border="1" cellspacing="1" cellpadding="1">
         <tbody>
          <tr>
            
            <th >Cin</th>
            <td >U72200TG2013PTC088399</td>
              <th >Pan No</th>
            <td >AAFCJ2351J</td>
          </tr>
        
           <tr>
            <th >Service Tax</th>
            <td >AAFCJ2351JSD001</td>
              <th >Tin No</th>
            <td > 36378123971</td>
          </tr>
          
         
        </tbody>
    
      </table>
      <table border="1" cellspacing="1" cellpadding="1">
         <tbody>
           <thead>
              <th >S.No</th>
              <th >Description</th>
              <th >Quantity</th>
              <th >Price</th>
              <th >Amount</th>
           </thead>
        
           <tr>
              <td >1</td>
              <td >Tracking Services for WTD Devices (Tracking devices only) </td>
              <td >'.$WTDVehicleCount.'</td>
              <td >43.86 </td>
              <td >'.$serviceWTDrental.'</td>
          </tr>
          <tr>
              <td >1</td>
              <td >Tracking Services for TFT Devices (Tracking devices only) </td>
              <td >'.$TFTVehicleCount.'</td>
              <td >325.00</td>
              <td >'.$serviceTFTrental.'</td>
          </tr>
          
           <tr >
              <td rowspan="3" colspan="2" >Amount in words :<br><b>
               Rupees '.ucwords(convert_number_to_words(intval($totalRentforAllVehicles+$serviceTax))).' only</b></td>
              <td colspan="2">Total</td>
              
              <td >'.($trackerServiceCharges).'</td>
          </tr>
           <tr >
              
              <td colspan="2">SeviceTax@14%</td>
              
              <td >'.($serviceTax).'</td>
          </tr>
          <tr>
              
              <td colspan="2">Grand Total</td>
              
              <td>'.($totalRentforAllVehicles+$serviceTax).'</td>
          </tr>
            <tr></tr>        
         
        </tbody>
    
      </table>     
   
      <div>Payment should be by crossed account payee Cheque draft in favour-<b> "M/s Glovision Techno Services Private Limited"</b></div>
       
      <div id="bank">
        <div><h4 class="name">Bank Name: HDFC BANK</h4>	 </div>					
           <div>Account:   50200000468592</div>						
          <div> Branch Address : Vidya Nagar, Hyderabad	</div>					
          <div> IFSC Code : HDFC0001628	</div>					
       </div>
      <div id="authorised">
        <div><h4 class="name">for Glovision Techno Serices Pvt Ltd</h4>	</div>					
           <div>Authorized Signatory</div>						
       </div>
       
      <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
      <footer>
              This invoice was generated by computer and is valid without the signature and seal.
       </footer>
    </main>
    </div></body></html>
    ';
 
  }
function convert_number_to_words($number) {
   
    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'fourty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );
   
    if (!is_numeric($number)) {
        return false;
    }
   
    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }
   
    $string = $fraction = null;
   
    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }
   
    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }
   
    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_explode((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }
   
    return $string;
}
?>
