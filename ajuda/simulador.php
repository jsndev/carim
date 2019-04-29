<?
header("Content-Type: text/html; charset=iso-8859-1");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Contrathos</title>
	<link href="../css/style.css" rel="stylesheet" type="text/css" />
	<link href="../css/dtree.css" rel="stylesheet" type="text/css" />
</head>

<body >
<table width="100%" border="0" cellpadding="2" cellspacing="2">
<tr>
	<td>
<?
include "extra.php";
$utils   = new utils();
$p_valor='';
$p_prazo='';
$tipo='';
$p_taxa='';

if($_POST){
$p_valor = $_POST['valor'];
$p_prazo = $_POST['prazo'];
$tipo=@$_POST['tipo_simulador'];
$p_taxa  = $_POST['taxa'];

}
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

if($tipo){
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
	//$cLOGIN->insert_log(3,3,'Simulador - Tabela:'.$nomeTabela." / Valor:".$valor." / Prazo:".$prazo." / Juros:".$p_taxa);
}

$obrig = '<span class="obrig"> *</span>';
?>
<script language="JavaScript" src="diversos.js"></script>
<script language="JavaScript" src="simulador.js"></script>

<form name="simulador" method="post" action="" onSubmit="">
		<table cellpadding=0 cellspacing=5 border=0>
        <tr>
          <td align="right">Tabela:<?=$obrig;?></td>
          <td align="left">
            <b>
            <input  type="radio" name="tipo_simulador" onClick="atualizaValFinan();" value="1" <?=($tipo=='1')?'checked':'';?> > Price &nbsp;&nbsp; 
            <input  type="radio" name="tipo_simulador" onClick="atualizaValFinan();" value="2" <?=($tipo=='2')?'checked':'';?> > SAC<br>
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
          <td align="left"><input type="text" name="taxa" id="taxa" style="width:150px;" value="<?=$p_taxa_f;?>" maxlength="10"
            onKeyDown="return teclasFloat(this,event);" onKeyUp="return mascaraJuros(this,event,'atualizaValFinan()');" >
</td>
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
          <td align="left"><input type="submit" name="Calcular" value="Calcular" class="im" />
         </td>
        </tr>
      </table>
</form>
<strong>Orienta&ccedil;&otilde;es:<br>
</strong><br>
<br>
<strong>O que é tabela PRICE</strong> - <em>Sistema Francês de Amortização?</em><br>
<br>
Consiste em um plano de amortização em que as prestações são iguais.<br> 
As amortizações crescem ao longo do período da operação: como a prestação é igual, com a redução do saldo devedor o juro diminui e a parcela de amortização aumenta.<br><br><br>
<strong>O que é tabela SAC</strong> - <em>Sistema de Amortização Constante?<br>
</em><br>
Neste sistema os valores das amortizações são iguais.<br> 
O valor das prestações é decrescente:  sendo as amortizações iguais, com a redução do saldo devedor o juro diminui e o valor da prestação diminui 
<br><br>
<strong>  O que compõe a prestação? 
</strong> <br><br>
A prestação do crédito imobiliário é composta de amortização, juros, seguro dano físico do imóvel, seguro de morte e invalidez permanente e Custos de Administração.
<br><br>
<strong>O que é juros?
</strong><br><br>
É a remuneração da instituição financeira pela operação de credito.
<br><br>
<strong>O que é amortização?
</strong><br><br>
É o componente da prestação que abate mensalmente do valor do saldo devedor.
<br><br>
<strong>O que é seguros?
</strong><br><br>
São prêmios pagos que correspondem  a cobertura para quitação total, ou parcial do saldo devedor por morte, invalidez permanente, ou danos físicos do imóvel.
<br><br>
<strong>O são custos de administração?
</strong><br><br>
Corresponde ao valor pago mensalmente pela administração do contrato, para execução dos serviços que compreendem a operação.
<br><br>
<strong>O que é taxa de juros efetiva e taxa de juros nominal?
</strong><br><br>
Uma taxa de juro é considerada nominal quando o prazo de incorporação de juros não coincide com aquele que a taxa se refere. É comum no dia-a-dia apresentar a taxa nominal, porém para o cálculo dos juros é utilizada a taxa efetiva. Por exemplo, uma taxa de juros nominal de 6 % ao ano, corresponde a uma taxa efetiva de 0,5 % ao mês (= 6/12). Se calcularmos a taxa efetiva anual teremos 6,1678% ao ano (taxa ano = [(1 + 0,5/100) ^ 12 -1] * 100).
<br><br>

</td>
</tr>
</table>
</body>
</html>
