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
$conexao = mysql_connect($BD_SERVIDOR,$BD_USUARIO,$BD_SENHA) or die("ERRO: conexão não realizada");
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
	$procurador="Sr.(a) ".ucwords($prop_nome)." é neste ato representado (a) por seu (sua) procurador (a) ".$proc_info;
}else
{
	$procurador='';
}
//////////////////////////////////////////////////////////////////////////////////////////	
if($prop_doc==1){$pdoc="Carteira de Identidade de Estrangeiro";}
if($prop_doc==2){$pdoc="Carteira de Identidade dos Juízes";}
if($prop_doc==3){$pdoc="Carteira Funcional Ministério Público";}
if($prop_doc==4){$pdoc="Carteira Identidade Expedida p/ Conselho Profissionais Liberais";}
if($prop_doc==5){$pdoc="Carteira de Identidade Expedida pelo Min. Relações Exteriores";}
if($prop_doc==6){$pdoc="Carteira de Identidade Expedida por Exército, Marinha, Aeronáutica";}
if($prop_doc==7){$pdoc="Carteira de Identidade Expedida pelo Ministério Público";}
if($prop_doc==8){$pdoc="Carteira de Identidade Policial Expedida pela Polícia Federal";}
if($prop_doc==9){$pdoc="Carteira de Identidade";}
if($prop_doc==10){$pdoc="Carteira Nacional de Habilitação";}
if($prop_doc==11){$pdoc="Carteira de Trabalho e Previdência Social";}
if($prop_doc==12){$pdoc="Certificado de Reservista";}
if($prop_doc==13){$pdoc="Certidão de Nascimento para Menor Representado ";}
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
if($conj_doc==2){$cdoc="Carteira de Identidade dos Juízes";}
if($conj_doc==3){$cdoc="Carteira Funcional Ministério Público";}
if($conj_doc==4){$cdoc="Carteira Identidade Expedida p/ Conselho Profissionais Liberais";}
if($conj_doc==5){$cdoc="Carteira de Identidade Expedida pelo Min. Relações Exteriores";}
if($conj_doc==6){$cdoc="Carteira de Identidade Expedida por Exército, Marinha, Aeronáutica";}
if($conj_doc==7){$cdoc="Carteira de Identidade Expedida pelo Ministério Público";}
if($conj_doc==8){$cdoc="Carteira de Identidade Policial Expedida pela Polícia Federal";}
if($conj_doc==9){$cdoc="Carteira de Identidade";}
if($conj_doc==10){$cdoc="Carteira Nacional de Habilitação";}
if($conj_doc==11){$cdoc="Carteira de Trabalho e Previdência Social";}
if($conj_doc==12){$cdoc="Certificado de Reservista";}
if($conj_doc==13){$cdoc="Certidão de Nascimento para Menor Representado ";}
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
	if($imov_foreiro=='S'){$foreiro="encontra-se no dossiê do financiamento";}else{$foreiro="não se aplica";}
	if($imov_flagcond=='S'){$cnd="Condomínio ".$imov_nomecond;}else{$cnd="não se aplica.";}

	
#########################################################################################################
#################################### NACIONALIDADES #####################################################
################################### ENDEREÇOS ###########################################################

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
#______________________________________IMÓVEL________________________________________________
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
############################# QUALIFICAÇÕES ASSINATURAS ##############################################
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
############################# QUALIFICAÇÕES EMITENTE #################################################
################################### EMITENTE MASCULINO ##########################################
if($prop_tipo==1)
{
if($prop_sex=='M')
{
	//EST. CIVIL CASADO
	if($prop_civil==2)
	{
//________________________ Comunhão Parcial de Bens antes da lei_____________________________________
		if($prop_reg==7)
		{
			if($prop_nacional==$conj_nacional)
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv!=$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}else
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>,  ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv!=$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}
		}
//___________________________________________________________________________________________________________
//__________________________ Comunhão Parcial de Bens depois da lei_____________________________________
		if($prop_reg==1)
		{
			if($prop_nacional==$conj_nacional)
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv!=$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}else
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv!=$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}

		}
//___________________________________________________________________________________________________
//______________________________ Comunhão Universal de Bens antes da lei__________________________________
		if($prop_reg==2)
		{
				if($prop_nacional==$conj_nacional)
				{
						if($prop_doc==9 && $conj_doc==9)
						{
								if($prop_capciv==$conj_capciv)
								{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunhão Universal de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
								}else
								{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunhão Universal de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
								}
						}else
						{
								if($prop_capciv!=$conj_capciv)
								{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunhão Universal de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
								}else
								{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunhão Universal de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
								}
						}
				}else
				{
						if($prop_doc==9 && $conj_doc==9)
						{
								if($prop_capciv==$conj_capciv)
								{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunhão Universal de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
								}else
								{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunhão Universal de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
								}
						}else
						{
								if($prop_capciv!=$conj_capciv)
								{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunhão Universal de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
								}else
								{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunhão Universal de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
								}
						}
				}
		}
//________________________________________________________________________________________________________
//_____________________________ Comunhão Universal de Bens depois da lei_________________________________
		if($prop_reg==3)
		{
			if($prop_nacional==$conj_nacional)
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunhão Universal de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunhão Universal de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv!=$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunhão Universal de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunhão Universal de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}else
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunhão Universal de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunhão Universal de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunhão Universal de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunhão Universal de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}
		}
//_________________________________________________________________________________________________		
//________________________________________Separação de Bens com pacto_________________________________
		if($prop_reg==5)
		{
			if($prop_nacional==$conj_nacional)
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Separação de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Separação de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv!=$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Separação de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Separação de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}else
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Separação de Bens de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Separação de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv!=$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Separação de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Separação de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}
		}
