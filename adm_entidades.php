<?php

$iREQ_AUT=1;
$aUSERS_PERM[]=4;

$pageTitle = "Entidades";
include "lib/header.inc.php";

$oEntidade = new entidade();
$oMensagem = new mensagens();

$listaEntidades = $oEntidade->getListaEntidade();
?>
<div class="quadroInterno">
	<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
	<div class="quadroInternoMeio">
		<? echo $oMensagem->getMessageBox(); ?>
		<div class="tListDiv">
			<table>
				<colgroup>
					<col />
					<col />
					<col width="100" />
				</colgroup>
				<thead>
					<tr>
						<td>Entidade</td>
						<td>Descrição</td>
						<td class="alc">&nbsp;</td>
					</tr>
				</thead>
				<tbody>
<?
if (@count($listaEntidades) > 0) {
	foreach ($listaEntidades as $idxDadosEntidade => $dadosEntidade) {
?>
					<tr class="tL<? echo $idxDadosEntidade%2 ? "1" : "2"; ?>">
						<td><? echo $dadosEntidade["nome_enti"]; ?></td>
						<td><? echo $dadosEntidade["descr_enti"]; ?></td>
						<td class="alc"><a href="adm_entidades_alt.php?k=<? echo $crypt->encrypt("cod_enti=".$dadosEntidade["cod_enti"]); ?>" title="Alterar"><img src="images/buttons/bt_alterar.gif" alt="Alterar" /></a></td>
					</tr>
<?
	}
}
?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4"><a href="adm_entidades_add.php"><img src="images/buttons/bt_adicionar.gif" alt=" " /></a></td>
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