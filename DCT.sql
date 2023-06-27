select n.deviceclasskey, dc.description, n.serialnumber, n.electronicserialnumber, n.registered, n.isregistrationprocesscomplete, epg.name, gm.state
from ami.node n 
inner join ami.groupmap gm on n.nodekey = gm.nodekey 
inner join ami.endpointgroup epg on gm.endpointgroupkey = epg.endpointgroupkey 
inner join ami.deviceclass dc on dc.deviceclasskey = n.deviceclasskey 
where n.deviceclasskey in ('13', '11') 
and epg.grouptype = 'C'
group by n.deviceclasskey, dc.description, n.serialnumber, n.electronicserialnumber, n.registered, n.isregistrationprocesscomplete, epg.name, gm.state
;


select n.deviceclasskey, dc.description, n.serialnumber, n.electronicserialnumber, n.registered, n.isregistrationprocesscomplete, epg.name, gm.state
from ami.node n inner join ami.groupmap gm on n.nodekey = gm.nodekey inner join ami.endpointgroup epg on gm.endpointgroupkey = epg.endpointgroupkey inner join ami.deviceclass dc on dc.deviceclasskey = n.deviceclasskey 
where n.deviceclasskey in ('13', '11') and epg.grouptype = 'A'
group by n.deviceclasskey, dc.description, n.serialnumber, n.electronicserialnumber, n.registered, n.isregistrationprocesscomplete, epg.name, gm.state


select dc.description, n.serialnumber, n.electronicserialnumber, n.registered, n.isregistrationprocesscomplete, epg.name, gm.state
from ami.node n 
inner join ami.groupmap gm on n.nodekey = gm.nodekey 
inner join ami.endpointgroup epg on gm.endpointgroupkey = epg.endpointgroupkey 
inner join ami.deviceclass dc on dc.deviceclasskey = n.deviceclasskey 
where n.deviceclasskey in ('13', '11') AND n.serialnumber = '" . $sMeterNumber . "'
and epg.grouptype = 'C'
group by n.deviceclasskey, dc.description, n.serialnumber, n.electronicserialnumber, n.registered, n.isregistrationprocesscomplete, epg.name, gm.state
;


SELECT *
FROM `sap_qa1`
ORDER BY (
CAST( 'SerialNumber' AS BINARY )
) ASC

SELECT * FROM `sap_qa1` ORDER BY CAST('SerialNumber' AS SIGNED) ASC