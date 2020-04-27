var lineSymbol = {
                 // path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW
              };

var bounds = L.latLngBounds();;
var intervelID=null;
$(document).ready(function(){
// your code
// //alert();
 initialise4();
  });
 function loadMap(lat,lng){
        if(map==null) {
      map = L.map('mapcontainer',{zoomControl: false}).setView([lat, lng], 12);
      L.tileLayer('http://maps3.glovision.co/tile/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'}).addTo(map);
     map.invalidateSize();
  }else{

    map.off();
     map.remove();
    map = L.map('mapcontainer',{zoomControl: false}).setView([lat, lng], 12);
      L.tileLayer('http://maps3.glovision.co/tile/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'}).addTo(map);
     map.invalidateSize();
}
  L.control.zoom({
     position:'topright'
}).addTo(map);
 
 }

function initialiseDrivingServices(){
   return null;
}


function replayPath(lat,long,lineSymbol,map,lat2,long2){

            var pointA = new L.LatLng(parseFloat(lat),parseFloat(long));
   var pointB = new L.LatLng(parseFloat(lat2),parseFloat(long2));
   var pointList = [pointA, pointB];
   var line = new L.Polyline(pointList, {
       color: '#4e5eb0',
       weight: 3,
      opacity: 0.8,
      smoothFactor: 3
   });
  line.addTo(map);

}
var intervelID;
 function replay1(){
            var zones = zone.split('*');
            var event = eventData.split('*');
            var replayrate=parseInt(document.getElementById('replayrate').value); 
            var center=event[0].split('^');
            map.off();
            map.remove();
            map = L.map('mapcontainer',{zoomControl: false}).setView([center[2],center[3]],11);
            L.tileLayer('http://maps3.glovision.co/tile/{z}/{x}/{y}.png', {
           attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'}).addTo(map);
           map.invalidateSize();
             L.control.zoom({
              position:'topright'
          }).addTo(map);
 
            var lineSymbol = {
                  path: 'M 1,1 1,1',
                  strokeOpacity: 1,
                  scale: 1
            };
            displayidles(map);
            var m=0;
            intervelID=setInterval(function(){
                 map.removeLayer(dummymarker);
                if(m>event.length-2){  pushpin(map,elat,elong,"Vehicle Current Location",'yes',1000,"../images/vehicle.png");clearTimeout(intervelID);return;}
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


 function replay(){
               var zones = zone.split('*');
               var event = eventData.split('*');
 
              var replayrate=parseInt(document.getElementById('replayrate').value); 
              var center=event[0].split('^');
              map.off();
              map.remove();
               map = L.map('mapcontainer',{zoomControl: false}).setView([center[2],center[3]],11);
              L.tileLayer('http://maps3.glovision.co/tile/{z}/{x}/{y}.png', {
             attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'}).addTo(map);
               map.invalidateSize();
              L.control.zoom({
              position:'topright'
          }).addTo(map);

 
            var lineSymbol = {
                  path: 'M 1,1 1,1',
                  strokeOpacity: 1,
                  scale: 1
               };
                displayidles(map);
                 gonesput1(event,zones);
                var m=0;
               intervelID=setInterval(function(){
                     map.removeLayer(dummymarker); 
                     if(m>event.length-2){  pushpin(map,elat,elong,"Vehicle Current Location",'yes',1000,"../images/vehicle.png");clearTimeout(id);return;}
                     else{
                         var evntdata=event[m].split('^');
                         var evntdata1=event[m+1].split('^');
                      if(evntdata1[5]>0.7 && (accountID=="gvkgjtest" ||  accountID=="goghealth" ||  accountID=="gvk-ut-gogh")){
                  
                               atob(evntdata[2],evntdata[3],evntdata1[2],evntdata1[3],'yes');
                                
                             }else{
                            if(evntdata[11]=="Ignition Off" || evntdata[11]=="Ignition On" || evntdata[11]=="Main Power OFF" || evntdata[11]=="Main Power ON" || evntdata[11]=="Over Speed" || evntdata[11]=="Time Interval" || evntdata[11]=="Idle Point"){   
                                var displaylabel=evntdata[11];
                                if(evntdata[11]=="Over Speed"){
                                      displaylabel=parseInt(evntdata[6]);
                                }
                                pushpin(map,evntdata[2],evntdata[3],evntdata[12],"no","viewpoint",'../images1/pin30_green.png');
                           }
                               vehicleTrack(evntdata1[2],evntdata1[3],lineSymbol,map,evntdata[6],"yes",evntdata[7],"Address:"+evntdata[10]+"<br>Time:"+evntdata[1]+"<br>Speed:"+evntdata[6]+" Kmph");
                         replayPath(evntdata[2],evntdata[3],lineSymbol,map,evntdata1[2],evntdata1[3]);
                         m++;


                    }

}
                },replayrate);//setInterval funcion closed

        }//end function



function pushpin(map,lat,lang,zone,status,r,img){
                var position =L.latLng(lat,lang);
               var marker =null;
               if(r=="viewpoint"){
                     if(img=="" || img==undefined){
                        marker=L.marker(position).bindPopup(zone);;
                    }else{
                       var icon1 = new L.Icon({
                             iconUrl: img,
                             // iconSize: [16, 16],
                             iconAnchor: [16,28],
                           //  popupAnchor: [0, -51]
                          });
                       // var icon1 =L.icon({iconUrl: img,iconAnchor: [0,0]});
                        marker=L.marker(position,{icon:icon1});
                        marker.bindPopup(zone);
                    }
 
                      marker.bindTooltip(zone, {permanent: true, className: "my-label", offset: [0, 0] });
                     marker.addTo(map);
               }else{
                    if(img=="" || img==undefined){
                        marker=L.marker(position);
                    }else{
                         var icon1 = new L.Icon({
                             iconUrl: img,
                            /* iconSize: [16, 16],*/
                             iconAnchor: [16,28],
                           //  popupAnchor: [0, -51]
                          });

                        marker=L.marker(position,{icon:icon1});
                    }
                    marker.addTo(map).bindPopup(zone);
               }
              if(r!="viewpoint")
                    dummymarker=marker;
              if(status=='no'){
              }else{
                  marker.openPopup();
              }
         //bounds.extend(position);
       // map.fitBounds(bounds);

}

function atob(lat1,long1,lat2,long2,replayornot) {

 }


   function circleCreation(lat,lng,map,color,opacity,radius,geozone){
      if(cityCircle!=null){
             map.removeLayer(cityCircle);
              cityCircle=null;
        }
        radius=parseInt(radius);

        var icon =L.icon({iconUrl: "../images/circle4.png",iconAnchor: [0,0]});

        var position =L.latLng(lat,lng);

        var cityCircle= L.circle([lat,lng], radius,{color:$("#shapeColor").val(),draggable: true});//.addTo(map);
        cityCircle.addTo(map);
       // map.setZoom(17);
        map.panTo(position);

    }

var graphpointonmapmarker=null;
var graphpointonmapmarkerinfo=null;
function graphpointonmap(map,lat,lang,zone,status,r,img){
         var position =L.latLng(parseFloat(lat),parseFloat(lang));
   if(graphpointonmapmarker==null){
              var icon1 = new L.Icon({
                             iconUrl: img,
                             iconAnchor: [16,28],
                          });
                     
                        graphpointonmapmarker=L.marker(position,{icon:icon1});
                        graphpointonmapmarker.addTo(map).bindPopup(zone);
   }else{
 
          graphpointonmapmarker.setLatLng(position).bindPopup(zone).update();  
         //graphpointonmapmarker.setPosition(position).bindPopup(zone).update();
   }
}



function replay3(){
            var zones = zone.split('*');
            var event = eventData.split('*');
            var replayrate=parseInt(document.getElementById('replayrate').value); 
            var center=event[0].split('^');
               map.off();
              map.remove();
               map = L.map('mapcontainer',{zoomControl: false}).setView([center[2],center[3]],11);
              L.tileLayer('http://maps3.glovision.co/tile/{z}/{x}/{y}.png', {
             attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'}).addTo(map);
               map.invalidateSize();
              L.control.zoom({
              position:'topright'
          }).addTo(map);

            var lineSymbol = {
                  path: 'M 1,1 1,1',
                  strokeOpacity: 1,
                  scale: 1
            };
            displayidles(map);
            showlocations(infocase);
            var m=0;
           dummymarker=null;
           intervelID=setInterval(function(){
              if(!isPaused) { 
                if(dummymarker!=null)
                     map.removeLayer(dummymarker);
                if(m>event.length-2){  //pushpin(map,elat,elong,"Vehicle Current Location",'yes',1000,"../images/vehicle.png");
                                    clearTimeout(intervelID);
                               single();
                         return;
                       }
                else{
                    var evntdata=event[m].split('^');
                    var evntdata1=event[m+1].split('^');
                        if(evntdata[11]=="Seen Arrival" || evntdata[11]=="Seen Depart" || evntdata[11]=="Destination Arrival" || evntdata[11]=="Destination Depart" || evntdata[11]=="Base Arrival" || evntdata[11]=="Base Depart" || evntdata[11]=="Back To Base Arrival"){
                        vehicleTrack(evntdata[2],evntdata[3],lineSymbol,map,evntdata[6],"yes",evntdata[7],evntdata[12]+"<br>Location :"+evntdata[10]);
                        elat=evntdata[2];
                        elong=evntdata[3];
                        replayPath(evntdata[2],evntdata[3],lineSymbol,map,evntdata1[2],evntdata1[3]);
                    }

                        vehicleTrack(evntdata[2],evntdata[3],lineSymbol,map,evntdata[6],"no",evntdata[7],evntdata[1]+"<br> Address:"+evntdata[10]+"<br> Speed:"+parseInt(evntdata[6]));
                        replayPath(evntdata[2],evntdata[3],lineSymbol,map,evntdata1[2],evntdata1[3]);
                        m++;
                }
              }
   
            },replayrate);//setInterval funcion closed
        }//end function

function calcRoute(slat,slng,elat,elng) {
     replayPath(slat,slng,elat,elng);
}

function fillGaps(lat1,lng1,lat2,lng2){
   calcRoute(lat1,lng1,lat2,lng2);

}

