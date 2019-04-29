// FUNCOES de validação do formulario de PROPOSTA
/*
function validaCheckList(){
	var _t_check = document.getElementsByTagName('input');
	var rex = new RegExp('[\\[\\]]', "g");

	for(i=0;i<_t_check.length; i++){
		if(_t_check[i].id.substr(0,13)=='ckl_doc_check'){
            id_trab = _t_check[i].id.substr(13).replace(rex,'');
            _data_ped = document.getElementById('ckl_doc_dt_ped['+id_trab+']');
            _date_emiss = document.getElementById('ckl_doc_dt_emis['+id_trab+']');
            _check_box = document.getElementById('ckl_doc_check['+id_trab+']');

            if(_check_box.checked == true && (_data_ped.value=='' || _date_emiss.value=='')) {
            	alert('O Documento só pode ser dado como \'OK\', caso a data de pedido e data de emissão estiverem sido preenchidas!');
            	_check_box.focus;
            	return false;
            }

            if(comparaDatas(_data_ped.value,_date_emiss.value)==1){
            	alert('A data de emissão deve ser maior que a data de pedido!');
            	_date_emiss.focus;
            	return false;
            }
		}
	}
	return true;
}

function valorFianciamento(){
  var valor_compra  = document.getElementById('valor_compra').value;
  var valor_entrada = document.getElementById('valor_entrada').value;
  var valor_fgts    = document.getElementById('valor_fgts').value;

  valor_compra  = valor_compra.replace(/\./g,'').replace(/\,/g,'.');
  valor_entrada = valor_entrada.replace(/\./g,'').replace(/\,/g,'.');
  valor_fgts    = valor_fgts.replace(/\./g,'').replace(/\,/g,'.');

  valor_compra  = (valor_compra=='')?0:parseFloat(valor_compra);
  valor_entrada = (valor_entrada=='')?0:parseFloat(valor_entrada);
  valor_fgts    = (valor_fgts=='')?0:parseFloat(valor_fgts);
  
  var valor_total = valor_compra - (valor_entrada + valor_fgts);
  return valor_total;
}

var valor_total_fianciamento = 0;

function atualizaValFinan(){
	valor_total_fianciamento = valorFianciamento();
  document.getElementById('valor_total').innerHTML = formataMoeda(parseInt(valor_total_fianciamento*100));
    
  if(fsRP = document.getElementById('divResultadoProposta')){
    //fsRP.style.display = 'none';
  }
  document.getElementById('alterouValores').value = 'S';
}

function atualizaFormVend(_tipo) {
	document.getElementById('div_pf').style.display = 'none';
	document.getElementById('div_pj').style.display = 'none';
	document.getElementById('div_pjs').style.display = 'none';

	switch(_tipo){
		case 1:
			document.getElementById('div_pf').style.display = 'block';
			break;
		case 2:
			document.getElementById('div_pj').style.display = 'block';
			document.getElementById('div_pjs').style.display = 'block';
			break;
	}
}

function atualizarTela(_ancora,_acao){
  if(validaProposta(false,false,false,false,false,false)){
  	if(_ancora){ document.getElementById('proposta').action += '#'+_ancora; }
  	if(_acao){   document.getElementById('acaoProposta').value = _acao; }
    document.getElementById('proposta').submit();
  }
  return true;
}

function calcularProposta(_acao){
  if(validaProposta(true,false,false,false,false,false)){
  	document.getElementById('proposta').action += '#';
    document.getElementById('acaoProposta').value = _acao;
    document.getElementById('proposta').submit();
    return true;
  }
 	return false;
}

function salvarProposta(_acao){
  if(validaPropostaPreenchidos()){
  	if(validaCheckList()){
	  	document.getElementById('proposta').action += '#';
	    document.getElementById('acaoProposta').value = _acao;
	    document.getElementById('proposta').submit();
	    return true;
  	}
  }
 	return false;
}

function concluirProposta(_acao){
	var mensagem = "Ao concluir a proposta o processo será enviado para Análise Jurídica.\nDeseja realmente concluir a Proposta?";
  if(validaProposta(true,true,true,true,true,false)){
  	if(validaPropostaPreenchidos(true) ){
	  	if( confirm(mensagem) ){
		  	document.getElementById('proposta').action += '#';
		    document.getElementById('acaoProposta').value = _acao;
		    document.getElementById('proposta').submit();
		    return true;
	  	}
  	}
  }
 	return false;
}

function addEvento(_acao){
	if(validaProposta(false,false,false,false,false,true)){
		document.getElementById('proposta').action += "#historico";
    document.getElementById('acaoProposta').value = _acao;
    document.getElementById('proposta').submit();
    return true;
  }
 	return false;
}

function openFormAddSocio(_acao){
	document.getElementById('f_cod_vjsoc').value = '';
	document.getElementById('acaoProposta').value = _acao;
	document.getElementById('div_formSocio').style.display = 'block';
	document.getElementById('bt_add_socio').style.display  = 'none';
	return true;
}

function closeFormAddSocio(){
	document.getElementById('f_cod_vjsoc').value = '';
	document.getElementById('acaoProposta').value = '';
	document.getElementById('div_formSocio').style.display = 'none';
	document.getElementById('bt_add_socio').style.display  = 'inline';
	return true;
}

function runAddSocio(_acao){
	if(validarAddSocio()){
		document.getElementById('proposta').action += "#socioform";
	  document.getElementById('acaoProposta').value = _acao;
	  document.getElementById('proposta').submit();
	  return true;
	}
	return false;
}

function runSaveSocio(_acao){
	if(validarAddSocio()){
		document.getElementById('proposta').action += "#socioform";
	  document.getElementById('acaoProposta').value = _acao;
	  document.getElementById('proposta').submit();
	  return true;
	}
	return false;
}

function excluirSocio(_cod,_nome,_acao){
	if( confirm('Deseja realmente excluir o sócio "'+_nome+'"?') ){
		document.getElementById('proposta').action += "#socioform";
		document.getElementById('f_cod_vjsoc').value = _cod;
    document.getElementById('acaoProposta').value = _acao;
    document.getElementById('proposta').submit();
    return true;
	}
  return flase;
}

function editarSocio(_cod,_acao){
	document.getElementById('proposta').action += "#socioform";
  document.getElementById('f_cod_vjsoc').value = _cod;
  document.getElementById('acaoProposta').value = _acao;
  document.getElementById('proposta').submit();
  return true;
}

// ---------------------------------------------------------------------------------------- //

function validaProposta(_validarPPST,_validarPPNT,_validarIMVL,_validarVEND,_validarDSOL,_validarEVNT){
  if(_validarPPST){
    if(!vCheck('tipo_simulador')) return false;
    if(!vTexto('valor_compra')) return false;
    //if(!vTexto('valor_entrada')) return false;
    //if(!vTexto('valor_fgts')) return false;
    //if(!vTexto('valor_total')) return false;
		valor_total_fianciamento = valorFianciamento();
		if(excedeFinan()) return false;
		if(excedePrazo()) return false;
	  if(excedeSinal()) return false;
		var tipov = valorRadio('sel_tipo_finan');
		switch(tipov){
			case '1':
				if(document.getElementById('prestacao').value == '0,00'){
					document.getElementById('prestacao').value = '';
				}
				if(!vTexto('prestacao','o Valor da Prestacao do Financiamento')) return false;
				break;
			case '2':
				if(!vPositivo('prazo','o Prazo do Financiamento')) return false;
				break;
			default:
				alert('Informe o Valor da Prestacao ou o Prazo do Financiamento!');
				foco('sel_tipo_finan');
				return false;
		}
    //if(!vTexto('taxa')) return false;
    //if(!vTexto('valor_seguro')) return false;
    //if(!vTexto('valor_manut')) return false;
    
    if(flgPrevi!='N'){
    	alert("Aguardando aprovação dos valores do Financiamento.\nNão é possivel concluir a Proposta!");
    	foco('proposta');
    	return false;
    }else{
			var campoFinanc  = '';
			var campoCompra  = '';
			var campoEntrada = '';
			var campoFGTS    = '';
			var campoParcela = '';
			var campoPrazo   = '';
			// --------------------------------------------------------------------- //
		  campoCompra  = document.getElementById('valor_compra').value;
		  campoEntrada = document.getElementById('valor_entrada').value;
		  campoFGTS    = document.getElementById('valor_fgts').value;
		
		  campoCompra  = campoCompra.replace(/\./g,'').replace(/\,/g,'.');
		  campoEntrada = campoEntrada.replace(/\./g,'').replace(/\,/g,'.');
		  campoFGTS    = campoFGTS.replace(/\./g,'').replace(/\,/g,'.');
		
		  campoCompra  = (campoCompra=='')?0:parseFloat(campoCompra);
		  campoEntrada = (campoEntrada=='')?0:parseFloat(campoEntrada);
		  campoFGTS    = (campoFGTS=='')?0:parseFloat(campoFGTS);

		  campoFinanc = campoCompra - (campoEntrada + campoFGTS);
		  // --------------------------------------------------------------------- //
			if(tipov == 1){
				campoParcela = document.getElementById('prestacao').value;
				campoParcela = campoParcela.replace(/\./g,'').replace(/\,/g,'.');
				campoParcela = (campoParcela=='')?0:parseFloat(campoParcela);
				campoPrazo   = 0;
				document.getElementById('prazo').value = '';
			}else{
				campoPrazo   = document.getElementById('prazo').value;
				campoPrazo = campoPrazo.replace(/\./g,'').replace(/\,/g,'.');
				campoPrazo = (campoPrazo=='')?0:parseFloat(campoPrazo);
				campoParcela = 0;
				document.getElementById('prestacao').value = '';
			}
		  // --------------------------------------------------------------------- //
  
		  if(
		  	   campoCompra != solicCompra
		  	|| campoEntrada != solicEntrada
		  	|| campoFGTS != solicFGTS
		  	|| (tipov==1 && campoParcela != solicParcela)
		  	|| (tipov==2 && campoPrazo != solicPrazo)
		  ){
		  	alert("Os campos de valores da Proposta foram alterados.\nÉ necessário esperar a aprovação dos mesmos pela Previ para concluir a Proposta.");
		  	return false;
		  }else{
			  if(
			  		 campoFinanc != aprovFinanc
			  	|| (tipov==1 && campoParcela != solicParcela)
			  	|| (tipov==2 && campoPrazo != solicPrazo)
			  ){
			  	alert("Os valores aprovados são diferentes da Proposta.\nCorrija os mesmos e aguarde a aprovação pela Previ.");
			  	return false;
			  }
		  }
    }

		// confirmação de pagamento do boleto // ------------------------------- //
		if(!document.getElementById('chk_pagto').checked){
			alert('É necessário a Confirmação do Pagamento!');
			foco('chk_pagto');
			return false;
		}
		if(!vData('data_pagto')) return false;
		
  }
  if(_validarPPNT){
    if(!vCPF('prop_cpf')) return false;
    if(!vData('prop_nasc')) return false;
    if(!vSelect('prop_civil','0')) return false;
    if(!vSelect('prop_lograd','0')) return false;
    if(!vTexto('prop_ender')) return false;
    if(!vTexto('prop_num')) return false;
    //if(!vTexto('prop_compl')) return false;
    if(!vSelect('prop_uf','0')) return false;
    if(!vSelect('prop_cidade','0')) return false;
    if(!vSelect('prop_bairro','0')) return false;
    if(!vCEP('prop_cep')) return false;
  }
  if(_validarIMVL){
    if(!vSelect('imov_lograd','0')) return false;
    if(!vTexto('imov_ender')) return false;
    if(!vTexto('imov_num')) return false;
    if(!vSelect('imov_uf','0')) return false;
    if(!vSelect('imov_cidade','0')) return false;
    if(!vCEP('imov_cep')) return false;
    if(flgAprov==''){
			alert('Informe antes se o Imóvel foi Aprovado ou Reprovado!');
			foco('imov_dt_aprov');
			return false;
    }
  }
  if(_validarVEND){
  	if(!vCheck('vend_tipo')) return false;
  	if(!vTexto('vend_nome')) return false;
  	if(!vTexto('vend_nick')) return false;
  	
  	var tipov = valorRadio('vend_tipo');
  	switch(tipov){
  		case '1':
  			if(!vCPF('vend_cpf')) return false;
  			if(!vCheck('vend_sexo')) return false;
  			if(!vData('vend_nasc')) return false;
  			if(!vSelect('vend_nacion','0')) return false;
  			if(!vTexto('vend_natural')) return false;
  			if(!vSelect('vend_tpdoc','0')) return false;
  			if(!vTexto('vend_rg')) return false;
  			if(!vTexto('vend_dtrg')) return false;
  			if(!vTexto('vend_orgrg')) return false;
  			if(!vSelect('vend_civil','0')) return false;
  			if(!vTexto('vend_nconj')) return false;
  			if(!vTexto('vend_npai')) return false;
  			if(!vTexto('vend_nmae')) return false;
  			if(!vSelect('vend_profiss','0')) return false;
  			if(!vMoeda('vend_rendim')) return false;
  			break;
  		case '2':
  			if(!vCNPJ('vend_cnpj')) return false;
  			if(!vSelect('vend_pispasep','x')) return false;
  			if(!vSelect('vend_cofins','x')) return false;
  			if(!vSelect('vend_csll','x')) return false;
  			if(!vSelect('vend_atveco','0')) return false;
  			if(!vQtde('qtde_vjsoc','Adicione pelo menos um Sócio')) return false;
  			break;
  	}
  	
  	if(!vSelect('vend_logr','0')) return false;
  	if(!vTexto('vend_ender')) return false;
  	if(!vTexto('vend_num')) return false;
  	//if(!vTexto('vend_compl')) return false;
  	if(!vSelect('vend_uf','0')) return false;
  	if(!vSelect('vend_cidade','0')) return false;
  	if(!vSelect('vend_bairro','0')) return false;
  	if(!vTexto('vend_cep')) return false;
  	if(!vTelefone('vend_fone')) return false;
  	if(!vTexto('vend_nrcc')) return false;
  	if(!vTexto('vend_dvcc')) return false;
  	if(!vTexto('vend_nrag')) return false;
  }
  if(_validarDSOL){
  	if(!vTexto('dsol_nome')) return false;
  	if(!vTexto('dsol_nick')) return false;
  	if(!vSelect('dsol_logr','0')) return false;
  	if(!vTexto('dsol_ender')) return false;
  	if(!vTexto('dsol_num')) return false;
  	//if(!vTexto('dsol_compl')) return false;
  	if(!vSelect('dsol_uf','0')) return false;
  	if(!vSelect('dsol_cidade','0')) return false;
  	if(!vSelect('dsol_bairro','0')) return false;
  	if(!vTexto('dsol_cep')) return false;
  	if(!vTexto('dsol_fone')) return false;
  	if(!vCPF('dsol_cpf')) return false;
  	if(!vCheck('dsol_sexo')) return false;
  	if(!vSelect('dsol_nacion','0')) return false;
  }
  if(_validarEVNT){
  	if(!vTexto('novo_evento')) return false;
  }
  return true;
}

function validarAddSocio(){
	if(!vTexto('vend_s_nome')) return false;
	if(!vTexto('vend_s_nabrev')) return false;
	if(!vSelect('vend_s_logr','0')) return false;
	if(!vTexto('vend_s_ender')) return false;
	if(!vTexto('vend_s_num')) return false;
	//if(!vTexto('vend_s_compl')) return false;
	if(!vSelect('vend_s_uf','0')) return false;
	if(!vSelect('vend_s_cidade','0')) return false;
	if(!vSelect('vend_s_bairro','0')) return false;
	if(!vTexto('vend_s_cep')) return false;
	if(!vTelefone('vend_s_fone')) return false;
	if(!vCPF('vend_s_cpf')) return false;
	if(!vSelect('vend_s_nacion','0')) return false;
	if(!vCheck('vend_s_sexo')) return false;
  return true;
}

function validaPropostaPreenchidos(_aprovacao){
	valor_total_fianciamento = valorFianciamento();
	if(!_aprovacao) _aprovacao = false;
	if(excedeFinan()) return false;
	if(excedePrazo()) return false;
  if(excedeSinal()) return false;
	var tipov = valorRadio('sel_tipo_finan');
	switch(tipov){
		case '1':
			if(document.getElementById('prestacao').value == '0,00'){
				document.getElementById('prestacao').value = '';
			}
			if(!vTexto('prestacao','o Valor da Prestacao do Financiamento')) return false;
			break;
		case '2':
			if(!vPositivo('prazo','o Prazo do Financiamento')) return false;
			break;
		default:
			alert('Informe o Valor da Prestacao ou o Prazo do Financiamento!');
			foco('sel_tipo_finan');
			return false;
	}
	if(!vVazio('prop_cpf')) if(!vCPF('prop_cpf')) return false;
	if(!vVazio('prop_nasc')) if(!vData('prop_nasc')) return false;
	if(!vVazio('prop_cep')) if(!vCEP('prop_cep')) return false;
	if(!vVazio('imov_cep')) if(!vCEP('imov_cep')) return false;

	// confirmação de pagamento do boleto // ------------------------------- //
	if(document.getElementById('chk_pagto')){
		if(document.getElementById('chk_pagto').checked){
			if(!vData('data_pagto')) return false;
		}
		if(!vVazio('data_pagto')){
			if(!vData('data_pagto')) return false;
			if(!document.getElementById('chk_pagto').checked){
				alert('A Data de Pagamento só pode ser informada com a Confirmação do Pagamento!');
				foco('chk_pagto');
				return false;
			}
		}
	}

	// -------------------------------------------------------------------- //
	var dataAprov = document.getElementById('imov_dt_aprov').value;
	var dataAval  = document.getElementById('imov_dt_aval').value;


	if(_aprovacao){
		if(!vData('imov_dt_aprov')) return false;
		if(!vData('imov_dt_aval')) return false;
		
		var comp = comparaDatas(dataAval,dataAprov);
		if(comp==0){
			alert('falha no cálculo de datas');
			return false;
		}else if(comp==1){
			alert('A Data de Aprovação não pode ser anterior à Data de Avaliação!');
			foco('imov_dt_aprov');
			return false;
		}
	
		var comp = comparaDatas(dataSistema,dataAprov);
		if(comp==0){
			alert('falha no cálculo de datas');
			return false;
		}else if(comp==3){
			alert('A Data de Aprovação não pode ser posterior à Data Atual!');
			foco('imov_dt_aprov');
			return false;
		}
	}
	
	// -------------------------------------------------------------------- //
	if(document.getElementById('imov_vl_aval').value == '0,00'){
		document.getElementById('imov_vl_aval').value = '';
	}

	if(!vVazio('imov_vl_aval')){
		if(!vTexto('imov_vl_aval')) return false;
		if(!vData('imov_dt_aval')) return false;
	}
	if(!vVazio('imov_dt_aval')){
		if(!vData('imov_dt_aval')) return false;
		if(!vTexto('imov_vl_aval')) return false;
	}
	
	if(!vVazio('imov_vl_aval') && !vVazio('imov_dt_aval')){
		var comp = comparaDatas(dataSistema,dataAval);
		if(comp==0){
			alert('falha no cálculo de datas');
			return false;
		}else if(comp==3){
			alert('A Data de Avaliação não pode ser posterior à Data Atual!');
			foco('imov_dt_aval');
			return false;
		}
		
    if(!vSelect('imov_tipo','x')) return false;
    if(!vSelect('imov_constr','x')) return false;
    if(!vSelect('imov_cond','x')) return false;
    if(!vPositivo('imov_area')) return false;
    if(!vSelect('imov_tp_impst','x')) return false;
    if(!vSelect('imov_cons_imov','x')) return false;
    
    var imovTipo = document.getElementById('imov_tipo');
		var opcao = imovTipo.selectedIndex;
		var tipo = imovTipo.options[opcao].value;
    if(tipo=='E'){
	    if(!vSelect('imov_cons_pred','x')) return false;
    }

    if(!vTexto('imov_sala')) return false;
    if(!vTexto('imov_quarto')) return false;
    if(!vTexto('imov_banh')) return false;
    if(!vTexto('imov_garag')) return false;
    if(!vTexto('imov_pavim')) return false;
    if(!vTexto('imov_empreg')) return false;

    if(!vSelect('imov_lograd','0')) return false;
    if(!vTexto('imov_ender')) return false;
    if(!vTexto('imov_num')) return false;
    if(!vSelect('imov_uf','0')) return false;
    if(!vSelect('imov_cidade','0')) return false;
    if(!vSelect('imov_bairro','0')) return false;
    if(!vCEP('imov_cep')) return false;

    if(!vSelect('imov_tp_morad','x')) return false;
    if(!vSelect('imov_terreo','x')) return false;

    if(!vCheck('imov_tb_dp_cnd')) return false;
    if(!vCheck('imov_incomb')) return false;
    if(!vCheck('imov_rural_fav')) return false;
    if(!vCheck('imov_em_constr')) return false;
    
    if(!_aprovacao){
	    if(!confirm("Ao informar o Valor de Avaliação do Imóvel,\nos dados do imóvel não poderão mais ser alterados.\nDeseja realmente continuar?")){
	    	document.getElementById('imov_vl_aval').value='';
	    	document.getElementById('imov_dt_aval').value='';
	    	foco('imov_vl_aval');
	    	return false;
	    }
    }
	}
	
	// -------------------------------------------------------------------- //
	
	var tipov = valorRadio('vend_tipo');
	switch(tipov){
		case '1':
			if(!vVazio('vend_cpf')) if(!vCPF('vend_cpf')) return false;
			if(!vVazio('vend_nasc')) if(!vData('vend_nasc')) return false;
			if(!vVazio('vend_rg')) if(!vTexto('vend_rg')) return false;
			if(!vVazio('vend_dtrg')) if(!vTexto('vend_dtrg')) return false;
			break;
		case '2':
			if(!vVazio('vend_cnpj')) if(!vCNPJ('vend_cnpj')) return false;
			break;
	}
	if(!vVazio('vend_cep')) if(!vTexto('vend_cep')) return false;
	if(!vVazio('vend_fone')) if(!vTelefone('vend_fone')) return false;
 	if(!vVazio('dsol_cep')) if(!vTexto('dsol_cep')) return false;
	if(!vVazio('dsol_fone')) if(!vTexto('dsol_fone')) return false;
	if(!vVazio('dsol_cpf')) if(!vCPF('dsol_cpf')) return false;
	return true;
}

function aprovarYImovel(_acao){
	if(validaPropostaPreenchidos(true)){
    if(confirm("Deseja realmente APROVAR o Imóvel?")){
	  	document.getElementById('proposta').action += '#';
	    document.getElementById('acaoProposta').value = _acao;
	    document.getElementById('proposta').submit();
	    return true;
		}else{
	    document.getElementById('imov_dt_aprov').value='';
    	foco('imov_dt_aprov');
    	return false;
    }
	}
}

function aprovarNImovel(_acao){
	if(validaPropostaPreenchidos(true)){
    if(confirm("Deseja realmente REPROVAR o Imóvel?")){
    	if(confirm("Ao REPROVAR o Imóvel a proposta será CANCELADA!\nDeseja realmente REPROVAR o Imóvel?")){
		    document.getElementById('proposta').action += '#';
		    document.getElementById('acaoProposta').value = _acao;
		    document.getElementById('proposta').submit();
		    return true;
    	}
    }
  	document.getElementById('imov_dt_aprov').value='';
  	foco('imov_dt_aprov');
  	return false;
	}
}

function excedeFinan(){
	var valor_total = valorFianciamento();
	var valor_max   = document.getElementById('valorMaxFinan').value;
	if(parseFloat(valor_total) > parseFloat(valor_max)){
    alert('O valor de financiamento excede o limite!');
    foco('valor_compra');
		return true;
	}
	return false;
}

function excedePrazo(){
	var valor_prazo = document.getElementById('prazo').value;
	var valor_max   = document.getElementById('prazoMaxFinan').value;
	if(parseFloat(valor_prazo) > parseFloat(valor_max)){
    alert('O prazo excede o limite!');
    foco('prazo');
		return true;
	}
	return false;
}

function excedeSinal(){
	if(valor_total_fianciamento <= 0){
    alert('A soma dos valores de sinal não podem ser maior que o valor de compra!');
    foco('valor_entrada');
		return true;
	}
	return false;
}

function trocouTipoImovel(_obj){
	var opcao = _obj.selectedIndex;
	var tipo = _obj.options[opcao].value;
	if(tipo == 'E'){
		showTR('tr_cons_pred');
	}else{
		hideTR('tr_cons_pred');
		document.getElementById('imov_cons_pred').selectedIndex=0;
	}
}

function selecionaTipoFinan(){
	var tipofin = valorRadio('sel_tipo_finan');
	document.getElementById('spnParcela').style.display = 'none';
	document.getElementById('spnPrazo').style.display   = 'none';
	if(tipofin==1) document.getElementById('spnParcela').style.display = 'inline';
	if(tipofin==2) document.getElementById('spnPrazo').style.display   = 'inline';
	atualizaValFinan();
	return false;
}

function selecionaEstadoCivilProponente(oEstadoCivil) {
	var sOpcao = oEstadoCivil.options[oEstadoCivil.selectedIndex].text;
	
}


*/
