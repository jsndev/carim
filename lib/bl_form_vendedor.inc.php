<?php
	$indiceVendedor = 0;
	$aAltVend = array();
	if($acaoProposta=='altVend'||$acaoProposta=='dtsVend'||$acaoProposta=='altSocio'||$acaoProposta=='dtsSocio'||$acaoProposta=='delSocio'||$acaoProposta=='saveSocio' || $vend_addexig==1){
		if (is_array($aProposta["vendedores"]) && @count($aProposta["vendedores"]) > 0) {
			foreach($aProposta["vendedores"] as $kVend=>$vVend){
				if($vVend["cod_vend"] == $_POST["frm_cod_vend"]){
					$aAltVend = $vVend;
					$indiceVendedor = $kVend;
				}
			}
		}
	}
?>
<table cellpadding=0 cellspacing=5 border=0 id="tbEditVend">
	<tr>
    <td align="right" valign="top">Tipo:<?php $utils->obrig('vend_tipo'); ?></td>
    <td align="left"  valign="top"><b>
      <input type="radio" class="rd" name="vend_tipo" id="vend_tipo_1" value="1" <?php echo ($aAltVend["tipo_vend"]=='1')?'checked':'';?> onClick="atualizaFormVend(1);" > Pessoa Física &nbsp;&nbsp;
      <input type="radio" class="rd" name="vend_tipo" id="vend_tipo_2" value="2" <?php echo ($aAltVend["tipo_vend"]=='2')?'checked':'';?> onClick="atualizaFormVend(2);" > Pessoa Jurídica<br></b>
      <?php
      $display_dados_pf = ($aAltVend["tipo_vend"]!='1')?'display:none;':'';
      $display_dados_pj = ($aAltVend["tipo_vend"]!='2')?'display:none;':'';
      ?>
    </td>
  </tr>
  <tr>
    <td align="right" valign="top">Nome do Vendedor:<?php $utils->obrig('vend_nome'); ?></td>
    <td align="left"  valign="top"><input type="text" style="width:300px;" name="vend_nome" id="vend_nome" value="<?php echo $aAltVend["nome_vend"];?>" maxlength="70"></td>
  </tr>
  <tr>
    <td align="right" valign="top">Nome do Vendedor Abrev:<?php $utils->obrig('vend_nabrev'); ?></td>
    <td align="left"  valign="top"><input type="text" style="width:150px;" name="vend_nabrev" id="vend_nabrev" value="<?php echo $aAltVend["nick_vend"];?>" maxlength="15"></td>
  </tr>
  <?php
  	if(@count($aProposta["vendedores"]) > 1){
  		$display_porc_vend = '';
  		$valor_porc_vend = $utils->formataFloat($aAltVend["percentualvenda_vend"],4);
  	}else{
  		$display_porc_vend = 'display:none;';
  		$valor_porc_vend = '100,00';
  	}

  ?>
  <tr style="<?php echo $display_porc_vend;?>">
    <td align="right" valign="top">Porcentagem da Venda (%):<?php $utils->obrig('vend_porcentagem'); ?></td>
    <td align="left"  valign="top"><input type="text" style="width:80px;" name="vend_porcentagem" id="vend_porcentagem" value="<?php echo $valor_porc_vend;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraMoeda(this,event,null,4);" maxlength="8" /></td>
  </tr>
</table>

