<?php
    date_default_timezone_set('Asia/Kolkata');
    require_once('config.php');
    $vehicleID=$_GET['vehicleID'];
    $accountID=$_GET['accountID'];
    if($accountID=="gvk-up-102"){$gtsserver="94.23.211.49";$tacserver="94.23.211.49";}
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
<script src="https://maps.googleapis.com/maps/api/js?libraries=geometry&client=gme-gvkemergencymanagement3&channel=<?php echo $accountID."-glovision";?>"></script>
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
function createMarker(latlng, label, html) {
  var contentString = '<b>' + label + '</b><br>' + html;
  var marker = new google.maps.Marker({
    position: latlng,
    map: map,
    title: label,
    zIndex: Math.round(latlng.lat() * -100000) << 5
  });
  marker.myname = label;
  google.maps.event.addListener(marker, 'click', function() {
    infowindow.setContent(contentString);
    infowindow.open(map, marker);
  });
  return marker;
}

function initialize() {

    var event = eventData.split('*');
             var lastevnt=event[0].split('^');
              elat=lastevnt[2];
              elong=lastevnt[3];
             var vehiclereachedin="Vehicle Stopped";

  infowindow = new google.maps.InfoWindow({
    size: new google.maps.Size(150, 50)
  });
 
  directionsService = new google.maps.DirectionsService();

  var mapOptions = {
                  zoom: 14,
                  mapTypeControlOptions: {
                        mapTypeIds: [google.maps.MapTypeId.SATELLITE, google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.HYBRID],
                        position: google.maps.ControlPosition.RIGHT_CENTER
                    },

                  center: new google.maps.LatLng(parseFloat(elat),parseFloat(elong))
             };
             var mapDiv = document.getElementById('map_canvas');
             map = new google.maps.Map(mapDiv, mapOptions);


  
  var rendererOptions = {
    map: map
  };
  directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);

  
  stepDisplay = new google.maps.InfoWindow();

  polyline = new google.maps.Polyline({
    path: [],
    strokeColor: '#FF0000',
    strokeWeight: 3
  });
  poly2 = new google.maps.Polyline({
    path: [],
    strokeColor: '#FF0000',
    strokeWeight: 3
  });
/*for(var lcnt=0;lcnt<alllatlng.length;lcnt++){
   var lt=alllatlng[lcnt].split("^");

   pushpin(lt[0],lt[1],"no",'',lcnt);
}*/
//pushpin(slat,slng,"yes","",startLocation);
//pushpin(elat,elng,"no","../images/vehicle.png","");
//atob(slat,slng,elat,elng);

replay();

}

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
                   dummymarker.setMap(null);
                 //  vehicleTrack(dummymarker.position.lat(),dummymarker.position.lng(),lineSymbol,map,oldspeed,"no",lastvaliddirectionold);
                    
                   var vehicleColor="#f19908";
              if(livespeed>1 && livespeed<40) vehicleColor="#329256";
              else if(livespeed>40)vehicleColor="#df3925";

             //  pushpin(map,clat,clong,vehicleID+"<br>"+parseInt(livespeed)+" KPH <br>"+parseFloat(clat).toFixed(5)+","+parseFloat(clong).toFixed(5),'yes',100,degree,vehicleColor);
                 //  calcRoute(elat,elong,clat,clong);
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



