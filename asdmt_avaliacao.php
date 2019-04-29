<?php
$iREQ_AUT=1;
$aUSERS_PERM[]=8;
$pageTitle = "Avaliação";
include "lib/header.inc.php";
$cod_usua=$_GET['cod_usuario'];
$acaoprop=$_POST['acaoprop'];

$db->query="Select nome_usua,id_lstn from usuario where cod_usua='".$cod_usua."'";
$db->query();
$prop_nome=$db->qrdata[0]['nome_usua'];
$id_lstn=$db->qrdata[0]['id_lstn'];

$db->query="Select * from proponente where cod_proponente='".$cod_usua."'";
$db->query();
$cod_ppst=$db->qrdata[0]['COD_PPST'];

$db->query="Select * from proposta where cod_ppst='".$cod_ppst."'";
$db->query();

	$formapagto=$db->qrdata[0]['FLGFORMAPAGTO_PPST'];
	$dtpagto=$utils->formataDataBRA($db->qrdata[0]['DTPAGTOBOLETO_PPST']);
	$vlpagto=$db->qrdata[0]['VALORBOLETOAVAL_PPST'];

$bol='';
if($db->qrdata[0]['FLGBOLETOAVALPAGO_PPST']!=''){$bol=1;}



$db->query="Select endereco_imov, nrendereco_imov, cpendereco_imov, cod_municipio, cod_uf, cod_logr from imovel where cod_ppst='".$cod_ppst."'";
$db->query();
$prop_ender=$db->qrdata[0]['endereco_imov'];
$prop_num=$db->qrdata[0]['nrendereco_imov'];
$prop_compl=", ".$db->qrdata[0]['cpendereco_imov'];
$cod_mun=$db->qrdata[0]['cod_municipio'];
$cod_log=$db->qrdata[0]['cod_logr'];
$prop_uf=$db->qrdata[0]['cod_uf'];

$db->query="Select nome_municipio from municipio where cod_municipio='".$cod_mun."'";
$db->query();
$prop_cidade=$db->qrdata[0]['nome_municipio'];

$db->query="Select desc_logr from logradouro where cod_logr='".$cod_log."'";
$db->query();
$prop_lograd=$db->qrdata[0]['desc_logr'];

$aval='';
$db->query="Select * from avaliacao where cod_ppst='".$cod_ppst."'";
$db->query();
if($db->qrcount>0)
{
	$dtsolaval=$db->qrdata[0]['DTPEDIDO'];
	$avaliador=$db->qrdata[0]['AVALIADOR'];
	$dtretaval=$db->qrdata[0]['DTENTREGA'];
	$contato  =$db->qrdata[0]['CONTATO'];
	$obs	  =$db->qrdata[0]['OBSERVACAO'];
	$aval=1;
}
$ins_aval='';
$db->query="Select * from infoaval where cod_ppst='".$cod_ppst."'";
$db->query();
if($db->qrcount>0)
{
	$dthabita_imov=	$db->qrdata[0]['DTHABITA'];
	$t_imov_g=		$db->qrdata[0]['TIPOIMOV'];
	$area_imov=		$db->qrdata[0]['AREAEDIF'];
	$areater_imov=	$db->qrdata[0]['AREATER'];
	$frente_imov=	$db->qrdata[0]['FRENTETER'];
	$fundo_imov=	$db->qrdata[0]['FUNDOTER'];
	$lddir_imov=	$db->qrdata[0]['LDDIREITOTER'];
	$ldesq_imov=	$db->qrdata[0]['LDESQUERDOTER'];
	$fri_imov=		$db->qrdata[0]['FRACIDEAL'];
	$vg_usocomum=	$db->qrdata[0]['AREADIVISAO'];
	$doc=			$db->qrdata[0]['DOCUMENTACAO'];
	$info=			$db->qrdata[0]['INFOCOMPLEMENTARES'];
	$areautil_imov=	$db->qrdata[0]['AREAUTILEDIF'];
	$areatotal_imov=$db->qrdata[0]['AREAPRIVATIVAEDIF'];
	$ins_aval=1;
}

