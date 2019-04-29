<?
if($aProposta["situacao_ppst"]>=3){
			$db->query="Select DTASSCONTRATO_PPST,INFOADICIONAISFORT_PPST from proposta where cod_ppst='".($_POST["frm_cod_ppst"] ? $_POST["frm_cod_ppst"] : $_GET["cod_proposta"])."'";
			$db->query();
			if($db->qrcount>0)
			{
				$dtasscontrato= $utils->formataDataBRA($db->qrdata[0]['DTASSCONTRATO_PPST']);
				$info_fort=$db->qrdata[0]['INFOADICIONAISFORT_PPST'];
			}
			$db->query="Select cod_proponente, cod_municipio from proponente where cod_ppst='".($_POST["frm_cod_ppst"] ? $_POST["frm_cod_ppst"] : $_GET["cod_proposta"])."'";
			$db->query();
			if($db->qrcount>0)
			{
				$cod_usua=$db->qrdata[0]['cod_proponente'];
				$cod_municipio=$db->qrdata[0]['cod_municipio'];
			}
			$db->query="Select flagutilizacao from fgts where cod_usua='".$cod_usua."'";
			$db->query();
			if($db->qrcount>0)
			{
				$flgfgts= $db->qrdata[0]['flagutilizacao'];
			}
		if($_POST['svif']=='salvar')
		{
			$db->query="Update proposta set infoadicionaisfort_ppst='".$_POST['info_fort']."' where cod_ppst='".($_POST["frm_cod_ppst"] ? $_POST["frm_cod_ppst"] : $_GET["cod_proposta"])."'";
			$db->query();
		}

		if($_POST['info_fort']!='')
		{
			$info_fort=$_POST['info_fort'];
		}
		if($_POST['svQV']=='salvar')
		{
			$db->query="Update vendedor set qualificacao_vend='".$_POST['qualificacao_confirm_vend']."' where cod_ppst='".($_POST["frm_cod_ppst"] ? $_POST["frm_cod_ppst"] : $_GET["cod_proposta"])."'";
			if($db->query()) echo "<script>alert('Qualificação de Vendedor atualizada com sucesso!');</script>";
		}

		if($_POST['qualificacao_confirm_vend']!='')
		{
			$qualificacao_confirm_vend=$_POST['qualificacao_confirm_vend'];
		}

		?>
		<script>
		function salvarInfoFort()
		{
			document.getElementById('svif').value="salvar";
			return true;
		}
		function salvarQualificacaoVencd()
		{
			document.getElementById('svQV').value="salvar";
			return true;
		}
		</script>
        <?php
		if(($cLOGIN->iLEVEL_USUA == TPUSER_JURIDICO && $aProposta["situacao_ppst"]<=6) || ($aProposta["situacao_ppst"]>6) )
		{?>
		
				<div class="bloco_include">
					<a name="assinatura"></a>
					<div class="bloco_titulo">Qualificação Vendedor</div>
					<div class="quadroInterno">
						<div><img src="images/layout/subquadro_t.gif" alt="" /></div>
						<div class="quadroInternoMeio">
						<?php
						$db->query="Select qualificacao_vend from vendedor where cod_ppst='".($_POST["frm_cod_ppst"] ? $_POST["frm_cod_ppst"] : $_GET["cod_proposta"])."'";
						$db->query();
						$qualificacao_vend=$db->qrdata[0]['qualificacao_vend'];
						if($aProposta["situacao_ppst"]<=6){
							if($qualificacao_vend!=''){
						?>
                                <p>
                                <textarea name="qualificacao_confirm_vend" id="qualificacao_confirm_vend" cols="135" rows="10"><?php echo $qualificacao_vend;?></textarea>
                                </p><br>
                                <p align="right">
                                    <input class="im" type="image" name="Svqual_vend" id="Svqual_vend" src="images/buttons/bt_confirmar.gif" alt="Salvar Qualificação Vendedor" onClick="return salvarQualificacaoVencd();">
                                    <input type="hidden" name="svQV" id="svQV" value="">						
                                </p>
						<?php
							}else{
								echo "Esta proposta não atende ao modelo onde há prévia para confirmação da Qualificação do Vendedor.";
							}
						}else{
								echo "<b>$vendedor</b>";
						}
						?>
						</div>
						<div><img src="images/layout/subquadro_b.gif" alt="" /></div>
					</div>
				</div>
	
		<?php
		}
		if(($cLOGIN->iLEVEL_USUA == TPUSER_JURIDICO && $aProposta["situacao_ppst"]<=6) || ($aProposta["situacao_ppst"]>6 && $info_fort!='') )
		{

			?>
				
                <div class="bloco_include">
					<a name="assinatura"></a>
					<div class="bloco_titulo">Claúsulas Adicionais</div>
					<div class="quadroInterno">
						<div><img src="images/layout/subquadro_t.gif" alt="" /></div>
						<div class="quadroInternoMeio">
						<?php
						if($aProposta["situacao_ppst"]<=6){
						?>
						<p>
						<textarea name="info_fort" id="info_fort" cols="135" rows="10"><?php echo $info_fort;?></textarea>
						</p><br>
						<p align="right">
						<input class="im" type="image" name="Svinfo_fort" id="Svinfo_fort" src="images/buttons/bt_salvar.gif" alt="Salvar Cláusulas de Fortaleza" onClick="return salvarInfoFort();">
						<input type="hidden" name="svif" id="svif" value="">						</p>
						<?php
						}else{
								echo "<b>$info_fort</b>";
						}
						?>
						</div>
						<div><img src="images/layout/subquadro_b.gif" alt="" /></div>
					</div>
				</div>
				<?php
		}
}
if($aProposta["situacao_ppst"]>=6){?>

<div class="bloco_include">
	<a name="assinatura"></a>
	<div class="bloco_titulo">Agendamento da Assinatura do Contrato</div>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt="" /></div>
		<div class="quadroInternoMeio">
			<?
				if(
					(/*$cLOGIN->iLEVEL_USUA == TPUSER_ATENDENTE ||*/ $cLOGIN->iLEVEL_USUA == TPUSER_JURIDICO) && 
					$aProposta["situacao_ppst"]==6 && $dtasscontrato==''
				){
				
			?>
				<table cellpadding=0 cellspacing=5 border=0>
	      	<tr>
	          <td align="right" valign="top">Data da Assinatura do Contrato:<? $utils->obrig('prop_agendam'); ?></td>
	          <td align="left"  valign="top"><input type="text" style="width:80px;" name="dtasscontrato_ppst" id="dtasscontrato_ppst" value="" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10" /></td>
	          <td align="left"  valign="top">
			  <?php if($flgfgts=='S'){ echo "";}else{?>
			  <img src="images/buttons/bt_confirmar.gif" alt="Confirmar Agendamento" class="im" onClick="assinarContrato('<?=$crypt->encrypt('assinarContrato');?>')" /><?php }?></td>
	        </tr>
	      </table>
	      <input type="hidden" name="dtaprovacao_ppst" id="dtaprovacao_ppst" value="<?=$utils->formataDataBRA($aProposta["dtaprovacao_ppst"]);?>" />
	    <? }else{ ?>
				<table width="380" border=0 cellpadding=0 cellspacing=5>
		        <tr>
		          <td width="211" align="right" valign="top">Data da Assinatura do Contrato:</td>
		          <td width="96" align="left"  valign="top"><b><?=$utils->formataDataBRA($aProposta["dtasscontrato_ppst"]);?></b></td>
	             <?
 				if(($cLOGIN->iLEVEL_USUA == TPUSER_JURIDICO && ($aProposta["situacao_ppst"] > 6 && $aProposta["situacao_ppst"] < 10))  && $flgfgts!= "S"){

				 ?>
				  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<td width="53" align="right"  valign="top"><img src="images/buttons/bt_cancelar.gif" alt="Cancelar Agendamento de Contrato" class="im" onClick="cancelarAssinatura('<?=$crypt->encrypt('cancelarAssinatura');?>')" /></td>
			    <?
				}
				?>
				</tr>
	      </table>
	      <input type="hidden" name="dtasscontrato_ppst" id="dtasscontrato_ppst" value="<?=$utils->formataDataBRA($aProposta["dtasscontrato_ppst"]);?>" />
	    <? } ?>
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt="" /></div>
	</div>
</div>
<? } ?>