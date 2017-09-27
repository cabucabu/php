
<?php 
include "/var/www/database.inc.php";

print "=== UpdateGVFormHBData ===\n"
UpdateGVFormHBData($con);
print "=== CheckModel ===\n"
CheckModel($con);
sleep(3);
print "=== UpdateData ===\n"
UpdateData($con);
exit();

function UpdateData($con){
	exec("cat /var/www/chatchai/Log/ModelGV.txt",$Array,$value);
	$valuelinksys='(';$valueMTK='(';$f=0;
	foreach($Array as $datas){
		$data = explode("^",$datas);
		switch($data[3]){
			case 'linksys' : $valuelinksys.= ($valuelinksys=='(')?"'".$data[1]."'":",'".$data[1]."'"; break;
			case 'MTK' : $valueMTK.= ($valueMTK=='(')?"'".$data[1]."'":",'".$data[1]."'";break;
			default : "";break;
		}
		print_r($data);
		$f++;
	}
	$valuelinksys.=')';$valueMTK.=')';
	mysql_query("UPDATE HotSpot_GV SET Model='linksys' WHERE Mac IN $valuelinksys",$con);
	mysql_query("UPDATE HotSpot_GV SET Model='MTK' WHERE Mac IN $valueMTK",$con);
	print "valuelinksys = ".$valuelinksys."\n";
	print "valueMTK = ".$valueMTK."\n";
}

function CheckModel($con){
	$sql = "SELECT Mac,IPAddressManage FROM HotSpot_GX ";
	$result = mysql_query($sql,$con);
	$count=0;
	exec("rm /var/www/chatchai/Log/ModelGV.txt",$Array,$value);
	while($row = mysql_fetch_assoc($result)){
		$row['Mac'] = trim($row['Mac']);
		$row['IPAddressManage'] = trim($row['IPAddressManage']);
		proc_close(proc_open ("/usr/bin/php /var/www/chatchai/Program/check/Model.php Gx {$row['Mac']} {$row['IPAddressManage']} &", array(), $foo));
		print $count." ".$row['Mac']."\n";
		$count++;
		usleep(1000);
	}
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
