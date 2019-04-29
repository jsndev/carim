<?php
$iREQ_AUT=1;
$aUSERS_PERM[]=4;

$pageTitle = "Adicionar Entidade";
include "lib/header.inc.php";

$oEntidade = new entidade();
/*$oMunicipio = new municipio();*/

$mensagem = new mensagens();

$pageAction = $crypt->decrypt($_POST["ac"]);
if ($pageAction == "adicionar") {
	
	$oEntidade->beginTransaction();
	
	$dadosEntidade['nome_enti'] = $_POST['nome'];
	$dadosEntidade['descr_enti'] = $_POST['descricao'];
	
	$bInsercao = $oEntidade->addEntidade($dadosEntidade);
	if ($oEntidade->getErrNo() == 0) {
/*
		$iCodEnti = $oEntidade->getInsertId();
		$bIsMunicipioEntidadeErro = false;
		if (is_array($_SESSION["entidadeMunicipios"]) && @count($_SESSION["entidadeMunicipios"]) > 0) {
			foreach ($_SESSION["entidadeMunicipios"] as $dadosTmpSess) {
				$dadosEntidadeMunicipio["cod_enti"] = $iCodEnti;
				$dadosEntidadeMunicipio["cod_uf"] = $dadosTmpSess["uf"];
				$dadosEntidadeMunicipio["cod_municipio"] = $dadosTmpSess["municipio"];
				$oEntidade->addEntidadeMunicipio($dadosEntidadeMunicipio);
				if ($oEntidade->getErrNo() != 0) {
					$bIsMunicipioEntidadeErro = true;
				}
			}
			if ($bIsMunicipioEntidadeErro) {
				$oEntidade->rollbackTransaction();
				$mensagem->setMensagem("Houve um erro ao cadastrar um ou mais municípios. Favor tentar novamente.", MSG_ERRO);
			} else {*/
				$mensagem->setMensagem("A entidade foi inserida com sucesso.", MSG_SUCESSO);
				/*unset($_SESSION["entidadeMunicipios"]);*/
				unset($_POST);
			/*}
		}*/
	} elseif ($oEntidade->getErrNo() == DB_ERR_UNIQUE) {
		$oEntidade->rollbackTransaction();
		$mensagem->setMensagem("Já existe uma entidade com este nome cadastrada.", MSG_ERRO);
	} else {
		$oEntidade->rollbackTransaction();
		$mensagem->setMensagem("Houve um erro ao cadastrar a entidade. Favor tentar novamente.", MSG_ERRO);
	}
	$oEntidade->commitTransaction();
	
}/* elseif ($pageAction == "addMunicipio" && $_POST["uf"] != "" && $_POST["municipios"] != "") {
	$tmpEntidadeMunicipios["uf"] = $_POST["uf"];
	$tmpEntidadeMunicipios["municipio"] = $_POST["municipios"];
	$isIncluded = false;
	if (is_array($_SESSION["entidadeMunicipios"]) && @count($_SESSION["entidadeMunicipios"]) > 0) {
		foreach ($_SESSION["entidadeMunicipios"] as $dadosTmpSess) {
			if ($dadosTmpSess["uf"] == $tmpEntidadeMunicipios["uf"] && $dadosTmpSess["municipio"] == $tmpEntidadeMunicipios["municipio"]) {
				$isIncluded = true;
			}
		}
	}
	if (!$isIncluded) {
		$_SESSION["entidadeMunicipios"][] = $tmpEntidadeMunicipios;
	}
} elseif (eregi("^([0-9]+)_del$", $pageAction)) {
	$idxToDel = eregi_replace("^([0-9]+)_del$", "\\1", $pageAction);
	unset($_SESSION["entidadeMunicipios"][$idxToDel]);
}

$aListaMunicipiosAtribuidos = (@count($_SESSION["entidadeMunicipios"]) > 0) ? $_SESSION["entidadeMunicipios"] : false;
$aListaUf = $oMunicipio->getListaUf();
*/
?>
<script language="javascript" type="text/javascript" src="js/cadastroEntidade.js"></script>
<script language="javascript" type="text/javascript" src="js/ajaxapi.js"></script>
<form method="post" action="<? echo $php_self; ?>" class="formPadrao" name="frm1" id="frm1">
<input type="hidden" name="ac" value="<? echo $crypt->encrypt("adicionar");?>" />
<div class="quadroInterno">
	<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
	<div class="quadroInternoMeio">
		<? echo $mensagem->getMessageBox(); ?>
		<table cellpadding="0" cellspacing="2" class="tbForm">
		<?/*
			<colgroup>
				<col width="70"></col>
				<col width="630"></col>
			</colgroup>*/?>
			<tr>
				<td>Entidade: </td>
				<td><input type="text" name="nome" id="nome" value="<? echo $_POST["nome"]; ?>" /></td>
			</tr>
			<tr>
				<td>Descrição: </td>
				<td><textarea name="descricao" id="descricao"><? echo $_POST["descricao"]; ?></textarea></td>
			</tr>
			
<? /*
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
					<a href="javascript:addMunicipioEntidade(document.frm1, '<? echo $crypt->encrypt("addMunicipio");?>');"><img src="images/buttons/bt_adicionar.gif" alt=" " class="vAlMid" /></a>
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
*/?>
			<tr>
				<td>&nbsp;</td>
				<td class="alr"><img src="images/buttons/bt_voltar.gif" onclick="goPage('adm_entidades.php')" alt="Cancelar e voltar" class="cursorMao" />&nbsp;<img src="images/buttons/bt_salvar.gif" onclick="validaEntidade(document.getElementById('frm1'));" alt="Inserir Entidade" class="cursorMao" /></td>
			</tr>
		</table>
	</div>
	<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
</form>
<?php
include "lib/footer.inc.php";
?>