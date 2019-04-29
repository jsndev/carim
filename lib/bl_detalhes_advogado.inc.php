<?
	$aAltCkAdv = array();
	if($acaoProposta=='altCkAdv'){
		if (is_array($aProposta["checklistadvogado"]) && @count($aProposta["checklistadvogado"]) > 0) {
			foreach($aProposta["checklistadvogado"] as $kckAdv=>$vckAdv){
				if($vckAdv["cod_clad"] == $_POST["frm_cod_ck_adv"]){
					$aAltCkAdv = $vckAdv;
				}
			}
		}
	}
?>
<table cellpadding=0 cellspacing=5 border=0>
  <tr>
    <td align="right" valign="top">Documento:<? $utils->obrig('documento_ck_adv'); ?></td>
    <td align="left"  valign="top"><input type="text" style="width:300px;" name="documento_ck_adv" id="documento_ck_adv" value="<?=$aAltCkAdv['documento_clad'];?>" maxlength="70"></td>
  </tr>
  <tr>
    <td align="right" valign="top">Entidades:<? $utils->obrig('entidades_ck_adv'); ?></td>
    <td align="left"  valign="top"><input type="text" style="width:300px;" name="entidades_ck_adv" id="entidades_ck_adv" value="<?=$aAltCkAdv['entidade_clad'];?>" maxlength="70"></td>
  </tr>
  <tr>
    <td align="right" valign="top">Observação do Advogado:<? $utils->obrig('obs_ck_adv'); ?></td>
    <td align="left"  valign="top"><textarea style="width:200px; height:30px;" name="obs_ck_adv" id="obs_ck_adv"><?=$aAltCkAdv["obsadvogado_clad"];?></textarea></td>
  </tr>
</table>

<div style="text-align:right; margin:10px 0px;">
	<? if($acaoProposta=='altCkAdv'){ ?>
		<img src="images/buttons/bt_salvar.gif"    id="bt_save_vend"   alt="Salvar Item" class="im" onClick="saveCkAdv('<?=$crypt->encrypt('saveCkAdv');?>');" />
	<? }else{ ?>
		<img src="images/buttons/bt_adicionar.gif" id="bt_add_vend" alt="Adicionar Item" class="im" onClick="addCkAdv('<?=$crypt->encrypt('addCkAdv');?>');" />
	<? } ?>
	<img src="images/buttons/bt_cancelar.gif"  id="bt_cancel_socio" alt="Cancelar" class="im" onClick="cancelFormAddCkAdv();" />
</div>