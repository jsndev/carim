<?php
class conteudo extends database {
	function getTree() {
		global $crypt;
		$retorno = false;
		$this->query = "
			SELECT
				ct.cod_ctgr,
				ct.descr_ctgr
			FROM
				categoria as ct, 
				(
					SELECT
						distinct(it.cod_ctgr) as cod_ctgr
					FROM
						infotemplate as it,
						templateconteudo as tc
					WHERE
						it.cod_tmpl = tc.cod_tmpl
				) as cttmp
			WHERE
				ct.cod_ctgr = cttmp.cod_ctgr and 
				ct.flgativo_ctgr = 1
			ORDER BY 
				ct.descr_ctgr
		";
		$this->query();
		
		$dadosCategoria = $this->qrdata;
		if ($dadosCategoria) {
			foreach ($dadosCategoria as $categoria) {
				$iCat++;
				$retorno[] .= "'c".$categoria["cod_ctgr"]."','0','".$categoria["descr_ctgr"]."','javascript: void(0);'";
				$this->query = "
					SELECT
						cod_info,
						titulo_info
					FROM
						informativo
					WHERE 
						cod_ctgr = ".mysql_real_escape_string($categoria["cod_ctgr"])." and 
						flgativo_info = 1
				";
				$this->query();
				$dadosInformativos = $this->qrdata;
				if ($dadosInformativos) {
					foreach ($dadosInformativos as $informativo) {
						$retorno[] .= "'i".$informativo["cod_info"]."','c".$categoria["cod_ctgr"]."','".$informativo["titulo_info"]."','javascript: void(0);'";
						
						$this->query = "
							SELECT
								tp.cod_tmpl, 
								tp.titulo_tmpl
							FROM
								infotemplate as it, 
								template as tp,
								(
									SELECT
										distinct(tctmp1.cod_tmpl) as cod_tmpl
									FROM
										templateconteudo as tctmp1
								) as tctmp2
							WHERE 
								tctmp2.cod_tmpl = tp.cod_tmpl and 
								it.cod_tmpl = tp.cod_tmpl and 
								tp.flgativo_tmpl = 1 and 
								it.cod_ctgr = ".mysql_real_escape_string($categoria["cod_ctgr"])." and 
								it.cod_info = ".mysql_real_escape_string($informativo["cod_info"])."
							ORDER BY
								it.ordem_intp
						";
						$this->query();
						$dadosTemplate = $this->qrdata;
						if ($dadosTemplate) {
							foreach ($dadosTemplate as $template) {
								$retorno[] .= "'t".$template["cod_tmpl"]."','i".$informativo["cod_info"]."','".$template["titulo_tmpl"]."','javascript:loadCont(\'".$crypt->encrypt("cod_tmpl=".$template["cod_tmpl"])."\');'";
							}
						}
					}
				}
			}
		}
		return $retorno;
	}
	
	function getConteudosTemplate($cod_tmpl) {
		$this->query = "
			SELECT
				ct.cod_cotd,
				ct.titulo_cotd,
				ct.descr_cotd,
				ct.flgativo_cotd,
				ct.tipo_cotd,
				ct.arquivo_cotd,
				ct.legenda_cotd,
				ct.texto_cotd
			FROM
				conteudo as ct,
				templateconteudo as tc
			WHERE
				tc.cod_tmpl = ".mysql_real_escape_string($cod_tmpl)." and 
				tc.cod_cotd = ct.cod_cotd and 
				ct.flgativo_cotd = 1
			ORDER BY 
				tc.ordem_tpco
		";
		$this->query();
		return $this->qrdata;
	}
	
	function getListaCategorias() {
		$this->query = "
			SELECT
				cod_ctgr,
				titulo_ctgr,
				descr_ctgr,
				flgativo_ctgr
			FROM
				categoria
			ORDER BY 
				titulo_ctgr
		";
		$this->query();
		return $this->qrdata;
	}
	
