 var map ; 
google.maps.event.addDomListener(window, 'load', initialise4);
 var bounds1 = new google.maps.LatLngBounds();
var lineSymbol1 = {
                   path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
                   strokeColor: '#FF0040' //arrow color
              };

 function initialise4(){
             var  myLatlng1 = new google.maps.LatLng(21.0000, 78.0000);
                 var mapOptions = {
                    zoom: 5,
                    center: myLatlng1,
                     mapTypeControlOptions: {
                                 mapTypeIds: [google.maps.MapTypeId.SATELLITE, google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.HYBRID],
                                 position: google.maps.ControlPosition.LEFT_CENTER
                             },
                             mapTypeId: google.maps.MapTypeId.ROADMAP,
                             streetViewControl: true,
                             mapTypeControl: true,
                             show_markers: false,
                             type: 'polygon',
                             draggable: true

                 }
          map = new google.maps.Map(document.getElementById('mapcontainer'), mapOptions);
   new google.maps.Map(document.getElementById('secondmap'), mapOptions);
        }

function reloadmap(){

                 var mapOptions22 = {
                       zoom:map.getZoom(),
                      center:map.getCenter(),
                       mapTypeControlOptions: {
                                 mapTypeIds: [google.maps.MapTypeId.SATELLITE, google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.HYBRID],
                                 position: google.maps.ControlPosition.LEFT_CENTER
                             },
                             mapTypeId: google.maps.MapTypeId.ROADMAP,
                             streetViewControl: true,
                             mapTypeControl: true,
                             show_markers: false,
                             type: 'polygon',
                             draggable: true

                 }
           map=null;
          map = new google.maps.Map(document.getElementById('mapcontainer'), mapOptions22);




}

 function mapview(){
   window.localStorage.setItem('viewtype',"map");
  document.getElementById('allreports').style.display="none";
  document.getElementById('reportarea').style.display="none";
  document.getElementById('mapcontainer').style.display="block";
  document.getElementById('mapcontainer').style.left="0%"; 
   document.getElementById('mapcontainer').style.width="99.5%";
   document.getElementById("availableVehicle").style.left = "0%";
   document.getElementById("vehicleStatusReport").style.left = "0%";
  document.getElementById("vehicleStatusReport").style.display = "none";
  document.getElementById("availableVehicle").style.display = "none";
var center = map.getCenter();
center=new google.maps.LatLng(22.2587,71.1924);
google.maps.event.trigger(map, "resize");
map.setCenter(center);
map.setZoom(6);

   
}
function textview(){
                window.localStorage.setItem('viewtype',"text");
       map.setZoom(6);
     document.getElementById('allreports').style.display="none";
      document.getElementById('reportarea').style.display="none";
       document.getElementById('mapcontainer').style.display="none";
       document.getElementById('mapcontainer').style.left="100%";
       document.getElementById('mapcontainer').style.width="0%" 
         document.getElementById("availableVehicle").style.left = "0%";
         document.getElementById("availableVehicle").style.top = "18%";
         document.getElementById("availableVehicle").style.width = "100%";
         document.getElementById("vehicleStatusReport").style.left = "0%";
         document.getElementById("vehicleStatusReport").style.top = "17.5%";
         document.getElementById("vehicleStatusReport").style.width = "100%"
         document.getElementById("vehicleStatusReport").style.height = "82.3%"
         document.getElementById("vehicleStatusReport").style.display = "block";
         document.getElementById("availableVehicle").style.display = "none";

}
function bothview(){
      document.getElementById('allreports').style.display="none";
   document.getElementById('reportarea').style.display="none";
document.getElementById('mapcontainer').style.display="block";
   document.getElementById('mapcontainer').style.left="50%";
   document.getElementById('mapcontainer').style.width="49.5%";
         document.getElementById("availableVehicle").style.left = "0%";
         document.getElementById("availableVehicle").style.width = "50%";
                  document.getElementById("vehicleStatusReport").style.left = "0%";
                  document.getElementById("vehicleStatusReport").style.width = "50%"
                             document.getElementById("vehicleStatusReport").style.display = "block";
                                          document.getElementById("availableVehicle").style.display = "none";
  if(document.getElementById("availableVehicle").style.display=="none"){
          document.getElementById("vehicleStatusReport").style.top = "17.5%";
    }
var center = map.getCenter();
center=new google.maps.LatLng(22.2587,71.1924);
google.maps.event.trigger(map, "resize");
map.setCenter(center);
     map.setZoom(map.getZoom());
 window.localStorage.setItem('viewtype',"both");

}

