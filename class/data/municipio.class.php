<?php
class municipio extends database {
	
	function municipio() {
		
	}
	
	function getListaUf() {
		$this->query = "
			SELECT
				cod_uf,
				nome_uf
			FROM
				uf
			ORDER BY
				nome_uf
		";
		$this->query();
		return $this->qrdata;
	}

	function getListaMunicipio($cod_uf) {
		$this->query = "
			SELECT
				cod_uf,
				cod_municipio,
				nome_municipio
			FROM
				municipio
			WHERE
				cod_uf = '".mysql_real_escape_string($cod_uf)."'
		";
		$this->query();
		return $this->qrdata;
	}
	
	function searchMunicipio($cod_uf, $nome_municipio) {
		$this->query = "
			SELECT
				cod_uf,
				cod_municipio,
				nome_municipio
			FROM
				municipio
			WHERE
				cod_uf = '".mysql_real_escape_string($cod_uf)."' and 
				nome_municipio like '%".mysql_real_escape_string($nome_municipio)."%'
		";
		$this->query();
		return $this->qrdata;
	}

	function getMunicipio($cod_municipio) {
		$this->query = "
			SELECT
				municipio.cod_uf,
				municipio.cod_municipio,
				municipio.nome_municipio, 
				uf.nome_uf
			FROM
				municipio, 
				uf 
			WHERE
				municipio.cod_municipio = '".mysql_real_escape_string($cod_municipio)."' and 
				municipio.cod_uf = uf.cod_uf 
		";
		$this->query();
		return $this->qrdata;
	}
}
?>