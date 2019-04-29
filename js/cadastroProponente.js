function validaProponente(oForm) {
	var reg1 = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/i;
	if (oForm.nome.value == '') {
		alert('Informe o nome do proponente.');
		oForm.nome.focus();
	} else if (oForm.email.value == '') {
		alert('Informe o endereço de e-mail');
		oForm.email.focus();
	} else if (!oForm.email.value.match(reg1)) {
		alert('O email informado é inválido.');
		oForm.email.focus();
	} else {
		oForm.submit();
	}
}

function validaProponenteAlt(oForm) {
	if (oForm.nome.value == '') {
		alert('Informe o nome do proponente.');
		oForm.nome.focus();
	} else {
		oForm.submit();
	}
}

function delAtendente(usName, uri) {
	if (window.confirm('Você deseja realmente remover o proponente \''+usName+'\'?')) {
		window.location='adm_proponentes.php?k='+uri;
	}
}

function altPwdAtendente(usName, uri) {
	if (window.confirm('A senha do proponente \''+usName+'\' será alterada e enviada para seu respectivo endereço eletrônico.\n\nTem certeza que deseja prosseguir?')) {
		window.location='adm_proponentes.php?k='+uri;
	}
}

