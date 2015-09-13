<?php session_start();  ?>
<?php require_once(dirname(__FILE__) . "/lib/std.php");  ?>
<!Doctype html>
<?php require_once(dirname(__FILE__) . "/config.php");  ?>
<html>
<head>
	<meta charset="utf8">
	
	<title>國立東華大學 通識教育中心 TA系統</title>
	<link rel="stylesheet" href="<?php echo $URLPv; ?>lib/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="<?php echo $URLPv; ?>index.css">
	<script src="<?php echo $URLPv; ?>lib/jquery/jquery-1.11.2.js"></script>
	<script src="<?php echo $URLPv; ?>lib/bootstrap/js/bootstrap.js"></script>
	<script src="<?php echo $URLPv; ?>lib/validator.min.js"></script>

</head>
<body>
<?php 
	require_once(dirname(__FILE__) . "/lib/header.php");
?>
<div class="body container">
<?php
	$has_require = 0; 
	if(isset($_GET['module'])){	
		$sites = array(
			array("select", "menu.php"), 
			array("rule", "rule.php"), 
			array("apply", "apply.php"), 
			array("list", "list.php"), 
			array("diary", "diaryform.php"), 
			array("show", "print.php"), 
			array("print", "printable.php")
		);
		foreach ($sites as $site){
			if($_GET['module'] == $site[0]){
				if(isset($_SESSION['loginID']) && isset($_SESSION['loginToken'])){
					if(checkExist($DBmain, $_SESSION['loginID'], $_SESSION['loginToken'])){
						require_once(dirname(__FILE__) . "/" . $site[1]); 
						$has_require = 1; 
					}
				}
			}
		}
	}
	if($has_require == 0)
		require_once(dirname(__FILE__) . "/login.php"); 
?>
</div>
<?php
	require_once(dirname(__FILE__) . "/lib/footer.php"); 
?>
