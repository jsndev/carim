<?php
//$dir = getcwd();
//echo $dir;
require_once "pdf/html2fpdf.php";
//require_once "class/dbclasses.class.php";
//require_once "class/db.class.php";
$cod_usuario=$_GET['cod_usuario'];
$tipo=$_GET['tipo'];

############################CONECTA NO BANCO DE DADOS#############################################

$BD_SERVIDOR = "localhost";
$BD_NOME	= "carim"; //banco de dados
$BD_USUARIO	= "sistema";
$BD_SENHA	= "for/17!kc";
 mysql_connect($BD_SERVIDOR,$BD_USUARIO,$BD_SENHA) or die("ERRO: conexão não realizada");
	mysql_select_db($BD_NOME) or die("ERRO: erro ao selecionar o banco de dados: ". mysql_error());
	
##################################################################################################
############################# FUNÇÕES EXTRAS #####################################################
#____________________________________ Formata CPF _________________________________________
	function formataCPF($cpf) {
		$cpfTmp = "";
		if ($cpf) {
		  $cpf = preg_replace("/\D/i","",$cpf);
      for($i=0; $i < strlen($cpf); $i++){
        if($i==3){ $cpfTmp .= '.'; }
        if($i==6){ $cpfTmp .= '.'; }
        if($i==9){ $cpfTmp .= '-'; }
        $cpfTmp .= substr($cpf, $i, 1);
      }
		}
		return $cpfTmp;
	}
#____________________________________ Formata CNPJ _________________________________________
	function formataCnpj($cnpj) {
		$cnpjRet = "";
		if ($cnpj) {
			$cnpjRet = substr($cnpj, -14, 2).".".substr($cnpj, -12, 3).".".substr($cnpj, -9, 3)."/".substr($cnpj, -6, 4)."-".substr($cnpj, -2, 2);
		}
		return $cnpjRet;
	}
#____________________________________ Formata Data Banco de Dados _________________________________________

	function formataData($data) {
		$dataTmp = "";
		if ($data) {
		  $dataArray = split('[-\/]',$data);
			$dataTmp = $dataArray[2].'-'.$dataArray[1].'-'.$dataArray[0];
		}
		return $dataTmp;
	}
#____________________________________ Formata Data Brasil _________________________________________
  function formataDataBRA($data) {
		$dataTmp = "";
		if($data) {
		  $dataArray = split('[-\/\ ]',$data);
			$dataTmp = $dataArray[2].'/'.$dataArray[1].'/'.$dataArray[0];
		}
		return $dataTmp;
  }
#____________________________________ Formata CEP _________________________________________
	function formataCep($cep) {
		$cepTmp = "";
		if ($cep) {
			if (strlen($cep) == 5) {
				$cep = str_pad($cep, 8, "0", STR_PAD_RIGHT);
			} elseif (strlen($cep) < 8) {
				$cep = str_pad($cep, 8, "0", STR_PAD_LEFT);
			}
			$cepTmp = substr($cep, 0, 5)."-".substr($cep, 5, 3);
		}
		return $cepTmp;
	}
