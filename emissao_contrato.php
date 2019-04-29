<?
//$iREQ_AUT=1;
//$aUSERS_PERM[]=8;
$pageTitle = "Participantes";
include "lib/header.inc.php";
$cod_ppst=$_GET['cod_proposta'];
$alt=$_POST['alt'];
$contr=$_GET['cont'];
$resp=$_GET['resp'];
if($_POST)
{
	$dtassinatura=$_POST['dtassinatura'];
	$vlseguro=$_POST['vlseguro'];
}

if($_POST['svdt']=='salvar'){
	$vlseg=str_replace(".","",$vlseguro);
	$vlseg=str_replace(",",".",$vlseg);
	$db->query="Update proposta set dtasscontrato_ppst='".$utils->formataData($dtassinatura)."', valorseguro_ppst='".$vlseg."' where cod_ppst='".$cod_ppst."'";
	$db->query();
	$alt='no';
}
if($contr=='aprov'){
	$db->query="Update proposta set situacao_ppst='9' where cod_ppst='".$cod_ppst."'";
	$db->query();
	$db->query="Insert into historico (COD_PPST, DT_HIST, OBS_HIST, TIPO_HIST, COD_USUA) values ('".$cod_ppst."',now(),'Contrato Conferido e Enviado para Registro','1','".$resp."')";
	$db->query();
}

$db->query="Select * from proponente where cod_ppst='".$cod_ppst."'";
$db->query();
$cod_ppnt=$db->qrdata[0]['COD_PROPONENTE'];

$db->query="Select * from proposta where cod_ppst='".$cod_ppst."'";
$db->query();
$dtassinatura=$db->qrdata[0]['DTASSCONTRATO_PPST'];
$vlseguro=$db->qrdata[0]['VALORSEGURO_PPST'];

$db->query="Select * from usuario where cod_usua='".$cod_ppnt."'";
$db->query();
$nome_ppnt=$db->qrdata[0]['NOME_USUA'];
$id_lstn=$db->qrdata[0]['ID_LSTN'];

$db->query="Select * from usuario where cod_usua='".$resp."'";
$db->query();
$nome_resp=$db->qrdata[0]['NOME_USUA'];

	//______________________ Dados do Lista de Nomes ___________________________
	$db->query= "SELECT * FROM  listadenomes WHERE id_lstn='".$id_lstn."' LIMIT 1";
	$db->query();
	$dtconvocacao_finan=strtotime($db->qrdata[0]['DTCONVOCACAO']);	
	
	$digitomat= substr($id_lstn, -1);
	$datacontratofuturo=strtotime("01-03-2008");
	if($digitomat==2 AND $dtconvocacao_finan>=$datacontratofuturo){
	$paginacontrato="contratofuturo.php";
	}			
	else{
	$paginacontrato="contrato.php";
	}

?>
<script language="JavaScript" src="./js/diversos.js"></script>
<script>
function salvarData()
{
	document.getElementById('svdt').value='salvar';
	document.getElementById('alt').value='no';
	return true;
}
function alterarData()
{
	document.getElementById('alt').value='yes';
	return true;
}
</script>
<form name="proposta" method="post" action="">

	<br><b>Informações da Proposta</b>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
	  <table>
	  	<tr>
			<td align="right">C.I.:</td>
			<td align="left"><b><?php echo $utils->formataMatricula($id_lstn);?></b></td>
			<td></td>
		</tr>
	  	<tr>
			<td align="right">Participante:</td>
			<td align="left"><b><?php echo $nome_ppnt;?></b></td>
			<td></td>
		</tr>
	  	<tr>
			<td align="right"></td>
			<td align="left"></td>
			<td></td>
		</tr>
		<?php if($alt!='yes'){?>
	  	<tr>
			<td align="right">Data Assinatura do Contrato:</td>
			<td align="left"><b><?php echo $utils->formataDataBRA($dtassinatura);?></b></td>
			<td></td>
		</tr>
		<tr>
			<td align="right">Valor do Seguro:</td>
			<td align="left"><b><?php echo $utils->formataMoeda($vlseguro);?></b></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td>
			<?php
			if($contr!='aprov'){?>
				<input type="image" class="im" name="alterar" value="alterar" id="alterar" onClick="return alterarData();" src="images/buttons/bt_alterar.gif">
				<input type="hidden" name="alt" id="alt" value="">
			<?php
			}
			?></td>
			<td></td>
		</tr>
	<?php }?>
		<?php if($alt=='yes'){?>
	  	<tr>
			<td align="right">Data Assinatura do Contrato:</td>
			<td align="left"><input type="text" style="width:80px;" name="dtassinatura" id="dtassinatura" value="<?php echo $utils->formataDataBRA($dtassinatura);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10"></td>
			<td></td>
		</tr>
		<tr>
			<td align="right">Valor do Seguro:</td>
			<td align="left"><input type="text" name="vlseguro" id="vlseguro" value="<?php echo $utils->formataFloat($vlseguro,2);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraMoeda(this,event,'atualizaValoresProposta()',2);" maxlength="12" /></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="image" class="im" name="salvar" value="salvar" id="salvar" onClick="return salvarData();" src="images/buttons/bt_salvar.gif">
				<input type="hidden" name="svdt" id="svdt" value=""></td>
			<td></td>
		</tr>
	<?php }?>
	  </table>
	  </div>
			<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
<?php 
if($contr!='aprov'){
?>
		<br><b>Emissão do Contrato</b>
<div class="quadroInterno">
			<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
			<table width="290" align="center">
				<tr>
					<td><a target="_blank" href="fpdf/<?=$paginacontrato;?>?cod_proposta=<?php echo $cod_ppst;?>&resp=<?php echo $resp;?>"><img src="images/buttons/bt_gerar_contrato.gif"></a></td>
					<td><a href="emissao_contrato.php?cod_proposta=<?php echo $cod_ppst;?>&resp=<?php echo $resp;?>&cont=aprov"><img src="images/buttons/bt_aprovar_contrato.gif"></a></td>
				</tr>
	</table>
			<div class="quadroInternoMeio">
  		</div>
			<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
<?php
}
?><br>
<p align="right">
<a href="asdmt_lista_propccb.php"><img src="images/buttons/bt_voltar.gif"></a>
</p>
</form>
<?php
include "lib/footer.inc.php";
?>