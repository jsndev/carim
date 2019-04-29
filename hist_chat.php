<?
// incluindo arquivo de configuração e classes
include "./class/dbclasses.class.php";

$pageTitle = "Chat Contrathos";
include "lib/header_chat.inc.php";
?>
	<style>
  .ifh{ width:350px; height:40px; margin:3px; }
  </style>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
			<div id="divMensagens" class="divMensagens" style="height:300px;">
				<?
					// pegando as mensagens //
					$db->query="SELECT c.*,
								date_format(c.DT_CHTM,'%d/%m/%Y-%h%:%i:%s') as datachat,
								a.NOME_USUA, u.NOME_CHTU
							FROM chatmensagens c
							LEFT JOIN usuario a
								ON (a.COD_USUA=c.COD_ATEN)
							LEFT JOIN chatusers u
								ON (u.COD_CHTU=c.COD_CHTU)
              WHERE c.COD_CHAT=".mysql_real_escape_string($_GET['codchat'])."
              ORDER BY c.DT_CHTM";
					$db->query();
					//print $db->query.'<hr>';
					
					if($db->qrcount > 0){
						foreach($db->qrdata as $k=>$v){
							$nome_user = '';
							switch($v['MSG_OWN']){
								case 1:
									$nome_user = '<span class="nick_prop">'.$cLOGIN->cUSUARIO.':</span>';
									break;
								case 2:
									$nome_user = '<span class="nick_aten">'.$v['NOME_USUA'].':</span>';
									break;
							}
							print '<div class="dthr">('.$v['datachat'].')</div>';
							print '<div class="divMsg"><b>'.$nome_user.'</b> '.nl2br($v['MENSAGEM']).'</div>';
						}
					}
				?>
			</div>
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
	</div>
<?
include "lib/footer_chat.inc.php";
?>