//_______________________________________________________________________________________________________		
//_______________________________________ Separação de Bens obrigatórioa _________________________________
		if($prop_reg==6)
		{
			if($prop_nacional==$conj_nacional)
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Separação Obrigatória de bens, nos termos do artigo 1641 do Código Civil Brasileiro,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Separação Obrigatória de bens, nos termos do artigo 1641 do Código Civil Brasileiro,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv!=$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Separação Obrigatória de bens, nos termos do artigo 1641 do Código Civil Brasileiro,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Separação Obrigatória de bens, nos termos do artigo 1641 do Código Civil Brasileiro,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}else
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Separação Obrigatória de bens, nos termos do artigo 1641 do Código Civil Brasileiro,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Separação Obrigatória de bens, nos termos do artigo 1641 do Código Civil Brasileiro,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv!=$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Separação Obrigatória de bens, nos termos do artigo 1641 do Código Civil Brasileiro,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Separação Obrigatória de bens, nos termos do artigo 1641 do Código Civil Brasileiro,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
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
	// EST.CIVIL UNIÃO ESTÁVEL
	if($prop_civil==1)
	{
			$emitente="<b>".maiusculo($prop_nome)."</b>, (nacionalidade), casados pelo regime de União Estável, portador(a) da carteira  de identidade nr. ".$prop_numdoc.", expedida por ".$prop_emissor.", inscrito(a) no CPFMF sob o nr. ".$prop_cpf.", residente e domiciliado(a) no(a) ".$prop_ender."<br>".$proc_info;
	}
}//fim if ($prop_sex=='M')
############################################## EMITENTE FEMININO ########################################
if($prop_sex=='F')
{
//EST. CIVIL CASADO
	if($prop_civil==2)
	{
//________________________ Comunhão Parcial de Bens antes da lei_____________________________________
		if($prop_reg==7)
		{
			if($prop_nacional==$conj_nacional)
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portador da ".$prop_doc." nr. ".$prop_numdoc." expedida por ".$prop_emissor.", ele portadora da ".$conj_doc." nr. ".$conj_numdoc." expedida por ".$conj_emissor.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portador da ".$prop_doc." nr. ".$prop_numdoc." expedida por ".$prop_emissor.", ele portadora da ".$conj_doc." nr. ".$conj_numdoc." expedida por ".$conj_emissor.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}else
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portador da ".$prop_doc." nr. ".$prop_numdoc." expedida por ".$prop_emissor.", ele portadora da ".$conj_doc." nr. ".$conj_numdoc." expedida por ".$conj_emissor.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$conj_capciv.", ".$conj_nacional.", casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portador da ".$prop_doc." nr. ".$prop_numdoc." expedida por ".$prop_emissor.", ele portadora da ".$conj_doc." nr. ".$conj_numdoc." expedida por ".$conj_emissor.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}
		}
//___________________________________________________________________________________________________________
//__________________________ Comunhão Parcial de Bens depois da lei_____________________________________
		if($prop_reg==1)
		{
			if($prop_nacional==$conj_nacional)
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcsm.",  ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv!=$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portador da ".$prop_doc." nr. ".$prop_numdoc." expedida por ".$prop_emissor.", ele portadora da ".$conj_doc." nr. ".$conj_numdoc." expedida por ".$conj_emissor.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portador da ".$prop_doc." nr. ".$prop_numdoc." expedida por ".$prop_emissor.", ele portadora da ".$conj_doc." nr. ".$conj_numdoc." expedida por ".$conj_emissor.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}else
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portador da ".$prop_doc." nr. ".$prop_numdoc." expedida por ".$prop_emissor.", ele portadora da ".$conj_doc." nr. ".$conj_numdoc." expedida por ".$conj_emissor.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portador da ".$prop_doc." nr. ".$prop_numdoc." expedida por ".$prop_emissor.", ele portadora da ".$conj_doc." nr. ".$conj_numdoc." expedida por ".$conj_emissor.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}

		}
//___________________________________________________________________________________________________
//______________________________ Comunhão Universal de Bens antes da lei__________________________________
		if($prop_reg==2)
		{
				if($prop_nacional==$conj_nacional)
				{
						if($prop_doc==9 && $conj_doc==9)
						{
								if($prop_capciv==$conj_capciv)
								{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunhão Universal de Bens,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
								}else
								{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunhão Universal de Bens,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
								}
						}else
						{
								if($prop_capciv==$conj_capciv)
								{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunhão Universal de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
								}else
								{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunhão Universal de Bens,  em ".$prop_dtcsm.", ele ".$dp_cargo.", ela ".$conj_profissao.", ele portador da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ela portadora da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
								}
						}
				}else
				{
						if($prop_doc==9 && $conj_doc==9)
						{
								if($prop_capciv==$conj_capciv)
								{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunhão Universal de Bens,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
								}else
								{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunhão Universal de Bens,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
								}
						}else
						{
								if($prop_capciv==$conj_capciv)
								{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunhão Universal de Bens,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portadora da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ele portador da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
								}else
								{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$prop_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunhão Universal de Bens,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portadora da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ele portador da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
								}
						}
				}
		}
//________________________________________________________________________________________________________
//_____________________________ Comunhão Universal de Bens depois da lei_________________________________
		if($prop_reg==3)
		{
			if($prop_nacional==$conj_nacional)
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunhão Universal de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunhão Universal de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunhão Universal de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portadora da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ele portador da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Comunhão Universal de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portadora da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ele portador da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}else
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunhão Universal de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunhão Universal de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunhão Universal de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portadora da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ele portador da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunhão Universal de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portadora da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ele portador da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}
		}
//_________________________________________________________________________________________________		
//________________________________________Separação de Bens com pacto_________________________________
		if($prop_reg==5)
		{
			if($prop_nacional==$conj_nacional)
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Separação de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Separação de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Separação de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portadora da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ele portador da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Separação de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portadora da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ele portador da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}else
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Separação de Bens de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Separação de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;

							}
					}else
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Separação de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portadora da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ele portador da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$prop_nacional." ele ".$conj_nacional.", casados pelo regime de Separação de Bens,  em ".$prop_dtcsm.", conforme escritura de pacto antenupcial lavrada no ".$prop_lavrareg.", no Livro ".$prop_lvreg.", Folhas ".$prop_flsreg.", em ".$prop_dtreg.", registrada sob o nr. ".$prop_numreg."-Registro Auxiliar do  ".$prop_numlocreg."o. Cartório de Registro de Imóveis de ".$prop_locreg.", em ".$prop_dtlocreg.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portadora da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ele portador da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}
		}
