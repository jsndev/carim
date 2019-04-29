function trocouEstadoCivilProp(_obj){
	var opcao = _obj.selectedIndex;
	var tipo = _obj.options[opcao].value;
	if(tipo == 2){ // CASADO
		showTR('tr_casam_dt');
		showTR('tr_regime_bens');
		hideTR('tr_uniao_estavel');
	  expandDiv('div_conjuje');
	} else if(tipo == 99){ // UNIÃO ESTÁVEL
		showTR('tr_casam_dt');
		hideTR('tr_regime_bens');
		resetObjSelect('regimebens_ppcj');
		camposRegimeDeBensProp(false);
	  expandDiv('div_conjuje');
	} else if(tipo==1 || tipo==3 || tipo==4 || tipo==5){
		hideTR('tr_regime_bens');
		hideTR('tr_casam_dt');
		resetObjSelect('regimebens_ppcj');
		showTR('tr_uniao_estavel');	
	}else{
		hideTR('tr_casam_dt');
		hideTR('tr_regime_bens');
		hideTR('tr_uniao_estavel');
		document.getElementById('dtcasamento_ppcj').value='';
		resetObjSelect('regimebens_ppcj');
		camposRegimeDeBensProp(false);
		//resetObjSelect('prop_regime_bens');
		colapseDiv('div_conjuje');
		resetFormDivId('div_conjuje');
	}
}

function trocouRegimeDeBensProp(_obj){
	var opcao = _obj.selectedIndex;
	var tipo = _obj.options[opcao].value;
	if(tipo == 1 || tipo == 3 || tipo == 5){
		camposRegimeDeBensProp(true);
	}else{
		camposRegimeDeBensProp(false);
	}
}

function trocouUniaoEstavel(_obj){
	if(_obj.value=='S' && _obj.checked){
		expandDiv('div_conjuje');
		showTR('tr_escritura');
	}else{
		colapseDiv('div_conjuje');
		resetFormDivId('div_conjuje');
		hideTR('tr_escritura');
	}
}

function trocouEscritura(_obj){
	if(_obj.value=='S' && _obj.checked){
		camposRegimeDeBensProp(true);
	}else{
		camposRegimeDeBensProp(false);
	}
}


function camposRegimeDeBensProp(_yn){
	if(_yn){
		showTR('tr_regime_bens_data');
		showTR('tr_regime_bens_lavrado');
		showTR('tr_regime_bens_livro');
		showTR('tr_regime_bens_fls');
		showTR('tr_regime_bens_nreg');
		showTR('tr_regime_bens_flgbens');
		showTR('tr_regime_bens_habens_cart');
		showTR('tr_regime_bens_flgbens_data');
}else{
		hideTR('tr_regime_bens_data');
		hideTR('tr_regime_bens_lavrado');
		hideTR('tr_regime_bens_livro');
		hideTR('tr_regime_bens_fls');
		hideTR('tr_regime_bens_nreg');
		hideTR('tr_regime_bens_flgbens');
		hideTR('tr_regime_bens_habens_cart');
		hideTR('tr_regime_bens_habens_data');
		document.getElementById('data_pcpa').value='';
		document.getElementById('locallavracao_pcpa').value='';
		document.getElementById('livro_pcpa').value='';
		document.getElementById('folha_pcpa').value='';
		document.getElementById('numeroregistro_pcpa').value='';
		document.getElementById('habens_pcpa').value='';
		document.getElementById('habenscart_pcpa').value='';
		document.getElementById('habensloccart_pcpa').value='';
		document.getElementById('habensdata_pcpa').value='';

	}
}

function trocouTrabConj(_obj){
	if(_obj.value=='S' && _obj.checked){
		camposTrabConj(true);
	}else{
		camposTrabConj(false);
	}
}
		
