<?php
    date_default_timezone_set('Asia/Kolkata');
    require_once('config.php');

    session_start();
    $accountID=$_GET['accountID'];
    $caseID=$_GET['caseID'];

    $deviceID=$_GET['deviceID'];
    $ftime=$_GET['fromTime'];
    $ttime=$_GET['toTime'];
    if($ftime=="")$ftime=time();
    if($ttime=="")$ttime=time();
     
    $travelDistance='';//$_GET['travelDistance'].' Kms';
    $result= getCaseInfo($deviceID,$ftime,$ttime,$caseID);
    $fromDate=$result[0];
    $toDate=$result[1];
    if($toDate=="" || $toDate=="0") {
        $toDate=time();
    }
    if($caseID==""){
       $fromDate=$ftime;
       $toDate=$ttime;

     }
    $vehicleID=$result[2];
    $caseInfo=$result[0].'^'.$result[1].'^'.$result[2].'^'.$result[3].'^'.$result[4].'^'.$result[5].'^'.$result[6].'^';//.$result[7].'^'.$result[8].'^'.$result[9].'^'.$result[10].'^'.$result[11].'^'.$result[12].'^';

    $eventData=array(array());
    getEventData($vehicleID,$accountID,$fromDate,$toDate);
    $data='';
    $indx=0;
    $startOdo=$eventData[0][4];
    $endOdo=$eventData[count($eventData)-1][4];
    $distanceTravelled=intval($endOdo-$startOdo);
    for($i=0;$i<count($eventData);$i++){
       $data .=$eventData[$i][0].'^'.$eventData[$i][3].'^'.$eventData[$i][8].'^'.$eventData[$i][9].'^'.$eventData[$i][10].'^'.$eventData[$i][4].'^'.$eventData[$i][5].'^'.$eventData[$i][11].'^'.$eventData[$i][12].'^'.$eventData[$i][2].'^'.$eventData[$i][6].'^'.$eventData[$i][13].'^'.$eventData[$i][14].'^'.$eventData[$i][15].'^*';
    }
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
function getCaseInfo($deviceID,$ftime,$ttime,$caseID){
      global $tacserver, $tacusername, $tacpassword,$tacdb;
      $result=array();
      $tacconnect = mysqli_connect("track.glovision.co:50999", $tacusername, $tacpassword) or die ("Unable to connect to the database: " . mysqli_error($tacconnect));
      mysqli_select_db($tacconnect,$tacdb);
      $Query="select * From caseManager where caseID='$caseID'";

     if($caseID==""){
        $Query="select * From caseManager where deviceID='$deviceID' and caseTimestamp between '$ftime' and '$ttime'";

     }
      $rs =mysqli_query($tacconnect,$Query) or die ("Query error1: " . mysqli_error($tacconnect));
      while($row = mysqli_fetch_assoc($rs)) {
          $result[0]=$row['caseTimestamp'];
          $result[1]=$row['caseCloseTime'];
          $result[2]=$row['deviceID'];
          $result[3]=$row['callerName'];
          $result[4]=$row['callerContact'];
          $result[5]=$row['incidentLatitude'];
          $result[6]=$row['incidentLongitude'];
          
        
      }
      mysqli_close($tacconnect);
      return $result;
}
function getEventData($vehicleID,$accountID,$fromDate,$toDate){
       global $gtsserver, $gtsusername, $gtspassword,$gtsdb,$eventData,$startlatlong,$endlatlong,$travelDistance,$destination,$factory,$stoppage,$timeinterval,$overspeed,$ignition,$mainpower,$result;
      
      $Query = " SELECT heading, geozoneID,timestamp,deviceID,FROM_UNIXTIME(timestamp) as 'time1',speedKPH,address,odometerKM+odometerOffsetKM as 'distance',statusCode,analog0,latitude,longitude FROM `EventData` where deviceID='$vehicleID' and accountID='$accountID' and timestamp between '$fromDate' and '$toDate' order by timestamp asc ";  //request from web for particular vehicle
  
       $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($gtsconnect));
                  mysqli_select_db($gtsconnect,'gts');
       $rs =mysqli_query($gtsconnect,$Query) or die ("Query error1: " . mysqli_error($gtsconnect));
       $num_rows = mysqli_num_rows($rs);
       $i=0;
       while($row = mysqli_fetch_assoc($rs)) {
           $eventData[$i][13]='';
            $eventData[$i][14]='';
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
                 $difftimes=abs(strtotime($eventData[$i][3])-strtotime($eventData[$i-1][3]))/60;
                 $eventData[$i][15]=secondsToTimeFormate(abs(strtotime($eventData[$i][3])-strtotime($eventData[$i-1][3])));
                 $maxDistanceTravelledperMint=2;//max km travelled per mint
                 $calculatedDistenceTravelledperMint=intval($eventtoeventdistance)/$difftimes;
                 if(is_nan($eventtoeventdistance) || ($eventtoeventdistance>5 && $difftimes<5)){//if distnace greater than 2 km with 5 minits then distnace=0 bzc drift point in 
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
       <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=drawing"></script> 
       <script type="hacker/JavaScript" hacker="enabled"></script>
       <script type="text/javascript" src="../js/markerlabel.js"></script>
       <script src="../js/jquery-1.11.1.min.js"></script>
       <script src="../js/datePicker1.js" type="text/javascript"></script>
       <link rel="stylesheet" type="text/css" href="../js/jquery.datetimepicker.css"/>
       <link rel="stylesheet" type="text/css" href="../css/fuelbar.css"/>
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
           var caseinfo='<?php echo $caseInfo; ?>'+"";
           var infocase=caseinfo.split('^');
           var caseID='<?php echo $caseID; ?>'; 
           var eventData='<?php echo $data; ?>'+""; 
           var zone='<?php echo $zones; ?>'; 
           var travelDistance='<?php echo $travelDistance; ?>'; 
           var distanceTravelled='<?php echo $distanceTravelled; ?>'; 
           var vehicleID='<?php echo $vehicleID; ?>'; 
           var accountID='<?php echo $accountID; ?>';
           var allzones="one";
           var dummymarker; 
           // var event = eventData.split('*'); 
           // var zones = zone.split('*'); 
           google.maps.event.addDomListener(window, 'load', initialise4);
           var map;
           var tochZones=[];
           var drawingManager;
           function initialise4() {
               var zones = zone.split('*');
               var event = eventData.split('*');
               var centr=event[0].split('^');
               var mapOptions = {
                   zoom: 13,
                   mapTypeControlOptions: {
                        mapTypeIds: [google.maps.MapTypeId.SATELLITE, google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.HYBRID],
                        position: google.maps.ControlPosition.RIGHT_CENTER
                   },
                   center: new google.maps.LatLng(parseFloat(centr[2]),parseFloat(centr[3]))
               };
               var mapDiv = document.getElementById('mapcontainer');
               map = new google.maps.Map(mapDiv, mapOptions);

              var lineSymbol = {
                  path: 'M 1,1 1,1',
                  strokeOpacity: 1,
                  scale: 1
               };
               var degree=0;
               var vehicleStatus=0;
               var rawdata="<center><table id='rawdatareport' class='tablecss'><thead><th>Device ID</th><th>Speed Km/p</th><th>Time</th><th>Current Location</th><th>Lat</th><th>Lng</th><th>Distance(Km)</th><th>Vehicle Status</th><th>Direction(degree)</th><th>Event Status</th><th>Event Duration</th></thead><tbody>";
               var cumulativeDistance=0;
               var timeintaveldiff=0;
               for(var m=0;m<event.length-1;m++){
                    var evntdata=event[m].split('^');
                    var evntdata2=event[m+1].split('^')
                    degree=evntdata[8];
                    vehicleStatus=evntdata[9];
                    var statusdis="Stopped";
                    if(vehicleStatus=="61714")statusdis="Moving";
                    else if(vehicleStatus=="61718")statusdis="Idle";
                    else if(vehicleStatus=="62467")statusdis="Ignition Off";
                    else if(vehicleStatus=="62465")statusdis="Ignition On";
                    if(m==0){evntdata[5]=0;}
                    cumulativeDistance +=parseFloat(evntdata[5]);
                    rawdata +="<tr><td>"+vehicleID.toUpperCase()+"</td><td>"+parseInt(evntdata[6])+"</td><td>"+evntdata[1]+"</td><td>"+evntdata[10]+"</td><td>"+parseFloat(evntdata[2]).toFixed(5)+"</td><td>"+parseFloat(evntdata[3]).toFixed(5)+"</td><td>"+parseFloat(cumulativeDistance).toFixed(2) +"</td><td>"+statusdis+"</td><td>"+evntdata[8]+"</td><td>"+evntdata[12]+"</td><td>"+evntdata[13]+"</td></tr>";
                    vehicleTrack(evntdata[2],evntdata[3],lineSymbol,map,evntdata[6],"no",evntdata[7],evntdata[1]);
                    replayPath(evntdata[2],evntdata[3],lineSymbol,map,evntdata2[2],evntdata2[3]);
                    if(evntdata[11]=="Seen Arrival" || evntdata[11]=="Seen Depart" || evntdata[11]=="Destination Arrival" || evntdata[11]=="Destination Depart" || evntdata[11]=="Base Arrival" || evntdata[11]=="Base Depart" || evntdata[11]=="Back To Base Arrival"){
                        vehicleTrack(evntdata[2],evntdata[3],lineSymbol,map,evntdata[6],"yes",evntdata[7],evntdata[12]+"<br>Location :"+evntdata[10]);
                        replayPath(evntdata[2],evntdata[3],lineSymbol,map,evntdata2[2],evntdata2[3]);
                    }


              } 
              rawdata +="</tbody></table></center>";
              document.getElementById("rawdata").innerHTML = rawdata;
              //Case Assign time    
              var startEvent=event[0].split('^');
              var info="Case ID:"+caseID+"<br>VehicleID :"+vehicleID+"<br>Beneficiary Name :"+infocase[3]+"<br> Beneficiary Contact:"+infocase[4]+"<br>Travell Distance:"+parseInt(cumulativeDistance)+" Km";
              pushpin(map,parseFloat(startEvent[2]),parseFloat(startEvent[3]),"Start","yes",100);
            
   
           pushpin(map,parseFloat(infocase[5]),parseFloat(infocase[6]),info,"yes",100);
              var image="../images1/idle.png";
              if(vehicleStatus=='61714')image="../images1/online.png";
              //Case Close Time
              var endEvent=event[event.length-2].split('^');
              pushpin(map,endEvent[2],endEvent[3],"Current Location",'yes',1000,"../images/vehicle.png");
              var position = new google.maps.LatLng(parseFloat(endEvent[2]),parseFloat(endEvent[3]));
                   gifmarker = new google.maps.Marker({
                   position: position,
                   map: map,
                   optimized: false,
                   icon:"http://glotrax.glovision.co:8080/track/extra/images/pp/CrosshairRed.gif",  
                   visible: true
             });
             //gonesput1(event,zones);
         }//end functio 

        function vehicleTrack(lat,long,lineSymbol,map,speed,status1,direction,dis){
            speed=parseInt(speed);
            var pincolor='yellow';
            if(speed>20 && speed<40){
                 pincolor='green';
            }else if(speed>40){
                 pincolor='orange';
            }
            var directionimage='../images1/pin30_'+pincolor+"_"+direction+".png";
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
                strokeColor: '#4e5eb0',
                strokeOpacity: 1.0,
                 strokeWeight: 2
            });
            var pt = new google.maps.LatLng(lat,long);
            map.setCenter(pt);
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
                   title:"Time : "+zone,
                   });


              }
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
             var cityCircle = new google.maps.Circle(populationOptions);
             var infowindow = new google.maps.InfoWindow({
                content: "Zone Id: "+geozone
             });
             google.maps.event.addListener(cityCircle, 'click', function() {
                 infowindow.open(map,cityCircle);
             });
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
        function displayidles(map){
            // for(var ll=0;ll<idlelat1.length-1;ll++){
              //      pushpin(map,idlelat1[ll],idlelong1[ll],"Idle : "+(ll+1),"yes",100);
              //  }
        }
        function replay(){
            var zones = zone.split('*');
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
            var lineSymbol = {
                  path: 'M 1,1 1,1',
                  strokeOpacity: 1,
                  scale: 1
            };
            displayidles(map);
            var m=0;
            var id=setInterval(function(){
                dummymarker.setMap(null); 
                if(m>event.length-2){  pushpin(map,elat,elong,"Vehicle Current Location",'yes',1000,"../images/vehicle.png");clearTimeout(id);return;}
                else{
                    var evntdata=event[m].split('^');
                    var evntdata1=event[m+1].split('^');
                    if(evntdata1[5]>0.7 && (accountID=="gvkgjtest" ||  accountID=="goghealth" ||  accountID=="gvk-ut-gogh")){
                         atob(evntdata[2],evntdata[3],evntdata1[2],evntdata1[3],'yes');
                    }else{
                        if(evntdata[11]=="Seen Arrival" || evntdata[11]=="Seen Depart" || evntdata[11]=="Destination Arrival" || evntdata[11]=="Destination Depart" || evntdata[11]=="Base Arrival" || evntdata[11]=="Base Depart" || evntdata[11]=="Back To Base Arrival"){
                        vehicleTrack(evntdata[2],evntdata[3],lineSymbol,map,evntdata[6],"yes",evntdata[7],evntdata[12]+"<br>Location :"+evntdata[10]);
                        replayPath(evntdata[2],evntdata[3],lineSymbol,map,evntdata1[2],evntdata1[3]);
                    }

                        vehicleTrack(evntdata[2],evntdata[3],lineSymbol,map,evntdata[6],"no",evntdata[7],evntdata[12]);
                        replayPath(evntdata[2],evntdata[3],lineSymbol,map,evntdata1[2],evntdata1[3]);
                        m++;
                    }
                }
            },replayrate);//setInterval funcion closed
        }//end function
        function showrawhistory(){
         $("#mapcontainer").slideToggle("slow");
        }
    </script>
      
    </head>
    <style type='text/css'>
       .tablecss {
        
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
         #mapcontainer { height: 100%; width:100% ;} 
         #rawdata{ height: 100%; width:100%;}
         #wrapper { position: relative; } 
         #logout {border-radius: 10px; position: absolute; background-color: transparent;height:100px; width:120px;top:0px; left:0px; z-index: 99; background: #E6E6E6;} 
         #help{ 
             border-radius: 10px; position: absolute; background-color: transparent;top:95%; left:90%; z-index: 99; background: #086A87;color:white; 
           }
         #helpdiv{
             border-radius: 10px; position: absolute; background-color: transparent;top:70%; left:70%; z-index: 99; background: #086A87;width:250px;height:125px
           }
         #info {border-radius: 10px; position: absolute; background-color: transparent;height:60px; width:250px;top:0px; left:0px; z-index: 99; background: #E6E6E6;}
        #maphistory{border-radius: 10px; position: absolute; background-color: transparent;top:95%; left:45%; z-index: 99; background: #086A87;color:white}
        #infoshowhide{border-radius: 10px; position: absolute; background-color: transparent;top:95%; left:0%; z-index: 99; background: #086A87;color:white}
    </style>
    <body onload="">
       <div id="wrapper">
           <div  id="mapcontainer" class="mapcontainer" >   </div>
       </div>
       <div id="rawdata" style="overflow-y:scroll;">  </div>
       <div id="logout"  class='hello'>
       <table align="center" class="BaseTable" background="#E6E6E6" class='tablecss'>  
          <thead> 
              <tr>
                  <td colspan='2' align="center">
                      <select style="width:100px"  class="button-clear pure-button" id="replayrate">
                          <option value='1000'>slow</option>
                          <option value='500'>medium</option>
                           <option value='200'>fast</option>
                      </select>
                  </td>
              </tr>
              <tr>
                  <td colspan='2' align="center"><input type="button" id="replay" style="width:100px"  class="button-clear pure-button" onclick="replay(event)" value="Replay"/></td>

              </tr>
              <tr>
                  
                  <td colspan='2' align="center"><input type="button" id="one" style="width:100px" onclick="single()" value="Reset" class="button-clear pure-button"/></td>
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
       <input type="button" id="maphistory" value="Raw Data" onclick="showrawhistory();$('#logout').slideToggle('slow'); $('#info').slideToggle('slow');">
       <input type="button" id="infoshowhide" value="Info show/hide" onclick=" $('#logout').slideToggle('slow'); $('#info').slideToggle('slow');">
       <input type="button" id="help" value="Help" onclick=" $('#helpdiv').slideToggle('slow');">
    </body>
</html>
