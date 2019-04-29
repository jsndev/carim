// FUNCOES de validação do formulario de PROPOSTA
/*
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
	
function atualizaValFinan(){
	var valor_total = valorFianciamento();
  document.getElementById('valor_total').innerHTML = formataMoeda(parseInt(valor_total*100));
    
  if(fsRP = document.getElementById('divResultadoProposta')){
    fsRP.style.display = 'none';
  }
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
  if(validaCheckList()){
  	document.getElementById('proposta').action += '#';
    document.getElementById('acaoProposta').value = _acao;
    document.getElementById('proposta').submit();
    return true;
  }
  return false;
}


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

function concluirProposta(_acao){
	var mensagem = "Ao concluir a proposta o processo será enviado para Análise Jurídica.\nDeseja realmente concluir a Proposta?";
  if(validaProposta(true,true,true,true,true,false)){
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
    if(!vPositivo('prazo')) return false;
    //if(!vTexto('taxa')) return false;
    //if(!vTexto('valor_seguro')) return false;
    //if(!vTexto('valor_manut')) return false;
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

function validaPropostaPreenchidos(){
	if(excedeFinan()) return false;
	if(excedePrazo()) return false;
	if(!vVazio('prop_cpf')) if(!vCPF('prop_cpf')) return false;
	if(!vVazio('prop_nasc')) if(!vData('prop_nasc')) return false;
	if(!vVazio('prop_cep')) if(!vCEP('prop_cep')) return false;
	if(!vVazio('imov_cep')) if(!vCEP('imov_cep')) return false;
	
	if(!vVazio('imov_vl_aval')){
		if(document.getElementById('imov_vl_aval').value != '0,00'){
			if(!vTexto('imov_vl_aval')) return false;
			if(!vData('imov_dt_aval')) return false;
		}
	}
	if(!vVazio('imov_dt_aval')){
		if(!vData('imov_dt_aval')) return false;
		if(!vTexto('imov_vl_aval')) return false;
	}
	
	if(!vVazio('imov_aprov')){
		if(!vData('imov_aprov')) return false;
		// ...
	}
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

function hideTR(_id){
	document.getElementById(_id).style.display = 'none';
}

function showTR(_id){
	if(navigator.appName=='Netscape'){
		document.getElementById(_id).style.display = 'table-row';
	}else{
		document.getElementById(_id).style.display = 'block';
	}
}
*/
