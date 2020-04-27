<?php
   $errormsg=$_GET['errormsg'];
   if($errormsg=="1"){
        $errormsg="Account,User name or Password are wrong, Please try again.";
    }else if($errormsg=="2"){
        $errormsg="Session Timeout ,Please login again";
    }else if($errormsg=="3"){
        $errormsg="DB Connection Error,Please try again";
    }else if($errormsg=="4"){$errormsg="Successfully logged out";}
    else {
       $errormsg='';
    }
   
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Login</title>

    <!-- Bootstrap -->
    <link href="loginpage/css/bootstrap.min.css" rel="stylesheet">
     <link rel="stylesheet" href="loginpage/css/style.css">
     <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700'
 rel='stylesheet' type='text/css'>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

<script type="text/javascript">
            var Browser = {
                Version: function () {
                    var version = 999;
                    if (navigator.appVersion.indexOf("MSIE") != -1)
                        version = parseFloat(navigator.appVersion.split("MSIE")[1]);
                    return version;
                }
            }
            if (Browser.Version() < 9) {
                //alert('This website is best optimised for IE 9.Kindly upgrade your browser to Internet Explorer 9...!');
                document.getElementById("divBrowser").style.display = "block";
            }

            function checkForm() {
                var uid = document.getElementById("txtuser");
                var pwd = document.getElementById("txtPwd");
                var acnt = document.getElementById("txtacnt");
                if (acnt.value == "") {
                    alert("Please enter value for Account");
                    acnt.focus();
                    return false;
                }
                else if (uid.value == "") {
                    alert("Please enter value for User ID");
                    uid.focus();
                    return false;
                }else if(pwd.value==""){
                    alert("please enter value for Password");
                    pwd.focus();
                    return false;
                }
            }

        </script>

  </head>
  <body>
  <div class="container-fluid">
  	<div class="row" style="background:#B20F14; padding:7px;">
    	<div class="col-md-12">
        <h3 style="text-align:center; color:#fff; font-weight:bold;">Welcome To GVK</h3>
        </div>
    </div>
  </div>
 
 
  <div class="loginpanel">
  <center>
    <img src="images/gvk-emri_527.png">
    </center>
<form method="post" action="php/login.php" id="Form1">
   <div class="txt"> <center><span id="lblError" style="font-weight:bold;color: Red;font-size:10px"><?php echo $errormsg; ?></span> </center> </div>
  <div class="txt">
    <input name="txtacnt" type="text" id="txtacnt" placeholder="Accountname"/>
    <label for="user" class="entypo-user"></label>
  </div>
  
  <div class="txt">
     <input name="txtuser" type="text" id="txtuser" placeholder="User Name"/>
    <label for="user" class="entypo-user"></label>
  </div>
  <div class="txt">
     <input name="txtPwd" type="password" id="txtPwd" placeholder="Password" />
    <label for="pwd" class="entypo-lock"></label>
  </div>
  <div class="buttons">
    <input type="submit" style="background:#B20F14;color:white"  name="btnLogin" value="Login" onclick="return checkForm();" id="btnLogin" />
    <!--<span>
      <a href="javascript:void(0)" class="entypo-user-add register">Register</a>
    </span>-->
  </div>
 <div class="txt">
         <div id="divBrowser" align="center" style="display: none; border: solid 1px gray; margin: 5px; padding: 5px;">
                                    <b>This website is best optimised for IE 9.Kindly upgrade your browser to Internet Explorer
                                    9...! </b>
                                </div>
 
  </div> 
 </form>
  <!--<div class="hr">
    <div></div>
    <div>OR</div>
    <div></div>
  </div>-->
  
 <!-- <div class="social">
    <a href="javascript:void(0)" class="facebook"></a>
    <a href="javascript:void(0)" class="twitter"></a>
    <a href="javascript:void(0)" class="googleplus"></a>
  </div>-->
 
</div>
<br><br><br><br>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
  
  
   

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>


<div class="container-fluid">
		<div class="row" style="background:#B20F14; padding:5px;">
       <a href="http://www.glovision.co/"> <p style="text-align:center; color:#fff; font-size:20px;">Designed By Glovision.co</p></a>
        </div>
</div>
 

  
  </body>
</html>
