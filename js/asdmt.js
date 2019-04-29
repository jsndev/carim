// FUNCOES de validação do formulario de PROPOSTA

function addEvento(_acao){
	if(!vTexto('novo_evento')) return false;
	document.getElementById('proposta').action += "#historico";
  document.getElementById('acaoProposta').value = _acao;
  document.getElementById('proposta').submit();
  return true;
}

function confirmarAssinatura(_acao){
	if(!vData('prop_assinatura')) return false;
	document.getElementById('proposta').action += "#historico";
  document.getElementById('acaoProposta').value = _acao;
  document.getElementById('proposta').submit();
  return true;
}

function salvarProposta(_acao){
	if(!vData('imov_dt_regist')) return false;
	if(!vTexto('imov_cartr_rgi')) return false;
	if(!vTexto('imov_matrc_rgi')) return false;
	if(!vTexto('imov_livro_rgi')) return false;
	if(!vTexto('imov_folhs_rgi')) return false;
	if(!vTexto('imov_rg_cprvnd')) return false;
	if(!vTexto('imov_rg_garant')) return false;
	document.getElementById('proposta').action += "#historico";
  document.getElementById('acaoProposta').value = _acao;
  document.getElementById('proposta').submit();
  return true;
}

function confirmarEnvioRemessa(_acao){
	if(!vData('prop_remessa')) return false;
	document.getElementById('proposta').action += "#historico";
  document.getElementById('acaoProposta').value = _acao;
  document.getElementById('proposta').submit();
  return true;
}

