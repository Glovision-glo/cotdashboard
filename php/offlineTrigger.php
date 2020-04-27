<?php 
//include 'config.php';
require_once 'Mail.php';
date_default_timezone_set('Asia/Kolkata');
$accountID=$_GET['accountID'];
error_log("logdis".''.$accountID);

$toDayDate=strtotime(date('d-M-Y'));
         $d=date('d',$toDayDate);
         $m=date('m',$toDayDate);
         $y=date('Y',$toDayDate);
  $toDate=strtotime(date("d-m-Y h:i:s a", mktime(0,0,0,$m,$d,$y)));
//echo $toDate;
$todayepoch=time();
$offlinetime=$todayepoch-86400;

    $host='localhost';
    $root='root';
    $password='gl0v1s10n';
    $conn=mysqli_connect($host,$root,$password) or die('unable to connect database');
    $gtsdbname='gts';
    mysqli_select_db($conn,$gtsdbname) or die(mysqli_error($conn));

    $tacdbname='autocare_gts';
    mysqli_select_db($conn,$tacdbname) or die(mysqli_error($conn));

//$sql="SELECT $gtsdbname.Device.deviceID,$gtsdbname.Device.description,$gtsdbname.Device.lastValidLatitude,$gtsdbname.Device.lastValidLongitude,$gtsdbname.Device.lastGPSTimestamp,from_unixtime($gtsdbname.Device.lastGPSTimestamp) as unixtime FROM $gtsdbname.Device  where  $gtsdbname.Device.accountID ='$accountID'  and ($gtsdbname.Device.lastGPSTimestamp < $offlinetime) and $gtsdbname.Device.deviceID not in (select $tacdbname.vehicleOffroad.vehicleID from vehicleOffroad where $tacdbname.vehicleOffroad.accountID='$accountID' and  $tacdbname.vehicleOffroad.offlineTime='$toDate')" ;

$sql="SELECT $gtsdbname.Device.deviceID,$gtsdbname.Device.description,$gtsdbname.Device.lastValidLatitude,$gtsdbname.Device.lastValidLongitude,$gtsdbname.Device.lastGPSTimestamp,from_unixtime($gtsdbname.Device.lastGPSTimestamp) as unixtime,$gtsdbname.Device.maintNotes,$tacdbname.gvkNotify.emeMail,$tacdbname.gvkNotify.pmMail,$tacdbname.gvkNotify.emeContact,$tacdbname.gvkNotify.pmContact,$tacdbname.gvkNotify.groupID  FROM $gtsdbname.Device,$tacdbname.gvkNotify  where  $gtsdbname.Device.accountID ='$accountID' and $tacdbname.gvkNotify.accountID='$accountID' and $tacdbname.gvkNotify.deviceID=$gtsdbname.Device.deviceID  and ($gtsdbname.Device.lastGPSTimestamp < $offlinetime) and $gtsdbname.Device.deviceID not in (select $tacdbname.vehicleOffroad.vehicleID from vehicleOffroad where $tacdbname.vehicleOffroad.accountID='$accountID' and  $tacdbname.vehicleOffroad.offlineTime='$toDate') "  ;


//echo $sql;

$rs=mysqli_query($conn,$sql);
$x=0;
$emmails=array();
$pmmails=array();
while($row=mysqli_fetch_assoc($rs)){
      $emmails[$row['emeMail']."--".$row['emeContact']]=$emmails[$row['emeMail']."--".$row['emeContact']].$row['groupID']."--".$row['deviceID'].",";
       $pmmails[$row['pmMail']."--".$row['pmContact']]=$pmmails[$row['pmMail']."--".$row['pmContact']].$row['groupID']."--".$row['deviceID'].",";
       
     // echo $x++."  ".$row['deviceID'].'-'.$row['vehicleID'].'-'.$row['lastValidLatitude'].'-'.$row['lastValidLongitude'].'-'.$row['lastGPSTimestamp'].'-'.$row['unixtime'].'-'.$row['maintNotes']." === ".$row['emeMail'].' == ' .$row['pmMail']."==".$row['emeContact']." ==".$row['pmContact']."<br>";

        }


           sendMail($emmails);
          echo "===================";
            sendMail($pmmails);


 function sendMail($emmails){

foreach($emmails as $mailcontact => $v) {
               // echo $k."==".$v."<br>";
           $mail=explode("--",$mailcontact)[0];
           $contact=explode("--",$mailcontact)[1];
             $msgbody="Offline Vehicles:".$v;
             $vals=explode(",",$v); 
             $mailBody="Dear sir,<br> Offline Vehicles <br>";
             for($x=0;$x<count($vals)-1;$x++){
                    $val= explode("--",$vals[$x]);
                   $mailBody=$mailBody." Group ID :".$val[0]." Vehicle ID:".$val[1]."<br>";
             }
             $mailBody=$mailBody."Thanks<br> Glovision Team,";
           if($mail!=""){
               echo "mail body $mail ".$mailBody."<br>";
               //sending mail
                // $mail[0]="prasanth@glovision.co";
                // $mail[1]="support@glovision.co";
                 mailSendToAccount($mail,$mailBody,$accountID,"Offline vehicles");
           }
           if($contact!=""){
//                echo $contact." ~~~~~" .$msgbody;
            //    $contact='9676412230';
                echo $contact." ~~~~~" .$msgbody;
                //sms url linke provide here
                error_log($msgbody."message");
                $search= array(" ","#",",");
                $replace= array("%20","%23","%2C");
                $msgs= str_replace($search, $replace, $msgbody);
                error_log($msgs."message-1");
                $url2="http://api.smscountry.com/SMSCwebservice_bulk.aspx?User=gvkemri108&passwd=22924732&mobilenumber=";
                $url2.=$contact;
                $url2.="&sid=gvkemri108&mtype=N&DR=Y";
                $url2.="&message=".$msgs;
                error_log($url2.'        SMS URL');
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url2);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HEADER, false);
                $str = curl_exec($curl);
                curl_close($curl);
 
            }
       }
}

function mailSendToAccount($mailperAccount,$body,$accountID,$reportType){
if ($body!=""){
    $from = " GLOVISION Support <support@glovision.co>";
    $host ="144.217.228.80";
    $username = "alerts@glovision.co";
    $password = 'Gl0v1$ion12';
    error_log(count($mailperAccount).' num of accounts');
echo $mailperAccount[0].'-'.$mailperAccount;
        $to ="Recipient <".$mailperAccount.">";
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

function getMails($tacserver,$tacusername,$tacpassword,$tacdb,$accountId,$time){
$tacconnect = mysqli_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database: " . mysqli_error($tacconnect));
mysqli_select_db($tacconnect,$tacdb);

$query1 = "SELECT DISTINCT emailaddress FROM email WHERE accountID ='$accountId' and time='$time'";

error_log($query1);
$qry_result = mysqli_query($tacconnect,$query1) or die(mysqli_error($tacconnect));

$mails=Array();
$i=0;

 while($row = mysqli_fetch_assoc($qry_result))
  {
  
    $mails[$i++]= $row['emailaddress'];
      error_log($row['emailaddress']);    
        
                                           
  }
mysqli_close($tacconnect);
return $mails;
}

?>
