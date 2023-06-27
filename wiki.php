<?php
$con = mysql_connect("localhost","root","saraswathi");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("ragu", $con);

$result = mysql_query("SELECT * FROM name");

echo "<table border='1'>
<tr>
<th>firstname</th>
<th>lastname</th>
</tr>";

while($row = mysql_fetch_array($result))
  {
  echo "<tr>";
  echo "<td>" . $row['firstname'] . "</td>";
  echo "<td>" . $row['lastname'] . "</td>";
  echo "</tr>";
  }
echo "</table>";

mysql_close($con);
?>
