<input type="hidden" name="cod_ppnt_<?=$ippnt;?>"       id="cod_ppnt_<?=$ippnt;?>"       value="<?=$registroPpnt["cod_proponente"];?>" />
<?
?>
<script language="javascript">
function atualexig(_cod,_acao){
	document.getElementById('proposta').action += '#exigencia';
	document.getElementById('proposta').submit();
}
function verificaexig()
{
	if(document.getElementById('descexig'))
	{
		if(document.getElementById('descexig').value=='')
		{
			alert('Preencha a Descrição da Exigencia')
			return false;
		}else
		{
			return true;
		}
	}else
	{
		return true;
	}		
}
function salvarExig()
{
	if(verificaexig())
	{
		document.getElementById('BtExig').value ="salvar";
	    document.getElementById('proposta').action += '#proponente';
		document.getElementById('proposta').submit();
  		return true;
	}else
	{
		return false;
	}
}
</script>

<?

########################## BUSCA DATAS DE INICIO E FIM DE EXIGENCIA NO BANCO DE DADOS ######################
$db->query="Select * from proponente where cod_proponente='".$registroPpnt["cod_proponente"]."'";
$db->query();
if($db->qrcount>0)
{
	$cod_ppst= $db->qrdata[0]['COD_PPST'];
}

$db->query="Select * from proposta where cod_ppst='".$cod_ppst."'";
$db->query();
if($db->qrcount>0)
{
	$iniexigencia=		$utils->formataDataBRA($db->qrdata[0]['DTINIEXIGENCIA_PPST']);
	//$fimexigencia=		$utils->formataDataBRA($db->qrdata[0]['DTFIMEXIGENCIA_PPST']);
}
$dtini=$iniexigencia;
//$dtfim=$fimexigencia;

############################ VARIAVEIS RECEBEM POST DE DATAS DE  INICIO E FIM DE EXIGENCIA  ############
if($_POST['fimexigprop'])
{
	$dtfim=$_POST['fimexigprop'];
}
$addexig=$_POST['addexig'];
$exig=$_POST['BtExig'];
############################## SALVA DATAS DE EXIGENCIA NO BANCO DE DADOS ########################
if($exig=='salvar')
{
	
	if($iniexigencia=='')
	{
		$db->query="Update proposta set dtiniexigencia_ppst= now() where cod_ppst='".$cod_ppst."'";
		$db->query();
	}
	if($dtfim!='')
	{
		$db->query="Update proposta set dtiniexigencia_ppst=NULL where cod_ppst='".$cod_ppst."'";
		$db->query();
	}
	$addexig='';
	if($_POST['descexig']!='')
	{
		$db->query="Insert into historico (COD_PPST, DT_HIST, OBS_HIST, TIPO_HIST, COD_USUA) values ('".$cod_ppst."',now(),'".$_POST[descexig]."','2','".$cLOGIN->iID."')";
		$db->query();
	}
	if($dtfim!='')
	{
		$datdia=	 substr($dtfim,0,2);
		$datmes=	 substr($dtfim,3,2);
		$datano=     substr($dtfim,6,4);
		$datafim= $datano."-".$datmes."-".$datdia;
		$db->query="Update proposta set dtfimexigencia_ppst='".$datafim."' where cod_ppst='".$cod_ppst."'";
		$db->query();
	}else
	{
		$db->query="Update proposta set dtfimexigencia_ppst=NULL where cod_ppst='".$cod_ppst."'";
		$db->query();
	}
}

#################### CONDIÇÕES PARA BLOQUEIO DOS CAMPOS DE INICIO DE EXIGENCIA ####################
?>
<?
$hoje=date("d/m/Y");
?>
<input type="hidden" name="dthoje" id="dthoje" value="<?=$hoje;?>">
<input type="hidden" name="BtExig" id="BtExig" value="">

<div id="div_exig" class="grupoDados" style="clear:both;">
<a name="exigencia"></a>
<table cellpadding=0 cellspacing=5 border=0 class="tb_dets_list">
<?
$db->query="Select  DTINIEXIGENCIA_PPST from proposta where cod_ppst='".$aProposta["cod_ppst"]."'";
$db->query();
if($db->qrcount>0)
{
	$dt_iniexig=$db->qrdata[0]['DTINIEXIGENCIA_PPST'];
}

if($cLOGIN->iLEVEL_USUA!=1)
{
	if($dt_iniexig!='')
	{
?>	
	<tr style="<?=$mostra;?>">
	  <td align="left" valign="top">Início de Exigência:&nbsp;&nbsp;&nbsp;<b><? echo $utils->formataDataBRA($dt_iniexig);?></b>
	  							   &nbsp;&nbsp;&nbsp; Fim de Exigência:&nbsp;&nbsp;&nbsp;<input type="text" name="fimexigprop" id="fimexigprop"  value="<?=$dtfim;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  </td>
	</tr>
	<tr style="<?=$mostra;?>">
	  <td align="left" valign="top"><hr></td>
	</tr>
<? }
if($dt_iniexig=='')
{
?>
	<tr >
	  <td align="left"><input <? if ($addexig==1){echo "checked";}?> type="radio" class="rd" name="addexig" id="addexig" value="1" onclick="atualexig();" >&nbsp;&nbsp;&nbsp;<b>Adicionar Exigência</b></td>
<? 
}
if ($addexig==1)
{?>
	</tr>
	<tr id="tb_dets_exig" style="">
	<td><textarea cols="120" name="descexig" id="descexig"></textarea>
	</td>
	</tr>
<?
}?>
<tr>
	  <td align="right" valign="top"><img src="images/buttons/bt_salvar.gif"   id="bt_save_exig"   alt="Salvar Exigência" class="im" onClick="return salvarExig();" /> </td>
	</tr> <? 
}?>	
	</table>
	</div>
