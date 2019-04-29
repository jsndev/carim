function atualizaFormVend(_tipo) {
	colapseDiv('div_pf');
	colapseDiv('div_pj');
	colapseDiv('div_pjs');
	colapseDiv('div_add_socio');
	colapseDiv('div_conjuje_vend');
	switch(_tipo){
		case 1:
			expandDiv('div_pf');
			break;
		case 2:
			expandDiv('div_pj');
			expandDiv('div_pjs');
			break;
	}
}

function trocouEstadoCivilVend(_obj){
	var opcao = _obj.selectedIndex;
	var tipo = _obj.options[opcao].value;
	if(tipo == 2){ // CASADO
		showTR('tr_casam_dt_aquis');
		showTR('tr_casam_dt_vend');
		showTR('tr_regime_bens_vend');
		hideTR('tr_uniao_estavel_vend');	
		expandDiv('div_conjuje_vend');
	}else if(tipo == 99){ // UNIÃO ESTÁVEL
		showTR('tr_casam_dt_vend');
		hideTR('tr_regime_bens_vend');
		hideTR('tr_casam_dt_aquis');
		resetObjSelect('vend_regimebens_ppcj');
		camposRegimeDeBensVend(false);
	  expandDiv('div_conjuje_vend');
	} else if(tipo==1 || tipo==3 || tipo==4 || tipo==5){
		hideTR('tr_regime_bens_vend');
		hideTR('tr_casam_dt_vend');
		hideTR('tr_casam_dt_aquis');
		resetObjSelect('vend_regimebens_ppcj');
		showTR('tr_uniao_estavel_vend');	
	}else{
		hideTR('tr_casam_dt_vend');
		hideTR('tr_casam_dt_aquis');
		hideTR('tr_regime_bens_vend');
		hideTR('tr_uniao_estavel_vend');	
		document.getElementById('vend_dtcasamento_ppcj').value='';
		resetObjSelect('vend_regimebens_ppcj');
		camposRegimeDeBensVend(false);
		//resetObjSelect('prop_regime_bens');
		colapseDiv('div_conjuje_vend');
	}
}

function trocouRegimeDeBensVend(_obj){
	var opcao = _obj.selectedIndex;
	var tipo = _obj.options[opcao].value;
	if(tipo == 1 || tipo == 3 || tipo == 5){
		camposRegimeDeBensVend(true);
	}else{
		camposRegimeDeBensVend(false);
	}
}

function trocouUniaoEstavelVend(_obj){
	if(_obj.value=='S' && _obj.checked){
		expandDiv('div_conjuje_vend');
		camposRegimeDeBensVend(true);
	}else{
		colapseDiv('div_conjuje_vend');
		resetFormDivId('div_conjuje_vend');
		camposRegimeDeBensVend(false);
	}
}

function trocouProcuradorVend(_obj){
	if(_obj.value=='S' && _obj.checked){
		expandDiv('tb_dets_proc_vend');
	}else{
		colapseDiv('tb_dets_proc_vend');
		resetFormDivId('tb_dets_proc_vend');
	}
}

function verificaAnuente(dat1,dat2)
{
//	alert(dat1);
	if(comparaDatas(dat1,dat2)==3)
	{
		document.getElementById('flganuente_vfisica').value='S';
	}else
	{
		document.getElementById('flganuente_vfisica').value='N';
	}
}

function camposRegimeDeBensVend(_yn){
	if(_yn){
		showTR('tr_vend_regime_bens_data');
		showTR('tr_vend_regime_bens_lavrado');
		showTR('tr_vend_regime_bens_livro');
		showTR('tr_vend_regime_bens_fls');
		showTR('tr_vend_regime_bens_nreg');
	}else{
		hideTR('tr_vend_regime_bens_data');
		hideTR('tr_vend_regime_bens_lavrado');
		hideTR('tr_vend_regime_bens_livro');
		hideTR('tr_vend_regime_bens_fls');
		hideTR('tr_vend_regime_bens_nreg');
	}
}

