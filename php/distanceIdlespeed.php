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
       //crone url
     //localhost/distanceidlereport/distanceIdle.php?accountID=rwsscto&vehicleID=selectall&group=baireddipalle-mandal&reportFormate=html
    include 'config.php';
    require_once 'Mail.php';

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
   $offlinetimerange=$_GET['offlinetime'];//mints
//error_log($accountID.'Acc-ID');
//error_log($from.'From');
//error_log($to.'To');
error_log($fromTimee.'From Timee');
error_log($toTimee.'To Timee');
//error_log($reportFormate.'Rep For');
     $croneRequest="no";
    if (isset($_GET['fromDate'])) {
         error_log($from.'from orig');
         $dt = new DateTime($from); 
         $fromdate1= $dt->format('Y-m-d ' .$fromTimee);
         $fromDate=strtotime($fromdate1);
         error_log($fromdate1.'fromdate');
         error_log(strtotime($fromdate1).'fromdateepoch');
         $dt1 = new DateTime($to);
         $todate1= $dt1->format('Y-m-d ' .$toTimee);
         $toDate=strtotime($todate1);

         error_log($todate1.'to date');
         error_log(strtotime($todate1).'todateepoch');   
         
         error_log($fromDate.'============= > fromdate111');
         error_log($toDate.'=============== > todate222');
    }else{  
         $setup=parse_ini_file("setup.ini",true);// loading setup file for from time and to time
         $starttime=$setup[$accountID.".fromtime"];
         $endtime=$setup[$accountID.".totime"];
         $idleTime = $setup[$accountID.".idletime"];
         $croneRequest="yes";
         $toDayDate=strtotime(date('d-M-Y',strtotime("-1 days")).'');
         $d=date('d',$toDayDate);
         $m=date('m',$toDayDate);
         $y=date('Y',$toDayDate);
         $fromTimee= "0".$starttime.":00:00";
         $toTimee=$endtime.":00:00";
         //error_log('dddddddddddd '.date("d-m-Y h:i:s a", mktime($time[0],$time[1],$time[2],$m,$d,$y)));   
         $fromDate=strtotime(date("d-m-Y h:i:s a", mktime($starttime,0,0,$m,$d,$y)));
         $toDate=strtotime(date("d-m-Y h:i:s a", mktime($endtime,0,0,$m,$d,$y)));
         //$fromTimee= "0".$starttime.":00:00";
         //$toTimee=$endtime.":00:00";
         //$fromDate=strtotime(date("d-m-Y h:i:s a", mktime($starttime,0,0,$m,$d,$y)));
         //$toDate=strtotime(date("d-m-Y h:i:s a", mktime($endtime,0,0,$m,$d,$y)));
         if(strpos($accountID,"gvk")>-1 || strpos($accountID,"gog")>-1){
             $toTimee=$endtime.":59:59";
             $toDate=strtotime(date("d-m-Y h:i:s a", mktime($endtime,59,59,$m,$d,$y)));
         }
    }
     $now=time();
$diff=abs($fromDate-$now);
error_log($fromDate.'fromdate'.$now.'-now');
$diffindays = floor(($diff)/86400);
error_log($diffindays.'diff');
 
     $image="images/".$accountID.".jpg";
     if(strpos($accountID,"gvk")>-1  || strpos($accountID,"gog")>-1){
         $image="images/gvk.jpg";
     }
   
    
 
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



  
  $queryString=substr($_SERVER['REQUEST_URI'],0,strpos($_SERVER['REQUEST_URI'],"?"));
     $abspath="http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST'].str_replace("distanceIdle.php","tripMap.php",$queryString);

 $downloadurl="http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST'].str_replace("distanceIdle.php","distanceIdleFromDB.php",$queryString);

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
    <center>";

