<?php
$iREQ_AUT=1;
$aUSERS_PERM[]=4;
ob_start();

$pageTitle = "Alterar Município";
include "lib/header.inc.php";

if ((int)$_GET["cod_municipio"] > 0) {
	ob_end_flush();
} else {
	ob_end_clean();
	header("Location: adm_municipios.php");
	exit();
}
$oMunicipio = new municipio();
$oMunicipioDocumento = new municipiodocumento();
$mensagem = new mensagens();
$pageAction = $crypt->decrypt($_POST["ac"]);
$aDadoMunicipio = $oMunicipio->pesquisarPorMunicipio($_GET["cod_municipio"]);//getMunicipio();
$cod_uf = $aDadoMunicipio[0]["cod_uf"];
$cod_municipio = $_GET["cod_municipio"];

if ($pageAction == "alterar") {
	$oMunicipio->atualizarPk($cod_uf,$cod_municipio,$aDadoMunicipio[0]["nome_municipio"],$_POST['obschecklist']);
	if ($oMunicipio->errno == "0") {
		$mensagem->setMensagem("Dados atualizados com sucesso.", MSG_SUCESSO);
	} else {
		$mensagem->setMensagem("Erro ao atualizar os dados.", MSG_ERRO);
	}
} elseif ($pageAction == "addDocumento") {
	$oMunicipio->atualizarPk($cod_uf,$cod_municipio,$aDadoMunicipio[0]["nome_municipio"],$_POST['obschecklist']);
	$oMunicipioDocumento->inserir($cod_uf,$cod_municipio,$_POST['documento'],$_POST['documento_detalhes'],$_POST['entidade'],$_POST['entidade_detalhes'],$_POST['validade'],'1',($_POST['flgproponente'] ? 'S' : ''),($_POST['flgvendfis'] ? 'S' : ''),($_POST['flgvendjur'] ? 'S' : ''),($_POST['flgimovel'] ? 'S' : ''),($_POST['flgproponenteconjuge'] ? 'S' : ''),($_POST['flgvendfisconjuge'] ? 'S' : ''),($_POST['flgfgts']? 'S' : ''));
	if ($oMunicipioDocumento->errno == "0") {
		$mensagem->setMensagem("Dados incluídos com sucesso.", MSG_SUCESSO);
	} else {
		$mensagem->setMensagem("Erro ao atualizar os dados.", MSG_ERRO);
	}
} elseif (eregi("^[0-9]+_del",$pageAction)) {
	$cod_mndc_excluir = eregi_replace("^([0-9]+)_del","\\1",$pageAction);
	$oMunicipioDocumento->deletarPk($cod_mndc_excluir);
	if ($oMunicipioDocumento->errno == "0") {
		$mensagem->setMensagem("Documento removido com sucesso.", MSG_SUCESSO);
	} else {
		$mensagem->setMensagem("Erro ao remover documento.", MSG_ERRO);
	}
} elseif (eregi("^[0-9]+_alt",$pageAction)) {
	$cod_mndc_manter = eregi_replace("^([0-9]+)_alt","\\1",$pageAction);
	$aDadosManterFormulario = $oMunicipioDocumento->pesquisarPk($cod_mndc_manter);
} elseif (eregi("^altDocumento_[0-9]+",$pageAction)) {
	$cod_mndc_alterar = eregi_replace("^altDocumento_([0-9]+)","\\1",$pageAction);
	$oMunicipioDocumento->atualizarPk($cod_uf,$cod_municipio,$cod_mndc_alterar,$_POST['documento'],$_POST['documento_detalhes'],$_POST['entidade'],$_POST['entidade_detalhes'],$_POST['validade'],'1',($_POST['flgproponente'] ? 'S' : ''),($_POST['flgvendfis'] ? 'S' : ''),($_POST['flgvendjur'] ? 'S' : ''),($_POST['flgimovel'] ? 'S' : ''),($_POST['flgproponenteconjuge'] ? 'S' : ''),($_POST['flgvendfisconjuge'] ? 'S' : ''),($_POST['flgfgts'] ? 'S' : ''));
	if ($oMunicipioDocumento->errno == "0") {
		$mensagem->setMensagem("Documento alterado com sucesso.", MSG_SUCESSO);
	} else {
		$mensagem->setMensagem("Erro ao alterar documento.", MSG_ERRO);
	}
}

$aDadosMunicipio = $oMunicipio->pesquisarPorMunicipio($_GET["cod_municipio"]);
$aListaDocumentosAtribuidos = $oMunicipioDocumento->pesquisarPorUfMunicipio($cod_uf,$cod_municipio);

