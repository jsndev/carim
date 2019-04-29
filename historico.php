<?
$pageTitle = "Histórico";
$iREQ_AUT=1;
$aUSERS_PERM[]=1;
$aUSERS_PERM[]=2;
$pageTitle = "Histórico";
include "lib/header.inc.php";

$forms = new forms();

if(!empty($_GET["cod_proposta"])){
	$where_ppst = " WHERE p.cod_ppst = '".mysql_real_escape_string($_GET['cod_proposta'])."'
                    AND u.cod_usua = p.proponente_ppst
                    AND p.proponente_ppst = n.cod_proponente 
                    AND p.cod_ppst = i.cod_ppst";
} else {
	$where_ppst = " WHERE u.cod_usua = '".mysql_real_escape_string($cLOGIN->iID)."'
                    AND u.cod_usua = p.proponente_ppst
                    AND p.proponente_ppst = n.cod_proponente 
                    AND p.cod_ppst = i.cod_ppst";
}

?>
<div class="quadroInterno">
	<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
	<div class="quadroInternoMeio">
<table cellpadding=0 cellspacing=0 border=0 width="100%" height="300"><tr>
  <td style="padding:15px;" valign="top" align="center">
    <fieldset style="width:550px;">
      <legend>Proposta</legend>
	  <?
  		$db->query="SELECT
                    u.nome_usua,
                    u.id_lstn,
                    u.cod_usua,
                    date_format(p.data_ppst,'%d/%m/%Y') as data_ppst,
                    p.vlfinsol_ppst,
                    p.przfinsol_ppst,
                    p.valordevsinalsol_ppst,
                    p.situacao_ppst,
                    n.cpf_ppnt,
                    date_format(n.dtnascimento_ppnt,'%d/%m/%Y') as dtnascimento_ppnt,
                    n.cod_estciv,
                    n.cod_logr,
                    n.endereco_ppnt,
                    n.nrendereco_ppnt,
                    n.cpendereco_ppnt,
                    n.cod_bairro,
                    n.cod_uf,
                    n.cod_municipio,
                    n.cep_ppnt,
                    i.tipo_imov,
                    i.tpconstrucao_imov,
                    i.tpcondominio_imov,
                    i.qtsala_imov,
                    i.qtquarto_imov,
                    i.qtbanh_imov,
                    i.qtgarag_imov,
                    i.qtpavim_imov,
                    i.qtdepemp_imov,
                    i.endereco_imov,
                    i.nrendereco_imov,
                    i.cpendereco_imov,
                    i.cep_imov,
                    i.cod_logr       i_cod_logr,
                    i.cod_bairro     i_cod_bairro,
                    i.cod_uf         i_cod_uf,
                    i.cod_municipio  i_cod_municipio
                  FROM proposta p, usuario u, proponente n, imovel i
                  $where_ppst";

  		$db->query();
      if($db->qrcount>0){
        $f_vlfinsol_ppst  = $db->qrdata[0]["vlfinsol_ppst"];
        $f_przfinsol_ppst = $db->qrdata[0]["przfinsol_ppst"];
        $f_sinalsol_ppst  = $db->qrdata[0]["valordevsinalsol_ppst"];

        $f_valor_compra = $f_vlfinsol_ppst + $f_sinalsol_ppst;
        $prop_taxa = 6.5;
        $taxa  = pow( (( $prop_taxa / 100 ) + 1), (1 / 12)) - 1;
        $f_prestacao      = $utils->fPMT($taxa,$f_przfinsol_ppst,$f_vlfinsol_ppst);
        
        $f_vlfinsol_ppst  = $utils->formataMoeda($f_vlfinsol_ppst);
        $f_valor_compra   = $utils->formataMoeda($f_valor_compra);
        $f_przfinsol_ppst = intval($f_przfinsol_ppst);
        $f_sinalsol_ppst  = $utils->formataMoeda($f_sinalsol_ppst);
        $f_prestacao      = $utils->formataMoeda($f_prestacao);
        
        $f_nome      = $db->qrdata[0]["nome_usua"];
        $f_cod_usua  = $db->qrdata[0]["cod_usua"];
        $f_matricula = $db->qrdata[0]["id_lstn"];
        $f_cpf       = $utils->formataCPF($db->qrdata[0]["cpf_ppnt"]);
        $f_dt_nasc   = $db->qrdata[0]["dtnascimento_ppnt"];
        $ecivil      = $db->qrdata[0]["cod_estciv"];
        $clogr       = $db->qrdata[0]["cod_logr"];
        $f_ender     = $db->qrdata[0]["endereco_ppnt"];
        $f_ender_nm  = $db->qrdata[0]["nrendereco_ppnt"];
        $f_ender_cpl = $db->qrdata[0]["cpendereco_ppnt"];
        $f_bairro    = $db->qrdata[0]["cod_bairro"];
        $f_uf        = $db->qrdata[0]["cod_uf"];
        $f_cidade    = $db->qrdata[0]["cod_municipio"];
        $f_cep       = $db->qrdata[0]["cep_ppnt"];
        
        $f_i_tipo    = $db->qrdata[0]["tipo_imov"];
        $f_i_tconst  = $db->qrdata[0]["tpconstrucao_imov"];
        $f_i_tcond   = $db->qrdata[0]["tpcondominio_imov"];
        $f_i_qsala   = $db->qrdata[0]["qtsala_imov"];
        $f_i_qquart  = $db->qrdata[0]["qtquarto_imov"];
        $f_i_qbnah   = $db->qrdata[0]["qtbanh_imov"];
        $f_i_qgarag  = $db->qrdata[0]["qtgarag_imov"];
        $f_i_qpavim  = $db->qrdata[0]["qtpavim_imov"];
        $f_i_qdpemp  = $db->qrdata[0]["qtdepemp_imov"];
        $f_i_ender   = $db->qrdata[0]["endereco_imov"];
        $f_i_ender_n = $db->qrdata[0]["nrendereco_imov"];
        $f_i_ender_c = $db->qrdata[0]["cpendereco_imov"];
        $f_i_cep     = $db->qrdata[0]["cep_imov"];
        $f_i_logr    = $db->qrdata[0]["i_cod_logr"];
        $f_i_bairro  = $db->qrdata[0]["i_cod_bairro"];
        $f_i_uf      = $db->qrdata[0]["i_cod_uf"];
        $f_i_cidade  = $db->qrdata[0]["i_cod_municipio"];

        $f_idade = '';
        if($f_dt_nasc!=''){ $f_idade = $utils->idade($f_dt_nasc).' anos'; }
        $ecivil      = $forms->getECivil($ecivil);
				$f_ecivil    = strtolower($ecivil[0]['desc_estciv']);
				$clogr       = $forms->getLogr($clogr);
				$f_lograd    = $clogr[0]['desc_logr'];
				$f_ender_cpl = (trim($f_ender_cpl)=='')?'':' - '.$f_ender_cpl;
				$f_bairro    = $forms->getBairro($f_bairro);
				$f_bairro    = $f_bairro[0]['nome_bairro'];
				$f_uf        = $forms->getUF($f_uf);
				$f_uf        = $f_uf[0]['nome_uf'];
				$f_cidade    = $forms->getMunicipios('',$f_cidade);
				$f_cidade    = $f_cidade[0]['nome_municipio'];
				$f_end_compl  = '';
				$f_end_compl .= $f_lograd.' '.$f_ender.', '.$f_ender_nm.$f_ender_cpl.'<br>';
				$f_end_compl .= $f_bairro.', '.$f_cidade.' - '.$f_uf.'<br>CEP: '.$f_cep;
				
				$f_i_logr    = $forms->getLogr($f_i_logr);
				$f_i_logr    = $f_i_logr[0]['desc_logr'];
				$f_i_ender_c = (trim($f_i_ender_c)=='')?'':' - '.$f_i_ender_c;
				$f_i_bairro  = $forms->getBairro($f_i_bairro);
				$f_i_bairro  = $f_i_bairro[0]['nome_bairro'];
				$f_i_uf      = $forms->getUF($f_i_uf);
				$f_i_uf      = $f_i_uf[0]['nome_uf'];
				$f_i_cidade  = $forms->getMunicipios('',$f_i_cidade);
				$f_i_cidade  = $f_i_cidade[0]['nome_municipio'];
				$f_i_end_compl  = '';
				$f_i_end_compl .= $f_i_logr.' '.$f_i_ender.', '.$f_i_ender_n.$f_i_ender_c.'<br>';
				$f_i_end_compl .= $f_i_bairro.', '.$f_i_cidade.' - '.$f_i_uf.'<br>CEP: '.$f_i_cep;
				
				$f_i_quantidades  = '';
				$f_i_quantidades .= ($f_i_qsala) ?$f_i_qsala .' Salas<br>':'';
				$f_i_quantidades .= ($f_i_qquart)?$f_i_qquart.' Quartos<br>':'';
				$f_i_quantidades .= ($f_i_qbnah) ?$f_i_qbnah .' Banheiros<br>':'';
				$f_i_quantidades .= ($f_i_qgarag)?$f_i_qgarag.' Garagens<br>':'';
				$f_i_quantidades .= ($f_i_qpavim)?$f_i_qpavim.' Pavimentos<br>':'';
				$f_i_quantidades .= ($f_i_qdpemp)?$f_i_qdpemp.' Dep Empreg<br>':'';
				?>
    		  <table cellpadding=0 cellspacing=5 border=0>
    
    			<tr>
    			  <td align="right" valign="top">Compra:</td><td align="left"><b>R$ <?=$f_valor_compra;?></b></td>
    			</tr>

    			<tr>
    			  <td align="right" valign="top">Entrada:</td><td align="left"><b>R$ <?=$f_sinalsol_ppst;?></b></td>
    			</tr>

    			<tr>
    			  <td align="right" valign="top">Financiamento:</td><td align="left"><b>R$ <?=$f_vlfinsol_ppst;?></b></td>
    			</tr>

    			<tr>
    			  <td align="right" valign="top">Prazo:</td><td align="left"><b><?=$f_przfinsol_ppst;?> meses</b></td>
    			</tr>

    			<tr>
    			  <td align="right" valign="top">Taxa de Juros:</td><td align="left"><b><?=$prop_taxa;?>%</b></td>
    			</tr>

    			<tr>
    			  <td align="right" valign="top">Prestação Inicial:</td><td align="left"><b>R$ <?=$f_prestacao;?></b></td>
    			</tr>

    			<tr><td align="center" colspan="2"><hr></td></tr>

    			<tr>
    			  <td align="right" valign="top">Nome:</td><td align="left"><b><?=$f_nome;?></b></td>
    			</tr>
    			
    			<tr>
    			  <td align="right" valign="top">Matricula:</td><td align="left"><b><?=$f_matricula;?></b></td>
    			</tr>
    			
    			<tr>
    			  <td align="right" valign="top">CPF:</td><td align="left"><b><?=$f_cpf;?></b></td>
    			</tr>
    			
    			<tr>
    			  <td align="right" valign="top">Idade:</td><td align="left"><b><?=$f_idade;?></b></td>
    			</tr>

    			<tr>
    			  <td align="right" valign="top">Est Civil:</td><td align="left"><b><?=$f_ecivil;?></b></td>
    			</tr>

    			<tr>
    			  <td align="right" valign="top">Endereço:</td><td align="left"><b><?=$f_end_compl;?></b></td>
    			</tr>

    			<tr><td align="center" colspan="2"><hr></td></tr>

    			<tr>
    			  <td align="right" valign="top">Tipo do Imóvel:</td><td align="left"><b><?=$aTIP_IMOVEL[$f_i_tipo];?></b></td>
    			</tr>

    			<tr>
    			  <td align="right" valign="top">Tipo Construção:</td><td align="left"><b><?=$aTIP_CONSTR[$f_i_tconst];?></b></td>
    			</tr>

    			<tr>
    			  <td align="right" valign="top">Tipo Condomínio:</td><td align="left"><b><?=$aTIP_CONDOM[$f_i_tcond];?></b></td>
    			</tr>

    			<tr>
    			  <td align="right" valign="top">Endereço:</td><td align="left"><b><?=$f_i_end_compl;?></b></td>
    			</tr>

    			<tr>
    			  <td align="right" valign="top"></td><td align="left"><b><?=$f_i_quantidades;?></b></td>
    			</tr>

    			</table>
    		<?
	    }else{
	      print "<center>Não existe uma proposta ativa para este usuário.</center>";
	    }
	  ?>
    </fieldset>

    <br><br>

    
    <div style="width:550px; text-align:left;"><b>Histórico</b></div>
    <div class="listaHistorico">
      <?
		$db->query="SELECT
                  h.cod_ppst,
                  date_format(h.dt_hist,'%d/%m/%Y a\s %h%:%i:%s ') as data,
                  h.obs_hist,
                  h.tipo_hist
                FROM historico h, proposta p
                WHERE p.proponente_ppst = '".mysql_real_escape_string($f_cod_usua)."' 
                  AND p.cod_ppst = h.cod_ppst
                ORDER BY h.seq_hist desc";
		$db->query();

		if($db->qrcount>0){
			?>
			<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#000000">
			<tr>
			<td>
			<table border="0" cellpadding="2" cellspacing="1" width="100%">
				<tr class="titulo_table">
					<td>Data</td>
					<td>Obs</td>
				</tr>
				<?
				for($i=0; $i<$db->qrcount; $i++){
					echo("<tr  bgcolor=\"#FFFFFF\">");
						echo("<td>".$db->qrdata[$i]['data']."</td>");
						echo("<td>".$db->qrdata[$i]['obs_hist']."</td>");
					echo("</tr>");
				}
				?>
			</table>
			</td>
			</tr>
			</table>

			<?
		} else {
			echo("Nenhum histórico encontrado.");
		}
      ?>
    </div>
  </td>
</tr></table>
	</div>
	<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
<?

if(!empty($_GET["cod_proposta"])){
	echo("<br><center><a href=\"consulta_proc.php\">Voltar para listagem de contratos</a></center>&nbsp;");
}
include "lib/footer.inc.php";
?>