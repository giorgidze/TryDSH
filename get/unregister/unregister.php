<?php $pfad_to_home = "../../"; 
include($pfad_to_home . "in/db.php");
include($pfad_to_home . "get/session.php");

//DROP USERS DATABASE
pg_query("DROP DATABASE " . db_of_user($uid));

//DELETE USER
$delete = "DELETE FROM trydsh_users WHERE id = $uid";
pg_query($delete);

$update = "DELETE FROM trydsh_users_login WHERE user_id = '$uid'";
$insert = pg_query($update);

session_destroy();

stirb("../../");
?>