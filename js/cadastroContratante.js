function validaContratante(oForm) {
	var reg1 = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/i;
	if (oForm.nome.value == '') {
		alert('Informe o nome do contratante.');
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

function validaContratanteAlt(oForm) {
	if (oForm.nome.value == '') {
		alert('Informe o nome do contratante.');
		oForm.nome.focus();
	} else {
		oForm.submit();
	}
}

function delContratante(usName, uri) {
	if (window.confirm('Voc� deseja realmente remover o contratante \''+usName+'\'?')) {
		window.location='adm_contratantes.php?k='+uri;
	}
}

function altPwdContratante(usName, uri) {
	if (window.confirm('A senha do contratante \''+usName+'\' ser� alterada e enviada para seu respectivo endere�o eletr�nico.\n\nTem certeza que deseja prosseguir?')) {
		window.location='adm_contratantes.php?k='+uri;
	}
}

