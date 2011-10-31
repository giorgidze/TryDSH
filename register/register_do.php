<?php $pfad_to_home = "../"; 

include($pfad_to_home . "in/db.php");
include($pfad_to_home . "in/menu.php");

$zeitjetzt = time();

//Prüfungen
$werte = array("prename", "name", "username", "mail1", "mail2", "pass1", "pass2");

//Zurück string
foreach($werte as $element)
	if($element != "pass1" and $element != "pass2")
		$backstring .= "&" . $element . "=" . $_POST[$element];


//Prüfen ob nicht leer
foreach($werte as $key => $element)
	{
	$$element = mresp($element);
	
	if($$element == "")
		stirb("index.php?warnung=1&" . $backstring);
	}


//Pürfen ob Email gültig
$wert = preg_match('~[a-z0-9\-_]?[a-z0-9.\-_]+[a-z0-9\-_]?@[a-z0-9.-]+\.[a-z]{2,}~i', $mail1);
if($wert != 1)
	stirb("index.php?warnung=4" . $backstring);
	
if($mail1 != $mail2)
	stirb("index.php?warnung=2" . $backstring);

//Prüfen ob passwörter gleich	
if($pass1 != $pass2)
	stirb("index.php?warnung=3" . $backstring);	
	
//Prüfen ob E-Mail bereits verwendet wird
$abfrage = "SELECT id FROM trydsh_users WHERE email = '$mail1'";
$ergebnis = pg_query($abfrage);
while($zeile = pg_fetch_object($ergebnis))
	stirb("index.php?warnung=5" . $backstring);

//Prüfen ob Username bereits verwendet wird
$abfrage = "SELECT id FROM trydsh_users WHERE username = '$username'";
$ergebnis = pg_query($abfrage);
while($zeile = pg_fetch_object($ergebnis))
	stirb("index.php?warnung=6" . $backstring);

$insert = "INSERT INTO trydsh_users (prename, name, username, email, pass) VALUES ('$prename', '$name', '$username', '$mail1', '" . md5($pass1) . "') RETURNING id";
$ergebnis = pg_query($insert);
$insert_row = pg_fetch_row($ergebnis);
$insert_id = $insert_row[0];

//CREATE USERS OWN DB
$erg = pg_query("CREATE DATABASE " . db_of_user($insert_id));

stirb("../?info=1");
?>