function setbound(bounds1){

   map.fitBounds(bounds1);
}
function showKml(){
   var kmlPath = "http://gj.glovision.co/gjdashboardnew/India-States.kml";
  var urlSuffix = (new Date).getTime().toString();
  var layer = new google.maps.KmlLayer(kmlPath + '?' + urlSuffix ,{ suppressInfoWindows: true,preserveViewport: false });
  layer.setMap(map);
}

function imageRotate(car,carcolor,degree){

var icon = {
  path: car,
  scale: .7,
  strokeColor: 'red',
  strokeWeight: .10,
  fillOpacity: 1,
  fillColor: carcolor,
  offset: '5%',

  anchor: new google.maps.Point(10, 25)
};
 icon.rotation=degree;
return icon;
}
function removeMarkers1(){
    for(var i=0; i<markers1.length; i++){
          markers1[i].setMap(null);
      }
           markers1=[];
         if(oldmarker!=null || oldmarker!=undefined)
        {
            oldmarker.setMap(null);
            oldmarker=null;

        }
    bounds1 = new google.maps.LatLngBounds(null);

}

function getlatlongforaddress(address){
   var geocoder = new google.maps.Geocoder();
  geocoder.geocode( { 'address': address}, function(results, status) {

  if (status == google.maps.GeocoderStatus.OK) {
      var latitude = results[0].geometry.location.lat();
      var longitude = results[0].geometry.location.lng();
      }
  });

}




