<?php
 include 'php/SessionCheck.php';

  session_start();
  $accountID= $_SESSION["accountID"];
  $userID=$_SESSION["user"];
  if(!sessionCheck()){
      header("Location: login.php?errormsg=2");
      exit;

  }
  session_write_close(); 
  
?>


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
       <link rel="shortcut icon" href="images/glovision.ico"/>
       <title>Glovision Techno Services</title>
       <script src="js/jquery-1.9.1.min.js"
           type="text/javascript"></script>
 <!--        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=drawing"></script> 
          <script type="text/javascript" src="js/markerlabel.js"></script>-->
<!-- 
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"  crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"  crossorigin=""></script>
-->
<script src="https://apis.mapmyindia.com/advancedmaps/v1/4nk6fj23vniumia8cieholhk1ak2yzh2/map_load?v=1.3"></script>
       <!--  <script src="../../../vehiclesStatus.js" type="text/javascript"></script> -->
     <!--  <script src="js/googletac.js" type="text/javascript"></script> -->
<!--       <script src="js/leaflettac.js" type="text/javascript"></script> -->
    <script src="js/mapmyindiatac.js" type="text/javascript"></script>
        <script src="js/tac.js" type="text/javascript"></script>
    <!-- <script src="js/leaflettac.js" type="text/javascript"></script>  -->
        <script src="js/InmotionReport.js" type="text/javascript"></script>
      <!--    <script src="../../../search.js" type="text/javascript"></script>-->
<!-- menu bar-->
 <script type="text/javascript" src="//cdn.rawgit.com/niklasvh/html2canvas/0.5.0-alpha2/dist/html2canvas.min.js"></script>

	<script type="text/javascript" src="//cdn.rawgit.com/MrRio/jsPDF/master/dist/jspdf.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
<link href="css/menu.css" rel="stylesheet" type="text/css" />   
<link href="css/loadimage.css" rel="stylesheet" type="text/css" /> 
<link href="css/menu.js" rel="stylesheet" type="text/css" />    
    <link href="css/fuelbar.css" rel="stylesheet" type="text/css" /> 
       <link rel="stylesheet" type="text/css" href="js/jquery.datetimepicker.css"/>
<script src="js/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="js/jquery.circlechart.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

       <script>
          function zoom() {
            document.body.style.zoom = "100%" 
        } 
           var nochangeofflineonlineidle='no';
            var showorhidemarkers="hide";
            var sessionDate=new Date();
            var acc='<?php echo $accountID; ?>';
            var userid='<?php echo $userID; ?>'; 
          var  value=acc+",1,1,1,1,1,1,1,1,1,1,1,1,1,1443092021,1413543222,1,1,1,1,1,1,1,1,silver";
          window.localStorage.setItem('accountID',acc);
          window.localStorage.setItem('userId',userid);
          if(acc.indexOf("gvk") == -1 || acc.indexOf("gog") == -1 || acc.indexOf("khil") == -1 || acc.indexOf("als") == -1){
             window.localStorage.setItem('showmap','all');
          }
          window.localStorage.setItem('packagingidentify',value);
          window.localStorage.setItem('vehicleType',"ambulance");
          value=value.split(","); 
           $(document).ready(function(){ 


            $('.demo-1').percentcircle({fillColor:'green'},0,0);
           $('.demo-2').percentcircle({fillColor:'yellow'},0,0);
           $('.demo-3').percentcircle({fillColor: 'red'},0,0); 
            $('.demo-5').percentcircle({fillColor: ' #FE9A2E'},0,0);
           $('.demo-4').percentcircle({fillColor: ' #0d62a0 '},0,0);

                var doc=new jsPDF('p', 'pt', 'b1');    
                var specialElementHandlers = {
                     '#editor': function (element, renderer) {
                     return true;
                     }
                };

               $('#pdf').click(function () {

                  specialElementHandlers = {
                                      '#editor': function (element, renderer) {
                                                           return true;
                                                                                }
                                                                                                };

                   
                   doc.fromHTML($('#reportarea').html(), 15, 15, {
                       'width': 500,
                       'elementHandlers': specialElementHandlers
                  });
                  
                 doc.save('reports.pdf');
                                                                        
               });

        	 
        	 $(".editableBox").change(function(){         
        	         $(".timeTextBox").val($(".editableBox option:selected").html());
        	             });
        		  
        		$('.load_image1').show();
        		vehicleAvailable=[];
        		var requestFromTrack="no";
        		var AccountIDFromTrack;
        		vehicleWithEventData=[];
        	
        		$("#play").attr("disabled", "disabled");
        		var liveSpeedMonitoringAtLoad=function(){
        			vehicleStatusFunction();
        			$("#play").attr("disabled", "disabled");
        		id=setInterval(function(){
        			vehicleStatusFunction();

        		 },60000);

                           setInterval(function(){
                                          //    casemanagementupdates();

                                    },300000);


                          /*       setInterval(function(){
                                              casemanagementupdates();

                                    },10000);
                              */
        		}

                 

        		//180000
        	    var vehicleStatusFunction=function(){ 
                      var curdate=new Date();
                     var difinsec=curdate.getTime()-sessionDate.getTime();
                     var diffinmin=difinsec/3600;
                     if(diffinmin>150){  //10 =40 sec so 230 means 15 mints
                         clearInterval(id);
                          alert("Session expired. Please reload");
                         location.reload();
                     }
        	    	$('.vehicleStatusReport').hide();
        	    //	$('.load_image1').show();
        	    var Account=window.localStorage.getItem('accountID');
        	   /* if(requestFromTrack=="yes") {
        	    	Account="nslsugars";
            	    window.localStorage.setItem('accountID',Account);
            	  alert(Account+' ra,ama');
            	  }
        	  
        	    */
        	      
        	    var vid=$( "#vehicleID" ).val();
        	    var from=$( "#fromDate" ).val();
        	    var to=$( "#toDate" ).val();
        	    from=fuelDBConverts(from);
        	    to=fuelDBConverts(to);
        	  
        	    var userID= window.localStorage.getItem('userId');
        	    $.ajax({
        	        type : 'get',
        	        //url:'php/vehiclesStatus.php',
        	        url:'php/vehiclesStatus.php',
        	        data:{"vehicleID":vid, "accountID":Account,"fromDate":from,"toDate":to,"userID":userID},
        	        dataType:'json',
        	        success: function(data) {
        	            $.each(data, function() {
        	                 $.each(data, function(key, value) {
        	                	 if(value.length<=1){ 
                                               //location.reload();
        	                		 alert("Vehicle Has No movement");
        	                	 }
        	                	// alert(value.length);
        	                     globalVehicles= window.localStorage.getItem('globalVehicles').split(',');//getting global Vehicles From FuelVehicles.js     
        	                     globalVehicles1= window.localStorage.getItem('globalVehicles1').split(',');//getting global Vehicles From FuelVehicles.js     
        	                     var vehicleWithEventDataIndx=0;
        	                     
        	                	 for(var vehicleindx=0;vehicleindx<globalVehicles.length;vehicleindx++,vehicleWithEventDataIndx++){
        	                	     vehicleWithEventData[vehicleWithEventDataIndx]=[];//create doube array for each vehicle to store event data
        	                		 var eventDataIndx=0;
        	                	     for(var loopindx1=0;loopindx1<value.length;loopindx1++){
        	                		     if(globalVehicles[vehicleindx].toLowerCase()==value[loopindx1][9].toLowerCase()){
        	                                 vehicleWithEventData[vehicleWithEventDataIndx][eventDataIndx]=value[loopindx1];
        	                                 eventDataIndx++;
        	                             }
        	                	     }//end inner oop
        	                	 }//end outer loop
        	                	 //alert(vehicleWithEventDataIndx);
                                         //document.getElementById('hide').style.display="none";
        	                	 var i=document.getElementById("Name");
           	                	 var displayAccountID=Account;
                                      if(Account=="gvk-up-108"){
                                        //       displayAccountID="Samajwadi Swasthya Sewa |UP-108";
                                               displayAccountID="UP-108";
                                      }
                                        else if(Account=="gvk-up-102"){
                                          //   displayAccountID="National Ambulance Service | UP-102";
                                             displayAccountID=" UP-102";
                                       }
                                        i.innerHTML="<font color='black'> Welcome :"+displayAccountID+" | "+userID +'</font>';
                                       document.getElementById("accountdis").innerHTML=Account.toUpperCase();
                                        var username="Welcome :"+userID+" | ";
                                        if(userID=="admin"){
                                            username='';
                                         } 
                                //        document.getElementById("NameID").innerHTML="<font color='black'> "+username+'  <u style="color:black;"><a onclick="back()" rel="external" style="color:black;">Logout</a></u>  </font>';

                                        var showmap= window.localStorage.getItem('showmap');
        	                	 if(showmap=="all" || showmap=="online" || showmap=="offline" || showmap=="idle" || showmap=="single"){
        	                	         
                                                  allVehicleStatus(); 
                                                  // groupwisedisplay();
        	                	 }else{
        	                	    MapClear();
        	                	 }
        	                });
        	            });
        	        }
        	     });
        	}
        	    
        	    var value = window.localStorage.getItem('packagingidentify');
        	    var Account=window.localStorage.getItem('accountID');
          	  /*   if(Account == '' || Account == undefined) {
          	    	
       	    	   Account="nslsugars";
           	       window.localStorage.setItem('accountID',Account);
           	       alert(Account);
           	      requestFromTrack="yes";
       	         }
        	    */
              value=value.split(",");
                 if(value[22]!='1'){
                     window.alert("You are not entitled to use this feature please contact support@glovision.co")
                     window.location = 'main.jsp'
                 }else{
                 
        	    $(window).load(validuser);
        	    $(window).load(vehicle);
        	    $(window).load(liveSpeedMonitoringAtLoad);
                  // $(window).load(casemanagementupdates);


        	    $('#vehicleID').change(vehicleStatusFunction);
        	    $('#reset').click(allvehiclesInfo);
        	    
        	    $('#online').click(online);
        	    $('#idle').click(idle);
        	    $('#offline1').click(offline);
        	    
        	    
        	    $('#getRoute').click(vehicleStatusFunction);
        	    $('#multyPath').click(twoVehiclePath);
        	    $('#allVehicles').click(allVehicleStatus);
        	    $('#lableDiplay').change(allVehicleStatus);
        	    $('#play').click(liveSpeedMonitoringAtLoad);
        	    $('#allVehiclesWithRoutes').click(allVehicleStatusWithRoute);
        	    $('#MapClear').click(MapClear);
        	    
                 }
        	    
        	    
        	})


          //pdf formate
     /*    var doc=new jsPDF('p', 'pt', 'b1'); 
          var specialElementHandlers = {
              '#editor': function (element, renderer) {
               return false;
              }
          };

         $('#pdf').click(function () {
          doc.fromHTML($('#reportarea').html(), 0, 0, {
            'width': 100,
            'elementHandlers': specialElementHandlers
            });
           doc.save('sample-file.pdf');
      
      });
*/

function excel(name) {
     window.open('data:application/vnd.ms-excel;filename='+name+'.xls,' + encodeURIComponent($('#reportarea').html()));
     e.preventDefault();
      //getting values of current time for generating the file name

     }

//function printreport(){
  //   window.print('reportarea');

  // }
function printreport() {
var DivID="reportarea";
var disp_setting="toolbar=yes,location=no,";
disp_setting+="directories=yes,menubar=yes,";
disp_setting+="scrollbars=yes,width=650, height=600, left=100, top=25";
   var content_vlue = document.getElementById(DivID).innerHTML;
      var docprint=window.open("","",disp_setting);
         docprint.document.open();
            docprint.document.write('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"');
               docprint.document.write('"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">');
                  docprint.document.write('<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">');
                     docprint.document.write('<head><title>My Title</title>');
                        docprint.document.write('<style type="text/css">body{ margin:0px;');
                           docprint.document.write('font-family:verdana,Arial;color:#000;');
                              docprint.document.write('font-family:Verdana, Geneva, sans-serif; font-size:12px;}');
                                 docprint.document.write('a{color:#000;text-decoration:none;} </style>');
                                    docprint.document.write('</head><body onLoad="self.print()"><center>');
                                       docprint.document.write(content_vlue);
                                          docprint.document.write('</center></body></html>');
                                             docprint.document.close();
                                                docprint.focus();
                                                } 

