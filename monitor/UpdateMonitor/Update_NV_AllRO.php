<?php  
include "/var/www/database.inc.php";
$conM = mysql_connect("10.30.1.83","root","Acumen2011");
if (!$conM){
	die('ติดต่อฐานข้อมูลไม่ได้: ' . mysql_error());
}else
	echo "";

mysql_select_db("Monitor", $conM);
$sql="set names tis620";
$result=mysql_query($sql,$conM);


UpdateN($con,$conM);
UpdateNAll($con,$conM);
UpdateNE($con,$conM);
UpdateNEAll($con,$conM);

function UpdateNAll($con,$conM){
	print "==== UpdateNVAll ====\n;";
	$sql = "
	SELECT
		RO_ID,
		COUNT(*) as c
	FROM 
		`All_hotspot_Type`
	WHERE 
		`Hotspot_Type` = 'NV'
	GROUP BY
		RO_ID
		";
	$result = mysql_query($sql,$con);
	while($row = mysql_fetch_assoc($result)){
		$sql = "
			INSERT INTO Monitor_NV_AllRO (
				Monitor_NV_AllRO.RO,
				Monitor_NV_AllRO.AllHotspotN,
				Case_Active,
				DateUpdate
			)VALUES(
				'".$row['RO_ID']."',
				'".$row['c']."',
				'UpdateGVAll',
				NOW()
			) ON DUPLICATE KEY UPDATE
				Monitor_NV_AllRO.AllHotspotN='".$row['c']."',
				Case_Active='UpdateNVAll',
				DateUpdate=NOW()
		";
		//print $sql;
		mysql_query($sql,$conM);
	}	
}

function UpdateNEAll($con,$conM){
	print "==== UpdateNVEAll ====\n;";
	$sql = "
	SELECT
		RO_ID,
		COUNT(*) as c
	FROM 
		`All_hotspot_Type`
	WHERE 
		`Hotspot_Type` = 'NVE'
	GROUP BY
		RO_ID
		";
	$result = mysql_query($sql,$con);
	while($row = mysql_fetch_assoc($result)){
		$sql = "
			INSERT INTO Monitor_NV_AllRO (
				Monitor_NV_AllRO.RO,
				Monitor_NV_AllRO.AllHotspotNE,
				Case_Active,
				DateUpdate
			)VALUES(
				'".$row['RO_ID']."',
				'".$row['c']."',
				'UpdateGVAll',
				NOW()
			) ON DUPLICATE KEY UPDATE
				Monitor_NV_AllRO.AllHotspotNE='".$row['c']."',
				Case_Active='UpdateNVEAll',
				DateUpdate=NOW()
		";
		//print $sql;
		mysql_query($sql,$conM);
	}	
}

function UpdateN($con,$conM){
	print "==== UpdateN ====\n";
	$sql = "
	SELECT
		Area as RO,
		SUM(if(LENGTH(IPAddressManage)>3,1,0)) as Monitor,
		SUM(if(NodeDown>1,1,0)) as fault
	FROM
		HotSpot_NV
	GROUP BY
		Area";
	$result = mysql_query($sql,$con);
	while($row = mysql_fetch_assoc($result)){
		$sql = "
		INSERT INTO Monitor_NV_AllRO (
			Monitor_NV_AllRO.RO,
			Monitor_NV_AllRO.MonitorIPN,
			Monitor_NV_AllRO.MonitorFaultN,
			Case_Active,
			DateUpdate
		)VALUES(
			'".$row['RO']."',
			'".$row['Monitor']."',
			'".$row['fault']."',
			'InsertMonitorIPN',
			NOW()
		)
		 ON DUPLICATE KEY UPDATE
			Monitor_NV_AllRO.MonitorIPN='".$row['Monitor']."',
			Monitor_NV_AllRO.MonitorFaultN='".$row['fault']."',
			Monitor_NV_AllRO.Case_Active='UpdateMonitorIPN',
			DateUpdate=NOW()
		";
		//print $sql;
		mysql_query($sql,$conM);
	}
}

function UpdateNE($con,$conM){
	
	print "==== UpdateNE ====\n";
	$sql = "
	SELECT
		Area as RO,
		SUM(if(LENGTH(IPAddressManage)>3,1,0)) as Monitor,
		SUM(if(NodeDown>1,1,0)) as fault
	FROM
		HotSpot_NVE
	GROUP BY
		Area";
	$result = mysql_query($sql,$con);
	while($row = mysql_fetch_assoc($result)){
		$sql = "
		INSERT INTO Monitor_NV_AllRO (
			Monitor_NV_AllRO.RO,
			Monitor_NV_AllRO.MonitorIPNE,
			Monitor_NV_AllRO.MonitorFaultNE,
			Case_Active,
			DateUpdate
		)VALUES(
			'".$row['RO']."',
			'".$row['Monitor']."',
			'".$row['fault']."',
			'InsertMonitorIPNE',
			NOW()
		)
		 ON DUPLICATE KEY UPDATE
			Monitor_NV_AllRO.MonitorIPNE='".$row['Monitor']."',
			Monitor_NV_AllRO.MonitorFaultNE='".$row['fault']."',
			Monitor_NV_AllRO.Case_Active='UpdateMonitorIPNE',
			DateUpdate=NOW()
		";
		//print $sql;
		mysql_query($sql,$conM);
	}
}

?>