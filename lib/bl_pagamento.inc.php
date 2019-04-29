<script language="JavaScript" type="text/javascript" src="./js/proposta_bl_pagamento.js"></script>
<? if( $aProposta["imovel"]["cod_uf"] != '' ){
 ?>
<a name="pagamento"></a>
<div class="bloco_include" id="bloco_pagamento">
	<div class="bloco_titulo">Pagamento</div>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt="" /></div>
		<div class="quadroInternoMeio">
			<?
			$db->query="Select * from valboleto where uf='".$aProposta["imovel"]["cod_uf"] ."'";
			$db->query();

			if($db->qrcount>0)
			{
				if($aProposta["imovel"]["cod_municipio"]==$db->qrdata[0]['CAPITAL']){
				$valorBoleto=$db->qrdata[0]['VALOR'];
				}
				else{
				$valorBoleto=$db->qrdata[0]['VLNAOCAPITAL'];
				}
			}
				/*if($aProposta["flgboletoavalpago_ppst"]=='S'){
					$valorBoleto = $aProposta["valorboletoaval_ppst"];
				}else{
					$aTMP = $oParametros->getValorBoleto($aProposta["imovel"]["cod_uf"]);
					$valorBoleto = $aTMP[0]['valor_param'];
				}*/
			
				if(
						$cLOGIN->iLEVEL_USUA == TPUSER_ATENDENTE &&
						($aProposta["situacao_ppst"]==3 || $aProposta["situacao_ppst"]==5) &&
						$aProposta["flgboletoavalpago_ppst"]!='S'
				){
					?>
		    		<table cellpadding=0 cellspacing=5 border=0 width="100%">
		    			<colgroup><col width="150" /><col width="*" /></colgroup>
		    			<tr>
		    			  <td align="right" valign="top">Valor do Boleto:</td>
		    			  <td align="left">
		    			  	<b>R$ <?=$utils->formataMoeda($valorBoleto);?></b>
		    			  	<input type="hidden" name="valorboletoaval_ppst" id="valorboletoaval_ppst" value="<?=$valorBoleto;?>" />
		    			  </td>
		    			  <? if($aProposta["situacao_ppst"]<6){ ?><td align="right" rowspan="3" valign="bottom"><img src="images/buttons/bt_gerar_boleto.gif" alt="Gerar Boleto" class="im" onClick="gerarBoleto('<?=$aProposta["imovel"]["cod_uf"];?>');" /></td><? } ?>
		    			</tr>
		    			<tr>
						<tr>
		    			  <td align="right" valign="top">Forma de Pagamento:<? $utils->obrig('flgboletoavalpago_ppst'); ?></td>
		    			  <td align="left">
											<input class="rd" name="flgformapagto_ppst" id="flgformapagto_ppst" value="B" <?php if($aProposta["flgformapagto_ppst"]=='B'){echo "checked";}?> type="radio"> 
											Boleto &nbsp;&nbsp; 
											<input class="rd" name="flgformapagto_ppst" id="flgformapagto_ppst" value="T"  <?php if($aProposta["flgformapagto_ppst"]=='T'){echo "checked";}?>  type="radio"> 
											Transferência Bancária<br></td>
		    			</tr>
		    			  <td align="right" valign="top">Confirmação de Pagamento:<? $utils->obrig('flgboletoavalpago_ppst'); ?></td>
		    			  <td align="left"><input type="checkbox" class="ck" name="flgboletoavalpago_ppst" value="S" id="flgboletoavalpago_ppst" <?=($aProposta["flgboletoavalpago_ppst"]=='S')?'disabled':'';?> <?=($aProposta["flgboletoavalpago_ppst"]=='S')?'checked':'';?> /></td>
		    			</tr>
		    			<tr>
		    			  <td align="right" valign="top">Data de Pagamento:<? $utils->obrig('dtpagtoboleto_ppst'); ?></td>
		    			  <td align="left"><input type="text" style="width:80px;" name="dtpagtoboleto_ppst" id="dtpagtoboleto_ppst" value="<?=$utils->formataDataBRA($aProposta["dtpagtoboleto_ppst"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10" <?=($aProposta["flgboletoavalpago_ppst"]=='S')?'disabled':'';?> /></td>
		    			</tr>
		    		</table>
			  	<? }else{ ?>
			  		<table cellpadding=0 cellspacing=5 border=0 width="100%">
			  			<colgroup><col width="150" /><col width="*" /></colgroup>
			  			<tr>
			  			  <td align="right" valign="top">Valor do Boleto:</td>
			  			  <td align="left"  valign="top"><b>R$ <?=$utils->formataMoeda($valorBoleto);?></b></td>
			  			  <? if($aProposta["situacao_ppst"]<6 && $cLOGIN->iLEVEL_USUA != TPUSER_ADMPREVI && $cLOGIN->iLEVEL_USUA != TPUSER_DESPACHANTE){ ?><td align="right" rowspan="2" valign="bottom"><img src="images/buttons/bt_gerar_boleto.gif" alt="Gerar Boleto" class="im" onClick="gerarBoleto('<?=$aProposta["imovel"]["cod_uf"];?>');" /></td><? } ?>
			  			</tr>
						<tr>
		    			  <td align="right" valign="top">Forma de Pagamento:</td>
		    			  <td align="left">
											<?php if($aProposta["flgformapagto_ppst"]=='B'){echo "<b>Boleto<b>";}?> 
											<?php if($aProposta["flgformapagto_ppst"]=='T'){echo "<b>Transferência Bancária<b>";}?></td>
		    			</tr>
			  			<tr>
			  			  <td align="right" valign="top">Data de Pagamento:</td>
			  			  <td align="left"  valign="top"><b><?
			  			  	if($aProposta["dtpagtoboleto_ppst"]){
			  			  		print $utils->formataDataBRA($aProposta["dtpagtoboleto_ppst"]);
			  			  	}
			  			  ?></b></td>
			  			</tr>
			  		</table>
			  	<?
			  }
			?>
	  	<input type="hidden" name="hidden_dtpagtoboleto_ppst" id="hidden_dtpagtoboleto_ppst" value="<?=$utils->formataDataBRA($aProposta["dtpagtoboleto_ppst"]);?>" />
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt="" /></div>
	</div>
</div>
<? } ?>