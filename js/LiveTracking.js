


function vehicle()
{ 
	
    var Account=window.localStorage.getItem('accountID');
    var userID= window.localStorage.getItem('userId');
	//var VehicleID= document.getElementById('vehicleID').value;
	var globalVehicles=[];//vahiles for vehiclesStatus
    var v = new Date();
    //document.getElementById('fromDate').value=dateToStringFormat(v);
   // document.getElementById('toDate').value=dateToStringFormat(v);
   $.ajax({
	   type: 'get',
	   url: 'php/fuelVehicles.php',
	   data: {'accountID':Account,'userID':userID},
	   dataType: 'json',
	   success: function(data)
	   {
		   $.each(data,function(key,value)
		   {
			
               for(var i=0;i<value.length;i++)
               {
            	   
            		globalVehicles[i]=value[i][0];
            		
               }
               
             
               window.localStorage.setItem('globalVehicles',globalVehicles);
		   });
	   }

   });
}


 
function validuser()
{
    //window.localStorage.setItem('accountID',"kkkk");    
    var Account=window.localStorage.getItem('accountID');
   
    if (Account == '' || Account == undefined) {
         alert('Invalid User Login Again');
         window.location="../../../index.html";
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

}
function sessionTimeOut()
{
    window.localStorage.setItem('accountID',"");
    alert('Session Timed Out plz login Again');
    window.location="../../../index.html";
} 
// Copyright 2013-2014 Glovision Techno Services Pvt Ltd

// Specific Form based methods

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
	  if(acc.indexOf("gvk") == -1){
	     window.localStorage.setItem('showmap','all');
	  }
	  window.localStorage.setItem('packagingidentify',value);
	  window.localStorage.setItem('vehicleType',"ambulance");
	
  }
  
