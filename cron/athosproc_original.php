#!/opt/downloads/php-4.4.2/sapi/cli/php
<?php
//#!/opt/php-4.4.2/bin/php -q
error_reporting(E_ALL);

include "/var/www/html/i4t/class/dbclasses.class.php";

$oArquivo = new arquivo();
$oProposta = new proposta();

$aDadosUltimoLote909IN  = $oArquivo->getArquivo(ATHOSFILE909IN);
$aDadosUltimoLote906IN  = $oArquivo->getArquivo(ATHOSFILE906IN);
$aDadosUltimoLote906OUT = $oArquivo->getArquivo(ATHOSFILE906OUT);

$iRemessa909in  = $aDadosUltimoLote909IN[0]["ultimaremessa_arqu"];
$iRemessa906in  = $aDadosUltimoLote906IN[0]["ultimaremessa_arqu"];
$iRemessa906out = $aDadosUltimoLote906OUT[0]["ultimaremessa_arqu"];

echo "\n\n\n\n\n\n\n\n\n\n";
echo "--------------------------------------------------------------------------------\n";
echo "--------------------------------------------------------------------------------\n\n";

echo "--------------------------------------------------------------------------------\n";
echo "Verificando se há registros a serem enviados... Aguarde...\n";
echo "--------------------------------------------------------------------------------\n\n";

