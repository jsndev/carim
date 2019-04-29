
<?php
include "./class/dbclasses.class.php";

$venc=$_GET['venc'];
$val=$_GET['val'];
$typ=$_GET['typ'];
$a=$_GET['a'];

  function formataD($data) {
		$dataTmp = "";
		if($data) {
		  $dataArray = split('[-\/\ ]',$data);
			$dataTmp = $dataArray[2].'/'.$dataArray[1].'/'.$dataArray[0];
		}
		return $dataTmp;
  }
  function formataF($valor,$desc=2) {
    // round
    $valor = str_replace('.',',',strval(round($valor,$desc)));
    $partes = split(',',$valor);
    $inteiro = '';
    $c = -1;
    for($i=strlen($partes[0]); $i >=0; $i--){
      if($c==3){ $inteiro = '.'.$inteiro; $c=0; }
      $inteiro = substr($partes[0],$i,1).$inteiro;
      $c++;
    }
    
    $output = $inteiro;
    
    if($desc > 0){
	    $fracao = substr($partes[1],0,$desc);
	    for($i=strlen($fracao); $i< $desc; $i++){
	      $fracao.='0';
	    }
	    $output .= ','.$fracao;
    }
    
    return $output;
  }

  function formataM($valor) {
    return formataF($valor,0);
  }
function formataMat($matricula) {
		$rgTmp = "";
		if ($matricula) {
		  $matricula = preg_replace("/\W/i","",$matricula);
		  $tam = strlen($matricula) - 1;
		  $tres=-1;
      for($i = $tam; $i >= 0; $i--){
        if($i==($tam-1)){ $rgTmp = '-'.$rgTmp; }
        if(($tres % 3 == 0)&&($tres > 0)){ $rgTmp = '.'.$rgTmp; }
        $rgTmp = substr($matricula, $i, 1).$rgTmp;
        $tres++;
      }
		}
		return $rgTmp;
  }
function nomeMes($mess)
{
	if($mess=='1')
	{
		return 'janeiro';
	}
	if($mess=='2')
	{
		return 'fevereiro';
	}
	if($mess=='3')
	{
		return 'março';
	}
	if($mess=='4')
	{
		return 'abril';
	}
	if($mess=='5')
	{
		return 'maio';
	}
	if($mess=='6')
	{
		return 'junho';
	}
	if($mess=='7')
	{
		return 'julho';
	}
	if($mess=='8')
	{
		return 'agosto';
	}
	if($mess=='9')
	{
		return 'setembro';
	}
	if($mess=='10')
	{
		return 'outubro';
	}
	if($mess=='11')
	{
		return 'novembro';
	}
	if($mess=='12')
	{
		return 'dezembro';
	}
	
}

if($typ == "pp")
{
	if(date('m')==1)
	{
		$mes='12';
		$ano=date('Y')-1;
	}else{
		$mes=date('m')-1;
		$ano=date('Y');
	}
	$db->query="Select * from faturamento where mes='".$mes."' and ano='".$ano."'";
	$db->query();
	if($db->qrcount>0)
	{
		$num_propostas=$db->qrdata[0]['NUMPROPOSTAS'];
		$valor_cobrado=$db->qrdata[0]['VLFATURAMENTO'];
	}
	if($db->qrcount<=0)
	{
			$num_propostas=$a;
			$valor_cobrado=($num_propostas*626).".00";
			$insert="Insert into faturamento (MES, ANO, NUMPROPOSTAS, VLFATURAMENTO) values ('".$mes."','".$ano."','".$num_propostas."','".$valor_cobrado."')";
			mysql_query($insert);
	}
	$dadosboleto["demonstrativo1"] = "Pagamento referente a taxa de Prestação de Serviço de Contratação de ".$num_propostas." propostas em ".nomeMes($mes)." de ".date('Y');
	$tipo='A';
	$csll=round(0.00*$valor_cobrado,2);
	$pis=round(0.00*$valor_cobrado,2);
	$cofins=round(0.00*$valor_cobrado,2);
	$ir=round(0.00*$valor_cobrado,2);
	$valor_cobrado=($valor_cobrado-($csll+$pis+$cofins+$ir));

}
if($typ == "sv")
{
	$valor_cobrado = $val; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
	$dadosboleto["demonstrativo1"] = "Pagamento referente a taxa de Prestação de Serviço de Avaliação";
}


$nome="Caixa de Previd&ecirc;ncia dos Funcion&aacute;rios do Banco do Brasil - PREVI<br>Praia de Botafogo, 501, 3&deg; e 4&deg; andares - Botafogo<br>Rio de Janeiro - RJ";
$logr="Avenida";
$ender="Brasil";
$num="1200";	
$valor = $entra["valor"];

