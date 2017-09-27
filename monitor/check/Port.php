<?php 

// value have set = $argv = Array("0"=>"ActionFrom","1"=>"remote","2"=>"ip","3"=>"type");

$connection = @fsockopen($argv[2], 800, $errno, $errstr, 2);
$ststus800 = is_resource($connection)? "800Open" : "800Close" ;
if(is_resource($connection))fclose($connection);


$connection = @fsockopen($argv[2], 8291, $errno, $errstr, 2);
$ststus8291 = is_resource($connection)? "8291Open" : "8291Close" ;
if(is_resource($connection))fclose($connection);

$connection = @fsockopen($argv[2], 80, $errno, $errstr, 2);
$ststus80 = is_resource($connection)? "80Open" : "80Close" ;
if(is_resource($connection))fclose($connection);



switch($argv[3]){
	case 'Cgw' : exec("echo $argv[3] ".date("Y-m-d H:i:s")." $argv[1] $argv[2] $ststus80 $ststus800 $ststus8291 >> /var/www/chatchai/Log/PortCgw.txt");break;
	case 'NV' : exec("echo $argv[3] ".date("Y-m-d H:i:s")." $argv[1] $argv[2] $ststus >> /var/www/chatchai/Log/PingNV.txt");break;
	case 'NVE' : exec("echo $argv[3] ".date("Y-m-d H:i:s")." $argv[1] $argv[2] $ststus >> /var/www/chatchai/Log/PingNVE.txt");break;
	case 'GV' : exec("echo $argv[3] ".date("Y-m-d H:i:s")." $argv[1] $argv[2] $ststus >> /var/www/chatchai/Log/PingGV.txt");break;
	case 'GVE' : exec("echo $argv[3] ".date("Y-m-d H:i:s")." $argv[1] $argv[2] $ststus >> /var/www/chatchai/Log/PingGVE.txt");break;
}
?>