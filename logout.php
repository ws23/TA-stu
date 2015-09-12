<?php
	session_start(); 
	require_once(dirname(__FILE__) . "/config.php"); 
	require_once(dirname(__FILE__) . "/lib/std.php");
	setLog($DBmain, "info", "Log out", $_SESSION['stuID']); 
	session_destroy(); 
	locate($URLPv . "index.php"); 
?>
