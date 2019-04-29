<?php
$iREQ_AUT=1;
$aUSERS_PERM[]=4;
ob_start();

$pageTitle = "Alterar Taxa";
include "lib/header.inc.php";

if ((int)$_GET["cod_taxa"] > 0) {
	ob_end_flush();
} else {
	ob_end_clean();
	header("Location: adm_taxas.php");
	exit();
}

$oTaxa = new taxa();
$mensagem = new mensagens();

if ($crypt->decrypt($_POST["ac"]) == "alterar" && (int)$_GET["cod_taxa"] > 0) {
	
	$dadosTaxa = $oTaxa->getTaxa($_GET["cod_taxa"]);
	$dadosTaxa[0]["cod_taxa"] = $_GET["cod_taxa"];
	$dadosTaxa[0]["nome_taxa"] = $_POST["taxa"];
	$dadosTaxa[0]["descr_taxa"] = $_POST["descricao"];
	$dadosTaxa[0]["valor_taxa"] = number_format((float)eregi_replace(",", ".", $_POST["valor"]), 2, ".", "");

	$bResultadoUpd = $oTaxa->updTaxa($dadosTaxa[0]);
	
	if ($oTaxa->getErrNo() == 0) {
		$mensagem->setMensagem("Os dados da Taxa foram alterados com sucesso.", MSG_SUCESSO);
		unset($_POST);
	} else {
		$mensagem->setMensagem("Houve um erro ao alterar dos dados da Taxa. Favor tentar novamente.", MSG_ERRO);
	}
}

if (@count($_POST) == 0) {
	$dadosTaxa = $oTaxa->getTaxa($_GET["cod_taxa"]);
	$_POST["taxa"] = $dadosTaxa[0]["nome_taxa"];
	$_POST["descricao"] = $dadosTaxa[0]["descr_taxa"];
	$_POST["valor"] = number_format((float)$dadosTaxa[0]["valor_taxa"], 2, ",", ".");
}
?>
<script language="javascript" type="text/javascript" src="js/cadastroTaxa.js"></script>
<form method="post" action="<? echo $php_self; ?>?k=<? echo $crypt->encrypt("cod_taxa=".$_GET["cod_taxa"]); ?>" class="formPadrao" name="frm1" id="frm1">
<input type="hidden" name="ac" value="<? echo $crypt->encrypt("alterar");?>" />
<div class="quadroInterno">
	<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
	<div class="quadroInternoMeio">
		<? echo $mensagem->getMessageBox(); ?>
		<table cellpadding="0" cellspacing="2" class="tbForm">
			<tr>
				<td>Taxa: </td>
				<td><input type="text" name="taxa" id="taxa" value="<? echo $_POST["taxa"]; ?>" /></td>
			</tr>
			<tr>
				<td>Descricao: </td>
				<td><textarea name="descricao" id="descricao"><? echo $_POST["descricao"]; ?></textarea></td>
			</tr>
			<tr>
				<td>Valor: </td>
				<td><input type="text" name="valor" id="valor" value="<? echo $_POST["valor"]; ?>" onkeydown="maskCurrency(this);" onkeyup="maskCurrency(this);" onblur="maskCurrency(this);" style="width: 50px;" class="alr" maxlength="5" />%</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td class="alr"><img src="images/buttons/bt_voltar.gif" onclick="goPage('adm_taxas.php')" alt="Cancelar e voltar" class="cursorMao" />&nbsp;<img src="images/buttons/bt_salvar.gif" onclick="validaTaxa(document.getElementById('frm1'));" alt="Inserir Atendente" class="cursorMao" /></td>
			</tr>
		</table>
	</div>
	<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
</form>
<?php
include "lib/footer.inc.php";
?>