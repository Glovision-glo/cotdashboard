<?php 
include 'config.php';
//require_once 'Mail.php';
date_default_timezone_set('Asia/Kolkata');
$accountID=$_GET['accountID'];
$healthDays=intval($_GET['days']);
if($healthDays>15){
  
  $healthDays=15;
}
$tacconnect = mysqli_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database: " . mysqli_error($tacconnect));
mysqli_select_db($tacconnect,$tacdb);
       $toDayDate=strtotime(date('d-M-Y',strtotime("0 days")).'');
       
       $d=date('d',$toDayDate);
       $m=date('m',$toDayDate);
       $y=date('Y',$toDayDate);
       $fromDate=strtotime(date("d-m-Y h:i:s a", mktime(0,0,0,$m,$d,$y)));
       $toDate=strtotime(date("d-m-Y h:i:s a", mktime(23,59,59,$m,$d,$y)));
       $fromDump=$fromDate;
       $toDump=$toDate;
       $gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error($gtsconnect));
   mysqli_select_db($gtsconnect,$gtsdb);
       $HealthCycle=array(array());
       for($dateIndx=0;$dateIndx<$healthDays;$dateIndx++){
            
            $vehicleDetails="SELECT deviceID,count(*) as no,MAX(timestamp) timestamp FROM EventData  where accountID='$accountID' and timestamp between $fromDump and $toDump GROUP BY deviceID ";
            
            error_log($vehicleDetails);
            $qry1 = mysqli_query($gtsconnect,$vehicleDetails) or die(mysqli_error($gtsconnect));
            while($row = mysqli_fetch_assoc($qry1))
            {
                //echo $row['deviceID'].'  '.$row['no'].'  '.$row['timestamp'].'<br>';
                $HealthCycle[$fromDump][$row['deviceID']]=$row['no'];
            }  
            $fromDump=$fromDump-86400;
            $toDump=$toDump-86400;
      }
        
       $devices = mysqli_query($gtsconnect,"SELECT deviceID FROM Device WHERE accountID ='$accountID'") or die(mysqli_error($gtsconnect)); 
       $totalDevices=array();    
       while($row = mysqli_fetch_assoc($devices))
       {
                
                $totalDevices[count($totalDevices)]=$row['deviceID'];
                
       }  
       mysqli_close($gtsconnect);
       $fromDump=$fromDate;
       $toDump=$toDate;
       $table="<html></style> <script src='https://code.jquery.com/jquery.js'></script><script type='text/javascript' src='//cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.13.3/jquery.tablesorter.min.js'></script>
    <script>3
	
$(document).ready(function () {
    $('#sort').tablesorter();
});</script><body><center><div  style='background: white;border-style: solid;border-color: #2E2E2E;'><h5>Account: $accountID<br>Vehicles Health Cycle<br> From: ".date('d-m-Y',$fromDate)." ToDate: ".date('d-m-Y',$toDate)."</h5><table id='sort' style='width:100%;font-family:sans-serif; font-size: 8pt; color: #000000;align=left;border-collapse: collapse;' border='1'><thead style='background:#0080FF;color:white'><th>vehicleID</th><th>Condition</th>";
        for($dateIndx=0;$dateIndx<$healthDays;$dateIndx++){
              $table=$table."<th>".date('d/M',$fromDump)."</th>";
              $fromDump=$fromDump-86400;
              $toDump=$toDump-86400;
          }
        $table=$table.'</thead><tbody>';
       for($deviceIndx=0;$deviceIndx<count($totalDevices);$deviceIndx++){
           $fromDump=$fromDate;
           $toDump=$toDate;
           $table=$table.'<tr><th>'.strtoupper($totalDevices[$deviceIndx]).'</th>';
           $streanth=0;
           $toweak=0;
           $row='';
           for($dateIndx=0;$dateIndx<intval($healthDays);$dateIndx++){
              if(intval($HealthCycle[$fromDump][$totalDevices[$deviceIndx]])>0 && intval($HealthCycle[$fromDump][$totalDevices[$deviceIndx]])<=145){
                   $row=$row.'<td style="background:#04B404;color:black">'.$HealthCycle[$fromDump][$totalDevices[$deviceIndx]].'</td>';              $streanth++;
              }elseif(intval($HealthCycle[$fromDump][$totalDevices[$deviceIndx]])>145){
                  $row=$row.'<td style="background:green;color:white">'.$HealthCycle[$fromDump][$totalDevices[$deviceIndx]].'</td>';              $streanth++;
              }else{
                   $row=$row.'<td style="background:red;color:black">0</td>';              $toweak++;
              }
              
              $fromDump=$fromDump-86400;
              $toDump=$toDump-86400;
          }
          if($streanth==intval($healthDays)){
              $table=$table.'<th style="color:green">Working</th>'.$row.'</tr>';
          }else if($toweak==intval($healthDays)){
              $table=$table.'<th style="color:red">Not Working</th>'.$row.'</tr>';
          }else{
               $table=$table.'<th style="color:#04B404">Gaps</th>'.$row.'</tr>';
          } 
          
       }
       $table=$table.'</tbody></table></div></center></body></html>'; 
       echo $table;
?>
