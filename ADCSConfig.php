<?php


require_once 'GlobalVariables.php';

	// Connects to the XE service (i.e. database) on the "SMIMDMTST1" machine
	$sADCSConn = oci_connect($sADCSUserName, $sADCSUserPass, $sADCSConnStr);
	if (!$sADCSConn) {
		$sADCSConnStr = oci_error();
		trigger_error(htmlentities($sADCSConnStr['message'], ENT_QUOTES), E_USER_ERROR);
		echo "Could not connect";
	}

?>