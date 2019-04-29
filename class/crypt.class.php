<?
/*******************************************************************************
*  Arquivo: chasses.inc.php
*  Autor: Paulo H. Lomanto (paulo@icreations.com.br)
*  Descricao: Arquivo de criptografia de texto, que pode ser utilizado para 
*    criptografar desde pequenos textos a ate metodos GET inteiros.
*    Esta funcao permite agir com transpagencia na criptografia de urls,
*    de tal forma que uma url do tipo teste.php?v1=teste1&v2=teste2 seja 
*    transformada em teste.php?tk=dWAdsfWEVGaa4erg21Qf5fsa, e possa ser
*	 reconstruida transformando-se o valor criptografado automaticamente
*    em $_GET["v1"] = "teste1" e $_GET["v2"] = "teste2".
*  Como usar:
*    Crie uma instancia 
*      $objt = new crypt_class([int chave], [float fator]);
*    onde:
*      chave = chave criptografica, um valor inteiro entre 1100000 e 99999900
*              Se o valor nao for passado, o valor 55005500 sera assumido 
*              automaticamente
*      fator = valor de indice utilizado para a criptografia, do tipo float,
*              entre 1.1 e 1.9299999999. Caso nao seja passado, o valor 1.5
*              sera assumido autoimaticamente.
*
*    Criptografe um texto:
*       mixed $objt->encrypt(string texto);
*    onde:
*      texto = texto que se deseja criptografar
*    retornos possiveis:
*      texto criptografado, em caso de sucesso
*      false, caso falhe
*
*    Descriptografe um texto:
*       mixed $objt->decrypt(string texto);
*    onde:
*      texto = texto que se deseja descriptografar
*    retornos possiveis:
*      texto descriptografado, em caso de sucesso
*      false, caso falhe
*
*    Descriptografe um texto para o array GET:
*       mixed $objt->decrypt_array(string texto);
*    onde:
*      texto = texto que se deseja descriptografar
*    retornos possiveis:
*      true, em caso de sucesso (o resultado estara armazenado no array $_GET)
*      false, caso falhe
*    Observacoes:
*      caso o valor criptografado seja, por exemplo, valor1&valor2&..valorN,
*      as chaves do $_GET serao "decrypted_1", "decrypted_2" .. "decrypted_N",
*      onde os numeros serao acrescentados em 1 a cada chave em branco passada.
*
*  Historico:
*    V 1.1 - 03-04-2004 - Adicionado 6 caracteres randomicos (48 bits) 
*		ao inicio da string original e implementado funcionalidade para retirar
*		esses mesmos 48 bits nas funcoes de descriptografia, para que as 
*		criptografadas sejam sempre diferentes umas das outras strings.
*    V 1.0 - 27-02-2004 - Primeira versao funcional da classe.
*******************************************************************************/

class crypt_class {
	var $mapa_caractere;
	var $chave;
	var $fator;

/* funcao construtora */
	function crypt_class($chave = false, $fator = false) {
		// este mapa TEM QUE TER 64 caracteres. Nao me responsabilizo caso seja utilizado um 
		//   numero menor de caracteres.
		$this->mapa_caractere = "_.0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$this->fator = ($fator >= 1.1 && $fator <= 1.9299999999) ? $fator : 1.5;
		$this->chave = ($chave >= 11000000 && $chave < 99999900) ? $chave : 55005500;
	}

/* faz a criptografia do texto */
	function encrypt($texto = false) {
		if ($texto) {
			$texto_tmp  = substr($this->mapa_caractere, mt_rand(0,63), 1);
			$texto_tmp .= substr($this->mapa_caractere, mt_rand(0,63), 1);
			$texto_tmp .= substr($this->mapa_caractere, mt_rand(0,63), 1);
			$texto_tmp .= substr($this->mapa_caractere, mt_rand(0,63), 1);
			$texto = $texto_tmp."&".$texto;
			if (substr(decbin(strlen($texto)),-1) == 1) {
				$texto_final = $texto . chr(255);
				$cadeia_6 = "1";
			} else {
				$texto_final = $texto;
				$cadeia_6 = "0";
			}
			$cadeia_configuracao = substr(str_pad(decbin(mt_rand(1,255)),6,"1",STR_PAD_LEFT), 1, 5) . $cadeia_6;
			$text_len = strlen($texto_final);
			$indice_coleta = 3;
			for ($i=0;$i<$text_len;$i++) {
				$binario .= str_pad(decbin(ceil((ord(substr($texto_final,$i,1)) + substr($this->chave, $indice_coleta, 1)) * $this->fator)), 9, "0", STR_PAD_LEFT);
				$indice_coleta = substr(decoct($indice_coleta + 3), -1);
			}
			$binario .= $cadeia_configuracao;
			$tamanho_binario = strlen($binario) - 1;
			for ($i=0;$i<$tamanho_binario;$i=$i+6) {
				$binario_reduzido = substr($binario, $i, 6);
				$string_criptografada .= substr($this->mapa_caractere, bindec($binario_reduzido), 1);
			}
			return $string_criptografada;
		} else {
			return false;
		}
	}

/* faz a descriptografia do texto */
	function decrypt($texto = false) {
		if ($texto) {
			$string_criptografada = $texto;
			$tamanho_criptografado = strlen($string_criptografada) - 1;
			for ($i=0;$i<=$tamanho_criptografado;$i++) {
				$binario_original .= str_pad(decbin(strpos($this->mapa_caractere, substr($string_criptografada, $i, 1))), 6, "0", STR_PAD_LEFT);
			}
			$configuracao = substr($binario_original, -6);
			$binario_original = substr($binario_original, 0, -6);
			if (substr($configuracao,-1) == 1) {
				$binario_original = substr($binario_original, 0, -9);
			}
			$tamanho_binario = strlen($binario_original) - 1;
			$indice_coleta = 3;
			unset($string_descriptografada);
			for ($i=0;$i<=$tamanho_binario;$i=$i+9) {
				$tmp_conversao = substr($binario_original, $i, 9);
				$tmp_conversao = floor(bindec($tmp_conversao) / $this->fator) - substr($this->chave, $indice_coleta, 1);
				$string_descriptografada .= chr($tmp_conversao);
				$indice_coleta = substr(decoct($indice_coleta + 3), -1);
			}
			$string_descriptografada = substr($string_descriptografada, 5);
			return $string_descriptografada;
		} else {
			return false;
		}
	}

/* faz a descriptografia e transforma os valores passados em $_GET */
	function decrypt_array($texto = false) {
		if ($texto) {
			$valores = $this->decrypt($texto);
			if ($valores) {
				if (eregi("&", $valores)) {
					$valores_quebrados = explode("&", $valores);
					for ($i=0; $i<=count($valores_quebrados)-1; $i++) {
						if (eregi("=", $valores_quebrados[$i])) {
							$chave_valor = explode("=", $valores_quebrados[$i]);
							$_GET[$chave_valor[0]] = $chave_valor[1];
						} else {
							$_GET["decrypted_".$i] = $valores_quebrados[$i];
						}
					}
				} elseif (eregi("=", $valores)) {
					$chave_valor = explode("=", $valores);
					$_GET[$chave_valor[0]] = $chave_valor[1];
				} else {
					$_GET["decrypted_1"] = $valores;
				}
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
/* * * * *  E N D   O F   C L A S S  * * * * * * */
}

/*EOF*/
?>
