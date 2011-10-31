<?php $pfad_to_home = "../../"; 

include($pfad_to_home . "in/db.php");
include($pfad_to_home . "in/menu.php");
include($pfad_to_home . "get/session.php");

$warnung = $_GET['warnung'];
$err = urldecode(mres('err'));
$cols = mresp('cols');
$cols = ($cols == "") ? mres('cols') : $cols;
$rid = mres('rid');

if($rid == -1 and !is_numeric($cols))
	stirb("view.php?uid=$uid&warnung=1");
	
$link2 = connect_to_user_db($uid);

if($rid != -1)
	{
	$abfrage = "SELECT COUNT(*) AS n FROM information_schema.tables WHERE table_name = '$rid'";
	$ergebnis = pg_query($abfrage);
	while($zeile = pg_fetch_object($ergebnis))
		$go = ($zeile->n == 1);
	
	if(!$go)
		stirb("../../?info=6");
	}
elseif(count_table_of_user() > 1000)
	stirb("view.php?uid=$uid&warnung=3");


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php include($pfad_to_home . "in/meta.php"); ?>
	<title>TRYDSH - Test Your Code</title>
</head>

<body>

    <div id="bg_div">
    
        <div class="main_div">
        
            <div class="logo">
            	<?php echo($menu_2); echo($menu_login); ?>
            </div>
            
            <div class="menu_1">
            	<?php echo($menu_1); echo($menu_login); ?>
            </div>
            
            <div class="content">
            	<p class="headline"><?php if($rid == "new") echo("Create"); else echo("Edit");?> Table</p>
                <p class="underline">Please enter a tablename and specify the columns.</p>
                <span style="color:red;">
                <?php
                if(isset($warnung))
					switch($warnung)
						{
						case 0:
							echo('<span style="color:red;">Edits saved.</span>');
							break;
						case 1:
							echo('<span style="color:red;">No file selected</span>');
							break;
						case 2:
							echo('<span style="color:red;">Please enter a table name</span>');
							break;
						case 3:
							echo('<span style="color:red;">Cannot create or rename table, maybe table already exists.</span>');
							break;
						case 4:
							echo('<span style="color:red;">Some columns or indexes couldn\'t be created/changed:</span><br /><br /><span style="color:#888; font-size:8pt;">' . $err . '</span><br />');
							break;
					
						}
				?>
				</span><br /><br />
                <form method="post" action="edit_do.php?uid=<?= $uid ?>&rid=<?= $rid ?>" id="input_form">
                <table>
                	<tr>
                    	<td style="width:150px;height:30px;">Tablename:</td>
                        <td colspan="2"><input type="text" style="width:200px;" name="name" value="<?php if($rid != -1) echo($rid);?>" /></td>
                    </tr>
                    <tr>
                    	<td style="height:30px;">Colums</td>
                        <td>Datatype</td>
                        <td style="text-align:center;">Delete</td>
                        <td style="text-align:center;">New Index</td>
                    </tr>
				  	<?php
					//existing columns
					$j = 0;
					$abfrage = "SELECT C.data_type, C.column_name FROM information_schema.columns C WHERE C.table_name = '$rid'";
					$ergebnis = pg_query($abfrage);
					while($zeile = pg_fetch_object($ergebnis))
						{	
						echo('<tr>
                    		<td><input type="text" name="cname2[' . $j . ']" style="width:150px;" value="' . $zeile->column_name . '" /><input type="hidden" name="cnameold2[' . $j . ']" style="width:150px;" value="' . $zeile->column_name . '" /></td>
                        	<td>' . datatype_select("ctype2[$j]", $zeile->data_type) . '</td>
							<td style="text-align:center;"><input type="checkbox" name=del2[' . $j . ']" /></td>
							<td style="text-align:center;"><input type="checkbox" name=index2[' . $j . ']"' . $index_value . ' /></td>
                    	</tr>');
						
						$j++;	
						}
					
					//new columns
					$i = 1;
             		while($cols >= $i)
                    	{
						echo('<tr>
                    		<td><input type="text" name="cname[' . $i . ']" style="width:150px;" /></td>
                        	<td>' . datatype_select("ctype[$i]") . '</td>
							<td style="text-align:center;"></td>
							<td style="text-align:center;"></td>
                    	</tr>');
						
						$i++;	
						}
					
					 if($j == 0 and $i == 1)
						echo('<tr><td colspan="4" style="color:red;">No columns.</td></tr>');                 
                   	?>
                    <tr>
                    	<td style="height:20px;"></td>
                    </tr>
                	<tr>
                    	<td style="height:30px;">Indexes</td>
                        <td>On Columns</td>
                        <td style="text-align:center;">Delete</td>
                        <td style="text-align:center;"></td>
                    </tr>  
                  	<?php
					//index
					$j = 0;
					$abfrage = "SELECT * FROM pg_catalog.pg_indexes WHERE tablename = '$rid'";
					$ergebnis = pg_query($abfrage);
					while($zeile = pg_fetch_object($ergebnis))
						{	
						preg_match('~CREATE .* ON .* USING [^\s]* \((.*)\)~', $zeile->indexdef, $matches);
						$index_cols = $matches[1];
						
						echo('<tr>
                    		<td><input type="text" name="index_name[' . $j . ']" style="width:150px;" value="' . $zeile->indexname . '" /><input type="hidden" name="index_nameold[' . $j . ']" style="width:150px;" value="' . $zeile->indexname . '" /></td>
                        	<td>' . $index_cols . '</td>
							<td style="text-align:center;"><input type="checkbox" name=index_del[' . $j . ']" /></td>
							<td style="text-align:center;"></td>
                    	</tr>');
						
						$j++;	
						}
					if($j == 0)
						echo('<tr><td colspan="4" style="color:red;">No indexes.</td></tr>');
					                  
                   	?>
                    
                </table><br /><br />
                <input type="hidden" value="<?php echo(mres('cols'));?>" name="cols" id="cols" />
                <input type="hidden" value="0" name="colsadd" id="colsadd" />
                <input type="submit" value="Save" id="input_submit" />
                <input type="button" value="Add column" onclick="javascript:document.getElementById('cols').value++; document.getElementById('colsadd').value = 1; document.getElementById('input_form').submit();" />
                <input type="button" value="Back" onclick="javascript:window.location.href = 'view.php?uid=<?= $uid?>'" />
                </form>    
                    
            </div>
            <div class="letzte_zeile">              	
             	<span><?php letztezeile();?></span>
        	</div>
        
        </div>
    </div>  
      		
	<script type="text/javascript">
	//Some design things
	$('.main_div').corner("round 8px").parent().css('padding', '4px').corner("round 10px");
	$('.main_div').dropShadow({left:10, top:10, blur:8, opacity:.9});
	var redraw_shadow =  function () {$('.main_div').redrawShadow();};
    
    </script>
</body>
</html>