function camposTrabConj(_yn){
	if(_yn){
		//showTR('tr_conj_trab_titulo');
		//showTR('tr_conj_trab_empresa');
		//showTR('tr_conj_trab_admissao');
		//showTR('tr_conj_trab_endereco');
		//showTR('tr_conj_trab_end_num');
		//showTR('tr_conj_trab_compl');
		//showTR('tr_conj_trab_estado');
		//showTR('tr_conj_trab_bairro');
		//showTR('tr_conj_trab_telefone');
		showTR('tr_conj_trab_cargo');
		//showTR('tr_conj_trab_salario');
	}else{
		//hideTR('tr_conj_trab_titulo');
		//hideTR('tr_conj_trab_empresa');
		//hideTR('tr_conj_trab_admissao');
		//hideTR('tr_conj_trab_endereco');
		//hideTR('tr_conj_trab_end_num');
		//hideTR('tr_conj_trab_compl');
		//hideTR('tr_conj_trab_estado');
		//hideTR('tr_conj_trab_bairro');
		//hideTR('tr_conj_trab_telefone');
		hideTR('tr_conj_trab_cargo');
		//hideTR('tr_conj_trab_salario');
	}
}

function trocouTemDevSol(_obj){
	if(_obj.value=='S' && _obj.checked){
		expandDiv('tb_dets_dev_sol');
	}else{
		colapseDiv('tb_dets_dev_sol');
		resetFormDivId('tb_dets_dev_sol');
	}
}

function trocouPropFgts(_obj){
	if(_obj.value=='S' && _obj.checked){
		expandDiv('tb_dets_propfgts');
	}else{
		colapseDiv('tb_dets_propfgts');
		resetFormDivId('tb_dets_propfgts');
	}
}

function trocouTemFgts(_obj){
	if(_obj.value=='S' && _obj.checked){
		expandDiv('tb_dets_fgts');
	}else{
		colapseDiv('tb_dets_fgts');
		resetFormDivId('tb_dets_fgts');
	}
}

function trocouHaBens(_obj){
	if(_obj.value=='S' && _obj.checked){
		expandDiv('tb_dets_habens_cart');
		expandDiv('tb_dets_habens_data');
	}else{
		colapseDiv('tb_dets_habens_cart');
		resetFormDivId('tb_dets_habens_cart');
		colapseDiv('tb_dets_habens_data');
		resetFormDivId('tb_dets_habens_data');
	}
}

function trocouProcurador(_obj){
	if(_obj.value=='S' && _obj.checked){
		expandDiv('tb_dets_proc');
	}else{
		colapseDiv('tb_dets_proc');
		resetFormDivId('tb_dets_proc');
	}
}

function addexig(_obj){
	if(_obj.value=='1' && _obj.checked){
		expandDiv('tb_dets_exig');
	}else{
		colapseDiv('tb_dets_exig');
		resetFormDivId('tb_dets_exig');
	}
}

function openFormAddPpnt(){
	resetFormDivId('div_add_ppnt'); // dá um reset nos campos dentro de uma div
	expandDiv('div_add_ppnt');
	document.location = '#proponente';
}

function cancelFormAddPpnt(limpar){
	colapseDiv('div_add_ppnt');
	if(limpar){
		resetFormDivId('div_add_ppnt'); // dá um reset nos campos dentro de uma div
		resetCalculo();
		camposRegimeDeBensProp(false);
		camposTrabConj(false);
		hideTR('tr_casam_dt');
		hideTR('tr_regime_bens');
		colapseDiv('div_conjuje');
		document.getElementById('frm_cod_ppnt').value = '';
		colapseDiv('tb_dets_dev_sol');
	}
	document.location = '#proponente';
}

function savePpnt(_acao,_pag){
	if(!salvarCheckList('ckls_ppnt','div_add_ppnt','','proponente_cklst','proponente','do Proponente')) return false;
	if(!salvarCheckList('ckls_ppcj','div_add_ppnt','','conjuge_cklst','conjuge','do Cônjuge do Proponente')) return false;
	if(!salvarBlocoProponentes()) return false;
	document.getElementById('proposta').action = submeter;
	document.getElementById('proposta').action += "#proponente";
	document.getElementById('acaoProposta').value = _acao;
  	document.getElementById('proposta').submit();
}

function addPpnt(_acao){
	if(!salvarCheckList('ckls_ppnt','div_add_ppnt','','proponente_cklst','proponente','do Proponente')) return false;
	if(!salvarCheckList('ckls_ppcj','div_add_ppnt','','conjuge_cklst','conjuge','do Cônjuge do Proponente')) return false;
	if(!salvarBlocoProponentes()) return false;
	document.getElementById('proposta').action += "#proponente";
  document.getElementById('acaoProposta').value = _acao;
  document.getElementById('proposta').submit();
}

