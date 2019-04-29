////////// Funções do menu ////////////
function menuIn(_obj) {
	var i = 0;
	for (i=0; i<document.getElementById('mainMenu').getElementsByTagName('td').length; i++) {
		if (document.getElementById('mainMenu').getElementsByTagName('td')[i].id.match(/^mn_/) ) {
			var imagemBotao = document.getElementById('mainMenu').getElementsByTagName('td')[i].id.substr(3);
			imagemBotao = imagemBotao+'_out.gif';
			document.getElementById('mainMenu').getElementsByTagName('td')[i].getElementsByTagName('img')[0].src = 'images/buttons/bot_'+imagemBotao;
			hideDiv(document.getElementById('mainMenu').getElementsByTagName('td')[i].id+'_sub');
		}
	}
	if (_obj) {
		_obj.getElementsByTagName('img')[0].src = 'images/buttons/bot_'+_obj.id.substr(3)+'_over.gif'
		moveDiv(_obj.id+'_sub', findObjPosX(_obj.id), (findObjPosY(_obj.id)+22));
		showDiv(_obj.id+'_sub');
		showDiv('menuMask');
	} else {
		hideDiv('menuMask');
	}
}

function subMenuIn(_obj) {
	_obj.className = 'subMenuItemIn cursorMao cBlue';
}

function subMenuOut(_obj) {
	_obj.className = 'subMenuItemOut cursorMao cBlue';
}

function showDiv(divName) {
	var divRef = '';
	if (divRev = document.getElementById(divName)) {
		divRev.style.visibility='visible';
		//selectboxTrick('f');
	}
}

function hideDiv(divName) {
	var divRef = '';
	if (divRev = document.getElementById(divName)) {
		divRev.style.visibility='hidden';
	}
}

function expandDiv(divName) {
	var divRef = '';
	if (divRev = document.getElementById(divName)) {
		divRev.style.display='block';
	}
}

function colapseDiv(divName) {
	var divRef = '';
	if (divRev = document.getElementById(divName)) {
		divRev.style.display='none';
	}
}

function hideTR(_id){
	if(document.getElementById(_id)){
	document.getElementById(_id).style.display = 'none';
	}
}

function showTR(_id){
	if(document.getElementById(_id)){
		if(navigator.appName=='Netscape'){
			document.getElementById(_id).style.display = 'table-row';
		}else{
			document.getElementById(_id).style.display = 'block';
		}
	}
}

function hideInLine(_id){
	document.getElementById(_id).style.display = 'none';
}

function showInLine(_id){
	document.getElementById(_id).style.display = 'inline';
}

function resetObjSelect(objId){
	var objRef = '';
	if (objRef = document.getElementById(objId)) {
		if(objRef.type=='select-one'){
			objRef.options[0].selected=true;
		}
	}
}

function moveDiv(_divName, _l, _t) {
	var div = '';
	if (div = document.getElementById(_divName)) {
		if (_l) {
			div.style.left = _l+'px';
		}
		if (_t) {
			div.style.top = _t+'px';
		}
	}
}

function clearInput(_objId) {
	if(inputText = document.getElementById(_objId)) {
		inputText.value = '';
	}
}

function goPage(pageUrl) {
	if (pageUrl != '#') {
		window.location = pageUrl;
	}
}

function findObjPosX(divName) {
	var divParent = '';
	var offsetLeft = 0;
	if (divParent = document.getElementById(divName)) {
		while (divParent) {
			offsetLeft += divParent.offsetLeft;
			divParent = divParent.offsetParent;
		}
	}
	return offsetLeft;
}

function findObjPosY(divName) {
	var divParent = '';
	var offsetTop = 0;
	if (divParent = document.getElementById(divName)) {
		while (divParent) {
			offsetTop += divParent.offsetTop;
			divParent = divParent.offsetParent;
		}
	}
	return offsetTop;
}

function loadCont(_uri) {
	document.getElementById('iFrCont').src = 'conteudo.php?k='+_uri;
}

window.onload = initPage;
function initPage() {  }

function doAction(oForm,strAct) {
	oForm.ac.value = strAct;
	oForm.submit();
}

function maskCurrency(oField) {
	var tmpValue = (parseFloat(oField.value.replace(/[^\d]/g,'')))/100;
	if (isNaN(tmpValue)) {
		tmpValue = 0.00;
	}
	oField.value = tmpValue.format(2,',','.');
}