?>
<script language="javascript" type="text/javascript" src="js/cadastroMunicipio.js"></script>
<script language="javascript" type="text/javascript" src="js/ajaxapi.js"></script>
<form method="post" action="<? echo $php_self; ?>?k=<? echo $crypt->encrypt("cod_municipio=".$_GET["cod_municipio"]); ?>" class="formPadrao" name="frm1" id="frm1">
<input type="hidden" name="ac" value="<? echo $crypt->encrypt("alterar");?>" />
<div class="quadroInterno">
	<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
	<div class="quadroInternoMeio">
		<? echo $mensagem->getMessageBox(); ?>
		<table cellpadding="0" cellspacing="2" class="tbForm">
			<colgroup>
				<col width="70"></col>
				<col width="630"></col>
			</colgroup>
			<tr>
				<td>Município: </td>
				<td><b><? echo $aDadosMunicipio[0]["nome_municipio"]; ?></b></td>
			</tr>
			<tr>
				<td>UF: </td>
				<td><b><? echo $aDadosMunicipio[0]["cod_uf"]; ?></b></td>
			</tr>
			<tr>
				<td>Observações: </td>
				<td><textarea name="obschecklist" id="obschecklist"><? echo $aDadosMunicipio[0]["obschecklist_municipio"]; ?></textarea></td>
			</tr>
			<tr>
				<td colspan="2"><br />
					<div style="padding: 10px; border: 1px solid #CCCCCC;">
					<b><? echo $aDadosManterFormulario ? "Alterar" : "Incluir"; ?> documento</b><br />
						<table>
							<tr>
								<td>Documento:</td>
								<td><input type="text" name="documento" id="documento" value="<? echo $aDadosManterFormulario[0]['documento_mndc']; ?>" /></td>
							</tr>
							<tr>
								<td>Detalhes:</td>
								<td><textarea name="documento_detalhes" id="documento_detalhes"><? echo $aDadosManterFormulario[0]['descr_documento_mndc']; ?></textarea></td>
							</tr>
							<tr>
								<td>Entidade:</td>
								<td><input type="text" name="entidade" id="entidade" value="<? echo $aDadosManterFormulario[0]['entidade_mndc']; ?>" /></td>
							</tr>
							<tr>
								<td>Detalhes:</td>
								<td><textarea name="entidade_detalhes" id="entidade_detalhes"><? echo $aDadosManterFormulario[0]['descr_entidade_mndc']; ?></textarea></td>
							</tr>
							<tr>
								<td>Validade:</td>
								<td><input type="text" name="validade" id="validade" value="<? echo $aDadosManterFormulario[0]['prazo_mndc']; ?>" style="width:50px;" /> dias</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>
									<table>
										<tr>
											<td><input type="checkbox" name="flgimovel" id="flgimovel"<? echo $aDadosManterFormulario[0]["flgimovel_mndc"] == "S" ? ' checked="checked"' : '';?> class="ck" /> Imóvel</td>
											<td><input type="checkbox" name="flgproponente" id="flgproponente"<? echo $aDadosManterFormulario[0]["flgproponente_mndc"] == "S" ? ' checked="checked"' : '';?> class="ck" /> Proponente</td>
											<td><input type="checkbox" name="flgproponenteconjuge" id="flgproponenteconjuge"<? echo $aDadosManterFormulario[0]["flgconjugeproponente_mndc"] == "S" ? ' checked="checked"' : '';?> class="ck" /> Cônjuge do Proponente</td>
											<td><input type="checkbox" name="flgfgts" value="S" id="flgfgts"<? echo $aDadosManterFormulario[0]["flgfgts_mndc"] == "S" ? ' checked="checked"' : '';?> class="ck" /> FGTS</td>
										</tr>
										<tr>
											<td><input type="checkbox" name="flgvendfis" id="flgvendfis"<? echo $aDadosManterFormulario[0]["flgvendedorpf_mndc"] == "S" ? ' checked="checked"' : '';?> class="ck" /> Vendedor PF</td>
											<td><input type="checkbox" name="flgvendfisconjuge" id="flgvendfisconjuge"<? echo $aDadosManterFormulario[0]["flgconjugevendpf_mndc"] == "S" ? ' checked="checked"' : '';?> class="ck" /> Cônjuge do Vendedor PF</td>
											<td colspan="2"><input type="checkbox" name="flgvendjur" id="flgvendjur"<? echo $aDadosManterFormulario[0]["flgvendedorpj_mndc"] == "S" ? ' checked="checked"' : '';?> class="ck" /> Vendedor PJ</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<?
						if ($aDadosManterFormulario) {
						?>
						<p style=""><a href="javascript:addDocumentoMunicipio(document.frm1, '<? echo $crypt->encrypt("altDocumento_".$aDadosManterFormulario[0]["cod_mndc"]);?>');"><img src="images/buttons/bt_salvar.gif" alt=" " class="vAlMid" /></a>&nbsp;<a href="javascript:addDocumentoMunicipio(document.frm1, '<? echo $crypt->encrypt("dummyAction");?>');"><img src="images/buttons/bt_cancelar.gif" alt=" " class="vAlMid" /></a></p>
						<?
						} else {
						?>
						<p style=""><a href="javascript:addDocumentoMunicipio(document.frm1, '<? echo $crypt->encrypt("addDocumento");?>');"><img src="images/buttons/bt_adicionar.gif" alt=" " class="vAlMid" /></a></p>
						<?
						}
						?>
					</div>
					<br /><br />
					<b>Documentos atribuídos: </b><br />
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
									<td>Documento</td>
									<td>Entidade</td>
									<td class="alc">&nbsp;</td>
								</tr>
							</thead>
