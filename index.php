<?
// incluindo arquivo de configuração e classes

$tmpMT_ini = microtime();

include "./class/dbclasses.class.php";
//------------------------- autenticação do usuário -----------------------------------------//
// vendo se veio user e senha por post... se veio... o cara tá tentando logar
if (!empty($_POST['usuario1'])) { 
	$cLOGIN->autentica_user($_POST['usuario1'],trim($_POST['password']));
	if($cLOGIN->bOK) {
	  switch($cLOGIN->iLEVEL_USUA){
	  	//   1 - Proponente
	    case 1: header("Location: proposta.php"); break;
	    //   2 - Atendente
	    case 2: header("Location: lista_propostas.php"); break;
	    //   3 - Adm. Previ
	    //   4 - Adm. Athos
	    
		//   5 - Financeiro
		case 5: header("Location: financeiro.php"); break;
		
		//   6 - Despachante
		case 6: header("Location: lista_propostas.php"); break;
	    
	    //   7 - Anal. Jurídico
	    case 7: header("Location: lista_propostas.php"); break;
	    
	    //   8 - Assist. Administrativo
	    case 8: header("Location: lista_assistente.php"); break;
	    
	    //   9 - Usuário master
	    case 9: header("Location: lista_propostas.php"); break;
		
		case 10: header("Location: dados_procurador.php"); break;
	    default: header("Location: index.php"); break;
	  }
	  exit();
	}
}
if($cLOGIN->bOK) {
	$cLOGIN->insert_log(1,1,'Visualização da Home');
}
//-------------------------------------------------------------------------------------------//
// incluindo o cabeçalho
$pageTitle = "Home";
include "lib/header.inc.php";
?>
<form name="login" method="post" action="<?=$_SERVER["PHP_SELF"];?>">


<table cellpadding=0 cellspacing=0 border=0 style="width: 100%;">
	<tr><td colspan="2">
	<?
		$mensagem = new mensagens();
		if(!empty($cLOGIN->cERRO)){
			$mensagem->setMensagem($cLOGIN->cERRO, MSG_ERRO);
			echo $mensagem->getMessageBox();
		}
	?>
	</td></tr>
	<tr>
		<td style="padding:15px;" valign="top">
			<div style="width:420px; margin-top:10px; margin-bottom:20px;">
				<div id="blAlertaIndex">
					<h1>AVISO IMPORTANTE!</h1>
					<p>
						ESTE SITE NÃO ENVIA E-MAIL (SPAM). ASSIM, CASO RECEBA QUALQUER E-MAIL NÃO SOLICITADO EM NOME DESTE SITE, DESCONSIDERE E APAGUE.
					</p>
					<p>
						VOCÊ SÓ RECEBERÁ E-MAIL DESTE SITE EM CASO DE ESQUECIMENTO DA SENHA PARA ACESSO RESTRITO, QUE LHE SERÁ ENVIADO MEDIANTE SUA SOLICITAÇÃO ATRAVÉS DE LINK NA PÁGINA DE LOGIN.
					</p>
				</div>
			</div>
		</td>
		<td style="width:160px; padding:15px;" valign="top">
<?
if($cLOGIN->bOK == 0) {
?>
			<div id="boxLogin">
				<label for="usuario1">Usuário: </label>
				<input type="text" value="<?=$_POST["usuario1"];?>" name="usuario1" id="usuario1" class="tx" /><br />
				<label for="password">Senha: </label>
				<input type="password" value="" name="password" id="password" class="tx" />
				<div class="alr">
					<input type="image" src="images/buttons/bt_entrar.gif" style="width: 98px; height: 21px;" class="im" />
					 <br />
					<a href="esqueci.php">Esqueci a Senha</a><br /><a href="alterasenha.php">Alterar Senha</a>
				</div>
			</div>

<?
} elseif ($cLOGIN->iLEVEL_USUA == "1") {
?>
			<a href="proposta.php"><img src="images/layout/acompanhamento.jpg" alt="Acompanhe a sua proposta de financiamento!" /></a>
<?
}
?>
		</td>
<?
/*
		<fieldset>
		  <legend>Login</legend>
		  <?
		  if(!empty($cLOGIN->cERRO)){
			echo("<font color=\"#CC0000\">".$cLOGIN->cERRO."</font><br /><br />");
		  }
		  ?>
		  Usuário:<br />
		  <input type="text" value="<?=$_POST["usuario1"];?>" name="usuario1" id="usuario1" />
		  <br />
		  Senha:<br />
		  <input type="password" value="" name="password" id="password" />
		  <br />
		  <input type="submit" value="ENTRAR" />
		  <div><a href="cadastro.php">Cadastre-se</a></div>
		  <div><a href="esqueci.php">Esqueci a Senha</a></div>
		</fieldset>
*/?>
	</tr>
</table>
</form>

<div id="blBannersHome">
	<a href="javascript:openChat();"><img src="images/layout/bnchat.gif" alt="Entre em contato via CHAT" /></a><a 
	href="javascript:void(0);"><img src="images/layout/bncentral.gif" alt="Entre em contato via CHAT" /></a><a 
	href="contato.php"><img src="images/layout/bnform.gif" alt="Entre em contato via CHAT" /></a>
</div>

<?
//echo md5("senha");

$tmpMT_fim = microtime();

// incluindo o rodapé
include "lib/footer.inc.php";

?>