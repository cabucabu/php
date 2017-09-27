<?php 

exec("/usr/bin/timeout 5 /bin/ping {$argv[2]} -c2",$Array,$value);

$ping = ($value==0)?"PingSuccess":"Fail";

if($ping=="Fail"){
	$connection = @fsockopen($argv[2], 80, $errno, $errstr, 2);
	$ststus = is_resource($connection)? "Port80Open" : $ping ;
	if(is_resource($connection))fclose($connection);
}else
	$ststus = $ping;


//exit();

switch($argv[4]){
	case 'C' : exec("echo $argv[3] ".date("Y-m-d H:i:s")." $argv[1] $argv[2] $ststus >> /var/www/chatchai/Log/PingC.txt");break;
	case 'NV' : exec("echo $argv[3] ".date("Y-m-d H:i:s")." $argv[1] $argv[2] $ststus >> /var/www/chatchai/Log/PingNV.txt");break;
	case 'NVE' : exec("echo $argv[3] ".date("Y-m-d H:i:s")." $argv[1] $argv[2] $ststus >> /var/www/chatchai/Log/PingNVE.txt");break;
	case 'GV' : exec("echo $argv[3] ".date("Y-m-d H:i:s")." $argv[1] $argv[2] $ststus >> /var/www/chatchai/Log/PingGV.txt");break;
	case 'GVE' : exec("echo $argv[3] ".date("Y-m-d H:i:s")." $argv[1] $argv[2] $ststus >> /var/www/chatchai/Log/PingGVE.txt");break;
}