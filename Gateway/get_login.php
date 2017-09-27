<?php
ini_set("max_execution_time", 4);
ini_set('display_errors', 0);

header('Content-type: application/json; charset=utf-8');
header('Pragma: public');
header('Cache-control: no-cache');

require('lib/mikrotik/routeros_api.class.php');





$usrWifi = trim($_REQUEST["tbb_user"]);
$ipGw = '10.30.66.44';

$usrGw = 'acumenapigwapp';
$pwdGw = '1234';
$portGw = '2500';


if($ipGw == ''){
	
	$rspData['code'] = '601';
	$rspData['description'] = 'Invalid Parameter: IP is null.';
	echo json_encode($rspData);	exit();
}



$usrWifi = str_replace(array("-"), ":", $usrWifi);
$port = $portGw;
$timeout = 4;
$delay = 0;

$API = new routeros_api();
$API->port = $port;
$API->timeout = $timeout;
$API->delay = $delay;
//$API->debug = true;

if($API->connect($ipGw, $usrGw, $pwdGw)) {

	$BRIDGEINFO = $API->comm('/ip/hotspot/active/print',
					array(
						//".proplist" => ".id",
						"?user" =>'nooanti'
				  ));
	print_r($BRIDGEINFO);			  
				  
				  
	/*	
	if(count($BRIDGEINFO[0]) != 0){
		
		$API->comm('/ip/hotspot/active/remove',
			array(
            	".id"=>$BRIDGEINFO[0]['.id'],
        ));
		
		$API->disconnect();
	
		$rspData['code'] = '200';
		$rspData['description'] = 'Success.';
		$rspData['rspPara'] = $BRIDGEINFO[0];
		echo json_encode($rspData);	exit();
	
	}else{
		
		$API->disconnect();
		
		$rspData['code'] = '713';
		$rspData['description'] = 'WiFi account is ready used.';
		echo json_encode($rspData);	exit();
	}*/
			
}else{
	
	$rspData['code'] = '500';
	$rspData['description'] = 'Can not connect to gateway.';
	echo json_encode($rspData);	exit();
}

?>
