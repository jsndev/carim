<?php
$iREQ_AUT=1;
$aUSERS_PERM[]=4;

$pageTitle = "Templates";
include "lib/header.inc.php";

unset($_SESSION["tmpListaConteudosDisponiveis"]);
unset($_SESSION["tmpListaConteudosAtribuidos"]);

$oTemplate = new conteudo();

$oMensagem = new mensagens();

if ($_GET["dmy"] != "" && $_GET["cod_tmpl"] != "" && $_GET["ac"] == "del") {
	$oTemplate->beginTransaction();
	$oTemplate->delTemplateConteudos($_GET["cod_tmpl"]);
	$oTemplate->delTemplate($_GET["cod_tmpl"]);
	if ($oTemplate->getErrNo() == 0) {
		$oMensagem->setMensagem("O template foi removido com sucesso", MSG_SUCESSO);
	} elseif ($oTemplate->getErrNo() == DB_ERR_FKREF) {
		$oTemplate->rollbackTransaction();
		$oMensagem->setMensagem("O template não pôde ser removido pois há informativos cadastrados a este template.", MSG_ALERTA);
	} else {
		$oTemplate->rollbackTransaction();
		$oMensagem->setMensagem("O template não pôde ser removido. Tente novamente.", MSG_ERRO);
	}
	$oTemplate->commitTransaction();
}

$listaTemplates = $oTemplate->getListaTemplates();
?>
<script language="javascript" type="text/javascript" src="js/cadastroTemplate.js"></script>
<div class="quadroInterno">
	<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
	<div class="quadroInternoMeio">
		<? echo $oMensagem->getMessageBox(); ?>
		<div class="tListDiv">
			<table>
				<colgroup>
					<col />
					<col />
					<col width="45" />
					<col width="170" />
				</colgroup>
				<thead>
					<tr>
						<td>Título</td>
						<td>Descrição</td>
						<td class="alc">Ativo</td>
						<td class="alc">&nbsp;</td>
					</tr>
				</thead>
				<tbody>
<?
if (@count($listaTemplates) > 0) {
	foreach ($listaTemplates as $idxDadoTemplate => $dadoTemplate) {
?>
					<tr class="tL<? echo $idxDadoTemplate%2 ? "1" : "2"; ?>">
						<td><? echo $dadoTemplate["titulo_tmpl"]; ?></td>
						<td><? echo $dadoTemplate["descr_tmpl"]; ?></td>
						<td class="alc"><img src="images/layout/indic_v<? echo $dadoTemplate["flgativo_tmpl"] == "1" ? "d" : "m"; ?>.gif" alt="<? echo $dadoTemplate["flgativo_tmpl"] == "1" ? "Ativo" : "Inativo"; ?>" /></td>
						<td class="alc"><a href="javascript:delTemplate('<? echo eregi_replace("\'", "", $dadoTemplate["titulo_tmpl"]); ?>','<? echo $crypt->encrypt("ac=del&dmy=".md5(mt_rand(0,(($idxDadoTemplate+1)*(int)mktime())))."&cod_tmpl=".$dadoTemplate["cod_tmpl"]); ?>');" title="Excluir"><img src="images/buttons/bt_excluir.gif" alt="Excluir" /></a>&nbsp;<a href="adm_templates_alt.php?k=<? echo $crypt->encrypt("cod_tmpl=".$dadoTemplate["cod_tmpl"]); ?>" title="Alterar"><img src="images/buttons/bt_alterar.gif" alt="Alterar" /></a></td>
					</tr>
<?
	}
}
?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="5"><a href="adm_templates_add.php"><img src="images/buttons/bt_adicionar.gif" alt=" " /></a></td>
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