<?php
 require_once 'Mail.php';
  include 'config.php';
include 'pdfcretor.php';
include 'Excelgenerator.php';
  date_default_timezone_set('Asia/Kolkata');
  $accountID=$_GET['accountID'];

getAccountGroups($accountID);

function getAccountGroups($accountID) {
global $gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$group;
   $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($gtsconnect));
   mysqli_select_db($gtsconnect,$gtsdb);

       $query="select distinct groupID from DeviceList where accountID='$accountID' order by groupID asc" ;
       $qry1 = mysqli_query($gtsconnect,$query) or die(mysqli_error($gtsconnect));
       $i=0;
       while($row = mysqli_fetch_assoc($qry1)){
          $group[$i]=$row['groupID'];
          $i++;
       }
}
mysqli_close($gtsconnect);

for($a=0;$a<count($group);$a++){
$groupname=$group[$a];
    // create curl resource
        $ch = curl_init();
// echo "http://glotrax.glovision.co/dashboard/php/vehiclesStatus.php?&accountID=$accountID&userID=admin&currentLocation=currentlocation&formate=html&selectedgroup=$groupname<br>"; 
 // echo "http://up108.glovision.co/distanceidlereport/distanceIdle.php?accountID=gvk-up-181&vehicleID=selectall&group=$groupname&reportFormate=html&offlinetime=30&idletime=10<br>";
        // set url
       // curl_setopt($ch, CURLOPT_URL, "http://glotrax.glovision.co/dashboard/php/vehiclesStatus.php?&accountID=$accountID&userID=admin&currentLocation=currentlocation&formate=html&selectedgroup=$groupname");
        curl_setopt($ch, CURLOPT_URL, "http://up108.glovision.co/distanceidlereport/distanceIdle.php?accountID=$accountID&vehicleID=selectall&group=$groupname&reportFormate=html&offlinetime=30&idletime=10");
    //  echo 'rama';
        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);
      // echo $output.'ram';
        // close curl resource to free up system resources
        curl_close($ch);  

}

?>

