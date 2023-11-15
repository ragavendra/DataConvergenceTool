<?php

//debug
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); // change as required
//debug

//session_start();
//include("config.php");
//require_once "DMTConfig.php";
include 'GlobalVariables.php';

session_start();
//$tLoginPeriod = $tLoginPeriod + $_SESSION['timeout'] + 1 * 60;

//echo time() . " ";
//echo $tLoginPeriod;
//$tIdleTimeSet=15 * 60;//after 15 * 60 seconds the user gets logged out
$tIdleTimeActual = time() - $_SESSION['timestamp'];
//echo $tIdleTimeActual;

if (isset($_SESSION['myusername']) and ($tIdleTimeActual < $tIdleTimeSet)) {

  //update the time
  $_SESSION['timestamp'] = time();

  // connect to the database server and select the appropriate database for use
  $sDMTConn = mysql_connect($sDMTServerName, $sDMTUserNameWrite, $sDMTUserPassWrite);

  if (!$sDMTConn) {
    die('Could not connect: ' . mysql_error());
  }

  mysql_select_db($sDMTDBName, $sDMTConn);



  $sNewPasswd = md5($_REQUEST[newpassword]);
  //$sNewPasswd = $_REQUEST[newpassword];

  echo "hi ";

  //on pageload
  session_start();
  echo $_SESSION[myusername];

  if ($_REQUEST["Submit"] == "Update") {
    $sql = "update users set password ='$sNewPasswd' where username='$_SESSION[myusername]'";

    //$sql="update user set password ='$_REQUEST[newpassword]' where user='$_SESSION[uname]'";

    //echo $sql;
    $sDMTQueryResult = mysql_query($sql);

    if (!$sDMTQueryResult) {
      $message  = 'Invalid query: ' . mysql_error() . "\n";
      $message .= 'Whole query: ' . $sql;
      die($message);
    }

    header("Location:PasswordReset.php?msg=updated");
  }


?>
  <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
  <HTML>

  <HEAD>
    <TITLE>Change password</TITLE>
    <script language="javascript" type="text/javascript">
      function validate() {

        var formName = document.frm;

        if (formName.newpassword.value == "") {
          document.getElementById("newpassword_label").innerHTML = 'Please Enter New Password';
          formName.newpassword.focus();
          return false;
        } else {
          document.getElementById("newpassword_label").innerHTML = '';
        }


        if (formName.cpassword.value == "") {
          document.getElementById("cpassword_label").innerHTML = 'Enter ConfirmPassword';
          formName.cpassword.focus();
          return false;
        } else {
          document.getElementById("cpassword_label").innerHTML = '';
        }


        if (formName.newpassword.value != formName.cpassword.value) {
          document.getElementById("cpassword_label").innerHTML = 'Passwords Missmatch';
          formName.cpassword.focus()
          return false;
        } else {
          document.getElementById("cpassword_label").innerHTML = '';
        }
      }
    </script>
    <style type="text/css">
      <!--
      .style1 {
        font-weight: bold
      }

      .style7 {
        color: yellow;
        font-size: 24px;
      }

      .style9 {
        color: #FF6666;
        font-weight: bold;
      }

      .style12 {
        color: #666666;
        font-weight: bold;
      }

      .style14 {
        color: #CC0033;
        font-weight: bold;
      }
      -->
    </style>
    <META http-equiv=Content-Type content="text/html; charset=windows-1252">
  </HEAD>

  <BODY>

    <form action="PasswordReset.php" method="post" name="frm" id="frm" onSubmit="return validate();">
      <table width="47%" border="1" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2" align="center"></td>
        </tr>
        <tr bgcolor="#666666">
          <td colspan="2"><span class="style7">Change Password</span></td>
        </tr>
        <?php if ($_REQUEST[msg] == "updated") { ?>
          <tr bgcolor="#666666">
            <td colspan="2"><span class="style7">Password has been changed successfully.</span></td>
          </tr>
        <?php } ?>
        <tr>
          <td bgcolor="#CCCCCC"><span class="style14">New Password:</span></td>
          <td bgcolor="#CCCCCC"><input type="password" name="newpassword" id="newpassword" size="20" autocomplete="off" />&nbsp; <label id="newpassword_label" class="level_msg"></td>
        </tr>
        <tr>
          <td bgcolor="#CCCCCC"><span class="style14">Confirm Password:</span></td>
          <td bgcolor="#CCCCCC"><input type="password" name="cpassword" id="cpassword" size="20" autocomplete="off">&nbsp; <label id="cpassword_label" class="level_msg"></td>
        </tr>

        <tr bgcolor="#666666">
          <td colspan="2" align="center"><input type="submit" name="Submit" value="Update" onSubmit="return validate();" /></td>
        </tr>

      </table>
      <a href="DataConvergenceTool.php">Home</a>
    </form>
  </BODY>

  </HTML>
<?php
} else {
  session_destroy();
  session_unset();
  echo "Invalid session or Session timed out. Please login to access this page";
?>

  <br /><br />
  <A HREF=main_login.php>Login page</A>
<?php
}
?>