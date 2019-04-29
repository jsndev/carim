<?
$iREQ_AUT=1;
$aUSERS_PERM[]=2;
$pageTitle = "Proposta";
include "lib/header.inc.php";

$mensagem_post = new mensagens();
$mensagem_prop = new mensagens();
$oUsuario      = new usuario();

$acaoProposta = $crypt->decrypt($_POST["acaoProposta"]);

if(!empty($_GET["cod_proposta"])){
	$where_status = " WHERE cod_ppst = '".mysql_real_escape_string($_GET['cod_proposta'])."'
											AND INDCANCELAMENTO_PPST is NULL
											AND situacao_ppst BETWEEN 3 AND 5";
}

$db->query="select proponente_ppst from proposta $where_status";
$db->query();
if($db->qrcount >0){
	$cod_ppnt = $db->qrdata[0]['proponente_ppst'];
}else{
	header('Location:atend_historico.php?cod_proposta='.$_GET['cod_proposta']); exit();
}


$valorMaxFinan  = 0;
$valorMaxPrest  = 0;
$prazoMaxFinan  = 0;
$taxaJurosAno   = 0;
$tipo_simulador = '';
$displaySocioForm  = 'display:none;';
$displayBtAddSocio = 'display:inline;';
$acaoAtualizaUF    = 'socioform';

// Pega a taxa de juros no banco de dados
$db->query="select valor_taxa from taxa";
$db->query();
if($db->qrcount>0){
  $taxaJurosAno = $db->qrdata[0]['valor_taxa'];
}

// Pega os valores limite de financiamento do usuario logado
$db->query="select
							l.vlmaxfinan, l.parcmaxfinan, l.przmaxfinan,
							l.vlaprovado, l.parcaprovada, l.przaprovado, l.vlentraprovado
            from usuario u,  listadenomes l
            where u.id_lstn=l.id_lstn and u.cod_usua='".mysql_real_escape_string($cod_ppnt)."' ";
$db->query();
if($db->qrcount>0){
  $valorMaxFinan  = $db->qrdata[0]['vlmaxfinan'];
  $valorMaxPrest  = $db->qrdata[0]['parcmaxfinan'];
  $prazoMaxFinan  = $db->qrdata[0]['przmaxfinan'];
  $valorAprov     = $db->qrdata[0]['vlaprovado'];
  $parcelaAprov   = $db->qrdata[0]['parcaprovada'];
  $prazoAprov     = $db->qrdata[0]['przaprovado'];
  $entradaAprov   = $db->qrdata[0]['vlentraprovado'];
}

// Carrega os dados do proponente se os mesmos já foram salvos
$cod_insert_ppnt = '';
$db->query="select *
						from proponente  p, usuario u
						where p.cod_proponente = '".mysql_real_escape_string($cod_ppnt)."' 
						  and p.cod_proponente = u.cod_usua";
$db->query();
if($db->qrcount>0){
	$prop_nome       = $db->qrdata[0]['NOME_USUA'];
	$prop_email      = $db->qrdata[0]['EMAIL_USUA'];
	$prop_matric     = $utils->formataMatricula($db->qrdata[0]['ID_LSTN']);
  $prop_cpf        = $db->qrdata[0]['CPF_PPNT'];
  $prop_nasc       = $utils->formataDataBRA($db->qrdata[0]['DTNASCIMENTO_PPNT']);
  $prop_civil      = $db->qrdata[0]['COD_ESTCIV'];
  $prop_lograd     = $db->qrdata[0]['COD_LOGR'];
  $prop_ender      = $db->qrdata[0]['ENDERECO_PPNT'];
  $prop_num        = $db->qrdata[0]['NRENDERECO_PPNT'];
  $prop_compl      = $db->qrdata[0]['CPENDERECO_PPNT'];
  $prop_bairro     = $db->qrdata[0]['COD_BAIRRO'];
  $prop_uf         = $db->qrdata[0]['COD_UF'];
  $prop_cidade     = $db->qrdata[0]['COD_MUNICIPIO'];
  $prop_cep        = $db->qrdata[0]['CEP_PPNT'];
  $prop_fone       = $db->qrdata[0]['TELEFONE_PPNT'];
  $cod_insert_ppnt = $db->qrdata[0]['COD_PROPONENTE'];
  $prop_cep        = $utils->formataCep($prop_cep);
  $prop_cpf        = $utils->formataCPF($prop_cpf);
  $prop_fone       = $utils->formataTelefone($prop_fone);
}

// Carrega os dados da proposta do usuario se a mesma existe
$cod_insert_ppst = $_GET['cod_proposta'];
if($cod_insert_ppnt!=''){
  $db->query="select
  							PRICESAC_PPST,
  							VLFINSOL_PPST,
  							VALORDEVSINALSOL_PPST,
  							VALORFGTS_PPST,
  							VLPRESTSOL_PPST,
  							PRZFINSOL_PPST,
  							TAXAJUROS_PPST,
  							VALORSEGURO_PPST,
  							VALORMANUTENCAO_PPST,
  							COD_PPST,
  							SITUACAO_PPST,
  							DESPACHANTE_PPST,
  							DTAPRESDOC_PPST,
  							FLGRESPOSTAVALOR_PPST,
  							VALORBOLETOAVAL_PPST,
  							FLGBOLETOAVALPAGO_PPST,
  							DTPAGTOBOLETO_PPST,
  							now() as DATA_SISTEMA
  						from proposta 
  						where cod_ppst = '".mysql_real_escape_string($_GET['cod_proposta'])."'";
  $db->query();
  //print $db->query.'<hr>';
  if($db->qrcount>0){
    $tipo_simulador  = $db->qrdata[0]['PRICESAC_PPST'];
    $valor_total     = $db->qrdata[0]['VLFINSOL_PPST'];
    $valor_entrada   = $db->qrdata[0]['VALORDEVSINALSOL_PPST'];
    $valor_fgts      = $db->qrdata[0]['VALORFGTS_PPST'];
    $prop_prest      = $db->qrdata[0]['VLPRESTSOL_PPST'];
    $prop_prazo      = $db->qrdata[0]['PRZFINSOL_PPST'];
    $prop_taxa       = $db->qrdata[0]['TAXAJUROS_PPST'];
    $valor_seguro    = $db->qrdata[0]['VALORSEGURO_PPST'];
    $valor_manut     = $db->qrdata[0]['VALORMANUTENCAO_PPST'];
    $cod_insert_ppst = $db->qrdata[0]['COD_PPST'];
    $cod_situac_ppst = $db->qrdata[0]['SITUACAO_PPST'];
    $imov_despach    = $db->qrdata[0]['DESPACHANTE_PPST'];
    $dtapresdoc_ppst = $db->qrdata[0]['DTAPRESDOC_PPST'];
    $data_sistema    = $db->qrdata[0]['DATA_SISTEMA'];
    $flg_aguardando  = $db->qrdata[0]['FLGRESPOSTAVALOR_PPST'];
    $valor_boleto    = $db->qrdata[0]['VALORBOLETOAVAL_PPST'];
    $flg_pgto_boleto = $db->qrdata[0]['FLGBOLETOAVALPAGO_PPST'];
    $dt_pgto_boleto  = $db->qrdata[0]['DTPAGTOBOLETO_PPST'];
    $valor_compra    = $valor_total + $valor_entrada + $valor_fgts;
    $f_prop_prest    = $prop_prest;
    $f_prop_prazo    = $prop_prazo;
    $prop_prest      = $utils->formataMoeda($f_prop_prest);
    $prop_prest      = ($prop_prazo!='')?'':$prop_prest;
    $flg_pgto_boleto = ($flg_pgto_boleto=='' )?'N':$flg_pgto_boleto;
    $pgto_disabled   = ($flg_pgto_boleto=='N')?'':'disabled';
    $pgto_checked    = ($flg_pgto_boleto=='N')?'':'checked';
    $dt_pgto_boleto  = ($flg_pgto_boleto=='N')?'':$dt_pgto_boleto;
	  $valor_boleto    = ($valor_boleto=='')?0:$valor_boleto;
	  $f_dt_pgto_boleto = $utils->formataDataBRA($dt_pgto_boleto);
  }
}

// Carrega os dados do imovel se os mesmos já foram salvos
$cod_insert_imo = '';
$imov_aval_ro   = '';
$imov_aprov_ro  = '';
$salvar_imov    = true;
if($cod_insert_ppst!=''){
  $db->query="select * from imovel where cod_ppst = '".mysql_real_escape_string($cod_insert_ppst)."' ";
  $db->query();
  if($db->qrcount>0){
    $imov_tipo      = $db->qrdata[0]['TIPO_IMOV'];
    $imov_constr    = $db->qrdata[0]['TPCONSTRUCAO_IMOV'];
    $imov_cond      = $db->qrdata[0]['TPCONDOMINIO_IMOV'];
    $imov_lograd    = $db->qrdata[0]['COD_LOGR'];
    $imov_sala      = $db->qrdata[0]['QTSALA_IMOV'];
    $imov_quarto    = $db->qrdata[0]['QTQUARTO_IMOV'];
    $imov_banh      = $db->qrdata[0]['QTBANH_IMOV'];
    $imov_garag     = $db->qrdata[0]['QTGARAG_IMOV'];
    $imov_pavim     = $db->qrdata[0]['QTPAVIM_IMOV'];
    $imov_empreg    = $db->qrdata[0]['QTDEPEMP_IMOV'];
    $imov_ender     = $db->qrdata[0]['ENDERECO_IMOV'];
    $imov_num       = $db->qrdata[0]['NRENDERECO_IMOV'];
    $imov_compl     = $db->qrdata[0]['CPENDERECO_IMOV'];
    $imov_bairro    = $db->qrdata[0]['COD_BAIRRO'];
    $imov_uf        = $db->qrdata[0]['COD_UF'];
    $imov_cidade    = $db->qrdata[0]['COD_MUNICIPIO'];
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
    $flg_aprovacao  = $db->qrdata[0]['FLGAPROVACAO_IMOV'];
    
    $cod_insert_imo = $db->qrdata[0]['COD_PPST'];
    $imov_cep       = $utils->formataCEP($imov_cep);
		$imov_dt_aval   = $utils->formataDataBRA($imov_dt_aval);
		$imov_dt_aprov  = $utils->formataDataBRA($imov_dt_aprov);
		
		$imov_aval_ro   = ($imov_dt_aval!='')?'disabled':'';
		$imov_aprov_ro  = ($imov_dt_aprov!='')?'disabled':'';
		$salvar_imov    = ($imov_dt_aval!='')?false:true;
  }
}

// Carrega os dados do vendedor se os mesmos já foram salvos
$cod_insert_vend = '';
if($cod_insert_ppst!=''){
  $db->query="select * from vendedor where cod_ppst = '".mysql_real_escape_string($cod_insert_ppst)."' ";
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
	  $vend_cep    = $db->qrdata[0]['CEP_VEND'];
	  $vend_fone   = $db->qrdata[0]['TELEFONE_VEND'];
	  $vend_nrcc   = $db->qrdata[0]['NRCC_VEND'];
	  $vend_dvcc   = $db->qrdata[0]['DVCC_VEND'];
	  $vend_nrag   = $db->qrdata[0]['NRAG_VEND'];
  
	  $vend_cep        = $utils->formataCep($vend_cep);
	  $vend_fone       = $utils->formataTelefone($vend_fone);
	  $cod_insert_vend = $cod_insert_ppst;
  }
}

// Carrega os dados do vendedor (juridico) se os mesmos já foram salvos
$cod_insert_vend_j = '';
if($cod_insert_ppst!=''){
  $db->query="select * from vendjur where cod_ppst = '".mysql_real_escape_string($cod_insert_ppst)."' ";
  $db->query();
  if($db->qrcount>0){
	  $vend_cnpj     = $db->qrdata[0]['CNPJ_VJUR'];
	  $vend_pispasep = $db->qrdata[0]['ISENPIS_VJUR'];
	  $vend_cofins   = $db->qrdata[0]['ISENCOFINS_VJUR'];
	  $vend_csll     = $db->qrdata[0]['ISENCSLL_VJUR'];
	  $vend_atveco   = $db->qrdata[0]['COD_CNAE'];
	
	  $vend_cnpj         = $utils->formataCnpj($vend_cnpj);
	  $cod_insert_vend_j = $cod_insert_ppst;
  }
}

// Carrega os dados do vendedor (fisico) se os mesmos já foram salvos
$cod_insert_vend_f = '';
if($cod_insert_ppst!=''){
  $db->query="select * from vendfis where cod_ppst = '".mysql_real_escape_string($cod_insert_ppst)."' ";
  $db->query();
  if($db->qrcount>0){
	  $vend_cpf     = $db->qrdata[0]['CPF_VFISICA'];
	  $vend_sexo    = $db->qrdata[0]['SEXO_VFISICA'];
	  $vend_nasc    = $db->qrdata[0]['DTNASCIMENTO_VFISICA'];
	  $vend_nacion  = $db->qrdata[0]['COD_PAIS'];
	  $vend_natural = $db->qrdata[0]['NATUR_VFISICA'];
	  $vend_tpdoc   = $db->qrdata[0]['COD_TPDOC'];
	  $vend_rg      = $db->qrdata[0]['NRRG_VFISICA'];
	  $vend_dtrg    = $db->qrdata[0]['DTRG_VFISICA'];
	  $vend_orgrg   = $db->qrdata[0]['ORGRG_VFISICA'];
	  $vend_civil   = $db->qrdata[0]['COD_ESTCIV'];
	  $vend_nconj   = $db->qrdata[0]['NOMECONJ_VFISICA'];
	  $vend_npai    = $db->qrdata[0]['NOMEPAI_VFISICA'];
	  $vend_nmae    = $db->qrdata[0]['NOMEMAE_VFISICA'];
	  $vend_profiss = $db->qrdata[0]['COD_PROF'];
	  $vend_rendim  = $db->qrdata[0]['VLRENDA_VFISICA'];
	  $vend_inss    = $db->qrdata[0]['NRINSS_VFISICA'];
	  
	  $vend_cpf          = $utils->formataCPF($vend_cpf);
	  $vend_nasc         = $utils->formataDataBRA($vend_nasc);
	  $vend_rg           = $utils->formataRG($vend_rg);
	  $vend_dtrg         = $utils->formataDataBRA($vend_dtrg);
	  $vend_rendim       = $utils->formataMoeda($vend_rendim);
	  $cod_insert_vend_f = $cod_insert_ppst;
  }
}