<div id="div_pf" style="<?php echo $display_dados_pf;?>">
  <table cellpadding=0 cellspacing=5 border=0>
    <tr>
      <td align="right" valign="top">CPF:<?php $utils->obrig('vend_cpf'); ?></td>
      <td align="left"  valign="top"><input type="text" style="width:150px;" name="vend_cpf" id="vend_cpf" value="<?php echo $utils->formataCPF($aAltVend["vendfis"][0]["cpf_vfisica"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraCPF(this,event);" maxlength="14"></td>
    </tr>
    <tr>
      <td align="right" valign="top">Sexo:<?php $utils->obrig('vend_sexo'); ?></td>
      <td align="left"  valign="top">
			  <?php
			  	foreach ($listas->getListaSexo() as $k=>$v){
          	$checked = ($aAltVend["vendfis"][0]["sexo_vfisica"]==$k)?'checked':'';
          	print '<input type="radio" class="rd" name="vend_sexo" id="vend_sexo" value="'.$k.'" '.$checked.' /> '.$v.' &nbsp;&nbsp;';
			  	}
			  ?>
			</td>
    </tr>
    <tr>
      <td align="right" valign="top">Data Nasc:<?php $utils->obrig('vend_nasc'); ?></td>
      <td align="left"  valign="top"><input type="text" style="width:80px;" name="vend_nasc" id="vend_nasc" value="<?php echo $utils->formataDataBRA($aAltVend["vendfis"][0]["dtnascimento_vfisica"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10"></td>
    </tr>
    <tr>
      <td align="right" valign="top">Nacionalidade:<?php $utils->obrig('vend_nacion'); ?></td>
      <td align="left"  valign="top">
				<select name="vend_nacion" id="vend_nacion">
					<option value="0" >-Selecione-</option>
					<?php
          	foreach($listas->getListaPais() as $k=>$v){
      		  	$selected = ($aAltVend["vendfis"][0]["cod_pais"]==$v['cod_pais'])?'selected':'';
       		    print '<option value="'.$v['cod_pais'].'" '.$selected.'>'.$v['nome_pais'].'</option>';
          	}
          ?>
        </select>
      </td>
    </tr>
    <tr>
      <td align="right" valign="top">Naturalidade:<?php $utils->obrig('vend_natural'); ?></td>
      <td align="left"  valign="top"><input type="text" style="width:150px;" name="vend_natural" id="vend_natural" value="<?php echo $aAltVend["vendfis"][0]["natur_vfisica"];?>" maxlength="30"></td>
    </tr>
    <tr>
      <td align="right" valign="top">Doc de Identif:<?php $utils->obrig('vend_tpdoc'); ?></td>
      <td align="left"  valign="top">
				<select name="vend_tpdoc" id="vend_tpdoc">
					<option value="0" >-Selecione-</option>
					<?php
          	foreach($listas->getListaTipoDocumento() as $k=>$v){
      		  	$selected = ($aAltVend["vendfis"][0]["cod_tpdoc"]==$v['cod_tpdoc'])?'selected':'';
       		    print '<option value="'.$v['cod_tpdoc'].'" '.$selected.'>'.$v['desc_tpdoc'].'</option>';
          	}
          ?>
        </select>
      </td>
    </tr>
    <tr>
      <td align="right" valign="top">RG:<?php $utils->obrig('vend_rg'); ?></td>
      <td align="left"  valign="top">
      	<input type="text" style="width:150px;" name="vend_rg" id="vend_rg" value="<?php echo $aAltVend["vendfis"][0]["nrrg_vfisica"];?>" maxlength="20">&nbsp;
      	Emiss?o:<?php $utils->obrig('vend_dtrg'); ?>
      	<input type="text" style="width:80px;" name="vend_dtrg" id="vend_dtrg" value="<?php echo $utils->formataDataBRA($aAltVend["vendfis"][0]["dtrg_vfisica"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10">&nbsp;
      	?rg?o Emissor:<?php $utils->obrig('vend_orgrg'); ?>
      	<input type="text" style="width:80px;" name="vend_orgrg" id="vend_orgrg" value="<?php echo $aAltVend["vendfis"][0]["orgrg_vfisica"];?>" maxlength="10">
      </td>
    </tr>
    <tr>
      <td align="right" valign="top">Est Civil:<?php $utils->obrig('vend_civil'); ?></td>
      <td align="left"  valign="top">
	      <select name="vend_civil" id="vend_civil" onchange="trocouEstadoCivilVend(this);">
	        <option value="0" >-Selecione-</option>
	        <?php
	        	$display_dados_estciv_vend = 'display:none;';
	        	foreach($listas->getListaECivil() as $k=>$v){
	    		  	$selected = ($aAltVend["vendfis"][0]["cod_estciv"]==$v['cod_estciv'])?'selected':'';
	     		    print '<option value="'.$v['cod_estciv'].'" '.$selected.'>'.$v['desc_estciv'].'</option>';
	     		    $display_dados_estciv_vend   = ($aAltVend["vendfis"][0]["cod_estciv"]==2||($aAltVend["vendfis"][0]["cod_estciv"]!=2 && $aAltVend["vendfis"][0]["flguniest_vfisica"]=='S'))?'':'display:none;';
	     		    $display_dados_estciv_vend_conj = ($aAltVend["vendfis"][0]["cod_estciv"]!=2 && $aAltVend["vendfis"][0]["flguniest_vfisica"]=='S')?'':'display:none;';
					$display_dados_estciv_vend_2 = ($aAltVend["vendfis"][0]["cod_estciv"]==2)?'':'display:none;';
					$display_dados_estciv_vend_3 = ($aAltVend["vendfis"][0]["cod_estciv"]!=2 && $aAltVend["vendfis"][0]["cod_estciv"]!='')?'':'display:none;';
				}
	        ?>
	      </select>
      </td>
    </tr>
	  <tr id="tr_casam_dt_vend" style="<?php echo $display_dados_estciv_vend;?>">
	    <td align="right" valign="top">Data do Casamento:<?php $utils->obrig('vend_dtcasamento_ppcj'); ?></td>
	    <td align="left"  valign="top"><input type="text" style="width:80px;" name="vend_dtcasamento_ppcj" id="vend_dtcasamento_ppcj" value="<?php echo $utils->formataDataBRA($aAltVend["vendfisconjuge"][0]["dtcasamento_vfcj"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10"></td>
	  </tr>
	  <tr id="tr_casam_dt_aquis" style="<?php echo $display_dados_estciv_vend;?>">
	    <td align="right" valign="top">Dt. Aquisi??o do Im?vel:<?php $utils->obrig('dtaquisimov_vfisica'); ?></td>
	    <td align="left"  valign="top"><input type="text" style="width:80px;" name="dtaquisimov_vfisica" id="dtaquisimov_vfisica" value="<?php echo $utils->formataDataBRA($aAltVend["vendfis"][0]["dtaquisimov_vfisica"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10" onBlur="verificaAnuente(document.getElementById('dtaquisimov_vfisica').value,document.getElementById('vend_dtcasamento_ppcj').value)">
									   <input type="hidden" name="flganuente_vfisica" id="flganuente_vfisica" value="<?php echo $aAltVend["vendfis"][0]["flganuente_vfisica"];?>"></td>
	  </tr>
	  <tr id="tr_regime_bens_vend" style="<?php echo $display_dados_estciv_vend_2;?>">
	    <td align="right" valign="top">Regime de Bens:<?php $utils->obrig('vend_regimebens_ppcj'); ?></td>
	    <td align="left"  valign="top">
	      <select name="vend_regimebens_ppcj" id="vend_regimebens_ppcj" onchange="trocouRegimeDeBensVend(this);">
	        <option value="0" >-Selecione-</option>
	        <?php
	        	$display_dets_regime_bens_vend = 'display:none;';
	        	foreach($listas->getListaRegimeBens() as $k=>$v){
	    		  	$selected = ($aAltVend["vendfisconjuge"][0]["regimebens_vfcj"]==$k)?'selected':'';
	     		    print '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
	     		    if($aAltVend["vendfisconjuge"][0]["regimebens_vfcj"]==1 || $aAltVend["vendfisconjuge"][0]["regimebens_vfcj"]==3 || $aAltVend["vendfisconjuge"][0]["regimebens_vfcj"]==5){$display_dets_regime_bens_vend ='';}else{$display_dets_regime_bens_vend ='display:none;';}
	     		    $display_dets_regime_bens_vend = ($aAltVend["vendfis"][0]["cod_estciv"]==2)?$display_dets_regime_bens_vend:'display:none;';
	        	}
	        ?>
	      </select>
	    </td>
	  </tr>
  <tr id="tr_uniao_estavel_vend" style="<?php echo $display_dados_estciv_vend_3;?>">
    <td align="right" valign="top">Vive em União Estável:<?php $utils->obrig('flguniest_vfisica'); ?></td>
    <td align="left"  valign="top">		<?php
			  	$display_dets_flguniest_vend = ($aAltVend["vendfis"][0]["flguniest_vfisica"]=='S')?'':'display:none;';
			  	$display_dets_regime_bens_vend = ($aAltVend["vendfis"][0]["flguniest_vfisica"]=='S' || ($aAltVend["vendfisconjuge"][0]["regimebens_vfcj"]==1 || $aAltVend["vendfisconjuge"][0]["regimebens_vfcj"]==3 || $aAltVend["vendfisconjuge"][0]["regimebens_vfcj"]==5) )?'':'display:none;';
				foreach ($listas->getListaSN() as $k=>$v){
          	$checked = ($aAltVend["vendfis"][0]["flguniest_vfisica"]==$k)?'checked':'';
			print '<input type="radio" class="rd" name="flguniest_vfisica" id="flguniest_vfisica" value="'.$k.'" '.$checked.' onclick="trocouUniaoEstavelVend(this);" /> '.$v.' &nbsp;&nbsp;';
			  	}
			  ?>
