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
				alert('Informe o Valor da Prestação ou o Prazo do Financiamento!');
				foco('sel_tipo_finan');
				return false;
		}
	}else{
		if(document.getElementById('dtapresdoc_ppst')){
			if(!vData('dtapresdoc_ppst','a Data de Apresentação dos Documentos')) return false;
			if(!salvarBlocoProposta()) return false;
		}
	}
	//if(!vFPositivo('valorseguro_ppst','o Valor do Seguro')) return false;
	if(!vFPositivo('valormanutencao_ppst','o Valor da Taxa Manutenção')) return false;
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
				alert('Informe o Valor da Prestação ou o Prazo do Financiamento!');
				foco('sel_tipo_finan');
				return false;
		}
	}else{
		if(document.getElementById('dtapresdoc_ppst')){
			if(!vData('dtapresdoc_ppst','a Data de Apresentação dos Documentos')) return false;
			if(!salvarBlocoProposta()) return false;
		}
	}
	//if(!vFPositivo('valorseguro_ppst','o Valor do Seguro')) return false;
	if(!vFPositivo('valormanutencao_ppst','o Valor da Taxa Manutenção')) return false;

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
	if(document.getElementById('flgboletoavalpago_ppst')){
		if(!vCheck('flgboletoavalpago_ppst','a Confirmação de Pagamento')) return false;
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
				if(!vTexto('endereco_intq','o Endereço do Interveniente Quitante')) return false;
				if(!vTexto('nrendereco_intq','o Número do Endereço do Interveniente Quitante')) return false;
				if(!vSelect('cod_bairro_intq','0','o Bairro do Interveniente Quitante')) return false;
				if(!vCEP('cep_intq','o CEP do Interveniente Quitante')) return false;
				if(!vSelect('cod_uf_intq','0','o Estado do Interveniente Quitante')) return false;
				if(!vSelect('cod_municipio_intq','0','o Município do Interveniente Quitante')) return false;
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
			if(!vSelect('cod_logr_imov','0',     'o Logradouro do Imóvel')) return false;
			if(!vTexto('endereco_imov',          'o Endereço do Imóvel')) return false;
			if(!vTexto('nrendereco_imov',        'o Número do Imóvel')) return false;
			if(!vSelect('cod_uf_imov','0',       'o Estado do Imóvel')) return false;
			if(!vSelect('cod_municipio_imov','0','o Município do Imóvel')) return false;
			if(!vSelect('cod_bairro_imov','0',   'o Bairro do Imóvel')) return false;
			if(!vCEP('cep_imov',                 'o CEP do Imóvel')) return false;
			
			if(!vTexto('qtsala_imov',  'a quantidade de Salas do Imóvel')) return false;
			if(!vTexto('qtquarto_imov','a quantidade de Quartos do Imóvel')) return false;
			if(!vTexto('qtbanh_imov',  'a quantidade de Banheiros do Imóvel')) return false;
			if(!vTexto('qtgarag_imov', 'a quantidade de Garagens do Imóvel')) return false;
			if(!vTexto('qtpavim_imov', 'a quantidade de Pavimentos do Imóvel')) return false;
			if(!vTexto('qtdepemp_imov','a quantidade de Dep. Empreg. do Imóvel')) return false;
			
			if(!vFPositivo('area_imov',      'a Área do Imóvel')) return false;
			if(!vSelect('tpimposto_imov','0','o Tipo de Imposto do Imóvel')) return false;
			if(!vSelect('tipo_imov','0',     'o Tipo do Imóvel')) return false;
			if(valorSelect('tipo_imov')=='E'){
				if(!vSelect('tipo_apartam','0',  'o Tipo de Apartamento')) return false;
				if(!vFPositivo('area_util',      'a Área do Útil Imóvel')) return false;
				if(!vFPositivo('area_total',     'a Área do Total Imóvel')) return false;
			}
			if(!vSelect('tpconstrucao_imov','0','o Tipo de Construção')) return false;
			if(!vSelect('estconserv_imov','0',  'o Estado de Conservação do Imóvel')) return false;
			if(valorSelect('tipo_imov')=='E'){
				if(!vSelect('estconspred_imov','0','o  Estado de Conservação do Prédio')) return false;
			}
			//if(!vTexto('andar_imov',        'o Andar do Imóvel')) return false;
			//if(!vTexto('pavimento_imov',    'o Pavimento do Imóvel')) return false;
			if(!vTexto('vagas_garagem_imov','o N° de Vagas de Garagem')) return false;
			
			if(!vCheck('isolado_imov',   'se o Imóvel é Isolado')) return false;
			if(!vCheck('condominio_imov','se o Imóvel está em Condomínio')) return false;
			if(valorRadio('condominio_imov')=='S'){
				if(!vTexto('nome_condominio_imov',  'o Nome do Condomínio')) return false;
				if(!vSelect('tpcondominio_imov','0','o Tipo do Condomínio')) return false;
			}
			if(!vCheck('bloco_imov',     'se o Imóvel está em Bloco')) return false;
			if(valorRadio('bloco_imov')=='S'){
				if(!vTexto('numero_bloco_imov',  'o Número do Bloco')) return false;
				if(!vTexto('edificio_bloco_imov','o Edifício do Bloco')) return false;
				if(!vTexto('conjunto_bloco_imov','o Conjunto do Bloco')) return false;
			}
			if(!vSelect('terreo_imov','0',   'se o Imóvel é Térreo')) return false;
			if(!vSelect('tpmoradia_imov','0','o Tipo de Moradia')) return false;
			
			if(!vCheck('aquispaimae_imov',     'se a Aquisição do imóvel é de pai ou mãe')) return false;
			if(valorRadio('aquispaimae_imov')=='S'){
				if(!vCheck('possuiirmaos_imov',  'se Possui irmãos')) return false;
			}
			
			if(!vCheck('tmbdspcndop_imov','se o Imóvel é tombado, desapropriado ou condenado por órgão público')) return false;
			if(!vCheck('incomb_imov',     'se o Imóvel é incombustível')) return false;
			if(!vCheck('ruralfav_imov',   'se o Imóvel é localizado em área rural ou favela')) return false;
			if(!vCheck('emconstr_imov',   'se o Imóvel está em construção')) return false;
			
			var qtdVagas = getQtdFormsVagasGaragem();
			if( qtdVagas > 0){
				for(i=1; i<=qtdVagas; i++){
					if(!vCheck('tipo_vaga_imov_'+i,  'o Tipo de Vaga')) return false;
					if(!vCheck('local_vaga_imov_'+i, 'o Local da Vaga')) return false;
					if(!vFPositivo('area_util_vaga_imov_'+i,  'a Área útil da Vaga')) return false;
					if(!vFPositivo('area_comum_vaga_imov_'+i, 'a Área comum da Vaga')) return false;
					if(!vFPositivo('area_total_vaga_imov_'+i, 'a Área total da Vaga')) return false;
					if(!vFPositivo('fracao_vaga_imov_'+i,     'a Fração ideal da Vaga')) return false;
					if(valorRadio('tipo_vaga_imov_'+i)=='I'){
						if(!vTexto('num_contrib_vaga_imov_'+i, 'o Número do Contribuinte')) return false;
						if(!vTexto('num_reg_vaga_imov_'+i,     'o Número de Registro')) return false;
						if(!vTexto('num_matr_vaga_imov_'+i,    'o Número de Matrícula')) return false;
						if(!vTexto('num_oficio_vaga_imov_'+i,  'o Número do ofício do registro de imóveis')) return false;
						if(!vTexto('local_oficio_vaga_imov_'+i,'o Local do ofício do registro de imóveis')) return false;
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
		alert('É necessário a aprovação do imóvel');
		return false;
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
	if(!validaCheckList('ckls_ppcj','div_add_ppnt','','conjuge_cklst','conjuge','do Cônjuge do Proponente')) return false;
	if(!validaCheckList('ckls_imov','','trava_clistimovel','imovel_cklst','imovel','do Imóvel')) return false;
	if(!validaCheckList('ckls_vnpj','div_add_vend','','vnpj_cklst','vendedor','do Vendedor')) return false;
	if(!validaCheckList('ckls_vnpf','div_add_vend','','vnpf_cklst','vendedor','do Vendedor')) return false;
	if(!validaCheckList('ckls_pfcj','div_add_vend','','vend_pfcj_cklst','vendedor','do Cônjuge do Vendedor')) return false;
	if(!validaCheckList('ckls_advg','','checklistadvogado','advogado_cklst','advogado','do Advogado')) return false;
	return true;
}

