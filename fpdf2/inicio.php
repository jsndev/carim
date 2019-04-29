<?php
//$dir = getcwd();
//echo $dir;
require_once "pdf/html2fpdf.php";
//require_once "class/dbclasses.class.php";
//require_once "class/db.class.php";
$cod_usuario=$_GET['cod_usuario'];

############################CONECTA NO BANCO DE DADOS#############################################

$BD_SERVIDOR = "localhost";
$BD_NOME	= "carim"; //banco de dados
$BD_USUARIO	= "root";
$BD_SENHA	= "/c8119H!";
$conexao = mysql_connect($BD_SERVIDOR,$BD_USUARIO,$BD_SENHA) or die("ERRO: conex�o n�o realizada");
	mysql_select_db($BD_NOME) or die("ERRO: erro ao selecionar o banco de dados: ". mysql_error());
	
##################################################################################################
############################# FUN��ES EXTRAS #####################################################
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
	
	
	// configura��o mes 
	
	switch ($mes){
	
	case 1: $mes = "janeiro"; break;
	case 2: $mes = "fevereiro"; break;
	case 3: $mes = "mar�o"; break;
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
	
	
	// configura��o semana 
	
	switch ($semana) {
	
	case 0: $semana = "DOMINGO"; break;
	case 1: $semana = "SEGUNDA FEIRA"; break;
	case 2: $semana = "TER�A-FEIRA"; break;
	case 3: $semana = "QUARTA-FEIRA"; break;
	case 4: $semana = "QUINTA-FEIRA"; break;
	case 5: $semana = "SEXTA-FEIRA"; break;
	case 6: $semana = "S�BADO"; break;
	
	}
	
	$dt_ext= $dia." de ".$mes." de ".$ano;//Agora basta imprimir na tela...
	return $dt_ext;
}  	
#____________________________________ N�mero por extenso _________________________________________
function extenso($valor=0) {
	$singular = array("centavo", "real", "mil", "milh�o", "bilh�o", "trilh�o", "quatrilh�o");
	$plural = array("centavos", "reais", "mil", "milh�es", "bilh�es", "trilh�es",
"quatrilh�es");

	$c = array("", "cem", "duzentos", "trezentos", "quatrocentos",
"quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
	$d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta",
"sessenta", "setenta", "oitenta", "noventa");
	$d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze",
"dezesseis", "dezesete", "dezoito", "dezenove");
	$u = array("", "um", "dois", "tr�s", "quatro", "cinco", "seis",
"sete", "oito", "nove");

	$z=0;

	$valor = number_format($valor, 2, ".", ".");
	$inteiro = explode(".", $valor);
	for($i=0;$i<count($inteiro);$i++)
		for($ii=strlen($inteiro[$i]);$ii<3;$ii++)
			$inteiro[$i] = "0".$inteiro[$i];

	// $fim identifica onde que deve se dar jun��o de centenas por "e" ou por "," ;)
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
	$string = str_replace ("�", "�", $string);
	$string = str_replace ("�", "�", $string);
	$string = str_replace ("�", "�", $string);
	$string = str_replace ("�", "A", $string);
	$string = str_replace ("�", "�", $string);
	$string = str_replace ("�", "�", $string);
	$string = str_replace ("�", "I", $string);
	$string = str_replace ("�", "�", $string);
	$string = str_replace ("�", "�", $string);
	$string = str_replace ("�", "�", $string);
	$string = str_replace ("�", "�", $string);
	$string = str_replace ("�", "�", $string);
	$string = str_replace ("�", "U", $string);
	$string = str_replace ("�", "�", $string);
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
	$query = "SELECT nome_usua FROM  usuario WHERE cod_usua='".$cod_usuario."' LIMIT 1";
	$result =mysql_query($query);
	$registro = mysql_fetch_array($result, MYSQL_ASSOC);
	$prop_nome = $registro['nome_usua'];
	
#_________________________________ PROPONENTE ________________________________________________________
	$query = "SELECT * FROM proponente WHERE cod_proponente='".$cod_usuario."' LIMIT 1";
	$result =mysql_query($query);
	$registro = mysql_fetch_array($result, MYSQL_ASSOC);
	$prop_tipo 		= $registro['TIPO_PPNT'];
	$prop_cpf  		= formataCPF($registro['CPF_PPNT']);
	$prop_cnpj      = formataCNPJ($registro['CNPJ_PPNT']);
	$prop_capciv 	= $registro['CAPCIVIL_PPNT'];
	$prop_nacional  = strtolower($registro['NACIONAL_PPNT']);
	$prop_sex		= $registro['SEXO_PPNT'];
	$prop_doc	    = $registro['DOCUMENTO_PPNT'];
	$prop_numdoc = $registro['NDOCUMENTO_PPNT'];
	$prop_dtdoc = formataDataBRA($registro['DTEMISSAO_PPNT']);
	$prop_emissor = $registro['ORGEMISSOR_PPNT'];
	$prop_nasc = formataDataBRA($registro['DTNASCIMENTO_PPNT']);
	$prop_dtcsm = formataDataBRA($registro['DTCASAMENTO_PPNT']);
	$prop_civil = $registro['COD_ESTCIV'];
	$prop_reg = $registro['REGIME_PPNT'];
	$prop_dtlocreg = formataDataBRA($registro['DTLOCREGIME_PPNT']);
	$prop_dtreg = formataDataBRA($registro['DTREGIME_PPNT']);
	$prop_lavrareg = $registro['LAVRADO_PPNT'];
	$prop_lvreg = $registro['LVREGIME_PPNT'];
	$prop_flsreg = $registro['FLSREGIME_PPNT'];
	$prop_numreg = $registro['NRREGIME_PPNT'];
	$prop_numlocreg = $registro['NUMLOCREGIME_PPNT'];
	$prop_locreg = $registro['LOCREGIME_PPNT'];
	$prop_lograd = $registro['COD_LOGR'];
	$prop_ender = $registro['ENDERECO_PPNT'];
	$prop_num = $registro['NRENDERECO_PPNT'];
	$prop_compl = $registro['CPENDERECO_PPNT'];
	$prop_bairro = $registro['COD_BAIRRO'];
	$prop_uf = $registro['COD_UF'];
	$prop_cidade = $registro['COD_MUNICIPIO'];
	$prop_cep = formataCep($registro['CEP_PPNT']);
	$prop_fone = $registro['TELEFONE_PPNT'];
	$t_imov_r = $registro['TIPOIMOV_PPNT'];
	$prop_flagbloco = $registro['BLIMOV_PPNT'];
	$prop_bloco = $registro['NUMBLIMOV_PPNT'];
	$prop_apart = $registro['APARTIMOV_PPNT'];
	$avalista = $registro['AVALISTA'];
	$procur = $registro['PROCURADOR'];
	$proc_info = $registro['INFOPROCURADOR_PPNT'];
	$prop_numsoc=$registro['NUMSOC_PPNT'];
	$cod_ppst=$registro['COD_PPST'];
if($proc_info!='')
{
	$procurador="Sr.(a) ".ucwords($prop_nome)." � neste ato representado (a) por seu (sua) procurador (a) ".$proc_info;
}else
{
	$procurador='';
}
//////////////////////////////////////////////////////////////////////////////////////////	
if($prop_doc==1){$pdoc="Carteira de Identidade de Estrangeiro";}
if($prop_doc==2){$pdoc="Carteira de Identidade dos Ju�zes";}
if($prop_doc==3){$pdoc="Carteira Funcional Minist�rio P�blico";}
if($prop_doc==4){$pdoc="Carteira Identidade Expedida p/ Conselho Profissionais Liberais";}
if($prop_doc==5){$pdoc="Carteira de Identidade Expedida pelo Min. Rela��es Exteriores";}
if($prop_doc==6){$pdoc="Carteira de Identidade Expedida por Ex�rcito, Marinha, Aeron�utica";}
if($prop_doc==7){$pdoc="Carteira de Identidade Expedida pelo Minist�rio P�blico";}
if($prop_doc==8){$pdoc="Carteira de Identidade Policial Expedida pela Pol�cia Federal";}
if($prop_doc==9){$pdoc="Carteira de Identidade";}
if($prop_doc==10){$pdoc="Carteira Nacional de Habilita��o";}
if($prop_doc==11){$pdoc="Carteira de Trabalho e Previd�ncia Social";}
if($prop_doc==12){$pdoc="Certificado de Reservista";}
if($prop_doc==13){$pdoc="Certid�o de Nascimento para Menor Representado ";}
if($prop_doc==14){$pdoc="Documento a classificar pela GECAT";}
if($prop_doc==15){$pdoc="Passaporte";}

#_________________________________ PROPOSTA __________________________________________________________
	$query = "SELECT * FROM proposta WHERE cod_ppst='".$cod_ppst."' LIMIT 1";
	$result =mysql_query($query);
	$registro = mysql_fetch_array($result, MYSQL_ASSOC);
	
	$tipo_simulador	= $registro['PRICESAC_PPST'];
	$valfinan  		= $registro['VLFINSOL_PPST'];
	$prop_prazo 	= $registro['PRZFINSOL_PPST'];
	$prestacao		= formataMoeda($registro['VLPRESTSOL_PPST']);
	$taxajuro		= $registro['TAXAJUROS_PPST'];
	$cod_ppst	    = $registro['COD_PPST'];
$taxaanual=formataMoeda($taxajuro*12);

#_________________________________ CONJUGE___________ __________________________________________________
	$query = "SELECT * FROM proponenteconjuge WHERE cod_proponente='".$cod_usuario."' LIMIT 1";
	$result =mysql_query($query);
	$registro = mysql_fetch_array($result, MYSQL_ASSOC);

	$conj_nome		= $registro['NOME_CONJPP'];
	$conj_capciv	= $registro['CAPCIVIL_CONJPP'];
	$conj_nacional	= strtolower($registro['NACIONAL_CONJPP']);
	$conj_cpf		= formataCPF($registro['CPF_CONJPP']);
	$conj_doc		= $registro['DOCUMENTO_CONJPP'];
	$conj_numdoc	= $registro['NDOCUMENTO_CONJPP'];
	$conj_dtdoc		= $registro['DTEMISSAO_CONJPP'];
	$conj_emissor	= $registro['ORGEMISSOR_CONJPP'];
	$conj_nasc		= $registro['DATA_CONJPP'];
	$conj_profissao	= strtolower($registro['PROFISSAO_CONJPP']);

