<head>
<link rel="stylesheet" href="style.css" type="text/css">
</head>

<?php

include 'DBConnections.php';
include 'GlobalVariables.php';
include 'Functions.php';


$sXLSX=$_POST['XLSX'];

If ($sXLSX == "XLSX")
{

$date = date_create();
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=DataConvergenceTool_" . date_timestamp_get($date) . ".xls");  //File name extension was wrong
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);

}$sDMTConn = mysql_connect($sDMTServerName, $sDMTUserName, $sDMTUserPass);
if (!$sDMTConn)
  {
  	echo "Could not connect to DMT database";
  die('Could not connect: ' . mysql_error());
  }
  
mysql_select_db($sDMTDBName, $sDMTConn);


// Connects to the XE service (i.e. database) on the "SMIMDMTST1" machine
$sMDMSConn = oci_connect($sMDMSUserName, $sMDMSUserPass, $sMDMSConnStr);
if (!$sMDMSConn) {
   // $sMDMSConnErr = oci_error();
 //   trigger_error(htmlentities($sMDMSConnErr['message'], ENT_QUOTES), E_USER_ERROR);
	echo "Could not connect to MDMS database";
}

//Oracle End

//ADCS Oracle Start


// Connects to the XE service (i.e. database) on the "SMIMDMTST1" machine
$sADCSConn = oci_connect($sADCSUserName, $sADCSUserPass, $sADCSConnStr);
if (!$sADCSConn) {
    //$sADCSConnStr = oci_error();
   // trigger_error(htmlentities($sADCSConnStr['message'], ENT_QUOTES), E_USER_ERROR);
	echo "Could not connect to ADCS database";
}

//ADCS Oracle End

// This could be supplied by a user, for example

$sMeterNumber=$_POST['MeterNumber'];
$sMeterNumberTo=$_POST['MeterNumberTo'];
$sInstallation=$_POST['Installation'];
$sMaterial=$_POST['Material'];
$sRegisterGroup=$_POST['RegisterGroup'];
$sRateCategory=$_POST['RateCategory'];
$sBillingClass=$_POST['BillingClass'];
$sMRUnit=$_POST['MRUnit'];
$sRowLimit=$_POST['RowLimit'];
//echo $sRowLimit;

$sMeterNumber = preg_replace('/\s+/', ' ', $sMeterNumber);
$sMeterNumberTo=preg_replace('/\s+/', ' ', $sMeterNumberTo);
$sInstallation=preg_replace('/\s+/', ' ', $sInstallation);
$sMaterial=preg_replace('/\s+/', ' ', $sMaterial);
$sRegisterGroup=preg_replace('/\s+/', ' ', $sRegisterGroup);
$sRateCategory=preg_replace('/\s+/', ' ', $sRateCategory);
$sBillingClass=preg_replace('/\s+/', ' ', $sBillingClass);
$sMRUnit=preg_replace('/\s+/', ' ', $sMRUnit);
$sRowLimit=preg_replace('/\s+/', ' ', $sRowLimit);
//echo $sRowLimit;

//$sMeterNumber = str_pad($sMeterNumber, 18, "0", STR_PAD_LEFT);
//$sMeterNumberTo = str_pad($sMeterNumberTo, 18, "0", STR_PAD_LEFT);
//$sMeterNumber = sprintf("%018d",$sMeterNumber);
//$sMeterNumberTo = sprintf("%018d",$sMeterNumberTo);

//DMT query
//$sDMTQuery = "SELECT * FROM smi.sap_qa1 WHERE SystemStatus = 'INST' ORDER BY MeterNumber ASC";
$sDMTQuery = "";

$sTableName="smi.sap_qa1";

//DMT query row count
$sDMTRowCountQuery = "SELECT * FROM smi.sap_qa1 WHERE SystemStatus = 'INST'";

