<?
$iREQ_AUT=1;
$aUSERS_PERM[]=2;
$pageTitle = "Proposta";
include "lib/header.inc.php";

$mensagem = new mensagens();

$acaoProposta = $crypt->decrypt($_POST["acaoProposta"]);

if($acaoProposta=='evento'){
	// novo evento // -------------------------------------------------------------
	$f_novo_evento = $_POST['novo_evento'];
	$cLOGIN->insert_log(4,7,'Inclusão de Evento - CODPPST:'.$_GET['cod_proposta']);
	$cLOGIN->insert_history($_GET['cod_proposta'],2,htmlentities($f_novo_evento));
	// ----------------------------------------------------------------------------
}

if(!empty($_GET["cod_proposta"])){
	$where_ppst = " AND p.cod_ppst = '".mysql_real_escape_string($_GET['cod_proposta'])."' ";
} else {
	$where_ppst = " AND u.cod_usua = '".mysql_real_escape_string($cLOGIN->iID)."' ";
}

$db->query="SELECT
			  			p.cod_ppst,
              u.nome_usua,
              u.email_usua,
              u.id_lstn,
              u.cod_usua,
              date_format(p.data_ppst,'%d/%m/%Y') as data_ppst,
              p.vlfinsol_ppst,
              p.przfinsol_ppst,
              p.vlprestsol_ppst,
              p.valordevsinalsol_ppst,
              p.situacao_ppst,
              p.pricesac_ppst,
              p.valorfgts_ppst,
              p.taxajuros_ppst,
              p.valorseguro_ppst,
              p.valormanutencao_ppst,
              p.proponente_ppst,
              p.indcancelamento_ppst,
							p.valorboletoaval_ppst,
							p.flgboletoavalpago_ppst,
							p.dtpagtoboleto_ppst,
							p.dtagend_asscontr_ppst,
							p.dtasscontrato_ppst,
							p.dtokregistro_ppst,
							p.dtremessacontrato_ppst,
							n.cpf_ppnt,
              date_format(n.dtnascimento_ppnt,'%d/%m/%Y') as dtnascimento_ppnt,
              n.cod_estciv,
              n.cod_logr,
              n.endereco_ppnt,
              n.nrendereco_ppnt,
              n.cpendereco_ppnt,
              n.cod_bairro,
              n.cod_uf,
              n.cod_municipio,
              n.cep_ppnt,
              n.telefone_ppnt,
              i.cod_uf as iuf,
							l.vlmaxfinan, l.parcmaxfinan,
							l.przmaxfinan, l.vlaprovado,
							l.parcaprovada, l.przaprovado,
							l.vlentraprovado
            FROM proposta p, usuario u, proponente n, imovel i, listadenomes l
            WHERE u.cod_usua = p.proponente_ppst
              AND p.proponente_ppst = n.cod_proponente 
              AND p.cod_ppst = i.cod_ppst
              AND u.id_lstn=l.id_lstn
            $where_ppst";

