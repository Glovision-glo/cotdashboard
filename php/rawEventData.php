<?php
    date_default_timezone_set('Asia/Kolkata');
    require_once('config.php');
     $accountID=$_GET['accountID'];
     $vehicleID=$_GET['vehicleID'];
     $reportType=$_GET['reportrequest'];
     $fromDate=strtotime($_GET['fromDate']);
     $idletime=$_GET['idletime'];
     $toDate=strtotime($_GET['toDate']);

     $stoppage=$_GET['stoppage'];
     $timeinterval=$_GET['timeinterval'];
     $overspeed=$_GET['overspeed'];
     $ignition=$_GET['ignition'];
     $mainpower=$_GET['mainpower'];

    if($idletime=="0" && $stoppage=="0" && $timeinterval=="0" && $overspeed=="0" && $ignition=="no" && $mainpower=="no"){
       $stoppage=10;
     $timeinterval=2;
     $overspeed=60;
     $ignition="yes";
     $mainpower="yes"; 

    }
     $eventData=array(array());
     getEventData($vehicleID,$accountID,$fromDate,$toDate);
     $totalDis=0;
     if($reportType=="Event Log" || $reportType=="Raw Details"){
         $oldtime=0;
         $startTime=0;
         $endTime=0;
         $idleStart='no';
         $count=0;
         $table="<style type='text/css'>
.tablecss {
        
        width:100%;
        border-collapse: collapse;
        color:black;
        background:white;
        font-family:Arial,Verdana,sans-serif;
        font-size:13;
}
</style><center> <h3>Event Log</h3>AccountID:<b style='font-size:16px'>$accountID </b>|Vehicle ID:<b style='font-size:16px' >$vehicleID</b> <br> From:".date('d-M-Y h:i:s',$fromDate )."  To:".date('d-M-Y h:i:s',$toDate )." <table border='1' style='font-family:Arial,Verdana,sans-serif;font-size:13;color:black;background:white;' class='tablecss'><thead style='background:black;color:white'><th>S NO</th><th>Device ID</th><th>Status</th><th>Zone ID</th><th>Location</th><th>Time</th><th>Lat/Long</th><th>Speed (Km/h)</th><th>Duration</th><th>Distance (Km)</th><th>Direction</th></thead><tbody>";
         $previousTime=0;       
       for($evntindx=0;$evntindx<count($eventData);$evntindx++){
            $statusinfo="Stopped";
            if($eventData[$evntindx][2]=="61714"){
                $statusinfo="Moving";
            }else if($eventData[$evntindx][2]=="61718"){
                $statusinfo="Idle";
            }else if($eventData[$evntindx][2]=="62467"){
                $statusinfo="Ignition Off";
            }else if($eventData[$evntindx][2]=="62465"){
                $statusinfo="Ignition On";
            }else if($eventData[$evntindx][2]=="64791"){
                 $statusinfo="Main Power OFF";

            }else if($eventData[$evntindx][2]=="64793"){
                 $statusinfo="Main Power ON";
            }
            //calculating stoppage 
            if( $idleStart=="no" && $statusinfo!="Moving"){
                 $idleStart="yes";
                 $startTime=$eventData[$evntindx][10];
             }
             if($idleStart=="yes" && $statusinfo=="Moving"){
                 $idleStart="no";
                 $endTime=$eventData[$evntindx][10];
                 $dis=abs($endTime-$startTime);
                 $disdump=$dis;

                 if(($dis/60)>=$stoppage && $stoppage!=0){
                     $table  =$table."<tr><td>".($count++)."</td><td>".strtoupper($eventData[$evntindx][1])."</td><td>Stoppage</td><td>".$eventData[$evntindx][0]."</td><td>".$eventData[$evntindx][6]."</td><td>".$eventData[$evntindx][3]."</td><td>".round($eventData[$evntindx][8],5)."/".round($eventData[$evntindx][9],5)."</td><td>".intval($eventData[$evntindx][5])."</td><td>".TimeFormate($disdump)."</td><td>".round($totalDis,2)."</td><td>".$eventData[$evntindx][11]."</td></tr>";
                 }
             }           


             $totalDis=$totalDis+$eventData[$evntindx][4]; 
             //time diff between event to event 
             $duration="00:00:00";
             if($evntindx>0){
                 $duration=TimeFormate(abs($eventData[$evntindx][10]-$previousTime));
             }
             $previousTime=$eventData[$evntindx][10];
             //if request for raw data 
             if($reportType=="Raw Details"){
                  $table  =$table."<tr><td>".($count++)."</td><td>".strtoupper($eventData[$evntindx][1])."</td><td>".$statusinfo."</td><td>".$eventData[$evntindx][0]."</td><td>".$eventData[$evntindx][6]."</td><td>".$eventData[$evntindx][3]."</td><td>".round($eventData[$evntindx][8],5)."/".round($eventData[$evntindx][9],5)."</td><td>".intval($eventData[$evntindx][5])."</td><td>".$duration."</td><td>".round($totalDis,2)."</td><td>".$eventData[$evntindx][11]."</td></tr>";   
 
             } 
             //calculating ignition on /off
             if($ignition=="yes" &&($statusinfo=="Ignition Off" || $statusinfo=="Ignition On") ){
	         $table  =$table."<tr><td>".($count++)."</td><td>".strtoupper($eventData[$evntindx][1])."</td><td>".$statusinfo."</td><td>".$eventData[$evntindx][0]."</td><td>".$eventData[$evntindx][6]."</td><td>".$eventData[$evntindx][3]."</td><td>".round($eventData[$evntindx][8],5)."/".round($eventData[$evntindx][9],5)."</td><td>".intval($eventData[$evntindx][5])."</td><td>".$duration."</td><td>".round($totalDis,2)."</td><td>".$eventData[$evntindx][11]."</td></tr>";

             }
             //calculation main power off/on
             if($mainpower=="yes" && ($statusinfo=="Main Power OFF" || $statusinfo=="Main Power ON")){
                 $table  =$table."<tr><td>".($count++)."</td><td>".strtoupper($eventData[$evntindx][1])."</td><td>".$statusinfo."</td><td>".$eventData[$evntindx][0]."</td><td>".$eventData[$evntindx][6]."</td><td>".$eventData[$evntindx][3]."</td><td>".round($eventData[$evntindx][8],5)."/".round($eventData[$evntindx][9],5)."</td><td>".intval($eventData[$evntindx][5])."</td><td>".$duration."</td><td>".round($totalDis,2)."</td><td>".$eventData[$evntindx][11]."</td></tr>";
 
             }
             //calculating overspeed event
             if($eventData[$evntindx][5]>$overspeed && $overspeed!=0){
                 $table  =$table."<tr><td>".($count++)."</td><td>".strtoupper($eventData[$evntindx][1])."</td><td>Over Speed</td><td>".$eventData[$evntindx][0]."</td><td>".$eventData[$evntindx][6]."</td><td>".$eventData[$evntindx][3]."</td><td>".round($eventData[$evntindx][8],5)."/".round($eventData[$evntindx][9],5)."</td><td>".intval($eventData[$evntindx][5])."</td><td>".$duration."</td><td>".round($totalDis,2)."</td><td>".$eventData[$evntindx][11]."</td></tr>";
             }
             //calculating time interval event
             if($oldtime==0){
                 $oldtime=$eventData[$evntindx][10];
             }
             $difftimeinterval=(abs($eventData[$evntindx][10]-$oldtime)/(60*60));

             if($timeinterval<=$difftimeinterval && $timeinterval!=0){
                 $oldtime=$eventData[$evntindx][10];
                 $table  =$table."<tr><td>".($count++)."</td><td>".strtoupper($eventData[$evntindx][1])."</td><td>".$timeinterval." Hour Entry</td><td>".$eventData[$evntindx][0]."</td><td>".$eventData[$evntindx][6]."</td><td>".$eventData[$evntindx][3]."</td><td>".round($eventData[$evntindx][8],5)."/".round($eventData[$evntindx][9],5)."</td><td>".intval($eventData[$evntindx][5])."</td><td>00:00:00</td><td>".round($totalDis,2)."</td><td>".$eventData[$evntindx][11]."</td></tr>";
             }   
         }// end for
     } //en$ignitiond if
     else if($reportType=="Stoppage"){
         $table=" <style type='text/css'>
.tablecss {
        
        width:100%;
        border-collapse: collapse;
        color:black;
        background:white;
        font-family:Arial,Verdana,sans-serif;
        font-size:13;
}
</style><center> <h3>Stoppage  Report</h3>AccountID:<b style='font-size:16px' >$accountID </b>|Vehicle ID:<b style='font-size:16px' >$vehicleID</b> <br> From:".date('d-M-Y h:i:s',$fromDate )."  To:".date('d-M-Y h:i:s',$toDate )." <table border='1' style='font-family:Arial,Verdana,sans-serif;font-size:13;color:black;background:white;' class='tablecss'><thead style='background:black;color:white'><th>S NO</th><th>Device ID</th><th>Status</th><th>Location</th><th>Start Time</th><th>End Time</th><th>Idle Time</th><th>Lat/Long</th></thead><tbody>";
//<th>Distance (Km)</th>
         $startTime=0;
         $endTime=0;
         $idleStart='no';
         $count=0;
         $totalIdleTime=0;
         $startDay='';
         for($evntindx=0;$evntindx<count($eventData);$evntindx++){
             $statusinfo="Stopped";
             if($eventData[$evntindx][2]=="61714"){
                 $statusinfo="Moving";
             }else if($eventData[$evntindx][2]=="61718"){
                 $statusinfo="Idle";
             }else if($eventData[$evntindx][2]=="62467"){
                 $statusinfo="Ignition Off";
             }else if($eventData[$evntindx][2]=="62465"){
                 $statusinfo="Ignition On";
             }
             if( $idleStart=="no" && $statusinfo!="Moving"){
                 $idleStart="yes";
                 $startTime=$eventData[$evntindx][10];
             }
             if($evntindx==0){
               $startDay=date('d-m-Y',$eventData[$evntindx][10]);
               $table=$table."<tr bgcolor='#39f2f5'><td>".date('l',$eventData[$evntindx][10])."</td><td colspan='8'>".date('d-m-Y',$eventData[$evntindx][10])."</td></tr>";
             }
         
             if($idleStart=="yes" && $statusinfo=="Moving"){
                 $idleStart="no";
                 $endTime=$eventData[$evntindx][10];
                 $dis=abs($endTime-$startTime);
                 $disdump=$dis;
                 $totalIdleTime=$totalIdleTime+$disdump; 
                 if(($dis/60)>=$idletime){
                     $table  =$table."<tr><td>".($count++)."</td><td>".strtoupper($eventData[$evntindx][1])."</td><td>Stopped</td><td>".$eventData[$evntindx][6]."</td><td>".date('d-m-Y H:i:s',$startTime)."</td><td>".$eventData[$evntindx][3]."</td><td>".TimeFormate($disdump)."</td><td>".round($eventData[$evntindx][8],5)."/".round($eventData[$evntindx][9],5)."</td></tr>";
                  //<td>".round($totalDis,2)."</td>
                 }
             }
              
             if($idleStart=="yes" && $startDay!=date('d-m-Y',$eventData[$evntindx][10])){
                 $idleStart="no";
                 $startDay=date('d-m-Y',$eventData[$evntindx][10]);
                 $endTime=$eventData[$evntindx][10];
                 $dis=abs($endTime-$startTime);
                 $disdump=$dis;
                 $totalIdleTime=$totalIdleTime+$disdump; 
                 if(($dis/60)>=$idletime){
                     $table  =$table."<tr><td>".($count++)."</td><td>".strtoupper($eventData[$evntindx][1])."</td><td>Stopped</td><td>".$eventData[$evntindx][6]."</td><td>".date('d-m-Y H:i:s',$startTime)."</td><td>".$eventData[$evntindx][3]."</td><td>".TimeFormate($disdump)."</td><td>".round($eventData[$evntindx][8],5)."/".round($eventData[$evntindx][9],5)."</td></tr>";
                     //<td>".round($totalDis,2)."</td>
               //      $table=$table."<tr bgcolor='#F5F6CE'><td colspan='6'>Total Duration :</td><td>".TimeFormate($totalIdleTime)."</td><td colspan='2'></td></tr>";
               //      $table=$table."<tr bgcolor='#39f2f5'><td>".date('l',$eventData[$evntindx][10])."</td><td colspan='8'>".date('d-m-Y',$eventData[$evntindx][10])."</td></tr>";

                 }
                 $table=$table."<tr bgcolor='#F5F6CE'><td colspan='6'>Total Duration :</td><td>".TimeFormate($totalIdleTime)."</td><td colspan='2'></td></tr>";
                     $table=$table."<tr bgcolor='#39f2f5'><td>".date('l',$eventData[$evntindx][10])."</td><td colspan='8'>".date('d-m-Y',$eventData[$evntindx][10])."</td></tr>";

                 $totalIdleTime=0;
             }

             $totalDis=$totalDis+$eventData[$evntindx][4];
             //  $table  =$table."<tr><td>".$evntindx."</td><td>".$eventData[$evntindx][1]."</td><td>".$statusinfo."</td><td>".$eventData[$evntindx][0]."</td><td>".$eventData[$evntindx][6]."</td><td>".$eventData[$evntindx][3]."</td><td>".round($eventData[$evntindx][8],5)."/".round($eventData[$evntindx][9],5)."</td><td>".intval($eventData[$evntindx][5])."</td><td>".round($totalDis,2)."</td><td>".$eventData[$evntindx][11]."</td></tr>";
         }
         $table=$table."<tr bgcolor='#F5F6CE'><td colspan='6'>Total Duration :</td><td>".TimeFormate($totalIdleTime)."</td><td colspan='2'></td></tr>";

     }//end else
       
     echo $table."</tbody></table>";


