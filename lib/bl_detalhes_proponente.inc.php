<input type="hidden" name="cod_ppnt_<?=$ippnt;?>"       id="cod_ppnt_<?=$ippnt;?>"       value="<?=$registroPpnt["cod_proponente"];?>" />
<input type="hidden" name="nome_ppnt_<?=$ippnt;?>"      id="nome_ppnt_<?=$ippnt;?>"      value="<?=$registroPpnt["usuario"][0]["nome_usua"];?>" />
<? if(FLG_PREVI){ ?>
	<input type="hidden" name="vlcompra_ppnt_<?=$ippnt;?>"  id="vlcompra_ppnt_<?=$ippnt;?>"  value="<?=$utils->formataFloat($registroPpnt["vlcompra_ppnt"],2);?>" />
	<input type="hidden" name="vlentrada_ppnt_<?=$ippnt;?>" id="vlentrada_ppnt_<?=$ippnt;?>" value="<?=$utils->formataFloat($registroPpnt["vlentrada_ppnt"],2);?>" />
	<input type="hidden" name="vlsinal_ppnt_<?=$ippnt;?>"   id="vlsinal_ppnt_<?=$ippnt;?>"   value="<?=$utils->formataFloat($registroPpnt["vlsinal_ppnt"],2);?>" />
<? }
$db->query="Select * from proponente where cod_ppst='".$aProposta["cod_ppst"]."'";
$db->query();
$cod_usua=$db->qrdata[0]['COD_PROPONENTE'];
$db->query="Select * from usuario where cod_usua='".$cod_usua."'";
$db->query();
$id_lstn=$db->qrdata[0]['ID_LSTN'];
$participante=$id_lstn=$db->qrdata[0]['ID_LSTN'];
$db->query="Select * from retornofgts where participante='".$id_lstn."'";
$db->query();
$ct=$db->qrdata[0]['nrconta'];
$ag=$db->qrdata[0]['nragencia'];
$renda=$db->qrdata[0]['rendabruta'];
$renda = $utils->db2moeda($renda);
?>

