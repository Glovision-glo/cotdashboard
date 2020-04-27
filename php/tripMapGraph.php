<?php
date_default_timezone_set('Asia/Kolkata');
/*
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/usr/local/apache/logs/error_log.txt');
error_reporting(E_ALL);
error_reporting(0);
*/
require_once('config.php');
$now=time();
$diff=abs($fromDate-$now);
error_log($fromDate.'fromdate'.$now.'-now');
$diffindays = floor(($diff)/86400);
error_log($diffindays.'diff');
/*
if($diffindays>15){
$gtsserver ="localhost";
$gtsusername ="root";
$gtspassword="gl0v1s10n";
$gtsdb ="gtshealth";
}*/
error_log($gtsserver.'-'.$gtsusername);



session_start();
$idlelat=$_GET['idlelat'];
$idlelong=$_GET['idlelong'];
$displayonlyIdles=$_GET['onlyidles'];
$startlatlong=$_GET['slatlong'];
$name=$_GET['name'];
$endlatlong=$_GET['endlatlong'];
$startindx=$_GET['startindx'];
$endIndx=$_GET['endIndx'];
$departTime=$_GET['fromdate'];
$sapDis=$_GET['sapDis'];
$gtsDis=$_GET['gtsDis'];
$lat=$_GET['lat'];
$long=$_GET['long'];
$vehicleID=$_GET['vehicleID'];
$accountID=$_GET['accountID'];
//$groupID=$_GET['groupID'];
$travelDistance='';//$_GET['travelDistance'].' Kms';
$latitude = $_GET['lat'];
    $longitude = $_GET['long'];
 $idle = $_GET['idle'];
$waitTime=TimeFormate($_GET['waitTime']);
$WaitLocation=$_GET['WaitLocation'];
$platEntryTime=$_GET['todate'];
//$platEntryTime=$platEntryTime-19800;
//$waitTime=$waitTime-19800;
echo "<center><img src='../images/ajax-loader-original.gif' id='loadimg'/> </center>";
$stoppage=$_GET['stoppage'];
     $timeinterval=$_GET['timeinterval'];
     $overspeed=$_GET['overspeed'];
     $ignition=$_GET['ignition'];
     $mainpower=$_GET['mainpower'];

error_log('entry timee  '.date('d-m-Y h:i:s a',$platEntryTime).' depart tiem'.date('d-m-Y h:i:s a',$departTime));
//error_log($vehicleID.'vechiollllllllllllllllllllll');
 //$nearestGeozoneID=$_SESSION['nearZone'];
$nearestGeozoneID=$_GET['nearZone'];
session_start(); 
$eventData=array(array());

//getEventData($vehicleID,$accountID,$departTime,$platEntryTime);
//echo strtotime($platEntryTime).'   '.strtotime($departTime).'  '.$_GET['request'];

         $toDayDate=strtotime(date('d-M-Y',time()));
         $d=date('d',$toDayDate);
         $m=date('m',$toDayDate);
         $y=date('Y',$toDayDate);
 //error_log('dddddddddddd '.date("d-m-Y h:i:s a", mktime($time[0],$time[1],$time[2],$m,$d,$y)));   
         $fromDate=strtotime(date("d-m-Y h:i:s a", mktime(0,0,0,$m,$d,$y)));


$fdate=date("d-M-Y H:i:s", mktime(0,0,0,$m,$d,$y));
$tdate=date("d-M-Y H:i:s",time());
$islive=$_GET['live'];
$numrows=0;

if($_GET['platEntryTime']!=""){
    $fromDate=$_GET['platEntryTime'];
    $toDate=$_GET['departTime'];
    $fdate=date('d-M-Y H:i:s',$fromDate);
      $tdate=date('d-M-Y H:i:s',$toDate);


}
if($_GET['request']=="itself"){
  $fdate=$departTime;
  $tdate=$platEntryTime;
  if(is_numeric($fdate)){
      $fdate=date('d-M-Y H:i:s',$departTime);
      $tdate=date('d-M-Y H:i:s',$platEntryTime);
  }
  
  getEventData($vehicleID,$accountID,(is_numeric($platEntryTime)?$platEntryTime:strtotime($platEntryTime)),(is_numeric($departTime)?$departTime:strtotime($departTime)));

}else if($islive=="yes"){
          $conn=mysqli_connect("track1.glovision.co", "root", "gl0v1s10n") or die ("Unable to connect to the database: " . mysqli_error($conn));
           mysqli_select_db($conn,"autocare_gts");
           $update="select caseCloseTime,caseTimestamp from caseManager  where deviceID='".$vehicleID."' order by caseTimestamp limit 1";
           $result= mysqli_query($conn,$update) or die(mysqli_error($conn));
          //echo $update;
            $numrows=mysqli_num_rows ($result);
            $toDate=0;
           while($row = mysqli_fetch_assoc($result) ){
                 $fromDate=$row['caseTimestamp'];
                 $toDate=$row['caseCloseTime'];
           }
           if($toDate==0 || $toDate==""){
                  $toDate=time();

           }      
          //  echo $fromDate.'   '.$toDate;
           mysqli_close($conn);
 
          if($numrows>0){
              getEventData($vehicleID,$accountID,$toDate,$fromDate);
          }
}
else{
 // getEventData($vehicleID,$accountID,time(),$fromDate);
  if($_GET['platEntryTime']!=""){

   }else{ $toDate=time();}
  getEventData($vehicleID,$accountID,$toDate,$fromDate);

}
//$eventData=$_SESSION[$vehicleID];
//error_log('errror count'.$evemtData[$startindx][3]);
$data='';
$indx=0;
//$departTime=date('Y-m-d H:i:s',$departTime);

error_log('evnt count'.count($eventData));
$startOdo=$eventData[0][4];
$endOdo=$eventData[count($eventData)-1][4];
$distanceTravelled=intval($endOdo-$startOdo);
for($i=0;$i<count($eventData);$i++){
  //error_log('date'.$departTime);
  //if(strtotime($eventData[$i][3])>$departTime){
       $data .=$eventData[$i][0].'^'.$eventData[$i][3].'^'.$eventData[$i][8].'^'.$eventData[$i][9].'^'.$eventData[$i][10].'^'.$eventData[$i][4].'^'.$eventData[$i][5].'^'.$eventData[$i][11].'^'.$eventData[$i][12].'^'.$eventData[$i][2].'^'.$eventData[$i][6].'^'.$eventData[$i][13].'^'.$eventData[$i][14].'^'.$eventData[$i][15].'^*';

     // $data .=$eventData[$i][0].'^'.$eventData[$i][3].'^'.$eventData[$i][8].'^'.$eventData[$i][9].'^'.$eventData[$i][10].'^'.$eventData[$i][4].'^'.$eventData[$i][11].'^'.'*';
   //}
}
//error_log($data.'    '.count($eventData));
  $geozones =array(array());
  getZones();
$zones="";
for($i=0;$i<count($geozones);$i++){
  //error_log($geozones[$i][0].'^'.$geozones[$i][1].'^'.$geozones[$i][2].'^'.'*');
  $zones .=$geozones[$i][0].'^'.$geozones[$i][1].'^'.$geozones[$i][2].'^'.$geozones[$i][3].'^'.'*';
 
}
//$factory=str_replace("-"," ",$_GET['factory']);; 
//$destination=str_replace("-"," ",$_GET['destination']);;
//error_log($startindx.' :start '.$endIndx.' end indx'.' count'.$data);
function TimeFormate($timeDiff){
	$hours=0;
	$min=0;
	$sec=0;
        if ($timeDiff==0) {
            return 0;
        }
	if($timeDiff>=3600){
		$hours=intval($timeDiff/(60*60));
		$tempmin=$timeDiff%(60*60);
		$result=TimeFormate($tempmin);
		return $hours."Hr :".$result;
	}else if($timeDiff>=60){
		 $min=intval($timeDiff/60);
		 $tempsec=$timeDiff%60;
		 
		 return $min." Min :".TimeFormate($tempsec);
	}else{
	     return $timeDiff." sec";	
	}
	
}

function getEventData($vehicleID,$accountID,$fromDate,$toDate){
       global $gtsserver, $gtsusername, $gtspassword,$gtsdb,$eventData,$startlatlong,$endlatlong,$travelDistance,$destination,$factory,$stoppage,$timeinterval,$overspeed,$ignition,$mainpower;
      
      $Query = " SELECT heading, geozoneID,timestamp,deviceID,FROM_UNIXTIME(timestamp) as 'time1',speedKPH,address,odometerKM+odometerOffsetKM as 'distance',statusCode,analog0,latitude,longitude FROM `EventData` where deviceID='$vehicleID' and accountID='$accountID' and  timestamp between '$toDate' and '$fromDate' and latitude<>0 and longitude<>0  order by timestamp asc ";  //request from web for particular vehicle
  
       $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($gtsconnect));
        if(date('d-M-Y',$fromDate)==date('d-M-Y') || date('d-M-Y',$toDate)==date('d-M-Y')){
                  mysqli_select_db($gtsconnect,'gts');

         }else{
        //mysqli_select_db($gtsconnect,'gtshealth');
        mysqli_select_db($gtsconnect,'gts');

         }

//       mysqli_select_db($gtsdb, $gtsconnect);
       error_log($Query);
       $rs =mysqli_query($gtsconnect,$Query) or die ("Query error1: " . mysqli_error($gtsconnect));
       $num_rows = mysqli_num_rows($rs);
       $i=0;
       $oldtime=0; 
       $oldtime=0;
         $startTime=0;
         $endTime=0;
         $idleStart='no';
       while($row = mysqli_fetch_assoc($rs)) {
          // if($row['statusCode']=='62000' ||  $row['statusCode']=='61968'){ error_log($row['geozoneID']." ".$row['statusCode'].'  '.$row['time1']);}
       /*   if($i>=2){ //delete drift event compare 1 to second and first to third.
                 $dis2to1=distance1($eventData[$i-2][8],$eventData[$i-2][9], $eventData[$i-1][8], $eventData[$i-1][9]);
                 $dis1topresent=distance1($row['latitude'],$row['longitude'], $eventData[$i-2][8], $eventData[$i-2][9]);
                if($dis2to1>$dis1topresent){
                   $i--;
                } 

          }*/
          if($i==0){$startlatlong=round($row['latitude'],5).'-'.round($row['longitude'],5);
              $factory=$row['address'];

         }      

            $statusinfo="Stopped";
            if($row['statusCode']=="61714"){
                $statusinfo="Moving";
            }else if($row['statusCode']=="61718"){
                $statusinfo="Idle";
            }else if($row['statusCode']=="62467"){
                $statusinfo="Ignition Off";
            }else if($row['statusCode']=="62465"){
                $statusinfo="Ignition On";
            }else if($row['statusCode']=="64791"){
                 $statusinfo="Main Power OFF";

            }else if($row['statusCode']=="64793"){
                 $statusinfo="Main Power ON";
            }
            $eventData[$i][13]='';
            $eventData[$i][14]='';
           //ignition on/off
           if($ignition=="yes" &&($statusinfo=="Ignition Off" || $statusinfo=="Ignition On") ){ 
               $eventData[$i][13]=$statusinfo;
               $eventData[$i][14]='Time:'.$row['time1']."<br>Ignition OFF<br> Km since start:".intval($travelDistance);        
           }//main power off/on
           if($mainpower=="yes" && ($statusinfo=="Main Power OFF" || $statusinfo=="Main Power ON")){
               $eventData[$i][13]=$statusinfo;
               $eventData[$i][14]='Time:'.$row['time1']."<br>Main power<br>Km since start:".intval($travelDistance);
           }//overspeed event
           if($row['speedKPH']>$overspeed && $overspeed!=0){
               $eventData[$i][13]="Over Speed";
               $eventData[$i][14]='Time:'.$row['time1']."<br>Over Speed".$row['speedKPH']." KPH<br>Km since start:".intval($travelDistance);;
           }
          //interval ivent
           if($oldtime==0){
                 $oldtime=$row['timestamp'];
            }
            $difftimeinterval=(abs($row['timestamp']-$oldtime)/(60*60));
            if($timeinterval<=$difftimeinterval && $timeinterval!=0){
                $eventData[$i][13]="Time Interval";
                $eventData[$i][14]='Time:'.$row['time1']."<br>".$timeinterval." hours event<br>Km since start:".intval($travelDistance);;;
                $oldtime=$row['timestamp'];
            }
            //calculating stoppage 
            if( $idleStart=="no" && $statusinfo!="Moving"){
                 $idleStart="yes";
                 $startTime=$row['timestamp'];
             }
             if($idleStart=="yes" && $statusinfo=="Moving"){
                 $idleStart="no";
                 $endTime=$row['timestamp'];
                 $dis=abs($endTime-$startTime);
                 $disdump=$dis;

                 if(($dis/60)>=$stoppage && $stoppage!=0){
                     $eventData[$i][13]="Idle Point";
                     $eventData[$i][14]='Time:'.$row['time1']."<br>Idle Time for:".secondsToTimeFormate($dis)."<br> Km since start:".intval($travelDistance);
                 }
             }
          

           $eventData[$i][0]=$row['geozoneID'];
           $eventData[$i][1]=strtoupper($row['deviceID']);
           $eventData[$i][2]=$row['statusCode'];
           $eventData[$i][3]=$row['time1'];
           $eventData[$i][4]=$row['distance'];
           $eventData[$i][5]=$row['speedKPH'];
           $eventData[$i][6]=$row['address'];
           $eventData[$i][7]=$row['analog0'];
           $eventData[$i][8]=$row['latitude'];
           $eventData[$i][9]=$row['longitude'];
           $eventData[$i][10]=$row['timestamp'];
           $eventData[$i][11]=findDirection($row['heading']);
           $eventData[$i][12]=$row['heading'];
           $endlatlong=round($row['latitude'],5).'-'.round($row['longitude'],5);
           $destination=$row['address'];
          

           if($i>0 && $row['latitude']!= 0 && $row['longitude']!=0 && $eventData[$i-1][8]!=0 && $eventData[$i-1][9]!=0) {

                 $eventtoeventdistance=distance1($row['latitude'],$row['longitude'], $eventData[$i-1][8], $eventData[$i-1][9]);
                 $difftimes=abs(strtotime($eventData[$i][3])-strtotime($eventData[$i-1][3]));
                 $eventData[$i][15]=secondsToTimeFormate(abs(strtotime($eventData[$i][3])-strtotime($eventData[$i-1][3])));
                 $maxDistanceTravelledperMint=2;//max km travelled per mint
                 $calculatedDistenceTravelledperMint=intval($eventtoeventdistance)/$difftimes;
                 if(is_nan($eventtoeventdistance) || ($eventtoeventdistance/ $difftimes>0.025)){//if distnace greater than 2 km with 5 minits then distnace=0 bzc drift point in 
                    // $eventData[$i][7]=0;
                      $eventData[$i][4]=0;
                   //  $i++;
                }else{
                   $eventData[$i][4]=$eventtoeventdistance;
                   $travelDistance=$travelDistance+$eventtoeventdistance;
                     $i++;
               }
          }
         if($i==0){$i++;}
            
       }
       mysqli_close($gtsconnect);
}

function findDirection($degree){
  if($degree>=0 && $degree<10){return 'h0';}
  else if($degree>10 && $degree<80){return 'h1';}

  else if($degree>=80 && $degree<100){return 'h2';}
  else if($degree>=100 && $degree<170){return 'h3';}
  else if($degree>=170 && $degree<190){return 'h4';}
  else if($degree>=190 && $degree<260){return 'h5';}
  else if($degree>=260 && $degree<280){return 'h6';}
  else if($degree>=280 && $degree<350){return 'h7';}
  else if($degree>=350 && $degree<360){return 'h0';}



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
function getZones(){
       global $gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$geozones;
       $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($gtsconnect));
       mysqli_select_db($gtsconnect,'gts');
       $query = "SELECT geozoneID,latitude1,longitude1,radius from Geozone where accountID='$accountID'";
           error_log($query);
       $qry_result = mysqli_query($gtsconnect,$query) or die(mysqli_error($gtsconnect));
       $i=0;
       while($row = mysqli_fetch_assoc($qry_result))
       {
         //error_log($row['geozoneID'].'  '.$row['latitude1'].'   '.$row['longitude1'].' '.$row['radius']);
           $geozones[$i][0]= $row['geozoneID'];
           $geozones[$i][1]= $row['latitude1'];
           $geozones[$i][2]= $row['longitude1'];
           $geozones[$i][3]= $row['radius'];
           
           $i++;
       }
       mysqli_close($gtsconnect);
       

   }