//   <a href='".$downloadurl."?accountID=".$accountID."&vehicleID=selectall&fromDate=".date('d-m-Y',$fromDate)."&toDate=".date('d-m-Y',$toDate)."&fromTime=".$fromTimee."&toTime=".$toTimee."&group=".$group."&reportFormate=excel&idletime=".$idleTime."&reportType=full'>Click to Download</a>  ";
   
    if($deviceID != "selectall"){
        $dis11 = explode("]",$deviceArrya[0][10]);
        if(strpos($accountID,"gvk")>-1 || strpos($accountID,"gog")>-1){
            $mobileNumber=$deviceArrya[0][9];
            $table1= $table1."<style type='text/css'>
.tablecss {
        
        width:100%;
        border-collapse: collapse;
        color:black;
        background:white;
        font-family:Arial,Verdana,sans-serif;
        font-size:13;
}
</style><div id='foot'>                  
             <center> Tracking  Report for Single Vehicle</center>
             </div>Group :".strtoupper($group)."<br>Asset Name: ".$deviceArrya[0][4]." <br> Mobile No:".$mobileNumber;
        }else{
             $mobileNumber=$deviceArrya[0][7];
            $table1= $table1."<div id='foot'>                  
             <center>Mobile Tracking  Report for Single Number</center>
             </div>Group :".strtoupper($group)."<br>Asset Name: ".trim($dis11[1],'[').' - '.trim($dis11[0],'[')." <br> Mobile No:".$mobileNumber;
 
        }
             
        
    }else{
        
        if(strpos($accountID,"gvk")>-1 || strpos($accountID,"gog")>-1){
            $table1= $table1."<style type='text/css'>
.tablecss {
        
        width:100%;
        border-collapse: collapse;
        color:black;
        background:white;
        font-family:Arial,Verdana,sans-serif;
        font-size:13;
}
</style><div id='foot'>                  
             <center>Day Wise Vehicles Tracking  Report </center>
             </div>Group :".strtoupper($group)."<br>";
        }else{
             $table1= $table1."<style type='text/css'>
.tablecss {
        
        width:100%;
        border-collapse: collapse;
        color:black;
        background:white;
        font-family:Arial,Verdana,sans-serif;
        font-size:13;
}
</style><div id='foot'>                  
             <center>Day Wise Mobile Tracking  Report </center>
             </div>Group :".strtoupper($group)."<br>";
  
        }
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
  $sumdays='';
 $days=array();
   $summarydata='';
    $summarydata1=$table1."<br><table border='1px' class='tablecss'><caption>Distance Summary Report</caption><thead  bgcolor='black' style='color:white'><th>Index</th> <th>Group</th><th>Asset</th><th>Mobile No</th><th>From Date</th><th>To Date</th><th>Total Distance</th></thead><tbody>"; 
     if(strpos($accountID,"gvk")>-1 || strpos($accountID,"gog")>-1){
  //       $summarydata1=$table1."<br><table border='1px'><caption>Distance Summary Report</caption><thead><th>Index</th> <th>District</th><th>Base Location</th><th>Asset</th><th>Crew Number</th><th>From Date</th><th>To Date</th><th>Total Distance</th><th>Lat/Long</th><th>Operator</th><th>Status</th></thead><tbody>"; 
      $summarydata1=$table1."<br><table border='1px' class='tablecss'><caption>Distance Summary Report</caption><thead  bgcolor='black' style='color:white'><th>Index</th> <th>District</th><th>Base Location</th><th>Asset</th><th>Crew Number</th><th>From Date</th><th>To Date</th>";
    }
       if($croneRequest=="yes"){
                  $summarydata1=$table1."<br><table border='1px'><caption>Distance Summary Report</caption><thead><th>Index</th> <th>Group</th><th>Asset</th><th>Mobile No</th><th>Total Distance</th></thead><tbody>";
                  if(strpos($accountID,"gvk")>-1 || strpos($accountID,"gog")>-1){
                       $summarydata1=$table1."<br><table border='1px'><caption>Distance Summary Report</caption><thead><th>Index</th> <th>District</th><th>Base Location</th><th>Asset</th><th>Crew Number</th><th>Total Distance</th><th>Lat/Long</th><th>Operator</th><th>Status</th></thead><tbody>";

                  }


        }

       
   if($deviceID != "selectall"){
        if(strpos($accountID,"gvk1")>-1 || strpos($accountID,"gog1")>-1){
            $table= $table."<table border='1px' class='tablecss'><caption>Detailed Distance Report</caption><thead  bgcolor='black' style='color:white'><th>Index</th><th>Date</th><th>Start Time Of <br> The Day</th><th>End Time Of <br> The Day</th><th>Distance</th><th>Offline Time</th><th>Travel Route</th></thead><tbody>";
        }else{
                    $table= $table."<table border='1px' class='tablecss'><caption>Detailed Distance Report</caption><thead  bgcolor='black' style='color:white'><th>Index</th><th>Date</th><th>Idle Location For More<br> Than $idleTime mins</th><th>Total Idle Time</th><th>Start Time Of <br> The Day</th><th>End Time Of <br> The Day</th><th>Distance</th><th>Offline Time</th><th>Travel Route</th></thead><tbody>";
        }
    }else{
 
        if(strpos($accountID,"gvk1")>-1 || strpos($accountID,"gog1")>-1) {
                       $table= $table."</center><table border='1px' class='tablecss' > <caption>Detailed Distance Report</caption><thead  bgcolor='black' style='color:white'><th>Index</th><th>Date</th><th>Asset</th><th>Start Time Of <br> The Day</th><th>End Time Of <br> The Day</th><th>Distance</th><th>Offline Time</th><th>Travel Route</th></thead><tbody>";
        }else{       
          $table= $table."</center><table border='1px' class='tablecss'><caption>Detailed Distance Report</caption><thead  bgcolor='black' style='color:white'><th>Index</th><th>Date</th><th>Asset</th><th>Idle Location For More<br> Than $idleTime mins</th><th>Total Idle Time</th><th>Start Time Of <br> The Day</th><th>End Time Of <br> The Day</th><th>Distance</th><th>Offline Time</th><th>Travel Route</th></thead><tbody>";
        }
    }
   // $summaryIndx=0;
   
    for($devindx=0;$devindx<count($deviceArrya);$devindx++) {
         
        $allidlelatpervehilce='';
        $allidlelongpervehilce='';
        $mobileNumber=$deviceArrya[$devindx][7];
        
        if(strpos($accountID,"gvk")>-1 || strpos($accountID,"gog")>-1){
            $mobileNumber=$deviceArrya[$devindx][9];
            
        }
        $totalDistanceTravelled=0;
        $address='';
        $fromTime='';
        $time1='';
        $statusCode='';
        
        $indx=0;
        $gtsconnect = mysqli_connect('localhost','root', 'gl0v1s10n') or die ("Unable to connect to the database1 : " . mysqli_error());
       if($from==date('d-M-Y') || $to==date('d-M-Y')){
                  mysqli_select_db($gtsconnect, 'gts');

         }else{
        //mysqli_select_db('gtshealth', $gtsconnect);
        mysqli_select_db($gtsconnect, 'gts');

         } 

         $data=array(array());
      for($m=$fromDate;$m<$toDate;){
             $m1=$m+36000;
 

       $sql = "Select accountID,deviceID,address,from_unixtime( timestamp, '%d-%m-%Y %H:%i:%s' ) as time1,statusCode,latitude,longitude,geozoneID,speedKPH from EventData where accountID='$accountID' and deviceID='".$deviceArrya[$devindx][4]."' and timestamp BETWEEN $m AND $m1";
             $m=$m1; 
   //    echo $sql;
error_log($sql);
        $qry_result = mysqli_query($gtsconnect, $sql) or die(mysqli_error());
        $num_rows = mysqli_num_rows($qry_result);
        //error_log($num_rows);
      
        $s='';
        $eventIncreament='yes';  
        while($row = mysqli_fetch_assoc($qry_result)) {
         //  echo $row['accountID'].'   '.$row['deviceID'].'   '.$row['statusCode'].'    '.$row['time1'].'<br>';
if($indx>=2){ //delete drift event compare 1 to second and first to third.
                 $dis2to1=distance1($data[$indx-2][5],$data[$indx-2][6], $data[$indx-1][5], $data[$indx-1][6]);
                 $dis1topresent=distance1($row['latitude'],$row['longitude'], $data[$indx-2][5], $data[$indx-2][6]);
                if($dis2to1>$dis1topresent){
                   $indx--;
                // echo 'ddd';
                }

          }
 
           $data[$indx][0]=$row['accountID'];
            $data[$indx][1]=$row['deviceID'];
            $data[$indx][2]=$row['address'];
            $data[$indx][3]=$row['time1'];
           
            $data[$indx][4]=$row['statusCode'];
            if($row['statusCode']=="61472"){
                  $data[$indx][4]="61718";
             }
            $data[$indx][5]=$row['latitude'];
            $data[$indx][6]=$row['longitude'];
           
           
           if($indx==0){
                $data[$indx][9]=0;    
            }else{
                 $data[$indx][9]=abs(strtotime($data[$indx][3])-strtotime($data[$indx-1][3]))/60;

             }

           if($indx>0 && $row['latitude']!= 0 && $row['longitude']!=0 && $data[$indx-1][5]!=0 && $data[$indx-1][6]!=0) {
                      $diffdis=distance1($row['latitude'],$row['longitude'], $data[$indx-1][5], $data[$indx-1][6]);
                 $difftimes=abs(strtotime($data[$indx][3])-strtotime($data[$indx-1][3]))/60;
                if( $diffdis>5 && $difftimes>=5 && ($accountID=="gvkgjtest" || $accountID=="goghealth" ||  $accountID=="gvk-ut-health" || $accountID=="gvk-assam")){
                        $googledistance= GetDrivingDistance($data[$indx-1][5],$data[$indx][5],$data[$indx-1][6],$data[$indx][6]);
                             $km=strpos($googledistance,"km"); 
                            $m=strpos($googledistance,"m");
                            $diss=0;
                            if($km>0){
                                   $km=trim($googledistance,"km");
                                   $disarray=explode(",", $km);

                                   $diss=$disarray[0];
                            }else{
                                     $m=trim($googledistance,"m");
                                     $diss=$m/1000;
                             }
 
                             $data[$indx][7]=$diss;
                $difftimes=abs(strtotime($data[$indx][3])-strtotime($data[$indx-1][3]))/60;

              }else{

                $data[$indx][7]=distance1($row['latitude'],$row['longitude'], $data[$indx-1][5], $data[$indx-1][6]);
            if($accountID != "gvkrajasthan"){
                    $difftimes=abs(strtotime($data[$indx][3])-strtotime($data[$indx-1][3]));
                    if(is_nan($data[$indx][7]) || ($data[$indx][7]/$difftimes>0.025)){//if distnace greater than 2 km with 5 minits then distnace=0 bzc drift point  
                  $eventIncreament="no"; 
                    $data[$indx][7]=0;//$data[$indx][7];
                    }
               }
  

             }
           } else {
                $data[$indx][7]=0;
           }

             if(is_nan($data[$indx][7])){
                 $data[$indx][7]=0;
            }

            $data[$indx][8]=$row['geozoneID'];
      if($eventIncreament=="yes"){
            $indx++;
        }else{
           $eventIncreament="yes";
         }
}


}
 //nd of while
        
       $startDateforSummary='';
       $endDateforSummary='';
        $startTime='';
        $endTime='';
        $travelPath='';
        $idleStart='stop';
        $idlelat='';
        $offlinedetails='';
        $idlelong='';
        $index=0;
        $totalIdleTime=0;
        $startDate='';
        $startDateTime='';
        $startlatlong='';
        $endlatlong='';
        $startAddress='';
        $endAddress='';
        $addressCount=1;
        $idlelat='';
        $idlelong='';
        $idleStartTime='';
        $idleStopTime='';
        $firstEventOfTheDay = 0;
        $eventsForIndividualDay=array();
        $fromTimeIntval = intval(date('H',strtotime($fromTimee)));
        $toTimeIntval =intval(date('H',strtotime($toTimee)));
        $toTimemin =intval(date('i',strtotime($toTimee)));
        $toTimesec =intval(date('s',strtotime($toTimee)));
        $lastEventOfTheDay = 0;
        $perdaystartaddress='';
        $perdayendaddress='';
        $mapaddress='';
        //for zone List
        $geozoneList=array(array());
      
       // geozoneList();
   
      
      // $days=array();
       $daywiseDistance=array(); 
       $totalofflinetime=0;
       $daywisetotalofflinetime=0;
        for ($indx=0;$indx<=count($data);$indx++) { // idle for each day and print end of day.

              $intvalue= intval(date('H',strtotime($data[$indx][3])));
              $min=intval(date('i',strtotime($data[$indx][3])));
              $sec=intval(date('s',strtotime($data[$indx][3])));
                //echo $data[$indx][3].'   '.$data[$indx+1][3].'   '.(abs(strtotime($data[$indx][3])-strtotime($data[$indx+1][3]))/60).'  <br> ';
                 // echo $intvalue; 
              // reject all early time interval events of any day or after the last event for the day was processed.
               if($data[$indx][9]>$offlinetimerange){
                   $totalofflinetime=$totalofflinetime+$data[$indx][9]*60;
                   $daywisetotalofflinetime=$daywisetotalofflinetime+$data[$indx][9]*60;
                   $offlinedetails=$offlinedetails."* From:".$data[$indx-1][3]." To:".$data[$indx][3]." OfflineTime ".secondsToTimeFormate($data[$indx][9]*60)."<br>";

              }
              if($intvalue<$fromTimeIntval || ($lastEventOfTheDay == 1 && $intvalue>=$toTimeIntval)) {   
                   continue;
              }
            //  echo $intvalue."  ".$min."  ".$sec."<br>";   
              // New Day initialization only for the first event of the day.
              if ($startDate=='' || ($startDate!=date('d-m-Y',strtotime($data[$indx][3])) && ($firstEventOfTheDay == 0))){
                 //     error_log(date('d-m-Y H:i:s',strtotime($data[$indx][3])).'startDate init');
                      $startDate=date('d-m-Y',strtotime($data[$indx][3]));
                      $startDateTime=date('d-m-Y H:i:s',strtotime($data[$indx][3]));
                      //$startAddress=$data[$indx][2]; 
                       $startAddress=zoneID($data[$indx][5],$data[$indx][6]);
                       if($startAddress==""){
                          $startAddress=$data[$indx][2];
                       }

                       $startAddress=$startAddress.'<b>('.round($data[$indx][5],5).'/'.round($data[$indx][6],5).')</b>';  
                     if(strtotime($data[$indx][8])==''){
                            $perdaystartaddress=str_replace(" ","-",$data[$indx][2]);
                            
                       }else{
                            $perdaystartaddress=$data[$indx][2];
                            
                       }
                       $startlatlong=round($data[$indx][5],5).'-'.round($data[$indx][6],5);
                     
                      //$startlatlong=$data[$indx][5].'-'.$data[$indx][6];
                      $firstEventOfTheDay = 1;
                      $lastEventOfTheDay = 0;  // reinit end of Day for endTime display.
                      $travelPath = '';
                      $totalIdleTime = 0;
                      $perDayDistanceTravelled = 0;
                      $daywisetotalofflinetime=0;
                      $offlinedetails='';
             } 

              // end of the Day processing for the same date only for one last event.
              if ($indx > 0 && $lastEventOfTheDay == 0 &&
                   (
                     ($startDate==date('d-m-Y',strtotime($data[$indx][3])) && ($intvalue>=$toTimeIntval) && ($min>=$toTimemin) && ($sec>=$toTimesec)) || 
                     ($startDate!=date('d-m-Y',strtotime($data[$indx+1][3])))
                   )) {
                   $lastEventOfTheDay = 1;
                   if  ($startDate==date('d-m-Y',strtotime($data[$indx][3])) && ($intvalue>=$toTimeIntval)) {
                     //  error_log(date('d-m-Y H:i:s',strtotime($data[$indx][3])).'end of day event after time period');
                       $lastEventIndx=$indx-1;  // after time period.
                   } else if ($startDate!=date('d-m-Y',strtotime($data[$indx+1][3]))) {
                    //   error_log(date('d-m-Y H:i:s',strtotime($data[$indx][3])).'last event of the day before time period end');
                       $lastEventIndx=$indx;  // after time period.
                   } else {
                   //    error_log(date('d-m-Y H:i:s',strtotime($data[$indx][3])).'last event of the day all other conditions');
                       $lastEventIndx=$indx-1;  // after time period.
                   }
                   $endTime=$data[$lastEventIndx][3];  // after time period.
                   //$endAddress=$data[$lastEventIndx][2];
                   $endAddress=zoneID($data[$lastEventIndx][5],$data[$lastEventIndx][6]);
                       if($endAddress==""){
                          $endAddress=$data[$indx][2];
                       }
                    $endAddress=$endAddress.'<b>('.round($data[$lastEventIndx][5],5).'/'.round($data[$lastEventIndx][6],5).')</b>';
                   $endlatlong=round($data[$lastEventIndx][5],5).'-'.round($data[$lastEventIndx][6],5);

                     if(strtotime($data[$indx][8])==''){
                           // $perdaystartaddress=str_replace(" ","-",$data[$indx][2]);
                             $perdayendaddress=str_replace(" ","-",$data[$lastEventIndx][2]); 
                       }else{
                           $perdayendaddress=$data[$lastEventIndx][2];
                            
                       }
                      $endlatlong=round($data[$lastEventIndx][5],5).'-'.round($data[$lastEventIndx][6],5);
                          
   
                   $toTime=$data[$lastEventIndx][3];
                   
                   // check if idle has been more than 20 mins.
                   if ($idleStart=="start" ) {
                       $idleStart="stop";
                       $idleStartTime=$fromTime;
                       $idleStopTime=$toTime;
                       $diff=timeDiff($fromTime,$toTime);
                       if($startTime==''){
                            $startTime=$fromTime;
                       }
                        //Idle time check more than 20 minits
                        
                        if ($diff>($idleTime*60)){ 
                              $totalIdleTime=$totalIdleTime+($diff/60); // accumulate for end of all days.
                              // $travelPath = $travelPath . $address ."( Wait Time: ".secondsToTimeFormate($diff*60) ." )," ."<br>";
                              //$travelPath = $travelPath .$addressCount++.')' . '<b>'.$address.'</b>'.'('.$idleStartTime.' to '.$idleStopTime.')'.'('.secondsToTimeFormate($diff*60).')'."<br>";
                              $travelPath = $travelPath .$addressCount++.')' . '<b>'.$address.'</b>'.'( '.$idleStartTime.')'.'('.secondsToTimeFormate($diff).')'."<br>";
                               $idlelat=$idlelat.round($data[$lastEventIndx][5],5).',';
                               $idlelong=$idlelong.round($data[$lastEventIndx][6],5).',';
      
                                $allidlelatpervehilce=$allidlelatpervehilce.round($data[$lastEventIndx][5],5).',';
                                $allidlelongpervehilce=$allidlelongpervehilce.round($data[$lastEventIndx][6],5).',';
                             // $idlelat='';
                             // $idlelong='';
                               
                              
                              // echo " eee " . $address . " eeee " . $diff .'  '.$fromTime.'   '.$toTime ."<br>";
                         }
                   }
                   if($deviceID != "selectall"){
                           if(strpos($accountID,"gvk1")>-1 || strpos($accountID,"gog1")>-1){
                               $table=$table.'<tr><td>'.(++$index).'</td><td>'.$startDate.'</td>';
                           }else{
                            $table=$table.'<tr><td>'.(++$index).'</td><td>'.$startDate.'</td><td>';
 
                           }
                   } else { 
                         
                           if(strpos($accountID,"gvk1")>-1 || strpos($accountID,"gog1")>-1){
                                                                  $table=$table.'<tr><td>'.(++$index).'</td><td>'.$startDate.'</td><td>'.$deviceArrya[$devindx][4].'<br>'.$mobileNumber.'</td>';
                            }else{
                               $dis11 = explode("]",$deviceArrya[$devindx][10]);
                               $table=$table.'<tr><td>'.(++$index).'</td><td>'.$startDate.'</td><td>'.$deviceArrya[$devindx][4].'<br>'.$mobileNumber.'<br>'.trim($dis11[1],'[').'</td><td>';
                            }
                   }
                   if($index==1){
                      $startDateforSummary=$startDate;
                   }
                  $endDateforSummary=$startDate;
               
                         // accumulate all distance travelled during time period of all days.
                         if ($intvalue<$toTimeIntval)
                               $perDayDistanceTravelled = $perDayDistanceTravelled + $data[$lastEventIndx][7]; 
                                
                         
                         error_log($perDayDistanceTravelled.'per day dist');
                         $totalDistanceTravelled=$totalDistanceTravelled+ $perDayDistanceTravelled;
                         
                          $tripUrl=$abspath."?accountID=".$accountID."&vehicleID=".$deviceArrya[$devindx][4].'&departTime='.strtotime($endTime).'&platEntryTime='.strtotime($startDateTime)."&name=".$deviceArrya[$devindx][7]."&travelDistance=".round($perDayDistanceTravelled,2)."&destination=".$perdayendaddress."&factory=".$perdaystartaddress."&slatlong=".$startlatlong."&endlatlong=".$endlatlong."&idlelat=".$idlelat."&idlelong=".$idlelong."&onlyidles=no";
                       //save distance idle report for fast access 
                       $dis11 = explode("]",$deviceArrya[$devindx][10]);
                       if($croneRequest=="yes"){
                       saveDistanceIdleReport($tacserver,$tacusername,$tacpassword,$tacdb,$accountID, $deviceArrya[$devindx][4],$deviceArrya[$devindx][8], trim($dis11[1],'['), $mobileNumber, $travelPath, secondsToTimeFormate($totalIdleTime*60), $startDateTime, $startAddress, round($perDayDistanceTravelled,2), $startDate, $endTime, $endAddress, $tripUrl,trim($dis11[2],'['));
                     }
                        if(strpos($accountID,"gvk1")>-1 || strpos($accountID,"gog1")>-1){
                           //  $days[count($days)]=date('d',strtotime($startDateTime));
                             $daywiseDistance[date('d-m-Y',strtotime($startDateTime))]=round($perDayDistanceTravelled,2);

	     $table=$table."<td>".$startAddress."<br>".$startDateTime."</td><td>".$endAddress."<br>".$endTime."</td><td>".round($perDayDistanceTravelled,2)." Kms</td><td>".$offlinedetails."<br><b>Total Offline Time:".secondsToTimeFormate($daywisetotalofflinetime)."</b></td><td><a href=".$tripUrl." target='_blank'>Route</a></td></tr>";
                        }else{
                        //         $days[count($days)]=date('d',strtotime($startDateTime));
                          //   $daywiseDistance[count($daywiseDistance)]=round($perDayDistanceTravelled,2);
                            $daywiseDistance[date('d-m-Y',strtotime($startDateTime))]=round($perDayDistanceTravelled,2);
                         $table=$table.$travelPath."</td><td>".secondsToTimeFormate($totalIdleTime*60)."</td><td>".$startAddress."<br>".$startDateTime."</td><td>".$endAddress."<br>".$endTime."</td><td>".round($perDayDistanceTravelled,2)." Kms</td><td>".$offlinedetails."<br><b>Total Offline Time:".secondsToTimeFormate($daywisetotalofflinetime)."</b></td><td><a href=".$tripUrl." target='_blank'>Route</a></td></tr>";
                       }
                   $daywisetotalofflinetime=0;
                   $offlinedetails='';
                   $idlelat='';
                   $idlelong='';
                   $perdaystartaddress='';
                   $perdayendaddress='';
                   $diff=0;
                   $fromTime=0;
                   $addressCount=1;
                   $address='';
                   $toTime=0;
                   $startTime='';
                   $travelPath='';
                   $idleStart="stop";
                   $firstEventOfTheDay=0; // go for the next day's first event to start distance calculation.
                   continue;
              } // end of Day processed.
                // 61715 -STOP statuscode
                // 61716 - DORMANT status code
       
              if (($data[$indx][4]=='61718' || $data[$indx][4]=='61715' || $data[$indx][4]=='61716') && $idleStart=="stop") {
                 // error_log(date('d-m-Y H:i:s',strtotime($data[$indx][3])).'idle begin');
                  $fromTime=$data[$indx][3];
                  if($data[$indx][8] !='') {
                       $address=$data[$indx][8];
                        // $href='https://www.google.co.in/maps/place/'.$data[$indx][5].','.$data[$indx][6];
                      // $address=$data[$indx][2].'<a href='.$href.' target="_blank">('.round($data[$indx][5],5).','.round($data[$indx][6],5).')</a>';
                       $mapaddress='goneID';
                  } else {
                        $href='https://www.google.co.in/maps/place/'.$data[$indx][5].','.$data[$indx][6];

                       $zoneID=zoneID($data[$indx][5],$data[$indx][6]);
                       //echo 'address'.$zoneID.'<br>';
                       if($zoneID==""){
                          $zoneID=$data[$indx][2];
                       }
                       $address=$zoneID.'<a href='.$href.' target="_blank">('.round($data[$indx][5],5).','.round($data[$indx][6],5).')</a>';
                       //$address=$data[$indx][2];
                       $mapaddress='address';
                  }
                 
                  $idleStart="start";

                  if ($indx>0)
                      $perDayDistanceTravelled = $perDayDistanceTravelled + $data[$indx][7];

                  continue;
             }
             if ($idleStart=="start" && ($data[$indx][4] <> '61718' && $data[$indx][4] <> '61715' && $data[$indx][4] <> '61716' ) ) {
               //   error_log(date('d-m-Y H:i:s',strtotime($data[$indx][3])).'idle end');
                  //        echo date('d-m-Y H:i:s',strtotime($data[$indx][3])) .' '.$data[$indx][4].' '.$intHours.' '.$fromTimee.' '.$toTimee.' <br>';
                  // It is either not idle status code  OR beyond time period.
                  //    echo 'Not Idle or during '.$fromTimee.' '.$toTimee.' <br>';
                  $toTime=$data[$indx-1][3];
                  $idleStart="stop";
                  $idleStartTime=$fromTime;
                  $idleStopTime=$toTime;
                  $diff=timeDiff($fromTime,$toTime);
                   if($startTime==''){
                         $startTime=$fromTime;
                   }
                   //Idle time check 
                   if ($diff>($idleTime*60)){
                         $idlelat=$idlelat.round($data[$indx][5],5).',';
                         $idlelong=$idlelong.round($data[$indx][6],5).',';

                         $allidlelatpervehilce=$allidlelatpervehilce.round($data[$indx][5],5).',';
                         $allidlelongpervehilce=$allidlelongpervehilce.round($data[$indx][6],5).',';
 
                         $totalIdleTime=$totalIdleTime+($diff/60);
                         //$travelPath = $travelPath . $address ."( Wait Time: ".secondsToTimeFormate($diff*60) ." )," ."<br>";
                         $travelPath = $travelPath .$addressCount++.')' . '<b>'.$address.'</b>'.'('.$idleStartTime.')('.secondsToTimeFormate($diff).')'."<br>";
                         // echo " eee " . $address . " eeee " . $diff .'  '.$fromTime.'   '.$toTime ."<br>";
                        ; 
                         
                         $diff=0;
                         $fromTime=0;
                         $address='';
                         $toTime=0;
                   }
             } // handle idle break event
             if ($indx>0)
                  $perDayDistanceTravelled = $perDayDistanceTravelled + $data[$indx][7];
        } // idle, end of day for loop 
 
         $slatlong=round($data[0][5],5).'-'.round($data[0][6],5);
         $elatlong=round($data[count($data)-1][5],5).'-'.round($data[count($data)-1][6],5);
         $tripUrl=$abspath."?accountID=".$accountID."&vehicleID=".$deviceArrya[$devindx][4].'&departTime='.$toDate.'&platEntryTime='.$fromDate."&name=".$deviceArrya[$devindx][7]."&travelDistance=".round($totalDistanceTravelled,2)."&destination=".str_replace(" ","-",$data[count($data)-1][2])."&factory=".str_replace(" ","-",$data[0][2])."&slatlong=".$slatlong."&endlatlong=".$elatlong."&idlelat=".$allidlelatpervehilce."&idlelong=".$allidlelongpervehilce."&onlyidles=yes";
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
     //  $sumdays='';
       $sumdis='';
       for($dindx=$fromDate;$dindx<$toDate;){
//            $sumdays=$sumdays."<th>".$days[$dindx]."</th>";
   
            $sumdis=$sumdis."<td>".$daywiseDistance[date('d-m-Y',$dindx)]."</td>";
            $dindx=$dindx+86400;

       }
        if($index>0 && $deviceID != "selectall"){
            $dis11 = explode("]",$deviceArrya[$devindx][10]);
          if(strpos($accountID,"gvk")>-1 || strpos($accountID,"gog")>-1){
               if($croneRequest!="yes"){
                  $table=$table."<tr  bgcolor='black' style='color:white'><th colspan='6'>Total Travelled Distance</th><th>".round($totalDistanceTravelled,2)." Kms</th><th>".secondsToTimeFormate($totalofflinetime)."</th><th><a href=".$tripUrl." target='_blank'>Route</a></th></tr>";
               $summarydata=$summarydata."<tr><td>".(++$summaryIndx)."</td><td>".trim($dis11[2],'[')."</td><td>".trim($dis11[1],'[')."</td><td>".$deviceArrya[$devindx][4]."</td><td>".$mobileNumber."</td><td>".$startDateforSummary."</td><td>".$endDateforSummary."</td>".$sumdis."<td>".round($totalDistanceTravelled,2)." Km</td><td>".$currentlatlong."</td><td>Glovision</td><td>".$vehicleStatus."</td><td>".secondsToTimeFormate($totalofflinetime)."</td></tr>";
                }else{

                         $summarydata=$summarydata."<tr><td>".(++$summaryIndx)."</td><td>".trim($dis11[2],'[')."</td><td>".trim($dis11[1],'[')."</td><td>".$deviceArrya[$devindx][4]."</td><td>".$mobileNumber."</td><td>".round($totalDistanceTravelled,2)." Km</td><td>".$currentlatlong."</td><td>Glovision</td><td>".$vehicleStatus."</td></tr>";


                }
           }else{
              if($croneRequest!="yes"){
                   $table=$table."<tr><th colspan='6'  bgcolor='black' style='color:white'>Total Travelled Distance</th><th>".round($totalDistanceTravelled,2)." Kms</th><th>".secondsToTimeFormate($totalofflinetime)."</th><th><a href=".$tripUrl." target='_blank'>Route</a></th></tr>";
              $summarydata=$summarydata."<tr><td>".(++$summaryIndx)."</td><td>".$deviceArrya[$devindx][8]."</td><td>".trim($dis11[1],'[').' - '.$deviceArrya[$devindx][4]."</td><td>".$mobileNumber."</td><td>".$startDateforSummary."</td><td>".$endDateforSummary."</td><td>".round($totalDistanceTravelled,2)." Km</td></tr>";
                }else{
                      $summarydata=$summarydata."<tr><td>".(++$summaryIndx)."</td><td>".$deviceArrya[$devindx][8]."</td><td>".trim($dis11[1],'[').' - '.$deviceArrya[$devindx][4]."</td><td>".$mobileNumber."</td><td>".round($totalDistanceTravelled,2)." Km</td></tr>";

 
                }
           }
        }
        if($index>0 && $deviceID == "selectall"){
              $dis11 = explode("]",$deviceArrya[$devindx][10]);
             if(strpos($accountID,"gvk")>-1 || strpos($accountID,"gog")>-1){
                   if($croneRequest!="yes"){
                         $table=$table."<tr bgcolor='black' style='color:white'><th colspan='7'>Total Travelled Distance</th><th>".round($totalDistanceTravelled,2)." Kms</th><th>".secondsToTimeFormate($totalofflinetime)."</th><th><a href=".$tripUrl." target='_blank'>Route</a></th></tr>";
                   $summarydata=$summarydata."<tr><td>".(++$summaryIndx)."</td><td>".trim($dis11[2],'[')."</td><td>".trim($dis11[1],'[')."</td><td>".$deviceArrya[$devindx][4]."</td><td>".$mobileNumber."</td><td>".$startDateforSummary."</td><td>".$endDateforSummary."</td>".$sumdis."<td>".round($totalDistanceTravelled,2)." Km</td><td>".$currentlatlong."</td><td>Glovision</td><td>".$vehicleStatus."</td><td>".secondsToTimeFormate($totalofflinetime)."</td></tr>";
                   }else{

                       $summarydata=$summarydata."<tr><td>".(++$summaryIndx)."</td><td>".trim($dis11[2],'[')."</td><td>".trim($dis11[1],'[')."</td><td>".$deviceArrya[$devindx][4]."</td><td>".$mobileNumber."</td><td>".round($totalDistanceTravelled,2)." Km</td><td>".$currentlatlong."</td><td>Glovision</td><td>".$vehicleStatus."</td></tr>";


                   }
                   
              }else{
                 if($croneRequest!="yes"){
                      $table=$table."<tr bgcolor='black' style='color:white'><th colspan='7'>Total Travelled Distance</th><th>".round($totalDistanceTravelled,2)." Kms</th><th><th>".secondsToTimeFormate($totalofflinetime)."</th><a href=".$tripUrl." target='_blank'>Route</a></th></tr>";
               $summarydata=$summarydata."<tr><td>".(++$summaryIndx)."</td><td>".$deviceArrya[$devindx][8]."</td><td>".trim($dis11[1],'[').' - '.$deviceArrya[$devindx][4]."</td><td>".$mobileNumber."</td><td>".$startDateforSummary."</td><td>".$endDateforSummary."</td><td>".round($totalDistanceTravelled,2)." Km</td></tr>";
                  }else{

                       $summarydata=$summarydata."<tr><td>".(++$summaryIndx)."</td><td>".$deviceArrya[$devindx][8]."</td><td>".trim($dis11[1],'[').' - '.$deviceArrya[$devindx][4]."</td><td>".$mobileNumber."</td><td>".round($totalDistanceTravelled,2)." Km</td></tr>";


                 }
            }
             
        }
        if($index==0){
             $sumdis="";
             for($dindx=$fromDate;$dindx<$toDate;){
//            $sumdays=$sumdays."<th>".$days[$dindx]."</th>";
                     $sumdis=$sumdis."<td></td>";
                   $dindx=$dindx+86400;

               }


           $dis11 = explode("]",$deviceArrya[$devindx][10]);
          if(strpos($accountID,"gvk")>-1 || strpos($accountID,"gog")>-1){

               if($croneRequest!="yes"){
                   $summarydata=$summarydata."<tr><td>".(++$summaryIndx)."</td><td>".trim($dis11[2],'[')."</td><td>".trim($dis11[1],'[')."</td><td>".$deviceArrya[$devindx][4]."</td><td>".$mobileNumber."</td><td>-----</td><td>------</td>".$sumdis."<td>------</td><td>".$currentlatlong."</td><td>Glovision</td><td>".$vehicleStatus."</td><td>".secondsToTimeFormate($totalofflinetime)."</td></tr>";
               }else{
                    $summarydata=$summarydata."<tr><td>".(++$summaryIndx)."</td><td>".trim($dis11[2],'[')."</td><td>".trim($dis11[1],'[')."</td><td>".$deviceArrya[$devindx][4]."</td><td>".$mobileNumber."</td><td>------</td><td>".$currentlatlong."</td><td>Glovision</td><td>".$vehicleStatus."</td></tr>";


                }
           }else{
               if($croneRequest!="yes"){
                 $summarydata=$summarydata."<tr><td>".(++$summaryIndx)."</td><td>".$deviceArrya[$devindx][8]."</td><td>".trim($dis11[1],'[').' - '.$deviceArrya[$devindx][4]."</td><td>".$mobileNumber."</td><td>-----</td><td>------</td><td>------</td></tr>";
                }else{

                   $summarydata=$summarydata."<tr><td>".(++$summaryIndx)."</td><td>".$deviceArrya[$devindx][8]."</td><td>".trim($dis11[1],'[').' - '.$deviceArrya[$devindx][4]."</td><td>".$mobileNumber."</td><td>------</td></tr>";

                 }
            }
   
        }
       
    } //end divice loop
  
for($dindx=$fromDate;$dindx<$toDate;){
        $sumdays=$sumdays."<th>".date('d/m',$dindx)."</th>";
       $dindx=$dindx+86400;

       }

    if($reportType=="summary"){
         if($croneRequest!="yes"){
         $table=$summarydata1.$sumdays."<th>Total Distance</th><th>Lat/Long</th><th>Operator</th><th>Status</th><th>Offline Time</th></thead><tbody>".$summarydata."<tr></tr><tr><td colspan='11'>*Off-road:Vehicle not Communicating from more than 12 hours *Not Responding:Never communicated </td></tr></tbody></table>
    </body>
    </html>";
        }else{
                 $table=$summarydata1.$summarydata."<tr></tr><tr><td colspan='11'>*Off-road:Vehicle not Communicating from more than 12 hours *Not Responding:Never communicated </td></tr></tbody></table>
    </body>
    </html>";

       }

    }else{
         if($croneRequest!="yes"){

       $table=$summarydata1.$sumdays."<th>Total Distance</th><th>Lat/Long</th><th>Operator</th><th>Status</th><th>Offline Time</th></thead><tbody>".$summarydata."<tr></tr><tr><td colspan='11'>*Off-road:Vehicle not Communicating from more than 12 hours *Not Responding:Never communicated </td></tr></tbody></table>".$table."<tr><td colspan='6'></td></tr></tbody></table>
    </body>
    </html>";
        }else{

            $table=$summarydata1.$summarydata."<tr></tr><tr><td colspan='11'>*Off-road:Vehicle not Communicating from more than 12 hours *Not Responding:Never communicated </td></tr></tbody></table>".$table."<tr><td colspan='6'></td></tr></tbody></table>
    </body>
     </html>";


         }

 
    }
    mysqli_close($gtsconnect);

    if($croneRequest=="yes"){
        $mailperAccount=array();
       
     // $mailperAccount[0]="ramana@glovision.co";        
    $mailperAccount=getMails($tacserver,$tacusername,$tacpassword,$tacdb,$accountID,'distanceidlereport',$group);
     // echo $table;
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
 
   $result=array(array());
   $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database2: " . mysqli_error());
   mysqli_select_db($gtsconnect, $gtsdb);
  // $vehicleDetails="select vehicleModel,vehicleMake,lastValidLatitude,lastValidLongitude,deviceID from Device where accountID='$accounts'";
   $vehicleDetails="select a.vehicleModel as vehicleModel,a.vehicleMake as vehicleMake,a.lastValidLatitude as lastValidLatitude,a.lastValidLongitude as lastValidLongitude,a.deviceID as deviceID,a.imeiNumber as imeinumber,	lastEventTimestamp,a.simPhoneNumber as simPhoneNumber,b.groupID as groupID,a.simPhoneNumber as contactPhone,a.description as description  from Device a,DeviceList b where a.accountID='$accounts' and b.groupID='$userID' and b.accountID='$accounts' and a.deviceID=b.deviceID ";
   if($userID=="admin" || $userID=="administrator" || $userID=="selectall"){
   $vehicleDetails="select a.vehicleModel as vehicleModel,a.vehicleMake as vehicleMake,a.lastValidLatitude as lastValidLatitude,a.lastValidLongitude as lastValidLongitude,a.deviceID as deviceID,a.imeiNumber as imeinumber,	lastEventTimestamp,a.simPhoneNumber as simPhoneNumber,b.groupID as groupID,a.simPhoneNumber as contactPhone,a.description as description  from Device a,DeviceList b where a.accountID='$accounts' and b.accountID='$accounts' and a.deviceID=b.deviceID ";
   }
   if($vehicleID!="selectall"){
      $vehicleDetails="select a.vehicleModel as vehicleModel,a.vehicleMake as vehicleMake,a.lastValidLatitude as lastValidLatitude,a.lastValidLongitude as lastValidLongitude,a.deviceID as deviceID,a.imeiNumber as imeinumber,	lastEventTimestamp,a.simPhoneNumber as simPhoneNumber,b.groupID as groupID,a.simPhoneNumber as contactPhone,a.description as description from Device a,DeviceList b where a.accountID='$accounts' and b.groupID='$userID' and b.accountID='$accounts' and a.deviceID=b.deviceID and b.deviceID='$vehicleID' and a.deviceID='$vehicleID' ";
   } 
 
    if(strpos($accounts,"gvk")>-1 || strpos($accounts,"gog")>-1){
      $vehicleDetails="select a.vehicleModel as vehicleModel,a.vehicleMake as vehicleMake,a.lastValidLatitude as lastValidLatitude,a.lastValidLongitude as lastValidLongitude,a.deviceID as deviceID,a.imeiNumber as imeinumber,	lastEventTimestamp,a.simPhoneNumber as simPhoneNumber,b.groupID as groupID,c.contactPhone as contactPhone,a.description as description  from Device a,DeviceList b,Driver c where a.accountID='$accounts' and b.groupID='$userID' and b.accountID='$accounts' and a.deviceID=b.deviceID and a.deviceID=c.deviceID and c.accountID=a.accountID";
   if($userID=="admin" || $userID=="administrator" || $userID=="selectall"){
   $vehicleDetails="select a.vehicleModel as vehicleModel,a.vehicleMake as vehicleMake,a.lastValidLatitude as lastValidLatitude,a.lastValidLongitude as lastValidLongitude,a.deviceID as deviceID,a.imeiNumber as imeinumber,	lastEventTimestamp,a.simPhoneNumber as simPhoneNumber,b.groupID as groupID,c.contactPhone as contactPhone,a.description as description  from Device a,DeviceList b,Driver c where a.accountID='$accounts' and b.accountID='$accounts' and a.deviceID=b.deviceID and a.deviceID=c.deviceID and c.accountID=a.accountID";
   }
   if($vehicleID!="selectall"){
      $vehicleDetails="select a.vehicleModel as vehicleModel,a.vehicleMake as vehicleMake,a.lastValidLatitude as lastValidLatitude,a.lastValidLongitude as lastValidLongitude,a.deviceID as deviceID,a.imeiNumber as imeinumber,	lastEventTimestamp,a.simPhoneNumber as simPhoneNumber,b.groupID as groupID,c.contactPhone as contactPhone,a.description as description from Device a,DeviceList b,Driver c where a.accountID='$accounts' and b.groupID='$userID' and b.accountID='$accounts' and a.deviceID=b.deviceID and b.deviceID='$vehicleID' and a.deviceID='$vehicleID' and a.deviceID=c.deviceID and c.accountID=a.accountID ";
   } 
      
    }
   
  
   
   error_log($vehicleDetails);
   $qry1 = mysqli_query($gtsconnect, $vehicleDetails) or die(mysqli_error());
   $rowcount=0;
   error_log('Devicessssss');
   while($row = mysqli_fetch_assoc($qry1)){
        $result[$rowcount][0]=$row['vehicleMake'];
        $result[$rowcount][1]=$row['vehicleModel'];
        $result[$rowcount][2]=$row['lastValidLatitude'];
        $result[$rowcount][3]=$row['lastValidLongitude'];
        $result[$rowcount][4]=$row['deviceID'];
         $result[$rowcount][5]=$row['imeinumber'];
        $result[$rowcount][6]=$row['lastEventTimestamp'];
        $result[$rowcount][7]=$row['simPhoneNumber'];
        $result[$rowcount][8]=$row['groupID'];
        $result[$rowcount][9]=$row['contactPhone'];
         $result[$rowcount][10]=$row['description'];
      // echo $result[$rowcount][4].'  '. $result[$rowcount][7].'<br>';
        $rowcount++;
        
   }
   mysqli_close($gtsconnect);
   return $result;
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

//    $from = " Glovision <support@glovision.co>";
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

function getMails($tacserver,$tacusername,$tacpassword,$tacdb,$accountId,$desc,$group){
$tacconnect = mysqli_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database3: " . mysqli_error());
mysqli_select_db($tacconnect, $tacdb);

//$query1 = "SELECT DISTINCT emailaddress FROM email WHERE accountID ='$accountId' and description='$desc' and groupID='$group'";
$query1 = "SELECT DISTINCT emailaddress FROM email WHERE accountID ='$accountId'";

error_log($query1);
$qry_result = mysqli_query($tacconnect, $query1) or die(mysqli_error());

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
$tacconnect = mysqli_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database4: " . mysqli_error());
mysqli_select_db($tacconnect, $tacdb);
$epochtimestamp=strtotime($timeStamp);
$uniqueID=$epochtimestamp.$deviceID;
//$endLocation=trim($endLocation,"'");
//$startLocation=trim($startLocation,"'");
$endLocation=str_replace("'","",$endLocation);
$startLocation=str_replace("'","",$startLocation);
$idleLocations=str_replace("'","",$idleLocations);
$route=str_replace("'","",$route);
$query1 = "INSERT INTO distanceIdleReport (accountID, deviceID, groupID, baseLocation, crewNumber, idleLocations, idleTime, startTime, startLocation, travelledDistance, timeStamp, endTime, endLocation, route,District,uniqueID) VALUES ('$accountID', '$deviceID', '$groupID', '$baseLocation', '$crewNumber', '$idleLocations', '$idleTime', '$startTime', '$startLocation', '$travelledDistance', '$epochtimestamp', '$endTime', '$endLocation','$route','$District','$uniqueID') ON DUPLICATE KEY UPDATE  idleLocations='$idleLocations' , idleTime='$idleTime' , startTime='$startTime' , startLocation='$startLocation' , travelledDistance='$travelledDistance' ,endTime='$endTime' ,endLocation='$endLocation' , route='$route' ";

error_log($query1);
$qry_result = mysqli_query($tacconnect, $query1) or die(mysqli_error());

}

function geozoneList(){
 global $gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$geozoneList;
$gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database5: " . mysqli_error());
    mysqli_select_db($gtsconnect, $gtsdb);

    $query = "SELECT geozoneID,radius,latitude1,longitude1,groupID,description,radians(latitude1) as lat1,radians(longitude1) as long1 FROM Geozone where accountID='$accountID' ";

    $qry_result = mysqli_query($gtsconnect, $query) or die(mysqli_error());
    
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


function GetDrivingDistance($lat1, $lat2, $long1, $long2)
{
    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?lic_key=15vujowanzfaasohwrm3c9qlctjgitsi&origins=".$lat1.",".$long1."&destinations=".$lat2.",".$long2."&mode=driving&language=pl-PL";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);
    curl_close($ch);
   //  echo $response;
    $response_a = json_decode($response, true);
    $dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
    $time = $response_a['rows'][0]['elements'][0]['duration']['text'];

    return  $dist;
}
?>
