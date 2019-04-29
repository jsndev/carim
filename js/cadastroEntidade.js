/*
function getListaMunicipios(oUf) {
	var oMunicipios = document.getElementById('municipios');
	oMunicipios.options.length = 0;
	if (oUf.selectedIndex == 0) {
		oMunicipios.options[0] = new Option('Selecione o estado', '');
	} else {
		oMunicipios.options[0] = new Option('Aguarde... carregando lista', '');
		var oXml = new xml();
		oXml.file = 'ws/regiao.ws.php';
		oXml.xml  = 'uf='+oUf.options[oUf.selectedIndex].value+'&ac=getMunicipios';
		oXml.ret  = function () {
			var oData = oXml.xmlDoc;
			var aListaMunicipios = oData.getElementsByTagName('MUNICIPIO');
			if (aListaMunicipios.length > 0) {
				var sCodMunicipio = '';
				var sNomeMunicipio = '';
				oMunicipios.options.length = 0;
				oMunicipios.options[0] = new Option('Selecione o estado', '');
				for (var i = 0; i < aListaMunicipios.length; i++) {
					sCodMunicipio = nValue(aListaMunicipios[i], 'COD_MUNICIPIO');
					sNomeMunicipio = nValue(aListaMunicipios[i], 'NOME_MUNICIPIO');
					oMunicipios.options[(i+1)] = new Option(sNomeMunicipio, sCodMunicipio);
				}
			} else {
				oMunicipios.options.length = 0;
				oMunicipios.options[0] = new Option('Selecione o estado', '');
			}
		};
		oXml.load();
	}
}

function addMunicipioEntidade(oForm,strAc) {
	var oUf = document.getElementById('uf');
	var oMunicipios = document.getElementById('municipios');
	if (oUf.selectedIndex == 0 || oMunicipios.selectedIndex == 0) {
		alert('Selecione um município para inclusão');
	} else {
		doAction(oForm,strAc);
	}
}

window.onload = function() {
	if (document.getElementById('uf') && document.getElementById('municipios')) {
		var oUf = document.getElementById('uf');
		if (oUf.selectedIndex > 0) {
			getListaMunicipios(oUf);
		}
	}
}
*/
function validaEntidade(oForm) {
	if (oForm.nome.value == '') {
		alert('Informe o nome da entidade.');
		oForm.nome.focus();
	} else if (oForm.descricao.value == '') {
		alert('Informe a descrição da entidade.');
		oForm.descricao.focus();
	} else {
		oForm.submit();
	}
}