// Carrega os dados do devedor solidario se os mesmos já foram salvos
$cod_insert_devsol = '';
if($cod_insert_ppst!=''){
  $db->query="select * from devsol where cod_ppst = '".mysql_real_escape_string($cod_insert_ppst)."' ";
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
	  
	  $cod_insert_devsol = $cod_insert_ppst;
  }
}



	  
if($_POST){
	### DADOS DA PROPOSTA #####################################################################
  $tipo_simulador = intval($_POST['tipo_simulador']);
  $valor_compra   = floatval(str_replace(',','.',str_replace('.','',$_POST['valor_compra'])));
  $valor_entrada  = floatval(str_replace(',','.',str_replace('.','',$_POST['valor_entrada'])));
  $valor_fgts     = floatval(str_replace(',','.',str_replace('.','',$_POST['valor_fgts'])));
  $prop_prest     = $_POST['prestacao'];
  $prop_prazo     = $_POST['prazo'];
  $prop_taxa      = $taxaJurosAno;
  $valor_seguro   = floatval(str_replace(',','.',str_replace('.','',$_POST['valor_seguro'])));
  $valor_manut    = floatval(str_replace(',','.',str_replace('.','',$_POST['valor_manut'])));
  $f_prop_prest   = floatval(str_replace(',','.',str_replace('.','',$_POST['prestacao'])));
  $f_prop_prazo   = intval($_POST['prazo']);
  $imov_despach   = $_POST['imov_despach'];
  $alterou_val    = $_POST['alterouValores'];
  $sel_tipo_finan = intval($_POST['sel_tipo_finan']);
  if($sel_tipo_finan==2){ $f_prop_prest = ''; $prop_prest = ''; }else{ $f_prop_prazo = ''; $prop_prazo = ''; }

	### DADOS DO PROPONENTE #####################################################################
  $prop_cpf    = $_POST['prop_cpf'];
  $prop_nasc   = $_POST['prop_nasc'];
  $prop_civil  = $_POST['prop_civil'];
  $prop_lograd = $_POST['prop_lograd'];
  $prop_ender  = $_POST['prop_ender'];
  $prop_num    = $_POST['prop_num'];
  $prop_compl  = $_POST['prop_compl'];
  $prop_uf     = $_POST['prop_uf'];
  $prop_cidade = $_POST['prop_cidade'];
  $prop_bairro = $_POST['prop_bairro'];
  $prop_cep    = $_POST['prop_cep'];
  $prop_fone   = $_POST['prop_fone'];

  ### DADOS DO IMÓVEL #####################################################################
	if($salvar_imov){
	  $imov_area      = floatval(str_replace(',','.',str_replace('.','',$_POST['imov_area'])));
	  $post_imov_aval = floatval(str_replace(',','.',str_replace('.','',$_POST['imov_vl_aval'])));
	  $imov_tp_impst  = $_POST['imov_tp_impst'];
	  $imov_tipo   = $_POST['imov_tipo'];
	  $imov_constr = $_POST['imov_constr'];
	  $imov_cond   = $_POST['imov_cond'];
	  $imov_cons_imov = $_POST['imov_cons_imov'];
	  $imov_cons_pred = $_POST['imov_cons_pred'];
	
	  $imov_sala   = $_POST['imov_sala'];
	  $imov_quarto = $_POST['imov_quarto'];
	  $imov_banh   = $_POST['imov_banh'];
	  $imov_garag  = $_POST['imov_garag'];
	  $imov_pavim  = $_POST['imov_pavim'];
	  $imov_empreg = $_POST['imov_empreg'];
	  
	  $imov_cartr_rgi = $_POST['imov_cartr_rgi'];
	  $imov_matrc_rgi = $_POST['imov_matrc_rgi'];
	  $imov_livro_rgi = $_POST['imov_livro_rgi'];
	  $imov_folhs_rgi = $_POST['imov_folhs_rgi'];
	  $imov_rg_cprvnd = $_POST['imov_rg_cprvnd'];
	  $imov_rg_garant = $_POST['imov_rg_garant'];
	
	  $imov_lograd = $_POST['imov_lograd'];
	  $imov_ender  = $_POST['imov_ender'];
	  $imov_num    = $_POST['imov_num'];
	  $imov_compl  = $_POST['imov_compl'];
	  $imov_uf     = $_POST['imov_uf'];
	  $imov_cidade = $_POST['imov_cidade'];
	  $imov_bairro = $_POST['imov_bairro'];
	  $imov_cep    = $_POST['imov_cep'];
	
	  $imov_tp_morad  = $_POST['imov_tp_morad'];
	  $imov_terreo    = $_POST['imov_terreo'];
	  $imov_tb_dp_cnd = $_POST['imov_tb_dp_cnd'];
	  $imov_incomb    = $_POST['imov_incomb'];
	  $imov_rural_fav = $_POST['imov_rural_fav'];
	  $imov_em_constr = $_POST['imov_em_constr'];
	  
	  $imov_dt_aval   = $_POST['imov_dt_aval'];
	  
	  if($post_imov_aval=='0.00' && $imov_vl_aval!='' && $imov_vl_aval!='0.00' ){
	  	$acaoProposta='';
	  }elseif($imov_dt_aval=='' && $post_imov_aval!='0.00'){
	  	$acaoProposta='';
	  }else{
	  	$imov_vl_aval = $post_imov_aval;
	  }
	}
	
  ### DADOS DO VENDEDOR #####################################################################
  $vend_tipo   = $_POST['vend_tipo'];
  $vend_nome   = $_POST['vend_nome'];
  $vend_nick   = $_POST['vend_nick'];
	
  $vend_cpf     = $_POST['vend_cpf'];
  $vend_sexo    = $_POST['vend_sexo'];
  $vend_nasc    = $_POST['vend_nasc'];
  $vend_nacion  = $_POST['vend_nacion'];
  $vend_natural = $_POST['vend_natural'];
  $vend_tpdoc   = $_POST['vend_tpdoc'];
  $vend_rg      = $_POST['vend_rg'];
  $vend_dtrg    = $_POST['vend_dtrg'];
  $vend_orgrg   = $_POST['vend_orgrg'];
  $vend_civil   = $_POST['vend_civil'];
  $vend_nconj   = $_POST['vend_nconj'];
  $vend_npai    = $_POST['vend_npai'];
  $vend_nmae    = $_POST['vend_nmae'];
  $vend_profiss = $_POST['vend_profiss'];
  $vend_rendim  = floatval(str_replace(',','.',str_replace('.','',$_POST['vend_rendim'])));
  $vend_inss    = $_POST['vend_inss'];
	
  $vend_cnpj     = $_POST['vend_cnpj'];
  $vend_pispasep = $_POST['vend_pispasep'];
  $vend_cofins   = $_POST['vend_cofins'];
  $vend_csll     = $_POST['vend_csll'];
  $vend_atveco   = $_POST['vend_atveco'];

  $vend_s_nome   = $_POST['vend_s_nome'];
  $vend_s_nabrev = $_POST['vend_s_nabrev'];
  $vend_s_logr   = $_POST['vend_s_logr'];
  $vend_s_ender  = $_POST['vend_s_ender'];
  $vend_s_num    = $_POST['vend_s_num'];
  $vend_s_compl  = $_POST['vend_s_compl'];
  $vend_s_uf     = $_POST['vend_s_uf'];
  $vend_s_cidade = $_POST['vend_s_cidade'];
  $vend_s_bairro = $_POST['vend_s_bairro'];
  $vend_s_cep    = $_POST['vend_s_cep'];
  $vend_s_fone   = $_POST['vend_s_fone'];
  $vend_s_cpf    = $_POST['vend_s_cpf'];
  $vend_s_nacion = $_POST['vend_s_nacion'];
  $vend_s_sexo   = $_POST['vend_s_sexo'];
  $vend_codvjsoc = $_POST['f_cod_vjsoc'];
  $qtde_vjsoc    = $_POST['qtde_vjsoc'];

  $vend_logr   = $_POST['vend_logr'];
  $vend_ender  = $_POST['vend_ender'];
  $vend_num    = $_POST['vend_num'];
  $vend_compl  = $_POST['vend_compl'];
  $vend_uf     = $_POST['vend_uf'];
  $vend_cidade = $_POST['vend_cidade'];
  $vend_bairro = $_POST['vend_bairro'];
  $vend_cep    = $_POST['vend_cep'];
  $vend_fone   = $_POST['vend_fone'];
  $vend_nrcc   = $_POST['vend_nrcc'];
  $vend_dvcc   = $_POST['vend_dvcc'];
  $vend_nrag   = $_POST['vend_nrag'];

  ### DADOS DO DEVEDOR SOLIDARIO #######################################################
  $dsol_nome   = $_POST['dsol_nome'];
  $dsol_nick   = $_POST['dsol_nick'];
  $dsol_logr   = $_POST['dsol_logr'];
  $dsol_ender  = $_POST['dsol_ender'];
  $dsol_num    = $_POST['dsol_num'];
  $dsol_compl  = $_POST['dsol_compl'];
  $dsol_uf     = $_POST['dsol_uf'];
  $dsol_cidade = $_POST['dsol_cidade'];
  $dsol_bairro = $_POST['dsol_bairro'];
  $dsol_cep    = $_POST['dsol_cep'];
  $dsol_fone   = $_POST['dsol_fone'];
  $dsol_cpf    = $_POST['dsol_cpf'];
  $dsol_sexo   = $_POST['dsol_sexo'];
  $dsol_nacion = $_POST['dsol_nacion'];

  if($flg_pgto_boleto=='N'){
	  $chk_pagto        = $_POST['chk_pagto'];
	  $f_dt_pgto_boleto = $_POST['data_pagto'];
		$valor_boleto     = $oParametros->listaValoresBoleto(trim($imov_uf));
	  $dt_pgto_boleto   = $utils->formataData($f_dt_pgto_boleto);
    $flg_pgto_boleto  = ($chk_pagto)?'S':'N';
    $pgto_disabled    = ($flg_pgto_boleto=='N')?'':'disabled';
    $pgto_checked     = ($flg_pgto_boleto=='N')?'':'checked';
    $dt_pgto_boleto   = ($flg_pgto_boleto=='N')?'':$dt_pgto_boleto;
		$f_valor_boleto   = floatval(str_replace(',','.',str_replace('.','',$valor_boleto)));
  }else{
 		$f_valor_boleto   = $valor_boleto;
  }

  ### NOVO EVENTO #####################################################################
  $f_novo_evento = htmlentities($_POST['novo_evento']);
  
}else{
	$cLOGIN->insert_log(1,1,'Tela de Avaliação da Proposta - CODPPST:'.$cod_insert_ppst);
}

$valor_boleto = $utils->formataMoeda($valor_boleto);
if($flg_pgto_boleto=='N'){
	$valor_boleto = $utils->formataMoeda($oParametros->listaValoresBoleto(trim($imov_uf)));
}
  
### CALCULO DO FINANCIAMENTO ###############################################################
$valor_total   = $valor_compra - ($valor_entrada + $valor_fgts);
$calculo_error = '';
/*
if($tipo_simulador > 0){
  $valor = $valor_total;
  $prop_prazo = ($prop_prazo > 0)?$prop_prazo:1;
  $taxa  = pow( (( $prop_taxa / 100 ) + 1), (1 / 12)) - 1;
  $resultMessage = '';
  switch($tipo_simulador){
    case '1':
      $prestacao = $utils->fPMT($taxa,$prop_prazo,$valor);
      $prestacao1 = $prestacao;
	      //$prestacao += $valor_seguro + $valor_manut;
	      $f_prestacao = $utils->formataMoeda($prestacao);
	      $resultMessage .= 'Valor da Prestação Inicial: <b>R$ '.$f_prestacao.'</b>';
      break;
    case '2':
      $amort = $valor / $prop_prazo;
      $juros = ($valor - $amort) * $taxa;
      $prestacao = $juros + $amort;
      $prestacao1 = $prestacao;
      $redMJ = $juros / $prop_prazo;
	      //$prestacao  += $valor_seguro + $valor_manut;
	      $f_prestacao = $utils->formataMoeda($prestacao);
	      $f_redMJ     = $utils->formataMoeda($redMJ);
	      $resultMessage .= 'Valor da Prestação Inicial: <b>R$ '.$f_prestacao.'</b><br>';
	      $resultMessage .= 'Redução Mensal de Juros: <b>R$ '.$f_redMJ.'</b>';
      break;
  }

  if($prestacao1 > $valorMaxPrest){      $calculo_error = 'valor da prestação excede o valor permitido'; }
  elseif($valor_total > $valorMaxFinan){ $calculo_error = 'valor do financiamento excede o valor permitido'; }
  elseif($prop_prazo  > $prazoMaxFinan){ $calculo_error = 'prazo do financiamento maior que o permitido'; }
  
  if($calculo_error!=''){
  	$resultMessage = '';
  	$mensagem->setMensagem($calculo_error, MSG_ERRO);
  	if($acaoProposta=='concluir'){ $acaoProposta='salvar'; }
  }
}
*/
  
