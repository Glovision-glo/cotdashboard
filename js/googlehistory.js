 google.maps.event.addDomListener(window, 'load', initialise4);
var intervelID=null;
var lineSymbol = {
                  path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW
              };

 function loadMap(lat,lng){

      var mapOptions = {
                  zoom: 12,
                  mapTypeControlOptions: {
                        mapTypeIds: [google.maps.MapTypeId.SATELLITE, google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.HYBRID],
                        position: google.maps.ControlPosition.RIGHT_CENTER
                    },

                  center: new google.maps.LatLng(lat,lng)
               };
      var mapDiv = document.getElementById('mapcontainer');
      map = new google.maps.Map(mapDiv, mapOptions);

 }

function initialiseDrivingServices(){

 var   drawingManager= new google.maps.drawing.DrawingManager({
                   drawingControl: true,
                   drawingControlOptions: {
                      position: google.maps.ControlPosition.TOP_CENTER,
                           drawingModes: [
                               google.maps.drawing.OverlayType.CIRCLE,
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

  return drawingManager;
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
                   title:"Zone Id: "+zone,
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

function atob(lat1,long1,lat2,long2,replayornot) {
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
                directionsDisplay.setMap(map);

              } else {
              }
            });
if(replayornot=="no")
    alert("close");
          }


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

var graphpointonmapmarker=null;
var graphpointonmapmarkerinfo=null;
function graphpointonmap(map,lat,lang,zone,status,r,img){
   var position = new google.maps.LatLng(parseFloat(lat),parseFloat(lang));
   if(graphpointonmapmarker==null){
           graphpointonmapmarker = new google.maps.Marker({
                   position: position,
                   map: map,
                   optimized: false,
                   icon:img,  
                   title:zone,
                   });
               graphpointonmapmarkerinfo = new google.maps.InfoWindow({
                    content: zone
                   });

                  google.maps.event.addListener(graphpointonmapmarker, 'click', function() {
                    graphpointonmapmarkerinfo.open(map,graphpointonmapmarker);
                   });
                    graphpointonmapmarkerinfo.open(map,graphpointonmapmarker);
   }else{
         graphpointonmapmarker.setPosition(position);
         graphpointonmapmarkerinfo.setContent(zone);
   }
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
             lineSymbol={path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW};
                displayidles(map);
                 gonesput1(event,zones);
                var m=0;

               intervelID=setInterval(function(){
                      dummymarker.setMap(null); 
                      if(m>event.length-2){  pushpin(map,elat,elong,"Vehicle Current Location",'yes',1000,"../images/vehicle.png");clearTimeout(intervelID);return;}
                      else{
                         var evntdata=event[m].split('^');
                         var evntdata1=event[m+1].split('^');
                          if(evntdata1[5]>0.7 && (accountID=="gvkgjtest" ||  accountID=="goghealth" ||  accountID=="gvk-ut-gogh")){
                               calcRoute(evntdata[2],evntdata[3],evntdata1[2],evntdata1[3]) ;
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
                                replayPath(evntdata[2],evntdata[3],lineSymbol,map,evntdata1[2],evntdata1[3]);
                                 m++;
                        }
                   }
             },replayrate);//setInterval funcion closed

        }//end function
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
    }
  });
}
function fillGaps(lat1,lng1,lat2,lng2){
   calcRoute(lat1,lng1,lat2,lng2);

}