if($_POST)
{
	if($_POST['formapagto']){			$formapagto		=$_POST['formapagto'];}
	if($_POST['dtpagto']){				$dtpagto		=$_POST['dtpagto'];}
	if($_POST['vlpagto']){				$vlpagto		=$_POST['vlpagto'];}
	if($_POST['avaliador']){			$avaliador		=$_POST['avaliador'];}
	if($_POST['dtsolavao']){			$dtsolaval		=$_POST['dtsolaval'];}
	if($_POST['dtretaval']){			$dtretaval		=$_POST['dtretaval'];}	
	if($_POST['contato']){				$contato		=$_POST['contato'];}
	if($_POST['obs']){					$obs			=$_POST['obs'];}
	if($_POST['dthabita_imov']){		$dthabita_imov	=$_POST['dthabita_imov'];}
	if($_POST['t_imov_g']){				$t_imov_g		=$_POST['t_imov_g'];}
	if($_POST['area_imov']){			$area_imov		=$_POST['area_imov'];}
	if($_POST['areater_imov']){			$areater_imov	=$_POST['areater_imov'];}
	if($_POST['frente_imov']){			$frente_imov	=$_POST['frente_imov'];}
	if($_POST['fundo_imov']){			$fundo_imov		=$_POST['fundo_imov'];}
	if($_POST['lddir_imov']){			$lddir_imov		=$_POST['lddir_imov'];}
	if($_POST['ldesq_imov']){			$ldesq_imov		=$_POST['ldesq_imov'];}
	if($_POST['fri_imov']){				$fri_imov		=$_POST['fri_imov'];}
	if($_POST['vg_usocomum']){			$vg_usocomum	=$_POST['vg_usocomum'];}
	if($_POST['doc']){					$doc			=$_POST['doc'];}
	if($_POST['info']){					$info			=$_POST['info'];}
	if($_POST['areautil_imov']){		$areautil_imov	=$_POST['areautil_imov'];}
	if($_POST['areatotal_imov']){		$areatotal_imov	=$_POST['areatotal_imov'];}
	
		

}
if($acaoprop=='salvar')
{
	if($aval==''){
	$db->query="Insert Into avaliacao (COD_PPST,CONTATO,OBSERVACAO,AVALIADOR ) 
							values (
							'".$cod_ppst."',
							'".$contato."',
							'".$obs."',
							'".$avaliador."'
							)";
	//echo $db->query;
	//echo "A";
	$db->query();
	}else
	{
	$db->query="Update avaliacao Set 
							AVALIADOR='".$avaliador."',
							OBSERVACAO='".$obs."',
							CONTATO='".$contato."'
							Where cod_ppst='".$cod_ppst."'
							";
	//echo $db->query;
	$db->query();
	}

	if($ins_aval=='')
	{
		$db->query="Insert into infoaval (
							COD_PPST,
							DTHABITA,
							TIPOIMOV,
							AREAEDIF,
							AREAUTILEDIF,
							AREAPRIVATIVAEDIF,
							AREATER,
							FRENTETER,
							FUNDOTER,
							LDDIREITOTER,
							LDESQUERDOTER,
							FRACIDEAL,
							AREADIVISAO,
							DOCUMENTACAO,
							INFOCOMPLEMENTARES)
					values(
							'".$cod_ppst."',
							'".$dthabita_imov."',
							'".$t_imov_g."',
							'".$area_imov."',
							'".$areautil_imov."',
							'".$areatotal_imov."',
							'".$areater_imov."',
							'".$frente_imov."',
							'".$fundo_imov."',
							'".$lddir_imov."',
							'".$ldesq_imov."',
							'".$fri_imov."',
							'".$vg_usocomum."',
							'".$doc."',
							'".$info."')";
		$db->query();
	}else{
		$db->query="Update infoaval set
							DTHABITA='".$dthabita_imov."',
							TIPOIMOV='".$t_imov_g."',
							AREAEDIF='".$area_imov."',
							AREAUTILEDIF='".$areautil_imov."',
							AREAPRIVATIVAEDIF='".$areatotal_imov."',
							AREATER='".$areater_imov."',
							FRENTETER='".$frente_imov."',
							FUNDOTER='".$fundo_imov."',
							LDDIREITOTER='".$lddir_imov."',
							LDESQUERDOTER='".$ldesq_imov."',
							FRACIDEAL='".$fri_imov."',
							AREADIVISAO='".$vg_usocomum."',
							DOCUMENTACAO='".$doc."',
							INFOCOMPLEMENTARES='".$info."'
					where
							COD_PPST='".$cod_ppst."'";
	
	}
	
	
}
if($acaoprop=='concluir')
{
	if($dtretaval!='')
	{
	$db->query="Update avaliacao Set 
							DTENTREGA= now()
							Where cod_ppst='".$cod_ppst."'
							";
	//echo $db->query;
	$db->query();
	}
	$db->query="update avaliacao set
							DTENTREGA='".$utils->formataData($dtretaval)."' where cod_ppst='".$cod_ppst."'";
	$db->query();
	$db->query="update fase_proposta set data_termino=now() where cod_ppst='".$cod_ppst."' and fase='2'";
	$db->query();
	$db->query="Insert into fase_proposta (COD_PPST,FASE,DATA_INICIO) values ('".$cod_ppst."','3',now())";
	$db->query();
	$db->query="update proposta set situacao_ppst=3 where cod_ppst='".$cod_ppst."'";
	$db->query();
			$qCMP = $qVAL = '';
			$qCMP .= " COD_USUA, ";   $qVAL .= " '".mysql_real_escape_string($cod_usua)."', ";
			$qCMP .= " DESC_HIST, ";  $qVAL .= " 'Processo de Avaliação encerrado, proposta enviada pra Análise Júridicia', ";
			$qCMP .= " USUA_HIST, ";   $qVAL .= " '".$cLOGIN->cUSUARIO."', ";
			$qCMP .= " FASE_HIST, ";  $qVAL .="'2',";
			$qCMP .= " DATA_HIST ";   $qVAL .= " now() ";	
			$db->query="INSERT INTO historico ($qCMP) VALUES ($qVAL)";
			//echo $db->query; echo "<br>";
			$db->query();
}

