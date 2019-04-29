<?php

class utils {

	// construtora da classe
	function utils() {
		
	}
	
	// Limpa qualquer caractere que não seja numérico do CNPJ
	function limpaCnpj($cnpj) {
		return eregi_replace("[^0-9]", "", $cnpj);
	}
	
	function limpaNumeros($numero) {
		$numero = eregi_replace("[^0-9\,]", "", $numero);
		return eregi_replace("\,", ".", $numero);
	}
	
	function limpaCep($cep) {
		return str_pad(eregi_replace("[^0-9]", "", $cep), 8, "0", STR_PAD_LEFT);
	}
	
	function limpaTelefone($telefone) {
		return eregi_replace("[^0-9]", "", $telefone);
	}
	
	function formataCnpj($cnpj) {
		$cnpjRet = "";
		if ($cnpj) {
			$cnpjRet = substr($cnpj, -14, 2).".".substr($cnpj, -12, 3).".".substr($cnpj, -9, 3)."/".substr($cnpj, -6, 4)."-".substr($cnpj, -2, 2);
		}
		return $cnpjRet;
	}
	
	function formataTelefone($telefone) {
		$telTmp = "";
		if ($telefone) {
			$telTmp = "(".substr($telefone, -10, 2).")".substr($telefone, -8, 4)."-".substr($telefone, -4, 4);
		}
		return $telTmp;
	}
	
	function formataCep($cep) {
		$cepTmp = "";
		if ($cep) {
			if (strlen($cep) == 5) {
				$cep = str_pad($cep, 8, "0", STR_PAD_RIGHT);
			} elseif (strlen($cep) < 8) {
				$cep = str_pad($cep, 8, "0", STR_PAD_LEFT);
			}
			$cepTmp = substr($cep, 0, 5)."-".substr($cep, 5, 3);
		}
		return $cepTmp;
	}

	function formataCPF($cpf) {
		$cpfTmp = "";
		if ($cpf) {
		  $cpf = preg_replace("/\D/i","",$cpf);
      for($i=0; $i < strlen($cpf); $i++){
        if($i==3){ $cpfTmp .= '.'; }
        if($i==6){ $cpfTmp .= '.'; }
        if($i==9){ $cpfTmp .= '-'; }
        $cpfTmp .= substr($cpf, $i, 1);
      }
		}
		return $cpfTmp;
	}
	
	function limpaCPF($cpf) {
		$subs = array(",", ".", "-");
		$cpf = str_replace($subs,"",$cpf);
		
		return $cpf;
	}
	
	function limpaMatricula($matricula) {
		$subs = array(".", "-");
		$matricula = str_replace($subs,"",$matricula);
		
		return $matricula;
	}
	
	function formataPIS($pis) {
		$cpfTmp = "";
		if ($pis) {
		  $pis = preg_replace("/\D/i","",$pis);
      for($i=0; $i < strlen($pis); $i++){
        if($i==3){ $cpfTmp .= '.'; }
        if($i==8){ $cpfTmp .= '.'; }
        if($i==10){ $cpfTmp .= '-'; }
        $cpfTmp .= substr($pis, $i, 1);
      }
		}
		return $cpfTmp;
	}
	
	function formataData($data) {
		$dataTmp = "";
		if ($data) {
		  $dataArray = split('[-\/]',$data);
			$dataTmp = $dataArray[2].'-'.$dataArray[1].'-'.$dataArray[0];
		}
		return $dataTmp;
	}

  function formataDataBRA($data) {
		$dataTmp = "";
		if($data) {
		  $dataArray = split('[-\/\ ]',$data);
			$dataTmp = $dataArray[2].'/'.$dataArray[1].'/'.$dataArray[0];
		}
		return $dataTmp;
  }
  
  function formataDataHora($datahora){
		$dataTmp = "";
		if($datahora) {
		  $dhar = split('[-\/\ ]',$datahora);
			$dataTmp = $dhar[2].'/'.$dhar[1].'/'.$dhar[0].' - '.$dhar[3];
		}
		return $dataTmp;
		//return $datahora;
  }
  
