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
`


<html>


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

<style>

@import url(http://fonts.googleapis.com/css?family=Bree+Serif);

/*******************
SELECTION STYLING
*******************/

::selection {
	color: #fff;
	background: #f676b2; /* Safari */
}
::-moz-selection {
	color: #fff;
	background: #f676b2; /* Firefox */
}

/*******************
BODY STYLING
*******************/

* {
	margin: 0;
	outline: none;
}

body {
/*	background: url('gj.jpg');*/
	font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
	font-weight:300;
	text-align: left;
	text-decoration: none;
	height: 500px;
}

#wrapper {
	/* Center wrapper perfectly */
	width: 400px;
	height: 200px;
	margin: 250px ;
}

/* Download Button (Demo Only) */
.download {
	display: block;
	position: absolute;
	float: right;
	right: 25px;
	bottom: 25px;
	padding: 5px;
	
	font-weight: bold;
	font-size: 11px;
	text-align: right;
	text-decoration: none;
	color: rgba(0,0,0,0.5);
	text-shadow: 1px 1px 0 rgba(256,256,256,0.5);
}

.download:hover {
	color: rgba(0,0,0,0.75);
	text-shadow: 1px 1px 0 rgba(256,256,256,0.5);
}

.download:focus {
	bottom: 24px;
}

/*
.gradient {
	width: 600px;
	height: 600px;
	position: fixed;
	left: 50%;
	top: 50%;
	margin-left: -300px;
	margin-top: -300px;
	
	background: url(http://www.demo.amitjakhu.com/login-form/images/gradient.png) no-repeat;
}
*/

.gradient {
	/* Center Positioning */
	width: 600px;
	height: 300px;
	position: fixed;
	left: 50%;
	top: 50%;
	margin-left: -300px;
	margin-top: -300px;
	z-index: -2;
	
	/* Fallback */ 
	background-image: url(http://www.demo.amitjakhu.com/login-form/images/gradient.png); 
	background-repeat: no-repeat; 
	
	/* CSS3 Gradient */
	background-image: -webkit-gradient(radial, 0% 0%, 0% 100%, from(rgba(213,246,255,1)), to(rgba(213,246,255,0)));
	background-image: -webkit-radial-gradient(50% 50%, 40% 40%, rgba(213,246,255,1), rgba(213,246,255,0));
	background-image: -moz-radial-gradient(50% 50%, 50% 50%, rgba(213,246,255,1), rgba(213,246,255,0));
	background-image: -ms-radial-gradient(50% 50%, 50% 50%, rgba(213,246,255,1), rgba(213,246,255,0));
	background-image: -o-radial-gradient(50% 50%, 50% 50%, rgba(213,246,255,1), rgba(213,246,255,0));
}

/*******************
LOGIN FORM
*******************/

.login-form {
	width: 300px;
	margin: 0 auto;
	position: relative;
	
	background: #f3f3f3;
	border: 1px solid #fff;
	border-radius: 5px;
	
	box-shadow: 0 1px 3px rgba(0,0,0,0.5);
	-moz-box-shadow: 0 1px 3px rgba(0,0,0,0.5);
	-webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.5);
}

/*******************
HEADER
*******************/

.login-form .header {
	padding: 40px 30px 30px 30px;
}

.login-form .header h1 {
	font-family: 'Bree Serif', serif;
	font-weight: 300;
	font-size: 28px;
	line-height:34px;
	color: #414848;
	text-shadow: 1px 1px 0 rgba(256,256,256,1.0);
	margin-bottom: 10px;
}

.login-form .header span {
	font-size: 11px;
	line-height: 16px;
	color: #678889;
	text-shadow: 1px 1px 0 rgba(256,256,256,1.0);
}

/*******************
CONTENT
*******************/

.login-form .content {
	padding: 0 30px 25px 30px;
}

/* Input field */
.login-form .content  {
	width: 251px;
	padding: 15px 25px;
	
	font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
	font-weight: 400;
	font-size: 14px;
	color: #9d9e9e;
	text-shadow: 1px 1px 0 rgba(256,256,256,1.0);
	
	background: #fff;
	border: 1px solid #fff;
	border-radius: 5px;
	
	box-shadow: inset 0 1px 3px rgba(0,0,0,0.50);
	-moz-box-shadow: inset 0 1px 3px rgba(0,0,0,0.50);
	-webkit-box-shadow: inset 0 1px 3px rgba(0,0,0,0.50);
}

/* Second input field */
.login-form .content .password, .login-form .content .pass-icon {
	margin-top: 25px;
}

.login-form .content :hover {
	background: #dfe9ec;
	color: #414848;
}

.login-form .content :focus {
	background: #dfe9ec;
	color: #414848;
	
	box-shadow: inset 0 1px 2px rgba(0,0,0,0.25);
	-moz-box-shadow: inset 0 1px 2px rgba(0,0,0,0.25);
	-webkit-box-shadow: inset 0 1px 2px rgba(0,0,0,0.25);
}

.user-icon, .pass-icon {
	width: 46px;
	height: 47px;
	display: block;
	position: absolute;
	left: 0px;
	padding-right: 2px;
	z-index: -1;
	
	-moz-border-radius-topleft: 5px;
	-moz-border-radius-bottomleft: 5px;
	-webkit-border-top-left-radius: 5px;
	-webkit-border-bottom-left-radius: 5px;
}

.user-icon {
	top:130px; /* Positioning fix for slide-in, got lazy to think up of simpler method. */
	background: rgba(65,72,72,0.75) url(http://www.demo.amitjakhu.com/login-form/images/user-icon.png) no-repeat center;	
}

.user-icon1{
        width: 46px;
        height: 47px;
        display: block;
        position: absolute;
        left: 0px;
        padding-right: 2px;
        z-index: -1;

        -moz-border-radius-topleft: 5px;
        -moz-border-radius-bottomleft: 5px;
        -webkit-border-top-left-radius: 5px;
        -webkit-border-bottom-left-radius: 5px;
}

.user-icon1 {
        top:210px; /* Positioning fix for slide-in, got lazy to think up of simpler method. */
        background: rgba(65,72,72,0.75) url(http://www.demo.amitjakhu.com/login-form/images/user-icon.png) no-repeat center;
}



.pass-icon {
	top:255px;
	background: rgba(65,72,72,0.75) url(http://www.demo.amitjakhu.com/login-form/images/pass-icon.png) no-repeat center;
}

.content input:focus + div{
	left: -6px;
}

/* Animation */
 .user-icon, .pass-icon, .button, .register ,.user-icon1{
	transition: all 0.5s ease;
	-moz-transition: all 0.5s ease;
	-webkit-transition: all 0.5s ease;
	-o-transition: all 0.5s ease;
	-ms-transition: all 0.5s ease;
}

/*******************
FOOTER
*******************/

.login-form .footer {
	padding: 10px 95px 11px 30px;
	overflow: auto;
	
	background: #d4dedf;
	border-top: 1px solid #fff;
	
	box-shadow: inset 0 1px 0 rgba(0,0,0,0.15);
	-moz-box-shadow: inset 0 1px 0 rgba(0,0,0,0.15);
	-webkit-box-shadow: inset 0 1px 0 rgba(0,0,0,0.15);
}

/* Login button */
.login-form .footer .button {
	float:right;
	padding: 11px 25px;
	
	font-family: 'Bree Serif', serif;
	font-weight: 300;
	font-size: 18px;
	color: #fff;
	text-shadow: 0px 1px 0 rgba(0,0,0,0.25);
	
	background: #56c2e1;
	border: 1px solid #46b3d3;
	border-radius: 5px;
	cursor: pointer;
	
	box-shadow: inset 0 0 2px rgba(256,256,256,0.75);
	-moz-box-shadow: inset 0 0 2px rgba(256,256,256,0.75);
	-webkit-box-shadow: inset 0 0 2px rgba(256,256,256,0.75);
}

.login-form .footer .button:hover {
	background: #3f9db8;
	border: 1px solid rgba(256,256,256,0.75);
	
	box-shadow: inset 0 1px 3px rgba(0,0,0,0.5);
	-moz-box-shadow: inset 0 1px 3px rgba(0,0,0,0.5);
	-webkit-box-shadow: inset 0 1px 3px rgba(0,0,0,0.5);
}

.login-form .footer .button:focus {
	position: relative;
	bottom: -1px;
	
	background: #56c2e1;
	
	box-shadow: inset 0 1px 6px rgba(256,256,256,0.75);
	-moz-box-shadow: inset 0 1px 6px rgba(256,256,256,0.75);
	-webkit-box-shadow: inset 0 1px 6px rgba(256,256,256,0.75);
}

/* Register button */
.login-form .footer .register {
	display: block;
	float: right;
	padding: 10px;
	margin-right: 20px;
	
	background: none;
	border: none;
	cursor: pointer;
	
	font-family: 'Bree Serif', serif;
	font-weight: 300;
	font-size: 18px;
	color: #414848;
	text-shadow: 0px 1px 0 rgba(256,256,256,0.5);
}

.login-form .footer .register:hover {
	color: #3f9db8;
}

hr {
    margin-top: 2px;
    margin-bottom: 2px;
}
.label {
  padding-top: 4px;
padding-right: 4px;
text-align: left;
font-size: 8pt;
font-family: verdana,sans-serif;

}

.textInput {
    border-width: 1px;
    border-style: solid;
    border-color: #555588 #171729 #AAAABB #555588;
    padding-left: 3px;
    background-color: white;
        font-size: 10pt;
    font-family: verdana,sans-serif;
}
.bg-img {

background: url('COT1.jpg') no-repeat center center fixed; 
   width:1200;
   height:100;

}
</style>
<body class="bg-img" >
<div id="wrapper">
        <center>
	<form name="login-form"  method="post" action="php/login.php" id="Form1">
	
		<div class="header"><span style='font-size:11pt'>Enter your Login ID and Password</span>
<hr style='height: 5px;'/><br>
		</div>
             <table>
    	
                 <tr><td class="label"> Account:</td><td> <input name="txtacnt" id="txtacnt" type="text" class="textInput"  placeholder="Account" /></td></tr>

           
                 <tr><td class="label"> User :</td><td>  <input name="txtuser" id="txtuser" type="text"  class="textInput" placeholder="Username" /></td></tr>

 
           <tr><td class="label"> Password:</td><td>		<input name="txtPwd" id="txtPwd" type="password" class="textInput"  placeholder="Password" /></td></tr>

         <tr></tr><tr></tr>
     <tr><td></td><td>	<input type="submit"   name="submit" value="Login"   onclick="return checkForm();" id="btnLogin"/></td></tr>
            
		</table>
	
	</form>
</center>
 <br><br>
  <span style="color:red;"><?php echo $errormsg; ?></span>
  
</body>
</html>
