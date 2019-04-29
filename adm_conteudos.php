<?php
$iREQ_AUT=1;
$aUSERS_PERM[]=4;

$pageTitle = "Conteúdos";
include "lib/header.inc.php";

$oConteudo = new conteudo();

$oMensagem = new mensagens();

if ($_GET["dmy"] != "" && $_GET["cod_cotd"] != "" && $_GET["ac"] == "del") {
	$oConteudo->beginTransaction();
	$oConteudo->delConteudo($_GET["cod_cotd"]);
	if ($oConteudo->getErrNo() == 0) {
		$oMensagem->setMensagem("O conteúdo foi removido com sucesso", MSG_SUCESSO);
	} elseif ($oConteudo->getErrNo() == DB_ERR_FKREF) {
		$oConteudo->rollbackTransaction();
		$oMensagem->setMensagem("O conteúdo não pôde ser removido pois está em uso por um ou mais templates.", MSG_ALERTA);
	} else {
		$oConteudo->rollbackTransaction();
		$oMensagem->setMensagem("O conteúdo não pôde ser removido. Tente novamente.", MSG_ERRO);
	}
	$oConteudo->commitTransaction();
}

$listaConteudos = $oConteudo->getListaConteudos();
?>
<script language="javascript" type="text/javascript" src="js/cadastroConteudo.js"></script>
<div class="quadroInterno">
	<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
	<div class="quadroInternoMeio">
		<? echo $oMensagem->getMessageBox(); ?>
		<div class="tListDiv">
			<table>
				<colgroup>
					<col />
					<col />
					<col width="50" />
					<col width="45" />
					<col width="170" />
				</colgroup>
				<thead>
					<tr>
						<td>Título</td>
						<td>Descrição</td>
						<td class="alc">Tipo</td>
						<td class="alc">Ativo</td>
						<td class="alc">&nbsp;</td>
					</tr>
				</thead>
				<tbody>
<?
if (@count($listaConteudos) > 0) {
	foreach ($listaConteudos as $idxDadoConteudo => $dadoConteudo) {
?>
					<tr class="tL<? echo $idxDadoConteudo%2 ? "1" : "2"; ?>">
						<td><? echo $dadoConteudo["titulo_cotd"]; ?></td>
						<td><? echo $dadoConteudo["descr_cotd"]; ?></td>
						<td class="alc"><img src="images/tpcont/<? echo $dadoConteudo["tipo_cotd"]; ?>.gif" alt=" " /></td>
						<td class="alc"><img src="images/layout/indic_v<? echo $dadoConteudo["flgativo_cotd"] == "1" ? "d" : "m"; ?>.gif" alt="<? echo $dadoConteudo["flgativo_cotd"] == "1" ? "Ativo" : "Inativo"; ?>" /></td>
						<td class="alc"><a href="javascript:delConteudo('<? echo eregi_replace("\'", "", $dadoConteudo["titulo_cotd"]); ?>', '<? echo $crypt->encrypt("ac=del&dmy=".md5(mt_rand(0,(($idxDadoConteudo+1)*(int)mktime())))."&cod_cotd=".$dadoConteudo["cod_cotd"]); ?>');" title="Excluir"><img src="images/buttons/bt_excluir.gif" alt="Excluir" /></a>&nbsp;<a href="adm_conteudos_alt.php?k=<? echo $crypt->encrypt("cod_cotd=".$dadoConteudo["cod_cotd"]); ?>" title="Alterar"><img src="images/buttons/bt_alterar.gif" alt="Alterar" /></a></td>
					</tr>
<?
	}
}
?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="5"><a href="adm_conteudos_add.php"><img src="images/buttons/bt_adicionar.gif" alt=" " /></a></td>
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