<?php	
	$admins = array("410121021", "fen19"); 

		if(isset($_SESSION['loginID']))
			locate($URLPv . "index.php?module=select"); 
		else if(isset($_POST['stuID']) && isset($_POST['stuPW'])) {
			if(CheckPOP3($_POST['mailserver'] . ".ndhu.edu.tw", $_POST['stuID'], $_POST['stuPW'])){
				$_SESSION['loginID'] = $_POST['stuID']; 
				setLog($DBmain, "info", "Login Sucess", $_SESSION['loginID']); 
				setLogin($DBmain, $_SESSION['loginID']);
				$_SESSION['loginToken'] = genToken($DBmain, $_SESSION['loginID']); 
				foreach( $admins as $admin ){
					if($_SESSION['loginID'] == $admin){
						setLog($DBmain, "info", "Admin Login", $_SESSION['loginID']); 
						$_SESSION['admin'] = $_SESSION['loginID']; 
					}
					locate($URLPv . "index.php"); 
				}
			}
			else{ 
				setLog($DBmain, "warning", "Login Failed.", $_POST['stuID']); 
				alert("Login Failed! Please try again. "); 
				locate($URLPv . "index.php"); 
			}
		}
		else {  ?>
	<div class="login">
		<form action="index.php" method="post">
			<div class="form-horizontal">
				<div class="form-group">
					<label class="control-label col-sm-2">Email: </label>
					<div class="col-sm-4">
						<input type="text" name="stuID" placeholder="NDHU mail" class="form-control" /> 
					</div>
					<div class="col-sm-6">
						<select class="form-control" name="mailserver">
							<option value="ems">@ems.ndhu.edu.tw</option>
							<option value="mail">@mail.ndhu.edu.tw</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">Password: </label>
					<div class="col-sm-10">
						<input type="password" name="stuPW" placeholer="Password" class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<input type="submit" value="Login" class="form-control btn btn-success"/>
				</div>
			</div>
		</form>
	</div>

<?php	}	?>