if($conj_doc==1){$cdoc="Carteira de Identidade de Estrangeiro";}
if($conj_doc==2){$cdoc="Carteira de Identidade dos Ju�zes";}
if($conj_doc==3){$cdoc="Carteira Funcional Minist�rio P�blico";}
if($conj_doc==4){$cdoc="Carteira Identidade Expedida p/ Conselho Profissionais Liberais";}
if($conj_doc==5){$cdoc="Carteira de Identidade Expedida pelo Min. Rela��es Exteriores";}
if($conj_doc==6){$cdoc="Carteira de Identidade Expedida por Ex�rcito, Marinha, Aeron�utica";}
if($conj_doc==7){$cdoc="Carteira de Identidade Expedida pelo Minist�rio P�blico";}
if($conj_doc==8){$cdoc="Carteira de Identidade Policial Expedida pela Pol�cia Federal";}
if($conj_doc==9){$cdoc="Carteira de Identidade";}
if($conj_doc==10){$cdoc="Carteira Nacional de Habilita��o";}
if($conj_doc==11){$cdoc="Carteira de Trabalho e Previd�ncia Social";}
if($conj_doc==12){$cdoc="Certificado de Reservista";}
if($conj_doc==13){$cdoc="Certid�o de Nascimento para Menor Representado ";}
if($conj_doc==14){$cdoc="Documento a classificar pela GECAT";}
if($conj_doc==15){$cdoc="Passaporte";}
		

#_________________________________ IMOVEL  __________________________________________________
	$query = "SELECT * FROM imovel WHERE cod_ppst='".$cod_ppst."' LIMIT 1";
	$result =mysql_query($query);
	$registro = mysql_fetch_array($result, MYSQL_ASSOC);
	
	$imov_tipo      = $registro['TIPO_IMOV'];
    $imov_cond      = $registro['TPCONDOMINIO_IMOV'];
    $imov_lograd    = $registro['COD_LOGR'];
    $imov_ender     = $registro['ENDERECO_IMOV'];
    $imov_matric    = $registro['NRMATRGI_IMOV'];
	$numloc_imov	= $registro['NOMECARTRGI_IMOV'];
    $imov_num       = $registro['NRENDERECO_IMOV'];
    $imov_compl     = $registro['CPENDERECO_IMOV'];
    $imov_bairro    = $registro['COD_BAIRRO'];
    $imov_uf        = $registro['COD_UF'];
    $imov_cidade    = $registro['COD_MUNICIPIO'];
    $imov_cep       = formataCEP($registro['CEP_IMOV']);
	$imov_dtaval 	= formataDataBRA($registro['DTAVALIACAO_IMOV']);
	$valor_aval		= $registro['VLAVALIACAO_IMOV'];
	$area_imov      = $registro['AREA_IMOV'];
	$tpimposto_imov = $registro['TPIMPOSTO_IMOV'];
	$areautil_imov  = $registro['AREAUTIL_IMOV'];
	$areatotal_imov = $registro['AREATOTAL_IMOV'];
	$imov_flagbloc  = $registro['BLOCO_IMOV'];
	$imov_boco 		= $registro['NUMBLOCO_IMOV'];
	$apart_imov_g   = $registro['APART_IMOV'];
	$imov_flagcond	= $registro['FLAGCONDOMINIO'];
	$imov_nomecond	= $registro['NOMECONDOMINIO'];
	$imov_foreiro	= $registro['FLAGFOREIRO'];
	$imov_vagas		= $registro['VAGAS_IMOV'];
	$imov_tipovagas	= $registro['TPVAGAS_IMOV'];
	$imov_dtcompra  = formataDataBRA($registro['DTCOMPRA_IMOV']);
	if($imov_foreiro=='S'){$foreiro="encontra-se no dossi� do financiamento";}else{$foreiro="n�o se aplica";}
	if($imov_flagcond=='S'){$cnd="Condom�nio ".$imov_nomecond;}else{$cnd="n�o se aplica.";}

	
#########################################################################################################
#################################### NACIONALIDADES #####################################################
################################### ENDERE�OS ###########################################################

#________________________________________________EMITENTE___________________________________________

$query="SELECT desc_logr FROM logradouro WHERE cod_logr = '".$prop_lograd."' ";
$result =mysql_query($query);
$registro = mysql_fetch_array($result, MYSQL_ASSOC);
$p_lograd= $registro['desc_logr'];

$query="SELECT nome_municipio, cod_uf FROM municipio WHERE cod_municipio = '".$prop_cidade."' ";
$result =mysql_query($query);
$registro = mysql_fetch_array($result, MYSQL_ASSOC);
$p_cidade=$registro['nome_municipio'];
$p_uf= $registro['cod_uf'];

if($t_imov_r=="A")
{
	if($prop_flagbloco=='S')
	{
		$endereco=$p_lograd." ".$prop_ender." nr. ".$prop_num.", apto. ".$prop_apart.", bloco: ".$prop_bloco.", bairro: ".$prop_bairro.", cidade: ".ucwords(strtolower($p_cidade)).", estado: ".$p_uf;	
	}
	if($prop_flagbloco=='' || $prop_flagbloco=='N')
	{
		$endereco=$p_lograd." ".$prop_ender." nr. ".$prop_num.", apto. ".$prop_apart.",  bairro: ".$prop_bairro.", cidade: ".ucwords(strtolower($p_cidade)).", estado: ".$p_uf;	
	}
}else
{
	$endereco=$p_lograd." ".$prop_ender." nr. ".$prop_num.", bairro: ".$prop_bairro.", cidade: ".ucwords(strtolower($p_cidade)).", estado: ".$p_uf;	
}
#______________________________________IM�VEL________________________________________________
  $query="SELECT desc_logr FROM logradouro	WHERE cod_logr = '".$imov_lograd."' ";
  $result =mysql_query($query);
  $registro = mysql_fetch_array($result, MYSQL_ASSOC);
  $imov_lograd= $registro['desc_logr'];
  
  $query="SELECT nome_municipio, cod_uf FROM municipio WHERE cod_municipio = '".$imov_cidade."' ";
  $result =mysql_query($query);
  $registro = mysql_fetch_array($result, MYSQL_ASSOC);
  $imov_cidade= $registro['nome_municipio']; 
  $imov_uf= $registro['cod_uf'];

if($t_imov_g=="A")
{
	if($imov_flagbloco=='S')
	{
		$enderecoimov=$imov_lograd." ".$imov_ender." nr. ".$imov_num.", apto. ".$imov_apart.", bloco: ".$imov_bloco.", bairro: ".$imov_bairro.", cidade: ".ucwords(strtolower($imov_cidade)).", estado: ".$imov_uf.".";	
	}
	if($imov_flagbloco=='' || $imov_flagbloco=='N')
	{
		$enderecoimov=$imov_lograd." ".$imov_ender." nr. ".$imov_num.", apto. ".$imov_apart.",  bairro: ".$imov_bairro.", cidade: ".ucwords(strtolower($imov_cidade)).", estado: ".$imov_uf.".";	
	}
}else
{
	$enderecoimov=$imov_lograd." ".$imov_ender." nr. ".$imov_num.", bairro: ".$imov_bairro.", cidade: ".ucwords(strtolower($imov_cidade)).", estado: ".$imov_uf.".";	
}
#______________________________________ AVALISTA ________________________________________________
  $query="SELECT desc_logr FROM logradouro	WHERE cod_logr = '".mysql_real_escape_string($av_lograd)."' ";
  $result =mysql_query($query);
  $registro = mysql_fetch_array($result, MYSQL_ASSOC);
  $av_lograd= $registro['desc_logr'];
  
  $query="SELECT nome_municipio, cod_uf FROM municipio WHERE cod_municipio = '".mysql_real_escape_string($av_cidade)."' ";
  $result =mysql_query($query);
  $registro = mysql_fetch_array($result, MYSQL_ASSOC);
  $av_cidade= ucwords(strtolower($registro['nome_municipio'])); 
  $av_uf= $registro['cod_uf'];
  
  $enderecoav=$av_lograd." ".$av_ender." nr. ".$av_num.", bairro: ".$av_bairro.", cidade: ".$av_cidade.", estado: ".$av_uf.".";	
###########################################################################################################
############################# QUALIFICA��ES ASSINATURAS ##############################################
function assinaturaemit($prop_tipo,$cod_ppst)
{
	if($prop_tipo==1)
	{
	
		#______________________________________ PROPONENTE __________________________________________________
		$ass_prop="<table border='0' width='728'>
							<tr>
								<td colspan='2' width='364' align='center'>_____________________________________</td>
								<td colspan='3' width='364' align='center'>_____________________________________</td>
							</tr>
							<tr>
								<td colspan='2' width='364' align='center'><b>".maiusculo($prop_nome)."</td>
								<td colspan='3' width='364' align='center'>".maiusculo($conj_nome)."</b></td>
							</tr>
					</table>";
	 return $ass_prop;
	}if($prop_tipo==2)
	{
			$query = "SELECT nome_sppnt FROM socioproponente WHERE cod_ppst='".$cod_ppst."'";
			$result =mysql_query($query);
			$socio=mysql_num_rows($result);
			if (mysql_num_rows($result) > 0)
			{
			//echo "entrou";
					$s=1;
				while($linhas = mysql_fetch_array($result, MYSQL_ASSOC))
				{
					
					$teste[$s]="<table border='0' width='728'>
								<tr>
									<td colspan='2' width='364' align='center'>_____________________________________</td>
								</tr>
								<tr>
									<td colspan='2' width='364' align='center'>".maiusculo($linhas[nome_sppnt])."</td>
								</tr> 
						</table><p align='justify'><font color='#FFFFFF'>LN</font></p>";
					$s++;
				}
			return $teste[1].$teste[2].$teste[3].$teste[4].$teste[5];
			}
			
			
	}
	
}				
function assinaturacoobgr($prop_tipo)
{
	if($prop_tipo==1)
	{
		#______________________________________ COOBRIGADO GARANTIDOR ___________________________________________
		$ass_coobgar="
					<p align='justify'><font color='#FFFFFF'>LN</font></p>
					<p align='justify'>COOBRIGADO INTEVENIENTE GARANTIDOR:</p>
					<p align='justify'><font color='#FFFFFF'>LN</font></p>
					<table border='0' width='728'>
							<tr>
								<td colspan='2' width='364' align='center'>_____________________________________</td>
								<td colspan='3' width='364' align='center'></td>
							</tr>
							<tr>
								<td colspan='2' width='364' align='center'><b>".maiusculo($conj_nome)."</td>
								<td colspan='3' width='364' align='center'></b></td>
							</tr>
					</table>";
	 return $ass_coobgar;
	}else
	{
		$ass_coobgar="";
		return $ass_coobgar;
	}
}			
function assinaturacoobav()
{
	if($prop_tipo==1)
	{
		#______________________________________ COOBRIGADO AVALISTA ___________________________________________
		if($av_cpf!=$conj_cpf)
		{
			$ass_coobav="
					<p align='justify'><font color='#FFFFFF'>LN</font></p>
					<p align='justify'>COOBRIGADO INTEVENIENTE AVALISTA:</p>
					<p align='justify'><font color='#FFFFFF'>LN</font></p>
					<table border='0' width='728'>
								<tr>
									<td colspan='2' width='364' align='center'>_____________________________________</td>
									<td colspan='3' width='364' align='center'></td>
								</tr>
								<tr>
									<td colspan='2' width='364' align='center'><b>".maiusculo($av_nome)."</td>
									<td colspan='3' width='364' align='center'></b></td>
								</tr>
						</table>";
			 return $ass_coobav;
		}else
		{
			$ass_coobav='';
			 return $ass_coobav;
		}
	}else
	{
			$ass_coobav='';
			 return $ass_coobav;
			
	}
}
######################################################################################################
############################# QUALIFICA��ES EMITENTE #################################################
################################### EMITENTE MASCULINO ##########################################
if($prop_tipo==1)
{
if($prop_sex=='M')
{
	//EST. CIVIL CASADO
	if($prop_civil==2)
	{
//________________________ Comunh�o Parcial de Bens antes da lei_____________________________________
		if($prop_reg==7)
		{
			if($prop_nacional==$conj_nacional)
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv!=$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}else
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>,  ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv!=$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}
		}
//___________________________________________________________________________________________________________
//__________________________ Comunh�o Parcial de Bens depois da lei_____________________________________
		if($prop_reg==1)
		{
			if($prop_nacional==$conj_nacional)
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv!=$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}else
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv!=$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}

		}
