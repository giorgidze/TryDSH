<?php $pfad_to_home = "../"; 

include($pfad_to_home . "in/db.php");
include($pfad_to_home . "in/menu.php");
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
            	<p class="headline">About Trydsh</p>
                <p class="underline">Further Information for you!</p>
                <!-- INSERT TEXT -->
                    
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