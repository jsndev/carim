<?php

$iREQ_AUT=1;
$aUSERS_PERM[]=4;

$pageTitle = "Relatórios";
include "lib/header.inc.php";

?>
	<div class="quadroInterno">
		<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
		<div class="quadroInternoMeio">
        
    <table width="254" border=0 cellpadding=0 align="center" cellspacing=5>
      <tr>
			<td colspan="2" align="center"><b>Tipos de Relatório</b></td>
		</tr>
     	<tr>
			<td colspan="2" align="center"></td>
		</tr>
	  <tr>
            <td width="119" align="center"><a target="_blank" href="relatorio/relatorio.php?tipo=1"><u><b>PARCIAL</b></u></a></td>
            <td width="120" align="center"><a target="_blank" href="relatorio/relatorio.php?tipo=2"><u><b>FINAL</b></u></a></td>
          </tr>
        </table>
		</div>
		<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
	</div>

<?php
include "lib/footer.inc.php";
?>