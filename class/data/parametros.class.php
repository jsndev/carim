<?
class parametros extends database {
	
	function parametros() {
		
	}
	
	function listaStatusProposta() {
		$this->query="SELECT valor_param, titulo_param FROM parametro WHERE tipo_param='status da proposta' ORDER BY valor_param";
		$this->query();
		$tmp = array();
		if (is_array($this->qrdata) && @count($this->qrdata) > 0) {
			foreach($this->qrdata as $k=>$v){
				$vv = $v['valor_param']; 
				$tmp[$vv] = $v['titulo_param'];
			}
		}
		return $tmp;
	}

	function listaIndicadorCancelamento() {
		$this->query="SELECT valor_param, titulo_param FROM parametro WHERE tipo_param='indicador de cancelamento' ORDER BY valor_param";
		$this->query();
		$tmp = array();
		if (is_array($this->qrdata) && @count($this->qrdata) > 0) {
			foreach($this->qrdata as $k=>$v){
				$vv = $v['valor_param']; 
				$tmp[$vv] = $v['titulo_param'];
			}
		}
		return $tmp;
	}

	function listaValoresBoleto() {
		$this->query="SELECT valor_param, titulo_param 
									FROM parametro
									WHERE tipo_param='valores de boleto'
									ORDER BY valor_param";
		$this->query();
		return $this->qrdata;
	}

	function getValorBoleto($cod_uf=false) {
		if($cod_uf){
			$this->query="SELECT valor_param, titulo_param 
										FROM parametro
										WHERE tipo_param='valores de boleto'
										AND titulo_param='".mysql_real_escape_string($cod_uf)."'
										ORDER BY valor_param";
			$this->query();
			return $this->qrdata;
		}
		return false;
	}

	function getTaxaJuros() {
		$this->query="SELECT valor_param FROM parametro WHERE tipo_param='taxa de juros'";
		$this->query();
		if (is_array($this->qrdata) && @count($this->qrdata) > 0) {
			return (float)$this->qrdata[0]['valor_param'];
		}
		return 0;
	}
		
	function listaTipoImovel($valor=false) {
		$sqlComplem = ($valor)?" AND valor_param='".mysql_real_escape_string($valor)."' ":"";
		$this->query="SELECT valor_param, titulo_param FROM parametro WHERE tipo_param='tipos de imovel'".$sqlComplem;
		$this->query();
		$tmp = array();
		if (is_array($this->qrdata) && @count($this->qrdata) > 0) {
			foreach($this->qrdata as $k=>$v){
				$vv = $v['valor_param']; 
				$tmp[$vv] = $v['titulo_param'];
			}
		}
		return $tmp;
	}
	
	function getListaTipoApartam($valor=false) {
		$sqlComplem = ($valor)?" AND valor_param='".mysql_real_escape_string($valor)."' ":"";
		$this->query="SELECT valor_param, titulo_param FROM parametro WHERE tipo_param='tipos de apartamento'".$sqlComplem;
		$this->query();
		$tmp = array();
		if (is_array($this->qrdata) && @count($this->qrdata) > 0) {
			foreach($this->qrdata as $k=>$v){
				$vv = $v['valor_param']; 
				$tmp[$vv] = $v['titulo_param'];
			}
		}
		return $tmp;
	}

	function listaTipoImposto($valor=false) {
		$sqlComplem = ($valor)?" AND valor_param='".mysql_real_escape_string($valor)."' ":"";
		$this->query="SELECT valor_param, titulo_param FROM parametro WHERE tipo_param='tipos de imposto'".$sqlComplem;
		$this->query();
		$tmp = array();
		if (is_array($this->qrdata) && @count($this->qrdata) > 0) {
			foreach($this->qrdata as $k=>$v){
				$vv = $v['valor_param']; 
				$tmp[$vv] = $v['titulo_param'];
			}
		}
		return $tmp;
	}
	
	function listaTipoConstrucao($valor=false) {
		$sqlComplem = ($valor)?" AND valor_param='".mysql_real_escape_string($valor)."' ":"";
		$this->query="SELECT valor_param, titulo_param FROM parametro WHERE tipo_param='tipos de construcao'".$sqlComplem;
		$this->query();
		$tmp = array();
		if (is_array($this->qrdata) && @count($this->qrdata) > 0) {
			foreach($this->qrdata as $k=>$v){
				$vv = $v['valor_param']; 
				$tmp[$vv] = $v['titulo_param'];
			}
		}
		return $tmp;
	}
	
