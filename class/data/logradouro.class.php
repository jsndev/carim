<?
class logradouro extends database {
	
	function logradouro() {

	}

	function getListaLogradouro() {
		$this->query = "
			SELECT
				cod_logr,
				desc_logr
			FROM
				logradouro 
			ORDER BY 
				desc_logr
		";
		$this->query();
		return $this->qrdata;
	}
	
}

?>