<?php 
include 'config.php';
include 'pdfcretor.php';
include 'Excelgenerator.php';
date_default_timezone_set('Asia/Kolkata');

$tacconnect = mysqli_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database: " . mysqli_error($tacconnect));
mysqli_select_db($tacconnect,$tacdb);
$deviceID=$_GET['vehicle'];
$group=$_GET['group'];
$accountID=$_GET['accountID'];
$userID=$_GET['userID'];
$dumpUserID=$userID;
$fromDate=$_GET['fromDate'];
$toDate=$_GET['toDate'];
$reportFormate=$_GET['formate'];
$speedlimit=$_GET['speedlimit'];
$crone=$_GET['crone'];
if($crone=="yes"){

       $toDayDate=strtotime(date('d-M-Y',strtotime("-1 days")).'');
       $d=date('d',$toDayDate);
       $m=date('m',$toDayDate);
       $y=date('Y',$toDayDate);
       $fromDate=strtotime(date("d-m-Y h:i:s a", mktime(0,0,0,$m,$d,$y)));
     //  $toDate=time();  
      $toDate=strtotime(date("d-m-Y h:i:s a", mktime(23,59,59,$m,$d,$y)));


   }


if($reportFormate=="word"){

   error_log($reportFormate.' report formate');
   header('Cache-control: no-cache,must revalidate');

   header("Access-Control-Allow-Origin: *");
   header("Content-type: application/vnd.ms-word");
   header("Content-Disposition: attachment;Filename=".$userID.".doc");
  
   }else if($reportFormate=="excel"){
    
   header('Cache-control: no-cache,must revalidate');
  header('Content-type: application/json');
   header("Access-Control-Allow-Origin: *");
  header("Content-type: application/vnd.ms-excel");
   header("Content-Disposition: attachment;Filename=".$userID.".xls");

 
   }else if($reportFormate=="web"){
       
        header('Cache-control: no-cache,must revalidate');
        
        header("Access-Control-Allow-Origin: *");
        header('Content-type: text/html');
        header("Content-Disposition: attachment;Filename=".$userID.'_'.date('d-m-Y').".html");
       
    }

   $groupArray=array();
 //echo 'satrt';   
   if($group=="selectall"){
      if($userID != "admin" && $userID != "administrator"){
          
          $groupArray=getGroups($gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$userID);

          if(count($groupArray)==0){
           $userID='admin';
           $groupArray=getGroups($gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$userID);
          }
         
        
      }else{
           $groupArray=getGroups($gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$userID);
      }
   }else{
        $groupArray[0]=$group;
   }

$ydata2 = array();

$groupLevel='';
$groupID='';
  
$result1='';
 $summaryReport="<html><head><style type='text/css'>
.tablecss {
	
        width:100%;
        border-collapse: collapse;
        color:black;
        background:white;
        font-family:Arial,Verdana,sans-serif;
        font-size:13;
}
</style><script>
</script></head><body><div id='printarea'><center><h3>Summary Over Speed Report(More than $speedlimit Kmph)</h3>User ID:$userID |District:$group <br> From:".date('d-M-Y h:i:s',$fromDate )."  To:".date('d-M-Y h:i:s',$toDate )." <br><table border='1' class='tablecss'><tr style='background:black;color:white'><th>S.NO</th><th>Vehicle ID</th><th>District</th><th>Base Location</th><th>No Of Times Crossed</th></tr><tbody>";
 $table="<center>Detailed Report <table border='1' class='tablecss'><tr style='background:black;color:white'><th>Vehicle ID</th><th>District</th><th>Base Location</th><th>Speed</th><th>Address</th><th>Lat/Long</th><th>Time</th></tr><tbody>";
