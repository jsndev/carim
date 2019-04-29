<?php
$aAltVend = $vVend;
?>
<table cellpadding=0 cellspacing=5 border=0 class="tb_dets_list">
	<colgroup><col width="180" /><col /></colgroup>
	<tr>
    <td align="right" valign="top">Tipo:</td>
    <td align="left"  valign="top"><b><?php echo (($vVend["tipo_vend"]=='1')?'Pessoa Física':'Pessoa Jurídica');?></b></td>
  </tr>
  <tr>
    <td align="right" valign="top">Nome do Vendedor:</td>
    <td align="left"  valign="top"><b><?php echo $vVend["nome_vend"];?></b></td>
  </tr>
  <tr>
    <td align="right" valign="top">Nome do Vendedor Abrev:</td>
    <td align="left"  valign="top"><b><?php echo $vVend["nick_vend"];?></b></td>
  </tr>
  <?php $display_porc_vend = (@count($aProposta["vendedores"]) > 1)?'':'display:none;'; ?>
  <tr style="<?php echo $display_porc_vend;?>">
    <td align="right" valign="top">Porcentagem da Venda:</td>
    <td align="left"  valign="top"><b><?php echo $utils->formataFloat($vVend["percentualvenda_vend"],2);?> %</b></td>
  </tr>
</table>

