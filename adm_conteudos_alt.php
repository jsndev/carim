<?php
$iREQ_AUT=1;
$aUSERS_PERM[]=4;

ob_start();

$pageTitle = 'Alterar Conteúdo';
include "lib/header.inc.php";

if ((int)$_GET["cod_cotd"] > 0) {
	ob_end_flush();
} else {
	ob_end_clean();
	header("Location: adm_conteudos.php");
	exit();
}

$oConteudo = new conteudo();
$mensagem = new mensagens();

$dadosConteudo = $oConteudo->getConteudo($_GET["cod_cotd"]);
$_POST["tipoconteudo"] = $dadosConteudo[0]["tipo_cotd"];
$tmpTipoConteudo = $_POST["tipoconteudo"];

if ($crypt->decrypt($_POST["ac"]) == "alterar" && (int)$_GET["cod_cotd"] > 0) {
	if ((int)$_POST["tipoconteudo"] >= 1 && (int)$_POST["tipoconteudo"] <= 3) {
		$oConteudo->beginTransaction();
		$dados["cod_cotd"] = $_GET["cod_cotd"];
		$dados["titulo_cotd"] = $_POST["titulo"];
		$dados["descr_cotd"] = $_POST["descricao"];
		$dados["flgativo_cotd"] = (string)($_POST["ativo"] == "s" ? "1" : "0");
		$dados["tipo_cotd"] = $_POST["tipoconteudo"];
		if ($dados["tipo_cotd"] == "1") {
			$dados["arquivo_cotd"] = "";
			$dados["legenda_cotd"] = "";
			$dados["texto_cotd"] = $_POST["editor"];
			$oConteudo->updConteudo($dados);
			if ($oConteudo->getErrNo() == 0) {
				$mensagem->setMensagem("Conteúdo alterado com sucesso", MSG_SUCESSO);
				$oConteudo->commitTransaction();
			} elseif ($oConteudo->getErrNo() == DB_ERR_UNIQUE) {
				$mensagem->setMensagem("Já existe um conteúdo com este nome. Favor utilizar outro nome.", MSG_ERRO);
			} else {
				$mensagem->setMensagem("Houve problemas com a alteração do conteúdo. Tente novamente.", MSG_ERRO);
			}
		} elseif ($dados["tipo_cotd"] == "3") {
			$dados["arquivo_cotd"] = $dadosConteudo[0]["arquivo_cotd"];
			
			if ((int)$_FILES["imagem"]["error"] == UPLOAD_ERR_OK && (int)$_FILES["imagem"]["size"] > 0) {
				$dados["arquivo_cotd"] = $_FILES["imagem"]["name"];
				$bHasFile = true;
			}
			
			$dados["legenda_cotd"] = $_POST["legendaimagem"];
			$dados["texto_cotd"] = "";
			
			$oConteudo->updConteudo($dados);

			if ($oConteudo->getErrNo() == 0) {
				if ($bHasFile) {
					$nomeArquivoTmp = explode(".",$_FILES["imagem"]["name"]);
					end($nomeArquivoTmp);
					$nomeArquivo = current($nomeArquivoTmp);
					$nomeArquivo = $dados["cod_cotd"] . "." . $nomeArquivo;
					if (copy($_FILES["imagem"]["tmp_name"], "files/".$nomeArquivo)) {
						$nomeArquivoOldTmp = explode(".",$dadosConteudo[0]["arquivo_cotd"]);
						end($nomeArquivoOldTmp);
						$nomeArquivoOld = $dados["cod_cotd"].".".current($nomeArquivoOldTmp);
						
						if ($nomeArquivo != $nomeArquivoOld) {
							unlink("files/".$nomeArquivoOld);
						}
						$mensagem->setMensagem("Conteúdo alterado com sucesso", MSG_SUCESSO);
						$oConteudo->commitTransaction();
					} else {
						$mensagem->setMensagem("Não foi possível gravar a imagem enviada. Tente novamente.", MSG_ERRO);
						$oConteudo->rollbackTransaction();
					}
					
				} else {
					$mensagem->setMensagem("Conteúdo alterado com sucesso", MSG_SUCESSO);
					$oConteudo->commitTransaction();
				}
			} elseif ($oConteudo->getErrNo() == DB_ERR_UNIQUE) {
				$mensagem->setMensagem("Já existe um conteúdo com este nome. Favor utilizar outro nome.", MSG_ERRO);
				$oConteudo->rollbackTransaction();
			} else {
				$mensagem->setMensagem("Houve problemas com a alteração do conteúdo. Tente novamente.", MSG_ERRO);
				$oConteudo->rollbackTransaction();
			}
			@unlink($_FILES["imagem"]["tmp_name"]);
			
		} elseif ($dados["tipo_cotd"] == "2") {
			
			$dados["arquivo_cotd"] = $dadosConteudo[0]["arquivo_cotd"];
			
			if ((int)$_FILES["arquivo"]["error"] == UPLOAD_ERR_OK && (int)$_FILES["arquivo"]["size"] > 0) {
				$dados["arquivo_cotd"] = $_FILES["arquivo"]["name"];
				$bHasFile = true;
			}
			
			$dados["legenda_cotd"] = $_POST["legendaarquivo"];
			$dados["texto_cotd"] = "";
			
			$oConteudo->updConteudo($dados);

			if ($oConteudo->getErrNo() == 0) {
				if ($bHasFile) {
					$nomeArquivoTmp = explode(".",$_FILES["arquivo"]["name"]);
					end($nomeArquivoTmp);
					$nomeArquivo = current($nomeArquivoTmp);
					$nomeArquivo = $dados["cod_cotd"] . "." . $nomeArquivo;
					if (copy($_FILES["arquivo"]["tmp_name"], "files/".$nomeArquivo)) {
						$nomeArquivoOldTmp = explode(".",$dadosConteudo[0]["arquivo_cotd"]);
						end($nomeArquivoOldTmp);
						$nomeArquivoOld = $dados["cod_cotd"].".".current($nomeArquivoOldTmp);
						
						if ($nomeArquivo != $nomeArquivoOld) {
							unlink("files/".$nomeArquivoOld);
						}
						$mensagem->setMensagem("Conteúdo alterado com sucesso", MSG_SUCESSO);
						$oConteudo->commitTransaction();
					} else {
						$mensagem->setMensagem("Não foi possível gravar o arquivo enviado. Tente novamente.", MSG_ERRO);
						$oConteudo->rollbackTransaction();
					}
				} else {
					$mensagem->setMensagem("Conteúdo alterado com sucesso", MSG_SUCESSO);
					$oConteudo->commitTransaction();
				}
			} elseif ($oConteudo->getErrNo() == DB_ERR_UNIQUE) {
				$mensagem->setMensagem("Já existe um conteúdo com este nome. Favor utilizar outro nome.", MSG_ERRO);
				$oConteudo->rollbackTransaction();
			} else {
				$mensagem->setMensagem("Houve problemas com a alteração do conteúdo. Tente novamente.", MSG_ERRO);
				$oConteudo->rollbackTransaction();
			}
			@unlink($_FILES["arquivo"]["tmp_name"]);
		}
		$oConteudo->commitTransaction();
	} else {
		$mensagem->setMensagem("Houve um erro de processamento da sua requisição. Contate o administrador do sistema.", MSG_ERRO);
	}
	$dadosConteudo = $oConteudo->getConteudo($_GET["cod_cotd"]);
}
$_POST["tipoconteudo"] = $tmpTipoConteudo;

