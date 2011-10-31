<?php $pfad_to_home = "../../"; 

include($pfad_to_home . "in/db.php");
include($pfad_to_home . "get/session.php");

$tablename = mresp('name');
$rid = mres('rid');
$ctype = mresp('ctype');
$cname = mresp('cname');
$ctype2 = mresp('ctype2');
$cname2 = mresp('cname2');
$cnameold2 = mresp('cnameold2');
$del2 = mresp('del2');
$index2 = mresp('index2');
$cols = mresp('cols');
$colsadd = (mresp('colsadd') == 1);

$index_name = mresp('index_name');
$index_nameold = mresp('index_nameold');
$index_del = mresp('index_del');

if(strlen($tablename) < 1)
	stirb("edit.php?uid=$uid&rid=$rid&cols=" . count($ctype) . "&warnung=2");

$link2 = connect_to_user_db($uid);
$values = array("bigint" => "bigint", "double precision" => "double precision", "text" => "text");


//safe error messages
$errors = 0;
$error_msg = array();

function safe_errors($erg, &$errors, &$error_msg)
	{
	if(!$erg)
		{
		$errors++;
		$error_msg[] = pg_last_error();	
		}
	}

//CREATE TABLE
if($rid == -1)
	{
	if(count_table_of_user() > 1000)
		stirb("view.php?uid=$uid&warnung=3");
			
	@$erg = pg_query("CREATE TABLE \"$tablename\" ()");
	if(!$erg)
		stirb("edit.php?uid=$uid&rid=$rid&cols=" . count($ctype) . "&warnung=3");
	}
	
//RENAME TABLE
if($rid != -1 and $rid != $tablename)
	{
	@$erg = pg_query("ALTER TABLE \"$rid\" RENAME TO \"$tablename\"");

	if(!$erg)
		stirb("edit.php?uid=$uid&rid=$rid&warnung=3");	
	else
		$rid = $tablename;
	}
	
//CREATE COLUMNS
if(count($_POST['ctype']) > 0)
	foreach($ctype as $key => $type)
		{
		$name = $cname[$key];
		if($name == "")
			continue;
		
		if(!isset($values[$type]))
			continue;
		else
			$realtype = $values[$type];		
				
			
		@$erg = pg_query("ALTER TABLE \"$tablename\" ADD COLUMN \"$name\" $realtype NOT NULL");	
		safe_errors($erg, $errors, $error_msg);
		}	

//EDIT/DEL COLUMNS		
if(count($_POST['ctype2']) > 0)
	foreach($ctype2 as $key => $type)
		{
		$name = $cname2[$key];
		
		//DEL COLUMN
		if($name == "" or $del2[$key] == "on")
			{
			@$erg = pg_query("ALTER TABLE \"$tablename\" DROP COLUMN \"$name\" RESTRICT");
			continue;	
			}
		
		//RENAME COLUMN
		if($name != $cnameold2[$key])
			{
			@$erg = pg_query("ALTER TABLE \"$tablename\" RENAME COLUMN \"" . $cnameold2[$key] . "\" TO \"$name\"");	
			safe_errors($erg, $errors, $error_msg);
			}
			
		//CHANGE TYPE
		if(!isset($values[$type]))
			continue;
		else
			$realtype = $values[$type];	
		
		pg_query("ALTER TABLE \"$tablename\" ALTER COLUMN \"" . $name . "\" SET DEFAULT 0");
		
		@$erg = pg_query("ALTER TABLE \"$tablename\" ALTER COLUMN \"" . $name . "\" TYPE $realtype");	
		safe_errors($erg, $errors, $error_msg);
			
		}

//Maybe add index
if(count($_POST['index2']) > 0)
	{
	foreach($index2 as $key => $element)
		{
		if($element == "on")
			$index_cols[] = "\"$cname2[$key]\"";
		}
	
	$col_str = implode(", ", $index_cols);
	
	//NAME
	$abfrage = "SELECT indexname FROM pg_catalog.pg_indexes WHERE indexname LIKE 'newindex_%' ORDER BY indexname DESC LIMIT 1";
	$ergebnis = pg_query($abfrage);
	while($zeile = pg_fetch_object($ergebnis))
		{
		preg_match('~newindex_([0-9]*)~', $zeile->indexname, $tmp);
		$new_index_name = "newindex_" . ($tmp[1] + 1);
		}
	$new_index_name = (!isset($new_index_name)) ? "newindex_0" : $new_index_name;
	
	@$erg = pg_query("CREATE INDEX \"$new_index_name\" ON \"$tablename\" ($col_str)");	
	safe_errors($erg, $errors, $error_msg);	
	}

//EDIT/CHANGE INDEXES
if(count($_POST['index_name']) > 0)
	foreach($index_name as $key => $element)
		{
		$name = $index_nameold[$key];
		
		//DEL INDEX
		if($element == "" or $index_del[$key] == "on")
			{
			@$erg = pg_query("DROP INDEX \"$name\"");
			continue;	
			}
		
		//RENAME INDEX
		if($name != $element)
			{
			@$erg = pg_query("ALTER INDEX \"$name\" RENAME TO \"" . $element . "\"");	
			safe_errors($erg, $errors, $error_msg);
			}
		}
		
$cols_str = ($colsadd) ? "&cols=$cols" : "";
if($errors > 0)
	stirb("edit.php?uid=$uid&rid=$tablename&warnung=4&err=" . urlencode($error_msg[0]) . $cols_str);
if($colsadd)
	stirb("edit.php?uid=$uid&rid=$tablename$cols_str");
	
stirb("edit.php?uid=$uid&rid=$tablename&warnung=0");
?>
