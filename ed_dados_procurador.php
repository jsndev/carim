<?php
$iREQ_AUT=1;
$aUSERS_PERM[]=10;
$pageTitle = "Contrato configurações";
include "lib/header.inc.php";



if ($crypt->decrypt($_POST["ac"]) == "alterar") {

$nome=$_POST["nome"];
$dados=$_POST["dados"];
	
	if($_POST["nome"]!='' AND $_POST["dados"]!=''){
	
		$db->query="UPDATE contrato_config SET NOME_CONTC='$nome',DADOSPROC_CONTC='$dados' WHERE ID_CONTC='$_REQUEST[cod_proc]'";
		$db->query();


		$msg="<span style='color:#0000FF'><strong>Atualização realizada com sucesso!</strong></span><br /><br />";
	}
	else{
	$msg="<span style='color:#ff0000'><strong>Você deve preencher todos os campos obrigatórios</strong></span><br /><br />";
	}
	
}
else{
$db->query="SELECT * FROM contrato_config WHERE ID_CONTC='$_REQUEST[cod_proc]'";
$db->query();
$nome=$db->qrdata[0]['NOME_CONTC'];
$dados=$db->qrdata[0]['DADOSPROC_CONTC'];
}
?>
<p><a href="dados_procurador.php"><img src="images/buttons/bt_voltar.gif" ></a></p><br />
<form method="post" action="<?=$_SERVER["PHP_SELF"];?>" name="frm1" id="frm1">
<input type="hidden" name="cod_proc" value="<?php echo $_REQUEST["cod_proc"];?>" />
<input type="hidden" name="ac" value="<? echo $crypt->encrypt("alterar");?>" />



			<center><?=$msg;?></center>
	
	<strong>Editar procurador</strong>
<div class="quadroInterno">
	<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
	<div class="quadroInternoMeio">

		 <table cellpadding=0 cellspacing=5 border=0>
	  <tr height="20">
          <td 	valign="top" colspan="2">Todos os campos são obrigat&oacute;rios</td>
        </tr>
		<tr>
          <td align="right" valign="top">Nome:</td>
          <td align="left"  valign="top"><input type="text" name="nome" id="nome" size="50" maxlength="50" value="<?=$nome;?>"></td>
        </tr>
		<tr>
				<td align="right">Dados do procurador: </td>
				<td><textarea name="dados" id="dados" cols="50" rows="10"><?=$dados;?></textarea></td>
		</tr>
				      
		</table>
	</div>
	<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
<br>
<center><input type="image" class="im"  src="images/buttons/bt_salvar.gif"></center>
</form>
<?php
include "lib/footer.inc.php";
?>