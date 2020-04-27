<?php
    date_default_timezone_set('Asia/Kolkata');
    $cno=$_GET['contactno'];
    //config
    $tacserver = "track.glovision.co:50999";
    $tacusername = "root";
    $tacpassword = "gl0v1s10n";
    $tacdb = "autocare_gts";
    $timestamp=time();
    $tacconnect = mysqli_connect($tacserver, $tacusername, $tacpassword) or die ("Unable to connect to the database: " . mysqli_error($tacconnect));
    mysqli_select_db($tacconnect,$tacdb);
    $query="select * from CallerInfo where contactNo='$cno'  order by timeStamp desc limit 1 ";
    $qry_result = mysqli_query($tacconnect,$query) or die(mysqli_error($tacconnect));
    $info=array();
   
    while($row = mysqli_fetch_assoc($qry_result)){
      $info[0]=$row['lat'];
      $info[1]=$row['lng'];

    }
    mysqli_close($tacconnect);
    echo "{'marker':".$info."}"
?>

