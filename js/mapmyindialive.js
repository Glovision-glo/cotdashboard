var marker=null;
$(document).ready(function(){
  initialize();
});

function initialize() {
    var event = eventData.split('*');
    var lastevnt=event[0].split('^');
    elat=lastevnt[2];
    elong=lastevnt[3];
    var vehiclereachedin="Vehicle Stopped";
   if(map==null) {
         map = new MapmyIndia.Map('map_canvas', {center: [21.0000,78.0000], zoomControl: true, hybrid: true});
       // map = L.map('map_canvas').setView([21.0000, 78.0000], 7);
        // L.tileLayer('http://maps3.glovision.co/tile/{z}/{x}/{y}.png', {
       // attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'}).addTo(map);
       map.invalidateSize();
    }else{
 
       map.off();
       map.remove();
      // map = L.map('map_canvas').setView([21.0000, 78.0000], 7);
     //  L.tileLayer('http://maps3.glovision.co/tile/{z}/{x}/{y}.png', {
     //  attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'}).addTo(map);
     map = new MapmyIndia.Map('map_canvas', {center: [21.0000,78.0000], zoomControl: true, hybrid: true});
      map.invalidateSize();
   }
   replay();

}

function removeMarker(dummymarker){
   map.removeLayer(dummymarker);
}
function pushpin(map,lat,lang,zone,status,r,heading,color,lasteventDate){
      map.setView(new L.LatLng(lat,lang), 14);
   //   var img="../images/vehicle.png";
      var img='../images1/readytotakecase-online.png';
      var vehicleID='';
      var lat1=parseFloat(lat).toFixed(4);
      var long1=parseFloat(lang).toFixed(4);
      // var zone="<b> Vehicle: "+vehicleID+"<br> Lat/Long :"+lat1+"/"+long1+" <br> Last Event Time :"+lasteventDate+"<br> "+zone+" Kmph";
      //    zone1=zone;
      var position =L.latLng(lat,lang);
      var marker =null;
      var icon1 =L.icon({iconUrl: img,
                             iconAnchor: [16,32],
                             popupAnchor: [0, -51]});
             marker=L.marker(position,{icon:icon1});
             marker.addTo(map).bindPopup(zone);
             dummymarker=marker;
              if(status=='no'){
              }else{
                 // marker.openPopup();
              }
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
                                if (status == google.maps.DirectionsStatus.OK)
                                {
                                    directionsDisplay.setDirections (response);

                                    var pointsArray = [];
                                    
                                    pointsArray = response.routes[0].overview_path;
                                    for(var x=1;x<pointsArray.length;x++){
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
function calcRoute(slat,slng,elat,elng,vehicleColor,degree,zone) {
    var img='../images1/readytotakecase-online.png';
   document.getElementById("imgicon").src =img ;
   var base64 = getBase64Image(document.getElementById("imgicon"), degree);
   var icon =L.icon({iconUrl: base64,iconAnchor: [16,10]});

   if(dummymarker==null){
       map.setView(new L.LatLng(slat,slng), 14);
      var img='../images1/readytotakecase-online.png';
      var position =L.latLng(slat,slng);
      dummymarker=L.marker(position,{icon:icon});
        dummymarker.addTo(map).bindPopup(zone);
     
   }
        

  

    dummymarker.bindPopup(zone);
    dummymarker.setIcon(icon);
   var waylist=[[slat,slng],[elat,elng]];

   var tiks=10000/waylist.length+7000;
   var xm= L.Marker.movingMarker(waylist,[tiks],'');//.addTo(map);
   xm.start();
/*
   var route_api_url='https://apis.mapmyindia.com/advancedmaps/v1/4nk6fj23vniumia8cieholhk1ak2yzh2/';
   var api_name="route_adv/driving/"
   var route_api_url_with_param = route_api_url+api_name+slng+","+slat+";"+elng+","+elat+"?alternatives=true&rtype=0&geometries=polyline&overview=full&exclude=&steps=true&region=ind";
   var api_url= btoa(route_api_url_with_param);

  
    $.ajax({
                        type: "GET",
                        dataType: 'text',
                        url:"getWayPoints.php",
                        async: false,
                        data: {
                            url: JSON.stringify(api_url),
                        },
                        success: function (result) {
                            var resdata = JSON.parse(result);
                            if(resdata.data.indexOf("503 Service Temporarily")>=1){alert("UAT Server not working");return false;}
                            if (resdata.status=='success'){
                            var jsondata = JSON.parse(resdata.data);
                                route_api_result(jsondata,slat,slng,elat,elng);
                                      
                        }
                          else{
                            var error_response="No Response from API Server. kindly check the keys or request server url";
                            $("#info").html(error_response);
                        }
                        }
                    });



*/
}

function sleep(ms) {
      return new Promise(resolve => setTimeout(resolve, ms));
   }
async function route_api_result(data,x,y,clat,clng)
{

  for (i = 0; i < data.routes.length; i++)
  {
       var geometry=decode(data.routes[i].geometry);
       
       var waylist=[[x,y],[clat,clng]];
         for(var mindx=0;mindx<geometry.length;mindx++){
             var postition= L.latLng(geometry[mindx][0],geometry[mindx][1]);
         
               waylist.push(geometry[mindx]);
          
          }
        waylist.push([clat,clng]);
         var tiks=10000/waylist.length+7000;
        var xm= L.Marker.movingMarker(waylist,[tiks],'','');//.addTo(map);
         xm.start();
     

     }

}

function decode(encoded)
{
    var points = [];
    var index = 0, len = encoded.length;
    var lat = 0, lng = 0;
    while (index < len) {
        var b, shift = 0, result = 0;
        do {

            b = encoded.charAt(index++).charCodeAt(0) - 63;
            result |= (b & 0x1f) << shift;
            shift += 5;
        } while (b >= 0x20);


        var dlat = ((result & 1) != 0 ? ~(result >> 1) : (result >> 1));
        lat += dlat;
        shift = 0;
        result = 0;
        do {
            b = encoded.charAt(index++).charCodeAt(0) - 63;
            result |= (b & 0x1f) << shift;
            shift += 5;
        } while (b >= 0x20);
        var dlng = ((result & 1) != 0 ? ~(result >> 1) : (result >> 1));
        lng += dlng;

        points.push([lat / 1E5, lng / 1E5])


    }
    return points
}

function getBase64Image(image, angle) {
    var offscreenCanvas = document.createElement('canvas');
    var offscreenCtx = offscreenCanvas.getContext('2d');

    var size = Math.max(image.width, image.height);
    offscreenCanvas.width = size;
    offscreenCanvas.height = size;

    offscreenCtx.translate(size / 2, size / 2);
    offscreenCtx.rotate(angle * Math.PI / 180);
    offscreenCtx.drawImage(image, -(image.width /2), -(image.height / 2));

    var dataURL = offscreenCanvas.toDataURL("image/png");
    return dataURL;
}

/*
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
     var infowindow = new google.maps.InfoWindow({
                    content: zone1
                   });
                  google.maps.event.addListener(marker, 'click', function() {
                    infowindow.open(map,marker);
                   });

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
  */              
