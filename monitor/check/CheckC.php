<?php 
$con = mysql_connect("10.30.1.83","root","Acumen2011");
if (!$con)
	die('Not connect Database: ' . mysql_error());
mysql_select_db("hotspot", $con);
$result=mysql_query("set names tis-620",$con);


exec("rm /var/www/chatchai/Log/PingC.txt",$Array,$value);

$sql = "SELECT * FROM HotSpot_Cyberpoint_NEW";
$result = mysql_query($sql,$con);
$count=1;

while ($row = mysql_fetch_assoc($result)) {
	print "\n$count" ;
	$row['ID_R_D'] = str_replace(" ","^",$row['ID_R_D']);
	usleep(10000);
	if(!strstr($row['IPAddressManage'],"10.10.") && !strstr($row['IPAddressManage'],"192.168.50"))
		CheckPing($row['IPAddressManage'],$row['ID_R_D'],$count);
		//CheckPort($row['IPAddressManage'],$row['ID_R_D'],$count);
	$count++;
}

UpdateC($con);

exit();

function CheckPort($ip,$key,$count){
	proc_close(proc_open ("/usr/bin/php /var/www/chatchai/Program/check/Port.php $key $ip $count C &", array(), $foo));
}

function UpdateC($con){
	exec("cat /var/www/chatchai/Log/PingC.txt",$Array,$value);
	$valueOk='(';$valueNoOk='(';$fOk=0;$fNoOk=0;
	foreach($Array as $array){
		$data = explode(" ",$array);
		$data['3'] = str_replace("^"," ",$data['3']);
		if($data['5']!="Fail"){
			$valueOk.=($fOk!=0)?',':"";
			$valueOk.="'".$data['3']."'";
			$fOk++;
		}else{
			$valueNoOk.=($fNoOk!=0)?',':"";
			$valueNoOk.="'".$data['3']."'";
			$fNoOk++;
		}
		
	}
	$valueOk.=')';$valueNoOk.=')';
	$sql1 = "
		UPDATE hotspot.HotSpot_Cyberpoint_NEW SET
			NodeDown='0',
			NodeDownCheckDate=NOW(),
			NodeDownDate=NOW()
		WHERE
			ID_R_D IN $valueOk
	";
	
	$sql2 = "
		UPDATE hotspot.HotSpot_Cyberpoint_NEW SET
			NodeDown=if(NodeDownDate is NULL,'2','1'),
			NodeDownCheckDate=NOW()
		WHERE
			ID_R_D IN $valueNoOk
		";
	print_r($sql1);
	//exit();
	mysql_query($sql1,$con);
	mysql_query($sql2,$con);
	mysql_query("UPDATE HotSpot_Cyberpoint_NEW SET IPAddressManage='Wait' WHERE IPAddressManage LIKE '10.10.%' OR IPAddressManage LIKE '192.168.50%'",$con);
	
	
	
}

function CheckPing($ip,$key,$count){
	proc_close(proc_open ("/usr/bin/php /var/www/chatchai/Program/check/Ping.php $key $ip $count C &", array(), $foo));
}
?>