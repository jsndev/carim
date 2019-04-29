<?php
		if($_POST['teste2']==''){
			$db->query="Select infoadicionais_ppst from proposta where cod_ppst='".$aProposta["cod_ppst"]."'";
			$db->query();
			if($db->qrcount>0)
			{
				$_POST['teste2']=$db->qrdata[0]['infoadicionais_ppst'];
				$infoad=$db->qrdata[0]['infoadicionais_ppst'];
			}
		}
		if($_POST['info']=='salvar'){
			$db->query="Update proposta set infoadicionais_ppst='".$_POST['teste2']."' where cod_ppst='".$aProposta["cod_ppst"]."'";
			$db->query();
			$db->query="Insert into historico (COD_PPST, DT_HIST, OBS_HIST, TIPO_HIST, COD_USUA) values ('".$aProposta["cod_ppst"]."',now(),'Informações Adicionais inseridas.','1','".$cLOGIN->iID."')";
			$db->query();
		}
		?>
		<script>
		function SalvarInformacao()
		{
			document.getElementById('info').value="salvar"
			return true;
		}
		</script>
<?php 
if($cLOGIN->iLEVEL_USUA == TPUSER_JURIDICO && ($aProposta["situacao_ppst"]>7 && $aProposta["situacao_ppst"]<=9)){ 
?>		
		<br>
		<div class="bloco_include" id="bloco_pagamento">
			<div class="bloco_titulo">Em Tempo</div>
			<div class="quadroInterno">
				<div><img src="images/layout/subquadro_t.gif" alt="" /></div>
				<div class="quadroInternoMeio">
		
		<?php 
		include("fckeditor.php") ;
		?>
		<?php
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
					<p align="right">
					<input type="image" name="SvQImov" id="SvQImov" class="im" src="images/buttons/bt_salvar.gif" onClick="return SalvarInformacao();" >
					<input type="hidden" name="info" id="info" value="">
					<?php if($_POST['teste2']!=''){
					?>
					&nbsp;&nbsp;&nbsp;
					<a target="_blank" href="fpdf2/contrato.php?cod_proposta=<?php echo $aProposta["cod_ppst"];?>"><img src="images/buttons/bt_gerar_contrato.gif"></a>
					<?php
					}
					?></p>
				</div>
				<div><img src="images/layout/subquadro_b.gif" alt="" /></div>
			</div>
		</div>
<?php
}elseif($aProposta["situacao_ppst"]>9)
{?>
		<br>
		<div class="bloco_include" id="bloco_pagamento">
			<div class="bloco_titulo">Em Tempo</div>
			<div class="quadroInterno">
				<div><img src="images/layout/subquadro_t.gif" alt="" /></div>
				<div class="quadroInternoMeio">
					<?php 
					echo $infoad;
					?>
				</div>
				<div><img src="images/layout/subquadro_b.gif" alt="" /></div>
			</div>
		</div>
 
<?php
}
?>
