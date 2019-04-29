<?php

$iREQ_AUT=1;
$aUSERS_PERM[]=4;

$pageTitle = "Documentos";
include "lib/header.inc.php";

$oDocumento = new documento();
$oMensagem = new mensagens();

$listaDocumentos = $oDocumento->getListaDocumento();
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
					<col />
					<col width="100" />
				</colgroup>
				<thead>
					<tr>
						<td>Documento</td>
						<td>Descrição</td>
						<td>Validade</td>
						<td class="alc">&nbsp;</td>
					</tr>
				</thead>
				<tbody>
<?
if (@count($listaDocumentos) > 0) {
	foreach ($listaDocumentos as $idxDadosDocumento => $dadosDocumento) {
?>
					<tr class="tL<? echo $idxDadosDocumento%2 ? "1" : "2"; ?>">
						<td><? echo $dadosDocumento["nome_docm"]; ?></td>
						<td><? echo $dadosDocumento["descr_docm"]; ?></td>
						<td><? echo (int)$dadosDocumento["validade_docm"]; ?> dias</td>
						<td class="alc"><a href="adm_documentos_alt.php?k=<? echo $crypt->encrypt("cod_docm=".$dadosDocumento["cod_docm"]); ?>" title="Alterar"><img src="images/buttons/bt_alterar.gif" alt="Alterar" /></a></td>
					</tr>
<?
	}
}
?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4"><a href="adm_documentos_add.php"><img src="images/buttons/bt_adicionar.gif" alt=" " /></a></td>
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