<?php 
//print_r($argv); => Array([LinkPart],[Type],[Mac],[IP]) format 1/2/2559

$mac = $argv[2];
$ip = $argv[3];
$data = file_get_contents("http://www.macvendorlookup.com/api/pipe/$mac");

if(strstr(strtolower($data),"mikrotik"))
	$model = "MTK";
elseif(strstr(strtolower($data),"cisco"))
	$model = "linksys";
elseif(strstr(strtolower($data),"belkin"))
	$model = "linksys";
else
	$model = "";

switch($argv[1]){
	case 'GV' : exec("echo ".date("Y-m-d H:i:s")."^$mac^$ip^$model >> /var/www/chatchai/Log/ModelGV.txt");break;
	case 'Gx' : exec("echo ".date("Y-m-d H:i:s")."^$mac^$ip^$model >> /var/www/chatchai/Log/ModelGx.txt");break;
}		

?>