#____________________________________ Formata Moeda _________________________________________
  function formataFloat($valor,$desc=0) {
    // round
    $valor = str_replace('.',',',strval(round($valor,2)));
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

  function formataMoeda($valor) {
    return formataFloat($valor,2);
  }
#____________________________________ Data por Extenso __________________________________________
function data_extenso()
{
	$dia = date('d');
	$mes = date('m');
	$ano = date('Y');
	$semana = date('w');
	
	
	// configuração mes 
	
	switch ($mes){
	
	case 1: $mes = "janeiro"; break;
	case 2: $mes = "fevereiro"; break;
	case 3: $mes = "março"; break;
	case 4: $mes = "abril"; break;
	case 5: $mes = "maio"; break;
	case 6: $mes = "junho"; break;
	case 7: $mes = "julho"; break;
	case 8: $mes = "agosto"; break;
	case 9: $mes = "setembro"; break;
	case 10: $mes = "outubro"; break;
	case 11: $mes = "novembro"; break;
	case 12: $mes = "dezembro"; break;
	
	}
	
	
	// configuração semana 
	
	switch ($semana) {
	
	case 0: $semana = "DOMINGO"; break;
	case 1: $semana = "SEGUNDA FEIRA"; break;
	case 2: $semana = "TERÇA-FEIRA"; break;
	case 3: $semana = "QUARTA-FEIRA"; break;
	case 4: $semana = "QUINTA-FEIRA"; break;
	case 5: $semana = "SEXTA-FEIRA"; break;
	case 6: $semana = "SÁBADO"; break;
	
	}
	
	$dt_ext= $dia." de ".$mes." de ".$ano;//Agora basta imprimir na tela...
	return $dt_ext;
}  	
#____________________________________ Número por extenso _________________________________________
function extenso($valor=0) {
	$singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
	$plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões",
"quatrilhões");

	$c = array("", "cem", "duzentos", "trezentos", "quatrocentos",
"quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
	$d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta",
"sessenta", "setenta", "oitenta", "noventa");
	$d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze",
"dezesseis", "dezesete", "dezoito", "dezenove");
	$u = array("", "um", "dois", "três", "quatro", "cinco", "seis",
"sete", "oito", "nove");

	$z=0;

	$valor = number_format($valor, 2, ".", ".");
	$inteiro = explode(".", $valor);
	for($i=0;$i<count($inteiro);$i++)
		for($ii=strlen($inteiro[$i]);$ii<3;$ii++)
			$inteiro[$i] = "0".$inteiro[$i];

	// $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
	$fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);
	for ($i=0;$i<count($inteiro);$i++) {
		$valor = $inteiro[$i];
		$rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
		$rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
		$ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";
	
		$r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd &&
$ru) ? " e " : "").$ru;
		$t = count($inteiro)-1-$i;
		$r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
		if ($valor == "000")$z++; elseif ($z > 0) $z--;
		if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : "").$plural[$t]; 
		if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) &&
($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
	}

	return($rt ? $rt : "zero");
}
#________________________________ Letras Maiusculas ____________________________________________
function maiusculo($string) 
{
	$string = strtoupper ($string);
	$string = str_replace ("â", "Â", $string);
	$string = str_replace ("á", "Á", $string);
	$string = str_replace ("ã", "Ã", $string);
	$string = str_replace ("à", "A", $string);
	$string = str_replace ("ê", "Ê", $string);
	$string = str_replace ("é", "É", $string);
	$string = str_replace ("Î", "I", $string);
	$string = str_replace ("í", "Í", $string);
	$string = str_replace ("ó", "Ó", $string);
	$string = str_replace ("õ", "Õ", $string);
	$string = str_replace ("ô", "Ô", $string);
	$string = str_replace ("ú", "Ú", $string);
	$string = str_replace ("Û", "U", $string);
	$string = str_replace ("ç", "Ç", $string);
	return $string;
}
#____________________________________ Vencimento  parcelas _______________________________________

$m=date('m');
if($m!=12)
{
	$ano=date("Y");
	$dia=date("d");
	$mes=$m+1;
	$dtvenc=$dia."/".$mes."/".$ano;
}else
{
	$ano=date("Y");
	$dia=date("d");
	$mes="01";
	$dtvenc=$dia."/".$mes."/".$ano;
}
function somardata($data, $dias, $meses, $ano)
{
   //passe a data no formato dd/mm/yyyy 
   $data = explode("/", $data);
   $newData = date("d/m/Y", mktime(0, 0, 0, $data[1] + $meses,
    $data[0] + $dias, $data[2] + $ano) );
   return $newData;
}

####################################################################################################
#################################BUSCAS  NO BANCO DE DADOS  ########################################

