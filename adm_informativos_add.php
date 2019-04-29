<?php
$iREQ_AUT=1;
$aUSERS_PERM[]=4;

$pageTitle = "Adicionar Informativo";
include "lib/header.inc.php";
$oConteudo = new conteudo();

$listaCategorias = $oConteudo->getListaCategorias();

$mensagem = new mensagens();

$pageAction = $_POST["ac"] ? $crypt->decrypt($_POST["ac"]) : "";

if ($pageAction == "adicionar") {
	$oConteudo->beginTransaction();
	$dados["titulo_info"] = $_POST["titulo"];
	$dados["descr_info"] = $_POST["descricao"];
	$dados["cod_ctgr"] = $_POST["categoria"];
	$dados["flgativo_info"] = (string)($_POST["ativo"] == "s" ? "1" : "0");
	$bInsercao = $oConteudo->addInformativo($dados);
	if ($oConteudo->getErrNo() == 0) {
		$erroInfoTemplate = false;
		if (@count($_SESSION["tmpListaTemplatesAtribuidos"]) > 0) {
			$codInsertInfo = $oConteudo->insertId;
			$ordemInfoTemplate = 1;
			foreach ($_SESSION["tmpListaTemplatesAtribuidos"] as $dadosInfoTemplate) {
				$dadosInfoTemplate["cod_ctgr"] = $_POST["categoria"];
				$dadosInfoTemplate["cod_info"] = $codInsertInfo;
				$dadosInfoTemplate["ordem_intp"] = $ordemInfoTemplate;
				$ordemInfoTemplate++;
				$oConteudo->addTemplatesInformativo($dadosInfoTemplate);
				if ((int)$oConteudo->errno !== 0 || (int)$oConteudo->qrcount !== 1) {
					$erroInfoTemplate = true;
				}
			}
		}
		
		if ($erroInfoTemplate === false) {
			$mensagem->setMensagem("Informativo inserido com sucesso", MSG_SUCESSO);
			unset($_POST);
			unset($_SESSION["tmpListaTemplatesDisponiveis"]);
			unset($_SESSION["tmpListaTemplatesAtribuidos"]);
		} else {
			$mensagem->setMensagem("Houve um erro ao inserir o informativo. Tente novamente.", MSG_ERRO);
			$oConteudo->rollbackTransaction();
		}
	} elseif ($oConteudo->getErrNo() == 1062) {
		$mensagem->setMensagem("Já existe um informativo com este título.", MSG_ALERTA);
	} else {
		$mensagem->setMensagem("O informativo não foi inserido. Tente novamente.", MSG_ERRO);
	}
	$oConteudo->commitTransaction();
}

if (!$_SESSION["tmpListaTemplatesDisponiveis"]) {
	$_SESSION["tmpListaTemplatesDisponiveis"] = $oConteudo->getTemplatesInformativo();
}

if (eregi("^([0-9]+)_add$", $pageAction)) {
	$tmplCodAdicionar = eregi_replace("^([0-9]+)_add$", "\\1", $pageAction);
	if ($_SESSION["tmpListaTemplatesDisponiveis"][$tmplCodAdicionar]) {
		$tmpSessData = $_SESSION["tmpListaTemplatesAtribuidos"];
		if (@count($tmpSessData) > 0) {
			$tmpSessData = array_values($tmpSessData);
			$novoIndice = (int)@count($tmpSessData);
		} else {
			$novoIndice = 0;
		}
		$tmpSessData[$novoIndice] = $_SESSION["tmpListaTemplatesDisponiveis"][$tmplCodAdicionar];
		$tmpSessData[$novoIndice]["indice_original"] = $tmplCodAdicionar;
		unset($_SESSION["tmpListaTemplatesDisponiveis"][$tmplCodAdicionar]);
		$_SESSION["tmpListaTemplatesAtribuidos"] = $tmpSessData;
		unset($tmpSessData);
	}
} elseif (eregi("^([0-9]+)_del$", $pageAction)) {
	$tmplCodExcluir = eregi_replace("^([0-9]+)_del$", "\\1", $pageAction);
	$tmpSessData = $_SESSION["tmpListaTemplatesDisponiveis"];
	$dadosInsercao = $_SESSION["tmpListaTemplatesAtribuidos"][$tmplCodExcluir];
	if ($dadosInsercao["indice_original"]) {
		$tmpSessData[$dadosInsercao["indice_original"]] = $dadosInsercao;
	} else {
		$tmpSessData[] = $dadosInsercao;
	}
	@ksort($tmpSessData);
	$_SESSION["tmpListaTemplatesDisponiveis"] = $tmpSessData;
	unset($_SESSION["tmpListaTemplatesAtribuidos"][$tmplCodExcluir]);
	unset($tmpSessData);
	unset($dadosInsercao);
	$_SESSION["tmpListaTemplatesAtribuidos"] = array_values($_SESSION["tmpListaTemplatesAtribuidos"]);
} elseif (eregi("^([0-9]+)_up$", $pageAction)) {
	$tmplCodUp = eregi_replace("^([0-9]+)_up$", "\\1", $pageAction);
	if ($tmplCodUp > 0) {
		$tmpData = $_SESSION["tmpListaTemplatesAtribuidos"][$tmplCodUp];
		$_SESSION["tmpListaTemplatesAtribuidos"][$tmplCodUp] = $_SESSION["tmpListaTemplatesAtribuidos"][($tmplCodUp-1)];
		$_SESSION["tmpListaTemplatesAtribuidos"][($tmplCodUp-1)] = $tmpData;
		unset($tmpData);
		$_SESSION["tmpListaTemplatesAtribuidos"] = array_values($_SESSION["tmpListaTemplatesAtribuidos"]);
	}
} elseif (eregi("^([0-9]+)_down$", $pageAction)) {
	$tmplCodUp = eregi_replace("^([0-9]+)_down$", "\\1", $pageAction);
	if ($tmplCodUp < (@count($_SESSION["tmpListaTemplatesAtribuidos"])-1)) {
		$tmpData = $_SESSION["tmpListaTemplatesAtribuidos"][$tmplCodUp];
		$_SESSION["tmpListaTemplatesAtribuidos"][$tmplCodUp] = $_SESSION["tmpListaTemplatesAtribuidos"][($tmplCodUp+1)];
		$_SESSION["tmpListaTemplatesAtribuidos"][($tmplCodUp+1)] = $tmpData;
		unset($tmpData);
		$_SESSION["tmpListaTemplatesAtribuidos"] = array_values($_SESSION["tmpListaTemplatesAtribuidos"]);
	}
}

