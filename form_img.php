<?php
$iREQ_AUT=1;
$aUSERS_PERM[]=8;
$pageTitle = "Tipo de Imagens";
include "lib/header.inc.php";
include("lib/calendar.php");
$mensagem = new mensagens();
$forms = new forms();
$cod_ppst=$_GET['cod'];
$acaoProposta = $crypt->decrypt($_POST["acaoProposta"]);
$acaoProposta='status';
$type=$_POST['type'];
$db->query="Select * from proposta where cod_ppst='".$cod_ppst."'";
$db->query();
$situacao=$db->qrdata[0]['SITUACAO_PPST'];
$db->query="Select * from proponente where cod_ppst='".$cod_ppst."'";
$db->query();
$cod_usuario=$db->qrdata[0]['COD_PROPONENTE'];

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
?>

<form action="upload.php?ps=<?php echo  $cod_ppst;?>&login=<?php echo $login;?>&type=<?php echo $_POST['type'];?>" method="post">
  <br><b>Selecione o tipo de Imagem:</b>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
      <table width="702" border=0 cellpadding=0 cellspacing=5>
		<tbody>
		<?php
		if($situacao<10){?>
			<tr><td width="520"><input type="radio" class="rd" name="type" value="2" onClick="window.location='upload.php?ps=<?php echo  $cod_ppst;?>&type=2'" /> MATRÍCULA&nbsp;&nbsp;<br>
								<input type="radio" class="rd" name="type" value="3" onClick="window.location='upload.php?ps=<?php echo  $cod_ppst;?>&type=3'"/> IPTU &nbsp;&nbsp;<br>
								<input type="radio" class="rd" name="type" value="4" onClick="window.location='upload.php?ps=<?php echo  $cod_ppst;?>&type=4'"/> INFORMAÇÕES PARA SOLICITAÇÃO DE AVALIAÇÃO &nbsp;&nbsp;<br>
								<input type="radio" class="rd" name="type" value="5" onClick="window.location='upload.php?ps=<?php echo  $cod_ppst;?>&type=5'"/> LAUDO DE AVALIAÇÃO &nbsp;&nbsp;<br>
								<input type="radio" class="rd" name="type" value="6" onClick="window.location='upload.php?ps=<?php echo  $cod_ppst;?>&type=6'"/> BOLETOS OU COMPROVANTES DE TRANSFERÊNCIA &nbsp;&nbsp;<br>
								<input type="radio" class="rd" name="type" value="7" onClick="window.location='upload.php?ps=<?php echo  $cod_ppst;?>&type=7'"/> FICHA DE ANÁLISE &nbsp;&nbsp;<br>
								<input type="radio" class="rd" name="type" value="8" onClick="window.location='upload.php?ps=<?php echo  $cod_ppst;?>&type=8'"/> OUTROS &nbsp;&nbsp;<br>

			</td></tr>
		<?php
		}
		if($situacao>10){?>
			<tr><td width="520"><input type="radio" class="rd" name="type" value="2" onClick="window.location='upload.php?ps=<?php echo  $cod_ppst;?>&type=2'" /> MATRÍCULA&nbsp;&nbsp;<br>
								<input type="radio" class="rd" name="type" value="1" onClick="window.location='upload.php?ps=<?php echo  $cod_ppst;?>&type=1'"/> CONTRATO &nbsp;&nbsp;<br>

			</td></tr>
		<?php
		}
		?>
		
			<tr> <td> </td>
		</tr>
		
	</tbody>
	 </table>
	</div>
<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div><br>
<?php 
if($situacao>10){
?>
<p align="right">
<a href="asdmt_lista_prop.php?select=<?php echo $cod_ppst;?>"><img src="images/buttons/bt_voltar.gif" alt="Voltar para Lista de Participantes"></a>
</p>
<?php
}
if($situacao<10){
?>
<p align="right">
<a href="asdmt_avaliacao.php?cod_usuario=<?php echo $cod_usuario;?>"><img src="images/buttons/bt_voltar.gif" alt="Voltar para Lista de Participantes"></a>
</p>
<?php
}
?>


</form>
<?php
include "lib/footer.inc.php";
?><!-- web53410.mail.re2.yahoo.com compressed/chunked Tue Aug  7 04:35:47 PDT 2007 -->