/* Funcoes de PAD e FORMAT */
String.PAD_LEFT  = 0;
String.PAD_RIGHT = 1;
String.PAD_BOTH  = 2;

String.prototype.pad = function(size, pad, side) {
	var str = this, append = "", size = (size - str.length);
	var pad = ((pad != null) ? pad : " ");
	if ((typeof size != "number") || ((typeof pad != "string") || (pad == ""))) {
		throw new Error("Wrong parameters for String.pad() method.");
	}
	if (side == String.PAD_BOTH) {
		str = str.pad((Math.floor(size / 2) + str.length), pad, String.PAD_LEFT);
		return str.pad((Math.ceil(size / 2) + str.length), pad, String.PAD_RIGHT);
	}
	while ((size -= pad.length) > 0) {
		append += pad;
	}
	append += pad.substr(0, (size + pad.length));
	return ((side == String.PAD_LEFT) ? append.concat(str) : str.concat(append));
}

Number.prototype.format = function(d_len, d_pt, t_pt) {
	var d_len = d_len || 0;
	var d_pt = d_pt || ".";
	var t_pt = t_pt || ",";
	if ((typeof d_len != "number") || (typeof d_pt != "string") || (typeof t_pt != "string")) {
		throw new Error("wrong parameters for method 'String.pad()'.");
	}
	var integer = "", decimal = "";
	var n = new String(this).split(/\./), i_len = n[0].length, i = 0;
	if (d_len > 0) {
		n[1] = (typeof n[1] != "undefined") ? n[1].substr(0, d_len) : "";
		decimal = d_pt.concat(n[1].pad(d_len, "0", String.PAD_RIGHT));
	}
	while (i_len > 0) {
		if ((++i % 3 == 1) && (i_len != n[0].length)) {
			integer = t_pt.concat(integer);
		}
		integer = n[0].substr(--i_len, 1).concat(integer);
	}
	return (integer + decimal);
}

function openChat(_tipo,_chat){
	var th = 525; // altura
	var tw = 370; // largura
	var tl = 300; // esquerda
	var tt = 50;  // topo
	var pagina = '';
	switch(_tipo){
		case 1:
			pagina = 'prop_chat.php';
			break;
		case 2:
			pagina = 'atend_chat.php';
			break;
		case 3:
			pagina = 'hist_chat.php?codchat='+_chat;
			break;
		default:
			pagina = 'other_chat_login.php';
	}
	window.open(pagina,'chatcontrathos', 'fullscreen=no, status=no, scrollbars=no, location=no, toolbar=no, menubar=no, resizable=no,  titlebar=no, left='+tl+', top='+tt+', height='+th+', width='+tw);
	// window.open(pagina,'chatcontrathos', 'fullscreen=no, status=no, scrollbars=no, location=no, toolbar=no, menubar=no, resizable=yes, titlebar=no, left='+tl+', top='+tt+', height='+th+', width='+tw);
}

function gerarBoleto(_prop){
	var th = 525; // altura
	var tw = 680; // largura
	var tl = 50; // esquerda
	var tt = 50;  // topo
	pagina = 'boleto.php?cod_proposta='+_prop;
	window.open(pagina,'boletocontrathos', 'fullscreen=no, status=no, scrollbars=yes, location=no, toolbar=no, menubar=no, resizable=no,  titlebar=no, left='+tl+', top='+tt+', height='+th+', width='+tw);
}

function resetFormDivId(_id){
	var obj = document.getElementById(_id);
	var inputs = obj.getElementsByTagName('input');
	for(i=0; i<inputs.length; i++){
		if(inputs[i].type=="text"){ inputs[i].value = ''; }
		if(inputs[i].type=="radio"){ inputs[i].checked = false; }
		if(inputs[i].type=="checkbox"){ inputs[i].checked = false; }
		if(inputs[i].type=="hidden"){ inputs[i].value = ''; }
	}
	var select = obj.getElementsByTagName('select');
	for(i=0; i<select.length; i++){ select[i].options[0].selected = true; }
	var txarea = obj.getElementsByTagName('textarea');
	for(i=0; i<txarea.length; i++){ txarea[i].value = ''; }
}

