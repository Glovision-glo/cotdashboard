<?php
    date_default_timezone_set('Asia/Kolkata');
    require_once('config.php');
 //   require_once('CheckVehicleInServer.php');
    $vehicleID=$_GET['vehicleID'];
    $accountID=$_GET['accountID'];

    $startlatlong=0;
    $endlatlong=0;
    $travelDistance=0;
    $eventData=array(array());
    getEventData($vehicleID,$accountID);
    for($i=0;$i<count($eventData);$i++){
       $data ='0^'.$eventData[$i][3].'^'.$eventData[$i][1].'^'.$eventData[$i][2].'^'.$eventData[$i][3].'^0^'.$eventData[$i][0].'^'.$eventData[$i][5].'^'.$eventData[$i][6].'^'.'*';

    }
function getEventData($vehicleID,$accountID){
    global $gtsserver, $gtsusername, $gtspassword,$gtsdb,$eventData;
     $Query="select lastValidHeading,lastValidSpeedKPH,lastValidLatitude,lastValidLongitude,a.lastEventTimestamp as lastGPSTimestamp,c.contactPhone as simPhoneNumber  from Device a,Driver c where a.accountID='$accountID' and  c.accountID='$accountID' and c.deviceID=a.deviceID and a.deviceID='$vehicleID' and c.deviceID='$vehicleID'";
  $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($gtsconnect));
  mysqli_select_db($gtsconnect,$gtsdb);
   $rs =mysqli_query($gtsconnect,$Query) or die ("Query error1: " . mysqli_error($gtsconnect));
    $i=0;
    while($row = mysqli_fetch_assoc($rs)) {
       $eventData[$i][0]=$row['lastValidSpeedKPH'];
       $eventData[$i][1]=$row['lastValidLatitude'];
       $eventData[$i][2]=$row['lastValidLongitude'];
       $eventData[$i][3]=$row['lastGPSTimestamp'];
       $eventData[$i][4]=$row['simPhoneNumber'];
       $eventData[$i][5]=findDirection($row['lastValidHeading']);
      $eventData[$i][6]=$row['lastValidHeading'];
     }
      mysqli_close($gtsconnect);

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



?>
<html>
<head>
    <!-- <script src="https://maps.googleapis.com/maps/api/js?libraries=geometry"></script> -->
<!--    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"  crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"  crossorigin=""></script>
-->
<script src="https://apis.mapmyindia.com/advancedmaps/v1/4nk6fj23vniumia8cieholhk1ak2yzh2/map_load?v=1.3"></script>
    <script type="hacker/JavaScript" hacker="enabled"></script>
    <script src="../js/jquery-1.11.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/fuelbar.css"/>
   <script>
// var alllatlng='<?php echo $alllatlng1; ?>';

 var accountID='<?php echo $accountID; ?>';         
          var eventData='<?php echo $data; ?>'; 
          var travelDistance='<?php echo $travelDistance; ?>'; 
          var vehicleID='<?php echo $vehicleID; ?>'; 
          var slatlong='<?php echo $startlatlong; ?>';
          var slat=slatlong.split("-")[0];
          var slong=slatlong.split("-")[1];
          var endlatlong='<?php echo $endlatlong; ?>';
          var elat='';
          var elong='';
          var gifmarker;
          var livespeed=0;
          var oldspeed=0;
          var degree=0;
         var vehicleStatus='offline';
          var lastvaliddirection='h0';
         var lastvaliddirectionold='h0';
          var allzones="one";
          var dummymarker;




var startLocation='<?php echo $startLocation; ?>';
var map;
var directionDisplay;
var directionsService;
var stepDisplay;
var markerArray = [];
var position;
var marker = null;
var polyline = null;
var poly2 = null;
var speed = 0.000005,
  wait = 1;
var infowindow = null;
var timerHandle = null;
//alllatlng=alllatlng.split("*");


  function replay(){
       var replayrate=15000;
             var event = eventData.split('*');
             var center=event[0].split('^');

             var lineSymbol = {
                  path: [],
                  strokeColor: '#FF0000',
                  strokeWeight: 3
               };
          lineSymbol = {
                  path: 'M 1,1 1,1',
                  strokeOpacity: 1,
                  scale: 1
               };
          
 
              var lastevntdata=event[0].split('^');
              oldspeed=parseInt(lastevntdata[6]);
              lastvaliddirectionold=lastevntdata[7];
              var lasteventepochtime=lastevntdata[1];
              var lasteventDate = new Date(lasteventepochtime*1000);
              getLiveEvent();   
               var vehicleColor="#f19908";
              if(oldspeed>1 && oldspeed<40) vehicleColor="#329256";
              else if(oldspeed>40)vehicleColor="#df3925";
              //  alert(elat+" "+elong+" "+lastevntdata[8]+"  "+vehicleColor);       
              pushpin(map,elat,elong,"Speed:"+oldspeed,'no',1000,lastevntdata[8],vehicleColor,lasteventDate);
              var id=setInterval(function(){
                   getLiveEvent();
                   removeMarker(dummymarker);
                   var vehicleColor="#f19908";
              if(livespeed>1 && livespeed<40) vehicleColor="#329256";
              else if(livespeed>40)vehicleColor="#df3925";

                  pushpin(map,clat,clong,zone1,'yes',100,degree,vehicleColor,lasteventDate);
                    oldspeed=livespeed;
                    var x=elat;
                    var y=elong;
                   // replayPath(elat,elong,lineSymbol,map,clat,clong);
                    elat=clat;
                   // alert(elat+"   "+clat);
                    elong=clong;
                    lastvaliddirectionold=lastvaliddirection;
                     calcRoute(x,y,clat,clong,vehicleColor,degree);
           
                },replayrate);


}





function getLiveEvent(){
          $.ajax({
                   type : 'get',
                   url:'LiveEventPerVehicle.php',
                   data:{"accountID":accountID,"vehicleID":vehicleID},
                        dataType:'json',
                        success: function(data) {
                            $.each(data, function() {
                                  $.each(data, function(key, value) {
                                     // alert(value);       
                                     clat=value[0];
                                     clong=value[1]; 
                                      livespeed=parseInt(value[2]);
                                      lastvaliddirection=value[3];
                                       degree=value[4];
                                      vehicleStatus=value[5];
                                     timestamp=value[6]
                                zone1="<b> Vehicle: "+vehicleID+"<br> Lat/Long :"+clat+"/"+clong+" <br> Last Event Time :"+timestamp+"<br>Speed: "+livespeed+" Kmph";
                               //  alert(clat); 
                                  });
                             });
                         }
                     });
            

}
</script>
 <!-- <script src="../js/googlelive.js"></script> -->
</head>
<body>
<div id="map_canvas" style="width:100%;height:100%;"></div>
<!-- <script src="../js/leafletlive.js"></script> -->
 <script src="../js/mapmyindialive.js"></script>

</body>
</html>
