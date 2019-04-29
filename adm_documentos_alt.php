<?php
ob_start();
$iREQ_AUT=1;
$aUSERS_PERM[]=4;

$pageTitle = "Alterar Documento";
include "lib/header.inc.php";

if ((int)$_GET["cod_docm"] > 0) {
	ob_end_flush();
} else {
	ob_end_clean();
	header("Location: adm_documentos.php");
	exit();
}

$oDocumento = new documento();
$mensagem = new mensagens();

$pageAction = $crypt->decrypt($_POST["ac"]);

if ($crypt->decrypt($_POST["ac"]) == "alterar") {
	$dadosDocumento["cod_docm"] = $_GET["cod_docm"];
	$dadosDocumento["nome_docm"] = $_POST["nome"];
	$dadosDocumento["descr_docm"] = $_POST["descricao"];
	$dadosDocumento['validade_docm'] = $_POST['validade'];
	$oDocumento->updDocumento($dadosDocumento);
	if ($oDocumento->getErrNo() == 0) {
		$mensagem->setMensagem("Documento alterado com sucesso", MSG_SUCESSO);
	} elseif ($oDocumento->getErrNo() == DB_ERR_UNIQUE) {
		$mensagem->setMensagem("Já existe um documento com este nome cadastrado.", MSG_ERRO);
	} else {
		$mensagem->setMensagem("Houve um erro ao cadastrar o documento. Favor tentar novamente.", MSG_ERRO);
	}
}

if (!$pageAction) {
	$dadosDocumento = $oDocumento->getDocumento($_GET["cod_docm"]);
	$_POST["nome"] = $dadosDocumento[0]["nome_docm"];
	$_POST["descricao"] = $dadosDocumento[0]["descr_docm"];
	$_POST["validade"] = $dadosDocumento[0]["validade_docm"];
}
?>
<script language="javascript" type="text/javascript" src="js/cadastroDocumento.js"></script>
<form method="post" action="<? echo $php_self; ?>?k=<? echo $crypt->encrypt("cod_docm=".$_GET["cod_docm"]); ?>" class="formPadrao" name="frm1" id="frm1">
<input type="hidden" name="ac" value="<? echo $crypt->encrypt("alterar");?>" />
<div class="quadroInterno">
	<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
	<div class="quadroInternoMeio">
		<? echo $mensagem->getMessageBox(); ?>
		<table cellpadding="0" cellspacing="2" class="tbForm">
			<tr>
				<td>Título: </td>
				<td><input type="text" name="nome" id="nome" value="<? echo $_POST["nome"]; ?>" /></td>
			</tr>
			<tr>
				<td>Descrição: </td>
				<td><textarea name="descricao" id="descricao"><? echo $_POST["descricao"]; ?></textarea></td>
			</tr>
			<tr>
				<td>Validade: </td>
				<td><input type="text" style="width: 40px;" name="validade" id="validade" value="<? echo $_POST["validade"]; ?>" /> dias</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td class="alr"><img src="images/buttons/bt_voltar.gif" onclick="goPage('adm_documentos.php')" alt="Cancelar e voltar" class="cursorMao" />&nbsp;<img src="images/buttons/bt_salvar.gif" onclick="validaDocumento(document.getElementById('frm1'));" alt="Alterar Documento" class="cursorMao" /></td>
			</tr>
		</table>
	</div>
	<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
</form>
<?php
include "lib/footer.inc.php";
?>