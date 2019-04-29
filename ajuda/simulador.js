// FUNCOES de validação do SIMULADOR de PROPOSTA

function atualizaValFinan(){
	document.getElementById('prest_lbl').innerHTML = '&nbsp;';
	document.getElementById('prest_val').innerHTML = '&nbsp;';
	document.getElementById('reduc_lbl').innerHTML = '&nbsp;';
	document.getElementById('reduc_val').innerHTML = '&nbsp;';
}

function validaForm(){
  if(!vCheck('tipo_simulador')) return false;
  if(!vTexto('valor')) return false;
  if(!vPositivo('prazo')) return false;
  //if(!vTexto('taxa')) return false;
  return true;
}

