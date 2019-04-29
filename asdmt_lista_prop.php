<?php
$iREQ_AUT=1;
$aUSERS_PERM[]=8;
$pageTitle = "Propostas";
$select_proposta=$_GET['select'];
$sit=$_GET['sit'];
if($sit=='111'){
	
		$qr="and
				d.dtremessacontrato_ppst is not NULL
			 and
				d.situacao_ppst in (11)";
}
if($sit=='112'){
	
		$qr="and
				d.dtremessacontrato_ppst is NULL
			 and
				d.situacao_ppst in (11)";
}
if($sit=='12'){
	
		$qr="and
				d.dtremessacontrato_ppst is NULL
			 and
				d.situacao_ppst in (12)";
}

include "lib/header.inc.php";
?>
<script language="JavaScript" src="./js/diversos.js"></script>

<form name="filtro" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">

	<br><b>Filtros</b>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
      <table cellpadding=0 cellspacing=5 border=0>
        <tr>
          <td align="right">Nome:</td>
          <td align="left"><input type="text" name="filtro_nome" value="<?php echo $_POST['filtro_nome'];?>" size="50"></td>
        </tr>
        <tr>
          <td align="right">Matricula:</td>
          <td align="left"><input type="text" name="filtro_mat" value="<?php echo $_POST['filtro_mat'];?>" size="50"></td>
        </tr>
		
        <tr>
        	<td align="right">&nbsp;</td>
          <td align="left">
          	<input type="image" name="btFiltrar" id="btFiltrar" src="images/buttons/bt_filtrar.gif" value="Filtrar" class="im" />&nbsp;
          	<a href="asdmt_lista_prop.php"><img src="images/buttons/bt_limparfiltros.gif" alt="Limpar Filtros" class="im" /></a>
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
			<?php
				if(!empty($_POST["filtro_mat"])) {
					$f_prop_mat = mysql_real_escape_string(preg_replace('/\D/i','',$_POST["filtro_mat"]));
					$f_query_mat=" and a.id_lstn='".mysql_real_escape_string($f_prop_mat)."' ";
					$filtros .= ' [Matrícula:'.$_POST["filtro_mat"].'] ';
				}elseif($_GET['mat'])
				{
					$f_query_mat=" and a.id_lstn='".mysql_real_escape_string($_GET['mat'])."' ";
				}
				
				if(!empty($_POST["filtro_nome"])) {
					$f_prop_nome = mysql_real_escape_string(preg_replace('/\D/i','',$_POST["filtro_nome"]));
					$f_query_nome=" and a.nome_usua like '%".mysql_real_escape_string($_POST["filtro_nome"]). "%'";
				}elseif($_GET['nome'])
				{
					$f_query_nome=" and a.nome_usua like '%".mysql_real_escape_string($_GET["nome"]). "%'";
				}
				
				$cLOGIN->insert_log(1,1,'Visualização da Lista de Propostas'.$filtros);
			
				$db->query="select 
								a.cod_usua,
								a.nome_usua,
								b.id_lstn,
								c.cpf_ppnt,
								date_format(d.data_ppst,'%d/%m/%Y') as data_ppst,
								d.situacao_ppst,
								d.cod_ppst,
								d.dtremessacontrato_ppst
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
								c.cod_ppst=d.cod_ppst
							$qr
							$f_query_nome
							$f_query_matricula
							$f_query_cpf";
				//echo $db->query;
				$db->query();
		
				if($db->qrcount>0){
					?>
					<div class="tListDiv listScroll">
						<table>
							<thead>
								<tr>
									<td colspan="3">Proponente</td>
									<td align="center">Matricula</td>
								</tr>
							</thead>
							<tbody>
							<?php
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
									<?php
									if($db->qrdata[$i]['cod_ppst']!=$db->qrdata[$i-1]['cod_ppst']){
									?>
										<tr class="tL<?php echo $i%2 ? "1" : "2"; ?>">
											<td colspan="3">
									<?php
											if($select_proposta!=$db->qrdata[$i]['cod_ppst'])
											{ ?>
												<b><a href="asdmt_lista_prop.php?select=<?php echo $db->qrdata[$i]['cod_ppst']; ?>&mat=<?php echo $db->qrdata[$i]['id_lstn'];?>&nome=<?php echo $db->qrdata[$i]['nome_usua'];?>"><?php echo $db->qrdata[$i]['nome_usua']; ?></a></b></td>
											<?php
											}else{
											?>
											<b><a href="asdmt_lista_prop.php"><?php echo $db->qrdata[$i]['nome_usua']; ?></a></b></td>
											<?php
											}
											?>
											<td align="center"><?php echo $db->qrdata[$i]['id_lstn']; ?></td>
										</tr>
									<?php
									if($select_proposta==$db->qrdata[$i]['cod_ppst'])
									{
											$query="Select nome,id from imagem where cod_ppst='".$db->qrdata[$i]['cod_ppst']."' and categoria='1'";
											$result=mysql_query($query);
											$cont=mysql_fetch_array($result,MYSQL_ASSOC);
											$query="Select nome,id from imagem where cod_ppst='".$db->qrdata[$i]['cod_ppst']."' and categoria='2'";
											$result=mysql_query($query);
											$mat=mysql_fetch_array($result,MYSQL_ASSOC);
									?>
									
													
						  <a name="<?php echo $db->qrdata[$i]['cod_ppst'];?>"></a>								
													<tr>
														<td align="center"><b>Data Remessa</b></td>
														<td align="center"><b>Imagem Contrato</b></td>
														<td align="center"><b>Imagem Matrícula</b></td>
														<td align="center"><b>Nova Imagem</b></td>
													</tr>
													<tr>
														<td align="center"><?php echo $utils->formataDataBRA($db->qrdata[$i]['dtremessacontrato_ppst']);?></td>
														<td align="center"><a href="openpdf.php?id=<?php echo $cont['id'];?>"><?php echo strtoupper($cont['nome']);?></a></td>
														<td align="center"><a href="openpdf.php?id=<?php echo $mat['id'];?>"><?php echo strtoupper($mat['nome']);?></a></td>
														<td align="center"><a href="form_img.php?cod=<?php echo $select_proposta;?>">Incluir</a></td>
													</tr>
													
									<?php
									}
									}
								}
							?>
							</tbody>
						</table>
					</div>
				  <?php
				} else {
					echo("Nenhuma proposta encontrada.");
				}
			?>
  		</div>
			<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
		</div>
		<table width="732">
			<tr><td width="106">
					<a href="lista_assistente.php"><img src="images/buttons/bt_voltar.gif"></a>
					
			</td>
			<?php 
			if($sit=='12'){
			?>
			<td width="621" align="right">
					<a href="lista_remetidas.php"><img src="images/buttons/bt_prop_rem.png"></a>
			</td>
			<?php 
			}?>
			</tr>
</table>
<?php
include "lib/footer.inc.php";
?>