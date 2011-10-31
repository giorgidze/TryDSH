<?php $pfad_to_home = "../../";
include($pfad_to_home . "in/db.php");
$zeitjetzt = time();
session_start();

$password = $_POST['password'];
$username = mresp('email');
$dauerlogin = mresp('dauerlogin');
//ID finden
$uid = username_id($username);

if(!$uid or $password == "")
	$on = 1;
else
	{
	$abfrage = "SELECT pass FROM trydsh_users WHERE id = '$uid'";
	$ergebnis = pg_query($abfrage);
	while($zeile = pg_fetch_object($ergebnis))
		{
		$passdb = $zeile->pass;
		}
	
	if(md5($password) == $passdb)
		{
		include("make_session.php");
		
		//Cookie setzen
		if($dauerlogin == "on")
			{
			$ident = md5($passdb . $zeitjetzt);
			
			//IDENT TO DB
			$input = "INSERT INTO trydsh_users_login (dat, user_id, ident) VALUES ($zeitjetzt, $uid, '$ident')";
			$update = pg_query($input);
			
			setcookie("ident", $ident, time()+3600*24*100, "/");
			setcookie("name", $uid, time()+3600*24*100, "/");
			}
		else
			{
			setcookie("ident", '', 0, "/");
			setcookie("name", $email, 0, "/");
			}
			
		

		header("Location:../../"); 
		}
	else
		$on = 1;
	}

if($on == 1)
	header("Location:../../?info=2");

?>