function TimeFormate($timeDiff){
	$hours=0;
	$min=0;
	$sec=0;
        if ($timeDiff==0) {
            return 0;
        }
	if($timeDiff>=3600){
		$hours=intval($timeDiff/(60*60));
		$tempmin=$timeDiff%(60*60);
		$result=TimeFormate($tempmin);
		return $hours."Hr :".$result;
	}else if($timeDiff>=60){
		 $min=intval($timeDiff/60);
		 $tempsec=$timeDiff%60;
		 
		 return $min." Min :".TimeFormate($tempsec);
	}else{
	     return $timeDiff." sec";	
	}
	
}

function getEventData($vehicleID,$accountID,$fromDate,$toDate){
       global $gtsserver, $gtsusername, $gtspassword,$gtsdb,$eventData;
      
      $Query = " SELECT heading, geozoneID,timestamp,deviceID,FROM_UNIXTIME(timestamp) as 'time1',speedKPH,address,odometerKM+odometerOffsetKM as 'distance',statusCode,analog0,latitude,longitude FROM `EventData` where accountID='$accountID' and   deviceID='$vehicleID'  and timestamp between '$fromDate' and '$toDate' and latitude<>0 and longitude<>0 order by timestamp asc ";  //request from web for particular vehicle
  
       $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($gtsconnect));
        if(date('d-M-Y',$fromDate)==date('d-M-Y') || date('d-M-Y',$toDate)==date('d-M-Y')){
                  mysqli_select_db($gtsconnect,'gts');

         }else{
        //mysqli_select_db($gtsconnect,'gtshealth');
        mysqli_select_db($gtsconnect,'gts');

         }

