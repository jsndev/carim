function openFormAddCkAdv(){
	resetFormDivId('div_add_ck_adv'); // dá um reset nos campos dentro de uma div
	expandDiv('div_add_ck_adv');
	document.location = '#advogado';
}

function cancelFormAddCkAdv(){
	colapseDiv('div_add_ck_adv');
	resetFormDivId('div_add_ck_adv'); // dá um reset nos campos dentro de uma div
	document.location = '#advogado';
}

function saveCkAdv(_acao){
  document.getElementById('acaoProposta').value = _acao;
	document.getElementById('proposta').action += "#advogado";
  document.getElementById('proposta').submit();
}

function addCkAdv(_acao){
	document.getElementById('frm_cod_ck_adv').value = 'N';
	document.getElementById('acaoProposta').value = _acao;
	document.getElementById('proposta').action += '#advogado';
	document.getElementById('proposta').submit();
}

function delCkAdv(_cod,_acao,_nome){
	if(confirm('Deseja excluir o item "'+_nome+'" ?')){
		document.getElementById('frm_cod_ck_adv').value = _cod;
		document.getElementById('acaoProposta').value = _acao;
		document.getElementById('proposta').action += '#advogado';
		document.getElementById('proposta').submit();
	}
}

function altCkAdv(_cod,_acao){
	document.getElementById('frm_cod_ck_adv').value = _cod;
	document.getElementById('acaoProposta').value = _acao;
	document.getElementById('proposta').action += '#advogado';
	document.getElementById('proposta').submit();
}

function salvarBlocoAdvogado(){
	if( document.getElementById('ckls_advg') ){
		if(!salvarCheckList('ckls_advg','','checklistadvogado','advogado_cklst','advogado','do Advogado')) return false;
	}
	return true;
}

