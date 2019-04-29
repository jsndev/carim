<?php
$pageTitle = "Alterar Usuário/Senha";
include "lib/header.inc.php";
$msg=0;
$mensagem = new mensagens();
$user_atual = trim($_POST['user_atual']);


if($_POST['logar_direto']=='sim'){
$cLOGIN->autentica_user($_POST['usuario1'],$crypt->decrypt($_POST['password']));
	if($cLOGIN->bOK) {
	header("Location: proposta.php");
	}
}

if($user_atual)
{
	$pswd_atual= md5($_POST['pswd_atual']);
	$pswd_novo1= md5($_POST['pswd_novo1']);
	$pswd_novo2= md5($_POST['pswd_novo2']);
	$pswd_email= $_POST['pswd_novo1'];
	$db->query="select * from usuario where email_usua='".mysql_real_escape_string($user_atual)."'";
	$db->query();
	if($db->qrcount>0)
	{
		$pswd_bd=$db->qrdata[0]['PWD_USUA'];
		$nome_usua=$db->qrdata[0]['NOME_USUA'];
			if ($pswd_bd==$pswd_atual)
			{
				if ($pswd_novo1==$pswd_novo2)
				{
					$qSET='';
					$qSET .= "PWD_USUA= '".mysql_real_escape_string($pswd_novo1)."' ";
					$db->query="UPDATE usuario SET $qSET WHERE EMAIL_USUA = '".mysql_real_escape_string($user_atual)."'";
					$db->query();
					
					include "enviar.php";
					
					$mail->AddAddress($user_atual,$nome_usua); //E-MAIL DO DESINATÁRIO, NOME DO DESINATÁRIO 

					$mail->Subject = "Sua senha de acesso ao sistema CARIM"; //ASSUNTO DA MENSAGEM
			
					$mail->Body = "
						<p>Prezado <b>".$nome_usua."</b>,</p>
						<p>Atendendo sua solicitação sua senha foi alterada conforme abaixo.</p>
						<p>
							Login: <b>".$user_atual."</b><br />
							Senha: <b>".$pswd_email."</b>
						</p>
						<p>Para acessar a tela de login clique no link a seguir: <a href=\"http://www.contrathos.athosgestao.com.br/carim\">www.contrathos.athosgestao.com.br/carim/</a></p>
						<p>Atenciosamente,</p>
						<p>Equipe Contrathos</p>
					";
					
					$mail->Send();

					$msg=5;
				}else//fim if ($pswd_novo1==$pswd_novo2) 
				{
					$msg=4;
				}
			}else// fim if ($pswd_bd==$pswd_atual)
			{
				$msg=3;
			}
	}else//fim if($db->qrcount>0)
	{
		$msg=1;
	}
}//fim if($user_atual) ?>

<div class="quadroInterno">
	<div><img src="images/layout/subquadro_t.gif" /></div>
	<div class="quadroInternoMeio">
		<form id="formAlteraSenha" name="formAlteraSenha" class="formPadrao" method="post" action="alterasenha.php">
		<table cellpadding=0 cellspacing=5 border=0  width="100%">
			<tr>
				<td align="right" valign="top">Usuário:</td>
				<td align="left"  valign="top">
				<?php
				if($_REQUEST['login']){
				echo "<b>" . $_REQUEST['login'] ."</b>";
				echo "<input type=\"hidden\" name=\"user_atual\"  value=\"". $_REQUEST['login'] ."\" />";
				echo "<input type=\"hidden\" name=\"login\"  value=\"". $_REQUEST['login'] ."\" />";
				}
				else{
				?>
				<input type="text" name="user_atual" id="user_atual" value="<?=$_POST['user_atual'];?>" />
				<?php
				}
				?>
				
				</td>
			</tr>
			<tr>
				<td align="right" valign="top">Senha:</td>
				<td align="left"  valign="top" width="80%"><input type="password" name="pswd_atual" id="pswd_atual" value="" /></td>
			</tr>
			<tr>
				<td align="right" valign="top">Nova Senha:</td>
				<td align="left"  valign="top"><input type="password" name="pswd_novo1" id="pswd_novo1" value="" /></td>
			</tr>
			<tr>
				<td align="right" valign="top">Confirma Nova Senha:</td>
				<td align="left"  valign="top"><input type="password" name="pswd_novo2" id="pswd_novo2" value="" /></td>
			</tr>
			<tr>
					<td align="right" valign="top">&nbsp;</td>
					<td align="left" valign="top">
					<input type="image" src="images/buttons/bt_alterar.gif" name="alterar" value="Alterar" class="im" style="width:auto;" onClick="return confirmaAlteracao();" />
				</td>
			</tr>
                        			<tr>
					<td align="right" valign="top" colspan='2'>
					<a href="index.php"><img src="images/buttons/bt_voltar.gif" border="0"  align="right"></a>
				</td>
			</tr>
		</table>
		</form>
	</div>
	<div><img src="images/layout/subquadro_b.gif"></div>
</div> 
<?php
if($msg==5){
?>
<form name="login" method="post" action="alterasenha.php">
<input type="hidden" value='sim'  name="logar_direto" />
<input type="hidden" value="<?=$user_atual;?>" name="usuario1" id="usuario1" /><br />
<input type="hidden" value="<?=$crypt->encrypt($pswd_email);?>" name="password" id="password" />
</form>
<?php
}
if ($msg==1)
{?>
	<script language="javascript">
		alert("Este usuário não está cadatrado!");
	</script><?php
}
if ($msg==3)
{?>
	<script language="javascript">
		alert("Senha Inválida!");
	</script><?php

}
if ($msg==4)
{?>
	<script language="javascript">
		alert("A senha digitada em 'Nova Senha' não confere com a digitada em 'Confirme Nova Senha'!");
	</script><?php
}
if ($msg==5)
{?>
<script language="javascript">

	if(confirm("Usuário e Senha alterado com sucesso!\n Uma mensagem foi enviada para o e-mail <?=$user_atual;?> com a sua nova senha. \n Deseja logar no sistema agora!")){
	document.login.submit();
	}		


</script>
<?php
} ?>
<script language="javascript">
function confirmaAlteracao()
{
	if(confirm("Deseja realmente alterar seu usuário e/ou senha?"))
		return true;
	else
		return false;
}
</script>
<? include "lib/footer.inc.php"; ?>
