<?php 
include "/var/www/database.inc.php";
//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

$sql = "
SELECT
	HotSpot_NV.IPAddressManage,
	HotSpot_NV.RemoteName,
	HotSpot_NV.Account
FROM
	HotSpot_NV
WHERE
	LENGTH(HotSpot_NV.IPAddressManage)<3
";
$result = mysql_query($sql,$con);
$count = 0;
while($row = mysql_fetch_assoc($result)){
	print $count++;
	$ip = GetIP($row['Account']);
	mysql_query("UPDATE HotSpot_NV SET IPAddressManage = '$ip' WHERE Account='".$row['Account']."'",$con);
	print "\n";
}

function GetIP($username){
	exec("/usr/bin/php /var/www/chatchai/acumen_ws/testcallws1.php $username",$Array,$value);
	$data = explode("|",trim($Array[2]));
	//$data = explode("|",trim($data[6]));
	//print_r($data);
	return trim($data[4]);
}



?>