<?php if($vVend["tipo_vend"]=='1'){ ?>
<div id="div_pf">
  <table cellpadding=0 cellspacing=5 border=0 class="tb_dets_list">
  	<colgroup><col width="180" /><col /></colgroup>
    <tr>
      <td align="right" valign="top">CPF:</td>
      <td align="left"  valign="top"><b><?php echo $utils->formataCPF($vVend["vendfis"][0]["cpf_vfisica"]);?></b></td>
    </tr>
    <tr>
      <td align="right" valign="top">Sexo:</td>
      <td align="left"  valign="top"><b><?php
      	$vTMP = $vVend["vendfis"][0]["sexo_vfisica"];
      	$aTMP = $oParametros->getListaSexo($vTMP);
      	print $aTMP[$vTMP];
      ?></b></td>
    </tr>
    <tr>
      <td align="right" valign="top">Data Nasc:</td>
      <td align="left"  valign="top"><b><?php echo $utils->formataDataBRA($vVend["vendfis"][0]["dtnascimento_vfisica"]);?></b></td>
    </tr>
    <tr>
      <td align="right" valign="top">Nacionalidade:</td>
      <td align="left"  valign="top"><b><?php
      	$vTMP = $vVend["vendfis"][0]["cod_pais"];
      	$aTMP = $listas->getListaPais($vTMP);
      	print $aTMP[0]["nome_pais"];
      ?></b></td>
    </tr>
    <tr>
      <td align="right" valign="top">Naturalidade:</td>
      <td align="left"  valign="top"><b><?php echo $vVend["vendfis"][0]["natur_vfisica"];?></b></td>
    </tr>
    <tr>
      <td align="right" valign="top">Doc de Identif:</td>
	      <td align="left"  valign="top"><b><?php
      	$vTMP = $vVend["vendfis"][0]["cod_tpdoc"];
      	$aTMP = $listas->getListaTipoDocumento($vTMP);
      	print $aTMP[0]["desc_tpdoc"];
      ?></b></td>
    </tr>
    <tr>
      <td align="right" valign="top">RG:</td>
      <td align="left"  valign="top">
      	<b><?php echo $vVend["vendfis"][0]["nrrg_vfisica"];?></b> &nbsp;&nbsp;
      	Emissão: <b><?php echo $utils->formataDataBRA($vVend["vendfis"][0]["dtrg_vfisica"]);?></b> &nbsp;&nbsp;
      	Órgão Emissor: <b><?php echo $vVend["vendfis"][0]["orgrg_vfisica"];?></b>
      </td>
    </tr>
    <tr>
      <td align="right" valign="top">Est Civil:</td>
      <td align="left"  valign="top"><b><?php
      	$vTMP = $vVend["vendfis"][0]["cod_estciv"];
      	$aTMP = $listas->getListaECivil($vTMP);
      	print $aTMP[0]["desc_estciv"];
      ?></b></td>
    </tr>
    <?php if($vVend["vendfis"][0]["cod_estciv"]==2||$vVend["vendfis"][0]["cod_estciv"]==99){ ?>
	  <tr id="tr_casam_dt_vend">
	    <td align="right" valign="top">Data do Casamento:</td>
	    <td align="left"  valign="top"><b><?php echo $utils->formataDataBRA($vVend["vendfisconjuge"][0]["dtcasamento_vfcj"]);?></b></td>
	  </tr>
	  <tr id="tr_regime_bens_vend">
	    <td align="right" valign="top">Regime de Bens:</td>
	    <td align="left"  valign="top"><b><?php
      	$vTMP = $vVend["vendfisconjuge"][0]["regimebens_vfcj"];
      	$aTMP = $listas->getListaRegimeBens($vTMP);
      	print $aTMP[$vTMP];
	    ?></b></td>
	  </tr>
	  <?php } ?>
	  <?php if($vVend["vendfisconjuge"][0]["regimebens_vfcj"]==3 || $vVend["vendfisconjuge"][0]["regimebens_vfcj"]==5){ ?>
		<tr>
			<td align="right" valign="top">Data:</td>
			<td align="left"  valign="top"><b><?php echo $utils->formataDataBRA($vVend["vendfisconjugepacto"][0]["data_vcpa"]);?></b></td>
		</tr>
		<tr>
			<td align="right" valign="top">Lavrado no:</td>
			<td align="left"  valign="top"><b><?php echo $vVend["vendfisconjugepacto"][0]["locallavracao_vcpa"];?></b></td>
		</tr>
		<tr>
			<td align="right" valign="top">Livro:</td>
			<td align="left"  valign="top"><b><?php echo $vVend["vendfisconjugepacto"][0]["livro_vcpa"];?></b></td>
		</tr>
		<tr>
			<td align="right" valign="top">Fls.:</td>
			<td align="left"  valign="top"><b><?php echo $vVend["vendfisconjugepacto"][0]["folha_vcpa"];?></b></td>
		</tr>
		<tr>
			<td align="right" valign="top">Número do Registro:</td>
			<td align="left"  valign="top"><b><?php echo $vVend["vendfisconjugepacto"][0]["numeroregistro_vcpa"];?></b></td>
		</tr>
		<?php } ?>
    <tr>
      <td align="right" valign="top">Nome do pai:</td>
      <td align="left"  valign="top"><b><?php echo $vVend["vendfis"][0]["nomepai_vfisica"];?></b></td>
    </tr>
    <tr>
      <td align="right" valign="top">Nome da mãe:</td>
      <td align="left"  valign="top"><b><?php echo $vVend["vendfis"][0]["nomemae_vfisica"];?></b></td>
    </tr>
    <tr>
      <td align="right" valign="top">Profissão (PREVI):</td>
      <td align="left"  valign="top"><b><?php
      	$vTMP = $vVend["vendfis"][0]["cod_prof"];
      	$aTMP = $listas->getListaProfissoes($vTMP);
      	print $aTMP[0]["desc_prof"];
      ?></b></td>
    </tr>
    <tr>
      <td align="right" valign="top">Profissão (Contrato):</td>
      <td align="left"  valign="top"><b><?php echo $vVend["vendfis"][0]["proponente_vfisica"];?></b></td>
    </tr>
    <tr>
      <td align="right" valign="top">Renda:</td>
      <td align="left"  valign="top"><b>R$ <?php echo $utils->formataMoeda($vVend["vendfis"][0]["vlrenda_vfisica"]);?></b></td>
    </tr>
    <tr>
      <td align="right" valign="top">Inscrição INSS:</td>
      <td align="left"  valign="top"><b><?php echo $vVend["vendfis"][0]["nrinss_vfisica"];?></b></td>
    </tr>
  </table>
</div>
<?php } ?>

