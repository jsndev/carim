<?php

$iREQ_AUT=1;
$aUSERS_PERM[]=4;

$pageTitle = "Categorias";
include "lib/header.inc.php";

$oInformativo = new conteudo();

$oMensagem = new mensagens();

if ($_GET["dmy"] != "" && $_GET["cod_ctgr"] != "" && $_GET["ac"] == "del") {
	$oInformativo->delCategoria($_GET["cod_ctgr"]);
	if ($oInformativo->getErrNo() == 0) {
		$oMensagem->setMensagem("A categoria foi removida com sucesso", MSG_SUCESSO);
	} elseif ($oInformativo->getErrNo() == 1451) {
		$oMensagem->setMensagem("A categoria não pôde ser removida pois há informativos cadastrados.", MSG_ALERTA);
	} else {
		$oMensagem->setMensagem("A categoria não pôde ser removida. Tente novamente.", MSG_ERRO);
	}
}
$listaCategorias = $oInformativo->getListaCategorias();
?>
<script language="javascript" type="text/javascript" src="js/cadastroCategoria.js"></script>
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
if (@count($listaCategorias) > 0) {
	foreach ($listaCategorias as $idxDadoCategoria => $dadoCategoria) {
?>
					<tr class="tL<? echo $idxDadoCategoria%2 ? "1" : "2"; ?>">
						<td><? echo $dadoCategoria["titulo_ctgr"]; ?></td>
						<td><? echo $dadoCategoria["descr_ctgr"]; ?></td>
						<td class="alc"><img src="images/layout/indic_v<? echo $dadoCategoria["flgativo_ctgr"] == "1" ? "d" : "m"; ?>.gif" alt="<? echo $dadoCategoria["flgativo_ctgr"] == "1" ? "Ativo" : "Inativo"; ?>" /></td>
						<td class="alc"><a href="javascript:delCat('<? echo eregi_replace("\'", "", $dadoCategoria["titulo_ctgr"]); ?>', '<? echo $crypt->encrypt("ac=del&dmy=".md5(mt_rand(0,(($idxDadoCategoria+1)*(int)mktime())))."&cod_ctgr=".$dadoCategoria["cod_ctgr"]); ?>');" title="Excluir"><img src="images/buttons/bt_excluir.gif" alt="Excluir" /></a>&nbsp;<a href="adm_categorias_alt.php?k=<? echo $crypt->encrypt("cod_ctgr=".$dadoCategoria["cod_ctgr"]); ?>" title="Alterar"><img src="images/buttons/bt_alterar.gif" alt="Alterar" /></a></td>
					</tr>
<?
	}
}
?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4"><a href="adm_categorias_add.php"><img src="images/buttons/bt_adicionar.gif" alt=" " /></a></td>
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