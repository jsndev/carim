<input type="hidden" name="cod_ppnt_<?=$ippnt;?>"       id="cod_ppnt_<?=$ippnt;?>"       value="<?=$registroPpnt["cod_proponente"];?>" />
<input type="hidden" name="nome_ppnt_<?=$ippnt;?>"      id="nome_ppnt_<?=$ippnt;?>"      value="<?=$registroPpnt["usuario"][0]["nome_usua"];?>" />
<? if(FLG_PREVI){ ?>
	<input type="hidden" name="vlcompra_ppnt_<?=$ippnt;?>"  id="vlcompra_ppnt_<?=$ippnt;?>"  value="<?=$utils->formataFloat($registroPpnt["vlcompra_ppnt"],2);?>" />
	<input type="hidden" name="vlentrada_ppnt_<?=$ippnt;?>" id="vlentrada_ppnt_<?=$ippnt;?>" value="<?=$utils->formataFloat($registroPpnt["vlentrada_ppnt"],2);?>" />
	<input type="hidden" name="vlsinal_ppnt_<?=$ippnt;?>"   id="vlsinal_ppnt_<?=$ippnt;?>"   value="<?=$utils->formataFloat($registroPpnt["vlsinal_ppnt"],2);?>" />
<? }

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
$db->query="Select * from usuario where cod_usua='".$cLOGIN->iID."'";
$db->query();
if($db->qrcount>0)
{
	$level_usua= $db->qrdata[0]['LEVEL_USUA'];
}
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
if($_POST['fimexigconj'])
{
	$dtfim=$_POST['fimexig'];
}
$addexig=$_POST['addexig'];
$addexig2=$_POST['addexig2'];
$exig=$_POST['BtExig'];
if($iniexigencia=='' || $dtini=='')
{
		$mostra='display:none';		
}
############################## SALVA DATAS DE EXIGENCIA NO BANCO DE DADOS ########################
if($exig=='salvar')
{
	if($iniexigencia!='' || $dtini!='')
	{
		$mostra='display:none';	
	}

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
	$addexig2='';
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

<table cellpadding=0 cellspacing=5 border=0 class="tb_dets_list">
	<colgroup><col width="180" /><col /></colgroup>
	<tr>
	  <td align="right" valign="top">E-Mail:</td><td align="left"><b><?=$registroPpnt["usuario"][0]["email_usua"];?></b></td>
	</tr>
	<tr>
	  <td align="right" valign="top">Data Nasc:</td><td align="left"><b><?=$utils->formataDataBRA($registroPpnt["dtnascimento_ppnt"]);?> <?=$utils->formataIdade($utils->formataDataBRA($registroPpnt["dtnascimento_ppnt"]));?></b></td>
	</tr>

	<tr>
	  <td align="right" valign="top">Est Civil:</td><td align="left"><b><?=$registroPpnt["estadocivil"][0]["desc_estciv"];?></b></td>
	</tr>
	<? if($registroPpnt["cod_estciv"]==2 || $registroPpnt["cod_estciv"]==99){ ?>
	<tr>
	  <td align="right" valign="top">Data do Casamento:</td><td align="left"><b><?=$utils->formataDataBRA($registroPpnt["conjuge"][0]["dtcasamento_ppcj"]);?></b></td>
	</tr>
	<tr>
	  <td align="right" valign="top">Regime de Bens:</td><td align="left"><b><?
    	$vTMP = $registroPpnt["conjuge"][0]["regimebens_ppcj"];
    	$aTMP = $listas->getListaRegimeBens($vTMP);
    	print $aTMP[$vTMP];
  	?></b></td>
	</tr>
		<? if($registroPpnt["conjuge"][0]["regimebens_ppcj"]==3 || $registroPpnt["conjuge"][0]["regimebens_ppcj"]==5){ ?>
		<tr>
		  <td align="right" valign="top">Data:</td><td align="left"><b><?=$utils->formataDataBRA($registroPpnt["conjugepacto"][0]["data_pcpa"]);?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Lavrado no:</td><td align="left"><b><?=$registroPpnt["conjugepacto"][0]["locallavracao_pcpa"];?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Livro:</td><td align="left"><b><?=$registroPpnt["conjugepacto"][0]["livro_pcpa"];?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Fls.:</td><td align="left"><b><?=$registroPpnt["conjugepacto"][0]["folha_pcpa"];?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Número do Registro:</td><td align="left"><b><?=$registroPpnt["conjugepacto"][0]["numeroregistro_pcpa"];?></b></td>
		</tr>
		<? } ?>
	<? } ?>
	<tr>
	  <td align="right" valign="top">Logradouro:</td><td align="left"><b><?=$registroPpnt["logradouro"][0]["desc_logr"];?></b></td>
	</tr>
	<tr>
	  <td align="right" valign="top">Endereço:</td><td align="left"><b><?=$registroPpnt["endereco_ppnt"];?></b></td>
	</tr>
	<tr>
	  <td align="right" valign="top">Número:</td><td align="left"><b><?=$registroPpnt["nrendereco_ppnt"];?></b></td>
	</tr>
	<tr>
	  <td align="right" valign="top">Complemento:</td><td align="left"><b><?=$registroPpnt["cpendereco_ppnt"];?></b></td>
	</tr>
	<tr>
	  <td align="right" valign="top">Bairro:</td><td align="left"><b><?=$registroPpnt["bairro"][0]["nome_bairro"];?></b></td>
	</tr>
	<tr>
	  <td align="right" valign="top">Cidade:</td><td align="left"><b><?=$registroPpnt["municipio"][0]["nome_municipio"];?></b></td>
	</tr>
	<tr>
	  <td align="right" valign="top">Estado:</td><td align="left"><b><?=$registroPpnt["uf"][0]["nome_uf"];?></b></td>
	</tr>
	<tr>
	  <td align="right" valign="top">CEP:</td><td align="left"><b><?=$utils->formataCep($registroPpnt["cep_ppnt"]);?></b></td>
	</tr>
	<tr>
	  <td align="right" valign="top">Telefones:</td><td align="left">
	   <?
	     $tipoTel = $listas->getListaTipoTelefone();
	     $aTelefones = $registroPpnt["telefones"];
	     if( is_array($aTelefones) && @count($aTelefones)>0 ){
	       foreach ($aTelefones as $kTelefones=>$vTelefones){
	         echo '<b>'.$utils->formataTelefone($vTelefones["TELEFONE_PPTL"]).'</b> ('.$tipoTel[$vTelefones["TIPO_PPTL"]].')<br />';
	       }
	     }
	   ?>
	  </td>
	</tr>
	<tr>
	  <td align="right" valign="top">E-Mail:</td><td align="left"><b><?=$registroPpnt["email_ppnt"];?></b></td>
	</tr>
</table>
<div id="div_exig" class="grupoDados" style="clear:both;">
<a name="exigencia"></a>
<table cellpadding=0 cellspacing=5 border=0 class="tb_dets_list">
<?
if($level_usua!=1)
{
?>	
	<tr style="<?=$mostra;?>">
	  <td align="left" valign="top">Início de Exigência:&nbsp;&nbsp;&nbsp;<b><? echo $iniexigencia;?></b>
	  							   &nbsp;&nbsp;&nbsp; Fim de Exigência:&nbsp;&nbsp;&nbsp;<input type="text" name="fimexigprop" id="fimexigprop"  value="<?=$dtfim;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  </td>
	</tr>
	<tr style="<?=$mostra;?>">
	  <td align="left" valign="top"><hr></td>
	</tr>
<?
?>

	<tr >
	  <td align="left"><input <? if ($addexig==1){echo "checked";}?> type="radio" class="rd" name="addexig" id="addexig" value="1" onclick="atualexig();" >&nbsp;&nbsp;&nbsp;<b>Adicionar Exigência</b></td>
<? 
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
	</tr>
<?
}?>	
	</table>
<? if($aProposta["situacao_ppst"] >= 3 ){
	$just_display = ($cLOGIN->iLEVEL_USUA == TPUSER_PROPONENTE)?false:true;
	$aAltPpnt = $registroPpnt;
	?><div id="ckls_ppnt">
	<? include('bl_ckls_proponente.inc.php'); ?></div><? 
} ?>
	</div>
<div id="div_empresa" class="grupoDados" style="clear:both;">
	<b>Dados Profissionais</b>
 	<table cellpadding=0 cellspacing=5 border=0 class="tb_dets_list">
 		<colgroup><col width="180" /><col /></colgroup>
		<tr>
		  <td align="right" valign="top">Empresa:</td><td align="left"><b><?=$registroPpnt["profissao"][0]["empresa_pppf"];?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Data de Admissão:</td><td align="left"><b><?=$utils->formataDataBRA($registroPpnt["profissao"][0]["dtadmissao_pppf"]);?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Endereço:</td><td align="left"><b><?=$registroPpnt["profissao"][0]["enderecoemp_pppf"];?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Número:</td><td align="left"><b><?=$registroPpnt["profissao"][0]["numeroemp_pppf"];?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Complemento:</td><td align="left"><b><?=$registroPpnt["profissao"][0]["complementoemp_pppf"];?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Bairro:</td><td align="left"><b><?
		  	if($registroPpnt["profissao"][0]["bairro_pppf"]){
		    	$vTMP = $registroPpnt["profissao"][0]["bairro_pppf"];
		    	$aTMP = $listas->getListaBairro($vTMP);
		    	print $aTMP[0]["nome_bairro"];
		  	}
		  ?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Cidade:</td><td align="left"><b><?
		  	if($registroPpnt["profissao"][0]["cidade_pppf"]){
			  	$vUF  = $registroPpnt["profissao"][0]["estado_pppf"];
		    	$vTMP = $registroPpnt["profissao"][0]["cidade_pppf"];
		    	$aTMP = $listas->getListaMunicipio($vUF,$vTMP);
		    	print $aTMP[0]["nome_municipio"];
		  	}
		  ?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Estado:</td><td align="left"><b><?
		  	if($registroPpnt["profissao"][0]["estado_pppf"]){
		    	$vTMP = $registroPpnt["profissao"][0]["estado_pppf"];
		    	$aTMP = $listas->getListaUF($vUF,$vTMP);
		    	print $aTMP[0]["nome_uf"];
		  	}
		  ?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Telefone:</td><td align="left"><b><?=$utils->formataTelefone($registroPpnt["profissao"][0]["telefone_pppf"]);?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Cargo:</td><td align="left"><b><?=$registroPpnt["profissao"][0]["cargo_pppf"];?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Salário:</td><td align="left"><b>R$ <?=$utils->formataMoeda($registroPpnt["profissao"][0]["salario_pppf"]);?></b></td>
		</tr>
	</table>
</div>

<? if($registroPpnt["cod_estciv"]==2 || $registroPpnt["cod_estciv"]==99){ ?>
<div id="div_conjuje" class="grupoDados">
	<b>Dados do Cônjuge</b>
 	<table cellpadding=0 cellspacing=5 border=0 class="tb_dets_list">
 		<colgroup><col width="180" /><col /></colgroup>
		<tr>
		  <td align="right" valign="top">Nome:</td><td align="left"><b><?=$registroPpnt["conjuge"][0]["nome_ppcj"];?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Nacionalidade:</td><td align="left"><b><?
		  	if($registroPpnt["conjuge"][0]["cod_pais"]){
		    	$vTMP = $registroPpnt["conjuge"][0]["cod_pais"];
		    	$aTMP = $listas->getListaPais($vTMP);
		    	print $aTMP[0]["nome_pais"];
		  	}
		  ?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">RG:</td><td align="left">
		  	<b><?=$registroPpnt["conjuge"][0]["nrrg_ppcj"];?></b> &nbsp;&nbsp;
		  	Emissão: <b><?=$utils->formataDataBRA($registroPpnt["conjuge"][0]["dtrg_ppcj"]);?></b> &nbsp;&nbsp;
		  	Órgão Emissor: <b><?=$registroPpnt["conjuge"][0]["orgrg_ppcj"];?></b>
		  </td>
		</tr>
		<tr>
		  <td align="right" valign="top">CPF:</td><td align="left"><b><?=$utils->formataCPF($registroPpnt["conjuge"][0]["cpf_pccj"]);?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Trabalha atualmente:</td><td align="left"><b><?
	    	$vTMP = $registroPpnt["conjuge"][0]["flgtrabalha_ppcj"];
	    	$aTMP = $listas->getListaSN($vTMP);
	    	print $aTMP[$vTMP];
		  ?></b></td>
		</tr>
	<? if($registroPpnt["conjuge"][0]["flgtrabalha_ppcj"]=='S'){ ?>
		<tr>
		  <td align="left" valign="top" colspan="2"><b>Dados Profissionais do Cônjuge</b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Empresa:</td><td align="left"><b><?=$registroPpnt["conjuge"][0]["empresa_ppcj"];?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Data de Admissão:</td><td align="left"><b><?=$utils->formataDataBRA($registroPpnt["conjuge"][0]["dtadmissaoemp_ppcj"]);?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Endereço:</td><td align="left"><b><?=$registroPpnt["conjuge"][0]["enderecoemp_ppcj"];?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Número:</td><td align="left"><b><?=$registroPpnt["conjuge"][0]["numeroemp_ppcj"];?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Complemento:</td><td align="left"><b><?=$registroPpnt["conjuge"][0]["complementoemp_ppcj"];?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Bairro:</td><td align="left"><b><?
	    	$vTMP = $registroPpnt["conjuge"][0]["bairroemp_ppcj"];
	    	$aTMP = $listas->getListaBairro($vTMP);
	    	print $aTMP[0]["nome_bairro"];
		  ?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Cidade:</td><td align="left"><b><?
		  	$vUF  = $registroPpnt["conjuge"][0]["estadoemp_ppcj"];
	    	$vTMP = $registroPpnt["conjuge"][0]["cidadeemp_ppcj"];
	    	$aTMP = $listas->getListaMunicipio($vUF,$vTMP);
	    	print $aTMP[0]["nome_municipio"];
		  ?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Estado:</td><td align="left"><b><?
	    	$vTMP = $registroPpnt["conjuge"][0]["estadoemp_ppcj"];
	    	$aTMP = $listas->getListaUF($vUF,$vTMP);
	    	print $aTMP[0]["nome_uf"];
		  ?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Telefone:</td><td align="left"><b><?=$utils->formataTelefone($registroPpnt["conjuge"][0]["telefoneemp_ppcj"]);?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Cargo:</td><td align="left"><b><?=$registroPpnt["conjuge"][0]["cargoemp_ppcj"];?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Salário:</td><td align="left"><b>R$ <?=$utils->formataMoeda($registroPpnt["conjuge"][0]["salarioemp_ppcj"]);?></b></td>
		</tr>
		<? } ?>
 	</table>
<div id="div_exig" class="grupoDados" style="clear:both;">
<a name="exigencia"></a>
<table cellpadding=0 cellspacing=5 border=0 class="tb_dets_list">
<?
if($level_usua!=1)
{
?>	
	<tr style="<?=$mostra;?>">
	  <td align="left" valign="top">Início de Exigência:&nbsp;&nbsp;&nbsp;<b><? echo $iniexigencia;?></b>
	  							   &nbsp;&nbsp;&nbsp; Fim de Exigência:&nbsp;&nbsp;&nbsp;<input type="text" name="fimexigprop" id="fimexigprop"  value="<?=$dtfim;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  </td>
	</tr>
	<tr style="<?=$mostra;?>">
	  <td align="left" valign="top"><hr></td>
	</tr>
<?
?>
	<tr >
	  <td align="left"><input <? if ($addexig2==1){echo "checked";}?> type="radio" class="rd" name="addexig2" id="addexig2" value="1" onclick="atualexig();" >&nbsp;&nbsp;&nbsp;<b>Adicionar Exigência</b></td>
<? if ($addexig2==1)
{?>
	</tr>
	<tr id="tb_dets_exig" style="">
	<td><textarea cols="120" name="descexig" id="descexig"></textarea>
	</td>
	</tr>
<?
?>
<tr>
	  <td align="right" valign="top"><img src="images/buttons/bt_salvar.gif"   id="bt_save_exig"   alt="Salvar Exigência" class="im" onClick="return salvarExig();" /> </td>
	</tr>
<?
}
}?>	
	</table>
	<? if($aProposta["situacao_ppst"] >= 3 ){ 
		$aAltPpnt = $registroPpnt;
		?><div id="ckls_ppcj"><? include('bl_ckls_conjuge.inc.php'); ?></div><?
	} ?>
</div>	
</div>
<? } ?>

<div id="div_devedor" class="grupoDados">
	<b>Devedor Solidário</b>
 	<table cellpadding=0 cellspacing=5 border=0 class="tb_dets_list">
 		<colgroup><col width="180" /><col /></colgroup>
    <tr>
      <td align="right" valign="top">Possui Devedor Solidário:</td>
      <td align="left"  valign="top"><b><?
	    	$vTMP = ($registroPpnt["flgdevsol_ppnt"]=='S')?'S':'N';
	    	$aTMP = $listas->getListaSN($vTMP);
	    	print $aTMP[$vTMP];
      ?></b></td>
    </tr>
    <? if($registroPpnt["flgdevsol_ppnt"]=='S'){ ?>
		<tr>
		  <td align="right" valign="top">Nome:</td><td align="left"><b><?=$registroPpnt["devsol"][0]["nome_devsol"];?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Nome Abreviado:</td><td align="left"><b><?=$registroPpnt["devsol"][0]["nick_devsol"];?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Logradouro:</td><td align="left"><b><?=$registroPpnt["devsol"][0]["logradouro"][0]["desc_logr"];?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Endereço:</td><td align="left"><b><?=$registroPpnt["devsol"][0]["endereco_devsol"];?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Num:</td><td align="left"><b><?=$registroPpnt["devsol"][0]["nrendereco_devsol"];?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Complemento:</td><td align="left"><b><?=$registroPpnt["devsol"][0]["cpendereco_devsol"];?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Bairro:</td><td align="left"><b><?=$registroPpnt["devsol"][0]["bairro"][0]["nome_bairro"];?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Cidade:</td><td align="left"><b><?=$registroPpnt["devsol"][0]["municipio"][0]["nome_municipio"];?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Estado:</td><td align="left"><b><?=$registroPpnt["devsol"][0]["uf"][0]["nome_uf"];?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">CEP:</td><td align="left"><b><?=$utils->formataCep($registroPpnt["devsol"][0]["cep_devsol"]);?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Telefone:</td><td align="left"><b><?=$utils->formataTelefone($registroPpnt["devsol"][0]["telefone_devsol"]);?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">CPF:</td><td align="left"><b><?=$utils->formataCPF($registroPpnt["devsol"][0]["cpf_devsol"]);?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Sexo:</td><td align="left"><b><?
	    	$vTMP = $registroPpnt["devsol"][0]["sexo_devsol"];
	    	$aTMP = $listas->getListaSexo($vTMP);
	    	print $aTMP[$vTMP];
		  ?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Nacionalidade:</td><td align="left"><b><?=$registroPpnt["devsol"][0]["pais"][0]["nome_pais"];?></b></td>
		</tr>
		<? } ?>
	</table>
</div>
<? 
$db->query = "Select * from fgts where cod_usua='".$registroPpnt["cod_proponente"]."'";
//echo $db->query;
$db->query();
if($db->qrcount>0)
{	
	$aAltPpnt["flgfgts_ppnt"] 	= 				$db->qrdata[0]['FLAGUTILIZACAO'];
	$aAltPpnt["fgts"][0]["stimov_fgts"] = 		$db->qrdata[0]['STATUSIMOV'];
	$aAltPpnt["fgts"][0]["municipio_fgts"] = 	$db->qrdata[0]['NOMEMUNIBGE'];
	$aAltPpnt["fgts"][0]["codmunicipio_fgts"] = $db->qrdata[0]['CODMUNIBGE'];
	$aAltPpnt["fgts"][0]["estado_fgts"]=		$db->qrdata[0]['UFIBGE'];
	$aAltPpnt["fgts"][0]["qtcontas"]=			$db->qrdata[0]['QTCONTAS'];
	$aAltPpnt["fgts"][0]["valoper_fgts"]=		$db->qrdata[0]['VALOPERACAO'];
}

$dadoscontafgts='';
$db->query = "Select * from contasfgts where cod_usua='".$registroPpnt["cod_proponente"]."'";
//echo $db->query;
$db->query();
if($db->qrcount>0)
{
	$dadoscontafgts=1;
	$c=0;
	while($c<=$db->qrcount)
	{
		$aAltPpnt["fgts"][$c+1]["nometrab_fgts"] = 		 	 $db->qrdata[$c]['NOMETRAB'];
		$aAltPpnt["fgts"][$c+1]["dtnasctrab_fgts"] = 		 $db->qrdata[$c]['DTNASCTRAB'];
		$aAltPpnt["fgts"][$c+1]["pis_fgts"] = 		 		 $db->qrdata[$c]['NUMPISPASEP'];
		$aAltPpnt["fgts"][$c+1]["sitconta_fgts"] = 	 		 $db->qrdata[$c]['SITUACAOCONTA'];
		$aAltPpnt["fgts"][$c+1]["contaemp_fgts"] =     		 $db->qrdata[$c]['CODCONTAEMP'];
		$aAltPpnt["fgts"][$c+1]["contatrab_fgts"]=	 		 $db->qrdata[$c]['CODCONTATRAB'];
		$aAltPpnt["fgts"][$c+1]["valordeb_fgts"]=		 	 $db->qrdata[$c]['VALORDEBITADO'];
		$aAltPpnt["fgts"][$c+1]["baseconta_fgts"]=	 		 $db->qrdata[$c]['SUREG'];
		$c++;
	}
}
?>
<div id="div_fgts" class="grupoDados">
<a name="fgts"></a>
	<b>Dados de FGTS:</b>
  
	<table cellpadding=0 cellspacing=5 border=0  class="tb_dets_list">
		<colgroup><col width="150" /><col /></colgroup>
    <tr>
      <td align="right" valign="top">Utilizar FGTS:</td>
      <td align="left"  valign="top"><b><? if ($aAltPpnt["flgfgts_ppnt"]=="S"){echo "SIM";}elseif($aAltPpnt["flgfgts_ppnt"]=="N"){echo "NÃO";} ?></b>
      </td>
    </tr>
    
	<tr>
       <td width="144" align="right">Status do Imóvel:<?php $obrig; ?></td>
	   <td width="260" align="left"><b><? if($aAltPpnt["fgts"][0]["stimov_fgts"]=='1'){echo "Novo";}elseif($aAltPpnt["fgts"][0]["stimov_fgts"]=='2'){echo "Usado";}?></b></tr>
    <tr>
       <td width="144" align="right">Estado: (IBGE)<?php $obrig; ?></td>
	   <td width="260" align="left"><b><? echo $aAltPpnt["fgts"][0]["estado_fgts"];?></b></td>
</tr>
       <td width="144" align="right">Municipio: (IBGE):<?php $obrig; ?></td>
	   <td width="260" align="left"><b><? echo $aAltPpnt["fgts"][0]["municipio_fgts"];?></b></td>

</tr>
	<tr>
      <td align="right" valign="top">Num. de Contas:</td>
      <td align="left"  valign="top"><b><? echo $aAltPpnt["fgts"][0]["qtcontas"]; ?></b></td>
    </tr>
<? 
$c=1;
while($c<=$aAltPpnt["fgts"][0]["qtcontas"])
{?>	
	<tr>
      <td align="center" valign="top" colspan="4"><b>Conta FGTS <? echo $c;?></b></td>
    </tr>
	<tr>
      <td align="left" valign="top" colspan="4"><b><hr></b></td>
    </tr>
    <tr>
      <td width="144" align="right" valign="top">Trabalhador:</td>
      <td align="left"  valign="top"><b><? echo $aAltPpnt["fgts"][$c]["nometrab_fgts"]; ?></b></td>
      <td width="136" align="right" valign="top">Dt. Nasc.:</td>
      <td width="192" align="left"  valign="top"><b><?=$utils->formataDataBRA($aAltPpnt["fgts"][$c]["dtnasctrab_fgts"]); ?></b></td>
    </tr>
    <tr>
      <td align="right" valign="top">PIS/PASEP:</td>
      <td align="left"  valign="top"><b><? echo $aAltPpnt["fgts"][$c]["pis_fgts"]; ?></b></td>
	  <td align="right" valign="top">Situação da Conta:</td>
	  <td align="left"><b> <?php if ($aAltPpnt["fgts"][$c]["sitconta_fgts"]=='A') {echo "Ativa";} ?>
	  					<?php if ($aAltPpnt["fgts"][$c]["sitconta_fgts"]=='I') {echo "Inativa";} ?> 				 
						<?php if ($aAltPpnt["fgts"][$c]["sitconta_fgts"]=='P') {echo "Plano Econômico";} ?>
		  </b></td>
    </tr>
    <tr>
      <td align="right" valign="top">Conta Empregador:</td>
      <td align="left"  valign="top"><b><? echo $aAltPpnt["fgts"][$c]["contaemp_fgts"]; ?></b></td>
      <td align="right" valign="top">Conta Trabalhador:</td>
      <td align="left"  valign="top"><b><? echo $aAltPpnt["fgts"][$c]["contatrab_fgts"]; ?></b></td>
    </tr>
    <tr>
      <td align="right" valign="top">Valor a ser debitado:</td>
      <td align="left"  valign="top"><b><? echo $utils->formataMoeda($aAltPpnt["fgts"][$c]["valordeb_fgts"]); ?></b></td>
      <td align="right" valign="top">Base Conta FGTS:</td>
      <td align="left"  valign="top"><b><? echo $aAltPpnt["fgts"][$c]["baseconta_fgts"]; ?></b></td>
    </tr><?
$c++;
}
	?>
    <tr>
      <td align="right" valign="top" colspan="4"><b><hr></b></td>
    </tr>
    <tr>
      <td align="right" valign="top">Valor da Operação:</td>
      <td align="left"  valign="top" style="font-weight:bold; color:#600;">R$ <?=$aAltPpnt["fgts"][0]["valoper_fgts"];?></td>
      <input type="hidden" name="valoper_fgts" id="valoper_fgts" value="<?=$aAltPpnt["fgts"][0]["valoper_fgts"];?>">
	</tr>
  </table>
</div>
<div style="height:10px;"></div>