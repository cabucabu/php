<?php 
include "/var/www/database.inc.php";
include("pagination.class.php");
$remote = (isset($_GET))?$_GET['remote']:"";
//$remote = "WI1VSS1-2 BKK";
$sql = "SELECT
		Dummy_Count,
		RO_ID,
		Province,
		Remote_name,
		IP_ADDRESS,
		IP_VPN,
		COLOR,
		Dummy1,
		ADSL_user1,
		ADSL_pwd1,
		Dummy2,
		ADSL_user2,
		ADSL_pwd2,
		Dummy3,
		ADSL_user3,
		ADSL_pwd3,
		Dummy4,
		ADSL_user4,
		ADSL_pwd4,
		Dummy5,
		ADSL_user5,
		ADSL_pwd5
	FROM
		All_New_ACU_C_Remote
	WHERE
		Remote_name='$remote'
	";

$result = mysql_query($sql,$con);
$row = mysql_fetch_assoc($result);

//print_r($row);
?>
<!DOCTYPE html>
<html>
<head>
<style>
table {
    border-collapse: collapse;
}

table, th, td {
    border: 1px solid black;
}
font {
    font-size: 14px;
}

</style>
</head>
<body>
<?php
print "<center>
	   <table width='50%'>";
print "<tr><th>Dummy</th><th>Username</th><th>Password</th></tr>";
print "<tr><td><font>".$row['Dummy1']."</fon></td><td><font>".$row['ADSL_user1']."</fon></td><td><font>".$row['ADSL_pwd1']."</fon></td></tr>";
if(strlen($row['Dummy2'])>5)
	print "<tr><td><font>".$row['Dummy2']."</fon></td><td><font>".$row['ADSL_user2']."</fon></td><td><font>".$row['ADSL_pwd2']."</fon></td></tr>";
if(strlen($row['Dummy3'])>5)
print "<tr><td><font>".$row['Dummy3']."</fon></td><td><font>".$row['ADSL_user3']."</fon></td><td><font>".$row['ADSL_pwd3']."</fon></td></tr>";
if(strlen($row['Dummy4'])>5)
print "<tr><td><font>".$row['Dummy4']."</fon></td><td><font>".$row['ADSL_user4']."</fon></td><td><font>".$row['ADSL_pwd4']."</fon></td></tr>";
if(strlen($row['Dummy5'])>5)
print "<tr><td><font>".$row['Dummy5']."</fon></td><td><font>".$row['ADSL_user5']."</fon></td><td><font>".$row['ADSL_pwd5']."</fon></td></tr>";
print "</table>";
?>

</body>
</html>