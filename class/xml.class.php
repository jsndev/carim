<?php

class xml {
	
	var $xmlData;
	
	function xml() {
		
	}
	
	function setXmlData($xmlData) {
		$this->xmlData = $xmlData;
	}
	function getXmlData() {
		return $this->xmlData;
	}
	function appendXmlData($xmlData) {
		$this->xmlData .= $xmlData;
	}
	
	function send() {
		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Content-type: text/xml; charset=iso-8859-1");
//		header("Content-length: ".strlen($this->getXmlData()));
		
		echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
		$tmpOut = $this->getXmlData();
		$tmpOut = eregi_replace("&", "&amp;",$tmpOut);
		echo $tmpOut;
	}
}

?>