function pushpin(map,lat,lang,zone,status,r,heading,color,lasteventDate){
      var vehicleID='<?php echo $vehicleID; ?>';
  var lat1=parseFloat(lat).toFixed(4);
  var long1=parseFloat(lang).toFixed(4);
var zone="<b> Vehicle: "+vehicleID+"<br> Lat/Long :"+lat1+"/"+long1+" <br> Last Event Time :"+lasteventDate+"<br> "+zone+" Kmph"; 
//     var zone = zone+"<br> Vehicle: "+vehicleID+"<br> "+lat1+"/"+long1;
             var car = "M17.402,0H5.643C2.526,0,0,3.467,0,6.584v34.804c0,3.116,2.526,5.644,5.643,5.644h11.759c3.116,0,5.644-2.527,5.644-5.644 V6.584C23.044,3.467,20.518,0,17.402,0z M22.057,14.188v11.665l-2.729,0.351v-4.806L22.057,14.188z M20.625,10.773 c-1.016,3.9-2.219,8.51-2.219,8.51H4.638l-2.222-8.51C2.417,10.773,11.3,7.755,20.625,10.773z M3.748,21.713v4.492l-2.73-0.349 V14.502L3.748,21.713z M1.018,37.938V27.579l2.73,0.343v8.196L1.018,37.938z M2.575,40.882l2.218-3.336h13.771l2.219,3.336H2.575z M19.328,35.805v-7.872l2.729-0.355v10.048L19.328,35.805z";
var icon = {
  path: car,
  scale: .7,
  strokeColor: 'red',
  strokeWeight: .10,
  fillOpacity: 1,
  fillColor: color,
  offset: '5%',
 
  anchor: new google.maps.Point(10, 25)
};
icon.rotation =parseInt(heading); 
               var position = new google.maps.LatLng(parseFloat(lat),parseFloat(lang));
               var marker = new google.maps.Marker({
                   position: position,
                   map: map,
                   optimized: false,
                   icon:icon,  
                   title:"Zone Id: "+zone,
                   visible: true
               });
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
 
          // var pt = new google.maps.LatLng(lat2,long2);
        //    map.setCenter(pt);
       //     map.setZoom(15);
            line.setMap(map);
      }



 function pushpin1(lat,lang,status,img,info){
            var position = new google.maps.LatLng(parseFloat(lat),parseFloat(lang));
            var marker =null;
                    marker = new google.maps.Marker({
                   position: position,
                   map: map,
                   optimized: false,
                   icon:img,  
                   
                   });

 var infowindow = new google.maps.InfoWindow({
                    content: info
              });
                if(status=='no'){
                  google.maps.event.addListener(marker, 'click', function() {
                    infowindow.open(map,marker);
                   });
             }else{infowindow.open(map,marker);}
  }



function atob(lat1,long1,lat2,long2) {

    var DirectionsDisplay;
    var directionsService = new google.maps.DirectionsService();
    var rendererOptions = {
            suppressMarkers : true,
             }
var polylineOptionsActual = new google.maps.Polyline({
    strokeColor: '#0000',
    strokeOpacity: 1.0,
    strokeWeight: 2,
     suppressMarkers : true
    });

    directionsDisplay = new google.maps.DirectionsRenderer({ suppressMarkers: true, polylineOptions: polylineOptionsActual });  
           var start = new google.maps.LatLng(lat1,long1);
            var end = new google.maps.LatLng(lat2,long2);
            var request = {
              origin: start,
              destination: end,
              travelMode: google.maps.TravelMode.DRIVING
            };
            directionsService.route(request, function(response, status) {
              if (status == google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(response);
                 //  alert('Total travel distance is: ' + (response.routes[0].legs[0].distance.value / 1000).toFixed(2) + ' km');
                directionsDisplay.setMap(map);
              } else {
              }
            });


   // get all lat lng from from driving route
  directionsService.route({
          origin: new google.maps.LatLng(lat1,long1),
          destination: new google.maps.LatLng(lat2,long2),
          travelMode: 'DRIVING'
        }, function(response, status) {
              //alert(status);
                                if (status == google.maps.DirectionsStatus.OK)
                                {
                                    directionsDisplay.setDirections (response);

                                    var pointsArray = [];
                                    
                                    pointsArray = response.routes[0].overview_path;
                                    for(var x=1;x<pointsArray.length;x++){
                                         // alert(pointsArray[x].lat());
                                         var speed=Math.floor((Math.random() * 50) + 1);
                                         var color="green";
                                         if(speed<30){
                                              color="yellow";
                                         }
                                         var img='../images1/pin30_'+color+'.png';
                                         pushpin(pointsArray[x].lat(),pointsArray[x].lng(),"no",img,x);
                                    }
                              }

        });



}

var steps = [];

