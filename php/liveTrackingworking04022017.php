<?php
    date_default_timezone_set('Asia/Kolkata');
    require_once('config.php');
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
       <script src="../js/jquery-1.11.1.min.js"></script>
       <script src="../js/datePicker1.js" type="text/javascript"></script>
       <link rel="stylesheet" type="text/css" href="../js/jquery.datetimepicker.css"/>
       <link rel="stylesheet" type="text/css" href="../css/fuelbar.css"/>
      <script src="../js/jquery.datetimepicker.js" type="text/javascript"></script>
       <script>
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
        //  var event = eventData.split('*'); 
          google.maps.event.addDomListener(window, 'load', initialise4);
          var map;
          var drawingManager;
          var clat='';
          var clong='';
          function initialise4() {
         //   var eventData='<?php echo $data; ?>';
             var event = eventData.split('*');
           //  var curlatlng=new google.maps.LatLng(parseFloat(elat),parseFloat(elong));
             var lastevnt=event[0].split('^');
              elat=lastevnt[2];
              elong=lastevnt[3];
             var vehiclereachedin="Vehicle Stopped";
           //  if(parseInt(lastevnt[6])!=0){ 
                      //vehiclereachedin=parseFloat(estimateddis/parseInt(lastevnt[6])).toFixed(2);;
           //  }
         //    document.getElementById('info').innerHTML="<h1 style='font-size:15;color:white;'>Vehicle ID : "+vehicleID.toUpperCase()+"</h1><h1 style='font-size:15;color:white;'>Distance Travelled : "+parseInt(travelDistance)+" Km</h1><h1 style='font-size:15;color:white;'>Distance Left:"+parseInt(estimateddis)+" Km </h1><h1 style='font-size:15;color:white;'>Total Distance:"+(parseInt(travelDistance)+parseInt(estimateddis))+" Km</h1><h1 style='font-size:15;color:white;'>Reach with in:"+vehiclereachedin+" Hr</h1>";
             replay();         
         }

         function atob(lat1,long1,lat2,long2,replayornot) {
             var estimatedDistance=0;
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
                   estimatedDistance=response.routes[0].legs[0].distance.value/1000;
                   directionsDisplay.setDirections(response);
                   directionsDisplay.setMap(map);
                 
              } else {
              
              }
            });

         if(replayornot=="no")
             alert("close");
 
      }

      function sleepFor( sleepDuration ){
           var now = new Date().getTime();
           while(new Date().getTime() < now + sleepDuration){ /* do nothing */ } 
      }
      function pushpin(map,lat,lang,zone,status,r,img){

               var position = new google.maps.LatLng(parseFloat(lat),parseFloat(lang));
               var marker = new google.maps.Marker({
                   position: position,
                   map: map,
                   optimized: false,
                   icon:img,  
                   title:"Zone Id: "+zone,
                   visible: true
               });
              // moveBus(map,marker);
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





    function moveBus( map, marker ) {

    marker.setPosition( new google.maps.LatLng( 0, 0 ) );
    map.panTo( new google.maps.LatLng( 0, 0 ) );

};


       function circleCreation(lat,long,map,color,opacity,r,geozone){
                
             var populationOptions = {
                 strokeColor: color,
                 strokeOpacity: 0.5,
                 strokeWeight: 2,
                 fillColor: color,
                 fillOpacity: opacity,
                 
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
       function replay(){
           //  var eventData='<?php echo $data; ?>';
             var event = eventData.split('*');

              var replayrate=15000; 
              var center=event[0].split('^');
              var mapOptions = {
                  zoom: 20,
                  mapTypeControlOptions: {
                        mapTypeIds: [google.maps.MapTypeId.SATELLITE, google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.HYBRID],
                        position: google.maps.ControlPosition.RIGHT_CENTER
                    },

                  center: new google.maps.LatLng(parseFloat(center[2]),parseFloat(center[3]))
               };
              var mapDiv = document.getElementById('mapcontainer');
                map = new google.maps.Map(mapDiv, mapOptions);
             // pushpin(map,parseFloat(center[2]),parseFloat(center[3]),callerinfo,'yes',1000,"../images1/help.gif"); 
             // circleCreation(parseFloat(center[2]),parseFloat(center[3]),map,'red',0.5,1000,"Incident Location");
              



              var lineSymbol = {
                  path: 'M 1,1 1,1',
                  strokeOpacity: 1,
                  scale: 1
               };
           
             for(var m=0;m<event.length-1;m++){

                    var evntdata=event[m].split('^');
                     var evntdata2=event[m+1].split('^')
                 //   vehicleTrack(evntdata[2],evntdata[3],lineSymbol,map,evntdata[6],"no",evntdata[7]);
                   //replayPath(evntdata[2],evntdata[3],lineSymbol,map,evntdata2[2],evntdata2[3]);
              }
                var lastevntdata=event[0].split('^');
              oldspeed=parseInt(lastevntdata[6]);
             lastvaliddirectionold=lastevntdata[7];
       //      atob(inlat,inlng,elat,elong,'yes');
            
            var imageselect="../images1/online_caseassigned.png";
              if(oldspeed>0) imageselect="../images1/online_caseassigned.png";
              else imageselect="../images1/idle_caseassigned.png";

              var markerImage = new google.maps.MarkerImage(RotateIcon
            .makeIcon(
                imageselect)
            .setRotation({deg:lastevntdata[8]})
            .getUrl(),
            new google.maps.Size(80,40)
                );
            pushpin(map,elat,elong,"Vehicle Current Location",'no',1000,markerImage);
        //  pushpin(map,elat,elong,"Vehicle Current Location",'yes',1000,"../images1/online.png");
             getLiveEvent();
           /*   var position = new google.maps.LatLng(parseFloat(elat),parseFloat(elong));
                 gifmarker = new google.maps.Marker({
                   position: position,
                   map: map,
                   optimized: false,
                   icon:"http://glotrax.glovision.co:8080/track/extra/images/pp/CrosshairRed.gif",  
                   visible: true
               });
*/
              var id=setInterval(function(){
                        getLiveEvent();
             
                    dummymarker.setMap(null);
                     vehicleTrack(dummymarker.position.lat(),dummymarker.position.lng(),lineSymbol,map,oldspeed,"no",lastvaliddirectionold);
                    
                   // pushpin(map,dummymarker.position.lat(),dummymarker.position.lng(),'','no',1000,'');
                   //  pushpin(map,clat,clong,parseInt(livespeed)+" KPH",'yes',1000,"../images/vehicle.png");
 
                  //gif image
 //                 gifmarker.setMap(null);
                 var position = new google.maps.LatLng(parseFloat(clat),parseFloat(clong));
          /*       gifmarker = new google.maps.Marker({
                   position: position,
                   map: map,
                   optimized: false,
                   icon:"http://glotrax.glovision.co:8080/track/extra/images/pp/CrosshairRed.gif",  
                   visible: true
               });
*/
                 
//                  pushpin(map,clat,clong,parseInt(livespeed)+" KPH",'no',1000,"http://glotrax.glovision.co:8080/track/extra/images/pp/CrosshairRed.gif");
              imageselect="../images1/online_caseassigned.png";
           
              if(vehicleStatus=="online") imageselect="../images1/online_caseassigned.png";
              else if(vehicleStatus=="idle")imageselect="../images1/idle_caseassigned.png";
              else{
               imageselect="../images1/offline_caseassigned.png";

              }
            //  alert(imageselect+"  "+vehicleStatus)
               markerImage = new google.maps.MarkerImage(RotateIcon
            .makeIcon(
                imageselect)
            .setRotation({deg: degree})
            .getUrl(),
                new google.maps.Size(90, 40)
                );

             //    pushpin(map,clat,clong,parseInt(livespeed)+" KPH",'yes',1000,"../images1/online.png"); 
              //   pushpin(map,clat,clong,parseInt(livespeed)+" KPH",'yes',1000,markerImage);
               pushpin(map,clat,clong,parseInt(livespeed)+" KPH <br>"+parseFloat(clat).toFixed(5)+","+parseFloat(clong).toFixed(5),'yes',1000,markerImage);
                  oldspeed=livespeed;
                   //  pushpin(map,elat,elong,'','no',1000,''); 
                 //   vehicleTrack(clat,clong,lineSymbol,map,livespeed,"yes");
                    replayPath(elat,elong,lineSymbol,map,clat,clong);
                    elat=clat;
                    elong=clong;
                    lastvaliddirectionold=lastvaliddirection;
             /*       if(parseInt(estimateddis)<=1){
                       
                     clearTimeout(id);
                        alert("Vehicle Reached");
                     return;
                      
                    }
             */
           
                },replayrate);
 
        

            /* var m=0;
             var id=setInterval(function(){ 
                     if(m>event.length-2){
                             pushpin(map,elat,elong,"Vehicle Current Location",'yes',1000,"../images/vehicle.png");
                            clearTimeout(id);return;

                     }
                     else{
                         var evntdata=event[m].split('^');
                         var evntdata1=event[m+1].split('^');
                        
                      if(evntdata1[5]>0.7 && (accountID=="gvkgjtest" ||  accountID=="goghealth" ||  accountID=="gvk-ut-gogh")){
                  
                               atob(evntdata[2],evntdata[3],evntdata1[2],evntdata1[3],'yes');
                                 
                             }else{
                              
  
                         vehicleTrack(evntdata[2],evntdata[3],lineSymbol,map,evntdata1[2],evntdata1[3],evntdata[6]);
                          }
                         m++;


                    }


                },replayrate);*/




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
                                      vehicleStatus=value[5]
                                 // alert(livespeed); 
                                  });
                             });
                         }
                     });
            

}


 
        function vehicleTrack(lat,long,lineSymbol,map,speed,status1,direction){

  

             speed=parseInt(speed);
             var pincolor='yellow';
             if(speed>20 && speed<40){
                 pincolor='green';
             }else if(speed>40){
                   pincolor='orange';
             }
             var directionimage='../images1/pin30_'+pincolor+"_"+direction+".png";

             
 
          /*

             var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=.|" +pincolor,new google.maps.Size(30, 40),
        new google.maps.Point(0,0),
        new google.maps.Point(24, 24));
           */
        
             pushpin(map,lat,long,"Speed:"+speed+" Kmph",status1,1000,directionimage);
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
    

var rad = function(x) {
  return x * Math.PI / 180;
};

function getDistance(p1, p2) {
  var R = 6378137; 
  var dLat = rad(p2.lat() - p1.lat());
  var dLong = rad(p2.lng() - p1.lng());
  var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
    Math.cos(rad(p1.lat())) * Math.cos(rad(p2.lat())) *
    Math.sin(dLong / 2) * Math.sin(dLong / 2);
  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  var d = R * c;
 return d/1000; 
}


</script>
      
   </head>
   <style>
      html { height: 100% } body { height: 100%; margin: 0; padding: 0 }
      #mapcontainer { height: 100%; width:100% }
      #wrapper { position: relative; }
     
      #info {border-radius: 10px; position: absolute; background-color: transparent;height:30%; width:25%;top:0px; left:0px; z-index: 99; background: #086A87;opacity:0.8;}


   </style>
                  <body onload="">
       <div id="wrapper">
           <div  id="mapcontainer" class="mapcontainer" >   </div>
       </div>




   </body>
</html>


