<?php
    set_time_limit(0);
    
    $filename=$_GET['f'];
    $disfile=str_replace(".html","",$filename);
     header('Cache-control: no-cache,must revalidate');
  header('Content-type: application/json');
   header("Access-Control-Allow-Origin: *");
  header("Content-type: application/vnd.ms-excel");
    header('Content-Disposition: attachment; filename='.$disfile.'.xls');
    $report = file_get_contents("../../images/".$filename);   
    echo $report;




?>
