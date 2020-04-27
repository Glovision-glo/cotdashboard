<?php 
    include 'config.php';
    date_default_timezone_set('Asia/Kolkata');
    $deviceID=$_GET['vehicle'];
    $group=$_GET['group'];
    $accountID=$_GET['accountID'];
    $userID=$_GET['userID'];
    $fromDate=$_GET['fromDate'];
    $toDate=$_GET['toDate'];
    $displayAccountID=$accountID;
    if($accountID=="gvk-up-108"){
       // $displayAccountID="Samajwadi Swasthya Sewa";
    }else if($accountID=="gvk-up-102"){
        $displayAccountID="National Ambulance Service";
    }
    $groupID='';
    $groupArray=array();
    
    if($group=="selectall"){
        if($userID != "admin" && $userID != "administrator"){
           $groupArray=getGroups($gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$userID);
           if(count($result)==0){
               $userID='admin';
               $groupArray=getGroups($gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$userID);
           }
        
        }else{
           $groupArray=getGroups($gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$userID);
        }
    }else{
        $groupArray[0]=$group;
    }
    $result1='';
    $result="<style type='text/css'>
.tablecss {
        
        width:100%;
        border-collapse: collapse;
        color:black;
        background:white;
        font-family:Arial,Verdana,sans-serif;
        font-size:13;
}
</style><div  style='background: white;border-style: solid;border-color: #2E2E2E;width:100%;'><table class='tablecss' style='width:100%;font-family:sans-serif; font-size: 8pt; color: #000000;align=left;'><thead style='background:#2E2E2E;color:white' bgcolor='#2E2E2E'><th>SNO</th><th>Vehicle No</th><th>Ignition On/Off</th><th>Date</th><th>Lat</th><th>Long</th><th>IMEI</th></thead><tbody>";
    $sno=0;
    for($gpindx=0;$gpindx<count($groupArray);$gpindx++){
        $lastLocaton=array(array());
        $groupID=$groupArray[$gpindx];
        getLastLocation();
        $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database2: " . mysqli_error($gtsconnect));
        mysqli_select_db($gtsconnect,$gtsdb);
        for($lve=0;$lve<count($lastLocaton);$lve++){
         //   $result=$result."<tr style='background:#2E2E2E;color:white' bgcolor='#2E2E2E'><th colspan='7'>".$lastLocaton[$lve][0]."</th></tr> ";
            $query = "select deviceID,analog0,statusCode,speedKPH,from_unixtime( timestamp, '%d-%m-%Y %h:%i:%s %p' ) as time1,odometerOffsetKM+odometerKM as 'odometerKM',latitude,longitude,address,analog1,heading as direction from EventData where accountID='$accountID' and deviceID='".$lastLocaton[$lve][0]."'  and timestamp between '$fromDate' and '$toDate'  and statusCode in('62465','62467') order by timestamp asc";//timestamp='".$lastLocaton[$lve][1]."' limit 1
            $qry_result = mysqli_query($gtsconnect,$query) or die(mysqli_error($gtsconnect));
             $rowcount=mysqli_num_rows($qry_result); 
            $x=0;
            $m='';
            $find=0;
            $sno=1;
            while($row = mysqli_fetch_assoc($qry_result))
            { 
               $power_on_off="Ignition Off";  
               if($row['statusCode']=="62465")
                   $power_on_off="Ignition On";
               if($x==0 || $x== $rowcount-1){
                   $find++;
                   $m=$m."<tr><td>".($sno++)."</td> <td>".strtoupper($lastLocaton[$lve][0])."</td> <td>".strtoupper($power_on_off)."</td> <td>".$row['time1']."</td> <td>".$row['latitude']."</td> <td>".$row['longitude']."</td> <td>".$lastLocaton[$lve][5]."</td></tr>";
                }
               $x++;
            }//end while
           if($find>0){
              $result=$result."<tr style='background:#2E2E2E;color:white' bgcolor='#2E2E2E'><th colspan='7'>".$lastLocaton[$lve][0]."</th></tr> ";
              $result=$result.$m;
           }
        } //end for
      
    }//end groups array
     mysqli_close($gtsconnect);
    $result=$result."</tbody></table>";
   echo "<html><body><center><div>
          <img alt='' src='".$image."'>
         </div><h5>AccountID:<b style='font-size:16px' >".$displayAccountID."</b><br>UserID:<b style='font-size:16px' > $userID</b><br>Ignition Report <br>From :".date('d-m-Y h:i:s a',$fromDate)."To :".date('d-m-Y h:i:s a',$toDate)."</h5>".$result."</center></body></html>";


