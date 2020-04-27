<?php

include 'config.php';

 //comment starts
	// Retrieve data from Query String
//$id = $_GET['accountID'];
	// Escape User Input to help prevent SQL Injection
$id = $_GET['accountID'];
$userID = $_GET['userID'];
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
error_log('login :'.$id." userid :".$userID);
//$groupwithlatlng=getGroupLatlng();        
//$groupID=$userID;
$vehicleGroup=array();
      $query="select a.lastEventTimestamp, a.deviceID as deviceID,a.description as description,b.groupID,a.simPhoneNumber as simPhoneNumber from Device a,DeviceList b where a.accountID='$id' and b.groupID='$groupID' and b.accountID='$id' and a.deviceID=b.deviceID  and a.isActive=1 order by a.deviceID";
      $allGroupsForAdmin=getGroups($gtsserver, $gtsusername, $gtspassword,$gtsdb,$id,$userID); 
     if($userID == "admin" || $userID == "administrator" || $userID=="tracking" || $userID=="monitoring"){
          $vehicleGroup=getGroupVehicle();
         $query="select  DISTINCT a.deviceID as deviceID,a.lastEventTimestamp,a.description as description,a.simPhoneNumber as simPhoneNumber from Device a where a.accountID='$id' and a.isActive=1 order by a.deviceID";
      }
$gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error());
mysqli_select_db($gtsconnect,$gtsdb);
$ydata  =array(array());
$qry_result = mysqli_query($gtsconnect,$query) or die(mysqli_error());
 $display_string="";
  // Insert a new row in the table for each person returned
$j=0;
$ungroupvehiclecount=0; 
 while($row = mysqli_fetch_assoc($qry_result))
  {
//        error_log($row['deviceID'].'     lsssssssssss');
      $dis11 =explode("]",$row['description']);
          $vehiclebaselocation=trim($dis11[1],'[');
        if($vehiclebaselocation==""){$vehiclebaselocation=$vehicleGroup[$row['deviceID']];} 

	$ydata[$j][0]=$row['deviceID'];
        if($userID == "admin" || $userID == "administrator" || $userID=="tracking" || $userID=="monitoring"){
             if($vehicleGroup[$row['deviceID']]=="ungroup" || $vehicleGroup[$row['deviceID']]=="" || $vehicleGroup[$row['deviceID']]==null){  $vehicleGroup[$row['deviceID']]="ungroup"; $ungroupvehiclecount++;} 
             $ydata[$j][1]="[".$row['deviceID']."][".$vehiclebaselocation."][".$vehicleGroup[strtolower($row['deviceID'])]."]";
        } else{
               if($row['groupID']==""){$row['groupID']="ungroup"; $ungroupvehiclecount++;}             

             $ydata[$j][1]="[".$row['deviceID']."][".$vehiclebaselocation."][".$row['groupID']."]";
           }
        $ydata[$j][2]="00000000";//driver number

        $ydata[$j][3]=$row['lastEventTimestamp'];
        $ydata[$j][4]=$row['simPhoneNumber'];
 //       $ydata[$j][2]=$row['simPhoneNumber'];
        $j++;                                    
  }
