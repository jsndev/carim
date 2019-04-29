<?
	$aAltSocio = array();
	if($acaoProposta=='altSocio'||$acaoProposta=='dtsSocio'){
		if (is_array($aAltVend["vendjursocios"]) && @count($aAltVend["vendjursocios"]) > 0) {
			foreach($aAltVend["vendjursocios"] as $kSocio=>$vSocio){
				if($vSocio["cod_vjsoc"] == $_POST["frm_cod_socio"]){
					$aAltSocio = $vSocio;
				}
			}
		}
	}
?>
<table cellpadding=0 cellspacing=5 border=0>
  <tr>
    <td align="right" valign="top">Nome do Sócio:<? $utils->obrig('vend_s_nome'); ?></td>
    <td align="left"  valign="top"><input type="text" style="width:300px;" name="vend_s_nome" id="vend_s_nome" value="<?=$aAltSocio['nome_vjsoc'];?>" maxlength="70"></td>
  </tr>
  <tr>
    <td align="right" valign="top">Nome do Sócio Abrev:<? $utils->obrig('vend_s_nabrev'); ?></td>
    <td align="left"  valign="top"><input type="text" style="width:150px;" name="vend_s_nabrev" id="vend_s_nabrev" value="<?=$aAltSocio['nick_vjsoc'];?>" maxlength="15"></td>
  </tr>
  <tr>
    <td align="right" valign="top">Tipo Logradouro:<? $utils->obrig('vend_s_logr'); ?></td>
    <td align="left"  valign="top">
      <select name="vend_s_logr" id="vend_s_logr">
        <option value="0" >-Selecione-</option>
        <?
        	foreach($listas->getListaLogradouro() as $k=>$v){
    		  	$selected = ($aAltSocio["cod_logr"]==$v['cod_logr'])?'selected':'';
     		    print '<option value="'.$v['cod_logr'].'" '.$selected.'>'.$v['desc_logr'].'</option>';
        	}
        ?>
      </select>
    </td>
  </tr>
  <tr>
    <td align="right" valign="top">Endereço:<? $utils->obrig('vend_s_ender'); ?></td>
    <td align="left"  valign="top"><input type="text" style="width:350px;" name="vend_s_ender" id="vend_s_ender" value="<?=$aAltSocio['endereco_vjsoc'];?>" maxlength="40"></td>
  </tr>
  <tr>

    <td align="right" valign="top">Num:<? $utils->obrig('vend_s_num'); ?></td>
    <td align="left"  valign="top"><input type="text" style="width:40px;" name="vend_s_num" id="vend_s_num" value="<?=$aAltSocio['nrendereco_vjsoc'];?>" maxlength="6" onKeyDown="return teclasInt(this,event);"></td>
  </tr>
  <tr>
    <td align="right" valign="top">Complemento:</td>
    <td align="left"  valign="top"><input type="text" style="width:150px;" name="vend_s_compl" id="vend_s_compl" value="<?=$aAltSocio['cpendereco_vjsoc'];?>" maxlength="15"></td>
  </tr>

  <tr>
    <td align="right" valign="top">Estado:<? $utils->obrig('vend_s_uf'); ?></td>
    <td align="left"  valign="top">
      <select name="vend_s_uf" id="vend_s_uf" onChange="getListaMunicipios_v2(this,'vend_s_cidade');">
        <option value="0" >-Selecione-</option>
        <?
        	foreach($listas->getListaUF() as $k=>$v){
    		  	$selected = ($aAltSocio["cod_uf"]==$v['cod_uf'])?'selected':'';
     		    print '<option value="'.$v['cod_uf'].'" '.$selected.'>'.$v['nome_uf'].'</option>';
        	}
        ?>
      </select>
      &nbsp;Cidade:<? $utils->obrig('vend_s_cidade'); ?>
      <select name="vend_s_cidade" id="vend_s_cidade">
      	<option value="0" >-Selecione-</option>
      	<?
      		if($aAltSocio["cod_uf"]){
          	foreach($listas->getListaMunicipio($aAltSocio["cod_uf"]) as $k=>$v){
      		  	$selected = ($aAltSocio["cod_municipio"]==$v['cod_municipio'])?'selected':'';
       		    print '<option value="'.$v['cod_municipio'].'" '.$selected.'>'.$v['nome_municipio'].'</option>';
          	}
      		}
      	?>
      </select>
    </td>
  </tr>
  <tr>
    <td align="right" valign="top">Bairro (Previ):<? $utils->obrig('vend_s_bairro'); ?></td>
    <td align="left"  valign="top">
      <select name="vend_s_bairro" id="vend_s_bairro">
        <option value="0" >-Selecione-</option>
        <?
        	foreach($listas->getListaBairro() as $k=>$v){
    		  	$selected = ($aAltSocio["cod_bairro"]==$v['cod_bairro'])?'selected':'';
     		    print '<option value="'.$v['cod_bairro'].'" '.$selected.'>'.$v['nome_bairro'].'</option>';
        	}
        ?>
      </select>
    </td>
  </tr>
  <tr>
    <td align="right" valign="top">Bairro (Contrato):</td>
    <td align="left"  valign="top"><input type="text" style="width:150px;" name="bairro_vjsoc" id="bairro_vjsoc" value="<?=$aAltSocio['bairro_vjsoc'];?>" maxlength="255"></td>
  </tr>

  <tr>
    <td align="right" valign="top">CEP:<? $utils->obrig('vend_s_cep'); ?></td>
    <td align="left"  valign="top"><input type="text" style="width:150px;" name="vend_s_cep" id="vend_s_cep" value="<?=$utils->formataCep($aAltSocio['cep_vjsoc']);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraCEP(this,event);" maxlength="9"></td>
  </tr>
  <tr>

    <td align="right" valign="top">Telefone:<? $utils->obrig('vend_s_fone'); ?></td>
    <td align="left"  valign="top"><input type="text" style="width:100px;" name="vend_s_fone" id="vend_s_fone" value="<?=$utils->formataTelefone($aAltSocio['telefone_vjsoc']);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraTEL(this,event);" maxlength="13"></td>
  </tr>
  <tr>
    <td align="right" valign="top">CPF:<? $utils->obrig('vend_s_cpf'); ?></td>
    <td align="left"  valign="top"><input type="text" style="width:150px;" name="vend_s_cpf" id="vend_s_cpf" value="<?=$utils->formataCPF($aAltSocio['cpf_vjsoc']);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraCPF(this,event);" maxlength="14"></td>
  </tr>
