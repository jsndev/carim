<?php
class contrato extends database {
	
	function contrato() {
		
	}

	//// Valor default = 1 para Previ, já que apenas a minuta 1 será utilizada.
	function getMinuta($cod_minu = 1) {
		$this->query = "
			SELECT
				cod_minu,
				nome_minu,
				texto_minu
			FROM
				minutacontrato
			WHERE
				cod_minu = '".(int)mysql_real_escape_string($cod_minu)."'
		";
		$this->query();
		return $this->qrdata;
	}
	
	function updMinuta($aDadosMinuta) {
		$this->query = "
			UPDATE minutacontrato SET 
				nome_minu = '".mysql_real_escape_string($aDadosMinuta["nome_minu"])."',
				texto_minu = '".mysql_escape_string($aDadosMinuta["texto_minu"])."'
			WHERE
				cod_minu = '".(int)mysql_real_escape_string($aDadosMinuta["cod_minu"])."'
		";
		return $this->query();
	}
	
}
?>