</td>
  </tr>
		<tr id="tr_vend_regime_bens_data" style="<?php echo $display_dets_regime_bens_vend;?>">
			<td align="right" valign="top">Data:<?php $utils->obrig('vend_data_pcpa'); ?></td>
			<td align="left"  valign="top"><input type="text" style="width:80px;" name="vend_data_pcpa" id="vend_data_pcpa" value="<?php echo $utils->formataDataBRA($aAltVend["vendfisconjugepacto"][0]["data_vcpa"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10"></td>
		</tr>
		<tr id="tr_vend_regime_bens_lavrado" style="<?php echo $display_dets_regime_bens_vend;?>">
			<td align="right" valign="top">Lavrado no:<?php $utils->obrig('vend_locallavracao_pcpa'); ?></td>
			<td align="left"  valign="top"><input type="text" style="width:300px;" name="vend_locallavracao_pcpa" id="vend_locallavracao_pcpa" value="<?php echo $aAltVend["vendfisconjugepacto"][0]["locallavracao_vcpa"];?>" maxlength="70"></td>
		</tr>
		<tr id="tr_vend_regime_bens_livro" style="<?php echo $display_dets_regime_bens_vend;?>">
			<td align="right" valign="top">Livro:<?php $utils->obrig('vend_livro_pcpa'); ?></td>
			<td align="left"  valign="top"><input type="text" style="width:300px;" name="vend_livro_pcpa" id="vend_livro_pcpa" value="<?php echo $aAltVend["vendfisconjugepacto"][0]["livro_vcpa"];?>" maxlength="70"></td>
		</tr>
		<tr id="tr_vend_regime_bens_fls" style="<?php echo $display_dets_regime_bens_vend;?>">
			<td align="right" valign="top">Fls.:<?php $utils->obrig('vend_folha_pcpa'); ?></td>
			<td align="left"  valign="top"><input type="text" style="width:300px;" name="vend_folha_pcpa" id="vend_folha_pcpa" value="<?php echo $aAltVend["vendfisconjugepacto"][0]["folha_vcpa"];?>" maxlength="70"></td>
		</tr>
		<tr id="tr_vend_regime_bens_nreg" style="<?php echo $display_dets_regime_bens_vend;?>">
			<td align="right" valign="top">Número do Registro:<?php $utils->obrig('vend_numeroregistro_pcpa'); ?></td>
			<td align="left"  valign="top"><input type="text" style="width:300px;" name="vend_numeroregistro_pcpa" id="vend_numeroregistro_pcpa" value="<?php echo $aAltVend["vendfisconjugepacto"][0]["numeroregistro_vcpa"];?>" maxlength="70"></td>
		</tr>
    <tr>
      <td align="right" valign="top">Nome do pai:<?php $utils->obrig('vend_npai'); ?></td>
      <td align="left"  valign="top"><input type="text" style="width:300px;" name="vend_npai" id="vend_npai" value="<?php echo $aAltVend["vendfis"][0]["nomepai_vfisica"];?>" maxlength="70"></td>
    </tr>
    <tr>
      <td align="right" valign="top">Nome da mãe:<?php $utils->obrig('vend_nmae'); ?></td>
      <td align="left"  valign="top"><input type="text" style="width:300px;" name="vend_nmae" id="vend_nmae" value="<?php echo $aAltVend["vendfis"][0]["nomemae_vfisica"];?>" maxlength="70"></td>
    </tr>
    <tr>
      <td align="right" valign="top">Profissão (PREVI):<?php $utils->obrig('vend_profiss'); ?></td>
      <td align="left"  valign="top">
	      <select name="vend_profiss" id="vend_profiss">
	        <option value="0" >-Selecione-</option>
	        <?php
	        	$display_dets_regime_bens_vend = 'display:none;';
	        	foreach($listas->getListaProfissoes() as $k=>$v){
	    		  	$selected = ($aAltVend["vendfis"][0]["cod_prof"]==$v['cod_prof'])?'selected':'';
	     		    print '<option value="'.$v['cod_prof'].'" '.$selected.'>'.$v['desc_prof'].'</option>';
	        	}
	        ?>
	      </select>
      </td>
    </tr>
    <tr>
      <td align="right" valign="top">Profissão (Contrato):<?php $utils->obrig('profissao_vfisica'); ?></td>
      <td align="left"  valign="top"><input type="text" style="width:150px;" name="profissao_vfisica" id="profissao_vfisica" value="<?php echo $aAltVend["vendfis"][0]["profissao_vfisica"];?>" maxlength="255"></td>
    </tr>
    <tr>
      <td align="right" valign="top">Renda (R$):<?php $utils->obrig('vend_rendim'); ?></td>
      <td align="left"  valign="top"><input type="text" name="vend_rendim" id="vend_rendim" style="width:80px;" value="<?php echo $utils->formataMoeda($aAltVend["vendfis"][0]["vlrenda_vfisica"]);?>" maxlength="20" onKeyDown="return teclasFloat(this,event);" onKeyUp="return mascaraMoeda(this,event);" onFocus="this.select();" /></td>
    </tr>
    <tr>
      <td align="right" valign="top">Inscrição INSS:<?php $utils->obrig('vend_inss'); ?></td>
      <td align="left"  valign="top"><input type="text" style="width:150px;" name="vend_inss" id="vend_inss" value="<?php echo $aAltVend["vendfis"][0]["nrinss_vfisica"];?>" maxlength="20"></td>
    </tr>
  </table>
</div>

