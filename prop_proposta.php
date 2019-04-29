<?
$iREQ_AUT=1;
$aUSERS_PERM[]=1;
$pageTitle = "Proposta";
include "lib/header.inc.php";


$mensagem = new mensagens();

$acaoProposta = $crypt->decrypt($_POST["acaoProposta"]);

$db->query="select * from proposta 
							where proponente_ppst = '".mysql_real_escape_string($cLOGIN->iID)."' 
								and situacao_ppst > 2";
$db->query();
//print $db->query.'<hr>';
if($db->qrcount>0){
	header('Location:prop_historico.php'); exit();
}


$valorMaxFinan  = 0;
$valorMaxPrest  = 0;
$prazoMaxFinan  = 0;
$taxaJurosAno   = 0;
$tipo_simulador = '';

// Pega a taxa de juros no banco de dados
$db->query="select valor_taxa from taxa";
$db->query();
if($db->qrcount>0){
  $taxaJurosAno = $db->qrdata[0]['valor_taxa'];
}

// Pega os valores limite de financiamento do usuario logado
$db->query="SELECT
							l.vlmaxfinan, l.parcmaxfinan, l.przmaxfinan,
							l.vlaprovado, l.parcaprovada, l.przaprovado, l.vlentraprovado
            FROM usuario u, listadenomes l
            WHERE u.id_lstn=l.id_lstn
            	AND u.cod_usua='".mysql_real_escape_string($cLOGIN->iID)."' ";
$db->query();
if($db->qrcount>0){
  $valorMaxFinan  = $db->qrdata[0]['vlmaxfinan'];
  $valorMaxPrest  = $db->qrdata[0]['parcmaxfinan'];
  $prazoMaxFinan  = $db->qrdata[0]['przmaxfinan'];
  $valorAprov   = $db->qrdata[0]['vlaprovado'];
  $parcelaAprov = $db->qrdata[0]['parcaprovada'];
  $prazoAprov   = $db->qrdata[0]['przaprovado'];
  $entradaAprov = $db->qrdata[0]['vlentraprovado'];
}

// Carrega os dados do proponente se os mesmos já foram salvos
$cod_insert_ppnt = '';
$db->query="SELECT * FROM proponente p
						WHERE p.cod_proponente = '".mysql_real_escape_string($cLOGIN->iID)."' ";
$db->query();
if($db->qrcount>0){
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

$db->query="SELECT * FROM usuario u
						WHERE u.cod_usua = '".mysql_real_escape_string($cLOGIN->iID)."' ";
$db->query();
if($db->qrcount>0){
	$prop_nome       = $db->qrdata[0]['NOME_USUA'];
	$prop_email      = $db->qrdata[0]['EMAIL_USUA'];
	$prop_matric     = $utils->formataMatricula($db->qrdata[0]['ID_LSTN']);
}

// Carrega os dados da proposta do usuario se a mesma existe
$cod_insert_ppst = '';
if($cod_insert_ppnt!=''){
  $db->query="select * from proposta where proponente_ppst = '".mysql_real_escape_string($cod_insert_ppnt)."' and SITUACAO_PPST < 3 ";
  $db->query();
  if($db->qrcount>0){
    $tipo_simulador  = $db->qrdata[0]['PRICESAC_PPST'];
    $valor_total     = $db->qrdata[0]['VLFINSOL_PPST'];
    $valor_entrada   = $db->qrdata[0]['VALORDEVSINALSOL_PPST'];
    $valor_fgts      = $db->qrdata[0]['VALORFGTS_PPST'];
    $prop_prazo      = $db->qrdata[0]['PRZFINSOL_PPST'];
    $prop_prest      = $db->qrdata[0]['VLPRESTSOL_PPST'];
    $flg_aguardando  = $db->qrdata[0]['FLGRESPOSTAVALOR_PPST'];
    $prop_taxa       = $taxaJurosAno;
    $cod_insert_ppst = $db->qrdata[0]['COD_PPST'];
    $valor_compra    = $valor_total + $valor_entrada + $valor_fgts;
    $f_prop_prest    = $prop_prest;
    $f_prop_prazo    = $prop_prazo;
    $prop_prest      = $utils->formataMoeda($f_prop_prest);
    $prop_prest      = ($prop_prazo!='')?'':$prop_prest;
	  $flg_aguardando  = ($flg_aguardando=='')?'S':'N';
  }
}

// Carrega os dados do imovel se os mesmos já foram salvos
$cod_insert_imo = '';
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
    $cod_insert_imo = $db->qrdata[0]['COD_PPST'];
    $imov_cep        = $utils->formataCEP($imov_cep);
  }
}

// Carrega os dados do vendedor se os mesmos já foram salvos
$cod_insert_vend = '';
$cod_insert_vend_j = '';
$cod_insert_vend_s = '';
$cod_insert_vend_f = '';
if($cod_insert_ppst!=''){
  $db->query="SELECT 
  									v.nome_vend, v.tipo_vend, vf.cpf_vfisica, 
  									vj.cnpj_vjur, vs.nome_vjsoc, v.telefone_vend,
  									vj.cod_ppst cvj,
  									vs.cod_ppst cvs,
  									vf.cod_ppst cvf
							FROM vendedor v
							     LEFT JOIN vendjur vj      ON  v.cod_ppst  = vj.cod_ppst
							     LEFT JOIN vendjursocio vs ON  vj.cod_ppst = vs.cod_ppst
							     LEFT JOIN vendfis vf      ON  v.cod_ppst  = vf.cod_ppst
							WHERE v.cod_ppst = '".mysql_real_escape_string($cod_insert_ppst)."' ";
  $db->query();
  //print $db->query.'<hr>';
	if($db->qrcount>0){
	  $vend_nome  = $db->qrdata[0]['nome_vend'];
	  $tipo_vend  = $db->qrdata[0]['tipo_vend'];
	  $vend_cpf   = $db->qrdata[0]['cpf_vfisica'];
	  $vend_cnpj  = $db->qrdata[0]['cnpj_vjur'];
	  $vend_reprs = $db->qrdata[0]['nome_vjsoc'];
	  $vend_fone  = $db->qrdata[0]['telefone_vend'];
	  $vend_cpf   = $utils->formataCPF($vend_cpf);
	  $vend_cnpj  = $utils->formataCnpj($vend_cnpj);
	  $vend_fone  = $utils->formataTelefone($vend_fone);
	  $cod_insert_vend = $cod_insert_ppst;
	  $cod_insert_vend_j = $db->qrdata[0]['cvj'];
	  $cod_insert_vend_s = $db->qrdata[0]['cvs'];
	  $cod_insert_vend_f = $db->qrdata[0]['cvf'];
  }
}

