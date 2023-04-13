<?php
session_start();
session_destroy();
$_SESSION['user']=NULL;
echo '<meta http-equiv="refresh" content="0,url=../../login.php">';
?>