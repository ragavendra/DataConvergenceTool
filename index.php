<?php

//include 'dataconvergencetool.php';
include 'GlobalVariables.php';
session_start();
//$tLoginPeriod = $tLoginPeriod + $_SESSION['timeout'] + 1 * 60;

//echo time() . " ";
//echo $tLoginPeriod;
//$tIdleTimeSet=15 * 60;//after 15 * 60 seconds the user gets logged out
$tIdleTimeActual=time()-$_SESSION['timestamp'];
//echo $tIdleTimeActual;

if(isset($_SESSION['myusername']) AND ($tIdleTimeActual < $tIdleTimeSet))
{
//update the time
$_SESSION['timestamp']=time();

header( 'Location: dataconvergencetool.php' ) ;
//header( 'Location: http://tc001423/dataconvergencetool/dataconvergencetool.php' ) ;
//header( 'Location: http://hy201440/smidct/dataconvergencetool.php' ) ;
}
else
{
?>

<table width="300" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
<tr>
<form name="form1" method="post" action="checklogin.php">
<td>
<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
<tr>
<td colspan="3"><strong>Member Login </strong></td>
</tr>
<tr>
<td width="78">Username</td>
<td width="6">:</td>
<td width="294"><input name="myusername" type="text" id="myusername"></td>
</tr>
<tr>
<td>Password</td>
<td>:</td>
<td><input name="mypassword" type="password" id="mypassword"></td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td><input type="submit" name="Submit" value="Login"></td>
</tr>
</table>
</td>
</form>
</tr>
</br></br>
<center>
<td>
<!--Author - Ragavendra BN -->
<center>Questions email - 
<a href="mailto:user@afcemail.com?Subject=Data Convergence Tool" target="_top">
user@afcemail.com</a></center>
</td>
</center>
</br></br>
<?php
}
?>