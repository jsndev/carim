function trocouTipoImovel(_obj){
	var opcao = _obj.selectedIndex;
	var tipo = _obj.options[opcao].value;
	if(tipo == 'E'){
		showTR('tr_cons_pred');
		showTR('tr_tipo_apart');
		showTR('tr_area_util');
		showTR('tr_area_total');
		var qtd = document.getElementById('vagas_garagem_imov').value;
		montaFormVagasGaragem(qtd);
	}else{
		hideTR('tr_cons_pred');
		hideTR('tr_tipo_apart');
		hideTR('tr_area_util');
		hideTR('tr_area_total');
		colapseDiv('divVagasGaragemContainer');
		document.getElementById('estconspred_imov').selectedIndex=0;
	}
}

function trocouTipoVaga(_obj,_idx){
	if(_obj.value=='I' && _obj.checked){
		showTR('tr_num_contrib_vaga_imov_'+_idx);
		showTR('tr_num_reg_vaga_imov_'+_idx);
		showTR('tr_num_matr_vaga_imov_'+_idx);
		showTR('tr_num_oficio_vaga_imov_'+_idx);
		showTR('tr_local_oficio_vaga_imov_'+_idx);
	}else{
		hideTR('tr_num_contrib_vaga_imov_'+_idx);
		hideTR('tr_num_reg_vaga_imov_'+_idx);
		hideTR('tr_num_matr_vaga_imov_'+_idx);
		hideTR('tr_num_oficio_vaga_imov_'+_idx);
		hideTR('tr_local_oficio_vaga_imov_'+_idx);
	}
}


function trocouCondominioImov(_obj){
	if(_obj.value=='S' && _obj.checked){
		showTR('tr_imov_condom_nome');
		showTR('tr_imov_condom_tipo');
	}else{
		hideTR('tr_imov_condom_nome');
		hideTR('tr_imov_condom_tipo');
		document.getElementById('nome_condominio_imov').value = '';
		document.getElementById('tpcondominio_imov').options[0].selected = true;
	}
}

function trocouBlocoImov(_obj){
	if(_obj.value=='S' && _obj.checked){
		showTR('tr_imov_bloco_numero');
		showTR('tr_imov_bloco_edificio');
		showTR('tr_imov_bloco_conjunto');
	}else{
		hideTR('tr_imov_bloco_numero');
		hideTR('tr_imov_bloco_edificio');
		hideTR('tr_imov_bloco_conjunto');
	}
}

function trocouAquisPaiMae(_obj){
	if(_obj.value=='S' && _obj.checked){
		showTR('tr_imov_aquis_paimae');
	}else{
		hideTR('tr_imov_aquis_paimae');
	}
}

var tmpVagasGaragem = '';
function setTmpVagasGaragem(){
	tmpVagasGaragem = getQtdFormsVagasGaragem();
}

function atualizaListaVagasGaragem(){
	var qtd = document.getElementById('vagas_garagem_imov').value;
	if(qtd != tmpVagasGaragem){
		if(qtd >= 5){
			if(confirm('O número de vagas informado realmente está correto?')){
				montaFormVagasGaragem(qtd);
			}
		}else{
			montaFormVagasGaragem(qtd);
		}
	}
}

function montaFormVagasGaragem(_qtd){
	var container = document.getElementById('divVagasGaragemContainer');
	if(_qtd=='0'){
		if(tmpVagasGaragem != 0){
			if( confirm('Deseja excluir as vagas de garagem?') ){
				container.innerHTML = '';
				colapseDiv('divVagasGaragemContainer');
				tmpVagasGaragem = 0;
			}
		}
	}else{
		var exist = getQtdFormsVagasGaragem();
		if(_qtd > exist){
			criaDivsVagaGaragem(_qtd - exist);
		}else if(_qtd < exist){
			excluiDivsVagaGaragem(exist - _qtd);
		}
		expandDiv('divVagasGaragemContainer');
	}
}

function getQtdFormsVagasGaragem(){
	var container = document.getElementById('divVagasGaragemContainer');
	var divs = container.getElementsByTagName('div');
	return divs.length;
}

function criaDivsVagaGaragem(_qtd){
	for(i=1; i<=_qtd; i++){
		criaElementoVagaGaragem();
	}
}

function excluiDivsVagaGaragem(_qtd){
	var pergunta = (_qtd==1)?'Deseja excluir a última vaga?':'Deseja excluir as últimas '+_qtd+' vagas?';
	if(confirm(pergunta)){
		var container = document.getElementById('divVagasGaragemContainer');
		var tot = container.childNodes.length;
		var excluidos = _qtd;
		for(i=tot; i>=0; i--){
			if(excluidos > 0){
				container.removeChild(container.lastChild);
				excluidos--;
			}
		}
	}
}

