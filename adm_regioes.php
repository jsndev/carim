<?php

$iREQ_AUT=1;
$aUSERS_PERM[]=4;

$pageTitle = "Regiões";
include "lib/header.inc.php";

unset($_SESSION["regiaoMunicipios"]);

$oRegiao = new regiao();
$oMensagem = new mensagens();

$listaRegioes = $oRegiao->getListaRegiao();
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
					<col width="45" />
					<col width="100" />
				</colgroup>
				<thead>
					<tr>
						<td>Região</td>
						<td>Descrição</td>
						<td class="alc">Ativo</td>
						<td class="alc">&nbsp;</td>
					</tr>
				</thead>
				<tbody>
<?
if (@count($listaRegioes) > 0) {
	foreach ($listaRegioes as $idxDadosRegiao => $dadosRegiao) {
?>
					<tr class="tL<? echo $idxDadosRegiao%2 ? "1" : "2"; ?>">
						<td><? echo $dadosRegiao["nome_regi"]; ?></td>
						<td><? echo $dadosRegiao["descr_regi"]; ?></td>
						<td class="alc"><img src="images/layout/indic_v<? echo $dadosRegiao["flgativo_regi"] == "1" ? "d" : "m"; ?>.gif" alt="<? echo $dadosRegiao["flgativo_regi"] == "1" ? "Ativo" : "Inativo"; ?>" /></td>
						<td class="alc"><a href="adm_regioes_alt.php?k=<? echo $crypt->encrypt("cod_regi=".$dadosRegiao["cod_regi"]); ?>" title="Alterar"><img src="images/buttons/bt_alterar.gif" alt="Alterar" /></a></td>
					</tr>
<?
	}
}
?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4"><a href="adm_regioes_add.php"><img src="images/buttons/bt_adicionar.gif" alt=" " /></a></td>
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