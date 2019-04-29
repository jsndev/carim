<?
// se a classe já foi incluida, não deixa incluir de novo.
if (!isset($iCLOGIN_INCLU))
	$iCLOGIN_INCLU=1;
else
	return 1;

class login extends manidados {
	var $bOK;
	var $cERRO;
	var $cNOME;
	var $cLOGIN;
	var $iID;
	var $iLEVEL_USUA;
	var $vCHAVELOGIN;
	var $vHORARIO;
	var $iCODPPST;
	var	$dDATA;
	var $dHORA;

	function login(){
		$this->bOK = 0;
		$this->cERRO="";
	}

	function desloga(){ // desloga o cara -  limpa as sessões
		if(isset($_SESSION['LOGIN'])){
			$this->insert_log(2,2,'Logout de Usuario: '.$_SESSION['LOGIN']); // inserindo o log 
	
			// deslogando o cara
			$this->bOK    = 0;
			$this->cERRO  = "";
			$this->cNOME  = "";
			$this->cLOGIN = "";
			$this->iID    = "";
			$this->iLEVEL_USUA = "";
			$this->vCHAVELOGIN = "";
			$this->vHORARIO    = "";
			
			unset($_SESSION['CODI_USER']);
			unset($_SESSION['USUARIO']);
			unset($_SESSION['LOGIN']);
			unset($_SESSION['LEVEL_USUA']);
			unset($_SESSION['CODPPST']);
			unset($_SESSION['CHAVELOGIN']);
			unset($_SESSION['HORARIO']);
		}
	}

	function autentica_user($func_login, $func_senha) {// Loga o cara no banco, se ele existir, dá uma chave pra ele.
		if (empty($func_login)) { // se usuário em branco, devolve msg.
			$this->cERRO="Nome de usuário em branco";
			return false;
		} elseif (empty($func_senha)) { // se senha em branca, devolve msg.
			$this->cERRO="Senha em branco";
			return false;
		} else { // caso contrário, tenta logar o cara.
		
		
			// Verificacao de tentativas de acesso
			$this->query="
				SELECT 
					cod_usua,
					tentativasacesso_usua,
					dtbloqueio_usua,
					time_to_sec(timediff(now(),dtbloqueio_usua))/60 as minutos_bloqueio
				from 
					usuario 
				where 
					email_usua='".mysql_real_escape_string($func_login)."'";
			$this->query();
			$aDadosTentativaAcesso = $this->qrdata[0];
//			print_r($aDadosTentativaAcesso);
			if ((int)$aDadosTentativaAcesso["minutos_bloqueio"] > 15) {
				$this->query = "
					UPDATE
						usuario
					SET
						tentativasacesso_usua = null,
						dtbloqueio_usua = null
					WHERE
						email_usua = '".mysql_real_escape_string($func_login)."'
				";
				$this->query();
				unset($aDadosTentativaAcesso);
			}

			$this->query="select 
							cod_usua,
							nome_usua,
							email_usua,
							level_usua
						  from 
							usuario 
						  where 
							email_usua='".mysql_real_escape_string($func_login)."' 
						  and 
							pwd_usua=md5('".mysql_real_escape_string($func_senha)."')";
			$this->query();

			//$aDADOS_USER=$bd->master_query($cQUERY);
			
			if ($this->qrcount >= 1 && ($aDadosTentativaAcesso["minutos_bloqueio"] == "" || $aDadosTentativaAcesso["minutos_bloqueio"] > 15)) { // usuário e senhas corretos
				srand((double)microtime()*1000000);	         // Inicializa random
				$id_login		   = $this->qrdata[0]['cod_usua'];
				$nome_usua		 = $this->qrdata[0]['nome_usua'];
				$email_usua		 = $this->qrdata[0]['email_usua'];
				$level_usua		 = $this->qrdata[0]['level_usua'];
				$ip				     = $_SERVER["REMOTE_ADDR"];
				$horario		   = time();					// Segundos desde 1 jan 1970
				$identificacao = crypt($ip, rand(1,99));	// Criptografa o IP randomicamente

				$this->iID         = $id_login;
				$this->cNOME       = $nome_usua;
				$this->cLOGIN      = $email_usua;
				$this->iLEVEL_USUA = $level_usua;
				$this->vCHAVELOGIN = $identificacao;
				$this->cERRO       = "";
				$this->vHORARIO    = $horario;
				
				if($this->iLEVEL_USUA==1){
					$oProponente = new proponente();
					$aPpstProp = $oProponente->pesquisarPorUsuario($this->iID);
					$this->iCODPPST = $aPpstProp[0]["cod_ppst"];
				}
				
				$_SESSION['CODI_USER']         = $id_login;
				$_SESSION['USUARIO']           = $nome_usua;
				$_SESSION['LOGIN']             = $email_usua;
				$_SESSION['LEVEL_USUA']        = $level_usua;
				$_SESSION['CODPPST']           = $this->iCODPPST;
				$_SESSION['CHAVELOGIN']        = $identificacao;
				$_SESSION['HORARIO']           = $horario;
				$_SESSION['sessao_usua']	   = session_id();

				$this->query = "
					UPDATE
						usuario
					SET
						tentativasacesso_usua = null,
						dtbloqueio_usua = null,
						sessao_usua = '".mysql_real_escape_string(session_id())."'
					WHERE
						cod_usua = '".mysql_real_escape_string($id_login)."'
				";
				$this->query();

				$this->insert_log(1,1,'Login de Usuario: '.$func_login); // inserindo o log 
				
				$this->manter_user(); // jogando informacoes do user na classe
				return true; // devolve a chave pro cara.
			} else { // Senha Inválida
				
				if (($aDadosTentativaAcesso["tentativasacesso_usua"]+1) > 2) {
					if (($aDadosTentativaAcesso["tentativasacesso_usua"]+1) > 3) {
						$sDataBloqueio = "";
					} else {
						$sDataBloqueio = ", dtbloqueio_usua = now()";
					}
					$this->query = "
						UPDATE
							usuario
						SET
							tentativasacesso_usua = IFNULL(tentativasacesso_usua,0)+1
							".$sDataBloqueio."
						WHERE
							email_usua='".mysql_real_escape_string($func_login)."'
					";
					$this->query();
					$this->insert_log(1,1,'Usuário '.$func_login.' bloqueado por 3 ou mais tentativas de acesso inválidas'); // inserindo o log 
					$this->cERRO="Sua senha foi bloqueada por 3 ou mais tentativas de login sem sucesso. Por favor, aguarde o desbloqueio que ocorrerá automaticamente em 15 minutos."; 
					//$aDadosTentativaAcesso["tentativasacesso_usua"]
				} else {
					$this->query = "
						UPDATE
							usuario
						SET
							tentativasacesso_usua = IFNULL(tentativasacesso_usua,0)+1,
							dtbloqueio_usua = null
						WHERE
							email_usua='".mysql_real_escape_string($func_login)."'
					";
					$this->query();
					$this->insert_log(1,1,'Tentativa de Login de Usuario: '.$func_login); // inserindo o log 
					$this->cERRO="Usuário ou senha inválida!"; 
				}
				return false;
			}
		}
	}

