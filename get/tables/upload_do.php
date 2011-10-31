<?php $pfad_to_home = "../../"; 

include($pfad_to_home . "in/db.php");
include($pfad_to_home . "get/session.php");


$csv = $_FILES['csv'];
$thead = (mresp('thead') == "on");
$rid = mres('rid');

if($rid == "" or is_null($rid))
	stirb("../../?info=6");

if($csv['size'] == 0)
	stirb("upload.php?uid=$uid&rid=$rid&warnung=1");
if(preg_match("~.csv$~", $csv['name']) < 1)
	stirb("upload.php?uid=$uid&rid=$rid&warnung=2");

$fname = $csv['tmp_name'];
chmod($fname, 0755); //otherwise postgres cant read

$link2 = connect_to_user_db($uid);

$ignore_first_row = ($thead) ? " HEADER" : "";

$query = "COPY \"$rid\" FROM '$fname' WITH CSV$ignore_first_row";
$erg = @pg_query($query);

if(!$erg)
	{
	$query = "COPY \"$rid\" FROM '$fname' WITH DELIMITER ';'  CSV$ignore_first_row";
	$erg = @pg_query($query);
	}

if(!$erg)
	{
	$error = pg_last_error();
	stirb("upload.php?uid=$uid&rid=$rid&warnung=3&err=" . urlencode($error));	
	}
else
	stirb("view.php?uid=$uid");

?>
