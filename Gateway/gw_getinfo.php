<?php
ini_set("max_execution_time", 10);
ini_set('display_errors', 0);

header('Content-type: application/json; charset=utf-8');
header('Pragma: public');
header('Cache-control: no-cache');

require('lib/mikrotik/routeros_api.class.php');


//$ipGw = '10.30.66.44';
$ipGw = $_REQUEST['ip'];

$usrGw = 'acumenapigwapp';
$pwdGw = '1234';
$portGw = '2500';

if($ipGw == ''){	echo 'Invalid Parameter: IP is null.';	}


$port = $portGw;
$timeout = 8;
$delay = 1;

$API = new routeros_api();
$API->port = $port;
$API->timeout = $timeout;
$API->delay = $delay;
//$API->debug = true;


#columl
#Identity, Interface eth1, Interface eth3, server{sername:disabled}

if($API->connect($ipGw, $usrGw, $pwdGw)) {
	
	echo 'Connected,';

	#Get Identity
	$arrIdentity = $API->comm('/system/identity/print', array());
	//print_r($arrIdentity);
	echo $arrIdentity[0]['name'] .',';
	

	#Get Interface
	$arrIdentity = $API->comm('/interface/print', array('?name'=>'ether1'));
	//print_r($arrIdentity);
	echo $arrIdentity[0]['mac-address'] .',';
	
	$arrIdentity = $API->comm('/interface/print', array('?name'=>'ether3'));
	//print_r($arrIdentity);
	echo $arrIdentity[0]['mac-address'] .',';
	
	#Get Server
	$arrIdentity = $API->comm('/ip/hotspot/print', array());
	echo '{';
	
	end($arrIdentity);
	$endKey = key($arrIdentity);
	foreach($arrIdentity as $key=>$getServer){
		
		$disable = '';
		$disable = $getServer['disabled'] == 'false' ? 'Enable' : 'Disable';
		echo $getServer['name'] .':'. $disable;
		if($key != $endKey){echo '|';}
	}
	echo '}';
	//print_r($arrIdentity);


	
	//echo 'Done.';
	$API->disconnect();
		
}else{
	
	echo 'Not Connect,';
}

?>
