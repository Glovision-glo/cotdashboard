<?php
  /**
* Author: CodexWorld
* Author URI: http://www.codexworld.com
* Function Name: getAddress()
* $latitude => Latitude.
* $longitude => Longitude.
* Return =>  Address of the given Latitude and longitude.
**/
include 'checkPointLocationInPolygon.php';
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

/*

gujarath boundary

*/
$polygon= array("24.570402,71.127134","24.690243,71.654478","24.610362,72.357603","24.570402,72.577329","24.410436,72.75311","24.290328,72.972837","24.089896,73.280454","23.848962,73.368345","23.607581,73.675962","23.446412,73.807798","23.244674,74.15936","23.042631,74.379087","22.75926,74.466978","22.59707,74.335142","22.556492,74.214292","22.536199,74.082456","22.505753,74.07147","22.472525,74.083512","22.462373,74.193375","22.447143,74.23732","22.365888,74.209855","22.360808,74.072525","22.294751,74.078019","22.218492,74.083512","22.213407,74.121964","22.137104,74.14943","22.03021,74.127457","21.958903,74.127457","21.897754,74.023087","21.764601,73.795166","21.764601,73.795166","21.596725,73.797867","21.535422,73.946183","21.535422,74.02858","21.540531,74.13295","21.560968,74.259293","21.520092,74.325211","21.397394,73.973648","21.156814,73.616593","20.94605,73.723755","20.900448,73.902237","20.777237,73.94069","20.576803,73.742936","20.576803,73.649552","20.674483,73.479264","20.648784,73.429825","20.566517,73.468277","20.458475,73.440812","20.412148,73.413346","20.324603,73.418839","20.206081,73.402359","20.175147,73.221085","20.175147,73.138688","20.206081,73.001358","20.123577,72.940934","20.154521,72.781632","20.13905,72.7267","20.47906,72.869523","20.756691,72.84755","21.023559,72.74318","21.458756,72.605851","21.744767,72.51796","22.233747,72.655289","22.299833,72.441056","22.18289,72.342179","22.009841,72.237809","21.709047,72.270768","21.530312,72.254288","21.305304,72.144425","20.828587,71.32045","20.715593,70.804093","20.926105,70.331681","21.60694,69.606583","21.872268,69.315445","22.294751,68.903458","22.457296,69.06276","22.82739,69.068253","23.05503,68.766129","23.337779,68.42006","23.554483,68.183854","23.94666,68.238785","23.97176,68.711197","24.297603,68.826554","24.28759,68.947403","24.26756,69.628556","24.1824,69.787857","24.297603,70.128434","24.4077,70.589859","24.277575,70.578873","24.222482,70.815079","24.367676,70.99086","24.4077,71.139176","24.577661,71.012833","24.667548,71.106217","24.588436,71.197325","24.576976,71.146917");

$lastLocaton=array(array());
$table="<table style='border: 1px solid black;'><tr><th>Group ID</th><th>Device ID</th> <th>Base Location </th><th>Driver Contact</th> <th> Current Location </th><th>Time</th></tr>";
getLastLocation();
$found=0;
$pointLocation = new pointLocation();
for($x=0;$x<count($lastLocaton);$x++) {
    if($lastLocaton[$x][10]!="" && $lastLocaton[$x][11]!=""){
        $status=$pointLocation->pointInPolygon($lastLocaton[$x][10].",".$lastLocaton[$x][11], $polygon);
        if($status=="outside"){
            $found++;
            $table=$table."<tr><td>".$lastLocaton[$x][6]."</td><td>".$lastLocaton[$x][0]."</td><td>".$lastLocaton[$x][8]."</td><td>".$lastLocaton[$x][4]."</td><td>".($lastLocaton[$x][10].",".$lastLocaton[$x][11])."</td> <td>".date('d-m-Y H:i:s',$lastLocaton[$x][1])."</td></tr>"; 
        }
    }
}
//echo getAddress(24.17174,72.41535);
$table=$table."</table>";
if($found>0){
echo $table;
$mailperAccount=array();
#$mailperAccount[0]="support@glovision.co";
#$mailperAccount[1]="gujaratmmu@gmail.com";
$mailperAccount[0]="tushar_shroff@emri.in";

//mailSendToAccount($mailperAccount,$table,$accountID,"Vehicles Crossing Assigned State Report");
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

?>
