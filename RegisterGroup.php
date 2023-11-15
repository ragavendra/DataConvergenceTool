<?php

$sRegisterGroup = $_GET['q'];

$sMeterT = '';

require_once "DCTConfig.php";

//$sTableName = "sap_qa1";
$sTableName = "matreg";

// query the database table for zip codes that match 'term'
$sSQL = "SELECT DISTINCT RegisterGroup FROM $sTableName WHERE RegisterGroup LIKE '" . $sRegisterGroup . "%'";

//$sTableName = "users";
//$sSQL = "SELECT username FROM $sTableName WHERE username LIKE '%".$sMeterType."%'";

$sDMTQueryResult = mysql_query($sSQL);

//while ($row = mysql_fetch_array($sDMTQueryResult)) { $sMeterT .= $row[username]."\n"; }

while ($row = mysql_fetch_array($sDMTQueryResult)) {
    $sMeterT .= $row[RegisterGroup] . "\n";
}
//echo $sMeterT;

//$o = json_encode($sMeterT);
//echo $o;

echo $sMeterT;