function calcRoute(slat,slng,elat,elng,vehicleColor,degree) {

   icon = {
  path: car,
  scale: .7,
  strokeColor: 'red',
  strokeWeight: .10,
  fillOpacity: 1,
  fillColor: vehicleColor,
  offset: '5%',
 
  anchor: new google.maps.Point(10, 25)
};
icon.rotation =parseInt(degree);


  if (timerHandle) {
    clearTimeout(timerHandle);
  }
  if (marker) {
    marker.setMap(null);
  }
  polyline.setMap(null);
  poly2.setMap(null);
 // directionsDisplay.setMap(null);
  polyline = new google.maps.Polyline({
    path: [],
    strokeColor: '#FF0000',
    strokeWeight: 3
  });
  poly2 = new google.maps.Polyline({
    path: [],
    strokeColor: '#FF0000',
    strokeWeight: 3
  });
 
  var rendererOptions = {
    map: map
  };
  directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);

 // var start =from;
 // var end = to;
//alert(slat+"  "+slng);
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
        for (j = 0; j < steps.length; j++) {
          var nextSegment = steps[j].path;
          for (k = 0; k < nextSegment.length; k++) {
            polyline.getPath().push(nextSegment[k]);
          //  bounds.extend(nextSegment[k]);
          }
        }
      }
     // polyline.setMap(map);
     // map.fitBounds(bounds);
     // map.setZoom(18);
      startAnimation();
    }
  });
}



var step = 5; 
var tick = 10;
var eol;
var k = 0;
var stepnum = 0;
var speed = "";
var lastVertex = 1;

function updatePoly(d) {
 
  if (poly2.getPath().getLength() > 20) {
    poly2 = new google.maps.Polyline([polyline.getPath().getAt(lastVertex - 1)]);
    
  }

  if (polyline.GetIndexAtDistance(d) < lastVertex + 2) {
    if (poly2.getPath().getLength() > 1) {
      poly2.getPath().removeAt(poly2.getPath().getLength() - 1);
    }
    poly2.getPath().insertAt(poly2.getPath().getLength(), polyline.GetPointAtDistance(d));
  } else {
    poly2.getPath().insertAt(poly2.getPath().getLength(), endLocation.latlng);
  }
}

function animate(d) {
  if (d > eol) {
    map.panTo(endLocation.latlng);
    marker.setPosition(endLocation.latlng);
    return;
  }
  var p = polyline.GetPointAtDistance(d);
  map.panTo(p);
  var lastPosn = marker.getPosition();
  marker.setPosition(p);
  var heading = google.maps.geometry.spherical.computeHeading(lastPosn, p);
  icon.rotation = heading;
  marker.setIcon(icon);
  updatePoly(d);
  timerHandle = setTimeout("animate(" + (d + step) + ")", tick);
}

function startAnimation() {
  eol = polyline.Distance();
  map.setCenter(polyline.getPath().getAt(0));
  marker = new google.maps.Marker({
    position: polyline.getPath().getAt(0),
    map: map,
    icon: icon
  });

  poly2 = new google.maps.Polyline({
    path: [polyline.getPath().getAt(0)],
    strokeColor: "#0000FF",
    strokeWeight: 10
});
  setTimeout("animate(50)", 2000); 
}
google.maps.event.addDomListener(window, 'load', initialize);


var car = "M17.402,0H5.643C2.526,0,0,3.467,0,6.584v34.804c0,3.116,2.526,5.644,5.643,5.644h11.759c3.116,0,5.644-2.527,5.644-5.644 V6.584C23.044,3.467,20.518,0,17.402,0z M22.057,14.188v11.665l-2.729,0.351v-4.806L22.057,14.188z M20.625,10.773 c-1.016,3.9-2.219,8.51-2.219,8.51H4.638l-2.222-8.51C2.417,10.773,11.3,7.755,20.625,10.773z M3.748,21.713v4.492l-2.73-0.349 V14.502L3.748,21.713z M1.018,37.938V27.579l2.73,0.343v8.196L1.018,37.938z M2.575,40.882l2.218-3.336h13.771l2.219,3.336H2.575z M19.328,35.805v-7.872l2.729-0.355v10.048L19.328,35.805z";
var icon = {
  path: car,
  scale: .7,
  strokeColor: 'red',
  strokeWeight: .10,
  fillOpacity: 1,
  fillColor: '#0b622c',
  offset: '5%',
 
  anchor: new google.maps.Point(10, 25)
};

