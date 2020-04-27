<?php
    date_default_timezone_set('Asia/Kolkata');
/*
    ini_set('display_errors', 1);
    ini_set('log_errors', 1);
    ini_set('error_log', dirname(__FILE__) . '/usr/local/apache/logs/error_log.txt');
    error_reporting(E_ALL);
    error_reporting(0);
*/
    set_time_limit(0);
    include 'config.php';
    $accountID=$_GET['accountID'];
    $reportType=$_GET['reportType'];
    $from= $_GET['fromDate'];
    $to = $_GET['toDate'];
    $idleTime = $_GET['idletime'];
    $fromTimee= $_GET['fromTime'];
    $toTimee = $_GET['toTime'];

    $reportFormate= $_GET['reportFormate'];
    $group= $_GET['group'];
    $deviceID=$_GET['vehicleID'];
    $croneRequest="no";
   
   if (isset($_GET['fromDate'])) {
         error_log($from.'from orig');
         $dt = new DateTime($from); 
         $fromdate1= $dt->format('Y-m-d ' ."00:00:00");
         $fromDate=strtotime($fromdate1);
         error_log($fromdate1.'fromdate');
         error_log(strtotime($fromdate1).'fromdateepoch');
         $dt1 = new DateTime($to);
         $todate1= $dt1->format('Y-m-d ' ."23:59:59");
         $toDate=strtotime($todate1);

         error_log($todate1.'to date');
         error_log(strtotime($todate1).'todateepoch');   
    }
    $image="images/".$accountID.".jpg";
    if(strpos($accountID,"gvk")>-1 || strpos($accountID,"gog")>-1){
         $image="images/gvk.jpg";
    }
   $setup=parse_ini_file("setup.ini",true);// loading setup file for from time and to time
        $fromTimee=$setup[$accountID.".fromtime"].':00:00';
          $toTimee=$setup[$accountID.".totime"].':59:59';
 
    
 
    $deviceArrya=array(array());

     if($reportFormate=="word"){

   error_log($reportFormate.' report formate');
   header('Cache-control: no-cache,must revalidate');

   header("Access-Control-Allow-Origin: *");
   header("Content-type: application/vnd.ms-word");
   header("Content-Disposition: attachment;Filename=".$group.".doc");
  
   }else if($reportFormate=="excel"){
    
   header('Cache-control: no-cache,must revalidate');
  header('Content-type: application/json');
   header("Access-Control-Allow-Origin: *");
  header("Content-type: application/vnd.ms-excel");
   header("Content-Disposition: attachment;Filename=".$group.".xls");

 
   }



    if($deviceID=="selectall"){
        $deviceArrya=device_info($gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$group,$deviceID);
    }else{
        $deviceArrya=device_info($gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$group,$deviceID);
    }


   $daywiseDistance=array();

    $table1="<!DOCTYPE HTML>
    <html>
    <head>
    <meta name='author' content='Glovision Techno Services'/>
    <meta http-equiv='content-type' content='text/html; charset=UTF-8'/>
    <link rel='shortcut icon' href='images/glovision.ico'/>
    <title>Glovision techno services</title>
    <link rel='stylesheet' href='style.css' />
    <script src='jquery/jquery-1.11.1.min.js'></script>
    <script src='http://code.jquery.com/ui/1.10.3/jquery-ui.js'></script>
    <link rel='stylesheet' type='text/css' href='jquery/jquery.datetimepicker.css'/>
    <script src='jquery/jquery.datetimepicker.js' type='text/javascript'></script>
    <script src='datePicker1.js' type='text/javascript'></script>
    <script src='distanceIdle.js' type='text/javascript'></script>
    </head>
    <body>
    <center>
    ";
   
    if($deviceID != "selectall"){
        $dis11 = explode("]",$deviceArrya[0][10]);
            $mobileNumber=$deviceArrya[0][9];
            $table1= $table1."<div id='foot'>                  
             <center> Tracking  Report for Single Vehicle</center>
             </div>Group :".strtoupper($group)."<br>Asset Name: ".$deviceArrya[0][4]." <br> Mobile No:".$mobileNumber;
            
        
    }else{
        
            $table1= $table1."<div id='foot'>                  
             <center>Day Wise Vehicles Tracking  Report </center>
             </div>Group :".strtoupper($group)."<br>";
   }
    $table1=  $table1.'<br> From :'.date('d-M-Y',$fromDate).' To: '.date('d-M-Y',$toDate).'<br> From :'.$fromTimee.' am  To:'.$toTimee.' pm';
    $table="<style type='text/css'>
