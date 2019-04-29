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
		( $cLOGIN->iLEVEL_USUA==TPUSER_JURIDICO && $aProposta["situacao_ppst"]==7 )
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
<?php
	}
?>
