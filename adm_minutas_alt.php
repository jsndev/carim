<?php
$iREQ_AUT=1;
$aUSERS_PERM[]=4;
ob_start();

$pageTitle = "Alterar Minuta de Contrato";
include "lib/header.inc.php";

if ((int)$_GET["cod_minu"] > 0) {
	ob_end_flush();
} else {
	ob_end_clean();
	header("Location: adm_minutas.php");
	exit();
}

$oContrato = new contrato();
$mensagem = new mensagens();

if ($crypt->decrypt($_POST["ac"]) == "alterar" && (int)$_GET["cod_minu"] > 0) {
	$aDadosContrato = $oContrato->getMinuta($_GET["cod_minu"]);
	$aDadosContrato[0]["texto_minu"] = $_POST["texto"];
	$oContrato->updMinuta($aDadosContrato[0]);
	
	if ($oContrato->getErrNo() == 0) {
		$mensagem->setMensagem("Os dados da Minuta de Contrato foram alterados com sucesso.", MSG_SUCESSO);
		unset($_POST);
	} else {
		$mensagem->setMensagem("Houve um erro ao alterar dos dados da Minuta de Contrato. Favor tentar novamente.", MSG_ERRO);
	}
}

$aDadosContrato = $oContrato->getMinuta($_GET["cod_minu"]);

if (@count($_POST) == 0) {
	$_POST["texto"] = $aDadosContrato[0]["texto_minu"];
}

$_POST["nome"] = $aDadosContrato[0]["nome_minu"];

?>
<script language="javascript" type="text/javascript" src="js/cadastroMinuta.js"></script>
<form method="post" action="<? echo $php_self; ?>?k=<? echo $crypt->encrypt("cod_minu=".$_GET["cod_minu"]); ?>" class="formPadrao" name="frm1" id="frm1">
<input type="hidden" name="ac" value="<? echo $crypt->encrypt("alterar");?>" />
<div class="quadroInterno">
	<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
	<div class="quadroInternoMeio">
		<? echo $mensagem->getMessageBox(); ?>
		<table cellpadding="0" cellspacing="2" class="tbForm">
			<tr>
				<td>Nome: </td>
				<td><b><? echo $_POST["nome"]; ?></b></td>
			</tr>
			<tr>
				<td>Texto da minuta: </td>
				<td><textarea style="width: 600px; height: 200px;" name="texto" id="texto"><? echo htmlentities($_POST["texto"], ENT_QUOTES); ?></textarea></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td class="alr"><img src="images/buttons/bt_voltar.gif" onclick="goPage('adm_minutas.php')" alt="Cancelar e voltar" class="cursorMao" />&nbsp;<img src="images/buttons/bt_salvar.gif" onclick="validaMinuta(document.getElementById('frm1'));" alt="Alterar Minuta" class="cursorMao" /></td>
			</tr>
		</table>
	</div>
	<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
</form>
<?php
include "lib/footer.inc.php";
?>