$listaTemplatesDisponiveis = $_SESSION["tmpListaTemplatesDisponiveis"];
$listaTemplatesAtribuidos = $_SESSION["tmpListaTemplatesAtribuidos"];

/*
echo "<pre>";
print_r($listaTemplatesAtribuidos);
echo "</pre>";
*/
?>
<script language="javascript" type="text/javascript" src="js/cadastroInformativo.js"></script>
<form method="post" action="<? echo $php_self; ?>" class="formPadrao" name="frm1" id="frm1">
<input type="hidden" name="ac" value="" />
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
				<td>Título: </td>
				<td><input type="text" name="titulo" id="titulo" value="<? echo $_POST["titulo"]; ?>" /></td>
			</tr>
			<tr>
				<td>Descrição: </td>
				<td><textarea name="descricao" id="descricao"><? echo $_POST["descricao"]; ?></textarea></td>
			</tr>
			<tr>
				<td>Categoria: </td>
				<td>
					<select name="categoria" id="categoria">
						<option value="0">selecione</option>
<?
if ($listaCategorias) {
	foreach ($listaCategorias as $dadosCategoria) {
?>
						<option value="<? echo $dadosCategoria["cod_ctgr"]; ?>"<? echo $_POST["categoria"] == $dadosCategoria["cod_ctgr"] ? " selected" : "";?>><? echo $dadosCategoria["titulo_ctgr"]; ?></option>
<?
	}
}
?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Templates: </td>
				<td>
					<b>Templates inseridos no informativo: </b>
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
									<td>Título</td>
									<td>Descrição</td>
									<td class="alc">Ordem</td>
									<td class="alc">&nbsp;</td>
								</tr>
							</thead>
<?
if (@count($listaTemplatesAtribuidos)) {
	$contador = 0;
?>
							<tbody>
<?
	foreach ($listaTemplatesAtribuidos as $idxDadosTemplatesInformativo => $dadosTemplatesInformativo) {
		$contador++;
?>
								<tr class="tL<? echo $contador%2 ? "1" : "2"; ?>">
									<td><? echo $dadosTemplatesInformativo["titulo_tmpl"]; ?></td>
									<td><? echo $dadosTemplatesInformativo["descr_tmpl"]; ?></td>
									<td class="alc">
<?
		if (count($listaTemplatesAtribuidos) > 1 && (($contador) < count($listaTemplatesAtribuidos))) {
?>
										<a href="javascript:doAction(document.frm1, '<? echo $crypt->encrypt($idxDadosTemplatesInformativo."_down");?>');"><img src="images/buttons/bt_arrdw.gif" alt="Descer uma posição" /></a>
<?
		} 
		if ($contador > 1) {
?>
										<a href="javascript:doAction(document.frm1, '<? echo $crypt->encrypt($idxDadosTemplatesInformativo."_up");?>');"><img src="images/buttons/bt_arrup.gif" alt="Subir uma posição" /></a>
<?
		}
?>
									</td>
									<td class="alc"><a href="javascript:doAction(document.frm1,'<? echo $crypt->encrypt($idxDadosTemplatesInformativo."_del"); ?>');"><img src="images/buttons/bt_excluir.gif" alt=" " /></a></td>
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
					<br />
					<b>Templates disponíveis:</b>
					<div class="tListDiv">
						<table>
							<colgroup>
								<col />
								<col />
								<col width="100" />
							</colgroup>
							<thead>
								<tr>
									<td>Título</td>
									<td>Descrição</td>
									<td class="alc">&nbsp;</td>
								</tr>
							</thead>
<?
if (@count($listaTemplatesDisponiveis)) {
	$contador = 0;
?>
							<tbody>
<?
	foreach ($listaTemplatesDisponiveis as $idxDadosTemplatesInformativo => $dadosTemplatesInformativo) {
		$contador++;
?>
								<tr class="tL<? echo $contador%2 ? "1" : "2"; ?>">
									<td><? echo $dadosTemplatesInformativo["titulo_tmpl"]; ?></td>
									<td><? echo $dadosTemplatesInformativo["descr_tmpl"]; ?></td>
									<td class="alc"><a href="javascript:doAction(document.frm1,'<? echo $crypt->encrypt(((int)$idxDadosTemplatesInformativo)."_add"); ?>');"><img src="images/buttons/bt_adicionar.gif" alt=" " /></a></td>
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
				<td class="alr"><img src="images/buttons/bt_voltar.gif" onclick="goPage('adm_informativos.php')" alt="Cancelar e voltar" class="cursorMao" />&nbsp;<img src="images/buttons/bt_salvar.gif" onclick="validaInformativo(document.getElementById('frm1'),'<? echo $crypt->encrypt("adicionar");?>');" alt="Inserir Informativo" class="cursorMao" /></td>
			</tr>
		</table>
	</div>
	<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
</form>
<?php
include "lib/footer.inc.php";
?>