<div id="div_pj" style="<?php echo $display_dados_pj;?>">
  <table cellpadding=0 cellspacing=5 border=0>
    <tr>
      <td align="right" valign="top">Tipo Societ?rio:</td>
      <td align="left"  valign="top"><input type="radio" name="vend_tipo_soc" id="vend_tipo_soc" value="LTDA" <?php echo ($aAltVend["vendjur"][0]["tipo_soc_vjur"]=='LTDA')?'checked':'';?> />&nbsp;<strong>Ltda</strong>&nbsp;&nbsp;&nbsp;
      								 <input type="radio" name="vend_tipo_soc" id="vend_tipo_soc" value="SA" <?php echo ($aAltVend["vendjur"][0]["tipo_soc_vjur"]=='SA')?'checked':'';?> />&nbsp;<strong>S/A</strong>
    </tr>
    <tr>
      <td align="right" valign="top">CNPJ:<?php $utils->obrig('vend_cnpj'); ?></td>
      <td align="left"  valign="top"><input type="text" style="width:150px;" name="vend_cnpj" id="vend_cnpj" value="<?php echo $utils->formataCnpj($aAltVend["vendjur"][0]["cnpj_vjur"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraCNPJ(this,event);" maxlength="18"></td>
    </tr>
    <tr>
      <td align="right" valign="top">Isenção PIS-PASEP:<?php $utils->obrig('vend_pispasep'); ?></td>
      <td align="left"  valign="top">
			  <?php
			  	foreach ($listas->getListaSN() as $k=>$v){
          	$checked = ($aAltVend["vendjur"][0]["isenpis_vjur"]==$k)?'checked':'';
          	print '<input type="radio" class="rd" name="vend_pispasep" id="vend_pispasep" value="'.$k.'" '.$checked.' /> '.$v.' &nbsp;&nbsp;';
			  	}
			  ?>
      </td>
    </tr>
    <tr>
      <td align="right" valign="top">Isenção COFINS:<?php $utils->obrig('vend_cofins'); ?></td>
      <td align="left"  valign="top">
			  <?php
			  	foreach ($listas->getListaSN() as $k=>$v){
          	$checked = ($aAltVend["vendjur"][0]["isencofins_vjur"]==$k)?'checked':'';
          	print '<input type="radio" class="rd" name="vend_cofins" id="vend_cofins" value="'.$k.'" '.$checked.' /> '.$v.' &nbsp;&nbsp;';
			  	}
			  ?>
      </td>
    </tr>
    <tr>
      <td align="right" valign="top">Isenção CSLL:<?php $utils->obrig('vend_csll'); ?></td>
      <td align="left"  valign="top">
			  <?php
			  	foreach ($listas->getListaSN() as $k=>$v){
          	$checked = ($aAltVend["vendjur"][0]["isencsll_vjur"]==$k)?'checked':'';
          	print '<input type="radio" class="rd" name="vend_csll" id="vend_csll" value="'.$k.'" '.$checked.' /> '.$v.' &nbsp;&nbsp;';
			  	}
			  ?>
      </td>
    </tr>
    <tr>
      <td align="right" valign="top">Atividade Econômica:<?php $utils->obrig('vend_cnae'); ?></td>
      <td align="left"  valign="top">
	      <select name="vend_cnae" id="vend_cnae">
	        <option value="0" >-Selecione-</option>
	        <?php
	        	$display_dets_regime_bens_vend = 'display:none;';
	        	foreach($listas->getListaAtivEcon() as $k=>$v){
	    		  	$selected = ($aAltVend["vendjur"][0]["cod_cnae"]==$v['cod_cnae'])?'selected':'';
	     		    print '<option value="'.$v['cod_cnae'].'" '.$selected.'>'.$v['desc_cnae'].'</option>';
	        	}
	        ?>
	      </select>
      </td>
    </tr>
    <tr>
      <td align="right" valign="top">Versão Estatuto Social:<?php $utils->obrig('versaoestat_vjur'); ?></td>
      <td align="left"  valign="top"><input type="text" style="width:30px;" name="versaoestat_vjur" id="versaoestat_vjur" value="<?php echo $aAltVend["vendjur"][0]["versaoestat_vjur"];?>" maxlength="10"></td>
    </tr>
    <tr>
      <td align="right" valign="top">Data Estatuto Social:<?php $utils->obrig('dtestat_vjur'); ?></td>
      <td align="left"  valign="top"><input type="text" style="width:70px;" name="dtestat_vjur" id="dtestat_vjur" value="<?php echo $utils->formataDataBRA($aAltVend["vendjur"][0]["dtestat_vjur"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10"></td>
    </tr>
    <tr>
      <td align="right" valign="top">Local de Registro:<?php $utils->obrig('locestat_vjur'); ?></td>
      <td align="left"  valign="top"><input type="text" style="width:150px;" name="locestat_vjur" id="locestat_vjur" value="<?php echo $aAltVend["vendjur"][0]["locestat_vjur"];?>" maxlength="45"></td>
    </tr>
    <tr>
      <td align="right" valign="top">N. de Registro:<?php $utils->obrig('nrregestat_vjur'); ?></td>
      <td align="left"  valign="top"><input type="text" style="width:150px;" name="nrregestat_vjur" id="nrregestat_vjur" value="<?php echo $aAltVend["vendjur"][0]["nrregestat_vjur"];?>" maxlength="45"></td>
    </tr>
    <tr>
      <td align="right" valign="top">Data do Registro:</td>
      <td align="left"  valign="top"><input type="text" style="width:70px;" name="dtregist_vjur" id="dtregist_vjur" value="<?php echo $utils->formataDataBRA($aAltVend["vendjur"][0]["dtregestat_vjur"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10"></td>
    </tr>
    <tr>
      <td align="right" valign="top">Tipo de Representação:</td>
      <td align="left"  valign="top"><input type="radio" name="vend_tipo_rep" id="vend_tipo_rep" value="P" <?php echo ($aAltVend["vendjur"][0]["tipo_rep_vjur"]=='P')?'checked':'';?> />&nbsp;<strong>Procurador</strong>&nbsp;&nbsp;&nbsp;
      								 <input type="radio" name="vend_tipo_rep" id="vend_tipo_rep" value="S" <?php echo ($aAltVend["vendjur"][0]["tipo_rep_vjur"]=='S')?'checked':'';?> />&nbsp;<strong>S?cio</strong>
    </tr>
  </table>
</div>
<!-- JAVASCRIPT PARA BUSCAR O ENDERECO PELO CEP AUTOMATICAMENTE -->
<script
    src="https://code.jquery.com/jquery-2.2.4.min.js"
    integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
    crossorigin="anonymous"></script>
<script>
    jQuery(function(){

        jQuery('#vend_cep').blur(function(){


            if (jQuery.trim(jQuery(this).val()).length > 0) {

                jQuery.ajax({
                    url: 'https://viacep.com.br/ws/'+ jQuery(this).val() + '/json/',
                    type: 'GET',
                    success: function(response){

                        if (!response.erro) {

                            jQuery('#vend_ender').val(response.logradouro);
                            jQuery('#vend_compl').val(response.complemento);
                            jQuery('#bairro_ppnt').val(response.bairro);

                            //encontra o bairro
                            jQuery('#cod_bairro_vend').find('option').each(function(i, value){

                                if ( jQuery.trim(jQuery(value).text().toLowerCase()) == response.bairro.toLowerCase() ) {
                                    jQuery(value).attr('selected', 'selected');
                                }
                            });

                            jQuery('#vend_bairro').val( response.bairro );

                            //encontra o estado
                            jQuery('#cod_uf_vend').find('option').each(function(i, value){

                                if ( jQuery.trim(jQuery(value).text().toLowerCase()) == response.uf.toLowerCase() ) {
                                    jQuery(value).attr('selected', 'selected');

                                    jQuery('#cod_uf_vend').change();

                                    setTimeout(function(){

                                        //encontra a cidade
                                        jQuery('#cod_municipio_vend').find('option').each(function(i, value){

                                            if ( jQuery.trim(jQuery(value).text().toLowerCase()) == response.localidade.toLowerCase() ) {
                                                jQuery(value).attr('selected', 'selected');
                                            }
                                        });

                                    }, 500);
                                }
                            });
                        }
                    }
                })
            }
        });

    })
