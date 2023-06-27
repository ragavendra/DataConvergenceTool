<?php

//idle time out for DMT
$tIdleTimeSet=15 * 60;//after 15 * 60 seconds the user gets logged out

//server name
$sDMTServerName = "localhost";

//User name
$sDMTUserName = "testsmi";
//$sDMTUserName = "ragavendra";
$sDMTUserNameWrite = "root";

//password
$sDMTUserPass = "testsmi";
//$sDMTUserPass = "Christmas2012";
$sDMTUserPassWrite = "Christmas2012";

//User name
$sMDMSUserName = "rnagraj";

//password
$sMDMSUserPass = "Rnagraj_002";

//User name
$sADCSUserName = "rnagraj";

//password
$sADCSUserPass = "Rnagraj_002";

//db name
$sDMTDBName = "smi";

//MDMS conn str
$sMDMSConnStr = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=SMIMDMTST1)(PORT=1521))(CONNECT_DATA=(SID=SMIMDQA)))";
$sMDMSConnStrQA = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=SMIMDMTST1)(PORT=1521))(CONNECT_DATA=(SID=SMIMDQA)))";
$sMDMSConnStrQA2 = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=SMIMDMTST1)(PORT=1521))(CONNECT_DATA=(SID=SMIMDQA2)))";
$sMDMSConnStrSTAGE = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=smimdmstg1)(PORT=1521))(CONNECT_DATA=(SID=SMIMDSTG)))";

//ADCS conn string
$sADCSConnStr = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=EDMSMISBX1)(PORT=1521))(CONNECT_DATA=(SID=SMICESB1)))";
$sADCSConnStrQA = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=EDMSMISBX1)(PORT=1521))(CONNECT_DATA=(SID=SMICESB1)))";
$sADCSConnStrQA2 = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=EDMSMISBX1)(PORT=1521))(CONNECT_DATA=(SID=SMICEQA2)))";
$sADCSConnStrSTAGE = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=SMICESTG1)(PORT=1521))(CONNECT_DATA=(SID=SMICESTG)))";

//DMT query
//$sDMTQuery = "SELECT * FROM sap_qa1 WHERE SystemStatus = 'INST'";

//dummy meter#
$sMeterNumber = "";

//MDMS query
$MDMS_Query = "SELECT ITRONEE.METER.METERNUMBER, ITRONEE.METER.METERID, MAX(ITRONEE.METER.CTRATIO), ITRONEE.METER.PTRATIO, ITRONEE.METER.ENDPOINTID, ITRONEE.FLATCONFIGPHYSICAL.SERVICEPOINTID, ITRONEE.FLATCONFIGPHYSICAL.METERPROGRAMID, ITRONEE.FLATCONFIGPHYSICAL.MTRSTATUS 
FROM ITRONEE.METER
INNER JOIN ITRONEE.FLATCONFIGPHYSICAL ON ITRONEE.METER.METERID=ITRONEE.FLATCONFIGPHYSICAL.METERID WHERE ITRONEE.METER.METERNUMBER = '" . $sMeterNumber . "'
GROUP BY ITRONEE.METER.METERNUMBER, ITRONEE.METER.METERID, ITRONEE.METER.PTRATIO, ITRONEE.METER.ENDPOINTID, ITRONEE.FLATCONFIGPHYSICAL.SERVICEPOINTID, ITRONEE.FLATCONFIGPHYSICAL.METERPROGRAMID, ITRONEE.FLATCONFIGPHYSICAL.MTRSTATUS 
ORDER BY ITRONEE.METER.METERNUMBER";

//ADCS query
$ADCS_Query = "select dc.description, n.serialnumber, n.registered, epg.name, gm.state
from ami.node n 
inner join ami.groupmap gm on n.nodekey = gm.nodekey 
inner join ami.endpointgroup epg on gm.endpointgroupkey = epg.endpointgroupkey 
inner join ami.deviceclass dc on dc.deviceclasskey = n.deviceclasskey 
where n.deviceclasskey in ('13', '11') AND n.serialnumber = '" . $sMeterNumber . "'
and epg.grouptype = 'C'
group by n.deviceclasskey, dc.description, n.serialnumber, n.registered, epg.name, gm.state";

// app group from here
$ADCS_Group_Query = "select epg.name 
from ami.node n inner join ami.groupmap gm on n.nodekey = gm.nodekey inner join ami.endpointgroup epg on gm.endpointgroupkey = epg.endpointgroupkey inner join ami.deviceclass dc on dc.deviceclasskey = n.deviceclasskey 
where n.deviceclasskey in ('13', '11') and epg.grouptype = 'A' AND n.serialnumber = '" . $sMeterNumber . "'
group by epg.name";

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

// REGFW from here
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


?>