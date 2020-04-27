<?php
 require_once 'Mail.php';
  include 'config.php';
  date_default_timezone_set('Asia/Kolkata');
//http://glotrax.glovision.co/dashboard/php/vehiclesStatusReportDaywise.php?accountID=gvk-up-108&fromDate=&reportFormate=html&toDate=&crone=yes //crone request
///glotrax.glovision.co/dashboard/php/vehiclesStatusReportDaywise.php?accountID=gvk-up-102&fromDate=&reportFormate=html&toDate=&crone=yes
   $accountID=$_GET['accountID'];
   $fromDate=$_GET['fromDate'];
  $toDate=$_GET['toDate'];
  $crone="no";
   if($fromDate==""){
      $crone="yes";
      $fromDate=strtotime(date('d-m-Y',time()));
      $toDate=time();
    }
   $reportFormate=$_GET['reportFormate'];
  if($reportFormate=="excel"){

   header('Cache-control: no-cache,must revalidate');
  header('Content-type: application/json');
   header("Access-Control-Allow-Origin: *");
  header("Content-type: application/vnd.ms-excel");
   header("Content-Disposition: attachment;Filename=".$accountID."-statusReport.xls");


   }else if($reportFormate=="web"){

        header('Cache-control: no-cache,must revalidate');

        header("Access-Control-Allow-Origin: *");
        header('Content-type: text/html');
        header("Content-Disposition: attachment;Filename=".$accountID."-StatusReport.html");

    }
//  $accountID=$_GET['accountID'];
   $image=$accountID.'.png';
           if(strpos($accountID,'gvk')>-1){
               $image='gvkemri.jpg';
           }

    // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, "http://glotrax.glovision.co/dashboardlikeraj/php/vehiclesStatus.php?&accountID=$accountID&userID=admin&currentLocation=currentlocation&formate=html&selectedgroup=selectall&offline=");
    //  echo 'rama';
        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);
      // echo $output.'ram';
        // close curl resource to free up system resources
        curl_close($ch);  




   $Report= "<html> <script src='https://code.jquery.com/jquery.js'></script>
               <script type='text/javascript' src='//cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.13.3/jquery.tablesorter.min.js'></script>
     <style type='text/css'>
.tablecss {
        
        width:100%;
        border-collapse: collapse;
        color:black;
        background:white;
        font-family:Arial,Verdana,sans-serif;
        font-size:13;
}
</style> <body><center><div>
          <img alt='' src='http://track.glovision.co:8080/statictrack/images/custom/".$image."'>
         </div><h5>AccountID: $accountID<br><div  style='background: white;border-style: solid;border-color: #2E2E2E;'><table class='tablecss' id='sort' style='width:100%;font-family:sans-serif; font-size: 8pt; color: #000000;align=left;'><thead style='background:#2E2E2E;color:white'><th>ACCOUNT ID</th><th>DATE</th><th>No of Installed Devices</th><th>No of Tracking Devices</th><th>NO Running Devices</th><th>NO of Idle Devices</th><th>No Of Offraod Devices</th><th>No of Not Tracking Devices</th><th>No of Not Responding Devices</th></thead><tbody>";



  $tacconnect = mysqli_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database: " . mysqli_error($tacconnect));
  mysqli_select_db($tacconnect,$tacdb);
 
    
       $query="select * from vehicleStatusReport where accountID='$accountID' and timestamp between $fromDate and $toDate";
 //  echo $query;
       $qry1 = mysqli_query($tacconnect,$query) or die(mysqli_error($tacconnect));
       error_log($vehicleDetails);
       while($row = mysqli_fetch_assoc($qry1)){
         $Report=$Report.'<tr><td>'.$row['accountID'].'</td><td>'.date('d-m-Y',$row['timestamp']).'</td><td>'.$row['InstalledDevices'].'</td><td>'.$row['TrackingDevices'].'</td><td>'.$row['RunningDevices'].'</td><td>'.$row['IdleDevices'].'</td><td>'.$row['OffroadDevices'].'</td><td>'.$row['NotTrackingDevices'].'</td><td>'.$row['NotRespondingDevices'].'</td></tr>';
           ;
        }     
$Report=$Report.'</tbody></table></div></body></html>';
      
       mysqli_close($tacconnect);
  echo $Report;
 if($crone=="yes"){
    // echo $crone.'fdkj';
         $mailperAccount=array();
         $mailperAccount[0]="support@glovision.co";
          $mailperAccount[1]="newtonbabu_i@emri.in";
        $mailperAccount[2]="nafish@glovision.co";
         $reportType=" Daily Vehicle Summary Report for";
   mailSendToAccount($mailperAccount,$Report,$accountID,$reportType);

  }
  

function mailSendToAccount($mailperAccount,$totalReport,$accountID,$reportType){
  //echo $totalReport;
if ($totalReport!=""){
    $from = " GLOVISION Support <support@glovision.co>";
    $host ="144.217.228.80";
    $username = "alerts@glovision.co";
    $password = 'Gl0v1$ion12';
    error_log(count($mailperAccount).' num of accounts');
    for ($mailIndx=0;$mailIndx<count($mailperAccount);$mailIndx++){
        $to ="Recipient <".$mailperAccount[$mailIndx].">";
        $headers = array ('MIME-Version' => '1.0rn',
        'Content-Type' => "text/html; charset=ISO-8859-1rn",
        'From' => $from,
        'To' => $to,
        'Subject' =>$reportType.'  '.$accountID
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
}

?>

