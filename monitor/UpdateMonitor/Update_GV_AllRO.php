#!/usr/bin/php

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

ClearGAll($conM);
UpdateG($con,$conM);
UpdateGAll($con,$conM);
UpdateGE($con,$conM);
UpdateGEAll($con,$conM);

function ClearGAll($con){
	$mysql = "
		UPDATE Monitor_GV_AllRO SET
			AllHotspotG='0',
			MonitorIPG='0',
			MonitorFaultG='0',
			AllHotspotGE='0',
			MonitorIPGE='0',
			MonitorFaultGE='0'
	";	
	mysql_query($sql,$con);
}

function ClearG($con){
	
}

function UpdateGAll($con,$conM){
	print "==== UpdateGVAll ====\n;";
	$sql = "
	SELECT
		RO_ID,
		COUNT(*) as c
	FROM 
		`All_hotspot_Type`
	WHERE 
		`Hotspot_Type` = 'GV'
	GROUP BY
		RO_ID
		";
	$result = mysql_query($sql,$con);
	while($row = mysql_fetch_assoc($result)){
		$sql = "
			INSERT INTO Monitor_GV_AllRO (
				Monitor_GV_AllRO.RO,
				Monitor_GV_AllRO.AllHotspotG,
				Case_Active,
				DateUpdate
			)VALUES(
				'".$row['RO_ID']."',
				'".$row['c']."',
				'UpdateGVAll',
				NOW()
			) ON DUPLICATE KEY UPDATE
				Monitor_GV_AllRO.AllHotspotG='".$row['c']."',
				Case_Active='UpdateGVAll',
				DateUpdate=NOW()
		";
		//print $sql;
		mysql_query($sql,$conM);
	}	
}

function UpdateGEAll($con,$conM){
	print "==== UpdateGVAll ====\n;";
	$sql = "
	SELECT
		RO_ID,
		COUNT(*) as c
	FROM 
		`All_hotspot_Type`
	WHERE 
		`Hotspot_Type` = 'GVE'
	GROUP BY
		RO_ID
		";
	$result = mysql_query($sql,$con);
	while($row = mysql_fetch_assoc($result)){
		$sql = "
			INSERT INTO Monitor_GV_AllRO (
				Monitor_GV_AllRO.RO,
				Monitor_GV_AllRO.AllHotspotGE,
				Case_Active,
				DateUpdate
			)VALUES(
				'".$row['RO_ID']."',
				'".$row['c']."',
				'UpdateGVEAll',
				NOW()
			) ON DUPLICATE KEY UPDATE
				Monitor_GV_AllRO.AllHotspotGE='".$row['c']."',
				Case_Active='UpdateGVEAll',
				DateUpdate=NOW()
		";
		//print $sql;
		mysql_query($sql,$conM);
	}	
}

function UpdateG($con,$conM){
	print "==== UpdateGV ====\n;";
	$sql = "
	SELECT
		Area as RO,
		COUNT(*) as Count,
		SUM(if(LENGTH(IPAddressManage)>3,1,0)) as Monitor,
		SUM(if(NodeDown!=0,1,0)) as fault
	FROM
		HotSpot_GV
	GROUP BY
		Area";
	$result = mysql_query($sql,$con);
	while($row = mysql_fetch_assoc($result)){
		$sql = "
		INSERT INTO Monitor_GV_AllRO (
			Monitor_GV_AllRO.RO,
			Monitor_GV_AllRO.MonitorIPG,
			Monitor_GV_AllRO.MonitorFaultG,
			Case_Active,
			DateUpdate
		)VALUES(
			'".$row['RO']."',
			'".$row['Monitor']."',
			'".$row['fault']."',
			'InsertMonitorIPG',
			NOW()
		)
		 ON DUPLICATE KEY UPDATE
			Monitor_GV_AllRO.MonitorIPG='".$row['Monitor']."',
			Monitor_GV_AllRO.MonitorFaultG='".$row['fault']."',
			Monitor_GV_AllRO.Case_Active='UpdateMonitorIPG',
			DateUpdate=NOW()
		";
		//print $sql;
		mysql_query($sql,$conM);
	}
}

function UpdateGE($con,$conM){
	print "==== UpdateGVE ====\n";
	$sql = "
	SELECT
		Area as RO,
		COUNT(*) as Count,
		SUM(if(LENGTH(IPAddressManage)>3,1,0)) as Monitor,
		SUM(if(NodeDown!=0,1,0)) as fault
	FROM
		HotSpot_GVE
	GROUP BY
		Area";
	$result = mysql_query($sql,$con);
	while($row = mysql_fetch_assoc($result)){
		$sql = "
		INSERT INTO Monitor_GV_AllRO (
			Monitor_GV_AllRO.RO,
			Monitor_GV_AllRO.MonitorIPGE,
			Monitor_GV_AllRO.MonitorFaultGE,
			Case_Active,
			DateUpdate
		)VALUES(
			'".$row['RO']."',
			'".$row['Monitor']."',
			'".$row['fault']."',
			'InsertMonitorIPGE',
			NOW()
		)
		 ON DUPLICATE KEY UPDATE
			Monitor_GV_AllRO.MonitorIPGE='".$row['Monitor']."',
			Monitor_GV_AllRO.MonitorFaultGE='".$row['fault']."',
			Monitor_GV_AllRO.Case_Active='UpdateMonitorIPGE',
			DateUpdate=NOW()
		";
		//print $sql;exit();
		mysql_query($sql,$conM);
	}
}

?>