if($_POST){
  ### DADOS DO PROPONENTE #####################################################################
  $prop_cpf    = $_POST['prop_cpf'];
  $prop_nasc   = $_POST['prop_nasc'];
  $prop_civil  = $_POST['prop_civil'];
  $prop_lograd = $_POST['prop_lograd'];
  $prop_ender  = $_POST['prop_ender'];
  $prop_num    = $_POST['prop_num'];
  $prop_compl  = $_POST['prop_compl'];
  $prop_bairro = $_POST['prop_bairro'];
  $prop_uf     = $_POST['prop_uf'];
  $prop_cidade = $_POST['prop_cidade'];
  $prop_cep    = $_POST['prop_cep'];
  $prop_fone   = $_POST['prop_fone'];
  
  ### DADOS DA PROPOSTA #####################################################################
  $tipo_simulador = intval($_POST['tipo_simulador']);
  $prop_prest     = $_POST['prestacao'];
  $prop_prazo     = $_POST['prazo'];
  $valor_compra   = floatval(str_replace(',','.',str_replace('.','',$_POST['valor_compra'])));
  $valor_entrada  = floatval(str_replace(',','.',str_replace('.','',$_POST['valor_entrada'])));
  $valor_fgts     = floatval(str_replace(',','.',str_replace('.','',$_POST['valor_fgts'])));
  $valor_seguro   = floatval(str_replace(',','.',str_replace('.','',$_POST['valor_seguro'])));
  $valor_manut    = floatval(str_replace(',','.',str_replace('.','',$_POST['valor_manut'])));
  $f_prop_prest   = floatval(str_replace(',','.',str_replace('.','',$_POST['prestacao'])));
  $f_prop_prazo   = intval($_POST['prazo']);
  $prop_taxa      = $taxaJurosAno;
  $sel_tipo_finan = intval($_POST['sel_tipo_finan']);
  if($sel_tipo_finan==2){ $f_prop_prest = ''; $prop_prest = ''; }else{ $f_prop_prazo = ''; $prop_prazo = ''; }

  ### DADOS DO IMÓVEL #####################################################################
  $imov_tipo  = $_POST['imov_tipo'];
  $imov_constr = $_POST['imov_constr'];
  $imov_cond = $_POST['imov_cond'];
  $imov_lograd = $_POST['imov_lograd'];
  
  $imov_sala = $_POST['imov_sala'];
  $imov_quarto = $_POST['imov_quarto'];
  $imov_banh = $_POST['imov_banh'];
  $imov_garag = $_POST['imov_garag'];
  $imov_pavim = $_POST['imov_pavim'];
  $imov_empreg = $_POST['imov_empreg'];
  
  $imov_ender = $_POST['imov_ender'];
  $imov_num = $_POST['imov_num'];
  $imov_compl = $_POST['imov_compl'];
  $imov_bairro = $_POST['imov_bairro'];
  $imov_uf = $_POST['imov_uf'];
  $imov_cidade = $_POST['imov_cidade'];
  $imov_cep = $_POST['imov_cep'];
  
  ### DADOS DO VENDEDOR #####################################################################
  $vend_nome  = $_POST['vend_nome'];
  $tipo_vend  = $_POST['tipo_vend'];
  $vend_cpf   = $_POST['vend_cpf'];
  $vend_cnpj  = $_POST['vend_cnpj'];
  $vend_reprs = $_POST['vend_reprs'];
  $vend_fone  = $_POST['vend_fone'];
  
  ### NOVO EVENTO #####################################################################
  $f_novo_evento = $_POST['novo_evento'];
  
}else{
	if($cod_insert_ppst!=''){
		$cLOGIN->insert_log(4,7,'Edição da Proposta - CODPPST:'.$cod_insert_ppst);
	}else{
		$cLOGIN->insert_log(4,7,'Tela de Criação da Proposta');
	}
}
  
