

<?php
//debug
//ini_set('log_errors', 1);
//ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); // change as required
//debug

//include 'dataminingtool.php';
include 'GlobalVariables.php';
session_start();

$tIdleTimeActual=time()-$_SESSION['timestamp'];


if(isset($_SESSION['myusername']) AND ($tIdleTimeActual < $tIdleTimeSet))
{

//update the time
$_SESSION['timestamp']=time();

$sXLSX=$_POST['XLSX'];
//echo $sXLSX;

	//if total rows needed is less than the total row count, make it total rows
		if ($sXLSX != "XLSX")
		{
?>
<head>
<link rel="stylesheet" href="style2.css" type="text/css">
<link rel="stylesheet" href="style.css" type="text/css">
</head>
<p class="home">
<A HREF = dataminingtool.php>Home</A>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</p>
<p class="home">
<A HREF = dataminingtoolAMS.php>ADCS-MDMS only</A>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</p>
<p class="logout">
<A HREF = logout.php>Log out</A>
</p>
<?php
}

include 'DBConnections.php';
include 'GlobalVariables.php';
include 'Functions.php';

//DMT Connect
$sDMTConn = mysql_connect($sDMTServerName, $sDMTUserName, $sDMTUserPass);
if (!$sDMTConn)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db($sDMTDBName, $sDMTConn);


// This could be supplied by a user, for example

$sMeterNumber=$_POST['MeterNumber'];
$sMeterNumberTo=$_POST['MeterNumberTo'];
$sCGR=$_POST['CGR'];
$sRowLimit=$_POST['RowLimit'];


$sMDMS=$_POST['MDMS'];
$sADCS=$_POST['ADCS'];


if ($_POST['Environment'] != "")
{
session_start();
$_SESSION['$sEnvironment']=$_POST['Environment'];
}
//echo $sEnvironment;


if (($_POST['MDMS'] != "") OR ($_POST['ADCS'] != ""))
{
	session_start();
	$_SESSION['$sMDMS']=$_POST['MDMS'];
	$_SESSION['$sADCS']=$_POST['ADCS'];
}


$sMeterNumber = preg_replace('/\s+/', ' ', $sMeterNumber);
$sMeterNumberTo=preg_replace('/\s+/', ' ', $sMeterNumberTo);
$sCGR=preg_replace('/\s+/', ' ', $sCGR);

$sRowLimit=preg_replace('/\s+/', ' ', $sRowLimit);
//echo $sMaterial;
session_start();

	//On page 2
	$sDMTQueryCount = $_SESSION['$sDMTQueryCountOrig'];

	
	if ($_SESSION['$sEnvironment']=="QA1")
	{
		$sTableName="sap_qa1";
		$sMDMSConnStr=$sMDMSConnStrQA;
		$sADCSConnStr=$sADCSConnStrQA;
	}
	elseif ($_SESSION['$sEnvironment']=="QA2")
	{
		$sTableName="sap_qa2";
		$sMDMSConnStr=$sMDMSConnStrQA2;
		$sADCSConnStr=$sADCSConnStrQA2;
	}
	elseif ($_SESSION['$sEnvironment']=="STAGE")
	{
		$sTableName="sap_stage";
		$sMDMSConnStr=$sMDMSConnStrSTAGE;
		$sADCSConnStr=$sADCSConnStrSTAGE;
	}
	elseif ($_SESSION['$sEnvironment']=="QA1_RT")
	{
		$sTableName="saprt_qa1";
		$sMDMSConnStr=$sMDMSConnStrQA;
		$sADCSConnStr=$sADCSConnStrQA;
	}
		
if ($_SESSION['$sMDMS'] == "1")
{
	// Connects to the XE service (i.e. database) on the "SMIMDMTST1" machine
	$sMDMSConn = oci_connect($sMDMSUserName, $sMDMSUserPass, $sMDMSConnStr);
	if (!$sMDMSConn) {
	   // $sMDMSConnErr = oci_error();
	  //  trigger_error(htmlentities($sMDMSConnErr['message'], ENT_QUOTES), E_USER_ERROR);
		echo "Could not connect to MDMS database";
	}
}
//Oracle End

//ADCS Oracle Start

if ($_SESSION['$sADCS'] == "1")
{
	// Connects to the XE service (i.e. database) on the "SMIMDMTST1" machine
	$sADCSConn = oci_connect($sADCSUserName, $sADCSUserPass, $sADCSConnStr);
	if (!$sADCSConn) {
		$sADCSConnStr = oci_error();
		trigger_error(htmlentities($sADCSConnStr['message'], ENT_QUOTES), E_USER_ERROR);
		echo "Could not connect";
	}
}

//ADCS Oracle End


$sNBFlag = True;

	if (($sMeterNumber!="") AND ($sMeterNumberTo==""))
{	

		$sDMTQuery = $sDMTQuery . " AND n.serialnumber LIKE '$sMeterNumber'";
		$sNBFlag = False;
}
	else if (($sMeterNumber!="") AND ($sMeterNumberTo!=""))
{	

		$sDMTQuery = $sDMTQuery . " AND n.serialnumber BETWEEN '$sMeterNumber' AND '$sMeterNumberTo'";
		$sNBFlag = False;
}

	if ($sCGR!="")	
{
		$sDMTQuery = $sDMTQuery . " AND cgr.SERIALNUMBER LIKE '$sCGR'";
		$sNBFlag = False;
}


  $sDMTQueryCount = "select COUNT(n.serialnumber) as TotalRows
from ami.node n 
inner join ami.groupmap gm on n.nodekey = gm.nodekey 
inner join ami.endpointgroup epg on gm.endpointgroupkey = epg.endpointgroupkey 
inner join ami.deviceclass dc on dc.deviceclasskey = n.deviceclasskey 
inner join ami.node cgr on n.relaykey = cgr.nodekey
inner join AMI.RFLANLINKINFO r on n.nodekey = r.nodekey
where n.deviceclasskey in ('13', '11') AND epg.grouptype = 'C'" . $sDMTQuery;

//echo $sDMTQueryCount;


	if ($_SESSION['$sEnvironment']=="QA1_RT")
		{
			//$sDMTQuery = "SELECT MRUnit, STR_TO_DATE(`MoveInDate`,'%d.%m.%Y') MoveInDate, STR_TO_DATE(`MoveOutDate`,'%d.%m.%Y') MoveOutDate, Installation, RateCategory, Equipment, RegisterGroup, AMCG, LogicalDeviceNo, Contract, ContractAccount, BusinessPartner, Inactive, Material, SerialNumber, STR_TO_DATE(`ValidTo`,'%d.%m.%Y') ValidTo, STR_TO_DATE(`ValidFrom`,'%d.%m.%Y') ValidFrom, BillingClass, SystemStatus, Premise, SAPUpdateDateTime, RateType FROM $sTableName WHERE 1" . $sDMTQuery;
			$sDMTQuery = "SELECT DISTINCT(epg.name), dc.description, n.serialnumber, n.registered, to_char(n.lastreadtime, 'yyyy-mm-dd hh24:mi:ss') LastReadTime, gm.state
								from ami.node n 
								inner join ami.groupmap gm on n.nodekey = gm.nodekey 
								inner join ami.endpointgroup epg on gm.endpointgroupkey = epg.endpointgroupkey 
								inner join ami.deviceclass dc on dc.deviceclasskey = n.deviceclasskey 
								where n.deviceclasskey in ('13', '11') AND epg.grouptype = 'C'" . $sDMTQuery;
								
								
			/*$sDMTQuery = "select * from (
  select a.*, ROWNUM rnum from (" . sDMTQuery . ") where ROWNUM <= MAX_ROW
) where rnum >= MIN_ROW";*/



		}
	else
		{
			//$sDMTQuery = "SELECT DISTINCT MRUnit, STR_TO_DATE(`MoveInDate`,'%d.%m.%Y') MoveInDate, STR_TO_DATE(`MoveOutDate`,'%d.%m.%Y') MoveOutDate, Installation, RateCategory, Equipment, RegisterGroup, AMCG, LogicalDeviceNo, Contract, ContractAccount, BusinessPartner, Inactive, Material, SerialNumber, STR_TO_DATE(`ValidTo`,'%d.%m.%Y') ValidTo, STR_TO_DATE(`ValidFrom`,'%d.%m.%Y') ValidFrom, BillingClass, SystemStatus, Premise, SAPUpdateDateTime FROM $sTableName WHERE 1" . $sDMTQuery;
			//"select n.SERIALNUMBER as Meter_Serial, cgr.SERIALNUMBER as CGR_Serial, r.lastupdatetime as CGR_LASTUPDATE, r.RFLANLEVEL AS CGR_RFLANLEVEL, r.BESTFATHERMACADDRESS, R.COUNTREGISTRATIONSSENT, R.COUNTREGISTRATIONSFAILED, R.SYNCHFATHERAVERAGERSSI AS FATHERAVERAGERSSI"

			$sDMTQuery = "SELECT DISTINCT(epg.name), dc.description, n.serialnumber, n.registered, to_char(n.lastreadtime, 'yyyy-mm-dd hh24:mi:ss') LastReadTime, cgr.SERIALNUMBER as CGR_Serial, r.lastupdatetime as CGR_LASTUPDATE, r.RFLANLEVEL AS CGR_RFLANLEVEL, r.BESTFATHERMACADDRESS, R.COUNTREGISTRATIONSSENT, R.COUNTREGISTRATIONSFAILED, R.SYNCHFATHERAVERAGERSSI AS FATHERAVERAGERSSI
							from ami.node n
							inner join ami.groupmap gm on n.nodekey = gm.nodekey 
							inner join ami.endpointgroup epg on gm.endpointgroupkey = epg.endpointgroupkey 
							inner join ami.deviceclass dc on dc.deviceclasskey = n.deviceclasskey
							inner join ami.node cgr on n.relaykey = cgr.nodekey
							inner join AMI.RFLANLINKINFO r on n.nodekey = r.nodekey
							where n.deviceclasskey in ('13', '11') AND epg.grouptype = 'C'" . $sDMTQuery . "ORDER BY n.serialnumber";
							
							/*
			$sDMTQuery = "SELECT DISTINCT(epg.name), dc.description, n.serialnumber, n.registered, to_char(n.lastreadtime, 'yyyy-mm-dd hh24:mi:ss') LastReadTime, gm.state, cgr.SERIALNUMBER as CGR_Serial, r.lastupdatetime as CGR_LASTUPDATE, r.RFLANLEVEL AS CGR_RFLANLEVEL, r.BESTFATHERMACADDRESS, R.COUNTREGISTRATIONSSENT, R.COUNTREGISTRATIONSFAILED, R.SYNCHFATHERAVERAGERSSI AS FATHERAVERAGERSSI
							from ami.node n
							inner join ami.groupmap gm on n.nodekey = gm.nodekey 
							inner join ami.endpointgroup epg on gm.endpointgroupkey = epg.endpointgroupkey 
							inner join ami.deviceclass dc on dc.deviceclasskey = n.deviceclasskey
							inner join ami.node cgr on n.relaykey = cgr.nodekey
							inner join AMI.RFLANLINKINFO r on n.nodekey = r.nodekey
							where n.deviceclasskey in ('13', '11') AND epg.grouptype = 'C'" . $sDMTQuery;							
							//echo $sDMTQuery; */
		}

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
if(isset($_SESSION['$sDMTQueryCountOrig'], $_SESSION['$sDMTQueryOrig'], $_SESSION['$sRowLimitOrig'], $_SESSION['$sLastPageNo']))
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
$sTargetPage = "querytestAMS.php"; 
$sRowsPerPage = 50; 

//echo $sDMTQueryCount;
$sTotalRows = oci_parse($sADCSConn, $sDMTQueryCount);
oci_execute($sTotalRows);
$sTotalRows = oci_fetch_array($sTotalRows, OCI_ASSOC+OCI_RETURN_NULLS);
$sTotalRows = $sTotalRows['TOTALROWS'];
//echo $sDMTQueryCount;
//echo $sTotalRows;

//group by n.deviceclasskey, dc.description, n.serialnumber, n.registered, n.lastreadtime, epg.name, gm.state";

//echo $sDMTQueryCount;
/*
$sADCSConnID = oci_parse($sADCSConn, $sDMTQueryCount);
oci_execute($sADCSConnID);
$sTotalRows = oci_fetch_array($sADCSConnID, OCI_ASSOC+OCI_RETURN_NULLS);
$sTotalRows = $sTotalRows['TotalRows'];
*/
	//If ($ADCSrow['SERIALNUMBER'] != "")

///

//$row = mysql_fetch_assoc($sDMTQueryResult);
//$ADCSrow = oci_fetch_array($sADCSConnID, OCI_ASSOC+OCI_RETURN_NULLS);


$sXLSX=$_POST['XLSX'];
//echo $sXLSX;

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
	

//}


//$sADCSConnID = oci_parse($sADCSConn, $sSQL);
//oci_execute($sSQL);


	//$sDMTQueryResult = mysql_query($sSQL);
	
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
		
			session_start();
			
			
			// store session data
			$_SESSION['$sPageStart']= $start;
			
}

	
//pagination end

	
// Check result
// This shows the actual query sent to MySQL, and the error. Useful for debugging.
//if (!$sDMTQueryResult) {
 //   $message  = 'Invalid query: ' . mysql_error() . "\n";
 //   $message .= 'Whole query: ' . $sDMTQueryPrint;
 //   die($message);
