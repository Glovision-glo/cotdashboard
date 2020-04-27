<?php 
include 'config.php';

 
 $alldevices=array();
$gtsconnect = mysqli_connect($gtsserver, $gtsusername, $gtspassword) or die ("Unable to connect to the database: " . mysqli_error());
mysqli_select_db($gtsconnect,$gtsdb);
     $query="select deviceID from DeviceList  where accountID='gvk-up-102'";
   
    error_log($query);
  $qry_result = mysqli_query($gtsconnect,$query) or die(mysqli_error());
   $i=0;
    //error_log('dddddddddddddddddddddddddddddddddddd');
   while($row = mysqli_fetch_assoc($qry_result)){
       $alldevices[$i]=$row['deviceID'];
       
    //error_log('raaaaaaaaaaammmmmmmm'.$lastLocaton[$i][1].'     '.$lastLocaton[$i][0]);
          $i++;
   }


  
  $query="select deviceID,description from Device  where accountID='gvk-up-102'";
  $qry_result = mysqli_query($gtsconnect,$query) or die(mysqli_error());
  $status='no';
  while($row = mysqli_fetch_assoc($qry_result)){
       $status='no';
      for($x=0;$x<count($alldevices);$x++){
         if($row['deviceID']==$alldevices[$x]){
            $status='yes';
         }
      }
      if($status=='no'){
             echo $row['deviceID'].'  '.$row['description'].'<br>';
      }
      
       
  }


    
  mysqli_close($gtsconnect);


?>