//___________________________________________________________________________________________________
//______________________________ Comunh�o Universal de Bens antes da lei__________________________________
		if($prop_reg==2)
		{
				if($prop_nacional==$conj_nacional)
				{
						if($prop_doc==9 && $conj_doc==9)
						{
								if($prop_capciv==$conj_capciv)
								{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunh�o Universal de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
								}else
								{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunh�o Universal de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
								}
						}else
						{
								if($prop_capciv!=$conj_capciv)
								{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunh�o Universal de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
								}else
								{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunh�o Universal de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
								}
						}
				}else
				{
						if($prop_doc==9 && $conj_doc==9)
						{
								if($prop_capciv==$conj_capciv)
								{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunh�o Universal de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
								}else
								{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunh�o Universal de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
								}
						}else
						{
								if($prop_capciv!=$conj_capciv)
								{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunh�o Universal de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
								}else
								{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunh�o Universal de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
								}
						}
				}
		}
//________________________________________________________________________________________________________
//_____________________________ Comunh�o Universal de Bens depois da lei_________________________________
		if($prop_reg==3)
		{
			if($prop_nacional==$conj_nacional)
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunh�o Universal de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunh�o Universal de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv!=$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunh�o Universal de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunh�o Universal de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}else
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunh�o Universal de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunh�o Universal de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunh�o Universal de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunh�o Universal de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}
		}
//_________________________________________________________________________________________________		
//________________________________________Separa��o de Bens com pacto_________________________________
		if($prop_reg==5)
		{
			if($prop_nacional==$conj_nacional)
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Separa��o de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Separa��o de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv!=$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Separa��o de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Separa��o de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}else
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Separa��o de Bens de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Separa��o de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv!=$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Separa��o de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Separa��o de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}
		}
//_______________________________________________________________________________________________________		
//_______________________________________ Separa��o de Bens obrigat�rioa _________________________________
		if($prop_reg==6)
		{
			if($prop_nacional==$conj_nacional)
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Separa��o Obrigat�ria de bens, nos termos do artigo 1641 do C�digo Civil Brasileiro,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Separa��o Obrigat�ria de bens, nos termos do artigo 1641 do C�digo Civil Brasileiro,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv!=$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Separa��o Obrigat�ria de bens, nos termos do artigo 1641 do C�digo Civil Brasileiro,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Separa��o Obrigat�ria de bens, nos termos do artigo 1641 do C�digo Civil Brasileiro,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}else
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Separa��o Obrigat�ria de bens, nos termos do artigo 1641 do C�digo Civil Brasileiro,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Separa��o Obrigat�ria de bens, nos termos do artigo 1641 do C�digo Civil Brasileiro,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv!=$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Separa��o Obrigat�ria de bens, nos termos do artigo 1641 do C�digo Civil Brasileiro,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Separa��o Obrigat�ria de bens, nos termos do artigo 1641 do C�digo Civil Brasileiro,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}
		}
	}//fim if $prop_civil==2
//____________________________________________________________________________________________________
	// EST. CIVIL SOLTEIRO
	if($prop_civil==1)
	{
			$emitente="<b>".maiusculo($prop_nome)."</b>, ".$prop_nacional.", solteiro, portador da ".$prop_doc." nr. ".$prop_numdoc.", expedida por ".$prop_emissor.", inscrito no CPFMF sob o nr. ".$prop_cpf.", residente e domiciliado no(a) ".$prop_ender."<br>".$proc_info;
	}
//_____________________________________________________________________________________________________	
	// EST.CIVIL UNI�O EST�VEL
	if($prop_civil==1)
	{
			$emitente="<b>".maiusculo($prop_nome)."</b>, (nacionalidade), casados pelo regime de Uni�o Est�vel, portador(a) da carteira  de identidade nr. ".$prop_numdoc.", expedida por ".$prop_emissor.", inscrito(a) no CPFMF sob o nr. ".$prop_cpf.", residente e domiciliado(a) no(a) ".$prop_ender."<br>".$proc_info;
	}
}//fim if ($prop_sex=='M')
############################################## EMITENTE FEMININO ########################################
if($prop_sex=='F')
{
//EST. CIVIL CASADO
	if($prop_civil==2)
	{
//________________________ Comunh�o Parcial de Bens antes da lei_____________________________________
		if($prop_reg==7)
		{
			if($prop_nacional==$conj_nacional)
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portador da ".$prop_doc." nr. ".$prop_numdoc." expedida por ".$prop_emissor.", ele portadora da ".$conj_doc." nr. ".$conj_numdoc." expedida por ".$conj_emissor.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portador da ".$prop_doc." nr. ".$prop_numdoc." expedida por ".$prop_emissor.", ele portadora da ".$conj_doc." nr. ".$conj_numdoc." expedida por ".$conj_emissor.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}else
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portador da ".$prop_doc." nr. ".$prop_numdoc." expedida por ".$prop_emissor.", ele portadora da ".$conj_doc." nr. ".$conj_numdoc." expedida por ".$conj_emissor.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$conj_capciv.", ".$conj_nacional.", casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portador da ".$prop_doc." nr. ".$prop_numdoc." expedida por ".$prop_emissor.", ele portadora da ".$conj_doc." nr. ".$conj_numdoc." expedida por ".$conj_emissor.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}
		}
//___________________________________________________________________________________________________________
//__________________________ Comunh�o Parcial de Bens depois da lei_____________________________________
		if($prop_reg==1)
		{
			if($prop_nacional==$conj_nacional)
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcsm.",  ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv!=$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portador da ".$prop_doc." nr. ".$prop_numdoc." expedida por ".$prop_emissor.", ele portadora da ".$conj_doc." nr. ".$conj_numdoc." expedida por ".$conj_emissor.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portador da ".$prop_doc." nr. ".$prop_numdoc." expedida por ".$prop_emissor.", ele portadora da ".$conj_doc." nr. ".$conj_numdoc." expedida por ".$conj_emissor.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}else
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portador da ".$prop_doc." nr. ".$prop_numdoc." expedida por ".$prop_emissor.", ele portadora da ".$conj_doc." nr. ".$conj_numdoc." expedida por ".$conj_emissor.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portador da ".$prop_doc." nr. ".$prop_numdoc." expedida por ".$prop_emissor.", ele portadora da ".$conj_doc." nr. ".$conj_numdoc." expedida por ".$conj_emissor.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}

		}
//___________________________________________________________________________________________________
//______________________________ Comunh�o Universal de Bens antes da lei__________________________________
		if($prop_reg==2)
		{
				if($prop_nacional==$conj_nacional)
				{
						if($prop_doc==9 && $conj_doc==9)
						{
								if($prop_capciv==$conj_capciv)
								{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunh�o Universal de Bens,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
								}else
								{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunh�o Universal de Bens,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
								}
						}else
						{
								if($prop_capciv==$conj_capciv)
								{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunh�o Universal de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
								}else
								{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunh�o Universal de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
								}
						}
				}else
				{
						if($prop_doc==9 && $conj_doc==9)
						{
								if($prop_capciv==$conj_capciv)
								{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunh�o Universal de Bens,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
								}else
								{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunh�o Universal de Bens,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
								}
						}else
						{
								if($prop_capciv==$conj_capciv)
								{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunh�o Universal de Bens,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portadora da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ele portador da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
								}else
								{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunh�o Universal de Bens,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portadora da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ele portador da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
								}
						}
				}
		}
//________________________________________________________________________________________________________
//_____________________________ Comunh�o Universal de Bens depois da lei_________________________________
		if($prop_reg==3)
		{
			if($prop_nacional==$conj_nacional)
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunh�o Universal de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunh�o Universal de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunh�o Universal de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portadora da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ele portador da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunh�o Universal de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portadora da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ele portador da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}else
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunh�o Universal de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunh�o Universal de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunh�o Universal de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portadora da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ele portador da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunh�o Universal de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portadora da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ele portador da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}
		}
//_________________________________________________________________________________________________		
//________________________________________Separa��o de Bens com pacto_________________________________
		if($prop_reg==5)
		{
			if($prop_nacional==$conj_nacional)
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Separa��o de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Separa��o de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Separa��o de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portadora da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ele portador da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Separa��o de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portadora da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ele portador da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}else
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Separa��o de Bens de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Separa��o de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;

							}
					}else
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Separa��o de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portadora da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ele portador da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$prop_nacional." ele ".$conj_nacional.", casados pelo regime de Separa��o de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cart�rio de Registro de Im�veis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portadora da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ele portador da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}
		}