function delPpnt(_cod,_acao,_nome){
	if(confirm('Deseja excluir o proponente "'+_nome+'" ?')){
		document.getElementById('frm_cod_ppnt').value = _cod;
		document.getElementById('acaoProposta').value = _acao;
		document.getElementById('proposta').action += '#proponente';
		document.getElementById('proposta').submit();
	}
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

function altPpnt(_cod,_acao){
	document.getElementById('frm_cod_ppnt').value = _cod;
	document.getElementById('acaoProposta').value = _acao;
	document.getElementById('proposta').action += '#proponente';
	document.getElementById('proposta').submit();
}

function corrigirPpnt(_cod,_acao){
	document.getElementById('frm_cod_ppnt').value = _cod;
	document.getElementById('acaoProposta').value = _acao;
	document.getElementById('proposta').action = 'corrigir.php?corrigir=sim#proponente';
	document.getElementById('proposta').submit();
}


function dtsPpnt(_cod,_acao){
	document.getElementById('frm_cod_ppnt').value = _cod;
	document.getElementById('acaoProposta').value = _acao;
	document.getElementById('proposta').action += '#proponente';
	document.getElementById('proposta').submit();
}

function atualizaValoresProposta(){
	somaCompraPpst=desformataMoeda(document.getElementById('somaCompraPpst').value);
	vlfin_mais_fgts=desformataMoeda(document.getElementById('somavVlIndFin').value);
	
	if(document.getElementById('resultado_vloperfgts')){
		vlfin_mais_fgts=(desformataMoeda(document.getElementById('resultado_vloperfgts').value))+vlfin_mais_fgts;
	}
	if(somaCompraPpst>0 && vlfin_mais_fgts>0){	
		
				if(vlfin_mais_fgts>somaCompraPpst){
					alert("Valor de financiamento mais o valor de FGTS não pode ser maior que a parcela individual da compra");		
					return false;
				}else{				
					 if(desformataMoeda(document.getElementById('vlavaliacao_imov').value)>0){
						 if(vlfin_mais_fgts>desformataMoeda(document.getElementById('vlavaliacao_imov').value)){
							alert("Valor de financiamento mais o valor de FGTS não pode ser maior que o valor de avaliação do imóvel");
							return false;
						}
				}
	}
	
	return true;
}



	/*
	var compraPpnt  = desformataMoeda(document.getElementById('vlcompra_ppnt').value);
	var entradaPpnt = desformataMoeda(document.getElementById('vlentrada_ppnt').value);
	var sinalPpnt   = desformataMoeda(document.getElementById('vlsinal_ppnt').value);
	var totalPpnt   = compraPpnt - entradaPpnt; // cálculo
	//document.getElementById('span_vlfinsol_ppnt').innerHTML = 'R$ '+formataMoeda(totalPpnt);
	//document.getElementById('vlfinsol_ppnt').value = formataMoeda(totalPpnt);
	var qtde = document.getElementById('qtde_tr_ppnt').value;
	var somaCompra  = compraPpnt;
	var somaEntrada = entradaPpnt;
	var somaSinal   = sinalPpnt;
	
	
	for(i=1; i<=qtde; i++){
		if(document.getElementById('cod_ppnt_'+i).value != document.getElementById('frm_cod_ppnt').value){
			somaCompra  += desformataMoeda(document.getElementById('vlcompra_ppnt_'+i).value);
			alert(document.getElementById('vlentrada_ppnt_'+i).value);
			somaEntrada += desformataMoeda(document.getElementById('vlentrada_ppnt_'+i).value);
			somaSinal   += desformataMoeda(document.getElementById('vlsinal_ppnt_'+i).value);
		}
	}
	somaTotal = somaCompra - somaEntrada; // cálculo total PPST
	document.getElementById('compra_total').innerHTML  = 'R$ '+formataMoeda(somaCompra);
	document.getElementById('entrada_total').innerHTML = 'R$ '+formataMoeda(somaEntrada);
	document.getElementById('sinal_total').innerHTML   = 'R$ '+formataMoeda(somaSinal);
	document.getElementById('valor_total').innerHTML   = 'R$ '+formataMoeda(somaTotal);
	*/
}

function resetCalculo(){
	var qtde = document.getElementById('qtde_tr_ppnt').value;
	var somaCompra  = 0;
	var somaEntrada = 0;
	var somaSinal   = 0;
	for(i=1; i<=qtde; i++){
		somaCompra  += desformataMoeda(document.getElementById('vlcompra_ppnt_'+i).value);
		somaEntrada += desformataMoeda(document.getElementById('vlentrada_ppnt_'+i).value);
		somaSinal   += desformataMoeda(document.getElementById('vlsinal_ppnt_'+i).value);
	}
	somaTotal = somaCompra - somaEntrada; // cálculo total PPST
	document.getElementById('compra_total').innerHTML  = 'R$ '+formataMoeda(somaCompra);
	document.getElementById('entrada_total').innerHTML = 'R$ '+formataMoeda(somaEntrada);
	document.getElementById('sinal_total').innerHTML   = 'R$ '+formataMoeda(somaSinal);
	document.getElementById('valor_total').innerHTML   = 'R$ '+formataMoeda(somaTotal);
}

function salvarBlocoProponentes(){
	if( document.getElementById('tbEditPpnt') ){
		if(!vVazio('cpf_ppnt')) if(!vCPF('cpf_ppnt', 'o CPF do Proponente')) return false;
		if(!vVazio('dtnascimento_ppnt')){
			if(!vData('dtnascimento_ppnt', 'a Data de Nascimento do Proponente')) return false;
			if(!vIdade('dtnascimento_ppnt',18,'a Idade do Proponente')) return false;
		}
		if(!vVazio('cep_ppnt')) if(!vCEP('cep_ppnt', 'o CEP do Proponente')) return false;
		if(!vVazio('telefone_ppnt_1')) if(!vTelefone('telefone_ppnt_1', 'o Telefone do Proponente')) return false;
		//if(!vSelect('tipotelefone_ppnt_1', '0', 'o Tipo do Telefone do Proponente')) return false;
		/*if(!vVazio('dtadmissaoemp_ppnt')){
			if(!vData('dtadmissaoemp_ppnt', 'a Data de Admissão do Proponente')) return false;
			if(comparaDatasId('dtadmissaoemp_ppnt','')==1){
				alert('a Data de Admissão do Proponente nao pode ser maior que a data atual');
				foco('dtadmissaoemp_ppnt');
				return false;
			}
			if(!vIdade('dtnascimento_ppnt',13,'a Idade do Proponente na Data de Admissão','dtadmissaoemp_ppnt')) return false;
		}*/
		//if(!vVazio('telefoneemp_ppnt')) if(!vTelefone('telefoneemp_ppnt', 'o Telefone da Empresa do Proponente')) return false;
		if(valorSelect('cod_estciv_ppnt')=='2' || valorSelect('cod_estciv_ppnt')=='99'){
			if(!vVazio('dtcasamento_ppcj')){
				if(!vData('dtcasamento_ppcj', 'a Data de Casamento do Proponente')) return false;
				if(comparaDatasId('dtcasamento_ppcj','')==1){
					alert('a Data de Casamento do Proponente nao pode ser maior que a data atual');
					foco('dtcasamento_ppcj');
					return false;
				}
				if(!vIdade('dtnascimento_ppnt',16,'a Idade do Proponente na Data de Casamento','dtcasamento_ppcj')) return false;
			}
			if(valorSelect('regimebens_ppcj')=='3' || valorSelect('regimebens_ppcj')=='5'){
				if(!vVazio('data_pcpa')){
					if(!vData('data_pcpa', 'a Data do Registro do Regime de Bens')) return false;
					if(comparaDatasId('data_pcpa','')==1){
						alert('a Data do Registro do Regime de Bens do Proponente nao pode ser maior que a data atual');
						foco('dtcasamento_ppcj');
						return false;
					}
					if(!vIdade('dtnascimento_ppnt',16,'a Idade do Proponente na Data do Registro do Regime de Bens','data_pcpa')) return false;
				}
			}
			if(!vVazio('dtrg_ppcj')){
				if(!vData('dtrg_ppcj', 'a Data de Emissão do RG do Cônjuge')) return false;
				if(comparaDatasId('data_pcpa','')==1){
					alert('a Data de Emissão do RG do Cônjuge nao pode ser maior que a data atual');
					foco('dtcasamento_ppcj');
					return false;
				}
			}
			if(!vVazio('cpf_pccj')) if(!vCPF('cpf_pccj', 'o CPF do Cônjuge')) return false;
		}
		if(!vVazio('cep_devsol')) if(!vCEP('cep_devsol', 'o CEP do Devedor Solidário')) return false;
		if(!vVazio('telefone_devsol')) if(!vTelefone('telefone_devsol', 'o Telefone Devedor Solidário')) return false;
		if(!vVazio('cpf_devsol')) if(!vCPF('cpf_devsol', 'o CPF do Devedor Solidário')) return false;
		if(!salvarCheckList('ckls_ppnt','div_add_ppnt','','proponente_cklst','proponente','do Proponente')) return false;
		if(!salvarCheckList('ckls_ppcj','div_add_ppnt','','conjuge_cklst','conjuge','do Cônjuge do Proponente')) return false;
	}
	return true;
}

function showDetsPpnt(_id){
	if(_id){
		if(document.getElementById('tr_dets_ppnt_'+_id).style.display=='none'){
			showTR('tr_dets_ppnt_'+_id);
			document.getElementById('flg_show_dets_ppnt_'+_id).value = 'S';
		}else{
			hideTR('tr_dets_ppnt_'+_id);
			document.getElementById('flg_show_dets_ppnt_'+_id).value = 'N';
		}
	}
}
function formatFloat2(_valor,_desc){
  _valor = String(_valor);
  var i;
  var output = '0';
  if(!_desc){ _desc = 10; }
  for(i=1; i<_desc; i++){ output += '0'; }
  var tmp = _valor.replace(',','');
  output += tmp.replace(/\./g,'');
  var tam = output.length;
  var tamdes = (tam-_desc);
  var p1  = output.substr(0,tamdes);
  var p2  = output.substr(tamdes,_desc);
  p1 = p1.replace(/^0*/g,"");
  p1 = (p1=='')?'0':p1;
  if(_desc > 0){ _valor = p1+','+p2; }else{ _valor = p1; }
  return _valor;
}

function formatMoeda2(_valor,_desc){
	if(!_desc){ _desc = 2; }
  _valor = formatFloat2(_valor,_desc);
  var i;
  var tmp = _valor.replace(/\./g,'');
  var partes = tmp.split(',');
  var output = '';
  var tres = 0;
  for(i = partes[0].length-1; i>=0; i--){
    if(tres==3){
      output = '.'+output;
      tres = 0;
    }
    output = partes[0].substr(i,1) + output;
    tres++;
  }
  if(partes[1]){
  	output += ','+partes[1];
  }
  return output;
}

function desformatMoeda2(_valor){
	return parseFloat(_valor.replace(/\./g,'').replace(/\,/g,'.')) * 100;
}

function existeobj2(nome){
 // alert(document.getElementById(nome));
  if( document.getElementById(nome) ){
   // alert('Existe!');
	return true;
  }else{
   // alert('Não existe');
    return false;
  }
}
function calcFinan(fgts)
{
	if(document.getElementById('vlentrada_ppnt').value!=''){	var entrada= 	desformatMoeda2(document.getElementById('vlentrada_ppnt').value);}else{ entrada=0;}
	if(document.getElementById('vlcompra_ppnt').value!=''){	var compra= 	desformatMoeda2(document.getElementById('vlcompra_ppnt').value);}else{ compra=0;}
	vl_fgts=desformatMoeda2(fgts);
	var finan= (compra-entrada)-vl_fgts;
	if(existeobj2('vlfinsol_ppnt')){document.getElementById('vlfinsol_ppnt').value=formatMoeda2(finan);}
	//if(existeobj('valor_finan2')){document.getElementById('valor_finan2').value=formatMoeda(compra-desconto);}
}
