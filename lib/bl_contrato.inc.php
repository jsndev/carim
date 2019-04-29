<?
	if(FLG_PREVI && $aProposta["situacao_ppst"]>=7 && $cLOGIN->iLEVEL_USUA == TPUSER_JURIDICO){
?>
<div class="bloco_include">
	<a name="contrato"></a>
	<div class="bloco_titulo">Emissão de Contrato</div>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt="" /></div>
		<div class="quadroInternoMeio">
    	<img src="images/buttons/bt_gerar_contrato.gif" alt="Gerar Contrato" class="im" onClick="return false;" />
    	<? if($aProposta["situacao_ppst"]==7){ ?>
    	<img name="btConcluir" id="btConcluir" src="images/buttons/bt_concluir.gif" alt="Concluir" class="im" onClick="concluirContrato('<?=$crypt->encrypt('concluir');?>');" />
    	<? } ?>
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt="" /></div>
	</div>
</div>
<? } ?>