  function formataFloat($valor,$desc=0) {
    // round
    $valor = str_replace('.',',',strval(round($valor,$desc)));
    $partes = split(',',$valor);
    $inteiro = '';
    $c = -1;
    for($i=strlen($partes[0]); $i >=0; $i--){
      if($c==3){ $inteiro = '.'.$inteiro; $c=0; }
      $inteiro = substr($partes[0],$i,1).$inteiro;
      $c++;
    }
    
    $output = $inteiro;
    
    if($desc > 0){
	    $fracao = @substr($partes[1],0,$desc);
	    for($i=strlen($fracao); $i< $desc; $i++){
	      $fracao.='0';
	    }
	    $output .= ','.$fracao;
    }
    
    return $output;
  }

  function formataMoeda($valor) {
    return $this->formataFloat($valor,2);
  }
  
  function fPMT($taxa,$prazo,$valor){
  	if($taxa==0 || $prazo==0 || $valor==0){ return 0; }
    $t1 = $taxa + 1;
    $x = 1 - (1 / (pow($t1,$prazo)) );
    return ( $valor * $taxa / $x);
  }

  function idade($dtNasc){
    $idade = '';
    if($dtNasc){
      $hoje = date('Ymd');
		  $dataArray = split('[-\/]',$dtNasc);
			$nasc = $dataArray[2].$dataArray[1].$dataArray[0];
      $idade = (int)(($hoje - $nasc) / 10000);
      $idade = ($idade > 0)?$idade:'';
    }
    return $idade;
  }
  
  function formataIdade($dtNasc){
  	if($dtNasc) if($this->idade($dtNasc)!='') return '('.$this->idade($dtNasc).' anos)';
  	else return '';
  }
  
  function formataRG($rg) {
		$rgTmp = "";
		if ($rg) {
		  $rg = preg_replace("/\W/i","",$rg);
		  $tam = strlen($rg) - 1;
		  $tres=-1;
      for($i = $tam; $i >= 0; $i--){
        if($i==($tam-1)){ $rgTmp = '-'.$rgTmp; }
        if(($tres % 3 == 0)&&($tres > 0)){ $rgTmp = '.'.$rgTmp; }
        $rgTmp = substr($rg, $i, 1).$rgTmp;
        $tres++;
      }
		}
		return $rgTmp;
  }
  
	function printArray($array) {
		echo "<pre>";
		print_r($array);
		echo "</pre>";
	}
  
  function formataMatricula($matricula) {
		$rgTmp = "";
		if ($matricula) {
		  $matricula = preg_replace("/\W/i","",$matricula);
		  $tam = strlen($matricula) - 1;
		  $tres=-1;
      for($i = $tam; $i >= 0; $i--){
        if($i==($tam-1)){ $rgTmp = '-'.$rgTmp; }
        if(($tres % 3 == 0)&&($tres > 0)){ $rgTmp = '.'.$rgTmp; }
        $rgTmp = substr($matricula, $i, 1).$rgTmp;
        $tres++;
      }
		}
		return $rgTmp;
  }
  /*
  function formataFloat($valor,$desc){
    //if(trim($valor)!=''){
      //$valor = str_replace(',','X',$valor);
    //}
    //return $valor;
  }
  
  function formataMoeda{$valor){
    //$valor = $this->formataFloat($valor,2);
    //return $valor;
  }
  */
  
  function formataSimNao($sn) {
  	$aSN = array('S'=>'Sim','N'=>'Não');
  	return $aSN[strtoupper($sn)];
  }
  
  function obrig($campo=false){
  	global $obrigatorio, $cLOGIN;
  	if($obrigatorio[$cLOGIN->iLEVEL_USUA]){
	  	if( in_array($campo,$obrigatorio[$cLOGIN->iLEVEL_USUA]) ){
	  		echo '<span class="obrig"> *</span>';
	  	}else{
	  		$obrigatorio['N'][] = $campo;
	  	}
  	}
  }

  
	function moeda2db($valor) {
		$retorno = eregi_replace("\.","",$valor);
		$retorno = eregi_replace(",",".",$retorno);
		return $retorno;
	}
	
	function db2moeda($valor) {
		$valor = str_replace(".",",",$valor);
		return $valor;
	}
	
	function data2db($data) {
		$retorno = $data;
		if (eregi("/",$data)) {
			$dataParts = explode("/",$data);
			$retorno = $dataParts[2].'-'.$dataParts[1].'-'.$dataParts[0];
		}
		return $retorno;
	}