if($_POST){
	if(
			$acaoProposta=='salvar'     || 
			$acaoProposta=='concluir'   ||
			$acaoProposta=='imov_aprov' ||
			$acaoProposta=='imov_reprov'
	){

	  ## ------------------------------------------------------------------------------------------- ##
	  ## APROVACAO DO IMOVEL ##########################################################################
	  if($calculo_error==''){
			if($acaoProposta=='imov_aprov' || $acaoProposta=='imov_reprov'){
				$imov_dt_aprov  = $_POST['imov_dt_aprov'];
			  $f_imov_dt_aprov = $utils->formataData($imov_dt_aprov);
			  if($acaoProposta=='imov_reprov'){
			  	// REPROVACAO do IMOVEL ..................................................................
					$db->query="UPDATE imovel SET dtaprovacao_imov='".mysql_real_escape_string($f_imov_dt_aprov)."', flgaprovacao_imov='N'
											WHERE cod_ppst = '".mysql_real_escape_string($cod_insert_ppst)."' ";
					$db->query();
					$db->query="UPDATE proposta  SET indcancelamento_ppst='IM'
											WHERE cod_ppst = '".mysql_real_escape_string($cod_insert_ppst)."' ";
					$db->query();
					$flg_aprovacao = 'N';
			  }else{
			  	// APROVACAO do IMOVEL ..................................................................
					$db->query="UPDATE imovel SET dtaprovacao_imov='".mysql_real_escape_string($f_imov_dt_aprov)."', flgaprovacao_imov='S'
											WHERE cod_ppst = '".mysql_real_escape_string($cod_insert_ppst)."' ";
					$db->query();
					$flg_aprovacao = 'S';
			  }
			}
		}else{
			$imov_vl_aval  = '';
			$imov_dt_aval  = '';
			$imov_dt_aprov  = '';
		}
		if($imov_dt_aval!='')  $imov_aval_ro  = 'disabled';
		if($imov_dt_aprov!='') $imov_aprov_ro = 'disabled';
	
	  ## ------------------------------------------------------------------------------------------- ##
	  ## DADOS DO PROPONENTE ##########################################################################
		  $f_prop_cpf  = mysql_real_escape_string(preg_replace('/\D/i','',$prop_cpf));
		  $f_prop_cep  = mysql_real_escape_string(preg_replace('/\D/i','',$prop_cep));
		  $f_prop_nasc = mysql_real_escape_string(((!empty($prop_nasc))?$utils->formataData($prop_nasc):''));
		  $f_prop_fone = mysql_real_escape_string(preg_replace('/\D/i','',$prop_fone));
	    if($cod_insert_ppnt==''){
	      $qCMP = $qVAL = '';
	      $qCMP .= " cpf_ppnt, ";          $qVAL .= " '".mysql_real_escape_string($f_prop_cpf)."', ";
	      $qCMP .= " dtnascimento_ppnt, "; if($f_prop_nasc!=''){  $qVAL .= " '".mysql_real_escape_string($f_prop_nasc)."', ";  }else{ $qVAL .= " NULL, "; }
	      $qCMP .= " cod_estciv, ";        if($prop_civil!='0') { $qVAL .= " '".mysql_real_escape_string($prop_civil)."', ";  }else{ $qVAL .= " NULL, "; }
	      $qCMP .= " cod_logr, ";          if($prop_lograd!='0'){ $qVAL .= " '".mysql_real_escape_string($prop_lograd)."', "; }else{ $qVAL .= " NULL, "; }
	      $qCMP .= " endereco_ppnt, ";     $qVAL .= " '".mysql_real_escape_string($prop_ender)."', ";
	      $qCMP .= " nrendereco_ppnt, ";   $qVAL .= " '".mysql_real_escape_string($prop_num)."', ";
	      $qCMP .= " cpendereco_ppnt, ";   $qVAL .= " '".mysql_real_escape_string($prop_compl)."', ";
	      $qCMP .= " cod_uf, ";            if($prop_uf!='0')    { $qVAL .= " '".mysql_real_escape_string($prop_uf)."', ";     }else{ $qVAL .= " NULL, "; }
	      $qCMP .= " cod_municipio, ";     if($prop_cidade!='0'){ $qVAL .= " '".mysql_real_escape_string($prop_cidade)."', "; }else{ $qVAL .= " NULL, "; }
	      $qCMP .= " cod_bairro, ";        if($prop_bairro!='0'){ $qVAL .= " '".mysql_real_escape_string($prop_bairro)."', "; }else{ $qVAL .= " NULL, "; }
	      $qCMP .= " cep_ppnt, ";          $qVAL .= " '".mysql_real_escape_string($f_prop_cep)."', ";
	      $qCMP .= " telefone_ppnt, ";     $qVAL .= " '".mysql_affected_rows($f_prop_fone)."', ";
	      $qCMP .= " cod_proponente ";     $qVAL .= " '".mysql_real_escape_string($cLOGIN->iID)."' ";
	  		$db->query="INSERT INTO proponente ($qCMP) VALUES ($qVAL)";
	  		$db->query();
	  		$cod_insert_ppnt = $cLOGIN->iID;
	  	}else{
	      $qSET = '';
	      $qSET .= " cpf_ppnt          =    '".mysql_real_escape_string($f_prop_cpf)."', ";
	      $qSET .= " dtnascimento_ppnt = ".(($f_prop_nasc!='' )?"'".mysql_real_escape_string($f_prop_nasc)."'":'NULL').", ";
	      $qSET .= " cod_estciv        = ".(($prop_civil!='0' )?"'".mysql_real_escape_string($prop_civil)."'" :'NULL').", ";
	      $qSET .= " cod_logr          = ".(($prop_lograd!='0')?"'".mysql_real_escape_string($prop_lograd)."'":'NULL').", ";
	      $qSET .= " endereco_ppnt     =    '".mysql_real_escape_string($prop_ender)."', ";
	      $qSET .= " nrendereco_ppnt   =    '".mysql_real_escape_string($prop_num)."', ";
	      $qSET .= " cpendereco_ppnt   =    '".mysql_real_escape_string($prop_compl)."', ";
	      $qSET .= " cod_uf            = ".(($prop_uf!='0'    )?"'".mysql_real_escape_string($prop_uf)."'"    :'NULL').", ";
	      $qSET .= " cod_municipio     = ".(($prop_cidade!='0')?"'".mysql_real_escape_string($prop_cidade)."'":'NULL').", ";
	      $qSET .= " cod_bairro        = ".(($prop_bairro!='0')?"'".mysql_real_escape_string($prop_bairro)."'":'NULL').", ";
	      $qSET .= " cep_ppnt          =    '".mysql_real_escape_string($f_prop_cep)."', ";
	      $qSET .= " telefone_ppnt     =    '".mysql_real_escape_string($f_prop_fone)."' ";
	  		$db->query="UPDATE proponente SET $qSET WHERE cod_proponente = '".mysql_real_escape_string($cod_insert_ppnt)."'";
	  		$db->query();
	  	}

	  ## ------------------------------------------------------------------------------------------- ##
	  ## DADOS DA PROPOSTA ############################################################################
		  $situacao_ppst = 4; // Avaliacao
		  if( $imov_vl_aval != '0.00' ){ $situacao_ppst = 5; } // Análise Documental
		  if($acaoProposta=='concluir'){ $situacao_ppst = 6; } // Análise Juridica
		  if($calculo_error==''){
	      $qSET = '';
	      $qSET .= " pricesac_ppst         = '".mysql_real_escape_string($tipo_simulador)."', ";
	      $qSET .= " valorcompra_ppst      = '".mysql_real_escape_string($valor_compra)."', ";
	      $qSET .= " valordevsinalsol_ppst = '".mysql_real_escape_string($valor_entrada)."', ";
	      $qSET .= " valorfgts_ppst        = '".mysql_real_escape_string($valor_fgts)."', ";
	      $qSET .= " vlfinsol_ppst         = '".mysql_real_escape_string($valor_total)."', ";
	      $qSET .= " taxajuros_ppst        = '".mysql_real_escape_string($prop_taxa)."', ";
	      if($f_prop_prazo!=''){ $qSET .= " przfinsol_ppst        = '".mysql_real_escape_string($f_prop_prazo)."', "; }else{ $qSET .= " przfinsol_ppst  = NULL, "; }
	      if($f_prop_prest!=''){ $qSET .= " vlprestsol_ppst       = '".mysql_real_escape_string($f_prop_prest)."', "; }else{ $qSET .= " vlprestsol_ppst = NULL, "; }
	      $qSET .= " valorseguro_ppst      = '".mysql_real_escape_string($valor_seguro)."', ";
	      $qSET .= " valormanutencao_ppst  = '".mysql_real_escape_string($valor_manut)."', ";
	      if($dtapresdoc_ppst==''){ $qSET .= " dtapresdoc_ppst       = now(), "; }
	      if($imov_despach!='0'){   $qSET .= " despachante_ppst      = '".mysql_real_escape_string($imov_despach)."', "; }else{ $qSET .= " despachante_ppst = NULL, "; }
	      if($alterou_val=='S'){    $qSET .= " flgrespostavalor_ppst = 'S', "; $flg_aguardando = 'S'; }
				if($flg_pgto_boleto=='S' && $dt_pgto_boleto!=''){
		      $qSET .= " valorboletoaval_ppst   = '".mysql_real_escape_string($f_valor_boleto)."', ";
		      $qSET .= " flgboletoavalpago_ppst = 'S', ";
		      $qSET .= " dtpagtoboleto_ppst     = '".mysql_real_escape_string($dt_pgto_boleto)."', ";
				}
	      $qSET .= " situacao_ppst         = '".mysql_real_escape_string($situacao_ppst)."' ";
	  		$db->query="UPDATE proposta SET $qSET WHERE cod_ppst = '".mysql_real_escape_string($cod_insert_ppst)."'";
	  		$db->query();
	  		//print $db->query.'<hr>';
		  }
		  
		  if($situacao_ppst==4){
		  	if($cod_situac_ppst==3){
			  	// Avaliacao
			 		$cLOGIN->insert_log(2,1,$_SESSION["prop_status"][4]);
			 		$cLOGIN->insert_history($cod_insert_ppst,1,$_SESSION["prop_status"][4]);
		  	}
		  }elseif($situacao_ppst==5){
		  	if($cod_situac_ppst==3){
			  	// Avaliacao
			 		$cLOGIN->insert_log(2,1,$_SESSION["prop_status"][4]);
			 		$cLOGIN->insert_history($cod_insert_ppst,1,$_SESSION["prop_status"][4]);
		  	}
		  	if($cod_situac_ppst<=4){
			  	// Análise Documental
			 		$cLOGIN->insert_log(2,2,$_SESSION["prop_status"][5]);
			 		$cLOGIN->insert_history($cod_insert_ppst,1,$_SESSION["prop_status"][5]);
		  	}
		  }elseif($situacao_ppst==6){
		  	if($cod_situac_ppst==3){
			  	// Avaliacao
			 		$cLOGIN->insert_log(2,1,$_SESSION["prop_status"][4]);
			 		$cLOGIN->insert_history($cod_insert_ppst,1,$_SESSION["prop_status"][4]);
		  	}
		  	if($cod_situac_ppst<=4){
			  	// Análise Documental
			 		$cLOGIN->insert_log(2,2,$_SESSION["prop_status"][5]);
			 		$cLOGIN->insert_history($cod_insert_ppst,1,$_SESSION["prop_status"][5]);
		  	}
		  	if($cod_situac_ppst<=5){
			  	// Análise Juridica
			 		$cLOGIN->insert_log(2,3,$_SESSION["prop_status"][6]);
			 		$cLOGIN->insert_history($cod_insert_ppst,1,$_SESSION["prop_status"][6]);
		  	}
		  }
		  
		  if($cod_situac_ppst == $situacao_ppst){
		  	// Alteração da Proposta
		 		$cLOGIN->insert_log(2,4,'Alteração da Proposta');
		 		$cLOGIN->insert_history($cod_insert_ppst,1,'Alteração da Proposta');
		  }

	  ## ------------------------------------------------------------------------------------------- ##
	  ## DADOS Do IMOVEL ############################################################################
		  $f_imov_cep = preg_replace('/\D/i','',$imov_cep);
		  $f_imov_dt_aval  = $utils->formataData($imov_dt_aval);
			if($salvar_imov){
		    if($cod_insert_imo==''){
		      $qCMP = $qVAL = '';
		      $qCMP .= " area_imov, ";         $qVAL .= " '$imov_area', ";
		      $qCMP .= " vlavaliacao_imov, ";  if($imov_vl_aval!='0.00'){ $qVAL .= " '".mysql_real_escape_string($imov_vl_aval)."', ";   }else{ $qVAL .= " NULL, "; }
		      $qCMP .= " dtavaliacao_imov, ";  if($f_imov_dt_aval!=''){   $qVAL .= " '".mysql_real_escape_string($f_imov_dt_aval)."', "; }else{ $qVAL .= " NULL, "; }
		      $qCMP .= " tpimposto_imov, ";    if($imov_tp_impst!='x'){   $qVAL .= " '".mysql_real_escape_string($imov_tp_impst)."', ";  }else{ $qVAL .= " NULL, "; }
		      $qCMP .= " tipo_imov, ";         if($imov_tipo!='x'){       $qVAL .= " '".mysql_real_escape_string($imov_tipo)."', ";      }else{ $qVAL .= " NULL, "; }
		      $qCMP .= " tpconstrucao_imov, "; if($imov_constr!='x'){     $qVAL .= " '".mysql_real_escape_string($imov_constr)."', ";    }else{ $qVAL .= " NULL, "; }
		      $qCMP .= " tpcondominio_imov, "; if($imov_cond!='x'){       $qVAL .= " '".mysql_real_escape_string($imov_cond)."', ";      }else{ $qVAL .= " NULL, "; }
		      $qCMP .= " estconserv_imov, ";   if($imov_cons_imov!='x'){  $qVAL .= " '".mysql_real_escape_string($imov_cons_imov)."', "; }else{ $qVAL .= " NULL, "; }
		      $qCMP .= " estconspred_imov, ";  if($imov_cons_pred!='x'){  $qVAL .= " '".mysql_real_escape_string($imov_cons_pred)."', "; }else{ $qVAL .= " NULL, "; }
		      $qCMP .= " qtsala_imov, ";       if($imov_sala!=''){        $qVAL .= " '".mysql_real_escape_string($imov_sala)."', ";      }else{ $qVAL .= " NULL, "; }
		      $qCMP .= " qtquarto_imov, ";     if($imov_quarto!=''){      $qVAL .= " '".mysql_real_escape_string($imov_quarto)."', ";    }else{ $qVAL .= " NULL, "; }
		      $qCMP .= " qtbanh_imov, ";       if($imov_banh!=''){        $qVAL .= " '".mysql_real_escape_string($imov_banh)."', ";      }else{ $qVAL .= " NULL, "; }
		      $qCMP .= " qtgarag_imov, ";      if($imov_garag!=''){       $qVAL .= " '".mysql_real_escape_string($imov_garag)."', ";     }else{ $qVAL .= " NULL, "; }
		      $qCMP .= " qtpavim_imov, ";      if($imov_pavim!=''){       $qVAL .= " '".mysql_real_escape_string($imov_pavim)."', ";     }else{ $qVAL .= " NULL, "; }
		      $qCMP .= " qtdepemp_imov, ";     if($imov_empreg!=''){      $qVAL .= " '".mysql_real_escape_string($imov_empreg)."', ";    }else{ $qVAL .= " NULL, "; }
		      $qCMP .= " nomecartrgi_imov, ";  $qVAL .= " '".mysql_real_escape_string($imov_cartr_rgi)."', ";
		      $qCMP .= " nrmatrgi_imov, ";     $qVAL .= " '".mysql_real_escape_string($imov_matrc_rgi)."', ";
		      $qCMP .= " nrlivrgi_imov, ";     $qVAL .= " '".mysql_real_escape_string($imov_livro_rgi)."', ";
		      $qCMP .= " nrfolhrgi_imov, ";    $qVAL .= " '".mysql_real_escape_string($imov_folhs_rgi)."', ";
		      $qCMP .= " nrrgcompvend_imov, "; $qVAL .= " '".mysql_real_escape_string($imov_rg_cprvnd)."', ";
		      $qCMP .= " nrrggar_imov, ";      $qVAL .= " '".mysql_real_escape_string($imov_rg_garant)."', ";
		      $qCMP .= " cod_logr, ";          if($imov_lograd!='0'){     $qVAL .= " '".mysql_real_escape_string($imov_lograd)."', ";    }else{ $qVAL .= " NULL, "; }
		      $qCMP .= " endereco_imov, ";     $qVAL .= " '".mysql_real_escape_string($imov_ender)."', ";
		      $qCMP .= " nrendereco_imov, ";   $qVAL .= " '".mysql_real_escape_string($imov_num)."', ";
		      $qCMP .= " cpendereco_imov, ";   $qVAL .= " '".mysql_real_escape_string($imov_compl)."', ";
		      $qCMP .= " cod_uf, ";            if($imov_uf!='0'){         $qVAL .= " '".mysql_real_escape_string($imov_uf)."', ";        }else{ $qVAL .= " NULL, "; }
		      $qCMP .= " cod_municipio, ";     if($imov_cidade!='0'){     $qVAL .= " '".mysql_real_escape_string($imov_cidade)."', ";    }else{ $qVAL .= " NULL, "; }
		      $qCMP .= " cod_bairro, ";        if($imov_bairro!='0'){     $qVAL .= " '".mysql_real_escape_string($imov_bairro)."', ";    }else{ $qVAL .= " NULL, "; }
		      $qCMP .= " cep_imov, ";          $qVAL .= " '".mysql_real_escape_string($f_imov_cep)."', ";
		      $qCMP .= " tpmoradia_imov, ";    if($imov_tp_morad!='x'){   $qVAL .= " '".mysql_real_escape_string($imov_tp_morad)."', ";  }else{ $qVAL .= " NULL, "; }
		      $qCMP .= " terreo_imov, ";       if($imov_terreo!='x'){     $qVAL .= " '".mysql_real_escape_string($imov_terreo)."', ";    }else{ $qVAL .= " NULL, "; }
		      $qCMP .= " tmbdspcndop_imov, ";  if($imov_tb_dp_cnd!='x'){  $qVAL .= " '".mysql_real_escape_string($imov_tb_dp_cnd)."', "; }else{ $qVAL .= " NULL, "; }
		      $qCMP .= " incomb_imov, ";       if($imov_incomb!='x'){     $qVAL .= " '".mysql_real_escape_string($imov_incomb)."', ";    }else{ $qVAL .= " NULL, "; }
		      $qCMP .= " ruralfav_imov, ";     if($imov_rural_fav!='x'){  $qVAL .= " '".mysql_real_escape_string($imov_rural_fav)."', "; }else{ $qVAL .= " NULL, "; }
		      $qCMP .= " emconstr_imov, ";     if($imov_em_constr!='x'){  $qVAL .= " '".mysql_real_escape_string($imov_em_constr)."', "; }else{ $qVAL .= " NULL, "; }
		      $qCMP .= " cod_ppst ";           $qVAL .= " '".mysql_real_escape_string($cod_insert_ppst)."' ";
		  		$db->query="INSERT INTO imovel ($qCMP) VALUES ($qVAL)";
		  		$db->query();
		  		//print $db->query.'<hr>';
		  	}else{
		      $qSET = '';
		      $qSET .= 							  " area_imov         = '".mysql_real_escape_string($imov_area)."', ";
		      if($imov_vl_aval!='0.00'){ $qSET .= " vlavaliacao_imov  = '".mysql_real_escape_string($imov_vl_aval)."', ";    }else{ $qSET .= " vlavaliacao_imov  = NULL, "; }
		      if($f_imov_dt_aval!=''){   $qSET .= " dtavaliacao_imov  = '".mysql_real_escape_string($f_imov_dt_aval)."', ";  }else{ $qSET .= " dtavaliacao_imov  = NULL, "; }
		      if($imov_tp_impst!='x'){   $qSET .= " tpimposto_imov    = '".mysql_real_escape_string($imov_tp_impst)."', ";   }else{ $qSET .= " tpimposto_imov    = NULL, "; }
		      if($imov_tipo!='x'){       $qSET .= " tipo_imov         = '".mysql_real_escape_string($imov_tipo)."', ";       }else{ $qSET .= " tipo_imov         = NULL, "; }
		      if($imov_constr!='x'){     $qSET .= " tpconstrucao_imov = '".mysql_real_escape_string($imov_constr)."', ";     }else{ $qSET .= " tpconstrucao_imov = NULL, "; }
		      if($imov_cond!='x'){       $qSET .= " tpcondominio_imov = '".mysql_real_escape_string($imov_cond)."', ";       }else{ $qSET .= " tpcondominio_imov = NULL, "; }
		      if($imov_cons_imov!='x'){  $qSET .= " estconserv_imov   = '".mysql_real_escape_string($imov_cons_imov)."', ";  }else{ $qSET .= " estconserv_imov   = NULL, "; }
		      if($imov_cons_pred!='x'){  $qSET .= " estconspred_imov  = '".mysql_real_escape_string($imov_cons_pred)."', ";  }else{ $qSET .= " estconspred_imov  = NULL, "; }
		      if($imov_sala!=''){        $qSET .= " qtsala_imov       = '".mysql_real_escape_string($imov_sala)."', ";       }else{ $qSET .= " qtsala_imov       = NULL, "; }
		      if($imov_quarto!=''){      $qSET .= " qtquarto_imov     = '".mysql_real_escape_string($imov_quarto)."', ";     }else{ $qSET .= " qtquarto_imov     = NULL, "; }
		      if($imov_banh!=''){        $qSET .= " qtbanh_imov       = '".mysql_real_escape_string($imov_banh)."', ";       }else{ $qSET .= " qtbanh_imov       = NULL, "; }
		      if($imov_garag!=''){       $qSET .= " qtgarag_imov      = '".mysql_real_escape_string($imov_garag)."', ";      }else{ $qSET .= " qtgarag_imov      = NULL, "; }
		      if($imov_pavim!=''){       $qSET .= " qtpavim_imov      = '".mysql_real_escape_string($imov_pavim)."', ";      }else{ $qSET .= " qtpavim_imov      = NULL, "; }
		      if($imov_empreg!=''){      $qSET .= " qtdepemp_imov     = '".mysql_real_escape_string($imov_empreg)."', ";     }else{ $qSET .= " qtdepemp_imov     = NULL, "; }
		      $qSET .=							  " nomecartrgi_imov  = '".mysql_real_escape_string($imov_cartr_rgi)."', ";
		      $qSET .= 							  " nrmatrgi_imov     = '".mysql_real_escape_string($imov_matrc_rgi)."', ";
		      $qSET .= 							  " nrlivrgi_imov     = '".mysql_real_escape_string($imov_livro_rgi)."', ";
		      $qSET .= 							  " nrfolhrgi_imov    = '".mysql_real_escape_string($imov_folhs_rgi)."', ";
		      $qSET .= 							  " nrrgcompvend_imov = '".mysql_real_escape_string($imov_rg_cprvnd)."', ";
		      $qSET .= 							  " nrrggar_imov      = '".mysql_real_escape_string($imov_rg_garant)."', ";
		      if($imov_lograd!='0'){     $qSET .= " cod_logr          = '".mysql_real_escape_string($imov_lograd)."', ";     }else{ $qSET .= " cod_logr          = NULL, "; }
		      $qSET .= 							  " endereco_imov     = '".mysql_real_escape_string($imov_ender)."', "; 
		      $qSET .= 							  " nrendereco_imov   = '".mysql_real_escape_string($imov_num)."', ";   
		      $qSET .= 							  " cpendereco_imov   = '".mysql_real_escape_string($imov_compl)."', "; 
		      if($imov_uf!='0'){         $qSET .= " cod_uf            = '".mysql_real_escape_string($imov_uf)."', ";         }else{ $qSET .= " cod_uf             = NULL, "; }
		      if($imov_cidade!='0'){     $qSET .= " cod_municipio     = '".mysql_real_escape_string($imov_cidade)."', ";     }else{ $qSET .= " cod_municipio      = NULL, "; }
		      if($imov_bairro!='0'){     $qSET .= " cod_bairro        = '".mysql_real_escape_string($imov_bairro)."', ";     }else{ $qSET .= " cod_bairro         = NULL, "; }
		      $qSET .= 							  " cep_imov          = '".mysql_real_escape_string($f_imov_cep)."', ";
		      if($imov_tp_morad!='x'){   $qSET .= " tpmoradia_imov    = '".mysql_real_escape_string($imov_tp_morad)."', ";   }else{ $qSET .= " tpmoradia_imov     = NULL, "; }
		      if($imov_terreo!='x'){     $qSET .= " terreo_imov       = '".mysql_real_escape_string($imov_terreo)."', ";     }else{ $qSET .= " terreo_imov        = NULL, "; }
		      if($imov_tb_dp_cnd!='x'){  $qSET .= " tmbdspcndop_imov  = '".mysql_real_escape_string($imov_tb_dp_cnd)."', ";  }else{ $qSET .= " tmbdspcndop_imov   = NULL, "; }
		      if($imov_incomb!='x'){     $qSET .= " incomb_imov       = '".mysql_real_escape_string($imov_incomb)."', ";     }else{ $qSET .= " incomb_imov        = NULL, "; }
		      if($imov_rural_fav!='x'){  $qSET .= " ruralfav_imov     = '".mysql_real_escape_string($imov_rural_fav)."', ";  }else{ $qSET .= " ruralfav_imov      = NULL, "; }
		      if($imov_em_constr!='x'){  $qSET .= " emconstr_imov     = '".mysql_real_escape_string($imov_em_constr)."' ";   }else{ $qSET .= " emconstr_imov      = NULL ";  }
		  		$db->query="UPDATE imovel SET $qSET WHERE cod_ppst = '".mysql_real_escape_string($cod_insert_ppst)."'";
		  		$db->query();
		  		//print $db->query.'<hr>';
		    }
			}
	    
	  ## ------------------------------------------------------------------------------------------- ##
	  ## DADOS DO VENDEDOR ############################################################################
		  $f_vend_cep    = preg_replace('/\D/i','',$vend_cep);
		  $f_vend_fone   = preg_replace('/\D/i','',$vend_fone);

		  if($cod_insert_vend==''){
	      $qCMP = $qVAL = '';
	      $qCMP .= " cod_ppst,";         						$qVAL .= " '".mysql_real_escape_string($cod_insert_ppst)."', ";
	      $qCMP .= " tipo_vend, ";       						$qVAL .= " '".mysql_real_escape_string($vend_tipo)."', ";
	      $qCMP .= " nome_vend, ";       						$qVAL .= " '".mysql_real_escape_string($vend_nome)."', ";
	      $qCMP .= " nick_vend, ";       						$qVAL .= " '".mysql_real_escape_string($vend_nick)."', ";
	      $qCMP .= " endereco_vend, ";   						$qVAL .= " '".mysql_real_escape_string($vend_ender)."', ";
	      $qCMP .= " nrendereco_vend, "; 						$qVAL .= " '".mysql_real_escape_string($vend_num)."', ";
	      $qCMP .= " cpendereco_vend, "; 						$qVAL .= " '".mysql_real_escape_string($vend_compl)."', ";
	      $qCMP .= " cod_logr, ";        if($vend_logr!='0'){   $qVAL .= " '".mysql_real_escape_string($vend_logr)."', ";   }else{ $qVAL .= " NULL, "; }
	      $qCMP .= " cod_uf, ";          if($vend_uf!='0'){     $qVAL .= " '".mysql_real_escape_string($vend_uf)."', ";     }else{ $qVAL .= " NULL, "; }
	      $qCMP .= " cod_municipio, ";   if($vend_cidade!='0'){ $qVAL .= " '".mysql_real_escape_string($vend_cidade)."', "; }else{ $qVAL .= " NULL, "; }
	      $qCMP .= " cod_bairro, ";      if($vend_bairro!='0'){ $qVAL .= " '".mysql_real_escape_string($vend_bairro)."', "; }else{ $qVAL .= " NULL, "; }
	      $qCMP .= " cep_vend, ";        						$qVAL .= " '".mysql_real_escape_string($f_vend_cep)."', ";
	      $qCMP .= " telefone_vend, ";   						$qVAL .= " '".mysql_real_escape_string($f_vend_fone)."', ";
	      $qCMP .= " nrcc_vend, ";       						$qVAL .= " '".mysql_real_escape_string($vend_nrcc)."', ";
	      $qCMP .= " dvcc_vend, ";       						$qVAL .= " '".mysql_real_escape_string($vend_dvcc)."', ";
	      $qCMP .= " nrag_vend ";        						$qVAL .= " '".mysql_real_escape_string($vend_nrag)."' ";
	  		$db->query="INSERT INTO vendedor ($qCMP) VALUES ($qVAL)";
	  		$db->query();
	  		//print $db->query.'<hr>';
	    }else{
	    	$qSET = '';
	      $qSET .= " tipo_vend       = '".mysql_real_escape_string($vend_tipo)."', "; 
	      $qSET .= " nome_vend       = '".mysql_real_escape_string($vend_nome)."', "; 
	      $qSET .= " nick_vend       = '".mysql_real_escape_string($vend_nick)."', "; 
	      $qSET .= " endereco_vend   = '".mysql_real_escape_string($vend_ender)."', "; 
	      $qSET .= " nrendereco_vend = '".mysql_real_escape_string($vend_num)."', "; 
	      $qSET .= " cpendereco_vend = '".mysql_real_escape_string($vend_compl)."', "; 
	      $qSET .= " cod_logr        = ".(($vend_logr!='0'  )?"'".mysql_real_escape_string($vend_logr)."'"  :'NULL').", ";
	      $qSET .= " cod_uf          = ".(($vend_uf!='0'    )?"'".mysql_real_escape_string($vend_uf)."'"    :'NULL').", ";
	      $qSET .= " cod_municipio   = ".(($vend_cidade!='0')?"'".mysql_real_escape_string($vend_cidade)."'":'NULL').", ";
	      $qSET .= " cod_bairro      = ".(($vend_bairro!='0')?"'".mysql_real_escape_string($vend_bairro)."'":'NULL').", ";
	      $qSET .= " cep_vend        = '".mysql_real_escape_string($f_vend_cep)."', "; 
	      $qSET .= " telefone_vend   = '".mysql_real_escape_string($f_vend_fone)."', "; 
	      $qSET .= " nrcc_vend       = '".mysql_real_escape_string($vend_nrcc)."', "; 
	      $qSET .= " dvcc_vend       = '".mysql_real_escape_string($vend_dvcc)."', "; 
	      $qSET .= " nrag_vend       = '".mysql_real_escape_string($vend_nrag)."' "; 
	  		$db->query="UPDATE vendedor SET $qSET WHERE cod_ppst = '".mysql_real_escape_string($cod_insert_ppst)."'";
	  		$db->query();
	  		//print $db->query.'<hr>';
	    }

 		  $f_vend_cnpj   = preg_replace('/\D/i','',$vend_cnpj);
	    if($vend_tipo==2){
		    if($cod_insert_vend_j==''){
		      $qCMP = $qVAL = '';
		      $qCMP .= " cod_ppst,";        $qVAL .= " '".mysql_real_escape_string($cod_insert_ppst)."', ";
		      $qCMP .= " cnpj_vjur ";       $qVAL .= " '".mysql_real_escape_string($f_vend_cnpj)."' ";
		      $qCMP .= " isenpis_vjur ";    $qVAL .= " '".mysql_real_escape_string($vend_pispasep)."' ";
		      $qCMP .= " isencofins_vjur "; $qVAL .= " '".mysql_real_escape_string($vend_cofins)."' ";
		      $qCMP .= " isencsll_vjur ";   $qVAL .= " '".mysql_real_escape_string($vend_csll)."' ";
		      $qCMP .= " cod_cnae ";        $qVAL .= " '".mysql_real_escape_string($vend_atveco)."' ";
		  		$db->query="INSERT INTO vendjur ($qCMP) VALUES ($qVAL)";
		  		$db->query();
		  		//print $db->query.'<hr>';
		    }else{
		    	$qSET = '';
		      $qSET .= " cnpj_vjur       = '".mysql_real_escape_string($f_vend_cnpj)."', "; 
		      $qSET .= " isenpis_vjur    = '".mysql_real_escape_string($vend_pispasep)."', "; 
		      $qSET .= " isencofins_vjur = '".mysql_real_escape_string($vend_cofins)."', "; 
		      $qSET .= " isencsll_vjur   = '".mysql_real_escape_string($vend_csll)."', "; 
		      $qSET .= " cod_cnae        = '".mysql_real_escape_string($vend_atveco)."' "; 
		  		$db->query="UPDATE vendjur SET $qSET WHERE cod_ppst = '".mysql_real_escape_string($cod_insert_ppst)."'";
		  		$db->query();
		  		//print $db->query.'<hr>';
		    }
		    // apaga os dados de Pessoa Fisica
	  		$db->query="DELETE FROM vendfis WHERE cod_ppst = '".mysql_real_escape_string($cod_insert_ppst)."'";
	  		$db->query();
			  $vend_cpf = $vend_sexo = $vend_nasc = $vend_nacion = $vend_natural           = '';
			  $vend_tpdoc = $vend_rg = $vend_dtrg = $vend_orgrg = $vend_civil = $vend_inss = '';
			  $vend_nconj = $vend_npai = $vend_nmae = $vend_profiss = $vend_rendim         = '';
	    }
		  
	  	$f_vend_cpf    = mysql_real_escape_string(preg_replace('/\D/i','',$vend_cpf));
		$f_vend_nasc   = mysql_real_escape_string(((!empty($vend_nasc))?$utils->formataData($vend_nasc):''));
		$f_vend_rg     = mysql_real_escape_string(preg_replace('/\D/i','',$vend_rg));
		$f_vend_dtrg   = mysql_real_escape_string(((!empty($vend_dtrg))?$utils->formataData($vend_dtrg):''));
 	    if($vend_tipo==1){
		    if($cod_insert_vend_f==''){
		      $qCMP = $qVAL = '';
		      $qCMP .= " cod_ppst,";              $qVAL .= " '".mysql_real_escape_string($cod_insert_ppst)."', ";
		      $qCMP .= " cpf_vfisica, ";          $qVAL .= " '".mysql_real_escape_string($f_vend_cpf)."', ";
		      $qCMP .= " sexo_vfisica, ";         if($vend_sexo!=''){     $qVAL .= " '".mysql_real_escape_string($vend_sexo)."', ";    }else{ $qVAL .= " NULL, "; }
		      $qCMP .= " dtnascimento_vfisica, "; if($f_vend_nasc!=''){   $qVAL .= " '".mysql_real_escape_string($f_vend_nasc)."', ";  }else{ $qVAL .= " NULL, "; }
		      $qCMP .= " natur_vfisica, ";        $qVAL .= " '".mysql_real_escape_string($vend_natural)."', ";
		      $qCMP .= " nrrg_vfisica, ";         $qVAL .= " '".mysql_real_escape_string($f_vend_rg)."', ";
		      $qCMP .= " dtrg_vfisica, ";         if($f_vend_dtrg!=''){   $qVAL .= " '".mysql_real_escape_string($f_vend_dtrg)."', ";  }else{ $qVAL .= " NULL, "; }
		      $qCMP .= " orgrg_vfisica, ";        $qVAL .= " '".mysql_real_escape_string($vend_orgrg)."', ";
		      $qCMP .= " nomeconj_vfisica, ";     $qVAL .= " '".mysql_real_escape_string($vend_nconj)."', ";
		      $qCMP .= " nomepai_vfisica, ";      $qVAL .= " '".mysql_real_escape_string($vend_npai)."', ";
		      $qCMP .= " nomemae_vfisica, ";      $qVAL .= " '".mysql_real_escape_string($vend_nmae)."', ";
		      $qCMP .= " vlrenda_vfisica, ";      $qVAL .= " '".mysql_real_escape_string($vend_rendim)."', ";
		      $qCMP .= " nrinss_vfisica, ";       $qVAL .= " '".mysql_real_escape_string($vend_inss)."', ";
		      $qCMP .= " cod_pais, ";             if($vend_nacion!='0'){  $qVAL .= " '".mysql_real_escape_string($vend_nacion)."', ";  }else{ $qVAL .= " NULL, "; }
		      $qCMP .= " cod_tpdoc, ";            if($vend_tpdoc!='0'){   $qVAL .= " '".mysql_real_escape_string($vend_tpdoc)."', ";   }else{ $qVAL .= " NULL, "; }
		      $qCMP .= " cod_prof, ";             if($vend_profiss!='0'){ $qVAL .= " '".mysql_real_escape_string($vend_profiss)."', "; }else{ $qVAL .= " NULL, "; }
		      $qCMP .= " cod_estciv ";            if($vend_civil!='0'){   $qVAL .= " '".mysql_real_escape_string($vend_civil)."' ";    }else{ $qVAL .= " NULL ";  }
		  		$db->query="INSERT INTO vendfis ($qCMP) VALUES ($qVAL)";
		  		$db->query();
		  		//print $db->query.'<hr>';
		    }else{
		    	$qSET = '';
		      $qSET .= " cpf_vfisica          = '".mysql_real_escape_string($f_vend_cpf)."', "; 
		      $qSET .= " sexo_vfisica         = ".(($vend_sexo!='')?"'".mysql_real_escape_string($vend_sexo)."'":'NULL').", ";
		      $qSET .= " dtnascimento_vfisica = ".(($f_vend_nasc!='')?"'".mysql_real_escape_string($f_vend_nasc)."'":'NULL').", ";
		      $qSET .= " natur_vfisica        = '".mysql_real_escape_string($vend_natural)."', "; 
		      $qSET .= " nrrg_vfisica         = '".mysql_real_escape_string($f_vend_rg)."', "; 
		      $qSET .= " dtrg_vfisica         = ".(($f_vend_dtrg!='')?"'".mysql_real_escape_string($f_vend_dtrg)."'":'NULL').", ";
		      $qSET .= " orgrg_vfisica        = '".mysql_real_escape_string($vend_orgrg)."', "; 
		      $qSET .= " nomeconj_vfisica     = '".mysql_real_escape_string($vend_nconj)."', "; 
		      $qSET .= " nomepai_vfisica      = '".mysql_real_escape_string($vend_npai)."', "; 
		      $qSET .= " nomemae_vfisica      = '".mysql_real_escape_string($vend_nmae)."', "; 
		      $qSET .= " vlrenda_vfisica      = '".mysql_real_escape_string($vend_rendim)."', "; 
		      $qSET .= " nrinss_vfisica       = '".mysql_real_escape_string($vend_inss)."', "; 
		      $qSET .= " cod_pais             = ".(($vend_nacion!='0' )?"'".mysql_real_escape_string($vend_nacion)."'" :'NULL').", ";
		      $qSET .= " cod_tpdoc            = ".(($vend_tpdoc!='0'  )?"'".mysql_real_escape_string($vend_tpdoc)."'"  :'NULL').", ";
		      $qSET .= " cod_prof             = ".(($vend_profiss!='0')?"'".mysql_real_escape_string($vend_profiss)."'":'NULL').", ";
		      $qSET .= " cod_estciv           = ".(($vend_civil!='0'  )?"'".mysql_real_escape_string($vend_civil)."'"  :'NULL')." ";
		  		$db->query="UPDATE vendfis SET $qSET WHERE cod_ppst = '".mysql_real_escape_string($cod_insert_ppst)."'";
		  		$db->query();
		  		//print $db->query.'<hr>';
		    }
		    // apaga os dados de Pessoa Juridica
	  		$db->query="DELETE FROM vendjursocio WHERE cod_ppst = '".mysql_real_escape_string($cod_insert_ppst)."'";
	  		$db->query();
			  $vend_s_nome = $vend_s_nabrev = $vend_s_logr = $vend_s_ender = $vend_s_num   = '';
			  $vend_s_compl = $vend_s_uf = $vend_s_cidade = $vend_s_bairro = $vend_s_cep   = '';
			  $vend_s_fone = $vend_s_cpf = $vend_s_nacion = $vend_s_sexo = $vend_codvjsoc  = '';

			  $db->query="DELETE FROM vendjur      WHERE cod_ppst = '".mysql_real_escape_string($cod_insert_ppst)."'";
	  		$db->query();
	  		$vend_cnpj = $vend_pispasep = $vend_cofins = $vend_csll = $vend_atveco  = '';
			}

	  ## ------------------------------------------------------------------------------------------- ##
	  ## DADOS DO DEVEDOR SOLIDARIO ###################################################################
	  	$f_dsol_cep    = preg_replace('/\D/i','',$dsol_cep);
	  	$f_dsol_fone   = preg_replace('/\D/i','',$dsol_fone);
	  	$f_dsol_cpf    = preg_replace('/\D/i','',$dsol_cpf);
	    if($cod_insert_devsol==''){
	      $qCMP = $qVAL = '';
	      $qCMP .= " cod_ppst,";           $qVAL .= " '".mysql_real_escape_string($cod_insert_ppst)."', ";
	      $qCMP .= " nome_devsol, ";       $qVAL .= " '".mysql_real_escape_string($dsol_nome)."', ";
	      $qCMP .= " nick_devsol, ";       $qVAL .= " '".mysql_real_escape_string($dsol_nick)."', ";
	      $qCMP .= " cod_logr, ";          if($dsol_logr!='0'){   $qVAL .= " '".mysql_real_escape_string($dsol_logr)."', ";   }else{ $qVAL .= " NULL, "; }
	      $qCMP .= " endereco_devsol, ";   $qVAL .= " '".mysql_real_escape_string($dsol_ender)."', ";
	      $qCMP .= " nrendereco_devsol, "; $qVAL .= " '".mysql_real_escape_string($dsol_num)."', ";
	      $qCMP .= " cpendereco_devsol, "; $qVAL .= " '".mysql_real_escape_string($dsol_compl)."', ";
	      $qCMP .= " cod_bairro, ";        if($dsol_bairro!='0'){ $qVAL .= " '".mysql_real_escape_string($dsol_bairro)."', "; }else{ $qVAL .= " NULL, "; }
	      $qCMP .= " cep_devsol, ";        $qVAL .= " '".mysql_real_escape_string($f_dsol_cep)."', ";
	      $qCMP .= " cod_uf, ";            if($dsol_uf!='0'){     $qVAL .= " '".mysql_real_escape_string($dsol_uf)."', ";     }else{ $qVAL .= " NULL, "; }
	      $qCMP .= " cod_municipio, ";     if($dsol_cidade!='0'){ $qVAL .= " '".mysql_real_escape_string($dsol_cidade)."', "; }else{ $qVAL .= " NULL, "; }
	      $qCMP .= " telefone_devsol, ";   $qVAL .= " '".mysql_real_escape_string($f_dsol_fone)."', ";
	      $qCMP .= " cpf_devsol, ";        $qVAL .= " '".mysql_real_escape_string($f_dsol_cpf)."', ";
	      $qCMP .= " sexo_devsol, ";       if($dsol_sexo!=''){    $qVAL .= " '".mysql_real_escape_string($dsol_sexo)."', ";   }else{ $qVAL .= " NULL, "; }
	      $qCMP .= " cod_pais ";           if($dsol_nacion!='0'){ $qVAL .= " '".mysql_real_escape_string($dsol_nacion)."' ";  }else{ $qVAL .= " NULL ";  }
	  		$db->query="INSERT INTO devsol ($qCMP) VALUES ($qVAL)";
	  		$db->query();
	  		//print $db->query.'<hr>';
	    }else{
	    	$qSET = '';
	      $qSET .= " nome_devsol       = '".mysql_real_escape_string($dsol_nome)."', "; 
	      $qSET .= " nick_devsol       = '".mysql_real_escape_string($dsol_nick)."', "; 
	      $qSET .= " cod_logr          = ".(($dsol_logr!='0'  )?"'".mysql_real_escape_string($dsol_logr)."'"  :'NULL').", ";
	      $qSET .= " endereco_devsol   = '".mysql_real_escape_string($dsol_ender)."', "; 
	      $qSET .= " nrendereco_devsol = '".mysql_real_escape_string($dsol_num)."', "; 
	      $qSET .= " cpendereco_devsol = '".mysql_real_escape_string($dsol_compl)."', "; 
	      $qSET .= " cod_bairro        = ".(($dsol_bairro!='0')?"'".mysql_real_escape_string($dsol_bairro)."'":'NULL').", ";
	      $qSET .= " cep_devsol        = '".mysql_real_escape_string($f_dsol_cep)."', "; 
	      $qSET .= " cod_uf            = ".(($dsol_uf!='0'    )?"'".mysql_real_escape_string($dsol_uf)."'"    :'NULL').", ";
	      $qSET .= " cod_municipio     = ".(($dsol_cidade!='0')?"'".mysql_real_escape_string($dsol_cidade)."'":'NULL').", ";
	      $qSET .= " telefone_devsol   = '".mysql_real_escape_string($f_dsol_fone)."', "; 
	      $qSET .= " cpf_devsol        = '".mysql_real_escape_string($f_dsol_cpf)."', "; 
	      $qSET .= " sexo_devsol       = ".(($dsol_sexo!=''   )?"'".mysql_real_escape_string($dsol_sexo)."'"  :'NULL').", ";
	      $qSET .= " cod_pais          = ".(($dsol_nacion!='0')?"'".mysql_real_escape_string($dsol_nacion)."'":'NULL')." ";
	  		$db->query="UPDATE devsol SET $qSET WHERE cod_ppst = '".mysql_real_escape_string($cod_insert_ppst)."'";
	  		$db->query();
	  		//print $db->query.'<hr>';
	    }
		    
	  ## ------------------------------------------------------------------------------------------- ##
	  ## checklist ###################################################################
		
	  if(is_array($_POST['ckl_doc_dt_ped'])){
		  foreach ($_POST['ckl_doc_dt_ped'] as $key=>$value) {
				$c_check=($_POST['ckl_doc_check'][$key]?1:0);
				$c_dt_ped=(!empty($_POST['ckl_doc_dt_ped'][$key])?"'".utils::formataData($_POST['ckl_doc_dt_ped'][$key])."'":"NULL");
				$c_dt_emis=(!empty($_POST['ckl_doc_dt_emis'][$key])?"'".utils::formataData($_POST['ckl_doc_dt_emis'][$key])."'":"NULL");
				$c_desc=mysql_real_escape_string($_POST['ckl_doc_desc'][$key]);
						
				$db->query="replace into checklist(
								COD_DOCM,
								COD_PSST,
								COD_MUNICIPIO,
								COD_UF,
								DTSOLICITACAO_CLST,
								DTEMISSAO_CLST,
								FLGSTATUS_CLST,
								OBS_CLST
							) values (
								'".mysql_real_escape_string($key)."',
								'".mysql_real_escape_string($cod_insert_ppst)."',
								'".mysql_real_escape_string($_POST['imov_cidade_ck'])."',
								'".mysql_real_escape_string($_POST['imov_uf_ck'])."',
								".mysql_real_escape_string($c_dt_ped).",
								".mysql_real_escape_string($c_dt_emis).",
								'".mysql_real_escape_string($c_check)."',
								'".mysql_real_escape_string($c_desc)."'
							)";
				$db->query();
		  }
	  }
	  
	  if($acaoProposta=='imov_reprov' && $calculo_error==''){ header("Location: atend_historico.php?cod_proposta=".$_GET['cod_proposta']); exit(); }
	  if($acaoProposta=='concluir' && $calculo_error==''){ header("Location: atend_historico.php?cod_proposta=".$_GET['cod_proposta']); exit(); }
	}elseif($acaoProposta=='calcular'){
		// Grava LOG de Calculo // ----------------------------------------------------
		$cLOGIN->insert_log(1,1,'Cálculo da Avaliação da Proposta - CODPPST:'.$cod_insert_ppst);
	}elseif($acaoProposta=='evento'){
		// Grava LOG de NOVO EVENTO // ----------------------------------------------------
		$cLOGIN->insert_log(4,7,'Inclusão de Evento - CODPPST:'.$cod_insert_ppst);
		$cLOGIN->insert_history($cod_insert_ppst,2,$f_novo_evento);
	}elseif($acaoProposta=='addsocio'){
		// Insere novo socio // ----------------------------------------------------
    $f_vend_s_cep  = preg_replace('/\D/i','',$vend_s_cep);
	  $f_vend_s_fone = preg_replace('/\D/i','',$vend_s_fone);
	  $f_vend_s_cpf  = preg_replace('/\D/i','',$vend_s_cpf);
	  
    if($cod_insert_vend_j==''){
  		$db->query="INSERT INTO vendjur (cod_ppst) VALUES ('".mysql_real_escape_string($cod_insert_ppst)."')";
  		$db->query();
  		//print $db->query.'<hr>';
    }

    $qCMP = $qVAL = '';
    $qCMP .= " cod_ppst,";          $qVAL .= " '".mysql_real_escape_string($cod_insert_ppst)."', ";
    $qCMP .= " nome_vjsoc, ";       $qVAL .= " '".mysql_real_escape_string($vend_s_nome)."', ";
    $qCMP .= " nick_vjsoc, ";       $qVAL .= " '".mysql_real_escape_string($vend_s_nabrev)."', ";
    $qCMP .= " endereco_vjsoc, ";   $qVAL .= " '".mysql_real_escape_string($vend_s_ender)."', ";
    $qCMP .= " nrendereco_vjsoc, "; $qVAL .= " '".mysql_real_escape_string($vend_s_num)."', ";
    $qCMP .= " cpendereco_vjsoc, "; $qVAL .= " '".mysql_real_escape_string($vend_s_compl)."', ";
    $qCMP .= " cep_vjsoc, ";        $qVAL .= " '".mysql_real_escape_string($f_vend_s_cep)."', ";
    $qCMP .= " telefone_vjsoc, ";   $qVAL .= " '".mysql_real_escape_string($f_vend_s_fone)."', ";
    $qCMP .= " cpf_vjsoc, ";        $qVAL .= " '".mysql_real_escape_string($f_vend_s_cpf)."', ";
    $qCMP .= " sexo_vjsoc, ";       if($vend_s_sexo!=''){    $qVAL .= " '".mysql_real_escape_string($vend_s_sexo)."', ";   }else{ $qVAL .= " NULL, "; }
    $qCMP .= " cod_pais, ";         if($vend_s_nacion!='0'){ $qVAL .= " '".mysql_real_escape_string($vend_s_nacion)."', "; }else{ $qVAL .= " NULL, "; }
    $qCMP .= " cod_logr, ";         if($vend_s_logr!='0'){   $qVAL .= " '".mysql_real_escape_string($vend_s_logr)."', ";   }else{ $qVAL .= " NULL, "; }
    $qCMP .= " cod_bairro, ";       if($vend_s_bairro!='0'){ $qVAL .= " '".mysql_real_escape_string($vend_s_bairro)."', "; }else{ $qVAL .= " NULL, "; }
    $qCMP .= " cod_uf, ";           if($vend_s_uf!='0'){     $qVAL .= " '".mysql_real_escape_string($vend_s_uf)."', ";     }else{ $qVAL .= " NULL, "; }
    $qCMP .= " cod_municipio ";     if($vend_s_cidade!='0'){ $qVAL .= " '".mysql_real_escape_string($vend_s_cidade)."' ";  }else{ $qVAL .= " NULL ";  }
		$db->query="INSERT INTO vendjursocio ($qCMP) VALUES ($qVAL)";
		$db->query();
		//print $db->query.'<hr>';
		
		$cLOGIN->insert_log(4,7,'Inclusão de Sócio - CODPPST:'.$cod_insert_ppst);
 		$cLOGIN->insert_history($cod_insert_ppst,1,'Inclusão de Sócio');
	}elseif($acaoProposta=='savesocio'){
		// Salva alteracao dos dados do socio // ----------------------------------------------------
		if(!empty($vend_codvjsoc)){
	    $f_vend_s_cep  = preg_replace('/\D/i','',$vend_s_cep);
		  $f_vend_s_fone = preg_replace('/\D/i','',$vend_s_fone);
		  $f_vend_s_cpf  = preg_replace('/\D/i','',$vend_s_cpf);
	
		  $qSET = '';
	    $qSET .= " cod_ppst         = '".mysql_real_escape_string($cod_insert_ppst)."', "; 
	    $qSET .= " nome_vjsoc       = '".mysql_real_escape_string($vend_s_nome)."', "; 
	    $qSET .= " nick_vjsoc       = '".mysql_real_escape_string($vend_s_nabrev)."', "; 
	    $qSET .= " endereco_vjsoc   = '".mysql_real_escape_string($vend_s_ender)."', "; 
	    $qSET .= " nrendereco_vjsoc = '".mysql_real_escape_string($vend_s_num)."', "; 
	    $qSET .= " cpendereco_vjsoc = '".mysql_real_escape_string($vend_s_compl)."', "; 
	    $qSET .= " cep_vjsoc        = '".mysql_real_escape_string($f_vend_s_cep)."', "; 
	    $qSET .= " telefone_vjsoc   = '".mysql_real_escape_string($f_vend_s_fone)."', "; 
	    $qSET .= " cpf_vjsoc        = '".mysql_real_escape_string($f_vend_s_cpf)."', "; 
	    $qSET .= " sexo_vjsoc       = ".(($vend_s_sexo!=''   )?"'".mysql_real_escape_string($vend_s_sexo)."'"  :'NULL').", ";
	    $qSET .= " cod_pais         = ".(($vend_s_nacion!='0')?"'".mysql_real_escape_string($vend_s_nacion)."'":'NULL').", ";
	    $qSET .= " cod_logr         = ".(($vend_s_logr!='0'  )?"'".mysql_real_escape_string($vend_s_logr)."'"  :'NULL').", ";
	    $qSET .= " cod_bairro       = ".(($vend_s_bairro!='0')?"'".mysql_real_escape_string($vend_s_bairro)."'":'NULL').", ";
	    $qSET .= " cod_uf           = ".(($vend_s_uf!='0'    )?"'".mysql_real_escape_string($vend_s_uf)."'"    :'NULL').", ";
	    $qSET .= " cod_municipio    = ".(($vend_s_cidade!='0')?"'".mysql_real_escape_string($vend_s_cidade)."'":'NULL')." ";
			$db->query="UPDATE vendjursocio SET $qSET WHERE cod_vjsoc = '".mysql_real_escape_string($vend_codvjsoc)."'";
			$db->query();
			//print $db->query.'<hr>';
			$cLOGIN->insert_log(4,7,'Alteração de dados de Sócio - CODPPST:'.$cod_insert_ppst);
	 		$cLOGIN->insert_history($cod_insert_ppst,1,'Alteração de dados de Sócio');
		}
	}elseif($acaoProposta=='delsocio'){
		if(!empty($vend_codvjsoc)){
  		$db->query="DELETE FROM vendjursocio WHERE cod_vjsoc = '".mysql_real_escape_string($vend_codvjsoc)."'";
  		$db->query();
			$cLOGIN->insert_log(4,7,'Exclusão de Sócio - CODPPST:'.$cod_insert_ppst);
	 		$cLOGIN->insert_history($cod_insert_ppst,1,'Exclusão de Sócio');
		}
	}elseif($acaoProposta=='editsocio'){
		// Carrega os dados do socio para alteracao // ----------------------------------------------------
		$displaySocioForm  = 'display:block;';
		$displayBtAddSocio = 'display:none;';
		$acaoProposta      = 'alterform';
		$acaoAtualizaUF    = 'alterform';
		if(!empty($vend_codvjsoc)){
		  $db->query="select * from vendjursocio where COD_VJSOC = '".mysql_real_escape_string($vend_codvjsoc)."' ";
		  $db->query();
		  if($db->qrcount>0){
			  $vend_s_nome   = $db->qrdata[0]['NOME_VJSOC'];
			  $vend_s_nabrev = $db->qrdata[0]['NICK_VJSOC'];
			  $vend_s_logr   = $db->qrdata[0]['COD_LOGR'];
			  $vend_s_ender  = $db->qrdata[0]['ENDERECO_VJSOC'];
			  $vend_s_num    = $db->qrdata[0]['NRENDERECO_VJSOC'];
			  $vend_s_compl  = $db->qrdata[0]['CPENDERECO_VJSOC'];
			  $vend_s_uf     = $db->qrdata[0]['COD_UF'];
			  $vend_s_cidade = $db->qrdata[0]['COD_MUNICIPIO'];
			  $vend_s_bairro = $db->qrdata[0]['COD_BAIRRO'];
			  $vend_s_cep    = $db->qrdata[0]['CEP_VJSOC'];
			  $vend_s_fone   = $db->qrdata[0]['TELEFONE_VJSOC'];
			  $vend_s_cpf    = $db->qrdata[0]['CPF_VJSOC'];
			  $vend_s_nacion = $db->qrdata[0]['COD_PAIS'];
			  $vend_s_sexo   = $db->qrdata[0]['SEXO_VJSOC'];
			  
			  $vend_s_cep        = $utils->formataCep($vend_s_cep);
			  $vend_s_fone       = $utils->formataTelefone($vend_s_fone);
			  $vend_s_cpf        = $utils->formataCPF($vend_s_cpf);
		  }
		}
	}elseif($acaoProposta=='alterform'){
		$displaySocioForm  = 'display:block;';
		$displayBtAddSocio = 'display:none;';
		$acaoAtualizaUF    = 'alterform';
	}elseif($acaoProposta=='socioform'){
		$displaySocioForm  = 'display:block;';
		$displayBtAddSocio = 'display:none;';
		$acaoAtualizaUF    = 'socioform';
	}elseif($acaoProposta=='novosocio'){
		$displaySocioForm  = 'display:block;';
		$displayBtAddSocio = 'display:none;';
		$acaoAtualizaUF    = 'socioform';
	}

	if($acaoProposta=='addsocio' || $acaoProposta=='savesocio' || $acaoProposta=='delsocio'){
	  $vend_s_nome = $vend_s_nabrev = $vend_s_logr = $vend_s_ender = $vend_s_num   = '';
	  $vend_s_compl = $vend_s_uf = $vend_s_cidade = $vend_s_bairro = $vend_s_cep   = '';
	  $vend_s_fone = $vend_s_cpf = $vend_s_nacion = $vend_s_sexo = $vend_codvjsoc  = '';
	}
}

