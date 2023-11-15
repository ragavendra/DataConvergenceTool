<?php

$sMeterType = $_GET['q'];

$sMeterT = '';

require_once "DCTConfig.php";

//$sTableName = "sap_qa1";
$sTableName = "matreg";

// query the database table for zip codes that match 'term'
$sSQL = "SELECT DISTINCT Material FROM $sTableName WHERE Material LIKE '" . $sMeterType . "%'";

//$sTableName = "users";
//$sSQL = "SELECT username FROM $sTableName WHERE username LIKE '%".$sMeterType."%'";

$sDMTQueryResult = mysql_query($sSQL);

//while ($row = mysql_fetch_array($sDMTQueryResult)) { $sMeterT .= $row[username]."\n"; }

while ($row = mysql_fetch_array($sDMTQueryResult)) {
    $sMeterT .= $row[Material] . "\n";
}
//echo $sMeterT;

//$o = json_encode($sMeterT);
//echo $o;

echo $sMeterT;
