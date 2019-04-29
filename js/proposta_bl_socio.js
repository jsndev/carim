function openFormAddSocio(){
	resetFormDivId('div_add_socio'); // dá um reset nos campos dentro de uma div
	expandDiv('div_add_socio');
	document.location = '#socios';
	hideInLine('bt_save_socio');
	showInLine('bt_add_socio');
}

function cancelFormAddSocio(){
	colapseDiv('div_add_socio');
	resetFormDivId('div_add_socio'); // dá um reset nos campos dentro de uma div
	document.location = '#vendedor';
}

function delSocio(_cod,_vend,_acao,_nome){
	document.getElementById('frm_cod_socio').value = _cod;
	document.getElementById('frm_cod_vend').value = _vend;
	document.getElementById('acaoProposta').value = _acao;
	document.getElementById('proposta').action += '#socios';
	document.getElementById('proposta').submit();
}

function altSocio(_cod,_vend,_acao){
	document.getElementById('frm_cod_socio').value = _cod;
	document.getElementById('frm_cod_vend').value = _vend;
	document.getElementById('acaoProposta').value = _acao;
	document.getElementById('proposta').action += '#socios';
	document.getElementById('proposta').submit();
}

function dtsSocio(_cod,_vend,_acao){
	document.getElementById('frm_cod_socio').value = _cod;
	document.getElementById('frm_cod_vend').value = _vend;
	document.getElementById('acaoProposta').value = _acao;
	document.getElementById('proposta').action += '#socios';
	document.getElementById('proposta').submit();
}


function saveSocio(_acao){
  document.getElementById('acaoProposta').value = _acao;
	document.getElementById('proposta').action += "#socios";
  document.getElementById('proposta').submit();
}

function addSocio(_acao){
	document.getElementById('frm_cod_socio').value = '';
	document.getElementById('acaoProposta').value = _acao;
	document.getElementById('proposta').action += '#socios';
	document.getElementById('proposta').submit();
}

