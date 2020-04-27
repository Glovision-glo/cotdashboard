function getmandals(){
	//var mandal='<?php echo $allGroups; ?>';
	var userID =  window.localStorage.getItem('userID');
	 if(userID!="admin"){
   	  
//         document.getElementById('invoice').style.display = 'none';
        // document.getElementById('health').style.display = 'none';
         
     }
        if(userID=="tracking"){

        //document.getElementById('overspeedreport').style.display = 'none';
        //document.getElementById('health').style.display = 'none';
       
             }
     if(userID=="monitoring"){

        document.getElementById('overspeedreport').style.display = 'none';
                document.getElementById('health').style.display = 'none';
                       
                                    }       



 
	 var v11 = new Date();
	  v11.setDate(v11.getDate()-1);
     v11.setHours(0); 
     v11.setMinutes(0);
     v11.setSeconds(0); 
     var datestr1 = dateToStringFormat(v11);
	        document.getElementById("fromDate").value=datestr1;
	        var v1 = new Date();
	       
	        var datestr1 = dateToStringFormat(v1);
	        document.getElementById("toDate").value=datestr1;
	
	//alert(mandal.length);
	  var selectlength1=document.getElementById("group").length;
	  var selectlength=document.getElementById("group");
      if (selectlength1>0) {
          for(var i=selectlength1-1;i>0;i--) {
        	  selectlength1.remove(i);
          }
      }
      for(var i=0;i<mandal.length-1;i++) {
          var option1=document.createElement("option");
          option1.value=mandal[i];
          option1.textContent=mandal[i];
          selectlength.appendChild(option1);
      }
      
    
      
}
function suitableVehicles(){
	
	//str.indexOf("welcome"); 
	var mandal=document.getElementById("group").value;
	vehicle
	 var vl=document.getElementById("vehicle").length;
	  var v=document.getElementById("vehicle");
    if (vl>0) {
        for(var i=vl-1;i>0;i--) {
      	  v.remove(i);
        }
    }
	for(var x=0;x<allDevices.length;x++){
		if(allDevices[x].indexOf(mandal)>-1){
			var option1=document.createElement("option");
	          option1.value=allDevices[x].split("*")[0];
	          option1.textContent=allDevices[x].split("*")[0];
	          v.appendChild(option1);
		}
		
	}
}



function getTripReport(){
         
	 var Account = window.localStorage.getItem('accountID');
	 var userID =  window.localStorage.getItem('userID');
	
   var formate = document.getElementById('fromDate').value;
   var toDate = document.getElementById('toDate').value;
   var fromDate = document.getElementById('fromDate').value;
   var vehicle = document.getElementById('vehicle').value;
   var group = document.getElementById('group').value;
   var FromDate = datestringToEpochDB(fromDate);
   var ToDate =  datestringToEpochDB(toDate);
   
        var location="ReportForInMotion.php?";
       
	window.open(location + "&accountID=" + Account
		   + "&vehicle=" + vehicle
		   + "&group=" + group
           + "&fromDate=" + FromDate
           + "&userID=" + userID
           + "&formate=" +formateType
           + "&toDate=" + ToDate);
}

function getStatusReportDaywise(){

     var Account = window.localStorage.getItem('accountID');
         var userID =  window.localStorage.getItem('userID');

   var formate = document.getElementById('fromDate').value;
   var toDate = document.getElementById('toDate').value;
   var fromDate = document.getElementById('fromDate').value;
   var vehicle = document.getElementById('vehicle').value;
   var group = document.getElementById('group').value;
   var FromDate = datestringToEpochDB(fromDate);
   var ToDate =  datestringToEpochDB(toDate);

        var location="vehiclesStatusReportDaywise.php?";

        window.open(location + "accountID=" + Account
           + "&fromDate=" + FromDate
           + "&reportFormate=" +formateType
           + "&toDate=" + ToDate
           );
         // +"&crone=yes");


}

