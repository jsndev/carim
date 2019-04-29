<?
class entidade extends database {
	
	function entidade() {

	}
	
	function getListaEntidade() {
		$this->query = "
			SELECT
				cod_enti,
				nome_enti,
				descr_enti
			FROM
				entidade 
			ORDER BY 
				nome_enti,
				descr_enti
		";
		$this->query();
		return $this->qrdata;
	}
	
	function getEntidade($cod_enti) {
		$this->query = "
			SELECT
				cod_enti,
				nome_enti,
				descr_enti
			FROM
				entidade
			WHERE
				cod_enti = '".mysql_real_escape_string($cod_enti)."'
		";
		$this->query();
		return $this->qrdata;
	}
	
	function addEntidade($dadosEntidade) {
		$this->query = "
			INSERT INTO entidade (
				nome_enti,
				descr_enti
			) VALUES (
				'".mysql_real_escape_string($dadosEntidade['nome_enti'])."',
				'".mysql_real_escape_string($dadosEntidade['descr_enti'])."'
			)
		";
		return $this->query();
	}
	
	function delEntidade($cod_enti) {
		$this->query = "
			DELETE FROM entidade
			WHERE cod_enti = '".mysql_real_escape_string($cod_enti)."'
		";
		return $this->query();
	}
	
	function updEntidade($dadosEntidade) {
		$this->query = "
			UPDATE entidade SET 
				nome_enti = '".mysql_real_escape_string($dadosEntidade["nome_enti"])."',
				descr_enti = '".mysql_real_escape_string($dadosEntidade["descr_enti"])."'
			WHERE
				cod_enti = '".mysql_real_escape_string($dadosEntidade["cod_enti"])."'
		";
		return $this->query();
	}
}

?>