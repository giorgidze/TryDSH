<?php $pfad_to_home = "../"; 

include($pfad_to_home . "in/db.php");
include($pfad_to_home . "in/menu.php");

$warnung = $_GET['warnung'];

$werte = array("prename", "name", "username", "mail1", "mail2", "pass1", "pass2");
foreach($werte as $element)
	$$element = $_GET[$element];
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
            	<p class="headline">Register</p>
                <p class="underline">Create your own account!</p>
                <span style="color:red;">
                <?php
                if(isset($warnung))
					switch($warnung)
						{
						case 1:
							echo("Please fill in all fields!");
							break;
						case 2:
							echo("E-Mail repetition is wrong");
							break;
						case 3:
							echo("Password repetition is wrong");
							break;
						case 4:
							echo("E-Mail is not valid");
							break;
						case 5:
							echo("Your E-Mail is already used by another account");
							break;
						case 6:
							echo("Username is in use, please choose a different one");
							break;
						}
				?>
				</span><br /><br />
                <form method="post" action="register_do.php" id="input_form">
                <table>
                	<tr>
                    	<td style="width:150px;">Prename:</td>
                        <td><input type="text" style="width:200px;" name="prename" /></td>
                    </tr>
                    <tr>
                    	<td>Name:</td>
                        <td><input type="text" style="width:200px;" name="name" /></td>
                    </tr>
                    <tr>
                    	<td>Username:</td>
                        <td><input type="text" style="width:200px;" name="username" /></td>
                    </tr>
                    <tr>
                    	<td>E-Mail:</td>
                        <td><input type="text" style="width:200px;" name="mail1" /></td>
                    </tr>
                    <tr>
                    	<td>Repeat E-Mail:</td>
                        <td><input type="text" style="width:200px;" name="mail2" /></td>
                    </tr>
                    <tr>
                    	<td>Password:</td>
                        <td><input type="password" style="width:200px;" name="pass1" /></td>
                    </tr>
                    <tr>
                    	<td>Repeat Password:</td>
                        <td><input type="password" style="width:200px;" name="pass2" /></td>
                    </tr>
                    
                </table><br /><br />
                <input type="submit" value="Register" id="input_submit" />
                <input type="button" value="Cancel" onclick="javascript:window.location.href = '../'" />
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
    
	<?php 
	foreach($werte as $element)
		echo("$('input[name=$element]').attr('value', '" . $$element . "');");
	?>
    </script>
</body>
</html>