function MapClear() {
       if(nochangeofflineonlineidle=='no'){
                window.localStorage.setItem('showmap',"clear");
                }
                if(vehicleWithEventData.length>0){
            var markerinfo=[];
            var zoom1=8;
            var myLatlng1;
            var mapInitializeOnce=0;

            var vehicleAvailableIndex=0;
            for(var i=0;i<vehicleWithEventData.length;i++){

                var lastEventOfVehicle=vehicleWithEventData[i].length-1;
                var mapOptions ;

                if(vehicleWithEventData[i].length>0){
                        var lastEvntDate=stringFormatToDate(stringFormatToDateForFuel(vehicleWithEventData[i][lastEventOfVehicle][4]+""));
                        var epoc1=dateToEpochDB(lastEvntDate);
                        var currentTime = new Date();
                    var epoc2=dateToEpochDB(currentTime);
                    if(mapInitializeOnce==0){
                                myLatlng1 = new google.maps.LatLng(parseFloat(vehicleWithEventData[i][0][6]),parseFloat(vehicleWithEventData[i][0][7]));
                         mapOptions = {
                             zoom: zoom1,
                             center: myLatlng1,
                             mapTypeControlOptions: {
                                 mapTypeIds: [google.maps.MapTypeId.SATELLITE, google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.HYBRID],
                                  position: google.maps.ControlPosition.LEFT_CENTER
                             },
                             mapTypeId: google.maps.MapTypeId.ROADMAP,
                             streetViewControl: true,
                             mapTypeControl: true,
                             show_markers: false,
                             type: 'polygon',
                             draggable: true
                             }
                         map = new google.maps.Map(document.getElementById('mapcontainer'), mapOptions);

                                 mapInitializeOnce++;
                         }


                         vehicleAvailable[vehicleAvailableIndex]=[];

                             vehicleAvailable[vehicleAvailableIndex][0]=vehicleWithEventData[i][0][9];
                             vehicleAvailable[vehicleAvailableIndex][1]=vehicleWithEventData[i][lastEventOfVehicle][2];
                             vehicleAvailable[vehicleAvailableIndex][2]=vehicleWithEventData[i][lastEventOfVehicle][5];
                             vehicleAvailable[vehicleAvailableIndex][3]=vehicleWithEventData[i][lastEventOfVehicle][8];
                             vehicleAvailable[vehicleAvailableIndex][4]=vehicleWithEventData[i][lastEventOfVehicle][10];
                             vehicleAvailable[vehicleAvailableIndex][5]=vehicleWithEventData[i][lastEventOfVehicle][3];
                           vehicleAvailable[vehicleAvailableIndex][6]=vehicleWithEventData[i][lastEventOfVehicle][12];
                         vehicleAvailable[vehicleAvailableIndex][7]=vehicleWithEventData[i][lastEventOfVehicle][13];
                       vehicleAvailable[vehicleAvailableIndex][8]=vehicleWithEventData[i][lastEventOfVehicle][11];
                     vehicleAvailable[vehicleAvailableIndex][9]=vehicleWithEventData[i][lastEventOfVehicle][14];
                    vehicleAvailable[vehicleAvailableIndex][10]=vehicleWithEventData[i][lastEventOfVehicle][6];
                    vehicleAvailable[vehicleAvailableIndex][11]=vehicleWithEventData[i][lastEventOfVehicle][7];
                    vehicleAvailable[vehicleAvailableIndex][12]=vehicleWithEventData[i][lastEventOfVehicle][4];
                     vehicleAvailable[vehicleAvailableIndex][13]=vehicleWithEventData[i][lastEventOfVehicle][16];
                    vehicleAvailable[vehicleAvailableIndex][14]=vehicleWithEventData[i][lastEventOfVehicle][20];
                        }else{

                  var res=['','',''];
                 var time='';
                for(var j=0;j<globalVehicles1.length;j++){
                        var dis=globalVehicles1[j].toUpperCase();
                        if (dis.indexOf(globalVehicles[i].toUpperCase()) !=-1) {
                                 var res=globalVehicles1[j].split("][");
                       time=globalVehicles2[j];
                             break;
                        }
                }
               for(var j=0;j<globalVehicles2.length;j++){
                   var dis=globalVehicles2[j].toUpperCase();
                if (dis.indexOf(globalVehicles[i].toUpperCase()) !=-1) {
                         time=globalVehicles2[j];
                                           break;
                     }
                }
                                     var text='';
                  if(time=="" || time==0 || time=="0" || (time === null && typeof time === "object") || time=="null"){
                    text="Vehicle Repair";
                 }else{
                    text="Vehicle Repair1";
                   }
                 vehicleAvailable[vehicleAvailableIndex]=[];
                    vehicleAvailable[vehicleAvailableIndex][0]=globalVehicles[i];
                    vehicleAvailable[vehicleAvailableIndex][1]="0000";
                    vehicleAvailable[vehicleAvailableIndex][2]="0";
                    vehicleAvailable[vehicleAvailableIndex][3]="No Location";
                    vehicleAvailable[vehicleAvailableIndex][4]=text;
                    vehicleAvailable[vehicleAvailableIndex][5]="";
                          vehicleAvailable[vehicleAvailableIndex][6]=res[2]+"/"+res[1];
                         vehicleAvailable[vehicleAvailableIndex][7]="";
                        vehicleAvailable[vehicleAvailableIndex][8]="";
                       vehicleAvailable[vehicleAvailableIndex][9]=res[3];
                      vehicleAvailable[vehicleAvailableIndex][10]='';
                        vehicleAvailable[vehicleAvailableIndex][11]='';
                         vehicleAvailable[vehicleAvailableIndex][12]="";
                     vehicleAvailable[vehicleAvailableIndex][13]="";
                     vehicleAvailable[vehicleAvailableIndex][14]="00:00:00";

                }
                vehicleAvailableIndex++;
            }//end loop


            availabilityVehicles();

           }//end if   

}


