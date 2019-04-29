<?php
$iREQ_AUT=1;
$aUSERS_PERM[]=8;
$pageTitle = "Incluir Imagens";
include "lib/header.inc.php";
$mensagem = new mensagens();
$forms = new forms();
$cod_ppst=$_GET['ps'];
$type=$_GET['type'];

$uploaddir = $_SERVER['DOCUMENT_ROOT'] . '/carim/imagens_previ/'.$cod_ppst.'/';
umask(0000);
print "<pre>";
if (move_uploaded_file($_FILES['userfile']['tmp_name'],	$uploaddir . md5($_FILES['userfile']['name']))) {
	chmod ($uploaddir . $_FILES['userfile']['name'], 0777);	
  $db->query="INSERT INTO imagem (CATEGORIA,NOME,COD_PPST) VALUES ('".$type."','".$_FILES['userfile']['name']."','".$cod_ppst."')";
  $db->query();
  echo '<div style="vertical-align: middle; padding: 5px; margin: 5px; border: 1px solid #13632C; background-color: #CBE5CF; color: #13632C; font-weight: bold;">
        <img src="images/mensagens/sucesso.gif" alt="Sucesso" style="vertical-align: middle;" />Foto enviada com sucesso.</div>';
  $db->query="Select situacao_ppst,dtremessacontrato_ppst from proposta where cod_ppst='".$cod_ppst."'";
  $db->query();
  if($db->qrdata[0]['dtremessacontrato_ppst']=='' && $db->qrdata[0]['situacao_ppst']>10 && ($type=='1' || $type=='2'))
  {
	  $db->query="update proposta set dtremessacontrato_ppst= now() where cod_ppst='".$cod_ppst."'";
	  $db->query();
  }
}
?>
<form enctype="multipart/form-data" action="upload.php?ps=<?php echo $cod_ppst;?>&login=<?php echo $login;?>&type=<?php echo $type;?>" method="post">

<input type="hidden" name="MAX_FILE_SIZE" value="30000000">
Enviar Arquivo: <input name="userfile" type="file"> 
<input type="submit" value="Enviar Arquivo">
</form>
<br>
<p align="right">
<a href="form_img.php?cod=<?php echo $cod_ppst;?>"><img src="images/buttons/bt_voltar.gif" alt="Voltar para Lista de Opções de Imagens"></a>
</p>
<?php
include "lib/footer.inc.php";

?>