value=value.split(",");


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
	


	 
	

	/////////////////status///////////////////////////////////////////
	

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
		var http = new XMLHttpRequest();
	    http.open('HEAD', url, false);
	    http.send();
	    return http.status!=404;
	}
	
	function allVehicleStatus() {
		
		window.localStorage.setItem('showmap',"all");
		
		if(vehicleWithEventData.length>0){
		  
	      var vehicleAvailableIndex=0;
             //  alert(vehicleWithEventData.length);
             var x=0;
             var y=0;
	      for(var i=0;i<vehicleWithEventData.length;i++){
	    	
	    	var lastEventOfVehicle=vehicleWithEventData[i].length-1;
	    	var mapOptions ;
	    	
	    	if(vehicleWithEventData[i].length>0){
                        x++;
	    		var lastEvntDate=stringFormatToDate(stringFormatToDateForFuel(vehicleWithEventData[i][lastEventOfVehicle][4]+""));
	    		var epoc1=dateToEpochDB(lastEvntDate);
	    		var currentTime = new Date();
	    	    var epoc2=dateToEpochDB(currentTime);
	          // if(parseInt((epoc2-epoc1)/(60*60))<24){
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
	          /* } else{
	     	    	// alert('time out'+secondsToTimeFormate(epoc2-epoc1));
	     	    	 vehicleAvailable[vehicleAvailableIndex]=[];
	     	         vehicleAvailable[vehicleAvailableIndex][0]=globalVehicles[i];
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
	       	}else{
	       		y++;
	       		vehicleAvailable[vehicleAvailableIndex]=[];
	            vehicleAvailable[vehicleAvailableIndex][0]=globalVehicles[i];
	            vehicleAvailable[vehicleAvailableIndex][1]="0000";
	            vehicleAvailable[vehicleAvailableIndex][2]="0";
	            vehicleAvailable[vehicleAvailableIndex][3]="No Location";
	            vehicleAvailable[vehicleAvailableIndex][4]="Vehicle Repair";
	            vehicleAvailable[vehicleAvailableIndex][5]="";
   	            vehicleAvailable[vehicleAvailableIndex][6]="";
   	            vehicleAvailable[vehicleAvailableIndex][7]="";
   	            vehicleAvailable[vehicleAvailableIndex][8]="";
   	           vehicleAvailable[vehicleAvailableIndex][9]="";
	       	}
	    	vehicleAvailableIndex++;
	    }//end loop
	   
        // alert(x+"   "+y);
	    //alert(vehicleAvailable.length+ 'length');
	    availabilityVehicles();

	   }//end if   
	}
	
	
	
	
	
	
	function allVehicleStatusWithRoute(){
		allVehicleStatus();
		allVehicleRoute(map);
	}
	var id;
	
	
	
	function clearTimeOut(){
		//setTimeout(id);
		clearTimeout(id);
		//alert("completed");

	}
	

	var msgperDay='';

	
	function availabilityVehicles(){
		var w=800;
		var h=600;

		var left=screen.width/2-w/2;
		var top=screen.height/2-h/2;
		var search='search';
		//<thead><tr><th>Vehicle Id</th><th>OdoMeter Reading</th><th>Location </th><th>Status</th><th>Replay</th></tr></thead>
		//var table="<div class='hello' style='width:460;border:solid 2px orange;overflow-y:scroll;background: white;'><table border='1' align='center' id='search'><tbody>";

		//if(vehicleAvailable.length>16){
			var table="<div class='hello' style='height:530;width:460;border:solid 2px orange;background: white;overflow: scroll;overflow-y:scroll;'><table border='1' align='center' id='search'><tbody>";
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
	   var acOnCount=0;
	   //alert(vehicleAvailable.length);
	   var running='';
	   var idle='';
	   var stopped='';
	   var offline='';
	   var uri = window.location.toString();
	  	    var url='';
	        if (uri.indexOf("?") > 0) {
	           var port='';
	           if(window.location.port!=''){port=":"+window.location.port;}
	  	       url = window.location.origin+port;
	          // url='http://track.glovision.co:8080';
	  	    }  
		for(var vehicleIndx=0;vehicleIndx<vehicleAvailable.length;vehicleIndx++){
			
			 var vehiclesType=vehicleAvailable[vehicleIndx][7];
			
			 var discription='';
			 var Account=window.localStorage.getItem('accountID');
		
			 if(vehiclesType==''){vehiclesType='default'; }
			if(vehicleAvailable[vehicleIndx][1]=="61714"){
			  countInMotion++;
			}else if(vehicleAvailable[vehicleIndx][1]=="61718" || vehicleAvailable[vehicleIndx][1]=="61720"){
			  countIdle++; 
	     	   	
			}else if(vehicleAvailable[vehicleIndx][1]=="61717" || vehicleAvailable[vehicleIndx][1]=="61477"){
			  countStopped++;
			 }else if(vehicleAvailable[vehicleIndx][1]=="0000") {
				countRepair++;
				
		      }else{
		    	  countStopped++;
		      }
			
		 }
		 
		//table=table+"<tr><td>Ignition Off <img src='../../../images/iggnitionoff.png' height='20'></td><td>Stop <br><img src='../../../images/stop.png' height='20'></td><td> Idle <br><img src='../../../images/idle.png' height='20'></td><td> Inmotion<br><img src='../../../images/inmotion.png' height='20'></td><td> Replay Path<br><img src='../../../images/PlaySymbol.png' height='20'></a></u></td></tr>";
    
	    //table=table+"<tr><td colspan='4' bgcolor='#4B8A08'>Running Vehcile</td></tr>"+running+"<tr><td colspan='4' bgcolor='#D7DF01'>Idel Vehicle</td></tr>"+idle+"<tr><td colspan='4' bgcolor='#FF0040'>Stopped Vehicles</td></tr>"+stopped+"<tr><td colspan='4' bgcolor='#2A120A'>Offline Vehicles</td></tr>"+offline+"</tbody></table><div>";
		//table=table+"<tr bgcolor='#4B8A08'><td></td><td></td><td><font color='#FFFFFF'>Ruunig Vehicle</font></td><td></td></tr>"+running+"<tr bgcolor='#D7DF01'><td></td><td></td><td><font>Idle Vehicle</font></td><td></td></tr>"+idle+"<tr bgcolor='#FF0040'><td></td><td></td><td><font>Stopped Vehicle</font></td><td></td></tr>"+stopped+"<tr bgcolor='#2A120A'><td></td><td></td><td><font color='#FFFFFF'>Offline Vehicles</font></td><td></td></tr>"+offline+"</tbody></table><div>";
		table=table+running+idle+stopped+offline+"</tbody></table><div>";
		
		//table=table+"</tbody></table><div>";
	     var totalvehicle=countInMotion+countIdle+countStopped+countRepair;
	     
		//var totalvehicle=vehicleAvailable.length;
	     var onlineVehilce=countInMotion+countIdle+countStopped;
	     /*var flagTable = "<table>";
	         flagTable +=  "<tr><td>Total Vehicles :<td></td> "+totalvehicle+"</td></tr>";
	         flagTable +=  "<tr><td>Online Vehicles :<td></td> "+onlineVehilce+"</td></tr>";
	         flagTable +=  "<tr><td></td><td>Running : "+countInMotion+"  <img src='../images/ambulance/RunningE.png'  width='40'  height='30'></td></tr>";
	    	 flagTable += "<tr><td></td><td>Idle: "+countIdle+" <img src='../../../images/ambulance/IdleE.png'  width='40' height='30'></td></tr>";
	    	 
	    	 flagTable += "<tr><td></td><td>Stopped : "+countStopped+" <img src='../images/ambulance/stoppedE.png' width='40' height='30'></td></tr>";
	    	 flagTable += "<tr><td colspan='2'>Offline : "+countRepair+" <img src='../images/ambulance/offlineE.png' width='40' height='30'></td></tr>";
	    	 */
	     var flagTable = "<table style=' width: 100%; height:80%;'>";
         flagTable +=  "<tr><td>&nbsp&nbspTotal Vehicles &nbsp&nbsp:</td><td> "+totalvehicle+"</td></tr>";
         flagTable +=  "<tr><td>&nbsp&nbspOnline Vehicles :</td><td> "+onlineVehilce+"</td></tr>";
         flagTable +=  "<tr><td align='center'>Running :</td><td><font color='green'> "+countInMotion+"</font></td></tr>";
         
         var Account=window.localStorage.getItem('accountID');
         if(Account.indexOf("gvk")>-1){
        	 
        	 flagTable += "<tr><td align='center'>Idle:</td><td> <font color='#FE2E2E'>"+(countIdle+countStopped)+"</font></td></tr>";
        	 //flagTable += "<tr><td></td><td>Stopped :<font color='red'> "+countStopped+"</font></td></tr>";
         }else{
    	    flagTable += "<tr><td align='center'>Idle: </td><td><font color='#FF9933'>"+countIdle+"</font></td></tr>";
    	    flagTable += "<tr><td align='center'>Stopped :</td><td><font color='red'> "+countStopped+"</font></td></tr>";
         }
    	 //flagTable += "<tr><td>&nbsp&nbspVehicles Not Communicating<br> for more than 24 hours&nbsp&nbsp&nbsp&nbsp&nbsp : </td><td>"+countRepair+"</td></tr>";
    	 flagTable += "<tr><td>&nbsp&nbspOffline Vehicles&nbsp&nbsp&nbsp&nbsp&nbsp : </td><td>"+countRepair+"</td></tr>";
    	 
    	// flagTable +="<select id='reportFormate' style='max-width:90px;'><option value='html'>HTML</option><option value=''word'>Word</option><option value='excel'>Excel</option></select>";
    	 flagTable +="<tr><td><input type='button' class='buttons' value='OffLine Vehicles' onclick='getOffline()'>&nbsp&nbsp<input type='button' class='buttons' value='Current Status Report' onclick='statusReport()'></td><td><input type='button' class='buttons' value='Inmotion Report' onclick='inmotion()'></td><td><select id='reportFormate' style='max-width:90px;'><option value='html'>HTML</option><option value='word'>Word</option><option value='excel'>Excel</option></select></td></tr>";
    	// flagTable +="<tr><td> &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td><td><input type='button' value='Current Status Report' onclick='statusReport()'></td></tr>";
 	    	
    	 // flagTable += "<td>A/c On : "+acOnCount+" <img src='../images/ac.png' width='20' height='20'></td>";
	    	//flagTable += "<td>Repair: "+countStopped+" <img src='../../../images/off.png'  width='30' height='30'></td></tr></table>";

	    var flags = document.getElementById("flags");
	    flags.innerHTML = flagTable;
	    
	    $('.vehicleStatusReport').show();  
	    $('.load_image1').hide();
		//window.localStorage.setItem('vehicleStatus',table);
		//window.open('availabilityVehicles.html', 'Vehicle Status', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	}

function getOffline(){
	 var formate = document.getElementById("reportFormate").value;
	 var ac=window.localStorage.getItem('accountID');
	  var uid=window.localStorage.getItem('userId');
	  window.open("/dashboard/php/vehiclesStatus.php?&accountID="+ac+"&userID="+uid+"&currentLocation=currentlocation&offline=offline&formate="+formate);
	}
function statusReport(){
	var formate = document.getElementById("reportFormate").value;
	 var ac=window.localStorage.getItem('accountID');
	  var uid=window.localStorage.getItem('userId');
	  window.open("/dashboard/php/vehiclesStatus.php?&accountID="+ac+"&userID="+uid+"&currentLocation=currentlocation&formate="+formate);

	
}
function inmotion(){
	var formate = document.getElementById("reportFormate").value;
	 var ac=window.localStorage.getItem('accountID');
	  var uid=window.localStorage.getItem('userId');
	  window.open("/dashboard/php/InmotionReport.php?&accountID="+ac+"&userID="+uid+"&formate="+formate,"_self");

	
}
	
