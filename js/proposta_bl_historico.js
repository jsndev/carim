function addEvento(_acao){
	if(!validaSalvarProposta2()){ return false; }
	else{
	document.getElementById('proposta').action += "#historico";
    document.getElementById('acaoProposta').value = _acao;
    document.getElementById('proposta').submit();
  }
}
function addEvento_2(_acao){
	if(!validaSalvarProposta2()){ return false; }
	else{
	document.getElementById('proposta').action += "#historico";
    document.getElementById('acaoProposta').value = _acao;
    document.getElementById('proposta').submit();
  }
}