if ($_POST["ac"] == "") {
	$_POST["titulo"] = $dadosConteudo[0]["titulo_cotd"];
	$_POST["descricao"] = $dadosConteudo[0]["descr_cotd"];
	$_POST["ativo"] = (int)$dadosConteudo[0]["flgativo_cotd"] == 1 ? "s" : false;
	$_POST["editor"] = $dadosConteudo[0]["texto_cotd"];
	switch ((string)$_POST["tipoconteudo"]) {
		case "2":
			$_POST["legendaarquivo"] = $dadosConteudo[0]["legenda_cotd"];
		break;
		case "3":
			$_POST["legendaimagem"] = $dadosConteudo[0]["legenda_cotd"];
		break;
	}
}

switch ((string)$dadosConteudo[0]["tipo_cotd"]) {
	case "2":
		$dadosArquivo["origname"] = $dadosConteudo[0]["arquivo_cotd"];
		$tmpExtensao = explode(".",$dadosArquivo["origname"]);
		$dadosArquivo["arquivo"] = $_GET["cod_cotd"].".".$tmpExtensao[(@count($tmpExtensao)-1)];
	break;
	case "3":
		$dadosImagem["origname"] = $dadosConteudo[0]["arquivo_cotd"];
		$tmpExtensao = explode(".",$dadosImagem["origname"]);
		$dadosImagem["arquivo"] = $_GET["cod_cotd"].".".$tmpExtensao[(@count($tmpExtensao)-1)];
	break;
}

$_POST["tipoconteudo"] = $_POST["tipoconteudo"] ? $_POST["tipoconteudo"] : "1";
?>
<script language="javascript" type="text/javascript" src="js/cadastroConteudo.js"></script>
<script language="javascript" type="text/javascript" src="tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
tinyMCE.init({
	mode : "exact", 
	elements : "editor", 
	theme : "advanced", 
	language : "pt_br", 
	theme_advanced_path : false, 
	theme_advanced_statusbar_location : "none",
	theme_advanced_buttons1 : "cut,copy,paste,separator,undo,redo,separator,bold,italic,underline,separator,strikethrough,separator,indent,outdent,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,forecolor,backcolor,separator,bullist,numlist,hr",
	theme_advanced_buttons2 : "",
	theme_advanced_buttons3 : "",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_path_location : "bottom",
	plugins : "paste",
	paste_create_paragraphs : false,
	paste_create_linebreaks : false,
	paste_use_dialog : true,
	paste_auto_cleanup_on_paste : true,
	paste_convert_middot_lists : false,
	paste_unindented_list_class : "unindentedList",
	paste_convert_headers_to_strong : true,
	paste_insert_word_content_callback : "convertWord",
	height : "230"
});

