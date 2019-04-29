<?php
$iREQ_AUT=1;
$aUSERS_PERM[]=8;
$pageTitle = "Avaliação";
include "lib/header.inc.php";

$cod_usua=$_GET['cod_usuario'];
$avaliador=$_GET['aval'];

$db->query="Select * from usuario where cod_usua='".$cod_usua."'";
$db->query();
if($db->qrcount>0){
$sql_procurado = $db->query;
$nome_prop=$db->qrdata[0]['NOME_USUA'];
$num_ppst=$db->qrdata[0]['ID_LSTN'];
}

$db->query="Select * from proponente where cod_proponente='".$cod_usua."'";
$db->query();
if($db->qrcount>0){
$cod_ppst=$db->qrdata[0]['COD_PPST'];
}
$db->query="Select * from imagem where cod_ppst='".$cod_ppst."' and categoria='3'";
$db->query();
if($db->qrcount>0){
$iptu=$db->qrdata[0]['NOME'];
}
$db->query="Select * from imagem where cod_ppst='".$cod_ppst."' and categoria='2'";
$db->query();
if($db->qrcount>0){
$matricula=$db->qrdata[0]['NOME'];
}
$db->query="Select * from imagem where cod_ppst='".$cod_ppst."' and categoria='4'";
$db->query();
if($db->qrcount>0){

$informacao=$db->qrdata[0]['NOME'];
}
$db->query="Select * from avaliador where cod_aval='".$avaliador."'";
$db->query();
if($db->qrcount>0){

$emailaval=$db->qrdata[0]['EMAIL_AVAL'];
$nomeaval=$db->qrdata[0]['APELIDO_AVAL'];
}

$db->query="Update avaliacao Set 
							DTPEDIDO= now()
							Where cod_ppst='".$cod_ppst."'
							";
$db->query();