<?
if ((int)count($aListaDocumentosAtribuidos) > 0 && is_array($aListaDocumentosAtribuidos)) {
	$contador = 0;
?>
							<tbody>
<?
	foreach ($aListaDocumentosAtribuidos as $idxDadosDocumentoAtribuido => $dadosDocumentoAtribuido) {
		$contador++;
?>
								<tr class="tL<? echo $contador%2 ? "1" : "2"; ?>">
									<td valign="top"><? echo $dadosDocumentoAtribuido["documento_mndc"].($dadosDocumentoAtribuido["descr_documento_mndc"] ? "<br />".$dadosDocumentoAtribuido["descr_documento_mndc"] : ""); ?></td>
									<td valign="top"><? echo $dadosDocumentoAtribuido["entidade_mndc"].($dadosDocumentoAtribuido["descr_entidade_mndc"] ? "<br />".$dadosDocumentoAtribuido["descr_entidade_mndc"] : ""); ?></td>
									<td valign="top" rowspan="2" class="alc"><a href="javascript:doAction(document.frm1,'<? echo $crypt->encrypt($dadosDocumentoAtribuido["cod_mndc"]."_alt"); ?>');"><img src="images/buttons/bt_alterar.gif" alt=" " /></a><br /><br /><a href="javascript:delDocumentoMunicipio(document.frm1,'<? echo $crypt->encrypt($dadosDocumentoAtribuido["cod_mndc"]."_del"); ?>','<? echo addslashes($dadosDocumentoAtribuido["documento_mndc"]); ?>');"><img src="images/buttons/bt_excluir.gif" alt=" " /></a></td>
								</tr>
								<tr class="tL<? echo $contador%2 ? "1" : "2"; ?>">
									<td colspan="2">
										<table style="border:0px;">
											<tr>
												<td width="106" style="border:0px;"><input type="checkbox" class="ck" readonly="readonly" disabled="disabled"<? echo $dadosDocumentoAtribuido["flgimovel_mndc"] == "S" ? ' checked="checked"' : '';?> /> Imóvel</td>
												<td width="181" style="border:0px;"><input type="checkbox" class="ck" readonly="readonly" disabled="disabled"<? echo $dadosDocumentoAtribuido["flgproponente_mndc"] == "S" ? ' checked="checked"' : '';?> /> Proponente</td>
												<td width="144" style="border:0px;"><input type="checkbox" class="ck" readonly="readonly" disabled="disabled"<? echo $dadosDocumentoAtribuido["flgconjugeproponente_mndc"] == "S" ? ' checked="checked"' : '';?> /> Cônjuge do Proponente</td>
												<td width="87" style="border:0px;"><input type="checkbox" class="ck" readonly="readonly" disabled="disabled"<? echo $dadosDocumentoAtribuido["flgfgts_mndc"] == "S" ? ' checked="checked"' : '';?> /> FGTS</td>
											</tr>
											<tr>
												<td style="border:0px;"><input type="checkbox" class="ck" readonly="readonly" disabled="disabled"<? echo $dadosDocumentoAtribuido["flgvendedorpf_mndc"] == "S" ? ' checked="checked"' : '';?> /> Vendedor PF</td>
												<td style="border:0px;"><input type="checkbox" class="ck" readonly="readonly" disabled="disabled"<? echo $dadosDocumentoAtribuido["flgconjugevendpf_mndc"] == "S" ? ' checked="checked"' : '';?> /> Cônjuge do Vendedor PF</td>
												<td style="border:0px;"><input type="checkbox" class="ck" readonly="readonly" disabled="disabled"<? echo $dadosDocumentoAtribuido["flgvendedorpj_mndc"] == "S" ? ' checked="checked"' : '';?> /> Vendedor PJ</td>
											</tr>
										</table>
									</td>
								</tr>
<?
	}
?>
							</tbody>
<?
}
?>
						</table>
					</div>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td class="alr"><br /><img src="images/buttons/bt_voltar.gif" onclick="goPage('adm_municipios.php')" alt="Cancelar e voltar" class="cursorMao" />&nbsp;<img src="images/buttons/bt_salvar.gif" onclick="validaMunicipioAlt(document.getElementById('frm1'));" alt="Salvar" class="cursorMao" /></td>
			</tr>
		</table>
	</div>
	<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
</form>
<?php
include "lib/footer.inc.php";
?>