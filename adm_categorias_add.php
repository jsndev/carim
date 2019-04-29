<?php
$iREQ_AUT=1;
$aUSERS_PERM[]=4;

$pageTitle = "Adicionar Categoria";
include "lib/header.inc.php";
$oConteudo = new conteudo();

$mensagem = new mensagens();
if ($crypt->decrypt($_POST["ac"]) == "adicionar") {
	$dados["titulo_ctgr"] = $_POST["categoria"];
	$dados["descr_ctgr"] = $_POST["descricao"];
	$dados["flgativo_ctgr"] = (string)($_POST["ativo"] == "s" ? "1" : "0");
	$bInsercao = $oConteudo->addCategoria($dados);
	if ($oConteudo->getErrNo() == 0) {
		$mensagem->setMensagem("Categoria inserida com sucesso", MSG_SUCESSO);
		unset($_POST);
	} else {
		$mensagem->setMensagem("A categoria não foi inserida. Tente novamente.", MSG_ERRO);
	}
}
?>
<script language="javascript" type="text/javascript" src="js/cadastroCategoria.js"></script>
<form method="post" action="<? echo $php_self; ?>" class="formPadrao" name="frm1" id="frm1">
<input type="hidden" name="ac" value="<? echo $crypt->encrypt("adicionar");?>" />
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
				<td class="alr"><img src="images/buttons/bt_voltar.gif" onclick="goPage('adm_categorias.php')" alt="Cancelar e voltar" class="cursorMao" />&nbsp;<img src="images/buttons/bt_salvar.gif" onclick="validaCategoria(document.getElementById('frm1'));" alt="Inserir Categoria" class="cursorMao" /></td>
			</tr>
		</table>
	</div>
	<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
</form>
<?php
include "lib/footer.inc.php";
?>