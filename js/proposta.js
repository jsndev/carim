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
    fsRP.style.display = 'none';
  }
}

function atualizaFormVend(_tipo) {
	hideTR('tr_cpf');
	hideTR('tr_cnpj');
	hideTR('tr_repr');
	switch(_tipo){
		case 1:
			showTR('tr_cpf');
			break;
		case 2:
			showTR('tr_cnpj');
			showTR('tr_repr');
			break;
	}
}

function atualizarTela(_ancora,_acao){
  if(validaProposta(false,false,false,false,false)){
  	if(_ancora){
  		document.getElementById('proposta').action += '#'+_ancora;
  	}
    document.getElementById('acaoProposta').value = _acao;
    document.getElementById('proposta').submit();
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

function calcularProposta(_acao){
  if(validaProposta(true,false,false,false,false)){
  	document.getElementById('proposta').action += '#proposta';
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

function salvarPropostaChkLst(_acao){
	if(validaCheckList()){
		document.getElementById('proposta').action += '#';
		document.getElementById('acaoProposta').value = _acao;
		document.getElementById('proposta').submit();
		return true;
	}
 	return false;
}

function salvarProposta(_acao,_passo){
	if (parseFloat(_passo) == 9) {
		if(!vData('imov_dt_regist')) return false;
		if(!vTexto('imov_cartr_rgi')) return false;
		if(!vTexto('imov_matrc_rgi')) return false;
		if(!vTexto('imov_livro_rgi')) return false;
		if(!vTexto('imov_folhs_rgi')) return false;
		if(!vTexto('imov_rg_cprvnd')) return false;
		if(!vTexto('imov_rg_garant')) return false;
	}
	document.getElementById('proposta').action += "#historico";
	document.getElementById('acaoProposta').value = _acao;
	document.getElementById('proposta').submit();
	return true;
}

function concluirProposta(_acao){
	var mensagem = "Ao concluir a proposta o processo será enviado para Avaliação.\nDeseja realmente concluir a Proposta?";
  if(validaProposta(true,true,true,true,false)){
  	if( confirm(mensagem) ){
	  	document.getElementById('proposta').action += '#';
	    document.getElementById('acaoProposta').value = _acao;
	    document.getElementById('proposta').submit();
	    return true;
  	}
  }
 	return false;
}

function addEvento(_acao){
	document.getElementById('proposta').action += "#historico";
	if(validaProposta(false,false,false,false,true)){
    document.getElementById('acaoProposta').value = _acao;
    document.getElementById('proposta').submit();
    return true;
  }
 	return false;
}

function validaProposta(_validarPPST,_validarPPNT,_validarIMVL,_validarVEND,_validarEVNT){
  if(_validarPPST){
    if(!vCheck('tipo_simulador','da Tabela de Financiamento')) return false;
    if(!vTexto('valor_compra','o Valor de Compra')) return false;
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
  }// 2500-3 / 15856-9
  if(_validarPPNT){
    if(!vCPF('prop_cpf','o seu CPF')) return false;
    if(!vData('prop_nasc','sua Data de Nascimento')) return false;
    if(!vSelect('prop_civil','0','seu Estado Civil')) return false;
    if(!vSelect('prop_lograd','0',' o tipo de Logradouro de seu Endereço')) return false;
    if(!vTexto('prop_ender','seu Endereço')) return false;
    if(!vTexto('prop_num',' o Número de seu Endereço')) return false;
    //if(!vTexto('prop_compl')) return false;
    if(!vSelect('prop_bairro','0','seu Bairro')) return false;
    if(!vSelect('prop_uf','0','seu Estado')) return false;
    if(!vSelect('prop_cidade','','sua Cidade')) return false;
    if(!vCEP('prop_cep','seu CEP')) return false;
    if(!vTelefone('prop_fone','seu Telefone')) return false;
  }
  if(_validarIMVL){
    if(!vSelect('imov_tipo','x','o Tipo do Imóvel')) return false;
    if(!vSelect('imov_constr','x','o Tipo da Construção')) return false;
    if(!vSelect('imov_cond','x','o Tipo do Condomínio')) return false;
    if(!vSelect('imov_lograd','0','o Tipo do Logradouro do Imóvel')) return false;
    //if(!vNumerico('imov_sala')) return false;
    //if(!vNumerico('imov_quarto')) return false;
    //if(!vNumerico('imov_banh')) return false;
    //if(!vNumerico('imov_garag')) return false;
    //if(!vNumerico('imov_pavim')) return false;
    //if(!vNumerico('imov_empreg')) return false;
    if(!vTexto('imov_ender','o Endereço do Imóvel')) return false;
    if(!vTexto('imov_num','o Número do Endereço do Imóvel')) return false;
    //if(!vTexto('imov_compl')) return false;
    if(!vSelect('imov_bairro','0','o Bairro do Imóvel')) return false;
    if(!vSelect('imov_uf','0','o Estado do Imóvel')) return false;
    if(!vSelect('imov_cidade','0','a Cidade do Imóvel')) return false;
    if(!vCEP('imov_cep','o CEP do Imóvel')) return false;
  }
  if(_validarVEND){
  	if(!vTexto('vend_nome','o Nome do Vendedor')) return false;
  	if(!vCheck('tipo_vend','o Tipo do Vendedor')) return false;
  	if(!vTelefone('vend_fone','o Telefone do Vendedor')) return false;
  	
  	var tipov = valorRadio('tipo_vend');
  	switch(tipov){
  		case '1':
  			if(!vCPF('vend_cpf','o CPF do Vendedor')) return false;
  			break;
  		case '2':
  			if(!vCNPJ('vend_cnpj','o CNPJ do Vendedor')) return false;
  			if(!vTexto('vend_reprs','o Nome do Representante Legal')) return false;
  			break;
  	}
  }
  if(_validarEVNT){
  	if(!vTexto('novo_evento')) return false;
  }
  return true;
}

function validaPropostaPreenchidos(){
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
	if(!vVazio('prop_cpf')) if(!vCPF('prop_cpf')) return false;
	if(!vVazio('prop_nasc')) if(!vData('prop_nasc')) return false;
	if(!vVazio('prop_cep')) if(!vCEP('prop_cep')) return false;
	if(!vVazio('imov_cep')) if(!vCEP('imov_cep')) return false;
	var tipov = valorRadio('tipo_vend');
	switch(tipov){
		case '1':
			if(!vVazio('vend_cpf')) if(!vCPF('vend_cpf')) return false;
			break;
		case '2':
			if(!vVazio('vend_cnpj')) if(!vCNPJ('vend_cnpj')) return false;
			break;
	}
	if(!vVazio('vend_fone')) if(!vTelefone('vend_fone')) return false;
  return true;
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
*/