function setZoom(level){
    map.setZoom(level);
}
function setCenter(lat,lng){
    map.setCenter(new google.maps.LatLng(lat,lng));
}


 function DrawLineMarkerToMarker(vehicleNo,eventInx,map,lineSymbol){
      var lat=parseFloat(vehicleWithEventData[vehicleNo][eventInx][6]);
                 var lang=parseFloat(vehicleWithEventData[vehicleNo][eventInx][7]);
                 var title1="vehicle Id: "+vehicleWithEventData[vehicleNo][eventInx][9]+"  Address : "+vehicleWithEventData[vehicleNo][eventInx][8]+"  Odometer Reading: "+vehicleWithEventData[vehicleNo][eventInx][5];
             CheckIgnitionOnOffIdelStop(lat,lang,vehicleNo,eventInx,map,title1,'yes');
                 var lineCoordinates = [
                      new google.maps.LatLng(lat,lang),
                          new google.maps.LatLng(parseFloat(vehicleWithEventData[vehicleNo][eventInx+1][6]),parseFloat(vehicleWithEventData[vehicleNo][eventInx+1][7]))
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

              line.setMap(map);

  }

function removegroupIcong(gm){

 gm.setMap(null);
}

function groupPushpin(lat,lang,map,image,title1,infoStatus,groupName,count){
       map.setZoom(map.getZoom());
            map.setCenter(map.getCenter());
                var position = new google.maps.LatLng(lat,lang);
              var marker = new MarkerWithLabel({
       position: position,
       icon: " ",
     labelText:groupName.toUpperCase()+"-"+count,
      labelContent:'G',
      labelClass: "labels", // the CSS class for the label
      labelStyle: {opacity: 0.80},
    labelInBackground:true
     });
            var iw = new google.maps.InfoWindow({
            content:title1
       });
         marker.addListener('click', function() {
             window.localStorage.setItem('showmap',"all");
              document.getElementById("groupdevice").value=groupName;
           document.getElementById("ad2").value=groupName;
          document.getElementById("svehicle").value="";
         document.getElementById("ad").value="";
          callfromlabel();


        });
             if(infoStatus=='yes'){
               iw.open(map, marker);
         }
           groupmarkers.push(marker);

}


function putPushpinVehicles(lat,lang,map,image,title1,vehicle,infoStatus,degree){

  var position = new google.maps.LatLng(lat,lang);
     var markerImage = new google.maps.MarkerImage(RotateIcon
            .makeIcon(image)
            .setRotation({deg: degree})
            .getUrl(),
                new google.maps.Size(60, 60),
                new google.maps.Point(0, 0),
                new google.maps.Point(5, 5));
     var showvehicles=window.localStorage.getItem('showmap');

     if(showvehicles!="single"){
            singlevehecleRefresh=0;
            var marker = new google.maps.Marker({
               position: position,
               map: map,
               icon:image,
           });

   var     openedinfowindow1 = new google.maps.InfoWindow({
            content:"<div>"+title1+"</div>"
       });
         marker.addListener('click', function() {
             openedinfowindow1.open(map, marker);
        });

      markers1.push(marker);
         bounds1.extend(marker.getPosition());
        }else{
          singlevehecleRefresh++;
            map.setZoom(map.getZoom());

            map.setCenter(new google.maps.LatLng(lat,lang));
          var markerImage = new google.maps.MarkerImage(RotateIcon
            .makeIcon(
                image)
            .setRotation({deg: degree})
            .getUrl(),
                new google.maps.Size(49, 60),
                new google.maps.Point(0, 0),
                new google.maps.Point(5, 5));

     var marker;
        var labelshow=document.getElementById("markerwithlable");
       if(labelshow.checked){

              marker = new MarkerWithLabel({
               position: position,
              map:map,
              icon:image,
              labelText:vehicle,
              labelContent:'G',

               labelAnchor: new google.maps.Point(20, 0),
               labelClass: "labels", // the CSS class for the label
               labelStyle: {opacity: 0.75},
              shadow:"images1/pin30_green.png"
            });
      }else{
                       marker = new google.maps.Marker({
               position: position,
               map: map,
               icon:image,
            });
        }


        var iw = new google.maps.InfoWindow({
            content:title1
       });
         marker.addListener('click', function() {
             iw.open(map, marker);
        });
       if(infoStatus=='yes'){
             iw.open(map, marker);

           }
        if(oldmarker!=null || oldmarker!=undefined)
               oldmarker.setMap(null);
         oldmarker=marker;

          }

}

function GetAddress(lat1,long1,map,image,vehicleTitle,vehicle,st,subtitle,degree) {
   var lat = parseFloat(lat1);
        var lng = parseFloat(long1);

        var latlng = new google.maps.LatLng(lat, lng);
        var geocoder = geocoder = new google.maps.Geocoder();
        geocoder.geocode({ 'latLng': latlng }, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[1]) {
                    vehicleTitle=vehicleTitle+ "<br>  Address : <b>"+results[1].formatted_address+"</b>"+subtitle;
                   putPushpinVehicles(lat1,long1,map,image,vehicleTitle,vehicle,st,degree);

                }
            }
        });

}


