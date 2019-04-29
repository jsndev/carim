<?
class regiao extends database {
	
	function regiao() {

	}
	
	function getListaRegiao() {
		$this->query = "
			SELECT
				cod_regi,
				nome_regi,
				descr_regi,
				flgativo_regi
			FROM
				regiao 
			WHERE 
				flgativo_regi < 9 
			ORDER BY 
				nome_regi,
				descr_regi
		";
		$this->query();
		return $this->qrdata;
	}
	
	function getRegiao($cod_regi) {
		$this->query = "
			SELECT
				cod_regi,
				nome_regi,
				descr_regi,
				flgativo_regi
			FROM
				regiao
			WHERE
				cod_regi = '".mysql_real_escape_string($cod_regi)."'
		";
		$this->query();
		return $this->qrdata;
	}
	
	function addRegiao($dadosRegiao) {
		$this->query = "
			INSERT INTO regiao (
				nome_regi,
				descr_regi,
				flgativo_regi
			) VALUES (
				'".mysql_real_escape_string($dadosRegiao['nome_regi'])."',
				'".mysql_real_escape_string($dadosRegiao['descr_regi'])."',
				'".mysql_real_escape_string($dadosRegiao['flgativo_regi'])."'
			)
		";
		return $this->query();
	}
	
	function delRegiao($cod_regi) {
		$this->query = "
			DELETE FROM regiao
			WHERE cod_regi = '".mysql_real_escape_string($cod_regi)."'
		";
		return $this->query();
	}
	
	function updRegiao($dadosRegiao) {
		$this->query = "
			UPDATE regiao SET 
				nome_regi = '".mysql_real_escape_string($dadosRegiao["nome_regi"])."',
				descr_regi = '".mysql_real_escape_string($dadosRegiao["descr_regi"])."',
				flgativo_regi = '".mysql_real_escape_string($dadosRegiao["flgativo_regi"])."'
			WHERE
				cod_regi = '".$dadosRegiao["cod_regi"]."'
		";
		return $this->query();
	}
	
	function getListaRegiaoMunicipio($cod_regi) {
		$this->query = "
			SELECT
				municipio.cod_uf,
				municipio.cod_municipio,
				municipio.nome_municipio
			FROM
				municipio, 
				regiaomunicipio as rgmn
			WHERE
				rgmn.cod_regi = '".mysql_real_escape_string($cod_regi)."' and 
				rgmn.cod_uf = municipio.cod_uf and 
				rgmn.cod_municipio = municipio.cod_municipio
			ORDER BY 
				municipio.cod_uf,
				municipio.nome_municipio
		";
		$this->query();
		return $this->qrdata;
	}
	
	function delRegiaoMunicipio($cod_regi, $cod_uf = false, $cod_municipio = false) {
		$this->query = "
			DELETE FROM regiaomunicipio
			WHERE
				cod_regi = '".mysql_real_escape_string($cod_regi)."'
				".(($cod_uf && $cod_municipio) ? " and cod_ud = '".mysql_real_escape_string($cod_uf)."' and cod_municipio = '".mysql_real_escape_string($cod_municipio)."'" : "")."
		";
		return $this->query();
	}
	
	function addRegiaoMunicipio($dadosRegiaoMunicipio) {
		$this->query = "
			INSERT INTO regiaomunicipio (
				cod_regi,
				cod_uf,
				cod_municipio
			) VALUES (
				'".mysql_real_escape_string($dadosRegiaoMunicipio["cod_regi"])."', 
				'".mysql_real_escape_string($dadosRegiaoMunicipio["cod_uf"])."', 
				'".mysql_real_escape_string($dadosRegiaoMunicipio["cod_municipio"])."'
			)
		";
		return $this->query();
	}
}

?>