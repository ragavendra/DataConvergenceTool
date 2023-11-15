<?php

// connect to the database server and select the appropriate database for use
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli("localhost", "stops", "Platty20@3", "stopsNearMe");

//$sTableName = "sap_qa1";
$sTableName = "stops";

// query the database table for zip codes that match 'term'
// $sSQL = "SELECT DISTINCT RegisterGroup FROM $sTableName WHERE RegisterGroup LIKE '".$sRegisterGroup."%'";
$sSQL = "SELECT * FROM $sTableName";

$sDMTQueryResult = $mysqli->query($sSQL);
echo "Result ... \n";

foreach ($sDMTQueryResult as $row) {
    echo " Stop Name = " . $row['stopName'] . "\n";
}
