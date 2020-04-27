<?php 
    include 'config.php';
    date_default_timezone_set('Asia/Kolkata');
    require_once 'Mail.php';
  
    $workingStatus='no';
    $accountsWithoutEvents=array();
    ForEachServer("glotrax.glovision.co","gts","opengts","gts");
    
    unset($accountsWithoutEvents);
    $workingStatus='no';
    $accountsWithoutEvents=array();
    ForEachServer("track.glovision.co:50999","gts","opengts","gts");
    
    unset($accountsWithoutEvents);
    $workingStatus='no';
    $accountsWithoutEvents=array();
    ForEachServer("rwsscto.glovision.co","gts","opengts","gts");
  
   function ForEachServer($gtsserver,$gtsusername,$gtspassword,$gtsdb){
       global $accountsWithoutEvents,$workingStatus;
       //$gtsserver ="rwsscto.glovision.co";
       //$gtsusername ="";
       //$gtspassword="";
       //$gtsdb ="";
       $status=DBConnectionTesting($gtsserver,$gtsusername,$gtspassword,$gtsdb);
       $report='';
       if($status>0){
         //echo $gtsserver.' Working Fine '.$status.'<br>';
         if(count($accountsWithoutEvents)>0){
              $accountsDontHaveEvents='';
              for($indx=0;$indx<count($accountsWithoutEvents);$indx++){
                  error_log($accountsWithoutEvents); 
                  $accountsDontHaveEvents=$accountsDontHaveEvents.$accountsWithoutEvents[$indx].'<br>';
              }
              $report="Server Name:-".$gtsserver."<br>Status:- DCS is not working for <br>".$accountsDontHaveEvents;
         }
      }
      else{
         if($workingStatus=="yes"){
             $report= 'Server Name:-'.$gtsserver.'<br>Status:-Getting No Events ';
         }else{
             $report= 'Sever Name:- '.$gtsserver.'<br>Status :- Down';
         }
 

      }

       if($report !=''){
          $mailperAccount=array();
          $mailperAccount[0]='sainath@glovision.co';
          $mailperAccount[1]='ramana@glovision.co';
          $mailperAccount[2]='support@glovision.co';
          $mailperAccount[3]='rajuyadati@glovision.co';
          $mailperAccount[4]='pavan@glovision.co';
          mailSendToAccount($mailperAccount,$report,$gtsserver,'DCS is not communicating');
       }
    
   }
 


    function DBConnectionTesting($gtsserver,$gtsusername,$gtspassword,$gtsdb){
        $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword);
        global $accountsWithoutEvents,$workingStatus;
        if (!$gtsconnect) {
            $workingStatus='no';
           mysqli_close($gtsconnect);
           return 0;
        }else{
            $toDate=strtotime(date('d-M-Y h:i:s a',strtotime("0 days")).'');
            $workingStatus='yes';
            //$fromDate=$toDate-1800;
            $fromDate=$toDate-30;
            mysqli_select_db($gtsdb, $gtsconnect);
            $records1= mysqli_query($gtsconnect," select count(*) as rows,deviceCode  from Device where lastEventTimestamp>=$fromDate and lastEventTimestamp<= $toDate group by deviceCode")or die(mysqli_error($gtsconnect));
error_log($gtsserver."-"."select count(*) as rows,deviceCode  from Device where lastEventTimestamp>=$fromDate and lastEventTimestamp<= $toDate group by deviceCode");
              $status="no";
             while($row = mysqli_fetch_assoc($records1))
                   {
                         $status="yes";
                        if($row['rows']<=0){
                       $accountsWithoutEvents[count($accountsWithoutEvents)]=$row['deviceCode'];
                        }
                       error_log($gtsserver.'-'.'deviceCode:'.$row['deviceCode'].' Evnt:'.$row['rows'].'<br>');
                   } 
           mysqli_close($gtsconnect);
            if($status=="no"){
            return 0;
}else{
            return 1;
}
       }
    }
 
 
 function mailSendToAccount($mailperAccount,$totalReport,$accountID,$reportType){
  //echo $totalReport;
    if ($totalReport!=""){
    $from = " Glovision <support@glovision.co>";
    $host ="144.217.228.80";
    $username = "alerts@glovision.co";
    $password = 'Gl0v1$ion12';
    //error_log(count($mailperAccount).' no accounts'.$totalReport);
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
