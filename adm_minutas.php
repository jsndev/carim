<?php
$iREQ_AUT=1;
$aUSERS_PERM[]=4;
$pageTitle = "Minuta de Contrato";
include "lib/header.inc.php";

$oContrato = new contrato();
$oMensagem = new mensagens();

$listaMinutas = $oContrato->getMinuta();
?>
<div class="quadroInterno">
	<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
	<div class="quadroInternoMeio">
		<? echo $oMensagem->getMessageBox(); ?>
		<div class="tListDiv">
			<table>
				<colgroup>
					<col />
					<col width="100" />
				</colgroup>
				<thead>
					<tr>
						<td>Nome</td>
						<td class="alc">&nbsp;</td>
					</tr>
				</thead>
				<tbody>
<?
if (@count($listaMinutas) > 0) {
	foreach ($listaMinutas as $idxDadoMinuta => $aDadoMinuta) {
?>
					<tr class="tL<? echo $idxDadoMinuta%2 ? "1" : "2"; ?>">
						<td><? echo $aDadoMinuta["nome_minu"]; ?></td>
						<td class="alc"><a href="adm_minutas_alt.php?k=<? echo $crypt->encrypt("cod_minu=".$aDadoMinuta["cod_minu"]); ?>" title="Alterar"><img src="images/buttons/bt_alterar.gif" alt="Alterar" /></a></td>
					</tr>
<?
	}
}
?>
				</tbody>
			</table>
		</div>
	</div>
	<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
<?php
include "lib/footer.inc.php";
?>