?>
<script language="JavaScript" src="./js/diversos.js"></script>
<script language="JavaScript" src="./js/proposta.js"></script>
<script language="javascript" type="text/javascript" src="js/ajaxapi.js"></script>
<script language="javascript">

function salvarProp(_acao)
{
		document.getElementById('acaoprop').value = _acao;
		return true;
}
</script>

<form action="" method="post" name="proposta" id="proposta">
<input type="hidden" name="acaoprop" id="acaoprop" value="">
<p align="right"><a href="form_img.php?cod_proposta=<?php echo  ($cod_ppst*$cod_ppst)*2;?>&cod=<?php echo $cod_ppst;?>&login=<?php echo $cLOGIN->cUSUARIO;?>"><img alt="Incluir Imagens" src="images/buttons/incluirimagem.png"></a><a href="verimagem.php?cod_proposta=<?php echo  ($cod_ppst*$cod_ppst)*2;?>&cod=<?php echo $cod_ppst;?>&login=<?php echo $cLOGIN->cUSUARIO;?>"><img src="images/buttons/verimagem.png" width="160" height="29"></a>			
	
</p>

<br>
  <br><b>Proposta</b>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
      <table width="702" border=0 cellpadding=0 cellspacing=5>
		<tbody>
		 <tr>
		 	<td width="73" height="19" align="right">C.I.: </td>
			<td width="614"><b><?php echo $utils->formataMatricula($id_lstn);?></b></td>
		</tr>
		 <tr>
		 	<td width="73" height="19" align="right">Nome:</td>
			<td width="614"><b><?php echo $prop_nome;?></b></td>
		</tr>
		 <tr>
		 	<td width="73" height="19" align="right">Cidade:</td>
			<td width="614"><b><?php echo $prop_cidade;?></b></td>
		</tr>
		 <tr>
		 	<td width="73" height="19" align="right">UF:</td>
			<td width="614"><b><?php echo $prop_uf;?></b></td>
		</tr>
		
		 <tr>
		 	<td width="73" height="19" align="right">Endereço:</td>
			<td width="614"><b><?php echo $prop_lograd." ".$prop_ender.", ".$prop_num;?></b></td>
			<input type="hidden" name="acaoboleto" id="acaoboleto" value="">
		</tr>
		
	</tbody>
	 </table>
	</div>
