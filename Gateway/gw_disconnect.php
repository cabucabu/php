<?php
ini_set("max_execution_time", 4);
ini_set('display_errors', 0);

header('Content-type: application/json; charset=utf-8');
header('Pragma: public');
header('Cache-control: no-cache');

require('lib/mikrotik/routeros_api.class.php');





$usrWifi = trim($_REQUEST["tbb_user"]);
$ipGw = (trim($_REQUEST["gw_ip"]) == '') ? '' : trim($_REQUEST["gw_ip"]);

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
						"?user" => $usrWifi
				  ));
				  
	$HOSTINFO = $API->comm('/ip/hotspot/host/print', array("?address" => $BRIDGEINFO[0]['address']));
	$LEASEINFO = $API->comm('/ip/dhcp-server/lease/print', array("?address" => $BRIDGEINFO[0]['address']));
		
	if(count($BRIDGEINFO[0]) != 0){
		
		$upTime = $BRIDGEINFO[0]['uptime'] !='' ? $BRIDGEINFO[0]['uptime'] : '0s';
		$toTime = preg_split("/(d|h|m|s)/", strtolower($upTime));
		
		unset($toTime[count($toTime)-1]);
		$arrCount = count($toTime);
		
		switch($arrCount){
			
			case 4 :
				$intDay = $toTime[0];	$intHour = $toTime[1];	$intMin = $toTime[2];	$intSec = $toTime[3];
				break;
			
			case 3 :
				$intHour = $toTime[0];	$intMin = $toTime[1];	$intSec = $toTime[2];
				break;
				
			case 2 :
				$intMin = $toTime[0];	$intSec = $toTime[1];
				break;
				
			case 1 :
				$intSec = $toTime[0];
				break;
		}
		
		$upTimeSec = ($intDay * 86400) + ($intHour * 60* 60) + ($intMin * 60) + $intSec;
		
		if($upTimeSec > 15){
			
			//$API->comm('/ip/hotspot/active/remove', array(".id" => $BRIDGEINFO[0]['.id']));
			//$API->comm('/ip/dhcp-server/lease/remove', array(".id" => $LEASEINFO[0]['.id']));
			$API->comm('/ip/hotspot/host/remove',  array(".id" => $HOSTINFO[0]['.id']));
		}
		
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
	}
			
}else{
	
	$rspData['code'] = '500';
	$rspData['description'] = 'Can not connect to gateway.';
	echo json_encode($rspData);	exit();
}

?>
