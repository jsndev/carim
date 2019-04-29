function validaDocumento(oForm) {
	if (oForm.nome.value == '') {
		alert('Informe o nome do documento.');
		oForm.nome.focus();
	} else if (oForm.descricao.value == '') {
		alert('Informe a descri��o do documento.');
		oForm.descricao.focus();
	} else if (oForm.validade.value == '') {
		alert('Informe a validade deste documento');
		oForm.validade.focus();
	} else if (!oForm.validade.value.match(/^[0-9]*$/gi)) {
		alert('Informe a validade deste documento utilizando apenas n�meros');
		oForm.validade.focus();
	} else if (parseInt(oForm.validade.value) < 1) {
		alert('Informe data de validade maior que 1 dia');
		oForm.validade.focus();
	} else {
		oForm.submit();
	}
}
