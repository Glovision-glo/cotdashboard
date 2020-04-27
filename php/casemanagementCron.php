<?php

   include 'config.php';
   $accountID = $_GET['accountID'];
   $track1conncetion=mysqli_connect("track1.glovision.co", "root", "gl0v1s10n") or die ("Unable to connect to the database: " . mysqli_error($track1conncetion)); 
   mysqli_select_db($track1conncetion,"autocare_gts");
   $assignedquery="SELECT * FROM caseManager WHERE caseStatus='assigned' or caseStatus='inzone'";      
  /////echo  
   $result= mysqli_query($track1conncetion,$assignedquery) or die(mysqli_error($track1conncetion));
   while($row = mysqli_fetch_assoc($result))
    {
      //  echo  $row['deviceID'].' '.$row['caseID'].'  '.$row['caseStatus'];// 	incidentLatitude 	incidentLongitude 	caseTimestamp 	caseType 	caseStatus 	caseDistance 	callerName 	callerContact
        $latlng=getLastLocation($row['deviceID']);
        $distance=intval(distance1($latlng[2], $latlng[3],$row['incidentLatitude'],$row['incidentLongitude'])*1000);//meters
       if($row['caseStatus']=="assigned" && $distance<=500){
           //vehicle reached to incidentlocation
           $conn=mysqli_connect("track1.glovision.co", "root", "gl0v1s10n") or die ("Unable to connect to the database: " . mysqli_error($conn));
               mysqli_select_db($conn,"autocare_gts");
            $update="update caseManager set caseStatus='inzone' where deviceID='".$row['deviceID']."' and caseID='".$row['caseID']."'";
            mysqli_query($conn,$update) or die(mysqli_error($conn));
           mysqli_close($conn); 
        }
       if($row['caseStatus']=="inzone" && $distance>=500){
          //vehicle left from incident location means case completd
              $conn=mysqli_connect("track1.glovision.co", "root", "gl0v1s10n") or die ("Unable to connect to the database: " . mysqli_error($conn));
               mysqli_select_db($conn,"autocare_gts");
              $update="update caseManager set caseStatus='complete',caseCloseTime='".time()."' where deviceID='".$row['deviceID']."' and caseID='".$row['caseID']."'";
               mysqli_query($conn,$update) or die(mysqli_error($conn));
              mysqli_close($conn);
        }


        echo $latlng[0].$latlng[1].$latlng[2].$latlng[3].'  '.$row['incidentLatitude'].$row['incidentLongitude'].'Distacne:'.$distance.'case Status:'.$row['caseStatus'].'<br>';
    }
   mysqli_close($track1conncetion);


function getLastLocation($deviceID){
     global $gtsserver, $gtsusername, $gtspassword,$gtsdb;
     $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($gtsconnect));
     mysqli_select_db($gtsconnect,$gtsdb);
     $query="select deviceID,lastEventTimestamp,description,lastValidLatitude,lastValidLongitude from Device where deviceID='$deviceID'";

    error_log($query);
    $qry_result = mysqli_query($gtsconnect,$query) or die(mysqli_error($gtsconnect));
    $i=0;
    $latlng=array(); 
    while($row = mysqli_fetch_assoc($qry_result)){
       $latlng[0]=$row['deviceID'];
       $latlng[1]=$row['lastGPSTimestamp'];
       $latlng[2]=$row['lastValidLatitude'];
       $latlng[3]=$row['lastValidLongitude'];
     //  echo $row['lastValidLatitude'].' '.$row['lastValidLongitude'];

   
           }
   
    mysqli_close($gtsconnect);
    return $latlng;
}
function distance1($lat1, $lon1, $lat2, $lon2) {
        if($lat1 == $lat2 && $lon1 == $lon2){
            return 0;

        }else{$theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            return $miles* 1.609344;
        }
    }





?>