//_______________________________________________________________________________________________________		
//_______________________________________ Separa��o de Bens obrigat�rioa _________________________________
		if($prop_reg==6)
		{
			if($prop_nacional==$conj_nacional)
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Separa��o Obrigat�ria de bens, nos termos do artigo 1641 do C�digo Civil Brasileiro,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Separa��o Obrigat�ria de bens, nos termos do artigo 1641 do C�digo Civil Brasileiro,  em ".$prop_dtcsm.",  ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Separa��o Obrigat�ria de bens, nos termos do artigo 1641 do C�digo Civil Brasileiro,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portadora da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ele portador da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Separa��o Obrigat�ria de bens, nos termos do artigo 1641 do C�digo Civil Brasileiro,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portadora da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ele portador da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}else
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Separa��o Obrigat�ria de bens, nos termos do artigo 1641 do C�digo Civil Brasileiro,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Separa��o Obrigat�ria de bens, nos termos do artigo 1641 do C�digo Civil Brasileiro,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Separa��o Obrigat�ria de bens, nos termos do artigo 1641 do C�digo Civil Brasileiro,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portadora da ".$prop_doc." nr. ".$prop_numdoc." expedida por ".$prop_emissor.", ele portadorda ".$conj_doc." nr. ".$conj_numdoc." expedida por ".$conj_emissor.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Separa��o Obrigat�ria de bens, nos termos do artigo 1641 do C�digo Civil Brasileiro,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portadora da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ele portador da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}
		}
	}//fim if $prop_civil==2
//____________________________________________________________________________________________________
	// EST. CIVIL SOLTEIRO
	if($prop_civil==1)
	{
			$emitente="<b>".maiusculo($prop_nome)."</b>, ".$prop_nacional.", solteira, portadora da ".$prop_doc." nr. ".$prop_numdoc.", expedida por ".$prop_emissor.", inscrita no CPFMF sob o nr. ".$prop_cpf.", residente e domiciliada na ".$prop_lograd." ".$prop_ender." nr. ".$prop_num.", apto. ".$prop_apart.", Bloco: ".$prop_bloco.", Bairro: ".$prop_bairro.", Cidade: ".$prop_cidade.", Estado: ".$prop_uf.".<br>".$proc_info;
	}
//_____________________________________________________________________________________________________	
	// EST.CIVIL UNI�O EST�VEL
	if($prop_civil==1)
	{
			$emitente="<b>".maiusculo($prop_nome)."</b>, ".$prop_nacional.", portador(a) da carteira  de identidade nr. ".$prop_numdoc.", expedida por ".$prop_emissor.", inscrito(a) no CPFMF sob o nr. ".$prop_cpf.", residente e domiciliado(a) na ".$prop_lograd." ".$prop_ender." nr. ".$prop_num.", apto. ".$prop_apart.", Bloco: ".$prop_bloco.", Bairro: ".$prop_bairro.", Cidade: ".$prop_cidade.", Estado: ".$prop_uf.". <b>".maiusculo($conj_nome)."</b>, ".$conj_nacional.", ".$conj_civil.", portador(a) da carteira  de identidade nr. ".$conj_numdoc.", expedida por ".$conj_emissor.", inscrito(a) no CPFMF sob o nr. ".$conj_cpf.", residente e domiciliado(a) no(a) ".$prop_ender."<br>".$proc_info;
	}
}//fim if ($prop_sex=='M')
}elseif($prop_tipo==2)
{
	if($proc_info!='')
	{
		$procurador=", neste ato representada por seu(s) procuradore(s) ".$proc_info;
		$endereco=$p_lograd." ".$prop_ender." nr. ".$prop_num.", ".$prop_compl.", bairro: ".$prop_bairro.", cidade: ".ucwords(strtolower($p_cidade)).", estado: ".$p_uf;	
		$emitente="<b>".maiusculo($prop_nome)."</b>, inscrita no CNPJMF sob o nr. ".$prop_cnpj."com sede no(a) ".$endereco.$procurador;
	}elseif($proc_info=='')
	{
		$prop_comp=$prop_compl.",";
		$endereco=$p_lograd." ".$prop_ender." nr. ".$prop_num.", ".$prop_compl." bairro: ".$prop_bairro.", cidade: ".ucwords(strtolower($p_cidade)).", estado: ".$p_uf;	
		if($estatsocial==1)
		{
			$emitente="<b>".maiusculo($prop_nome)."</b>, inscrita no CNPJMF sob o nr. ".$prop_cnpj." com sede no(a) ".$endereco.", neste ato representada na forma de seu Estatuto, por seus representates legais ao final assinados e identificados.";
		}elseif($contatsocial==1)
		{
			$emitente="<b>".maiusculo($prop_nome)."</b>, inscrita no CNPJMF sob o nr. ".$prop_cnpj." com sede no(a) ".$endereco.", neste ato representada na forma de seu Contrato Social, por seus representates legais ao final assinados e identificados.";
		}
		
	}
	
}
#######################################################################################################
#######################################################################################################
############################# QUALIFICA��O AVALISTA #####################################################
if($conj_cpf==$av_cpf)
{
	$cobgav="n�o se aplica.";
}else
{
	if($av_nome!='')
	{
		$cobgav="<b>".maiusculo($av_nome)."</b>, ".$av_civil.", portador(ra) da ".$av_doc." nr. ".$prop_numdoc.", expedida por ".$prop_emissor.", inscrito(a) no CPFMF sob o nr. ".$prop_cpf.", residente e domiciliado(a) ".$enderecoav."."; 
	}else
	{
		$cobgav="n�o se aplica.";
	}
}

