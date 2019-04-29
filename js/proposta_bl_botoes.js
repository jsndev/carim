function salvarProposta(_acao){
	if(!validaSalvarProposta())return false;
//alert ("entrou salvarProposta");
if(confirm('Deseja salvar a proposta?')){
		document.getElementById('proposta').action += '#';
		document.getElementById('acaoProposta').value = _acao;
		document.getElementById('proposta').submit();
	}
	return false;
}

function salvarProposta2(_acao){
	if(!validaSalvarProposta2())return false;
//alert ("entrou salvarProposta");
if(confirm('Deseja salvar a proposta?')){
		document.getElementById('proposta').action += '#';
		document.getElementById('acaoProposta').value = _acao;
		document.getElementById('proposta').submit();
	}
	return false;
}

function concluirProposta(_acao){
	if(!validaConcluirProposta()) return false;
	if(confirm('Deseja concluir a proposta?')){
		document.getElementById('proposta').action += '#';
		document.getElementById('acaoProposta').value = _acao;
		document.getElementById('proposta').submit();
	}
	return false;
}

function concluirProposta2(_acao){
	if(!validaConcluirProposta2()) return false;
	if(confirm('Deseja concluir a proposta?')){
		document.getElementById('proposta').action += '#';
		document.getElementById('acaoProposta').value = _acao;
		document.getElementById('proposta').submit();
	}
	return false;
}


function aprovarProposta(_acao){
	if(confirm('Deseja aprovar a proposta?')){
		document.getElementById('proposta').action += '#';
		document.getElementById('acaoProposta').value = _acao;
		document.getElementById('proposta').submit();
	}
	return false;
}
function ReprovProp(acao){
	if(confirm('Deseja cancelar a aprova��o da proposta?')){
		//document.getElementById('proposta').action += '#';
		document.proposta.acaoProposta.value = acao;
		document.getElementById('proposta').submit();
	}
	return false;
}

function CancPropostaPP(acao){
	if(confirm('Deseja realmente cancelar a Proposta?')){
		//document.getElementById('proposta').action += '#';
		document.proposta.acaoProposta.value = acao;
		document.getElementById('proposta').submit();
	}
	return false;
}

function retornarProposta(_acao){
	if(confirm('Deseja que a proposta retorne para o Atendente?')){
		document.getElementById('proposta').action += '#';
		document.getElementById('acaoProposta').value = _acao;
		document.getElementById('proposta').submit();
	}
	return false;
}

function aprovarImovel(_acao){
	if(document.getElementById('somaCompraPpst').value==0 || document.getElementById('somaCompraPpst').value=='0,00'){
	alert("Por favor informe o valor da parcela individual da compra!");
	return false;
	}
	
	if(document.getElementById('somavVlIndFin').value==0  || document.getElementById('somavVlIndFin').value=='0,00'){
	alert("Por favor informe o valor de financiamento!");
	return false;
	}
	

	if(!atualizaValoresProposta()) return false;
	if(!validaAprovacaoImov()) return false;
	if(!vData('dtaprovacao_imov','a Data de Aprova��o do Im�vel')) return false;
	carctImovel=0;
	msgErro="";
	if(document.proposta.tmbdspcndop_imov[0].checked){
		msgErro+="Im�vel definido como tombado, desapropriado ou condenado por �rg�o p�blico. Corrija este campo!\n";
		carctImovel=1;
	}
	
	if(document.proposta.incomb_imov[1].checked){
		msgErro+="Im�vel definido como combust�vel. Corrija este campo!\n";
		carctImovel=1;
	}
	
	if(document.proposta.ruralfav_imov[0].checked){
		msgErro+="Im�vel localizado em �rea rural ou favela. Corrija este campo!\n";
		carctImovel=1;
	}
	
	if(document.proposta.emconstr_imov[0].checked){
		msgErro+="Im�vel em constru��o. Corrija este campo!\n";
		carctImovel=1;
	}
	
	if(carctImovel==1){
		alert("Aprova��o do Imovel n�o � possivel devido a(s) seguinte(s) inconsist�ncia(s):\n" + msgErro);
		return false;
	}
	
	if(confirm('Deseja realmente aprovar o Im�vel?')){
		document.getElementById('acaoProposta').value = _acao;
		document.getElementById('proposta').action += '#imovel';
		document.getElementById('proposta').submit();
	}
}

function aprovarImovel2(_acao){
	
	if(document.getElementById('somavVlIndFin').value==0  || document.getElementById('somavVlIndFin').value=='0,00'){
	alert("Por favor informe o valor de financiamento!");
	return false;
	}
	
	
	//if(!atualizaValoresProposta()) return false;
	if(!validaAprovacaoImov()) return false;
	if(!vData('dtaprovacao_imov','a Data de Aprova��o do Im�vel')) return false;
	//alert('teste');
	carctImovel=0;
	msgErro="";
	if(document.proposta.tmbdspcndop_imov[0].checked){
		msgErro+="Im�vel definido como tombado, desapropriado ou condenado por �rg�o p�blico. Corrija este campo!\n";
		carctImovel=1;
	}
	
	if(document.proposta.incomb_imov[1].checked){
		msgErro+="Im�vel definido como combust�vel. Corrija este campo!\n";
		carctImovel=1;
	}
	
	if(document.proposta.ruralfav_imov[0].checked){
		msgErro+="Im�vel localizado em �rea rural ou favela. Corrija este campo!\n";
		carctImovel=1;
	}
	
	if(document.proposta.emconstr_imov[0].checked){
		msgErro+="Im�vel em constru��o. Corrija este campo!\n";
		carctImovel=1;
	}
	
	if(carctImovel==1){
		alert("Aprova��o do Imovel n�o � possivel devido a(s) seguinte(s) inconsist�ncia(s):\n" + msgErro);
		return false;
	}
	
	if(confirm('Deseja realmente aprovar o Im�vel?')){
		document.getElementById('acaoProposta').value = _acao;
		document.getElementById('proposta').action += '#imovel';
		document.getElementById('proposta').submit();
	}
}



