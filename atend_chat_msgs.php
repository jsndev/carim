<?
	$iREQ_AUT=1;
	$aUSERS_PERM[]=2;
	// incluindo arquivo de configuração e classes
	include "./class/dbclasses.class.php";
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
		$_SESSION['CHAT_ERRO'] = -1; // ainda nao entrou nenhum ATEN
	}

	if($_SESSION['CHAT']=='' || $_SESSION['CHAT'] < 0){
		$db->query="SELECT COD_CHAT, COD_USUA, COD_CHTU FROM chatsessoes WHERE (COD_USUA is not NULL OR COD_CHTU is not NULL) and FLG_DISP=0 AND DT_USUA > (now()-".mysql_real_escape_string($iExpireChat).") ORDER BY COD_CHAT LIMIT 1";
		$db->query();
		if($db->qrcount > 0){
			$_SESSION['CHAT'] = $db->qrdata[0]["COD_CHAT"];
			$_SESSION['USUA'] = $db->qrdata[0]["COD_USUA"];
			$_SESSION['CHTU'] = $db->qrdata[0]["COD_CHTU"];
			$_SESSION['CHAT_ERRO'] = 0;
			$db->query="UPDATE chatsessoes SET FLG_DISP=1, COD_ATEN='".mysql_real_escape_string($cLOGIN->iID)."' WHERE COD_CHAT='".mysql_real_escape_string($_SESSION['CHAT'])."'";
			$db->query();
			$db->query="INSERT INTO chatmensagens ( DT_CHTM, COD_ATEN, COD_USUA, COD_CHTU, MSG_OWN, FLG_USER, FLG_ATEN, MENSAGEM, COD_CHAT ) VALUES ( now(), '".mysql_real_escape_string($cLOGIN->iID)."', '".mysql_real_escape_string($_SESSION['USUA'])."', NULL, 2, 0, 0, 'Entrou no chat ...', '".mysql_real_escape_string($_SESSION['CHAT'])."' )"; 
			$db->query();
		}else{
			if($_SESSION['CHAT_ERRO'] == -2){
				$db->query="INSERT INTO chatsessoes (FLG_DISP, COD_ATEN) VALUES (0,'".mysql_real_escape_string($cLOGIN->iID)."')"; 
				$db->query();
				$_SESSION['CHAT'] = $db->getInsertId();
				$mensagem = '<div class="msgAguarde">Nenhum Proponente está Aguardando ...</div>';
				$db->query="INSERT INTO chatmensagens ( DT_CHTM, COD_ATEN, COD_USUA, COD_CHTU, MSG_OWN, FLG_USER, FLG_ATEN, MENSAGEM, COD_CHAT ) VALUES ( now(), '".mysql_real_escape_string($cLOGIN->iID)."', NULL, NULL, 0, 1, 1, '".mysql_real_escape_string($mensagem)."', '".mysql_real_escape_string($_SESSION['CHAT'])."' )"; 
				$db->query();
				$output_chat[] = array( date('d/m/Y-h:i:s'), $mensagem, '' );
				$db->query="INSERT INTO chatmensagens ( DT_CHTM, COD_ATEN, COD_USUA, COD_CHTU, MSG_OWN, FLG_USER, FLG_ATEN, MENSAGEM, COD_CHAT ) VALUES ( now(), '".mysql_real_escape_string($cLOGIN->iID)."', '".mysql_real_escape_string($_SESSION['USUA'])."', NULL, 2, 0, 0, 'Entrou no chat ...', '".mysql_real_escape_string($_SESSION['CHAT'])."' )"; 
				$db->query();
			}
			$_SESSION['CHAT_ERRO']--;
			if($_SESSION['CHAT_ERRO'] <= -3){ $_SESSION['CHAT_ERRO'] = -3; }
		}
	}else{
		// ver se o ATEN caiu ...
		if($_SESSION['CHAT_ERRO'] < 2){
			$db->query="SELECT COD_USUA, COD_CHTU FROM chatsessoes WHERE COD_CHAT='".mysql_real_escape_string($_SESSION['CHAT'])."' AND DT_USUA > (now()-".mysql_real_escape_string($iExpireChat).")"; 
			$db->query();
			if($db->qrcount > 0){
				$_SESSION['CHAT_ERRO'] = 0;
				$_SESSION['USUA'] = $db->qrdata[0]["COD_USUA"];
				$_SESSION['CHTU'] = $db->qrdata[0]["COD_CHTU"];
			}else{
				if($_SESSION['CHAT_ERRO'] >= 0){
					$_SESSION['CHAT_ERRO']++;
					if($_SESSION['CHAT_ERRO'] == 2){
						$db->query="UPDATE chatsessoes SET FLG_DISP=2 WHERE COD_CHAT='".mysql_real_escape_string($_SESSION['CHAT'])."'"; 
						$db->query();
						$mensagem = '<div class="msgLogoff">O Proponente saiu do Chat!</div>';
						$db->query="INSERT INTO chatmensagens ( DT_CHTM, COD_ATEN, COD_USUA, COD_CHTU, MSG_OWN, FLG_USER, FLG_ATEN, MENSAGEM, COD_CHAT ) VALUES ( now(), '".mysql_real_escape_string($cLOGIN->iID)."', '".mysql_real_escape_string($_SESSION['USUA'])."', NULL, 0, 0, 0, '".mysql_real_escape_string($mensagem)."', '".mysql_real_escape_string($_SESSION['CHAT'])."' )"; 
						$db->query();
						$_SESSION['USUA'] = '';
						$_SESSION['CHTU'] = '';
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
								a.NOME_USUA, u.NOME_CHTU
							FROM chatmensagens c
							LEFT JOIN usuario a
								ON (a.COD_USUA=c.COD_USUA)
							LEFT JOIN chatusers u
								ON (u.COD_CHTU=c.COD_CHTU)
              WHERE c.COD_CHAT='".mysql_real_escape_string($_SESSION['CHAT'])."'
                AND c.FLG_ATEN = 0
              ORDER BY c.DT_CHTM";
	$db->query();
	//print $db->query.'<hr>';
	if($db->qrcount > 0){
		$aMensagens = $db->qrdata;
		// marcando as mensagens como LIDAS //
		$db->query="UPDATE chatmensagens SET FLG_ATEN = 1 WHERE COD_CHAT='".mysql_real_escape_string($_SESSION['CHAT'])."'";
		$db->query();

		foreach($aMensagens as $k=>$v){
			$nome_user = '';
			switch($v['MSG_OWN']){
				case 1:
					if($v['NOME_USUA']==''){
						$nome_user = '<span class="nick_aten">'.$v['NOME_CHTU'].':</span>';
					}else{
						$nome_user = '<span class="nick_aten">'.$v['NOME_USUA'].':</span>';
					}
					break;
				case 2:
					$nome_user = '<span class="nick_prop">'.$cLOGIN->cUSUARIO.':</span>';
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