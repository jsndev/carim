<?
class documento extends database {
	
	function documento() {

	}
	
	function getListaDocumento() {
		$this->query = "
			SELECT
				cod_docm,
				nome_docm,
				descr_docm,
				validade_docm
			FROM
				documento 
			ORDER BY 
				nome_docm,
				descr_docm
		";
		$this->query();
		return $this->qrdata;
	}
	
	function getDocumento($cod_docm) {
		$this->query = "
			SELECT
				cod_docm,
				nome_docm,
				descr_docm,
				validade_docm
			FROM
				documento
			WHERE
				cod_docm = '".mysql_real_escape_string($cod_docm)."'
		";
		$this->query();
		return $this->qrdata;
	}
	
	function addDocumento($dadosDocumento) {
		$this->query = "
			INSERT INTO documento (
				nome_docm,
				descr_docm,
				validade_docm
			) VALUES (
				'".mysql_real_escape_string($dadosDocumento['nome_docm'])."',
				'".mysql_real_escape_string($dadosDocumento['descr_docm'])."',
				'".mysql_real_escape_string($dadosDocumento['validade_docm'])."'
			)
		";
		return $this->query();
	}
	
	function delDocumento($cod_docm) {
		$this->query = "
			DELETE FROM documento
			WHERE cod_docm = '".mysql_real_escape_string($cod_docm)."'
		";
		return $this->query();
	}
	
	function updDocumento($dadosDocumento) {
		$this->query = "
			UPDATE documento SET 
				nome_docm = '".mysql_real_escape_string($dadosDocumento["nome_docm"])."',
				descr_docm = '".mysql_real_escape_string($dadosDocumento["descr_docm"])."',
				validade_docm = '".mysql_real_escape_string($dadosDocumento["validade_docm"])."'
			WHERE
				cod_docm = '".mysql_real_escape_string($dadosDocumento["cod_docm"])."'
		";
		return $this->query();
	}
	
	function getListaDocumentoMunicipio($cod_uf, $cod_municipio) {
		$this->query = "
			SELECT
				mndc.cod_uf as cod_uf, 
				mndc.cod_municipio as cod_municipio, 
				mndc.cod_docm as cod_docm,
				mndc.cod_enti as cod_enti, 
				mndc.flgobrigatorio_mndc as flgobrigatorio_mndc,
				muni.nome_municipio as nome_municipio,
				docm.nome_docm as nome_docm,
				docm.descr_docm as descr_docm,
				docm.validade_docm as validade_docm,
				enti.nome_enti as nome_enti,
				enti.descr_enti as descr_enti
			FROM
				municipio as muni, 
				documento as docm, 
				entidade as enti, 
				municipiodocumento as mndc
			WHERE
				mndc.cod_uf = muni.cod_uf and 
				mndc.cod_municipio = muni.cod_municipio and 
				mndc.cod_docm = docm.cod_docm and 
				mndc.cod_enti = enti.cod_enti and 
				mndc.cod_uf = '".mysql_real_escape_string($cod_uf)."' and 
				mndc.cod_municipio = '".mysql_real_escape_string($cod_municipio)."' and 
				mndc.flgstatus_mndc = '1'
			ORDER BY
				docm.nome_docm, 
				enti.nome_enti
		";
		$this->query();
		return $this->qrdata;
	}
	
	function addDocumentoMunicipio($cod_uf,$cod_municipio,$cod_docm,$cod_enti,$obrig) {
		$this->query = "
			INSERT INTO municipiodocumento (
				cod_uf, 
				cod_municipio,
				cod_docm,
				cod_enti,
				flgobrigatorio_mndc,
				flgstatus_mndc
			) VALUES (
				'".mysql_real_escape_string($cod_uf)."', 
				'".mysql_real_escape_string($cod_municipio)."', 
				'".mysql_real_escape_string($cod_docm)."', 
				'".mysql_real_escape_string($cod_enti)."', 
				'".(int)mysql_real_escape_string($obrig)."', 
				'1'
			)
		";
		return $this->query();
	}

	function updDocumentoMunicipio($cod_uf,$cod_municipio,$cod_docm,$cod_enti,$obrig,$stat) {
		$this->query = "
			UPDATE municipiodocumento SET
				flgobrigatorio_mndc = '".(int)mysql_real_escape_string($obrig)."', 
				flgstatus_mndc = '".(int)mysql_real_escape_string($stat)."'
			WHERE
				cod_uf = '".mysql_real_escape_string($cod_uf)."' and 
				cod_municipio = '".mysql_real_escape_string($cod_municipio)."' and 
				cod_docm = '".mysql_real_escape_string($cod_docm)."' and 
				cod_enti = '".mysql_real_escape_string($cod_enti)."'
		";
		return $this->query();
	}
	
	function delDocumentoMunicipio($cod_uf,$cod_municipio,$cod_docm,$cod_enti,$obrig) {
		return $this->updDocumentoMunicipio($cod_uf,$cod_municipio,$cod_docm,$cod_enti,$obrig,'9');
	}
	
	function clearDocumentoMunicipio($cod_uf,$cod_municipio) {
		$this->query = "
			UPDATE municipiodocumento SET
				flgstatus_mndc = '9'
			WHERE
				cod_uf = '".mysql_real_escape_string($cod_uf)."' and 
				cod_municipio = '".mysql_real_escape_string($cod_municipio)."'
		";
		return $this->query();
	}
}

?>