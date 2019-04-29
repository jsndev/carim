<?
include "class/dbclasses.class.php";
include "lib/header.inc.php";

if($_POST)
{	
	$aAltPpnt["flgfgts_ppnt"]=						$_POST['flgfgts_ppnt'];
	$aAltPpnt["fgts"][0]["stimov_fgts"] = 			$_POST['stimov_fgts'];
	$aAltPpnt["fgts"][0]["municipio_fgts"] = 		$_POST['municipio_fgts'];
	$aAltPpnt["fgts"][0]["codmunicipio_fgts"] = 	$_POST['codmunicipio_fgts'];
	$aAltPpnt["fgts"][0]["estado_fgts"]=			$_POST['estado_fgts'];
	$aAltPpnt["fgts"][0]["qtcontas"]=				$_POST['qtcontas_fgts'];
	$c=1;
	while($c<=$aAltPpnt["fgts"][0]["qtcontas"])
	{
		$aAltPpnt["fgts"][$c]["pis_fgts"]=			$_POST['pis_fgts'.$c];
		$aAltPpnt["fgts"][$c]["sitconta_fgts"]=		$_POST['sitconta_fgts'.$c];
		$aAltPpnt["fgts"][$c]["contaemp_fgts"]=		$_POST['contaemp_fgts'.$c];
		$aAltPpnt["fgts"][$c]["contatrab_fgts"]=	$_POST['contatrab_fgts'.$c];
		$aAltPpnt["fgts"][$c]["valordeb_fgts"]=		$_POST['valordeb_fgts'.$c];
		$aAltPpnt["fgts"][$c]["baseconta_fgts"]=	$_POST['baseconta_fgts'.$c];
		$c++;
	}
	$aAltPpnt["fgts"][0]["valoper_fgts"]= 			$_POST['valoper_fgts'];
}
?>
<script language="JavaScript" src="./js/proposta_bl_proponente.js"></script>
<script>
function atualfgts(_cod,_acao){
	document.getElementById('frm_cod_ppnt').value = _cod;
	document.getElementById('acaoProposta').value = _acao;
	document.getElementById('proposta').action += '#fgts';
	document.getElementById('proposta').submit();
}
</script>
<form action="" name="proposta" id="proposta">
<div id="div_fgts" class="grupoDados">
<a name="fgts"></a>
	<b>Dados de FGTS:</b>
	<table cellpadding=0 cellspacing=5 border=0>
		<colgroup><col width="150" /><col /></colgroup>
    <tr>
      <td align="right" valign="top">Proponente FGTS:<? $utils->obrig('flgfgts_ppnt'); ?></td>
      <td align="left"  valign="top">
			  <?
			  	$display_dets_fgts = ($aAltPpnt["flgfgts_ppnt"]=='S')?'':'display:none;';
			  	foreach ($listas->getListaSN() as $k=>$v){
          	$checked = ($aAltPpnt["flgfgts_ppnt"]==$k)?'checked':'';
          	print '<input type="radio" class="rd" name="flgfgts_ppnt" id="flgfgts_ppnt" value="'.$k.'" '.$checked.' onclick="trocouTemFgts(this);" /> '.$v.' &nbsp;&nbsp;';
			  	}
			  ?>
      </td>
    </tr>
  </table>

  <?
    if( !is_array($aAltPpnt["fgts"])) $aAltPpnt["fgts"] = array(0,1,2,3);
  ?>
  
	<table cellpadding=0 cellspacing=5 border=0 id="tb_dets_fgts" style="<?=$display_dets_fgts;?>">
		<colgroup><col width="150" /><col /></colgroup>
    <tr>
       <td width="163" align="right">Status do Imóvel:<?php $obrig; ?></td>
	   <td width="155" align="left">
	   	<?php $tipo_simulador=1; ?>
	     <input type="radio" class="rd" name="stimov_fgts" id="stimov_fgts" value="1" <?php if($aAltPpnt["fgts"][0]["stimov_fgts"]=='1'){echo "checked";}?> onClick="" > Novo &nbsp;&nbsp; 
         <input type="radio" class="rd" name="stimov_fgts" id="stimov_fgts" value="2" <?php if($aAltPpnt["fgts"][0]["stimov_fgts"]=='2'){echo "checked";}?>  onClick="" > Usado<br></tr>
    <tr>
       <td width="163" align="right">Estado: (IBGE)<?php $obrig; ?></td>
	   <td width="155" align="left">
	   	<?php $tipo_simulador=1; ?>
          	      <select name="estado_fgts" id="estado_fgts" onChange="atualfgts('<?=$registroPpnt["cod_proponente"];?>','<?=$crypt->encrypt('altPpnt');?>');">
	        <option value="0" >-Selecione-</option>
	        <?
	        	foreach($listas->getListaUF() as $k=>$v){
	    		  	$selected = ($aAltPpnt["fgts"][0]["estado_fgts"]==$v['cod_uf'])?'selected':'';
	     		    print '<option value="'.$v['cod_uf'].'" '.$selected.'>'.$v['nome_uf'].'</option>';
	        	}
	        ?>
	      </select>
</tr>
       <td width="163" align="right">Municipio: (IBGE):<?php $obrig; ?></td>
	   <td width="440" align="left">
	   		  <select name="municipio_fgts" onChange="">
				<option value="0">-Selecione-</option><?php
			$query = "SELECT cod_municipio, municipio FROM ibge WHERE uf='".$aAltPpnt["fgts"][0]["estado_fgts"]."'";
			$result =mysql_query($query);
			if (mysql_num_rows($result) > 0)
			{
				while($linhas = mysql_fetch_array($result, MYSQL_ASSOC))
				{
						$selected='';
						if($aAltPpnt["fgts"][0]["municipio_fgts"]==$linhas[municipio]){$aAltPpnt["fgts"][0]["codmunicipio_fgts"]=$linhas[cod_municipio];$selected="selected";}?>
						<option <?php echo $selected;?> value="<?php echo $linhas[municipio] ?>"><?php echo $linhas[municipio]?></option><?php
						$reg++;
				}
			}?>
			</select>
			<input type="hidden" name="codmunicipio_fgts" id="codmunicipio_fgts" value="<? echo $aAltPpnt["fgts"][0]["codmunicipio_fgts"];?>">
