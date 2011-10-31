<?php $pfad_to_home = "";

//>>> 
// GO TO LINE 34 (+/-) FOR EDITING PADDING-CODE
// OR SEARCH IN FILE FOR: //>>> COMMAND-PADDING
//<<<

include($pfad_to_home . "in/db.php");

function create_file()
	{
	global $o;	
	global $dir;
	global $cmd_lines_before;
		
	// TMP-DIR & Filename
	$o['rand'] = time() . mt_rand();
	$datei = "tmp" . $o['rand'] . ".hs";
	$pfad = $dir . $datei;
	$pfad_cmd = $dir . $datei;
	
	$cmd = $_POST['command'];
	
	if($cmd == "")
		{
		$o['error'] = "Input is empty!";
		$o['status'] = 1;
		echo(json_encode($o));
		exit;	
		}
	
	$db_of_user = db_of_user($_SESSION['id']);
	
	//-----------------------------------------------------------
	//>>> COMMAND-PADDING
	// In PHP "." concat two strings. Write code-definitions
	// between "-quotes in following variables.
	//
	// The var $cmd_db will be concat if user is logged in
	// Use PHP var $db_of_user in code-string for the databasename.
	//
	// Escape "-qoutes with \ . If too expensive, replace "
	//   with ' in the following var definitions, escape '  
	//   with \ and remove command-symbol (//) of line after
	//   $cmd_db-STRING. Use symbol DB_OF_USER for users db.
	//-----------------------------------------------------------
	$cmd_lines_before = 25; // to correct line-statement in error message
	$cmd_top = '

{-# LANGUAGE TemplateHaskell, RebindableSyntax, MonadComprehensions, ViewPatterns, OverloadedStrings, FlexibleInstances, MultiParamTypeClasses #-}
	
module Main where
	
import qualified Prelude
import Database.DSH
import Database.DSH.Compiler

import Database.HDBC.PostgreSQL
	
import System.Environment (getArgs)

';
	
	$cmd_bottom = '

main :: IO ()
main = getConn Prelude.>>= \conn  ->
       getArgs Prelude.>>= \flags ->
       mainAux conn flags

mainAux :: Connection -> [String] -> IO ()
mainAux conn (s : _) | (Prelude.==) s "SQL"      = debugSQL     conn mainQuery Prelude.>>= putStrLn
                     | (Prelude.==) s "PLAN"     = debugPlan    conn mainQuery Prelude.>>= putStrLn
                     | (Prelude.==) s "PLAN_OPT" = debugPlanOpt conn mainQuery Prelude.>>= putStrLn
                     | (Prelude.==) s "CSV"      = fromQ        conn mainQuery Prelude.>>= csvExportStdout
                     | (Prelude.==) s "JSON"     = fromQ        conn (take 100 mainQuery) Prelude.>>= jsonExportStdout
                     | (Prelude.==) s "JSON_D"   = fromQ        conn mainQuery Prelude.>>= jsonExportStdout
                     | (Prelude.==) s "XHTML"    = fromQ        conn (take 100 mainQuery) Prelude.>>= xhtmlExportStdout 
mainAux conn _                                   = fromQ        conn (take 100 mainQuery) Prelude.>>= print
';
	
	$cmd_db = "

getConn :: IO Connection
getConn = (connectPostgreSQL \"user = '" . DB_USERNAME . "' password = '" . DB_PASSWORD . "' host = 'localhost' dbname = '$db_of_user'\")

$(generateDatabaseRecordInstances (connectPostgreSQL \"user = '" . DB_USERNAME . "' password = '" . DB_PASSWORD . "' host = 'localhost' dbname = '$db_of_user'\"))

$(generateTableDeclarations (connectPostgreSQL \"user = '" . DB_USERNAME . "' password = '" . DB_PASSWORD . "' host = 'localhost' dbname = '$db_of_user'\"))

";
	
	//-----------------------------------------------------------
	//<<<
	//-----------------------------------------------------------
	
	$cmd = $cmd_top . "\n" . $cmd_db . "\n" . $cmd . "\n" . "\n" . $cmd_bottom;
	
	//Write data to tmp file
	if(file_exists($pfad))
		unlink($pfad);	
		
	$fp = fopen($pfad, "w");
	
	if($fp and fwrite($fp, $cmd))
		fclose($fp);
	else
		die(json_encode(array("error" => "File not writeable, sorry!", "status" => 1)));
		
	return $pfad_cmd;
	}

$dir =  "/home/trydsh/tmp/"; //TEMP DIR

$flag = (isset($_GET['flag'])) ? mres('flag') : mresp('flag');
$rand = (isset($_GET['rand'])) ? mres('rand') : mresp('rand');

if($rand)
	$pfad_cmd = $dir . "tmp" . $rand . ".hs";
else
	$pfad_cmd = create_file();

if($flag == "csv")
	$flag_p = " CSV";
elseif($flag == "sql")
	$flag_p = " SQL";
elseif($flag == "plan_opt")
	$flag_p = " PLAN_OPT";
elseif($flag == "plan")
	$flag_p = " PLAN";
elseif($flag == "json")
	$flag_p = " JSON";
elseif($flag == "json_d")
	$flag_p = " JSON_D";
elseif($flag == "xhtml")
	$flag_p = " XHTML";
else
	$flag_p = "";


//Opnen GHC process
$descriptorspec = array(
   0 => array("pipe", "r"),  // STDIN ist eine Pipe, von der das Child liest
   1 => array("pipe", "w"),  // STDOUT ist eine Pipe, in die das Child schreibt
   2 => array("pipe", "w") // STDERR ist eine Pipe,
);

if(isset($pfad_cmd))
	$process = proc_open('/home/trydsh/opt/ghc-7.2.1/bin/runghc ' . $pfad_cmd . $flag_p, $descriptorspec, $pipes, NULL);

if(is_resource($process))
	{
	while(($add = stream_get_contents($pipes[1], 512)) != "")
		{
		if($flag == "sql" or $flag == "plan_opt" or $flag == "plan")		
			$raw_data .= $add;
		else
			//Direct Output
			$o['erg'] .= $add;
		}

	$o['error'] = stream_get_contents($pipes[2]);

	fclose($pipes[1]);
	fclose($pipes[2]);
	
	$o['status'] = proc_close($process);
	
	if($flag == "sql")
		{
		$tmp = preg_match('~<\!\[CDATA\[(.*)\]\]>~s', $raw_data, $matches);
		echo(nl2br(trim($matches[1]))); 
		}
	elseif($flag == "plan_opt" or $flag == "plan")
		{
		//CONNECT TO FERRY LEAKS
		$url = 'dbwiscam.informatik.uni-tuebingen.de';
		$folder = '/AlgebraEditor/algebraeditor/postinterface';
		
		$value = urlencode($raw_data);
		$req = "xmlplan=$value&redirect=false";
		
		//HEADERS
		$header .= "POST /AlgebraEditor/algebraeditor/postinterface HTTP/1.0\r\n"; 
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n"; 
		$header .= "Content-Length: " . strlen($req) . "\r\n\r\n"; 
		$fp = fsockopen ($url, 80, $errno, $errstr, 30);
		
		if (!$fp) 
			{ 
			// HTTP ERROR 
			} 
		else 
			{
			fputs ($fp, $header . $req); 
			while (!feof($fp))  
				$res .= fgets($fp, 1024);
				
			fclose ($fp);
			
			if(preg_match('~0::(.*)~s', $res, $matches) > 0) ///!!!!
				{ 
				header("Location:http://" . $url . $matches[1]);
				}
			else
				echo("There was an error, sorry!");
			}
		}
	elseif($flag == "csv" or $flag == "json_d")
		{
		//built header
		if($flag == "csv")
			{
			header("content-type: application/csv-tab-delimited-table");
			header("content-disposition: attachment; filename=\"export.csv\"");
			}
		elseif($flag == "json_d")
			{
                        header("content-type: application/octet-stream");
                        header("content-disposition: attachment; filename=\"export.txt\"");
                        }

		echo($raw_data);
		}
	}
else
	$o['error'] = "Compiler is missing, sorry";

if(!is_numeric($rand))
	{
	$o['flag'] = $flag;
	$o['echo'] = 1;
	
	//Correct error line-statement
	$o['error'] = preg_replace_callback('~\.hs:([0-9]{1,}):~', function ($match) {global $cmd_lines_before; return '.h*s:' . ($match[1] - $cmd_lines_before) . ':'; }, $o['error']);

	$o['error'] = str_replace("\n", "<br />", str_replace(" ", "&nbsp;", $o['error']));
	echo(json_encode($o));	
	}
else
	echo($o['erg']);

// Maype save program to history (if log_in and "normal mode")
if($LOG_IN and $_POST['save'] == 1 and !$direct_output)
	save_program(mresp('command'), mresp('savename'), $_SESSION['id']);

?>