</script>
<table cellpadding=0 cellspacing=5 border=0>
  <tr>
    <td align="right" valign="top">Tipo Logradouro:<?php $utils->obrig('vend_logr'); ?></td>
    <td align="left"  valign="top">
      <select name="vend_logr" id="vend_logr">
        <option value="0" >-Selecione-</option>
        <?php
        	foreach($listas->getListaLogradouro() as $k=>$v){
    		  	$selected = ($aAltVend["cod_logr"]==$v['cod_logr'])?'selected':'';
     		    print '<option value="'.$v['cod_logr'].'" '.$selected.'>'.$v['desc_logr'].'</option>';
        	}
        ?>
      </select>
    </td>
  </tr>
    <tr>
        <td align="right" valign="top">CEP:<?php $utils->obrig('vend_cep'); ?></td>
        <td align="left"  valign="top"><input type="text" class="cep" style="width:150px;" name="vend_cep" id="vend_cep" value="<?php echo $utils->formataCep($aAltVend["cep_vend"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraCEP(this,event);" maxlength="9"></td>
    </tr>
  <tr>
    <td align="right" valign="top">Endereço:<?php $utils->obrig('vend_ender'); ?></td>
    <td align="left"  valign="top"><input type="text" style="width:350px;" name="vend_ender" id="vend_ender" value="<?php echo $aAltVend["endereco_vend"];?>" maxlength="40"></td>
  </tr>
  <tr>
    <td align="right" valign="top">Num:<?php $utils->obrig('vend_num'); ?></td>
    <td align="left"  valign="top"><input type="text" style="width:40px;" name="vend_num" id="vend_num" value="<?php echo $aAltVend["nrendereco_vend"];?>" maxlength="6" onKeyDown="return teclasInt(this,event);"></td>
  </tr>
  <tr>
    <td align="right" valign="top">Complemento:</td>
    <td align="left"  valign="top"><input type="text" style="width:150px;" name="vend_compl" id="vend_compl" value="<?php echo $aAltVend["cpendereco_vend"];?>" maxlength="15"></td>
  </tr>
  <tr>
    <td align="right" valign="top">Estado:<?php $utils->obrig('cod_uf_vend'); ?></td>
    <td align="left"  valign="top">
      <select name="cod_uf_vend" id="cod_uf_vend" onChange="getListaMunicipios_v2(this,'cod_municipio_vend');">
        <option value="0" >-Selecione-</option>
        <?php
        	foreach($listas->getListaUF() as $k=>$v){
    		  	$selected = ($aAltVend["cod_uf"]==$v['cod_uf'])?'selected':'';
     		    print '<option value="'.$v['cod_uf'].'" '.$selected.'>'.$v['nome_uf'].'</option>';
        	}
        ?>
      </select>
      &nbsp;Cidade:<?php $utils->obrig('cod_municipio_vend'); ?>
      <select name="cod_municipio_vend" id="cod_municipio_vend"> <?php //  onChange="getListaDespachantes(this,'cod_uf_vend','cod_despachante_vend');" ?>
      	<option value="0" >-Selecione-</option>
      	<?php
      		if($aAltVend["cod_uf"]){
          	foreach($listas->getListaMunicipio($aAltVend["cod_uf"]) as $k=>$v){
      		  	$selected = ($aAltVend["cod_municipio"]==$v['cod_municipio'])?'selected':'';
       		    print '<option value="'.$v['cod_municipio'].'" '.$selected.'>'.$v['nome_municipio'].'</option>';
          	}
      		}
      	?>
      </select>
    </td>
  </tr>
  <tr>
    <td align="right" valign="top">Bairro (Previ):<?php $utils->obrig('cod_bairro_vend'); ?></td>
    <td align="left"  valign="top">
      <select name="cod_bairro_vend" id="cod_bairro_vend">
        <option value="0" >-Selecione-</option>
        <?php
        	foreach($listas->getListaBairro() as $k=>$v){
    		  	$selected = ($aAltVend["cod_bairro"]==$v['cod_bairro'])?'selected':'';
     		    print '<option value="'.$v['cod_bairro'].'" '.$selected.'>'.$v['nome_bairro'].'</option>';
        	}
        ?>
      </select>
    </td>
  </tr>
  <tr>
    <td align="right" valign="top">Bairro (Contrato):</td>
    <td align="left"  valign="top"><input type="text" style="width:350px;" name="vend_bairro" id="vend_bairro" value="<?php echo $aAltVend["bairro_vend"];?>" maxlength="40"></td>
  </tr>
  <tr>
    <td align="right" valign="top">Telefones:</td>
    <td align="left"  valign="top">
      <?php for($itels=1; $itels<=3;$itels++){
        $aTelefone = $aAltVend["telefones"][$itels-1];
        ?>
        <input type="text" style="width:100px;" name="vend_fone_<?php echo $itels;?>" id="vend_fone_<?php echo $itels;?>" value="<?php echo $utils->formataTelefone($aTelefone["TELEFONE_VNTL"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraTEL(this,event);" maxlength="13">
        <select name="vend_tipofone_<?php echo $itels;?>" id="vend_tipofone_<?php echo $itels;?>">
          <option value="0" >-Selecione-</option>
          <?php
          	foreach($listas->getListaTipoTelefone() as $k=>$v){
      		  	$selected = ($aTelefone["TIPO_VNTL"]==$k)?'selected':'';
       		    print '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
          	}
          ?>
        </select>
        <?php if($itels==1){ echo $utils->obrig('vend_fone_1'); } ?>
        <br />
      <?php } ?>
    </td>
  </tr>
  <tr>
    <td align="right" valign="top">E-Mail:<?php $utils->obrig('email_vend'); ?></td>
    <td align="left"  valign="top"><input type="text" style="width:300px;" name="email_vend" id="email_vend" value="<?php echo $aAltVend["email_vend"];?>" maxlength="255"></td>
  </tr>
  <?php if($cLOGIN->iLEVEL_USUA > 1){ ?>
  <tr>
    <td align="right" valign="top">Conta Corrente (Previ):<?php $utils->obrig('vend_nrcc'); ?></td>
    <td align="left"  valign="top">
      <input type="text" style="width:100px;" name="vend_nrcc" id="vend_nrcc" value="<?php echo $aAltVend["nrcc_vend"];?>" onKeyDown="return teclasInt(this,event);" maxlength="12">&nbsp;&nbsp;&nbsp;&nbsp;
	  <select name="vend_dvcc" id="vend_dvcc"><?php echo $aAltVend["dvcc_vend"];?>
	  	<option value="0">---</option>
		<option value="zero"  <?php if($aAltVend["dvcc_vend"]=='zero'){echo "selected='selected'"; }?> >0</option>
		<option value="1"  <?php if($aAltVend["dvcc_vend"]=='1'){echo "selected='selected'"; }?> >1</option>
		<option value="2"  <?php if($aAltVend["dvcc_vend"]=='2'){echo "selected='selected'"; }?> >2</option>
		<option value="3"  <?php if($aAltVend["dvcc_vend"]=='3'){echo "selected='selected'"; }?> >3</option>
		<option value="4"  <?php if($aAltVend["dvcc_vend"]=='4'){echo "selected='selected'"; }?> >4</option>
		<option value="5"  <?php if($aAltVend["dvcc_vend"]=='5'){echo "selected='selected'"; }?> >5</option>
		<option value="6"  <?php if($aAltVend["dvcc_vend"]=='6'){echo "selected='selected'"; }?> >6</option>
		<option value="7"  <?php if($aAltVend["dvcc_vend"]=='7'){echo "selected='selected'"; }?> >7</option>
		<option value="8"  <?php if($aAltVend["dvcc_vend"]=='8'){echo "selected='selected'"; }?> >8</option>
		<option value="9"  <?php if($aAltVend["dvcc_vend"]=='9'){echo "selected='selected'"; }?> >9</option>
		<option value="X"  <?php if($aAltVend["dvcc_vend"]=='X'){echo "selected='selected'"; }?> >X</option>
		
	  </select>&nbsp;&nbsp;&nbsp;&nbsp;
      &nbsp;Agência:<?php $utils->obrig('vend_nrag'); ?>
      <input type="text" style="width:60px;"  name="vend_nrag" id="vend_nrag" value="<?php echo $aAltVend["nrag_vend"];?>" onKeyDown="return teclasInt(this,event);" maxlength="4" onblur="getNomeAgencia(this,'vend_lbl_ag');">
      &nbsp; <span id="vend_lbl_ag" class="bold"></span>
    </td>
  </tr>
  <tr>
    <td align="right" valign="top">Conta Corrente (Contrato):</td>
    <td align="left"  valign="top">
      <input type="text" style="width:100px;" name="vend_nrcc2" id="vend_nrcc2" value="<?php echo $aAltVend["nrcc2_vend"];?>" onKeyDown="return teclasInt(this,event);" maxlength="12">&nbsp;&nbsp;&nbsp;&nbsp;
	  <select name="vend_dvcc2" id="vend_dvcc2"><?php echo $aAltVend["dvcc2_vend"];?>
	  	<option value="0">---</option>
		<option value="zero"  <?php if($aAltVend["dvcc2_vend"]=='zero'){echo "selected='selected'"; }?> >0</option>
		<option value="1"  <?php if($aAltVend["dvcc2_vend"]=='1'){echo "selected='selected'"; }?> >1</option>
		<option value="2"  <?php if($aAltVend["dvcc2_vend"]=='2'){echo "selected='selected'"; }?> >2</option>
		<option value="3"  <?php if($aAltVend["dvcc2_vend"]=='3'){echo "selected='selected'"; }?> >3</option>
		<option value="4"  <?php if($aAltVend["dvcc2_vend"]=='4'){echo "selected='selected'"; }?> >4</option>
		<option value="5"  <?php if($aAltVend["dvcc2_vend"]=='5'){echo "selected='selected'"; }?> >5</option>
		<option value="6"  <?php if($aAltVend["dvcc2_vend"]=='6'){echo "selected='selected'"; }?> >6</option>
		<option value="7"  <?php if($aAltVend["dvcc2_vend"]=='7'){echo "selected='selected'"; }?> >7</option>
		<option value="8"  <?php if($aAltVend["dvcc2_vend"]=='8'){echo "selected='selected'"; }?> >8</option>
		<option value="9"  <?php if($aAltVend["dvcc2_vend"]=='9'){echo "selected='selected'"; }?> >9</option>
		<option value="X"  <?php if($aAltVend["dvcc2_vend"]=='X'){echo "selected='selected'"; }?> >X</option>
		
	  </select>&nbsp;&nbsp;&nbsp;&nbsp;
      &nbsp;Agência:
      <input type="text" style="width:60px;"  name="vend_nrag2" id="vend_nrag2" value="<?php echo $aAltVend["nrag2_vend"];?>" onKeyDown="return teclasInt(this,event);" maxlength="5" onblur="">
      &nbsp;Banco:<input type="text" style="width:150px;" name="vend_banco" id="vend_banco" value="<?php echo $aAltVend["banco_vend"];?>" maxlength="40"></td>

    </td>
  </tr>

  <?php
include_once("bl_qualificacao_vendedor.inc.php");
   } ?>