function GetBaseLocationAndGroupID($vehicleID){
       $gtsconnect = mysqli_connect('gj.glovision.co', 'root', 'gl0v1s10n') or die ("Unable to connect to the database: " . mysqli_error  ($gtsconnect));
       $Query ="SELECT a.description as description,b.groupID as groupID from Device a,DeviceList b where a.deviceID='$vehicleID' and b.deviceID='$vehicleID'"; 
       mysqli_select_db($gtsconnect,'gts');
       $result = mysqli_query($gtsconnect,$Query) or die(mysqli_error($gtsconnect));
       //$numrows=mysqli_num_rows ($result);
      // echo $numrows;
       $barr=array();
           while($row = mysqli_fetch_assoc($result)){
             
                $barr[1]=$row['groupID'];
                $dis11 =explode("]",$row['description']);
                $vehiclebaselocation=trim($dis11[1],'[');
                $barr[0]=$vehiclebaselocation;
           }

          mysqli_close($gtsconnect);
      return $barr;
     }
$barr=array();
$barr=GetBaseLocationAndGroupID($vehicleID);
$groupID=$barr[1];
$baseloc=$barr[0];

//echo $startlatlong.'ramana';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<!-- <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"> -->
<!-- <!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> -->
<!-- <html xmlns='http://www.w3.org/1999/xhtml' xmlns:v='urn:schemas-microsoft-com:vml'>-->
<!-- custom/loginSession_cbanner.jsp: E2.5.3-B03 [default] page=login
  =======================================================================================
  Copyright(C) 2007-2013 Glovision Techno Services, All rights reserved
  Centered image banner
  =======================================================================================
-->
<!-- meta -->
<html>
   <head>
       <meta name="author" content="Glovision Techno Services"/>
       <meta http-equiv="content-type" content='text/html; charset=UTF-8'/>
       <link rel="shortcut icon" href="../../../images/glovision.ico"/>
       <title>Glovision Techno Services</title>
   <script src="https://maps.googleapis.com/maps/api/js?libraries=drawing&client=gme-gvkemergencymanagement3&channel=<?php echo $accountID."-glovision";?>"></script>
<!--
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&v=3&libraries=geometry"></script>       -->
<!--     <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=drawing"></script> -->
   <script type="hacker/JavaScript" hacker="enabled"></script>
 <script type="text/javascript" src="../js/markerlabel.js"></script>
<script src="../js/jquery-1.11.1.min.js"></script>
<script src="../js/datePicker1.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../js/jquery.datetimepicker.css"/>
<link rel="stylesheet" type="text/css" href="../css/fuelbar.css"/>
 <script type="text/javascript" src="../js/tac.js"></script>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="../js/jquery.datetimepicker.js" type="text/javascript"></script>

 <style>
 table#rawdatareport tr:nth-child(even) {
    background-color: #eee;
}
table#rawdatareport tr:nth-child(odd) {
   background-color:#fff;
}
table#rawdatareport th {
    background-color: black;
    color: white;
}
.labels {
      color:  black ;
           background-color:white;
                     font-size: 10px;
                    font-weight: bold;
                               text-align: center;
                                border-radius:10px;
                                   /* width: 70px;
                                    height:20px;*/
                                         border: 1px solid  red;
                                             /*word-break: break-all;*/
                                              white-space: nowrap;
                                                 }


 </style>
       <script>
