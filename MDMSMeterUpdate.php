<html>
<head>

<title>SMI - MDMS Meter Update Tool</title>
<h1>SMI - MDMS Meter Update Tool</h1>

</head>

<body>
<table>
<p class="medium">
<form name="MDMSMeterUpdate" method="post" action="MDMSMeterUpdateQuery.php" onsubmit="return validateForm()">
<tr>
<td>Environment: </td><td><select name="Environment">
  <option value="QA1">QA1</option>
  <option value="QA2">QA2</option>
  <option value="STAGE">STAGE</option>
</select></td>
</tr>

<tr>
<td>Meter Number(s):&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><textarea class="FormElement" name="MeterNumber" id="MeterNumber" cols="7" rows="5" maxlength="48" WRAP="SOFT"></textarea></td>
<td>4702908<br>3990233</br></td>
</tr>
<tr>
<td>
<input type="Submit" value="Submit">
</td>
<td></td><td></td>
<td>
<input type="reset" value="Clear Form">
</td>
</tr>

</form>
</p>
</table>
</body>
</html>
