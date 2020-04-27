<?php
    date_default_timezone_set('Asia/Kolkata');
   set_time_limit(0);
    include 'config.php';
    $accountID=$_GET['accountID'];
    $from= $_GET['fromDate'];
    $to = $_GET['toDate'];
    $group= $_GET['group'];
   $gtsconnect = mysqli_connect('localhost','root', 'gl0v1s10n') or die ("Unable to connect to the database1 : " . mysqli_error());
    mysqli_select_db($gtsconnect, 'gts');



       if (mysqli_multi_query($gtsconnect, "call eventdata($from,$to,'$accountID','up41g0576',1800)"))
{
  do
    {
    // Store first result set
    if ($result=mysqli_store_result($gtsconnect)) {
      // Fetch one and one row
      while ($row=mysqli_fetch_row($result))
        {
        printf("%s\n",$row[3]);
        }
      // Free result set
      mysqli_free_result($result);
      }
    }
  while (mysqli_next_result($gtsconnect));
}

echo "dfdfd";
/*

 echo "p ";
 $result = mysqli_query($gtsconnect,  "call eventdata($from,$to,'$accountID','up41g0576')") or die("Query fail: " . mysqli_error($gtscnnect)); 
echo "ss";



$c=0;
while ($row = mysqli_fetch_array($result)){   
      echo $row[0] . " - " . $row[1]."<br>"; 
      $c++;
  }
echo "xx".$c;

*/

 mysqli_close($gtsconnect);


?>
