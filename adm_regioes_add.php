<?php
$iREQ_AUT=1;
$aUSERS_PERM[]=4;

$pageTitle = "Adicionar Região";
include "lib/header.inc.php";

$oRegiao = new regiao();
$oMunicipio = new municipio();

$mensagem = new mensagens();

$pageAction = $crypt->decrypt($_POST["ac"]);
if ($pageAction == "adicionar") {
	
	$oRegiao->beginTransaction();
	
	$dadosRegiao['nome_regi'] = $_POST['nome'];
	$dadosRegiao['descr_regi'] = $_POST['descricao'];
	$dadosRegiao['flgativo_regi'] = $_POST['ativo'] ? '1' : '0';
	
	$bInsercao = $oRegiao->addRegiao($dadosRegiao);
	if ($oRegiao->getErrNo() == 0) {
		$iCodRegi = $oRegiao->getInsertId();
		$bIsMunicipioRegiaoErro = false;
		if (is_array($_SESSION["regiaoMunicipios"]) && @count($_SESSION["regiaoMunicipios"]) > 0) {
			foreach ($_SESSION["regiaoMunicipios"] as $dadosTmpSess) {
				$dadosRegiaoMunicipio["cod_regi"] = $iCodRegi;
				$dadosRegiaoMunicipio["cod_uf"] = $dadosTmpSess["uf"];
				$dadosRegiaoMunicipio["cod_municipio"] = $dadosTmpSess["municipio"];
				$oRegiao->addRegiaoMunicipio($dadosRegiaoMunicipio);
				if ($oRegiao->getErrNo() != 0) {
					$bIsMunicipioRegiaoErro = true;
				}
			}
			if ($bIsMunicipioRegiaoErro) {
				$oRegiao->rollbackTransaction();
				$mensagem->setMensagem("Houve um erro ao cadastrar um ou mais municípios. Favor tentar novamente.", MSG_ERRO);
			} else {
				$mensagem->setMensagem("A região foi inserida com sucesso.", MSG_SUCESSO);
				unset($_SESSION["regiaoMunicipios"]);
				unset($_POST);
			}
		}
	} elseif ($oRegiao->getErrNo() == DB_ERR_UNIQUE) {
		$oRegiao->rollbackTransaction();
		$mensagem->setMensagem("Já existe uma região com este nome cadastrada.", MSG_ERRO);
	} else {
		$oRegiao->rollbackTransaction();
		$mensagem->setMensagem("Houve um erro ao cadastrar a região. Favor tentar novamente.", MSG_ERRO);
	}
	$oRegiao->commitTransaction();
	
} elseif ($pageAction == "addMunicipio" && $_POST["uf"] != "" && $_POST["municipios"] != "") {
	$tmpRegiaoMunicipios["uf"] = $_POST["uf"];
	$tmpRegiaoMunicipios["municipio"] = $_POST["municipios"];
	$isIncluded = false;
	if (is_array($_SESSION["regiaoMunicipios"]) && @count($_SESSION["regiaoMunicipios"]) > 0) {
		foreach ($_SESSION["regiaoMunicipios"] as $dadosTmpSess) {
			if ($dadosTmpSess["uf"] == $tmpRegiaoMunicipios["uf"] && $dadosTmpSess["municipio"] == $tmpRegiaoMunicipios["municipio"]) {
				$isIncluded = true;
			}
		}
	}
	if (!$isIncluded) {
		$_SESSION["regiaoMunicipios"][] = $tmpRegiaoMunicipios;
	}
} elseif (eregi("^([0-9]+)_del$", $pageAction)) {
	$idxToDel = eregi_replace("^([0-9]+)_del$", "\\1", $pageAction);
	unset($_SESSION["regiaoMunicipios"][$idxToDel]);
}

$aListaMunicipiosAtribuidos = (@count($_SESSION["regiaoMunicipios"]) > 0) ? $_SESSION["regiaoMunicipios"] : false;

$aListaUf = $oMunicipio->getListaUf();
?>
<script language="javascript" type="text/javascript" src="js/cadastroRegiao.js"></script>
<script language="javascript" type="text/javascript" src="js/ajaxapi.js"></script>
<form method="post" action="<? echo $php_self; ?>" class="formPadrao" name="frm1" id="frm1">
<input type="hidden" name="ac" value="<? echo $crypt->encrypt("adicionar");?>" />
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
				<td>Região: </td>
				<td><input type="text" name="nome" id="nome" value="<? echo $_POST["nome"]; ?>" /></td>
			</tr>
			<tr>
				<td>Descrição: </td>
				<td><textarea name="descricao" id="descricao"><? echo $_POST["descricao"]; ?></textarea></td>
			</tr>
			<tr>
				<td>Municípios: </td>
				<td>
					<select name="uf" id="uf" style="width: 50px;" onchange="getListaMunicipios(this);">
						<option value="">UF</option>
<?php
if (@count($aListaUf) > 0) {
	foreach ($aListaUf as $aDadosUf) {
?>
						<option value="<?php echo $aDadosUf["cod_uf"]; ?>"<? echo $aDadosUf["cod_uf"] == $_POST["uf"] ? ' selected="selected"' : ''; ?>><?php echo $aDadosUf["nome_uf"]; ?></option>
<?php
	}
}
?>
					</select>&nbsp;
					<select name="municipios" id="municipios" style="width: 200px;">
						<option value="">Selecione o estado</option>
					</select>
					&nbsp;&nbsp;
					<a href="javascript:addMunicipioRegiao(document.frm1, '<? echo $crypt->encrypt("addMunicipio");?>');"><img src="images/buttons/bt_adicionar.gif" alt=" " class="vAlMid" /></a>
					<br /><br />
					<div class="tListDiv">
						<table>
							<colgroup>
								<col />
								<col />
								<col width="100" />
							</colgroup>
							<thead>
								<tr>
									<td>UF</td>
									<td>Município</td>
									<td class="alc">&nbsp;</td>
								</tr>
							</thead>
<?
if ((int)count($aListaMunicipiosAtribuidos) > 0 && is_array($aListaMunicipiosAtribuidos)) {
	$contador = 0;
?>
							<tbody>
<?
	foreach ($aListaMunicipiosAtribuidos as $idxDadosMunicipiosAtribuidos => $dadosMunicipiosAtribuidos) {
		$dadosMunicipios = $oMunicipio->getMunicipio($dadosMunicipiosAtribuidos["municipio"]);
		$contador++;
?>
								<tr class="tL<? echo $contador%2 ? "1" : "2"; ?>">
									<td><? echo $dadosMunicipiosAtribuidos["uf"]; ?></td>
									<td><? echo $dadosMunicipios[0]["nome_municipio"]; ?></td>
									<td class="alc"><a href="javascript:doAction(document.frm1,'<? echo $crypt->encrypt($idxDadosMunicipiosAtribuidos."_del"); ?>');"><img src="images/buttons/bt_excluir.gif" alt=" " /></a></td>
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
				<td>Ativo: </td>
				<td><input type="checkbox" name="ativo" id="ativo" value="s" class="ck" <? echo $_POST["ativo"] == "s" ? "checked" : ""; ?> /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td class="alr"><img src="images/buttons/bt_voltar.gif" onclick="goPage('adm_regioes.php')" alt="Cancelar e voltar" class="cursorMao" />&nbsp;<img src="images/buttons/bt_salvar.gif" onclick="validaRegiao(document.getElementById('frm1'));" alt="Inserir Região" class="cursorMao" /></td>
			</tr>
		</table>
	</div>
	<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
</form>
<?php
include "lib/footer.inc.php";
?>