<?
$pageTitle = "Fale Conosco";
include "lib/header.inc.php";
$obrig = '<span class="obrig"> *</span>';

if($_POST){
	$dadosform = '';
	$mensagem  = '';
	$dadoshist = '';
	$mensagem .= '<b>Assunto</b>: '.$_POST['assunto']."<br />\n";
	$mensagem .= '<b>Mensagem</b>: '.$_POST['mensagem']."<br />\n";
	if($cLOGIN->bOK == 0) {
		$mensagem .= '<b>Nome</b>: '.$_POST['nome']."<br />\n";
		$mensagem .= '<b>E-Mail</b>: '.$_POST['email']."<br />\n";
		$dadosform .= ' [Nome:'.$_POST['nome'].'] ';
		$dadosform .= ' [E-Mail:'.$_POST['email'].'] ';
	}else{
		$mensagem .= '<b>Nome</b>: '.$cLOGIN->cUSUARIO."<br />\n";
		$mensagem .= '<b>E-Mail</b>: '.$cLOGIN->cLOGIN."<br />\n";
		$dadosform .= ' [Nome:'.$cLOGIN->cUSUARIO.'] ';
		$dadosform .= ' [E-Mail:'.$cLOGIN->cLOGIN.'] ';
	}
	$dadosform .= ' [Assunto:'.$_POST['assunto'].'] ';
	$dadosform .= ' [Mensagem:'.$_POST['mensagem'].'] ';
	$dadoshist .= '<b>'.$_POST['assunto'].'</b><br />'.$_POST['mensagem'];
	
	$email = new email();
	$email->setTo('previ@athosgestao.com.br.');
	$email->setSubject($_POST['assunto']);
	$email->setMessage($mensagem);
	$email->send();

	// LOG e HISTORICO // -------------------------------------------- //
	$cLOGIN->insert_log(4,7,'Fale Conosco - '.$dadosform);
	if($cLOGIN->bOK == 1) {
		$proposta = 0;
		$db->query="select COD_PPST from proposta 
								where proponente_ppst = '".mysql_real_escape_string($cLOGIN->iID)."' 
								AND situacao_ppst <= 12";
		$db->query();
		if($db->qrcount>0){
			$proposta = $db->qrdata[0]['COD_PPST'];
		}
		$cLOGIN->insert_history($proposta,3,$dadoshist);
	}
}

$logado = ($cLOGIN->bOK == 0)?'false':'true';

?>
<script language="JavaScript" src="./js/diversos.js"></script>
<script language="JavaScript">
function validarForm(_logado){
	if(!vTexto('assunto')) return false;
	if(!vTexto('mensagem')) return false;
	if(!_logado){
		if(!vTexto('nome')) return false;
		if(!vEmail('email')) return false;
	}
	return true;
}
</script>

<form name="contato" method="post" action="<?=$php_self;?>"onSubmit="">
<div class="quadroInterno">
	<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
	<div class="quadroInternoMeio">

	<div style="float: left; width:550px;">
		<table cellpadding=0 cellspacing=5 border=0>
        <tr>
          <td align="right">Assunto:<?=$obrig;?></td>
          <td align="left">
            <input type="text" name="assunto" id="assunto" style="width:300px;" maxlength="200" onFocus="this.select();">
          </td>
        </tr>
        <tr>
          <td align="right" valign="top">Mensagem:<?=$obrig;?></td>
          <td align="left">
            <textarea name="mensagem" id="mensagem" style="width:450px; height:120px;"></textarea>
          </td>
        </tr>
      <? if($cLOGIN->bOK == 0) { ?>
        <tr>
          <td align="right">Nome:<?=$obrig;?></td>
          <td align="left">
            <input type="text" name="nome" id="nome" style="width:300px;" maxlength="200" onFocus="this.select();">
          </td>
        </tr>
        <tr>
          <td align="right">E-Mail:<?=$obrig;?></td>
          <td align="left">
            <input type="text" name="email" id="email" style="width:300px;" maxlength="200" onFocus="this.select();">
          </td>
        </tr>
	    <? } ?>
        <tr>
          <td align="right">&nbsp;</td>
          <td align="left"><input type="image" name="btEnviar" id="btEnviar" src="images/buttons/bt_enviar.gif" value="Enviar" class="im" /></td>
        </tr>
      </table>
		</div>

</div>
	<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
</form>
<?
include "lib/footer.inc.php";
?>