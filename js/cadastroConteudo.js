function validaConteudo(oForm,strAc) {
	if (oForm.titulo.value == '') {
		alert('Informe o título do conteúdo');
		oForm.titulo.focus();
	} else if (oForm.descricao.value == '') {
		alert('Informe a descrição do conteúdo');
		oForm.descricao.focus();
	} else if (oForm.tipoconteudo[0].checked == true && tinyMCE.getContent() == '') {
		alert('Informe o texto do conteúdo');
	} else if (oForm.tipoconteudo[1].checked == true && oForm.imagem.value == '') {
		alert(oForm.imagem.value);
		alert('Informe o arquivo de imagem');
		oForm.imagem.focus();
	} else if (oForm.tipoconteudo[1].checked == true && oForm.imagem.value.match(/(\.gif|\.jpg|\.png)$/i) == false) {
		alert('O arquivo de imagem deve ser GIF, JPG ou PNG');
		oForm.imagem.focus();
	} else if (oForm.tipoconteudo[1].checked == true && oForm.legendaimagem.value == '') {
		alert('Informe a legenda da imagem');
		oForm.legendaimagem.focus();
	} else if (oForm.tipoconteudo[2].checked == true && oForm.arquivo.value == '') {
		alert('Informe o arquivo');
		oForm.arquivo.focus();
	} else if (oForm.tipoconteudo[2].checked == true && oForm.legendaarquivo.value == '') {
		alert('Informe a legenda do arquivo');
		oForm.legendaarquivo.focus();
	} else {
		doAction(oForm,strAc);
	}
}

function validaConteudoAlt(oForm,strAc) {
	if (oForm.titulo.value == '') {
		alert('Informe o título do conteúdo');
		oForm.titulo.focus();
	} else if (oForm.descricao.value == '') {
		alert('Informe a descrição do conteúdo');
		oForm.descricao.focus();
	} else if (oForm.tipoconteudo[0].checked == true && tinyMCE.getContent() == '') {
		alert('Informe o texto do conteúdo');
	} else if (oForm.tipoconteudo[1].checked == true && oForm.imagem.value.match(/(\.gif|\.jpg|\.png)$/i) == false && oForm.imagem.value != '') {
		alert('O arquivo de imagem deve ser GIF, JPG ou PNG');
		oForm.imagem.focus();
	} else if (oForm.tipoconteudo[1].checked == true && oForm.legendaimagem.value == '') {
		alert('Informe a legenda da imagem');
		oForm.legendaimagem.focus();
	} else if (oForm.tipoconteudo[2].checked == true && oForm.legendaarquivo.value == '') {
		alert('Informe a legenda do arquivo');
		oForm.legendaarquivo.focus();
	} else {
		doAction(oForm,strAc);
	}
}

function delConteudo(contName, uri) {
	if (window.confirm('Você deseja realmente remover o conteúdo \''+contName+'\'?')) {
		window.location='adm_conteudos.php?k='+uri;
	}
}


function setContentType(_type) {
	var oTrs = document.getElementsByTagName('tr');
	var regBox = '';
	
	if (_type == '1') {
		document.getElementById('editorContainer').style.display = 'block';
	} else {
		document.getElementById('editorContainer').style.display = 'none';
	}

	if (oTrs.length > 0) {
		for (var j=0; j<oTrs.length; j++) {
			if (oTrs[j].id.match(/boxtipo_/)) {
				oTrs[j].className = 'hide';
			}
			regBox = new RegExp('boxtipo_'+_type);
			if (regBox.test(oTrs[j].id)) {
				oTrs[j].className = 'trow';
			}
		}
	}
}

function updateContentPreview() {
	var oForm = document.getElementById('frm1');
	var oPreview = document.getElementById('imagemPreview');
	if (oForm.imagem.value.match(/(\.gif|\.jpg|\.png)$/i)) {
		oPreview.innerHTML = '';
	} else {
		oPreview.innerHTML = '<div style="vertical-align: middle; padding: 5px; margin: 5px; border: 1px solid #CA1D1D; background-color: #EBCACA; color: #CA1D1D; font-weight: bold;"><img src="images/mensagens/erro.gif" alt="Erro" style="vertical-align: middle;" />A imagem deve ser um arquivo JPG, GIF ou PNG</div>';
	}
}