$db->query="Select endereco_imov, nrendereco_imov, cpendereco_imov, cod_municipio, cod_uf, cod_logr from imovel where cod_ppst='".$cod_ppst."'";
$db->query();
if($db->qrcount>0){

$prop_ender=$db->qrdata[0]['endereco_imov'];
$prop_num=$db->qrdata[0]['nrendereco_imov'];
$prop_compl=", ".$db->qrdata[0]['cpendereco_imov'];
$cod_mun=$db->qrdata[0]['cod_municipio'];
$cod_log=$db->qrdata[0]['cod_logr'];
$prop_uf=$db->qrdata[0]['cod_uf'];
}
$db->query="Select nome_municipio from municipio where cod_municipio='".$cod_mun."'";
$db->query();
if($db->qrcount>0){

$prop_cidade=$db->qrdata[0]['nome_municipio'];
}
$db->query="Select desc_logr from logradouro where cod_logr='".$cod_log."'";
$db->query();
if($db->qrcount>0){

$prop_lograd=$db->qrdata[0]['desc_logr'];
}
$db->query="Select contato,observacao from avaliacao where cod_ppst='".$cod_ppst."'";
$db->query();
if($db->qrcount>0){

$contato=$db->qrdata[0]['contato'];
$obs=$db->qrdata[0]['observacao'];
}
  echo "<b>Enviando E-mail................................<br><br></b>";
  echo "<b>Msg email: <br><br><br></b>";


  /*
   *  Class mime_mail
   *  Original implementation by Sascha Schumann <sascha@schumann.cx>
   *  Modified by Tobias Ratschiller <tobias@dnet.it>:
   *      - general code clean-up
   *      - separate body- and from-property
   *      - killed some mostly un-necessary stuff
   *  Modified by Patrick Polzer <sky@nachtwind.net>:
   *      - added "Content-Disposition"-MIME-Statement for all attachments
   *        (does not affect body text)
   */

  class mime_mail
   {
   var $parts;
   var $to;
   var $from;
   var $headers;
   var $subject;
   var $body;

   /*
    *     void mime_mail()
    *     class constructor
    */
   function mime_mail()
    {
    $this->parts = array();
    $this->to = "cinthia@athosgestao.com.br";
    $this->from = "";
    $this->subject = "Teste Img";
    $this->body = "Oi Ola";
    $this->headers = "teste4testesetses";
    }

   /*
    *     void add_attachment(string message, [string name], [string ctype])
    *     Add an attachment to the mail object
    */
   function add_attachment($message, $name = "images/rodape.jpg", $ctype = "application/octet-stream")
    {
    $this->parts[] = array (
                            "ctype" => $ctype,
                            "message" => $message,
                            "encode" => $encode,
                            "name" => $name
                            );
    }

  /*
   *      void build_message(array part=
   *      Build message parts of an multipart mail
   */
  function build_message($part)
   {
   $message = $part["message"];
   $message = chunk_split(base64_encode($message));
   $encoding = "base64";
   if ($part["name"]!="") {
      $dispstring = "Content-Disposition: attachment; filename=\"$part[name]\"\n";
   }
   return "Content-Type: ".$part["ctype"].
                          ($part["name"]?"; name = \"".$part["name"]."\"" : "").
                          "\nContent-Transfer-Encoding: $encoding\n".$dispstring."\n$message\n";
   }

  /*
   *      void build_multipart()
   *      Build a multipart mail
   */
  function build_multipart()
   {
   $boundary = "b".md5(uniqid(time()));
   $multipart = "Content-Type: multipart/mixed; boundary=$boundary\n\nThis is a MIME encoded message.\n\n--$boundary";

   for($i = sizeof($this->parts)-1; $i >= 0; $i--)
      {
      $multipart .= "\n".$this->build_message($this->parts[$i])."--$boundary";
      }
   return $multipart.= "--\n";
   }

  /*
   *      void send()
   *      Send the mail (last class-function to be called)
   */
  function send()
   {
   $mime = "";
   if (!empty($this->from))
      $mime .= "From: Previ <previ@athosgestao.com.br>\n";
 	  $mime .= "CC: previ@athosgestao.com.br\n";
   if (!empty($this->headers))
      $mime .= $this->headers."\n";
		//echo  "<br><br>HEADER:".$this->headers;
   echo $this->body."<br><br><br>";
   if (!empty($this->body))
      $this->add_attachment($this->body, "", "text/html");//text/plain
   $mime .= "MIME-Version: 1.0\n".$this->build_multipart();
   $success = mail($this->to, $this->subject, "", $mime);
//	echo 'to:'.$this->to;
	//echo 'mime:'.$mime;
   if (!$success) {
        echo '<h2>Falha ao Enviar E-mail!!</h2>';
   } else {
        echo '<h2>E-mail Enviado com sucesso para: '.$this->to;
   }
   }
  }; // end of class


   //Example usage
   $attachment2 = fread(fopen("imagens_previ/".$cod_ppst."/".$iptu, "r"), filesize("imagens_previ/".$cod_ppst."/".$iptu));
   $attachment =fread(fopen("imagens_previ/".$cod_ppst."/".$matricula, "r"), filesize("imagens_previ/".$cod_ppst."/".$matricula));
   $attachment3 =fread(fopen("imagens_previ/".$cod_ppst."/".$informacao, "r"), filesize("imagens_previ/".$cod_ppst."/".$informacao));
   
   $mail = new mime_mail();
   $mail->from = "Athos Gestão e Serviço";
   $mail->headers = "Errors-To: imovelpan@athosgestao.com.br";
   $mail->to = $emailaval;
   $mail->subject = "PREVI - ".$num_ppst." - ".$nome_prop;
   if($obs!=''){
   $mail->body = "
   
       <b>".$nomeaval.",</b><br><br>
	   Segue em anexo, documento do proponente em epígrafe, para elaboração do Laudo de Avaliação.<br>
	   <br>
	   Endereço: ".$prop_lograd." ".$prop_ender.", ".$prop_num."<br>
	   Cidade: ".$prop_cidade."-".$prop_uf."<br><br>
	   Contato: ".$contato."<br><br><br>
	   Observações:<br><br>
	   <i>".$obs."</i><br><br><br>
	   
	   Athos Gestão e Serviço Ltda.<br>
	   Tel: (11)3068-7077
   
   ";
   }else{
   $mail->body = "
   
       <b>Sortino/Viviane,</b><br><br>
	   Segue em anexo, documento do proponente em epígrafe, para elaboração do Laudo de Avaliação.<br>
	   <br>
	   Endereço: ".$prop_lograd." ".$prop_ender.", ".$prop_num."<br>
	   Cidade: ".$prop_cidade."-".$prop_uf."<br><br>
	   Contato: ".$contato."<br><br><br>
	   
	   Athos Gestão e Serviço Ltda.<br>
	   Tel: (11)3068-7077
   
   ";
   }
   $mail->add_attachment("$attachment", "".$matricula."", "image/jpeg");
   $mail->add_attachment("$attachment2", "".$iptu."", "image/jpeg");
   $mail->add_attachment("$attachment3", "".$informacao."", "image/jpeg");
   $mail->send();
  

include "lib/footer.inc.php";

?>