#_________________________________ USUARIO __________________________________________________________
	$query = "select 
								a.id_lstn,
								a.nome_usua,
								d.cod_ppst,
								date_format(c.dtaprovacao_imov,'%d/%m/%Y') as dtaprovacao_imov,
								date_format(d.data_ppst,'%d/%m/%Y') as data_ppst,
								date_format(d.dtaprovacao_ppst,'%d/%m/%Y') as dtaprovacao_ppst,
								date_format(d.dtasscontrato_ppst,'%d/%m/%Y') as dtasscontrato_ppst,
								date_format(d.dtokregistro_ppst,'%d/%m/%Y') as dtokregistro_ppst
							from 
								usuario a,
								imovel c,
								proposta d,
								proponente b
							where
								a.cod_usua=b.cod_proponente
							and
								b.cod_ppst=d.cod_ppst
							and
								d.cod_ppst=c.cod_ppst	
							and
								d.situacao_ppst = '11' order by d.cod_ppst";
	$result =mysql_query($query);
	$linhas=mysql_num_rows($result);
	$nome="<table width='728'>
				<tr>
					<td><b><i>C.I.</i></b></td>
					<td><b><i>Participante</i></b></td>
					<td align='center'><b>Entrada</b></td>
					<td align='center'><b><i>Aprovação</i></b></td>
					<td align='center'><b><i>Assinatura</i></b></td>
					<td align='center'><b><i>Registro</i></b></td>
				</tr>
				";
	$a=1;
	$x=0;
	while ($registro = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		$proposta[$a]=$registro['cod_ppst'];
		if($proposta[$a]!=$proposta[$a-1]){
			$nome .="<tr>
					 <td width='90'>".$registro['id_lstn']."</td>
					 <td  width='278'>".$registro['nome_usua']."</td>
					 <td width='90' align='center'>".$registro['data_ppst']."</td>
					 <td width='90' align='center'>".$registro['dtaprovacao_ppst']."</td>
					 <td width='90' align='center'>".$registro['dtasscontrato_ppst']."</td>
					 <td width='90' align='center'>".$registro['dtokregistro_ppst']."</td>
	
					</tr>";
			$hoje=date("d/m/Y");
			$um="01/".date("m/Y");
			if($tipo=='2')
			{
				$update="Update proposta set situacao_ppst='12' where cod_ppst='".$registro['cod_ppst']."'";
				mysql_query($update);
			}
			$x++;
		}
		$a++;
	}
	
	$nome.="</table>";
	$nome.="<br><br><i><b>Total de Propostas Finalizadas:</b></i> ".$x;
	if($tipo=='2'){
		if(date('m')==1)
		{
			$date=12;
		}else
		{
			$date=date('m')-1;
		}
		$query="Select * from faturamento where mes='".$date."' and ano='".date('Y')."'";
		$result=mysql_query($query);
		$fat=mysql_num_rows($result);
		if($fat<=0)
		{
			$vl_fat=$x*626;
			$vl_fat=$vl_fat.'.00';
			$query2="insert into faturamento (MES,ANO,NUMPROPOSTAS,VLFATURAMENTO) values ('".$date."','".date('Y')."','".$x."','".$vl_fat."')";
			mysql_query($query2);
		}
	}
##########################################################################################################
#										SOLICITAÇÃO DE AVALIAÇÃO									
##########################################################################################################		
$m=date('m');
if($m==1)
{
	$mes='Janeiro';
}
if($m==2)
{
	$mes='Fevereiro';
}
if($m==3)
{
	$mes='Março';
}
if($m==4)
{
	$mes='Abril';
}
if($m==5)
{
	$mes='Maio';
}
if($m==6)
{
	$mes='Junho';
}
if($m==7)
{
	$mes='Julho';
}
if($m==8)
{
	$mes='Agosto';
}
if($m==9)
{
	$mes='Setembro';
}
if($m==10)
{
	$mes='Outubro';
}
if($m==11)
{
	$mes='Novembro';
}
if($m==12 || $m==0)
{
	$mes='Dezembro';
}


		$relat=$nome;		
			
		//Instanciation of inherited class
		$pdf=new HTML2FPDF();
		//$pdf=new FPDF();
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',11);	
		$pdf->Cell(0,5,'Relação de Propostas Finalizadas no Mês de '.$mes,0,2,'C');
		$pdf->Ln(10);
		$pdf->WriteHTML($relat);
		
		$pdf->Output();
		
		

?>
