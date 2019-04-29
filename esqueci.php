<?php
$pageTitle = "Alterar Usuário/Senha";
include "lib/header.inc.php";
$msg=0;
$mensagem = new mensagens();
$user_atual = trim($_POST['user_atual']);
if($user_atual)
{
	$pswd_atual=$_POST['pswd_atual'];
	$db->query="select * from usuario where email_usua='".mysql_real_escape_string($user_atual)."' and id_lstn='".$utils->limpaMatricula($pswd_atual)."'";
	$db->query();
	if($db->qrcount>0)
	{
					$nome_usua=$db->qrdata[0]['NOME_USUA'];
					$pswd_usua=$sTmpPassword = substr(md5(mt_rand(0,mktime())),0,8);
					$qSET='';
					$qSET .= "PWD_USUA= '".mysql_real_escape_string(md5($pswd_usua))."' ";
					$db->query="UPDATE usuario SET $qSET WHERE EMAIL_USUA = '".mysql_real_escape_string($user_atual)."'";
					$db->query();
					$email = new email();
					$email->setTo($user_atual);
					$email->setSubject("Sua senha de acesso ao sistema Contrathos");
					$sMensagem = "
						<p>Prezado <b>".$nome_usua."</b>,</p>
						<p>Atendendo sua solicitação sua senha foi alterada conforme abaixo.</p>
						<p>
							Login: <b>".$user_atual."</b><br />
							Senha: <b>".$pswd_usua."</b>
						</p>
						<p>Para acessar a tela de login clique no link a seguir: <a href=\"http://www.contrathos.athosgestao.com.br/carim\">www.contrathos.athosgestao.com.br/carim/</a></p>

						<p>Atenciosamente,</p>
						<p>Equipe Contrathos</p>
					";
					$email->setMessage($sMensagem);
					$email->send();
					$msg=5;
	}else//fim if($db->qrcount>0)
	{
		$msg=1;
	}
}//fim if($user_atual) ?>
<script language="JavaScript" src="./js/diversos.js"></script>

<div class="quadroInterno">
	<div><img src="images/layout/subquadro_t.gif" /></div>
	<div class="quadroInternoMeio">
		<form id="formAlteraSenha" name="formAlteraSenha" class="formPadrao" method="post" action="esqueci.php">
		<table cellpadding=0 cellspacing=5 border=0 width="100%">
			<tr>
				<td align="right" valign="top">Email:</td>
				<td align="left"  valign="top" width="85%"><input type="text" name="user_atual" id="user_atual" value="<?=$_POST['user_atual'];?>" /></td>
			</tr>
                    <tr>
          <td align="right">Cód. Identificação:</td>
          <td align="left"><input type="text" name="pswd_atual" id="pswd_atual"  onKeyDown='return teclasMatricula(this,event);' onKeyUp='return mascaraMatricula(this,event);'  maxlength='12'  value="<?php echo $_POST["filtro_matricula"];?>" size="50"></td>
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
</div><?php 

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
		alert("Usuário e Senha recuperado com sucesso!");
		window.location='index.php';
	</script><?php
} ?>
<script language="javascript">
function confirmaAlteracao()
{
	if(confirm("Deseja realmente recuperar seu usuário e/ou senha?"))
		return true;
	else
		return false;
}
</script>
<? include "lib/footer.inc.php"; ?>