#!/opt/downloads/php-4.4.2/sapi/cli/php
<?php
//#!/opt/php-4.4.2/bin/php -q
error_reporting(E_ALL);
include "../class/dbclasses.class.php";

$oArquivo = new arquivo();
$oProposta = new proposta();

$aDadosUltimoLote909IN  = $oArquivo->getArquivo(ATHOSFILE909IN);
$aDadosUltimoLote906IN  = $oArquivo->getArquivo(ATHOSFILE906IN);
$aDadosUltimoLote906OUT = $oArquivo->getArquivo(ATHOSFILE906OUT);

$iRemessa909in  = $aDadosUltimoLote909IN[0]["ultimaremessa_arqu"];
$iRemessa906in  = $aDadosUltimoLote906IN[0]["ultimaremessa_arqu"];
$iRemessa906out = $aDadosUltimoLote906OUT[0]["ultimaremessa_arqu"];
$soma=0;
$somavend=0;
$somavendexc=0;

$msgRetorno="";

$msgRetorno.="\n\n\n\n\n\n\n\n\n\n<br>";
$msgRetorno.="--------------------------------------------------------------------------------\n<br>";
$msgRetorno.="--------------------------------------------------------------------------------\n\n<br><br>";

$msgRetorno.="--------------------------------------------------------------------------------\n<br>";
$msgRetorno.="Verificando se há registros a serem enviados... Aguarde...\n<br>";
$msgRetorno.="--------------------------------------------------------------------------------\n\n<br><br>";

