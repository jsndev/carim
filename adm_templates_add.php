<?php
$iREQ_AUT=1;
$aUSERS_PERM[]=4;

$pageTitle = "Adicionar Template";
include "lib/header.inc.php";
$oConteudo = new conteudo();

$mensagem = new mensagens();

$pageAction = $_POST["ac"] ? $crypt->decrypt($_POST["ac"]) : "";

if ($pageAction == "adicionar") {
	$oConteudo->beginTransaction();
	$dados["titulo_tmpl"] = $_POST["titulo"];
	$dados["descr_tmpl"] = $_POST["descricao"];
	$dados["flgativo_tmpl"] = (string)($_POST["ativo"] == "s" ? "1" : "0");
	$bInsercao = $oConteudo->addTemplate($dados);
	if ($oConteudo->getErrNo() == 0) {
		$erroConteudoTemplate = false;
		if (@count($_SESSION["tmpListaConteudosAtribuidos"]) > 0) {
			$codInsertTmpl = $oConteudo->getInsertId();
			$ordemConteudoTemplate = 1;
			foreach ($_SESSION["tmpListaConteudosAtribuidos"] as $dadosConteudoTemplate) {
				$dadosConteudoTemplate["cod_tmpl"] = $codInsertTmpl;
				$dadosConteudoTemplate["ordem_tpco"] = $ordemConteudoTemplate;
				$ordemConteudoTemplate++;
				$oConteudo->addTemplateConteudo($dadosConteudoTemplate);
				if ((int)$oConteudo->errno !== 0 || (int)$oConteudo->qrcount !== 1) {
					$erroConteudoTemplate = true;
				}
			}
		}
		
		if ($erroConteudoTemplate === false) {
			$mensagem->setMensagem("Template inserido com sucesso", MSG_SUCESSO);
			unset($_POST);
			unset($_SESSION["tmpListaConteudosDisponiveis"]);
			unset($_SESSION["tmpListaConteudosAtribuidos"]);
		} else {
			$mensagem->setMensagem("Houve um erro ao inserir o template. Tente novamente.", MSG_ERRO);
			$oConteudo->rollbackTransaction();
		}
	} elseif ($oConteudo->getErrNo() == 1062) {
		$mensagem->setMensagem("Já existe um template com este título.", MSG_ALERTA);
	} else {
		$mensagem->setMensagem("O template não foi inserido. Tente novamente.", MSG_ERRO);
	}
	$oConteudo->commitTransaction();
}

if (!$_SESSION["tmpListaConteudosDisponiveis"]) {
	$_SESSION["tmpListaConteudosDisponiveis"] = $oConteudo->getListaConteudosTemplate();
}

if (eregi("^([0-9]+)_add$", $pageAction)) {
	$tmplCodAdicionar = eregi_replace("^([0-9]+)_add$", "\\1", $pageAction);
	if ($_SESSION["tmpListaConteudosDisponiveis"][$tmplCodAdicionar]) {
		$tmpSessData = $_SESSION["tmpListaConteudosAtribuidos"];
		if (@count($tmpSessData) > 0) {
			$tmpSessData = array_values($tmpSessData);
			$novoIndice = (int)@count($tmpSessData);
		} else {
			$novoIndice = 0;
		}
		$tmpSessData[$novoIndice] = $_SESSION["tmpListaConteudosDisponiveis"][$tmplCodAdicionar];
		$tmpSessData[$novoIndice]["indice_original"] = $tmplCodAdicionar;
		unset($_SESSION["tmpListaConteudosDisponiveis"][$tmplCodAdicionar]);
		$_SESSION["tmpListaConteudosAtribuidos"] = $tmpSessData;
		unset($tmpSessData);
	}
} elseif (eregi("^([0-9]+)_del$", $pageAction)) {
	$tmplCodExcluir = eregi_replace("^([0-9]+)_del$", "\\1", $pageAction);
	$tmpSessData = $_SESSION["tmpListaConteudosDisponiveis"];
	$dadosInsercao = $_SESSION["tmpListaConteudosAtribuidos"][$tmplCodExcluir];
	if ($dadosInsercao["indice_original"]) {
		$tmpSessData[$dadosInsercao["indice_original"]] = $dadosInsercao;
	} else {
		$tmpSessData[] = $dadosInsercao;
	}
	@ksort($tmpSessData);
	$_SESSION["tmpListaConteudosDisponiveis"] = $tmpSessData;
	unset($_SESSION["tmpListaConteudosAtribuidos"][$tmplCodExcluir]);
	unset($tmpSessData);
	unset($dadosInsercao);
	$_SESSION["tmpListaConteudosAtribuidos"] = array_values($_SESSION["tmpListaConteudosAtribuidos"]);
} elseif (eregi("^([0-9]+)_up$", $pageAction)) {
	$tmplCodUp = eregi_replace("^([0-9]+)_up$", "\\1", $pageAction);
	if ($tmplCodUp > 0) {
		$tmpData = $_SESSION["tmpListaConteudosAtribuidos"][$tmplCodUp];
		$_SESSION["tmpListaConteudosAtribuidos"][$tmplCodUp] = $_SESSION["tmpListaConteudosAtribuidos"][($tmplCodUp-1)];
		$_SESSION["tmpListaConteudosAtribuidos"][($tmplCodUp-1)] = $tmpData;
		unset($tmpData);
		$_SESSION["tmpListaConteudosAtribuidos"] = array_values($_SESSION["tmpListaConteudosAtribuidos"]);
	}
} elseif (eregi("^([0-9]+)_down$", $pageAction)) {
	$tmplCodUp = eregi_replace("^([0-9]+)_down$", "\\1", $pageAction);
	if ($tmplCodUp < (@count($_SESSION["tmpListaConteudosAtribuidos"])-1)) {
		$tmpData = $_SESSION["tmpListaConteudosAtribuidos"][$tmplCodUp];
		$_SESSION["tmpListaConteudosAtribuidos"][$tmplCodUp] = $_SESSION["tmpListaConteudosAtribuidos"][($tmplCodUp+1)];
		$_SESSION["tmpListaConteudosAtribuidos"][($tmplCodUp+1)] = $tmpData;
		unset($tmpData);
		$_SESSION["tmpListaConteudosAtribuidos"] = array_values($_SESSION["tmpListaConteudosAtribuidos"]);
	}
}