  function camposObrigatoriosPpnt($aPpnt){
  	global $cLOGIN, $aProposta;

  	if($aPpnt["cpf_ppnt"]=='') return 'Informe o CPF';
    if($aPpnt["vlcompra_ppnt"]==0) return 'Informe o Valor de Compra';
    if(($aPpnt["przfinsol_ppnt"]==0)&&
       ($aPpnt["vlprestsol_ppnt"]==0)) return 'Informe o Prazo ou a Prestação';
	
	if($aProposta["situacao_ppst"] >=3)
	{
  
			if($aPpnt["dtnascimento_ppnt"]=='') return 'Informe a Data de Nascimento';
			if($aPpnt["cod_estciv"]=='') return 'Informe o Estado Civil';
			if($cLOGIN->iLEVEL_USUA==TPUSER_ATENDENTE){
				if($aPpnt["cod_estciv"]=='2'){
					if($aPpnt["conjuge"][0]["dtcasamento_ppcj"]=='') return 'Informe a Data de Casamento';
					if($aPpnt["conjuge"][0]["regimebens_ppcj"]=='') return 'Informe o Regime de Bens';
					if($aPpnt["conjuge"][0]["regimebens_ppcj"]=='3' || $aPpnt["conjuge"][0]["regimebens_ppcj"]=='5'){
						if($aPpnt["conjugepacto"][0]["data_pcpa"]=='') return '(Regime de Bens) Informe a Data';
						if($aPpnt["conjugepacto"][0]["locallavracao_pcpa"]=='') return '(Regime de Bens) Informe onde foi Lavrado';
						if($aPpnt["conjugepacto"][0]["livro_pcpa"]=='') return '(Regime de Bens) Informe o Livro';
						if($aPpnt["conjugepacto"][0]["folha_pcpa"]=='') return '(Regime de Bens) Informe a Folha';
						if($aPpnt["conjugepacto"][0]["numeroregistro_pcpa"]=='') return '(Regime de Bens) Informe o Número do Registro';
					}
				}
			}
			if($aPpnt["flgproc_ppnt"]=='') return 'Informe se há procurador';
			if($aPpnt["cod_logr"]=='') return 'Informe o Logradouro';
			if($aPpnt["endereco_ppnt"]=='') return 'Informe o Endereço';
			if($aPpnt["nrendereco_ppnt"]=='') return 'Informe o Número';
			if($aPpnt["cod_uf"]=='') return 'Informe o Estado';
			if($aPpnt["cod_municipio"]=='') return 'Informe o Município';
			if($aPpnt["cod_bairro"]=='') return 'Informe o Bairro';
			if($aPpnt["cep_ppnt"]=='') return 'Informe o CEP';
	}
    if($aPpnt["telefones"][0]['TELEFONE_PPTL']=='') return 'Informe o Telefone';
    if($aProposta["situacao_ppst"]>=3)
	{
  /*  if($cLOGIN->iLEVEL_USUA==TPUSER_ATENDENTE){
	    if($aPpnt["profissao"][0]["empresa_pppf"]=='') return '(Dados Profissionais) Informe a Empresa';
	    if($aPpnt["profissao"][0]["dtadmissao_pppf"]=='') return '(Dados Profissionais) Informe a Data de Admissão';
	    if($aPpnt["profissao"][0]["enderecoemp_pppf"]=='') return '(Dados Profissionais) Informe o Endereço';
	    if($aPpnt["profissao"][0]["numeroemp_pppf"]=='') return '(Dados Profissionais) Informe o Número';
			if($aPpnt["profissao"][0]["estado_pppf"]=='') return '(Dados Profissionais) Informe o Estado';
			if($aPpnt["profissao"][0]["cidade_pppf"]=='') return '(Dados Profissionais) Informe a Cidade';
			if($aPpnt["profissao"][0]["bairro_pppf"]=='') return '(Dados Profissionais) Informe o Bairro';
			if($aPpnt["profissao"][0]["telefone_pppf"]=='') return '(Dados Profissionais) Informe o Telefone';
			if($aPpnt["profissao"][0]["cargo_pppf"]=='') return '(Dados Profissionais) Informe o Cargo';
			if($aPpnt["profissao"][0]["salario_pppf"]=='') return '(Dados Profissionais) Informe o Salário';
    }*/
    
		if($aPpnt["cod_estciv"]=='2'){
			if($aPpnt["conjuge"][0]["nome_ppcj"]=='') return '(Dados do Cônjuge) Informe o Nome';
			if($cLOGIN->iLEVEL_USUA==TPUSER_ATENDENTE){
				if($aPpnt["conjuge"][0]["cod_pais"]=='') return '(Dados do Cônjuge) Informe a Nacionalidade';
				if($aPpnt["conjuge"][0]["nrrg_ppcj"]=='') return '(Dados do Cônjuge) Informe o RG';
				if($aPpnt["conjuge"][0]["dtrg_ppcj"]=='') return '(Dados do Cônjuge) Informe a Emissão do RG';
				if($aPpnt["conjuge"][0]["orgrg_ppcj"]=='') return '(Dados do Cônjuge) Informe o Órgão Emissor do RG';
			}
			if($aPpnt["conjuge"][0]["cpf_pccj"]=='') return '(Dados do Cônjuge) Informe o CPF';
			if($cLOGIN->iLEVEL_USUA==TPUSER_ATENDENTE){
				if($aPpnt["conjuge"][0]["flgtrabalha_ppcj"]=='') return '(Dados do Cônjuge) Informe se trabalha atualmente';
				if($aPpnt["conjuge"][0]["flgtrabalha_ppcj"]=='S'){/*
					if($aPpnt["conjuge"][0]["empresa_ppcj"]=='') return '(Dados Profissionais do Cônjuge) Informe a Empresa';
					if($aPpnt["conjuge"][0]["dtadmissaoemp_ppcj"]=='') return '(Dados Profissionais do Cônjuge) Informe a Data de Admissão';
					if($aPpnt["conjuge"][0]["enderecoemp_ppcj"]=='') return '(Dados Profissionais do Cônjuge) Informe o Endereço';
					if($aPpnt["conjuge"][0]["numeroemp_ppcj"]=='') return '(Dados Profissionais do Cônjuge) Informe o Número';
					if($aPpnt["conjuge"][0]["estadoemp_ppcj"]=='') return '(Dados Profissionais do Cônjuge) Informe o Estado';
					if($aPpnt["conjuge"][0]["cidadeemp_ppcj"]=='') return '(Dados Profissionais do Cônjuge) Informe a Cidade';
					if($aPpnt["conjuge"][0]["bairroemp_ppcj"]=='') return '(Dados Profissionais do Cônjuge) Informe o Bairro';
					if($aPpnt["conjuge"][0]["telefoneemp_ppcj"]=='') return '(Dados Profissionais do Cônjuge) Informe o Telefone';*/
					if($aPpnt["conjuge"][0]["cargoemp_ppcj"]=='') return '(Dados Profissionais do Cônjuge) Informe o Cargo';/*
					if($aPpnt["conjuge"][0]["salarioemp_ppcj"]=='') return '(Dados Profissionais do Cônjuge) Informe o Salário';
			*/	}
			}
		}
			if($aProposta["situacao_ppst"] >= 3 && $aPpnt["cod_estciv"]=='2'){
				if($aPpnt["checklistconjuge"]["travachecklist"]=='S') return 'Check List do Cônjuge do Proponente incompleto';
			}
		}
	if($aProposta["situacao_ppst"]>=3)
	{
	  if($aPpnt["flgdevsol_ppnt"]=='S'){
			if($cLOGIN->iLEVEL_USUA==TPUSER_ATENDENTE){
				if($aPpnt["devsol"][0]["nome_devsol"]=='') return '(Dados do Devedor Solidário) Informe o Nome';
				if($aPpnt["devsol"][0]["nick_devsol"]=='') return '(Dados do Devedor Solidário) Informe o Nome Abreviado';
				if($aPpnt["devsol"][0]["cod_logr"]=='') return '(Dados do Devedor Solidário) Informe o Tipo de Logradouro';
				if($aPpnt["devsol"][0]["endereco_devsol"]=='') return '(Dados do Devedor Solidário) Informe o Endereço';
				if($aPpnt["devsol"][0]["nrendereco_devsol"]=='') return '(Dados do Devedor Solidário) Informe o Número';
				if($aPpnt["devsol"][0]["cod_uf"]=='') return '(Dados do Devedor Solidário) Informe o Estado';
				if($aPpnt["devsol"][0]["cod_municipio"]=='') return '(Dados do Devedor Solidário) Informe o Cidade';
				if($aPpnt["devsol"][0]["cod_bairro"]=='') return '(Dados do Devedor Solidário) Informe o Bairro';
				if($aPpnt["devsol"][0]["cep_devsol"]=='') return '(Dados do Devedor Solidário) Informe o CEP';
				if($aPpnt["devsol"][0]["telefone_devsol"]=='') return '(Dados do Devedor Solidário) Informe o Telefone';
				if($aPpnt["devsol"][0]["cpf_devsol"]=='') return '(Dados do Devedor Solidário) Informe o CPF';
				if($aPpnt["devsol"][0]["sexo_devsol"]=='') return '(Dados do Devedor Solidário) Informe o Sexo';
				if($aPpnt["devsol"][0]["cod_pais"]=='') return '(Dados do Devedor Solidário) Informe o Nacionalidade';
			}
	  }
		}
		if($aProposta["situacao_ppst"] >= 3){
			if($aPpnt["checklist"]["travachecklist"]=='S') return 'Check List do Proponente incompleto';
		}

		return true;
  }

