function validaCategoria(oForm) {
	if (oForm.categoria.value == '') {
		alert('Informe o t�tulo da categoria');
		oForm.categoria.focus();
	} else if (oForm.descricao.value == '') {
		alert('Informe a descri��o da categoria');
		oForm.descricao.focus();
	} else {
		oForm.submit();
	}
}

function delCat(catName, uri) {
	if (window.confirm('Voc� deseja realmente remover a categoria \''+catName+'\'?')) {
		window.location='adm_categorias.php?k='+uri;
	}
}