function getVehicleHealthStatus(){
	 var Account = window.localStorage.getItem('accountID');
	 var userID =  window.localStorage.getItem('userID');
	
   var formate = document.getElementById('fromDate').value;
   var toDate = document.getElementById('toDate').value;
   var fromDate = document.getElementById('fromDate').value;
   var vehicle = document.getElementById('vehicle').value;
   var group = document.getElementById('group').value;
   var FromDate = datestringToEpochDB(fromDate);
   var ToDate =  datestringToEpochDB(toDate);
   
        var location="VehicleHealthCycleSuperfast.php?";
       
	window.open(location + "accountID=" + Account
		   + "&vehicle=" + vehicle
		   + "&group=" + group
           + "&fromDate=" + FromDate
           + "&userID=" + userID
           + "&formate=" +formateType
           + "&toDate=" + ToDate
           +"&type=health");
         // +"&crone=yes");
    
	
	
}
function getInvoice(){
	 var Account = window.localStorage.getItem('accountID');
	 var userID =  window.localStorage.getItem('userID');
	
  var formate = document.getElementById('fromDate').value;
  var toDate = document.getElementById('toDate').value;
  var fromDate = document.getElementById('fromDate').value;
  var vehicle = document.getElementById('vehicle').value;
  var group = document.getElementById('group').value;
  var FromDate = datestringToEpochDB(fromDate);
  var ToDate =  datestringToEpochDB(toDate);
  var location="VehicleHealthCycleSuperfast.php?";
   var invoiceNO = prompt("Please enter Invoice Number", "01");
   
   if (invoiceNO != null) {
      
   
   
	window.open(location + "accountID=" + Account
		   + "&vehicle=" + vehicle
		   + "&group=" + group
          + "&fromDate=" + FromDate
          + "&userID=" + userID
          + "&formate=" +formateType
          + "&toDate=" + ToDate
          +"&type=invoice"
          +"&invoiceNO="+invoiceNO);
   
	
   }
}
function getOverSpeedReport(){

         var Account = window.localStorage.getItem('accountID');
         var userID =  window.localStorage.getItem('userID');

 //  var formate = formateType;
   var toDate = document.getElementById('toDate').value;
   var fromDate = document.getElementById('fromDate').value;
   var vehicle = document.getElementById('vehicle').value;
   var group = document.getElementById('group').value;
   var FromDate = datestringToEpochDB(fromDate);
   var ToDate =  datestringToEpochDB(toDate);
   var speedlimit=prompt("Enter Maximum Speed Limit");
    if(!isNaN(speedlimit)){
          var location="OverSpeedReport.php?";

        window.open(location + "&accountID=" + Account
                   + "&vehicle=" + vehicle
                   + "&group=" + group
           + "&fromDate=" + FromDate
           + "&userID=" + userID
           + "&formate=" +formateType
           + "&toDate=" + ToDate
           +"&speedlimit="+speedlimit);
   }else{
          alert(" Enter Numeric Value");

    }


}


function completeTripReport(){
   var Account = window.localStorage.getItem('accountID');
   var userID =  window.localStorage.getItem('userID');
    var formate = document.getElementById('fromDate').value;
   var toDate = document.getElementById('toDate').value;
   var fromDate = document.getElementById('fromDate').value;
   var FromDate = datestringToEpochDB(fromDate);
   var ToDate =  datestringToEpochDB(toDate);
   var id = document.getElementById('vehicleID').value;
  var plant = document.getElementById('plant').value;
   window.open("../autocare/server/tripReport.php?"
           + "&accountID=" + Account
           + "&vehicleID=" + id
           + "&fromDate=" + FromDate
           + "&userID=" + userID
           + "&toDate=" + ToDate
           + "&plantID="+plant);
}

function CSVUpload(){
  
  // window.open ('csvTripUpload.php', 'newwindow', config='left=500,right=500,top=500,bottom=500,height=100,width=400, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=no, directories=no, status=no');
    
   var left = (screen.width) / 2;
   var top = (screen.height) / 4;  // for 25% - devide by 4  |  for 33% - devide by 3
   var targetWin = window.open('csvTripUpload.php','csv upload...', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=1000, height=300, top=' + top + ', left=' + left/2);
     
}

function geozoneUpload1(){
  
 
   var left = (screen.width) / 2;
   var top = (screen.height) / 4;  
  
   var targetWin = window.open('geozonesUpload.php','geozone upload...', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no,  width=800, height=200, top=' + top + ', left=' + left/2);
     
}

function dateToEpochDB(indate)
{
    var tempdate=new Date();
    tempdate=parseInt(Math.round(indate.getTime()/1000.0));
 //   alert("D2EB: " + tempdate);
    return tempdate;
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
function datestringToEpochDB(ds)
{
    if (ds) {
   //     alert("DS2EB: " + ds);
        var tempdate = stringFormatToDate(ds);
        return dateToEpochDB(tempdate);
    }
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
function dateInit()
{


        var v = new Date();
        v.setHours(0); 
        v.setMinutes(0);
        v.setSeconds(0);  
        var datestr = dateToStringFormat(v);
        document.getElementById("fromDate").value=datestr;
        var v1 = new Date();
        v1.setHours(23); 
        v1.setMinutes(59);
        v1.setSeconds(50); 
        var datestr1 = dateToStringFormat(v1);
        document.getElementById("toDate").value=datestr1;
        var i=document.getElementById("Name");
        var displayAccountID=accountID;
        if(accountID=="gvk-up-108")
             
            //  displayAccountID="Samajwadi Swasthya Sewa";
              displayAccountID="GVK-UP-108";
        else if(accountID=="gvk-up-102")
             displayAccountID="National Ambulance Service";       

        i.innerHTML="AccountID :"+displayAccountID+" | UserID:"+userID;
   
}$(function() {

    var pickerOpts = {
            format: 'd-M-Y H:i:s',
            timepicker:true,
            datepicker:true,
            changeMonth : true,
            changeYear : true,
            showSeconds: true,
            showMonthAfterYear : true, 
        };
   
      var pickerOpts1 = {
            format: 'd-M-Y H:i:s',
            timepicker:true,
            datepicker:true,
            
            changeMonth : true,
            changeYear : true,
            showSeconds: true,
            showMonthAfterYear : true, 
        };
    
         $("#fromDate").datetimepicker(pickerOpts);
         $("#toDate").datetimepicker(pickerOpts1);
        
});


function hide(){
  alert("hai");
}
function show(){

}




/*
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
*/