$db->query="INSERT INTO `log_boleto` (
			`id` ,
			`cod_usuario` ,
			`cod_proposta`,
			`tipo_boleto`,
			`valor` ,
			`horario`
			)
			VALUES (
			NULL , '$cod_usua', '$num_proposta', '$id', '$valor_cobrado',
			CURRENT_TIMESTAMP
			);";
$db->query();

//echo $db->query;

$db->query=" SELECT `id` FROM `log_boleto` ORDER BY `id` DESC LIMIT 1";
$db->query();
//echo $db->query;
$id_inserida = $db->qrdata[0]['id'];

$db->query="Select nosso_num from nossonumero";
$db->query();
if($db->qrcount>0)
{
	$atual_nn=$db->qrdata[0]['nosso_num'];
	$novo_nn=$atual_nn+1;
	$query="update nossonumero set nosso_num='".$novo_nn."'";
	mysql_query($query);
}
$num_proposta = $novo_nn;

// +----------------------------------------------------------------------+
// | BoletoPhp - Versão Beta                                              |
// +----------------------------------------------------------------------+
// | Este arquivo está disponível sob a Licença GPL disponível pela Web   |
// | em http://pt.wikipedia.org/wiki/GNU_General_Public_License           |
// | Você deve ter recebido uma cópia da GNU Public License junto com     |
// | esse pacote; se não, escreva para:                                   |
// |                                                                      |
// | Free Software Foundation, Inc.                                       |
// | 59 Temple Place - Suite 330                                          |
// | Boston, MA 02111-1307, USA.                                          |
// +----------------------------------------------------------------------+

// +----------------------------------------------------------------------+
// | Originado do Projeto BBBoletoFree que tiveram colaborações de Daniel |
// | William Schultz e Leandro Maniezo que por sua vez foi derivado do	  |
// | PHPBoleto de João Prado Maia e Pablo Martins F. Costa				        |
// | 														                                   			  |
// | Se vc quer colaborar, nos ajude a desenvolver p/ os demais bancos :-)|
// | Acesse o site do Projeto BoletoPhp: www.boletophp.com.br             |
// +----------------------------------------------------------------------+

// +--------------------------------------------------------------------------------------------------------+
// | Equipe Coordenação Projeto BoletoPhp: <boletophp@boletophp.com.br>              		             				|
// | Desenvolvimento Boleto Banco do Brasil: Daniel William Schultz / Leandro Maniezo / Rogério Dias Pereira|
// +--------------------------------------------------------------------------------------------------------+


// ------------------------- DADOS DINÂMICOS DO SEU CLIENTE PARA A GERAÇÃO DO BOLETO (FIXO OU VIA GET) -------------------- //
// Os valores abaixo podem ser colocados manualmente ou ajustados p/ formulário c/ POST, GET ou de BD (MySql,Postgre,etc)	//





// DADOS DO BOLETO PARA O SEU CLIENTE
$dias_de_prazo_para_pagamento = $venc;
$taxa_boleto = 0;
$data_venc = date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006"; 
//$valor_cobrado = "1,00"; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
$valor_cobrado = str_replace(",", ".",$valor_cobrado);
$valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');

$dadosboleto["nosso_numero"] = $num_proposta;
$dadosboleto["numero_documento"] = $id_inserida;	// Num do pedido ou nosso numero
$dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto
$dadosboleto["data_documento"] = date("d/m/Y"); // Data de emissão do Boleto
$dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
$dadosboleto["valor_boleto"] = $valor_cobrado; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula

// DADOS DO SEU CLIENTE
$dadosboleto["sacado"] = $nome;
//$dadosboleto["endereco1"] = "Rua ABC";
//$dadosboleto["endereco2"] = "São Paulo - SP - CEP: 010200-000";

// INFORMACOES PARA O CLIENTE
// foi pra cima $dadosboleto["demonstrativo1"] = "Pagamento referente a taxa de Avaliação";
//$dadosboleto["demonstrativo2"] = "Mensalidade referente a nonon nonooon nononon<br>Taxa bancária - R$ ".$taxa_boleto;
//$dadosboleto["demonstrativo3"] = "BoletoPhp - http://www.boletophp.com.br";
//$dadosboleto["instrucoes1"] = "- Sr. Caixa, cobrar multa de 2% após o vencimento";
$dadosboleto["instrucoes2"] = "- N&atilde;o receber ap&oacute;s o vencimento<br>";
$dadosboleto["instrucoes3"] = "- Em caso de d&uacute;vidas entre em contato conosco: previ@athosgestao.com.br ou 11 30687077";
$dadosboleto["instrucoes4"] = "- Emitido pelo sistema Contrathos - www.contrathos.athosgestao.com.br/carim";


