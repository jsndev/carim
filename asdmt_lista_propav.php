<?
$iREQ_AUT=1;
$aUSERS_PERM[]=8;
$pageTitle = "Participantes";
include "lib/header.inc.php";
function diferenca_datas($inicio,$fim){
	//FAZ ARRAY $feriados COM TODOS OS FERIADOS DO INTERVALO DOS MESES
	$feriados = array();
	for($i=0;mktime(0,0,0,date('m',$inicio) + $i,1,date('Y',$inicio)) <=
		mktime(0,0,0,date('m',$fim),1,date('Y',$fim));$i++){
		$data = mktime(0,0,0,date('m',$inicio) + $i,1,date('Y',$inicio));
		$arq = "feriados/".date('Y',$data)."/".date('m',$data).".txt";
		if(file_exists($arq)){
			$file = fopen ($arq, "r" );
			$bd = fread($file, filesize($arq));
			fclose($file);
			$bd = explode("|",$bd);
			$feriados = array_merge($feriados,$bd);
		}
	}
	//SOMA DIAS UTEIS (QDO E DIFERENTE DE SABADO, DOMINGO E NAO ESTA EM $feriados)
	$num=0;
	$i=86400;
	while($inicio + $i <= $fim){
		if(date('w',$inicio+$i) != 0 and date('w',$inicio + $i) != 6){
			if(!in_array($inicio+$i,$feriados))
			$num++;
		}
		$i+=86400;
	}
	return $num;
}

?>
<script language="JavaScript" src="./js/diversos.js"></script>

<form name="filtro" method="post" action="<?=$_SERVER["PHP_SELF"];?>">

	<br><b>Filtros</b>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
      <table cellpadding=0 cellspacing=5 border=0>
        <tr>
          <td align="right">Proposta:</td>
          <td align="left"><input type="text" name="filtro_nome" value="<?=$_POST["filtro_nome"];?>" size="50"></td>
        <tr>
        	<td align="right">&nbsp;</td>
          <td align="left">
          	<input type="image" name="btFiltrar" id="btFiltrar" src="images/buttons/bt_filtrar.gif" value="Filtrar" class="im" />&nbsp;
          	<a href="atend_lista_prop.php"><img src="images/buttons/bt_limparfiltros.gif" alt="Limpar Filtros" class="im" /></a>
          </td>
        </tr>
      </table>
	  </div>
			<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>

		<br><b>Lista de Participantes</b>
