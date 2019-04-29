<?php

		if(!$_POST){
			$db->query="Select qualificacao_imov from imovel where cod_ppst='".$aProposta["cod_ppst"]."'";
			$db->query();
			if($db->qrcount>0)
			{
				$_POST['teste']=$db->qrdata[0]['qualificacao_imov'];
				$qualimov=$db->qrdata[0]['qualificacao_imov'];
			}
		}
		if($_POST['qimov']=='salvar'){
			$db->query="Update imovel set qualificacao_imov='".$_POST['teste']."' where cod_ppst='".$aProposta["cod_ppst"]."'";
			$db->query();
			$db->query="Insert into historico (COD_PPST, DT_HIST, OBS_HIST, TIPO_HIST, COD_USUA) values ('".$aProposta["cod_ppst"]."',now(),'Qualificação do Imóvel inseridas.','1','".$cLOGIN->iID."')";
			$db->query();
		}
		?>
		<script>
		function SalvarQualificacao()
		{
			document.getElementById('qimov').value="salvar"
			return true;
		}
		</script>
<?php 
if(1){ 
?>		
		<br>
		<div class="bloco_include" id="bloco_pagamento">
			<div class="bloco_titulo">Qualificação do Imóvel</div>
			<div class="quadroInterno">
				<div><img src="images/layout/subquadro_t.gif" alt="" /></div>
				<div class="quadroInternoMeio">
		
		<?php 
		include("fckeditor.php") ;
		// Automatically calculates the editor base path based on the _samples directory.
		// This is usefull only for these samples. A real application should use something like this:
		// $oFCKeditor->BasePath = '/fckeditor/' ;	// '/fckeditor/' is the default value.
		$sBasePath = $_SERVER['PHP_SELF'] ;
		$sBasePath = substr( $sBasePath, 0, strpos( $sBasePath, "_samples" ) ) ;
		
		$oFCKeditor = new FCKeditor('FCKeditor1') ;
		$oFCKeditor->BasePath	= $sBasePath ;
		//$oFCKeditor->Value		= '' ;
		$oFCKeditor->Create() ;
		?>
					<br>
					<p>&nbsp;&nbsp;<i>Use <b>(m2)</b> para substituir <b>(m<sup>2</sup>)</b></i></p>
					<p align="right">
					<input type="image" name="SvQImov" id="SvQImov" class="im" src="images/buttons/bt_salvar.gif" onClick="return SalvarQualificacao();" ></p>
					<input type="hidden" name="qimov" id="qimov" value="">
				</div>
				<div><img src="images/layout/subquadro_b.gif" alt="" /></div>
			</div>
		</div>
<?php
}elseif($aProposta["dtaprovacao_ppst"]!='' && $aProposta["situacao_ppst"]>=6)
{
			$db->query="Select qualificacao_imov from imovel where cod_ppst='".$aProposta["cod_ppst"]."'";
			$db->query();
			if($db->qrcount>0)
			{
				$qualimov=$db->qrdata[0]['qualificacao_imov'];
			}
?>
		<br>
		<div class="bloco_include" id="bloco_pagamento">
			<div class="bloco_titulo">Qualificação do Imóvel</div>
			<div class="quadroInterno">
				<div><img src="images/layout/subquadro_t.gif" alt="" /></div>
				<div class="quadroInternoMeio">
					<?php 
					echo $qualimov;
					?>
				</div>
				<div><img src="images/layout/subquadro_b.gif" alt="" /></div>
			</div>
		</div>
<?php
}
?>