(function(){"use strict";var t=this,i=t.Chart,e=function(t){this.canvas=t.canvas,this.ctx=t;this.width=t.canvas.width,this.height=t.canvas.height;return this.aspectRatio=this.width/this.height,s.retinaScale(this),this};e.defaults={global:{animation:!0,animationSteps:60,animationEasing:"easeOutQuart",showScale:!0,scaleOverride:!1,scaleSteps:null,scaleStepWidth:null,scaleStartValue:null,scaleLineColor:"rgba(0,0,0,.1)",scaleLineWidth:1,scaleShowLabels:!0,scaleLabel:"<%=value%>",scaleIntegersOnly:!0,scaleBeginAtZero:!1,scaleFontFamily:"'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",scaleFontSize:12,scaleFontStyle:"normal",scaleFontColor:"#666",responsive:!1,maintainAspectRatio:!0,showTooltips:!0,customTooltips:!1,tooltipEvents:["mousemove","touchstart","touchmove","mouseout"],tooltipFillColor:"rgba(0,0,0,0.8)",tooltipFontFamily:"'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",tooltipFontSize:14,tooltipFontStyle:"normal",tooltipFontColor:"#fff",tooltipTitleFontFamily:"'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",tooltipTitleFontSize:14,tooltipTitleFontStyle:"bold",tooltipTitleFontColor:"#fff",tooltipYPadding:6,tooltipXPadding:6,tooltipCaretSize:8,tooltipCornerRadius:6,tooltipXOffset:10,tooltipTemplate:"<%if (label){%><%=label%>: <%}%><%= value %>",multiTooltipTemplate:"<%= value %>",multiTooltipKeyBackground:"#fff",onAnimationProgress:function(){},onAnimationComplete:function(){}}},e.types={};var s=e.helpers={},n=s.each=function(t,i,e){var s=Array.prototype.slice.call(arguments,3);if(t)if(t.length===+t.length){var n;for(n=0;n<t.length;n++)i.apply(e,[t[n],n].concat(s))}else for(var o in t)i.apply(e,[t[o],o].concat(s))},o=s.clone=function(t){var i={};return n(t,function(e,s){t.hasOwnProperty(s)&&(i[s]=e)}),i},a=s.extend=function(t){return n(Array.prototype.slice.call(arguments,1),function(i){n(i,function(e,s){i.hasOwnProperty(s)&&(t[s]=e)})}),t},h=s.merge=function(){var t=Array.prototype.slice.call(arguments,0);return t.unshift({}),a.apply(null,t)},l=s.indexOf=function(t,i){if(Array.prototype.indexOf)return t.indexOf(i);for(var e=0;e<t.length;e++)if(t[e]===i)return e;return-1},r=(s.where=function(t,i){var e=[];return s.each(t,function(t){i(t)&&e.push(t)}),e},s.findNextWhere=function(t,i,e){e||(e=-1);for(var s=e+1;s<t.length;s++){var n=t[s];if(i(n))return n}},s.findPreviousWhere=function(t,i,e){e||(e=t.length);for(var s=e-1;s>=0;s--){var n=t[s];if(i(n))return n}},s.inherits=function(t){var i=this,e=t&&t.hasOwnProperty("constructor")?t.constructor:function(){return i.apply(this,arguments)},s=function(){this.constructor=e};return s.prototype=i.prototype,e.prototype=new s,e.extend=r,t&&a(e.prototype,t),e.__super__=i.prototype,e}),c=s.noop=function(){},u=s.uid=function(){var t=0;return function(){return"chart-"+t++}}(),d=s.warn=function(t){window.console&&"function"==typeof window.console.warn&&console.warn(t)},p=s.amd="function"==typeof define&&define.amd,f=s.isNumber=function(t){return!isNaN(parseFloat(t))&&isFinite(t)},g=s.max=function(t){return Math.max.apply(Math,t)},m=s.min=function(t){return Math.min.apply(Math,t)},v=(s.cap=function(t,i,e){if(f(i)){if(t>i)return i}else if(f(e)&&e>t)return e;return t},s.getDecimalPlaces=function(t){return t%1!==0&&f(t)?t.toString().split(".")[1].length:0}),S=s.radians=function(t){return t*(Math.PI/180)},x=(s.getAngleFromPoint=function(t,i){var e=i.x-t.x,s=i.y-t.y,n=Math.sqrt(e*e+s*s),o=2*Math.PI+Math.atan2(s,e);return 0>e&&0>s&&(o+=2*Math.PI),{angle:o,distance:n}},s.aliasPixel=function(t){return t%2===0?0:.5}),y=(s.splineCurve=function(t,i,e,s){var n=Math.sqrt(Math.pow(i.x-t.x,2)+Math.pow(i.y-t.y,2)),o=Math.sqrt(Math.pow(e.x-i.x,2)+Math.pow(e.y-i.y,2)),a=s*n/(n+o),h=s*o/(n+o);return{inner:{x:i.x-a*(e.x-t.x),y:i.y-a*(e.y-t.y)},outer:{x:i.x+h*(e.x-t.x),y:i.y+h*(e.y-t.y)}}},s.calculateOrderOfMagnitude=function(t){return Math.floor(Math.log(t)/Math.LN10)}),C=(s.calculateScaleRange=function(t,i,e,s,n){var o=2,a=Math.floor(i/(1.5*e)),h=o>=a,l=g(t),r=m(t);l===r&&(l+=.5,r>=.5&&!s?r-=.5:l+=.5);for(var c=Math.abs(l-r),u=y(c),d=Math.ceil(l/(1*Math.pow(10,u)))*Math.pow(10,u),p=s?0:Math.floor(r/(1*Math.pow(10,u)))*Math.pow(10,u),f=d-p,v=Math.pow(10,u),S=Math.round(f/v);(S>a||a>2*S)&&!h;)if(S>a)v*=2,S=Math.round(f/v),S%1!==0&&(h=!0);else if(n&&u>=0){if(v/2%1!==0)break;v/=2,S=Math.round(f/v)}else v/=2,S=Math.round(f/v);return h&&(S=o,v=f/S),{steps:S,stepValue:v,min:p,max:p+S*v}},s.template=function(t,i){function e(t,i){var e=/\W/.test(t)?new Function("obj","var p=[],print=function(){p.push.apply(p,arguments);};with(obj){p.push('"+t.replace(/[\r\t\n]/g," ").split("<%").join("	").replace(/((^|%>)[^\t]*)'/g,"$1\r").replace(/\t=(.*?)%>/g,"',$1,'").split("	").join("');").split("%>").join("p.push('").split("\r").join("\\'")+"');}return p.join('');"):s[t]=s[t];return i?e(i):e}if(t instanceof Function)return t(i);var s={};return e(t,i)}),w=(s.generateLabels=function(t,i,e,s){var o=new Array(i);return labelTemplateString&&n(o,function(i,n){o[n]=C(t,{value:e+s*(n+1)})}),o},s.easingEffects={linear:function(t){return t},easeInQuad:function(t){return t*t},easeOutQuad:function(t){return-1*t*(t-2)},easeInOutQuad:function(t){return(t/=.5)<1?.5*t*t:-0.5*(--t*(t-2)-1)},easeInCubic:function(t){return t*t*t},easeOutCubic:function(t){return 1*((t=t/1-1)*t*t+1)},easeInOutCubic:function(t){return(t/=.5)<1?.5*t*t*t:.5*((t-=2)*t*t+2)},easeInQuart:function(t){return t*t*t*t},easeOutQuart:function(t){return-1*((t=t/1-1)*t*t*t-1)},easeInOutQuart:function(t){return(t/=.5)<1?.5*t*t*t*t:-0.5*((t-=2)*t*t*t-2)},easeInQuint:function(t){return 1*(t/=1)*t*t*t*t},easeOutQuint:function(t){return 1*((t=t/1-1)*t*t*t*t+1)},easeInOutQuint:function(t){return(t/=.5)<1?.5*t*t*t*t*t:.5*((t-=2)*t*t*t*t+2)},easeInSine:function(t){return-1*Math.cos(t/1*(Math.PI/2))+1},easeOutSine:function(t){return 1*Math.sin(t/1*(Math.PI/2))},easeInOutSine:function(t){return-0.5*(Math.cos(Math.PI*t/1)-1)},easeInExpo:function(t){return 0===t?1:1*Math.pow(2,10*(t/1-1))},easeOutExpo:function(t){return 1===t?1:1*(-Math.pow(2,-10*t/1)+1)},easeInOutExpo:function(t){return 0===t?0:1===t?1:(t/=.5)<1?.5*Math.pow(2,10*(t-1)):.5*(-Math.pow(2,-10*--t)+2)},easeInCirc:function(t){return t>=1?t:-1*(Math.sqrt(1-(t/=1)*t)-1)},easeOutCirc:function(t){return 1*Math.sqrt(1-(t=t/1-1)*t)},easeInOutCirc:function(t){return(t/=.5)<1?-0.5*(Math.sqrt(1-t*t)-1):.5*(Math.sqrt(1-(t-=2)*t)+1)},easeInElastic:function(t){var i=1.70158,e=0,s=1;return 0===t?0:1==(t/=1)?1:(e||(e=.3),s<Math.abs(1)?(s=1,i=e/4):i=e/(2*Math.PI)*Math.asin(1/s),-(s*Math.pow(2,10*(t-=1))*Math.sin(2*(1*t-i)*Math.PI/e)))},easeOutElastic:function(t){var i=1.70158,e=0,s=1;return 0===t?0:1==(t/=1)?1:(e||(e=.3),s<Math.abs(1)?(s=1,i=e/4):i=e/(2*Math.PI)*Math.asin(1/s),s*Math.pow(2,-10*t)*Math.sin(2*(1*t-i)*Math.PI/e)+1)},easeInOutElastic:function(t){var i=1.70158,e=0,s=1;return 0===t?0:2==(t/=.5)?1:(e||(e=.3*1.5),s<Math.abs(1)?(s=1,i=e/4):i=e/(2*Math.PI)*Math.asin(1/s),1>t?-.5*s*Math.pow(2,10*(t-=1))*Math.sin(2*(1*t-i)*Math.PI/e):s*Math.pow(2,-10*(t-=1))*Math.sin(2*(1*t-i)*Math.PI/e)*.5+1)},easeInBack:function(t){var i=1.70158;return 1*(t/=1)*t*((i+1)*t-i)},easeOutBack:function(t){var i=1.70158;return 1*((t=t/1-1)*t*((i+1)*t+i)+1)},easeInOutBack:function(t){var i=1.70158;return(t/=.5)<1?.5*t*t*(((i*=1.525)+1)*t-i):.5*((t-=2)*t*(((i*=1.525)+1)*t+i)+2)},easeInBounce:function(t){return 1-w.easeOutBounce(1-t)},easeOutBounce:function(t){return(t/=1)<1/2.75?7.5625*t*t:2/2.75>t?1*(7.5625*(t-=1.5/2.75)*t+.75):2.5/2.75>t?1*(7.5625*(t-=2.25/2.75)*t+.9375):1*(7.5625*(t-=2.625/2.75)*t+.984375)},easeInOutBounce:function(t){return.5>t?.5*w.easeInBounce(2*t):.5*w.easeOutBounce(2*t-1)+.5}}),b=s.requestAnimFrame=function(){return window.requestAnimationFrame||window.webkitRequestAnimationFrame||window.mozRequestAnimationFrame||window.oRequestAnimationFrame||window.msRequestAnimationFrame||function(t){return window.setTimeout(t,1e3/60)}}(),P=(s.cancelAnimFrame=function(){return window.cancelAnimationFrame||window.webkitCancelAnimationFrame||window.mozCancelAnimationFrame||window.oCancelAnimationFrame||window.msCancelAnimationFrame||function(t){return window.clearTimeout(t,1e3/60)}}(),s.animationLoop=function(t,i,e,s,n,o){var a=0,h=w[e]||w.linear,l=function(){a++;var e=a/i,r=h(e);t.call(o,r,e,a),s.call(o,r,e),i>a?o.animationFrame=b(l):n.apply(o)};b(l)},s.getRelativePosition=function(t){var i,e,s=t.originalEvent||t,n=t.currentTarget||t.srcElement,o=n.getBoundingClientRect();return s.touches?(i=s.touches[0].clientX-o.left,e=s.touches[0].clientY-o.top):(i=s.clientX-o.left,e=s.clientY-o.top),{x:i,y:e}},s.addEvent=function(t,i,e){t.addEventListener?t.addEventListener(i,e):t.attachEvent?t.attachEvent("on"+i,e):t["on"+i]=e}),L=s.removeEvent=function(t,i,e){t.removeEventListener?t.removeEventListener(i,e,!1):t.detachEvent?t.detachEvent("on"+i,e):t["on"+i]=c},k=(s.bindEvents=function(t,i,e){t.events||(t.events={}),n(i,function(i){t.events[i]=function(){e.apply(t,arguments)},P(t.chart.canvas,i,t.events[i])})},s.unbindEvents=function(t,i){n(i,function(i,e){L(t.chart.canvas,e,i)})}),F=s.getMaximumWidth=function(t){var i=t.parentNode;return i.clientWidth},R=s.getMaximumHeight=function(t){var i=t.parentNode;return i.clientHeight},T=(s.getMaximumSize=s.getMaximumWidth,s.retinaScale=function(t){var i=t.ctx,e=t.canvas.width,s=t.canvas.height;window.devicePixelRatio&&(i.canvas.style.width=e+"px",i.canvas.style.height=s+"px",i.canvas.height=s*window.devicePixelRatio,i.canvas.width=e*window.devicePixelRatio,i.scale(window.devicePixelRatio,window.devicePixelRatio))}),A=s.clear=function(t){t.ctx.clearRect(0,0,t.width,t.height)},M=s.fontString=function(t,i,e){return i+" "+t+"px "+e},W=s.longestText=function(t,i,e){t.font=i;var s=0;return n(e,function(i){var e=t.measureText(i).width;s=e>s?e:s}),s},z=s.drawRoundedRectangle=function(t,i,e,s,n,o){t.beginPath(),t.moveTo(i+o,e),t.lineTo(i+s-o,e),t.quadraticCurveTo(i+s,e,i+s,e+o),t.lineTo(i+s,e+n-o),t.quadraticCurveTo(i+s,e+n,i+s-o,e+n),t.lineTo(i+o,e+n),t.quadraticCurveTo(i,e+n,i,e+n-o),t.lineTo(i,e+o),t.quadraticCurveTo(i,e,i+o,e),t.closePath()};e.instances={},e.Type=function(t,i,s){this.options=i,this.chart=s,this.id=u(),e.instances[this.id]=this,i.responsive&&this.resize(),this.initialize.call(this,t)},a(e.Type.prototype,{initialize:function(){return this},clear:function(){return A(this.chart),this},stop:function(){return s.cancelAnimFrame.call(t,this.animationFrame),this},resize:function(t){this.stop();var i=this.chart.canvas,e=F(this.chart.canvas),s=this.options.maintainAspectRatio?e/this.chart.aspectRatio:R(this.chart.canvas);return i.width=this.chart.width=e,i.height=this.chart.height=s,T(this.chart),"function"==typeof t&&t.apply(this,Array.prototype.slice.call(arguments,1)),this},reflow:c,render:function(t){return t&&this.reflow(),this.options.animation&&!t?s.animationLoop(this.draw,this.options.animationSteps,this.options.animationEasing,this.options.onAnimationProgress,this.options.onAnimationComplete,this):(this.draw(),this.options.onAnimationComplete.call(this)),this},generateLegend:function(){return C(this.options.legendTemplate,this)},destroy:function(){this.clear(),k(this,this.events);var t=this.chart.canvas;t.width=this.chart.width,t.height=this.chart.height,t.style.removeProperty?(t.style.removeProperty("width"),t.style.removeProperty("height")):(t.style.removeAttribute("width"),t.style.removeAttribute("height")),delete e.instances[this.id]},showTooltip:function(t,i){"undefined"==typeof this.activeElements&&(this.activeElements=[]);var o=function(t){var i=!1;return t.length!==this.activeElements.length?i=!0:(n(t,function(t,e){t!==this.activeElements[e]&&(i=!0)},this),i)}.call(this,t);if(o||i){if(this.activeElements=t,this.draw(),this.options.customTooltips&&this.options.customTooltips(!1),t.length>0)if(this.datasets&&this.datasets.length>1){for(var a,h,r=this.datasets.length-1;r>=0&&(a=this.datasets[r].points||this.datasets[r].bars||this.datasets[r].segments,h=l(a,t[0]),-1===h);r--);var c=[],u=[],d=function(){var t,i,e,n,o,a=[],l=[],r=[];return s.each(this.datasets,function(i){t=i.points||i.bars||i.segments,t[h]&&t[h].hasValue()&&a.push(t[h])}),s.each(a,function(t){l.push(t.x),r.push(t.y),c.push(s.template(this.options.multiTooltipTemplate,t)),u.push({fill:t._saved.fillColor||t.fillColor,stroke:t._saved.strokeColor||t.strokeColor})},this),o=m(r),e=g(r),n=m(l),i=g(l),{x:n>this.chart.width/2?n:i,y:(o+e)/2}}.call(this,h);new e.MultiTooltip({x:d.x,y:d.y,xPadding:this.options.tooltipXPadding,yPadding:this.options.tooltipYPadding,xOffset:this.options.tooltipXOffset,fillColor:this.options.tooltipFillColor,textColor:this.options.tooltipFontColor,fontFamily:this.options.tooltipFontFamily,fontStyle:this.options.tooltipFontStyle,fontSize:this.options.tooltipFontSize,titleTextColor:this.options.tooltipTitleFontColor,titleFontFamily:this.options.tooltipTitleFontFamily,titleFontStyle:this.options.tooltipTitleFontStyle,titleFontSize:this.options.tooltipTitleFontSize,cornerRadius:this.options.tooltipCornerRadius,labels:c,legendColors:u,legendColorBackground:this.options.multiTooltipKeyBackground,title:t[0].label,chart:this.chart,ctx:this.chart.ctx,custom:this.options.customTooltips}).draw()}else n(t,function(t){var i=t.tooltipPosition();new e.Tooltip({x:Math.round(i.x),y:Math.round(i.y),xPadding:this.options.tooltipXPadding,yPadding:this.options.tooltipYPadding,fillColor:this.options.tooltipFillColor,textColor:this.options.tooltipFontColor,fontFamily:this.options.tooltipFontFamily,fontStyle:this.options.tooltipFontStyle,fontSize:this.options.tooltipFontSize,caretHeight:this.options.tooltipCaretSize,cornerRadius:this.options.tooltipCornerRadius,text:C(this.options.tooltipTemplate,t),chart:this.chart,custom:this.options.customTooltips}).draw()},this);return this}},toBase64Image:function(){return this.chart.canvas.toDataURL.apply(this.chart.canvas,arguments)}}),e.Type.extend=function(t){var i=this,s=function(){return i.apply(this,arguments)};if(s.prototype=o(i.prototype),a(s.prototype,t),s.extend=e.Type.extend,t.name||i.prototype.name){var n=t.name||i.prototype.name,l=e.defaults[i.prototype.name]?o(e.defaults[i.prototype.name]):{};e.defaults[n]=a(l,t.defaults),e.types[n]=s,e.prototype[n]=function(t,i){var o=h(e.defaults.global,e.defaults[n],i||{});return new s(t,o,this)}}else d("Name not provided for this chart, so it hasn't been registered");return i},e.Element=function(t){a(this,t),this.initialize.apply(this,arguments),this.save()},a(e.Element.prototype,{initialize:function(){},restore:function(t){return t?n(t,function(t){this[t]=this._saved[t]},this):a(this,this._saved),this},save:function(){return this._saved=o(this),delete this._saved._saved,this},update:function(t){return n(t,function(t,i){this._saved[i]=this[i],this[i]=t},this),this},transition:function(t,i){return n(t,function(t,e){this[e]=(t-this._saved[e])*i+this._saved[e]},this),this},tooltipPosition:function(){return{x:this.x,y:this.y}},hasValue:function(){return f(this.value)}}),e.Element.extend=r,e.Point=e.Element.extend({display:!0,inRange:function(t,i){var e=this.hitDetectionRadius+this.radius;return Math.pow(t-this.x,2)+Math.pow(i-this.y,2)<Math.pow(e,2)},draw:function(){if(this.display){var t=this.ctx;t.beginPath(),t.arc(this.x,this.y,this.radius,0,2*Math.PI),t.closePath(),t.strokeStyle=this.strokeColor,t.lineWidth=this.strokeWidth,t.fillStyle=this.fillColor,t.fill(),t.stroke()}}}),e.Arc=e.Element.extend({inRange:function(t,i){var e=s.getAngleFromPoint(this,{x:t,y:i}),n=e.angle>=this.startAngle&&e.angle<=this.endAngle,o=e.distance>=this.innerRadius&&e.distance<=this.outerRadius;return n&&o},tooltipPosition:function(){var t=this.startAngle+(this.endAngle-this.startAngle)/2,i=(this.outerRadius-this.innerRadius)/2+this.innerRadius;return{x:this.x+Math.cos(t)*i,y:this.y+Math.sin(t)*i}},draw:function(t){var i=this.ctx;i.beginPath(),i.arc(this.x,this.y,this.outerRadius,this.startAngle,this.endAngle),i.arc(this.x,this.y,this.innerRadius,this.endAngle,this.startAngle,!0),i.closePath(),i.strokeStyle=this.strokeColor,i.lineWidth=this.strokeWidth,i.fillStyle=this.fillColor,i.fill(),i.lineJoin="bevel",this.showStroke&&i.stroke()}}),e.Rectangle=e.Element.extend({draw:function(){var t=this.ctx,i=this.width/2,e=this.x-i,s=this.x+i,n=this.base-(this.base-this.y),o=this.strokeWidth/2;this.showStroke&&(e+=o,s-=o,n+=o),t.beginPath(),t.fillStyle=this.fillColor,t.strokeStyle=this.strokeColor,t.lineWidth=this.strokeWidth,t.moveTo(e,this.base),t.lineTo(e,n),t.lineTo(s,n),t.lineTo(s,this.base),t.fill(),this.showStroke&&t.stroke()},height:function(){return this.base-this.y},inRange:function(t,i){return t>=this.x-this.width/2&&t<=this.x+this.width/2&&i>=this.y&&i<=this.base}}),e.Tooltip=e.Element.extend({draw:function(){var t=this.chart.ctx;t.font=M(this.fontSize,this.fontStyle,this.fontFamily),this.xAlign="center",this.yAlign="above";var i=this.caretPadding=2,e=t.measureText(this.text).width+2*this.xPadding,s=this.fontSize+2*this.yPadding,n=s+this.caretHeight+i;this.x+e/2>this.chart.width?this.xAlign="left":this.x-e/2<0&&(this.xAlign="right"),this.y-n<0&&(this.yAlign="below");var o=this.x-e/2,a=this.y-n;if(t.fillStyle=this.fillColor,this.custom)this.custom(this);else{switch(this.yAlign){case"above":t.beginPath(),t.moveTo(this.x,this.y-i),t.lineTo(this.x+this.caretHeight,this.y-(i+this.caretHeight)),t.lineTo(this.x-this.caretHeight,this.y-(i+this.caretHeight)),t.closePath(),t.fill();break;case"below":a=this.y+i+this.caretHeight,t.beginPath(),t.moveTo(this.x,this.y+i),t.lineTo(this.x+this.caretHeight,this.y+i+this.caretHeight),t.lineTo(this.x-this.caretHeight,this.y+i+this.caretHeight),t.closePath(),t.fill()}switch(this.xAlign){case"left":o=this.x-e+(this.cornerRadius+this.caretHeight);break;case"right":o=this.x-(this.cornerRadius+this.caretHeight)}z(t,o,a,e,s,this.cornerRadius),t.fill(),t.fillStyle=this.textColor,t.textAlign="center",t.textBaseline="middle",t.fillText(this.text,o+e/2,a+s/2)}}}),e.MultiTooltip=e.Element.extend({initialize:function(){this.font=M(this.fontSize,this.fontStyle,this.fontFamily),this.titleFont=M(this.titleFontSize,this.titleFontStyle,this.titleFontFamily),this.height=this.labels.length*this.fontSize+(this.labels.length-1)*(this.fontSize/2)+2*this.yPadding+1.5*this.titleFontSize,this.ctx.font=this.titleFont;var t=this.ctx.measureText(this.title).width,i=W(this.ctx,this.font,this.labels)+this.fontSize+3,e=g([i,t]);this.width=e+2*this.xPadding;var s=this.height/2;this.y-s<0?this.y=s:this.y+s>this.chart.height&&(this.y=this.chart.height-s),this.x>this.chart.width/2?this.x-=this.xOffset+this.width:this.x+=this.xOffset},getLineHeight:function(t){var i=this.y-this.height/2+this.yPadding,e=t-1;return 0===t?i+this.titleFontSize/2:i+(1.5*this.fontSize*e+this.fontSize/2)+1.5*this.titleFontSize},draw:function(){if(this.custom)this.custom(this);else{z(this.ctx,this.x,this.y-this.height/2,this.width,this.height,this.cornerRadius);var t=this.ctx;t.fillStyle=this.fillColor,t.fill(),t.closePath(),t.textAlign="left",t.textBaseline="middle",t.fillStyle=this.titleTextColor,t.font=this.titleFont,t.fillText(this.title,this.x+this.xPadding,this.getLineHeight(0)),t.font=this.font,s.each(this.labels,function(i,e){t.fillStyle=this.textColor,t.fillText(i,this.x+this.xPadding+this.fontSize+3,this.getLineHeight(e+1)),t.fillStyle=this.legendColorBackground,t.fillRect(this.x+this.xPadding,this.getLineHeight(e+1)-this.fontSize/2,this.fontSize,this.fontSize),t.fillStyle=this.legendColors[e].fill,t.fillRect(this.x+this.xPadding,this.getLineHeight(e+1)-this.fontSize/2,this.fontSize,this.fontSize)},this)}}}),e.Scale=e.Element.extend({initialize:function(){this.fit()},buildYLabels:function(){this.yLabels=[];for(var t=v(this.stepValue),i=0;i<=this.steps;i++)this.yLabels.push(C(this.templateString,{value:(this.min+i*this.stepValue).toFixed(t)}));this.yLabelWidth=this.display&&this.showLabels?W(this.ctx,this.font,this.yLabels):0},addXLabel:function(t){this.xLabels.push(t),this.valuesCount++,this.fit()},removeXLabel:function(){this.xLabels.shift(),this.valuesCount--,this.fit()},fit:function(){this.startPoint=this.display?this.fontSize:0,this.endPoint=this.display?this.height-1.5*this.fontSize-5:this.height,this.startPoint+=this.padding,this.endPoint-=this.padding;var t,i=this.endPoint-this.startPoint;for(this.calculateYRange(i),this.buildYLabels(),this.calculateXLabelRotation();i>this.endPoint-this.startPoint;)i=this.endPoint-this.startPoint,t=this.yLabelWidth,this.calculateYRange(i),this.buildYLabels(),t<this.yLabelWidth&&this.calculateXLabelRotation()},calculateXLabelRotation:function(){this.ctx.font=this.font;var t,i,e=this.ctx.measureText(this.xLabels[0]).width,s=this.ctx.measureText(this.xLabels[this.xLabels.length-1]).width;if(this.xScalePaddingRight=s/2+3,this.xScalePaddingLeft=e/2>this.yLabelWidth+10?e/2:this.yLabelWidth+10,this.xLabelRotation=0,this.display){var n,o=W(this.ctx,this.font,this.xLabels);this.xLabelWidth=o;for(var a=Math.floor(this.calculateX(1)-this.calculateX(0))-6;this.xLabelWidth>a&&0===this.xLabelRotation||this.xLabelWidth>a&&this.xLabelRotation<=90&&this.xLabelRotation>0;)n=Math.cos(S(this.xLabelRotation)),t=n*e,i=n*s,t+this.fontSize/2>this.yLabelWidth+8&&(this.xScalePaddingLeft=t+this.fontSize/2),this.xScalePaddingRight=this.fontSize/2,this.xLabelRotation++,this.xLabelWidth=n*o;this.xLabelRotation>0&&(this.endPoint-=Math.sin(S(this.xLabelRotation))*o+3)}else this.xLabelWidth=0,this.xScalePaddingRight=this.padding,this.xScalePaddingLeft=this.padding},calculateYRange:c,drawingArea:function(){return this.startPoint-this.endPoint},calculateY:function(t){var i=this.drawingArea()/(this.min-this.max);return this.endPoint-i*(t-this.min)},calculateX:function(t){var i=(this.xLabelRotation>0,this.width-(this.xScalePaddingLeft+this.xScalePaddingRight)),e=i/(this.valuesCount-(this.offsetGridLines?0:1)),s=e*t+this.xScalePaddingLeft;return this.offsetGridLines&&(s+=e/2),Math.round(s)},update:function(t){s.extend(this,t),this.fit()},draw:function(){var t=this.ctx,i=(this.endPoint-this.startPoint)/this.steps,e=Math.round(this.xScalePaddingLeft);this.display&&(t.fillStyle=this.textColor,t.font=this.font,n(this.yLabels,function(n,o){var a=this.endPoint-i*o,h=Math.round(a),l=this.showHorizontalLines;t.textAlign="right",t.textBaseline="middle",this.showLabels&&t.fillText(n,e-10,a),0!==o||l||(l=!0),l&&t.beginPath(),o>0?(t.lineWidth=this.gridLineWidth,t.strokeStyle=this.gridLineColor):(t.lineWidth=this.lineWidth,t.strokeStyle=this.lineColor),h+=s.aliasPixel(t.lineWidth),l&&(t.moveTo(e,h),t.lineTo(this.width,h),t.stroke(),t.closePath()),t.lineWidth=this.lineWidth,t.strokeStyle=this.lineColor,t.beginPath(),t.moveTo(e-5,h),t.lineTo(e,h),t.stroke(),t.closePath()},this),n(this.xLabels,function(i,e){var s=this.calculateX(e)+x(this.lineWidth),n=this.calculateX(e-(this.offsetGridLines?.5:0))+x(this.lineWidth),o=this.xLabelRotation>0,a=this.showVerticalLines;0!==e||a||(a=!0),a&&t.beginPath(),e>0?(t.lineWidth=this.gridLineWidth,t.strokeStyle=this.gridLineColor):(t.lineWidth=this.lineWidth,t.strokeStyle=this.lineColor),a&&(t.moveTo(n,this.endPoint),t.lineTo(n,this.startPoint-3),t.stroke(),t.closePath()),t.lineWidth=this.lineWidth,t.strokeStyle=this.lineColor,t.beginPath(),t.moveTo(n,this.endPoint),t.lineTo(n,this.endPoint+5),t.stroke(),t.closePath(),t.save(),t.translate(s,o?this.endPoint+12:this.endPoint+8),t.rotate(-1*S(this.xLabelRotation)),t.font=this.font,t.textAlign=o?"right":"center",t.textBaseline=o?"middle":"top",t.fillText(i,0,0),t.restore()},this))}}),e.RadialScale=e.Element.extend({initialize:function(){this.size=m([this.height,this.width]),this.drawingArea=this.display?this.size/2-(this.fontSize/2+this.backdropPaddingY):this.size/2},calculateCenterOffset:function(t){var i=this.drawingArea/(this.max-this.min);return(t-this.min)*i},update:function(){this.lineArc?this.drawingArea=this.display?this.size/2-(this.fontSize/2+this.backdropPaddingY):this.size/2:this.setScaleSize(),this.buildYLabels()},buildYLabels:function(){this.yLabels=[];for(var t=v(this.stepValue),i=0;i<=this.steps;i++)this.yLabels.push(C(this.templateString,{value:(this.min+i*this.stepValue).toFixed(t)}))},getCircumference:function(){return 2*Math.PI/this.valuesCount},setScaleSize:function(){var t,i,e,s,n,o,a,h,l,r,c,u,d=m([this.height/2-this.pointLabelFontSize-5,this.width/2]),p=this.width,g=0;for(this.ctx.font=M(this.pointLabelFontSize,this.pointLabelFontStyle,this.pointLabelFontFamily),i=0;i<this.valuesCount;i++)t=this.getPointPosition(i,d),e=this.ctx.measureText(C(this.templateString,{value:this.labels[i]})).width+5,0===i||i===this.valuesCount/2?(s=e/2,t.x+s>p&&(p=t.x+s,n=i),t.x-s<g&&(g=t.x-s,a=i)):i<this.valuesCount/2?t.x+e>p&&(p=t.x+e,n=i):i>this.valuesCount/2&&t.x-e<g&&(g=t.x-e,a=i);l=g,r=Math.ceil(p-this.width),o=this.getIndexAngle(n),h=this.getIndexAngle(a),c=r/Math.sin(o+Math.PI/2),u=l/Math.sin(h+Math.PI/2),c=f(c)?c:0,u=f(u)?u:0,this.drawingArea=d-(u+c)/2,this.setCenterPoint(u,c)},setCenterPoint:function(t,i){var e=this.width-i-this.drawingArea,s=t+this.drawingArea;this.xCenter=(s+e)/2,this.yCenter=this.height/2},getIndexAngle:function(t){var i=2*Math.PI/this.valuesCount;return t*i-Math.PI/2},getPointPosition:function(t,i){var e=this.getIndexAngle(t);return{x:Math.cos(e)*i+this.xCenter,y:Math.sin(e)*i+this.yCenter}},draw:function(){if(this.display){var t=this.ctx;if(n(this.yLabels,function(i,e){if(e>0){var s,n=e*(this.drawingArea/this.steps),o=this.yCenter-n;if(this.lineWidth>0)if(t.strokeStyle=this.lineColor,t.lineWidth=this.lineWidth,this.lineArc)t.beginPath(),t.arc(this.xCenter,this.yCenter,n,0,2*Math.PI),t.closePath(),t.stroke();else{t.beginPath();for(var a=0;a<this.valuesCount;a++)s=this.getPointPosition(a,this.calculateCenterOffset(this.min+e*this.stepValue)),0===a?t.moveTo(s.x,s.y):t.lineTo(s.x,s.y);t.closePath(),t.stroke()}if(this.showLabels){if(t.font=M(this.fontSize,this.fontStyle,this.fontFamily),this.showLabelBackdrop){var h=t.measureText(i).width;t.fillStyle=this.backdropColor,t.fillRect(this.xCenter-h/2-this.backdropPaddingX,o-this.fontSize/2-this.backdropPaddingY,h+2*this.backdropPaddingX,this.fontSize+2*this.backdropPaddingY)}t.textAlign="center",t.textBaseline="middle",t.fillStyle=this.fontColor,t.fillText(i,this.xCenter,o)}}},this),!this.lineArc){t.lineWidth=this.angleLineWidth,t.strokeStyle=this.angleLineColor;for(var i=this.valuesCount-1;i>=0;i--){if(this.angleLineWidth>0){var e=this.getPointPosition(i,this.calculateCenterOffset(this.max));t.beginPath(),t.moveTo(this.xCenter,this.yCenter),t.lineTo(e.x,e.y),t.stroke(),t.closePath()}var s=this.getPointPosition(i,this.calculateCenterOffset(this.max)+5);t.font=M(this.pointLabelFontSize,this.pointLabelFontStyle,this.pointLabelFontFamily),t.fillStyle=this.pointLabelFontColor;var o=this.labels.length,a=this.labels.length/2,h=a/2,l=h>i||i>o-h,r=i===h||i===o-h;t.textAlign=0===i?"center":i===a?"center":a>i?"left":"right",t.textBaseline=r?"middle":l?"bottom":"top",t.fillText(this.labels[i],s.x,s.y)}}}}}),s.addEvent(window,"resize",function(){var t;return function(){clearTimeout(t),t=setTimeout(function(){n(e.instances,function(t){t.options.responsive&&t.resize(t.render,!0)})},50)}}()),p?define(function(){return e}):"object"==typeof module&&module.exports&&(module.exports=e),t.Chart=e,e.noConflict=function(){return t.Chart=i,e}}).call(this),function(){"use strict";var t=this,i=t.Chart,e=i.helpers,s={scaleBeginAtZero:!0,scaleShowGridLines:!0,scaleGridLineColor:"rgba(0,0,0,.05)",scaleGridLineWidth:1,scaleShowHorizontalLines:!0,scaleShowVerticalLines:!0,barShowStroke:!0,barStrokeWidth:2,barValueSpacing:5,barDatasetSpacing:1,legendTemplate:'<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>'};i.Type.extend({name:"Bar",defaults:s,initialize:function(t){var s=this.options;this.ScaleClass=i.Scale.extend({offsetGridLines:!0,calculateBarX:function(t,i,e){var n=this.calculateBaseWidth(),o=this.calculateX(e)-n/2,a=this.calculateBarWidth(t);return o+a*i+i*s.barDatasetSpacing+a/2},calculateBaseWidth:function(){return this.calculateX(1)-this.calculateX(0)-2*s.barValueSpacing},calculateBarWidth:function(t){var i=this.calculateBaseWidth()-(t-1)*s.barDatasetSpacing;return i/t}}),this.datasets=[],this.options.showTooltips&&e.bindEvents(this,this.options.tooltipEvents,function(t){var i="mouseout"!==t.type?this.getBarsAtEvent(t):[];this.eachBars(function(t){t.restore(["fillColor","strokeColor"])}),e.each(i,function(t){t.fillColor=t.highlightFill,t.strokeColor=t.highlightStroke}),this.showTooltip(i)}),this.BarClass=i.Rectangle.extend({strokeWidth:this.options.barStrokeWidth,showStroke:this.options.barShowStroke,ctx:this.chart.ctx}),e.each(t.datasets,function(i){var s={label:i.label||null,fillColor:i.fillColor,strokeColor:i.strokeColor,bars:[]};this.datasets.push(s),e.each(i.data,function(e,n){s.bars.push(new this.BarClass({value:e,label:t.labels[n],datasetLabel:i.label,strokeColor:i.strokeColor,fillColor:i.fillColor,highlightFill:i.highlightFill||i.fillColor,highlightStroke:i.highlightStroke||i.strokeColor}))},this)},this),this.buildScale(t.labels),this.BarClass.prototype.base=this.scale.endPoint,this.eachBars(function(t,i,s){e.extend(t,{width:this.scale.calculateBarWidth(this.datasets.length),x:this.scale.calculateBarX(this.datasets.length,s,i),y:this.scale.endPoint}),t.save()},this),this.render()},update:function(){this.scale.update(),e.each(this.activeElements,function(t){t.restore(["fillColor","strokeColor"])}),this.eachBars(function(t){t.save()}),this.render()},eachBars:function(t){e.each(this.datasets,function(i,s){e.each(i.bars,t,this,s)},this)},getBarsAtEvent:function(t){for(var i,s=[],n=e.getRelativePosition(t),o=function(t){s.push(t.bars[i])},a=0;a<this.datasets.length;a++)for(i=0;i<this.datasets[a].bars.length;i++)if(this.datasets[a].bars[i].inRange(n.x,n.y))return e.each(this.datasets,o),s;return s},buildScale:function(t){var i=this,s=function(){var t=[];return i.eachBars(function(i){t.push(i.value)}),t},n={templateString:this.options.scaleLabel,height:this.chart.height,width:this.chart.width,ctx:this.chart.ctx,textColor:this.options.scaleFontColor,fontSize:this.options.scaleFontSize,fontStyle:this.options.scaleFontStyle,fontFamily:this.options.scaleFontFamily,valuesCount:t.length,beginAtZero:this.options.scaleBeginAtZero,integersOnly:this.options.scaleIntegersOnly,calculateYRange:function(t){var i=e.calculateScaleRange(s(),t,this.fontSize,this.beginAtZero,this.integersOnly);e.extend(this,i)},xLabels:t,font:e.fontString(this.options.scaleFontSize,this.options.scaleFontStyle,this.options.scaleFontFamily),lineWidth:this.options.scaleLineWidth,lineColor:this.options.scaleLineColor,showHorizontalLines:this.options.scaleShowHorizontalLines,showVerticalLines:this.options.scaleShowVerticalLines,gridLineWidth:this.options.scaleShowGridLines?this.options.scaleGridLineWidth:0,gridLineColor:this.options.scaleShowGridLines?this.options.scaleGridLineColor:"rgba(0,0,0,0)",padding:this.options.showScale?0:this.options.barShowStroke?this.options.barStrokeWidth:0,showLabels:this.options.scaleShowLabels,display:this.options.showScale};this.options.scaleOverride&&e.extend(n,{calculateYRange:e.noop,steps:this.options.scaleSteps,stepValue:this.options.scaleStepWidth,min:this.options.scaleStartValue,max:this.options.scaleStartValue+this.options.scaleSteps*this.options.scaleStepWidth}),this.scale=new this.ScaleClass(n)},addData:function(t,i){e.each(t,function(t,e){this.datasets[e].bars.push(new this.BarClass({value:t,label:i,x:this.scale.calculateBarX(this.datasets.length,e,this.scale.valuesCount+1),y:this.scale.endPoint,width:this.scale.calculateBarWidth(this.datasets.length),base:this.scale.endPoint,strokeColor:this.datasets[e].strokeColor,fillColor:this.datasets[e].fillColor}))},this),this.scale.addXLabel(i),this.update()},removeData:function(){this.scale.removeXLabel(),e.each(this.datasets,function(t){t.bars.shift()},this),this.update()},reflow:function(){e.extend(this.BarClass.prototype,{y:this.scale.endPoint,base:this.scale.endPoint});
var t=e.extend({height:this.chart.height,width:this.chart.width});this.scale.update(t)},draw:function(t){var i=t||1;this.clear();this.chart.ctx;this.scale.draw(i),e.each(this.datasets,function(t,s){e.each(t.bars,function(t,e){t.hasValue()&&(t.base=this.scale.endPoint,t.transition({x:this.scale.calculateBarX(this.datasets.length,s,e),y:this.scale.calculateY(t.value),width:this.scale.calculateBarWidth(this.datasets.length)},i).draw())},this)},this)}})}.call(this),function(){"use strict";var t=this,i=t.Chart,e=i.helpers,s={segmentShowStroke:!0,segmentStrokeColor:"#fff",segmentStrokeWidth:2,percentageInnerCutout:50,animationSteps:100,animationEasing:"easeOutBounce",animateRotate:!0,animateScale:!1,legendTemplate:'<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<segments.length; i++){%><li><span style="background-color:<%=segments[i].fillColor%>"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>'};i.Type.extend({name:"Doughnut",defaults:s,initialize:function(t){this.segments=[],this.outerRadius=(e.min([this.chart.width,this.chart.height])-this.options.segmentStrokeWidth/2)/2,this.SegmentArc=i.Arc.extend({ctx:this.chart.ctx,x:this.chart.width/2,y:this.chart.height/2}),this.options.showTooltips&&e.bindEvents(this,this.options.tooltipEvents,function(t){var i="mouseout"!==t.type?this.getSegmentsAtEvent(t):[];e.each(this.segments,function(t){t.restore(["fillColor"])}),e.each(i,function(t){t.fillColor=t.highlightColor}),this.showTooltip(i)}),this.calculateTotal(t),e.each(t,function(t,i){this.addData(t,i,!0)},this),this.render()},getSegmentsAtEvent:function(t){var i=[],s=e.getRelativePosition(t);return e.each(this.segments,function(t){t.inRange(s.x,s.y)&&i.push(t)},this),i},addData:function(t,i,e){var s=i||this.segments.length;this.segments.splice(s,0,new this.SegmentArc({value:t.value,outerRadius:this.options.animateScale?0:this.outerRadius,innerRadius:this.options.animateScale?0:this.outerRadius/100*this.options.percentageInnerCutout,fillColor:t.color,highlightColor:t.highlight||t.color,showStroke:this.options.segmentShowStroke,strokeWidth:this.options.segmentStrokeWidth,strokeColor:this.options.segmentStrokeColor,startAngle:1.5*Math.PI,circumference:this.options.animateRotate?0:this.calculateCircumference(t.value),label:t.label})),e||(this.reflow(),this.update())},calculateCircumference:function(t){return 2*Math.PI*(t/this.total)},calculateTotal:function(t){this.total=0,e.each(t,function(t){this.total+=t.value},this)},update:function(){this.calculateTotal(this.segments),e.each(this.activeElements,function(t){t.restore(["fillColor"])}),e.each(this.segments,function(t){t.save()}),this.render()},removeData:function(t){var i=e.isNumber(t)?t:this.segments.length-1;this.segments.splice(i,1),this.reflow(),this.update()},reflow:function(){e.extend(this.SegmentArc.prototype,{x:this.chart.width/2,y:this.chart.height/2}),this.outerRadius=(e.min([this.chart.width,this.chart.height])-this.options.segmentStrokeWidth/2)/2,e.each(this.segments,function(t){t.update({outerRadius:this.outerRadius,innerRadius:this.outerRadius/100*this.options.percentageInnerCutout})},this)},draw:function(t){var i=t?t:1;this.clear(),e.each(this.segments,function(t,e){t.transition({circumference:this.calculateCircumference(t.value),outerRadius:this.outerRadius,innerRadius:this.outerRadius/100*this.options.percentageInnerCutout},i),t.endAngle=t.startAngle+t.circumference,t.draw(),0===e&&(t.startAngle=1.5*Math.PI),e<this.segments.length-1&&(this.segments[e+1].startAngle=t.endAngle)},this)}}),i.types.Doughnut.extend({name:"Pie",defaults:e.merge(s,{percentageInnerCutout:0})})}.call(this),function(){"use strict";var t=this,i=t.Chart,e=i.helpers,s={scaleShowGridLines:!0,scaleGridLineColor:"rgba(0,0,0,.05)",scaleGridLineWidth:1,scaleShowHorizontalLines:!0,scaleShowVerticalLines:!0,bezierCurve:!0,bezierCurveTension:.4,pointDot:!0,pointDotRadius:4,pointDotStrokeWidth:1,pointHitDetectionRadius:20,datasetStroke:!0,datasetStrokeWidth:2,datasetFill:!0,legendTemplate:'<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].strokeColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>'};i.Type.extend({name:"Line",defaults:s,initialize:function(t){this.PointClass=i.Point.extend({strokeWidth:this.options.pointDotStrokeWidth,radius:this.options.pointDotRadius,display:this.options.pointDot,hitDetectionRadius:this.options.pointHitDetectionRadius,ctx:this.chart.ctx,inRange:function(t){return Math.pow(t-this.x,2)<Math.pow(this.radius+this.hitDetectionRadius,2)}}),this.datasets=[],this.options.showTooltips&&e.bindEvents(this,this.options.tooltipEvents,function(t){var i="mouseout"!==t.type?this.getPointsAtEvent(t):[];this.eachPoints(function(t){t.restore(["fillColor","strokeColor"])}),e.each(i,function(t){t.fillColor=t.highlightFill,t.strokeColor=t.highlightStroke}),this.showTooltip(i)}),e.each(t.datasets,function(i){var s={label:i.label||null,fillColor:i.fillColor,strokeColor:i.strokeColor,pointColor:i.pointColor,pointStrokeColor:i.pointStrokeColor,points:[]};this.datasets.push(s),e.each(i.data,function(e,n){s.points.push(new this.PointClass({value:e,label:t.labels[n],datasetLabel:i.label,strokeColor:i.pointStrokeColor,fillColor:i.pointColor,highlightFill:i.pointHighlightFill||i.pointColor,highlightStroke:i.pointHighlightStroke||i.pointStrokeColor}))},this),this.buildScale(t.labels),this.eachPoints(function(t,i){e.extend(t,{x:this.scale.calculateX(i),y:this.scale.endPoint}),t.save()},this)},this),this.render()},update:function(){this.scale.update(),e.each(this.activeElements,function(t){t.restore(["fillColor","strokeColor"])}),this.eachPoints(function(t){t.save()}),this.render()},eachPoints:function(t){e.each(this.datasets,function(i){e.each(i.points,t,this)},this)},getPointsAtEvent:function(t){var i=[],s=e.getRelativePosition(t);return e.each(this.datasets,function(t){e.each(t.points,function(t){t.inRange(s.x,s.y)&&i.push(t)})},this),i},buildScale:function(t){var s=this,n=function(){var t=[];return s.eachPoints(function(i){t.push(i.value)}),t},o={templateString:this.options.scaleLabel,height:this.chart.height,width:this.chart.width,ctx:this.chart.ctx,textColor:this.options.scaleFontColor,fontSize:this.options.scaleFontSize,fontStyle:this.options.scaleFontStyle,fontFamily:this.options.scaleFontFamily,valuesCount:t.length,beginAtZero:this.options.scaleBeginAtZero,integersOnly:this.options.scaleIntegersOnly,calculateYRange:function(t){var i=e.calculateScaleRange(n(),t,this.fontSize,this.beginAtZero,this.integersOnly);e.extend(this,i)},xLabels:t,font:e.fontString(this.options.scaleFontSize,this.options.scaleFontStyle,this.options.scaleFontFamily),lineWidth:this.options.scaleLineWidth,lineColor:this.options.scaleLineColor,showHorizontalLines:this.options.scaleShowHorizontalLines,showVerticalLines:this.options.scaleShowVerticalLines,gridLineWidth:this.options.scaleShowGridLines?this.options.scaleGridLineWidth:0,gridLineColor:this.options.scaleShowGridLines?this.options.scaleGridLineColor:"rgba(0,0,0,0)",padding:this.options.showScale?0:this.options.pointDotRadius+this.options.pointDotStrokeWidth,showLabels:this.options.scaleShowLabels,display:this.options.showScale};this.options.scaleOverride&&e.extend(o,{calculateYRange:e.noop,steps:this.options.scaleSteps,stepValue:this.options.scaleStepWidth,min:this.options.scaleStartValue,max:this.options.scaleStartValue+this.options.scaleSteps*this.options.scaleStepWidth}),this.scale=new i.Scale(o)},addData:function(t,i){e.each(t,function(t,e){this.datasets[e].points.push(new this.PointClass({value:t,label:i,x:this.scale.calculateX(this.scale.valuesCount+1),y:this.scale.endPoint,strokeColor:this.datasets[e].pointStrokeColor,fillColor:this.datasets[e].pointColor}))},this),this.scale.addXLabel(i),this.update()},removeData:function(){this.scale.removeXLabel(),e.each(this.datasets,function(t){t.points.shift()},this),this.update()},reflow:function(){var t=e.extend({height:this.chart.height,width:this.chart.width});this.scale.update(t)},draw:function(t){var i=t||1;this.clear();var s=this.chart.ctx,n=function(t){return null!==t.value},o=function(t,i,s){return e.findNextWhere(i,n,s)||t},a=function(t,i,s){return e.findPreviousWhere(i,n,s)||t};this.scale.draw(i),e.each(this.datasets,function(t){var h=e.where(t.points,n);e.each(t.points,function(t,e){t.hasValue()&&t.transition({y:this.scale.calculateY(t.value),x:this.scale.calculateX(e)},i)},this),this.options.bezierCurve&&e.each(h,function(t,i){var s=i>0&&i<h.length-1?this.options.bezierCurveTension:0;t.controlPoints=e.splineCurve(a(t,h,i),t,o(t,h,i),s),t.controlPoints.outer.y>this.scale.endPoint?t.controlPoints.outer.y=this.scale.endPoint:t.controlPoints.outer.y<this.scale.startPoint&&(t.controlPoints.outer.y=this.scale.startPoint),t.controlPoints.inner.y>this.scale.endPoint?t.controlPoints.inner.y=this.scale.endPoint:t.controlPoints.inner.y<this.scale.startPoint&&(t.controlPoints.inner.y=this.scale.startPoint)},this),s.lineWidth=this.options.datasetStrokeWidth,s.strokeStyle=t.strokeColor,s.beginPath(),e.each(h,function(t,i){if(0===i)s.moveTo(t.x,t.y);else if(this.options.bezierCurve){var e=a(t,h,i);s.bezierCurveTo(e.controlPoints.outer.x,e.controlPoints.outer.y,t.controlPoints.inner.x,t.controlPoints.inner.y,t.x,t.y)}else s.lineTo(t.x,t.y)},this),s.stroke(),this.options.datasetFill&&h.length>0&&(s.lineTo(h[h.length-1].x,this.scale.endPoint),s.lineTo(h[0].x,this.scale.endPoint),s.fillStyle=t.fillColor,s.closePath(),s.fill()),e.each(h,function(t){t.draw()})},this)}})}.call(this),function(){"use strict";var t=this,i=t.Chart,e=i.helpers,s={scaleShowLabelBackdrop:!0,scaleBackdropColor:"rgba(255,255,255,0.75)",scaleBeginAtZero:!0,scaleBackdropPaddingY:2,scaleBackdropPaddingX:2,scaleShowLine:!0,segmentShowStroke:!0,segmentStrokeColor:"#fff",segmentStrokeWidth:2,animationSteps:100,animationEasing:"easeOutBounce",animateRotate:!0,animateScale:!1,legendTemplate:'<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<segments.length; i++){%><li><span style="background-color:<%=segments[i].fillColor%>"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>'};i.Type.extend({name:"PolarArea",defaults:s,initialize:function(t){this.segments=[],this.SegmentArc=i.Arc.extend({showStroke:this.options.segmentShowStroke,strokeWidth:this.options.segmentStrokeWidth,strokeColor:this.options.segmentStrokeColor,ctx:this.chart.ctx,innerRadius:0,x:this.chart.width/2,y:this.chart.height/2}),this.scale=new i.RadialScale({display:this.options.showScale,fontStyle:this.options.scaleFontStyle,fontSize:this.options.scaleFontSize,fontFamily:this.options.scaleFontFamily,fontColor:this.options.scaleFontColor,showLabels:this.options.scaleShowLabels,showLabelBackdrop:this.options.scaleShowLabelBackdrop,backdropColor:this.options.scaleBackdropColor,backdropPaddingY:this.options.scaleBackdropPaddingY,backdropPaddingX:this.options.scaleBackdropPaddingX,lineWidth:this.options.scaleShowLine?this.options.scaleLineWidth:0,lineColor:this.options.scaleLineColor,lineArc:!0,width:this.chart.width,height:this.chart.height,xCenter:this.chart.width/2,yCenter:this.chart.height/2,ctx:this.chart.ctx,templateString:this.options.scaleLabel,valuesCount:t.length}),this.updateScaleRange(t),this.scale.update(),e.each(t,function(t,i){this.addData(t,i,!0)},this),this.options.showTooltips&&e.bindEvents(this,this.options.tooltipEvents,function(t){var i="mouseout"!==t.type?this.getSegmentsAtEvent(t):[];e.each(this.segments,function(t){t.restore(["fillColor"])}),e.each(i,function(t){t.fillColor=t.highlightColor}),this.showTooltip(i)}),this.render()},getSegmentsAtEvent:function(t){var i=[],s=e.getRelativePosition(t);return e.each(this.segments,function(t){t.inRange(s.x,s.y)&&i.push(t)},this),i},addData:function(t,i,e){var s=i||this.segments.length;this.segments.splice(s,0,new this.SegmentArc({fillColor:t.color,highlightColor:t.highlight||t.color,label:t.label,value:t.value,outerRadius:this.options.animateScale?0:this.scale.calculateCenterOffset(t.value),circumference:this.options.animateRotate?0:this.scale.getCircumference(),startAngle:1.5*Math.PI})),e||(this.reflow(),this.update())},removeData:function(t){var i=e.isNumber(t)?t:this.segments.length-1;this.segments.splice(i,1),this.reflow(),this.update()},calculateTotal:function(t){this.total=0,e.each(t,function(t){this.total+=t.value},this),this.scale.valuesCount=this.segments.length},updateScaleRange:function(t){var i=[];e.each(t,function(t){i.push(t.value)});var s=this.options.scaleOverride?{steps:this.options.scaleSteps,stepValue:this.options.scaleStepWidth,min:this.options.scaleStartValue,max:this.options.scaleStartValue+this.options.scaleSteps*this.options.scaleStepWidth}:e.calculateScaleRange(i,e.min([this.chart.width,this.chart.height])/2,this.options.scaleFontSize,this.options.scaleBeginAtZero,this.options.scaleIntegersOnly);e.extend(this.scale,s,{size:e.min([this.chart.width,this.chart.height]),xCenter:this.chart.width/2,yCenter:this.chart.height/2})},update:function(){this.calculateTotal(this.segments),e.each(this.segments,function(t){t.save()}),this.render()},reflow:function(){e.extend(this.SegmentArc.prototype,{x:this.chart.width/2,y:this.chart.height/2}),this.updateScaleRange(this.segments),this.scale.update(),e.extend(this.scale,{xCenter:this.chart.width/2,yCenter:this.chart.height/2}),e.each(this.segments,function(t){t.update({outerRadius:this.scale.calculateCenterOffset(t.value)})},this)},draw:function(t){var i=t||1;this.clear(),e.each(this.segments,function(t,e){t.transition({circumference:this.scale.getCircumference(),outerRadius:this.scale.calculateCenterOffset(t.value)},i),t.endAngle=t.startAngle+t.circumference,0===e&&(t.startAngle=1.5*Math.PI),e<this.segments.length-1&&(this.segments[e+1].startAngle=t.endAngle),t.draw()},this),this.scale.draw()}})}.call(this),function(){"use strict";var t=this,i=t.Chart,e=i.helpers;i.Type.extend({name:"Radar",defaults:{scaleShowLine:!0,angleShowLineOut:!0,scaleShowLabels:!1,scaleBeginAtZero:!0,angleLineColor:"rgba(0,0,0,.1)",angleLineWidth:1,pointLabelFontFamily:"'Arial'",pointLabelFontStyle:"normal",pointLabelFontSize:10,pointLabelFontColor:"#666",pointDot:!0,pointDotRadius:3,pointDotStrokeWidth:1,pointHitDetectionRadius:20,datasetStroke:!0,datasetStrokeWidth:2,datasetFill:!0,legendTemplate:'<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].strokeColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>'},initialize:function(t){this.PointClass=i.Point.extend({strokeWidth:this.options.pointDotStrokeWidth,radius:this.options.pointDotRadius,display:this.options.pointDot,hitDetectionRadius:this.options.pointHitDetectionRadius,ctx:this.chart.ctx}),this.datasets=[],this.buildScale(t),this.options.showTooltips&&e.bindEvents(this,this.options.tooltipEvents,function(t){var i="mouseout"!==t.type?this.getPointsAtEvent(t):[];this.eachPoints(function(t){t.restore(["fillColor","strokeColor"])}),e.each(i,function(t){t.fillColor=t.highlightFill,t.strokeColor=t.highlightStroke}),this.showTooltip(i)}),e.each(t.datasets,function(i){var s={label:i.label||null,fillColor:i.fillColor,strokeColor:i.strokeColor,pointColor:i.pointColor,pointStrokeColor:i.pointStrokeColor,points:[]};this.datasets.push(s),e.each(i.data,function(e,n){var o;this.scale.animation||(o=this.scale.getPointPosition(n,this.scale.calculateCenterOffset(e))),s.points.push(new this.PointClass({value:e,label:t.labels[n],datasetLabel:i.label,x:this.options.animation?this.scale.xCenter:o.x,y:this.options.animation?this.scale.yCenter:o.y,strokeColor:i.pointStrokeColor,fillColor:i.pointColor,highlightFill:i.pointHighlightFill||i.pointColor,highlightStroke:i.pointHighlightStroke||i.pointStrokeColor}))},this)},this),this.render()},eachPoints:function(t){e.each(this.datasets,function(i){e.each(i.points,t,this)},this)},getPointsAtEvent:function(t){var i=e.getRelativePosition(t),s=e.getAngleFromPoint({x:this.scale.xCenter,y:this.scale.yCenter},i),n=2*Math.PI/this.scale.valuesCount,o=Math.round((s.angle-1.5*Math.PI)/n),a=[];return(o>=this.scale.valuesCount||0>o)&&(o=0),s.distance<=this.scale.drawingArea&&e.each(this.datasets,function(t){a.push(t.points[o])}),a},buildScale:function(t){this.scale=new i.RadialScale({display:this.options.showScale,fontStyle:this.options.scaleFontStyle,fontSize:this.options.scaleFontSize,fontFamily:this.options.scaleFontFamily,fontColor:this.options.scaleFontColor,showLabels:this.options.scaleShowLabels,showLabelBackdrop:this.options.scaleShowLabelBackdrop,backdropColor:this.options.scaleBackdropColor,backdropPaddingY:this.options.scaleBackdropPaddingY,backdropPaddingX:this.options.scaleBackdropPaddingX,lineWidth:this.options.scaleShowLine?this.options.scaleLineWidth:0,lineColor:this.options.scaleLineColor,angleLineColor:this.options.angleLineColor,angleLineWidth:this.options.angleShowLineOut?this.options.angleLineWidth:0,pointLabelFontColor:this.options.pointLabelFontColor,pointLabelFontSize:this.options.pointLabelFontSize,pointLabelFontFamily:this.options.pointLabelFontFamily,pointLabelFontStyle:this.options.pointLabelFontStyle,height:this.chart.height,width:this.chart.width,xCenter:this.chart.width/2,yCenter:this.chart.height/2,ctx:this.chart.ctx,templateString:this.options.scaleLabel,labels:t.labels,valuesCount:t.datasets[0].data.length}),this.scale.setScaleSize(),this.updateScaleRange(t.datasets),this.scale.buildYLabels()},updateScaleRange:function(t){var i=function(){var i=[];return e.each(t,function(t){t.data?i=i.concat(t.data):e.each(t.points,function(t){i.push(t.value)})}),i}(),s=this.options.scaleOverride?{steps:this.options.scaleSteps,stepValue:this.options.scaleStepWidth,min:this.options.scaleStartValue,max:this.options.scaleStartValue+this.options.scaleSteps*this.options.scaleStepWidth}:e.calculateScaleRange(i,e.min([this.chart.width,this.chart.height])/2,this.options.scaleFontSize,this.options.scaleBeginAtZero,this.options.scaleIntegersOnly);e.extend(this.scale,s)},addData:function(t,i){this.scale.valuesCount++,e.each(t,function(t,e){var s=this.scale.getPointPosition(this.scale.valuesCount,this.scale.calculateCenterOffset(t));this.datasets[e].points.push(new this.PointClass({value:t,label:i,x:s.x,y:s.y,strokeColor:this.datasets[e].pointStrokeColor,fillColor:this.datasets[e].pointColor}))},this),this.scale.labels.push(i),this.reflow(),this.update()},removeData:function(){this.scale.valuesCount--,this.scale.labels.shift(),e.each(this.datasets,function(t){t.points.shift()},this),this.reflow(),this.update()},update:function(){this.eachPoints(function(t){t.save()}),this.reflow(),this.render()},reflow:function(){e.extend(this.scale,{width:this.chart.width,height:this.chart.height,size:e.min([this.chart.width,this.chart.height]),xCenter:this.chart.width/2,yCenter:this.chart.height/2}),this.updateScaleRange(this.datasets),this.scale.setScaleSize(),this.scale.buildYLabels()},draw:function(t){var i=t||1,s=this.chart.ctx;this.clear(),this.scale.draw(),e.each(this.datasets,function(t){e.each(t.points,function(t,e){t.hasValue()&&t.transition(this.scale.getPointPosition(e,this.scale.calculateCenterOffset(t.value)),i)},this),s.lineWidth=this.options.datasetStrokeWidth,s.strokeStyle=t.strokeColor,s.beginPath(),e.each(t.points,function(t,i){0===i?s.moveTo(t.x,t.y):s.lineTo(t.x,t.y)},this),s.closePath(),s.stroke(),s.fillStyle=t.fillColor,s.fill(),e.each(t.points,function(t){t.hasValue()&&t.draw()})},this)}})}.call(this);

          //var directionsDisplay;
	  //var directionsService = new google.maps.DirectionsService();
        /*  google.maps.event.addDomListener(window, 'load', displayCurrentStatus);

     function displayCurrentStatus()
    {
            var rendererOptions = {
            map: maptest,
            suppressMarkers : true
  
             }
    directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);    
}*/
          var stoppageIdleTime='<?php echo $stoppage; ?>';
          var timeinterval='<?php echo $timeinterval; ?>';
           var overspeed='<?php echo $overspeed; ?>';
           var ignition='<?php echo $ignition; ?>';
          var mainpower='<?php echo $mainpower; ?>';
          var islive='<?php echo $islive; ?>'; 
          var numrows='<?php echo $numrows; ?>'; 
           var eventData='<?php echo $data; ?>'+""; 
           var zone='<?php echo $zones; ?>'; 
           var factory='<?php echo $factory; ?>'; 
          // alert(factory);             
// var x=factory.split("][");
  //         var b=x[1];
            var baseloc='<?php echo $baseloc; ?>';
          // alert(baseloc);

            var name='<?php echo $name; ?>'; 
           var destination='<?php echo $destination; ?>';
           var nearestGeozoneID='<?php echo $nearestGeozoneID; ?>';
           var sapdis='<?php echo $sapDis; ?>'; 
           var gtsDis='<?php echo $gtsDis; ?>';
           //var lat1='<?php echo $lat; ?>';
           // var long1='<?php echo $long; ?>';
           var waitTime='<?php echo $waitTime; ?>'; 
           var travelDistance='<?php echo $travelDistance; ?>'; 
           var WaitLocation='<?php echo $WaitLocation; ?>'; 
           var distanceTravelled='<?php echo $distanceTravelled; ?>'; 
           var vehicleID='<?php echo $vehicleID; ?>'; 
           var accountID='<?php echo $accountID; ?>';

           var latitude='<?php echo $latitude; ?>';
           var longitude='<?php echo $longitude; ?>';
           var idle='<?php echo $idle; ?>';
           var idlelat='<?php echo $idlelat; ?>'+"";
              var idlelat1=idlelat.split(',');
           var idlelong='<?php echo $idlelong; ?>'+"";
              var  idlelong1=idlelong.split(',');
            var slatlong='<?php echo $startlatlong; ?>'+"";
 var groupID='<?php echo $groupID; ?>'+"";
          
      //     alert(slatlong); 
            var slat=slatlong.split("-")[0];
           var slong=slatlong.split("-")[1];
           var endlatlong='<?php echo $endlatlong; ?>'+"";
           var elat=endlatlong.split("-")[0];
           var elong=endlatlong.split("-")[1];
           var allzones="one";
         var dummymarker; 
          // var event = eventData.split('*'); 
          // var zones = zone.split('*'); 
           google.maps.event.addDomListener(window, 'load', initialise4);
            var map;
var directionsService;
           var tochZones=[];
           var drawingManager;
//google.maps.event.addDomListener(window, 'load', displayCurrentStatus);
   document.getElementById("loadimg").style.display="none";
           function initialise4() {


           if(stoppageIdleTime!=0){
               document.getElementById("stpid").checked=true;
               document.getElementById("sid").value=stoppageIdleTime;
           }
           if(timeinterval!=0){
               document.getElementById("tiid").checked=true;
               document.getElementById("tid").value=timeinterval;
           }
           if(overspeed!=0){
               document.getElementById("osid").checked=true;
               document.getElementById("sid").value=overspeed;
           }
           if(ignition=="yes"){
               document.getElementById("igid").checked=true;
           }
           if(mainpower=="yes"){
               document.getElementById("mpid").checked=true;
           }
           // var zone='<?php echo $zones; ?>';
          $('#helpdiv').slideToggle('slow');
               var zones = zone.split('*');
             //  var eventData='<?php echo $data; ?>'+"";
               var event = eventData.split('*');
              var centr=event[0].split('^');
              var mapOptions = {
                  zoom: 12,
                  mapTypeControlOptions: {
                        mapTypeIds: [google.maps.MapTypeId.SATELLITE, google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.HYBRID],
                        position: google.maps.ControlPosition.RIGHT_CENTER
                    },

                  center: new google.maps.LatLng(parseFloat(centr[2]),parseFloat(centr[3]))
               };
               var mapDiv = document.getElementById('mapcontainer');
                map = new google.maps.Map(mapDiv, mapOptions);
                 directionsService = new google.maps.DirectionsService();

               document.getElementById('fromdate').value='<?php echo $fdate; ?>'; 
               document.getElementById('todate').value='<?php echo $tdate; ?>';

               //creating circle
                if(idle=="yes"){
                    pushpin(map,latitude,longitude,'Idle Point',"yes",100);
                }   
                //display statrt location
               //  var info="Asset :"+vehicleID+"  &nbsp"+name+"<br>Starting Locaton :"+factory+"<br>Travell Distance:"+travelDistance;
              //pushpin(map,slat,slong,info,"yes",100);
                //display ending location
             if(isNaN(travelDistance) || travelDistance==undefined){
                travelDistance=0;
              }
              if(slat==undefined) slat=0;
              if(slong==undefined) slong=0;
              if(elat==undefined)elat=0;
              if(elong==undefined) elong=0; 
//	document.getElementById('info').innerHTML="<h1 style='font-size:13;color:black;font-family:Arial,Verdana,sans-serif'>Vehicle ID : "+vehicleID.toUpperCase()+"</h1><h1 style='font-size:13;color:black;font-family:Arial,Verdana,sans-serif'>Distance : "+parseInt(travelDistance)+" Km</h1><h1 style='font-size:13;color:black;font-family:Arial,Verdana,sans-serif'>S lat/lng :"+slat+"/"+slong+"</h1><h1 style='font-size:13;color:black;font-family:Arial,Verdana,sans-serif'>E lat/lng:"+elat+"/"+elong+"</h1>";
               //document.getElementById('info').innerHTML="<table class='tablecss'><tr><td>Vehicle ID :</td><td> "+vehicleID.toUpperCase()+"</td></tr><tr><td>Distance : </td><td>"+parseInt(travelDistance)+" Km</td></tr><tr><td>S lat/lng :</td><td>"+slat+"/"+slong+"</td></tr><tr><td>E lat/lng:</td><td>"+elat+"/"+elong+"</td></tr></table>";
           

                pushpin(map,elat,elong,"Ending : "+destination,"yes",100);
                //display all idle poins
                displayidles(map);
   //           displayCurrentStatus(); //google driving path showing on map 
                drawingManager = new google.maps.drawing.DrawingManager({
                   //drawingMode: google.maps.drawing.OverlayType.MARKER,
                   drawingControl: true,
                   drawingControlOptions: {
                      position: google.maps.ControlPosition.TOP_CENTER,
                           drawingModes: [
                              // google.maps.drawing.OverlayType.MARKER,
                               google.maps.drawing.OverlayType.CIRCLE,
                               //google.maps.drawing.OverlayType.POLYGON,
                               //google.maps.drawing.OverlayType.POLYLINE,
                               //google.maps.drawing.OverlayType.RECTANGLE
                           ]
                   },
    
                  circleOptions: {
                     fillColor: '#ffff00',
                     fillOpacity: 0.12,
                     strokeWeight: 3,
                     clickable: true,
                     editable: true,
                     zIndex: 1
                  }
               });



/*
               //drawingManager.setMap(map);    
               google.maps.event.addListener(drawingManager, 'circlecomplete', function(circle) {
               var radius = circle.getRadius(),
               center = circle.getCenter();
               var r = confirm("Do You Want Save?");
               if (r == true) {
                 var zoneID = prompt("Enter Zone Name(without spaces)", "fill1");
                 var color = prompt("Enter Color", "pink/blue");
                 var queryString="insertZone.php?accountID="+accountID+"&lat="+circle.getCenter().lat()+"&long="+circle.getCenter().lng()+"&radius="+circle.getRadius()+"&zoneID="+zoneID+"&color="+color;
                 //alert(queryString);
                 window.open(''+queryString,'zoneCreate','left=300,top=300,width=500px,height=50px,toolbar=1,resizable=0');



                
               }
                 google.maps.event.addListener(circle, 'rightclick', function(ev){
         
                circle.setMap(null);
 
               });
 // google.maps.event.addDomListener(window, 'load', displayCurrentStatus);      
              google.maps.event.addListener(circle, 'click', function(ev){
              // circle.setMap(null);
              var r = confirm("Do You Want Save?");
              if (r == true) {
                  var zoneID = prompt("Enter Zone Name(without spaces)", "fill1");
                   var color = prompt("Enter Color", "pink/blue");
                  var queryString="insertZone.php?accountID="+accountID+"&lat="+circle.getCenter().lat()+"&long="+circle.getCenter().lng()+"&radius="+circle.getRadius()+"&zoneID="+zoneID+"&color="+color;
                  //alert(queryString);
                   window.open(''+queryString,'zoneCreate','left=300,top=300,width=500px,height=50px,toolbar=0,resizable=0');
              }
      
           });
     
        });

  */             //circleCreation(lat1,long1,map,'#5882FA',0.8);
              // var waitLocation="WaitLocation: "+WaitLocation+"<br> wait Time:"+waitTime+";<br>GTS Distance :"+distanceTravelled+" Km";
              // pushpin(map,lat1,long1,waitLocation,"yes");
        //  if(islive!="yes"){ 
          var lineSymbol = {
                  path: 'M 1,1 1,1',
                  strokeOpacity: 1,
                  scale: 1
               };
            lineSymbol = {
          path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW
        };

            var degree=0;
            var vehicleStatus=0;
            var rawdata="<center><table id='rawdatareport' class='tablecss'><thead><th>Device ID</th><th>Speed Km/p</th><th>Time</th><th>Current Location</th><th>Lat</th><th>Lng</th><th>Distance(Km)</th><th>Vehicle Status</th><th>Direction(degree)</th><th>Event Status</th><th>Event Duration</th></thead><tbody>";
            var cumulativeDistance=0;
            var timeintaveldiff=0;
            var graphDatax=[];//{ label: "Jan",  y: 5.28 },
           var graphDatay=[];
             for(var m=1;m<event.length-1;m++){
                    var evntdata=event[m].split('^');
                     var evntdata2=event[m-1].split('^')
                      degree=evntdata[8];
                      vehicleStatus=evntdata[9];
                      var statusdis="Stopped";
                      if(vehicleStatus=="61714")statusdis="Moving";
                      else if(vehicleStatus=="61718")statusdis="Idle";
                      else if(vehicleStatus=="62467")statusdis="Ignition Off";
                      else if(vehicleStatus=="62465")statusdis="Ignition On";
                       if(m==0){evntdata[5]=0;}
                      
                      cumulativeDistance +=parseFloat(evntdata[5]);
                        graphDatax.push(evntdata[1]);
                        graphDatay.push(parseInt(evntdata[6]));
                       rawdata +="<tr><td>"+vehicleID.toUpperCase()+"</td><td>"+parseInt(evntdata[6])+"</td><td>"+evntdata[1]+"</td><td>"+evntdata[10]+"</td><td>"+parseFloat(evntdata[2]).toFixed(5)+"</td><td>"+parseFloat(evntdata[3]).toFixed(5)+"</td><td>"+parseFloat(cumulativeDistance).toFixed(2) +"</td><td>"+statusdis+"</td><td>"+evntdata[8]+"</td><td>"+evntdata[12]+"</td><td>"+evntdata[13]+"</td></tr>";
               //  if(statusdis=="Moving" ){
                    if(parseFloat(evntdata[5])>3){
                        // alert(parseFloat(evntdata[5]));
                          calcRoute(evntdata[2],evntdata[3],evntdata2[2],evntdata2[3]);
                       //  replayPath(evntdata2[2],evntdata2[3],lineSymbol,map,evntdata[2],evntdata[3]); 
                     }else{
                       replayPath(evntdata2[2],evntdata2[3],lineSymbol,map,evntdata[2],evntdata[3]);
                     }
                //  }
//vehicleTrack(evntdata[2],evntdata[3],lineSymbol,map,evntdata[6],"no",evntdata[7],"Address:"+evntdata[10]+"<br>Time:"+evntdata[1]+"<br>Speed:"+evntdata[6]+" Kmph");
 
                  if(evntdata[11]=="Ignition Off" || evntdata[11]=="Ignition On" || evntdata[11]=="Main Power OFF" || evntdata[11]=="Main Power ON" || evntdata[11]=="Over Speed" || evntdata[11]=="Time Interval" || evntdata[11]=="Idle Point"){
                    vehicleTrack(evntdata[2],evntdata[3],lineSymbol,map,evntdata[6],"yes",evntdata[7],evntdata[12]);
                   replayPath(evntdata2[2],evntdata2[3],lineSymbol,map,evntdata[2],evntdata[3]);
               }
              } 
            rawdata +="</tbody></table></center>";
            document.getElementById("rawdata").innerHTML = rawdata;
           var info="Asset :"+vehicleID+"  &nbsp"+name+"<br>Starting Locaton :"+factory+"<br>Travelled Distance: "+parseFloat(cumulativeDistance).toFixed(2)+" Kms";
              pushpin(map,slat,slong,info,"yes",100);


                         document.getElementById('info').innerHTML="<table class='tablecss'><tr><td>Vehicle ID :</td><td> "+vehicleID.toUpperCase()+"</td></tr><tr><td>Distance : </td><td>"+parseFloat(cumulativeDistance).toFixed(2)+" Kms</td></tr><tr><td>S lat/lng :</td><td>"+slat+"/"+slong+"</td></tr><tr><td>E lat/lng:</td><td>"+elat+"/"+elong+"</td></tr><tr><td>B Location:</td><td>"+baseloc+"</td></tr><tr><td>District:</td><td>"+groupID+"</td></tr></table>";
            var image="../images1/idle.png";
             if(vehicleStatus=='61714')image="../images1/online.png";
         /*   var markerImage = new google.maps.MarkerImage(RotateIcon
            .makeIcon(
                image)
            .setRotation({deg: degree})
            .getUrl(),
                new google.maps.Size(60, 60),
                new google.maps.Point(0, 0),
                new google.maps.Point(5, 5));
*/
             pushpin(map,elat,elong,"Vehicle Current Location",'yes',1000,"../images/vehicle.png");
           //    pushpin(map,elat,elong,"Vehicle Current Location",'yes',1000,markerImage);
               var position = new google.maps.LatLng(parseFloat(elat),parseFloat(elong));
                 gifmarker = new google.maps.Marker({
                   position: position,
                   map: map,
                   optimized: false,
                   icon:"http://glotrax.glovision.co:8080/track/extra/images/pp/CrosshairRed.gif",  
                   visible: true
               });


           /*
                graph
 
            */

  var areaChartData = {
          labels:graphDatax ,
          datasets: [
            {
              label: "Time Vs Speed",
              fillColor: "rgba(60,141,188,0.9)",
              strokeColor: "rgba(60,141,188,0.8)",
              pointColor: "#3b8bba",
              pointStrokeColor: "rgba(60,141,188,1)",
              pointHighlightFill: "#fff",
              pointHighlightStroke: "rgba(60,141,188,1)",
              data: graphDatay
            }
          ]
        };

        var areaChartOptions = {


          //Boolean - If we should show the scale at all
          showScale:false,
          //Boolean - Whether grid lines are shown across the chart
          scaleShowGridLines: true,
          //String - Colour of the grid lines
          scaleGridLineColor: "rgba(0,0,0,.05)",
          //Number - Width of the grid lines
          scaleGridLineWidth: 1,
          //Boolean - Whether to show horizontal lines (except X axis)
          scaleShowHorizontalLines: true,
          //Boolean - Whether to show vertical lines (except Y axis)
          scaleShowVerticalLines: true,
          //Boolean - Whether the line is curved between points
          bezierCurve: true,
          //Number - Tension of the bezier curve between points
          bezierCurveTension: 0.3,
          //Boolean - Whether to show a dot for each point
          pointDot: false,
          //Number - Radius of each point dot in pixels
          pointDotRadius: 1,
          //Number - Pixel width of point dot stroke
          pointDotStrokeWidth: 1,
          //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
          pointHitDetectionRadius: 1,
          //Boolean - Whether to show a stroke for datasets
          datasetStroke: false,
          //Number - Pixel width of dataset stroke
          datasetStrokeWidth: 2,
          //Boolean - Whether to fill the dataset with a color
          datasetFill: true,
          //String - A legend template
          legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
          //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
          maintainAspectRatio: false,
          //Boolean - whether to make the chart responsive to window resizing
          responsive: true,
        };

        //Create the line chart
        
        //-------------
        //- LINE CHART -
        //--------------
        var lineChartCanvas = $("#lineChart").get(0).getContext("2d");
        var lineChart = new Chart(lineChartCanvas);
        var lineChartOptions = areaChartOptions;
        lineChartOptions.datasetFill = false;
        lineChart.Line(areaChartData, lineChartOptions);

             
           //  atob();
              gonesput1(event,zones);
                  }


function calcRoute(slat,slng,elat,elng) {


var ss = new google.maps.LatLng(parseFloat(slat),parseFloat(slng));
 var ee = new google.maps.LatLng(parseFloat(elat),parseFloat(elng));
  var travelMode = google.maps.DirectionsTravelMode.WALKING;

  var request = {
    origin: ss,
    destination: ee,
    travelMode: travelMode
  };
directionsService.route(request, function(response, status) {
    if (status == google.maps.DirectionsStatus.OK) {
      //directionsDisplay.setDirections(response);

      var bounds = new google.maps.LatLngBounds();
      var route = response.routes[0];
      startLocation = new Object();
      endLocation = new Object();

     
      var path = response.routes[0].overview_path;
      var legs = response.routes[0].legs;
      for (i = 0; i < legs.length; i++) {
        if (i === 0) {
          startLocation.latlng = legs[i].start_location;
          startLocation.address = legs[i].start_address;
}
        endLocation.latlng = legs[i].end_location;
        endLocation.address = legs[i].end_address;
        var steps = legs[i].steps;
         var lineSymbol = {
                   path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
                  //  strokeColor: '#2E2EFE'
                   };
         var lineCoordinates;
        for (j = 0; j < steps.length; j++) {
          var nextSegment = steps[j].path;
          for (k = 1; k < nextSegment.length; k++) {
             if(k==0){
               lineCoordinates = [
                       legs[i].start_location,
                       nextSegment[k]
                     ];
                 }else{
                    lineCoordinates = [
                       nextSegment[k-1],
                       nextSegment[k]
                     ];
 
                
                    }    
                 var line = new google.maps.Polyline({
                   path: lineCoordinates,
                     icons: [{
                          icon: lineSymbol,
                          offset: '100%'
                       }],
                       map: map
                  });

          }
}
      }
     // map.setZoom(18);
    }
  });
}



function vehicleTrack(lat,long,lineSymbol,map,speed,status1,direction,dis){

  

             speed=parseInt(speed);
             var pincolor='yellow';
             if(speed>20 && speed<40){
                 pincolor='green';
             }else if(speed>40){
                   pincolor='orange';
             }
             var directionimage='../images1/pin30_'+pincolor+"_"+direction+".png";
//alert(directionimage);
             
 
          /*

             var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=.|" +pincolor,new google.maps.Size(30, 40),
        new google.maps.Point(0,0),
        new google.maps.Point(24, 24));
           */
        
             pushpin(map,lat,long,dis,status1,1000,directionimage);
      }



var RotateIcon = function(options){
    this.options = options || {};
    this.rImg = options.img || new Image();
    this.rImg.src = this.rImg.src || this.options.url || '';
 
   this.options.width = this.options.width || this.rImg.width || 0;
    this.options.height = this.options.height || this.rImg.height || 0;
  var  canvas = document.createElement("canvas");
    canvas.width =60;
    canvas.height =60;

    this.context = canvas.getContext("2d");
    this.canvas = canvas;
};
RotateIcon.makeIcon = function(url) {
    return new RotateIcon({url: url});
};
RotateIcon.prototype.setRotation = function(options){
    var canvas = this.context,
        angle = options.deg ? options.deg * Math.PI / 180:
            options.rad,
        centerX = this.options.width/2+9,
        centerY = this.options.height/2;

    canvas.clearRect(0, 0, this.options.width, this.options.height);
   canvas.save();
    canvas.translate(centerX, centerY);
    canvas.rotate(angle);
    canvas.translate(-centerX, -centerY);
    canvas.drawImage(this.rImg, 0, 0);
    canvas.restore();
    return this;
};
RotateIcon.prototype.getUrl = function(){
    return this.canvas.toDataURL('image/png');
};

function replayPath(lat,long,lineSymbol,map,lat2,long2){
              var lineCoordinates = [
              new google.maps.LatLng(parseFloat(lat),parseFloat(long)),
              new google.maps.LatLng(parseFloat(lat2),parseFloat(long2))
          ];
          var line = new google.maps.Polyline({
              icons: [{
                  icon: lineSymbol,
                  offset: '100%'
              }],
              path: lineCoordinates,
              geodesic: true,
              
             // strokeColor: '#4e5eb0',
              strokeOpacity: 1.0,
              strokeWeight: 2
              });
 
          var pt = new google.maps.LatLng(lat,long);
            map.setCenter(pt);
         //  map.setZoom(12);
            line.setMap(map);
      }



function pushpin(map,lat,lang,zone,status,r,img){

               var position = new google.maps.LatLng(parseFloat(lat),parseFloat(lang));
               var marker =null;
               if(r=="viewpoint"){
                     marker = new MarkerWithLabel({
                     position: position,
                     map:map,
                     icon:img,
                     visible:true,
                     labelText:zone,
                     labelContent:'G',

                     labelAnchor: new google.maps.Point(20, 0),
                     labelClass: "labels", // the CSS class for the label
                     labelStyle: {opacity: 0.75},
                  });

               }else{
                    marker = new google.maps.Marker({
                   position: position,
                   map: map,
                   optimized: false,
                   icon:img,  
                   title:"Zone Id: "+zone,
                   });


              }
              

              // moveBus(map,marker);
               if(r!="viewpoint")
                    dummymarker=marker;
          
               var infowindow = new google.maps.InfoWindow({
                    content: zone
                   });
               if(status=='no'){
                  google.maps.event.addListener(marker, 'click', function() {
                    infowindow.open(map,marker);
                   });
               }else{infowindow.open(map,marker);}
          }


function atob(lat1,long1,lat2,long2,replayornot) {

var DirectionsDisplay;
          var directionsService = new google.maps.DirectionsService();
 var rendererOptions = {
           // map: map,
            //   path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,

          // icons:[{repeat:'50px',icon:{path:google.maps.SymbolPath.FORWARD_CLOSED_ARROW}}],
            suppressMarkers : true,
           //strokeColor: '#8b0013'
             }
var polylineOptionsActual = new google.maps.Polyline({
    strokeColor: '#0000',
    strokeOpacity: 1.0,
    strokeWeight: 2,
     suppressMarkers : true
    });

    directionsDisplay = new google.maps.DirectionsRenderer({ suppressMarkers: true, polylineOptions: polylineOptionsActual });   


 // alert();
  //    getLocation();
            /* var discription='';
         if(atoborbtoa=="atob"){
               discription="From vehicle to person";

           //    distanceinkm= google.maps.geometry.spherical.computeDistanceBetween (start,end);
           }else if(atoborbtoa=="btoa"){
               discription="From person to vehicle";
             //  distanceinkm= google.maps.geometry.spherical.computeDistanceBetween (end,start);
          }
*/
          //document.getElementById('atob').style.opacity=1;
          //document.getElementById('btoa').style.opacity =0.5;

  //           var value=<?php echo json_encode($row); ?>;
            //alert(currlat+' '+currlong);
            var start = new google.maps.LatLng(lat1,long1);
            //var end = new google.maps.LatLng(38.334818, -181.884886);
            var end = new google.maps.LatLng(lat2,long2);
           //alert(value[1]+' '+value[2]);
          //  alert( google.maps.geometry.spherical.computeDistanceBetween (start,end));
            var request = {
              origin: start,
              destination: end,
              travelMode: google.maps.TravelMode.DRIVING
             //  travelMode: google.maps.TravelMode.TRANSIT
            };
  //        map.setZoom(5);
  //  alert();
            directionsService.route(request, function(response, status) {
              if (status == google.maps.DirectionsStatus.OK) {
                 // var distance=response.routes[0].legs[0].distance.value/1000;
                 // if(distance<1){alert("Vehicle Reached");}
                   // document.getElementById('dis').innerHTML="<b>You are here</b> <br>"+distance.toFixed(2)+" Km<br> "+discription;
                
                  

                directionsDisplay.setDirections(response);
                directionsDisplay.setMap(map);
           //     maptest.setZoom(14);

                 /*  if(distance<1){alert("Reached "+distance.toFixed(2)+" Km  "+"Person To Vehicle("+vehicleID+") ");
                             notifyMe("Reached "+distance.toFixed(2)+" Km  "+"Person To Vehicle("+vehicleID+") ");
                   }*/

              } else {
               // alert("Directions Request from " + start.toUrlValue(6) + " to " + end.toUrlValue(6) + " failed: " + status);
              }
            });
/*setTimeout(function() { 
      alert('close'); 
}, 5000);*/
//sleepFor(5000);
if(replayornot=="no")
    alert("close");
          }

function sleepFor( sleepDuration ){
    var now = new Date().getTime();
    while(new Date().getTime() < now + sleepDuration){ /* do nothing */ } 
}

      /*    function pushpin(map,lat,lang,zone,status,r){

               var position = new google.maps.LatLng(parseFloat(lat),parseFloat(lang));
  	       var marker = new google.maps.Marker({
                   position: position,
                   map: map,

                  
                   animation: google.maps.Animation.DROP,
                   title:"Zone Id: "+zone,
                   visible: true
               });
               var infowindow = new google.maps.InfoWindow({
                    content: zone
                   });
               if(status=='no'){
                  google.maps.event.addListener(marker, 'click', function() {
                    infowindow.open(map,marker);
                   });
               }else{infowindow.open(map,marker);}
          }
*/
          function circleCreation(lat,long,map,color,opacity,r,geozone){
                
             var populationOptions = {
                 strokeColor: color,
                 strokeOpacity: 0.5,
                 strokeWeight: 2,
                 fillColor: color,
                 fillOpacity: opacity,
                  //editable: true,
                 map: map,
                 center: new google.maps.LatLng(parseFloat(lat),parseFloat(long)),
                 
                 radius: parseInt(r)
                 
                 };
              // Add the circle for this city to the map.
                
              var cityCircle = new google.maps.Circle(populationOptions);
                    
                var infowindow = new google.maps.InfoWindow({
                content: "Zone Id: "+geozone
                });
               google.maps.event.addListener(cityCircle, 'click', function() {
               
                     infowindow.open(map,cityCircle);
                });
                /*
                //to update Geozone
                 google.maps.event.addListener(cityCircle, 'rightclick', function(ev){
         
                //cityCircle.setMap(null);//delete circle
                  
                  var r = confirm("Do You Want Update?");
                  if (r == true) {
                  var zoneID = geozone;
                   var color = color;
                  var queryString="insertZone.php?accountID="+accountID+"&lat="+cityCircle.getCenter().lat()+"&long="+cityCircle.getCenter().lng()+"&radius="+cityCircle.getRadius()+"&zoneID="+zoneID+"&color="+color;
                  //alert(queryString);
                   window.open(''+queryString,'zoneCreate','left=300,top=300,width=500px,height=50px,toolbar=0,resizable=0');
                 }
 
               });*/

          }
         function distance(lat1, lon1, lat2, lon2, unit) {
	    var radlat1 = Math.PI * lat1/180;
	    var radlat2 = Math.PI * lat2/180;
	    var radlon1 = Math.PI * lon1/180;
	    var radlon2 = Math.PI * lon2/180;
	    var theta = lon1-lon2;
	    var radtheta = Math.PI * theta/180;
	    var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
	    dist = Math.acos(dist);
	    dist = dist * 180/Math.PI;
	    dist = dist * 60 * 1.1515;
	    if (unit=="K") { dist = dist * 1.609344; }
	    if (unit=="N") { dist = dist * 0.8684 ;}
	    return dist;
	}
         function gonesput1(event,zones){
                     var lat1='';
             var long1='';
             var info="Asset :"+vehicleID+"  &nbsp"+name+"<br>Starting Locaton :"+factory+"<br>Travelled Distance:"+parseFloat(travelDistance).toFixed(2);
             //   pushpin(map,slat,slong,info,"yes",100);
               
               // pushpin(map,elat,elong,"Ending : "+destination,"yes",100);
            
             for(var m=0;m<zones.length;m++){
                 
                   
                 var color='green';
                 var opacity=0.3;
                 var zoneId=zones[m].split('^');
                 var evntdata=event[1].split('^');
                  var dis=distance(evntdata[2], evntdata[3], zoneId[1], zoneId[2], "K");
                 if(dis>20){//display zones within 30 km distance
                    continue;
                 }
                 var lat=0;
                 var long=0;
                 for(var zindx=0;zindx<tochZones.length;zindx++){
                   if(zoneId[0]==tochZones[zindx]){
                       pushpin(map,zoneId[1],zoneId[2],zoneId[0],'no',zoneId[3]);
                       color='green';
                       opacity=0.6;
                       circleCreation(zoneId[1],zoneId[2],map,color,opacity,zoneId[3],zoneId[0]);
                      break;
                    }
                 } 
                 
                /* if(zoneId[0]==factory){
                   //alert(zoneId[0].split('.')[1]+"  "+factory.split('.')[1]);
                    var info="VehicleID:"+vehicleID+"<br>Fill Sation :"+zoneId[0]+"<br>Travell Distance:"+travelDistance;
                    pushpin(map,zoneId[1],zoneId[2],info,'yes',zoneId[3]);
                    color='#1200DC';
                    opacity=0.3;
                      circleCreation(zoneId[1],zoneId[2],map,color,opacity,zoneId[3],zoneId[0]);   
                       continue; 
                 }*/
                 
               
               /*  if(zoneId[0]==destination){
                       //alert('d '+m);
                       var info="Delivery Station:"+zoneId[0];
                       pushpin(map,zoneId[1],zoneId[2],info,'yes',zoneId[3]);
                       color='pink';
                       opacity=0.3;
                       circleCreation(zoneId[1],zoneId[2],map,color,opacity,zoneId[3],zoneId[0]);
                      continue;
                 }*/
                 if(allzones=="all"){
                   if(zoneId[0]==nearestGeozoneID){
                     //alert('n'+m);
                     var info="Near Zone:"+zoneId[0];
                     pushpin(map,zoneId[1],zoneId[2],info,'no',zoneId[3]);
                     color='green';
                     opacity=0.6;
                     //circleCreation(zoneId[1],zoneId[2],map,color,opacity,zoneId[3],zoneId[0]); 
                     //continue;
                  } 
                  if(zoneId[0].indexOf("fill")>=0){
                     var info="Near Zone:"+zoneId[0];
                     pushpin(map,zoneId[1],zoneId[2],info,'no',zoneId[3]);
                     //color='#FFFF00';
                     opacity=0.6;
                      color='green';
                    // circleCreation(zoneId[1],zoneId[2],map,color,opacity,zoneId[3],zoneId[0]);
                    // continue;
                  }    
                  if(zoneId[0].indexOf("other")>=0){
                     var info="Near Zone:"+zoneId[0];
                     pushpin(map,zoneId[1],zoneId[2],info,'no',zoneId[3]);
                     //color='#FFFF00';
                     opacity=0.6;
                      color='green';
                     //circleCreation(zoneId[1],zoneId[2],map,color,opacity,zoneId[3],zoneId[0]);
                  }           
                 circleCreation(zoneId[1],zoneId[2],map,color,opacity,zoneId[3],zoneId[0]);
                 } 
                }
             
               
            
           
         }
         function displayidles(map){
             for(var ll=0;ll<idlelat1.length-1;ll++){
                   
                    pushpin(map,idlelat1[ll],idlelong1[ll],"Idle : "+(ll+1),"yes",100);
 
                }

 
         }
         function replay(){
        //  var zone='<?php echo $zones; ?>';
               var zones = zone.split('*');
               //var eventData='<?php echo $data; ?>'+"";
               var event = eventData.split('*');
 
              var replayrate=parseInt(document.getElementById('replayrate').value); 
              var center=event[0].split('^');
              var mapOptions = {
                  zoom: 14,
                  mapTypeControlOptions: {
                        mapTypeIds: [google.maps.MapTypeId.SATELLITE, google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.HYBRID],
                        position: google.maps.ControlPosition.RIGHT_CENTER
                    },

                  center: new google.maps.LatLng(parseFloat(center[2]),parseFloat(center[3]))
               };
              var mapDiv = document.getElementById('mapcontainer');
                map = new google.maps.Map(mapDiv, mapOptions);
               
         /*     var lineSymbol = {
			        path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
			        strokeColor: '#2E2EFE' //arrow color
		        };
          */
            var lineSymbol = {
                  path: 'M 1,1 1,1',
                  strokeOpacity: 1,
                  scale: 1
               };
             lineSymbol={path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW};
             //   drawingManager.setMap(map);    
                displayidles(map);
                 gonesput1(event,zones);
                var m=0;
             var id=setInterval(function(){
                     dummymarker.setMap(null); 
                     if(m>event.length-2){  pushpin(map,elat,elong,"Vehicle Current Location",'yes',1000,"../images/vehicle.png");clearTimeout(id);return;}
                     else{
                         var evntdata=event[m].split('^');
                         var evntdata1=event[m+1].split('^');
                       //  replayPath(evntdata[2],evntdata[3],lineSymbol,map,evntdata1[2],evntdata1[3]);
                       //  m++;
                      if(evntdata1[5]>0.7 && (accountID=="gvkgjtest" ||  accountID=="goghealth" ||  accountID=="gvk-ut-gogh")){
                                calcRoute(evntdata[2],evntdata[3],evntdata1[2],evntdata1[3]) ;
                               //atob(evntdata[2],evntdata[3],evntdata1[2],evntdata1[3],'yes');
                                m++;
                             }else{
                            if(evntdata[11]=="Ignition Off" || evntdata[11]=="Ignition On" || evntdata[11]=="Main Power OFF" || evntdata[11]=="Main Power ON" || evntdata[11]=="Over Speed" || evntdata[11]=="Time Interval" || evntdata[11]=="Idle Point"){   
                                var displaylabel=evntdata[11];
                                if(evntdata[11]=="Over Speed"){
                                      displaylabel=parseInt(evntdata[6]);
                                }
                                pushpin(map,evntdata[2],evntdata[3],evntdata[12],"no","viewpoint",'../images1/pin30_green.png');
                           }
                             vehicleTrack(evntdata1[2],evntdata1[3],lineSymbol,map,evntdata[6],"yes",evntdata[7],"Address:"+evntdata[10]+"<br>Time:"+evntdata[1]+"<br>Speed:"+evntdata[6]+" Kmph");
                         //  vehicleTrack(evntdata[2],evntdata[3],lineSymbol,map,evntdata[6],"no",evntdata[7],evntdata[12]);
                         replayPath(evntdata[2],evntdata[3],lineSymbol,map,evntdata1[2],evntdata1[3]);
                       //  replayPath(evntdata[2],evntdata[3],lineSymbol,map,evntdata1[2],evntdata1[3]);}
                         m++;


                    }

}
                },replayrate);//setInterval funcion closed

        }//end function
/*      
       	function replayPath(lat,long,lineSymbol,map,lat2,long2){
	  var lineCoordinates = [
	      new google.maps.LatLng(parseFloat(lat),parseFloat(long)),
	      new google.maps.LatLng(parseFloat(lat2),parseFloat(long2))
	  ];
	  var line = new google.maps.Polyline({
	      icons: [{
	          icon: lineSymbol,
	          offset: '100%'
	      }],
	      path: lineCoordinates,
	      geodesic: true,
	      
	      strokeColor: '#4e5eb0',
	      strokeOpacity: 1.0,
	      strokeWeight: 2
	      });
           var pt = new google.maps.LatLng(lat,long);
          map.setCenter(pt);
       //    map.setZoom(17);
	    line.setMap(map);

	}*/
      function showAllZones(){
           allzones="all";
           initialise4();

      }
      function single(){
            allzones="one";
           initialise4();
      }
function reload(){
          
       var fromdate = document.getElementById('fromdate').value;
        var todate = document.getElementById('todate').value;
     // fromdate= datestringToEpochDB(fromdate);
 //         alert(fromdate+"   "+todate);
      // todate=datestringToEpochDB(todate);
   //     alert(fromdate+"   "+todate);


        

       var stoppage=0;
         var timeinterval=0;
         var overspeed=0;
         var ignition='no';
         var mainpower='no';
     var epochtdate=datestringToEpochDB(todate);
       var epochfdate=datestringToEpochDB(fromdate);
        var diffepoch=epochtdate-epochfdate;



      if(document.getElementsByName("multicheck")[0].checked) stoppage=document.getElementById(document.getElementsByName("multicheck")[0].value).value
             if(document.getElementsByName("multicheck")[1].checked) timeinterval=document.getElementById(document.getElementsByName("multicheck")[1].value).value
             if(document.getElementsByName("multicheck")[2].checked) overspeed=document.getElementById(document.getElementsByName("multicheck")[2].value).value 
              if(document.getElementsByName("multicheck")[3].checked) ignition="yes";
             if(document.getElementsByName("multicheck")[4].checked)  mainpower="yes";
       if(diffepoch<=86400){

      window.location.href ='tripMap.php?accountID='+accountID+'&vehicleID='+vehicleID+'&fromdate='+fromdate+'&todate='+todate+'&onlyidles=no&request=itself&stoppage='+stoppage+"&timeinterval="+timeinterval+"&overspeed="+overspeed+"&ignition="+ignition+"&mainpower="+mainpower;
         }else{

             alert("Date range should be in 24 Hrs");
         }

 
}
 function showrawhistory(){

     $("#mapcontainer").slideToggle("slow");

 }



    </script>
      
   </head>
   <style type='text/css'>
       .tablecss {
        text-align:center; 
        width:100%;
        border-collapse: collapse;
        color:black;
        background:white;
        font-family:Arial,Verdana,sans-serif;
        font-size:13;
        }
   </style>
   <style>
          html { height: 100% } body { height: 100%; margin: 0; padding: 0 } 
         #mapcontainer { height: 100%; width:79% ;left:20%;border:solid} 
         #rawdata{ height: 100%; width:100%;}
         #wrapper { position: relative; } 
         #logout {border-radius: 0px; position: absolute; background-color: transparent;height:80%; width:20%;top:20%; left:0px; z-index: 99; background: #d6dbdf;} 
         #help{ 
             border-radius: 10px; position: absolute; background-color: transparent;top:95%; left:80%; z-index: 99; background: #086A87;color:white; 
           }
         #helpdiv{
             border-radius: 10px; position: absolute; background-color: transparent;top:70%; left:70%; z-index: 99; background: #086A87;width:250px;height:125px
           }
         #info {border-radius: 0px; position: absolute; background-color: transparent;height:20%; width:20%;top:0px; left:0px; z-index: 99; background: #d6dbdf;;}
        #maphistory{border-radius: 10px; position: absolute; background-color: transparent;top:95%; left:45%; z-index: 99; background: #086A87;color:white}
        #infoshowhide{border-radius: 10px; position: absolute; background-color: transparent;top:95%; left:0%; z-index: 99; background: #086A87;color:white}
         
        .button {
    border: 2px solid #118cbe;
    border-radius: 5px;
    height: 22px;
    background: #118cbe;
    color: white;
    width: 230px;
    font-family: Arial,Verdana,sans-serif;
    font-size: 13;}  
.hello table tbody td {
    color: black;
    border-left: 1px solid #E1EEF4;
    font-size: 10px;
    background: #d6dbdf;
    font-family: 'arial helvetica sans-serif';
}
   </style>
   <body onload="">
       <div id="wrapper">
           <div  id="mapcontainer" class="mapcontainer" > <center><img src='../images/ajax-loader-original.gif' /> </center>  </div>
       </div>
        <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Vehicle Time VS Speed</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="box-body">
                  <div class="chart">
                    <canvas id="lineChart" height="250"></canvas>
                  </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->


       <div id="rawdata" style="overflow-y:scroll;">  </div>
       <div id="info"   class='hello'></div>
       <div id="logout"  class='hello'>
       <table align="center" class="BaseTable" background="#E6E6E6" class='tablecss'>  
          <thead> 
              <tr>
                  <td>From Date :</td>
                  <td><input type="text" id="fromdate" style="width:160px" class="button-error pure-button"  value=""/></td>
              </tr>
              <tr>
                  <td>To Date :</td>
                  <td><input type="text" id="todate" style="width:160px"  class="button-error pure-button" value=""/></td> 
              </tr>
              <tr id="stoppageID">
                  <td align="center" colspan='2'><input id='stpid' type="checkbox" name="multicheck" value="sid"> Stoppage&nbsp&nbsp&nbsp&nbsp&nbsp:
                  <input type="text" id="sid" class='textcss'style="width: 30px;"  value='10'/> (Min&nbsp)&nbsp&nbsp</td>
             </tr>
             <tr id="timeintervalID">
                  <td align="center" colspan="2"> <input type="checkbox" name="multicheck" value="tid" id='tiid'> Time Interval:<input type="text" id="tid" class='textcss'style="width: 30px;" value='2'/> (Hour)&nbsp&nbsp </td>
             </tr>
             <tr id="overspeedID">
                  <td align="center" colspan='2'><input type="checkbox" name="multicheck" value="oid" id='osid'> Over Speed&nbsp&nbsp:<input type="text" id="oid" class='textcss'style="width: 30px;" value='60' /> (Kmph) </td>
             </tr>
             <tr id="ignitionID">
                 <td align="center" colspan='2'> <input type="checkbox" name="multicheck" value="ignition" id='igid'> Ignition &nbsp&nbsp&nbsp&nbsp&nbsp <input type="checkbox" name="multicheck" value="mainpower" id='mpid'> Main Power  </td>
              </tr>

               <tr>
                  <td colspan='2' align='center'><input type="button"  onClick="reload()" style="width:120px"  class="button" value="submit"/><br></td>
              </tr>
              <tr>
                  <td colspan='2' align="center">
                      <select style="width:120px"  class="button" id="replayrate">
                          <option value='1000'>slow</option>
                          <option value='500'>medium</option>
                           <option value='200'>fast</option>
                      </select>
                  </td>
              </tr>
              <tr>
                  <td colspan='2' align="center"><input type="button" id="replay" style="width:120px"  class="button" onclick="replay(event)" value="Replay"/><br></td>

              </tr>
              <tr>
                  
                  <td colspan='2' align="center"><input type="button" id="allZone" style="width:120px"  onclick="showAllZones()" value="Show All Zones" class="button"/><br></td>
              </tr>
              <tr>
                  
                  <td colspan='2' align="center"><input type="button" id="one" style="width:120px" onclick="single()" value="Reset" class="button"/><br></td>
               </tr>
          </thead>
       </table>
      </div>
      <div id='helpdiv'>
       <table class='tablecss' style='width:100%;height:100%'>
           <thead>
              <tr>
                  <td colspan='2'><img src="../images1/pin30_yellow_h0.png" width="20" height="20">  Speed <=30</td>
               </tr>
               <tr>
                  <td colspan='2'><img src="../images1/pin30_green_h0.png" width="20" height="20"> Speed Between 30 and 40</td>
               </tr>
               <tr>
                  <td colspan='2';><img src="../images1/pin30_orange_h0.png" width="20" height="20">  Speed >=40</td>
                </tr>
               <tr>
                  <td colspan='2'><img src="../images1/pin30_green.png" width="20" height="20"> Event Type  </td>
               </tr>
           </thead>
       </table>
       </div>
       <input type="button" id="maphistory"  class="button" value="Raw Data" onclick="showrawhistory();$('#logout').slideToggle('slow'); $('#info').slideToggle('slow');">
    <!--   <input type="button" id="infoshowhide" class="button" value="Info show/hide" onclick=" $('#logout').slideToggle('slow'); $('#info').slideToggle('slow');">-->
       <input type="button" id="help" class="button" value="Help" onclick=" $('#helpdiv').slideToggle('slow');">
    </body>
</html>
