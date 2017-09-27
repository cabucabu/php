<?php 
include "/var/www/database.inc.php";
$sql = "
SELECT
	Hotspot_ID_N,
	COUNT(Hotspot_ID_N) as c
FROM
	HotSpot_E_NEW
GROUP BY
	Hotspot_ID_N
";
$result = mysql_query($sql,$con);
$count=0;
while($row = mysql_fetch_assoc($result)){
	mysql_query("UPDATE HotSpot SET CountTypeE='".$row['c']."' WHERE Hotspot_ID = '".$row['Hotspot_ID_N']."'",$con);
	print $count++;
	print " ".$row['Hotspot_ID_N']."\n";
}

?>