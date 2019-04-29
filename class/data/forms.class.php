<?php
define("ESTADOCIVILPREVI", "P");
define("ESTADOCIVILATHOS", "A");
define("ESTADOCIVILTODOS", "T");

class forms extends database {

	function getBairro($cod=false) {
		$where = '';
		if($cod){ $where = " WHERE cod_bairro='".mysql_real_escape_string($cod)."' "; }
		$this->query="SELECT cod_bairro, nome_bairro FROM bairro $where ORDER BY nome_bairro";
		$this->query();
		return $this->qrdata;
	}
	
	function getUF($coduf=false) {
		$where = '';
		if($coduf){ $where = " WHERE cod_uf='".mysql_real_escape_string($coduf)."' "; }
		$this->query="SELECT cod_uf, nome_uf FROM uf $where ORDER BY nome_uf";
		$this->query();
		return $this->qrdata;
	}

	function getMunicipios($coduf,$codmun=false) {
		$where = " WHERE cod_uf='".mysql_real_escape_string($coduf)."' ";
		if($codmun){ $where = " WHERE cod_municipio='".mysql_real_escape_string($codmun)."' "; }
		$this->query="SELECT cod_municipio, nome_municipio FROM municipio $where ORDER BY nome_municipio";
		$this->query();
		return $this->qrdata;
	}

	function  getECivil($cod=false, $strOrigem = false) {
		$where = "WHERE flgprevi_estciv in ";
		
		switch ($strOrigem) {
			case ESTADOCIVILATHOS:
				$where .= "('N')";
			break;
			case ESTADOCIVILTODOS:
				$where .= "('S','N')";
			break;
			case ESTADOCIVILPREVI:
			default:
				$where .= "('S')";
			break;
		}
		
		if($cod) { 
			$where .= " and cod_estciv='".mysql_real_escape_string($cod)."' "; 
		}
		
		$this->query="SELECT cod_estciv, desc_estciv FROM estadocivil $where ORDER BY desc_estciv";
		$this->query();
		return $this->qrdata;
	}
	
	function getLogr($cod=false) {
		$where = '';
		if($cod){ $where = " WHERE cod_logr='".mysql_real_escape_string($cod)."' "; }
		$this->query="SELECT cod_logr, desc_logr FROM logradouro $where ORDER BY desc_logr";
		$this->query();
		return $this->qrdata;
	}

	function getPais($codpais=false) {
		$where = '';
		if($codpais){ $where = " WHERE cod_pais='".mysql_real_escape_string($codpais)."' "; }
		$this->query="SELECT cod_pais, nome_pais FROM pais $where ORDER BY nome_pais";
		$this->query();
		return $this->qrdata;
	}

	function getTpDoc($codtpdoc=false) {
		$where = '';
		if($codtpdoc){ $where = " WHERE cod_tpdoc='".mysql_real_escape_string($codtpdoc)."' "; }
		$this->query="SELECT cod_tpdoc, desc_tpdoc FROM tipodoc $where ORDER BY desc_tpdoc";
		$this->query();
		return $this->qrdata;
	}

	function getProfissao($codprof=false) {
		$where = '';
		if($codprof){ $where = " WHERE cod_prof='".mysql_real_escape_string($codprof)."' "; }
		$this->query="SELECT cod_prof, desc_prof FROM profissao $where ORDER BY desc_prof";
		$this->query();
		return $this->qrdata;
	}
	
	function getAtvEcon($codcnae=false) {
		$where = '';
		if($codcnae){ $where = " WHERE cod_cnae='".mysql_real_escape_string($codcnae)."' "; }
		$this->query="SELECT cod_cnae, desc_cnae FROM cnae $where ORDER BY desc_cnae";
		$this->query();
		return $this->qrdata;
	}

}

?>