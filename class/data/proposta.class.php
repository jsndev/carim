<?
/*
class proposta extends database {
	
	function getListaProposta() {
		$this->query = "
			SELECT
				ppst.cod_ppst,
				ppst.data_ppst,
				ppst.situacao_ppst,
				ppst.proponente_ppst,
				ppst.dtapresdoc_ppst,
				ppst.dtiniexigencia_ppst,
				ppst.dtfimexigencia_ppst,
				ppst.dtaprovacao_ppst,
				ppst.dtasscontrato_ppst,
				ppst.dtokregistro_ppst,
				ppst.vlfinsol_ppst,
				ppst.przfinsol_ppst,
				ppst.valordevsinalsol_ppst,
				ppst.pricesac_ppst,
				ppst.valorcompra_ppst,
				ppst.valorfgts_ppst,
				ppst.taxajuros_ppst,
				ppst.valorseguro_ppst,
				ppst.valormanutencao_ppst,
				ppst.vlprestsol_ppst,
				ppst.indcancelamento_ppst,
				ppst.valorboletoaval_ppst,
				ppst.flgboletoavalpago_ppst,
				ppst.despachante_ppst,
				ppst.dtagend_asscontr_ppst,
				ppst.flgrespostavalor_ppst,
				ppst.dtpagtoboleto_ppst,
				ppst.flgaprovacaoprevi_ppst,
				ppst.dtremessacontrato_ppst,
							
				ppnt.cpf_ppnt,
				ppnt.dtnascimento_ppnt,
				ppnt.endereco_ppnt,
				ppnt.nrendereco_ppnt,
				ppnt.cpendereco_ppnt,
				ppnt.cep_ppnt,
				ppnt.cod_proponente,
				ppnt.cod_logr,
				ppnt.cod_bairro,
				ppnt.cod_uf,
				ppnt.cod_municipio,
				ppnt.cod_estciv,
				ppnt.telefone_ppnt,

				usua.cod_usua,
				usua.nome_usua,
				usua.email_usua,
				usua.level_usua,
				usua.id_lstn,
				usua.flgstatus_usua,
				
				lstn.vlmaxfinan,
				lstn.parcmaxfinan,
				lstn.przmaxfinan,
				lstn.vlaprovado,
				lstn.parcaprovada,
				lstn.przaprovado,
				lstn.vlentraprovado,
				lstn.status
			FROM
				proposta as ppst,
				usuario as usua,
				listadenomes as lstn,
				proponente as ppnt
			WHERE
				lstn.id_lstn = usua.id_lstn and 
				usua.cod_usua = ppnt.cod_proponente and 
				ppnt.cod_proponente = ppst.proponente_ppst and 
				lstn.status NOT IN ('E','A') and 
				ppst.situacao_ppst >= 3 and 
				ppst.situacao_ppst < 12 
			ORDER BY
				usua.id_lstn
		";
		$this->query();
		print $this->query.'<hr>';
		return $this->qrdata;
	}

	function getPropostaProponente($cod_ppst) {
		// gambi para testar N proponentes
		$aTmpData = array();
		$aTmpData[] = array( "cod_ppnt" => '54', "nome_ppnt" => 'Fulano',         "matricula_ppnt" => '123456', "status_ppnt" => '1' );
		$aTmpData[] = array( "cod_ppnt" => '45', "nome_ppnt" => 'Siclano',        "matricula_ppnt" => '654321', "status_ppnt" => '2' );
		$aTmpData[] = array( "cod_ppnt" => '57', "nome_ppnt" => 'Beltrano',       "matricula_ppnt" => '159159', "status_ppnt" => '1' );
		$aTmpData[] = array( "cod_ppnt" => '31', "nome_ppnt" => 'Zeca Pagodinho', "matricula_ppnt" => '753753', "status_ppnt" => '3' );
		return $aTmpData;
	}
	
	function getPropostaImovel($cod_ppst) {
		$this->query = "
			SELECT
				cod_ppst,
				tipo_imov,
				flgaprovacao_imov,
				area_imov,
				tpconstrucao_imov,
				tpcondominio_imov,
				qtsala_imov,
				qtquarto_imov,
				qtbanh_imov,
				qtgarag_imov,
				qtpavim_imov,
				qtdepemp_imov,
				estconserv_imov,
				estconspred_imov,
				nomecartrgi_imov,
				nrmatrgi_imov,
				nrlivrgi_imov,
				nrfolhrgi_imov,
				nrrgcompvend_imov,
				nrrggar_imov,
				endereco_imov,
				nrendereco_imov,
				cpendereco_imov,
				cep_imov,
				tpimposto_imov,
				vlavaliacao_imov,
				dtavaliacao_imov,
				dtaprovacao_imov,
				imovel.cod_logr,
				imovel.cod_bairro,
				imovel.cod_uf,
				imovel.cod_municipio,
				tpmoradia_imov,
				terreo_imov,
				tmbdspcndop_imov,
				incomb_imov,
				ruralfav_imov,
				emconstr_imov,
				
				bairro.nome_bairro as bairro_imov,
				logradouro.desc_logr as logr_imov,
				uf.nome_uf as uf_imov,
				municipio.nome_municipio as municipio_imov,
				
				ptipo.titulo_param as descr_tipo_imov,
				pcons.titulo_param as descr_tpconstrucao_imov,
				pcond.titulo_param as descr_tpcondominio_imov,
				pestconsi.titulo_param as descr_estconserv_imov,
				pestconsp.titulo_param as descr_estconspred_imov,
				pimpost.titulo_param as descr_tpimposto_imov,
				pterreo.titulo_param as descr_terreo_imov,
				pmorad.titulo_param as descr_tpmoradia_imov
			FROM
				imovel
			LEFT JOIN
				bairro ON imovel.cod_bairro = bairro.cod_bairro
			LEFT JOIN
				logradouro ON imovel.cod_logr = logradouro.cod_logr
			LEFT JOIN
				municipio ON imovel.cod_uf = municipio.cod_uf AND imovel.cod_municipio = municipio.cod_municipio
			LEFT JOIN
				uf ON imovel.cod_uf = uf.cod_uf
			LEFT JOIN
				parametro as ptipo ON ptipo.tipo_param = 'tipos de imovel' AND imovel.tipo_imov = ptipo.valor_param
			LEFT JOIN
				parametro as pcons ON pcons.tipo_param = 'tipos de construcao' AND imovel.tpconstrucao_imov = pcons.valor_param
			LEFT JOIN
				parametro as pcond ON pcond.tipo_param = 'tipos de condominio' AND imovel.tpcondominio_imov = pcond.valor_param
			LEFT JOIN
				parametro as pestconsi ON pestconsi.tipo_param = 'tipos de conservacao' AND imovel.estconserv_imov = pestconsi.valor_param
			LEFT JOIN
				parametro as pestconsp ON pestconsp.tipo_param = 'tipos de conservacao' AND imovel.estconspred_imov = pestconsp.valor_param
			LEFT JOIN
				parametro as pimpost ON pimpost.tipo_param = 'tipos de imposto' AND imovel.tpimposto_imov = pimpost.valor_param
			LEFT JOIN
				parametro as pterreo ON pterreo.tipo_param = 'terreo' AND imovel.terreo_imov = pterreo.valor_param
			LEFT JOIN
				parametro as pmorad ON pmorad.tipo_param = 'tipos de moradia' AND imovel.tpmoradia_imov = pmorad.valor_param
			WHERE
				cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		$this->query();
		//print $this->query;
		return $this->qrdata;
	}
	
	function getPropostaVendedor($cod_ppst) {
		/*
		$this->query = "
			SELECT
				cod_ppst,
				tipo_vend,
				nome_vend,
				nick_vend,
				endereco_vend,
				nrendereco_vend,
				cep_vend,
				telefone_vend,
				nrcc_vend,
				dvcc_vend,
				nrag_vend,
				vendedor.cod_bairro,
				vendedor.cod_logr,
				vendedor.cod_uf,
				vendedor.cod_municipio,
				cpendereco_vend,
				
				bairro.nome_bairro as bairro_vend,
				logradouro.desc_logr as logr_vend,
				uf.nome_uf as uf_vend,
				municipio.nome_municipio as municipio_vend,
				ptipo.titulo_param as descr_tipo_vend
				
			FROM
				vendedor
			LEFT JOIN
				bairro ON vendedor.cod_bairro = bairro.cod_bairro
			LEFT JOIN
				logradouro ON vendedor.cod_logr = logradouro.cod_logr
			LEFT JOIN
				municipio ON vendedor.cod_uf = municipio.cod_uf AND vendedor.cod_municipio = municipio.cod_municipio
			LEFT JOIN
				uf ON vendedor.cod_uf = uf.cod_uf
			LEFT JOIN
				parametro as ptipo ON ptipo.tipo_param = 'tipos de vendedor' AND vendedor.tipo_vend = ptipo.valor_param
			WHERE
				cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		$this->query();
		$aTmpData = $this->qrdata;
		if (!$aTmpData[0]["tipo_vend"]) {
			return false;
		}
		
		if (is_array($aTmpData) && @count($aTmpData) > 0) {
			foreach($aTmpData as $key=>$value){
				if ((int)$aTmpData[$key]["tipo_vend"] == 1) { // Caso PF
					// Dados do vendedor PF ------------------------------------------
					$aTmpDataPf = $this->getPropostaVendedorPF($cod_ppst);
					$aTmpData[$key]["PF"] = $aTmpDataPf[0];
				} elseif ((int)$aTmpData[$key]["tipo_vend"] == 2) { // Caso PJ
					// Dados do vendedor PJ ------------------------------------------
					$aTmpDataPj = $this->getPropostaVendedorPJ($cod_ppst);
					$aTmpData[$key]["PJ"] = $aTmpDataPj[0];
					// Dados do(s) sócio(s) PJ ---------------------------------------
					$aTmpDataPjSocio = $this->getPropostaVendedorPJSocio($cod_ppst);
					$aTmpData[$key]["PJSocio"] = $aTmpDataPjSocio;
				}
			}
		}
		* /
		// gambi para testar N vendedores
		$aTmpData = array();
		$aTmpData[] = array( "cod_vend" => '54', "nome_vend" => 'Fulano da Silva Sauro', "nick_vend" => 'FSS', "tipo_vend" => '1' );
		$aTmpData[] = array( "cod_vend" => '45', "nome_vend" => 'Siclano Severino',      "nick_vend" => 'SS',  "tipo_vend" => '1'  );
		$aTmpData[] = array( "cod_vend" => '57', "nome_vend" => 'Beltrano Augusto',      "nick_vend" => 'BA',  "tipo_vend" => '2'  );
		return $aTmpData;

	}
	
	function getPropostaVendedorPF($cod_ppst) {
		$this->query = "
			SELECT
				cpf_vfisica,
				sexo_vfisica,
				dtnascimento_vfisica,
				natur_vfisica,
				nrrg_vfisica,
				dtrg_vfisica,
				orgrg_vfisica,
				nomeconj_vfisica,
				nomepai_vfisica,
				nomemae_vfisica,
				vlrenda_vfisica,
				nrinss_vfisica,
				cod_ppst,
				vendfis.cod_pais,
				vendfis.cod_tpdoc,
				vendfis.cod_prof,
				vendfis.cod_estciv,
				
				pais.nome_pais as pais_vfisica,
				tipodoc.desc_tpdoc as tpdoc_vfisica,
				estadocivil.desc_estciv as estciv_vfisica,
				profissao.desc_prof as prof_vfisica,
				psexo.titulo_param as descr_sexo_vfisica
				
			FROM
				vendfis
			LEFT JOIN
				pais ON pais.cod_pais = vendfis.cod_pais
			LEFT JOIN
				tipodoc ON tipodoc.cod_tpdoc = vendfis.cod_tpdoc
			LEFT JOIN
				estadocivil ON estadocivil.cod_estciv = vendfis.cod_estciv
			LEFT JOIN
				profissao ON profissao.cod_prof = vendfis.cod_prof
			LEFT JOIN
				parametro as psexo ON psexo.tipo_param = 'tipos de sexo' AND vendfis.sexo_vfisica = psexo.valor_param
			WHERE
				cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		$this->query();
		return $this->qrdata;
	}
	function getPropostaVendedorPJ($cod_ppst) {
		$this->query = "
			SELECT
				cod_ppst,
				cnpj_vjur,
				isenpis_vjur,
				isencofins_vjur,
				isencsll_vjur,
				vendjur.cod_cnae,
				cnae.desc_cnae as cnae_vjur
			FROM
				vendjur
			LEFT JOIN
				cnae ON cnae.cod_cnae = vendjur.cod_cnae
			WHERE
				cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		$this->query();
		return $this->qrdata;
	}
	function getPropostaVendedorPJSocio($cod_ppst) {
		$this->query = "
			SELECT
				cod_ppst,
				cod_vjsoc,
				nome_vjsoc,
				nick_vjsoc,
				endereco_vjsoc,
				nrendereco_vjsoc,
				cpendereco_vjsoc,
				cep_vjsoc,
				telefone_vjsoc,
				cpf_vjsoc,
				vendjursocio.sexo_vjsoc,
				vendjursocio.cod_pais,
				vendjursocio.cod_logr,
				vendjursocio.cod_bairro,
				vendjursocio.cod_uf,
				vendjursocio.cod_municipio,
				
				pais.nome_pais as pais_vjsoc,
				psexo.titulo_param as descr_sexo_vjsoc,
				bairro.nome_bairro as bairro_vjsoc,
				logradouro.desc_logr as logr_vjsoc,
				uf.nome_uf as uf_vjsoc,
				municipio.nome_municipio as municipio_vjsoc

			FROM
				vendjursocio
			LEFT JOIN
				pais ON pais.cod_pais = vendjursocio.cod_pais
			LEFT JOIN
				bairro ON vendjursocio.cod_bairro = bairro.cod_bairro
			LEFT JOIN
				logradouro ON vendjursocio.cod_logr = logradouro.cod_logr
			LEFT JOIN
				municipio ON vendjursocio.cod_uf = municipio.cod_uf AND vendjursocio.cod_municipio = municipio.cod_municipio
			LEFT JOIN
				uf ON vendjursocio.cod_uf = uf.cod_uf
			LEFT JOIN
				parametro as psexo ON psexo.tipo_param = 'tipos de sexo' AND vendjursocio.sexo_vjsoc = psexo.valor_param
			WHERE
				cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		$this->query();
		return $this->qrdata;
	}
	function getPropostaDevedorSolidario($cod_ppst) {
		$this->query = "
			SELECT
				cod_ppst,
				nome_devsol,
				nick_devsol,
				endereco_devsol,
				nrendereco_devsol,
				cpendereco_devsol,
				cep_devsol,
				telefone_devsol,
				cpf_devsol,

				devsol.cod_bairro,
				devsol.cod_uf,
				devsol.cod_municipio,
				devsol.cod_logr,
				devsol.sexo_devsol,
				devsol.cod_pais,

				pais.nome_pais as pais_devsol,
				psexo.titulo_param as descr_sexo_devsol,
				bairro.nome_bairro as bairro_devsol,
				logradouro.desc_logr as logr_devsol,
				uf.nome_uf as uf_devsol,
				municipio.nome_municipio as municipio_devsol

			FROM
				devsol
			LEFT JOIN
				parametro as psexo ON psexo.tipo_param = 'tipos de sexo' AND devsol.sexo_devsol = psexo.valor_param
			LEFT JOIN
				pais ON pais.cod_pais = devsol.cod_pais
			LEFT JOIN
				bairro ON devsol.cod_bairro = bairro.cod_bairro
			LEFT JOIN
				logradouro ON devsol.cod_logr = logradouro.cod_logr
			LEFT JOIN
				municipio ON devsol.cod_uf = municipio.cod_uf AND devsol.cod_municipio = municipio.cod_municipio
			LEFT JOIN
				uf ON devsol.cod_uf = uf.cod_uf
			WHERE
				cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		$this->query();
		return $this->qrdata;
	}
	
	function getProposta($cod_ppst) {
		$this->query = "
			SELECT
				ppst.cod_ppst,
				ppst.data_ppst,
				ppst.situacao_ppst,
				ppst.proponente_ppst,
				ppst.dtapresdoc_ppst,
				ppst.dtiniexigencia_ppst,
				ppst.dtfimexigencia_ppst,
				ppst.dtaprovacao_ppst,
				ppst.dtasscontrato_ppst,
				ppst.dtokregistro_ppst,
				ppst.vlfinsol_ppst,
				ppst.przfinsol_ppst,
				ppst.valordevsinalsol_ppst,
				ppst.pricesac_ppst,
				ppst.valorcompra_ppst,
				ppst.valorfgts_ppst,
				ppst.taxajuros_ppst,
				ppst.valorseguro_ppst,
				ppst.valormanutencao_ppst,
				ppst.vlprestsol_ppst,
				ppst.indcancelamento_ppst,
				ppst.valorboletoaval_ppst,
				ppst.flgboletoavalpago_ppst,
				ppst.despachante_ppst,
				ppst.dtagend_asscontr_ppst, 
				ppst.flgrespostavalor_ppst,
				ppst.dtpagtoboleto_ppst,
				ppst.flgaprovacaoprevi_ppst,
				ppst.dtremessacontrato_ppst,
				
				ppnt.cpf_ppnt,
				ppnt.dtnascimento_ppnt,
				ppnt.endereco_ppnt,
				ppnt.nrendereco_ppnt,
				ppnt.cpendereco_ppnt,
				ppnt.cep_ppnt,
				ppnt.cod_proponente,
				ppnt.cod_logr,
				ppnt.cod_bairro,
				ppnt.cod_uf,
				ppnt.cod_municipio,
				ppnt.cod_estciv,
				ppnt.telefone_ppnt,
				bairro.nome_bairro as bairro_ppnt,
				estadocivil.desc_estciv as estciv_ppnt,
				logradouro.desc_logr as logr_ppnt,
				uf.nome_uf as uf_ppnt,
				municipio.nome_municipio as municipio_ppnt,
				
				usua.cod_usua,
				usua.nome_usua,
				usua.email_usua,
				usua.level_usua,
				usua.id_lstn,
				usua.flgstatus_usua,
				
				lstn.vlmaxfinan,
				lstn.parcmaxfinan,
				lstn.przmaxfinan,
				lstn.vlaprovado,
				lstn.parcaprovada,
				lstn.przaprovado,
				lstn.vlentraprovado,
				lstn.status
			FROM
				proposta as ppst,
				usuario as usua,
				listadenomes as lstn,
				proponente as ppnt
			LEFT JOIN
				bairro ON ppnt.cod_bairro = bairro.cod_bairro
			LEFT JOIN
				estadocivil ON ppnt.cod_estciv = estadocivil.cod_estciv
			LEFT JOIN
				logradouro ON ppnt.cod_logr = logradouro.cod_logr
			LEFT JOIN
				municipio ON ppnt.cod_municipio = municipio.cod_municipio
			LEFT JOIN
				uf ON ppnt.cod_uf = uf.cod_uf
			WHERE
				lstn.id_lstn = usua.id_lstn and 
				usua.cod_usua = ppnt.cod_proponente and 
				ppnt.cod_proponente = ppst.proponente_ppst and 
				ppst.cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		$this->query();
		$aTmpRetorno = $this->qrdata[0];
		
		$aTmpDadoProponente		    = $this->getPropostaProponente($cod_ppst);
		$aTmpDadoImovel 			    = $this->getPropostaImovel($cod_ppst);
		$aTmpDadoVendedor 			  = $this->getPropostaVendedor($cod_ppst);
		$aTmpDadoDevedorSolidario = $this->getPropostaDevedorSolidario($cod_ppst);
		
		// gambi p/ exibir algumas vagas de garagem ----------------- //
		$aTmpDadoImovel[0]["vagas_garagem_imov"] = 0;
		/*
		$aTmpDadoImovel[0]["lista_vagas_garagem"] = array();
		$aTmpDadoImovel[0]["lista_vagas_garagem"][] = array(
			'tipo_vaga_imov'       => 'C', //   I / C
			'local_vaga_imov'      => 'T', //   T / 1 / 2 / I
			'area_util_vaga_imov'  => '4000', 
			'area_comum_vaga_imov' => '5000', 
			'area_total_vaga_imov' => '6000',
			'fracao_vaga_imov'     => '55', 
		);
		$aTmpDadoImovel[0]["lista_vagas_garagem"][] = array(
			'tipo_vaga_imov'       => 'I', //   I / C
			'local_vaga_imov'      => '1', //   T / 1 / 2 / I
			'area_util_vaga_imov'  => '2000', 
			'area_comum_vaga_imov' => '4000', 
			'area_total_vaga_imov' => '6500', 
			'fracao_vaga_imov'     => '25',
			'num_contrib_vaga_imov'  => '654545',
			'num_reg_vaga_imov'      => '445',
			'num_matr_vaga_imov'     => '57665',
			'num_oficio_vaga_imov'   => '453',
			'local_oficio_vaga_imov' => 'sdfsdsgdsg',
		);
		$aTmpDadoImovel[0]["lista_vagas_garagem"][] = array(
			'tipo_vaga_imov'       => 'C', //   I / C
			'local_vaga_imov'      => 'I', //   T / 1 / 2 / I
			'area_util_vaga_imov'  => '100', 
			'area_comum_vaga_imov' => '300', 
			'area_total_vaga_imov' => '900',
			'fracao_vaga_imov'     => '20',
		);
		* /
		// ------------------------------------------------------------ //
		
		$aTmpRetorno["proponente"]       = $aTmpDadoProponente;
		$aTmpRetorno["imovel"] 				   = $aTmpDadoImovel[0];
		$aTmpRetorno["vendedor"] 			   = $aTmpDadoVendedor;
		$aTmpRetorno["devedorsolidario"] = $aTmpDadoDevedorSolidario[0];
		
		return $aTmpRetorno;
	}
	
	function getPropostaUsuario($cod_usua) {
		$this->query = "
			SELECT
				max(data_ppst) as dt_ppst,
				cod_ppst
			FROM
				proposta
			WHERE
				proponente_ppst = '".mysql_real_escape_string($cod_usua)."'
			GROUP BY
				data_ppst
		";
		$this->query();
		return $this->qrdata;
	}
	
	function setPropostaRespostaValor($cod_ppst,$resposta = "N") {
		$this->query = "
			UPDATE
				proposta
			SET
				flgrespostavalor_ppst = '".mysql_real_escape_string($resposta)."'
			WHERE
				cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		return $this->query();
	}
	
	function setPropostaAprovacaoPrevi($cod_ppst,$resposta = "N") {
		$this->query = "
			UPDATE
				proposta
			SET
				flgaprovacaoprevi_ppst = '".mysql_real_escape_string($resposta)."'
			WHERE
				cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		return $this->query();
	}

	
	function newPropostaUsuario($cod_usua) {
		$this->query = "
			INSERT INTO proponente (cod_proponente)
			VALUES ('".mysql_real_escape_string($cod_usua)."')
		";
		$this->query();
		$this->query = "
			INSERT INTO proposta (proponente_ppst, situacao_ppst)
			VALUES ('".mysql_real_escape_string($cod_usua)."', 1)
		";
		$this->query();
		return $this->getInsertId();
	}
	
}
*/
?>