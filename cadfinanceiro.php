<?php
$pageTitle = "Cadastro";
include "lib/header.inc.php";

$msg_erro = '';
$msg='';
if($_POST['cad_nome'])
{
	$nome= $_POST['cad_nome'];
	$f_mail=$_POST['cad_mail'];
	$msg=2;
	mt_srand(mktime());
	$sTmpPassword = substr(md5(mt_rand(0,mktime())),0,8);
	$dadosUsuario["email_usua"] = $_POST["cad_email"];
	$dadosUsuario["pwd_usua"] = md5($sTmpPassword);
	$email = new email();
	$email->setTo($dadosUsuario["email_usua"]);
	$email->setSubject("Sua senha de acesso ao sistema Contrathos");
	$sMensagem = "
	<p>Prezado <b>".$nome."</b>,</p>
	<p>Você está recebendo abaixo os dados de acesso ao Sistema Contrathos.</p>
	<p>
	Login: <b>".$dadosUsuario["email_usua"]."</b><br />
	Senha: <b>".$sTmpPassword."</b>
	</p>
	<p>Atenciosamente,</p>
	<p>Athos Gestão e Serviço Ltda.</p>";
	$email->setMessage($sMensagem);
	$email->send();
	
	$qCMP = $qVAL = '';
	$qCMP .= " EMAIL_USUA,";        $qVAL .= " '".mysql_real_escape_string($dadosUsuario["email_usua"])."', ";
	$qCMP .= " NOME_USUA, ";       $qVAL .= " '".$_POST["cad_nome"]."', ";
	$qCMP .= " PWD_USUA, ";       $qVAL .= " '".$dadosUsuario["pwd_usua"]."', ";
	$qCMP .= " LEVEL_USUA";   $qVAL .= " '5' ";
	$db->query="INSERT INTO usuario($qCMP) VALUES ($qVAL)";
	//echo $db->query;
	$db->query();
	unset($_POST);
}
?>
<div class="quadroInterno">
	<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
	<div class="quadroInternoMeio">
		<form id="formCadastroUser" name="formCadastroUser" class="formPadrao" method="post" action="">
		<?php echo $msg_erro;?>
		<table cellpadding=0 cellspacing=5 border=0>
			<tr>
				<td align="right" valign="top">Nome:</td>
				<td align="left"  valign="top"><input type="text" name="cad_nome" id="cad_nome" value="<?php echo $_POST['cad_nome'];?>" /></td>
			</tr>
			<tr>
				<td align="right" valign="top">E-Mail:</td>
				<td align="left"  valign="top"><input type="text" name="cad_email" id="cad_email" value="<?php echo $_POST['cad_email'];?>" /></td>
			</tr>
			<tr>
					<td align="right" valign="top">&nbsp;</td>
					<td align="left" valign="top"><img src="images/buttons/bt_cadastrar.gif" class="im" style="width:auto;" onClick="document.formCadastroUser.submit();" /><?php /*<input type="image" value="Cadastrar" src="images/buttons/bt_cadastrar.gif" class="im" style="width:auto;" onClick="return cadastrar();" />*/?></td>
			</tr>
			<font color="#FF0000"><b><?php 
			if ($msg==1)
			{?>
			 	<script language="javascript">
					alert("Já existe um usuário para esta proposta!");
				</script><?php
			}
			if ($msg==2)
			{?>
				<script language="javascript">
					alert("Está sendo enviado para o seu e-mail o login e senha para o acesso ao sistema!");
				</script><?php
			}
			if ($msg==3)
			{?>
				<script language="javascript">
					alert("Proposta ainda não cadastrada, tente novamente mais tarde!");
				</script><?php
			
			}
			if ($msg==4)
			{?>
				<script language="javascript">
					alert("Este não é o CPF cadastrado para esta proposta!");
				</script><?php
			
			}
			?></font></b>
		</table>
		</form>
	</div>
	<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
<?php
include "lib/footer.inc.php";
?>