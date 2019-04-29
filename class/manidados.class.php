<?
class manidados extends database {

	// construtora da classe
	function manidados() {
		
	}

	// inserção de log no banco de dados
	function insert_history($proposta,$tipo,$obs) {
		if((!empty($proposta) and is_numeric($proposta))) {
			// inserindo o historico do contrato
			$this->query="INSERT INTO historico (
							cod_ppst,
							dt_hist,
							tipo_hist,
							cod_usua,
							obs_hist
						  ) VALUES (
							'".mysql_real_escape_string($proposta)."',
							NOW(),
							'".mysql_real_escape_string($tipo)."',
							'".mysql_real_escape_string($this->iID)."',
							'".mysql_real_escape_string($obs)."'
						  )";
			$this->query();
			//print $this->query."<hr>";
		} else {
			$this->cERRO="Erro nos parametros de inserção de histórico.";
		}
	}

	// inserção de histórico no banco de dados
	function insert_log($trans,$oper,$obs) {
		$uID = ($this->iID)?$this->iID:'NULL';
		if((!empty($trans) and !empty($oper)) and (is_numeric($trans) and is_numeric($oper))) {
			// inserindo o log 
			$this->query="INSERT INTO log (
							dt_log,
							transacao_log,
							operacao_log,
							usuario_log,
							observacao_log
						  ) VALUES (
							NOW(),
							'".mysql_real_escape_string($trans)."',
							'".mysql_real_escape_string($oper)."',
							".mysql_real_escape_string($uID).",
							'".mysql_real_escape_string($obs)."'
						  )";
			$this->query();
			//print $this->query."<hr>";
		} else {
			$this->cERRO="Erro nos parametros de inserção de log.";
		}
	}
}
?>