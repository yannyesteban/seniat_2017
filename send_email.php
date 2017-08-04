<?php

define ("MAIL_HOST","172.16.16.91");
define ("MAIL_FROM","cef@seniat.gob.ve");
define ("MAIL_PORT",25);

define ("MAIL_USERNAME","cef");
define ("MAIL_PASSWORD","Cefseniat20151");
define ("MAIL_NAME","Centro de Estudios Fiscales");
/*
define ("MAIL_HOST","smtp.gmail.com");
define ("MAIL_FROM","yannyesteban@gmail.com");
define ("MAIL_PORT",465);



define ("MAIL_USERNAME","cef@gmail.com");
define ("MAIL_PASSWORD","pass1234");
define ("MAIL_NAME","Centro de Estudios Fiscales");

*/

require_once('../../PHPMailer-5.2.23/PHPMailerAutoload.php');
class send_email{
	private $mail = false;
	public $error = false; 
	
	public function __construct(){
		$this->mail             = new PHPMailer();
		
		$this->mail->IsSMTP(); // telling the class to use SMTP
		$this->mail->Host       = MAIL_HOST; // SMTP server
		$this->mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
												   // 1 = errors and messages
												   // 2 = messages only
		$this->mail->SMTPAuth   = true;                  // enable SMTP authentication
		$this->mail->SMTPSecure = "ssl";
		//$mail->SMTPSecure = "ssl";//"ssl";                 // sets the prefix to the servier
		//$mail->Host       = "172.16.16.91";      // sets GMAIL as the SMTP server
		//$mail->Port       = 465;                   // set the SMTP port for the GMAIL server
		$this->mail->Port       = MAIL_PORT;                   // set the SMTP port for the GMAIL server
		$this->mail->Username   = MAIL_USERNAME;  // GMAIL username
		$this->mail->Password   = MAIL_PASSWORD;            // GMAIL password
		$this->mail->From = MAIL_USERNAME;
		$this->mail->FromName = MAIL_NAME;
		$this->mail->SetFrom(MAIL_FROM, MAIL_NAME);
				
				
				
				
	}
	
	
	
	public function send($correo, $nombre, $asunto, $mensaje){
		
		$this->mail->ClearAddresses();
		
		if(!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
			$this->error = 2;
			return false;	
		}// end if
		
		
		$this->mail->Subject = $asunto;
		$this->mail->MsgHTML($mensaje);
		//$mail->Body = $body;
		
		$this->mail->AddAddress($correo, $nombre);
		
		if($this->mail->Send()) {		
			return true;
					
		}else{
			$this->error = 1;
			return false;
		}// end if
	
	}// end fucntion
	
}




?>