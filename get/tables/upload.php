<?php $pfad_to_home = "../../"; 

include($pfad_to_home . "in/db.php");
include($pfad_to_home . "in/menu.php");
include($pfad_to_home . "get/session.php");

$warnung = $_GET['warnung'];
$err = urldecode(mres('err'));
$rid = mres('rid');

if($rid == "" or is_null($rid))
	stirb("../../?info=6");

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
            	<p class="headline">Upload Data to table <?= $rid ?></p>
                <p class="underline">Plaese select your CSV-file</p>
                
                <?php
                if(isset($warnung))
					switch($warnung)
						{
						case 1:
							echo('<span style="color:red;">No file selected</span><br />');
							break;
						case 2:
							echo('<span style="color:red;">Selected file is no CSV-file</span><br />');
							break;
						case 3:
							echo('<span style="color:red;">PostgreSQL returns an error:</span><br /><br /><span style="color:#888; font-size:8pt;">' . $err . '</span><br />');
							break;
						}
				?>
				</span><br /><br />
                <form method="post" action="upload_do.php?uid=<?= $uid ?>&rid=<?= $rid ?>" id="input_form" enctype="multipart/form-data">
                <table>
                	<tr>
                    	<td style="width:150px;height:30px;">CSV-File:</td>
                        <td><input type="file" style="width:200px;" name="csv" /></td>
                    </tr>
                    <tr>
                    	<td colspan="2" style="vertical-align:middle;height:30px;">
                        	<input type="checkbox" name="thead" /> Ignore first row
                        </td>
                    </tr>
                    
                </table><br /><br />
                <input type="submit" value="Upload" id="input_submit" />
                <input type="button" value="Cancel" onclick="javascript:window.location.href = 'view.php?uid=<?= $uid ?>'" />
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