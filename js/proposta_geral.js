// FUNCOES GERAIS de valida��o do formulario de PROPOSTA

function validaSalvarProposta(){
	if(!salvarBlocoProposta()) return false;
	
	if(!salvarBlocoPagamento()) return false;
	if(!salvarBlocoProponentes()) return false;
	if(!salvarBlocoImovel()) return false;
	//if(!salvarBlocoVendedores()) return false;
	if(!salvarBlocoAdvogado()) return false;
	
	return true;
}

function validaSalvarProposta2(){
	if(!salvarBlocoProposta()) return false;
	if(!salvarBlocoPagamento()) return false;
	if(!salvarBlocoProponentes()) return false;
	if(!salvarBlocoImovel()) return false;
//	if(!salvarBlocoVendedores()) return false;
	if(!salvarBlocoAdvogado()) return false;
	return true;
}

function validaConcluirProposta(){
	if(!obrigBlocointvQuitante()) return false;
	if(!atualizaValoresProposta())return false;
	if(!obrigBlocoProposta()) return false;
	if(!obrigBlocoProponentes()) return false;
	if(!obrigBlocoPagamento()) return false;
	if(!obrigBlocoImovel()) return false;
	if(!obrigBlocoVendedor()) return false;
	if(!obrigBlocoAssinatura()) return false;
	if(!obrigBlocoRemessa()) return false;
	if(!obrigBlocoHistorico()) return false;
	if(!obrigCheckLists()) return false;
	return true;
}
function validaConcluirProposta2(){
	if(!obrigBlocointvQuitante())return false;
//	if(!atualizaValoresProposta())return false;
	if(!obrigBlocoProposta2()) return false;
	if(!obrigBlocoProponentes()) return false;
	if(!obrigBlocoPagamento()) return false;
	if(!obrigBlocoImovel()) return false;
//	if(!obrigBlocoVendedor()) return false;
	if(!obrigBlocoAssinatura()) return false;
	if(!obrigBlocoRemessa()) return false;
	if(!obrigBlocoHistorico()) return false;
	if(!obrigCheckLists()) return false;
	return true;
}


function validaCheckList(_dCkLst,_dContainer,_campoHidden,_ancora,_ancora_pai,_titulo){
	var titulo = (_titulo)?_titulo:'';
	var visivel = true;
	if(_dContainer) if( document.getElementById(_dContainer) ){
		if( document.getElementById(_dContainer).style.display=='none' ) visivel=false;
	}
	if(visivel){
		if(objCKLST = document.getElementById(_dCkLst) ){
			var tudoOK = true;
			var inputs = objCKLST.getElementsByTagName('input');
			for(i=0; i<inputs.length; i++){
				if(inputs[i].type=='checkbox') if(!inputs[i].checked) tudoOK=false;
				if(inputs[i].type=='text') if(!vData(inputs[i].id,'',true)) tudoOK=false;
				if(!tudoOK){
					alert('Check List '+titulo+' incompleto!');
					if(_ancora) document.location = '#'+_ancora;
					return false;
				}
			}
		}
	}else{
		if(_campoHidden) if( document.getElementById(_campoHidden) ){
			if(document.getElementById(_campoHidden).value=='S'){
				alert('Check List '+titulo+' incompleto!');
				if(_ancora_pai) document.location = '#'+_ancora_pai;
				return false;
			}
		}
	}
	
	if(!salvarCheckList(_dCkLst,_dContainer,_campoHidden,_ancora,_ancora_pai,_titulo)) return false;

	return true;
}

function salvarCheckList(_dCkLst,_dContainer,_campoHidden,_ancora,_ancora_pai,_titulo){
	//alert ("entrou salvar checklist");
	var titulo = (_titulo)?_titulo:'';
	var visivel = true;
	if(_dContainer) if( document.getElementById(_dContainer) ){
		if( document.getElementById(_dContainer).style.display=='none' ) visivel=false;
	}
	if(visivel){
		var countTX = 0;
		var dt1 = dt2 = false;
		if(objCKLST = document.getElementById(_dCkLst) ){
			var tudoOK = true;
			var inputs = objCKLST.getElementsByTagName('input');
			for(i=0; i<inputs.length; i++){
				if(inputs[i].type=='text'){
					countTX++;
					if(countTX==1) dt1 = inputs[i];
					if(countTX==2) dt2 = inputs[i];
				}
			}
		
			if(dt1 && dt2){
				if( comparaDatas(dt1.value,DATA_ATUAL)==1 ){
					alert('Check List '+titulo+': A Data de Pedido deve ser menor ou igual a Data Atual!');
					foco(dt1.id);
					return false;
				}
				if( comparaDatas(dt2.value,DATA_ATUAL)==1 ){
					alert('Check List '+titulo+': A Data de Emiss�o deve ser menor ou igual a Data Atual!');
					foco(dt2.id);
					return false;
				}
				if( comparaDatas(dt1.value,dt2.value)==1 ){
					alert('Check List '+titulo+': A Data de Emiss�o deve ser maior ou igual a Data de Pedido!');
					foco(dt2.id);
					return false;
				}
			}
			
		}
	}
	return true;
}

function validaCamposCkLstPpnt(_ck1,_ck2,_dt1,_dt2){
	if(!document.getElementById(_ck1).checked ){
		document.getElementById(_ck2).checked = false;
		document.getElementById(_dt1).value="";
		document.getElementById(_dt2).value="";
		return true;
	}else{
		if( document.getElementById(_dt1) ) if(!vCheckBox(_dt1,'a Data de Pedido')) return false;
		if( document.getElementById(_dt2) ) if(!vCheckBox(_dt2,'a Data de Emiss�o')) return false;
	}
	return true;
}

function validaCamposCkLstAtnd(_ck1,_ck2,_dt1,_dt2){
	if(document.getElementById(_ck2).checked ){
		if( document.getElementById(_ck1) ) if(!vCheck(_ck1,'','A primeira confirma��o � necess�ria')) return false;
		if( document.getElementById(_dt1) ) if(!vData(_dt1,'a Data de Pedido')) return false;
		if( document.getElementById(_dt2) ) if(!vData(_dt2,'a Data de Emiss�o')) return false;
	}
	return true;
}

function validaCamposCkLstDtPedido(_ck1,_ck2,_dt1,_dt2){
	if(!vData(_dt1,'a Data de Pedido')) return false;
	if( comparaDatas(document.getElementById(_dt1).value, DATA_ATUAL)==1 ){
		alert('A Data de Pedido deve ser menor ou igual a Data Atual!');
		foco(_dt1);
		return false;
	}
	if(!vVazio(_dt2)){
		if( comparaDatas(document.getElementById(_dt1).value, document.getElementById(_dt2).value)==1 ){
			alert('A Data de Pedido deve ser menor ou igual a Data de Emiss�o!');
			foco(_dt1);
			return false;
		}
	}
	return true;
}

function validaCamposCkLstDtEmissao(_ck1,_ck2,_dt1,_dt2){
	if(!vData(_dt2,'a Data de Emiss�o')) return false;
	if(!vData(_dt1,'a Data de Pedido')) return false;
	if( comparaDatas(document.getElementById(_dt2).value, DATA_ATUAL)==1 ){
		alert('A Data de Emiss�o deve ser menor ou igual a Data Atual!');
		foco(_dt2);
		return false;
	}
	if( comparaDatas(document.getElementById(_dt1).value, document.getElementById(_dt2).value)==1 ){
		alert('A Data de Emiss�o deve ser maior ou igual a Data de Pedido!');
		foco(_dt2);
		return false;
	}
	return true;
}

