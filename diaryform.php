<?php 

	$month = trim(mysqli_real_escape_string($DBmain, $_GET['month'])); 
	$id = trim(mysqli_real_escape_string($DBmain, $_GET['id'])); 
	$semester = getSemester(); 

	$infos = $DBmain->query("SELECT `list_TA`.`stuID`, `list_TA`.`name`, `list_TA`.`courseCode`, `list_course`.`courseName`, `list_course`.`teacher`  FROM `list_TA` LEFT JOIN `list_course` ON `list_TA`.`courseCode` = `list_course`.`code` WHERE `list_TA`.`id` = {$id} AND `list_TA`.`semester` = '{$semester}' AND `list_course`.`semester` = '{$semester}'; "); 
	$info = $infos->fetch_array(MYSQLI_BOTH); 
	

	if($info['stuID']==$_SESSION['loginID'] || isset($_SESSION['admin'])) {
		if($month != getMonth())
	        locate($URLPv . "index.php?module=showD&id={$id}&month={$month}");
		$result = $DBmain->query("SELECT * FROM `diary` WHERE `TAID` = {$id} AND `month` = '{$month}'; ");
		if($result->num_rows<=0)
			$DBmain->query("INSERT INTO `diary` (`semester`, `month`, `TAID`) VALUES('{$semester}', '{$month}', {$id}); ");  
		$result = $DBmain->query("SELECT * FROM `diary` WHERE `TAID` = {$id} AND `month` = '{$month}'; ");
		$row = $result->fetch_array(MYSQLI_BOTH);
		$records = $DBmain->query("SELECT * FROM `diary_record` WHERE `diaryID` = {$row['id']}; "); 

	/* FORM */
?>
    <div class="diary-heading text-center">
        <h2>國立東華大學</h2>
        <h3>通識教育中心教學助理工作日誌</h3>
    </div>
    <div class="diary-info">
        <p class="text-left">
            姓　　名：<?php echo $info['name']; ?><br />
            科目代碼：<?php echo $info['courseCode']; ?><br />
            課程名稱：<?php echo $info['courseName']; ?><br />
            授課教師：<?php echo $info['teacher']; ?>
        </p>
    </div>

<div class="diary-record">
<form method="post" action="index.php?module=showD&id=<?php echo $id; ?>&month=<?php echo $month; ?>">
	<input type="hidden" name="flag" value="<?php echo $_SESSION['loginToken']; ?>">
    <h4 colspan="3" class="text-center"><?php echo $month; ?>月 工作內容與執行情況（摘要）</h4>
        <table class="table table-bordered table-hover">
            <tr>
                <th class="col-xs-2 text-center">日期</th>
                <th class="col-xs-4 text-center">時段</th>
                <th class="col-xs-6 text-center">工作內容</th>
            </tr>
            <?php
			for($i=0; $i<10; $i++) {
			if($records->num_rows>=0)
	            $rec = $records->fetch_array(MYSQLI_BOTH); 
			else
				$rec = false; 
            ?>
            <tr class="text-center">
                <td>
					<?php 
					if($rec){
						$yearmonth = substr($rec['date'], 0, 7); 
						$day = substr($rec['date'], 8, 2);
					}
					else {
						$yearmonth = date("Y-m", time()); 
						$day = -1; 
					}
					?>
					<select name="rec_date[]" class="form-control">
						<option value="0"> </option>
					<?php
						for($j=1; $j<=31; $j++){
							if($month==2 && $j==29)
								break;
							else if(($month==4||$month==6||$month==9||$month==11) && $j==31)
								break; ?>
							<option value="<?php echo $yearmonth . "-" . (fixZero($j, 2)); ?>"<?php echo $day==$j? " selected":""; ?>><?php echo $yearmonth . "-" . (fixZero($j, 2)); ?></option>
				<?php	}
					?>
				</td>
                <td><?php 
				if($rec!=false){
				$fromHour = strval(substr($rec['fromTime'], 0, 2)); 
				$fromMin = strval(substr($rec['fromTime'], 3, 2)); 
				$toHour = strval(substr($rec['toTime'], 0, 2)); 
				$toMin = strval(substr($rec['toTime'], 3, 2)); 
				}
				?>
				<div class="form-inline">
				<div class="form-group">
					<select name="rec_from_hr[]" class="form-control">
						<option value="-1"> </option>
					<?php
						for($j=0; $j<24; $j++){ ?>
						<option value="<?php echo fixZero($j, 2); ?>"<?php echo $rec!=false? ($fromHour==$j? " selected":"") :""; ?>><?php echo (fixZero($j, 2)); ?></option>
				<?php	}
					?>
					</select>
					<label>:</label>
					<select name="rec_from_min[]" class="form-control">
                    <?php
                        for($j=0; $j<60; $j+=5){ ?>
                        <option value="<?php echo fixZero($j, 2); ?>"<?php echo $rec!=false? ($fromMin==$j? " selected":"") :""; ?>><?php echo (fixZero($j, 2)); ?></option>
                <?php   }   
                    ?>  

					</select>
					<label> ~ </label>
					<select name="rec_to_hr[]" class="form-control">
                        <option value="-1"> </option>
                    <?php
                        for($j=0; $j<24; $j++){ ?>
                        <option value="<?php echo fixZero($j, 2); ?>"<?php echo $rec!=false? ($toHour==$j? " selected":"") :""; ?>><?php echo (fixZero($j, 2)); ?></option>
                <?php   }   
                    ?>  

					</select>
					<label>:</label>
					<select name="rec_to_min[]" class="form-control">
                    <?php
                        for($j=0; $j<60; $j+=5){ ?>
                        <option value="<?php echo fixZero($j, 2); ?>"<?php echo $rec!=false? ($toMin==$j? " selected":"") :""; ?>><?php echo (fixZero($j, 2)); ?></option>
                <?php   }   
                    ?>  

					</select>
				</div>	
				</div>
				
				</td>
                <td class="text-left">
				<input type="text" class="form-control" name="rec_text[]" value="<?php echo $rec['content']; ?>">
				</td>
            </tr>
			<?php } ?>
        </table>
<p class="text-center"><input type="submit" class="btn btn-success" value="確認送出"></p>
</form>

</div>
<?php }
else{
	setlog($DBmain, "warning", "Try to get diary that dont have auth. ", $_SESSION['loginID']); 
	locate($URLPv . "index.php"); 
}
?>
