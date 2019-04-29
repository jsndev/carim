<?
	// incluindo arquivo de configuração e classes
	include "./class/dbclasses.class.php";

	if(!isset($_SESSION['CODCHTU'])){
		print 'USUARIO NAO LOGADO';
		exit();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<style>
	* { font-family: Verdana; font-size:7pt; }
	body { margin:0px; padding:1px; }
	</style>
	<script language="JavaScript">
		function printMsg(){
			var msg = document.getElementById('msgOut').innerHTML;
			parent.msgOut(msg);
		}
		function txtDisabled(){
			parent.txtDisabled();
		}
		function txtEnabled(){
			parent.txtEnabled();
		}
	</script>
</head>
<body>
<?
	$aMensagens = array();
	$output_chat = array();
	
	if(!isset($_SESSION['CHAT_ERRO'])){
		$_SESSION['CHAT_ERRO'] = -2; // Todos Atendentes ocupados
	}

	if($_SESSION['CHAT']=='' || $_SESSION['CHAT'] < 0){
		$db->query="
			SELECT 
				COD_CHAT, 
				COD_ATEN 
			FROM 
				chatsessoes 
			WHERE 
				COD_ATEN is not NULL and 
				FLG_DISP=0 AND 
				DT_ATEN > (now()-".mysql_real_escape_string($iExpireChat).")  
			ORDER BY 
				COD_CHAT 
			LIMIT 1";
		$db->query();
		if($db->qrcount > 0){
			$_SESSION['CHAT'] = $db->qrdata[0]["COD_CHAT"];
			$_SESSION['ATEN'] = $db->qrdata[0]["COD_ATEN"];
			$_SESSION['CHAT_ERRO'] = 0;
			$db->query="
				UPDATE chatsessoes SET 
					FLG_DISP=1, 
					COD_CHTU='".mysql_real_escape_string($_SESSION['CODCHTU'])."' 
				WHERE 
					COD_CHAT='".mysql_real_escape_string($_SESSION['CHAT'])."'"; 
			$db->query();
			$db->query="
				INSERT INTO chatmensagens ( 
					DT_CHTM, 
					COD_ATEN, 
					COD_USUA, 
					COD_CHTU, 
					MSG_OWN, 
					FLG_USER, 
					FLG_ATEN, 
					MENSAGEM, 
					COD_CHAT 
				) VALUES ( 
					now(), 
					'".mysql_real_escape_string($_SESSION['ATEN'])."', 
					NULL, 
					'".mysql_real_escape_string($_SESSION['CODCHTU'])."', 
					1, 
					0, 
					0, 
					'Entrou no chat ...', 
					'".mysql_real_escape_string($_SESSION['CHAT'])."' 
				)"; 
			$db->query();
		}else{
			if($_SESSION['CHAT_ERRO'] == -2){
				$db->query="
					INSERT INTO chatsessoes (
						FLG_DISP, 
						COD_CHTU
					) VALUES (
						0,
						".mysql_real_escape_string($_SESSION['CODCHTU'])."
					)"; 
				$db->query();
				$_SESSION['CHAT'] = $db->getInsertId();
				$mensagem = '<div class="msgAguarde">Todos Atendentes estão ocupados. Aguarde...</div>';
				$db->query="
					INSERT INTO chatmensagens ( 
						DT_CHTM, 
						COD_ATEN, 
						COD_USUA, 
						COD_CHTU, 
						MSG_OWN, 
						FLG_USER, 
						FLG_ATEN, 
						MENSAGEM, 
						COD_CHAT 
					) VALUES ( 
						now(), 
						NULL, 
						NULL, 
						'".mysql_real_escape_string($_SESSION['CODCHTU'])."', 
						0, 
						1, 
						1, 
						'".mysql_real_escape_string($mensagem)."', 
						'".mysql_real_escape_string($_SESSION['CHAT'])."' 
					)"; 
				$db->query();
				$output_chat[] = array( date('d/m/Y-h:i:s'), $mensagem, '' );
				$db->query="
					INSERT INTO chatmensagens ( 
						DT_CHTM, 
						COD_ATEN, 
						COD_USUA, 
						COD_CHTU, 
						MSG_OWN, 
						FLG_USER, 
						FLG_ATEN, 
						MENSAGEM, 
						COD_CHAT 
					) VALUES ( 
						now(), 
						'".mysql_real_escape_string($_SESSION['ATEN'])."', 
						NULL, 
						'".mysql_real_escape_string($_SESSION['CODCHTU'])."', 
						1, 
						0, 
						0, 
						'Entrou no chat ...', 
						'".mysql_real_escape_string($_SESSION['CHAT'])."' 
					)"; 
				$db->query();
			}
			$_SESSION['CHAT_ERRO']--;
			if($_SESSION['CHAT_ERRO'] <= -3){ $_SESSION['CHAT_ERRO'] = -3; }
		}
	}else{
		// ver se o ATEN caiu ...
		if($_SESSION['CHAT_ERRO'] < 2){
			$db->query="SELECT COD_ATEN FROM chatsessoes WHERE COD_CHAT='".mysql_real_escape_string($_SESSION['CHAT'])."' AND DT_ATEN > (now()-".mysql_real_escape_string($iExpireChat).")"; 
			$db->query();
			if($db->qrcount > 0){
				$_SESSION['CHAT_ERRO'] = 0;
				$_SESSION['ATEN'] = $db->qrdata[0]["COD_ATEN"];
			}else{
				if($_SESSION['CHAT_ERRO'] >= 0){
					$_SESSION['CHAT_ERRO']++;
					if($_SESSION['CHAT_ERRO'] == 2){
						$db->query="
							UPDATE chatsessoes SET 
								FLG_DISP=2 
							WHERE 
								COD_CHAT='".mysql_real_escape_string($_SESSION['CHAT'])."'"; 
						$db->query();
						$mensagem = '<div class="msgLogoff">O Atendente saiu do Chat!</div>';
						$db->query="
							INSERT INTO chatmensagens ( 
								DT_CHTM, 
								COD_ATEN, 
								COD_USUA, 
								COD_CHTU, 
								MSG_OWN, 
								FLG_USER, 
								FLG_ATEN, 
								MENSAGEM, 
								COD_CHAT 
							) VALUES ( 
								now(), 
								'".mysql_real_escape_string($_SESSION['ATEN'])."', 
								NULL, 
								'".mysql_real_escape_string($_SESSION['CODCHTU'])."', 
								0, 
								1, 
								1, 
								'".mysql_real_escape_string($mensagem)."', 
								'".mysql_real_escape_string($_SESSION['CHAT'])."' 
							)"; 
						$db->query();
						$_SESSION['ATEN'] = '';
						$_SESSION['CHAT'] = '';
						$output_chat[] = array( date('d/m/Y-h:i:s'), $mensagem, '' );
						$_SESSION['CHAT_ERRO']=-2;
					}
				}else{
					$_SESSION['CHAT_ERRO']--;
					if($_SESSION['CHAT_ERRO'] <= -3){ $_SESSION['CHAT_ERRO'] = -3; }
				}
			}
		}
	}
	

	// pegando as mensagens NAO LIDAS //
	$db->query="SELECT c.*,
								date_format(c.DT_CHTM,'%d/%m/%Y-%h%:%i:%s') as datachat,
								u.NOME_CHTU, a.NOME_USUA
							FROM chatmensagens c, chatusers u, usuario a
              WHERE c.COD_CHAT=".mysql_real_escape_string($_SESSION['CHAT'])."
                AND c.FLG_USER = 0
                AND u.COD_CHTU=c.COD_CHTU
                AND a.COD_USUA=c.COD_ATEN
              ORDER BY c.DT_CHTM";
	$db->query();
	if($db->qrcount > 0){
		$aMensagens = $db->qrdata;
		// marcando as mensagens como LIDAS //
		$db->query="UPDATE chatmensagens SET FLG_USER = 1 WHERE COD_CHAT='".mysql_real_escape_string($_SESSION['CHAT'])."'"; 
		$db->query();

		foreach($aMensagens as $k=>$v){
			$nome_user = '';
			switch($v['MSG_OWN']){
				case 1:
					$nome_user = '<span class="nick_prop">'.$v['NOME_CHTU'].':</span>';
					break;
				case 2:
					$nome_user = '<span class="nick_aten">'.$v['NOME_USUA'].':</span>';
					break;
			}
			$output_chat[] = array( $v['datachat'], $nome_user, nl2br($v['MENSAGEM']) );
		}
	}
	
?>
	<div id="msgOut"><?
		if( count($output_chat) > 0){
			foreach($output_chat as $k=>$v){
				print '<div class="dthr">('.$v[0].')</div>';
				print '<div class="divMsg"><b>'.$v[1].'</b> '.$v[2].'</div>';
			}
		}
	?></div>
	<script language="JavaScript">
	<?
		if(count($output_chat) > 0){ print "printMsg();"; }
		
		if($_SESSION['CHAT_ERRO'] < 0){
			print "txtDisabled();";
		}elseif($_SESSION['CHAT_ERRO'] > 1){
			print "txtDisabled();";
		}else{
			print "txtEnabled();";
		}
	?>
	</script>
	<?
		print $_SESSION['CHAT'].'/'.$_SESSION['CHAT_ERRO'].' <b>MSG:</b> '.microtime();
	?>
</body>
</html>