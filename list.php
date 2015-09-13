<?php 
	$semester = getSemester(); 
	$month = getMonth(); 
	if(strval(date("d",time()))>20)
		$month = -1; 
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
