// FUNCOES de validação do formulario de CADASTRO

function cadastrar(){
  if(validaCadastro()){
   document.getElementById('formCadastroUser').submit();
  }
}

function validaCadastro(){
	 if(document.getElementById('cad_matricula')){
		if(!vMatricula('cad_matricula')) return false;
	 }
	 
	 if(document.getElementById('cad_nome')){
		if(!vTexto('cad_nome')) return false;
	}
  
  if(!vEmail('cad_email')) return false;
  
 if(document.getElementById('cad_condom_s')){
	  if(FLAG_PREVI){
			if(document.getElementById('cad_condom_s').checked){
				  var ppnts = getQtdFormsMatriculas();
				  for(i=1; i<=ppnts; i++){
					if(!vMatricula('matricula_ppnt_'+i)) return false;
					if(!vTexto('nome_ppnt_'+i)) return false;
					if(!vEmail('email_ppnt_'+i)) return false;
				  }
			}
	  }
 }
  return true;
}

function trocouTipoProposta(_obj){
	if(_obj.checked && _obj.value == 'S'){
		showTR('tr_qtde_ppnt');
		document.getElementById('cad_qtde_ppnt').selectedIndex=0;
		atualizaListaMatriculas();
	}else{
		hideTR('tr_qtde_ppnt');
		document.getElementById('cad_qtde_ppnt').selectedIndex=0;
		montaFormMatriculas(0);
	}
}


var tmpQtdePpnts = '';
function atualizaListaMatriculas(){
	var _obj  = document.getElementById('cad_qtde_ppnt');
	var opcao = _obj.selectedIndex;
	var qtde  = _obj.options[opcao].value;
	if(qtde != tmpQtdePpnts){
		montaFormMatriculas(qtde);
	}
}

function montaFormMatriculas(_qtd){
	var container = document.getElementById('td_matriculas_ppnt');
	if(_qtd=='0'){
		container.innerHTML = '';
		hideTR('tr_matriculas_ppnt');
		tmpQtdePpnts = 0;
	}else{
		var exist = getQtdFormsMatriculas();
		if(_qtd > exist){
			criaDivsMatriculas(_qtd - exist);
		}else if(_qtd < exist){
			excluiDivsMatriculas(exist - _qtd);
		}
		tmpQtdePpnts = _qtd;
		showTR('tr_matriculas_ppnt');
	}
}

function getQtdFormsMatriculas(){
	var container = document.getElementById('td_matriculas_ppnt');
	var divs = container.getElementsByTagName('div');
	return divs.length;
}

function criaDivsMatriculas(_qtd){
	for(i=1; i<=_qtd; i++){
		criaElementoMatricula();
	}
}

function excluiDivsMatriculas(_qtd){
	var container = document.getElementById('td_matriculas_ppnt');
	var tot = container.childNodes.length;
	var excluidos = _qtd;
	for(i=tot; i>=0; i--){
		if(excluidos > 0){
			container.removeChild(container.lastChild);
			excluidos--;
		}
	}
}


function criaElementoMatricula(){
	var container = document.getElementById('td_matriculas_ppnt');
	var i = (container.childNodes.length)+1;
	var obj_matric = document.createElement('div');
	obj_matric.className = "divMatricula";

	var novoHTML = '';
	novoHTML += "<table cellpadding=0 cellspacing=2 border=0>";
		// ---------------------------------------------------------------------------------
		novoHTML   += "<tr><td align='right' valign='top'>Matrícula:</td>";
		novoHTML   += "<td align='left'>";
		novoHTML   += "<input type='text' style='width:250px;' name='matricula_ppnt_"+i+"' id='matricula_ppnt_"+i+"' value='' onKeyDown='return teclasMatricula(this,event);' onKeyUp='return mascaraMatricula(this,event);' maxlength='12' />";
		novoHTML   += "</td></tr>";
		// ---------------------------------------------------------------------------------
		novoHTML   += "<tr><td align='right' valign='top'>Nome:</td>";
		novoHTML   += "<td align='left'>";
		novoHTML   += "<input type='text' style='width:250px;' name='nome_ppnt_"+i+"' id='nome_ppnt_"+i+"' value='' />";
		novoHTML   += "</td></tr>";
		// ---------------------------------------------------------------------------------
		novoHTML   += "<tr><td align='right' valign='top'>E-Mail:</td>";
		novoHTML   += "<td align='left'>";
		novoHTML   += "<input type='text' style='width:250px;' name='email_ppnt_"+i+"' id='email_ppnt_"+i+"' value='' />";
		novoHTML   += "</td></tr>";
		// ---------------------------------------------------------------------------------
	novoHTML += "</table>";
	
	obj_matric.innerHTML = novoHTML;
	container.appendChild(obj_matric);
}

