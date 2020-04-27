 var map=null;
 var bounds1 =L.latLngBounds(); ;
 var lineSymbol1 = {
    //  path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
      strokeColor: '#FF0040' //arrow color
 };
$(document).ready(function(){
// your code
//alert();
initialise4();
 });
 function initialise4(){
    if(map==null) {
      map = L.map('mapcontainer').setView([21.0000, 78.0000], 5);
      L.tileLayer('http://maps3.glovision.co/tile/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'}).addTo(map);
    map.invalidateSize();
  }
      //  mapHitTrack(mapkeytype,"map");
}

 function reloadmap(){
     map=null;
       map = L.map('mapcontainer').setView([21.0000, 78.0000], 5);
      L.tileLayer('http://maps3.glovision.co/tile/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'}).addTo(map);
      map.invalidateSize();
}

function imageRotate(car,carcolor,degree){
     document.getElementById("liveimg").src = car;
     var base64=null;
          base64 = getBase64Image(document.getElementById("liveimg"), degree);
   alert(base64); 
return base64;
}


 function mapview(){
  window.localStorage.setItem('viewtype',"map");
  document.getElementById('legend').style.display='block';
  //document.getElementById('contactsearch').style.display="block";
 // document.getElementById('contactsearch').style.left="38%";
  document.getElementById('allreports').style.display="none";
  document.getElementById('reportarea').style.display="none";
  document.getElementById('mapcontainer').style.display="block";
  document.getElementById('mapcontainer').style.left="0%"; 
  document.getElementById('mapcontainer').style.width="99.5%";
  document.getElementById("availableVehicle").style.left = "0%";
  document.getElementById("vehicleStatusReport").style.left = "0%";
  document.getElementById("vehicleStatusReport").style.display = "none";
  document.getElementById("availableVehicle").style.display = "none";
map.invalidateSize();
 map.setZoom(6);
 //map.setView(new L.LatLng(map.getCenter().lat(),map.getCenter().lng()), 6);
}
function textview(){
  window.localStorage.setItem('viewtype',"text");
  if(map!=null)
      map.setZoom(6);
 // document.getElementById('contactsearch').style.display="none";
 // document.getElementById('contactsearch').style.left="38%";
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
map.invalidateSize();
}

function bothview(){
  document.getElementById('legend').style.display='block'; 
 // document.getElementById('contactsearch').style.display="block";
 // document.getElementById('contactsearch').style.left="55%"
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
map.invalidateSize();
 map.setZoom(6);
// map.setView(new L.LatLng(map.getCenter().lat(),map.getCenter().lng()), 6);
  window.localStorage.setItem('viewtype',"both");
}

function setbound(bounds1){
    //map.fitBounds(bounds1);
}
function showKml(){
/*    var kmlPath = "http://up108.glovision.co/upmergeddashboard/Labelled.kml";
    var urlSuffix = (new Date).getTime().toString();
    var layer = new google.maps.KmlLayer(kmlPath + '?' + urlSuffix ,{ suppressInfoWindows: true,preserveViewport: false });
   layer.setMap(map);
*/
}

function removeMarkers1(){
     for(var i=0; i<markers1.length; i++){
         markers1[i].remove();
         markers1[i].closePopup();
     }
     markers1=[];
     if(oldmarker!=null || oldmarker!=undefined) {
         oldmarker.remove();
         oldmarker.closePopup();
         oldmarker=null;
     }
     bounds1 = null;
}

function getlatlongforaddress(address){
/*  var geocoder = new google.maps.Geocoder();
  geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
          var latitude = results[0].geometry.location.lat();
          var longitude = results[0].geometry.location.lng();
          alert(latitude+"  "+longitude);
      }
  });*/
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
               
                       map = L.map('mapcontainer').setView([parseFloat(vehicleWithEventData[i][0][6]),parseFloat(vehicleWithEventData[i][0][7])], zoom1);
                       L.tileLayer('http://maps3.glovision.co/tile/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'}).addTo(map);
                      map.invalidateSize();


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
                 vehicleAvailable[vehicleAvailableIndex][15]=vehicleWithEventData[i][lastEventOfVehicle][23];
                 vehicleAvailable[vehicleAvailableIndex][16]=vehicleWithEventData[i][lastEventOfVehicle][24];
                 vehicleAvailable[vehicleAvailableIndex][17]=vehicleWithEventData[i][lastEventOfVehicle][25];
                 vehicleAvailable[vehicleAvailableIndex][18]=vehicleWithEventData[i][lastEventOfVehicle][26];
                 vehicleAvailable[vehicleAvailableIndex][19]=vehicleWithEventData[i][lastEventOfVehicle][27];
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
               vehicleAvailable[vehicleAvailableIndex][15]="";
               vehicleAvailable[vehicleAvailableIndex][16]="";
               vehicleAvailable[vehicleAvailableIndex][17]="";
               vehicleAvailable[vehicleAvailableIndex][18]="";
               vehicleAvailable[vehicleAvailableIndex][19]="";
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
    map.setView(new L.LatLng(lat,lng), 10);
}


