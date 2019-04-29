<script language="JavaScript" type="text/javascript" src="./js/proposta_bl_proposta.js"></script>
<a name="proposta"></a>
<div class="bloco_include">
	<div style="float:right;"><span class="obrig">* campos obrigatórios</span></div>
	<div class="bloco_titulo">Proposta</div>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
		  <div style="float:left; width:400px;">
<?php

$db->query = "SELECT * FROM proponente WHERE COD_PPST = {$aProposta["cod_ppst"]}";
$db->query();
$somaFgtsPpst = 0;
if ($db->qrdata) {

	foreach ($db->qrdata as $proponente) {

		$db->query = "Select SUM(VALORDEBITADO) AS total from contasfgts where cod_usua='".$proponente['COD_PROPONENTE']."'";
		$db->query();
		if($db->qrcount>0)
		{
			$somaFgtsPpst += $db->qrdata[0]['total'];
		}

	}
}

if($aProposta["tf_ppst"]!="S"){	
				if($aProposta["vlprestsol_ppst"]=='' && $aProposta["przfinsol_ppst"]==''){ $aProposta["przfinsol_ppst"]=' '; }

				// SOMA DOS VALORES DA PROPOSTA -------------------------------------------------- //
				$somaCompraPpst  = 0;
				$somaEntradaPpst = 0;
				//$somaFGTSPpst = 0;
				$somaFinancPpst  = 0;
				$somaSinalPpst   = 0;
				if (is_array($aProposta["proponentes"]) && @count($aProposta["proponentes"]) > 0) {
					foreach($aProposta["proponentes"] as $kPpnt=>$registroPpnt){
						$somaCompraPpst  += $registroPpnt["vlcompra_ppnt"];
						$somaEntradaPpst +=	$registroPpnt["vlentrada_ppnt"];
						//$somaFgtsPpst    +=	$registroPpnt["vlfgts_ppnt"];
						$somaSinalPpst   += $registroPpnt["vlsinal_ppnt"];
					}
				}
				$somaFinancPpst  = $somaCompraPpst - $somaEntradaPpst - $somaFgtsPpst;
}else{
	
	$db->query="Select * from proponente where cod_ppst='".$aProposta["cod_ppst"]."'";					
	$db->query();
	if($db->qrcount>0){
		$somaFinancPpst=$db->qrdata[0]['VLFINSOL_PPNT'];
	}
}

if ($somaFinancPpst < 0) {
	$somaFinancPpst = 0;
}
				// ------------------------------------------------------------------------------- //
$db->query="Select * from proponente where cod_ppst='".$aProposta["cod_ppst"]."'";					
$db->query();
if($db->qrcount>0)
{
	$i=1;
	$a=$db->qrcount;
	while($i<=$db->qrcount)
	{
		$cod_ppnt[$i]=$db->qrdata[$i-1]['COD_PROPONENTE'];
		$i++;
	}
}


