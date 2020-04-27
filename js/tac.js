
var refreshsingleormultipleGroups="single";
var multiplegroups="";
var groups=[];
function vehicle()
{ 
	
    var Account=window.localStorage.getItem('accountID');
    var userID= window.localStorage.getItem('userId');
	//var VehicleID= document.getElementById('vehicleID').value;
	var globalVehicles=[];//vahiles for vehiclesStatus
	var globalVehicles1=[];
//	var globalVehicles2=[];
    var groupdivision=[];
//    var groups=[];
    var v = new Date();
    v.setHours(0);
v.setMinutes(0);
v.setSeconds(0);
    document.getElementById('fromDate').value=dateToStringFormat(v);
   
   var v1=new Date(); 
   document.getElementById('toDate').value=dateToStringFormat(v1);
   $.ajax({
	   type: 'get',
	   url: 'php/fuelVehicles.php',
	   data: {'accountID':Account,'userID':userID},
	   dataType: 'json',
	   success: function(data)
	   {
		   $.each(data,function(key,value)
		   {
			//   alert(globalVehicles);
		     /*  var select=document.getElementById('vehicleID');
		       var selectlength=document.getElementById("vehicleID").length;
               if(selectlength>=0){
            	   for(var i=selectlength-1;i>0;i--)
	               {
	                   select.remove(i);
	               }
               }*/
                 //alert(key);
                 if(key=="groupsdivisions"){
                     for(var i=0;i<value.length;i++)
                             groupdivision[i]=value[i];                   
 
                      window.localStorage.setItem('groupdevices',groupdivision);

                 }
                 if(key=='divisions'){
                   //  alert("ramana"+value.length);
                   //
                     var select=document.getElementById('divisions');
                          var selectlength=document.getElementById("divisions").length;
                          if(selectlength>=0){
                             for(var i=selectlength-1;i>0;i--)
                             {
                               select.remove(i);
                             }
                         }
 
                     for(var i=0;i<value.length;i++)
                        {
                            var option=document.createElement("option");
                           option.value= option.text=value[i];
                            select.appendChild(option);
                                      // alert(value[i]);
                        }
                 }
                  if(key=='groups'){
                        //  alert(value);
                          var select=document.getElementById('groupdevice');
     
                          var group=document.getElementById('selectedgroup');
                          var selectlength=document.getElementById("groupdevice").length;
                          if(selectlength>=0){
                             for(var i=selectlength-1;i>0;i--)
                             {
                               select.remove(i);
                               group.remove(i);
                             }
                         }
                       groups=[];
                       for(var i=0;i<value.length;i++)
                        {
                             var option=document.createElement("option");
                             var option1=document.createElement("option");
                            option.value= option.text=value[i][0];
                            option1.value= option1.text=value[i][0];
                            select.appendChild(option);
                           group.appendChild(option1);
                            groups[i]=value[i][0]+"*"+value[i][1]+"*"+value[i][2]+"*"+value[i][3];
                        }
 

                   }               

                  if(key=="markers"){
                          var select=document.getElementById('ss');
                          var vehicles=document.getElementById('vehicles');
                          var selectlength=document.getElementById("ss").length;
                          if(selectlength>=0){
                             for(var i=selectlength-1;i>0;i--)
                             {
                               select.remove(i);
                               vehicles.remove(i);
                             }
                         }

                    for(var i=0;i<value.length;i++)
                    {
            	          var option=document.createElement("option");
                        //option.value=value[i][0];
            		//option.textContent=value[i][0]+'   < '+value[i][1]+'   >';
            		globalVehicles[i]=value[i][0];
            		globalVehicles1[i]=value[i][1]+'['+value[i][2];
                       globalVehicles2[i]=value[i][3]+"*"+value[i][0];          		
                    //alert(value[i][3]);
            		 option.value= option.text=value[i][0];
            		select.appendChild(option);
                        vehicles.appendChild(option);
                     }
                        //alert(value.length+" first");
                      window.localStorage.setItem('vehicle',value[0][0]);
               
                       window.localStorage.setItem('globalVehicles',globalVehicles);
                        window.localStorage.setItem('globalVehicles1',globalVehicles1);
                      // window.localStorage.setItem('globalVehicles2',globalVehicles2);
                     }
		   });
	   }

   });
}

function loadvehicles(baselocation){

//alert(baselocation);

     var select=document.getElementById('ss');
               var selectlength=document.getElementById("ss").length;
     if(selectlength>=0){
       for(var i=selectlength;i>=0;i--)
        {
           select.remove(i);
        }
     }

     for(var vehicleIndx=0;vehicleIndx<vehicleAvailable.length;vehicleIndx++){
           var optresult = vehicleAvailable[vehicleIndx][6].split("/");
        //   alert(optresult[1]);
              var bl=baselocation.toUpperCase();
              var allbl=optresult[1].toUpperCase();
      //       alert(bl.indexOf(optresult[1])+"   "+bl+"   "+optresult[1]);
               if(allbl.indexOf(bl)>-1 || baselocation=="selectall"){
                          //alert(optresult[1]);
                          var option=document.createElement("option");
                            option.value= option.text=vehicleAvailable[vehicleIndx][0];
                            select.appendChild(option);
                           
                }
    

     }
     $("#ss option").each(function(){
  $(this).siblings("[value='"+ this.value+"']").remove();
}); 

}

function refreshstatus(){
    refreshsingleormultipleGroups="multiple";
  /*  var select=document.getElementById('groupdevice');
    var selectlength=document.getElementById("groupdevice").length;
     for(var i=selectlength-1;i>0;i--){
            alert(select.item(i).value);
     }
   */
}

function groupsload(){
         var value1=window.localStorage.getItem('groupdevices');
       var value=value1.split(','); 
       var selecteddivision=document.getElementById('divisionstext').value;
// alert(value=='');
    if(value!=''){
           // alert();
          var select=document.getElementById('groupdevice');
                          var selectlength=document.getElementById("groupdevice").length;
                          if(selectlength>=0){
                             for(var i=selectlength-1;i>0;i--)
                             {
                               select.remove(i);
                             }
                         }

                       for(var i=0;i<value.length;i++)
                        {     // alert(value[i].indexOf(selecteddivision));
                              var gp=value[i].split('-')[0].toLowerCase();
                              var gp1=selecteddivision.toLowerCase();
                               //  alert(gp+"    "+gp1); 
                              if( gp==gp1|| selecteddivision=="selectall"){
                             var option=document.createElement("option");
                            option.value= option.text=multiReplace(value[i].split('-')[1],' ','');
                            select.appendChild(option);
                            }

                        }
 }
   //to remove duplicate options
   $("#groupdevice option").each(function(){
  $(this).siblings("[value='"+ this.value+"']").remove();
});
}
 
function multiReplace(str, match, repl) {
    if (match === repl)
        return str;
    do {
        str = str.replace(match, repl);
    } while(str.indexOf(match) !== -1);
    return str.toLowerCase();
}


function validuser()
{
    //window.localStorage.setItem('accountID',"kkkk");    
    var Account=window.localStorage.getItem('accountID');
   
    if (Account == '' || Account == undefined) {
         alert('Invalid User Login Again');
         window.location="login.php?errormsg=2";
     }
}
function logout()
{
    var Account=window.localStorage.getItem('accountID');
//alert(Account);
 //   window.localStorage.setItem('accountID',"");    
       alert('Logged Out. Thanks for logging in');
       window.localStorage.setItem('accountID',"");
    window.location="../vehicleStatus.html";
   

}
function back(){
       window.location='logout.php';
      /*
	 var Account=window.localStorage.getItem('accountID');
   if(Account!="goghealth" && Account!="gvk-ut-gogh" && Account!="khilkhilat"){ 
	var uri = window.location.toString();
	var url='';
    if (uri.indexOf("?") > 0) {
	   // url = uri.substring(0,uri.indexOf("?"));
	   url=window.location.origin;
	   var port='';
       if(window.location.port!=''){port=":"+window.location.port;}
	   url=url+port+":8080/track/Track";
	} 
	
	    window.location=url;
    }else{
         window.location='http://goghealth.glovision.co:8080/track/Track';
    }*/

}
function sessionTimeOut()
{
    window.localStorage.setItem('accountID',"");
    alert('Session Timed Out plz login Again');
    window.location="../../../index.html";
} 
// Copyright 2013-2014 Glovision Techno Services Pvt Ltd
// Specific Form based methods
/*
var value = window.localStorage.getItem('packagingidentify');
// new code for track request
var userid='';
var Account=window.localStorage.getItem('accountID');
var uri = window.location.toString();
  if(uri.indexOf("accountID=")>0) {
	 // var uri = window.location.toString();
	  var acc;
    	if (uri.indexOf("?") > 0) {
    		var substring=uri.substring(uri.indexOf("?"),uri.length);
    		   var keyvalue=substring.split('&');
    		         acc=keyvalue[0].split('=')[1];
    		         userid=keyvalue[1].split('=')[1];
    		         
    	    //acc = uri.substring(uri.indexOf("accountID=")+10,uri.length);
    	    
    	} 
	  value=acc+",1,1,1,1,1,1,1,1,1,1,1,1,1,1443092021,1413543222,1,1,1,1,1,1,1,1,silver";
	  window.localStorage.setItem('accountID',acc);
	  window.localStorage.setItem('userId',userid);
	  if(acc.indexOf("gvk") == -1 || acc.indexOf("gog") == -1 || acc.indexOf("khil") == -1){
	     window.localStorage.setItem('showmap','all');
	  }
	  window.localStorage.setItem('packagingidentify',value);
	  window.localStorage.setItem('vehicleType',"ambulance");
	
  }
  
value=value.split(",");

*/
function dateToEpochDB(indate)
{
    var tempdate=new Date();
    tempdate=parseInt(Math.round(indate.getTime()/1000.0));
 //   alert("D2EB: " + tempdate);
    return tempdate;
}
function datestringToEpochDB(ds)
{
    if (ds) {
   //     alert("DS2EB: " + ds);
        var tempdate = stringFormatToDate(ds);
        return dateToEpochDB(tempdate);
    }
}

function dateToStringFormat(indate)
{
   // alert(indate);
    var month = new Array();
    month[0] = "Jan";
    month[1] = "Feb";
    month[2] = "Mar";
    month[3] = "Apr";
    month[4] = "May";
    month[5] = "Jun";
    month[6] = "Jul";
    month[7] = "Aug";
    month[8] = "Sep";
    month[9] = "Oct";
    month[10] = "Nov";
    month[11] = "Dec";
    // Wed May 07 2014 20:01:48 GMT+0530 (IST)
    var d = indate.getDate();
    if (d < 10) {
        d = "0" + d;
    }
    var M = month[indate.getMonth()]; 
    var Y = indate.getFullYear();
    var H = indate.getHours();
    var i = indate.getMinutes();
    var s = indate.getSeconds();
    
    // 07-May-2014 20:01:48
    return (d + "-" + M + "-" + Y + " "+ H + ":" + i + ":" + s);
}

function stringFormatToDate(ds)
{
    // 07-May-2014 20:01:48
    //alert(ds);
    var dsArray=ds.split(/[\s-:]+/);
    
    var year = dsArray[2];
    var day = dsArray[0];
    var month;
    if (dsArray[1]=="Jan") month = 0;
    else if (dsArray[1]=="Feb") month = 1;
    else if (dsArray[1]=="Mar") month = 2;
    else if (dsArray[1]=="Apr") month = 3;
    else if (dsArray[1]=="May") month = 4;
    else if (dsArray[1]=="Jun") month = 5;
    else if (dsArray[1]=="Jul") month = 6;
    else if (dsArray[1]=="Aug") month = 7;
    else if (dsArray[1]=="Sep") month = 8;
    else if (dsArray[1]=="Oct") month = 9;
    else if (dsArray[1]=="Nov") month = 10;
    else if (dsArray[1]=="Dec") month = 11;
    
    var hour = dsArray[3];
    var minute = dsArray[4];
    var second = dsArray[5];
    
    var d = new Date (year, month, day, hour, minute, second );
    return d;
}
function EpochDBToDate(epoch){
 //   alert("EDB2D: "+epoch);
    var date = new Date(parseInt(Math.round(epoch*1000)));
  //  alert("EDB2D: "+date);
    // date.setHours(date.getHours()+5);
    // date.setMinutes(date.getMinutes()+30);
    return date;
}
function EpochDBToDateString(epoch){
  // alert("EDB2DS: "+epoch);
    var date = EpochDBToDate(epoch);
    return dateToStringFormat(date);
}
function fuelDBConverts(from){
var date=stringFormatToDate(from);
	
	var H = date.getHours();
    var i = date.getMinutes();
    var s = date.getSeconds();
	var d = date.getDate();
    if (d < 10) {
        d = "0" + d;
    }
    var M = date.getMonth(); 
    //alert(M);
    if (M < 9) {
        M = "0" + ++M;
    }
    else {
     //alert("else");
     M = ++M;
     //alert(M);
     
    }
    var Y = date.getFullYear();
   return Y+"-"+M+"-"+d+"  "+H+":"+i+":"+s ;
	
	
}

function stringFormatToDateForFuel(ds)
{
    // 07-May-2014 20:01:48
    //alert(ds);
    var dsArray=ds.split(/[\s-:]+/);
    var year = dsArray[0];
    var day = dsArray[2];
    var month;
    if (dsArray[1]=="01") month = "Jan";
    else if (dsArray[1]=="02") month = "Feb";
    else if (dsArray[1]=="03") month = "Mar";
    else if (dsArray[1]=="04") month = "Apr";
    else if (dsArray[1]=="05") month = "May";
    else if (dsArray[1]=="06") month = "Jun";
    else if (dsArray[1]=="07") month = "Jul";
    else if (dsArray[1]=="08") month = "Aug";
    else if (dsArray[1]=="09") month ="Sep";
    else if (dsArray[1]=="10") month = "Oct";
    else if (dsArray[1]=="11") month = "Nov";
    else if (dsArray[1]=="12") month = "Dec";
    
    var hour = dsArray[3];
    var minute = dsArray[4];
    var second = dsArray[5];
    return day+"-"+month+"-"+year+"  "+ hour+":"+minute+":"+ second ;
}
function addOneDayToFromDate(){
	var date = new Date();
	date.setDate(date.getDate() - 1);
    document.getElementById("fromDate").value=dateToStringFormat(date);
    var date1 = new Date();
    document.getElementById("toDate").value=dateToStringFormat(date1);
	
}// Copyright 2013-2014 Glovision Techno Services Pvt Ltd



	
	
/////////////////vehicle Status.js///////////////
	var vehicleWithEventData=[];
	var vehicleAvailable=[];
	var globalVehicles=[];
	var globalVehicles1=[];
          var globalVehicles2=[];

     var  contactambulance=[];

	 
	function twoVehiclePath(){
		var checkbox = document.getElementsByName('multipleVehicles');
		var selectVehicle=[];
		 var VehicleIndx=0;
	    for (var i = 0; i < checkbox.length; i++) {
	         if (checkbox[i].checked) {
		        selectVehicle[VehicleIndx]=checkbox[i].value;
		         VehicleIndx++;
		     }
		 }
	    if(selectVehicle.length==2){
	    	vehicleRoute(selectVehicle,3);
	     }else if(selectVehicle.length==1 || selectVehicle.length==0){
	    	 alert(" Select Two Vehicles"); 
	     }else{
	    	alert("You select More Then Two Vehicles");
	    }
	}

	/////////////////status///////////////////////////////////////////
	//var marker=new Array();
	

	function secondsToTimeFormate(TotalAcOnTime){
		var hours=0;
		var min=0;
		var sec=0;
		if(TotalAcOnTime>=3600){
			hours=parseInt(TotalAcOnTime/(60*60));
			var tempmin=TotalAcOnTime%(60*60);
			var result=secondsToTimeFormate(tempmin);
			return hours+"Hr :"+result;
		}else if(TotalAcOnTime>=60){
			 min=parseInt(TotalAcOnTime/60);
			 var tempsec=TotalAcOnTime%60;
			 
			 return min+" Min :"+secondsToTimeFormate(tempsec);
		}else{
		     return TotalAcOnTime+" sec";	
		}
		
	}
	
	function UrlExit(url) {
	/*	var http = new XMLHttpRequest();
	    http.open('HEAD', url, false);
	    http.send();
	    return http.status!=404;*/
    return true;
	}