//       mysqli_select_db($gtsdb, $gtsconnect);
       error_log($Query);
       $rs =mysqli_query($gtsconnect,$Query) or die ("Query error1: " . mysqli_error($gtsconnect));
       $num_rows = mysqli_num_rows($rs);
       $i=0; 
       while($row = mysqli_fetch_assoc($rs)) {
          // if($row['statusCode']=='62000' ||  $row['statusCode']=='61968'){ error_log($row['geozoneID']." ".$row['statusCode'].'  '.$row['time1']);}
        /*  if($i>=2){ //delete drift event compare 1 to second and first to third.
                 $dis2to1=distance1($eventData[$i-2][8],$eventData[$i-2][9], $eventData[$i-1][8], $eventData[$i-1][9]);
                 $dis1topresent=distance1($row['latitude'],$row['longitude'], $eventData[$i-2][8], $eventData[$i-2][9]);
                if($dis2to1>$dis1topresent){
                   $i--;
                } 

          }*/
          if($i==0){
              $startlatlong=round($row['latitude'],5).'-'.round($row['longitude'],5);
              $factory=$row['address'];
             $row['distance']=0;

         } 
           $eventData[$i][0]=$row['geozoneID'];
           $eventData[$i][1]=$row['deviceID'];
           $eventData[$i][2]=$row['statusCode'];
           $eventData[$i][3]=$row['time1'];
           $eventData[$i][4]=$row['distance'];
           $eventData[$i][5]=$row['speedKPH'];
           $eventData[$i][6]=$row['address'];
           $eventData[$i][7]=$row['analog0'];
           $eventData[$i][8]=$row['latitude'];
           $eventData[$i][9]=$row['longitude'];
           $eventData[$i][10]=$row['timestamp'];
           $eventData[$i][11]=findDirection($row['heading']);
           $eventData[$i][12]=$row['heading'];
           $endlatlong=round($row['latitude'],5).'-'.round($row['longitude'],5);
           if($i>0 && $row['latitude']!= 0 && $row['longitude']!=0 && $eventData[$i-1][8]!=0 && $eventData[$i-1][9]!=0) {

                 $eventtoeventdistance=distance1($row['latitude'],$row['longitude'], $eventData[$i-1][8], $eventData[$i-1][9]);
                 $difftimes=abs(strtotime($eventData[$i][3])-strtotime($eventData[$i-1][3]));
                 $maxDistanceTravelledperMint=2;//max km travelled per mint
                 $calculatedDistenceTravelledperMint=intval($eventtoeventdistance)/$difftimes;
                 if(is_nan($eventtoeventdistance) || ($eventtoeventdistance/$difftimes>0.025)){//if distnace greater than 2 km with 5 minits then distnace=0 bzc drift point in 
                    $eventData[$i][4]=0;
                 //   $i++;
                }else{
                   $eventData[$i][4]=$eventtoeventdistance;
                   $travelDistance=$travelDistance+$eventtoeventdistance;
                   $i++;
               }

              if(is_nan($eventData[$i][4])){
                 $data[$indx][7]=0;
              }


          }
         if($i==0){$i++;}
            
         //  $i++;
       }
       mysqli_close($gtsconnect);
}

