<?php 

//Menu 1
if($LOG_IN)
	{
	$menu_1 = '
	<a href="%">Home</a>&nbsp;
	<a href="%about/">About</a>&nbsp;
	<a href="%get/tables/view.php?uid=' . $_SESSION['id'] . '">My Tables</a>&nbsp;
	<a href="%get/programs/view.php?uid=' . $_SESSION['id'] . '">My Programs</a>&nbsp;
	';
	}
else
	$menu_1 = '
		<a href="%">Home</a>&nbsp;
		<a href="%about/">About</a>&nbsp;
	';
//Inhalt der Fußzeile
function letztezeile()
	{
	global $pfad_to_home, $c;
	$dauer = number_format(round(microtime(TRUE) - MICROTIME, 3), 3, ",", ".");
	echo("
&copy DB Uni Tübingen - <a href=\"http://www-db.informatik.uni-tuebingen.de\">www-db.informatik.uni-tuebingen.de</a> - ($dauer s)");
	}


//Menu_2

	//Login oder nicht
	$menupunkt_rechts = ($LOG_IN) 
		? '<a href="%get/login/logout.php?uid=' . $_SESSION['id'] . '" title="Logout"  class="obenwhite" id="a_logout">Logout</a>&nbsp;</td><td><a href="%get/login/logout.php?uid=' . $_SESSION['id'] . '" title="Logout"  class="obenwhite" id="a_logout"><img src="' . $pfad_to_home . 'get/login/img/logout_klein.png" border="0" /></a>'
		: '<a href="%?info=4" title="Login"  class="obenwhite" id="a_login" onclick="switch_login(); return false;">Login</a>';
	
	//Myprofile or not
	$menupunkt_mitte = ($LOG_IN) 
		? '<a href="%get/profile/edit.php?uid=' . $_SESSION['id'] . '" class="obenwhite">My Profile</a>'
		: '<a href="%register/" class="obenwhite">Register</a>';	
		
$menu_2 = '
<div class="menu_2 obenwhite">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td><a href="%" class="obenwhite">TRYDSH v0.1</a>&nbsp;|&nbsp;</td>
			<td>' . $menupunkt_mitte . '&nbsp;|&nbsp;</td>
			<td>' . $menupunkt_rechts . '</td>
		</tr>
	</table>
</div>
';

$menu_login = '
<div class="login_div" id="login_div_bg"></div>
<div class="obenwhite" style="position:absolute; top:50px; right:30px; z-index:1; visibility:hidden;" id="login_div">
	<form method="post" action="%get/login/login.php">
		<table>
			<tr>
				<td>Username</td>
				<td>Password</td>
				<td></td>
			</tr>
			<tr>
				<td><input id="benutzer" type="text" name="email" style="width:120px; font-style:italic; color:#777;" value="Username" onclick="javascript: reset_feld(\'benutzer\', \'Username\');" /></td>
				<td><input id="password" type="password" name="password" style="width:120px; color:#CCC;" value="Pass" onclick="javascript: reset_feld(\'password\', \'Pass\');" /></td>
				<td><input type="submit" value="OK" style="width:45px;" /></td>
			</tr>
			<tr>
				<td colspan="3" height="20" valign="middle" align="right" style="vertical-align:middle;">
					<span style="height:20px; vertical-align:middle;display:block;"><input type="checkbox" name="dauerlogin" checked="checked" /> Keep me logged in</span>
				</td>
			</tr>
		</table>
	</form>
	
	<script type="text/javascript">
		
		if(document.getElementById(\'a_login\'))
			document.getElementById(\'a_login\').setAttribute(\'href\', \'#\', 0);
	
		function reset_feld(eingabe, standard)
			{ 
			input = document.getElementById(eingabe);
			if (input.value == standard)
				{
				input.value = "";
				input.style.color = "#000";
				input.style.fontStyle = "normal";
				}
			if(eingabe == \'benutzer\')
				reset_feld(\'password\', \'Pass\');
			if(eingabe == \'benutzer2\')
				reset_feld(\'password2\', \'Pass\');
				
			
			return true;
			}
	
		function switch_login(aus)
			{
			ldiv = document.getElementById(\'login_div\');
			ldiv2 = document.getElementById(\'login_div_bg\');
			//a = document.getElementById(\'a_login\');
			
			if(ldiv.style.visibility == \'hidden\')
				{
				ldiv.style.visibility = \'visible\';
				ldiv2.style.visibility = \'visible\';
				//a.innerHTML = "Suche";
				
				}
			else
				{
				ldiv.style.visibility = \'hidden\';
				ldiv2.style.visibility = \'hidden\';
				//a.innerHTML = "Login";
				}	
			}
	</script>
	
</div>

';

$menu_1 = str_replace("%", $pfad_to_home, $menu_1);
$menu_2 = str_replace("%", $pfad_to_home, $menu_2);
$menu_login = str_replace("%", $pfad_to_home, $menu_login);


?>