	function camposObrigatoriosVend($aVend){
  	global $cLOGIN, $aProposta;

		if($aVend["tipo_vend"]=='') return 'Informe o Tipo do Vendedor';
		if($aVend["nome_vend"]=='') return 'Informe o Nome do Vendedor';
		if($aVend["nick_vend"]=='') return 'Informe o Nome do Vendedor Abrev';
		
		if($aVend["tipo_vend"]=='1'){ // PF
			if($aVend["vendfis"][0]["cpf_vfisica"]=='') return 'Informe o CPF';
			if($aProposta["situacao_ppst"]>=3){
			if($cLOGIN->iLEVEL_USUA==TPUSER_ATENDENTE ){
				if($aVend["vendfis"][0]["sexo_vfisica"]=='') return 'Informe o Sexo';
				if($aVend["vendfis"][0]["dtnascimento_vfisica"]=='') return 'Informe a Data de Nascimento';
				if($aVend["vendfis"][0]["cod_pais"]=='') return 'Informe a Nacionalidade';
				if($aVend["vendfis"][0]["natur_vfisica"]=='') return 'Informe a Naturalidade';
				if($aVend["vendfis"][0]["cod_tpdoc"]=='') return 'Informe o Doc de Identif';
				if($aVend["vendfis"][0]["nrrg_vfisica"]=='') return 'Informe o RG';
				if($aVend["vendfis"][0]["dtrg_vfisica"]=='') return 'Informe a Emissão do RG';
				if($aVend["vendfis"][0]["orgrg_vfisica"]=='') return 'Informe o Órgão Emissor do RG';
				if($aVend["vendfis"][0]["cod_estciv"]=='') return 'Informe o Estado Civil do Vendedor';
				if($aVend["vendfis"][0]["cod_estciv"]=='2'){
					if($aVend["vendfisconjuge"][0]["dtcasamento_vfcj"]=='') return 'Informe a Data do Casamento';
					if($aVend["vendfisconjuge"][0]["regimebens_vfcj"]=='') return 'Informe o Regime de Bens';
		    	if($aVend["vendfisconjuge"][0]["regimebens_vfcj"]=='3' || $aPpnt["vendfisconjuge"][0]["regimebens_vfcj"]=='5'){
		    		if($aVend["vendfisconjugepacto"][0]["data_vcpa"]=='') return '(Regime de Bens) Informe a Data';
		    		if($aVend["vendfisconjugepacto"][0]["locallavracao_vcpa"]=='') return '(Regime de Bens) Informe onde foi Lavrado';
		    		if($aVend["vendfisconjugepacto"][0]["livro_vcpa"]=='') return '(Regime de Bens) Informe o Livro';
		    		if($aVend["vendfisconjugepacto"][0]["folha_vcpa"]=='') return '(Regime de Bens) Informe a Folha';
		    		if($aVend["vendfisconjugepacto"][0]["numeroregistro_vcpa"]=='') return '(Regime de Bens) Informe o Número do Registro';
		    	}
				}
				if($aVend["vendfis"][0]["nomepai_vfisica"]=='') return 'Informe o Nome do pai';
				if($aVend["vendfis"][0]["nomemae_vfisica"]=='') return 'Informe o Nome da mãe';
				if($aVend["vendfis"][0]["cod_prof"]=='') return 'Informe a Profissão';
				if($aVend["vendfis"][0]["vlrenda_vfisica"]=='') return 'Informe a Renda';
				//if($aVend["vendfis"][0]["nrinss_vfisica"]=='') return 'Informe a Inscrição INSS';
				if($aVend["vendfis"][0]["cod_estciv"]=='2'){
					if($aVend["vendfisconjuge"][0]["nome_vfcj"]=='') return '(Dados do Cônjuge) Informe o Nome';
					if($aVend["vendfisconjuge"][0]["cod_pais"]=='') return '(Dados do Cônjuge) Informe a Nacionalidade';
					if($aVend["vendfisconjuge"][0]["nrrg_vfcj"]=='') return '(Dados do Cônjuge) Informe o RG';
					if($aVend["vendfisconjuge"][0]["dtrg_vfcj"]=='') return '(Dados do Cônjuge) Informe a Emissão do RG';
					if($aVend["vendfisconjuge"][0]["orgrg_vfcj"]=='') return '(Dados do Cônjuge) Informe o Órgão Emissor do RG';
					if($aVend["vendfisconjuge"][0]["cpf_pccj"]=='') return '(Dados do Cônjuge) Informe o CPF';
					if($aVend["vendfisconjuge"][0]["flgtrabalha_vfcj"]=='') return '(Dados do Cônjuge) Informe se Trabalha atualmente';
					if($aVend["vendfisconjuge"][0]["flgtrabalha_vfcj"]=='S'){
						//if($aVend["vendfisconjuge"][0]["empresa_vfcj"]=='') return '(Dados da Empresa do Cônjuge) Informe a Empresa';
						//if($aVend["vendfisconjuge"][0]["dtadmissaoemp_vfcj"]=='') return '(Dados da Empresa do Cônjuge) Informe a Data de Admissão';
					//	if($aVend["vendfisconjuge"][0]["enderecoemp_vfcj"]=='') return '(Dados da Empresa do Cônjuge) Informe o Endereço';
						//if($aVend["vendfisconjuge"][0]["numeroemp_vfcj"]=='') return '(Dados da Empresa do Cônjuge) Informe o Número';
						//if($aVend["vendfisconjuge"][0]["estadoemp_vfcj"]=='') return '(Dados da Empresa do Cônjuge) Informe o Estado';
						//if($aVend["vendfisconjuge"][0]["cidadeemp_vfcj"]=='') return '(Dados da Empresa do Cônjuge) Informe a Cidade';
						//if($aVend["vendfisconjuge"][0]["bairroemp_vfcj"]=='') return '(Dados da Empresa do Cônjuge) Informe o Bairro';
						//if($aVend["vendfisconjuge"][0]["telefoneemp_vfcj"]=='') return '(Dados da Empresa do Cônjuge) Informe o Telefone';
						if($aVend["vendfisconjuge"][0]["cargoemp_vfcj"]=='') return '(Dados da Empresa do Cônjuge) Informe o Cargo';
						//if($aVend["vendfisconjuge"][0]["salarioemp_vfcj"]=='') return '(Dados da Empresa do Cônjuge) Informe o Salário';
					}
				
					if($aProposta["situacao_ppst"] >= 3){
						if($aVend["vendfisconjuge"][0]["checklist"]["travachecklist"]=='S') return 'Check List do Cônjuge do Vendedor incompleto';
					}
				}
			}
		}
			if($aProposta["situacao_ppst"] >= 3){
				if($aVend["vendfis"][0]["checklist"]["travachecklist"]=='S') return 'Check List do Vendedor incompleto';
			}

		}else{ // PJ
			if($aVend["vendjur"][0]["cnpj_vjur"]=='') return 'Informe o CNPJ';
			if($cLOGIN->iLEVEL_USUA==TPUSER_ATENDENTE && $aProposta["situacao_ppst"]>=3){
				if($aVend["vendjur"][0]["isenpis_vjur"]=='') return 'Informe se tem Isenção de PIS-PASEP';
				if($aVend["vendjur"][0]["isencofins_vjur"]=='') return 'Informe se tem Isenção de COFINS';
				if($aVend["vendjur"][0]["isencsll_vjur"]=='') return 'Informe se tem Isenção de CSLL';
				if($aVend["vendjur"][0]["cod_cnae"]=='') return 'Informe a Atividade Econômica';
			}
			if(count($aVend["vendjursocios"])>0 && $aProposta["situacao_ppst"]>=3){
				foreach($aVend["vendjursocios"] as $kSoc=>$vSoc){
					$erros = $this->camposObrigatoriosVendSocio($vSoc);
					if($erros!==true) return 'Sócio '.$vSoc["nome_vjsoc"].': '.$erros;
				}
			}
			if($aProposta["situacao_ppst"] >= 3){
				if($aVend["vendjur"][0]["checklist"]["travachecklist"]=='S') return 'Check List do Vendedor incompleto';
			}
		}
		
		if($cLOGIN->iLEVEL_USUA==TPUSER_ATENDENTE){
		  if($aProposta["situacao_ppst"]>=3){
			if($aVend["cod_logr"]=='') return 'Informe o Tipo de Logradouro';
			if($aVend["endereco_vend"]=='') return 'Informe o Endereço';
			if($aVend["nrendereco_vend"]=='') return 'Informe o Número';
			if($aVend["cod_uf"]=='') return 'Informe o Estado';
			if($aVend["cod_municipio"]=='') return 'Informe a Cidade';
			if($aVend["cod_bairro"]=='') return 'Informe o Bairro';
			if($aVend["cep_vend"]=='') return 'Informe o CEP';
		  }
			if($aVend["telefones"][0]["TELEFONE_VNTL"]=='') return 'Informe o Telefone';
		  if($aProposta["situacao_ppst"]>=3){
			if($aVend["nrcc_vend"]=='') return 'Informe a Conta Corrente';
			if($aVend["dvcc_vend"]=='') return 'Informe o Dígito da Conta Corrente';
			if($aVend["nrag_vend"]=='') return 'Informe a Agência';
		  }
		}

  	return true;
	}