<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
<br>
<br>
	<b>Pagamento Avaliação</b>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt="" /></div>
		<div class="quadroInternoMeio">
			<?php
					?>
		    		<table cellpadding=0 cellspacing=5 border=0 width="100%">
                      <colgroup>
                      <col width="150" />
                      <col width="*" />
                      </colgroup>
                      <tr>
                      <tr>
                        <td align="right" valign="top">Forma de Pagamento:</td>
                        <td align="left" ><b><?php if($formapagto=='B'){echo "Boleto";}
												if($formapagto=='T'){echo "Transferência";}?></b></td>
                      </tr>
                      <tr>
                        <td align="right" valign="top">Data de Pagamento:</td>
                        <td align="left"><b><?php echo $dtpagto;?></b></td>
                      </tr>
                      <tr>
                        <td align="right" valign="top">Valor (R$):</td>
                        <td align="left"><b><?php echo $utils->formataMoeda($vlpagto);?></b>
                        </td>
                      </tr>
                    </table>
	  </div>
		<div><img src="images/layout/subquadro_b.gif" alt="" /></div>
	</div>


  <br><b>Avaliador</b>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
      <table width="702" border=0 cellpadding=0 cellspacing=5>
		<tbody>
		<?php
		?>
		 <tr>
		 	<td width="63" height="19" align="right">Nome:</td>
			<td width="244" align=""><select name="avaliador" id="avaliador" onChange="document.proposta.submit();">
			<option  value="0" >--Selecione--</option><?php
			$db->query="Select nome_aval, cod_aval from avaliador";
			$db->query();
			$i=0;
			while($i<$db->qrcount)
			{?>
				// if($avaliador==$db->qrdata[$i]['cod_aval']){$selected="selected"; $nome_aval=$db->qrdata[$i]['nome_aval'];};
				<option <?php if ($avaliador==$db->qrdata[$i]['cod_aval']){echo 'selected="selected"';} ?> value="<?php echo  $db->qrdata[$i]['cod_aval'];?>"><?php echo $db->qrdata[$i]['nome_aval'];?></option><?php 
				$i++;
			}
			?></select></td>
		</tr>
		<?php
			$db->query="Select * from avaliador where cod_aval='".$avaliador."'";
			$db->query();
			{?>
				<tr>
					<td align="right">Empresa:</td>
					<td><b><?php echo $db->qrdata[0]['NOME_AVAL'];?></b></td>		
					<td align="right">CNPJ:</td>
					<td width="315"><b><?php echo $db->qrdata[0]['CNPJ_AVAL'];?></b></td>					
				</tr>
				<tr>
					<td align="right">Endereço:</td>
					<td><b><?php echo $db->qrdata[0]['ENDERECO_AVAL'];?></b></td>		
					<td align="right" >Telefone:</td>
					<td width="315"><b><?php echo $db->qrdata[0]['TEL_AVAL'];?></b></td>					
				</tr>
				<tr>
					<td align="right">Banco:</td>
					<td colspan="3"><b><?php echo $db->qrdata[0]['BANCO_AVAL'];?></b></td>		
				</tr>
				<tr>
					<td width="63" align="right">Agência:</td>
					<td width="244"><b><?php echo $db->qrdata[0]['AGENCIA_AVAL'];?></b></td>			
					<td width="55" align="right">Conta:</td>
					<td width="315"><b><?php echo $db->qrdata[0]['CONTA_AVAL'];?></b></td>					
				</tr>
				<tr>
					<td align="right">Email:</td>
					<td colspan="3"><b><?php echo $db->qrdata[0]['EMAIL_AVAL'];?></b></td>		
				</tr>
				

				<?php
		}
		?>
						<input type="hidden" name="aval" id="aval" value="<?php echo $avaliador;?>" />
 
	</tbody>
	 </table>
	</div>
