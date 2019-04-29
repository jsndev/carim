// FUNCOES de validação do formulario de PROPOSTA

function obrigBlocoProposta(){
	if(!FLG_PREVI){
		if(!vFPositivo('valorcompra_ppst','o Valor de Compra')) return false;
		var tipov = valorRadio('sel_tipo_finan');
		switch(tipov){
			case '1':
				if(document.getElementById('vlprestsol_ppst').value == '0,00'){ document.getElementById('vlprestsol_ppst').value = ''; }
				if(!vFPositivo('vlprestsol_ppst','o Valor da Prestacao do Financiamento')) return false;
				break;
			case '2':
				if(!vPositivo('przfinsol_ppst','o Prazo do Financiamento')) return false;
				break;
			default:
				alert('Informe o Valor da Prestacao ou o Prazo do Financiamento!');
				foco('sel_tipo_finan');
				return false;
		}
	}
	return true;
}

function obrigBlocoProponentes(){
	var qtdppnt = parseFloat(document.getElementById('qtde_tr_ppnt').value);
	if(qtdppnt < 1){
		alert('É necessário adicionar pelo menos um Proponente!');
		document.location = '#proponente';
		return false;
	}else{
		for(i=1; i<=qtdppnt; i++){
			var msgErro = document.getElementById('msg_erro_'+i).value;
			if(msgErro!=''){
				var userErro = document.getElementById('nome_ppnt_'+i).value;
				alert('Proponente '+userErro+': '+msgErro);
				document.location = '#proponente';
				return false;
			}
		}
	}
	return true;
}


function obrigBlocoPagamento(){
	return true;
}

function obrigBlocoImovel(){
	if(document.getElementById('tbEditImov')){
		if(document.getElementById('dtaprovacao_imov')){
			if(!vSelect('cod_logr_imov','0',     'o Logradouro do Imóvel')) return false;
			if(!vTexto('endereco_imov',          'o Endereço do Imóvel')) return false;
			if(!vTexto('nrendereco_imov',        'o Número do Imóvel')) return false;
			if(!vSelect('cod_uf_imov','0',       'o Estado do Imóvel')) return false;
			if(!vSelect('cod_municipio_imov','0','o Município do Imóvel')) return false;
			if(!vSelect('cod_bairro_imov','0',   'o Bairro do Imóvel')) return false;
			if(!vCEP('cep_imov',                 'o CEP do Imóvel')) return false;
		}
	}
	return true;
}


function obrigBlocoVendedor(){
	var qtdvend = parseFloat(document.getElementById('qtde_tr_vend').value);
	if(qtdvend < 1){
			alert('É necessário adicionar pelo menos um Vendedor!');
			document.location = '#vendedor';
			return false;
	}else{
		for(i=1; i<=qtdvend; i++){
			if (document.getElementById('msg_erro_vend_'+i)) {
				var msgErro = document.getElementById('msg_erro_vend_'+i).value;
				if(msgErro!=''){
					var userErro = document.getElementById('nome_vend_'+i).value;
					alert('Vendedor '+userErro+': '+msgErro);
					document.location = '#vendedor';
					return false;
				}
			}
		}
	}
	return true;
}

function obrigBlocoAssinatura(){
	return true;
}

function obrigBlocoRemessa(){
	return true;
}

function obrigBlocoHistorico(){
	return true;
}

function obrigCheckLists(){
	return true;
}