<?php if($vVend["tipo_vend"]=='2'){ ?>
<div id="div_pj">
  <table cellpadding=0 cellspacing=5 border=0 class="tb_dets_list">
  	<colgroup><col width="180" /><col /></colgroup>
    <tr>
      <td align="right" valign="top">Tipo Societário:</td>
      <td align="left"  valign="top"><strong><?php echo $vVend["vendjur"][0]["tipo_soc_vjur"];?></strong></td>
    </tr>
    <tr>
      <td align="right" valign="top">CNPJ:</td>
      <td align="left"  valign="top"><b><?php echo $utils->formataCnpj($vVend["vendjur"][0]["cnpj_vjur"]);?></b></td>
    </tr>
    <tr>
      <td align="right" valign="top">Isenção PIS-PASEP:</td>
      <td align="left"  valign="top"><b><?php
        $vTMP = $vVend["vendjur"][0]["isenpis_vjur"];
      	$aTMP = $listas->getListaSN();
      	print $aTMP[$vTMP];	
      ?></b></td>
    </tr>
    <tr>
      <td align="right" valign="top">Isenção COFINS:</td>
      <td align="left"  valign="top"><b><?php
        $vTMP = $vVend["vendjur"][0]["isencofins_vjur"];
      	$aTMP = $listas->getListaSN();
      	print $aTMP[$vTMP];	
     	?></b></td>
    </tr>
    <tr>
      <td align="right" valign="top">Isenção CSLL:</td>
      <td align="left"  valign="top"><b><?php
        $vTMP = $vVend["vendjur"][0]["isencsll_vjur"];
      	$aTMP = $listas->getListaSN();
      	print $aTMP[$vTMP];
      ?></b></td>
    </tr>
    <tr>
      <td align="right" valign="top">Atividade Econômica:</td>
      <td align="left"  valign="top"><b><?php
        $vTMP = $vVend["vendjur"][0]["cod_cnae"];
      	$aTMP = $listas->getListaAtivEcon($vTMP);
      	print $aTMP[0]["desc_cnae"];
      ?></b></td>
    </tr>
    <tr>
      <td align="right" valign="top">Versão Estatuto Social:</td>
      <td align="left"  valign="top"><strong><?php echo $vVend["vendjur"][0]["versaoestat_vjur"];?></strong></td>
    </tr>
    <tr>
      <td align="right" valign="top">Data Estatuto Social:</td>
      <td align="left"  valign="top"><strong><?php echo $utils->formataDataBRA($vVend["vendjur"][0]["dtestat_vjur"]);?></strong></td>
    </tr>
    <tr>
      <td align="right" valign="top">Local de Registro:</td>
      <td align="left"  valign="top"><strong><?php echo $vVend["vendjur"][0]["locestat_vjur"];?></strong></td>
    </tr>
    <tr>
      <td align="right" valign="top">Nº de Registro:</td>
      <td align="left"  valign="top"><strong><?php echo $vVend["vendjur"][0]["nrregestat_vjur"];?></strong></td>
    </tr>
    <tr>
      <td align="right" valign="top">Data do Registro:</td>
      <td align="left"  valign="top"><strong><?php echo $utils->formataDataBRA($vVend["vendjur"][0]["dtregestat_vjur"]);?></strong></td>
    </tr>
  </table>
</div>
<?php } ?>

<table cellpadding=0 cellspacing=5 border=0 class="tb_dets_list">
	<colgroup><col width="180" /><col /></colgroup>
  <tr>
    <td align="right" valign="top">Tipo Logradouro:</td>
    <td align="left"  valign="top"><b><?php echo $vVend["logradouro"][0]["desc_logr"];?></b></td>
  </tr>
  <tr>
    <td align="right" valign="top">Endereço:</td>
    <td align="left"  valign="top"><b><?php echo $vVend["endereco_vend"];?></b></td>
  </tr>
  <tr>
    <td align="right" valign="top">Num:</td>
    <td align="left"  valign="top"><b><?php echo $vVend["nrendereco_vend"];?></b></td>
  </tr>
  <tr>
    <td align="right" valign="top">Complemento:</td>
    <td align="left"  valign="top"><b><?php echo $vVend["cpendereco_vend"];?></b></td>
  </tr>
  <tr>
    <td align="right" valign="top">Bairro:</td>
    <td align="left"  valign="top"><b><?php echo $vVend["bairro"][0]["nome_bairro"];?></b></td>
	</tr>
  <tr>
    <td align="right" valign="top">Cidade:</td>
    <td align="left"  valign="top"><b><?php echo $vVend["municipio"][0]["nome_municipio"];?></b></td>
	</tr>
  <tr>
    <td align="right" valign="top">Estado:</td>
    <td align="left"  valign="top"><b><?php echo $vVend["uf"][0]["nome_uf"];?></b></td>
	</tr>
  <tr>
    <td align="right" valign="top">CEP:</td>
    <td align="left"  valign="top"><b><?php echo $utils->formataCep($vVend["cep_vend"]);?></b></td>
  </tr>
	<tr>
	  <td align="right" valign="top">Telefones:</td><td align="left">
	   <?php
	     $tipoTel = $listas->getListaTipoTelefone();
	     $aTelefones = $vVend["telefones"];
	     if( is_array($aTelefones) && @count($aTelefones)>0 ){
	       foreach ($aTelefones as $kTelefones=>$vTelefones){
	         echo '<b>'.$utils->formataTelefone($vTelefones["TELEFONE_VNTL"]).'</b> ('.$tipoTel[$vTelefones["TIPO_VNTL"]].')<br />';
	       }
	     }
	   ?>
	  </td>
	</tr>
	<tr>
	  <td align="right" valign="top">E-Mail:</td><td align="left"><b><?php echo $vVend["email_vend"];?></b></td>
	</tr>
  
  <tr>
    <td align="right" valign="top">Conta Corrente:</td>
    <td align="left"  valign="top">
	<?php
	if ($vVend["dvcc_vend"]=='zero'){$dv="0";}else{$dv=$vVend["dvcc_vend"];}
	?>
    	<b><?php echo $vVend["nrcc_vend"].'-'.$dv;?></b>
      &nbsp;Agência: <b><?php echo $vVend["nrag_vend"];?>
      <?php if($vVend["nrag_vend"]){
      	$vTMP = $vVend["nrag_vend"];
      	$aTMP = $listas->getAgencia($vTMP);
      	print ' ('.$aTMP[0]["nome_agbb"].')';
      } ?></b>
    </td>
  </tr>
