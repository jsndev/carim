<?
$iREQ_AUT=1;
$aUSERS_PERM[]=1;
$pageTitle = "Chat Proponente";
include "lib/header_chat.inc.php";

$_SESSION['CHAT']      = '';
$_SESSION['ATEN']      = '';
$_SESSION['CHAT_ERRO'] = 0;


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
	<script language="JavaScript" src="./js/chatProp.js"></script>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
			<? if($atendimento){ ?>
			<div id="divMensagens" class="divMensagens"></div>
			<div id="divTxtPost" class="divTxtPost" style="visibility:hidden;">
				<form id="formPost" action="prop_chat_post.php" method="POST" target="iframePost">
					<textarea name="txtMsg" id="txtMsg" style="width:308px; height:60px; margin-bottom:5px;" ></textarea><br />
					<img src="images/buttons/bt_enviar.gif" alt="Enviar" name="btnMsg" id="btnMsg" onClick="enviarMsg();" />
				</form>
			</div>
			<div style="width:300px; text-align:right;">rolagem automática <input type="checkbox" id="chk_auto" class="ck" checked></div>
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
	<? if($atendimento){ ?>
	<div style="display:none;">
		<iframe name="iframeMsgs" id="iframeMsgs" src="" class="ifh"></iframe><br>
		<iframe name="iframePost" id="iframePost" src="" class="ifh"></iframe><br>
		<iframe name="iframeUpdt" id="iframeUpdt" src="" class="ifh"></iframe><br>
		<br><br><br>
	</div>	
	<script language="JavaScript">
		 startChat();
	</script>
	<? } ?>
<?
include "lib/footer_chat.inc.php";
?>