$sNBFlag = True;
//for ($i=1; $i<=4; $i++)
//  {
	if (($sMeterNumber!="") AND ($sMeterNumberTo==""))
{	
//$sMeterNumber = sprintf("%018d",$sMeterNumber);
		$sDMTQuery = $sDMTQuery . " AND SerialNumber LIKE '$sMeterNumber'";
		$sNBFlag = False;
}
	else if (($sMeterNumber!="") AND ($sMeterNumberTo!=""))
{	
//$sMeterNumber = sprintf("%018d",$sMeterNumber);
//$sMeterNumberTo = sprintf("%018d",$sMeterNumberTo);
		$sDMTQuery = $sDMTQuery . " AND SerialNumber BETWEEN '$sMeterNumber' AND '$sMeterNumberTo'";
		$sNBFlag = False;
}

	if ($sInstallation!="")	
{
		$sDMTQuery = $sDMTQuery . " AND Installation LIKE '$sInstallation'";
		$sNBFlag = False;
}
	if ($sMaterial!="")
{
		$sDMTQuery = $sDMTQuery . " AND Material LIKE '$sMaterial'";
		$sNBFlag = False;
}
	if ($sRegisterGroup!="")
{
		$sDMTQuery = $sDMTQuery . " AND RegisterGroup LIKE '$sRegisterGroup'";
		$sNBFlag = False;
}
	if ($sRateCategory!="")
{
		$sDMTQuery = $sDMTQuery . " AND RateCategory LIKE '$sRateCategory'";
		$sNBFlag = False;
}
	if ($sMRUnit!="")
{
		$sDMTQuery = $sDMTQuery . " AND MRUnit LIKE '$sMRUnit'";
		$sNBFlag = False;
}
	if ($sBillingClass!="")
{
		$sDMTQuery = $sDMTQuery . " AND BillingClass LIKE '$sBillingClass'";
		$sNBFlag = False;
}

//If ($sNBFlag == True)
//exit("Please fill value in atleast any one of the fields");

$sDMTQueryCount = "SELECT COUNT(*) as TotalRows FROM $sTableName WHERE SystemStatus = 'INST'" . $sDMTQuery;

//$sDMTQuery = "SELECT * FROM sap_qa1 WHERE SystemStatus = 'INST'" . $sDMTQuery;

$sDMTQuery = "SELECT MRUnit, STR_TO_DATE(`MoveInDate`,'%d.%m.%Y') MoveInDate, STR_TO_DATE(`MoveOutDate`,'%d.%m.%Y') MoveOutDate, Installation, RateCategory, Equipment, RegisterGroup, AMCG, LogicalDeviceNo, Contract, ContractAccount, BusinessPartner, Inactive, Material, SerialNumber, STR_TO_DATE(`ValidTo`,'%d.%m.%Y') ValidTo, STR_TO_DATE(`ValidFrom`,'%d.%m.%Y') ValidFrom, BillingClass, SystemStatus, Premise, SAPUpdateDateTime FROM smi.sap_qa1 WHERE SystemStatus = 'INST'" . $sDMTQuery;


if ($_GET['sCurrentPage'] == ""){

session_start();
// store session data
$_SESSION['$sDMTQueryCountOrig']=$sDMTQueryCount;

// store session data
$_SESSION['$sDMTQueryOrig']=$sDMTQuery;

// store session data
$_SESSION['$sRowLimitOrig']=$sRowLimit;

}

session_start();
if(isset($_SESSION['$sDMTQueryCountOrig'], $_SESSION['$sDMTQueryCountOrig'], $_SESSION['$sRowLimitOrig'], $_SESSION['$sLastPageNo']))
{

	//On page 2
	$sDMTQueryCount = $_SESSION['$sDMTQueryCountOrig'];

	//On page 2
	$sDMTQuery = $_SESSION['$sDMTQueryOrig'];

	//On page 2
	$sRowLimit = $_SESSION['$sRowLimitOrig'];
	//echo "hi";

	$sLastPageNo = $_SESSION['$sLastPageNo'];

}

//pagination start
$sAdjacents = 3;
$sTargetPage = "query.php"; 
$sRowsPerPage = 100; 

$sTotalRows = mysql_fetch_array(mysql_query($sDMTQueryCount));
$sTotalRows = $sTotalRows['TotalRows'];

	//if total rows needed is less than the total row count, make it total rows
		if (($sRowLimit < $sTotalRows) AND (($sRowLimit != "") OR ($sXLSX == "XLSX")))
			{
				$sTotalRows = $sRowLimit;
				//echo "hello";
			}