	function getListaInformativos() {
		$this->query = "
			SELECT
				info.cod_info,
				info.titulo_info,
				info.descr_info,
				info.cod_ctgr,
				info.flgativo_info,
				ctgr.titulo_ctgr
			FROM
				categoria as ctgr,
				informativo as info
			WHERE
				ctgr.cod_ctgr = info.cod_ctgr
			ORDER BY 
				info.titulo_info,
				ctgr.titulo_ctgr
		";
		$this->query();
		return $this->qrdata;
	}
	
	function getListaTemplates() {
		$this->query = "
			SELECT
				tmpl.cod_tmpl,
				tmpl.titulo_tmpl,
				tmpl.descr_tmpl,
				tmpl.flgativo_tmpl
			FROM
				template as tmpl
			ORDER BY 
				tmpl.titulo_tmpl
		";
		$this->query();
		return $this->qrdata;
	}
	
	function getListaConteudos() {
		$this->query = "
			SELECT
				cod_cotd,
				titulo_cotd,
				descr_cotd,
				flgativo_cotd,
				tipo_cotd
			FROM
				conteudo cotd
			ORDER BY 
				titulo_cotd
		";
		$this->query();
		return $this->qrdata;
	}
	
	function addCategoria($dados) {
		$this->query = "
			INSERT INTO categoria (
				titulo_ctgr,
				descr_ctgr,
				flgativo_ctgr
			) VALUES (
				'".mysql_real_escape_string($dados["titulo_ctgr"])."',
				'".mysql_real_escape_string($dados["descr_ctgr"])."',
				'".mysql_real_escape_string($dados["flgativo_ctgr"])."'
			)
		";
		$this->query();
		return $this->insertId;
	}
	
	function getCategoria($cod_ctgr) {
		$this->query = "
			SELECT
				cod_ctgr,
				titulo_ctgr,
				descr_ctgr,
				flgativo_ctgr
			FROM
				categoria
			WHERE
				cod_ctgr = '".mysql_real_escape_string($cod_ctgr)."'
		";
		$this->query();
		return $this->qrdata;
	}
	
	function updCategoria($dados) {
		$this->query = "
		
			UPDATE categoria SET 
				titulo_ctgr = '".mysql_real_escape_string($dados["titulo_ctgr"])."',
				descr_ctgr = '".mysql_real_escape_string($dados["descr_ctgr"])."',
				flgativo_ctgr = '".mysql_real_escape_string($dados["flgativo_ctgr"])."'
			WHERE
				cod_ctgr = '".mysql_real_escape_string($dados["cod_ctgr"])."'
		";
		return $this->query();
	}
	
	function delCategoria($cod_ctgr) {
		$this->query = "
			DELETE FROM 
				categoria 
			WHERE
				cod_ctgr = '".mysql_real_escape_string($cod_ctgr)."'
		";
		return $this->query();
	}
	
	
	function addInformativo($dados) {
		$this->query = "
			INSERT INTO informativo (
				titulo_info,
				descr_info,
				flgativo_info,
				cod_ctgr
			) VALUES (
				'".mysql_real_escape_string($dados["titulo_info"])."',
				'".mysql_real_escape_string($dados["descr_info"])."',
				'".mysql_real_escape_string($dados["flgativo_info"])."',
				'".mysql_real_escape_string($dados["cod_ctgr"])."'
			)
		";
		$this->query();
		return $this->insertId;
	}

	function getInformativo($cod_info) {
		$this->query = "
			SELECT
				info.cod_info,
				info.titulo_info,
				info.descr_info,
				info.flgativo_info,
				ctgr.titulo_ctgr,
				ctgr.cod_ctgr
			FROM
				informativo as info,
				categoria as ctgr
			WHERE
				info.cod_info = '".mysql_real_escape_string($cod_info)."' and 
				info.cod_ctgr = ctgr.cod_ctgr
		";
		$this->query();
		return $this->qrdata;
	}

	function updInformativo($dados) {
		$this->query = "
			UPDATE informativo SET 
				titulo_info = '".mysql_real_escape_string($dados["titulo_info"])."',
				descr_info = '".mysql_real_escape_string($dados["descr_info"])."',
				flgativo_info = '".mysql_real_escape_string($dados["flgativo_info"])."'
			WHERE
				cod_info = '".mysql_real_escape_string($dados["cod_info"])."'
		";
		return $this->query();
	}
	
