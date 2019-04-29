// FUNCOES de valida��o do formulario de PROPOSTA

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
				alert('Informe o Valor da Presta��o ou o Prazo do Financiamento!');
				foco('sel_tipo_finan');
				return false;
		}
	}else{
		if(document.getElementById('dtapresdoc_ppst')){
			if(!vData('dtapresdoc_ppst','a Data de Apresenta��o dos Documentos')) return false;
			if(!salvarBlocoProposta()) return false;
		}
	}
	//if(!vFPositivo('valorseguro_ppst','o Valor do Seguro')) return false;
	if(!vFPositivo('valormanutencao_ppst','o Valor da Taxa Manuten��o')) return false;
	return true;
}

function obrigBlocoProposta2(){
	if(!FLG_PREVI){
	//	if(!vFPositivo('valorcompra_ppst','o Valor de Compra')) return false;
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
				alert('Informe o Valor da Presta��o ou o Prazo do Financiamento!');
				foco('sel_tipo_finan');
				return false;
		}
	}else{
		if(document.getElementById('dtapresdoc_ppst')){
			if(!vData('dtapresdoc_ppst','a Data de Apresenta��o dos Documentos')) return false;
			if(!salvarBlocoProposta()) return false;
		}
	}
	//if(!vFPositivo('valorseguro_ppst','o Valor do Seguro')) return false;
	if(!vFPositivo('valormanutencao_ppst','o Valor da Taxa Manuten��o')) return false;

	return true;
}


