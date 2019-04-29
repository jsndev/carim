<?
	if(FLG_PREVI && $aProposta["situacao_ppst"]>=9){
?>
<div class="bloco_include">
	<a name="envioremessa"></a>
	<div class="bloco_titulo">Data de envio do Contrato e Matricula original à Previ</div>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
			<? if($cLOGIN->iLEVEL_USUA == TPUSER_JURIDICO && $aProposta["situacao_ppst"]==9){ ?>
				<input type="hidden" name="dtaprovacao_ppst" id="dtaprovacao_ppst" value="<?=$utils->formataDataBRA($aProposta["dtaprovacao_ppst"]);?>" />
				<table cellpadding=0 cellspacing=5 border=0>
		        <tr>
		          <td align="right" valign="top">Data de Confirma&ccedil;&atilde;o Registro:<? $utils->obrig('prop_remessa'); ?></td>
		          <td align="left"  valign="top"><input type="text" style="width:80px;" name="dtokregistro_ppst" id="dtokregistro_ppst" value="" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraData(this,event);" maxlength="10"></td>
		        </tr>
		        <tr>
		          <td align="right" valign="top">Nome do Cartório:<? $utils->obrig('nomecartrgi_imov'); ?></td>
		          <td align="left"  valign="top"><input type="text" style="width:300px;" name="nomecartrgi_imov" id="nomecartrgi_imov" value="" maxlength="70"></td>
		        </tr>
		        <tr>
		          <td align="right" valign="top">Número de Matrícula:<? $utils->obrig('nrmatrgi_imov'); ?></td>
		          <td align="left"  valign="top"><input type="text" style="width:100px;" name="nrmatrgi_imov" id="nrmatrgi_imov" value="<?=$aProposta["imovel"]["nrmatrgi_imov"];?>" maxlength="10"></td>
		        </tr>
		        <tr>
		          <td align="right" valign="top">Número do Livro:<? $utils->obrig('nrlivrgi_imov'); ?></td>
		          <td align="left"  valign="top"><input type="text" style="width:100px;" name="nrlivrgi_imov" id="nrlivrgi_imov" value="" maxlength="10"></td>
		        </tr>
		        <tr>
		          <td align="right" valign="top">Número da Folha:<? $utils->obrig('nrfolhrgi_imov'); ?></td>
		          <td align="left"  valign="top"><input type="text" style="width:100px;" name="nrfolhrgi_imov" id="nrfolhrgi_imov" value="" maxlength="10"></td>
		        </tr>
		        <tr>
		          <td align="right" valign="top">Número do Registro de Compra e Venda:<? $utils->obrig('nrrgcompvend_imov'); ?></td>
		          <td align="left"  valign="top"><input type="text" style="width:100px;" name="nrrgcompvend_imov" id="nrrgcompvend_imov" value="" maxlength="10"></td>
		        </tr>
		        <tr>
		          <td align="right" valign="top">Número do Registro de Garantia:<? $utils->obrig('nrrggar_imov'); ?></td>
		          <td align="left"  valign="top"><input type="text" style="width:100px;" name="nrrggar_imov" id="nrrggar_imov" value="" maxlength="10"></td>
		        </tr>
		        <tr>
		        	<td></td>
		          <td align="left"  valign="top"><img src="images/buttons/bt_confirmar.gif" alt="Registrar Imóvel" class="im" onClick="registrarImovel('<?=$crypt->encrypt('registrarImovel');?>')" /></td>
		        </tr>
	      </table>
	    <? }else{ ?>
				<table cellpadding=0 cellspacing=5 border=0>
		        <tr>
		          <td align="right" valign="top">Data de Confirma&ccedil;&atilde;o Registro:</td>
		          <td align="left"  valign="top"><b><?=$utils->formataDataBRA($aProposta["dtokregistro_ppst"]);?></b></td>
		        </tr>
		        <tr>
		          <td align="right" valign="top">Nome do Cartório:</td>
		          <td align="left"  valign="top"><b><?=$aProposta["imovel"]["nomecartrgi_imov"];?></b></td>
		        </tr>
		        <tr>
		          <td align="right" valign="top">Número de Matrícula:</td>
		          <td align="left"  valign="top"><b><?=$aProposta["imovel"]["nrmatrgi_imov"];?></b></td>
		        </tr>
		        <tr>
		          <td align="right" valign="top">Número de Livro:</td>
		          <td align="left"  valign="top"><b><?=$aProposta["imovel"]["nrlivrgi_imov"];?></b></td>
		        </tr>
		        <tr>
		          <td align="right" valign="top">Número da Folha:</td>
		          <td align="left"  valign="top"><b><?=$aProposta["imovel"]["nrfolhrgi_imov"];?></b></td>
		        </tr>
		        <tr>
		          <td align="right" valign="top">Número do Registro de Compra e Venda:</td>
		          <td align="left"  valign="top"><b><?=$aProposta["imovel"]["nrrgcompvend_imov"];?></b></td>
		        </tr>
		        <tr>
		          <td align="right" valign="top">Número do Registro de Garantia:</td>
		          <td align="left"  valign="top"><b><?=$aProposta["imovel"]["nrrggar_imov"];?></b></td>
		        </tr>

<?			if($cLOGIN->iLEVEL_USUA == TPUSER_JURIDICO && $aProposta["situacao_ppst"]==10){
?>    		        <tr>
		        	<td></td>
		          <td align="left"  valign="top"><img src="images/buttons/bt_cancelar.gif" alt="Cancelar Registro de Imóvel" class="im" onClick="cancRegImovel('<?=$crypt->encrypt("cancRegImovel");?>');" /></td>
		        </tr>
<?
			}
?>				
		  </table>
			<? } ?>
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
	</div>
</div>
<? } ?>