<?
$iREQ_AUT=1;
$aUSERS_PERM[]=4;

$pageTitle = "Home Administrativa";
// incluindo o cabeçalho
include "lib/header.inc.php";
?>
<div class="quadroInterno">
	<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
	<div class="quadroInternoMeio">
	  Olá, <span class="warning"><b><?=$cLOGIN->cUSUARIO;?></b></span><br>
	  <br>
	  Seja bem vindo á PREVI<br>
	  "Financiamento Imobiliário"
	</div>
	<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
<?
// incluindo o rodapé
include "lib/footer.inc.php";
?>