	function camposObrigatoriosVendSocio($aSocio){
  	global $cLOGIN;
  	//print '<hr><pre>'; print_r($aSocio); print '</pre><hr>';
  	if($cLOGIN->iLEVEL_USUA==TPUSER_ATENDENTE && $aProposta["situacao_ppst"]>=3){
	  	if($aSocio["nome_vjsoc"]=='') return 'Informe o Nome';
	  	if($aSocio["nick_vjsoc"]=='') return 'Informe o Nome Abreviado';
	  	if($aSocio["cod_logr"]=='') return 'Informe o Tipo de Logradouro';
	  	if($aSocio["endereco_vjsoc"]=='') return 'Informe o Endereço';
	  	if($aSocio["nrendereco_vjsoc"]=='') return 'Informe o Número';
	  	if($aSocio["cod_uf"]=='') return 'Informe o Estado';
	  	if($aSocio["cod_municipio"]=='') return 'Informe o Cidade';
	  	if($aSocio["cod_bairro"]=='') return 'Informe o Bairro';
	  	if($aSocio["cep_vjsoc"]=='') return 'Informe o CEP';
	  	if($aSocio["telefone_vjsoc"]=='') return 'Informe o Telefone';
	  	if($aSocio["cpf_vjsoc"]=='') return 'Informe o CPF';
	  	if($aSocio["sexo_vjsoc"]=='') return 'Informe o Sexo';
	  	if($aSocio["cod_pais"]=='') return 'Informe o Nacionalidade';
  	}
  	return true;
	}
	
	function procuraErroValidacaoPpnt($aPpnt){
		if($aPpnt["erroValidacao"]){
			return 'Proponente: '.$aPpnt["erroValidacao"];
		}
		if($aPpnt["conjuge"]){
			$erros = $this->procuraErroValidacaoPpcj($aPpnt["conjuge"][0]);
			if($erros!==true) return $erros;
		}
		if($aPpnt["devsol"]){
			$erros = $this->procuraErroValidacaoDsol($aPpnt["devsol"][0]);
			if($erros!==true) return $erros;
		}
		return true;
	}

	function procuraErroValidacaoPpcj($aPpcj){
		if($aPpcj["erroValidacao"]){
			return 'Cônjuge: '.$aPpcj["erroValidacao"];
		}
		return true;
	}

	function procuraErroValidacaoDsol($aDsol){
		if($aDsol["erroValidacao"]){
			return 'Devedor Solidário: '.$aDsol["erroValidacao"];
		}
		return true;
	}
	
}


?>