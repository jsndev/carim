<?
$gmtDate = gmdate("D, d M Y H:i:s");
header("Expires: {$gmtDate} GMT");
header("Last-Modified: {$gmtDate} GMT");
header("Content-type: text/html; charset=iso-8859-1");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache"); 

include "./class/dbclasses.class.php";

$query = "SELECT cod_municipio, municipio FROM ibge WHERE uf='".$_GET['id']."'";

?>
	   		  <select name="municipio_fgts" onChange="">
				<option value="0">-Selecione-</option><?php
			$result =mysql_query($query);
			if (mysql_num_rows($result) > 0)
			{
				while($linhas = mysql_fetch_array($result, MYSQL_ASSOC))
				{
						$selected='';
						if($aAltPpnt["fgts"][0]["municipio_fgts"]==$linhas[municipio]){$aAltPpnt["fgts"][0]["codmunicipio_fgts"]=$linhas[cod_municipio];$selected="selected";}?>
						<option <?php echo $selected;?> value="<?php echo $linhas[municipio] ?>"><?php echo $linhas[municipio]?></option><?php
						$reg++;
				}
			}?>
			</select>