.tablecss {
        
        width:100%;
        border-collapse: collapse;
        color:black;
        background:white;
        font-family:Arial,Verdana,sans-serif;
        font-size:13;
}
</style>";
    $summarydata='';
       $summarydata1=$table1."<br><table border='1px' class='tablecss'><caption>Distance Summary Report</caption><thead bgcolor='black' style='color:white'><th>Index</th> <th>District</th><th>Base Location</th><th>Asset</th><th>Crew Number</th><th>From Date</th><th>To Date</th>";
   
   if($deviceID != "selectall"){
                    $table= $table."<table border='1px' class='tablecss'><caption>Detailed Distance Report</caption><thead bgcolor='black' style='color:white'><th>Index</th><th>Date</th><th>Idle Location For More<br> Than $idleTime mins</th><th>Total Idle Time</th><th>Start Time Of <br> The Day</th><th>End Time Of <br> The Day</th><th>Distance</th><th>Travel Route</th></thead><tbody>";
    }else{
 
         $table= $table."</center><table border='1px' class='tablecss'><caption>Detailed Distance Report</caption><thead bgcolor='black' style='color:white'><th>Index</th><th>Date</th><th>Asset</th><th>Idle Location For More<br> Than $idleTime mins</th><th>Total Idle Time</th><th>Start Time Of <br> The Day</th><th>End Time Of <br> The Day</th><th>Distance</th><th>Travel Route</th></thead><tbody>";
    }
    $summaryIndx=0;
    for($devindx=0;$devindx<count($deviceArrya);$devindx++) {
       $daywiseDistance=array();
       $mobileNumber=$deviceArrya[$devindx][7];
            $mobileNumber=$deviceArrya[$devindx][9];
            
        
        
        $tacconnect = mysqli_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database: " . mysqli_error());
mysqli_select_db($tacconnect, $tacdb);
        $sql = "select accountID, deviceID, groupID, baseLocation, crewNumber, idleLocations, idleTime, startTime, startLocation, travelledDistance, timeStamp, endTime, endLocation, route,District,uniqueID from distanceIdleReport where accountID='$accountID' and deviceID='".$deviceArrya[$devindx][4]."' and timestamp BETWEEN $fromDate AND $toDate";
        error_log($sql);
        $qry_result = mysqli_query($tacconnect,$sql) or die(mysqli_error());
        
        $num_rows = mysqli_num_rows($qry_result);
        
        $startDateforSummary='';
        $endDateforSummary='';
        $totalDistanceTravelled=0;
        $index=0;
        while($row = mysqli_fetch_assoc($qry_result)) {
         
           
           $d=date('d-m-Y',$row['timeStamp']);
           $daywiseDistance[$d]=$row['travelledDistance'];
           if($index==0){
               $startDateforSummary=$d;
           }
           $asset=$row['deviceID']."<br>".$row['baseLocation'];
           $startlocationdatetime=$row['startLocation']."<br>".$row['startTime'];
           $endlocationdatetime=$row['endLocation']."<br>".$row['endTime'];
                 $day=date('D',$row['timeStamp']);
                $color="white";
                $fcolor='black';
                if($day=="Sun"){
                   $color="#F6CEE3";
                   $fcolor='black';
                }
      
                 if($deviceID == "selectall"){
                     $table= $table."<tr  style='color: $fcolor; background: $color;'><td>".(++$index)."</td><td>".$d."</td><td>".$asset."</td><td>".$row['idleLocations']."</td><td>".$row['idleTime']."</td><td>".$startlocationdatetime."</td><td>".$endlocationdatetime."</td><td>".$row['travelledDistance']." Kms</td><td><a href=".$row['route']." target='_blank'>Route</a></td></tr>";
                 }else{
                    $table= $table."<tr style='color: $fcolor; background: $color;'><td>".(++$index)."</td><td>".$d."</td><td>".$row['idleLocations']."</td><td>".$row['idleTime']."</td><td>".$startlocationdatetime."</td><td>".$endlocationdatetime."</td><td>".$row['travelledDistance']." Kms</td><td><a href=".$row['route']." target='_blank'>Route</a></td></tr>";
                       
                }

           $totalDistanceTravelled=$totalDistanceTravelled+ $row['travelledDistance'];
           
            
          $endDateforSummary=$d;
            
        } //end of while
        
         $currentlatlong=round($deviceArrya[$devindx][2],5).'/'.round($deviceArrya[$devindx][3],5);
      $latvalidtimestamp=$deviceArrya[$devindx][6];
      $diffhours=abs(time()-$latvalidtimestamp)/3600;// hours  
      $vehicleStatus="<font style='color:green'>Online</font>";
       if($deviceArrya[$devindx][2]==0){
                 $currentlatlong=$elatlong;
      }
        if($diffhours>12){
         $vehicleStatus="<font style='color:red'>Off-road</font>";
        }
       if($deviceArrya[$devindx][6]=='' || $deviceArrya[$devindx][6]=='0' || $deviceArrya[$devindx][6]==0){
            $vehicleStatus="<font style='color:red'>Not Responding</font>";

       }
        
       $sumdis='';
           for($dindx=$fromDate;$dindx<$toDate;){

                     $sumdis=$sumdis."<td>".$daywiseDistance[date('d-m-Y',$dindx)]."</td>";
                     $dindx=$dindx+86400;

              }


         $tripUrl="";
         if($index>0 && $deviceID != "selectall"){
           
            $dis11 = explode("]",$deviceArrya[$devindx][10]);
               
               $table=$table."<tr bgcolor='black' style='color:white'><th colspan='6'>Total Travelled Distance</th><th>".round($totalDistanceTravelled,2)." Kms</th><th><a href=".$tripUrl." target='_blank'>Route</a></th></tr>";
               $summarydata=$summarydata."<tr><td>".(++$summaryIndx)."</td><td>".trim($dis11[2],'[')."</td><td>".trim($dis11[1],'[')."</td><td>".$deviceArrya[$devindx][4]."</td><td>".$mobileNumber."</td><td>".$startDateforSummary."</td><td>".$endDateforSummary."</td>".$sumdis."<td>".round($totalDistanceTravelled,2)." Km</td><td>".$currentlatlong."</td><td>Glovision</td><td>".$vehicleStatus."</td></tr>";
           
        }
        if($index>0 && $deviceID == "selectall"){
              $dis11 = explode("]",$deviceArrya[$devindx][10]);
                  
                  $table=$table."<tr bgcolor='black' style='color:white'><th colspan='7'>Total Travelled Distance</th><th>".round($totalDistanceTravelled,2)." Kms</th><th><a href=".$tripUrl." target='_blank'>Route</a></th></tr>";
                   $summarydata=$summarydata."<tr><td>".(++$summaryIndx)."</td><td>".trim($dis11[2],'[')."</td><td>".trim($dis11[1],'[')."</td><td>".$deviceArrya[$devindx][4]."</td><td>".$mobileNumber."</td><td>".$startDateforSummary."</td><td>".$endDateforSummary."</td>".$sumdis."<td>".round($totalDistanceTravelled,2)." Km</td><td>".$currentlatlong."</td><td>Glovision</td><td>".$vehicleStatus."</td></tr>";
             
        }
        if($index==0){
          $sumdis="";
             for($dindx=$fromDate;$dindx<$toDate;){
                     $sumdis=$sumdis."<td></td>";
                   $dindx=$dindx+86400;

               }


           $dis11 = explode("]",$deviceArrya[$devindx][10]);
               $summarydata=$summarydata."<tr><td>".(++$summaryIndx)."</td><td>".trim($dis11[2],'[')."</td><td>".trim($dis11[1],'[')."</td><td>".$deviceArrya[$devindx][4]."</td><td>".$mobileNumber."</td><td>-----</td><td>------</td>".$sumdis."<td>------</td><td>".$currentlatlong."</td><td>Glovision</td><td>".$vehicleStatus."</td></tr>";
   
        }
       
    } //end divice loop
  $sumdays='';
  for($dindx=$fromDate;$dindx<$toDate;){
        $sumdays=$sumdays."<th>".date('d/M',$dindx)."</th>";
       $dindx=$dindx+86400;

       }

    if($reportType=="summary"){
         $table=$summarydata1.$sumdays."<th>Total Distance</th><th>Lat/Long</th><th>Operator</th><th>Status as on ".date('d-m-Y H:i:s',time())."</th></thead><tbody>".$summarydata."<tr></tr></tbody></table>
    </body>
    </html>";
    }else{
       $table=$summarydata1.$sumdays."<th>Total Distance</th><th>Lat/Long</th><th>Operator</th><th>Status as on ".date('d-m-Y H:i:s',time())."</th></thead><tbody>".$summarydata."<tr></tr></tbody></table>".$table."<tr><td colspan='6'></td></tr></tbody></table>
    </body>
    </html>";
    }
    mysqli_close($gtsconnect);

    if($croneRequest=="yes"){
        $mailperAccount=array();
       
               
       $mailperAccount=getMails($tacserver,$tacusername,$tacpassword,$tacdb,$accountID,'distanceidlereport',$group);
        //echo $table;
        mailSendToAccount($mailperAccount,$table,$accountID,'Distance Idle Report for :'.$group);
    }else{
        echo $table;
    }
    function timeDiff($fromTime,$toTime)
    {
        $toTime=strtotime($toTime);
        //error_log($toTime.'totimee222');
        $fromTime=strtotime($fromTime);
      //  error_log($fromTime.'fromtime2222');
        $seconds  = $toTime - $fromTime;
        //$minutes   = round(($interval / 60) % 60);
        $minutes = $seconds;
     // error_log($minutes . "diff in min");
        return $minutes;

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

 


function device_info($gtsserver, $gtsusername, $gtspassword,$gtsdb,$accounts,$userID,$vehicleID){
   global $fromDate,$toDate;
     $now=strtotime("10 October 2018");
   $result=array(array());
   $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error());
   mysqli_select_db($gtsconnect,$gtsdb);
    $vehicleGroup=array();
   $$vehiclecontact=array();
     $vehiclecontact=  vehiclecontact();
     $vehicleGroup=getGroupVehicle();

/*     $groupsforadmin="'ahmedabad','amreli','anand','aravalli','banaskantha','bharuch','bhavnagar','botad','chhotaudepur','dahod','devbhumidwarka','gandhinagar','girsomnath','jamnagar','junagadh','kheda','kutch','mahesana','mahisagar','morbi','narmada','navsari','panchmahals','patan','porbandar','rajkot','sabarkantha','surat','surendranagar','tapi','thedangs','vadodara','valsad'";

  // $vehicleDetails="select vehicleModel,vehicleMake,lastValidLatitude,lastValidLongitude,deviceID from Device where accountID='$accounts'";
   $vehicleDetails="select a.vehicleModel as vehicleModel,a.vehicleMake as vehicleMake,a.lastValidLatitude as lastValidLatitude,a.lastValidLongitude as lastValidLongitude,a.deviceID as deviceID,a.imeiNumber as imeinumber,	lastEventTimestamp,a.simPhoneNumber as simPhoneNumber,b.groupID as groupID,a.simPhoneNumber as contactPhone,a.description as description  from Device a,DeviceList b where a.accountID='$accounts' and b.groupID='$userID' and b.accountID='$accounts' and a.deviceID=b.deviceID ";
   if($userID=="admin" || $userID=="administrator" || $userID=="selectall"){
   $vehicleDetails="select a.vehicleModel as vehicleModel,a.vehicleMake as vehicleMake,a.lastValidLatitude as lastValidLatitude,a.lastValidLongitude as lastValidLongitude,a.deviceID as deviceID,a.imeiNumber as imeinumber,	lastEventTimestamp,a.simPhoneNumber as simPhoneNumber,b.groupID as groupID,a.simPhoneNumber as contactPhone,a.description as description  from Device a,DeviceList b where a.accountID='$accounts' and b.accountID='$accounts' and a.deviceID=b.deviceID ";
   }
   if($vehicleID!="selectall"){
      $vehicleDetails="select a.vehicleModel as vehicleModel,a.vehicleMake as vehicleMake,a.lastValidLatitude as lastValidLatitude,a.lastValidLongitude as lastValidLongitude,a.deviceID as deviceID,a.imeiNumber as imeinumber,	lastEventTimestamp,a.simPhoneNumber as simPhoneNumber,b.groupID as groupID,a.simPhoneNumber as contactPhone,a.description as description from Device a,DeviceList b where a.accountID='$accounts' and b.groupID='$userID' and b.accountID='$accounts' and a.deviceID=b.deviceID and b.deviceID='$vehicleID' and a.deviceID='$vehicleID' ";
   } 
 
    if(strpos($accounts,"gvk")>-1 || strpos($accounts,"gog")>-1 ){
      $vehicleDetails="select a.vehicleModel as vehicleModel,a.vehicleMake as vehicleMake,a.lastValidLatitude as lastValidLatitude,a.lastValidLongitude as lastValidLongitude,a.deviceID as deviceID,a.imeiNumber as imeinumber,	lastEventTimestamp,a.simPhoneNumber as simPhoneNumber,b.groupID as groupID,c.contactPhone as contactPhone,a.description as description  from Device a,DeviceList b,Driver c where a.accountID='$accounts' and b.groupID='$userID' and b.accountID='$accounts' and a.deviceID=b.deviceID and a.deviceID=c.deviceID and c.accountID=a.accountID";
   if($userID=="admin" || $userID=="administrator" || $userID=="selectall"){
   $vehicleDetails="select a.vehicleModel as vehicleModel,a.vehicleMake as vehicleMake,a.lastValidLatitude as lastValidLatitude,a.lastValidLongitude as lastValidLongitude,a.deviceID as deviceID,a.imeiNumber as imeinumber,	lastEventTimestamp,a.simPhoneNumber as simPhoneNumber,b.groupID as groupID,c.contactPhone as contactPhone,a.description as description  from Device a,DeviceList b,Driver c where a.accountID='$accounts' and b.accountID='$accounts' and a.deviceID=b.deviceID and a.deviceID=c.deviceID and c.accountID=a.accountID and b.groupID in ($groupsforadmin)";
   }
   if($vehicleID!="selectall"){
      $vehicleDetails="select a.vehicleModel as vehicleModel,a.vehicleMake as vehicleMake,a.lastValidLatitude as lastValidLatitude,a.lastValidLongitude as lastValidLongitude,a.deviceID as deviceID,a.imeiNumber as imeinumber,	lastEventTimestamp,a.simPhoneNumber as simPhoneNumber,b.groupID as groupID,c.contactPhone as contactPhone,a.description as description from Device a,DeviceList b,Driver c where a.accountID='$accounts' and b.groupID='$userID' and b.accountID='$accounts' and a.deviceID=b.deviceID and b.deviceID='$vehicleID' and a.deviceID='$vehicleID' and a.deviceID=c.deviceID and c.accountID=a.accountID ";
   } 
      
    }
  */
  $vehicleDetails="select a.isActive as isActive,a.vehicleModel as vehicleModel,a.vehicleMake as vehicleMake,a.lastValidLatitude as lastValidLatitude,a.lastValidLongitude as lastValidLongitude,a.deviceID as deviceID,a.imeiNumber as imeinumber,   lastEventTimestamp,a.simPhoneNumber as simPhoneNumber,b.groupID as groupID,a.simPhoneNumber as contactPhone,a.description as description  from Device a,DeviceList b where a.accountID='$accounts' and b.groupID='$userID' and b.accountID='$accounts' and a.deviceID=b.deviceID ";
   if($userID=="admin" || $userID=="administrator" || $userID=="selectall"){
    //  $vehicleGroup=getGroupVehicle();
   $vehicleDetails="select a.isActive as isActive,a.vehicleModel as vehicleModel,a.vehicleMake as vehicleMake,a.lastValidLatitude as lastValidLatitude,a.lastValidLongitude as lastValidLongitude,a.deviceID as deviceID,a.imeiNumber as imeinumber,   lastEventTimestamp,a.simPhoneNumber as simPhoneNumber,a.simPhoneNumber as contactPhone,a.description as description  from Device a where a.accountID='$accounts' ";
   }
   if($vehicleID!="selectall"){
      //   $vehicleGroup=getGroupVehicle();
      $vehicleDetails="select a.isActive as isActive,a.vehicleModel as vehicleModel,a.vehicleMake as vehicleMake,a.lastValidLatitude as lastValidLatitude,a.lastValidLongitude as lastValidLongitude,a.deviceID as deviceID,a.imeiNumber as imeinumber,lastEventTimestamp,a.simPhoneNumber as simPhoneNumber,a.simPhoneNumber as contactPhone,a.description as description from Device a where a.accountID='$accounts' and a.deviceID='$vehicleID' ";
   }
 
  
   error_log($vehicleDetails);
   $qry1 = mysqli_query($gtsconnect,$vehicleDetails) or die(mysqli_error());
   $rowcount=0;
   error_log('Devicessssss');
   while($row = mysqli_fetch_assoc($qry1)){
        if($row['isActive']==1||($row['isActive']==0 && ($fromDate<$now || $toDate<$now))){
        $result[$rowcount][0]=$row['vehicleMake'];
        $result[$rowcount][1]=$row['vehicleModel'];
        $result[$rowcount][2]=$row['lastValidLatitude'];
        $result[$rowcount][3]=$row['lastValidLongitude'];
        $result[$rowcount][4]=$row['deviceID'];
         $result[$rowcount][5]=$row['imeinumber'];
        $result[$rowcount][6]=$row['lastEventTimestamp'];
        $result[$rowcount][7]=$row['simPhoneNumber'];
       // $result[$rowcount][8]=$row['groupID'];
        if($vehicleGroup[$row['deviceID']]=="" || $vehicleGroup[$row['deviceID']]==null){  $vehicleGroup[$row['deviceID']]="ungroup";}
             $result[$rowcount][8]="[".$row['deviceID']."][".$vehicleGroup[$row['deviceID']]."][".$vehicleGroup[$row['deviceID']]."]";
         $result[$rowcount][9]=$vehiclecontact[$row['deviceID']]==null?"0000000000":$vehiclecontact[$row['deviceID']];;
         $result[$rowcount][10]="[".$row['deviceID']."][".$vehicleGroup[$row['deviceID']]."][".$vehicleGroup[$row['deviceID']]."]";//$row['description'];


          $result[$rowcount][10]=$row['description'];
    //    $result[$rowcount][9]=$row['contactPhone'];
      //   $result[$rowcount][10]=$row['description'];
      // echo $result[$rowcount][4].'  '. $result[$rowcount][7].'<br>';
        $rowcount++;
        }
        
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

  function getGroupVehicle(){
     global $id,$gtsserver,$gtsusername,$gtspassword,$gtsdb,$accountID;
     $result=array();
      $groupsforadmin="'ahmedabad','amreli','anand','aravalli','banaskantha','bharuch','bhavnagar','botad','chhotaudepur','dahod','devbhumidwarka','gandhinagar','girsomnath','jamnagar','junagadh','kheda','kutch','mahesana','mahisagar','morbi','narmada','navsari','panchmahals','patan','porbandar','rajkot','sabarkantha','surat','surendranagar','tapi','thedangs','vadodara','valsad'";
     $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($tacconnect));
     mysqli_select_db($gtsconnect,$gtsdb);
     $query="select groupID,deviceID from DeviceList where accountID='$accountID' and groupID in ($groupsforadmin)";
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


function mailSendToAccount($mailperAccount,$totalReport,$accountID,$reportType){
  //echo $totalReport;
if ($totalReport!=""){
    $from = " Glovision <support@glovision.co>";
    $host ="209.126.105.231";
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

function getMails($tacserver,$tacusername,$tacpassword,$tacdb,$accountId,$desc,$group){
$tacconnect = mysqli_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database: " . mysqli_error());
mysqli_select_db($tacdb, $tacconnect);

$query1 = "SELECT DISTINCT emailaddress FROM email WHERE accountID ='$accountId' and description='$desc' and groupID='$group'";

error_log($query1);
$qry_result = mysqli_query($query1) or die(mysqli_error());

$mails=Array();
$i=0;

 while($row = mysqli_fetch_assoc($qry_result))
  {
  
    $mails[$i++]= $row['emailaddress'];
     //echo  $row['emailaddress'];    
        
                                           
  }
mysqli_close($tacconnect);
return $mails;
}


function saveDistanceIdleReport($tacserver,$tacusername,$tacpassword,$tacdb,$accountID, $deviceID, $groupID, $baseLocation, $crewNumber, $idleLocations, $idleTime, $startTime, $startLocation, $travelledDistance, $timeStamp, $endTime, $endLocation, $route,$District){
$tacconnect = mysqli_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database: " . mysqli_error());
mysqli_select_db($tacdb, $tacconnect);
$epochtimestamp=strtotime($timeStamp);
$uniqueID=$epochtimestamp.$deviceID;
$query1 = "INSERT INTO distanceIdleReport (accountID, deviceID, groupID, baseLocation, crewNumber, idleLocations, idleTime, startTime, startLocation, travelledDistance, timeStamp, endTime, endLocation, route,District,uniqueID) VALUES ('$accountID', '$deviceID', '$groupID', '$baseLocation', '$crewNumber', '$idleLocations', '$idleTime', '$startTime', '$startLocation', '$travelledDistance', '$epochtimestamp', '$endTime', '$endLocation','$route','$District','$uniqueID') ON DUPLICATE KEY UPDATE  idleLocations='$idleLocations' , idleTime='$idleTime' , startTime='$startTime' , startLocation='$startLocation' , travelledDistance='$travelledDistance' ,endTime='$endTime' ,endLocation='$endLocation' , route='$route' ";

error_log($query1);
$qry_result = mysqli_query($query1) or die(mysqli_error());

}

function geozoneList(){
 global $gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$geozoneList;
$gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error());
    mysqli_select_db($gtsdb, $gtsconnect);

    $query = "SELECT geozoneID,radius,latitude1,longitude1,groupID,description,radians(latitude1) as lat1,radians(longitude1) as long1 FROM Geozone where accountID='$accountID' ";

    $qry_result = mysqli_query($query) or die(mysqli_error());
    
    $i=0;
    error_log($query);
    while($row = mysqli_fetch_assoc($qry_result))
    {
  
        $geozoneList[$i][0]= $row['geozoneID'];
        
        $geozoneList[$i][1]= $row['radius'];
        $geozoneList[$i][2]= $row['latitude1'];
        $geozoneList[$i][3]= $row['longitude1'];
        $geozoneList[$i][4]= $row['groupID'];
        $geozoneList[$i][5]= $row['description'];
        $geozoneList[$i][6]= $row['lat1'];
        $geozoneList[$i][7]= $row['long1'];
       // echo $geozoneList[$i][0].'<br>';
        $i++;
    }
    mysqli_close($gtsconnect);

}

function zoneID($lat1,$lon1){
  global $geozoneList;
  $R = 6371;// Earth's radius in Km
  $geozoneID="";
  for($i=0;$i<count($geozoneList);$i++){
     //$redus=acos(sin($lat1)*sin($result[$i][2]) + cos($lat1)*cos($result[$i][2]) * cos($result[$i][3]-$lon1)) * $R;
     $redus=zoneDistance($lat1,$lon1,$geozoneList[$i][2],$geozoneList[$i][3]);

     if($redus<=(intval($geozoneList[$i][1]))){
          // echo "distance ".$x."   ".$geozoneList[$i][0]."  ".$geozoneList[$i][5]."  <br>";
        $geozoneID=$geozoneList[$i][5];
        break;
      }else{
        $geozoneID="";
      }
   
  }
  
   
  return $geozoneID;
}


function zoneDistance($lat1, $lon1, $lat2, $lon2) {
  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;
  return ($miles * 1609.344);
}



?>
