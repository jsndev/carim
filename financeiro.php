<?
include "./class/dbclasses.class.php";

$iREQ_AUT=1;
$aUSERS_PERM[] = TPUSER_FINANCEIRO;

$pageTitle = "Financeiro";
include "lib/header.inc.php";
$bol=$_GET['bol'];
$tarefas=$_GET['tarefas'];
$nn=$_GET['nn'];
$tipo=$_GET['tipo'];
  function formataD($data) {
		$dataTmp = "";
		if($data) {
		  $dataArray = split('[-\/\ ]',$data);
			$dataTmp = $dataArray[2].'/'.$dataArray[1].'/'.$dataArray[0];
		}
		return $dataTmp;
  }
  function formataF($valor,$desc=0) {
    // round
    $valor = str_replace('.',',',strval(round($valor,$desc)));
    $partes = split(',',$valor);
    $inteiro = '';
    $c = -1;
    for($i=strlen($partes[0]); $i >=0; $i--){
      if($c==3){ $inteiro = '.'.$inteiro; $c=0; }
      $inteiro = substr($partes[0],$i,1).$inteiro;
      $c++;
    }
    
    $output = $inteiro;
    
    if($desc > 0){
	    $fracao = substr($partes[1],0,$desc);
	    for($i=strlen($fracao); $i< $desc; $i++){
	      $fracao.='0';
	    }
	    $output .= ','.$fracao;
    }
    
    return $output;
  }

  function formataM($valor) {
    return formataF($valor,2);
  }
function formataMat($matricula) {
		$rgTmp = "";
		if ($matricula) {
		  $matricula = preg_replace("/\W/i","",$matricula);
		  $tam = strlen($matricula) - 1;
		  $tres=-1;
      for($i = $tam; $i >= 0; $i--){
        if($i==($tam-1)){ $rgTmp = '-'.$rgTmp; }
        if(($tres % 3 == 0)&&($tres > 0)){ $rgTmp = '.'.$rgTmp; }
        $rgTmp = substr($matricula, $i, 1).$rgTmp;
        $tres++;
      }
		}
		return $rgTmp;
  }
$vencimento=$_POST['vencimento'];
$valor=str_replace(".","",$_POST['valor']);
$valor=str_replace(",",".",$valor);
$numprop=$_POST['numprop'];
$acaoProposta=$_POST['acaoProposta'];
$conf=$_POST['confpg'];
$dt=$_POST['datapg'];

 if($_POST['vencimento']!='' && $_POST['valor']!='' && $acao="servico")
 {?>
 		<script>
		window.location='boleto_bb.php?venc=<?php echo $vencimento;?>&val=<?php echo $valor;?>&typ=sv';
		</script><?php
 }
 if($_POST['vencimento']!='' && $acao="proposta")
 {?>
 		<script>
		window.location='boleto_bb.php?venc=<?php echo $vencimento;?>&typ=pp&a=<?php echo $numprop;?>';
		</script><?php
 }

if($acaoProposta=='salvar')
{
	$db->query="Update boletosprevi set DTPAGTO='".$dt."', CONFPAGTO='".$conf."' where nossonum='".$nn."' and tipo='".$tipo."'";
	$db->query();
}
if($acaoProposta=='cancelar')
{
	$db->query="Delete from boletosprevi where nossonum='".$nn."'";
	//echo $db->query;
	$db->query();
}

?>
	<script language="JavaScript" type="text/javascript" src="./js/diversos.js"></script>

<script>
function emitirboleto(_nome)
{
	document.getElementeById('acao').value=_nome;
	return true;
}
function salvarConf(acao,nn)
{
	document.filtro.acaoProposta.value=acao;
	document.filtro.action += '#'+nn;
	//return true;
}
function cancelarBoleto(acao,nn)
{
	if(confirm("Tem certeza que deseja excluir este Boleto?")){
	document.filtro.acaoProposta.value=acao;
	document.filtro.action += '#'+nn;}
	else{
	return false;
	}
}

