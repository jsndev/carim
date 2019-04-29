<?
class /*historico */extends database {
	
	function historico() {

	}
	
	function getListaHistorico($cod_ppst,$cod_prop,$nome_prop) {
		$this->query="
			SELECT
				h.cod_ppst,
				h.obs_hist,
				h.tipo_hist,
				u.level_usua,
				plevel.titulo_param as descr_level_usua,
				u.nome_usua,
				'' as cod_chat,
				h.dt_hist as data
			FROM
				historico h,
				proposta p,
				usuario u
			LEFT JOIN
				parametro as plevel ON plevel.tipo_param = 'tipos de usuarios' AND u.level_usua = plevel.valor_param
			WHERE
				p.proponente_ppst = '".mysql_real_escape_string($cod_prop)."'
				AND p.cod_ppst = h.cod_ppst
				AND h.cod_usua = u.cod_usua
		UNION
			SELECT
				'".mysql_real_escape_string($cod_ppst)."' as cod_ppst,
				'Sessão de chat' as obs_hist,
				'4' as tipo_hist,
				'1' as level_usua,
				plevel.titulo_param as descr_level_usua,
				'".mysql_real_escape_string($nome_prop)."' as nome_usua,
				cod_chat,
				(SELECT MIN(dt_chtm) FROM chatmensagens WHERE cod_chat = chat.cod_chat) as data
			FROM
				chatsessoes as chat
			LEFT JOIN
				parametro as plevel ON plevel.tipo_param = 'tipos de usuarios' AND plevel.valor_param = 1
			WHERE
				cod_usua = '".mysql_real_escape_string($cod_prop)."'
			ORDER BY
				data DESC
		";
		$this->query();
		return $this->qrdata;
	}
	
}

?>