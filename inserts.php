<?php
$username="root";
$password="saraswathi";
$database="ragu";

$field1-name=$_POST['field1-name'];
$field2-name=$_POST['field2-name'];
$field3-name=$_POST['field3-name'];

mysql_connect(localhost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query = "INSERT INTO name VALUES ('$field1-name','$field2-name','$field3-name')";

mysql_query($query);

mysql_close();
?>
