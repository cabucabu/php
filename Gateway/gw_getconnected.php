<?php
ini_set("max_execution_time", 10);
ini_set('display_errors', 0);

header('Content-type: application/json; charset=utf-8');
header('Pragma: public');
header('Cache-control: no-cache');

require('lib/mikrotik/routeros_api.class.php');

$ipGw = $_REQUEST['ip'];

$usrGw = 'acumenapigwapp';
$pwdGw = '1234';
$portGw = '2500';

if($ipGw == ''){	echo 'Invalid Parameter: IP is null.';	}


$port = $portGw;
$timeout = 4;
$delay = 1;

$API = new routeros_api();
$API->port = $port;
$API->timeout = $timeout;
$API->delay = $delay;
//$API->debug = true;


if($API->connect($ipGw, $usrGw, $pwdGw)) {
	
	echo 'Connected.';
	$API->disconnect();
	exit();
		
}else{
	
	echo 'Not Connect.';
	exit();
}

?>
