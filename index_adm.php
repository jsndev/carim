<?
$iREQ_AUT=1;
$aUSERS_PERM[]=3;
// incluindo o cabe�alho
$pageTitle = "Home Administrativa";
include "lib/header.inc.php";
?>
<div class="quadroInterno">
	<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
	<div class="quadroInternoMeio">
	  Ol�, <span class="warning"><b><?=$cLOGIN->cUSUARIO;?></b></span><br>
	  <br>
	  Seja bem vindo � PREVI<br>
	  "Finamentiamento Imobili�rio"
	</div>
	<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
<?
// incluindo o rodap�
include "lib/footer.inc.php";
?>