<?
// incluindo arquivo de configuração e classes
include "./class/dbclasses.class.php";

$mensagem = new mensagens();
$oProposta = new proposta();

if(isset($_POST['submetido'])){
	$retorno = $oProposta->incluirProposta($_POST);
 	if($retorno[0] === true){
		$senhaGerada=$retorno[2];
		
	/*	include "enviar.php";

		$mail->AddAddress($_POST['cad_email'],$_POST['cad_nome']); //E-MAIL DO DESINATÁRIO, NOME DO DESINATÁRIO 

		$mail->Subject = "Sua senha de acesso ao sistema CARIM"; //ASSUNTO DA MENSAGEM

		$mail->Body = "
			<p>Prezado(a) <b>".$_POST['cad_nome']."</b>,</p>
			<p>Seja bem vindo ao site de contratação do Carim 2007. Você está recebendo abaixo os dados de acesso ao sistema.</p>
			<p>
				Login: <b>".$_POST["cad_email"]."</b><br />
				Senha: <b>".$senhaGerada."</b>
			</p>
			<p>A senha foi gerada automaticamente e poderá ser alterada na tela de login a seguir: <a href=\"https://www.contrathos.athosgestao.com.br/carim/alterasenha.php?login=".$_POST["cad_email"]."\">www.contrathos.athosgestao.com.br/carim/alterasenha.php</a></p>
			<p>Atenciosamente,</p>
			<p>Equipe Athos Gestão e Serviços LTDA</p>
		";*/

		//if($mail->Send()){
 		header ("Location: proposta.php?cod_proposta=" . $retorno[1]);exit;
		/*}
		else{
 		$mensagem->setMensagem("Proposta cadastrada com sucesso, porém houve erro ao enviar e-mail ao proponente. <br /> Senha gerada: $senhaGerada", MSG_ERRO);
		}*/
		
 	}else{
 		$mensagem->setMensagem($retorno[0], MSG_ERRO);
 	}
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
		<form id="formCadastroUser" name="formCadastroUser" class="formPadrao" method="post" action="<?=$php_self;?>">
		<input type='hidden' name='submetido' value="sim">
		<?=$msg_erro;?>
		<table cellpadding=0 cellspacing=5 border=0>
			<? if(FLG_PREVI){ ?>
			<tr>
				<td align="right" valign="top">Cód. Identificação:</td>
				<td align="left"  valign="top"><input type="hidden" name="cad_matricula" id="cad_matricula"  onKeyDown='return teclasMatricula(this,event);' onKeyUp='return mascaraMatricula(this,event);' value="<?=$_POST['cad_matricula'];?>" /><B><?=$_POST['cad_matricula'];?></B> </td>
			</tr>
			<? } ?>
		
			<tr>
				<td align="right" valign="top">Nome:</td>
				<td align="left"  valign="top"><input type="text" name="cad_nome" id="cad_nome" value="<?=$_POST['cad_nome'];?>" /></td>
			</tr>
		
			<tr>
				<td align="right" valign="top">E-Mail:</td>
				<td align="left"  valign="top"><input type="text" name="cad_email" id="cad_email" value="<?=$_POST['cad_email'];?>" /></td>
			</tr>
            <tr>
					<td align="right" valign="top">Transferência de finaciamento?</td>
					<td align="left"  valign="top">
						<div>
						<input type="radio" class="rd" name="tf_ppst" id="tf_ppst" value="S"  <?=(($_POST['tf_ppst']=='S')?'checked':'');?> /> Sim
						&nbsp;&nbsp;&nbsp;
						<input type="radio" class="rd" name="tf_ppst" id="tf_ppst" value="N"  <?=(($_POST['tf_ppst']!='N')?'checked':'');?> /> Não
						</div>
					</td>
			</tr>            
            
			<? if(FLG_PREVI){ ?>
			<tr>
					<td align="right" valign="top">Proposta em Condomínio?</td>
					<td align="left"  valign="top">
						<div>
						<input type="radio" class="rd" name="cad_condom" id="cad_condom_s" value="S" onclick="trocouTipoProposta(this);" <?=(($_POST['cad_condom']=='S')?'checked':'');?> /> Sim
						&nbsp;&nbsp;&nbsp;
						<input type="radio" class="rd" name="cad_condom" id="cad_condom_n" value="N" onclick="trocouTipoProposta(this);" <?=(($_POST['cad_condom']!='S')?'checked':'');?> /> Não
						</div>
					</td>
			</tr>

			<tr id="tr_qtde_ppnt" <?=(($_POST['cad_condom']!='S')?'style="display:none"':'');?>>
					<td align="left" valign="top" colspan="2">
						<select name="cad_qtde_ppnt" id="cad_qtde_ppnt" style="width:90px; float:right;" onchange="atualizaListaMatriculas();">
							<?
								for($i=1; $i<=10; $i++){
									$selected = ($_POST['cad_qtde_ppnt']==$i)?'selected':'';
									print '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
								}
							?>
						</select>
						Quantos proponentes além de você participarão desta proposta?
					</td>
			</tr>

			<tr id="tr_matriculas_ppnt" <?=(($_POST['cad_condom']!='S')?'style="display:none"':'');?>>
					<td id="td_matriculas_ppnt" align="right" valign="top" colspan="2"><?
							if($_POST['cad_qtde_ppnt'] > 0){
								for($i=1; $i<=$_POST['cad_qtde_ppnt']; $i++){
									?><div class="divMatricula">
											<table cellpadding=0 cellspacing=2 border=0>
												<tr>
													<td align='right' valign='top'>Cód. Identificação:</td>
													<td align='left'><input type='text' style='width:250px;' name='matricula_ppnt_<?=$i;?>' id='matricula_ppnt_<?=$i;?>' value='<?=$_POST['matricula_ppnt_'.$i];?>' onKeyDown='return teclasMatricula(this,event);' onKeyUp='return mascaraMatricula(this,event);' maxlength='12' /></td>
												</tr>
												<tr>
													<td align='right' valign='top'>Nome:</td>
													<td align='left'><input type="text" style='width:250px;' name="nome_ppnt_<?=$i;?>" id="nome_ppnt_<?=$i;?>" value="<?=$_POST['nome_ppnt_'.$i];?>" /></td>
												</tr>
												<tr>
													<td align='right' valign='top'>E-Mail:</td>
													<td align='left'><input type="text" style='width:250px;' name="email_ppnt_<?=$i;?>" id="email_ppnt_<?=$i;?>" value="<?=$_POST['email_ppnt_'.$i];?>" /></td>
												</tr>
											</table>
										</div><?
								}
							}
					?></td>
			</tr>
			<? } ?>
			
			<tr>
					<td align="right" valign="top">&nbsp;</td>
					<td align="left" valign="top" style="padding-top:10px;"><img src="images/buttons/bt_cadastrar.gif" class="im" style="width:auto;" onClick="return cadastrar();" /><?/*<input type="image" value="Cadastrar" src="images/buttons/bt_cadastrar.gif" class="im" style="width:auto;" onClick="return cadastrar();" />*/?></td>
			</tr>
		</table>
		</form>
	</div>
	<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
	<table width="100%">
		<tr>
			<td  align="right">
					<a href="lista_limites.php"><img src="images/buttons/bt_voltar.gif"></a>
			</td>
		</tr>
	</table>
<?
include "lib/footer.inc.php";
?>