google.maps.LatLng.prototype.distanceFrom = function(newLatLng) {
  var EarthRadiusMeters = 6378137.0; 
  var lat1 = this.lat();
  var lon1 = this.lng();
  var lat2 = newLatLng.lat();
  var lon2 = newLatLng.lng();
  var dLat = (lat2 - lat1) * Math.PI / 180;
  var dLon = (lon2 - lon1) * Math.PI / 180;
  var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * Math.sin(dLon / 2) * Math.sin(dLon / 2);
  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  var d = EarthRadiusMeters * c;
  return d;
}

google.maps.LatLng.prototype.latRadians = function() {
  return this.lat() * Math.PI / 180;
}

google.maps.LatLng.prototype.lngRadians = function() {
  return this.lng() * Math.PI / 180;
}


google.maps.Polygon.prototype.Distance = function() {
  var dist = 0;
  for (var i = 1; i < this.getPath().getLength(); i++) {
    dist += this.getPath().getAt(i).distanceFrom(this.getPath().getAt(i - 1));
  }
  return dist;
}


google.maps.Polygon.prototype.GetPointAtDistance = function(metres) {

  if (metres == 0) return this.getPath().getAt(0);
  if (metres < 0) return null;
  if (this.getPath().getLength() < 2) return null;
  var dist = 0;
  var olddist = 0;
  for (var i = 1;
    (i < this.getPath().getLength() && dist < metres); i++) {
    olddist = dist;
    dist += this.getPath().getAt(i).distanceFrom(this.getPath().getAt(i - 1));
  }
  if (dist < metres) {
    return null;
  }
  var p1 = this.getPath().getAt(i - 2);
  var p2 = this.getPath().getAt(i - 1);
  var m = (metres - olddist) / (dist - olddist);
  return new google.maps.LatLng(p1.lat() + (p2.lat() - p1.lat()) * m, p1.lng() + (p2.lng() - p1.lng()) * m);
}


google.maps.Polygon.prototype.GetPointsAtDistance = function(metres) {
  var next = metres;
  var points = [];
 
  if (metres <= 0) return points;
  var dist = 0;
  var olddist = 0;
  for (var i = 1;
    (i < this.getPath().getLength()); i++) {
    olddist = dist;
    dist += this.getPath().getAt(i).distanceFrom(this.getPath().getAt(i - 1));
    while (dist > next) {
      var p1 = this.getPath().getAt(i - 1);
      var p2 = this.getPath().getAt(i);
      var m = (next - olddist) / (dist - olddist);
      points.push(new google.maps.LatLng(p1.lat() + (p2.lat() - p1.lat()) * m, p1.lng() + (p2.lng() - p1.lng()) * m));
      next += metres;
    }
  }
  return points;
}


google.maps.Polygon.prototype.GetIndexAtDistance = function(metres) {
   
    if (metres == 0) return this.getPath().getAt(0);
    if (metres < 0) return null;
    var dist = 0;
    var olddist = 0;
    for (var i = 1;
      (i < this.getPath().getLength() && dist < metres); i++) {
      olddist = dist;
      dist += this.getPath().getAt(i).distanceFrom(this.getPath().getAt(i - 1));
    }
    if (dist < metres) {
      return null;
    }
    return i;
  }
  
google.maps.Polyline.prototype.Distance = google.maps.Polygon.prototype.Distance;
google.maps.Polyline.prototype.GetPointAtDistance = google.maps.Polygon.prototype.GetPointAtDistance;
google.maps.Polyline.prototype.GetPointsAtDistance = google.maps.Polygon.prototype.GetPointsAtDistance;
google.maps.Polyline.prototype.GetIndexAtDistance = google.maps.Polygon.prototype.GetIndexAtDistance;


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
                                      livespeed=value[2];
                                      lastvaliddirection=value[3];
                                       degree=value[4];
                                      vehicleStatus=value[5];
                                     timestamp=value[6]
                                 // alert(livespeed); 
                                  });
                             });
                         }
                     });
            

}
</script>
<body>
<div id="map_canvas" style="width:100%;height:100%;"></div>
</body>
</html>
