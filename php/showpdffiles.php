<?php
    date_default_timezone_set('Asia/Kolkata');
    require_once('config.php');
     $accountID=$_GET['accountID'];
     $fromDate=strtotime($_GET['fromDate']);
     $toDate=strtotime($_GET['toDate']);
     $timeinterval=$_GET['userID'];
     $pdfexcel=$_GET['pdfexcel'];
     $groupID=$_GET['group'];
    $viewstyle=$_GET['viewstyle'];    

     $pdfreportType=$_GET['pdfreportType'];
    $reportHeading="All PDF/Excel Reports";
    if($pdfreportType=="distaneidlereport") $reportHeading=" Distance Idle Reports";
    if($pdfreportType=="casemanagerreport") $reportHeading="Case Manager Reports";
    if($pdfreportType=="nocases") $reportHeading="No Case Vehicle Reports";
    if($pdfreportType=="overspeed") $reportHeading="Over Speed Reports";
    if($pdfreportType=="offline") $reportHeading="Offline Vehicles Reports";
    if($pdfreportType=="currentlocation") $reportHeading="Vehicles Current Status Reports";
    if($pdfreportType=="daywisestatusreport") $reportHeading="Vehicles Status Monthly Report";
    $toDayDate=strtotime(date('d-M-Y',$fromDate));
       $d=date('d',$toDayDate);
       $m=date('m',$toDayDate);
       $y=date('Y',$toDayDate);
    $fromDate=strtotime(date("d-m-Y h:i:s a", mktime(0,0,0,$m,$d,$y)));
     $toDayDate=strtotime(date('d-M-Y',$toDate));
       $d=date('d',$toDayDate);
       $m=date('m',$toDayDate);
       $y=date('Y',$toDayDate);

       $toDate=strtotime(date("d-m-Y h:i:s a", mktime(23,59,59,$m,$d,$y)));

  $table="<style type='text/css'>
