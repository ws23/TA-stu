<?php session_start();  ?>                                                                            
<?php require_once(dirname(__FILE__) . "/lib/std.php");  ?>
<!Doctype html>
<?php require_once(dirname(__FILE__) . "/config.php");  ?>
<?php
    $month = trim(mysqli_real_escape_string($DBmain, $_GET['month']));
   	$id = trim(mysqli_real_escape_string($DBmain, $_GET['id'])); 
	$semester = getSemester(); 
	if(isset($_SESSION['loginID']) && isset($_SESSION['loginToken'])){
	    if(checkExist($DBmain, $_SESSION['loginID'], $_SESSION['loginToken'])){
?>
<html>
<head>
    <meta charset="utf8">
    
    <title>國立東華大學 通識教育中心 TA系統</title>
    <link rel="stylesheet" href="<?php echo $URLPv; ?>lib/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo $URLPv; ?>index.css">
    <script src="<?php echo $URLPv; ?>lib/jquery/jquery-1.11.2.js"></script>
    <script src="<?php echo $URLPv; ?>lib/bootstrap/js/bootstrap.js"></script>
    <script src="<?php echo $URLPv; ?>lib/validator.min.js"></script>
	<script>
		print(); 
		function wait(){
			setTimeout('window.location.href = "<?php echo $URLPv; ?>index.php?module=showD&id=<?php echo $id; ?>&month=<?php echo $month; ?>"', 10); 
		}
	</script>
</head>
<body class="printable" onload="wait(); ">

<?php
	

	$infos = $DBmain->query("SELECT `list_TA`.`stuID`, `list_TA`.`name`, `list_TA`.`courseCode`, `list_course`.`courseName`, `list_course`.`teacher`  FROM `list_TA` LEFT JOIN `list_course` ON `list_TA`.`courseCode` = `list_course`.`code` WHERE `list_TA`.`id` = {$id} AND `list_TA`.`semester` = '{$semester}' AND `list_course`.`semester` = '{$semester}'; "); 
	$result = $DBmain->query("SELECT * FROM `diary` WHERE `TAID` = {$id} AND `month` = '{$month}'; ");
	$pTime = date("Y-m-d H:i:s", time()); 
	
	$row = $result->fetch_array(MYSQLI_BOTH);
	$DBmain->query("UPDATE `diary` SET `printTimes` = " . ($row['printTimes']+1) . ", `lastPrint` = CURRENT_TIMESTAMP WHERE `id` = {$row['id']}; "); 
	$info = $infos->fetch_array(MYSQLI_BOTH); 
	
	if($info['stuID']==$_SESSION['loginID'] || isset($_SESSION['admin'])) {

	$records = $DBmain->query("SELECT * FROM `diary_record` WHERE `diaryID` = {$row['id']}; "); 
?>

<div class="diary">
	<div class="diary-heading text-center">
		<h2>國立東華大學</h2>
		<h3>通識教育中心教學助理工作日誌</h3>
	</div>
	<div class="diary-date">
		<p class="text-right">
			填表時間：<?php echo $row['lastUpdate'];?><br />
			列印時間：<?php echo $pTime; ?>
			
		</p>
	</div>
	<div class="diary-info">
		<p class="text-left">
			姓　　名：<?php echo $info['name']; ?><br />
			科目代碼：<?php echo $info['courseCode']; ?><br />
			課程名稱：<?php echo $info['courseName']; ?><br />
			授課教師：<?php echo $info['teacher']; ?>
		</p>
	</div>
	<div class="diary-form">
	<h4 colspan="3" class="text-center"><?php echo $month; ?>月 工作內容與執行情況（摘要）</h4>
		<table class="diary-table table">
			<tr>
				<th class="col-xs-2 text-center">日期</th>
				<th class="col-xs-2 text-center">時段</th>
				<th class="col-xs-8 text-center">工作內容</th>
			</tr>
			<?php
			$counter = 0; 
			while($rec = $records->fetch_array(MYSQLI_BOTH)){
			?>
			<tr class="text-center">
				<td><?php echo $rec['date']; ?></td>
				<td><?php echo substr($rec['fromTime'], 0, 5) . " ~ " . substr($rec['toTime'], 0, 5); ?></td>
				<td class="text-left"><?php echo $rec['content']; ?></td>
			</tr>
			<?php
			
				$counter++; 
			}
			for($counter; $counter<10; $counter++){
			?>
			<tr><td>&nbsp; </td><td></td><td></td></tr>

			<?php
			}
			?>
			<tr><td colspan="2" class="text-center twotimes">授課教師簽閱</td><td></td></tr>
		</table>
	</div>
	<ul>
		<li>請確實做好每月工作紀錄，並以條列式敘述該月工作狀況。</li>
		<li>煩請助教於每月 30 日前填寫完畢，並請老師簽名後，將本工作月誌繳交至通識教育中心。</li>
	</ul>
</div>

<?php 
}
}
}
else
	locate($URLPv . "index.php"); 
?>
