<?php
$iREQ_AUT=1;
$aUSERS_PERM[]=4;

$pageTitle = "Despachantes";
include "lib/header.inc.php";

unset($_SESSION["regiaoDespachante"]);

$oUsuario = new usuario();
$oMensagem = new mensagens();
$oDespachante = new despachante();

if ($_GET["dmy"] != "" && $_GET["cod_usua"] != "" && $_GET["ac"] == "del") {
	$oUsuario->delUsuario($_GET["cod_usua"]);
	$oDespachante->deletarPk($_GET["cod_usua"]);
	if ($oUsuario->getErrNo() == 0) {
		$oMensagem->setMensagem("O despachante foi removido com sucesso", MSG_SUCESSO);
	} else {
		$oMensagem->setMensagem("O despachante não pôde ser removido. Tente novamente.", MSG_ERRO);
	}
} elseif ($_GET["dmy"] != "" && $_GET["cod_usua"] != "" && $_GET["ac"] == "uppwd") {
	mt_srand(mktime());
	$sTmpPassword = substr(md5(mt_rand(0,mktime())),0,8);
	$dadosUsuario = $oUsuario->getUsuario($_GET["cod_usua"]);
	$dadosUsuario[0]["pwd_usua"] = md5($sTmpPassword);
	$oUsuario->updUsuario($dadosUsuario[0]);
	if ($oUsuario->getErrNo() == 0) {
		$email = new email();
		$email->setTo($dadosUsuario[0]["email_usua"]);
		$email->setSubject("Sua senha de acesso ao sistema Contrathos");
		$sMensagem = "
			<p>Prezado <b>".$dadosUsuario[0]["nome_usua"]."</b>,</p>
			<p>Seja bem vindo so site de contratação de financiamento da Athos Gestão e Serviço Ltda para o Carim 2007.
			Você está recebendo abaixo os dados de acesso ao Sistema Contrathos.</p>
			<p>
				Login: <b>".$dadosUsuario[0]["email_usua"]."</b><br />
				Senha: <b>".$sTmpPassword."</b>
			</p>
			<p>A senha foi gerada automaticamente e poderá ser alterada na tela de login a seguir:</p>
			<p align=\"center\"><a href=\"http://www.contrathos.athosgestao.com.br/carim\">www.contrathos.athosgestao.com.br/carim</p>
			<p>Atenciosamente,</p>
			<p>Equipe Contrathos</p>
		";
		$email->setMessage($sMensagem);
		$email->send();
		$oMensagem->setMensagem("A senha do despachante \"".$dadosUsuario[0]["nome_usua"]."\" foi alterada com sucesso. Um e-mail foi enviado ao seu endereço eletrônico contendo sua nova senha de acesso.", MSG_SUCESSO);
	} else {
		$oMensagem->setMensagem("A senha do despachante \"".$dadosUsuario[0]["nome_usua"]."\" não pôde ser alterada. Tente novamente.", MSG_ERRO);
	}
}

$aListaUsuario = $oUsuario->getListaUsuarios(TPUSER_DESPACHANTE);
?>
<script language="javascript" type="text/javascript" src="js/cadastroDespachante.js"></script>
<div class="quadroInterno">
	<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
	<div class="quadroInternoMeio">
		<? echo $oMensagem->getMessageBox(); ?>
		<div class="tListDiv">
			<table>
				<colgroup>
					<col />
					<col />
					<col width="130" />
					<col width="45" />
					<col width="260" />
				</colgroup>
				<thead>
					<tr>
						<td>Nome</td>
						<td>Email</td>
						<td class="alc">Último Acesso</td>
						<td class="alc">Ativo</td>
						<td class="alc">&nbsp;</td>
					</tr>
				</thead>
				<tbody>
<?
if (@count($aListaUsuario) > 0) {
	foreach ($aListaUsuario as $nIdxDadoUsuario => $aDadoUsuario) {
?>
					<tr class="tL<? echo $nIdxDadoUsuario%2 ? "1" : "2"; ?>">
						<td><? echo $aDadoUsuario["nome_usua"]; ?></td>
						<td><? echo $aDadoUsuario["email_usua"]; ?></td>
						<td class="alc"><? echo $aDadoUsuario["dt_login"] ? date("d/m/Y H:i:s", strtotime($aDadoUsuario["dt_login"])) : "&nbsp;"; ?></td>
						<td class="alc"><img src="images/layout/indic_v<? echo $aDadoUsuario["flgstatus_usua"] == "1" ? "d" : "m"; ?>.gif" alt="<? echo $dadoInformativo["flgativo_info"] == "1" ? "Ativo" : "Inativo"; ?>" /></td>
						<td class="alc"><a href="javascript:delDespachante('<? echo eregi_replace("\'", "", $aDadoUsuario["nome_usua"]); ?>', '<? echo $crypt->encrypt("ac=del&dmy=".md5(mt_rand(0,(($nIdxDadoUsuario+1)*(int)mktime())))."&cod_usua=".$aDadoUsuario["cod_usua"]); ?>');" title="Excluir"><img src="images/buttons/bt_excluir.gif" alt="Excluir" /></a>&nbsp;<a href="adm_despachantes_alt.php?k=<? echo $crypt->encrypt("cod_usua=".$aDadoUsuario["cod_usua"]); ?>" title="Alterar"><img src="images/buttons/bt_alterar.gif" alt="Alterar" /></a>&nbsp;<a href="javascript:altPwdDespachante('<? echo eregi_replace("\'", "", $aDadoUsuario["nome_usua"]); ?>', '<? echo $crypt->encrypt("ac=uppwd&dmy=".md5(mt_rand(0,(($nIdxDadoUsuario+1)*(int)mktime())))."&cod_usua=".$aDadoUsuario["cod_usua"]); ?>');" title="Alterar Senha"><img src="images/buttons/bt_alterarsenha.gif" alt="Alterar Senha" /></a></td>
					</tr>
<?
	}
}
?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="5"><a href="adm_despachantes_add.php"><img src="images/buttons/bt_adicionar.gif" alt=" " /></a></td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
	<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
<?php
include "lib/footer.inc.php";
?>