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
<?php require_once(dirname(__FILE__) . "/lib/header.php"); 
?>

<div class="container body">
	<h2>國立東華大學 通識教育中心 TA系統</h2>
<?php require_once(dirname(__FILE__) . "/lib/footer.php"); ?>