if ($iRemessa906in == $iRemessa906out) {
	$aListaProposta = $oProposta->getListaProposta(true);
	$msgRetorno.="Total de propostas encontradas: ".(int)@count($aListaProposta)."\n\n<br>";
	if (is_array($aListaProposta) && (int)@count($aListaProposta) > 0) {
		$msgRetorno.="Processando registros... Aguarde...\n<br>";
		
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
$i=1;
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

                $ppst[$i]=$aDadoListaProposta["cod_ppst"];
                $msgRetorno.="<br><br>PROPOSTA:".$ppst[$i];
                $msgRetorno.="<br><br>PROPOSTA ANTERIOR:".$ppst[$i-1];
                $msgRetorno.="<br>SITUACAO:".$aDadosProposta["situacao_ppst"];
				$msgRetorno.='>>>>>>>>'.$oArquivo->formatString($aDadosProposta["indcancelamento_ppst"],2);
				
				$db->query = "Select SUM(vlcompra_ppnt) AS vlcompra,SUM(vlentrada_ppnt) AS vlentrada from proponente where cod_ppst='".$ppst[$i]."'";
				$db->query();
				if($db->qrcount>0)
				{
						$valorcompra=$db->qrdata[0]['vlcompra'];
						$vlentrada=$db->qrdata[0]['vlentrada'];
				}

				if((($ppst[$i]==$ppst[$i-1]) && $aDadosProposta["situacao_ppst"] >= "9" ) ||$aDadosProponente["listadenomes"][0]["status_pp"]=="I")
				{
					$msgRetorno.="";
					$i++;
				}else{
				
				$i++;
				

				
				$msgRetorno.="<br>\n   * Proponente: ".$aDadosProponente['usuario'][0]["id_lstn"]."     Proposta: ".str_pad($aDadoListaProposta["cod_ppst"], 8, "0", STR_PAD_LEFT)."";
				
				$sBuffer  = (string)"01";														// 02 - Tipo de registro
				$sBuffer .= $oArquivo->formatNumber($aDadosProponente["listadenomes"][0]["id_lstn"], 9);				// 09 - Cód. Participante
				$sBuffer .= $oArquivo->formatNumber($aDadosProponente["cpf_ppnt"], 11); 			// 11 - CPF Participante
				$sBuffer .= $oArquivo->formatDate($aDadosProposta["data_ppst"]); 				// 08 - Data da proposta
				
				//*********** DADOS DO PARTICIPANTE ************
				
				if ($contadorProponente == "0") {
					if (!$aDadosProposta["dtaprovacao_ppst"] || (substr($aDadosProposta["dtaprovacao_ppst"],0,10) == date("Y-m-d"))) {
						$msgRetorno.="<br>DATA:".$aDadosProposta["dtapresdoc_ppst"];
						$sBuffer .= $oArquivo->formatDate($aDadosProposta["dtapresdoc_ppst"]); 			// 08 - Data da apresentação dos documentos
						$sBuffer .= $oArquivo->formatDate($aDadosProposta["dtiniexigencia_ppst"]); 		// 08 - Data de início da exigência
						$sBuffer .= $oArquivo->formatDate($aDadosProposta["dtfimexigencia_ppst"]); 		// 08 - Data final da exigência
						if($aDadosProposta["dtfimexigencia_ppst"]!='')
						{
							$db->query="Update proposta set DTINIEXIGENCIA_PPST=NULL, DTFIMEXIGENCIA_PPST=NULL where cod_ppst='".$aDadoListaProposta["cod_ppst"]."'";
							//$msgRetorno.=$db->query;
							$db->query();
						}
					} else {
						$sBuffer .= $oArquivo->formatString("",24);
					}
					if ($aDadosProposta["situacao_ppst"] >= "6" && $aDadosProposta["situacao_ppst"] < 10 ) {
						$sBuffer .= $oArquivo->formatDate($aDadosProposta["dtaprovacao_ppst"]); 		// 08 - Data de aprovacao da proposta
					} else {
						$sBuffer .= $oArquivo->formatString("",8);
					}
					if ($aDadosProposta["situacao_ppst"] >= "7" && $aDadosProposta["situacao_ppst"] < 10 ) {
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
				
				$db->query = "Select vlaprovado, przaprovado, parcaprovada, aprovprevi from listadenomes where id_lstn='".$aDadosProponente["listadenomes"][0]["id_lstn"]."'";
				$db->query();
				if($db->qrcount>0)
				{
						$vlaprovado=$db->qrdata[0]['vlaprovado'];
						$przaprovado=$db->qrdata[0]['przaprovado'];
						$parcaprovada=$db->qrdata[0]['parcaprovada'];
						$aprovprevi=$db->qrdata[0]['aprovprevi'];
				}
			//echo	$aDadosProponente["vlfinsol_ppnt"];
			if ($aprovprevi != "S" && $aDadosProponente["vlfinsol_ppnt"]!="" ) {
						if($aDadosProponente["vlfinsol_ppnt"]!=''){
						$sBuffer .= $oArquivo->formatNumber($aDadosProponente["vlfinsol_ppnt"],15,"0"," ");}
						else{
						$sBuffer .= $oArquivo->formatString("",15);} // 15 - Valor do fncto solicitado
						
						if($aDadosProponente["vlprestsol_ppnt"]!=''){
						$sBuffer .= $oArquivo->formatNumber($aDadosProponente["vlprestsol_ppnt"],15,"0"," "); }
						else{
						$sBuffer .= $oArquivo->formatString("",15);}		// 03 - Prestacao Solicitado
						
						if($aDadosProponente["przfinsol_ppnt"]!=''){
						$sBuffer .= $oArquivo->formatNumber($aDadosProponente["przfinsol_ppnt"],3,"0"," ");}
						else{
						$sBuffer .= $oArquivo->formatString("",3);}	// 03 - Prazo Solicitado
						
						if($aDadosProponente["vlsinal_ppnt"]!=''){
						$sBuffer .= $oArquivo->formatNumber($aDadosProponente["vlsinal_ppnt"],15,"0"," ");}
						else{
						$sBuffer .= $oArquivo->formatString("",15);} 	// 15 - Valor Sinal Solicitado
					
					}elseif($aprovprevi == "S"){
						if($vlaprovado!=''){
						$sBuffer .= $oArquivo->formatNumber($vlaprovado,15,"0"," ");}
						else{
						$sBuffer .= $oArquivo->formatString("",15);} 		// 15 - Valor do fncto solicitado
						
						if($parcaprovada!=''){
						$sBuffer .= $oArquivo->formatNumber($parcaprovada,15,"0"," "); }
						else{
						$sBuffer .= $oArquivo->formatString("",15);}
						
						if($przaprovado!=''){		// 03 - Prazo Solicitado
						$sBuffer .= $oArquivo->formatNumber($przaprovado,3,"0"," ");}
						else{
						$sBuffer .= $oArquivo->formatString("",3);}	// 03 - Prazo Solicitado
						
						if($aDadosProponente["vlsinal_ppnt"]!=''){
						$sBuffer .= $oArquivo->formatNumber($aDadosProponente["vlsinal_ppnt"],15,"0"," ");}
						else{
						$sBuffer .= $oArquivo->formatString("",15);} 	// 15 - Valor Sinal Solicitado
					
					} else {
						$sBuffer .= $oArquivo->formatString("",48);
					}
				/*} elseif ($aDadosProposta["situacao_ppst"] == "9") {
					$sBuffer .= $oArquivo->formatNumber($aDadosProponente["vlfinsol_ppnt"],15,"0"," "); 		// 15 - Valor do fncto solicitado
					$sBuffer .= $oArquivo->formatNumber($aDadosProponente["vlprestaprov_ppnt"],15,"0"," "); 	// 15 - Valor da prestacao solicitada
					$sBuffer .= $oArquivo->formatNumber($aDadosProponente["przaprov_ppnt"],3,"0"," "); 	// 03 - Prazo Solicitado
					$sBuffer .= $oArquivo->formatNumber($aDadosProposta["valordevsinalsol_ppst"],15,"0"," "); 	// 15 - Valor Sinal Solicitado
				} else {
					$sBuffer .= $oArquivo->formatString("",48);
				}*/

				$sBuffer .= $oArquivo->formatString($aDadosProposta["indcancelamento_ppst"],2); // 02 - Indicador de cancelamento
				

//				$sBuffer .= $oArquivo->formatDate($aDadosImovel["dtaprovacao_imov"]); 			// 08 - Data de aprovacao do imovel

				//$aDadosProposta["flgaprovimovel_ppst"]
				if ($aDadosImovel["dtaprovacao_imov"]) {
							$oProposta->setPropostaAprovImovel($aDadoListaProposta["cod_ppst"],'S');
						}
				$db->query="Select flgaprovimovel_ppst from proposta where cod_ppst='".$aDadoListaProposta["cod_ppst"]."'";	
				//$msgRetorno.=$db->query."<br>";
				$db->query();
				if($db->qrcount>0)
				{
					$aDadosProposta["flgaprovimovel_ppst"]=$db->qrdata[0]['flgaprovimovel_ppst'];
				}
					if ($contadorProponente == "0" && $aDadosProposta["flgaprovimovel_ppst"] == "S") {
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
				
				$query="Select cod_usua from usuario where id_lstn='".$aDadosProponente['usuario'][0]["id_lstn"]."'";	
				$result =mysql_query($query);
				$linhas= mysql_num_rows($result);
				$registro = mysql_fetch_array($result, MYSQL_ASSOC);
				$cod_usua 	= 				$registro['cod_usua'];
				
				$flgfgts="";
				echo "<br><br>".$db->query="Select * from fgts where cod_usua='".$cod_usua."'";	
				//$msgRetorno.=$db->query."<br>";
				$db->query();
				if($db->qrcount>0)
				{	
					echo "<br>FlagFGTS:".$flgfgts=$db->qrdata[0]['FLAGUTILIZACAO'];
				}	
					if ($flgfgts=='S' && $aDadosProposta["situacao_ppst"] < "7" ) {
						$sBuffer .= $oArquivo->formatString("",70); 		// 70 - Nome do Cartório (RGI)
						$sBuffer .= $oArquivo->formatString($aDadosImovel["nrmatrgi_imov"],10); 		// 10 - Num. Matric. RGI
						$sBuffer .= $oArquivo->formatString("",10); 		// 10 - Num. Livro RGI
						$sBuffer .= $oArquivo->formatString("",10); 		// 10 - Num. Folhas Livro RGI
						$sBuffer .= $oArquivo->formatString("",10); 	    // 10 - Num. Reg. Compra e Venda
						$sBuffer .= $oArquivo->formatString("",10); 		// 10 - Num. Reg. Garantia
					}elseif ($aDadosProposta["dtokregistro_ppst"] != "" && $aDadosProposta["situacao_ppst"] == "10") {
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
					//$msgRetorno.="<br>valor aval:".$aDadosImovel["vlavaliacao_imov"];
					//$msgRetorno.="<br>valor aval:".$aDadosImovel["dtavaliacao_imov"];
					if ($aDadosImovel["vlavaliacao_imov"] != "" && $aDadosImovel["dtavaliacao_imov"] != "") {
						//$msgRetorno.="<br>positivo";
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
					$sBuffer .= $oArquivo->formatNumber($valorcompra,15,"0"," ");
					 
					
				} else {
					$sBuffer .= $oArquivo->formatString("",307);
				}
				
				
				
				//*********** DADOS DO VENDEDOR (MEGA-IF) ************
				$query="Select cod_usua from usuario where id_lstn='".$aDadosProponente['usuario'][0]["id_lstn"]."'";	
				$result =mysql_query($query);
				$linhas= mysql_num_rows($result);
				$registro = mysql_fetch_array($result, MYSQL_ASSOC);
					
					$cod_usua 	= 				$registro['cod_usua'];
				$db->query = "Select cod_ppst from proponente where cod_proponente='".$cod_usua."'";
				$db->query();
				if($db->qrcount>0)
				{
						$cod_ppst_vend=$db->qrdata[0]['cod_ppst'];
				}
				$db->query = "Select cod_vend from vendedor where cod_ppst='".$cod_ppst_vend."'";
				
				$db->query();
				if($db->qrcount>0)
				{
						$cod_vend=$db->qrdata[0]['cod_vend'];
				}
				$db->query = "Select TELEFONE_VNTL from vendtelefone where cod_vend='".$cod_vend."'";
				
				$db->query();
				if($db->qrcount>0)
				{
						$tel_vend=$db->qrdata[0]['TELEFONE_VNTL'];
				}
								
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
						$tel_vend && 
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
					/*$sBuffer .= $oArquivo->formatString($aDadosVendedor["nome_vend"],70); 			// 70 - Nome do vendedor
					$sBuffer .= $oArquivo->formatString($aDadosVendedor["nick_vend"],15); 			// 15 - Nome abreviado
					$sBuffer .= $oArquivo->formatString($aDadosVendedor["cod_logr"],3); 			// 03 - Tipo de logradouro
					$sBuffer .= $oArquivo->formatString($aDadosVendedor["endereco_vend"],40); 		// 40 - Endereço do vendedor
					$sBuffer .= $oArquivo->formatString($aDadosVendedor["nrendereco_vend"],6); 		// 06 - Número do Endereço do vendedor
					$sBuffer .= $oArquivo->formatString($aDadosVendedor["cpendereco_vend"],15); 	// 15 - Complemento do endereço
					$sBuffer .= $oArquivo->formatNumber($aDadosVendedor["cod_bairro"],4,"0",""); 	// 04 - Código do Bairro
					$sBuffer .= $oArquivo->formatString($aDadosVendedor["cep_vend"],8,"0"); 		// 08 - CEP do vendedor
					$sBuffer .= $oArquivo->formatNumber($aDadosVendedor["cod_municipio"],4,"0",""); // 04 - Código do municipio
					$sBuffer .= $oArquivo->formatString($tel_vend,12); 		// 12 - Telefone do vendedor
					$sBuffer .= $oArquivo->formatString($aDadosVendedor["nrcc_vend"],12); 			// 12 - numero da CC
					$sBuffer .= $oArquivo->formatString($aDadosVendedor["dvcc_vend"],3); 			// 03 - dígito verificador da cc
					$sBuffer .= $oArquivo->formatNumber($aDadosVendedor["nrag_vend"],4,"0",""); 	// 04 - Número da agência
		
					//*********** DADOS DO VENDEDOR (PF) ************
					if ((int)$aDadosVendedor["tipo_vend"] === 1) {
						/*$sBuffer .= $oArquivo->formatNumber($aDadosVendedorPF["cpf_vfisica"],11,"0"," "); 		// 11 - CPF do vendedor
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
						$sBuffer .= $oArquivo->formatString("",333);
					} else {
						$sBuffer .= $oArquivo->formatString("",333);
					}
					
					//*********** DADOS DO VENDEDOR (PJ) ************
					if ((int)$aDadosVendedor["tipo_vend"] === 2) {
						/*$sBuffer .= $oArquivo->formatNumber($aDadosVendedorPJ["cnpj_vjur"],14,"0"," "); 		// 14 - CNPJ
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
						$sBuffer .= $oArquivo->formatString("",218);
					} else {
						$sBuffer .= $oArquivo->formatString("",218);
					}*/
					$sBuffer .= $oArquivo->formatString("",70);
				} else {
					$sBuffer .= $oArquivo->formatString("",70);
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
				// DADOS DE FGTS
							
				$query="Select cod_usua from usuario where id_lstn='".$aDadosProponente['usuario'][0]["id_lstn"]."'";	
				$result =mysql_query($query);
				$linhas= mysql_num_rows($result);
				$registro = mysql_fetch_array($result, MYSQL_ASSOC);
					
					$cod_usua 	= 				$registro['cod_usua'];
							
				$db->query = "Select cod_ppst from proponente where cod_proponente='".$cod_usua."'";
				$db->query();
				if($db->qrcount>0)
				{
						$cod_ppst=$db->qrdata[0]['cod_ppst'];
				}

				$db->query = "Select * from proponente where cod_ppst='".$cod_ppst."'";
				$db->query();
				$numppst=$db->qrcount;

						$db->query="Select * from intvquitante where cod_ppst='".$cod_ppst."'";	
						//$msgRetorno.=$db->query."<br>";
						$db->query();
						if($db->qrcount>0)
						{	
								///// IF PARA GARANTIR QUE TODOS OS CAMPOS DO INTERVENIENTE QUITANTE ESTÃO PREENCHIDOS
							if(($db->qrdata[0]['NOME_INTQ']!="") && 
								($db->qrdata[0]['NOMEABR_INTQ'] != "") &&
								($db->qrdata[0]['COD_LOGR'] != "") && 
								($db->qrdata[0]['ENDERECO_INTQ'] != "") && 
								($db->qrdata[0]['NRENDERECO_INTQ']!= "") && 
								($db->qrdata[0]['COD_BAIRRO']!="") && 
								($db->qrdata[0]['CEP_INTQ']!="") && 
								($db->qrdata[0]['COD_MUNICIPIO']!="") && 
								($db->qrdata[0]['TELEFONE_INTQ']!="") && 
								($db->qrdata[0]['CNPJ_INTQ']!="") && 
								($db->qrdata[0]['VLSALDODEV_INTQ']!=""))
							{ 
								$soma+=1;
								$aBufferInterveniente = "\n";								
								if($aDadosProposta["tf_ppst"]!="S"){
									$aBufferInterveniente .= "20";
								}else{
									$aBufferInterveniente .= "25";
								}
								$aBufferInterveniente .= $oArquivo->formatNumber($aDadosProponente["listadenomes"][0]["id_lstn"], 9);			
								$aBufferInterveniente .= $oArquivo->formatNumber($aDadosProponente["cpf_ppnt"], 11);
								$aBufferInterveniente .= $oArquivo->formatString($db->qrdata[0]['NOME_INTQ'],70);
								$aBufferInterveniente .= $oArquivo->formatString($db->qrdata[0]['NOMEABR_INTQ'],15);
								$aBufferInterveniente .= $oArquivo->formatString($db->qrdata[0]['COD_LOGR'],3);
								$aBufferInterveniente .= $oArquivo->formatString($db->qrdata[0]['ENDERECO_INTQ'],40);
								$aBufferInterveniente .= $oArquivo->formatString($db->qrdata[0]['NRENDERECO_INTQ'],6);
								$aBufferInterveniente .= $oArquivo->formatString($db->qrdata[0]['CPENDERECO_INTQ'],15);
								$aBufferInterveniente .= $oArquivo->formatString($db->qrdata[0]['COD_BAIRRO'],4);
								$aBufferInterveniente .= $oArquivo->formatNumber($db->qrdata[0]['CEP_INTQ'], 8);
								$aBufferInterveniente .= $oArquivo->formatString($db->qrdata[0]['COD_MUNICIPIO'],4);
								$aBufferInterveniente .= $oArquivo->formatString($db->qrdata[0]['TELEFONE_INTQ'],12);
								$aBufferInterveniente .= $oArquivo->formatNumber($db->qrdata[0]['CNPJ_INTQ'], 14,"0","");
								$aBufferInterveniente .= $oArquivo->formatNumber($db->qrdata[0]['VLSALDODEV_INTQ'], 15,"0"," ");
							}
						}

			if($aDadosProposta["dtaprovacao_ppst"]=='')
			{
			
			
						$db->query="Select * from fgts where cod_usua='".$cod_usua."'";	
						//$msgRetorno.=$db->query."<br>";
						$db->query();
						if($db->qrcount>0)
						{	
							$aAltPpnt["flgfgts_ppnt"] 	= 				$db->qrdata[0]['FLAGUTILIZACAO'];
							$aAltPpnt["fgts"][0]["stimov_fgts"] = 		$db->qrdata[0]['STATUSIMOV'];
							$aAltPpnt["fgts"][0]["municipio_fgts"] = 	$db->qrdata[0]['NOMEMUNIBGE'];
							$aAltPpnt["fgts"][0]["codmunicipio_fgts"] = $db->qrdata[0]['CODMUNIBGE'];
							$aAltPpnt["fgts"][0]["estado_fgts"]=		$db->qrdata[0]['UFIBGE'];
							$aAltPpnt["fgts"][0]["qtcontas"]=			$db->qrdata[0]['QTCONTAS'];
							$aAltPpnt["fgts"][0]["valoper_fgts"]=		$db->qrdata[0]['VALOPERACAO'];
							
							///// IF PARA GARANTIR QUE TODOS OS CAMPOS DO FGTS ESTÃO PREENCHIDOS
							if(($aAltPpnt["flgfgts_ppnt"]!="") && 
								($aAltPpnt["fgts"][0]["stimov_fgts"] != "") &&
								($aAltPpnt["fgts"][0]["municipio_fgts"] != "") && 
								($aAltPpnt["fgts"][0]["codmunicipio_fgts"] != "") && 
								($aAltPpnt["fgts"][0]["estado_fgts"]!= "") &&
								($aAltPpnt["fgts"][0]["qtcontas"]!="") && 
								($aAltPpnt["fgts"][0]["valoper_fgts"]!=""))
							{ 
								
								if($aAltPpnt["flgfgts_ppnt"]=='S'){
									$soma+=1;
									$aBufferFgts = "\n";
									$aBufferFgts .= "02";
									$aBufferFgts .= $oArquivo->formatNumber($aDadosProponente["listadenomes"][0]["id_lstn"], 9);			
									$aBufferFgts .= $oArquivo->formatNumber($aDadosProponente["cpf_ppnt"], 11);
									$aBufferFgts .= $oArquivo->formatString($aAltPpnt["flgfgts_ppnt"],1);
									$aBufferFgts .= $oArquivo->formatNumber($aAltPpnt["fgts"][0]["codmunicipio_fgts"], 7);
									$aBufferFgts .= $oArquivo->formatString($aAltPpnt["fgts"][0]["municipio_fgts"],20);
									$aBufferFgts .= $oArquivo->formatNumber($aAltPpnt["fgts"][0]["stimov_fgts"], 1);
									$aBufferFgts .= $oArquivo->formatNumber($vlentrada,15,"0"," ");

									////////VALOR DE COMPRA
								
								/*}elseif($aAltPpnt["flgfgts_ppnt"]=='N'){
									$aBufferFgts = "\n";
									$aBufferFgts .= "02";
									$aBufferFgts .= $oArquivo->formatNumber($aDadosProponente["listadenomes"][0]["id_lstn"], 9);			
									$aBufferFgts .= $oArquivo->formatNumber($aDadosProponente["cpf_ppnt"], 11);
									$aBufferFgts .= $oArquivo->formatString($aAltPpnt["flgfgts_ppnt"],1);
									$aBufferFgts .= $oArquivo->formatNumber($aAltPpnt["fgts"][0]["codmunicipio_fgts"], 7,"0"," ");
									$aBufferFgts .= $oArquivo->formatString($aAltPpnt["fgts"][0]["municipio_fgts"],20);
									$aBufferFgts .= $oArquivo->formatNumber($aAltPpnt["fgts"][0]["stimov_fgts"], 1,"0"," ");
									$aBufferFgts .= $oArquivo->formatNumber($aDadosProponente["vlentrada_ppnt"],15,"0"," ");
									if($numppst==1)
									{
										$aBufferFgts .= $oArquivo->formatNumber($valorcompra,15,"0"," ");
									}elseif($numppst>1){
										$valcompra=($valorcompra*2)."00";
										$aBufferFgts .= $oArquivo->formatNumber($valcompra,15,"0"," ");
									}*/
								}  // if($aAltPpnt["flgfgts_ppnt"]=='S')
							} //fim IF PARA GARANTIR QUE TODOS OS CAMPOS DO FGTS ESTÃO PREENCHIDOS
						} // fim $db->qrcount
						//$sBuffer .= $aBufferFgts;
						$aBufferContas = "";
						$db->query = "Select * from contasfgts where cod_usua='".$cod_usua."'";
						$db->query();
						if($db->qrcount>0)
						{
							$c=0;
							while($c<$db->qrcount)
							{
									if(($db->qrdata[$c]['NOMETRAB'] != "")&&
										($db->qrdata[$c]['NUMPISPASEP'] != "") &&
										($db->qrdata[$c]['SITUACAOCONTA'] != "") &&
										($db->qrdata[$c]['CODCONTAEMP'] != "") &&
										($db->qrdata[$c]['CODCONTATRAB'] != "") &&
										($db->qrdata[$c]['VALORDEBITADO'] != "") &&
										($db->qrdata[$c]['SUREG'] != "") &&
										($db->qrdata[$c]['DTNASCTRAB'] != "") )
									{
										$soma+=1;
										$datano=substr($db->qrdata[$c]['DTNASCTRAB'],0,4);
										$datmes=substr($db->qrdata[$c]['DTNASCTRAB'],5,2);
										$datdia=substr($db->qrdata[$c]['DTNASCTRAB'],8,2);
										$datanasc=$datdia.$datmes.$datano;
										$aBufferContas .=	 "\n";
										$aBufferContas .=	"03";
										$aBufferContas .=   $oArquivo->formatNumber($aDadosProponente["listadenomes"][0]["id_lstn"], 9);			
										$aBufferContas .=   $oArquivo->formatNumber($aDadosProponente["cpf_ppnt"], 11);
										$aBufferContas .=	$oArquivo->formatString($db->qrdata[$c]['NOMETRAB'],40);
										$aBufferContas .=  	$oArquivo->formatNumber($db->qrdata[$c]['NUMPISPASEP'],11);
										$aBufferContas .=	$oArquivo->formatNumber($datanasc,8);
										$aBufferContas .= 	$oArquivo->formatString($db->qrdata[$c]['SITUACAOCONTA'],1);
										$aBufferContas .=   $oArquivo->formatNumber($db->qrdata[$c]['CODCONTAEMP'],14);
										$aBufferContas .=	$oArquivo->formatNumber($db->qrdata[$c]['CODCONTATRAB'],11);
										$aBufferContas .=	$oArquivo->formatNumber($db->qrdata[$c]['VALORDEBITADO'],15,"0"," ");
										$aBufferContas .=	$oArquivo->formatString($db->qrdata[$c]['SUREG'],2);
										//$sBuffer .= $aBufferContas;
									}
								$c++;
							}// fim while
						}//fim $db->qrcount
                 } // fim 	if($aDadosProposta["dtaprovacao_ppst"]=='')
				 
				 
				 
				 $aBufferVendedor = "";
				 ### da qui ate
				 $db->query="Select * from excvend where cod_ppst='".$cod_ppst."'";
				 $db->query();
				 $cancelarVendedor=0;
				 if($aDadosProposta["tf_ppst"]!="S"){
					 if($db->qrcount>0)
					 {
						$aBufferVendedor .= "\n";
						$aBufferVendedor .= "11";
						$aBufferVendedor .=   $oArquivo->formatNumber($aDadosProponente["listadenomes"][0]["id_lstn"], 9);			
						$aBufferVendedor .=   $oArquivo->formatNumber($aDadosProponente["cpf_ppnt"], 11);
						$somavendexc+=1;
						$db->query="Delete from excvend where cod_ppst='".$cod_ppst."'";
						$db->query();
						$cancelarVendedor=1;					
					 }
				 }
					if((((($flgfgts == "N" || $flgfgts== "") && ($aDadosImovel["dtaprovacao_imov"]!='') && ($aDadosProposta["situacao_ppst"] < "10")) || (($flgfgts == "S") && ($aDadosImovel["dtaprovacao_imov"]!='') && ($aDadosProposta["situacao_ppst"] < "6")))) || $cancelarVendedor==1)
					{
						
					//	echo $cod_ppst."entrou laço vendedor<br>";
						
					$db->query="Select * from vendedor where cod_ppst='".$cod_ppst."'";
					$db->query();
					
					if($db->qrcount>0)
					{
						$x=$db->qrcount;
						$v=0;
						while ($v<$x)
						{
							
							$query3="Select * from vendtelefone where cod_vend='".$db->qrdata[$v]['COD_VEND']."'";
							$result3 =mysql_query($query3);
							$linhas= mysql_num_rows($result3);
							$rg = mysql_fetch_array($result3, MYSQL_ASSOC);
							
							if($db->qrdata[$v]['TIPO_VEND']==1){
							$query="Select * from vendfis where cod_vend='".$db->qrdata[$v]['COD_VEND']."'";
							$result =mysql_query($query);
							$linhas= mysql_num_rows($result);
							$registro = mysql_fetch_array($result, MYSQL_ASSOC);
							$query2="Select * from vendfisconjuge where cod_vend='".$db->qrdata[$v]['COD_VEND']."'";
							$result2 =mysql_query($query2);
							$linhas= mysql_num_rows($result2);
							$reg = mysql_fetch_array($result2, MYSQL_ASSOC);
							}
							
							if($db->qrdata[$v]['TIPO_VEND']==2){
							$query="Select * from vendjur where cod_vend='".$db->qrdata[$v]['COD_VEND']."'";
							$result =mysql_query($query);
							$linhas= mysql_num_rows($result);
							$registro = mysql_fetch_array($result, MYSQL_ASSOC);
							$query2="Select * from vendjursocio where cod_vend='".$db->qrdata[$v]['COD_VEND']."'";
							$result2 =mysql_query($query2);
							$linhas= mysql_num_rows($result2);
							$reg = mysql_fetch_array($result2, MYSQL_ASSOC);
							}
							
							
					

						############# MEGA IF DO VENDEDOR ##########################
							if(( // Dados comuns
									($db->qrdata[$v]['NOME_VEND']  		!="") &&
									($db->qrdata[$v]['NICK_VEND']  		!="") &&
									($db->qrdata[$v]['COD_LOGR']  		!="") &&
									($db->qrdata[$v]['ENDERECO_VEND']  	!="") &&
									($db->qrdata[$v]['NRENDERECO_VEND'] !="") &&
									($db->qrdata[$v]['COD_BAIRRO']  	!="") &&
									($db->qrdata[$v]['CEP_VEND']  		!="") &&
									($db->qrdata[$v]['COD_MUNICIPIO']  	!="") &&
									($rg['TELEFONE_VNTL']  				!="") &&
									($db->qrdata[$v]['NRCC_VEND']  		!="") &&
									($db->qrdata[$v]['DVCC_VEND']  		!="") &&
									($db->qrdata[$v]['NRAG_VEND']  		!="") &&
									($db->qrdata[$v]['PERCENTUALVENDA_VEND']  !="")
								) && 
								(
								
									  (// Dados do vendedor pessoa FÍSICA
										($db->qrdata[$v]['TIPO_VEND']		==1)&&
										($registro["CPF_VFISICA"]   		!="")&&
										($registro['SEXO_VFISICA']  		!="")&&
										($registro["DTNASCIMENTO_VFISICA"]  !="")&&
										($registro['COD_PAIS']   			!="")&&
										($registro['NATUR_VFISICA']   		!="")&&
										($registro['COD_TPDOC']   			!="")&&
										($registro['NRRG_VFISICA']   		!="")&&
										($registro['DTRG_VFISICA']   		!="")&&
										($registro['ORGRG_VFISICA']   		!="")&&
										($registro['COD_ESTCIV']   			!="")&&
										($registro['NOMEPAI_VFISICA']   	!="")&&
										($registro['NOMEMAE_VFISICA']   	!="")&&
										($registro['COD_PROF']   			!="")&&
										($registro['VLRENDA_VFISICA']   	!="") 
									  )
							  || 
							 ( // Dados do vendedor pessoa JURIDICA
								($db->qrdata[$v]['TIPO_VEND']		==2)&&
								($registro["CNPJ_VJUR"]   			!="")&&
								($registro['ISENPIS_VJUR']  		!="")&&
								($registro['ISENCOFINS_VJUR']   	!="")&&
								($registro['ISENCSLL_VJUR']   			!="")&&
								($registro['COD_CNAE']   			!="")&&
								
								($reg['NOME_VJSOC']   				!="")&&
								($reg['NICK_VJSOC']   				!="")&&
								($reg['COD_LOGR']   				!="")&&
								($reg['ENDERECO_VJSOC']   			!="")&&
								($reg['NRENDERECO_VJSOC']   		!="")&&
								($reg['COD_BAIRRO']   				!="")&&
								($reg["CEP_VJSOC"]   				!="")&&
								($reg['COD_MUNICIPIO']   			!="") &&
								($reg["CPF_VJSOC"]   				!="") &&
								($reg['TELEFONE_VJSOC']   			!="") &&
								($reg['SEXO_VJSOC']   				!="") &&
								($reg['COD_PAIS']   				!="")
							  )	))
                         {
							 
							 $somavend+=1;
							$aBufferVendedor  .=   "\n";
							$aBufferVendedor .=	  "10";
							$aBufferVendedor .=   $oArquivo->formatNumber($aDadosProponente["listadenomes"][0]["id_lstn"], 9);			
							$aBufferVendedor .=   $oArquivo->formatNumber($aDadosProponente["cpf_ppnt"], 11);
							$aBufferVendedor .=   $oArquivo->formatString($db->qrdata[$v]['NOME_VEND'],70);
							$aBufferVendedor .=   $oArquivo->formatString($db->qrdata[$v]['NICK_VEND'],15);
							$aBufferVendedor .=   $oArquivo->formatString($db->qrdata[$v]['COD_LOGR'],3);
							$aBufferVendedor .=   $oArquivo->formatString($db->qrdata[$v]['ENDERECO_VEND'],40);
							$aBufferVendedor .=   $oArquivo->formatString($db->qrdata[$v]['NRENDERECO_VEND'],6);
							$aBufferVendedor .=   $oArquivo->formatString($db->qrdata[$v]['CPENDERECO_VEND'],15);
							$aBufferVendedor .=   $oArquivo->formatString($db->qrdata[$v]['COD_BAIRRO'],4);
							$aBufferVendedor .=   $oArquivo->formatNumber($db->qrdata[$v]['CEP_VEND'],8);
							$aBufferVendedor .=   $oArquivo->formatString($db->qrdata[$v]['COD_MUNICIPIO'],4);
							
							
							$aBufferVendedor .=   $oArquivo->formatString($rg['TELEFONE_VNTL'],12);
							if($db->qrdata[$v]['DVCC_VEND']=='zero')
							{
								$digito_vendedor='0';
							}else
							{
								$digito_vendedor=$db->qrdata[$v]['DVCC_VEND'];
							}
							$aBufferVendedor .=   $oArquivo->formatString($db->qrdata[$v]['NRCC_VEND'],12);
							$aBufferVendedor .=   $oArquivo->formatString($digito_vendedor,3);
							$aBufferVendedor .=   $oArquivo->formatString($db->qrdata[$v]['NRAG_VEND'],4);
							$aBufferVendedor .=	  $oArquivo->formatNumber($db->qrdata[$v]['PERCENTUALVENDA_VEND'],8,"0"," ");
							if($db->qrdata[$v]['TIPO_VEND']==1)
							{
								$aBufferVendedor .=   $oArquivo->formatNumber($registro["CPF_VFISICA"], 11);
								$aBufferVendedor .=   $oArquivo->formatString($registro['SEXO_VFISICA'],1);
								$aBufferVendedor .=   $oArquivo->formatDate($registro["DTNASCIMENTO_VFISICA"]);
								$aBufferVendedor .=   $oArquivo->formatString($registro['COD_PAIS'],6);
								$aBufferVendedor .=   $oArquivo->formatString($registro['NATUR_VFISICA'],30);
								$aBufferVendedor .=   $oArquivo->formatString($registro['COD_TPDOC'],2);
								$aBufferVendedor .=   $oArquivo->formatString($registro['NRRG_VFISICA'],11);
								$aBufferVendedor .=   $oArquivo->formatDate($registro['DTRG_VFISICA']);
								$aBufferVendedor .=   $oArquivo->formatString($registro['ORGRG_VFISICA'],10);
								$aBufferVendedor .=   $oArquivo->formatString($registro['COD_ESTCIV'],1);
								if($registro['COD_ESTCIV']==2)
								{
									$aBufferVendedor .=   $oArquivo->formatString($reg['NOME_VFCJ'],70);
								}else{
									$aBufferVendedor .=   $oArquivo->formatString("",70);
								}
								$aBufferVendedor .=   $oArquivo->formatString($registro['NOMEPAI_VFISICA'],70);
								$aBufferVendedor .=   $oArquivo->formatString($registro['NOMEMAE_VFISICA'],70);
								$aBufferVendedor .=   $oArquivo->formatString($registro['COD_PROF'],3);
								$aBufferVendedor .=	  $oArquivo->formatNumber($registro['VLRENDA_VFISICA'],21,"0"," ");
								$aBufferVendedor .=   $oArquivo->formatString($registro['NRINSS_VFISICA'],11);
							
							
							}else{
								$aBufferVendedor .=   $oArquivo->formatString("",333);
							}
							if($db->qrdata[$v]['TIPO_VEND']==2)
							{
								$aBufferVendedor .=   $oArquivo->formatNumber($registro["CNPJ_VJUR"], 14,"0","");
								$aBufferVendedor .=   $oArquivo->formatString($registro['ISENPIS_VJUR'],1);
								$aBufferVendedor .=   $oArquivo->formatString($registro['ISENCOFINS_VJUR'],1);
								$aBufferVendedor .=   $oArquivo->formatString($registro['ISENCSLL_VJUR'],1);
								$aBufferVendedor .=   $oArquivo->formatString($registro['COD_CNAE'],6);
								
								$aBufferVendedor .=   $oArquivo->formatString($reg['NOME_VJSOC'],70);
								$aBufferVendedor .=   $oArquivo->formatString($reg['NICK_VJSOC'],15);
								$aBufferVendedor .=   $oArquivo->formatString($reg['COD_LOGR'],3);
								$aBufferVendedor .=   $oArquivo->formatString($reg['ENDERECO_VJSOC'],40);
								$aBufferVendedor .=   $oArquivo->formatString($reg['NRENDERECO_VJSOC'],6);
								$aBufferVendedor .=   $oArquivo->formatString($reg['CPENDERECO_VJSOC'],15);
								$aBufferVendedor .=   $oArquivo->formatString($reg['COD_BAIRRO'],4);
								$aBufferVendedor .=   $oArquivo->formatNumber($reg["CEP_VJSOC"], 8,"0","");
								$aBufferVendedor .=   $oArquivo->formatString($reg['COD_MUNICIPIO'],4);
								$aBufferVendedor .=   $oArquivo->formatString($reg['TELEFONE_VJSOC'],12);
								$aBufferVendedor .=   $oArquivo->formatNumber($reg["CPF_VJSOC"], 11,"0","");
								$aBufferVendedor .=   $oArquivo->formatString($reg['SEXO_VJSOC'],1);
								$aBufferVendedor .=   $oArquivo->formatString($reg['COD_PAIS'],6);
								
							}else{
								$aBufferVendedor .= $oArquivo->formatString("",218);
							}
                            }
							$v++;
						}
					}
                 }
						//$msgRetorno.=$aBufferVendedor;
						$sBuffer .= $aBufferInterveniente;
						$sBuffer .= $aBufferFgts;
						$sBuffer .= $aBufferContas;
						$sBuffer .= $aBufferVendedor;
						$aBufferInterveniente='';
						$aBufferFgts='';
						$aBufferContas='';
						$aBufferVendedor='';

				//$sBuffer .=$aBufferContas;
				//$msgRetorno.="\n\n".$sBuffer." --- ".strlen($sBuffer)."\n\n";
				$contadorProponente++;
				
				
				$aRegistrosEnviados = $oArquivo->getArquivoRegistro($aDadoListaProposta["cod_ppst"],$aDados906Out[0]["cod_arqu"],$aDadosProponente['usuario'][0]["id_lstn"]);
				
				if ($aRegistrosEnviados[0]["registro_arrg"] != $sBuffer) {
					$iNumRegistro++;
					$aNovosRegistros[$iNumRegistro]["registro"] = $sBuffer;
					$aNovosRegistros[$iNumRegistro]["cod_usua"] = $aDadoListaProposta["cod_usua"];
					$aNovosRegistros[$iNumRegistro]["cod_ppst"] = $aDadoListaProposta["cod_ppst"];
					$msgRetorno.="     ATUALIZADO - REGISTRO: ".$iNumRegistro;
				} else {
					$msgRetorno.="     NÃO ATUALIZADO";
				}
				
				
			}//fim else	
				
			}
			
		}
		
		if ($iNumRegistro > 0) {
			$msgRetorno.="\n\nNovos registros: ".$iNumRegistro.". Aguarde... Gerando remessa número ".str_pad($iNovaRemessa,6,"0",STR_PAD_LEFT)."\n\n<br><br>";

			// Header do arquivo
			$sBufferHeader  = "00";
			$sBufferHeader .= "COFSP906";
			$sBufferHeader .= "ATHOSGESTÃO    ";
			$sBufferHeader .= date("dmY");
			$sBufferHeader .= $oArquivo->formatNumber($iNovaRemessa,6);
	$msgRetorno.="FGTS:".$soma;
	$msgRetorno.="VEND:".$somavend;
	$msgRetorno.="EXC:".$somavendexc;

			/// Trailer do arquivo
			$sBufferTrailer  = "99";
			$sBufferTrailer .= $oArquivo->formatNumber(($iNumRegistro+2+$soma+$somavend+$somavendexc),10);
	
			$sBuffer = $sBufferHeader."\n";
			foreach ($aNovosRegistros as $iNovoRegistro => $aNovoRegistro) {
				$sBuffer .= $aNovoRegistro["registro"]."\n";
			}
			//$sBuffer .= $aBufferFgts."\n";
			//$sBuffer .= $aBufferContas;
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
			$msgRetorno.="\n\nAguarde... Gerando arquivo ".$sArquivo906out."...";
			
			$resArquivoSaida = fopen(ATHOSFILEPATH_ENT.$sArquivo906out, "w+");		
			fputs($resArquivoSaida,$sBuffer,strlen($sBuffer));
			fclose($resArquivoSaida);
			
			$msgRetorno.="OK\n\n";

			//$aNovosRegistros[$iNumRegistro]["cod_usua"] = $aDadoListaProposta["cod_usua"];
		} else {
			$msgRetorno.="\n\nNão há novos registros. Remessa não gerada.\n\n";
		}
	}
} else {
	$msgRetorno.="O arquivo de resultado da remessa ".str_pad($iRemessa906out,6,"0",STR_PAD_LEFT)." ainda não foi retornado.\n\n";
}

$msgRetorno.="\n\n\n";

echo $msgRetorno;

	if(isset($autoproc)){
	$datahoje=date('Y-m-d',time());
	$horario=date('h:i:s',time());
		mysql_query("UPDATE autoproc SET status='S' WHERE data = '$datahoje'");
	
		$mensagem = 'Arquivo processado em' .$datahoje . "às $horario. \n\n\n RESULTADO \n\n\n";
		$mensagem .= $msgRetorno;

		//montando o corpo da mensagem
		mail("gerson@contrathos.com.br","Execução automática do arquivo athosproc",$mensagem, "From: contato@contrathos.com.br");

	}



?>
