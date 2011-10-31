<?php $pfad_to_home = "../../"; 

include($pfad_to_home . "in/db.php");
include($pfad_to_home . "get/session.php");

$rid = mres('rid');
$modus = mres('modus');

//DELETE TABLE
if($modus == "name")
	{
	$name = urldecode(mres('rid'));
	$erg = pg_query("DELETE FROM trydsh_history WHERE user_id = $uid AND name = '$name'");
	}
elseif(is_numeric($rid))
	{
	$erg = pg_query("DELETE FROM trydsh_history WHERE user_id = $uid AND id = '$rid'");	
	}
	
	
stirb("view.php?uid=$uid");
?>