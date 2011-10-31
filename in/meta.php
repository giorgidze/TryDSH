<?php
	
$data = '	
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="TRYDHS - Test Your Code!" />
	<meta name="author" content="" />
	<meta name="keywords" content="" />
	
	<link href="%in/style/basic.css" type="text/css" rel="stylesheet" media="all" />
	<link id="menu_3" href="%in/bar/menu_3.css" type="text/css" rel="stylesheet" />
	<link href="%in/favicon.ico" rel="shortcut icon" />
	<script type="text/javascript" src="%in/jquery.js"></script>
	<script type="text/javascript" src="%in/jq-json.js"></script>
	<script type="text/javascript" src="%in/jq-corners.js"></script>
	<script type="text/javascript" src="%in/jq-shadow.js"></script>
';
	
$data = str_replace("%", $pfad_to_home, $data);
echo($data);

?>