function criaElementoVagaGaragem(){
	var container = document.getElementById('divVagasGaragemContainer');
	var i = (container.childNodes.length)+1;
	//var obrig = '<span class="obrig"> *</span>';
	var obrig = document.getElementById('tmpObrigVagas').value;
	var obj_vaga = document.createElement('div');
	obj_vaga.className = "divVagaGaragem";

	var novoHTML = '';
	novoHTML += "<b>Vaga "+i+"</b>";
	novoHTML += "<table cellpadding=0 cellspacing=5 border=0>";
	novoHTML += "<colgroup><col width='220' /><col /></colgroup>";
		// ---------------------------------------------------------------------------------
		novoHTML   += "<tr><td align='right' valign='top'>Tipo de Vaga:"+obrig+"</td>";
		novoHTML   += "<td align='left'>";
			for(v=0; v < aTipoVaga.length; v++){
				novoHTML += "<input type='radio' class='rd' name='tipo_vaga_imov_"+i+"' id='tipo_vaga_imov_"+i+"' value='"+aTipoVaga[v][0]+"' /> "+aTipoVaga[v][1]+" &nbsp;&nbsp;";
			}
		novoHTML   += "</td></tr>";
		// ---------------------------------------------------------------------------------
		novoHTML   += "<tr><td align='right' valign='top'>Local da Vaga:"+obrig+"</td>";
		novoHTML   += "<td align='left'>";
			for(v=0; v < aLocalVaga.length; v++){
				novoHTML += "<input type='radio' class='rd' name='local_vaga_imov_"+i+"' id='local_vaga_imov_"+i+"' value='"+aLocalVaga[v][0]+"' /> "+aLocalVaga[v][1]+" &nbsp;&nbsp;";
			}
		novoHTML   += "</td></tr>";
		// ---------------------------------------------------------------------------------
		novoHTML   += "<tr><td align='right' valign='top'>Área útil (m²):"+obrig+"</td>";
		novoHTML   += "<td align='left'>";
		novoHTML   += "<input type='text' style='width:80px;' name='area_util_vaga_imov_"+i+"' id='area_util_vaga_imov_"+i+"' value='' onKeyDown='return teclasInt(this,event);' onKeyUp='return mascaraMoeda(this,event,null,4);' maxlength='14' />";
		novoHTML   += "</td></tr>";
		// ---------------------------------------------------------------------------------
		novoHTML   += "<tr><td align='right' valign='top'>Área comum (m²):"+obrig+"</td>";
		novoHTML   += "<td align='left'>";
		novoHTML   += "<input type='text' style='width:80px;' name='area_comum_vaga_imov_"+i+"' id='area_comum_vaga_imov_"+i+"' value='' onKeyDown='return teclasInt(this,event);' onKeyUp='return mascaraMoeda(this,event,null,4);' maxlength='14' />";
		novoHTML   += "</td></tr>";
		// ---------------------------------------------------------------------------------
		novoHTML   += "<tr><td align='right' valign='top'>Área total (m²):"+obrig+"</td>";
		novoHTML   += "<td align='left'>";
		novoHTML   += "<input type='text' style='width:80px;' name='area_total_vaga_imov_"+i+"' id='area_total_vaga_imov_"+i+"' value='' onKeyDown='return teclasInt(this,event);' onKeyUp='return mascaraMoeda(this,event,null,4);' maxlength='14' />";
		novoHTML   += "</td></tr>";
		// ---------------------------------------------------------------------------------
		novoHTML   += "<tr><td align='right' valign='top'>Fração ideal (%):"+obrig+"</td>";
		novoHTML   += "<td align='left'>";
		novoHTML   += "<input type='text' style='width:80px;' name='fracao_vaga_imov_"+i+"' id='fracao_vaga_imov_"+i+"' value='' onKeyDown='return teclasInt(this,event);' onKeyUp='return mascaraMoeda(this,event,null,6);' maxlength='10' />";
		novoHTML   += "</td></tr>";
		// ---------------------------------------------------------------------------------
		novoHTML   += "<tr id='tr_num_contrib_vaga_imov_"+i+"' style='display:none;'><td align='right' valign='top'>Número do Contribuinte:"+obrig+"</td>";
		novoHTML   += "<td align='left'>";
		novoHTML   += "<input type='text' style='width:160px;' name='num_contrib_vaga_imov_"+i+"' id='num_contrib_vaga_imov_"+i+"' value='' maxlength='25' />";
		novoHTML   += "</td></tr>";
		// ---------------------------------------------------------------------------------
		novoHTML   += "<tr id='tr_num_reg_vaga_imov_"+i+"' style='display:none;'><td align='right' valign='top'>Número de Registro:"+obrig+"</td>";
		novoHTML   += "<td align='left'>";
		novoHTML   += "<input type='text' style='width:60px;' name='num_reg_vaga_imov_"+i+"' id='num_reg_vaga_imov_"+i+"' value='' maxlength='6' />";
		novoHTML   += "</td></tr>";
		// ---------------------------------------------------------------------------------
		novoHTML   += "<tr id='tr_num_matr_vaga_imov_"+i+"' style='display:none;'><td align='right' valign='top'>Número de Matrícula:"+obrig+"</td>";
		novoHTML   += "<td align='left'>";
		novoHTML   += "<input type='text' style='width:60px;' name='num_matr_vaga_imov_"+i+"' id='num_matr_vaga_imov_"+i+"' value='' maxlength='6' />";
		novoHTML   += "</td></tr>";
		// ---------------------------------------------------------------------------------
		novoHTML   += "<tr id='tr_num_oficio_vaga_imov_"+i+"' style='display:none;'><td align='right' valign='top'>Número do ofício do registro de imóveis:"+obrig+"</td>";
		novoHTML   += "<td align='left'>";
		novoHTML   += "<input type='text' style='width:60px;' name='num_oficio_vaga_imov_"+i+"' id='num_oficio_vaga_imov_"+i+"' value='' maxlength='6' />";
		novoHTML   += "</td></tr>";
		// ---------------------------------------------------------------------------------
		novoHTML   += "<tr id='tr_local_oficio_vaga_imov_"+i+"' style='display:none;'><td align='right' valign='top'>Local do ofício do registro de imóveis:"+obrig+"</td>";
		novoHTML   += "<td align='left'>";
		novoHTML   += "<input type='text' style='width:200px;' name='local_oficio_vaga_imov_"+i+"' id='local_oficio_vaga_imov_"+i+"' value='' maxlength='40' />";
		novoHTML   += "</td></tr>";
		// ---------------------------------------------------------------------------------
	novoHTML += "</table>";
	
	obj_vaga.innerHTML = novoHTML;
	container.appendChild(obj_vaga);
	
	var inputs = obj_vaga.getElementsByTagName('input');
	for(j=0; j<inputs.length; j++){
		if(inputs[j].id==('tipo_vaga_imov_'+i)){
			inputs[j].onclick = function(){ trocouTipoVaga(this,i); };
		}
	}
}

