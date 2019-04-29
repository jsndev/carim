<?
// incluindo arquivo de configuração e classes
include "./class/dbclasses.class.php";

if(!isset($_SESSION['CODCHTU'])){
	header ("Location: other_chat_login.php");
	exit();
}

$pageTitle = "Chat Contrathos";
include "lib/header_chat.inc.php";

$_SESSION['CHAT']      = '';
$_SESSION['ATEN']      = '';
$_SESSION['CHAT_ERRO'] = 0;
?>
	<style>
  .ifh{ width:350px; height:40px; margin:3px; }
  </style>
	<script language="JavaScript" src="./js/chatOther.js"></script>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
			<div id="divMensagens" class="divMensagens"></div>
			<div id="divTxtPost" class="divTxtPost" style="visibility:hidden;">
				<form id="formPost" action="other_chat_post.php" method="POST" target="iframePost">
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