function replayPath(vehicleNo,eventInx,lineSymbol,map,contentString){
      var lineCoordinates = [
              new google.maps.LatLng(parseFloat(vehicleWithEventData[vehicleNo][eventInx][6]),parseFloat(vehicleWithEventData[vehicleNo][eventInx][7])),
              new google.maps.LatLng(parseFloat(vehicleWithEventData[vehicleNo][eventInx+1][6]),parseFloat(vehicleWithEventData[vehicleNo][eventInx+1][7]))
          ];
          var line = new google.maps.Polyline({
              icons: [{
                  icon: lineSymbol,
                  offset: '100%'
              }],
              path: lineCoordinates,
              geodesic: true,
              label: contentString,
              strokeColor: '#4e5eb0',
              strokeOpacity: 1.0,
              strokeWeight: 2
              });
            line.setMap(map);

}



function allVehicleRoute(map){
     for(var vehicleIndx=0;vehicleIndx<vehicleWithEventData.length;vehicleIndx++){
                        var color1='#'+parseInt(Math.random()*100000000).toString(16);
                        var lineSymbol = {
                                  path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
                                  strokeColor: color1 //arrow color

                         };
                        var templat;
                        var templang;
                        for(var eventInx=0;eventInx<vehicleWithEventData[vehicleIndx].length-2;eventInx=eventInx+20){
                                if(eventInx==0){
                                   templat=parseFloat(vehicleWithEventData[vehicleIndx][eventInx][6]);
                                   templang=parseFloat(vehicleWithEventData[vehicleIndx][eventInx][7]);
                                }
                                var title="vehicle Id: "+vehicleWithEventData[vehicleIndx][eventInx][9]+"  Address : "+vehicleWithEventData[vehicleIndx][eventInx][8]+"  Odometer Reading: "+vehicleWithEventData[vehicleIndx][eventInx][5];

                                CheckIgnitionOnOffIdelStop(templat,templang,vehicleIndx,eventInx,map,title,'no');
                    var lineCoordinates = [
                        new google.maps.LatLng(templat,templang),
                        new google.maps.LatLng(parseFloat(vehicleWithEventData[vehicleIndx][eventInx+1][6]),parseFloat(vehicleWithEventData[vehicleIndx][eventInx+1][7]))
                    ];
                        templat=parseFloat(vehicleWithEventData[vehicleIndx][eventInx+1][6]);
                                templang=parseFloat(vehicleWithEventData[vehicleIndx][eventInx+1][7]);
                    var line = new google.maps.Polyline({
                    icons: [{
                              icon: lineSymbol,
                              offset: '100%'
                            }],
                     path: lineCoordinates,
                    geodesic: true,
                    strokeColor: color1,
                    strokeOpacity: 1.0,
                    strokeWeight: 2
                    });

                    line.setMap(map);

           }//end inner loop
     }//end outer loop
}//end allVehicleRoute


