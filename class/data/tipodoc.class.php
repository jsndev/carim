<?
class tipodoc extends database {
	
	function tipodoc() {

	}
	
	function getListaTipoDoc() {
		$this->query = "
			SELECT
				cod_tpdoc,
				desc_tpdoc
			FROM
				tipodoc 
			ORDER BY 
				desc_tpdoc
		";
		$this->query();
		return $this->qrdata;
	}
	
}

?>