<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
  <br>
  <b>Informações sobre o Imóvel: </b>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
      <table cellpadding=0 cellspacing=5 border=0> 
		<tr>
          <td align="right" valign="top">Data do habita-se:</td>
          <td align="left"  valign="top">

		 <input type="text" style="width:80px;" name="dthabita_imov" id="dthabita_imov" value="<?php echo $dthabita_imov;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" onblur="anc('imovel');" maxlength="10"></td>
		</tr>
		<tr>
	    			  <td align="right" valign="top">Tipo do Imóvel:</td>
	    			  <td align="left">
		            <select name="t_imov_g" id="t_imov_g" onchange="">
					        <option value="0">-Selecione-</option>
					        <option <?php if ($t_imov_g=='C') {echo "selected";} ?> value="C">Casa</option>
							<option <?php if ($t_imov_g=='A') {echo "selected";} ?> value="A">Apartamento</option>
							<option <?php if ($t_imov_g=='S') {echo "selected";} ?> value="S">Sobrado</option>
							<option <?php if ($t_imov_g=='L') {echo "selected";} ?> value="L">Loja</option>
							<option <?php if ($t_imov_g=='B') {echo "selected";} ?> value="B">Barracão</option>
							<option <?php if ($t_imov_g=='PC') {echo "selected";} ?> value="PC">Prédio Comercial</option>
							<option <?php if ($t_imov_g=='T') {echo "selected";} ?> value="T">Terreno</option>
					</select>		        	</td> 
			</tr>		
			<tr>
				<td colspan="2"><hr /></td>
			</tr>

			<tr>
				<td><b>EDIFICAÇÃO:</b></td>
				<td></td>
			</tr>
           <tr>
	    			  <td align="right" valign="top">Área(m²):</span></td>
	    			  <td align="left"><input style="width: 80px;" name="area_imov" id="area_imov" value="<?php echo $area_imov;?>" onkeydown="" onkeyup="" maxlength="9" type="text" /></td>
           </tr>

	    			<tr id="tr_area_util" >
	    			  <td align="right" valign="top">Área Privativa(m²):</td>

	    			  <td align="left"><input style="width: 80px;" name="areautil_imov" id="areautil_imov" value="<?php echo $areautil_imov;?>" onkeydown="" onkeyup="" maxlength="9" type="text"></td>
	    			</tr>
	    			<tr id="tr_area_total" >
	    			  <td align="right" valign="top">Área do Total (m²):</td>
	    			  <td align="left"><input style="width: 80px;" name="areatotal_imov" id="areatotal_imov" value="<?php echo $areatotal_imov?>" onkeydown="" onkeyup="" maxlength="9" type="text"></td>
	    			</tr>
			<tr>
			<td colspan="2"><hr /></td>
		</tr>
			<tr>
				<td><b>TERRENO:</b></td>
				<td></td>
			</tr>
           <tr>
	    			  <td align="right" valign="top">Área(m²):</span></td>
	    			  <td align="left"><input style="width: 80px;" name="areater_imov" id="areater_imov" value="<?php echo $areater_imov;?>" onkeydown="" onkeyup="" maxlength="9" type="text" /></td>
           </tr>
		              <tr>
	    			  <td align="right" valign="top">Frente (m²):</span></td>
	    			  <td align="left"><input style="width: 80px;" name="frente_imov" id="frente_imov" value="<?php echo $frente_imov;?>" onkeydown="" onkeyup="" maxlength="9" type="text" /></td>
           </tr>
           <tr>
	    			  <td align="right" valign="top">Fundo (m²):</span></td>
	    			  <td align="left"><input style="width: 80px;" name="fundo_imov" id="fundo_imov" value="<?php echo $fundo_imov;?>" onkeydown="" onkeyup="" maxlength="9" type="text" /></td>
           </tr>
           <tr>
	    			  <td align="right" valign="top">Lado Direito (m²):</span></td>
	    			  <td align="left"><input style="width: 80px;" name="lddir_imov" id="lddir_imov" value="<?php echo $lddir_imov;?>" onkeydown="" onkeyup="" maxlength="9" type="text" /></td>
           </tr>
           <tr>
	    			  <td align="right" valign="top">Lado Esquerdo (m²):</span></td>
	    			  <td align="left"><input style="width: 80px;" name="ldesq_imov" id="ldesq_imov" value="<?php echo $ldesq_imov;?>" onkeydown="" onkeyup="" maxlength="9" type="text" /></td>
           </tr>
           <tr>
	    			  <td align="right" valign="top">Fração Ideal (%):</span></td>
	    			  <td align="left"><input style="width: 80px;" name="fri_imov" id="fri_imov" value="<?php echo $fri_imov;?>" onkeydown="" onkeyup="" maxlength="9" type="text" /></td>
           </tr>

		<tr>
			<td colspan="2"><hr /></td>
		</tr>
			<tr>
				<td><b>VAGAS DE ESTACIONAMENTO:</b></td>
				<td></td>
			</tr>
	    			
	    			<tr >
	    			  <td align="right" valign="top"></td>

	    			  <td align="left"></tr>
	    			<tr id="tr_area_util" >
      
					  <td align="right" valign="top">Área de uso comum de divisão não proporcional:</td>
					  <td align="left"  valign="top">
						<input type="text" style="width:80px;" name="vg_usocomum" id="vg_usocomum" value="<?php echo $vg_usocomum;?>">  m²
			<tr>
				<td colspan="2"><hr /></td>
			</tr>
									
        <tr>
          <td align="right" valign="top">Documentação:</td>
          <td align="left"  valign="top"><i>(Descrever as fontes de informação das áreas de terreno, construção, etc., informadas)</i><br /><textarea name="doc" id="doc" cols="90" rows="5"><?php echo $doc;?></textarea></td>
        </tr>
        <tr>
          <td align="right" valign="top">Informações Complementares:</td>
          <td align="left"  valign="top"><i>(Se houver)</i><br /><textarea name="info" id="info" cols="90" rows="5"><?php echo $info;?></textarea></td>
        </tr>
		<tr>
			<td align="right" valign="top"><font color="#CC0000"><u><b></b></u></font></td>
			<td>
	</td>
	</tr>
</table><BR><BR><BR>
			<?php
					?>
		    		<table cellpadding=0 cellspacing=5 border=0 width="100%">
                      <tr>
					  	<td width="87%" align="left"><a href="avaliacao/infoaval.php?cod_usuario=<?php echo $cod_usua;?>" target="_blank"><b>Imprimir Informações Básicas para Solicitação de Avaliação</b></td>
                      </tr>
                    </table>
		    		<?php
			?>
