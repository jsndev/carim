<?
$pageTitle = "Erro";
include "lib/header.inc.php";

$mensagem = new mensagens();
if ($session->getMessage()) {
	$mensagem->setMensagem($session->getMessage(true), MSG_ERRO);
} else {
	$mensagem->setMensagem('ÁREA RESTRITA', MSG_ERRO);
}
$cLOGIN->insert_log(1,1,'ÁREA RESTRITA');
?>
<table cellpadding=0 cellspacing=0 border=0 width="100%" height="300"><tr>
  <td style="padding:15px;" valign="top" align="center">
    <?=$mensagem->getMessageBox();?>
  </td>
</tr></table>
<?
include "lib/footer.inc.php";
?>