######################################################################################################
######################################### QUALIFICA��O IM�VEL OBJETO DE GARANTIA ######################
if($imov_tipo=='A')
{
	if($imov_vagas=='')
	{
		if($imov_flagbloco=='S')
		{
			$imovgarantia="<b>APARTAMENTO NR. ".$prop_apart."</b>, localizado no bloco: ".$bloco_imov.", situado na(o) ".$imov_lograd." ".$imov_ender." nr. ".$imov_num.", ".$imov_cidade."-".$imov_uf.". Im�vel perfeitamente descrito e caracterizado na matr�cula nr. ".$imov_matric."  do ".$numloc_imov."� Cart�rio de Registro de Im�veis de ".ucwords(strtolower($imov_cidade)).".";
		}else
		{
			$imovgarantia="<b>APARTAMENTO NR. ".$prop_apart."</b>, situado na(o) ".$imov_lograd." ".$imov_ender." nr. ".$imov_num.", ".$imov_cidade."-".$imov_uf." . Im�vel perfeitamente descrito e caracterizado na matr�cula nr. ".$imov_matric."  do ".$numloc_imov."� Cart�rio de Registro de Im�veis de ".ucwords(strtolower($imov_cidade)).".";
		}
	}else
	{
		if($imov_flagbloco=='S')
		{
			$imovgarantia="<b>APARTAMENTO NR. ".$prop_apart."</b>, localizado no bloco: ".$bloco_imov.", situado na(o) ".$imov_lograd." ".$imov_ender." nr. ".$imov_num.", ".$imov_cidade."-".$imov_uf.". Possui ".$imov_vagas." vaga(s) de garagem. Im�vel perfeitamente descrito e caracterizado na matr�cula nr. ".$imov_matric."  do ".$numloc_imov."� Cart�rio de Registro de Im�veis de ".ucwords(strtolower($imov_cidade)).".";
		}else
		{
			$imovgarantia="<b>APARTAMENTO NR. ".$prop_apart."</b>, situado na(o) ".$imov_lograd." ".$imov_ender." nr. ".$imov_num.", ".$imov_cidade."-".$imov_uf.". Possui ".$imov_vagas." vaga(s) de garagem. Im�vel perfeitamente descrito e caracterizado na matr�cula nr. ".$imov_matric."  do ".$numloc_imov."� Cart�rio de Registro de Im�veis de ".ucwords(strtolower($imov_cidade)).".";
		}	
	}
}
if($imov_tipo=='L')
{
	$imovgarantia="<b>LOJA NR.".$imov_num."</b>, localizada no(a) ".$enderecoimov." Im�vel perfeitamente descrito e caracterizado na matr�cula nr. ".$imov_matric."  do ".$numloc_imov."� Cart�rio de Registro de Im�veis de ".ucwords(strtolower($imov_cidade)).".";
}
if($imov_tipo=='C')
{
	$imovgarantia="<b>CASA E RESPECTIVO TERRENO</b>, localizados no(a) ".$enderecoimov." Im�vel perfeitamente descrito e caracterizado na matr�cula nr. ".$imov_matric."  do ".$numloc_imov."� Cart�rio de Registro de Im�veis de ".ucwords(strtolower($imov_cidade)).".";
}
if($imov_tipo=='P')
{
	$imovgarantia="<b>PR�DIO, localizado no(a) ".$enderecoimov." Im�vel perfeitamente descrito e caracterizado na matr�cula nr. ".$imov_matric."  do ".$numloc_imov."� Cart�rio de Registro de Im�veis de ".ucwords(strtolower($imov_cidade)).".";
}
if($imov_tipo=='T')
{
	$imovgarantia="<b>TERRENO</b>, localizado no(a) ".$enderecoimov." Im�vel perfeitamente descrito e caracterizado na matr�cula nr. ".$imov_matric."  do ".$numloc_imov."� Cart�rio de Registro de Im�veis de ".ucwords(strtolower($imov_cidade)).".";
}
if($prop_tipo==1)
{
	$coobrigado_garantidor=maiusculo($conj_nome);
}
if($prop_tipo==2)
{
	$coobrigado_garantidor="n�o se aplica";
	$cobgav="n�o se aplica";
}
##########################################################################################################
##########################################################################################################
#										C�DULA DE CR�DITO BANC�RIO									
##########################################################################################################		
		//$titulo="<table border='1' width='728'>
					//<tr>
						//<td> align='center'><b>CONTRATO DE COMPRA E VENDA DE IM�VEL COM FINANCIAMENTO, ALIENA��O FIDUCI�RIA DE IM�VEL E OUTRAS AVEN�AS<b></td>
					//</tr>
				//</table>";

		$texto="<font face='Arial' size='10'>
				<table border='1' width='728'>
					<tr>
						<td colspan='2' width='364'><b>1) N� ".$numpropcg."</td>
						<td colspan='3' width='364'>2) Valor R$ ".formataMoeda($valfinan)." (".extenso($valfinan).")</b><br><font color='#FFFFFF'>LN</font></td>
					</tr>
					<tr>
						<td colspan='5' align='center'><b>A- EMITENTE</b></td>
					</tr>
					<tr>
						<td colspan='5'><font color='#FFFFFF'>LN</font><br>".$emitente."<br><font color='#FFFFFF'>LN</font></td>
					</tr>
					<tr>
						<td colspan='5' align='center'><b>B- CREDOR</b></td>
					</tr>
					<tr>
						<td colspan='5'><font color='#FFFFFF'>LN</font><br><b>BANCO PANAMERICANO S/A</b>, com sede na Av. Paulista, 2.240, S�o Paulo - SP, inscrito no C.N.P.J. sob n� 59.285.411/0001-13, doravante denominado simplesmente BANCO.<br><font color='#FFFFFF'>LN</font></td>
					</tr>
					<tr>
						<td colspan='5' align='center'><b>C- COOBRIGADOS</b>
						</td>
					</tr>
					<tr>
						<td colspan='5'><font color='#FFFFFF'>LN</font><br>COOBRIGADO INTERVENIENTE GARANTIDOR - ".$coobrigado_garantidor.".<br><font color='#FFFFFF'>LN</font></td>
					</tr>
					<tr>
						<td colspan='5' ><font color='#FFFFFF'>LN</font><br>COOBRIGADO INTERVENIENTE AVALISTA - ".$cobgav." <br><font color='#FFFFFF'>LN</font></td>
					</tr>
					<tr>
						<td colspan='5' align='center'><b>D - CARACTER�STICAS DA C�DULA</b>
						</td>
					</tr>
					<tr>
						<td colspan='5'><font color='#FFFFFF'>LN</font><br>VALOR DO CR�DITO ABERTO A FAVOR DO EMITENTE - R$ ".formataMoeda($valfinan)." (".extenso($valfinan).") <br><font color='#FFFFFF'>LN</font></td>
					</tr>
					<tr>
						<td colspan='2'><font color='#FFFFFF'>LN</font><br>TAXA DE JUROS ao ano&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".str_replace('.',',',$taxaanual)." %<br><font color='#FFFFFF'>LN</font></td>
						<td colspan='3'>TAXA DE JUROS ao m�s&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".str_replace('.',',',$taxajuro)." %</td>
					</tr>
					<tr>
						<td colspan='5'><font color='#FFFFFF'>LN</font><br>DATA DA LIBERA��O: 05 (cinco) dias �teis do conhecimento do registro da garantia.</td>
					</tr>
					<tr>
						<td colspan='5' ><font color='#FFFFFF'>LN</font><br>FINALIDADE: ABERTURA DE CR�DITO EM FAVOR DO EMITENTE</td>
					</tr>
					<tr>
						<td colspan='5'><font color='#FFFFFF'>LN</font><br>PERIODICIDADE DE CAPITALIZA��O DOS JUROS: MENSAL </td>
					</tr>
					<tr>
						<td colspan='5' ><font color='#FFFFFF'>LN</font><br>�NDICE DE CORRE��O MONET�RIA: As parcelas s�o fixas. A corre��o monet�ria incidir� somente na hip�tese de inadimplemento contratual e para o fim de corre��o anual do valor atribu�do ao im�vel para a venda em p�blico leil�o (art. 24, inciso VI, da Lei n� 9.514/97)	</td>
					</tr>
					<tr>
						<td colspan='5'><font color='#FFFFFF'>LN</font><br>OUTROS ENCARGOS INCIDENTES</td>
					</tr>
					<tr>
						<td colspan='5' ><font color='#FFFFFF'>LN</font><br>TAXAS:<br>TAC � Tarifa de Abertura de cr�dito: R$ ".$tac." <br>TEC:- Tarifa de Emiss�o do Carn� : R$ 4,95 (quatro reais e noventa e cinco centavos) por folha <br>Tarifa de D�bito em Conta Corrente: N�o tem<br></td>
					</tr>
					<tr>
						<td colspan='5' align='center'><b>E � FORMA DE PAGAMENTO EM R$ - PRINCIPAL MAIS ENCARGOS</b></td>
					</tr>
					<tr>
						<td align='center'>MENSAL<br><br>sim</td>
						<td align='center'>N� DE<br>PARCELAS<br><br>".$prop_prazo."</td>
						<td align='center'>VALOR DA<br>PARCELA<br><br>R$ ".$prestacao."</td>
						<td align='center'>VENCIMENTO DA 1� PARCELA<br>".$dtvenc."</td>
						<td align='center'>VENCIMENTO DA �LTIMA PARCELA<br>".somardata(date("d/m/Y"),0,$prop_prazo,0)."<br><font color='#FFFFFF'>LN</font><br></td>
					</tr>
					<tr>
						<td colspan='5'><font color='#FFFFFF'>LN</font><br>VENCIMENTO:  30 dias ap�s a CCB.<br><font color='#FFFFFF'>LN</font> </td>
					</tr>
					<tr>
						<td colspan='5'><font color='#FFFFFF'>LN</font><br>LOCAL DE PAGAMENTO: ".$p_cidade." / ".$p_uf."<br><font color='#FFFFFF'>LN</font></td>
					</tr>
					<tr>
						<td colspan='5' align='center'><b>F - GARANTIA REAL</b></td>
					</tr>
					<tr>
						<td colspan='5'>".$imovgarantia."<br><font color='#FFFFFF'>LN</font></td>
					</tr>
					<tr>

						<td colspan='5'><font color='#FFFFFF'>LN</font><br>DOCUMENTOS DO IM�VEL OBJETO DA ALIENA��O FIDUCI�RIA:<br><font color='#FFFFFF'>LN</font><br>
						Certid�o da Matr�cula e negativa de �nus atualizada: Expedida em: ".$certonus."<br>
						Locad�mico (CAT e guia recolhida): ".$foreiro.".<br>
						Plantas Aprovadas: n�o se aplica.<br>
						Certid�o negativa de IPTU: ".$certiptu."<br>
						Carn� do exerc�cio vigente com as presta��es pagas: encontra-se no dossi� de financiamento.<br>
						Especifica��o de Condom�nio: ".$cnd.".<br>
						Certid�o negativa de d�bitos condominiais (taxas ordin�rias e extraordin�rias), com firma reconhecida, acompanhada da ATA de ELEI��O DO S�NDICO: encontra-se no dossi� de financiamento.<br>
						3 �ltimas contas de luz: encontra-se no dossi� de financiamento.<br>
						3 �ltimas contas de g�s: n�o se aplica.
						</td>
					</tr>
					<tr>
						<td colspan='5'><font color='#FFFFFF'>LN</font><br>VALOR DE AVALIA��O DO IM�VEL PARA TODOS OS EFEITOS LEGAIS: R$ ".formataMoeda($valor_aval)." (".extenso($valor_aval).")<br><font color='#FFFFFF'>LN</font> </td>
					</tr>
					<tr>
						<td colspan='5' align='center'><b>G � DOS SEGUROS</b></td>
					</tr>
					<tr rowspan='2'>
						<td colspan='5'>DO SEGURO DE PESSOA<br>O EMITENTE, ap�s an�lise pr�via de proposta escrita correspondente e da aceita��o de seus elementos, contratou Seguro de Vida e Invalidez Permanente, tendo o BANCO como favorecido, para o fim de recebimento do capital segurado e sua aplica��o na liquida��o total ou parcial das obriga��es de pagamento dos valores representados pela presente C�dula de Cr�dito Banc�rio. Para o pagamento do pr�mio devido � Seguradora, o EMITENTE utilizar� parte do cr�dito aberto em seu favor pelo BANCO.
