<?php
require("mail/class.phpmailer.php");
$mail = new PHPMailer();
$mail->IsSMTP(); //MANDAR VIA SMTP
$mail->Host = "mail.athosgestao.com.br"; //SERVIDOR DE SMTP, USE smtp.SeuDominio.com OU smtp.hostsys.com.br 
$mail->SMTPAuth = true; //SMTP AUTENTICADO
$mail->Username = "previ@athosgestao.com.br"; //NOME DE USUÁRIO PARA SMTP AUTENTICADO
$mail->Password = "mudar123"; //SENHA DO USUÁRIO PARA SMTP AUTENTICADO
$mail->From = "previ@athosgestao.com.br"; //E-MAIL DO REMETENTE 
$mail->AddCC("previ@athosgestao.com.br",'Previ'); //E-MAIL DO REMETENTE 
$mail->FromName = 'Athos Gestão'; //NOME DO REMETENTE
$site_acesso = "http://www.contrathos.athosgestao.com.br/carim";
$mail->WordWrap = 50; // ATIVAR QUEBRA DE LINHA
$mail->IsHTML(true); //ATIVA MENSAGEM NO FORMATO HTML
?>