//
//
// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["quantidade"] = "10";
$dadosboleto["valor_unitario"] = "10";
$dadosboleto["aceite"] = "N";		
$dadosboleto["uso_banco"] = ""; 	
$dadosboleto["especie"] = "R$";
$dadosboleto["especie_doc"] = "DM";


// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //


// DADOS DA SUA CONTA - BANCO DO BRASIL
$dadosboleto["agencia"] = "1203"; // Num da agencia, sem digito
$dadosboleto["conta"] = "16267"; 	// Num da conta, sem digito

// DADOS PERSONALIZADOS - BANCO DO BRASIL
$dadosboleto["convenio"] = "1380659";  // Num do convênio - REGRA: 6 ou 7 ou 8 dígitos
$dadosboleto["contrato"] = "17813216"; // Num do seu contrato
$dadosboleto["carteira"] = "18";
//$dadosboleto["variacao_carteira"] = "-019";  // Variação da Carteira, com traço (opcional)

// TIPO DO BOLETO
$dadosboleto["formatacao_convenio"] = "7"; // REGRA: 8 p/ Convênio c/ 8 dígitos, 7 p/ Convênio c/ 7 dígitos, ou 6 se Convênio c/ 6 dígitos
//$dadosboleto["formatacao_nosso_numero"] = "2"; // REGRA: Usado apenas p/ Convênio c/ 6 dígitos: informe 1 se for NossoNúmero de até 5 dígitos ou 2 para opção de até 17 dígitos

/*
#################################################
DESENVOLVIDO PARA CARTEIRA 18

- Carteira 18 com Convenio de 8 digitos
  Nosso número: pode ser até 9 dígitos

- Carteira 18 com Convenio de 7 digitos
  Nosso número: pode ser até 10 dígitos

- Carteira 18 com Convenio de 6 digitos
  Nosso número:
  de 1 a 99999 para opção de até 5 dígitos
  de 1 a 99999999999999999 para opção de até 17 dígitos

#################################################
*/


// SEUS DADOS
//$dadosboleto["identificacao"] = "BoletoPhp - Código Aberto de Sistema de Boletos";
//$dadosboleto["cpf_cnpj"] = "";
//$dadosboleto["endereco"] = "Rua Amália de Noronha, 159";
//$dadosboleto["cidade_uf"] = "Curitiba - PR";
$dadosboleto["cedente"] = "Athos Gest&atilde;o e Servi&ccedil;os.";

// NÃO ALTERAR!
include("include/funcoes_bb.php"); 
include("include/layout_bb.php");/*
if($typ=='pp'){
$query="Select * from boletosprevi where nossonum='".$nossonumero."' and tipo='".$typ."'";
	$result=mysql_query($query);
	$linhas=mysql_num_rows($result);
	$reg=mysql_fetch_array($result, MYSQL_ASSOC);
	$valorbr=round($valor_cobrado+$csll+$cofins+$pis+$ir,2);
	
	if($linhas<=0)
	{
		$query="Insert into boletosprevi (NOSSONUM, DTEMISSAO, DTVENC, VALOR, TIPO, CSLL, PIS, COFINS, IR, VLBRUTO) values ('".$nossonumero."','".$dadosboleto["data_documento"]."','".$data_venc."','".$valor_boleto."','".$typ."','".$csll."','".$pis."','".$cofins."','".$ir."','".$valorbr."')";
		//echo $query;
		mysql_query($query);
	}
}
if($typ=='sv')
{
		$query="Insert into boletosprevi (NOSSONUM, DTEMISSAO, DTVENC, VALOR, TIPO) values ('".$nossonumero."','".$dadosboleto["data_documento"]."','".$data_venc."','".$valor_boleto."','".$typ."')";
		mysql_query($query);
}
if($typ=='sv')$bol='dois';
else $bol='um';
*/
?>
<html>
<head>
<body vlink="#FFFFFF" alink="#FFFFFF">
<p align="center">
<a href="financeiro.php?tarefas=gb&bol=<?php echo $bol;?>#boletos"><img src="images/buttons/ret_financeiro.gif"></a>
&nbsp;&nbsp;&nbsp;
<a target="_blank" href="imprimir_boleto.php?venc=<?php echo $venc;?>&val=<?php echo $val;?>&typ=<?php echo $typ;?>"><img src="images/buttons/imp_boleto.gif"></a>
</body>
</head>
</p>
</html>
