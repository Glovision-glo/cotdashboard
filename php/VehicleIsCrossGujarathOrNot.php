<?php
  /**
* Author: CodexWorld
* Author URI: http://www.codexworld.com
* Function Name: getAddress()
* $latitude => Latitude.
* $longitude => Longitude.
* Return =>  Address of the given Latitude and longitude.
**/
require_once('encodeurl1.php');
require_once 'PolylineEncoder1.php';
require_once 'Mail.php';
set_time_limit(0);
//$accountID="goghealth";
$accountID=$_GET['accountID'];
//$gtsserver="gj.glovision.co";
$gtsserver="localhost";
$gtsusername="root";
$gtspassword="gl0v1s10n";
$gtsdb ="gts";
$userID="admin";
$lastLocaton=array(array());
$table="<table style='border: 1px solid black;'><tr><th>Device ID</th> <th>Base Location </th><th>Driver Contact</th> <th>Assigned State </th><th>Current State </th><th>Time</th></tr>";
getLastLocation();
$found=0;
for($x=0;$x<count($lastLocaton);) {
if($lastLocaton[$x][10]!="" && $lastLocaton[$x][11]!=""){
   $cd=getAddress($lastLocaton[$x][10],$lastLocaton[$x][11]);
   if($cd!=""){
      //echo $lastLocaton[$x][0]."---A D".$lastLocaton[$x][6]."---CD ".$cd."<br>";
      $cd=strtolower(trim(str_replace("br","",preg_replace("/[^a-zA-Z]/", "", $cd))));
      $lastLocaton[$x][6]="gujarat";//strtolower(trim(str_replace("br","",preg_replace("/[^a-zA-Z]/", "",  $lastLocaton[$x][6]))));

     // $percent=wordMatchingPercent($cd,$lastLocaton[$x][6]);
     // if(strtolower(trim($cd))!=strtolower(trim($lastLocaton[$x][6]))){
      if($cd!=$lastLocaton[$x][6]){//vehicle boundary check based on percent
           $found++;
          $table=$table."<tr><td>".$lastLocaton[$x][0]."</td><td>".$lastLocaton[$x][8]."</td><td>".$lastLocaton[$x][4]."</td>  <td>".$lastLocaton[$x][6]."</td><td>".strtolower($cd)."</td><td>".date('d-m-Y H:i:s',$lastLocaton[$x][1])."</td></tr>";
        }
      //  }
      $x++;
    }

    
}else{
  $x++;
}
//if($x==10)break;

}
//echo getAddress(24.17174,72.41535);
$table=$table."</table>";
if($found>0){
echo $table;
$mailperAccount=array();
#$mailperAccount[0]="support@glovision.co";
#$mailperAccount[1]="gujaratmmu@gmail.com";
$mailperAccount[0]="tushar_shroff@emri.in";

mailSendToAccount($mailperAccount,$table,$accountID,"Vehicles Crossing Assigned State Report");
}
function getAddress($latitude,$longitude){
    //$geocodeFromLatLong = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($latitude).','.trim($longitude).'&sensor=true_or_false&key=GoogleAPIKey');
    if(!empty($latitude) && !empty($longitude)){
        //Send request and receive json data by address
        // echo  'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($latitude).','.trim($longitude).'&sensor=false'."<br>"; 
         $url=signUrl('http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($latitude).','.trim($longitude).'&sensor=false&client=gme-gvkemergencymanagement3', 'd1CkkvZsTH1jgTgvsZvvYRaSDSc=');
       echo $url;
      //  $geocodeFromLatLong = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($latitude).','.trim($longitude).'&sensor=false'); 
       $geocodeFromLatLong = file_get_contents($url); 
        $output = json_decode($geocodeFromLatLong);
        $status = $output->status;
        //echo $output;
        //Get address from json data
//        $address = ($status=="OK")?$output->results[1]->formatted_address:'';
        $address = '';
        if($status=="OK"){
          foreach($output->results[0]->address_components as $x){
               //  echo var_dump($x);
                if($x->types[0]=="administrative_area_level_1"){
                   $address=  $x->long_name."<br>";
                }
          } 
        /*  echo $output->results[0]->address_components[0]->long_name."<br>";
           echo "locality :".$output->results[0]->address_components[1]->long_name."<br>";
          echo "District :".$output->results[0]->address_components[2]->long_name."<br>";
          echo "State :".$output->results[0]->address_components[3]->long_name; */
        }
        // echo $output->results[1];
        //Return address of the given latitude and longitude
        if(!empty($address)){
            return $address;
        }else{
            return false;
        }
    }else{
        return false;   
    }

}
 

function getLastLocation(){
    global $gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$lastLocaton,$userID;
    $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database1: " . mysqli_error($gtsconnect));
    mysqli_select_db($gtsconnect,$gtsdb);
    $vehicleGroup=array();
    $vehiclecontact=array();
    $vehicleGroup=getGroupVehicle();
     $vehiclecontact=  vehiclecontact();
    $query="select DISTINCT a.deviceID as deviceID,a.maintNotes,a.statusCodeState,a.lastValidHeading,a.lastValidSpeedKPH,a.lastValidLatitude,a.lastValidLongitude,a.lastGPSTimestamp as lastGPSTimestamp,a.description as description,a.vehicleModel as vehicleModel,a.uniqueID as uniqueID,a.simPhoneNumber as simnum,a.lastStartTime,a.lastStopTime from Device a where a.accountID='$accountID'  order by a.lastGPSTimestamp";
    $qry_result = mysqli_query($gtsconnect,$query) or die(mysqli_error($gtsconnect));
    $i=0;
    while($row = mysqli_fetch_assoc($qry_result)){
        $lastLocaton[$i][0]=$row['deviceID'];
        $lastLocaton[$i][1]=$row['lastGPSTimestamp'];
        $dis11 =explode("]",$row['description']);
          $vehiclebaselocation=trim($dis11[1],'[');
        if($userID == "admin" || $userID == "administrator" || $userID=="tracking" || $userID=="monitoring" || $userID == "avoidDuplecate"){
              if($vehicleGroup[strtolower($row['deviceID'])]=="" || $vehicleGroup[strtolower($row['deviceID'])]==null){  $vehicleGroup[strtolower($row['deviceID'])]="ungroup";}
             $lastLocaton[$i][2]="[".$row['deviceID']."][".$vehiclebaselocation."][".$vehicleGroup[strtolower($row['deviceID'])]."]";
        } else{
              if($row['groupID']==""){$row['groupID']="ungroup";}
              $lastLocaton[$i][2]="[".$row['deviceID']."][".$vehiclebaselocation."][".$row['groupID']."]";
           }

//       $lastLocaton[$i][2]="[".$row['deviceID']."][".$row['groupID']."][".$row['groupID']."]" ;//strtoupper($row['description']);
       $lastLocaton[$i][3]=$row['vehicleModel'];
       $lastLocaton[$i][4]=$vehiclecontact[strtolower($row['deviceID'])]==null?"0000000000":$vehiclecontact[strtolower($row['deviceID'])];//$row['simPhoneNumber'];
       $lastLocaton[$i][5]=$row['uniqueID'];
       $lastLocaton[$i][6]=$vehicleGroup[strtolower($row['deviceID'])];
     $lastLocaton[$i][7]=$row['simnum'];
      $lastLocaton[$i][8]=$vehiclebaselocation;
      $lastLocaton[$i][9]=$row['lastStopTime'];
     $lastLocaton[$i][10]=round($row['lastValidLatitude'],5);
      $lastLocaton[$i][11]=round($row['lastValidLongitude'],5);

  $lastLocaton[$i][12]=$row['maintNotes'];
      $lastLocaton[$i][13]=$row['statusCodeState'];
     $lastLocaton[$i][14]=$row['lastValidHeading'];
     $lastLocaton[$i][15]=$row['lastValidSpeedKPH'];

          $i++;
   }
   //echo $i.'count';
mysqli_close($gtsconnect);
}

  function getGroupVehicle(){
     global $id,$gtsserver,$gtsusername,$gtspassword,$gtsdb,$accountID;
     $result=array();
     $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database2: " . mysqli_error($tacconnect));
     mysqli_select_db($gtsconnect,$gtsdb);
      $groupsforadmin="'ahmedabad','amreli','anand','aravalli','banaskantha','bharuch','bhavnagar','botad','chhotaudepur','dahod','devbhumidwarka','gandhinagar','girsomnath','jamnagar','junagadh','kheda','kutch','mahesana','mahisagar','morbi','narmada','navsari','panchmahals','patan','porbandar','rajkot','sabarkantha','surat','surendranagar','tapi','thedangs','vadodara','valsad'";
     $query="select groupID,deviceID from DeviceList where accountID='$accountID' and groupID in ($groupsforadmin)";

     $qry1 = mysqli_query($gtsconnect,$query) or die(mysqli_error($gtsconnect));
     $rowcount=0;
      while($row = mysqli_fetch_assoc($qry1)){
        $result[strtolower($row['deviceID'])]=$row['groupID'];
      }
      if($rowcount==0){
       $result[]='';
      }
       mysqli_close($gtsconnect);
      return $result;
} 

function vehiclecontact(){
  global $gtsserver, $gtsusername, $gtspassword,$gtsdb,$accountID,$userID,$groupID;
$gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($gtsconnect));
mysqli_select_db($gtsconnect,$gtsdb);
   $query="select deviceID,contactPhone from Driver  where accountID='$accountID'";
   $lastLocaton=array();
  $qry_result = mysqli_query($gtsconnect,$query) or die(mysqli_error($gtsconnect));
   while($row = mysqli_fetch_assoc($qry_result)){
       $lastLocaton[strtolower($row['deviceID'])]=$row['contactPhone'];
   }
   mysqli_close($gtsconnect);
   return $lastLocaton;
}

