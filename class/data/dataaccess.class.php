<?php
class agenciabb extends database {

	function agenciabb() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_agbb,nome_agbb
			FROM agenciabb
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_agbb) {
		$this->query = "
			SELECT cod_agbb,nome_agbb
			FROM agenciabb
			WHERE cod_agbb = '".mysql_real_escape_string($cod_agbb)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_agbb,$nome_agbb) {
		$this->query = "
			UPDATE agenciabb SET 
			cod_agbb = ".(!$cod_agbb ? "NULL" : "'".mysql_real_escape_string($cod_agbb)."'").",nome_agbb = ".(!$nome_agbb ? "NULL" : "'".mysql_real_escape_string($nome_agbb)."'")."
			WHERE cod_agbb = '".mysql_real_escape_string($cod_agbb)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_agbb) {
		$this->query = "
			DELETE FROM agenciabb 
			WHERE cod_agbb = '".mysql_real_escape_string($cod_agbb)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_agbb,$nome_agbb) {
		$this->query = "
			INSERT INTO agenciabb ( cod_agbb,nome_agbb ) VALUES (
				".(!$cod_agbb ? "NULL" : "'".mysql_real_escape_string($cod_agbb)."'").",".(!$nome_agbb ? "NULL" : "'".mysql_real_escape_string($nome_agbb)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class arquivo extends database {

	var $errDesc;
	var $errCode;
	
	var $fileArr;
	var $curLine;
	var $numRows;
	var $procLines;
	
	var $iNumRemessa;
	var $dtDataRemessa;

	function arquivo() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_arqu,nome_arqu,descr_arqu,ultimaremessa_arqu,dtultimaremessa_arqu
			FROM arquivo
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_arqu) {
		$this->query = "
			SELECT cod_arqu,nome_arqu,descr_arqu,ultimaremessa_arqu,dtultimaremessa_arqu
			FROM arquivo
			WHERE cod_arqu = '".mysql_real_escape_string($cod_arqu)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_arqu,$nome_arqu,$descr_arqu,$ultimaremessa_arqu,$dtultimaremessa_arqu) {
		$this->query = "
			UPDATE arquivo SET 
			nome_arqu = ".(!$nome_arqu ? "NULL" : "'".mysql_real_escape_string($nome_arqu)."'").",descr_arqu = ".(!$descr_arqu ? "NULL" : "'".mysql_real_escape_string($descr_arqu)."'").",ultimaremessa_arqu = ".(!$ultimaremessa_arqu ? "NULL" : "'".mysql_real_escape_string($ultimaremessa_arqu)."'").",dtultimaremessa_arqu = ".(!$dtultimaremessa_arqu ? "NULL" : "'".mysql_real_escape_string($dtultimaremessa_arqu)."'")."
			WHERE cod_arqu = '".mysql_real_escape_string($cod_arqu)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_arqu) {
		$this->query = "
			DELETE FROM arquivo 
			WHERE cod_arqu = '".mysql_real_escape_string($cod_arqu)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($nome_arqu,$descr_arqu,$ultimaremessa_arqu,$dtultimaremessa_arqu) {
		$this->query = "
			INSERT INTO arquivo ( nome_arqu,descr_arqu,ultimaremessa_arqu,dtultimaremessa_arqu ) VALUES (
				".(!$nome_arqu ? "NULL" : "'".mysql_real_escape_string($nome_arqu)."'").",".(!$descr_arqu ? "NULL" : "'".mysql_real_escape_string($descr_arqu)."'").",".(!$ultimaremessa_arqu ? "NULL" : "'".mysql_real_escape_string($ultimaremessa_arqu)."'").",".(!$dtultimaremessa_arqu ? "NULL" : "'".mysql_real_escape_string($dtultimaremessa_arqu)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}
	
	function recLoteCadastro($sArquivo) {
		//ATHOSGEST�O.COFSP909.0000011.SAI
		$this->clearData();
		$this->setProcLines(0);
		
		//$fileName = "../athosfiles/COFSP909.ATHOS.SAI";
		$fileName = ATHOSFILEPATH.$sArquivo;

		$headerFields = "2,8,15,8,6";
		$regFields = "2,9,15,15,3,15,15,3,15,2,6,8,250,8,3,4,12,3,15,15";
		$trailerFields = "2,10";

		if (!$this->openFile($fileName)) {
			if ($this->getNumRows() > 1) {
				$this->goLine(1);
				$header = $this->explodeData($headerFields);
				$this->goLine($this->getNumRows());
				$trail = $this->explodeData($trailerFields);

				$this->setNumRemessa($header[4]);
				$this->setDtRemessa($this->fieldDate($header[3]));

				if ($this->getNumRows() > 2) {
					for ($i=2;$i<$this->getNumRows();$i++) {
						$this->goLine($i);
						$reg = $this->explodeData($regFields);
						if (strtoupper(trim($reg[9])) == "X") {
							$this->query = "
								SELECT
									cod_usua
								FROM
									usuario
								WHERE
									id_lstn = '".((string)str_pad($reg[1],9,"0",STR_PAD_LEFT))."'
							";
							$this->query();
							$iCodUsua = $this->qrdata[0]["cod_usua"];
							if ($iCodUsua) {
								$this->query = "
									UPDATE
										arquivoregistro
									SET
										flgerro_arrg = 'S',
										dtprocessamento_arrg = NOW(),
										ocorrencia_arrg = CONCAT_WS('; ', ocorrencia_arrg, '".mysql_real_escape_string($reg[12])."')
									WHERE
										cod_arqu = '2' and 
										remessa_arre = '".((int)$reg[10])."' and 
										cod_usua = '".$iCodUsua."' 
								";
							}
							
							$oProponente = new proponente();
							$aDadosProponente = $oProponente->pesquisarPorUsuario($iCodUsua);
							$oHistorico = new historico();
							$oHistorico->inserir($aDadosProponente[(count($aDadosProponente)-1)]['cod_ppst'],date("Y-m-d H:i:s"),'Retorno da Previ com erro: '.$reg[12],'1','');

							
						} elseif (strtoupper(trim($reg[9])) == "C") {
$data=$reg[13];					$data=$header[3];
								$dia=substr($data,0,2);
								$mes=substr($data,2,2);
								$ano=substr($data,4,4);
								$dtconvocacao=($ano."-".$mes."-".$dia);							
								$this->query = "
								INSERT INTO listadenomes (
									id_lstn,
									vlmaxfinan,
									parcmaxfinan,
									przmaxfinan,
									status_pp,
									dtconvocacao
								) VALUES (
									'".mysql_real_escape_string($reg[1])."',
									".$this->fieldCurrency(mysql_real_escape_string($reg[2])).",
									".$this->fieldCurrency(mysql_real_escape_string($reg[3])).",
									".$this->fieldNumber(mysql_real_escape_string($reg[4])).",
									'".mysql_real_escape_string($reg[9])."',
									'".$dtconvocacao."'
								)
							";
							$this->query();
							//echo $this->query."<br />";
							if (!$this->qrcount && @mysql_errno() == "1062") {
								$this->query = "
									UPDATE listadenomes SET 
										vlmaxfinan = ".$this->fieldCurrency(mysql_real_escape_string($reg[2])).",
										parcmaxfinan = ".$this->fieldCurrency(mysql_real_escape_string($reg[3])).",
										przmaxfinan = ".$this->fieldNumber(mysql_real_escape_string($reg[4])).",
										vlaprovado = ".$this->fieldCurrency(mysql_real_escape_string($reg[5])).",
										parcaprovada = ".$this->fieldCurrency(mysql_real_escape_string($reg[6])).",
										przaprovado = ".$this->fieldNumber(mysql_real_escape_string($reg[7])).",
										vlentraprovado = ".$this->fieldNumber(mysql_real_escape_string($reg[8])).",
										status_pp = '".mysql_real_escape_string($reg[9])."'
									WHERE
										id_lstn = '".mysql_real_escape_string($reg[1])."'
								";
								$this->query();
								if ($this->qrcount) {
									$this->setProcLines($this->getProcLines()+1);
								}
							} else {
								$this->setProcLines($this->getProcLines()+1);
							}
						
						}elseif(strtoupper(trim($reg[9])) == "V"){
						// AQUI VOU INSERIR C�DIGO PARA QUANDO VIER NO ARQUIVO TIPO DE INFORMA��O FN
								$this->query = "
									UPDATE
										listadenomes
									SET
										vlmaxfinan='".$this->fieldCurrency(mysql_real_escape_string($reg[2]))."',
										parcmaxfinan='".$this->fieldCurrency(mysql_real_escape_string($reg[3]))."',
										przmaxfinan='".$this->fieldNumber(mysql_real_escape_string($reg[4]))."'
									WHERE
										id_lstn = '".((string)str_pad($reg[1],9,"0",STR_PAD_LEFT))."'
								";
								//echo $this->query;
								$this->query();
								$this->query = "
								SELECT
									cod_usua
								FROM
									usuario
								WHERE
									id_lstn = '".((string)str_pad($reg[1],9,"0",STR_PAD_LEFT))."'
							";
								//echo $this->query();

							$this->query();
							$iCodUsua = $this->qrdata[0]["cod_usua"];
	
							$oProponente = new proponente();
							$aDadosProponente = $oProponente->pesquisarPorUsuario($iCodUsua);
							$oHistorico = new historico();
							
							$oHistorico->inserir($aDadosProponente[(count($aDadosProponente)-1)]['cod_ppst'],date("Y-m-d H:i:s"),'Retorno da Previ com Limites Atualizados.','1','');

					}elseif (strtoupper(trim($reg[9])) == "VA") {
							$this->query = "
								SELECT
									cod_usua
								FROM
									usuario
								WHERE
									id_lstn = '".((string)str_pad($reg[1],9,"0",STR_PAD_LEFT))."'
							";
							$this->query();
							$iCodUsua = $this->qrdata[0]["cod_usua"];
							
							$oProponente = new proponente();
							$aDadosProponente = $oProponente->pesquisarPorUsuario($iCodUsua);
							$this->query = "
								UPDATE listadenomes SET 
									vlaprovado = ".$this->fieldCurrency(mysql_real_escape_string($reg[5])).",
									parcaprovada = ".$this->fieldCurrency(mysql_real_escape_string($reg[6])).",
									przaprovado = ".$this->fieldNumber(mysql_real_escape_string($reg[7])).",
									vlsinalaprovado = ".$this->fieldCurrency(mysql_real_escape_string($reg[8])).",
									status_pp = '".mysql_real_escape_string($reg[9])."'
								WHERE
									id_lstn = '".mysql_real_escape_string($reg[1])."'
							";
							$this->query();
							$this->query = "
								UPDATE proponente SET 
									vlfinaprov_ppnt = ".$this->fieldCurrency(mysql_real_escape_string($reg[5])).",
									vlprestaprov_ppnt = ".$this->fieldCurrency(mysql_real_escape_string($reg[6])).",
									przaprov_ppnt = ".$this->fieldNumber(mysql_real_escape_string($reg[7]))."
									vlsinal_ppnt = ".$this->fieldCurrency(mysql_real_escape_string($reg[8]))."
									status_pp = '".mysql_real_escape_string($reg[9])."'
								WHERE
									cod_proponente = '".mysql_real_escape_string($iCodUsua)."' and 
									cod_ppst = '".$aDadosProponente[(count($aDadosProponente)-1)]['cod_ppst']."'
							";
							//vlentraprovado = ".$this->fieldNumber(mysql_real_escape_string($reg[8])).",
							$this->query();
							$oHistorico = new historico();
							$oHistorico->inserir($aDadosProponente[(count($aDadosProponente)-1)]['cod_ppst'],date("Y-m-d H:i:s"),'Retorno da Previ com Valores de Financiamento Autorizados.','1','');
							if ($this->qrcount) {
								$this->setProcLines($this->getProcLines()+1);
							}
						}elseif (strtoupper(trim($reg[9])) == "A") {
							$this->query = "
								SELECT
									cod_usua
								FROM
									usuario
								WHERE
									id_lstn = '".((string)str_pad($reg[1],9,"0",STR_PAD_LEFT))."'
							";
							$this->query();
							$iCodUsua = $this->qrdata[0]["cod_usua"];
							$query="Select * from fgts where cod_usua='".$iCodUsua."'";
							$result=mysql_query($query);
							$registro=mysql_fetch_array($result,MYSQL_ASSOC);
							
							$fgts=$registro['FLAGUTILIZACAO'];
							if($fgts=='S'){$msg_fgts=". Fundo de Garantia enviado para Caixa.";}else{$msg_fgts='';}
						
							$oProponente = new proponente();
							$aDadosProponente = $oProponente->pesquisarPorUsuario($iCodUsua);
							
							if ($aDadosProponente[(count($aDadosProponente)-1)]["cod_ppst"]) {
								$oProposta = new proposta();
								
								$oProposta->setPropostaAprovacaoPrevi($aDadosProponente[(count($aDadosProponente)-1)]["cod_ppst"]);
								//$oProposta->setPropostaStatus($aDadosProponente[(count($aDadosProponente)-1)]["cod_ppst"],"7");
								
								$oHistorico = new historico();
								$oHistorico->inserir($aDadosProponente[(count($aDadosProponente)-1)]['cod_ppst'],date("Y-m-d H:i:s"),'Retorno da Previ com proposta aprovada (Conta corrente e agencia participante informadas'.$msg_fgts.').','1','');
							}
							
								$this->query = "Select * from retornofgts WHERE	participante = '".((string)str_pad($reg[1],9,"0",STR_PAD_LEFT))."'";
								$this->query();	
								if($this->qrcount>0)
								{
										$this->query = "
											UPDATE
												retornofgts
											SET
												nragencia='".$reg[15]."',
												nrconta='".$reg[16]."',
												nrdigito='".$reg[17]."',
												premio='".$reg[19]."',
												rendabruta='".$this->fieldCurrency($reg[18])."'
											WHERE
												participante = '".((string)str_pad($reg[1],9,"0",STR_PAD_LEFT))."'
										";
										//echo $this->query;
										$this->query();	
								}else{
										$this->query = "
											INSERT INTO
												retornofgts (participante,nragencia,nrconta,nrdigito,premio,rendabruta)
											VALUES (
												'".((string)str_pad($reg[1],9,"0",STR_PAD_LEFT))."',
												'".$reg[15]."',
												'".$reg[16]."',
												'".$reg[17]."',
												'".$reg[19]."',
												'".$this->fieldCurrency($reg[18])."')";
										//echo $this->query;
										$this->query();	
								}
						}elseif(strtoupper(trim($reg[9])) == "FL"){
								$data=$reg[13];
								$dia=substr($data,0,2);
								$mes=substr($data,2,2);
								$ano=substr($data,4,4);
								$dtcredito=($ano."-".$mes."-".$dia);
								$this->query = "
									INSERT INTO
										retornofgts (participante,dtcredito) VALUES('".((string)str_pad($reg[1],9,"0",STR_PAD_LEFT))."','".$dtcredito."')
									WHERE
										participante = '".((string)str_pad($reg[1],9,"0",STR_PAD_LEFT))."'
								";
								//echo $this->query;
								$this->query();	
								$this->query = "
								SELECT
									cod_usua
								FROM
									usuario
								WHERE
									id_lstn = '".((string)str_pad($reg[1],9,"0",STR_PAD_LEFT))."'
							";
								//echo $this->query();

							$this->query();
							$iCodUsua = $this->qrdata[0]["cod_usua"];
							$this->query = "
								SELECT
									cod_ppst
								FROM
									proponente
								WHERE
									cod_proponente = '".$iCodUsua."'
							";
							//echo $this->query;
							$this->query();
							$iCodPpst = $this->qrdata[0]["cod_ppst"];
								$this->query = "
									UPDATE
										proposta
									SET
										DTASSCONTRATO_PPST ='".$dtcredito."',
										SITUACAO_PPST='7',
										VALORSEGURO_PPST ='".$this->fieldCurrency(mysql_real_escape_string($reg[19]))."'
									WHERE
										cod_ppst='".$iCodPpst."'
								";
								//echo $this->query;
								$this->query();
								$oProponente = new proponente();
								$aDadosProponente = $oProponente->pesquisarPorUsuario($iCodUsua);

								$oHistorico = new historico();
								$oHistorico->inserir($aDadosProponente[(count($aDadosProponente)-1)]['cod_ppst'],date("Y-m-d H:i:s"),'Fundo de Garantia Liberado (data de assinatura agendada e seguros informados).','1','');

								$oProposta = new proposta();
								$oProposta->setPropostaStatus($aDadosProponente[(count($aDadosProponente)-1)]["cod_ppst"],"7");
						}elseif(strtoupper(trim($reg[9])) == "FN"){
							$this->query = "
								SELECT
									cod_usua
								FROM
									usuario
								WHERE
									id_lstn = '".((string)str_pad($reg[1],9,"0",STR_PAD_LEFT))."'
							";
								//echo $this->query();

							$this->query();
							$iCodUsua = $this->qrdata[0]["cod_usua"];
							
							$this->query = "
								SELECT
									msg_erro
								FROM
									erros
								WHERE
									cod_erro = '".$reg[14]."'
							";
								//echo $this->query();

							$this->query();
							$msg_erro = $this->qrdata[0]["msg_erro"];

							$oProponente = new proponente();
							$aDadosProponente = $oProponente->pesquisarPorUsuario($iCodUsua);
							$oHistorico = new historico();
							$oHistorico->inserir($aDadosProponente[(count($aDadosProponente)-1)]['cod_ppst'],date("Y-m-d H:i:s"),'Fundo de Garantia Negado. Erro: '.$reg[14].' - '.$msg_erro.'','1','');


						// AQUI VOU INSERIR C�DIGO PARA QUANDO VIER NO ARQUIVO TIPO DE INFORMA��O FN
								$this->query = "
									INSERT INTO 
										retornoerro	(participante,erro)
								    VALUES
										('".((string)str_pad($reg[1],9,"0",STR_PAD_LEFT))."','".$reg[14]."')
								";
								//echo $this->query;
								$this->query();												
					}elseif(strtoupper(trim($reg[9])) == "R"){
						// AQUI VOU INSERIR C�DIGO PARA QUANDO VIER NO ARQUIVO TIPO DE INFORMA��O FN
								$this->query = "
									UPDATE
										listadenomes
									SET
										status_pp='R'
									WHERE
										id_lstn = '".((string)str_pad($reg[1],9,"0",STR_PAD_LEFT))."'
								";
								//echo $this->query;
								$this->query();
								$this->query = "
								SELECT
									cod_usua
								FROM
									usuario
								WHERE
									id_lstn = '".((string)str_pad($reg[1],9,"0",STR_PAD_LEFT))."'
							";
								//echo $this->query();

							$this->query();
							$iCodUsua = $this->qrdata[0]["cod_usua"];
	
							$oProponente = new proponente();
							$aDadosProponente = $oProponente->pesquisarPorUsuario($iCodUsua);
							$oHistorico = new historico();
							
							$oHistorico->inserir($aDadosProponente[(count($aDadosProponente)-1)]['cod_ppst'],date("Y-m-d H:i:s"),'Retorno da Previ com registro do im�vel confirmado.','1','');
			
					}elseif(strtoupper(trim($reg[9])) == "E"){
						// QUANDO PROPOSTA VEM EXPIRADA
								$this->query = "
									UPDATE
										listadenomes
									SET
										status_pp='E'
									WHERE
										id_lstn = '".((string)str_pad($reg[1],9,"0",STR_PAD_LEFT))."'
								";
								//echo $this->query;
								$this->query();				
	
					}elseif(strtoupper(trim($reg[9])) == "S"){
							$this->query = "
								SELECT
									cod_usua
								FROM
									usuario
								WHERE
									id_lstn = '".((string)str_pad($reg[1],9,"0",STR_PAD_LEFT))."'
							";
								//echo $this->query();

							$this->query();
							$iCodUsua = $this->qrdata[0]["cod_usua"];
							$oProponente = new proponente();
							$aDadosProponente = $oProponente->pesquisarPorUsuario($iCodUsua);
							$oHistorico = new historico();
							$oHistorico->inserir($aDadosProponente[(count($aDadosProponente)-1)]['cod_ppst'],date("Y-m-d H:i:s"),'Retorno da Previ com assinatura confirmada (Seguros Informados).','1','');
								$data=$reg[13];
								$dia=substr($data,0,2);
								$mes=substr($data,2,2);
								$ano=substr($data,4,4);
								$dtcredito=($ano."-".$mes."-".$dia);
								$this->query = "
								SELECT
									cod_usua
								FROM
									usuario
								WHERE
									id_lstn = '".((string)str_pad($reg[1],9,"0",STR_PAD_LEFT))."'
							";
								//echo $this->query();
							$this->query();
							$iCodUsua = $this->qrdata[0]["cod_usua"];
							$this->query = "
								SELECT
									cod_ppst
								FROM
									proponente
								WHERE
									cod_proponente = '".$iCodUsua."'
							";
							//echo $this->query;
							$this->query();
							$iCodPpst = $this->qrdata[0]["cod_ppst"];
								$this->query = "
									UPDATE
										proposta
									SET
										DTASSCONTRATO_PPST ='".$dtcredito."',
										VALORSEGURO_PPST ='".$this->fieldCurrency(mysql_real_escape_string($reg[19]))."'
									WHERE
										cod_ppst='".$iCodPpst."'
								";
								//echo $this->query;
								$this->query();
							
					}elseif(strtoupper(trim($reg[9])) == "D"){
							$this->query = "
								SELECT
									cod_usua
								FROM
									usuario
								WHERE
									id_lstn = '".((string)str_pad($reg[1],9,"0",STR_PAD_LEFT))."'
							";
								//echo $this->query();

							$this->query();
							$iCodUsua = $this->qrdata[0]["cod_usua"];
							$oProponente = new proponente();
							$aDadosProponente = $oProponente->pesquisarPorUsuario($iCodUsua);
							$oHistorico = new historico();
							$oHistorico->inserir($aDadosProponente[(count($aDadosProponente)-1)]['cod_ppst'],date("Y-m-d H:i:s"),'Participante Desimpedido pela Previ.','1','');
						// AQUI VOU INSERIR C�DIGO PARA QUANDO VIER NO ARQUIVO TIPO DE INFORMA��O FN
								$this->query = "
									UPDATE
										listadenomes
									SET
										status_pp='C',
										przmaxfinan='".$this->fieldNumber(mysql_real_escape_string($reg[4]))."'
									WHERE
										id_lstn = '".((string)str_pad($reg[1],9,"0",STR_PAD_LEFT))."'
								";
								//echo $this->query;
								$this->query();
					}elseif(strtoupper(trim($reg[9])) == "DA"){
							$this->query = "
								SELECT
									cod_usua
								FROM
									usuario
								WHERE
									id_lstn = '".((string)str_pad($reg[1],9,"0",STR_PAD_LEFT))."'
							";
								//echo $this->query();

							$this->query();
							$iCodUsua = $this->qrdata[0]["cod_usua"];
							$oProponente = new proponente();
							$aDadosProponente = $oProponente->pesquisarPorUsuario($iCodUsua);
							$oHistorico = new historico();
							$oHistorico->inserir($aDadosProponente[(count($aDadosProponente)-1)]['cod_ppst'],date("Y-m-d H:i:s"),'Retorno da Previ confirmando Apresenta��o dos Documentos.','1','');
					}elseif(strtoupper(trim($reg[9])) == "TR"){
							$this->query = "
								SELECT
									cod_usua
								FROM
									usuario
								WHERE
									id_lstn = '".((string)str_pad($reg[1],9,"0",STR_PAD_LEFT))."'
							";
								//echo $this->query();

							$this->query();
							$iCodUsua = $this->qrdata[0]["cod_usua"];
							$oProponente = new proponente();
							$aDadosProponente = $oProponente->pesquisarPorUsuario($iCodUsua);
							$oHistorico = new historico();
							$oHistorico->inserir($aDadosProponente[(count($aDadosProponente)-1)]['cod_ppst'],date("Y-m-d H:i:s"),'Retorno da Previ confirmando Dados de Transfer�ncia de Financiamento.','1','');
					}elseif(strtoupper(trim($reg[9])) == "IA"){
							$this->query = "
								SELECT
									cod_usua
								FROM
									usuario
								WHERE
									id_lstn = '".((string)str_pad($reg[1],9,"0",STR_PAD_LEFT))."'
							";
								//echo $this->query();

							$this->query();
							$iCodUsua = $this->qrdata[0]["cod_usua"];
							$oProponente = new proponente();
							$aDadosProponente = $oProponente->pesquisarPorUsuario($iCodUsua);
							$oHistorico = new historico();
							$oHistorico->inserir($aDadosProponente[(count($aDadosProponente)-1)]['cod_ppst'],date("Y-m-d H:i:s"),' Retorno da Previ confirmando Aprova��o do Im�vel.','1','');
					}elseif(strtoupper(trim($reg[9])) == "I"){
							$this->query = "
								SELECT
									cod_usua
								FROM
									usuario
								WHERE
									id_lstn = '".((string)str_pad($reg[1],9,"0",STR_PAD_LEFT))."'
							";
								//echo $this->query();

							$this->query();
							$iCodUsua = $this->qrdata[0]["cod_usua"];
							$oProponente = new proponente();
							$aDadosProponente = $oProponente->pesquisarPorUsuario($iCodUsua);
							$oHistorico = new historico();
							$oHistorico->inserir($aDadosProponente[(count($aDadosProponente)-1)]['cod_ppst'],date("Y-m-d H:i:s"),'Participante Impedido pela Previ.','1','');
						// AQUI VOU INSERIR C�DIGO PARA QUANDO VIER NO ARQUIVO TIPO DE INFORMA��O FN
								$this->query = "
									UPDATE
										listadenomes
									SET
										status_pp='".$reg[9]."',
										vlmaxfinan='".$this->fieldCurrency(mysql_real_escape_string($reg[2]))."',
										parcmaxfinan='".$this->fieldCurrency(mysql_real_escape_string($reg[3]))."',
										przmaxfinan='".$this->fieldNumber(mysql_real_escape_string($reg[4]))."'
									WHERE
										id_lstn = '".((string)str_pad($reg[1],9,"0",STR_PAD_LEFT))."'
								";
								//echo $this->query;
								$this->query();												
							}		
					}
				}
			} else {
				$this->setErr(99,"Arquivo de dados inconsistente.");
			}
		}
		return $this->getErrCode();
	}
	
	function recRetorno($sArquivo) {
		$this->clearData();
		$this->setProcLines(0);
		
		//$fileName = "../files/COFSP909.ATHOS.SAI";
		$fileName = ATHOSFILEPATH.$sArquivo;

		$headerFields = "2,8,15,8,6";
		$regFieldsDetermina = "2,500";
		
		// Caso 1: Arquivo recusado
		$regFields[1] = "2,500";
		
		// Caso 2: Registro recusado
		$regFields[2] = "2,10,9,500";
		
		// Caso 3: Arquivo recebido com sucesso
		$regFields[3] = "2,500";
		
		$trailerFields = "2,10";

		if (!$this->openFile($fileName)) {
			if ($this->getNumRows() > 1) {
				$this->goLine(1);
				$header = $this->explodeData($headerFields);
				$this->goLine($this->getNumRows());
				$trail = $this->explodeData($trailerFields);

				$this->setNumRemessa($header[4]);
				$this->setDtRemessa($this->fieldDate($header[3]));

				if ($this->getNumRows() > 2) {
					$aDadosArquivo = $this->getArquivo(ATHOSFILE906IN);
					$iCodArqu = $aDadosArquivo[0]["cod_arqu"];
					
					$aDadosArquivoEnviado = $this->getArquivo(ATHOSFILE906OUT);
					$iCodArquEnviado = $aDadosArquivoEnviado[0]["cod_arqu"];
					for ($i=2;$i<$this->getNumRows();$i++) {
						$this->goLine($i);
						unset($regTemp);
						unset($reg);
						$regTemp = $this->explodeData($regFieldsDetermina);
						if ($regTemp[0] == "01") {
							$reg = $this->explodeData($regFields[(int)$regTemp[0]]);
							$this->setArquivoRemessaErro($iCodArquEnviado,$this->getNumRemessa(),"S");
							$aRegistrosRemessa = $this->getArquivoRegistroRemessa($iCodArquEnviado,$this->getNumRemessa());
							if (is_array($aRegistrosRemessa) && @count($aRegistrosRemessa) > 0) {
								foreach ($aRegistrosRemessa as $iDadoRegistroRemessa => $aDadoRegistroRemessa) {
									$aDadoRegistroRemessa["ocorrencia_arrg"] = $reg[1];
									$aDadoRegistroRemessa["flgerro_arrg"] = "S";
									$this->setRetornoRegistro($aDadoRegistroRemessa);
								}
							}
							$this->setProcLines($this->getProcLines()+1);
						} elseif ($regTemp[0] == "02") {
							$reg = $this->explodeData($regFields[(int)$regTemp[0]]);
							$this->setArquivoRemessaErro($iCodArquEnviado,$this->getNumRemessa(),"R");
							
							$aRegistrosRemessa = $this->getArquivoRegistroRemessa($iCodArquEnviado,$this->getNumRemessa());
							if (is_array($aRegistrosRemessa) && @count($aRegistrosRemessa) > 0) {
								foreach ($aRegistrosRemessa as $iDadoRegistroRemessa => $aDadoRegistroRemessa) {
									if ((int)$aDadoRegistroRemessa["linha_arrg"] == (int)$reg[1]) {
										$aLinhasErro[(int)$reg[1]] = $reg[3];
									}
								}
							}
							
							$this->setProcLines($this->getProcLines()+1);
						} elseif ($regTemp[0] == "03") {
							$reg = $this->explodeData($regFields[(int)$regTemp[0]]);
							$this->setArquivoRemessaErro($iCodArquEnviado,$this->getNumRemessa(),"N");
							$aRegistrosRemessa = $this->getArquivoRegistroRemessa($iCodArquEnviado,$this->getNumRemessa());
							if (is_array($aRegistrosRemessa) && @count($aRegistrosRemessa) > 0) {
								foreach ($aRegistrosRemessa as $iDadoRegistroRemessa => $aDadoRegistroRemessa) {
									$aDadoRegistroRemessa["ocorrencia_arrg"] = "REGISTRO PROCESSADO COM SUCESSO";
									$aDadoRegistroRemessa["flgerro_arrg"] = "N";
									$this->setRetornoRegistro($aDadoRegistroRemessa);
								}
							}
							$this->setProcLines($this->getProcLines()+1);
						}
					}
					
					if ($aLinhasErro) {
						$aRegistrosRemessa = $this->getArquivoRegistroRemessa($iCodArquEnviado,$this->getNumRemessa());
						foreach ($aRegistrosRemessa as $iDadoRegistroRemessa => $aDadoRegistroRemessa) {
							if ($aLinhasErro[(int)$aDadoRegistroRemessa["linha_arrg"]] != "") {
								$aDadoRegistroRemessa["ocorrencia_arrg"] = $aLinhasErro[(int)$aDadoRegistroRemessa["linha_arrg"]];
								$aDadoRegistroRemessa["flgerro_arrg"] = "S";
								$this->setRetornoRegistro($aDadoRegistroRemessa);
							} else {
								$aDadoRegistroRemessa["ocorrencia_arrg"] = "REGISTRO PROCESSADO COM SUCESSO";
								$aDadoRegistroRemessa["flgerro_arrg"] = "N";
								$this->setRetornoRegistro($aDadoRegistroRemessa);
							}
						}
					}
					
				}
				$this->addLogArquivo(ATHOSFILE906IN,$this->getNumRemessa(),$this->getProcLines(),(($this->getNumRows() - 2) - $this->getProcLines() ),$this->getNumRows(),$this->getDtRemessa(),implode("\n",$this->getFileArr()));
			} else {
				$this->setErr(99,"Arquivo de dados inconsistente.");
			}
		}
		return $this->getErrCode();
	}
	

	
	function setErr($code = false, $desc = false) {
		$this->errCode = $code;
		$this->errDesc = $desc;
	}
	function getErrCode() {
		return $this->errCode;
	}
	function getErrDesc() {
		return $this->errDesc;
	}
	
	function clearData() {
		$this->setFileArr(false);
		$this->setCurLine(false);
		$this->setNumRows(false);
		$this->setProcLines(false);
		$this->setNumRemessa(false);
		$this->setDtRemessa(false);
		$this->setErr();
	}
	
	function nLine() {
		$newLine = "\n";
		return $newLine;
	}
	
	function getPos($start,$length) {
		return substr($this->getCurLine(), ($start-1), $length);
	}
	function goLine($lineNumber) {
		$tmpFileArr = $this->getFileArr();
		$this->setCurLine($tmpFileArr[($lineNumber-1)]);
	}
	
	function openFile($fileName) {
	$this->clearData();
		if ($dados = file($fileName)) {
		echo "dados: ".$dados;
			$this->setFileArr($dados);
			$this->setNumRows(@count($this->getFileArr()));
			$this->goLine(1);
		} else {
			$this->setErr(99,"N�o foi poss�vel abrir o arquivo.");
		}
		return $this->getErrCode();
	}
	
	function explodeData($fields) {
		$lengths = explode(",", $fields);
		$start = 0;
		foreach ($lengths as $length) {
			$returnData[] = trim(substr($this->getCurLine(), $start, $length));
			$start += $length;
		}
		return $returnData;
	}
	
	function fieldCurrency($value) {
		return number_format((@(float)$value/100), 2, ".", "");
	}
	function fieldNumber($value) {
		return (int)$value;
	}
	function fieldDate($value) {
		return mktime(0,0,0,substr($value, 2,2),substr($value, 0,2),substr($value, 4,4));
	}
	
	function formatNumber($value,$chars,$charPadding = "0", $charNullPadding = false) {
		$value = eregi_replace("[^0-9]+", "", $value);
		$value = substr($value,0,$chars);
		if ($charNullPadding !== false && $value === "") {
			if ($charNullPadding === "") {
				$charNullPadding = " ";
			}
			return str_pad("", $chars, $charNullPadding, STR_PAD_RIGHT);
		} else {
			return str_pad( eregi_replace("[^0-9]+", "", $value), $chars, $charPadding, STR_PAD_LEFT );
		}
	}
	function formatString($value,$chars,$charPadding = " ") {
		$value = substr($value,0,$chars);
		return str_pad( $value, $chars, $charPadding, STR_PAD_RIGHT );
	}
	function formatDate($value) {
		if ($value) {
			$tmpDate = strtotime($value);
			if ($tmpDate) {
				return date("dmY", $tmpDate);
			}
			return str_pad( "", 8, " ", STR_PAD_RIGHT );
		}
		return str_pad( "", 8, " ", STR_PAD_RIGHT );
	}
	
	function getFileArr() {
		return $this->fileArr;
	}
	function setFileArr($fileArr) {
		unset($this->fileArr);
		$this->fileArr = $fileArr;
	}
	
	function getCurLine() {
		return $this->curLine;
	}
	function setCurLine($curLine) {
		unset($this->curLine);
		$this->curLine = $curLine;
	}

	function getNumRows() {
		return $this->numRows;
	}
	function setNumRows($numRows) {
		unset($this->numRows);
		$this->numRows = $numRows;
	}
	
	function getProcLines() {
		return $this->procLines;
	}
	function setProcLines($procLines) {
		unset($this->procLines);
		$this->procLines = $procLines;
	}
	
	function getNumRemessa() {
		return $this->iNumRemessa;
	}
	function setNumRemessa($numRemessa) {
		$this->iNumRemessa = $numRemessa;
	}
	
	function getDtRemessa() {
		return $this->dtDataRemessa;
	}
	function setDtRemessa($dataRemessa) {
		$this->dtDataRemessa = $dataRemessa;
	}
	
	///////////////////////// MANIPULACAO DE LOGS DE ARQUIVO ///////////////
	function addLogArquivo($arquivo,$remessa,$regProcessados,$regErro,$regTotal,$dtRemessa,$arquivoContent) {
		$aDadosArquivo = $this->getArquivo($arquivo);
		$this->query = "
			UPDATE arquivo SET
				ultimaremessa_arqu = '".$remessa."', 
				dtultimaremessa_arqu = '".date("Y-m-d", $dtRemessa)."'
			WHERE
				nome_arqu = '".mysql_real_escape_string($arquivo)."'
		";
		$this->query();
		if ($aDadosArquivo[0]["cod_arqu"] != "" && (int)$this->getErrNo() === 0) {
			$this->query = "
				INSERT INTO arquivoremessa (
					cod_arqu,
					remessa_arre,
					dtprocessamento_arre,
					conteudo_arre,
					regprocessados_arre,
					regerro_arre,
					regtotal_arre
				) VALUES (
					'".mysql_real_escape_string($aDadosArquivo[0]["cod_arqu"])."',
					'".mysql_real_escape_string($remessa)."',
					NOW(),
					'".mysql_escape_string($arquivoContent)."',
					'".(int)mysql_real_escape_string($regProcessados)."',
					'".(int)mysql_real_escape_string($regErro)."',
					'".(int)mysql_real_escape_string($regTotal)."'
				)
			";
			$this->query();
		}
	}
	
	function getArquivo($arquivo = false) {
		$this->query = "
			SELECT
				cod_arqu,
				nome_arqu,
				descr_arqu,
				ultimaremessa_arqu,
				dtultimaremessa_arqu
			FROM
				arquivo
			".($arquivo ? " WHERE nome_arqu = '".mysql_real_escape_string($arquivo)."' " : "")."
			ORDER BY 
				nome_arqu
		";
		$this->query();
		return $this->qrdata;
	}
	
	function getArquivoConteudo($arquivo,$remessa) {
		$this->query = "
			SELECT
				cod_arqu,
				remessa_arre,
				dtprocessamento_arre,
				conteudo_arre,
				regprocessados_arre,
				regerro_arre,
				regtotal_arre,
				flgerro_arre
			FROM
				arquivoremessa
			WHERE
				cod_arqu = '".mysql_real_escape_string($arquivo)."' and 
				remessa_arre = '".mysql_real_escape_string($remessa)."'
		";
		$this->query();
		return $this->qrdata;
	}
	
	function getArquivoRegistro($cod_ppst,$cod_arqu,$cod_usua,$remessa = false) {
		$this->query = "
			SELECT
				cod_ppst,
				cod_arqu,
				remessa_arre,
				dtgeracao_arrg,
				registro_arrg,
				linha_arrg,
				ocorrencia_arrg,
				dtprocessamento_arrg,
				cod_usua,
				flgerro_arrg
			FROM
				arquivoregistro
			WHERE
				cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and 
				cod_arqu = '".mysql_real_escape_string($cod_arqu)."' and 
				cod_usua = '".mysql_real_escape_string($cod_usua)."' 
				".($remessa ? " and remessa_arre = '".mysql_real_escape_string($remessa)."'" : "")."
			ORDER BY
				remessa_arre DESC
		";
		$this->query();
		return $this->qrdata;
	}
	function getArquivoRegistroRemessa($cod_arqu,$remessa) {
		$this->query = "
			SELECT
				cod_ppst,
				cod_arqu,
				remessa_arre,
				dtgeracao_arrg,
				registro_arrg,
				linha_arrg,
				ocorrencia_arrg,
				dtprocessamento_arrg,
				cod_usua,
				flgerro_arrg
			FROM
				arquivoregistro
			WHERE
				cod_arqu = '".mysql_real_escape_string($cod_arqu)."' and 
				remessa_arre = '".mysql_real_escape_string($remessa)."'
			ORDER BY
				linha_arrg
		";
		$this->query();
		return $this->qrdata;
	}
	function addArquivoRegistro($dados) {
		$this->query = "
			INSERT INTO arquivoregistro (
				cod_ppst,
				cod_arqu,
				remessa_arre,
				dtgeracao_arrg,
				registro_arrg,
				linha_arrg,
				cod_usua
			) VALUES (
				'".mysql_real_escape_string($dados["cod_ppst"])."',
				'".mysql_real_escape_string($dados["cod_arqu"])."',
				'".mysql_real_escape_string($dados["remessa_arre"])."',
				NOW(),
				'".mysql_real_escape_string($dados["registro_arrg"])."',
				'".mysql_real_escape_string($dados["linha_arrg"])."',
				'".mysql_real_escape_string($dados["cod_usua"])."'
			)
		";
		return $this->query();
	}
	
	function setArquivoRemessaErro($arquivo,$remessa,$erro = "N") {
		$this->query = "
			UPDATE
				arquivoremessa
			SET
				flgerro_arre = '".mysql_real_escape_string($erro)."'
			WHERE
				cod_arqu = '".mysql_real_escape_string($arquivo)."' and 
				remessa_arre = '".mysql_real_escape_string($remessa)."'
		";
		return $this->query();
	}
	
	function setRetornoRegistro($dados) {
		$this->query = "
			UPDATE arquivoregistro SET
				ocorrencia_arrg = '".mysql_real_escape_string($dados["ocorrencia_arrg"])."', 
				dtprocessamento_arrg = NOW(), 
				flgerro_arrg = '".mysql_real_escape_string($dados["flgerro_arrg"])."'
			WHERE
				cod_usua = '".mysql_real_escape_string($dados["cod_usua"])."' and 
				cod_ppst = '".mysql_real_escape_string($dados["cod_ppst"])."' and 
				cod_arqu = '".mysql_real_escape_string($dados["cod_arqu"])."' and 
				remessa_arre = '".mysql_real_escape_string($dados["remessa_arre"])."'
				".($dados["linha_arrg"] ? " and linha_arrg = '".mysql_real_escape_string($dados["linha_arrg"])."'" : "")."
		";
		return $this->query();
	}
	
	function get906Estatistica($remessa) {
		$aDadosArquivo = $this->getArquivo(ATHOSFILE906OUT);
		//print_r($aDadosArquivo);
		$iCodArqu = $aDadosArquivo[0]["cod_arqu"];
		
		$this->query = "
			SELECT
				(
					SELECT COUNT(*) as registros FROM
					arquivoregistro
					WHERE
						cod_arqu = '".mysql_real_escape_string($iCodArqu)."' and 
						remessa_arre = '".mysql_real_escape_string($remessa)."' and
						flgerro_arrg = 'S'
				) as registroerro,
				(
					SELECT COUNT(*) as registros FROM
					arquivoregistro
					WHERE
						cod_arqu = '".mysql_real_escape_string($iCodArqu)."' and 
						remessa_arre = '".mysql_real_escape_string($remessa)."' and
						flgerro_arrg = 'N'
				) as registrosucesso
		";
		$this->query();
		return $this->qrdata;
	}
	
	function getArquivoRegistrosErroProposta($cod_ppst) {
		$this->query = "
			SELECT
				arrg.remessa_arre,
				arrg.dtprocessamento_arrg,
				arrg.ocorrencia_arrg
			FROM
				arquivoregistro as arrg,
				arquivo as arqu
			WHERE
				arqu.cod_arqu = arrg.cod_arqu and 
				arqu.nome_arqu = '".mysql_real_escape_string(ATHOSFILE906OUT)."' and 
				arrg.flgerro_arrg = 'S' and 
				arrg.cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
			ORDER BY
				arrg.remessa_arre desc,
				arrg.dtprocessamento_arrg desc
		";
	}

}
					
class arquivoregistro extends database {

	function arquivoregistro() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_ppst,cod_arqu,remessa_arre,dtgeracao_arrg,registro_arrg,linha_arrg,ocorrencia_arrg,dtprocessamento_arrg,cod_usua,flgerro_arrg
			FROM arquivoregistro
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_ppst,$cod_arqu,$remessa_arre,$cod_usua) {
		$this->query = "
			SELECT cod_ppst,cod_arqu,remessa_arre,dtgeracao_arrg,registro_arrg,linha_arrg,ocorrencia_arrg,dtprocessamento_arrg,cod_usua,flgerro_arrg
			FROM arquivoregistro
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_arqu = '".mysql_real_escape_string($cod_arqu)."' and remessa_arre = '".mysql_real_escape_string($remessa_arre)."' and cod_usua = '".mysql_real_escape_string($cod_usua)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_ppst,$cod_arqu,$remessa_arre,$dtgeracao_arrg,$registro_arrg,$linha_arrg,$ocorrencia_arrg,$dtprocessamento_arrg,$cod_usua,$flgerro_arrg) {
		$this->query = "
			UPDATE arquivoregistro SET 
			cod_ppst = ".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",cod_arqu = ".(!$cod_arqu ? "NULL" : "'".mysql_real_escape_string($cod_arqu)."'").",remessa_arre = ".(!$remessa_arre ? "NULL" : "'".mysql_real_escape_string($remessa_arre)."'").",dtgeracao_arrg = ".(!$dtgeracao_arrg ? "NULL" : "'".mysql_real_escape_string($dtgeracao_arrg)."'").",registro_arrg = ".(!$registro_arrg ? "NULL" : "'".mysql_real_escape_string($registro_arrg)."'").",linha_arrg = ".(!$linha_arrg ? "NULL" : "'".mysql_real_escape_string($linha_arrg)."'").",ocorrencia_arrg = ".(!$ocorrencia_arrg ? "NULL" : "'".mysql_real_escape_string($ocorrencia_arrg)."'").",dtprocessamento_arrg = ".(!$dtprocessamento_arrg ? "NULL" : "'".mysql_real_escape_string($dtprocessamento_arrg)."'").",cod_usua = ".(!$cod_usua ? "NULL" : "'".mysql_real_escape_string($cod_usua)."'").",flgerro_arrg = ".(!$flgerro_arrg ? "NULL" : "'".mysql_real_escape_string($flgerro_arrg)."'")."
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_arqu = '".mysql_real_escape_string($cod_arqu)."' and remessa_arre = '".mysql_real_escape_string($remessa_arre)."' and cod_usua = '".mysql_real_escape_string($cod_usua)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_ppst,$cod_arqu,$remessa_arre,$cod_usua) {
		$this->query = "
			DELETE FROM arquivoregistro 
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_arqu = '".mysql_real_escape_string($cod_arqu)."' and remessa_arre = '".mysql_real_escape_string($remessa_arre)."' and cod_usua = '".mysql_real_escape_string($cod_usua)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_ppst,$cod_arqu,$remessa_arre,$dtgeracao_arrg,$registro_arrg,$linha_arrg,$ocorrencia_arrg,$dtprocessamento_arrg,$cod_usua,$flgerro_arrg) {
		$this->query = "
			INSERT INTO arquivoregistro ( cod_ppst,cod_arqu,remessa_arre,dtgeracao_arrg,registro_arrg,linha_arrg,ocorrencia_arrg,dtprocessamento_arrg,cod_usua,flgerro_arrg ) VALUES (
				".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",".(!$cod_arqu ? "NULL" : "'".mysql_real_escape_string($cod_arqu)."'").",".(!$remessa_arre ? "NULL" : "'".mysql_real_escape_string($remessa_arre)."'").",".(!$dtgeracao_arrg ? "NULL" : "'".mysql_real_escape_string($dtgeracao_arrg)."'").",".(!$registro_arrg ? "NULL" : "'".mysql_real_escape_string($registro_arrg)."'").",".(!$linha_arrg ? "NULL" : "'".mysql_real_escape_string($linha_arrg)."'").",".(!$ocorrencia_arrg ? "NULL" : "'".mysql_real_escape_string($ocorrencia_arrg)."'").",".(!$dtprocessamento_arrg ? "NULL" : "'".mysql_real_escape_string($dtprocessamento_arrg)."'").",".(!$cod_usua ? "NULL" : "'".mysql_real_escape_string($cod_usua)."'").",".(!$flgerro_arrg ? "NULL" : "'".mysql_real_escape_string($flgerro_arrg)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class arquivoremessa extends database {

	function arquivoremessa() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_arqu,remessa_arre,dtprocessamento_arre,conteudo_arre,regprocessados_arre,regerro_arre,regtotal_arre,flgerro_arre
			FROM arquivoremessa
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_arqu,$remessa_arre,$dtprocessamento_arre) {
		$this->query = "
			SELECT cod_arqu,remessa_arre,dtprocessamento_arre,conteudo_arre,regprocessados_arre,regerro_arre,regtotal_arre,flgerro_arre
			FROM arquivoremessa
			WHERE cod_arqu = '".mysql_real_escape_string($cod_arqu)."' and remessa_arre = '".mysql_real_escape_string($remessa_arre)."' and dtprocessamento_arre = '".mysql_real_escape_string($dtprocessamento_arre)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_arqu,$remessa_arre,$dtprocessamento_arre,$conteudo_arre,$regprocessados_arre,$regerro_arre,$regtotal_arre,$flgerro_arre) {
		$this->query = "
			UPDATE arquivoremessa SET 
			remessa_arre = ".(!$remessa_arre ? "NULL" : "'".mysql_real_escape_string($remessa_arre)."'").",dtprocessamento_arre = ".(!$dtprocessamento_arre ? "NULL" : "'".mysql_real_escape_string($dtprocessamento_arre)."'").",conteudo_arre = ".(!$conteudo_arre ? "NULL" : "'".mysql_real_escape_string($conteudo_arre)."'").",regprocessados_arre = ".(!$regprocessados_arre ? "NULL" : "'".mysql_real_escape_string($regprocessados_arre)."'").",regerro_arre = ".(!$regerro_arre ? "NULL" : "'".mysql_real_escape_string($regerro_arre)."'").",regtotal_arre = ".(!$regtotal_arre ? "NULL" : "'".mysql_real_escape_string($regtotal_arre)."'").",flgerro_arre = ".(!$flgerro_arre ? "NULL" : "'".mysql_real_escape_string($flgerro_arre)."'")."
			WHERE cod_arqu = '".mysql_real_escape_string($cod_arqu)."' and remessa_arre = '".mysql_real_escape_string($remessa_arre)."' and dtprocessamento_arre = '".mysql_real_escape_string($dtprocessamento_arre)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_arqu,$remessa_arre,$dtprocessamento_arre) {
		$this->query = "
			DELETE FROM arquivoremessa 
			WHERE cod_arqu = '".mysql_real_escape_string($cod_arqu)."' and remessa_arre = '".mysql_real_escape_string($remessa_arre)."' and dtprocessamento_arre = '".mysql_real_escape_string($dtprocessamento_arre)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($remessa_arre,$dtprocessamento_arre,$conteudo_arre,$regprocessados_arre,$regerro_arre,$regtotal_arre,$flgerro_arre) {
		$this->query = "
			INSERT INTO arquivoremessa ( remessa_arre,dtprocessamento_arre,conteudo_arre,regprocessados_arre,regerro_arre,regtotal_arre,flgerro_arre ) VALUES (
				".(!$remessa_arre ? "NULL" : "'".mysql_real_escape_string($remessa_arre)."'").",".(!$dtprocessamento_arre ? "NULL" : "'".mysql_real_escape_string($dtprocessamento_arre)."'").",".(!$conteudo_arre ? "NULL" : "'".mysql_real_escape_string($conteudo_arre)."'").",".(!$regprocessados_arre ? "NULL" : "'".mysql_real_escape_string($regprocessados_arre)."'").",".(!$regerro_arre ? "NULL" : "'".mysql_real_escape_string($regerro_arre)."'").",".(!$regtotal_arre ? "NULL" : "'".mysql_real_escape_string($regtotal_arre)."'").",".(!$flgerro_arre ? "NULL" : "'".mysql_real_escape_string($flgerro_arre)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class bairro extends database {

	function bairro() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_bairro,nome_bairro
			FROM bairro
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_bairro) {
		$this->query = "
			SELECT cod_bairro,nome_bairro
			FROM bairro
			WHERE cod_bairro = '".mysql_real_escape_string($cod_bairro)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_bairro,$nome_bairro) {
		$this->query = "
			UPDATE bairro SET 
			cod_bairro = ".(!$cod_bairro ? "NULL" : "'".mysql_real_escape_string($cod_bairro)."'").",nome_bairro = ".(!$nome_bairro ? "NULL" : "'".mysql_real_escape_string($nome_bairro)."'")."
			WHERE cod_bairro = '".mysql_real_escape_string($cod_bairro)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_bairro) {
		$this->query = "
			DELETE FROM bairro 
			WHERE cod_bairro = '".mysql_real_escape_string($cod_bairro)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_bairro,$nome_bairro) {
		$this->query = "
			INSERT INTO bairro ( cod_bairro,nome_bairro ) VALUES (
				".(!$cod_bairro ? "NULL" : "'".mysql_real_escape_string($cod_bairro)."'").",".(!$nome_bairro ? "NULL" : "'".mysql_real_escape_string($nome_bairro)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

	function getListaBairro($cod_bairro=false) {
		$sqlComplem = ($cod_bairro)?" WHERE cod_bairro='".mysql_real_escape_string($cod_bairro)."' ":"";
		$this->query = "
			SELECT
				cod_bairro,
				nome_bairro
			FROM
				bairro 
			$sqlComplem
			ORDER BY 
				nome_bairro
		";
		$this->query();
		return $this->qrdata;
	}


}
					
class bancos extends database {

	function bancos() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT bnid,layout,nome,codigo,uso_do_banco
			FROM bancos
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($bnid) {
		$this->query = "
			SELECT bnid,layout,nome,codigo,uso_do_banco
			FROM bancos
			WHERE bnid = '".mysql_real_escape_string($bnid)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($bnid,$layout,$nome,$codigo,$uso_do_banco) {
		$this->query = "
			UPDATE bancos SET 
			layout = ".(!$layout ? "NULL" : "'".mysql_real_escape_string($layout)."'").",nome = ".(!$nome ? "NULL" : "'".mysql_real_escape_string($nome)."'").",codigo = ".(!$codigo ? "NULL" : "'".mysql_real_escape_string($codigo)."'").",uso_do_banco = ".(!$uso_do_banco ? "NULL" : "'".mysql_real_escape_string($uso_do_banco)."'")."
			WHERE bnid = '".mysql_real_escape_string($bnid)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($bnid) {
		$this->query = "
			DELETE FROM bancos 
			WHERE bnid = '".mysql_real_escape_string($bnid)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($layout,$nome,$codigo,$uso_do_banco) {
		$this->query = "
			INSERT INTO bancos ( layout,nome,codigo,uso_do_banco ) VALUES (
				".(!$layout ? "NULL" : "'".mysql_real_escape_string($layout)."'").",".(!$nome ? "NULL" : "'".mysql_real_escape_string($nome)."'").",".(!$codigo ? "NULL" : "'".mysql_real_escape_string($codigo)."'").",".(!$uso_do_banco ? "NULL" : "'".mysql_real_escape_string($uso_do_banco)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class boletos extends database {

	function boletos() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT bid,bnid,cid,titulo,agencia,cedente,conta_cedente,especie_documento,codigo,sacado,cpf,local_pagamento,sacador,carteira,instrucoes_linha1,instrucoes_linha2,instrucoes_linha3,instrucoes_linha4,instrucoes_linha5
			FROM boletos
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($bid) {
		$this->query = "
			SELECT bid,bnid,cid,titulo,agencia,cedente,conta_cedente,especie_documento,codigo,sacado,cpf,local_pagamento,sacador,carteira,instrucoes_linha1,instrucoes_linha2,instrucoes_linha3,instrucoes_linha4,instrucoes_linha5
			FROM boletos
			WHERE bid = '".mysql_real_escape_string($bid)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($bid,$bnid,$cid,$titulo,$agencia,$cedente,$conta_cedente,$especie_documento,$codigo,$sacado,$cpf,$local_pagamento,$sacador,$carteira,$instrucoes_linha1,$instrucoes_linha2,$instrucoes_linha3,$instrucoes_linha4,$instrucoes_linha5) {
		$this->query = "
			UPDATE boletos SET 
			bnid = ".(!$bnid ? "NULL" : "'".mysql_real_escape_string($bnid)."'").",cid = ".(!$cid ? "NULL" : "'".mysql_real_escape_string($cid)."'").",titulo = ".(!$titulo ? "NULL" : "'".mysql_real_escape_string($titulo)."'").",agencia = ".(!$agencia ? "NULL" : "'".mysql_real_escape_string($agencia)."'").",cedente = ".(!$cedente ? "NULL" : "'".mysql_real_escape_string($cedente)."'").",conta_cedente = ".(!$conta_cedente ? "NULL" : "'".mysql_real_escape_string($conta_cedente)."'").",especie_documento = ".(!$especie_documento ? "NULL" : "'".mysql_real_escape_string($especie_documento)."'").",codigo = ".(!$codigo ? "NULL" : "'".mysql_real_escape_string($codigo)."'").",sacado = ".(!$sacado ? "NULL" : "'".mysql_real_escape_string($sacado)."'").",cpf = ".(!$cpf ? "NULL" : "'".mysql_real_escape_string($cpf)."'").",local_pagamento = ".(!$local_pagamento ? "NULL" : "'".mysql_real_escape_string($local_pagamento)."'").",sacador = ".(!$sacador ? "NULL" : "'".mysql_real_escape_string($sacador)."'").",carteira = ".(!$carteira ? "NULL" : "'".mysql_real_escape_string($carteira)."'").",instrucoes_linha1 = ".(!$instrucoes_linha1 ? "NULL" : "'".mysql_real_escape_string($instrucoes_linha1)."'").",instrucoes_linha2 = ".(!$instrucoes_linha2 ? "NULL" : "'".mysql_real_escape_string($instrucoes_linha2)."'").",instrucoes_linha3 = ".(!$instrucoes_linha3 ? "NULL" : "'".mysql_real_escape_string($instrucoes_linha3)."'").",instrucoes_linha4 = ".(!$instrucoes_linha4 ? "NULL" : "'".mysql_real_escape_string($instrucoes_linha4)."'").",instrucoes_linha5 = ".(!$instrucoes_linha5 ? "NULL" : "'".mysql_real_escape_string($instrucoes_linha5)."'")."
			WHERE bid = '".mysql_real_escape_string($bid)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($bid) {
		$this->query = "
			DELETE FROM boletos 
			WHERE bid = '".mysql_real_escape_string($bid)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($bnid,$cid,$titulo,$agencia,$cedente,$conta_cedente,$especie_documento,$codigo,$sacado,$cpf,$local_pagamento,$sacador,$carteira,$instrucoes_linha1,$instrucoes_linha2,$instrucoes_linha3,$instrucoes_linha4,$instrucoes_linha5) {
		$this->query = "
			INSERT INTO boletos ( bnid,cid,titulo,agencia,cedente,conta_cedente,especie_documento,codigo,sacado,cpf,local_pagamento,sacador,carteira,instrucoes_linha1,instrucoes_linha2,instrucoes_linha3,instrucoes_linha4,instrucoes_linha5 ) VALUES (
				".(!$bnid ? "NULL" : "'".mysql_real_escape_string($bnid)."'").",".(!$cid ? "NULL" : "'".mysql_real_escape_string($cid)."'").",".(!$titulo ? "NULL" : "'".mysql_real_escape_string($titulo)."'").",".(!$agencia ? "NULL" : "'".mysql_real_escape_string($agencia)."'").",".(!$cedente ? "NULL" : "'".mysql_real_escape_string($cedente)."'").",".(!$conta_cedente ? "NULL" : "'".mysql_real_escape_string($conta_cedente)."'").",".(!$especie_documento ? "NULL" : "'".mysql_real_escape_string($especie_documento)."'").",".(!$codigo ? "NULL" : "'".mysql_real_escape_string($codigo)."'").",".(!$sacado ? "NULL" : "'".mysql_real_escape_string($sacado)."'").",".(!$cpf ? "NULL" : "'".mysql_real_escape_string($cpf)."'").",".(!$local_pagamento ? "NULL" : "'".mysql_real_escape_string($local_pagamento)."'").",".(!$sacador ? "NULL" : "'".mysql_real_escape_string($sacador)."'").",".(!$carteira ? "NULL" : "'".mysql_real_escape_string($carteira)."'").",".(!$instrucoes_linha1 ? "NULL" : "'".mysql_real_escape_string($instrucoes_linha1)."'").",".(!$instrucoes_linha2 ? "NULL" : "'".mysql_real_escape_string($instrucoes_linha2)."'").",".(!$instrucoes_linha3 ? "NULL" : "'".mysql_real_escape_string($instrucoes_linha3)."'").",".(!$instrucoes_linha4 ? "NULL" : "'".mysql_real_escape_string($instrucoes_linha4)."'").",".(!$instrucoes_linha5 ? "NULL" : "'".mysql_real_escape_string($instrucoes_linha5)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class categoria extends database {

	function categoria() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_ctgr,titulo_ctgr,descr_ctgr,flgativo_ctgr
			FROM categoria
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_ctgr) {
		$this->query = "
			SELECT cod_ctgr,titulo_ctgr,descr_ctgr,flgativo_ctgr
			FROM categoria
			WHERE cod_ctgr = '".mysql_real_escape_string($cod_ctgr)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_ctgr,$titulo_ctgr,$descr_ctgr,$flgativo_ctgr) {
		$this->query = "
			UPDATE categoria SET 
			titulo_ctgr = ".(!$titulo_ctgr ? "NULL" : "'".mysql_real_escape_string($titulo_ctgr)."'").",descr_ctgr = ".(!$descr_ctgr ? "NULL" : "'".mysql_real_escape_string($descr_ctgr)."'").",flgativo_ctgr = ".(!$flgativo_ctgr ? "NULL" : "'".mysql_real_escape_string($flgativo_ctgr)."'")."
			WHERE cod_ctgr = '".mysql_real_escape_string($cod_ctgr)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_ctgr) {
		$this->query = "
			DELETE FROM categoria 
			WHERE cod_ctgr = '".mysql_real_escape_string($cod_ctgr)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($titulo_ctgr,$descr_ctgr,$flgativo_ctgr) {
		$this->query = "
			INSERT INTO categoria ( titulo_ctgr,descr_ctgr,flgativo_ctgr ) VALUES (
				".(!$titulo_ctgr ? "NULL" : "'".mysql_real_escape_string($titulo_ctgr)."'").",".(!$descr_ctgr ? "NULL" : "'".mysql_real_escape_string($descr_ctgr)."'").",".(!$flgativo_ctgr ? "NULL" : "'".mysql_real_escape_string($flgativo_ctgr)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class chatmensagens extends database {

	function chatmensagens() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_chtm,dt_chtm,cod_aten,cod_usua,cod_chtu,msg_own,flg_user,flg_aten,mensagem,cod_chat
			FROM chatmensagens
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_chtm) {
		$this->query = "
			SELECT cod_chtm,dt_chtm,cod_aten,cod_usua,cod_chtu,msg_own,flg_user,flg_aten,mensagem,cod_chat
			FROM chatmensagens
			WHERE cod_chtm = '".mysql_real_escape_string($cod_chtm)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_chtm,$dt_chtm,$cod_aten,$cod_usua,$cod_chtu,$msg_own,$flg_user,$flg_aten,$mensagem,$cod_chat) {
		$this->query = "
			UPDATE chatmensagens SET 
			dt_chtm = ".(!$dt_chtm ? "NULL" : "'".mysql_real_escape_string($dt_chtm)."'").",cod_aten = ".(!$cod_aten ? "NULL" : "'".mysql_real_escape_string($cod_aten)."'").",cod_usua = ".(!$cod_usua ? "NULL" : "'".mysql_real_escape_string($cod_usua)."'").",cod_chtu = ".(!$cod_chtu ? "NULL" : "'".mysql_real_escape_string($cod_chtu)."'").",msg_own = ".(!$msg_own ? "NULL" : "'".mysql_real_escape_string($msg_own)."'").",flg_user = ".(!$flg_user ? "NULL" : "'".mysql_real_escape_string($flg_user)."'").",flg_aten = ".(!$flg_aten ? "NULL" : "'".mysql_real_escape_string($flg_aten)."'").",mensagem = ".(!$mensagem ? "NULL" : "'".mysql_real_escape_string($mensagem)."'").",cod_chat = ".(!$cod_chat ? "NULL" : "'".mysql_real_escape_string($cod_chat)."'")."
			WHERE cod_chtm = '".mysql_real_escape_string($cod_chtm)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_chtm) {
		$this->query = "
			DELETE FROM chatmensagens 
			WHERE cod_chtm = '".mysql_real_escape_string($cod_chtm)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($dt_chtm,$cod_aten,$cod_usua,$cod_chtu,$msg_own,$flg_user,$flg_aten,$mensagem,$cod_chat) {
		$this->query = "
			INSERT INTO chatmensagens ( dt_chtm,cod_aten,cod_usua,cod_chtu,msg_own,flg_user,flg_aten,mensagem,cod_chat ) VALUES (
				".(!$dt_chtm ? "NULL" : "'".mysql_real_escape_string($dt_chtm)."'").",".(!$cod_aten ? "NULL" : "'".mysql_real_escape_string($cod_aten)."'").",".(!$cod_usua ? "NULL" : "'".mysql_real_escape_string($cod_usua)."'").",".(!$cod_chtu ? "NULL" : "'".mysql_real_escape_string($cod_chtu)."'").",".(!$msg_own ? "NULL" : "'".mysql_real_escape_string($msg_own)."'").",".(!$flg_user ? "NULL" : "'".mysql_real_escape_string($flg_user)."'").",".(!$flg_aten ? "NULL" : "'".mysql_real_escape_string($flg_aten)."'").",".(!$mensagem ? "NULL" : "'".mysql_real_escape_string($mensagem)."'").",".(!$cod_chat ? "NULL" : "'".mysql_real_escape_string($cod_chat)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class chatsessoes extends database {

	function chatsessoes() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_chat,cod_aten,cod_usua,cod_chtu,flg_disp,dt_aten,dt_usua
			FROM chatsessoes
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_chat) {
		$this->query = "
			SELECT cod_chat,cod_aten,cod_usua,cod_chtu,flg_disp,dt_aten,dt_usua
			FROM chatsessoes
			WHERE cod_chat = '".mysql_real_escape_string($cod_chat)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_chat,$cod_aten,$cod_usua,$cod_chtu,$flg_disp,$dt_aten,$dt_usua) {
		$this->query = "
			UPDATE chatsessoes SET 
			cod_aten = ".(!$cod_aten ? "NULL" : "'".mysql_real_escape_string($cod_aten)."'").",cod_usua = ".(!$cod_usua ? "NULL" : "'".mysql_real_escape_string($cod_usua)."'").",cod_chtu = ".(!$cod_chtu ? "NULL" : "'".mysql_real_escape_string($cod_chtu)."'").",flg_disp = ".(!$flg_disp ? "NULL" : "'".mysql_real_escape_string($flg_disp)."'").",dt_aten = ".(!$dt_aten ? "NULL" : "'".mysql_real_escape_string($dt_aten)."'").",dt_usua = ".(!$dt_usua ? "NULL" : "'".mysql_real_escape_string($dt_usua)."'")."
			WHERE cod_chat = '".mysql_real_escape_string($cod_chat)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_chat) {
		$this->query = "
			DELETE FROM chatsessoes 
			WHERE cod_chat = '".mysql_real_escape_string($cod_chat)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_aten,$cod_usua,$cod_chtu,$flg_disp,$dt_aten,$dt_usua) {
		$this->query = "
			INSERT INTO chatsessoes ( cod_aten,cod_usua,cod_chtu,flg_disp,dt_aten,dt_usua ) VALUES (
				".(!$cod_aten ? "NULL" : "'".mysql_real_escape_string($cod_aten)."'").",".(!$cod_usua ? "NULL" : "'".mysql_real_escape_string($cod_usua)."'").",".(!$cod_chtu ? "NULL" : "'".mysql_real_escape_string($cod_chtu)."'").",".(!$flg_disp ? "NULL" : "'".mysql_real_escape_string($flg_disp)."'").",".(!$dt_aten ? "NULL" : "'".mysql_real_escape_string($dt_aten)."'").",".(!$dt_usua ? "NULL" : "'".mysql_real_escape_string($dt_usua)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class chatusers extends database {

	function chatusers() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_chtu,nome_chtu,email_chtu
			FROM chatusers
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_chtu) {
		$this->query = "
			SELECT cod_chtu,nome_chtu,email_chtu
			FROM chatusers
			WHERE cod_chtu = '".mysql_real_escape_string($cod_chtu)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_chtu,$nome_chtu,$email_chtu) {
		$this->query = "
			UPDATE chatusers SET 
			nome_chtu = ".(!$nome_chtu ? "NULL" : "'".mysql_real_escape_string($nome_chtu)."'").",email_chtu = ".(!$email_chtu ? "NULL" : "'".mysql_real_escape_string($email_chtu)."'")."
			WHERE cod_chtu = '".mysql_real_escape_string($cod_chtu)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_chtu) {
		$this->query = "
			DELETE FROM chatusers 
			WHERE cod_chtu = '".mysql_real_escape_string($cod_chtu)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($nome_chtu,$email_chtu) {
		$this->query = "
			INSERT INTO chatusers ( nome_chtu,email_chtu ) VALUES (
				".(!$nome_chtu ? "NULL" : "'".mysql_real_escape_string($nome_chtu)."'").",".(!$email_chtu ? "NULL" : "'".mysql_real_escape_string($email_chtu)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class clistadvogado extends database {

	function clistadvogado() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_ppst,cod_clad,cod_usua,documento_clad,entidade_clad,dtsolicitacao_clad,dtprevisao_clad,dtemissao_clad,flgatendente_clad,obsadvogado_clad,obsatendente_clad
			FROM clistadvogado
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_clad) {
		$this->query = "
			SELECT cod_ppst,cod_clad,cod_usua,documento_clad,entidade_clad,dtsolicitacao_clad,dtprevisao_clad,dtemissao_clad,flgatendente_clad,obsadvogado_clad,obsatendente_clad
			FROM clistadvogado
			WHERE cod_clad = '".mysql_real_escape_string($cod_clad)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPorProposta($cod_ppst) {
		$this->query = "
			SELECT cod_ppst,cod_clad,cod_usua,documento_clad,entidade_clad,dtsolicitacao_clad,dtprevisao_clad,dtemissao_clad,flgatendente_clad,obsadvogado_clad,obsatendente_clad
			FROM clistadvogado
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_ppst,$cod_clad,$cod_usua,$documento_clad,$entidade_clad,$dtsolicitacao_clad,$dtprevisao_clad,$dtemissao_clad,$flgatendente_clad,$obsadvogado_clad,$obsatendente_clad) {
		$this->query = "
			UPDATE clistadvogado SET 
			cod_ppst = ".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",cod_usua = ".(!$cod_usua ? "NULL" : "'".mysql_real_escape_string($cod_usua)."'").",documento_clad = ".(!$documento_clad ? "NULL" : "'".mysql_real_escape_string($documento_clad)."'").",entidade_clad = ".(!$entidade_clad ? "NULL" : "'".mysql_real_escape_string($entidade_clad)."'").",dtsolicitacao_clad = ".(!$dtsolicitacao_clad ? "NULL" : "'".mysql_real_escape_string($dtsolicitacao_clad)."'").",dtprevisao_clad = ".(!$dtprevisao_clad ? "NULL" : "'".mysql_real_escape_string($dtprevisao_clad)."'").",dtemissao_clad = ".(!$dtemissao_clad ? "NULL" : "'".mysql_real_escape_string($dtemissao_clad)."'").",flgatendente_clad = ".(!$flgatendente_clad ? "NULL" : "'".mysql_real_escape_string($flgatendente_clad)."'").",obsadvogado_clad = ".(!$obsadvogado_clad ? "NULL" : "'".mysql_real_escape_string($obsadvogado_clad)."'").",obsatendente_clad = ".(!$obsatendente_clad ? "NULL" : "'".mysql_real_escape_string($obsatendente_clad)."'")."
			WHERE cod_clad = '".mysql_real_escape_string($cod_clad)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_clad) {
		$this->query = "
			DELETE FROM clistadvogado 
			WHERE cod_clad = '".mysql_real_escape_string($cod_clad)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_ppst,$cod_usua,$documento_clad,$entidade_clad,$dtsolicitacao_clad,$dtprevisao_clad,$dtemissao_clad,$flgatendente_clad,$obsadvogado_clad,$obsatendente_clad) {
		$this->query = "
			INSERT INTO clistadvogado ( cod_ppst,cod_usua,documento_clad,entidade_clad,dtsolicitacao_clad,dtprevisao_clad,dtemissao_clad,flgatendente_clad,obsadvogado_clad,obsatendente_clad ) VALUES (
				".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",".(!$cod_usua ? "NULL" : "'".mysql_real_escape_string($cod_usua)."'").",".(!$documento_clad ? "NULL" : "'".mysql_real_escape_string($documento_clad)."'").",".(!$entidade_clad ? "NULL" : "'".mysql_real_escape_string($entidade_clad)."'").",".(!$dtsolicitacao_clad ? "NULL" : "'".mysql_real_escape_string($dtsolicitacao_clad)."'").",".(!$dtprevisao_clad ? "NULL" : "'".mysql_real_escape_string($dtprevisao_clad)."'").",".(!$dtemissao_clad ? "NULL" : "'".mysql_real_escape_string($dtemissao_clad)."'").",".(!$flgatendente_clad ? "NULL" : "'".mysql_real_escape_string($flgatendente_clad)."'").",".(!$obsadvogado_clad ? "NULL" : "'".mysql_real_escape_string($obsadvogado_clad)."'").",".(!$obsatendente_clad ? "NULL" : "'".mysql_real_escape_string($obsatendente_clad)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class clistimovel extends database {

	function clistimovel() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_mndc,cod_ppst,dtsolicitacao_clim,dtprevisao_clim,dtemissao_clim,flgdespachante_clim,flgatendente_clim,obs_clim,cod_alterador_clim
			FROM clistimovel
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_mndc,$cod_ppst) {
		$this->query = "
			SELECT cod_mndc,cod_ppst,dtsolicitacao_clim,dtprevisao_clim,dtemissao_clim,flgdespachante_clim,flgatendente_clim,obs_clim,cod_alterador_clim
			FROM clistimovel
			WHERE cod_mndc = '".mysql_real_escape_string($cod_mndc)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_mndc,$cod_ppst,$dtsolicitacao_clim,$dtprevisao_clim,$dtemissao_clim,$flgdespachante_clim,$flgatendente_clim,$obs_clim,$cod_alterador_clim) {
		$this->query = "
			UPDATE clistimovel SET 
			cod_mndc = ".(!$cod_mndc ? "NULL" : "'".mysql_real_escape_string($cod_mndc)."'").",cod_ppst = ".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",dtsolicitacao_clim = ".(!$dtsolicitacao_clim ? "NULL" : "'".mysql_real_escape_string($dtsolicitacao_clim)."'").",dtprevisao_clim = ".(!$dtprevisao_clim ? "NULL" : "'".mysql_real_escape_string($dtprevisao_clim)."'").",dtemissao_clim = ".(!$dtemissao_clim ? "NULL" : "'".mysql_real_escape_string($dtemissao_clim)."'").",flgdespachante_clim = ".(!$flgdespachante_clim ? "NULL" : "'".mysql_real_escape_string($flgdespachante_clim)."'").",flgatendente_clim = ".(!$flgatendente_clim ? "NULL" : "'".mysql_real_escape_string($flgatendente_clim)."'").",obs_clim = ".(!$obs_clim ? "NULL" : "'".mysql_real_escape_string($obs_clim)."'").",cod_alterador_clim = ".(!$cod_alterador_clim ? "NULL" : "'".mysql_real_escape_string($cod_alterador_clim)."'")."
			WHERE cod_mndc = '".mysql_real_escape_string($cod_mndc)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_mndc,$cod_ppst) {
		$this->query = "
			DELETE FROM clistimovel 
			WHERE cod_mndc = '".mysql_real_escape_string($cod_mndc)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPorProposta($cod_ppst) {
		$this->query = "
			DELETE FROM clistimovel 
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_mndc,$cod_ppst,$dtsolicitacao_clim,$dtprevisao_clim,$dtemissao_clim,$flgdespachante_clim,$flgatendente_clim,$obs_clim,$cod_alterador_clim) {
		$this->query = "
			INSERT INTO clistimovel ( cod_mndc,cod_ppst,dtsolicitacao_clim,dtprevisao_clim,dtemissao_clim,flgdespachante_clim,flgatendente_clim,obs_clim,cod_alterador_clim ) VALUES (
				".(!$cod_mndc ? "NULL" : "'".mysql_real_escape_string($cod_mndc)."'").",".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",".(!$dtsolicitacao_clim ? "NULL" : "'".mysql_real_escape_string($dtsolicitacao_clim)."'").",".(!$dtprevisao_clim ? "NULL" : "'".mysql_real_escape_string($dtprevisao_clim)."'").",".(!$dtemissao_clim ? "NULL" : "'".mysql_real_escape_string($dtemissao_clim)."'").",".(!$flgdespachante_clim ? "NULL" : "'".mysql_real_escape_string($flgdespachante_clim)."'").",".(!$flgatendente_clim ? "NULL" : "'".mysql_real_escape_string($flgatendente_clim)."'").",".(!$obs_clim ? "NULL" : "'".mysql_real_escape_string($obs_clim)."'").",".(!$cod_alterador_clim ? "NULL" : "'".mysql_real_escape_string($cod_alterador_clim)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class clistproponente extends database {

	function clistproponente() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_mndc,cod_ppst,cod_proponente,dtsolicitacao_clpn,dtprevisao_clpn,dtemissao_clpn,flgdespachante_clpn,flgatendente_clpn,obs_clpn,cod_alterador_clpn
			FROM clistproponente
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_mndc,$cod_ppst,$cod_proponente) {
		$this->query = "
			SELECT cod_mndc,cod_ppst,cod_proponente,dtsolicitacao_clpn,dtprevisao_clpn,dtemissao_clpn,flgdespachante_clpn,flgatendente_clpn,obs_clpn,cod_alterador_clpn
			FROM clistproponente
			WHERE cod_mndc = '".mysql_real_escape_string($cod_mndc)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_proponente = '".mysql_real_escape_string($cod_proponente)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_mndc,$cod_ppst,$cod_proponente,$dtsolicitacao_clpn,$dtprevisao_clpn,$dtemissao_clpn,$flgdespachante_clpn,$flgatendente_clpn,$obs_clpn,$cod_alterador_clpn) {
		$this->query = "
			UPDATE clistproponente SET 
			cod_mndc = ".(!$cod_mndc ? "NULL" : "'".mysql_real_escape_string($cod_mndc)."'").",cod_ppst = ".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",cod_proponente = ".(!$cod_proponente ? "NULL" : "'".mysql_real_escape_string($cod_proponente)."'").",dtsolicitacao_clpn = ".(!$dtsolicitacao_clpn ? "NULL" : "'".mysql_real_escape_string($dtsolicitacao_clpn)."'").",dtprevisao_clpn = ".(!$dtprevisao_clpn ? "NULL" : "'".mysql_real_escape_string($dtprevisao_clpn)."'").",dtemissao_clpn = ".(!$dtemissao_clpn ? "NULL" : "'".mysql_real_escape_string($dtemissao_clpn)."'").",flgdespachante_clpn = ".(!$flgdespachante_clpn ? "NULL" : "'".mysql_real_escape_string($flgdespachante_clpn)."'").",flgatendente_clpn = ".(!$flgatendente_clpn ? "NULL" : "'".mysql_real_escape_string($flgatendente_clpn)."'").",obs_clpn = ".(!$obs_clpn ? "NULL" : "'".mysql_real_escape_string($obs_clpn)."'").",cod_alterador_clpn = ".(!$cod_alterador_clpn ? "NULL" : "'".mysql_real_escape_string($cod_alterador_clpn)."'")."
			WHERE cod_mndc = '".mysql_real_escape_string($cod_mndc)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_proponente = '".mysql_real_escape_string($cod_proponente)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_mndc,$cod_ppst,$cod_proponente) {
		$this->query = "
			DELETE FROM clistproponente 
			WHERE cod_mndc = '".mysql_real_escape_string($cod_mndc)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_proponente = '".mysql_real_escape_string($cod_proponente)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPorPropostaProponente($cod_ppst,$cod_proponente) {
		$this->query = "
			DELETE FROM clistproponente 
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_proponente = '".mysql_real_escape_string($cod_proponente)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_mndc,$cod_ppst,$cod_proponente,$dtsolicitacao_clpn,$dtprevisao_clpn,$dtemissao_clpn,$flgdespachante_clpn,$flgatendente_clpn,$obs_clpn,$cod_alterador_clpn) {
		$this->query = "
			INSERT INTO clistproponente ( cod_mndc,cod_ppst,cod_proponente,dtsolicitacao_clpn,dtprevisao_clpn,dtemissao_clpn,flgdespachante_clpn,flgatendente_clpn,obs_clpn,cod_alterador_clpn ) VALUES (
				".(!$cod_mndc ? "NULL" : "'".mysql_real_escape_string($cod_mndc)."'").",".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",".(!$cod_proponente ? "NULL" : "'".mysql_real_escape_string($cod_proponente)."'").",".(!$dtsolicitacao_clpn ? "NULL" : "'".mysql_real_escape_string($dtsolicitacao_clpn)."'").",".(!$dtprevisao_clpn ? "NULL" : "'".mysql_real_escape_string($dtprevisao_clpn)."'").",".(!$dtemissao_clpn ? "NULL" : "'".mysql_real_escape_string($dtemissao_clpn)."'").",".(!$flgdespachante_clpn ? "NULL" : "'".mysql_real_escape_string($flgdespachante_clpn)."'").",".(!$flgatendente_clpn ? "NULL" : "'".mysql_real_escape_string($flgatendente_clpn)."'").",".(!$obs_clpn ? "NULL" : "'".mysql_real_escape_string($obs_clpn)."'").",".(!$cod_alterador_clpn ? "NULL" : "'".mysql_real_escape_string($cod_alterador_clpn)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}

class agenda extends database {

	function agenda() {
	
	}
	
	function getCodProposta() {
	
		$ID_LSTN = $_POST['ID_LSTN'];
		$this->query = "SELECT P.COD_PPST FROM listadenomes AS L, proponente AS P, usuario AS U WHERE
						L.ID_LSTN = $ID_LSTN AND L.ID_LSTN = U.ID_LSTN AND U.COD_USUA = P.COD_PROPONENTE";
		$this->query();
		
		return $this->qrdata;
	
	}
	
	function getID_LSTN($cod_ppst) {
		
		$this->query = "SELECT L.ID_LSTN FROM listadenomes AS L, proponente AS P, usuario AS U WHERE
						P.COD_PPST = $cod_ppst AND P.COD_PROPONENTE = U.COD_USUA AND U.ID_LSTN = L.ID_LSTN;";
		$this->query();
		
		return $this->qrdata;	
	}
	
	function getCOD_USUA($COD_PPST) {
	
		$this->query = "SELECT COD_PROPONENTE FROM proponente WHERE COD_PPST = '$COD_PPST' ";
		$this->query();
		
		return $this->qrdata;		
	}
	
	function getHistorico($cod_ppst) {
		
		$this->query = "SELECT historico FROM agenda WHERE COD_PPST = $cod_ppst;";
		$this->query();
		
		return $this->qrdata;	
	}	
	
	function putCodProposta($cod_ppst) {		
		
		$ID_LSTN = $this->getID_LSTN($cod_ppst);
		$ID_LSTN = $ID_LSTN[0]['ID_LSTN'];
		
		$this->query = "UPDATE `agenda` SET `COD_PPST` = '$cod_ppst' WHERE `ID_LSTN` = '$ID_LSTN' LIMIT 1 ;";
		//echo " <b> $this->query  </b> <br>";
		$this->query();		
	}
	
	function conferir(){
	
		$utils = new utils();
		$dataTmp_at = date("d/m/y");
	    $var_dia_at = substr($dataTmp_at, 0, 2); // retorna 13;
    	$var_mes_at = substr($dataTmp_at, 3, 2); // retorna 04;
    	$var_ano_at = substr($dataTmp_at, 6, 4); // retorna 04;
		$timestamp_at = mktime(0, 0, 0, $var_mes_at, $var_dia_at, $var_ano_at);
	
		$this->query = "SELECT * FROM `agenda` WHERE `transpotada` = 0";
		$this->query();
		
/*
    [0] => Array
        (
            [ID_LSTN] => 036544960
            [COD_PPST] => 28
            [nome] => 
            [agendamento] => 2008-03-23 00:00:00
            [historico] => jhkjhljh
            [atendente] => 
            [transpotada] => 0
        )
*/		
		
		$retorno = $this->qrdata;
		$n = 0;
		while(isset($retorno[$n])){
		
			$dataTmp = $utils->formataDataBRA($retorno[$n]['agendamento']);
			$var_dia = substr($dataTmp, 0, 2); // retorna 13;
   		 	$var_mes = substr($dataTmp, 3, 2); // retorna 04;
    		$var_ano = substr($dataTmp, 6, 4); // retorna 04;
			$timestamp = mktime(0, 0, 0, $var_mes, $var_dia, $var_ano);
		
			if($retorno[$n]['COD_PPST'] > 0 && $timestamp < $timestamp_at)
				$this->transpotar($retorno[$n]['COD_PPST']);
			
			$n++;
		}
	}
	
	function transpotar($cod_ppst) {

		$historico = $this->getHistorico($cod_ppst);
		$historico = $historico[0]['historico'];
		
		$COD_USUA = $this->getCOD_USUA($cod_ppst);
		$COD_USUA = $COD_USUA[0]['COD_PROPONENTE'];
			
		$this->query="Insert into historico (COD_PPST, DT_HIST, OBS_HIST, TIPO_HIST, COD_USUA) values ('".$cod_ppst."',now(),'".$historico."','2','".$COD_USUA."')";
		//echo " <b> $this->query  </b> <br>";
		$this->query();
		
		
		$this->query = "UPDATE `agenda` SET `transpotada` = '1' WHERE `COD_PPST` = '$cod_ppst' LIMIT 1 ;";
		//echo " <b> $this->query  </b> <br>";
		$this->query();				
		
	}
	
	
	function listarVendedor() {
	
		$atendente = $_POST['agenda_atendente'];
		$this->query="SELECT * FROM agenda WHERE atendente = '$atendente' AND transpotada = 0;";
		$this->query();
		
		return $this->qrdata;		
	}
	
	function listarAtendentes() {
	
		$cod = $_POST['agenda_atendente'];
		$this->query="SELECT * FROM usuario WHERE LEVEL_USUA = 2 AND ATIVO = 1 ORDER BY NOME_USUA;";
		$this->query();
		
		return $this->qrdata;
	}	
	
	function getAgenda() {
	
		$ID_LSTN = $_POST["filtro_matricula"];
		$utils = new utils();
		$ID_LSTN = $utils->limpaMatricula($ID_LSTN);
		
		$this->query = "SELECT * FROM agenda WHERE ID_LSTN = '$ID_LSTN'";
		$this->query();
		
		return $this->qrdata;		
		
	}

	function getAgenda2() {
	
		$ID_LSTN = $_POST["filtro_mat"];
		$utils = new utils();
		$ID_LSTN = $utils->limpaMatricula($ID_LSTN);
		
		$this->query = "SELECT * FROM agenda WHERE ID_LSTN = '$ID_LSTN'";
		$this->query();
		
		return $this->qrdata;		
		
	}
	
	function getAgenda3() {
	
		$ID_LSTN = $_GET["id"];
		
		$this->query = "SELECT * FROM agenda WHERE ID_LSTN = '$ID_LSTN'";
		$this->query();
		
		return $this->qrdata;		
		
	}	
	
	function getAgendaDia() {
	
		$hoje = date("Y-m-d 00:00:00");
		
		$this->query = "SELECT * FROM agenda WHERE agendamento = '$hoje'";
		$this->query();
		
		return $this->qrdata;		
		
	}	
	
	function insert() {
	
		$ID_LSTN = $_POST['ID_LSTN'];
		$agendamento = $_POST['agenda_agendamento'];
		$historico = $_POST['agenda_historico'];
		$nome = $_POST['agenda_nome'];
		$cod_ppst = $this->getCodProposta();
		$cod_ppst = $cod_ppst[0]['COD_PPST'];	
		
		if(!isset($cod_ppst))
			$cod_ppst = 0;
			
		$utils = new utils();
		$agendamento = $utils->formataData($agendamento);
		$atendente = $_POST['agenda_atendente'];
	
		$this->query = "DELETE FROM agenda WHERE ID_LSTN = $ID_LSTN";
		$this->query();
		
		$this->query = "
				INSERT INTO `agenda` (
				`ID_LSTN` ,
				`COD_PPST` ,
				`nome` ,
				`agendamento` ,
				`historico` ,
				`atendente`
				)
				VALUES (
				'$ID_LSTN', '$cod_ppst' , '$nome', '$agendamento', '$historico', '$atendente'
				);";
		//echo $this->query;
		
		if($this->query())
			return 1;
	}
	
	function listar() {
	
	}
	
	function atualizar() {
	
	}
}
					
class clistproponenteconjuge extends database {

	function clistproponenteconjuge() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_mndc,cod_ppst,cod_proponente,dtsolicitacao_clpc,dtprevisao_clpc,dtemissao_clpc,flgdespachante_clpc,flgatendente_clpc,obs_clpc,cod_alterador_clpc
			FROM clistproponenteconjuge
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_mndc,$cod_ppst,$cod_proponente) {
		$this->query = "
			SELECT cod_mndc,cod_ppst,cod_proponente,dtsolicitacao_clpc,dtprevisao_clpc,dtemissao_clpc,flgdespachante_clpc,flgatendente_clpc,obs_clpc,cod_alterador_clpc
			FROM clistproponenteconjuge
			WHERE cod_mndc = '".mysql_real_escape_string($cod_mndc)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_proponente = '".mysql_real_escape_string($cod_proponente)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_mndc,$cod_ppst,$cod_proponente,$dtsolicitacao_clpc,$dtprevisao_clpc,$dtemissao_clpc,$flgdespachante_clpc,$flgatendente_clpc,$obs_clpc,$cod_alterador_clpc) {
		$this->query = "
			UPDATE clistproponenteconjuge SET 
			cod_mndc = ".(!$cod_mndc ? "NULL" : "'".mysql_real_escape_string($cod_mndc)."'").",cod_ppst = ".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",cod_proponente = ".(!$cod_proponente ? "NULL" : "'".mysql_real_escape_string($cod_proponente)."'").",dtsolicitacao_clpc = ".(!$dtsolicitacao_clpc ? "NULL" : "'".mysql_real_escape_string($dtsolicitacao_clpc)."'").",dtprevisao_clpc = ".(!$dtprevisao_clpc ? "NULL" : "'".mysql_real_escape_string($dtprevisao_clpc)."'").",dtemissao_clpc = ".(!$dtemissao_clpc ? "NULL" : "'".mysql_real_escape_string($dtemissao_clpc)."'").",flgdespachante_clpc = ".(!$flgdespachante_clpc ? "NULL" : "'".mysql_real_escape_string($flgdespachante_clpc)."'").",flgatendente_clpc = ".(!$flgatendente_clpc ? "NULL" : "'".mysql_real_escape_string($flgatendente_clpc)."'").",obs_clpc = ".(!$obs_clpc ? "NULL" : "'".mysql_real_escape_string($obs_clpc)."'").",cod_alterador_clpc = ".(!$cod_alterador_clpc ? "NULL" : "'".mysql_real_escape_string($cod_alterador_clpc)."'")."
			WHERE cod_mndc = '".mysql_real_escape_string($cod_mndc)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_proponente = '".mysql_real_escape_string($cod_proponente)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_mndc,$cod_ppst,$cod_proponente) {
		$this->query = "
			DELETE FROM clistproponenteconjuge 
			WHERE cod_mndc = '".mysql_real_escape_string($cod_mndc)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_proponente = '".mysql_real_escape_string($cod_proponente)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPorPropostaProponente($cod_ppst,$cod_proponente) {
		$this->query = "
			DELETE FROM clistproponenteconjuge 
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_proponente = '".mysql_real_escape_string($cod_proponente)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_mndc,$cod_ppst,$cod_proponente,$dtsolicitacao_clpc,$dtprevisao_clpc,$dtemissao_clpc,$flgdespachante_clpc,$flgatendente_clpc,$obs_clpc,$cod_alterador_clpc) {
		$this->query = "
			INSERT INTO clistproponenteconjuge ( cod_mndc,cod_ppst,cod_proponente,dtsolicitacao_clpc,dtprevisao_clpc,dtemissao_clpc,flgdespachante_clpc,flgatendente_clpc,obs_clpc,cod_alterador_clpc ) VALUES (
				".(!$cod_mndc ? "NULL" : "'".mysql_real_escape_string($cod_mndc)."'").",".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",".(!$cod_proponente ? "NULL" : "'".mysql_real_escape_string($cod_proponente)."'").",".(!$dtsolicitacao_clpc ? "NULL" : "'".mysql_real_escape_string($dtsolicitacao_clpc)."'").",".(!$dtprevisao_clpc ? "NULL" : "'".mysql_real_escape_string($dtprevisao_clpc)."'").",".(!$dtemissao_clpc ? "NULL" : "'".mysql_real_escape_string($dtemissao_clpc)."'").",".(!$flgdespachante_clpc ? "NULL" : "'".mysql_real_escape_string($flgdespachante_clpc)."'").",".(!$flgatendente_clpc ? "NULL" : "'".mysql_real_escape_string($flgatendente_clpc)."'").",".(!$obs_clpc ? "NULL" : "'".mysql_real_escape_string($obs_clpc)."'").",".(!$cod_alterador_clpc ? "NULL" : "'".mysql_real_escape_string($cod_alterador_clpc)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}

class clistproponentefgts extends database {

	function clistproponentefgts() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_mndc,cod_ppst,cod_proponente,dtsolicitacao_clfg,dtprevisao_clfg,dtemissao_clfg,flgdespachante_clfg,flgatendente_clfg,obs_clfg,cod_alterador_clfg
			FROM clistproponentefgts
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_mndc,$cod_ppst,$cod_proponente) {
		$this->query = "
			SELECT cod_mndc,cod_ppst,cod_proponente,dtsolicitacao_clfg,dtprevisao_clfg,dtemissao_clfg,flgdespachante_clfg,flgatendente_clfg,obs_clfg,cod_alterador_clfg
			FROM clistproponentefgts
			WHERE cod_mndc = '".mysql_real_escape_string($cod_mndc)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_proponente = '".mysql_real_escape_string($cod_proponente)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_mndc,$cod_ppst,$cod_proponente,$dtsolicitacao_clfg,$dtprevisao_clfg,$dtemissao_clfg,$flgdespachante_clfg,$flgatendente_clfg,$obs_clfg,$cod_alterador_clfg) {
		$this->query = "
			UPDATE clistproponentefgts SET 
			cod_mndc = ".(!$cod_mndc ? "NULL" : "'".mysql_real_escape_string($cod_mndc)."'").",cod_ppst = ".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",cod_proponente = ".(!$cod_proponente ? "NULL" : "'".mysql_real_escape_string($cod_proponente)."'").",dtsolicitacao_clfg = ".(!$dtsolicitacao_clfg ? "NULL" : "'".mysql_real_escape_string($dtsolicitacao_clfg)."'").",dtprevisao_clfg = ".(!$dtprevisao_clfg ? "NULL" : "'".mysql_real_escape_string($dtprevisao_clfg)."'").",dtemissao_clfg = ".(!$dtemissao_clfg ? "NULL" : "'".mysql_real_escape_string($dtemissao_clfg)."'").",flgdespachante_clfg = ".(!$flgdespachante_clfg ? "NULL" : "'".mysql_real_escape_string($flgdespachante_clfg)."'").",flgatendente_clfg = ".(!$flgatendente_clfg ? "NULL" : "'".mysql_real_escape_string($flgatendente_clfg)."'").",obs_clfg = ".(!$obs_clfg ? "NULL" : "'".mysql_real_escape_string($obs_clfg)."'").",cod_alterador_clfg = ".(!$cod_alterador_clfg ? "NULL" : "'".mysql_real_escape_string($cod_alterador_clfg)."'")."
			WHERE cod_mndc = '".mysql_real_escape_string($cod_mndc)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_proponente = '".mysql_real_escape_string($cod_proponente)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_mndc,$cod_ppst,$cod_proponente) {
		$this->query = "
			DELETE FROM clistproponentefgts 
			WHERE cod_mndc = '".mysql_real_escape_string($cod_mndc)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_proponente = '".mysql_real_escape_string($cod_proponente)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPorPropostaProponente($cod_ppst,$cod_proponente) {
		$this->query = "
			DELETE FROM clistproponentefgts 
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_proponente = '".mysql_real_escape_string($cod_proponente)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_mndc,$cod_ppst,$cod_proponente,$dtsolicitacao_clfg,$dtprevisao_clfg,$dtemissao_clfg,$flgdespachante_clfg,$flgatendente_clfg,$obs_clfg,$cod_alterador_clfg) {
		$this->query = "
			INSERT INTO clistproponentefgts ( cod_mndc,cod_ppst,cod_proponente,dtsolicitacao_clfg,dtprevisao_clfg,dtemissao_clfg,flgdespachante_clfg,flgatendente_clfg,obs_clfg,cod_alterador_clfg ) VALUES (
				".(!$cod_mndc ? "NULL" : "'".mysql_real_escape_string($cod_mndc)."'").",".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",".(!$cod_proponente ? "NULL" : "'".mysql_real_escape_string($cod_proponente)."'").",".(!$dtsolicitacao_clfg ? "NULL" : "'".mysql_real_escape_string($dtsolicitacao_clfg)."'").",".(!$dtprevisao_clfg ? "NULL" : "'".mysql_real_escape_string($dtprevisao_clfg)."'").",".(!$dtemissao_clfg ? "NULL" : "'".mysql_real_escape_string($dtemissao_clfg)."'").",".(!$flgdespachante_clfg ? "NULL" : "'".mysql_real_escape_string($flgdespachante_clfg)."'").",".(!$flgatendente_clfg ? "NULL" : "'".mysql_real_escape_string($flgatendente_clfg)."'").",".(!$obs_clfg ? "NULL" : "'".mysql_real_escape_string($obs_clfg)."'").",".(!$cod_alterador_clfg ? "NULL" : "'".mysql_real_escape_string($cod_alterador_clfg)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}

class clistvendfis extends database {

	function clistvendfis() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_mndc,cod_ppst,cod_vend,dtsolicitacao_clvf,dtprevisao_clvf,dtemissao_clvf,flgdespachante_clvf,flgatendente_clvf,obs_clvf,cod_alterador_clvf
			FROM clistvendfis
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_mndc,$cod_ppst,$cod_vend) {
		$this->query = "
			SELECT cod_mndc,cod_ppst,cod_vend,dtsolicitacao_clvf,dtprevisao_clvf,dtemissao_clvf,flgdespachante_clvf,flgatendente_clvf,obs_clvf,cod_alterador_clvf
			FROM clistvendfis
			WHERE cod_mndc = '".mysql_real_escape_string($cod_mndc)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_vend = '".mysql_real_escape_string($cod_vend)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_mndc,$cod_ppst,$cod_vend,$dtsolicitacao_clvf,$dtprevisao_clvf,$dtemissao_clvf,$flgdespachante_clvf,$flgatendente_clvf,$obs_clvf,$cod_alterador_clvf) {
		$this->query = "
			UPDATE clistvendfis SET 
			cod_mndc = ".(!$cod_mndc ? "NULL" : "'".mysql_real_escape_string($cod_mndc)."'").",cod_ppst = ".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",cod_vend = ".(!$cod_vend ? "NULL" : "'".mysql_real_escape_string($cod_vend)."'").",dtsolicitacao_clvf = ".(!$dtsolicitacao_clvf ? "NULL" : "'".mysql_real_escape_string($dtsolicitacao_clvf)."'").",dtprevisao_clvf = ".(!$dtprevisao_clvf ? "NULL" : "'".mysql_real_escape_string($dtprevisao_clvf)."'").",dtemissao_clvf = ".(!$dtemissao_clvf ? "NULL" : "'".mysql_real_escape_string($dtemissao_clvf)."'").",flgdespachante_clvf = ".(!$flgdespachante_clvf ? "NULL" : "'".mysql_real_escape_string($flgdespachante_clvf)."'").",flgatendente_clvf = ".(!$flgatendente_clvf ? "NULL" : "'".mysql_real_escape_string($flgatendente_clvf)."'").",obs_clvf = ".(!$obs_clvf ? "NULL" : "'".mysql_real_escape_string($obs_clvf)."'").",cod_alterador_clvf = ".(!$cod_alterador_clvf ? "NULL" : "'".mysql_real_escape_string($cod_alterador_clvf)."'")."
			WHERE cod_mndc = '".mysql_real_escape_string($cod_mndc)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_vend = '".mysql_real_escape_string($cod_vend)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_mndc,$cod_ppst,$cod_vend) {
		$this->query = "
			DELETE FROM clistvendfis 
			WHERE cod_mndc = '".mysql_real_escape_string($cod_mndc)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_vend = '".mysql_real_escape_string($cod_vend)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPorPropostaVendedor($cod_ppst,$cod_vend) {
		$this->query = "
			DELETE FROM clistvendfis 
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_vend = '".mysql_real_escape_string($cod_vend)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_mndc,$cod_ppst,$cod_vend,$dtsolicitacao_clvf,$dtprevisao_clvf,$dtemissao_clvf,$flgdespachante_clvf,$flgatendente_clvf,$obs_clvf,$cod_alterador_clvf) {
		$this->query = "
			INSERT INTO clistvendfis ( cod_mndc,cod_ppst,cod_vend,dtsolicitacao_clvf,dtprevisao_clvf,dtemissao_clvf,flgdespachante_clvf,flgatendente_clvf,obs_clvf,cod_alterador_clvf ) VALUES (
				".(!$cod_mndc ? "NULL" : "'".mysql_real_escape_string($cod_mndc)."'").",".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",".(!$cod_vend ? "NULL" : "'".mysql_real_escape_string($cod_vend)."'").",".(!$dtsolicitacao_clvf ? "NULL" : "'".mysql_real_escape_string($dtsolicitacao_clvf)."'").",".(!$dtprevisao_clvf ? "NULL" : "'".mysql_real_escape_string($dtprevisao_clvf)."'").",".(!$dtemissao_clvf ? "NULL" : "'".mysql_real_escape_string($dtemissao_clvf)."'").",".(!$flgdespachante_clvf ? "NULL" : "'".mysql_real_escape_string($flgdespachante_clvf)."'").",".(!$flgatendente_clvf ? "NULL" : "'".mysql_real_escape_string($flgatendente_clvf)."'").",".(!$obs_clvf ? "NULL" : "'".mysql_real_escape_string($obs_clvf)."'").",".(!$cod_alterador_clvf ? "NULL" : "'".mysql_real_escape_string($cod_alterador_clvf)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class clistvendfisconjuge extends database {

	function clistvendfisconjuge() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_mndc,cod_ppst,cod_vend,dtsolicitacao_clvc,dtprevisao_clvc,dtemissao_clvc,flgdespachante_clvc,flgatendente_clvc,obs_clvc,cod_alterador_clvc
			FROM clistvendfisconjuge
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_mndc,$cod_ppst,$cod_vend) {
		$this->query = "
			SELECT cod_mndc,cod_ppst,cod_vend,dtsolicitacao_clvc,dtprevisao_clvc,dtemissao_clvc,flgdespachante_clvc,flgatendente_clvc,obs_clvc,cod_alterador_clvc
			FROM clistvendfisconjuge
			WHERE cod_mndc = '".mysql_real_escape_string($cod_mndc)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_vend = '".mysql_real_escape_string($cod_vend)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_mndc,$cod_ppst,$cod_vend,$dtsolicitacao_clvc,$dtprevisao_clvc,$dtemissao_clvc,$flgdespachante_clvc,$flgatendente_clvc,$obs_clvc,$cod_alterador_clvc) {
		$this->query = "
			UPDATE clistvendfisconjuge SET 
			cod_mndc = ".(!$cod_mndc ? "NULL" : "'".mysql_real_escape_string($cod_mndc)."'").",cod_ppst = ".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",cod_vend = ".(!$cod_vend ? "NULL" : "'".mysql_real_escape_string($cod_vend)."'").",dtsolicitacao_clvc = ".(!$dtsolicitacao_clvc ? "NULL" : "'".mysql_real_escape_string($dtsolicitacao_clvc)."'").",dtprevisao_clvc = ".(!$dtprevisao_clvc ? "NULL" : "'".mysql_real_escape_string($dtprevisao_clvc)."'").",dtemissao_clvc = ".(!$dtemissao_clvc ? "NULL" : "'".mysql_real_escape_string($dtemissao_clvc)."'").",flgdespachante_clvc = ".(!$flgdespachante_clvc ? "NULL" : "'".mysql_real_escape_string($flgdespachante_clvc)."'").",flgatendente_clvc = ".(!$flgatendente_clvc ? "NULL" : "'".mysql_real_escape_string($flgatendente_clvc)."'").",obs_clvc = ".(!$obs_clvc ? "NULL" : "'".mysql_real_escape_string($obs_clvc)."'").",cod_alterador_clvc = ".(!$cod_alterador_clvc ? "NULL" : "'".mysql_real_escape_string($cod_alterador_clvc)."'")."
			WHERE cod_mndc = '".mysql_real_escape_string($cod_mndc)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_vend = '".mysql_real_escape_string($cod_vend)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_mndc,$cod_ppst,$cod_vend) {
		$this->query = "
			DELETE FROM clistvendfisconjuge 
			WHERE cod_mndc = '".mysql_real_escape_string($cod_mndc)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_vend = '".mysql_real_escape_string($cod_vend)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPorPropostaVendedor($cod_ppst,$cod_vend) {
		$this->query = "
			DELETE FROM clistvendfisconjuge 
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_vend = '".mysql_real_escape_string($cod_vend)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_mndc,$cod_ppst,$cod_vend,$dtsolicitacao_clvc,$dtprevisao_clvc,$dtemissao_clvc,$flgdespachante_clvc,$flgatendente_clvc,$obs_clvc,$cod_alterador_clvc) {
		$this->query = "
			INSERT INTO clistvendfisconjuge ( cod_mndc,cod_ppst,cod_vend,dtsolicitacao_clvc,dtprevisao_clvc,dtemissao_clvc,flgdespachante_clvc,flgatendente_clvc,obs_clvc,cod_alterador_clvc ) VALUES (
				".(!$cod_mndc ? "NULL" : "'".mysql_real_escape_string($cod_mndc)."'").",".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",".(!$cod_vend ? "NULL" : "'".mysql_real_escape_string($cod_vend)."'").",".(!$dtsolicitacao_clvc ? "NULL" : "'".mysql_real_escape_string($dtsolicitacao_clvc)."'").",".(!$dtprevisao_clvc ? "NULL" : "'".mysql_real_escape_string($dtprevisao_clvc)."'").",".(!$dtemissao_clvc ? "NULL" : "'".mysql_real_escape_string($dtemissao_clvc)."'").",".(!$flgdespachante_clvc ? "NULL" : "'".mysql_real_escape_string($flgdespachante_clvc)."'").",".(!$flgatendente_clvc ? "NULL" : "'".mysql_real_escape_string($flgatendente_clvc)."'").",".(!$obs_clvc ? "NULL" : "'".mysql_real_escape_string($obs_clvc)."'").",".(!$cod_alterador_clvc ? "NULL" : "'".mysql_real_escape_string($cod_alterador_clvc)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class clistvendjur extends database {

	function clistvendjur() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_mndc,cod_ppst,cod_vend,dtsolicitacao_clpn,dtprevisao_clpn,dtemissao_clpn,flgdespachante_clpn,flgatendente_clpn,obs_clpn,cod_alterador_clpn
			FROM clistvendjur
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_mndc,$cod_ppst,$cod_vend) {
		$this->query = "
			SELECT cod_mndc,cod_ppst,cod_vend,dtsolicitacao_clpn,dtprevisao_clpn,dtemissao_clpn,flgdespachante_clpn,flgatendente_clpn,obs_clpn,cod_alterador_clpn
			FROM clistvendjur
			WHERE cod_mndc = '".mysql_real_escape_string($cod_mndc)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_vend = '".mysql_real_escape_string($cod_vend)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_mndc,$cod_ppst,$cod_vend,$dtsolicitacao_clpn,$dtprevisao_clpn,$dtemissao_clpn,$flgdespachante_clpn,$flgatendente_clpn,$obs_clpn,$cod_alterador_clpn) {
		$this->query = "
			UPDATE clistvendjur SET 
			cod_mndc = ".(!$cod_mndc ? "NULL" : "'".mysql_real_escape_string($cod_mndc)."'").",cod_ppst = ".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",cod_vend = ".(!$cod_vend ? "NULL" : "'".mysql_real_escape_string($cod_vend)."'").",dtsolicitacao_clpn = ".(!$dtsolicitacao_clpn ? "NULL" : "'".mysql_real_escape_string($dtsolicitacao_clpn)."'").",dtprevisao_clpn = ".(!$dtprevisao_clpn ? "NULL" : "'".mysql_real_escape_string($dtprevisao_clpn)."'").",dtemissao_clpn = ".(!$dtemissao_clpn ? "NULL" : "'".mysql_real_escape_string($dtemissao_clpn)."'").",flgdespachante_clpn = ".(!$flgdespachante_clpn ? "NULL" : "'".mysql_real_escape_string($flgdespachante_clpn)."'").",flgatendente_clpn = ".(!$flgatendente_clpn ? "NULL" : "'".mysql_real_escape_string($flgatendente_clpn)."'").",obs_clpn = ".(!$obs_clpn ? "NULL" : "'".mysql_real_escape_string($obs_clpn)."'").",cod_alterador_clpn = ".(!$cod_alterador_clpn ? "NULL" : "'".mysql_real_escape_string($cod_alterador_clpn)."'")."
			WHERE cod_mndc = '".mysql_real_escape_string($cod_mndc)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_vend = '".mysql_real_escape_string($cod_vend)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_mndc,$cod_ppst,$cod_vend) {
		$this->query = "
			DELETE FROM clistvendjur 
			WHERE cod_mndc = '".mysql_real_escape_string($cod_mndc)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_vend = '".mysql_real_escape_string($cod_vend)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPorPropostaVendedor($cod_ppst,$cod_vend) {
		$this->query = "
			DELETE FROM clistvendjur 
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_vend = '".mysql_real_escape_string($cod_vend)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_mndc,$cod_ppst,$cod_vend,$dtsolicitacao_clpn,$dtprevisao_clpn,$dtemissao_clpn,$flgdespachante_clpn,$flgatendente_clpn,$obs_clpn,$cod_alterador_clpn) {
		$this->query = "
			INSERT INTO clistvendjur ( cod_mndc,cod_ppst,cod_vend,dtsolicitacao_clpn,dtprevisao_clpn,dtemissao_clpn,flgdespachante_clpn,flgatendente_clpn,obs_clpn,cod_alterador_clpn ) VALUES (
				".(!$cod_mndc ? "NULL" : "'".mysql_real_escape_string($cod_mndc)."'").",".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",".(!$cod_vend ? "NULL" : "'".mysql_real_escape_string($cod_vend)."'").",".(!$dtsolicitacao_clpn ? "NULL" : "'".mysql_real_escape_string($dtsolicitacao_clpn)."'").",".(!$dtprevisao_clpn ? "NULL" : "'".mysql_real_escape_string($dtprevisao_clpn)."'").",".(!$dtemissao_clpn ? "NULL" : "'".mysql_real_escape_string($dtemissao_clpn)."'").",".(!$flgdespachante_clpn ? "NULL" : "'".mysql_real_escape_string($flgdespachante_clpn)."'").",".(!$flgatendente_clpn ? "NULL" : "'".mysql_real_escape_string($flgatendente_clpn)."'").",".(!$obs_clpn ? "NULL" : "'".mysql_real_escape_string($obs_clpn)."'").",".(!$cod_alterador_clpn ? "NULL" : "'".mysql_real_escape_string($cod_alterador_clpn)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class cnae extends database {

	function cnae() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_cnae,desc_cnae
			FROM cnae
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_cnae) {
		$this->query = "
			SELECT cod_cnae,desc_cnae
			FROM cnae
			WHERE cod_cnae = '".mysql_real_escape_string($cod_cnae)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_cnae,$desc_cnae) {
		$this->query = "
			UPDATE cnae SET 
			cod_cnae = ".(!$cod_cnae ? "NULL" : "'".mysql_real_escape_string($cod_cnae)."'").",desc_cnae = ".(!$desc_cnae ? "NULL" : "'".mysql_real_escape_string($desc_cnae)."'")."
			WHERE cod_cnae = '".mysql_real_escape_string($cod_cnae)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_cnae) {
		$this->query = "
			DELETE FROM cnae 
			WHERE cod_cnae = '".mysql_real_escape_string($cod_cnae)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_cnae,$desc_cnae) {
		$this->query = "
			INSERT INTO cnae ( cod_cnae,desc_cnae ) VALUES (
				".(!$cod_cnae ? "NULL" : "'".mysql_real_escape_string($cod_cnae)."'").",".(!$desc_cnae ? "NULL" : "'".mysql_real_escape_string($desc_cnae)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

	function getListaAtivEcon($cod_cnae=false) {
		$sqlComplem = ($cod_cnae)?" WHERE cod_cnae='".mysql_real_escape_string($cod_cnae)."' ":"";
		$this->query = "
			SELECT
				cod_cnae,
				desc_cnae
			FROM
				cnae 
			$sqlComplem
			ORDER BY 
				desc_cnae
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class config extends database {

	function config() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cid,titulo,enviar_email,remetente,remetente_email,assunto,servidor_smtp,servidor_http,imagem_tipo,usar_truetype,enviar_pdf,mensagem_texto,mensagem_html
			FROM config
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cid) {
		$this->query = "
			SELECT cid,titulo,enviar_email,remetente,remetente_email,assunto,servidor_smtp,servidor_http,imagem_tipo,usar_truetype,enviar_pdf,mensagem_texto,mensagem_html
			FROM config
			WHERE cid = '".mysql_real_escape_string($cid)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cid,$titulo,$enviar_email,$remetente,$remetente_email,$assunto,$servidor_smtp,$servidor_http,$imagem_tipo,$usar_truetype,$enviar_pdf,$mensagem_texto,$mensagem_html) {
		$this->query = "
			UPDATE config SET 
			titulo = ".(!$titulo ? "NULL" : "'".mysql_real_escape_string($titulo)."'").",enviar_email = ".(!$enviar_email ? "NULL" : "'".mysql_real_escape_string($enviar_email)."'").",remetente = ".(!$remetente ? "NULL" : "'".mysql_real_escape_string($remetente)."'").",remetente_email = ".(!$remetente_email ? "NULL" : "'".mysql_real_escape_string($remetente_email)."'").",assunto = ".(!$assunto ? "NULL" : "'".mysql_real_escape_string($assunto)."'").",servidor_smtp = ".(!$servidor_smtp ? "NULL" : "'".mysql_real_escape_string($servidor_smtp)."'").",servidor_http = ".(!$servidor_http ? "NULL" : "'".mysql_real_escape_string($servidor_http)."'").",imagem_tipo = ".(!$imagem_tipo ? "NULL" : "'".mysql_real_escape_string($imagem_tipo)."'").",usar_truetype = ".(!$usar_truetype ? "NULL" : "'".mysql_real_escape_string($usar_truetype)."'").",enviar_pdf = ".(!$enviar_pdf ? "NULL" : "'".mysql_real_escape_string($enviar_pdf)."'").",mensagem_texto = ".(!$mensagem_texto ? "NULL" : "'".mysql_real_escape_string($mensagem_texto)."'").",mensagem_html = ".(!$mensagem_html ? "NULL" : "'".mysql_real_escape_string($mensagem_html)."'")."
			WHERE cid = '".mysql_real_escape_string($cid)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cid) {
		$this->query = "
			DELETE FROM config 
			WHERE cid = '".mysql_real_escape_string($cid)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($titulo,$enviar_email,$remetente,$remetente_email,$assunto,$servidor_smtp,$servidor_http,$imagem_tipo,$usar_truetype,$enviar_pdf,$mensagem_texto,$mensagem_html) {
		$this->query = "
			INSERT INTO config ( titulo,enviar_email,remetente,remetente_email,assunto,servidor_smtp,servidor_http,imagem_tipo,usar_truetype,enviar_pdf,mensagem_texto,mensagem_html ) VALUES (
				".(!$titulo ? "NULL" : "'".mysql_real_escape_string($titulo)."'").",".(!$enviar_email ? "NULL" : "'".mysql_real_escape_string($enviar_email)."'").",".(!$remetente ? "NULL" : "'".mysql_real_escape_string($remetente)."'").",".(!$remetente_email ? "NULL" : "'".mysql_real_escape_string($remetente_email)."'").",".(!$assunto ? "NULL" : "'".mysql_real_escape_string($assunto)."'").",".(!$servidor_smtp ? "NULL" : "'".mysql_real_escape_string($servidor_smtp)."'").",".(!$servidor_http ? "NULL" : "'".mysql_real_escape_string($servidor_http)."'").",".(!$imagem_tipo ? "NULL" : "'".mysql_real_escape_string($imagem_tipo)."'").",".(!$usar_truetype ? "NULL" : "'".mysql_real_escape_string($usar_truetype)."'").",".(!$enviar_pdf ? "NULL" : "'".mysql_real_escape_string($enviar_pdf)."'").",".(!$mensagem_texto ? "NULL" : "'".mysql_real_escape_string($mensagem_texto)."'").",".(!$mensagem_html ? "NULL" : "'".mysql_real_escape_string($mensagem_html)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class conteudo extends database {

	function conteudo() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_cotd,titulo_cotd,descr_cotd,flgativo_cotd,tipo_cotd,arquivo_cotd,legenda_cotd,texto_cotd
			FROM conteudo
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_cotd) {
		$this->query = "
			SELECT cod_cotd,titulo_cotd,descr_cotd,flgativo_cotd,tipo_cotd,arquivo_cotd,legenda_cotd,texto_cotd
			FROM conteudo
			WHERE cod_cotd = '".mysql_real_escape_string($cod_cotd)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_cotd,$titulo_cotd,$descr_cotd,$flgativo_cotd,$tipo_cotd,$arquivo_cotd,$legenda_cotd,$texto_cotd) {
		$this->query = "
			UPDATE conteudo SET 
			titulo_cotd = ".(!$titulo_cotd ? "NULL" : "'".mysql_real_escape_string($titulo_cotd)."'").",descr_cotd = ".(!$descr_cotd ? "NULL" : "'".mysql_real_escape_string($descr_cotd)."'").",flgativo_cotd = ".(!$flgativo_cotd ? "NULL" : "'".mysql_real_escape_string($flgativo_cotd)."'").",tipo_cotd = ".(!$tipo_cotd ? "NULL" : "'".mysql_real_escape_string($tipo_cotd)."'").",arquivo_cotd = ".(!$arquivo_cotd ? "NULL" : "'".mysql_real_escape_string($arquivo_cotd)."'").",legenda_cotd = ".(!$legenda_cotd ? "NULL" : "'".mysql_real_escape_string($legenda_cotd)."'").",texto_cotd = ".(!$texto_cotd ? "NULL" : "'".mysql_real_escape_string($texto_cotd)."'")."
			WHERE cod_cotd = '".mysql_real_escape_string($cod_cotd)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_cotd) {
		$this->query = "
			DELETE FROM conteudo 
			WHERE cod_cotd = '".mysql_real_escape_string($cod_cotd)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($titulo_cotd,$descr_cotd,$flgativo_cotd,$tipo_cotd,$arquivo_cotd,$legenda_cotd,$texto_cotd) {
		$this->query = "
			INSERT INTO conteudo ( titulo_cotd,descr_cotd,flgativo_cotd,tipo_cotd,arquivo_cotd,legenda_cotd,texto_cotd ) VALUES (
				".(!$titulo_cotd ? "NULL" : "'".mysql_real_escape_string($titulo_cotd)."'").",".(!$descr_cotd ? "NULL" : "'".mysql_real_escape_string($descr_cotd)."'").",".(!$flgativo_cotd ? "NULL" : "'".mysql_real_escape_string($flgativo_cotd)."'").",".(!$tipo_cotd ? "NULL" : "'".mysql_real_escape_string($tipo_cotd)."'").",".(!$arquivo_cotd ? "NULL" : "'".mysql_real_escape_string($arquivo_cotd)."'").",".(!$legenda_cotd ? "NULL" : "'".mysql_real_escape_string($legenda_cotd)."'").",".(!$texto_cotd ? "NULL" : "'".mysql_real_escape_string($texto_cotd)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

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
					
class despachante extends database {

	function despachante() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_usua,nome_dpct,contato_dpct,telcel_dpct,telcom_dpct,ramal_dpct,telfax_dpct,email_dpct,endereco_dpct,nrendereco_dpct,cpendereco_dpct,cod_logr,cod_bairro,cod_uf,cod_municipio,cpfcnpj_dpct,nrbanco_dpct,nragencia_dpct,nrcc_dpct,nrdvcc_dpct,obs_dpct
			FROM despachante
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_usua) {
		$this->query = "
			SELECT cod_usua,nome_dpct,contato_dpct,telcel_dpct,telcom_dpct,ramal_dpct,telfax_dpct,email_dpct,endereco_dpct,nrendereco_dpct,cpendereco_dpct,cod_logr,cod_bairro,cod_uf,cod_municipio,cpfcnpj_dpct,nrbanco_dpct,nragencia_dpct,nrcc_dpct,nrdvcc_dpct,obs_dpct
			FROM despachante
			WHERE cod_usua = '".mysql_real_escape_string($cod_usua)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_usua,$nome_dpct,$contato_dpct,$telcel_dpct,$telcom_dpct,$ramal_dpct,$telfax_dpct,$email_dpct,$endereco_dpct,$nrendereco_dpct,$cpendereco_dpct,$cod_logr,$cod_bairro,$cod_uf,$cod_municipio,$cpfcnpj_dpct,$nrbanco_dpct,$nragencia_dpct,$nrcc_dpct,$nrdvcc_dpct,$obs_dpct) {
		$this->query = "
			UPDATE despachante SET 
			cod_usua = ".(!$cod_usua ? "NULL" : "'".mysql_real_escape_string($cod_usua)."'").",nome_dpct = ".(!$nome_dpct ? "NULL" : "'".mysql_real_escape_string($nome_dpct)."'").",contato_dpct = ".(!$contato_dpct ? "NULL" : "'".mysql_real_escape_string($contato_dpct)."'").",telcel_dpct = ".(!$telcel_dpct ? "NULL" : "'".mysql_real_escape_string($telcel_dpct)."'").",telcom_dpct = ".(!$telcom_dpct ? "NULL" : "'".mysql_real_escape_string($telcom_dpct)."'").",ramal_dpct = ".(!$ramal_dpct ? "NULL" : "'".mysql_real_escape_string($ramal_dpct)."'").",telfax_dpct = ".(!$telfax_dpct ? "NULL" : "'".mysql_real_escape_string($telfax_dpct)."'").",email_dpct = ".(!$email_dpct ? "NULL" : "'".mysql_real_escape_string($email_dpct)."'").",endereco_dpct = ".(!$endereco_dpct ? "NULL" : "'".mysql_real_escape_string($endereco_dpct)."'").",nrendereco_dpct = ".(!$nrendereco_dpct ? "NULL" : "'".mysql_real_escape_string($nrendereco_dpct)."'").",cpendereco_dpct = ".(!$cpendereco_dpct ? "NULL" : "'".mysql_real_escape_string($cpendereco_dpct)."'").",cod_logr = ".(!$cod_logr ? "NULL" : "'".mysql_real_escape_string($cod_logr)."'").",cod_bairro = ".(!$cod_bairro ? "NULL" : "'".mysql_real_escape_string($cod_bairro)."'").",cod_uf = ".(!$cod_uf ? "NULL" : "'".mysql_real_escape_string($cod_uf)."'").",cod_municipio = ".(!$cod_municipio ? "NULL" : "'".mysql_real_escape_string($cod_municipio)."'").",cpfcnpj_dpct = ".(!$cpfcnpj_dpct ? "NULL" : "'".mysql_real_escape_string($cpfcnpj_dpct)."'").",nrbanco_dpct = ".(!$nrbanco_dpct ? "NULL" : "'".mysql_real_escape_string($nrbanco_dpct)."'").",nragencia_dpct = ".(!$nragencia_dpct ? "NULL" : "'".mysql_real_escape_string($nragencia_dpct)."'").",nrcc_dpct = ".(!$nrcc_dpct ? "NULL" : "'".mysql_real_escape_string($nrcc_dpct)."'").",nrdvcc_dpct = ".(!$nrdvcc_dpct ? "NULL" : "'".mysql_real_escape_string($nrdvcc_dpct)."'").",obs_dpct = ".(!$obs_dpct ? "NULL" : "'".mysql_real_escape_string($obs_dpct)."'")."
			WHERE cod_usua = '".mysql_real_escape_string($cod_usua)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_usua) {
		$this->query = "
			DELETE FROM despachante 
			WHERE cod_usua = '".mysql_real_escape_string($cod_usua)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_usua,$nome_dpct,$contato_dpct,$telcel_dpct,$telcom_dpct,$ramal_dpct,$telfax_dpct,$email_dpct,$endereco_dpct,$nrendereco_dpct,$cpendereco_dpct,$cod_logr,$cod_bairro,$cod_uf,$cod_municipio,$cpfcnpj_dpct,$nrbanco_dpct,$nragencia_dpct,$nrcc_dpct,$nrdvcc_dpct,$obs_dpct) {
		$this->query = "
			INSERT INTO despachante ( cod_usua,nome_dpct,contato_dpct,telcel_dpct,telcom_dpct,ramal_dpct,telfax_dpct,email_dpct,endereco_dpct,nrendereco_dpct,cpendereco_dpct,cod_logr,cod_bairro,cod_uf,cod_municipio,cpfcnpj_dpct,nrbanco_dpct,nragencia_dpct,nrcc_dpct,nrdvcc_dpct,obs_dpct ) VALUES (
				".(!$cod_usua ? "NULL" : "'".mysql_real_escape_string($cod_usua)."'").",".(!$nome_dpct ? "NULL" : "'".mysql_real_escape_string($nome_dpct)."'").",".(!$contato_dpct ? "NULL" : "'".mysql_real_escape_string($contato_dpct)."'").",".(!$telcel_dpct ? "NULL" : "'".mysql_real_escape_string($telcel_dpct)."'").",".(!$telcom_dpct ? "NULL" : "'".mysql_real_escape_string($telcom_dpct)."'").",".(!$ramal_dpct ? "NULL" : "'".mysql_real_escape_string($ramal_dpct)."'").",".(!$telfax_dpct ? "NULL" : "'".mysql_real_escape_string($telfax_dpct)."'").",".(!$email_dpct ? "NULL" : "'".mysql_real_escape_string($email_dpct)."'").",".(!$endereco_dpct ? "NULL" : "'".mysql_real_escape_string($endereco_dpct)."'").",".(!$nrendereco_dpct ? "NULL" : "'".mysql_real_escape_string($nrendereco_dpct)."'").",".(!$cpendereco_dpct ? "NULL" : "'".mysql_real_escape_string($cpendereco_dpct)."'").",".(!$cod_logr ? "NULL" : "'".mysql_real_escape_string($cod_logr)."'").",".(!$cod_bairro ? "NULL" : "'".mysql_real_escape_string($cod_bairro)."'").",".(!$cod_uf ? "NULL" : "'".mysql_real_escape_string($cod_uf)."'").",".(!$cod_municipio ? "NULL" : "'".mysql_real_escape_string($cod_municipio)."'").",".(!$cpfcnpj_dpct ? "NULL" : "'".mysql_real_escape_string($cpfcnpj_dpct)."'").",".(!$nrbanco_dpct ? "NULL" : "'".mysql_real_escape_string($nrbanco_dpct)."'").",".(!$nragencia_dpct ? "NULL" : "'".mysql_real_escape_string($nragencia_dpct)."'").",".(!$nrcc_dpct ? "NULL" : "'".mysql_real_escape_string($nrcc_dpct)."'").",".(!$nrdvcc_dpct ? "NULL" : "'".mysql_real_escape_string($nrdvcc_dpct)."'").",".(!$obs_dpct ? "NULL" : "'".mysql_real_escape_string($obs_dpct)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class devsol extends database {

	function devsol() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_ppst,nome_devsol,nick_devsol,cod_logr,endereco_devsol,nrendereco_devsol,cpendereco_devsol,cod_bairro,cep_devsol,cod_uf,cod_municipio,telefone_devsol,cpf_devsol,sexo_devsol,cod_pais,cod_proponente
			FROM devsol
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_ppst,$cod_proponente) {
		$this->query = "
			SELECT cod_ppst,nome_devsol,nick_devsol,cod_logr,endereco_devsol,nrendereco_devsol,cpendereco_devsol,cod_bairro,cep_devsol,cod_uf,cod_municipio,telefone_devsol,cpf_devsol,sexo_devsol,cod_pais,cod_proponente
			FROM devsol
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_proponente = '".mysql_real_escape_string($cod_proponente)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_ppst,$nome_devsol,$nick_devsol,$cod_logr,$endereco_devsol,$nrendereco_devsol,$cpendereco_devsol,$cod_bairro,$cep_devsol,$cod_uf,$cod_municipio,$telefone_devsol,$cpf_devsol,$sexo_devsol,$cod_pais,$cod_proponente) {
		$this->query = "
			UPDATE devsol SET 
			cod_ppst = ".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",nome_devsol = ".(!$nome_devsol ? "NULL" : "'".mysql_real_escape_string($nome_devsol)."'").",nick_devsol = ".(!$nick_devsol ? "NULL" : "'".mysql_real_escape_string($nick_devsol)."'").",cod_logr = ".(!$cod_logr ? "NULL" : "'".mysql_real_escape_string($cod_logr)."'").",endereco_devsol = ".(!$endereco_devsol ? "NULL" : "'".mysql_real_escape_string($endereco_devsol)."'").",nrendereco_devsol = ".(!$nrendereco_devsol ? "0" : "'".mysql_real_escape_string($nrendereco_devsol)."'").",cpendereco_devsol = ".(!$cpendereco_devsol ? "NULL" : "'".mysql_real_escape_string($cpendereco_devsol)."'").",cod_bairro = ".(!$cod_bairro ? "NULL" : "'".mysql_real_escape_string($cod_bairro)."'").",cep_devsol = ".(!$cep_devsol ? "NULL" : "'".mysql_real_escape_string($cep_devsol)."'").",cod_uf = ".(!$cod_uf ? "NULL" : "'".mysql_real_escape_string($cod_uf)."'").",cod_municipio = ".(!$cod_municipio ? "NULL" : "'".mysql_real_escape_string($cod_municipio)."'").",telefone_devsol = ".(!$telefone_devsol ? "NULL" : "'".mysql_real_escape_string($telefone_devsol)."'").",cpf_devsol = ".(!$cpf_devsol ? "NULL" : "'".mysql_real_escape_string($cpf_devsol)."'").",sexo_devsol = ".(!$sexo_devsol ? "NULL" : "'".mysql_real_escape_string($sexo_devsol)."'").",cod_pais = ".(!$cod_pais ? "NULL" : "'".mysql_real_escape_string($cod_pais)."'").",cod_proponente = ".(!$cod_proponente ? "NULL" : "'".mysql_real_escape_string($cod_proponente)."'")."
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_proponente = '".mysql_real_escape_string($cod_proponente)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_ppst,$cod_proponente) {
		$this->query = "
			DELETE FROM devsol 
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_proponente = '".mysql_real_escape_string($cod_proponente)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_ppst,$nome_devsol,$nick_devsol,$cod_logr,$endereco_devsol,$nrendereco_devsol,$cpendereco_devsol,$cod_bairro,$cep_devsol,$cod_uf,$cod_municipio,$telefone_devsol,$cpf_devsol,$sexo_devsol,$cod_pais,$cod_proponente) {
		$this->query = "
			INSERT INTO devsol ( cod_ppst,nome_devsol,nick_devsol,cod_logr,endereco_devsol,nrendereco_devsol,cpendereco_devsol,cod_bairro,cep_devsol,cod_uf,cod_municipio,telefone_devsol,cpf_devsol,sexo_devsol,cod_pais,cod_proponente ) VALUES (
				".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",".(!$nome_devsol ? "NULL" : "'".mysql_real_escape_string($nome_devsol)."'").",".(!$nick_devsol ? "NULL" : "'".mysql_real_escape_string($nick_devsol)."'").",".(!$cod_logr ? "NULL" : "'".mysql_real_escape_string($cod_logr)."'").",".(!$endereco_devsol ? "NULL" : "'".mysql_real_escape_string($endereco_devsol)."'").",".(!$nrendereco_devsol ? "0" : "'".mysql_real_escape_string($nrendereco_devsol)."'").",".(!$cpendereco_devsol ? "NULL" : "'".mysql_real_escape_string($cpendereco_devsol)."'").",".(!$cod_bairro ? "NULL" : "'".mysql_real_escape_string($cod_bairro)."'").",".(!$cep_devsol ? "NULL" : "'".mysql_real_escape_string($cep_devsol)."'").",".(!$cod_uf ? "NULL" : "'".mysql_real_escape_string($cod_uf)."'").",".(!$cod_municipio ? "NULL" : "'".mysql_real_escape_string($cod_municipio)."'").",".(!$telefone_devsol ? "NULL" : "'".mysql_real_escape_string($telefone_devsol)."'").",".(!$cpf_devsol ? "NULL" : "'".mysql_real_escape_string($cpf_devsol)."'").",".(!$sexo_devsol ? "NULL" : "'".mysql_real_escape_string($sexo_devsol)."'").",".(!$cod_pais ? "NULL" : "'".mysql_real_escape_string($cod_pais)."'").",".(!$cod_proponente ? "NULL" : "'".mysql_real_escape_string($cod_proponente)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}
	
	function pesquisarPorCpf($cpf) {
		$this->query = "
			SELECT cod_ppst,nome_devsol,nick_devsol,cod_logr,endereco_devsol,nrendereco_devsol,cpendereco_devsol,cod_bairro,cep_devsol,cod_uf,cod_municipio,telefone_devsol,cpf_devsol,sexo_devsol,cod_pais,cod_proponente
			FROM devsol
			WHERE
				cpf_devsol = '".$cpf."'
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class documento extends database {

	function documento() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_docm,nome_docm,descr_docm,validade_docm
			FROM documento
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_docm) {
		$this->query = "
			SELECT cod_docm,nome_docm,descr_docm,validade_docm
			FROM documento
			WHERE cod_docm = '".mysql_real_escape_string($cod_docm)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_docm,$nome_docm,$descr_docm,$validade_docm) {
		$this->query = "
			UPDATE documento SET 
			nome_docm = ".(!$nome_docm ? "NULL" : "'".mysql_real_escape_string($nome_docm)."'").",descr_docm = ".(!$descr_docm ? "NULL" : "'".mysql_real_escape_string($descr_docm)."'").",validade_docm = ".(!$validade_docm ? "NULL" : "'".mysql_real_escape_string($validade_docm)."'")."
			WHERE cod_docm = '".mysql_real_escape_string($cod_docm)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_docm) {
		$this->query = "
			DELETE FROM documento 
			WHERE cod_docm = '".mysql_real_escape_string($cod_docm)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($nome_docm,$descr_docm,$validade_docm) {
		$this->query = "
			INSERT INTO documento ( nome_docm,descr_docm,validade_docm ) VALUES (
				".(!$nome_docm ? "NULL" : "'".mysql_real_escape_string($nome_docm)."'").",".(!$descr_docm ? "NULL" : "'".mysql_real_escape_string($descr_docm)."'").",".(!$validade_docm ? "NULL" : "'".mysql_real_escape_string($validade_docm)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class entidade extends database {

	function entidade() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_enti,nome_enti,descr_enti
			FROM entidade
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_enti) {
		$this->query = "
			SELECT cod_enti,nome_enti,descr_enti
			FROM entidade
			WHERE cod_enti = '".mysql_real_escape_string($cod_enti)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_enti,$nome_enti,$descr_enti) {
		$this->query = "
			UPDATE entidade SET 
			nome_enti = ".(!$nome_enti ? "NULL" : "'".mysql_real_escape_string($nome_enti)."'").",descr_enti = ".(!$descr_enti ? "NULL" : "'".mysql_real_escape_string($descr_enti)."'")."
			WHERE cod_enti = '".mysql_real_escape_string($cod_enti)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_enti) {
		$this->query = "
			DELETE FROM entidade 
			WHERE cod_enti = '".mysql_real_escape_string($cod_enti)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($nome_enti,$descr_enti) {
		$this->query = "
			INSERT INTO entidade ( nome_enti,descr_enti ) VALUES (
				".(!$nome_enti ? "NULL" : "'".mysql_real_escape_string($nome_enti)."'").",".(!$descr_enti ? "NULL" : "'".mysql_real_escape_string($descr_enti)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}
	
	function getListaEntidade() {
		$this->query = "
			SELECT
				cod_enti,
				nome_enti,
				descr_enti
			FROM
				entidade 
			ORDER BY 
				nome_enti,
				descr_enti
		";
		$this->query();
		return $this->qrdata;
	}
	
	function getEntidade($cod_enti) {
		$this->query = "
			SELECT
				cod_enti,
				nome_enti,
				descr_enti
			FROM
				entidade
			WHERE
				cod_enti = '".mysql_real_escape_string($cod_enti)."'
		";
		$this->query();
		return $this->qrdata;
	}
	
	function addEntidade($dadosEntidade) {
		$this->query = "
			INSERT INTO entidade (
				nome_enti,
				descr_enti
			) VALUES (
				'".mysql_real_escape_string($dadosEntidade['nome_enti'])."',
				'".mysql_real_escape_string($dadosEntidade['descr_enti'])."'
			)
		";
		return $this->query();
	}
	
	function delEntidade($cod_enti) {
		$this->query = "
			DELETE FROM entidade
			WHERE cod_enti = '".mysql_real_escape_string($cod_enti)."'
		";
		return $this->query();
	}
	
	function updEntidade($dadosEntidade) {
		$this->query = "
			UPDATE entidade SET 
				nome_enti = '".mysql_real_escape_string($dadosEntidade["nome_enti"])."',
				descr_enti = '".mysql_real_escape_string($dadosEntidade["descr_enti"])."'
			WHERE
				cod_enti = '".mysql_real_escape_string($dadosEntidade["cod_enti"])."'
		";
		return $this->query();
	}

}
					
class estadocivil extends database {

	function estadocivil() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_estciv,desc_estciv,flgprevi_estciv
			FROM estadocivil
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_estciv) {
		$this->query = "
			SELECT cod_estciv,desc_estciv,flgprevi_estciv
			FROM estadocivil
			WHERE cod_estciv = '".mysql_real_escape_string($cod_estciv)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_estciv,$desc_estciv,$flgprevi_estciv) {
		$this->query = "
			UPDATE estadocivil SET 
			cod_estciv = ".(!$cod_estciv ? "NULL" : "'".mysql_real_escape_string($cod_estciv)."'").",desc_estciv = ".(!$desc_estciv ? "NULL" : "'".mysql_real_escape_string($desc_estciv)."'").",flgprevi_estciv = ".(!$flgprevi_estciv ? "NULL" : "'".mysql_real_escape_string($flgprevi_estciv)."'")."
			WHERE cod_estciv = '".mysql_real_escape_string($cod_estciv)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_estciv) {
		$this->query = "
			DELETE FROM estadocivil 
			WHERE cod_estciv = '".mysql_real_escape_string($cod_estciv)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_estciv,$desc_estciv,$flgprevi_estciv) {
		$this->query = "
			INSERT INTO estadocivil ( cod_estciv,desc_estciv,flgprevi_estciv ) VALUES (
				".(!$cod_estciv ? "NULL" : "'".mysql_real_escape_string($cod_estciv)."'").",".(!$desc_estciv ? "NULL" : "'".mysql_real_escape_string($desc_estciv)."'").",".(!$flgprevi_estciv ? "NULL" : "'".mysql_real_escape_string($flgprevi_estciv)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}
	
	function getListaECivil() {
		$this->query = "
			SELECT
				cod_estciv,
				desc_estciv
			FROM
				estadocivil 
			ORDER BY 
				desc_estciv
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class historico extends database {

	function historico() {
		
	}

	function listarMotivos() {
		$this->query = "
			SELECT *
			FROM historico_motivos
			WHERE ativo = 1
		";
		$this->query();
		return $this->qrdata;
	}
	
	function listar() {
		$this->query = "
			SELECT cod_ppst,seq_hist,dt_hist,obs_hist,tipo_hist,cod_usua
			FROM historico
		";
		$this->query();
		return $this->qrdata;
	}

	function listarPorProposta($cod_ppst) {
		$this->query = "
			SELECT cod_ppst,seq_hist,dt_hist,obs_hist,tipo_hist,cod_usua, arquivo_anexo, path_arquivo_anexo, apto, historico_motivos.motivo
			FROM historico
			LEFT JOIN historico_motivos ON historico_motivos.id = historico.motivo
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
			ORDER BY seq_hist DESC
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($seq_hist) {
		$this->query = "
			SELECT cod_ppst,seq_hist,dt_hist,obs_hist,tipo_hist,cod_usua
			FROM historico
			WHERE seq_hist = '".mysql_real_escape_string($seq_hist)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_ppst,$seq_hist,$dt_hist,$obs_hist,$tipo_hist,$cod_usua) {
		$this->query = "
			UPDATE historico SET 
			cod_ppst = ".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",dt_hist = ".(!$dt_hist ? "NULL" : "'".mysql_real_escape_string($dt_hist)."'").",obs_hist = ".(!$obs_hist ? "NULL" : "'".mysql_real_escape_string($obs_hist)."'").",tipo_hist = ".(!$tipo_hist ? "NULL" : "'".mysql_real_escape_string($tipo_hist)."'").",cod_usua = ".(!$cod_usua ? "NULL" : "'".mysql_real_escape_string($cod_usua)."'")."
			WHERE seq_hist = '".mysql_real_escape_string($seq_hist)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($seq_hist) {
		$this->query = "
			DELETE FROM historico 
			WHERE seq_hist = '".mysql_real_escape_string($seq_hist)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_ppst,$dt_hist,$obs_hist = 'Histórico sem observações',$tipo_hist,$cod_usua, $nome_arquivo = null, $path_arquivo = null, $apto = 'NI', $motivo = 0) {
		 $this->query = "
			INSERT INTO historico ( cod_ppst,dt_hist,obs_hist,tipo_hist,cod_usua, arquivo_anexo, path_arquivo_anexo, apto, motivo ) VALUES (
				".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",
				".(!$dt_hist ? "NULL" : "'".mysql_real_escape_string($dt_hist)."'").",
				".(!$obs_hist ? "NULL" : "'".mysql_real_escape_string($obs_hist)."'").",
				".(!$tipo_hist ? "NULL" : "'".mysql_real_escape_string($tipo_hist)."'").",
				".(!$cod_usua ? "NULL" : "'".mysql_real_escape_string($cod_usua)."'").",
				'$nome_arquivo',
				'$path_arquivo',
				'$apto',
				$motivo
			)
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class imovel extends database {

	function imovel() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_ppst,tipo_imov,flgaprovacao_imov,area_imov,tpconstrucao_imov,tpcondominio_imov,qtsala_imov,qtquarto_imov,qtbanh_imov,qtgarag_imov,qtpavim_imov,qtdepemp_imov,estconserv_imov,estconspred_imov,nomecartrgi_imov,nrmatrgi_imov,nrlivrgi_imov,nrfolhrgi_imov,nrrgcompvend_imov,nrrggar_imov,endereco_imov,nrendereco_imov,cpendereco_imov,cep_imov,tpimposto_imov,vlavaliacao_imov,vlavalsemgar_imov,vlavalgar_imov,dtavaliacao_imov,dtaprovacao_imov,cod_logr,cod_bairro,cod_uf,cod_municipio,tpmoradia_imov,terreo_imov,tmbdspcndop_imov,incomb_imov,ruralfav_imov,emconstr_imov,aquispaimae_imov,possuiirmaos_imov,andar_imov,pavimento_imov,tpapto_imov,flgbloco_imov,numbloco_imov,edificio_imov,conjunto_imov,areautil_imov,areatotal_imov,vagasapto_imov,isolado_imov,nomecondominio_imov,despachante_imov,flgcondominio_imov
			FROM imovel
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_ppst) {
		$this->query = "
			SELECT cod_ppst,tipo_imov,flgaprovacao_imov,area_imov,tpconstrucao_imov,tpcondominio_imov,qtsala_imov,qtquarto_imov,qtbanh_imov,qtgarag_imov,qtpavim_imov,qtdepemp_imov,estconserv_imov,estconspred_imov,nomecartrgi_imov,nrmatrgi_imov,nrlivrgi_imov,nrfolhrgi_imov,nrrgcompvend_imov,nrrggar_imov,endereco_imov,nrendereco_imov,cpendereco_imov,cep_imov,tpimposto_imov,vlavaliacao_imov,vlavalsemgar_imov,vlavalgar_imov,dtavaliacao_imov,dtaprovacao_imov,cod_logr,cod_bairro,cod_uf,cod_municipio,tpmoradia_imov,terreo_imov,tmbdspcndop_imov,incomb_imov,ruralfav_imov,emconstr_imov,aquispaimae_imov,possuiirmaos_imov,andar_imov,pavimento_imov,tpapto_imov,flgbloco_imov,numbloco_imov,edificio_imov,conjunto_imov,areautil_imov,areatotal_imov,vagasapto_imov,isolado_imov,nomecondominio_imov,despachante_imov,flgcondominio_imov
			FROM imovel
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		//echo $this->query;
		$this->query();
		return $this->qrdata;
	}
	function pesquisarPorDespachante($despachante) {
		$this->query = "
			SELECT cod_ppst,tipo_imov,flgaprovacao_imov,area_imov,tpconstrucao_imov,tpcondominio_imov,qtsala_imov,qtquarto_imov,qtbanh_imov,qtgarag_imov,qtpavim_imov,qtdepemp_imov,estconserv_imov,estconspred_imov,nomecartrgi_imov,nrmatrgi_imov,nrlivrgi_imov,nrfolhrgi_imov,nrrgcompvend_imov,nrrggar_imov,endereco_imov,nrendereco_imov,cpendereco_imov,cep_imov,tpimposto_imov,vlavaliacao_imov,vlavalsemgar_imov,vlavalgar_imov,dtavaliacao_imov,dtaprovacao_imov,cod_logr,cod_bairro,cod_uf,cod_municipio,tpmoradia_imov,terreo_imov,tmbdspcndop_imov,incomb_imov,ruralfav_imov,emconstr_imov,aquispaimae_imov,possuiirmaos_imov,andar_imov,pavimento_imov,tpapto_imov,flgbloco_imov,numbloco_imov,edificio_imov,conjunto_imov,areautil_imov,areatotal_imov,vagasapto_imov,isolado_imov,nomecondominio_imov,despachante_imov,flgcondominio_imov
			FROM imovel
			WHERE despachante_imov = '".mysql_real_escape_string($despachante)."'
		";
		$this->query();
		return $this->qrdata;
	}
	function atualizarPk($cod_ppst,$tipo_imov,$flgaprovacao_imov,$area_imov,$tpconstrucao_imov,$tpcondominio_imov,$qtsala_imov,$qtquarto_imov,$qtbanh_imov,$qtgarag_imov,$qtpavim_imov,$qtdepemp_imov,$estconserv_imov,$estconspred_imov,$nomecartrgi_imov,$nrmatrgi_imov,$nrlivrgi_imov,$nrfolhrgi_imov,$nrrgcompvend_imov,$nrrggar_imov,$endereco_imov,$nrendereco_imov,$cpendereco_imov,$cep_imov,$tpimposto_imov,$vlavaliacao_imov,$vlavalsemgar_imov,$vlavalgar_imov,$dtavaliacao_imov,$dtaprovacao_imov,$cod_logr,$cod_bairro,$cod_uf,$cod_municipio,$tpmoradia_imov,$terreo_imov,$tmbdspcndop_imov,$incomb_imov,$ruralfav_imov,$emconstr_imov,$aquispaimae_imov,$possuiirmaos_imov,$andar_imov,$pavimento_imov,$tpapto_imov,$flgbloco_imov,$numbloco_imov,$edificio_imov,$conjunto_imov,$areautil_imov,$areatotal_imov,$vagasapto_imov,$isolado_imov,$nomecondominio_imov,$despachante_imov,$flgcondominio_imov) {
		
		$this->query = "
			UPDATE imovel SET 
			cod_ppst = ".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",tipo_imov = ".(!$tipo_imov ? "NULL" : "'".mysql_real_escape_string($tipo_imov)."'").",flgaprovacao_imov = ".(!$flgaprovacao_imov ? "NULL" : "'".mysql_real_escape_string($flgaprovacao_imov)."'").",area_imov = ".(!$area_imov ? "NULL" : "'".mysql_real_escape_string($area_imov)."'").",tpconstrucao_imov = ".(!$tpconstrucao_imov ? "NULL" : "'".mysql_real_escape_string($tpconstrucao_imov)."'").",tpcondominio_imov = ".(!$tpcondominio_imov ? "NULL" : "'".mysql_real_escape_string($tpcondominio_imov)."'").",qtsala_imov = ".(!$qtsala_imov ? "NULL" : "'".mysql_real_escape_string($qtsala_imov)."'").",qtquarto_imov = ".(!$qtquarto_imov ? "NULL" : "'".mysql_real_escape_string($qtquarto_imov)."'").",qtbanh_imov = ".(!$qtbanh_imov ? "NULL" : "'".mysql_real_escape_string($qtbanh_imov)."'").",qtgarag_imov = ".(!$qtgarag_imov ? "NULL" : "'".mysql_real_escape_string($qtgarag_imov)."'").",qtpavim_imov = ".(!$qtpavim_imov ? "NULL" : "'".mysql_real_escape_string($qtpavim_imov)."'").",qtdepemp_imov = ".(!$qtdepemp_imov ? "NULL" : "'".mysql_real_escape_string($qtdepemp_imov)."'").",estconserv_imov = ".(!$estconserv_imov ? "NULL" : "'".mysql_real_escape_string($estconserv_imov)."'").",estconspred_imov = ".(!$estconspred_imov ? "NULL" : "'".mysql_real_escape_string($estconspred_imov)."'").",nomecartrgi_imov = ".(!$nomecartrgi_imov ? "NULL" : "'".mysql_real_escape_string($nomecartrgi_imov)."'").",nrmatrgi_imov = ".(!$nrmatrgi_imov ? "NULL" : "'".mysql_real_escape_string($nrmatrgi_imov)."'").",nrlivrgi_imov = ".(!$nrlivrgi_imov ? "NULL" : "'".mysql_real_escape_string($nrlivrgi_imov)."'").",nrfolhrgi_imov = ".(!$nrfolhrgi_imov ? "NULL" : "'".mysql_real_escape_string($nrfolhrgi_imov)."'").",nrrgcompvend_imov = ".(!$nrrgcompvend_imov ? "NULL" : "'".mysql_real_escape_string($nrrgcompvend_imov)."'").",nrrggar_imov = ".(!$nrrggar_imov ? "NULL" : "'".mysql_real_escape_string($nrrggar_imov)."'").",endereco_imov = ".(!$endereco_imov ? "NULL" : "'".mysql_real_escape_string($endereco_imov)."'").",nrendereco_imov = ".(!$nrendereco_imov ? "0" : "'".mysql_real_escape_string($nrendereco_imov)."'").",cpendereco_imov = ".(!$cpendereco_imov ? "NULL" : "'".mysql_real_escape_string($cpendereco_imov)."'").",cep_imov = ".(!$cep_imov ? "NULL" : "'".mysql_real_escape_string($cep_imov)."'").",tpimposto_imov = ".(!$tpimposto_imov ? "NULL" : "'".mysql_real_escape_string($tpimposto_imov)."'").",vlavaliacao_imov = ".(!$vlavaliacao_imov ? "NULL" : "'".mysql_real_escape_string($vlavaliacao_imov)."'").",vlavalsemgar_imov = ".(!$vlavalsemgar_imov ? "NULL" : "'".mysql_real_escape_string($vlavalsemgar_imov)."'").",vlavalgar_imov = ".(!$vlavalgar_imov ? "NULL" : "'".mysql_real_escape_string($vlavalgar_imov)."'").",dtavaliacao_imov = ".(!$dtavaliacao_imov ? "NULL" : "'".mysql_real_escape_string($dtavaliacao_imov)."'").",dtaprovacao_imov = ".(!$dtaprovacao_imov ? "NULL" : "'".mysql_real_escape_string($dtaprovacao_imov)."'").",cod_logr = ".(!$cod_logr ? "NULL" : "'".mysql_real_escape_string($cod_logr)."'").",cod_bairro = ".(!$cod_bairro ? "NULL" : "'".mysql_real_escape_string($cod_bairro)."'").",cod_uf = ".(!$cod_uf ? "NULL" : "'".mysql_real_escape_string($cod_uf)."'").",cod_municipio = ".(!$cod_municipio ? "NULL" : "'".mysql_real_escape_string($cod_municipio)."'").",tpmoradia_imov = ".(!$tpmoradia_imov ? "NULL" : "'".mysql_real_escape_string($tpmoradia_imov)."'").",terreo_imov = ".(!$terreo_imov ? "NULL" : "'".mysql_real_escape_string($terreo_imov)."'").",tmbdspcndop_imov = ".(!$tmbdspcndop_imov ? "NULL" : "'".mysql_real_escape_string($tmbdspcndop_imov)."'").",incomb_imov = ".(!$incomb_imov ? "NULL" : "'".mysql_real_escape_string($incomb_imov)."'").",ruralfav_imov = ".(!$ruralfav_imov ? "NULL" : "'".mysql_real_escape_string($ruralfav_imov)."'").",emconstr_imov = ".(!$emconstr_imov ? "NULL" : "'".mysql_real_escape_string($emconstr_imov)."'").",aquispaimae_imov = ".(!$aquispaimae_imov ? "NULL" : "'".mysql_real_escape_string($aquispaimae_imov)."'").",possuiirmaos_imov = ".(!$possuiirmaos_imov ? "NULL" : "'".mysql_real_escape_string($possuiirmaos_imov)."'").",andar_imov = ".(!$andar_imov ? "NULL" : "'".mysql_real_escape_string($andar_imov)."'").",pavimento_imov = ".(!$pavimento_imov ? "NULL" : "'".mysql_real_escape_string($pavimento_imov)."'").",tpapto_imov = ".(!$tpapto_imov ? "NULL" : "'".mysql_real_escape_string($tpapto_imov)."'").",flgbloco_imov = ".(!$flgbloco_imov ? "NULL" : "'".mysql_real_escape_string($flgbloco_imov)."'").",numbloco_imov = ".(!$numbloco_imov ? "NULL" : "'".mysql_real_escape_string($numbloco_imov)."'").",edificio_imov = ".(!$edificio_imov ? "NULL" : "'".mysql_real_escape_string($edificio_imov)."'").",conjunto_imov = ".(!$conjunto_imov ? "NULL" : "'".mysql_real_escape_string($conjunto_imov)."'").",areautil_imov = ".(!$areautil_imov ? "NULL" : "'".mysql_real_escape_string($areautil_imov)."'").",areatotal_imov = ".(!$areatotal_imov ? "NULL" : "'".mysql_real_escape_string($areatotal_imov)."'").",vagasapto_imov = ".(!$vagasapto_imov ? "NULL" : "'".mysql_real_escape_string($vagasapto_imov)."'").",isolado_imov = ".(!$isolado_imov ? "NULL" : "'".mysql_real_escape_string($isolado_imov)."'").",nomecondominio_imov = ".(!$nomecondominio_imov ? "NULL" : "'".mysql_real_escape_string($nomecondominio_imov)."'").",despachante_imov = ".(!$despachante_imov ? "NULL" : "'".mysql_real_escape_string($despachante_imov)."'").",flgcondominio_imov = ".(!$flgcondominio_imov ? "NULL" : "'".mysql_real_escape_string($flgcondominio_imov)."'")."
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";

		//echo $this->query;
		
		
		$this->query();
		return $this->qrdata;
	}
	
	function atualizarRegistro($cod_ppst,$nomecartrgi_imov,$nrmatrgi_imov,$nrlivrgi_imov,$nrfolhrgi_imov,$nrrgcompvend_imov,$nrrggar_imov,$dtokregistro_ppst) {
		$this->query = "
			UPDATE imovel SET 
			nomecartrgi_imov = '".mysql_real_escape_string($nomecartrgi_imov)."',
			nrmatrgi_imov = '".mysql_real_escape_string($nrmatrgi_imov)."',
			nrlivrgi_imov = '".mysql_real_escape_string($nrlivrgi_imov)."',
			nrfolhrgi_imov = '".mysql_real_escape_string($nrfolhrgi_imov)."',
			nrrgcompvend_imov = '".mysql_real_escape_string($nrrgcompvend_imov)."',
			nrrggar_imov = '".mysql_real_escape_string($nrrggar_imov)."'
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		$this->query();
		$this->query = "
			UPDATE proposta SET dtokregistro_ppst = '".utils::data2db($dtokregistro_ppst)."', situacao_ppst = '10', indcancelamento_ppst=NULL
			WHERE cod_ppst = '".$cod_ppst."'
		";
		$this->query();
	}

	function deletarPk($cod_ppst) {
		$this->query = "
			DELETE FROM imovel 
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_ppst,$tipo_imov,$flgaprovacao_imov,$area_imov,$tpconstrucao_imov,$tpcondominio_imov,$qtsala_imov,$qtquarto_imov,$qtbanh_imov,$qtgarag_imov,$qtpavim_imov,$qtdepemp_imov,$estconserv_imov,$estconspred_imov,$nomecartrgi_imov,$nrmatrgi_imov,$nrlivrgi_imov,$nrfolhrgi_imov,$nrrgcompvend_imov,$nrrggar_imov,$endereco_imov,$nrendereco_imov,$cpendereco_imov,$cep_imov,$tpimposto_imov,$vlavaliacao_imov,$vlavalsemgar_imov,$vlavalgar_imov,$dtavaliacao_imov,$dtaprovacao_imov,$cod_logr,$cod_bairro,$cod_uf,$cod_municipio,$tpmoradia_imov,$terreo_imov,$tmbdspcndop_imov,$incomb_imov,$ruralfav_imov,$emconstr_imov,$aquispaimae_imov,$possuiirmaos_imov,$andar_imov,$pavimento_imov,$tpapto_imov,$flgbloco_imov,$numbloco_imov,$edificio_imov,$conjunto_imov,$areautil_imov,$areatotal_imov,$vagasapto_imov,$isolado_imov,$nomecondominio_imov,$despachante_imov,$flgcondominio_imov) {
		$this->query = "
			INSERT INTO imovel ( cod_ppst,tipo_imov,flgaprovacao_imov,area_imov,tpconstrucao_imov,tpcondominio_imov,qtsala_imov,qtquarto_imov,qtbanh_imov,qtgarag_imov,qtpavim_imov,qtdepemp_imov,estconserv_imov,estconspred_imov,nomecartrgi_imov,nrmatrgi_imov,nrlivrgi_imov,nrfolhrgi_imov,nrrgcompvend_imov,nrrggar_imov,endereco_imov,nrendereco_imov,cpendereco_imov,cep_imov,tpimposto_imov,vlavaliacao_imov,vlavalsemgar_imov,vlavalgar_imov,dtavaliacao_imov,dtaprovacao_imov,cod_logr,cod_bairro,cod_uf,cod_municipio,tpmoradia_imov,terreo_imov,tmbdspcndop_imov,incomb_imov,ruralfav_imov,emconstr_imov,aquispaimae_imov,possuiirmaos_imov,andar_imov,pavimento_imov,tpapto_imov,flgbloco_imov,numbloco_imov,edificio_imov,conjunto_imov,areautil_imov,areatotal_imov,vagasapto_imov,isolado_imov,nomecondominio_imov,despachante_imov,flgcondominio_imov ) VALUES (
				".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",".(!$tipo_imov ? "NULL" : "'".mysql_real_escape_string($tipo_imov)."'").",".(!$flgaprovacao_imov ? "NULL" : "'".mysql_real_escape_string($flgaprovacao_imov)."'").",".(!$area_imov ? "NULL" : "'".mysql_real_escape_string($area_imov)."'").",".(!$tpconstrucao_imov ? "NULL" : "'".mysql_real_escape_string($tpconstrucao_imov)."'").",".(!$tpcondominio_imov ? "NULL" : "'".mysql_real_escape_string($tpcondominio_imov)."'").",".(!$qtsala_imov ? "NULL" : "'".mysql_real_escape_string($qtsala_imov)."'").",".(!$qtquarto_imov ? "NULL" : "'".mysql_real_escape_string($qtquarto_imov)."'").",".(!$qtbanh_imov ? "NULL" : "'".mysql_real_escape_string($qtbanh_imov)."'").",".(!$qtgarag_imov ? "NULL" : "'".mysql_real_escape_string($qtgarag_imov)."'").",".(!$qtpavim_imov ? "NULL" : "'".mysql_real_escape_string($qtpavim_imov)."'").",".(!$qtdepemp_imov ? "NULL" : "'".mysql_real_escape_string($qtdepemp_imov)."'").",".(!$estconserv_imov ? "NULL" : "'".mysql_real_escape_string($estconserv_imov)."'").",".(!$estconspred_imov ? "NULL" : "'".mysql_real_escape_string($estconspred_imov)."'").",".(!$nomecartrgi_imov ? "NULL" : "'".mysql_real_escape_string($nomecartrgi_imov)."'").",".(!$nrmatrgi_imov ? "NULL" : "'".mysql_real_escape_string($nrmatrgi_imov)."'").",".(!$nrlivrgi_imov ? "NULL" : "'".mysql_real_escape_string($nrlivrgi_imov)."'").",".(!$nrfolhrgi_imov ? "NULL" : "'".mysql_real_escape_string($nrfolhrgi_imov)."'").",".(!$nrrgcompvend_imov ? "NULL" : "'".mysql_real_escape_string($nrrgcompvend_imov)."'").",".(!$nrrggar_imov ? "NULL" : "'".mysql_real_escape_string($nrrggar_imov)."'").",".(!$endereco_imov ? "NULL" : "'".mysql_real_escape_string($endereco_imov)."'").",".(!$nrendereco_imov ? "0" : "'".mysql_real_escape_string($nrendereco_imov)."'").",".(!$cpendereco_imov ? "NULL" : "'".mysql_real_escape_string($cpendereco_imov)."'").",".(!$cep_imov ? "NULL" : "'".mysql_real_escape_string($cep_imov)."'").",".(!$tpimposto_imov ? "NULL" : "'".mysql_real_escape_string($tpimposto_imov)."'").",".(!$vlavaliacao_imov ? "NULL" : "'".mysql_real_escape_string($vlavaliacao_imov)."'").",".(!$vlavalsemgar_imov ? "NULL" : "'".mysql_real_escape_string($vlavalsemgar_imov)."'").",".(!$vlavalgar_imov ? "NULL" : "'".mysql_real_escape_string($vlavalgar_imov)."'").",".(!$dtavaliacao_imov ? "NULL" : "'".mysql_real_escape_string($dtavaliacao_imov)."'").",".(!$dtaprovacao_imov ? "NULL" : "'".mysql_real_escape_string($dtaprovacao_imov)."'").",".(!$cod_logr ? "NULL" : "'".mysql_real_escape_string($cod_logr)."'").",".(!$cod_bairro ? "NULL" : "'".mysql_real_escape_string($cod_bairro)."'").",".(!$cod_uf ? "NULL" : "'".mysql_real_escape_string($cod_uf)."'").",".(!$cod_municipio ? "NULL" : "'".mysql_real_escape_string($cod_municipio)."'").",".(!$tpmoradia_imov ? "NULL" : "'".mysql_real_escape_string($tpmoradia_imov)."'").",".(!$terreo_imov ? "NULL" : "'".mysql_real_escape_string($terreo_imov)."'").",".(!$tmbdspcndop_imov ? "NULL" : "'".mysql_real_escape_string($tmbdspcndop_imov)."'").",".(!$incomb_imov ? "NULL" : "'".mysql_real_escape_string($incomb_imov)."'").",".(!$ruralfav_imov ? "NULL" : "'".mysql_real_escape_string($ruralfav_imov)."'").",".(!$emconstr_imov ? "NULL" : "'".mysql_real_escape_string($emconstr_imov)."'").",".(!$aquispaimae_imov ? "NULL" : "'".mysql_real_escape_string($aquispaimae_imov)."'").",".(!$possuiirmaos_imov ? "NULL" : "'".mysql_real_escape_string($possuiirmaos_imov)."'").",".(!$andar_imov ? "NULL" : "'".mysql_real_escape_string($andar_imov)."'").",".(!$pavimento_imov ? "NULL" : "'".mysql_real_escape_string($pavimento_imov)."'").",".(!$tpapto_imov ? "NULL" : "'".mysql_real_escape_string($tpapto_imov)."'").",".(!$flgbloco_imov ? "NULL" : "'".mysql_real_escape_string($flgbloco_imov)."'").",".(!$numbloco_imov ? "NULL" : "'".mysql_real_escape_string($numbloco_imov)."'").",".(!$edificio_imov ? "NULL" : "'".mysql_real_escape_string($edificio_imov)."'").",".(!$conjunto_imov ? "NULL" : "'".mysql_real_escape_string($conjunto_imov)."'").",".(!$areautil_imov ? "NULL" : "'".mysql_real_escape_string($areautil_imov)."'").",".(!$areatotal_imov ? "NULL" : "'".mysql_real_escape_string($areatotal_imov)."'").",".(!$vagasapto_imov ? "NULL" : "'".mysql_real_escape_string($vagasapto_imov)."'").",".(!$isolado_imov ? "NULL" : "'".mysql_real_escape_string($isolado_imov)."'").",".(!$nomecondominio_imov ? "NULL" : "'".mysql_real_escape_string($nomecondominio_imov)."'").",".(!$despachante_imov ? "NULL" : "'".mysql_real_escape_string($despachante_imov)."'").",".(!$flgcondominio_imov ? "NULL" : "'".mysql_real_escape_string($flgcondominio_imov)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class imovelvaga extends database {

	function imovelvaga() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_ppst,tpvaga_imvg,local_imvg,areautil_imvg,areacomum_imvg,areatotal_imvg,fracaoideal_imvg,nrcontribuinte_imvg,nrregistro_imvg,nrmatricula_imvg,nroficioregistro_imvg,localoficio_imvg,cod_imvg
			FROM imovelvaga
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_imvg) {
		$this->query = "
			SELECT cod_ppst,tpvaga_imvg,local_imvg,areautil_imvg,areacomum_imvg,areatotal_imvg,fracaoideal_imvg,nrcontribuinte_imvg,nrregistro_imvg,nrmatricula_imvg,nroficioregistro_imvg,localoficio_imvg,cod_imvg
			FROM imovelvaga
			WHERE cod_imvg = '".mysql_real_escape_string($cod_imvg)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPorProposta($cod_ppst) {
		$this->query = "
			SELECT cod_ppst,tpvaga_imvg,local_imvg,areautil_imvg,areacomum_imvg,areatotal_imvg,fracaoideal_imvg,nrcontribuinte_imvg,nrregistro_imvg,nrmatricula_imvg,nroficioregistro_imvg,localoficio_imvg,cod_imvg
			FROM imovelvaga
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_ppst,$tpvaga_imvg,$local_imvg,$areautil_imvg,$areacomum_imvg,$areatotal_imvg,$fracaoideal_imvg,$nrcontribuinte_imvg,$nrregistro_imvg,$nrmatricula_imvg,$nroficioregistro_imvg,$localoficio_imvg,$cod_imvg) {
		$this->query = "
			UPDATE imovelvaga SET 
			cod_ppst = ".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",tpvaga_imvg = ".(!$tpvaga_imvg ? "NULL" : "'".mysql_real_escape_string($tpvaga_imvg)."'").",local_imvg = ".(!$local_imvg ? "NULL" : "'".mysql_real_escape_string($local_imvg)."'").",areautil_imvg = ".(!$areautil_imvg ? "NULL" : "'".mysql_real_escape_string($areautil_imvg)."'").",areacomum_imvg = ".(!$areacomum_imvg ? "NULL" : "'".mysql_real_escape_string($areacomum_imvg)."'").",areatotal_imvg = ".(!$areatotal_imvg ? "NULL" : "'".mysql_real_escape_string($areatotal_imvg)."'").",fracaoideal_imvg = ".(!$fracaoideal_imvg ? "NULL" : "'".mysql_real_escape_string($fracaoideal_imvg)."'").",nrcontribuinte_imvg = ".(!$nrcontribuinte_imvg ? "NULL" : "'".mysql_real_escape_string($nrcontribuinte_imvg)."'").",nrregistro_imvg = ".(!$nrregistro_imvg ? "NULL" : "'".mysql_real_escape_string($nrregistro_imvg)."'").",nrmatricula_imvg = ".(!$nrmatricula_imvg ? "NULL" : "'".mysql_real_escape_string($nrmatricula_imvg)."'").",nroficioregistro_imvg = ".(!$nroficioregistro_imvg ? "NULL" : "'".mysql_real_escape_string($nroficioregistro_imvg)."'").",localoficio_imvg = ".(!$localoficio_imvg ? "NULL" : "'".mysql_real_escape_string($localoficio_imvg)."'")."
			WHERE cod_imvg = '".mysql_real_escape_string($cod_imvg)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_imvg) {
		$this->query = "
			DELETE FROM imovelvaga 
			WHERE cod_imvg = '".mysql_real_escape_string($cod_imvg)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPorProposta($cod_ppst) {
		$this->query = "
			DELETE FROM imovelvaga 
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_ppst,$tpvaga_imvg,$local_imvg,$areautil_imvg,$areacomum_imvg,$areatotal_imvg,$fracaoideal_imvg,$nrcontribuinte_imvg,$nrregistro_imvg,$nrmatricula_imvg,$nroficioregistro_imvg,$localoficio_imvg) {
		$this->query = "
			INSERT INTO imovelvaga ( cod_ppst,tpvaga_imvg,local_imvg,areautil_imvg,areacomum_imvg,areatotal_imvg,fracaoideal_imvg,nrcontribuinte_imvg,nrregistro_imvg,nrmatricula_imvg,nroficioregistro_imvg,localoficio_imvg ) VALUES (
				".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",".(!$tpvaga_imvg ? "NULL" : "'".mysql_real_escape_string($tpvaga_imvg)."'").",".(!$local_imvg ? "NULL" : "'".mysql_real_escape_string($local_imvg)."'").",".(!$areautil_imvg ? "NULL" : "'".mysql_real_escape_string($areautil_imvg)."'").",".(!$areacomum_imvg ? "NULL" : "'".mysql_real_escape_string($areacomum_imvg)."'").",".(!$areatotal_imvg ? "NULL" : "'".mysql_real_escape_string($areatotal_imvg)."'").",".(!$fracaoideal_imvg ? "NULL" : "'".mysql_real_escape_string($fracaoideal_imvg)."'").",".(!$nrcontribuinte_imvg ? "NULL" : "'".mysql_real_escape_string($nrcontribuinte_imvg)."'").",".(!$nrregistro_imvg ? "NULL" : "'".mysql_real_escape_string($nrregistro_imvg)."'").",".(!$nrmatricula_imvg ? "NULL" : "'".mysql_real_escape_string($nrmatricula_imvg)."'").",".(!$nroficioregistro_imvg ? "NULL" : "'".mysql_real_escape_string($nroficioregistro_imvg)."'").",".(!$localoficio_imvg ? "NULL" : "'".mysql_real_escape_string($localoficio_imvg)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class informativo extends database {

	function informativo() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_info,titulo_info,descr_info,cod_ctgr,flgativo_info
			FROM informativo
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_info) {
		$this->query = "
			SELECT cod_info,titulo_info,descr_info,cod_ctgr,flgativo_info
			FROM informativo
			WHERE cod_info = '".mysql_real_escape_string($cod_info)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_info,$titulo_info,$descr_info,$cod_ctgr,$flgativo_info) {
		$this->query = "
			UPDATE informativo SET 
			titulo_info = ".(!$titulo_info ? "NULL" : "'".mysql_real_escape_string($titulo_info)."'").",descr_info = ".(!$descr_info ? "NULL" : "'".mysql_real_escape_string($descr_info)."'").",cod_ctgr = ".(!$cod_ctgr ? "NULL" : "'".mysql_real_escape_string($cod_ctgr)."'").",flgativo_info = ".(!$flgativo_info ? "NULL" : "'".mysql_real_escape_string($flgativo_info)."'")."
			WHERE cod_info = '".mysql_real_escape_string($cod_info)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_info) {
		$this->query = "
			DELETE FROM informativo 
			WHERE cod_info = '".mysql_real_escape_string($cod_info)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($titulo_info,$descr_info,$cod_ctgr,$flgativo_info) {
		$this->query = "
			INSERT INTO informativo ( titulo_info,descr_info,cod_ctgr,flgativo_info ) VALUES (
				".(!$titulo_info ? "NULL" : "'".mysql_real_escape_string($titulo_info)."'").",".(!$descr_info ? "NULL" : "'".mysql_real_escape_string($descr_info)."'").",".(!$cod_ctgr ? "NULL" : "'".mysql_real_escape_string($cod_ctgr)."'").",".(!$flgativo_info ? "NULL" : "'".mysql_real_escape_string($flgativo_info)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class infotemplate extends database {

	function infotemplate() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_ctgr,cod_info,cod_tmpl,ordem_intp
			FROM infotemplate
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_ctgr,$cod_info,$cod_tmpl) {
		$this->query = "
			SELECT cod_ctgr,cod_info,cod_tmpl,ordem_intp
			FROM infotemplate
			WHERE cod_ctgr = '".mysql_real_escape_string($cod_ctgr)."' and cod_info = '".mysql_real_escape_string($cod_info)."' and cod_tmpl = '".mysql_real_escape_string($cod_tmpl)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_ctgr,$cod_info,$cod_tmpl,$ordem_intp) {
		$this->query = "
			UPDATE infotemplate SET 
			cod_ctgr = ".(!$cod_ctgr ? "NULL" : "'".mysql_real_escape_string($cod_ctgr)."'").",cod_info = ".(!$cod_info ? "NULL" : "'".mysql_real_escape_string($cod_info)."'").",cod_tmpl = ".(!$cod_tmpl ? "NULL" : "'".mysql_real_escape_string($cod_tmpl)."'").",ordem_intp = ".(!$ordem_intp ? "NULL" : "'".mysql_real_escape_string($ordem_intp)."'")."
			WHERE cod_ctgr = '".mysql_real_escape_string($cod_ctgr)."' and cod_info = '".mysql_real_escape_string($cod_info)."' and cod_tmpl = '".mysql_real_escape_string($cod_tmpl)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_ctgr,$cod_info,$cod_tmpl) {
		$this->query = "
			DELETE FROM infotemplate 
			WHERE cod_ctgr = '".mysql_real_escape_string($cod_ctgr)."' and cod_info = '".mysql_real_escape_string($cod_info)."' and cod_tmpl = '".mysql_real_escape_string($cod_tmpl)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_ctgr,$cod_info,$cod_tmpl,$ordem_intp) {
		$this->query = "
			INSERT INTO infotemplate ( cod_ctgr,cod_info,cod_tmpl,ordem_intp ) VALUES (
				".(!$cod_ctgr ? "NULL" : "'".mysql_real_escape_string($cod_ctgr)."'").",".(!$cod_info ? "NULL" : "'".mysql_real_escape_string($cod_info)."'").",".(!$cod_tmpl ? "NULL" : "'".mysql_real_escape_string($cod_tmpl)."'").",".(!$ordem_intp ? "NULL" : "'".mysql_real_escape_string($ordem_intp)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class listadenomes extends database {

	function listadenomes() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT id_lstn,vlmaxfinan,parcmaxfinan,przmaxfinan,vlaprovado,parcaprovada,przaprovado,vlentraprovado,status_pp
			FROM listadenomes
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($id_lstn) {
		$this->query = "
			SELECT id_lstn,vlmaxfinan,parcmaxfinan,przmaxfinan,vlaprovado,parcaprovada,przaprovado,vlentraprovado,status_pp
			FROM listadenomes
			WHERE id_lstn = '".mysql_real_escape_string($id_lstn)."'
		";
	//	echo $this->query;
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($id_lstn,$vlmaxfinan,$parcmaxfinan,$przmaxfinan,$vlaprovado,$parcaprovada,$przaprovado,$vlentraprovado,$status) {
		$this->query = "
			UPDATE listadenomes SET 
			id_lstn = ".(!$id_lstn ? "NULL" : "'".mysql_real_escape_string($id_lstn)."'").",vlmaxfinan = ".(!$vlmaxfinan ? "NULL" : "'".mysql_real_escape_string($vlmaxfinan)."'").",parcmaxfinan = ".(!$parcmaxfinan ? "NULL" : "'".mysql_real_escape_string($parcmaxfinan)."'").",przmaxfinan = ".(!$przmaxfinan ? "NULL" : "'".mysql_real_escape_string($przmaxfinan)."'").",vlaprovado = ".(!$vlaprovado ? "NULL" : "'".mysql_real_escape_string($vlaprovado)."'").",parcaprovada = ".(!$parcaprovada ? "NULL" : "'".mysql_real_escape_string($parcaprovada)."'").",przaprovado = ".(!$przaprovado ? "NULL" : "'".mysql_real_escape_string($przaprovado)."'").",vlentraprovado = ".(!$vlentraprovado ? "NULL" : "'".mysql_real_escape_string($vlentraprovado)."'").",status_pp = ".(!$status ? "NULL" : "'".mysql_real_escape_string($status)."'")."
			WHERE id_lstn = '".mysql_real_escape_string($id_lstn)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($id_lstn) {
		$this->query = "
			DELETE FROM listadenomes 
			WHERE id_lstn = '".mysql_real_escape_string($id_lstn)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($id_lstn,$vlmaxfinan,$parcmaxfinan,$przmaxfinan,$vlaprovado,$parcaprovada,$przaprovado,$vlentraprovado,$status) {
		$this->query = "
			INSERT INTO listadenomes ( id_lstn,vlmaxfinan,parcmaxfinan,przmaxfinan,vlaprovado,parcaprovada,przaprovado,vlentraprovado,status_pp ) VALUES (
				".(!$id_lstn ? "NULL" : "'".mysql_real_escape_string($id_lstn)."'").",".(!$vlmaxfinan ? "NULL" : "'".mysql_real_escape_string($vlmaxfinan)."'").",".(!$parcmaxfinan ? "NULL" : "'".mysql_real_escape_string($parcmaxfinan)."'").",".(!$przmaxfinan ? "NULL" : "'".mysql_real_escape_string($przmaxfinan)."'").",".(!$vlaprovado ? "NULL" : "'".mysql_real_escape_string($vlaprovado)."'").",".(!$parcaprovada ? "NULL" : "'".mysql_real_escape_string($parcaprovada)."'").",".(!$przaprovado ? "NULL" : "'".mysql_real_escape_string($przaprovado)."'").",".(!$vlentraprovado ? "NULL" : "'".mysql_real_escape_string($vlentraprovado)."'").",".(!$status ? "NULL" : "'".mysql_real_escape_string($status)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class log extends database {

	function log() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT id_log,dt_log,transacao_log,operacao_log,usuario_log,observacao_log
			FROM log
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($id_log) {
		$this->query = "
			SELECT id_log,dt_log,transacao_log,operacao_log,usuario_log,observacao_log
			FROM log
			WHERE id_log = '".mysql_real_escape_string($id_log)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($id_log,$dt_log,$transacao_log,$operacao_log,$usuario_log,$observacao_log) {
		$this->query = "
			UPDATE log SET 
			dt_log = ".(!$dt_log ? "NULL" : "'".mysql_real_escape_string($dt_log)."'").",transacao_log = ".(!$transacao_log ? "NULL" : "'".mysql_real_escape_string($transacao_log)."'").",operacao_log = ".(!$operacao_log ? "NULL" : "'".mysql_real_escape_string($operacao_log)."'").",usuario_log = ".(!$usuario_log ? "NULL" : "'".mysql_real_escape_string($usuario_log)."'").",observacao_log = ".(!$observacao_log ? "NULL" : "'".mysql_real_escape_string($observacao_log)."'")."
			WHERE id_log = '".mysql_real_escape_string($id_log)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($id_log) {
		$this->query = "
			DELETE FROM log 
			WHERE id_log = '".mysql_real_escape_string($id_log)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($dt_log,$transacao_log,$operacao_log,$usuario_log,$observacao_log) {
		$this->query = "
			INSERT INTO log ( dt_log,transacao_log,operacao_log,usuario_log,observacao_log ) VALUES (
				".(!$dt_log ? "NULL" : "'".mysql_real_escape_string($dt_log)."'").",".(!$transacao_log ? "NULL" : "'".mysql_real_escape_string($transacao_log)."'").",".(!$operacao_log ? "NULL" : "'".mysql_real_escape_string($operacao_log)."'").",".(!$usuario_log ? "NULL" : "'".mysql_real_escape_string($usuario_log)."'").",".(!$observacao_log ? "NULL" : "'".mysql_real_escape_string($observacao_log)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class logradouro extends database {

	function logradouro() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_logr,desc_logr
			FROM logradouro
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_logr) {
		$this->query = "
			SELECT cod_logr,desc_logr
			FROM logradouro
			WHERE cod_logr = '".mysql_real_escape_string($cod_logr)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_logr,$desc_logr) {
		$this->query = "
			UPDATE logradouro SET 
			cod_logr = ".(!$cod_logr ? "NULL" : "'".mysql_real_escape_string($cod_logr)."'").",desc_logr = ".(!$desc_logr ? "NULL" : "'".mysql_real_escape_string($desc_logr)."'")."
			WHERE cod_logr = '".mysql_real_escape_string($cod_logr)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_logr) {
		$this->query = "
			DELETE FROM logradouro 
			WHERE cod_logr = '".mysql_real_escape_string($cod_logr)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_logr,$desc_logr) {
		$this->query = "
			INSERT INTO logradouro ( cod_logr,desc_logr ) VALUES (
				".(!$cod_logr ? "NULL" : "'".mysql_real_escape_string($cod_logr)."'").",".(!$desc_logr ? "NULL" : "'".mysql_real_escape_string($desc_logr)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

	function getListaLogradouro() {
		$this->query = "
			SELECT
				cod_logr,
				desc_logr
			FROM
				logradouro 
			ORDER BY 
				desc_logr
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class minutacontrato extends database {

	function minutacontrato() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_minu,nome_minu,texto_minu
			FROM minutacontrato
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_minu) {
		$this->query = "
			SELECT cod_minu,nome_minu,texto_minu
			FROM minutacontrato
			WHERE cod_minu = '".mysql_real_escape_string($cod_minu)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_minu,$nome_minu,$texto_minu) {
		$this->query = "
			UPDATE minutacontrato SET 
			nome_minu = ".(!$nome_minu ? "NULL" : "'".mysql_real_escape_string($nome_minu)."'").",texto_minu = ".(!$texto_minu ? "NULL" : "'".mysql_real_escape_string($texto_minu)."'")."
			WHERE cod_minu = '".mysql_real_escape_string($cod_minu)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_minu) {
		$this->query = "
			DELETE FROM minutacontrato 
			WHERE cod_minu = '".mysql_real_escape_string($cod_minu)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($nome_minu,$texto_minu) {
		$this->query = "
			INSERT INTO minutacontrato ( nome_minu,texto_minu ) VALUES (
				".(!$nome_minu ? "NULL" : "'".mysql_real_escape_string($nome_minu)."'").",".(!$texto_minu ? "NULL" : "'".mysql_real_escape_string($texto_minu)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class municipio extends database {

	function municipio() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_uf,cod_municipio,nome_municipio,obschecklist_municipio
			FROM municipio
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_uf,$cod_municipio) {
		$this->query = "
			SELECT cod_uf,cod_municipio,nome_municipio,obschecklist_municipio
			FROM municipio
			WHERE cod_uf = '".mysql_real_escape_string($cod_uf)."' and cod_municipio = '".mysql_real_escape_string($cod_municipio)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPorMunicipio($cod_municipio) {
		$this->query = "
			SELECT cod_uf,cod_municipio,nome_municipio,obschecklist_municipio
			FROM municipio
			WHERE cod_municipio = '".mysql_real_escape_string($cod_municipio)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_uf,$cod_municipio,$nome_municipio,$obschecklist_municipio) {
		$this->query = "
			UPDATE municipio SET 
			cod_uf = ".(!$cod_uf ? "NULL" : "'".mysql_real_escape_string($cod_uf)."'").",cod_municipio = ".(!$cod_municipio ? "NULL" : "'".mysql_real_escape_string($cod_municipio)."'").",nome_municipio = ".(!$nome_municipio ? "NULL" : "'".mysql_real_escape_string($nome_municipio)."'").",obschecklist_municipio = ".(!$obschecklist_municipio ? "NULL" : "'".mysql_real_escape_string($obschecklist_municipio)."'")."
			WHERE cod_uf = '".mysql_real_escape_string($cod_uf)."' and cod_municipio = '".mysql_real_escape_string($cod_municipio)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_uf,$cod_municipio) {
		$this->query = "
			DELETE FROM municipio 
			WHERE cod_uf = '".mysql_real_escape_string($cod_uf)."' and cod_municipio = '".mysql_real_escape_string($cod_municipio)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_uf,$cod_municipio,$nome_municipio,$obschecklist_municipio) {
		$this->query = "
			INSERT INTO municipio ( cod_uf,cod_municipio,nome_municipio,obschecklist_municipio ) VALUES (
				".(!$cod_uf ? "NULL" : "'".mysql_real_escape_string($cod_uf)."'").",".(!$cod_municipio ? "NULL" : "'".mysql_real_escape_string($cod_municipio)."'").",".(!$nome_municipio ? "NULL" : "'".mysql_real_escape_string($nome_municipio)."'").",".(!$obschecklist_municipio ? "NULL" : "'".mysql_real_escape_string($obschecklist_municipio)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}
	
	function getListaUf($cod_uf=false) {
		$sqlComplem = ($cod_uf)?" WHERE cod_uf='".mysql_real_escape_string($cod_uf)."' ":"";
		$this->query = "
			SELECT
				cod_uf,
				nome_uf
			FROM
				uf
			$sqlComplem
			ORDER BY
				nome_uf
		";
		$this->query();
		return $this->qrdata;
	}

	function getListaMunicipio($cod_uf=false,$cod_mun=false) {
		$sqlComplem = ($cod_mun)?" AND cod_municipio='".mysql_real_escape_string($cod_mun)."' ":"";
		$this->query = "
			SELECT
				cod_uf,
				cod_municipio,
				nome_municipio
			FROM
				municipio
			WHERE
				cod_uf = '".mysql_real_escape_string($cod_uf)."'
				$sqlComplem
		";
		$this->query();
		return $this->qrdata;
	}
	
	function searchMunicipio($cod_uf, $nome_municipio) {
		$this->query = "
			SELECT
				cod_uf,
				cod_municipio,
				nome_municipio
			FROM
				municipio
			WHERE
				cod_uf = '".mysql_real_escape_string($cod_uf)."' and 
				nome_municipio like '%".mysql_real_escape_string($nome_municipio)."%'
		";
		$this->query();
		return $this->qrdata;
	}

	function getMunicipio($cod_municipio) {
		$this->query = "
			SELECT
				municipio.cod_uf,
				municipio.cod_municipio,
				municipio.nome_municipio, 
				uf.nome_uf
			FROM
				municipio, 
				uf 
			WHERE
				municipio.cod_municipio = '".mysql_real_escape_string($cod_municipio)."' and 
				municipio.cod_uf = uf.cod_uf 
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class municipiodocumento extends database {

	function municipiodocumento() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_uf,cod_municipio,cod_mndc,documento_mndc,descr_documento_mndc,entidade_mndc,descr_entidade_mndc,prazo_mndc,flgativo_mndc,flgproponente_mndc,flgvendedorpf_mndc,flgvendedorpj_mndc,flgimovel_mndc,flgconjugeproponente_mndc,flgconjugevendpf_mndc,flgfgts_mndc
			FROM municipiodocumento
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_mndc) {
		$this->query = "
			SELECT cod_uf,cod_municipio,cod_mndc,documento_mndc,descr_documento_mndc,entidade_mndc,descr_entidade_mndc,prazo_mndc,flgativo_mndc,flgproponente_mndc,flgvendedorpf_mndc,flgvendedorpj_mndc,flgimovel_mndc,flgconjugeproponente_mndc,flgconjugevendpf_mndc,flgfgts_mndc
			FROM municipiodocumento
			WHERE cod_mndc = '".mysql_real_escape_string($cod_mndc)."'
		";
		
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_uf,$cod_municipio,$cod_mndc,$documento_mndc,$descr_documento_mndc,$entidade_mndc,$descr_entidade_mndc,$prazo_mndc,$flgativo_mndc,$flgproponente_mndc,$flgvendedorpf_mndc,$flgvendedorpj_mndc,$flgimovel_mndc,$flgconjugeproponente_mndc,$flgconjugevendpf_mndc,$flgfgts_mndc) {
		$this->query = "
			UPDATE municipiodocumento SET 
			cod_uf = ".(!$cod_uf ? "NULL" : "'".mysql_real_escape_string($cod_uf)."'").",cod_municipio = ".(!$cod_municipio ? "NULL" : "'".mysql_real_escape_string($cod_municipio)."'").",documento_mndc = ".(!$documento_mndc ? "NULL" : "'".mysql_real_escape_string($documento_mndc)."'").",descr_documento_mndc = ".(!$descr_documento_mndc ? "NULL" : "'".mysql_real_escape_string($descr_documento_mndc)."'").",entidade_mndc = ".(!$entidade_mndc ? "NULL" : "'".mysql_real_escape_string($entidade_mndc)."'").",descr_entidade_mndc = ".(!$descr_entidade_mndc ? "NULL" : "'".mysql_real_escape_string($descr_entidade_mndc)."'").",prazo_mndc = ".(!$prazo_mndc ? "NULL" : "'".mysql_real_escape_string($prazo_mndc)."'").",flgativo_mndc = ".(!$flgativo_mndc ? "NULL" : "'".mysql_real_escape_string($flgativo_mndc)."'").",flgproponente_mndc = ".(!$flgproponente_mndc ? "NULL" : "'".mysql_real_escape_string($flgproponente_mndc)."'").",flgvendedorpf_mndc = ".(!$flgvendedorpf_mndc ? "NULL" : "'".mysql_real_escape_string($flgvendedorpf_mndc)."'").",flgvendedorpj_mndc = ".(!$flgvendedorpj_mndc ? "NULL" : "'".mysql_real_escape_string($flgvendedorpj_mndc)."'").",flgimovel_mndc = ".(!$flgimovel_mndc ? "NULL" : "'".mysql_real_escape_string($flgimovel_mndc)."'").",flgconjugeproponente_mndc = ".(!$flgconjugeproponente_mndc ? "NULL" : "'".mysql_real_escape_string($flgconjugeproponente_mndc)."'").",flgconjugevendpf_mndc = ".(!$flgconjugevendpf_mndc ? "NULL" : "'".mysql_real_escape_string($flgconjugevendpf_mndc)."'").",flgconjugevendpf_mndc = ".(!$flgfgts_mndc ? "NULL" : "'".mysql_real_escape_string($flgfgts_mndc)."'")."
			WHERE cod_mndc = '".mysql_real_escape_string($cod_mndc)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_mndc) {
		$this->query = "
			DELETE FROM municipiodocumento 
			WHERE cod_mndc = '".mysql_real_escape_string($cod_mndc)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_uf,$cod_municipio,$documento_mndc,$descr_documento_mndc,$entidade_mndc,$descr_entidade_mndc,$prazo_mndc,$flgativo_mndc,$flgproponente_mndc,$flgvendedorpf_mndc,$flgvendedorpj_mndc,$flgimovel_mndc,$flgconjugeproponente_mndc,$flgconjugevendpf_mndc,$flgfgts_mndc) {
		$this->query = "
			INSERT INTO municipiodocumento ( cod_uf,cod_municipio,documento_mndc,descr_documento_mndc,entidade_mndc,descr_entidade_mndc,prazo_mndc,flgativo_mndc,flgproponente_mndc,flgvendedorpf_mndc,flgvendedorpj_mndc,flgimovel_mndc,flgconjugeproponente_mndc,flgconjugevendpf_mndc,flgfgts_mndc ) VALUES (
				".(!$cod_uf ? "NULL" : "'".mysql_real_escape_string($cod_uf)."'").",".(!$cod_municipio ? "NULL" : "'".mysql_real_escape_string($cod_municipio)."'").",".(!$documento_mndc ? "NULL" : "'".mysql_real_escape_string($documento_mndc)."'").",".(!$descr_documento_mndc ? "NULL" : "'".mysql_real_escape_string($descr_documento_mndc)."'").",".(!$entidade_mndc ? "NULL" : "'".mysql_real_escape_string($entidade_mndc)."'").",".(!$descr_entidade_mndc ? "NULL" : "'".mysql_real_escape_string($descr_entidade_mndc)."'").",".(!$prazo_mndc ? "NULL" : "'".mysql_real_escape_string($prazo_mndc)."'").",".(!$flgativo_mndc ? "NULL" : "'".mysql_real_escape_string($flgativo_mndc)."'").",".(!$flgproponente_mndc ? "NULL" : "'".mysql_real_escape_string($flgproponente_mndc)."'").",".(!$flgvendedorpf_mndc ? "NULL" : "'".mysql_real_escape_string($flgvendedorpf_mndc)."'").",".(!$flgvendedorpj_mndc ? "NULL" : "'".mysql_real_escape_string($flgvendedorpj_mndc)."'").",".(!$flgimovel_mndc ? "NULL" : "'".mysql_real_escape_string($flgimovel_mndc)."'").",".(!$flgconjugeproponente_mndc ? "NULL" : "'".mysql_real_escape_string($flgconjugeproponente_mndc)."'").",".(!$flgconjugevendpf_mndc ? "NULL" : "'".mysql_real_escape_string($flgconjugevendpf_mndc)."'").",".(!$flgfgts_mndc ? "NULL" : "'".mysql_real_escape_string($flgfgts_mndc)."'")."
			)
		";
		//echo $this->query;
		$this->query();
		return $this->qrdata;
	}
	
	function pesquisarPorUfMunicipio($cod_uf,$cod_municipio,$tipo = false) {
		
		$sQueryCompl = '';
		switch ($tipo) {
			case 'proponente':
				$sQueryCompl = ' and flgproponente_mndc = \'S\'';
			break;
			case 'vendfis':
				$sQueryCompl = ' and flgvendedorpf_mndc = \'S\'';
			break;
			case 'vendjur':
				$sQueryCompl = ' and flgvendedorpj_mndc = \'S\'';
			break;
			case 'vendfisconjuge':
				$sQueryCompl = ' and flgconjugevendpf_mndc = \'S\'';
			break;
			case 'proponenteconjuge':
				$sQueryCompl = ' and flgconjugeproponente_mndc = \'S\'';
			break;
			case 'imovel':
				$sQueryCompl = ' and flgimovel_mndc = \'S\'';
			break;
			case 'fgts':
				$sQueryCompl = ' and flgfgts_mndc = \'S\'';	
			break;
		}
		$this->query = "
			SELECT cod_uf,cod_municipio,cod_mndc,documento_mndc,descr_documento_mndc,entidade_mndc,descr_entidade_mndc,prazo_mndc,flgativo_mndc,flgproponente_mndc,flgvendedorpf_mndc,flgvendedorpj_mndc,flgimovel_mndc,flgconjugeproponente_mndc,flgconjugevendpf_mndc, flgfgts_mndc
			FROM municipiodocumento
			WHERE cod_uf = '".mysql_real_escape_string($cod_uf)."' and cod_municipio = '".mysql_real_escape_string($cod_municipio)."'".$sQueryCompl."
		";
		//echo $this->query;
		$this->query();
		return $this->qrdata;
	}

}
					
class pais extends database {

	function pais() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_pais,nome_pais,sigla_pais
			FROM pais
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_pais) {
		$this->query = "
			SELECT cod_pais,nome_pais,sigla_pais
			FROM pais
			WHERE cod_pais = '".mysql_real_escape_string($cod_pais)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_pais,$nome_pais,$sigla_pais) {
		$this->query = "
			UPDATE pais SET 
			cod_pais = ".(!$cod_pais ? "NULL" : "'".mysql_real_escape_string($cod_pais)."'").",nome_pais = ".(!$nome_pais ? "NULL" : "'".mysql_real_escape_string($nome_pais)."'").",sigla_pais = ".(!$sigla_pais ? "NULL" : "'".mysql_real_escape_string($sigla_pais)."'")."
			WHERE cod_pais = '".mysql_real_escape_string($cod_pais)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_pais) {
		$this->query = "
			DELETE FROM pais 
			WHERE cod_pais = '".mysql_real_escape_string($cod_pais)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_pais,$nome_pais,$sigla_pais) {
		$this->query = "
			INSERT INTO pais ( cod_pais,nome_pais,sigla_pais ) VALUES (
				".(!$cod_pais ? "NULL" : "'".mysql_real_escape_string($cod_pais)."'").",".(!$nome_pais ? "NULL" : "'".mysql_real_escape_string($nome_pais)."'").",".(!$sigla_pais ? "NULL" : "'".mysql_real_escape_string($sigla_pais)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}
	
	function getListaPais($cod_pais=false) {
		$sqlCompl = ($cod_pais)?" WHERE cod_pais='".mysql_real_escape_string($cod_pais)."' ":"";
		$this->query = "
			SELECT
				cod_pais,
				nome_pais
			FROM
				pais
			$sqlCompl
			ORDER BY
				nome_pais
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class parametro extends database {

	function parametro() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_param,titulo_param,valor_param,tipo_param
			FROM parametro
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_param) {
		$this->query = "
			SELECT cod_param,titulo_param,valor_param,tipo_param
			FROM parametro
			WHERE cod_param = '".mysql_real_escape_string($cod_param)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_param,$titulo_param,$valor_param,$tipo_param) {
		$this->query = "
			UPDATE parametro SET 
			titulo_param = ".(!$titulo_param ? "NULL" : "'".mysql_real_escape_string($titulo_param)."'").",valor_param = ".(!$valor_param ? "NULL" : "'".mysql_real_escape_string($valor_param)."'").",tipo_param = ".(!$tipo_param ? "NULL" : "'".mysql_real_escape_string($tipo_param)."'")."
			WHERE cod_param = '".mysql_real_escape_string($cod_param)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_param) {
		$this->query = "
			DELETE FROM parametro 
			WHERE cod_param = '".mysql_real_escape_string($cod_param)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($titulo_param,$valor_param,$tipo_param) {
		$this->query = "
			INSERT INTO parametro ( titulo_param,valor_param,tipo_param ) VALUES (
				".(!$titulo_param ? "NULL" : "'".mysql_real_escape_string($titulo_param)."'").",".(!$valor_param ? "NULL" : "'".mysql_real_escape_string($valor_param)."'").",".(!$tipo_param ? "NULL" : "'".mysql_real_escape_string($tipo_param)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
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

	function listaValoresBoleto($uf=false) {
		$where = ($uf)?" AND titulo_param='".$uf."' ":'';
		$this->query="SELECT valor_param, titulo_param 
									FROM parametro
									WHERE tipo_param='valores de boleto'
									".$where."
									ORDER BY valor_param";
		$this->query();
		//print  $this->query.'<hr>';
		if($uf){
			$tmp = '';
			if (is_array($this->qrdata) && @count($this->qrdata) > 0) {
				$tmp = $this->qrdata[0]['valor_param'];
			}
		}else{
			$tmp = array();
			if (is_array($this->qrdata) && @count($this->qrdata) > 0) {
				foreach($this->qrdata as $k=>$v){
					$vv = $v['titulo_param']; 
					$tmp[$vv] = $v['valor_param'];
				}
			}
		}
		return $tmp;
	}
	
	function getTaxaJuros() {
		$this->query="SELECT valor_param FROM parametro WHERE tipo_param='taxa de juros'";
		$this->query();
		if (is_array($this->qrdata) && @count($this->qrdata) > 0) {
			return (float)$this->qrdata[0]['valor_param'];
		}
		return 0;
	}
		
	function listaTipoImovel() {
		$this->query="SELECT valor_param, titulo_param FROM parametro WHERE tipo_param='tipos de imovel'";
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
	
	function getListaTipoApartam() {
		$this->query="SELECT valor_param, titulo_param FROM parametro WHERE tipo_param='tipos de apartamento'";
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

	function listaTipoImposto() {
		$this->query="SELECT valor_param, titulo_param FROM parametro WHERE tipo_param='tipos de imposto'";
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
	
	function listaTipoConstrucao() {
		$this->query="SELECT valor_param, titulo_param FROM parametro WHERE tipo_param='tipos de construcao'";
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
	
	function listaTipoCondominio() {
		$this->query="SELECT valor_param, titulo_param FROM parametro WHERE tipo_param='tipos de condominio'";
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

	function listaTipoConservacao() {
		$this->query="SELECT valor_param, titulo_param FROM parametro WHERE tipo_param='tipos de conservacao'";
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
	
	function listaTipoMoradia() {
		$this->query="SELECT valor_param, titulo_param FROM parametro WHERE tipo_param='tipos de moradia'";
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
	
	function listaImovelTerreo() {
		$this->query="SELECT valor_param, titulo_param FROM parametro WHERE tipo_param='terreo'";
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

	function getTipoVaga() {
		$this->query="SELECT valor_param, titulo_param FROM parametro WHERE tipo_param='tipo de vaga' ORDER BY cod_param";
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

	function getLocalVaga() {
		$this->query="SELECT valor_param, titulo_param FROM parametro WHERE tipo_param='local da vaga' ORDER BY cod_param";
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
	
	function getListaRegimeBens() {
		$this->query="SELECT valor_param, titulo_param FROM parametro WHERE tipo_param='regime de bens'";
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
	
	function getListaSexo() {
		$this->query="SELECT valor_param, titulo_param FROM parametro WHERE tipo_param='tipos de sexo'";
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

}
					
class profissao extends database {

	function profissao() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_prof,desc_prof
			FROM profissao
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_prof) {
		$this->query = "
			SELECT cod_prof,desc_prof
			FROM profissao
			WHERE cod_prof = '".mysql_real_escape_string($cod_prof)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_prof,$desc_prof) {
		$this->query = "
			UPDATE profissao SET 
			cod_prof = ".(!$cod_prof ? "NULL" : "'".mysql_real_escape_string($cod_prof)."'").",desc_prof = ".(!$desc_prof ? "NULL" : "'".mysql_real_escape_string($desc_prof)."'")."
			WHERE cod_prof = '".mysql_real_escape_string($cod_prof)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_prof) {
		$this->query = "
			DELETE FROM profissao 
			WHERE cod_prof = '".mysql_real_escape_string($cod_prof)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_prof,$desc_prof) {
		$this->query = "
			INSERT INTO profissao ( cod_prof,desc_prof ) VALUES (
				".(!$cod_prof ? "NULL" : "'".mysql_real_escape_string($cod_prof)."'").",".(!$desc_prof ? "NULL" : "'".mysql_real_escape_string($desc_prof)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}
	
	function getListaProfissoes($cod_prof=false) {
		$sqlComplem = ($cod_prof)?" WHERE cod_prof='".mysql_real_escape_string($cod_prof)."' ":"";
		$this->query = "
			SELECT
				cod_prof,
				desc_prof
			FROM
				profissao 
			$sqlComplem
			ORDER BY 
				desc_prof
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class proponente extends database {

	function proponente() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cpf_ppnt,nrrg_ppnt,orgrg_ppnt,dtrg_ppnt,dtnascimento_ppnt,sexo_ppnt,nacional_ppnt,endereco_ppnt,nrendereco_ppnt,cpendereco_ppnt,bairro_ppnt,cep_ppnt,cod_proponente,cod_logr,cod_bairro,cod_uf,cod_municipio,cod_estciv,telefone_ppnt,cod_ppst,vlfinsol_ppnt,przfinsol_ppnt,vlsinal_ppnt,vlentrada_ppnt,vlprestsol_ppnt,vlcompra_ppnt,vlfinaprov_ppnt,vlprestaprov_ppnt,przaprov_ppnt,despachante_ppnt,flgdevsol_ppnt,email_ppnt,profissao_ppnt,flgproc_ppnt,proc_ppnt,flguniest_ppnt,flgescritura_ppnt
			FROM proponente
		";
		$this->query();
		return $this->qrdata;
	}
	
	function listarPorUsuarios($aListaUsuarios) {
		if (is_array($aListaUsuarios) && @count($aListaUsuarios) > 0) {
			$this->query = "

			SELECT cpf_ppnt,nrrg_ppnt,orgrg_ppnt,dtrg_ppnt,dtnascimento_ppnt,sexo_ppnt,nacional_ppnt,endereco_ppnt,nrendereco_ppnt,cpendereco_ppnt,bairro_ppnt,cep_ppnt,cod_proponente,cod_logr,cod_bairro,cod_uf,cod_municipio,cod_estciv,telefone_ppnt,cod_ppst,vlfinsol_ppnt,przfinsol_ppnt,vlsinal_ppnt,vlentrada_ppnt,vlprestsol_ppnt,vlcompra_ppnt,vlfinaprov_ppnt,vlprestaprov_ppnt,przaprov_ppnt,despachante_ppnt,flgdevsol_ppnt,email_ppnt,profissao_ppnt,flgproc_ppnt,proc_ppnt,flguniest_ppnt,flgescritura_ppnt
				FROM proponente
				WHERE
					cod_proponente in ('".implode("','",$aListaUsuarios)."')
			";
			$this->query();
			//echo $this->query;
			//query funcionando e retornando resultados v�lidos, mas o qrdata est� em branco
			return $this->qrdata;
		} else {
			return false;
		}
	}

	function pesquisarPorCpf($cpf) {
		$utils = new utils();
		$cpf = $utils->limpaCPF($cpf);
		$this->query = "
			SELECT cpf_ppnt,nrrg_ppnt,orgrg_ppnt,dtrg_ppnt,dtnascimento_ppnt,sexo_ppnt,nacional_ppnt,endereco_ppnt,nrendereco_ppnt,cpendereco_ppnt,bairro_ppnt,cep_ppnt,cod_proponente,cod_logr,cod_bairro,cod_uf,cod_municipio,cod_estciv,telefone_ppnt,cod_ppst,vlfinsol_ppnt,przfinsol_ppnt,vlsinal_ppnt,vlentrada_ppnt,vlprestsol_ppnt,vlcompra_ppnt,vlfinaprov_ppnt,vlprestaprov_ppnt,przaprov_ppnt,despachante_ppnt,flgdevsol_ppnt,flgproc_ppnt,proc_ppnt,flguniest_ppnt,flgescritura_ppnt
			FROM proponente
			WHERE
				cpf_ppnt = '".$cpf."'
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_proponente,$cod_ppst) {
		$this->query = "
			SELECT cpf_ppnt,nrrg_ppnt,orgrg_ppnt,dtrg_ppnt,dtnascimento_ppnt,sexo_ppnt,nacional_ppnt,endereco_ppnt,nrendereco_ppnt,cpendereco_ppnt,bairro_ppnt,cep_ppnt,cod_proponente,cod_logr,cod_bairro,cod_uf,cod_municipio,cod_estciv,telefone_ppnt,cod_ppst,vlfinsol_ppnt,przfinsol_ppnt,vlsinal_ppnt,vlentrada_ppnt,vlprestsol_ppnt,vlcompra_ppnt,vlfinaprov_ppnt,vlprestaprov_ppnt,przaprov_ppnt,despachante_ppnt,flgdevsol_ppnt,email_ppnt,profissao_ppnt,flgproc_ppnt,proc_ppnt,flguniest_ppnt,flgescritura_ppnt
			FROM proponente
			WHERE cod_proponente = '".mysql_real_escape_string($cod_proponente)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cpf_ppnt,$nrrg_ppnt,$orgrg_ppnt,$dtrg_ppnt,$dtnascimento_ppnt,$sexo_ppnt,$nacional_ppnt,$endereco_ppnt,$nrendereco_ppnt,$cpendereco_ppnt,$bairro_ppnt,$cep_ppnt,$cod_proponente,$cod_logr,$cod_bairro,$cod_uf,$cod_municipio,$cod_estciv,$telefone_ppnt,$cod_ppst,$vlfinsol_ppnt,$przfinsol_ppnt,$vlsinal_ppnt,$vlentrada_ppnt,$vlprestsol_ppnt,$vlcompra_ppnt,$vlfinaprov_ppnt,$vlprestaprov_ppnt,$przaprov_ppnt,$despachante_ppnt,$flgdevsol_ppnt = "",$email_ppnt,$profissao_ppnt,$flgproc_ppnt,$proc_ppnt,$flguniest_ppnt,$flgescritura_ppnt) {
		$this->query = "
			UPDATE proponente SET 
			cpf_ppnt = ".(!$cpf_ppnt ? "NULL" : "'".mysql_real_escape_string($cpf_ppnt)."'").",nrrg_ppnt = ".(!$nrrg_ppnt ? "NULL" : "'".mysql_real_escape_string($nrrg_ppnt)."'").",orgrg_ppnt = ".(!$orgrg_ppnt ? "NULL" : "'".mysql_real_escape_string($orgrg_ppnt)."'").",dtrg_ppnt = ".(!$dtrg_ppnt ? "NULL" : "'".mysql_real_escape_string($dtrg_ppnt)."'").",dtnascimento_ppnt = ".(!$dtnascimento_ppnt ? "NULL" : "'".mysql_real_escape_string($dtnascimento_ppnt)."'").",sexo_ppnt = ".(!$sexo_ppnt ? "NULL" : "'".mysql_real_escape_string($sexo_ppnt)."'").",nacional_ppnt = ".(!$nacional_ppnt ? "NULL" : "'".mysql_real_escape_string($nacional_ppnt)."'").",endereco_ppnt = ".(!$endereco_ppnt ? "NULL" : "'".mysql_real_escape_string($endereco_ppnt)."'").",nrendereco_ppnt = ".(!$nrendereco_ppnt ? "0" : "'".mysql_real_escape_string($nrendereco_ppnt)."'").",cpendereco_ppnt = ".(!$cpendereco_ppnt ? "NULL" : "'".mysql_real_escape_string($cpendereco_ppnt)."'").",bairro_ppnt = ".(!$bairro_ppnt ? "NULL" : "'".mysql_real_escape_string($bairro_ppnt)."'").",cep_ppnt = ".(!$cep_ppnt ? "NULL" : "'".mysql_real_escape_string($cep_ppnt)."'").",cod_proponente = ".(!$cod_proponente ? "NULL" : "'".mysql_real_escape_string($cod_proponente)."'").",cod_logr = ".(!$cod_logr ? "NULL" : "'".mysql_real_escape_string($cod_logr)."'").",cod_bairro = ".(!$cod_bairro ? "NULL" : "'".mysql_real_escape_string($cod_bairro)."'").",cod_uf = ".(!$cod_uf ? "NULL" : "'".mysql_real_escape_string($cod_uf)."'").",cod_municipio = ".(!$cod_municipio ? "NULL" : "'".mysql_real_escape_string($cod_municipio)."'").",cod_estciv = ".(!$cod_estciv ? "NULL" : "'".mysql_real_escape_string($cod_estciv)."'").",telefone_ppnt = ".(!$telefone_ppnt ? "NULL" : "'".mysql_real_escape_string($telefone_ppnt)."'").",cod_ppst = ".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",vlfinsol_ppnt = ".(!$vlfinsol_ppnt ? "NULL" : "'".mysql_real_escape_string($vlfinsol_ppnt)."'").",przfinsol_ppnt = ".(!$przfinsol_ppnt ? "NULL" : "'".mysql_real_escape_string($przfinsol_ppnt)."'").",vlsinal_ppnt = ".(!$vlsinal_ppnt ? "NULL" : "'".mysql_real_escape_string($vlsinal_ppnt)."'").",vlentrada_ppnt = ".(!$vlentrada_ppnt ? "NULL" : "'".mysql_real_escape_string($vlentrada_ppnt)."'").",vlprestsol_ppnt = ".(!$vlprestsol_ppnt ? "NULL" : "'".mysql_real_escape_string($vlprestsol_ppnt)."'").",vlcompra_ppnt = ".(!$vlcompra_ppnt ? "NULL" : "'".mysql_real_escape_string($vlcompra_ppnt)."'").",vlfinaprov_ppnt = ".(!$vlfinaprov_ppnt ? "NULL" : "'".mysql_real_escape_string($vlfinaprov_ppnt)."'").",vlprestaprov_ppnt = ".(!$vlprestaprov_ppnt ? "NULL" : "'".mysql_real_escape_string($vlprestaprov_ppnt)."'").",przaprov_ppnt = ".(!$przaprov_ppnt ? "NULL" : "'".mysql_real_escape_string($przaprov_ppnt)."'").",despachante_ppnt = ".(!$despachante_ppnt ? "NULL" : "'".mysql_real_escape_string($despachante_ppnt)."'").",flgdevsol_ppnt = ".(!$flgdevsol_ppnt ? "NULL" : "'".mysql_real_escape_string($flgdevsol_ppnt)."',email_ppnt = ".(!$email_ppnt ? "NULL" : "'".mysql_real_escape_string($email_ppnt)."'").",profissao_ppnt = ".(!$profissao_ppnt ? "NULL" : "'".mysql_real_escape_string($profissao_ppnt)."'").",flgproc_ppnt = ".(!$flgproc_ppnt ? "NULL" : "'".mysql_real_escape_string($flgproc_ppnt)."'").",proc_ppnt = ".(!$proc_ppnt ? "NULL" : "'".mysql_real_escape_string($proc_ppnt)."'")."").",flguniest_ppnt=".(!$flguniest_ppnt ? "NULL" : "'".mysql_real_escape_string($flguniest_ppnt)."'").",flgescritura_ppnt=".(!$flgescritura_ppnt ? "NULL" : "'".mysql_real_escape_string($flgescritura_ppnt)."'")."
			WHERE cod_proponente = '".mysql_real_escape_string($cod_proponente)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_proponente,$cod_ppst) {
		$this->query = "
			DELETE FROM proponente 
			WHERE cod_proponente = '".mysql_real_escape_string($cod_proponente)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cpf_ppnt,$nrrg_ppnt,$orgrg_ppnt,$dtrg_ppnt,$dtnascimento_ppnt,$sexo_ppnt,$nacional_ppnt,$endereco_ppnt,$nrendereco_ppnt,$cpendereco_ppnt,$bairro_ppnt,$cep_ppnt,$cod_proponente,$cod_logr,$cod_bairro,$cod_uf,$cod_municipio,$cod_estciv,$telefone_ppnt,$cod_ppst,$vlfinsol_ppnt,$przfinsol_ppnt,$vlsinal_ppnt,$vlentrada_ppnt,$vlprestsol_ppnt,$vlcompra_ppnt,$vlfinaprov_ppnt,$vlprestaprov_ppnt,$przaprov_ppnt,$despachante_ppnt = "",$flgdevsol_ppnt = "",$email_ppnt,$profissao_ppnt,$flgproc_ppnt,$proc_ppnt,$flguniest_ppnt,$flgescritura_ppnt) {
		$this->query = "
			INSERT INTO proponente (cpf_ppnt,nrrg_ppnt,orgrg_ppnt,dtrg_ppnt,dtnascimento_ppnt,sexo_ppnt,nacional_ppnt,endereco_ppnt,nrendereco_ppnt,cpendereco_ppnt,bairro_ppnt,cep_ppnt,cod_proponente,cod_logr,cod_bairro,cod_uf,cod_municipio,cod_estciv,telefone_ppnt,email_ppnt,profissao_ppnt,cod_ppst,vlfinsol_ppnt,przfinsol_ppnt,vlsinal_ppnt,vlentrada_ppnt,vlprestsol_ppnt,vlcompra_ppnt,vlfinaprov_ppnt,vlprestaprov_ppnt,przaprov_ppnt,despachante_ppnt,flgdevsol_ppnt,flgproc_ppnt,proc_ppnt,flguniest_ppnt,flgescritura_ppnt) VALUES (
				".(!$cpf_ppnt ? "NULL" : "'".mysql_real_escape_string($cpf_ppnt)."'").",".(!$nrrg_ppnt ? "NULL" : "'".mysql_real_escape_string($nrrg_ppnt)."'").",".(!$orgrg_ppnt ? "NULL" : "'".mysql_real_escape_string($orgrg_ppnt)."'").",".(!$dtrg_ppnt ? "NULL" : "'".mysql_real_escape_string($dtrg_ppnt)."'").",".(!$dtnascimento_ppnt ? "NULL" : "'".mysql_real_escape_string($dtnascimento_ppnt)."'").",".(!$sexo_ppnt ? "NULL" : "'".mysql_real_escape_string($sexo_ppnt)."'").",".(!$nacional_ppnt ? "NULL" : "'".mysql_real_escape_string($nacional_ppnt)."'").",".(!$endereco_ppnt ? "NULL" : "'".mysql_real_escape_string($endereco_ppnt)."'").",".(!$nrendereco_ppnt ? "0" : "'".mysql_real_escape_string($nrendereco_ppnt)."'").",".(!$cpendereco_ppnt ? "NULL" : "'".mysql_real_escape_string($cpendereco_ppnt)."'").",".(!$bairro_ppnt ? "NULL" : "'".mysql_real_escape_string($bairro_ppnt)."'").",".(!$cep_ppnt ? "NULL" : "'".mysql_real_escape_string($cep_ppnt)."'").",".(!$cod_proponente ? "NULL" : "'".mysql_real_escape_string($cod_proponente)."'").",".(!$cod_logr ? "NULL" : "'".mysql_real_escape_string($cod_logr)."'").",".(!$cod_bairro ? "NULL" : "'".mysql_real_escape_string($cod_bairro)."'").",".(!$cod_uf ? "NULL" : "'".mysql_real_escape_string($cod_uf)."'").",".(!$cod_municipio ? "NULL" : "'".mysql_real_escape_string($cod_municipio)."'").",".(!$cod_estciv ? "NULL" : "'".mysql_real_escape_string($cod_estciv)."'").",".(!$telefone_ppnt ? "NULL" : "'".mysql_real_escape_string($telefone_ppnt)."'").",".(!$email_ppnt ? "NULL" : "'".mysql_real_escape_string($email_ppnt)."'").",".(!$profissao_ppnt ? "NULL" : "'".mysql_real_escape_string($profissao_ppnt)."'").",".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",".(!$vlfinsol_ppnt ? "NULL" : "'".mysql_real_escape_string($vlfinsol_ppnt)."'").",".(!$przfinsol_ppnt ? "NULL" : "'".mysql_real_escape_string($przfinsol_ppnt)."'").",".(!$vlsinal_ppnt ? "NULL" : "'".mysql_real_escape_string($vlsinal_ppnt)."'").",".(!$vlentrada_ppnt ? "NULL" : "'".mysql_real_escape_string($vlentrada_ppnt)."'").",".(!$vlprestsol_ppnt ? "NULL" : "'".mysql_real_escape_string($vlprestsol_ppnt)."'").",".(!$vlcompra_ppnt ? "NULL" : "'".mysql_real_escape_string($vlcompra_ppnt)."'").",".(!$vlfinaprov_ppnt ? "NULL" : "'".mysql_real_escape_string($vlfinaprov_ppnt)."'").",".(!$vlprestaprov_ppnt ? "NULL" : "'".mysql_real_escape_string($vlprestaprov_ppnt)."'").",".(!$przaprov_ppnt ? "NULL" : "'".mysql_real_escape_string($przaprov_ppnt)."'").",".(!$despachante_ppnt ? "NULL" : "'".mysql_real_escape_string($flgdevsol_ppnt)."'").",".(!$flgdevsol_ppnt ? "NULL" : "'".mysql_real_escape_string($flgdevsol_ppnt)."'").",".(!$flgproc_ppnt ? "NULL" : "'".mysql_real_escape_string($flgproc_ppnt)."'").",".(!$proc_ppnt ? "NULL" : "'".mysql_real_escape_string($proc_ppnt)."'").",".(!$flguniest_ppnt ? "NULL" : "'".mysql_real_escape_string($flguniest_ppnt)."'").",".(!$flgescritura_ppnt ? "NULL" : "'".mysql_real_escape_string($flgescritura_ppnt)."'").")";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPorUsuario($cod_proponente) {
		$this->query = "
			SELECT cpf_ppnt,nrrg_ppnt,orgrg_ppnt,dtrg_ppnt,dtnascimento_ppnt,sexo_ppnt,nacional_ppnt,endereco_ppnt,nrendereco_ppnt,cpendereco_ppnt,bairro_ppnt,cep_ppnt,cod_proponente,cod_logr,cod_bairro,cod_uf,cod_municipio,cod_estciv,telefone_ppnt,cod_ppst,vlfinsol_ppnt,przfinsol_ppnt,vlsinal_ppnt,vlentrada_ppnt,vlprestsol_ppnt,vlcompra_ppnt,vlfinaprov_ppnt,vlprestaprov_ppnt,przaprov_ppnt,despachante_ppnt,flgdevsol_ppnt,email_ppnt,profissao_ppnt,flgproc_ppnt,proc_ppnt,flguniest_ppnt,flgescritura_ppnt
			FROM proponente
			WHERE cod_proponente = '".mysql_real_escape_string($cod_proponente)."'
		";
		//echo $this->query;
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPorProposta($cod_ppst) {
		$this->query = "
			SELECT cpf_ppnt,nrrg_ppnt,orgrg_ppnt,dtrg_ppnt,dtnascimento_ppnt,sexo_ppnt,nacional_ppnt,endereco_ppnt,nrendereco_ppnt,cpendereco_ppnt,bairro_ppnt,cep_ppnt,cod_proponente,cod_logr,cod_bairro,cod_uf,cod_municipio,cod_estciv,telefone_ppnt,cod_ppst,vlfinsol_ppnt,przfinsol_ppnt,vlsinal_ppnt,vlentrada_ppnt,vlprestsol_ppnt,vlcompra_ppnt,vlfinaprov_ppnt,vlprestaprov_ppnt,przaprov_ppnt,despachante_ppnt,flgdevsol_ppnt,email_ppnt,profissao_ppnt,flgproc_ppnt,proc_ppnt,flguniest_ppnt,flgescritura_ppnt
			FROM proponente
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
			ORDER BY cod_proponente
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class proponenteconjuge extends database {

	function proponenteconjuge() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_proponente,regimebens_ppcj,dtcasamento_ppcj,nome_ppcj,cod_pais,cod_estciv,nrrg_ppcj,orgrg_ppcj,dtrg_ppcj,cpf_pccj,flgtrabalha_ppcj,empresa_ppcj,dtadmissaoemp_ppcj,enderecoemp_ppcj,numeroemp_ppcj,complementoemp_ppcj,bairroemp_ppcj,cidadeemp_ppcj,estadoemp_ppcj,telefoneemp_ppcj,cargoemp_ppcj,salarioemp_ppcj,cod_ppst,despachante_ppcj
			FROM proponenteconjuge
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_proponente,$cod_ppst) {
		$this->query = "
			SELECT cod_proponente,regimebens_ppcj,dtcasamento_ppcj,nome_ppcj,cod_pais,cod_estciv,nrrg_ppcj,orgrg_ppcj,dtrg_ppcj,cpf_pccj,flgtrabalha_ppcj,empresa_ppcj,dtadmissaoemp_ppcj,enderecoemp_ppcj,numeroemp_ppcj,complementoemp_ppcj,bairroemp_ppcj,cidadeemp_ppcj,estadoemp_ppcj,telefoneemp_ppcj,cargoemp_ppcj,salarioemp_ppcj,cod_ppst,despachante_ppcj
			FROM proponenteconjuge
			WHERE cod_proponente = '".mysql_real_escape_string($cod_proponente)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_proponente,$regimebens_ppcj,$dtcasamento_ppcj,$nome_ppcj,$cod_pais,$cod_estciv,$nrrg_ppcj,$orgrg_ppcj,$dtrg_ppcj,$cpf_pccj,$flgtrabalha_ppcj,$empresa_ppcj,$dtadmissaoemp_ppcj,$enderecoemp_ppcj,$numeroemp_ppcj,$complementoemp_ppcj,$bairroemp_ppcj,$cidadeemp_ppcj,$estadoemp_ppcj,$telefoneemp_ppcj,$cargoemp_ppcj,$salarioemp_ppcj,$cod_ppst,$despachante_ppcj) {
		$this->query = "
			UPDATE proponenteconjuge SET 
			cod_proponente = ".(!$cod_proponente ? "NULL" : "'".mysql_real_escape_string($cod_proponente)."'").",regimebens_ppcj = ".(!$regimebens_ppcj ? "NULL" : "'".mysql_real_escape_string($regimebens_ppcj)."'").",dtcasamento_ppcj = ".(!$dtcasamento_ppcj ? "NULL" : "'".mysql_real_escape_string($dtcasamento_ppcj)."'").",nome_ppcj = ".(!$nome_ppcj ? "NULL" : "'".mysql_real_escape_string($nome_ppcj)."'").",cod_pais = ".(!$cod_pais ? "NULL" : "'".mysql_real_escape_string($cod_pais)."'").",cod_estciv = ".(!$cod_estciv ? "NULL" : "'".mysql_real_escape_string($cod_estciv)."'").",nrrg_ppcj = ".(!$nrrg_ppcj ? "NULL" : "'".mysql_real_escape_string($nrrg_ppcj)."'").",orgrg_ppcj = ".(!$orgrg_ppcj ? "NULL" : "'".mysql_real_escape_string($orgrg_ppcj)."'").",dtrg_ppcj = ".(!$dtrg_ppcj ? "NULL" : "'".mysql_real_escape_string($dtrg_ppcj)."'").",cpf_pccj = ".(!$cpf_pccj ? "NULL" : "'".mysql_real_escape_string($cpf_pccj)."'").",flgtrabalha_ppcj = ".(!$flgtrabalha_ppcj ? "NULL" : "'".mysql_real_escape_string($flgtrabalha_ppcj)."'").",empresa_ppcj = ".(!$empresa_ppcj ? "NULL" : "'".mysql_real_escape_string($empresa_ppcj)."'").",dtadmissaoemp_ppcj = ".(!$dtadmissaoemp_ppcj ? "NULL" : "'".mysql_real_escape_string($dtadmissaoemp_ppcj)."'").",enderecoemp_ppcj = ".(!$enderecoemp_ppcj ? "NULL" : "'".mysql_real_escape_string($enderecoemp_ppcj)."'").",numeroemp_ppcj = ".(!$numeroemp_ppcj ? "NULL" : "'".mysql_real_escape_string($numeroemp_ppcj)."'").",complementoemp_ppcj = ".(!$complementoemp_ppcj ? "NULL" : "'".mysql_real_escape_string($complementoemp_ppcj)."'").",bairroemp_ppcj = ".(!$bairroemp_ppcj ? "NULL" : "'".mysql_real_escape_string($bairroemp_ppcj)."'").",cidadeemp_ppcj = ".(!$cidadeemp_ppcj ? "NULL" : "'".mysql_real_escape_string($cidadeemp_ppcj)."'").",estadoemp_ppcj = ".(!$estadoemp_ppcj ? "NULL" : "'".mysql_real_escape_string($estadoemp_ppcj)."'").",telefoneemp_ppcj = ".(!$telefoneemp_ppcj ? "NULL" : "'".mysql_real_escape_string($telefoneemp_ppcj)."'").",cargoemp_ppcj = ".(!$cargoemp_ppcj ? "NULL" : "'".mysql_real_escape_string($cargoemp_ppcj)."'").",salarioemp_ppcj = ".(!$salarioemp_ppcj ? "NULL" : "'".mysql_real_escape_string($salarioemp_ppcj)."'").",cod_ppst = ".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",despachante_ppcj = ".(!$despachante_ppcj ? "NULL" : "'".mysql_real_escape_string($despachante_ppcj)."'")."
			WHERE cod_proponente = '".mysql_real_escape_string($cod_proponente)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_proponente,$cod_ppst) {
		$this->query = "
			DELETE FROM proponenteconjuge 
			WHERE cod_proponente = '".mysql_real_escape_string($cod_proponente)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_proponente,$regimebens_ppcj,$dtcasamento_ppcj,$nome_ppcj,$cod_pais,$cod_estciv,$nrrg_ppcj,$orgrg_ppcj,$dtrg_ppcj,$cpf_pccj,$flgtrabalha_ppcj,$empresa_ppcj,$dtadmissaoemp_ppcj,$enderecoemp_ppcj,$numeroemp_ppcj,$complementoemp_ppcj,$bairroemp_ppcj,$cidadeemp_ppcj,$estadoemp_ppcj,$telefoneemp_ppcj,$cargoemp_ppcj,$salarioemp_ppcj,$cod_ppst,$despachante_ppcj) {
		$this->query = "
			INSERT INTO proponenteconjuge ( cod_proponente,regimebens_ppcj,dtcasamento_ppcj,nome_ppcj,cod_pais,cod_estciv,nrrg_ppcj,orgrg_ppcj,dtrg_ppcj,cpf_pccj,flgtrabalha_ppcj,empresa_ppcj,dtadmissaoemp_ppcj,enderecoemp_ppcj,numeroemp_ppcj,complementoemp_ppcj,bairroemp_ppcj,cidadeemp_ppcj,estadoemp_ppcj,telefoneemp_ppcj,cargoemp_ppcj,salarioemp_ppcj,cod_ppst,despachante_ppcj ) VALUES (
				".(!$cod_proponente ? "NULL" : "'".mysql_real_escape_string($cod_proponente)."'").",".(!$regimebens_ppcj ? "NULL" : "'".mysql_real_escape_string($regimebens_ppcj)."'").",".(!$dtcasamento_ppcj ? "NULL" : "'".mysql_real_escape_string($dtcasamento_ppcj)."'").",".(!$nome_ppcj ? "NULL" : "'".mysql_real_escape_string($nome_ppcj)."'").",".(!$cod_pais ? "NULL" : "'".mysql_real_escape_string($cod_pais)."'").",".(!$cod_estciv ? "NULL" : "'".mysql_real_escape_string($cod_estciv)."'").",".(!$nrrg_ppcj ? "NULL" : "'".mysql_real_escape_string($nrrg_ppcj)."'").",".(!$orgrg_ppcj ? "NULL" : "'".mysql_real_escape_string($orgrg_ppcj)."'").",".(!$dtrg_ppcj ? "NULL" : "'".mysql_real_escape_string($dtrg_ppcj)."'").",".(!$cpf_pccj ? "NULL" : "'".mysql_real_escape_string($cpf_pccj)."'").",".(!$flgtrabalha_ppcj ? "NULL" : "'".mysql_real_escape_string($flgtrabalha_ppcj)."'").",".(!$empresa_ppcj ? "NULL" : "'".mysql_real_escape_string($empresa_ppcj)."'").",".(!$dtadmissaoemp_ppcj ? "NULL" : "'".mysql_real_escape_string($dtadmissaoemp_ppcj)."'").",".(!$enderecoemp_ppcj ? "NULL" : "'".mysql_real_escape_string($enderecoemp_ppcj)."'").",".(!$numeroemp_ppcj ? "NULL" : "'".mysql_real_escape_string($numeroemp_ppcj)."'").",".(!$complementoemp_ppcj ? "NULL" : "'".mysql_real_escape_string($complementoemp_ppcj)."'").",".(!$bairroemp_ppcj ? "NULL" : "'".mysql_real_escape_string($bairroemp_ppcj)."'").",".(!$cidadeemp_ppcj ? "NULL" : "'".mysql_real_escape_string($cidadeemp_ppcj)."'").",".(!$estadoemp_ppcj ? "NULL" : "'".mysql_real_escape_string($estadoemp_ppcj)."'").",".(!$telefoneemp_ppcj ? "NULL" : "'".mysql_real_escape_string($telefoneemp_ppcj)."'").",".(!$cargoemp_ppcj ? "NULL" : "'".mysql_real_escape_string($cargoemp_ppcj)."'").",".(!$salarioemp_ppcj ? "NULL" : "'".mysql_real_escape_string($salarioemp_ppcj)."'").",".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",".(!$despachante_ppcj ? "NULL" : "'".mysql_real_escape_string($despachante_ppcj)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPorCpf($cpf) {
		$this->query = "
			SELECT cod_proponente,regimebens_ppcj,dtcasamento_ppcj,nome_ppcj,cod_pais,cod_estciv,nrrg_ppcj,orgrg_ppcj,dtrg_ppcj,cpf_pccj,flgtrabalha_ppcj,empresa_ppcj,dtadmissaoemp_ppcj,enderecoemp_ppcj,numeroemp_ppcj,complementoemp_ppcj,bairroemp_ppcj,cidadeemp_ppcj,estadoemp_ppcj,telefoneemp_ppcj,cargoemp_ppcj,salarioemp_ppcj,cod_ppst,despachante_ppcj
			FROM proponenteconjuge
			WHERE
				cpf_pccj = '".$cpf."'
		";
		$this->query();
		return $this->qrdata;
	}
	function pesquisarPorRG($rg) {
		$this->query = "
			SELECT cod_proponente,regimebens_ppcj,dtcasamento_ppcj,nome_ppcj,cod_pais,cod_estciv,nrrg_ppcj,orgrg_ppcj,dtrg_ppcj,cpf_pccj,flgtrabalha_ppcj,empresa_ppcj,dtadmissaoemp_ppcj,enderecoemp_ppcj,numeroemp_ppcj,complementoemp_ppcj,bairroemp_ppcj,cidadeemp_ppcj,estadoemp_ppcj,telefoneemp_ppcj,cargoemp_ppcj,salarioemp_ppcj,cod_ppst,despachante_ppcj
			FROM proponenteconjuge
			WHERE
				nrrg_ppcj = '".$rg."'
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class proponenteconjugepacto extends database {

	function proponenteconjugepacto() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_proponente,data_pcpa,locallavracao_pcpa,livro_pcpa,folha_pcpa,numeroregistro_pcpa,habens_pcpa,habenscart_pcpa,habensloccart_pcpa,habensdata_pcpa,cod_ppst
			FROM proponenteconjugepacto
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_proponente,$cod_ppst) {
		$this->query = "
			SELECT cod_proponente,data_pcpa,locallavracao_pcpa,livro_pcpa,folha_pcpa,numeroregistro_pcpa,habens_pcpa,habenscart_pcpa,habensloccart_pcpa,habensdata_pcpa,cod_ppst
			FROM proponenteconjugepacto
			WHERE cod_proponente = '".mysql_real_escape_string($cod_proponente)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_proponente,$data_pcpa,$locallavracao_pcpa,$livro_pcpa,$folha_pcpa,$numeroregistro_pcpa,$habens_pcpa,$habenscart_pcpa,$habensloccart_pcpa,$habensdata_pcpa,$cod_ppst) {
		$this->query = "
			UPDATE proponenteconjugepacto SET 
			cod_proponente = ".(!$cod_proponente ? "NULL" : "'".mysql_real_escape_string($cod_proponente)."'").",data_pcpa = ".(!$data_pcpa ? "NULL" : "'".mysql_real_escape_string($data_pcpa)."'").",locallavracao_pcpa = ".(!$locallavracao_pcpa ? "NULL" : "'".mysql_real_escape_string($locallavracao_pcpa)."'").",livro_pcpa = ".(!$livro_pcpa ? "NULL" : "'".mysql_real_escape_string($livro_pcpa)."'").",folha_pcpa = ".(!$folha_pcpa ? "NULL" : "'".mysql_real_escape_string($folha_pcpa)."'").",numeroregistro_pcpa = ".(!$numeroregistro_pcpa ? "NULL" : "'".mysql_real_escape_string($numeroregistro_pcpa)."'").",habens_pcpa = ".(!$habens_pcpa ? "NULL" : "'".mysql_real_escape_string($habens_pcpa)."'").",habenscart_pcpa = ".(!$habenscart_pcpa ? "NULL" : "'".mysql_real_escape_string($habenscart_pcpa)."'").",habensloccart_pcpa = ".(!$habensloccart_pcpa ? "NULL" : "'".mysql_real_escape_string($habensloccart_pcpa)."'").",habensdata_pcpa = ".(!$habensdata_pcpa ? "NULL" : "'".mysql_real_escape_string($habensdata_pcpa)."'").",cod_ppst = ".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'")."
			WHERE cod_proponente = '".mysql_real_escape_string($cod_proponente)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_proponente,$cod_ppst) {
		$this->query = "
			DELETE FROM proponenteconjugepacto 
			WHERE cod_proponente = '".mysql_real_escape_string($cod_proponente)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_proponente,$data_pcpa,$locallavracao_pcpa,$livro_pcpa,$folha_pcpa,$numeroregistro_pcpa,$habens_pcpa,$habenscart_pcpa,$habensloccart_pcpa,$habensdata_pcpa,$cod_ppst) {
		$this->query = "
			INSERT INTO proponenteconjugepacto ( cod_proponente,data_pcpa,locallavracao_pcpa,livro_pcpa,folha_pcpa,numeroregistro_pcpa,habens_pcpa,habenscart_pcpa,habensloccart_pcpa,habensdata_pcpa,cod_ppst ) VALUES (
				".(!$cod_proponente ? "NULL" : "'".mysql_real_escape_string($cod_proponente)."'").",".(!$data_pcpa ? "NULL" : "'".mysql_real_escape_string($data_pcpa)."'").",".(!$locallavracao_pcpa ? "NULL" : "'".mysql_real_escape_string($locallavracao_pcpa)."'").",".(!$livro_pcpa ? "NULL" : "'".mysql_real_escape_string($livro_pcpa)."'").",".(!$folha_pcpa ? "NULL" : "'".mysql_real_escape_string($folha_pcpa)."'").",".(!$numeroregistro_pcpa ? "NULL" : "'".mysql_real_escape_string($numeroregistro_pcpa)."'").",".(!$habens_pcpa ? "NULL" : "'".mysql_real_escape_string($habens_pcpa)."'").",".(!$habenscart_pcpa ? "NULL" : "'".mysql_real_escape_string($habenscart_pcpa)."'").",".(!$habensloccart_pcpa ? "NULL" : "'".mysql_real_escape_string($habensloccart_pcpa)."'").",".(!$habensdata_pcpa ? "NULL" : "'".mysql_real_escape_string($habensdata_pcpa)."'").",".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class proponenteprofissao extends database {

	function proponenteprofissao() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_proponente,empresa_pppf,dtadmissao_pppf,enderecoemp_pppf,numeroemp_pppf,complementoemp_pppf,bairro_pppf,cidade_pppf,estado_pppf,telefone_pppf,cargo_pppf,salario_pppf
			FROM proponenteprofissao
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_proponente) {
		$this->query = "
			SELECT cod_proponente,empresa_pppf,dtadmissao_pppf,enderecoemp_pppf,numeroemp_pppf,complementoemp_pppf,bairro_pppf,cidade_pppf,estado_pppf,telefone_pppf,cargo_pppf,salario_pppf
			FROM proponenteprofissao
			WHERE cod_proponente = '".mysql_real_escape_string($cod_proponente)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_proponente,$empresa_pppf,$dtadmissao_pppf,$enderecoemp_pppf,$numeroemp_pppf,$complementoemp_pppf,$bairro_pppf,$cidade_pppf,$estado_pppf,$telefone_pppf,$cargo_pppf,$salario_pppf) {
		$this->query = "
			UPDATE proponenteprofissao SET 
			empresa_pppf = ".(!$empresa_pppf ? "NULL" : "'".mysql_real_escape_string($empresa_pppf)."'").",dtadmissao_pppf = ".(!$dtadmissao_pppf ? "NULL" : "'".mysql_real_escape_string($dtadmissao_pppf)."'").",enderecoemp_pppf = ".(!$enderecoemp_pppf ? "NULL" : "'".mysql_real_escape_string($enderecoemp_pppf)."'").",numeroemp_pppf = ".(!$numeroemp_pppf ? "NULL" : "'".mysql_real_escape_string($numeroemp_pppf)."'").",complementoemp_pppf = ".(!$complementoemp_pppf ? "NULL" : "'".mysql_real_escape_string($complementoemp_pppf)."'").",bairro_pppf = ".(!$bairro_pppf ? "NULL" : "'".mysql_real_escape_string($bairro_pppf)."'").",cidade_pppf = ".(!$cidade_pppf ? "NULL" : "'".mysql_real_escape_string($cidade_pppf)."'").",estado_pppf = ".(!$estado_pppf ? "NULL" : "'".mysql_real_escape_string($estado_pppf)."'").",telefone_pppf = ".(!$telefone_pppf ? "NULL" : "'".mysql_real_escape_string($telefone_pppf)."'").",cargo_pppf = ".(!$cargo_pppf ? "NULL" : "'".mysql_real_escape_string($cargo_pppf)."'").",salario_pppf = ".(!$salario_pppf ? "NULL" : "'".mysql_real_escape_string($salario_pppf)."'")."
			WHERE cod_proponente = '".mysql_real_escape_string($cod_proponente)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_proponente) {
		$this->query = "
			DELETE FROM proponenteprofissao 
			WHERE cod_proponente = '".mysql_real_escape_string($cod_proponente)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_proponente,$empresa_pppf,$dtadmissao_pppf,$enderecoemp_pppf,$numeroemp_pppf,$complementoemp_pppf,$bairro_pppf,$cidade_pppf,$estado_pppf,$telefone_pppf,$cargo_pppf,$salario_pppf) {
		$this->query = "
			INSERT INTO proponenteprofissao ( cod_proponente,empresa_pppf,dtadmissao_pppf,enderecoemp_pppf,numeroemp_pppf,complementoemp_pppf,bairro_pppf,cidade_pppf,estado_pppf,telefone_pppf,cargo_pppf,salario_pppf ) VALUES (
				".(!$cod_proponente ? "NULL" : "'".mysql_real_escape_string($cod_proponente)."'").",".(!$empresa_pppf ? "NULL" : "'".mysql_real_escape_string($empresa_pppf)."'").",".(!$dtadmissao_pppf ? "NULL" : "'".mysql_real_escape_string($dtadmissao_pppf)."'").",".(!$enderecoemp_pppf ? "NULL" : "'".mysql_real_escape_string($enderecoemp_pppf)."'").",".(!$numeroemp_pppf ? "NULL" : "'".mysql_real_escape_string($numeroemp_pppf)."'").",".(!$complementoemp_pppf ? "NULL" : "'".mysql_real_escape_string($complementoemp_pppf)."'").",".(!$bairro_pppf ? "NULL" : "'".mysql_real_escape_string($bairro_pppf)."'").",".(!$cidade_pppf ? "NULL" : "'".mysql_real_escape_string($cidade_pppf)."'").",".(!$estado_pppf ? "NULL" : "'".mysql_real_escape_string($estado_pppf)."'").",".(!$telefone_pppf ? "NULL" : "'".mysql_real_escape_string($telefone_pppf)."'").",".(!$cargo_pppf ? "NULL" : "'".mysql_real_escape_string($cargo_pppf)."'").",".(!$salario_pppf ? "NULL" : "'".mysql_real_escape_string($salario_pppf)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}

class proponentetelefone extends database {

	function proponentetelefone() {
	}

	function getList() {
		$this->query = "
			SELECT COD_PPTL,COD_PPNT,TELEFONE_PPTL,TIPO_PPTL
			FROM proponentetelefone
		";
		$this->query();
		return $this->qrdata;
	}
		

	function getPk($cod_pptl) {
		$this->query = "
			SELECT COD_PPTL,COD_PPNT,TELEFONE_PPTL,TIPO_PPTL
			FROM proponentetelefone
			WHERE COD_PPTL = '".mysql_real_escape_string($cod_pptl)."'
		";
		$this->query();
		return $this->qrdata;
	}
	
	
	function getListaTelefonePpnt($cod_ppnt) {
		$this->query = "
			SELECT COD_PPTL,COD_PPNT,TELEFONE_PPTL,TIPO_PPTL
			FROM proponentetelefone
			WHERE COD_PPNT = '".mysql_real_escape_string($cod_ppnt)."'
		";
		$this->query();
		return $this->qrdata;
	}
			

	function updatePk($cod_pptl,$cod_ppnt,$telefone_pptl,$tipo_pptl) {
		$this->query = "
			UPDATE proponentetelefone SET 
			COD_PPNT=".(!$cod_ppnt ? "NULL" : "'".mysql_real_escape_string($cod_ppnt)."'").",TELEFONE_PPTL=".(!$telefone_pptl ? "NULL" : "'".mysql_real_escape_string($telefone_pptl)."'").",TIPO_PPTL=".(!$tipo_pptl ? "NULL" : "'".mysql_real_escape_string($tipo_pptl)."'")."
			WHERE COD_PPTL = '".mysql_real_escape_string($cod_pptl)."'
		";
		$this->query();
		return true;
	}
			

	function deletePk($cod_pptl) {
		$this->query = "
			DELETE FROM proponentetelefone 
			WHERE COD_PPTL = '".mysql_real_escape_string($cod_pptl)."'
		";
		$this->query();
		return true;
	}
	
	
	function deletePorPpnt($cod_ppnt) {
		$this->query = "
			DELETE FROM proponentetelefone 
			WHERE COD_PPNT = '".mysql_real_escape_string($cod_ppnt)."'
		";
		$this->query();
		return true;
	}
			

	function insert($cod_ppnt,$telefone_pptl,$tipo_pptl) {
		$this->query = "
			INSERT INTO proponentetelefone ( COD_PPNT,TELEFONE_PPTL,TIPO_PPTL ) VALUES (
				".(!$cod_ppnt ? "NULL" : "'".mysql_real_escape_string($cod_ppnt)."'").",".(!$telefone_pptl ? "NULL" : "'".mysql_real_escape_string($telefone_pptl)."'").",".(!$tipo_pptl ? "NULL" : "'".mysql_real_escape_string($tipo_pptl)."'")."
			)
		";
		$this->query();
		if ($this->getErrNo()) {
			return false;
		}
		
		return true;
	}
		
}
					
class proposta extends database {
	
	public $_propostas_por_pagina = 30;
	public $_propostas;
	
	function proposta() {
		
	}


	function atualizarEnvioPrevi($cod_ppst, $enviar_previ)
    {
        $this->query = "
			UPDATE proposta SET enviar_previ =  $enviar_previ
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."';
		";
        $this->query();
    }
	
	function listar() {
		$this->query = "
			SELECT cod_ppst,data_ppst,situacao_ppst,dtapresdoc_ppst,dtiniexigencia_ppst,dtfimexigencia_ppst,dtaprovacao_ppst,dtasscontrato_ppst,dtokregistro_ppst,vlfinsol_ppst,przfinsol_ppst,valordevsinalsol_ppst,pricesac_ppst,valorcompra_ppst,valorfgts_ppst,taxajuros_ppst,valorseguro_ppst,valormanutencao_ppst,vlprestsol_ppst,indcancelamento_ppst,valorboletoaval_ppst,flgboletoavalpago_ppst,dtagend_asscontr_ppst,flgrespostavalor_ppst,dtpagtoboleto_ppst,flgaprovacaoprevi_ppst,dtremessacontrato_ppst,flgpropostaativa_ppst,flgaprovimovel_ppst,tf_ppst
			FROM proposta
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_ppst) {
		$this->query = "
			SELECT enviar_previ, cod_ppst,data_ppst,situacao_ppst,dtapresdoc_ppst,dtiniexigencia_ppst,dtfimexigencia_ppst,dtaprovacao_ppst,dtasscontrato_ppst,dtokregistro_ppst,vlfinsol_ppst,przfinsol_ppst,valordevsinalsol_ppst,pricesac_ppst,valorcompra_ppst,valorfgts_ppst,taxajuros_ppst,valorseguro_ppst,valormanutencao_ppst,vlprestsol_ppst,indcancelamento_ppst,valorboletoaval_ppst,flgformapagto_ppst,flgboletoavalpago_ppst,dtagend_asscontr_ppst,flgrespostavalor_ppst,dtpagtoboleto_ppst,flgaprovacaoprevi_ppst,dtremessacontrato_ppst,flgpropostaativa_ppst,flgaprovimovel_ppst,tf_ppst
			FROM proposta
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		//echo $this->query;
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_ppst,$data_ppst,$situacao_ppst,$dtapresdoc_ppst,$dtiniexigencia_ppst,$dtfimexigencia_ppst,$dtaprovacao_ppst,$dtasscontrato_ppst,$dtokregistro_ppst,$vlfinsol_ppst,$przfinsol_ppst,$valordevsinalsol_ppst,$pricesac_ppst,$valorcompra_ppst,$valorfgts_ppst,$taxajuros_ppst,$valorseguro_ppst,$valormanutencao_ppst,$vlprestsol_ppst,$indcancelamento_ppst,$valorboletoaval_ppst,$flgformapagto_ppst, $flgboletoavalpago_ppst,$dtagend_asscontr_ppst,$flgrespostavalor_ppst,$dtpagtoboleto_ppst,$flgaprovacaoprevi_ppst,$dtremessacontrato_ppst,$flgpropostaativa_ppst) {
		/* query antiga
		$this->query = "
			UPDATE proposta SET 
			data_ppst = ".(!$data_ppst ? "NULL" : "'".mysql_real_escape_string($data_ppst)."'").",situacao_ppst = ".(!$situacao_ppst ? "NULL" : "'".mysql_real_escape_string($situacao_ppst)."'").",dtapresdoc_ppst = ".(!$dtapresdoc_ppst ? "NULL" : "'".mysql_real_escape_string($dtapresdoc_ppst)."'").",dtiniexigencia_ppst = ".(!$dtiniexigencia_ppst ? "NULL" : "'".mysql_real_escape_string($dtiniexigencia_ppst)."'").",dtfimexigencia_ppst = ".(!$dtfimexigencia_ppst ? "NULL" : "'".mysql_real_escape_string($dtfimexigencia_ppst)."'").",dtaprovacao_ppst = ".(!$dtaprovacao_ppst ? "NULL" : "'".mysql_real_escape_string($dtaprovacao_ppst)."'").",dtasscontrato_ppst = ".(!$dtasscontrato_ppst ? "NULL" : "'".mysql_real_escape_string($dtasscontrato_ppst)."'").",dtokregistro_ppst = ".(!$dtokregistro_ppst ? "NULL" : "'".mysql_real_escape_string($dtokregistro_ppst)."'").",vlfinsol_ppst = ".(!$vlfinsol_ppst ? "NULL" : "'".mysql_real_escape_string($vlfinsol_ppst)."'").",przfinsol_ppst = ".(!$przfinsol_ppst ? "NULL" : "'".mysql_real_escape_string($przfinsol_ppst)."'").",valordevsinalsol_ppst = ".(!$valordevsinalsol_ppst ? "NULL" : "'".mysql_real_escape_string($valordevsinalsol_ppst)."'").",pricesac_ppst = ".(!$pricesac_ppst ? "NULL" : "'".mysql_real_escape_string($pricesac_ppst)."'").",valorcompra_ppst = ".(!$valorcompra_ppst ? "NULL" : "'".mysql_real_escape_string($valorcompra_ppst)."'").",valorfgts_ppst = ".(!$valorfgts_ppst ? "NULL" : "'".mysql_real_escape_string($valorfgts_ppst)."'").",taxajuros_ppst = ".(!$taxajuros_ppst ? "NULL" : "'".mysql_real_escape_string($taxajuros_ppst)."'").",valorseguro_ppst = ".(!$valorseguro_ppst ? "NULL" : "'".mysql_real_escape_string($valorseguro_ppst)."'").",valormanutencao_ppst = ".(!$valormanutencao_ppst ? "NULL" : "'".mysql_real_escape_string($valormanutencao_ppst)."'").",vlprestsol_ppst = ".(!$vlprestsol_ppst ? "NULL" : "'".mysql_real_escape_string($vlprestsol_ppst)."'").",indcancelamento_ppst = ".(!$indcancelamento_ppst ? "NULL" : "'".mysql_real_escape_string($indcancelamento_ppst)."'").",valorboletoaval_ppst = ".(!$valorboletoaval_ppst ? "NULL" : "'".mysql_real_escape_string($valorboletoaval_ppst)."'").",flgboletoavalpago_ppst = ".(!$flgboletoavalpago_ppst ? "NULL" : "'".mysql_real_escape_string($flgboletoavalpago_ppst)."'").",dtagend_asscontr_ppst = ".(!$dtagend_asscontr_ppst ? "NULL" : "'".mysql_real_escape_string($dtagend_asscontr_ppst)."'").",flgrespostavalor_ppst = ".(!$flgrespostavalor_ppst ? "NULL" : "'".mysql_real_escape_string($flgrespostavalor_ppst)."'").",dtpagtoboleto_ppst = ".(!$dtpagtoboleto_ppst ? "NULL" : "'".mysql_real_escape_string($dtpagtoboleto_ppst)."'").",flgaprovacaoprevi_ppst = ".(!$flgaprovacaoprevi_ppst ? "NULL" : "'".mysql_real_escape_string($flgaprovacaoprevi_ppst)."'").",dtremessacontrato_ppst = ".(!$dtremessacontrato_ppst ? "NULL" : "'".mysql_real_escape_string($dtremessacontrato_ppst)."'").",flgpropostaativa_ppst = ".(!$flgpropostaativa_ppst ? "NULL" : "'".mysql_real_escape_string($flgpropostaativa_ppst)."'")."
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";*/
			$this->query = "
						UPDATE proposta SET 
						data_ppst = ".(!$data_ppst ? "NULL" : "'".mysql_real_escape_string($data_ppst)."'").",situacao_ppst = ".(!$situacao_ppst ? "NULL" : "'".mysql_real_escape_string($situacao_ppst)."'").",dtapresdoc_ppst = ".(!$dtapresdoc_ppst ? "NULL" : "'".mysql_real_escape_string($dtapresdoc_ppst)."'").",dtiniexigencia_ppst = ".(!$dtiniexigencia_ppst ? "NULL" : "'".mysql_real_escape_string($dtiniexigencia_ppst)."'").",dtfimexigencia_ppst = ".(!$dtfimexigencia_ppst ? "NULL" : "'".mysql_real_escape_string($dtfimexigencia_ppst)."'").",dtaprovacao_ppst = ".(!$dtaprovacao_ppst ? "NULL" : "'".mysql_real_escape_string($dtaprovacao_ppst)."'").",dtasscontrato_ppst = ".(!$dtasscontrato_ppst ? "NULL" : "'".mysql_real_escape_string($dtasscontrato_ppst)."'").",dtokregistro_ppst = ".(!$dtokregistro_ppst ? "NULL" : "'".mysql_real_escape_string($dtokregistro_ppst)."'").",vlfinsol_ppst = ".(!$vlfinsol_ppst ? "NULL" : "'".mysql_real_escape_string($vlfinsol_ppst)."'").",przfinsol_ppst = ".(!$przfinsol_ppst ? "NULL" : "'".mysql_real_escape_string($przfinsol_ppst)."'").",valordevsinalsol_ppst = ".(!$valordevsinalsol_ppst ? "NULL" : "'".mysql_real_escape_string($valordevsinalsol_ppst)."'").",pricesac_ppst = ".(!$pricesac_ppst ? "NULL" : "'".mysql_real_escape_string($pricesac_ppst)."'").",valorcompra_ppst = ".(!$valorcompra_ppst ? "NULL" : "'".mysql_real_escape_string($valorcompra_ppst)."'").",valorfgts_ppst = ".(!$valorfgts_ppst ? "NULL" : "'".mysql_real_escape_string($valorfgts_ppst)."'").",taxajuros_ppst = ".(!$taxajuros_ppst ? "NULL" : "'".mysql_real_escape_string($taxajuros_ppst)."'").",vlprestsol_ppst = ".(!$vlprestsol_ppst ? "NULL" : "'".mysql_real_escape_string($vlprestsol_ppst)."'").",indcancelamento_ppst = ".(!$indcancelamento_ppst ? "NULL" : "'".mysql_real_escape_string($indcancelamento_ppst)."'").",valorboletoaval_ppst = ".(!$valorboletoaval_ppst ? "NULL" : "'".mysql_real_escape_string(floatval(str_replace(',','.',str_replace('.','',$valorboletoaval_ppst))))."'").",flgformapagto_ppst = ".(!$flgformapagto_ppst ? "NULL" : "'".mysql_real_escape_string($flgformapagto_ppst)."'").",flgboletoavalpago_ppst = ".(!$flgboletoavalpago_ppst ? "NULL" : "'".mysql_real_escape_string($flgboletoavalpago_ppst)."'").",dtagend_asscontr_ppst = ".(!$dtagend_asscontr_ppst ? "NULL" : "'".mysql_real_escape_string($dtagend_asscontr_ppst)."'").",flgrespostavalor_ppst = ".(!$flgrespostavalor_ppst ? "NULL" : "'".mysql_real_escape_string($flgrespostavalor_ppst)."'").",dtpagtoboleto_ppst = ".(!$dtpagtoboleto_ppst ? "NULL" : "'".mysql_real_escape_string($dtpagtoboleto_ppst)."'").",flgaprovacaoprevi_ppst = ".(!$flgaprovacaoprevi_ppst ? "NULL" : "'".mysql_real_escape_string($flgaprovacaoprevi_ppst)."'").",dtremessacontrato_ppst = ".(!$dtremessacontrato_ppst ? "NULL" : "'".mysql_real_escape_string($dtremessacontrato_ppst)."'").",flgpropostaativa_ppst = ".(!$flgpropostaativa_ppst ? "NULL" : "'".mysql_real_escape_string($flgpropostaativa_ppst)."'")."
						WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		 $this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_ppst) {
		$this->query = "
			DELETE FROM proposta 
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($data_ppst,$situacao_ppst,$dtapresdoc_ppst,$dtiniexigencia_ppst,$dtfimexigencia_ppst,$dtaprovacao_ppst,$dtasscontrato_ppst,$dtokregistro_ppst,$vlfinsol_ppst,$przfinsol_ppst,$valordevsinalsol_ppst,$pricesac_ppst,$valorcompra_ppst,$valorfgts_ppst,$taxajuros_ppst,$valorseguro_ppst,$valormanutencao_ppst,$vlprestsol_ppst,$indcancelamento_ppst,$valorboletoaval_ppst,$flgboletoavalpago_ppst,$dtagend_asscontr_ppst,$flgrespostavalor_ppst,$dtpagtoboleto_ppst,$flgaprovacaoprevi_ppst,$dtremessacontrato_ppst,$flgpropostaativa_ppst,$flgtf_ppst) {
		$this->query = "
			INSERT INTO proposta ( data_ppst,situacao_ppst,dtapresdoc_ppst,dtiniexigencia_ppst,dtfimexigencia_ppst,dtaprovacao_ppst,dtasscontrato_ppst,dtokregistro_ppst,vlfinsol_ppst,przfinsol_ppst,valordevsinalsol_ppst,pricesac_ppst,valorcompra_ppst,valorfgts_ppst,taxajuros_ppst,valorseguro_ppst,valormanutencao_ppst,vlprestsol_ppst,indcancelamento_ppst,valorboletoaval_ppst,flgboletoavalpago_ppst,dtagend_asscontr_ppst,flgrespostavalor_ppst,dtpagtoboleto_ppst,flgaprovacaoprevi_ppst,dtremessacontrato_ppst,flgpropostaativa_ppst,tf_ppst ) VALUES (
				".(!$data_ppst ? "NULL" : "'".mysql_real_escape_string($data_ppst)."'").",".(!$situacao_ppst ? "NULL" : "'".mysql_real_escape_string($situacao_ppst)."'").",".(!$dtapresdoc_ppst ? "NULL" : "'".mysql_real_escape_string($dtapresdoc_ppst)."'").",".(!$dtiniexigencia_ppst ? "NULL" : "'".mysql_real_escape_string($dtiniexigencia_ppst)."'").",".(!$dtfimexigencia_ppst ? "NULL" : "'".mysql_real_escape_string($dtfimexigencia_ppst)."'").",".(!$dtaprovacao_ppst ? "NULL" : "'".mysql_real_escape_string($dtaprovacao_ppst)."'").",".(!$dtasscontrato_ppst ? "NULL" : "'".mysql_real_escape_string($dtasscontrato_ppst)."'").",".(!$dtokregistro_ppst ? "NULL" : "'".mysql_real_escape_string($dtokregistro_ppst)."'").",".(!$vlfinsol_ppst ? "NULL" : "'".mysql_real_escape_string($vlfinsol_ppst)."'").",".(!$przfinsol_ppst ? "NULL" : "'".mysql_real_escape_string($przfinsol_ppst)."'").",".(!$valordevsinalsol_ppst ? "NULL" : "'".mysql_real_escape_string($valordevsinalsol_ppst)."'").",".(!$pricesac_ppst ? "NULL" : "'".mysql_real_escape_string($pricesac_ppst)."'").",".(!$valorcompra_ppst ? "NULL" : "'".mysql_real_escape_string($valorcompra_ppst)."'").",".(!$valorfgts_ppst ? "NULL" : "'".mysql_real_escape_string($valorfgts_ppst)."'").",".(!$taxajuros_ppst ? "NULL" : "'".mysql_real_escape_string($taxajuros_ppst)."'").",".(!$valorseguro_ppst ? "NULL" : "'".mysql_real_escape_string($valorseguro_ppst)."'").",".(!$valormanutencao_ppst ? "NULL" : "'".mysql_real_escape_string($valormanutencao_ppst)."'").",".(!$vlprestsol_ppst ? "NULL" : "'".mysql_real_escape_string($vlprestsol_ppst)."'").",".(!$indcancelamento_ppst ? "NULL" : "'".mysql_real_escape_string($indcancelamento_ppst)."'").",".(!$valorboletoaval_ppst ? "NULL" : "'".mysql_real_escape_string($valorboletoaval_ppst)."'").",".(!$flgboletoavalpago_ppst ? "NULL" : "'".mysql_real_escape_string($flgboletoavalpago_ppst)."'").",".(!$dtagend_asscontr_ppst ? "NULL" : "'".mysql_real_escape_string($dtagend_asscontr_ppst)."'").",".(!$flgrespostavalor_ppst ? "NULL" : "'".mysql_real_escape_string($flgrespostavalor_ppst)."'").",".(!$dtpagtoboleto_ppst ? "NULL" : "'".mysql_real_escape_string($dtpagtoboleto_ppst)."'").",".(!$flgaprovacaoprevi_ppst ? "NULL" : "'".mysql_real_escape_string($flgaprovacaoprevi_ppst)."'").",".(!$dtremessacontrato_ppst ? "NULL" : "'".mysql_real_escape_string($dtremessacontrato_ppst)."'").",".(!$flgpropostaativa_ppst ? "NULL" : "'".mysql_real_escape_string($flgpropostaativa_ppst)."'").",'".$flgtf_ppst."'"."
			)
		";
		$this->query();
		return $this->qrdata;
	}

	function listarPorStatus($aStatus, $limite = false, $enviar_previ = false) {
		$sComplementoQuery = '';
		global $cLOGIN;
		
		if ($_POST['filtro_nome']) {
			//utiliza a classe usu�rio para pesquisar se tem nome
			//retorna uma array em aUsuarios
			$aUsuarios = usuario::pesquisarPorNome($_POST['filtro_nome']);
			if (is_array($aUsuarios) && @count($aUsuarios) > 0) {
				foreach ($aUsuarios as $aUsua) {
					$listaUsuas[] = $aUsua['cod_usua'];
				}
				//print_r(array_change_key_case($listaUsuas, CASE_UPPER));
				//$listaUsuas � uma array com as chaves da procura, mas o problema parece estar em seu retorno
				$aPpnts = proponente::listarPorUsuarios($listaUsuas);
				if (is_array($aPpnts) && @count($aPpnts) > 0) {
					foreach ($aPpnts as $ppsts) {
						$listappsts[] = $ppsts['cod_ppst'];
					}
					$complementoFiltroNome = " and cod_ppst in ('".implode("','",$listappsts)."') ";
				}
			}
			if (!$complementoFiltroNome) {
				return false;
			}
		}
		if ($_POST['filtro_matricula']) {
			$aUsuarios = usuario::pesquisarPorMatricula($_POST['filtro_matricula']);
			if (is_array($aUsuarios) && @count($aUsuarios) > 0) {
				foreach ($aUsuarios as $aUsua) {
					$listaUsuas[] = $aUsua['cod_usua'];
				}
				$aPpnts = proponente::listarPorUsuarios($listaUsuas);
				if (is_array($aPpnts) && @count($aPpnts) > 0) {
					foreach ($aPpnts as $ppsts) {
						$listappsts[] = $ppsts['cod_ppst'];
					}
					$complementoFiltroMatricula = " and cod_ppst in ('".implode("','",$listappsts)."') ";
				}
			}
			if (!$complementoFiltroMatricula) {
				return false;
			}
		}
		
		if ($_POST['filtro_cpf']) {
			$aPpnts = proponente::pesquisarPorCpf($_POST['filtro_cpf']);
			if (is_array($aPpnts) && @count($aPpnts) > 0) {
				foreach ($aPpnts as $ppsts) {
					$listappsts[] = $ppsts['cod_ppst'];
				}
				$complementoFiltroCpf = " and cod_ppst in ('".implode("','",$listappsts)."') ";
			} else {
				return false;
			}
		}
		
		
				if ($_POST['filtro_vendedor']) {
				$query = "
					SELECT 
						cod_ppst
					FROM vendedor
					WHERE nome_vend like '%".$_POST['filtro_vendedor']."%'";
				$result=mysql_query($query);
				$rows=mysql_num_rows($result);
				$i=1;
				if($rows>0){
				while($reg=mysql_fetch_array($result,MYSQL_ASSOC)){
					$aPpnts = proponente::pesquisarPorProposta($reg['cod_ppst']);
						if (is_array($aPpnts) && @count($aPpnts) > 0) {
							foreach ($aPpnts as $ppsts) {
								$listappsts[] = $ppsts['cod_ppst'];
							}
							$complementoFiltroNome = " and cod_ppst in ('".implode("','",$listappsts)."') ";
						}else{
						return false;
					}
				}
				}else{
					return false;
				}
		}
		
		if ($_POST['filtro_locresp']) {
				$query = "
					SELECT 
						cod_ppst
					FROM proposta
					WHERE locresp_ppst='".$_POST['filtro_locresp']."'";
				$result=mysql_query($query);
				$rows=mysql_num_rows($result);
				$i=1;
				if($rows>0){
				while($reg=mysql_fetch_array($result,MYSQL_ASSOC)){
					$aPpnts = proponente::pesquisarPorProposta($reg['cod_ppst']);
						if (is_array($aPpnts) && @count($aPpnts) > 0) {
							foreach ($aPpnts as $ppsts) {
								$listappsts[] = $ppsts['cod_ppst'];
							}
							$complementoFiltroNome = " and cod_ppst in ('".implode("','",$listappsts)."') ";
						}else{
						return false;
					}
				}
				}else{
					return false;
				}
		}

		
		if ($cLOGIN->iLEVEL_USUA == TPUSER_DESPACHANTE) {
			$aImovel = imovel::pesquisarPorDespachante($cLOGIN->iID);
			$aVendedor = vendedor::pesquisarPorDespachante($cLOGIN->iID);
			
			if (is_array($aImovel) && @count($aImovel) > 0) {
				foreach ($aImovel as $imovel) {
					$listaPropostasDesp[] = $imovel['cod_ppst'];
				}
			}
			if (is_array($aVendedor) && @count($aVendedor) > 0) {
				foreach ($aVendedor as $vendedor) {
					$listaPropostasDesp[] = $vendedor['cod_ppst'];
				}
			}
			if (is_array($listaPropostasDesp) && @count($listaPropostasDesp) > 0) {
				$complementoFiltroDespachante = " and cod_ppst in ('".implode("','",$listaPropostasDesp)."') ";
			} else {
				return false;
			}
		}

		if($_GET['pagina'])		
			//$x = $_GET['pagina'] * $this->_propostas_por_pagina;
			$x = ($_GET['pagina'] - 1) * $this->_propostas_por_pagina; 
			
		else
			$x = 0;

        $enviar = ($enviar_previ == true) ? " enviar_previ=1 and" : "";
		$this->query = "
			SELECT 
				cod_ppst,data_ppst,situacao_ppst,dtapresdoc_ppst,dtiniexigencia_ppst,dtfimexigencia_ppst,dtaprovacao_ppst,dtasscontrato_ppst,dtokregistro_ppst,vlfinsol_ppst,przfinsol_ppst,valordevsinalsol_ppst,pricesac_ppst,valorcompra_ppst,valorfgts_ppst,taxajuros_ppst,valorseguro_ppst,valormanutencao_ppst,vlprestsol_ppst,indcancelamento_ppst,valorboletoaval_ppst,flgboletoavalpago_ppst,dtagend_asscontr_ppst,flgrespostavalor_ppst,dtpagtoboleto_ppst,flgaprovacaoprevi_ppst,dtremessacontrato_ppst,flgpropostaativa_ppst,flgaprovimovel_ppst,tf_ppst
			FROM proposta
			WHERE
			$enviar
			situacao_ppst IN ('".implode("','",$aStatus)."') ".$complementoFiltroNome." ".$complementoFiltroMatricula." ".$complementoFiltroCpf." ".$complementoFiltroDespachante. "
		";
		

		$this->query();
		$this->_propostas = $this->qrcount;
		if($limite)
			$this->query .= "LIMIT $x,$this->_propostas_por_pagina";
		$this->query();
		
		
		return $this->qrdata;
	}
	
	function geraBarraProposta() {
		
		if($_GET['pagina'])
			$pagina_atual = $_GET['pagina'];
		else
			$pagina_atual = 1;
		
		//pega o n�mero de p�ginas
		$paginas = $this->getPaginasProposta();
		$ant_pagina = $pagina_atual - 1;
		$prox_pagina = $pagina_atual + 1;

		if($ant_pagina > 0)
			echo "<a href='" . $_SERVER['PHP_SELF'] ."?pagina=" . $ant_pagina . "'> << </a> ";	
		for($i=1; $i<=$paginas; $i++){
			
			if($i != $pagina_atual)
				echo "<a href='" . $_SERVER['PHP_SELF'] ."?pagina=" . $i . "'>" . $i . "</a> "; 
			else
				echo " $i "; 			
			
		}
		if($prox_pagina <= $paginas)
			echo "<a href='" . $_SERVER['PHP_SELF'] ."?pagina=" . $prox_pagina . "'> >> </a> ";
		
	}
	
	function getPaginasProposta() {
		
		global $cLOGIN,$aPROPOSTALISTA;
		$aParams = $aPROPOSTALISTA[$cLOGIN->iLEVEL_USUA];
		$this->query = "
			SELECT 
				cod_ppst,data_ppst,situacao_ppst,dtapresdoc_ppst,dtiniexigencia_ppst,dtfimexigencia_ppst,dtaprovacao_ppst,dtasscontrato_ppst,dtokregistro_ppst,vlfinsol_ppst,przfinsol_ppst,valordevsinalsol_ppst,pricesac_ppst,valorcompra_ppst,valorfgts_ppst,taxajuros_ppst,valorseguro_ppst,valormanutencao_ppst,vlprestsol_ppst,indcancelamento_ppst,valorboletoaval_ppst,flgboletoavalpago_ppst,dtagend_asscontr_ppst,flgrespostavalor_ppst,dtpagtoboleto_ppst,flgaprovacaoprevi_ppst,dtremessacontrato_ppst,flgpropostaativa_ppst,flgaprovimovel_ppst,tf_ppst
			FROM proposta
			WHERE situacao_ppst IN ('".implode("','",$aParams)."') ";
		$this->query();
		
		$paginas = $this->qrcount / $this->_propostas_por_pagina ;
		$paginas = ceil($paginas);
		
		return $paginas;
		
	}

	function getListaProposta($ctAll = false, $limite = false, $enviar_previ = false) {

		/* @var $cLOGIN login */
		global $cLOGIN,$aPROPOSTALISTA;
		if ($ctAll) {
			$aParams = $aPROPOSTALISTA[99];
		} else {
			$aParams = $aPROPOSTALISTA[$cLOGIN->iLEVEL_USUA];
		}
		
		$aDadosProposta = false;
		if ($aParams) {
			$aDadosProposta = $this->listarPorStatus($aParams, $limite, $enviar_previ);
            
			if (is_array($aDadosProposta) && @count($aDadosProposta) > 0) {
				foreach ($aDadosProposta as $idxProposta => $aProposta) {
					$aDadosProposta[$idxProposta]['proponentes'] = proponente::pesquisarPorProposta($aProposta['cod_ppst']);
					if (is_array($aDadosProposta[$idxProposta]['proponentes']) && @count($aDadosProposta[$idxProposta]['proponentes']) > 0) {
						foreach ($aDadosProposta[$idxProposta]['proponentes'] as $idxProponente => $aProponente) { 
							$aDadosProposta[$idxProposta]['proponentes'][$idxProponente]['usuario'] = usuario::pesquisarPk($aProponente['cod_proponente']);
						}
					}
					
				}
			}
		}
		
		return $aDadosProposta;
	}
	
	function incluirProposta($aDados) {
		$cod_ppst=0;
		$retorno = true;
		$bHasCondominio = false;
		if (FLG_PREVI == true) {
			if ($aDados['cad_condom'] == 'S' && (int)$aDados['cad_qtde_ppnt'] > 0) {
				for ($i=1;$i<=(int)$aDados['cad_qtde_ppnt'];$i++) {
					$aMatriculas[$i] = str_pad(utils::limpaNumeros($aDados['matricula_ppnt_'.$i]),9,"0",STR_PAD_LEFT);
				}
				$bHasCondominio = true;
			}
			$aMatriculas[0] = str_pad(utils::limpaNumeros($aDados['cad_matricula']),9,"0",STR_PAD_LEFT);
			foreach ($aMatriculas as $sMatricula) {
				$aDadosMatricula[$sMatricula] = listadenomes::pesquisarPk($sMatricula);
				if ($aDadosMatricula[$sMatricula]) {
					if ($aDadosMatricula[$sMatricula][0]['status_pp']!='I'){
						if($aDadosMatricula[$sMatricula][0]['status_pp']!='E'){
							$aDadosUsuario = usuario::pesquisarPorMatricula($sMatricula);
							if ($aDadosUsuario) {
								$aDadosProponentes = proponente::pesquisarPorUsuario($aDadosUsuario[0]['cod_usua']);
								if (@count($aDadosProponentes) > 0) {
									foreach ($aDadosProponentes as $aProponente) {
										if ($aProponente['cod_ppst']) {
											$aDadosProposta = proposta::pesquisarPk($aProponente['cod_ppst']);
											if (@count($aDadosProposta) > 0) {
												foreach ($aDadosProposta as $aProposta) {
													if ($aProposta['flgpropostaativa_ppst'] == '1') {
														$retorno = "C�digo Identifica��o ".$sMatricula." possui proposta ativa.<br />";
													}
												}
											}
										}
									}
								}
							}
						}else{
						$retorno = "C�digo Identifica��o ".$sMatricula." Expirado.<br />";
						}
					}else{
						$retorno = "Participante ".$sMatricula." Impedido.<br />";
					}
				} else {
					$retorno = "C�digo Identifica��o ".$sMatricula." Inv�lido.<br />";
				}
			}
			
			if ($retorno === true) {
				$this->beginTransaction();
				$this->inserir(date("Y-m-d H:i:s"),"2","","","","","","","","","","","","","","","","","","","","","","","","","1",$aDados["tf_ppst"]);
				$cod_ppst = $this->insertId;
				if ($cod_ppst) {
					$dir=$cod_ppst;
					@mkdir ("imagens_previ/".$dir, 0777);
					$sTmpPassword = substr(md5(mt_rand(0,mktime())),0,8);
					$sSenhaUsuario = md5($sTmpPassword);
					foreach ($aMatriculas as $idxMatricula => $sMatricula) {
						if ($idxMatricula == 0) {
							$sNomeUsuario = $aDados['cad_nome'];
							$sEmailUsuario = $aDados['cad_email'];
						} else {
							$sNomeUsuario = $aDados['nome_ppnt_'.$idxMatricula];
							$sEmailUsuario = $aDados['email_ppnt_'.$idxMatricula];
						}
						
						$aDadosUsuario = usuario::pesquisarPorMatricula($sMatricula);
						if (@count($aDadosUsuario) == 0) {
							usuario::inserir($sNomeUsuario,$sEmailUsuario,$sSenhaUsuario,'1',$sMatricula,'1','0','','');
							$cod_usua = $this->insertId;
						} else {
							$cod_usua = $aDadosUsuario[0]['cod_usua'];
						}
						
						if (!$cod_usua) {
							if ($this->getErrNo() == DB_ERR_UNIQUE) {
								$retorno = 'Endere�o de e-mail '.$sEmailUsuario.' em uso por outro usu�rio.';
							} else {
								$retorno = 'Erro com dados de usu�rios.';
							}
						} else {
							proponente::inserir('','','','','','','','','','','','',$cod_usua,'','','','','','',$cod_ppst,'','','','','','','','','','','','','','','','','');
							if ($this->errno != "0") {
								$retorno = 'Erro com dados do proponente.';
							} else {
								devsol::inserir($cod_ppst,'','','','','','','','','','','','','','',$cod_usua);
							}
						}
					}
					if ($retorno === true) {
						imovel::inserir($cod_ppst,'','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','',($bHasCondominio ? 'S' : 'N'));
						vendedor::inserir($cod_ppst,'','','','','','','','','','','','','','','','','','','','','','100','','');
					}
				} else {
					$retorno = 'Erro ao incluir a proposta.';
				}
				
				if ($retorno !== true) {
					$this->rollbackTransaction();
				}
				
				////// COMENTAR ESTA LINHA
				//$this->rollbackTransaction();
				$this->commitTransaction();
			}
		} else {
			$aDadosUsuario = usuario::pesquisarPorEmail($aDados['cad_email']);
			if ($aDadosUsuario) {
				$aDadosProponentes = proponente::pesquisarPorUsuario($aDadosUsuario[0]['cod_usua']);
				if (@count($aDadosProponentes) > 0) {
					foreach ($aDadosProponentes as $aProponente) {
						if ($aProponente['cod_ppst']) {
							$aDadosProposta = proposta::pesquisarPk($aProponente['cod_ppst']);
							if (@count($aDadosProposta) > 0) {
								foreach ($aDadosProposta as $aProposta) {
									if ($aProposta['flgpropostaativa_ppst'] == '1') {
										$retorno .= "Matr�cula ".$sMatricula." possui proposta ativa.<br />";
									}
								}
							}
						}
					}
				}
			}
		}
		$dadoretorno[0]=$retorno;
		$dadoretorno[1]=$cod_ppst;
		$dadoretorno[2]=$sTmpPassword;
		return $dadoretorno;
	}
	
	function getPropostaDuplicidadeRGCPFCNPJ($aLista,$sProcura,$tipo = "CPF") {
		if (array_search($sProcura,$aLista) !== false) {
			if ($tipo == "CPF") {
				return "CPF ".utils::formataCPF($sProcura)." est� em uso nesta proposta.";
			} elseif ($tipo == "RG") {
				return "RG ".utils::formataRG($sProcura)." est� em uso nesta proposta.";
			} elseif ($tipo == "CNPJ") {
				return "CNPJ ".utils::formataCnpj($sProcura)." est� em uso nesta proposta.";
			}
		}
		return true;
	}

	function getProposta($cod_ppst) {
		$aTmpProposta = proposta::pesquisarPk($cod_ppst);
		
		$aRetorno = $aTmpProposta[0];
		
		$aListaCpf  = array();
		$aListaRg   = array();
		$aListaCnpj = array();
		
		if ($aRetorno['situacao_ppst'] == "2") {
			$this->setPropostaStatus($cod_ppst,"3");
			$aRetorno['situacao_ppst'] = '3';
		}

		$aTmpImovel = imovel::pesquisarPk($cod_ppst);
		$aRetorno['imovel'] = $aTmpImovel[0];
		
		$aRetorno['imovel']['logradouro'] = logradouro::pesquisarPk($aRetorno['imovel']['cod_logr']);
		$aRetorno['imovel']['bairro'] = bairro::pesquisarPk($aRetorno['imovel']['cod_bairro']);
		$aRetorno['imovel']['uf'] = uf::pesquisarPk($aRetorno['imovel']['cod_uf']);
		$aRetorno['imovel']['municipio'] = municipio::pesquisarPk($aRetorno['imovel']['cod_uf'],$aRetorno['imovel']['cod_municipio']);
		
		$aRetorno['imovel']['despachante'] = despachante::pesquisarPk($aRetorno['imovel']['despachante_imov']);
		$aRetorno['imovel']['checklist'] = municipiodocumento::pesquisarPorUfMunicipio($aRetorno['imovel']['cod_uf'],$aRetorno['imovel']['cod_municipio'],'imovel');
		
		if (is_array($aRetorno['imovel']['checklist']) && @count($aRetorno['imovel']['checklist']) > 0) {
			foreach ($aRetorno['imovel']['checklist'] as $idxChecklistImovel => $checklistImovel) {
				$tmpDadosCheckList = clistimovel::pesquisarPk($checklistImovel['cod_mndc'],$cod_ppst);
				$aRetorno['imovel']['checklist'][$idxChecklistImovel]['dados'] = $tmpDadosCheckList[0];
				if ($aRetorno['imovel']['checklist'][$idxChecklistImovel]['dados']['flgatendente_clim'] != 'S') {
					$travaClistImovel = true;
				}
			}
		}

		$aRetorno['imovelvagas'] = imovelvaga::pesquisarPorProposta($cod_ppst);
		$aRetorno['proponentes'] = proponente::pesquisarPorProposta($cod_ppst);
		if (is_array($aRetorno['proponentes']) && @count($aRetorno['proponentes']) > 0) {
			foreach ($aRetorno['proponentes'] as $idxProponente => $aProponente) {
				
				if ($aRetorno['proponentes'][$idxProponente]['cpf_ppnt'] != '') {
					if (!proposta::checkPropostaDuplicidadeCPFProponente($cod_ppst,$aRetorno['proponentes'][$idxProponente]['cpf_ppnt'])) {
						$aRetorno['proponentes'][$idxProponente]['erroValidacao'] = "CPF ".utils::formataCPF($aRetorno['proponentes'][$idxProponente]['cpf_ppnt'])." est� em uso por outra proposta ativa.";
					} else {
						$mDuplicidade = true;//proposta::getPropostaDuplicidadeRGCPFCNPJ($aListaCpf,$aRetorno['proponentes'][$idxProponente]['cpf_ppnt']);
						if ($mDuplicidade === true) {
							$aListaCpf[] = $aRetorno['proponentes'][$idxProponente]['cpf_ppnt'];
						} else {
							$aRetorno['proponentes'][$idxProponente]['erroValidacao'] = $mDuplicidade;
						}
					}
				}

				$aRetorno['proponentes'][$idxProponente]['logradouro'] = logradouro::pesquisarPk($aRetorno['proponentes'][$idxProponente]['cod_logr']);
				$aRetorno['proponentes'][$idxProponente]['bairro'] = bairro::pesquisarPk($aRetorno['proponentes'][$idxProponente]['cod_bairro']);
				$aRetorno['proponentes'][$idxProponente]['uf'] = uf::pesquisarPk($aRetorno['proponentes'][$idxProponente]['cod_uf']);
				$aRetorno['proponentes'][$idxProponente]['municipio'] = municipio::pesquisarPk($aRetorno['proponentes'][$idxProponente]['cod_uf'],$aRetorno['proponentes'][$idxProponente]['cod_municipio']);
				$aRetorno['proponentes'][$idxProponente]['estadocivil'] = estadocivil::pesquisarPk($aRetorno['proponentes'][$idxProponente]['cod_estciv']);
				$aRetorno['proponentes'][$idxProponente]['usuario'] = usuario::pesquisarPk($aRetorno['proponentes'][$idxProponente]['cod_proponente']);
				$aRetorno['proponentes'][$idxProponente]['conjuge'] = proponenteconjuge::pesquisarPk($aRetorno['proponentes'][$idxProponente]['cod_proponente'],$cod_ppst);
				$aRetorno['proponentes'][$idxProponente]['conjugepacto'] = proponenteconjugepacto::pesquisarPk($aRetorno['proponentes'][$idxProponente]['cod_proponente'],$cod_ppst);
				$aRetorno['proponentes'][$idxProponente]['profissao'] = proponenteprofissao::pesquisarPk($aRetorno['proponentes'][$idxProponente]['cod_proponente']);
				$aRetorno['proponentes'][$idxProponente]['listadenomes'] = listadenomes::pesquisarPk($aRetorno['proponentes'][$idxProponente]['usuario'][0]['id_lstn']);
				$aRetorno['proponentes'][$idxProponente]['telefones'] = proponentetelefone::getListaTelefonePpnt($aRetorno['proponentes'][$idxProponente]['cod_proponente']);
				
				$aRetorno['proponentes'][$idxProponente]['conjuge'][0]['pais'] = pais::pesquisarPk($aRetorno['proponentes'][$idxProponente]['conjuge'][0]['cod_pais']);
				
				if ($aRetorno['proponentes'][$idxProponente]['conjuge'][0]['cpf_pccj'] != '') {
				  $aListaCpf_semPccj = $aListaCpf;
					$mDuplicidade = true;//proposta::getPropostaDuplicidadeRGCPFCNPJ($aListaCpf,$aRetorno['proponentes'][$idxProponente]['conjuge'][0]['cpf_pccj']);
					if ($mDuplicidade === true) {
						$aListaCpf[] = $aRetorno['proponentes'][$idxProponente]['conjuge'][0]['cpf_pccj'];
					} else {
						$aRetorno['proponentes'][$idxProponente]['conjuge'][0]['erroValidacao'] = $mDuplicidade;
					}
				}
				if ($aRetorno['proponentes'][$idxProponente]['conjuge'][0]['nrrg_pccj'] != '') {
					$aListaRg_semPccj = $aListaRg;
				  $mDuplicidade = true; //proposta::getPropostaDuplicidadeRGCPFCNPJ($aListaRg,$aRetorno['proponentes'][$idxProponente]['conjuge'][0]['nrrg_pccj'],"RG");
					if ($mDuplicidade === true) {
						$aListaRg[] = $aRetorno['proponentes'][$idxProponente]['conjuge'][0]['nrrg_pccj'];
					} else {
						$aRetorno['proponentes'][$idxProponente]['conjuge'][0]['erroValidacao'] = $mDuplicidade;
					}
				}
				
				
				if ($aRetorno['proponentes'][$idxProponente]['flgdevsol_ppnt'] == 'S') {
					$aRetorno['proponentes'][$idxProponente]['devsol'] = devsol::pesquisarPk($cod_ppst,$aRetorno['proponentes'][$idxProponente]['cod_proponente']);
					$aRetorno['proponentes'][$idxProponente]['devsol'][0]['logradouro'] = logradouro::pesquisarPk($aRetorno['proponentes'][$idxProponente]['devsol'][0]['cod_logr']);
					$aRetorno['proponentes'][$idxProponente]['devsol'][0]['bairro'] = bairro::pesquisarPk($aRetorno['proponentes'][$idxProponente]['devsol'][0]['cod_bairro']);
					$aRetorno['proponentes'][$idxProponente]['devsol'][0]['uf'] = uf::pesquisarPk($aRetorno['proponentes'][$idxProponente]['devsol'][0]['cod_uf']);
					$aRetorno['proponentes'][$idxProponente]['devsol'][0]['municipio'] = municipio::pesquisarPk($aRetorno['proponentes'][$idxProponente]['devsol'][0]['cod_uf'],$aRetorno['proponentes'][$idxProponente]['devsol'][0]['cod_municipio']);
					$aRetorno['proponentes'][$idxProponente]['devsol'][0]['pais'] = pais::pesquisarPk($aRetorno['proponentes'][$idxProponente]['devsol'][0]['cod_pais']);
					
					if ($aRetorno['proponentes'][$idxProponente]['devsol'][0]['cpf_devsol'] != '') {
						$mDuplicidade = true; //proposta::getPropostaDuplicidadeRGCPFCNPJ($aListaCpf_semPccj,$aRetorno['proponentes'][$idxProponente]['devsol'][0]['cpf_devsol']);
						if ($mDuplicidade === true) {
							$aListaCpf[] = $aRetorno['proponentes'][$idxProponente]['devsol'][0]['cpf_devsol'];
						} else {
							$aRetorno['proponentes'][$idxProponente]['devsol'][0]['erroValidacao'] = $mDuplicidade;
						}
					}
				} else {
					$aRetorno['proponentes'][$idxProponente]['devsol'] = "";
				}
				
				$aRetorno['proponentes'][$idxProponente]['checklist'] = municipiodocumento::pesquisarPorUfMunicipio($aRetorno['proponentes'][$idxProponente]['cod_uf'],$aRetorno['proponentes'][$idxProponente]['cod_municipio'],'proponente');
				$aRetorno['proponentes'][$idxProponente]['checklistconjuge'] = municipiodocumento::pesquisarPorUfMunicipio($aRetorno['proponentes'][$idxProponente]['cod_uf'],$aRetorno['proponentes'][$idxProponente]['cod_municipio'],'proponenteconjuge');
				$aRetorno['proponentes'][$idxProponente]['checklistfgts']=  municipiodocumento::pesquisarPorUfMunicipio($aRetorno['proponentes'][$idxProponente]['cod_uf'],$aRetorno['proponentes'][$idxProponente]['cod_municipio'],'fgts');
				if (is_array($aRetorno['proponentes'][$idxProponente]['checklist']) && @count($aRetorno['proponentes'][$idxProponente]['checklist']) > 0) {
					foreach ($aRetorno['proponentes'][$idxProponente]['checklist'] as $idxChecklistProponente => $checklistProponente) {
						$tmpDadosCheckList = clistproponente::pesquisarPk($checklistProponente['cod_mndc'],$cod_ppst,$aRetorno['proponentes'][$idxProponente]['cod_proponente']);
						$aRetorno['proponentes'][$idxProponente]['checklist'][$idxChecklistProponente]['dados'] = $tmpDadosCheckList[0];
						if ($aRetorno['proponentes'][$idxProponente]['checklist'][$idxChecklistProponente]['dados']['flgatendente_clpn'] != 'S') {
							$travaClistProponente = true;
							$aRetorno['proponentes'][$idxProponente]['checklist']['travachecklist'] = 'S';
						}
					}
				}

				if (is_array($aRetorno['proponentes'][$idxProponente]['checklistconjuge']) && @count($aRetorno['proponentes'][$idxProponente]['checklistconjuge']) > 0) {
					foreach ($aRetorno['proponentes'][$idxProponente]['checklistconjuge'] as $idxChecklistProponenteConjuge => $checklistProponenteConjuge) {
						$tmpDadosCheckList = clistproponenteconjuge::pesquisarPk($checklistProponenteConjuge['cod_mndc'],$cod_ppst,$aRetorno['proponentes'][$idxProponente]['cod_proponente']);
						$aRetorno['proponentes'][$idxProponente]['checklistconjuge'][$idxChecklistProponenteConjuge]['dados'] = $tmpDadosCheckList[0];
						if ($aRetorno['proponentes'][$idxProponente]['checklistconjuge'][$idxChecklistProponenteConjuge]['dados']['flgatendente_clpc'] != 'S') {
							$travaClistProponenteConjuge = true;
							$aRetorno['proponentes'][$idxProponente]['checklistconjuge']['travachecklist'] = 'S';
						}
					}
				}
				
				if (is_array($aRetorno['proponentes'][$idxProponente]['checklistfgts']) && @count($aRetorno['proponentes'][$idxProponente]['checklistfgts']) > 0) {
					foreach ($aRetorno['proponentes'][$idxProponente]['checklistfgts'] as $idxChecklistProponenteFgts => $checklistProponenteFgts) {
						$tmpDadosCheckList = clistproponentefgts::pesquisarPk($checklistProponenteFgts['cod_mndc'],$cod_ppst,$aRetorno['proponentes'][$idxProponente]['cod_proponente']);
						$aRetorno['proponentes'][$idxProponente]['checklistfgts'][$idxChecklistProponenteFgts]['dados'] = $tmpDadosCheckList[0];
						if ($aRetorno['proponentes'][$idxProponente]['checklistfgts'][$idxChecklistProponenteFgts]['dados']['flgatendente_clfg'] != 'S') {
							$travaClistProponenteFgts = true;
							$aRetorno['proponentes'][$idxProponente]['checklistfgts']['travachecklist'] = 'S';
						}
					}
				}				
			}
		}
		$aRetorno['vendedores'] = vendedor::pesquisarPorProposta($cod_ppst);
		
		if (is_array($aRetorno['vendedores']) && @count($aRetorno['vendedores']) > 0) {
			foreach ($aRetorno['vendedores'] as $idxVendedor => $aVendedor) {
				
				$aRetorno['vendedores'][$idxVendedor]['logradouro'] = logradouro::pesquisarPk($aRetorno['vendedores'][$idxVendedor]['cod_logr']);
				$aRetorno['vendedores'][$idxVendedor]['bairro'] = bairro::pesquisarPk($aRetorno['vendedores'][$idxVendedor]['cod_bairro']);
				$aRetorno['vendedores'][$idxVendedor]['uf'] = uf::pesquisarPk($aRetorno['vendedores'][$idxVendedor]['cod_uf']);
				$aRetorno['vendedores'][$idxVendedor]['municipio'] = municipio::pesquisarPk($aRetorno['vendedores'][$idxVendedor]['cod_uf'],$aRetorno['vendedores'][$idxVendedor]['cod_municipio']);
				$aRetorno['vendedores'][$idxVendedor]['telefones'] = vendtelefone::getListaTelefoneVend($aVendedor['cod_vend']);
				
				// Tipos: 1 = Fis; 2 = Jur
				if ($aVendedor['tipo_vend'] == "1") {
					$aRetorno['vendedores'][$idxVendedor]['vendfis'] = vendfis::pesquisarPk($cod_ppst,$aVendedor['cod_vend']);
					$aRetorno['vendedores'][$idxVendedor]['vendfis'][0]['checklist'] = municipiodocumento::pesquisarPorUfMunicipio($aRetorno['vendedores'][$idxVendedor]['cod_uf'],$aRetorno['vendedores'][$idxVendedor]['cod_municipio'],'vendfis');
					
					if ($aRetorno['vendedores'][$idxVendedor]['vendfis'][0]['cpf_vfisica'] != '') {
						$mDuplicidade = proposta::getPropostaDuplicidadeRGCPFCNPJ($aListaCpf,$aRetorno['vendedores'][$idxVendedor]['vendfis'][0]['cpf_vfisica']);
						if ($mDuplicidade === true) {
							$aListaCpf[] = $aRetorno['vendedores'][$idxVendedor]['vendfis'][0]['cpf_vfisica'];
						} else {
							$aRetorno['vendedores'][$idxVendedor]['vendfis'][0]['erroValidacao'] = $mDuplicidade;
						}
					}
					if ($aRetorno['vendedores'][$idxVendedor]['vendfis'][0]['nrrg_vfisica'] != '') {
						$mDuplicidade = proposta::getPropostaDuplicidadeRGCPFCNPJ($aListaRg,$aRetorno['vendedores'][$idxVendedor]['vendfis'][0]['nrrg_vfisica'],"RG");
						if ($mDuplicidade === true) {
							$aListaRg[] = $aRetorno['vendedores'][$idxVendedor]['vendfis'][0]['nrrg_vfisica'];
						} else {
							$aRetorno['vendedores'][$idxVendedor]['vendfis'][0]['erroValidacao'] = $mDuplicidade;
						}
					}

					
					if (is_array($aRetorno['vendedores'][$idxVendedor]['vendfis'][0]['checklist']) && @count($aRetorno['vendedores'][$idxVendedor]['vendfis'][0]['checklist']) > 0) {
						foreach ($aRetorno['vendedores'][$idxVendedor]['vendfis'][0]['checklist'] as $idxChecklistVendfis => $checklistVendfis) {
							$tmpDadosCheckList = clistvendfis::pesquisarPk($checklistVendfis['cod_mndc'],$cod_ppst,$aVendedor['cod_vend']);
							$aRetorno['vendedores'][$idxVendedor]['vendfis'][0]['checklist'][$idxChecklistVendfis]['dados'] = $tmpDadosCheckList[0];
							if ($aRetorno['vendedores'][$idxVendedor]['vendfis'][0]['checklist'][$idxChecklistVendfis]['dados']['flgatendente_clvf'] != 'S') {
								$travaClistVendfis = true;
								$aRetorno['vendedores'][$idxVendedor]['vendfis'][0]['checklist']['travachecklist'] = 'S';
							}
						}
					}
					$aRetorno['vendedores'][$idxVendedor]['vendfisconjuge'] = vendfisconjuge::pesquisarPk($aVendedor['cod_vend'],$cod_ppst);
					
					if ($aRetorno['vendedores'][$idxVendedor]['vendfisconjuge'][0]['cpf_pccj'] != '') {
						$mDuplicidade = proposta::getPropostaDuplicidadeRGCPFCNPJ($aListaCpf,$aRetorno['vendedores'][$idxVendedor]['vendfisconjuge'][0]['cpf_pccj']);
						if ($mDuplicidade === true) {
							$aListaCpf[] = $aRetorno['vendedores'][$idxVendedor]['vendfisconjuge'][0]['cpf_pccj'];
						} else {
							$aRetorno['vendedores'][$idxVendedor]['vendfisconjuge'][0]['erroValidacao'] = $mDuplicidade;
						}
					}
					if ($aRetorno['vendedores'][$idxVendedor]['vendfisconjuge'][0]['nrrg_vfcj'] != '') {
						$mDuplicidade = proposta::getPropostaDuplicidadeRGCPFCNPJ($aListaRg,$aRetorno['vendedores'][$idxVendedor]['vendfisconjuge'][0]['nrrg_vfcj'],"RG");
						if ($mDuplicidade === true) {
							$aListaRg[] = $aRetorno['vendedores'][$idxVendedor]['vendfisconjuge'][0]['nrrg_vfcj'];
						} else {
							$aRetorno['vendedores'][$idxVendedor]['vendfisconjuge'][0]['erroValidacao'] = $mDuplicidade;
						}
					}

					
					$aRetorno['vendedores'][$idxVendedor]['vendfisconjugepacto'] = vendfisconjugepacto::pesquisarPk($aVendedor['cod_vend'],$cod_ppst);
					if ($aRetorno['vendedores'][$idxVendedor]['vendfisconjuge']) {
						$aRetorno['vendedores'][$idxVendedor]['vendfisconjuge'][0]['pais'] = pais::pesquisarPk($aRetorno['vendedores'][$idxVendedor]['vendfisconjuge'][0]['cod_pais']);
						
						$aRetorno['vendedores'][$idxVendedor]['vendfisconjuge'][0]['checklist'] = municipiodocumento::pesquisarPorUfMunicipio($aRetorno['vendedores'][$idxVendedor]['cod_uf'],$aRetorno['vendedores'][$idxVendedor]['cod_municipio'],'vendfisconjuge');
						if (is_array($aRetorno['vendedores'][$idxVendedor]['vendfisconjuge'][0]['checklist']) && @count($aRetorno['vendedores'][$idxVendedor]['vendfisconjuge'][0]['checklist']) > 0) {
							foreach ($aRetorno['vendedores'][$idxVendedor]['vendfisconjuge'][0]['checklist'] as $idxChecklistVendfisConjuge => $checklistVendfisConjuge) {
								$tmpDadosCheckList = clistvendfisconjuge::pesquisarPk($checklistVendfisConjuge['cod_mndc'],$cod_ppst,$aVendedor['cod_vend']);
								$aRetorno['vendedores'][$idxVendedor]['vendfisconjuge'][0]['checklist'][$idxChecklistVendfisConjuge]['dados'] = $tmpDadosCheckList[0];
								if ($aRetorno['vendedores'][$idxVendedor]['vendfisconjuge'][0]['checklist'][$idxChecklistVendfisConjuge]['dados']['flgatendente_clvc'] != 'S') {
									$travaClistVendfisConjuge = true;
									$aRetorno['vendedores'][$idxVendedor]['vendfisconjuge'][0]['checklist']['travachecklist'] = 'S';
								}
							}
						}
						if ($idxVendedor == "0") {
							$aRetorno['vendedores'][$idxVendedor]['vendfisconjuge'][0]['despachante'] = despachante::pesquisarPk($aRetorno['vendedores'][$idxVendedor]['vendfisconjuge'][0]['despachante_vfcj']);
						}
					}
					if ($idxVendedor == "0") {
						$aRetorno['vendedores'][$idxVendedor]['vendfis'][0]['despachante'] = despachante::pesquisarPk($aRetorno['vendedores'][$idxVendedor]['vendfis'][0]['despachante_vfisica']);
					}
				} else {
					$aRetorno['vendedores'][$idxVendedor]['vendjur'] = vendjur::pesquisarPk($cod_ppst,$aVendedor['cod_vend']);
					$aRetorno['vendedores'][$idxVendedor]['vendjursocios'] = vendjursocio::pesquisarPorVendedor($aVendedor['cod_vend']);

					if ($aRetorno['vendedores'][$idxVendedor]['vendjur'][0]['cnpj_vjur'] != '') {
						$mDuplicidade = proposta::getPropostaDuplicidadeRGCPFCNPJ($aListaCnpj,$aRetorno['vendedores'][$idxVendedor]['vendjur'][0]['cnpj_vjur'],"CNPJ");
						if ($mDuplicidade === true) {
							$aListaCnpj[] = $aRetorno['vendedores'][$idxVendedor]['vendjur'][0]['cnpj_vjur'];
						} else {
							$aRetorno['vendedores'][$idxVendedor]['vendjur'][0]['erroValidacao'] = $mDuplicidade;
						}
					}

					if ($idxVendedor == "0") {
						$aRetorno['vendedores'][$idxVendedor]['vendjur'][0]['despachante'] = despachante::pesquisarPk($aRetorno['vendedores'][$idxVendedor]['vendjur'][0]['despachante_vjur']);
					}

					$aRetorno['vendedores'][$idxVendedor]['vendjur'][0]['checklist'] = municipiodocumento::pesquisarPorUfMunicipio($aRetorno['vendedores'][$idxVendedor]['cod_uf'],$aRetorno['vendedores'][$idxVendedor]['cod_municipio'],'vendjur');
					if (is_array($aRetorno['vendedores'][$idxVendedor]['vendjur'][0]['checklist']) && @count($aRetorno['vendedores'][$idxVendedor]['vendjur'][0]['checklist']) > 0) {
						foreach ($aRetorno['vendedores'][$idxVendedor]['vendjur'][0]['checklist'] as $idxChecklistVendjur => $checklistVendjur) {
							$tmpDadosCheckList = clistvendjur::pesquisarPk($checklistVendjur['cod_mndc'],$cod_ppst,$aVendedor['cod_vend']);
							$aRetorno['vendedores'][$idxVendedor]['vendjur'][0]['checklist'][$idxChecklistVendjur]['dados'] = $tmpDadosCheckList[0];
							if ($aRetorno['vendedores'][$idxVendedor]['vendjur'][0]['checklist'][$idxChecklistVendjur]['dados']['flgatendente_clpn'] != 'S') {
								$travaClistVendjur = true;
								$aRetorno['vendedores'][$idxVendedor]['vendjur'][0]['checklist']['travachecklist'] = 'S';
							}
						}
					}
					if (is_array($aRetorno['vendedores'][$idxVendedor]['vendjursocios']) && @count($aRetorno['vendedores'][$idxVendedor]['vendjursocios']) > 0) {
						foreach ($aRetorno['vendedores'][$idxVendedor]['vendjursocios'] as $idxVendJurSocio => $aVendJurSocio) {

							if ($aRetorno['vendedores'][$idxVendedor]['vendjursocios'][$idxVendJurSocio]['cpf_vjsoc'] != '') {
								$mDuplicidade = proposta::getPropostaDuplicidadeRGCPFCNPJ($aListaCpf,$aRetorno['vendedores'][$idxVendedor]['vendjursocios'][$idxVendJurSocio]['cpf_vjsoc']);
								if ($mDuplicidade === true) {
									$aListaCpf[] = $aRetorno['vendedores'][$idxVendedor]['vendjursocios'][$idxVendJurSocio]['cpf_vjsoc'];
								} else {
									$aRetorno['vendedores'][$idxVendedor]['vendjursocios'][$idxVendJurSocio]['erroValidacao'] = $mDuplicidade;
								}
							}

							$aRetorno['vendedores'][$idxVendedor]['vendjursocios'][$idxVendJurSocio]['logradouro'] = logradouro::pesquisarPk($aRetorno['vendedores'][$idxVendedor]['vendjursocios'][$idxVendJurSocio]['cod_logr']);
							$aRetorno['vendedores'][$idxVendedor]['vendjursocios'][$idxVendJurSocio]['bairro'] = bairro::pesquisarPk($aRetorno['vendedores'][$idxVendedor]['vendjursocios'][$idxVendJurSocio]['cod_bairro']);
							$aRetorno['vendedores'][$idxVendedor]['vendjursocios'][$idxVendJurSocio]['uf'] = uf::pesquisarPk($aRetorno['vendedores'][$idxVendedor]['vendjursocios'][$idxVendJurSocio]['cod_uf']);
							$aRetorno['vendedores'][$idxVendedor]['vendjursocios'][$idxVendJurSocio]['municipio'] = municipio::pesquisarPk($aRetorno['vendedores'][$idxVendedor]['vendjursocios'][$idxVendJurSocio]['cod_uf'],$aRetorno['vendedores'][$idxVendedor]['vendjursocios'][$idxVendJurSocio]['cod_municipio']);
							$aRetorno['vendedores'][$idxVendedor]['vendjursocios'][$idxVendJurSocio]['pais'] = pais::pesquisarPk($aRetorno['vendedores'][$idxVendedor]['vendjursocios'][$idxVendJurSocio]['cod_pais']);
						}
					}
				}
			}
		}
		
		$aRetorno['trava_clistimovel'] = $travaClistImovel ? 'S' : '';
		$aRetorno['trava_clistproponente'] = $travaClistProponente ? 'S' : '';
		$aRetorno['trava_clistproponenteconjuge'] = $travaClistProponenteConjuge ? 'S' : '';
		$aRetorno['trava_clistproponentefgts'] = $travaClistProponenteFgts ? 'S' : '';
		$aRetorno['trava_clistvendfis'] = $travaClistVendfis ? 'S' : '';
		$aRetorno['trava_clistvendfisconjuge'] = $travaClistVendfisConjuge ? 'S' : '';
		$aRetorno['trava_clistvendjur'] = $travaClistVendjur ? 'S' : '';
		
		$aRetorno['checklistadvogado'] = clistadvogado::pesquisarPorProposta($cod_ppst);

		return $aRetorno;
		
	}

	function setPropostaStatus($cod_ppst,$situacao) {
		$this->query = "UPDATE proposta SET situacao_ppst = '".$situacao."' WHERE cod_ppst = '".$cod_ppst."'";
		if ($situacao == "6") {
			$this->query();
			$this->query = "UPDATE proposta SET dtaprovacao_ppst = NOW() WHERE cod_ppst = '".$cod_ppst."'";
		}
		return $this->query();
	}
	
	function setPropostaAprovImovel($cod_ppst,$aprovacao) {
		$this->query = "UPDATE proposta SET flgaprovimovel_ppst = '".$aprovacao."' WHERE cod_ppst = '".$cod_ppst."'";
		return $this->query();
	}

	function setPropostaAprovImovelAtendente($cod_ppst,$data) {
		$this->query = "UPDATE imovel SET dtaprovacao_imov = '".utils::data2db($data)."' WHERE cod_ppst = '".$cod_ppst."'";
		return $this->query();
	}

	function setPropostaDataAssinatura($cod_ppst,$data) {
		$this->query = "UPDATE proposta SET dtasscontrato_ppst = '".utils::data2db($data)."' WHERE cod_ppst = '".$cod_ppst."'";
		return $this->query();
	}
	
	function setPropostaAprovacaoPrevi($cod_ppst) {
		$this->query = "UPDATE proposta SET dtaprovacao_ppst = NOW() WHERE cod_ppst = '".$cod_ppst."'";
		return $this->query();
	}
	
	function checkPropostaDuplicidadeCPFProponente($cod_ppst,$cpf) {
		$bRetorno = true;
		$aProponenteProposta = proponente::pesquisarPorCpf($cpf);
		if (is_array($aProponenteProposta) && @count($aProponenteProposta) > 0) {
			foreach ($aProponenteProposta as $aProponente) {
				if ($aProponente["cod_ppst"] != $cod_ppst) {
					$aDadosProposta = proposta::pesquisarPk($aProponente["cod_ppst"]);
					if (is_array($aDadosProposta) && @count($aDadosProposta) > 0 && (int)$aDadosProposta[0]['situacao_ppst'] < 11) {
						$bRetorno = false;
					}
				}
			}
		}
		return $bRetorno;
	}

	function saveProposta() {
		/* @var $crypt crypt_class */
		$this->beginTransaction();

		global $crypt,$cLOGIN;
		$sAcao = $crypt->decrypt($_POST['acaoProposta']);
		
		$cod_ppst = $_POST['frm_cod_ppst'];
		//$aDadosProposta = proposta::pesquisarPk($cod_ppst);

		$retorno = true;
		$aErrors = true;
		
		$aDadosProposta = proposta::getProposta($cod_ppst);
		
		$oHistorico = new historico();
		
		if ($sAcao == "concluir" && $aDadosProposta['situacao_ppst'] == "1" && $cLOGIN->iLEVEL_USUA == TPUSER_PROPONENTE) {
			$oHistorico->inserir($_POST['frm_cod_ppst'],date("Y-m-d H:i:s"),'Proposta enviada � Athos','1',$cLOGIN->iID);
			$this->setPropostaStatus($cod_ppst,"2");
			unset($sAcao);
		} elseif ($sAcao == "concluir" && $aDadosProposta['situacao_ppst'] == "3" && $cLOGIN->iLEVEL_USUA == TPUSER_ATENDENTE) {
			$oHistorico->inserir($_POST['frm_cod_ppst'],date("Y-m-d H:i:s"),'Proposta conclu�da e enviada ao advogado','1',$cLOGIN->iID);
			$this->setPropostaStatus($cod_ppst,"4");
			unset($sAcao);
		} elseif ($sAcao == "concluir" && $aDadosProposta['situacao_ppst'] == "4" && $cLOGIN->iLEVEL_USUA == TPUSER_JURIDICO) {
			$oHistorico->inserir($_POST['frm_cod_ppst'],date("Y-m-d H:i:s"),'Proposta aprovada pela Athos.','1',$cLOGIN->iID);
			$query = "Update proposta set indcancelamento_ppst= NULL, situacao_ppst='6' where cod_ppst='".$_POST['frm_cod_ppst']."'";
			$result =mysql_query($query);
			//$this->setPropostaStatus($cod_ppst,"6");
			/////////////////////////////////////////
			//$this->setPropostaStatus($cod_ppst,"7");
			$this->setPropostaAprovacaoPrevi($cod_ppst);
			unset($sAcao);
		} elseif ($sAcao == "retornar" && $aDadosProposta['situacao_ppst'] == "4" && $cLOGIN->iLEVEL_USUA == TPUSER_JURIDICO) {
			$oHistorico->inserir($_POST['frm_cod_ppst'],date("Y-m-d H:i:s"),'Proposta retornada pelo advogado ao atendente','1',$cLOGIN->iID);
			$this->setPropostaStatus($cod_ppst,"3");
			unset($sAcao);
		} elseif ($sAcao == "cancelar_aprovacao_proposta" && $aDadosProposta['situacao_ppst'] == "6" && $cLOGIN->iLEVEL_USUA == TPUSER_JURIDICO) {
			$oHistorico->inserir($_POST['frm_cod_ppst'],date("Y-m-d H:i:s"),'Aprova��o de Proposta Cancelada.','1',$cLOGIN->iID);
			$query = "Update proposta set dtaprovacao_ppst= NULL, situacao_ppst='4', indcancelamento_ppst='' where cod_ppst='".$_POST['frm_cod_ppst']."'";
			
			$result =mysql_query($query);
			unset($sAcao);
		} elseif ($sAcao == "cancelar_aprovacao_proposta_fgts" && $aDadosProposta['situacao_ppst'] == "6" && $cLOGIN->iLEVEL_USUA == TPUSER_JURIDICO) {
			$oHistorico->inserir($_POST['frm_cod_ppst'],date("Y-m-d H:i:s"),'Aprova��o de Proposta Cancelada.','1',$cLOGIN->iID);
			$query = "Update proposta set dtaprovacao_ppst= NULL, situacao_ppst='4', indcancelamento_ppst='' where cod_ppst='".$_POST['frm_cod_ppst']."'";
			
			$result =mysql_query($query);
			unset($sAcao);			
		} elseif ($sAcao == "cancelar_proposta_pp") {
			$oHistorico->inserir($_POST['frm_cod_ppst'],date("Y-m-d H:i:s"),'Proposta Cancelada a pedido do Proponente.','1',$cLOGIN->iID);
			$query = "Update proposta set dtaprovacao_ppst= NULL, situacao_ppst='5', indcancelamento_ppst='PP', dtapresdoc_ppst= NULL, valorboletoaval_ppst= NULL, flgboletoavalpago_ppst= NULL, dtpagtoboleto_ppst= NULL where cod_ppst='".$_POST['frm_cod_ppst']."'";
			$result =mysql_query($query);
			$query = "Update imovel set vlavaliacao_imov= NULL, dtavaliacao_imov= NULL, dtaprovacao_imov= NULL where cod_ppst='".$_POST['frm_cod_ppst']."'";
			$result =mysql_query($query);
			//echo $sAcao;
			unset($sAcao);
		} elseif ($sAcao == "concluir" && $aDadosProposta['situacao_ppst'] == "5" && $cLOGIN->iLEVEL_USUA == TPUSER_ATENDENTE) {
			$oHistorico->inserir($_POST['frm_cod_ppst'],date("Y-m-d H:i:s"),'Proposta reencaminhada ao advogado','1',$cLOGIN->iID);
			$this->setPropostaStatus($cod_ppst,"4");
			unset($sAcao);
		} elseif ($sAcao == "concluir" && $aDadosProposta['situacao_ppst'] == "7" && $cLOGIN->iLEVEL_USUA == TPUSER_JURIDICO) {
			$oHistorico->inserir($_POST['frm_cod_ppst'],date("Y-m-d H:i:s"),'Contrato Gerado e Assinado.','1',$cLOGIN->iID);
			$this->setPropostaStatus($cod_ppst,"9");
			unset($sAcao);
		} elseif (false/*$sAcao == "concluir" && $aDadosProposta['situacao_ppst'] == "8" && $cLOGIN->iLEVEL_USUA == TPUSER_JURIDICO*/) {
			$oHistorico->inserir($_POST['frm_cod_ppst'],date("Y-m-d H:i:s"),'Proposta assinada.','1',$cLOGIN->iID);
			$this->setPropostaStatus($cod_ppst,"9");
			unset($sAcao);
		} elseif (false/*$sAcao == "concluir" && $aDadosProposta['situacao_ppst'] == "9" && $cLOGIN->iLEVEL_USUA == TPUSER_JURIDICO*/) {
			$oHistorico->inserir($_POST['frm_cod_ppst'],date("Y-m-d H:i:s"),'Informados os dados de registro do im�vel.','1',$cLOGIN->iID);
			$this->setPropostaStatus($cod_ppst,"10");
			unset($sAcao);
		} elseif (false/*$sAcao == "concluir" && $aDadosProposta['situacao_ppst'] == "10" && $cLOGIN->iLEVEL_USUA == TPUSER_JURIDICO*/) {
			$oHistorico->inserir($_POST['frm_cod_ppst'],date("Y-m-d H:i:s"),'Informado parecer final.','1',$cLOGIN->iID);
			$this->setPropostaStatus($cod_ppst,"11");
			unset($sAcao);
		} 
		
		if (($sAcao == "addVend" || $sAcao == "salvar" || $sAcao == "savePpnt" || $sAcao == "saveVend" || $sAcao == "saveSocio") && $cod_ppst) {
			$oHistorico->inserir($_POST['frm_cod_ppst'],date("Y-m-d H:i:s"),'Proposta alterada','1',$cLOGIN->iID);
			//INCLUIR A LOGICA
			//$aDadosProposta['valorcompra_ppst'] = $_POST[''];
			//INCLUIR A LOGICA
			//$aDadosProposta['valorfgts_ppst'] = $_POST[''];
			
			$aDadosProposta['valorseguro_ppst'] = $_POST['valorseguro_ppst'];
			$aDadosProposta['valormanutencao_ppst'] = $_POST['valormanutencao_ppst'];
			
			if ($aDadosProposta['situacao_ppst'] == "1" || ($aDadosProposta['situacao_ppst'] == "3" && $cLOGIN->iLEVEL_USUA == TPUSER_ATENDENTE) || $aDadosProposta['situacao_ppst'] == "5" ) {
				if (!$aDadosProposta['dtapresdoc_ppst']) {
					if ($_POST['dtapresdoc_ppst'] != '') {
						$aDadosProposta['dtapresdoc_ppst'] = utils::data2db($_POST['dtapresdoc_ppst']);
					}
				}
				
				if ($_POST['dtpagtoboleto_ppst'] && $_POST['flgboletoavalpago_ppst']) {
					$aDadosProposta['valorboletoaval_ppst'] 	= $_POST['valorboletoaval_ppst'];
					$aDadosProposta['flgboletoavalpago_ppst'] 	= $_POST['flgboletoavalpago_ppst'];
					$aDadosProposta['flgformapagto_ppst'] 	= $_POST['flgformapagto_ppst'];
					$aDadosProposta['dtpagtoboleto_ppst'] 		= utils::data2db($_POST['dtpagtoboleto_ppst']);
				}
			}
			
			proposta::atualizarPk($aDadosProposta['cod_ppst'],$aDadosProposta['data_ppst'],$aDadosProposta['situacao_ppst'],$aDadosProposta['dtapresdoc_ppst'],$aDadosProposta['dtiniexigencia_ppst'],$aDadosProposta['dtfimexigencia_ppst'],$aDadosProposta['dtaprovacao_ppst'],$aDadosProposta['dtasscontrato_ppst'],$aDadosProposta['dtokregistro_ppst'],$aDadosProposta['vlfinsol_ppst'],$aDadosProposta['przfinsol_ppst'],$aDadosProposta['valordevsinalsol_ppst'],$aDadosProposta['pricesac_ppst'],$aDadosProposta['valorcompra_ppst'],$aDadosProposta['valorfgts_ppst'],$aDadosProposta['taxajuros_ppst'],$aDadosProposta['valorseguro_ppst'],$aDadosProposta['valormanutencao_ppst'],$aDadosProposta['vlprestsol_ppst'],$aDadosProposta['indcancelamento_ppst'],$aDadosProposta['valorboletoaval_ppst'], $aDadosProposta['flgformapagto_ppst'],$aDadosProposta['flgboletoavalpago_ppst'],$aDadosProposta['dtagend_asscontr_ppst'],$aDadosProposta['flgrespostavalor_ppst'],$aDadosProposta['dtpagtoboleto_ppst'],$aDadosProposta['flgaprovacaoprevi_ppst'],$aDadosProposta['dtremessacontrato_ppst'],$aDadosProposta['flgpropostaativa_ppst']);

			$aDadosProposta['imovel']['tipo_imov'] = $_POST['tipo_imov'];
			
			//INCLUIR REGRA
			//$aDadosProposta['imovel']['flgaprovacao_imov'] = $_POST[''];
			
			
			if (($aDadosProposta['situacao_ppst'] == "3" && ($cLOGIN->iLEVEL_USUA == TPUSER_ATENDENTE || ($cLOGIN->iLEVEL_USUA == TPUSER_PROPONENTE && $aDadosProposta['imovel']['despachante_imov'] == "") || ($cLOGIN->iLEVEL_USUA == TPUSER_DESPACHANTE && $aDadosProposta['imovel']['despachante_imov'] == $cLOGIN->iID) )) || $aDadosProposta['situacao_ppst'] == "1" || $aDadosProposta['situacao_ppst'] == "5") {
				clistimovel::deletarPorProposta($cod_ppst);
			}
			
			if (!$aDadosProposta['imovel']['dtaprovacao_imov'] && ($aDadosProposta['situacao_ppst'] == "1" || ($aDadosProposta['situacao_ppst'] == "3" && $cLOGIN->iLEVEL_USUA == TPUSER_ATENDENTE) || $aDadosProposta['situacao_ppst'] == "5")) {
				
				$aDadosProposta['imovel']['area_imov'] = utils::limpaNumeros($_POST['area_imov']);
				$aDadosProposta['imovel']['tipo_imov'] = $_POST['tipo_imov'];
				$aDadosProposta['imovel']['tpconstrucao_imov'] = $_POST['tpconstrucao_imov'];
				$aDadosProposta['imovel']['tpcondominio_imov'] = $_POST['tpcondominio_imov'];
				$aDadosProposta['imovel']['qtsala_imov'] = $_POST['qtsala_imov'];
				$aDadosProposta['imovel']['qtquarto_imov'] = $_POST['qtquarto_imov'];
				$aDadosProposta['imovel']['qtbanh_imov'] = $_POST['qtbanh_imov'];
				$aDadosProposta['imovel']['qtgarag_imov'] = $_POST['qtgarag_imov'];
				$aDadosProposta['imovel']['qtpavim_imov'] = $_POST['qtpavim_imov'];
				$aDadosProposta['imovel']['qtdepemp_imov'] = $_POST['qtdepemp_imov'];
				$aDadosProposta['imovel']['estconserv_imov'] = $_POST['estconserv_imov'];
				$aDadosProposta['imovel']['estconspred_imov'] = $_POST['estconspred_imov'];
				$aDadosProposta['imovel']['endereco_imov'] = $_POST['endereco_imov'];
				$aDadosProposta['imovel']['nrendereco_imov'] = $_POST['nrendereco_imov'];
				$aDadosProposta['imovel']['cpendereco_imov'] = $_POST['cpendereco_imov'];
				$aDadosProposta['imovel']['cep_imov'] = utils::limpaNumeros($_POST['cep_imov']);
				$aDadosProposta['imovel']['tpimposto_imov'] = $_POST['tpimposto_imov'];

				$aDadosProposta['imovel']['vlavalsemgar_imov'] = utils::moeda2db($_POST['vlavalsemgar_imov']);
				$aDadosProposta['imovel']['vlavalgar_imov'] = utils::moeda2db($_POST['vlavalgar_imov']);
				$aDadosProposta['imovel']['vlavaliacao_imov'] = utils::moeda2db($_POST['vlavaliacao_imov']);
				$aDadosProposta['imovel']['dtavaliacao_imov'] = utils::data2db($_POST['dtavaliacao_imov']);
				
				$aDadosProposta['imovel']['cod_logr'] = $_POST['cod_logr_imov'];
				$aDadosProposta['imovel']['cod_bairro'] = $_POST['cod_bairro_imov'];
				$aDadosProposta['imovel']['cod_uf'] = $_POST['cod_uf_imov'];
				$aDadosProposta['imovel']['cod_municipio'] = $_POST['cod_municipio_imov'];
				$aDadosProposta['imovel']['tpmoradia_imov'] = $_POST['tpmoradia_imov'];
				$aDadosProposta['imovel']['terreo_imov'] = $_POST['terreo_imov'];
				$aDadosProposta['imovel']['tmbdspcndop_imov'] = $_POST['tmbdspcndop_imov'];
				$aDadosProposta['imovel']['incomb_imov'] = $_POST['incomb_imov'];
				$aDadosProposta['imovel']['ruralfav_imov'] = $_POST['ruralfav_imov'];
				$aDadosProposta['imovel']['emconstr_imov'] = $_POST['emconstr_imov'];
				$aDadosProposta['imovel']['aquispaimae_imov'] = $_POST['aquispaimae_imov'];
				$aDadosProposta['imovel']['possuiirmaos_imov'] = $_POST['possuiirmaos_imov'];
				$aDadosProposta['imovel']['andar_imov'] = $_POST['andar_imov'];
				$aDadosProposta['imovel']['pavimento_imov'] = $_POST['pavimento_imov'];
				$aDadosProposta['imovel']['tpapto_imov'] = $_POST['tipo_apartam'];
				$aDadosProposta['imovel']['flgbloco_imov'] = $_POST['bloco_imov'];
				$aDadosProposta['imovel']['flgcondominio_imov'] = $_POST['condominio_imov'];
				$aDadosProposta['imovel']['numbloco_imov'] = $_POST['numero_bloco_imov'];
				$aDadosProposta['imovel']['edificio_imov'] = $_POST['edificio_bloco_imov'];
				$aDadosProposta['imovel']['conjunto_imov'] = $_POST['conjunto_bloco_imov'];
				$aDadosProposta['imovel']['nrmatrgi_imov'] = $_POST['nrmatrgi_imov'];
				$aDadosProposta['imovel']['areautil_imov'] = utils::limpaNumeros($_POST['area_util']);
				$aDadosProposta['imovel']['areatotal_imov'] = utils::limpaNumeros($_POST['area_total']);
				$aDadosProposta['imovel']['vagasapto_imov'] = $_POST['vagas_garagem_imov'];
				$aDadosProposta['imovel']['isolado_imov'] = $_POST['isolado_imov'];
				$aDadosProposta['imovel']['nomecondominio_imov'] = $_POST['nome_condominio_imov'];
				$aDadosProposta['imovel']['despachante_imov'] = $_POST['cod_despachante_imov'];
				
				imovel::atualizarPk($aDadosProposta['imovel']['cod_ppst'],$aDadosProposta['imovel']['tipo_imov'],$aDadosProposta['imovel']['flgaprovacao_imov'],$aDadosProposta['imovel']['area_imov'],$aDadosProposta['imovel']['tpconstrucao_imov'],$aDadosProposta['imovel']['tpcondominio_imov'],$aDadosProposta['imovel']['qtsala_imov'],$aDadosProposta['imovel']['qtquarto_imov'],$aDadosProposta['imovel']['qtbanh_imov'],$aDadosProposta['imovel']['qtgarag_imov'],$aDadosProposta['imovel']['qtpavim_imov'],$aDadosProposta['imovel']['qtdepemp_imov'],$aDadosProposta['imovel']['estconserv_imov'],$aDadosProposta['imovel']['estconspred_imov'],$aDadosProposta['imovel']['nomecartrgi_imov'],$aDadosProposta['imovel']['nrmatrgi_imov'],$aDadosProposta['imovel']['nrlivrgi_imov'],$aDadosProposta['imovel']['nrfolhrgi_imov'],$aDadosProposta['imovel']['nrrgcompvend_imov'],$aDadosProposta['imovel']['nrrggar_imov'],$aDadosProposta['imovel']['endereco_imov'],$aDadosProposta['imovel']['nrendereco_imov'],$aDadosProposta['imovel']['cpendereco_imov'],$aDadosProposta['imovel']['cep_imov'],$aDadosProposta['imovel']['tpimposto_imov'],$aDadosProposta['imovel']['vlavaliacao_imov'],$aDadosProposta['imovel']['vlavalsemgar_imov'],$aDadosProposta['imovel']['vlavalgar_imov'],$aDadosProposta['imovel']['dtavaliacao_imov'],$aDadosProposta['imovel']['dtaprovacao_imov'],$aDadosProposta['imovel']['cod_logr'],$aDadosProposta['imovel']['cod_bairro'],$aDadosProposta['imovel']['cod_uf'],$aDadosProposta['imovel']['cod_municipio'],$aDadosProposta['imovel']['tpmoradia_imov'],$aDadosProposta['imovel']['terreo_imov'],$aDadosProposta['imovel']['tmbdspcndop_imov'],$aDadosProposta['imovel']['incomb_imov'],$aDadosProposta['imovel']['ruralfav_imov'],$aDadosProposta['imovel']['emconstr_imov'],$aDadosProposta['imovel']['aquispaimae_imov'],$aDadosProposta['imovel']['possuiirmaos_imov'],$aDadosProposta['imovel']['andar_imov'],$aDadosProposta['imovel']['pavimento_imov'],$aDadosProposta['imovel']['tpapto_imov'],$aDadosProposta['imovel']['flgbloco_imov'],$aDadosProposta['imovel']['numbloco_imov'],$aDadosProposta['imovel']['edificio_imov'],$aDadosProposta['imovel']['conjunto_imov'],$aDadosProposta['imovel']['areautil_imov'],$aDadosProposta['imovel']['areatotal_imov'],$aDadosProposta['imovel']['vagasapto_imov'],$aDadosProposta['imovel']['isolado_imov'],$aDadosProposta['imovel']['nomecondominio_imov'],$aDadosProposta['imovel']['despachante_imov'],$aDadosProposta['imovel']['flgcondominio_imov']);
				imovelvaga::deletarPorProposta($cod_ppst);
				
				if ((int)$_POST['vagas_garagem_imov'] > 0) {
					for ($iVgGaragem = 1; $iVgGaragem <= (int)$_POST['vagas_garagem_imov']; $iVgGaragem++) {
						imovelvaga::inserir($cod_ppst,$_POST['tipo_vaga_imov_'.$iVgGaragem.''],$_POST['local_vaga_imov_'.$iVgGaragem.''],utils::moeda2db($_POST['area_util_vaga_imov_'.$iVgGaragem.'']),utils::moeda2db($_POST['area_comum_vaga_imov_'.$iVgGaragem.'']),utils::moeda2db($_POST['area_total_vaga_imov_'.$iVgGaragem.'']),utils::moeda2db($_POST['fracao_vaga_imov_'.$iVgGaragem.'']),$_POST['num_contrib_vaga_imov_'.$iVgGaragem.''],$_POST['num_reg_vaga_imov_'.$iVgGaragem.''],$_POST['num_matr_vaga_imov_'.$iVgGaragem.''],$_POST['num_oficio_vaga_imov_'.$iVgGaragem.''],$_POST['local_oficio_vaga_imov_'.$iVgGaragem.'']);
					}
				}
			}
			
			
//if (!$aDadosProposta['imovel']['dtaprovacao_imov'] && ($aDadosProposta['situacao_ppst'] == "1" || $aDadosProposta['situacao_ppst'] == "3" || $aDadosProposta['situacao_ppst'] == "5")) {
							

			if ($_POST['frm_cod_ppnt'] != '' && ($aDadosProposta['situacao_ppst'] == "1" || ($aDadosProposta['situacao_ppst'] == "3" && $cLOGIN->iLEVEL_USUA == TPUSER_ATENDENTE) || $aDadosProposta['situacao_ppst'] == "5" || (($_GET['corrigir']=='sim') && $cLOGIN->iLEVEL_USUA == TPUSER_ATENDENTE && $aDadosProposta['situacao_ppst'] >= 4 && $aDadosProposta["situacao_ppst"]<8))) {
				$aProponente = proponente::pesquisarPk($_POST['frm_cod_ppnt'],$cod_ppst);
			

				if (($aDadosProposta['situacao_ppst'] == "3" && ($cLOGIN->iLEVEL_USUA == TPUSER_ATENDENTE || $cLOGIN->iLEVEL_USUA == TPUSER_PROPONENTE)) || $aDadosProposta['situacao_ppst'] == "1") {
					clistproponente::deletarPorPropostaProponente($cod_ppst,$_POST['frm_cod_ppnt']);
					clistproponenteconjuge::deletarPorPropostaProponente($cod_ppst,$_POST['frm_cod_ppnt']);
					clistproponentefgts::deletarPorPropostaProponente($cod_ppst,$_POST['frm_cod_ppnt']);
				}
				$aProponente[0]['nome_usua'] = $_POST['nome_ppnt'];
				$aProponente[0]['cpf_ppnt'] = utils::limpaNumeros($_POST['cpf_ppnt']);
				$aProponente[0]['nrrg_ppnt'] = $_POST['nrrg_ppnt'];
				$aProponente[0]['orgrg_ppnt'] = $_POST['orgrg_ppnt'];
				$aProponente[0]['dtrg_ppnt'] = utils::data2db($_POST['dtrg_ppnt']);
				$aProponente[0]['dtnascimento_ppnt'] = utils::data2db($_POST['dtnascimento_ppnt']);
				$aProponente[0]['sexo_ppnt'] = $_POST['sexo_ppnt'];
				$aProponente[0]['nacional_ppnt'] = $_POST['nacional_ppnt'];
				$aProponente[0]['endereco_ppnt'] = $_POST['endereco_ppnt'];
				$aProponente[0]['nrendereco_ppnt'] = $_POST['nrendereco_ppnt'];
				$aProponente[0]['cpendereco_ppnt'] = $_POST['cpendereco_ppnt'];
				$aProponente[0]['bairro_ppnt'] = $_POST['bairro_ppnt'];
				$aProponente[0]['cep_ppnt'] = utils::limpaNumeros($_POST['cep_ppnt']);
				$aProponente[0]['cod_logr'] = $_POST['cod_logr_ppnt'];
				$aProponente[0]['cod_bairro'] = $_POST['cod_bairro_ppnt'];
				$aProponente[0]['cod_uf'] = $_POST['cod_uf_ppnt'];
				$aProponente[0]['cod_municipio'] = $_POST['cod_municipio_ppnt'];
				$aProponente[0]['cod_estciv'] = $_POST['cod_estciv_ppnt'];
				//$aProponente[0]['telefone_ppnt'] = utils::limpaNumeros($_POST['telefone_ppnt']);
				$aProponente[0]['email_ppnt'] = $_POST['email_ppnt'];
				$aProponente[0]['profissao_ppnt'] = $_POST['profissao_ppnt'];
				$aProponente[0]['flgproc_ppnt'] = $_POST['flgproc_ppnt'];
				$aProponente[0]['proc_ppnt'] = $_POST['proc_ppnt'];
				$aProponente[0]['flguniest_ppnt'] = $_POST['flguniest_ppnt'];
				$aProponente[0]['flgescritura_ppnt'] = $_POST['flgescritura_ppnt'];
				$aProponente[0]['flgdevsol_ppnt'] = $_POST['flgdevsol_ppnt'] ? $_POST['flgdevsol_ppnt'] : "N";
				
				$aDadosProponenteTelefone = array();
				$cTels = 0;
				for($iTels=1; $iTels<=3; $iTels++){
				  if($_POST['telefone_ppnt_'.$iTels]!=''){
				    $aDadosProponenteTelefone[$cTels]['TELEFONE_PPTL'] = utils::limpaNumeros($_POST['telefone_ppnt_'.$iTels]);;
				    $aDadosProponenteTelefone[$cTels]['TIPO_PPTL'] = $_POST['tipotelefone_ppnt_'.$iTels];
				    $cTels++;
				    //insert
				  }
				}
				proponentetelefone::deletePorPpnt($_POST['frm_cod_ppnt']);
				foreach($aDadosProponenteTelefone as $vDadosProponenteTelefone){
				  proponentetelefone::insert($_POST['frm_cod_ppnt'],$vDadosProponenteTelefone['TELEFONE_PPTL'],$vDadosProponenteTelefone['TIPO_PPTL']);
				}
				
				if ($_POST['sel_tipo_ppnt_finan'] == "1") {
					$aProponente[0]['vlprestsol_ppnt'] = utils::moeda2db($_POST['vlprestsol_ppnt']);
					$aProponente[0]['przfinsol_ppnt'] = '';
				} elseif ($_POST['sel_tipo_ppnt_finan'] == "2") {
					$aProponente[0]['vlprestsol_ppnt'] = '';
					$aProponente[0]['przfinsol_ppnt'] = utils::moeda2db($_POST['przfinsol_ppnt']);
				} else {
					$aProponente[0]['vlprestsol_ppnt'] = '';
					$aProponente[0]['przfinsol_ppnt'] = '';
				}
				
				$aProponente[0]['vlfinsol_ppnt'] = utils::moeda2db($_POST['vlfinsol_ppnt']);
				$aProponente[0]['vlsinal_ppnt'] = utils::moeda2db($_POST['vlsinal_ppnt']);
				$aProponente[0]['vlentrada_ppnt'] = utils::moeda2db($_POST['vlentrada_ppnt']);
				$aProponente[0]['vlcompra_ppnt'] = utils::moeda2db($_POST['vlcompra_ppnt']);
				
				proponente::atualizarPk($aProponente[0]['cpf_ppnt'],$aProponente[0]['nrrg_ppnt'],$aProponente[0]['orgrg_ppnt'],$aProponente[0]['dtrg_ppnt'],$aProponente[0]['dtnascimento_ppnt'],$aProponente[0]['sexo_ppnt'],$aProponente[0]['nacional_ppnt'],$aProponente[0]['endereco_ppnt'],$aProponente[0]['nrendereco_ppnt'],$aProponente[0]['cpendereco_ppnt'],$aProponente[0]['bairro_ppnt'],$aProponente[0]['cep_ppnt'],$aProponente[0]['cod_proponente'],$aProponente[0]['cod_logr'],$aProponente[0]['cod_bairro'],$aProponente[0]['cod_uf'],$aProponente[0]['cod_municipio'],$aProponente[0]['cod_estciv'],$aProponente[0]['telefone_ppnt'],$aProponente[0]['cod_ppst'],$aProponente[0]['vlfinsol_ppnt'],$aProponente[0]['przfinsol_ppnt'],$aProponente[0]['vlsinal_ppnt'],$aProponente[0]['vlentrada_ppnt'],$aProponente[0]['vlprestsol_ppnt'],$aProponente[0]['vlcompra_ppnt'],$aProponente[0]['vlfinaprov_ppnt'],$aProponente[0]['vlprestaprov_ppnt'],$aProponente[0]['przaprov_ppnt'],$aProponente[0]['despachante_ppnt'],$aProponente[0]['flgdevsol_ppnt'],$aProponente[0]['email_ppnt'],$aProponente[0]['profissao_ppnt'],$aProponente[0]['flgproc_ppnt'],$aProponente[0]['proc_ppnt'],$aProponente[0]['flguniest_ppnt'],$aProponente[0]['flgescritura_ppnt']);
				
				
				usuario::atualizarNome($aProponente[0]['cod_proponente'],$aProponente[0]['nome_usua']);
				$aDadosProponenteProfissao = proponenteprofissao::pesquisarPk($_POST['frm_cod_ppnt']);
				proponenteprofissao::deletarPk($_POST['frm_cod_ppnt']);
				
				$aDadosProponenteProfissao[0]['empresa_pppf'] = $_POST['empresa_ppnt'];
				$aDadosProponenteProfissao[0]['dtadmissao_pppf'] = utils::data2db($_POST['dtadmissaoemp_ppnt']);
				$aDadosProponenteProfissao[0]['enderecoemp_pppf'] = $_POST['enderecoemp_ppnt'];
				$aDadosProponenteProfissao[0]['numeroemp_pppf'] = $_POST['nrenderecoemp_ppnt'];
				$aDadosProponenteProfissao[0]['complementoemp_pppf'] = $_POST['cpenderecoemp_ppnt'];
				$aDadosProponenteProfissao[0]['bairro_pppf'] = $_POST['bairroemp_ppnt'];
				$aDadosProponenteProfissao[0]['cidade_pppf'] = $_POST['cidadeemp_ppnt'];
				$aDadosProponenteProfissao[0]['estado_pppf'] = $_POST['estadoemp_ppnt'];
				$aDadosProponenteProfissao[0]['telefone_pppf'] = utils::limpaNumeros($_POST['telefoneemp_ppnt']);
				$aDadosProponenteProfissao[0]['cargo_pppf'] = $_POST['cargoemp_ppnt'];
				$aDadosProponenteProfissao[0]['salario_pppf'] = utils::moeda2db($_POST['salarioemp_ppnt']);
				proponenteprofissao::inserir($_POST['frm_cod_ppnt'],$aDadosProponenteProfissao[0]['empresa_pppf'],$aDadosProponenteProfissao[0]['dtadmissao_pppf'],$aDadosProponenteProfissao[0]['enderecoemp_pppf'],$aDadosProponenteProfissao[0]['numeroemp_pppf'],$aDadosProponenteProfissao[0]['complementoemp_pppf'],$aDadosProponenteProfissao[0]['bairro_pppf'],$aDadosProponenteProfissao[0]['cidade_pppf'],$aDadosProponenteProfissao[0]['estado_pppf'],$aDadosProponenteProfissao[0]['telefone_pppf'],$aDadosProponenteProfissao[0]['cargo_pppf'],$aDadosProponenteProfissao[0]['salario_pppf']);
				
				$aDadosProponenteConjugePacto = proponenteconjugepacto::pesquisarPk($_POST['frm_cod_ppnt'],$cod_ppst);
				proponenteconjugepacto::deletarPk($_POST['frm_cod_ppnt'],$cod_ppst);
				
				if (($_GET['corrigir']=='sim') && $cLOGIN->iLEVEL_USUA == TPUSER_ATENDENTE && $aDadosProposta['situacao_ppst'] >= 4 && $aDadosProposta["situacao_ppst"]<8){
				$aDadosProponenteConjuge[0]['regimebens_ppcj'] = $_POST['regimebens_ppcj'];
				$aDadosProponenteConjuge[0]['dtcasamento_ppcj'] = utils::data2db($_POST['dtcasamento_ppcj']);
				$aDadosProponenteConjuge[0]['nome_ppcj'] = $_POST['nome_ppcj'];
				$aDadosProponenteConjuge[0]['cod_pais'] = $_POST['prop_conjuge_nacionalidade'];
				$aDadosProponenteConjuge[0]['cod_estciv'] = $_POST['prop_conjuge_estciv'];
				$aDadosProponenteConjuge[0]['nrrg_ppcj'] = $_POST['nrrg_ppcj'];
				$aDadosProponenteConjuge[0]['orgrg_ppcj'] = $_POST['orgrg_ppcj'];
				$aDadosProponenteConjuge[0]['dtrg_ppcj'] = utils::data2db($_POST['dtrg_ppcj']);
				$aDadosProponenteConjuge[0]['cpf_pccj'] = utils::limpaNumeros($_POST['cpf_pccj']);
				$aDadosProponenteConjuge[0]['flgtrabalha_ppcj'] = $_POST['flgtrabalha_ppcj'];
				$aDadosProponenteConjuge[0]['empresa_ppcj'] = $_POST['empresa_ppcj'];
				$aDadosProponenteConjuge[0]['dtadmissaoemp_ppcj'] = utils::data2db($_POST['dtadmissaoemp_ppcj']);
				$aDadosProponenteConjuge[0]['enderecoemp_ppcj'] = $_POST['enderecoemp_ppcj'];
				$aDadosProponenteConjuge[0]['numeroemp_ppcj'] = $_POST['numeroemp_ppcj'];
				$aDadosProponenteConjuge[0]['complementoemp_ppcj'] = $_POST['complementoemp_ppcj'];
				$aDadosProponenteConjuge[0]['bairroemp_ppcj'] = $_POST['bairroemp_ppcj'];
				$aDadosProponenteConjuge[0]['cidadeemp_ppcj'] = $_POST['cidadeemp_ppcj'];
				$aDadosProponenteConjuge[0]['estadoemp_ppcj'] = $_POST['estadoemp_ppcj'];
				$aDadosProponenteConjuge[0]['telefoneemp_ppcj'] = utils::limpaNumeros($_POST['telefoneemp_ppcj']);
				$aDadosProponenteConjuge[0]['cargoemp_ppcj'] = $_POST['cargoemp_ppcj'];
				$aDadosProponenteConjuge[0]['salarioemp_ppcj'] = utils::moeda2db($_POST['salarioemp_ppcj']);
				
				proponenteconjuge::atualizarPk($_POST['frm_cod_ppnt'],$aDadosProponenteConjuge[0]['regimebens_ppcj'],$aDadosProponenteConjuge[0]['dtcasamento_ppcj'],$aDadosProponenteConjuge[0]['nome_ppcj'],$aDadosProponenteConjuge[0]['cod_pais'],$aDadosProponenteConjuge[0]['cod_estciv'],$aDadosProponenteConjuge[0]['nrrg_ppcj'],$aDadosProponenteConjuge[0]['orgrg_ppcj'],$aDadosProponenteConjuge[0]['dtrg_ppcj'],$aDadosProponenteConjuge[0]['cpf_pccj'],$aDadosProponenteConjuge[0]['flgtrabalha_ppcj'],$aDadosProponenteConjuge[0]['empresa_ppcj'],$aDadosProponenteConjuge[0]['dtadmissaoemp_ppcj'],$aDadosProponenteConjuge[0]['enderecoemp_ppcj'],$aDadosProponenteConjuge[0]['numeroemp_ppcj'],$aDadosProponenteConjuge[0]['complementoemp_ppcj'],$aDadosProponenteConjuge[0]['bairroemp_ppcj'],$aDadosProponenteConjuge[0]['cidadeemp_ppcj'],$aDadosProponenteConjuge[0]['estadoemp_ppcj'],$aDadosProponenteConjuge[0]['telefoneemp_ppcj'],$aDadosProponenteConjuge[0]['cargoemp_ppcj'],$aDadosProponenteConjuge[0]['salarioemp_ppcj'],$cod_ppst,$aDadosProponenteConjuge[0]['despachante_ppcj']);
			
				}
				else{
				$aDadosProponenteConjuge = proponenteconjuge::pesquisarPk($_POST['frm_cod_ppnt'],$cod_ppst);
				proponenteconjuge::deletarPk($_POST['frm_cod_ppnt'],$cod_ppst);
					
				$aDadosProponenteConjuge[0]['regimebens_ppcj'] = $_POST['regimebens_ppcj'];
				$aDadosProponenteConjuge[0]['dtcasamento_ppcj'] = utils::data2db($_POST['dtcasamento_ppcj']);
				$aDadosProponenteConjuge[0]['nome_ppcj'] = $_POST['nome_ppcj'];
				$aDadosProponenteConjuge[0]['cod_pais'] = $_POST['prop_conjuge_nacionalidade'];
				$aDadosProponenteConjuge[0]['cod_estciv'] = $_POST['prop_conjuge_estciv'];
				$aDadosProponenteConjuge[0]['nrrg_ppcj'] = $_POST['nrrg_ppcj'];
				$aDadosProponenteConjuge[0]['orgrg_ppcj'] = $_POST['orgrg_ppcj'];
				$aDadosProponenteConjuge[0]['dtrg_ppcj'] = utils::data2db($_POST['dtrg_ppcj']);
				$aDadosProponenteConjuge[0]['cpf_pccj'] = utils::limpaNumeros($_POST['cpf_pccj']);
				$aDadosProponenteConjuge[0]['flgtrabalha_ppcj'] = $_POST['flgtrabalha_ppcj'];
				$aDadosProponenteConjuge[0]['empresa_ppcj'] = $_POST['empresa_ppcj'];
				$aDadosProponenteConjuge[0]['dtadmissaoemp_ppcj'] = utils::data2db($_POST['dtadmissaoemp_ppcj']);
				$aDadosProponenteConjuge[0]['enderecoemp_ppcj'] = $_POST['enderecoemp_ppcj'];
				$aDadosProponenteConjuge[0]['numeroemp_ppcj'] = $_POST['numeroemp_ppcj'];
				$aDadosProponenteConjuge[0]['complementoemp_ppcj'] = $_POST['complementoemp_ppcj'];
				$aDadosProponenteConjuge[0]['bairroemp_ppcj'] = $_POST['bairroemp_ppcj'];
				$aDadosProponenteConjuge[0]['cidadeemp_ppcj'] = $_POST['cidadeemp_ppcj'];
				$aDadosProponenteConjuge[0]['estadoemp_ppcj'] = $_POST['estadoemp_ppcj'];
				$aDadosProponenteConjuge[0]['telefoneemp_ppcj'] = utils::limpaNumeros($_POST['telefoneemp_ppcj']);
				$aDadosProponenteConjuge[0]['cargoemp_ppcj'] = $_POST['cargoemp_ppcj'];
				$aDadosProponenteConjuge[0]['salarioemp_ppcj'] = utils::moeda2db($_POST['salarioemp_ppcj']);
				
				proponenteconjuge::inserir($_POST['frm_cod_ppnt'],$aDadosProponenteConjuge[0]['regimebens_ppcj'],$aDadosProponenteConjuge[0]['dtcasamento_ppcj'],$aDadosProponenteConjuge[0]['nome_ppcj'],$aDadosProponenteConjuge[0]['cod_pais'],$aDadosProponenteConjuge[0]['cod_estciv'],$aDadosProponenteConjuge[0]['nrrg_ppcj'],$aDadosProponenteConjuge[0]['orgrg_ppcj'],$aDadosProponenteConjuge[0]['dtrg_ppcj'],$aDadosProponenteConjuge[0]['cpf_pccj'],$aDadosProponenteConjuge[0]['flgtrabalha_ppcj'],$aDadosProponenteConjuge[0]['empresa_ppcj'],$aDadosProponenteConjuge[0]['dtadmissaoemp_ppcj'],$aDadosProponenteConjuge[0]['enderecoemp_ppcj'],$aDadosProponenteConjuge[0]['numeroemp_ppcj'],$aDadosProponenteConjuge[0]['complementoemp_ppcj'],$aDadosProponenteConjuge[0]['bairroemp_ppcj'],$aDadosProponenteConjuge[0]['cidadeemp_ppcj'],$aDadosProponenteConjuge[0]['estadoemp_ppcj'],$aDadosProponenteConjuge[0]['telefoneemp_ppcj'],$aDadosProponenteConjuge[0]['cargoemp_ppcj'],$aDadosProponenteConjuge[0]['salarioemp_ppcj'],$cod_ppst,$aDadosProponenteConjuge[0]['despachante_ppcj']);

				}
				
				$aDadosProponenteConjugePacto[0]['data_pcpa'] = utils::data2db($_POST['data_pcpa']);
				$aDadosProponenteConjugePacto[0]['locallavracao_pcpa'] = $_POST['locallavracao_pcpa'];
				$aDadosProponenteConjugePacto[0]['livro_pcpa'] = $_POST['livro_pcpa'];
				$aDadosProponenteConjugePacto[0]['folha_pcpa'] = $_POST['folha_pcpa'];
				$aDadosProponenteConjugePacto[0]['numeroregistro_pcpa'] = $_POST['numeroregistro_pcpa'];
				$aDadosProponenteConjugePacto[0]['habens_pcpa'] = $_POST['habens_pcpa'];
				$aDadosProponenteConjugePacto[0]['habenscart_pcpa'] = $_POST['habenscart_pcpa'];
				$aDadosProponenteConjugePacto[0]['habensloccart_pcpa'] = $_POST['habensloccart_pcpa'];
				$aDadosProponenteConjugePacto[0]['habensdata_pcpa'] = utils::data2db($_POST['habensdata_pcpa']);
				
				proponenteconjugepacto::inserir($_POST['frm_cod_ppnt'],$aDadosProponenteConjugePacto[0]['data_pcpa'],$aDadosProponenteConjugePacto[0]['locallavracao_pcpa'],$aDadosProponenteConjugePacto[0]['livro_pcpa'],$aDadosProponenteConjugePacto[0]['folha_pcpa'],$aDadosProponenteConjugePacto[0]['numeroregistro_pcpa'],$aDadosProponenteConjugePacto[0]['habens_pcpa'],$aDadosProponenteConjugePacto[0]['habenscart_pcpa'],$aDadosProponenteConjugePacto[0]['habensloccart_pcpa'],$aDadosProponenteConjugePacto[0]['habensdata_pcpa'],$cod_ppst);
				
				$aDadosDevedorSolidario = devsol::pesquisarPk($cod_ppst,$_POST['frm_cod_ppnt']);
				devsol::deletarPk($cod_ppst,$_POST['frm_cod_ppnt']);
				
				if ($aProponente[0]['flgdevsol_ppnt'] == "S") {
					$aDadosDevedorSolidario[0]['nome_devsol'] = $_POST['nome_devsol'];
					$aDadosDevedorSolidario[0]['nick_devsol'] = $_POST['nick_devsol'];
					$aDadosDevedorSolidario[0]['cod_logr'] = $_POST['logr_devsol'];
					$aDadosDevedorSolidario[0]['endereco_devsol'] = $_POST['endereco_devsol'];
					$aDadosDevedorSolidario[0]['nrendereco_devsol'] = $_POST['nrendereco_devsol'];
					$aDadosDevedorSolidario[0]['cpendereco_devsol'] = $_POST['cpendereco_devsol'];
					$aDadosDevedorSolidario[0]['cod_bairro'] = $_POST['bairro_devsol'];
					$aDadosDevedorSolidario[0]['cep_devsol'] = utils::limpaNumeros($_POST['cep_devsol']);
					$aDadosDevedorSolidario[0]['cod_uf'] = $_POST['uf_devsol'];
					$aDadosDevedorSolidario[0]['cod_municipio'] = $_POST['municipio_devsol'];
					$aDadosDevedorSolidario[0]['telefone_devsol'] = utils::limpaNumeros($_POST['telefone_devsol']);
					$aDadosDevedorSolidario[0]['cpf_devsol'] = utils::limpaNumeros($_POST['cpf_devsol']);
					$aDadosDevedorSolidario[0]['sexo_devsol'] = $_POST['sexo_devsol'];
					$aDadosDevedorSolidario[0]['cod_pais'] = $_POST['pais_devsol'];
					devsol::inserir($cod_ppst,$aDadosDevedorSolidario[0]['nome_devsol'],$aDadosDevedorSolidario[0]['nick_devsol'],$aDadosDevedorSolidario[0]['cod_logr'],$aDadosDevedorSolidario[0]['endereco_devsol'],$aDadosDevedorSolidario[0]['nrendereco_devsol'],$aDadosDevedorSolidario[0]['cpendereco_devsol'],$aDadosDevedorSolidario[0]['cod_bairro'],$aDadosDevedorSolidario[0]['cep_devsol'],$aDadosDevedorSolidario[0]['cod_uf'],$aDadosDevedorSolidario[0]['cod_municipio'],$aDadosDevedorSolidario[0]['telefone_devsol'],$aDadosDevedorSolidario[0]['cpf_devsol'],$aDadosDevedorSolidario[0]['sexo_devsol'],$aDadosDevedorSolidario[0]['cod_pais'],$_POST['frm_cod_ppnt']);
				}
			}
			
			$cod_vend = $_POST['frm_cod_vend'];
			
			if ($sAcao == 'addVend') {
				$aDadosVendedor[0]['tipo_vend'] = $_POST['vend_tipo'];
				$aDadosVendedor[0]['nome_vend'] = $_POST['vend_nome'];
				$aDadosVendedor[0]['nick_vend'] = $_POST['vend_nabrev'];
				$aDadosVendedor[0]['endereco_vend'] = $_POST['vend_ender'];
				$aDadosVendedor[0]['nrendereco_vend'] = $_POST['vend_num'];
				$aDadosVendedor[0]['cep_vend'] = utils::limpaNumeros($_POST['vend_cep']);
				$aDadosVendedor[0]['telefone_vend'] = utils::limpaNumeros($_POST['vend_fone']);
				$aDadosVendedor[0]['nrcc_vend'] = $_POST['vend_nrcc'];
				$aDadosVendedor[0]['dvcc_vend'] = $_POST['vend_dvcc'];
				$aDadosVendedor[0]['nrag_vend'] = $_POST['vend_nrag'];
				$aDadosVendedor[0]['nrcc2_vend'] = $_POST['vend_nrcc2'];
				$aDadosVendedor[0]['dvcc2_vend'] = $_POST['vend_dvcc2'];
				$aDadosVendedor[0]['nrag2_vend'] = $_POST['vend_nrag2'];
				$aDadosVendedor[0]['banco_vend'] = $_POST['vend_banco'];
				$aDadosVendedor[0]['qualificacao_vend'] = $_POST['qualificacao_vend'];
				$aDadosVendedor[0]['cod_bairro'] = $_POST['cod_bairro_vend'];
				$aDadosVendedor[0]['bairro_vend'] = $_POST['vend_bairro'];
				$aDadosVendedor[0]['cod_logr'] = $_POST['vend_logr'];
				$aDadosVendedor[0]['cod_uf'] = $_POST['cod_uf_vend'];
				$aDadosVendedor[0]['cod_municipio'] = $_POST['cod_municipio_vend'];
				$aDadosVendedor[0]['cpendereco_vend'] = $_POST['vend_compl'];
				$aDadosVendedor[0]['percentualvenda_vend'] = utils::moeda2db($_POST['vend_porcentagem']);
				$aDadosVendedor[0]['despachante_vend'] = $_POST['cod_despachante_vend'];
        $aDadosVendedor[0]['email_vend'] = $_POST['email_vend'];
        
				vendedor::inserir($cod_ppst,$aDadosVendedor[0]['tipo_vend'],$aDadosVendedor[0]['nome_vend'],$aDadosVendedor[0]['nick_vend'],$aDadosVendedor[0]['endereco_vend'],$aDadosVendedor[0]['nrendereco_vend'],$aDadosVendedor[0]['cep_vend'],$aDadosVendedor[0]['telefone_vend'],$aDadosVendedor[0]['nrcc_vend'],$aDadosVendedor[0]['dvcc_vend'],$aDadosVendedor[0]['nrag_vend'],$aDadosVendedor[0]['nrcc2_vend'],$aDadosVendedor[0]['dvcc2_vend'],$aDadosVendedor[0]['nrag2_vend'],$aDadosVendedor[0]['banco_vend'],$aDadosVendedor[0]['qualificacao_vend'],$aDadosVendedor[0]['cod_bairro'],$aDadosVendedor[0]['bairro_vend'],$aDadosVendedor[0]['cod_logr'],$aDadosVendedor[0]['cod_uf'],$aDadosVendedor[0]['cod_municipio'],$aDadosVendedor[0]['cpendereco_vend'],$aDadosVendedor[0]['percentualvenda_vend'],$aDadosVendedor[0]['despachante_vend'],$aDadosVendedor[0]['email_vend']);
				$cod_vend = $this->insertId;
			}
			
			
			if (($cod_vend != "" && ($aDadosProposta['situacao_ppst'] == "1" || ($aDadosProposta['situacao_ppst'] == "3" && $cLOGIN->iLEVEL_USUA == TPUSER_ATENDENTE) || $aDadosProposta['situacao_ppst'] == "5")) || (($_GET['corrigir']=='sim') && $cLOGIN->iLEVEL_USUA == TPUSER_ATENDENTE && $aDadosProposta['situacao_ppst'] >= 4  && $aDadosProposta["situacao_ppst"]<8)) {
				/////////////////////$_POST['vend_tipo'];
				$aDadosVendedor = vendedor::pesquisarPk($cod_ppst,$cod_vend);
				if ($_POST['vend_tipo'] != "") {
					
					$tipoVendedorAtual = $aDadosVendedor[0]['tipo_vend'];
					
					$aDadosVendedor[0]['tipo_vend'] = $_POST['vend_tipo'];
					$aDadosVendedor[0]['nome_vend'] = $_POST['vend_nome'];
					$aDadosVendedor[0]['nick_vend'] = $_POST['vend_nabrev'];
					$aDadosVendedor[0]['endereco_vend'] = $_POST['vend_ender'];
					$aDadosVendedor[0]['nrendereco_vend'] = $_POST['vend_num'];
					$aDadosVendedor[0]['cep_vend'] = utils::limpaNumeros($_POST['vend_cep']);
					$aDadosVendedor[0]['telefone_vend'] = utils::limpaNumeros($_POST['vend_fone']);
					$aDadosVendedor[0]['nrcc_vend'] = $_POST['vend_nrcc'];
					$aDadosVendedor[0]['dvcc_vend'] = $_POST['vend_dvcc'];
					$aDadosVendedor[0]['nrag_vend'] = $_POST['vend_nrag'];
					$aDadosVendedor[0]['nrcc2_vend'] = $_POST['vend_nrcc2'];
					$aDadosVendedor[0]['dvcc2_vend'] = $_POST['vend_dvcc2'];
					$aDadosVendedor[0]['nrag2_vend'] = $_POST['vend_nrag2'];
					$aDadosVendedor[0]['banco_vend'] = $_POST['vend_banco'];
					$aDadosVendedor[0]['qualificacao_vend'] = $_POST['qualificacao_vend'];
					$aDadosVendedor[0]['cod_bairro'] = $_POST['cod_bairro_vend'];
					$aDadosVendedor[0]['bairro_vend'] = $_POST['vend_bairro'];
					$aDadosVendedor[0]['cod_logr'] = $_POST['vend_logr'];
					$aDadosVendedor[0]['cod_uf'] = $_POST['cod_uf_vend'];
					$aDadosVendedor[0]['cod_municipio'] = $_POST['cod_municipio_vend'];
					$aDadosVendedor[0]['cpendereco_vend'] = $_POST['vend_compl'];
					$aDadosVendedor[0]['percentualvenda_vend'] = utils::moeda2db($_POST['vend_porcentagem']);
					$aDadosVendedor[0]['despachante_vend'] = $_POST['cod_despachante_vend'];
					$aDadosVendedor[0]['email_vend'] = $_POST['email_vend'];
					
					vendedor::atualizarPk($aDadosVendedor[0]['cod_ppst'],$aDadosVendedor[0]['cod_vend'],$aDadosVendedor[0]['tipo_vend'],$aDadosVendedor[0]['nome_vend'],$aDadosVendedor[0]['nick_vend'],$aDadosVendedor[0]['endereco_vend'],$aDadosVendedor[0]['nrendereco_vend'],$aDadosVendedor[0]['cep_vend'],$aDadosVendedor[0]['telefone_vend'],$aDadosVendedor[0]['nrcc_vend'],$aDadosVendedor[0]['dvcc_vend'],$aDadosVendedor[0]['nrag_vend'],$aDadosVendedor[0]['nrcc2_vend'],$aDadosVendedor[0]['dvcc2_vend'],$aDadosVendedor[0]['nrag2_vend'],$aDadosVendedor[0]['banco_vend'],$aDadosVendedor[0]['qualificacao_vend'],$aDadosVendedor[0]['cod_bairro'],$aDadosVendedor[0]['bairro_vend'],$aDadosVendedor[0]['cod_logr'],$aDadosVendedor[0]['cod_uf'],$aDadosVendedor[0]['cod_municipio'],$aDadosVendedor[0]['cpendereco_vend'],$aDadosVendedor[0]['percentualvenda_vend'],$aDadosVendedor[0]['despachante_vend'],$aDadosVendedor[0]['email_vend']);
					
										
					if ($_POST['vend_tipo'] == '1') {
						if (($aDadosProposta['situacao_ppst'] == "3" && ($cLOGIN->iLEVEL_USUA == TPUSER_ATENDENTE || ($cLOGIN->iLEVEL_USUA == TPUSER_PROPONENTE && $aDadosVendedor[0]['despachante_vend'] == "") || ($cLOGIN->iLEVEL_USUA == TPUSER_DESPACHANTE && $aDadosVendedor[0]['despachante_vend'] == $cLOGIN->iID) )) || $aDadosProposta['situacao_ppst'] == "1") {
							clistvendfis::deletarPorPropostaVendedor($cod_ppst,$cod_vend);
							clistvendfisconjuge::deletarPorPropostaVendedor($cod_ppst,$cod_vend);
						}

						if ($tipoVendedorAtual == '2') {
							vendjursocio::deletarPorVendedor($cod_vend);
							vendjur::deletarPk($cod_ppst,$cod_vend);
						}
						vendfisconjugepacto::deletarPk($cod_vend,$cod_ppst);
						vendfisconjuge::deletarPk($cod_vend,$cod_ppst);
						vendfis::deletarPk($cod_ppst,$cod_vend);
						
						$cpf_vfisica = utils::limpaNumeros($_POST['vend_cpf']);
						$sexo_vfisica = $_POST['vend_sexo'];
						$dtnascimento_vfisica = utils::data2db($_POST['vend_nasc']);
						$natur_vfisica = $_POST['vend_natural'];
						$nrrg_vfisica = $_POST['vend_rg'];
						$dtrg_vfisica = utils::data2db($_POST['vend_dtrg']);
						$orgrg_vfisica = $_POST['vend_orgrg'];
						$nomeconj_vfisica = '';
						$nomepai_vfisica = $_POST['vend_npai'];
						$nomemae_vfisica = $_POST['vend_nmae'];
						$vlrenda_vfisica = utils::moeda2db($_POST['vend_rendim']);
						$nrinss_vfisica = $_POST['vend_inss'];
						$cod_pais = $_POST['vend_nacion'];
						$cod_tpdoc = $_POST['vend_tpdoc'];
						$cod_prof = $_POST['vend_profiss'];
						$cod_estciv = $_POST['vend_civil'];
						$despachante_vfisica = '';
						$flguniest_vfisica = $_POST['flguniest_vfisica'];
						$flganuente_vfisica = $_POST['flganuente_vfisica'];
						$profissao_vfisica = $_POST['profissao_vfisica'];
						$dtaquisimov_vfisica = utils::data2db($_POST['dtaquisimov_vfisica']);
						
						if(isset($_GET['corrigir'])){
						vendfis::atualizarPk($cpf_vfisica,$sexo_vfisica,$dtnascimento_vfisica,$natur_vfisica,$nrrg_vfisica,$dtrg_vfisica,$orgrg_vfisica,$nomeconj_vfisica,$nomepai_vfisica,$nomemae_vfisica,$vlrenda_vfisica,$nrinss_vfisica,$cod_pais,$cod_ppst,$cod_tpdoc,$cod_prof,$cod_estciv,$cod_vend,$despachante_vfisica,$flguniest_vfisica,$flganuente_vfisica,$profissao_vfisica,$dtaquisimov_vfisica);
						}
						else{
						vendfis::inserir($cpf_vfisica,$sexo_vfisica,$dtnascimento_vfisica,$natur_vfisica,$nrrg_vfisica,$dtrg_vfisica,$orgrg_vfisica,$nomeconj_vfisica,$nomepai_vfisica,$nomemae_vfisica,$vlrenda_vfisica,$nrinss_vfisica,$cod_pais,$cod_ppst,$cod_tpdoc,$cod_prof,$cod_estciv,$cod_vend,$despachante_vfisica,$flguniest_vfisica,$flganuente_vfisica,$profissao_vfisica,$dtaquisimov_vfisica);
						}
						
						
						$regimebens_vfcj = $_POST['vend_regimebens_ppcj'];
						$dtcasamento_vfcj = utils::data2db($_POST['vend_dtcasamento_ppcj']);
						$nome_vfcj = $_POST['vend_nome_ppcj'];
						$cod_pais = $_POST['vend_cod_pais_ppcj'];
						$cod_estciv = $_POST['vend_civil_ppcj'];
						$nrrg_vfcj = $_POST['vend_nrrg_ppcj'];
						$orgrg_vfcj = $_POST['vend_orgrg_ppcj'];
						$dtrg_vfcj = utils::data2db($_POST['vend_dtrg_ppcj']);
						$cpf_pccj = utils::limpaNumeros($_POST['vend_cpf_pccj']);
						$flgtrabalha_vfcj = $_POST['vend_flgtrabalha_ppcj'];
						$empresa_vfcj = $_POST['vend_empresa_ppcj'];
						$dtadmissaoemp_vfcj = utils::data2db($_POST['vend_dtadmissaoemp_ppcj']);
						$enderecoemp_vfcj = $_POST['vend_enderecoemp_ppcj'];
						$numeroemp_vfcj = $_POST['vend_numeroemp_ppcj'];
						$complementoemp_vfcj = $_POST['vend_complementoemp_ppcj'];
						$bairroemp_vfcj = $_POST['vend_bairroemp_ppcj'];
						$cidadeemp_vfcj = $_POST['vend_cidadeemp_ppcj'];
						$estadoemp_vfcj = $_POST['vend_estadoemp_ppcj'];
						$telefoneemp_vfcj = utils::limpaNumeros($_POST['vend_telefoneemp_ppcj']);
						$cargoemp_vfcj = $_POST['vend_cargoemp_ppcj'];
						$salarioemp_vfcj = utils::moeda2db($_POST['vend_salarioemp_ppcj']);
						$despachante_vfcj = '';
						
					if(($_GET['corrigir']=='sim') && $cLOGIN->iLEVEL_USUA == TPUSER_ATENDENTE && $aDadosProposta['situacao_ppst'] >= 4  && $aDadosProposta["situacao_ppst"]<8){
					vendfisconjuge::atualizarPk($cod_vend,$cod_ppst,$regimebens_vfcj,$dtcasamento_vfcj,$nome_vfcj,$cod_pais,$cod_estciv,$nrrg_vfcj,$orgrg_vfcj,$dtrg_vfcj,$cpf_pccj,$flgtrabalha_vfcj,$empresa_vfcj,$dtadmissaoemp_vfcj,$enderecoemp_vfcj,$numeroemp_vfcj,$complementoemp_vfcj,$bairroemp_vfcj,$cidadeemp_vfcj,$estadoemp_vfcj,$telefoneemp_vfcj,$cargoemp_vfcj,$salarioemp_vfcj,$despachante_vfcj);
					}
						
						vendfisconjuge::inserir($cod_vend,$cod_ppst,$regimebens_vfcj,$dtcasamento_vfcj,$nome_vfcj,$cod_pais,$cod_estciv,$nrrg_vfcj,$orgrg_vfcj,$dtrg_vfcj,$cpf_pccj,$flgtrabalha_vfcj,$empresa_vfcj,$dtadmissaoemp_vfcj,$enderecoemp_vfcj,$numeroemp_vfcj,$complementoemp_vfcj,$bairroemp_vfcj,$cidadeemp_vfcj,$estadoemp_vfcj,$telefoneemp_vfcj,$cargoemp_vfcj,$salarioemp_vfcj,$despachante_vfcj);
						

						
						$data_vcpa = utils::data2db($_POST['vend_data_pcpa']);
						$locallavracao_vcpa = $_POST['vend_locallavracao_pcpa'];
						$livro_vcpa = $_POST['vend_livro_pcpa'];
						$folha_vcpa = $_POST['vend_folha_pcpa'];
						$numeroregistro_vcpa = $_POST['vend_numeroregistro_pcpa'];
						
						vendfisconjugepacto::inserir($cod_vend,$cod_ppst,$data_vcpa,$locallavracao_vcpa,$livro_vcpa,$folha_vcpa,$numeroregistro_vcpa);

					} elseif ($_POST['vend_tipo'] == '2') {
						
						if ($tipoVendedorAtual == '1') {
							vendfisconjugepacto::deletarPk($cod_vend,$cod_ppst);
							vendfisconjuge::deletarPk($cod_vend,$cod_ppst);
							vendfis::deletarPk($cod_ppst,$cod_vend);
						}
						
						$cnpj_vjur = utils::limpaNumeros($_POST['vend_cnpj']);
						$isenpis_vjur = $_POST['vend_pispasep'];
						$isencofins_vjur = $_POST['vend_cofins'];
						$isencsll_vjur = $_POST['vend_csll'];
						$cod_cnae = $_POST['vend_cnae'];
						$despachante_vjur = '';
						$versaoestat_vjur = $_POST['versaoestat_vjur'];
						$dtestat_vjur = utils::data2db($_POST['dtestat_vjur']);
						$locestat_vjur = $_POST['locestat_vjur'];
						$nrregestat_vjur = $_POST['nrregestat_vjur'];
						$dtregestat_vjur = utils::data2db($_POST['dtregist_vjur']);
						$tipo_soc_vjur = $_POST['vend_tipo_soc'];
						$tipo_rep_vjur = $_POST['vend_tipo_rep'];

						$aDadosVendedorJur = vendjur::pesquisarPk($cod_ppst,$cod_vend);
						
						if ($aDadosVendedorJur) {
							vendjur::atualizarPk($cod_ppst,$cnpj_vjur,$isenpis_vjur,$isencofins_vjur,$isencsll_vjur,$cod_cnae,$cod_vend,$despachante_vjur,$versaoestat_vjur,$dtestat_vjur,$locestat_vjur,$nrregestat_vjur,$dtregestat_vjur,$tipo_soc_vjur,$tipo_rep_vjur);
						} else {
							vendjur::inserir($cod_ppst,$cnpj_vjur,$isenpis_vjur,$isencofins_vjur,$isencsll_vjur,$cod_cnae,$cod_vend,$despachante_vjur,$versaoestat_vjur,$dtestat_vjur,$locestat_vjur,$nrregestat_vjur,$dtregestat_vjur,$tipo_soc_vjur,$tipo_rep_vjur);
						}
						
						if ($sAcao == "saveSocio") {
							
							$cod_vjsoc = $_POST['frm_cod_socio'];
							$nome_vjsoc = $_POST['vend_s_nome'];
							$nick_vjsoc = $_POST['vend_s_nabrev'];
							$endereco_vjsoc = $_POST['vend_s_ender'];
							$nrendereco_vjsoc = $_POST['vend_s_num'];
							$cpendereco_vjsoc = $_POST['vend_s_compl'];
							$bairro_vjsoc = $_POST['bairro_vjsoc'];
							$cep_vjsoc = utils::limpaNumeros($_POST['vend_s_cep']);
							$telefone_vjsoc = utils::limpaNumeros($_POST['vend_s_fone']);
							$cpf_vjsoc = utils::limpaNumeros($_POST['vend_s_cpf']);
							$sexo_vjsoc = $_POST['vend_s_sexo'];
							$cod_pais = $_POST['vend_s_nacion'];
							$cod_logr = $_POST['vend_s_logr'];
							$cod_bairro = $_POST['vend_s_bairro'];
							$cod_uf = $_POST['vend_s_uf'];
							$cod_municipio = $_POST['vend_s_cidade'];
							$nrrg_vjsoc = $_POST['vend_s_nrrg'];
							$orgrg_vjsoc = $_POST['vend_s_orgrg'];
							$dtrg_vjsoc = utils::data2db($_POST['vend_s_dtrg']);
							$cargo_vjsoc = $_POST['vend_s_cargo'];
							$cod_estciv = $_POST['vend_s_estciv'];
							
							if ($_POST['frm_cod_socio']) {
								vendjursocio::atualizarPk($cod_ppst,$cod_vjsoc,$nome_vjsoc,$nick_vjsoc,$endereco_vjsoc,$nrendereco_vjsoc,$cpendereco_vjsoc,$bairro_vjsoc,$cep_vjsoc,$telefone_vjsoc,$cpf_vjsoc,$sexo_vjsoc,$cod_pais,$cod_logr,$cod_bairro,$cod_uf,$cod_municipio,$cod_vend,$nrrg_vjsoc,$orgrg_vjsoc,$dtrg_vjsoc,$cod_estciv,$cargo_vjsoc);
							} else {
								vendjursocio::inserir($cod_ppst,$nome_vjsoc,$nick_vjsoc,$endereco_vjsoc,$nrendereco_vjsoc,$cpendereco_vjsoc,$bairro_vjsoc,$cep_vjsoc,$telefone_vjsoc,$cpf_vjsoc,$sexo_vjsoc,$cod_pais,$cod_logr,$cod_bairro,$cod_uf,$cod_municipio,$cod_vend,$nrrg_vjsoc,$orgrg_vjsoc,$dtrg_vjsoc,$cod_estciv,$cargo_vjsoc);
							}
						}
					}
				}
			}

			$aDadosVendedorTelefone = array();
			$cTels = 0;
			for($iTels=1; $iTels<=3; $iTels++){
			  if($_POST['vend_fone_'.$iTels]!=''){
			    $aDadosVendedorTelefone[$cTels]['TELEFONE_VNTL'] = utils::limpaNumeros($_POST['vend_fone_'.$iTels]);;
			    $aDadosVendedorTelefone[$cTels]['TIPO_VNTL'] = $_POST['vend_tipofone_'.$iTels];
			    $cTels++;
			  }
			}
			vendtelefone::deletePorVend($cod_vend);
			foreach($aDadosVendedorTelefone as $vDadosVendedorTelefone){
			  vendtelefone::insert($cod_vend,$vDadosVendedorTelefone['TELEFONE_VNTL'],$vDadosVendedorTelefone['TIPO_VNTL']);
			}
			
			
			if (($_POST['frm_cod_ppnt'] != '' && ($aDadosProposta['situacao_ppst'] == "1" || ($aDadosProposta['situacao_ppst'] == "3" && ($cLOGIN->iLEVEL_USUA == TPUSER_ATENDENTE || $cLOGIN->iLEVEL_USUA == TPUSER_PROPONENTE)) || $aDadosProposta['situacao_ppst'] == "5" ))  || ($_POST['frm_cod_ppnt'] != '' && !(isset($_GET['corrigir'])))) {
			//--------------------------------------------------------\\
				$aProponente = proponente::pesquisarPk($_POST['frm_cod_ppnt'],$cod_ppst);
				clistproponente::deletarPorPropostaProponente($cod_ppst,$_POST['frm_cod_ppnt']);
				clistproponenteconjuge::deletarPorPropostaProponente($cod_ppst,$_POST['frm_cod_ppnt']);
				clistproponentefgts::deletarPorPropostaProponente($cod_ppst,$_POST['frm_cod_ppnt']);
				foreach ($_POST as $kPost => $vPost) {
						if (eregi("^clistproponente_[0-9]+",$kPost)) {
						$ok1 = $_POST['ppn_ckl_doc_ck_prop_'.$vPost] ? 'S' : '';
						$ok2 = $_POST['ppn_ckl_doc_ck_atend_'.$vPost] ? 'S' : '';
						$dtped = utils::data2db($_POST['ppn_ckl_doc_dt_ped_'.$vPost]);
						$dtemis = utils::data2db($_POST['ppn_ckl_doc_dt_emis_'.$vPost]);
						$obs = $_POST['ppn_ckl_doc_desc_'.$vPost];
						clistproponente::inserir($vPost,$cod_ppst,$_POST['frm_cod_ppnt'],$dtped,'',$dtemis,$ok1,$ok2,$obs,'');
					}
				}
				foreach ($_POST as $kPost => $vPost) {
					if (eregi("^clistproponenteconjuge_[0-9]+",$kPost)) {
						$ok1 = $_POST['ppc_ckl_doc_ck_prop_'.$vPost] ? 'S' : '';
						$ok2 = $_POST['ppc_ckl_doc_ck_atend_'.$vPost] ? 'S' : '';
						$dtped = utils::data2db($_POST['ppc_ckl_doc_dt_ped_'.$vPost]);
						$dtemis = utils::data2db($_POST['ppc_ckl_doc_dt_emis_'.$vPost]);
						$obs = $_POST['ppc_ckl_doc_desc_'.$vPost];
						clistproponenteconjuge::inserir($vPost,$cod_ppst,$_POST['frm_cod_ppnt'],$dtped,'',$dtemis,$ok1,$ok2,$obs,'');
					}
				}
				foreach ($_POST as $kPost => $vPost) {
						if (eregi("^clistproponentefgts_[0-9]+",$kPost)) {
						$ok1 = $_POST['pfg_ckl_doc_ck_prop_'.$vPost] ? 'S' : '';
						$ok2 = $_POST['pfg_ckl_doc_ck_atend_'.$vPost] ? 'S' : '';
						$dtped = utils::data2db($_POST['pfg_ckl_doc_dt_ped_'.$vPost]);
						$dtemis = utils::data2db($_POST['pfg_ckl_doc_dt_emis_'.$vPost]);
						$obs = $_POST['pfg_ckl_doc_desc_'.$vPost];
						clistproponentefgts::inserir($vPost,$cod_ppst,$_POST['frm_cod_ppnt'],$dtped,'',$dtemis,$ok1,$ok2,$obs,'');
					}
				}
			}

			if ($cod_vend && ((($aDadosProposta['situacao_ppst'] == "3" || $aDadosProposta['situacao_ppst'] == "5") && ($cLOGIN->iLEVEL_USUA == TPUSER_ATENDENTE || ($cLOGIN->iLEVEL_USUA == TPUSER_PROPONENTE && $aDadosVendedor[0]['despachante_vend'] == "") || ($cLOGIN->iLEVEL_USUA == TPUSER_DESPACHANTE && $aDadosVendedor[0]['despachante_vend'] == $cLOGIN->iID) )) || $aDadosProposta['situacao_ppst'] == "1") ) {
				$aDadosVendedor = vendedor::pesquisarPk($cod_ppst,$cod_vend);
				if ($aDadosVendedor[0]['tipo_vend'] == '1') {
					clistvendfis::deletarPorPropostaVendedor($cod_ppst,$cod_vend);
					clistvendfisconjuge::deletarPorPropostaVendedor($cod_ppst,$cod_vend);
					foreach ($_POST as $kPost => $vPost) {
						if (eregi("^clistvendfis_[0-9]+",$kPost)) {
							$ok1 = $_POST['vdf_ckl_doc_ck_prop_'.$vPost] ? 'S' : '';
							$ok2 = $_POST['vdf_ckl_doc_ck_atend_'.$vPost] ? 'S' : '';
							$dtped = utils::data2db($_POST['vdf_ckl_doc_dt_ped_'.$vPost]);
							$dtemis = utils::data2db($_POST['vdf_ckl_doc_dt_emis_'.$vPost]);
							$obs = $_POST['vdf_ckl_doc_desc_'.$vPost];
							clistvendfis::inserir($vPost,$cod_ppst,$cod_vend,$dtped,'',$dtemis,$ok1,$ok2,$obs,'');
						}
					}

					foreach ($_POST as $kPost => $vPost) {
						if (eregi("^clistvendfisconjuge_[0-9]+",$kPost)) {
							$ok1 = $_POST['vfc_ckl_doc_ck_prop_'.$vPost] ? 'S' : '';
							$ok2 = $_POST['vfc_ckl_doc_ck_atend_'.$vPost] ? 'S' : '';
							$dtped = utils::data2db($_POST['vfc_ckl_doc_dt_ped_'.$vPost]);
							$dtemis = utils::data2db($_POST['vfc_ckl_doc_dt_emis_'.$vPost]);
							$obs = $_POST['vfc_ckl_doc_desc_'.$vPost];
							clistvendfisconjuge::inserir($vPost,$cod_ppst,$cod_vend,$dtped,'',$dtemis,$ok1,$ok2,$obs,'');
						}
					}
				} elseif ($aDadosVendedor[0]['tipo_vend'] == '2') {
					clistvendjur::deletarPorPropostaVendedor($cod_ppst,$cod_vend);
					foreach ($_POST as $kPost => $vPost) {
						if (eregi("^clistvendjur_[0-9]+",$kPost)) {
							$ok1 = $_POST['vjr_ckl_doc_ck_prop_'.$vPost] ? 'S' : '';
							$ok2 = $_POST['vjr_ckl_doc_ck_atend_'.$vPost] ? 'S' : '';
							$dtped = utils::data2db($_POST['vjr_ckl_doc_dt_ped_'.$vPost]);
							$dtemis = utils::data2db($_POST['vjr_ckl_doc_dt_emis_'.$vPost]);
							$obs = $_POST['vjr_ckl_doc_desc_'.$vPost];
							clistvendjur::inserir($vPost,$cod_ppst,$cod_vend,$dtped,'',$dtemis,$ok1,$ok2,$obs,'');
						}
					}
				}
			}
			
			if (($aDadosProposta['situacao_ppst'] == "3" && ($cLOGIN->iLEVEL_USUA == TPUSER_ATENDENTE || ($cLOGIN->iLEVEL_USUA == TPUSER_PROPONENTE && $aDadosProposta['imovel']['despachante_imov'] == "") || ($cLOGIN->iLEVEL_USUA == TPUSER_DESPACHANTE && $aDadosProposta['imovel']['despachante_imov'] == $cLOGIN->iID) )) || $aDadosProposta['situacao_ppst'] == "1" || $aDadosProposta['situacao_ppst'] == "5") {
				foreach ($_POST as $kPost => $vPost) {
					if (eregi("^clistimovel_[0-9]+",$kPost)) {
						$ok1 = $_POST['imv_ckl_doc_ck_prop_'.$vPost] ? 'S' : '';
						$ok2 = $_POST['imv_ckl_doc_ck_atend_'.$vPost] ? 'S' : '';
						$dtped = utils::data2db($_POST['imv_ckl_doc_dt_ped_'.$vPost]);
						$dtemis = utils::data2db($_POST['imv_ckl_doc_dt_emis_'.$vPost]);
						$obs = $_POST['imv_ckl_doc_desc_'.$vPost];
						clistimovel::inserir($vPost,$cod_ppst,$dtped,'',$dtemis,$ok1,$ok2,$obs,'');
					}
				}
			}
			if ($aDadosProposta['situacao_ppst'] == "5" && $cLOGIN->iLEVEL_USUA == TPUSER_ATENDENTE) {
				foreach ($_POST as $kPost => $vPost) {
					if (eregi("^clistadvogado_[0-9]+",$kPost)) {
						$cod_clad = $vPost;
						
						$aClistAdv = clistadvogado::pesquisarPk($cod_clad);
						
						$ok1 = $_POST['adv_ckl_doc_ck_atend_'.$vPost] ? 'S' : '';
						$dtped = utils::data2db($_POST['adv_ckl_doc_dt_ped_'.$vPost]);
						$dtemis = utils::data2db($_POST['adv_ckl_doc_dt_emis_'.$vPost]);
						$obs = $_POST['adv_ckl_doc_desc_'.$vPost];
						
						clistadvogado::atualizarPk($cod_ppst,$cod_clad,$cLOGIN->iID,$aClistAdv[0]['documento_clad'],$aClistAdv[0]['entidade_clad'],$dtped,'',$dtemis,$ok1,$aClistAdv[0]['obsadvogado_clad'],$obs);
					}
				}
			}

		} elseif ((eregi("delVend",$sAcao)) && $aDadosProposta && $_POST['frm_cod_vend']) {
			$oHistorico->inserir($_POST['frm_cod_ppst'],date("Y-m-d H:i:s"),'Proposta alterada','1',$cLOGIN->iID);
			$cod_vend = $_POST['frm_cod_vend'];
			clistvendfisconjuge::deletarPorPropostaVendedor($cod_ppst,$cod_vend);
			clistvendfis::deletarPorPropostaVendedor($cod_ppst,$cod_vend);
			clistvendjur::deletarPorPropostaVendedor($cod_ppst,$cod_vend);
			vendjursocio::deletarPorVendedor($cod_vend);
			vendjur::deletarPk($cod_ppst,$cod_vend);
			vendfisconjugepacto::deletarPk($cod_vend,$cod_ppst);
			vendfisconjuge::deletarPk($cod_vend,$cod_ppst);
			vendfis::deletarPk($cod_ppst,$cod_vend);
			vendedor::deletarPk($cod_ppst,$cod_vend);
		} elseif ($sAcao == 'delSocio' && $aDadosProposta && $_POST['frm_cod_socio']) {
			$oHistorico->inserir($_POST['frm_cod_ppst'],date("Y-m-d H:i:s"),'Proposta alterada','1',$cLOGIN->iID);
			$cod_vjsoc = $_POST['frm_cod_socio'];
			vendjursocio::deletarPk($cod_vjsoc);
		} elseif ($sAcao == 'addCkAdv' && $cLOGIN->iLEVEL_USUA == TPUSER_JURIDICO) {
			$oHistorico->inserir($_POST['frm_cod_ppst'],date("Y-m-d H:i:s"),'Proposta alterada','1',$cLOGIN->iID);
			clistadvogado::inserir($cod_ppst,$cLOGIN->iID,$_POST['documento_ck_adv'],$_POST['entidades_ck_adv'],'','','','',$_POST['obs_ck_adv'],'');
		} elseif ($sAcao == 'delCkAdv' && $_POST['frm_cod_ck_adv'] != '' && $cLOGIN->iLEVEL_USUA == TPUSER_JURIDICO) {
			$oHistorico->inserir($_POST['frm_cod_ppst'],date("Y-m-d H:i:s"),'Proposta alterada','1',$cLOGIN->iID);
			clistadvogado::deletarPk($_POST['frm_cod_ck_adv']);
		} elseif ($sAcao == 'saveCkAdv' && $_POST['frm_cod_ck_adv'] != '' && $cLOGIN->iLEVEL_USUA == TPUSER_JURIDICO) {
			$oHistorico->inserir($_POST['frm_cod_ppst'],date("Y-m-d H:i:s"),'Proposta alterada','1',$cLOGIN->iID);
			clistadvogado::atualizarPk($cod_ppst,$_POST['frm_cod_ck_adv'],$cLOGIN->iID,$_POST['documento_ck_adv'],$_POST['entidades_ck_adv'],'','','','',$_POST['obs_ck_adv'],'');
		} elseif ($sAcao == 'aprovarImovel' && $cLOGIN->iLEVEL_USUA == TPUSER_ATENDENTE && $_POST['dtaprovacao_imov']) {
			$oHistorico->inserir($_POST['frm_cod_ppst'],date("Y-m-d H:i:s"),'Im�vel aprovado.','1',$cLOGIN->iID);
			
			$query = "Update proposta set indcancelamento_ppst= NULL where cod_ppst='".$_POST['frm_cod_ppst']."'";
			$result =mysql_query($query);
			
			$aDadosProposta['imovel']['tipo_imov'] = $_POST['tipo_imov'];
			$aDadosProposta['imovel']['area_imov'] = utils::limpaNumeros($_POST['area_imov']);
			$aDadosProposta['imovel']['tpconstrucao_imov'] = $_POST['tpconstrucao_imov'];
			$aDadosProposta['imovel']['tpcondominio_imov'] = $_POST['tpcondominio_imov'];
			$aDadosProposta['imovel']['qtsala_imov'] = $_POST['qtsala_imov'];
			$aDadosProposta['imovel']['qtquarto_imov'] = $_POST['qtquarto_imov'];
			$aDadosProposta['imovel']['qtbanh_imov'] = $_POST['qtbanh_imov'];
			$aDadosProposta['imovel']['qtgarag_imov'] = $_POST['qtgarag_imov'];
			$aDadosProposta['imovel']['qtpavim_imov'] = $_POST['qtpavim_imov'];
			$aDadosProposta['imovel']['qtdepemp_imov'] = $_POST['qtdepemp_imov'];
			$aDadosProposta['imovel']['estconserv_imov'] = $_POST['estconserv_imov'];
			$aDadosProposta['imovel']['estconspred_imov'] = $_POST['estconspred_imov'];
			$aDadosProposta['imovel']['endereco_imov'] = $_POST['endereco_imov'];
			$aDadosProposta['imovel']['nrendereco_imov'] = $_POST['nrendereco_imov'];
			$aDadosProposta['imovel']['cpendereco_imov'] = $_POST['cpendereco_imov'];
			$aDadosProposta['imovel']['cep_imov'] = utils::limpaNumeros($_POST['cep_imov']);
			$aDadosProposta['imovel']['tpimposto_imov'] = $_POST['tpimposto_imov'];
			$aDadosProposta['imovel']['vlavalsemgar_imov'] = utils::moeda2db($_POST['vlavalsemgar_imov']);
			$aDadosProposta['imovel']['vlavalgar_imov'] = utils::moeda2db($_POST['vlavalgar_imov']);
			$aDadosProposta['imovel']['vlavaliacao_imov'] = utils::moeda2db($_POST['vlavaliacao_imov']);
			$aDadosProposta['imovel']['dtavaliacao_imov'] = utils::data2db($_POST['dtavaliacao_imov']);
			
			$aDadosProposta['imovel']['cod_logr'] = $_POST['cod_logr_imov'];
			$aDadosProposta['imovel']['cod_bairro'] = $_POST['cod_bairro_imov'];
			$aDadosProposta['imovel']['cod_uf'] = $_POST['cod_uf_imov'];
			$aDadosProposta['imovel']['cod_municipio'] = $_POST['cod_municipio_imov'];
			$aDadosProposta['imovel']['tpmoradia_imov'] = $_POST['tpmoradia_imov'];
			$aDadosProposta['imovel']['terreo_imov'] = $_POST['terreo_imov'];
			$aDadosProposta['imovel']['tmbdspcndop_imov'] = $_POST['tmbdspcndop_imov'];
			$aDadosProposta['imovel']['incomb_imov'] = $_POST['incomb_imov'];
			$aDadosProposta['imovel']['ruralfav_imov'] = $_POST['ruralfav_imov'];
			$aDadosProposta['imovel']['emconstr_imov'] = $_POST['emconstr_imov'];
			$aDadosProposta['imovel']['aquispaimae_imov'] = $_POST['aquispaimae_imov'];
			$aDadosProposta['imovel']['possuiirmaos_imov'] = $_POST['possuiirmaos_imov'];
			$aDadosProposta['imovel']['andar_imov'] = $_POST['andar_imov'];
			$aDadosProposta['imovel']['pavimento_imov'] = $_POST['pavimento_imov'];
			$aDadosProposta['imovel']['tpapto_imov'] = $_POST['tipo_apartam'];
			$aDadosProposta['imovel']['flgbloco_imov'] = $_POST['bloco_imov'];
			$aDadosProposta['imovel']['flgcondominio_imov'] = $_POST['condominio_imov'];
			$aDadosProposta['imovel']['numbloco_imov'] = $_POST['numero_bloco_imov'];
			$aDadosProposta['imovel']['edificio_imov'] = $_POST['edificio_bloco_imov'];
			$aDadosProposta['imovel']['conjunto_imov'] = $_POST['conjunto_bloco_imov'];
			$aDadosProposta['imovel']['nrmatrgi_imov'] = $_POST['nrmatrgi_imov'];
			$aDadosProposta['imovel']['areautil_imov'] = utils::limpaNumeros($_POST['area_util']);
			$aDadosProposta['imovel']['areatotal_imov'] = utils::limpaNumeros($_POST['area_total']);
			$aDadosProposta['imovel']['vagasapto_imov'] = $_POST['vagas_garagem_imov'];
			$aDadosProposta['imovel']['isolado_imov'] = $_POST['isolado_imov'];
			$aDadosProposta['imovel']['nomecondominio_imov'] = $_POST['nome_condominio_imov'];
			$aDadosProposta['imovel']['despachante_imov'] = $_POST['cod_despachante_imov'];
			imovel::atualizarPk($aDadosProposta['imovel']['cod_ppst'],$aDadosProposta['imovel']['tipo_imov'],$aDadosProposta['imovel']['flgaprovacao_imov'],$aDadosProposta['imovel']['area_imov'],$aDadosProposta['imovel']['tpconstrucao_imov'],$aDadosProposta['imovel']['tpcondominio_imov'],$aDadosProposta['imovel']['qtsala_imov'],$aDadosProposta['imovel']['qtquarto_imov'],$aDadosProposta['imovel']['qtbanh_imov'],$aDadosProposta['imovel']['qtgarag_imov'],$aDadosProposta['imovel']['qtpavim_imov'],$aDadosProposta['imovel']['qtdepemp_imov'],$aDadosProposta['imovel']['estconserv_imov'],$aDadosProposta['imovel']['estconspred_imov'],$aDadosProposta['imovel']['nomecartrgi_imov'],$aDadosProposta['imovel']['nrmatrgi_imov'],$aDadosProposta['imovel']['nrlivrgi_imov'],$aDadosProposta['imovel']['nrfolhrgi_imov'],$aDadosProposta['imovel']['nrrgcompvend_imov'],$aDadosProposta['imovel']['nrrggar_imov'],$aDadosProposta['imovel']['endereco_imov'],$aDadosProposta['imovel']['nrendereco_imov'],$aDadosProposta['imovel']['cpendereco_imov'],$aDadosProposta['imovel']['cep_imov'],$aDadosProposta['imovel']['tpimposto_imov'],$aDadosProposta['imovel']['vlavaliacao_imov'],$aDadosProposta['imovel']['vlavalsemgar_imov'],$aDadosProposta['imovel']['vlavalgar_imov'],$aDadosProposta['imovel']['dtavaliacao_imov'],$aDadosProposta['imovel']['dtaprovacao_imov'],$aDadosProposta['imovel']['cod_logr'],$aDadosProposta['imovel']['cod_bairro'],$aDadosProposta['imovel']['cod_uf'],$aDadosProposta['imovel']['cod_municipio'],$aDadosProposta['imovel']['tpmoradia_imov'],$aDadosProposta['imovel']['terreo_imov'],$aDadosProposta['imovel']['tmbdspcndop_imov'],$aDadosProposta['imovel']['incomb_imov'],$aDadosProposta['imovel']['ruralfav_imov'],$aDadosProposta['imovel']['emconstr_imov'],$aDadosProposta['imovel']['aquispaimae_imov'],$aDadosProposta['imovel']['possuiirmaos_imov'],$aDadosProposta['imovel']['andar_imov'],$aDadosProposta['imovel']['pavimento_imov'],$aDadosProposta['imovel']['tpapto_imov'],$aDadosProposta['imovel']['flgbloco_imov'],$aDadosProposta['imovel']['numbloco_imov'],$aDadosProposta['imovel']['edificio_imov'],$aDadosProposta['imovel']['conjunto_imov'],$aDadosProposta['imovel']['areautil_imov'],$aDadosProposta['imovel']['areatotal_imov'],$aDadosProposta['imovel']['vagasapto_imov'],$aDadosProposta['imovel']['isolado_imov'],$aDadosProposta['imovel']['nomecondominio_imov'],$aDadosProposta['imovel']['despachante_imov'],$aDadosProposta['imovel']['flgcondominio_imov']);
			
			$this->setPropostaAprovImovelAtendente($cod_ppst,$_POST['dtaprovacao_imov']);
		} elseif ($sAcao == 'salvarEntrada') {
		$vlentrada=$_POST['vlentrada_ppnt'];
		$vlcompra=$_POST['vlcompra_2'];
		$vlfinan=utils::moeda2db($vlcompra)-utils::moeda2db($vlentrada);
		//echo $vlfinan;
			$query = "UPDATE proponente SET 
						VLFINSOL_PPNT = '".utils::moeda2db($vlfinan)."',
						VLENTRADA_PPNT = '".utils::moeda2db($vlentrada)."'
					WHERE 
						COD_PPST ='".$_POST['frm_cod_ppst']."'";
			$result =mysql_query($query);
		} elseif ($sAcao == 'CancImovel') {
			$oHistorico->inserir($_POST['frm_cod_ppst'],date("Y-m-d H:i:s"),'Aprova��o do Im�vel Cancelada.','1',$cLOGIN->iID);
			
			$query = "UPDATE imovel SET 
						DTAPROVACAO_IMOV = NULL
					WHERE 
						COD_PPST ='".$_POST['frm_cod_ppst']."'";
			$result =mysql_query($query);
			$query = "UPDATE proposta SET 
						INDCANCELAMENTO_PPST = 'IM'
					WHERE 
						COD_PPST ='".$_POST['frm_cod_ppst']."'";
			$result =mysql_query($query);
			
		} elseif ($sAcao == 'assinarContrato' && $cLOGIN->iLEVEL_USUA == TPUSER_JURIDICO && $_POST['dtasscontrato_ppst']) {
			$this->setPropostaDataAssinatura($cod_ppst,$_POST['dtasscontrato_ppst']);
			$query = "UPDATE proposta SET 
						SITUACAO_PPST='7',
						INDCANCELAMENTO_PPST = NULL
					WHERE 
						COD_PPST ='".$_POST['frm_cod_ppst']."'";
			$result =mysql_query($query);		
			$oHistorico->inserir($_POST['frm_cod_ppst'],date("Y-m-d H:i:s"),'Data de Assinatura do Contrato Agendada.','1',$cLOGIN->iID);
		} elseif ($sAcao == 'cancelarAssinatura' && $cLOGIN->iLEVEL_USUA == TPUSER_JURIDICO) {
			$oHistorico->inserir($_POST['frm_cod_ppst'],date("Y-m-d H:i:s"),'Agendamento de Assinatura de Contrato Cancelado.','1',$cLOGIN->iID);		
			
			$query = "UPDATE proposta SET 
						DTASSCONTRATO_PPST = NULL,
						DTAPROVACAO_PPST=NULL,
						SITUACAO_PPST='4'
					WHERE 
						COD_PPST ='".$_POST['frm_cod_ppst']."'";
			$result =mysql_query($query);		
		
		} elseif ($sAcao == 'registrarImovel' && $cLOGIN->iLEVEL_USUA == TPUSER_JURIDICO) {
			imovel::atualizarRegistro($_POST['frm_cod_ppst'],$_POST['nomecartrgi_imov'],$_POST['nrmatrgi_imov'],$_POST['nrlivrgi_imov'],$_POST['nrfolhrgi_imov'],$_POST['nrrgcompvend_imov'],$_POST['nrrggar_imov'],$_POST['dtokregistro_ppst']);
			$oHistorico->inserir($_POST['frm_cod_ppst'],date("Y-m-d H:i:s"),'Registro de Im�vel','1',$cLOGIN->iID);
		} elseif ($sAcao == 'cancRegImovel' && $cLOGIN->iLEVEL_USUA == TPUSER_JURIDICO) {
			$this->query = "
				UPDATE proposta SET situacao_ppst = '9', indcancelamento_ppst='CR' 
				WHERE cod_ppst = '".$_POST['frm_cod_ppst']."'
			";
			$this->query();

			$oHistorico->inserir($_POST['frm_cod_ppst'],date("Y-m-d H:i:s"),'Registro de Im�vel Cancelado','1',$cLOGIN->iID);
			//echo $sAcao;
		} elseif ($sAcao == 'parecerFinal' && $cLOGIN->iLEVEL_USUA == TPUSER_JURIDICO) {
			$oHistorico->inserir($_POST['frm_cod_ppst'],date("Y-m-d H:i:s"),$_POST['txtparecer'],'5',$cLOGIN->iID);
			$this->setPropostaStatus($cod_ppst,'11');
		}
		
		//$this->rollbackTransaction();
		$this->commitTransaction();
		
		return $mErrors;
		
	}
	
	
	
}
					
class regiao extends database {

	function regiao() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_regi,nome_regi,descr_regi,flgativo_regi
			FROM regiao
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_regi) {
		$this->query = "
			SELECT cod_regi,nome_regi,descr_regi,flgativo_regi
			FROM regiao
			WHERE cod_regi = '".mysql_real_escape_string($cod_regi)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_regi,$nome_regi,$descr_regi,$flgativo_regi) {
		$this->query = "
			UPDATE regiao SET 
			nome_regi = ".(!$nome_regi ? "NULL" : "'".mysql_real_escape_string($nome_regi)."'").",descr_regi = ".(!$descr_regi ? "NULL" : "'".mysql_real_escape_string($descr_regi)."'").",flgativo_regi = ".(!$flgativo_regi ? "NULL" : "'".mysql_real_escape_string($flgativo_regi)."'")."
			WHERE cod_regi = '".mysql_real_escape_string($cod_regi)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_regi) {
		$this->query = "
			DELETE FROM regiao 
			WHERE cod_regi = '".mysql_real_escape_string($cod_regi)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($nome_regi,$descr_regi,$flgativo_regi) {
		$this->query = "
			INSERT INTO regiao ( nome_regi,descr_regi,flgativo_regi ) VALUES (
				".(!$nome_regi ? "NULL" : "'".mysql_real_escape_string($nome_regi)."'").",".(!$descr_regi ? "NULL" : "'".mysql_real_escape_string($descr_regi)."'").",".(!$flgativo_regi ? "NULL" : "'".mysql_real_escape_string($flgativo_regi)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}
	
	function getListaRegiao() {
		$this->query = "
			SELECT
				cod_regi,
				nome_regi,
				descr_regi,
				flgativo_regi
			FROM
				regiao 
			WHERE 
				flgativo_regi < 9 
			ORDER BY 
				nome_regi,
				descr_regi
		";
		$this->query();
		return $this->qrdata;
	}
	
	function getRegiao($cod_regi) {
		$this->query = "
			SELECT
				cod_regi,
				nome_regi,
				descr_regi,
				flgativo_regi
			FROM
				regiao
			WHERE
				cod_regi = '".mysql_real_escape_string($cod_regi)."'
		";
		$this->query();
		return $this->qrdata;
	}
	
	function addRegiao($dadosRegiao) {
		$this->query = "
			INSERT INTO regiao (
				nome_regi,
				descr_regi,
				flgativo_regi
			) VALUES (
				'".mysql_real_escape_string($dadosRegiao['nome_regi'])."',
				'".mysql_real_escape_string($dadosRegiao['descr_regi'])."',
				'".mysql_real_escape_string($dadosRegiao['flgativo_regi'])."'
			)
		";
		return $this->query();
	}
	
	function delRegiao($cod_regi) {
		$this->query = "
			DELETE FROM regiao
			WHERE cod_regi = '".mysql_real_escape_string($cod_regi)."'
		";
		return $this->query();
	}
	
	function updRegiao($dadosRegiao) {
		$this->query = "
			UPDATE regiao SET 
				nome_regi = '".mysql_real_escape_string($dadosRegiao["nome_regi"])."',
				descr_regi = '".mysql_real_escape_string($dadosRegiao["descr_regi"])."',
				flgativo_regi = '".mysql_real_escape_string($dadosRegiao["flgativo_regi"])."'
			WHERE
				cod_regi = '".$dadosRegiao["cod_regi"]."'
		";
		return $this->query();
	}
	
	function getListaRegiaoMunicipio($cod_regi) {
		$this->query = "
			SELECT
				municipio.cod_uf,
				municipio.cod_municipio,
				municipio.nome_municipio
			FROM
				municipio, 
				regiaomunicipio as rgmn
			WHERE
				rgmn.cod_regi = '".mysql_real_escape_string($cod_regi)."' and 
				rgmn.cod_uf = municipio.cod_uf and 
				rgmn.cod_municipio = municipio.cod_municipio
			ORDER BY 
				municipio.cod_uf,
				municipio.nome_municipio
		";
		$this->query();
		return $this->qrdata;
	}
	
	function delRegiaoMunicipio($cod_regi, $cod_uf = false, $cod_municipio = false) {
		$this->query = "
			DELETE FROM regiaomunicipio
			WHERE
				cod_regi = '".mysql_real_escape_string($cod_regi)."'
				".(($cod_uf && $cod_municipio) ? " and cod_ud = '".mysql_real_escape_string($cod_uf)."' and cod_municipio = '".mysql_real_escape_string($cod_municipio)."'" : "")."
		";
		return $this->query();
	}
	
	function addRegiaoMunicipio($dadosRegiaoMunicipio) {
		$this->query = "
			INSERT INTO regiaomunicipio (
				cod_regi,
				cod_uf,
				cod_municipio
			) VALUES (
				'".mysql_real_escape_string($dadosRegiaoMunicipio["cod_regi"])."', 
				'".mysql_real_escape_string($dadosRegiaoMunicipio["cod_uf"])."', 
				'".mysql_real_escape_string($dadosRegiaoMunicipio["cod_municipio"])."'
			)
		";
		return $this->query();
	}

}
					
class regiaodespachante extends database {

	function regiaodespachante() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_regi,cod_usua
			FROM regiaodespachante
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_regi,$cod_usua) {
		$this->query = "
			SELECT cod_regi,cod_usua
			FROM regiaodespachante
			WHERE cod_regi = '".mysql_real_escape_string($cod_regi)."' and cod_usua = '".mysql_real_escape_string($cod_usua)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_regi,$cod_usua) {
		$this->query = "
			UPDATE regiaodespachante SET 
			cod_regi = ".(!$cod_regi ? "NULL" : "'".mysql_real_escape_string($cod_regi)."'").",cod_usua = ".(!$cod_usua ? "NULL" : "'".mysql_real_escape_string($cod_usua)."'")."
			WHERE cod_regi = '".mysql_real_escape_string($cod_regi)."' and cod_usua = '".mysql_real_escape_string($cod_usua)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_regi,$cod_usua) {
		$this->query = "
			DELETE FROM regiaodespachante 
			WHERE cod_regi = '".mysql_real_escape_string($cod_regi)."' and cod_usua = '".mysql_real_escape_string($cod_usua)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_regi,$cod_usua) {
		$this->query = "
			INSERT INTO regiaodespachante ( cod_regi,cod_usua ) VALUES (
				".(!$cod_regi ? "NULL" : "'".mysql_real_escape_string($cod_regi)."'").",".(!$cod_usua ? "NULL" : "'".mysql_real_escape_string($cod_usua)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class regiaomunicipio extends database {

	function regiaomunicipio() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_regi,cod_uf,cod_municipio
			FROM regiaomunicipio
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_regi,$cod_uf,$cod_municipio) {
		$this->query = "
			SELECT cod_regi,cod_uf,cod_municipio
			FROM regiaomunicipio
			WHERE cod_regi = '".mysql_real_escape_string($cod_regi)."' and cod_uf = '".mysql_real_escape_string($cod_uf)."' and cod_municipio = '".mysql_real_escape_string($cod_municipio)."'
		";
		$this->query();
		return $this->qrdata;
	}
	
	function atualizarPk($cod_regi,$cod_uf,$cod_municipio) {
		$this->query = "
			UPDATE regiaomunicipio SET 
			cod_regi = ".(!$cod_regi ? "NULL" : "'".mysql_real_escape_string($cod_regi)."'").",cod_uf = ".(!$cod_uf ? "NULL" : "'".mysql_real_escape_string($cod_uf)."'").",cod_municipio = ".(!$cod_municipio ? "NULL" : "'".mysql_real_escape_string($cod_municipio)."'")."
			WHERE cod_regi = '".mysql_real_escape_string($cod_regi)."' and cod_uf = '".mysql_real_escape_string($cod_uf)."' and cod_municipio = '".mysql_real_escape_string($cod_municipio)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_regi,$cod_uf,$cod_municipio) {
		$this->query = "
			DELETE FROM regiaomunicipio 
			WHERE cod_regi = '".mysql_real_escape_string($cod_regi)."' and cod_uf = '".mysql_real_escape_string($cod_uf)."' and cod_municipio = '".mysql_real_escape_string($cod_municipio)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_regi,$cod_uf,$cod_municipio) {
		$this->query = "
			INSERT INTO regiaomunicipio ( cod_regi,cod_uf,cod_municipio ) VALUES (
				".(!$cod_regi ? "NULL" : "'".mysql_real_escape_string($cod_regi)."'").",".(!$cod_uf ? "NULL" : "'".mysql_real_escape_string($cod_uf)."'").",".(!$cod_municipio ? "NULL" : "'".mysql_real_escape_string($cod_municipio)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class taxa extends database {

	function taxa() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_taxa,nome_taxa,descr_taxa,valor_taxa
			FROM taxa
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_taxa) {
		$this->query = "
			SELECT cod_taxa,nome_taxa,descr_taxa,valor_taxa
			FROM taxa
			WHERE cod_taxa = '".mysql_real_escape_string($cod_taxa)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_taxa,$nome_taxa,$descr_taxa,$valor_taxa) {
		$this->query = "
			UPDATE taxa SET 
			nome_taxa = ".(!$nome_taxa ? "NULL" : "'".mysql_real_escape_string($nome_taxa)."'").",descr_taxa = ".(!$descr_taxa ? "NULL" : "'".mysql_real_escape_string($descr_taxa)."'").",valor_taxa = ".(!$valor_taxa ? "NULL" : "'".mysql_real_escape_string($valor_taxa)."'")."
			WHERE cod_taxa = '".mysql_real_escape_string($cod_taxa)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_taxa) {
		$this->query = "
			DELETE FROM taxa 
			WHERE cod_taxa = '".mysql_real_escape_string($cod_taxa)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($nome_taxa,$descr_taxa,$valor_taxa) {
		$this->query = "
			INSERT INTO taxa ( nome_taxa,descr_taxa,valor_taxa ) VALUES (
				".(!$nome_taxa ? "NULL" : "'".mysql_real_escape_string($nome_taxa)."'").",".(!$descr_taxa ? "NULL" : "'".mysql_real_escape_string($descr_taxa)."'").",".(!$valor_taxa ? "NULL" : "'".mysql_real_escape_string($valor_taxa)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}
	
	function getListaTaxas() {
		$this->query = "
			SELECT
				cod_taxa,
				nome_taxa,
				descr_taxa,
				valor_taxa
			FROM
				taxa
			ORDER BY 
				nome_taxa,
				valor_taxa
		";
		$this->query();
		return $this->qrdata;
	}
	
	function getTaxa($cod_taxa) {
		$this->query = "
			SELECT
				cod_taxa,
				nome_taxa,
				descr_taxa,
				valor_taxa
			FROM
				taxa
			WHERE
				cod_taxa = '".mysql_real_escape_string($cod_taxa)."'
		";
		$this->query();
		return $this->qrdata;
	}
	
	function addTaxa($dadosTaxa) {
		$this->query = "";
		return $this->query();
	}
	
	function delTaxa($cod_taxa) {
		$this->query = "";
		return $this->query();
	}
	
	function updTaxa($dadosTaxa) {
		$this->query = "
			UPDATE taxa SET 
				nome_taxa = '".mysql_real_escape_string($dadosTaxa["nome_taxa"])."',
				descr_taxa = '".mysql_real_escape_string($dadosTaxa["descr_taxa"])."',
				valor_taxa = '".mysql_real_escape_string($dadosTaxa["valor_taxa"])."'
			WHERE
				cod_taxa = '".mysql_real_escape_string($dadosTaxa["cod_taxa"])."'
		";
		return $this->query();
	}

}
					
class template extends database {

	function template() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_tmpl,titulo_tmpl,descr_tmpl,flgativo_tmpl
			FROM template
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_tmpl) {
		$this->query = "
			SELECT cod_tmpl,titulo_tmpl,descr_tmpl,flgativo_tmpl
			FROM template
			WHERE cod_tmpl = '".mysql_real_escape_string($cod_tmpl)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_tmpl,$titulo_tmpl,$descr_tmpl,$flgativo_tmpl) {
		$this->query = "
			UPDATE template SET 
			titulo_tmpl = ".(!$titulo_tmpl ? "NULL" : "'".mysql_real_escape_string($titulo_tmpl)."'").",descr_tmpl = ".(!$descr_tmpl ? "NULL" : "'".mysql_real_escape_string($descr_tmpl)."'").",flgativo_tmpl = ".(!$flgativo_tmpl ? "NULL" : "'".mysql_real_escape_string($flgativo_tmpl)."'")."
			WHERE cod_tmpl = '".mysql_real_escape_string($cod_tmpl)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_tmpl) {
		$this->query = "
			DELETE FROM template 
			WHERE cod_tmpl = '".mysql_real_escape_string($cod_tmpl)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($titulo_tmpl,$descr_tmpl,$flgativo_tmpl) {
		$this->query = "
			INSERT INTO template ( titulo_tmpl,descr_tmpl,flgativo_tmpl ) VALUES (
				".(!$titulo_tmpl ? "NULL" : "'".mysql_real_escape_string($titulo_tmpl)."'").",".(!$descr_tmpl ? "NULL" : "'".mysql_real_escape_string($descr_tmpl)."'").",".(!$flgativo_tmpl ? "NULL" : "'".mysql_real_escape_string($flgativo_tmpl)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class templateconteudo extends database {

	function templateconteudo() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_tmpl,cod_cotd,ordem_tpco
			FROM templateconteudo
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_tmpl,$cod_cotd) {
		$this->query = "
			SELECT cod_tmpl,cod_cotd,ordem_tpco
			FROM templateconteudo
			WHERE cod_tmpl = '".mysql_real_escape_string($cod_tmpl)."' and cod_cotd = '".mysql_real_escape_string($cod_cotd)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_tmpl,$cod_cotd,$ordem_tpco) {
		$this->query = "
			UPDATE templateconteudo SET 
			cod_tmpl = ".(!$cod_tmpl ? "NULL" : "'".mysql_real_escape_string($cod_tmpl)."'").",cod_cotd = ".(!$cod_cotd ? "NULL" : "'".mysql_real_escape_string($cod_cotd)."'").",ordem_tpco = ".(!$ordem_tpco ? "NULL" : "'".mysql_real_escape_string($ordem_tpco)."'")."
			WHERE cod_tmpl = '".mysql_real_escape_string($cod_tmpl)."' and cod_cotd = '".mysql_real_escape_string($cod_cotd)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_tmpl,$cod_cotd) {
		$this->query = "
			DELETE FROM templateconteudo 
			WHERE cod_tmpl = '".mysql_real_escape_string($cod_tmpl)."' and cod_cotd = '".mysql_real_escape_string($cod_cotd)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_tmpl,$cod_cotd,$ordem_tpco) {
		$this->query = "
			INSERT INTO templateconteudo ( cod_tmpl,cod_cotd,ordem_tpco ) VALUES (
				".(!$cod_tmpl ? "NULL" : "'".mysql_real_escape_string($cod_tmpl)."'").",".(!$cod_cotd ? "NULL" : "'".mysql_real_escape_string($cod_cotd)."'").",".(!$ordem_tpco ? "NULL" : "'".mysql_real_escape_string($ordem_tpco)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class tipoconteudo extends database {

	function tipoconteudo() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_tpco,titulo_tpco,descr_tpco
			FROM tipoconteudo
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_tpco) {
		$this->query = "
			SELECT cod_tpco,titulo_tpco,descr_tpco
			FROM tipoconteudo
			WHERE cod_tpco = '".mysql_real_escape_string($cod_tpco)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_tpco,$titulo_tpco,$descr_tpco) {
		$this->query = "
			UPDATE tipoconteudo SET 
			titulo_tpco = ".(!$titulo_tpco ? "NULL" : "'".mysql_real_escape_string($titulo_tpco)."'").",descr_tpco = ".(!$descr_tpco ? "NULL" : "'".mysql_real_escape_string($descr_tpco)."'")."
			WHERE cod_tpco = '".mysql_real_escape_string($cod_tpco)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_tpco) {
		$this->query = "
			DELETE FROM tipoconteudo 
			WHERE cod_tpco = '".mysql_real_escape_string($cod_tpco)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($titulo_tpco,$descr_tpco) {
		$this->query = "
			INSERT INTO tipoconteudo ( titulo_tpco,descr_tpco ) VALUES (
				".(!$titulo_tpco ? "NULL" : "'".mysql_real_escape_string($titulo_tpco)."'").",".(!$descr_tpco ? "NULL" : "'".mysql_real_escape_string($descr_tpco)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class tipodoc extends database {

	function tipodoc() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_tpdoc,desc_tpdoc
			FROM tipodoc
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_tpdoc) {
		$this->query = "
			SELECT cod_tpdoc,desc_tpdoc
			FROM tipodoc
			WHERE cod_tpdoc = '".mysql_real_escape_string($cod_tpdoc)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_tpdoc,$desc_tpdoc) {
		$this->query = "
			UPDATE tipodoc SET 
			cod_tpdoc = ".(!$cod_tpdoc ? "NULL" : "'".mysql_real_escape_string($cod_tpdoc)."'").",desc_tpdoc = ".(!$desc_tpdoc ? "NULL" : "'".mysql_real_escape_string($desc_tpdoc)."'")."
			WHERE cod_tpdoc = '".mysql_real_escape_string($cod_tpdoc)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_tpdoc) {
		$this->query = "
			DELETE FROM tipodoc 
			WHERE cod_tpdoc = '".mysql_real_escape_string($cod_tpdoc)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_tpdoc,$desc_tpdoc) {
		$this->query = "
			INSERT INTO tipodoc ( cod_tpdoc,desc_tpdoc ) VALUES (
				".(!$cod_tpdoc ? "NULL" : "'".mysql_real_escape_string($cod_tpdoc)."'").",".(!$desc_tpdoc ? "NULL" : "'".mysql_real_escape_string($desc_tpdoc)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}
	
	function getListaTipoDoc($cod_tpdoc=false) {
		$sqlCompl = ($cod_tpdoc)?" WHERE cod_tpdoc='".mysql_real_escape_string($cod_tpdoc)."' ":"";
		$this->query = "
			SELECT
				cod_tpdoc,
				desc_tpdoc
			FROM
				tipodoc 
			$sqlCompl
			ORDER BY 
				desc_tpdoc
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class uf extends database {

	function uf() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_uf,nome_uf
			FROM uf
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_uf) {
		$this->query = "
			SELECT cod_uf,nome_uf
			FROM uf
			WHERE cod_uf = '".mysql_real_escape_string($cod_uf)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_uf,$nome_uf) {
		$this->query = "
			UPDATE uf SET 
			cod_uf = ".(!$cod_uf ? "NULL" : "'".mysql_real_escape_string($cod_uf)."'").",nome_uf = ".(!$nome_uf ? "NULL" : "'".mysql_real_escape_string($nome_uf)."'")."
			WHERE cod_uf = '".mysql_real_escape_string($cod_uf)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_uf) {
		$this->query = "
			DELETE FROM uf 
			WHERE cod_uf = '".mysql_real_escape_string($cod_uf)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_uf,$nome_uf) {
		$this->query = "
			INSERT INTO uf ( cod_uf,nome_uf ) VALUES (
				".(!$cod_uf ? "NULL" : "'".mysql_real_escape_string($cod_uf)."'").",".(!$nome_uf ? "NULL" : "'".mysql_real_escape_string($nome_uf)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}

class usuario extends database {

	function usuario() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_usua,nome_usua,email_usua,pwd_usua,level_usua,id_lstn,flgstatus_usua,tentativasacesso_usua,dtbloqueio_usua,sessao_usua
			FROM usuario
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_usua) {
		$this->query = "
			SELECT cod_usua,nome_usua,email_usua,pwd_usua,level_usua,id_lstn,flgstatus_usua,tentativasacesso_usua,dtbloqueio_usua,sessao_usua
			FROM usuario
			WHERE cod_usua = '".mysql_real_escape_string($cod_usua)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPorNome($nome) {
		$this->query = "
			SELECT cod_usua,nome_usua,email_usua,pwd_usua,level_usua,id_lstn,flgstatus_usua,tentativasacesso_usua,dtbloqueio_usua,sessao_usua
			FROM usuario
			WHERE nome_usua like '%".mysql_real_escape_string($nome)."%'
		";
		$this->query();
		//echo $this->query;
		return $this->qrdata;
	}

	function atualizarPk($cod_usua,$nome_usua,$email_usua,$pwd_usua,$level_usua,$id_lstn,$flgstatus_usua,$tentativasacesso_usua,$dtbloqueio_usua,$sessao_usua) {
		$this->query = "
			UPDATE usuario SET 
			nome_usua = ".(!$nome_usua ? "NULL" : "'".mysql_real_escape_string($nome_usua)."'").",email_usua = ".(!$email_usua ? "NULL" : "'".mysql_real_escape_string($email_usua)."'").",pwd_usua = ".(!$pwd_usua ? "NULL" : "'".mysql_real_escape_string($pwd_usua)."'").",level_usua = ".(!$level_usua ? "NULL" : "'".mysql_real_escape_string($level_usua)."'").",id_lstn = ".(!$id_lstn ? "NULL" : "'".mysql_real_escape_string($id_lstn)."'").",flgstatus_usua = ".(!$flgstatus_usua ? "NULL" : "'".mysql_real_escape_string($flgstatus_usua)."'").",tentativasacesso_usua = ".(!$tentativasacesso_usua ? "NULL" : "'".mysql_real_escape_string($tentativasacesso_usua)."'").",dtbloqueio_usua = ".(!$dtbloqueio_usua ? "NULL" : "'".mysql_real_escape_string($dtbloqueio_usua)."'").",sessao_usua = ".(!$sessao_usua ? "NULL" : "'".mysql_real_escape_string($sessao_usua)."'")."
			WHERE cod_usua = '".mysql_real_escape_string($cod_usua)."'
		";
		$this->query();
		return $this->qrdata;
	}
	
	function atualizarNome($cod_usua,$nome_usua) {
		$this->query = "
			UPDATE usuario SET 
			nome_usua = ".(!$nome_usua ? "NULL" : "'".mysql_real_escape_string($nome_usua)."'")."
			WHERE cod_usua = '".mysql_real_escape_string($cod_usua)."'
		";
	//	echo $this->query;
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_usua) {
		$this->query = "
			DELETE FROM usuario 
			WHERE cod_usua = '".mysql_real_escape_string($cod_usua)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($nome_usua,$email_usua,$pwd_usua,$level_usua,$id_lstn,$flgstatus_usua,$tentativasacesso_usua,$dtbloqueio_usua,$sessao_usua) {
		$this->query = "
			INSERT INTO usuario ( nome_usua,email_usua,pwd_usua,level_usua,id_lstn,flgstatus_usua,tentativasacesso_usua,dtbloqueio_usua,sessao_usua ) VALUES (
				".(!$nome_usua ? "NULL" : "'".mysql_real_escape_string($nome_usua)."'").",".(!$email_usua ? "NULL" : "'".mysql_real_escape_string($email_usua)."'").",".(!$pwd_usua ? "NULL" : "'".mysql_real_escape_string($pwd_usua)."'").",".(!$level_usua ? "NULL" : "'".mysql_real_escape_string($level_usua)."'").",".(!$id_lstn ? "NULL" : "'".mysql_real_escape_string($id_lstn)."'").",".(!$flgstatus_usua ? "NULL" : "'".mysql_real_escape_string($flgstatus_usua)."'").",".(!$tentativasacesso_usua ? "NULL" : "'".mysql_real_escape_string($tentativasacesso_usua)."'").",".(!$dtbloqueio_usua ? "NULL" : "'".mysql_real_escape_string($dtbloqueio_usua)."'").",".(!$sessao_usua ? "NULL" : "'".mysql_real_escape_string($sessao_usua)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}
	
	function pesquisarPorMatricula($id_lstn) {
		$utils = new utils();
		$id_lstn = $utils->limpaCPF($id_lstn);
		
		$this->query = "
			SELECT cod_usua,nome_usua,email_usua,pwd_usua,level_usua,id_lstn,flgstatus_usua,tentativasacesso_usua,dtbloqueio_usua,sessao_usua
			FROM usuario
			WHERE id_lstn = '".mysql_real_escape_string($id_lstn)."'
		";
		//echo $this->query;
		$this->query();
		return $this->qrdata;
	}
	function pesquisarPorEmail($email_usua) {
		$this->query = "
			SELECT cod_usua,nome_usua,email_usua,pwd_usua,level_usua,id_lstn,flgstatus_usua,tentativasacesso_usua,dtbloqueio_usua,sessao_usua
			FROM usuario
			WHERE email_usua = '".mysql_real_escape_string($email_usua)."'
		";
		$this->query();
		return $this->qrdata;
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
				dtbloqueio_usua,
				tentativasacesso_usua,
				sessao_usua,
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
	
	function altCadEmail($email,$cod_usua) {
		 $this->query = "
			UPDATE usuario SET 
				email_usua = '".mysql_real_escape_string($email)."'
				WHERE
				cod_usua = '".mysql_real_escape_string($cod_usua)."'
		";
		return $this->query();
	}
	
	function altCadSenha($senha,$cod_usua) {	
		 $this->query = "
			UPDATE usuario SET 
				PWD_USUA  = '".mysql_real_escape_string($senha)."'
				WHERE
				cod_usua = '".mysql_real_escape_string($cod_usua)."'
		";
		return $this->query();
	}
		function altCadMatricula($matricula,$dadosUsuario) {
		
		$this->query = "UPDATE usuario SET ID_LSTN = NULL WHERE cod_usua = '".mysql_real_escape_string($dadosUsuario[0]['cod_usua'])."'";
		$this->query();
		
		$this->query = "
				UPDATE listadenomes SET 
					id_lstn = '".mysql_real_escape_string($matricula)."'
					WHERE
					id_lstn = '".mysql_real_escape_string($dadosUsuario[0]['id_lstn'])."'";
		$this->query();		
		
		$this->query = "UPDATE usuario SET ID_LSTN = '".mysql_real_escape_string($matricula)."' WHERE cod_usua = '".mysql_real_escape_string($dadosUsuario[0]['cod_usua'])."'";
		$this->query();

		echo mysql_error();	
		
		
	}
	
	function getCodPropostabyProponente($cod_usua) {
		
		$this->query = "SELECT COD_PPST FROM proponente WHERE COD_PROPONENTE='$cod_usua'";
		$this->query();
		
		return $this->qrdata[0]['COD_PPST'];
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
	
	function getListaDsespachantes($uf,$municipio){
		$this->query = "
			SELECT
			  u.cod_usua, u.nome_usua
			FROM
			  usuario u,
			  despachante dp,
			  regiaodespachante d,
			  regiaomunicipio m,
			  regiao r
			WHERE
					u.cod_usua = dp.cod_usua
				AND r.cod_regi = m.cod_regi
				AND r.cod_regi = d.cod_regi
				AND d.cod_usua = dp.cod_usua
				AND m.cod_uf='".mysql_real_escape_string($uf)."'
				AND m.cod_municipio='".mysql_real_escape_string($municipio)."'
				AND r.flgativo_regi=1
		";
		$this->query();
		return $this->qrdata;
	}

	function getListaMunicipiosDespachante($cod_usua) {
		$this->query = "
			SELECT m.cod_uf, m.cod_municipio, m.nome_municipio
			FROM
				regiaodespachante d,
				regiaomunicipio r,
				municipio m
			WHERE
					d.cod_regi      = r.cod_regi
				AND r.cod_uf        = m.cod_uf
				AND r.cod_municipio = m.cod_municipio
				AND d.cod_usua      = '".mysql_real_escape_string($cod_usua)."'
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class vendedor extends database {

	function vendedor() {
		
	}
	
	function listar() {
		 $this->query = "
			SELECT cod_ppst,cod_vend,tipo_vend,nome_vend,nick_vend,endereco_vend,nrendereco_vend,cep_vend,telefone_vend,nrcc_vend,dvcc_vend,nrag_vend,nrcc2_vend,dvcc2_vend,nrag2_vend,banco_vend,qualificacao_vend,cod_bairro,bairro_vend,cod_logr,cod_uf,cod_municipio,cpendereco_vend,percentualvenda_vend,despachante_vend,email_vend
			FROM vendedor
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_ppst,$cod_vend) {
		 $this->query = "
			SELECT cod_ppst,cod_vend,tipo_vend,nome_vend,nick_vend,endereco_vend,nrendereco_vend,cep_vend,telefone_vend,nrcc_vend,dvcc_vend,nrag_vend,nrcc2_vend,dvcc2_vend,nrag2_vend,banco_vend,qualificacao_vend,cod_bairro,bairro_vend,cod_logr,cod_uf,cod_municipio,cpendereco_vend,percentualvenda_vend,despachante_vend,email_vend
			FROM vendedor
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_vend = '".mysql_real_escape_string($cod_vend)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPorDespachante($despachante) {
		$this->query = "
			SELECT cod_ppst,cod_vend,tipo_vend,nome_vend,nick_vend,endereco_vend,nrendereco_vend,cep_vend,telefone_vend,nrcc_vend,dvcc_vend,nrag_vend,nrcc2_vend,dvcc2_vend,nrag2_vend,banco_vend,qualificacao_vend,cod_bairro,bairro_vend,cod_logr,cod_uf,cod_municipio,cpendereco_vend,percentualvenda_vend,despachante_vend,email_vend
			FROM vendedor
			WHERE despachante_vend = '".mysql_real_escape_string($despachante)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_ppst,$cod_vend,$tipo_vend,$nome_vend,$nick_vend,$endereco_vend,$nrendereco_vend,$cep_vend,$telefone_vend,$nrcc_vend,$dvcc_vend,$nrag_vend,$nrcc2_vend,$dvcc2_vend,$nrag2_vend,$banco_vend,$qualificacao_vend,$cod_bairro,$bairro_vend,$cod_logr,$cod_uf,$cod_municipio,$cpendereco_vend,$percentualvenda_vend,$despachante_vend,$email_vend) {
		$this->query="Insert into excvend (cod_ppst) values ('".$cod_ppst."')";
		$this->query();
		 $this->query = "
			UPDATE vendedor SET 
			cod_ppst = ".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",tipo_vend = ".(!$tipo_vend ? "NULL" : "'".mysql_real_escape_string($tipo_vend)."'").",nome_vend = ".(!$nome_vend ? "NULL" : "'".mysql_real_escape_string($nome_vend)."'").",nick_vend = ".(!$nick_vend ? "NULL" : "'".mysql_real_escape_string($nick_vend)."'").",endereco_vend = ".(!$endereco_vend ? "NULL" : "'".mysql_real_escape_string($endereco_vend)."'").",nrendereco_vend = ".(!$nrendereco_vend ? "0" : "'".mysql_real_escape_string($nrendereco_vend)."'").",cep_vend = ".(!$cep_vend ? "NULL" : "'".mysql_real_escape_string($cep_vend)."'").",telefone_vend = ".(!$telefone_vend ? "NULL" : "'".mysql_real_escape_string($telefone_vend)."'").",nrcc_vend = ".(!$nrcc_vend ? "NULL" : "'".mysql_real_escape_string($nrcc_vend)."'").",dvcc_vend = ".(!$dvcc_vend ? "NULL" : "'".mysql_real_escape_string($dvcc_vend)."'").",nrag_vend = ".(!$nrag_vend ? "NULL" : "'".mysql_real_escape_string($nrag_vend)."'").",nrcc2_vend = ".(!$nrcc2_vend ? "NULL" : "'".mysql_real_escape_string($nrcc2_vend)."'").",dvcc2_vend = ".(!$dvcc2_vend ? "NULL" : "'".mysql_real_escape_string($dvcc2_vend)."'").",nrag2_vend = ".(!$nrag2_vend ? "NULL" : "'".mysql_real_escape_string($nrag2_vend)."'").",banco_vend = ".(!$banco_vend ? "NULL" : "'".mysql_real_escape_string($banco_vend)."'").",qualificacao_vend = ".(!$qualificacao_vend ? "NULL" : "'".mysql_real_escape_string($qualificacao_vend)."'").",cod_bairro = ".(!$cod_bairro ? "NULL" : "'".mysql_real_escape_string($cod_bairro)."'").",bairro_vend = ".(!$bairro_vend ? "NULL" : "'".mysql_real_escape_string($bairro_vend)."'").",cod_logr = ".(!$cod_logr ? "NULL" : "'".mysql_real_escape_string($cod_logr)."'").",cod_uf = ".(!$cod_uf ? "NULL" : "'".mysql_real_escape_string($cod_uf)."'").",cod_municipio = ".(!$cod_municipio ? "NULL" : "'".mysql_real_escape_string($cod_municipio)."'").",cpendereco_vend = ".(!$cpendereco_vend ? "NULL" : "'".mysql_real_escape_string($cpendereco_vend)."'").",percentualvenda_vend = ".(!$percentualvenda_vend ? "NULL" : "'".mysql_real_escape_string($percentualvenda_vend)."'").",despachante_vend = ".(!$despachante_vend ? "NULL" : "'".mysql_real_escape_string($despachante_vend)."'").",email_vend = ".(!$email_vend ? "NULL" : "'".mysql_real_escape_string($email_vend)."'")."
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_vend = '".mysql_real_escape_string($cod_vend)."'
		";
		$this->query();
		echo mysql_error();
		return $this->qrdata;
	}

	function deletarPk($cod_ppst,$cod_vend) {
		$this->query = "
			DELETE FROM vendedor 
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_vend = '".mysql_real_escape_string($cod_vend)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_ppst,$tipo_vend,$nome_vend,$nick_vend,$endereco_vend,$nrendereco_vend,$cep_vend,$telefone_vend,$nrcc_vend,$dvcc_vend,$nrag_vend,$nrcc2_vend,$dvcc2_vend,$nrag2_vend,$banco_vend,$qualificacao_vend,$cod_bairro,$bairro_vend,$cod_logr,$cod_uf,$cod_municipio,$cpendereco_vend,$percentualvenda_vend,$despachante_vend,$email_vend) {
		$this->query = "
			INSERT INTO vendedor ( cod_ppst,tipo_vend,nome_vend,nick_vend,endereco_vend,nrendereco_vend,cep_vend,telefone_vend,nrcc_vend,dvcc_vend,nrag_vend,nrcc2_vend,dvcc2_vend,nrag2_vend,banco_vend,qualificacao_vend,cod_bairro,bairro_vend,cod_logr,cod_uf,cod_municipio,cpendereco_vend,percentualvenda_vend,despachante_vend,email_vend ) VALUES (
				".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",".(!$tipo_vend ? "NULL" : "'".mysql_real_escape_string($tipo_vend)."'").",".(!$nome_vend ? "NULL" : "'".mysql_real_escape_string($nome_vend)."'").",".(!$nick_vend ? "NULL" : "'".mysql_real_escape_string($nick_vend)."'").",".(!$endereco_vend ? "NULL" : "'".mysql_real_escape_string($endereco_vend)."'").",".(!$nrendereco_vend ? "0" : "'".mysql_real_escape_string($nrendereco_vend)."'").",".(!$cep_vend ? "NULL" : "'".mysql_real_escape_string($cep_vend)."'").",".(!$telefone_vend ? "NULL" : "'".mysql_real_escape_string($telefone_vend)."'").",".(!$nrcc_vend ? "NULL" : "'".mysql_real_escape_string($nrcc_vend)."'").",".(!$dvcc_vend ? "NULL" : "'".mysql_real_escape_string($dvcc_vend)."'").",".(!$nrag_vend ? "NULL" : "'".mysql_real_escape_string($nrag_vend)."'").",".(!$nrcc2_vend ? "NULL" : "'".mysql_real_escape_string($nrcc2_vend)."'").",".(!$dvcc2_vend ? "NULL" : "'".mysql_real_escape_string($dvcc2_vend)."'").",".(!$nrag2_vend ? "NULL" : "'".mysql_real_escape_string($nrag2_vend)."'").",".(!$banco_vend ? "NULL" : "'".mysql_real_escape_string($banco_vend)."'").",".(!$qualificacao_vend ? "NULL" : "'".mysql_real_escape_string($qualificacao_vend)."'").",".(!$cod_bairro ? "NULL" : "'".mysql_real_escape_string($cod_bairro)."'").",".(!$bairro_vend ? "NULL" : "'".mysql_real_escape_string($bairro_vend)."'").",".(!$cod_logr ? "NULL" : "'".mysql_real_escape_string($cod_logr)."'").",".(!$cod_uf ? "NULL" : "'".mysql_real_escape_string($cod_uf)."'").",".(!$cod_municipio ? "NULL" : "'".mysql_real_escape_string($cod_municipio)."'").",".(!$cpendereco_vend ? "NULL" : "'".mysql_real_escape_string($cpendereco_vend)."'").",".(!$percentualvenda_vend ? "NULL" : "'".mysql_real_escape_string($percentualvenda_vend)."'").",".(!$despachante_vend ? "NULL" : "'".mysql_real_escape_string($despachante_vend)."'").",".(!$email_vend ? "NULL" : "'".mysql_real_escape_string($email_vend)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}
	
	function pesquisarPorProposta($cod_ppst) {
		$this->query = "
			SELECT cod_ppst,cod_vend,tipo_vend,nome_vend,nick_vend,endereco_vend,nrendereco_vend,cep_vend,telefone_vend,nrcc_vend,dvcc_vend,nrag_vend,nrcc2_vend,dvcc2_vend,nrag2_vend,banco_vend,qualificacao_vend,cod_bairro,bairro_vend,cod_logr,cod_uf,cod_municipio,cpendereco_vend,percentualvenda_vend,despachante_vend,email_vend
			FROM vendedor
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		$this->query();
		return $this->qrdata;
	}


}
					
class vendfis extends database {

	function vendfis() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cpf_vfisica,sexo_vfisica,dtnascimento_vfisica,natur_vfisica,nrrg_vfisica,dtrg_vfisica,orgrg_vfisica,nomeconj_vfisica,nomepai_vfisica,nomemae_vfisica,vlrenda_vfisica,nrinss_vfisica,cod_pais,cod_ppst,cod_tpdoc,cod_prof,cod_estciv,cod_vend,despachante_vfisica,flguniest_vfisica,flganuente_vfisica,profissao_vfisica,dtaquisimov_vfisica
			FROM vendfis
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_ppst,$cod_vend) {
		$this->query = "
			SELECT cpf_vfisica,sexo_vfisica,dtnascimento_vfisica,natur_vfisica,nrrg_vfisica,dtrg_vfisica,orgrg_vfisica,nomeconj_vfisica,nomepai_vfisica,nomemae_vfisica,vlrenda_vfisica,nrinss_vfisica,cod_pais,cod_ppst,cod_tpdoc,cod_prof,cod_estciv,cod_vend,despachante_vfisica,flguniest_vfisica,flganuente_vfisica,profissao_vfisica,dtaquisimov_vfisica
			FROM vendfis
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_vend = '".mysql_real_escape_string($cod_vend)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cpf_vfisica,$sexo_vfisica,$dtnascimento_vfisica,$natur_vfisica,$nrrg_vfisica,$dtrg_vfisica,$orgrg_vfisica,$nomeconj_vfisica,$nomepai_vfisica,$nomemae_vfisica,$vlrenda_vfisica,$nrinss_vfisica,$cod_pais,$cod_ppst,$cod_tpdoc,$cod_prof,$cod_estciv,$cod_vend,$despachante_vfisica,$flguniest_vfisica,$flganuente_vfisica,$profissao_vfisica,$dtaquisimov_vfisica) {
		 $this->query = "
			UPDATE vendfis SET 
			cpf_vfisica = ".(!$cpf_vfisica ? "NULL" : "'".mysql_real_escape_string($cpf_vfisica)."'").",sexo_vfisica = ".(!$sexo_vfisica ? "NULL" : "'".mysql_real_escape_string($sexo_vfisica)."'").",dtnascimento_vfisica = ".(!$dtnascimento_vfisica ? "NULL" : "'".mysql_real_escape_string($dtnascimento_vfisica)."'").",natur_vfisica = ".(!$natur_vfisica ? "NULL" : "'".mysql_real_escape_string($natur_vfisica)."'").",nrrg_vfisica = ".(!$nrrg_vfisica ? "NULL" : "'".mysql_real_escape_string($nrrg_vfisica)."'").",dtrg_vfisica = ".(!$dtrg_vfisica ? "NULL" : "'".mysql_real_escape_string($dtrg_vfisica)."'").",orgrg_vfisica = ".(!$orgrg_vfisica ? "NULL" : "'".mysql_real_escape_string($orgrg_vfisica)."'").",nomeconj_vfisica = ".(!$nomeconj_vfisica ? "NULL" : "'".mysql_real_escape_string($nomeconj_vfisica)."'").",nomepai_vfisica = ".(!$nomepai_vfisica ? "NULL" : "'".mysql_real_escape_string($nomepai_vfisica)."'").",nomemae_vfisica = ".(!$nomemae_vfisica ? "NULL" : "'".mysql_real_escape_string($nomemae_vfisica)."'").",vlrenda_vfisica = ".(!$vlrenda_vfisica ? "NULL" : "'".mysql_real_escape_string($vlrenda_vfisica)."'").",nrinss_vfisica = ".(!$nrinss_vfisica ? "NULL" : "'".mysql_real_escape_string($nrinss_vfisica)."'").",cod_pais = ".(!$cod_pais ? "NULL" : "'".mysql_real_escape_string($cod_pais)."'").",cod_ppst = ".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",cod_tpdoc = ".(!$cod_tpdoc ? "NULL" : "'".mysql_real_escape_string($cod_tpdoc)."'").",cod_prof = ".(!$cod_prof ? "NULL" : "'".mysql_real_escape_string($cod_prof)."'").",cod_estciv = ".(!$cod_estciv ? "NULL" : "'".mysql_real_escape_string($cod_estciv)."'").",cod_vend = ".(!$cod_vend ? "NULL" : "'".mysql_real_escape_string($cod_vend)."'").",despachante_vfisica = ".(!$despachante_vfisica ? "NULL" : "'".mysql_real_escape_string($despachante_vfisica)."'").",flguniest_vfisica = ".(!$flguniest_vfisica ? "NULL" : "'".mysql_real_escape_string($flguniest_vfisica)."'").",flganuente_vfisica = ".(!$flganuente_vfisica ? "NULL" : "'".mysql_real_escape_string($flganuente_vfisica)."'").",profissao_vfisica = ".(!$profissao_vfisica ? "NULL" : "'".mysql_real_escape_string($profissao_vfisica)."'").",dtaquisimov_vfisica = ".(!$dtaquisimov_vfisica ? "NULL" : "'".mysql_real_escape_string($dtaquisimov_vfisica)."'")."
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_vend = '".mysql_real_escape_string($cod_vend)."'
		";
		$this->query();		
		echo mysql_error();
		return $this->qrdata;
	}

	function deletarPk($cod_ppst,$cod_vend) {
$this->query = "
			DELETE FROM vendfis 
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_vend = '".mysql_real_escape_string($cod_vend)."'
		";
		$this->query();
				//echo mysql_error();
		return $this->qrdata;
	}

	function inserir($cpf_vfisica,$sexo_vfisica,$dtnascimento_vfisica,$natur_vfisica,$nrrg_vfisica,$dtrg_vfisica,$orgrg_vfisica,$nomeconj_vfisica,$nomepai_vfisica,$nomemae_vfisica,$vlrenda_vfisica,$nrinss_vfisica,$cod_pais,$cod_ppst,$cod_tpdoc,$cod_prof,$cod_estciv,$cod_vend,$despachante_vfisica,$flguniest_vfisica,$flganuente_vfisica,$profissao_vfisica,$dtaquisimov_vfisica) {
	$this->query = "
			INSERT INTO vendfis ( cpf_vfisica,sexo_vfisica,dtnascimento_vfisica,natur_vfisica,nrrg_vfisica,dtrg_vfisica,orgrg_vfisica,nomeconj_vfisica,nomepai_vfisica,nomemae_vfisica,vlrenda_vfisica,nrinss_vfisica,cod_pais,cod_ppst,cod_tpdoc,cod_prof,cod_estciv,cod_vend,despachante_vfisica,flguniest_vfisica,flganuente_vfisica,profissao_vfisica,dtaquisimov_vfisica) VALUES (
				".(!$cpf_vfisica ? "NULL" : "'".mysql_real_escape_string($cpf_vfisica)."'").",".(!$sexo_vfisica ? "NULL" : "'".mysql_real_escape_string($sexo_vfisica)."'").",".(!$dtnascimento_vfisica ? "NULL" : "'".mysql_real_escape_string($dtnascimento_vfisica)."'").",".(!$natur_vfisica ? "NULL" : "'".mysql_real_escape_string($natur_vfisica)."'").",".(!$nrrg_vfisica ? "NULL" : "'".mysql_real_escape_string($nrrg_vfisica)."'").",".(!$dtrg_vfisica ? "NULL" : "'".mysql_real_escape_string($dtrg_vfisica)."'").",".(!$orgrg_vfisica ? "NULL" : "'".mysql_real_escape_string($orgrg_vfisica)."'").",".(!$nomeconj_vfisica ? "NULL" : "'".mysql_real_escape_string($nomeconj_vfisica)."'").",".(!$nomepai_vfisica ? "NULL" : "'".mysql_real_escape_string($nomepai_vfisica)."'").",".(!$nomemae_vfisica ? "NULL" : "'".mysql_real_escape_string($nomemae_vfisica)."'").",".(!$vlrenda_vfisica ? "NULL" : "'".mysql_real_escape_string($vlrenda_vfisica)."'").",".(!$nrinss_vfisica ? "NULL" : "'".mysql_real_escape_string($nrinss_vfisica)."'").",".(!$cod_pais ? "NULL" : "'".mysql_real_escape_string($cod_pais)."'").",".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",".(!$cod_tpdoc ? "NULL" : "'".mysql_real_escape_string($cod_tpdoc)."'").",".(!$cod_prof ? "NULL" : "'".mysql_real_escape_string($cod_prof)."'").",".(!$cod_estciv ? "NULL" : "'".mysql_real_escape_string($cod_estciv)."'").",".(!$cod_vend ? "NULL" : "'".mysql_real_escape_string($cod_vend)."'").",".(!$despachante_vfisica ? "NULL" : "'".mysql_real_escape_string($despachante_vfisica)."'").",".(!$flguniest_vfisica ? "NULL" : "'".mysql_real_escape_string($flguniest_vfisica)."'").",".(!$flganuente_vfisica ? "NULL" : "'".mysql_real_escape_string($flganuente_vfisica)."'").",".(!$profissao_vfisica ? "NULL" : "'".mysql_real_escape_string($profissao_vfisica)."'").",".(!$dtaquisimov_vfisica ? "NULL" : "'".mysql_real_escape_string($dtaquisimov_vfisica)."'")."
			)
		";
	$this->query();
		return $this->qrdata;
		echo mysql_error();
	}
	
	function pesquisarPorCpf($cpf) {
		$this->query = "
			SELECT cpf_vfisica,sexo_vfisica,dtnascimento_vfisica,natur_vfisica,nrrg_vfisica,dtrg_vfisica,orgrg_vfisica,nomeconj_vfisica,nomepai_vfisica,nomemae_vfisica,vlrenda_vfisica,nrinss_vfisica,cod_pais,cod_ppst,cod_tpdoc,cod_prof,cod_estciv,cod_vend,despachante_vfisica,flguniest_vfisica,flganuente_vfisica,profissao_vfisica,dtaquisimov_vfisica
			FROM vendfis
			WHERE
				cpf_vfisica = '".$cpf."'
		";
		$this->query();
		return $this->qrdata;
	}
	function pesquisarPorRG($rg) {
		$this->query = "
			SELECT cpf_vfisica,sexo_vfisica,dtnascimento_vfisica,natur_vfisica,nrrg_vfisica,dtrg_vfisica,orgrg_vfisica,nomeconj_vfisica,nomepai_vfisica,nomemae_vfisica,vlrenda_vfisica,nrinss_vfisica,cod_pais,cod_ppst,cod_tpdoc,cod_prof,cod_estciv,cod_vend,despachante_vfisica,flguniest_vfisica,flganuente_vfisica,profissao_vfisica,dtaquisimov_vfisica
			FROM vendfis
			WHERE
				nrrg_vfisica = '".$cpf."'
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class vendfisconjuge extends database {

	function vendfisconjuge() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_vend,cod_ppst,regimebens_vfcj,dtcasamento_vfcj,nome_vfcj,cod_pais,cod_estciv,nrrg_vfcj,orgrg_vfcj,dtrg_vfcj,cpf_pccj,flgtrabalha_vfcj,empresa_vfcj,dtadmissaoemp_vfcj,enderecoemp_vfcj,numeroemp_vfcj,complementoemp_vfcj,bairroemp_vfcj,cidadeemp_vfcj,estadoemp_vfcj,telefoneemp_vfcj,cargoemp_vfcj,salarioemp_vfcj,despachante_vfcj
			FROM vendfisconjuge
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_vend,$cod_ppst) {
		$this->query = "
			SELECT cod_vend,cod_ppst,regimebens_vfcj,dtcasamento_vfcj,nome_vfcj,cod_pais,cod_estciv,nrrg_vfcj,orgrg_vfcj,dtrg_vfcj,cpf_pccj,flgtrabalha_vfcj,empresa_vfcj,dtadmissaoemp_vfcj,enderecoemp_vfcj,numeroemp_vfcj,complementoemp_vfcj,bairroemp_vfcj,cidadeemp_vfcj,estadoemp_vfcj,telefoneemp_vfcj,cargoemp_vfcj,salarioemp_vfcj,despachante_vfcj
			FROM vendfisconjuge
			WHERE cod_vend = '".mysql_real_escape_string($cod_vend)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_vend,$cod_ppst,$regimebens_vfcj,$dtcasamento_vfcj,$nome_vfcj,$cod_pais,$cod_estciv,$nrrg_vfcj,$orgrg_vfcj,$dtrg_vfcj,$cpf_pccj,$flgtrabalha_vfcj,$empresa_vfcj,$dtadmissaoemp_vfcj,$enderecoemp_vfcj,$numeroemp_vfcj,$complementoemp_vfcj,$bairroemp_vfcj,$cidadeemp_vfcj,$estadoemp_vfcj,$telefoneemp_vfcj,$cargoemp_vfcj,$salarioemp_vfcj,$despachante_vfcj) {
		$this->query = "
			UPDATE vendfisconjuge SET 
			cod_vend = ".(!$cod_vend ? "NULL" : "'".mysql_real_escape_string($cod_vend)."'").",cod_ppst = ".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",regimebens_vfcj = ".(!$regimebens_vfcj ? "NULL" : "'".mysql_real_escape_string($regimebens_vfcj)."'").",dtcasamento_vfcj = ".(!$dtcasamento_vfcj ? "NULL" : "'".mysql_real_escape_string($dtcasamento_vfcj)."'").",nome_vfcj = ".(!$nome_vfcj ? "NULL" : "'".mysql_real_escape_string($nome_vfcj)."'").",cod_pais = ".(!$cod_pais ? "NULL" : "'".mysql_real_escape_string($cod_pais)."'").",cod_estciv = ".(!$cod_estciv ? "NULL" : "'".mysql_real_escape_string($cod_estciv)."'").",nrrg_vfcj = ".(!$nrrg_vfcj ? "NULL" : "'".mysql_real_escape_string($nrrg_vfcj)."'").",orgrg_vfcj = ".(!$orgrg_vfcj ? "NULL" : "'".mysql_real_escape_string($orgrg_vfcj)."'").",dtrg_vfcj = ".(!$dtrg_vfcj ? "NULL" : "'".mysql_real_escape_string($dtrg_vfcj)."'").",cpf_pccj = ".(!$cpf_pccj ? "NULL" : "'".mysql_real_escape_string($cpf_pccj)."'").",flgtrabalha_vfcj = ".(!$flgtrabalha_vfcj ? "NULL" : "'".mysql_real_escape_string($flgtrabalha_vfcj)."'").",empresa_vfcj = ".(!$empresa_vfcj ? "NULL" : "'".mysql_real_escape_string($empresa_vfcj)."'").",dtadmissaoemp_vfcj = ".(!$dtadmissaoemp_vfcj ? "NULL" : "'".mysql_real_escape_string($dtadmissaoemp_vfcj)."'").",enderecoemp_vfcj = ".(!$enderecoemp_vfcj ? "NULL" : "'".mysql_real_escape_string($enderecoemp_vfcj)."'").",numeroemp_vfcj = ".(!$numeroemp_vfcj ? "NULL" : "'".mysql_real_escape_string($numeroemp_vfcj)."'").",complementoemp_vfcj = ".(!$complementoemp_vfcj ? "NULL" : "'".mysql_real_escape_string($complementoemp_vfcj)."'").",bairroemp_vfcj = ".(!$bairroemp_vfcj ? "NULL" : "'".mysql_real_escape_string($bairroemp_vfcj)."'").",cidadeemp_vfcj = ".(!$cidadeemp_vfcj ? "NULL" : "'".mysql_real_escape_string($cidadeemp_vfcj)."'").",estadoemp_vfcj = ".(!$estadoemp_vfcj ? "NULL" : "'".mysql_real_escape_string($estadoemp_vfcj)."'").",telefoneemp_vfcj = ".(!$telefoneemp_vfcj ? "NULL" : "'".mysql_real_escape_string($telefoneemp_vfcj)."'").",cargoemp_vfcj = ".(!$cargoemp_vfcj ? "NULL" : "'".mysql_real_escape_string($cargoemp_vfcj)."'").",salarioemp_vfcj = ".(!$salarioemp_vfcj ? "NULL" : "'".mysql_real_escape_string($salarioemp_vfcj)."'").",despachante_vfcj = ".(!$despachante_vfcj ? "NULL" : "'".mysql_real_escape_string($despachante_vfcj)."'")."
			WHERE cod_vend = '".mysql_real_escape_string($cod_vend)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_vend,$cod_ppst) {
		$this->query = "
			DELETE FROM vendfisconjuge 
			WHERE cod_vend = '".mysql_real_escape_string($cod_vend)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_vend,$cod_ppst,$regimebens_vfcj,$dtcasamento_vfcj,$nome_vfcj,$cod_pais,$cod_estciv,$nrrg_vfcj,$orgrg_vfcj,$dtrg_vfcj,$cpf_pccj,$flgtrabalha_vfcj,$empresa_vfcj,$dtadmissaoemp_vfcj,$enderecoemp_vfcj,$numeroemp_vfcj,$complementoemp_vfcj,$bairroemp_vfcj,$cidadeemp_vfcj,$estadoemp_vfcj,$telefoneemp_vfcj,$cargoemp_vfcj,$salarioemp_vfcj,$despachante_vfcj) {
		$this->query = "
			INSERT INTO vendfisconjuge ( cod_vend,cod_ppst,regimebens_vfcj,dtcasamento_vfcj,nome_vfcj,cod_pais,cod_estciv,nrrg_vfcj,orgrg_vfcj,dtrg_vfcj,cpf_pccj,flgtrabalha_vfcj,empresa_vfcj,dtadmissaoemp_vfcj,enderecoemp_vfcj,numeroemp_vfcj,complementoemp_vfcj,bairroemp_vfcj,cidadeemp_vfcj,estadoemp_vfcj,telefoneemp_vfcj,cargoemp_vfcj,salarioemp_vfcj,despachante_vfcj ) VALUES (
				".(!$cod_vend ? "NULL" : "'".mysql_real_escape_string($cod_vend)."'").",".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",".(!$regimebens_vfcj ? "NULL" : "'".mysql_real_escape_string($regimebens_vfcj)."'").",".(!$dtcasamento_vfcj ? "NULL" : "'".mysql_real_escape_string($dtcasamento_vfcj)."'").",".(!$nome_vfcj ? "NULL" : "'".mysql_real_escape_string($nome_vfcj)."'").",".(!$cod_pais ? "NULL" : "'".mysql_real_escape_string($cod_pais)."'").",".(!$cod_estciv ? "NULL" : "'".mysql_real_escape_string($cod_estciv)."'").",".(!$nrrg_vfcj ? "NULL" : "'".mysql_real_escape_string($nrrg_vfcj)."'").",".(!$orgrg_vfcj ? "NULL" : "'".mysql_real_escape_string($orgrg_vfcj)."'").",".(!$dtrg_vfcj ? "NULL" : "'".mysql_real_escape_string($dtrg_vfcj)."'").",".(!$cpf_pccj ? "NULL" : "'".mysql_real_escape_string($cpf_pccj)."'").",".(!$flgtrabalha_vfcj ? "NULL" : "'".mysql_real_escape_string($flgtrabalha_vfcj)."'").",".(!$empresa_vfcj ? "NULL" : "'".mysql_real_escape_string($empresa_vfcj)."'").",".(!$dtadmissaoemp_vfcj ? "NULL" : "'".mysql_real_escape_string($dtadmissaoemp_vfcj)."'").",".(!$enderecoemp_vfcj ? "NULL" : "'".mysql_real_escape_string($enderecoemp_vfcj)."'").",".(!$numeroemp_vfcj ? "NULL" : "'".mysql_real_escape_string($numeroemp_vfcj)."'").",".(!$complementoemp_vfcj ? "NULL" : "'".mysql_real_escape_string($complementoemp_vfcj)."'").",".(!$bairroemp_vfcj ? "NULL" : "'".mysql_real_escape_string($bairroemp_vfcj)."'").",".(!$cidadeemp_vfcj ? "NULL" : "'".mysql_real_escape_string($cidadeemp_vfcj)."'").",".(!$estadoemp_vfcj ? "NULL" : "'".mysql_real_escape_string($estadoemp_vfcj)."'").",".(!$telefoneemp_vfcj ? "NULL" : "'".mysql_real_escape_string($telefoneemp_vfcj)."'").",".(!$cargoemp_vfcj ? "NULL" : "'".mysql_real_escape_string($cargoemp_vfcj)."'").",".(!$salarioemp_vfcj ? "NULL" : "'".mysql_real_escape_string($salarioemp_vfcj)."'").",".(!$despachante_vfcj ? "NULL" : "'".mysql_real_escape_string($despachante_vfcj)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPorCpf($cpf) {
		$this->query = "
			SELECT cod_vend,cod_ppst,regimebens_vfcj,dtcasamento_vfcj,nome_vfcj,cod_pais,cod_estciv,nrrg_vfcj,orgrg_vfcj,dtrg_vfcj,cpf_pccj,flgtrabalha_vfcj,empresa_vfcj,dtadmissaoemp_vfcj,enderecoemp_vfcj,numeroemp_vfcj,complementoemp_vfcj,bairroemp_vfcj,cidadeemp_vfcj,estadoemp_vfcj,telefoneemp_vfcj,cargoemp_vfcj,salarioemp_vfcj,despachante_vfcj
			FROM vendfisconjuge
			WHERE
				cpf_pccj = '".$cpf."'
		";
		$this->query();
		return $this->qrdata;
	}
	function pesquisarPorRG($rg) {
		$this->query = "
			SELECT cod_vend,cod_ppst,regimebens_vfcj,dtcasamento_vfcj,nome_vfcj,cod_pais,cod_estciv,nrrg_vfcj,orgrg_vfcj,dtrg_vfcj,cpf_pccj,flgtrabalha_vfcj,empresa_vfcj,dtadmissaoemp_vfcj,enderecoemp_vfcj,numeroemp_vfcj,complementoemp_vfcj,bairroemp_vfcj,cidadeemp_vfcj,estadoemp_vfcj,telefoneemp_vfcj,cargoemp_vfcj,salarioemp_vfcj,despachante_vfcj
			FROM vendfisconjuge
			WHERE
				nrrg_vfcj = '".$cpf."'
		";
		$this->query();
		return $this->qrdata;
	}
}
					
class vendfisconjugepacto extends database {

	function vendfisconjugepacto() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_vend,cod_ppst,data_vcpa,locallavracao_vcpa,livro_vcpa,folha_vcpa,numeroregistro_vcpa
			FROM vendfisconjugepacto
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_vend,$cod_ppst) {
		$this->query = "
			SELECT cod_vend,cod_ppst,data_vcpa,locallavracao_vcpa,livro_vcpa,folha_vcpa,numeroregistro_vcpa
			FROM vendfisconjugepacto
			WHERE cod_vend = '".mysql_real_escape_string($cod_vend)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_vend,$cod_ppst,$data_vcpa,$locallavracao_vcpa,$livro_vcpa,$folha_vcpa,$numeroregistro_vcpa) {
		$this->query = "
			UPDATE vendfisconjugepacto SET 
			cod_vend = ".(!$cod_vend ? "NULL" : "'".mysql_real_escape_string($cod_vend)."'").",cod_ppst = ".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",data_vcpa = ".(!$data_vcpa ? "NULL" : "'".mysql_real_escape_string($data_vcpa)."'").",locallavracao_vcpa = ".(!$locallavracao_vcpa ? "NULL" : "'".mysql_real_escape_string($locallavracao_vcpa)."'").",livro_vcpa = ".(!$livro_vcpa ? "NULL" : "'".mysql_real_escape_string($livro_vcpa)."'").",folha_vcpa = ".(!$folha_vcpa ? "NULL" : "'".mysql_real_escape_string($folha_vcpa)."'").",numeroregistro_vcpa = ".(!$numeroregistro_vcpa ? "NULL" : "'".mysql_real_escape_string($numeroregistro_vcpa)."'")."
			WHERE cod_vend = '".mysql_real_escape_string($cod_vend)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_vend,$cod_ppst) {
		$this->query = "
			DELETE FROM vendfisconjugepacto 
			WHERE cod_vend = '".mysql_real_escape_string($cod_vend)."' and cod_ppst = '".mysql_real_escape_string($cod_ppst)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_vend,$cod_ppst,$data_vcpa,$locallavracao_vcpa,$livro_vcpa,$folha_vcpa,$numeroregistro_vcpa) {
		$this->query = "
			INSERT INTO vendfisconjugepacto ( cod_vend,cod_ppst,data_vcpa,locallavracao_vcpa,livro_vcpa,folha_vcpa,numeroregistro_vcpa ) VALUES (
				".(!$cod_vend ? "NULL" : "'".mysql_real_escape_string($cod_vend)."'").",".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",".(!$data_vcpa ? "NULL" : "'".mysql_real_escape_string($data_vcpa)."'").",".(!$locallavracao_vcpa ? "NULL" : "'".mysql_real_escape_string($locallavracao_vcpa)."'").",".(!$livro_vcpa ? "NULL" : "'".mysql_real_escape_string($livro_vcpa)."'").",".(!$folha_vcpa ? "NULL" : "'".mysql_real_escape_string($folha_vcpa)."'").",".(!$numeroregistro_vcpa ? "NULL" : "'".mysql_real_escape_string($numeroregistro_vcpa)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

}
					
class vendjur extends database {

	function vendjur() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_ppst,cnpj_vjur,isenpis_vjur,isencofins_vjur,isencsll_vjur,cod_cnae,cod_vend,despachante_vjur,versaoestat_vjur,dtestat_vjur,locestat_vjur,nrregestat_vjur,dtregestat_vjur,tipo_soc_vjur,tipo_rep_vjur
			FROM vendjur
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_ppst,$cod_vend) {
		$this->query = "
			SELECT cod_ppst,cnpj_vjur,isenpis_vjur,isencofins_vjur,isencsll_vjur,cod_cnae,cod_vend,despachante_vjur,versaoestat_vjur,dtestat_vjur,locestat_vjur,nrregestat_vjur,dtregestat_vjur,tipo_soc_vjur,tipo_rep_vjur
			FROM vendjur
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_vend = '".mysql_real_escape_string($cod_vend)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_ppst,$cnpj_vjur,$isenpis_vjur,$isencofins_vjur,$isencsll_vjur,$cod_cnae,$cod_vend,$despachante_vjur,$versaoestat_vjur,$dtestat_vjur,$locestat_vjur,$nrregestat_vjur,$dtregestat_vjur,$tipo_soc_vjur,$tipo_rep_vjur) {
		   $this->query = "
			UPDATE vendjur SET 
			cod_ppst = ".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",cnpj_vjur = ".(!$cnpj_vjur ? "NULL" : "'".mysql_real_escape_string($cnpj_vjur)."'").",isenpis_vjur = ".(!$isenpis_vjur ? "NULL" : "'".mysql_real_escape_string($isenpis_vjur)."'").",isencofins_vjur = ".(!$isencofins_vjur ? "NULL" : "'".mysql_real_escape_string($isencofins_vjur)."'").",isencsll_vjur = ".(!$isencsll_vjur ? "NULL" : "'".mysql_real_escape_string($isencsll_vjur)."'").",cod_cnae = ".(!$cod_cnae ? "NULL" : "'".mysql_real_escape_string($cod_cnae)."'").",cod_vend = ".(!$cod_vend ? "NULL" : "'".mysql_real_escape_string($cod_vend)."'").",versaoestat_vjur = ".(!$versaoestat_vjur ? "NULL" : "'".mysql_real_escape_string($versaoestat_vjur)."'").",dtestat_vjur = ".(!$dtestat_vjur ? "NULL" : "'".mysql_real_escape_string($dtestat_vjur)."'").",locestat_vjur = ".(!$locestat_vjur ? "NULL" : "'".mysql_real_escape_string($locestat_vjur)."'").",nrregestat_vjur = ".(!$nrregestat_vjur ? "NULL" : "'".mysql_real_escape_string($nrregestat_vjur)."'").", dtregestat_vjur = ".(!$dtregestat_vjur ? "NULL" : "'".mysql_real_escape_string($dtregestat_vjur)."'").", tipo_soc_vjur = ".(!$tipo_soc_vjur ? "NULL" : "'".mysql_real_escape_string($tipo_soc_vjur)."'").", tipo_rep_vjur = ".(!$tipo_rep_vjur ? "NULL" : "'".mysql_real_escape_string($tipo_rep_vjur)."'")."
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_vend = '".mysql_real_escape_string($cod_vend)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_ppst,$cod_vend) {
		$this->query = "
			DELETE FROM vendjur 
			WHERE cod_ppst = '".mysql_real_escape_string($cod_ppst)."' and cod_vend = '".mysql_real_escape_string($cod_vend)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_ppst,$cnpj_vjur,$isenpis_vjur,$isencofins_vjur,$isencsll_vjur,$cod_cnae,$cod_vend,$despachante_vjur,$versaoestat_vjur,$dtestat_vjur,$locestat_vjur,$nrregestat_vjur,$dtregestat_vjur,$tipo_soc_vjur,$tipo_rep_vjur) {
		 $this->query = "
			INSERT INTO vendjur ( cod_ppst,cnpj_vjur,isenpis_vjur,isencofins_vjur,isencsll_vjur,cod_cnae,cod_vend,despachante_vjur,versaoestat_vjur,dtestat_vjur,locestat_vjur,nrregestat_vjur,dtregestat_vjur,tipo_soc_vjur,tipo_rep_vjur ) VALUES (
				".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",".(!$cnpj_vjur ? "NULL" : "'".mysql_real_escape_string($cnpj_vjur)."'").",".(!$isenpis_vjur ? "NULL" : "'".mysql_real_escape_string($isenpis_vjur)."'").",".(!$isencofins_vjur ? "NULL" : "'".mysql_real_escape_string($isencofins_vjur)."'").",".(!$isencsll_vjur ? "NULL" : "'".mysql_real_escape_string($isencsll_vjur)."'").",".(!$cod_cnae ? "NULL" : "'".mysql_real_escape_string($cod_cnae)."'").",".(!$cod_vend ? "NULL" : "'".mysql_real_escape_string($cod_vend)."'").",".(!$despachante_vjur ? "NULL" : "'".mysql_real_escape_string($despachante_vjur)."'").",".(!$versaoestat_vjur ? "NULL" : "'".mysql_real_escape_string($versaoestat_vjur)."'").",".(!$dtestat_vjur ? "NULL" : "'".mysql_real_escape_string($dtestat_vjur)."'").",".(!$locestat_vjur ? "NULL" : "'".mysql_real_escape_string($locestat_vjur)."'").",".(!$nrregestat_vjur ? "NULL" : "'".mysql_real_escape_string($nrregestat_vjur)."'").",".(!$dtregestat_vjur ? "NULL" : "'".mysql_real_escape_string($dtregestat_vjur)."'").",".(!$tipo_soc_vjur ? "NULL" : "'".mysql_real_escape_string($tipo_soc_vjur)."'").",".(!$tipo_rep_vjur ? "NULL" : "'".mysql_real_escape_string($tipo_rep_vjur)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPorCnpj($cnpj) {
		$this->query = "
			SELECT cod_ppst,cnpj_vjur,isenpis_vjur,isencofins_vjur,isencsll_vjur,cod_cnae,cod_vend,despachante_vjur,versaoestat_vjur,dtestat_vjur,locestat_vjur,nrregestat_vjur,dtregestat_vjur,tipo_soc_vjur,tipo_rep_vjur
			FROM vendjur
			WHERE
				cnpj_vjur = '".$cnpj."'
		";
		$this->query();
		return $this->qrdata;
	}
}

					
class vendjursocio extends database {

	function vendjursocio() {
		
	}
	
	function listar() {
		$this->query = "
			SELECT cod_ppst,cod_vjsoc,nome_vjsoc,nick_vjsoc,endereco_vjsoc,nrendereco_vjsoc,cpendereco_vjsoc,bairro_vjsoc,cep_vjsoc,telefone_vjsoc,cpf_vjsoc,sexo_vjsoc,cod_pais,cod_logr,cod_bairro,cod_uf,cod_municipio,cod_vend,nrrg_vjsoc,orgrg_vjsoc,dtrg_vjsoc,cod_estciv,cargo_vjsoc
			FROM vendjursocio
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPk($cod_vjsoc) {
		$this->query = "
			SELECT cod_ppst,cod_vjsoc,nome_vjsoc,nick_vjsoc,endereco_vjsoc,nrendereco_vjsoc,cpendereco_vjsoc,bairro_vjsoc,cep_vjsoc,telefone_vjsoc,cpf_vjsoc,sexo_vjsoc,cod_pais,cod_logr,cod_bairro,cod_uf,cod_municipio,cod_vend,nrrg_vjsoc,orgrg_vjsoc,dtrg_vjsoc,cod_estciv,cargo_vjsoc
			FROM vendjursocio
			WHERE cod_vjsoc = '".mysql_real_escape_string($cod_vjsoc)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function atualizarPk($cod_ppst,$cod_vjsoc,$nome_vjsoc,$nick_vjsoc,$endereco_vjsoc,$nrendereco_vjsoc,$cpendereco_vjsoc,$bairro_vjsoc,$cep_vjsoc,$telefone_vjsoc,$cpf_vjsoc,$sexo_vjsoc,$cod_pais,$cod_logr,$cod_bairro,$cod_uf,$cod_municipio,$cod_vend,$nrrg_vjsoc,$orgrg_vjsoc,$dtrg_vjsoc,$cod_estciv,$cargo_vjsoc) {
		$this->query = "
			UPDATE vendjursocio SET 
			cod_ppst = ".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",nome_vjsoc = ".(!$nome_vjsoc ? "NULL" : "'".mysql_real_escape_string($nome_vjsoc)."'").",nick_vjsoc = ".(!$nick_vjsoc ? "NULL" : "'".mysql_real_escape_string($nick_vjsoc)."'").",endereco_vjsoc = ".(!$endereco_vjsoc ? "NULL" : "'".mysql_real_escape_string($endereco_vjsoc)."'").",nrendereco_vjsoc = ".(!$nrendereco_vjsoc ? "0" : "'".mysql_real_escape_string($nrendereco_vjsoc)."'").",cpendereco_vjsoc = ".(!$cpendereco_vjsoc ? "NULL" : "'".mysql_real_escape_string($cpendereco_vjsoc)."'").",bairro_vjsoc = ".(!$bairro_vjsoc ? "NULL" : "'".mysql_real_escape_string($bairro_vjsoc)."'").",cep_vjsoc = ".(!$cep_vjsoc ? "NULL" : "'".mysql_real_escape_string($cep_vjsoc)."'").",telefone_vjsoc = ".(!$telefone_vjsoc ? "NULL" : "'".mysql_real_escape_string($telefone_vjsoc)."'").",cpf_vjsoc = ".(!$cpf_vjsoc ? "NULL" : "'".mysql_real_escape_string($cpf_vjsoc)."'").",sexo_vjsoc = ".(!$sexo_vjsoc ? "NULL" : "'".mysql_real_escape_string($sexo_vjsoc)."'").",cod_pais = ".(!$cod_pais ? "NULL" : "'".mysql_real_escape_string($cod_pais)."'").",cod_logr = ".(!$cod_logr ? "NULL" : "'".mysql_real_escape_string($cod_logr)."'").",cod_bairro = ".(!$cod_bairro ? "NULL" : "'".mysql_real_escape_string($cod_bairro)."'").",cod_uf = ".(!$cod_uf ? "NULL" : "'".mysql_real_escape_string($cod_uf)."'").",cod_municipio = ".(!$cod_municipio ? "NULL" : "'".mysql_real_escape_string($cod_municipio)."'").",cod_vend = ".(!$cod_vend ? "NULL" : "'".mysql_real_escape_string($cod_vend)."'").",nrrg_vjsoc = ".(!$nrrg_vjsoc ? "NULL" : "'".mysql_real_escape_string($nrrg_vjsoc)."'").",orgrg_vjsoc = ".(!$orgrg_vjsoc ? "NULL" : "'".mysql_real_escape_string($orgrg_vjsoc)."'").",dtrg_vjsoc = ".(!$dtrg_vjsoc ? "NULL" : "'".mysql_real_escape_string($dtrg_vjsoc)."'").",cod_estciv = ".(!$cod_estciv ? "NULL" : "'".mysql_real_escape_string($cod_estciv)."'").",cargo_vjsoc = ".(!$cargo_vjsoc ? "NULL" : "'".mysql_real_escape_string($cargo_vjsoc)."'")."
			WHERE cod_vjsoc = '".mysql_real_escape_string($cod_vjsoc)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function deletarPk($cod_vjsoc) {
		$this->query = "
			DELETE FROM vendjursocio 
			WHERE cod_vjsoc = '".mysql_real_escape_string($cod_vjsoc)."'
		";
		$this->query();
		return $this->qrdata;
	}
	function deletarPorVendedor($cod_vend) {
		$this->query = "
			DELETE FROM vendjursocio 
			WHERE cod_vend = '".mysql_real_escape_string($cod_vend)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_ppst,$nome_vjsoc,$nick_vjsoc,$endereco_vjsoc,$nrendereco_vjsoc,$cpendereco_vjsoc,$bairro_vjsoc,$cep_vjsoc,$telefone_vjsoc,$cpf_vjsoc,$sexo_vjsoc,$cod_pais,$cod_logr,$cod_bairro,$cod_uf,$cod_municipio,$cod_vend,$nrrg_vjsoc,$orgrg_vjsoc,$dtrg_vjsoc,$cod_estciv,$cargo_vjsoc) {
		$this->query = "
			INSERT INTO vendjursocio ( cod_ppst,nome_vjsoc,nick_vjsoc,endereco_vjsoc,nrendereco_vjsoc,cpendereco_vjsoc,bairro_vjsoc,cep_vjsoc,telefone_vjsoc,cpf_vjsoc,sexo_vjsoc,cod_pais,cod_logr,cod_bairro,cod_uf,cod_municipio,cod_vend,nrrg_vjsoc,orgrg_vjsoc,dtrg_vjsoc,cod_estciv,cargo_vjsoc ) VALUES (
				".(!$cod_ppst ? "NULL" : "'".mysql_real_escape_string($cod_ppst)."'").",".(!$nome_vjsoc ? "NULL" : "'".mysql_real_escape_string($nome_vjsoc)."'").",".(!$nick_vjsoc ? "NULL" : "'".mysql_real_escape_string($nick_vjsoc)."'").",".(!$endereco_vjsoc ? "NULL" : "'".mysql_real_escape_string($endereco_vjsoc)."'").",".(!$nrendereco_vjsoc ? "0" : "'".mysql_real_escape_string($nrendereco_vjsoc)."'").",".(!$cpendereco_vjsoc ? "NULL" : "'".mysql_real_escape_string($cpendereco_vjsoc)."'").",".(!$bairro_vjsoc ? "NULL" : "'".mysql_real_escape_string($bairro_vjsoc)."'").",".(!$cep_vjsoc ? "NULL" : "'".mysql_real_escape_string($cep_vjsoc)."'").",".(!$telefone_vjsoc ? "NULL" : "'".mysql_real_escape_string($telefone_vjsoc)."'").",".(!$cpf_vjsoc ? "NULL" : "'".mysql_real_escape_string($cpf_vjsoc)."'").",".(!$sexo_vjsoc ? "NULL" : "'".mysql_real_escape_string($sexo_vjsoc)."'").",".(!$cod_pais ? "NULL" : "'".mysql_real_escape_string($cod_pais)."'").",".(!$cod_logr ? "NULL" : "'".mysql_real_escape_string($cod_logr)."'").",".(!$cod_bairro ? "NULL" : "'".mysql_real_escape_string($cod_bairro)."'").",".(!$cod_uf ? "NULL" : "'".mysql_real_escape_string($cod_uf)."'").",".(!$cod_municipio ? "NULL" : "'".mysql_real_escape_string($cod_municipio)."'").",".(!$cod_vend ? "NULL" : "'".mysql_real_escape_string($cod_vend)."'").",".(!$nrrg_vjsoc ? "NULL" : "'".mysql_real_escape_string($nrrg_vjsoc)."'").",".(!$orgrg_vjsoc ? "NULL" : "'".mysql_real_escape_string($orgrg_vjsoc)."'").",".(!$dtrg_vjsoc ? "NULL" : "'".mysql_real_escape_string($dtrg_vjsoc)."'").",".(!$cod_estciv ? "NULL" : "'".mysql_real_escape_string($cod_estciv)."'").",".(!$cargo_vjsoc ? "NULL" : "'".mysql_real_escape_string($cargo_vjsoc)."'")."
			)
		";
		$this->query();
		return $this->qrdata;
	}
	
	function pesquisarPorVendedor($cod_vend) {
		$this->query = "
			SELECT cod_ppst,cod_vjsoc,nome_vjsoc,nick_vjsoc,endereco_vjsoc,nrendereco_vjsoc,cpendereco_vjsoc,bairro_vjsoc,cep_vjsoc,telefone_vjsoc,cpf_vjsoc,sexo_vjsoc,cod_pais,cod_logr,cod_bairro,cod_uf,cod_municipio,cod_vend,nrrg_vjsoc,orgrg_vjsoc,dtrg_vjsoc,cod_estciv,cargo_vjsoc
			FROM vendjursocio
			WHERE cod_vend = '".mysql_real_escape_string($cod_vend)."'
		";
		$this->query();
		return $this->qrdata;
	}

	function pesquisarPorCpf($cpf) {
		$this->query = "
			SELECT cod_ppst,cod_vjsoc,nome_vjsoc,nick_vjsoc,endereco_vjsoc,nrendereco_vjsoc,cpendereco_vjsoc,bairro_vjsoc,cep_vjsoc,telefone_vjsoc,cpf_vjsoc,sexo_vjsoc,cod_pais,cod_logr,cod_bairro,cod_uf,cod_municipio,cod_vend,nrrg_vjsoc,orgrg_vjsoc,dtrg_vjsoc,cod_estciv,cargo_vjsoc
			FROM vendjursocio
			WHERE
				cpf_vjsoc = '".$cpf."'
		";
		$this->query();
		return $this->qrdata;
	}
}

class vendtelefone extends database {

	function vendtelefone() {
	}

	function getList() {
		$this->query = "
			SELECT COD_VNTL,COD_VEND,TELEFONE_VNTL,TIPO_VNTL
			FROM vendtelefone
		";
		$this->query();
		return $this->qrdata;
	}
		

	function getPk($cod_vntl) {
		$this->query = "
			SELECT COD_VNTL,COD_VEND,TELEFONE_VNTL,TIPO_VNTL
			FROM vendtelefone
			WHERE COD_VNTL = '".mysql_real_escape_string($cod_vntl)."'
		";
		$this->query();
		return $this->qrdata;
	}
			

	function getListaTelefoneVend($cod_vend) {
		$this->query = "
			SELECT COD_VNTL,COD_VEND,TELEFONE_VNTL,TIPO_VNTL
			FROM vendtelefone
			WHERE COD_VEND = '".mysql_real_escape_string($cod_vend)."'
		";
		$this->query();
		return $this->qrdata;
	}
	
	function updatePk($cod_vntl,$cod_vend,$telefone_vntl,$tipo_vntl) {
		$this->query = "
			UPDATE vendtelefone SET 
			COD_VEND=".(!$cod_vend ? "NULL" : "'".mysql_real_escape_string($cod_vend)."'").",TELEFONE_VNTL=".(!$telefone_vntl ? "NULL" : "'".mysql_real_escape_string($telefone_vntl)."'").",TIPO_VNTL=".(!$tipo_vntl ? "NULL" : "'".mysql_real_escape_string($tipo_vntl)."'")."
			WHERE COD_VNTL = '".mysql_real_escape_string($cod_vntl)."'
		";
		$this->query();
		return true;
	}
			

	function deletePk($cod_vntl) {
		$this->query = "
			DELETE FROM vendtelefone 
			WHERE COD_VNTL = '".mysql_real_escape_string($cod_vntl)."'
		";
		$this->query();
		return true;
	}
	
	
	function deletePorVend($cod_vend) {
		$this->query = "
			DELETE FROM vendtelefone 
			WHERE COD_VEND = '".mysql_real_escape_string($cod_vend)."'
		";
		$this->query();
		return true;
	}
			

	function insert($cod_vend,$telefone_vntl,$tipo_vntl) {
		$this->query = "
			INSERT INTO vendtelefone ( COD_VEND,TELEFONE_VNTL,TIPO_VNTL ) VALUES (
				".(!$cod_vend ? "NULL" : "'".mysql_real_escape_string($cod_vend)."'").",".(!$telefone_vntl ? "NULL" : "'".mysql_real_escape_string($telefone_vntl)."'").",".(!$tipo_vntl ? "NULL" : "'".mysql_real_escape_string($tipo_vntl)."'")."
			)
		";
		$this->query();
		if ($this->getErrNo()) {
			return false;
		}
		
		return true;
	}
		
}


class ecivil extends database {
	
	function ecivil() {
	}

	function getListaECivil($cod_estciv=false) {
		$sqlComplem = ($cod_estciv)?" WHERE cod_estciv='".mysql_real_escape_string($cod_estciv)."' ":"";
		$this->query = "
			SELECT
				cod_estciv,
				desc_estciv
			FROM
				estadocivil 
			$sqlComplem
			ORDER BY 
				desc_estciv
		";
		$this->query();
		return $this->qrdata;
	}
	
}

class documentoDAO extends database {

	function documento() {

	}

	function buscarTipos()
	{
		$this->query = "
			SELECT  *
			FROM tipo_documentacao WHERE parent_id IS NULL
		";
		$this->query();
		return $this->qrdata;
	}

	function buscarTiposFilhos($id_pai)
	{
		$this->query = "
			SELECT  *
			FROM tipo_documentacao WHERE parent_id = $id_pai
		";
		$this->query();
		return $this->qrdata;
	}

	function buscarSubtipos($id_pai)
	{
		$this->query = "
			SELECT  *
			FROM subtipo_documentacao WHERE tipo_documentacao_id = $id_pai
		";
		$this->query();
		return $this->qrdata;
	}


	function listarPorProposta($cod_ppst) {
		$this->query = "
			SELECT  
			cod_proposta, 
			usuario.nome_usua as nome_usuario, 
			hash_arquivo, 
			nome_arquivo,
			tipo_documentacao.id as id_tipo,
			tipo_documentacao.descricao as tipo,
			tipo_documentacao.parent_id as parent_id,
			subtipo_documentacao.descricao as id_subtipo,
			subtipo_documentacao.descricao as subtipo,
			date_format(data_criacao, '%d/%m/%Y %H:%i') as data_criacao
			FROM documentos_anexo
			INNER JOIN usuario ON usuario.cod_usua = documentos_anexo.cod_usuario
			INNER JOIN tipo_documentacao ON tipo_documentacao.id = documentos_anexo.tipo
			INNER JOIN subtipo_documentacao ON subtipo_documentacao.id = documentos_anexo.subtipo
			WHERE cod_proposta = '".mysql_real_escape_string($cod_ppst)."'
			ORDER BY data_criacao DESC
		";
		$this->query();
		return $this->qrdata;
	}

	function inserir($cod_ppst, $cod_usua ,$tipo, $subtipo, $nome_arquivo, $hash_arquivo) {
		$this->query = "
			INSERT INTO documentos_anexo ( cod_proposta, cod_usuario, hash_arquivo, nome_arquivo, tipo, subtipo, data_criacao ) VALUES (
				$cod_ppst,
				$cod_usua,
				'$hash_arquivo',
				'$nome_arquivo',
				$tipo,
				$subtipo,
				NOW()
			)
		";
		$this->query();
		return $this->qrdata;
	}

}

?>