</tr>
	<tr>
	
	<tr>
      <td align="right" valign="top">Num. de Contas:</td>
      <td align="left"  valign="top"><input type="text" style="width:40px;" name="qtcontas_fgts" id="qtcontas_fgts" value="<? echo $aAltPpnt["fgts"][0]["qtcontas"]; ?>" onBlur="atualfgts('<?=$registroPpnt["cod_proponente"];?>','<?=$crypt->encrypt('altPpnt');?>');" maxlength="70"></td>
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
      <td align="right" valign="top">PIS/PASEP:</td>
      <td align="left"  valign="top"><input type="text" style="width:100px;" name="pis_fgts<?=$c;?>" id="pis_fgts<?=$c;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraPIS(this,event);" value="<? if($aAltPpnt["fgts"][$c]["pis_fgts"]==''){echo $utils->formataPIS($aAltPpnt["fgts"][1]["pis_fgts"]);}else{echo $utils->formataPIS($aAltPpnt["fgts"][$c]["pis_fgts"]);} ?>" maxlength="14"></td>
	  <td align="right" valign="top">Situação da Conta:</td>
	  <td align="left">
          <select name="sitconta_fgts<?=$c;?>" id="sitconta_fgts<?=$c;?>" >
		        <option  value="0">-Selecione-</option>
				<option <?php if ($aAltPpnt["fgts"][$c]["sitconta_fgts"]=='A') {echo "selected";} ?> value="A" >Ativa</option>
				<option <?php if ($aAltPpnt["fgts"][$c]["sitconta_fgts"]=='I') {echo "selected";} ?> value="I">Inativa</option>
				<option <?php if ($aAltPpnt["fgts"][$c]["sitconta_fgts"]=='P') {echo "selected";} ?> value="P">Plano Econômico</option>
		  </select></td>
    </tr>
    <tr>
      <td align="right" valign="top">Conta Empregador:</td>
      <td align="left"  valign="top"><input type="text" style="width:100px;" name="contaemp_fgts<?=$c;?>" id="contaemp_fgts<?=$c;?>" value="<? echo $aAltPpnt["fgts"][$c]["contaemp_fgts"]; ?>" maxlength="14"></td>
      <td align="right" valign="top">Conta Trabalhador:</td>
      <td align="left"  valign="top"><input type="text" style="width:100px;" name="contatrab_fgts<?=$c;?>" id="contatrab_fgts<?=$c;?>" value="<? echo $aAltPpnt["fgts"][$c]["contatrab_fgts"]; ?>" maxlength="11"></td>
    </tr>
    <tr>
      <td align="right" valign="top">Valor a ser debitado:</td>
      <td align="left"  valign="top"><input type="text" style="width:80px;" name="valordeb_fgts<? echo $c;?>" id="valordeb_fgts<? echo $c;?>" value="<? echo $aAltPpnt["fgts"][$c]["valordeb_fgts"]; ?>" maxlength="20" onblur="atualfgts('<?=$registroPpnt["cod_proponente"];?>','<?=$crypt->encrypt('altPpnt');?>');"></td>
      <td align="right" valign="top">Base Conta FGTS:</td>
      <td align="left"  valign="top"><input type="text" style="width:100px;" name="baseconta_fgts<?=$c;?>" id="baseconta_fgts<?=$c;?>" value="<? echo $aAltPpnt["fgts"][$c]["baseconta_fgts"]; ?>" maxlength="2"> (SUREG)</td>
    </tr><?
$c++;
}
function calculofgts($qtcontas)
{
	$c=1;
	$somafgts='';
	while($c<=$qtcontas)
	{
		$somafgts=$_POST["valordeb_fgts".$c]+$somafgts;
		$c++;
	}
	return $somafgts;
	
}
	?>
    <tr>
      <td align="right" valign="top" colspan="4"><b><hr></b></td>
    </tr>
    <tr>
      <td align="right" valign="top">Valor da Operação:</td>
      <td align="left"  valign="top" style="font-weight:bold; color:#600;">R$ <?=$utils->formataFloat(@calculofgts($aAltPpnt["fgts"][0]["qtcontas"]),2);?></td>
      <input type="hidden" name="valoper_fgts" id="valoper_fgts" value="<?=$aAltPpnt["fgts"][0]["valoper_fgts"];?>">
	</tr>
  </table>
</div>
	<div style="text-align:right; margin-top:10px;">
	    <input type="hidden" name="acaoFgts" id="acaoFgts" value="">
		<img src="images/buttons/bt_salvar.gif"   id="bt_save_fgts"   alt="Salvar Fgts" class="im" onClick="return salvarFgts();" />
	</div>
<?

function inserefgts($usuario, $flag, $uf, $codmun, $mun, $stimov, $valoper, $qt)
{
	$query="INSERT INTO fgts ( COD_USUA, FLAGUTILIZACAO, UFIBGE, CODMUNIBGE, NOMEMUNIBGE, STATUSIMOV, VALOPERACAO, QTCONTAS, TIPOFGTS) VALUES ( '".$usuario."', '".$flag."', '".$uf."', '".$codmun."', '".$mun."', '".$stimov."', '".$valoper."', '".$qt."', 'proponente') 
	";
	mysql_query($query);
}

?>