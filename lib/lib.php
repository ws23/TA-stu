<?php	/* standard function definitions */

/* To get the IP address of client */
function getIP() {
	if(!empty($_SERVER['REMOTE_ADDR']))
		$ip = $_SERVER['REMOTE_ADDR'];
	if(!empty($_SERVER['HTTP_X_FORWADED_FOR'])) {
		$ips = explode(",", $_SERVER['HTTP_X_FORWARDED_FOR']); 
		if($ip) {
			array_unshift($ips, $ip); 
			$ip = false; 
		}
		for($i=0; $i<count($ips); $i++) {
			if(!eregi("^(10|172.16|192.168).", $ips[$i])) {
				$ip = $ips[$i]; 
				break;	
			}
		}
	}
	return $ip; 
}


function alert($msg){
	echo "<script>\n"; 
	echo "alert('{$msg}'); \n"; 
	echo "</script>"; 
}

function locate($url){
	echo "<script>\n"; 
	echo "window.location.href = \"{$url}\";\n";
	echo "</script>"; 
}
/* Log, Need Database (MYSQL) */
function setLog($DBlink, $type="info", $content, $user=""){
	$ip = getIP(); 
	$url = $_SERVER['REQUEST_URI'];
	$user = $DBlink->real_escape_string($user); 
	$DBlink->query("INSERT INTO `log`(`type`, `msg`, `user`, `site`, `IP`) VALUES ('{$type}', '{$content}', '{$user}', '{$url}', '{$ip}'); ");
}

/* set Login */
function setLogin($DBlink, $user){
	$ip = getIP(); 
	$result = $DBlink->query("SELECT * FROM `login` WHERE `user` = '{$user}'; "); 
	if($result->num_rows>0){
		$DBlink->query("UPDATE `login` SET `IP`= '{$ip}', `time` = CURRENT_TIMESTAMP WHERE `user` = '{$user}'; "); 
	}
	else{
		$DBlink->query("INSERT INTO `login`(`user`, `IP`) VALUES('{$user}', '{$ip}'); "); 
	
	}
}

/* POP3 Auth */
function CheckPOP3($server, $user, $pwd, $port = 110){
    //若任一欄位為空白則無效
    if (empty($server) || empty($user) || empty($pwd))
        return false;
    //連結POP3 Server
    $fs = fsockopen ($server, $port, $errno, $errstr, 5);
    //檢查是否連線
    if (!$fs)
        return false;
    //connected...
    $msg = fgets($fs,256);
    //step 1. 傳送帳號
    fputs($fs, "USER $user\r\n");
    $msg = fgets($fs,256);
    if (strpos($msg,"+OK")===false)
        return false;
    //step 2. 傳送密碼
    fputs($fs, "PASS $pwd\r\n");
    $msg = fgets($fs,256);
    if (strpos($msg,"+OK")===false)
        return false;
    //step 3.通過認證 QUIT
    fputs($fs, "QUIT \r\n");
    fclose($fs);
    return true;
}