<input type="hidden" name="hidden_dtpagtoboleto_ppst" id="hidden_dtpagtoboleto_ppst" value="<?php echo $utils->formataDataBRA($aProposta["dtpagtoboleto_ppst"]);?>" />
	  </div>
		<div><img src="images/layout/subquadro_b.gif" alt="" /></div>
	</div>
	<br>
	<b>Contato</b>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt="" /></div>
		<div class="quadroInternoMeio">
			<?php
					?>
		    		<table cellpadding=0 cellspacing=5 border=0 width="100%">
                      <colgroup>
                      <col width="150" />
                      <col width="*" />
                      </colgroup>
                      <tr>
                        <td width="13%" align="right" valign="top">Nome e Telefone:</td>
                        <td width="87%" align="left"><input type="text" style="width:250px;" name="contato" id="contato" value="<?php echo $contato;?>" />  
                        </td>
                      </tr>
					  <tr>
                        <td width="13%" align="right" valign="top"></td>
						<td><i>(Ex: Nome, (xx)xxxx-xxxx)</i></td>
                      </tr>

                    </table>
		    		<?php
			?>
<input type="hidden" name="hidden_dtpagtoboleto_ppst" id="hidden_dtpagtoboleto_ppst" value="<?php echo $utils->formataDataBRA($aProposta["dtpagtoboleto_ppst"]);?>" />
	  </div>
		<div><img src="images/layout/subquadro_b.gif" alt="" /></div>
	</div>
	<br>
	<b>Observações</b>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt="" /></div>
		<div class="quadroInternoMeio">
			<?php
					?>
		    		<table cellpadding=0 cellspacing=5 border=0 width="100%">
                      <colgroup>
                      <col width="150" />
                      <col width="*" />
                      </colgroup>
                      <tr>
                        <td width="87%" align="left"><textarea name="obs" id="obs" cols="130" rows="5"><?php echo $obs;?> </textarea>  
                        </td>
                      </tr>
					  <tr>
                        <td width="13%" align="left" valign="top"><i>(Estas observações serão enviadas junto ao e-mail de solicitação de avaliação) </i></td>
                      </tr>

                    </table>
		    		<?php
			?>
<input type="hidden" name="hidden_dtpagtoboleto_ppst" id="hidden_dtpagtoboleto_ppst" value="<?php echo $utils->formataDataBRA($aProposta["dtpagtoboleto_ppst"]);?>" />
	  </div>
		<div><img src="images/layout/subquadro_b.gif" alt="" /></div>
	</div>

<BR>
<?php
if($dtpagto!=''){

?>
  <br><b>Solicitação de Avaliação</b>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
      <table width="702" border=0 cellpadding=0 cellspacing=5>
		<tbody>
		 <tr>
		 	<td width="147" height="19" align="right">Solicitar Laudo de Avaliação:</td>
			<td width="540"><a href="asdmt_emailsol.php?cod_usuario=<?php echo $cod_usua;?>&aval=<?php echo $avaliador;?>" target="_blank"><img src="images/buttons/bt_enviar_mail.gif" alt="Enviar E-mail para Avaliador"/></a></td></tr>
		</tr>
		</tbody>
	 </table>
	</div>
<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
<br>
<?php
}
?>
<p align="center"><a href="asdmt_lista_propav.php"><img src="images/buttons/bt_voltar.gif" alt="Voltar para Lista Inicial de Assistente"></a>
    <input type="image" name="btSalvar"   id="btSalvar"   src="images/buttons/bt_salvar.gif"   value="Salvar"   class="im" onClick="return salvarProp('salvar');" />
    <input type="image" name="btConcluir" id="btConcluir" src="images/buttons/bt_concluir.gif" value="Concluir" class="im" onClick="return salvarProp('concluir');" />
</p>

<?php
    ### HISTORICO ##################################################################### 
