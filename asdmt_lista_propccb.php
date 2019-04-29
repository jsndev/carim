<?
include "class/dbclasses.class.php";

$iREQ_AUT=1;
$aUSERS_PERM[] = TPUSER_ATENDENTE;


$pageTitle = "Participantes";
include "lib/header.inc.php";
?>
<script language="JavaScript" src="./js/diversos.js"></script>

<form name="filtro" method="post" action="<?=$_SERVER["PHP_SELF"];?>">

	<br><b></b>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
      <table cellpadding=0 cellspacing=5 border=0>
        <tr>
          <td align="right">Mostar Contratos disponíveis para:</td>
          <td align="left">	<select name="filtro_nome" id="filtro_nome">
					 <option value='0'>-- Selecione --</option>
					 <?php
					 	$db->query="Select nome_usua, cod_usua from usuario where level_usua='2'";
						$db->query();
						if($db->qrcount>0)
						{
							$i=0;
							while($i<$db->qrcount)
							{
								$query="Select * from proposta where resp_ppst='".$db->qrdata[$i]['cod_usua']."' and situacao_ppst in (7,8)";
								$result=mysql_query($query);
								$linha=mysql_num_rows($result);
								if($linha>0)
								{
									$aux[$i]=" (".$linha.")";
								}
							?>
								<option <?php if($_POST['filtro_nome']==$db->qrdata[$i]['cod_usua']) echo "selected='selected'";?> value="<?php echo $db->qrdata[$i]['cod_usua'];?>"><?php echo $db->qrdata[$i]['nome_usua'].$aux[$i];?></option>
								<?php
								$i++;
							}
						}
					 ?>
					 </select>&nbsp;&nbsp;&nbsp;
</td>
	<tr>
              <td>Nome Participante:</td>
              <td><input type="text" name="nome_usua" id="nome_usua" style="width: 270px;" value="<?php echo (!empty($_POST['nome_usua'])) ? $_POST['nome_usua'] : ''?>"></td>
          </tr>
        <tr>
        	<td align="right">&nbsp;</td>
          <td align="left">
          	<input type="image" name="btFiltrar" id="btFiltrar" src="images/buttons/bt_filtrar.gif" value="Filtrar" class="im" />&nbsp;
          	<a href="asdmt_lista_propccb.php"><img src="images/buttons/bt_limparfiltros.gif" alt="Limpar Filtros" class="im" /></a>
          </td>
        </tr>
      </table>
	  </div>
			<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
<?php
				$filtros = '';
				if(!empty($_POST["filtro_nome"])) {
					$f_query_nome="and d.resp_ppst ='".$_POST["filtro_nome"]."'";
					$cod_atend=$_POST["filtro_nome"];
					$filtros .= ' [NOME:'.$_POST["filtro_nome"].'] ';
				}
				if(!empty($_POST["filtro_cpf"])) {
					$f_prop_cpf = mysql_real_escape_string(preg_replace('/\D/i','',$_POST["filtro_cpf"]));
					$f_query_cpf="and c.cpf_ppnt='".mysql_real_escape_string($f_prop_cpf)."' ";
					$filtros .= ' [CPF:'.$_POST["filtro_cpf"].'] ';
				}

		if(!empty($_POST["nome_usua"])) {
                    $f_query_nome_usua="and a.nome_usua LIKE'".$_POST["nome_usua"]."%'";
                    $nome_usua=$_POST["nome_usua"];
                    $filtros .= ' [NOME PARTICIPANTE:'.$_POST["nome_usua"].'] ';
                }

if($f_query_nome!='' || $cLOGIN->iID){
if($f_query_nome=='')
{
	$cod_atend=$cLOGIN->iID;
	$f_query_nome="and d.resp_ppst ='".$cLOGIN->iID."'";
	$resp=$cLOGIN->iID;
}else{
	$f_query_nome=$f_query_nome;
	$resp=$_POST["filtro_nome"];
}
$db->query="Select nome_usua from usuario where cod_usua='".$cod_atend."'";
$db->query();
$nome_atendente=$db->qrdata[0]['nome_usua'];
?>

		<br><b>Contratos disponíveis para <i><?php echo $nome_atendente;?></i></b>
<div class="quadroInterno">
			<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
			<div class="quadroInternoMeio">
			<?
				
				$cLOGIN->insert_log(1,1,'Visualização da Lista de propostas'.$filtros);
			$db->query="select 
								a.cod_usua,
								a.id_lstn,
								a.nome_usua,
								c.cpf_ppnt,
								d.situacao_ppst,
								d.cod_ppst
							from 
								usuario a,
								proponente c,
								proposta d
							where
								a.cod_usua=c.cod_proponente
							and
								c.cod_ppst=d.cod_ppst
							and
								d.situacao_ppst in  (7,8)
							$f_query_nome
							$f_query_cpf
							$f_query_nome_usua";
				$db->query();
				if($db->qrcount>0) {
					?>
					<div class="tListDiv listScroll" style="overflow:auto ">
						<table>
							<colgroup>
								<col />
								<col />
								<col />
								<col width="30" />
							</colgroup>
							<thead>
								<tr>
									<td>Participante</td>
									<td>C.I.</td>
									<td>Situação</td>
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
											<td><? echo $db->qrdata[$i]['nome_usua']; ?></td>
											<td><?php echo $utils->formataMatricula($db->qrdata[$i]['id_lstn']);?></td>
											<td><b><?php echo $aSIT_PPST[$situacao];?></b></td>
											<td><a href='emissao_contrato.php?cod_proposta=<?=$db->qrdata[$i]['cod_ppst'];?>&resp=<?php echo $resp;?>'><? echo $lnk_acao; ?></a></td>
										</tr>
									<?
								}
							?>
							</tbody>
						</table>
					</div>
					<?
				} else {
					echo("Nenhum contrato encontrado.");
				}
			?>
  		</div>
			<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
<?php
}?>
<br>
<p align="right">
<a href="lista_propostas.php"><img src="images/buttons/bt_voltar.gif"></a>
</p>
<?php
include "lib/footer.inc.php";
?>