	function delInformativo($cod_info) {
		$this->query = "
			DELETE FROM 
				informativo 
			WHERE
				cod_info = '".mysql_real_escape_string($cod_info)."'
		";
		return $this->query();
	}
	function delInformativoTemplates($cod_info) {
		$this->query = "
			DELETE FROM 
				infotemplate 
			WHERE
				cod_info = '".mysql_real_escape_string($cod_info)."'
		";
		return $this->query();
	}
	
	function getTemplatesInformativo($cod_info = false) {
		$this->query = "
			SELECT
				tmpl.cod_tmpl,
				tmpl.titulo_tmpl,
				tmpl.descr_tmpl,
				tmpl.flgativo_tmpl,
				intp.ordem_intp, 
				intp.cod_info is not null as atribuido, 
				intp.cod_ctgr
			FROM
				template as tmpl
			LEFT JOIN
				infotemplate as intp on (
					intp.cod_tmpl = tmpl.cod_tmpl 
					and intp.cod_info = '".mysql_real_escape_string($cod_info)."'
				)
			ORDER BY 
		        atribuido,
		        intp.ordem_intp,
				tmpl.titulo_tmpl
		";
		$this->query();
		return $this->qrdata;
	}
	
	function addTemplatesInformativo($dados) {
		$this->query = "
			INSERT INTO infotemplate (
				cod_ctgr,
				cod_info,
				cod_tmpl,
				ordem_intp
			) VALUES (
				'".mysql_real_escape_string($dados["cod_ctgr"])."', 
				'".mysql_real_escape_string($dados["cod_info"])."', 
				'".mysql_real_escape_string($dados["cod_tmpl"])."', 
				'".mysql_real_escape_string($dados["ordem_intp"])."'
			)
		";
		return $this->query();
	}
	
	function addConteudo($dados) {
		$this->query = "
			INSERT INTO conteudo (
				titulo_cotd,
				descr_cotd,
				flgativo_cotd,
				tipo_cotd,
				arquivo_cotd,
				legenda_cotd,
				texto_cotd
			) VALUES (
				'".mysql_real_escape_string($dados["titulo_cotd"])."', 
				'".mysql_real_escape_string($dados["descr_cotd"])."', 
				'".mysql_real_escape_string($dados["flgativo_cotd"])."', 
				'".mysql_real_escape_string($dados["tipo_cotd"])."', 
				'".mysql_real_escape_string($dados["arquivo_cotd"])."', 
				'".mysql_real_escape_string($dados["legenda_cotd"])."', 
				'".mysql_real_escape_string($dados["texto_cotd"])."'
			)
		";
		return $this->query();
	}
	
	function getConteudo($cod_cotd) {
		$this->query = "
			SELECT
				cod_cotd,
				titulo_cotd,
				descr_cotd,
				flgativo_cotd,
				tipo_cotd,
				arquivo_cotd,
				legenda_cotd,
				texto_cotd
			FROM
				conteudo
			WHERE
				cod_cotd = '".mysql_real_escape_string($cod_cotd)."'
		";
		$this->query();
		return $this->qrdata;
	}
	
	function updConteudo($dados) {
		$this->query = "
			UPDATE conteudo SET
				titulo_cotd = '".mysql_real_escape_string($dados["titulo_cotd"])."',
				descr_cotd = '".mysql_real_escape_string($dados["descr_cotd"])."',
				flgativo_cotd = '".mysql_real_escape_string($dados["flgativo_cotd"])."',
				tipo_cotd = '".mysql_real_escape_string($dados["tipo_cotd"])."',
				arquivo_cotd = '".mysql_real_escape_string($dados["arquivo_cotd"])."',
				legenda_cotd = '".mysql_real_escape_string($dados["legenda_cotd"])."',
				texto_cotd = '".mysql_real_escape_string($dados["texto_cotd"])."'
			WHERE
				cod_cotd = '".mysql_real_escape_string($dados["cod_cotd"])."'
		";
		return $this->query();
	}
	
