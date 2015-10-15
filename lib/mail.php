<?php
require_once('phpmailer/class.phpmailer.php');
// ==================================
// 發送EMAIL～啾咪
// ==================================
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";
$headers .= "From:{$MailSend}\r\n";
$headers .= "X-Mailer: GENEDU";

class Mail{
	//	Set SysPara
	//	[In]		$address	收件者信箱
	//	[In]		$name		收件者名稱
	//	[In]		$subject	信件標題
	//	[In]		$content	信件內容
	//	[In]		$sender		寄件者信箱
	//	[In]		$senderName	寄件者名稱
	//	[Return]	True	mail成功
	//				False	mail失敗
	public static function sendmail($address, $name, $subject, $content, $sender=$MailSend, $senderName=$MailSendName, $header="")
	{
		if($address != "" && $subject != "" && $content != "" )
		{
			global $SMTPSec,$SendMailfrom, $SMTPDomainName,$headers, $SendMail_Acc, $SendMail_pwd, $SendMail_Port;
			global $MailHost, $MailProt, $MailUser, $MailPass;  
			
			mb_internal_encoding('UTF-8');
			$subject = mb_encode_mimeheader($subject, 'UTF-8');
			
			if($header != ""){ $headers = $header; }
			$ret = mail($address,$subject,$content,$headers);
			
			mb_internal_encoding('UTF-8');
			$subject = mb_encode_mimeheader($subject, 'UTF-8');
			$mail	= new PHPMailer();
			$body  = $content;
			$mail->CharSet = "utf-8";
			
			$mail->IsSMTP();
			$mail->SMTPDebug = 1;
			$mail->SMTPAuth   = true;                  // enable SMTP authentication
			$mail->Host       = $MailHost;     // sets SMTP server
			$mail->Port       = $MailPort;     // set the SMTP port

			$mail->Username   = $MailUser; 
			$mail->Password   = $MailPass; 

			$mail->From       = $sender;
			$mail->FromName   = $sendName;
			$mail->Subject    = $subject;
			$mail->AltBody    = $content; //Text Body
			$mail->WordWrap   = 50; // set word wrap
			$mail->Body    = $content;

	//		$mail->AddReplyTo("email", "name");

			//$mail->AddAttachment("/path/to/file.zip");             // attachment
			//$mail->AddAttachment("/path/to/image.jpg", "new.jpg"); // attachment

			$mail->AddAddress($address, $name);

			$mail->IsHTML(true); // send as HTML
			
			if( !$mail->Send())
			//if(!$ret)
			{
				$rret = "0";
				//LogBook::Log("email address:".$address.":傳送失敗，msg:"."$mail->ErrorInfo");
			}else{
				$rret = "1";
				//LogBook::Log("email address:".$address.":傳送成功，msg:"."$content");
			}
		}else
		{
			$rret = "0";
			//LogBook::Log("email address:".$address.":傳送失敗，msg:"."$mail->ErrorInfo");
		}
		return $rret;
	}
}

//檢查E-mail真實性
function check_email(&$email, $strict=0)
{
	$email = strtolower($email);

	if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*((\.[a-z]{2,3})|(\.info)|(\.name))$", $email)) {
	return false;
	}
	else {
	if($strict)
	{
	list ($Username, $Domain) = split ("@", $email);

	if(function_exists('getmxrr') && getmxrr($Domain, $MXHost, $Weight))
	{
	foreach ( $Weight as $mxid => $preference )
	{
	if(trim($preference) == '') {
	$preference = 10000000 + $row_counter;
	} else {
	$preference = $preference * 100;
	}

	while(isset($MXRecords[$preference])) {
	$preference = $preference + 1;
	}

	$MXRecords[$preference] = $MXHost[$mxid];
	}
	ksort($MXRecords);
	$ConnectAddress = array_shift($MXRecords);
	} else {
	$ConnectAddress = $Domain;
	}
	$Connect = fsockopen ( $ConnectAddress, 25 );

	if ($Connect) {

	if (ereg("^220", $Out = fgets($Connect, 1024))) {

	fputs ($Connect, "HELO $HTTP_HOST\r\n");
	$Out = fgets ( $Connect, 1024 );
	fputs ($Connect, "MAIL FROM: <{$email}>\r\n");
	$From = fgets ( $Connect, 1024 );
	fputs ($Connect, "RCPT TO: <{$email}>\r\n");
	$To = fgets ($Connect, 1024);
	fputs ($Connect, "QUIT\r\n");
	fclose($Connect);

	if (!ereg ("^250", $From) || !ereg ( "^250", $To )) {
	return false;
	}
	} else {
	return false;
	}
	} else {
	return false;
	}
	} # End of strict check

	return true;
	}
}

?>