<tr>
	<td align="right" valign="top">RG:<?php $utils->obrig('vend_s_nrrg'); ?></td>
	<td align="left"  valign="top">
		<input type="text" style="width:150px;" name="vend_s_nrrg" id="vend_s_nrrg" value="<?php echo $aAltSocio["nrrg_vjsoc"];?>"  maxlength="13">&nbsp;
		Emissão:<?php $utils->obrig('vend_s_dtrg'); ?>
		<input type="text" style="width:80px;" name="vend_s_dtrg" id="vend_s_dtrg" value="<?php echo $utils->formataDataBRA($aAltSocio["dtrg_vjsoc"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10">&nbsp;
		Órgão Emissor:<?php $utils->obrig('vend_s_orgrg'); ?>
		<input type="text" style="width:80px;" name="vend_s_orgrg" id="vend_s_orgrg" value="<?php echo $aAltSocio["orgrg_vjsoc"];?>" maxlength="10">
	</td>
 </tr>  <tr>
    <td align="right" valign="top">Sexo:<? $utils->obrig('vend_s_sexo'); ?></td>
    <td align="left"  valign="top">
		  <?
		  	foreach ($listas->getListaSexo() as $k=>$v){
        	$checked = ($aAltSocio["sexo_vjsoc"]==$k)?'checked':'';
        	print '<input type="radio" class="rd" name="vend_s_sexo" id="vend_s_sexo" value="'.$k.'" '.$checked.' /> '.$v.' &nbsp;&nbsp;';
		  	}
		  ?>
		</td>
  </tr>
  <tr>
    <td align="right" valign="top">Nacionalidade:<? $utils->obrig('vend_s_nacion'); ?></td>
    <td align="left"  valign="top">
			<select name="vend_s_nacion" id="vend_s_nacion">
				<option value="0" >-Selecione-</option>
				<?
        	foreach($listas->getListaPais() as $k=>$v){
    		  	$selected = ($aAltSocio["cod_pais"]==$v['cod_pais'])?'selected':'';
     		    print '<option value="'.$v['cod_pais'].'" '.$selected.'>'.$v['nome_pais'].'</option>';
        	}
        ?>
      </select>
    </td>
  </tr>
  <tr>
    <td align="right" valign="top">Estado Civil:<?php $utils->obrig('vend_s_estciv'); ?></td>
    <td align="left"  valign="top">
      <select name="vend_s_estciv" id="vend_s_estciv">
        <option value="0" >-Selecione-</option>
        <?php
        	foreach($listas->getListaECivil() as $k=>$v){
    		  	$selected = ($aAltSocio["cod_estciv"]==$v['cod_estciv'])?'selected':'';
     		    print '<option value="'.$v['cod_estciv'].'" '.$selected.'>'.$v['desc_estciv'].'</option>';
        	}
        ?>
      </select>
    </td>
  </tr>
 <tr>
	<td align="right" valign="top">Profissão:<?php $utils->obrig('vend_s_cargo'); ?></td>
	<td align="left"  valign="top"><input type="text" style="width:150px;" name="vend_s_cargo" id="vend_s_cargo" value="<?php echo $aAltSocio["cargo_vjsoc"];?>" maxlength="60"></td>
  </tr></table>

<div style="text-align:right; margin:10px 0px;">
	<?
		$display_bt_save = ($acaoProposta=='altVend'||$acaoProposta=='altSocio')?'':'display:none';
		$display_bt_add  = ($acaoProposta=='altVend'||$acaoProposta=='altSocio')?'display:none':'';
	?>
	<img src="images/buttons/bt_salvar.gif"    id="bt_save_socio" alt="Salvar Sócio" class="im" onClick="saveSocio('<?=$crypt->encrypt('saveSocio');?>');" style="<?=$display_bt_save;?>" />
	<img src="images/buttons/bt_adicionar.gif" id="bt_add_socio"  alt="Adicionar Sócio" class="im" onClick="addSocio('<?=$crypt->encrypt('saveSocio');?>');" style="<?=$display_bt_add;?>" />
	<img src="images/buttons/bt_cancelar.gif"  id="bt_cancel_socio" alt="Cancelar" class="im" onClick="cancelFormAddSocio();" />
</div>