function salvarBlocoImovel(){
	if( document.getElementById('tbEditImov') ){
		if(!vVazio('cep_imov')) if(!vCEP('cep_imov','o CEP do Imóvel')) return false;
		if(!salvarBlocoImovelAval()) return false;
		if(!salvarCheckList('ckls_imov','','trava_clistimovel','imovel_cklst','imovel','do Imóvel')) return false;
	}
	return true;
}
	
function salvarBlocoImovelAval(){
	if( document.getElementById('tbEditAvalImov') ){
		if(document.getElementById('dtaprovacao_imov')){
			if(vFPositivo('vlavaliacao_imov','',true) || !vVazio('dtavaliacao_imov')){
				if(!vData('hidden_dtpagtoboleto_ppst','',true)){
					alert('É necessária a confirmação de pagamento do Boleto'); return false;
				}
				if(!vFPositivo('vlavaliacao_imov','o Valor da Avaliação do Imóvel')) return false;
				if(!vData('dtavaliacao_imov','a Data da Avaliação do Imóvel')) return false;
				if( document.getElementById('hidden_dtpagtoboleto_ppst') ){
					if( comparaDatasId('hidden_dtpagtoboleto_ppst','dtavaliacao_imov')==1){
						alert('a Data da Avaliação do Imóvel não pode ser menor que a data de Pagamento do Boleto');
						foco('dtavaliacao_imov');
						return false;
					}
				}
				if( comparaDatasId('dtavaliacao_imov','')==1){
					alert('a Data da Avaliação do Imóvel não pode ser maior que a data atual');
					foco('dtavaliacao_imov');
					return false;
				}
				if(!naoExcedeAvaliacao()) return false;
			}
		}
	}
	return true;
}

function validaAprovacaoImov(){
	if(!vFPositivo('vlavaliacao_imov','o Valor da Avaliação do Imóvel')) return false;
	if(!vData('dtavaliacao_imov','a Data da Avaliação do Imóvel')) return false;
	if(!vVazio('dtaprovacao_imov')){
		if( comparaDatasId('dtaprovacao_imov','dtavaliacao_imov')==3){
			alert('a Data da Aprovação do Imóvel não pode ser menor que a Data da Avaliação do Imóvel');
			foro('dtaprovacao_imov');
			return false;
		}
		if( comparaDatasId('dtaprovacao_imov','')==1){
			alert('a Data da Aprovação do Imóvel não pode ser maior que a data atual');
			foro('dtavaliacao_imov');
			return false;
		}
	}
	if(!naoExcedeAvaliacao()) return false;
	return true;
}

function naoExcedeAvaliacao(){
		if( document.getElementById('tbEditAvalImov') ){
		var qtdePpnt = document.getElementById('qtde_tr_ppnt').value;
		var somaCompra  = 0;
		for(i=1; i<=qtdePpnt; i++){
			somaCompra  += desformataMoeda(document.getElementById('vlcompra_ppnt_'+i).value);
		}
		var valorAval = desformataMoeda(document.getElementById('vlavaliacao_imov').value);
		
	//	if(somaCompra < valorAval){
			//alert('O valor da Compra não pode ser menor que o Valor de Avaliação do Imóvel');
			//foco('vlavaliacao_imov');
			//return false;
		//}
	}
	return true;
}

function verBtnAprovarImovel(){
	if(vData('dtaprovacao_imov','',true)){
		showDiv('btAprovarImov');
	}else{
		hideDiv('btAprovarImov');
	}
}

