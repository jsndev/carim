function exibeSimulador(){
	window.open('simulador_popup.php','simulador','fullscreen=no, location=no, menubar=no, resizable=no, scrollbars=no, status=no, titlebar=yes, toolbar=no, width=410, height=300,');
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

function selecionaPpntTipoFinan(){
	var tipofin = valorRadio('sel_tipo_ppnt_finan');
	document.getElementById('spnPpntParcela').style.display = 'none';
	document.getElementById('spnPpntPrazo').style.display   = 'none';
	if(tipofin==1) document.getElementById('spnPpntParcela').style.display = 'inline';
	if(tipofin==2) document.getElementById('spnPpntPrazo').style.display   = 'inline';
	//atualizaValFinan();
	return false;
}

function salvarBlocoProposta(){
	if(document.getElementById('dtapresdoc_ppst')){
		if(!vVazio('dtapresdoc_ppst')){
			if(!vData('dtapresdoc_ppst','a Data de Apresentação dos Documentos')) return false;
			var dtAprs = document.getElementById('dtapresdoc_ppst').value;
			var dtPpst = document.getElementById('frm_data_ppst').value;
			if( comparaDatas(dtAprs,dtPpst)==3 ){
				alert('A Data de Apresentação dos Documentos não pode ser menor que a Data de Cadastramento da Proposta!');
		  	foco('dtapresdoc_ppst');
		  	return false;
			}
			if( comparaDatas(dtAprs,DATA_ATUAL)==1 ){
				alert('A Data de Apresentação dos Documentos deve ser menor ou igual a Data Atual!');
				foco('dtapresdoc_ppst');
				return false;
			}
		}
	}
	return true;
}

/*
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

