<?php
$con83 = mysql_connect("10.30.1.83","root","Acumen2011");
if (!$con83){
	die('Not Connect Database: ' . mysql_error());
}else
	echo "";

mysql_select_db("hotspot", $con83);
$sql="set names tis620";
$result=mysql_query($sql,$con83);

$conNoa = mysql_connect("10.10.19.62","hotnoa","hotnoa");
if (!$conNoa){
	die('Not Connect Database: ' . mysql_error());
}else
	echo "";
$sql="set names tis620";
$result=mysql_query($sql,$conNoa);

mysql_select_db("noa_3bb", $conNoa);
$value = getValueNOA($conNoa);
mysql_query("TRUNCATE TABLE DslamDetail_new",$con83);
Insert83($con83,$value);
print "\n";
exit();



function Insert83($con,$value){
	$sql = "
		INSERT DslamDetail_new (
			Province,
			Address,
			IP,
			DslamName,
			Location,
			DS_MODEL,
			DS_MODEL_FROM_NODE,
			Zone,
			Amphor,
			Tambol
		)Value
			$value
	";
	mysql_query($sql,$con);
	//print $sql ;
}

function getValueNOA($con){
	$sql = "SELECT * FROM noa_3bb.v_dslam";
	$result = mysql_query($sql,$con);
	$value = "";$f=0;
	while($row = mysql_fetch_assoc($result)){
		$row['provice'] = trim(str_replace('¨.',"",$row['provice']));
		$value .= ($f==0)?"(":",(";
		$value .= "'".$row['provice']."',";
		$value .= "'".$row['c_name']."',";
		$value .= "'".$row['ip']."',";
		$value .= "'".$row['name']."',";
		$value .= "'location',";
		$value .= "'".$row['d_version']."',";
		$value .= "'".$row['model']."',";
		$value .= "'".$row['provice']."',";
		$value .= "'".$row['Amphur']."',";
		$value .= "''";
		$value .= ')';
		//if($f==10)break;
		$f++;
	}
	return $value;
}


?>