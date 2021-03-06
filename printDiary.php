<?php 

	require_once(dirname(__FILE__) . "/list.php"); 

	$month = trim(mysqli_real_escape_string($DBmain, $_GET['month'])); 
	$id = trim(mysqli_real_escape_string($DBmain, $_GET['id'])); 
	$semester = getSemester(); 

	$infos = $DBmain->query("SELECT `list_TA`.`stuID`, `list_TA`.`name`, `list_TA`.`courseCode`, `list_course`.`courseName`, `list_course`.`teacher`  FROM `list_TA` LEFT JOIN `list_course` ON `list_TA`.`courseCode` = `list_course`.`code` WHERE `list_TA`.`id` = {$id} AND `list_TA`.`semester` = '{$semester}' AND `list_course`.`semester` = '{$semester}'; "); 
	$info = $infos->fetch_array(MYSQLI_BOTH); 

	if($info['stuID']==$_SESSION['loginID'] || isset($_SESSION['admin'])) {
	
	$result = $DBmain->query("SELECT * FROM `diary` WHERE `TAID` = {$id} AND `month` = {$month}; ");
	if($result->num_rows<=0 && !isset($_SESSION['admin']))
		locate($URLPv . "index.php?module=diary&id={$id}&month={$month}");
	$row = $result->fetch_array(MYSQLI_BOTH);

	if(isset($_POST['flag'])){
		if($_POST['flag']==$_SESSION['loginToken']){
			$DBmain->query("UPDATE `diary` SET `lastUpdate` = CURRENT_TIMESTAMP WHERE `month` = '{$month}' AND `TAID` = {$id}; "); 
			$DBmain->query("DELETE FROM `diary_record` WHERE `diaryID` = {$row['id']}; ");
			
			for($i=0; $i<10; $i++){
				if($_POST['rec_date'][$i]=='0' || $_POST['rec_from_hr'][$i]=='-1' || $_POST['rec_to_hr'][$i]=='-1')
					continue; 
				$date = trim(mysqli_real_escape_string($DBmain, $_POST['rec_date'][$i])); 
				$from = fixZero(trim(mysqli_real_escape_string($DBmain, $_POST['rec_from_hr'][$i])), 2) . ":" . fixZero(trim(mysqli_real_escape_string($DBmain, $_POST['rec_from_min'][$i])), 2) . ":00"; 
				$to = fixZero(trim(mysqli_real_escape_string($DBmain, $_POST['rec_to_hr'][$i])), 2) . ":" . fixZero(trim(mysqli_real_escape_string($DBmain, $_POST['rec_to_min'][$i])), 2) . ":00";
				$content = trim(mysqli_real_escape_string($DBmain, $_POST['rec_text'][$i]));
				$DBmain->query("INSERT INTO `diary_record` (`diaryID`, `date`, `fromTime`, `toTime`, `content`) VALUES ({$row['id']}, '{$date}', '{$from}', '{$to}', '{$content}'); ");
			}
			locate($URLPv . "index.php?module=showD&month={$month}&id={$id}"); 
		}
	}
	$records = $DBmain->query("SELECT * FROM `diary_record` WHERE `diaryID` = {$row['id']} ORDER BY `date`, `fromTime`, `toTime`; "); 
?>

<div class="diary">
	<div class="diary-heading text-center">
		<h2>國立東華大學</h2>
		<h3>通識教育中心教學助理工作日誌</h3>
	</div>
	<div class="diary-date">
		<p class="text-right">
			填表時間：<?php echo $row['lastUpdate'];?>
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
	<?php if($records){ ?>
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
			<tr><td colspan="2" class="text-center">授課教師簽閱</td><td></td></tr>
		</table>
	</div>
	<ul>
		<li>請確實做好每月工作紀錄，並以條列式敘述該月工作狀況。</li>
		<li>煩請助教於每月 <?php echo $deadline; ?> 日前填寫完畢，並請老師簽名後，將本工作月誌繳交至通識教育中心。</li>
	</ul>
</div>

<p class="text-center">
	<?php
	if ($month == getMonth()){ ?>
	<a class="print-btn" href="<?php echo $URLPv; ?>index.php?module=diary&id=<?php echo $id; ?>&month=<?php echo $month; ?>"><button class="btn btn-danger">返回修改資料</button></a>
<?php	}
	?>
    <a class="print-btn" href="<?php echo $URLPv; ?>printableDiary.php?id=<?php echo $id; ?>&month=<?php echo $month; ?>"><button class="btn btn-success">確認，列印申請表</button></a>
</p>

<?php } }
else
	setlog($DBmain, "warning", "Try to get diary that dont have auth. ", $_SESSION['loginID']); 
?>
