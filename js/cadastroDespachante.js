function validaDespachante(oForm) {
	var reg1 = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/i;
	if (oForm.nome.value == '') {
		alert('Informe o nome do Despachante.');
		oForm.nome.focus();
	} else if (oForm.email.value == '') {
		alert('Informe o endere�o de e-mail');
		oForm.email.focus();
	} else if (!oForm.email.value.match(reg1)) {
		alert('O email informado � inv�lido.');
		oForm.email.focus();
	} else {
		oForm.submit();
	}
}

function validaDespachanteAlt(oForm) {
	if (oForm.nome.value == '') {
		alert('Informe o nome do despachante.');
		oForm.nome.focus();
	} else {
		oForm.submit();
	}
}

function delDespachante(usName, uri) {
	if (window.confirm('Voc� deseja realmente remover o despachante \''+usName+'\'?')) {
		window.location='adm_despachantes.php?k='+uri;
	}
}

function altPwdDespachante(usName, uri) {
	if (window.confirm('A senha do despachante \''+usName+'\' ser� alterada e enviada para seu respectivo endere�o eletr�nico.\n\nTem certeza que deseja prosseguir?')) {
		window.location='adm_despachantes.php?k='+uri;
	}
}

function addRegiaoDespachante(oForm,strAc) {
	var oRegioes = document.getElementById('regiao');
	if (oRegioes.selectedIndex == 0) {
		alert('Selecione uma regi�o para inclus�o');
	} else {
		doAction(oForm,strAc);
	}
}
