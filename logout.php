<?php
	session_start(); 
	require_once(dirname(__FILE__) . "/config.php"); 
	require_once(dirname(__FILE__) . "/lib/std.php");
	if(isset($_SESSION['loginID'] && isset($_SESSION['loginToken']){
		if(checkExist($DBmain, $_SESSION['loginID'], $_SESSION['loginToken'])){
			setLog($DBmain, "info", "Log out", $_SESSION['stuID']); 
			session_destroy(); 
		}
		else
			setLog($DBmain, "warning", "login ID or Token Error. ", $_SESSION['loginID']; 
	}
	locate($URLPv . "index.php"); 
?>
