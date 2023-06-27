
<?php
//debug
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); // change as required
//debug

//session_start();
//$tLoginPeriod = $_SESSION['timeout'] + 1 * 60;

//echo time() . " ";
//echo $tLoginPeriod;

include 'GlobalVariables.php';

//on pageload
session_start();

//$tIdleTimeSet=15 * 60;//after 15 * 60 seconds the user gets logged out

$tIdleTimeActual=time()-$_SESSION['timestamp'];

//echo $tIdleTimeActual;
//echo (time()-$_SESSION['timestamp']);

//if(time()-$_SESSION['timestamp'] > $idletime){
 //   $_SESSION['timestamp']=time();
//}
//else {
 //   session_destroy();
 //   session_unset();
//}

//on session creation
//$_SESSION['timestamp']=time();

//if((isset($_SESSION['myusername'])) AND (time() < $tLoginPeriod))

if(isset($_SESSION['myusername']) AND ($tIdleTimeActual < $tIdleTimeSet))
{

//update the time
$_SESSION['timestamp']=time();


?>

<html>
<head>

<link rel="stylesheet" href="style3.css" type="text/css">
<meta charset="utf-8" />
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css" />
<script type="text/javascript" src="jquery.js"></script>
<script type='text/javascript' src='jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="jquery.autocomplete.css" />
<script>
$().ready(function() {
    $("#CGR").autocomplete("CGR.php", {
        width: 50,
        matchContains: true,
        selectFirst: false
    });
});


</script>
<p class="home">
<A HREF = dataminingtool.php>Home</A>
</p>
<p class="logout">
<A HREF = PasswordReset.php>Password Reset</A>
</p>
<p class="logout">
<A HREF = logout.php>Log out</A>
</p>
<title>SMI Data Mining Tool - SMI BCHydro - ADCS-MDMS only</title>
<h1>SMI Data Mining Tool - SMI BCHydro (Beta) - ADCS-MDMS only</h1>
</head>
<body>
<p class="medium">
<form name="DataMiningTool" method="post" action="querytestAMS.php" onsubmit="return validateForm()">

<table border="0">
<tr>
<td>Meter Number:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input type="text" size="12" maxlength="12" name="MeterNumber"></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;eg. 4613635, 461%</td>
<td>To: <input type="text" size="12" maxlength="12" name="MeterNumberTo"></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
</tr>

<tr>
<td>CGR: </td><td><input type="text" size="8" maxlength="8" name="CGR" id="CGR"></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;eg. 15379360</td>
</tr>

<tr>
<td>Environment: </td><td><select name="Environment">
  <option value="QA1">QA1</option>
  <option value="QA2">QA2</option>
  <option value="STAGE">STAGE</option>
</select></td>
</tr>

<tr>
<td>Load: </td><td>
<input type="hidden" name="MDMS" value="0" />
<input type="checkbox" name="MDMS" value="1" checked>MDMS<br>
<input type="hidden" name="ADCS" value="1" />
</td>
</tr>

<tr>
<td>Export as: </td><td><input type="radio" value="XLSX" name="XLSX">XLSX<br /><input type="radio" value="HTML" name="XLSX" checked="checked">HTML<br /><br /></td>
</tr>

<tr>
<td>
<input type="Submit" value="Submit">
</td>
<td></td><td></td>
<td>
<input type="reset" value="Clear Form">
</td>
</tr>

</form>
</p>
<tr>
</tr>
<tr>
<td></td>
<td></td>
<td>
Last SAP QA1 Data Update: 15 July 2013 - 3.00 PM PST
<script src="DataMiningTool.js"></script>
</td>
</tr>
</table>
</br></br>
<center>
<td>
Author - Ragavendra BN - 
<a href="mailto:ragavendra.nagraj@bchydro.com?Subject=SMI - CGR Memory Monitor" target="_top">
ragavendra.nagraj@bchydro.com</a>
</td>
</center>
</br></br>
</body>
</html>

<?php
}
else
{
session_destroy();
session_unset();
echo "Invalid session or Session timed out. Please login to access this page";

?>
<br /><br />
<A HREF = main_login.php>Login page</A>
<?php
}
?>