function mailSendToAccount($mailperAccount,$totalReport,$accountID,$reportType){
  //echo $totalReport;
if ($totalReport!=""){
    $from = " GLOVISION Support <support@glovision.co>";
    $host ="ssl://mail.glovision.co";
    $username = "alerts@glovision.co";
    $password = 'Gl0v1$ion12';
    error_log(count($mailperAccount).' num of accounts');
    for ($mailIndx=0;$mailIndx<count($mailperAccount);$mailIndx++){
        $to ="Recipient <".$mailperAccount[$mailIndx].">";
        $headers = array ('MIME-Version' => '1.0rn',
        'Content-Type' => "text/html; charset=ISO-8859-1rn",
        'From' => $from,
        'To' => $to,
        'Subject' =>$accountID.' '.$reportType
        );
        error_log('1');
        $smtp = Mail::factory('smtp',
        array ('host' => $host,
           'auth' => true,
           'port' =>'465',
           'username' => $username,
           'password' => $password));

        $mail = $smtp->send($to,$headers,$totalReport);
        error_log('3');
        if (PEAR::isError($mail)) {
           error_log('222222222222222');
           echo("<p>" . $mail->getMessage() . "</p>");
        } else {
          error_log('ddddddddddd');
           echo("<p>Message successfully sent!</p>");
        }
    }
}
}

function wordMatchingPercent($usrKeywords,$dbKeywords){
$user_keywords_unique =str_split($usrKeywords);
$db_keywords_unique =str_split($dbKeywords );
//  unset($user_keywords_unique[1]);
$count=0;
foreach ($user_keywords_unique as $a){
   foreach ($db_keywords_unique as $key=>$b){
        if($a==$b){
           unset($db_keywords_unique[$key]);
           $count++;
           break;
        }
   }
}

$percentage = intval(100*($count/count($user_keywords_unique)));

 return $percentage;
}
?>
