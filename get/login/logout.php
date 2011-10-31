<?php $pfad_to_home = "../../";
$ident = $_COOKIE['ident'];

include($pfad_to_home . "in/db.php");
include("../session.php");

$uid = $_SESSION['id'];

$update = "DELETE FROM trydsh_users_login WHERE user_id = '$uid' AND ident = '$ident'";
$insert = pg_query($update);

setcookie("ident", '', 0, "/");
setcookie("name", '', 0, "/");

session_destroy();

header("Location:" . $pfad_to_home);
?>
