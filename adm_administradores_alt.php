<?php
$iREQ_AUT=1;
$aUSERS_PERM[]=4;
ob_start();

$pageTitle = "Alterar Administrador";
include "lib/header.inc.php";

if ((int)$_GET["cod_usua"] > 0) {
	ob_end_flush();
} else {
	ob_end_clean();
	header("Location: adm_administradores.php");
	exit();
}

$oUsuario = new usuario();
$mensagem = new mensagens();

if ($crypt->decrypt($_POST["ac"]) == "alterar") {
	$dadosUsuarioTmp = $oUsuario->getUsuario($_GET["cod_usua"]);
	$dadosUsuario = $dadosUsuarioTmp[0];
	$dadosUsuario["cod_usua"] = $_GET["cod_usua"];
	$dadosUsuario["nome_usua"] = $_POST["nome"];
	$dadosUsuario["flgstatus_usua"] = (string)($_POST["ativo"] == "s" ? "1" : "0");
	$bInsercao = $oUsuario->updUsuario($dadosUsuario);
	if ($oUsuario->getErrNo() == 0) {
		$mensagem->setMensagem("Os dados do administrador foram alterados com sucesso.", MSG_SUCESSO);
		unset($_POST);
	} else {
		$mensagem->setMensagem("Houve um erro ao alterar dos dados do administrador. Favor tentar novamente.", MSG_ERRO);
		$_POST["email"] = $dadosUsuario["email_usua"];
	}
}

if (@count($_POST) == 0) {
	$dadosUsuario = $oUsuario->getUsuario($_GET["cod_usua"]);
	$_POST["nome"] = $dadosUsuario[0]["nome_usua"];
	$_POST["email"] = $dadosUsuario[0]["email_usua"];
	$_POST["ativo"] = (string)$dadosUsuario[0]["flgstatus_usua"] == "1" ? "s" : "n";
}
?>
<script language="javascript" type="text/javascript" src="js/cadastroAdministrador.js"></script>
<form method="post" action="<? echo $php_self; ?>?k=<? echo $crypt->encrypt("cod_usua=".$_GET["cod_usua"]); ?>" class="formPadrao" name="frm1" id="frm1">
<input type="hidden" name="ac" value="<? echo $crypt->encrypt("alterar");?>" />
<div class="quadroInterno">
	<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
	<div class="quadroInternoMeio">
		<? echo $mensagem->getMessageBox(); ?>
		<table cellpadding="0" cellspacing="2" class="tbForm">
			<tr>
				<td>Nome: </td>
				<td><input type="text" name="nome" id="nome" value="<? echo $_POST["nome"]; ?>" /></td>
			</tr>
			<tr>
				<td>Email: </td>
				<td><b><? echo $_POST["email"]; ?></b></td>
			</tr>
			<tr>
				<td>Ativo: </td>
				<td><input type="checkbox" name="ativo" id="ativo" value="s" class="ck" <? echo $_POST["ativo"] == "s" ? "checked" : ""; ?> /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td class="alr"><img src="images/buttons/bt_voltar.gif" onclick="goPage('adm_administradores.php')" alt="Cancelar e voltar" class="cursorMao" />&nbsp;<img src="images/buttons/bt_salvar.gif" onclick="validaAdministradorAlt(document.getElementById('frm1'));" alt="Inserir Administrador" class="cursorMao" /></td>
			</tr>
		</table>
	</div>
	<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
</form>
<?php
include "lib/footer.inc.php";
?>