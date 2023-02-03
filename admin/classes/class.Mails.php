<?php
/*	wscms/classes/class.Mails.php v.3.5.4. 04/06/2019 */

class Mails extends Core {

	public function __construct() 	{
		parent::__construct();
		}
		
	public static function sendEmail($address,$subject,$message,$messase_plain,$opt) {
		$optDef = array('sendDebug'=>0,'sendDebugEmail'=>'','fromEmail'=>'','fromLabel'=>'','attachments'=>'');
		$opt = array_merge($optDef,$opt);
		if (self::$globalSettings['use send mail class'] == 1) {
			self::sendEmailClass($address,$subject,$message,$messase_plain,$opt);		
			} else if (self::$globalSettings['use send mail class'] == 2) {
				self::sendMailPHPMAILER($address,$subject,$message,$messase_plain,$opt);
				} else {
					self::sendEmailPHP($address,$subject,$message,$messase_plain,$opt);
					}
		}
		
		
		
	public static function sendEmailClass($address,$subject,$message,$messase_plain,$opt) {
		$optDef = array('sendDebug'=>0,'sendDebugEmail'=>'','fromEmail'=>'','fromLabel'=>'','attachments'=>array());
		$opt = array_merge($optDef,$opt);
		if ($address != '') {		
			
			$transport = '';
			switch (self::$globalSettings['mail server']) {
				case 'SMTP':
					$transport = new Swift_SmtpTransport(self::$globalSettings['SMTP server'], self::$globalSettings['SMTP port']);
					if (isset(self::$globalSettings['SMTP username']) && self::$globalSettings['SMTP username'] != '') $transport->setUsername(self::$globalSettings['SMTP username']);
					if (isset(self::$globalSettings['SMTP password']) && self::$globalSettings['SMTP password'] != '') $transport->setPassword(self::$globalSettings['SMTP password']);
				break;
				
				default:
					$transport = new Swift_SendmailTransport(self::$globalSettings['sendmail path']);
				break;
				}
			try {
				$fromEmail = $opt['fromEmail'];
				$fromLabel = $opt['fromLabel'];
				$mailer = new Swift_Mailer($transport);
				$message = (new Swift_Message($subject));
				$message->setFrom($fromEmail,$fromLabel);
				$message->setTo($address);
				$message->setBody($message,'text/html');
				$message->addPart($messase_plain, 'text/plain');
				
				if ($opt['sendDebug'] == 1) {
					if ($opt['sendDebugEmail'] != '') $message->setBcc($opt['sendDebugEmail']);
					}
				  
				/* allegati */
				if (is_array($opt['attachments']) && count($opt['attachments']) > 0) {			
					foreach ($opt['attachments'] AS $key=>$value) {
						$message->attach(
							Swift_Attachment::fromPath($value['filename']->setFilename($value['title']))
							);
						}
					}
				  			 
				 try {
					$mailer->send($message);
					} catch (\Swift_TransportException $e) {
						Core::$resultOp->error = 1;
						//echo $e->getMessage();
						} 
			} catch (\Swift_TransportException $e) {
				Core::$resultOp->error = 1;
			} catch (Exception $e) {
				Core::$resultOp->error = 1;
			}			
			} else {
				Core::$resultOp->error = 1;
				}
		}
		
	/* versione PHP MAILER */
	public static function sendMailPHPMAILER($address,$subject,$content,$text_content,$opt) {
		include_once("class.phpmailer.php");
		include_once("class.pop3.php");
		include_once("class.smtp.php");
		$optDef = array('sendDebug'=>0,'sendDebugEmail'=>'','fromEmail'=>'n.d','fromLabel'=>'n.d','attachments'=>array(),'classMailer'=>'');	
		$opt = array_merge($optDef,$opt);	
	
		$mail = new PHPMailer();
		$mail->SetFrom($opt['fromEmail'],$opt['fromLabel']);
		$mail->IsHTML(true);
		$mail->CharSet = 'UTF-8';
		$mail->Subject = $subject;
		$mail->AltBody = $text_content;
		$mail->MsgHTML($content);	
		$mail->AddAddress($address);				
		if ($opt['sendDebug'] == 1) {
			if ($opt['sendDebugEmail'] != '') $mail->AddBCC($opt['sendDebugEmail']);
			}
			
		/* allegati */
		if (is_array( $opt['attachments']) && count($opt['attachments']) > 0 ) {			
			foreach ($opt['attachments'] AS $key=>$value) {
				$mail->addAttachment($value['filename'],$value['title']);    // Optional name
				}
			}
		
		//$mail->SMTPDebug  = 2;

		if (!$mail->Send()) {
			Core::$resultOp->error = 1;
			//Core::$resultOp->message = $mail->ErrorInfo;
		} else {
			Core::$resultOp->error = 0;
		}
	}

