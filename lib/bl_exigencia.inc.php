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
