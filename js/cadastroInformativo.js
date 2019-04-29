function validaInformativo(oForm,strAcAd) {
	if (oForm.titulo.value == '') {
		alert('Informe o título do informativo');
		oForm.titulo.focus();
	} else if (oForm.descricao.value == '') {
		alert('Informe a descrição do informativo');
		oForm.descricao.focus();
	} else if (oForm.categoria.selectedIndex == 0) {
		alert('Selecione a categoria deste informativo.');
		oForm.categoria.focus();
	} else {
		oForm.ac.value = strAcAd;
		oForm.submit();
	}
}

function validaInformativoAlt(oForm) {
	if (oForm.titulo.value == '') {
		alert('Informe o título do informativo');
		oForm.titulo.focus();
	} else if (oForm.descricao.value == '') {
		alert('Informe a descrição do informativo');
		oForm.descricao.focus();
	} else {
		oForm.submit();
	}
}

function delInformativo(infoName, uri) {
	if (window.confirm('Você deseja realmente remover o informativo \''+infoName+'\'?')) {
		window.location='adm_informativos.php?k='+uri;
	}
}

