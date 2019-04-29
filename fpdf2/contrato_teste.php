<?php
//$dir = getcwd();
//echo $dir;
require_once "pdf/html2fpdf.php";
//require_once "class/dbclasses.class.php";
//require_once "class/db.class.php";
$cod_ppst=$_GET['cod_proposta'];
$resp=$_GET['resp'];

############################CONECTA NO BANCO DE DADOS#############################################

$BD_SERVIDOR = "localhost";
$BD_NOME	= "carim"; //banco de dados
$BD_USUARIO	= "root";
$BD_SENHA	= "/c8119H!";
$conexao = mysql_connect($BD_SERVIDOR,$BD_USUARIO,$BD_SENHA) or die("ERRO: conexão não realizada");
	mysql_select_db($BD_NOME) or die("ERRO: erro ao selecionar o banco de dados: ". mysql_error());
$query="SELECT * FROM proposta where cod_ppst='".$cod_ppst."'";
$result=mysql_query($query);
$rows=mysql_num_rows($result);
$registro=mysql_fetch_array($result,MYSQL_ASSOC);
$vl_premio=		$registro['VALORSEGURO_PPST'];
$infoadicional= $registro['INFOADICIONAIS_PPST'];
$asscontrato=	$registro['DTASSCONTRATO_PPST'];
$situacao=		$registro['SITUACAO_PPST'];
$infofort=		$registro['INFOADICIONAISFORT_PPST'];		
if($rows==1)
{
	$tipo_ppst="S"; //Proposta Simples (Com apenas um participante)
}elseif($rows>1)
{
	$tipo_ppst="C"; // Proposta em Condomínio (Com mais de um participante)
}
if($situacao==7)
{
	$query="update proposta set situacao_ppst='8' where cod_ppst='".$cod_ppst."'";
	mysql_query($query);
}

$query="Insert into historico (COD_PPST, DT_HIST, OBS_HIST, TIPO_HIST, COD_USUA) values ('".$cod_ppst."',now(),'Contrato Emitido','1','".$resp."')";
mysql_query($query);



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
 
#____________________________________ Formata Conta _____________________________________________
function formataConta($vl)
{
	$cont=strlen($vl);
	$i=0;
	$aux=0;
	while($i<$cont)
	{
		if($vl[$i]=='0')
		{
			$aux++;
		}else
		{
			$i=$cont;
		}
		$i++;
	}
	$resto=($cont-$aux);
	$invalido=substr($vl,0,$aux);
	$valido=substr($vl,$aux,$resto);
	return $valido;
}