$sno=1;
for($gpindx=0;$gpindx<count($groupArray);$gpindx++){
     $lastLocaton=array(array());
     $groupID=$groupArray[$gpindx];
     getLastLocation();
  
    
     $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($gtsconnect));
     mysqli_select_db($gtsconnect,$gtsdb);
     $countoverspeed=0;
    for($lve=0;$lve<count($lastLocaton);$lve++){
         $deviceLevel='';
         $ydata  =array(array());
         $cumulativeDistance=0;
        $countoverspeed=0; 
         $query = "select timestamp,deviceID,analog0,statusCode,speedKPH,from_unixtime( timestamp, '%d-%m-%Y %h:%i:%s %p' ) as time1,odometerOffsetKM+odometerKM as 'odometerKM',latitude,longitude,address,analog1,heading as direction from EventData where accountID='$accountID' and deviceID='".$lastLocaton[$lve][0]."'  and timestamp between '$fromDate' and '$toDate' and speedKPH > '$speedlimit' and statusCode='61714' order by timestamp asc";//
         $qry_result = mysqli_query($gtsconnect,$query) or die(mysqli_error($gtsconnect));
        // echo $query; 
         $j=0;
        $catchOverspeedEventtime='no';
        $vehiclestatrt='';
        while($row = mysqli_fetch_assoc($qry_result))
        {   
   
         
         $ydata[$j][1]=$row['statusCode'];
         $ydata[$j][2]= floor($row['speedKPH']);
         $ydata[$j][3]=$row['time1'];
        
         $ydata[$j][4]=$row['timestamp'];
         $ydata[$j][5]=round($row['latitude'],5);
         $ydata[$j][6]=round($row['longitude'],5);

         $ydata[$j][7]=$row['address'];
 
         $ydata[$j][8]=$row['deviceID'];
         
          $dis11 = explode("]",$lastLocaton[$lve][2]);
          
          $ydata[$j][9]=trim($dis11[2],'[');
          $ydata[$j][10]=trim($dis11[1],'[');
          $ydata[$j][11]=$lastLocaton[$lve][3];
          $ydata[$j][13]=$lastLocaton[$lve][4];
          if($vehiclestatrt==$ydata[$j][8]){
             $diff=($row['timestamp']-$ydata[$j-1][4])/60;
             if($diff>10){ 
                    $countoverspeed++;
                   $table .='<tr><td>'.strtoupper($ydata[$j][8]).'</td><td>  '.strtoupper($ydata[$j][9]).'</td><td>  '.strtoupper($ydata[$j][10]).' </td><td> '.$ydata[$j][2].' Kmph</td><td>  '.$ydata[$j][7]."</td><td>".$ydata[$j][5]."/".$ydata[$j][6]."</td><td>".$ydata[$j][3]."</td></tr>";

             }
         
         }

        if($vehiclestatrt!=$ydata[$j][8] ){ 
        $vehiclestatrt=$ydata[$j][8];
                     $countoverspeed++;
                     $table .='<tr><th colspan="6"> Vehicle ID: '.strtoupper($ydata[$j][8]).' </th></tr><tr><td>'. strtoupper($ydata[$j][8]).'</td><td>  '.strtoupper($ydata[$j][9]).'</td><td>  '.strtoupper($ydata[$j][10]).' </td><td> '.$ydata[$j][2].' Kmph</td><td>  '.$ydata[$j][7]."</td><td>".$ydata[$j][5]."/".$ydata[$j][6]."</td><td>".$ydata[$j][3]."</td></tr>";
        }

         $j++;
      }//while
       $dis11 = explode("]",$lastLocaton[$lve][2]);
     if($countoverspeed>0)
      $summaryReport .="<tr><td>".($sno++)."</td><td>".strtoupper($lastLocaton[$lve][0])."</td><td>".strtoupper(trim($dis11[2],'['))."</td><td>".strtoupper(trim($dis11[1],'['))."</td><td>".$countoverspeed."</td></tr>";
   }




   

}

