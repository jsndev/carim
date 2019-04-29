var paramsChat = {countUpdt:0,countMsgs:0,nErros:0};

function startChat(){
	refreshUpdtTimer();
	refreshMsgsTimer();
	setFocusBottom();
}

// --------------------------------------------------------------------------------- //

function refreshUpdtTimer(){
	if(paramsChat.countUpdt <= 0){
		paramsChat.countUpdt = 4;
		refreshUpdt();
	}else{
		paramsChat.countUpdt--;
	}
	setTimeout('refreshUpdtTimer()',500); // meio segundo
}

function refreshUpdt(){
	var dt = Date();
	var rex = new RegExp('\\D', "g");
	dt = dt.replace(rex,'');
	document.getElementById('iframeUpdt').src = 'prop_chat_updt.php?dt='+dt;
}

// --------------------------------------------------------------------------------- //

function refreshMsgsTimer(){
	if(paramsChat.countMsgs <= 0){
		paramsChat.countMsgs = 8;
		refreshMsgs();
	}else{
		paramsChat.countMsgs--;
	}
	setTimeout('refreshMsgsTimer()',500); // um segundo
}

function refreshMsgs(){
	var dt = Date();
	var rex = new RegExp('\\D', "g");
	dt = dt.replace(rex,'');
	document.getElementById('iframeMsgs').src = 'prop_chat_msgs.php?dt='+dt;
}

// --------------------------------------------------------------------------------- //

function msgOut(_msg){
	var divMsg = document.getElementById('divMensagens');
	var mensagem = document.createElement('div');
	mensagem.innerHTML = _msg;
	mensagem.className = 'chatMsg';
	divMsg.appendChild(mensagem);
	document.title = 'CHAT CONTRATHOS';
	setTimeout('redoTitle()',1000);
}

function redoTitle(){
	document.title = 'Chat Contrathos';
}

function txtDisabled(){
	document.getElementById('divTxtPost').style.visibility='hidden';
}

function txtEnabled(){
	document.getElementById('divTxtPost').style.visibility='visible';
}

function setFocusBottom(){
	if(document.getElementById('chk_auto').checked){
		var divMsg = document.getElementById('divMensagens');
		divMsg.scrollTop = divMsg.scrollHeight;
	}
	setTimeout('setFocusBottom()',500);
}

function enviarMsg(){
	document.getElementById('formPost').submit();
	document.getElementById('txtMsg').value = '';
}