/*
function printreport(){
     var printContents = document.getElementById("reportarea").innerHTML;
     var originalContents = document.body.innerHTML;
     document.body.innerHTML = "<html><head><title></title></head><body>" + printContents + "</body>";
     window.print();
    document.body.innerHTML = originalContents;
 }
  */
       </script>
      
   </head>



   <style>

 .leaflet-pane {
    z-index: 0;
}

.demo > div {
  float:left;
  margin: 10px;

}

/*    body {zoom: 2; height:97%;width:99%; margin: 0; padding: 0;background:#ff9933;background: url('http://www.demo.amitjakhu.com/login-form/images/bg.png'); } */
  body { height:97%;width:99%; margin: 0; padding: 0;background:white; }
    #mapcontainer {position: absolute; height:81%;width:98%;left:0px;top:17.7%;border-style: solid;border-radius: 0px;

                    background:#086A87 ;display: none
                    }

  
   #wrapper { position: relative; position: absolute;} 
#availableVehicle{height:5%;width:100%;  top:16%;position: absolute; left: 0%; z-index: 99; background: #211e1f  ;opacity:1;display: none}
  #vehicleStatusReport {opacity:1;height: 82.3%;width:100%; position: absolute; background-color: transparent; top:28%; left: 0%; z-index: 99; background: white;border-radius: 0px;border-color: white;display: none } 
  #time { position: absolute; background-color: transparent; bottom: 10px; right: 10px; z-index: 99; background: #81BEF7;opacity:0.5; }
#logout { width:99.9%;left:0%;height:11.8%;position: absolute; background-color: transparent; z-index: 99; opacity:1;border-radius:1px;}

  #arrow{
   border-color: transparent transparent #084B8A transparent;
    border-style: solid;
    border-width: 0px 10px 10px 10px;
    height: 0px;
    width: 0px;
    position: absolute;top:98%; left: 68%; z-index: 99; background: #FBF3F3;opacity:1;
 
  }

.image_left {
	    -webkit-transform: rotate(180deg);
	    	    -moz-transform: rotate(180deg);
	    	    	    -ms-transform: rotate(180deg);
	    	    	    	    -o-transform: rotate(180deg);
	    	    	    	    	    transform: rotate(180deg);
	    	    	    	    	    	}

    #arrow2{
   border-color: transparent transparent #084B8A transparent;
    border-style: solid;
    border-width: 0px 40px 40px 40px;
     border-height: 0px 40px 40px 40px;
    height: 0px;
    width: 0px;
    position: absolute;top:94%; left: 68%; z-index: 99; background: #FBF3F3;opacity:1;
 
  }
div#menu {
    position: absolute;
    top:12.6%;

    left:0%;

    width:100%;
background: rgb(35,83,138); /* Old browsers */
background: -moz-linear-gradient(top, rgba(35,83,138,1) 0%, rgba(167,207,223,1) 98%); /* FF3.6-15 */
background: -webkit-linear-gradient(top, rgba(35,83,138,1) 0%,rgba(167,207,223,1) 98%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(to bottom, rgba(35,83,138,1) 0%,rgba(167,207,223,1) 98%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#23538a', endColorstr='#a7cfdf',GradientType=0 );

}
a{
color: #0e1617;
text-decoration: none;
cursor: hand; 
}
.labels {
      color: white;

           background-color:green;
                     font-size: 9px;
                    font-weight: bold;
                      opacity:0.5;
                               text-align: center;
                                border-radius:0px;
                                   /* width: 70px;
                                    height:20px;*/
                                         border: 1px solid  blue;
                                             /*word-break: break-all;*/
                                              white-space: nowrap;
                                                 }


     #bottom {

      position: absolute; background-color: transparent;width:60%; top:99%; left: 37%; z-index: 99; background: #084B8A;opacity:1;} 
  
  #report {

      position: absolute; background-color: transparent; top:7%;right:5%; z-index: 99;background:#086A87;border-radius:10px;opacity:0.8;
      } 
  
   #allVehicls {left:5%;top:7%; position: absolute; width:90%;background-color: transparent;  z-index: 99; background: #a3e4d7;opacity:0.8;display: none} 
    #runningVehilces {left:5%;top:7%; position: absolute; width:90%;background-color: #A9F5A9;  z-index: 99; background: #a3e4d7;opacity:0.8;display: none} 
  #idleVehilces { left:5%;top:7%;position: absolute; width:90%;background-color: transparent; z-index: 99; background: #a3e4d7;opacity:0.8;} 
  #offlineVehilces {left:5%;top:7%; position: absolute; width:90%;background-color: transparent; z-index: 99; background:#a3e4d7;opacity:0.8;} 
  #casemanagement{display:none;font-weight: bold;font-size: 15px;position: absolute; background-color: transparent; top:55%; left: 0%; z-index: 99;85929e ;border-radius:10px;opacity:1;width:150px;height:200px;} 
   #flags{border-width: 4px;  border-style: inset; border-color: red;font-weight: bold;font-size: 15px;position: absolute; background-color: transparent; top: 45%; right: 0%; z-index: 99; background:  #85929e ;opacity:1;}
  .load_image1{ height: 0px;width:0px;left:30%;top:28%;border-radius: 10px; position: absolute; background-color: transparent; z-index: 99; background: #CDD0D0; opacity:1;}
 #searchresult{ position: absolute; background-color: transparent; top:97%;left:49%; z-index: 99; background: #81BEF7;opacity:1; }
 #cmgtshowhide{display:block; position: absolute; background-color: transparent; top:97%;left:6%; z-index: 99; background: #81BEF7;opacity:1; } 
#legend{background-color: #e5e7e9;display:block; position: absolute;  top:93%;left:0%; z-index: 99; border-radius: 10px;width:100%; display:none; }

 #mail{ position: absolute; background-color: transparent; top:18%;right:0px;width:5%; z-index: 99; background: #81BEF7;opacity:1; }
#live{ position: absolute; background-color: transparent; top:80%;left:0px;width:5%; z-index: 99; background: #81BEF7;opacity:1;border-radius: 10px; }  
#div1{ position: absolute; background-color: transparent; top:20%;left:0px;width:5%; z-index: 99; background: #81BEF7;opacity:1;border-ra
dius: 10px; }
#div2{ position: absolute; background-color: transparent; top:35%;left:0px;width:5%; z-index: 99; background: #81BEF7;opacity:1;border-radius: 10px; }
#div3{ position: absolute; background-color: transparent; top:60%;left:0px;width:5%; z-index: 99; background: #81BEF7;opacity:1;border-radius: 10px; }
#div4{ position: absolute; background-color: transparent; top:75%;left:0px;width:5%; z-index: 99; background: #81BEF7;opacity:1;border-radius: 10px; }
#div5{ position: absolute; background-color: transparent; top:90%;left:0px;width:5%; z-index: 99; background: #81BEF7;opacity:1;border-radius: 10px; }

#div6{ position: absolute; background-color: transparent; top:20%;right:0px;width:5%; z-index: 99; background: #81BEF7;opacity:1;border-radius: 10px; }
#div7{ position: absolute; background-color: transparent; top:35%;right:0px;width:5%; z-index: 99; background: #81BEF7;opacity:1;border-radius: 10px; }
#div8{ position: absolute; background-color: transparent; top:60%;right:0px;width:5%; z-index: 99; background: #81BEF7;opacity:1;border-radius: 10px; }
#div9{ position: absolute; background-color: transparent; top:75%;right:0px;width:5%; z-index: 99; background: #81BEF7;opacity:1;border-radius: 10px; }
#allreports{position: absolute; background-color: transparent; top:17.2%;left:0px;width:20%;height:81.5%; z-index: 99; background: #e5e7e9;opacity:1;border-style: solid;
    border-color: none; display: none}
#reportarea{overflow-x: auto;position: absolute; background-color: transparent; top:17.2%;left:20%;height:81.5%;width:80%; z-index: 99; background: #e5e7e9;opacity:1;border-style: 1px;
    border-color: black;display: none }



 #msg{ position: absolute; background-color: transparent; top: 250px; right: 10px; z-index: 99; background: #81BEF7;opacity:1; }
  #flags,#msg {
   
    background: white;
    border-style: solid;
    border-color: #086A87;
   
   
} 
      #innerDiv {
   
    background: white;
    border-style: solid;
    border-color: #0000ff;
    width:29%;
    height: 80%; 
}
  
.load_image2{
background-image:url('images/ajax-loader-original.gif');
 width: 100px;  
height: 100px;
align:center;
}
.divdisign
{
font-size:5pt;
font-family:times new roman;
font-weight:bold;
font-style:italic;

}
.editableBox {
    width: 75px;
        height: 20px;
        }

.timeTextBox {
    width: 54px;
        margin-left: -78px;
            height: 20px;
                border: none;
                }


input[type=button],a{
 cursor:pointer;
}


