<?php 
include "/var/www/database.inc.php";

$mac = 'E48D8CA11446';
CheckModel($con);
UpdateGVFormHBData($con);

function CheckModel($con){
	$sql = "SELECT Mac,IPAddressManage FROM HotSpot_GV ";
	$result = mysql_query($sql,$con);
	while($row = mysql_fetch_assoc($result)){
		$mac = $row['Mac'];
		$data = file_get_contents("http://www.macvendorlookup.com/api/pipe/$mac");
		print_r($data);
		if(strstr(strtolower($data),"mikrotik"))
			print "\n{$row['IPAddressManage']} MTK\n";
		elseif(strstr(strtolower($data),"cisco"))
			print "\n{$row['IPAddressManage']} linksys\n";
		elseif(strstr(strtolower($data),"belkin"))
			print "\n{$row['IPAddressManage']} linksys\n";
		else
			print "\n{$row['IPAddressManage']} {$row['Mac']} \n\n Nospec\n================================================\n";
	}
	exit();
}

function UpdateGVFormHBData($con){
	$sql = "
	UPDATE
		HotSpot_GV as a,
		HB_DATA as b
	SET
		a.HB_Status=b.`Status`,
		a.HB_Date=b.UPDATETIME,
		a.IPVPN=b.IPVPN,
		a.Mac=b.Mac
	WHERE
		a.RemoteName=b.Remotename
	";
	mysql_query($sql,$con);
	print "\n";
}

?>