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

UpdateAllHotspotGE($con,$conM);
UpdateAllHotspotG($con,$conM);
UpdateHotspotG($con,$conM);
UpdateHotspotGE($con,$conM);

function UpdateHotspotGE($con,$conM){
	$sql = "
	SELECT
		HotSpot_GE_NEW.Area as RO,
		COUNT(*) as Count,
		SUM(if(HotSpot_GE_NEW.NodeDown>0,1,0)) as fault
	FROM
		HotSpot_GE_NEW
	GROUP BY
		Area
	";
	$result = mysql_query($sql,$con);
	while($row = mysql_fetch_assoc($result)){
		$sql = "
		INSERT INTO Monitor_G_AllRO (
			Monitor_G_AllRO.RO,
			Monitor_G_AllRO.ActiveIPGE,
			Monitor_G_AllRO.MonitorFaultGE,
			Case_Active,
			DateUpdate
		)VALUES(
			'".$row['RO']."',
			'".$row['Count']."',
			'".$row['fault']."',
			'InsertActiveIPGE',
			NOW()
		)
		 ON DUPLICATE KEY UPDATE
			Monitor_G_AllRO.ActiveIPGE='".$row['Count']."',
			Monitor_G_AllRO.MonitorFaultGE='".$row['fault']."',
			Monitor_G_AllRO.Case_Active='UpdateActiveIPGE',
			DateUpdate=NOW()
		";
		mysql_query($sql,$conM);
	}
}

function UpdateHotspotG($con,$conM){
	$sql ="
	SELECT
		HotSpot_G_NEW.Area AS RO,
		COUNT(*) AS Count,
		HotSpot_G_NEW.Province AS`จังหวัดที่ตั้ง`,
		sum(if(NodeDown>24,1,0)) AS `Fault`,
		sum(if(ISP='3BB',1,0)) AS `3BB`,
		sum(if(ISP!='3BB',1,0)) AS `OTHER`,
		0 as `Sum จำนวนลูกค้าใช้งาน`,
		0 as `Count จำนวน Hotspot ที่มีssการใช้งาน`
	FROM
		HotSpot_G_NEW
	GROUP BY
		Area
	ORDER BY
		Area*1
	
	";
	$result = mysql_query($sql,$con);
	while($row = mysql_fetch_assoc($result)){
		$sql = "
		INSERT INTO Monitor_G_AllRO (
			Monitor_G_AllRO.RO,
			Monitor_G_AllRO.ActiveIPG,
			Monitor_G_AllRO.ActiveIPG_3BB,
			Monitor_G_AllRO.ActiveIPG_Other,
			Monitor_G_AllRO.MonitorFaultG,
			Case_Active,
			DateUpdate
		)VALUES(
			'".$row['RO']."',
			'".$row['Count']."',
			'".$row['3BB']."',
			'".$row['OTHER']."',
			'".$row['Fault']."',
			'InsertActiveIPG',
			NOW()
		)
		 ON DUPLICATE KEY UPDATE
			Monitor_G_AllRO.ActiveIPG='".$row['Count']."',
			Monitor_G_AllRO.ActiveIPG_3BB='".$row['3BB']."',
			Monitor_G_AllRO.ActiveIPG_Other='".$row['OTHER']."',
			Monitor_G_AllRO.MonitorFaultG='".$row['Fault']."',
			Monitor_G_AllRO.Case_Active='UpdateActiveIPG',
			DateUpdate=NOW()
		";
		mysql_query($sql,$conM);
	}
}


function UpdateAllHotspotGE($con,$conM){
	$sql_ge="SELECT
		sum(if(Hotspot_Type='GE',Count_AP,''))as GE,
		RO_ID
	FROM
		All_hotspot_Type
	GROUP BY
		RO_ID";
	$rsGe = mysql_query($sql_ge,$con);
	while($row = mysql_fetch_assoc($rsGe)){
		$sql = "
		INSERT INTO Monitor_G_AllRO (
			Monitor_G_AllRO.RO,
			Monitor_G_AllRO.AllHotspotGE,
			Case_Active,
			DateUpdate
		)VALUES(
			'".$row['RO_ID']."',
			'".$row['GE']."',
			'InsertAllHotspotGE',
			NOW()
		)
		 ON DUPLICATE KEY UPDATE
			Monitor_G_AllRO.AllHotspotGE='".$row['GE']."',
			Case_Active='UpdateAllHotspotGE',
			DateUpdate=NOW()
		";
		mysql_query($sql,$conM);
	}
}

function UpdateAllHotspotG($con,$conM){
	$sql_ge="
	SELECT
		sum(if(Hotspot_Type='G',Count_AP,''))as G,
		RO_ID
	FROM
		All_hotspot_Type
	GROUP BY
		RO_ID
	";
	$rsGe = mysql_query($sql_ge,$con);
	while($row = mysql_fetch_assoc($rsGe)){
		$sql = "
		INSERT INTO Monitor_G_AllRO (
			Monitor_G_AllRO.RO,
			Monitor_G_AllRO.AllHotspotG,
			Case_Active,
			DateUpdate
		)VALUES(
			'".$row['RO_ID']."',
			'".$row['G']."',
			'InsertAllHotspotG',
			NOW()
		)
		 ON DUPLICATE KEY UPDATE
			Monitor_G_AllRO.AllHotspotG='".$row['G']."',
			Case_Active='UpdateAllHotspotG',
			DateUpdate=NOW()
		";
		mysql_query($sql,$conM);
		print $sql;
	}
}




?>