<table cellpadding=0 cellspacing=5 border=0 class="tb_dets_list">
	<colgroup><col width="180" /><col /></colgroup>
	<?
	if($ct!='' && $ag!='')
	{
	?>
	<tr>
	  <td align="right" valign="top">Ag�ncia:</td><td align="left"><b><?=$ag;?></b></td>
	</tr>
	<tr>
	  <td align="right" valign="top">Conta:</td><td align="left"><b><?=$ct;?></b></td>
	</tr>
	<tr>
	  <td align="right" valign="top">Renda:</td><td align="left"><b><?=$renda;?></b></td>
	</tr>
	<?
	}
	?>
	<tr>
	  <td align="right" valign="top">E-Mail:</td><td align="left"><b><?=$registroPpnt["usuario"][0]["email_usua"];?></b></td>
	</tr>
	  <tr>
	<td align="right" valign="top">RG:</td>
	<td align="left"  valign="top"><b><?php echo $registroPpnt["nrrg_ppnt"];?>&nbsp;&nbsp;&nbsp;&nbsp;</b>
		Emiss�o: <b><?php echo $utils->formataDataBRA($registroPpnt["dtrg_ppnt"]);?>&nbsp;&nbsp;&nbsp;&nbsp;</b>
		�rg�o Emissor: <b><?php echo $registroPpnt["orgrg_ppnt"];?></b>
	</td>
 </tr>

	<tr>
	  <td align="right" valign="top">Data Nasc:</td><td align="left"><b><?=$utils->formataDataBRA($registroPpnt["dtnascimento_ppnt"]);?> <?=$utils->formataIdade($utils->formataDataBRA($registroPpnt["dtnascimento_ppnt"]));?></b></td>
	</tr>
    <tr>
      <td align="right" valign="top">Sexo:</td>
      <td align="left"  valign="top">
			  <?php if($registroPpnt["sexo_ppnt"]=='F')echo "<b>Feminino</b>";
			  		if($registroPpnt["sexo_ppnt"]=='M')echo "<b>Masculino</b>";
			  ?>
			</td>
    </tr>
    <tr>
      <td align="right" valign="top">Nacionalidade:</td>
      <td align="left"  valign="top">
	  <?php
	  $db->query="select nome_pais from pais where cod_pais='". $registroPpnt["nacional_ppnt"]."'";
	  $db->query();
	  echo "<b>".$db->qrdata[0]['nome_pais']."</b>";
        ?>
        </select>
      </td>
    </tr>
	<tr>
	  <td align="right" valign="top">Est Civil:</td><td align="left"><b><?=$registroPpnt["estadocivil"][0]["desc_estciv"];?></b></td>
	</tr>
	<?php 
	if ($registroPpnt["cod_estciv"]!=2){ ?>
	  <tr>
    <td align="right" valign="top">Vive em Uni�o Est�vel:</td>
    <td align="left"  valign="top"><?php 	if($registroPpnt["flguniest_ppnt"]=='S') echo "<b>Sim</b>"; else echo "<b>N�o</b>";
			  ?>
</td>
  </tr>
  	  <tr>
    <td align="right" valign="top">Possui Escritura:</td>
    <td align="left"  valign="top"><?php 	if($registroPpnt["flgescritura_ppnt"]=='S') echo "<b>Sim</b>"; else echo "<b>N�o</b>";
			  ?>
</td>
  </tr>

	<?php
	}
	 if($registroPpnt["cod_estciv"]==2){ ?>
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
		<?
	}
	 if(($registroPpnt["cod_estciv"]==2 && ($registroPpnt["conjuge"][0]["regimebens_ppcj"]==1 || $registroPpnt["conjuge"][0]["regimebens_ppcj"]==3 || $registroPpnt["conjuge"][0]["regimebens_ppcj"]==5)) || $registroPpnt["flgescritura_ppnt"]=='S'){ ?>
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
		  <td align="right" valign="top">N�mero do Registro:</td><td align="left"><b><?=$registroPpnt["conjugepacto"][0]["numeroregistro_pcpa"];?></b></td>
		</tr>
		<tr>
			<td align="right" valign="top">H� Bens:</td>
			<td align="left"  valign="top">			  
			<?php if($registroPpnt["conjugepacto"][0]["habens_pcpa"]=='S') echo "<b>Sim</b>";
				  if($registroPpnt["conjugepacto"][0]["habens_pcpa"]=='N') echo "<b>N�o</b>";
				  ?>
			</td>
		</tr>
		<?php
			if($registroPpnt["conjugepacto"][0]["habens_pcpa"]=='S'){
			?>
				<tr>
					<td align="right" valign="top">Cartorio:</td>
					<td align="left"  valign="top"><b><?php echo $registroPpnt["conjugepacto"][0]["habenscart_pcpa"];?>� Cart�rio de Registro de Im�veis de <?php echo $aAltPpnt["conjugepacto"][0]["habensloccart_pcpa"];?></b></tr>
				<tr>
					<td align="right" valign="top">Data de Registro:</td>
					<td align="left"  valign="top"><b><?php echo $utils->formataDataBRA($registroPpnt["conjugepacto"][0]["habensdata_pcpa"]);?></b></td>
				</tr>
		<?   }
		   } ?>
	<tr>
	  <td align="right" valign="top">Logradouro:</td><td align="left"><b><?=$registroPpnt["logradouro"][0]["desc_logr"];?></b></td>
	</tr>
	<tr>
	  <td align="right" valign="top">Endere�o:</td><td align="left"><b><?=$registroPpnt["endereco_ppnt"];?></b></td>
	</tr>
	<tr>
	  <td align="right" valign="top">N�mero:</td><td align="left"><b><?=$registroPpnt["nrendereco_ppnt"];?></b></td>
	</tr>
	<tr>
	  <td align="right" valign="top">Complemento:</td><td align="left"><b><?=$registroPpnt["cpendereco_ppnt"];?></b></td>
	</tr>
	<tr>
	  <td align="right" valign="top">Bairro (Previ):</td><td align="left"><b><?=$registroPpnt["bairro"][0]["nome_bairro"];?></b></td>
	</tr>
	<tr>
	  <td align="right" valign="top">Bairro (Contrato):</td><td align="left"><b><?=$registroPpnt["bairro_ppnt"];?></b></td>
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
    

	<tr>
	  <td align="right" valign="top">Profiss�o:</td><td align="left"><b><?=$registroPpnt["profissao_ppnt"];?></b></td>
	</tr>
		<tr align="right" valign="top">
		<td align="right" valign="top">Procurador:</td>
		<td align="left"  valign="top"><b>			  
		<?php
			  	$display_dets_flgproc = ($registroPpnt["flgproc_ppnt"]=='S')?'':'display:none;';
          	if($registroPpnt["flgproc_ppnt"]=='S') echo "Sim"; else echo "N�o";
			  ?></b>
		</td>
	</tr>
	<tr id="tb_dets_proc" style="<?php echo $display_dets_flgproc;?>">
		<td align="right" valign="top">Dados do Procurador:</td>
		<td align="left"  valign="top"><b><?php echo $registroPpnt["proc_ppnt"];?></b></td>
	</tr>