#____________________________________ Data por Extenso __________________________________________
function data_extenso($data)
{
	$ano=substr($data,0,4);
	$mes=substr($data,5,2);
	$dia=substr($data,8,2);
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
# ___________________________________ Próximo Mês _______________________________________________
function proxMes($data)	
{
	$mes1=substr($data,5,2);
	
	switch ($mes1){
	
	case 1: $mes = '02'; break;
	case 2: $mes = '03'; break;
	case 3: $mes = '04'; break;
	case 4: $mes = '05'; break;
	case 5: $mes = '06'; break;
	case 6: $mes = '07'; break;
	case 7: $mes = '08'; break;
	case 8: $mes = '09'; break;
	case 9: $mes = '10'; break;
	case 10: $mes = '11'; break;
	case 11: $mes = '12'; break;
	case 12: $mes = '01'; break;
	
	}
	return $mes;
	
}
# ___________________________________ Próximo Mes Ano _______________________________________________
/*function proxMesAno($mes)	
{
	switch ($mes){
	
	case 1: $ano = date('Y'); break;
	case 2: $ano = date('Y'); break;
	case 3: $ano = date('Y'); break;
	case 4: $ano = date('Y'); break;
	case 5: $ano = date('Y'); break;
	case 6: $ano = date('Y'); break;
	case 7: $ano = date('Y'); break;
	case 8: $ano = date('Y'); break;
	case 9: $ano = date('Y'); break;
	case 10: $ano = date('Y'); break;
	case 11: $ano = date('Y'); break;
	case 12: $ano = date('Y')+1; break;
	
	}
	return $ano;
}*/

#____________________________________ Formata Texto _________________________________________

function formataTexto($texto)
{
	$texto=str_replace("<p>","<p align='justify'><font color='#FFFFFF'>",$texto);
	$texto=str_replace("<p align='justify'><font color='#FFFFFF'>&nbsp;</p>","",$texto);
	$texto=str_replace("m2","m<sup>2</sup>",$texto);
	
	return $texto;
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
################################# QUALIFICAÇÃO DE COMPRADOR(A,ES)  #######################################
//§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§
#____________________________QUALIFICAÇÃO DE PARTICIPANTE DE PROPOSTA SIMPLES_______________________
//§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§
if($tipo_ppst=='S'){ // Qualificação do Participante de Proposta Simples

	//______________________ Dados do Proponente________________________________
	$query = "SELECT * FROM proponente WHERE cod_ppst='".$cod_ppst."' LIMIT 1";
	$result =mysql_query($query);
	$registro = mysql_fetch_array($result, MYSQL_ASSOC);
	$prop_cpf  		= formataCPF($registro['CPF_PPNT']);
	$prop_nacional  = strtolower($registro['NACIONAL_PPNT']);
	$prop_sex		= $registro['SEXO_PPNT'];
	$prop_numdoc 	= $registro['NRRG_PPNT'];
	$prop_dtdoc 	= formataDataBRA($registro['DTRG_PPNT']);
	$prop_emissor 	= $registro['ORGRG_PPNT'];
	$prop_nasc 		= formataDataBRA($registro['DTNASCIMENTO_PPNT']);
	$prop_civil 	= $registro['COD_ESTCIV'];
	$prop_lograd 	= $registro['COD_LOGR'];
	$prop_ender 	= $registro['ENDERECO_PPNT'];
	$prop_num 		= $registro['NRENDERECO_PPNT'];
	$prop_compl 	= $registro['CPENDERECO_PPNT'];
	$prop_bairro 	= $registro['COD_BAIRRO'];
	$prop_uf 		= $registro['COD_UF'];
	$prop_cidade 	= $registro['COD_MUNICIPIO'];
	$prop_cep 		= formataCep($registro['CEP_PPNT']);
	$cod_usuario	= $registro['COD_PROPONENTE'];
	$prop_vlsinal	= $registro['VLSINAL_PPNT'];
	$prop_vlentrada = $registro['VLENTRADA_PPNT'];
	$prop_vlcompra	= $registro['VLCOMPRA_PPNT'];
	$prop_prazo		= $registro['PRZFINSOL_PPNT'];
	$prop_prazo		= explode(".",$prop_prazo);
	$pdoc="Carteira de Identidade";
	$flgproc		= $registro['FLGPROC_PPNT'];
	$proc			= $registro['PROC_PPNT'];
	$flguniest		= $registro['FLGUNIEST_PPNT'];
	$flgpacto		= $registro['FLGESCRITURA_PPNT'];
	$prop_prof		= strtolower($registro['PROFISSAO_PPNT']);
	
	// PROCURADOR
	if($flgproc=='S'){
		$procurador=", neste ato representado por seu bastante procurador ".$proc;
		$ass_pproc="P.P. ";
	}else{
		$procurador=".";
		$ass_pproc="";
	}

	// NACIONALIDADE
	$query="Select * from pais where cod_pais='".$prop_nacional."'";
	$result =mysql_query($query);
	$registro = mysql_fetch_array($result, MYSQL_ASSOC);
	if($prop_sex=='M')
	{
		$emit_nacional= $registro['NACIONALM'];
	}
	if($prop_sex=='F')
	{
		$emit_nacional= $registro['NACIONALF'];
	}
	// ENDEREÇO
	$query="SELECT desc_logr FROM logradouro WHERE cod_logr = '".$prop_lograd."' ";
	$result =mysql_query($query);
	$registro = mysql_fetch_array($result, MYSQL_ASSOC);
	$p_lograd= $registro['desc_logr'];
	
	$query="SELECT nome_bairro FROM bairro WHERE cod_bairro = '".$prop_bairro."' ";
	$result =mysql_query($query);
	$registro = mysql_fetch_array($result, MYSQL_ASSOC);
	$p_bairro= $registro['nome_bairro'];
	
	$query="SELECT nome_municipio, cod_uf FROM municipio WHERE cod_municipio = '".$prop_cidade."' ";
	$result =mysql_query($query);
	$registro = mysql_fetch_array($result, MYSQL_ASSOC);
	$p_cidade=$registro['nome_municipio'];
	$p_uf= $registro['cod_uf'];
	
	//COMPLEMENTO;
	 if($prop_compl!=''){
	 	$prop_compl= $prop_compl.", ";
	 }else{
	 	$prop_compl="";
	 }
	
			$endereco=$p_lograd." ".$prop_ender.", nr. ".$prop_num.", ".$prop_compl.ucwords(strtolower($p_bairro)).", ".ucwords(strtolower($p_cidade)).", ".$p_uf;	

	//______________________  Dados de Usuário _________________________________________________
	$query = "SELECT nome_usua, id_lstn FROM  usuario WHERE cod_usua='".$cod_usuario."' LIMIT 1";
	$result =mysql_query($query);
	$registro = mysql_fetch_array($result, MYSQL_ASSOC);
	$prop_nome = $registro['nome_usua'];
	$id_lstn=	 $registro['id_lstn'];

	//______________________ Dados do Lista de Nomes ___________________________
	$query = "SELECT * FROM  listadenomes WHERE id_lstn='".$id_lstn."' LIMIT 1";
	$result =mysql_query($query);
	$registro = mysql_fetch_array($result, MYSQL_ASSOC);
	$valor_finan  	= $registro['VLAPROVADO'];
	$parcela_finan	= $registro['PARCAPROVADA'];
	$prazo_finan	= $registro['PRZAPROVADO'];
	$entrada_finan	= $registro['VLENTRAPROVADO'];	
	
	//______________________ Dados do Conjuge de Proponente___________________________
	$query = "SELECT * FROM proponenteconjuge WHERE cod_ppst='".$cod_ppst."' LIMIT 1";
	$result =mysql_query($query);
	$registro = mysql_fetch_array($result, MYSQL_ASSOC);

	$prop_reg			= $registro['REGIMEBENS_PPCJ'];
	$conj_nome			= $registro['NOME_PPCJ'];
	$conj_nacional		= $registro['COD_PAIS'];
	$conj_civil			= $registro['COD_ESTCIV'];
	$conj_cpf			= formataCPF($registro['CPF_PCCJ']);
	$conj_numdoc		= $registro['NRRG_PPCJ'];
	$conj_dtdoc			= formataDataBRA($registro['DTRG_PPCJ']);
	$conj_emissor		= $registro['ORGRG_PPCJ'];
	$prop_dtcasamento	= formataDataBRA($registro['DTCASAMENTO_PPCJ']);
	$conj_flgprof		= $registro['FLGTRABALHA_PPCJ'];
	$conj_profissao		= strtolower($registro['CARGOEMP_PPCJ']);
	$cdoc="Carteira de Identidade";
	//NACIONALIDADE
	$query="Select * from pais where cod_pais='".$conj_nacional."'";
	$result =mysql_query($query);
	$registro = mysql_fetch_array($result, MYSQL_ASSOC);
	if($prop_sex=='M')
	{
		$conjemit_nacional= $registro['NACIONALF'];
	}
	if($prop_sex=='F')
	{
		$conjemit_nacional= $registro['NACIONALM'];
	}
	if($prop_nacional==$conj_nacional)
	{
		$nacional= $registro['NACIONALFM'];
	}

	//______________________ Dados de DEVEDOR(A,ES) Solidário __________________
	$query = "SELECT * FROM devsol WHERE cod_ppst='".$cod_ppst."' LIMIT 1";
	$result =mysql_query($query);
	$registro = mysql_fetch_array($result, MYSQL_ASSOC);

	$dev_nome			= $registro['NOME_DEVSOL'];
	$dev_nacional		= $registro['COD_PAIS'];
	
		
	//______________________ Dados do Pacto Antenupcial de Proponente e Conjuge ___________________
	$query = "SELECT * FROM proponenteconjugepacto WHERE cod_proponente='".$cod_usuario."' LIMIT 1";
	$result =mysql_query($query);
	$registro = mysql_fetch_array($result, MYSQL_ASSOC);

	$prop_dtlavrado			= formataDataBRA($registro['DATA_PCPA']);
	$prop_loclavrado		= $registro['LOCALLAVRACAO_PCPA'];
	$prop_livro				= $registro['LIVRO_PCPA'];
	$prop_folha				= $registro['FOLHA_PCPA'];
	$prop_numregistro		= $registro['NUMEROREGISTRO_PCPA'];
	$prop_habens			= $registro['HABENS_PCPA'];
	$prop_habenscart		= $registro['HABENSCART_PCPA'];
	$prop_habensloccart		= $registro['HABENSLOCCART_PCPA'];
	$prop_habensdata		= formataDataBRA($registro['HABENSDATA_PCPA']);
	//HA BENS
	if($prop_habens=='S')
	{
		$habens="-Registro Auxiliar do  ".$prop_habenscart."o. Cartório de Registro de Imóveis de ".$prop_habensloccart.", em ".$prop_habensdata."";
	}
	
	
	// ____________Qualificação Credor Quitante _________________________________________
	$query="Select * from credorquitante where cod_ppst='".$cod_ppst."'";
	$result=mysql_query($query);
	$registro=mysql_fetch_array($result,MYSQL_ASSOC);
	$flg_crd		= $registro['FLG_CRD'];
	$gravame_crd	= $registro['TPGRAVAME_CRD'];
	$reg_crd		= $registro['NRREGISTRO_CRD'];
	$cart_crd		= $registro['CARTORIO_CRD'];
	$rec_crd		= $registro['RECURSOS_CRD'];
	if($flg_crd=='S'){
		$credor=" E INTERVENIENTE QUITANTE";
		$desc_crd="Caixa de Previdência dos Funcionários do Banco do Brasil";
	}else{
		$gravame_crd= "não há<br><font color='#FFFFFF'>LN</font><br>";
		$reg_crd= "não há<br><font color='#FFFFFF'>LN</font><br>";
		$cart_crd= "não há<br><font color='#FFFFFF'>LN</font><br>";
		$rec_crd= "não há<br><font color='#FFFFFF'>LN</font><br>";
		$credor="";
		$desc_crd="não há<br><font color='#FFFFFF'>LN</font><br>";		
	}
	
	

	
	// ____________Dados de Valores para Item III _____________________________________
	$query="Select * from imovel where cod_ppst='".$cod_ppst."'";
	$result=mysql_query($query);
	$registro=mysql_fetch_array($result,MYSQL_ASSOC);
	$vlavaliacao		= $registro['VLAVALIACAO_IMOV'];
	$vlavalsemgar		= $registro['VLAVALSEMGAR_IMOV'];
	$vlavalgar			= $registro['VLAVALGAR_IMOV'];
	
	// ____________Dados de Valores para Item IV ______________________________________
	// Valor FGTS
	$query="Select * from fgts where cod_usua='".$cod_usuario."'";
	$result=mysql_query($query);
	$registro=mysql_fetch_array($result,MYSQL_ASSOC);
	$vlfgts			= $registro['VALOPERACAO'];
	//Valores de Financiamento e Valor Liberado
	$vltotfinan= (($prop_vlcompra - $prop_vlentrada)-$vlfgts);
	$vlliberado= ($vltotfinan - $rec_crd) + $vlfgts;
	//Qualificação de Contas do DEVEDOR(A,ES)
	$query="Select * from retornofgts where participante='".$id_lstn."'";
	$result=mysql_query($query);
	$registro=mysql_fetch_array($result,MYSQL_ASSOC);
	$prop_agencia	= $registro['nragencia'];
	$prop_conta		= $registro['nrconta'];
	$prop_renda		= $registro['rendabruta'];

	$prop_contas	= strtoupper($prop_nome).": Banco do Brasil S/A, Agência n° ".$prop_agencia.", Conta n° ".formataConta($prop_conta);
	
	//Clausulas fixas
	/*
	if($prop_cidade==4076)
	{
		$infofixa="<p align='justify'>Foram apresentadas em nome do Vendedor as certidões exigidas pela Lei 7.433, de 18/12/85, quais sejam: Certidões dos 1°, 2°, 3°, 4° e 9° Ofícios de Distribuição, 1° e 2° Ofícios de Interdições e Tutelas, situação enfitêutica, quitação fiscal, 9° Ofício – Executivos Fiscais e declaração de quitação condominial relativas ao imóvel. O imóvel objeto do presente contrato não é foreiro, conforme certidão expedida pela Prefeitura do Município do Rio de Janeiro. Quando for a hipótese de imóvel foreiro, a certidão apresentada, bem como o recolhimento de laudêmio encontrar-se-á mencionado no campo Informações Adicionais.</p>";
	}elseif($prop_cidade==3172)
	{
		$infofixa="<p align='justify'>Declara o Vendedor que não existem quaisquer débitos que recaiam sobre  o imóvel objeto deste contrato, conforme Certidão Negativa de Tributos Municipais, expedida pela Prefeitura Municipal de Niterói. Foram apresentadas as certidões exigidas pela Lei 7.433, de 18/12/85, relativamente a Interdição, Tutela e Curatela, expedida pelo Registro Civil da Primeira Zona Judiciária de Niterói, 1° Distribuidor de Niterói e Justiça Federal, em nome do Vendedor.</p>";
	}elseif($prop_cidade==1445)
	{
		$infofixa="<p align='justify'>Na conformidade da Lei n° 7.433, de 18/12/1985, do Decreto n° 93.240, de 09/09/1986, da Corregedoria da Justiça do Estado do Paraná, foram apresentadas as Certidões exigidas, inclusive a de feitos ajuizados, do conhecimento das partes. Declara ainda o outorgante não existirem ações reais e pessoais reipersecutórias relativas ao imóvel objeto da presente Escritura, e de outros ônus reais incidentes sobre o mesmo, conforme Decreto n° 93.240, de 09/09/86, Art. 1°, inc. V, parágrafo 3°.</p>";
	}elseif($prop_cidade==1751)
	{
		$infofixa="<p align='justify'>".$infofort."</p>";
	}else{
		$infofixa="";
	}*/
	
	// DEVEDOR(A,ES) ou DEVEDOR(A,ES)es
	if($prop_civil==2 || ($prop_civil!=2 && $flguniest=='S'))
	{
		$assinatura_dev="
			<table border='0'>
				<tr>
					<td width='200'></td>
					<td width='364' align='center'><b>________________________________________________</b></td>
				</tr>
				<tr>
					<td></td>
					<td align='center'><b>".$ass_pproc.maiusculo($prop_nome)." - ".$prop_cpf."</b></td>
				</tr>
				<tr>
					<td></td>
					<td align='center'><font color='#FFFFFF'>LN</font><br>
									   <font color='#FFFFFF'>LN</font><br></td>
				</tr>
				<tr>
					<td></td>
					<td align='center'><b>________________________________________________</b></td>
				</tr>
				<tr>
					<td></td>
					<td align='center'><b>".$ass_pproc.maiusculo($conj_nome)." - ".$conj_cpf."</b></td>
				</tr>
			</table>";
	}else{
			$assinatura_dev="
			<table border='0'>
				<tr>
					<td width='200'></td>
					<td width='364' align='center'><b>________________________________________________</b></td>
				</tr>
				<tr>
					<td width='200'></td>
					<td align='center'><b>".$ass_pproc.maiusculo($prop_nome)." - ".$prop_cpf."</b></td>
				</tr>
			</table>";
	}
			$dev1="doravante denominado(a,s) simplesmente, DEVEDOR(A,ES)";
			$dev2="o(a,s) DEVEDOR(A,ES)";
			$dev3="do(a,s) DEVEDOR(A,ES)";
			$dev4="ao(s) DEVEDOR(A,ES)";
			$dev5="pelo(a,s) DEVEDOR(A,ES)";
			$dev6="O(A,s) DEVEDOR(A,ES)";
  ############################# QUALIFICAÇÃO DE PARTICIPANTE MASCULINO ###################################

	// Comprador do sexo MASCULINO
	if($prop_sex=='M')
	{
		if($prop_civil==2)//EST. CIVIL CASADO
		{
			if($prop_reg==1)// Comunhão Parcial de Bens antes da lei
			{
				if($prop_nacional==$conj_nacional)
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional.", casados  pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores das carteiras  de identidade RG n°(s) ".$prop_numdoc."-".maiusculo($prop_emissor)." e ".$conj_numdoc."-".maiusculo($conj_emissor).", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}else
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$emit_nacional.", ela ".$conjemit_nacional.", casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores das carteiras  de identidade RG n°(s) ".$prop_numdoc."-".maiusculo($prop_emissor)." e ".$conj_numdoc."-".maiusculo($conj_emissor).", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}
			}
			if($prop_reg==7)//Comunhão Parcial de Bens depois da lei
			{
				if($prop_nacional==$conj_nacional)
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional.", casados  pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcasamento.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores das carteiras  de identidade RG n°(s) ".$prop_numdoc."-".maiusculo($prop_emissor)." e ".$conj_numdoc."-".maiusculo($conj_emissor).", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}else
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$emit_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcasamento.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores das carteiras  de identidade RG n°(s) ".$prop_numdoc."-".maiusculo($prop_emissor)." e ".$conj_numdoc."-".maiusculo($conj_emissor).", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}
	
			}
			if($prop_reg==2)// Comunhão Universal de Bens antes da lei
			{
					if($prop_nacional==$conj_nacional)
					{
										$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional.", casados pelo regime de Comunhão Universal de Bens, anteriormente a Lei n°  6.515/77,  em ".$prop_dtcasamento.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores das carteiras  de identidade RG n°(s) ".$prop_numdoc."-".maiusculo($prop_emissor)." e ".$conj_numdoc."-".maiusculo($conj_emissor).", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
					}else
					{
										$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$emit_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunhão Universal de Bens, anteriormente a Lei n°  6.515/77,  em ".$prop_dtcasamento.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores das carteiras  de identidade RG n°(s) ".$prop_numdoc."-".maiusculo($prop_emissor)." e ".$conj_numdoc."-".maiusculo($conj_emissor).", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
					}
			}
			if($prop_reg==3)//Comunhão Universal de Bens depois da lei
			{
				if($prop_nacional==$conj_nacional)
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." , casados pelo regime de Comunhão Universal de Bens, na vigência da Lei n°  6.515/77,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores das carteiras  de identidade RG n°(s) ".$prop_numdoc."-".maiusculo($prop_emissor)." e ".$conj_numdoc."-".maiusculo($conj_emissor).", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}else
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$emit_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunhão Universal de Bens, na vigência da Lei n°  6.515/77,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores das carteiras  de identidade RG n°(s) ".$prop_numdoc."-".maiusculo($prop_emissor)." e ".$conj_numdoc."-".maiusculo($conj_emissor).", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}
			}
			if($prop_reg==5)//Separação de Bens com pacto (não obrigatória)
			{
				if($prop_nacional==$conj_nacional)
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional.", casados  pelo regime de Separação de Bens,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores das carteiras  de identidade RG n°(s) ".$prop_numdoc."-".maiusculo($prop_emissor)." e ".$conj_numdoc."-".maiusculo($conj_emissor).", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}else
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$emit_nacional.", ela ".$conj_nacional.", casados pelo regime de Separação de Bens de Bens,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores das carteiras  de identidade RG n°(s) ".$prop_numdoc."-".maiusculo($prop_emissor)." e ".$conj_numdoc."-".maiusculo($conj_emissor).", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}
			}
	 
			if($prop_reg==6)//Separação de Bens obrigatória
			{
				if($prop_nacional==$conj_nacional)
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional.", casados  pelo regime de Separação Obrigatória de bens, nos termos do artigo 1641 do Código Civil Brasileiro,  em ".$prop_dtcasamento.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores das carteiras  de identidade RG n°(s) ".$prop_numdoc."-".maiusculo($prop_emissor)." e ".$conj_numdoc."-".maiusculo($conj_emissor).", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}else
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$emit_nacional.", ela ".$conj_nacional.", casados pelo regime de Separação Obrigatória de bens, nos termos do artigo 1641 do Código Civil Brasileiro,  em ".$prop_dtcasamento.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores das carteiras  de identidade RG n°(s) ".$prop_numdoc."-".maiusculo($prop_emissor)." e ".$conj_numdoc."-".maiusculo($conj_emissor).", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}
			}
		}//fim de Estado civil Casado
		
		// EST.  DIFERENTE DE CASADO
		if($prop_civil==1) $estciv='solteiro';
		if($prop_civil==3) $estciv='separado judicialmente';
		if($prop_civil==4) $estciv='divorciado';
		if($prop_civil==5) $estciv='viúvo';
					
		if($conj_civil==1) $pc_estciv='solteira';
		elseif($conj_civil==3) $pc_estciv='separada judicialmente';
		elseif($conj_civil==4) $pc_estciv='divorciada';
		elseif($conj_civil==5) $pc_estciv='viúva';

		if($prop_civil!=2 && $flguniest=='S')
		{
				if($flgpacto=='S'){
					$emitente="<b>".maiusculo($prop_nome)."</b>, ".$estciv.", convivendo em união estável, nos termos da Lei nº. 9.278/96 e alterações do art. 1.723 do Código Civil Brasileiro, conforme escritura de declaração lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro.", com <b>".maiusculo($conj_nome)."</b>, ".$pc_estciv.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores das carteiras  de identidade RG n°(s) ".$prop_numdoc."-".maiusculo($prop_emissor)." e ".$conj_numdoc."-".maiusculo($conj_emissor).", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}else
				{
					$emitente="<b>".maiusculo($prop_nome)."</b>, ".$estciv.", convivendo em união estável, nos termos da Lei nº. 9.278/96 e alterações do art. 1.723 do Código Civil Brasileiro com <b>".maiusculo($conj_nome)."</b>, ".$pc_estciv.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores das carteiras  de identidade RG n°(s) ".$prop_numdoc."-".maiusculo($prop_emissor)." e ".$conj_numdoc."-".maiusculo($conj_emissor).", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}
		
		}elseif($prop_civil!=2 && ($flguniest=='N' || $flguniest==''))
		{
				$emitente="<b>".maiusculo($prop_nome)."</b>, ".$emit_nacional.", ".$estciv.", ".$prop_prof.", portador da carteira de identidade RG n° ".$prop_numdoc."-".$prop_emissor.", inscrito no CPF/MF sob o nr. ".$prop_cpf.", residente e domiciliado no(a) ".$endereco.", ".$dev1.$procurador;
		}
	}//fim if ($prop_sex=='M')
  
  ############################# QUALIFICAÇÃO DE PARTICIPANTE FEMININO ###################################
  
    // Compra do Sexo FEMININO 
	if($prop_sex=='F')
	{
		if($prop_civil==2)//EST. CIVIL CASADO
		{
			if($prop_reg==1)//Comunhão Parcial de Bens antes da lei
			{
				if($prop_nacional==$conj_nacional)
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional.", casados  pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores das carteiras  de identidade RG n°(s) ".$prop_numdoc."-".maiusculo($prop_emissor)." e ".$conj_numdoc."-".maiusculo($conj_emissor).", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco." <br>".$procurador;
				}else
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$emit_nacional.", ele ".$conjemit_nacional.", casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores das carteiras  de identidade RG n°(s) ".$prop_numdoc."-".maiusculo($prop_emissor)." e ".$conj_numdoc."-".maiusculo($conj_emissor).", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco." <br>".$procurador;
				}
			}
			if($prop_reg==7)//Comunhão Parcial de Bens depois da lei
			{
				if($prop_nacional==$conj_nacional)
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional.", casados  pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcasamento.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores das carteiras  de identidade RG n°(s) ".$prop_numdoc."-".maiusculo($prop_emissor)." e ".$conj_numdoc."-".maiusculo($conj_emissor).", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}else
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$emit_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcasamento.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores das carteiras  de identidade RG n°(s) ".$prop_numdoc."-".maiusculo($prop_emissor)." e ".$conj_numdoc."-".maiusculo($conj_emissor).", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}
	
			}
			if($prop_reg==2)//Comunhão Universal de Bens antes da lei
			{
					if($prop_nacional==$conj_nacional)
					{
										$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional.", casados pelo regime de Comunhão Universal de Bens, anteriormente a Lei n°  6.515/77,  em ".$prop_dtcasamento.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores das carteiras  de identidade RG n°(s) ".$prop_numdoc."-".maiusculo($prop_emissor)." e ".$conj_numdoc."-".maiusculo($conj_emissor).", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
					}else
					{
										$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$emit_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunhão Universal de Bens, anteriormente a Lei n°  6.515/77,  em ".$prop_dtcasamento.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores das carteiras  de identidade RG n°(s) ".$prop_numdoc."-".maiusculo($prop_emissor)." e ".$conj_numdoc."-".maiusculo($conj_emissor).", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
					}
			}
			if($prop_reg==3)//Comunhão Universal de Bens depois da lei
			{
				if($prop_nacional==$conj_nacional)
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." , casados pelo regime de Comunhão Universal de Bens, na vigência da Lei n°  6.515/77,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores das carteiras  de identidade RG n°(s) ".$prop_numdoc."-".maiusculo($prop_emissor)." e ".$conj_numdoc."-".maiusculo($conj_emissor).", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}else
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$emit_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunhão Universal de Bens, na vigência da Lei n°  6.515/77,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores das carteiras  de identidade RG n°(s) ".$prop_numdoc."-".maiusculo($prop_emissor)." e ".$conj_numdoc."-".maiusculo($conj_emissor).", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}
			}
			if($prop_reg==5)//Separação de Bens com pacto (não obrigatória)
			{
				if($prop_nacional==$conj_nacional)
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional.", casados  pelo regime de Separação de Bens,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores das carteiras  de identidade RG n°(s) ".$prop_numdoc."-".maiusculo($prop_emissor)." e ".$conj_numdoc."-".maiusculo($conj_emissor).", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}else
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$emit_nacional.", ele ".$conj_nacional.", casados pelo regime de Separação de Bens de Bens,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores das carteiras  de identidade RG n°(s) ".$prop_numdoc."-".maiusculo($prop_emissor)." e ".$conj_numdoc."-".maiusculo($conj_emissor).", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}
			}
			if($prop_reg==6)//Separação de Bens obrigatória
			{
				if($prop_nacional==$conj_nacional)
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional.", casados  pelo regime de Separação Obrigatória de bens, nos termos do artigo 1641 do Código Civil Brasileiro,  em ".$prop_dtcasamento.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores das carteiras  de identidade RG n°(s) ".$prop_numdoc."-".maiusculo($prop_emissor)." e ".$conj_numdoc."-".maiusculo($conj_emissor).", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}else
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$emit_nacional.", ele ".$conj_nacional.", casados pelo regime de Separação Obrigatória de bens, nos termos do artigo 1641 do Código Civil Brasileiro,  em ".$prop_dtcasamento.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores das carteiras  de identidade RG n°(s) ".$prop_numdoc."-".maiusculo($prop_emissor)." e ".$conj_numdoc."-".maiusculo($conj_emissor).", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}
			}
		}//fim de Estado Civil Casado de comprador feminino 

		// EST.  DIFERENTE DE CASADO
		if($prop_civil==1) $estciv='solteira';
		if($prop_civil==3) $estciv='separada judicialmente';
		if($prop_civil==4) $estciv='divorciada';
		if($prop_civil==5) $estciv='viúva';

		if($conj_civil==1) $pc_estciv='solteiro';
		elseif($conj_civil==3) $pc_estciv='separado judicialmente';
		elseif($conj_civil==4) $pc_estciv='divorciado';
		elseif($conj_civil==5) $pc_estciv='viúvo';

		if($prop_civil!=2 && $flguniest=='S')
		{
				if($flgpacto=='S'){
				
					$emitente="<b>".maiusculo($prop_nome)."</b>, ".$estciv.", convivendo em união estável, nos termos da Lei nº. 9.278/96 e alterações do art. 1.723 do Código Civil Brasileiro, conforme escritura de declaração lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro.", com <b>".maiusculo($conj_nome)."</b>, ".$pc_estciv.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores das carteiras  de identidade RG n°(s) ".$prop_numdoc."-".maiusculo($prop_emissor)." e ".$conj_numdoc."-".maiusculo($conj_emissor).", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}else
				{
					$emitente="<b>".maiusculo($prop_nome)."</b>, ".$estciv.", convivendo em união estável, nos termos da Lei nº. 9.278/96 e alterações do art. 1.723 do Código Civil Brasileiro com <b>".maiusculo($conj_nome)."</b>, ".$pc_estciv.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores das carteiras  de identidade RG n°(s) ".$prop_numdoc."-".maiusculo($prop_emissor)." e ".$conj_numdoc."-".maiusculo($conj_emissor).", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}
		
		}elseif($prop_civil!=2 && ($flguniest=='N' || $flguniest==''))
		{
				$emitente="<b>".maiusculo($prop_nome)."</b>, ".$emit_nacional.", ".$estciv.", ".$prop_prof.", portadora da carteira de identidade RG n° ".$prop_numdoc."-".$prop_emissor.", inscrita no CPF/MF sob o nr. ".$prop_cpf.", residente e domiciliada no(a) ".$endereco.", ".$dev1.$procurador;
		}
	}//fim Comprador Feminino
} // Fim de Proposta Simples



