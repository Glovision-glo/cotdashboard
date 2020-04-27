<?php

include 'config.php';

 //comment starts
	// Retrieve data from Query String
//$id = $_GET['accountID'];
	// Escape User Input to help prevent SQL Injection
$id = $_GET['accountID'];
$userID = $_GET['userID'];
        error_log();
	//build query
        $groupID='';
       if($userID != "admin" || $userID != "administrator"){
          $result=array(array());
          $result=getGroups($gtsserver, $gtsusername, $gtspassword,$gtsdb,$id,$userID);
          $groupID=$result[0][0];
          if($groupID=='' || $groupID=='all'){$userID='admin';}
        
        }
$allGroupsForAdmin=array(array());
$groupwithlatlng=array(array());
$groupwithlatlng=getGroupLatlng($id);        
//if($id=="gvk-up-102"){$gtsserver="94.23.211.49";$tacserver="94.23.211.49";}
//$groupID=$userID;
      error_log('----'.$userID);
      $query="select a.lastEventTimestamp, a.deviceID as deviceID,a.description as description,simPhoneNumber,c.contactPhone from Device a,DeviceList b,Driver c where a.accountID='$id' and b.groupID='$groupID' and b.accountID='$id' and a.deviceID=b.deviceID and c.deviceID=a.deviceID order by deviceID,description";
      if($id=="gvk-up-108" || $id=="als" || $id="gvk-up-102"){
               $query="select a.lastEventTimestamp, a.deviceID as deviceID,a.description as description,simPhoneNumber,c.contactPhone from Device a,DeviceList b,Driver c where a.accountID='$id' and b.groupID='$groupID' and b.accountID='$id' and a.deviceID=b.deviceID and c.accountID='$id' and c.deviceID=a.deviceID  order by a.deviceID,a.description";
 }
 // $query="select a.deviceID as deviceID,a.description as description,simPhoneNumber from Device a,DeviceList b where a.accountID='$id' and b.groupID='$groupID' and b.accountID='$id' and a.deviceID=b.deviceID  order by deviceID,description";
      $allGroupsForAdmin=getGroups($gtsserver, $gtsusername, $gtspassword,$gtsdb,$id,$userID); 
     if($userID == "admin" || $userID == "administrator" || $userID=="tracking" || $userID=="monitoring"){
          //$query = "SELECT DISTINCT deviceID,description,simPhoneNumber FROM Device WHERE accountID = '$id'  order by deviceID,description";
        
      //     $allGroupsForAdmin=getGroups($gtsserver, $gtsusername, $gtspassword,$gtsdb,$id,"admin");
// $query="select DISTINCT a.deviceID as deviceID,a.description as description,a.simPhoneNumber as simPhoneNumber from Device a,DeviceList b where a.accountID='$id' and b.accountID='$id' and a.deviceID=b.deviceID ";

         $query="select  DISTINCT a.deviceID as deviceID,a.lastEventTimestamp,a.description as description,c.contactPhone from Device a,Driver c where a.accountID='$id' and c.accountID='$id' and a.deviceID=c.deviceID ";
         if($id=="gvk-up-108" || $id=="als" || $id="gvk-up-102"){
$query="select  DISTINCT a.deviceID as deviceID,a.lastEventTimestamp,a.description as description,c.contactPhone from Device a,Driver c where a.accountID='$id' and c.accountID='$id' and a.deviceID=c.deviceID";
error_log($query);
}
      }

$gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error());
mysqli_select_db($gtsconnect,$gtsdb);
$ydata  =array(array());
$qry_result = mysqli_query($gtsconnect,$query) or die(mysqli_error());
 $display_string="";
  // Insert a new row in the table for each person returned
$j=0;
 while($row = mysqli_fetch_assoc($qry_result))
  {
	$ydata[$j][0]=$row['deviceID'];
        $ydata[$j][1]=strtoupper($row['description']);
        $ydata[$j][2]=$row['contactPhone'];
        $ydata[$j][3]=$row['lastEventTimestamp'];
 //       $ydata[$j][2]=$row['simPhoneNumber'];
        $j++;                                    
  }
mysqli_close($gtsconnect);
$divisions=array();
$groupsanddivisions=array();
$groupsanddivisions=getDivisionsGroups($tacserver,$tacusername,$tacpassword,$tacdb,$id);
$divisions=getDivisions($tacserver,$tacusername,$tacpassword,$tacdb,$id);
//$divisions = array_unique($divisions);
echo '{"markers": ' . json_encode(utf8ize($ydata) ) . ' ,"groups":'.json_encode($allGroupsForAdmin).',"divisions":'.json_encode($divisions).',"groupsdivisions":'.json_encode($groupsanddivisions).'}';

