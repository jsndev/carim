<script language="JavaScript" type="text/javascript" src="./js/proposta_bl_botoes.js"></script>
<?

foreach($aProposta["proponentes"] as $kPpnt=>$vPpnt){
				if($vPpnt["cod_proponente"] == $_POST["frm_cod_ppnt"]){
					$aAltPpnt = $vPpnt;
				}
			}
			$db->query="Select * from fgts where cod_usua='".$vPpnt["cod_proponente"]."'";
			$db->query();
			if($db->qrcount>0)
			{
				$flag_fgts=$db->qrdata[0]['FLAGUTILIZACAO'];
			}
			if(
		( $cLOGIN->iLEVEL_USUA==TPUSER_JURIDICO && $aProposta["situacao_ppst"]==6 )
	){?>
	<div class="bloco_include">
	<a name="assinatura"></a>
	<div class="bloco_titulo">Aprovação da Proposta</div>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt="" /></div>
		<div class="quadroInternoMeio">

		<table width="442" border=0 cellpadding=0 cellspacing=5>
		        <tr>
		         	 <td width="172" align="right" valign="top">Data da Aprovação da Proposta:</td>
		         	 <td width="21" align="left"  valign="top"><b><?=$utils->formataDataBRA($dtaprov_ppst);?></b></td>
				<? if($flag_fgts=='')
				{
				?>	
					<td width="37">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img name="cancAprovProp" id="cancAprovProp" src="images/buttons/bt_cancelar.gif" alt="Cancelar Aprovação de Proposta" class="im" onClick="ReprovProp('<?=$crypt->encrypt('cancelar_aprovacao_proposta');?>');" /></td>
				<?
				}else
				{
				?>
					<td width="165">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img name="cancAprovProp" id="cancAprovProp" src="images/buttons/bt_cancelar.gif" alt="Cancelar Aprovação de Proposta" class="im" onClick="ReprovProp('<?=$crypt->encrypt('cancelar_aprovacao_proposta_fgts');?>');" /></td>
				<?
				}
				?>								
				</tr>
	      </table>

		</div>
		<div><img src="images/layout/subquadro_b.gif" alt="" /></div>
	</div>
</div>
	  <?
	}else{?>
	<div class="bloco_include">
	<a name="assinatura"></a>
	<div class="bloco_titulo">Aprovação da Proposta</div>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt="" /></div>
		<div class="quadroInternoMeio">

		<table width="293" border=0 cellpadding=0 cellspacing=5>
		        <tr>
		         	 <td width="166" align="left" valign="top">Data da Aprovação da Proposta:</td>
		         	 <td width="112" align="left"  valign="top"><b><?=$utils->formataDataBRA($dtaprov_ppst);?></b></td>
				</tr>
	      </table>

		</div>
		<div><img src="images/layout/subquadro_b.gif" alt="" /></div>
	</div>
</div>
	  <?
	}?>
<div class="bloco_include" style="text-align:center; padding:10px 0px;">
	<?php	
	if(
		( $cLOGIN->iLEVEL_USUA==TPUSER_PROPONENTE && ($aProposta["situacao_ppst"]==1 || $aProposta["situacao_ppst"]==3 || $aProposta["situacao_ppst"]==5) )
		||
		( $cLOGIN->iLEVEL_USUA==TPUSER_ATENDENTE && ($aProposta["situacao_ppst"]==3 || $aProposta["situacao_ppst"]==5) )
		||
		( $cLOGIN->iLEVEL_USUA==TPUSER_DESPACHANTE && $aProposta["situacao_ppst"]==3 )
		||
		( $cLOGIN->iLEVEL_USUA==TPUSER_JURIDICO && $aProposta["situacao_ppst"]==4 )
	){
?>
	<? if( $cLOGIN->iLEVEL_USUA==TPUSER_JURIDICO ){ ?>
	  <img name="btRetornar" id="btRetornar" src="images/buttons/bt_retornar.gif" alt="Retornar" class="im" onClick="retornarProposta('<?=$crypt->encrypt('retornar');?>');" />
	  <img name="btAprovar"  id="btAprovar"  src="images/buttons/bt_aprovar.gif"  alt="Aprovar"  class="im" onClick="aprovarProposta('<?=$crypt->encrypt('concluir');?>');"  />
	<? }else{ ?>
		<img name="btSalvar"   id="btSalvar"   src="images/buttons/bt_salvar.gif"   alt="Salvar"   class="im" onClick="salvarProposta('<?=$crypt->encrypt('salvar');?>');" />
		<?
			if ((
				($cLOGIN->iLEVEL_USUA==TPUSER_PROPONENTE && $aProposta["situacao_ppst"]==1) ||
				 $cLOGIN->iLEVEL_USUA==TPUSER_ATENDENTE )|| $corrigir=='sim'
			){
		?>
	  	<img name="btConcluir" id="btConcluir" src="images/buttons/bt_concluir.gif" alt="Concluir" class="im" onClick="concluirProposta('<?=$crypt->encrypt('concluir');?>');" />
	  <? } ?>
  <? } ?>
<? } ?>
  <?php
if (($cLOGIN->iLEVEL_USUA==TPUSER_ATENDENTE || $cLOGIN->iLEVEL_USUA==TPUSER_JURIDICO) && $aProposta["situacao_ppst"]<6){  ?>
<a href="fpdf/previa_contrato.php?cod_proposta=<?php echo $aProposta["cod_ppst"];?>" target="_blank"><img src="images/buttons/previa_contrato.png"></a>
<?php
}
if (($cLOGIN->iLEVEL_USUA==TPUSER_ATENDENTE && $aProposta["situacao_ppst"]>4)){  
?>
<a href="corrigir.php?cod_proposta=<?php echo $aProposta["cod_ppst"];?>">CORRIGIR</a>
<?php
}
?>
 </div>
