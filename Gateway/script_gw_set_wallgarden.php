<?php

ini_set("max_execution_time", 10);
ini_set('mysql.connect_timeout', 5);
ini_set('display_errors', 0);



require_once("lib/mikrotik/routeros_api.class.php");


/*$ipGw = '10.30.66.4';
*/
$remote = 'SCPN';
$ipGw = $_REQUEST['ip_vpn'];
//$remote = $_REQUEST['remote'];

$usrGw = 'acumenapigwapp';
$pwdGw = '1234';
$portGw = '2500';


$port = $portGw;
$timeout = 3;
$delay = 0;

$API = new routeros_api();
$API->port = $port;
$API->timeout = $timeout;
$API->delay = $delay;
//$API->debug = true;

$arrWallSet = array
	(
	array('dst-host' => '*.3bb.co.th', 'dst-port' => ''),
	array('dst-host' => '*.3bbwifi.com', 'dst-port' => ''),
	array('dst-host' => '*.mycyberpoint.com', 'dst-port' => ''),
	
	array('dst-host' => '*.paysbuy.com', 'dst-port' => ''),
	array('dst-host' => 'secure.2c2p.com', 'dst-port' => ''),
	array('dst-host' => 'nsips.scb.co.th', 'dst-port' => ''),
	array('dst-host' => 'alerts.conduit-services.com', 'dst-port' => ''),
	array('dst-host' => '*.cimbclicks.in.th', 'dst-port' => ''),
	
	array('dst-host' => '*.secureverification.apac.citibank.com', 'dst-port' => '', 'comment' => 'CITIBANK VISA'),
	array('dst-host' => 'ipay.bangkokbank.com', 'dst-port' => '', 'comment' => 'BBL VISA'),
	array('dst-host' => 'rt*.kasikornbank.com', 'dst-port' => '', 'comment' => 'KBANK VISA'),
	array('dst-host' => 'secure4.arcot.com', 'dst-port' => '', 'comment' => 'BAY VISA'),
	array('dst-host' => 'acs.ktc.co.th', 'dst-port' => '', 'comment' => 'KTC MASTER CARD'),
	array('dst-host' => 'vbv.scb.co.th', 'dst-port' => '', 'comment' => 'SCB VISA'),
	array('dst-host' => 'cardsecurity.standardchartered.com', 'dst-port' => '', 'comment' => 'Standard MASTER CARD'),
	array('dst-host' => 'acs.tmbbank.com', 'dst-port' => '', 'comment' => 'TMB VISA'),
	array('dst-host' => 'cap.attempts.securecode.com', 'dst-port' => '', 'comment' => 'UOB MASTER CARD')
	

	);
	
include_once('inc_gw_set_wallgarden.php');

if($API->connect($ipGw, $usrGw, $pwdGw)) {

	$BRIDGEINFO = $API->comm('/ip/hotspot/walled-garden/print', array());
	
	//print_r($BRIDGEINFO);
	foreach($BRIDGEINFO as $arrWallGet){
		
		$id = $arrWallGet['.id'];
		
		if (!preg_match('/3bb|cyberpoint|paysbuy|2c2p|scb|conduit-services|cimbclicks|citibank|bangkokbank|kasikornbank|secure4.arcot|ktc|standardchartered|tmbbank|securecode'.$strMatch.'/i', $arrWallGet['dst-host']) && trim($arrWallGet['dst-host']) != ''){
      				
						
			array_push($arrWallSet, array('dst-host' => $arrWallGet['dst-host'], 'dst-port' => $arrWallGet['dst-port']));
			//print_r($arrWallGet);
		}
				
		$API->comm('/ip/hotspot/walled-garden/remove', array('.id' => $id));
	}
	
	foreach($arrWallSet as $arrWallto){
		
		$API->comm('/ip/hotspot/walled-garden/add', $arrWallto);
	}
	
	//print_r($arrWallSet);
	$API->disconnect();
	echo 'Success.';
	
}else{
	
	echo 'Connection Fail.';	
	
}

?>