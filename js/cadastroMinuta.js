function validaMinuta(oForm) {
	if (oForm.texto.value.length == 0) {
		alert('Favor informar o texto da minuta de contrato.');
		oForm.texto.focus();
	} else {
		oForm.submit();
	}
}

