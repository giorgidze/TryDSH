<?php 

define("MICROTIME", microtime(TRUE));
//Zeitzone
date_default_timezone_set('Europe/Berlin');
error_reporting(E_ALL ^ E_NOTICE);
setlocale(LC_ALL, "en_US");

define("DB_USERNAME", "trydsh");
define("DB_PASSWORD", "");

//Datenbank Verbindung
if(!isset($protect_key))
	{
	$protect_key = TRUE;	

	$linkID = pg_connect("host=localhost user=" . DB_USERNAME . " password=" . DB_PASSWORD . " dbname=trydsh connect_timeout=5");
	if (!$linkID)
		{
		die("No connection to database.");
		}
		
	function connect_to_user_db($uid)
		{
		if(!($username = name($uid)))
			return false;
			
		$db_name = db_of_user($uid);	

		$linkID = pg_connect("host=localhost user=" . DB_USERNAME . " password=" . DB_PASSWORD . " dbname=$db_name connect_timeout=5");
		if (!$linkID)
			die("No connection to database.");
		else
			{
			pg_query("SET lc_messages TO 'en_US.UTF-8'");
			return $linkID;
			}
		}
	
	//alias and array support mysql_real_escape_string
	function mres($text, $post = FALSE)
		{
		$var = ($post) ? $_POST[$text] : $_GET[$text];
		
		if($var == NULL and $var != "")
			return NULL;
		elseif(!is_array($var))
			return pg_escape_string($var);
		else
			return array_map("pg_escape_string", $var);
		}
		
	function mresp($text)
		{
		return mres($text, TRUE);
		}
		
	//Text ggf. kürzen
	function str_kuerzen($text, $l, $title = FALSE)
		{
		if(strlen($text) <= $l)
			return $text;
		else
			{
			if($title)
				return '<span title="' . $text . '">' . substr($text, 0, $l - 3) . '...</span>';
			else
				return substr($text, 0, $l - 3) . '...';
			}
		}
		
	//Header und Exit
	function stirb($url)
		{
		header("Location:$url");
		exit;
		}
	
	function name($uid)
		{
		$autor = FALSE;
		$abfrage = "SELECT username FROM trydsh_users WHERE id = $uid";
		$ergebnis = pg_query($abfrage);
		while($zeile = pg_fetch_object($ergebnis))
			$autor = $zeile->username;
		
		return $autor;
		}
		
	function db_of_user($uid = FALSE)
		{
		if(is_numeric($uid))
			return "db_" . strtolower(name($uid));
		else
			return "trydsh_nologin";
		}
	
	function username_id($username)
		{
		$uid = FALSE;
		$username = strtolower($username);
		$abfrage = "SELECT id FROM trydsh_users WHERE LOWER(username) = '$username'";
		$ergebnis = pg_query($abfrage);
		while($zeile = pg_fetch_object($ergebnis))
			$uid = $zeile->id;
			
		return $uid;
		}
		
	function datatype_select($name, $standard = NULL, $width = 150, $atr = "")
		{
		$ausgabe = '<select name="' . $name . '" size="1" style="width:' . $width . 'px;"' . $atr . '>';
		$types = array("bigint" => "INTEGER", "double precision" => "FLOATING POINT", "text" => "TEXT");
		
		foreach($types as $key => $element)
			{
			$selected = ($key == $standard) ? " selected=\"selected\"" : "";
			$ausgabe .= "<option value=\"$key\"$selected>$element</option>";
			}	
		
		$ausgabe .= "</select>";
		
		return $ausgabe;
		}
		
	function count_table_of_user()
		{
		//CONNECTION TO USER_DB should run
		$abfrage = "SELECT COUNT(*) AS n FROM information_schema.tables WHERE table_schema = 'public'";
		$ergebnis = pg_query($abfrage);
		while($zeile = pg_fetch_object($ergebnis))
			return $zeile->n;	
		}
		
	//today_yesterday
	function today_yesterday($zeit_unix, $vor_heute = "", $vor_sonst = "", $bold_heute = 0, $jahr_dran = NULL)
		{
		$bold_auf = ($bold_heute == 1) ? "<b>" : "";
		$bold_zu = ($bold_heute == 1) ? "</b>" : "";
		$zeit = getdate($zeit_unix);
		//TODAY
		$jetzt = getdate(time());
		if($zeit["mday"] == $jetzt["mday"] and $zeit["mon"] == $jetzt["mon"] and $zeit["year"] == $jetzt["year"])
			{
			return $vor_heute . $bold_auf . "Today" . $bold_zu;
			}
		//GESTERN
		$jetzt = getdate(time() - 3600*24);
		if($zeit["mday"] == $jetzt["mday"] and $zeit["mon"] == $jetzt["mon"] and $zeit["year"] == $jetzt["year"])
			{
			return $vor_heute . $bold_auf . "Yesterday" . $bold_zu;
			}
		//MORGEN
		$jetzt = getdate(time() + 3600*24);
		if($zeit["mday"] == $jetzt["mday"] and $zeit["mon"] == $jetzt["mon"] and $zeit["year"] == $jetzt["year"])
			{
			return $vor_heute . "Tomorrow";
			}
		//SONST
		$zeit["year"] = ($jahr_dran >= 1) ? $zeit["year"] : "";
		$zeit["year"] = ($jahr_dran == 2) ? substr($zeit["year"], 2) : $zeit["year"];
		return $vor_sonst . strftime("%Y-%m-%d", $zeit_unix);//"$zeit[year]-$zeit[mon]-$zeit[mday]"; 
		}
	
	//die if no cookies	
	function stirb_wenn_keine_cookies($check_cookie = "do", $check_value = 1)
		{
		global $pfad_to_home;
		if($_COOKIE[$check_cookie] != $check_value)
			strib($pfad_to_home . "?info=3");
		}
		
	//save program to history
	function save_program($program, $title, $uid)
		{
		//LIMITS!
		$abfrage = "SELECT COUNT(*) AS n FROM trydsh_history WHERE user_id = $uid AND name = '$title'";
		$ergebnis = pg_query($abfrage);
		while($zeile = pg_fetch_object($ergebnis))	
			$revisions = $zeile->n;
			
		//Maybe program limit reached	
		if($revisions == 0)	
			{
			$abfrage = "SELECT COUNT(name) AS n FROM trydsh_history WHERE user_id = $uid";
			$ergebnis = pg_query($abfrage);
			while($zeile = pg_fetch_object($ergebnis))	
				$programs = $zeile->n;
				
			if($programs >= 1000)
				{
				$abfrage = "SELECT name FROM trydsh_history WHERE user_id = $uid GROUP BY name ORDER BY MAX(dat) LIMIT 1";
				$ergebnis = pg_query($abfrage);
				while($zeile = pg_fetch_object($ergebnis))	
					$program_del_name = $zeile->name;
				
				$delete = "DELETE FROM trydsh_history WHERE user_id = $uid AND name = '$program_del_name'";	
				$erg = pg_query($delete);
				}
			}
		elseif($revisions >= 10)
			{
			$abfrage = "SELECT id FROM trydsh_history WHERE user_id = $uid AND name = '$title' ORDER BY dat LIMIT 1";
			$ergebnis = pg_query($abfrage);
			while($zeile = pg_fetch_object($ergebnis))	
				$revision_del_id = $zeile->id;
			
			$delete = "DELETE FROM trydsh_history WHERE user_id = $uid AND id = $revision_del_id";	
			$erg = pg_query($delete);
			}
			
		$insert = "INSERT INTO trydsh_history (user_id, name, program, dat) VALUES ($uid, '$title', '$program', NOW())";
		$erg = pg_query($insert);
		}
	
	//auto-login	
	include($pfad_to_home . "get/login/session_normal.php");
	
	//set a cookie, nessacary for die if no cookies
	if($_COOKIE["do"] != 1)
		setcookie("do", 1, time() + 3600, "/");


	//
		// Logged in?
		$LOG_IN = (isset($_SESSION['in']) and $_SESSION['in'] == 2 and isset($_SESSION['id']));
		$LOG_IN_text = ($LOG_IN) ? '<div style="position:absolute; bottom: 5px; right: 0px;" class="obenwhite">Eingeloggt als ' . name($_SESSION['id']) . '&nbsp;&nbsp;</div>' : '';
		
	//kick out if user is deleted
	if($LOG_IN and !name($_SESSION['id']))
		{
		session_destroy();
		stirb($pfad_to_home . "?info=5");
		}	
	}

?>
