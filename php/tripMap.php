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
                  mysqli_select_db($gtsconnect,'gts');

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
/*
$barr=array();
$barr=GetBaseLocationAndGroupID($vehicleID);
$groupID=$barr[1];
$baseloc=$barr[0];
*/
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
<!--      <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"  crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"  crossorigin=""></script>-->
<!--
   <script src="https://maps.googleapis.com/maps/api/js?libraries=drawing"></script>
   <script type="text/javascript" src="../js/markerlabel.js"></script>
-->
<script src="https://apis.mapmyindia.com/advancedmaps/v1/4nk6fj23vniumia8cieholhk1ak2yzh2/map_load?v=1.3"></script>

<!--
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&v=3&libraries=geometry"></script>       -->
<!--     <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=drawing"></script> -->
   <script type="hacker/JavaScript" hacker="enabled"></script>
<script src="../js/jquery-1.11.1.min.js"></script>
<script src="../js/datePicker1.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../js/jquery.datetimepicker.css"/>
<link rel="stylesheet" type="text/css" href="../css/fuelbar.css"/>
 <script type="text/javascript" src="../js/tac.js"></script>
<script src="../js/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
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

.leaflet-pane {
    z-index: 0;
}


 </style>
       <script>

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
            var map;
var directionsService;
           var tochZones=[];
           var drawingManager;
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
loadMap(parseFloat(centr[2]),parseFloat(centr[3]));
           //     directionsService = new google.maps.DirectionsService();

               document.getElementById('fromdate').value='<?php echo $fdate; ?>'; 
               document.getElementById('todate').value='<?php echo $tdate; ?>';

               //creating circle
                if(idle=="yes"){
                    pushpin(map,latitude,longitude,'Idle Point',"yes",100,"../images1/idle.png");
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

                pushpin(map,elat,elong,"Ending : "+destination,"yes",100,"../images1/pin30_green.pn");
                //display all idle poins
                displayidles(map);
   //           displayCurrentStatus(); //google driving path showing on map 
                drawingManager = initialiseDrivingServices();



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

            var degree=0;
            var vehicleStatus=0;
            var rawdata="<center><table id='rawdatareport' class='tablecss'><thead><th>Device ID</th><th>Speed Km/p</th><th>Time</th><th>Current Location</th><th>Lat</th><th>Lng</th><th>Distance(Km)</th><th>Vehicle Status</th><th>Direction(degree)</th><th>Event Status</th><th>Event Duration</th></thead><tbody>";
            var cumulativeDistance=0;
            var timeintaveldiff=0;
            var graphDatax=[];//{ label: "Jan",  y: 5.28 },
           var graphDatay=[];
           var graphpointdata=[];

             var starttime=0; 
             var starttimeformate=0;
             for(var m=1;m<event.length-2;m++){
                      var evntdata=event[m].split('^');
                      var evntdata2=event[m-1].split('^')
 
                      if(starttime==0 && evntdata[9]!="61714"){
                            starttime=evntdata[4];
                            starttimeformate=evntdata[1];
                      }
                      if(starttime!=0 && evntdata[9]=="61714"){
                          var diff=(evntdata[4]-starttime)/60;
                            if(diff>=2){
                                 pushpin(map,evntdata[2],evntdata[3],"Parking From Time:"+starttimeformate+"<br> To Time:"+evntdata[1]+"<br>Parking Time:"+secondsToHms(parseInt(diff)),'no',1000,"../images/parking.png");
                               //  alert("parking Time"+diff);
                           }
                          starttime=0;
                          starttimeformate=0;
 
                      }

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
                        graphpointdata.push(evntdata[1]+"^"+parseInt(evntdata[6])+"^"+vehicleID+"^"+evntdata[10]+"^"+parseFloat(evntdata[2]).toFixed(5)+"^"+parseFloat(evntdata[3]).toFixed(5));
                       rawdata +="<tr><td>"+vehicleID.toUpperCase()+"</td><td>"+parseInt(evntdata[6])+"</td><td>"+evntdata[1]+"</td><td>"+evntdata[10]+"</td><td>"+parseFloat(evntdata[2]).toFixed(5)+"</td><td>"+parseFloat(evntdata[3]).toFixed(5)+"</td><td>"+parseFloat(cumulativeDistance).toFixed(2) +"</td><td>"+statusdis+"</td><td>"+evntdata[8]+"</td><td>"+evntdata[12]+"</td><td>"+evntdata[13]+"</td></tr>";
               //  if(statusdis=="Moving" ){
                    if(parseFloat(evntdata[5])>3){
                          fillGaps(evntdata[2],evntdata[3],evntdata2[2],evntdata2[3]);
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


           if(starttime!=0){
               var lstevnt=event[event.length-2].split('^');
                var diff=(lstevnt[4]-starttime)/60;
                if(diff>=2){
                       pushpin(map,lstevnt[2],lstevnt[3],"Parking From Time:"+starttimeformate+"<br> To Time:"+lstevnt[1]+"<br>Parking Time:"+secondsToHms(parseInt(diff)),'no',1000,"../images/parking.png");
                               //  alert("parking Time"+diff);
                           }
                  starttime=0;
                 starttimeformate=0;
 
           }



 
            rawdata +="</tbody></table></center>";
            document.getElementById("rawdata").innerHTML = rawdata;
           var info="Asset :"+vehicleID+"  &nbsp"+name+"<br>Starting Locaton :"+factory+"<br>Travelled Distance: "+parseFloat(cumulativeDistance).toFixed(2)+" Kms";
              pushpin(map,slat,slong,info,"yes",100);


                         document.getElementById('info').innerHTML="<table class='tablecss'><tr><td>Vehicle ID :</td><td> "+vehicleID.toUpperCase()+"</td></tr><tr><td>Distance : </td><td>"+parseFloat(cumulativeDistance).toFixed(2)+" Kms</td></tr><tr><td>S lat/lng :</td><td>"+slat+"/"+slong+"</td></tr><tr><td>E lat/lng:</td><td>"+elat+"/"+elong+"</td></tr></table>";
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
              pushpin(map,elat,elong,"Vehicle Current Location",'no',1000,"http://glotrax.glovision.co:8080/track/extra/images/pp/CrosshairRed.gif");

new Chart(document.getElementById("lineChart"), {
  type: 'line',
  data: {
    labels:graphDatax,
    datasets: [{ 
        data: graphDatay,
        label: "Speed",
        borderColor: "#3e95cd",
        fill: false
      }
    ]
  },
  options: {
    title: {
      display: true,
      text: 'Vehicle Time Vs Speed,d'
    },
     'onClick' : function (evt, item) {
              //alert(item[0]._index);
              var indx=item[0]._index
               var pointdetails= graphpointdata[indx].split('^');
               
               var info="Vehicle ID:"+pointdetails[2]+"<br>Speed:"+pointdetails[1]+" Kmph<br>Time:"+pointdetails[0]+"<br>Address:"+pointdetails[3];
               graphpointonmap(map,pointdetails[4],pointdetails[5],info,'yes',1000,"../images/red1.png")
            //  alert(graphDatax[indx]+"  "+graphDatay[indx]+" "+graphpointdata[indx]);
          },
  }
});
           
           //  atob();
              gonesput1(event,zones);
                  }


function secondsToHms(d) {
    d = Number(d*60);
    var h = Math.floor(d / 3600);
    var m = Math.floor(d % 3600 / 60);
    var s = Math.floor(d % 3600 % 60);

    var hDisplay = h > 0 ? h + (h == 1 ? " hour, " : " hours, ") : "";
    var mDisplay = m > 0 ? m + (m == 1 ? " minute, " : " minutes, ") : "";
    var sDisplay = s > 0 ? s + (s == 1 ? " second" : " seconds") : "";
    return hDisplay + mDisplay + sDisplay; 
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




function sleepFor( sleepDuration ){
    var now = new Date().getTime();
    while(new Date().getTime() < now + sleepDuration){ /* do nothing */ } 
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
      function showAllZones(){
           allzones="all"; 
           clearTimeout(intervelID);
           initialise4();

      }
      function single(){
            allzones="one";
            clearTimeout(intervelID);
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
       if(diffepoch<=259200){

      window.location.href ='tripMap.php?accountID='+accountID+'&vehicleID='+vehicleID+'&fromdate='+fromdate+'&todate='+todate+'&onlyidles=no&request=itself&stoppage='+stoppage+"&timeinterval="+timeinterval+"&overspeed="+overspeed+"&ignition="+ignition+"&mainpower="+mainpower;
         }else{

             alert("Date range should be in 3 days");
         }

 
}
 function showrawhistory(){

     $("#mapcontainer").slideToggle("slow");

 }



    </script>
   <!--   <script type="text/javascript" src="../js/googlehistory.js"></script>  -->
<!--     <script type="text/javascript" src="../js/leaflethistory.js"></script> -->
    <script type="text/javascript" src="../js/mapmyindiahistory.js"></script>
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
         #mapcontainer { height:59%; width:79% ;left:20%;border:solid} 
         #rawdata{ height: 100%; width:100%;}
         #wrapper { position: relative; } 
         #logout {border-radius: 0px; position: absolute; background-color: transparent;height:40%; width:20%;top:20%; left:0px; z-index: 99; background: #d6dbdf;} 
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

        <canvas id="lineChart" width="800" height="200"></canvas>


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