</table>

<?php if($kVend==0){ ?>
	<table cellpadding=0 cellspacing=5 border=0 class="tb_dets_list" style="margin-top:5px;">
		<colgroup><col width="180" /><col /></colgroup>
		<tr>
			<td align="right" valign="top">Despachante:<?php $utils->obrig('cod_despachante_vend'); ?></td>
			<td align="left"  valign="top"><b><?php
				$vTMP = $vVend["despachante_vend"];
				$aTMP = $oUsuario->getUsuario($vTMP);
				print ($aTMP[0]["nome_usua"]!='')?$aTMP[0]["nome_usua"]:'Nenhum despachante associado';
			?></b></td>
		</tr>
	</table>
<?php } ?>

<?php
	$just_display = ($cLOGIN->iLEVEL_USUA == TPUSER_PROPONENTE)?false:true;
	if($vVend["tipo_vend"]=='1'){
		?><div id="ckls_vnpf"><?php include('bl_ckls_vendedor_pf.inc.php'); ?></div><?php
	}elseif($vVend["tipo_vend"]=='2'){
		?><div id="ckls_vnpj"><?php include('bl_ckls_vendedor_pj.inc.php'); ?></div><?php
	}
?>

<?php if($vVend["vendfis"][0]["cod_estciv"]=='2'||$vVend["vendfis"][0]["cod_estciv"]=='99'){ ?>
<div id="div_conjuje_vend" class="grupoDados">
	<b>Dados do Cônjuge</b>
	<table cellpadding=0 cellspacing=5 border=0 class="tb_dets_list" style="margin-top:5px;">
		<colgroup><col width="180" /><col /></colgroup>
		<tr>
			<td align="right" valign="top">Nome:</td>
			<td align="left"  valign="top"><b><?php echo $vVend["vendfisconjuge"][0]["nome_vfcj"];?></b></td>
		</tr>
		<tr>
			<td align="right" valign="top">Nacionalidade:</td>
			<td align="left"  valign="top"><b><?php echo $vVend["vendfisconjuge"][0]["cod_pais"];?></b></td>
		</tr>
		<tr>
			<td align="right" valign="top">RG:</td>
			<td align="left"  valign="top">
				<b><?php echo $vVend["vendfisconjuge"][0]["nrrg_vfcj"];?></b> &nbsp;&nbsp;
				Emissão: <b><?php echo $utils->formataDataBRA($vVend["vendfisconjuge"][0]["dtrg_vfcj"]);?></b> &nbsp;&nbsp;
				Órgão Emissor: <b><?php echo $vVend["vendfisconjuge"][0]["orgrg_vfcj"];?></b>
			</td>
		</tr>
		<tr>
			<td align="right" valign="top">CPF:</td>
			<td align="left"  valign="top"><b><?php echo $utils->formataCPF($vVend["vendfisconjuge"][0]["cpf_pccj"]);?></b></td>
		</tr>
		<tr>
			<td align="right" valign="top">Trabalha atualmente:</td>
			<td align="left"  valign="top"><b><?php
      	$vTMP = $vVend["vendfisconjuge"][0]["flgtrabalha_vfcj"];
      	$aTMP = $listas->getListaSN();
      	print $aTMP[$vTMP];
			?></b></td>
		</tr>
		<?php if($vVend["vendfisconjuge"][0]["flgtrabalha_vfcj"]=='S'){ ?>
		<tr>
			<td align="left" valign="top" colspan="2" style="padding-top:10px;"><b>Dados da Empresa do Cônjuge</b></td>
		</tr>
		<tr>
			<td align="right" valign="top">Empresa:</td>
			<td align="left"  valign="top"><b><?php echo $vVend["vendfisconjuge"][0]["empresa_vfcj"];?></b></td>
		</tr>
		<tr>
			<td align="right" valign="top">Data de Admissão:</td>
			<td align="left"  valign="top"><b><?php echo $utils->formataDataBRA($vVend["vendfisconjuge"][0]["dtadmissaoemp_vfcj"]);?></b></td>
		</tr>
    <tr>
      <td align="right" valign="top">Endereço:</td>
      <td align="left"  valign="top"><b><?php echo $vVend["vendfisconjuge"][0]["enderecoemp_vfcj"];?></b></td>
    </tr>
    <tr>
      <td align="right" valign="top">Número:</td>
      <td align="left"  valign="top"><b><?php echo $vVend["vendfisconjuge"][0]["numeroemp_vfcj"];?></b></td>
    </tr>
    <tr>
      <td align="right" valign="top">Complemento:</td>
      <td align="left"  valign="top"><b><?php echo $vVend["vendfisconjuge"][0]["complementoemp_vfcj"];?></b></td>
    </tr>
    <tr>
      <td align="right" valign="top">Bairro:</td>
      <td align="left"  valign="top"><b><?php
      	$vTMP = $vVend["vendfisconjuge"][0]["bairroemp_vfcj"];
      	$aTMP = $listas->getListaBairro($vTMP);
      	print $aTMP[0]["nome_bairro"];
      ?></b></td>
    </tr>
    <tr>
      <td align="right" valign="top">Cidade:</td>
      <td align="left"  valign="top"><b><?php
      	$vUF  = $vVend["vendfisconjuge"][0]["estadoemp_vfcj"];
      	$vTMP = $vVend["vendfisconjuge"][0]["cidadeemp_vfcj"];
      	$aTMP = $listas->getListaMunicipio($vUF,$vTMP);
      	print $aTMP[0]["nome_municipio"];
      ?></b></td>
    </tr>
    <tr>
      <td align="right" valign="top">Estado:</td>
      <td align="left"  valign="top"><b><?php
      	$vTMP = $vVend["vendfisconjuge"][0]["estadoemp_vfcj"];
      	$aTMP = $listas->getListaUF($vTMP);
      	print $aTMP[0]["nome_uf"];
      ?></b></td>
    </tr>
		<tr>
			<td align="right" valign="top">Telefone:</td>
      <td align="left"  valign="top"><b><?php echo $utils->formataTelefone($vVend["vendfisconjuge"][0]["telefoneemp_vfcj"]);?></b></td>
		</tr>
		<tr>
			<td align="right" valign="top">Cargo:</td>
      <td align="left"  valign="top"><b><?php echo $vVend["vendfisconjuge"][0]["cargoemp_vfcj"];?></b></td>
		</tr>
		<tr>
			<td align="right" valign="top">Salário:</td>
      <td align="left"  valign="top"><b>R$ <?php echo $utils->formataMoeda($vVend["vendfisconjuge"][0]["salarioemp_vfcj"]);?></b></td>
		</tr>
		<?php } ?>
	</table>
	<div id="ckls_pfcj"><?php include('bl_ckls_vendedor_pfcj.inc.php'); ?></div>
</div>
<?php } ?>

<?php
	if($vVend["tipo_vend"]=='2'){
		if (is_array($vVend["vendjursocios"]) && @count($vVend["vendjursocios"]) > 0) {
			?><div id="div_pjs" class="grupoDados"><?php
			$sep = '';
			foreach($vVend["vendjursocios"] as $kSocio=>$vSocio){
				print $sep;
				include('bl_detalhes_socios.inc.php');
				$sep = '<hr class="sepHr">';
			}
			?></div><?php
		}
	}
?>