var singlevehiclerefresh=0;
var maploadcount=0;
var groupmarkers=[];
var markers1 = [];	
var markerClusterer;
var overspeed=0;
	function allVehicleStatus() {
 //map boundaries
if(maploadcount==0 && (window.localStorage.getItem('accountID')=="g1vkmhu" || window.localStorage.getItem('accountID')=="gvk-up-102" || window.localStorage.getItem('accountID')=="als")){
            showKml();
}
//if(maploadcount==0){
  //   textview();

// }
		if(nochangeofflineonlineidle=='no'){
		 window.localStorage.setItem('showmap',"all");
		}

		if(vehicleWithEventData.length>0){
	    var markerinfo=[];
	    var zoom1=5;
	    var myLatlng1;
	    var mapInitializeOnce=0;
       //       removeMarkers();	    
	    var vehicleAvailableIndex=0;
	       //  markers = [];
	    var selectedGroup= multiReplace(document.getElementById("groupdevice").value,' ','');
	    var showvehicles=window.localStorage.getItem('showmap');
          removeMarkers1();
         if(selectedGroup!="selectall"){
               showorhidemarkers="show";
         }         


         maploadcount++;
         // alert(showorhidemarkers);
           //alert(singlevehecleRefresh);
    if (singlevehecleRefresh<2 || showvehicles!="single"){
      //  if(showorhidemarkers=="show")
        //  reloadmap();
       }
     //     alert(groups.length);
	    for(var i=0;i<vehicleWithEventData.length;i++){
	    	
	    	var lastEventOfVehicle=vehicleWithEventData[i].length-1;
	   	var mapOptions ;
	    	
     	    	if(vehicleWithEventData[i].length>0){

    if(parseFloat(vehicleWithEventData[i][lastEventOfVehicle][6])==0){
                 //   alert(parseFloat(vehicleWithEventData[i][lastEventOfVehicle][6]));
                      vehicleWithEventData[i][lastEventOfVehicle][2]="0000"; 
                  }
	    		var lastEvntDate=stringFormatToDate(stringFormatToDateForFuel(vehicleWithEventData[i][lastEventOfVehicle][4]+""));
	    		var epoc1=dateToEpochDB(lastEvntDate);
	    		var currentTime = new Date();
	    	    var epoc2=dateToEpochDB(currentTime);
	   /*  if(maploadcount==0){
                   map.setCenter(new google.maps.LatLng(parseFloat(vehicleWithEventData[i][0][6]),parseFloat(vehicleWithEventData[i][0][7])));                    map.setZoom(6);

               }*/	  
              //maploadcount++;
  
	             if(showvehicles!="single"){
    	          //    map.setCenter(new google.maps.LatLng(parseFloat(vehicleWithEventData[i][0][6]),parseFloat(vehicleWithEventData[i][0][7])));   
	    	/* 
	        	 if(mapInitializeOnce==0){
	        		myLatlng1 = new google.maps.LatLng(parseFloat(vehicleWithEventData[i][0][6]),parseFloat(vehicleWithEventData[i][0][7]));
	        		 //myLatlng1 = new google.maps.LatLng(21.0000, 78.0000);
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
                    */
	        }	 



	        	 var vehicleStatusColor="#F78181";
	    	     if(vehicleWithEventData[i][lastEventOfVehicle][2]=='61714'){
	    		     vehicleStatusColor="#81F781";
	    	     }
	    	     var statusColor='blue';
	  	        // var image='http://track.glovision.co:8080/statictrack/images/custom/'+vehicleWithEventData[i][lastEventOfVehicle][9]+'.png';
	    	     
	    	     //alert(vehicleWithEventData[i][lastEventOfVehicle][11]);
	    	   var  position;
	    	   if(showvehicles!="single"){
 
                  //   position = new google.maps.LatLng(parseFloat(vehicleWithEventData[i][lastEventOfVehicle][6]),parseFloat(vehicleWithEventData[i][lastEventOfVehicle][7]));
                    }
	  	         var title1='Ignition Off';
                         var pincolor='';
	  	         var showstatus='';
	  	   	     if(vehicleWithEventData[i][lastEventOfVehicle][2]=="61714"){
	     		     title1="Running";
	  		         statusColor="green";
	  		       showstatus='online';
                                pincolor='green';
			     }else if(vehicleWithEventData[i][lastEventOfVehicle][2]=="61718" || vehicleWithEventData[i][lastEventOfVehicle][2]=="61720"){
				     title1="Idle";
	   		         statusColor="yellow";
	   		      showstatus='idle';
                                 pincolor='orange';
			    }else if(vehicleWithEventData[i][lastEventOfVehicle][2]=="61717" || vehicleWithEventData[i][lastEventOfVehicle][2]=="61477"){
				     title1="Idle";
	           	     statusColor="red";
	           	     showstatus='idle';
                                  pincolor='orange';
			    }else if(vehicleWithEventData[i][lastEventOfVehicle][2]=="0000"){
			    	title1="offline";
			    	showstatus='offline';
                                pincolor='black';
			    }else{
			    	title1="Idle";
	           	     statusColor="red";
	           	  showstatus='idle';
                           pincolor='orange';
			   }
	  	   	    // if(vehicleWithEventData[i][0][9]=='tn23ah0648'){alert(title1+vehicleWithEventData[i][lastEventOfVehicle][11]+vehicleWithEventData[i][lastEventOfVehicle][2]);}
	  	   	    
	  	  
                      // casemanager info
                       var caseID='';
                var callerName='';
                var caseType='';
                var caseTime='';
                var contactNo='';
                var caseStatus='';
                 for(var cvindx=0;cvindx<contactambulance.length;cvindx++){
                      if(vehicleWithEventData[i][lastEventOfVehicle][9].toUpperCase()==contactambulance[cvindx][0].toUpperCase()){
                             caseID=contactambulance[cvindx][4];
                             callerName=contactambulance[cvindx][3];
                             caseType=contactambulance[cvindx][5];
                             caseTime=contactambulance[cvindx][6];
                             contactNo=contactambulance[cvindx][1];
                             caseStatus=contactambulance[cvindx][2];
                            if(contactambulance[cvindx][2]=="assigned"){
                                  pincolor="31f134";
                                  pincolor="green";
                             }else if(contactambulance[cvindx][2]=="inzone"){
                                    pincolor="f5e43e";
                                   pincolor="yellow";
                             }else{

                                   pincolor="f44a0f";
                                   pincolor="orange";
                              }


                               break;
                       }
                        pincolor="f44a0f";
                        pincolor="orange";

                  }

              var caseinfo="<br>Case ID:"+caseID+"<br>Caller Name:"+callerName+"<br>Case Type:"+caseType+"<br>Case Time:"+caseTime+"<br>Caller No:"+contactNo;
              if(title1!="offline" && title1!="stopped" && caseID!=''){
                caseinfo=caseinfo+"<input type='button' value='Live Track' class='button-secondary pure-button' onClick='liveTracking(\""+vehicleWithEventData[i][lastEventOfVehicle][9]+"\")'/>"
            }
            var showstatusimage='';

            var carcolor='red';

                if(showstatus=="online")carcolor="green";
               else if(showstatus=="idle")carcolor="#CCCC00";



             if(showstatus=="online" && caseStatus=='assigned'){
                    showstatusimage="towardsscene";
              }else if(showstatus=="online" && caseStatus=='inzone'){
                    showstatusimage="atscene";
              }else if(showstatus=="online" && caseStatus=='tohospital'){
                    showstatusimage="towardshospital";
              }else if(showstatus=="online" && caseStatus==''){
                    showstatusimage="readytotakecase-online";
              }else if(showstatus=="idle" && caseStatus=='assigned'){
                    showstatusimage="towardsscene";
              }else if(showstatus=="idle" && caseStatus=='inzone'){
                    showstatusimage="atscene";
              }else if(showstatus=="idle" && caseStatus=='tohospital'){
                    showstatusimage="towardshospital";
              }else if(showstatus=="idle" && caseStatus==''){
                    showstatusimage="readytotakecase-idle";
              }else if(showstatus=="offline" && caseStatus=='assigned'){
                    showstatusimage="offline-caseassigned";
              }else if(showstatus=="offline" && caseStatus=='inzone'){
                    showstatusimage="offline-caseassigned";
              }else if(showstatus=="offline" && caseStatus=='tohospital'){
                    showstatusimage="offline-caseassigned";
              }else if(showstatus=="offline" && caseStatus==''){
                    showstatusimage="offline-casenotassigned";
              }






           	    


 
	  	   	//if(parseInt((epoc2-epoc1)/60)>12){title1="offline";}
	  	   	  //var vehiclesType=window.localStorage.getItem('vehicleType');
	  	   	var uri = window.location.toString();
	  		var url='';
	  	    if (uri.indexOf("?") > 0) {
	  	    	 var port='';
		           if(window.location.port!=''){port=":"+window.location.port;}
		  	      url = window.location.origin+port;
		          // url='http://track.glovision.co:8080';
	  		    	    
	  		} 
	  	   	 var vehiclesType=vehicleWithEventData[i][lastEventOfVehicle][13];
	  	   //vehiclesType="ambulance";
	  	 // alert(vehicleWithEventData[i][lastEventOfVehicle][11]);
	  	   	if(vehiclesType==''){vehiclesType='ambulance'; }
	  	   	var image='images/'+vehiclesType+'/'+title1+vehicleWithEventData[i][lastEventOfVehicle][11]+'.png';
	  	  if(!UrlExit(image)){
	  	//	alert(image+" 1");
	  	    	image='images/vehicle.png'
	  		   image= url+'/statictrack/images/custom/'+vehicleWithEventData[i][lastEventOfVehicle][9]+'.png';
	  	    	if(!UrlExit(image)){image='images/vehicle.png';}



	  	    	
	  	    }

              // image="images1/pin30_"+pincolor+"_"+vehicleWithEventData[i][lastEventOfVehicle][11]+".png";
        image="images1/"+showstatusimage+".png";


           // image=imageRotate(car,carcolor,parseInt(vehicleWithEventData[i][lastEventOfVehicle][11]));


          /*  image = {
    url: "images1/"+showstatus+".png", // url
    scaledSize: new google.maps.Size(32, 32), // scaled size
    origin: new google.maps.Point(0,0), // origin
    anchor: new google.maps.Point(0, 0) // anchor
};*/

             /*
               var arrow='%e2%86%92';//east          
               if(vehicleWithEventData[i][lastEventOfVehicle][11]=="NE"){
                   arrow="%e2%86%97";
                   arrow="NE";
               }else if(vehicleWithEventData[i][lastEventOfVehicle][11]=="N"){
                    arrow="%e2%86%91";
               }else if(vehicleWithEventData[i][lastEventOfVehicle][11]=="NW"){
                    arrow="%e2%86%96";
                    arrow="NW"
               }else if(vehicleWithEventData[i][lastEventOfVehicle][11]=="W"){
                    arrow="%e2%86%90";
               }else if(vehicleWithEventData[i][lastEventOfVehicle][11]=="SW"){
                    arrow="%e2%86%99";
                     arrow="SW";
               }else if(vehicleWithEventData[i][lastEventOfVehicle][11]=="S"){
                     arrow="%e2%86%93";
               }else if(vehicleWithEventData[i][lastEventOfVehicle][11]=="SE"){
                     arrow="%e2%86%98";
                     arrow="SE";
               }
*/



               


         /*         var pinShadow = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_shadow",
        new google.maps.Size(40, 37),
        new google.maps.Point(0, 0),
        new google.maps.Point(12, 35));

                 var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld="+arrow+"|" +pincolor,new google.maps.Size(30, 40),
        new google.maps.Point(0,0),
        new google.maps.Point(24, 24));
 */
              //  image=pinImage;//icon  
	   	   //alert(image);
	      var result = vehicleWithEventData[i][lastEventOfVehicle][12].split("/");

 var discription="Base Location :<b>"+(typeof(result[1])== 'undefined'?' ':result[1]).replace(']','')+"</b><br>Dist :<b>"+(typeof(result[0])== 'undefined'?'':result[0]).replace(']','')+"</b><br> Phone:<b>"+vehicleWithEventData[i][lastEventOfVehicle][14]+"</b><br>Status :<b>"+title1+"</b>";
 var vehicleTitle="<h4> vehicle Id:  "+(vehicleWithEventData[i][lastEventOfVehicle][9].toUpperCase())+"<br>DateTime :<b>"+vehicleWithEventData[i][lastEventOfVehicle][4]+"</b><br> Lat/Long :<b>"+vehicleWithEventData[i][lastEventOfVehicle][6]+"/"+vehicleWithEventData[i][lastEventOfVehicle][7]+"</b><br>Speed :<b>"+vehicleWithEventData[i][lastEventOfVehicle][3]+" Kmph</b>";
//var subtitle="<br>  Odometer Reading: <b>"+vehicleWithEventData[i][lastEventOfVehicle][5]+" Km</b><br>"+discription+"</h4>";
var subtitle="<br>"+discription+"</h4>";
              vehicleTitle=vehicleTitle+ "<br>  Address : <b>"+vehicleWithEventData[i][lastEventOfVehicle][8]+"</b>"+subtitle+"<input type='button' value='History' class='button-secondary pure-button' onClick='routeTracking(\""+vehicleWithEventData[i][lastEventOfVehicle][9]+"\")' data-toggle='modal' data-target='#myModal'/><br>";//----------------"+caseinfo+"</b><br>";
   //             putPushpinVehicles(parseFloat(vehicleWithEventData[i][lastEventOfVehicle][6]),parseFloat(vehicleWithEventData[i][lastEventOfVehicle][7]),map,image,vehicleTitle,vehicleWithEventData[i][lastEventOfVehicle][9],"no");
	  	//   var marker;
                  var result = vehicleWithEventData[i][lastEventOfVehicle][12].split("/");
                 var vehiclegroup=multiReplace(result[0].replace(']',''),' ','');
          //   alert(selectedGroup);
	  if(showvehicles!="single" && (showvehicles==showstatus || showvehicles=='all' || showvehicles==showstatusimage)&& (selectedGroup.toUpperCase()==vehiclegroup.toUpperCase() || selectedGroup=="selectall")){ 
            //if(selectedGroup!="selectall")
              // alert(selectedGroup);
////alert();
          /*  if(showorhidemarkers=="show"){
             map.setCenter(new google.maps.LatLng(parseFloat(vehicleWithEventData[i][lastEventOfVehicle][6]),parseFloat(vehicleWithEventData[i][lastEventOfVehicle][7])));  
                  map.setZoom(10);
               
             } */      


             if(showorhidemarkers=="show"){
                 if(vehicleWithEventData[i][lastEventOfVehicle][3]>=overspeed){
                     putPushpinVehicles(parseFloat(vehicleWithEventData[i][lastEventOfVehicle][6]),parseFloat(vehicleWithEventData[i][lastEventOfVehicle][7]),map,image,vehicleTitle,vehicleWithEventData[i][lastEventOfVehicle][9],"no",vehicleWithEventData[i][lastEventOfVehicle][11]);
                 }

              }
	      /*  marker= new google.maps.Marker({
	                position: position,
	                map: map,
	                icon: image,
	                animation: google.maps.Animation.DROP,
	                title:"vehicle Id: "+vehicleWithEventData[i][0][9]+"  Speed : "+vehicleWithEventData[i][0][3]+"  Odometer Reading: "+vehicleWithEventData[i][0][5]+"Status: "+title1,
	                visible: true
	            });
	         
	        	
	            markers.push(marker);*/
	   }
	         var boxText = document.createElement("div");
	   		boxText.style.cssText = "border: 1px solid black; margin-top: 8px; background: "+statusColor+"; padding: 1px;";
	   		boxText.innerHTML = vehicleWithEventData[i][lastEventOfVehicle][9]+" :"+title1;
	   		//boxText.innerHTML='ra';
	   		var  myOptions = {
	   			 content: "vehicle ID:"+vehicleWithEventData[i][lastEventOfVehicle][9]
	   			
	   		};
	           
	   		

	           
	   		var Account=window.localStorage.getItem('accountID');
           //  if(showvehicles!="single")
	/*  	 var iw = new google.maps.InfoWindow(myOptions);
	        google.maps.event.addListener(marker, "click", function (e) {iw.open(map,this); });
	        var lablediplay=$( "#lableDiplay" ).val();
	        if(lablediplay == "enable"){
                    if(showvehicles!="single") 
                       iw.open(map, marker);
	         }
	  */     
		     //  google.maps.event.addListener(marker[i], "click", function () { iw.open(map, marker[i]); });
	     
	       

	        /*google.maps.event.addListener(marker, 'mouseover', function() {
	            if (!this.infowindow) {
	                this.infowindow = new google.maps.InfoWindow({content: 'abc'});
	            }
	            this.infowindow.open(map, this);
	        });
	        google.maps.event.addListener(marker, 'mouseout', function() {
	            this.infowindow.close();
	        });*/
	             // iw.open(map, marker);
	           //  if(parseInt((epoc2-epoc1)/(60*60))<24){
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
	             /*} else{
	            	 //alert(secondsToTimeFormate(epoc2-epoc1));
	            	  
	     	    	    vehicleAvailable[vehicleAvailableIndex]=[];
	     	            vehicleAvailable[vehicleAvailableIndex][0]=vehicleWithEventData[i][0][9];
	     	            vehicleAvailable[vehicleAvailableIndex][1]="0000";
	     	            vehicleAvailable[vehicleAvailableIndex][2]=vehicleWithEventData[i][lastEventOfVehicle][5];
	     	            vehicleAvailable[vehicleAvailableIndex][3]=vehicleWithEventData[i][lastEventOfVehicle][8];
	     	            vehicleAvailable[vehicleAvailableIndex][4]="Disconnect in" +secondsToTimeFormate(epoc2-epoc1);
	     	           vehicleAvailable[vehicleAvailableIndex][5]=vehicleWithEventData[i][lastEventOfVehicle][3];
	     	          vehicleAvailable[vehicleAvailableIndex][6]=vehicleWithEventData[i][lastEventOfVehicle][12];
	     	         vehicleAvailable[vehicleAvailableIndex][7]=vehicleWithEventData[i][lastEventOfVehicle][13];
	     	        vehicleAvailable[vehicleAvailableIndex][8]=vehicleWithEventData[i][lastEventOfVehicle][11];
	     	       vehicleAvailable[vehicleAvailableIndex][9]=vehicleWithEventData[i][lastEventOfVehicle][14];
	     	    	}
	             
	          */
	            /* var populationOptions = {
	       	         strokeColor: vehicleStatusColor,
	       	         strokeOpacity: 0.8,
	       	         strokeWeight: 2,
	       	         fillColor: vehicleStatusColor,
	       	         fillOpacity: 0.35,
	       	         map: map,
	       	         center:new google.maps.LatLng(parseFloat(vehicleWithEventData[i][lastEventOfVehicle][6]),parseFloat(vehicleWithEventData[i][lastEventOfVehicle][7])),
	                 radius:2000,
	       	     };
	             cityCircle = new google.maps.Circle(populationOptions);*/
	            //allVehicleRoute(map); //too late load all vehicles
	               
	       	    // Add the circle for this city to the map.
	 
	    	    
	       	}else{
	       	   
	       	  var res=['','',''];
               var time='';
           //  alert(globalVehicles2[j]); 
               for(var j=0;j<globalVehicles1.length;j++){
            	   var dis=globalVehicles1[j].toUpperCase();
               	if (dis.indexOf(globalVehicles[i].toUpperCase()) !=-1) {
               		 var res=globalVehicles1[j].split("][");
                            //  alert(time);	
               	     break;
               	}
                }

               for(var j=0;j<globalVehicles2.length;j++){
                   var dis=globalVehicles2[j].toUpperCase();
                if (dis.indexOf(globalVehicles[i].toUpperCase()) !=-1) {
                         time=globalVehicles2[j].split("*")[0];
                        // alert(time+"  "+dis);
                             break;
                   }
           }                                               

                    var text='';
                  if(time=="" || time==0 || time=="0" || (time == null && typeof time === "object") || time=="null"){
                    text="Vehicle Repair";
                       //  alert(globalVehicles[i]+"  "+time);
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
              vehicleAvailable[vehicleAvailableIndex][10]="";
               vehicleAvailable[vehicleAvailableIndex][11]="";
               vehicleAvailable[vehicleAvailableIndex][12]="";
               vehicleAvailable[vehicleAvailableIndex][13]="";
               vehicleAvailable[vehicleAvailableIndex][14]="00:00:00";

	       	}
	    	vehicleAvailableIndex++;
	    }//end loop
	    ///allVehicleRoute(map); //here is load all vehicles early
	    //var mc = new MarkerClusterer(map);
	    //var zoom = parseInt(document.getElementById('zoom').value, 10);
	    //var size = parseInt(document.getElementById('size').value, 10);
	    //var style = parseInt(document.getElementById('style').value, 10);
	    //zoom = zoom == -1 ? null : zoom;
	    //size = size == -1 ? null : size;
	    //style = style == -1 ? null: style;
//	    alert(showvehicles);
//if(showvehicles!="single"){
             var mcOptions = {
     //       gridSize: 1,
              styles: clusterStyles,
             maxZoom: 15
      } ;
//alert(showvehicles);
        // markerClusterer = new MarkerClusterer(map, markers1,mcOptions);
if(showvehicles!="single"){
 //  alert();
// alert(maploadcount+"   "+selectedGroup+" "+showvehicles)
  if((selectedGroup=="selectall" && maploadcount==1) || selectedGroup!="selectall") 
        setbound(bounds1); 
      /* google.maps.event.addListener(markerClusterer, 'clusterclick', function(markerClusterer){
    map.setCenter(markerClusterer.getCenter());
    map.setZoom(map.getZoom()+1);
});*/
   //     map.fitBounds(bounds);


         /*
            if(markerClusterer!=null || markerClusterer!=undefined){
                   markerClusterer.setMap(null);
            }
      */ 
                  // markerClusterer = new MarkerClusterer(map, markers1,{imagePath: 'images/m'});
        singlevehiclerefresh=0; 
            }else{
           //   maploadcount=1;
             //markerClusterer.setMap(null);
              //      availabilityVehicles(); 
                     //  alert(window.localStorage.getItem('selectedVehicleIndx')); 
                    
                   vehicleRoute(window.localStorage.getItem('selectedVehicleIndx'),1);                   
             }	 
//map.fitBounds(bounds);
	  availabilityVehicles();
      if(maploadcount==1){
           groupwisedisplay();
       }
//      map.fitBounds(bounds);
	   }//end if   
	}
var hideShowGroupLabelsCount=0;
function hideGroupLabels(){
   if(hideShowGroupLabelsCount==0){
      for(var i=0; i<groupmarkers.length; i++){
           groupmarkers[i].setMap(null);
      }
      hideShowGroupLabelsCount=1;
   }else{
       groupwisedisplay();
       hideShowGroupLabelsCount=0;
    }

   setCenter(22.2587,71.1924);
   setZoom(6);
}
function groupwisedisplay(){
//  alert();
    for(var i=0; i<groupmarkers.length; i++){
          groupmarkers[i].setMap(null);
        // markers1.pop();
      // alert();
        //       //  alert(); 
    }
   groupmarkers=[];                   
   // alert(groups.length);
   for(var gindx=0;gindx<groups.length;gindx++){
             var groupName=groups[gindx].split("*")[0];
            //  getlatlongforaddress(groupName); 
             var groupVehicles=groups[gindx].split("*")[1];
         var Account=window.localStorage.getItem('accountID');
           if(Account=="gvk-up-102" || Account=="gvk-up-108" || Account=="als"){
              var lat=groups[gindx].split("*")[2];
              var lng=groups[gindx].split("*")[3];
             if(lat!='' && lng!=''){
               groupPushpin(lat,lng,map,"",groupName,"no",groupName,groupVehicles);
             }
            
             
           }else{
           for(var vehicleIndx=0;vehicleIndx<vehicleAvailable.length;vehicleIndx++){
              var optresult = vehicleAvailable[vehicleIndx][6].split("/");
              var groupforvehicles=multiReplace(optresult[0].replace(']',''),' ','');
                if(groupName.toUpperCase()==groupforvehicles.toUpperCase()){
                     if((vehicleAvailable[vehicleIndx][10]!="" && vehicleAvailable[vehicleIndx][11]!="") && (vehicleAvailable[vehicleIndx][10]!="0" && vehicleAvailable[vehicleIndx][11]!="0")){
                       
                        groupPushpin(vehicleAvailable[vehicleIndx][10],vehicleAvailable[vehicleIndx][11],map,"",groupName,"no",groupName,groupVehicles);
                        // alert(vehicleAvailable[vehicleIndx][10]+" "+vehicleAvailable[vehicleIndx][11]);
                         break;
                     }                  
          

                }
          }//end for
       }//end else if


}
 if( maploadcount==1) setbound(bounds1);

}




       var clusterStyles = [
  {
 //   textColor: 'white',
    url: 'https://raw.githubusercontent.com/googlemaps/v3-utility-library/master/markerclustererplus/images/conv30.png',
    height: 27,
        width: 30,
        anchor: [3, 0],
        textColor: '#ff00ff',
        
        textSize: 10
   },
 {
    //textColor: 'black',
    url: 'https://raw.githubusercontent.com/googlemaps/v3-utility-library/master/markerclustererplus/images/conv40.png',
     height: 36,
        width: 40,
        anchor: [6, 0],
        textColor: '#ff0000',
        textSize: 11
    
  },
 {
    //textColor: 'white',
    url: 'https://raw.githubusercontent.com/googlemaps/v3-utility-library/master/markerclustererplus/images/conv50.png',
     width: 50,
        height: 45,
        anchor: [8, 0],
        textSize: 12
   
  }
];




        function overspeedreport(){window.localStorage.setItem('showmap',"online");nochangeofflineonlineidle='yes';showorhidemarkers="show";overspeed=50;allVehicleStatus(); 

   var viewtype=window.localStorage.getItem('viewtype');
    if(viewtype=='text'){
        textview();
    }else if(viewtype=="map"){
         mapview();
    }else if(viewtype=="both"){
         bothview();
    }
}
	function allvehiclesInfo(){nochangeofflineonlineidle='no'; showorhidemarkers="show";overspeed=0;allVehicleStatus(); 
                    var viewtype=window.localStorage.getItem('viewtype');
    if(viewtype=='text'){
        textview();
    }else if(viewtype=="map"){
         mapview();
    }else if(viewtype=="both"){
         bothview();
    }


         }
	function online(){window.localStorage.setItem('showmap',"online");nochangeofflineonlineidle='yes';showorhidemarkers="show";overspeed=0;allVehicleStatus();

             var viewtype=window.localStorage.getItem('viewtype');
    if(viewtype=='text'){
        textview();
    }else if(viewtype=="map"){
         mapview();
    }else if(viewtype=="both"){
         bothview();
    }

                
}
	function offline(){window.localStorage.setItem('showmap',"offline");nochangeofflineonlineidle='yes';showorhidemarkers="show";overspeed=0;allVehicleStatus(); 
             var viewtype=window.localStorage.getItem('viewtype');
    if(viewtype=='text'){
        textview();
    }else if(viewtype=="map"){
         mapview();
    }else if(viewtype=="both"){
         bothview();
    }

 
 }


    function offroad(){window.localStorage.setItem('showmap',"offroad");nochangeofflineonlineidle='yes';showorhidemarkers="show";overspeed=0;allVehicleStatus();
             var viewtype=window.localStorage.getItem('viewtype');
    if(viewtype=='text'){
        textview();
    }else if(viewtype=="map"){
         mapview();
    }else if(viewtype=="both"){
         bothview();
    }


 }
	function idle(){window.localStorage.setItem('showmap',"idle");nochangeofflineonlineidle='yes';showorhidemarkers="show";overspeed=0;allVehicleStatus();
          var viewtype=window.localStorage.getItem('viewtype');
    if(viewtype=='text'){
        textview();
    }else if(viewtype=="map"){
         mapview();
    }else if(viewtype=="both"){
         bothview();
    }



}
	function caseinfo(casevehicles){window.localStorage.setItem('showmap',casevehicles);nochangeofflineonlineidle='yes';showorhidemarkers="show";overspeed=0;allVehicleStatus();}
	
	function allVehicleStatusWithRoute(){
		allVehicleStatus();
		allVehicleRoute(map);
	}
	var id;
	function vehicleRoute(VehiclesArrayOrVehicle,typeview){
                    removeMarkers1();
 
		//clearTimeOut();
//		removeMarkers1();
//	n	searchresultshowhide();
            //   document.getElementById("vehicleStatusReport").style.display="none";
             //  document.getElementById("availableVehicle").style.display="none";
		//var distanceRange=document.getElementById('distanceRange').value;
                 var distanceRange=15;

		nochangeofflineonlineidle="yes";
	window.localStorage.setItem('singlevehicleshow',"single");
  window.localStorage.setItem('showmap',"single");           


                 // alert(VehiclesArrayOrVehicle);
              window.localStorage.setItem('selectedVehicleIndx',VehiclesArrayOrVehicle);  
                 //     alert(window.localStorage.getItem('showmap'));	
		var vehicleNo=VehiclesArrayOrVehicle;
		if(typeview==3){
			vehicleNo=VehiclesArrayOrVehicle[0];
		}
//		var map;
		if(vehicleWithEventData[vehicleNo].length>0){
	        if(typeview==3){
	                 setZoom(15); 
	        	for(var index=0;index<VehiclesArrayOrVehicle.length;index++){
	        		startAndStopOfVehiclePosition(VehiclesArrayOrVehicle[index],map,'yes');
	        	}
	        
	        
	        }else{
	        	startAndStopOfVehiclePosition(vehicleNo,map,'yes');
                         if(singlevehiclerefresh==0){
                             // setZoom(10);
                               setCenter(parseFloat(vehicleWithEventData[vehicleNo][0][6]),parseFloat(vehicleWithEventData[vehicleNo][0][7]));

                         }
//                      singlevehiclerefresh++;

                       // show vehicles 15 so for from selected vehicle
                 /*      var selectvehilcepoint=new google.maps.LatLng(parseFloat(vehicleWithEventData[vehicleNo][0][6]),parseFloat(vehicleWithEventData[vehicleNo][0][7]));
                         for(var eventInx=0;eventInx<vehicleWithEventData.length;eventInx++){
                             if(vehicleWithEventData[eventInx]!=''){
                              var vehiclepoints=new google.maps.LatLng(parseFloat(vehicleWithEventData[eventInx][0][6]),parseFloat(vehicleWithEventData[eventInx][0][7]));

                               var dis= getDistance(selectvehilcepoint,vehiclepoints);
                                   
                                   if(dis<distanceRange){//show vehicle on map less 15 km from selected vehicle
                                           startAndStopOfVehiclePosition(eventInx,map,'no');
                                 } 
                              }

                         }*/
	        }
	        
	        if(typeview==3){
	        	document.getElementById("play").disabled = false;
	        	var vehicle1Index1=0;
	        	var vehicle1Index2=0;
	        	var vehicleNo1=VehiclesArrayOrVehicle[0];
	        	var VehicleNo2=VehiclesArrayOrVehicle[1];
	        
	        	id=setInterval(function(){
	        
					     if(vehicle1Index1>vehicleWithEventData[vehicleNo1].length-2 && vehicle1Index2>vehicleWithEventData[vehicleNo2].length-2){
					           //clearTimeOut();
				          }else{
				        	  if(vehicle1Index1 < vehicleWithEventData[vehicleNo1].length-2){
				        		  DrawLineMarkerToMarker(vehicleNo1,vehicle1Index1,map,lineSymbol); 
				        		  vehicle1Index1++;
				        	  }
				        	  if(vehicle1Index2 < vehicleWithEventData[VehicleNo2].length-2){
				        		  DrawLineMarkerToMarker(VehicleNo2,vehicle1Index2,map,lineSymbol1);
				        		  vehicle1Index2++;
				        	  }
				         	 
				         }
				        },1);//setInterval funcion closed
			}else if(typeview==2 && vehicleWithEventData[vehicleNo].length>2){
				document.getElementById("play").disabled = false;
	        	eventInx=0;
	        	 setZoom(14);
	        	id=setInterval(function(){
					     if(eventInx>vehicleWithEventData[vehicleNo].length-2){
					           //clearTimeOut();
				          }else{
				        	  DrawLineMarkerToMarker(vehicleNo,eventInx,map,lineSymbol);
				        	  eventInx++;
				        
				         }
				        },1);//setInterval funcion closed
				    
	       	   
	        }else{
	        //	 map.setZoom(10);
	        	for(var eventInx=0;eventInx<vehicleWithEventData[vehicleNo].length-2;eventInx++){
			       var lat=parseFloat(vehicleWithEventData[vehicleNo][eventInx][6]);
			 	   var lang=parseFloat(vehicleWithEventData[vehicleNo][eventInx][7]);
			 	  var title1="vehicle Id: "+vehicleWithEventData[vehicleNo][eventInx][9]+"  Address : "+vehicleWithEventData[vehicleNo][eventInx][8]+"  Odometer Reading: "+vehicleWithEventData[vehicleNo][eventInx][5];
                                      alert(title1);
			 	 CheckIgnitionOnOffIdelStop(lat,lang,vehicleNo,eventInx,map,title1,'no');
	       	    }//end inner loop
	        }//end if
		}else{
			
		     alert("Vehicle Has No movement");
		}


 bothview();
	}//end vehicleRoute Function
var rad = function(x) {
  return x * Math.PI / 180;
};
function getDistance(p1, p2) {
  var R = 6378137; // Earth’s mean radius in meter
  var dLat = rad(p2.lat() - p1.lat());
  var dLong = rad(p2.lng() - p1.lng());
  var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
    Math.cos(rad(p1.lat())) * Math.cos(rad(p2.lat())) *
    Math.sin(dLong / 2) * Math.sin(dLong / 2);
  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  var d = R * c;
  return d/1000; // returns the distance in km
}


 function callfromlabel(){
   showorhidemarkers="show";
     allVehicleStatus();


}

   

var oldmarker;
var singlevehecleRefresh=0;
  var RotateIcon = function(options){
    this.options = options || {};
    this.rImg = options.img || new Image();
    this.rImg.src = this.rImg.src || this.options.url || '';
  //  this.options.width = this.options.width || this.rImg.width || 52;
   // this.options.height = this.options.height || this.rImg.height || 60;
 //  alert(this.options.width+" "+this.rImg.width+"   "+this.options.height+" "+this.rImg.height);
   this.options.width = this.options.width || this.rImg.width || 0;
    this.options.height = this.options.height || this.rImg.height || 0;
  var  canvas = document.createElement("canvas");
    canvas.width =60;
    canvas.height =60;
// alert(this.options.width+" "+this.rImg.width+"   "+this.options.height+" "+this.rImg.height);
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

var openedinfowindow=[];
  var car = "M17.402,0H5.643C2.526,0,0,3.467,0,6.584v34.804c0,3.116,2.526,5.644,5.643,5.644h11.759c3.116,0,5.644-2.527,5.644-5.644 V6.584C23.044,3.467,20.518,0,17.402,0z M22.057,14.188v11.665l-2.729,0.351v-4.806L22.057,14.188z M20.625,10.773 c-1.016,3.9-2.219,8.51-2.219,8.51H4.638l-2.222-8.51C2.417,10.773,11.3,7.755,20.625,10.773z M3.748,21.713v4.492l-2.73-0.349 V14.502L3.748,21.713z M1.018,37.938V27.579l2.73,0.343v8.196L1.018,37.938z M2.575,40.882l2.218-3.336h13.771l2.219,3.336H2.575z M19.328,35.805v-7.872l2.729-0.355v10.048L19.328,35.805z";

	function startAndStopOfVehiclePosition(vehicleNo,map,infostatus){
		 var lastEventOfVehicle=vehicleWithEventData[vehicleNo].length-1;
		 //var image='http://track.glovision.co:8080/statictrack/images/custom/'+vehicleWithEventData[vehicleNo][0][9]+'.png';
		 //var image='../../../images/ambulance.png';
		var pincolor='black';
                var newimage='offline'; 
		 var title1='Ignition Off';
	   	     if(vehicleWithEventData[vehicleNo][lastEventOfVehicle][2]=="61714"){
 		     title1="Running";
                     newimage="online";
		      pincolor='green';
	     }else if(vehicleWithEventData[vehicleNo][lastEventOfVehicle][2]=="61718" || vehicleWithEventData[vehicleNo][lastEventOfVehicle][2]=="61720"){
		     title1="Idle";
                     newimage="idle";
                      pincolor='orange';
		        
	    }/*else if(vehicleWithEventData[vehicleNo][lastEventOfVehicle][2]=="61717" || vehicleWithEventData[vehicleNo][lastEventOfVehicle][2]=="61477"){
		    
	    	title1="stopped";
      	     
	   }*/else if(vehicleWithEventData[vehicleNo][lastEventOfVehicle][2]=="0000"){title1="offline";pincolor='black';newimage="offline";
	    }else{
		   newimage="idle";
		 	 pincolor='orange';
		    	title1="Idle";
	      
	   }
           //case manager info
              var caseID='';
             var callerName='';
                var caseType='';
                var caseTime='';
              var callerno='';
               var caseStatus='';
                 for(var cvindx=0;cvindx<contactambulance.length;cvindx++){
                      if(vehicleWithEventData[vehicleNo][lastEventOfVehicle][9].toUpperCase()==contactambulance[cvindx][0].toUpperCase()){
                             caseID=contactambulance[cvindx][4];
                             callerName=contactambulance[cvindx][3];
                             caseType=contactambulance[cvindx][5];
                             caseTime=contactambulance[cvindx][6];
                             callerno=contactambulance[cvindx][1];
                             caseStatus=contactambulance[cvindx][2];
                            if(contactambulance[cvindx][2]=="assigned"){
                                       pincolor="31f134";
                                       pincolor='green';

                             }else if(contactambulance[cvindx][2]=="inzone"){
                                    pincolor="f5e43e";
                                    pincolor='yellow';
                             }else{

                                  pincolor="f44a0f";
                                  pincolor='orange';

                              }


                               break;
                       }
                        pincolor="f44a0f";
                        pincolor='orange';

                  }

              var caseinfo="<br>Case ID:"+caseID+"<br>Caller Name:"+callerName+"<br>Case Type:"+caseType+"<br>Case Time:"+caseTime+"<br> Caller No:"+callerno;

        
	   	  
	   	  var lastEvntDate=stringFormatToDate(stringFormatToDateForFuel(vehicleWithEventData[vehicleNo][lastEventOfVehicle][4]+""));
  		var epoc1=dateToEpochDB(lastEvntDate);
  		var currentTime = new Date();
  	    var epoc2=dateToEpochDB(currentTime);
  	/*  if(parseInt((epoc2-epoc1)/(60*60))>24){
  		title1="offline";
  	  }*/
	   	 
              if(title1!="offline" && title1!="stopped" && caseID!=''){
                caseinfo=caseinfo+"<input type='button' value='Live Track' class='button-secondary pure-button' onClick='liveTracking(\""+vehicleWithEventData[vehicleNo][lastEventOfVehicle][9]+"\")'/>"
            }

          var carcolor='red';

                if(newimage=="online")carcolor="green";
                else if(newimage=="idle")carcolor="#CCCC00";


              if(newimage=="online" && caseStatus=='assigned'){

                    newimage="towardsscene";
              }else if(newimage=="online" && caseStatus=='inzone'){
                    newimage="atscene";
              }else if(newimage=="online" && caseStatus=='tohospital'){
                     newimage="towardshospital";
              }else if(newimage=="online" && caseStatus==''){
                    newimage="online";
              }else if(newimage=="idle" && caseStatus=='assigned'){
                    newimage="towardsscene";
              }else if(newimage=="idle" && caseStatus=='inzone'){
                    newimage="atscene";
              }else if(newimage=="idle" && caseStatus=='tohospital'){
                    newimage="towardshospital";
              }else if(newimage=="idle" && caseStatus==''){
                    newimage="idle";
              }else if(newimage=="offline" && caseStatus=='assigned'){
                    newimage="offline-caseassigned";
              }else if(newimage=="offline" && caseStatus=='inzone'){
                    newimage="offline-caseassigned";
              }else if(newimage=="offline" && caseStatus=='tohospital'){
                    newimage="offline-caseassigned";
              }else if(newimage=="offline" && caseStatus==''){
                    newimage="offline-casenotassigned";
              }



                 
	   	  var vehiclesType=vehicleWithEventData[vehicleNo][lastEventOfVehicle][13];
	   	// vehiclesType="ambulance";
	  	   	 if(vehiclesType==''){vehiclesType='ambulance'; }
	  	   	   var image='images/'+vehiclesType+'/'+title1+vehicleWithEventData[vehicleNo][lastEventOfVehicle][11]+'.png';
	  if(UrlExit(image)){
	  		   // image='../images/ambulance/'+title1+vehicleWithEventData[i][lastEventOfVehicle][11]+'.png';
	  	    }else{
	  	    	
	  	    	var uri = window.location.toString();
	  	  	    var url='';
	  	        if (uri.indexOf("?") > 0) {
	  	        	 var port='';
	  	           if(window.location.port!=''){port=":"+window.location.port;}
	  	  	       url = window.location.origin+port;
	  	  	    }   
	  	  	
	  	      //alert(image+" 2");
	  	    	image=url+'/statictrack/images/custom/'+vehicleWithEventData[vehicleNo][lastEventOfVehicle][9]+'.png';
	  	    	if(!UrlExit(image)){image='images/vehicle.png';}
	  	    }
	   	 //  image="images1/pin30_"+pincolor+"_"+vehicleWithEventData[vehicleNo][lastEventOfVehicle][11]+".png";   
	   	   image="images1/"+newimage+".png";  
			//var image='images/ambulance/'+title1+vehicleWithEventData[vehicleNo][lastEventOfVehicle][11]+'.png';
	  	 	//alert(image);

//image=imageRotate(car,carcolor,parseInt(vehicleWithEventData[vehicleNo][lastEventOfVehicle][11])); 


	  	  var discription='';
	  	var Account=window.localStorage.getItem('accountID');
	  	  if(Account.indexOf("gvk")>-1 || Account.indexOf("gog")>-1 || Account.indexOf("khil")>-1 || Account.indexOf("als")>-1 || Account!=""){
        	  if((typeof(vehicleWithEventData[vehicleNo][lastEventOfVehicle][12]) !== 'undefined') && (vehicleWithEventData[vehicleNo][lastEventOfVehicle][12] !== null)) {
		          var result = vehicleWithEventData[vehicleNo][lastEventOfVehicle][12].split("/");
		          
		          discription="Base Location :<b>"+(typeof(result[1])== 'undefined'?' ':result[1]).replace(']','')+"</b><br>Dist :<b>"+(typeof(result[0])== 'undefined'?'':result[0]).replace(']','')+"</b><br> Phone:<b>"+vehicleWithEventData[vehicleNo][lastEventOfVehicle][14]+"</b><br>Status :<b>"+title1+"</b>";
			      
        	  }
		 }
	  	    //call geoaddresss code 
	  	// var address=GetAddress(vehicleWithEventData[vehicleNo][lastEventOfVehicle][6],vehicleWithEventData[vehicleNo][lastEventOfVehicle][7]);
	     //alert(address);
	  	 var vehicleTitle="<h4> vehicle Id:  "+(vehicleWithEventData[vehicleNo][lastEventOfVehicle][9].toUpperCase())+"<br>DateTime :<b>"+vehicleWithEventData[vehicleNo][lastEventOfVehicle][4]+"</b><br> Lat/Long :<b>"+vehicleWithEventData[vehicleNo][lastEventOfVehicle][6]+"/"+vehicleWithEventData[vehicleNo][lastEventOfVehicle][7]+"</b><br>Speed :<b>"+vehicleWithEventData[vehicleNo][lastEventOfVehicle][3]+" Kmph</b>";
	 // 	 if(infostatus=='no'){vehicleTitle="vehicle Id:  "+(vehicleWithEventData[vehicleNo][lastEventOfVehicle][9].toUpperCase());} 
               //  var subtitle="<br>  Odometer Reading: <b>"+vehicleWithEventData[vehicleNo][lastEventOfVehicle][5]+" Km</b><br>"+discription+"<br>---------------"+caseinfo+"</h4>";
var subtitle="<br>"+discription;//"<br>---------------"+caseinfo+"</h4>";

	  	GetAddress(parseFloat(vehicleWithEventData[vehicleNo][lastEventOfVehicle][6]),parseFloat(vehicleWithEventData[vehicleNo][lastEventOfVehicle][7]),map,image,vehicleTitle,vehicleWithEventData[vehicleNo][lastEventOfVehicle][9],infostatus,subtitle,vehicleWithEventData[vehicleNo][lastEventOfVehicle][11]);
	     var title2="vehicle Id: "+vehicleWithEventData[vehicleNo][0][9]+"<br>  Address : "+vehicleWithEventData[vehicleNo][0][8]+" <br> Odometer Reading: "+vehicleWithEventData[vehicleNo][0][5];
	     var image1='';
	   //  putPushpinVehicles(parseFloat(vehicleWithEventData[vehicleNo][0][6]),parseFloat(vehicleWithEventData[vehicleNo][0][7]),map,image1,title2,vehicleWithEventData[vehicleNo][0][9],'yes');

	}
	
	
	function CheckIgnitionOnOffIdelStop(lat,lang,vehicleNo,eventInx,map,title1,infoStatus){

		 	  if(vehicleWithEventData[vehicleNo][eventInx][2]=="61718" || vehicleWithEventData[vehicleNo][eventInx][2]=="61720"){//idel
			//var image='../../../images/blue-dot.png';
		 		 var image='images/yellow.png';
                           image='';
		 	  //var image='../../../images/ambulance.png';
			  title1=title1+"   Idle";
			  putPushpinVehicles(lat,lang,map,image,title1,'Idle',infoStatus);
		  }else if(vehicleWithEventData[vehicleNo][eventInx][2]=="61717" || vehicleWithEventData[vehicleNo][eventInx][2]=="61477"){//off
			title1=title1+"   Ignition Off";
			//var image='http://maps.google.com/mapfiles/ms/icons/yellow-dot.png';
			var image='images/red.png'
                          image='';
			//	var image='../../../images/ambulance.png';
			putPushpinVehicles(lat,lang,map,image,title1,'Ignition Off',infoStatus);
	    }else if(vehicleWithEventData[vehicleNo][eventInx][2]=="61714"){//ignition on
	 	   title1=title1+"   Running";
	 	  var image='images/green.png'
           image='';
	 		// var image='../../../images/ambulance.png';
			 //  var image='http://maps.google.com/mapfiles/ms/icons/green-dot.png';
			   putPushpinVehicles(lat,lang,map,image,title1,'Ignition on',infoStatus);
	    }
		 
	}
	function clearTimeOut(){
		//setTimeout(id);
		clearTimeout(id);
		//alert("completed");

	}

	var msgperDay='';
        function filter (term,cellNr){
		 var _id='search';
       var selectlength=document.getElementById("groupdevice").length;   
     if(term.value!=""){
      for(var x=0;x<selectlength;x++){
                _id='search'+x;
                 var yy = document.getElementById(_id).parentNode.id;
		var suche =term.value.toLowerCase();
        	var table = document.getElementById(_id);
		var ele;
                var base; 
                var count=0;
		for (var r = 1; r < table.rows.length; r++){
			ele = table.rows[r].cells[cellNr].innerHTML.replace(/<[^'>]+>/g,"");
			if (ele.toLowerCase().indexOf(suche)>=0 ){
				table.rows[r].style.display = '';
                                table.rows[r].style.border = "2pt solid blue";                              
                                count++;
                         }
			else {
                          table.rows[r].style.color='black'
                          table.rows[r].style.border = "1pt solid black";
                       }                     
		 }
if(count==0){
         for (var r = 1; r < table.rows.length; r++){
//             alert('base');
                base= table.rows[r].cells[2].innerHTML.replace(/<[^'>]+>/g,"");
                  if (base.toLowerCase().indexOf(suche)>=0 ){
                                table.rows[r].style.display = '';
                                table.rows[r].style.border = "2pt solid blue";
                                count++;
                         }else {
                          table.rows[r].style.color='black'
                          table.rows[r].style.border = "1pt solid black";
                       }
                     }
}



              if(count>0){

                    expandCollapse(yy,yy+'1',2)
              }else{
                   expandCollapse(yy,yy+'1',3)
              }		 
         }
    }else{
       for(var x=0;x<selectlength;x++){
          _id='search'+x;
          var yy = document.getElementById(_id).parentNode.id;
          expandCollapse(yy,yy+'1',3)
       }
     }		
  }
function filter1 (term,cellNr){
                 var _id='contact';

                var suche =term.value.toLowerCase();
                var table = document.getElementById(_id);
                var ele;
                for (var r = 0; r < table.rows.length; r++){
                        ele = table.rows[r].cells[cellNr].innerHTML.replace(/<[^'>]+>/g,"");
                        if (ele.toLowerCase().indexOf(suche)>=0 )
                                table.rows[r].style.display = '';
                        else table.rows[r].style.display = 'none';
                }



        }
function loadVehicles(){
     var Account=window.localStorage.getItem('accountID');
                          var userID=window.localStorage.getItem('userId');
     var groupName=document.getElementById('selectedgroup').value; 
     var select=document.getElementById('vehicles');
     var selectlength=document.getElementById("vehicles").length;
     if(selectlength>=0){
         for(var i=selectlength-1;i>0;i--)
         {
             select.remove(i);
         }
      }

       $.ajax({
                       type : 'get',
                        url:'php/getVehiclesPerGroup.php',
                        data:{"accountID":Account,"userID":userID,"groupID":groupName},
                        dataType:'json',
                         success: function(data) {
                             $.each(data, function() {
                                $.each(data, function(key, value) {
                                  if(key=="markers"){
                                       for (var x=0;x<value.length;x++){
                                         var option1=document.createElement("option");
                                             option1.value= option1.text=value[x];//vehicleAvailable[vehicleIndx][0];
                                             select.appendChild(option1);}
                                  }
                               });
                              });
                 }
            });
                            
/* 
     for(var vehicleIndx=0;vehicleIndx<vehicleAvailable.length;vehicleIndx++){
          if( vehicleAvailable[vehicleIndx][6] != null){
             var optresult = vehicleAvailable[vehicleIndx][6].split("/");
              var groupforvehicles=multiReplace(optresult[0].replace(']',''),' ','');
               if(groupName.toUpperCase()==groupforvehicles.toUpperCase() || groupName=="selectall"){
                          var option1=document.createElement("option");
                            option1.value= option1.text=vehicleAvailable[vehicleIndx][0];
                            select.appendChild(option1);

                }
             }



     }
*/
}
 var collapsegroup="all";	
	function availabilityVehicles(){
		var w=800;
		var h=600;
               var showmap=window.localStorage.getItem('showmap'); 
		var left=screen.width/2-w/2;
		var top=screen.height/2-h/2;
		var search='search';
                   var User=window.localStorage.getItem('userId');
		//<thead><tr><th>Vehicle Id</th><th>OdoMeter Reading</th><th>Location </th><th>Status</th><th>Replay</th></tr></thead>
		//var table="<div class='hello' style='width:460;border:solid 2px orange;overflow-y:scroll;background: white;'><table border='1' align='center' id='search'><tbody>";

		//if(vehicleAvailable.length>16){
		var h1=(screen.height/2)+(screen.height/20);
                   var h2=(screen.height/100)*55;
                var groupwisetable="";
if(User=="tracking"){
			var table="<div class='hello' style='height:100%;border:solid 1px ;background:#b1c6c5;overflow: scroll;overflow-x:scroll;border-radius: 0px;background: #b1c6c5'><table  class='responstable' border='1' align='center' id='search' class='ok' style='border-radius: 10px;'><thead style='font-weight:bold;'><th align='center'>Status</th><th align='center'>Group Name</th><th align='center'>BaseLocation</th><th align='center'>Vehicle Number/Running Case Status</th><th align='center'>Driver Phone Number</th><th align='center'>Current Location</th><th align='center'>Last Seen</th><th align='center'>Speed(Km/h)</th></tr></thead><tbody>";
} else {

			var table="<div class='hello' style='height:100%;border:solid 1px #b1c6c5;background:#b1c6c5;overflow: scroll;overflow-x:scroll;border-radius: 0px;background: #b1c6c5'><table  class='responstable' border='1' align='center' id='search' class='ok' style='border-radius: 0px;font-family: 'Trebuchet MS', Verdana, Arial, Helvetica, sans-serif;'><thead style='font-weight:bold;'><th align='center'>Status</th><th align='center'>Group Name</th><th align='center'>BaseLocation</th><th align='center'>Vehicle Number/Running Case Status</th><th align='center'>Driver Phone Number</th><th align='center'>Current Location</th><th align='center'>Last Seen</th><th align='center'>Speed(Km/h)</th><th align='center'>Idle Time (HH:MM:SS)</th><th align='center'>History</th></tr></thead><tbody>";

}		
	var table1="<div class='hello' style='height:"+h1+";border:solid 2px orange;background: white;overflow: scroll;overflow-y:scroll;'><table border='1' class='responstable' align='center'><tbody>";
      //  var summarytable="<div class='hello' style='height:100%;background:#B20F14;overflow: scroll;overflow-x:scroll;border-radius: 0px;background: #B20F14'><table  class='responstable' border='1' align='center' id='searchi' style='border-radius: 0px;font-family: 'Trebuchet MS', Verdana, Arial, Helvetica, sans-serif;'><thead style='font-weight:bold;text-align:center'><th colspan='2'>------</th><th style='background: #13415a ' colspan='3'>Case Assigned</th><th colspan='2' style='background: #13415a '>Ready To Take Case</th><th colspan='2' style='background: #13415a '>Offline</th><th></th></thead><thead style='font-weight:bold;'><th align='center'></th><th align='center'>District</th><th align='center' style='background:rgb(142, 68, 173)'>Towards Scene</th><th align='center' style='background:rgb(52, 152, 219)'>At Scene</th><th align='center' style='background:rgb(220, 118, 51)'>Started From Scene</th><th align='center' style='background:green'>Running</th> <th align='center' style='background:rgb(215, 223, 1)'>Idle</th><th align='center' style='background:rgb(223, 1, 1)'>Attending Case</th><th style='background:rgb(247, 129, 216)' align='center'>Not Attending Case</th><th align='center'>Total Vehicles</th></tr></thead><tbody>";	

     var summarytable="<div class='hello' style='height:100%;background:#b1c6c5;overflow: scroll;overflow-x:scroll;border-radius: 0px;background: #b1c6c5'><table  class='responstable' border='1' align='center' id='searchi' style='border-radius: 0px;font-family: 'Trebuchet MS', Verdana, Arial, Helvetica, sans-serif;'><thead style='font-weight:bold;font-size:15px'><th align='center'></th><th align='center'>Group Name</th><th align='center' style='background:green'>Running Vehicles</th> <th align='center' style='background:rgb(215, 223, 1)'>Idle Vehicles</th><th align='center' style='background:rgb(223, 1, 1)'>Offline Vehicles</th> <th align='center' style='background: #FE9A2E'>OffRoad Vehicles</th><th align='center'>Total Vehicles</th><th align='center'>Map View</th></tr></thead><tbody>";


		groupwisetable="<div class='hello' style='background:#B20F14;border-radius: 0px;background: #B20F14'><table  class='responstable' border='1' align='center' style='border-radius: 0px;font-family: 'Trebuchet MS', Verdana, Arial, Helvetica, sans-serif;' id='search' class='ok'><thead style='font-weight:bold;color:white'><th align='center'>Status</th><th align='center'>Group Name</th><th align='center'>BaseLocation</th><th align='center'>Vehicle Number/<br> Running Case Status</th><th align='center'>Driver Phone Number</th><th align='center'>Current Location</th><th align='center'>Last Seen</th><th align='center'>Speed(Km/h)</th><th align='center'>Idle Time (HH:MM:SS)</th><th align='center'>History</th></tr></thead><tbody>";
			var Account=window.localStorage.getItem('accountID');
			//
			if(Account=="gvkrajasthan"){
				//table="<div class='hello' style='height:530;width:500;border:solid 2px orange;overflow-y:scroll;overflow-x:scroll;background: white;'><table border='1' align='center' id='search'><tbody>";
						        	
			}
		//}
		//var table="<div class='hello' style='width:480;border:solid 2px orange;overflow-y:scroll;background: white;'><table border='1' align='center'><tbody>";
		var Status="Iggniton Off";
		//alert(vehicleAvailable+"    "+vehicleAvailable.length);
		var replay="replay";
		var noreplay="noreplay";
		var Symbol='off';
		//table=table+"<tr><td>Ignition Off <img src='../../../images/iggnitionoff.png' height='20'></td><td>Stop <br><img src='../../../images/stop.png' height='20'></td><td> Idle <br><img src='../../../images/idle.png' height='20'></td><td> Inmotion<br><img src='../../../images/inmotion.png' height='20'></td><td> Replay Path<br><img src='../../../images/PlaySymbol.png' height='20'></a></u></td></tr>";
	   var countInMotion=0;
	   var countIdle=0;
	   var countStopped=0;
	   var countIgnitonOff=0;
	   var countRepair=0;
           var offroadcount=0;
            var offroad="";
	   var acOnCount=0;
	   //alert(vehicleAvailable.length);
	   var running='';
	   var idle='';
	   var stopped='';
	   var offline='';
           var offline_nottrackingcount=0;
           var offline_notrespondingcount=0
	   var uri = window.location.toString();
	  	    var url='';
	        if (uri.indexOf("?") > 0) {
	           var port='';
	           if(window.location.port!=''){port=":"+window.location.port;}
	  	       url = window.location.origin+port;
	          // url='http://track.glovision.co:8080';
	  	    }  
                var selectedGroup=multiReplace(document.getElementById("groupdevice").value,' ','');
             
                var divisiongroups=[];
                  var sgid=  document.getElementById("groupdevice");
                for(var i=0;i<sgid.length;i++){
                     divisiongroups[i]=sgid[i].value;
                  }
//populate baselocations list
             var select=document.getElementById('baselocations');
                          var selectlength=document.getElementById("baselocations").length;
                          if(selectlength>=0){
                             for(var i=selectlength-1;i>0;i--)
                             {
                               select.remove(i);
                             }
                         }


                  var  groupvehicles=document.getElementById('ss');
                          var groupvehicleslength=document.getElementById("ss").length;
                          if(groupvehicleslength>=0){
                             for(var i=groupvehicleslength-1;i>=0;i--)
                             {
                               groupvehicles.remove(i);
                             }
                           }
                 var casemanagerconunt=[  [0,0,0,0], [0,0,0,0], [0,0,0,0]];
      
                var assosiative=[];
                for(var vehicleIndx=0;vehicleIndx<vehicleAvailable.length;vehicleIndx++){
		//	alert(vehicleAvailable[vehicleIndx][11]+" 12:"+vehicleAvailable[vehicleIndx][12]+" 13:"+vehicleAvailable[vehicleIndx][13]+" 14:"+vehicleAvailable[vehicleIndx][14]+" 4:"+vehicleAvailable[vehicleIndx][4]+" 5:"+vehicleAvailable[vehicleIndx][5]+" 6:"+vehicleAvailable[vehicleIndx][6]+" 7:"+vehicleAvailable[vehicleIndx][7]+" 8:"+vehicleAvailable[vehicleIndx][8]+" 9:"+vehicleAvailable[vehicleIndx][9]+" 10:  "+vehicleAvailable[vehicleIndx][10]);
			 var vehiclesType=vehicleAvailable[vehicleIndx][7];
                         var vehicleCaseStatus='';
                         for(var cvindx=0;cvindx<contactambulance.length;cvindx++){
                           if(vehicleAvailable[vehicleIndx][0].toUpperCase()==contactambulance[cvindx][0].toUpperCase()){
                                  vehicleCaseStatus=contactambulance[cvindx][2];;
                                  break;
                           }
                       }      

 

			// vehiclesType="ambulance";
			 //vehicleAvailable[vehicleIndx][6];
			 var vehiclegroup='';
			 var discription='';
                         var discription2='';
			 var Account=window.localStorage.getItem('accountID');
	                  var userID=window.localStorage.getItem('userId');
                          var odometerReading="ODO:"+vehicleAvailable[vehicleIndx][2];
                    ///   alert(userID);	
                           if(userID=="monitoring" || userID=="tracking"){
                                   odometerReading='';
                            }	  
			 // alert(Account.indexOf("gvk"));


	         if(Account.indexOf("gvk")>-1 || Account.indexOf("gog")>-1 || Account.indexOf("khil")>-1 || Account.indexOf("als")>-1 || Account!=""){//Account=="gvkrajasthan"
	        	  // alert(vehicleAvailable[vehicleIndx][6]);
	        
                     //      var head="<table><thead><tr><th>Status</th><th>Vehicle Id</th><th>Phone </th><th>Base Location</th><th>District </th></tr><tr><td colspan='5'><input id='ss' name='filter' onkeyup='filter(this,1)' size='5'  type='text' placeholder='Vehilce Search'><input id='ad' name='filter' onkeyup='filter(this,3)' size='5' type='text' placeholder='Base Location Search'><input id='ad2' name='filter' onkeyup='filter(this,4)' size='5' type='text' placeholder='District Search'></td></tr></thead></table>";
	
	      //  	  var head="<table><thead><tr><th>Status</th><th>Vehicle Id</th><th>Phone </th><th>Base Location</th><th>District </th></tr><tr><td colspan='5'><input id='ss' name='filter' onkeyup='filter(this,1)' size='5'  type='text' placeholder='Vehilce Search'><input id='ad' name='filter' onkeyup='filter(this,3)' size='5' type='text' placeholder='Base Location Search'><input id='ad2' name='filter' onkeyup='filter(this,4)' size='5' type='text' placeholder='District Search'><select id='distanceRange' width='10px'><option value='10'>10</option><option value='20'>20</option><option value='30'>30</option><option value='40'>40</option><option value='50'>50</option></td></tr></thead></table>";
	        	  //<th>Address </th>
	        //	   var elem = document.getElementById("availableVehicle");
	      	  //  elem.innerHTML = head;
	      	   
             //populate baselocations 
           if( vehicleAvailable[vehicleIndx][6] != null){
             var optresult = vehicleAvailable[vehicleIndx][6].split("/");
              var groupforvehicles=multiReplace(optresult[0].replace(']',''),' ','');
               // //if(groupforvehicles.toUpperCase()=="AGRA")
                // alert(selectedGroup.toUpperCase()+"   "+groupforvehicles.toUpperCase());
               if(selectedGroup.toUpperCase()==groupforvehicles.toUpperCase() || selectedGroup=="selectall"){ 
                          var option=document.createElement("option");
                            option.value= option.text=optresult[1].replace(']','');
                            select.appendChild(option);
                            var option1=document.createElement("option");
                            option1.value= option1.text=vehicleAvailable[vehicleIndx][0];
                            groupvehicles.appendChild(option1);

                } 
             
           }


	   if((typeof(vehicleAvailable[vehicleIndx][6]) !== 'undefined') && (vehicleAvailable[vehicleIndx][6] !== null)) {
	        		  
			          var result = vehicleAvailable[vehicleIndx][6].split("/");
			           // discription="<td>"+(userID=="monitoring"?'':vehicleAvailable[vehicleIndx][9])+"</td><td style='width:15'>"+(typeof(result[1])== 'undefined'?' ':result[1]).replace(']','')+"</td><td>"+(typeof(result[0])== 'undefined'?'':result[0]).replace(']','')+"</td><td>"+vehicleAvailable[vehicleIndx][12]+"</td><td>"+vehicleAvailable[vehicleIndx][3]+"</td><td>"+vehicleAvailable[vehicleIndx][5]+"</td>";
			         discription="<td>"+(typeof(result[0])== 'undefined'?'':result[0]).replace(']','')+"</td><td style='width:15'>"+(typeof(result[1])== 'undefined'?' ':result[1]).replace(']','')+"</td>";
                                var ocolor="#d6dbdf";
                                if(vehicleAvailable[vehicleIndx][5]>=overspeed && overspeed!=0){
                                     ocolor="#F6CECE";
                               }
 
                                  discription2="<td>"+(userID=="monitoring"|| userID=="tracking"?'':vehicleAvailable[vehicleIndx][9])+"</td><td>"+vehicleAvailable[vehicleIndx][3]+"</td><td>"+vehicleAvailable[vehicleIndx][12]+"</td><td style='background-color:"+ocolor+";color:black'>"+vehicleAvailable[vehicleIndx][5]+"</td>";
				      vehiclegroup=multiReplace(result[0].replace(']',''),' ','');
	        	  }
			 }else{
	        	      discription="<td>"+parseInt(vehicleAvailable[vehicleIndx][2])+" Speed: "+vehicleAvailable[vehicleIndx][5]+" Kmph</td><td>"+vehicleAvailable[vehicleIndx][3]+"</td>";
	                 }
			 if(vehiclesType==''){vehiclesType='ambulance'; }     

                       var isshowvehicle="no";  
                       selectedGroup="selectall";      
                      if(selectedGroup=="selectall"){                    
 
                       for(var i=0;i<divisiongroups.length;i++){
                           if(divisiongroups[i].toUpperCase()==vehiclegroup.toUpperCase()){
                               isshowvehicle="yes";
                               break;
                           }
                         }
                       }else{
                            if(selectedGroup.toUpperCase()==vehiclegroup.toUpperCase()){
                              isshowvehicle="yes";
                             }

                       }
                        if(assosiative[vehiclegroup]==undefined){
                              assosiative[vehiclegroup]=[vehiclegroup,0,0,0,0,"",0,0,0,0,0,0,0,0,0,0,0,0,"","",0,""];
                        } else{
                              // alert(assosiative[vehiclegroup]);
                          }                     
                            //selectedGroup.toUpperCase()==vehiclegroup.toUpperCase() removed
			if(vehicleAvailable[vehicleIndx][1]=="61714" && (isshowvehicle=="yes" || vehicleCaseStatus==showmap)){
                               assosiative[vehiclegroup][1]=parseInt(assosiative[vehiclegroup][1])+1;
                               assosiative[vehiclegroup][2]=parseInt(assosiative[vehiclegroup][2])+1;
				Status="Iggniton On";
				Symbol='RunningE';
				countInMotion++;
                               
					var image="images/"+vehiclesType+"/"+Symbol+".png";
			  	  if(UrlExit(image)){
			  		   // image='../images/ambulance/'+title1+vehicleWithEventData[i][lastEventOfVehicle][11]+'.png';
			  	    }else{
			  	    	//alert(image+" 3");
			  	    	image=url+'/statictrack/images/custom/'+vehicleAvailable[vehicleIndx][0]+'.png';
			  	    	if(!UrlExit(image)){image='images/vehicle.png';}
			  	    } 
				
				//running=running+"<tr><td><u><a  onClick='vehicleRoute("+vehicleIndx+",1)'>"+vehicleAvailable[vehicleIndx][0]+"</a></u></td><td>"+vehicleAvailable[vehicleIndx][6]+"<br>"+parseInt(vehicleAvailable[vehicleIndx][2])+" Speed: "+vehicleAvailable[vehicleIndx][5]+" Kmph</td><td>"+vehicleAvailable[vehicleIndx][3]+"</td><td><a  onClick='vehicleRoute("+vehicleIndx+",1)'><img src='"+image+"' align='center' height='20'></a></td></tr>";
				//	running=running+"<tr><td><u><a  onClick='vehicleRoute("+vehicleIndx+",1)'>"+vehicleAvailable[vehicleIndx][0].toUpperCase()+"</a></u></td>"+discription+"<td><a  onClick='vehicleRoute("+vehicleIndx+",1)'><img src='"+image+"' align='center' height='20'></a></td></tr>";
                                $routeUrl="<td>"+vehicleAvailable[vehicleIndx][14]+"</td><td><input type='button' onClick=routeTracking('"+vehicleAvailable[vehicleIndx][0]+"') value='History' style='width:70px' class='button-clear' data-toggle='modal' data-target='#myModal'></td>";
                             if(userID=="monitoring" || userID=="tracking"){
                               $routeUrl='';
                             }
                            image="images/on.gif";
                           // if(selectedGroup==vehiclegroup || selectedGroup=="selectall")
                           var caselist='';
                           if(vehicleAvailable[vehicleIndx][5]>=overspeed){
                                  var color="#5FB404";              

                             //  if(collapsegroup=="all" || collapsegroup==vehiclegroup){
                                   if(vehicleCaseStatus=='assigned'){
                                       color='#8e44ad';
                                       caselist='towardsscene';
                                        casemanagerconunt[0][0]=casemanagerconunt[0][0]+1;
                                         assosiative[vehiclegroup][6]=parseInt(assosiative[vehiclegroup][6])+1;
                                    }
                                  else if(vehicleCaseStatus=='inzone'){
                                         caselist='atscene';
                                         casemanagerconunt[0][1]=casemanagerconunt[0][1]+1;
                                          assosiative[vehiclegroup][7]=parseInt(assosiative[vehiclegroup][7])+1;
                                         color='#3498db';}
                                  else if(vehicleCaseStatus=='tohospital'){ caselist='towardshospital';color='#dc7633';casemanagerconunt[0][3]=casemanagerconunt[0][3]+1;
 assosiative[vehiclegroup][9]=parseInt(assosiative[vehiclegroup][9])+1;
}
                                  else{
                                       caselist='readytotakecase-online';
                                       casemanagerconunt[0][2]=casemanagerconunt[0][2]+1;
                                            assosiative[vehiclegroup][8]=parseInt(assosiative[vehiclegroup][8])+1;
                                   }
                                 
                              // }
                               //  running=running+"<tr><td><a  onClick='vehicleRoute("+vehicleIndx+",1)'><img src='"+image+"' align='center' height='20'> speed:"+vehicleAvailable[vehicleIndx][5]+"-Kmph "+odometerReading+"</a></td><td style='background-color:"+color+";color:white' class='animation'><p onClick='vehicleRoute("+vehicleIndx+",1)'  >"+vehicleAvailable[vehicleIndx][0].toUpperCase()+"</p> </td>"+discription+$routeUrl+"</tr>";
                               //
                              if((showmap!='all' && showmap!="online" && showmap!="offline" && showmap!='idle' && showmap!='single') && showmap==caselist){
                                 running=running+"<tr><td><a  onClick='vehicleRoute("+vehicleIndx+",1)'><img src='"+image+"' align='center' height='20'></a></td>"+discription+"<td style='background-color:"+color+";color:black'><p onClick='vehicleRoute("+vehicleIndx+",1)'  >"+vehicleAvailable[vehicleIndx][0].toUpperCase()+"</p> </td>"+discription2+$routeUrl+"</tr>";
                              }else if(showmap=='all' || showmap=="online" || showmap=="offline" || showmap=='idle' || showmap=="single"){ 
                                running=running+"<tr><td><a  onClick='vehicleRoute("+vehicleIndx+",1)'><img src='"+image+"' align='center' height='20'></a></td>"+discription+"<td style='background-color:"+color+";color:black'><p onClick='vehicleRoute("+vehicleIndx+",1)'  >"+vehicleAvailable[vehicleIndx][0].toUpperCase()+"</p> </td>"+discription2+$routeUrl+"</tr>";


                                  }  
                                 //style='background-color:"+color+";color:black' case color classification 
                                 assosiative[vehiclegroup][5]= assosiative[vehiclegroup][5]+"<tr><td><a  onClick='vehicleRoute("+vehicleIndx+",1)'><img src='"+image+"' align='center' height='20'></a></td>"+discription+"<td><p onClick='vehicleRoute("+vehicleIndx+",1)'  >"+vehicleAvailable[vehicleIndx][0].toUpperCase()+"</p> </td>"+discription2+$routeUrl+"</tr>"; 
                                }
                           

			  	    //<input type='checkbox' value='"+vehicleIndx+"' name='multipleVehicles'>
			}else if((vehicleAvailable[vehicleIndx][1]=="61718" || vehicleAvailable[vehicleIndx][1]=="61720" || vehicleAvailable[vehicleIndx][1]=="61717") && (isshowvehicle=="yes"  || vehicleCaseStatus==showmap)){
				Status="Idle";
				Symbol='IdleE';
				countIdle++; 
                            assosiative[vehiclegroup][1]=parseInt(assosiative[vehiclegroup][1])+1;
                               assosiative[vehiclegroup][3]=parseInt(assosiative[vehiclegroup][3])+1;
	     	   	var image="images/"+vehiclesType+"/"+Symbol+".png";
		  	 if(UrlExit(image)){
		  		   // image='../images/ambulance/'+title1+vehicleWithEventData[i][lastEventOfVehicle][11]+'.png';
		  	    }else{
		  	    	  //alert(image+"  4");
		  	    	image='images/vehicle.png';
		  	    	image= url+'/statictrack/images/custom/'+vehicleAvailable[vehicleIndx][0]+'.png';
		  	    	if(!UrlExit(image)){image='images/vehicle.png';}
		  	    } 
				//idle=idle+"<tr><td><u><a  onClick='vehicleRoute("+vehicleIndx+",1)'>"+vehicleAvailable[vehicleIndx][0]+"</a></u></td><td>"+vehicleAvailable[vehicleIndx][6]+"<br>"+parseInt(vehicleAvailable[vehicleIndx][2])+" Speed: "+vehicleAvailable[vehicleIndx][5]+" Kmph</td><td>"+vehicleAvailable[vehicleIndx][3]+"</td><td><a  onClick='vehicleRoute("+vehicleIndx+",1)'><img src='"+image+"' align='center' height='20'></a></td></tr>";
		//		idle=idle+"<tr><td><u><a  onClick='vehicleRoute("+vehicleIndx+",1)'>"+vehicleAvailable[vehicleIndx][0].toUpperCase()+"</a></u></td>"+discription+"<td><a  onClick='vehicleRoute("+vehicleIndx+",1)'><img src='"+image+"' align='center' height='20'></a></td></tr>";
                $routeUrl="<td>"+vehicleAvailable[vehicleIndx][14]+"</td><td><input type='button' onClick=routeTracking('"+vehicleAvailable[vehicleIndx][0]+"') value='History' style='width:70px' class='button-clear' data-toggle='modal' data-target='#myModal'></td>";
                      if(userID=="monitoring" || userID=="tracking"){
                       $routeUrl='';
                     }



                //     if(selectedGroup==vehiclegroup || selectedGroup=="selectall")
                    image="images/off.gif";
                          var color="#D7DF01";
                            var caselist='';
                               // if(collapsegroup=="all" || collapsegroup==vehiclegroup){
                                   if(vehicleCaseStatus=='assigned'){caselist='towardsscene';color='#8e44ad';casemanagerconunt[1][0]=casemanagerconunt[1][0]+1; assosiative[vehiclegroup][10]=parseInt(assosiative[vehiclegroup][10])+1;}
                                  else  if(vehicleCaseStatus=='inzone'){caselist='atscene';color='#3498db';casemanagerconunt[1][1]=casemanagerconunt[1][1]+1;  assosiative[vehiclegroup][11]=parseInt(assosiative[vehiclegroup][11])+1;}
                                   else if(vehicleCaseStatus=='tohospital'){caselist='towardshospital';color='#dc7633';casemanagerconunt[1][3]=casemanagerconunt[1][3]+1;  assosiative[vehiclegroup][13]=parseInt(assosiative[vehiclegroup][13])+1;}
                                  else{casemanagerconunt[1][2]=casemanagerconunt[1][2]+1;caselist='readytotakecase-idle';  assosiative[vehiclegroup][12]=parseInt(assosiative[vehiclegroup][12])+1;}
                           // }

                        //  idle=idle+"<tr><td><a  onClick='vehicleRoute("+vehicleIndx+",1)'><img src='"+image+"' align='center' height='20'> "+odometerReading+"</a></td><td style='background-color:"+color+"' class='animation'><p onClick='vehicleRoute("+vehicleIndx+",1)'  style='width:100%;height:100%;'> "+vehicleAvailable[vehicleIndx][0].toUpperCase()+"</p> </td>"+discription+$routeUrl+"</tr>";
                          if((showmap!='all' && showmap!="online" && showmap!="offline" && showmap!='idle' && showmap!='single') && showmap==caselist){   
                          idle=idle+"<tr><td><a  onClick='vehicleRoute("+vehicleIndx+",1)'><img src='"+image+"' align='center' height='20'></a></td>"+discription+"<td style='background-color:"+color+"' ><p onClick='vehicleRoute("+vehicleIndx+",1)'  style='width:100%;height:100%;'> "+vehicleAvailable[vehicleIndx][0].toUpperCase()+"</p> </td>"+discription2+$routeUrl+"</tr>";
                         }else if(showmap=='all' || showmap=="online" || showmap=="offline" || showmap=='idle' || showmap=="single"){
                             idle=idle+"<tr><td><a  onClick='vehicleRoute("+vehicleIndx+",1)'><img src='"+image+"' align='center' height='20'></a></td>"+discription+"<td style='background-color:"+color+"' ><p onClick='vehicleRoute("+vehicleIndx+",1)'  style='width:100%;height:100%;'> "+vehicleAvailable[vehicleIndx][0].toUpperCase()+"</p> </td>"+discription2+$routeUrl+"</tr>";


                         }
                          assosiative[vehiclegroup][18]= assosiative[vehiclegroup][18]+"<tr><td><a  onClick='vehicleRoute("+vehicleIndx+",1)'><img src='"+image+"' align='center' height='20'></a></td>"+discription+"<td ><p onClick='vehicleRoute("+vehicleIndx+",1)'  >"+vehicleAvailable[vehicleIndx][0].toUpperCase()+"</p> </td>"+discription2+$routeUrl+"</tr>";
   
			}/*else if(vehicleAvailable[vehicleIndx][1]=="61717" || vehicleAvailable[vehicleIndx][1]=="61477"){
		
			}*/else if(vehicleAvailable[vehicleIndx][1]=="0000" && (isshowvehicle=="yes" || vehicleCaseStatus==showmap)) {
                                  if(vehicleAvailable[vehicleIndx][4]!="Vehicle Repair")
                                        offline_nottrackingcount++;
                                  else{
                                      offline_notrespondingcount++;
                                 }
                                assosiative[vehiclegroup][1]=parseInt(assosiative[vehiclegroup][1])+1;
                                assosiative[vehiclegroup][4]=parseInt(assosiative[vehiclegroup][4])+1;
				Status="Repair";
				Symbol='offlineE';
				countRepair++;
				var image="images/"+vehiclesType+"/"+Symbol+".png";
				msgperDay +=vehicleAvailable[vehicleIndx][0]+ " is OffLine <br>";
                                $routeUrl="<td>"+vehicleAvailable[vehicleIndx][14]+"</td><td><input type='button' onClick=routeTracking('"+vehicleAvailable[vehicleIndx][0]+"') value='History' style='width:70px' class='button-clear' data-toggle='modal' data-target='#myModal'></td>";
                                if(userID=="monitoring"  || userID=="tracking"){
                                    $routeUrl='';
                                 }
                                 image="images//NotRpt.gif";
                                 var color="#F781D8";
                                 var caselist='';
                                 if(vehicleCaseStatus=='assigned'){caselist='offline-caseassigned';color='#DF0101';casemanagerconunt[2][0]=casemanagerconunt[2][0]+1;  assosiative[vehiclegroup][14]=parseInt(assosiative[vehiclegroup][14])+1;}
                                 else if(vehicleCaseStatus=='inzone'){caselist='offline-caseassinged';color='#DF0101';casemanagerconunt[2][1]=casemanagerconunt[2][1]+1;  assosiative[vehiclegroup][15]=parseInt(assosiative[vehiclegroup][15])+1;}
                                 else if(vehicleCaseStatus=='tohospital'){caselist='offline-caseassigned';color='#dc7633';casemanagerconunt[2][3]=casemanagerconunt[2][3]+1;  assosiative[vehiclegroup][17]=parseInt(assosiative[vehiclegroup][17])+1;}
                                 else{casemanagerconunt[2][2]=casemanagerconunt[2][2]+1;caselist='offline-casenotassigned';  assosiative[vehiclegroup][16]=parseInt(assosiative[vehiclegroup][16])+1;}
                                 if((showmap!='all' && showmap!="online" && showmap!="offline" && showmap!='idle' && showmap!='single') && showmap==caselist){    
                          offline=offline+"<tr><td><a  onClick='vehicleRoute("+vehicleIndx+",1)'><img src='"+image+"' align='center' height='20'> </a></td>"+discription+"<td style='background-color:"+color+"'><p  onClick='vehicleRoute("+vehicleIndx+",1)' > "+vehicleAvailable[vehicleIndx][0].toUpperCase()+"</p></td> "+discription2+$routeUrl+"</tr>";
                                 }else if(showmap=='all' || showmap=="online" || showmap=="offline" || showmap=='idle' || showmap=="single"){
                                    offline=offline+"<tr><td><a  onClick='vehicleRoute("+vehicleIndx+",1)'><img src='"+image+"' align='center' height='20'> </a></td>"+discription+"<td style='background-color:"+color+"'><p  onClick='vehicleRoute("+vehicleIndx+",1)' > "+vehicleAvailable[vehicleIndx][0].toUpperCase()+"</p></td> "+discription2+$routeUrl+"</tr>";
                                 }
                                  assosiative[vehiclegroup][19]= assosiative[vehiclegroup][19]+"<tr><td><a  onClick='vehicleRoute("+vehicleIndx+",1)'><img src='"+image+"' align='center' height='20'></a></td>"+discription+"<td ><p onClick='vehicleRoute("+vehicleIndx+",1)'  >"+vehicleAvailable[vehicleIndx][0].toUpperCase()+"</p> </td>"+discription2+$routeUrl+"</tr>";

			}else if(vehicleAvailable[vehicleIndx][1]=="offroad" && (isshowvehicle=="yes" || vehicleCaseStatus==showmap)) {
                                        offroadcount++;
                                assosiative[vehiclegroup][1]=parseInt(assosiative[vehiclegroup][1])+1;
                                assosiative[vehiclegroup][20]=parseInt(assosiative[vehiclegroup][20])+1;
                                Status="Repair";
                                Symbol='offlineE';
                               // countRepair++;
                                var image="images/"+vehiclesType+"/"+Symbol+".png";
                                msgperDay +=vehicleAvailable[vehicleIndx][0]+ " is OffLine <br>";
                                $routeUrl="<td>"+vehicleAvailable[vehicleIndx][14]+"</td><td><input type='button' onClick=routeTracking('"+vehicleAvailable[vehicleIndx][0]+"') value='History' style='width:70px' class='button-clear' data-toggle='modal' data-target='#myModal'></td>";
                                if(userID=="monitoring"  || userID=="tracking"){
                                    $routeUrl='';
                                 }
                                 image="images//NotRpt.gif";
                                 var color="#F781D8";
                                 var caselist='';
                                 if(vehicleCaseStatus=='assigned'){caselist='offline-caseassigned';color='#DF0101';casemanagerconunt[2][0]=casemanagerconunt[2][0]+1;  assosiative[vehiclegroup][14]=parseInt(assosiative[vehiclegroup][14])+1;}
                                 else if(vehicleCaseStatus=='inzone'){caselist='offline-caseassinged';color='#DF0101';casemanagerconunt[2][1]=casemanagerconunt[2][1]+1;  assosiative[vehiclegroup][15]=parseInt(assosiative[vehiclegroup][15])+1;}
                                 else if(vehicleCaseStatus=='tohospital'){caselist='offline-caseassigned';color='#dc7633';casemanagerconunt[2][3]=casemanagerconunt[2][3]+1;  assosiative[vehiclegroup][17]=parseInt(assosiative[vehiclegroup][17])+1;}
                                 else{casemanagerconunt[2][2]=casemanagerconunt[2][2]+1;caselist='offline-casenotassigned';  assosiative[vehiclegroup][16]=parseInt(assosiative[vehiclegroup][16])+1;}
                                 if((showmap!='all' && showmap!="online" && showmap!="offline" && showmap!='idle' && showmap!='single') && showmap==caselist){
                          offroad=offroad+"<tr><td><a  onClick='vehicleRoute("+vehicleIndx+",1)'><img src='"+image+"' align='center' height='20'> </a></td>"+discription+"<td style='background-color:"+color+"'><p  onClick='vehicleRoute("+vehicleIndx+",1)' > "+vehicleAvailable[vehicleIndx][0].toUpperCase()+"</p></td> "+discription2+$routeUrl+"</tr>";
                                 }else if(showmap=='all' || showmap=="online" || showmap=="offline" || showmap=='idle' || showmap=="single"){
                                    offroad=offroad+"<tr><td><a  onClick='vehicleRoute("+vehicleIndx+",1)'><img src='"+image+"' align='center' height='20'> </a></td>"+discription+"<td style='background-color:"+color+"'><p  onClick='vehicleRoute("+vehicleIndx+",1)' > "+vehicleAvailable[vehicleIndx][0].toUpperCase()+"</p></td> "+discription2+$routeUrl+"</tr>";
                                }
                                  assosiative[vehiclegroup][21]= assosiative[vehiclegroup][21]+"<tr><td><a  onClick='vehicleRoute("+vehicleIndx+",1)'><img src='"+image+"' align='center' height='20'></a></td>"+discription+"<td ><p onClick='vehicleRoute("+vehicleIndx+",1)'  >"+vehicleAvailable[vehicleIndx][0].toUpperCase()+"</p> </td>"+discription2+$routeUrl+"</tr>";
                        }

                            
                        else if(isshowvehicle=="yes" || vehicleCaseStatus==showmap){
				Status="Idle";
				Symbol='stoppedE';
				
				countStopped++;
                                assosiative[vehiclegroup][1]=parseInt(assosiative[vehiclegroup][1])+1;
                               assosiative[vehiclegroup][3]=parseInt(assosiative[vehiclegroup][3])+1;
				var image="images/"+vehiclesType+"/"+Symbol+".png";
		  	    if(UrlExit(image)){
		  		   // image='../images/ambulance/'+title1+vehicleWithEventData[i][lastEventOfVehicle][11]+'.png';
		  	    }else{
		  	    	//alert(image+"  5");
		  	    	image='images/vehicle.png';
		  	    	/*image= url+'/statictrack/images/custom/'+vehicleAvailable[vehicleIndx][0]+'.png';
		  	    	
		  	    	if(!UrlExit(image)){image='images/vehicle.png';}*/
		  	    } 
//				stopped=stopped+"<tr><td><u><a  onClick='vehicleRoute("+vehicleIndx+",1)'>"+vehicleAvailable[vehicleIndx][0].toUpperCase()+"</a></u></td>"+discription+"<td><a  onClick='vehicleRoute("+vehicleIndx+",1)'><img src='"+image+"' align='center' height='20'></a></td></tr>";
                $routeUrl="<td>"+vehicleAvailable[vehicleIndx][14]+"</td><td><input type='button' onClick=routeTracking('"+vehicleAvailable[vehicleIndx][0]+"') value='History' style='width:70px' class='button-clear' data-toggle='modal' data-target='#myModal'></td>";
                      if(userID=="monitoring" || userID=="tracking"){
                       $routeUrl='';
                     }
                          image="images/off.gif";
                         // if(selectedGroup==vehiclegroup || selectedGroup=="selectall")
                               var color="#D7DF01";
                              var caselist='';

                   //            if(collapsegroup=="all" || collapsegroup==vehiclegroup){
                                   if(vehicleCaseStatus=='assigned'){caselist='towardsscene';color='#8e44ad';casemanagerconunt[1][0]=casemanagerconunt[1][0]+1;  assosiative[vehiclegroup][10]=parseInt(assosiative[vehiclegroup][10])+1;}
                                  else  if(vehicleCaseStatus=='inzone'){caselist='atscene';color='#3498db';casemanagerconunt[1][1]=casemanagerconunt[1][1]+1;  assosiative[vehiclegroup][11]=parseInt(assosiative[vehiclegroup][11])+1;}
                                  else if(vehicleCaseStatus=='tohospital'){caselist='towardshospital';color='#dc7633';casemanagerconunt[1][3]=casemanagerconunt[1][3]+1;  assosiative[vehiclegroup][13]=parseInt(assosiative[vehiclegroup][13])+1;}
                                  else{casemanagerconunt[1][2]=casemanagerconunt[1][2]+1;caselist='readytotakecase-idle';  assosiative[vehiclegroup][12]=parseInt(assosiative[vehiclegroup][12])+1;}
                     //           }
		//		stopped=stopped+"<tr><td><a  onClick='vehicleRoute("+vehicleIndx+",1)'><img src='"+image+"' align='center' height='20'> "+odometerReading+"</a></td><td style='background-color:"+color+"' class='animation'><p  onClick='vehicleRoute("+vehicleIndx+",1)' >"+vehicleAvailable[vehicleIndx][0].toUpperCase()+"</p></u></td>"+discription+$routeUrl+"</tr>";
                         if((showmap!='all' && showmap!="online" && showmap!="offline" && showmap!='idle' && showmap!='single') && showmap==caselist){
                               stopped=stopped+"<tr><td><a  onClick='vehicleRoute("+vehicleIndx+",1)'><img src='"+image+"' align='center' height='20'></a></td>"+discription+"<td style='background-color:"+color+"'><p  onClick='vehicleRoute("+vehicleIndx+",1)' >"+vehicleAvailable[vehicleIndx][0].toUpperCase()+"</p></u></td>"+discription2+$routeUrl+"</tr>";
                          }else if(showmap=='all' || showmap=="online" || showmap=="offline" || showmap=='idle' || showmap=='single'){

                              stopped=stopped+"<tr><td><a  onClick='vehicleRoute("+vehicleIndx+",1)'><img src='"+image+"' align='center' height='20'></a></td>"+discription+"<td style='background-color:"+color+"'><p  onClick='vehicleRoute("+vehicleIndx+",1)' >"+vehicleAvailable[vehicleIndx][0].toUpperCase()+"</p></u></td>"+discription2+$routeUrl+"</tr>";
                           }
                           assosiative[vehiclegroup][18]= assosiative[vehiclegroup][18]+"<tr><td><a  onClick='vehicleRoute("+vehicleIndx+",1)'><img src='"+image+"' align='center' height='20'></a></td>"+discription+"<td ><p onClick='vehicleRoute("+vehicleIndx+",1)'  >"+vehicleAvailable[vehicleIndx][0].toUpperCase()+"</p> </td>"+discription2+$routeUrl+"</tr>";

			}
			/*else{
				countIgnitonOff++;
				//Symbol='red';
				Symbol='off';
			}*/
			
			if(vehicleAvailable[vehicleIndx][4]=="ON"){
				acOnCount++;
			}
			//table=table+"<tr><td><input type='checkbox' value='"+vehicleIndx+"' name='multipleVehicles'><u><a  onClick='vehicleRoute("+vehicleIndx+",1)'>"+vehicleAvailable[vehicleIndx][0]+"</a></u></td><td>"+vehicleAvailable[vehicleIndx][2]+"</td><td>"+vehicleAvailable[vehicleIndx][3]+"</td><td><img src='../../../images/"+Symbol+".png' align='center' height='20'></td><td><u><a  onClick='vehicleRoute("+vehicleIndx+",2)'><img src='../../../images/PlaySymbol.png' align='center'></a></u></td><td>"+vehicleAvailable[vehicleIndx][4]+"</td></tr>";
			//table=table+"<tr><td><input type='checkbox' value='"+vehicleIndx+"' name='multipleVehicles'><u><a  onClick='vehicleRoute("+vehicleIndx+",1)'>"+vehicleAvailable[vehicleIndx][0]+"</a></u></td><td>"+parseInt(vehicleAvailable[vehicleIndx][2])+"<br> Speed: "+vehicleAvailable[vehicleIndx][5]+" Kmph</td><td>"+vehicleAvailable[vehicleIndx][3]+"</td><td><a  onClick='vehicleRoute("+vehicleIndx+",1)'><img src='../../../images/ambulance/"+Symbol+".png' align='center' height='20'></a></td></tr>";

		 }
		//table=table+"<tr><td>Ignition Off <img src='../../../images/iggnitionoff.png' height='20'></td><td>Stop <br><img src='../../../images/stop.png' height='20'></td><td> Idle <br><img src='../../../images/idle.png' height='20'></td><td> Inmotion<br><img src='../../../images/inmotion.png' height='20'></td><td> Replay Path<br><img src='../../../images/PlaySymbol.png' height='20'></a></u></td></tr>";
    
	    //table=table+"<tr><td colspan='4' bgcolor='#4B8A08'>Running Vehcile</td></tr>"+running+"<tr><td colspan='4' bgcolor='#D7DF01'>Idel Vehicle</td></tr>"+idle+"<tr><td colspan='4' bgcolor='#FF0040'>Stopped Vehicles</td></tr>"+stopped+"<tr><td colspan='4' bgcolor='#2A120A'>Offline Vehicles</td></tr>"+offline+"</tbody></table><div>";
		//table=table+"<tr bgcolor='#4B8A08'><td></td><td></td><td><font color='#FFFFFF'>Ruunig Vehicle</font></td><td></td></tr>"+running+"<tr bgcolor='#D7DF01'><td></td><td></td><td><font>Idle Vehicle</font></td><td></td></tr>"+idle+"<tr bgcolor='#FF0040'><td></td><td></td><td><font>Stopped Vehicle</font></td><td></td></tr>"+stopped+"<tr bgcolor='#2A120A'><td></td><td></td><td><font color='#FFFFFF'>Offline Vehicles</font></td><td></td></tr>"+offline+"</tbody></table><div>";
            if(userID=="monitoring" || userID=="tracking"){
                 offline='';
                }
	
		var showmap= window.localStorage.getItem('showmap');
		var allvehiclesshow=table1+running+idle+stopped+offline+offroad+"</tbody></table><div>";
		var runningVehilces=table1+running+"</tbody></table><div>";
		var idleVehilces=table1+idle+stopped+"</tbody></table><div>";
		var offlineVehilces=table1+offline+"</tbody></table><div>";
                var offroadVehicles=table1+offroad+"</tbody></table><div>";                   

		if(showmap=='all' ||showmap=='clear' || showmap=='single' || showmap=='towardsscene' || showmap=='atscene' || showmap=='towardshospital' || showmap=='readytotakecase-online' || showmap=='readytotakecase-idle' || showmap=='offline-caseassigned' || showmap=='offline-casenotassigned'){table=table+running+idle+stopped+offline+"</tbody></table><div>";

}
		else if(showmap=='online'){table=table+running+"</tbody></table><div>";}
		else if(showmap=='offline'){table=table+offline+"</tbody></table><div>";}
		else if(showmap=='idle'){table=table+idle+stopped+"</tbody></table><div>";}
		else if(showmap=='offroad'){table=table+offroad+"</tbody></table><div>";}
		
		//table=table+"</tbody></table><div>";
	     var totalvehicle=countInMotion+countIdle+countStopped+countRepair+offroadcount;
	     var onlineVehilce=countInMotion+countIdle+countStopped;
             var caseandstatustable="<table style='font-weight: bold;font-size: 15px;'><tr style='background-color:#E6E6E6'><th></th><th style='color:black'>Case<br> Assigned</th><th style='color:black'>At Scene</th><th style='color:black'>Ready To <br>Take Case</th></tr><tr><th style='background-color:#E6E6E6;color:black' >Running<br>"+countInMotion;
           caseandstatustable +=(userID=="tracking")?"</th>":"<a onclick='getRunning();'><img src='images/arrows.png' width='16' height='16' /></a></th>"
           caseandstatustable +="<td style='background-color:#0B610B;color:white' class='animation'>"+casemanagerconunt[0][0]+"</td><td style='background-color:#4B8A08;color:black' class='animation'>"+casemanagerconunt[0][1]+"</td><td style='background-color:#5FB404;color:black' class='animation'>"+casemanagerconunt[0][2]+"</td></tr><tr><th style='background-color:#E6E6E6;color:black' '>Idle<br>"+(countIdle+countStopped);
           caseandstatustable +=(userID=="tracking")?"</th>":"<a onclick='getIdle();'><img src='images/arrows.png' width='16' height='16' /></a></th>";

           caseandstatustable +="<td style='background-color:#D7DF01;color:black' class='animation'>"+casemanagerconunt[1][0]+"</td><td style='background-color:#F7FE2E;color:black' class='animation'>"+casemanagerconunt[1][1]+"</td><td style='background-color:#F2F5A9;color:black' class='animation'>"+casemanagerconunt[1][2]+"</td></tr><tr><th style='background-color:#E6E6E6;color:black' >Offline<br>"+countRepair;
         caseandstatustable +=(userID=="tracking")?"</th>":"<a onclick='getOfflineDownload();'><img src='images/arrows.png' width='16' height='16' /></a></th>";
        caseandstatustable += "<td style='background-color:#DF0101;color:white' class='animation'>"+casemanagerconunt[2][0]+"</td><td style='background-color:#FA5858;color:white' class='animation'>"+casemanagerconunt[2][1]+"</td><td style='background-color:#F5A9A9;color:white' class='animation'>"+casemanagerconunt[2][2]+"</td></tr><tr style='background-color:#E6E6E6;'><td style='color:black'>Total <br>"+totalvehicle;
        caseandstatustable +=(userID=="tracking")?"</td>":"<a onclick='statusReportDownload();'><img src='images/arrows.png' width='16' height='16' /></a></td>";
         caseandstatustable += "<td style='color:black'>"+(casemanagerconunt[0][0]+casemanagerconunt[1][0]+casemanagerconunt[2][0])+"</td><td style='color:black'>"+(casemanagerconunt[0][1]+casemanagerconunt[1][1]+casemanagerconunt[2][1])+"</td><td style='color:black'>"+(casemanagerconunt[0][2]+casemanagerconunt[1][2]+casemanagerconunt[2][2])+"</td></tr>";
       caseandstatustable  +="<tr><td colspan='2' style='color:black;background-color:#E6E6E6'>Not Tracking:"+(offline_nottrackingcount)+"</td><td colspan='2' style='color:black;background-color:#E6E6E6'>Not Responding:"+(offline_notrespondingcount+offroadcount)+"</td></tr></table>";
        
    if(Account=="als"){ 
          var newlook="<table style='font-weight: bold;font-size: 15px;'><tr style='background-color:#e8d4d0'><th colspan='2' align='center'><b>Cases</b></th></tr><tr style='background-color:#8e44ad'><th style='color:white'>Towards Scene</th><th style='color:white'><a onclick=caseinfo('towardsscene')>"+(casemanagerconunt[0][0]+casemanagerconunt[1][0])+"</a></th></tr><tr style='background-color: #3498db'><th style='color:white'>At Scene</th><th style='color:white'><a onclick=caseinfo('atscene')>"+(casemanagerconunt[0][1]+casemanagerconunt[1][1])+"</a></th></tr><tr style='background-color: #dc7633'><th style='color:white'>Started From Scene</th><th style='color:white'><a onclick=caseinfo('towardshospital')>"+(casemanagerconunt[0][3]+casemanagerconunt[1][3]+casemanagerconunt[2][3])+"</th></tr><tr style='background-color:#e8d4d0'><th colspan='2' align='center'><b>Ready To Take Case</b></th></tr><tr style='background-color:#5FB404'><th>Running</th><th><a onclick=caseinfo('readytotakecase-online')>"+(casemanagerconunt[0][2])+"</a></th></tr><tr style='background-color:#D7DF01'><th>Idle</th><th><a onclick=caseinfo('readytotakecase-idle')>"+(casemanagerconunt[1][2])+"</a></th></tr><tr style='background-color: #e8d4d0 '><th colspan='2' align='center'><b>Offline</b></th></tr><tr style='background-color:#DF0101'><th style='color:white'>Attending Case</th><th style='color:white'><a onclick=caseinfo('offline-caseassigned')>"+(casemanagerconunt[2][0]+casemanagerconunt[2][1])+"</a></th></tr><tr style='background-color:#F781D8'><th> Not Attending Case</th><th><a onclick=caseinfo('offline-casenotassigned')>"+(casemanagerconunt[2][2])+"</a></th></tr><tr><td style='color:black'>Total :</td><td style='color:black'>"+totalvehicle+"</td></tr></table>";
            } else if (Account=="gvk-up-181"){
          var newlook="<table style='font-weight: bold;font-size: 15px;'><tr style='background-color:#e8d4d0'><th colspan='2' align='center'><b>Cases</b></th></tr><tr style='background-color:#8e44ad'><th style='color:white'>Towards Scene</th><th style='color:white'><a onclick=caseinfo('towardsscene')>"+(casemanagerconunt[0][0]+casemanagerconunt[1][0])+"</a></th></tr><tr style='background-color: #3498db'><th style='color:white'>At Scene</th><th style='color:white'><a onclick=caseinfo('atscene')>"+(casemanagerconunt[0][1]+casemanagerconunt[1][1])+"</a></th></tr><tr style='background-color: #dc7633'><th style='color:white'>Started From Scene</th><th style='color:white'><a onclick=caseinfo('towardshospital')>"+(casemanagerconunt[0][3]+casemanagerconunt[1][3]+casemanagerconunt[2][3])+"</th></tr><tr style='background-color:#e8d4d0'><th colspan='2' align='center'><b>Ready To Take Case</b></th></tr><tr style='background-color:#5FB404'><th>Running</th><th><a onclick=caseinfo('readytotakecase-online')>"+(casemanagerconunt[0][2])+"</a></th></tr><tr style='background-color:#D7DF01'><th>Idle</th><th><a onclick=caseinfo('readytotakecase-idle')>"+(casemanagerconunt[1][2])+"</a></th></tr><tr style='background-color: #e8d4d0 '><th colspan='2' align='center'><b>Offline</b></th></tr><tr style='background-color:#DF0101'><th style='color:white'>Attending Case</th><th style='color:white'><a onclick=caseinfo('offline-caseassigned')>"+(casemanagerconunt[2][0]+casemanagerconunt[2][1])+"</a></th></tr><tr style='background-color:#F781D8'><th> Not Attending Case</th><th><a onclick=caseinfo('offline-casenotassigned')>"+(casemanagerconunt[2][2])+"</a></th></tr><tr><td style='color:black'>Total :</td><td style='color:black'>"+totalvehicle+"</td></tr></table>";
            } else {
         var newlook="<table style='font-weight: bold;font-size: 15px;'><tr style='background-color:#e8d4d0'><th colspan='2' align='center'><b>Stats</b></th></tr><tr style='background-color:#5FB404'><th>Running</th><th><a onclick=caseinfo('readytotakecase-online')>"+(casemanagerconunt[0][2])+"</a></th></tr><tr style='background-color:#D7DF01'><th>Idle</th><th><a onclick=caseinfo('readytotakecase-idle')>"+(casemanagerconunt[1][2])+"</a></th></tr><tr style='background-color:#DF0101'><th style='color:white'>Offline vehicle</th><th style='color:white'><a onclick=caseinfo('offline-casenotassigned')>"+(casemanagerconunt[2][2])+"</a></th></tr><tr><td style='color:black'>Total :</td><td style='color:black'>"+totalvehicle+"</td></tr></table>";
      //     var newlook="<table style='font-weight: bold;font-size: 15px;'><tr style='background-color:#e8d4d0'><th colspan='2' align='center'><b>Cases</b></th></tr><tr style='background-color:#8e44ad'><th style='color:white'>Towards Scene</th><th style='color:white'><a onclick=caseinfo('towardsscene')>"+(casemanagerconunt[0][0]+casemanagerconunt[1][0])+"</a></th></tr><tr style='background-color: #3498db'><th style='color:white'>At Scene</th><th style='color:white'><a onclick=caseinfo('atscene')>"+(casemanagerconunt[0][1]+casemanagerconunt[1][1])+"</a></th></tr><tr style='background-color: #dc7633'><th style='color:white'>Started From Scene</th><th style='color:white'><a onclick=caseinfo('towardshospital')>"+(casemanagerconunt[0][3]+casemanagerconunt[1][3]+casemanagerconunt[2][3])+"</th></tr><tr style='background-color:#e8d4d0'><th colspan='2' align='center'><b>Ready To Take Case</b></th></tr><tr style='background-color:#5FB404'><th>Running</th><th><a onclick=caseinfo('readytotakecase-online')>"+(casemanagerconunt[0][2])+"</a></th></tr><tr style='background-color:#D7DF01'><th>Idle</th><th><a onclick=caseinfo('readytotakecase-idle')>"+(casemanagerconunt[1][2])+"</a></th></tr><tr style='background-color: #e8d4d0 '><th colspan='2' align='center'><b>Offline</b></th></tr><tr style='background-color:#DF0101'><th style='color:white'>Attending Case</th><th style='color:white'><a onclick=caseinfo('offline-caseassigned')>"+(casemanagerconunt[2][0]+casemanagerconunt[2][1])+"</a></th></tr><tr style='background-color:#F781D8'><th> Not Attending Case</th><th><a onclick=caseinfo('offline-casenotassigned')>"+(casemanagerconunt[2][2])+"</a></th></tr><tr><td style='color:black'>Total :</td><td style='color:black'>"+totalvehicle+"</td></tr></table>";

            }

            /*var flagTable = "<table>";
	         flagTable +=  "<tr><td>Total Vehicle :<td></td> "+totalvehicle+"</td></tr>";
	         flagTable +=  "<tr><td>Online Vehicle :<td></td> "+onlineVehilce+"</td></tr>";
	         flagTable +=  "<tr><td></td><td>Running : "+countInMotion+"  <img src='../images/ambulance/RunningE.png'  width='40'  height='30'></td></tr>";
	    	 flagTable += "<tr><td></td><td>Idle: "+countIdle+" <img src='../../../images/ambulance/IdleE.png'  width='40' height='30'></td></tr>";
	    	 
	    	 flagTable += "<tr><td></td><td>Stopped : "+countStopped+" <img src='../images/ambulance/stoppedE.png' width='40' height='30'></td></tr>";
	    	 flagTable += "<tr><td colspan='2'>Offline : "+countRepair+" <img src='../images/ambulance/offlineE.png' width='40' height='30'></td></tr>";
	    	 */
          //  casemanagementupdates(totalvehicle);
      //      document.getElementById("casemanagement").innerHTML=  '<table style="width:100%;height:100%"><tr><td> <div style="font-size: 13px;position: absolute; background-color: transparent;  z-index: 99; background: #0A2A0A ;border-radius:10px;opacity:0.8;width:200px;height:50px;color:white"><center><font style="font-size:25;">'+totalvehicle+'</font><br> Attending Case</center></div><br> </td></tr><tr><td>  <div style="font-size: 13px;position: absolute; background-color: transparent; z-index: 99; background: #FAAC58;border-radius:10px;opacity:0.8;width:200px;height:50px;color:white"><center><font style="font-size:25;">'+totalvehicle+'</font><br> Scene/Hospital</center></div><br></td></tr> <tr><td> <div style="font-size: 13px;position: absolute; background-color: transparent; z-index: 99; background: #8A0808 ;border-radius:10px;opacity:0.8;width:200px;height:50px;color:white"><center><font style="font-size:25;">'+totalvehicle+'</font><br> Ready to take case</center></div><br></td></tr>         <tr><td>  <div id="installeddevices" style=" bold;font-size: 13px;position: absolute; background-color: transparent; z-index: 99; background:#01DFD7 ;border-radius:10px;opacity:0.8;width:200px;height:50px;color:white"><center><font style="font-size:25;">'+totalvehicle+'</font><br> Total GPS Installed</center></div><br></td></tr>';
	     var flagTable = "<table style='background: #85929e ;font-weight: bold;font-size: 15px; '>";
         flagTable +=  "<tr style='background:#42B8DD' ><td style='color:white; font-weight: bold;font-size: 15px;'>Total Vehicles : <font style='font-size: 20px;'>"+totalvehicle+" </font>&nbsp";
         flagTable +=(userID=="tracking")?"</td></tr>":"<a onclick='statusReportDownload();'><img src='images/arrows.png' width='16' height='16' /></a></td></tr>";
         flagTable +=  "<tr style='background:#df7514'><td style='color:white;font-weight: bold;font-size: 15px;'>Online Vehicles : <font style='font-size: 20px;'>"+onlineVehilce+"</font>  &nbsp";
         flagTable +=(userID=="tracking")?"</td></tr>":"<a onclick='getOnline();'><img src='images/arrows.png' width='16' height='16' /></a></td></tr>";
         flagTable +=  "<tr style='background:#1cb841'><td style='color:white;font-weight: bold;font-size: 15px;'>&nbsp&nbsp&nbsp&nbsp&nbsp&nbspRunning :<font style='font-size: 20px;color:white'> "+countInMotion+"</font> &nbsp ";
         flagTable +=(userID=="tracking")?"</td></tr>":"<a onclick='getRunning();'><img src='images/arrows.png' width='16' height='16' /></a></td></tr>";
         
         document.getElementById("online").value=''+countInMotion+'';
         document.getElementById("idle").value=''+(countIdle+countStopped)+'';
          if(userID!="monitoring" || userID=="tracking"){
             // alert(userID);
             if(Account=="gvkrajasthan"){
                     document.getElementById("offline1").value=''+(countRepair)+'';

              }else{
                       document.getElementById("offline1").value=''+(countRepair)+'';
              }
         }         
              $('.demo-1').percentcircle({fillColor:'#77e58f'},countInMotion,parseInt((100*countInMotion/totalvehicle)));
              //  document.getElementById('demo-2').innerHTML='';
             $('.demo-2').percentcircle({fillColor:'yellow'},countIdle+countStopped,parseInt(((countIdle+countStopped)*100/totalvehicle)));
             // document.getElementById('demo-3').innerHTML='';
             $('.demo-3').percentcircle({fillColor:'red'},countRepair,parseInt((countRepair*100/totalvehicle)));
             $('.demo-5').percentcircle({fillColor:'#FE9A2E'},offroadcount,parseInt((offroadcount*100/totalvehicle)));

             $('.demo-4').percentcircle({fillColor: ' #ca9ad6 '},totalvehicle,'100');
              var arraySorted = [];
         
             if((countInMotion+countIdle+countStopped+offroadcount+countRepair)==0){
                  location.reload(); 
            }

        //assosiative = assosiative.sortAssoc();                                              
             for  (var key in assosiative) {
                      arraySorted.push(key);
               }
                    arraySorted.sort();       
               var cnt=1;
                for(var x=0;x<arraySorted.length;x++){
                       var key=arraySorted[x]; 
          

                    summarytable=summarytable+"<tr style='background-color:#d6dbdf' class='gradient_bgcolor'><th onclick=expandCollapse('"+key+"','"+key+"1',0);  style='color:black'  id='"+key+"1'><a><img src='images/plus.png' width='16' height='16' /></a> </th><th style='color:black;text-align:left;width:300px'>"+assosiative[key][0].toUpperCase()+"</th><th style='background-color:rgb(116, 173, 116) ;color:white;font-weight: bold;font-size:15px' class='gradient_green'><a onclick=expandCollapse('"+key+"','"+key+"1',1);online(); >"+(assosiative[key][2])+"</th><th style='background-color:rgb(207, 210, 133);color:white;font-weight: bold;font-size:15px' class='gradient_yellow'><a onclick=expandCollapse('"+key+"','"+key+"1',1);idle(); >"+(assosiative[key][3])+"</a></th><th style='background-color:rgb(222, 110, 110);color:white;font-weight: bold;font-size:15px' class='gradient_red'><a onclick= expandCollapse('"+key+"','"+key+"1',1);offline(); >"+(assosiative[key][4])+"</a></th><th style='color:black;font-weight: bold;font-size:15px' class='gradient_blue'> <a onclick=expandCollapse('"+key+"','"+key+"1',1);offroad();>"+(assosiative[key][20])+" </a></th> <th style='color:red;font-weight: bold;font-size:15px'> <a onclick=expandCollapse('"+key+"','"+key+"1',1);allvehiclesInfo();>"+(assosiative[key][1])+" </a></th><th style='color:black;font-weight: bold;font-size:15px' id='"+key+"11'><a><img src='images/mapicon.png' onclick=expandCollapse('"+key+"','"+key+"1');bothview();availabilityVehicles();extra('"+key+"') height='25px' width='25px'  /></a></th> </tr>"
 
//                    summarytable=summarytable+"<tr style='background-color:#d6dbdf'><th onclick=expandCollapse('"+key+"','"+key+"1');availabilityVehicles()  style='color:black'  id='"+key+"1'><img src='images/plus.png' width='16' height='16' /> </th><th style='color:black'>"+assosiative[key][0].toUpperCase()+"</th><th style='background-color:rgba(167, 59, 213, 0.49);color:white'><a >"+(assosiative[key][6]+assosiative[key][10])+"</a></th><th style='background-color:rgba(85, 162, 213, 0.79);color:white'><a >"+(assosiative[key][7]+assosiative[key][11])+"</a></th><th style='background-color:rgb(222, 163, 123) ;color:white'><a onclick=>"+(assosiative[key][9]+assosiative[key][13]+assosiative[key][17])+"</th><th style='background-color:rgb(116, 173, 116);color:white'><a onclick=>"+(assosiative[key][8])+"</a></th><th style='background-color:rgb(207, 210, 133);color:black'><a onclick=>"+(assosiative[key][12])+"</a></th><th style='background-color:rgb(222, 110, 110);color:white'><a >"+(assosiative[key][14]+assosiative[key][15])+"</a></th><th style='background-color:rgba(239, 136, 211, 0.8)'><a >"+(assosiative[key][16])+"</a></th><td style='color:black'>"+(assosiative[key][1])+"</td></tr>"
                  //  summarytable=summarytable+"<tr><td onclick=expandCollapse('"+key+"','"+key+"1');availabilityVehicles() id='"+key+"1'><img src='images/plus.png' width='16' height='16' /></td> <td style='background-color:"+ocolor+";color:black'>"+assosiative[key][0].toUpperCase()+"</td><td >"+assosiative[key][1]+"</td><td style='background-color:green;color:white'>"+assosiative[key][2]+"</td><td style='background-color:yellow;color:black'>"+assosiative[key][3]+"</td><td style='background-color:#bb451c;color:white'>"+assosiative[key][4]+"</td></tr>";
                  //
                   groupwisetable="<div class='hello' style='background:#B20F14;border-radius: 0px;background: #B20F14'><table  class='responstable' border='1' align='center' style='border-radius: 0px;font-family: 'Trebuchet MS', Verdana, Arial, Helvetica, sans-serif;' id='search"+x+"' class='ok'><thead style='font-weight:bold;color:white'><th align='center'>Status</th><th align='center'>Group Name</th><th align='center'>BaseLocation</th><th align='center'>Vehicle Number/<br> Running Case Status</th><th align='center'>Driver Phone Number</th><th align='center'>Current Location</th><th align='center'>Last Seen</th><th align='center'>Speed(Km/h)</th><th align='center'>Idle Time (HH:MM:SS)</th><th align='center'>History</th></tr></thead><tbody>";
                        var Account=window.localStorage.getItem('accountID');
                  var dislist=groupwisetable+assosiative[key][5]+assosiative[key][18]+assosiative[key][19]+assosiative[key][21];
                if(showmap=="online"){ dislist=groupwisetable+assosiative[key][5];}
                 if(showmap=="offline"){ dislist=groupwisetable+assosiative[key][19]}
               if(showmap=='idle'){ dislist=groupwisetable+assosiative[key][18];}
                     
               if(showmap=='offroad'){ dislist=groupwisetable+assosiative[key][21];}
               if(showmap=='single' && showmap==caselist){
                     dislist=groupwisetable+assosiative[key][5]+assosiative[key][18]+assosiative[key][19]+assosiative[key][21];
                }
           
           summarytable=summarytable+"<tr><td colspan='8' > <div  id='"+key+"' style='display: none; >"+dislist+"</tbody></table><div></div></td></tr>";



                
           }
           summarytable=summarytable+"</tbody></table><div>";
          

 
        document.getElementById("selectingGroupID").innerHTML=(selectedGroup=="selectall")?"All ":selectedGroup; 
         document.getElementById("reset").innerHTML=totalvehicle+'';
         var Account=window.localStorage.getItem('accountID');
         if(Account.indexOf("gvk")>-1  || Account.indexOf("gog")>-1 ||  Account.indexOf("khil")>-1 ||  Account.indexOf("als")>-1 || Account!="" ){
        	 flagTable += "<tr style='background:yellow'><td style='color:black;font-weight: bold;font-size: 15px;'>&nbsp&nbsp&nbsp&nbsp&nbsp&nbspIdle:<font style='font-size: 20px; color:black'>"+(countIdle+countStopped)+"</font> &nbsp";
                 flagTable +=(userID=="tracking")?"</td></tr>":"<a onclick='getIdle();'><img src='images/arrows.png' width='16' height='16' /></a></td></tr>";
        	 //flagTable += "<tr><td></td><td>Stopped :<font color='red'> "+countStopped+"</font></td></tr>";
        	 
        }else{
    	    flagTable += "<tr style='background:#ca3c60'><td>Idle: <font color='white'>"+countIdle+"</font> </td></tr>";
    	    flagTable += "<tr style='background:#ca3c60'><td>Stopped :<font color='white'> "+countStopped+"</font></td></tr>";
         }
    	 //flagTable += "<tr><td>&nbsp&nbspVehicles Not Communicating<br> for more than 24 hours&nbsp&nbsp&nbsp&nbsp&nbsp : </td><td>"+countRepair+"</td></tr>";
           if(userID!="monitoring" || userID!="tracking"){
    	 


       //     $specificword=(Account=="gvkrajasthan"?"Offline":"Off-Road");
             flagTable += "<tr style='background:red'><td style='color:white;font-weight: bold;font-size: 15px;'>"+(Account=="gvkrajasthan"?"Offline":"Offline")+" Vehicles :<font style='font-size: 20px;'> "+(countRepair)+" </font> &nbsp";
             flagTable +=(userID=="tracking")?"</td></tr>":"<a onclick='getOfflineDownload();'><img src='images/arrows.png' width='16' height='16' /></a> </td></tr>";
           if(userID=="tracking"){
                 flagTable +="<tr style='background: #85929e '><td style='color:white;font-weight: bold;font-size: 15px;'>&nbsp&nbsp&nbsp&nbsp&nbsp&nbspNot Tracking: <font style='font-size: 20px;'>"+offline_nottrackingcount+"</font></td></tr><tr style='background: #2c3e50 '><td style='color:white;font-weight: bold;font-size: 15px;'>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Not Responding:<font style='font-size: 20px;'>"+(offline_notrespondingcount+offroadcount)+"</font></td></tr><tr style='background:#eaeded'><td></td></tr>";
 

           }else{  
              flagTable +="<tr style='background: #85929e '><td style='color:white;font-weight: bold;font-size: 15px;'> &nbsp&nbsp&nbsp&nbsp&nbsp&nbspNot Tracking:<font style='font-size: 20px;'>"+offline_nottrackingcount+"</font> </a></td></tr><tr style='background: #2c3e50 '><td style='color:white;font-weight: bold;font-size: 15px;'>&nbsp&nbsp&nbsp&nbsp&nbsp&nbspNot Responding:<font style='font-size: 20px;'>"+(offline_notrespondingcount+offroadcount)+"</font></td></tr>";
          // flagTable +="<tr style='background: #85929e '><td style='color:white;font-weight: bold;font-size: 15px;'> &nbsp&nbsp&nbsp&nbsp&nbsp&nbspNot Tracking:<font style='font-size: 20px;'>"+offline_nottrackingcount+"</font> <a onclick='getOfflineNottrackingDownload();'><img src='images/arrows.png' width='16' height='16' /></a></td></tr><tr style='background: #2c3e50 '><td style='color:white;font-weight: bold;font-size: 15px;'>&nbsp&nbsp&nbsp&nbsp&nbsp&nbspNot Responding:<font style='font-size: 20px;'>"+offline_notrespondingcount+"</font><a onclick='getOfflineNotRespondingDownload();'><img src='images/arrows.png' width='16' height='16' /></td></tr><tr style='background:#eaeded'><td style='color:black;font-weight: bold;font-size: 15px;'>Time Range "+(Account=="gvkrajasthan"?"Offline":"Offline")+":<a onclick='getOfflineDaterange();'><img src='images/arrows.png' width='16' height='16' /></a></td></tr>";
          }
    	}
	    	// flagTable += "<td>A/c On : "+acOnCount+" <img src='../images/ac.png' width='20' height='20'></td>";
	    	//flagTable += "<td>Repair: "+countStopped+" <img src='../../../images/off.png'  width='30' height='30'></td></tr></table>";

	    var flags = document.getElementById("flags");
	   // flags.innerHTML = flagTable;
	   // flags.innerHTML=caseandstatustable;
           flags.innerHTML=newlook;
	   /* var flags = document.getElementById("msgcontent");
	   
	    while (flags.hasChildNodes()) {
	    	flags.removeChild(flags.firstChild);
	    }
	    flags.innerHTML = msgperDay;*/
//	    document.getElementById("allVehicls").innerHTML= allvehiclesshow;
//	   document.getElementById("runningVehilces").innerHTML= runningVehilces;
//	    document.getElementById("idleVehilces").innerHTML= idleVehilces;
            if(userID!="monitoring" || userID!="tracking"){
  //              document.getElementById("offlineVehilces").innerHTML= offlineVehilces;
            }
	    var elem = document.getElementById("vehicleStatusReport");
	    elem.innerHTML = summarytable;//table;
           // elem.scrollTop = 1;
	    $('.vehicleStatusReport').show();  
	    $('.load_image1').hide();
       
           if(collapsegroup!="all"){
                       expandCollapse(collapsegroup,collapsegroup+'1',1)
            }  
		//window.localStorage.setItem('vehicleStatus',table);
		//window.open('availabilityVehicles.html', 'Vehicle Status', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	}
function routeTracking(vehicleID){
       var w=1024;
       var h=400;
       var Account=window.localStorage.getItem('accountID');
          var left = (screen.width/2)-(w/2);
         var topp = (screen.height/2)-(h/2);
     window.open ("php/tripMap.php?accountID="+Account+"&vehicleID="+vehicleID+"&onlyidles=no","Route Tracking",'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+topp+', left='+left);

}


 

 function expandCollapse(showHide,ex1,expand) {
       collapsegroup=showHide; 
        var hideShowDiv = document.getElementById(showHide);
        var label = document.getElementById(ex1);
          // alert(hideShowDiv.style.display);

if(expand==2){

  label.innerHTML = label.innerHTML.replace("plus", "minus");
            hideShowDiv.style.display = 'block';
            document.getElementById(showHide+"11").innerHTML="<img src='images/mapicon.png' height='25px' width='25px'>"

}
if(expand==3){

   label.innerHTML = label.innerHTML.replace("minus", "plus");
            hideShowDiv.style.display = 'none';
 document.getElementById(showHide+"11").innerHTML="<img src='images/mapicon.png' height='25px' width='25px' onclick=expandCollapse('"+showHide+"','"+showHide+"1');availabilityVehicles();extra('"+showHide+"');bothview();>"
}
 
if(expand==0 ||(expand==1 && hideShowDiv.style.display == 'none')){
        if (hideShowDiv.style.display == 'none') {
            label.innerHTML = label.innerHTML.replace("plus", "minus");
            hideShowDiv.style.display = 'block'; 
            document.getElementById(showHide+"11").innerHTML="<img src='images/mapicon.png' height='25px' width='25px'>"           
        } else {
              textview();
                document.getElementById('groupdevice').value='selectall'; showorhidemarkers='hide';
                  window.localStorage.setItem('showmap','all'); 
               // maploadcount=0;
             //  allVehicleStatus();
            label.innerHTML = label.innerHTML.replace("minus", "plus");
            hideShowDiv.style.display = 'none';
              collapsegroup="all";
          document.getElementById(showHide+"11").innerHTML="<img src='images/mapicon.png' height='25px' width='25px' onclick=expandCollapse('"+showHide+"','"+showHide+"1');availabilityVehicles();extra('"+showHide+"');bothview();>"
        }
 }

    }
function extra(key){
   document.getElementById('groupdevice').value=key; 
document.getElementById('ad2').value=key;
document.getElementById('svehicle').value="";
document.getElementById('ad').value="";
       window.localStorage.setItem('showmap',"all");
callfromlabel();

}
function liveTracking(vehicleID){
       var w=900;
       var h=400;
       var Account=window.localStorage.getItem('accountID');
          var left = (screen.width/2)-(w/2);
         var topp = (screen.height/2)-(h/2);
     window.open ("../UP/casemanagerdashboard/php/liveTracking.php?live=yes&accountID="+Account+"&vehicleID="+vehicleID+"&onlyidles=no","Route Tracking",'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+topp+', left='+left);

}

function casemanagementupdates(){
     // alert();
     var Account=window.localStorage.getItem('accountID');
     var userID= window.localStorage.getItem('userId');
     $.ajax({
                        type : 'get',
                        url:'../UP/casemanagerdashboard/php/casemanagementupdate.php',
                        data:{"accountID":Account,"userID":userID},
                        dataType:'json',
                         success: function(data) {
                             $.each(data, function() {
                                  $.each(data, function(key, value) {
                             if(key=="markers"){
                                  var totalvehicle=value[0];
                                  var attendcase=value[1];
                                  var inhospital=value[2];
                                  var readytotake=value[3];
                                  
                                 document.getElementById("casemanagement").innerHTML=  '<table style="width:100%;height:100%"><tr><td> <div style="font-size: 13px;position: absolute; background-color: transparent;  z-index: 99; background: #0A2A0A ;border-radius:10px;opacity:0.8;width:100%;height:50px;color:white"><center><font style="font-size:25;">'+attendcase+'</font><br> Attending Case</center></div><br> </td></tr><tr><td>  <div style="font-size: 13px;position: absolute; background-color: transparent; z-index: 99; background: #eaa612 ;border-radius:10px;opacity:0.8;width:100%;height:50px;color:white"><center><font style="font-size:25;">'+inhospital+'</font><br>At Scene</center></div><br></td></tr> <tr><td> <div style="font-size: 13px;position: absolute; background-color: transparent; z-index: 99; background: #8A0808 ;border-radius:10px;opacity:0.8;width:100%;height:50px;color:white"><center><font style="font-size:25;">'+readytotake+'</font><br> Ready to take case</center></div><br></td></tr>         <tr><td>  <div id="installeddevices" style=" bold;font-size: 13px;position: absolute; background-color: transparent; z-index: 99; background: #20b2b2 ;border-radius:10px;opacity:0.8;width:100%;height:50px;color:white"><center><font style="font-size:25;">'+totalvehicle+'</font><br> Total GPS Installed</center></div><br></td></tr>';
                                  }else if(key=="casemanagervehicles"){
                                          contactambulance=[];
                                           var table="<table id='contact'><thead></thead><tbody>";
                                           for(var i=0;i<value.length;i++){
                                                var x=value[i].split('^');
                                               contactambulance[i]=[];
                                              contactambulance[i][0]=x[0];
                                              contactambulance[i][1]=x[1];

                                              contactambulance[i][2]=x[2];
                                              contactambulance[i][3]=x[3];
                                              contactambulance[i][4]=x[4];

                                              contactambulance[i][5]=x[5];
                                              contactambulance[i][6]=x[6];
                                            }
                                         allVehicleStatus();

                                     }



                                  });
                             });
                         }
                     });

}
function searchcontactambulance(){
       var searchambulance='';
        var searchphonenumber= document.getElementById("contactphone").value;
       for(var i=0;i<contactambulance.length;i++){
                if(searchphonenumber==contactambulance[i][1]){
                    searchambulance=contactambulance[i][0];
                    break;
                 }
      }

     if(searchambulance!=''){
           var flag='false';
            for(var vehicleIndx=0;vehicleIndx<vehicleAvailable.length;vehicleIndx++){
                  if(vehicleAvailable[vehicleIndx][0].toLowerCase()==searchambulance.toLowerCase()){
                          flag="true";
                        vehicleRoute(vehicleIndx,1);
                        break;
                  }

           }
          if(flag=="false"){
                  alert("No Vehicle matched");
          }

      }else{
         alert("Not found");

      }

}
 
/*

function casemanagementupdates(){
        var Account=window.localStorage.getItem('accountID');
    var userID= window.localStorage.getItem('userId');
            /// alert(); 
        //  document.getElementById("casemanagement").innerHTML=  '<table style="width:100%;height:100%"><tr><td> <div style="font-size: 13px;position: absolute; background-color: transparent;  z-index: 99; background: #0A2A0A ;border-radius:10px;opacity:0.8;width:100%;height:50px;color:white"><center><font style="font-size:25;">'+totalvehicle+'</font><br> Attending Case</center></div><br> </td></tr><tr><td>  <div style="font-size: 13px;position: absolute; background-color: transparent; z-index: 99; background: #FAAC58;border-radius:10px;opacity:0.8;width:100%;height:50px;color:white"><center><font style="font-size:25;">'+totalvehicle+'</font><br> Scene/Hospital</center></div><br></td></tr> <tr><td> <div style="font-size: 13px;position: absolute; background-color: transparent; z-index: 99; background: #8A0808 ;border-radius:10px;opacity:0.8;width:100%;height:50px;color:white"><center><font style="font-size:25;">'+totalvehicle+'</font><br> Ready to take case</center></div><br></td></tr>         <tr><td>  <div id="installeddevices" style=" bold;font-size: 13px;position: absolute; background-color: transparent; z-index: 99; background:#01DFD7 ;border-radius:10px;opacity:0.8;width:100%;height:50px;color:white"><center><font style="font-size:25;">'+totalvehicle+'</font><br> Total GPS Installed</center></div><br></td></tr>';
       $.ajax({
                        type : 'get',
                        url:'../UP/casemanagerdashboard/php/casemanagementupdate.php',
                        data:{"accountID":Account,"userID":userID},
                        dataType:'json',
                         success: function(data) {
                             $.each(data, function() {
                                  $.each(data, function(key, value) { 
                             if(key=="markers"){
                                  var totalvehicle=value[0];
                                  var attendcase=value[1];
                                  var inhospital=value[2];
                                  var readytotake=value[3];
                                  var tohospital=value[4];     
                               
                                 document.getElementById("casemanagement").innerHTML=  '<table style="width:100%;height:100%"><tr><td> <div style="font-size: 13px;position: absolute; background-color: transparent;  z-index: 99; background: #0A2A0A ;border-radius:10px;opacity:0.8;width:100%;height:50px;color:white"><center><font style="font-size:25;">'+attendcase+'</font><br> Attending Case</center></div><br> </td></tr><tr><td>  <div style="font-size: 13px;position: absolute; background-color: transparent; z-index: 99; background: #eaa612 ;border-radius:10px;opacity:0.8;width:100%;height:50px;color:white"><center><font style="font-size:25;">'+inhospital+'</font><br> Scene/Hospital</center></div><br></td></tr> <tr><td> <div style="font-size: 13px;position: absolute; background-color: transparent; z-index: 99; background: #8A0808 ;border-radius:10px;opacity:0.8;width:100%;height:50px;color:white"><center><font style="font-size:25;">'+readytotake+'</font><br> Ready to take case</center></div><br></td></tr>         <tr><td>  <div id="installeddevices" style=" bold;font-size: 13px;position: absolute; background-color: transparent; z-index: 99; background: #20b2b2 ;border-radius:10px;opacity:0.8;width:100%;height:50px;color:white"><center><font style="font-size:25;">'+totalvehicle+'</font><br> Total GPS Installed</center></div><br></td></tr>';
                                  }else if(key=="casemanagervehicles"){
                                     //   alert(value.length);
                                     
                                           var table="<table id='contact'><thead></thead><tbody>";
                                           for(var i=0;i<value.length;i++){
                                                var x=value[i].split('^');
                                                if(x[2]=="assigned"){
                                                     table=table+"<tr><td><input type='button' value='"+x[0]+"' style='background:#0A2A0A;color:white'  onClick='liveTracking(\""+x[0]+"\")'/></td><td>"+x[1]+"</td><td>Attending Case</td></tr>";
                                                  }else{

                                                          table=table+"<tr><td><input type='button' value='"+x[0]+"' style='background:#eaa612;color:white'  onClick='liveTracking(\""+x[0]+"\")'/></td><td>"+x[1]+"</td><td>Scene/Hospital</td></tr>";
                                                   }
                                            }
                                            table=table+"</tbody></table></div>";
                                               document.getElementById("contactsearch1").innerHTML=table;
                                     }



                                  });
                             });
                         }
                     });

}*/
	
	
	
	
function getBase64Image(image, angle) {
    var offscreenCanvas = document.createElement('canvas');
    var offscreenCtx = offscreenCanvas.getContext('2d');

    var size = Math.max(image.width, image.height);
    offscreenCanvas.width = size;
    offscreenCanvas.height = size;

    offscreenCtx.translate(size / 2, size / 2);
    offscreenCtx.rotate(angle * Math.PI / 180);
    offscreenCtx.drawImage(image, -(image.width / 2), -(image.height / 2));

    var dataURL = offscreenCanvas.toDataURL("image/png");
    return dataURL;
}
	
