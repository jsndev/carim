<?
// incluindo arquivo de configuração e classes
include "./class/dbclasses.class.php";

if(isset($_SESSION['CODCHTU'])){
	header ("Location: other_chat.php");
	exit();
}

if($_POST){
	$db->query="INSERT INTO chatusers ( NOME_CHTU, EMAIL_CHTU ) VALUES ( '".mysql_real_escape_string($_POST['chat_nome'])."', '".mysql_real_escape_string($_POST['chat_email'])."' )"; 
	$db->query();
	$_SESSION['CODCHTU'] = $db->getInsertId();
	header ("Location: other_chat.php");
	exit();
}

$pageTitle = "Chat Contrathos";
include "lib/header_chat.inc.php";


$hora   = (int)substr($cLOGIN->dHORA,0,2);
$dia = substr($cLOGIN->dDATA,0,2);
$mes = substr($cLOGIN->dDATA,3,2);
$ano = substr($cLOGIN->dDATA,6,4);
$week = date('w', mktime(0, 0, 0, $mes, $dia, $ano));

$atendimento = true;
if($week==0 || $week==6) $atendimento = false; //    !=  SEG  a  SEX
if($hora < 9 || $hora >=17) $atendimento = false; // !=  9:00 as 17:00

?>
	<style>
  .ifh{ width:350px; height:40px; margin:3px; }
  </style>
	
	<script language="JavaScript" src="./js/diversos.js"></script>
	<script language="JavaScript" src="./js/chatOther.js"></script>
	
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
			<? if($atendimento){ ?>
			<form id="formChatLogin" name="formChatLogin" class="formPadrao" method="post" action="<?=$php_self;?>" style="height:290px;">
			<table cellpadding=0 cellspacing=5 border=0>

				<tr>
					<td align="right" valign="top">Nome:</td>
					<td align="left"  valign="top"><input type="text" name="chat_nome" id="chat_nome" value="<?=$_POST['chat_nome'];?>" style="width:250px;" /></td>
				</tr>
			
				<tr>
					<td align="right" valign="top">E-Mail:</td>
					<td align="left"  valign="top"><input type="text" name="chat_email" id="chat_email" value="<?=$_POST['chat_email'];?>" style="width:250px;" /></td>
				</tr>
				
				<tr>
						<td align="right" valign="top">&nbsp;</td>
						<td align="left" valign="top"><img alt="Entrar" src="images/buttons/bt_entrar.gif" class="im" style="width:auto;" onClick="return entrarChat();" /></td>
				</tr>

			</table>
			</form>
			<? }else{ ?>
				<div style="width:300px; height:60px; margin:120px 5px; font-size:11pt; font-weight:bold; text-align:center;">
					Horario de funcionamento<br>
					segunda a sexta-feira<br>
					das 9:00h as 17:00h
				</div>
			<? } ?>
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
	</div>
<?
include "lib/footer_chat.inc.php";
?>