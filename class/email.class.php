<?php
//require("../../mail/class.phpmailer.php");

class email {

	var $to;
	var $cc;
	var $subject;
	var $message;
	var $headers;
	
	function email() {
		
	}
	
	function setTo($to) {
		$this->to = $to;
	}
	function setCc($cc) {
		$this->cc = $cc;
	}

	function setSubject($subject) {
		$this->subject = $subject;
	}
	function setMessage($message) {
		$this->message = $message;
	}
	function setHeaders($headers) {
		$this->headers = $headers;
	}
	
	function getTo() {
		return $this->to;
	}
	function getCc() {
		return $this->cc;
	}
	function getSubject() {
		return $this->subject;
	}
	function getMessage() {
		return $this->message;
	}
	function getHeaders() {
		return $this->headers;
	}
	
	function setDefaultHeaders() {
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$headers .= "From:Athos Gestão<contato@contrathos.com.br>";
		$headers .= $this->getCc() ? "\r\nCc: ".$this->getCc()."" : "";
		$headers .= "\n";

		$this->setHeaders($headers);
	}
	
	function send() {
		/*
		$this->setDefaultHeaders();
		if ($this->getTo() && $this->getSubject() && $this->getMessage()) {
			return @mail($this->getTo(), $this->getSubject(), $this->getMessage(), $this->getHeaders());
		}
		return false;
		*/

		$mail = new PHPMailer();
		$mail->IsSMTP(); //MANDAR VIA SMTP
		$mail->Host = "mail.contrathos.com.br"; //SERVIDOR DE SMTP, USE smtp.SeuDominio.com OU smtp.hostsys.com.br 
		$mail->SMTPAuth = true; //SMTP AUTENTICADO
		$mail->Username = "contato@contrathos.com.br"; //NOME DE USUÁRIO PARA SMTP AUTENTICADO
		$mail->Password = "mudar123"; //SENHA DO USUÁRIO PARA SMTP AUTENTICADO
		$mail->From = "contrathos@contrathos.com.br"; //E-MAIL DO REMETENTE 
		$mail->FromName = 'Contrathos'; //NOME DO REMETENTE
		$site_acesso = "http://www.contrathos.com.br/scaixa";
		$mail->WordWrap = 50; // ATIVAR QUEBRA DE LINHA
		$mail->IsHTML(true); //ATIVA MENSAGEM NO FORMATO HTML

		$mail->AddAddress($this->getTo()); //E-MAIL DO DESINATÁRIO, NOME DO DESINATÁRIO 

		$mail->Subject = $this->getSubject(); //ASSUNTO DA MENSAGEM

		$mail->Body = $this->getMessage();

		return $mail->Send();
		
	}
	
}

?>