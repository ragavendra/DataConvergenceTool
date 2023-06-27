<html>
<head>

<title>AFCE - MDMS Meter Update Tool</title>
<h1>AFCE - MDMS Meter Update Too</h1>

</head>

<body>
<p class="medium">
<form name="MDMSMeterUpdate" method="post" action="querytestAMS.php" onsubmit="return validateForm()">
<tr>
<td>Environment: </td><td><select name="Environment">
  <option value="QA1">QA1</option>
  <option value="QA2">QA2</option>
  <option value="STAGE">STAGE</option>
</select></td>
</tr>
<tr>
<td>Meter Number:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input type="text" size="12" maxlength="12" name="MeterNumber"></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;eg. 4613635, 461%</td>
<td>To: <input type="text" size="12" maxlength="12" name="MeterNumberTo"></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
</tr>

</form>
</html>
</head>

<?php
include 'DBConnections.php';
include 'GlobalVariables.php';


	if ($_SESSION['$sEnvironment']=="QA1")
	{
		$sTableName="sap_qa1";
		$sMDMSConnStr=$sMDMSConnStrQA;
		$sADCSConnStr=$sADCSConnStrQA;
	}
	elseif ($_SESSION['$sEnvironment']=="QA2")
	{
		$sTableName="sap_qa2";
		$sMDMSConnStr=$sMDMSConnStrQA2;
		$sADCSConnStr=$sADCSConnStrQA2;
	}
	elseif ($_SESSION['$sEnvironment']=="STAGE")
	{
		$sTableName="sap_stage";
		$sMDMSConnStr=$sMDMSConnStrSTAGE;
		$sADCSConnStr=$sADCSConnStrSTAGE;
	}
	elseif ($_SESSION['$sEnvironment']=="QA1_RT")
	{
		$sTableName="sap_qa1";
		$sMDMSConnStr=$sMDMSConnStrQA;
		$sADCSConnStr=$sADCSConnStrQA;
	}
	

	// Connects to the XE service (i.e. database) on the "SMIMDMTST1" machine
	$sMDMSConn = oci_connect($sMDMSUserName, $sMDMSUserPass, $sMDMSConnStr);
	if (!$sMDMSConn) {
	   // $sMDMSConnErr = oci_error();
	  //  trigger_error(htmlentities($sMDMSConnErr['message'], ENT_QUOTES), E_USER_ERROR);
		echo "Could not connect to MDMS database";
	}
	
//get conn id
$sMDMSConnID = oci_parse($sMDMSConn, $MDMS_Query);
oci_execute($sMDMSConnID);
$MDMSrow = oci_fetch_array($sMDMSConnID, OCI_ASSOC+OCI_RETURN_NULLS);

$data = array();
$firstLine = true;
foreach(explode("\n", $string) as $line) {
    if($firstLine) { $firstLine = false; continue; } // skip first line
    $row = explode('^', $line);
    $data[] = array(
        'id' => (int)$row[0],
        'name' => $row[1],
        'description' => $row[2],
        'images' => explode(',', $row[3])
    );
}
?>