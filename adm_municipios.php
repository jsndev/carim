<?php
$iREQ_AUT=1;
$aUSERS_PERM[]=4;

$pageTitle = "Municípios";
include "lib/header.inc.php";

$oMunicipio = new municipio();
$oMensagem = new mensagens();

if ($_POST["municipio"]) {
	$_POST["municipio"] = eregi_replace("\%", "", $_POST["municipio"]);
	$_SESSION["admMunicipios"]["searchMunicipio"] = trim($_POST["municipio"]);
}
if ($_POST["uf"]) {
	$_SESSION["admMunicipios"]["searchUf"] = $_POST["uf"];
}
$aListaUf = $oMunicipio->getListaUf();

if ($_SESSION["admMunicipios"]["searchUf"] != "" && strlen(trim($_SESSION["admMunicipios"]["searchMunicipio"])) >= 3) {
	$aListaMunicipios = $oMunicipio->searchMunicipio($_SESSION["admMunicipios"]["searchUf"], $_SESSION["admMunicipios"]["searchMunicipio"]);
	if (!$aListaMunicipios) {
		$oMensagem->setMensagem("Sua pesquisa não retornou resultados.", MSG_ALERTA);
	}
}

unset($_SESSION["documentoMunicipio"]);

?>
<script language="javascript" type="text/javascript" src="js/cadastroMunicipio.js"></script>
<form method="post" action="<? echo $php_self; ?>" class="formPadrao" name="frm1" id="frm1">
<div class="quadroInterno">
	<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
	<div class="quadroInternoMeio">
		<b>Pesquisa de municípios: </b>
		<table cellpadding="3" cellspacing="0">
			<tr>
				<td>UF: </td>
				<td>
					<select name="uf" id="uf" style="width: 300px;">
						<option value="">selecione</option>
<?
if (is_array($aListaUf) && @count($aListaUf) > 0) {
	foreach ($aListaUf as $aDadoUf) {
?>
						<option value="<? echo $aDadoUf["cod_uf"]; ?>"<? echo $aDadoUf["cod_uf"] == $_SESSION["admMunicipios"]["searchUf"] ? " selected=\"selected\"" : "";?>><? echo $aDadoUf["nome_uf"]; ?></option>
<?
	}
}
?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Município: </td>
				<td><input style="width: 300px;" type="text" name="municipio" id="municipio" value="<? echo $_SESSION["admMunicipios"]["searchMunicipio"]; ?>" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td class="alr"><img src="images/buttons/bt_filtrar.gif" onclick="pesquisarMunicipios();" alt="Filtrar" class="cursorMao" /></td>
			</tr>
		</table>
	</div>
	<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
<br />
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
						<td>Município</td>
						<td class="alc">&nbsp;</td>
					</tr>
				</thead>
				<tbody>
<?
if (@count($aListaMunicipios) > 0) {
	foreach ($aListaMunicipios as $iIdxDadoMunicipio => $aDadoMunicipio) {
?>
					<tr class="tL<? echo $iIdxDadoMunicipio%2 ? "1" : "2"; ?>">
						<td><? echo $aDadoMunicipio["nome_municipio"]; ?></td>
						<td class="alc"><a href="adm_municipios_alt.php?k=<? echo $crypt->encrypt("cod_municipio=".$aDadoMunicipio["cod_municipio"]); ?>" title="Alterar"><img src="images/buttons/bt_alterar.gif" alt="Alterar" /></a></td>
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
</form>
<?php
include "lib/footer.inc.php";
?>