<?php $pfad_to_home = "../../"; 

include($pfad_to_home . "in/db.php");
include($pfad_to_home . "in/menu.php");
include($pfad_to_home . "get/session.php");

$warnung = $_GET['warnung'];

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
            	<p class="headline">My Tables</p>
                <p class="underline">Create, upload an delete your tables</p>
                <span style="color:red;">
                <?php
                if(isset($warnung))
					switch($warnung)
						{
						case 1:
							echo("Please enter a correct number of columns.");
							break;
						case 2:
							echo("Selected file is no CSV-file");
							break;
						case 3:
							echo("Limit of 1000 tables reached, please delete some tables first.");
							break;
						}
				?>
				</span><br /><br />
                <form method="post" action="edit.php?uid=<?= $uid ?>&rid=-1" id="input_form">
                <table class="table1" cellpadding="0" cellspacing="0">
                    <tr>
                        <th style="width:200px;">Name</th>
                        <th style="width:70px;">Rows</th>
                        <th style="width:70px;">Cols</th>
                        <th style="width:100px;"></th>
                        <th style="width:60px;"></th>
                        <th style="width:60px;text-align:center;">&nbsp;</th>
                        <th style="width:60px;text-align:center;">&nbsp;</th>
                    </tr>
                 
                    <?php
                    $link2 = connect_to_user_db($uid);
					
                    $abfrage = "SELECT *, (SELECT COUNT(*) FROM information_schema.columns C WHERE C.table_name = T.table_name) AS cols FROM information_schema.tables T WHERE table_schema = 'public' ORDER BY table_name";
                    $ergebnis = pg_query($abfrage);
                    while($zeile = pg_fetch_object($ergebnis))
                        {
						//GET ROWS
						$abfrage2 = "SELECT COUNT(*) AS n FROM \"$zeile->table_name\"";
						$ergebnis2 = pg_query($abfrage2);
						while($zeile2 = pg_fetch_object($ergebnis2))
							$rows = $zeile2->n;
							
							
						$edit = "<a href=\"edit.php?uid=$uid&rid=$zeile->table_name\">Edit</a>";
                        $del = "<a href=\"del.php?uid=$uid&rid=$zeile->table_name&modus=drop\" onclick=\"javascript:return confirm('Do you really want to delete this table?');\">Delete</a>";
						$empty = "<a href=\"del.php?uid=$uid&rid=$zeile->table_name&modus=empty\" onclick=\"javascript:return confirm('Do you really want to empty this table?');\">Empty</a>";
						$upload = "<a href=\"upload.php?uid=$uid&rid=$zeile->table_name\">Upload data</a>";
                                              
                        echo("
                        <tr>
                            <td>$zeile->table_name</td>
                            <td>$rows</td>
                            <td>$zeile->cols</td>
                            <td style=\"text-align:right;\">$upload</td>
                            <td style=\"text-align:center;\">$edit</td>
							<td style=\"text-align:center;\">$empty</td>
                            <td style=\"text-align:center;\">$del</td>
                        </tr>
                        ");
                        
                        }
                    if(pg_num_rows($ergebnis) == 0)
                        echo('<tr><td colspan="6" style="height:40px;vertical-align:middle;color:red;">No Tables.</td></tr>');	
                        
                 ?>   
                  <tr>
                    <td colspan="6" style="height:90px;">Create a new Table with <input type="text" style="width:30px;" name="cols" /> columns. <input type="submit" value="OK" /></td>
                  </tr>
                  <tr>
                        <td><input type="button" value="Back" onclick="javascript:window.location.href = '../../'" style="width:120px;" id="abbrechen_button" />
                    </td>
                 </tr>  
                 </table><br /><br />
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