function trocouTrabConjVend(_obj){
	if(_obj.value=='S' && _obj.checked){
		camposTrabConjVend(true);
	}else{
		camposTrabConjVend(false);
	}
}
		
function camposTrabConjVend(_yn){
	if(_yn){
		//showTR('tr_vend_conj_trab_titulo');
		//showTR('tr_vend_conj_trab_empresa');
		//showTR('tr_vend_conj_trab_admissao');
		//showTR('tr_vend_conj_trab_endereco');
		//showTR('tr_vend_conj_trab_end_num');
		//showTR('tr_vend_conj_trab_compl');
		//showTR('tr_vend_conj_trab_estado');
		//showTR('tr_vend_conj_trab_bairro');
		//showTR('tr_vend_conj_trab_telefone');
		showTR('tr_vend_conj_trab_cargo');
		//showTR('tr_vend_conj_trab_salario');
	}else{
		//hideTR('tr_vend_conj_trab_titulo');
		//hideTR('tr_vend_conj_trab_empresa');
		//hideTR('tr_vend_conj_trab_admissao');
		//hideTR('tr_vend_conj_trab_endereco');
		//hideTR('tr_vend_conj_trab_end_num');
		//hideTR('tr_vend_conj_trab_compl');
		//hideTR('tr_vend_conj_trab_estado');
		//hideTR('tr_vend_conj_trab_bairro');
		//hideTR('tr_vend_conj_trab_telefone');
		hideTR('tr_vend_conj_trab_cargo');
		//hideTR('tr_vend_conj_trab_salario');
	}
}

function openFormAddVend(){
	resetFormDivId('div_add_vend'); // dá um reset nos campos dentro de uma div
	atualizaFormVend(3);
	hideTR('tr_casam_dt_vend');
	hideTR('tr_regime_bens_vend');
	hideTR('tfoot_lista_vend_socios');
	colapseDiv('div_conjuje_vend');
	var listaSocios = document.getElementById('tbody_lista_vend_socios');
	for(i=0; i < listaSocios.childNodes.length; i++){
		listaSocios.removeChild(listaSocios.lastChild);
	}
	camposRegimeDeBensVend(false);
	camposTrabConjVend(false);
	expandDiv('div_add_vend'); // mostra FORM limpo
	document.location = '#vendedor';
}

function cancelFormAddVend(){
	colapseDiv('div_add_vend');
	resetFormDivId('div_add_vend'); // dá um reset nos campos dentro de uma div
	//document.getElementById('acaoProposta').value = 'CVend';
	document.location = '#vendedor';
}

function delVend(_cod,_acao,_nome){
	if(confirm('Deseja excluir o vendedor "'+_nome+'" ?')){
		document.getElementById('frm_cod_vend').value = _cod;
		document.getElementById('acaoProposta').value = _acao;
		document.getElementById('proposta').action += '#vendedor';
		document.getElementById('proposta').submit();
	}
}

function altVend(_cod,_acao){
	document.getElementById('frm_cod_vend').value = _cod;
	document.getElementById('acaoProposta').value = _acao;
	document.getElementById('proposta').action += '#vendedor';
	document.getElementById('proposta').submit();
}

function corrigirVend(_cod,_acao){
	document.getElementById('frm_cod_vend').value = _cod;
	document.getElementById('acaoProposta').value = _acao;
	document.getElementById('proposta').action = 'corrigir.php?corrigir=sim#vendedor';
	document.getElementById('proposta').submit();
}

function corrigirProcVend()
{
	document.getElementById('proposta').action = 'corrigir.php?corrigir=sim#vendedor';
	document.getElementById('hdproc_vend').value='salvar';
}

function dtsVend(_cod,_acao){
	document.getElementById('frm_cod_vend').value = _cod;
	document.getElementById('acaoProposta').value = _acao;
	document.getElementById('proposta').action += '#vendedor';
	document.getElementById('proposta').submit();
}