$f_valor_compra  = $utils->formataMoeda($valor_compra);
$f_valor_entrada = $utils->formataMoeda($valor_entrada);
$f_valor_fgts    = $utils->formataMoeda($valor_fgts);
$f_valor_total   = $utils->formataMoeda($valor_total);
$f_valor_seguro  = $utils->formataMoeda($valor_seguro);
$f_valor_manut   = $utils->formataMoeda($valor_manut);

$f_valorMaxFinan = $utils->formataMoeda($valorMaxFinan);
$f_valorMaxPrest = $utils->formataMoeda($valorMaxPrest);
$f_prazoMaxFinan = intval($prazoMaxFinan);
$f_taxaJurosAno  = $utils->formataFloat($taxaJurosAno,2);

$f_imov_area     = $utils->formataFloat($imov_area,0);
$f_imov_vl_aval  = $utils->formataMoeda($imov_vl_aval);

$trava_valores = '';
if($flg_aguardando!='N'){
	$mensagem_prop->setMensagem('Aguardando aprovação dos valores do Financiamento', MSG_ALERTA);
	if($flg_aguardando=='E'){
		$trava_valores = 'disabled';
	}
}

if($_POST && $errMessage==''){
	$mensagem_post->setMensagem('Dados gravados com sucesso!', MSG_SUCESSO);
}

