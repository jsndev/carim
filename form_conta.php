<?php
//$iREQ_AUT=1;
//$aUSERS_PERM[]=8;
$pageTitle = "Incluir Imagens";
include "lib/header.inc.php";
include("lib/calendar.php");
$mensagem = new mensagens();
$forms = new forms();
$renova_login=$_GET['login'];
$login=$_GET['login'];
$cod_ppst=$_GET['ps'];
$acaoProposta = $crypt->decrypt($_POST["acaoProposta"]);
$acaoProposta='status';
// -------------------------------------------------
// Arquivo integrante do artigo:
//   PHP: Formulários e upload de múltiplos arquivos
// Autor:
//   Alfred Reinold Baudisch
// E-mail:
//   alfred@auriumsoft.com.br
// Site:
//   www.auriumsoft.com.br
// Data:
//   28/02/2006
// Download do artigo:
//   http://www.auriumsoft.com.br/alfred/artigos/multiplos.zip
// -------------------------------------------------
$type=$_POST['type'];
?>
<form action="form_gera.php?ps=<?php echo $cod_ppst;?>&login=<?=$login;?>&type=<?=$type;?>" method="post">
  <br><b>Imagens</b>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
      <table width="702" border=0 cellpadding=0 cellspacing=5>
		<tbody>
		 <tr>
		 	<td width="167" height="19">Número de Imagens para incluir:</td>
			<td width="520"><input type="text" name="quantidade" size="5"/> &nbsp;&nbsp;
			  <input type="submit" value="Avançar"/></td>
		</tr>
		
	</tbody>
	 </table>
	</div>
<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>

</form>
<?
include "lib/footer.inc.php";
?><!-- web53410.mail.re2.yahoo.com compressed/chunked Tue Aug  7 04:35:47 PDT 2007 -->