</table>

<div id="ckls_vnpj" style="<?php echo $display_dados_pj;?>"><?php
if($cLOGIN->iLEVEL_USUA!=TPUSER_PROPONENTE){echo qd_exigencia('vend_exigencia','vend_BtExig','vend_addexig', 'vend_btsalvarexigencia',$_POST['vend_addexig'],$aProposta["cod_ppst"],'fimexigvend');}
 include('bl_ckls_vendedor_pj.inc.php'); ?></div>
<div id="ckls_vnpf" style="<?php echo $display_dados_pf;?>"><?php
if($cLOGIN->iLEVEL_USUA!=TPUSER_PROPONENTE){echo qd_exigencia('vend_exigencia','vend_BtExig','vend_addexig', 'vend_btsalvarexigencia',$_POST['vend_addexig'],$aProposta["cod_ppst"],'fimexigvend');}
 include('bl_ckls_vendedor_pf.inc.php'); ?></div>


<div id="div_conjuje_vend" class="grupoDados" style="<?php echo $display_dados_estciv_vend;?>">
	<b>Dados do Cônjuge</b>
	<table cellpadding=0 cellspacing=5 border=0 style="margin-top:5px;">
		<tr>
			<td align="right" valign="top">Nome:<?php $utils->obrig('vend_nome_ppcj'); ?></td>
			<td align="left"  valign="top"><input type="text" style="width:300px;" name="vend_nome_ppcj" id="vend_nome_ppcj" value="<?php echo $aAltVend["vendfisconjuge"][0]["nome_vfcj"];?>" maxlength="70"></td>
		</tr>
		<tr>
			<td align="right" valign="top">Nacionalidade:<?php $utils->obrig('vend_cod_pais_ppcj'); ?></td>
			<td align="left"  valign="top">
				<select name="vend_cod_pais_ppcj" id="vend_cod_pais_ppcj">
					<option value="0" >-Selecione-</option>
					<?php
          	foreach($listas->getListaPais() as $k=>$v){
      		  	$selected = ($aAltVend["vendfisconjuge"][0]["cod_pais"]==$v['cod_pais'])?'selected':'';
       		    print '<option value="'.$v['cod_pais'].'" '.$selected.'>'.$v['nome_pais'].'</option>';
          	}
          ?>
        </select>
			</td>
		</tr>
    <tr>
      <td align="right" valign="top"  style="<?php echo $display_dets_estciv_vend_conj;?>">Est Civil:<?php $utils->obrig('vfcj_civil'); ?></td>
      <td align="left"  valign="top">
	      <select name="vend_civil_ppcj" id="vend_civil_ppcj" onchange="trocouEstadoCivilVend(this);">
	        <option value="0" >-Selecione-</option>
	        <?php
	        	foreach($listas->getListaECivil() as $k=>$v){
	    		  	$selected = ($aAltVend["vendfisconjuge"][0]["cod_estciv"]==$v['cod_estciv'])?'selected':'';
	     		    print '<option value="'.$v['cod_estciv'].'" '.$selected.'>'.$v['desc_estciv'].'</option>';
				}
	        ?>
	      </select>
      </td>
    </tr>
		<tr>
			<td align="right" valign="top">RG:<?php $utils->obrig('vend_nrrg_ppcj'); ?></td>
			<td align="left"  valign="top">
				<input type="text" style="width:150px;" name="vend_nrrg_ppcj" id="vend_nrrg_ppcj" value="<?php echo $aAltVend["vendfisconjuge"][0]["nrrg_vfcj"];?>"  maxlength="13">&nbsp;
				Emiss?o:<?php $utils->obrig('vend_dtrg_ppcj'); ?>
				<input type="text" style="width:80px;" name="vend_dtrg_ppcj" id="vend_dtrg_ppcj" value="<?php echo $utils->formataDataBRA($aAltVend["vendfisconjuge"][0]["dtrg_vfcj"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10">&nbsp;
				?rg?o Emissor:<?php $utils->obrig('vend_orgrg_ppcj'); ?>
				<input type="text" style="width:80px;" name="vend_orgrg_ppcj" id="vend_orgrg_ppcj" value="<?php echo $aAltVend["vendfisconjuge"][0]["orgrg_vfcj"];?>" maxlength="10">
			</td>
		</tr>
		<tr>
			<td align="right" valign="top">CPF:<?php $utils->obrig('vend_cpf_pccj'); ?></td>
			<td align="left"  valign="top"><input type="text" style="width:150px;" name="vend_cpf_pccj" id="vend_cpf_pccj" value="<?php echo $utils->formataCPF($aAltVend["vendfisconjuge"][0]["cpf_pccj"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraCPF(this,event);" maxlength="14" /></td>
		</tr>
		<tr>
			<td align="right" valign="top">Trabalha atualmente:<?php $utils->obrig('vend_flgtrabalha_ppcj'); ?></td>
			<td align="left"  valign="top">
			  <?php
			  	$display_dets_trab_conj = ($aAltVend["vendfisconjuge"][0]["flgtrabalha_vfcj"]=='S')?'':'display:none;';
			  	$display_dets_trab_conj_2 = 'display:none;';
				foreach ($listas->getListaSN() as $k=>$v){
          	$checked = ($aAltVend["vendfisconjuge"][0]["flgtrabalha_vfcj"]==$k)?'checked':'';
          	print '<input type="radio" class="rd" name="vend_flgtrabalha_ppcj" id="vend_flgtrabalha_ppcj" value="'.$k.'" '.$checked.' onclick="trocouTrabConjVend(this);" /> '.$v.' &nbsp;&nbsp;';
			  	}
			  ?>
			</td>
		</tr>
		
		<tr id="tr_vend_conj_trab_titulo" style="<?php echo $display_dets_trab_conj_2;?>">
			<td align="left" valign="top" colspan="2" style="padding-top:10px;"><b>Dados da Empresa do C?njuge</b></td>
		</tr>
		<tr id="tr_vend_conj_trab_empresa" style="<?php echo $display_dets_trab_conj_2;?>">
			<td align="right" valign="top">Empresa:<?php $utils->obrig('vend_empresa_ppcj'); ?></td>
			<td align="left"  valign="top"><input type="text" style="width:80px;" name="vend_empresa_ppcj" id="vend_empresa_ppcj" value="<?php echo $aAltVend["vendfisconjuge"][0]["empresa_vfcj"];?>" maxlength="80"></td>
		</tr>
		<tr id="tr_vend_conj_trab_admissao" style="<?php echo $display_dets_trab_conj_2;?>">
			<td align="right" valign="top">Data de Admiss?o:<?php $utils->obrig('vend_dtadmissaoemp_ppcj'); ?></td>
			<td align="left"  valign="top"><input type="text" style="width:80px;" name="vend_dtadmissaoemp_ppcj" id="vend_dtadmissaoemp_ppcj" value="<?php echo $utils->formataDataBRA($aAltVend["vendfisconjuge"][0]["dtadmissaoemp_vfcj"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10"></td>
		</tr>
    <tr id="tr_vend_conj_trab_endereco" style="<?php echo $display_dets_trab_conj_2;?>">
      <td align="right" valign="top">Endere?o:<?php $utils->obrig('vend_enderecoemp_ppcj'); ?></td>
      <td align="left"  valign="top"><input type="text" style="width:350px;" name="vend_enderecoemp_ppcj" id="vend_enderecoemp_ppcj" value="<?php echo $aAltVend["vendfisconjuge"][0]["enderecoemp_vfcj"];?>" maxlength="100"></td>
    </tr>
    <tr id="tr_vend_conj_trab_end_num" style="<?php echo $display_dets_trab_conj_2;?>">
      <td align="right" valign="top">N?mero:<?php $utils->obrig('vend_numeroemp_ppcj'); ?></td>
      <td align="left"  valign="top"><input type="text" style="width:40px;" name="vend_numeroemp_ppcj" id="vend_numeroemp_ppcj" value="<?php echo $aAltVend["vendfisconjuge"][0]["numeroemp_vfcj"];?>" maxlength="6"></td>
    </tr>
    <tr id="tr_vend_conj_trab_compl" style="<?php echo $display_dets_trab_conj_2;?>">
      <td align="right" valign="top">Complemento:</td>
      <td align="left"  valign="top"><input type="text" style="width:150px;" name="vend_complementoemp_ppcj" id="vend_complementoemp_ppcj" value="<?php echo $aAltVend["vendfisconjuge"][0]["complementoemp_vfcj"];?>" maxlength="60"></td>
    </tr>
    <tr id="tr_vend_conj_trab_estado" style="<?php echo $display_dets_trab_conj_2;?>">
      <td align="right" valign="top">Estado:<?php $utils->obrig('vend_estadoemp_ppcj'); ?></td>
      <td align="left"  valign="top">
        <select name="vend_estadoemp_ppcj" id="vend_estadoemp_ppcj" onChange="getListaMunicipios_v2(this,'vend_cidadeemp_ppcj');">
          <option value="0" >-Selecione-</option>
          <?php
          	foreach($listas->getListaUF() as $k=>$v){
      		  	$selected = ($aAltVend["vendfisconjuge"][0]["estadoemp_vfcj"]==$v['cod_uf'])?'selected':'';
       		    print '<option value="'.$v['cod_uf'].'" '.$selected.'>'.$v['nome_uf'].'</option>';
          	}
          ?>
        </select>
        &nbsp;Cidade:<?php $utils->obrig('vend_cidadeemp_ppcj'); ?>
        <select name="vend_cidadeemp_ppcj" id="vend_cidadeemp_ppcj">
        	<option value="0" >-Selecione-</option>
        	<?php
        		if($aAltVend["vendfisconjuge"][0]["estadoemp_vfcj"]){
            	foreach($listas->getListaMunicipio($aAltVend["vendfisconjuge"][0]["estadoemp_vfcj"]) as $k=>$v){
        		  	$selected = ($aAltVend["vendfisconjuge"][0]["cidadeemp_vfcj"]==$v['cod_municipio'])?'selected':'';
         		    print '<option value="'.$v['cod_municipio'].'" '.$selected.'>'.$v['nome_municipio'].'</option>';
            	}
        		}
        	?>
        </select>
      </td>
    </tr>
    <tr id="tr_vend_conj_trab_bairro" style="<?php echo $display_dets_trab_conj_2;?>">
      <td align="right" valign="top">Bairro:<?php $utils->obrig('vend_bairroemp_ppcj'); ?></td>
      <td align="left"  valign="top">
        <select name="vend_bairroemp_ppcj" id="vend_bairroemp_ppcj">
          <option value="0" >-Selecione-</option>
          <?php
          	foreach($listas->getListaBairro() as $k=>$v){
      		  	$selected = ($aAltVend["vendfisconjuge"][0]["bairroemp_vfcj"]==$v['cod_bairro'])?'selected':'';
       		    print '<option value="'.$v['cod_bairro'].'" '.$selected.'>'.$v['nome_bairro'].'</option>';
          	}
          ?>
        </select>
      </td>
    </tr>
		<tr id="tr_vend_conj_trab_telefone" style="<?php echo $display_dets_trab_conj_2;?>">
			<td align="right" valign="top">Telefone:<?php $utils->obrig('vend_telefoneemp_ppcj'); ?></td>
			<td align="left"  valign="top"><input type="text" style="width:100px;" name="vend_telefoneemp_ppcj" id="vend_telefoneemp_ppcj" value="<?php echo $utils->formataTelefone($aAltVend["vendfisconjuge"][0]["telefoneemp_vfcj"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraTEL(this,event);" maxlength="13"></td>
		</tr>
		<tr id="tr_vend_conj_trab_cargo" style="<?php echo $display_dets_trab_conj;?>">
			<td align="right" valign="top">Cargo:<?php $utils->obrig('vend_cargoemp_ppcj'); ?></td>
			<td align="left"  valign="top"><input type="text" style="width:80px;" name="vend_cargoemp_ppcj" id="vend_cargoemp_ppcj" value="<?php echo $aAltVend["vendfisconjuge"][0]["cargoemp_vfcj"];?>" maxlength="60"></td>
		</tr>
		<tr id="tr_vend_conj_trab_salario" style="<?php echo $display_dets_trab_conj_2;?>">
			<td align="right" valign="top">Sal?rio:<?php $utils->obrig('vend_salarioemp_ppcj'); ?></td>
			<td align="left"  valign="top"><input type="text" name="vend_salarioemp_ppcj" id="vend_salarioemp_ppcj" style="width:80px;" value="<?php echo $utils->formataMoeda($aAltVend["vendfisconjuge"][0]["salarioemp_vfcj"]);?>" maxlength="20" onKeyDown="return teclasFloat(this,event);" onKeyUp="return mascaraMoeda(this,event);" onFocus="this.select();" /></td>
		</tr>
	</table>
	<div id="ckls_pfcj"><?php  if($cLOGIN->iLEVEL_USUA!=TPUSER_PROPONENTE){
	echo qd_exigencia('vendcj_exigencia','vendcj_BtExig','vendcj_addexig', 'vendcj_btsalvarexigencia',$_POST['vendcj_addexig'],$aProposta["cod_ppst"],'fimexigvendcj');}
	 include('bl_ckls_vendedor_pfcj.inc.php'); ?></div>
