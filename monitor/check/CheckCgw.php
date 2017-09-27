#!/usr/bin/php
<?php
#include USER and PASS from main account config
require_once("/var/www/thewa/typeN/pgbrowser.php");
require_once("/var/www/thewa/typeN/phpuri.php");
include "/var/www/database.inc.php";

exec("rm /var/www/chatchai/Log/ISPCgw.txt");
exec("rm /var/www/chatchai/Log/PortCgw.txt");
$sql = "
SELECT
	Remote_name,
	IP_ADDRESS,
	IP_VPN
FROM
	All_New_ACU_C_Remote
";
$result = mysql_query($sql,$con);
$count=0;
while($row = mysql_fetch_assoc($result)){
	$remoteName = str_replace(" ","^",$row['Remote_name']);
	$ip= $row['IP_VPN'];
	proc_close(proc_open ("/usr/bin/php /var/www/chatchai/Program/check/ISP.php $remoteName $ip &", array(), $foo));
	proc_close(proc_open ("/usr/bin/php /var/www/chatchai/Program/check/Port.php $remoteName $ip Cgw &", array(), $foo));
	// http://www.macvendorlookup.com/api/pipe/C8-B3-73-11-39-6B
	usleep(10000);
	print $count++."\n";
}
sleep(10);
UpdateStatusPort_UpdateModel($con);
print "\n";
exit();
function UpdateStatusPort_UpdateModel($con){
	exec("cat /var/www/chatchai/Log/PortCgw.txt",$Array,$value);
	$modelMTK = Array(); $_80Open = Array();$_800Open = Array();$_8291Open = Array();
	foreach($Array as $datas){
		$data = explode(" ",$datas);
		$remote = str_replace("^"," ",$data[3]);
		if($data[6]=="800Open" || $data[7]=="8291Open")array_push($modelMTK,$remote);
		if($data[5]=="80Open")array_push($_80Open,$remote);
		if($data[6]=="800Open")array_push($_800Open,$remote);
		if($data[7]=="8291Open")array_push($_8291Open,$remote);
	}
	
	$f=0;$valueModel='('; //========== update model
	foreach($modelMTK as $data){$valueModel .=($f==0)? "'$data'" : ",'$data'" ;$f++;}$valueModel.=')';
	$f=0;$valuePort80='('; //========== update model
	foreach($_80Open as $data){$valuePort80 .=($f==0)? "'$data'" : ",'$data'" ;$f++;}$valuePort80.=')';
	$f=0;$valuePort800='('; //========== update model
	foreach($_800Open as $data){$valuePort800 .=($f==0)? "'$data'" : ",'$data'" ;$f++;}$valuePort800.=')';
	$f=0;$valuePort8291='('; //========== update model
	foreach($_8291Open as $data){$valuePort8291 .=($f==0)? "'$data'" : ",'$data'" ;$f++;}$valuePort8291.=')';
	
	mysql_query("UPDATE All_New_ACU_C_Remote set Port80='Open' WHERE Remote_name IN $valuePort80",$con);
	mysql_query("UPDATE All_New_ACU_C_Remote set Port800='Open' WHERE Remote_name IN $valuePort800",$con);
	mysql_query("UPDATE All_New_ACU_C_Remote set Port8291='Open' WHERE Remote_name IN $valuePort8291",$con);
	mysql_query("UPDATE All_New_ACU_C_Remote set Model='MTK' WHERE Remote_name IN $valueModel",$con);
	mysql_query("UPDATE All_New_ACU_C_Remote set Port80='Close' WHERE Remote_name NOT IN $valuePort80",$con);
	mysql_query("UPDATE All_New_ACU_C_Remote set Port800='Close' WHERE Remote_name NOT IN $valuePort800",$con);
	mysql_query("UPDATE All_New_ACU_C_Remote set Port8291='Close' WHERE Remote_name NOT IN $valuePort8291",$con);
	
}