<?php

include 'config.php';
$id = $_GET['accountID'];
$userID = $_GET['userID'];
        $groupID='';
       if($userID != "admin" || $userID != "administrator"){
          $result=array();
          $result=getGroups($gtsserver, $gtsusername, $gtspassword,$gtsdb,$id,$userID);
          $groupID=$result[0];
          if($groupID=='' || $groupID=='all'){$userID='admin';}
        
        }
        
      $query="select a.lastEventTimestamp, a.deviceID as deviceID,a.description as description,simPhoneNumber,c.contactPhone from Device a,DeviceList b,Driver c where a.accountID='$id' and b.groupID='$groupID' and b.accountID='$id' and a.deviceID=b.deviceID and c.deviceID=a.deviceID order by deviceID,description";
      
     if($userID == "admin" || $userID == "administrator" || $userID=="tracking" || $userID=="monitoring"){
        

         $query="select  DISTINCT a.deviceID as deviceID,a.lastEventTimestamp,a.description as description,c.contactPhone from Device a,Driver c where a.accountID='$id' and c.accountID='$id' and a.deviceID=c.deviceID ";
      }
$gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($gtsconnect));
mysqli_select_db($gtsconnect,$gtsdb);
$ydata  =array(array());
$qry_result = mysqli_query($gtsconnect,$query) or die(mysqli_error($gtsconnect));
$j=0;
 while($row = mysqli_fetch_assoc($qry_result))
  {
	$ydata[$j][0]=$row['deviceID'];
        $ydata[$j][1]=$row['description'];
        $ydata[$j][2]=$row['contactPhone'];
        $ydata[$j][3]=$row['lastEventTimestamp'];

        $j++;                                    
  }
mysqli_close($gtsconnect);
$casemanagervehicles=array();
 $casemanagervehiclesindex=0;
       $server="track1.glovision.co";
       if($id==="als")
           $server="localhost";

   $track1conncetion=mysqli_connect($server, "root", "gl0v1s10n") or die ("Unable to connect to the database: " . mysqli_error($track1conncetion)); 
   mysqli_select_db($track1conncetion,"autocare_gts");
     $assignedquery="SELECT * FROM caseManager WHERE caseStatus='assigned'";       
  

         $result= mysqli_query($track1conncetion,$assignedquery) or die(mysqli_error($track1conncetion));
        
  $assigned=0;
    while($row = mysqli_fetch_assoc($result))
    {
           $assigned++;
 //      echo $row['attendcases'];
          $casemanagervehicles[$casemanagervehiclesindex]=$row['deviceID']."^".$row['callerContact']."^".$row['caseStatus'];
     //     $casemanagervehicles[$casemanagervehiclesindx][1]=$row['callerContact'];
       //    $casemanagervehicles[$casemanagervehiclesindx][2]=$row['caseStatus'];

         $casemanagervehiclesindex++;
        //  $assigned=$row['attendcases'];
      //     $assigned=$row['caseType'];
     }
  
    $inzone=0;
    $result1 = mysqli_query($track1conncetion,"SELECT * FROM caseManager WHERE caseStatus='inzone'");
     while($row = mysqli_fetch_assoc($result1))
    {
           $inzone++;
           $casemanagervehicles[$casemanagervehiclesindex]=$row['deviceID']."^".$row['callerContact']."^".$row['caseStatus'];
         // $casemanagervehicles[$casemanagervehiclesindx][1]=$row['callerContact'];
          // $casemanagervehicles[$casemanagervehiclesindx][2]=$row['caseStatus'];
           $casemanagervehiclesindex++;
     }
   mysqli_close($track1conncetion);
$analytics=array();
$analytics[0]=$j;//total vehicle
$analytics[1]=$assigned;//case assign
$analytics[2]=$inzone;//in zone
$analytics[3]=$j-($analytics[1]+$analytics[2]);//ready to take
echo '{"markers": ' . json_encode($analytics) .',"casemanagervehicles":'.json_encode($casemanagervehicles).'}';

function getDivisions($tacserver,$tacusername,$tacpassword,$tacdb,$accounts){
 // global  $allGroupsForAdmin; 
  //  global $divisions;
    $result=array();
   $tacconnect = mysqli_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database: " . mysqli_error($tacconnect));
   mysqli_select_db($tacdb, $tacconnect);
   $divisionquery="select DISTINCT divisionID from divisions where accountID='$accounts'";
    $qry1 = mysqli_query($divisionquery) or die(mysqli_error($tacconnect));
   $rowcount=0;
//  echo $divisionquery;
   error_log('hai2222222222  ');
   while($row = mysqli_fetch_assoc($qry1)){
//        $result[$rowcount]=$row['divisionID'].'-'.$row['groupID'];
        $result[$rowcount]=$row['divisionID'];
         //  echo 'ramana';
         $rowcount++;
   }
   if($rowcount==0){
       $result[$rowcount]='';
   }
   mysqli_close($tacconnect);
   return $result;


}

function getDivisionsGroups($tacserver,$tacusername,$tacpassword,$tacdb,$accounts){
 // global  $allGroupsForAdmin; 
   // global $divisions;
    $result=array();
   $tacconnect = mysqli_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database: " . mysqli_error($tacconnect));
   mysqli_select_db($tacdb, $tacconnect);
   $divisionquery="select * from divisions where accountID='$accounts'";
    $qry1 = mysqli_query($divisionquery) or die(mysqli_error($tacconnect));
   $rowcount=0;
   error_log('hai2222222222  ');
   while($row = mysqli_fetch_assoc($qry1)){
        $result[$rowcount]=$row['divisionID'].'-'.$row['groupID'];
   //     $divisions[$rowcount]=$row['divisionID'];
         $rowcount++;
   }
   if($rowcount==0){
       $result[$rowcount]='';
   }
   mysqli_close($tacconnect);
   return $result;


}



function getGroups($gtsserver, $gtsusername, $gtspassword,$gtsdb,$accounts,$userID){
    $result=array();
   $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($gtsconnect));
   mysqli_select_db($gtsdb, $gtsconnect);
  // $vehicleDetails="select vehicleModel,vehicleMake,lastValidLatitude,lastValidLongitude,deviceID from Device where accountID='$accounts'";
    $vehicleDetails='';
     
    if($userID == "admin" || $userID == "administrator"){
         $vehicleDetails="select DISTINCT groupID from DeviceGroup where accountID='$accounts'";
    }else{
         $vehicleDetails="select DISTINCT groupID from GroupList where accountID='$accounts' and userID='$userID'";
    }

   $qry1 = mysqli_query($vehicleDetails) or die(mysqli_error($gtsconnect));
   $rowcount=0;
   error_log('hai2222222222  ');
   while($row = mysqli_fetch_assoc($qry1)){
        $result[$rowcount]=$row['groupID'];
        $rowcount++;
       error_log($row['groupID'].'   kkkkkkkkkkkkkkkk');
   }
   if($rowcount==0){
       $result[$rowcount]='';
   }
   mysqli_close($gtsconnect);
   return $result;

}
?>