$db->query="Select * from historico where desc_hist='Avaliação Solicitada' and cod_usua='".mysql_real_escape_string($cod_usuario)."'";
//echo $db->query;
$db->query();
if($db->qrcount>0)
{
	$hist=1;
}
if ($acaoprop=='salvar') 
{
			if($hist!=1){
				$qCMP = '';
				$qCMP .= " COD_USUA, ";   $qVAL .= " '".mysql_real_escape_string($cod_usuario)."', ";
				$qCMP .= " DESC_HIST, ";  $qVAL .= " 'Avaliação Solicitada', ";
				$qCMP .= " DATA_HIST, ";   $qVAL .= " now(), ";
				$qCMP .= " FASE_HIST, ";   $qVAL .= " 3, ";
				$qCMP .= " USUA_HIST ";   $qVAL .= " '".$cLOGIN->cUSUARIO."' ";
				$db->query="INSERT INTO historico ($qCMP) VALUES ($qVAL)";
				//echo $db->query; echo "<br>";
				$db->query();
			}
}
if ($acaoproposta=='fase3B') 
{
		//echo "entrou2";
		
			//echo "1";
			$qCMP = '';
			$qCMP .= " COD_USUA = '".mysql_real_escape_string($cod_usuario)."', ";
			$qCMP .= " DESC_HIST= 'Fase Emissão CCB concluída', ";
			$qCMP .= " DATA_HIST=  now(), ";
			$qCMP .= " FASE_HIST=  '3', ";
			$qCMP .= " USUA_HIST = '".$cLOGIN->cUSUARIO."' ";
			$db->query="UPDATE historico SET ($qCMP) WHERE cod_usua='".mysql_real_escape_string($cod_usuario)."'";
			//echo $db->query; echo "<br>";
			$db->query();
}
if ($acaoprop=='fase4A') 
{
		if($cod_histf4=='')
		{
			$qCMP = '';
			$qCMP .= " COD_USUA, ";   $qVAL .= " '".mysql_real_escape_string($cod_usuario)."', ";
			$qCMP .= " DESC_HIST, ";  $qVAL .= " 'Contratro Assinado pelo PAN', ";
			$qCMP .= " DATA_HIST, ";   $qVAL .= " now(), ";
			$qCMP .= " FASE_HIST, ";   $qVAL .= " 4, ";
			$qCMP .= " USUA_HIST ";   $qVAL .= " '".$cLOGIN->cUSUARIO."' ";
			$db->query="INSERT INTO historico ($qCMP) VALUES ($qVAL)";
			//echo $db->query; echo "<br>";
			$db->query();
		}
}
if ($acaoproposta=='fase4B') 
{
		//echo "entrou2";
			//echo "1";
			$qCMP = '';
			$qCMP .= " COD_USUA = '".mysql_real_escape_string($cod_usuario)."', ";
			$qCMP .= " DESC_HIST= 'Fase Assinatura PAN concluída', ";
			$qCMP .= " DATA_HIST=  now(), ";
			$qCMP .= " FASE_HIST=  '4', ";
			$qCMP .= " USUA_HIST = '".$cLOGIN->cUSUARIO."' ";
			$db->query="UPDATE historico SET ($qCMP) WHERE cod_usua='".mysql_real_escape_string($cod_usuario)."'";
			//echo $db->query; echo "<br>";
			$db->query();
}
if ($acaoprop=='fase5A') 
{
		if($cod_histf5=='')
		{
			$qCMP = '';
			$qCMP .= " COD_USUA, ";   $qVAL .= " '".mysql_real_escape_string($cod_usuario)."', ";
			$qCMP .= " DESC_HIST, ";  $qVAL .= " 'Contrato Assinado pelo Cliente', ";
			$qCMP .= " DATA_HIST, ";   $qVAL .= " now(), ";
			$qCMP .= " FASE_HIST, ";   $qVAL .= " 5, ";
			$qCMP .= " USUA_HIST ";   $qVAL .= " '".$cLOGIN->cUSUARIO."' ";
			$db->query="INSERT INTO historico ($qCMP) VALUES ($qVAL)";
			//echo $db->query; echo "<br>";
			$db->query();
		}
}
if ($acaoproposta=='fase5B') 
{
		//echo "entrou2";
			//echo "1";
			$qCMP = '';
			$qCMP .= " COD_USUA = '".mysql_real_escape_string($cod_usuario)."', ";
			$qCMP .= " DESC_HIST= 'Fase Assinatura Cliente concluída', ";
			$qCMP .= " DATA_HIST=  now(), ";
			$qCMP .= " FASE_HIST=  '5', ";
			$qCMP .= " USUA_HIST = 'Assistente Administrativo' ";
			$db->query="UPDATE historico SET ($qCMP) WHERE cod_usua='".mysql_real_escape_string($cod_usuario)."'";
			//echo $db->query; echo "<br>";
			$db->query();
}
if ($acaoprop=='fase8A') 
{
		if($cod_histf8=='')
		{
			$qCMP = '';
			$qCMP .= " COD_USUA, ";   $qVAL .= " '".mysql_real_escape_string($cod_usuario)."', ";
			$qCMP .= " DESC_HIST, ";  $qVAL .= " 'Remessa CCB/Pasta concluída', ";
			$qCMP .= " FASE_HIST, ";   $qVAL .= "'8', ";
			$qCMP .= " USUA_HIST, ";   $qVAL .= " '".$cLOGIN->cUSUARIO."', ";
			$qCMP .= " DATA_HIST ";   $qVAL .= "'".date("Y-m-d")."'  ";
			$db->query="INSERT INTO historico ($qCMP) VALUES ($qVAL)";
			//echo $db->query; echo "<br>";
			$db->query();
			$qCMP = '';
			$qCMP .= " COD_USUA, ";   $qVAL .= " '".mysql_real_escape_string($cod_usuario)."', ";
			$qCMP .= " DESC_HIST, ";  $qVAL .= " 'proposta Finalizada', ";
			$qCMP .= " FASE_HIST, ";   $qVAL .= "'9', ";
			$qCMP .= " USUA_HIST, ";   $qVAL .= " '".$cLOGIN->cUSUARIO."', ";
			$qCMP .= " DATA_HIST ";   $qVAL .="'".date("Y-m-d")."'  ";
			$db->query="INSERT INTO historico ($qCMP) VALUES ($qVAL)";
			//echo $db->query; echo "<br>";
			$db->query();
			}

}

