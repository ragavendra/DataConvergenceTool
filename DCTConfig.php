<?php

include 'DBConnections.php';
include 'GlobalVariables.php';

// connect to the database server and select the appropriate database for use
$sDMTConn = mysql_connect($sDMTServerName, $sDMTUserName, $sDMTUserPass);

if (!$sDMTConn) {
  die('Could not connect: ' . mysql_error());
}

mysql_select_db($sDMTDBName, $sDMTConn);