function obrigBlocoProponentes(){
	var qtdppnt = parseFloat(document.getElementById('qtde_tr_ppnt').value);
	if(qtdppnt < 1){
		alert('� necess�rio adicionar pelo menos um Proponente!');
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
	if(document.getElementById('flgboletoavalpago_ppst')){
		if(!vCheck('flgboletoavalpago_ppst','a Confirma��o de Pagamento')) return false;
		if(!vData('dtpagtoboleto_ppst','a Data de Pagamento')) return false;
		if(!salvarBlocoPagamento()) return false;
	}
	return true;
}

function obrigBlocointvQuitante(){
	if(document.getElementById('flg_intv')){
		if(!vCheck('flg_intv','se possui interveniente quitante')){ 
		return false;
		}
		else{
			if(document.getElementById('checkflg_intv').value=='S'){
				if(!vTexto('nome_intq','o Nome do Interveniente Quitante')) return false;
				if(!vTexto('nomeabr_intq','o Nome abreviado do Interveniente Quitante')) return false;
				if(!vSelect('cod_logr_intq','0','o Logradouro do Interveniente Quitante')) return false;
				if(!vTexto('endereco_intq','o Endere�o do Interveniente Quitante')) return false;
				if(!vTexto('nrendereco_intq','o N�mero do Endere�o do Interveniente Quitante')) return false;
				if(!vSelect('cod_bairro_intq','0','o Bairro do Interveniente Quitante')) return false;
				if(!vCEP('cep_intq','o CEP do Interveniente Quitante')) return false;
				if(!vSelect('cod_uf_intq','0','o Estado do Interveniente Quitante')) return false;
				if(!vSelect('cod_municipio_intq','0','o Munic�pio do Interveniente Quitante')) return false;
				if(!vTelefone('telefone_intq','o Telefone do Interveniente Quitante')) return false;
				if(!vCNPJ('cnpj_intq','o CNPJ do Interveniente Quitante')) return false;
				if(!vFPositivo('vlsaldodev_intq','o Valor do saldo devedor do Interveniente Quitante')) return false;
			}
		}
	}
	return true;
}

function obrigBlocoImovel(){
	if(document.getElementById('tbEditImov')){
		if(document.getElementById('dtaprovacao_imov')){
			if(!vSelect('cod_logr_imov','0',     'o Logradouro do Im�vel')) return false;
			if(!vTexto('endereco_imov',          'o Endere�o do Im�vel')) return false;
			if(!vTexto('nrendereco_imov',        'o N�mero do Im�vel')) return false;
			if(!vSelect('cod_uf_imov','0',       'o Estado do Im�vel')) return false;
			if(!vSelect('cod_municipio_imov','0','o Munic�pio do Im�vel')) return false;
			if(!vSelect('cod_bairro_imov','0',   'o Bairro do Im�vel')) return false;
			if(!vCEP('cep_imov',                 'o CEP do Im�vel')) return false;
			
			if(!vTexto('qtsala_imov',  'a quantidade de Salas do Im�vel')) return false;
			if(!vTexto('qtquarto_imov','a quantidade de Quartos do Im�vel')) return false;
			if(!vTexto('qtbanh_imov',  'a quantidade de Banheiros do Im�vel')) return false;
			if(!vTexto('qtgarag_imov', 'a quantidade de Garagens do Im�vel')) return false;
			if(!vTexto('qtpavim_imov', 'a quantidade de Pavimentos do Im�vel')) return false;
			if(!vTexto('qtdepemp_imov','a quantidade de Dep. Empreg. do Im�vel')) return false;
			
			if(!vFPositivo('area_imov',      'a �rea do Im�vel')) return false;
			if(!vSelect('tpimposto_imov','0','o Tipo de Imposto do Im�vel')) return false;
			if(!vSelect('tipo_imov','0',     'o Tipo do Im�vel')) return false;
			if(valorSelect('tipo_imov')=='E'){
				if(!vSelect('tipo_apartam','0',  'o Tipo de Apartamento')) return false;
				if(!vFPositivo('area_util',      'a �rea do �til Im�vel')) return false;
				if(!vFPositivo('area_total',     'a �rea do Total Im�vel')) return false;
			}
			if(!vSelect('tpconstrucao_imov','0','o Tipo de Constru��o')) return false;
			if(!vSelect('estconserv_imov','0',  'o Estado de Conserva��o do Im�vel')) return false;
			if(valorSelect('tipo_imov')=='E'){
				if(!vSelect('estconspred_imov','0','o  Estado de Conserva��o do Pr�dio')) return false;
			}
			//if(!vTexto('andar_imov',        'o Andar do Im�vel')) return false;
			//if(!vTexto('pavimento_imov',    'o Pavimento do Im�vel')) return false;
			if(!vTexto('vagas_garagem_imov','o N� de Vagas de Garagem')) return false;
			
			if(!vCheck('isolado_imov',   'se o Im�vel � Isolado')) return false;
			if(!vCheck('condominio_imov','se o Im�vel est� em Condom�nio')) return false;
			if(valorRadio('condominio_imov')=='S'){
				if(!vTexto('nome_condominio_imov',  'o Nome do Condom�nio')) return false;
				if(!vSelect('tpcondominio_imov','0','o Tipo do Condom�nio')) return false;
			}
			if(!vCheck('bloco_imov',     'se o Im�vel est� em Bloco')) return false;
			if(valorRadio('bloco_imov')=='S'){
				if(!vTexto('numero_bloco_imov',  'o N�mero do Bloco')) return false;
				if(!vTexto('edificio_bloco_imov','o Edif�cio do Bloco')) return false;
				if(!vTexto('conjunto_bloco_imov','o Conjunto do Bloco')) return false;
			}
			if(!vSelect('terreo_imov','0',   'se o Im�vel � T�rreo')) return false;
			if(!vSelect('tpmoradia_imov','0','o Tipo de Moradia')) return false;
			
			if(!vCheck('aquispaimae_imov',     'se a Aquisi��o do im�vel � de pai ou m�e')) return false;
			if(valorRadio('aquispaimae_imov')=='S'){
				if(!vCheck('possuiirmaos_imov',  'se Possui irm�os')) return false;
			}
			
			if(!vCheck('tmbdspcndop_imov','se o Im�vel � tombado, desapropriado ou condenado por �rg�o p�blico')) return false;
			if(!vCheck('incomb_imov',     'se o Im�vel � incombust�vel')) return false;
			if(!vCheck('ruralfav_imov',   'se o Im�vel � localizado em �rea rural ou favela')) return false;
			if(!vCheck('emconstr_imov',   'se o Im�vel est� em constru��o')) return false;
			
			var qtdVagas = getQtdFormsVagasGaragem();
			if( qtdVagas > 0){
				for(i=1; i<=qtdVagas; i++){
					if(!vCheck('tipo_vaga_imov_'+i,  'o Tipo de Vaga')) return false;
					if(!vCheck('local_vaga_imov_'+i, 'o Local da Vaga')) return false;
					if(!vFPositivo('area_util_vaga_imov_'+i,  'a �rea �til da Vaga')) return false;
					if(!vFPositivo('area_comum_vaga_imov_'+i, 'a �rea comum da Vaga')) return false;
					if(!vFPositivo('area_total_vaga_imov_'+i, 'a �rea total da Vaga')) return false;
					if(!vFPositivo('fracao_vaga_imov_'+i,     'a Fra��o ideal da Vaga')) return false;
					if(valorRadio('tipo_vaga_imov_'+i)=='I'){
						if(!vTexto('num_contrib_vaga_imov_'+i, 'o N�mero do Contribuinte')) return false;
						if(!vTexto('num_reg_vaga_imov_'+i,     'o N�mero de Registro')) return false;
						if(!vTexto('num_matr_vaga_imov_'+i,    'o N�mero de Matr�cula')) return false;
						if(!vTexto('num_oficio_vaga_imov_'+i,  'o N�mero do of�cio do registro de im�veis')) return false;
						if(!vTexto('local_oficio_vaga_imov_'+i,'o Local do of�cio do registro de im�veis')) return false;
					}
				}
			}
			
			if(!obrigBlocoImovelAval()) return false;
		}
	}
	return true;
}

function obrigBlocoImovelAval(){
	if(!validaAprovacaoImov()) return false;
	if(vVazio('dtaprovacao_imov')){
		alert('� necess�rio a aprova��o do im�vel');
		return false;
	}
	return true;
}

function obrigBlocoVendedor(){
	var qtdvend = parseFloat(document.getElementById('qtde_tr_vend').value);
	if(qtdvend < 1){
			alert('� necess�rio adicionar pelo menos um Vendedor!');
			document.location = '#vendedor';
			return false;
	}else{
		for(i=1; i<=qtdvend; i++){
			var msgErro = document.getElementById('msg_erro_vend_'+i).value;
			if(msgErro!=''){
				var userErro = document.getElementById('nome_vend_'+i).value;
				alert('Vendedor '+userErro+': '+msgErro);
				document.location = '#vendedor';
				return false;
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
	if(!validaCheckList('ckls_ppnt','div_add_ppnt','','proponente_cklst','proponente','do Proponente')) return false;
	if(!validaCheckList('ckls_ppcj','div_add_ppnt','','conjuge_cklst','conjuge','do C�njuge do Proponente')) return false;
	if(!validaCheckList('ckls_imov','','trava_clistimovel','imovel_cklst','imovel','do Im�vel')) return false;
	if(!validaCheckList('ckls_vnpj','div_add_vend','','vnpj_cklst','vendedor','do Vendedor')) return false;
	if(!validaCheckList('ckls_vnpf','div_add_vend','','vnpf_cklst','vendedor','do Vendedor')) return false;
	if(!validaCheckList('ckls_pfcj','div_add_vend','','vend_pfcj_cklst','vendedor','do C�njuge do Vendedor')) return false;
	if(!validaCheckList('ckls_advg','','checklistadvogado','advogado_cklst','advogado','do Advogado')) return false;
	return true;
}