<br><br>DO SEGURO DE DANO - O EMITENTE contratar� com companhia id�nea, dentro do prazo de at� 30 (trinta) dias, contados desta data, o seguro do bem alienado fiduciariamente, contra risco de fogo e desabamento, bem como das benfeitorias e acess�es nele existentes, de maneira a atender o previsto na al�nea �h�, do item 8.1, infra, obedecido o disposto no art. 36 da Lei n� 10.931/2004.</td>
					</tr>
					<tr  rowspan='2'>
						<td colspan='5'><br>O DEVEDOR, titular de dom�nio do im�vel, ora alienado fiduciariamente,  declara, sob as penas da lei, que o bem oferecido em garantia encontra-se  livre e desembara�ado de �nus, encargos ou pend�ncias judiciais e extrajudiciais.<br><br><font color='#FFFFFF'>LN</font></td>
					</tr>
					<tr>
						<td colspan='5'><br><br><br></td>
					</tr>
					<tr  rowspan='2'>
						<td colspan='5'><br><br>INFORMA��ES ADICIONAIS:<font color='#FFFFFF'>LN</font><br><font color='#FFFFFF'>LN</font><br><font color='#FFFFFF'>LN</font><br><font color='#FFFFFF'>LN</font><br><font color='#FFFFFF'>LN</font><br><font color='#FFFFFF'>LN</font><br><font color='#FFFFFF'>LN</font><br><font color='#FFFFFF'>LN</font><br><font color='#FFFFFF'>LN</font><br><font color='#FFFFFF'>LN</font><br></td>
					</tr></font>
			</table>
			<br><p align='justify'>
			Na data do vencimento, para pagamento do valor correspondente ao montante utilizado do cr�dito aberto em meu favor, indicado no campo 2 acima, o EMITENTE pagar� por esta C�dula de Cr�dito Banc�rio, ao BANCO, retro designado, os valores especificados, correspondentes ao referido cr�dito, desde j� reconhecendo o cr�dito utilizado como certo, l�quido e exig�vel, de acordo com o art. 29, inciso II, da Lei n� 10.931, 02/08/2004.</p>
			<p align='justify'>
			01. VALOR DO CR�DITO - O EMITENTE emite, neste ato, em favor do BANCO a presente C�dula de Cr�dito Banc�rio, t�tulo de cr�dito certo, l�quido e exig�vel  representativo da obriga��o de pagamento em dinheiro, decorrente do cr�dito aberto a favor do EMITENTE, no valor especificado no Quadro D supra.</p>			
			<p align='justify'>
			02. JUROS - Calculados e capitalizados dia a dia, incidentes sobre o saldo devedor atualizado, a partir do dia da libera��o dos recursos, exig�veis � taxa e na forma descritas no Quadro D - Caracter�sticas da C�dula.</p>
			<p align='justify'>
			03. FORMA DE PAGAMENTO - A remunera��o b�sica do valor devido, com base no �ndice estipulado no Quadro D - Caracter�sticas da C�dula, ser� apurada dia a dia, pelo crit�rio pro-rata tempore, sobre o valor do cr�dito aberto, sendo exig�vel juntamente com o valor utilizado desse cr�dito, nas datas especificadas no Quadro E - Forma de Pagamento.</p>
			<p align='justify'>
			3.1. O vencimento da primeira parcela de pagamento ocorrer� na data fixada no Quadro E do Quadro Resumo ou imediatamente ap�s o transcurso do prazo de 30 (trinta) dias, contados da data da libera��o, em conta corrente, do cr�dito a favor do Emitente, o que ocorrer por �ltimo. Por sua vez, as parcelas subseq�entes ter�o seus vencimentos nas datas previstas no Quadro. E do Quadro Resumo ou nos mesmos dias dos meses subseq�entes � data de vencimento da primeira parcela de pagamento, vencimento esse fixado de acordo com o crit�rio previsto na primeira parte deste item.</p>
			<p align='justify'>
			04. IMPOSTOS, TAXAS E CONTRIBUI��ES - Todos os impostos, taxas e contribui��es sobre o cr�dito aberto em favor do EMITENTE, correm por conta dele, EMITENTE.</p>
			<p align='justify'>
			05. JUROS DE MORA E MULTA - Em caso de mora no pagamento do principal e/ou juros, sem preju�zo do disposto nas demais cl�usulas desta C�dula, incidir� sobre o saldo devedor, comiss�o de perman�ncia, juros de mora, multa, corre��o monet�ria, suportando o EMITENTE, de igual forma, honor�rios advocat�cios, judiciais ou extrajudiciais, al�m de todas as despesas necess�rias � consecu��o das garantias prestadas por ele, EMITENTE.</p>
			<p align='justify'>
			5.1. A comiss�o de perman�ncia, calculada dia a dia ser� cobrada pelo BANCO, �s taxas m�ximas de mercado, conforme autoriza o Banco Central do Brasil.</p>
			<p align='justify'>
			5.2. Os juros de mora ser�o de 1% (um por cento) ao m�s, calculado dia a dia.</p>
			<p align='justify'>
			5.3. Sobre o saldo devedor total desta c�dula, incidir� multa de 2% (dois por cento).</p>
			<p align='justify'>
			5.4.	O EMITENTE arcar� com as despesas de publica��o dos editais do leil�o extrajudicial e comiss�o de leiloeiro, esta na base de 5% (cinco por cento) sobre o valor do montante em atraso ou do lance vencedor, se houver, na hip�tese de aliena��o do im�vel em leil�o p�blico.</p>
			<p align='justify'>
			5.5. DA CORRE��O MONET�RIA</p>
			<p align='justify'>
			5.5.1. O valor a ser pago pelo EMITENTE, representado por esta C�dula de Cr�dito Banc�rio, ser� atualizado monetariamente pelo IPCA (�ndice de Pre�os ao Consumidor Amplo, calculado pelo IBGE).</p>
			<p align='justify'>
			5.5.2. Na hip�tese de ocorr�ncia, de forma tempor�ria ou definitiva, de veda��o, extin��o e/ou suspens�o do �ndice de atualiza��o monet�ria, ser� adotado como �ndice substitutivo o �ndice Geral de Pre�os de Mercado (IGP-M), divulgado pela Funda��o Get�lio Vargas. </p>
			<p align='justify'>
			5.5.3. As partes, desde j�, convencionam, como condi��o do presente neg�cio, que, em face do princ�pio constitucional de respeito ao direito adquirido e ao ato jur�dico perfeito, n�o se aplicar� a esta C�dula de Cr�dito Banc�rio qualquer norma superveniente de congelamento ou defla��o, total ou parcial, do valor devido pelo EMITENTE.</p>
			<p align='justify'>
			06. GARANTIAS - Para assegurar o cumprimento de todas as obriga��es, principal e acess�rias, decorrente desta C�dula, o EMITENTE constitui, em favor do BANCO, as garantias especificadas nos Quadros C � Intervenientes Garantidor e Avalista e F - Garantia Real � Aliena��o Fiduci�ria de im�vel em garantia, conforme expressamente autoriza o art. 31 da Lei n� 10.931/2004.</p>
			<p align='justify'>
			6.1. O BANCO poder�, a qualquer tempo e a seu exclusivo crit�rio, exigir a constitui��o de outras garantias destinadas a assegurar o cumprimento das obriga��es contra�das em raz�o desta C�dula ou exigir o refor�o das garantias j� constitu�das.</p>
			<p align='justify'>
			6.2. Se o pedido de que trata a cl�usula anterior deixar de ser atendido pelo EMITENTE dentro do prazo de 5 (cinco) dias, contados do recebimento da notifica��o feita, considerar-se-� a d�vida vencida por antecipa��o, com todos os seus encargos, independentemente de qualquer outra comunica��o, notifica��o ou interpela��o.</p>
			<p align='justify'>
			6.3. DA GARANTIA FIDUCI�RIA IMOBILI�RIA</p>
			<p align='justify'>
			A aliena��o fiduci�ria imobili�ria em garantia, prevista nesta C�dula de Cr�dito Banc�rio, est� disciplinada pela Lei n� 9.514, de 20/11/97, que instituiu a Aliena��o Fiduci�ria de Coisa Im�vel, devendo as disposi��es contratuais, adiante estipuladas, serem interpretadas, para todos os efeitos legais, quer pelos contratantes, quer pelos �rg�os jurisdicionais competentes para a aplica��o da mencionada lei, na conformidade dos princ�pios, das normas e dos objetivos do regime jur�dico institu�do pela Lei n� 9.514/97, que os contratantes admitem ser de natureza especial.</p>
			<p align='justify'>
			6.3.1. Em garantia do t�tulo de cr�dito, ora emitido, em favor do BANCO, e do cumprimento de todas as obriga��es decorrentes desta opera��o de cr�dito, como prev� o art. 27 da Lei n� 10.931, de 02/08/2004, o EMITENTE aliena fiduciariamente ao BANCO, nos termos da Lei n� 9.514, de 20-11-97, o im�vel descrito e caracterizado no Quadro F, incorporando-se � garantia todas as acess�es e benfeitorias que acrescerem ao im�vel.</p>
			<p align='justify'>
			6.3.2. A garantia constitu�da viger� at� o pagamento integral, pelo EMITENTE, do valor nela representado, valor este reconhecido pela lei como certo, l�quido e exig�vel, conforme prev� o art. 28 da Lei n� 10.931, de 02/08/2004, seja pela soma aqui indicada, seja pelo saldo devedor demonstrado em planilha de c�lculo, obedecido o disposto no �2�, do art. 28 da Lei n� 10.931, de 02/08/2004.</p>
			<p align='justify'>
			6.3.3. Fica assegurado ao EMITENTE, enquanto adimplente, a livre utiliza��o, por sua conta e risco, do im�vel objeto da aliena��o fiduci�ria, respondendo ele, EMITENTE, antes e ap�s a emiss�o da presente C�dula de Cr�dito Banc�rio, pelas despesas relacionadas ao im�vel alienado ao BANCO em garantia, seja de que natureza for, em especial, todos os impostos, taxas, seguro, contribui��es condominiais e quaisquer outras contribui��es ou encargos que incidam ou venham a incidir sobre o im�vel ou que sejam inerentes � garantia.</p>
			<p align='justify'>
			6.3.4. Para os efeitos do art. 24, inciso VI, da Lei n� 9.514/97, as partes avaliam o bem alienado fiduciariamente no montante indicado no Quadro F desta C�dula de Cr�dito Banc�rio.</p>
			<p align='justify'>
			6.3.5. Qualquer acess�o ou benfeitoria, n�o importa de que esp�cie ou natureza, somente poder� ser introduzida pelo EMITENTE no im�vel alienado fiduciariamente mediante pr�via e expressa autoriza��o do BANCO, obrigando-se o EMITENTE, caso a obra seja autorizada, a obter as licen�as administrativas necess�rias, o CND-INSS e a averbar o aumento ou a diminui��o da �rea constru�da, sendo que, em qualquer hip�tese, os acr�scimos ocorridos se incorporar�o ao im�vel e ao seu valor, para fins de realiza��o do leil�o extrajudicial, n�o podendo o EMITENTE invocar direito de indeniza��o ou de reten��o.</p>
			<p align='justify'>
			6.3.6. Se o im�vel estiver locado, a loca��o poder� ser denunciada com prazo de trinta dias para desocupa��o, obrigando-se o EMITENTE, sob pena de vencimento antecipado da d�vida, a incluir no contrato de loca��o, celebrado ou a celebrar, que o locat�rio toma conhecimento de que: (a) a propriedade fiduci�ria do im�vel � titulada pelo BANCO; (b) eventual indeniza��o por benfeitorias, de qualquer esp�cie ou natureza, passar� a integrar o valor do lance vencedor em leil�o, n�o podendo ser pleiteado ao BANCO qualquer direito de indeniza��o ou de reten��o, n�o importa a que t�tulo ou pretexto; (c) sujeitar-se-� aos efeitos da a��o de reintegra��o na posse prevista no artigo 30 da Lei 9.514/97, independentemente de sua cita��o ou intima��o; (d) inexistir� qualquer direito de prefer�ncia e/ou de continuidade da loca��o, caso ocorra a consolida��o da propriedade em nome do BANCO e/ou a aliena��o do im�vel a terceiros em leil�o p�blico extrajudicial; (e) ap�s a a consolida��o da propriedade em nome do BANCO e/ou a aliena��o do im�vel a terceiros em leil�o p�blico extrajudicial, a t�tulo de taxa de utiliza��o do im�vel, pagar� ao BANCO ou ao adquirente o valor previsto no art. 37-A da Lei n� 9.514/97.</p>
			<p align='justify'>
			6.3.7. Ser� considerada ineficaz, e sem qualquer efeito perante o BANCO ou seus sucessores, a contrata��o ou a prorroga��o de loca��o de im�vel alienado fiduciariamente por prazo superior a um ano sem concord�ncia por escrito do BANCO. O EMITENTE, sempre que lhe for solicitado, dever� entregar ao BANCO c�pia do contrato de loca��o.</p>
			<p align='justify'>
			6.3.8. No prazo de 30 (trinta) dias, contados da data em que se efetivar a liquida��o total da d�vida, o BANCO outorgar� o pertinente Termo de Quita��o, sob pena de responder pelo pagamento de multa morat�ria equivalente a 0,5% (meio por cento) ao m�s, ou fra��o, sobre o valor de face da C�dula de Cr�dito Banc�rio, atualizado monetariamente, na conformidade do �ndice previsto neste contrato.</p>
			<p align='justify'>
			6.3.9. O cancelamento imobili�rio do registro da propriedade fiduci�ria, com a conseq�ente consolida��o na pessoa do EMITENTE da plena propriedade do im�vel, ser� de inteira responsabilidade e custo deste, fazendo-se � luz do aludido Termo de Quita��o.</p>
			<p align='justify'>
			6.3.10.	 Considerando a aliena��o fiduci�ria em garantia, aqui pactuada, o EMITENTE n�o poder� constituir �nus sobre o im�vel, que, por tal raz�o, � insuscet�vel de penhora, pois constitui patrim�nio afetado exclusivamente como garantia ao cumprimento do pagamento dos valores objeto da presente C�dula de Cr�dito Banc�rio.</p>
			<p align='justify'>
			6.3.11.  Ap�s o vencimento da d�vida, transcorrido o prazo de car�ncia de 5 (cinco) dias �teis, o Sr. Oficial de Registro de Im�veis expedir� intima��o ao EMITENTE para que, no prazo de 15 (quinze) dias, satisfa�a, a d�vida vencida, acrescida dos juros ora convencionados, das penalidades, dos demais encargos aqui previstos, dos encargos legais, inclusive tributos, as contribui��es condominiais eventualmente  imput�veis ao im�vel, al�m das despesas de cobran�a e de intima��o, tudo de acordo com o art. 26, ��1� e 2�, da Lei n� 9.514/97.</p>
			<p align='justify'>
			6.3.12. O procedimento de intima��o obedecer� aos seguintes requisitos: (a) ser� requerido pelo BANCO ao Oficial do competente Registro de Im�veis, indicando o valor vencido e n�o pago e penalidades morat�rias; (b) far-se-�, a crit�rio do Oficial do Registro de Im�veis, por interm�dio de seu preposto, ou pelo Oficial do Registro de T�tulos e Documentos da comarca da situa��o do im�vel ou do domic�lio de quem deva receb�-la, ou pelo correio, com aviso de recebimento firmado pelo EMITENTE, ou por quem deva receber a intima��o.</p>
			<p align='justify'>
			6.3.13. Se o destinat�rio da intima��o encontrar-se em local incerto e n�o sabido, certificado pelo Oficial do Registro de Im�veis ou pelo de T�tulos e Documentos, competir� ao primeiro promover sua intima��o por edital com prazo de 10 (dez) dias, contados da primeira divulga��o, publicada por tr�s dias, ao menos, pelo menos, em um dos jornais de maior circula��o no local do im�vel ou noutro de comarca de f�cil acesso, se, no local do im�vel, n�o houver imprensa com circula��o di�ria.</p>
			<p align='justify'>
			6.3.14.	A mora do EMITENTE verificar-se-� quando transcorrido o prazo de 15 (quinze) dias, contados da data em que for notificado para purgar as quantias em atraso.</p>
			<p align='justify'>
			6.3.15. N�o purgada a mora no prazo assinado o Oficial: (a) certificar� tal fato; (b) promover� o registro da consolida��o da propriedade em nome do BANCO mediante a pr�via apresenta��o da prova de recolhimento do imposto de transmiss�o de bens im�veis ou de direitos a eles relativos.</p>
			<p align='justify'>
			6.3.16. Uma vez consolidada a propriedade em seu nome, o BANCO, no prazo de trinta dias, contados da data do registro de que trata o � 7� do artigo 26 da Lei n� 9.514/97, promover� p�blico leil�o para a aliena��o do im�vel alienado fiduciariamente, respeitado o procedimento de que trata o art. 27 da Lei n� 9.514/97, procedimento este a seguir transcrito:</p> 
			<p align='justify'><b><i>
			<font color='#FFFFFF'>espa�o</font>�� 1� Se, no primeiro p�blico leil�o, o maior lance oferecido for inferior ao valor do im�vel, estipulado na forma do inciso VI do art. 24, ser� realizado o segundo leil�o, nos quinze dias seguintes.<br>
			<font color='#FFFFFF'>espa�o</font>� 2� No segundo leil�o, ser� aceito o maior lance oferecido, desde que igual ou superior ao valor da d�vida, das despesas, dos pr�mios de seguro, dos encargos legais, inclusive tributos, e das contribui��es condominiais.<br>
			<font color='#FFFFFF'>espa�o</font>� 3� Para os fins do disposto neste artigo, entende-se por:<br>
			I - d�vida: o saldo devedor da opera��o de aliena��o fiduci�ria, na data do leil�o, nele inclu�dos os juros convencionais, as penalidades e os demais encargos contratuais;<br>
			II - despesas: a soma das import�ncias correspondentes aos encargos e custas de intima��o e as necess�rias � realiza��o do p�blico leil�o, nestas compreendidas as relativas aos an�ncios e � comiss�o do leiloeiro. <br>
			<font color='#FFFFFF'>espa�o</font>� 4� Nos cinco dias que se seguirem � venda do im�vel no leil�o, o credor entregar� ao devedor a import�ncia que sobejar, considerando-se nela compreendido o valor da indeniza��o de benfeitorias, depois de deduzidos os valores da d�vida e das despesas e encargos de que tratam os �� 2� e 3�, fato esse que importar� em rec�proca quita��o, n�o se aplicando o disposto na parte final do art. 516 do C�digo Civil.<br>
			<font color='#FFFFFF'>espa�o</font>� 5� Se, no segundo leil�o, o maior lance oferecido n�o for igual ou superior ao valor referido no � 2�, considerar-se-� extinta a d�vida e exonerado o credor da obriga��o de que trata o � 4�.<br>
			<font color='#FFFFFF'>espa�o</font>� 6� Na hip�tese de que trata o par�grafo anterior, o credor, no prazo de cinco dias a contar da data do segundo leil�o, dar� ao devedor quita��o da d�vida, mediante termo pr�prio.<br>
			<font color='#FFFFFF'>espa�o</font>� 7o Se o im�vel estiver locado, a loca��o poder� ser denunciada com o prazo de trinta dias para desocupa��o, salvo se tiver havido aquiesc�ncia por escrito do fiduci�rio, devendo a den�ncia ser realizada no prazo de noventa dias a contar da data da consolida��o da propriedade no fiduci�rio, devendo essa condi��o constar expressamente em cl�usula contratual espec�fica, destacando-se das demais por sua apresenta��o gr�fica. (reda��o dada pelo art. 57 da Lei n� 10.931/04).<br>
			<font color='#FFFFFF'>espa�o</font>� 8o Responde o fiduciante pelo pagamento dos impostos, taxas, contribui��es condominiais e quaisquer outros encargos que recaiam ou venham a recair sobre o im�vel, cuja posse tenha sido transferida para o fiduci�rio, nos termos deste artigo, at� a data em que o fiduci�rio vier a ser imitido na posse.</i></b> (reda��o dada pelo art. 57 da Lei n� 10.931/04).</p>
			<p align='justify'>
			6.3.17. O EMITENTE restituir� o im�vel, livre e desimpedido de pessoas e/ou coisas, dentro do prazo de 10 (dez) dias, contados da consolida��o da propriedade fiduci�ria em nome do BANCO, sob pena de pagamento ao BANCO  ou ao adquirente do im�vel em leil�o da multa di�ria equivalente a 0,035% (trinta e cinco mil�simos por cento) sobre o valor do im�vel, como definido no Quadro  F � GARANTIA REAL sem preju�zo de sua responsabilidade pelo pagamento: (a) de todas as despesas de condom�nio, �gua, luz e g�s incorridas ap�s a data da realiza��o do p�blico leil�o; (b) de todas as despesas necess�rias � reposi��o do im�vel ao estado em que o recebeu.</p>
			<p align='justify'>
			6.3.18. N�o ocorrendo a desocupa��o do im�vel no prazo e forma ajustados, o BANCO, ou mesmo o adquirente do im�vel em leil�o, poder� requerer a sua reintegra��o na posse, que ser� concedida liminarmente, para que o im�vel seja desocupado no prazo m�ximo de 60 (sessenta) dias, desde que comprovada, mediante certid�o da matr�cula do im�vel, a consolida��o da plena propriedade em nome do BANCO, ou do registro do contrato celebrado em decorr�ncia do leil�o, conforme quem seja o autor da reintegra��o na posse, cumulada com a cobran�a do valor da taxa di�ria de ocupa��o e demais despesas previstas neste contrato.			</p>
			<p align='justify'>
			6.3.19. O EMITENTE tem ci�ncia inequ�voca quanto � desnecessidade de sua   intima��o pessoal, a respeito da data da realiza��o do leil�o extrajudicial. Caso, ele, EMITENTE, tenha interesse em acompanhar o leil�o extrajudicial, ser-lhe-� facultado solicitar, por escrito, informa��es junto ao BANCO, sem preju�zo, evidentemente, da continuidade plena do leil�o extrajudicial.</p>
			<p align='justify'>
			6.4. O EMITENTE, em face das condi��es, ora pactuadas, declara  que o im�vel alienado fiduciariamente esta livre de quaisquer  impostos ou taxas, sendo certo que a garantia, ora constitu�da,  permanecer� �ntegra e em pleno vigor at� haja cumprimento total de todas as obriga��es assumidas pelo EMITENTE, a favor do BANCO, quando, ent�o, se dar� a conseq�ente libera��o.</p>
			<p align='justify'>
			6.5. O EMITENTE se obriga a fazer constar da respectiva matr�cula, para todos os efeitos de direito, ter sido constitu�da esta garantia fiduci�ria.</p>
			<p align='justify'>
			6.6. Se o bem constitutivo da garantia for desapropriado, ou se for danificado ou perecer por fato imput�vel a terceiro, o credor sub-rogar-se-� no direito � indeniza��o devida pelo expropriante ou pelo terceiro causador do dano, at� o montante necess�rio para liquidar ou amortizar a obriga��o garantida.</p>
			<p align='justify'>
			6.7. Na hip�tese prevista no item 6.6 supra, faculta-se ao BANCO exigir a substitui��o da garantia, ou o seu refor�o, renunciando ao direito � percep��o do valor relativo � indeniza��o.</p>
			<p align='justify'>
			6.8. O BANCO poder�, ainda, exigir a substitui��o ou o refor�o da garantia, em caso de perda, deteriora��o ou diminui��o de seu valor.</p>
			<p align='justify'>
			6.9. O EMITENTE est� ciente e concorda que, para a abertura de cr�dito em seu favor, o BANCO necessita analisar seu hist�rico financeiro, consultar, elaborar e/ou atualizar seus dados cadastrais, bem como adotar as demais formalidades cab�veis, pelo que ser� devida a Tarifa de Abertura de Cr�dito - TAC sendo, ainda, de responsabilidade do EMITENTE todas as demais despesas deste contrato, bem como todos os tributos que incidem ou venham a incidir sobre a opera��o, especialmente o Imposto de Opera��es de Cr�dito - IOC, al�m daquelas que se fa�am necess�rias para o devido registro da aliena��o fiduci�ria na circunscri��o imobili�ria competente.</p>
			<p align='justify'>
			6.10. As partes autorizam desde j� o Sr. Oficial do Cart�rio de Registro de Im�veis competente  a proceder, �s expensas  do EMITENTE, a todas e quaisquer  averba��es e registros que tornarem necess�rios � perfeita e completa legaliza��o desta C�dula.</p>
			<p align='justify'>
			07. AMORTIZA��ES EXTRAORDIN�RIAS - O DEVEDOR poder�, a qualquer tempo, mediante pr�via e expressa anu�ncia do BANCO, efetuar a quita��o antecipada de qualquer parcela ou do saldo devedor integral do contrato. Nessa hip�tese, o DEVEDOR estar� sujeito ao pagamento de uma tarifa de liquida��o antecipada, no valor vigente � �poca da liquida��o, conforme tabela divulgada pelo BANCO.</p>
			<p align='justify'>
			08. VENCIMENTO ANTECIPADO - Operar-se-� de pleno direito, independentemente de interpela��o judicial ou extrajudicial, para os efeitos do art. 397 do C�digo Civil Brasileiro, o vencimento antecipado da totalidade do saldo devedor, principal e encargos desta c�dula, de responsabilidade do EMITENTE, al�m das demais previstas neste instrumento, nos seguintes casos:</p>
			<p align='justify'>
			<font color='#FFFFFF'>espa�o</font>a.) se ocorrer qualquer uma das causas previstas nos artigos 1425 e 333 do C�digo Civil Brasileiro;</p>
			<p align='justify'>
			<font color='#FFFFFF'>espa�o</font>b.) se for apurada a falsidade de qualquer declara��o, informa��o ou documento que houverem sido, respectivamente, firmados, prestados ou entregues pelo EMITENTE e/ou COOBRIGADOS GARANTIDORES;</p>
			<p align='justify'>
			<font color='#FFFFFF'>espa�o</font>c.) se qualquer t�tulo for objeto de protesto contra o EMITENTE;</p>
			<p align='justify'>
			<font color='#FFFFFF'>espa�o</font>d.) se o EMITENTE  sofrer justo requerimento de fal�ncia ou tiver esta decretada;</p>
			<p align='justify'>
			<font color='#FFFFFF'>espa�o</font>e.) se, em caso de morte, insolv�ncia ou interdi��o, ou fal�ncia dos COOBRIGADOS GARANTIDORES, -o EMITENTE n�o providenciar a sua substitui��o no prazo de 48 (quarenta e oito) horas contadas da data do recebimento da comunica��o que lhe for dirigida neste sentido, e;</p>
			<p align='justify'>
			<font color='#FFFFFF'>espa�o</font>f.) se o EMITENTE deixar de cumprir qualquer obriga��o decorrente das condi��es desta C�dula ou da Lei.</p>
			<p align='justify'>
			8.1. Al�m das demais hip�teses previstas em Lei e neste instrumento, de vencimento antecipado da d�vida, com a imediata exigibilidade do cr�dito, estes se verificar�o se for comprovada:</p>
			<p align='justify'>
			<font color='#FFFFFF'>espa�o</font>a.)	a inclus�o, em rela��o ao EMITENTE, em qualquer �rg�o de restri��o ao cr�dito</p>
			<p align='justify'>
			<font color='#FFFFFF'>espa�o</font>b.) a falsidade de qualquer declara��o do EMITENTE e/ou COOBRIGADO GARANTIDOR, contida nesta C�dula.</p>
			<p align='justify'>
			<font color='#FFFFFF'>espa�o</font>c) se vier o EMITENTE a  compromissar a venda, onerar ou constituir   �nus real, de  qualquer natureza, sobre parte ou totalidade do im�vel alienado em garantia ;</p>
			<p align='justify'>
			<font color='#FFFFFF'>espa�o</font>d) se, no curso de qualquer a��o ou execu��o, inclusive expropriat�ria, for  determinada medida judicial que afete diretamente o bem dado em garantia, no todo ou em parte, sem oferecer o EMITENTE ao BANCO - a respectiva substitui��o da garantia;</p>
			<p align='justify'>
			<font color='#FFFFFF'>espa�o</font>e) se, depreciando-se por qualquer motivo, o bem objeto da garantia o EMITENTE n�o o substituir, ap�s devidamente intimado por simples carta protocolada, sendo que o BANCO ter� a faculdade de recusar qualquer novo bem oferecido em garantia, sem especificar as raz�es da recusa; </p>
			<p align='justify'>
			<font color='#FFFFFF'>espa�o</font>f) se, o EMITENTE ou ANUENTE tiver requerida ou declarada insolv�ncia ou fal�ncia, ou de outra forma, tiver caracterizada a sua insolv�ncia;</p>
			<p align='justify'>
			<font color='#FFFFFF'>espa�o</font>g) se faltar o EMITENTE ao pagamento, nas �pocas pr�prias, dos valores  aven�ados e devidos nos respectivos vencimentos,  dos impostos, taxas e  demais encargos incidentes ou que venham a incidir sobre o im�vel alienado fiduciariamente ou deixar de atender intima��o das autoridades fiscais ou administrativas concernentes ao mesmo im�vel;</p>
			<p align='justify'>
			<font color='#FFFFFF'>espa�o</font>h) se deixar o EMITENTE de promover em companhia id�nea, dentro do prazo de at� 30 (trinta) dias, contados desta data, o seguro do  bem alienado fiduciariamente, contra risco de fogo  e desabamento, bem como das benfeitorias e acess�es nele existentes, obedecido o disposto no art. 36 da Lei n� 10.931/2004;</p>
			<p align='justify'>
			<font color='#FFFFFF'>espa�o</font>i) se o ANUENTE e o EMITENTE, vierem a inadimplir qualquer cl�usula ou condi��o de tal contrato.</p>
			<p align='justify'>
			09. INADIMPLEMENTO - O n�o pagamento, no respectivo vencimento, de qualquer das parcelas de amortiza��o do principal e respectivos encargos ou o inadimplemento de qualquer obriga��o assumida pelo EMITENTE, na presente C�dula, determinar� o vencimento antecipado do total do saldo devedor em aberto, principal atualizado e encargos acrescido dos juros morat�rios, da multa, dos honor�rios advocat�cios e outras eventuais despesas decorrentes do atraso, que se tornar�o imediatamente exig�veis. Em tal hip�tese, � facultado ao BANCO o direito de proceder a execu��o de qualquer uma ou todas as garantias vinculadas a esta C�dula ou que vierem a s�-lo, podendo tais garantias ser, a qualquer tempo, excutidas at� final e integral liquida��o do d�bito apurado.</p>
			<p align='justify'>
			10. O EMITENTE se responsabiliza por todas as despesas e custos decorrentes do registro desta C�dula, bem como da respectiva garantia nos cart�rios competentes.</p>
			<p align='justify'>
			11. A n�o utiliza��o pelo BANCO de qualquer dos direitos ou faculdades que lhe concedam a Lei e esta c�dula, n�o importa em ren�ncia dos mesmos direitos ou faculdades, sendo mera toler�ncia ou reserva para faz�-los prevalecer em qualquer outro momento ou oportunidade.</p>
			<p align='justify'>
			12. O BANCO fica expressamente autorizado a informar os dados relativos a todas as obriga��es assumidas pelo EMITENTE junto ao BANCO, para constarem de cadastros compartilhados pelo BANCO com outras institui��es conveniadas para tanto, administrados pelo Serasa ou por outras entidades de prote��o ao cr�dito. O BANCO e tais outras institui��es ficam expressamente autorizadas a disponibilizar e intercambiar entre si informa��es sobre obriga��es contra�das pelo EMITENTE, o que � de utilidade aos seus interesses. O EMITENTE declara tamb�m que est� ciente que o BANCO deve fornecer ao Banco Central do Brasil, informa��es sobre a presente opera��o, ou seja, d�vida a vencer, vencida e registrada como preju�zo.</p>
			<p align='justify'>
			13. Esta C�dula obriga as partes, seus herdeiros e sucessores.</p>
			<p align='justify'>
			As partes elegem o Foro de S�o Paulo, Capital, como o competente para dirimir quaisquer d�vidas decorrentes da presente.</p>
			<p align='justify'>
			Pelo presente instrumento, firmado pelas 02 (duas) testemunhas abaixo, em 3 (tr�s) vias de igual teor, as partes acima nomeadas e qualificadas e abaixo assinadas, t�m entre si justo e avan�ado a presente mediante as cl�usulas deste instrumento.</p>
			<p align='justify'><font color='#FFFFFF'>LN</font></p>
			<p align='justify'>".ucwords(strtolower($p_cidade)).", ".data_extenso()."</p>
			<p align='justify'><font color='#FFFFFF'>LN</font></p>
			<p align='justify'>EMITENTE(S):</p>
			<p align='justify'><font color='#FFFFFF'>LN</font></p>".assinaturaemit($prop_tipo,$cod_ppst).assinaturacoobgr($prop_tipo).assinaturacoobav($prop_tipo)."
			<p align='justify'><font color='#FFFFFF'>LN</font></p>
			<p align='justify'>CREDOR:</p>
			<p align='justify'><font color='#FFFFFF'>LN</font></p>
			<table border='0' width='728'>
				<tr>
					<td colspan='2' width='364' align='center'>_____________________________________</td>
					<td colspan='3' width='364' align='center'>_____________________________________</td>
				</tr>
				<tr>
					<td colspan='2' width='364' align='center'>BANCO PANAMERICANO S/A</td>
					<td colspan='3' width='364' align='center'>BANCO PANAMERICANO S/A</td>
				</tr>
			</table>
			<p align='justify'><font color='#FFFFFF'>LN</font></p>
			<p align='justify'>TESTEMUNHAS:</p>
			<p align='justify'><font color='#FFFFFF'>LN</font></p>
			<table border='0' width='728'>
				<tr>
					<td colspan='2' width='364' align='center'>_____________________________________</td>
					<td colspan='3' width='364' align='center'>_____________________________________</td>
				</tr>
				<tr>
					<td colspan='2' width='364' ><font color='#FFFFFF'>esp</font>Nome:</td>
					<td colspan='3' width='364' ><font color='#FFFFFF'>esp</font>Nome:</td>
				</tr>
				<tr>
					<td colspan='2' width='364' ><font color='#FFFFFF'>esp</font>RG:</td>
					<td colspan='3' width='364' ><font color='#FFFFFF'>esp</font>RG:</td>
				</tr>
				<tr>
					<td colspan='2' width='364' ><font color='#FFFFFF'>esp</font>CPF:</td>
					<td colspan='3' width='364' ><font color='#FFFFFF'>esp</font>CPF:</td>
				</tr>
			</table>			";
			
		//Instanciation of inherited class
		$pdf=new HTML2FPDF();
		//$pdf=new FPDF();
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
	
		$pdf->Cell(0,5,'C�DULA DE CR�DITO BANC�RIO',0,2,'C');
		//$pdf->WriteHTML($titulo);
		$pdf->WriteHTML($texto);
		$pdf->Ln(0);
		
		$pdf->Output();
		

?>