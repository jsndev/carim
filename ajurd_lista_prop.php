<?
$iREQ_AUT=1;
$aUSERS_PERM[]=7;
$pageTitle = "Propostas";
include "lib/header.inc.php";
?>
<script language="JavaScript" src="./js/diversos.js"></script>

<form name="filtro" method="post" action="<?=$_SERVER["PHP_SELF"];?>">

	<br><b>Filtros</b>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
      <table cellpadding=0 cellspacing=5 border=0>
        <tr>
          <td align="right">Nome:</td>
          <td align="left"><input type="text" name="filtro_nome" value="<?=$_POST["filtro_nome"];?>" size="50"></td>
        </tr>
        <tr>
          <td align="right">Matricula:</td>
          <td align="left"><input type="text" name="filtro_matricula" value="<?=$_POST["filtro_matricula"];?>" size="50"></td>
        </tr>
        <tr>
          <td align="right">CPF:</td>
          <td align="left"><input type="text" name="filtro_cpf" value="<?=$_POST["filtro_cpf"];?>" size="50" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraCPF(this,event);" maxlength="14"></td>
        </tr>
        <tr>
        	<td align="right">&nbsp;</td>
          <td align="left">
          	<input type="image" name="btFiltrar" id="btFiltrar" src="images/buttons/bt_filtrar.gif" value="Filtrar" class="im" />&nbsp;
          	<a href="ajurd_lista_prop.php"><img src="images/buttons/bt_limparfiltros.gif" alt="Limpar Filtros" class="im" /></a>
          </td>
        </tr>
      </table>
			</div>
			<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
		</div>

		<br><b>Propostas</b>
		<div class="quadroInterno">
			<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
			<div class="quadroInternoMeio">
			<?
				$filtros = '';
				if(!empty($_POST["filtro_nome"])) {
					$f_query_nome="and a.nome_usua like '%".mysql_real_escape_string($_POST["filtro_nome"])."%' ";
					$filtros .= ' [NOME:'.$_POST["filtro_nome"].'] ';
				}
				if(!empty($_POST["filtro_matricula"])) {
					$f_query_matricula="and b.id_lstn='".mysql_real_escape_string($_POST["filtro_matricula"])."' ";
					$filtros .= ' [MATRICULA:'.$_POST["filtro_matricula"].'] ';
				}
				if(!empty($_POST["filtro_cpf"])) {
					$f_prop_cpf = preg_replace('/\D/i','',$_POST["filtro_cpf"]);
					$f_query_cpf="and c.cpf_ppnt='".mysql_real_escape_string($f_prop_cpf)."' ";
					$filtros .= ' [CPF:'.$_POST["filtro_cpf"].'] ';
				}
				
				$cLOGIN->insert_log(1,1,'Visualização da Lista de Propostas'.$filtros);
			
				$db->query="select 
								a.cod_usua,
								a.nome_usua,
								b.id_lstn,
								c.cpf_ppnt,
								date_format(d.data_ppst,'%d/%m/%Y') as data_ppst,
								d.situacao_ppst,
								d.cod_ppst
							from 
								usuario a,
								listadenomes b,
								proponente c,
								proposta d
							where
								a.id_lstn=b.id_lstn
							and
								a.cod_usua=c.cod_proponente
							and
								c.cod_proponente=d.proponente_ppst
							and
								situacao_ppst in (6,7,11)
							$f_query_nome
							$f_query_matricula
							$f_query_cpf";
				$db->query();
		
				if($db->qrcount>0){
					?>
					<div class="tListDiv listScroll">
						<table>
							<colgroup>
								<col width="150" />
								<col />
								<col />
								<col />
								<col />
								<col width="50" />
							</colgroup>
							<thead>
								<tr>
									<td>Data</td>
									<td>Proponente</td>
									<td>Matricula</td>
									<td>CPF</td>
									<td>Situação</td>
									<td></td>
								</tr>
							</thead>
							<tbody>
							<?
								for($i=0; $i<$db->qrcount; $i++){
									$situacao = $db->qrdata[$i]['situacao_ppst'];
									$estilo = '';
									if($situacao < 3){
										$lnk_acao = 'nulo';
									}elseif($situacao>=3 && $situacao<=11){
										$estilo = ' class="bold"';
										$lnk_acao = 'abrir';
									}else{
										$lnk_acao = 'consulta';
									}
									?>
										<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
											<td><? echo $db->qrdata[$i]['data_ppst']; ?></td>
											<td><? echo $db->qrdata[$i]['nome_usua']; ?></td>
											<td><? echo $db->qrdata[$i]['id_lstn']; ?></td>
											<td><? echo $utils->formataCPF($db->qrdata[$i]['cpf_ppnt']); ?></td>
											<td <?=$estilo;?> ><? echo $_SESSION["prop_status"][$situacao]; ?></td>
											<td><a href='ajurd_proposta.php?cod_proposta=<?=$db->qrdata[$i]['cod_ppst'];?>'><? echo $lnk_acao; ?></a></td>
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
		</div>
<?
include "lib/footer.inc.php";
?>