function findDirection($degree){
  if($degree>=0 && $degree<10){return 'N';}
  else if($degree>10 && $degree<80){return 'NE';}

  else if($degree>=80 && $degree<100){return 'E';}
  else if($degree>=100 && $degree<170){return 'SE';}
  else if($degree>=170 && $degree<190){return 'S';}
  else if($degree>=190 && $degree<260){return 'SW';}
  else if($degree>=260 && $degree<280){return 'W';}
  else if($degree>=280 && $degree<350){return 'NW';}
  else if($degree>=350 && $degree<360){return 'N';}



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
function getZones(){
       global $gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$geozones;
       $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($gtsconnect));
       mysqli_select_db($gtsconnect,'gts');
       $query = "SELECT geozoneID,latitude1,longitude1,radius from Geozone where accountID='$accountID'";
           error_log($query);
       $qry_result = mysqli_query($gtsconnect,$query) or die(mysqli_error($gtsconnect));
       $i=0;
       while($row = mysqli_fetch_assoc($qry_result))
       {
         //error_log($row['geozoneID'].'  '.$row['latitude1'].'   '.$row['longitude1'].' '.$row['radius']);
           $geozones[$i][0]= $row['geozoneID'];
           $geozones[$i][1]= $row['latitude1'];
           $geozones[$i][2]= $row['longitude1'];
           $geozones[$i][3]= $row['radius'];
           
           $i++;
       }
       mysqli_close($gtsconnect);
       

   }
?>