### CALCULO DO FINANCIAMENTO ###############################################################
$valor_total   = $valor_compra - ($valor_entrada + $valor_fgts);
/*
$calculo_error = '';
if($tipo_simulador > 0){
  $valor = $valor_total;
  $prop_prazo = ($prop_prazo > 0)?$prop_prazo:1;
  $taxa  = pow( (( $prop_taxa / 100 ) + 1), (1 / 12)) - 1;
  $resultMessage = '';
  switch($tipo_simulador){
    case '1':
      $prestacao = $utils->fPMT($taxa,$prop_prazo,$valor);
      $prestacao1 = $prestacao;
	      $prestacao += $valor_seguro + $valor_manut;
	      $f_prestacao = $utils->formataMoeda($prestacao);
	      $resultMessage .= 'Valor da Prestação Inicial: <b>R$ '.$f_prestacao.'</b>';
      break;
    case '2':
      $amort = $valor / $prop_prazo;
      $juros = ($valor - $amort) * $taxa;
      $prestacao = $juros + $amort;
      $prestacao1 = $prestacao;
      $redMJ = $juros / $prop_prazo;
	      $prestacao  += $valor_seguro + $valor_manut;
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


if($_POST) {
	if($acaoProposta=='salvar' || $acaoProposta=='concluir'){
	  $f_prop_cpf  = preg_replace('/\D/i','',$prop_cpf);
	  $f_prop_cep  = preg_replace('/\D/i','',$prop_cep);
	  $f_imov_cep  = preg_replace('/\D/i','',$imov_cep);
	  $f_prop_fone = preg_replace('/\D/i','',$prop_fone);
	  ## ------------------------------------------------------------------------------------------- ##
	  ## DADOS DO PROPONENTE ##########################################################################
	    if($cod_insert_ppnt==''){
	      $qCMP = $qVAL = '';
	      $qCMP .= " COD_PROPONENTE, ";    $qVAL .= " '".mysql_real_escape_string($cLOGIN->iID)."', ";
	      $qCMP .= " ENDERECO_PPNT, ";     $qVAL .= " '".mysql_real_escape_string($prop_ender)."', ";
	      $qCMP .= " NRENDERECO_PPNT, ";   $qVAL .= " '".mysql_real_escape_string($prop_num)."', ";
	      $qCMP .= " CPENDERECO_PPNT, ";   $qVAL .= " '".mysql_real_escape_string($prop_compl)."', ";
	      $qCMP .= " CEP_PPNT, ";          $qVAL .= " '".mysql_real_escape_string($f_prop_cep)."', ";
	      if($prop_nasc!=''){    $qCMP .= " DTNASCIMENTO_PPNT, "; $qVAL .= " '".mysql_real_escape_string($utils->formataData($prop_nasc))."', "; }
	      if($prop_lograd!='0'){ $qCMP .= " COD_LOGR, ";          $qVAL .= " '".mysql_real_escape_string($prop_lograd)."', "; }
	      if($prop_bairro!='0'){ $qCMP .= " COD_BAIRRO, ";        $qVAL .= " '".mysql_real_escape_string($prop_bairro)."', "; }
	      if($prop_uf!='0')    { $qCMP .= " COD_UF, ";            $qVAL .= " '".mysql_real_escape_string($prop_uf)."', ";     }
	      if($prop_cidade!='0'){ $qCMP .= " COD_MUNICIPIO, ";     $qVAL .= " '".mysql_real_escape_string($prop_cidade)."', "; }
	      if($prop_civil!='0') { $qCMP .= " COD_ESTCIV, ";        $qVAL .= " '".mysql_real_escape_string($prop_civil)."', ";  }
	      $qCMP .= " CPF_PPNT, ";          $qVAL .= " '".mysql_real_escape_string($f_prop_cpf)."', ";
	      $qCMP .= " TELEFONE_PPNT ";      $qVAL .= " '".mysql_real_escape_string($f_prop_fone)."' ";
	  		$db->query="INSERT INTO proponente ($qCMP) VALUES ($qVAL)";
	  		$db->query();
	  		$cod_insert_ppnt = $cLOGIN->iID;
	  	}else{
	      $qSET = '';
	      $qSET .= " ENDERECO_PPNT = '".mysql_real_escape_string($prop_ender)."', ";
	      $qSET .= " NRENDERECO_PPNT = '".mysql_real_escape_string($prop_num)."', ";
	      $qSET .= " CPENDERECO_PPNT = '".mysql_real_escape_string($prop_compl)."', ";
	      $qSET .= " CEP_PPNT = '".mysql_real_escape_string($f_prop_cep)."', ";
	      if($prop_nasc!=''){    $qSET .= " DTNASCIMENTO_PPNT = '".mysql_real_escape_string($utils->formataData($prop_nasc))."', "; }
	      if($prop_lograd!='0'){ $qSET .= " COD_LOGR = '".mysql_real_escape_string($prop_lograd)."', ";      }
	      if($prop_bairro!='0'){ $qSET .= " COD_BAIRRO = '".mysql_real_escape_string($prop_bairro)."', ";    }
	      if($prop_uf!='0')    { $qSET .= " COD_UF = '".mysql_real_escape_string($prop_uf)."', ";            }
	      if($prop_cidade!='0'){ $qSET .= " COD_MUNICIPIO = '".mysql_real_escape_string($prop_cidade)."', "; }
	      if($prop_civil!='0') { $qSET .= " COD_ESTCIV = '".mysql_real_escape_string($prop_civil)."',";      }
	      $qSET .= " CPF_PPNT = '".mysql_real_escape_string($f_prop_cpf)."', ";
	      $qSET .= " TELEFONE_PPNT = '".mysql_real_escape_string($f_prop_fone)."' ";
	  		$db->query="UPDATE proponente SET $qSET WHERE COD_PROPONENTE = '".mysql_real_escape_string($cod_insert_ppnt)."'";
	  		$db->query();
	  	}

	  ## ------------------------------------------------------------------------------------------- ##
	  ## DADOS DA PROPOSTA ############################################################################
		  if($calculo_error==''){
		  	if($acaoProposta=='salvar'){   $situacao_ppst = 1; }
		  	if($acaoProposta=='concluir'){ $situacao_ppst = 3; }
		    if($cod_insert_ppst==''){
		      $qCMP = $qVAL = '';
		      $qCMP .= " SITUACAO_PPST, ";         $qVAL .= " '".mysql_real_escape_string($situacao_ppst)."', ";
		      $qCMP .= " VLFINSOL_PPST, ";         $qVAL .= " '".mysql_real_escape_string($valor_total)."', ";
		      $qCMP .= " VALORDEVSINALSOL_PPST, "; $qVAL .= " '".mysql_real_escape_string($valor_entrada)."', ";
		      $qCMP .= " PRICESAC_PPST, ";         $qVAL .= " '".mysql_real_escape_string($tipo_simulador)."', ";
		      $qCMP .= " VALORCOMPRA_PPST, ";      $qVAL .= " '".mysql_real_escape_string($valor_compra)."', ";
		      $qCMP .= " VALORFGTS_PPST, ";        $qVAL .= " '".mysql_real_escape_string($valor_fgts)."', ";
		      $qCMP .= " TAXAJUROS_PPST, ";        $qVAL .= " '".mysql_real_escape_string($prop_taxa)."', ";
		      $qCMP .= " PRZFINSOL_PPST, ";        if($f_prop_prazo!=''){ $qVAL .= " '".mysql_real_escape_string($f_prop_prazo)."', "; }else{ $qVAL .= " NULL, "; }
		      $qCMP .= " VLPRESTSOL_PPST, ";       if($f_prop_prest!=''){ $qVAL .= " '".mysql_real_escape_string($f_prop_prest)."', "; }else{ $qVAL .= " NULL, "; }
		      $qCMP .= " FLGRESPOSTAVALOR_PPST, "; $qVAL .= " 'S', ";
		      if($situacao_ppst==3){
		      	$qCMP .= " DATA_PPST, ";             $qVAL .= "  now(), ";
		      }
		      $qCMP .= " PROPONENTE_PPST ";        $qVAL .= " '".mysql_real_escape_string($cod_insert_ppnt)."' ";
		  		$db->query="INSERT INTO proposta ($qCMP) VALUES ($qVAL)";
		  		$db->query();
		      $cod_insert_ppst = $db->insertId;
  	  		// grava LOG e HISTORICO
				  if($acaoProposta=='concluir'){
				  	// preenchimento
				 		$cLOGIN->insert_log(1,1,$_SESSION["prop_status"][1]);
				 		$cLOGIN->insert_history($cod_insert_ppst,1,$_SESSION["prop_status"][1]);
				 		// confirmação
				 		$cLOGIN->insert_log(1,2,$_SESSION["prop_status"][2]);
				 		$cLOGIN->insert_history($cod_insert_ppst,1,$_SESSION["prop_status"][2]);
				 		// montagem de pasta
				 		$cLOGIN->insert_log(1,3,$_SESSION["prop_status"][3]);
				 		$cLOGIN->insert_history($cod_insert_ppst,1,$_SESSION["prop_status"][3]);
				  }else{
				  	// preenchimento
				 		$cLOGIN->insert_log(1,1,$_SESSION["prop_status"][1]);
				 		$cLOGIN->insert_history($cod_insert_ppst,1,$_SESSION["prop_status"][1]);
				  }
		    }else{
		      $qSET = '';
		      $qSET .= " SITUACAO_PPST         = '".mysql_real_escape_string($situacao_ppst)."', ";
		      $qSET .= " VLFINSOL_PPST         = '".mysql_real_escape_string($valor_total)."', ";
		      $qSET .= " VALORDEVSINALSOL_PPST = '".mysql_real_escape_string($valor_entrada)."', ";
		      $qSET .= " PRICESAC_PPST         = '".mysql_real_escape_string($tipo_simulador)."', ";
		      $qSET .= " VALORCOMPRA_PPST      = '".mysql_real_escape_string($valor_compra)."', ";
		      $qSET .= " VALORFGTS_PPST        = '".mysql_real_escape_string($valor_fgts)."', ";
		      $qSET .= " TAXAJUROS_PPST        = '".mysql_real_escape_string($prop_taxa)."', ";
		      $qSET .= " PRZFINSOL_PPST        = ".(($prop_prazo=='')?"NULL":"'".mysql_real_escape_string($f_prop_prazo)."'").", ";
		      $qSET .= " VLPRESTSOL_PPST       = ".(($prop_prest=='')?"NULL":"'".mysql_real_escape_string($f_prop_prest)."'").", ";
		      $qSET .= " FLGRESPOSTAVALOR_PPST = 'S', ";
		      if($situacao_ppst==3){
		      	$qSET .= " DATA_PPST           = now() ";
		      }
		  		$db->query="UPDATE proposta SET $qSET WHERE COD_PPST = '".mysql_real_escape_string($cod_insert_ppst)."'";
		  		$db->query();
  	  		// grava LOG e HISTORICO
				  if($acaoProposta=='concluir'){
				 		// confirmação
				 		$cLOGIN->insert_log(1,2,$_SESSION["prop_status"][2]);
				 		$cLOGIN->insert_history($cod_insert_ppst,1,$_SESSION["prop_status"][2]);
				 		// montagem de pasta
				 		$cLOGIN->insert_log(1,3,$_SESSION["prop_status"][3]);
				 		$cLOGIN->insert_history($cod_insert_ppst,1,$_SESSION["prop_status"][3]);
				  }else{
				  	// alteração
				 		$cLOGIN->insert_log(1,1,'Alteração de Proposta');
				 		$cLOGIN->insert_history($cod_insert_ppst,1,'Alteração de Proposta');
				  }
		    }
		  }

	  ## ------------------------------------------------------------------------------------------- ##
	  ## DADOS DO IMOVEL ############################################################################
	    if($cod_insert_imo==''){
	      $qCMP = $qVAL = '';
	      if($imov_tipo!='x'){   $qCMP .= " TIPO_IMOV, ";         $qVAL .= " '".mysql_real_escape_string($imov_tipo)."', ";   }
	      if($imov_constr!='x'){ $qCMP .= " TPCONSTRUCAO_IMOV, "; $qVAL .= " '".mysql_real_escape_string($imov_constr)."', "; }
	      if($imov_cond!='x'){   $qCMP .= " TPCONDOMINIO_IMOV, "; $qVAL .= " '".mysql_real_escape_string($imov_cond)."', ";   }
	      if($imov_lograd!='0'){ $qCMP .= " COD_LOGR, ";          $qVAL .= " '".mysql_real_escape_string($imov_lograd)."', "; }
	      if($imov_sala!=''){    $qCMP .= " QTSALA_IMOV, ";       $qVAL .= " '".mysql_real_escape_string($imov_sala)."', ";   }
	      if($imov_quarto!=''){  $qCMP .= " QTQUARTO_IMOV, ";     $qVAL .= " '".mysql_real_escape_string($imov_quarto)."', "; }
	      if($imov_banh!=''){    $qCMP .= " QTBANH_IMOV, ";       $qVAL .= " '".mysql_real_escape_string($imov_banh)."', ";   }
	      if($imov_garag!=''){   $qCMP .= " QTGARAG_IMOV, ";      $qVAL .= " '".mysql_real_escape_string($imov_garag)."', ";  }
	      if($imov_pavim!=''){   $qCMP .= " QTPAVIM_IMOV, ";      $qVAL .= " '".mysql_real_escape_string($imov_pavim)."', ";  }
	      if($imov_empreg!=''){  $qCMP .= " QTDEPEMP_IMOV, ";     $qVAL .= " '".mysql_real_escape_string($imov_empreg)."', "; }
	      $qCMP .= " ENDERECO_IMOV, ";     $qVAL .= " '".mysql_real_escape_string($imov_ender)."', ";
	      $qCMP .= " NRENDERECO_IMOV, ";   $qVAL .= " '".mysql_real_escape_string($imov_num)."', ";
	      $qCMP .= " CPENDERECO_IMOV, ";   $qVAL .= " '".mysql_real_escape_string($imov_compl)."', ";
	      if($imov_bairro!='0'){ $qCMP .= " COD_BAIRRO, ";    $qVAL .= " '".mysql_real_escape_string($imov_bairro)."', "; }
	      if($imov_uf!='0'){     $qCMP .= " COD_UF, ";        $qVAL .= " '".mysql_real_escape_string($imov_uf)."', ";     }
	      if($imov_cidade!='0'){ $qCMP .= " COD_MUNICIPIO, "; $qVAL .= " '".mysql_real_escape_string($imov_cidade)."', "; }
	      $qCMP .= " CEP_IMOV, ";          $qVAL .= " '".mysql_real_escape_string($f_imov_cep)."', ";
	      $qCMP .= " COD_PPST ";           $qVAL .= " '".mysql_real_escape_string($cod_insert_ppst)."' ";
	  		$db->query="INSERT INTO imovel ($qCMP) VALUES ($qVAL)";
	  		$db->query();
	  		//print $db->query.'<hr>';
	  	}else{
	      $qSET = '';
	      if($imov_tipo!='x'){   $qSET .= " TIPO_IMOV         = '".mysql_real_escape_string($imov_tipo)."', ";   }
	      if($imov_constr!='x'){ $qSET .= " TPCONSTRUCAO_IMOV = '".mysql_real_escape_string($imov_constr)."', "; }
	      if($imov_cond!='x'){   $qSET .= " TPCONDOMINIO_IMOV = '".mysql_real_escape_string($imov_cond)."', ";   }
	      if($imov_lograd!='0'){ $qSET .= " COD_LOGR          = '".mysql_real_escape_string($imov_lograd)."', "; }
	      if($imov_sala!=''){    $qSET .= " QTSALA_IMOV       = '".mysql_real_escape_string($imov_sala)."', ";   }
	      if($imov_quarto!=''){  $qSET .= " QTQUARTO_IMOV     = '".mysql_real_escape_string($imov_quarto)."', "; }
	      if($imov_banh!=''){    $qSET .= " QTBANH_IMOV       = '".mysql_real_escape_string($imov_banh)."', ";   }
	      if($imov_garag!=''){   $qSET .= " QTGARAG_IMOV      = '".mysql_real_escape_string($imov_garag)."', ";  }
	      if($imov_pavim!=''){   $qSET .= " QTPAVIM_IMOV      = '".mysql_real_escape_string($imov_pavim)."', ";  }
	      if($imov_empreg!=''){  $qSET .= " QTDEPEMP_IMOV     = '".mysql_real_escape_string($imov_empreg)."', "; }
	      $qSET .= " ENDERECO_IMOV     = '".mysql_real_escape_string($imov_ender)."', "; 
	      $qSET .= " NRENDERECO_IMOV   = '".mysql_real_escape_string($imov_num)."', ";   
	      $qSET .= " CPENDERECO_IMOV   = '".mysql_real_escape_string($imov_compl)."', "; 
	      if($imov_bairro!='0'){ $qSET .= " COD_BAIRRO        = '".mysql_real_escape_string($imov_bairro)."', "; }
	      if($imov_uf!='0'){     $qSET .= " COD_UF            = '".mysql_real_escape_string($imov_uf)."', ";     }
	      if($imov_cidade!='0'){ $qSET .= " COD_MUNICIPIO     = '".mysql_real_escape_string($imov_cidade)."', "; }
	      $qSET .= " CEP_IMOV          = '".mysql_real_escape_string($f_imov_cep)."' ";    
	  		$db->query="UPDATE imovel SET $qSET WHERE COD_PPST = '".mysql_real_escape_string($cod_insert_ppst)."'";
	  		$db->query();
	  		//print $db->query.'<hr>';
	    }
	    
	  ## ------------------------------------------------------------------------------------------- ##
	  ## DADOS DO VENDEDOR ############################################################################
	  	$f_vend_cpf  = mysql_real_escape_string(preg_replace('/\D/i','',$vend_cpf));
		  $f_vend_cnpj = mysql_real_escape_string(preg_replace('/\D/i','',$vend_cnpj));
		  $f_vend_fone = mysql_real_escape_string(preg_replace('/\D/i','',$vend_fone));
	    if($cod_insert_vend==''){
	      $qCMP = $qVAL = '';
	      $qCMP .= " COD_PPST,";        $qVAL .= " '".mysql_real_escape_string($cod_insert_ppst)."', ";
	      $qCMP .= " tipo_vend, ";      $qVAL .= " '".mysql_real_escape_string($tipo_vend)."', ";
	      $qCMP .= " nome_vend, ";      $qVAL .= " '".mysql_real_escape_string($vend_nome)."', ";
	      $qCMP .= " telefone_vend ";   $qVAL .= " '".mysql_real_escape_string($f_vend_fone)."' ";
	  		$db->query="INSERT INTO vendedor ($qCMP) VALUES ($qVAL)";
	  		//print $db->query.'<hr>';
	  		$db->query();
	    }else{
	    	$qSET = '';
	      $qSET .= " tipo_vend     = '".mysql_real_escape_string($tipo_vend)."', "; 
	      $qSET .= " nome_vend     = '".mysql_real_escape_string($vend_nome)."', "; 
	      $qSET .= " telefone_vend = '".mysql_real_escape_string($f_vend_fone)."' "; 
	  		$db->query="UPDATE vendedor SET $qSET WHERE COD_PPST = '".mysql_real_escape_string($cod_insert_ppst)."'";
	  		$db->query();
	  		//print $db->query.'<hr>';
	    }
	    
	    if($cod_insert_vend_j==''){
	      $qCMP = $qVAL = '';
	      $qCMP .= " COD_PPST,";       $qVAL .= " '".mysql_real_escape_string($cod_insert_ppst)."', ";
	      $qCMP .= " cnpj_vjur ";      $qVAL .= " '".mysql_real_escape_string($f_vend_cnpj)."' ";
	  		$db->query="INSERT INTO vendjur ($qCMP) VALUES ($qVAL)";
	  		//print $db->query.'<hr>';
	  		$db->query();
	    }else{
	    	$qSET = '';
	      $qSET .= " cnpj_vjur     = '".mysql_real_escape_string($f_vend_cnpj)."' "; 
	  		$db->query="UPDATE vendjur SET $qSET WHERE COD_PPST = '".mysql_real_escape_string($cod_insert_ppst)."'";
	  		$db->query();
	  		//print $db->query.'<hr>';
	    }

 	    if($cod_insert_vend_s==''){
	      $qCMP = $qVAL = '';
	      $qCMP .= " COD_PPST,";       $qVAL .= " '".mysql_real_escape_string($cod_insert_ppst)."', ";
	      $qCMP .= " nome_vjsoc ";     $qVAL .= " '".mysql_real_escape_string($vend_reprs)."' ";
	  		$db->query="INSERT INTO vendjursocio ($qCMP) VALUES ($qVAL)";
	  		//print $db->query.'<hr>';
	  		$db->query();
	    }else{
	    	$qSET = '';
	      $qSET .= " nome_vjsoc     = '".mysql_real_escape_string($vend_reprs)."' "; 
	  		$db->query="UPDATE vendjursocio SET $qSET WHERE COD_PPST = '".mysql_real_escape_string($cod_insert_ppst)."'";
	  		$db->query();
	  		//print $db->query.'<hr>';
	    }

 	    if($cod_insert_vend_f==''){
	      $qCMP = $qVAL = '';
	      $qCMP .= " COD_PPST,";       $qVAL .= " '".mysql_real_escape_string($cod_insert_ppst)."', ";
	      $qCMP .= " cpf_vfisica ";     $qVAL .= " '".mysql_real_escape_string($f_vend_cpf)."' ";
	  		$db->query="INSERT INTO vendfis ($qCMP) VALUES ($qVAL)";
	  		//print $db->query.'<hr>';
	  		$db->query();
	    }else{
	    	$qSET = '';
	      $qSET .= " cpf_vfisica     = '".mysql_real_escape_string($f_vend_cpf)."' "; 
	  		$db->query="UPDATE vendfis SET $qSET WHERE COD_PPST = '".mysql_real_escape_string($cod_insert_ppst)."'";
	  		$db->query();
	  		//print $db->query.'<hr>';
	    }

/*
	  ## ------------------------------------------------------------------------------------------- ##
	  	  ## checklist ###################################################################

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
		//echo("<pre>");
		//echo($db->query."<br>");
		//echo("</pre>");
		$db->query();
		//echo($db->errdesc."<br>");
		//echo($db->getErrDesc()."<br>");
	  }
*/
	  
	  if($acaoProposta=='concluir' && $calculo_error==''){ header("Location: prop_historico.php"); exit(); }
	}elseif($acaoProposta=='calcular'){
		$cLOGIN->insert_log(4,7,'Cálculo da Proposta');
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

$obrig = '<span class="obrig"> *</span>';
?>
<script language="JavaScript" src="./js/diversos.js"></script>
<script language="JavaScript" src="./js/proposta.js"></script>
<script language="javascript" type="text/javascript" src="js/ajaxapi.js"></script>

<form name="proposta" id="proposta" method="post" action="<?=$php_self;?>">
	<div class="alr"><? if($cod_insert_ppst!=''){ ?>
		<a href="#historico"><img src="images/buttons/bt_historico.gif" alt="Ver Histórico" class="im" /></a>
	<? } ?></div>
  <?
    ### LIMITES ##################################################################### 
  ?>
  <a name="limites"></a>
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
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
	</div>
	
	<?
    ### PROPOSTA ##################################################################### 
  ?>
  <a name="proposta"></a>
  <br><b>Proposta</b>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
      <?=$mensagem->getMessageBox();?>
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
	          onKeyDown="return teclasFloat(this,event);" onKeyUp="return mascaraMoeda(this,event,'atualizaValFinan()');" onFocus="this.select();"></td>
	        </tr>
	
	        <tr>
	          <td align="right">Valor de Entrada (R$):</td>
	          <td align="left"><input type="text" name="valor_entrada" id="valor_entrada" style="width:80px;" value="<?=$f_valor_entrada;?>" maxlength="20"
	          onKeyDown="return teclasFloat(this,event);" onKeyUp="return mascaraMoeda(this,event,'atualizaValFinan()');" onFocus="this.select();"></td>
	        </tr>
	
	        <tr>
	          <td align="right">FGTS (R$):</td>
	          <td align="left"><input type="text" name="valor_fgts" id="valor_fgts" style="width:80px;" value="<?=$f_valor_fgts;?>" maxlength="20"
	          onKeyDown="return teclasFloat(this,event);" onKeyUp="return mascaraMoeda(this,event,'atualizaValFinan()');" onFocus="this.select();"></td>
	        </tr>

 	        <tr>
	          <td align="right">Valor do Financiamento (R$):</td>
	          <td align="left" id="valor_total" style="font-weight:bold;" height="18"><?=$f_valor_total;?></td>
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
	          	<input type="radio" class="rd" name="sel_tipo_finan" id="sel_tipo_finan" value="1" onclick="selecionaTipoFinan();" <?=$checked;?>>
	          	<span id="spnParcela" style="<?=$display;?>">
	          		<?=$obrig;?>
		          	<input type="text" name="prestacao" id="prestacao" style="width:80px;" value="<?=$prop_prest;?>" maxlength="20"
		          	onKeyDown="return teclasFloat(this,event);" onKeyUp="return mascaraMoeda(this,event,'atualizaValFinan()');" onFocus="this.select();">
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
	          	<input type="radio" class="rd" name="sel_tipo_finan" id="sel_tipo_finan" value="2" onclick="selecionaTipoFinan();" <?=$checked;?>>
	          	<span id="spnPrazo" style="<?=$display;?>">
	          		<?=$obrig;?>
		          	<input type="text" name="prazo" id="prazo" style="width:40px;" value="<?=$prop_prazo;?>" maxlength="3"
		          	onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraInt(this,event,'atualizaValFinan()');" onFocus="this.select();">
		          </span>
	          </td>
	        </tr>
	
	        <tr style="display:none">
	          <td align="right"></td>
	          <td align="left"><input type="image" name="btCalcular" id="btCalcular" src="images/buttons/bt_calcular.gif" value="Calcular" class="im" onClick="return calcularProposta('<?=$crypt->encrypt('calcular');?>');" /></td>
	        </tr>
	      </table>
			</div>
			<? /* if ($resultMessage) { ?>
				<div class="warning" id="divResultadoProposta" style="border:1px solid #DDDDDD; background-color: #F5F5F5; padding: 20px; float:left; width:200px;"><? echo $resultMessage; ?></div>
			<? } */ ?>
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
	</div>
  
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
            <select name="prop_civil" id="prop_civil">
              <option value="0" >-Selecione-</option>
              <?
          		  foreach($forms->getECivil() as $k=>$v){
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
          <td align="left"  valign="top"><input type="text" style="width:350px;" name="prop_ender" id="prop_ender" value="<?=$prop_ender;?>"></td>
        </tr>
        
        <tr>
          <td align="right" valign="top">Número:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:40px;" name="prop_num" id="prop_num" value="<?=$prop_num;?>"></td>
        </tr>
        
        <tr>
          <td align="right" valign="top">Complemento:</td>
          <td align="left"  valign="top"><input type="text" style="width:150px;" name="prop_compl" id="prop_compl" value="<?=$prop_compl;?>"></td>
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
            &nbsp;
            Cidade:<?=$obrig;?>
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
                <select name="imov_tipo" id="imov_tipo">
                  <option value="x" >-Selecione-</option>
                  <?
                  foreach ($aTIP_IMOVEL as $k=>$v){
	          		    $selected = ($imov_tipo==$k)?'selected':'';
	          		    print '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
                  }
                  ?>
                </select>
              </td>
            </tr>
    
            <tr>
              <td align="right" valign="top">Tipo Construção:<?=$obrig;?></td>
              <td align="left"  valign="top">
                <select name="imov_constr" id="imov_constr">
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
                <select name="imov_cond" id="imov_cond">
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
              <td align="right" valign="top">Tipo Logradouro:<?=$obrig;?></td>
              <td align="left"  valign="top">
                <select name="imov_lograd" id="imov_lograd">
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
            
          </table>
        </td><td width="50"></td><td valign="top">
          <table cellpadding=0 cellspacing=5 border=0>
    
            <tr>
              <td align="right" valign="top">Qtde Salas:</td>
              <td align="left"  valign="top"><input type="text" style="width:50px;" name="imov_sala" id="imov_sala" value="<?=$imov_sala;?>" onKeyDown="return teclasInt(this,event);" maxlength="2"></td>
            </tr>
            
            <tr>
              <td align="right" valign="top">Qtde Quarto:</td>
              <td align="left"  valign="top"><input type="text" style="width:50px;" name="imov_quarto" id="imov_quarto" value="<?=$imov_quarto;?>" onKeyDown="return teclasInt(this,event);" maxlength="2"></td>
            </tr>
            
            <tr>
              <td align="right" valign="top">Qtde Banheiro:</td>
              <td align="left"  valign="top"><input type="text" style="width:50px;" name="imov_banh" id="imov_banh" value="<?=$imov_banh;?>" onKeyDown="return teclasInt(this,event);" maxlength="2"></td>
            </tr>
            
            <tr>
              <td align="right" valign="top">Qtde Garagem:</td>
              <td align="left"  valign="top"><input type="text" style="width:50px;" name="imov_garag" id="imov_garag" value="<?=$imov_garag;?>" onKeyDown="return teclasInt(this,event);" maxlength="2"></td>
            </tr>
            
            <tr>
              <td align="right" valign="top">Qtde Pavimento:</td>
              <td align="left"  valign="top"><input type="text" style="width:50px;" name="imov_pavim" id="imov_pavim" value="<?=$imov_pavim;?>" onKeyDown="return teclasInt(this,event);" maxlength="2"></td>
            </tr>
            
            <tr>
              <td align="right" valign="top">Qtde Dep Empreg:</td>
              <td align="left"  valign="top"><input type="text" style="width:50px;" name="imov_empreg" id="imov_empreg" value="<?=$imov_empreg;?>" onKeyDown="return teclasInt(this,event);" maxlength="2"></td>
            </tr>
            
          </table>
        </td>
      </tr></table>
      
      <table cellpadding=0 cellspacing=5 border=0>
        
        <tr>
          <td align="right" valign="top">Endereço:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:350px;" name="imov_ender" id="imov_ender" value="<?=$imov_ender;?>"></td>
        </tr>
        
        <tr>
          <td align="right" valign="top">Número:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:40px;" name="imov_num" id="imov_num" value="<?=$imov_num;?>"></td>
        </tr>
        
        <tr>
          <td align="right" valign="top">Complemento:</td>
          <td align="left"  valign="top"><input type="text" style="width:150px;" name="imov_compl" id="imov_compl" value="<?=$imov_compl;?>"></td>
        </tr>
        
        <tr>
          <td align="right" valign="top">Estado:<?=$obrig;?></td>
          <td align="left"  valign="top">
            <select name="imov_uf" id="imov_uf" onChange="getListaMunicipios_v2(this,'imov_cidade');">
              <option value="0" >-Selecione-</option>
              <?
          		  foreach($forms->getUF() as $k=>$v){
          		    $selected = ($imov_uf==$v['cod_uf'])?'selected':'';
          		    print '<option value="'.$v['cod_uf'].'" '.$selected.'>'.$v['nome_uf'].'</option>'."\n";
          		  }
              ?>
            </select>
            &nbsp;
            Cidade:<?=$obrig;?>
            <select name="imov_cidade" id="imov_cidade" OnChange="document.proposta.submit();">
            	<option value="0">-Selecione-</option>
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
            <select name="imov_bairro" id="imov_bairro">
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
          <td align="left"  valign="top"><input type="text" style="width:150px;" name="imov_cep" id="imov_cep" value="<?=$imov_cep;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraCEP(this,event);" maxlength="9"></td>
        </tr>
        
      </table>
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
	</div>

  
  <?
    ### DADOS DO VENDEDOR ##################################################################### 
    $show_cpf  = ($tipo_vend==1)?'':'style="display:none;"';
    $show_cnpj = ($tipo_vend==2)?'':'style="display:none;"';
  ?>
  <a name="imovel"></a>
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
          <td align="right" valign="top">Nome:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:150px;" name="vend_nome" id="vend_nome" value="<?=$vend_nome;?>" maxlength="100"></td>
        </tr>
        <tr>
          <td align="right" valign="top">Tipo:<?=$obrig;?></td>
          <td align="left"  valign="top"><b>
            <input type="radio" class="rd" name="tipo_vend" id="tipo_vend" value="1" <?=($tipo_vend=='1')?'checked':'';?> onClick="atualizaFormVend(1);" > Pessoa Física &nbsp;&nbsp; 
            <input type="radio" class="rd" name="tipo_vend" id="tipo_vend" value="2" <?=($tipo_vend=='2')?'checked':'';?> onClick="atualizaFormVend(2);" > Pessoa Jurídica<br></b>
          </td>
        </tr>
        <tr id="tr_cpf"  <?=$show_cpf;?>>
          <td align="right" valign="top">CPF:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:150px;" name="vend_cpf" id="vend_cpf" value="<?=$vend_cpf;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraCPF(this,event);" maxlength="14"></td>
        </tr>
        <tr id="tr_cnpj" <?=$show_cnpj;?>>
          <td align="right" valign="top">CNPJ:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:150px;" name="vend_cnpj" id="vend_cnpj" value="<?=$vend_cnpj;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraCNPJ(this,event);" maxlength="18"></td>
        </tr>
        <tr id="tr_repr" <?=$show_cnpj;?>>
          <td align="right" valign="top">Representante Legal:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:150px;" name="vend_reprs" id="vend_reprs" value="<?=$vend_reprs;?>" maxlength="70"></td>
        </tr>
        <tr>
          <td align="right" valign="top">Telefone:<?=$obrig;?></td>
          <td align="left"  valign="top"><input type="text" style="width:100px;" name="vend_fone" id="vend_fone" value="<?=$vend_fone;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraTEL(this,event);" maxlength="13"></td>
        </tr>
      </table>
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
	</div>
		
  <?
/*
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
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
		</div>
*/
	?>
	
  <?
    ### BOTOES ##################################################################### 
  ?>
	<div style="width:500px; text-align:right; margin-top:10px;">
    <input type="hidden" name="acaoProposta" id="acaoProposta" value="">
    <input type="image" name="btSalvar"   id="btSalvar"   src="images/buttons/bt_salvar.gif"   value="Salvar"   class="im" onClick="return salvarProposta('<?=$crypt->encrypt('salvar');?>','<? echo ($situacao ? $situacao : '1'); ?>');" />
    <input type="image" name="btConcluir" id="btConcluir" src="images/buttons/bt_concluir.gif" value="Concluir" class="im" onClick="return concluirProposta('<?=$crypt->encrypt('concluir');?>');" />
  </div>

  <?
    ### HISTORICO ##################################################################### 
  ?>
  <?
		$db->query="SELECT h.cod_ppst, h.obs_hist, h.tipo_hist, u.level_usua, u.nome_usua, '' as cod_chat, h.dt_hist as data
								FROM historico h, proposta p, usuario u
								WHERE p.proponente_ppst = '".mysql_real_escape_string($cLOGIN->iID)."' AND p.cod_ppst = h.cod_ppst AND h.cod_usua = u.cod_usua
							UNION
								SELECT '".mysql_real_escape_string($cod_insert_ppst)."' as cod_ppst, 'Sessão de chat' as obs_hist, '4' as tipo_hist, '1' as level_usua, '".mysql_real_escape_string($cLOGIN->cUSUARIO)."' as nome_usua, cod_chat,
									(SELECT MIN(dt_chtm) FROM chatmensagens WHERE cod_chat = chat.cod_chat) as data
								FROM chatsessoes as chat
								WHERE cod_usua = '".mysql_real_escape_string($cLOGIN->iID)."'
							ORDER BY data DESC";
		$db->query();

		if($db->qrcount>0){
			?>
		  <a name="historico"></a>
			<br><b>Histórico</b>
			<div class="tListDiv listScroll">
				<table>
					<colgroup><col width="150" /><col width="120" /><col /></colgroup>
					<thead><tr><td>Usuário</td><td>Data</td><td>Descrição</td></tr></thead>
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
</form>
<?
include "lib/footer.inc.php";
?>