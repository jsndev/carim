<?php

$iREQ_AUT=1;
$aUSERS_PERM[]=4;

$pageTitle = "Taxas";
include "lib/header.inc.php";

$oTaxa = new taxa();
$oMensagem = new mensagens();

$listaTaxas = $oTaxa->getListaTaxas();
?>
<script language="javascript" type="text/javascript" src="js/cadastroTaxa.js"></script>
<div class="quadroInterno">
	<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
	<div class="quadroInternoMeio">
		<? echo $oMensagem->getMessageBox(); ?>
		<div class="tListDiv">
			<table>
				<colgroup>
					<col />
					<col />
					<col width="60" />
					<col width="100" />
				</colgroup>
				<thead>
					<tr>
						<td>Taxa</td>
						<td>Descrição</td>
						<td class="alr">Valor</td>
						<td class="alc">&nbsp;</td>
					</tr>
				</thead>
				<tbody>
<?
if (@count($listaTaxas) > 0) {
	foreach ($listaTaxas as $idxDadoTaxa => $dadoTaxa) {
?>
					<tr class="tL<? echo $idxDadoTaxa%2 ? "1" : "2"; ?>">
						<td><? echo $dadoTaxa["nome_taxa"]; ?></td>
						<td><? echo $dadoTaxa["descr_taxa"]; ?></td>
						<td class="alr"><? echo number_format((float)$dadoTaxa["valor_taxa"], 2, ",", "."); ?> %</td>
						<td class="alc"><a href="adm_taxas_alt.php?k=<? echo $crypt->encrypt("cod_taxa=".$dadoTaxa["cod_taxa"]); ?>" title="Alterar"><img src="images/buttons/bt_alterar.gif" alt="Alterar" /></a></td>
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