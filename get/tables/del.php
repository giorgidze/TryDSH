<?php $pfad_to_home = "../../"; 

include($pfad_to_home . "in/db.php");
include($pfad_to_home . "get/session.php");

$rid = mres('rid');
$modus = mres('modus');

$link2 = connect_to_user_db($uid);

//DELETE TABLE
if($rid != "" and $modus == "drop")
	{
	@$erg = pg_query("DROP TABLE \"$rid\"");
	}
	
//EMPTY TABLE
if($rid != "" and $modus == "empty")
	{
	@$erg = pg_query("TRUNCATE TABLE \"$rid\"");
	}
	
stirb("view.php?uid=$uid");
?>