function saveVend(_acao){
	if(!salvarCheckList('ckls_vnpj','div_add_vend','','vnpj_cklst','vendedor','do Vendedor')) return false;
	if(!salvarCheckList('ckls_vnpf','div_add_vend','','vnpf_cklst','vendedor','do Vendedor')) return false;
	if(!salvarCheckList('ckls_pfcj','div_add_vend','','vend_pfcj_cklst','vendedor','do Cônjuge do Vendedor')) return false;
	if(!salvarBlocoVendedores()) return false;
  document.getElementById('acaoProposta').value = _acao;
	document.getElementById('proposta').action += "#vendedor";
  document.getElementById('proposta').submit();
}


function salvarCorrigirVend(_acao){
	if(!salvarCheckList('ckls_vnpj','div_add_vend','','vnpj_cklst','vendedor','do Vendedor')) return false;
	if(!salvarCheckList('ckls_vnpf','div_add_vend','','vnpf_cklst','vendedor','do Vendedor')) return false;
	if(!salvarCheckList('ckls_pfcj','div_add_vend','','vend_pfcj_cklst','vendedor','do Cônjuge do Vendedor')) return false;
	if(!salvarBlocoVendedores()) return false;
	document.getElementById('acaoProposta').value = _acao;
	document.getElementById('proposta').action = 'corrigir.php?corrigir=sim#vendedor';
	document.getElementById('proposta').submit();
}

function addVend(_acao){
	if(!salvarCheckList('ckls_vnpj','div_add_vend','','vnpj_cklst','vendedor','do Vendedor')) return false;
	if(!salvarCheckList('ckls_vnpf','div_add_vend','','vnpf_cklst','vendedor','do Vendedor')) return false;
	if(!salvarCheckList('ckls_pfcj','div_add_vend','','vend_pfcj_cklst','vendedor','do Cônjuge do Vendedor')) return false;
	if(!salvarBlocoVendedores()) return false;
	document.getElementById('frm_cod_vend').value = 'N';
	document.getElementById('acaoProposta').value = _acao;
	document.getElementById('proposta').action += '#vendedor';
	document.getElementById('proposta').submit();
}

