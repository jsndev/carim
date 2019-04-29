<script language="JavaScript" type="text/javascript" src="./js/proposta_bl_advogado.js"></script>
<? if(count($aProposta["checklistadvogado"])>0 || $cLOGIN->iLEVEL_USUA == TPUSER_JURIDICO){ // ou advogado ?>
<a name="advogado"></a>
<div class="bloco_include">
	<div class="bloco_titulo">Ítens adicionais exigidos pelo Advogado</div>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio" id="ckls_advg">
	  	<? include('bl_ckls_advogado.inc.php'); ?>
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
	</div>
</div>
<? } ?>