$ungroupAdd="no";
for($x=0;$x<count($allGroupsForAdmin);$x++){
  
   if($allGroupsForAdmin[$x][0]=="ungroup"){
      $ungroupAdd="yes";
      $allGroupsForAdmin[$x][1]  =  $ungroupvehiclecount;break;
 
     }
}
if($ungroupAdd=="no" && $ungroupvehiclecount>0){
        $in=count($allGroupsForAdmin);
      $allGroupsForAdmin[$in][0]  =  "ungroup";
    $allGroupsForAdmin[$in][1]  =  $ungroupvehiclecount;
     $allGroupsForAdmin[$in][2]=0.0;
     $allGroupsForAdmin[$in][3] =0.0;
}
mysqli_close($gtsconnect);
$divisions=array();
$groupsanddivisions=array();
//$groupsanddivisions=getDivisionsGroups($tacserver,$tacusername,$tacpassword,$tacdb,$id);
//$divisions=getDivisions($tacserver,$tacusername,$tacpassword,$tacdb,$id);
//$divisions = array_unique($divisions);
//echo '{"markers": ' . json_encode($ydata ) . ' ,"groups":'.json_encode($allGroupsForAdmin).',"divisions":'.json_encode($divisions).',"groupsdivisions":'.json_encode($groupsanddivisions).'}';
echo '{"markers": ' . json_encode(utf8ize($ydata) ) . ' ,"groups":'.json_encode(utf8ize($allGroupsForAdmin)).',"divisions":'.json_encode(utf8ize($divisions)).',"groupsdivisions":'.json_encode(utf8ize($groupsanddivisions)).'}';
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
    $groupsforadmin="'ahmedabad','amreli','anand','aravalli','banaskantha','bharuch','bhavnagar','botad','chhotaudepur','dahod','devbhumidwarka','gandhinagar','girsomnath','jamnagar','junagadh','kheda','kutch','mahesana','mahisagar','morbi','narmada','navsari','panchmahals','patan','porbandar','rajkot','sabarkantha','surat','surendranagar','tapi','thedangs','vadodara','valsad'";
   if($userID == "admin" || $userID == "administrator" || $userID=="tracking" || $userID=="monitoring"){
         if($accounts=="gvk-ut-108" || $accounts=="gvk-ut-health"){
              $vehicleDetails="select a.groupID,count(a.deviceID) as count from DeviceList a,DeviceGroup b,Device c  where a.accountID='$accounts' and b.accountID='$accounts' and a.groupID=b.groupID  and a.deviceID=c.deviceID and a.accountID=c.accountID and c.isActive=1 group by a.groupID";
         }else{
           $vehicleDetails="select a.groupID,count(a.deviceID) as count from DeviceList a,DeviceGroup b,Device c  where a.accountID='$accounts' and b.accountID='$accounts' and a.groupID=b.groupID  and a.groupID in ($groupsforadmin) and a.deviceID=c.deviceID and a.accountID=c.accountID and c.isActive=1 group by a.groupID";
         }
    }else{
      //   $vehicleDetails="select DISTINCT a.groupID,count(a.deviceID) as count from DeviceList a,DeviceGroup b where a.accountID='$accounts' and a.groupID='$userID' and b.accountID='$accounts' and a.groupID=b.groupID group by a.groupID";
           $vehicleDetails="select a.groupID,count(a.deviceID) as count from DeviceList a,DeviceGroup b,Device c where a.accountID='$accounts' and a.groupID in (select groupID from GroupList where accountID='$accounts' and userID= '$userID') and b.accountID='$accounts' and a.groupID=b.groupID and a.deviceID=c.deviceID and a.accountID=c.accountID and c.isActive=1  group by a.groupID";


    }
   $qry1 = mysqli_query($gtsconnect, $vehicleDetails) or die(mysqli_error());
   $rowcount=0;
   while($row = mysqli_fetch_assoc($qry1)){
         if($row['groupID']==""){$row['groupID']="ungroup";}                      

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
   function getGroupLatlng(){
     global $id,$tacserver,$tacusername,$tacpassword,$tacdb,$accountID;
     $result=array(array());
     $tacconnect = mysqli_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database: " . mysqli_error($tacconnect));
     mysqli_select_db($tacconnect,$tacdb);
     $query="select groupID,lat,lng from Grouplatlng where accountID='$id'";
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

  function getGroupVehicle(){
     global $id,$gtsserver,$gtsusername,$gtspassword,$gtsdb,$accountID;
     $result=array();
     $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($tacconnect));
     mysqli_select_db($gtsconnect,$gtsdb);
      $groupsforadmin="'ahmedabad','amreli','anand','aravalli','banaskantha','bharuch','bhavnagar','botad','chhotaudepur','dahod','devbhumidwarka','gandhinagar','girsomnath','jamnagar','junagadh','kheda','kutch','mahesana','mahisagar','morbi','narmada','navsari','panchmahals','patan','porbandar','rajkot','sabarkantha','surat','surendranagar','tapi','thedangs','vadodara','valsad'";
     $query="select groupID,deviceID from DeviceList where accountID='$id' and groupID in ($groupsforadmin)";
     if($id=="gvk-ut-108" || $id=="gvk-ut-health"){
            $query="select groupID,deviceID from DeviceList where accountID='$id'";

     }
     $qry1 = mysqli_query($gtsconnect,$query) or die(mysqli_error($gtsconnect));
     $rowcount=0;
      while($row = mysqli_fetch_assoc($qry1)){
         if($row['groupID']==""){$row['groupID']="ungroup";}          
                   
        $result[strtolower($row['deviceID'])]=$row['groupID'];
      }
      if($rowcount==0){
       $result[]='';
      }
       mysqli_close($gtsconnect);
      return $result;
}


?>
