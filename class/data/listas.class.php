<?php

class listas extends database {
	function getListaECivil($cod_estciv=false) {
		$oECivil = new ecivil();
		$aECivil = $oECivil->getListaECivil($cod_estciv);
		return $aECivil;
	}
	
	function getListaLogradouro() {
		$oLogradouro = new logradouro();
		$aLogradouro = $oLogradouro->getListaLogradouro();
		return $aLogradouro;
	}

	function getListaBairro($cod_bairro=false) {
		$oBairro = new bairro();
		$aBairro = $oBairro->getListaBairro($cod_bairro);
		return $aBairro;
	}
	
	function getListaUF($cod_uf=false) {
		$oMunicipio = new municipio();
		$aUF = $oMunicipio->getListaUf($cod_uf);
		return $aUF;
	}

	function getListaMunicipio($cod_uf=false,$cod_mun=false) {
		$aMunicipio = array();
		if($cod_uf){
			$oMunicipio = new municipio();
			$aMunicipio = $oMunicipio->getListaMunicipio($cod_uf,$cod_mun);
		}
		return $aMunicipio;
	}
	
	function getListaPais($cod_pais=false) {
		$oPais = new pais();
		$aPais = $oPais->getListaPais($cod_pais);
		return $aPais;
	}
	
	function getListaTipoImovel($valor=false) {
		$oParametros = new parametros();
		$aParametros = $oParametros->listaTipoImovel($valor);
		return $aParametros;
	}

	function getListaTipoApartam($valor=false) {
		$oParametros = new parametros();
		$aParametros = $oParametros->getListaTipoApartam($valor);
		return $aParametros;
	}

	function getListaTipoDocumento($cod_tpdoc=false) {
		$oTipoDoc = new tipodoc();
		$aTipoDoc = $oTipoDoc->getListaTipoDoc($cod_tpdoc);
		return $aTipoDoc;
	}

	function getListaProfissoes($cod_prof=false) {
		$oProfissao = new profissao();
		$aProfissao = $oProfissao->getListaProfissoes($cod_prof);
		return $aProfissao;
	}

	function getListaAtivEcon($cod_cnae=false) {
		$oAtivEcon = new cnae();
		$aAtivEcon = $oAtivEcon->getListaAtivEcon($cod_cnae);
		return $aAtivEcon;
	}

	function getAgencia($cod_agbb) {
		$oAgencia = new agenciabb();
		$aAgencia = $oAgencia->pesquisarPk($cod_agbb);
		return $aAgencia;
	}

	function getListaTipoImposto($valor=false) {
		$oParametros = new parametros();
		$aParametros = $oParametros->listaTipoImposto($valor);
		return $aParametros;
	}

	function getListaTipoConstrucao($valor=false) {
		$oParametros = new parametros();
		$aParametros = $oParametros->listaTipoConstrucao($valor);
		return $aParametros;
	}

	function getListaTipoCondominio($valor=false) {
		$oParametros = new parametros();
		$aParametros = $oParametros->listaTipoCondominio($valor);
		return $aParametros;
	}

	function getListaTipoConservacao($valor=false) {
		$oParametros = new parametros();
		$aParametros = $oParametros->listaTipoConservacao($valor);
		return $aParametros;
	}
	
	function getListaTipoMoradia($valor=false) {
		$oParametros = new parametros();
		$aParametros = $oParametros->listaTipoMoradia($valor);
		return $aParametros;
	}
	
	function getListaImovelTerreo($valor=false) {
		$oParametros = new parametros();
		$aParametros = $oParametros->listaImovelTerreo($valor);
		return $aParametros;
	}

	function getTipoVaga($valor=false) {
		$oParametros = new parametros();
		$aParametros = $oParametros->getTipoVaga($valor);
		return $aParametros;
	}

	function getLocalVaga($valor=false) {
		$oParametros = new parametros();
		$aParametros = $oParametros->getLocalVaga($valor);
		return $aParametros;
	}

	
	function getListaSN() {
		$aParametros = array('S'=>'Sim', 'N'=>'Não');
		return $aParametros;
	}
	
	function getListaTipoTelefone() {
		$aParametros = array('H'=>'Residencial', 'C'=>'Comercial', 'M'=>'Celular', 'R'=>'Recados', 'F'=>'FAX');
		return $aParametros;
	}
	
	function getListaRegimeBens($valor=false) {
		$oParametros = new parametros();
		$aParametros = $oParametros->getListaRegimeBens($valor);
		return $aParametros;
	}

	function getListaSexo($valor=false) {
		$oParametros = new parametros();
		$aParametros = $oParametros->getListaSexo($valor);
		return $aParametros;
	}
	
	function getListaDespachantes($uf=false,$cidade=false){
		$oUsuario = new usuario();
		$aUsuario = $oUsuario->getListaDsespachantes($uf,$cidade);
		return $aUsuario;
	}

}
?>