$newpdf=new PdfGeneretor();
       $newpdf->pdfcreation($summaryReport.'</tbody></table><br>'.$table.'</tbody></table></div></center></body></html>',$accountID.'_'.$group.'_'.$fromDate.'_overspeed',null,"Over Speed Report",date('d-m-Y',$fromDate));
$excel=new ExcelService();
$excel->generateExcel($summaryReport.'</tbody></table><br>'.$table.'</tbody></table></div></center></body></html>',$accountID.'_'.$group.'_'.$fromDate.'_overspeed');

 echo $summaryReport.'</tbody></table>'.$table.'</tbody></table></div></center></body></html>';




function getLastLocation(){
  global $gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$lastLocaton,$userID,$groupID,$deviceID;
$gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($gtsconnect));
mysqli_select_db($gtsconnect,$gtsdb);
     $query="select a.deviceID as deviceID,a.lastGPSTimestamp as lastGPSTimestamp,a.description as description,a.vehicleModel as vehicleModel,c.contactPhone as simPhoneNumber,a.uniqueID as uniqueID from Device a,DeviceList b,Driver c where a.accountID='$accountID' and b.groupID='$groupID' and b.accountID='$accountID' and a.deviceID=b.deviceID and c.deviceID=a.deviceID and c.deviceID=b.deviceID and c.accountID='$accountID'";
        
    if(($userID == "admin" || $userID == "administrator") && $groupID=="selectall"){
        $query = "SELECT a.deviceID as deviceID,a.lastGPSTimestamp as lastGPSTimestamp,a.description as description,a.vehicleModel as vehicleModel,c.contactPhone as simPhoneNumber,a.uniqueID as uniqueID FROM Device a,Driver c WHERE a.accountID ='$accountID' and a.deviceID=c.deviceID and c.accountID=a.accountID";
       
      }
     if($deviceID != 'selectall'){
          $query = "SELECT a.deviceID as deviceID,a.lastGPSTimestamp as lastGPSTimestamp,a.description as description,a.vehicleModel as vehicleModel,c.contactPhone as simPhoneNumber,a.uniqueID as uniqueID FROM Device a,Driver c WHERE a.accountID ='$accountID' and a.deviceID='$deviceID' and c.deviceID='$deviceID'";
     }
 // echo $query;
  $qry_result = mysqli_query($gtsconnect, $query) or die(mysqli_error($gtsconnect));
   $i=0;
 
   while($row = mysqli_fetch_assoc($qry_result)){
       $lastLocaton[$i][0]=$row['deviceID'];
        $lastLocaton[$i][1]=$row['lastGPSTimestamp'];
       $lastLocaton[$i][2]=$row['description'];
       $lastLocaton[$i][3]=$row['vehicleModel'];
       $lastLocaton[$i][4]=$row['simPhoneNumber'];
       $lastLocaton[$i][5]=$row['uniqueID'];
    //  echo 'raman';
          $i++;
   }
mysqli_close($gtsconnect);
}

function getGroups($gtsserver, $gtsusername, $gtspassword,$gtsdb,$accounts,$userID){
    $result=array();
   $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($gtsconnect));
   mysqli_select_db($gtsconnect,$gtsdb);
  
    $vehicleDetails='';
    if($userID == "admin" || $userID == "administrator"){
         $vehicleDetails="select DISTINCT groupID from DeviceGroup where accountID='$accounts'";
    }else{
         $vehicleDetails="select DISTINCT groupID from GroupList where accountID='$accounts' and userID='$userID'";
    }
   error_log($vehicleDetails);
   $qry1 = mysqli_query($gtsconnect,$vehicleDetails) or die(mysqli_error($gtsconnect));
   $rowcount=0;
  
   while($row = mysqli_fetch_assoc($qry1)){
        $result[$rowcount]=$row['groupID'];
        $rowcount++;
    
   }
   if($rowcount==0){
       $result[$rowcount]='';
   }
   mysqli_close($gtsconnect);
   return $result;

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


?>
