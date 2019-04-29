<?
class taxa extends database {
	
	function taxa() {
		
	}
	
	function getListaTaxas() {
		$this->query = "
			SELECT
				cod_taxa,
				nome_taxa,
				descr_taxa,
				valor_taxa
			FROM
				taxa
			ORDER BY 
				nome_taxa,
				valor_taxa
		";
		$this->query();
		return $this->qrdata;
	}
	
	function getTaxa($cod_taxa) {
		$this->query = "
			SELECT
				cod_taxa,
				nome_taxa,
				descr_taxa,
				valor_taxa
			FROM
				taxa
			WHERE
				cod_taxa = '".mysql_real_escape_string($cod_taxa)."'
		";
		$this->query();
		return $this->qrdata;
	}
	
	function addTaxa($dadosTaxa) {
		$this->query = "";
		return $this->query();
	}
	
	function delTaxa($cod_taxa) {
		$this->query = "";
		return $this->query();
	}
	
	function updTaxa($dadosTaxa) {
		$this->query = "
			UPDATE taxa SET 
				nome_taxa = '".mysql_real_escape_string($dadosTaxa["nome_taxa"])."',
				descr_taxa = '".mysql_real_escape_string($dadosTaxa["descr_taxa"])."',
				valor_taxa = '".mysql_real_escape_string($dadosTaxa["valor_taxa"])."'
			WHERE
				cod_taxa = '".mysql_real_escape_string($dadosTaxa["cod_taxa"])."'
		";
		return $this->query();
	}
}

?>