function getLastLocation(){
    global $gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$lastLocaton,$userID,$groupID,$deviceID,$fromDate,$toDate;
    $now=strtotime("10 October 2018");
    $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database4: " . mysqli_error($gtsconnect));
    mysqli_select_db($gtsconnect,$gtsdb);
    $query="select a.isActive as isActive, b.groupID,a.deviceID as deviceID,a.lastGPSTimestamp as lastGPSTimestamp,a.description as description,a.vehicleModel as vehicleModel,a.uniqueID as uniqueID from Device a,DeviceList b where a.accountID='$accountID' and b.groupID='$groupID' and b.accountID='$accountID' and a.deviceID=b.deviceID";
    if(($userID == "admin" || $userID == "administrator") && $groupID=="selectall"){
        $query = "SELECT a.isActive as isActive, a.deviceID as deviceID,a.lastGPSTimestamp as lastGPSTimestamp,a.description as description,a.vehicleModel as vehicleModel,a.uniqueID as uniqueID FROM Device a WHERE a.accountID ='$accountID'";
       
    }
    if($deviceID != 'selectall'){
        $query = "SELECT a.isActive as isActive, a.deviceID as deviceID,a.lastGPSTimestamp as lastGPSTimestamp,a.description as description,a.vehicleModel as vehicleModel,a.uniqueID as uniqueID FROM Device a  WHERE a.accountID ='$accountID' and a.deviceID='$deviceID'";
    }
    error_log($query);
    $qry_result = mysqli_query($gtsconnect,$query) or die(mysqli_error($gtsconnect));
    $i=0;
    while($row = mysqli_fetch_assoc($qry_result)){
    if($row['isActive']==1||($row['isActive']==0 && ($fromDate<$now || $toDate<$now))){
        $lastLocaton[$i][0]=$row['deviceID'];
        $lastLocaton[$i][1]=$row['lastGPSTimestamp'];
        $lastLocaton[$i][2]=$row['description'];
        $lastLocaton[$i][3]=$row['vehicleModel'];
        $lastLocaton[$i][4]=$row['simPhoneNumber'];
        $lastLocaton[$i][5]=$row['uniqueID'];
     //    echo $row['deviceID']."  ".$row['groupID']."<br>";
        $i++;
    }
    }
    mysqli_close($gtsconnect);
}

function getGroups($gtsserver, $gtsusername, $gtspassword,$gtsdb,$accounts,$userID){
   $result=array();
   $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database5: " . mysqli_error($gtsconnect));
   mysqli_select_db($gtsconnect,$gtsdb);
   $vehicleDetails='';
   $groupsforadmin="'ahmedabad','amreli','anand','aravalli','banaskantha','bharuch','bhavnagar','botad','chhotaudepur','dahod','devbhumidwarka','gandhinagar','girsomnath','jamnagar','junagadh','kheda','kutch','mahesana','mahisagar','morbi','narmada','navsari','panchmahals','patan','porbandar','rajkot','sabarkantha','surat','surendranagar','tapi','thedangs','vadodara','valsad'";
   if($userID == "admin" || $userID == "administrator"){
       $vehicleDetails="select DISTINCT groupID from DeviceGroup where accountID='$accounts' and groupID in ($groupsforadmin)";
   }else{
       $vehicleDetails="select DISTINCT groupID from GroupList where accountID='$accounts' and userID='$userID'";
   }
   error_log($vehicleDetails);
   $qry1 = mysqli_query($gtsconnect, $vehicleDetails) or die(mysqli_error($gtsconnect));
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



?>
