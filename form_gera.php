<?php
//$iREQ_AUT=1;
//$aUSERS_PERM[]=8;
$pageTitle = "Incluir Imagens";
include "lib/header.inc.php";
include("lib/calendar.php");
$mensagem = new mensagens();
$forms = new forms();
$renova_login=$_GET['login'];
$ps=$_GET['ps'];
$login=$_GET['login'];
$type=$_GET['type'];
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

// Obtém quantidade enviada. Perceba que verifica se é um número inteiro,
// caso contrário, é usada uma quantidade padrão, 5.
$Quantidade = (isset($_POST['quantidade']) && is_int(intval($_POST['quantidade']))) ? (int)$_POST['quantidade'] : 5;

// Abre formulário de upload
echo '<form action="processa_upload.php?ps='.$ps.'&login='.$login.'&type='.$type.'" method="POST" enctype="multipart/form-data">';
echo' <br><b></b>';
echo'	<div class="quadroInterno">';
echo'		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>';
echo'		<div class="quadroInternoMeio">';
echo'      <table width="702" border=0 cellpadding=0 cellspacing=5>';
echo'		<tbody>';
for($i = 1; $i <= $Quantidade; ++$i)
{
    echo '<b>Foto ' . $i . ':</b> <input type="file" name="fotos[]" /><br/>';
}
		
echo'	</tbody>';
echo'	 </table>';
echo'	</div>';
echo'<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>';
echo'</div>';

// Imprime os campos para upload, de acordo com a quantidade pedida
// Fecha formulário
echo '<br /><input type="submit" value="Anexar"/>';
echo '</form>';
include "lib/footer.inc.php";
?>