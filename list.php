<?php 
	$semester = getSemester(); 
	$month = getMonth(); 
	if(strval(date("d",time()))>$deadline)
		$month = -1;


	if(isset($_SESSION['admin'])){
		if(isset($_POST['ident'])){
			$_SESSION['loginID'] = trim(mysqli_real_escape_string($DBmain, $_POST['ident'])); 
		}
?>
	<h2>請選擇您想要檢視的月誌所屬之TA姓名</h2>
	<form action="index.php?module=select" method="post">
	<select name="ident" class="form-control">
<?php
		$result = $DBmain->query("SELECT * FROM `list_TA` LEFT JOIN `list_course` ON `list_course`.`code` = `list_TA`.`courseCode` WHERE `list_TA`.`semester` = '{$semester}' AND `list_course`.`semester` = '{$semester}' ORDER BY `list_TA`.`stuID`, `list_course`.`code`; "); 
		if($result->num_rows>0){
			$front = ''; 
			while($row = $result->fetch_array(MYSQLI_BOTH)){
				if($row['stuID']!=$front){
					if($front != ''){
						echo '</option>'; 
					} ?>
		<option <?php echo $row['stuID'] == $_SESSION['loginID']? "selected ":""; ?>value="<?php echo $row['stuID']; ?>"><?php echo $row['stuID'] . " " . $row['name'] . " : " . $row['courseName'] . "/" . $row['teacher']; ?>
<?php
				}
				else {
					echo "、" . $row['courseName'] . "/" . $row['teacher']; 
				}
				$front = $row['stuID']; 
			}
		}
?>
	</select>
	<input type="submit" value="選擇" class="btn btn-info">
	</form>
	<br />
	<br />
	<br />
	<br />
	<br />
	<br />
	<br />
<?php
	}

	$query = "SELECT * FROM `list_TA` LEFT JOIN `list_course` ON `list_course`.`code` = `list_TA`.`courseCode` WHERE `list_TA`.`semester` = '{$semester}' AND `list_course`.`semester` = '{$semester}' AND `list_TA`.`stuID` = '{$_SESSION['loginID']}';"; 
	$result = $DBmain->query($query); 
	if($result->num_rows>0){
?>
<table class="table table-hover table-bordered">
	<tr>
		<th>課程代碼</th><th>課程名稱</th><th>本月</th><th>過去</th>
	</tr>
<?php	while($subj = $result->fetch_array(MYSQLI_BOTH)){ 
		$resultofdiary = $DBmain->query("SELECT * FROM `diary` WHERE `TAid` = {$subj['id']} AND `month` = '{$month}'; ");
?>
	<tr class="<?php echo $resultofdiary->num_rows>0? 'success':'danger'; ?>"> 
		<td class="col-xs-2"><?php echo $subj['courseCode']; ?></td>
		<td class="col-xs-3"><?php echo $subj['courseName']; ?></td>
		<td class="col-xs-1"><?php echo "<a href=\"index.php?module="; echo $resultofdiary->num_rows>0? "showD":"diary"; echo "&id={$subj['id']}&month={$month}\">{$month}月</a>"; ?></td>
		<td class="col-xs-6"><?php
			$othermonth = $DBmain->query("SELECT * FROM `diary` WHERE `TAid` = {$subj['id']} AND `month` != '{$month}' ORDER BY `id`; "); 
			while($m = $othermonth->fetch_array(MYSQLI_BOTH)){ ?>
			<a href="index.php?module=showD&id=<?php echo $m['id']; ?>&month=<?php echo $m['month']; ?>"><?php echo $m['month']; ?>月 </a>	
	<?php	}
		?></td>
	</tr>
<?php 
	}
?>
</table>

<?php
	}
?>