$somaFgts=$somaFgtsPpst;


				if(
					($cLOGIN->iLEVEL_USUA == 1 && $aProposta["situacao_ppst"] == 1)
					||
					(
						$cLOGIN->iLEVEL_USUA == 2 &&
						($aProposta["situacao_ppst"]==3 || $aProposta["situacao_ppst"]==5)
					)
				){
				?>
		      <table cellpadding=0 cellspacing=5 border=0>
			  <input type="hidden" name="somaCompraPpst" id="somaCompraPpst" value="<?=$utils->formataMoeda($somaCompraPpst);?>"  />
			  
							<colgroup>
			<col width="150" /><col />
		</colgroup>

					  <? if(FLG_PREVI){ ?>
			        <tr>
			          <td align="right">Valor de Compra:</td>
			          <td align="left" id="compra_total" style="font-weight:bold;">R$ <?=$utils->formataMoeda($somaCompraPpst);?></td>
			        </tr>
			        <tr>
			          <td align="right">Valor de Entrada:</td>
			          <td align="left" id="entrada_total" style="font-weight:bold;">R$ <?=$utils->formataMoeda($somaEntradaPpst);?></td>
			        </tr>
			        <tr>
			          <td align="right">Valor do FGTS:</td>
			          <td align="left" id="fgts_total" style="font-weight:bold;">R$ <?=$utils->formataMoeda($somaFgts);?></td>
			        </tr>
			        <tr>
			          <td align="right">Valor de Sinal:</td>
			          <td align="left" id="sinal_total" style="font-weight:bold;">R$ <?=$utils->formataMoeda($somaSinalPpst);?></td>
			        </tr>
		 	        <tr>
			          <td align="right">Valor do Financiamento: </td>
			          <td align="left" id="valor_total" style="font-weight:bold; color:#600;">R$ <?=$utils->formataMoeda($somaFinancPpst);?></td>
			        </tr>
					 <tr>
			          <td align="right">Valor da Prestação:</td>
			          <td align="left" style="font-weight:bold; color:#600;"><div id='resultadoSomaparcaprovada'  style="font-weight:bold; color:#600;"></div></td>
			        </tr>
					<tr>
			          <td align="right">Prazo:</td>
			          <td align="left" style="font-weight:bold; color:#600;"><div id='resultadoPrazofinanciamento'  style="font-weight:bold; color:#600;"></div></td>
			        </tr>
					  <? }else{ ?>
			        <tr>
			          <td align="right">Valor de Compra (R$):<? $utils->obrig('valorcompra_ppst'); ?></td>
			          <td align="left"><input type="text" name="valorcompra_ppst" id="valorcompra_ppst" style="width:80px;" value="<?=$utils->formataMoeda($aProposta["valorcompra_ppst"]);?>" maxlength="20" onKeyDown="return teclasFloat(this,event);" onKeyUp="return mascaraMoeda(this,event);" onFocus="this.select();" /></td>
			        </tr>
			        <tr>
			          <td align="right">Valor de Entrada (R$):</td>
			          <td align="left"><input type="text" name="valordevsinalsol_ppst" id="valordevsinalsol_ppst" style="width:80px;" value="<?=$utils->formataMoeda($aProposta["valordevsinalsol_ppst"]);?>" maxlength="20" onKeyDown="return teclasFloat(this,event);" onKeyUp="return mascaraMoeda(this,event,'atualizaValFinan()');" onFocus="this.select();" /></td>
			        </tr>
			         <tr>
			          <td align="right">Valor do FGTS:</td>
			          <td align="left" id="fgts_total" style="font-weight:bold;">R$ <?=$utils->formataMoeda($somaFgts);?></td>
			        </tr>
		 	        <tr>
			          <td align="right">Valor do Financiamento (R$):</td>
			          <td align="left" id="valor_total" style="font-weight:bold;" height="18"><?=$utils->formataMoeda($aProposta["vlfinsol_ppst"]);?></td>
			        </tr>
			        <tr>
			          <td align="right">Prestação (R$):</td>
			          <td align="left" height="18">
			          	<input type="radio" class="rd" name="sel_tipo_finan" id="sel_tipo_finan" value="1" onclick="selecionaTipoFinan();" <?=(($aProposta["vlprestsol_ppst"])?'checked':'');?> />
			          	<span id="spnParcela" <?=(($aProposta["vlprestsol_ppst"])?'':'style="display:none"');?>>
			          		<? $utils->obrig('vlprestsol_ppst'); ?>
			          		<input type="text" name="vlprestsol_ppst" id="vlprestsol_ppst" style="width:80px;" value="<?=$utils->formataMoeda($aProposta["vlprestsol_ppst"]);?>" maxlength="20" onKeyDown="return teclasFloat(this,event);" onKeyUp="return mascaraMoeda(this,event,'atualizaValFinan()');" onFocus="this.select();" />
				          </span>
			          </td>
			        </tr>
			        <tr>
			          <td align="right">Prazo (em meses):</td>
			          <td align="left" height="18">
			          	<input type="radio" class="rd" name="sel_tipo_finan" id="sel_tipo_finan" value="2" onclick="selecionaTipoFinan();" <?=(($aProposta["przfinsol_ppst"])?'checked':'');?> />
			          	<span id="spnPrazo" <?=(($aProposta["przfinsol_ppst"])?'':'style="display:none"');?>>
			          		<? $utils->obrig('przfinsol_ppst'); ?>
			          		<input type="text" name="przfinsol_ppst" id="przfinsol_ppst" style="width:40px;" value="<?=$aProposta["przfinsol_ppst"];?>" maxlength="3" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraInt(this,event,'atualizaValFinan()');" onFocus="this.select();" />
				          </span>
			          </td>
			        </tr>
			      <? } ?>
		        <? // o "iLEVEL_USUA=1" (proponente) não preenche estes campos
							if($cLOGIN->iLEVEL_USUA > 1){ ?>
			        <tr>
			          <td align="right">Seguro (R$):<? $utils->obrig('valorseguro_ppst'); ?></td>
			          <td align="left"><input type="text" name="valorseguro_ppst" id="valorseguro_ppst" style="width:80px;" value="<?=$utils->formataMoeda($aProposta["valorseguro_ppst"]);?>" maxlength="20" onKeyDown="return teclasFloat(this,event);" onKeyUp="return mascaraMoeda(this,event);" onFocus="this.select();" /></td>
			        </tr>
			        <tr>
			          <td align="right">Taxa Manutenção (R$):<? $utils->obrig('valormanutencao_ppst'); ?></td>
			          <td align="left"><input type="text" name="valormanutencao_ppst" id="valormanutencao_ppst" style="width:80px;" value="19,00" maxlength="20" onKeyDown="return teclasFloat(this,event);" onKeyUp="return mascaraMoeda(this,event);" onFocus="this.select();" /></td>
			        </tr>
	        	<? } ?>
	        	<? if(FLG_PREVI){ ?>
	        		<? if($cLOGIN->iLEVEL_USUA == 2 && $aProposta["dtapresdoc_ppst"]==''){?>
				        <tr>
				          <td align="right">Data da Apresentação dos Documentos:<? $utils->obrig('dtapresdoc_ppst'); ?></td>
				          <td align="left"><input type="text" style="width:80px;" name="dtapresdoc_ppst" id="dtapresdoc_ppst" value="<?=$utils->formataDataBRA($aProposta["dtapresdoc_ppst"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10" /></td>
				        </tr>
				      <? }elseif($aProposta["dtapresdoc_ppst"]){ ?>
				        <tr>
				          <td align="right">Data da Apresentação dos Documentos:</td>
				          <td align="left"><b><?=$utils->formataDataBRA($aProposta["dtapresdoc_ppst"]);?></b></td>
				        </tr>
		        	<? } ?>
	        	<? } ?>
		      </table>
		      
				<? }else{ ?>
				
		  		<table cellpadding=0 cellspacing=5 border=0>
		  					<colgroup>
			<col width="150" /><col />
		</colgroup>

					<? if(FLG_PREVI){ ?>
			       
				    <tr>
			          <td align="right">Valor de Compra:</td>
			          <td align="left" style="font-weight:bold;" valign="top">R$ <?=$utils->formataMoeda($somaCompraPpst);?></td>
			        </tr>
			        <tr>
			          <td align="right">Valor de Entrada:</td>
			          <td align="left" style="font-weight:bold;" valign="top">R$ <?=$utils->formataMoeda($somaEntradaPpst);?></td>
			        </tr>
                    <tr>
			          <td align="right">Valor do FGTS:</td>
			          <td align="left" id="fgts_total" style="font-weight:bold;">R$ <?=$utils->formataMoeda($somaFgts);?></td>
			        </tr>
		 	        <tr>
			          <td align="right">Valor do Financiamento:</td>
			          <td align="left" style="font-weight:bold; color:#600;" valign="top">R$ <?=$utils->formataMoeda($somaFinancPpst);?></td>
			        </tr>
										 <tr>
			          <td align="right">Valor da Prestação:</td>
			          <td align="left" style="font-weight:bold; color:#600;"><div id='resultadoSomaparcaprovada'  style="font-weight:bold; color:#600;"></div></td>
			        </tr>
					<tr>
			          <td align="right">Prazo:</td>
			          <td align="left" style="font-weight:bold; color:#600;"><div id='resultadoPrazofinanciamento'  style="font-weight:bold; color:#600;"></div></td>
			        </tr>
		  			<? }else{ ?>
			  			<tr>
			  			  <td align="right" valign="top">Compra:</td><td align="left"><b>R$ <?=$utils->formataMoeda($aProposta["valorcompra_ppst"]);?></b></td>
			  			</tr>
			  			<tr>
			  			  <td align="right" valign="top">Entrada:</td><td align="left"><b>R$ <?=$utils->formataMoeda($aProposta["valordevsinalsol_ppst"]);?></b></td>
			  			</tr>
			  		<tr>
			          <td align="right">Valor do FGTS:</td>
			          <td align="left" id="fgts_total" style="font-weight:bold;">R$ <?=$utils->formataMoeda($somaFgts);?></td>
			        </tr>
			  			<tr>
			  			  <td align="right" valign="top">Financiamento:</td><td align="left"><b>R$ <?=$utils->formataMoeda($aProposta["vlfinsol_ppst"]);?></b></td>
			  			</tr>
			  			<? if($aProposta["vlprestsol_ppst"]){ ?>
			  			<tr>
			  			  <td align="right" valign="top">Prestação:</td><td align="left"><b>R$ <?=$utils->formataMoeda($aProposta["vlprestsol_ppst"]);?></b></td>
			  			</tr>
			  			<? } ?>
			  			<? if($aProposta["przfinsol_ppst"]){ ?>
			  			<tr>
			  			  <td align="right" valign="top">Prazo:</td><td align="left"><b><?=$aProposta["przfinsol_ppst"];?> meses</b></td>
			  			</tr>
			  			<? } ?>
		  			<? } ?>
		  			<tr>
		  			  <td align="right" valign="top">Seguro:</td><td align="left"><b>R$ <?=$utils->formataMoeda($aProposta["valorseguro_ppst"]);?></b></td>
		  			</tr>
		  			<tr>
		  			  <td align="right" valign="top">Taxa Manutenção:</td><td align="left"><b>R$ 19,00</b></td>
		  			</tr>
	        	<? if(FLG_PREVI && $aProposta["dtapresdoc_ppst"]){ ?>
			        <tr>
			          <td align="right">Data da Apresentação dos Documentos:</td>
			          <td align="left"><b><?=$utils->formataDataBRA($aProposta["dtapresdoc_ppst"]);?></b></td>
			        </tr>
	        	<? } ?>
		  		</table>
			
							<? } ?>
		  </div>
 			
			<div style="float:left; width:150px; padding-top:3px;">
				<? 
				if(
					($cLOGIN->iLEVEL_USUA == 1 && $aProposta["situacao_ppst"] == 1)
					||
					(
						$cLOGIN->iLEVEL_USUA == 2 &&
						($aProposta["situacao_ppst"]==3 || $aProposta["situacao_ppst"]==5)
					)
				){
				?>
       		<img src="images/buttons/bt_simulador.gif" alt="" class="cursorMao" onclick="exibeSimulador();" />
       	<? } ?>
			</div>
<?
if(($aProposta["situacao_ppst"]==4 || $aProposta["situacao_ppst"]==6 || $aProposta["situacao_ppst"]==7) && $cLOGIN->iLEVEL_USUA!=TPUSER_PROPONENTE) {?>			
<div align="center" class="warning" id="divResultadoProposta" style="border:1px solid #DDDDDD; background-color: #F5F5F5; padding: 5px 20px; float:right; width:195px; margin:5px 35px;">
		<u><b>Cancelar Proposta à pedido do Proponente:</b></u><br /><br />
				<? //if($flag_fgts=='')
				//{
				?>	<p align="center">
					<img name="cancProp" id="cancProp" src="images/buttons/bt_cancelar.gif" alt="Cancelar Proposta a pedido do Participante" class="im" onClick="CancPropostaPP('<?=$crypt->encrypt('cancelar_proposta_pp');?>');" />
					</p>
		
	</div>	
<?
}
?>	</div>
		<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
	</div>
</div>