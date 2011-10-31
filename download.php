<?php $pfad_to_home = "";

include($pfad_to_home . "in/db.php");

function parse_haskell_to_csv($input, &$str_aktiv = FALSE, &$escaped_char = FALSE, &$new_line = TRUE, &$n = 0, &$m = 0)
	{
	if($input == "")
		return "";	
	$rek = str_split(trim($input));
	
	$o = "";
	
	foreach($rek as $key => $element)
		{
		// STRINGS	
		if($escaped_char)			// put this char to output
			{
			$o .= $element;
			$escaped_char = FALSE;	
			}
		elseif($element == "\\") 	// escape next char
			{
			$o .= "\\";
			$escaped_char = TRUE;
			}
		elseif($element == "\"")	// str starts or ends
			{
			$o .= "\"";
			$str_aktiv = !$str_aktiv;
			}
		elseif($str_aktiv)			// 
			$o .= $element;
			
		// LISTS		
		elseif($element == "[")
			{	
			$n++;
			if($n > 1)
				return ($o . "Parse error: nested lists");			
			}
		elseif($element == "]")
			{
			$n--;
			return $o;
			}
			
		// TUPELS
		elseif($element == "(")
			{
			$new_line = FALSE;		
			$m++;
			if($m > 1)
				return ($o . "Parse error: nested tupels");			
			}
		elseif($element == ")")
			{	
			$m--;
			$new_line = TRUE;
			}
		// NEW ELEMENT	
		elseif($element == "," and !$new_line)
			$o .= ",";	
			
		//NEW LINE
		elseif($element == "," and $new_line)
			$o .= "\n";	
		
		// INT/FLOATS
		else
			$o .= $element;	
		/*elseif(is_numeric($element) or $element == "." or $element == "-")
			{
			$el .= $element;
			$el_type = ((isset($el_type) and $el_type == "float") or $element == ".") ? "float" : "int";
			}	*/	
			
		}
	
	return $o;
	}

//echo(nl2br(parse_haskell_to_csv('[(5,3,4),(5,6,"abc', $str_aktiv, $escaped_char, $new_line, $n, $m)));
//echo(nl2br(parse_haskell_to_csv('7")]', $str_aktiv, $escaped_char, $new_line, $n, $m)));
//exit;

function add_to_tmp_array(&$tmp, $el, $el_type)
	{
	if($el_type == "int")
		$el = (int)$el;
	elseif($el_type == "float")
		$el = (float)$el;
	
	$tmp[] = $el;	
	}

// TMP-DIR & Filename
$dir = "/home/digel/trydsh/tmp/"; //TEMP DIR
$rand = $_GET['rand'];
$datei = "tmp" . $rand . ".hs";
$pfad = $dir . $datei;
$pfad_cmd = $dir . $datei;

//Write data to tmp file
if(!file_exists($pfad))
	die("Ungültier Aufruf");	

$descriptorspec = array(
   0 => array("pipe", "r"),  // STDIN ist eine Pipe, von der das Child liest
   1 => array("pipe", "w"),  // STDOUT ist eine Pipe, in die das Child schreibt
   2 => array("pipe", "w") // STDERR ist eine Pipe,
);

//HEADER SETZEN
header("content-type: application/csv-tab-delimited-table");
header("content-disposition: attachment; filename=\"export.csv\"");

$o['erg'] = "";
$process = proc_open('/home/trydsh/opt/ghc-7.0.4/bin/runghc ' . $pfad_cmd . '', $descriptorspec, $pipes, NULL);//, array("bypass_shell" => TRUE));

$str_aktiv = FALSE; $escaped_char = FALSE; $new_line = TRUE; $n = 0; $m = 0;

if(is_resource($process))
	{
	while(($add = stream_get_contents($pipes[1], 4096)) != "")
		{
		echo(parse_haskell_to_csv($add, $str_aktiv, $escaped_char, $new_line, $n, $m));
		}
	fclose($pipes[1]);
	
	$o['error'] = stream_get_contents($pipes[2]);
	fclose($pipes[2]);
	
	$o['status'] = proc_close($process);
	}

if($o['status'] == 1)
	{	
	echo(trim($o['error']));	
	}


?>