	public static function sendEmailPHP($address,$subject,$content,$text_content,$opt) {
		$optDef = array('sendDebug'=>0,'sendDebugEmail'=>'','fromEmail'=>'n.d','fromLabel'=>'n.d','attachments'=>array());	
		$opt = array_merge($optDef,$opt);	
		$mail_boundary = "=_NextPart_" . md5(uniqid(time()));	
		$headers = "From: ".$opt['fromLabel']." <".$opt['fromEmail'].">\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: multipart/alternative;\n\tboundary=\"$mail_boundary\"\n";
		$headers .= "X-Mailer: PHP " . phpversion();
		if ($opt['sendDebug'] == 1) {
			if ($opt['sendDebugEmail'] != '') $headers = "Bcc: ".$opt['sendDebugEmail']."\n";
			}	
		// Costruisci il corpo del messaggio da inviare
		$msg = "This is a multi-part message in MIME format.\n\n";
		$msg .= "--$mail_boundary\n";
		$msg .= "Content-Type: text/plain; charset=\"UTF-8\"\n";
		$msg .= "Content-Transfer-Encoding: 8bit\n\n";
		$msg .= $text_content; // aggiungi il messaggio in formato text
 
		$msg .= "\n--$mail_boundary\n";
		$msg .= "Content-Type: text/html; charset=\"UTF-8\"\n";
		$msg .= "Content-Transfer-Encoding: 8bit\n\n";
		$msg .= $content;  // aggiungi il messaggio in formato HTML
 
		// Boundary di terminazione multipart/alternative
		$msg .= "\n--$mail_boundary--\n";
 		$sender = $opt['fromEmail'];
		// Imposta il Return-Path (funziona solo su hosting Windows)
		ini_set("sendmail_from", $sender); 
		// Invia il messaggio, il quinto parametro "-f$sender" imposta il Return-Path su hosting Linux
		$result = mail($address,$subject,$msg,$headers, "-f$sender");		
		if (!$result) {   
    		//echo "Error";
    		Core::$resultOp->error = 1;  
			} else {
    			//echo "Success";
    			Core::$resultOp->error = 0;
				}
		}

	public static function parseEmailContent($content,$replaces,$opt) {
			$optDef = array('others'=>array());
			$opt = array_merge($optDef,$opt);	
			$content = preg_replace('/%SITENAME%/',Config::$globalSettings['site name'],$content);	
			if (isset($replaces['name'])) $content = preg_replace('/%NAME%/',$replaces['name'],$content);			
			if (isset($replaces['surname'])) $content = preg_replace('/%SURNAME%/',$replaces['surname'],$content);
			if (isset($replaces['company'])) $content = preg_replace('/%COMPANY%/',$replaces['company'],$content);
			if (isset($replaces['email'])) $content = preg_replace('/%EMAIL%/',$replaces['email'],$content);
			if (isset($replaces['telephone'])) $content = preg_replace('/%TELEPHONE%/',$replaces['telephone'],$content);
			if (isset($replaces['subject'])) $content = preg_replace('/%SUBJECT%/',$replaces['subject'],$content);
			if (isset($replaces['object'])) $content = preg_replace('/%OBJECT%/',$replaces['object'],$content);	
			if (isset($replaces['message'])) $content = preg_replace('/%MESSAGE%/',$replaces['message'],$content);
			
			/* altri */
			if (is_array($opt['others']) && count($opt['others']) > 0) {
				foreach ($opt['others'] AS $key=>$value) {
					$content = preg_replace('/'.$key.'/',$value,$content);	
				}
			}		
		return $content;
	}
		
}
?>