$resultMessage = '';
if($valorAprov!='' && $valorAprov!=0){
	$resultMessage .= '<u><b>Valores aprovados pela Previ</b></u><br>';
	$resultMessage .= 'Financiamento: <b>R$ '.$utils->formataMoeda($valorAprov).'</b><br>';
	$resultMessage .= 'Parcela: <b>R$ '.$utils->formataMoeda($parcelaAprov).'</b><br>';
	$resultMessage .= 'Prazo: <b>'.$prazoAprov.' meses</b><br>';
	//$resultMessage .= 'Entrada: <b>R$ '.$utils->formataMoeda($entradaAprov).'</b><br>';
}

$obrig = '<span class="obrig"> *</span>';
?>
<script language="JavaScript" src="./js/diversos.js"></script>
<script language="JavaScript" src="./js/atendente.js"></script>
<script language="javascript" type="text/javascript" src="js/ajaxapi.js"></script>

<?
	$dta = split('[-\/\ ]',$data_sistema);
	$data_sistema = $dta[2].'/'.$dta[1].'/'.$dta[0];
?>
<script language="javascript">
	var dataSistema  = '<?=$data_sistema;?>';
	
	var aprovFinanc  = '<?=$valorAprov;?>';
	var aprovParcela = '<?=$parcelaAprov;?>';
	var aprovPrazo   = '<?=$prazoAprov;?>';
	var aprovEntrada = '<?=$entradaAprov;?>';

	var solicCompra  = '<?=$valor_compra;?>';
	var solicEntrada = '<?=$valor_entrada;?>';
	var solicFGTS    = '<?=$valor_fgts;?>';
	var solicParcela = '<?=$f_prop_prest;?>';
	var solicPrazo   = '<?=$prop_prazo;?>';
	
	var flgPrevi     = '<?=$flg_aguardando;?>';
	var flgAprov     = '<?=$flg_aprovacao;?>';

</script>
      