.tablecss {
        
        width:100%;
        border-collapse: collapse;
        color:black;
        background:white;
        font-family:Arial,Verdana,sans-serif;
        font-size:13;
}
</style><center> <h3>".$reportHeading."</h3>AccountID:$accountID |Vehicle ID:$vehicleID <br> From:".date('d-M-Y h:i:s',$fromDate )."  To:".date('d-M-Y h:i:s',$toDate )." <table border='1' style='font-family:Arial,Verdana,sans-serif;font-size:13;color:black;background:white;' class='tablecss'> <tbody>";//<tr><td><a> <img src='images/pdf.png' height='42' width='42'> Report</a></td><td><a> <img src='images/pdf.png' height='42' width='42'> </a></td><td><a> <img src='images/pdf.png' height='42' width='42'> </a></td><td><a> <img src='images/pdf.png' height='42' width='42'> </a></td></tr><tr><td><a> <img src='images/pdf.png' height='42' width='42'> </a></td><td><a> <img src='images/pdf.png' height='42' width='42'> </a></td><td><a> <img src='images/pdf.png' height='42' width='42'> </a></td><td><a> <img src='images/pdf.png' height='42' width='42'> </a></td></tr><tr><td><a> <img src='images/pdf.png' height='42' width='42'> </a></td><td><a> <img src='images/pdf.png' height='42' width='42'> </a></td><td><a> <img src='images/pdf.png' height='42' width='42'> </a></td><td><a> <img src='images/pdf.png' height='42' width='42'> </a></td></tr><tr><td><a> <img src='images/pdf.png' height='42' width='42'> </a></td><td><a> <img src='images/pdf.png' height='42' width='42'> </a></td><td><a> <img src='images/pdf.png' height='42' width='42'> </a></td><td><a> <img src='images/pdf.png' height='42' width='42'> </a></td></tr>";
$dir    = '../../images';
$files1 = scandir($dir);
$files2 = scandir($dir, 1);
//print_r( $files1);
if($viewstyle=="grid"){
    $table=$table."<tr>";
}else{
    $table=$table."<tr style='background-color:black;color:white'><th>Download</th><th>Report Type</th><th>Group</th><th>Date</th><th>Size</th><th>Range</th></tr>";
}
$fileadd=0;
for($filecount=0;$filecount<count($files1);$filecount++){
     if($fileadd==10 && $viewstyle=="grid"){
      $table=$table."</tr><tr>";
      $fileadd=0;

    }
   
    if(strpos($files1[$filecount],".pdf")>0 && $pdfexcel=="pdf"){
            $title = str_replace(".pdf", "", $files1[$filecount]);
            $filesplit=explode("_",$title);
           if(count($filesplit)==4){
            $filedate=$filesplit[2];//0 means account id, 1 groupd id ,2 timestamp 3 means report type
            if($filedate>=$fromDate && $filedate<$toDate && $accountID==$filesplit[0] && $groupID==$filesplit[1]){
                   $filesize=round((filesize("../../images/".$files1[$filecount])/1024)/1024,2);
                   $filereport="Daily Report"; 
                    if($filesplit[3]=="daywisestatusreport"){$filereport="Monthly Report";} 
                    if($filesplit[3]=="distaneidlereportweekly"){$filereport="Weekly Report";}
                   if($pdfreportType==$filesplit[3] || ($pdfreportType=="distaneidlereport" && $filesplit[3]=="distaneidlereportweekly" )){
                        $fileadd++;
                        if($filesplit[1]=="selectall"){$filesplit[1]=" All Groups"; }
                        if($viewstyle=="grid"){
                          $table=$table."<td><a href='../images/".$files1[$filecount]."' target='_blank'> <img src='images/pdf.png' height='32' width='32'></a><br>".$filesplit[3]."<br>Group: ".$filesplit[1]."<br>Date :".date('d-m-Y', $filedate)."<br>Size:".$filesize." MB <br>".$filereport."</td>";
                        }else{
                          $table=$table."<tr><td><a href='../images/".$files1[$filecount]."' target='_blank'> <img src='images/pdf.png' height='22' width='22'></a></td><td>".$filesplit[3]."</td><td> ".$filesplit[1]."</td><td>".date('d-m-Y', $filedate)."</td><td>".$filesize." MB </td><td>".$filereport."</td></tr>";

                        }
                   }else if($pdfreportType=="all"){
                          $fileadd++;
                          if($filesplit[1]=="selectall"){$filesplit[1]=" All Groups"; }
                          if($viewstyle=="grid"){
                              $table=$table."<td><a href='../images/".$files1[$filecount]."' target='_blank'> <img src='images/pdf.png' height='32' width='32'></a><br>".$filesplit[3]."<br>Group: ".$filesplit[1]."<br>Date :".date('d-m-Y', $filedate)."<br>Size:".$filesize." MB <br>".$filereport."</td>";
                         }else{
                             $table=$table."<tr><td><a href='../images/".$files1[$filecount]."' target='_blank'> <img src='images/pdf.png' height='22' width='22'></a></td><td>".$filesplit[3]."</td><td> ".$filesplit[1]."</td><td>".date('d-m-Y', $filedate)."</td><td>".$filesize." MB </td><td>".$filereport."</td></tr>";
                         }
                   }
             }
          }
       
    }
   
//  excel files
    if(strpos($files1[$filecount],".html")>0 && $pdfexcel=="excel"){
            $title = str_replace(".html", "", $files1[$filecount]);
          
            $filesplit=explode("_",$title);
           if(count($filesplit)==4){
            $filedate=$filesplit[2];//0 means account id, 1 groupd id ,2 timestamp 3 means report type
            if($filedate>=$fromDate && $filedate<$toDate && $accountID=$filesplit[0] && $groupID==$filesplit[1]){
                   $filesize=round((filesize("../../images/".$files1[$filecount])/1024)/1024,2);
                   $filereport="Daily Report";
                    if($filesplit[3]=="daywisestatusreport"){$filereport="Monthly Report";}
                    if($filesplit[3]=="distaneidlereportweekly"){$filereport="Weekly Report";}
                   if($pdfreportType==$filesplit[3] || ($pdfreportType=="distaneidlereport" && $filesplit[3]=="distaneidlereportweekly" )){
                        $fileadd++;
                        if($filesplit[1]=="selectall"){$filesplit[1]=" All Groups"; }
                        if($viewstyle=="grid"){
                          $table=$table."<td><a  onclick=downloadexcel('".$files1[$filecount]."') target='_blank'> <img src='images/excel.png' height='32' width='32'></a><br>".$filesplit[3]."<br>Group: ".$filesplit[1]."<br>Date :".date('d-m-Y', $filedate)."<br>Size:".$filesize." MB <br>".$filereport."</td>";
                       }else{
                          $table=$table."<tr><td><a  onclick=downloadexcel('".$files1[$filecount]."') target='_blank'> <img src='images/excel.png' height='22' width='22'></a></td><td>".$filesplit[3]."</td><td> ".$filesplit[1]."</td><td>".date('d-m-Y', $filedate)."</td><td>".$filesize." MB </td><td>".$filereport."</td></tr>";
                       }
                   }else if($pdfreportType=="all"){
                          $fileadd++;
                           //href='../images/".$files1[$filecount]."'
                           if($filesplit[1]=="selectall"){$filesplit[1]=" All Groups"; }
                           if($viewstyle=="grid"){
                              $table=$table."<td><a  onclick=downloadexcel('".$files1[$filecount]."') target='_blank'> <img src='images/excel.png' height='32' width='32'></a><br>".$filesplit[3]."<br>Group: ".$filesplit[1]."<br>Date :".date('d-m-Y', $filedate)."<br>Size:".$filesize." MB <br>".$filereport."</td>";
                           }else{
                              $table=$table."<tr><td><a  onclick=downloadexcel('".$files1[$filecount]."') target='_blank'> <img src='images/excel.png' height='22' width='22'></a></td><td>".$filesplit[3]."</td><td> ".$filesplit[1]."</td><td>".date('d-m-Y', $filedate)."</td><td>".$filesize." MB </td><td>".$filereport."</td></tr>";
                           }
                   }
             }
          }

    }
    
}//end loop
if($fileadd<10 && $viewstyle=="grid"){
 /*  for(;$fileadd<=6;$fileadd++){
      $table=$table."<td></td>";
    }*/
   $table=$table."</tr>";
}
echo $table."</tbody></table>";
?>

