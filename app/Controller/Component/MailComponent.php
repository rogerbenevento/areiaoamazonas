<?php
App::import( 'Vendor', 'PHPMailer', array( 'file' => 'PHPMailer'. DS .'class.phpmailer.php' ) );

class MailComponent extends Component
{
////	public $host = 'smtp.vsegura.com.br';
	public $host = 'email-smtp.us-east-1.amazonaws.com';
	public $port = 465;
//	public $username = 'sistema@vsegura.com.br';
//	public $password = 'si1q2w3e4r';
	public $username = 'AKIAJQI64V4YAX2IEEOA';
	public $password = 'Ai64VxVx40LFA/H2bUOlV2mp+jNhPC4tHpzSGIBrkdmo';
//	public $username = 'AKIAJR6KCXWLGH67HMKQ';
//	public $password = 'AnyOtzi5ZfwjFOsmdwprP2WhBGTE5rjJexiliST72PAM';
//	public $username = 'sistema.vidasegura';
//	public $password = 'si1q2w3e4r';
	public $from = 'nfboletosareiaepedra@terra.com.br';
	public $fromName = '';
	public $error = '';
	
	public function send( $to, $titulo, $corpo, $anexo=null ){
		$mail = new PHPMailer();
		$mail->isSMTP();
		$mail->Host = $this->host;
		$mail->SMTPAuth = true;
		$mail->Username = $this->username;
		$mail->Password = $this->password;
		$mail->SMTPDebug = 1;
		$mail->SMTPSecure = 'ssl';
		$mail->Port = $this->port;
		$mail->AddAmazonSESKey($this->username, $this->password); 
		
		// Definição do remetente
		$mail->From = $this->from;
		$mail->FromName = $this->fromName;
		$mail->ConfirmReadingTo = $this->username;
		// Define destinatário
		if(is_array($to)){
			foreach($to as $t):
				if(!empty($t))
				$mail->AddAddress( $t );
			endforeach;
		}else
			$mail->AddAddress( $to );
		// $mail->AddCC( 'email@dominio.com.br', 'Nome do fulano' ); // Copia
		 $mail->AddBCC( 'gerson@hoomweb.com' , 'Nome do fulano' ); // Copia Oculta
		
		// Definindo dados tecnicos da mensagem
		$mail->IsHTML( true );
		$mail->CharSet = 'iso-8859-1'; // Opcional
		if(!empty($anexo)){
			if(is_array($anexo)){
				foreach($anexo as $a):
					if(!empty($a)){
						$mail->AddAttachment($a);
					}
				endforeach;
			}else{
				$mail->AddAttachment($anexo);
			}
		}
		$mail->Subject = $titulo;
		$mail->Body = $corpo;
		
		if ( $mail->Send() ) 
			return true;
		else {
			$this->error = $mail->ErrorInfo;
			return false;
		}
	}
}