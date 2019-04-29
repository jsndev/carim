<?
$iREQ_AUT=1;
$aUSERS_PERM[]=2;
$pageTitle = "Chat Atendente";
include "lib/header_chat.inc.php";

$_SESSION['CHAT']      = '';
$_SESSION['USUA']      = '';
$_SESSION['CHTU']      = '';
$_SESSION['CHAT_ERRO'] = 0;
?>
	<style>
  .ifh{ width:350px; height:40px; margin:3px; }
  </style>
	<script language="JavaScript" src="./js/chatAten.js"></script>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
			<div id="divMensagens" class="divMensagens"></div>
			<div id="divTxtPost" class="divTxtPost" style="visibility:hidden;">
				<form id="formPost" action="atend_chat_post.php" method="POST" target="iframePost">
					<textarea name="txtMsg" id="txtMsg" style="width:308px; height:60px; margin-bottom:5px;" ></textarea><br />
					<img src="images/buttons/bt_enviar.gif" alt="Enviar" name="btnMsg" id="btnMsg" onClick="enviarMsg();" />
				</form>
			</div>
			<div style="width:300px; text-align:right;">rolagem automática <input type="checkbox" id="chk_auto" class="ck" checked></div>
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
	</div>
	<div style="display:none;">
		<iframe name="iframeMsgs" id="iframeMsgs" src="" class="ifh"></iframe><br>
		<iframe name="iframePost" id="iframePost" src="" class="ifh"></iframe><br>
		<iframe name="iframeUpdt" id="iframeUpdt" src="" class="ifh"></iframe><br>
	</div>	
	<script language="JavaScript">
		 startChat();
	</script>
<?
include "lib/footer_chat.inc.php";
?>