$f_novo_evento = htmlentities($_POST['novo_evento']);
//echo "evento:".$f_novo_evento;
if($htadd!='')
{
	$qCMP = $qVAL = '';
	$qCMP .= " COD_USUA, ";   $qVAL .= " '".mysql_real_escape_string($cod_usuario)."', ";
	$qCMP .= " DESC_HIST, ";  $qVAL .= " '".mysql_real_escape_string($f_novo_evento)."', ";
	$qCMP .= " DATA_HIST, ";   $qVAL .= " now(), ";
	$qCMP .= " FASE_HIST, ";   $qVAL .=" '".mysql_real_escape_string($situacao)."', ";
	$qCMP .= " USUA_HIST ";   $qVAL .= " '".$cLOGIN->cUSUARIO."' ";
	$db->query="INSERT INTO historico ($qCMP) VALUES ($qVAL)";
	//echo $db->query; echo "<br>";
	$db->query();

} 
		$db->query="SELECT desc_hist, data_hist, usua_hist FROM historico WHERE cod_usua = '".mysql_real_escape_string($cod_usuario)."' order by data_hist desc"; 
							
		$db->query();

		if($db->qrcount>0)
		{
			?>
		  <a name="historico"></a>
			<br><b>Histórico</b>
			<div class="tListDiv listScroll" style="overflow:auto ">
				<table>
					<colgroup><col width="150" /><col width="120" /><col /></colgroup>
					<thead><tr><td>Usuário</td><td>Data</td><td>Descrição</td></tr></thead>
					<tbody>
					<?php
						for($i=0; $i<$db->qrcount; $i++)
						{
							$usua = $db->qrdata[$i]['usua_hist'];
							$hist_data = $utils->formataDataHora($db->qrdata[$i]['data_hist']);
							$hist_desc  = $db->qrdata[$i]['desc_hist'];
							?>
								<tr class="tL<?php echo $i%2 ? "1" : "2"; ?>">
									<td <?php echo $estilo;?> style="white-space:nowrap;"><?php echo $usua; ?></td>
									<td <?php echo $estilo;?>><?php echo $hist_data;?></td>
									<td <?php echo $estilo;?>><?php echo $hist_desc;?></td>
								</tr>
							<?php
						}
					?>
					</tbody>
				</table>
			</div>
			
  <?php
    ### NOVA ACAO ##################################################################### 
  ?>
  <a name="novaacao"></a>
  <br><b>Nova Ação</b>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
			<table cellpadding=0 cellspacing=5 border=0>
        <tr>
          <td align="right" valign="top">Evento:</td>
          <td align="left"  valign="top"><textarea style="width:500px; height:60px;" name="novo_evento" id="novo_evento"></textarea></td>
        </tr>
        <tr>
          <td align="right" valign="top">&nbsp;</td>
          <td align="left"  valign="top">
		<input type="image" name="htadd"   id="htadd"   src="images/buttons/bt_adicionar.gif"   value="Adcionar"   class="im" onClick="return addht('hd_htadd','adicionar')" />
		<input type="hidden" name="hd_htadd"   id="hd_htadd"     value=""  /></td>
        </tr>
      </table>
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
	</div>

	<?php
		}
?>
</form>
<?php
include "lib/footer.inc.php";
?>