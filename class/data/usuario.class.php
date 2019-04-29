<?
/* Tipo 5 - Figura do Avaliador - Excluído do projeto * /
define("TPUSER_PROPONENTE"	, "1");
define("TPUSER_ATENDENTE"	, "2");
define("TPUSER_ADMPREVI"	, "3");
define("TPUSER_ADMATHOS"	, "4");
define("TPUSER_DESPACHANTE"	, "6");
define("TPUSER_JURIDICO"	, "7");
define("TPUSER_ADMINISTRATIVO"	, "8");
define("TPUSER_USUARIOMASTER"	, "9");

class usuario extends database {
	
	function usuario() {
		
	}
	
	function getListaUsuarios($tipo = false, $status = false) {
		$sFiltroStatus = $status == false ? " < 9 " : " = ".mysql_real_escape_string($status);
		$sFiltroTipo   = $tipo == false ? " is not null" : " = ".mysql_real_escape_string($tipo);
		$this->query = "
			SELECT 
				cod_usua, 
				nome_usua, 
				email_usua, 
				level_usua, 
				id_lstn, 
				flgstatus_usua, 
				( 
					SELECT 
						max(dt_log) 
					FROM 
						`log` 
					WHERE 
						usuario_log = usua.cod_usua and 
						transacao_log = 1 and 
						operacao_log = 1 
				) as dt_login 
			FROM 
				usuario as usua 
			WHERE 
				level_usua ".mysql_real_escape_string($sFiltroTipo)." and 
				flgstatus_usua ".mysql_real_escape_string($sFiltroStatus)."
			ORDER BY 
				nome_usua
		";
		$this->query();
		return $this->qrdata;
	}
	
	function getUsuario($cod_usua) {
		$this->query = "
			SELECT 
				cod_usua,
				nome_usua,
				email_usua,
				pwd_usua,
				level_usua,
				id_lstn,
				flgstatus_usua, 
				( 
					SELECT 
						max(dt_log) 
					FROM 
						`log` 
					WHERE 
						usuario_log = usuario.cod_usua and 
						transacao_log = 1 and 
						operacao_log = 1 
				) as dt_login 
			FROM
				usuario
			WHERE 
				cod_usua = '".mysql_real_escape_string($cod_usua)."'
		";
		$this->query();
		return $this->qrdata;
	}
	
	function getUsuarioByMailMatricula($email,$matricula) {
		$this->query = "
			SELECT 
				cod_usua,
				nome_usua,
				email_usua,
				pwd_usua,
				level_usua,
				id_lstn,
				flgstatus_usua, 
				( 
					SELECT 
						max(dt_log) 
					FROM 
						`log` 
					WHERE 
						usuario_log = usuario.cod_usua and 
						transacao_log = 1 and 
						operacao_log = 1 
				) as dt_login 
			FROM
				usuario
			WHERE 
				email_usua = '".mysql_real_escape_string($email)."' and 
				LPAD(id_lstn,12,'0') = LPAD('".mysql_real_escape_string($matricula)."',12,'0')
		";
		$this->query();
		return $this->qrdata;
	}
	
	function addUsuario($dadosUsuario) {
		$this->query = "
			INSERT INTO usuario (
				nome_usua,
				email_usua,
				pwd_usua,
				level_usua,
				id_lstn,
				flgstatus_usua
			) VALUES (
				'".mysql_real_escape_string($dadosUsuario["nome_usua"])."',
				'".mysql_real_escape_string($dadosUsuario["email_usua"])."',
				'".mysql_real_escape_string($dadosUsuario["pwd_usua"])."',
				'".mysql_real_escape_string($dadosUsuario["level_usua"])."',
				".($dadosUsuario["id_lstn"] ? "'".mysql_real_escape_string($dadosUsuario["id_lstn"])."'" : "NULL").",
				'".mysql_real_escape_string($dadosUsuario["flgstatus_usua"])."'
			)
		";
		return $this->query();
	}
	
	function delUsuario($cod_usua) {
		$this->query = "
			UPDATE usuario SET
				flgstatus_usua = 9
			WHERE
				cod_usua = '".mysql_real_escape_string($cod_usua)."'
		";
		$this->query();
	}
	
	function updUsuario($dadosUsuario) {
		$this->query = "
			UPDATE usuario SET 
				nome_usua = '".mysql_real_escape_string($dadosUsuario["nome_usua"])."',
				email_usua = '".mysql_real_escape_string($dadosUsuario["email_usua"])."',
				pwd_usua = '".mysql_real_escape_string($dadosUsuario["pwd_usua"])."',
				flgstatus_usua = '".mysql_real_escape_string($dadosUsuario["flgstatus_usua"])."'
			WHERE
				cod_usua = '".mysql_real_escape_string($dadosUsuario["cod_usua"])."'
		";
		return $this->query();
	}
	
	function addRegiaoDespachante($cod_regi, $cod_usua) {
		$this->query = "
			INSERT INTO regiaodespachante (
				cod_regi, 
				cod_usua 
			) VALUES (
				'".mysql_real_escape_string($cod_regi)."', 
				'".mysql_real_escape_string($cod_usua)."'
			)
		";
		return $this->query();
	}

	function delRegiaoDespachante($cod_usua) {
		$this->query = "
			DELETE FROM regiaodespachante
			WHERE 
				cod_usua = '".mysql_real_escape_string($cod_usua)."'
		";
		return $this->query();
	}
	
	function getRegiaoDespachante($cod_usua) {
		$this->query = "
			SELECT 
				cod_regi, 
				cod_usua 
			FROM
				regiaodespachante 
			WHERE
				cod_usua = '".mysql_real_escape_string($cod_usua)."'
		";
		$this->query();
		return $this->qrdata;
	}
	
	function getListaDespachantes($uf,$municipio){
		$this->query = "
			SELECT
			  u.cod_usua, u.nome_usua
			FROM
			  usuario u,
			  regiaodespachante d,
			  regiaomunicipio m,
			  regiao r
			WHERE
						r.cod_regi = m.cod_regi
			  AND r.cod_regi = d.cod_regi
			  AND d.cod_usua = u.cod_usua
			  AND m.cod_uf='".mysql_real_escape_string($uf)."'
			  AND m.cod_municipio='".mysql_real_escape_string($municipio)."'
			  AND r.flgativo_regi=1
		";
		$this->query();
		return $this->qrdata;
	}
	
}
*/
?>