#########################################################################################################
#################################### QUALIFICAÇÃO DE VENDEDOR(A,ES) ##################################################
	//Informações de Procurador
	$query = "SELECT * FROM vendprocurador WHERE cod_ppst='".$cod_ppst."'";
	$result =mysql_query($query);
	$reg=mysql_fetch_array($result,MYSQL_ASSOC);
	$flgproc_v=$reg['FLGPROC_VPROC'];
	if($flgproc_v=='S')
	{
		$procurador_v=", neste ato representado(a,s) por seu(sua) bastante procurador(a) ".$reg['PROC_VPROC'].".";
		$ass_proc="P.P. ";
	}else
	{
		$procurador_v=".";
		$ass_proc="";
	}
	
	$vendedor='';
	$socio='';
	$vend_contas='';
	$assinatura_vend="<table border='0'>";
	$assinatura_socio='';
	//Informações do Vendedor
	$query = "SELECT * FROM vendedor WHERE cod_ppst='".$cod_ppst."'";
	$result =mysql_query($query);
	$linhas_v=mysql_num_rows($result);
	if($linhas_v>1)
	{
		$limite_v="; ";
	}else
	{
		$limite_v=", ";
	}
	$vend_nome='';
	$a=1;
	while($registro = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		$cod_vend[$a]		= $registro['COD_VEND'];
		$vend_nome[$a]		= $registro['NOME_VEND'];
		$vend_tipo[$a]		= $registro['TIPO_VEND'];
		$vend_ender[$a]		= $registro['ENDERECO_VEND'];
		$vend_num[$a]		= $registro['NRENDERECO_VEND'];
		$vend_cep[$a]		= formataCep($registro['CEP_VEND']);
		$vend_conta[$a]		= $registro['NRCC_VEND'];
		$vend_digito[$a]	= $registro['DVCC_VEND'];
		$vend_agencia[$a]	= $registro['NRAG_VEND'];
		$vend_bairro[$a]	= $registro['COD_BAIRRO'];
		$vend_lograd[$a]	= $registro['COD_LOGR'];
		$vend_uf[$a]		= $registro['COD_UF'];
		$vend_cidade[$a]	= $registro['COD_MUNICIPIO'];
		$vend_compl[$a]		= $registro['CPENDERECO_VEND'];
		$vend_perctual[$a]	= $registro['PERCENTUALVENDA_VEND'];
		// Digito zero
		if($vend_digito[$a]=='zero')
		{
			$vend_digito[$a]='0';
		}
		
		//Endereço do Vendedor
		$qrvlograd="SELECT desc_logr FROM logradouro WHERE cod_logr = '".$vend_lograd[$a]."' ";
		$rsvlograd =mysql_query($qrvlograd);
		$regvlograd = mysql_fetch_array($rsvlograd, MYSQL_ASSOC);
			$v_lograd[$a]= $regvlograd['desc_logr'];
		$qrvbairro="SELECT nome_bairro FROM bairro WHERE cod_bairro = '".$vend_bairro[$a]."' ";
		$rsvbairro =mysql_query($qrvbairro);
		$regvbairro = mysql_fetch_array($rsvbairro, MYSQL_ASSOC);
			$v_bairro[$a]= $regvbairro['nome_bairro'];
		$qrvcidade="SELECT nome_municipio, cod_uf FROM municipio WHERE cod_municipio = '".$vend_cidade[$a]."' ";
		$rsvcidade =mysql_query($qrvcidade);
		$regvcidade = mysql_fetch_array($rsvcidade, MYSQL_ASSOC);
			$v_cidade[$a]=$regvcidade['nome_municipio'];
			$v_uf[$a]= $regvcidade['cod_uf'];
			//COMPLEMENTO;
		if($vend_compl[$a]!=''){
	 		$vend_compl[$a]= $vend_compl[$a].", ";
	 	}else{
	 		$vend_compl[$a]="";
	 	}
	
			$v_endereco[$a]=$v_lograd[$a]." ".$vend_ender[$a].", nr. ".$vend_num[$a].", ".$vend_compl[$a].ucwords(strtolower($v_bairro[$a])).", ".ucwords(strtolower($v_cidade[$a])).", ".$v_uf[$a];	
		
		//CONTAS DOS VENDEDORES
		$vend_contas .=strtoupper($vend_nome[$a]).": Banco do Brasil S/A, Agência n° ".$vend_agencia[$a].", Conta Corrente n° ".$vend_conta[$a]."-".$vend_digito[$a]."-   ".round($vend_perctual[$a],2)."%<br>";
		
		// Vendedor Pessoa Física
		if($vend_tipo[$a]==1){
			
			// Informações de Pessoa Física do Vendedor
			$qrvf = "SELECT * FROM vendfis WHERE cod_ppst='".$cod_ppst."' and cod_vend='".$cod_vend[$a]."'";
			$rsvf =mysql_query($qrvf);
			$regvf = mysql_fetch_array($rsvf, MYSQL_ASSOC);
				$vendf_cpf[$a]			= formataCPF($regvf['CPF_VFISICA']);
				$vendf_sex[$a]			= $regvf['SEXO_VFISICA'];		
				$vendf_natur[$a]		= $regvf['NATUR_VFISICA'];
				$vendf_nrrg[$a]			= $regvf['NRRG_VFISICA'];
				$vendf_orgrg[$a]		= $regvf['ORGRG_VFISICA'];
				$vendf_dtrg[$a]			= formataDataBRA($regvf['DTRG_VFISICA']);
				$vendf_pai[$a]			= $regvf['NOMEPAI_VFISICA'];
				$vendf_mae[$a]			= $regvf['NOMEMAE_VFISICA'];
				$vendf_renda[$a]		= $regvf['VLRENDA_VFISICA'];
				$vendf_nacional[$a]		= $regvf['COD_PAIS'];
				$v_profissao[$a]		= $regvf['PROFISSAO_VFISICA'];
				$vendf_estciv[$a]		= $regvf['COD_ESTCIV'];
				$vendf_flguniest[$a]	= $regvf['FLGUNIEST_VFISICA'];
				$vendf_dtaquisimov[$a]	= $regvf['DTAQUISIMOV_VFISICA'];
				$vendf_flganuente[$a]	= $regvf['FLGANUENTE_VFISICA'];
				$qrvfpais="Select * from pais where cod_pais='".$vendf_nacional[$a]."'";
				$rsvfpais =mysql_query($qrvfpais);
				$regvfpais = mysql_fetch_array($rsvfpais, MYSQL_ASSOC);
				//Nacionalidade do Vendedor PF
				if($vendf_sex[$a]=='M')
				{
					$v_nacional[$a]= $regvfpais['NACIONALM'];
				}
				if($vendf_sex[$a]=='F')
				{
					$v_nacional[$a]= $regvfpais['NACIONALF'];
				}
				//Profissão do Vendedor PF
				//$qrvfprof="Select * from profissao where cod_prof='".$vendf_profissao[$a]."'";
				//$rsvfprof =mysql_query($qrvfprof);
				//$regvfprof = mysql_fetch_array($rsvfprof, MYSQL_ASSOC);
					//$v_profissao[$a] = strtolower($regvfprof['DESC_PROF']);
					//echo $v_profissao[$a];
			// Informações do Conjuge de Vendedor Pessoa Física
			$qrvfc = "SELECT * FROM vendfisconjuge WHERE cod_ppst='".$cod_ppst."' and cod_vend='".$cod_vend[$a]."'";
			$rsvfc =mysql_query($qrvfc);
			$regvfc = mysql_fetch_array($rsvfc, MYSQL_ASSOC);
				$vconj_regime[$a]			= $regvfc['REGIMEBENS_VFCJ'];
				$vconj_dtcasamento[$a]		= formataDataBRA($regvfc['DTCASAMENTO_VFCJ']);
				$vconj_nome[$a]				= $regvfc['NOME_VFCJ'];
				$vconj_nacional[$a]			= $regvfc['COD_PAIS'];
				$vconj_civil[$a]			= $regvfc['COD_ESTCIV'];
				$vconj_nrrg[$a]				= $regvfc['NRRG_VFCJ'];
				$vconj_dtrg[$a]				= formataDataBRA($regvfc['DTRG_VFCJ']);
				$vconj_orgrg[$a]			= $regvfc['ORGRG_VFCJ'];
				$vconj_cpf[$a]				= formataCPF($regvfc['CPF_PCCJ']);
				$vconj_cargoemp[$a]			= $regvfc['CARGOEMP_VFCJ'];
				$qrvfcpais="Select * from pais where cod_pais='".$vconj_nacional[$a]."'";
				$rsvfcpais =mysql_query($qrvfcpais);
				$regvfcpais = mysql_fetch_array($rsvfcpais, MYSQL_ASSOC);
				if($vendf_sex[$a]=='M')
				{
					$vc_nacional[$a]= $regvfcpais['NACIONALF'];
				}
				if($vendf_sex[$a]=='F')
				{
					$vc_nacional[$a]= $regvfcpais['NACIONALM'];
				}
				if($vendf_nacional[$a]==$vconj_nacional[$a])
				{
					$vnacional[$a]= $regvfcpais['NACIONALFM'];
				}
				
				$qrvfcprof="Select * from profissao where cod_prof='".$vconj_profissao[$a]."'";
				$rsvfcprof =mysql_query($qrvfcprof);
				$regvfcprof = mysql_fetch_array($rsvfcprof, MYSQL_ASSOC);
					//$vconj_cargoemp[$a] = $regvfcprof['DESC_PROF'];

			// Informações de Pacto Antenupcial de Conjuge e Vendedor Pessoa Física
			$qrvfcp = "SELECT * FROM vendfisconjugepacto WHERE cod_ppst='".$cod_ppst."' and cod_vend='".$cod_vend[$a]."'";
			$rsvfcp =mysql_query($qrvfcp);
			$regvfcp = mysql_fetch_array($rsvfcp, MYSQL_ASSOC);
				$vcpacto_data[$a]		= formataDataBRA($regvfcp['DATA_VCPA']);
				$vcpacto_loc[$a]		= $regvfcp['LOCALLAVRACAO_VCPA'];
				$vcpacto_livro[$a]		= $regvfcp['LIVRO_VCPA'];
				$vcpacto_folha[$a]		= $regvfcp['FOLHA_VCPA'];
				$vcpacto_reg[$a]		= $regvfcp['NUMEROREGISTRO_VCPA'];
			
			//Assinatura dos Vendedores
			if($vendf_estciv[$a]==2 || ($vendf_estciv[$a]!=2 && $vendf_flguniest[$a]=='S')){
				$assinatura_vend .="				
					<tr>
						<td width='200'></td>
						<td width='364' align='center'><b>________________________________________________</b></td>
					</tr>
					<tr>
						<td></td>
						<td align='center'><b>".$ass_proc.maiusculo($vend_nome[$a])." - ".$vendf_cpf[$a]."</b></td>
					</tr>
					<tr>
						<td align='center'><font color='#FFFFFF'>LN</font><br><font color='#FFFFFF'>LN</font></td>
					</tr>
					<tr>
						<td></td>
						<td align='center'><b>________________________________________________</b></td>
					</tr>
					<tr>
						<td></td>
						<td align='center'><b>".$ass_proc.maiusculo($vconj_nome[$a])." - ".$vconj_cpf[$a]."</b></td>
					</tr>
					<tr>
						<td></td>
						<td align='center'><font color='#FFFFFF'>LN</font><br><font color='#FFFFFF'>LN</font></td>
					</tr>";
			}else{
				$assinatura_vend .="				
					<tr>
						<td width='200'></td>
						<td width='364' align='center'><b>________________________________________________</b></td>
					</tr>
					<tr>
						<td></td>
						<td align='center'><b>".$ass_proc.maiusculo($vend_nome[$a])." - ".$vendf_cpf[$a]."</b></td>
					</tr>
					<tr>
						<td></td>
						<td align='center'><font color='#FFFFFF'>LN</font></td>
					</tr>";
			}	
			//§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§
			#__________________________ QUALIFICAÇÃO(A,s) VENDEDOR(A,ES) PESSOA FÍSICA _________________________#
			//§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§
					if($a==1 && $linhas_v>1)
					{
						$vendedor .="<b>".$a."-</b> ";
					}elseif($a!=1 && $linhas_v>1){
						$vendedor .=" <b>".$a."-</b> ";
					}elseif($linhas_v<1)
					{
						$vendedor .="";
					}
					if($vendf_sex[$a]=='M')// Vendedor PF Masculino
					{
						if($vendf_estciv[$a]==2)//EST. CIVIL CASADO
						{
							if($vconj_regime[$a]==1)//Comunhão Parcial de Bens antes da lei
				
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a]." ,casados pelo regime de Comunhão Parcial de Bens,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpacto_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vcpacto_reg[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores das carteiras  de identidade RG n°(s) ".$vendf_nrrg[$a]."-".maiusculo($vendf_orgrg[$a])." e ".$vconj_nrrg[$a]."-".maiusculo($vconj_orgrg[$a]).", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ele ".$v_nacional[$a].", ela ".$vc_nacional[$a].", casados pelo regime de Comunhão Parcial de Bens,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpacto_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vcpacto_reg[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores das carteiras  de identidade RG n°(s) ".$vendf_nrrg[$a]."-".maiusculo($vendf_orgrg[$a])." e ".$vconj_nrrg[$a]."-".maiusculo($vconj_orgrg[$a]).", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}
							}
							if($vconj_regime[$a]==7)//Comunhão Parcial de Bens depois da lei
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a]." ,casados pelo regime de Comunhão Parcial de Bens,  em ".$vconj_dtcasamento[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores das carteiras  de identidade nrs. ".$vendf_nrrg[$a]."-".maiusculo($vendf_orgrg[$a])." e ".$vconj_nrrg[$a]."-".maiusculo($vconj_orgrg[$a]).", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ele ".$v_nacional[$a].", ela ".$vc_nacional[$a].", casados pelo regime de Comunhão Parcial de Bens,  em ".$vconj_dtcasamento[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores das carteiras  de identidade RG n°(s) ".$vendf_nrrg[$a]."-".maiusculo($vendf_orgrg[$a])." e ".$vconj_nrrg[$a]."-".maiusculo($vconj_orgrg[$a]).", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}
					
							}
							if($vconj_regime[$a]==2)//Comunhão Universal de Bens antes da lei
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a].", casados pelo regime de Comunhão Universal de Bens, anteriormente a Lei n°  6.515/77,  em ".$vconj_dtcasamento[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores das carteiras  de identidade RG n°(s) ".$vendf_nrrg[$a]."-".maiusculo($vendf_orgrg[$a])." e ".$vconj_nrrg[$a]."-".maiusculo($vconj_orgrg[$a]).", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ele ".$vendf_nacional[$a].", ela ".$vconj_nacional[$a].", casados pelo regime de Comunhão Universal de Bens, anteriormente a Lei n°  6.515/77,  em ".$vconj_dtcasamento[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores das carteiras  de identidade RG n°(s) ".$vendf_nrrg[$a]."-".maiusculo($vendf_orgrg[$a])." e ".$vconj_nrrg[$a]."-".maiusculo($vconj_orgrg[$a]).", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}
							}
							if($vconj_regime[$a]==3)//Comunhão Universal de Bens depois da lei
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a].", casados pelo regime de Comunhão Universal de Bens, na vigência da Lei n°  6.515/77,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpacto_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vcpacto_reg[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores das carteiras  de identidade RG n°(s) ".$vendf_nrrg[$a]."-".maiusculo($vendf_orgrg[$a])." e ".$vconj_nrrg[$a]."-".maiusculo($vconj_orgrg[$a]).", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ele ".$vendf_nacional[$a].", ela ".$vconj_nacional[$a].", casados pelo regime de Comunhão Universal de Bens, na vigência da Lei n°  6.515/77,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpacto_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vcpacto_reg[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores das carteiras  de identidade RG n°(s) ".$vendf_nrrg[$a]."-".maiusculo($vendf_orgrg[$a])." e ".$vconj_nrrg[$a]."-".maiusculo($vconj_orgrg[$a]).", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}
							}
							if($vconj_regime[$a]==5)//Separação de Bens com pacto
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a]." ,casados pelo regime de Separação de Bens,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpacto_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vcpacto_reg[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores das carteiras  de identidade RG n°(s) ".$vendf_nrrg[$a]."-".maiusculo($vendf_orgrg[$a])." e ".$vconj_nrrg[$a]."-".maiusculo($vconj_orgrg[$a]).", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ele ".$vendf_nacional[$a].", ela ".$vconj_nacional[$a].", casados pelo regime de Separação de Bens de Bens,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpacto_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vcpacto_reg[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores das carteiras  de identidade RG n°(s) ".$vendf_nrrg[$a]."-".maiusculo($vendf_orgrg[$a])." e ".$vconj_nrrg[$a]."-".maiusculo($vconj_orgrg[$a]).", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}
							}
							if($vconj_regime[$a]==6)//Separação de Bens obrigatórioa
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a]." ,casados pelo regime de Separação Obrigatória de bens, nos termos do artigo 1641 do Código Civil Brasileiro,  em ".$vconj_dtcasamento[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores das carteiras  de identidade RG n°(s) ".$vendf_nrrg[$a]."-".maiusculo($vendf_orgrg[$a])." e ".$vconj_nrrg[$a]."-".maiusculo($vconj_orgrg[$a]).", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ele ".$vendf_nacional[$a].", ela ".$vconj_nacional[$a].", casados pelo regime de Separação Obrigatória de bens, nos termos do artigo 1641 do Código Civil Brasileiro,  em ".$vconj_dtcasamento[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores das carteiras  de identidade RG n°(s) ".$vendf_nrrg[$a]."-".maiusculo($vendf_orgrg[$a])." e ".$vconj_nrrg[$a]."-".maiusculo($vconj_orgrg[$a]).", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}
							}
					}//Fim de Vendedor PF Casado
		
					// EST. CIVIL DIFERENTE DE CASADO
					
					if($vendf_estciv[$a]==1) $v_estciv[$a]='solteiro';
					elseif($vendf_estciv[$a]==3) $v_estciv[$a]='separado judicialmente';
					elseif($vendf_estciv[$a]==4) $v_estciv[$a]='divorciado';
					elseif($vendf_estciv[$a]==5) $v_estciv[$a]='viúvo';
					
					if($vconj_civil[$a]==1) $vcj_estciv[$a]='solteira';
					elseif($vconj_civil[$a]==3) $vcj_estciv[$a]='separada judicialmente';
					elseif($vconj_civil[$a]==4) $vcj_estciv[$a]='divorciada';
					elseif($vconj_civil[$a]==5) $vcj_estciv[$a]='viúva';
					
					
					if($vendf_estciv[$a]!=2 && $vendf_flguniest[$a]=='S')
					{
							$vendedor .="<b>".maiusculo($vend_nome[$a])."</b>, ".$v_estciv[$a].", convivendo em união estável, nos termos da Lei nº. 9.278/96 e alterações do art. 1.723 do Código Civil Brasileiro com ".$vconj_nome[$a].", ".$vcj_estciv[$a].", brasileiros, portadores das carteiras  de identidade RG n°(s) ".$vendf_nrrg[$a]."-".maiusculo($vendf_orgrg[$a])." e ".$vconj_nrrg[$a]."-".maiusculo($vconj_orgrg[$a]).", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
					
					}elseif($vendf_estciv[$a]!=2 && ($vendf_flguniest[$a]=='N' || $vendf_flguniest[$a]==''))
					{
							$vendedor .="<b>".maiusculo($vend_nome[$a])."</b>, ".$v_nacional[$a].", ".$v_estciv[$a].", ".$v_profissao[$a].", portador da carteira de identidade RG n° ".$vendf_nrrg[$a]."-".$vendf_orgrg[$a].", inscrito no CPF/MF sob o nr. ".$vendf_cpf[$a].", residente e domiciliado no(a) ".$v_endereco[$a].$limite_v;
					}
				}//Fim Vendedor PF Masculino
					if($vendf_sex[$a]=='F')// Vendedor PF Feminino
					{
						if($vendf_estciv[$a]==2)//EST. CIVIL CASADO
						{
							if($vconj_regime[$a]==1)//Comunhão Parcial de Bens antes da lei
				
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a]." ,casados pelo regime de Comunhão Parcial de Bens,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpactio_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vpacto_reg[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores das carteiras  de identidade RG n°(s) ".$vendf_nrrg[$a]."-".maiusculo($vendf_orgrg[$a])." e ".$vconj_nrrg[$a]."-".maiusculo($vconj_orgrg[$a]).", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ela ".$v_nacional[$a].", ele ".$vc_nacional[$a].", casados pelo regime de Comunhão Parcial de Bens,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpactio_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vpacto_reg[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores das carteiras  de identidade RG n°(s) ".$vendf_nrrg[$a]."-".maiusculo($vendf_orgrg[$a])." e ".$vconj_nrrg[$a]."-".maiusculo($vconj_orgrg[$a]).", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}
							}
							if($vconj_regime[$a]==7)//Comunhão Parcial de Bens depois da lei
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a]." ,casados pelo regime de Comunhão Parcial de Bens,  em ".$vconj_dtcasamento[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores das carteiras  de identidade RG n°(s) ".$vendf_nrrg[$a]."-".maiusculo($vendf_orgrg[$a])." e ".$vconj_nrrg[$a]."-".maiusculo($vconj_orgrg[$a]).", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ela ".$v_nacional[$a].", ele ".$vc_nacional[$a].", casados pelo regime de Comunhão Parcial de Bens,  em ".$vconj_dtcasamento[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores das carteiras  de identidade RG n°(s) ".$vendf_nrrg[$a]."-".maiusculo($vendf_orgrg[$a])." e ".$vconj_nrrg[$a]."-".maiusculo($vconj_orgrg[$a]).", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}
					
							}
							if($vconj_regime[$a]==2)//Comunhão Universal de Bens antes da lei
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a].", casados pelo regime de Comunhão Universal de Bens, anteriormente a Lei n°  6.515/77,  em ".$vconj_dtcasamento[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores das carteiras  de identidade RG n°(s) ".$vendf_nrrg[$a]."-".maiusculo($vendf_orgrg[$a])." e ".$vconj_nrrg[$a]."-".maiusculo($vconj_orgrg[$a]).", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ela ".$vendf_nacional[$a].", ele ".$vconj_nacional[$a].", casados pelo regime de Comunhão Universal de Bens, anteriormente a Lei n°  6.515/77,  em ".$vconj_dtcasamento[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores das carteiras  de identidade RG n°(s) ".$vendf_nrrg[$a]."-".maiusculo($vendf_orgrg[$a])." e ".$vconj_nrrg[$a]."-".maiusculo($vconj_orgrg[$a]).", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}
							}
							if($vconj_regime[$a]==3)//Comunhão Universal de Bens depois da lei
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a].", casados pelo regime de Comunhão Universal de Bens, na vigência da Lei n°  6.515/77,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpacto_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vcpacto_reg[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores das carteiras  de identidade RG n°(s) ".$vendf_nrrg[$a]."-".maiusculo($vendf_orgrg[$a])." e ".$vconj_nrrg[$a]."-".maiusculo($vconj_orgrg[$a]).", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ela ".$vendf_nacional[$a].", ele ".$vconj_nacional[$a].", casados pelo regime de Comunhão Universal de Bens, na vigência da Lei n°  6.515/77,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpacto_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vcpacto_reg[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores das carteiras  de identidade RG n°(s) ".$vendf_nrrg[$a]."-".maiusculo($vendf_orgrg[$a])." e ".$vconj_nrrg[$a]."-".maiusculo($vconj_orgrg[$a]).", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}
							}
							if($vconj_regime[$a]==5)//Separação de Bens com pacto
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a]." ,casados pelo regime de Separação de Bens,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpacto_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vcpacto_reg[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores das carteiras  de identidade RG n°(s) ".$vendf_nrrg[$a]."-".maiusculo($vendf_orgrg[$a])." e ".$vconj_nrrg[$a]."-".maiusculo($vconj_orgrg[$a]).", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ela ".$vendf_nacional[$a].", ele ".$vconj_nacional[$a].", casados pelo regime de Separação de Bens de Bens,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpacto_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vcpacto_reg[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores das carteiras  de identidade RG n°(s) ".$vendf_nrrg[$a]."-".maiusculo($vendf_orgrg[$a])." e ".$vconj_nrrg[$a]."-".maiusculo($vconj_orgrg[$a]).", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}
							}
							if($vconj_regime[$a]==6)//Separação de Bens obrigatórioa
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a]." ,casados pelo regime de Separação Obrigatória de bens, nos termos do artigo 1641 do Código Civil Brasileiro,  em ".$vconj_dtcasamento[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores das carteiras  de identidade RG n°(s) ".$vendf_nrrg[$a]."-".maiusculo($vendf_orgrg[$a])." e ".$vconj_nrrg[$a]."-".maiusculo($vconj_orgrg[$a]).", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ela ".$vendf_nacional[$a].", ele ".$vconj_nacional[$a].", casados pelo regime de Separação Obrigatória de bens, nos termos do artigo 1641 do Código Civil Brasileiro,  em ".$vconj_dtcasamento[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores das carteiras  de identidade RG n°(s) ".$vendf_nrrg[$a]."-".maiusculo($vendf_orgrg[$a])." e ".$vconj_nrrg[$a]."-".maiusculo($vconj_orgrg[$a]).", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}
							}
					}//Fim de Vendedor PF Casado
		
					// EST. CIVIL DIFERENTE DE CASADO
					if($vendf_estciv[$a]==1) $v_estciv[$a]='solteira';
					elseif($vendf_estciv[$a]==3) $v_estciv[$a]='separada judicialmente';
					elseif($vendf_estciv[$a]==4) $v_estciv[$a]='divorciada';
					elseif($vendf_estciv[$a]==5) $v_estciv[$a]='viúva';
					
					if($vconj_civil[$a]==1) $vcj_estciv[$a]='solteiro';
					elseif($vconj_civil[$a]==3) $vcj_estciv[$a]='separado judicialmente';
					elseif($vconj_civil[$a]==4) $vcj_estciv[$a]='divorciado';
					elseif($vconj_civil[$a]==5) $vcj_estciv[$a]='viúvo';
			
					if($vendf_estciv[$a]!=2 && $vendf_flguniest[$a]=='S')
					{
							$vendedor .="<b>".maiusculo($vend_nome[$a])."</b>, ".$v_estciv[$a].", convivendo em união estável, nos termos da Lei nº. 9.278/96 e alterações do art. 1.723 do Código Civil Brasileiro com ".$vconj_nome[$a].", ".$vcj_estciv[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores das carteiras  de identidade RG n°(s) ".$vendf_nrrg[$a]."-".maiusculo($vendf_orgrg[$a])." e ".$vconj_nrrg[$a]."-".maiusculo($vconj_orgrg[$a]).", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
					
					}elseif($vendf_estciv[$a]!=2 && ($vendf_flguniest[$a]=='N' || $vendf_flguniest[$a]==''))
					{
							$vendedor .="<b>".maiusculo($vend_nome[$a])."</b>, ".$v_nacional[$a].", ".$v_estciv[$a].", ".$v_profissao[$a].", portadora da carteira de identidade RG n° ".$vendf_nrrg[$a]."-".$vendf_orgrg[$a].", inscrita no CPF/MF sob o nr. ".$vendf_cpf[$a].", residente e domiciliada no(a) ".$v_endereco[$a].$limite_v;
					}
				}//Fim Vendedor PF Feminino
				if($linhas_v==$a){
					$vendedor .=" doravante denominado(a,s) VENDEDOR(A,ES)".$procurador_v;
				}

		}//Fim Vendedor Pessoa Física

		//Vendedor Pessoa Jurídica
		if($vend_tipo[$a]==2){
			
			$qrvj = "SELECT * FROM vendjur WHERE cod_ppst='".$cod_ppst."' and cod_vend='".$cod_vend[$a]."'";
			$rsvj =mysql_query($qrvj);
			$regvj = mysql_fetch_array($rsvj, MYSQL_ASSOC);
				$vendj_cnpj[$a]				= formataCnpj($regvj['CNPJ_VJUR']);
				$vendj_versaoestat[$a]		= $regvj['VERSAOESTAT_VJUR'];
				$vendj_dtestatv[$a]			= formataDataBRA($regvj['DTESTAT_VJUR']);
				$vendj_locestat[$a]			= $regvj['LOCESTAT_VJUR'];
				$vendj_regestat[$a]			= $regvj['NRREGESTAT_VJUR'];
			
			// Informações dos Sócios do Vendedor PJ
			$qrvjs = "SELECT * FROM vendjursocio WHERE cod_ppst='".$cod_ppst."' and cod_vend='".$cod_vend[$a]."'";
			$rsvjs =mysql_query($qrvjs);
			$b=1;
			while($regvjs = mysql_fetch_array($rsvjs, MYSQL_ASSOC))
			{
				$vjsocio_nome[$a][$b]		= $regvjs['NOME_VJSOC'];
				$vjsocio_cpf[$a][$b]		= formataCPF($regvjs['CPF_VJSOC']);
				$vjsocio_sexo[$a][$b]		= $regvjs['SEXO_VJSOC'];
				$vjsocio_nacional[$a][$b]	= $regvjs['COD_PAIS'];
				$vjsocio_cidade[$a][$b]		= $regvjs['COD_MUNICIPIO'];
				$vjsocio_estciv[$a][$b]		= $regvjs['COD_ESTCIV'];
				$vjsocio_nrrg[$a][$b]		= $regvjs['NRRG_VJSOC'];
				$vjsocio_orgrg[$a][$b]		= $regvjs['ORGRG_VJSOC'];
				$vjsocio_dtrg[$a][$b]		= formataDataBRA($regvjs['DTRG_VJSOC']);
				$vjsocio_prof[$a][$b]		= $regvjs['CARGO_VJSOC'];
				$qrvjspais="Select * from pais where cod_pais='".$vjsocio_nacional[$a][$b]."'";
				$rsvjspais =mysql_query($qrvjspais);
				$regvjspais = mysql_fetch_array($rsvjspais, MYSQL_ASSOC);
				if($vjsocio_sexo[$a][$b]=='F')
				{
					$vjs_nacional[$a][$b]= $regvjspais['NACIONALF'];
				}
				if($vjsocio_sexo[$a][$b]=='M')
				{
					$vjs_nacional[$a][$b]= $regvjspais['NACIONALM'];
				}
				$qrvjscidade="SELECT nome_municipio, cod_uf FROM municipio WHERE cod_municipio = '".$vjsocio_cidade[$a][$b]."' ";
				$rsvjscidade =mysql_query($qrvjscidade);
				$regvjscidade = mysql_fetch_array($rsvjscidade, MYSQL_ASSOC);
				$vjs_cidade[$a][$b]=$regvjscidade['nome_municipio'];
				$vjs_uf[$a][$b]= $regvjscidade['cod_uf'];
				
				//Qualificação de Sócio Masculino
				if($vjsocio_sexo[$a][$b]=='M')
				{
					$ident="seu sócio";
					if($vjsocio_estciv[$a][$b]==1) $vjs_estciv[$a][$b]='solteiro';
					elseif($vjsocio_estciv[$a][$b]==2) $vjs_estciv[$a][$b]='casado';
					elseif($vjsocio_estciv[$a][$b]==3) $vjs_estciv[$a][$b]='separado judicialmente';
					elseif($vjsocio_estciv[$a][$b]==4) $vjs_estciv[$a][$b]='divorciado';
					elseif($vjsocio_estciv[$a][$b]==5) $vjs_estciv[$a][$b]='viúvo';

					$socio .="<b>Sr. ".maiusculo($vjsocio_nome[$a][$b])."</b>, ".$vjs_nacional[$a][$b].", ".$vjs_estciv[$a][$b].", ".$vjsocio_prof[$a][$b].", portador da Cédula de Identidade RG nº. ".$vjsocio_nrrg[$a][$b]."-".$vjsocio_orgrg[$a][$b].", inscrito no CPF/MF sob nº. ".$vjsocio_cpf[$a][$b].", residente e domiciliado em ".$vjs_cidade[$a][$b]."-".$vjs_uf[$a][$b]."; "; 
				}
				//Qualificação de Sócio Feminino
				if($vjsocio_sexo[$a][$b]=='F')
				{
					$ident="sua sócia";
					if($vjsocio_estciv[$a][$b]==1) $vjs_estciv[$a][$b]='solteira';
					elseif($vjsocio_estciv[$a][$b]==2) $vjs_estciv[$a][$b]='casada';
					elseif($vjsocio_estciv[$a][$b]==3) $vjs_estciv[$a][$b]='separada judicialmente';
					elseif($vjsocio_estciv[$a][$b]==4) $vjs_estciv[$a][$b]='divorciada';
					elseif($vjsocio_estciv[$a][$b]==5) $vjs_estciv[$a][$b]='viúva';
					
					$socio .="<b>Sra. ".maiusculo($vjsocio_nome[$a][$b])."</b>, ".$vjs_nacional[$a][$b].", ".$vjs_estciv[$a][$b].", ".$vjsocio_prof[$a][$b].", portadora da Cédula de Identidade RG nº. ".$vjsocio_nrrg[$a][$b]."-".$vjsocio_orgrg[$a][$b].", inscrita no CPF/MF sob nº. ".$vjsocio_cpf[$a][$b].", residente e domiciliada em ".$vjs_cidade[$a][$b]."-".$vjs_uf[$a][$b];
					$assinatura_socio .="<br>".maiusculo($vjsocio_nome[$a][$b])." - ".$vjsocio_cpf[$a][$b]."";
				}
				$b++;
			}
			//§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§
			#__________________________ QUALIFICAÇÃO(A,s) VENDEDOR(A,ES) PESSOA JURÍDICA _________________________#
			//§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§
			if($vendj_versaoestat[$a]!=''){
				$vendedor .= "<b>".maiusculo($vend_nome[$a])."</b>, com sede e foro na(o) ".$v_endereco[$a].", inscrita no CNPJ sob nº. ".$vendj_cnpj[$a].", com seu Contrato Social/consolidação e ".$vendj_versaoestat[$a]." Alteração de Contrato Social, datada de ".$vendj_dtestatv[$a].", registrada na(o) ".$vendj_locestat[$a]." sob nº. ".$vendj_regestat[$a].", doravante denominado(a) simplesmente VENDEDOR(A,ES), representado por ".$ident." ".$socio.$procurador_v;
			}else{
				$vendedor .= "<b>".maiusculo($vend_nome[$a])."</b>, com sede e foro na(o) ".$v_endereco[$a].", inscrita no CNPJ sob nº. ".$vendj_cnpj[$a].", com seu Contrato Social/consolidação registrada na(o) ".$vendj_locestat[$a]." sob nº. ".$vendj_regestat[$a].", doravante denominado(a) simplesmente VENDEDOR(A,ES), representado por ".$ident." ".$socio.$procurador_v;
			}
			$assinatura_vend .="
					<tr>
						<td width='200'></td>
						<td width='364' align='center'><b>________________________________________________</b></td>
					</tr>
					<tr>
						<td></td>
						<td align='center'><b>".$ass_proc.maiusculo($vend_nome[$a])." - ".$vendj_cnpj[$a].$assinatura_socio."</b></td>
					</tr>
					<tr>
						<td></td>
						<td align='center'><font color='#FFFFFF'>LN</font></td>
					</tr>";
		}
		$a++;
	}// Fim de Informações de Vendedor
		$assinatura_vend .="</table>";
