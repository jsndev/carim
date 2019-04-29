<?
$pageTitle = "Ajuda";
include "lib/header.inc.php";


if($cLOGIN->bOK == 1) {
$conteudo = new conteudo();

$dadosTree = $conteudo->getTree();
?>
<table cellpadding="0" cellspacing="0">
	<colgroup>
		<col width="188" style="background-color: #FFFFFF;"></col>
		<col width="540"></col>
	</colgroup>
	<tr>
		<td valign="top">
			<div style="width: 188px; height: 400px; overflow: auto;">
<a href='ajuda/simulador.php' target="iFrCont">Simulador</a><br /><br />
<a href='ajuda/documentos.php' target="iFrCont">Documentos</a><br /><br />
<a href='ajuda/faq.php' target="iFrCont">FAQ</a><br /><br />
<a href='ajuda/link.php' target="iFrCont">Links Úteis</a><br /><br />
			</div>
		</td>
		<td valign="top">
			<div style="width: 538px; height: 400px; background-color: #EEEEEE; border: 1px solid #DDDDDD;">
				<iframe style="margin:0px; background-color: #EEEEEE;" id="iFrCont" name="iFrCont" src="blank.php" height="400" frameborder="0" width="538" marginheight="0" marginwidth="0" scrolling="auto"></iframe>
			</div>
		</td>
	</tr>
</table>

<?
}
else{
echo "<br><br><br><center>Você precisa estar logado para acessar a Ajuda.</center><br><br><br>";
}

include "lib/footer.inc.php";
?>