if ($iRemessa906in == $iRemessa906out) {
	$aListaProposta = $oProposta->getListaProposta(true);
	echo "Total de propostas encontradas: ".(int)@count($aListaProposta)."\n\n";
	if (is_array($aListaProposta) && (int)@count($aListaProposta) > 0) {
		echo "Processando registros... Aguarde...\n";
		
		$aDados906Out = $oArquivo->getArquivo(ATHOSFILE906OUT);
		$aConteudoUltimaRemessa = $oArquivo->getArquivoConteudo($aDados906Out[0]["cod_arqu"],((int)$aDados906Out[0]["ultimaremessa_arqu"]-1));
		
		$aConteudoEnviado = array();
		if ($aConteudoUltimaRemessa[0]["conteudo_arre"]) {
			$aConteudoEnviado = explode("\n",$aConteudoUltimaRemessa[0]["conteudo_arre"]);
		}
		
		foreach ($aConteudoEnviado as $sConteudoEnviado) {
			if (strlen($sConteudoEnviado) == 1362) {
				$aDadosConteudoEnviado[substr($sConteudoEnviado,2,9)] = $sConteudoEnviado;
			}
		}
		
		$iNovaRemessa = ((int)$aDados906Out[0]["ultimaremessa_arqu"])+1;
		$iNumRegistro = 0;

		foreach ($aListaProposta as $iDadoListaProposta => $aDadoListaProposta) {
			
			$aDadosProposta = $oProposta->getProposta($aDadoListaProposta["cod_ppst"]);
			$contadorProponente = 0;
			
			foreach ($aDadosProposta['proponentes'] as $aProponente) {
				
				$aDadosProponente       = $aProponente;
				$aDadosImovel 			= $aDadosProposta["imovel"];
				$aDadosVendedor 		= $aDadosProposta["vendedores"][0];
				$aDadosVendedorPF 		= $aDadosVendedor["vendfis"][0];
				$aDadosVendedorPFConj 	= $aDadosVendedor["vendfisconjuge"][0];
				$aDadosVendedorPJ 		= $aDadosVendedor["vendjur"][0];
				$aDadosVendedorPJSocio 	= $aDadosVendedorPJ["vendjursocios"][0];
				$aDadosDevSolid 		= $aDadosProponente["devsol"][0];
				
				echo "\n   * Proponente: ".$aDadosProponente['usuario'][0]["id_lstn"]."     Proposta: ".str_pad($aDadoListaProposta["cod_ppst"], 8, "0", STR_PAD_LEFT)."";
				
				$sBuffer  = (string)"01";														// 02 - Tipo de registro
				$sBuffer .= $oArquivo->formatNumber($aDadosProponente["id_lstn"], 9);				// 09 - Cód. Participante
				$sBuffer .= $oArquivo->formatNumber($aDadosProponente["cpf_ppnt"], 11); 			// 11 - CPF Participante
				$sBuffer .= $oArquivo->formatDate($aDadosProposta["data_ppst"]); 				// 08 - Data da proposta
				
				//*********** DADOS DO PARTICIPANTE ************
				if ($contadorProponente == "0") {
					if (!$aDadosProposta["dtaprovacao_ppst"] || (substr($aDadosProposta["dtaprovacao_ppst"],0,10) == date("Y-m-d"))) {
						$sBuffer .= $oArquivo->formatDate($aDadosProposta["dtapresdoc_ppst"]); 			// 08 - Data da apresentação dos documentos
						$sBuffer .= $oArquivo->formatDate($aDadosProposta["dtiniexigencia_ppst"]); 		// 08 - Data de início da exigência
						$sBuffer .= $oArquivo->formatDate($aDadosProposta["dtfimexigencia_ppst"]); 		// 08 - Data final da exigência
					} else {
						$sBuffer .= $oArquivo->formatString("",24);
					}
					if ($aDadosProposta["situacao_ppst"] == "6") {
						$sBuffer .= $oArquivo->formatDate($aDadosProposta["dtaprovacao_ppst"]); 		// 08 - Data de aprovacao da proposta
					} else {
						$sBuffer .= $oArquivo->formatString("",8);
					}
					if ($aDadosProposta["situacao_ppst"] == "9") {
						$sBuffer .= $oArquivo->formatDate($aDadosProposta["dtasscontrato_ppst"]); 		// 08 - Data de assinatura do contrato
					} else {
						$sBuffer .= $oArquivo->formatString("",8);
					}
					if ($aDadosProposta["situacao_ppst"] == "10") {
						$sBuffer .= $oArquivo->formatDate($aDadosProposta["dtokregistro_ppst"]); 		// 08 - Data de confirmacao do registro
						
					} else {
						$sBuffer .= $oArquivo->formatString("",8);
					}
				} else {
					$sBuffer .= $oArquivo->formatString("",48);
				}
				

				
				if ($aDadosProposta["situacao_ppst"] == "2" || $aDadosProposta["situacao_ppst"] == "3" || $aDadosProposta["situacao_ppst"] == "4" || $aDadosProposta["situacao_ppst"] == "5") {
					if ($aDadosProponente["vlfinsol_ppnt"] && $aDadosProponente["przfinsol_ppnt"]) {
						$sBuffer .= $oArquivo->formatNumber($aDadosProponente["vlfinsol_ppnt"],15,"0"," "); 		// 15 - Valor do fncto solicitado
						if (($aDadosProponente["przfinsol_ppnt"] == $aDadosProponente["przaprov_ppnt"])) {
							$sBuffer .= $oArquivo->formatNumber($aDadosProponente["vlprestaprov_ppnt"],3,"0"," "); 		// 03 - Prazo Solicitado
						} else {
							$sBuffer .= $oArquivo->formatString("",15);
						}
						$sBuffer .= $oArquivo->formatNumber($aDadosProponente["przfinsol_ppnt"],3,"0"," "); 		// 03 - Prazo Solicitado
						$sBuffer .= $oArquivo->formatNumber($aDadosProposta["valordevsinalsol_ppst"],15,"0"," "); 	// 15 - Valor Sinal Solicitado
					} elseif ($aDadosProponente["vlfinsol_ppnt"] && $aDadosProponente["vlprestsol_ppnt"]) {
						$sBuffer .= $oArquivo->formatNumber($aDadosProponente["vlfinsol_ppnt"],15,"0"," "); 		// 15 - Valor do fncto solicitado
						$sBuffer .= $oArquivo->formatNumber($aDadosProponente["vlprestsol_ppnt"],15,"0"," "); 	// 15 - Valor da prestacao solicitada
						if (($aDadosProponente["vlprestsol_ppnt"] == $aDadosProponente["vlprestaprov_ppnt"])) {
							$sBuffer .= $oArquivo->formatNumber($aDadosProponente["vlprestaprov_ppnt"],3,"0"," "); 		// 03 - Prazo Solicitado
						} else {
							$sBuffer .= $oArquivo->formatString("",3);
						}
						$sBuffer .= $oArquivo->formatNumber($aDadosProposta["valordevsinalsol_ppst"],15,"0"," "); 	// 15 - Valor Sinal Solicitado
					} else {
						$sBuffer .= $oArquivo->formatString("",48);
					}
				} elseif ($aDadosProposta["situacao_ppst"] == "9") {
					$sBuffer .= $oArquivo->formatNumber($aDadosProponente["vlfinsol_ppnt"],15,"0"," "); 		// 15 - Valor do fncto solicitado
					$sBuffer .= $oArquivo->formatNumber($aDadosProponente["vlprestaprov_ppnt"],15,"0"," "); 	// 15 - Valor da prestacao solicitada
					$sBuffer .= $oArquivo->formatNumber($aDadosProponente["przaprov_ppnt"],3,"0"," "); 	// 03 - Prazo Solicitado
					$sBuffer .= $oArquivo->formatNumber($aDadosProposta["valordevsinalsol_ppst"],15,"0"," "); 	// 15 - Valor Sinal Solicitado
				} else {
					$sBuffer .= $oArquivo->formatString("",48);
				}

				$sBuffer .= $oArquivo->formatString($aDadosProposta["indcancelamento_ppst"],2); // 02 - Indicador de cancelamento
				

//				$sBuffer .= $oArquivo->formatDate($aDadosImovel["dtaprovacao_imov"]); 			// 08 - Data de aprovacao do imovel

				//$aDadosProposta["flgaprovimovel_ppst"]

				if ($contadorProponente == "0" && $aDadosProposta["flgaprovimovel_ppst"] != "S") {
					$sBuffer .= $oArquivo->formatString($aDadosImovel["tipo_imov"],1); 				// 01 - Tipo de Imovel
					$sBuffer .= $oArquivo->formatNumber($aDadosImovel["area_imov"],8,"0",""); 		// 08 - Area do Imovel
					$sBuffer .= $oArquivo->formatString($aDadosImovel["tpconstrucao_imov"],2); 		// 02 - Tipo de construcao
					$sBuffer .= $oArquivo->formatString($aDadosImovel["tpcondominio_imov"],1); 		// 01 - Tipo de condomínio
					$sBuffer .= $oArquivo->formatNumber($aDadosImovel["qtsala_imov"],2,"0",""); 	// 02 - Qtde Salas
					$sBuffer .= $oArquivo->formatNumber($aDadosImovel["qtquarto_imov"],2,"0",""); 	// 02 - Qtde Quartos
					$sBuffer .= $oArquivo->formatNumber($aDadosImovel["qtbanh_imov"],2,"0",""); 	// 02 - Qtde Banheiros
					$sBuffer .= $oArquivo->formatNumber($aDadosImovel["qtgarag_imov"],2,"0",""); 	// 02 - Qtde garagens
					$sBuffer .= $oArquivo->formatNumber($aDadosImovel["qtpavim_imov"],2,"0",""); 	// 02 - Qtde pavimentos
					$sBuffer .= $oArquivo->formatNumber($aDadosImovel["qtdepemp_imov"],2,"0",""); 	// 02 - Qtde dep. empregados
					$sBuffer .= $oArquivo->formatString($aDadosImovel["estconserv_imov"],1); 		// 01 - Estado de conservacao do imovel
					$sBuffer .= $oArquivo->formatString($aDadosImovel["estconspred_imov"],1); 		// 01 - Estado de conservacao do predio
					
					if ($aDadosProposta["dtokregistro_ppst"] != "" && $aDadosProposta["situacao_ppst"] == "10") {
						$sBuffer .= $oArquivo->formatString($aDadosImovel["nomecartrgi_imov"],70); 		// 70 - Nome do Cartório (RGI)
						$sBuffer .= $oArquivo->formatString($aDadosImovel["nrmatrgi_imov"],10); 		// 10 - Num. Matric. RGI
						$sBuffer .= $oArquivo->formatString($aDadosImovel["nrlivrgi_imov"],10); 		// 10 - Num. Livro RGI
						$sBuffer .= $oArquivo->formatString($aDadosImovel["nrfolhrgi_imov"],10); 		// 10 - Num. Folhas Livro RGI
						$sBuffer .= $oArquivo->formatString($aDadosImovel["nrrgcompvend_imov"],10); 	// 10 - Num. Reg. Compra e Venda
						$sBuffer .= $oArquivo->formatString($aDadosImovel["nrrggar_imov"],10); 			// 10 - Num. Reg. Garantia
					} else {
						$sBuffer .= $oArquivo->formatString("",120);
					}
					
					$sBuffer .= $oArquivo->formatString($aDadosImovel["cod_logr"],3); 				// 03 - Tipo de Logradouro
					$sBuffer .= $oArquivo->formatString($aDadosImovel["endereco_imov"],50); 		// 50 - Nome do Logradouro
					$sBuffer .= $oArquivo->formatString($aDadosImovel["nrendereco_imov"],6); 		// 06 - Número do endereço
					$sBuffer .= $oArquivo->formatString($aDadosImovel["cpendereco_imov"],30); 		// 30 - Complemento
					$sBuffer .= $oArquivo->formatNumber($aDadosImovel["cod_bairro"],4,"0",""); 		// 04 - Código do Bairro
					$sBuffer .= $oArquivo->formatString($aDadosImovel["cep_imov"],8,"0"); 			// 08 - CEP do imóvel
					$sBuffer .= $oArquivo->formatNumber($aDadosImovel["cod_municipio"],4,"0",""); 	// 04 - Código do municipio
					$sBuffer .= $oArquivo->formatString($aDadosImovel["tpimposto_imov"],4); 		// 04 - Tipo de imposto
					
					if ($aDadosImovel["vlavaliacao_imov"] != "" && $aDadosImovel["dtavaliacao_imov"] != "") {
						$sBuffer .= $oArquivo->formatNumber($aDadosImovel["vlavaliacao_imov"],15,"0"," "); // 15 - Valor de avaliacao do imovel
						$sBuffer .= $oArquivo->formatDate($aDadosImovel["dtavaliacao_imov"]); 			// 08 - Data de avaliacao
						$sBuffer .= $oArquivo->formatDate($aDadosImovel["dtaprovacao_imov"]); 			// 08 - Data de aprovacao do imovel
						if ($aDadosImovel["dtaprovacao_imov"]) {
							$oProposta->setPropostaAprovImovel($aDadoListaProposta["cod_ppst"],'S');
						}
					} else {
						$sBuffer .= str_pad("",31," ",STR_PAD_LEFT);
					}

					$sBuffer .= $oArquivo->formatString($aDadosImovel["tpmoradia_imov"],1); 		// 01 - Tipo de moradia
					$sBuffer .= $oArquivo->formatString($aDadosImovel["terreo_imov"],1); 			// 01 - Imovel terreo
					$sBuffer .= $oArquivo->formatString($aDadosImovel["tmbdspcndop_imov"],1); 		// 01 - Imovel tombado
					$sBuffer .= $oArquivo->formatString($aDadosImovel["incomb_imov"],1); 			// 01 - Imovel incombustivel
					$sBuffer .= $oArquivo->formatString($aDadosImovel["ruralfav_imov"],1); 			// 01 - Imovel em area rural/favela
					$sBuffer .= $oArquivo->formatString($aDadosImovel["emconstr_imov"],1); 			// 01 - Imovel em construcao
					
				} else {
					$sBuffer .= $oArquivo->formatString("",292);
				}
				
				
				
				//*********** DADOS DO VENDEDOR (MEGA-IF) ************
				if (
					(
						$contadorProponente == "0"
					)
					&& 
					( // Proposta não pode estar aprovada
						(int)$aDadosProposta["situacao_ppst"] < 6
					)
					&&
					( // Dados do imóvel deverão estar preenchidos
						$aDadosImovel["vlavaliacao_imov"] != "" && $aDadosImovel["dtavaliacao_imov"] != ""
					)
					&&
					( // Dados básicos do vendedor devem estar completos
						$aDadosVendedor["nome_vend"] && 
						$aDadosVendedor["nick_vend"] && 
						$aDadosVendedor["cod_logr"] && 
						$aDadosVendedor["endereco_vend"] && 
						$aDadosVendedor["nrendereco_vend"] && 
						$aDadosVendedor["cod_bairro"] && 
						$aDadosVendedor["cep_vend"] && 
						$aDadosVendedor["cod_municipio"] && 
						$aDadosVendedor["telefone_vend"] && 
						$aDadosVendedor["nrcc_vend"] && 
						$aDadosVendedor["dvcc_vend"] && 
						$aDadosVendedor["nrag_vend"]
					) 
					&& 
					(
						( // Dados do vendedor PF devem estar completos caso seja PF
							(int)$aDadosVendedor["tipo_vend"] === 1 && 
							$aDadosVendedorPF["cpf_vfisica"] && 
							$aDadosVendedorPF["sexo_vfisica"] && 
							$aDadosVendedorPF["dtnascimento_vfisica"] && 
							$aDadosVendedorPF["cod_pais"] && 
							$aDadosVendedorPF["natur_vfisica"] && 
							$aDadosVendedorPF["cod_tpdoc"] && 
							$aDadosVendedorPF["nrrg_vfisica"] && 
							$aDadosVendedorPF["dtrg_vfisica"] && 
							$aDadosVendedorPF["orgrg_vfisica"] && 
							$aDadosVendedorPF["cod_estciv"] && 
							$aDadosVendedorPF["nomepai_vfisica"] && 
							$aDadosVendedorPF["nomemae_vfisica"] && 
							$aDadosVendedorPF["cod_prof"] && 
							$aDadosVendedorPF["vlrenda_vfisica"]
						) 
						|| 
						( // Dados do vendedor PJ devem estar completos caso seja PJ
							(int)$aDadosVendedor["tipo_vend"] === 2 && 
							$aDadosVendedorPJ["cnpj_vjur"] && 
							$aDadosVendedorPJ["isenpis_vjur"] && 
							$aDadosVendedorPJ["isencofins_vjur"] && 
							$aDadosVendedorPJ["isencsll_vjur"] && 
							$aDadosVendedorPJ["cod_cnae"] && 
							
							$aDadosVendedorPJSocio["nome_vjsoc"] && 
							$aDadosVendedorPJSocio["nick_vjsoc"] && 
							$aDadosVendedorPJSocio["cod_logr"] && 
							$aDadosVendedorPJSocio["endereco_vjsoc"] && 
							$aDadosVendedorPJSocio["nrendereco_vjsoc"] && 
							$aDadosVendedorPJSocio["cod_bairro"] && 
							$aDadosVendedorPJSocio["cep_vjsoc"] && 
							$aDadosVendedorPJSocio["cod_municipio"] && 
							$aDadosVendedorPJSocio["telefone_vjsoc"] && 
							$aDadosVendedorPJSocio["cpf_vjsoc"] && 
							$aDadosVendedorPJSocio["sexo_vjsoc"] && 
							$aDadosVendedorPJSocio["cod_pais"]
						)
					)
				) {
					//*********** DADOS DO VENDEDOR (DADOS COMUNS) ************
					$sBuffer .= $oArquivo->formatString($aDadosVendedor["nome_vend"],70); 			// 70 - Nome do vendedor
					$sBuffer .= $oArquivo->formatString($aDadosVendedor["nick_vend"],15); 			// 15 - Nome abreviado
					$sBuffer .= $oArquivo->formatString($aDadosVendedor["cod_logr"],3); 			// 03 - Tipo de logradouro
					$sBuffer .= $oArquivo->formatString($aDadosVendedor["endereco_vend"],40); 		// 40 - Endereço do vendedor
					$sBuffer .= $oArquivo->formatString($aDadosVendedor["nrendereco_vend"],6); 		// 06 - Número do Endereço do vendedor
					$sBuffer .= $oArquivo->formatString($aDadosVendedor["cpendereco_vend"],15); 	// 15 - Complemento do endereço
					$sBuffer .= $oArquivo->formatNumber($aDadosVendedor["cod_bairro"],4,"0",""); 	// 04 - Código do Bairro
					$sBuffer .= $oArquivo->formatString($aDadosVendedor["cep_vend"],8,"0"); 		// 08 - CEP do vendedor
					$sBuffer .= $oArquivo->formatNumber($aDadosVendedor["cod_municipio"],4,"0",""); // 04 - Código do municipio
					$sBuffer .= $oArquivo->formatString($aDadosVendedor["telefone_vend"],12); 		// 12 - Telefone do vendedor
					$sBuffer .= $oArquivo->formatString($aDadosVendedor["nrcc_vend"],12); 			// 12 - numero da CC
					$sBuffer .= $oArquivo->formatString($aDadosVendedor["dvcc_vend"],3); 			// 03 - dígito verificador da cc
					$sBuffer .= $oArquivo->formatNumber($aDadosVendedor["nrag_vend"],4,"0",""); 	// 04 - Número da agência
		
					//*********** DADOS DO VENDEDOR (PF) ************
					if ((int)$aDadosVendedor["tipo_vend"] === 1) {
						$sBuffer .= $oArquivo->formatNumber($aDadosVendedorPF["cpf_vfisica"],11,"0"," "); 		// 11 - CPF do vendedor
						$sBuffer .= $oArquivo->formatString($aDadosVendedorPF["sexo_vfisica"],1); 				// 01 - Sexo
						$sBuffer .= $oArquivo->formatDate($aDadosVendedorPF["dtnascimento_vfisica"]); 			// 08 - Data de Nascimento
						$sBuffer .= $oArquivo->formatNumber($aDadosVendedorPF["cod_pais"],6,"0"," "); 			// 06 - Nacionalidade
						$sBuffer .= $oArquivo->formatString($aDadosVendedorPF["natur_vfisica"],30); 			// 30 - Naturalidade
						$sBuffer .= $oArquivo->formatNumber($aDadosVendedorPF["cod_tpdoc"],2,"0"," "); 			// 02 - Tipo de Documento
						$sBuffer .= $oArquivo->formatString($aDadosVendedorPF["nrrg_vfisica"],11); 				// 11 - Nr. RG
						$sBuffer .= $oArquivo->formatDate($aDadosVendedorPF["dtrg_vfisica"]); 					// 08 - Dt. emissao RG
						$sBuffer .= $oArquivo->formatString($aDadosVendedorPF["orgrg_vfisica"],10); 			// 10 - Orgao emissor RG
						$sBuffer .= $oArquivo->formatNumber($aDadosVendedorPF["cod_estciv"],1,"0"," "); 		// 01 - Estado Civil
						$sBuffer .= $oArquivo->formatString($aDadosVendedorPFConj["nome_vfcj"],70); 			// 70 - Nome do conjuge
						$sBuffer .= $oArquivo->formatString($aDadosVendedorPF["nomepai_vfisica"],70); 			// 70 - Nome do pai
						$sBuffer .= $oArquivo->formatString($aDadosVendedorPF["nomemae_vfisica"],70); 			// 70 - Nome da mae
						$sBuffer .= $oArquivo->formatNumber($aDadosVendedorPF["cod_prof"],3,"0"," "); 			// 03 - Profissao
						$sBuffer .= $oArquivo->formatNumber($aDadosVendedorPF["vlrenda_vfisica"],21,"0"," "); 	// 21 - Renda
						$sBuffer .= $oArquivo->formatString($aDadosVendedorPF["nrinss_vfisica"],11); 			// 11 - Inscr. INSS
					} else {
						$sBuffer .= $oArquivo->formatString("",333);
					}
					
					//*********** DADOS DO VENDEDOR (PJ) ************
					if ((int)$aDadosVendedor["tipo_vend"] === 2) {
						$sBuffer .= $oArquivo->formatNumber($aDadosVendedorPJ["cnpj_vjur"],14,"0"," "); 		// 14 - CNPJ
						$sBuffer .= $oArquivo->formatString($aDadosVendedorPJ["isenpis_vjur"],1); 				// 01 - Isencao PIS
						$sBuffer .= $oArquivo->formatString($aDadosVendedorPJ["isencofins_vjur"],1); 			// 01 - Isencao Cofins
						$sBuffer .= $oArquivo->formatString($aDadosVendedorPJ["isencsll_vjur"],1); 				// 01 - Isencao CSLL
						$sBuffer .= $oArquivo->formatNumber($aDadosVendedorPJ["cod_cnae"],6,"0"," "); 			// 06 - Cod CNAE
		
						$sBuffer .= $oArquivo->formatString($aDadosVendedorPJSocio["nome_vjsoc"],70); 				// 70 - Nome do Sócio
						$sBuffer .= $oArquivo->formatString($aDadosVendedorPJSocio["nick_vjsoc"],15); 				// 15 - Nome Abrev.
						$sBuffer .= $oArquivo->formatString($aDadosVendedorPJSocio["cod_logr"],3); 					// 03 - Tipo de Endereco
						$sBuffer .= $oArquivo->formatString($aDadosVendedorPJSocio["endereco_vjsoc"],40); 			// 40 - Endereco
						$sBuffer .= $oArquivo->formatString($aDadosVendedorPJSocio["nrendereco_vjsoc"],6); 			// 06 - Nr. Endereco
						$sBuffer .= $oArquivo->formatString($aDadosVendedorPJSocio["cpendereco_vjsoc"],15); 		// 15 - Complemento Endereco
						$sBuffer .= $oArquivo->formatNumber($aDadosVendedorPJSocio["cod_bairro"],4,"0"," "); 		// 04 - Bairro
						$sBuffer .= $oArquivo->formatString($aDadosVendedorPJSocio["cep_vjsoc"],8,"0"); 			// 08 - Cep
						$sBuffer .= $oArquivo->formatNumber($aDadosVendedorPJSocio["cod_municipio"],4,"0"," "); 	// 04 - Municipio
						$sBuffer .= $oArquivo->formatString($aDadosVendedorPJSocio["telefone_vjsoc"],12); 			// 12 - Telefone
						$sBuffer .= $oArquivo->formatNumber($aDadosVendedorPJSocio["cpf_vjsoc"],11,"0"," "); 		// 11 - CPF
						$sBuffer .= $oArquivo->formatString($aDadosVendedorPJSocio["sexo_vjsoc"],1); 				// 01 - Sexo
						$sBuffer .= $oArquivo->formatNumber($aDadosVendedorPJSocio["cod_pais"],6,"0"," "); 			// 06 - Nacionalidade
					} else {
						$sBuffer .= $oArquivo->formatString("",218);
					}
				} else {
					$sBuffer .= $oArquivo->formatString("",747);
				}
				
				
				if ($aDadosDevSolid["nome_devsol"] && 
					$aDadosDevSolid["nick_devsol"] && 
					$aDadosDevSolid["cod_logr"] && 
					$aDadosDevSolid["endereco_devsol"] && 
					$aDadosDevSolid["nrendereco_devsol"] && 
					$aDadosDevSolid["cod_bairro"] && 
					$aDadosDevSolid["cep_devsol"] && 
					$aDadosDevSolid["cod_municipio"] && 
					$aDadosDevSolid["telefone_devsol"] && 
					$aDadosDevSolid["cpf_devsol"] && 
					$aDadosDevSolid["sexo_devsol"] && 
					$aDadosDevSolid["cod_pais"] && 
					(
						(int)$aDadosProposta["situacao_ppst"] < 6
					)
				) {
					$sBuffer .= $oArquivo->formatString($aDadosDevSolid["nome_devsol"],70); 				// 70 - Nome
					$sBuffer .= $oArquivo->formatString($aDadosDevSolid["nick_devsol"],15); 				// 15 - Abreviacao Nome
					$sBuffer .= $oArquivo->formatString($aDadosDevSolid["cod_logr"],3); 					// 03 - Tipo de logradouro
					$sBuffer .= $oArquivo->formatString($aDadosDevSolid["endereco_devsol"],40); 			// 40 - Endereco
					$sBuffer .= $oArquivo->formatString($aDadosDevSolid["nrendereco_devsol"],6); 			// 06 - Nr. Endereco
					$sBuffer .= $oArquivo->formatString($aDadosDevSolid["cpendereco_devsol"],15); 			// 15 - Compl. Endereco
					$sBuffer .= $oArquivo->formatNumber($aDadosDevSolid["cod_bairro"],4,"0"," "); 			// 04 - Bairro
					$sBuffer .= $oArquivo->formatString($aDadosDevSolid["cep_devsol"],8,"0"); 				// 08 - CEP
					$sBuffer .= $oArquivo->formatNumber($aDadosDevSolid["cod_municipio"],4,"0"," "); 		// 04 - Municipio
					$sBuffer .= $oArquivo->formatString($aDadosDevSolid["telefone_devsol"],12); 			// 12 - Telefone
					$sBuffer .= $oArquivo->formatNumber($aDadosDevSolid["cpf_devsol"],11,"0"," "); 			// 11 - CPF
					$sBuffer .= $oArquivo->formatString($aDadosDevSolid["sexo_devsol"],1); 					// 01 - Sexo
					$sBuffer .= $oArquivo->formatNumber($aDadosDevSolid["cod_pais"],6,"0"," "); 			// 06 - Nacionalidade
				} else {
					$sBuffer .= $oArquivo->formatString("",195);
				}
				
				//echo "\n\n".$sBuffer." --- ".strlen($sBuffer)."\n\n";
				$contadorProponente++;
				
				
				
				
				
				
				
				
				
				$aRegistrosEnviados = $oArquivo->getArquivoRegistro($aDadoListaProposta["cod_ppst"],$aDados906Out[0]["cod_arqu"],$aDadosProponente['usuario'][0]["id_lstn"]);
				
				if ($aRegistrosEnviados[0]["registro_arrg"] != $sBuffer) {
					$iNumRegistro++;
					$aNovosRegistros[$iNumRegistro]["registro"] = $sBuffer;
					$aNovosRegistros[$iNumRegistro]["cod_usua"] = $aDadoListaProposta["cod_usua"];
					$aNovosRegistros[$iNumRegistro]["cod_ppst"] = $aDadoListaProposta["cod_ppst"];
					echo "     ATUALIZADO - REGISTRO: ".$iNumRegistro;
				} else {
					echo "     NÃO ATUALIZADO";
				}
				
				
				
				
			}
			
		}
		
		if ($iNumRegistro > 0) {
			echo "\n\nNovos registros: ".$iNumRegistro.". Aguarde... Gerando remessa número ".str_pad($iNovaRemessa,6,"0",STR_PAD_LEFT)."\n\n";
			
			// Header do arquivo
			$sBufferHeader  = "00";
			$sBufferHeader .= "COFSP906";
			$sBufferHeader .= "ATHOSGESTÃO    ";
			$sBufferHeader .= date("dmY");
			$sBufferHeader .= $oArquivo->formatNumber($iNovaRemessa,6);
	
			/// Trailer do arquivo
			$sBufferTrailer  = "99";
			$sBufferTrailer .= $oArquivo->formatNumber(($iNumRegistro+2),10);
	
			$sBuffer = $sBufferHeader."\n";
			foreach ($aNovosRegistros as $iNovoRegistro => $aNovoRegistro) {
				$sBuffer .= $aNovoRegistro["registro"]."\n";
			}
			$sBuffer .= $sBufferTrailer;
			
			
			$oArquivo->addLogArquivo(ATHOSFILE906OUT,$iNovaRemessa,$iNumRegistro,0,($iNumRegistro+2),mktime(),$sBuffer);
			
			foreach ($aNovosRegistros as $iNovoRegistro => $aNovoRegistro) {
				unset($aTmpDadosArqReg);
				$aTmpDadosArqReg["cod_ppst"] = $aNovoRegistro["cod_ppst"];
				$aTmpDadosArqReg["cod_arqu"] = $aDados906Out[0]["cod_arqu"];
				$aTmpDadosArqReg["remessa_arre"] = $iNovaRemessa;
				$aTmpDadosArqReg["registro_arrg"] = $aNovoRegistro["registro"];
				$aTmpDadosArqReg["linha_arrg"] = $iNovoRegistro;
				$aTmpDadosArqReg["cod_usua"] = $aNovoRegistro["cod_usua"];
				$oArquivo->addArquivoRegistro($aTmpDadosArqReg);
				$oHistorico = new historico();
				$oHistorico->inserir($aNovoRegistro["cod_ppst"],date("Y-m-d H:i:s"),'Dados da proposta enviados à Previ.','1','');
			}
			
			$sArquivo906out = "ATHOSGESTAO.COFSP906.".str_pad(($iRemessa906out+1),6,"0",STR_PAD_LEFT).".ENT";
			echo "\n\nAguarde... Gerando arquivo ".$sArquivo906out."...";
			
			$resArquivoSaida = fopen(ATHOSFILEPATH.$sArquivo906out, "w+");
			fputs($resArquivoSaida,$sBuffer,strlen($sBuffer));
			fclose($resArquivoSaida);
			
			echo "OK\n\n";

			//$aNovosRegistros[$iNumRegistro]["cod_usua"] = $aDadoListaProposta["cod_usua"];
		} else {
			echo "\n\nNão há novos registros. Remessa não gerada.\n\n";
		}
	}
} else {
	echo "O arquivo de resultado da remessa ".str_pad($iRemessa906out,6,"0",STR_PAD_LEFT)." ainda não foi retornado.\n\n";
}

echo "\n\n\n";

?>