//}

//$sXLSX=$_POST['XLSX'];

If ($sXLSX == "XLSX")
{

$date = date_create();
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=DataMiningTool_" . date_timestamp_get($date) . ".xls");  //File name extension was wrong
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);

}
//	  <th colspan=" . 0 . ">SAP Others</th>
//<th>SAP Data Update Timestamp</th>

//echo $sEnvironment;
if ($_SESSION['$sEnvironment']=="QA1_RT")
{echo "<table border='1'>
  <thead>
    <tr>
		<th colspan=" . 10 . ">ADCS</th>
		<th colspan=" . 7 . ">MDMS</th>
		<th colspan=" . 9 . ">SAP Device</th>
		<th colspan=" . 11 . ">SAP Installation</th>

    </tr>

<th>Meter Number</th>
<th>Description</th>
<th>Last Read Time</th>
<th>Configuration Group</th>
<th>Membership</th>
<th>Application Group(s)</th>
<th>Registered</th>
<th>Comm Module FW</th>
<th>Register FW</th>
<th>FW Last Update</th>

<th>Meter No</th>
<th>Meter ID</th>
<th>CTRatio</th>
<th>PTRatio</th>
<th>ServicePointID</th>
<th>ProgramID</th>
<th>Status</th>

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
<th>Rate Type</th>
<th>Move In</th>
<th>Move Out</th>


";}
else
{
echo "<table border='1'>
  <thead>
    <tr>
		<th colspan=" . 17 . ">ADCS</th>
		<th colspan=" . 7 . ">MDMS</th>
    </tr>
	
	
<th>Meter Number</th>
<th>Description</th>
<th>Last Read Time</th>
<th>Configuration Group</th>
<th>Membership</th>
<th>Application Group(s)</th>
<th>Registered</th>
<th>Comm Module FW</th>
<th>Register FW</th>
<th>FW Last Update</th>

<th>CGR Number</th>
<th>CGR Last Update</th>
<th>Meter RFLAN Level</th>
<th>Meter Best Father MAC</th>
<th>Meter Father Avg. SSI</th>
<th>Meter Reg. Sent</th>
<th>Meter Reg. Failed</th>

<th>Meter No</th>
<th>Meter ID</th>
<th>CTRatio</th>
<th>PTRatio</th>
<th>ServicePointID</th>
<th>ProgramID</th>
<th>Status</th>

";
}

echo "</thead><tbody>";

$sNull = "NA";

$_SESSION['sRowExists'] = FALSE;



	if (($sLastPageNo == $sCurrentPage) AND ($sRowLimit != ""))
		{
			$lastlimit = $sTotalRows % $sRowsPerPage;
			$sSQL = $sDMTQuery  . " LIMIT $start, $lastlimit";
			//echo $sSQL;
		}
	else
	{
	//$sSQL = $sDMTQuery  . " LIMIT $start, $sRowsPerPage";
	//echo $sSQL;
	
	//echo $sRowsPerPage . "\n";
	//echo $sCurrentPage . "\n";
	//echo $sTotalRows . "\n";
	
	$lastlimits = $sCurrentPage * $sRowsPerPage;
	
	if ($lastlimits >= $sTotalRows)
	$lastlimits = $sTotalRows;
	
	//echo $sTotalRows;
	
	
		//echo $sDMTQuery;
		
		//echo $lastlimits;
		
	//put formula to get remainder
	$sSQL = "select * from 
( select a.*, ROWNUM rnum from 
  ( $sDMTQuery ) a 
  where ROWNUM <= $lastlimits  )
where rnum  >= " . ($start + 1);
//echo $sSQL;

	}
	//echo $sXLSX;
	if($sXLSX == "XLSX")
	{
	//$sSQL = $sDMTQuery  . " LIMIT $start, $sRowLimit";
	$sSQL = $sDMTQuery;
	//echo $sXLSX;
	}
	

//	echo $sSQL;
	
$sADCSConnID = oci_parse($sADCSConn, $sSQL);
$r = oci_execute($sADCSConnID);

// Perform the logic of the query
if (!$r) {
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

//$row = mysql_fetch_assoc($sDMTQueryResult);
while ($ADCSrow = oci_fetch_array($sADCSConnID, OCI_ASSOC+OCI_RETURN_NULLS)){

//echo $sSQL;

  echo "<tr>";

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
  
  	If ($ADCSrow['LASTREADTIME'] != "")
  {
	echo "<td>" . $ADCSrow['LASTREADTIME'] . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  } 
  
  $sMeterNumber = (int) $ADCSrow['SERIALNUMBER'];
  
    $ADCS_Query = "select dc.description, n.serialnumber, n.registered, to_char(n.lastreadtime, 'yyyy-mm-dd hh24:mi:ss') LastReadTime, epg.name, gm.state
from ami.node n 
inner join ami.groupmap gm on n.nodekey = gm.nodekey 
inner join ami.endpointgroup epg on gm.endpointgroupkey = epg.endpointgroupkey 
inner join ami.deviceclass dc on dc.deviceclasskey = n.deviceclasskey 
where n.deviceclasskey in ('13', '11') AND n.serialnumber = '" . $sMeterNumber . "'
and epg.grouptype = 'C'
group by n.deviceclasskey, dc.description, n.serialnumber, n.registered, n.lastreadtime, epg.name, gm.state";

$sADCSConnIDs = oci_parse($sADCSConn, $ADCS_Query);
$r = oci_execute($sADCSConnIDs);

// Perform the logic of the query
if (!$r) {
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

//$ADCSrowZ = oci_fetch_array($sADCSConnID, OCI_ASSOC+OCI_RETURN_NULLS);

$sValueExists = False;
  echo "<td>";
  while ($ADCSrowZ= oci_fetch_array($sADCSConnIDs, OCI_ASSOC+OCI_RETURN_NULLS)){
	echo $ADCSrowZ['NAME'];
	echo "<br />";
	$sValueExists = True;
 }
 if (($ADCSrowZ==FALSE) AND ($sValueExists == FALSE))	echo $sNull;
 
 echo "</td>";
 
    $ADCS_Query = "select gm.state
from ami.node n 
inner join ami.groupmap gm on n.nodekey = gm.nodekey 
inner join ami.endpointgroup epg on gm.endpointgroupkey = epg.endpointgroupkey 
inner join ami.deviceclass dc on dc.deviceclasskey = n.deviceclasskey 
where n.deviceclasskey in ('13', '11') AND n.serialnumber = '" . $sMeterNumber . "'
and epg.grouptype = 'C'
group by epg.name, gm.state";

  $sADCSConnIDt = oci_parse($sADCSConn, $ADCS_Query);
oci_execute($sADCSConnIDt);
//$ADCSrowS = oci_fetch_array($sADCSConnID, OCI_ASSOC+OCI_RETURN_NULLS);

$sValueExists = False;
   echo "<td>";
while ($ADCSrowS = oci_fetch_array($sADCSConnIDt, OCI_ASSOC+OCI_RETURN_NULLS)) {

	echo $ADCSrowS['STATE'];
	echo "<br />";
	$sValueExists = True;
 }
 if (($ADCSrowS==FALSE) AND ($sValueExists == FALSE))	echo $sNull;

  echo "</td>";
// app group till here

  // app group from here
$ADCS_Group_Query = "select epg.name 
from ami.node n 
inner join ami.groupmap gm on n.nodekey = gm.nodekey 
inner join ami.endpointgroup epg on gm.endpointgroupkey = epg.endpointgroupkey 
inner join ami.deviceclass dc on dc.deviceclasskey = n.deviceclasskey 
where n.deviceclasskey in ('13', '11') and epg.grouptype = 'A' AND n.serialnumber = '" . $sMeterNumber . "'
group by epg.name";

$sADCSConnIDu = oci_parse($sADCSConn, $ADCS_Group_Query);
oci_execute($sADCSConnIDu);
//$ADCSrow1 = oci_fetch_array($sADCSConnID, OCI_ASSOC+OCI_RETURN_NULLS);

$sValueExists = False;
echo "<td>";

while ($ADCSrow1 = oci_fetch_array($sADCSConnIDu, OCI_ASSOC+OCI_RETURN_NULLS)) {

	echo $ADCSrow1['NAME'];
	echo "<br />";
	$sValueExists = True;
  }
   if (($ADCSrowS==FALSE) AND ($sValueExists == FALSE))	echo $sNull;
  echo "</td>";
// app group till here

  	If ($ADCSrow['REGISTERED'] != "")
  {
	echo "<td>" . $ADCSrow['REGISTERED'] . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  } 
//	If ($ADCSrow['STATE'] != "")
 // {
//	echo "<td>" . $ADCSrow['STATE'] . "</td>";
//  }
// else
// {
//echo "<td>" . $sNull . "</td>";
 //}
  

  //echo $ADCS_COMMODFW_Query;
  // COMMODFW from here
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

$sADCSConnIDv = oci_parse($sADCSConn, $ADCS_COMMODFW_Query);
oci_execute($sADCSConnIDv);
$ADCSrow2 = oci_fetch_array($sADCSConnIDv, OCI_ASSOC+OCI_RETURN_NULLS);

  
	If ($ADCSrow2['COMMODFW'] != "")
  {
	echo "<td>" . $ADCSrow2['COMMODFW'] . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  }

  // REGFW from here
$ADCS_REGFW_Query = "select fw.VERSION REGFW, to_char(nfw.LASTUPDATED, 'yyyy-mm-dd hh24:mi:ss') FWLASTUPDATE
from ami.node n 
inner join ami.groupmap gm on n.nodekey = gm.nodekey 
inner join ami.endpointgroup epg on gm.endpointgroupkey = epg.endpointgroupkey 
inner join ami.deviceclass dc on dc.deviceclasskey = n.deviceclasskey 
inner join ami.nodefirmwareversion nfw on n.nodekey = nfw.nodekey
inner join ami.firmware fw on nfw.firmwarekey = fw.firmwarekey
where n.deviceclasskey in ('13', '11') AND fw.description = 'Register Firmware' AND n.serialnumber = '" . $sMeterNumber . "'
and epg.grouptype = 'C'
group by fw.VERSION, nfw.LASTUPDATED";

//STR_TO_DATE(`MoveInDate`,'%d.%m.%Y') MoveInDate

$sADCSConnIDw = oci_parse($sADCSConn, $ADCS_REGFW_Query);
oci_execute($sADCSConnIDw);
$ADCSrow3 = oci_fetch_array($sADCSConnIDw, OCI_ASSOC+OCI_RETURN_NULLS);
 
	If ($ADCSrow3['REGFW'] != "")
  {
	echo "<td>" . $ADCSrow3['REGFW'] . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  }
  
  	If ($ADCSrow3['FWLASTUPDATE'] != "")
  {
	echo "<td>" . $ADCSrow3['FWLASTUPDATE'] . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  }  
  
  
// CGR from here
/*$ADCS_CGR_Query = "select cgr.SERIALNUMBER as CGR_Serial, r.lastupdatetime as CGR_LASTUPDATE, r.RFLANLEVEL AS CGR_RFLANLEVEL, r.BESTFATHERMACADDRESS, R.COUNTREGISTRATIONSSENT, R.COUNTREGISTRATIONSFAILED, R.SYNCHFATHERAVERAGERSSI AS FATHERAVERAGERSSI
from ami.node n, ami.node cgr, AMI.RFLANLINKINFO r
where n.relaykey = cgr.nodekey and n.nodekey = r.nodekey and n.deviceclasskey in ('13', '11')
and  n.SERIALNUMBER = '" . $sMeterNumber . "'";

//STR_TO_DATE(`MoveInDate`,'%d.%m.%Y') MoveInDate

$sADCSConnIDCGR = oci_parse($sADCSConn, $ADCS_CGR_Query);
oci_execute($sADCSConnIDCGR);
$ADCSrowCGR = oci_fetch_array($sADCSConnIDCGR, OCI_ASSOC+OCI_RETURN_NULLS);
 */
	If ($ADCSrow['CGR_SERIAL'] != "")
  {
	echo "<td>" . $ADCSrow['CGR_SERIAL'] . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  }
  
  	If ($ADCSrow['CGR_LASTUPDATE'] != "")
  {
	echo "<td>" . $ADCSrow['CGR_LASTUPDATE'] . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  }  
  
    If ($ADCSrow['CGR_RFLANLEVEL'] != "")
  {
	echo "<td>" . $ADCSrow['CGR_RFLANLEVEL'] . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  }
  
    If ($ADCSrow['BESTFATHERMACADDRESS'] != "")
  {
	echo "<td>" . dechex($ADCSrow['BESTFATHERMACADDRESS']) . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  }
  
   If ($ADCSrow['FATHERAVERAGERSSI'] != "")
  {
	echo "<td>" . $ADCSrow['FATHERAVERAGERSSI'] . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  }
  
  If ($ADCSrow['COUNTREGISTRATIONSSENT'] != "")
  {
	echo "<td>" . $ADCSrow['COUNTREGISTRATIONSSENT'] . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  }
  
  If ($ADCSrow['COUNTREGISTRATIONSFAILED'] != "")
  {
	echo "<td>" . $ADCSrow['COUNTREGISTRATIONSFAILED'] . "</td>";
  }
  else
  {
	echo "<td>" . $sNull . "</td>";
  }
  
 //oracle from here

//$sMeterNumber = (int) $row['SerialNumber'];

$MDMS_Query = "SELECT F.EFFECTIVEENDDATE, M.METERNUMBER,M.METERID, MAX(M.CTRATIO),M.PTRATIO, M.ENDPOINTID, F.SERVICEPOINTID, F.METERPROGRAMID, F.MTRSTATUS FROM ITRONEE.METER M
INNER JOIN ITRONEE.FLATCONFIGPHYSICAL F ON M.METERID=F.METERID WHERE M.METERNUMBER = '$sMeterNumber' AND F.EFFECTIVEENDDATE = '2031-01-01 00:00:00.000'
GROUP BY F.EFFECTIVEENDDATE, M.METERNUMBER, M.METERID, M.PTRATIO, M.ENDPOINTID, F.SERVICEPOINTID, F.METERPROGRAMID, F.MTRSTATUS 
ORDER BY M.METERNUMBER";
	
// AND ITRONEE.FLATCONFIGPHYSICAL.METERPROGRAMID = '" . $ADCSrowZ['NAME'] . "'
//echo $MDMS_Query;

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

$_SESSION['sRowExists'] = TRUE;


//$sRowExixts == TRUE;
  }

  //}
echo "</table>";
  echo "</tr></tbody>";
  
    //echo $_SESSION['sRowExixts'];
  //echo $row['Material'];
  //if (!$row OR !$_SESSION['sRowExixts'])
  //if (($row['Material'] == "") AND $_SESSION['sRowExixts'])
  
  //echo $_SESSION['sRowExists'];
  session_start();
  if (!$_SESSION['sRowExists'])
{//echo "<font size=\"30\" color=\"red\">No data for the selection criteria. Please try again with different inputs</font>";
echo "No data found for the search parameters. Please click on Home and revise your search";}


//echo $pagination;
// Free the statement identifier when closing the connection
oci_free_statement($sMDMSConnID);
oci_close($sMDMSConn);

// Free the statement identifier when closing the connection
oci_free_statement($sADCSConnID);
oci_free_statement($sADCSConnIDs);
oci_free_statement($sADCSConnIDt);
oci_free_statement($sADCSConnIDu);
oci_free_statement($sADCSConnIDv);
oci_free_statement($sADCSConnIDw);
oci_free_statement($sADCSConnIDCGR);
oci_close($sADCSConn);

// Free the resources associated with the result set
// This is done automatically at the end of the script
//mysql_free_result($sDMTQueryResult);
//mysql_close($sDMTConn);

//echo debug_view ( $sCurrentPage );
//echo debug_view ( $sNull );
//echo debug_view ( $sDMTQueryResult);
//echo "hi";
?>

<?=$sPagination?>

<?php

}
else
{
session_destroy();
session_unset();
echo "Invalid session or Session timed out. Please login to access this page";
?>

<br /><br />
<A HREF = main_login.php>Login page</A>

<?php
}
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js" type="text/javascript"></script>

<script src="../js/jquery.stickytableheaders.js" type="text/javascript"></script> 

<script src="jquery.stickytableheaders.js" type="text/javascript"></script> 

<script type="text/javascript">

		$(document).ready(function () {
			$("table").stickyTableHeaders();
		});
	
</script>