<?
	if($cLOGIN->iLEVEL_USUA == TPUSER_JURIDICO && $aProposta["situacao_ppst"]==10){
?>
<div class="bloco_include">
	<a name="envioremessa"></a>
	<div class="bloco_titulo">Parecer Final</div>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
			<table cellpadding=0 cellspacing=5 border=0>
	        <tr>
	        	<td align="left"  valign="top"><textarea style="width:500px; height:60px;" name="txtparecer" id="txtparecer"></textarea></td>
	          <td align="left"  valign="bottom"><img name="btConcluir" id="btConcluir" src="images/buttons/bt_concluir.gif" alt="Concluir" class="im" onClick="parecerFinal('<?=$crypt->encrypt('parecerFinal');?>');" /></td>
	        </tr>
      </table>
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
	</div>
</div>
<? } ?>