$ListaConteudosDisponiveis = $_SESSION["tmpListaConteudosDisponiveis"];
$ListaConteudosAtribuidos = $_SESSION["tmpListaConteudosAtribuidos"];

/*
echo "<pre>";
print_r($ListaConteudosAtribuidos);
echo "</pre>";
*/
?>
<script language="javascript" type="text/javascript" src="js/cadastroTemplate.js"></script>
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
				<td>Conteúdos: </td>
				<td>
					<b>Conteúdos inseridos no template: </b>
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
if (@count($ListaConteudosAtribuidos)) {
	$contador = 0;
?>
							<tbody>
<?
	foreach ($ListaConteudosAtribuidos as $idxDadosConteudosTemplate => $dadosConteudosTemplate) {
		$contador++;
?>
								<tr class="tL<? echo $contador%2 ? "1" : "2"; ?>">
									<td><? echo $dadosConteudosTemplate["titulo_cotd"]; ?></td>
									<td><? echo $dadosConteudosTemplate["descr_cotd"]; ?></td>
									<td class="alc">
<?
		if (count($ListaConteudosAtribuidos) > 1 && (($contador) < count($ListaConteudosAtribuidos))) {
?>
										<a href="javascript:doAction(document.frm1, '<? echo $crypt->encrypt($idxDadosConteudosTemplate."_down");?>');"><img src="images/buttons/bt_arrdw.gif" alt="Descer uma posição" /></a>
<?
		} 
		if ($contador > 1) {
?>
										<a href="javascript:doAction(document.frm1, '<? echo $crypt->encrypt($idxDadosConteudosTemplate."_up");?>');"><img src="images/buttons/bt_arrup.gif" alt="Subir uma posição" /></a>
<?
		}
?>
									</td>
									<td class="alc"><a href="javascript:doAction(document.frm1,'<? echo $crypt->encrypt($idxDadosConteudosTemplate."_del"); ?>');"><img src="images/buttons/bt_excluir.gif" alt=" " /></a></td>
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
					<b>Conteúdos disponíveis:</b>
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
if (@count($ListaConteudosDisponiveis)) {
	$contador = 0;
?>
							<tbody>
<?
	foreach ($ListaConteudosDisponiveis as $idxDadosConteudosTemplate => $dadosConteudosTemplate) {
		$contador++;
?>
								<tr class="tL<? echo $contador%2 ? "1" : "2"; ?>">
									<td><? echo $dadosConteudosTemplate["titulo_cotd"]; ?></td>
									<td><? echo $dadosConteudosTemplate["descr_cotd"]; ?></td>
									<td class="alc"><a href="javascript:doAction(document.frm1,'<? echo $crypt->encrypt(((int)$idxDadosConteudosTemplate)."_add"); ?>');"><img src="images/buttons/bt_adicionar.gif" alt=" " /></a></td>
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
				<td class="alr"><img src="images/buttons/bt_voltar.gif" onclick="goPage('adm_templates.php')" alt="Cancelar e voltar" class="cursorMao" />&nbsp;<img src="images/buttons/bt_salvar.gif" onclick="validaTemplate(document.getElementById('frm1'),'<? echo $crypt->encrypt("adicionar");?>');" alt="Inserir Template" class="cursorMao" /></td>
			</tr>
		</table>
	</div>
	<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
</form>
<?php
include "lib/footer.inc.php";
?>