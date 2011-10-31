<?php $pfad_to_home = ""; 

include($pfad_to_home . "in/db.php");
include($pfad_to_home . "in/menu.php");
$zeitjetzt = time();
$info = mres('info');
$history = mres('history');

$cmd = "Please type in your program";
$savename = "Unnamed";

//Maybe select history program
if($LOG_IN and $history != "" and is_numeric($history))
	{
	$abfrage = "SELECT program, name FROM trydsh_history WHERE user_id = '" . $_SESSION['id'] . "' AND id = $history";
	$ergebnis = pg_query($abfrage);
	while($zeile = pg_fetch_object($ergebnis))
		{
		$cmd = $zeile->program;
		$savename = $zeile->name;
		}
	}
elseif(!$LOG_IN)
	{
	$cmd ='-- Here you can type your own database-executable program.

employees :: Q [(Text, Text, Integer)]
employees = toQ [
    ("Simon",  "MS",   80)
  , ("Erik",   "MS",   90)
  , ("Phil",   "Ed",   40)
  , ("Gordon", "Ed",   45)
  , ("Paul",   "Yale", 60)
  ]

departments :: Q [Text]
departments = nub [ dept | (view -> (name,dept,salary)) <- employees]

deptSalary :: Q Text -> Q Integer
deptSalary dept = sum [ salary
                      | (view -> (name,dept\',salary)) <- employees
                      , dept == dept\']

mainQuery :: Q [(Text,Integer)]
mainQuery = [ tuple (dept,deptSalary dept)
            | dept <- departments]';
	}	



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
            
            <div class="console" id="console">
                <form method="post" action="#" id="input_form">
                    <textarea id="input_area"><?= $cmd ?></textarea>
                    <table class="table1" width="100%">
                    	<tr>
                        	<td><input type="submit" value="Run" id="input_submit" style="width:120px;margin:5px;" /></td>
                            
							<?php
                            if($LOG_IN)
                                echo('
									<td class="underline" style="vertical-align:middle;text-align:right;width:50px;">
										<input type="checkbox" name="save" id="save" checked="checked" />
									</td>
									<td class="underline" style="vertical-align:middle;text-align:left;">
										Save program to <b><a href="get/programs/view.php?uid=' . $_SESSION['id'] . '">My Programs</a></b> when running.
									</td>
									<td class="underline" style="vertical-align:middle;text-align:right;width:160px;">
										Title: <input type="text" style="width:120px;" name="savename" id="savename" value="' . $savename . '" />
									</td>
                                ');
                            ?>
                         </tr>
                     </table>
                </form>
            </div>

            <div class="result" id="res">
		<div class="result_links" id="result_links">
			<a href="#" id="a_table" name="optionlinks">Table</a>
	                <a href="#" id="a_haskell" name="optionlinks">Haskell</a>
        	        <a href="#" id="a_json" name="optionlinks">JSON</a>
			<a href="#" id="a_json_d" name="optionlinks">Download JSON</a>
                	<a href="#" id="a_csv" name="optionlinks">Download CSV</a>
                	<a href="#" id="a_sql" name="optionlinks">SQL</a>
                	<a href="#" id="a_plan" name="optionlinks">Query Plan</a>
                	<a href="#" id="a_plan_opt" name="optionlinks">Optimised Query Plan</a>
		</div>
                <div class="result" id="res_haskell">
                    <?php
					
                    if(isset($info))
                        switch($info)	
                            {
                            case 1:
                                echo('<span style="color:red;">Registration succeeded. You can now login.</span>');
                                break;
                            case 2:
                                echo('<span style="color:red;">Login failed, wrong username or password.</span>');
                                break;
                            case 3:
                                echo('<span style="color:red;">Cookies are needed for login-features. Please enable or allow cookies.</span>');
                                break;
                            case 4:
                                echo('<span style="color:red;">Javascript must be enabled to use this site.</span>');
                                break;	
                            case 5:
                                echo('<span style="color:red;">Restricted area, please login.</span>');
                                break;	
							case 6:
                                echo('<span style="color:red;">Your required site doesen\'t exists.</span>');
                                break;
                            }
                    ?>
                </div>
		<br />
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
	
	//Remove links
	//$('a[name="optionlinks"]').css('display', 'none');
	$('#result_links').css('display', 'none');
	
	//Remove start-text of input area
	$('#input_area').click(function () {
		if($('#input_area').html() == 'Please type in your program') {
			$('#input_area').html('');
		}
	});
	
	//Bind ajax event to submit button
	$('#input_form').submit(function() {
		txt = $('#input_area').val();
		save = ($('#save') && $('#save').attr('checked') == 'checked') ? 1 : 0;
		savename = (save) ? $('#savename').val() : '';			
		run_code(txt, save, savename);
		return false;
	});
	
	//ajax event (send and handle echo)
	var run_code = function(command, save, savename) {
		$('#input_submit').attr('value', 'Please wait...');
		$('#input_submit').attr('disabled', 'disabled');
		$('#res_haskell').hide('slow');
		$('#res_haskell').html('');
		$.ajax({
			url: "echo.php",
			type: "POST",
			data: {'command': command, 'flag': 'xhtml', 'save': save, 'savename': savename},
			dataType: "json",
			contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(msg) {
				//Safe result
				ERG = msg;
				//$('#res').html(msg.replace('\n', '<br />'));
				$('#input_submit').attr('value', 'Run');
				$('#input_submit').removeAttr('disabled');
				
				if(msg.status == 0 && msg.echo == 1)
					{
					$('div#res a').css('display', '');
					show_table();
					show_csv_link();
					update_link_target('plan');
					update_link_target('plan_opt');
					update_link_target('json_d');
					}
				else if (msg.status == 1) {
					$('#result_links').css('display', 'none');
					$('#res_haskell').html('<p style="color:red;margin:0px;padding:0px;">There is an error in your code:</p>'+msg.error.replace('\n', '<br />'));
					$('#res_haskell').show('slow', redraw_shadow);
				}
				else {
					$('#result_links').css('display', '');
					$('a[name="optionlinks"]').css('display', 'none');
					$('#a_csv').css('display', '');
					$('#res_haskell').html('<p style="color:red;margin:0px;padding:0px;">Data to large to display, please download as CSV</p>');
					$('#res_haskell').show('slow', redraw_shadow);
					show_csv_link();
				}
			}	
		})
			
	}
	
	var change_link = function(active) {
		$('#result_links').css('display', '');
		$('a[name="optionlinks"]').attr('class', 'result_links_notactive'); 
		$(active).attr('class', 'result_links_active'); 	
	}
	
	
	//Display table result
	$('#a_table').click(function () {show_table();});
	
	var show_table = function () {
		if(typeof ERG != "undefined" && ERG.status == 0) {
			
			$('#res_haskell').hide();
			$('#res_haskell').html(ERG.erg);
			$('#res_haskell').show('slow', redraw_shadow);
		}
		else {
			$('#res_haskell').html('No good data or table has no rows');
			$('#res_haskell').show('slow', redraw_shadow);
		}
		
		change_link('#a_table');
		return false;
	};
	
	//Download CSV
	var show_csv_link = function () {
		if(typeof ERG != "undefined" && ERG.status == 0) {
			$('#a_csv').attr('href', 'echo.php?flag=csv&rand='+ERG.rand); 
		};	
	};
	$('#a_json_d, #a_csv, #a_plan, #a_plan_opt').click(function () {
		change_link('#'+$(this).attr('id'));
	});

	var update_link_target = function(mode) {
		$('#a_'+mode).attr('href', 'echo.php?flag=' + mode + '&rand=' + ERG.rand);
		if(mode != 'json_d')
			$('#a_'+mode).attr('target', '_blank');
	};

	
	
	//Show SQL+JSON+...
	var create_click_functions = function (mode) {
		$('#a_'+mode).click(function () {
			$('#res_haskell').hide();
			$('#res_haskell').html('Loading...');
			$('#res_haskell').show('slow', redraw_shadow);
			$('#res_haskell').load('echo.php?flag=' + mode + '&rand=' + ERG.rand, function() {
				$('#res_haskell').show('slow', redraw_shadow);
			});
			
			change_link('#a_'+mode);	
			return false;
		});
	};
	
	create_click_functions('sql');
	create_click_functions('json');
	create_click_functions('haskell');
    
    </script>
</body>
</html>