</table>
<?
?>
<div id="div_exig" class="grupoDados" style="clear:both;">
<a name="exigencia"></a>
<? if($aProposta["situacao_ppst"] >= 3 ){
	$just_display = ($cLOGIN->iLEVEL_USUA == TPUSER_PROPONENTE)?false:true;
	$aAltPpnt = $registroPpnt;
	?><div id="ckls_ppnt">
	<? include('bl_ckls_proponente.inc.php'); ?></div><? 
} ?>
	</div><? /*
<div id="div_empresa" class="grupoDados" style="clear:both;">
	<b>Dados Profissionais</b>
 	<table cellpadding=0 cellspacing=5 border=0 class="tb_dets_list">
 		<colgroup><col width="180" /><col /></colgroup>
		<tr>
		  <td align="right" valign="top">Empresa:</td><td align="left"><b><?=$registroPpnt["profissao"][0]["empresa_pppf"];?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Data de Admiss�o:</td><td align="left"><b><?=$utils->formataDataBRA($registroPpnt["profissao"][0]["dtadmissao_pppf"]);?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Endere�o:</td><td align="left"><b><?=$registroPpnt["profissao"][0]["enderecoemp_pppf"];?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">N�mero:</td><td align="left"><b><?=$registroPpnt["profissao"][0]["numeroemp_pppf"];?></b></td>
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
		  <td align="right" valign="top">Sal�rio:</td><td align="left"><b>R$ <?=$utils->formataMoeda($registroPpnt["profissao"][0]["salario_pppf"]);?></b></td>
		</tr>
	</table>
</div>
*/
 if($registroPpnt["cod_estciv"]==2 || $registroPpnt["cod_estciv"]==99 || $registroPpnt["flguniest_ppnt"]=='S'){ ?>
<div id="div_conjuje" class="grupoDados">
	<b>Dados do C�njuge</b>
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
		  	Emiss�o: <b><?=$utils->formataDataBRA($registroPpnt["conjuge"][0]["dtrg_ppcj"]);?></b> &nbsp;&nbsp;
		  	�rg�o Emissor: <b><?=$registroPpnt["conjuge"][0]["orgrg_ppcj"];?></b>
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
	<? if($registroPpnt["conjuge"][0]["flgtrabalha_ppcj"]=='S'){ /* ?>
		<tr>
		  <td align="left" valign="top" colspan="2"><b>Dados Profissionais do C�njuge</b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Empresa:</td><td align="left"><b><?=$registroPpnt["conjuge"][0]["empresa_ppcj"];?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Data de Admiss�o:</td><td align="left"><b><?=$utils->formataDataBRA($registroPpnt["conjuge"][0]["dtadmissaoemp_ppcj"]);?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">Endere�o:</td><td align="left"><b><?=$registroPpnt["conjuge"][0]["enderecoemp_ppcj"];?></b></td>
		</tr>
		<tr>
		  <td align="right" valign="top">N�mero:</td><td align="left"><b><?=$registroPpnt["conjuge"][0]["numeroemp_ppcj"];?></b></td>
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
		</tr>*/?>
		<tr>
		  <td align="right" valign="top">Profiss�o:</td><td align="left"><b><?=$registroPpnt["conjuge"][0]["cargoemp_ppcj"];?></b></td>
		</tr><? /*
		<tr>
		  <td align="right" valign="top">Sal�rio:</td><td align="left"><b>R$ <?=$utils->formataMoeda($registroPpnt["conjuge"][0]["salarioemp_ppcj"]);?></b></td>
		</tr>
		<? */} ?>
 	</table>
<div id="div_exig" class="grupoDados" style="clear:both;">
<a name="exigencia"></a>

	<? if($aProposta["situacao_ppst"] >= 3 ){ 
		$aAltPpnt = $registroPpnt;
		?><div id="ckls_ppcj"><? include('bl_ckls_conjuge.inc.php'); ?></div><?
	} ?>
</div>	
</div>
<? } ?>