$db->query();
if($db->qrcount>0){
  $valorMaxFinan  = $db->qrdata[0]['vlmaxfinan'];
  $valorMaxPrest  = $db->qrdata[0]['parcmaxfinan'];
  $prazoMaxFinan  = $db->qrdata[0]['przmaxfinan'];
  $valorAprov     = $db->qrdata[0]['vlaprovado'];
  $parcelaAprov   = $db->qrdata[0]['parcaprovada'];
  $prazoAprov     = $db->qrdata[0]['przaprovado'];
  $entradaAprov   = $db->qrdata[0]['vlentraprovado'];
  
  $f_vlfinsol_ppst  = $db->qrdata[0]["vlfinsol_ppst"];
  $f_przfinsol_ppst = $db->qrdata[0]["przfinsol_ppst"];
  $f_prestacao_ppst = $db->qrdata[0]["vlprestsol_ppst"];
  $f_sinalsol_ppst  = $db->qrdata[0]["valordevsinalsol_ppst"];
  $f_pricesac_ppst  = $db->qrdata[0]["pricesac_ppst"];
  $f_valorfgts_ppst = $db->qrdata[0]["valorfgts_ppst"];
  $f_taxajuros_ppst = $db->qrdata[0]["taxajuros_ppst"];
  $f_seguro_ppst    = $db->qrdata[0]["valorseguro_ppst"];
  $f_manut_ppst     = $db->qrdata[0]["valormanutencao_ppst"];
  $f_cancel_ppst    = $db->qrdata[0]["indcancelamento_ppst"];

  $valor_boleto     = $db->qrdata[0]['valorboletoaval_ppst'];
  $flg_pgto_boleto  = $db->qrdata[0]['flgboletoavalpago_ppst'];
  $dt_pgto_boleto   = $db->qrdata[0]['dtpagtoboleto_ppst'];

  $imov_dt_regist   = $utils->formataDataBRA($db->qrdata[0]["dtokregistro_ppst"]);
  $agendamento_ppst = $utils->formataDataBRA($db->qrdata[0]["dtagend_asscontr_ppst"]);
  $assinatura_ppst  = $utils->formataDataBRA($db->qrdata[0]["dtasscontrato_ppst"]);
  $data_remessa     = $utils->formataDataBRA($db->qrdata[0]["dtremessacontrato_ppst"]);

  $f_valor_compra = $f_vlfinsol_ppst + $f_sinalsol_ppst + $f_valorfgts_ppst;
/*
  $prop_taxa      = $f_taxajuros_ppst; //floatval(str_replace(',','.',str_replace('.','',$f_taxajuros_ppst)));
  $taxa  = pow( (( $prop_taxa / 100 ) + 1), (1 / 12)) - 1;
  switch($f_pricesac_ppst){
    case '1':
    	$f_tabela = "Price";
    	$f_prestacao   = $utils->fPMT($taxa,$f_przfinsol_ppst,$f_vlfinsol_ppst);
    	$f_redMJ = 0;
    	break;
    case '2':
  		$f_tabela = "SAC";
      $amort = $f_vlfinsol_ppst / $f_przfinsol_ppst;
      $juros = ($f_vlfinsol_ppst - $amort) * $taxa;
      $f_prestacao = $juros + $amort;
      $f_redMJ = $juros / $f_przfinsol_ppst;
	  	break;
  }
*/
  $f_vlfinsol_ppst  = $utils->formataMoeda($f_vlfinsol_ppst);
  $f_valor_compra   = $utils->formataMoeda($f_valor_compra);
  $f_przfinsol_ppst = intval($f_przfinsol_ppst);
  $f_sinalsol_ppst  = $utils->formataMoeda($f_sinalsol_ppst);
  $f_valorfgts_ppst = $utils->formataMoeda($f_valorfgts_ppst);
  //$f_prestacao      = $utils->formataMoeda($f_prestacao);
  $f_prestacao_ppst = $utils->formataMoeda($f_prestacao_ppst);
  $f_redMJ          = $utils->formataMoeda($f_redMJ);
  $f_seguro_ppst    = $utils->formataMoeda($f_seguro_ppst);
  $f_manut_ppst     = $utils->formataMoeda($f_manut_ppst);
  
  $f_prestacao_ppst = ($f_przfinsol_ppst!='0')?'0,00':$f_prestacao_ppst;
  
  $cod_ppst    = $db->qrdata[0]["cod_ppst"];

  $f_cod_prop  = $db->qrdata[0]['proponente_ppst'];
  $f_situacao  = $db->qrdata[0]['situacao_ppst'];
  $f_nome      = $db->qrdata[0]["nome_usua"];
  $f_cod_usua  = $db->qrdata[0]["cod_usua"];
  $f_matricula = $db->qrdata[0]["id_lstn"];
  $f_email     = $db->qrdata[0]["email_usua"];
  $f_cpf       = $utils->formataCPF($db->qrdata[0]["cpf_ppnt"]);
  $f_dt_nasc   = $db->qrdata[0]["dtnascimento_ppnt"];
  $ecivil      = $db->qrdata[0]["cod_estciv"];
  $clogr       = $db->qrdata[0]["cod_logr"];
  $f_ender     = $db->qrdata[0]["endereco_ppnt"];
  $f_ender_nm  = $db->qrdata[0]["nrendereco_ppnt"];
  $f_ender_cpl = $db->qrdata[0]["cpendereco_ppnt"];
  $f_bairro    = $db->qrdata[0]["cod_bairro"];
  $f_uf        = $db->qrdata[0]["cod_uf"];
  $f_cidade    = $db->qrdata[0]["cod_municipio"];
  $f_cep       = $utils->formataCep($db->qrdata[0]["cep_ppnt"]);
  $f_fone      = $utils->formataTelefone($db->qrdata[0]["telefone_ppnt"]);

  $f_idade = '';
  if($f_dt_nasc!=''){ $f_idade = $utils->idade($f_dt_nasc).' anos'; }
  $ecivil      = $forms->getECivil($ecivil);
	$f_ecivil    = strtolower($ecivil[0]['desc_estciv']);
	$clogr       = $forms->getLogr($clogr);
	$f_lograd    = $clogr[0]['desc_logr'];
	$f_ender_cpl = (trim($f_ender_cpl)=='')?'':' - '.$f_ender_cpl;
	$f_bairro    = $forms->getBairro($f_bairro);
	$f_bairro    = $f_bairro[0]['nome_bairro'];
	$f_uf        = $forms->getUF($f_uf);
	$f_uf        = $f_uf[0]['nome_uf'];
	$f_cidade    = $forms->getMunicipios('',$f_cidade);
	$f_cidade    = $f_cidade[0]['nome_municipio'];
	$f_end_compl  = '';
	$f_end_compl .= $f_lograd.' '.$f_ender.', '.$f_ender_nm.$f_ender_cpl.'<br>';
	$f_end_compl .= $f_bairro.', '.$f_cidade.' - '.$f_uf.'<br>CEP: '.$f_cep;
	
	$f_v_logr    = $forms->getLogr($v_logr);
	$f_v_logr    = $f_v_logr[0]['desc_logr'];
	$f_v_compl   = (trim($v_compl)=='')?'':' - '.$v_compl;
	$f_v_bairro  = $forms->getBairro($v_bairro);
	$f_v_bairro  = $f_v_bairro[0]['nome_bairro'];
	$f_v_uf      = $forms->getUF($v_uf);
	$f_v_uf      = $f_v_uf[0]['nome_uf'];
	$f_v_cidade  = $forms->getMunicipios('',$v_cidade);
	$f_v_cidade  = $f_v_cidade[0]['nome_municipio'];
	$f_v_end_compl  = '';
	$f_v_end_compl .= $f_v_logr.' '.$v_ender.', '.$v_num.$f_v_compl.'<br>';
	$f_v_end_compl .= $f_v_bairro.', '.$f_v_cidade.' - '.$f_v_uf.'<br>CEP: '.$v_cep;
	
	$i_uf        = $db->qrdata[0]["iuf"];
	$i_uf        = $forms->getUF($i_uf);
	$i_uf        = trim($i_uf[0]['nome_uf']);
	
  $flg_pgto_boleto  = ($flg_pgto_boleto=='' )?'N':$flg_pgto_boleto;
  $dt_pgto_boleto  = ($flg_pgto_boleto=='N')?'':$dt_pgto_boleto;
  $valor_boleto    = ($valor_boleto=='')?0:$valor_boleto;
  $f_dt_pgto_boleto = $utils->formataDataBRA($dt_pgto_boleto);

	if($i_uf!='' && $flg_pgto_boleto=='N'){
		$valor_boleto = $oParametros->listaValoresBoleto($i_uf);
	}
	
	$valor_boleto = $utils->formataMoeda($valor_boleto);
	
	$mensagem = new mensagens();
	if($f_situacao > 5 && $f_situacao < 12){
		$mensagem->setMensagem('Esta proposta está em fase de '.$_SESSION["prop_status"][$f_situacao].', disponível apenas para consulta.', MSG_ALERTA);
	}elseif($f_situacao == 12){
		$mensagem->setMensagem('Esta proposta está Concluída, disponível apenas para consulta.', MSG_SUCESSO);
	}

	
	if($f_cancel_ppst != ''){
		$mensagem->setMensagem('Esta proposta foi cancelada por '.$_SESSION["indic_cancel"][$f_cancel_ppst].', disponível apenas para consulta.', MSG_ERRO);
	}
	
	$resultMessage = '';
	if($valorAprov!='' && $valorAprov!=0){
		$resultMessage .= '<u><b>Valores aprovados pela Previ</b></u><br>';
		$resultMessage .= 'Financiamento: <b>R$ '.$utils->formataMoeda($valorAprov).'</b><br>';
		$resultMessage .= 'Parcela: <b>R$ '.$utils->formataMoeda($parcelaAprov).'</b><br>';
		$resultMessage .= 'Prazo: <b>'.$prazoAprov.' meses</b><br>';
		//$resultMessage .= 'Entrada: <b>R$ '.$utils->formataMoeda($entradaAprov).'</b><br>';
	}
	?>
		<div class="alr"><a href="#historico"><img src="images/buttons/bt_historico.gif" alt="Ver Histórico" class="im" /></a></div>

		<?
	    ### LIMITES ##################################################################### 
			$db->query="select l.vlmaxfinan, l.parcmaxfinan, l.przmaxfinan 
			            from usuario u,  listadenomes l
			            where u.id_lstn=l.id_lstn and u.cod_usua='".mysql_real_escape_string($f_cod_usua)."' ";
			$db->query();
			if($db->qrcount>0){
			  $valorMaxFinan  = $utils->formataMoeda($db->qrdata[0]['vlmaxfinan']);
			  $valorMaxPrest  = $utils->formataMoeda($db->qrdata[0]['parcmaxfinan']);
			  $prazoMaxFinan  = intval($db->qrdata[0]['przmaxfinan']);
			}
	  ?>
 		<br><?=$mensagem->getMessageBox();?>
	  <b>Limites</b>
		<div class="quadroInterno">
			<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
			<div class="quadroInternoMeio">
	        <table cellpadding=0 cellspacing=5 border=0>
	          <tr>
	            <td align="right">Valor Máximo para Financiamento:</td>
	            <td align="left"><b>R$ <?=$valorMaxFinan;?></b></td>
	          </tr>
	          <tr>
	            <td align="right">Valor Máximo por Prestação:</td>
	            <td align="left"><b>R$ <?=$valorMaxPrest;?></b></td>
	          </tr>
	          <tr>
	            <td align="right">Prazo Máximo do Financiamento:</td>
	            <td align="left"><b><?=$prazoMaxFinan;?> meses</b></td>
	          </tr>
	        </table>
			</div>
			<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
		</div>

	  <?
	    ### PROPOSTA ##################################################################### 
	  ?>
		<br><b>Proposta</b>
		<div class="quadroInterno">
			<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
			<div class="quadroInternoMeio">
				<div style="float: left; width:400px;">
	    		<table cellpadding=0 cellspacing=5 border=0>
		  			<tr style="display:none;">
		  			  <td align="right" valign="top">Tabela:</td><td align="left"><b><?=$f_tabela;?></b></td>
		  			</tr>
		  			<tr>
		  			  <td align="right" valign="top">Compra:</td><td align="left"><b>R$ <?=$f_valor_compra;?></b></td>
		  			</tr>
		  			<tr>
		  			  <td align="right" valign="top">Entrada:</td><td align="left"><b>R$ <?=$f_sinalsol_ppst;?></b></td>
		  			</tr>
		  			<tr>
		  			  <td align="right" valign="top">FGTS:</td><td align="left"><b>R$ <?=$f_valorfgts_ppst;?></b></td>
		  			</tr>
		  			<tr>
		  			  <td align="right" valign="top">Financiamento:</td><td align="left"><b>R$ <?=$f_vlfinsol_ppst;?></b></td>
		  			</tr>
		  			<tr style="display:none;">
		  			  <td align="right" valign="top">Taxa de Juros:</td><td align="left"><b><?=$f_taxajuros_ppst;?>%</b></td>
		  			</tr>
		  			<? if($f_prestacao_ppst!='0,00'){ ?>
		  			<tr>
		  			  <td align="right" valign="top">Prestação:</td><td align="left"><b>R$ <?=$f_prestacao_ppst;?></b></td>
		  			</tr>
		  			<? } ?>
		  			<? if($f_przfinsol_ppst!='0'){ ?>
		  			<tr>
		  			  <td align="right" valign="top">Prazo:</td><td align="left"><b><?=$f_przfinsol_ppst;?> meses</b></td>
		  			</tr>
		  			<? } ?>
		  			<tr>
		  			  <td align="right" valign="top">Seguro:</td><td align="left"><b>R$ <?=$f_seguro_ppst;?></b></td>
		  			</tr>
		  			<tr>
		  			  <td align="right" valign="top">Taxa Manutenção:</td><td align="left"><b>R$ <?=$f_manut_ppst;?></b></td>
		  			</tr>
	    		</table>
	    	</div>
				<? if ($resultMessage) { ?>
					<div class="warning" id="divResultadoProposta" style="border:1px solid #DDDDDD; background-color: #F5F5F5; padding: 20px; float:left; width:200px;"><? echo $resultMessage; ?></div>
				<? } ?>
			</div>
			<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
		</div>

		<?
	    ### DADOS DO BOLETO ##################################################################### 
	    if($i_uf!=''){
	  		?>
				<br><b>Pagamento</b>
				<div class="quadroInterno">
					<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
					<div class="quadroInternoMeio">
				    		<table cellpadding=0 cellspacing=5 border=0 width="100%">
				    			<colgroup><col width="150" /><col width="*" /></colgroup>
				    			<tr>
				    			  <td align="right" valign="top">Valor do Boleto:</td>
				    			  <td align="left"  valign="top"><b>R$ <?=$valor_boleto;?></b></td>
				    			  <? if($f_situacao <= 5){ ?>
				    			  <td align="right" rowspan="2" valign="bottom"><img src="images/buttons/bt_gerar_boleto.gif" alt="Gerar Boleto" class="im" onClick="gerarBoleto('<?=$_GET['cod_proposta'];?>');" /></td>
				    			  <? } ?>
				    			</tr>
				    			<tr>
				    			  <td align="right" valign="top">Data de Pagamento:</td>
				    			  <td align="left"  valign="top"><b><?=$f_dt_pgto_boleto;?></b></td>
				    			</tr>
				    		</table>
					</div>
					<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
				</div>
				<?
	    }
	  ?>
	  
		<?
	    ### DADOS DO PROPONENTE ##################################################################### 
	  ?>
	  <br><b>Dados do Proponente</b>
		<div class="quadroInterno">
			<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
			<div class="quadroInternoMeio">
		    		<table cellpadding=0 cellspacing=5 border=0>
		    			<tr>
		    			  <td align="right" valign="top">Nome:</td><td align="left"><b><?=$f_nome;?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Matricula:</td><td align="left"><b><?=$f_matricula;?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">E-Mail:</td><td align="left"><b><?=$f_email;?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">CPF:</td><td align="left"><b><?=$f_cpf;?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Data Nasc:</td><td align="left"><b><?=$f_dt_nasc;?> (<?=$f_idade;?>)</b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Est Civil:</td><td align="left"><b><?=$f_ecivil;?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Endereço:</td><td align="left"><b><?=$f_end_compl;?></b></td>
		    			</tr>
			  			<tr>
			  			  <td align="right" valign="top">Telefone:</td><td align="left"><b><?=$f_fone;?></b></td>
			  			</tr>
		    		</table>
			</div>
			<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
		</div>
		    	
	  <?
	    ### DADOS DO IMÓVEL ##################################################################### 
		  $db->query="select * from imovel where cod_ppst = '".mysql_real_escape_string($cod_ppst)."' ";
		  $db->query();
			if($db->qrcount>0){
		    $imov_tipo      = $db->qrdata[0]['TIPO_IMOV'];
		    $imov_constr    = $db->qrdata[0]['TPCONSTRUCAO_IMOV'];
		    $imov_cond      = $db->qrdata[0]['TPCONDOMINIO_IMOV'];
		    $f_i_logr       = $db->qrdata[0]['COD_LOGR'];
		    $f_i_qsala      = $db->qrdata[0]['QTSALA_IMOV'];
		    $f_i_qquart     = $db->qrdata[0]['QTQUARTO_IMOV'];
		    $f_i_qbnah      = $db->qrdata[0]['QTBANH_IMOV'];
		    $f_i_qgarag     = $db->qrdata[0]['QTGARAG_IMOV'];
		    $f_i_qpavim     = $db->qrdata[0]['QTPAVIM_IMOV'];
		    $f_i_qdpemp     = $db->qrdata[0]['QTDEPEMP_IMOV'];
		    $imov_ender     = $db->qrdata[0]['ENDERECO_IMOV'];
		    $imov_num       = $db->qrdata[0]['NRENDERECO_IMOV'];
		    $f_i_ender_c    = $db->qrdata[0]['CPENDERECO_IMOV'];
		    $f_i_bairro     = $db->qrdata[0]['COD_BAIRRO'];
		    $f_i_uf         = $db->qrdata[0]['COD_UF'];
		    $f_i_cidade     = $db->qrdata[0]['COD_MUNICIPIO'];
		    $imov_cep       = $db->qrdata[0]['CEP_IMOV'];
		
		    $imov_area      = $db->qrdata[0]['AREA_IMOV'];
		    $imov_cons_imov = $db->qrdata[0]['ESTCONSERV_IMOV'];
		    $imov_cons_pred = $db->qrdata[0]['ESTCONSPRED_IMOV'];
		    $imov_cartr_rgi = $db->qrdata[0]['NOMECARTRGI_IMOV'];
		    $imov_matrc_rgi = $db->qrdata[0]['NRMATRGI_IMOV'];
		    $imov_livro_rgi = $db->qrdata[0]['NRLIVRGI_IMOV'];
		    $imov_folhs_rgi = $db->qrdata[0]['NRFOLHRGI_IMOV'];
		    $imov_rg_cprvnd = $db->qrdata[0]['NRRGCOMPVEND_IMOV'];
		    $imov_rg_garant = $db->qrdata[0]['NRRGGAR_IMOV'];
		    $imov_tp_impst  = $db->qrdata[0]['TPIMPOSTO_IMOV'];
		    
		    $imov_vl_aval   = $db->qrdata[0]['VLAVALIACAO_IMOV'];
		    $imov_dt_aval   = $db->qrdata[0]['DTAVALIACAO_IMOV'];
		    $imov_dt_aprov  = $db->qrdata[0]['DTAPROVACAO_IMOV'];
		    
		    $imov_tp_morad  = $db->qrdata[0]['TPMORADIA_IMOV'];
		    $imov_terreo    = $db->qrdata[0]['TERREO_IMOV'];
		    $imov_tb_dp_cnd = $db->qrdata[0]['TMBDSPCNDOP_IMOV'];
		    $imov_incomb    = $db->qrdata[0]['INCOMB_IMOV'];
		    $imov_rural_fav = $db->qrdata[0]['RURALFAV_IMOV'];
		    $imov_em_constr = $db->qrdata[0]['EMCONSTR_IMOV'];
		    
		    $imov_cep      = $utils->formataCEP($imov_cep);
				$imov_area     = $utils->formataFloat($imov_area,2);

				$f_i_logr    = $forms->getLogr($f_i_logr);
				$f_i_logr    = $f_i_logr[0]['desc_logr'];
				$f_i_ender_c = (trim($f_i_ender_c)=='')?'':' - '.$f_i_ender_c;
				$f_i_bairro  = $forms->getBairro($f_i_bairro);
				$f_i_bairro  = $f_i_bairro[0]['nome_bairro'];
				$f_i_uf      = $forms->getUF($f_i_uf);
				$f_i_uf      = $f_i_uf[0]['nome_uf'];
				$f_i_cidade_cod = $f_i_cidade;
				$f_i_cidade  = $forms->getMunicipios('',$f_i_cidade);
				$f_i_cidade  = $f_i_cidade[0]['nome_municipio'];
				$f_i_end_compl  = '';
				$f_i_end_compl .= $f_i_logr.' '.$imov_ender.', '.$imov_num.$f_i_ender_c.'<br>';
				$f_i_end_compl .= $f_i_bairro.', '.$f_i_cidade.' - '.$f_i_uf.'<br>CEP: '.$imov_cep;
				
				$f_i_quantidades  = '';
				$f_i_quantidades .= ($f_i_qsala) ?'<b>'.$f_i_qsala .'</b> Salas<br>':'';
				$f_i_quantidades .= ($f_i_qquart)?'<b>'.$f_i_qquart.'</b> Quartos<br>':'';
				$f_i_quantidades .= ($f_i_qbnah) ?'<b>'.$f_i_qbnah .'</b> Banheiros<br>':'';
				$f_i_quantidades .= ($f_i_qgarag)?'<b>'.$f_i_qgarag.'</b> Garagens<br>':'';
				$f_i_quantidades .= ($f_i_qpavim)?'<b>'.$f_i_qpavim.'</b> Pavimentos<br>':'';
				$f_i_quantidades .= ($f_i_qdpemp)?'<b>'.$f_i_qdpemp.'</b> Dep Empreg<br>':'';

    		$imov_vl_aval   = $utils->formataMoeda($imov_vl_aval);
		    $imov_dt_aval   = $utils->formataDataBRA($imov_dt_aval);
		    $imov_dt_aprov  = $utils->formataDataBRA($imov_dt_aprov);
		    
				$imov_quantidades = '';
		  }
	  ?>
	  <br><b>Dados do Imóvel</b>
		<div class="quadroInterno">
			<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
			<div class="quadroInternoMeio">
    		<table cellpadding=0 cellspacing=5 border=0><tr>
    			<td valign="top">
		    		<table cellpadding=0 cellspacing=5 border=0>
		    			<tr>
		    			  <td align="right" valign="top">Área do Imóvel:</td><td align="left"><b><?=$imov_area;?> m²</b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Tipo de Imposto:</td><td align="left"><b><?=$imov_tp_impst;?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Tipo do Imóvel:</td><td align="left"><b><?=$aTIP_IMOVEL[$imov_tipo];?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Tipo Construção:</td><td align="left"><b><?=$aTIP_CONSTR[$imov_constr];?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Tipo Condomínio:</td><td align="left"><b><?=$aTIP_CONDOM[$imov_cond];?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Estado Conservação Imóvel:</td><td align="left"><b><?=$aTIP_CONSERV[$imov_cons_imov];?></b></td>
		    			</tr>
		    			<? if($imov_tipo=='E') { ?>
		    			<tr>
		    			  <td align="right" valign="top">Estado Conservação Prédio:</td><td align="left"><b><?=$aTIP_CONSERV[$imov_cons_pred];?></b></td>
		    			</tr>
		    			<? } ?>
			    	</table>
			    </td>
			    <td width="40">&nbsp;</td>
			    <td valign="top">
			    	<table cellpadding=0 cellspacing=5 border=0>
		    			<tr>
		    			  <td align="right" valign="top"></td><td align="left"><?=$f_i_quantidades;?></td>
		    			</tr>
		    		</table>
			    </td>
<? /*
			    <td width="20">&nbsp;</td>
			    <td valign="top">
			    	<table cellpadding=0 cellspacing=5 border=0>
		    			<tr>
		    			  <td align="right" valign="top">Nome Cartório (RGI):</td><td align="left"><b><?=$imov_cartr_rgi;?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Num matrícula do registro imóvel:</td><td align="left"><b><?=$imov_matrc_rgi;?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Num livro do registro imóvel:</td><td align="left"><b><?=$imov_livro_rgi;?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Num folhas do registro imóvel:</td><td align="left"><b><?=$imov_folhs_rgi;?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Num registro compra e venda:</td><td align="left"><b><?=$imov_rg_cprvnd;?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Num registro da garantia:</td><td align="left"><b><?=$imov_rg_garant;?></b></td>
		    			</tr>
		    		</table>
			    </td>
*/ ?>
				</tr></table>

				<table cellpadding=0 cellspacing=5 border=0>
    			<tr>
    			  <td align="right" valign="top">Endereço:</td><td align="left"><b><?=$f_i_end_compl;?></b></td>
    			</tr>
    		</table>

    		<table cellpadding=0 cellspacing=5 border=0><tr>
			    <td valign="top">
			    	<table cellpadding=0 cellspacing=5 border=0>
		    			<tr>
		    			  <td align="right" valign="top">Imóvel Térreo:</td><td align="left"><b><?=$aTIP_TERREO[$imov_terreo];?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Tipo de Moradia:</td><td align="left"><b><?=$aTIP_MORADIA[$imov_tp_morad];?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Imóvel tombado, desapropriado ou condenado por órgão público:</td><td align="left"><b><?=$aTIP_SN[$imov_tb_dp_cnd];?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Imóvel incombustível:</td><td align="left"><b><?=$aTIP_SN[$imov_incomb];?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Imóvel localizado em área rural ou favela:</td><td align="left"><b><?=$aTIP_SN[$imov_rural_fav];?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Imóvel em construção:</td><td align="left"><b><?=$aTIP_SN[$imov_em_constr];?></b></td>
		    			</tr>
			    	</table>
			    </td>
			  </tr></table>
			  
	    	<hr>
		      <table cellpadding=0 cellspacing=5 border=0>
		        <tr>
		          <td align="right" valign="top">Data de Registro:<?=$obrig;?></td>
		          <td align="left"  valign="top"><b><?=$imov_dt_regist;?></b></td>
		        </tr>
		    			
		      	<tr>
		          <td align="right" valign="top">Nome Cartório (RGI):</td>
		          <td align="left"  valign="top"><b><?=$imov_cartr_rgi;?></b></td>
		        </tr>
		        
		      	<tr>
		          <td align="right" valign="top">Num matrícula do registro imóvel:</td>
		          <td align="left"  valign="top"><b><?=$imov_matrc_rgi;?></b></td>
		        </tr>
		        
		      	<tr>
		          <td align="right" valign="top">Num livro do registro imóvel:</td>
		          <td align="left"  valign="top"><b><?=$imov_livro_rgi;?></b></td>
		        </tr>
		        
		      	<tr>
		          <td align="right" valign="top">Num folhas do registro imóvel:</td>
		          <td align="left"  valign="top"><b><?=$imov_folhs_rgi;?></b></td>
		        </tr>
		        
		      	<tr>
		          <td align="right" valign="top">Num registro compra e venda:</td>
		          <td align="left"  valign="top"><b><?=$imov_rg_cprvnd;?></b></td>
		        </tr>
		        
		      	<tr>
		          <td align="right" valign="top">Num registro da garantia:</td>
		          <td align="left"  valign="top"><b><?=$imov_rg_garant;?></b></td>
		        </tr>
		      </table>
	      
	      <hr>
			  <table cellpadding=0 cellspacing=5 border=0>
    			<tr>
    			  <td align="right" valign="top">Avaliação do Imóvel:</td>
    			  <td align="left"><b>R$ <?=$imov_vl_aval;?></b></td>
    			</tr>
    			<tr>
    			  <td align="right" valign="top">Data da Avaliação do Imóvel:</td>
    			  <td align="left"><b><?=$imov_dt_aval;?></b></td>
    			</tr>
    			<tr>
    			  <td align="right" valign="top">Data de Aprovação do Imóvel:</td>
    			  <td align="left"><b><?=$imov_dt_aprov;?></b></td>
    			</tr>
    		</table>
		    
			</div>
			<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
		</div>
			
		<?
	    ### DADOS DO VENDEDOR ##################################################################### 
		  $db->query="select *
		  						from vendedor v
			  						left join vendjur vj on v.cod_ppst = vj.cod_ppst
			  						left join vendfis vf on v.cod_ppst = vf.cod_ppst
		  						where v.cod_ppst = '".mysql_real_escape_string($cod_ppst)."' ";
		  $db->query();
		  if($db->qrcount>0){
		  	$vend_tipo   = $db->qrdata[0]['TIPO_VEND'];
			  $vend_nome   = $db->qrdata[0]['NOME_VEND'];
			  $vend_nick   = $db->qrdata[0]['NICK_VEND'];
			  $vend_logr   = $db->qrdata[0]['COD_LOGR'];
			  $vend_ender  = $db->qrdata[0]['ENDERECO_VEND'];
			  $vend_num    = $db->qrdata[0]['NRENDERECO_VEND'];
			  $vend_compl  = $db->qrdata[0]['CPENDERECO_VEND'];
			  $vend_uf     = $db->qrdata[0]['COD_UF'];
			  $vend_cidade = $db->qrdata[0]['COD_MUNICIPIO'];
			  $vend_bairro = $db->qrdata[0]['COD_BAIRRO'];
			  $vend_cep    = $utils->formataCep($db->qrdata[0]['CEP_VEND']);
			  $vend_fone   = $utils->formataTelefone($db->qrdata[0]['TELEFONE_VEND']);
			  $vend_nrcc   = $db->qrdata[0]['NRCC_VEND'];
			  $vend_dvcc   = $db->qrdata[0]['DVCC_VEND'];
			  $vend_nrag   = $db->qrdata[0]['NRAG_VEND'];
			  
			  $vf_cpf     = $db->qrdata[0]['CPF_VFISICA'];
			  $vf_sexo    = $db->qrdata[0]['SEXO_VFISICA'];
			  $vf_nasc    = $db->qrdata[0]['DTNASCIMENTO_VFISICA'];
			  $cpais      = $forms->getPais($db->qrdata[0]['COD_PAIS']);
			  $vf_nacion  = $cpais[0]['nome_pais'];
			  $vf_natural = $db->qrdata[0]['NATUR_VFISICA'];
			  $ctipdoc    = $forms->getTpDoc($db->qrdata[0]['COD_TPDOC']);
			  $vf_tpdoc   = $ctipdoc[0]['desc_tpdoc'];
			  $vf_rg      = $db->qrdata[0]['NRRG_VFISICA'];
			  $vf_dtrg    = $db->qrdata[0]['DTRG_VFISICA'];
			  $vf_orgrg   = $db->qrdata[0]['ORGRG_VFISICA'];
			  $cecivil    = $forms->getECivil($db->qrdata[0]['COD_ESTCIV']);
			  $vf_civil   = $cecivil[0]['desc_estciv'];
			  $vf_nconj   = $db->qrdata[0]['NOMECONJ_VFISICA'];
			  $vf_npai    = $db->qrdata[0]['NOMEPAI_VFISICA'];
			  $vf_nmae    = $db->qrdata[0]['NOMEMAE_VFISICA'];
			  $cprof      = $forms->getProfissao($db->qrdata[0]['COD_PROF']);
			  $vf_profiss = $cprof[0]['desc_prof'];
			  $vf_rendim	=	$db->qrdata[0]['VLRENDA_VFISICA'];
			  $vf_rendim  = $utils->formataMoeda($vf_rendim);
			  $vf_inss    = $db->qrdata[0]['NRINSS_VFISICA'];
			  
			  $vf_cpf          = $utils->formataCPF($vf_cpf);
			  $vf_nasc         = $utils->formataDataBRA($vf_nasc);
			  $vf_rg           = $utils->formataRG($vf_rg);
			  $vf_dtrg         = $utils->formataDataBRA($vf_dtrg);
			  $vf_idade        = $utils->idade($vf_nasc).' anos';
			  
			  $vj_cnpj     = $utils->formataCnpj($db->qrdata[0]['CNPJ_VJUR']);
			  $vj_pispasep = $db->qrdata[0]['ISENPIS_VJUR'];
			  $vj_cofins   = $db->qrdata[0]['ISENCOFINS_VJUR'];
			  $vj_csll     = $db->qrdata[0]['ISENCSLL_VJUR'];
			  $ccnae       = $forms->getAtvEcon($db->qrdata[0]['COD_CNAE']);
			  $vj_cnae     = $ccnae[0]['desc_cnae'];

			  $clogr        = $forms->getLogr($vend_logr);
				$vend_logr    = $clogr[0]['desc_logr'];
				$vend_compl   = (trim($vend_compl)=='')?'':' - '.$vend_compl;
				$cbairro      = $forms->getBairro($vend_bairro);
				$vend_bairro  = $cbairro[0]['nome_bairro'];
				$cuf          = $forms->getUF($vend_uf);
				$vend_uf      = $cuf[0]['nome_uf'];
				$ccidade      = $forms->getMunicipios('',$vend_cidade);
				$vend_cidade  = $ccidade[0]['nome_municipio'];

				$vend_endcompl  = '';
				$vend_endcompl .= $vend_logr.' '.$vend_ender.', '.$vend_num.$vend_compl.'<br>';
				$vend_endcompl .= $vend_bairro.', '.$vend_cidade.' - '.$vend_uf.'<br>CEP: '.$vend_cep;
		  }
	  ?>
	  <br><b>Dados do Vendedor</b>
		<div class="quadroInterno">
			<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
			<div class="quadroInternoMeio">
		    		<table cellpadding=0 cellspacing=5 border=0>
		    			<tr>
		    			  <td align="right" valign="top">Tipo:</td><td align="left"><b><?=$aTIP_VENDEDOR[$vend_tipo];?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Nome:</td><td align="left"><b><?=$vend_nome;?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Nome Abreviado:</td><td align="left"><b><?=$vend_nick;?></b></td>
		    			</tr>
	    			<? if($vend_tipo==1){ ?>
		    			<tr>
		    			  <td align="right" valign="top">CPF:</td><td align="left"><b><?=$vf_cpf;?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Sexo:</td><td align="left"><b><?=$aTIP_SEXO[$vf_sexo];?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Data Nasc:</td><td align="left"><b><?=$vf_nasc;?> (<?=$vf_idade;?>)</b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Nacionalidade:</td><td align="left"><b><?=$vf_nacion;?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Naturalidade:</td><td align="left"><b><?=$vf_natural;?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Doc de Identif:</td><td align="left"><b><?=$vf_tpdoc;?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">RG:</td><td align="left"><b><?=$vf_rg.' &nbsp; </b>Emissão:<b>'.$vf_dtrg.' &nbsp; </b>Órgão Emissor:<b>'.$vf_orgrg;?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Estado Civil:</td><td align="left"><b><?=$vf_civil;?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Nome do Cônjuje:</td><td align="left"><b><?=$vf_nconj;?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Nome do Pai:</td><td align="left"><b><?=$vf_npai;?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Nome do Mãe:</td><td align="left"><b><?=$vf_nmae;?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Profissão:</td><td align="left"><b><?=$vf_profiss;?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Renda:</td><td align="left"><b>R$ <?=$vf_rendim;?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Inscrição INSS:</td><td align="left"><b><?=$vf_inss;?></b></td>
		    			</tr>
	    			<? }elseif($vend_tipo==2){ ?>
		    			<tr>
		    			  <td align="right" valign="top">CNPJ:</td><td align="left"><b><?=$vj_cnpj;?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Isenção PIS-PASEP:</td><td align="left"><b><?=$aTIP_SN[$vj_pispasep];?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Isenção COFINS:</td><td align="left"><b><?=$aTIP_SN[$vj_cofins];?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Isenção CSLL:</td><td align="left"><b><?=$aTIP_SN[$vj_csll];?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Atividade Econômica:</td><td align="left"><b><?=$vj_cnae;?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="left" valign="top" colspan="2"><hr><b>Sócios</b></td></td>
		    			</tr>
	    				<?
							  $db->query="select * from vendjursocio where cod_ppst = '".mysql_real_escape_string($_GET['cod_proposta'])."' ";
							  $db->query();
							  if($db->qrcount>0){
					      	foreach($db->qrdata as $k=>$aSocio){
										$clogr        = $forms->getLogr($aSocio['COD_LOGR']);
										$ensoc_logr   = $clogr[0]['desc_logr'];
										$ensoc_cpl    = (trim($aSocio['CPENDERECO_VJSOC'])=='')?'':' - '.$aSocio['CPENDERECO_VJSOC'];
										$cbairro      = $forms->getBairro($aSocio['COD_BAIRRO']);
										$ensoc_bairro = $cbairro[0]['nome_bairro'];
										$cuf          = $forms->getUF($aSocio['COD_UF']);
										$ensoc_uf     = $cuf[0]['nome_uf'];
										$ccidade      = $forms->getMunicipios('',$aSocio['COD_MUNICIPIO']);
										$ensoc_cidade = $ccidade[0]['nome_municipio'];
										$ensoc_cep    = $utils->formataCep($aSocio['CEP_VJSOC']);
										$cpais        = $forms->getPais($aSocio['COD_PAIS']);
										$ensoc_pais   = $cpais[0]['nome_pais'];
				
										$endereco_s_completo  = '';
										$endereco_s_completo .= $ensoc_logr.' '.$aSocio['ENDERECO_VJSOC'].', '.$aSocio['NRENDERECO_VJSOC'].$ensoc_cpl.'<br>';
										$endereco_s_completo .= $ensoc_bairro.', '.$ensoc_cidade.' - '.$ensoc_uf.'<br>CEP: '.$ensoc_cep;
					      		
					      		?>
							        <tr>
							          <td align="right" valign="top">Nome:</td><td align="left"  valign="top"><b><?=$aSocio['NOME_VJSOC'];?></b></td>
							        </tr>
							        <tr>
							          <td align="right" valign="top">Nome abreviado:</td><td align="left"  valign="top"><b><?=$aSocio['NICK_VJSOC'];?></b></td>
							        </tr>
							        <tr>
							          <td align="right" valign="top">Endereço:</td><td align="left"  valign="top"><b><?=$endereco_s_completo;?></b></td>
							        </tr>
							        <tr>
							          <td align="right" valign="top">Telefone:</td><td align="left"  valign="top"><b><?=$utils->formataTelefone($aSocio['TELEFONE_VJSOC']);?></b></td>
							        </tr>
							        <tr>
							          <td align="right" valign="top">CPF:</td><td align="left"  valign="top"><b><?=$utils->formataCPF($aSocio['CPF_VJSOC']);?></b></td>
							        </tr>
							        <tr>
							          <td align="right" valign="top">Sexo:</td><td align="left"  valign="top"><b><?=$aTIP_SEXO[$aSocio['SEXO_VJSOC']];?></b></td>
							        </tr>
							        <tr>
							          <td align="right" valign="top">Nacionalidade:</td><td align="left"  valign="top"><b><?=$ensoc_pais;?></b></td>
							        </tr>
							        <tr><td colspan="2"><hr></td></tr>
										<?
					      	}
							  }
							?>

	    			<? } ?>
		    			<tr>
		    			  <td align="right" valign="top">Endereço:</td><td align="left"><b><?=$vend_endcompl;?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Telefone:</td><td align="left"><b><?=$vend_fone;?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Conta Corrente:</td><td align="left"><b><?=$vend_nrcc.'-'.$vend_dvcc;?></b> &nbsp; Agência:<b><?=$vend_nrag;?></b></td>
		    			</tr>
		      	</table>
			</div>
			<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
		</div>
		
		<?
	    ### DADOS DO DEVEDOR SOLIDÁRIO ##################################################################### 
		  $db->query="select * from devsol where cod_ppst = '".mysql_real_escape_string($cod_ppst)."' ";
		  $db->query();
		  if($db->qrcount>0){
			  $dsol_nome   = $db->qrdata[0]['NOME_DEVSOL'];
			  $dsol_nick   = $db->qrdata[0]['NICK_DEVSOL'];
			  $dsol_logr   = $db->qrdata[0]['COD_LOGR'];
			  $dsol_ender  = $db->qrdata[0]['ENDERECO_DEVSOL'];
			  $dsol_num    = $db->qrdata[0]['NRENDERECO_DEVSOL'];
			  $dsol_compl  = $db->qrdata[0]['CPENDERECO_DEVSOL'];
			  $dsol_uf     = $db->qrdata[0]['COD_UF'];
			  $dsol_cidade = $db->qrdata[0]['COD_MUNICIPIO'];
			  $dsol_bairro = $db->qrdata[0]['COD_BAIRRO'];
			  $dsol_cep    = $db->qrdata[0]['CEP_DEVSOL'];
			  $dsol_fone   = $db->qrdata[0]['TELEFONE_DEVSOL'];
			  $dsol_cpf    = $db->qrdata[0]['CPF_DEVSOL'];
			  $dsol_sexo   = $db->qrdata[0]['SEXO_DEVSOL'];
			  $dsol_nacion = $db->qrdata[0]['COD_PAIS'];
			  
			  $dsol_cpf   = $utils->formataCPF($dsol_cpf);
			  $dsol_fone  = $utils->formataTelefone($dsol_fone);
			  $dsol_cep   = $utils->formataCEP($dsol_cep);
			  

			  $clogr        = $forms->getLogr($dsol_logr);
				$dsol_logr    = $clogr[0]['desc_logr'];
				$dsol_compl   = (trim($dsol_compl)=='')?'':' - '.$dsol_compl;
				$cbairro      = $forms->getBairro($dsol_bairro);
				$dsol_bairro  = $cbairro[0]['nome_bairro'];
				$cuf          = $forms->getUF($dsol_uf);
				$dsol_uf      = $cuf[0]['nome_uf'];
				$ccidade      = $forms->getMunicipios('',$dsol_cidade);
				$dsol_cidade  = $ccidade[0]['nome_municipio'];
				$cpais        = $forms->getPais($dsol_nacion);
				$dsol_nacion  = $cpais[0]['nome_pais'];

				$dsol_endcompl  = '';
				$dsol_endcompl .= $dsol_logr.' '.$dsol_ender.', '.$dsol_num.$dsol_compl.'<br>';
				$dsol_endcompl .= $dsol_bairro.', '.$dsol_cidade.' - '.$dsol_uf.'<br>CEP: '.$dsol_cep;
		  }
	  ?>
	  <br><b>Dados do Devedor Solidário</b>
		<div class="quadroInterno">
			<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
			<div class="quadroInternoMeio">
		    		<table cellpadding=0 cellspacing=5 border=0>
		    			<tr>
		    			  <td align="right" valign="top">Nome:</td><td align="left"><b><?=$dsol_nome;?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Nome Abreviado:</td><td align="left"><b><?=$dsol_nick;?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Endereço:</td><td align="left"><b><?=$dsol_endcompl;?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Telefone:</td><td align="left"><b><?=$dsol_fone;?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">CPF:</td><td align="left"><b><?=$dsol_cpf;?></b></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Sexo:</td><td align="left"><b><?=$aTIP_SEXO[$dsol_sexo];?></b></td>
		    			</tr>
		    			<tr>
 		    			  <td align="right" valign="top">Nacionalidade:</td><td align="left"><b><?=$dsol_nacion;?></b></td>
		    			</tr>
		    		</table>			
			</div>
			<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
		</div>

	<?
    ### ASSINATURA DO CONTRATO ######################################################## 
  ?>
  <a name="assinatura"></a>
  <br><b>Assinatura do Contrato</b>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
			<table cellpadding=0 cellspacing=5 border=0>
	        <tr>
	          <td align="right" valign="top">Data do Agendamento:</td>
	          <td align="left"  valign="top"><b><?=$agendamento_ppst;?></b></td>
	        </tr>
	        <tr>
	          <td align="right" valign="top">Data da Assinatura do Contrato:</td>
	          <td align="left"  valign="top"><b><?=$assinatura_ppst;?></b></td>
	        </tr>
      </table>
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
	</div>
	
	<?
    ### ENVIO DE REMESSA ######################################################## 
  ?>
  <a name="envioremessa"></a>
  <br><b>Data de envio do Contrato e Matricula original à Previ</b>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
			<table cellpadding=0 cellspacing=5 border=0>
	        <tr>
	          <td align="right" valign="top">Data de envio:</td>
	          <td align="left"  valign="top"><b><?=$data_remessa;?></b></td>
	        </tr>
      </table>
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
	</div>
	
  <?
    ### Check List ##################################################################### 
     
    $db->query="select 
    				distinct(a.cod_docm),
    				a.nome_docm,
    				a.descr_docm,
    				a.validade_docm,
    				b.flgobrigatorio_mndc,    				
    				date_format(c.dtsolicitacao_clst,'%d/%m/%Y') as dtsolicitacao_clst,
    				date_format(c.dtprevisao_clst,'%d/%m/%Y') as dtprevisao_clst,
    				date_format(c.dtemissao_clst,'%d/%m/%Y') as dtemissao_clst,
    				IF(c.dtemissao_clst is not null,date_format(DATE_ADD(c.dtemissao_clst,interval a.validade_docm day),'%d/%m/%Y'),null) as dtvalidade_clst,
    				c.flgstatus_clst,
    				c.obs_clst
    			from 
    				municipiodocumento b,
    				documento a
    			left join
    				checklist c
    					on 
   							c.cod_docm=a.cod_docm 
   						and 
   							c.COD_PSST = '".mysql_real_escape_string($cod_ppst)."'
    			where 
    				a.cod_docm=b.cod_docm
    			  and
    				b.flgstatus_mndc = 1
    			  and
    				b.cod_municipio='".mysql_real_escape_string($f_i_cidade_cod)."'
    			  and
    				b.flgobrigatorio_mndc=1
    			order by
    				nome_docm";
    //echo("<pre>");
    //echo($db->query);
	//echo("</pre>");
    $db->query();
	$aDADOSDOCUMENTOS=$db->qrdata;

	
	
	$db->query="select 
    				distinct(a.cod_docm),
    				a.nome_docm,
    				a.descr_docm,
    				a.validade_docm,
    				b.flgobrigatorio_mndc,    				
    				date_format(c.dtsolicitacao_clst,'%d/%m/%Y') as dtsolicitacao_clst,
    				date_format(c.dtprevisao_clst,'%d/%m/%Y') as dtprevisao_clst,
    				date_format(c.dtemissao_clst,'%d/%m/%Y') as dtemissao_clst,
    				IF(c.dtemissao_clst is not null,date_format(DATE_ADD(c.dtemissao_clst,interval a.validade_docm day),'%d/%m/%Y'),null) as dtvalidade_clst,
    				c.flgstatus_clst,
    				c.obs_clst
    			from 
    				municipiodocumento b,
    				documento a
    			left join
    				checklist c
    					on 
   							c.cod_docm=a.cod_docm 
   						and 
   							c.COD_PSST = '".mysql_real_escape_string($cod_ppst)."'
    			where 
    				a.cod_docm=b.cod_docm
    			  and
    				b.flgstatus_mndc = 1
    			  and
    				b.cod_municipio='".mysql_real_escape_string($f_i_cidade_cod)."'
    			  and
    				b.flgobrigatorio_mndc=0
    			order by
    				nome_docm";
    //echo("<pre>");
    //echo($db->query);
	//echo("</pre>");
    $db->query();
	$aDADOSDOCUMENTOS_NO=$db->qrdata;
	
	
	
  ?>
  <a name="checklist"></a>
  <br><b>Check List</b>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
			<b>Documentos Obrigatórios</b>
			<div class="tListDiv">
				<table>
					<colgroup>
						<col width="25" />
						<col width="120" />
						<col width="120" />
						<col width="100" />
						<col width="100" />
						<col width="80" />
						<col />
					</colgroup>
					<thead>
						<tr>
							<td></td>
							<td>Documento</td>
							<td>Entidades</td>
							<td class="alc">Dt Pedido</td>
							<td class="alc">Dt Emissão</td>
							<td class="alc">Validade</td>
							<td class="alc">Observações</td>
						</tr>
					</thead>
					<tbody>
					<?
					if(count($aDADOSDOCUMENTOS)>0){
						for($i=0; $i<count($aDADOSDOCUMENTOS); $i++){
							?>
							<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
								<td class="alc"><input  disabled type="checkbox" name="ckl_doc_check[<?=$aDADOSDOCUMENTOS[$i]["cod_docm"];?>]" value="1" id="ckl_doc_check[<?=$aDADOSDOCUMENTOS[$i]["cod_docm"];?>]" <?=($aDADOSDOCUMENTOS[$i]["flgstatus_clst"]?"checked":"")?>></td>
								<td title="<?=$aDADOSDOCUMENTOS[$i]["descr_docm"];?>"><?=$aDADOSDOCUMENTOS[$i]["nome_docm"];?></td>
								<td>
									<ul style="padding:0px; margin:1px; margin-left:12px;">
									<?
									$db->query="select 
													a.cod_enti,
													a.nome_enti,
													a.descr_enti
												from 
													entidade a,
													municipiodocumento b
												where
													a.cod_enti=b.cod_enti
												and
													b.COD_MUNICIPIO='".mysql_real_escape_string($f_i_cidade_cod)."'
												and
													b.COD_DOCM='".mysql_real_escape_string($aDADOSDOCUMENTOS[$i]["cod_docm"])."'
												order by
													nome_enti";
									//echo($db->query);
									$db->query();
									for ($z=0; $z<$db->qrcount; $z++){
										echo("<li title=\"".$db->qrdata[$z]["descr_enti"]."\">".$db->qrdata[$z]["nome_enti"]."</li>");
									}
									?>
									</ul>
								</td>
								<td class="alc">
									<b><?=$aDADOSDOCUMENTOS[$i]["dtsolicitacao_clst"];?></b>
								</td>
								
								<td class="alc">
									<b><?=$aDADOSDOCUMENTOS[$i]["dtemissao_clst"];?></b>
								</td>
								<td class="alc"><?=$aDADOSDOCUMENTOS[$i]["validade_docm"];?> dias <?=($aDADOSDOCUMENTOS[$i]["dtvalidade_clst"]!=NULL?"<br> ".$aDADOSDOCUMENTOS[$i]["dtvalidade_clst"]:"");?></td> <? // <br>15/10/2007 ?>
								<td><textarea disabled style="width:150px; height:30px;" name="ckl_doc_desc[<?=$aDADOSDOCUMENTOS[$i]["cod_docm"];?>]" id="ckl_doc_desc[<?=$aDADOSDOCUMENTOS[$i]["cod_docm"];?>]"><?=$aDADOSDOCUMENTOS[$i]["obs_clst"];?></textarea></td>
							</tr>
							<?
						}
					} else {
						?>
						<tr class="tL2">
							<td colspan="7" align="center"> Não existem documentos cadastrados para o município do Imovel.</td>
						</tr>
						<?
					}
					?>
					</tbody>
				</table>
			</div>
			
			<br>
			<b>Documentos Não obrigatórios</b>
			<div class="tListDiv">
				<table>
					<colgroup>
						<col width="25" />
						<col width="120" />
						<col width="120" />
						<col width="100" />
						<col width="100" />
						<col width="80" />
						<col />
					</colgroup>
					<thead>
						<tr>
							<td></td>
							<td>Documento</td>
							<td>Entidades</td>
							<td class="alc">Dt Pedido</td>
							<td class="alc">Dt Emissão</td>
							<td class="alc">Validade</td>
							<td class="alc">Observações</td>
						</tr>
					</thead>
					<tbody>
					<?
					if(count($aDADOSDOCUMENTOS_NO)>0){
						for($i=0; $i<count($aDADOSDOCUMENTOS_NO); $i++){
							?>
							<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
								<td class="alc"><input disabled type="checkbox" name="ckl_doc_check[<?=$aDADOSDOCUMENTOS_NO[$i]["cod_docm"];?>]" value="1" id="ckl_doc_check[<?=$aDADOSDOCUMENTOS_NO[$i]["cod_docm"];?>]" <?=($aDADOSDOCUMENTOS_NO[$i]["flgstatus_clst"]?"checked":"")?>></td>
								<td title="<?=$aDADOSDOCUMENTOS_NO[$i]["descr_docm"];?>"><?=$aDADOSDOCUMENTOS_NO[$i]["nome_docm"];?></td>
								<td>
									<ul style="padding:0px; margin:1px; margin-left:12px;">
									<?
									$db->query="select 
													a.cod_enti,
													a.nome_enti,
													a.descr_enti
												from 
													entidade a,
													municipiodocumento b
												where
													a.cod_enti=b.cod_enti
												and
													b.COD_MUNICIPIO='".mysql_real_escape_string($f_i_cidade_cod)."'
												and
													b.COD_DOCM='".mysql_real_escape_string($aDADOSDOCUMENTOS_NO[$i]["cod_docm"])."'
												order by
													nome_enti";
									//echo($db->query);
									$db->query();
									for ($z=0; $z<$db->qrcount; $z++){
										echo("<li title=\"".$db->qrdata[$z]["descr_enti"]."\">".$db->qrdata[$z]["nome_enti"]."</li>");
									}
									?>
									</ul>
								</td>
								<td class="alc">
									<b><?=$aDADOSDOCUMENTOS_NO[$i]["dtsolicitacao_clst"];?></b>
								</td>
								
								<td class="alc">
									<b><?=$aDADOSDOCUMENTOS_NO[$i]["dtemissao_clst"];?></b>
								</td>
								<td class="alc"><?=$aDADOSDOCUMENTOS_NO[$i]["validade_docm"];?> dias <?=($aDADOSDOCUMENTOS_NO[$i]["dtvalidade_clst"]!=NULL?"<br> ".$aDADOSDOCUMENTOS_NO[$i]["dtvalidade_clst"]:"");?></td> <? // <br>15/10/2007 ?>
								<td><textarea disabled style="width:150px; height:30px;" name="ckl_doc_desc[<?=$aDADOSDOCUMENTOS_NO[$i]["cod_docm"];?>]" id="ckl_doc_desc[<?=$aDADOSDOCUMENTOS_NO[$i]["cod_docm"];?>]"><?=$aDADOSDOCUMENTOS_NO[$i]["obs_clst"];?></textarea></td>
							</tr>
							<?
						}
					} else {
						?>
						<tr class="tL2">
							<td colspan="7" align="center"> Não existem documentos cadastrados para o município do Imovel.</td>
						</tr>
						<?
					}
					?>
					</tbody>
				</table>
			</div>
			
			
			
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
	</div>
		
		
		
		
		
		
		
	<?
}else{
	if(!empty($cLOGIN->cERRO)){
		$mensagem->setMensagem('Não existe uma proposta ativa para este usuário.', MSG_ERRO);
		echo $mensagem->getMessageBox();
	}
}
?>


  <?
    ### HISTORICO ##################################################################### 
  ?>
  <a name="historico"></a>
	<br><b>Histórico</b>
  <?
		$db->query="SELECT h.cod_ppst, h.obs_hist, h.tipo_hist, u.level_usua, u.nome_usua, '' as cod_chat, h.dt_hist as data
								FROM historico h, proposta p, usuario u
								WHERE p.proponente_ppst = '".mysql_real_escape_string($f_cod_prop)."' AND p.cod_ppst = h.cod_ppst AND h.cod_usua = u.cod_usua
							UNION
								SELECT '".mysql_real_escape_string($_GET['cod_proposta'])."' as cod_ppst, 'Sessão de chat' as obs_hist, '4' as tipo_hist, '1' as level_usua, '".mysql_real_escape_string($f_nome)."' as nome_usua, cod_chat,
									(SELECT MIN(dt_chtm) FROM chatmensagens WHERE cod_chat = chat.cod_chat) as data
								FROM chatsessoes as chat
								WHERE cod_usua = '".mysql_real_escape_string($f_cod_prop)."'
							ORDER BY data DESC";
		$db->query();

		if($db->qrcount>0){
			?>
			<div class="tListDiv listScroll">
				<table>
					<colgroup>
						<col width="150" /><col width="120" /><col />
					</colgroup>

					<thead>
						<tr>
							<td>Usuário</td>
							<td>Data</td>
							<td>Descrição</td>
						</tr>
					</thead>
					<tbody>
					<?
						for($i=0; $i<$db->qrcount; $i++){
							$level = $db->qrdata[$i]['level_usua'];
							$tipo = $db->qrdata[$i]['tipo_hist'];
							$usuario = $db->qrdata[$i]['nome_usua'].' ('.$aTIPOSUSER[$level].')';
							$estilo  = ($tipo==2)?' class="hist2" ':' class="hist1" ';
							$hist_data = $utils->formataDataHora($db->qrdata[$i]['data']);
							$hist_obs  = $db->qrdata[$i]['obs_hist'];
							if($tipo==4){ $hist_obs .= ' &nbsp; <img src="images/buttons/lupa.gif" class="cursorMao" onClick="openChat(3,\''.$db->qrdata[$i]['cod_chat'].'\');" />'; }
							if($tipo==5){ $estilo = ' class="pfinal" '; $hist_obs = '<b>Parecer Final:</b><br>'.$hist_obs; }
							?>
								<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
									<td <?=$estilo;?> style="white-space:nowrap;"><? echo $usuario; ?></td>
									<td <?=$estilo;?>><?=$hist_data;?></td>
									<td <?=$estilo;?>><?=$hist_obs;?></td>
								</tr>
							<?
						}
					?>
					</tbody>
				</table>
			</div>
			<?
		}
		?>		

<script language="JavaScript" src="./js/diversos.js"></script>
<script language="JavaScript" src="./js/atendente.js"></script>
<form name="proposta" id="proposta" method="post" action="<?=$php_self;?>">
	<input type="hidden" name="acaoProposta" id="acaoProposta" value="">
	
  <?
    ### NOVA ACAO ##################################################################### 
  ?>
  <a name="novaacao"></a>
  <br><b>Nova Ação</b>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
			<table cellpadding=0 cellspacing=5 border=0>
        <tr>
          <td align="right" valign="top">Evento:</td>
          <td align="left"  valign="top"><textarea style="width:500px; height:60px;" name="novo_evento" id="novo_evento"></textarea></td>
        </tr>
        <tr>
          <td align="right" valign="top">&nbsp;</td>
          <td align="left"  valign="top"><input type="image" name="btAddEvnt" id="btAddEvnt"   src="images/buttons/bt_adicionar.gif" value="Adicionar Evento" class="im" onClick="return addEvento('<?=$crypt->encrypt('evento');?>');" /></td>
        </tr>
      </table>
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
	</div>

</form>

<?
include "lib/footer.inc.php";
?>