.gradient_bgcolor{
background: #f2f6f8; /* Old browsers */
background: -moz-linear-gradient(top, #f2f6f8 0%, #d8e1e7 50%, #b5c6d0 51%, #e0eff9 100%); /* FF3.6-15 */
background: -webkit-linear-gradient(top, #f2f6f8 0%,#d8e1e7 50%,#b5c6d0 51%,#e0eff9 100%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(to bottom, #f2f6f8 0%,#d8e1e7 50%,#b5c6d0 51%,#e0eff9 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f2f6f8', endColorstr='#e0eff9',GradientType=0 ); /* IE6-9 */

}

.gradient_green{
  background: #b4e391; /* Old browsers */
background: -moz-linear-gradient(top, #b4e391 0%, #61c419 59%, #b4e391 100%); /* FF3.6-15 */
background: -webkit-linear-gradient(top, #b4e391 0%,#61c419 59%,#b4e391 100%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(to bottom, #b4e391 0%,#61c419 59%,#b4e391 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#b4e391', endColorstr='#b4e391',GradientType=0 ); /* IE6-9 */
}
.gradient_yellow{
   background: #fefcea; /* Old browsers */
background: -moz-linear-gradient(top, #fefcea 0%, #f1da36 63%); /* FF3.6-15 */
background: -webkit-linear-gradient(top, #fefcea 0%,#f1da36 63%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(to bottom, #fefcea 0%,#f1da36 63%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#fefcea', endColorstr='#f1da36',GradientType=0 ); /* IE6-9 */

}
.gradient_red{

    background: #febbbb; /* Old browsers */
background: -moz-linear-gradient(top, #febbbb 0%, #fe9090 45%, #ff5c5c 90%); /* FF3.6-15 */
background: -webkit-linear-gradient(top, #febbbb 0%,#fe9090 45%,#ff5c5c 90%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(to bottom, #febbbb 0%,#fe9090 45%,#ff5c5c 90%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#febbbb', endColorstr='#ff5c5c',GradientType=0 ); /* IE6-9 */

}

.gradient_blue{

    background: #FE9A2E; /* Old browsers */
background: -moz-linear-gradient(top, #F7BE81 0%, #FE9A2E 45%,#FE9A2E 90%); /* FF3.6-15 */
background: -webkit-linear-gradient(top, #F7BE81 0%,#FE9A2E 45%,#FE9A2E 90%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(to bottom, #F7BE81 0%,#FE9A2E 45%,#FE9A2E 90%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#F7BE81', endColorstr='#FE9A2E',GradientType=0 ); /* IE6-9 */

}


.gradient_menubg{
  background: rgb(167,207,223); /* Old browsers */
background: -moz-linear-gradient(top, rgba(167,207,223,1) 0%, rgba(35,83,138,1) 100%); /* FF3.6-15 */
background: -webkit-linear-gradient(top, rgba(167,207,223,1) 0%,rgba(35,83,138,1) 100%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(to bottom, rgba(167,207,223,1) 0%,rgba(35,83,138,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#a7cfdf', endColorstr='#23538a',GradientType=0 ); /* IE6-9 */

}
   </style>
   <script language="javascript"> 
function filtery (term,cellNr){
                 var _id='search';

                var suche =term.value.toLowerCase();
                var table = document.getElementById(_id);
                var ele;
               var dummycount=0;
                for (var r = 1; r < table.rows.length; r++){
                        ele = table.rows[r].cells[cellNr].innerHTML.replace(/<[^'>]+>/g,'');
                        if (ele.toLowerCase().indexOf(suche)>=0 ){dummycount++;
                                table.rows[r].style.display = '';
                         }
                        else table.rows[r].style.display = 'none';
                }
           
              document.getElementById('casecount').value=dummycount;

        }

   function show() {
     // Get the DOM reference
     var contentId = document.getElementById("vehicleStatusReport");
     
   contentId.style.display = "block"; 
  /* var duration = 1000;
   // how many times should it should be changed in delay duration
   var AmountOfActions=100;

contentId.style.height = 0;  
var counte=0;
   setInterval(function(){counte ++;
      if ( counte<AmountOfActions) { contentId.style.height = counte/AmountOfActions;}
   },
   duration / AmountOfActions);
   */
   }
    
  function hide(){
      var contentId = document.getElementById("vehicleStatusReport");
         // Toggle 
         contentId.style.display = "none"; 
        /* contentId.style.display == "block" ? contentId.style.display = "none" : 
       contentId.style.display = "block"; */
  }
  
  function allshow(event) {
	  var contentId = document.getElementById("allVehicls");
	  contentId.style.display = "block"; 
	// alert(event.clientX);  
        //  contentId.style.position = "absolute";
          //contentId.style.left = event.clientX+'px';
          //contentId.style.top =(event.clientY+15)+'px';

	  document.getElementById("runningVehilces").style.display = "none"; 
	  document.getElementById("idleVehilces").style.display = "none"; 
	  document.getElementById("offlineVehilces").style.display = "none"; 
  


}
/*
  var doc=new jsPDF('p', 'pt', 'b1');    
                var specialElementHandlers = {
                     '#editor': function (element, renderer) {
                     return true;
                     }
                }
function pdfc(){

                 alert();  

                  specialElementHandlers = {
                                      '#editor': function (element, renderer) {
                                                           return true;
                                                                                }
                                                                                                };

                   
                   doc.fromHTML($('#reportarea').html(), 15, 15, {
                       'width': 500,
                       'elementHandlers': specialElementHandlers
                  });
                  
                 doc.save('reports.pdf');
                                                                        
              


  

}

*/

function pdfc(name) {
      var divid="reportarea";
    var pdf = new jsPDF('p', 'pt', 'b1');
    var pdf_name = name+'_Report.pdf';
    htmlsource = $('#'+divid)[0];

    specialElementHandlers = {
        '#bypassme': function (element, renderer) {
            return true
        }
    };
    margins = {
        top: 80,
        bottom: 60,
        left: 40,
        width: 522
    };

    pdf.fromHTML(
    htmlsource, // HTML string or DOM elem ref.
    margins.left, // x coord
    margins.top, { // y coord
        'width': margins.width, // max width of content on PDF
        'elementHandlers': specialElementHandlers
    },

    function (dispose) {                

        pdf.save(pdf_name);
    }, margins);
}

  function allhide() {
        $ ("#allreports").hide();
      //document.getElementById(acc).style. border="2px solid  white";
           //    document.getElementById(acc).style. boxShadow="9px 5px 5px black";

     //    document.getElementById('report').style.display = 'none';
         document.getElementById('vehicleStatusReport').style.display = 'none';
         document.getElementById('availableVehicle').style.display = 'none';
         document.getElementById('flags').style.display = 'none';
                document.getElementById('reportarea').style.display="none";
	 //document.getElementById('invoice').style.display = 'none';
	 // document.getElementById('vehiclehealth').style.display = 'none';  
	       var userID=window.localStorage.getItem('userId');
	      // document.getElementById('vehiclehealth').style.display = 'none';
   /*   if(userID=="monitoring"){
	      //document.getElementById('vehiclehealth').style.display = 'none';
	      document.getElementById('invoice').style.display = 'none';
             document.getElementById('vehiclehealth').style.display = 'none';	    
              document.getElementById('offline').style.display = 'none'; 
              document.getElementById('overspeed').style.display = 'none';
              
      }
      if(userID=="tracking"){
             document.getElementById('vehiclehealth').style.display = 'none'; 
              document.getElementById('invoice').style.display = 'none';
              document.getElementById('offline').style.display = 'none';
              document.getElementById('online1').style.display = 'none';

                            document.getElementById('overspeed').style.display = 'none';
       }*/
        var ac=window.localStorage.getItem('accountID');
        if(ac=="gvkrajasthan"){
          // document.getElementById('offline').value = 'Offline';
           // document.getElementById('offline1').value = 'Offline';


        }
       // document.getElementById('pdfref').style.display = 'none';
        // document.getElementById('nocases').style.display = 'none';
      //  if(userID=="administrator"){
           //    document.getElementById('pdfref').style.display = 'block';
            //  document.getElementById('nocases').style.display = 'block';
       // }
 window.localStorage.getItem('viewtype',"text");
   textview(); 
//	  var contentId = document.getElementById("allVehicls");
  //    contentId.style.display = "none"; 
   //   document.getElementById("runningVehilces").style.display = "none"; 
//	  document.getElementById("idleVehilces").style.display = "none"; 
//	  document.getElementById("offlineVehilces").style.display = "none"; 
var uid=window.localStorage.getItem('userId');
  if(uid=="shrammhu" || uid=="rbsk"){
         document.getElementById('reportsbtn').style.display = 'none';
        }

  }
  function runningshow(event) {
	  var contentId = document.getElementById("runningVehilces");
	  contentId.style.display = "block"; 
	  // contentId.style.position = "absolute";
	  //           contentId.style.left = event.clientX+'px';
	  //                     contentId.style.top =(event.clientY+15)+'px';
	  document.getElementById("allVehicls").style.display = "none"; 
	  document.getElementById("idleVehilces").style.display = "none"; 
	  document.getElementById("offlineVehilces").style.display = "none"; 
  }
  function idleshow(event) {
	  var contentId = document.getElementById("idleVehilces");
      contentId.style.display = "block"; 
      // contentId.style.position = "absolute";
      //           contentId.style.left = event.clientX+'px';
       //                    contentId.style.top =(event.clientY+15)+'px';
      document.getElementById("runningVehilces").style.display = "none"; 
	  document.getElementById("allVehicls").style.display = "none"; 
	  document.getElementById("offlineVehilces").style.display = "none"; 
  }
  function offlineshow(event) {
	  var contentId = document.getElementById("offlineVehilces");
      contentId.style.display = "block"; 
      // contentId.style.position = "absolute";
       //          contentId.style.left = event.clientX+'px';
       ///                    contentId.style.top =(event.clientY+15)+'px';
      document.getElementById("runningVehilces").style.display = "none"; 
	  document.getElementById("idleVehilces").style.display = "none"; 
	  document.getElementById("allVehicls").style.display = "none"; 
  }
  
  function reporthide() {
	  var contentId = document.getElementById("report");
      contentId.style.display = "none"; 
     
    // document.getElementById("bottom").style.display = "block";
    //  document.getElementById("arrow").style.display = "block";
     // document.getElementById("arrow2").style.display = "block";
       // document.getElementById("arrow").style.top = "98%"; 
      //document.getElementById("runningVehilces").style.display = "none"; 
	  //document.getElementById("idleVehilces").style.display = "none"; 
	 // document.getElementById("offlineVehilces").style.display = "block"; 
  }
  function reportshow(){
//	  var contentId = document.getElementById("report");
//      contentId.style.display = "block";
    //  document.getElementById("arrow").style.display = "block";
    //  document.getElementById("bottom").style.display = "block";
     // document.getElementById("arrow2").style.display = "block";
    
     
  }
  
  function getOffline(status){
      document.getElementById('allreports').style.display="block";
      document.getElementById('reportarea').style.display="block";
      document.getElementById('mapcontainer').style.display="none";
      document.getElementById("availableVehicle").style.display = "none";
      document.getElementById("vehicleStatusReport").style.diplay = "none";  
      var formate ="html";//document.getElementById("reportFormate").value;
      var ac=window.localStorage.getItem('accountID');
      var uid=window.localStorage.getItem('userId');
      var groupdevice=document.getElementById('selectedgroup').value;
      var offline_current="offline";
      if(status=="status"){
          offline_current="";
      }
      if(status=="offroad"){
             offline_current="offroad";
       } 
      if(uid != "onitoring"){
           // window.open("/dashboard/php/vehiclesStatus.php?&accountID="+ac+"&userID="+uid+"&currentLocation=currentlocation&offline=offline&formate="+formate);
	   //	 window.open("php/vehiclesStatus.php?&accountID="+ac+"&userID="+uid+"&currentLocation=currentlocation&offline=offline&formate="+formate+"&selectedgroup="+groupdevice);
	   document.getElementById("reportarea").innerHTML=  "<div class='loader1'><center><img src='images/ajax-loader-original.gif' /><center></div>"; 
          $.ajax({
	       type : 'get',
	       url:'php/vehiclesStatus.php',
	       data:{"accountID":ac,"userID":uid,"currentLocation":"currentlocation","offline":offline_current,"selectedgroup":groupdevice},
	       dataType:'json',
	       success: function(data) {
	          $.each(data, function() {
	              $.each(data, function(key, value) {
	                  document.getElementById("reportarea").innerHTML = value;
	              });
	          });
	       },
	       error: function (request, status, error) {
	           document.getElementById("reportarea").innerHTML = '<a onclick=pdfc("CurrentStatus_Offline") > &nbsp&nbsp&nbsp<img src="images/pdf.png" width="30" height="30" title="Pdf">&nbsp&nbsp&nbsp </a><a onclick=excel("CurrentStatus_Offline")><img src="images/excel.png" width="30" height="30" title="Excel"> &nbsp&nbsp&nbsp</a><a onclick="printreport()"> <img src="images/printer.png" width="30" height="30" title="Print" > &nbsp&nbsp&nbsp</a>'+request.responseText;
               }
          });

      }
    
}


 function getOfflineDownload(){
                     var groupdevice=document.getElementById('groupdevice').value;

                     var formate ="html";//document.getElementById("reportFormate").value;
                     var ac=window.localStorage.getItem('accountID');
                     var uid=window.localStorage.getItem('userId');
                                                                      // window.open("/dashboard/php/vehiclesStatus.php?&accountID="+ac+"&userID="+uid+"&currentLocation=currentlocation&offline=offline&formate="+formate);
                                                                                       window.open("php/vehiclesStatus.php?&accountID="+ac+"&userID="+uid+"&currentLocation=currentlocation&offline=offline&formate=excel&selectedgroup="+groupdevice);


    }

function getOfflineNottrackingDownload(){
                  var formate ="html";//document.getElementById("reportFormate").value;
                  var ac=window.localStorage.getItem('accountID');
                   var uid=window.localStorage.getItem('userId');
                  var groupdevice=document.getElementById('groupdevice').value;
                  // window.open("/dashboard/php/vehiclesStatus.php?&accountID="+ac+"&userID="+uid+"&currentLocation=currentlocation&offline=offline&formate="+formate);
                                                                                                                                                       window.open("php/vehiclesStatus.php?&accountID="+ac+"&userID="+uid+"&currentLocation=currentlocation&offline=offlinenottracking&formate=excel&selectedgroup="+groupdevice);


    }
    function getOfflineNotRespondingDownload(){
                   var formate ="html";//document.getElementById("reportFormate").value;
                  var ac=window.localStorage.getItem('accountID');
                   var uid=window.localStorage.getItem('userId');
                   var groupdevice=document.getElementById('groupdevice').value;
                   // window.open("/dashboard/php/vehiclesStatus.php?&accountID="+ac+"&userID="+uid+"&currentLocation=currentlocation&offline=offline&formate="+formate);
                                                                                                                                                                     window.open("php/vehiclesStatus.php?&accountID="+ac+"&userID="+uid+"&currentLocation=currentlocation&offline=offlinenotresponding&formate=excel&selectedgroup="+groupdevice);


    }
function getOfflineDaterange(){
                    var formate ="html";//document.getElementById("reportFormate").value;
                   var ac=window.localStorage.getItem('accountID');
                   var groupdevice=document.getElementById('groupdevice').value;
                   var uid=window.localStorage.getItem('userId');

                                     // window.open("/dashboard/php/vehiclesStatus.php?&accountID="+ac+"&userID="+uid+"&currentLocation=currentlocation&offline=offline&formate="+formate);

         window.open("php/vehiclesStatus.php?&accountID="+ac+"&userID="+uid+"&currentLocation=currentlocation&offline=offlinedaterange&formate=excel&selectedgroup="+groupdevice);


    }


function getOnline(){
                 var formate ="html";//document.getElementById("reportFormate").value;
                 var ac=window.localStorage.getItem('accountID');
                 var uid=window.localStorage.getItem('userId');
                var groupdevice=document.getElementById('groupdevice').value;
                // window.open("/dashboard/php/vehiclesStatus.php?&accountID="+ac+"&userID="+uid+"&currentLocation=currentlocation&offline=offline&formate="+formate);
                       window.open("php/vehiclesStatus.php?&accountID="+ac+"&userID="+uid+"&currentLocation=currentlocation&offline=online&formate=excel&selectedgroup="+groupdevice);


    }
function getRunning(){
                 var formate ="html";//document.getElementById("reportFormate").value;
                 var ac=window.localStorage.getItem('accountID');
                 var uid=window.localStorage.getItem('userId');
                 var groupdevice=document.getElementById('groupdevice').value;
                 // window.open("/dashboard/php/vehiclesStatus.php?&accountID="+ac+"&userID="+uid+"&currentLocation=currentlocation&offline=offline&formate="+formate);
                 window.open("php/vehiclesStatus.php?&accountID="+ac+"&userID="+uid+"&currentLocation=currentlocation&offline=running&formate=excel&selectedgroup="+groupdevice);


    
}

function getIdle(){
                 var formate ="html";//document.getElementById("reportFormate").value;
                 var ac=window.localStorage.getItem('accountID');
                 var uid=window.localStorage.getItem('userId');
                var groupdevice=document.getElementById('groupdevice').value;
                // window.open("/dashboard/php/vehiclesStatus.php?&accountID="+ac+"&userID="+uid+"&currentLocation=currentlocation&offline=offline&formate="+formate);
                               window.open("php/vehiclesStatus.php?&accountID="+ac+"&userID="+uid+"&currentLocation=currentlocation&offline=idle&formate=excel&selectedgroup="+groupdevice);


    }

function statusReportDownload(){
                var formate ="html";//document.getElementById("reportFormate").value;
                 var ac=window.localStorage.getItem('accountID');
                var uid=window.localStorage.getItem('userId');
                var groupdevice=document.getElementById('groupdevice').value;
                // window.open("/dashboard/php/vehiclesStatus.php?&accountID="+ac+"&userID="+uid+"&currentLocation=currentlocation&formate="+formate);
                                window.open("php/vehiclesStatus.php?&accountID="+ac+"&userID="+uid+"&currentLocation=currentlocation&formate=excel&selectedgroup="+groupdevice);


        }

	function statusReport(){
/*	  document.getElementById('allreports').style.display="block";
	 document.getElementById('reportarea').style.display="block";
	 document.getElementById('mapcontainer').style.display="none";
	 document.getElementById("availableVehicle").style.display = "none";
         document.getElementById("vehicleStatusReport").style.diplay = "none";
  */       var formate ="html";//document.getElementById("reportFormate").value;
	var ac=window.localStorage.getItem('accountID');
	 var uid=window.localStorage.getItem('userId');
         var groupdevice=document.getElementById('groupdevice').value;
     window.open("php/vehiclesStatus.php?&accountID="+ac+"&userID="+uid+"&currentLocation=currentlocation&formate="+formate+"&selectedgroup="+groupdevice);

		
	}

    function distanceIdlereport(id){
        
 /*     document.getElementById('allreports').style.display="block";
       document.getElementById('reportarea').style.display="block";
       document.getElementById('mapcontainer').style.display="none";
       document.getElementById("availableVehicle").style.display = "none";
       document.getElementById("vehicleStatusReport").style.diplay = "none";
  */    var formate ="html";//document.getElementById("reportFormate").value;
        var ac=window.localStorage.getItem('accountID');
        var uid=window.localStorage.getItem('userId');
        var tDate = document.getElementById('toDate').value;
        var fDate = document.getElementById('fromDate').value;
          var epochtdate=datestringToEpochDB(tDate);
       var epochfdate=datestringToEpochDB(fDate);
        var diffepoch=epochtdate-epochfdate;

        var toDate=tDate.split(" ")[0];
        var toTime=tDate.split(" ")[1];
        var fromDate=fDate.split(" ")[0];
        var fromTime=fDate.split(" ")[1];
        var vehicle = document.getElementById('vehicles').value;
        var group = document.getElementById('selectedgroup').value;
       
         var idletime = document.getElementById('idletime').value;
          var offlinetime = document.getElementById('offlinetime').value;
         var reportType=document.getElementById('reportType').value;
       document.getElementById("reportarea").innerHTML=  "<div class='loader1'><center><img src='images/ajax-loader-original.gif' /><center></div>";
    //  window.open("../rjdistanceidlereport/distanceidlereport.php?&accountID="+ac+"&userID="+uid+"&currentLocation=currentlocation&formate="+formate+"&selectedgroup="+groupdevice) 
        var url="php/distanceIdle.php"
        if(id.value=="Predefined Report"){ url="php/distanceIdleFromDB.php";}
       if(id.value=="Predefined Report" || (group!="selectall" && diffepoch<=86400)){
          $.ajax({
             type : 'get',
                  url:url,
                     data:{"vehicleID":vehicle, "accountID":ac,"fromDate":fromDate,"toDate":toDate,"userID":uid,"group":group,"fromTime":fromTime,"toTime":toTime,"reportFormate":"html","idletime":idletime,"reportType":reportType,"offlinetime":offlinetime},
                        dataType:'json',
                             success: function(data) {
                                 $.each(data, function() {
                                     $.each(data, function(key, value) {
                                          document.getElementById("reportarea").innerHTML = value;                    
                                      });
                                  });
                             },
                            error: function (request, status, error) {
                               document.getElementById("reportarea").innerHTML = '<a onclick=pdfc("DistanceIdle") > &nbsp&nbsp&nbsp<img src="images/pdf.png" width="30" height="30" title="Pdf">&nbsp&nbsp&nbsp </a><a onclick=excel("DistanceIdle")><img src="images/excel.png" width="30" height="30" title="Excel"> &nbsp&nbsp&nbsp</a><a onclick="printreport()"> <img src="images/printer.png" width="30" height="30" title="Print" > &nbsp&nbsp&nbsp</a>'+request.responseText;
                            }
                                          
          });
       }else{
                if(vehicle=="selectall" || group=="selectall")
                  document.getElementById("reportarea").innerHTML="select Group Or Vehicle";
                if(fromDate!=toDate)  
                    document.getElementById("reportarea").innerHTML="Date Range Should be in 24 Hrs";

          }



    }
 function getHighways(id){
     var ac=window.localStorage.getItem('accountID');
     $.ajax({
             type : 'get',
                  url:"php/highwayinfo.php",
                     data:{"accountID":ac},
                        dataType:'json',
                             success: function(data) {
                                 $.each(data, function() {
                                     $.each(data, function(key, value) {
                                          document.getElementById("reportarea").innerHTML = value;                    
                                      });
                                  });
                             },
                            error: function (request, status, error) {
                               document.getElementById("reportarea").innerHTML ='<a onclick=pdfc("Highway") > &nbsp&nbsp&nbsp<img src="images/pdf.png" width="30" height="30" title="Pdf">&nbsp&nbsp&nbsp </a><a onclick=excel("Highway")><img src="images/excel.png" width="30" height="30" title="Excel"> &nbsp&nbsp&nbsp</a><a onclick="printreport()"> <img src="images/printer.png" width="30" height="30" title="Print" > &nbsp&nbsp&nbsp</a>'+request.responseText;
                            }
                                          
          });
 


}
    function rawdata(id){
         var ac=window.localStorage.getItem('accountID');
         var uid=window.localStorage.getItem('userId');
         var vehicle = document.getElementById('vehicles').value;
         var group = document.getElementById('selectedgroup').value;
         var tDate = document.getElementById('toDate').value;
         var fDate = document.getElementById('fromDate').value;
        
        var epochtdate=datestringToEpochDB(tDate);
       var epochfdate=datestringToEpochDB(fDate);
        var diffepoch=epochtdate-epochfdate;
        var filename="EventLog";
         var url="php/rawEventData.php";
         var reportrequest=id.value;
         var idletime=0;
         var stoppage=0;
         var timeinterval=0;
         var overspeed=0;
         var ignition='no';
         var mainpower='no';
         if(reportrequest=="Stoppage"){ filename=reportrequest;
            idletime=document.getElementById('idletime').value;
         }else if(reportrequest=="Event Log"){
               filename="EventLog";
             if(document.getElementsByName("multicheck")[0].checked) stoppage=document.getElementById(document.getElementsByName("multicheck")[0].value).value
             if(document.getElementsByName("multicheck")[1].checked) timeinterval=document.getElementById(document.getElementsByName("multicheck")[1].value).value
             if(document.getElementsByName("multicheck")[2].checked) overspeed=document.getElementById(document.getElementsByName("multicheck")[2].value).value 
              if(document.getElementsByName("multicheck")[3].checked) ignition="yes";
             if(document.getElementsByName("multicheck")[4].checked)  mainpower="yes";
         }
        var toDate=tDate.split(" ")[0];
        var fromDate=fDate.split(" ")[0]
       if(group !="selectall" && vehicle!="selectall" && diffepoch<=86400){
         document.getElementById("reportarea").innerHTML=  "<div class='loader1'><center><img src='images/ajax-loader-original.gif'/></center></div>";

          $.ajax({
             type : 'get',
                   url:url,
                           data:{"vehicleID":vehicle, "accountID":ac,"fromDate":fDate,"toDate":tDate,"userID":uid,"group":group,"reportrequest":reportrequest,"idletime":idletime,"stoppage":stoppage,"timeinterval":timeinterval,"overspeed":overspeed,"ignition":ignition,"mainpower":mainpower},
                                  dataType:'json',
                                         success: function(data) {
                                              $.each(data, function() {
                                                   $.each(data, function(key, value) {
                                                         document.getElementById("reportarea").innerHTML = value;                    
                                                    });
                                               });
                                         },
                                         error: function (request, status, error) {
                                                  document.getElementById("reportarea").innerHTML ='<a onclick=pdfc("'+filename+'") > &nbsp&nbsp&nbsp<img src="images/pdf.png" width="30" height="30" title="Pdf">&nbsp&nbsp&nbsp </a><a onclick=excel("'+filename+'")><img src="images/excel.png" width="30" height="30" title="Excel"> &nbsp&nbsp&nbsp</a><a onclick="printreport()"> <img src="images/printer.png" width="30" height="30" title="Print" > &nbsp&nbsp&nbsp</a>'+request.responseText;
                                                     }
                                          
            });

       }else{
                if(vehicle=="selectall" || group=="selectall")
                  document.getElementById("reportarea").innerHTML="select Group Or Vehicle";
                if(fromDate!=toDate)  
                    document.getElementById("reportarea").innerHTML="Date Range Should be in 24 Hrs";

          }


    }

	function inmotion(buttontype){
                $("#flags").hide();
               $("#vehicleStatusReport").hide();
              // document.getElementById('legend').style.display='none';
	       document.getElementById('allreports').style.display="block";
	       document.getElementById('reportarea').style.display="block";
	       document.getElementById('mapcontainer').style.display="none";
	       document.getElementById("availableVehicle").style.display = "none";
	       document.getElementById("vehicleStatusReport").style.diplay = "none";
	       var formate ="html";//document.getElementById("reportFormate").value;
	       var ac=window.localStorage.getItem('accountID');
	       var uid=window.localStorage.getItem('userId');
	       var buttons=["zonecrossed","zonereport","highway","nocasesreport","pdfreport","stoppage","offroadreport","offlinereport","ignition","powercutlog","overspeed","currentstatusreport","inmotion","daywisestatus","historytracking","livevehicletracking","eventlog","rawdata","distanceidle","casemanagerreport"];
	       for(var x=0;x<buttons.length;x++){
                   if(buttons[x]!=buttontype){
                      document.getElementById(buttons[x]).style.display="none";
                    }else{
                      document.getElementById(buttons[x]).style.display="block";
                    }


                }
              document.getElementById("dynamicdistanceidlereport").style.display="none";
              if(buttontype=="stoppage"){
                  document.getElementById("reportheading").innerHTML="Stoppage Report Form";
 
               }else if(buttontype=="offlinereport"){
                   document.getElementById("reportheading").innerHTML="Offline Report Form";
               }else if(buttontype=="offroadreport"){
                   document.getElementById("reportheading").innerHTML="Offroad Report Form";
               }else if(buttontype=="powercutlog"){
                    document.getElementById("reportheading").innerHTML="Powercut Log Report Form";
               }else if(buttontype=="ignition"){
                    document.getElementById("reportheading").innerHTML="Ignition Report Form";
               }
                 else if(buttontype=="overspeed"){
                     document.getElementById("reportheading").innerHTML="Over Speed Report Form";
               }else if(buttontype=="currentstatusreport"){ 
                      document.getElementById("reportheading").innerHTML="All Vehicles Status Report Form";
               }else if(buttontype=="inmotion"){
                      document.getElementById("reportheading").innerHTML="Vehicle Log Report Form";
               }else if(buttontype=="daywisestatus"){
                      document.getElementById("reportheading").innerHTML="Day Wise Vehicles Status Report Form";
               }else if(buttontype=="historytracking"){
                      document.getElementById("reportheading").innerHTML="Vehicle History Tracking Form";
               }else if(buttontype=="livevehicletracking"){
                       document.getElementById("reportheading").innerHTML="Vehicle Live Tracking";
               }else if(buttontype=="eventlog"){    
                        document.getElementById("reportheading").innerHTML="Vehicle Event Log";
               }else if(buttontype=="rawdata"){
                      document.getElementById("reportheading").innerHTML="Vehicle Raw Details";
               }else if(buttontype=="distanceidle"){
                       document.getElementById("dynamicdistanceidlereport").style.display="block";
                      document.getElementById("reportheading").innerHTML="Distance Idle Report Form";
               }else if(buttontype=="casemanagerreport"){
                      document.getElementById("reportheading").innerHTML="Case Manager Report";
               }
               else if(buttontype=="pdfreport"){
                      document.getElementById("reportheading").innerHTML="PDF/Excel Reports";
               }
              else if(buttontype=="nocasesreport"){
                      document.getElementById("reportheading").innerHTML="No Case Vehicles";
               }
               else if(buttontype=="highway"){
                      document.getElementById("reportheading").innerHTML="High Ways";
               }
               else if(buttontype=="zonereport"){
                    document.getElementById("reportheading").innerHTML="Zone Report";
               }
                else if(buttontype=="zonecrossed"){
                    document.getElementById("reportheading").innerHTML="Zone Crossed Report";
               }


              document.getElementById("stoppageID").style.display="none";
              document.getElementById("timeintervalID").style.display="none";
              document.getElementById("overspeedID").style.display="none";
              document.getElementById("ignitionID").style.display="none";
                document.getElementById("pdfexcelID").style.display="none";
                document.getElementById("stoppageID").style.display="none";  
               document.getElementById("listgridID").style.display="none";
                document.getElementById("idletimeID").style.display="none";
                document.getElementById("offlinetimeID").style.display="none";
                document.getElementById("reporttypeID").style.display="none"
               document.getElementById("pdfreporttypeID").style.display="none"
               if(buttontype=="offlinereport" || buttontype=="currentstatusreport" || buttontype=="offroadreport"){
                  document.getElementById("fromDateID").style.display="none";
                  document.getElementById("toDateID").style.display="none";
                  document.getElementById("vehiclesIDs").style.display="none";
                  document.getElementById("selectedgroupID").style.display="block";
               }else if(buttontype=="daywisestatus"){
                   document.getElementById("fromDateID").style.display="block";
                  document.getElementById("toDateID").style.display="block";
                   document.getElementById("vehiclesIDs").style.display="none";
                      document.getElementById("selectedgroupID").style.display="none"; 
              }else if(buttontype=="livevehicletracking" || buttontype=="historytracking"){

                      document.getElementById("fromDateID").style.display="none";
                  document.getElementById("toDateID").style.display="none";
                  document.getElementById("vehiclesIDs").style.display="block";
                  document.getElementById("selectedgroupID").style.display="block";

                   if(buttontype=="historytracking"){
                       //document.getElementById("stoppageID").style.display="block";
                      // document.getElementById("timeintervalID").style.display="block";
                      // document.getElementById("overspeedID").style.display="block";
                      // document.getElementById("ignitionID").style.display="block"
                  }
              }else{
                 
                  document.getElementById("vehiclesIDs").style.display="block";
                  document.getElementById("fromDateID").style.display="block";
                  document.getElementById("toDateID").style.display="block";
                   document.getElementById("selectedgroupID").style.display="block";
                  if(buttontype=="distanceidle"){
                      document.getElementById("idletimeID").style.display="block";
                      document.getElementById("offlinetimeID").style.display="block";
                      document.getElementById("reporttypeID").style.display="block"  
                   }
                    if(buttontype=="stoppage"){
                         
                         document.getElementById("idletimeID").style.display="block";
                     }
                   if(buttontype=="eventlog"){
                       document.getElementById("stoppageID").style.display="block";
              document.getElementById("timeintervalID").style.display="block";
              document.getElementById("overspeedID").style.display="block";
              document.getElementById("ignitionID").style.display="block";

                    }
                    if(buttontype=="pdfreport"){
                          document.getElementById("pdfexcelID").style.display="block";
                           document.getElementById("listgridID").style.display="block";
                          document.getElementById("pdfreporttypeID").style.display="block";
                    }
                   if(buttontype=="pdfreport" || buttontype=="nocasesreport"){
                     document.getElementById("vehiclesIDs").style.display="none";
                    }
                    if(buttontype=="highway"){
                       document.getElementById("vehiclesIDs").style.display="none";
                       document.getElementById("selectedgroupID").style.display="none";
                       document.getElementById("fromDateID").style.display="none";
                       document.getElementById("toDateID").style.display="none";
                    }
               }
                   
               document.getElementById("reportarea").innerHTML = "";
   
             //  document.getElementById("selectedgroupID").style.display="block";
	        //   window.open("php/InmotionReport.php?&accountID="+ac+"&userID="+uid+"&formate="+formate);
		
	}
function vehicleTracking(button){
   var vehicleID=document.getElementById("vehicles").value;
   if(vehicleID=="selectall"){
       alert("Please select vehicle");
    }else{
        var ac=window.localStorage.getItem('accountID');
       var w=900;
       var h=400;
       var Account=window.localStorage.getItem('accountID');
          var left = (screen.width/2)-(w/2);
         var topp = (screen.height/2)-(h/2);
     window.open ("php/liveTracking.php?live=yes&accountID="+ac+"&vehicleID="+vehicleID+"&onlyidles=no","Route Tracking",'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+topp+', left='+left);
       // window.open("php/liveTracking.php?live=yes&accountID="+ac+"&vehicleID="+vehicleID);
   }
}
function historyTracking(button){
      var vehicleID=document.getElementById("vehicles").value;
      var groupID=document.getElementById("selectedgroup").value;

      var stoppage=0;
      var timeinterval=0;
      var overspeed=0;
      var ignition='no';
      var mainpower='no';
      if(document.getElementsByName("multicheck")[0].checked) stoppage=document.getElementById(document.getElementsByName("multicheck")[0].value).value
      if(document.getElementsByName("multicheck")[1].checked) timeinterval=document.getElementById(document.getElementsByName("multicheck")[1].value).value
      if(document.getElementsByName("multicheck")[2].checked) overspeed=document.getElementById(document.getElementsByName("multicheck")[2].value).value 
      if(document.getElementsByName("multicheck")[3].checked) ignition="yes";
      if(document.getElementsByName("multicheck")[4].checked)  mainpower="yes";
  

      if(vehicleID=="selectall"){
          alert("Please select vehicle");
      }else{
      //    routeTracking(vehicleID);
          var w=1500;
          var h=700;
          var Account=window.localStorage.getItem('accountID');
          var left = (screen.width/2)-(w/2);
          var topp = (screen.height/2)-(h/2);
                 window.open ("php/tripMap.php?accountID="+Account+"&vehicleID="+vehicleID+"&onlyidles=no"+"&stoppage="+stoppage+"&timeinterval="+timeinterval+"&overspeed="+overspeed+"&ignition="+ignition+"&mainpower="+mainpower+"&groupID="+groupID,"Route Tracking",'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+topp+', left='+left);

      }

}	
 
function getZoneCrossedReport(button){
         var toDate = document.getElementById('toDate').value;
     var fromDate = document.getElementById('fromDate').value;
        
    var epochtdate=datestringToEpochDB(toDate);
       var epochfdate=datestringToEpochDB(fromDate);
        var diffepoch=epochtdate-epochfdate;


     var Account=window.localStorage.getItem('accountID');
     var userID=window.localStorage.getItem('userId');
     var vehicle = document.getElementById('vehicles').value;
     var group = document.getElementById('selectedgroup').value;
     var FromDate = datestringToEpochDB(fromDate);
     var ToDate =  datestringToEpochDB(toDate);
     var speedlimit = 0;// document.getElementById('overspeedlimit').value;
     //   var speedlimit=prompt("Enter Maximum Speed Limit");
     // alert(vehicle+Account+FromDate+ToDate+userID+group+speedlimit );
   // alert(fromDate.split(" ")[0]);
     $('.load_image1').show();
     var filename="";
    var url="php/getzonecrossedreport.php";
       document.getElementById("reportarea").innerHTML=  "<div class='loader1'><center><img src='images/ajax-loader-original.gif' /><center></div>";
    if(button.value=="Ignition" || button.value=="Day wise status report" || button.value=="Case Manager Report" || button.value=="Zone Report"  || button.value=="No Cases Data" || ( diffepoch<=86400 )){ 
       $.ajax({
             type : 'get',
                   url:url,
                           data:{"vehicle":vehicle, "accountID":Account,"fromDate":FromDate,"toDate":ToDate,"userID":userID,"group":group,"speedlimit":speedlimit,"formate":"formate"},
                                  dataType:'json',
                                         success: function(data) {
                                              $.each(data, function() {
                                                   $.each(data, function(key, value) {
                                                         document.getElementById("reportarea").innerHTML = value;                    
                                                    });
                                               });
                                         },
                                         error: function (request, status, error) {
                                                  document.getElementById("reportarea").innerHTML ='<a onclick=pdfc("'+filename+'") > &nbsp&nbsp&nbsp<img src="images/pdf.png" width="30" height="30" title="Pdf">&nbsp&nbsp&nbsp </a><a onclick=excel("'+filename+'")><img src="images/excel.png" width="30" height="30" title="Excel"> &nbsp&nbsp&nbsp</a><a onclick="printreport()"> <img src="images/printer.png" width="30" height="30" title="Print" > &nbsp&nbsp&nbsp</a>'+request.responseText;
                               
                                                     }
                                          
            });
    $('.load_image1').hide();
  }else{
        
       if(fromDate.split(" ")[0]!=toDate.split(" ")[0])
         document.getElementById("reportarea").innerHTML = "Date Range should be in 24 Hrs";

   }
}



 
 function getOverSpeedReport1(button){
     var toDate = document.getElementById('toDate').value;
     var fromDate = document.getElementById('fromDate').value;
        
    var epochtdate=datestringToEpochDB(toDate);
       var epochfdate=datestringToEpochDB(fromDate);
        var diffepoch=epochtdate-epochfdate;


     var Account=window.localStorage.getItem('accountID');
     var userID=window.localStorage.getItem('userId');
     var vehicle = document.getElementById('vehicles').value;
     var group = document.getElementById('selectedgroup').value;
     var FromDate = datestringToEpochDB(fromDate);
     var ToDate =  datestringToEpochDB(toDate);
     var speedlimit = 0;// document.getElementById('overspeedlimit').value;
     //   var speedlimit=prompt("Enter Maximum Speed Limit");
     // alert(vehicle+Account+FromDate+ToDate+userID+group+speedlimit );
   // alert(fromDate.split(" ")[0]);
     $('.load_image1').show();
     var filename="";
     var url="php/OverSpeedReport.php";
     if(button.value=="Log Report"){
         filename="Log";
        url="php/ReportForInMotion.php";
      }else if(button.value=="Ignition"){
        filename="Ignition";
        url="php/ignitiononoff.php";
      }
     else if(button.value=="Power cut Log"){
        filename="Powercut";
        url="php/powercutlog.php";
      }else if(button.value=="Day wise status report"){
         filename="DayWiseStatus";
        url="php/vehiclesStatusReportDaywise.php";
      }else if(button.value=="Case Manager Report"){
         filename="CaseManger";
        url="php/casemanagementReport.php";
      }else if(button.value=="No Cases Data"){
         filename="NoCaseData";
         url="php/casemanagementReportNocases.php";
      }
      if(button.value=="Over Speed"){
           filename="OverSpeed";
           speedlimit=prompt("Enter Maximum Speed Limit");
            if(isNaN(speedlimit)){
               alert(" Enter Numeric Value");
               return;
             }
      }
      if(button.value=="Zone Report"){
           url="php/getzonereport.php";
      }   


     document.getElementById("reportarea").innerHTML=  "<div class='loader1'><center><img src='images/ajax-loader-original.gif' /><center></div>";
    if(button.value=="Ignition" || button.value=="Day wise status report" || button.value=="Case Manager Report" || button.value=="Zone Report"  || button.value=="No Cases Data" || (group!="selectall" && diffepoch<=86400 )){ 
       $.ajax({
             type : 'get',
                   url:url,
                           data:{"vehicle":vehicle, "accountID":Account,"fromDate":FromDate,"toDate":ToDate,"userID":userID,"group":group,"speedlimit":speedlimit,"formate":"formate"},
                                  dataType:'json',
                                         success: function(data) {
                                              $.each(data, function() {
                                                   $.each(data, function(key, value) {
                                                         document.getElementById("reportarea").innerHTML = value;                    
                                                    });
                                               });
                                         },
                                         error: function (request, status, error) {
                                                  document.getElementById("reportarea").innerHTML ='<a onclick=pdfc("'+filename+'") > &nbsp&nbsp&nbsp<img src="images/pdf.png" width="30" height="30" title="Pdf">&nbsp&nbsp&nbsp </a><a onclick=excel("'+filename+'")><img src="images/excel.png" width="30" height="30" title="Excel"> &nbsp&nbsp&nbsp</a><a onclick="printreport()"> <img src="images/printer.png" width="30" height="30" title="Print" > &nbsp&nbsp&nbsp</a>'+request.responseText;
                               
                                                     }
                                          
            });
    $('.load_image1').hide();
  }else{
       if(group=="selectall")
        document.getElementById("reportarea").innerHTML = "Select Group";
        
       if(fromDate.split(" ")[0]!=toDate.split(" ")[0])
         document.getElementById("reportarea").innerHTML = "Date Range should be in 24 Hrs";

   }
}




	function Showandhide(){


                 $("#report").slideToggle("slow");
                /*   $("#report").animate({
                         marginLeft: parseInt($("#report").css('marginLeft'),10) == 0 ?
                                 $("#report").outerWidth() :
                                         0
                                             });;
*/
}
//var x=0;
  function searchresultshowhide(){
                    /* if(x==0){
                             x=1;
                        document.getElementById("searchresult").style.left = "49%";
                         document.getElementById("searchresult").style.top = "26%";   
                      }else{
                         x=0;
                             document.getElementById("searchresult").style.left = "49%";
                                                      document.getElementById("searchresult").style.top = "94%";  
                      }*/
              //    $("#vehicleStatusReport").slideToggle("slow");
//            $("#vehicleStatusReport").toggle("slide");
  //                             $("#vehicleStatusReport").animate({marginTop:"0px"}, 200);
//$("#availableVehicle").toggle("slide");
                //  $("#availableVehicle").slideToggle("slide")
}

function cmgtshowhide(){
        $("#casemanagement").slideToggle("slow");
}


function searchhide(){
   /*if(document.getElementById("availableVehicle").style.display=="block"){
      document.getElementById("availableVehicle").style.top = "16.9%";
      document.getElementById("vehicleStatusReport").style.top = "17%";
      document.getElementById("vehicleStatusReport").style.height = "80.5%";
      document.getElementById("availableVehicle").style.display="none";
   }else{
       document.getElementById("vehicleStatusReport").style.top = "22%";
        document.getElementById("availableVehicle").style.top = "16.9%";
       document.getElementById("availableVehicle").style.display="block";
   }*/
}

function searchdisplay() {
document.getElementById("vehicleStatusReport").style.top = "22%";
        document.getElementById("availableVehicle").style.top = "16.9%";
       document.getElementById("availableVehicle").style.display="block";

}

function linveshowandhide(){


          $("#flags").slideToggle("slow");


}
function sessionDateSet(){
      sessionDate=new Date();


}

function getPDFFile(id){
     var toDate = document.getElementById('toDate').value;
     var fromDate = document.getElementById('fromDate').value;
     var Account=window.localStorage.getItem('accountID');
     var userID=window.localStorage.getItem('userId');
     var group = document.getElementById('selectedgroup').value;
     var pdfexcel = document.getElementById('pdfexcel').value;
     var view = document.getElementsByName('view');
      var viewvalue='';
     for(var z=0;z<view.length;z++){
         if(view[z].checked){
               viewvalue=view[z].value;
               break;
         }
     }
    var pdfreportType = document.getElementById('pdfreportType').value;
     //window.open (" http://up108.glovision.co/images/arrows.png");
     $('.load_image1').show();
     
     $.ajax({
          type : 'get',
          url:"php/showpdffiles.php",
          data:{"accountID":Account,"fromDate":fromDate,"toDate":toDate,"userID":userID,"group":group,"pdfreportType":pdfreportType,"pdfexcel":pdfexcel,"viewstyle":viewvalue},
          dataType:'json',
          success: function(data) {
              $.each(data, function() {
                  $.each(data, function(key, value) {
                      document.getElementById("reportarea").innerHTML = value;
                   });
              });
          },
          error: function (request, status, error) {
               document.getElementById("reportarea").innerHTML ='<a onclick=pdfc("Pdf") > &nbsp&nbsp&nbsp<img src="images/pdf.png" width="30" height="30" title="Pdf">&nbsp&nbsp&nbsp </a><a onclick=excel("Pdf")><img src="images/excel.png" width="30" height="30" title="Excel"> &nbsp&nbsp&nbsp</a><a onclick="printreport()"> <img src="images/printer.png" width="30" height="30" title="Print" > &nbsp&nbsp&nbsp</a>'+request.responseText;

          }

   });

    $('.load_image1').hide();
  
}
 function downloadexcel(filename){
    window.open("php/DownLoadExcel.php?f="+filename);
}

   </script>
</script>
   
<body background="images/background.jpg" onload="allhide();reportshow();" onMousemove="sessionDateSet();">
<img id='liveimg' src="images1/online.png" style="display:none"/>

<!--
     
         <div id="allVehicls" onMouseOut="allhide()" onMouseOver='allshow()'></div>
         <div id="runningVehilces" onMouseOut="allhide()" onMouseOver='runningshow()'></div>
         <div id="idleVehilces" onMouseOut="allhide()" onMouseOver='idleshow()'></div>
         <div id="offlineVehilces" onMouseOut="allhide()" onMouseOver='offlineshow()'></div>
         -->
  <div  id="vehicleStatusReport">
                    <div class='loader1'><center><center></div>
                    </div>

       <div id="logout" onMouseOver="this.style.opacity=1" onMouseOut="this.style.opacity=1;" >
       <table align="center"   style="opacity:1;border-radius:10px;width:100%">

          <tr><th>  <img src="images/logo1.png" height='100px'  ></th>
          <!--  <th> 
            <font color="white"><u style="color:white;"><a onclick="back()" rel="external" style="color:white;">Back</a></u>  </font>
             </th> -->
                 <!--  <div class="accountDiv" align="left" id="acc1"><input type="button" id="reset" value="Reset Map"/></div>-->
            <!-- <th align="right"><input  type="button" id="resetallroups" value="Reset" onClick="document.getElementById('groupdevice').value='selectall'; showorhidemarkers='hide';window.localStorage.setItem('showmap','all'); maploadcount=0;allVehicleStatus();" class="button-secondary pure-button" /><input  type="button" id="reset" value="All" onMouseOver='allshow(event)' class="button-secondary pure-button" /><input type="button" id="online" value="Running" onMouseOver='runningshow(event)' class="button-success pure-button"/><input type="button" id="idle" value="Idle" onMouseOver='idleshow(event)' class='button-error pure-button'/><input type="button" id="offline1" value="Offline" onMouseOver='offlineshow(event)' class="button-warning"/><input type="button" id="MapClear" value="Clear Map" class='button-clear'/></th>                                  
             -->
       <!--    <th align="left">-->
  <!--         <a href="php/migrate.php?accountID=gvk-up-108&userID=<?php echo $userID ?>"><img id='gvk-up-108' src="images/108.png" width="80px" height="80px" style="border-radius:50px;"></a> &nbsp&nbsp&nbsp&nbsp<a href="php/migrate.php?accountID=gvk-up-102&userID=<?php echo $userID ?>" ><img  id='gvk-up-102' src="images/102.png" width="80px" height="80px" style="border-radius:50px"></a> &nbsp&nbsp&nbsp<a href="php/migrate.php?accountID=als&userID=<?php echo $userID ?>"><img id='als' src="images/als.png" width="80px" height="80px" style="border-radius:50px"></a>
  -->
<!--<input type="button" id="selectingGroupID" value="All ">&nbsp&nbsp<input type="button" id="online" value="(0)" />>0 kmph<img src="images/on.gif"> 
           <input type="button" id="idle" value="(0)" />=0 kmph<img src="images/off.gif">
           <input type="button" id="offline1" value="(0)" />=offline<img src="images/NotRpt.gif"> 
              <input  type="button" id="reset" value="All" class="button-secondary pure-button" />
-->
  
  <!--     </th> -->
     <th>
       <div class="demo">
      <div class="demo-1" id="online" onclick="xyz()" data-percent="65" value='25'></div>
      <div class="demo-2" id="idle" data-percent="65" value='25'></div>
      <div class="demo-3" id="offline1" data-percent="65" value='25'></div>
      <div class="demo-5" id="offroad" data-percent="65" value='25'></div>
      <div class="demo-4" id="reset1" data-percent="65" value='25'></div>
       </div>
    </th>
 
     <th align="right"> <img src="images/gvk-emri_527.png" height='50px'> <br><span style="font-size:10px;color:black;font-family: 'Trebuchet MS', Verdana, Arial, Helvetica, sans-serif;"><label id="Name"></label> </span>
    </th> 
          
        </tr>
     <!--   <tr style="border-bottom:1pt solid black;">
            <th align="left" style="border-bottom:1pt solid black;" colspan="2">
                                <div id="menucase">

  <div id="styletwo">

    <ul>

      <li><a href="" class="current">Home</a></li>


      <li><a onclick="linveshowandhide();">Live Stats</a></li>

      <li><a onclick="getOffline()">Offline</a></li>

      <li><a onclick="inmotion()">Over Speed</a></li>
      <li><a onclick="statusReport()">Current Status</a></li>

      <li><a onclick="inmotion()">Inmotion</a></li>

<li>
            <a href="#">Contry</a>
                            <ul> <li>India</li> <li>Shree lanka </li> <li>Bangaladesh</li> <li>England</li> </ul>
                                        </li>






      <li>       <a class="changeBlue"  onclick="textview();"> <img src="images/textView.png" width="20" height="20">&nbsp&nbsp&nbsp</a></li>
         <li>   <a class="changeBlue" onclick="mapview();"><img src="images/mapView.png" width="20" height="20">&nbsp&nbsp&nbsp</a></li>
          <li>  <a class="changeBlue"  onclick="bothview();"><img src="images/bothView.png" width="20" height="20"></a></li>
</ul>
</div>
            </th>
     
      </tr>-->
       </table>
      
       </div>
       <!--  bottom div -->
      <!--    <div id="arrow2" onclick="reporthide()"></div>-->
<!--
       <div id="report" >
         <center>
         <table>
                <tr><th><input style="height:30px;width:200px" type="button" id="overspeed" value="Over-Speed"  class="button-secondary1 pure-button"  onclick='inmotion();'/></th>
                </tr><tr>
              <th><input style="height:30px;width:200px" type="button" id="offline" value="Offline Vehicles" class="button-secondary1 pure-button" onclick='getOffline();'/></th>
          </tr><tr>    <th><input style="height:30px;width:200px" type="button" id="online1" value="Current Status" class="button-secondary1 pure-button" onclick='statusReport();'/></th>
             </tr><tr> <th><input style="height:30px;width:200px" type="button" id="inmotion" value="Inmotion Report" class="button-secondary1 pure-button" onclick='inmotion();'/></th>
        </tr><tr>      <th><input style="height:30px;width:200px"  type="button" id="vehiclehealth" value=" Health Status " class="button-secondary1 pure-button" onclick='inmotion();'/><input type="button" id="invoice" value="Invoice "  class='buttons' onclick='inmotion();'/></th>
            </tr><tr>  <th><select id='reportFormate' style="height:30px;width:200px" class="button-secondary1 pure-button"><option value='html'>HTML</option><option value='word'>Word</option><option value='excel'>Excel</option></select></th>
          </tr>
          </table>
          </center>
       </div>
    -->   
    <!--       <div id="bottom" onMouseOver="this.style.opacity=1" onMouseOut="this.style.opacity=1;" class='hello'>
 <br>
      
       </div> -->
    <!--       <div id="arrow" onClick="reportshow()">
  
      
       </div>-->
               <!--  div 0 -->
                <div  id="mapcontainer" class="mapcontainer" >   </div>
                <div class="load_image1" align="center">
                      <img src="images/ajax-loader-original.gif"/> 
                </div>

             <div  id="availableVehicle" ><table><thead><tr>

<td><input id='divisionstext' name='filter'  style="width:60%" type='text' placeholder='Divisions Search'>  <select style="width: 20px;" id='divisions' onChange='document.getElementById("divisionstext").value=this.value;groupsload();document.getElementById("ad").value="";document.getElementById("ad2").value="";document.getElementById("svehicle").value="";refreshstatus();availabilityVehicles()' ><option value='selectall'>selectall</option></select></td>
<td><input id='ad2' name='filter' onkeyup='filter(this,1)'  type='text' style="width:60%;" placeholder='District Search'> <select style="width: 20px;" id='groupdevice' onChange='document.getElementById("ad2").value=this.value;document.getElementById("svehicle").value="";document.getElementById("ad").value="";window.localStorage.setItem("showmap","all");searchresultshowhide();allVehicleStatus();filter(this,1);availabilityVehicles()' ><option value='selectall'>selectall</option></select></td>
<td><input style="width:50%" id='ad' name='filter' onkeyup='filter(this,2)' type='text' placeholder='Base Location Search'>  <select style="width: 20px;" id='baselocations' onChange='document.getElementById("ad").value=this.value;getElementById("svehicle").value="";filter(this,2);loadvehicles(this.value);' ><option value='selectall'>selectall</option></select></td>
<td>&nbsp&nbsp</td><td><input style="width:50%" placeholder="Vehicle Search" name="timebox" id="svehicle" onkeyup='filter(this,3)'/><select id='ss' onChange='document.getElementById("svehicle").value=this.value;filter(this,3);' name='filter' style="width:20px;" ></select></td><td>&nbsp&nbsp&nbsp</td>

<td style="color:white;font-size:10px"><b> Color Scheme :</b> <span style='background-color: palegreen; width: 10px; display: inline-block'>&nbsp;</span> Moving &nbsp;&nbsp; <span style='background-color: Yellow; width: 10px; display: inline-block'>&nbsp;</span> Idle &nbsp;&nbsp;<span style='background-color: Red; width: 10px; display: inline-block'>&nbsp;</span> Unreachable &nbsp;&nbsp; </td>


</tr><!--
<tr>
    <td colspan="6" style="color:white"> Filters:
     <input type="radio" name="vehiclestatus" value="any" onclick="allvehiclesInfo();"> Any
       <input type="radio" name="vehiclestatus" value="online" onclick="online();"> Moving
         <input type="radio" name="vehiclestatus" value="idle" onclick="idle();"> Stoppage

         <input type="radio" name="vehiclestatus" value="offline" onclick="offline();"> Unreachable(No Data)
         <input type="radio" name="vehiclestatus" value="overspeed" onclick="overspeedreport()"> Overspeed 
       <input type="checkbox" id="markerwithlable" name="markerwithlable" value="markerwithlable"> Marker with Vehicle Name
        
  </td>

</tr>
-->
</thead></table></div><br>
              
               
                 <!-- div2 -->
                   
                  <!--  div 3 --> 
                  <!--   <div id="msg" style="width:300px;height:300px;background:white;display:none;overflow-y:scroll" onMouseOver="this.style.opacity=1" onMouseOut="document.getElementById('mail').style.display='block';document.getElementById('msg').style.display='none'"><div id="msgcontent"></div></div>
            <div id="mail"><input  type="button" id="reportAnalysis" value="Reports" class="button-secondary1 pure-button" onclick="Showandhide()"/></div>
      --> 
<!--          <div id="searchresult"><input type="button" id="search" value="Show/Hide" class="button-secondary pure-button" onclick="searchresultshowhide()"/></div>
      <div id="live"><input  type="button" id="live1" value="Live" class="button-secondary1 pure-button" onclick="linveshowandhide()"/></div>
      -->
<!--
       <div id="div1"><input  type="button" id="live2" value="" class="button-secondary1 pure-button" onclick="linveshowandhide()"/></div>

         <div id="div2"><input  type="button" id="live3" value="" class="button-secondary1 pure-button" onclick="linveshowandhide()"/></div>

            <div id="div3"><input  type="button" id="live4" value="" class="button-secondary1 pure-button" onclick="linveshowandhide()"/></div>

          <div id="div4"><input  type="button" id="live5" value="" class="button-secondary1 pure-button" onclick="linveshowandhide()"/></div>

          <div id="div5"><input  type="button" id="live6" value="" class="button-secondary1 pure-button" onclick="linveshowandhide()"/></div>



    <div id="div6"><input  type="button" id="live7" value="" class="button-secondary1 pure-button" onclick="linveshowandhide()"/></div>
    <div id="div7"><input  type="button" id="live8" value="" class="button-secondary1 pure-button" onclick="linveshowandhide()"/></div>
    <div id="div8"><input  type="button" id="live9" value="" class="button-secondary1 pure-button" onclick="linveshowandhide()"/></div>
    <div id="div9"><input type="button" id="live10" value="" class="button-secondary1 pure-button" onclick="linveshowandhide()"/></div>
    <div id="div10"><input  type="button" id="live11" value="" class="button-secondary1 pure-button" onclick="linveshowandhide()"/></div>


-->



   </div>
<div id="menu" style="background:#13415a;height:5%">

    <ul class="menu"  style="background: rgb(35,83,138); /* Old browsers */
background: -moz-linear-gradient(top, rgba(35,83,138,1) 0%, rgba(167,207,223,1) 98%); /* FF3.6-15 */
background: -webkit-linear-gradient(top, rgba(35,83,138,1) 0%,rgba(167,207,223,1) 98%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(to bottom, rgba(35,83,138,1) 0%,rgba(167,207,223,1) 98%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#23538a', endColorstr='#a7cfdf',GradientType=0 );
width:100%">
        <li><a href="" class="parent"><span>Home  <b>| </b></span></a>
        </li>
         <li><a onclick="linveshowandhide();"> <span>Live Stats  <b>| </b> </span></a></li>

        <?php if($userID!="pdumedical"){?> 
             
        <li><a href="#" id="reportsbtn" class="parent"><span>Reports  <b>| </b></span></a>

            <div><ul>
                    <li><a onclick="inmotion('eventlog')"><span>Event Log</span></a></li>
                     <li><a onclick="inmotion('stoppage')"><span>Stoppage</span></a></li>
                    <li><a onclick="inmotion('overspeed');"><span>Over Speed</span></a></li>
                      <li><a onclick="inmotion('powercutlog');"><span>Power Cut Log  </span></a></li>
                      <li><a onclick="inmotion('ignition');"><span>Ignition</span></a></li>
                   <!-- <li><a href="#" class="parent"><span>Basic Reports</span></a>
                          <div><ul>
                               <li><a onclick="inmotion('stoppage')"><span>Stoppage</span></a></li>
                               <li><a onclick="inmotion('overspeed');"><span>Over Speed</span></a></li>
                                </ul>
                          </div>

                     </li> -->
                   <li><a href="#" class="parent"><span>Vehicle Status</span></a>
                          <div><ul>
                                   <li><a onclick="inmotion('offlinereport')"><span>Offline Vehicles</span></a></li>  
                                    <li><a onclick="inmotion('offroadreport')"><span>Offroad Vehicles</span></a></li>
                                   <li><a onclick="inmotion('currentstatusreport')"><span>Current Status Vehicles</span></a></li>
                                   <li><a onclick="inmotion('daywisestatus');"><span>Day wise Vehicles Summary Status</span></a></li>
                                </ul>
                          </div>

                     </li>

            
              <!--      <li><a onclick="inmotion('offlinereport')"><span>Offline</span></a></li>
                    <li><a onclick="inmotion('overspeed');"><span>Over Speed</span></a></li> 
                    <li><a onclick="inmotion('currentstatusreport')"><span>Current Status</span></a></li>
                      <li><a onclick="inmotion('inmotion');"><span>Inmotion</span></a></li>
                   <li><a onclick="inmotion();"><span>Vehicle Health Cycle Status</span></a></li> 
                    <li><a onclick="inmotion('daywisestatus');"><span>Day wise Status</span></a></li> -->
                      
                     <li><a onclick="inmotion('distanceidle');"><span>Performance Report</span></a></li>
                     <li><a href="#" class="parent"><span>Movement Reports</span></a>
                          <div><ul>
                               <li><a onclick="inmotion('rawdata')"><span>Vehicle Raw Details</span></a></li>
                               <li><a onclick="inmotion('inmotion');"><span>Vehicle Log Report</span></a></li>
                               </ul>
                          </div>

                     </li>
                 <!--  <li><a onclick="inmotion('casemanagerreport')"><span>Case Manager Report</span></a></li> 
                  <li id="pdfref"><a onclick="inmotion('pdfreport')"><span>PDF/Excel Reports</span></a></li> 
                  <li id="nocases"><a onclick="inmotion('nocasesreport')"><span>No Cases Report</span></a></li>
                   <li id="highwayID"><a onclick="inmotion('highway')"><span>High Ways</span></a></li>
             -->
                   <li><a onclick="inmotion('zonereport');"><span>Geozone Report</span></a></li>
                   <li><a onclick="inmotion('zonecrossed');"><span>Geozone Crossed Report</span></a></li>
          </ul></div>

        </li>
     <?php  } ?>
         <li><a href="#" class="parent"><span>Maps  <b>| </b></span></a>

            <div><ul>
                    <li><a onclick="inmotion('historytracking')"><span>History Tracking</span></a></li>
                    <li><a onclick="inmotion('livevehicletracking');"><span>Live Vehicle Tracking</span></a></li>
          </ul></div>

        </li>

         <li><a href="#" class="parent"><span>Options <b>| </b></span></a>

            <div><ul>
                    <li><a><span><input type='radio' name='vehiclestatus' value='any' onclick='allvehiclesInfo();'> All Vehicles</span></a></li>
                    <li><a><span><input type='radio' name='vehiclestatus' value='online' onclick='online();'> Moving</span></a></li>
                    <li><a><span> <input type='radio' name='vehiclestatus' value='idle' onclick='idle();'> Stoppage</span></a></li>
                    <li><a><span><input type='radio' name='vehiclestatus' value='offline' onclick='offline();'> Unreachable(No Data)</span></a></li>
                    <li><a><span><input type='radio' name='vehiclestatus' value='overspeed' onclick='overspeedreport()'> Overspeed</span></a></li>
                   <li><a><span><input type='checkbox' id='markerwithlable' name='markerwithlable' value='markerwithlable'> Marker with Vehicle Name</span></a></li>
          </ul></div>

        </li>



        <li><a><span>Info  <b>| </b></span></a>
          <div><ul>
          <!--  <li><a onclick="$('#legend').slideToggle('slow');"><span>Legend Show/Hide  </span></a></li>-->
          <li><a onclick="hideGroupLabels();"><span>Labels Show/Hide  </span></a></li>
         <li><a onclick="window.open('info.html')"><span>Help Topics </span></a></li>
               
            </ul></div>
       </li>


           <li><a onClick="document.getElementById('groupdevice').value='selectall'; showorhidemarkers='hide';window.localStorage.setItem('showmap','all'); maploadcount=0;allVehicleStatus();"><span> <label id="resetallroups"> Reset<label> <b>| </b></span> </a></li>


     <li> <a> <span><b>| </b>Group : &nbsp<b style='font-size:18px;color:yellow'> <label id="selectingGroupID"></label></b><b>|</b></span></a></li>
<li> <a><span>Total Vehicles: &nbsp<b style='font-size:18px;color:yellow'> <label id="reset"></label> </b><b>|</b></span></a></li>

   
      <li> <a><span><b>| </b>Account:&nbsp<b style='font-size:18px;color:yellow'> <label id="accountdis"></label> </b></span></a></li>

<!-- <li> <a onclick="searchhide()" rel="external" style=""><span><img src="images/search.png" width="23" height="30" title="Search"></span></a></li>-->



      <li>       <a  onclick="textview();"> <img src="images/textView.png" width="30" height="30" alt="Text View" title="Text View"> &nbsp&nbsp&nbsp</a></li>
            <li>   <a  onclick="mapview();"> <img src="images/icon1.png" width="30" height="30" title="Map View"> &nbsp&nbsp&nbsp</a></li>
                  <li>  <a   onclick="bothview();"><img src="images/bothView.png" width="30" height="30" title="Map and Text View"> </a>&nbsp&nbsp&nbsp</li>
   <li> <input style="width:50%" placeholder="Vehicle Search" name="timebox" id="svehicle11" /><input type='button' value="Go" onClick="filter(document.getElementById('svehicle11'),3)"/></li>

<!--       <li> <a class="changeBlue"><span><label id="Name1"></label> <b>|</b></span></a> </li>-->


 <!--    <li>    <a id="pdf" > &nbsp&nbsp&nbsp<img src="images/pdf.png" width="30" height="30" title="Pdf">&nbsp&nbsp&nbsp </a>  </li>
        <li><a onclick="excel()"><img src="images/excel.png" width="30" height="30" title="Excel"> &nbsp&nbsp&nbsp</a></li>
        <li><a onclick="printreport()"> <img src="images/printer.png" width="30" height="30" title="Print" > &nbsp&nbsp&nbsp</a></li>-->

<li style="float:right">  <a onclick="back()" rel="external" style="color:black;"><span> <b> Logout </b></span></a></li>

    </ul>

</div>




<div id="allreports" >
  <center>
   <div style="width:100%;height:20px;background:black;color:white" id="reportheading">Report Form</div>
      <table style="font-family:Arial,Verdana,sans-serif;font-size:13;">
          <tr id="selectedgroupID">
                <td style="width:100px;">Group :</td><td><select class='textcss' id="selectedgroup" style="width: 150px;" onChange="loadVehicles();">
                            <option value="selectall">Select All</option>
                             
                       </select>
               </td>
          </tr>
         <tr id="vehiclesIDs" >
                        <td style="width:100px;"><label>Vehicle :</label></td><td><select class='textcss' id="vehicles" style="width: 150px;">
                                                    <option value="selectall">Select All</option>

                       </select>
                       </td>
            </tr> 
            <tr id="fromDateID" >
                 <td style="width:100px;"><label>From Date:</label></td><td><input class='textcss' id="fromDate" name="fromDate" style="width: 150px;"/></td>
           </tr>
            <tr id="toDateID">
           <td style="width:100px;"><label>To Date:</label></td><td> <input class='textcss' id="toDate" name="toDate" style="width: 150px;"/></td>
          </tr>
              
             <tr id="reporttypeID" >
                     <td style="width:100px;"><label>Report Type :</label></td><td> <select class='textcss'  id="reportType" style="width: 150px;">
                                                                           <option value="summary">Summary </option>
                                                                           <option value="all">All </option>
                                                                          </select> 
                   </td>
           </tr>
        
             <tr id="pdfexcelID" >
                     <td style="width:100px;"><label>Report Formate :</label></td><td> <select class='textcss'  id="pdfexcel" style="width: 150px;">
                                                                           <option value="pdf">PDF </option>
                                                                           <option value="excel">Excel </option>
                                                                          </select>
                   </td>
           </tr>


     
             <tr id="pdfreporttypeID" >
                     <td style="width:100px;"><label>Report Type :</label></td><td> <select class='textcss'  id="pdfreportType" style="width: 150px;">
                                                                            <option value="all">All </option>
                                                                           <option value="distaneidlereport">Distance Idle Reports </option>
                                                                           <option value="casemanagerreport">Case Manager Report </option>
                                                                           <option value="nocases">Case Manager Report With out Cases</option>
<option value="overspeed">Over Speed Report </option>
<option value="offline"> offline Report </option>
<option value="currentlocation"> Current Status Rerport </option>
<option value="daywisestatusreport">Day wise all Vehicle Report </option>
                                                                          </select>
                   </td>
           </tr>


              <tr id="listgridID" >
                     <td style="width:300px;">List <input  type="radio" name="view" value="list" checked /> Grid <input  type="radio" name="view" value="grid" />
                   </td>
           </tr>


            <tr id="idletimeID">
                     <td style="width:100px;"><label>Idle Time(mins):</label></td><td> <input class='textcss' type="text" id="idletime" value="10" style="width: 150px;"/></td>
           </tr> 
             <tr id="offlinetimeID">
                     <td style="width:100px;"><label>Offline Time(mins):</label></td><td> <input class='textcss' type="text" id="offlinetime" value="10" style="width: 150px;"/></td>
           </tr>
             <tr id="stoppageID">
               <td align="center" colspan='2'><input type="checkbox" name="multicheck" value="sid"> Stoppage&nbsp&nbsp&nbsp&nbsp&nbsp:<input type="text" id="sid" class='textcss'style="width: 30px;"  value='10'/> (Min&nbsp)&nbsp&nbsp</td>
             </tr>
             <tr id="timeintervalID">  
               <td align="center" colspan="2"> <input type="checkbox" name="multicheck" value="tid"> Time Interval:<input type="text" id="tid" class='textcss'style="width: 30px;" value='2'/> (Hour)&nbsp&nbsp </td>
           </tr> 
          <tr id="overspeedID">
               <td align="center" colspan='2'><input type="checkbox" name="multicheck" value="oid"> Over Speed&nbsp&nbsp:<input type="text" id="oid" class='textcss'style="width: 30px;" value='60' /> (Kmph) </td>
           </tr> 
           <tr id="ignitionID">
               <td align="center" colspan='2'> <input type="checkbox" name="multicheck" value="ignition"> Ignition &nbsp&nbsp&nbsp&nbsp&nbsp <input type="checkbox" name="multicheck" value="mainpower"> Main Power  </td>
           </tr> 
           <tr>
                     <td align="center" colspan='2'> <input class='textcss1' type="button" id="inmotion" value="Log Report" style="width: 200px;" onclick="getOverSpeedReport1(this);"/></td>
           </tr>
           <tr>
                    <td align="center" colspan='2'> <input type="button" class='textcss1'  id="overspeed" value="Over Speed" style="width: 200px;" onclick="getOverSpeedReport1(this);"/></td>
           </tr>
          <tr>
                <td align="center" colspan='2'> <input type="button" class='textcss1'  id="powercutlog" value="Power cut Log" style="width: 200px;" onclick="getOverSpeedReport1(this);"/></td>
          </tr>
               
 <tr>
                <td align="center" colspan='2'> <input type="button" class='textcss1'  id="ignition" value="Ignition" style="width: 200px;" onclick="getOverSpeedReport1(this);"/></td>
          </tr>

 
          <tr>
                <td align="center" colspan='2'> <input type="button" class='textcss1'  id="zonereport" value="Zone Report" style="width: 200px;" onclick="getOverSpeedReport1(this);"/></td>
          </tr>

        <tr>
                <td align="center" colspan='2'> <input type="button" class='textcss1'  id="zonecrossed" value="Zone Crossed Report" style="width: 200px;" onclick="getZoneCrossedReport(this);"/></td>
          </tr>


           <tr>
                  <td align="center" colspan='2'>  <input type="button" id="offlinereport" class='textcss1' value="Offline" style="width: 200px;" onclick="getOffline('offline');"/></td>
           </tr>

     <tr>
                  <td align="center" colspan='2'>  <input type="button" id="offroadreport" class='textcss1' value="Offroad" style="width: 200px;" onclick="getOffline('offroad');"/></td>
           </tr>


           <tr>
                <td align="center" colspan='2'>  <input type="button" id="currentstatusreport" class='textcss1' value="Current Status" style="width: 200px;" onclick="getOffline('status');"/></td>
           </tr>
           <tr>
                 <td align="center" colspan='2'>  <input type="button" id="daywisestatus" class='textcss1' value="Day wise status report" style="width: 200px;" onclick="getOverSpeedReport1(this);"/></td>
           </tr>
             <tr>
                 <td align="center" colspan='2'>  <input type="button" id="historytracking" class='textcss1' value="History tracking" style="width: 200px;" onclick="historyTracking(this);"/></td>
           </tr>
         <tr>
                 <td align="center" colspan='2'> <input type="button" id="livevehicletracking" class='textcss1' value="Live Vehicle Tracking" style="width: 200px;" onclick="vehicleTracking(this);"/></td>
           </tr>
           <tr>
                 <td align="center" colspan='2'> <input type="button" id="eventlog" class='textcss1' value="Event Log" style="width: 200px;" onclick="rawdata(this);"/></td>
           </tr>
          <tr>
                 <td align="center" colspan='2'> <input type="button" id="rawdata" class='textcss1' value="Raw Details" style="width: 200px;" onclick="rawdata(this);"/></td>
           </tr>
           <tr>
                 <td align="center" colspan='2'>  <input type="button" id="stoppage" class='textcss1' value="Stoppage" style="width: 200px;" onclick="rawdata(this);"/></td>
           </tr>

          <tr>
               <td align="center" colspan='2'> <input type="button" id="distanceidle" class='textcss1' value="Submit" style="width: 200px;" onclick="distanceIdlereport(this);"/></td>
           </tr>
              <tr>
               <td align="center" colspan='2'> <input type="button" id="casemanagerreport" class='textcss1' value="Case Manager Report" style="width: 200px;" onclick="getOverSpeedReport1(this);"/></td>
           </tr>
 
         <tr>
               <td align="center" colspan='2'> <input type="button" id="pdfreport" class='textcss1' value="Get Report" style="width: 200px;" onclick="getPDFFile(this);"/></td>
           </tr>
 
   <tr>
               <td align="center" colspan='2'> <input type="button" id="nocasesreport" class='textcss1' value="No Cases Data" style="width: 200px;" onclick="getOverSpeedReport1(this);"/></td>
           </tr>
      <tr>
               <td align="center" colspan='2'> <input type="button" id="dynamicdistanceidlereport" class='textcss1' value="Predefined Report" style="width: 200px;" onclick="distanceIdlereport(this);"/></td>
           </tr>
       <tr>
               <td align="center" colspan='2'> <input type="button" id="highway" class='textcss1' value="High Way" style="width: 100px;" onclick="getHighways(this);"/></td>
           </tr>



    </table>
</center>

</div>
<div id="editor"></div>
 <div id="reportarea" style="overflow-y:scroll;">

</div>

 
  <!--      <div align="center">Powered by <a class="changeBlue" href=""  onclick="window.open('http://www.glovision.co')">Glovision</a></div>
             <div id="footer" data-role="footer" data-theme="f" style="background-color:#81DAF5"> Copyright © 2013   Glovision Techno Services Pvt Ltd. " - All Rights Reserved "</div>
            
      -->  
        <div id="casemanagement">
       </div>
      <div id="flags" class='hello2'>
                  </div>
 
      <!-- legend -->
      <div id='legend'>
        <table border="1" cellpadding="0" cellspacing="0">
                <tr style="font-size:10px;color:black;font-weight: bold;">
              <td> <a onclick="caseinfo('towardsscene')"><img src="images1/towardsscene.png" height="32" width="16"> </td><td> Vehicle is running and towards scene</td>
              <td><a onclick="caseinfo('atscene')"> <img src="images1/atscene.png" height="32" width="16"> </td><td> Vehicle is running and at scene</td>
              <td><a onclick="caseinfo('towardshospital')"> <img src="images1/towardshospital.png" height="32" width="16"> </td><td> Vehicle is running and Started from scene</td>
              <td> <a onclick="caseinfo('readytotakecase-online')"><img src="images1/readytotakecase-online.png" height="32" width="16"> </td><td> Vehicle is running and Ready to take case</td>
              <td><a onclick="caseinfo('readytotakecase-idle')"> <img src="images1/readytotakecase-idle.png" height="32" width="16"> </td><td> Vehicle is idle and Ready to take case</td>
              <td> <a onclick="caseinfo('offline-caseassigned')"><img src="images1/offline-caseassigned.png" height="32" width="16"> </td><td> Vehicle is offline and case assigned</td>
              <td><a onclick="caseinfo('offline-casenotassigned')"> <img src="images1/offline-casenotassigned.png" height="32" width="16"> </td><td> Vehicle is offline and case not assigned</td>
      </tr>
 

            
  </table>


 </body>
</html>
