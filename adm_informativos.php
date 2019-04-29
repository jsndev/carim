<?php
$iREQ_AUT=1;
$aUSERS_PERM[]=4;

$pageTitle = "Informativos";
include "lib/header.inc.php";

unset($_SESSION["tmpListaTemplatesDisponiveis"]);
unset($_SESSION["tmpListaTemplatesAtribuidos"]);

$oInformativo = new conteudo();

$oMensagem = new mensagens();

if ($_GET["dmy"] != "" && $_GET["cod_info"] != "" && $_GET["ac"] == "del") {
	$oInformativo->beginTransaction();
	$oInformativo->delInformativoTemplates($_GET["cod_info"]);
	$oInformativo->delInformativo($_GET["cod_info"]);
	if ($oInformativo->getErrNo() == 0) {
		$oMensagem->setMensagem("O informativo foi removido com sucesso", MSG_SUCESSO);
	} elseif ($oInformativo->getErrNo() == 1451) {
		$oInformativo->rollbackTransaction();
		$oMensagem->setMensagem("O informativo não pôde ser removido pois há templates cadastrados.", MSG_ALERTA);
	} else {
		$oInformativo->rollbackTransaction();
		$oMensagem->setMensagem("O informativo não pôde ser removido. Tente novamente.", MSG_ERRO);
	}
	$oInformativo->commitTransaction();
}

$listaInformativos = $oInformativo->getListaInformativos();
?>
<script language="javascript" type="text/javascript" src="js/cadastroInformativo.js"></script>
<div class="quadroInterno">
	<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
	<div class="quadroInternoMeio">
		<? echo $oMensagem->getMessageBox(); ?>
		<div class="tListDiv">
			<table>
				<colgroup>
					<col />
					<col />
					<col />
					<col width="45" />
					<col width="170" />
				</colgroup>
				<thead>
					<tr>
						<td>Título</td>
						<td>Descrição</td>
						<td>Categoria</td>
						<td class="alc">Ativo</td>
						<td class="alc">&nbsp;</td>
					</tr>
				</thead>
				<tbody>
<?
if (@count($listaInformativos) > 0) {
	foreach ($listaInformativos as $idxDadoInformativo => $dadoInformativo) {
?>
					<tr class="tL<? echo $idxDadoInformativo%2 ? "1" : "2"; ?>">
						<td><? echo $dadoInformativo["titulo_info"]; ?></td>
						<td><? echo $dadoInformativo["descr_info"]; ?></td>
						<td><? echo $dadoInformativo["titulo_ctgr"]; ?></td>
						<td class="alc"><img src="images/layout/indic_v<? echo $dadoInformativo["flgativo_info"] == "1" ? "d" : "m"; ?>.gif" alt="<? echo $dadoInformativo["flgativo_info"] == "1" ? "Ativo" : "Inativo"; ?>" /></td>
						<td class="alc"><a href="javascript:delInformativo('<? echo eregi_replace("\'", "", $dadoInformativo["titulo_info"]); ?>', '<? echo $crypt->encrypt("ac=del&dmy=".md5(mt_rand(0,(($idxDadoInformativo+1)*(int)mktime())))."&cod_info=".$dadoInformativo["cod_info"]); ?>');" title="Excluir"><img src="images/buttons/bt_excluir.gif" alt="Excluir" /></a>&nbsp;<a href="adm_informativos_alt.php?k=<? echo $crypt->encrypt("cod_info=".$dadoInformativo["cod_info"]); ?>" title="Alterar"><img src="images/buttons/bt_alterar.gif" alt="Alterar" /></a></td>
					</tr>
<?
	}
}
?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="5"><a href="adm_informativos_add.php"><img src="images/buttons/bt_adicionar.gif" alt=" " /></a></td>
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