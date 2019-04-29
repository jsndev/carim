<?php
ob_start();
$iREQ_AUT=1;
$aUSERS_PERM[]=4;

$pageTitle = "Alterar Categoria";
include "lib/header.inc.php";

if ((int)$_GET["cod_ctgr"] > 0) {
	ob_end_flush();
} else {
	ob_end_clean();
	header("Location: adm_categorias.php");
	exit();
}

$oConteudo = new conteudo();

$mensagem = new mensagens();
if ($crypt->decrypt($_POST["ac"]) == "alterar") {
	$dados["cod_ctgr"] = $_GET["cod_ctgr"];
	$dados["titulo_ctgr"] = $_POST["categoria"];
	$dados["descr_ctgr"] = $_POST["descricao"];
	$dados["flgativo_ctgr"] = (string)($_POST["ativo"] == "s" ? "1" : "0");
	$oConteudo->updCategoria($dados);
	if ($oConteudo->getErrNo() == 0) {
		$mensagem->setMensagem("Categoria alterada com sucesso", MSG_SUCESSO);
		unset($_POST);
	} else {
		if ((int)$oConteudo->getErrNo() == 1062) {
			$mensagem->setMensagem("Já existe uma categoria com o título \"".$_POST["categoria"]."\". Utilize outro nome.", MSG_ERRO);
		} else {
			$mensagem->setMensagem("A categoria não foi alterada. Tente novamente.", MSG_ERRO);
		}
	}
}

$dadosCategoria = $oConteudo->getCategoria($_GET["cod_ctgr"]);
$_POST["categoria"] = $dadosCategoria[0]["titulo_ctgr"];
$_POST["descricao"] = $dadosCategoria[0]["descr_ctgr"];
$_POST["ativo"] = (int)$dadosCategoria[0]["flgativo_ctgr"] == 1 ? "s" : false;
?>
<script language="javascript" type="text/javascript" src="js/cadastroCategoria.js"></script>
<form method="post" action="<? echo $php_self; ?>?k=<? echo $crypt->encrypt("cod_ctgr=".$_GET["cod_ctgr"]); ?>" class="formPadrao" name="frm1" id="frm1">
<input type="hidden" name="ac" value="<? echo $crypt->encrypt("alterar");?>" />
<div class="quadroInterno">
	<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
	<div class="quadroInternoMeio">
		<? echo $mensagem->getMessageBox(); ?>
		<table cellpadding="0" cellspacing="2" class="tbForm">
			<tr>
				<td>Título: </td>
				<td><input type="text" name="categoria" id="categoria" value="<? echo $_POST["categoria"]; ?>" /></td>
			</tr>
			<tr>
				<td>Descrição: </td>
				<td><textarea name="descricao" id="descricao"><? echo $_POST["descricao"]; ?></textarea></td>
			</tr>
			<tr>
				<td>Ativo: </td>
				<td><input type="checkbox" name="ativo" id="ativo" value="s" class="ck" <? echo $_POST["ativo"] == "s" ? "checked" : ""; ?> /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td class="alr"><img src="images/buttons/bt_voltar.gif" onclick="goPage('adm_categorias.php')" alt="Cancelar e voltar" class="cursorMao" />&nbsp;<img src="images/buttons/bt_salvar.gif" onclick="validaCategoria(document.getElementById('frm1'));" alt="Alterar Categoria" class="cursorMao" /></td>
			</tr>
		</table>
	</div>
	<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
</form>
<?php
include "lib/footer.inc.php";
?>