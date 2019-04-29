<?php
$iREQ_AUT=1;
$aUSERS_PERM[]=4;

$pageTitle = "Adicionar Administrador";
include "lib/header.inc.php";

$oUsuario = new usuario();

$mensagem = new mensagens();

if ($crypt->decrypt($_POST["ac"]) == "adicionar") {
	mt_srand(mktime());
	$sTmpPassword = substr(md5(mt_rand(0,mktime())),0,8);
	
	$dadosUsuario["nome_usua"] = $_POST["nome"];
	$dadosUsuario["email_usua"] = $_POST["email"];
	$dadosUsuario["pwd_usua"] = md5($sTmpPassword);
	$dadosUsuario["level_usua"] = TPUSER_ADMATHOS;
	$dadosUsuario["id_lstn"] = '';
	$dadosUsuario["flgstatus_usua"] = (string)($_POST["ativo"] == "s" ? "1" : "0");
	
	$bInsercao = $oUsuario->addUsuario($dadosUsuario);
	if ($oUsuario->getErrNo() == 0) {
		$email = new email();
		$email->setTo($dadosUsuario["email_usua"]);
		$email->setSubject("Sua senha de acesso ao sistema Contrathos");
		$sMensagem = "
			<p>Prezado(a) <b>".$dadosUsuario["nome_usua"]."</b>,</p>
			<p>Seja bem vindo ao site de contratação da Athos Gestão e Serviço Ltda para o Carim 2007. Você está recebendo abaixo os dados de acesso ao Sistema Contrathos.</p>
			<p>
				Login: <b>".$dadosUsuario["email_usua"]."</b><br />
				Senha: <b>".$sTmpPassword."</b>
			</p>
			<p>A senha foi gerada automaticamente e poderá ser alterada na tela de login a seguir: <a href=\"http://www.contrathos.athosgestao.com.br/carim\">www.contrathos.athosgestao.com.br/carim</a></p>
			<p>Atenciosamente,</p>
			<p>Equipe Contrathos</p>
		";
		
		$email->setMessage($sMensagem);
		$email->send();

		$mensagem->setMensagem("Administrador cadastrado com sucesso. Um e-mail foi enviado para o administrador contendo sua senha de acesso.", MSG_SUCESSO);
		unset($_POST);
	} elseif ($oUsuario->getErrNo() == DB_ERR_UNIQUE) {
		$mensagem->setMensagem("Já existe ou existiu um administrador cadastrado com esse endereço de e-mail.", MSG_ERRO);
	} else {
		$mensagem->setMensagem("Houve um erro ao cadastrar o administrador. Favor tentar novamente.", MSG_ERRO);
	}
}
?>
<script language="javascript" type="text/javascript" src="js/cadastroAdministrador.js"></script>
<form method="post" action="<? echo $php_self; ?>" class="formPadrao" name="frm1" id="frm1">
<input type="hidden" name="ac" value="<? echo $crypt->encrypt("adicionar");?>" />
<div class="quadroInterno">
	<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
	<div class="quadroInternoMeio">
		<? echo $mensagem->getMessageBox(); ?>
		<table cellpadding="0" cellspacing="2" class="tbForm">
			<tr>
				<td>Nome: </td>
				<td><input type="text" name="nome" id="nome" value="<? echo $_POST["nome"]; ?>" /></td>
			</tr>
			<tr>
				<td>Email: </td>
				<td>
					<input type="text" name="email" id="email" value="<? echo $_POST["email"]; ?>" />
					<p class="warning" style="width: 300px;"><b>Importante:</b> a senha de acesso do usuário será automaticamente gerada e enviada ao endereço de e-mail informado. Certifique-se de que o endereço de e-mail esteja correto.</p>
				</td>
			</tr>
			<tr>
				<td>Ativo: </td>
				<td><input type="checkbox" name="ativo" id="ativo" value="s" class="ck" <? echo $_POST["ativo"] == "s" ? "checked" : ""; ?> /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td class="alr"><img src="images/buttons/bt_voltar.gif" onclick="goPage('adm_administradores.php')" alt="Cancelar e voltar" class="cursorMao" />&nbsp;<img src="images/buttons/bt_salvar.gif" onclick="validaAdministrador(document.getElementById('frm1'));" alt="Inserir Administrador" class="cursorMao" /></td>
			</tr>
		</table>
	</div>
	<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
</form>
<?php
include "lib/footer.inc.php";
?>