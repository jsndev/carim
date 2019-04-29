<?
$iREQ_AUT=1;
$aUSERS_PERM[]=2;
$pageTitle = "Propostas";
include "lib/header.inc.php";
?>
<script language="JavaScript" src="./js/diversos.js"></script>

<form name="filtro" method="post" action="<?=$_SERVER["PHP_SELF"];?>">
<div class="quadroInterno">
	<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
	<div class="quadroInternoMeio">
<table cellpadding=0 cellspacing=0 border=0 width="100%" height="300"><tr>
  <td style="padding:15px;" valign="top" align="center">
    <fieldset style="width:400px;">
      <legend>Filtros</legend>
      <table cellpadding=0 cellspacing=5 border=0 width="100%">

        <tr>
          <td align="right" width="20%">Nome:</td>
          <td align="left" width="80%"><input type="text" name="filtro_nome" value="<?=$_POST["filtro_nome"];?>" size="50"></td>
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
          <td align="center" colspan="2"><input type="submit" value="filtar"></td>
        </tr>
        
      </table>
    </fieldset>

    <br><br>
    <div style="width:400px; text-align:left;"><b>Propostas </b></div>

	<div class="listaHistorico">
      <?

		// fazendo a query das propostas

		// montando os filtros
		if(!empty($_POST["filtro_nome"])) {
			$f_query_nome="and a.nome_usua like '%".mysql_real_escape_string($_POST["filtro_nome"])."%' ";
		}
		if(!empty($_POST["filtro_matricula"])) {
			$f_query_matricula="and b.id_lstn='".mysql_real_escape_string($_POST["filtro_matricula"])."' ";
		}
		if(!empty($_POST["filtro_cpf"])) {
			$f_prop_cpf = mysql_real_escape_string(preg_replace('/\D/i','',$_POST["filtro_cpf"]));
			$f_query_cpf="and c.cpf_ppnt='".mysql_real_escape_string($f_prop_cpf)."' ";
		}
	

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
					$f_query_nome
					$f_query_matricula
					$f_query_cpf";
		$db->query();

		if($db->qrcount>0){
			?>
			<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#000000">
			<tr>
			<td>
			<table border="0" cellpadding="2" cellspacing="1" width="100%">
				<tr class="titulo_table">
					<td>Proponente</td>
					<td>Data</td>
					<td>Matricula</td>
					<td>CPF</td>
					<td>Situação</td>
					<td></td>
				</tr>
				<?
				for($i=0; $i<$db->qrcount; $i++){
					echo("<tr  bgcolor=\"#FFFFFF\" style=\"Cursor:Hand;\" >");
						echo("<td>".$db->qrdata[$i]['nome_usua']."</td>");
						echo("<td>".$db->qrdata[$i]['data_ppst']."</td>");
						echo("<td>".$db->qrdata[$i]['id_lstn']."</td>");
						echo("<td>".$utils->formataCPF($db->qrdata[$i]['cpf_ppnt'])."</td>");
						echo("<td>".$aSIT_PPST[$db->qrdata[$i]['situacao_ppst']]."</td>");
						echo("<td><a href='proposta_atendente.php?cod_proposta=".$db->qrdata[$i]['cod_ppst']."'>ver</a></td>");
					echo("</tr>\n");
				}
				?>
			</table>
			</td>
			</tr>
			</table>

			<?
		} else {
			echo("Nenhuma proposta encontrada.");
		}
      ?>
    </div>
  </td>
</tr></table>
	</div>
	<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
<?
include "lib/footer.inc.php";
?>