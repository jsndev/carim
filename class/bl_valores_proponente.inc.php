<div style="float:left; width:400px;">
<script language="javascript">
function verBtnSalvarEnt(){
		showDiv('btSalvarEnt');
}
</script>
<?php
foreach($aProposta["proponentes"] as $kPpnt=>$vPpnt){
				if($vPpnt["cod_proponente"] == $_POST["frm_cod_ppnt"]){
					$aAltPpnt = $vPpnt;
				}
			}
?>
	<table cellpadding=0 cellspacing=5 border=0 class="tb_dets_list">
		<colgroup>
			<col width="180" /><col />
		</colgroup>
		<?php if(!FLG_PREVI){ 
			?>
			<tr>
			  <td align="right" valign="top">Composição de Renda:</td><td align="left"><b><?php echo $aProposta["compos_renda_ppnt"];?>%</b></td>
			</tr>
		<?php }else{
		$db->query="Select id_lstn from usuario where cod_usua='".$vPpnt["cod_proponente"]."'";
		$db->query();
		if($db->qrcount>0)
		{
			$id_lstn=$db->qrdata[0]['id_lstn'];
		}
		$db->query="Select vlaprovado, parcaprovada, przaprovado from listadenomes where id_lstn='".$id_lstn."'";
		$db->query();
		if($db->qrcount>0)
		{ ?>
		  <tr>
		    <td align="right" valign="top">Parcela individual da Compra:</td><td align="left"><b>R$ <?php echo $utils->formataMoeda($registroPpnt["vlcompra_ppnt"]);?></b></td>
		  </tr>
		  <?php
		  if(($registroPpnt["vlfinsol_ppnt"]==$registroPpnt["listadenomes"][0]['vlaprovado']) || ($db->qrdata[0]['vlaprovado']==0)){
		   ?>
		  <tr>
		    <td align="right" valign="top">Entrada individual:</td><td align="left"><b>R$ <?php echo $utils->formataMoeda($registroPpnt["vlentrada_ppnt"]);?></b></td>
		  </tr>
		  <?php
		  }else{  ?>
		  <tr>
		    <td align="right" valign="top">Entrada individual (R$):</td><td align="left"><input type="text" style="width:80px;" name="vlentrada_ppnt" id="vlentrada_ppnt" value="<?php echo $utils->formataMoeda($registroPpnt["vlentrada_ppnt"]);?>" onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraMoeda(this,event,'atualizaValoresProposta()',2);" onfocus="verBtnSalvarEnt();" onblur="verBtnSalvarEnt();" maxlength="12" /></td>
			 <td align="left" valign="top" style="padding-left:5px;">
			       	    <img name="btSalvarEnt" id="btSalvarEnt" src="images/buttons/bt_salvar.gif" alt="Aprovar Imóvel"  class="im" onClick="salvarEntrada('<?php echo $crypt->encrypt('salvarEntrada');?>');" style="visibility:hidden;" />
			       	    <?php /*depois de voltar a resposta da previ verificar se é preciso
			       	    exibir botao "cancelar aprovação" que irá limpar o campo da
			       	    "data de aprovação" e deixará o form imovel aberto*/ ?>
			          </td>
		  </tr>
		  <?php 
		  } 
			$query="Select vlfinsol_ppnt from proponente where cod_ppst='".$aProposta["cod_ppst"]."'";
			$result =mysql_query($query);
			$linhas= mysql_num_rows($result);
			$registro = mysql_fetch_array($result, MYSQL_ASSOC);
		 ?>
		  <tr>
		    <td align="right" valign="top">Sinal individual:</td><td align="left"><b>R$ <?php echo $utils->formataMoeda($registroPpnt["vlsinal_ppnt"]);?></b></td>
		  </tr>
		  <?php
		  if($db->qrdata[0]["vlaprovado"]>0){
		  ?>
		  <tr>
		    <td align="right" valign="top">Valor individual do Financiamento:</td><td align="left" style="color:#600;"><b>R$ <?php echo $utils->formataMoeda($registroPpnt["vlfinsol_ppnt"]);?></b></td>
		  </tr>
	  	<?php }else{  ?>
	  	<?php }  ?>
		  <tr>
		    <td align="right" valign="top">Prestaçao:</td><td align="left"><b>R$ <?php echo $utils->formataMoeda($registroPpnt["listadenomes"][0]["parcaprovada"]);?></b></td>
		  </tr>
		  <?php  ?>
		  <tr>
		    <td align="right" valign="top">Prazo:</td><td align="left"><b><?php echo $utils->formataFloat($registroPpnt["listadenomes"][0]["przaprovado"],-1);?> meses</b></td>
		  </tr>
		  <?php } ?>
		<?php } ?>
	</table>
</div>

<?php if(FLG_PREVI){
 ?>
	<div class="warning" id="divResultadoProposta" style="border:1px solid #DDDDDD; background-color: #F5F5F5; padding: 10px 20px; float:right; width:220px; margin:5px 1px;">
		<u><b>Limites</b></u><br />
		Financiamento: <b>R$ <?php echo $utils->formataMoeda($registroPpnt["listadenomes"][0]["vlmaxfinan"]);?></b><br />
		Prestação: <b>R$ <?php echo $utils->formataMoeda($registroPpnt["listadenomes"][0]["parcmaxfinan"]);?></b><br />
		Prazo: <b><?php echo $utils->formataFloat($registroPpnt["listadenomes"][0]["przmaxfinan"]);?> meses</b><br />
		<?php if(true){ 
		$db->query="Select id_lstn from usuario where cod_usua='".$Ppnt["cod_proponente"]."'";
		$db->query();
		if($db->qrcount>0)
		{
			$id_lstn=$db->qrdata[0]['id_lstn'];
		}
		$db->query="Select vlaprovado, parcaprovada, przaprovado, vlsinalaprovado from listadenomes where id_lstn='".$id_lstn."'";
		$db->query();
		if($db->qrcount>0)
		{?>
			<div id='divValoresAprovadosPrevi'>
				<hr class="redHr" />
				<u><b>Valores aprovados pela Previ</b></u><br />
				Financiamento: <b>R$ <?php echo $utils->formataMoeda($registroPpnt["listadenomes"][0]["vlaprovado"]);?></b><br />
				Parcela: <b>R$ <?php echo $utils->formataMoeda($registroPpnt["listadenomes"][0]["parcaprovada"]);?></b><br />
				Prazo: <b><?php echo $utils->formataFloat($registroPpnt["listadenomes"][0]["przaprovado"]);?> meses</b><br />
				Sinal: <b>R$ <?php echo $utils->formataMoeda($registroPpnt["listadenomes"][0]["vlsinalaprovado"]);?></b><br />
			</div>
		<?php } }?>
	</div>
<?php } ?>