	function listaTipoCondominio($valor=false) {
		$sqlComplem = ($valor)?" AND valor_param='".mysql_real_escape_string($valor)."' ":"";
		$this->query="SELECT valor_param, titulo_param FROM parametro WHERE tipo_param='tipos de condominio'".$sqlComplem;
		$this->query();
		$tmp = array();
		if (is_array($this->qrdata) && @count($this->qrdata) > 0) {
			foreach($this->qrdata as $k=>$v){
				$vv = $v['valor_param']; 
				$tmp[$vv] = $v['titulo_param'];
			}
		}
		return $tmp;
	}

	function listaTipoConservacao($valor=false) {
		$sqlComplem = ($valor)?" AND valor_param='".mysql_real_escape_string($valor)."' ":"";
		$this->query="SELECT valor_param, titulo_param FROM parametro WHERE tipo_param='tipos de conservacao'".$sqlComplem;
		$this->query();
		$tmp = array();
		if (is_array($this->qrdata) && @count($this->qrdata) > 0) {
			foreach($this->qrdata as $k=>$v){
				$vv = $v['valor_param']; 
				$tmp[$vv] = $v['titulo_param'];
			}
		}
		return $tmp;
	}
	
	function listaTipoMoradia($valor=false) {
		$sqlComplem = ($valor)?" AND valor_param='".mysql_real_escape_string($valor)."' ":"";
		$this->query="SELECT valor_param, titulo_param FROM parametro WHERE tipo_param='tipos de moradia'".$sqlComplem;
		$this->query();
		$tmp = array();
		if (is_array($this->qrdata) && @count($this->qrdata) > 0) {
			foreach($this->qrdata as $k=>$v){
				$vv = $v['valor_param']; 
				$tmp[$vv] = $v['titulo_param'];
			}
		}
		return $tmp;
	}
	
	function listaImovelTerreo($valor=false) {
		$sqlComplem = ($valor)?" AND valor_param='".mysql_real_escape_string($valor)."' ":"";
		$this->query="SELECT valor_param, titulo_param FROM parametro WHERE tipo_param='terreo'".$sqlComplem;
		$this->query();
		$tmp = array();
		if (is_array($this->qrdata) && @count($this->qrdata) > 0) {
			foreach($this->qrdata as $k=>$v){
				$vv = $v['valor_param']; 
				$tmp[$vv] = $v['titulo_param'];
			}
		}
		return $tmp;
	}

	function getTipoVaga($valor=false) {
		$sqlComplem = ($valor)?" AND valor_param='".mysql_real_escape_string($valor)."' ":"";
		$this->query="SELECT valor_param, titulo_param FROM parametro WHERE tipo_param='tipo de vaga' ORDER BY cod_param".$sqlComplem;
		$this->query();
		$tmp = array();
		if (is_array($this->qrdata) && @count($this->qrdata) > 0) {
			foreach($this->qrdata as $k=>$v){
				$vv = $v['valor_param']; 
				$tmp[$vv] = $v['titulo_param'];
			}
		}
		return $tmp;
	}

	function getLocalVaga($valor=false) {
		$sqlComplem = ($valor)?" AND valor_param='".mysql_real_escape_string($valor)."' ":"";
		$this->query="SELECT valor_param, titulo_param FROM parametro WHERE tipo_param='local da vaga' ORDER BY cod_param".$sqlComplem;
		$this->query();
		$tmp = array();
		if (is_array($this->qrdata) && @count($this->qrdata) > 0) {
			foreach($this->qrdata as $k=>$v){
				$vv = $v['valor_param']; 
				$tmp[$vv] = $v['titulo_param'];
			}
		}
		return $tmp;
	}
	
	function getListaRegimeBens($valor=false) {
		$sqlComplem = ($valor)?" AND valor_param='".mysql_real_escape_string($valor)."' ":"";
		$this->query="SELECT valor_param, titulo_param FROM parametro WHERE tipo_param='regime de bens'".$sqlComplem." ORDER BY cod_param";
		$this->query();
		$tmp = array();
		if (is_array($this->qrdata) && @count($this->qrdata) > 0) {
			foreach($this->qrdata as $k=>$v){
				$vv = $v['valor_param']; 
				$tmp[$vv] = $v['titulo_param'];
			}
		}
		return $tmp;
	}
	
	function getListaSexo($valor=false) {
		$sqlComplem = ($valor)?" AND valor_param='".mysql_real_escape_string($valor)."' ":"";
		$this->query="SELECT valor_param, titulo_param FROM parametro WHERE tipo_param='tipos de sexo'".$sqlComplem;
		$this->query();
		$tmp = array();
		if (is_array($this->qrdata) && @count($this->qrdata) > 0) {
			foreach($this->qrdata as $k=>$v){
				$vv = $v['valor_param']; 
				$tmp[$vv] = $v['titulo_param'];
			}
		}
		return $tmp;
	}
	
	function getListaSN() {
		$aParametros = array('S'=>'Sim', 'N'=>'Não');
		return $aParametros;
	}
	
}

?>