function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}


function getDivisions($tacserver,$tacusername,$tacpassword,$tacdb,$accounts){
 // global  $allGroupsForAdmin; 
  //  global $divisions;
    $result=array();
   $tacconnect = mysqli_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database: " . mysqli_error());
   mysqli_select_db($tacconnect, $tacdb);
   $divisionquery="select DISTINCT divisionID from divisions where accountID='$accounts'";
    $qry1 = mysqli_query($tacconnect, $divisionquery) or die(mysqli_error());
   $rowcount=0;
//  echo $divisionquery;
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
   mysqli_select_db($tacconnect,$tacdb);
   $divisionquery="select * from divisions where accountID='$accounts'";
   error_log($divisionsquery);
   $qry1 = mysqli_query($tacconnect, $divisionquery) or die(mysqli_error($tacconnect));
   $rowcount=0;
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
   global $groupwithlatlng;
    $result=array(array());
   $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error());
   mysqli_select_db($gtsconnect, $gtsdb);
  // $vehicleDetails="select vehicleModel,vehicleMake,lastValidLatitude,lastValidLongitude,deviceID from Device where accountID='$accounts'";
    $vehicleDetails='';
   /* if($userID == "admin" || $userID == "administrator" || $userID=="tracking" || $userID=="monitoring"){
         $vehicleDetails="select DISTINCT groupID,count(deviceID) as count from DeviceList  where accountID='$accounts' group by groupID";
    }else{
         $vehicleDetails="select DISTINCT groupID,count(deviceID) as count from DeviceList where accountID='$accounts' and userID='$userID' group by groupID";
    }*/
   if($userID == "admin" || $userID == "administrator" || $userID=="tracking" || $userID=="monitoring"){
         $vehicleDetails="select DISTINCT a.groupID,count(a.deviceID) as count from DeviceList a,DeviceGroup b  where a.accountID='$accounts' and b.accountID='$accounts' and a.groupID=b.groupID group by a.groupID";
    }else{
         $vehicleDetails="select DISTINCT a.groupID,count(a.deviceID) as count from DeviceList a,DeviceGroup b where a.accountID='$accounts' and a.groupID='$userID' and b.accountID='$accounts' and a.groupID=b.groupID group by a.groupID";
    }
   error_log($vehicleDetails);
   $qry1 = mysqli_query($gtsconnect, $vehicleDetails) or die(mysqli_error());
   $rowcount=0;
   while($row = mysqli_fetch_assoc($qry1)){
        $result[$rowcount][0]=$row['groupID'];
        $result[$rowcount][1]=$row['count'];
        $result[$rowcount][2]=0.0;
        $result[$rowcount][3]=0.0;
        for($glatlngindx=0;$glatlngindx<count($groupwithlatlng);$glatlngindx++){
           if(strtolower($row['groupID'])==strtolower($groupwithlatlng[$glatlngindx][0])){
               $result[$rowcount][2]=$groupwithlatlng[$glatlngindx][1];
               $result[$rowcount][3]=$groupwithlatlng[$glatlngindx][2];
              break;
          }

        }
        $rowcount++;
   }
   if($rowcount==0){
       $result[$rowcount]='';
   }
   mysqli_close($gtsconnect);
   return $result;

}
   function getGroupLatlng($id){
     global $id,$tacserver,$tacusername,$tacpassword,$tacdb;
     $result=array(array());
     $tacconnect = mysqli_connect("localhost", $tacusername, $tacpassword) or die ("Unable to connect to the database: " . mysqli_error($tacconnect));
     mysqli_select_db($tacconnect,$tacdb);
     $query="select groupID,lat,lng from Grouplatlng where accountID='gvk-up-108'";
     $qry1 = mysqli_query($tacconnect,$query) or die(mysqli_error());
     $rowcount=0;
      while($row = mysqli_fetch_assoc($qry1)){
        $result[$rowcount][0]=$row['groupID'];
        $result[$rowcount][1]=$row['lat'];
        $result[$rowcount][2]=$row['lng'];
        $rowcount++;
      }
      if($rowcount==0){
       $result[$rowcount]='';
      }
       mysqli_close($tacconnect);
      return $result;
}

?>
