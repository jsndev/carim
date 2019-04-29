// FUNCOES de validação do formulario de PROPOSTA

function retornarAnalDoc(){
	document.getElementById('motivo').style.display='block';
	foco('motivo_retorno');
	return false;
}

function confirmarRetornar(_acao){
	if(!vTexto('motivo_retorno')) return false;
	if(confirm("Ao retornar para Análise Documental esta proposta volta para o Atentente.\nDeseja realmente que a proposta retorne para Análise Documental?")){
		document.getElementById('proposta').action += '#';
	  document.getElementById('acaoProposta').value = _acao;
	  document.getElementById('proposta').submit();
	}
 	return false;
}

function aprovarContrato(_acao){
	if(confirm("Deseja realmente Aprovar o Contrato desta proposta?")){
	 	document.getElementById('proposta').action += '#';
	  document.getElementById('acaoProposta').value = _acao;
	  document.getElementById('proposta').submit();
	}
 	return false;
}

function addEvento(_acao){
	document.getElementById('proposta').action += "#historico";
	if(validaProposta(false,false,false,false,true)){
    document.getElementById('acaoProposta').value = _acao;
    document.getElementById('proposta').submit();
  }
 	return false;
}

function parecerFinal(_acao){
	if(!vTexto('parecer')) return false;
	document.getElementById('proposta').action += "#";
  document.getElementById('acaoProposta').value = _acao;
  document.getElementById('proposta').submit();
 	return true;
}

function imprimirContrato(){
	return false;
}

function agendarAssinatura(){
	if(flgAprovPrevi=='S'){
		document.getElementById('agendamento').style.display='block';
		foco('prop_agendam');
	}else{
		alert('Aguardando a aprovação da proposta pela Previ!');
	}
	return false;
}

function confirmarAgendamento(_acao){
	if(validaAgendamento()){
	 	document.getElementById('proposta').action += '#';
	  document.getElementById('acaoProposta').value = _acao;
	  document.getElementById('proposta').submit();
	}
 	return false;
}

function validaAgendamento(){
	if(!vData('prop_agendam')) return false;
	var dataAgend  = document.getElementById('prop_agendam').value;
	var comp = comparaDatas(dataSistema,dataAgend);
	if(comp==0){
		alert('falha no cálculo de datas');
		return false;
	}else if(comp==1){
		alert('A Data de Agendamento de Assinatura não pode ser anterior à Data Atual!');
		foco('imov_dt_aval');
		return false;
	}
  return true;
}