<form name="proposta" id="proposta" method="post" action="<?=$php_self;?>">
	<div class="alr">
		<a href="#checklist"><img src="images/buttons/bt_checklist.gif" alt="Ver Check List" class="im" /></a>
		<a href="#historico"><img src="images/buttons/bt_historico.gif" alt="Ver Histórico" class="im" /></a>
	</div>
  <?
    ### LIMITES ##################################################################### 
  ?>
	
  <?=$mensagem_post->getMessageBox();?>
  <a name="limites"></a>
  <br>
  <table width="100%" cellpadding=0 cellspacing=0 border=0><tr>
  	<td align="left"><b>Limites</b></td>
  	<td align="right"><span class="obrig">* campos obrigatórios</span></td>
  </tr></table>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
        <table cellpadding=0 cellspacing=5 border=0>
          <tr>
            <td align="right">Valor Máximo para Financiamento:</td>
            <td align="left"><b>R$ <?=$f_valorMaxFinan;?></b></td>
          </tr>
          <tr>
            <td align="right">Valor Máximo por Prestação:</td>
            <td align="left"><b>R$ <?=$f_valorMaxPrest;?></b></td>
          </tr>
          <tr>
            <td align="right">Prazo Máximo do Financiamento:</td>
            <td align="left"><b><?=$f_prazoMaxFinan;?> meses</b></td>
          </tr>
        </table>
        <input type="hidden" name="valorMaxFinan" id="valorMaxFinan" value="<?=$valorMaxFinan;?>">
        <input type="hidden" name="valorMaxPrest" id="valorMaxPrest" value="<?=$valorMaxPrest;?>">
        <input type="hidden" name="prazoMaxFinan" id="prazoMaxFinan" value="<?=$prazoMaxFinan;?>">
        <input type="hidden" name="alterouValores" id="alterouValores" value="N">
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
	</div>

  <?
    ### PROPOSTA ##################################################################### 
  ?>
  <a name="proposta" id="proposta"></a>
  <br><b>Proposta</b>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
      <?=$mensagem_prop->getMessageBox();?>
			<div style="float: left; width:400px;">
	      <table cellpadding=0 cellspacing=5 border=0>
	        <tr style="display:none;">
	          <td align="right">Tabela:<?=$obrig;?></td>
	          <td align="left"><b>
	          	<?
	          	$tipo_simulador=2;
	          	?>
	            <input type="radio" class="rd" name="tipo_simulador" id="tipo_simulador" value="1" <?=($tipo_simulador=='1')?'checked':'';?> onClick="atualizaValFinan('<?=$crypt->encrypt('atualizar');?>');" > Price &nbsp;&nbsp; 
	            <input type="radio" class="rd" name="tipo_simulador" id="tipo_simulador" value="2" <?=($tipo_simulador=='2')?'checked':'';?> onClick="atualizaValFinan('<?=$crypt->encrypt('atualizar');?>');" > SAC<br></b>
	          </td>
	        </tr>
	
	        <tr>
	          <td align="right">Valor de Compra (R$):<?=$obrig;?></td>
	          <td align="left"><input type="text" name="valor_compra" id="valor_compra" style="width:80px;" value="<?=$f_valor_compra;?>" maxlength="20"
	          onKeyDown="return teclasFloat(this,event);" onKeyUp="return mascaraMoeda(this,event,'atualizaValFinan()');" onFocus="this.select();" <?=$trava_valores;?>></td>
	        </tr>
	
	        <tr>
	          <td align="right">Valor de Entrada (R$):</td>
	          <td align="left"><input type="text" name="valor_entrada" id="valor_entrada" style="width:80px;" value="<?=$f_valor_entrada;?>" maxlength="20"
	          onKeyDown="return teclasFloat(this,event);" onKeyUp="return mascaraMoeda(this,event,'atualizaValFinan()');" onFocus="this.select();" <?=$trava_valores;?>></td>
	        </tr>
	
	        <tr>
	          <td align="right">FGTS (R$):</td>
	          <td align="left"><input type="text" name="valor_fgts" id="valor_fgts" style="width:80px;" value="<?=$f_valor_fgts;?>" maxlength="20"
	          onKeyDown="return teclasFloat(this,event);" onKeyUp="return mascaraMoeda(this,event,'atualizaValFinan()');" onFocus="this.select();" <?=$trava_valores;?>></td>
	        </tr>
	        
	        <tr>
	          <td align="right">Valor do Financiamento (R$):</td>
	          <td align="left" id="valor_total" style="font-weight:bold;"><?=$f_valor_total;?></td>
	        </tr>

 	        <tr style="display:none">
	          <td align="right">Taxa de Juros ao Ano (%):</td>
	          <td align="left" style="font-weight:bold;"><?=$f_taxaJurosAno;?></td>
	        </tr>

	        <tr>
	          <td align="right">Prestação (R$):</td>
	          <td align="left" height="18">
	          	<?
	          		$checked = ($prop_prest=='')?'':'checked';
	          		$display = ($prop_prest=='')?'display:none':'';
							?>
	          	<input type="radio" class="rd" name="sel_tipo_finan" id="sel_tipo_finan" value="1" onclick="selecionaTipoFinan();" <?=$checked;?> <?=$trava_valores;?>>
	          	<span id="spnParcela" style="<?=$display;?>">
	          		<?=$obrig;?>
		          	<input type="text" name="prestacao" id="prestacao" style="width:80px;" value="<?=$prop_prest;?>" maxlength="20"
		          	onKeyDown="return teclasFloat(this,event);" onKeyUp="return mascaraMoeda(this,event,'atualizaValFinan()');" onFocus="this.select();" <?=$trava_valores;?>>
		          </span>
	          </td>
	        </tr>

	        <tr>
	          <td align="right">Prazo (em meses):</td>
	          <td align="left" height="18">
	          	<?
	          		$checked = ($prop_prazo=='')?'':'checked';
	          		$display = ($prop_prazo=='')?'display:none':'';
							?>
	          	<input type="radio" class="rd" name="sel_tipo_finan" id="sel_tipo_finan" value="2" onclick="selecionaTipoFinan();" <?=$checked;?> <?=$trava_valores;?>>
	          	<span id="spnPrazo" style="<?=$display;?>">
	          		<?=$obrig;?>
		          	<input type="text" name="prazo" id="prazo" style="width:40px;" value="<?=$prop_prazo;?>" maxlength="3"
		          	onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraInt(this,event,'atualizaValFinan()');" onFocus="this.select();" <?=$trava_valores;?>>
		          </span>
	          </td>
	        </tr>
	        
	        <tr>
	          <td align="right">Seguro (R$):</td>
	          <td align="left"><input type="text" name="valor_seguro" id="valor_seguro" style="width:80px;" value="<?=$f_valor_seguro;?>" maxlength="20"
	          onKeyDown="return teclasFloat(this,event);" onKeyUp="return mascaraMoeda(this,event);" onFocus="this.select();" <?=$trava_valores;?>></td>
	        </tr>
	
	        <tr>
	          <td align="right">Taxa Manutenção (R$):</td>
	          <td align="left"><input type="text" name="valor_manut" id="valor_manut" style="width:80px;" value="<?=$f_valor_manut;?>" maxlength="20"
	          onKeyDown="return teclasFloat(this,event);" onKeyUp="return mascaraMoeda(this,event);" onFocus="this.select();" <?=$trava_valores;?>></td>
	        </tr>
	        
	        <tr style="display:none">
	          <td align="right"></td>
	          <td align="left"><img src="images/buttons/bt_calcular.gif" alt="Calcular" class="im cursorMao" onClick="calcularProposta('<?=$crypt->encrypt("calcular");?>');" /></td>
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
    if($imov_uf!=''){
  		?>
			<br><b>Pagamento</b>
			<div class="quadroInterno">
				<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
				<div class="quadroInternoMeio">
			    		<table cellpadding=0 cellspacing=5 border=0 width="100%">
			    			<colgroup><col width="150" /><col width="*" /></colgroup>
			    			<tr>
			    			  <td align="right" valign="top">Valor do Boleto:</td>
			    			  <td align="left"><b>R$ <?=$valor_boleto;?></b></td>
			    			  <td align="right" rowspan="3" valign="bottom"><img src="images/buttons/bt_gerar_boleto.gif" alt="Gerar Boleto" class="im" onClick="gerarBoleto('<?=$_GET['cod_proposta'];?>');" /></td>
			    			</tr>
			    			<tr>
			    			  <td align="right" valign="top">Confirmação de Pagamento:</td>
			    			  <td align="left"><input type="checkbox" class="ck" name="chk_pagto" id="chk_pagto" <?=$pgto_checked;?> <?=$pgto_disabled;?> /></td>
			    			</tr>
			    			<tr>
			    			  <td align="right" valign="top">Data de Pagamento:</td>
			    			  <td align="left"><input type="text" style="width:80px;" name="data_pagto" id="data_pagto" value="<?=$f_dt_pgto_boleto;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10" <?=$pgto_disabled;?>></td>
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
  <a name="proponente"></a>
  <br><b>Dados do Proponente</b>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
      <table cellpadding=0 cellspacing=5 border=0>

        <tr>
          <td align="right" valign="top">Nome:</td>
          <td align="left"  valign="top"><b><?=$prop_nome;?></b></td>
        </tr>

        <tr>
          <td align="right" valign="top">Matrícula:</td>
          <td align="left"  valign="top"><b><?=$prop_matric;?></b></td>
        </tr>

        <tr>
          <td align="right" valign="top">E-Mail:</td>
          <td align="left"  valign="top"><b><?=$prop_email;?></b></td>
        </tr>

        <tr>
          <td align="right" valign="top">CPF:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:150px;" name="prop_cpf" id="prop_cpf" value="<?=$prop_cpf;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraCPF(this,event);" maxlength="14"></td>
        </tr>


        <tr>
          <td align="right" valign="top">Data de Nascimento:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <input type="text" style="width:80px;" name="prop_nasc" id="prop_nasc" value="<?=$prop_nasc;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10">
            <?
              if($prop_nasc!=''){ print $utils->idade($prop_nasc).' anos'; }
            ?>
          </td>
        </tr>
        
        <tr>
          <td align="right" valign="top">Estado Civil:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <select name="prop_civil" id="prop_civil" onchange="selecionaEstadoCivilProponente(this);">
              <option value="0" >-Selecione-</option>
              <?
          		  foreach($forms->getECivil(false, ESTADOCIVILTODOS) as $k=>$v){
          		  	$selected = ($prop_civil==$v['cod_estciv'])?'selected':'';
           		    print '<option value="'.$v['cod_estciv'].'" '.$selected.'>'.$v['desc_estciv'].'</option>';
          		  }
              ?>
            </select>
          </td>
        </tr>

        <tr>
          <td align="right" valign="top">Tipo Logradouro:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <select name="prop_lograd" id="prop_lograd">
              <option value="0" >-Selecione-</option>
              <?
          		  foreach($forms->getLogr() as $k=>$v){
            		    $selected = ($prop_lograd==$v['cod_logr'])?'selected':'';
            		    print '<option value="'.$v['cod_logr'].'" '.$selected.'>'.$v['desc_logr'].'</option>';
          		  }
              ?>
            </select>
          </td>
        </tr>
        
        <tr>
          <td align="right" valign="top">Endereço:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:350px;" name="prop_ender" id="prop_ender" value="<?=$prop_ender;?>" maxlength="100"></td>
        </tr>
        
        <tr>
          <td align="right" valign="top">Número:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:40px;" name="prop_num" id="prop_num" value="<?=$prop_num;?>" maxlength="6"></td>
        </tr>
        
        <tr>
          <td align="right" valign="top">Complemento:</td>
          <td align="left"  valign="top"><input type="text" style="width:150px;" name="prop_compl" id="prop_compl" value="<?=$prop_compl;?>" maxlength="60"></td>
        </tr>
        
        <tr>
          <td align="right" valign="top">Estado:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <select name="prop_uf" id="prop_uf" onChange="getListaMunicipios_v2(this,'prop_cidade');">
              <option value="0" >-Selecione-</option>
              <?
          		  foreach($forms->getUF() as $k=>$v){
          		    $selected = ($prop_uf==$v['cod_uf'])?'selected':'';
          		    print '<option value="'.$v['cod_uf'].'" '.$selected.'>'.$v['nome_uf'].'</option>'."\n";
          		  }
              ?>
            </select>
            &nbsp;Cidade:<?=$obrig;?>
            <select name="prop_cidade" id="prop_cidade">
            	<option value="0" >-Selecione-</option>
              <?
                if($prop_uf!="" && $prop_uf!='0'){
                  foreach($forms->getMunicipios($prop_uf) as $k=>$v){
                    $selected = ($prop_cidade==$v['cod_municipio'])?'selected':'';
                    print '<option value="'.$v['cod_municipio'].'" '.$selected.'>'.$v['nome_municipio'].'</option>'."\n";
                  }
                }
              ?>
            </select>
          </td>
        </tr>

        <tr>
          <td align="right" valign="top">Bairro:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <select name="prop_bairro" id="prop_bairro">
              <option value="0" >-Selecione-</option>
              <?
              	foreach($forms->getBairro() as $k=>$v){
          		    $selected = ($prop_bairro==$v['cod_bairro'])?'selected':'';
          		    print '<option value="'.$v['cod_bairro'].'" '.$selected.'>'.$v['nome_bairro'].'</option>';
              	}
              ?>
            </select>
          </td>
        </tr>
        
        <tr>
          <td align="right" valign="top">CEP:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:150px;" name="prop_cep" id="prop_cep" value="<?=$prop_cep;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraCEP(this,event);" maxlength="9"></td>
        </tr>

        <tr>
          <td align="right" valign="top">Telefone:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:100px;" name="prop_fone" id="prop_fone" value="<?=$prop_fone;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraTEL(this,event);" maxlength="13"></td>
        </tr>

      </table>
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
	</div>
<?
### DADOS DO CONJUGE ##################################################################### 
?>
	<a name="conjugeProponente"></a>
	<br><b>Dados do Cônjuge</b>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
			<table cellpadding=0 cellspacing=5 border=0>
				<tr>
					<td align="right" valign="top">Nome:</td>
					<td align="left"  valign="top"><input type="text" style="width:300px;" name="prop_conjuge" id="prop_conjuge" value="<?=$prop_conjuge;?>" maxlength="70"><?=$obrig;?></td>
				</tr>
				<tr>
					<td align="right" valign="top">Nacionalidade</td>
					<td align="left"  valign="top">
						<select name="prop_conjuge_nacionalidade" id="prop_conjuge_nacionalidade">
							<option value="0" >-Selecione-</option>
							<?
							foreach($forms->getPais() as $k=>$v){
								$selected = ($prop_conjuje_nacionalidade==$v['cod_pais'])?'selected':'';
								print '<option value="'.$v['cod_pais'].'" '.$selected.'>'.$v['nome_pais'].'</option>';
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td align="right" valign="top">RG:<?=$obrig;?></td>
					<td align="left"  valign="top">
						<input type="text" style="width:150px;" name="prop_conujuge_rg" id="prop_conujuge_rg" value="<?=$prop_conujuge_rg;?>" onKeyDown="return teclasRG(this,event);" onKeyUp="return mascaraRG(this,event);" maxlength="13">
						&nbsp;Emissão:<?=$obrig;?>
						<input type="text" style="width:80px;" name="prop_conujuge_dtrg" id="prop_conujuge_dtrg" value="<?=$prop_conujuge_dtrg;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10">
						&nbsp;Órgão Emissor:<?=$obrig;?>
						<input type="text" style="width:80px;" name="prop_conujuge_orgrg" id="prop_conujuge_orgrg" value="<?=$prop_conujuge_orgrg;?>" maxlength="10">
					</td>
				</tr>
			</table>
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
	</div>
  <?
    ### DADOS DO IMÓVEL ##################################################################### 
  ?>
  <a name="imovel"></a>
  <br><b>Dados do Imóvel</b>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
      <table cellpadding=0 cellspacing=5 border=0><tr>
        <td valign="top">
          <table cellpadding=0 cellspacing=5 border=0>
		        
            <tr>
              <td align="right" valign="top">Tipo de Imóvel:<?=$obrig;?></td>
              <td align="left"  valign="top">
                <select name="imov_tipo" id="imov_tipo" onChange="trocouTipoImovel(this);" <?=$imov_aval_ro;?>>
                  <option value="x" >-Selecione-</option>
                  <?
                  foreach ($aTIP_IMOVEL as $k=>$v){
	          		    $selected = ($imov_tipo==$k)?'selected':'';
	          		    print '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
                  }
                  $show_cons_pred = ($imov_tipo!='E')?'display:none':'';
                  $imov_cons_pred = ($imov_tipo!='E')?'':$imov_cons_pred;
                  ?>
                </select>
              </td>
            </tr>
    
            <tr>
              <td align="right" valign="top">Tipo Construção:<?=$obrig;?></td>
              <td align="left"  valign="top">
                <select name="imov_constr" id="imov_constr" <?=$imov_aval_ro;?>>
                  <option value="x" >-Selecione-</option>
                  <?
                  foreach ($aTIP_CONSTR as $k=>$v){
	          		    $selected = ($imov_constr==$k)?'selected':'';
	          		    print '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
                  }
                  ?>
                </select>
              </td>
            </tr>
    
            <tr>
              <td align="right" valign="top">Tipo Condomínio:<?=$obrig;?></td>
              <td align="left"  valign="top">
                <select name="imov_cond" id="imov_cond" <?=$imov_aval_ro;?>>
                  <option value="x" >-Selecione-</option>
                  <?
                  foreach ($aTIP_CONDOM as $k=>$v){
	          		    $selected = ($imov_cond==$k)?'selected':'';
	          		    print '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
                  }
                  ?>
                </select>
              </td>
            </tr>
            
            <tr>
              <td align="right" valign="top">Área do Imóvel (m²):<?=$obrig;?></td>
							<td align="left"  valign="top"><input type="text" style="width:80px;" name="imov_area" id="imov_area" value="<?=$f_imov_area;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraMoeda(this,event,null,-1);" maxlength="9" <?=$imov_aval_ro;?>></td>
            </tr>
		      	<tr>
		          <td align="right" valign="top">Tipo de Imposto:<?=$obrig;?></td>
		          <td align="left"  valign="top">
		            <select name="imov_tp_impst" id="imov_tp_impst" <?=$imov_aval_ro;?>>
		              <option value="x" >-Selecione-</option>
		                <?
		                foreach ($aTIP_IMPOSTO as $k=>$v){
		          		    $selected = ($imov_tp_impst==$k)?'selected':'';
		          		    print '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
		                }
		                ?>
		            </select>
		          </td>
		        </tr>
		        
		      	<tr>
		          <td align="right" valign="top">Estado Conservação Imóvel:<?=$obrig;?></td>
		          <td align="left"  valign="top">
		            <select name="imov_cons_imov" id="imov_cons_imov" <?=$imov_aval_ro;?>>
		              <option value="x" >-Selecione-</option>
		                <?
		                foreach ($aTIP_CONSERV as $k=>$v){
		          		    $selected = ($imov_cons_imov==$k)?'selected':'';
		          		    print '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
		                }
		                ?>
		            </select>
		          </td>
		        </tr>
		        
		      	<tr id="tr_cons_pred" style="<?=$show_cons_pred;?>">
		          <td align="right" valign="top">Estado Conservação Prédio:<?=$obrig;?></td>
		          <td align="left"  valign="top">
		            <select name="imov_cons_pred" id="imov_cons_pred" <?=$imov_aval_ro;?>>
		              <option value="x" >-Selecione-</option>
		                <?
		                foreach ($aTIP_CONSERV as $k=>$v){
		          		    $selected = ($imov_cons_pred==$k)?'selected':'';
		          		    print '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
		                }
		                ?>
		            </select>
		          </td>
		        </tr>
		        
          </table>
        </td><td width="50"></td><td valign="top">
          <table cellpadding=0 cellspacing=5 border=0>
            <tr>
              <td align="right" valign="top">Qtde Salas:<?=$obrig;?></td>
              <td align="left"  valign="top"><input type="text" style="width:50px;" name="imov_sala" id="imov_sala" value="<?=$imov_sala;?>" onKeyDown="return teclasInt(this,event);" maxlength="2" <?=$imov_aval_ro;?>></td>
            </tr>
            <tr>
              <td align="right" valign="top">Qtde Quarto:<?=$obrig;?></td>
              <td align="left"  valign="top"><input type="text" style="width:50px;" name="imov_quarto" id="imov_quarto" value="<?=$imov_quarto;?>" onKeyDown="return teclasInt(this,event);" maxlength="2" <?=$imov_aval_ro;?>></td>
            </tr>
            <tr>
              <td align="right" valign="top">Qtde Banheiro:<?=$obrig;?></td>
              <td align="left"  valign="top"><input type="text" style="width:50px;" name="imov_banh" id="imov_banh" value="<?=$imov_banh;?>" onKeyDown="return teclasInt(this,event);" maxlength="2" <?=$imov_aval_ro;?>></td>
            </tr>
            <tr>
              <td align="right" valign="top">Qtde Garagem:<?=$obrig;?></td>
              <td align="left"  valign="top"><input type="text" style="width:50px;" name="imov_garag" id="imov_garag" value="<?=$imov_garag;?>" onKeyDown="return teclasInt(this,event);" maxlength="2" <?=$imov_aval_ro;?>></td>
            </tr>
            <tr>
              <td align="right" valign="top">Qtde Pavimento:<?=$obrig;?></td>
              <td align="left"  valign="top"><input type="text" style="width:50px;" name="imov_pavim" id="imov_pavim" value="<?=$imov_pavim;?>" onKeyDown="return teclasInt(this,event);" maxlength="2" <?=$imov_aval_ro;?>></td>
            </tr>
            <tr>
              <td align="right" valign="top">Qtde Dep Empreg:<?=$obrig;?></td>
              <td align="left"  valign="top"><input type="text" style="width:50px;" name="imov_empreg" id="imov_empreg" value="<?=$imov_empreg;?>" onKeyDown="return teclasInt(this,event);" maxlength="2" <?=$imov_aval_ro;?>></td>
            </tr>
          </table>
        </td>
      </tr></table>
<? /*
      <hr>
      
      <table cellpadding=0 cellspacing=5 border=0>
      	<tr>
          <td align="right" valign="top">Nome Cartório (RGI):</td>
          <td align="left"  valign="top"><input type="text" style="width:200px;" name="imov_cartr_rgi" id="imov_cartr_rgi" value="<?=$imov_cartr_rgi;?>" maxlength="70" <?=$imov_aval_ro;?>></td>
        </tr>
      	<tr>
          <td align="right" valign="top">Num matrícula do registro imóvel:</td>
          <td align="left"  valign="top"><input type="text" style="width:100px;" name="imov_matrc_rgi" id="imov_matrc_rgi" value="<?=$imov_matrc_rgi;?>" maxlength="10" <?=$imov_aval_ro;?>></td>
        </tr>
        
      	<tr>
          <td align="right" valign="top">Num livro do registro imóvel:</td>
          <td align="left"  valign="top"><input type="text" style="width:100px;" name="imov_livro_rgi" id="imov_livro_rgi" value="<?=$imov_livro_rgi;?>" maxlength="10" <?=$imov_aval_ro;?>></td>
        </tr>
        
      	<tr>
          <td align="right" valign="top">Num folhas do registro imóvel:</td>
          <td align="left"  valign="top"><input type="text" style="width:100px;" name="imov_folhs_rgi" id="imov_folhs_rgi" value="<?=$imov_folhs_rgi;?>" maxlength="10" <?=$imov_aval_ro;?>></td>
        </tr>
        
      	<tr>
          <td align="right" valign="top">Num registro compra e venda:</td>
          <td align="left"  valign="top"><input type="text" style="width:100px;" name="imov_rg_cprvnd" id="imov_rg_cprvnd" value="<?=$imov_rg_cprvnd;?>" maxlength="10" <?=$imov_aval_ro;?>></td>
        </tr>
        
      	<tr>
          <td align="right" valign="top">Num registro da garantia:</td>
          <td align="left"  valign="top"><input type="text" style="width:100px;" name="imov_rg_garant" id="imov_rg_garant" value="<?=$imov_rg_garant;?>" maxlength="10" <?=$imov_aval_ro;?>></td>
        </tr>
      
      </table>
*/ ?>
      <hr>
      
      <table cellpadding=0 cellspacing=5 border=0>
      
        <tr>
          <td align="right" valign="top">Tipo Logradouro:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <select name="imov_lograd" id="imov_lograd" <?=$imov_aval_ro;?>>
              <option value="0" >-Selecione-</option>
              <?
              	foreach($forms->getLogr() as $k=>$v){
          		    $selected = ($imov_lograd==$v['cod_logr'])?'selected':'';
          		    print '<option value="'.$v['cod_logr'].'" '.$selected.'>'.$v['desc_logr'].'</option>';
              	}
              ?>
            </select>
          </td>
        </tr>

        <tr>
          <td align="right" valign="top">Endereço:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:350px;" name="imov_ender" id="imov_ender" value="<?=$imov_ender;?>" maxlength="50" <?=$imov_aval_ro;?>></td>
        </tr>
        
        <tr>
          <td align="right" valign="top">Número:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:40px;" name="imov_num" id="imov_num" value="<?=$imov_num;?>" maxlength="6" <?=$imov_aval_ro;?>></td>
        </tr>
        
        <tr>
          <td align="right" valign="top">Complemento:</td>
          <td align="left"  valign="top"><input type="text" style="width:150px;" name="imov_compl" id="imov_compl" value="<?=$imov_compl;?>" maxlength="30" <?=$imov_aval_ro;?>></td>
        </tr>
        
        <tr>
          <td align="right" valign="top">Estado:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <select name="imov_uf" id="imov_uf" onChange="getListaMunicipios_v2(this,'imov_cidade'); limpaDespachantes('imov_despach');" <?=$imov_aval_ro;?>>
              <option value="0" >-Selecione-</option>
              <?
          		  foreach($forms->getUF() as $k=>$v){
          		    $selected = ($imov_uf==$v['cod_uf'])?'selected':'';
          		    print '<option value="'.$v['cod_uf'].'" '.$selected.'>'.$v['nome_uf'].'</option>'."\n";
          		  }
              ?>
            </select>
            &nbsp;Cidade:<?=$obrig;?>
            
            <select name="imov_cidade" id="imov_cidade" onChange="document.proposta.submit(); getListaDespachantes(this,'imov_uf','imov_despach'); " <?=$imov_aval_ro;?>>
            	<option value="0" >-Selecione-</option>
              <?
                if($imov_uf!="" && $imov_uf!='0'){
                  foreach($forms->getMunicipios($imov_uf) as $k=>$v){
                    $selected = ($imov_cidade==$v['cod_municipio'])?'selected':'';
                    print '<option value="'.$v['cod_municipio'].'" '.$selected.'>'.$v['nome_municipio'].'</option>'."\n";
                  }
                }
              ?>
            </select>
          </td>
        </tr>

        <tr>
          <td align="right" valign="top">Bairro:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <select name="imov_bairro" id="imov_bairro" <?=$imov_aval_ro;?>>
              <option value="0" >-Selecione-</option>
              <?
              	foreach($forms->getBairro() as $k=>$v){
          		    $selected = ($imov_bairro==$v['cod_bairro'])?'selected':'';
          		    print '<option value="'.$v['cod_bairro'].'" '.$selected.'>'.$v['nome_bairro'].'</option>';
              	}
              ?>
            </select>
          </td>
        </tr>
        
        <tr>
          <td align="right" valign="top">CEP:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:150px;" name="imov_cep" id="imov_cep" value="<?=$imov_cep;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraCEP(this,event);" maxlength="9" <?=$imov_aval_ro;?>></td>
        </tr>
        
      </table>
      
      <hr>
      
      <table cellpadding=0 cellspacing=5 border=0>
        
      	<tr>
          <td align="right" valign="top">Tipo de Moradia:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <select name="imov_tp_morad" id="imov_tp_morad" <?=$imov_aval_ro;?>>
              <option value="x" >-Selecione-</option>
                <?
                foreach ($aTIP_MORADIA as $k=>$v){
          		    $selected = ($imov_tp_morad==$k)?'selected':'';
          		    print '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
                }
                ?>
            </select>
          </td>
        </tr>
        
      	<tr>
          <td align="right" valign="top">Imóvel Térreo:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <select name="imov_terreo" id="imov_terreo" <?=$imov_aval_ro;?>>
              <option value="x" >-Selecione-</option>
                <?
                foreach ($aTIP_TERREO as $k=>$v){
          		    $selected = ($imov_terreo==$k)?'selected':'';
          		    print '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
                }
                ?>
            </select>
          </td>
        </tr>

        <tr>
          <td align="right" valign="top">Imóvel tombado, desapropriado ou condenado por órgão público:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <b><?
                foreach ($aTIP_SN as $k=>$v){
                	$checked = ($imov_tb_dp_cnd==$k)?'checked':'';
                	print '<input type="radio" class="rd" name="imov_tb_dp_cnd" id="imov_tb_dp_cnd" value="'.$k.'" '.$checked.' '.$imov_aval_ro.'> '.$v.' &nbsp;&nbsp;';
                }
            ?></b>
          </td>
        </tr>
        
      	<tr>
          <td align="right" valign="top">Imóvel incombustível:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <b><?
                foreach ($aTIP_SN as $k=>$v){
                	$checked = ($imov_incomb==$k)?'checked':'';
   		            print '<input type="radio" class="rd" name="imov_incomb" id="imov_incomb" value="'.$k.'" '.$checked.' '.$imov_aval_ro.'> '.$v.' &nbsp;&nbsp;';
                }
            ?></b>
          </td>
        </tr>
        
      	<tr>
          <td align="right" valign="top">Imóvel localizado em área rural ou favela:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <b><?
                foreach ($aTIP_SN as $k=>$v){
                	$checked = ($imov_rural_fav==$k)?'checked':'';
   		            print '<input type="radio" class="rd" name="imov_rural_fav" id="imov_rural_fav" value="'.$k.'" '.$checked.' '.$imov_aval_ro.'> '.$v.' &nbsp;&nbsp;';
                }
            ?></b>
          </td>
        </tr>
        
      	<tr>
          <td align="right" valign="top">Imóvel em construção:<?=$obrig;?></td>
          <td align="left"  valign="top">
          	<b><?
                foreach ($aTIP_SN as $k=>$v){
                	$checked = ($imov_em_constr==$k)?'checked':'';
   		            print '<input type="radio" class="rd" name="imov_em_constr" id="imov_em_constr" value="'.$k.'" '.$checked.' '.$imov_aval_ro.'> '.$v.' &nbsp;&nbsp;';
                }
            ?></b>
          </td>
        </tr>
        
      </table>

      <hr>
      
      <table cellpadding=0 cellspacing=5 border=0>

      	<tr>
          <td align="right" valign="top">Avaliação do imóvel (R$):<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:80px;" name="imov_vl_aval" id="imov_vl_aval" value="<?=$f_imov_vl_aval;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraMoeda(this,event);" maxlength="19" <?=$imov_aval_ro;?>></td>
        </tr>

        <tr>
          <td align="right" valign="top">Data da Avaliação do Imóvel:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <input type="text" style="width:80px;" name="imov_dt_aval" id="imov_dt_aval" value="<?=$imov_dt_aval;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10" <?=$imov_aval_ro;?>>
          </td>
      	</tr>

      </table>

      <hr>
      <table cellpadding=0 cellspacing=5 border=0>
      	<tr>
          <td align="right" valign="top">Data de Aprovação do Imóvel:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <input type="text" style="width:80px;" name="imov_dt_aprov" id="imov_dt_aprov" value="<?=$imov_dt_aprov;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10" <?=$imov_aprov_ro;?>>
          </td>
          <td align="left"  valign="top">
          	<? if($imov_aprov_ro==''){ ?>
       	    <img src="images/buttons/bt_aprovar_sim.gif" alt="Aprovar Imóvel"     class="im cursorMao" onClick="aprovarYImovel('<?=$crypt->encrypt('imov_aprov');?>');" />
       	    <img src="images/buttons/bt_aprovar_nao.gif" alt="Não Aprovar Imóvel" class="im cursorMao" onClick="aprovarNImovel('<?=$crypt->encrypt('imov_reprov');?>');" />
       	    <? } ?>
          </td>
      	</tr>
      </table>

    </div>
		<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
	</div>

	<?
    ### DADOS DO DESPACHANTE ##################################################################### 
  ?>
  <a name="despachante"></a>
  <br><b>Despachante</b>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
      Despachante:
      <select name="imov_despach" id="imov_despach">
      	<option value="0" >-Selecione-</option>
        <?
          if($imov_uf!="" && $imov_uf!='0' && $imov_cidade!="" && $imov_cidade!='0'){
            foreach($oUsuario->getListaDsespachantes($imov_uf,$imov_cidade) as $k=>$v){
              $selected = ($imov_despach==$v['cod_usua'])?'selected':'';
              print '<option value="'.$v['cod_usua'].'" '.$selected.'>'.$v['nome_usua'].'</option>'."\n";
            }
          }
        ?>
      </select>
    </div>
		<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
	</div>

	<?
    ### DADOS DO VENDEDOR ##################################################################### 
    $show_pf = ($vend_tipo==1)?'':'style="display:none;"';
    $show_pj = ($vend_tipo==2)?'':'style="display:none;"';
  ?>
  <a name="vendedor"></a>
  <br><b>Dados do Vendedor</b>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
			<table cellpadding=0 cellspacing=5 border=0>
				<colgroup>
					<col width="120" />
					<col />
				</colgroup>
        <tr>
          <td align="right" valign="top">Tipo:<?=$obrig;?></td>
          <td align="left"  valign="top"><b>
            <input type="radio" class="rd" name="vend_tipo" id="vend_tipo" value="1" <?=($vend_tipo=='1')?'checked':'';?> onClick="atualizaFormVend(1);" > Pessoa Física &nbsp;&nbsp; 
            <input type="radio" class="rd" name="vend_tipo" id="vend_tipo" value="2" <?=($vend_tipo=='2')?'checked':'';?> onClick="atualizaFormVend(2);" > Pessoa Jurídica<br></b>
          </td>
        </tr>
        <tr>
          <td align="right" valign="top">Nome:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:300px;" name="vend_nome" id="vend_nome" value="<?=$vend_nome;?>" maxlength="100"></td>
        </tr>
        <tr>
          <td align="right" valign="top">Nome Abreviado:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:150px;" name="vend_nick" id="vend_nick" value="<?=$vend_nick;?>" maxlength="40"></td>
        </tr>
      </table>
      
      <hr>
      
      <?
      	// Pessoa Fisica --------------------------------------------------
      ?>
      <div id="div_pf" <?=$show_pf;?>>
      <table cellpadding=0 cellspacing=5 border=0>
        <tr>
          <td align="right" valign="top">CPF:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:150px;" name="vend_cpf" id="vend_cpf" value="<?=$vend_cpf;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraCPF(this,event);" maxlength="14"></td>
        </tr>
        <tr>
          <td align="right" valign="top">Sexo:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <? foreach ($aTIP_SEXO as $k=>$v){
      		    $checked = ($vend_sexo==$k)?'checked':'';
      		    print '<input type="radio" class="rd" name="vend_sexo" id="vend_sexo" value="'.$k.'" '.$checked.'>'.$v.'&nbsp;&nbsp;';
            } ?>
          </td>
        </tr>
        <tr>
          <td align="right" valign="top">Data Nasc:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <input type="text" style="width:80px;" name="vend_nasc" id="vend_nasc" value="<?=$vend_nasc;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10">
            <? if($vend_nasc!=''){ print $utils->idade($vend_nasc).' anos'; } ?>
          </td>
        </tr>
        <tr>
          <td align="right" valign="top">Nacionalidade:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <select name="vend_nacion" id="vend_nacion">
              <option value="0" >-Selecione-</option>
              <?
              	foreach($forms->getPais() as $k=>$v){
          		    $selected = ($vend_nacion==$v['cod_pais'])?'selected':'';
          		    print '<option value="'.$v['cod_pais'].'" '.$selected.'>'.$v['nome_pais'].'</option>';
              	}
              ?>
            </select>
          </td>
        </tr>
        <tr>
          <td align="right" valign="top">Naturalidade:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:150px;" name="vend_natural" id="vend_natural" value="<?=$vend_natural;?>" maxlength="30"></td>
        </tr>
        <tr>
          <td align="right" valign="top">Doc de Identif:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <select name="vend_tpdoc" id="vend_tpdoc">
              <option value="0" >-Selecione-</option>
              <?
              	foreach($forms->getTpDoc() as $k=>$v){
          		    $selected = ($vend_tpdoc==$v['cod_tpdoc'])?'selected':'';
          		    print '<option value="'.$v['cod_tpdoc'].'" '.$selected.'>'.$v['desc_tpdoc'].'</option>';
              	}
              ?>
            </select>
          </td>
        </tr>
        <tr>
          <td align="right" valign="top">RG:<?=$obrig;?></td>
          <td align="left"  valign="top">
          	<input type="text" style="width:150px;" name="vend_rg" id="vend_rg" value="<?=$vend_rg;?>" onKeyDown="return teclasRG(this,event);" onKeyUp="return mascaraRG(this,event);" maxlength="13">
            &nbsp;Emissão:<?=$obrig;?>
            <input type="text" style="width:80px;" name="vend_dtrg" id="vend_dtrg" value="<?=$vend_dtrg;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10">
            &nbsp;Órgão Emissor:<?=$obrig;?>
            <input type="text" style="width:80px;" name="vend_orgrg" id="vend_orgrg" value="<?=$vend_orgrg;?>" maxlength="10">
          </td>
        </tr>
        <tr>
          <td align="right" valign="top">Est Civil:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <select name="vend_civil" id="vend_civil">
              <option value="0" >-Selecione-</option>
              <?
          		  foreach($forms->getECivil() as $k=>$v){
          		  	$selected = ($vend_civil==$v['cod_estciv'])?'selected':'';
           		    print '<option value="'.$v['cod_estciv'].'" '.$selected.'>'.$v['desc_estciv'].'</option>';
          		  }
              ?>
            </select>
          </td>
        </tr>
        <tr>
          <td align="right" valign="top">Nome do cônjuge:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:300px;" name="vend_nconj" id="vend_nconj" value="<?=$vend_nconj;?>" maxlength="70"></td>
        </tr>
        <tr>
          <td align="right" valign="top">Nome do pai:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:300px;" name="vend_npai" id="vend_npai" value="<?=$vend_npai;?>" maxlength="70"></td>
        </tr>
        <tr>
          <td align="right" valign="top">Nome da mãe:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:300px;" name="vend_nmae" id="vend_nmae" value="<?=$vend_nmae;?>" maxlength="70"></td>
        </tr>
        <tr>
          <td align="right" valign="top">Profissão:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <select name="vend_profiss" id="vend_profiss">
              <option value="0" >-Selecione-</option>
              <?
          		  foreach($forms->getProfissao() as $k=>$v){
          		  	$selected = ($vend_profiss==$v['cod_prof'])?'selected':'';
           		    print '<option value="'.$v['cod_prof'].'" '.$selected.'>'.$v['desc_prof'].'</option>';
          		  }
              ?>
            </select>
          </td>
        </tr>
        <tr>
          <td align="right">Renda (R$):<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" name="vend_rendim" id="vend_rendim" style="width:120px;" value="<?=$vend_rendim;?>" maxlength="29"
	        onKeyDown="return teclasFloat(this,event);" onKeyUp="return mascaraMoeda(this,event);" onFocus="this.select();"></td>
        </tr>
        <tr>
          <td align="right" valign="top">Inscrição INSS:</td>
          <td align="left"  valign="top"><input type="text" style="width:150px;" name="vend_inss" id="vend_inss" value="<?=$vend_inss;?>" maxlength="11"></td>
        </tr>
      </table>
      </div>
      
      <?
      	// Pessoa Juridica --------------------------------------------------
      ?>
      <div id="div_pj" <?=$show_pj;?>>
      <table cellpadding=0 cellspacing=5 border=0>
        <tr>
          <td align="right" valign="top">CNPJ:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:150px;" name="vend_cnpj" id="vend_cnpj" value="<?=$vend_cnpj;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraCNPJ(this,event);" maxlength="18"></td>
        </tr>
        <tr>
          <td align="right" valign="top">Isenção PIS-PASEP:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <select name="vend_pispasep" id="vend_pispasep">
              <option value="x" >-Selecione-</option>
                <? foreach ($aTIP_SN as $k=>$v){
          		    $selected = ($vend_pispasep==$k)?'selected':'';
          		    print '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
                } ?>
            </select>
          </td>
        </tr>
        <tr>
          <td align="right" valign="top">Isenção COFINS:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <select name="vend_cofins" id="vend_cofins">
              <option value="x" >-Selecione-</option>
                <? foreach ($aTIP_SN as $k=>$v){
          		    $selected = ($vend_cofins==$k)?'selected':'';
          		    print '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
                } ?>
            </select>
          </td>
        </tr>
        <tr>
          <td align="right" valign="top">Isenção CSLL:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <select name="vend_csll" id="vend_csll">
              <option value="x" >-Selecione-</option>
                <? foreach ($aTIP_SN as $k=>$v){
          		    $selected = ($vend_csll==$k)?'selected':'';
          		    print '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
                } ?>
            </select>
          </td>
        </tr>
        <tr>
          <td align="right" valign="top">Atividade Econômica:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <select name="vend_atveco" id="vend_atveco">
              <option value="0" >-Selecione-</option>
              <?
          		  foreach($forms->getAtvEcon() as $k=>$v){
          		  	$selected = ($vend_atveco==$v['cod_cnae'])?'selected':'';
           		    print '<option value="'.$v['cod_cnae'].'" '.$selected.'>'.$v['desc_cnae'].'</option>';
          		  }
              ?>
            </select>
          </td>
        </tr>
      </table>
      <hr>
			</div>

			<?
				// Pessoa Juridica - Socio ------------------------------------------
				//print $acaoProposta.'<hr color=red>';
				//print $acaoAtualizaUF.'<hr color=red>';
			?>
      <div id="div_pjs" <?=$show_pj;?>>
		  <a name="socioform"></a>
		  <br><b>Sócios</b> &nbsp;
		  <img src="images/buttons/bt_adicionar.gif" id="bt_add_socio" alt="Adicionar Sócio" style="<?=$displayBtAddSocio;?>" class="im" onClick="openFormAddSocio('<?=$crypt->encrypt('socioform');?>');" />
      <div id="div_formSocio" style="<?=$displaySocioForm;?> border:1px solid #DDDDDD; background-color: #F5F5F5; padding: 10px 20px; margin:10px;">
      <table cellpadding=0 cellspacing=5 border=0>
        <tr>
          <td colspan="2"><b>Novo Sócio</b></td>
        </tr>
        <tr>
          <td align="right" valign="top">Nome do Sócio:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:300px;" name="vend_s_nome" id="vend_s_nome" value="<?=$vend_s_nome;?>" maxlength="70"></td>
        </tr>
        <tr>
          <td align="right" valign="top">Nome do Sócio Abrev:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:150px;" name="vend_s_nabrev" id="vend_s_nabrev" value="<?=$vend_s_nabrev;?>" maxlength="15"></td>
        </tr>
        <tr>
          <td align="right" valign="top">Tipo Logradouro:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <select name="vend_s_logr" id="vend_s_logr">
              <option value="0" >-Selecione-</option>
              <?
          		  foreach($forms->getLogr() as $k=>$v){
            		    $selected = ($vend_s_logr==$v['cod_logr'])?'selected':'';
            		    print '<option value="'.$v['cod_logr'].'" '.$selected.'>'.$v['desc_logr'].'</option>';
          		  }
              ?>
            </select>
          </td>
        </tr>
        <tr>
          <td align="right" valign="top">Endereço:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:350px;" name="vend_s_ender" id="vend_s_ender" value="<?=$vend_s_ender;?>" maxlength="40"></td>
        </tr>
        <tr>
          <td align="right" valign="top">Num:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:40px;" name="vend_s_num" id="vend_s_num" value="<?=$vend_s_num;?>" maxlength="6"></td>
        </tr>
        <tr>
          <td align="right" valign="top">Complemento:</td>
          <td align="left"  valign="top"><input type="text" style="width:150px;" name="vend_s_compl" id="vend_s_compl" value="<?=$vend_s_compl;?>" maxlength="15"></td>
        </tr>
        <tr>
          <td align="right" valign="top">Estado:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <select name="vend_s_uf" id="vend_s_uf" onChange="getListaMunicipios_v2(this,'vend_s_cidade');">
              <option value="0" >-Selecione-</option>
              <?
          		  foreach($forms->getUF() as $k=>$v){
          		    $selected = ($vend_s_uf==$v['cod_uf'])?'selected':'';
          		    print '<option value="'.$v['cod_uf'].'" '.$selected.'>'.$v['nome_uf'].'</option>'."\n";
          		  }
              ?>
            </select>
            &nbsp;Cidade:<?=$obrig;?>
            <select name="vend_s_cidade" id="vend_s_cidade">
            	<option value="0" >-Selecione-</option>
              <?
                if($vend_s_uf!="" && $vend_s_uf!='0'){
                  foreach($forms->getMunicipios($vend_s_uf) as $k=>$v){
                    $selected = ($vend_s_cidade==$v['cod_municipio'])?'selected':'';
                    print '<option value="'.$v['cod_municipio'].'" '.$selected.'>'.$v['nome_municipio'].'</option>'."\n";
                  }
                }
              ?>
            </select>
          </td>
        </tr>
        <tr>
          <td align="right" valign="top">Bairro:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <select name="vend_s_bairro" id="vend_s_bairro">
              <option value="0" >-Selecione-</option>
              <?
              	foreach($forms->getBairro() as $k=>$v){
          		    $selected = ($vend_s_bairro==$v['cod_bairro'])?'selected':'';
          		    print '<option value="'.$v['cod_bairro'].'" '.$selected.'>'.$v['nome_bairro'].'</option>';
              	}
              ?>
            </select>
          </td>
        </tr>
        <tr>
          <td align="right" valign="top">CEP:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:150px;" name="vend_s_cep" id="vend_s_cep" value="<?=$vend_s_cep;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraCEP(this,event);" maxlength="9"></td>
        </tr>
        <tr>
          <td align="right" valign="top">Telefone:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:100px;" name="vend_s_fone" id="vend_s_fone" value="<?=$vend_s_fone;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraTEL(this,event);" maxlength="13"></td>
        </tr>
        <tr>
          <td align="right" valign="top">CPF:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:150px;" name="vend_s_cpf" id="vend_s_cpf" value="<?=$vend_s_cpf;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraCPF(this,event);" maxlength="14"></td>
        </tr>
        <tr>
          <td align="right" valign="top">Sexo:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <? foreach ($aTIP_SEXO as $k=>$v){
      		    $checked = ($vend_s_sexo==$k)?'checked':'';
      		    print '<input type="radio" class="rd" name="vend_s_sexo" id="vend_s_sexo" value="'.$k.'" '.$checked.'>'.$v.'&nbsp;&nbsp;';
            } ?>
          </td>
        </tr>
        <tr>
          <td align="right" valign="top">Nacionalidade:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <select name="vend_s_nacion" id="vend_s_nacion">
              <option value="0" >-Selecione-</option>
              <?
              	foreach($forms->getPais() as $k=>$v){
          		    $selected = ($vend_s_nacion==$v['cod_pais'])?'selected':'';
          		    print '<option value="'.$v['cod_pais'].'" '.$selected.'>'.$v['nome_pais'].'</option>';
              	}
              ?>
            </select>
            <input type="hidden" name="f_cod_vjsoc" id="f_cod_vjsoc" value="<?=$vend_codvjsoc;?>">
          </td>
        </tr>
      </table>
      <? if($acaoProposta=='alterform'){ ?>
		  		<img src="images/buttons/bt_salvar.gif" alt="Salvar Sócio" class="im" onClick="runSaveSocio('<?=$crypt->encrypt('savesocio');?>');" />
		  <? }else{ ?>
		  		<img src="images/buttons/bt_adicionar.gif" alt="Adicionar Sócio" class="im" onClick="runAddSocio('<?=$crypt->encrypt('addsocio');?>');" />
			<? } ?>
			<img src="images/buttons/bt_cancelar.gif" alt="Cancelar" class="im" onClick="closeFormAddSocio();" />

      </div>
      <div style="margin-left:20px;">
      <table cellpadding=0 cellspacing=5 border=0>
				<colgroup>
					<col width="120" />
					<col />
				</colgroup>
	      <?
					// Carrega os dados dos socios se os mesmos já foram salvos
					if($cod_insert_ppst!=''){
					  $db->query="select * from vendjursocio where cod_ppst = '".mysql_real_escape_string($cod_insert_ppst)."' ";
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
					        <tr><td colspan="2"><hr></td></tr>
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
					        <tr>
					          <td align="right" valign="top" colspan="2">
					          	<img src="images/buttons/bt_alterar.gif" alt="Alterar" class="im" onClick="editarSocio('<?=$aSocio['COD_VJSOC'];?>','<?=$crypt->encrypt('editsocio');?>');" />
					          	<img src="images/buttons/bt_excluir.gif" alt="Excluir" class="im" onClick="excluirSocio('<?=$aSocio['COD_VJSOC'];?>','<?=$aSocio['NOME_VJSOC'];?>','<?=$crypt->encrypt('delsocio');?>');" />
					          </td>
					        </tr>
								<?
			      	}
					  }
					}
				?>
      </table>
      <input type="hidden" name="qtde_vjsoc" id="qtde_vjsoc" value="<?=intval($db->qrcount);?>">
      </div><br>
      </div>
      
      <hr>
      
      <?
      	// ------------------------------------------------------------------
      ?>
      <table cellpadding=0 cellspacing=5 border=0>
				<colgroup>
					<col width="120" />
					<col />
				</colgroup>
        <tr>
          <td align="right" valign="top">Tipo Logradouro:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <select name="vend_logr" id="vend_logr">
              <option value="0" >-Selecione-</option>
              <?
              	foreach($forms->getLogr() as $k=>$v){
          		    $selected = ($vend_logr==$v['cod_logr'])?'selected':'';
          		    print '<option value="'.$v['cod_logr'].'" '.$selected.'>'.$v['desc_logr'].'</option>';
              	}
              ?>
            </select>
          </td>
        </tr>
        <tr>
          <td align="right" valign="top">Endereço:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:350px;" name="vend_ender" id="vend_ender" value="<?=$vend_ender;?>" maxlength="40"></td>
        </tr>
        <tr>
          <td align="right" valign="top">Num:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:40px;" name="vend_num" id="vend_num" value="<?=$vend_num;?>" maxlength="6"></td>
        </tr>
        <tr>
          <td align="right" valign="top">Complemento:</td>
          <td align="left"  valign="top"><input type="text" style="width:150px;" name="vend_compl" id="vend_compl" value="<?=$vend_compl;?>" maxlength="15"></td>
        </tr>
        
        <tr>
          <td align="right" valign="top">Estado:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <select name="vend_uf" id="vend_uf" onChange="getListaMunicipios_v2(this,'vend_cidade');">
              <option value="0" >-Selecione-</option>
              <?
          		  foreach($forms->getUF() as $k=>$v){
          		    $selected = ($vend_uf==$v['cod_uf'])?'selected':'';
          		    print '<option value="'.$v['cod_uf'].'" '.$selected.'>'.$v['nome_uf'].'</option>'."\n";
          		  }
              ?>
            </select>
            &nbsp;Cidade:<?=$obrig;?>
            <select name="vend_cidade" id="vend_cidade">
            	<option value="0" >-Selecione-</option>
              <?
                if($imov_uf!="" && $imov_uf!='0'){
                  foreach($forms->getMunicipios($vend_uf) as $k=>$v){
                    $selected = ($vend_cidade==$v['cod_municipio'])?'selected':'';
                    print '<option value="'.$v['cod_municipio'].'" '.$selected.'>'.$v['nome_municipio'].'</option>'."\n";
                  }
                }
              ?>
            </select>
          </td>
        </tr>

        <tr>
          <td align="right" valign="top">Bairro:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <select name="vend_bairro" id="vend_bairro">
              <option value="0" >-Selecione-</option>
              <?
              	foreach($forms->getBairro() as $k=>$v){
          		    $selected = ($vend_bairro==$v['cod_bairro'])?'selected':'';
          		    print '<option value="'.$v['cod_bairro'].'" '.$selected.'>'.$v['nome_bairro'].'</option>';
              	}
              ?>
            </select>
          </td>
        </tr>
        <tr>
          <td align="right" valign="top">CEP:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:150px;" name="vend_cep" id="vend_cep" value="<?=$vend_cep;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraCEP(this,event);" maxlength="9"></td>
        </tr>
        <tr>
          <td align="right" valign="top">Telefone:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:100px;" name="vend_fone" id="vend_fone" value="<?=$vend_fone;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraTEL(this,event);" maxlength="13"></td>
        </tr>

        <tr>
          <td align="right" valign="top">Conta Corrente:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <input type="text" style="width:100px;" name="vend_nrcc" id="vend_nrcc" value="<?=$vend_nrcc;?>" onKeyDown="return teclasInt(this,event);" maxlength="12">
            <input type="text" style="width:25px;"  name="vend_dvcc" id="vend_dvcc" value="<?=$vend_dvcc;?>" onKeyDown="return teclasInt(this,event);" maxlength="3">
            &nbsp;Agência:<?=$obrig;?>
            <input type="text" style="width:60px;"  name="vend_nrag" id="vend_nrag" value="<?=$vend_nrag;?>" onKeyDown="return teclasInt(this,event);" maxlength="4" onblur="getNomeAgencia(this,'vend_lbag');"> &nbsp; <span id="vend_lbag" class="bold"></span>
          </td>
        </tr>
      </table>
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
	</div>

	<?
    ### DADOS DO DEVEDOR SOLIDÁRIO ##################################################################### 
  ?>
  <a name="devsol"></a>
  <br><b>Dados do Devedor Solidário</b>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
			<table cellpadding=0 cellspacing=5 border=0>
				<colgroup>
					<col width="120" />
					<col />
				</colgroup>
        <tr>
          <td align="right" valign="top">Nome:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:300px;" name="dsol_nome" id="dsol_nome" value="<?=$dsol_nome;?>" maxlength="70"></td>
        </tr>
        <tr>
          <td align="right" valign="top">Nome Abreviado:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:150px;" name="dsol_nick" id="dsol_nick" value="<?=$dsol_nick;?>" maxlength="15"></td>
        </tr>
        <tr>
          <td align="right" valign="top">Tipo Logradouro:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <select name="dsol_logr" id="dsol_logr">
              <option value="0" >-Selecione-</option>
              <?
          		  foreach($forms->getLogr() as $k=>$v){
            		    $selected = ($dsol_logr==$v['cod_logr'])?'selected':'';
            		    print '<option value="'.$v['cod_logr'].'" '.$selected.'>'.$v['desc_logr'].'</option>';
          		  }
              ?>
            </select>
          </td>
        </tr>
        <tr>
          <td align="right" valign="top">Endereço:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:350px;" name="dsol_ender" id="dsol_ender" value="<?=$dsol_ender;?>" maxlength="40"></td>
        </tr>
        <tr>
          <td align="right" valign="top">Num:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:40px;" name="dsol_num" id="dsol_num" value="<?=$dsol_num;?>" maxlength="6"></td>
        </tr>
        <tr>
          <td align="right" valign="top">Complemento:</td>
          <td align="left"  valign="top"><input type="text" style="width:150px;" name="dsol_compl" id="dsol_compl" value="<?=$dsol_compl;?>" maxlength="15"></td>
        </tr>
        <tr>
          <td align="right" valign="top">Estado:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <select name="dsol_uf" id="dsol_uf" onChange="getListaMunicipios_v2(this,'dsol_cidade');">
              <option value="0" >-Selecione-</option>
              <?
          		  foreach($forms->getUF() as $k=>$v){
          		    $selected = ($dsol_uf==$v['cod_uf'])?'selected':'';
          		    print '<option value="'.$v['cod_uf'].'" '.$selected.'>'.$v['nome_uf'].'</option>'."\n";
          		  }
              ?>
            </select>
            &nbsp;Cidade:<?=$obrig;?>
            <select name="dsol_cidade" id="dsol_cidade">
            	<option value="0" >-Selecione-</option>
              <?
                if($dsol_uf!="" && $dsol_uf!='0'){
                  foreach($forms->getMunicipios($dsol_uf) as $k=>$v){
                    $selected = ($dsol_cidade==$v['cod_municipio'])?'selected':'';
                    print '<option value="'.$v['cod_municipio'].'" '.$selected.'>'.$v['nome_municipio'].'</option>'."\n";
                  }
                }
              ?>
            </select>
          </td>
        </tr>
        <tr>
          <td align="right" valign="top">Bairro:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <select name="dsol_bairro" id="dsol_bairro">
              <option value="0" >-Selecione-</option>
              <?
              	foreach($forms->getBairro() as $k=>$v){
          		    $selected = ($dsol_bairro==$v['cod_bairro'])?'selected':'';
          		    print '<option value="'.$v['cod_bairro'].'" '.$selected.'>'.$v['nome_bairro'].'</option>';
              	}
              ?>
            </select>
          </td>
        </tr>
        <tr>
          <td align="right" valign="top">CEP:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:150px;" name="dsol_cep" id="dsol_cep" value="<?=$dsol_cep;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraCEP(this,event);" maxlength="9"></td>
        </tr>
        <tr>
          <td align="right" valign="top">Telefone:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:100px;" name="dsol_fone" id="dsol_fone" value="<?=$dsol_fone;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraTEL(this,event);" maxlength="13"></td>
        </tr>
        <tr>
          <td align="right" valign="top">CPF:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:150px;" name="dsol_cpf" id="dsol_cpf" value="<?=$dsol_cpf;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraCPF(this,event);" maxlength="14"></td>
        </tr>
        <tr>
          <td align="right" valign="top">Sexo:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <? foreach ($aTIP_SEXO as $k=>$v){
      		    $checked = ($dsol_sexo==$k)?'checked':'';
      		    print '<input type="radio" class="rd" name="dsol_sexo" id="dsol_sexo" value="'.$k.'" '.$checked.'>'.$v.'&nbsp;&nbsp;';
            } ?>
          </td>
        </tr>
        <tr>
          <td align="right" valign="top">Nacionalidade:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <select name="dsol_nacion" id="dsol_nacion">
              <option value="0" >-Selecione-</option>
              <?
              	foreach($forms->getPais() as $k=>$v){
          		    $selected = ($dsol_nacion==$v['cod_pais'])?'selected':'';
          		    print '<option value="'.$v['cod_pais'].'" '.$selected.'>'.$v['nome_pais'].'</option>';
              	}
              ?>
            </select>
          </td>
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
    include("lib/calendar.php");
 
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
   							c.COD_PSST = '".mysql_real_escape_string($cod_insert_ppst)."'
    			where 
    				a.cod_docm=b.cod_docm
    			  and
    				b.flgstatus_mndc = 1
    			  and
    				b.cod_municipio='".mysql_real_escape_string($imov_cidade)."'
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
  <input type="hidden" name="imov_cidade_ck" value="<?=$imov_cidade;?>">
  <input type="hidden" name="imov_uf_ck" value="<?=$imov_uf;?>">
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
								<td class="alc"><input type="checkbox" name="ckl_doc_check[<?=$aDADOSDOCUMENTOS[$i]["cod_docm"];?>]" value="1" id="ckl_doc_check[<?=$aDADOSDOCUMENTOS[$i]["cod_docm"];?>]" <?=($aDADOSDOCUMENTOS[$i]["flgstatus_clst"]?"checked":"")?>></td>
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
									<input type="text" style="width:60px;" name="ckl_doc_dt_ped[<?=$aDADOSDOCUMENTOS[$i]["cod_docm"];?>]" id="ckl_doc_dt_ped[<?=$aDADOSDOCUMENTOS[$i]["cod_docm"];?>]" value="<?=$aDADOSDOCUMENTOS[$i]["dtsolicitacao_clst"];?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10">
									<img src="images/buttons/calendario.gif" alt="Ver Calendário" class="cursorMao im" onclick="return showCalendar('ckl_doc_dt_ped[<?=$aDADOSDOCUMENTOS[$i]["cod_docm"];?>]', 'dd/mm/y');" />
								</td>
								
								<td class="alc">
									<input type="text" style="width:60px;" name="ckl_doc_dt_emis[<?=$aDADOSDOCUMENTOS[$i]["cod_docm"];?>]" id="ckl_doc_dt_emis[<?=$aDADOSDOCUMENTOS[$i]["cod_docm"];?>]" value="<?=$aDADOSDOCUMENTOS[$i]["dtemissao_clst"];?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10">
									<img src="images/buttons/calendario.gif" alt="Ver Calendário" class="cursorMao im" onclick="return showCalendar('ckl_doc_dt_emis[<?=$aDADOSDOCUMENTOS[$i]["cod_docm"];?>]', 'dd/mm/y');" />
								</td>
								<td class="alc"><?=$aDADOSDOCUMENTOS[$i]["validade_docm"];?> dias <?=($aDADOSDOCUMENTOS[$i]["dtvalidade_clst"]!=NULL?"<br> ".$aDADOSDOCUMENTOS[$i]["dtvalidade_clst"]:"");?></td> <? // <br>15/10/2007 ?>
								<td><textarea style="width:150px; height:30px;" name="ckl_doc_desc[<?=$aDADOSDOCUMENTOS[$i]["cod_docm"];?>]" id="ckl_doc_desc[<?=$aDADOSDOCUMENTOS[$i]["cod_docm"];?>]"><?=$aDADOSDOCUMENTOS[$i]["obs_clst"];?></textarea></td>
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
								<td class="alc"><input type="checkbox" name="ckl_doc_check[<?=$aDADOSDOCUMENTOS_NO[$i]["cod_docm"];?>]" value="1" id="ckl_doc_check[<?=$aDADOSDOCUMENTOS_NO[$i]["cod_docm"];?>]" <?=($aDADOSDOCUMENTOS_NO[$i]["flgstatus_clst"]?"checked":"")?>></td>
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
									<input type="text" style="width:60px;" name="ckl_doc_dt_ped[<?=$aDADOSDOCUMENTOS_NO[$i]["cod_docm"];?>]" id="ckl_doc_dt_ped[<?=$aDADOSDOCUMENTOS_NO[$i]["cod_docm"];?>]" value="<?=$aDADOSDOCUMENTOS_NO[$i]["dtsolicitacao_clst"];?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10">
									<img src="images/buttons/calendario.gif" alt="Ver Calendário" class="cursorMao im" onclick="return showCalendar('ckl_doc_dt_ped[<?=$aDADOSDOCUMENTOS_NO[$i]["cod_docm"];?>]', 'dd/mm/y');" />
								</td>
								
								<td class="alc">
									<input type="text" style="width:60px;" name="ckl_doc_dt_emis[<?=$aDADOSDOCUMENTOS_NO[$i]["cod_docm"];?>]" id="ckl_doc_dt_emis[<?=$aDADOSDOCUMENTOS_NO[$i]["cod_docm"];?>]" value="<?=$aDADOSDOCUMENTOS_NO[$i]["dtemissao_clst"];?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10">
									<img src="images/buttons/calendario.gif" alt="Ver Calendário" class="cursorMao im" onclick="return showCalendar('ckl_doc_dt_emis[<?=$aDADOSDOCUMENTOS_NO[$i]["cod_docm"];?>]', 'dd/mm/y');" />
								</td>
								<td class="alc"><?=$aDADOSDOCUMENTOS_NO[$i]["validade_docm"];?> dias <?=($aDADOSDOCUMENTOS_NO[$i]["dtvalidade_clst"]!=NULL?"<br> ".$aDADOSDOCUMENTOS_NO[$i]["dtvalidade_clst"]:"");?></td> <? // <br>15/10/2007 ?>
								<td><textarea style="width:150px; height:30px;" name="ckl_doc_desc[<?=$aDADOSDOCUMENTOS_NO[$i]["cod_docm"];?>]" id="ckl_doc_desc[<?=$aDADOSDOCUMENTOS_NO[$i]["cod_docm"];?>]"><?=$aDADOSDOCUMENTOS_NO[$i]["obs_clst"];?></textarea></td>
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
    ### BOTOES ##################################################################### 
  ?>
	<div style="width:500px; text-align:right; margin-top:10px;">
    <input type="hidden" name="acaoProposta" id="acaoProposta" value="">
    <img src="images/buttons/bt_salvar.gif"   alt="Salvar"   class="im cursorMao" onClick="salvarProposta('<?=$crypt->encrypt('salvar');?>');" />
    <img src="images/buttons/bt_concluir.gif" alt="Concluir" class="im cursorMao" onClick="concluirProposta('<?=$crypt->encrypt('concluir');?>');" />
  </div>

  <?
    ### HISTORICO ##################################################################### 
  ?>
  <a name="historico"></a>
	<br><b>Histórico</b>
  <?
		$db->query="SELECT h.cod_ppst, h.obs_hist, h.tipo_hist, u.level_usua, u.nome_usua, '' as cod_chat, h.dt_hist as data
								FROM historico h, proposta p, usuario u
								WHERE p.proponente_ppst = '".mysql_real_escape_string($cod_insert_ppnt)."' AND p.cod_ppst = h.cod_ppst AND h.cod_usua = u.cod_usua
							UNION
								SELECT '".mysql_real_escape_string($_GET['cod_proposta'])."' as cod_ppst, 'Sessão de chat' as obs_hist, '4' as tipo_hist, '1' as level_usua, '".mysql_real_escape_string($prop_nome)."' as nome_usua, cod_chat,
									(SELECT MIN(dt_chtm) FROM chatmensagens WHERE cod_chat = chat.cod_chat) as data
								FROM chatsessoes as chat
								WHERE cod_usua = '".mysql_real_escape_string($cod_insert_ppnt)."'
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
          <td align="left"  valign="top"><img src="images/buttons/bt_adicionar.gif" alt="Adicionar Evento" class="im cursorMao" onClick="addEvento('<?=$crypt->encrypt('evento');?>');" /></td>
        </tr>
      </table>
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
	</div>
</form>

<?
include "lib/footer.inc.php";
?>