function convertWord(type, content) {
	switch (type) {
		case "before":
			content = content.toLowerCase();
			break;
		case "after":
			content = content.toLowerCase();
			break;
	}
	return content;
}

</script>
<form method="post" action="<? echo $php_self; ?>?k=<? echo $crypt->encrypt("cod_cotd=".$_GET["cod_cotd"]); ?>" class="formPadrao" name="frm1" id="frm1" enctype="multipart/form-data">
<input type="hidden" name="ac" value="<? echo $crypt->encrypt("alterar");?>" />
<div class="quadroInterno">
	<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
	<div class="quadroInternoMeio">
		<? echo $mensagem->getMessageBox(); ?>
		<table cellpadding="0" cellspacing="2" class="tbForm" border="0">
			<colgroup>
				<col width="100"></col>
				<col width="600"></col>
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
				<td>Tipo de conteúdo: </td>
				<td valign="middle">
					Texto <input type="radio" onclick="return false;" readonly name="tipoconteudo" id="tipo_1" class="rd" value="1"<? echo $_POST["tipoconteudo"] == "1" ? " checked" : ""; ?> />&nbsp;&nbsp;&nbsp;
					Imagem <input type="radio" onclick="return false;" readonly name="tipoconteudo" id="tipo_3" class="rd" value="3"<? echo $_POST["tipoconteudo"] == "3" ? " checked" : ""; ?> />&nbsp;&nbsp;&nbsp;
					Arquivo <input type="radio" onclick="return false;" readonly name="tipoconteudo" id="tipo_2" class="rd" value="2"<? echo $_POST["tipoconteudo"] == "2" ? " checked" : ""; ?> />
				</td>
			</tr>
			<tr id="boxtipo_1" class="<? echo $_POST["tipoconteudo"] == "1" ? "trow" : "hide"; ?>">
				<td>Texto: </td>
				<td id="editorContainer"><div id="editor"><? echo $_POST["editor"]; ?></div></td>
			</tr>
			<tr id="boxtipo_3_1" class="<? echo $_POST["tipoconteudo"] == "3" ? "trow" : "hide"; ?>">
				<td>Imagem: </td>
				<td>
					<input type="file" name="imagem" id="imagem" onchange="updateContentPreview();" onkeypress="return false;" /><br />
					<div id="imagemPreview"></div>
				</td>
			</tr>
<?
if ($dadosImagem) {
?>
			<tr id="boxtipo_3_3" class="<? echo $_POST["tipoconteudo"] == "3" ? "trow" : "hide"; ?>">
				<td>Imagem atual: </td>
				<td><div style="overflow:auto;"><img src="getimage.php?k=<? echo $crypt->encrypt("img=".$dadosImagem["arquivo"]);?>" alt="Imagem atual cadastrada"></div></td>
			</tr>
<?
}
?>
			<tr id="boxtipo_3_2" class="<? echo $_POST["tipoconteudo"] == "3" ? "trow" : "hide"; ?>">
				<td>Legenda: </td>
				<td><input type="text" name="legendaimagem" id="legendaimagem" value="<? echo $_POST["legendaimagem"]; ?>" /></td>
			</tr>
			
			<tr id="boxtipo_2_1" class="<? echo $_POST["tipoconteudo"] == "2" ? "trow" : "hide"; ?>">
				<td>Arquivo: </td>
				<td><input type="file" name="arquivo" id="arquivo" /></td>
			</tr>
<?
if ($dadosArquivo) {
?>
			<tr id="boxtipo_2_3" class="<? echo $_POST["tipoconteudo"] == "2" ? "trow" : "hide"; ?>">
				<td>Arquivo atual: </td>
				<td><a href="getfile.php?k=<? echo $crypt->encrypt("origname=".$dadosArquivo["origname"]."&arquivo=".$dadosArquivo["arquivo"]);?>" target="_blank"><? echo $dadosArquivo["origname"]; ?></a></td>
			</tr>
<?
}
?>
			<tr id="boxtipo_2_2" class="<? echo $_POST["tipoconteudo"] == "2" ? "trow" : "hide"; ?>">
				<td>Legenda: </td>
				<td><input type="text" name="legendaarquivo" id="legendaarquivo" value="<? echo $_POST["legendaarquivo"]; ?>" /></td>
			</tr>
			<tr>
				<td>Ativo: </td>
				<td><input type="checkbox" name="ativo" id="ativo" value="s" class="ck" <? echo $_POST["ativo"] == "s" ? "checked" : ""; ?> /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td class="alr"><img src="images/buttons/bt_voltar.gif" onclick="goPage('adm_conteudos.php')" alt="Cancelar e voltar" class="cursorMao" />&nbsp;<img src="images/buttons/bt_salvar.gif" onclick="validaConteudoAlt(document.getElementById('frm1'),'<? echo $crypt->encrypt("alterar");?>');" alt="Alterar Conteúdo" class="cursorMao" /></td>
			</tr>
		</table>
	</div>
	<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
</form>
<?php
include "lib/footer.inc.php";
?>