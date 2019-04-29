<?
// incluindo arquivo de configuração e classes
include "./class/dbclasses.class.php";

$mensagem = new mensagens();
$oProposta = new proposta();
$usuario = new usuario();

$utils= new utils();

$aUsuarios = $usuario->getUsuario($_REQUEST['usuario']);

$matricula=(isset($_POST['cad_matricula']))?$_POST['cad_matricula']:$aUsuarios[0]['id_lstn'];
$nome=$aUsuarios[0]['nome_usua'];
$email=(isset($_POST['cad_email']))?$_POST['cad_email']:$aUsuarios[0]['email_usua'];

if(isset($_POST['submetido'])){

$cod_ppst=$usuario->getCodPropostabyProponente($_REQUEST['usuario']) ;
$oHistorico = new historico();
$acao=0;

	if($utils->limpaMatricula($_POST['cad_matricula'])!=$aUsuarios[0]['id_lstn']){
		if($usuario->altCadMatricula($utils->limpaMatricula($matricula),$aUsuarios)){
		$oHistorico->inserir($cod_ppst,date("Y-m-d H:i:s"),'Código da matrícula alterado de ' . $utils->formataMatricula($aUsuarios[0]['id_lstn']) . ' para ' . $matricula,'1',$cLOGIN->iID);
		$acao=1;
		}
	}

	if($_POST['cad_email']!=$aUsuarios[0]['email_usua']){
	
		if($usuario->altCadEmail($email,$_REQUEST['usuario'])){
		$oHistorico->inserir($cod_ppst,date("Y-m-d H:i:s"),'E-mail alterado de ' . $aUsuarios[0]['email_usua'] . ' para ' . $email,'1',$cLOGIN->iID);
		
		$sTmpPassword = substr(md5(mt_rand(0,mktime())),0,8);
		$sSenhaUsuario = md5($sTmpPassword);
		
		$usuario->altCadSenha($sSenhaUsuario,$_REQUEST['usuario']);
		
		include "enviar.php";

		$mail->AddAddress($_POST['cad_email'],$aUsuarios[0]['nome_usua']); //E-MAIL DO DESINATÁRIO, NOME DO DESINATÁRIO 

		$mail->Subject = "Sua senha de acesso ao sistema CARIM"; //ASSUNTO DA MENSAGEM

		$mail->Body = "
			<p>Prezado(a) <b>".$_POST['cad_nome']."</b>,</p>
			<p>Seja bem vindo ao site de contratação do Carim 2007. Você está recebendo abaixo os dados de acesso ao sistema.</p>
			<p>
				Login: <b>".$_POST["cad_email"]."</b><br />
				Senha: <b>".$sTmpPassword."</b></p>
			<p>A senha foi gerada automaticamente e poderá ser alterada na tela de login a seguir: <a href=\"https://www.contrathos.athosgestao.com.br/carim/alterasenha.php?login=".$_POST["cad_email"]."\">www.contrathos.athosgestao.com.br/carim/alterasenha.php</a></p>
			<p>Atenciosamente,</p>
			<p>Equipe Athos Gestão e Serviços LTDA</p>
		";
			if(!($mail->Send())){
			$mensagem->setMensagem("Erro ao enviar e-mail.", MSG_ERRO);
			$acao=0;
			}
			else{		
			$acao=1;
			}
		}
		else{
		$mensagem->setMensagem("E-mail já existente", MSG_ERRO);	
		}
	}
	
	if($acao){
	$mensagem->setMensagem("Ação realizada com sucesso",MSG_SUCESSO);	
	}
	

/*
	$retorno = $oProposta->incluirProposta($_POST);
 	if($retorno[0] === true){
		$senhaGerada=$retorno[2];
		
		include "enviar.php";

		$mail->AddAddress($_POST['cad_email'],$_POST['cad_nome']); //E-MAIL DO DESINATÁRIO, NOME DO DESINATÁRIO 

		$mail->Subject = "Sua senha de acesso ao sistema Contrathos"; //ASSUNTO DA MENSAGEM

		$mail->Body = "
			<p>Prezado(a) <b>".$_POST['cad_nome']."</b>,</p>
			<p>Seja bem vindo ao site de contratação da Contrathos. Você está recebendo abaixo os dados de acesso ao Sistema Contrathos.</p>
			<p>
				Login: <b>".$_POST["cad_email"]."</b><br />
				Senha: <b>".$senhaGerada."</b>
			</p>
			<p>A senha foi gerada automaticamente e poderá ser alterada na tela de login a seguir: <a href=\"http://www.contrathos.athosgestao.com.br/carm/alterasenha.php?login=".$_POST["cad_email"]."\">www.contrathos.athosgestao.com.br/sgprevi/alterasenha.php</a></p>
			<p>Atenciosamente,</p>
			<p>Equipe Contrathos</p>
		";
		if($mail->Send()){
	 		header ("Location: proposta.php?cod_proposta=" . $retorno[1]);exit;
		}
		else{
 		$mensagem->setMensagem("Proposta cadastrada com sucesso, porém houve erro ao enviar e-mail ao proponente. <br /> Senha gerada: $senhaGerada", MSG_ERRO);
		}
		
 	}else{
 		$mensagem->setMensagem($retorno[0], MSG_ERRO);
 	}
*/	
}
$pageTitle = "Cadastro";
include "lib/header.inc.php";

?>
<script language="JavaScript">
	var FLAG_PREVI = <?=(FLG_PREVI)?'true':'false';?>;
</script>
<script language="JavaScript" src="./js/diversos.js"></script>
<script language="JavaScript" src="./js/cadastro.js"></script>

<div class="quadroInterno">
	<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
	<div class="quadroInternoMeio">
		<br><?=$mensagem->getMessageBox();?>
		<form id="formCadastroUser" name="formCadastroUser" class="formPadrao" method="post">
		<input type='hidden' name='submetido' value="sim">
        <input type='hidden' name='usuario' value="<?=$_REQUEST['usuario'];?>">
		<?=$msg_erro;?>
		<table cellpadding=0 cellspacing=5 border=0>
			<tr>
				<td align="right" valign="top">Cód. Identificação:</td>
				<td align="left"  valign="top"><input type="text" name="cad_matricula" id="cad_matricula"  onKeyDown='return teclasMatricula(this,event);' onKeyUp='return mascaraMatricula(this,event);' value="<?=$utils->formataMatricula($matricula);?>" /> </td>
			</tr>
			<tr>
				<td align="right" valign="top">Nome:</td>
				<td align="left"  valign="top"><?=$nome;?></td>
			</tr>
			<tr>
				<td align="right" valign="top">E-Mail:</td>
				<td align="left"  valign="top"><input type="text" name="cad_email" id="cad_email" value="<?=$email;?>" /></td>
			</tr>
            <tr>
					<td align="right" valign="top">&nbsp;</td>
					<td align="left" valign="top" style="padding-top:10px;"><img src="images/buttons/bt_alterar.gif" class="im" style="width:auto;" onClick="return cadastrar();" /></td>
			</tr>
		</table>
		</form>
	</div>
	<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
	<table width="100%">
		<tr>
			<td  align="right">
					<a href="alterar_propostas.php"><img src="images/buttons/bt_voltar.gif"></a>
			</td>
		</tr>
	</table>
<?
include "lib/footer.inc.php";
?>