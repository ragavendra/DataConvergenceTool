<?php

$sCGRTyped = $_GET['q'];

$sCGR = '';

require_once "ADCSConfig.php";


// query the database table for zip codes that match 'term'
$sSQL = "select DISTINCT cgr.SERIALNUMBER
from ami.node n, ami.node cgr, AMI.RFLANLINKINFO r
where n.relaykey = cgr.nodekey and n.nodekey = r.nodekey and n.deviceclasskey in ('13', '11')";
//AND cgr.SERIALNUMBER LIKE '" . $sCGRTyped . "%'";

$sADCSConnID = oci_parse($sADCSConn, $sSQL);
$r = oci_execute($sADCSConnID);

// Perform the logic of the query
if (!$r) {
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	echo $e;
}

//$row = mysql_fetch_assoc($sDMTQueryResult);
while ($ADCSrow = oci_fetch_array($sADCSConnID, OCI_ASSOC+OCI_RETURN_NULLS)){ $sCGR .= $ADCSrow[SERIALNUMBER]."\n"; }

echo $sCGR;

?>
