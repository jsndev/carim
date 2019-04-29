var xml = function() {
	var self = this;
	this.file   = null;
	this.ret    = null;
	this.xml    = '';
	this.xmlDoc = null;
	this.transf = 'POST';
	this.returnType = 'xml';
	var xmlhttp = null;
	this.load = _load;
	function _load() {
		if (self.file != '' && self.ret != '') {
			try {
				self.xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e1) {
				try {
					self.xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e2) {
					if (!self.xmlhttp && typeof XMLHttpRequest!='undefined') {
						self.xmlhttp = new XMLHttpRequest();
					}
				}
			}
			if (self.xmlhttp != null) {
				self.xmlhttp.onreadystatechange = _testReadyStateSend;
				self.xmlhttp.open(self.transf,self.file,true);
				if (this.transf == 'POST'){
					self.xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
				}
				self.xmlhttp.send(self.xml);
				return true;
			}
		} else {
			return false;
		}
		return false;
	}

	function _testReadyStateSend() {
		if (self.xmlhttp.readyState == 4) {
			if (self.xmlhttp.status == '200') {
				_evalInternalGet();
			} else {
				alert('Página nao encontrada');
				return false;
			}
		}
	}

	function _testReadyStateReturn() {
		if (self.xmlDoc.readyState == 4) {
			_evalReturn();
		}
	}

	function _evalReturn() {
		var retFunc = '';
		if (typeof(self.ret) == 'function') {
			eval('new self.ret');
		}
	}
	function _evalInternalGet() {
		self.xmlDoc = (self.returnType == 'xml' ? self.xmlhttp.responseXML : self.xmlhttp.responseText);
		_evalReturn();
	}
}

function nValue(obj,nodeName) {
	var _obj = obj.getElementsByTagName(nodeName)[0];
	if (_obj.hasChildNodes()) {
		if (_obj.firstChild) {
			if (_obj.firstChild.nodeValue) {
				return _obj.firstChild.nodeValue;
			}
		}
	}
	return '';
}