<div id="div_devedor" class="grupoDados">
	<b>Devedor Solid�rio</b>
 	<table cellpadding=0 cellspacing=5 border=0 class="tb_dets_list">
 		<colgroup><col width="180" /><col /></colgroup>
    <tr>
      <td align="right" valign="top">Possui Devedor Solid�rio:</td>
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
		  <td align="right" valign="top">Endere�o:</td><td align="left"><b><?=$registroPpnt["devsol"][0]["endereco_devsol"];?></b></td>
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
		<td colspan="4" ><font color="#993300">
			<?  
			$db->query="Select * from retornoerro where participante='".$participante."'";
			$db->query();
			if($db->qrcount>0)
			{
				echo "<b><br><br>FGTS RECUSADO<br><br></b>";
				$e=1;
				$f=$db->qrcount;
				
				while($e<=$f)
				{
					$cod[$e]=$db->qrdata[$e-1]['erro'];
					$query="Select * from erros where cod_erro='".$cod[$e]."'";
					$result=mysql_query($query);
					$reg=mysql_fetch_array($result,MYSQL_ASSOC);
					echo "<b>C�DIGO DE RECUSA:</b> ".$cod[$e]." (".$reg['MSG_ERRO'].")<br>"; 
					$e++;
				}
				echo "<br><br>";
			}
			?></font>
		</td>
	</tr>
	<tr>
      <td align="right" valign="top">Utiliza FGTS:</td>
      <td align="left"  valign="top"><b><? if ($aAltPpnt["flgfgts_ppnt"]=="S"){echo "SIM";}else{echo "N�O";} ?></b>
      </td>
    </tr>
   <?php
   if($aAltPpnt["flgfgts_ppnt"]=="S"){
   ?> 
	<tr>
       <td width="144" align="right">Status do Im�vel:<?php $obrig; ?></td>
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
	  <td align="right" valign="top">Situa��o da Conta:</td>
	  <td align="left"><b> <?php if ($aAltPpnt["fgts"][$c]["sitconta_fgts"]=='A') {echo "Ativa";} ?>
	  					<?php if ($aAltPpnt["fgts"][$c]["sitconta_fgts"]=='I') {echo "Inativa";} ?> 				 
						<?php if ($aAltPpnt["fgts"][$c]["sitconta_fgts"]=='P') {echo "Plano Econ�mico";} ?>
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
      <td align="right" valign="top">Valor da Opera��o:</td>
      <td align="left"  valign="top" style="font-weight:bold; color:#600;">R$ <?= $utils->formataMoeda($vloperfgts);?></td>
      <input type="hidden" name="valoper_fgts" id="valoper_fgts" value="<?=$vloperfgts;?>">
	</tr>
<?php
}
?>
  </table>
</div>
<div style="height:10px;"></div>
<? if($aProposta["situacao_ppst"] >= 3 ){
	$just_display = ($cLOGIN->iLEVEL_USUA == TPUSER_PROPONENTE)?false:true;
	$aAltPpnt = $registroPpnt;
	?><div id="ckls_ppnt">
	<? include('bl_ckls_fgts.inc.php'); ?></div><? 
} ?>