//	$lastpage = ceil($sTotalRows/$sRowsPerPage);		//lastpage is = total pages / items per page, rounded up.
if (($sRowLimit < $sRowsPerPage) AND (($sRowLimit != "") OR ($sXLSX == "XLSX")))
	{
		$sRowsPerPage = $sRowLimit;
		//echo "hi";
		//echo $sRowsPerPage;
	}
	
	$sCurrentPage = $_GET['sCurrentPage'];
	//echo $sCurrentPage;
	//$sCurrentPage = 10;
	if($sCurrentPage) 
		$start = ($sCurrentPage - 1) * $sRowsPerPage; 			//first item to display on this page
		
		
	else
	{

		$start = 0;								//if no page var is given, set start to 0
	}
	//echo $sTotalRows;
	
	/* Get data. */
	//$sql = "SELECT * FROM $sTableName LIMIT $start, $sRowsPerPage";
	//$result = mysql_query($sql);

	
	$sLastPageNo = ceil($sTotalRows/$sRowsPerPage);


session_start();

//if(isset($_SESSION['$sLastPageNo'])
// store session data
$_SESSION['$sLastPageNo']=$sLastPageNo;




	// Perform Query

	// echo $sDMTQuery;
	$sSQL = $sDMTQuery;
	
//If ($sXLSX != "XLSX")
//{	

	//for last page, put the limit
	//if ($sCurrentPage = $lastpage)
	//$limit = $sTotalRows % $sCurrentPage;
	
	if (($sLastPageNo == $sCurrentPage) AND ($sRowLimit != ""))
		{
			$lastlimit = $sTotalRows % $sRowsPerPage;
			//echo $lastlimit;
			$sSQL = $sDMTQuery  . " LIMIT $start, $lastlimit";
		}
	elseif($sXLSX == "XLSX")
	{
	$sSQL = $sDMTQuery  . " LIMIT $start, $sRowLimit";
	}
	else
	{
	$sSQL = $sDMTQuery  . " LIMIT $start, $sRowsPerPage";
	}
