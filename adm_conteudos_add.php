<?php
$iREQ_AUT=1;
$aUSERS_PERM[]=4;

$pageTitle = 'Adicionar Conte�do';
include "lib/header.inc.php";
$oConteudo = new conteudo();

$mensagem = new mensagens();

if ($crypt->decrypt($_POST["ac"]) == "adicionar") {
	if ((int)$_POST["tipoconteudo"] >= 1 && (int)$_POST["tipoconteudo"] <= 3) {
		$oConteudo->beginTransaction();
		$dados["titulo_cotd"] = $_POST["titulo"];
		$dados["descr_cotd"] = $_POST["descricao"];
		$dados["flgativo_cotd"] = (string)($_POST["ativo"] == "s" ? "1" : "0");
		$dados["tipo_cotd"] = $_POST["tipoconteudo"];
		if ($dados["tipo_cotd"] == "1") {
			$dados["arquivo_cotd"] = "";
			$dados["legenda_cotd"] = "";
			$dados["texto_cotd"] = $_POST["editor"];
			$oConteudo->addConteudo($dados);
			if ($oConteudo->getErrNo() == 0 && $oConteudo->getInsertId() !== false) {
				$mensagem->setMensagem("Conte�do inserido com sucesso", MSG_SUCESSO);
				$oConteudo->commitTransaction();
				unset($_POST);
			} elseif ($oConteudo->getErrNo() == DB_ERR_UNIQUE) {
				$mensagem->setMensagem("J� existe um conte�do com este nome. Favor utilizar outro nome.", MSG_ERRO);
			} else {
				$mensagem->setMensagem("Houve problemas com a inclus�o do conte�do. Tente novamente.", MSG_ERRO);
			}
		} elseif ($dados["tipo_cotd"] == "3") {
			if ((int)$_FILES["imagem"]["error"] == UPLOAD_ERR_OK) {
				//$dados["arquivo_cotd"] = current(end(explode("/",$_FILES["imagem"]["name"])));
				$dados["arquivo_cotd"] = $_FILES["imagem"]["name"];
				if (!$dados["arquivo_cotd"]) {
					$mensagem->setMensagem("Houve problemas com o envio do arquivo de imagem. Favor tentar novamente.", MSG_ERRO);
				} else {
					$dados["legenda_cotd"] = $_POST["legendaimagem"];
					$dados["texto_cotd"] = "";
					$oConteudo->addConteudo($dados);
					if ($oConteudo->getErrNo() == 0 && $oConteudo->getInsertId() !== false) {
						$nomeArquivoTmp = explode(".",$_FILES["imagem"]["name"]);
						end($nomeArquivoTmp);
						$nomeArquivo = current($nomeArquivoTmp);
						$nomeArquivo = $oConteudo->getInsertId() . "." . $nomeArquivo;
						if (copy($_FILES["imagem"]["tmp_name"], "files/".$nomeArquivo)) {
							$mensagem->setMensagem("Conte�do inserido com sucesso", MSG_SUCESSO);
							$oConteudo->commitTransaction();
							unset($_POST);
						} else {
							$mensagem->setMensagem("N�o foi poss�vel gravar a imagem enviada. Tente novamente.", MSG_ERRO);
							$oConteudo->rollbackTransaction();
						}
					} elseif ($oConteudo->getErrNo() == DB_ERR_UNIQUE) {
						$mensagem->setMensagem("J� existe um conte�do com este nome. Favor utilizar outro nome.", MSG_ERRO);
					} else {
						$mensagem->setMensagem("Houve problemas com a inclus�o do conte�do. Tente novamente.", MSG_ERRO);
					}
				}
			} else {
				$mensagem->setMensagem("Houve problemas com o envio do arquivo de imagem. Favor tentar novamente.", MSG_ERRO);
			}
			@unlink($_FILES["imagem"]["tmp_name"]);
		} elseif ($dados["tipo_cotd"] == "2") {
			if ((int)$_FILES["arquivo"]["error"] == UPLOAD_ERR_OK) {
				//$dados["arquivo_cotd"] = current(end(explode("/",$_FILES["imagem"]["name"])));
				$dados["arquivo_cotd"] = $_FILES["arquivo"]["name"];
				if (!$dados["arquivo_cotd"]) {
					$mensagem->setMensagem("Houve problemas com o envio do arquivo. Favor tentar novamente.", MSG_ERRO);
				} else {
					$dados["legenda_cotd"] = $_POST["legendaarquivo"];
					$dados["texto_cotd"] = "";
					$oConteudo->addConteudo($dados);
					if ($oConteudo->getErrNo() == 0 && $oConteudo->getInsertId() !== false) {
						$nomeArquivoTmp = explode(".",$_FILES["arquivo"]["name"]);
						end($nomeArquivoTmp);
						$nomeArquivo = current($nomeArquivoTmp);
						$nomeArquivo = $oConteudo->getInsertId() . "." . $nomeArquivo;
						if (copy($_FILES["arquivo"]["tmp_name"], "files/".$nomeArquivo)) {
							$mensagem->setMensagem("Conte�do inserido com sucesso", MSG_SUCESSO);
							$oConteudo->commitTransaction();
							unset($_POST);
						} else {
							$mensagem->setMensagem("N�o foi poss�vel gravar o arquivo enviado. Tente novamente.", MSG_ERRO);
							$oConteudo->rollbackTransaction();
						}
					} elseif ($oConteudo->getErrNo() == DB_ERR_UNIQUE) {
						$mensagem->setMensagem("J� existe um conte�do com este nome. Favor utilizar outro nome.", MSG_ERRO);
					} else {
						$mensagem->setMensagem("Houve problemas com a inclus�o do conte�do. Tente novamente.", MSG_ERRO);
					}
				}
			} else {
				$mensagem->setMensagem("Houve problemas com o envio do arquivo. Favor tentar novamente.", MSG_ERRO);
			}
			@unlink($_FILES["arquivo"]["tmp_name"]);
		}
		$oConteudo->commitTransaction();
	} else {
		$mensagem->setMensagem("Houve um erro de processamento da sua requisi��o. Contate o administrador do sistema.", MSG_ERRO);
	}

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
<form method="post" action="<? echo $php_self; ?>" class="formPadrao" name="frm1" id="frm1" enctype="multipart/form-data">
<input type="hidden" name="ac" value="<? echo $crypt->encrypt("adicionar");?>" />
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
				<td>T�tulo: </td>
				<td><input type="text" name="titulo" id="titulo" value="<? echo $_POST["titulo"]; ?>" /></td>
			</tr>
			<tr>
				<td>Descri��o: </td>
				<td><textarea name="descricao" id="descricao"><? echo $_POST["descricao"]; ?></textarea></td>
			</tr>
			<tr>
				<td>Tipo de conte�do: </td>
				<td valign="middle">
					Texto <input type="radio" onclick="setContentType('1');" name="tipoconteudo" id="tipo_1" class="rd" value="1"<? echo $_POST["tipoconteudo"] == "1" ? " checked" : ""; ?> />&nbsp;&nbsp;&nbsp;
					Imagem <input type="radio" onclick="setContentType('3');" name="tipoconteudo" id="tipo_3" class="rd" value="3"<? echo $_POST["tipoconteudo"] == "3" ? " checked" : ""; ?> />&nbsp;&nbsp;&nbsp;
					Arquivo <input type="radio" onclick="setContentType('2');" name="tipoconteudo" id="tipo_2" class="rd" value="2"<? echo $_POST["tipoconteudo"] == "2" ? " checked" : ""; ?> />
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
			<tr id="boxtipo_3_2" class="<? echo $_POST["tipoconteudo"] == "3" ? "trow" : "hide"; ?>">
				<td>Legenda: </td>
				<td><input type="text" name="legendaimagem" id="legendaimagem" /></td>
			</tr>
			<tr id="boxtipo_2_1" class="<? echo $_POST["tipoconteudo"] == "2" ? "trow" : "hide"; ?>">
				<td>Arquivo: </td>
				<td><input type="file" name="arquivo" id="arquivo" /></td>
			</tr>
			<tr id="boxtipo_2_2" class="<? echo $_POST["tipoconteudo"] == "2" ? "trow" : "hide"; ?>">
				<td>Legenda: </td>
				<td><input type="text" name="legendaarquivo" id="legendaarquivo" /></td>
			</tr>
			<tr>
				<td>Ativo: </td>
				<td><input type="checkbox" name="ativo" id="ativo" value="s" class="ck" <? echo $_POST["ativo"] == "s" ? "checked" : ""; ?> /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td class="alr"><img src="images/buttons/bt_voltar.gif" onclick="goPage('adm_conteudos.php')" alt="Cancelar e voltar" class="cursorMao" />&nbsp;<img src="images/buttons/bt_salvar.gif" onclick="validaConteudo(document.getElementById('frm1'),'<? echo $crypt->encrypt("adicionar");?>');" alt="Inserir Conte�do" class="cursorMao" /></td>
			</tr>
		</table>
	</div>
	<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
</form>
<?php
include "lib/footer.inc.php";
?>