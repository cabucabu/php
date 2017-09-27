<?php 
require_once("/var/www/thewa/typeN/pgbrowser.php");
require_once("/var/www/thewa/typeN/phpuri.php");

$remoteName = str_replace("^"," ",$argv[1]);
$ip = $argv[2];

if(preg_match('/^100.126./', $ip)){
	exec("echo ".date("Y-m-d H:i:s")."^$remoteName^$ip^3BB >> /var/www/chatchai/Log/ISPCgw.txt");
	//print $remoteName." : ".$ip."	=> 	";	
}else if(strlen($ip)>3){
	//exec("curl 'http://wq.apnic.net/apnic-bin/whois.pl?searchtext=$IP' ", $output, $retval);
	
	$b = new PGBrowser();
	$RURL = "http://wq.apnic.net/apnic-bin/whois.pl?searchtext=$ip";
	$page = $b->get($RURL);
	$respData=$page->body;
	$SSID1_ENABLE = trim(substr($respData, strpos($respData, 'descr: '), 120));
	$SSID2_ENABLE = trim(substr($respData, strpos($respData, 'netname: '), 120));
	
	$rr = get_isp($SSID1_ENABLE);
	if($rr[2]=='NO OK'){
		$rr = get_isp($SSID2_ENABLE);
	}
		
	//print $remoteName." : ".$ip."	=> 	$rr[1] \n\n";	
	exec("echo ".date("Y-m-d H:i:s")."^$remoteName^$ip^$rr[1] >> /var/www/chatchai/Log/ISPCgw.txt");
}

function get_isp($text){
	if( strpos( $text, "TRUE") 
	|| strpos( $text, "True Internet") 
	|| strpos( $text, "True Broadband") 
	|| strpos( $text, "True internet") 
	|| strpos( $text, "True WiFi") 
	|| strpos( $text, "ASIANET")){
		$ans[1] = "TRUE";
		$ans[2] = 'OK';
		//print "TRUE \n";
	}
	else if( strpos( $text, "3BB") 
	|| strpos( $text, "Triple T")){
		$ans[1] = "3BB";
		$ans[2] = 'OK';
		//print "3BB \n";
	}
	else if( strpos( $text, "TOT" ) || strpos( $text, "tot" )){
		$ans[1] = "TOT";
		$ans[2] = 'OK';
		//print "TOT \n";
	}
	else if( strpos( $text, "CAT" )){
		$ans[1] = "CAT";
		$ans[2] = 'OK';
		//print "CAT \n";
	}
	else if( strpos( $text, "csloxinfo" )){
		$ans[1] = "csloxinfo";
		$ans[2] = 'OK';
		//print "csloxinfo \n";
	}else if( strpos( $text, " bigcamera" )){
		$ans[1] = "BIG CAMERA";
		$ans[2] = 'OK';
		//print "BIG CAMERA \n";
	}else if( strpos( $text, "MOE-NET" )){
		$ans[1] = "MOE-NET";
		$ans[2] = 'OK';
		//print "MOE-NET \n";
	}
	else{
		$ans[1] = "n/a";
		$ans[2] = 'NO OK';
		//print "Check Next Again \n";
	}
	return $ans;
}
?>