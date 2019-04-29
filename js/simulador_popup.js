// FUNCOES de validação do SIMULADOR de PROPOSTA

function getParentData(){
	if(window.opener.document.getElementById('vlfinsol_ppst')){
		document.getElementById('valor').value = window.opener.document.getElementById('vlfinsol_ppst').value;
		document.getElementById('prazo').value = window.opener.document.getElementById('przfinsol_ppst').value;
	}
}

function travaValorFinan(){
	if(window.opener.document.getElementById('vlfinsol_ppst')){
		if(document.getElementById('usarValFin').value == ''){
			if(document.getElementById('valor').value == '0,00'){
				document.getElementById('usarValFin').value = 'S';
			}else{
				document.getElementById('usarValFin').value = 'N';
			}
		}
		if(document.getElementById('usarValFin').value == 'S'){
			document.getElementById('valor').readonly     = false;
			document.getElementById('valor').style.color  = '#000';
			//document.getElementById('valor').style.fontWeight = 'normal';
			document.getElementById('valor').onfocus = function(){ this.focus(); }
		}else{
			document.getElementById('valor').readonly     = true;
			document.getElementById('valor').style.color  = '#AAA';
			//document.getElementById('valor').style.fontWeight = 'bold';
			document.getElementById('valor').onfocus = function(){ this.blur(); }
		}
	}
}

function atualizaValFinan(){
	document.getElementById('prest_lbl').innerHTML = '&nbsp;';
	document.getElementById('prest_val').innerHTML = '&nbsp;';
	document.getElementById('reduc_lbl').innerHTML = '&nbsp;';
	document.getElementById('reduc_val').innerHTML = '&nbsp;';
	document.getElementById('usarDados').style.display = 'none';
}

function validaForm(){
  if(!vCheck('tipo_simulador')) return false;
  if(!vTexto('valor')) return false;
  if(!vPositivo('prazo')) return false;
  //if(!vTexto('taxa')) return false;
  return true;
}

function usarDadosRun(){
	var usarValFin = (document.getElementById('usarValFin').value=='S');
	var inputs = window.opener.document.getElementsByTagName('input');
	for(i=0; i<inputs.length; i++){
		if(inputs[i].name=='sel_tipo_finan' && inputs[i].value=='2'){
			inputs[i].checked=true;
		}
	}
	window.opener.selecionaTipoFinan();
	window.opener.document.getElementById('przfinsol_ppst').value = document.getElementById('prazo').value;
	if(usarValFin){
		window.opener.document.getElementById('valorcompra_ppst').value = document.getElementById('valor').value;
		window.opener.document.getElementById('valordevsinalsol_ppst').value = '0,00';
		window.opener.document.getElementById('valorfgts_ppst').value = '0,00';
		window.opener.atualizaValFinan();
	}
	window.close(true);
}