//_______________________________________________________________________________________________________		
//_______________________________________ Separação de Bens obrigatórioa _________________________________
		if($prop_reg==6)
		{
			if($prop_nacional==$conj_nacional)
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Separação Obrigatória de bens, nos termos do artigo 1641 do Código Civil Brasileiro,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Separação Obrigatória de bens, nos termos do artigo 1641 do Código Civil Brasileiro,  em ".$prop_dtcsm.",  ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Separação Obrigatória de bens, nos termos do artigo 1641 do Código Civil Brasileiro,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portadora da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ele portador da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." ,casados pelo regime de Separação Obrigatória de bens, nos termos do artigo 1641 do Código Civil Brasileiro,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portadora da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ele portador da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}
			}else
			{
					if($prop_doc==9 && $conj_doc==9)
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Separação Obrigatória de bens, nos termos do artigo 1641 do Código Civil Brasileiro,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Separação Obrigatória de bens, nos termos do artigo 1641 do Código Civil Brasileiro,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", portadores das carteiras  de identidade nrs. ".$prop_numdoc." e ".$conj_numdoc.", expedidas por ".maiusculo($prop_emissor)."-".$prop_uf."/".maiusculo($conj_emissor)."-".$prop_uf.", respectivamente, inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}
					}else
					{
							if($prop_capciv==$conj_capciv)
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Separação Obrigatória de bens, nos termos do artigo 1641 do Código Civil Brasileiro,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portadora da ".$prop_doc." nr. ".$prop_numdoc." expedida por ".$prop_emissor.", ele portadorda ".$conj_doc." nr. ".$conj_numdoc." expedida por ".$conj_emissor.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
							}else
							{
								$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$prop_nacional.", ele ".$conj_nacional.", casados pelo regime de Separação Obrigatória de bens, nos termos do artigo 1641 do Código Civil Brasileiro,  em ".$prop_dtcsm.", ela ".$dp_cargo.", ele ".$conj_profissao.", ela portadora da ".$pdoc." nr. ".$prop_numdoc." expedida por ".maiusculo($prop_emissor)."-".$prop_uf.", ele portador da ".$cdoc." nr. ".$conj_numdoc." expedida por ".maiusculo($conj_emissor)."-".$prop_uf.", inscritos no CPFMF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.".<br>".$procurador;
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
	// EST.CIVIL UNIÃO ESTÁVEL
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
############################# QUALIFICAÇÃO AVALISTA #####################################################
if($conj_cpf==$av_cpf)
{
	$cobgav="não se aplica.";
}else
{
	if($av_nome!='')
	{
		$cobgav="<b>".maiusculo($av_nome)."</b>, ".$av_civil.", portador(ra) da ".$av_doc." nr. ".$prop_numdoc.", expedida por ".$prop_emissor.", inscrito(a) no CPFMF sob o nr. ".$prop_cpf.", residente e domiciliado(a) ".$enderecoav."."; 
	}else
	{
		$cobgav="não se aplica.";
	}
}

######################################################################################################
######################################### QUALIFICAÇÃO IMÓVEL OBJETO DE GARANTIA ######################
if($imov_tipo=='A')
{
	if($imov_vagas=='')
	{
		if($imov_flagbloco=='S')
		{
			$imovgarantia="<b>APARTAMENTO NR. ".$prop_apart."</b>, localizado no bloco: ".$bloco_imov.", situado na(o) ".$imov_lograd." ".$imov_ender." nr. ".$imov_num.", ".$imov_cidade."-".$imov_uf.". Imóvel perfeitamente descrito e caracterizado na matrícula nr. ".$imov_matric."  do ".$numloc_imov."º Cartório de Registro de Imóveis de ".ucwords(strtolower($imov_cidade)).".";
		}else
		{
			$imovgarantia="<b>APARTAMENTO NR. ".$prop_apart."</b>, situado na(o) ".$imov_lograd." ".$imov_ender." nr. ".$imov_num.", ".$imov_cidade."-".$imov_uf." . Imóvel perfeitamente descrito e caracterizado na matrícula nr. ".$imov_matric."  do ".$numloc_imov."º Cartório de Registro de Imóveis de ".ucwords(strtolower($imov_cidade)).".";
		}
	}else
	{
		if($imov_flagbloco=='S')
		{
			$imovgarantia="<b>APARTAMENTO NR. ".$prop_apart."</b>, localizado no bloco: ".$bloco_imov.", situado na(o) ".$imov_lograd." ".$imov_ender." nr. ".$imov_num.", ".$imov_cidade."-".$imov_uf.". Possui ".$imov_vagas." vaga(s) de garagem. Imóvel perfeitamente descrito e caracterizado na matrícula nr. ".$imov_matric."  do ".$numloc_imov."º Cartório de Registro de Imóveis de ".ucwords(strtolower($imov_cidade)).".";
		}else
		{
			$imovgarantia="<b>APARTAMENTO NR. ".$prop_apart."</b>, situado na(o) ".$imov_lograd." ".$imov_ender." nr. ".$imov_num.", ".$imov_cidade."-".$imov_uf.". Possui ".$imov_vagas." vaga(s) de garagem. Imóvel perfeitamente descrito e caracterizado na matrícula nr. ".$imov_matric."  do ".$numloc_imov."º Cartório de Registro de Imóveis de ".ucwords(strtolower($imov_cidade)).".";
		}	
	}
}
if($imov_tipo=='L')
{
	$imovgarantia="<b>LOJA NR.".$imov_num."</b>, localizada no(a) ".$enderecoimov." Imóvel perfeitamente descrito e caracterizado na matrícula nr. ".$imov_matric."  do ".$numloc_imov."º Cartório de Registro de Imóveis de ".ucwords(strtolower($imov_cidade)).".";
}
if($imov_tipo=='C')
{
	$imovgarantia="<b>CASA E RESPECTIVO TERRENO</b>, localizados no(a) ".$enderecoimov." Imóvel perfeitamente descrito e caracterizado na matrícula nr. ".$imov_matric."  do ".$numloc_imov."º Cartório de Registro de Imóveis de ".ucwords(strtolower($imov_cidade)).".";
}
if($imov_tipo=='P')
{
	$imovgarantia="<b>PRÉDIO, localizado no(a) ".$enderecoimov." Imóvel perfeitamente descrito e caracterizado na matrícula nr. ".$imov_matric."  do ".$numloc_imov."º Cartório de Registro de Imóveis de ".ucwords(strtolower($imov_cidade)).".";
}
if($imov_tipo=='T')
{
	$imovgarantia="<b>TERRENO</b>, localizado no(a) ".$enderecoimov." Imóvel perfeitamente descrito e caracterizado na matrícula nr. ".$imov_matric."  do ".$numloc_imov."º Cartório de Registro de Imóveis de ".ucwords(strtolower($imov_cidade)).".";
}
if($prop_tipo==1)
{
	$coobrigado_garantidor=maiusculo($conj_nome);
}
if($prop_tipo==2)
{
	$coobrigado_garantidor="não se aplica";
	$cobgav="não se aplica";
}
##########################################################################################################
##########################################################################################################
#										CÉDULA DE CRÉDITO BANCÁRIO									
##########################################################################################################		
		//$titulo="<table border='1' width='728'>
					//<tr>
						//<td> align='center'><b>CONTRATO DE COMPRA E VENDA DE IMÓVEL COM FINANCIAMENTO, ALIENAÇÃO FIDUCIÁRIA DE IMÓVEL E OUTRAS AVENÇAS<b></td>
					//</tr>
				//</table>";

		$texto="<font face='Arial' size='10'>
				<table border='1' width='728'>
					<tr>
						<td colspan='2' width='364'><b>1) N° ".$numpropcg."</td>
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
						<td colspan='5'><font color='#FFFFFF'>LN</font><br><b>BANCO PANAMERICANO S/A</b>, com sede na Av. Paulista, 2.240, São Paulo - SP, inscrito no C.N.P.J. sob nº 59.285.411/0001-13, doravante denominado simplesmente BANCO.<br><font color='#FFFFFF'>LN</font></td>
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
						<td colspan='5' align='center'><b>D - CARACTERÍSTICAS DA CÉDULA</b>
						</td>
					</tr>
					<tr>
						<td colspan='5'><font color='#FFFFFF'>LN</font><br>VALOR DO CRÉDITO ABERTO A FAVOR DO EMITENTE - R$ ".formataMoeda($valfinan)." (".extenso($valfinan).") <br><font color='#FFFFFF'>LN</font></td>
					</tr>
					<tr>
						<td colspan='2'><font color='#FFFFFF'>LN</font><br>TAXA DE JUROS ao ano&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".str_replace('.',',',$taxaanual)." %<br><font color='#FFFFFF'>LN</font></td>
						<td colspan='3'>TAXA DE JUROS ao mês&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".str_replace('.',',',$taxajuro)." %</td>
					</tr>
					<tr>
						<td colspan='5'><font color='#FFFFFF'>LN</font><br>DATA DA LIBERAÇÃO: 05 (cinco) dias úteis do conhecimento do registro da garantia.</td>
					</tr>
					<tr>
						<td colspan='5' ><font color='#FFFFFF'>LN</font><br>FINALIDADE: ABERTURA DE CRÉDITO EM FAVOR DO EMITENTE</td>
					</tr>
					<tr>
						<td colspan='5'><font color='#FFFFFF'>LN</font><br>PERIODICIDADE DE CAPITALIZAÇÃO DOS JUROS: MENSAL </td>
					</tr>
					<tr>
						<td colspan='5' ><font color='#FFFFFF'>LN</font><br>ÍNDICE DE CORREÇÃO MONETÁRIA: As parcelas são fixas. A correção monetária incidirá somente na hipótese de inadimplemento contratual e para o fim de correção anual do valor atribuído ao imóvel para a venda em público leilão (art. 24, inciso VI, da Lei nº 9.514/97)	</td>
					</tr>
					<tr>
						<td colspan='5'><font color='#FFFFFF'>LN</font><br>OUTROS ENCARGOS INCIDENTES</td>
					</tr>
					<tr>
						<td colspan='5' ><font color='#FFFFFF'>LN</font><br>TAXAS:<br>TAC – Tarifa de Abertura de crédito: R$ ".$tac." <br>TEC:- Tarifa de Emissão do Carnê : R$ 4,95 (quatro reais e noventa e cinco centavos) por folha <br>Tarifa de Débito em Conta Corrente: Não tem<br></td>
					</tr>
					<tr>
						<td colspan='5' align='center'><b>E – FORMA DE PAGAMENTO EM R$ - PRINCIPAL MAIS ENCARGOS</b></td>
					</tr>
					<tr>
						<td align='center'>MENSAL<br><br>sim</td>
						<td align='center'>Nº DE<br>PARCELAS<br><br>".$prop_prazo."</td>
						<td align='center'>VALOR DA<br>PARCELA<br><br>R$ ".$prestacao."</td>
						<td align='center'>VENCIMENTO DA 1ª PARCELA<br>".$dtvenc."</td>
						<td align='center'>VENCIMENTO DA ÚLTIMA PARCELA<br>".somardata(date("d/m/Y"),0,$prop_prazo,0)."<br><font color='#FFFFFF'>LN</font><br></td>
					</tr>
					<tr>
						<td colspan='5'><font color='#FFFFFF'>LN</font><br>VENCIMENTO:  30 dias após a CCB.<br><font color='#FFFFFF'>LN</font> </td>
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

						<td colspan='5'><font color='#FFFFFF'>LN</font><br>DOCUMENTOS DO IMÓVEL OBJETO DA ALIENAÇÃO FIDUCIÁRIA:<br><font color='#FFFFFF'>LN</font><br>
						Certidão da Matrícula e negativa de ônus atualizada: Expedida em: ".$certonus."<br>
						Locadêmico (CAT e guia recolhida): ".$foreiro.".<br>
						Plantas Aprovadas: não se aplica.<br>
						Certidão negativa de IPTU: ".$certiptu."<br>
						Carnê do exercício vigente com as prestações pagas: encontra-se no dossiê de financiamento.<br>
						Especificação de Condomínio: ".$cnd.".<br>
						Certidão negativa de débitos condominiais (taxas ordinárias e extraordinárias), com firma reconhecida, acompanhada da ATA de ELEIÇÃO DO SÍNDICO: encontra-se no dossiê de financiamento.<br>
						3 últimas contas de luz: encontra-se no dossiê de financiamento.<br>
						3 últimas contas de gás: não se aplica.
						</td>
					</tr>
					<tr>
						<td colspan='5'><font color='#FFFFFF'>LN</font><br>VALOR DE AVALIAÇÃO DO IMÓVEL PARA TODOS OS EFEITOS LEGAIS: R$ ".formataMoeda($valor_aval)." (".extenso($valor_aval).")<br><font color='#FFFFFF'>LN</font> </td>
					</tr>
					<tr>
						<td colspan='5' align='center'><b>G – DOS SEGUROS</b></td>
					</tr>
					<tr rowspan='2'>
						<td colspan='5'>DO SEGURO DE PESSOA<br>O EMITENTE, após análise prévia de proposta escrita correspondente e da aceitação de seus elementos, contratou Seguro de Vida e Invalidez Permanente, tendo o BANCO como favorecido, para o fim de recebimento do capital segurado e sua aplicação na liquidação total ou parcial das obrigações de pagamento dos valores representados pela presente Cédula de Crédito Bancário. Para o pagamento do prêmio devido à Seguradora, o EMITENTE utilizará parte do crédito aberto em seu favor pelo BANCO.
