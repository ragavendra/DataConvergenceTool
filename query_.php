//$sNBFlag = True;
//for ($i=1; $i<=4; $i++)
//  {
	if ($MeterNumber!="")	$sQuery = $sQuery . " AND SerialNumber = '$MeterNumber'";
//	{
//		$sQuery = $sQuery . " AND SerialNumber = '$MeterNumber'";
//		$sNBFlag = False;
//	}
//	(
	if ($Installation!="")	$sQuery = $sQuery . " AND SerialNumber = '$MeterNumber'";
//	{
//		$sQuery = $sQuery . " AND Installation = '$Installation'";
//		$sNBFlag = False;
//	}
	if ($Material!="")	$sQuery = $sQuery . " AND SerialNumber = '$MeterNumber'";
//	{
//		$sQuery = $sQuery . " AND Material = '$Material'";
//		$sNBFlag = False;
//	}
	if ($RateCategory!="")	$sQuery = $sQuery . " AND SerialNumber = '$MeterNumber'";
//	{
//		$sQuery = $sQuery . " AND RateCategory = '$RateCategory'";
//		$sNBFlag = False;
	}
  //echo "The number is " . $i . "<br>";
//  }