<?php

$uid = $_GET['uid'];
$exit = "../../?info=5";

if (!isset($_SESSION["in"]) or $_SESSION["in"] != 2 or $_SESSION["id"] != $uid)
	{		
	header("Location:$exit");
	session_destroy();
	exit;
	}

/*$liste_rechte = array("r_main", "r_codes", "r_buchungen", "r_log", "r_user", "r_rechnungen", "r_kategorien", "r_licht", "r_set", "r_design");
$liste_pfade = array("plan/", "codes/", "buchungen/", "log/", "user/", "rechnungen/", "kategorien/", "licht/", "set/", "design/");

foreach($liste_pfade as $key => $element)
	{	
	if(strpos($_SERVER['PHP_SELF'], $element) > -1)
		{
		if (!is_numeric($_SESSION[XPR . $liste_rechte[$key]]) or $_SESSION[XPR . $liste_rechte[$key]] < 1) 
			{
			header("Location:$exit");
			session_destroy();
			exit;
			}
		}
	}*/
?>