	function manter_user() { // atualiza o ultimo acesso do user e desloga os que estão logados a muito tempo sem nenhuma acao
		global $iTEMPO_EXPIRAR,$session;
		
		$this->query = "
			SELECT
				sessao_usua
			FROM
				usuario
			WHERE
				cod_usua = '".mysql_real_escape_string($_SESSION['CODI_USER'])."'
		";
		$this->query();
		$sSessaoUsuario = $this->qrdata[0]['sessao_usua'];
		
		if (($_SESSION['sessao_usua'] != $sSessaoUsuario) && $_SESSION['CODI_USER'] != '') {
			$session->setMessage('Usuário em uso em outro computador.');
			$this->iID = $_SESSION['CODI_USER'];
			$this->insert_log(1,1,'Usuario '.$_SESSION['USUARIO'].'('.$_SESSION['CODI_USER'].')'.' deslogado por estar em uso em outra máquina. IP do outro terminal: '.$_SERVER['REMOTE_ADDR']); // inserindo o log 
			$this->desloga();
			return false;
		}
		
		// Dados do Usuário 
		$ip		      = $_SERVER["REMOTE_ADDR"]; // IP Remoto
		$horario    = time(); // Segundos desde 1 jan 1970
		$expiracao  = ($horario - TIME_EXP_LOGIN); // Tempo de Expiração em segundos

		$this->iID                = $_SESSION['CODI_USER'];
		$this->cUSUARIO           = $_SESSION['USUARIO'];
		$this->cLOGIN             = $_SESSION['LOGIN'];
		$this->iLEVEL_USUA        = $_SESSION['LEVEL_USUA'];
		$this->iCODPPST           = $_SESSION['CODPPST'];
		$this->vCHAVELOGIN        = $_SESSION['CHAVELOGIN'];
		$this->vHORARIO           = $_SESSION['HORARIO'];

		if($this->vHORARIO < $expiracao) {
			$this->desloga();
		} else {
			$_SESSION['HORARIO'] = $horario;
			$this->vHORARIO = $_SESSION['HORARIO'];
			$this->bOK=1;
			$this->cERRO="";
		}

		// pegando a hora e data do banco
		$this->query="select 
						date_format(now(),'%d/%m/%Y') as data,
						date_format(now(),'%H:%i:%s') as hora";
		$this->query();
		$this->dDATA=$this->qrdata[0]['data'];
		$this->dHORA=$this->qrdata[0]['hora'];

		//$cQUERY="delete from user_ativo where horario_user_ativo < $expiracao";
		//$bd->just_query($cQUERY); // desloga os usuários que estão logados há muito tempo sem atividade		
		//$cQUERY="select codi_user from user_ativo where ip_user_ativo='$ip' and identificador_user_ativo='".$_SESSION['CHAVELOGIN']."'";
		/*
		//$aDADOS_SESSAO=$bd->master_query($cQUERY); // pega os dados do user de acordo com sua chave de login		
		$bd->numrows = 1;
		if ($bd->numrows == 1) { // se o user ainda estiver logado, vai entrar aqui
			//$cQUERY="update user_ativo set horario_user_ativo='$horario' where ip_user_ativo='$ip' and identificador_user_ativo='".$_SESSION['CHAVELOGIN']."'";
			//$bd->just_query($cQUERY); // Atualiza a identificacao do user
			//$this->iID  = $aDADOS_SESSAO[0]['CODI_USER'];  // Pega id do user logado no sistema
			//$cQUERY="update user set last_logout_user=now() where codi_user=".$this->iID;
			//$bd->just_query($cQUERY); // atualiza o IP e as datas de login.				
			$this->bOK=1;
			$this->cERRO="";
		} else { // user ficou muito tempo sem mecher no sistema ou não tava logado
			$this->bOK=0;
		}
		*/
		return;
	}
} // termino da classe
?>