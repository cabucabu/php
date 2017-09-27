<?php
ini_set("max_execution_time", 10);
ini_set('display_errors', 0);

header('Content-type: application/json; charset=utf-8');
header('Pragma: public');
header('Cache-control: no-cache');

require('lib/mikrotik/routeros_api.class.php');


//$ipGw = '10.30.112.212';
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

if($API->connect($ipGw, $usrGw, $pwdGw)) {

	#Edit Lease Time 2H
	$arrDhcp = $API->comm('/ip/dhcp-server/print', array('?disabled' => 'false'));
	foreach($arrDhcp as $dhcp){
		
		$API->comm('/ip/dhcp-server/set', array('.id' => $dhcp['.id'], 'lease-time'=> '2h', 'add-arp'=> 'true'));
	}


	#Edit address per MAC & Keepalive
	$arrHotspot = $API->comm('/ip/hotspot/print', array('?disabled' => 'false'));
	foreach($arrHotspot as $hotspot){
		
		$API->comm('/ip/hotspot/set', 
			array('.id' => $hotspot['.id'], 
			'idle-timeout'=> '1h', 
			'keepalive-timeout'=> 'none', 
			'addresses-per-mac'=>'1'
			)
		);
	}


	#Edit Login By Mac Cookie
	$arrPorfile = $API->comm('/ip/hotspot/profile/print', array());
	foreach($arrPorfile as $profile){
		
		$API->comm('/ip/hotspot/profile/set', 
			array('.id' => $profile['.id'], 
			'login-by'=> 'mac,http-pap,mac-cookie', 
			'mac-auth-password'=> 'acumen3bb'
			)
		);
	}


	#Edit user Profile GW
	$arrPorfile = $API->comm('/ip/hotspot/user/profile/print', array('?name' => 'default'));
	foreach($arrPorfile as $profile){
		
		$API->comm('/ip/hotspot/user/profile/set', 
			array('.id' => $profile['.id'], 
			'session-timeout'=> '1d', 
			'idle-timeout'=> '1h',
			'keepalive-timeout'=> 'none',
			'add-mac-cookie'=> 'true',
			'mac-cookie-timeout'=> '4d',
			'transparent-proxy'=> 'false',
			'open-status-page'=> 'always',
			'shared-users'=> '1'
			)
		);
	}


	#Edit user Profile GW
	$arrService = $API->comm('/ip/service/print', array());
	foreach($arrService as $service){
		
		if($service['name']=='api'){
			
			$API->comm('/ip/service/set', 
				array('.id' => $service['.id'], 'address'=>'10.30.1.0/24', 'port'=>'2500', 'disabled'=>'false')
			);
		}
				
		if($service['name']=='ssh'){
			
			$API->comm('/ip/service/set', 
				array('.id' => $service['.id'], 'port'=>'2499', 'disabled'=>'false')
			);
		}
		
		if($service['name']=='www'){
			
			$API->comm('/ip/service/set', 
				array('.id' => $service['.id'], 'port'=>'800', 'disabled'=>'false')
			);
		}
		
		if($service['name']=='winbox'){
			
			$API->comm('/ip/service/set', 
				array('.id' => $service['.id'], 'port'=>'8291', 'disabled'=>'false')
			);
		}
		
		if($service['name']=='api-ssl'){
			
			$API->comm('/ip/service/set', 
				array('.id' => $service['.id'], 'disabled'=>'true')
			);
		}
		
		if($service['name']=='telnet'){
			
			$API->comm('/ip/service/set', 
				array('.id' => $service['.id'], 'disabled'=>'true')
			);
		}
		
		if($service['name']=='ftp'){
			
			$API->comm('/ip/service/set', 
				array('.id' => $service['.id'], 'disabled'=>'true')
			);
		}
		
		if($service['name']=='www-ssl'){
			
			$API->comm('/ip/service/set', 
				array('.id' => $service['.id'], 'disabled'=>'true')
			);
		}

	}
	
	#Edit Firewall Filter
	$arrPorfile = $API->comm('/ip/firewall/filter/print', array('?chain'=>'pre-hs-input', '?disabled'=>'false'));
	foreach($arrPorfile as $profile){
		
		$API->comm('/ip/firewall/filter/set', 
			array('.id' => $profile['.id'], 
			'disabled'=> 'true'
			)
		);
	}
	
	
	echo 'Done.';
	$API->disconnect();
		
}else{
	
	echo 'Not Connect.';
}

?>
