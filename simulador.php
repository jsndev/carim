<?
$pageTitle = "Simulador";
include "lib/header.inc.php";

$p_valor = ($_POST['valor']=='')?0:$_POST['valor'];
$p_prazo = ($_POST['prazo']=='')?0:$_POST['prazo'];
$p_taxa  = $oParametros->getTaxaJuros();

$p_valor = floatval(str_replace(',','.',str_replace('.','',$p_valor)));
$p_prazo = intval($p_prazo);
$p_taxa  = floatval(str_replace(',','.',str_replace('.','',$p_taxa)));

$p_valor_f  = $utils->formataMoeda($p_valor);
$p_prazo_f  = $p_prazo;
$p_taxa_f   = $utils->formataFloat($p_taxa,2);

function fPMT($t,$p,$v){
	$a = $t + 1;
	$x = 1 - (1 / (pow($a,$p)) );
	return ( $v * $t / $x);
}

$f_prest_lbl = '&nbsp;';
$f_prest_val = '&nbsp;';
$f_reduc_lbl = '&nbsp;';
$f_reduc_val = '&nbsp;';

if($_POST['tipo_simulador']){
	$valor = $p_valor;
	$prazo = $p_prazo;
	if( $p_taxa <= 0 ){
		$errMessage = "Taxa de Juros deve ser maior que zero";
	} else {
		$taxa = pow( (( $p_taxa / 100 ) + 1), (1 / 12)) - 1;
		switch($_POST['tipo_simulador']){
			case '1':
					$nomeTabela = "Price";
    			$prestacao = fPMT($taxa,$prazo,$valor);
    			$f_prestacao = $utils->formataMoeda($prestacao);
    			$f_prest_lbl = 'Valor da Prestação Inicial (R$):';
    			$f_prest_val = $f_prestacao;
			break;
			case '2':
					$nomeTabela = "SAC";
    			$amort = $valor / $prazo;
    			$juros = ($valor - $amort) * $taxa;
    			$prestacao = $juros + $amort;
    			$redMJ = $juros / $prazo;
    			$f_prestacao = $utils->formataMoeda($prestacao);
    			$f_redMJ     = $utils->formataMoeda($redMJ);
    			$f_prest_lbl = 'Valor da Prestação Inicial (R$):';
    			$f_prest_val = $f_prestacao;
					$f_reduc_lbl = 'Redução Mensal de Juros (R$):';
					$f_reduc_val = $f_redMJ;
			break;
		}
	}
	$cLOGIN->insert_log(3,3,'Simulador - Tabela:'.$nomeTabela." / Valor:".$valor." / Prazo:".$prazo." / Juros:".$p_taxa);
}

$obrig = '<span class="obrig"> *</span>';
?>
<script language="JavaScript" src="./js/diversos.js"></script>
<script language="JavaScript" src="./js/simulador.js"></script>

<form name="simulador" method="post" action="<?=$php_self;?>"onSubmit="return validaForm();">
<div class="quadroInterno">
	<div><img src="images/layout/subquadro_t.gif" alt=" " /></div>
	<div class="quadroInternoMeio">
<?
if ($errMessage) {
?>
		<p class="warning" style="clear:both;"><? echo $errMessage; ?></p>
<?
}
?>
		<div style="float: left; width:400px;">
		<table cellpadding=0 cellspacing=5 border=0>
        <tr>
          <td align="right" width="150">Tabela:<?=$obrig;?></td>
          <td align="left">
            <b>
            <input class="rd" type="radio" name="tipo_simulador" onClick="atualizaValFinan();" value="1" <?=($_POST['tipo_simulador']=='1')?'checked':'';?> > Price &nbsp;&nbsp; 
            <input class="rd" type="radio" name="tipo_simulador" onClick="atualizaValFinan();" value="2" <?=($_POST['tipo_simulador']=='2')?'checked':'';?> > SAC<br>
            </b>
          </td>
        </tr>
        <tr>
          <td align="right">Valor do Financiamento (R$):<?=$obrig;?></td>
          <td align="left">
            <input type="text" name="valor" id="valor" style="width:150px;" value="<?=$p_valor_f;?>" maxlength="20"
            onKeyDown="return teclasFloat(this,event);" onKeyUp="return mascaraMoeda(this,event,'atualizaValFinan()');" onFocus="this.select();">
          </td>
        </tr>
        <tr>
          <td align="right">Prazo (em meses):<?=$obrig;?></td>
          <td align="left">
            <input type="text" name="prazo" id="prazo" style="width:40px;" value="<?=$p_prazo;?>" maxlength="3"
            onKeyDown="return teclasInt(this,event);" onKeyUp="return mascaraInt(this,event,'atualizaValFinan()');" onFocus="this.select();">
          </td>
        </tr>
        <tr>
          <td align="right">Taxa de Juros ao Ano (%):</td>
          <td align="left"><b><?=$p_taxa_f;?></b></td>
        </tr>
        <tr>
          <td align="right" id="prest_lbl"><?=$f_prest_lbl;?></td>
          <td align="left"  id="prest_val"><b><?=$f_prest_val;?></b></td>
        </tr>
        <tr>
          <td align="right" id="reduc_lbl"><?=$f_reduc_lbl;?></td>
          <td align="left"  id="reduc_val"><b><?=$f_reduc_val;?></b></td>
        </tr>
        <tr>
          <td align="right">&nbsp;</td>
          <td align="left"><input type="image" src="images/buttons/bt_calcular.gif" value="Calcular" class="im" />
         </td>
        </tr>
      </table>
		</div>
	</div>
	<div class="importante" style="clear:both;">
		<b>Importante:</b>
		As operações de crédito estão sujeitas à análise e aprovação.
		Os resultados obtidos nesta página não valem como proposta.
		Sua função é a de facilitar a sua orientação.
		Taxas, prazos e demais condições apresentadas podem ser alteradas sem aviso prévio
	</div>
	<div><img src="images/layout/subquadro_b.gif" alt=" " /></div>
</div>
</form>
<?
include "lib/footer.inc.php";
?>