function salvarBlocoVendedores(){
	if( document.getElementById('tbEditVend') ){
		if(valorRadio('vend_tipo')=='1'){ // Pessoa Fisica
			if(!vVazio('vend_cpf')) if(!vCPF('vend_cpf', 'o CPF do Vendedor')) return false;
			if(!vVazio('vend_nasc')){
				if(!vData('vend_nasc', 'a Data de Nascimento do Vendedor')) return false;
				if(!vIdade('vend_nasc',2,'a Idade do Vendedor')) return false;
				if(comparaDatasId('vend_nasc','')==1){
					alert('a Data de Nascimento do Vendedor nao pode ser maior que a data atual');
					foco('vend_nasc');
					return false;
				}
			}
			if(!vVazio('vend_dtrg')){
				if(!vData('vend_dtrg', 'a Data de Emissão do RG do Vendedor')) return false;
				if(comparaDatasId('vend_dtrg','')==1){
					alert('a Data de Emissão do RG do Vendedor nao pode ser maior que a data atual');
					foco('vend_dtrg');
					return false;
				}
				if(comparaDatasId('vend_nasc','vend_nasc')==3){
					alert('a Data de Emissão do RG do Vendedor nao pode ser menor que a Data de Nascimento');
					foco('vend_dtrg');
					return false;
				}
			}
			if(valorSelect('vend_civil')=='2'){
				if(!vVazio('vend_dtcasamento_ppcj')){
					if(!vData('vend_dtcasamento_ppcj', 'a Data de Casamento do Vendedor')) return false;
					if(comparaDatasId('vend_dtcasamento_ppcj','')==1){
						alert('a Data de Casamento do Vendedor nao pode ser maior que a data atual');
						foco('vend_dtcasamento_ppcj');
						return false;
					}
					if(!vIdade('vend_nasc',16,'a Idade do Vendedor na Data de Casamento','vend_dtcasamento_ppcj')) return false;
				}
				if(valorSelect('vend_regimebens_ppcj')=='3' || valorSelect('vend_regimebens_ppcj')=='5'){
					if(!vVazio('vend_data_pcpa')){
						if(!vData('vend_data_pcpa', 'a Data do Registro Regime de Bens do Vendedor')) return false;
						if(comparaDatasId('vend_data_pcpa','')==1){
							alert('a Data do Registro Regime de Bens do Vendedor nao pode ser maior que a data atual');
							foco('vend_data_pcpa');
							return false;
						}
						if(!vIdade('vend_nasc',16,'a Idade do Vendedor na Data do Registro Regime de Bens do Vendedor','vend_data_pcpa')) return false;
					}
				}
				if(!vVazio('vend_dtrg_ppcj')){
					if(!vData('vend_dtrg_ppcj', 'a Data de Emissão do RG do Cônjuge')) return false;
					if(comparaDatasId('vend_dtrg_ppcj','')==1){
						alert('a Data de Emissão do RG do Cônjuge nao pode ser maior que a data atual');
						foco('vend_dtrg_ppcj');
						return false;
					}
				}
				if(!vVazio('vend_cpf_pccj')) if(!vCPF('vend_cpf_pccj', 'o CPF do Cônjuge')) return false;
				if(valorRadio('vend_flgtrabalha_ppcj')=='S'){
					if(!vVazio('vend_dtadmissaoemp_ppcj')){
						if(!vData('vend_dtadmissaoemp_ppcj', 'a Data de Admissão do Cônjuge')) return false;
						if(comparaDatasId('vend_dtadmissaoemp_ppcj','')==1){
							alert('a Data de Admissão do Cônjuge nao pode ser maior que a data atual');
							foco('vend_dtadmissaoemp_ppcj');
							return false;
						}
					}
					if(!vVazio('vend_telefoneemp_ppcj')) if(!vTelefone('vend_telefoneemp_ppcj', 'o Telefone da Empresa do Cônjuge')) return false;
				}
			}
		}
		if(valorRadio('vend_tipo')=='2'){ // Pessoa Juridica
			if(!vVazio('vend_cnpj')) if(!vCNPJ('vend_cnpj', 'o CNPJ do Vendedor')) return false;
			if(!vVazio('vend_s_cep')) if(!vCEP('vend_s_cep', 'o CEP do Sócio')) return false;
			if(!vVazio('vend_s_fone')) if(!vTelefone('vend_s_fone', 'o Telefone do Sócio')) return false;
			if(!vVazio('vend_s_cpf')) if(!vTelefone('vend_s_cpf', 'o CPF do Sócio')) return false;
		}
		if(!vVazio('vend_cep')) if(!vCEP('vend_cep', 'o CEP do Vendedor')) return false;
		if(!vVazio('vend_fone_1')) if(!vTelefone('vend_fone_1', 'o Telefone do Vendedor')) return false;
		//if(!vSelect('vend_tipofone_1', '0', 'o Tipo do Telefone do Vendedor')) return false;
		
		if(!salvarCheckList('ckls_vnpj','div_add_vend','','vnpj_cklst','vendedor','do Vendedor')) return false;
		if(!salvarCheckList('ckls_vnpf','div_add_vend','','vnpf_cklst','vendedor','do Vendedor')) return false;
		if(!salvarCheckList('ckls_pfcj','div_add_vend','','vend_pfcj_cklst','vendedor','do Cônjuge do Vendedor')) return false;
	}
	return true;
}

function showDetsVend(_id){
	if(_id){
		if(document.getElementById('tr_dets_vend_'+_id).style.display=='none'){
			showTR('tr_dets_vend_'+_id);
			document.getElementById('flg_show_dets_vend_'+_id).value = 'S';
		}else{
			hideTR('tr_dets_vend_'+_id);
			document.getElementById('flg_show_dets_vend_'+_id).value = 'N';
		}
	}
}

function SalvarProcVend()
{
	document.getElementById('hdproc_vend').value='salvar';
}