<div class="quadroInterno">
			<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
			<div class="quadroInternoMeio">
			<?
				$filtros = '';
				if(!empty($_POST["filtro_nome"])) {
					$f_query_nome="and a.nome_usua like '%".mysql_real_escape_string($_POST["filtro_nome"])."%' ";
					$filtros .= ' [NOME:'.$_POST["filtro_nome"].'] ';
				}
				if(!empty($_POST["filtro_cpf"])) {
					$f_prop_cpf = mysql_real_escape_string(preg_replace('/\D/i','',$_POST["filtro_cpf"]));
					$f_query_cpf="and c.cpf_ppnt='".mysql_real_escape_string($f_prop_cpf)."' ";
					$filtros .= ' [CPF:'.$_POST["filtro_cpf"].'] ';
				}
				
				$cLOGIN->insert_log(1,1,'Visualização da Lista de propostas'.$filtros);
			$db->query="select 
								a.cod_usua,
								a.id_lstn,
								a.nome_usua,
								c.cpf_ppnt,
								date_format(d.data_ppst,'%d/%m/%Y') as data_ppst,
								d.situacao_ppst,
								d.indcancelamento_ppst,
								d.cod_ppst
								
							from 
								usuario a,
								proponente c,
								proposta d
								
							where
								a.cod_usua=c.cod_proponente
							and
								d.cod_ppst=c.cod_ppst
							and
								d.solavaldoc_matricula = '1'
							and
								d.solavaldoc_iptu= '1'
							$f_query_nome
							$f_query_cpf";
				
				$db->query();
				if($db->qrcount>0) {
					?>
					<div class="tListDiv listScroll" style="overflow:auto ">
						<table>
							<colgroup>
								<col width="80" />
								<col width="75"/>
								<col width="75"/>
								<col width="80"/>
								<col width="75"/>
								<col width="10" />
							</colgroup>
							<thead>
								<tr>
									<td align="center">C.I.</td>
									<td align="center">Data Solicitação</td>
									<td align="center">Prazo para Expirar:</td>
									<td align="center">Em avaliação há:</td>
									<td align="center">Data Entrega</td>
									<td></td>
								</tr>
							</thead>
							<tbody>
							<?
								for($i=0; $i<$db->qrcount; $i++){
									$situacao = $db->qrdata[$i]['situacao_ppst'];
									$cancelam = $db->qrdata[$i]['indcancelamento_ppst'];
									$status   = $_SESSION["prop_status"][$situacao];
									$estilo = '';
									$estilo = ' class="bold"';
									$lnk_acao = 'abrir';
									
									
									?>
										<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
											<td align="center"><b><?php echo $utils->formataMatricula($db->qrdata[$i]['id_lstn']);?></b></td>
											<td align="center"><? $query="Select * from avaliacao where cod_ppst='".$db->qrdata[$i]['cod_ppst']."'"; 
												   $result= mysql_query($query);
												   $reg = mysql_fetch_array($result, MYSQL_ASSOC);
												   echo $utils->formataDataBRA($reg['DTPEDIDO']);
												   $dtpedido=$utils->formataDataBRA($reg['DTPEDIDO']);
												   $dtentrega=$utils->formataDataBRA($reg['DTENTREGA']);?></td>
											<td align="center"> 7 dias úteis</td>
											<td <?=$estilo;?>  align="center" ><?php
											
										if($dtpedido!='' && $dtentrega==''){
											$mes1=substr($dtpedido,3,2);
											$dia1=substr($dtpedido,0,2);
											if($dia1=='01')$dia1='1';elseif($dia1=='02')$dia1='2';elseif($dia1=='03')$dia1='3';elseif($dia1=='04')$dia1='4';elseif($dia1=='05')$dia1='5';elseif($dia1=='06')$dia1='6';elseif($dia1=='07')$dia1='7';elseif($dia1=='08')$dia1='8';elseif($dia1=='09')$dia1='9';elseif($dia1=$dia1);
											$ano1=substr($dtpedido,6,4);
											//--------------------------------
											$mes2=date('m');
											$dia2=date('d');
											$ano2=date('Y');
											$expira=@diferenca_datas(mktime(0,0,0,$mes1,$dia1,$ano1),mktime(0,0,0,$mes2,$dia2,$ano2));

											if($expira<=4){
											echo $expira." dia(s) útil(eis)";
											}elseif($expira>=5 && $expira<=7){
											echo "<font color=\"#FF0000\">".$expira." dias úteis</font>";
											}elseif($expira>7){
											echo $expira." dias úteis<br><font color=\"#FF0000\">Expirou!</font>";
											}
										}?></td> 
											<td align="center"><?php echo $dtentrega; ?></td>
											<td align="center"><a href='asdmt_avaliacao.php?cod_usuario=<?=$db->qrdata[$i]['cod_usua'];?>'><? echo $lnk_acao; ?></a></td>
										</tr>
									<?
								}
							?>
							</tbody>
						</table>
					</div>
					<?
				} else {
					echo("Nenhuma proposta encontrada.");
				}
			?>
  		</div>
			<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div><br>
<p align="right"><a href="lista_assistente.php"><img src="images/buttons/bt_voltar.gif" alt="Voltar para Lista Inicial de Assistente"></a>
</p>

<?
include "lib/footer.inc.php";
?>