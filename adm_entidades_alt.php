<?php
ob_start();
$iREQ_AUT=1;
$aUSERS_PERM[]=4;

$pageTitle = "Alterar Entidade";
include "lib/header.inc.php";

if ((int)$_GET["cod_enti"] > 0) {
	ob_end_flush();
} else {
	ob_end_clean();
	header("Location: adm_entidades.php");
	exit();
}

$oEntidade = new entidade();
$mensagem = new mensagens();

$pageAction = $crypt->decrypt($_POST["ac"]);

if ($crypt->decrypt($_POST["ac"]) == "alterar") {
	$dadosEntidade["cod_enti"] = $_GET["cod_enti"];
	$dadosEntidade["nome_enti"] = $_POST["nome"];
	$dadosEntidade["descr_enti"] = $_POST["descricao"];
	$oEntidade->updEntidade($dadosEntidade);
	if ($oEntidade->getErrNo() == 0) {
		$mensagem->setMensagem("Entidade alterada com sucesso", MSG_SUCESSO);
	} elseif ($oEntidade->getErrNo() == DB_ERR_UNIQUE) {
		$mensagem->setMensagem("Já existe uma entidade com este nome cadastrada.", MSG_ERRO);
	} else {
		$mensagem->setMensagem("Houve um erro ao cadastrar a entidade. Favor tentar novamente.", MSG_ERRO);
	}
}

if (!$pageAction) {
	$dadosEntidade = $oEntidade->getEntidade($_GET["cod_enti"]);
	$_POST["nome"] = $dadosEntidade[0]["nome_enti"];
	$_POST["descricao"] = $dadosEntidade[0]["descr_enti"];
}
?>
<script language="javascript" type="text/javascript" src="js/cadastroEntidade.js"></script>
<form method="post" action="<? echo $php_self; ?>?k=<? echo $crypt->encrypt("cod_enti=".$_GET["cod_enti"]); ?>" class="formPadrao" name="frm1" id="frm1">
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
				<td>&nbsp;</td>
				<td class="alr"><img src="images/buttons/bt_voltar.gif" onclick="goPage('adm_entidades.php')" alt="Cancelar e voltar" class="cursorMao" />&nbsp;<img src="images/buttons/bt_salvar.gif" onclick="validaEntidade(document.getElementById('frm1'));" alt="Alterar Entidade" class="cursorMao" /></td>
			</tr>
		</table>
	</div>
	<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
</form>
<?php
include "lib/footer.inc.php";
?>