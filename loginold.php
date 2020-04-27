<?php
   $errormsg=$_GET['errormsg'];
   if($errormsg=="1"){
        $errormsg="Account,User name or Password are wrong, Please try again.";
    }else if($errormsg=="2"){
        $errormsg="Session Timeout ,Please login again";
    }else if($errormsg=="3"){
        $errormsg="DB Connection Error,Please try again";
    }else if($errormsg=="4"){$errormsg="Successfully logout..";}
    else {
       $errormsg='';
    }

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="css/MainSite.css" />
</head>
<body style="background: #ffffff url(images/bg_body.gif) top left repeat-x;">
    <br />
    <br />
    <br />
    <br />
    <form method="post" action="php/login.php" id="Form1">
</div>

<div class="aspNetHidden">

</div>
        <table width="527" border="0" cellspacing="0" cellpadding="0" style="background: url(images/login.png) no-repeat top left;"
            align="center">
            <tr>
                <td height="16"></td>
            </tr>
            <tr>
                <td valign="top">
                    <img src="images/spacer.gif" alt="" width="1" height="30" />
                </td>
            </tr>
            <tr>
                <td height="30">&nbsp;
                </td>
            </tr>
            <tr>
                <td valign="top">
                    <table width="387" border="0" align="center" cellpadding="5" cellspacing="2">
                        <tr>
                            <th align="left">Account:
                            </th>
                            <td>
                                <input name="txtacnt" type="text" id="txtacnt" style="width:200px;" />
                            </td>
                        </tr>
                        <tr>
                            <th align="left">User ID:
                            </th>
                            <td>
                                <input name="txtuser" type="text" id="txtuser" style="width:200px;" />
                            </td>
                        </tr>

                        <tr>
                            <th align="left">Password:
                            </th>
                            <td>
                                <input name="txtPwd" type="password" id="txtPwd" style="width:200px;" />
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td valign="top">
                                <input type="submit" name="btnLogin" value="Login" onclick="return checkForm();" id="btnLogin" class="button1" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="left">
                                <small><b style="color: rgb(0, 51, 102);">Note:</b> User Name and Password are Case
                                Sensitive.</small>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="color: Red;" align="center">
                                <span id="lblError" style="font-weight:bold;"><?php echo $errormsg; ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div id="divBrowser" align="center" style="display: none; border: solid 1px gray; margin: 5px; padding: 5px;">
                                    <b>This website is best optimised for IE 9.Kindly upgrade your browser to Internet Explorer
                                    9...! </b>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td align="center" valign="top"></td>
            </tr>
        </table>
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
    </form>
</body>
</html>

