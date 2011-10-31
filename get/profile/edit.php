<?php $pfad_to_home = "../../"; 

include($pfad_to_home . "in/db.php");
include($pfad_to_home . "in/menu.php");
include($pfad_to_home . "get/session.php");

$warnung = $_GET['warnung'];

$abfrage = "SELECT email FROM trydsh_users WHERE id = $uid";
$ergebnis = pg_query($abfrage);
while($zeile = pg_fetch_object($ergebnis))
	$mail = $zeile->email;
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
            	<p class="headline">My Profile</p>
                <p class="underline">Change your password and E-Mail</p>
                <span style="color:red;">
                <?php
                if(isset($warnung))
					switch($_GET['warnung'])
						{
						case 0: echo("E-Mail and/or password successful changed.<br />&nbsp;"); break;
						case 1: echo("Old password is wrong.<br />&nbsp;"); break;
						case 2: echo("Password repetition is wrong.<br />&nbsp;"); break;
						case 3: echo("E-Mail is not valid!<br />&nbsp;"); break;
						case 4: echo("Nothing changed.<br />&nbsp;"); break;
						default: echo(""); break;
						}
				?>
				</span><br /><br />
                <form method="post" action="edit_do.php?uid=<?= $uid ?>" id="input_form">
                <table>
                    <tr>
                        <td style="vertical-align:top; width:200px; height:30px;">Old password:</td>
                        <td style="vertical-align:top; width:400px;"><input type="password" name="p_alt" style="width:150px;" /></td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top; height:30px;">New password:</td>
                        <td style="vertical-align:top;"><input type="password" name="p_neu" style="width:150px;" /></td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top; height:30px;">Repeat new password:</td>
                        <td style="vertical-align:top;"><input type="password" name="p_neu2" style="width:150px;" /></td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top; height:30px;padding-top:20px;">Change E-Mail to:</td>
                        <td style="vertical-align:top;padding-top:20px;"><input type="text" name="mail" value="<?= $mail ?>" style="width:250px;" /></td>
                    </tr>
                </table><br /><br />
                <input type="submit" value="Save" id="input_submit" />
                <input type="button" value="Cancel" onclick="javascript:window.location.href = '../../'" />
                <a href="../unregister/unregister.php?uid=<?= $uid ?>" onclick="javascript: return confirm('All tables and your account will be deleted. Do you really want to continue?');" style="float:right;margin-right:20px;">Unregister and delete my account</a>
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