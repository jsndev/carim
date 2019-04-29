<?
define ("ATHOSFILEPATH", "/home/paulo.lomanto/desenvolvimento/athosfiles/");
/*
define ("ATHOSFILE909IN", "ATHOSGESTAO.COFSP909.0000011.SAI");
define ("ATHOSFILE906IN", "ATHOSGESTÃO.COFSP906.0000011.SAI");
define ("ATHOSFILE906OUT", "ATHOSGESTÃO.COFSP906.0000011.ENT");
*/
define ("ATHOSFILE909IN", "cofsp909sai");
define ("ATHOSFILE906IN", "cofsp906sai");
define ("ATHOSFILE906OUT", "cofsp906ent");

class arquivo extends database {
	var $errDesc;
	var $errCode;
	
	var $fileArr;
	var $curLine;
	var $numRows;
	var $procLines;
	
	var $iNumRemessa;
	var $dtDataRemessa;
	
	function recLoteCadastro($sArquivo) {
		//ATHOSGESTÃO.COFSP909.0000011.SAI
		$this->clearData();
		$this->setProcLines(0);
		
		//$fileName = "../athosfiles/COFSP909.ATHOS.SAI";
		$fileName = ATHOSFILEPATH.$sArquivo;

		$headerFields = "2,8,15,8,6";
		$regFields = "2,9,15,15,3,15,15,3,15,2,6,8,250";
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
						} elseif (strtoupper(trim($reg[9])) == "C") {
							$this->query = "
								INSERT INTO listadenomes (
									id_lstn,
									vlmaxfinan,
									parcmaxfinan,
									przmaxfinan,
									status_pp
								) VALUES (
									'".mysql_real_escape_string($reg[1])."',
									".$this->fieldCurrency(mysql_real_escape_string($reg[2])).",
									".$this->fieldCurrency(mysql_real_escape_string($reg[3])).",
									".$this->fieldNumber(mysql_real_escape_string($reg[4])).",
									".$this->fieldCurrency(mysql_real_escape_string($reg[5])).",
									".$this->fieldCurrency(mysql_real_escape_string($reg[6])).",
									".$this->fieldNumber(mysql_real_escape_string($reg[7])).",
									".$this->fieldNumber(mysql_real_escape_string($reg[8])).",
									'".mysql_real_escape_string($reg[9])."'
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
						
						} elseif (strtoupper(trim($reg[9])) == "VA") {
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
							
							$oProposta = new proposta();
							$aDadosProposta = $oProposta->getPropostaProponente($iCodUsua);
							if ($aDadosProposta[0]["cod_ppst"]) {
								$oProposta->setPropostaRespostaValor($aDadosProposta[0]["cod_ppst"]);
							}
							$this->query = "
								UPDATE listadenomes SET 
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
						} elseif (strtoupper(trim($reg[9])) == "A") {
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
							
							$oProposta = new proposta();
							$aDadosProposta = $oProposta->getPropostaProponente($iCodUsua);
							if ($aDadosProposta[0]["cod_ppst"]) {
								$oProposta->setPropostaAprovacaoPrevi($aDadosProposta[0]["cod_ppst"],"S");
							}
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
		
		//$fileName = "../athosfiles/COFSP909.ATHOS.SAI";
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
			$this->setFileArr($dados);
			$this->setNumRows(@count($this->getFileArr()));
			$this->goLine(1);
		} else {
			$this->setErr(99,"Não foi possível abrir o arquivo.");
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
	
	function getArquivoRegistro($cod_ppst,$cod_arqu,$remessa = false) {
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
				cod_arqu = '".mysql_real_escape_string($cod_arqu)."'
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
?>