<br><br>DO SEGURO DE DANO - O EMITENTE contratará com companhia idônea, dentro do prazo de até 30 (trinta) dias, contados desta data, o seguro do bem alienado fiduciariamente, contra risco de fogo e desabamento, bem como das benfeitorias e acessões nele existentes, de maneira a atender o previsto na alínea “h”, do item 8.1, infra, obedecido o disposto no art. 36 da Lei nº 10.931/2004.</td>
					</tr>
					<tr  rowspan='2'>
						<td colspan='5'><br>O DEVEDOR, titular de domínio do imóvel, ora alienado fiduciariamente,  declara, sob as penas da lei, que o bem oferecido em garantia encontra-se  livre e desembaraçado de ônus, encargos ou pendências judiciais e extrajudiciais.<br><br><font color='#FFFFFF'>LN</font></td>
					</tr>
					<tr>
						<td colspan='5'><br><br><br></td>
					</tr>
					<tr  rowspan='2'>
						<td colspan='5'><br><br>INFORMAÇÕES ADICIONAIS:<font color='#FFFFFF'>LN</font><br><font color='#FFFFFF'>LN</font><br><font color='#FFFFFF'>LN</font><br><font color='#FFFFFF'>LN</font><br><font color='#FFFFFF'>LN</font><br><font color='#FFFFFF'>LN</font><br><font color='#FFFFFF'>LN</font><br><font color='#FFFFFF'>LN</font><br><font color='#FFFFFF'>LN</font><br><font color='#FFFFFF'>LN</font><br></td>
					</tr></font>
			</table>
			<br><p align='justify'>
			Na data do vencimento, para pagamento do valor correspondente ao montante utilizado do crédito aberto em meu favor, indicado no campo 2 acima, o EMITENTE pagará por esta Cédula de Crédito Bancário, ao BANCO, retro designado, os valores especificados, correspondentes ao referido crédito, desde já reconhecendo o crédito utilizado como certo, líquido e exigível, de acordo com o art. 29, inciso II, da Lei nº 10.931, 02/08/2004.</p>
			<p align='justify'>
			01. VALOR DO CRÉDITO - O EMITENTE emite, neste ato, em favor do BANCO a presente Cédula de Crédito Bancário, título de crédito certo, líquido e exigível  representativo da obrigação de pagamento em dinheiro, decorrente do crédito aberto a favor do EMITENTE, no valor especificado no Quadro D supra.</p>			
			<p align='justify'>
			02. JUROS - Calculados e capitalizados dia a dia, incidentes sobre o saldo devedor atualizado, a partir do dia da liberação dos recursos, exigíveis à taxa e na forma descritas no Quadro D - Características da Cédula.</p>
			<p align='justify'>
			03. FORMA DE PAGAMENTO - A remuneração básica do valor devido, com base no índice estipulado no Quadro D - Características da Cédula, será apurada dia a dia, pelo critério pro-rata tempore, sobre o valor do crédito aberto, sendo exigível juntamente com o valor utilizado desse crédito, nas datas especificadas no Quadro E - Forma de Pagamento.</p>
			<p align='justify'>
			3.1. O vencimento da primeira parcela de pagamento ocorrerá na data fixada no Quadro E do Quadro Resumo ou imediatamente após o transcurso do prazo de 30 (trinta) dias, contados da data da liberação, em conta corrente, do crédito a favor do Emitente, o que ocorrer por último. Por sua vez, as parcelas subseqüentes terão seus vencimentos nas datas previstas no Quadro. E do Quadro Resumo ou nos mesmos dias dos meses subseqüentes à data de vencimento da primeira parcela de pagamento, vencimento esse fixado de acordo com o critério previsto na primeira parte deste item.</p>
			<p align='justify'>
			04. IMPOSTOS, TAXAS E CONTRIBUIÇÕES - Todos os impostos, taxas e contribuições sobre o crédito aberto em favor do EMITENTE, correm por conta dele, EMITENTE.</p>
			<p align='justify'>
			05. JUROS DE MORA E MULTA - Em caso de mora no pagamento do principal e/ou juros, sem prejuízo do disposto nas demais cláusulas desta Cédula, incidirá sobre o saldo devedor, comissão de permanência, juros de mora, multa, correção monetária, suportando o EMITENTE, de igual forma, honorários advocatícios, judiciais ou extrajudiciais, além de todas as despesas necessárias à consecução das garantias prestadas por ele, EMITENTE.</p>
			<p align='justify'>
			5.1. A comissão de permanência, calculada dia a dia será cobrada pelo BANCO, às taxas máximas de mercado, conforme autoriza o Banco Central do Brasil.</p>
			<p align='justify'>
			5.2. Os juros de mora serão de 1% (um por cento) ao mês, calculado dia a dia.</p>
			<p align='justify'>
			5.3. Sobre o saldo devedor total desta cédula, incidirá multa de 2% (dois por cento).</p>
			<p align='justify'>
			5.4.	O EMITENTE arcará com as despesas de publicação dos editais do leilão extrajudicial e comissão de leiloeiro, esta na base de 5% (cinco por cento) sobre o valor do montante em atraso ou do lance vencedor, se houver, na hipótese de alienação do imóvel em leilão público.</p>
			<p align='justify'>
			5.5. DA CORREÇÃO MONETÁRIA</p>
			<p align='justify'>
			5.5.1. O valor a ser pago pelo EMITENTE, representado por esta Cédula de Crédito Bancário, será atualizado monetariamente pelo IPCA (índice de Preços ao Consumidor Amplo, calculado pelo IBGE).</p>
			<p align='justify'>
			5.5.2. Na hipótese de ocorrência, de forma temporária ou definitiva, de vedação, extinção e/ou suspensão do índice de atualização monetária, será adotado como índice substitutivo o Índice Geral de Preços de Mercado (IGP-M), divulgado pela Fundação Getúlio Vargas. </p>
			<p align='justify'>
			5.5.3. As partes, desde já, convencionam, como condição do presente negócio, que, em face do princípio constitucional de respeito ao direito adquirido e ao ato jurídico perfeito, não se aplicará a esta Cédula de Crédito Bancário qualquer norma superveniente de congelamento ou deflação, total ou parcial, do valor devido pelo EMITENTE.</p>
			<p align='justify'>
			06. GARANTIAS - Para assegurar o cumprimento de todas as obrigações, principal e acessórias, decorrente desta Cédula, o EMITENTE constitui, em favor do BANCO, as garantias especificadas nos Quadros C – Intervenientes Garantidor e Avalista e F - Garantia Real – Alienação Fiduciária de imóvel em garantia, conforme expressamente autoriza o art. 31 da Lei nº 10.931/2004.</p>
			<p align='justify'>
			6.1. O BANCO poderá, a qualquer tempo e a seu exclusivo critério, exigir a constituição de outras garantias destinadas a assegurar o cumprimento das obrigações contraídas em razão desta Cédula ou exigir o reforço das garantias já constituídas.</p>
			<p align='justify'>
			6.2. Se o pedido de que trata a cláusula anterior deixar de ser atendido pelo EMITENTE dentro do prazo de 5 (cinco) dias, contados do recebimento da notificação feita, considerar-se-á a dívida vencida por antecipação, com todos os seus encargos, independentemente de qualquer outra comunicação, notificação ou interpelação.</p>
			<p align='justify'>
			6.3. DA GARANTIA FIDUCIÁRIA IMOBILIÁRIA</p>
			<p align='justify'>
			A alienação fiduciária imobiliária em garantia, prevista nesta Cédula de Crédito Bancário, está disciplinada pela Lei nº 9.514, de 20/11/97, que instituiu a Alienação Fiduciária de Coisa Imóvel, devendo as disposições contratuais, adiante estipuladas, serem interpretadas, para todos os efeitos legais, quer pelos contratantes, quer pelos órgãos jurisdicionais competentes para a aplicação da mencionada lei, na conformidade dos princípios, das normas e dos objetivos do regime jurídico instituído pela Lei nº 9.514/97, que os contratantes admitem ser de natureza especial.</p>
			<p align='justify'>
			6.3.1. Em garantia do título de crédito, ora emitido, em favor do BANCO, e do cumprimento de todas as obrigações decorrentes desta operação de crédito, como prevê o art. 27 da Lei nº 10.931, de 02/08/2004, o EMITENTE aliena fiduciariamente ao BANCO, nos termos da Lei nº 9.514, de 20-11-97, o imóvel descrito e caracterizado no Quadro F, incorporando-se à garantia todas as acessões e benfeitorias que acrescerem ao imóvel.</p>
			<p align='justify'>
			6.3.2. A garantia constituída vigerá até o pagamento integral, pelo EMITENTE, do valor nela representado, valor este reconhecido pela lei como certo, líquido e exigível, conforme prevê o art. 28 da Lei nº 10.931, de 02/08/2004, seja pela soma aqui indicada, seja pelo saldo devedor demonstrado em planilha de cálculo, obedecido o disposto no §2º, do art. 28 da Lei nº 10.931, de 02/08/2004.</p>
			<p align='justify'>
			6.3.3. Fica assegurado ao EMITENTE, enquanto adimplente, a livre utilização, por sua conta e risco, do imóvel objeto da alienação fiduciária, respondendo ele, EMITENTE, antes e após a emissão da presente Cédula de Crédito Bancário, pelas despesas relacionadas ao imóvel alienado ao BANCO em garantia, seja de que natureza for, em especial, todos os impostos, taxas, seguro, contribuições condominiais e quaisquer outras contribuições ou encargos que incidam ou venham a incidir sobre o imóvel ou que sejam inerentes à garantia.</p>
			<p align='justify'>
			6.3.4. Para os efeitos do art. 24, inciso VI, da Lei nº 9.514/97, as partes avaliam o bem alienado fiduciariamente no montante indicado no Quadro F desta Cédula de Crédito Bancário.</p>
			<p align='justify'>
			6.3.5. Qualquer acessão ou benfeitoria, não importa de que espécie ou natureza, somente poderá ser introduzida pelo EMITENTE no imóvel alienado fiduciariamente mediante prévia e expressa autorização do BANCO, obrigando-se o EMITENTE, caso a obra seja autorizada, a obter as licenças administrativas necessárias, o CND-INSS e a averbar o aumento ou a diminuição da área construída, sendo que, em qualquer hipótese, os acréscimos ocorridos se incorporarão ao imóvel e ao seu valor, para fins de realização do leilão extrajudicial, não podendo o EMITENTE invocar direito de indenização ou de retenção.</p>
			<p align='justify'>
			6.3.6. Se o imóvel estiver locado, a locação poderá ser denunciada com prazo de trinta dias para desocupação, obrigando-se o EMITENTE, sob pena de vencimento antecipado da dívida, a incluir no contrato de locação, celebrado ou a celebrar, que o locatário toma conhecimento de que: (a) a propriedade fiduciária do imóvel é titulada pelo BANCO; (b) eventual indenização por benfeitorias, de qualquer espécie ou natureza, passará a integrar o valor do lance vencedor em leilão, não podendo ser pleiteado ao BANCO qualquer direito de indenização ou de retenção, não importa a que título ou pretexto; (c) sujeitar-se-á aos efeitos da ação de reintegração na posse prevista no artigo 30 da Lei 9.514/97, independentemente de sua citação ou intimação; (d) inexistirá qualquer direito de preferência e/ou de continuidade da locação, caso ocorra a consolidação da propriedade em nome do BANCO e/ou a alienação do imóvel a terceiros em leilão público extrajudicial; (e) após a a consolidação da propriedade em nome do BANCO e/ou a alienação do imóvel a terceiros em leilão público extrajudicial, a título de taxa de utilização do imóvel, pagará ao BANCO ou ao adquirente o valor previsto no art. 37-A da Lei nº 9.514/97.</p>
			<p align='justify'>
			6.3.7. Será considerada ineficaz, e sem qualquer efeito perante o BANCO ou seus sucessores, a contratação ou a prorrogação de locação de imóvel alienado fiduciariamente por prazo superior a um ano sem concordância por escrito do BANCO. O EMITENTE, sempre que lhe for solicitado, deverá entregar ao BANCO cópia do contrato de locação.</p>
			<p align='justify'>
			6.3.8. No prazo de 30 (trinta) dias, contados da data em que se efetivar a liquidação total da dívida, o BANCO outorgará o pertinente Termo de Quitação, sob pena de responder pelo pagamento de multa moratória equivalente a 0,5% (meio por cento) ao mês, ou fração, sobre o valor de face da Cédula de Crédito Bancário, atualizado monetariamente, na conformidade do índice previsto neste contrato.</p>
			<p align='justify'>
			6.3.9. O cancelamento imobiliário do registro da propriedade fiduciária, com a conseqüente consolidação na pessoa do EMITENTE da plena propriedade do imóvel, será de inteira responsabilidade e custo deste, fazendo-se à luz do aludido Termo de Quitação.</p>
			<p align='justify'>
			6.3.10.	 Considerando a alienação fiduciária em garantia, aqui pactuada, o EMITENTE não poderá constituir ônus sobre o imóvel, que, por tal razão, é insuscetível de penhora, pois constitui patrimônio afetado exclusivamente como garantia ao cumprimento do pagamento dos valores objeto da presente Cédula de Crédito Bancário.</p>
			<p align='justify'>
			6.3.11.  Após o vencimento da dívida, transcorrido o prazo de carência de 5 (cinco) dias úteis, o Sr. Oficial de Registro de Imóveis expedirá intimação ao EMITENTE para que, no prazo de 15 (quinze) dias, satisfaça, a dívida vencida, acrescida dos juros ora convencionados, das penalidades, dos demais encargos aqui previstos, dos encargos legais, inclusive tributos, as contribuições condominiais eventualmente  imputáveis ao imóvel, além das despesas de cobrança e de intimação, tudo de acordo com o art. 26, §§1º e 2º, da Lei nº 9.514/97.</p>
			<p align='justify'>
			6.3.12. O procedimento de intimação obedecerá aos seguintes requisitos: (a) será requerido pelo BANCO ao Oficial do competente Registro de Imóveis, indicando o valor vencido e não pago e penalidades moratórias; (b) far-se-á, a critério do Oficial do Registro de Imóveis, por intermédio de seu preposto, ou pelo Oficial do Registro de Títulos e Documentos da comarca da situação do imóvel ou do domicílio de quem deva recebê-la, ou pelo correio, com aviso de recebimento firmado pelo EMITENTE, ou por quem deva receber a intimação.</p>
			<p align='justify'>
			6.3.13. Se o destinatário da intimação encontrar-se em local incerto e não sabido, certificado pelo Oficial do Registro de Imóveis ou pelo de Títulos e Documentos, competirá ao primeiro promover sua intimação por edital com prazo de 10 (dez) dias, contados da primeira divulgação, publicada por três dias, ao menos, pelo menos, em um dos jornais de maior circulação no local do imóvel ou noutro de comarca de fácil acesso, se, no local do imóvel, não houver imprensa com circulação diária.</p>
			<p align='justify'>
			6.3.14.	A mora do EMITENTE verificar-se-á quando transcorrido o prazo de 15 (quinze) dias, contados da data em que for notificado para purgar as quantias em atraso.</p>
			<p align='justify'>
			6.3.15. Não purgada a mora no prazo assinado o Oficial: (a) certificará tal fato; (b) promoverá o registro da consolidação da propriedade em nome do BANCO mediante a prévia apresentação da prova de recolhimento do imposto de transmissão de bens imóveis ou de direitos a eles relativos.</p>
			<p align='justify'>
			6.3.16. Uma vez consolidada a propriedade em seu nome, o BANCO, no prazo de trinta dias, contados da data do registro de que trata o § 7º do artigo 26 da Lei nº 9.514/97, promoverá público leilão para a alienação do imóvel alienado fiduciariamente, respeitado o procedimento de que trata o art. 27 da Lei nº 9.514/97, procedimento este a seguir transcrito:</p> 
			<p align='justify'><b><i>
			<font color='#FFFFFF'>espaço</font>“§ 1º Se, no primeiro público leilão, o maior lance oferecido for inferior ao valor do imóvel, estipulado na forma do inciso VI do art. 24, será realizado o segundo leilão, nos quinze dias seguintes.<br>
			<font color='#FFFFFF'>espaço</font>§ 2º No segundo leilão, será aceito o maior lance oferecido, desde que igual ou superior ao valor da dívida, das despesas, dos prêmios de seguro, dos encargos legais, inclusive tributos, e das contribuições condominiais.<br>
			<font color='#FFFFFF'>espaço</font>§ 3º Para os fins do disposto neste artigo, entende-se por:<br>
			I - dívida: o saldo devedor da operação de alienação fiduciária, na data do leilão, nele incluídos os juros convencionais, as penalidades e os demais encargos contratuais;<br>
			II - despesas: a soma das importâncias correspondentes aos encargos e custas de intimação e as necessárias à realização do público leilão, nestas compreendidas as relativas aos anúncios e à comissão do leiloeiro. <br>
			<font color='#FFFFFF'>espaço</font>§ 4º Nos cinco dias que se seguirem à venda do imóvel no leilão, o credor entregará ao devedor a importância que sobejar, considerando-se nela compreendido o valor da indenização de benfeitorias, depois de deduzidos os valores da dívida e das despesas e encargos de que tratam os §§ 2º e 3º, fato esse que importará em recíproca quitação, não se aplicando o disposto na parte final do art. 516 do Código Civil.<br>
			<font color='#FFFFFF'>espaço</font>§ 5º Se, no segundo leilão, o maior lance oferecido não for igual ou superior ao valor referido no § 2º, considerar-se-á extinta a dívida e exonerado o credor da obrigação de que trata o § 4º.<br>
			<font color='#FFFFFF'>espaço</font>§ 6º Na hipótese de que trata o parágrafo anterior, o credor, no prazo de cinco dias a contar da data do segundo leilão, dará ao devedor quitação da dívida, mediante termo próprio.<br>
			<font color='#FFFFFF'>espaço</font>§ 7o Se o imóvel estiver locado, a locação poderá ser denunciada com o prazo de trinta dias para desocupação, salvo se tiver havido aquiescência por escrito do fiduciário, devendo a denúncia ser realizada no prazo de noventa dias a contar da data da consolidação da propriedade no fiduciário, devendo essa condição constar expressamente em cláusula contratual específica, destacando-se das demais por sua apresentação gráfica. (redação dada pelo art. 57 da Lei nº 10.931/04).<br>
			<font color='#FFFFFF'>espaço</font>§ 8o Responde o fiduciante pelo pagamento dos impostos, taxas, contribuições condominiais e quaisquer outros encargos que recaiam ou venham a recair sobre o imóvel, cuja posse tenha sido transferida para o fiduciário, nos termos deste artigo, até a data em que o fiduciário vier a ser imitido na posse.</i></b> (redação dada pelo art. 57 da Lei nº 10.931/04).</p>
			<p align='justify'>
			6.3.17. O EMITENTE restituirá o imóvel, livre e desimpedido de pessoas e/ou coisas, dentro do prazo de 10 (dez) dias, contados da consolidação da propriedade fiduciária em nome do BANCO, sob pena de pagamento ao BANCO  ou ao adquirente do imóvel em leilão da multa diária equivalente a 0,035% (trinta e cinco milésimos por cento) sobre o valor do imóvel, como definido no Quadro  F – GARANTIA REAL sem prejuízo de sua responsabilidade pelo pagamento: (a) de todas as despesas de condomínio, água, luz e gás incorridas após a data da realização do público leilão; (b) de todas as despesas necessárias à reposição do imóvel ao estado em que o recebeu.</p>
			<p align='justify'>
			6.3.18. Não ocorrendo a desocupação do imóvel no prazo e forma ajustados, o BANCO, ou mesmo o adquirente do imóvel em leilão, poderá requerer a sua reintegração na posse, que será concedida liminarmente, para que o imóvel seja desocupado no prazo máximo de 60 (sessenta) dias, desde que comprovada, mediante certidão da matrícula do imóvel, a consolidação da plena propriedade em nome do BANCO, ou do registro do contrato celebrado em decorrência do leilão, conforme quem seja o autor da reintegração na posse, cumulada com a cobrança do valor da taxa diária de ocupação e demais despesas previstas neste contrato.			</p>
			<p align='justify'>
			6.3.19. O EMITENTE tem ciência inequívoca quanto à desnecessidade de sua   intimação pessoal, a respeito da data da realização do leilão extrajudicial. Caso, ele, EMITENTE, tenha interesse em acompanhar o leilão extrajudicial, ser-lhe-á facultado solicitar, por escrito, informações junto ao BANCO, sem prejuízo, evidentemente, da continuidade plena do leilão extrajudicial.</p>
			<p align='justify'>
			6.4. O EMITENTE, em face das condições, ora pactuadas, declara  que o imóvel alienado fiduciariamente esta livre de quaisquer  impostos ou taxas, sendo certo que a garantia, ora constituída,  permanecerá íntegra e em pleno vigor até haja cumprimento total de todas as obrigações assumidas pelo EMITENTE, a favor do BANCO, quando, então, se dará a conseqüente liberação.</p>
			<p align='justify'>
			6.5. O EMITENTE se obriga a fazer constar da respectiva matrícula, para todos os efeitos de direito, ter sido constituída esta garantia fiduciária.</p>
			<p align='justify'>
			6.6. Se o bem constitutivo da garantia for desapropriado, ou se for danificado ou perecer por fato imputável a terceiro, o credor sub-rogar-se-á no direito à indenização devida pelo expropriante ou pelo terceiro causador do dano, até o montante necessário para liquidar ou amortizar a obrigação garantida.</p>
			<p align='justify'>
			6.7. Na hipótese prevista no item 6.6 supra, faculta-se ao BANCO exigir a substituição da garantia, ou o seu reforço, renunciando ao direito à percepção do valor relativo à indenização.</p>
			<p align='justify'>
			6.8. O BANCO poderá, ainda, exigir a substituição ou o reforço da garantia, em caso de perda, deterioração ou diminuição de seu valor.</p>
			<p align='justify'>
			6.9. O EMITENTE está ciente e concorda que, para a abertura de crédito em seu favor, o BANCO necessita analisar seu histórico financeiro, consultar, elaborar e/ou atualizar seus dados cadastrais, bem como adotar as demais formalidades cabíveis, pelo que será devida a Tarifa de Abertura de Crédito - TAC sendo, ainda, de responsabilidade do EMITENTE todas as demais despesas deste contrato, bem como todos os tributos que incidem ou venham a incidir sobre a operação, especialmente o Imposto de Operações de Crédito - IOC, além daquelas que se façam necessárias para o devido registro da alienação fiduciária na circunscrição imobiliária competente.</p>
			<p align='justify'>
			6.10. As partes autorizam desde já o Sr. Oficial do Cartório de Registro de Imóveis competente  a proceder, às expensas  do EMITENTE, a todas e quaisquer  averbações e registros que tornarem necessários à perfeita e completa legalização desta Cédula.</p>
			<p align='justify'>
			07. AMORTIZAÇÕES EXTRAORDINÁRIAS - O DEVEDOR poderá, a qualquer tempo, mediante prévia e expressa anuência do BANCO, efetuar a quitação antecipada de qualquer parcela ou do saldo devedor integral do contrato. Nessa hipótese, o DEVEDOR estará sujeito ao pagamento de uma tarifa de liquidação antecipada, no valor vigente à época da liquidação, conforme tabela divulgada pelo BANCO.</p>
			<p align='justify'>
			08. VENCIMENTO ANTECIPADO - Operar-se-á de pleno direito, independentemente de interpelação judicial ou extrajudicial, para os efeitos do art. 397 do Código Civil Brasileiro, o vencimento antecipado da totalidade do saldo devedor, principal e encargos desta cédula, de responsabilidade do EMITENTE, além das demais previstas neste instrumento, nos seguintes casos:</p>
			<p align='justify'>
			<font color='#FFFFFF'>espaço</font>a.) se ocorrer qualquer uma das causas previstas nos artigos 1425 e 333 do Código Civil Brasileiro;</p>
			<p align='justify'>
			<font color='#FFFFFF'>espaço</font>b.) se for apurada a falsidade de qualquer declaração, informação ou documento que houverem sido, respectivamente, firmados, prestados ou entregues pelo EMITENTE e/ou COOBRIGADOS GARANTIDORES;</p>
			<p align='justify'>
			<font color='#FFFFFF'>espaço</font>c.) se qualquer título for objeto de protesto contra o EMITENTE;</p>
			<p align='justify'>
			<font color='#FFFFFF'>espaço</font>d.) se o EMITENTE  sofrer justo requerimento de falência ou tiver esta decretada;</p>
			<p align='justify'>
			<font color='#FFFFFF'>espaço</font>e.) se, em caso de morte, insolvência ou interdição, ou falência dos COOBRIGADOS GARANTIDORES, -o EMITENTE não providenciar a sua substituição no prazo de 48 (quarenta e oito) horas contadas da data do recebimento da comunicação que lhe for dirigida neste sentido, e;</p>
			<p align='justify'>
			<font color='#FFFFFF'>espaço</font>f.) se o EMITENTE deixar de cumprir qualquer obrigação decorrente das condições desta Cédula ou da Lei.</p>
			<p align='justify'>
			8.1. Além das demais hipóteses previstas em Lei e neste instrumento, de vencimento antecipado da dívida, com a imediata exigibilidade do crédito, estes se verificarão se for comprovada:</p>
			<p align='justify'>
			<font color='#FFFFFF'>espaço</font>a.)	a inclusão, em relação ao EMITENTE, em qualquer órgão de restrição ao crédito</p>
			<p align='justify'>
			<font color='#FFFFFF'>espaço</font>b.) a falsidade de qualquer declaração do EMITENTE e/ou COOBRIGADO GARANTIDOR, contida nesta Cédula.</p>
			<p align='justify'>
			<font color='#FFFFFF'>espaço</font>c) se vier o EMITENTE a  compromissar a venda, onerar ou constituir   ônus real, de  qualquer natureza, sobre parte ou totalidade do imóvel alienado em garantia ;</p>
			<p align='justify'>
			<font color='#FFFFFF'>espaço</font>d) se, no curso de qualquer ação ou execução, inclusive expropriatória, for  determinada medida judicial que afete diretamente o bem dado em garantia, no todo ou em parte, sem oferecer o EMITENTE ao BANCO - a respectiva substituição da garantia;</p>
			<p align='justify'>
			<font color='#FFFFFF'>espaço</font>e) se, depreciando-se por qualquer motivo, o bem objeto da garantia o EMITENTE não o substituir, após devidamente intimado por simples carta protocolada, sendo que o BANCO terá a faculdade de recusar qualquer novo bem oferecido em garantia, sem especificar as razões da recusa; </p>
			<p align='justify'>
			<font color='#FFFFFF'>espaço</font>f) se, o EMITENTE ou ANUENTE tiver requerida ou declarada insolvência ou falência, ou de outra forma, tiver caracterizada a sua insolvência;</p>
			<p align='justify'>
			<font color='#FFFFFF'>espaço</font>g) se faltar o EMITENTE ao pagamento, nas épocas próprias, dos valores  avençados e devidos nos respectivos vencimentos,  dos impostos, taxas e  demais encargos incidentes ou que venham a incidir sobre o imóvel alienado fiduciariamente ou deixar de atender intimação das autoridades fiscais ou administrativas concernentes ao mesmo imóvel;</p>
			<p align='justify'>
			<font color='#FFFFFF'>espaço</font>h) se deixar o EMITENTE de promover em companhia idônea, dentro do prazo de até 30 (trinta) dias, contados desta data, o seguro do  bem alienado fiduciariamente, contra risco de fogo  e desabamento, bem como das benfeitorias e acessões nele existentes, obedecido o disposto no art. 36 da Lei nº 10.931/2004;</p>
			<p align='justify'>
			<font color='#FFFFFF'>espaço</font>i) se o ANUENTE e o EMITENTE, vierem a inadimplir qualquer cláusula ou condição de tal contrato.</p>
			<p align='justify'>
			09. INADIMPLEMENTO - O não pagamento, no respectivo vencimento, de qualquer das parcelas de amortização do principal e respectivos encargos ou o inadimplemento de qualquer obrigação assumida pelo EMITENTE, na presente Cédula, determinará o vencimento antecipado do total do saldo devedor em aberto, principal atualizado e encargos acrescido dos juros moratórios, da multa, dos honorários advocatícios e outras eventuais despesas decorrentes do atraso, que se tornarão imediatamente exigíveis. Em tal hipótese, é facultado ao BANCO o direito de proceder a execução de qualquer uma ou todas as garantias vinculadas a esta Cédula ou que vierem a sê-lo, podendo tais garantias ser, a qualquer tempo, excutidas até final e integral liquidação do débito apurado.</p>
			<p align='justify'>
			10. O EMITENTE se responsabiliza por todas as despesas e custos decorrentes do registro desta Cédula, bem como da respectiva garantia nos cartórios competentes.</p>
			<p align='justify'>
			11. A não utilização pelo BANCO de qualquer dos direitos ou faculdades que lhe concedam a Lei e esta cédula, não importa em renúncia dos mesmos direitos ou faculdades, sendo mera tolerância ou reserva para fazê-los prevalecer em qualquer outro momento ou oportunidade.</p>
			<p align='justify'>
			12. O BANCO fica expressamente autorizado a informar os dados relativos a todas as obrigações assumidas pelo EMITENTE junto ao BANCO, para constarem de cadastros compartilhados pelo BANCO com outras instituições conveniadas para tanto, administrados pelo Serasa ou por outras entidades de proteção ao crédito. O BANCO e tais outras instituições ficam expressamente autorizadas a disponibilizar e intercambiar entre si informações sobre obrigações contraídas pelo EMITENTE, o que é de utilidade aos seus interesses. O EMITENTE declara também que está ciente que o BANCO deve fornecer ao Banco Central do Brasil, informações sobre a presente operação, ou seja, dívida a vencer, vencida e registrada como prejuízo.</p>
			<p align='justify'>
			13. Esta Cédula obriga as partes, seus herdeiros e sucessores.</p>
			<p align='justify'>
			As partes elegem o Foro de São Paulo, Capital, como o competente para dirimir quaisquer dúvidas decorrentes da presente.</p>
			<p align='justify'>
			Pelo presente instrumento, firmado pelas 02 (duas) testemunhas abaixo, em 3 (três) vias de igual teor, as partes acima nomeadas e qualificadas e abaixo assinadas, têm entre si justo e avançado a presente mediante as cláusulas deste instrumento.</p>
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
	
		$pdf->Cell(0,5,'CÉDULA DE CRÉDITO BANCÁRIO',0,2,'C');
		//$pdf->WriteHTML($titulo);
		$pdf->WriteHTML($texto);
		$pdf->Ln(0);
		
		$pdf->Output();
		

?>