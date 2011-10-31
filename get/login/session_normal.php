<?php 
$TIME_LIMIT = time() - 3600*24*100;
session_start();

if(isset($_COOKIE['name']) and isset($_COOKIE['ident']))
	{
	
	//Einloggen durch führen
	if($_SESSION['in'] != 2)
		{
		$potentielle_id = $_COOKIE['name'];
		$ident_123 = $_COOKIE['ident'];
		
		$uid = -1;
		if($potentielle_id)
			{
			//ID finden
			$abfrage = "SELECT user_id, ident FROM trydsh_users_login WHERE user_id = '$potentielle_id' AND dat >= '$TIME_LIMIT'";
			$ergebnis = pg_query($abfrage);
			while($zeile = pg_fetch_object($ergebnis))
				{
				if($zeile->ident == $ident_123)
					{
					$uid = $zeile->user_id;
					break;
					}
				}
			}
			
		if ($uid == -1 or $ident_123 == "")
			{
			session_destroy();
			}
		else
			{
			include($pfad_to_home . "get/login/make_session.php");
			}	
		}
	}
else
	if($_SESSION['in'] != 2)	
		session_destroy();
?>