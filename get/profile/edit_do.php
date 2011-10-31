<?php $pfad_to_home = "../../"; 

include($pfad_to_home . "in/db.php");
include($pfad_to_home . "get/session.php");

$jump_to = "edit.php?uid=$uid&warnung=4";

//read old pass and email
$abfrage = "SELECT pass, email FROM trydsh_users WHERE id = $uid";
$ergebnis = pg_query($abfrage);
while($zeile = pg_fetch_object($ergebnis))
	{
	$db_pass = $zeile->pass;
	$db_mail = $zeile->email;
	}

//PASSWORD
if(strlen($_POST['p_alt']) > 0)
	{
	$p_alt = md5($_POST['p_alt']);
	$p_neu = md5($_POST['p_neu']);
	$p_neu2 = md5($_POST['p_neu2']);
		
	//cancel if...
	if($p_alt != $db_pass)
		$jump_to = "edit.php?uid=$uid&warnung=1";
	elseif($p_neu != $p_neu2)
		$jump_to = "edit.php?uid=$uid&warnung=2";
	else
		{	
		$update = "UPDATE trydsh_users SET pass = '$p_neu' WHERE id = '$uid'";
		$ergebnis = pg_query($update);
		
		$jump_to = "edit.php?uid=$uid&warnung=0";
		}
	}
	
//MAIL
//check if mail is valid
$mail = mresp('mail');
if(strlen($mail) > 0 and $db_mail != $mail)
	{
	$wert = preg_match('~[a-z0-9\-_]?[a-z0-9.\-_]+[a-z0-9\-_]?@[a-z0-9.-]+\.[a-z]{2,}~i', $mail);
	if($wert != 1)
		$jump_to = "edit.php?uid=$uid&warnung=3";
	else
		{	
		$update = "UPDATE trydsh_users SET email = '$mail' WHERE id = '$uid'";
		$ergebnis = pg_query($update);
		
		$jump_to = "edit.php?uid=$uid&warnung=0";
		}
	
	}
	
stirb($jump_to);
?>