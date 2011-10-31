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
            	<p class="headline">My Programs</p>
                <p class="underline">Manage and run your programs!</p>
                <span style="color:red;">
                <?php
                if(isset($warnung))
					switch($warnung)
						{
						case 1:
							echo("");
							break;
						case 2:
							echo("");
							break;
						}
				?>
				</span><br /><br />
                <table class="table1" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <th style="width:170px;">Name</th>
                        <th style="width:60px;">Version</th>
                        <th style="width:170px;">Time</th>
                        <th style="width:90px;text-align:center;">&nbsp;</th>
                        <th style="width:55px;text-align:center;">&nbsp;</th>
                        <th style="width:70px;text-align:center;">&nbsp;</th>
                    </tr>
                 
                    <?php
					$names[-1] = FALSE;
					$i = 0;
					$n = 1;
                    $abfrage = "SELECT * FROM trydsh_history WHERE user_id = $uid ORDER BY name, dat";
                    $ergebnis = pg_query($abfrage);
                    while($zeile = pg_fetch_object($ergebnis))
                        {
						$names[$i] = $zeile->name;	
						
						//GROUP BY NAMES
						if($names[$i] != $names[$i - 1])
							{
							$name = $zeile->name;	
							$n = 1;
							$style = "padding-top:15px;padding-bottom:0px;";
							$del_all = "<a href=\"del.php?uid=$uid&rid=" . urlencode($zeile->name) . "&modus=name\" onclick=\"javascript:return confirm('Do you really want to delete all program named $zeile->name?');\">Delete all</a>";
							}
						else
							{
							$name = "";	
							$style = "height:20px;padding-top:0px;padding-bottom:0px;";
							$del_all = "";
							}
							
							
						$edit = "<a href=\"../../?history=$zeile->id\">View & Edit</a>";
                        $del = "<a href=\"del.php?uid=$uid&rid=$zeile->id\" onclick=\"javascript:return confirm('Do you really want to delete this program?');\">Delete</a>";
						$dat_unix = strtotime($zeile->dat);
						$dat = today_yesterday($dat_unix) . strftime(", %H:%M", $dat_unix);
                                              
                        echo("
                        <tr>
                            <td style=\"$style\">$name</td>
                            <td style=\"$style\">$n</td>
                            <td style=\"$style\">$dat</td>
                            <td style=\"text-align:center;$style\">$edit</td>
                            <td style=\"text-align:center;$style\">$del</td>
							<td style=\"text-align:center;$style\">$del_all</td>
                        </tr>
                        ");
                        
						$i++; $n++;
                        }
                    if(pg_num_rows($ergebnis) == 0)
                        echo('<tr><td colspan="6" style="height:40px;vertical-align:middle;color:red;">No Programs.</td></tr>');	
                        
                 ?> 
                 
                  <tr>
                        <td colspan="6"><input type="button" value="Back" onclick="javascript:window.location.href = '../../'" style="width:120px;margin-top:15px;" id="abbrechen_button" />
                    </td>
                 </tr> 
                 <tr>
                        <td colspan="6" class="underline">Be aware of the limit of 1000 programs and 10 revision for each program. If one limit is reached, older programs/revision will be automatically deleted.
                    </td>
                 </tr>   
                 </table><br /><br />
                    
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
