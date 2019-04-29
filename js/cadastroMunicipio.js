function pesquisarMunicipios() {
	var oForm = document.getElementById('frm1');
	if (oForm.uf.selectedIndex == 0) {
		alert('Selecione a UF para pesquisa.');
		oForm.uf.focus();
	} else if (oForm.municipio.value == '') {
		alert('Informe o nome ou parte do nome do munic�pio para pesquisa.');
		oForm.municipio.focus();
	} else if (oForm.municipio.value.length < 3) {
		alert('O nome do munic�pio para pesquisa deve possuir pelo menos 3 caracteres.');
		oForm.municipio.focus();
	} else {
		oForm.submit();
	}
}

function addDocumentoMunicipio(oForm,strAc) {
	if (document.getElementById('documento').value == '') {
		alert('Informe o documento');
		document.getElementById('documento').focus();
	} else if (document.getElementById('entidade').value == '') {
		alert('Informe a entidade do documento.');
		document.getElementById('entidade').focus();
	} else if (document.getElementById('validade').value == '') {
		alert('Informe a validade do documento.');
		document.getElementById('validade').focus();
	} else if (document.getElementById('validade').value.match(/[^0-9]/gi)) {
		alert('Utilize apenas n�meros.');
		document.getElementById('validade').focus();
	} else {
		doAction(oForm,strAc);
	}
}

function delDocumentoMunicipio(oForm,strAc,_ident) {
	if (confirm('Voc� tem certeza que deseja remover o documento '+_ident+ ' do munic�pio? Esta opera��o n�o poder� ser desfeita ap�s realizada.')) {
		doAction(oForm,strAc);
	}
}

function validaMunicipioAlt(oForm) {
	oForm.submit();
}