################################ QUALIFICAÇÃO DO IMÓVEL ##################################################
			$query = "SELECT * FROM imovel WHERE cod_ppst='".$cod_ppst."'";
			$result =mysql_query($query);
			$registro = mysql_fetch_array($result, MYSQL_ASSOC);
			$qualificacao_imov= $registro['QUALIFICACAO_IMOV'];
	

##########################################################################################################
##########################################################################################################
#										CONTRATO DE FINANCIAMENTO								
##########################################################################################################		
		$titulo="<table border='0' width='728'>
					<tr>
						<td align='center'><font color='#FFFFFF'><b> CONTRATO DE COMPRA E VENDA DE IMÓVEL COM FINANCIAMENTO, ALIENAÇÃO FIDUCIÁRIA DE IMÓVEL E OUTRAS AVENÇAS - Nº ".$id_lstn."<b></td>
					</tr>
				</table><br>";
		$quadro_um="<font color='#FFFFFF'><b>PREÂMBULO</b><br><br>
		
					<b><font color='#FFFFFF'>I – PARTES</b>
					
					<p align='justify'><font color='#FFFFFF'>COMPRADOR(A,ES):<br>".$emitente."</p>
					<p align='justify'><font color='#FFFFFF'>VENDEDOR(A,ES):<br>".$vendedor."</p>
					<p align='justify'>FINANCIADOR".$credor.":<br><b>CAIXA DE PREVIDÊNCIA DOS FUNCIONÁRIOS DO BANCO DO BRASIL </b>- entidade fechada de previdência complementar constituída na forma de sociedade civil sem fins lucrativos, inscrita no CNPJ/MF sob o n.º 33.754.482/0001-24 , com sede na cidade do Rio de Janeiro – RJ, doravante denominada PREVI, neste ato representada por sua bastante procuradora <b>ATHOS GESTÃO E SERVIÇOS LTDA.</b>, inscrita no CNPJ/MF sob o n° 00.839.032/001-85 - <b>Matriz</b>, com sede na Avenida Senador Xavier da Silva n° 294, São Francisco, Curitiba-PR e <b>Filial</b> na Rua Amália de Noronha n° 159, Jardim América, São Paulo-SP, inscrita no CNPJ/MF sob o n° 00.839.032/0002-66, neste ato representada por <b>CARLOS EDUARDO VELLOSO</b>, brasileiro, casado, bel. em direito, portador da cédula de identidade RG nº. 23.220.652-1-SSP-SP, inscrito no CPF/MF sob o nº. 261.776.448-64, com endereço comercial na Cidade de São Paulo-SP, na Rua Amália de Noronha, nº. 159, Pinheiros, nos termos das procurações lavradas no 6º Ofício de Notas da Comarca do Rio de Janeiro – RJ, à fls. 162/162 do livro nº. 6.385, datada de 04/09/2008 e no 1º Tabelionato de Notas de Curitiba - PR, à fls. 056 do livro 0731-P, datada de 10/09/2008, respectivamente, que são partes integrantes do presente contrato.</p><br>";
		$quadro_dois="<b><font color='#FFFFFF'>II – IMÓVEL OBJETO DESTE CONTRATO</b>".formataTexto($qualificacao_imov);
		$quadro_tres="<b><font color='#FFFFFF'>
					III – PREÇO DA COMPRA E VENDA</b><br>					
					<table border='0' width='728'>
						<tr>
							<td width='242' align='center'><font color='#FFFFFF'>APARTAMENTO/CASA</td>
							<td width='242' align='center'><font color='#FFFFFF'>DA GARAGEM (cada vaga)</td>
							<td width='242' align='center'><font color='#FFFFFF'>TOTAL</td>
						</tr>
						<tr>
							<td align='center'><font color='#FFFFFF'>R$ ".formataMoeda($prop_vlcompra - $vlavalgar)."</td>
							<td align='center'><font color='#FFFFFF'>R$ ".formataMoeda($vlavalgar)."</td>
							<td align='center'><font color='#FFFFFF'>R$ ".formataMoeda($prop_vlcompra)."</td>
						</tr>
						<tr>
							<td colspan='3'><font color='#FFFFFF'>VALOR DE AVALIAÇÃO: R$ ".formataMoeda($vlavaliacao)."</td>
						</tr>
						
					</table><br>";
		$quadro_quatro="<b><font color='#FFFFFF'>
					IV – ORIGEM DOS RECURSOS PARA PAGAMENTO DA COMPRA E VENDA</b><br>					
					<table border='0' width='728'>
						<tr>
							<td><font color='#FFFFFF'>1. Recursos próprios ".$dev3.": R$ ".formataMoeda($prop_vlentrada)."</td>
						</tr>
						<tr>
							<td><font color='#FFFFFF'>2. Recursos de FGTS: R$ ".formataMoeda($vlfgts)."</td>
						</tr>
						<tr>
							<td><font color='#FFFFFF'>3. Recursos próprios de DEVEDOR(A,ES) a título de sinal a ser ressarcido pela PREVI e incluído no financiamento: R$ ".formataMoeda($prop_vlsinal)."</td>
						</tr>
						<tr>
							<td><font color='#FFFFFF'>4. Recursos do Financiamento: R$ ".formataMoeda($vltotfinan)."</td>
						</tr>
					</table><br>";
		$quadro_cinco="<b><font color='#FFFFFF'>
					V – GRAVAMES EXISTENTES SOBRE O IMÓVEL</b><br>					
					<table border='0' width='728'>
						<tr>
							<td width='182' align='center'><font color='#FFFFFF'>GRAVAME</td>
							<td width='182' align='center'><font color='#FFFFFF'>FAVORECIDO</td>
							<td width='182' align='center'><font color='#FFFFFF'>N° DO REGISTRO OU INSCRIÇÃO</td>
							<td width='182' align='center'><font color='#FFFFFF'>CARTÓRIO DE REGISTRO DE IMÓVEIS</td>
						</tr>
						<tr>
							<td align='center'><font color='#FFFFFF'>".$gravame_crd."</td>
							<td align='center'><font color='#FFFFFF'>".$desc_crd."</td>
							<td align='center'><font color='#FFFFFF'>".$reg_crd."</td>
							<td align='center'><font color='#FFFFFF'>".$cart_crd."</td>
						</tr>
						
					</table><br>";
		$quadro_seis="<b><font color='#FFFFFF'>
					VI – FORMA DE LIBERAÇÃO DOS RECURSOS DO FINANCIAMENTO</b><br>					
					<table border='0' width='728'>
						<tr>
							<td><font color='#FFFFFF'>1. Recursos liberados ao(s) VENDEDOR(A,ES) (somados recursos de FGTS e do FINANCIAMENTO): R$ ".formataMoeda($vlliberado-$prop_vlsinal)."</td>
						</tr>
						<tr>
							<td><font color='#FFFFFF'>2. Recursos liberados ao CREDOR QUITANTE (recursos do Financiamento) por meio de Cheque Administrativo do Banco do Brasil S.A.: R$ ".formataMoeda($rec_crd)."</td>
						</tr>
						<tr>
							<td><font color='#FFFFFF'>3. Recursos liberados ".$dev4." (em caso de ressarcimento de sinal): R$ ".formataMoeda($prop_vlsinal)."</td>
						</tr>
						<tr>
							<td><font color='#FFFFFF'>4. Crédito em conta-corrente:<br>
								<font color='#FFFFFF'>4.1 VENDEDOR(A,ES): ".$vend_contas."
								<font color='#FFFFFF'>4.2 COMPRADOR(A,ES): ".$prop_contas."
								
						</tr>
					</table><br>";
		$quadro_sete="<b><font color='#FFFFFF'>
					<font color='#FFFFFF'>VII – CONDIÇÕES DO FINANCIAMENTO</b><br>					
					<table border='0' width='728'>
						<tr>
							<td><font color='#FFFFFF'>1. Valor do Financiamento: R$".formataMoeda($vltotfinan)."</td>
						</tr>
						<tr>
							<td><font color='#FFFFFF'>2. Prazo total do Financiamento: ".$prazo_finan." meses</td>
						</tr>
						<tr>
							<td><font color='#FFFFFF'>3. Índice de Atualização Monetária: INPC</td>
						</tr>
						<tr>
							<td><font color='#FFFFFF'>4. Taxa Efetiva Anual de Juros: 5,750 %<br>5. Taxa Efetiva Mensal de Juros: 0,467%</td>
						</tr>
						<tr>
							<td><font color='#FFFFFF'>6. Fundo de Hedge (FH): %<br>
								<font color='#FFFFFF'>6.1 - Fundo de Liquidez (FL): 0,24% a.a<br>
								<font color='#FFFFFF'>6.2 - Fundo de Quitação por Morte (FQM): 0,25% a.a. até 60 anos  ou 1,80% a.a. a partir de 60 anos</td>
						</tr>
						<tr>
							<td><font color='#FFFFFF'>7. Data de Vencimento do primeiro pagamento mensal: 20/".proxMes($asscontrato)."/2008</td>
						</tr>
						<tr>
							<td><font color='#FFFFFF'>8. Forma de pagamento: consignação em folha de pagamento do BB, PREVI ou INSS</td>
						</tr>
					</table><br>";
		$quadro_oito="<b><font color='#FFFFFF'>
					VIII – ENCARGOS MENSAIS</b><br>					
					<table border='0' width='728'>
						<tr>
							<td><font color='#FFFFFF'>1. Prestação contratual mensal (principal, juros e fundo de hedge): R$ ".formataMoeda($parcela_finan)."</td>
						</tr>
						<tr>
							<td><font color='#FFFFFF'>2. Taxa de Administração Mensal: R$ 19,00</td>
						</tr>
					</table><br>";
		$quadro_nove="<b><font color='#FFFFFF'>
					IV – SEGURO DE DANOS FÍSISCOS AO IMÓVEL</b><br>					
					<table border='0' width='728'>
						<tr>
							<td><font color='#FFFFFF'>1. Valor do primeiro prêmio anual: R$ ".formataMoeda($vl_premio)."</td>
						</tr>
						<tr>
							<td><font color='#FFFFFF'>2. Data do pagamento: 20/".proxMes($asscontrato)."/2008</td>
						</tr>
					</table><br>";
		$quadro_dez="<b><font color='#FFFFFF'>
					X – RENDA BRUTA BASE DO(S) DEVEDOR(A,ES)</b><br>					
					<table border='0' width='728'>
						<tr>
							<td width='546' align='center'><font color='#FFFFFF'>NOMES</td>
							<td width='182' align='center'><font color='#FFFFFF'>VALORES</td>
						</tr>
						<tr>
							<td><font color='#FFFFFF'>".$prop_nome."</td>
							<td align='center'><font color='#FFFFFF'>LN</font><br>
								<font color='#FFFFFF'>R$ ".formataMoeda($prop_renda)."<br>
								<font color='#FFFFFF'>________________<br>
								<font color='#FFFFFF'>R$ ".formataMoeda($prop_renda)."
								</td>
						</tr>
						
					</table><br>";
		$quadro_onze="<b><font color='#FFFFFF'>
					XI – RESPONSABILIDADE DO(S) DEVEDOR(A,ES) NO PAGAMENTO DAS PRESTAÇÕES</b><br>					
					<table border='0' width='728'>
						<tr>
							<td width='546' align='center'><font color='#FFFFFF'>NOMES</td>
							<td width='182' align='center'><font color='#FFFFFF'>PERCENTUAL</td>
						</tr>
						<tr>
							<td><font color='#FFFFFF'>".$prop_nome."</td>
							<td align='center'><font color='#FFFFFF'>LN<br>
								100%<br>
								________________<br>
								100%
								</td>
						</tr>
						
					</table><br>";
		$quadro_doze="<b><font color='#FFFFFF'>
					XII – CLÁUSULAS ADICIONAIS</b></font><br><br><font color='#FFFFFF'>".$infofort."</font>".$infoadicional;
		//Instanciation of inherited class
		$pdf=new HTML2FPDF();
		//$pdf=new FPDF();
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
	
		//$pdf->Cell(0,5,'CÉDULA DE CRÉDITO BANCÁRIO',0,2,'C');
		$pdf->WriteHTML($titulo);
		$pdf->WriteHTML($quadro_um);
		$pdf->WriteHTML($quadro_dois);
		$pdf->WriteHTML($quadro_tres);
		$pdf->WriteHTML($quadro_quatro);
		$pdf->WriteHTML($quadro_cinco);
		$pdf->WriteHTML($quadro_seis);
		$pdf->WriteHTML($quadro_sete);
		$pdf->WriteHTML($quadro_oito);
		$pdf->WriteHTML($quadro_nove);
		$pdf->WriteHTML($quadro_dez);
		$pdf->WriteHTML($quadro_onze);
		$pdf->WriteHTML($quadro_doze);
		//$pdf->WriteHTML($fixa);
		$pdf->Ln(0);
		
		$pdf->Output();
		

?>
