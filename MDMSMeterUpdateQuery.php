<a href=MDMSMeterUpdate.php>Home</A>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

<?php

//debug
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); // change as required
//debug

include 'DBConnections.php';
include 'GlobalVariables.php';

session_start();
//$_SESSION['$sEnvironment']=$_POST['Environment'];
//$_SESSION['$sMeterNumber']=$_POST['MeterNumber'];

$sEnvironment = $_POST['Environment'];
$sMeterNumber = $_POST['MeterNumber'];

//echo $sMeterNumber;

if ($sEnvironment == "QA1") {
	$sMDMSConnStr = $sMDMSConnStrQA;
} elseif ($sEnvironment == "QA2") {
	$sMDMSConnStr = $sMDMSConnStrQA2;
} elseif ($sEnvironment == "STAGE") {
	$sMDMSConnStr = $sMDMSConnStrSTAGE;
} elseif ($sEnvironment == "QA1_RT") {
	$sMDMSConnStr = $sMDMSConnStrQA;
}

// Connects to the XE service (i.e. database) on the "SMIMDMTST1" machine
$sMDMSConn = oci_connect($sMDMSUserName, $sMDMSUserPass, $sMDMSConnStr);
if (!$sMDMSConn) {
	// $sMDMSConnErr = oci_error();
	//  trigger_error(htmlentities($sMDMSConnErr['message'], ENT_QUOTES), E_USER_ERROR);
	echo "Could not connect to MDMS database";
}

$sMeter = array();

foreach (explode("\n", $sMeterNumber) as $sMeter) {

	$iIndex++;
	$sMeter = Trim($sMeter);

	print "\n<pre>\n";
	echo "Running Update for Meter# $iIndex  - $sMeter";
	print "\n<pre>\n";
	//echo "hi";

	//Query1 start
	echo "Query1 - \n";
	$MDMS_Query = "update itronee.meter set endpointid = '" . $sMeter . date("Mj") . "', meternumber = '" . $sMeter . date("Mj") . "' where endpointid = '2.16.840.1.114416.1.63." . $sMeter . "'";

	//get conn id
	$sMDMSConnID = oci_parse($sMDMSConn, $MDMS_Query);
	$sOE = oci_execute($sMDMSConnID);

	if (!$sOE) {
		$e = oci_error($sMDMSConnID);  // For oci_execute errors pass the statement handle
		print htmlentities($e['message']);
		print "\n<pre>\n";
		print htmlentities($e['sqltext']);
		printf("\n%" . ($e['offset'] + 1) . "s", "^");
		print  "\n</pre>\n";
	}

	// Free the statement identifier when closing the connection
	//oci_free_statement($sMDMSConnID);


	//Query2 start
	echo "Query2 - \n";
	$MDMS_Query = "update itronee.recordingdevice set deviceid = '2.16.840.1.114416.1.63." . $sMeter . date("Mj") . "', externalrecordingdeviceid = '2.16.840.1.114416.1.63." . $sMeter . date("Mj") . "' where deviceid = '2.16.840.1.114416.1.63." . $sMeter . "'";

	//get conn id
	$sMDMSConnID = oci_parse($sMDMSConn, $MDMS_Query);
	$sOE = oci_execute($sMDMSConnID);

	if (!$sOE) {
		$e = oci_error($sMDMSConnID);  // For oci_execute errors pass the statement handle
		print htmlentities($e['message']);
		print "\n<pre>\n";
		print htmlentities($e['sqltext']);
		printf("\n%" . ($e['offset'] + 1) . "s", "^");
		print  "\n</pre>\n";
		//echo "hi";
		//echo $MDMS_Query;
	}


	//Query3 start
	echo "Query3 - \n";
	$MDMS_Query = "update itronee.flatconfigphysical set endpointid = '2.16.840.1.114416.1.63." . $sMeter . date("Mj") . "' where endpointid = '2.16.840.1.114416.1.63." . $sMeter . "'";

	//get conn id
	$sMDMSConnID = oci_parse($sMDMSConn, $MDMS_Query);
	$sOE = oci_execute($sMDMSConnID);

	if (!$sOE) {
		$e = oci_error($sMDMSConnID);  // For oci_execute errors pass the statement handle
		print htmlentities($e['message']);
		print "\n<pre>\n";
		print htmlentities($e['sqltext']);
		printf("\n%" . ($e['offset'] + 1) . "s", "^");
		print  "\n</pre>\n";
	}
}
?>