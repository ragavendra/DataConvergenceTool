
<?php

//debug
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); // change as required
//debug

include 'DBConnections.php';
include 'GlobalVariables.php';

ob_start();
$host="localhost"; // Host name
$username=""; // Mysql username
$password=""; // Mysql password
$db_name="test"; // Database name
$tbl_name="users"; // Table name

// Connect to server and select databse.
mysql_connect("$sDMTServerName", "$sDMTUserName", "$sDMTUserPass")or die("cannot connect");
mysql_select_db("$sDMTDBName")or die("cannot select DB");

// Define $myusername and $mypassword
$myusername=$_POST['myusername'];
$mypassword=$_POST['mypassword'];

// To protect MySQL injection (more detail about MySQL injection)
$myusername = stripslashes($myusername);
$mypassword = md5(stripslashes($mypassword));
$myusername = mysql_real_escape_string($myusername);
$mypassword = mysql_real_escape_string($mypassword);

$sql="SELECT * FROM $tbl_name WHERE username='$myusername' and password='$mypassword'";
$result=mysql_query($sql);

// Mysql_num_row is counting table row
$count=mysql_num_rows($result);

// If result matched $myusername and $mypassword, table row must be 1 row

if($count==1){

// Register $myusername, $mypassword and redirect to file "login_success.php"
//session_register("myusername");
//session_register("mypassword");
session_start();
// store session data
$_SESSION['myusername']=$myusername;
$_SESSION['mypassword']=$mypassword;

//code to timeout session for 1 minute
//on session creation
$_SESSION['timestamp']=time();

header("location:dataminingtool.php");
}
else {
echo "Wrong Username or Password";
}

ob_end_flush();
?>