//}

	$sDMTQueryResult = mysql_query($sSQL);
	//echo "hi";
	//echo $sSQL;
	
	/* Setup page vars for display. */
	if ($sCurrentPage == 0) $sCurrentPage = 1;					//if no page var is given, default to 1.
	$prev = $sCurrentPage - 1;							//previous page is page - 1
	$next = $sCurrentPage + 1;							//next page is page + 1
	$lastpage = ceil($sTotalRows/$sRowsPerPage);		//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;	

	
	/* 
		Now we apply our rules and draw the pagination object. 
		We're actually saving the code to a variable in case we want to draw it more than once.
	*/
	$sPagination = "";
	if($lastpage > 1)
	{	
		$sPagination .= "<div class=\"pagination\">";
		//previous button
		if ($sCurrentPage > 1) 
			$sPagination.= "<a href=\"$sTargetPage?sCurrentPage=$prev\"><< previous</a>";
		else
			$sPagination.= "<span class=\"disabled\">? previous</span>";	
		
		//pages	
		if ($lastpage < 7 + ($sAdjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $sCurrentPage)
					$sPagination.= "<span class=\"current\">$counter</span>";
				else
					$sPagination.= "<a href=\"$sTargetPage?sCurrentPage=$counter\">$counter</a>";					
			}
		}
		elseif($lastpage > 5 + ($sAdjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($sCurrentPage < 1 + ($sAdjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($sAdjacents * 2); $counter++)
				{
					if ($counter == $sCurrentPage)
						$sPagination.= "<span class=\"current\">$counter</span>";
					else
						$sPagination.= "<a href=\"$sTargetPage?sCurrentPage=$counter\">$counter</a>";					
				}
				$sPagination.= "...";
				$sPagination.= "<a href=\"$sTargetPage?sCurrentPage=$lpm1\">$lpm1</a>";
				$sPagination.= "<a href=\"$sTargetPage?sCurrentPage=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($sAdjacents * 2) > $sCurrentPage && $sCurrentPage > ($sAdjacents * 2))
			{
				$sPagination.= "<a href=\"$sTargetPage?sCurrentPage=1\">1</a>";
				$sPagination.= "<a href=\"$sTargetPage?sCurrentPage=2\">2</a>";
				$sPagination.= "...";
				for ($counter = $sCurrentPage - $sAdjacents; $counter <= $sCurrentPage + $sAdjacents; $counter++)
				{
					if ($counter == $sCurrentPage)
						$sPagination.= "<span class=\"current\">$counter</span>";
					else
						$sPagination.= "<a href=\"$sTargetPage?sCurrentPage=$counter\">$counter</a>";					
				}
				$sPagination.= "...";
				$sPagination.= "<a href=\"$sTargetPage?sCurrentPage=$lpm1\">$lpm1</a>";
				$sPagination.= "<a href=\"$sTargetPage?sCurrentPage=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$sPagination.= "<a href=\"$sTargetPage?sCurrentPage=1\">1</a>";
				$sPagination.= "<a href=\"$sTargetPage?sCurrentPage=2\">2</a>";
				$sPagination.= "...";
				for ($counter = $lastpage - (2 + ($sAdjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $sCurrentPage)
						$sPagination.= "<span class=\"current\">$counter</span>";
					else
						$sPagination.= "<a href=\"$sTargetPage?sCurrentPage=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($sCurrentPage < $counter - 1) 
			$sPagination.= "<a href=\"$sTargetPage?sCurrentPage=$next\">next >></a>";
		else
			$sPagination.= "<span class=\"disabled\">next ?</span>";
		$sPagination.= "</div>\n";
		
		//echo $counter;
		//echo $sCurrentPage;
		
		//if ($counter == ($sCurrentPage+1))
		
		//{
			session_start();
			
			
			// store session data
			$_SESSION['$sPageStart']= $start;
			
		//	echo $sCurrentPage;
			

		//}

}


	
	
//pagination end

// Check result
// This shows the actual query sent to MySQL, and the error. Useful for debugging.
if (!$sDMTQueryResult) {
    $message  = 'Invalid query: ' . mysql_error() . "\n";
    $message .= 'Whole query: ' . $sDMTQueryPrint;
    die($message);
}

echo "<table border='1'>
  <thead>
    <tr>
      <th colspan=" . 9 . ">SAP Device</th>
      <th colspan=" . 10 . ">SAP Installation</th>
	  <th colspan=" . 9 . ">SAP Others</th>
      <th colspan=" . 7 . ">MDMS</th>
	  <th colspan=" . 8 . ">ADCS</th>
    </tr>
  </thead>
  <tbody>
<th>Meter Type</th>
<th>Serial Number</th>
<th>Equipment</th>
<th>Logical Device No</th>
<th>Register Group</th>
<th>AMCG</th>
<th>System Status</th>
<th>Installation Valid From</th>
<th>Installation Valid To</th>
<th>Business Partner</th>
<th>Premise</th>
<th>Contract Account</th>
<th>Contract</th>
<th>SLID</th>
<th>MRU</th>
<th>Billing Class</th>
<th>Rate Category</th>
<th>Move In</th>
<th>Move Out</th>
<th>Billing Order Exists</th>
<th>Meter Read Order Exists</th>
<th>Meter Reading Date</th>
<th>Meter Reading Reason</th>
<th>Multiple Allocation</th>
<th>Meter Reading Type</th>
<th>Meter Reading Status</th>
<th>Meter Reading Recorded</th>
<th>SAP Data Update Timestamp</th>
<th>Meter Number</th>
<th>Meter ID</th>
<th>Meter CTRatio</th>
<th>Meter PTRatio</th>
<th>Meter ServicePointID</th>
<th>Meter ProgramID</th>
<th>Meter Status</th>
<th>Meter Number</th>
<th>Meter Description</th>
<th>Meter Configuration Group</th>
<th>Meter Application Group</th>
<th>Meter Registered</th>
<th>Meter Status</th>
<th>Comm Module FW</th>
<th>Register FW</th>
</tr>";


$sNull = "NULL";
while($row = mysql_fetch_array($sDMTQueryResult))
  {
  echo "<tr>";
  echo "<td>" . $row['Material'] . "</td>";
  echo "<td>" . $row['SerialNumber'] . "</td>";
  echo "<td>" . $row['Equipment'] . "</td>";
  echo "<td>" . $row['LogicalDeviceNo'] . "</td>";
  echo "<td>" . $row['RegisterGroup'] . "</td>";
  echo "<td>" . $row['AMCG'] . "</td>";
  echo "<td>" . $row['SystemStatus'] . "</td>";
  echo "<td>" . $row['ValidFrom'] . "</td>";
  echo "<td>" . $row['ValidTo'] . "</td>";
  echo "<td>" . $row['BusinessPartner'] . "</td>";
  echo "<td>" . $row['Premise'] . "</td>";
  echo "<td>" . $row['ContractAccount'] . "</td>";
  echo "<td>" . $row['Contract'] . "</td>";
  echo "<td>" . $row['Installation'] . "</td>";
  echo "<td>" . $row['MRUnit'] . "</td>";
  echo "<td>" . $row['BillingClass'] . "</td>";
  echo "<td>" . $row['RateCategory'] . "</td>";
//  echo "<td>" . $row['Inactive'] . "</td>";
  echo "<td>" . $row['MoveInDate'] . "</td>";
  echo "<td>" . $row['MoveOutDate'] . "</td>";


  If ($row['BillingOrderExists'] != "")
  {
	echo "<td>" . $row['BillingOrderExists'] . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  }
  
  If ($row['MeterReadOrderExists'] != "")
  {
	echo "<td>" . $row['MeterReadOrderExists'] . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  }

  If ($row['MeterReadingDate'] != "")
  {
	echo "<td>" . $row['MeterReadingDate'] . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  }

  If ($row['MeterReadingReason'] != "")
  {
	echo "<td>" . $row['MeterReadingReason'] . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  } 

    If ($row['MultipleAllocation'] != "")
  {
	echo "<td>" . $row['MultipleAllocation'] . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  }
    If ($row['MeterReadingType'] != "")
  {
	echo "<td>" . $row['MeterReadingType'] . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  }
    If ($row['MeterReadingStatus'] != "")
  {
	echo "<td>" . $row['MeterReadingStatus'] . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  }

    If ($row['MeterReadingRecorded'] != "")
  {
	echo "<td>" . $row['MeterReadingRecorded'] . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  } 
  
	If ($row['SAPUpdateDateTime'] != "")
  {
	echo "<td>" . $row['SAPUpdateDateTime'] . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  } 
//  echo "<td>" . $row['BillingOrderExists'] . "</td>";
//  echo "<td>" . $row['MeterReadOrderExists'] . "</td>";
//  echo "<td>" . $row['MeterReadingDate'] . "</td>";
//  echo "<td>" . $row['MeterReadingReason'] . "</td>";
//  echo "<td>" . $row['MultipleAllocation'] . "</td>";
//  echo "<td>" . $row['MeterReadingType'] . "</td>";
//  echo "<td>" . $row['MeterReadingStatus'] . "</td>";
//  echo "<td>" . $row['MeterReadingRecorded'] . "</td>";
 
 //oracle from here

$sMeterNumber = (int) $row['SerialNumber'];

$MDMS_Query = "SELECT ITRONEE.METER.METERNUMBER, ITRONEE.METER.METERID, MAX(ITRONEE.METER.CTRATIO), ITRONEE.METER.PTRATIO, ITRONEE.METER.ENDPOINTID, ITRONEE.FLATCONFIGPHYSICAL.SERVICEPOINTID, ITRONEE.FLATCONFIGPHYSICAL.METERPROGRAMID, ITRONEE.FLATCONFIGPHYSICAL.MTRSTATUS 
FROM ITRONEE.METER
INNER JOIN ITRONEE.FLATCONFIGPHYSICAL ON ITRONEE.METER.METERID=ITRONEE.FLATCONFIGPHYSICAL.METERID WHERE ITRONEE.METER.METERNUMBER = '" . $sMeterNumber . "'
GROUP BY ITRONEE.METER.METERNUMBER, ITRONEE.METER.METERID, ITRONEE.METER.PTRATIO, ITRONEE.METER.ENDPOINTID, ITRONEE.FLATCONFIGPHYSICAL.SERVICEPOINTID, ITRONEE.FLATCONFIGPHYSICAL.METERPROGRAMID, ITRONEE.FLATCONFIGPHYSICAL.MTRSTATUS 
ORDER BY ITRONEE.METER.METERNUMBER";

//get conn id
$sMDMSConnID = oci_parse($sMDMSConn, $MDMS_Query);
oci_execute($sMDMSConnID);
$MDMSrow = oci_fetch_array($sMDMSConnID, OCI_ASSOC+OCI_RETURN_NULLS);

    If ($MDMSrow['METERNUMBER'] != "")
  {
	echo "<td>" . $MDMSrow['METERNUMBER'] . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  }
    If ($MDMSrow['METERID'] != "")
  {
	echo "<td>" . $MDMSrow['METERID'] . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  }
    If ($MDMSrow['MAX(ITRONEE.METER.CTRATIO)'] != "")
  {
	echo "<td>" . INTVAL($MDMSrow['MAX(ITRONEE.METER.CTRATIO)']) . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  }
  
      If ($MDMSrow['PTRATIO'] != "")
  {
	echo "<td>" . INTVAL($MDMSrow['PTRATIO']) . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  }
//      If ($MDMSrow['ENDPOINTID'] != "")
//  {
//	echo "<td>" . $MDMSrow['ENDPOINTID'] . "</td>";
//  }
//  else
// {
//	echo "<td>" . $sNull . "</td>";
//  }
      If ($MDMSrow['SERVICEPOINTID'] != "")
  {
	echo "<td>" . $MDMSrow['SERVICEPOINTID'] . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  }
      If ($MDMSrow['METERPROGRAMID'] != "")
  {
	echo "<td>" . $MDMSrow['METERPROGRAMID'] . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  }
      If ($MDMSrow['MTRSTATUS'] != "")
  {
	echo "<td>" . $MDMSrow['MTRSTATUS'] . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  } 
//  echo "<td>" . $MDMSrow['METERNUMBER'] . "</td>";
//  echo "<td>" . $MDMSrow['METERID'] . "</td>";
//  echo "<td>" . INTVAL($MDMSrow['MAX(ITRONEE.METER.CTRATIO)']) . "</td>";
//  echo "<td>" . INTVAL($MDMSrow['PTRATIO']) . "</td>";
//  echo "<td>" . $MDMSrow['ENDPOINTID'] . "</td>";
//  echo "<td>" . $MDMSrow['SERVICEPOINTID'] . "</td>";
//  echo "<td>" . $MDMSrow['METERPROGRAMID'] . "</td>";
//  echo "<td>" . $MDMSrow['MTRSTATUS'] . "</td>";
//oracle till here


 //ADCS oracle from here

//$sMeterNumber = (int) $row['SerialNumber'];

  $ADCS_Query = "select dc.description, n.serialnumber, n.registered, epg.name, gm.state
from ami.node n 
inner join ami.groupmap gm on n.nodekey = gm.nodekey 
inner join ami.endpointgroup epg on gm.endpointgroupkey = epg.endpointgroupkey 
inner join ami.deviceclass dc on dc.deviceclasskey = n.deviceclasskey 
where n.deviceclasskey in ('13', '11') AND n.serialnumber = '" . $sMeterNumber . "'
and epg.grouptype = 'C'
group by n.deviceclasskey, dc.description, n.serialnumber, n.registered, epg.name, gm.state";

$sADCSConnID = oci_parse($sADCSConn, $ADCS_Query);
oci_execute($sADCSConnID);
$ADCSrow = oci_fetch_array($sADCSConnID, OCI_ASSOC+OCI_RETURN_NULLS);

	If ($ADCSrow['SERIALNUMBER'] != "")
  {
	echo "<td>" . $ADCSrow['SERIALNUMBER'] . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  }
	If ($ADCSrow['DESCRIPTION'] != "")
  {
	echo "<td>" . $ADCSrow['DESCRIPTION'] . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  } 
	If ($ADCSrow['NAME'] != "")
  {
	echo "<td>" . $ADCSrow['NAME'] . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  } 

$sADCSConnID = oci_parse($sADCSConn, $ADCS_Group_Query);
oci_execute($sADCSConnID);
$ADCSrow1 = oci_fetch_array($sADCSConnID, OCI_ASSOC+OCI_RETURN_NULLS);

  	If ($ADCSrow1['NAME'] != "")
  {
	echo "<td>" . $ADCSrow1['NAME'] . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  }
// app group till here

  	If ($ADCSrow['REGISTERED'] != "")
  {
	echo "<td>" . $ADCSrow['REGISTERED'] . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  } 
	If ($ADCSrow['STATE'] != "")
  {
	echo "<td>" . $ADCSrow['STATE'] . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  }
  
  $ADCS_COMMODFW_Query = "select fw.VERSION COMMODFW
from ami.node n 
inner join ami.groupmap gm on n.nodekey = gm.nodekey 
inner join ami.endpointgroup epg on gm.endpointgroupkey = epg.endpointgroupkey 
inner join ami.deviceclass dc on dc.deviceclasskey = n.deviceclasskey 
inner join ami.nodefirmwareversion nfw on n.nodekey = nfw.nodekey
inner join ami.firmware fw on nfw.firmwarekey = fw.firmwarekey
where n.deviceclasskey in ('13', '11') AND fw.description = 'Communication Module Firmware' AND n.serialnumber = '" . $sMeterNumber . "'
and epg.grouptype = 'C'
group by fw.VERSION";


$sADCSConnID = oci_parse($sADCSConn, $ADCS_COMMODFW_Query);
oci_execute($sADCSConnID);
$ADCSrow2 = oci_fetch_array($sADCSConnID, OCI_ASSOC+OCI_RETURN_NULLS);

  
	If ($ADCSrow2['COMMODFW'] != "")
  {
	echo "<td>" . $ADCSrow2['COMMODFW'] . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  }
  
  $ADCS_REGFW_Query = "select fw.VERSION REGFW
from ami.node n 
inner join ami.groupmap gm on n.nodekey = gm.nodekey 
inner join ami.endpointgroup epg on gm.endpointgroupkey = epg.endpointgroupkey 
inner join ami.deviceclass dc on dc.deviceclasskey = n.deviceclasskey 
inner join ami.nodefirmwareversion nfw on n.nodekey = nfw.nodekey
inner join ami.firmware fw on nfw.firmwarekey = fw.firmwarekey
where n.deviceclasskey in ('13', '11') AND fw.description = 'Register Firmware' AND n.serialnumber = '" . $sMeterNumber . "'
and epg.grouptype = 'C'
group by fw.VERSION";

$sADCSConnID = oci_parse($sADCSConn, $ADCS_REGFW_Query);
oci_execute($sADCSConnID);
$ADCSrow3 = oci_fetch_array($sADCSConnID, OCI_ASSOC+OCI_RETURN_NULLS);
  
	If ($ADCSrow3['REGFW'] != "")
  {
	echo "<td>" . $ADCSrow3['REGFW'] . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  }  
//  echo "<td>" . $ADCSrow['SERIALNUMBER'] . "</td>";
//  echo "<td>" . $ADCSrow['DESCRIPTION'] . "</td>";
//  echo "<td>" . $ADCSrow['NAME'] . "</td>";
//  echo "<td>" . $ADCSrow['REGISTERED'] . "</td>";
//  echo "<td>" . $ADCSrow['STATE'] . "</td>";
//ADCS oracle till here

  echo "</tr></tbody>";
  }
echo "</table>";

//echo $pagination;
// Free the statement identifier when closing the connection
oci_free_statement($sMDMSConnID);
oci_close($sMDMSConn);

// Free the statement identifier when closing the connection
oci_free_statement($sADCSConnID);
oci_close($sADCSConn);

// Free the resources associated with the result set
// This is done automatically at the end of the script
mysql_free_result($sDMTQueryResult);
mysql_close($sDMTConn);

//echo debug_view ( $sCurrentPage );
//echo debug_view ( $counter );
//echo debug_view ( $sLastPageFlag);

?>

<?=$sPagination?>