</div>

<div id="div_pjs" style="<?php echo $display_dados_pj;?>"><?php include('bl_socios.inc.php'); ?></div>

<?php if($indiceVendedor==0){ ?>
	<table cellpadding=0 cellspacing=5 border=0 style="margin-top:5px;">
		<?php if($cLOGIN->iLEVEL_USUA==TPUSER_ATENDENTE){ ?>
			<tr>
				<td align="right" valign="top">Despachante:<?php $utils->obrig('cod_despachante_vend'); ?></td>
				<td align="left"  valign="top">
			    <select name="cod_despachante_vend" id="cod_despachante_vend">
			      <option value="0" >-Selecione-</option>
			      <?php
			      	foreach($listas->getListaDespachantes($aAltVend["cod_uf"],$aAltVend["cod_municipio"]) as $kDesp=>$vDesp){
			  		  	$selected = ($aAltVend["despachante_vend"]==$vDesp['cod_usua'])?'selected':'';
			   		    print '<option value="'.$vDesp['cod_usua'].'" '.$selected.'>'.$vDesp['nome_usua'].'</option>';
			      	}
			      ?>
			    </select>
				</td>
			</tr>
		<?php }else{ ?>
			<tr>
				<td align="right" valign="top">Despachante:<?php $utils->obrig('cod_despachante_vend'); ?></td>
				<td align="left"  valign="top"><b><?php
					$vTMP = $aAltVend["despachante_vend"];
					$aTMP = $oUsuario->getUsuario($vTMP);
					print $aTMP[0]["nome_usua"];
				?></b></td>
			</tr>
		<?php } ?>
	</table>
<?php } ?>

<div style="text-align:right; margin-top:10px;">
	<?php if($acaoProposta=='altVend'||$acaoProposta=='altSocio'){ ?>
		<img src="images/buttons/bt_salvar.gif"   id="bt_save_vend"   alt="Salvar Vendedor" class="im" onClick="saveVend('<?php echo $crypt->encrypt('saveVend');?>');" />
	<?php }else{ ?>
		<img src="images/buttons/bt_adicionar.gif" id="bt_add_vend" alt="Adicionar Vendedor" class="im" onClick="addVend('<?php echo $crypt->encrypt('addVend');?>');" />
	<?php } ?>
	<img src="images/buttons/bt_cancelar.gif"  id="bt_cancel_vend" alt="Cancelar" class="im" onClick="cancelFormAddVend();" />
</div>