function cancImovel(_acao){
	if(confirm('Deseja realmente cancelar a aprovac�o o Im�vel?')){
		document.getElementById('acaoProposta').value = _acao;
		//document.getElementById('proposta').action += '#imovel';
		document.getElementById('proposta').submit();
	}
}

function salvarEntrada(_acao){
		document.getElementById('acaoProposta').value = _acao;
		//document.getElementById('proposta').action += '#imovel';
		document.getElementById('proposta').submit();
}
function cancRegImovel(_acao){
	if(confirm('Deseja realmente cancelar a aprovac�o o Im�vel?')){
		document.proposta.acaoProposta.value = _acao;
		//document.getElementById('proposta').action += '#imovel';
		document.getElementById('proposta').submit();
	}
}

function cancelarAssinatura(_acao){
	if(confirm('Deseja realmente cancelar o Agendamento da Assinatura do Contrato?')){
		document.getElementById('acaoProposta').value = _acao;
		//document.getElementById('proposta').action += '#imovel';
		document.getElementById('proposta').submit();
	}
}

function concluirContrato(_acao){
	if(confirm('Deseja concluir a proposta?')){
		document.getElementById('proposta').action += '#';
		document.getElementById('acaoProposta').value = _acao;
		document.getElementById('proposta').submit();
	}
	return false;
}

function assinarContrato(_acao){
	if(!validaAssinaturaContrato()) return false;
	document.getElementById('acaoProposta').value = _acao;
	document.getElementById('proposta').action += '#';
	document.getElementById('proposta').submit();
}

function registrarImovel(_acao){
	if(!validaregistrarImovel()) return false;
	document.getElementById('acaoProposta').value = _acao;
	document.getElementById('proposta').action += '#';
	document.getElementById('proposta').submit();
}

function parecerFinal(_acao){
	if(!vTexto('txtparecer','o Parecer Final')) return false;
	document.getElementById('acaoProposta').value = _acao;
	document.getElementById('proposta').action += '#';
	document.getElementById('proposta').submit();
}

function validaAssinaturaContrato(){
	if(!vData('dtasscontrato_ppst','a Data da Assinatura do Contrato')) return false;
	var dtAprovacao   = document.getElementById('dtaprovacao_ppst').value;
	var dtAssContrato = document.getElementById('dtasscontrato_ppst').value;
	if( comparaDatas(dtAssContrato,dtAprovacao)==3 ){
		alert('A Data da Assinatura do Contrato deve ser maior que a Data de Aprova��o!');
  	foco('dtasscontrato_ppst');
  	return false;
	}
	if( comparaDatas(dtAssContrato,DATA_ATUAL)==1 ){
		//alert('A Data da Assinatura do Contrato deve ser menor ou igual a Data Atual!');
  	//foco('dtasscontrato_ppst');
  	return true;
	}
	return true;
}

function validaregistrarImovel(){
	if(!vData('dtokregistro_ppst',  'a Data de envio do Contrato')) return false;
	if(!vTexto('nomecartrgi_imov',  'o Nome do Cart�rio')) return false;
	//if(!vTexto('nrmatrgi_imov',     'o N�mero de Matr�cula')) return false;
	if(!vTexto('nrlivrgi_imov',     'o N�mero do Livro')) return false;
	if(!vTexto('nrfolhrgi_imov',    'o N�mero da Folha')) return false;
	if(!vTexto('nrrgcompvend_imov', 'o N�mero do Registro de Compra e Venda')) return false;
	if(!vTexto('nrrggar_imov',      'o N�mero do Registro de Garantia')) return false;
	var dtokRegistro  = document.getElementById('dtokregistro_ppst').value;
	var dtAprovacao   = document.getElementById('dtaprovacao_ppst').value;
	var dtAssContrato = document.getElementById('dtasscontrato_ppst').value;
	if( comparaDatas(dtokRegistro,dtAprovacao)!=1 ){
		alert('A Data de Envio deve ser maior que a Data de Aprova��o!');
  	foco('dtokregistro_ppst');
  	return false;
	}
	if( comparaDatas(dtokRegistro,DATA_ATUAL)==1 ){
		alert('A Data de Envio deve ser menor ou igual a Data Atual!');
  	foco('dtokregistro_ppst');
  	return true;
	}
	if( comparaDatas(dtokRegistro,dtAssContrato)==3 ){
		alert('A Data de Envio nao pode ser menor que a Data de Assinatura do Contrato!');
  	foco('dtokregistro_ppst');
  	return false;
	}
	return true;
}

