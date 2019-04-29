<?php
$iREQ_AUT=1;
$aUSERS_PERM[]=4;

$pageTitle = 'Adicionar Conteúdo';
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
				$mensagem->setMensagem("Conteúdo inserido com sucesso", MSG_SUCESSO);
				$oConteudo->commitTransaction();
				unset($_POST);
			} elseif ($oConteudo->getErrNo() == DB_ERR_UNIQUE) {
				$mensagem->setMensagem("Já existe um conteúdo com este nome. Favor utilizar outro nome.", MSG_ERRO);
			} else {
				$mensagem->setMensagem("Houve problemas com a inclusão do conteúdo. Tente novamente.", MSG_ERRO);
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
							$mensagem->setMensagem("Conteúdo inserido com sucesso", MSG_SUCESSO);
							$oConteudo->commitTransaction();
							unset($_POST);
						} else {
							$mensagem->setMensagem("Não foi possível gravar a imagem enviada. Tente novamente.", MSG_ERRO);
							$oConteudo->rollbackTransaction();
						}
					} elseif ($oConteudo->getErrNo() == DB_ERR_UNIQUE) {
						$mensagem->setMensagem("Já existe um conteúdo com este nome. Favor utilizar outro nome.", MSG_ERRO);
					} else {
						$mensagem->setMensagem("Houve problemas com a inclusão do conteúdo. Tente novamente.", MSG_ERRO);
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
							$mensagem->setMensagem("Conteúdo inserido com sucesso", MSG_SUCESSO);
							$oConteudo->commitTransaction();
							unset($_POST);
						} else {
							$mensagem->setMensagem("Não foi possível gravar o arquivo enviado. Tente novamente.", MSG_ERRO);
							$oConteudo->rollbackTransaction();
						}
					} elseif ($oConteudo->getErrNo() == DB_ERR_UNIQUE) {
						$mensagem->setMensagem("Já existe um conteúdo com este nome. Favor utilizar outro nome.", MSG_ERRO);
					} else {
						$mensagem->setMensagem("Houve problemas com a inclusão do conteúdo. Tente novamente.", MSG_ERRO);
					}
				}
			} else {
				$mensagem->setMensagem("Houve problemas com o envio do arquivo. Favor tentar novamente.", MSG_ERRO);
			}
			@unlink($_FILES["arquivo"]["tmp_name"]);
		}
		$oConteudo->commitTransaction();
	} else {
		$mensagem->setMensagem("Houve um erro de processamento da sua requisição. Contate o administrador do sistema.", MSG_ERRO);
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
		<table cellpadding="0" cellspacing="2" class="tbForm