	function delConteudo($cod_cotd) {
		$this->query = "
			DELETE FROM 
				conteudo 
			WHERE
				cod_cotd = '".mysql_real_escape_string($cod_cotd)."'
		";
		return $this->query();
	}
	
	function getListaConteudosTemplate($cod_tmpl = false) {
		$this->query = "
			SELECT
				cotd.cod_cotd,
				cotd.titulo_cotd,
				cotd.descr_cotd,
				cotd.flgativo_cotd,
				tpco.ordem_tpco, 
				tpco.cod_cotd is not null as atribuido, 
				tpco.cod_tmpl
			FROM
				conteudo as cotd
			LEFT JOIN
				templateconteudo as tpco on (
					tpco.cod_cotd = cotd.cod_cotd 
					and tpco.cod_tmpl = '".mysql_real_escape_string($cod_tmpl)."'
				)
			ORDER BY 
		        atribuido,
		        tpco.ordem_tpco,
				cotd.titulo_cotd
		";
		$this->query();
		return $this->qrdata;
	}
	
	function addTemplate($dados) {
		$this->query = "
			INSERT INTO template (
				titulo_tmpl,
				descr_tmpl,
				flgativo_tmpl
			) VALUES (
				'".mysql_real_escape_string($dados["titulo_tmpl"])."',
				'".mysql_real_escape_string($dados["descr_tmpl"])."',
				'".mysql_real_escape_string($dados["flgativo_tmpl"])."'
			)
		";
		$this->query();
		return $this->getInsertId();
	}
	
	function addTemplateConteudo($dados) {
		$this->query = "
			INSERT INTO templateconteudo (
				cod_tmpl,
				cod_cotd,
				ordem_tpco
			) VALUES (
				'".mysql_real_escape_string($dados["cod_tmpl"])."', 
				'".mysql_real_escape_string($dados["cod_cotd"])."', 
				'".mysql_real_escape_string($dados["ordem_tpco"])."'
			)
		";
		return $this->query();
	}
	
	function getTemplate($cod_tmpl) {
		$this->query = "
			SELECT
				tmpl.cod_tmpl,
				tmpl.titulo_tmpl,
				tmpl.descr_tmpl,
				tmpl.flgativo_tmpl
			FROM
				template as tmpl
			WHERE
				tmpl.cod_tmpl  = '".mysql_real_escape_string($cod_tmpl)."'
		";
		$this->query();
		return $this->qrdata;
	}
	
	function updTemplate($dados) {
		$this->query = "
			UPDATE template SET 
				titulo_tmpl = '".mysql_real_escape_string($dados["titulo_tmpl"])."',
				descr_tmpl = '".mysql_real_escape_string($dados["descr_tmpl"])."',
				flgativo_tmpl = '".mysql_real_escape_string($dados["flgativo_tmpl"])."'
			WHERE
				cod_tmpl = '".mysql_real_escape_string($dados["cod_tmpl"])."'
		";
		return $this->query();
	}
	
	function delTemplateConteudos($cod_tmpl) {
		$this->query = "
			DELETE FROM 
				templateconteudo 
			WHERE
				cod_tmpl = '".mysql_real_escape_string($cod_tmpl)."'
		";
		return $this->query();
	}

	function addConteudosTemplate($dados) {
		$this->query = "
			INSERT INTO templateconteudo (
				cod_tmpl,
				cod_cotd,
				ordem_tpco
			) VALUES (
				'".mysql_real_escape_string($dados["cod_tmpl"])."', 
				'".mysql_real_escape_string($dados["cod_cotd"])."', 
				'".mysql_real_escape_string($dados["ordem_tpco"])."'
			)
		";
		return $this->query();
	}
	
	function delTemplate($cod_tmpl) {
		$this->query = "
			DELETE FROM 
				template 
			WHERE
				cod_tmpl = '".mysql_real_escape_string($cod_tmpl)."'
		";
		return $this->query();
	}
}

?>