</script>
<form name="filtro" method="post" action="">
<input type="hidden" name="acao" id="acao" value="">
	<br><b>Filtros</b>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
      <table cellpadding=0 cellspacing=5 border=0>
        <tr>
          <td align="right">Matricula:</td>
          <td align="left"><input type="text" name="filtro_mat" value="<?=$_POST["filtro_mat"];?>" size="50"></td>
        </tr>
        <tr>
        	<td align="right">&nbsp;</td>
          <td align="left">
          	<input type="image" name="btFiltrar" id="btFiltrar" src="images/buttons/bt_filtrar.gif" value="Filtrar" class="im" />&nbsp;
          	<a href="financeiro.php"><img src="images/buttons/bt_limparfiltros.gif" alt="Limpar Filtros" class="im" /></a>
          </td>
        </tr>
      </table>
	  </div>
			<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>

		<br>
		<b>Participantes Finalizados</b>
		<div class="quadroInterno">
			<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
			<div class="quadroInternoMeio">
			<?
				$filtros = '';
				if(!empty($_POST["filtro_mat"])) {
					$f_prop_mat = mysql_real_escape_string(preg_replace('/\D/i','',$_POST["filtro_mat"]));
					$f_query_mat=" and b.id_lstn='".mysql_real_escape_string($f_prop_mat)."' ";
					$filtros .= ' [Matrícula:'.$_POST["filtro_mat"].'] ';
				}


				$cLOGIN->insert_log(1,1,'Visualização da Lista de propostas'.$filtros);
				$db->query="select 
								a.cod_usua,
								a.nome_usua,
								b.id_lstn,
								c.cpf_ppnt,
								date_format(d.data_ppst,'%d/%m/%Y') as data_ppst,
								d.situacao_ppst,
								d.indcancelamento_ppst,
								d.cod_ppst,
								d.dtokregistro_ppst
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
							and
								situacao_ppst = '11'
							$f_query_mat order by d.cod_ppst
							";
				$db->query();
				//echo $db->query;
				$a=$db->qrcount;
				if($db->qrcount>0) {
				
					?>
					<div class="tListDiv listScroll" style="overflow:auto ">
						<table>
							<colgroup>
								<col width="110"/>
								<col width="70"/>
								<col  width="70"/>
								
							</colgroup>
							<thead>
								<tr>
									<td >Nome</td>
									<td align="center">C.I.</td>
									<td align="center">Data de Conclusão</td>
								</tr>
							</thead>
							<tbody>
							<?
								$i=0;
								$a=0;
								while($i<$db->qrcount){
									if($db->qrdata[$i]['cod_ppst']!=$db->qrdata[$i-1]['cod_ppst']){
									
									?>
										<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
											<td ><?  echo $db->qrdata[$i]['nome_usua']; ?></td>
											<td align="center"><? echo formataMat($db->qrdata[$i]['id_lstn']); ?></td>
											<td align="center"><? echo formataD($db->qrdata[$i]['dtokregistro_ppst']); ?></td>
										</tr>
									<?
									 $a++;
									}
								  $i++;
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
			
		<table>
		<tr>
			<td width="189" height="21">
					<b>Total de Propostas:<font color="#CC0000"> <?php echo $a;?></font></b>
			</td>
			<td width="527" align="left"><b>Total de Faturamento Bruto: <font color="#CC0000">R$&nbsp;<?php $valortot=$a*600;echo formataM($a*600);?></font></b>
			</td>
		</tr>
	</table>
  		</div>
			<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
		</div>
		<p align="center">
</p>
	<table>
		<tr>
			<td width="128" height="21"><a href="financeiro.php?tarefas=gb#boleto"><img src="images/buttons/boletos.gif"></a>
			</td>
			<td width="132" align="left"><a href="financeiro.php?tarefas=conf#confirmacao"><img src="images/buttons/confpg.gif"></a>
			</td>
			<td width="487" align="left"><a href="financeiro.php?tarefas=rel#relatorio"><img src="images/buttons/relatorio.gif"></a>
			</td>
		</tr>
	</table>
	<?php
	if($tarefas=='rel'){
	?><br>
	<a name="relatorio"></a>
	<b>Tipo de Relatório</b>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
        
    <table width="254" border=0 cellpadding=0 align="center" cellspacing=5>
     	<tr>
			<td colspan="2" align="center"></td>
		</tr>
	  <tr>
            <td width="119" align="center"><a target="_blank" href="relatorio/relatorio.php?tipo=1"><u><b>PARCIAL</b></u></a></td>
            <td width="120" align="center"><a target="_blank" href="relatorio/relatorio.php?tipo=2"><u><b>FINAL</b></u></a></td>
          </tr>
        </table>
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
	</div><?php
	}
	if($tarefas=='gb'){
	?>
	<br>
	<a name="boleto"></a>
	<b>Tipo de Boleto</b>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
        
    <table width="725" border=0 cellpadding=0 cellspacing=5>
     	<tr>
			<td colspan="2"></td>
		</tr>
	  <tr>
            <td width="94"><input type="radio" <?php if($bol=='um'){echo "checked='checked'";}?> name="tp_bol" id="tp_bol" class="rd" value="1" onClick="window.location='financeiro.php?tarefas=gb&bol=um#boleto'">&nbsp;PROPOSTAS</td>
            <td width="616"><input type="radio" <?php if($bol=='dois'){echo "checked='checked'";}?> name="tp_bol" id="tp_bol" class="rd" value="2" onClick="window.location='financeiro.php?tarefas=gb&bol=dois#boleto'">&nbsp;SERVIÇOS</td>
       </tr>
	   <?php 
	   if($bol=='um')
	   {
	   ?>
	   <tr>
	   <td colspan="2"><hr></td>
	   </tr>
	  <tr>
	  	<td colspan="2">
			<table border="1" cellspacing="0" bordercolor="#999999">
				<tr>
					<td width="119" align="center"><b>Imposto</b></td>
					<td width="119" align="center"><b>Percentual</b></td>
					<td width="119" align="center"><b>Retenção</b></td>
				</tr>
				<tr>
					<td width="119" align="center"><b>CSLL</b></td>
					<td width="119" align="center">0,00 %</td>
					<td width="119" align="center"><font color="#CC0000"><b>R$ <?php $csll=round($valortot*0.00,2); echo formataM(round($valortot*0.00,2));?></b></font></td>
				</tr>
				<tr>
					<td width="119" align="center"><b>PIS</b></td>
					<td width="119" align="center">0,00 %</td>
					<td width="119" align="center"><font color="#CC0000"><b>R$ <?php $pis=round($valortot*0.00,2); echo formataM(round($valortot*0.00,2));?></b></font></td>
				</tr>
				<tr>
					<td width="119" align="center"><b>COFINS</b></td>
					<td width="119" align="center">0,00 %</td>
					<td width="119" align="center"><font color="#CC0000"><b>R$ <?php $cofins=round($valortot*0.00,2); echo formataM(round($valortot*0.00,2));?></b></font></td>
				</tr>
				<tr>
					<td width="119" align="center"><b>IR</b></td>
					<td width="119" align="center">0,00 %</td>
					<td width="119" align="center"><font color="#CC0000"><b>R$ <?php $ir=round($valortot*0.00,2); echo formataM(round($valortot*0.00,2));?></b></font></td>
				</tr>
			</table>
			</td>
		<tr>
			<td colspan="2" align="left"><b>Total de Impostos: <font color="#CC0000">R$&nbsp;<?php echo formataM(round($csll+$pis+$cofins+$ir,2));?></font></b><br>
										 <b>Total de Faturamento Líquido: <font color="#CC0000">R$&nbsp;<?php echo formataM(round($valortot-($csll+$pis+$cofins+$ir),2));?></font></b>
			</td>
		</tr>
		<tr>
			<td colspan="2"></td>
		</tr>
		<tr>
			<td colspan="2"></td>
		</tr>
		</tr>
            <td width="94" align="right">Vencimento:</td>
            <td width="616" align="left"><input type="text" size="3" name="vencimento" id="vencimento" value="<?php echo $vencimento;?>">&nbsp;dias</td>
       </tr>
	  <tr>
            <td width="94" align="right"></td>
            <td width="616" align="left"><input type="image" class="im" src="images/buttons/gboletos.gif" onclick="emitirboleto('proposta');"></td>
       </tr>
	   <?php
	   }
	   if($bol=='dois')
	   {
	   ?>
	   <tr>
	   <td colspan="2"><hr></td>
	   </tr>
	  <tr>
            <td width="94" align="right">Vencimento:</td>
            <td width="616" align="left"><input type="text" size="3" name="vencimento" id="vencimento" value="<?php echo $vencimento;?>">&nbsp;dias</td>
       </tr>
	  <tr>
            <td width="94" align="right">Valor:</td>
            <td width="616" align="left"><input type="text" size="10" name="valor" id="valor" value="<?php echo $valor;?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraMoeda(this,event,'atualizaValoresProposta()',2);" maxlength="12"></td>
       </tr>
	  <tr>
            <td width="94" align="right"></td>
            <td width="616" align="left"><input type="image" class="im" src="images/buttons/gboletos.gif" onclick="emitirboleto('servico');"></td>
       </tr>
	   <?php
	   }
	   ?>
        </table>
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
	</div>
<?php
	}
	if($tarefas=='conf')
	{?>
	<a name="confirmacao"></a><br>
	<b>Boletos Emitidos</b>
		<div class="quadroInterno">
			<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
			<div class="quadroInternoMeio">
<?php
	
		$db->query="Select * from boletosprevi";
		$db->query();
		if($db->qrcount>0)
		{
?>
						<div class="tListDiv listScroll" style="overflow:scroll ">
						<table>
							<colgroup>
								<col width="200"/>
								<col width="100"/>
								<col  width="100"/>
								
							</colgroup>
							<thead>
								<tr>
									<td align="center">Nosso Número</td>
									<td align="center">Dt. Emissão</td>
									<td align="center">Dt. Vencimento</td>
									<td align="center">Valor (R$)</td>
									<td align="center">Tipo</td>
								</tr>
							</thead>
							<tbody>
							<?
							
								for($i=0; $i<$db->qrcount; $i++){
									
									?>
										<a name="<?php echo $db->qrdata[$i]['NOSSONUM'];?>"></a>
										<tr class="tL<? echo $i%2 ? "1" : "2"; ?>">
											<td align="center"><b>
											<?php
											if($nn=='' && $tipo==''){
											?>
											<a href="financeiro.php?tarefas=conf&nn=<?php echo $db->qrdata[$i]['NOSSONUM'];?>&tipo=<?php echo $db->qrdata[$i]['TIPO'];?>#confirmacao"><?php echo $db->qrdata[$i]['NOSSONUM']; ?></a>
											<?php
											}
											?>
											<?php
											if($nn!='' && $tipo!=''){
											?>
											<a href="financeiro.php?tarefas=conf#confirmacao"><?php echo $db->qrdata[$i]['NOSSONUM']; ?></a>
											<?php
											}
											?>
											
											</b></td>
											<td align="center"><? echo $db->qrdata[$i]['DTEMISSAO']; ?></td>
											<td align="center"><? echo $db->qrdata[$i]['DTVENC']; ?></td>
											<td align="center"><? echo formataM($db->qrdata[$i]['VALOR']); ?></td>
											<td align="center"><? if($db->qrdata[$i]['TIPO']=='pp')echo "Proposta"; else echo "Serviços"; ?></td>

										</tr>
<?php
											if($nn==$db->qrdata[$i]['NOSSONUM'] && $tipo=='pp'){?>
										  <tr>
											<td colspan="5" align="center">
												<table border="1" cellspacing="0" bordercolor="#999999">
													<tr>
														<td width="119" align="center"><b>Imposto</b></td>
														<td width="119" align="center"><b>Percentual</b></td>
														<td width="119" align="center"><b>Retenção</b></td>
													</tr>
													<tr>
														<td width="119" align="center"><b>CSLL</b></td>
														<td width="119" align="center">0,00 %</td>
														<td width="119" align="center"><font color="#CC0000"><b>R$ <?php echo formataM($db->qrdata[$i]['CSLL']);?></b></font></td>
													</tr>
													<tr>
														<td width="119" align="center"><b>PIS</b></td>
														<td width="119" align="center">0,00 %</td>
														<td width="119" align="center"><font color="#CC0000"><b>R$ <?php echo formataM($db->qrdata[$i]['PIS']);?></b></font></td>
													</tr>
													<tr>
														<td width="119" align="center"><b>COFINS</b></td>
														<td width="119" align="center">0,00 %</td>
														<td width="119" align="center"><font color="#CC0000"><b>R$ <?php echo formataM($db->qrdata[$i]['COFINS']);?></b></font></td>
													</tr>
													<tr>
														<td width="119" align="center"><b>IR</b></td>
														<td width="119" align="center">0,00 %</td>
														<td width="119" align="center"><font color="#CC0000"><b>R$ <?php echo formataM($db->qrdata[$i]['IR']);?></b></font></td>
													</tr>
												</table>
												</td>
											<tr>
												<td colspan="5" align="left"><b>Total de Impostos: <font color="#CC0000">R$&nbsp;<?php echo formataM(round($db->qrdata[$i]['CSLL']+$db->qrdata[$i]['PIS']+$db->qrdata[$i]['COFINS']+$db->qrdata[$i]['IR'],2));?></font></b><br>
																			 
												</td>
											</tr>
											<?php
											}
											?>	
											<?php
											if($nn==$db->qrdata[$i]['NOSSONUM'] && $tipo==$db->qrdata[$i]['TIPO']){
											?>
										<tr><td colspan="5">
											<table>
											  <tr>
											  	<td align="center"><b>Confirm. Pagamento</b></td>
												<td width="127" align="center"><b>Data de Pagamento</b></td>
												<td></td>
											  </tr>
											  <tr>
											  		<td width="124" align="center"><input <?php if($db->qrdata[$i]['CONFPAGTO']=='S' && $db->qrdata[$i]['DTPAGTO']!='')echo "disabled";?> type="checkbox" value="S" <?php if($conf=='S' || $db->qrdata[$i]['CONFPAGTO']=='S')echo "checked='checked'";?> name="confpg" id="confpg" class="rd"></td>
													<td align="center">
													<?php
													if($db->qrdata[$i]['DTPAGTO']=='' || $db->qrdata[$i]['CONFPAGTO']==''){
													?>
													<input type="text" name="datapg" id="datapg" value="<?php if($db->qrdata[$i]['DTPAGTO']==''){echo $dt;}else{echo $db->qrdata[$i]['DTPAGTO'];}?>" size="15" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10" />&nbsp;&nbsp;&nbsp;
													<?php
													}elseif($db->qrdata[$i]['DTPAGTO']!='' && $db->qrdata[$i]['CONFPAGTO']=='S'){ 
													echo $db->qrdata[$i]['DTPAGTO'];
													}
													?>
													
													</td>
													<td>
													<?php 
													if($db->qrdata[$i]['DTPAGTO']=='' || $db->qrdata[$i]['CONFPAGTO']==''){
													?>
													<input type="image" name="btSalvar"   id="btSalvar"   src="images/buttons/bt_salvar.gif"   value="Salvar"   class="im" onClick="salvarConf('salvar','confirmacao');" />&nbsp;&nbsp;&nbsp;
													<input type="image" name="btCancelar"   id="btCancelar" src="images/buttons/bt_cancelar.gif"  value="Cancelar"   class="im" onClick="cancelarBoleto('cancelar','confirmacao');" />

													<?php
													} 
													?>
													<input type="hidden" name="acaoProposta" id="acaoProposta" value=""></td>
											  </tr>
											</table>
										</td></tr>
											<?php
											}
											?>

									<?
										
								}
							?>
							</tbody>
						</table>
					</div>
					<?
		} else {
			echo("Nenhum boleto encontrado.");
		}

			?>
  		</div>
			<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
		</div>
<?php
}
?>

	<input type="hidden" name="numprop" value="<?php echo $a;?>" id="numprop">
</form>
<?
include "lib/footer.inc.php";
?>