function DrawLineMarkerToMarker(vehicleNo,eventInx,map,lineSymbol){
   var lat=parseFloat(vehicleWithEventData[vehicleNo][eventInx][6]);
   var lang=parseFloat(vehicleWithEventData[vehicleNo][eventInx][7]);
   var title1="vehicle Id: "+vehicleWithEventData[vehicleNo][eventInx][9]+"  Address : "+vehicleWithEventData[vehicleNo][eventInx][8]+"  Odometer Reading: "+vehicleWithEventData[vehicleNo][eventInx][5];
   CheckIgnitionOnOffIdelStop(lat,lang,vehicleNo,eventInx,map,title1,'yes');
   var pointA = new L.LatLng(lat, lang);
   var pointB = new L.LatLng(parseFloat(vehicleWithEventData[vehicleNo][eventInx+1][6]),parseFloat(vehicleWithEventData[vehicleNo][eventInx+1][7]));
   var pointList = [pointA, pointB];

   var firstpolyline = new L.Polyline(pointList, {
       color: '#4b238a',
       weight: 3,
      opacity: 0.8,
      smoothFactor: 3
   });
  firstpolyline.addTo(map);

}

function groupPushpin(lat,lang,map,image,title1,infoStatus,groupName,count){
    //map.setView(new L.LatLng(map.getCenter().lat(),map.getCenter().lng()), map.getZoom());
     map.getZoom(6);
      var marker = new L.marker([lat,lang], { opacity:1 }); //opacity may be set to zero
     marker.bindTooltip(groupName.toUpperCase()+"-"+count, {permanent: true, className: "my-label", offset: [0, 0] });
    marker.addTo(map).bindPopup(title1).on('click', function(e) {
          window.localStorage.setItem('showmap',"all");
       document.getElementById("groupdevice").value=groupName;
       document.getElementById("ad2").value=groupName;
       document.getElementById("svehicle").value="";
       document.getElementById("ad").value="";
       callfromlabel();

    });
    groupmarkers.push(marker);
}

var oldmarker=null;
function putPushpinVehicles(lat,lang,map,image,title1,vehicle,infoStatus,degree){
     var position =L.latLng(lat,lang);
     document.getElementById("liveimg").src = image;
     var base64=null;
          base64 = getBase64Image(document.getElementById("liveimg"), degree);
     var markerImage =L.icon({iconUrl: base64,iconSize: [25, 32],
                             iconAnchor: [16,32],
                             popupAnchor: [0, -51]});


 

     var showvehicles=window.localStorage.getItem('showmap');
     if(showvehicles!="single"){
        singlevehecleRefresh=0;
         var marker= L.marker(position, {icon: markerImage}).addTo(map).bindPopup("<div>"+title1+"</div>");
         markers1.push(marker);
        // bounds1.extend(marker.getLatLng());
       }else{
          singlevehecleRefresh++;
          map.setView(new L.LatLng(lat,lang), map.getZoom());
         var marker;
         var labelshow=document.getElementById("markerwithlable");
         if(labelshow.checked){
              marker=L.marker(position, {icon: markerImage});
              marker.bindTooltip(vehicle, {permanent: true, className: "my-label", offset: [0, 0] });
              marker.addTo(map); 
              marker.bindPopup(title1);  
        }else{
             marker=L.marker(position, {icon: markerImage});
             marker.addTo(map);
             marker.bindPopup(title1);

        }
        if(infoStatus=='yes'){
           //  iw.open(map, marker);
            //marker.openPopup();
        }
        if(oldmarker!=null || oldmarker!=undefined){
             map.removeLayer(oldmarker);
            
         }
            //oldmarker.setMap(null);
        //   alert(oldmarker);
          //  oldmarker.remove();
           // oldmarker.closePopup();
            oldmarker=marker;
        }
 }


function GetAddress(lat1,long1,map,image,vehicleTitle,vehicle,st,subtitle,degree) {
    var lat = parseFloat(lat1);
    var lng = parseFloat(long1);
/*
    var latlng = new google.maps.LatLng(lat, lng);
    var geocoder = geocoder = new google.maps.Geocoder();
    geocoder.geocode({ 'latLng': latlng }, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            if (results[1]) {
                vehicleTitle=vehicleTitle+ "<br>  Address : <b>"+results[1].formatted_address+"</b>"+subtitle;
                putPushpinVehicles(lat1,long1,map,image,vehicleTitle,vehicle,st,degree);
            }
        }
    });*/
   vehicleTitle=vehicleTitle+subtitle;
   putPushpinVehicles(lat1,long1,map,image,vehicleTitle,vehicle,st,degree);

}

function removegroupIcong(gm){

 gm.remove();
 gm.closePopup();
}

function replayPath(vehicleNo,eventInx,lineSymbol,map,contentString){
   var pointA = new L.LatLng(parseFloat(vehicleWithEventData[vehicleNo][eventInx][6]),parseFloat(vehicleWithEventData[vehicleNo][eventInx][7]));
   var pointB = new L.LatLng(parseFloat(vehicleWithEventData[vehicleNo][eventInx+1][6]),parseFloat(vehicleWithEventData[vehicleNo][eventInx+1][7]));
   var pointList = [pointA, pointB];
   var line = new L.Polyline(pointList, {
       color: '#4b238a',
       weight: 3,
      opacity: 0.8,
      smoothFactor: 3
   });
  line.addTo(map);

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
            
 
            var pointA = new L.LatLng(templat,templang);
            var pointB = new L.LatLng(parseFloat(vehicleWithEventData[vehicleNo][eventInx+1][6]),parseFloat(vehicleWithEventData[vehicleNo][eventInx+1][7]));
            var pointList = [pointA, pointB];
            templat=parseFloat(vehicleWithEventData[vehicleIndx][eventInx+1][6]);
            templang=parseFloat(vehicleWithEventData[vehicleIndx][eventInx+1][7]);
            var line = new L.Polyline(pointList, {
               color: '#4b238a',
               weight: 3,
               opacity: 0.8,
               smoothFactor: 3
           });
           line.addTo(map);

           
           }//end inner loop
     }//end outer loop
}//end allVehicleRoute


