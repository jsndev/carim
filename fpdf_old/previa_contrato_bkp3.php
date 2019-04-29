<?php
//$dir = getcwd();
//echo $dir;
error_reporting(0);

require_once "pdf/html2fpdf.php";
//require_once "class/dbclasses.class.php";
//require_once "class/db.class.php";
$cod_ppst=$_GET['cod_proposta'];
$resp=$_GET['resp'];

############################CONECTA NO BANCO DE DADOS#############################################

$BD_SERVIDOR = "localhost";
$BD_NOME	= "carim"; //banco de dados
$BD_USUARIO	= "sistema";
$BD_SENHA	= "for/17!kc";
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
function proxMesAno($data){
$dia=date("d",strtotime($data));
$mes=date("m",strtotime($data));
$ano=date("Y",strtotime($data));

$proxAno=date("Y",mktime(0,0,0,$mes+1,$dia,$ano));

return $proxAno;
}

#____________________________________ Formata Texto _________________________________________

function formataTexto($texto)
{
	$texto=str_replace("<p>","<p align='justify'>",$texto);
	$texto=str_replace("<p align='justify'>&nbsp;</p>","",$texto);
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
	$bairro_ppnt 	= $registro['BAIRRO_PPNT'];
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
	
	if($bairro_ppnt==''){
		$query="SELECT nome_bairro FROM bairro WHERE cod_bairro = '".$prop_bairro."' ";
		$result =mysql_query($query);
		$registro = mysql_fetch_array($result, MYSQL_ASSOC);
		$p_bairro= $registro['nome_bairro'];
	}else{
		$p_bairro=$bairro_ppnt;
	}
	
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
	if($prop_nacional==$conj_nacional && $prop_civil==2)
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
	$query="Select * from intvquitante where cod_ppst='".$cod_ppst."'";
	$result=mysql_query($query);
	$registro=mysql_fetch_array($result,MYSQL_ASSOC);
	$desc_crd		= $registro['NOME_INTQ'];
	$qualificacao_crd= $registro['QUALIFICACAO_INTQ'];

	if($flg_crd=='S'){
		if($desc_crd==''){
			$credor=" E INTERVENIENTE QUITANTE";
			$desc_crd="Caixa de Previdência dos Funcionários do Banco do Brasil";
		}else{
			if(substr_count($desc_crd, 'Previ')>0){
				$credor=" E INTERVENIENTE QUITANTE";
				$exibir_interveniente='N';
			}else{
				$credor="";
				$exibir_interveniente='S';
			}
		}
	}else{
		$gravame_crd= "não há<br><font color='#FFFFFF'>LN</font><br>";
		$reg_crd= "não há<br><font color='#FFFFFF'>LN</font><br>";
		$cart_crd= "não há<br><font color='#FFFFFF'>LN</font><br>";
		$rec_crd= "não há<br><font color='#FFFFFF'>LN</font><br>";
		$credor="";
		$desc_crd="não há<br><font color='#FFFFFF'>LN</font><br>";		
	}
	
		// ____________PROCURADOR CONTRATO_________________________________________
	$query = "SELECT * FROM contrato_config WHERE PADRAO_CONTC='1' LIMIT 1";
	$result =mysql_query($query);
	$registro = mysql_fetch_array($result, MYSQL_ASSOC);
	$nome_procurador		= $registro['NOME_CONTC'];
	$dados_procurador		= $registro['DADOSPROC_CONTC'];

	
	// ____________Dados de Valores para Item III _____________________________________
	$query="Select * from imovel where cod_ppst='".$cod_ppst."'";
	$result=mysql_query($query);
	$registro=mysql_fetch_array($result,MYSQL_ASSOC);
	$vlavaliacao		= $registro['VLAVALIACAO_IMOV'];
	$vlavalsemgar		= $registro['VLAVALSEMGAR_IMOV'];
	$vlavalgar			= $registro['VLAVALGAR_IMOV'];
	
	// ____________Dados de Valores para Item IV ______________________________________
	// Valor FGTS
	$query="Select SUM(VALORDEBITADO) AS total from contasfgts where cod_usua='".$cod_usuario."'";
	$result=mysql_query($query);
	$registro=mysql_fetch_array($result,MYSQL_ASSOC);
	$vlfgts			= $registro['total'];
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
	}
	*/
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
					<td align='center'><b>".maiusculo($conj_nome)." - ".$conj_cpf."</b></td>
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
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional.", casados  pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores dos Documentos de Identificação n°(s) ".$prop_numdoc.", expedido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", expedido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}else
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$emit_nacional.", ela ".$conjemit_nacional.", casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores dos Documentos de Identificação n°(s) ".$prop_numdoc.", expedido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", expedido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}
			}
			if($prop_reg==7)//Comunhão Parcial de Bens depois da lei
			{
				if($prop_nacional==$conj_nacional)
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional.", casados  pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcasamento.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores dos Documentos de Identificação n°(s) ".$prop_numdoc.", expedido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", expedido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}else
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$emit_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcasamento.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores dos Documentos de Identificação n°(s) ".$prop_numdoc.", expedido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", expedido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}
	
			}
			if($prop_reg==2)// Comunhão Universal de Bens antes da lei
			{
					if($prop_nacional==$conj_nacional)
					{
										$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional.", casados pelo regime de Comunhão Universal de Bens, anteriormente a Lei n°  6.515/77,  em ".$prop_dtcasamento.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores dos Documentos de Identificação n°(s) ".$prop_numdoc.", expedido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", expedido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
					}else
					{
										$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$emit_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunhão Universal de Bens, anteriormente a Lei n°  6.515/77,  em ".$prop_dtcasamento.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores dos Documentos de Identificação n°(s) ".$prop_numdoc.", expedido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", expedido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
					}
			}
			if($prop_reg==3)//Comunhão Universal de Bens depois da lei
			{
				if($prop_nacional==$conj_nacional)
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." , casados pelo regime de Comunhãoooo Universal de Bens, na vigência da Lei n°  6.515/77,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores dos Documentos de Identificação n°(s) ".$prop_numdoc.", expedido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", expedido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}else
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$emit_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunhão Universal de Bens, na vigência da Lei n°  6.515/77,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores dos Documentos de Identificação n°(s) ".$prop_numdoc.", expedido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", expedido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}
			}
			if($prop_reg==5)//Separação de Bens com pacto (não obrigatória)
			{
				if($prop_nacional==$conj_nacional)
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional.", casados  pelo regime de Separação de Bens,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores dos Documentos de Identificação n°(s) ".$prop_numdoc.", expedido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", expedido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}else
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$emit_nacional.", ela ".$conj_nacional.", casados pelo regime de Separação de Bens de Bens,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores dos Documentos de Identificação n°(s) ".$prop_numdoc.", expedido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", expedido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}
			}
	 
			if($prop_reg==6)//Separação de Bens obrigatória
			{
				if($prop_nacional==$conj_nacional)
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional.", casados  pelo regime de Separação Obrigatória de bens, nos termos do artigo 1641 do Código Civil Brasileiro,  em ".$prop_dtcasamento.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores dos Documentos de Identificação n°(s) ".$prop_numdoc.", expedido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", expedido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}else
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$emit_nacional.", ela ".$conj_nacional.", casados pelo regime de Separação Obrigatória de bens, nos termos do artigo 1641 do Código Civil Brasileiro,  em ".$prop_dtcasamento.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores dos Documentos de Identificação n°(s) ".$prop_numdoc.", expedido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", expedido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
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
		$maior=($prop_civil==1)?' maior,':'';
				if($flgpacto=='S'){			
				$emitente="<b>".maiusculo($prop_nome)."</b>, ".$emit_nacional.", ".$prop_prof.", ".$estciv.",$maior portador do Documento de identificação nº ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc.", inscrito no CPF/MF sob n° ".$prop_cpf."  <b> e ".maiusculo($conj_nome)."</b>, ".$conjemit_nacional.", ".$conj_profissao.", ".$pc_estciv.", portador do Documento de Identificação n° ".$conj_numdoc.", emitido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", inscrito no CPF/MF sob n° ".$conj_cpf.", convivendo em união estável, nos termos da Lei nº. 9.278/96 e alterações do art. 1.723 do Código Civil Brasileiro, conforme escritura de declaração lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
					
				}else
				{
					$emitente="<b>".maiusculo($prop_nome)."</b>, ".$emit_nacional.", ".$prop_prof.", ".$estciv.",$maior portador do Documento de identificação nº ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc.", inscrito no CPF/MF sob n° ".$prop_cpf."  <b> e ".maiusculo($conj_nome)."</b>, ".$conjemit_nacional.", ".$conj_profissao.", ".$pc_estciv.", portador do Documento de Identificação n° ".$conj_numdoc.", emitido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", inscrito no CPF/MF sob n° ".$conj_cpf.", convivendo em união estável, nos termos da Lei nº. 9.278/96 e alterações do art. 1.723 do Código Civil Brasileiro, residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}
				
		}elseif($prop_civil!=2 && ($flguniest=='N' || $flguniest==''))
		{
				$emitente="<b>".maiusculo($prop_nome)."</b>, ".$emit_nacional.", ".$prop_prof.", ".$estciv.", maior, portador do Documento de identificação nº ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc.", inscrito no CPF/MF sob o nr. ".$prop_cpf.", residente e domiciliado no(a) ".$endereco.", ".$dev1.$procurador;
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
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional.", casados  pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores dos Documentos de Identificação n°(s) ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", emitido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco." <br>".$procurador;
				}else
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$emit_nacional.", ele ".$conjemit_nacional.", casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores dos Documentos de Identificação n°(s) ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", emitido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco." <br>".$procurador;
				}
			}
			if($prop_reg==7)//Comunhão Parcial de Bens depois da lei
			{
				if($prop_nacional==$conj_nacional)
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional.", casados  pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcasamento.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores dos Documentos de Identificação n°(s) ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", emitido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}else
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$emit_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunhão Parcial de Bens,  em ".$prop_dtcasamento.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores dos Documentos de Identificação n°(s) ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", emitido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}
	
			}
			if($prop_reg==2)//Comunhão Universal de Bens antes da lei
			{
					if($prop_nacional==$conj_nacional)
					{
										$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional.", casados pelo regime de Comunhão Universal de Bens, anteriormente a Lei n°  6.515/77,  em ".$prop_dtcasamento.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores dos Documentos de Identificação n°(s) ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", emitido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
					}else
					{
										$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$emit_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunhão Universal de Bens, anteriormente a Lei n°  6.515/77,  em ".$prop_dtcasamento.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores dos Documentos de Identificação n°(s) ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", emitido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
					}
			}
			if($prop_reg==3)//Comunhão Universal de Bens depois da lei
			{
				if($prop_nacional==$conj_nacional)
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." , casados pelo regime de Comunhão Universal de Bens, na vigência da Lei n°  6.515/77,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores dos Documentos de Identificação n°(s) ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", emitido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}else
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$emit_nacional.", ele ".$conjemit_nacional.", casados pelo regime de Comunhão Universal de Bens, na vigência da Lei n°  6.515/77,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores dos Documentos de Identificação n°(s) ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", emitido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}
			}
			if($prop_reg==5)//Separação de Bens com pacto (não obrigatória)
			{
				if($prop_nacional==$conj_nacional)
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional.", casados  pelo regime de Separação de Bens,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores dos Documentos de Identificação n°(s) ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", emitido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}else
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$emit_nacional.", ele ".$conj_nacional.", casados pelo regime de Separação de Bens de Bens,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores dos Documentos de Identificação n°(s) ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", emitido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}
			}
			if($prop_reg==6)//Separação de Bens obrigatória
			{
				if($prop_nacional==$conj_nacional)
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional.", casados  pelo regime de Separação Obrigatória de bens, nos termos do artigo 1641 do Código Civil Brasileiro,  em ".$prop_dtcasamento.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores dos Documentos de Identificação n°(s) ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", emitido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}else
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$emit_nacional.", ele ".$conj_nacional.", casados pelo regime de Separação Obrigatória de bens, nos termos do artigo 1641 do Código Civil Brasileiro,  em ".$prop_dtcasamento.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores dos Documentos de Identificação n°(s) ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", emitido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
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
		$maior=($prop_civil==1)?' maior,':'';
				if($flgpacto=='S'){			
				$emitente="<b>".maiusculo($prop_nome)."</b>, ".$emit_nacional.", ".$prop_prof.", ".$estciv.",$maior portador do Documento de Identificação nº ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc.", inscrito no CPF/MF sob n° ".$prop_cpf."  <b> e ".maiusculo($conj_nome)."</b>, ".$conjemit_nacional.", ".$conj_profissao.", ".$pc_estciv.", portador do Documento de Identificação n° ".$conj_numdoc.", emitido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", inscrito no CPF/MF sob n° ".$conj_cpf.", convivendo em união estável, nos termos da Lei nº. 9.278/96 e alterações do art. 1.723 do Código Civil Brasileiro, conforme escritura de declaração lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
					
				}else
				{
					$emitente="<b>".maiusculo($prop_nome)."</b>, ".$emit_nacional.", ".$prop_prof.", ".$estciv.",$maior portador do Documento de Identificação nº ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc.", inscrito no CPF/MF sob n° ".$prop_cpf."  <b> e ".maiusculo($conj_nome)."</b>, ".$conjemit_nacional.", ".$conj_profissao.", ".$pc_estciv.", portador do Documento de Identificação n° ".$conj_numdoc.", emitido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", inscrito no CPF/MF sob n° ".$conj_cpf.", convivendo em união estável, nos termos da Lei nº. 9.278/96 e alterações do art. 1.723 do Código Civil Brasileiro, residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}
		}elseif($prop_civil!=2 && ($flguniest=='N' || $flguniest==''))
		{
				$emitente="<b>".maiusculo($prop_nome)."</b>, ".$emit_nacional.", ".$prop_prof.", ".$estciv.", maior, portadora do Documento de Identificação nº ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc.", inscrita no CPF/MF sob o nr. ".$prop_cpf.", residente e domiciliada no(a) ".$endereco.", ".$dev1.$procurador;
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
		$qualificacao_vend =$registro['QUALIFICACAO_VEND'];
		$vend_conta2[$a]	= $registro['NRCC2_VEND'];
		$vend_digito2[$a]	= $registro['DVCC2_VEND'];
		$vend_agencia2[$a]	= $registro['NRAG2_VEND'];
		$vend_banco2[$a]	= $registro['BANCO_VEND'];
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
		if($vend_banco2[$a]==''){
			$vend_contas .=strtoupper($vend_nome[$a]).": Banco do Brasil S/A, Agência n° ".$vend_agencia[$a].", Conta Corrente n° ".$vend_conta[$a]."-".$vend_digito[$a]."-   ".round($vend_perctual[$a],2)."%<br>";
		}else{
			$vend_contas .=strtoupper($vend_nome[$a]).": ".$vend_banco2[$a].", Agência n° ".$vend_agencia2[$a].", Conta Corrente n° ".$vend_conta2[$a]."-".$vend_digito2[$a]."-   ".round($vend_perctual[$a],2)."%<br>";
		}
		
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
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a]." ,casados pelo regime de Comunhão Parcial de Bens,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpacto_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vcpacto_reg[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores do Documento de Identificação n°(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ele ".$v_nacional[$a].", ela ".$vc_nacional[$a].", casados pelo regime de Comunhão Parcial de Bens,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpacto_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vcpacto_reg[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores do Documento de Identificação n°(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}
							}
							if($vconj_regime[$a]==7)//Comunhão Parcial de Bens depois da lei
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a]." ,casados pelo regime de Comunhão Parcial de Bens,  em ".$vconj_dtcasamento[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores do Documento de Identificação n°(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ele ".$v_nacional[$a].", ela ".$vc_nacional[$a].", casados pelo regime de Comunhão Parcial de Bens,  em ".$vconj_dtcasamento[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores do Documento de Identificação n°(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}
					
							}
							if($vconj_regime[$a]==2)//Comunhão Universal de Bens antes da lei
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a].", casados pelo regime de Comunhão Universal de Bens, anteriormente a Lei n°  6.515/77,  em ".$vconj_dtcasamento[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores do Documento de Identificação n°(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ele ".$v_nacional[$a].", ela ".$vc_nacional[$a].", casados pelo regime de Comunhão Universal de Bens, anteriormente a Lei n°  6.515/77,  em ".$vconj_dtcasamento[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores do Documento de Identificação n°(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}
							}
							if($vconj_regime[$a]==3)//Comunhão Universal de Bens depois da lei
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a].", casados pelo regime de Comunhão Universal de Bens, na vigência da Lei n°  6.515/77,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpacto_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vcpacto_reg[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores do Documento de Identificação n°(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ele ".$v_nacional[$a].", ela ".$vc_nacional[$a].", casados pelo regime de Comunhão Universal de Bens, na vigência da Lei n°  6.515/77,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpacto_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vcpacto_reg[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores do Documento de Identificação n°(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}
							}
							if($vconj_regime[$a]==5)//Separação de Bens com pacto
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a]." ,casados pelo regime de Separação de Bens,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpacto_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vcpacto_reg[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores do Documento de Identificação n°(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ele ".$v_nacional[$a].", ela ".$vc_nacional[$a].", casados pelo regime de Separação de Bens de Bens,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpacto_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vcpacto_reg[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores do Documento de Identificação n°(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}
							}
							if($vconj_regime[$a]==6)//Separação de Bens obrigatórioa
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a]." ,casados pelo regime de Separação Obrigatória de bens, nos termos do artigo 1641 do Código Civil Brasileiro,  em ".$vconj_dtcasamento[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores do Documento de Identificação n°(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ele ".$v_nacional[$a].", ela ".$vc_nacional[$a].", casados pelo regime de Separação Obrigatória de bens, nos termos do artigo 1641 do Código Civil Brasileiro,  em ".$vconj_dtcasamento[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores do Documento de Identificação n°(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
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
							
					$vendedor.="<b>".maiusculo($vend_nome[$a])."</b>, ".$v_nacional[$a].", ".$v_profissao[$a].", ".$v_estciv[$a].", portador do Documento de Identificação nº ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a].", inscrito no CPF/MF sob n° ".$vendf_cpf[$a]."  <b> e ".$vconj_nome[$a]."</b>, ".$vc_nacional[$a].", ".$vconj_cargoemp[$a].", ".$vcj_estciv[$a].", portador do Documento de Identificação n° ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", inscrito no CPF/MF sob n° ".$vconj_cpf[$a].", convivendo em união estável, nos termos da Lei nº. 9.278/96 e alterações do art. 1.723 do Código Civil Brasileiro, residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
					
					}elseif($vendf_estciv[$a]!=2 && ($vendf_flguniest[$a]=='N' || $vendf_flguniest[$a]==''))
					{
					$vendedor .="<b>".maiusculo($vend_nome[$a])."</b>, ".$v_nacional[$a].", ".$v_profissao[$a].", ".$v_estciv[$a].", maior, portador do Documento de Identificação nº ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a].", inscrito no CPF/MF sob o nr. ".$vendf_cpf[$a].", residente e domiciliado no(a) ".$v_endereco[$a].$limite_v;
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
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a]." ,casados pelo regime de Comunhão Parcial de Bens,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpactio_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vpacto_reg[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores do Documento de Identificação n°(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ela ".$v_nacional[$a].", ele ".$vc_nacional[$a].", casados pelo regime de Comunhão Parcial de Bens,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpactio_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vpacto_reg[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores do Documento de Identificação n°(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}
							}
							if($vconj_regime[$a]==7)//Comunhão Parcial de Bens depois da lei
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a]." ,casados pelo regime de Comunhão Parcial de Bens,  em ".$vconj_dtcasamento[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores do Documento de Identificação n°(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ela ".$v_nacional[$a].", ele ".$vc_nacional[$a].", casados pelo regime de Comunhão Parcial de Bens,  em ".$vconj_dtcasamento[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores do Documento de Identificação n°(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}
					
							}
							if($vconj_regime[$a]==2)//Comunhão Universal de Bens antes da lei
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a].", casados pelo regime de Comunhão Universal de Bens, anteriormente a Lei n°  6.515/77,  em ".$vconj_dtcasamento[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores do Documento de Identificação n°(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ela ".$v_nacional[$a].", ele ".$vc_nacional[$a].", casados pelo regime de Comunhão Universal de Bens, anteriormente a Lei n°  6.515/77,  em ".$vconj_dtcasamento[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores do Documento de Identificação n°(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}
							}
							if($vconj_regime[$a]==3)//Comunhão Universal de Bens depois da lei
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a].", casados pelo regime de Comunhão Universal de Bens, na vigência da Lei n°  6.515/77,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpacto_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vcpacto_reg[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores do Documento de Identificação n°(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ela ".$v_nacional[$a].", ele ".$vc_nacional[$a].", casados pelo regime de Comunhão Universal de Bens, na vigência da Lei n°  6.515/77,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpacto_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vcpacto_reg[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores do Documento de Identificação n°(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}
							}
							if($vconj_regime[$a]==5)//Separação de Bens com pacto
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a]." ,casados pelo regime de Separação de Bens,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpacto_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vcpacto_reg[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores do Documento de Identificação n°(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ela ".$v_nacional[$a].", ele ".$vc_nacional[$a].", casados pelo regime de Separação de Bens de Bens,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpacto_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vcpacto_reg[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores do Documento de Identificação n°(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}
							}
							if($vconj_regime[$a]==6)//Separação de Bens obrigatórioa
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a]." ,casados pelo regime de Separação Obrigatória de bens, nos termos do artigo 1641 do Código Civil Brasileiro,  em ".$vconj_dtcasamento[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores do Documento de Identificação n°(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ela ".$v_nacional[$a].", ele ".$vc_nacional[$a].", casados pelo regime de Separação Obrigatória de bens, nos termos do artigo 1641 do Código Civil Brasileiro,  em ".$vconj_dtcasamento[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores do Documento de Identificação n°(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
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
					$vendedor.="<b>".maiusculo($vend_nome[$a])."</b>, ".$v_nacional[$a].", ".$v_profissao[$a].", ".$v_estciv[$a].", portador do Documento de Identificação nº ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a].", inscrito no CPF/MF sob n° ".$vendf_cpf[$a]."  <b> e ".$vconj_nome[$a]."</b>, ".$vc_nacional[$a].", ".$vconj_cargoemp[$a].", ".$vcj_estciv[$a].", portador do Documento de Identificação n° ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", inscrito no CPF/MF sob n° ".$vconj_cpf[$a].", convivendo em união estável, nos termos da Lei nº. 9.278/96 e alterações do art. 1.723 do Código Civil Brasileiro, residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;

					
					}elseif($vendf_estciv[$a]!=2 && ($vendf_flguniest[$a]=='N' || $vendf_flguniest[$a]==''))
					{
					$vendedor .="<b>".maiusculo($vend_nome[$a])."</b>, ".$v_nacional[$a].", ".$v_profissao[$a].", ".$v_estciv[$a].", maior, portadora do Documento de Identificação nº ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a].", inscrita no CPF/MF sob o nr. ".$vendf_cpf[$a].", residente e domiciliada no(a) ".$v_endereco[$a].$limite_v;
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

					$socio .="<b>Sr. ".maiusculo($vjsocio_nome[$a][$b])."</b>, ".$vjs_nacional[$a][$b].", ".$vjs_estciv[$a][$b].", ".$vjsocio_prof[$a][$b].", portador do Documento de Identificação nº. ".$vjsocio_nrrg[$a][$b].", emitido por ".$vjsocio_orgrg[$a][$b]." em ".$vjsocio_dtrg[$a][$b].", inscrito no CPF/MF sob nº. ".$vjsocio_cpf[$a][$b].", residente e domiciliado em ".$vjs_cidade[$a][$b]."-".$vjs_uf[$a][$b]."; "; 
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
					
					$socio .="<b>Sra. ".maiusculo($vjsocio_nome[$a][$b])."</b>, ".$vjs_nacional[$a][$b].", ".$vjs_estciv[$a][$b].", ".$vjsocio_prof[$a][$b].", portadora do Documento de Identificação nº. ".$vjsocio_nrrg[$a][$b].", emitido por ".$vjsocio_orgrg[$a][$b]." em ".$vjsocio_dtrg[$a][$b].", inscrita no CPF/MF sob nº. ".$vjsocio_cpf[$a][$b].", residente e domiciliada em ".$vjs_cidade[$a][$b]."-".$vjs_uf[$a][$b];
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
		
		if($qualificacao_vend!=''){
			$vendedor=$qualificacao_vend;
		}
################################ QUALIFICAÇÃO DO IMÓVEL ##################################################
			$query = "SELECT * FROM imovel WHERE cod_ppst='".$cod_ppst."'";
			$result =mysql_query($query);
			$registro = mysql_fetch_array($result, MYSQL_ASSOC);
			$qualificacao_imov= $registro['QUALIFICACAO_IMOV'];


##########################################################################################################
##########################################################################################################
#										CONTRATO DE FINANCIAMENTO								
##########################################################################################################		
		if($exibir_interveniente=='S'){
			$interveniente_quitante="<p align='justify'>INTERVENIENTE QUITANTE:<br>".formataTexto($qualificacao_crd)."</p>";
		}	
		$titulo="<table border='1' width='728'>
					<tr>
						<td align='center'><b> CONTRATO DE COMPRA E VENDA DE IMÓVEL COM FINANCIAMENTO, ALIENAÇÃO FIDUCIÁRIA DE IMÓVEL E OUTRAS AVENÇAS - Nº ".$id_lstn."<b></td>
					</tr>
				</table><br>";
		$quadro_um="<b>PREÂMBULO</b><br><br>
		
					<b>I – PARTES</b>
					
					<p align='justify'>COMPRADOR(A,ES):<br>".$emitente."</p>
					<p align='justify'>VENDEDOR(A,ES):<br>".$vendedor."</p>
					<p align='justify'>FINANCIADOR".$credor.":<br><b>CAIXA DE PREVIDÊNCIA DOS FUNCIONÁRIOS DO BANCO DO BRASIL - PREVI </b>- entidade fechada de previdência complementar constituída na forma de sociedade civil sem fins lucrativos, integrante do Sistema Financeiro da Habitação nos termos da Resolução nº 3157 do Banco Central, inscrita no CNPJ/MF sob o n.º 33.754.482/0001-24 , com sede na Praia de Botafogo, n° 501, 3° e 4° pavimento na cidade do Rio de Janeiro – RJ, doravante denominada PREVI, neste ato representada por sua bastante procuradora <b>ATHOS GESTÃO E SERVIÇOS LTDA.</b>, inscrita no CNPJ/MF sob o n° 00.839.032/0001-85 - <b>Matriz</b>, com sede na Rua José de Alencar n° 60, Curitiba-PR e <b>Filial</b> na Rua Amália de Noronha n° 159, Jardim América, São Paulo-SP, inscrita no CNPJ/MF sob o n° 00.839.032/0002-66, neste ato representada por <b>$nome_procurador</b>, $dados_procurador</p>".$interveniente_quitante;
		$quadro_dois="<b>II – IMÓVEL OBJETO DESTE CONTRATO</b>".formataTexto($qualificacao_imov);
		$quadro_tres="<b>
					III – PREÇO DA COMPRA E VENDA</b><br>					
					<table border='1' width='728'>
						<tr>
							<td width='242' align='center'>APARTAMENTO/CASA</td>
							<td width='242' align='center'>DA GARAGEM (cada vaga)</td>
							<td width='242' align='center'>TOTAL</td>
						</tr>
						<tr>
							<td align='center'>R$ ".formataMoeda($prop_vlcompra - $vlavalgar)."</td>
							<td align='center'>R$ ".formataMoeda($vlavalgar)."</td>
							<td align='center'>R$ ".formataMoeda($prop_vlcompra)."</td>
						</tr>
						<tr>
							<td colspan='3'>VALOR DE AVALIAÇÃO: R$ ".formataMoeda($vlavaliacao)."</td>
						</tr>
						
					</table><br>";
		$quadro_quatro="<b>
					IV – ORIGEM DOS RECURSOS PARA PAGAMENTO DA COMPRA E VENDA</b><br>					
					<table border='1' width='728'>
						<tr>
							<td>1. Recursos próprios ".$dev3.": R$ ".formataMoeda($prop_vlentrada)."</td>
						</tr>
						<tr>
							<td>2. Recursos de FGTS: R$ ".formataMoeda($vlfgts)."</td>
						</tr>
						<tr>
							<td>3. Recursos próprios de DEVEDOR(A,ES) a título de sinal a ser ressarcido pela PREVI e incluído no financiamento: R$ ".formataMoeda($prop_vlsinal)."</td>
						</tr>
						<tr>
							<td>4. Recursos do Financiamento: R$ ".formataMoeda($vltotfinan)."</td>
						</tr>
					</table><br>";
		$quadro_cinco="<b>
					V – GRAVAMES EXISTENTES SOBRE O IMÓVEL</b><br>					
					<table border='1' width='728'>
						<tr>
							<td width='182' align='center'>GRAVAME</td>
							<td width='182' align='center'>FAVORECIDO</td>
							<td width='182' align='center'>N° DO REGISTRO OU INSCRIÇÃO</td>
							<td width='182' align='center'>CARTÓRIO DE REGISTRO DE IMÓVEIS</td>
						</tr>
						<tr>
							<td align='center'>".$gravame_crd."</td>
							<td align='center'>".$desc_crd."</td>
							<td align='center'>".$reg_crd."</td>
							<td align='center'>".$cart_crd."</td>
						</tr>
						
					</table><br>";
		$quadro_seis="<b>
					VI – FORMA DE LIBERAÇÃO DOS RECURSOS DO FINANCIAMENTO</b><br>					
					<table border='1' width='728'>
						<tr>
							<td>1. Recursos liberados ao(s) VENDEDOR(A,ES) (somados recursos de FGTS e do FINANCIAMENTO): R$ ".formataMoeda($vlliberado-$prop_vlsinal)."</td>
						</tr>
						<tr>
							<td>2. Recursos liberados ao CREDOR QUITANTE (recursos do Financiamento) por meio de Cheque Administrativo do Banco do Brasil S.A.: R$ ".formataMoeda($rec_crd)."</td>
						</tr>
						<tr>
							<td>3. Recursos liberados ".$dev4." (em caso de ressarcimento de sinal): R$ ".formataMoeda($prop_vlsinal)."</td>
						</tr>
						<tr>
							<td>4. Crédito em conta-corrente:<br>
								4.1 VENDEDOR(A,ES): ".$vend_contas."
								4.2 COMPRADOR(A,ES): ".$prop_contas."
								
						</tr>
					</table><br>";
		$quadro_sete="<b>
					VII – CONDIÇÕES DO FINANCIAMENTO</b><br>					
					<table border='1' width='728'>
						<tr>
							<td>1. Valor do Financiamento: R$".formataMoeda($vltotfinan)."</td>
						</tr>
						<tr>
							<td>2. Prazo total do Financiamento: ".$prazo_finan." meses</td>
						</tr>
						<tr>
							<td>3. Índice de Atualização Monetária: INPC</td>
						</tr>
						<tr>
							<td>4. Taxa Efetiva Anual de Juros: 5,00 %<br>5. Taxa Efetiva Mensal de Juros: 0,407%</td>
						</tr>
						<tr>
							<td>6. Fundo de Hedge (FH): %<br>
								6.1 - Fundo de Liquidez (FL): 0,24% a.a<br>
								6.2 - Fundo de Quitação por Morte (FQM): 0,25% a.a. até 60 anos  ou 1,80% a.a. a partir de 60 anos</td>
						</tr>
						<tr>
							<td>7. Data de Vencimento do primeiro pagamento mensal: ";
							if($asscontrato!=''){ 
							$quadro_sete.="20/".proxMes($asscontrato)."/".proxMesAno($asscontrato);
							}
				$quadro_sete.="</td>
						</tr>
						<tr>
							<td>8. Forma de pagamento: consignação em folha de pagamento do BB, PREVI ou INSS</td>
						</tr>
					</table><br>";
		$quadro_oito="<b>
					VIII – ENCARGOS MENSAIS</b><br>					
					<table border='1' width='728'>
						<tr>
							<td>1. Prestação contratual mensal (principal, juros e fundo de hedge): R$ ".formataMoeda($parcela_finan)."</td>
						</tr>
						<tr>
							<td>2. Taxa de Administração Mensal: R$ 19,00</td>
						</tr>
					</table><br>";
		$quadro_nove="<b>
					IX – SEGURO DE DANOS FÍSICOS AO IMÓVEL</b><br>					
					<table border='1' width='728'>
						<tr>
							<td>1. Valor do primeiro prêmio anual: R$ ".formataMoeda($vl_premio)."</td>
						</tr>
						<tr>
							<td>2. Data do pagamento: ";
							if($asscontrato!=''){ 
							$quadro_nove.="20/".proxMes($asscontrato)."/".proxMesAno($asscontrato);
							}
				$quadro_nove.="</td>
						</tr>
					</table><br>";
		$quadro_dez="<b>
					X – RENDA BRUTA BASE DO(S) DEVEDOR(A,ES)</b><br>					
					<table border='1' width='728'>
						<tr>
							<td width='546' align='center'>NOMES</td>
							<td width='182' align='center'>VALORES</td>
						</tr>
						<tr>
							<td>".$prop_nome."</td>
							<td align='center'><font color='#FFFFFF'>LN</font><br>
								R$ ".formataMoeda($prop_renda)."<br>
								________________<br>
								R$ ".formataMoeda($prop_renda)."
								</td>
						</tr>
						
					</table><br>";
		$quadro_onze="<b>
					XI – RESPONSABILIDADE DO(S) DEVEDOR(A,ES) NO PAGAMENTO DAS PRESTAÇÕES</b><br>					
					<table border='1' width='728'>
						<tr>
							<td width='546' align='center'>NOMES</td>
							<td width='182' align='center'>PERCENTUAL</td>
						</tr>
						<tr>
							<td>".$prop_nome."</td>
							<td align='center'><font color='#FFFFFF'>LN</font><br>
								100%<br>
								________________<br>
								100%
								</td>
						</tr>
						
					</table><br>";
		$quadro_doze="<b>
					INFORMAÇÕES ADICIONAIS</b><br><br>".$infofort."
					<font color='#FFFFFF'>LN</font><br>
					<font color='#FFFFFF'>LN</font><br>
					<font color='#FFFFFF'>LN</font><br>
					<font color='#FFFFFF'>LN</font><br>
					<font color='#FFFFFF'>LN</font><br>
					<font color='#FFFFFF'>LN</font><br>
					<font color='#FFFFFF'>LN</font><br>
					<font color='#FFFFFF'>LN</font><br>
					<font color='#FFFFFF'>LN</font><br>
					<font color='#FFFFFF'>LN</font><br>
					<font color='#FFFFFF'>LN</font><br>
					";
		$fixa=" <hr>
				<p align='justify'>
										REÚNEM-SE as Partes, anteriormente nomeadas e qualificadas, para, de mútuo acordo, celebrar, por meio deste instrumento particular com força de escritura pública, nos termos do artigo 61, § 5º da Lei nº 4.380 de 21/08/64, com as alterações introduzidas pela Lei nº 5.049, de 29.06.66, o  presente CONTRATO DE COMPRA E VENDA DE IMÓVEL COM FINANCIAMENTO, GARANTIA DE ALIENAÇÃO FIDUCIÁRIA E OUTRAS AVENÇAS (“Contrato”), integrado para todos os efeitos de direito pelos Itens que se encontram preenchidos no preâmbulo, garantido por alienação fiduciária de imóvel, constituída nos termos da Lei nº 9.514/97, e regido pelas cláusulas, termos e condições a seguir:</p>
				<p align='justify'><b>
										CLÁUSULA 1 – COMPRA E VENDA DO IMÓVEL</b></p>
				<p align='justify'>
										O(A,s) VENDEDOR(A,ES), na qualidade de proprietário(a,s) e legítimo(a,s) possuidor(a,es) do imóvel descrito e caracterizado no Item II (“Imóvel”), pelo presente Contrato, vende e transfere ".$dev4.", que compra(m) e adquire(m) referido Imóvel, pelo preço certo e ajustado constante do Item III, na forma mencionada no Item VI, por meio dos recursos mencionados no Item IV.</p>
				<p align='justify'><b>
										Declarações do(a,s) VENDEDOR(A,ES)</b></p>
			    <p align='justify'>
										1.1. O(A,s) VENDEDOR(A,ES) declara(m) que: i) o Imóvel encontra-se livre e desembaraçado de quaisquer ônus, gravames ou restrições, pessoais ou reais, de qualquer origem, inclusive fiscal, judicial ou negocial extrajudicial, exceto os gravames descritos no Item V, os quais são liberados conforme previsto na cláusula 2 deste Contrato; ii) não existem, contra si ou contra qualquer dos antigos proprietários do Imóvel, ações reais ou pessoais reipersecutórias, conforme certidões expedidas pelo Registro de Imóveis competente; iii) sobre o Imóvel não pesam débitos fiscais, condominiais ou de contribuições devidas a associação que congregue os moradores do conjunto imobiliário a que pertence o Imóvel.</p>
				<p align='justify'>
										1.1.1. O(A,s) VENDEDOR(A,ES) declara(m), ainda, que o seu estado civil é aquele que se encontra descrito no Item I. Caso viva em união estável, seu(sua) companheiro(a), qualificado(a) também no Item I, assina este Contrato, expressando sua integral anuência com a presente compra e venda, sem que tal concordância tenha qualquer reflexo de caráter registrário, pois não infringidos os princípios da especialidade subjetiva e da continuidade.</p>
				<p align='justify'><b>
										Quitação do preço do Imóvel</b></p>
				<p align='justify'>
										1.2. Considerando que receberá o pagamento do preço da presente compra e venda diretamente da PREVI, à exceção dos valores já pagos a título de sinal ".$dev5.", conforme descrito no Item IV, nos termos deste Contrato, o(a,s) VENDEDOR(A,ES), neste ato, dá(dão) ".$dev4." a mais plena, geral, rasa e irrevogável quitação do preço do Imóvel, para dele nada mais reclamar com respeito a tal preço, podendo, tão somente, reclamar da PREVI a liberação dos recursos do Financiamento e do Fundo de Garantia por Tempo de Serviço - FGTS, na forma do Item VI, que ocorrerá conforme os termos e condições deste Contrato.</p>
				<p align='justify'><b>
										Transferência da posse sobre o Imóvel</b></p>
				<p align='justify'>						
										1.3. Em decorrência da compra e venda e da transferência do domínio sobre o Imóvel, efetuadas na forma do caput desta cláusula, o(a,s) VENDEDOR(A,ES) cede(m) e transfere(m), neste ato, ".$dev4.", que os adquire, toda a posse que exerce sobre o Imóvel, bem como todos os direitos, pretensões e ações, inclusive possessórias, de sua titularidade, relativas ao Imóvel, para que ".$dev2." dele use, goze e livremente disponha, como proprietário exclusivo que passa a ser.</p>
				<p align='justify'><b>				
										Responsabilidade pela evicção</b></p>
				<p align='justify'>
										1.4. O(A,s) VENDEDOR(A,ES) obriga(m)-se por si, seus herdeiros e sucessores, a qualquer título, a fazer esta venda sempre boa, firme e valiosa e a responder pela evicção de direito na forma da lei.</p>
				<p align='justify'><b>				
										Responsabilidade por tributos e contribuições</b></p>
				<p align='justify'>
										1.5. Por força da aquisição do Imóvel, correrão por conta exclusiva ".$dev3." todos os tributos e contribuições que, a partir desta data, venham a incidir sobre a propriedade, posse ou utilização do Imóvel.</p>
				<p align='justify'>				
										1.5.1. Caso o Imóvel integre condomínio de utilização ou qualquer conjunto imobiliário administrado por associação de moradores, todas as contribuições relativas ao condomínio de utilização ou contribuições devidas à referida associação de moradores, a partir desta data, passam a ser de responsabilidade ".$dev3.".</p>
				<p align='justify'><b>				
										Imposto sobre Transmissão de Bens Imóveis – ITBI</b></p>
				<p align='justify'>
										1.6. É anexada à primeira via deste Contrato a guia de recolhimento do Imposto sobre Transmissão de Bens Imóveis – ITBI, devidamente paga ".$dev5.", referente à transmissão do Imóvel por força da presente compra e venda.</p>
				<p align='justify'><b>
										CLÁUSULA 2 – DO GRAVAME SOBRE O IMÓVEL</b></p>
				<p align='justify'>
										Caso, nesta data, exista gravame hipotecário ou de alienação fiduciária sobre o Imóvel, conforme mencionado no Item V, constituído em favor do CREDOR QUITANTE para garantir o cumprimento de obrigação do(a,s) VENDEDOR(A,ES), este(a,s), neste ato, autoriza(m) expressamente a PREVI a entregar ao CREDOR QUITANTE, do montante do financiamento ora concedido, o valor mencionado no número 2 do Item VI, quitando as obrigações do(a,s) VENDEDOR(A,ES) perante o CREDOR QUITANTE, e remindo, dessa forma, o gravame existente sobre o Imóvel. O montante entregue ao CREDOR QUITANTE será deduzido do valor a ser entregue ao(s) VENDEDOR(A,ES) por força do financiamento ora concedido, conforme disposto na cláusula 3.2.</p>
				<p align='justify'><b>				
										Quitação das obrigações e liberação do gravame</b></p>
				<p align='justify'>						
										2.1. O CREDOR QUITANTE, concordando com a compra e venda do Imóvel ora contratada e recebendo da PREVI, neste ato, por meio de pagamento em cheque conforme descrito no Item VI, os recursos correspondentes ao pagamento de seu crédito, dá ao(s) VENDEDOR(A,ES) a mais plena, rasa e irrevogável quitação com relação a tal dívida e autoriza expressamente o Sr. Oficial do Cartório de Registro de Imóveis competente a proceder o cancelamento do referido gravame existente sobre o Imóvel, desde que o faça concomitantemente com registro da propriedade fiduciária constituída em favor da PREVI nos termos da cláusula 13.</p>
				<p align='justify'><b>				
										Custos e despesas com o cancelamento</b></p>
				<p align='justify'>
										2.2. ".$dev6." arcará(ão) com todos os custos e despesas referentes ao cancelamento do gravame existente sobre o Imóvel mencionado nesta cláusula, pagando-os diretamente ao Oficial do Cartório de Registro de Imóveis competente.</p>
				<p align='justify'><b>			
										Condição suspensiva</b></p>
				<p align='justify'>
										2.3. Além das condições estipuladas na cláusula 3.1, a liberação dos recursos referentes ao Financiamento conforme descrito nos itens 1 e 3 do Item VI encontra-se suspensivamente condicionada à comprovação à PREVI, por meio da entrega da ficha de matrícula atualizada do Imóvel, do efetivo cancelamento do gravame existente sobre o Imóvel em favor do CREDOR QUITANTE.</p>
				<p align='justify'><b>										
										CLÁUSULA 3 – FINANCIAMENTO</b></p>
				<p align='justify'>
										Para possibilitar o pagamento do preço do Imóvel, a PREVI, neste ato, concede ".$dev4." um financiamento, no valor total estipulado no Item VII (“Financiamento”), utilizando-se, para tanto, de recursos próprios, oriundos dos recursos garantidores do Plano de Benefícios 01, administrado pela PREVI.</p>
				<p align='justify'><b>				
										Condição suspensiva</b></p>
				<p align='justify'>
										3.1. Sem prejuízo das demais condições estipuladas neste Contrato, a entrega dos recursos do Financiamento ao(s) VENDEDOR(A,ES) e ".$dev4.", no caso de ressarcimento de sinal, fica suspensivamente condicionada à comprovação à PREVI do efetivo e perfeito registro, junto à matrícula do Imóvel, da garantia de alienação fiduciária constituída nos termos da cláusula 13, o qual será realizado pelo Oficial de Registro de Imóveis competente. A comprovação do registro referido nesta cláusula dar-se-á pela entrega da ficha de matrícula atualizada do Imóvel.</p>
				<p align='justify'><b>								
										Verificação da condição suspensiva</b></p>
				<p align='justify'>
										3.2. Verificada a condição suspensiva estipulada na cláusula 3.1, a PREVI liberará os recursos referentes ao Financiamento, entregando-os diretamente ao(s) VENDEDOR(A,ES), a título de pagamento do preço do Imóvel, observado sempre o disposto na cláusula 2.1.</p>
				<p align='justify'>				
										3.2.1. Caso parte do preço do Imóvel seja pago pela utilização de recursos do Fundo de Garantia por Tempo de Serviço – FGTS ".$dev3.", a PREVI liberará tais recursos diretamente ao(s) VENDEDOR(A,ES) ou ao CREDOR QUITANTE, após satisfeita a condição suspensiva estipulada na cláusula 2.3, acima, ou após tê-los recebidos da Caixa Econômica Federal – CEF, o que ocorrer depois.</p>
				<p align='justify'><b>				
										CLÁUSULA 4 – ATUALIZAÇÃO DO SALD".$dev6."</b></p>
				<p align='justify'>
										O montante total do Saldo Devedor (conforme definido na cláusula 4.1.) será atualizado, desde a presente data e com periodicidade mensal pela aplicação da variação mensal do índice atuarial utilizado para a remuneração básica dos recursos garantidores do Plano de Benefícios 01 da PREVI, com defasagem de 2 (dois) meses da data de atualização do saldo ".$dev3."</p>
				<p align='justify'><b>								
										Saldo Devedor</b></p>
				<p align='justify'>			
										4.1. Para os fins deste Contrato, “Saldo Devedor” significa o valor de principal do Financiamento ainda não amortizado, atualizado na forma prevista na cláusula 4.</p>
				<p align='justify'><b>				
										Forma da atualização do Saldo Devedor</b></p>
				<p align='justify'>
										4.2. O índice de atualização monetária, definido no  número 3 do Item VII , incidirá sobre o Saldo Devedor antes da aplicação, sobre este, dos juros e encargos incorridos naquele mês, e antes da imputação dos pagamentos efetuados ".$dev5." naquele mês.</p>
				<p align='justify'>				
										4.2.1. Para todos os efeitos deste Contrato, as quantias devidas por força da atualização do Saldo Devedor serão acrescidas ao valor do principal do Financiamento.</p>
				<p align='justify'><b>								
										Substituição do índice</b></p>
				<p align='justify'>
										4.3. Caso o índice mencionado no número 3 do Item VII venha a ser substituído por outro índice de cunho nacional, e que se enquadre como referencial para a reavaliação atuarial do Plano de Benefícios 01 da PREVI, este novo índice será utilizado para efeito de atualização monetária, na forma e periodicidade estabelecida neste instrumento, a partir de sua adoção.</p>
				<p align='justify'>				
										4.3.1. No caso de substituição do índice mencionado no número 3 do Item VII, a PREVI divulgará, por meio de seus canais de comunicação institucionais, o novo índice a ser utilizado. </p>
				<p align='justify'><b>
										CLÁUSULA 5 – JUROS</b></p>
				<p align='justify'>
										Sobre o Saldo Devedor (conforme definido na cláusula 4.1.), acrescido da atualização monetária nos termos da cláusula 4, incidirão, desde a presente data, e com periodicidade mensal, juros à taxa efetiva mensal estipulada no número 5 do Item VII deste Contrato correspondentes aos juros atuariais e previstos no Regulamento do Plano de Benefícios 01, da PREVI, os quais deverão ser pagos mensalmente, conforme previsto na cláusula 7.</p>
				<p align='justify'><b>				
										Alteração da taxa efetiva de juros</b></p>
				<p align='justify'>
										5.1. Caso a taxa de juros atuarial do Plano de Benefícios 01 da PREVI, ao qual ".$dev2." encontra(m)-se vinculado(a,s), venha a ser alterada, esta nova taxa de juros será aplicada sobre o Saldo Devedor, nos termos da cláusula 4, a partir de sua adoção.</p>
				<p align='justify'>				
										5.2 A taxa de juros a que se refere a cláusula 5 será acrescida de 2%a.a. (dois por cento ao ano) se ".$dev2." desligar(em)-se do Plano de Benefícios 01, administrado pela PREVI, e deixar de receber proventos da Patrocinadora do respectivo Plano de Benefícios, adequando-se o valor da prestação à nova condição contratual.</p>
				<p align='justify'><b>				
										CLÁUSULA 6 - FUNDO DE HEDGE</b></p>
				<p align='justify'>			
										Será também cobrado mensalmente, a título de contribuição ao Fundo de Hedge, composto pelo Fundo de Liquidez e pelo Fundo de Quitação por Morte, percentual sobre o Saldo Devedor, diferenciado de acordo com a idade ".$dev3."</p>
				<p align='justify'><b>				
										Fundo de Liquidez</b></p>
				<p align='justify'>
										6.1 O Fundo de Liquidez será formado por contribuições mensais calculadas sobre o valor do saldo devedor atualizado,  pela aplicação do percentual definido no número 6.1 do Item VII,  e serão destinadas a quitar eventual resíduo do Saldo Devedor existente após o pagamento da última prestação, desde que o referido resíduo não tenha sido causado por inadimplemento ".$dev3."</p>
				<p align='justify'><b>				
										Fundo de Quitação por Morte</b></p>
				<p align='justify'>						
										6.2 O Fundo de Quitação por Morte (FQM) será formado por contribuições mensais calculadas sobre o valor do Saldo Devedor atualizado e em percentual definido em função da idade ".$dev3.", pela aplicação dos percentuais definidos no número 6.2 do Item VII e se destinará a quitar todas as obrigações vincendas em caso de morte ".$dev3."</p>
				<p align='justify'>
										6.2.1 - O percentual relativo ao FQM será alterado automaticamente,  durante a vigência do contrato,  em função da mudança de idade ".$dev3.", conforme definido no número 6.2 do Item VII. </p>
				<p align='justify'>				
										6.2.2 Havendo mais de um DEVEDOR(A,ES) o FQM quitará apenas as parcelas vincendas relativas ".$dev4." falecido(a,s), na proporção indicada no Item XI. </p>
				<p align='justify'><b>				
										Alteração dos percentuais devidos ao Fundo de Hedge</b></p>
				<p align='justify'>
										6.3 A PREVI poderá rever, periodicamente, em virtude da ocorrência de alteração do risco a ser coberto, as taxas que compõe o Fundo de Hedge, visando manter seu equilíbrio.   Será dada ampla divulgação, por meio dos canais  de comunicação institucionais da PREVI, a mudança do percentual aplicado, visto que esta poderá resultar em alteração do valor da prestação mensal.</p>				
				<p align='justify'><b>
										CLÁUSULA 7 – PAGAMENTO</b></p>
				<p align='justify'>
										".$dev6." obriga(m)-se a: i) reembolsar(em) a PREVI os prêmios de seguro por ela pagos, na forma da cláusula 11; ii) pagar(em) mensalmente as despesas de administração referidas na cláusula 12 ; e iii) pagar(em) a prestação que vencerá em cada mês, calculada na forma da cláusula 7.1.2 . </p>
				<p align='justify'><b>				
										Prestação  mensal</b></p>
				<p align='justify'>
										7.1. A prestação, composta pela soma dos valores relativos a amortização do capital, juros e contribuições ao Fundo de Hedge, deverá ser paga em parcelas mensais, consecutivas e postecipadas, sendo a primeira vencível no dia 20 do mês seguinte ao da celebração do contrato e as demais no dia 20 dos meses subseqüentes.</p>
				<p align='justify'>				
										7.1.1  No pagamento da primeira prestação serão cobrados, pro-rata dia, os encargos constantes nas cláusulas 5 e 6,  devidos no período compreendido entre a data do contrato e a data do vencimento da primeira prestação.</p>
				<p align='justify'>				
										7.1.2 O valor das prestações será recalculado anualmente, no mês de aniversário do contrato, de acordo com a fórmula abaixo:</p>
				
				<p>
							a=( b x ( 1 + d ) ) x ( 1/c + ( e + f ) )</p>
							<p>Onde:<br><br>
							a = prestação recalculada<br>
							b = saldo devedor na data do recálculo<br>
							c = prazo remanescente em meses<br>
							d = taxa do índice atuarial projetado para os próximos 12 (doze) meses<br>
							e = taxa mensal equivalente aos juros atuariais  estabelecidos para o Plano de Benefícios 01da PREVI<br>
							f = taxa mensal equivalente do Fundo de Hedge
						</p>
				<p align='justify'><b>				
										Vencimento pelo decurso do prazo</b></p>
				<p align='justify'>
										7.2. O vencimento das obrigações ".$dev3." decorrentes deste Contrato dar-se-á nas datas estipuladas nesta cláusula, independentemente de qualquer comunicação, notificação ou interpelação, aplicando-se o previsto no art. 397 do Código Civil.</p>
				<p align='justify'>
										7.2.1. O vencimento da primeira prestação, bem como da taxa de administração e do prêmio de seguro, dar-se-ão na data mencionada na cláusula 7.1, ainda que, até essa data, não tenham sido liberados, total ou parcialmente, ao(s) VENDEDOR(A,ES) ou ao CREDOR QUITANTE os recursos referentes ao Financiamento, uma vez que: i) por força da quitação dada pelo(a,s) VENDEDOR(A,ES) relativa ao preço da compra e venda, a PREVI já está, desde esta data, obrigada a liberar ao(s) VENDEDOR(A,ES) os recursos do Financiamento, tendo reservado em sua tesouraria tais recursos; e ii) a posse direta, bem como o uso e gozo do Imóvel já foram transferidos ".$dev4.", fruindo este plenamente, desde já, os efeitos econômicos deste Financiamento.</p>
				<p align='justify'><b>
										Forma de pagamento</b></p>
				<p align='justify'>
										7.3 ".$dev6." obriga(m)-se a pagar todas as obrigações decorrentes deste Contrato por meio de consignação em folha de pagamento de salários ou benefícios pagos pelo Banco do Brasil S.A., PREVI e/ou INSS, ficando a PREVI, desde já, em caráter irrevogável e irretratável, autorizada a consignar na folha de pagamento ".$dev3.", conforme mencionado no número 8 do Item VII, quaisquer obrigações  decorrentes deste Contrato.</p>
				<p align='justify'>
										7.3.1. Caso ".$dev2.", no curso deste Contrato, receba(m) salário insuficiente e/ou deixe de receber salário ou benefício do Banco do Brasil S.A., PREVI e/ou INSS a forma de pagamento das obrigações ".$dev3." prevista no número 8 do Item VII, será alterada, de forma que tais pagamentos passem a ser efetuados por meio de débito na conta corrente indicada ".$dev5." no número 4.2 do Item VI. Dessa forma, ".$dev2.", desde logo, autoriza(m), em caráter irrevogável e irretratável, para todos os efeitos legais e contratuais, que o Banco do Brasil S.A., sob pedido da PREVI, efetue o débito em sua conta corrente de todo e qualquer valor decorrente das obrigações assumidas.</p>
				<p align='justify'>				
										7.3.2 Para efeito do disposto na cláusula 7.3.1 ".$dev2." obriga(m)-se a manter conta-corrente no Banco do Brasil S.A., cabendo a ele informar à PREVI agência e o número da conta corrente  quando houver qualquer alteração do número da mesma.</p>
				<p align='justify'>				
										7.3.3 A PREVI, a seu critério, poderá alterar a forma de pagamento para liquidação por meio de boleto de cobrança bancária. Neste caso, a PREVI passará a enviar os respectivos boletos ".$dev4.", os quais deverão ser liquidados na forma neles estabelecida. A falta de recebimento de qualquer dos boletos não eximirá ".$dev2." de realizar(em) os pagamentos na data em que forem devidos, devendo ser realizados na forma indicada pela PREVI.</p>
				<p align='justify'>
										7.3.4 ".$dev6." que, por qualquer motivo, deixar(em) de receber os benefícios do INSS por meio da folha de pagamentos da PREVI, neste ato expressamente autoriza(m) a PREVI a consignar o desconto das prestações mensais, no todo ou em parte, diretamente na folha daquele Instituto.</p>
				<p align='justify'><b>
										Imputação do pagamento</b></p>
				<p align='justify'>
										7.4. Os pagamentos realizados ".$dev5." imputar-se-ão nas obrigações devidas na seguinte ordem: i) a taxa de administração nos termos da cláusula 12; ii) o reembolso dos prêmios de seguro pagos pela PREVI, nos termos da cláusula 11; iii) a contribuição devida ao Fundo de Hedge; iv) a liquidação dos juros remuneratórios ; v) a liquidação dos juros e encargos moratórios, eventualmente devidos; e vi) a amortização do principal.</p>
				<p align='justify'><b>
										Limitação do valor das prestações</b></p>
				<p align='justify'>
										7.5 O valor da prestação mensal, conforme definido na cláusula 7.1 ficará limitado a, no máximo, 30% (trinta por cento) dos proventos brutos mensais contidos na folha de pagamentos do mês anterior à data do vencimento. A presente limitação não se estende às obrigações acessórias, como a taxa de administração e as despesas de seguros.</p>
				<p align='justify'>						
										7.5.1 Para ".$dev2." aposentado(a,s) ou pensionista(s) considera-se como proventos brutos a soma dos benefícios recebidos da PREVI e do INSS. Caso ".$dev2." não receba(m) os benefícios do INSS via folha de pagamentos da PREVI e na ausência de comprovação do benefício recebido por aquele Instituto, será utilizado para compor o total de proventos brutos, para os fins desta cláusula, o valor do teto vigente de benefícios definido pelo INSS.</p>
				<p align='justify'>						
										7.5.2 Para ".$dev2." aposentado(a,s) ou pensionista(s) que receba apenas o benefício do INSS pela PREVI, será considerado como proventos brutos, para fins da limitação de que trata esta cláusula, a renda bruta que serviu de base para a concessão do financiamento, devidamente atualizada pelo índice previsto na cláusula4.</p>
				<p align='justify'>						
										7.5.3 A".$dev2." que recebe(m) da PREVI a antecipação da complementação de aposentadoria, sem o benefício do INSS, considera-se como proventos brutos apenas os valores pagos pela PREVI, até a concessão de aposentadoria pelo INSS, após este evento será aplicado o disposto na cláusula 7.5.1.</p>
				<p align='justify'>						
										7.5.4 Se ".$dev2.", por qualquer motivo, deixar de receber(em) proventos do Banco do Brasil S.A., da PREVI ou do INSS será considerada como renda bruta, para fins da limitação prevista na cláusula 7.5 a renda bruta que serviu de base para a concessão do financiamento, devidamente atualizada pelo índice previsto na cláusula 4.</p>
				<p align='justify'>						
										7.5.5 Caso ".$dev2." que tiver(em) rompido o vínculo empregatício com o Banco do Brasil S.A. e cancelado sua inscrição junto à PREVI vier a reingressar nos quadros do Banco do Brasil S.A., independentemente de nova adesão à PREVI, será considerada, para os efeitos de limitação previstos na cláusula 7.5 a renda bruta que serviu de base para a concessão do financiamento, devidamente atualizada pelo índice previsto na cláusula 4.</p>
				<p align='justify'>						
										7.5.6 Eventual resíduo existente ao final do contrato decorrente da limitação tratada nesta cláusula será liquidado com recursos do Fundo de Liquidez, previsto na cláusula 6.1.</p>
				<p align='justify'>						
										7.5.7 A limitação dos 30% (trinta por cento) não se estende aos valores cobrados relativos a obrigações de competências anteriores à vigente, em decorrência de reprocessamento ou acerto.</p>
				<p align='justify'><b>									
										CLÁUSULA 8 – PAGAMENTOS ANTECIPADOS</b></p>
				<p align='justify'>						
										".$dev6." que não se encontrar(em) em mora com qualquer de suas obrigações decorrentes deste Contrato poderá realizar amortizações extraordinárias do Saldo Devedor, mediante solicitação por escrito à PREVI, desde que o valor a ser amortizado não seja inferior a uma prestação mensal vigente à época em que se realizar a amortização.</p>
				<p align='justify'><b>						
										Juros pro rata die</b></p>
				<p align='justify'>						
										8.1. Caso a data da realização da amortização extraordinária não coincida com a data de vencimento de qualquer das prestações, prevista na cláusula 7, ao Saldo Devedor a ser amortizado serão acrescidos, para todos os efeitos desta cláusula, a atualização, na forma da cláusula 4, o Fundo de Hedge e  os juros incorridos desde a data de vencimento da parcela de principal imediatamente anterior à amortização extraordinária até a data em que essa se realizar, calculados pelo critério pro rata die.</p>
				<p align='justify'><b>						
										Imputação das amortizações extraordinárias</b></p>
				<p align='justify'>
										8.2. Os valores efetivamente pagos ".$dev5." a título de amortização extraordinária serão deduzidos do Saldo Devedor total, acrescido dos montantes referidos na cláusula 8.1, mantendo-se o prazo original do Financiamento e reduzindo-se, dessa forma, proporcionalmente, o valor da prestação mensal.</b>
				<p align='justify'>						
										8.2.1. Não obstante o disposto na cláusula 8.2, ".$dev2." poderá(ão) solicitar a manutenção do valor da prestação e a redução do prazo total do financiamento. Caso ".$dev2." não efetue(m) a solicitação aqui prevista, aplicar-se-á a regra mencionada na cláusula 8.2.</p>
				<p align='justify'>						
										8.2.2 Se ".$dev2." estiver(em) com a prestação do financiamento limitada nos termos da cláusula 7.5 e optar pela redução do prazo do financiamento, será utilizado para fins de recálculo do novo prazo remanescente, em decorrência da amortização extraordinária, o valor da prestação com a limitação citada. </p>
				<p align='justify'>						
										8.3 Os recálculos mencionados nesta cláusula serão realizados de forma independente do recálculo anual previsto na cláusula 7.1.2.</p>
				<p align='justify'>						
										8.4 Caso ".$dev2." venha(m) a romper(em) o vínculo empregatício com o Banco do Brasil S.A. e cancelar sua inscrição junto à PREVI, será utilizado para quitar ou amortizar o presente financiamento imobiliário o saldo correspondente à Diferença de Reserva Matemática. Caso este saldo não seja suficiente para liquidação da dívida, poderá ser utilizado o saldo da Reserva Pessoal de Poupança. ".$dev6.", neste ato expressamente autoriza(m) a utilização destes valores para compensação com a dívida oriunda do financiamento imobiliário.</p>
				<p align='justify'>						
										8.5 Caso, em função de evento de perda de renda, a PREVI tenha se abstido de consignar o valor integral da prestação devida para respeitar a limitação máxima de 30% (trinta por cento) prevista na cláusula 7.5. e ".$dev2." venha(m) a manifestar(em) intenção de quitar antecipadamente sua dívida, o Fundo de Hedge, nesta hipótese, não poderá ser invocado por este para cobrir saldo das diferenças geradas pelo evento. </p>
				<p align='justify'><b>
										CLÁUSULA 9 – JUROS E ENCARGOS MORATÓRIOS  </b></p>
				<p align='justify'>
										Caso ".$dev2." não pague(m), na data de seu vencimento, qualquer obrigação pecuniária, de qualquer natureza, principal ou acessória, serão devidos à PREVI: i) atualização monetária dos valores não pagos pelo índice previsto no número 3 do  Item VII;  ii) juros contratuais previstos no número 4 do Item VII; iii) multa não indenizatória de 2% (dois por cento) e juros moratórios de 1% a.m. (um por cento ao mês) sobre os valores em atraso  atualizados acrescidos dos juros definidos no número 4 do Item VII; e iv) despesas de cobrança e honorários advocatícios.</p>
				<p align='justify'>						
										9.1. No caso da excussão da garantia de alienação fiduciária ora constituída, ".$dev2." arcará(ão) com todos os custos e despesas dela decorrentes  e demais cominações legais e convencionais.</p>
				<p align='justify'><b>						
										CLÁUSULA 10 – VENCIMENTO ANTECIPADO</b></p>
				<p align='justify'>						
										A PREVI poderá considerar antecipadamente vencidas e imediatamente exigíveis todas as obrigações ".$dev3." decorrentes deste Contrato, caso ocorra qualquer das seguintes hipóteses:</p>
				<p align='justify'>						
										I – se ".$dev2." ceder(em) ou transferir(em) a terceiros os seus direitos e obrigações decorrentes deste Contrato, ou vender ou prometer vender, por qualquer outra forma, o Imóvel, ou sobre ele constituir quaisquer ônus ou gravames, sem prévio e expresso consentimento da PREVI;</p>
				<p align='justify'>						
										II – se ".$dev2." incorrer(em) em mora, total ou parcial, com relação ao pagamento de qualquer obrigação decorrente deste Contrato e o referido inadimplemento não for saldado dentro de 90 (noventa) dias;</p>
				<p align='justify'>						
										III – se contra ".$dev2." for movida qualquer ação ou execução real ou reipersecutória cujo objeto seja o Imóvel, ou caso este seja objeto de qualquer medida constritiva, judicial ou administrativa, tais como penhora, seqüestro ou arresto;</p>
				<p align='justify'>						
										IV – se ".$dev2." tiver sua insolvência civil decretada ou, se for empresário, requerer recuperação judicial ou extrajudicial, ou falência, ou tiver sua falência requerida por terceiros;</p>
				<p align='justify'>						
										V – se qualquer das declarações feitas ".$dev5." ou pelo(a,s) VENDEDOR(A,ES) neste Contrato revelar-se errônea, enganosa, falsa ou inverídica;</p>
				<p align='justify'>						
										VI – se houver o descumprimento ".$dev5." de qualquer obrigação por ele(A,ES) assumida neste Contrato, inclusive daquelas relativas à garantia de alienação fiduciária ora constituída;</p>
				<p align='justify'>						
										VII – se ".$dev2." deixar(em) de apresentar à PREVI anualmente, ou quando solicitado para tanto, os recibos comprobatórios do pagamento dos impostos e taxas, despesas condominiais, bem como quaisquer outros tributos incidentes sobre o Imóvel;</p>
				<p align='justify'>						
										VIII – se o Imóvel for desapropriado, no todo ou em parte;</p>
				<p align='justify'>						
										IX – se ".$dev2." não mantiver(em) o Imóvel em perfeito estado de conservação, segurança e habitabilidade, ou nele realizar, sem o prévio e expresso consentimento da PREVI, obras de demolição, alteração ou acréscimo;</p>
				<p align='justify'>						
										X – se ocorrer qualquer das hipóteses previstas no artigo  333 do Código Civil;</p>
				<p align='justify'>						
										XI – se houver utilização indevida da indenização do seguro conforme especificado na cláusula 11.7</p>
				<p align='justify'>						
										XII – se, por qualquer forma, se constatar que ".$dev2." se furtou à finalidade a que o financiamento objetivou, dando ao imóvel outra destinação que não seja a sua ocupação residencial.</p>
				<p align='justify'>									
										10.1 Na hipótese de imóvel financiado para mais de um DEVEDOR, conforme previsto na cláusula 19, o vencimento antecipado se dará em relação a todos os DEVEDORES. </p>
				<p align='justify'><b>						
										Pagamento no caso de vencimento antecipado</b></p>
				<p align='justify'>						
										10.2. Ocorrendo o vencimento antecipado de suas obrigações, nos termos aqui previstos, e caso a PREVI não tenha iniciado, ainda, o procedimento de excussão da garantia, fazendo intimar ".$dev2." nos termos da cláusula 15, ".$dev2." deverá(ão) pagar à PREVI a totalidade do Saldo Devedor, acrescido dos juros, contribuições para o Fundo de Hedge, taxa de administração e prêmio de seguro até então incorridos, 24 (vinte e quatro) horas após ser extrajudicialmente notificado para tanto, por simples carta enviada com Aviso de Recebimento ou por qualquer outro meio hábil, sob pena de incorrer em mora com relação a tais quantias, passando a incidir sobre elas os juros e encargos moratórios previstos na cláusula 9, e sob pena de consolidação da propriedade do Imóvel em nome da PREVI, nos termos da cláusula 15.</p>
				<p align='justify'><b>						
										CLÁUSULA 11 – SEGURO </b></p>
				<p align='justify'>						
										Durante a vigência deste contrato e até a amortização definitiva da dívida, ".$dev2." autoriza(m) a PREVI a contratar, junto à companhia seguradora de primeira linha, seguro contra danos físicos ao imóvel, conforme condições das coberturas do seguro, anexas ao contrato, figurando a PREVI como única e exclusiva beneficiária do seguro, podendo exigir e receber as respectivas indenizações.</p>
				<p align='justify'>						
										11.1 A PREVI não se responsabiliza por danos causados ao imóvel em decorrência de riscos não cobertos pelo seguro contratado.</p>
				<p align='justify'>						
										11.2 A cobertura do seguro se dará a partir da assinatura deste instrumento, regendo-se pelas cláusulas e condições constantes da Apólice estipulada pela PREVI.</p>
				<p align='justify'>						
										11.3 O seguro contra morte ".$dev3." fica, para este financiamento, substituído por contribuições ao Fundo de Quitação por Morte, previsto na cláusula 6.2, deste Contrato. </p>
				<p align='justify'>						
										11.4 Não será exigida ".$dev3." a contratação de seguro para cobertura de invalidez permanente, enquanto este mantiver contratado com a PREVI plano de aposentadoria que assegure a complementação do salário na situação de aposentadoria por invalidez permanente.  Entretanto, ".$dev2." autoriza a PREVI a contratar em seu nome,  seguro para cobertura de invalidez permanente com cláusula beneficiária à PREVI, na hipótese de desvinculação ".$dev3." do plano de aposentadoria contratado com a PREVI.</p>
				<p align='justify'><b>						
										Pagamento dos prêmios do seguro</b></p>
				<p align='justify'>						
										11.5 ".$dev6.", neste ato, autoriza(m) a PREVI a pagar, em seu nome, diretamente à companhia seguradora, o prêmio do seguro contratado, nos termos desta cláusula, obrigando-se a reembolsá-la dos montantes pagos, sendo certo que tal reembolso deverá ser feito juntamente com a prestação mensal no mês subseqüente à assinatura do contrato, e todo o mês de novembro nas renovações durante o período do financiamento, sendo certo que esse valor considerar-se-á automaticamente alterado quando, por qualquer motivo, for modificado pela companhia seguradora.</p>
				<p align='justify'>						
										11.6 No primeiro ano de vigência do contrato, o seguro será cobrado, pro-rata dia, desde a data da contratação do financiamento até a data do vencimento da apólice em vigor. </p>
				<p align='justify'><b>						
										Utilização do seguro </b></p>
				<p align='justify'>						
										11.7 Em caso de sinistro, as partes obrigam-se a utilizar os montantes recebidos da companhia seguradora, conforme definido nas condições gerais da apólice, para repor o Imóvel ao estado em que este se encontrava anteriormente à ocorrência de tais danos ou, caso tal reposição não seja possível, a indenização deverá ser utilizada para amortizar ou liquidar todas as obrigações oriundas deste Contrato, restituindo-se ".$dev4." o montante que, eventualmente, sobejar.</p>
				<p align='justify'>										
										11.7.1 Caso venha a seguradora, na indenização de seguro de natureza material, optar pelo pagamento em espécie, a PREVI não assumirá qualquer obrigação de financiar possível diferença entre o custo orçado na nova obra e o valor da indenização recebida.</p>
				<p align='justify'><b>										
										Obrigações ".$dev3." referentes ao seguro</b></p>
				<p align='justify'>						
										11.8. São obrigações ".$dev3." em relação aos seguros contratados nos termos desta cláusula:</p>
				<p align='justify'>						
										a. formalizar comunicação à companhia seguradora e à PREVI, imediatamente, a ocorrência de sinistro coberto pela respectiva apólice, relatando todos os fatos a ele relacionados de modo a permitir sua completa elucidação;</p>
				<p align='justify'>						
										b. tomar todas as providências necessárias para a limitação das conseqüências do sinistro;</p>
				<p align='justify'>						
										c. caso o sinistro seja imputável a terceiros, ".$dev2." deverá(ão) fornecer os documentos necessários para que a companhia seguradora exerça os seus direitos contra tais terceiros, inclusive com outorga de mandato com os necessários poderes para tal fim;</p>
				<p align='justify'>						
										d. dar conhecimento aos seus descendentes, ascendentes, cônjuge ou companheiro(a), da existência dos seguros aqui referidos e da obrigatoriedade de comunicação imediata à companhia seguradora e à PREVI caso ocorra qualquer sinistro coberto por tais seguros.</b>
				<p align='justify'>						
										11.9 ".$dev6." declara(ão) que recebeu , juntamente com o presente instrumento cópia da Apólice de seguro estipulada pela PREVI, tomando ciência das condições pactuadas.</p>
				<p align='justify'><b>				
										CLÁUSULA 12 – TAXA DE ADMINISTRAÇÃO</b></p>
				<p align='justify'>						
										".$dev6." pagará(ão), à PREVI, juntamente com a prestação mensal, a Taxa de Administração mencionada no número 2 do Item VIII, a título de ressarcimento dos custos pela administração, gestão da cobrança do Financiamento e de todos os processos a eles vinculados, nos termos da Resolução CMN 3.121/03,  cujo valor poderá ser revisto periodicamente pela PREVI.</p>
				<p align='justify'><b>						
										CLÁUSULA 13 – ALIENAÇÃO FIDUCIÁRIA</b></p>
				<p align='justify'>						
										Para garantir o cumprimento de todas e quaisquer obrigações principais e acessórias, inclusive as referentes à restituição de principal e ao pagamento de juros, encargos, comissões, tarifas, reembolso dos prêmios de seguro pagos na forma da cláusula 11.5, multas e encargos moratórios, por si assumidas neste Contrato (“Obrigações Garantidas”), ".$dev2.", neste ato, nos termos e para os efeitos dos arts. 22 e seguintes da Lei nº 9.514/97, transfere à PREVI, em caráter fiduciário, a propriedade resolúvel e a posse indireta sobre o Imóvel, que foi adquirido ".$dev5." por compra e venda, nos termos deste Contrato. ".$dev6.", enquanto adimplente, manterá consigo a posse direta sobre o Imóvel, podendo utilizá-lo livremente, por sua conta e risco.</p>
				<p align='justify'><b>						
										Compreensão e extinção da propriedade resolúvel da PREVI</b></p>
				<p align='justify'>						
										13.1. Por força da alienação fiduciária ora contratada, a PREVI passa a deter a propriedade resolúvel e a posse indireta sobre o Imóvel e todas as acessões, melhoramentos, construções e instalações nele existentes e que a ele forem acrescidas. A propriedade fiduciária detida pela PREVI sobre o Imóvel será eficaz até o final e total pagamento de todas as Obrigações Garantidas, e resolver-se-á de pleno direito com o cancelamento do registro da propriedade fiduciária, o qual será feito pelo Oficial de Registro de Imóveis competente, mediante a exibição de termo de quitação, entregue pela PREVI ".$dev4.", nos termos da cláusula 14.</p>
				<p align='justify'><b>										
										Impostos e contribuições</b></p>
				<p align='justify'>						
										13.2. ".$dev6." obriga(m)-se a pagar pontualmente todos os impostos, taxas e quaisquer outras contribuições ou encargos que incidam ou venham a incidir sobre a posse ou sobre a propriedade resolúvel do Imóvel, tais como Imposto Predial e Territorial Urbano – IPTU, contribuições devidas ao condomínio de utilização do edifício ou a associação que congregue os moradores do conjunto imobiliário respectivo, exibindo os respectivos comprovantes à PREVI, anualmente, ou quando solicitado.</p>
				<p align='justify'>						
										13.2.1. Caso ".$dev2." não pague(m) em dia todos os impostos e demais tributos que incidam ou venham a incidir sobre o Imóvel, poderá a PREVI fazê-lo, ficando ".$dev2." obrigado(a,s) a reembolsá-la das quantias despendidas no prazo de 24 (vinte e quatro) horas após recebimento de notificação encaminhada por esta, sob pena de, sobre tais quantias, incidirem os juros e encargos moratórios estipulados na cláusula 9. O reembolso devido à PREVI ".$dev5.", nos termos desta cláusula, fica garantido pela presente alienação fiduciária.</p>
				<p align='justify'><b>						
										Conservação do Imóvel</b></p>
				<p align='justify'>						
										13.3. ".$dev6." compromete(m)-se a manter e conservar o Imóvel em perfeito estado de segurança e habitabilidade, bem como a realizar às suas custas, dentro do prazo que lhe for determinado para tanto, as obras e os reparos julgados necessários, ficando vedada a realização de qualquer obra de modificação ou acréscimo no Imóvel sem o prévio consentimento da PREVI. O cumprimento dessa obrigação poderá ser fiscalizado pela PREVI, obrigando-se ".$dev2." a permitir o ingresso de pessoa credenciada para executar as vistorias periódicas.</p>
				<p align='justify'><b>						
										Desapropriação do Imóvel</b></p>
				<p align='justify'>						
										13.4. ".$dev6.", desde já, de forma irrevogável e irretratável, autoriza(m) a PREVI a receber, em seu nome, todas as quantias referentes a indenizações pagas pelo poder expropriante por força de desapropriação, integral ou parcial, do Imóvel, por qualquer forma ou motivo, aplicando tais valores na amortização ou liquidação das Obrigações Garantidas, colocando o remanescente, se houver, à disposição ".$dev3.", na forma prevista na cláusula 16.5.</p>
				<p align='justify'>						
										13.4.1. ".$dev6.", pelo presente Contrato e na melhor forma de direito, nomeia(m) e constitui(em) a PREVI sua procuradora, na forma do artigo 684 do Código Civil, com amplos e irrevogáveis poderes para, em juízo ou fora dele, representá-lo junto aos órgãos públicos federais, municipais ou estaduais, bancos, autarquias e demais entidades públicas e privadas, bem como perante Agentes Financeiros ou companhias de seguros em todos os assuntos referentes à desapropriação e aos seguros, para receber importâncias em casos de sinistros ou desapropriação amigável ou judicial, total ou parcial, decorrentes de pagamento de seu crédito, podendo, ainda, assinar, reconhecer, aceitar, dar quitação, receber, endossar, requerer, impugnar, concordar, recorrer, desistir, transigir, firmar compromissos e substabelecer. A presente outorga de poderes será eficaz até o pagamento final e total das Obrigações Garantidas.</p>
				<p align='justify'><b>						
										Retenção e indenização por benfeitorias</b></p>
				<p align='justify'>						
										13.5. Nos termos do disposto nos parágrafos 4º e 5º do artigo 27 da Lei 9.514/97, jamais haverá direito de retenção por benfeitorias realizadas ".$dev5." no Imóvel, mesmo que tenham caráter de necessárias ou úteis, ou que tenham sido autorizadas pela PREVI.</p>
				<p align='justify'>					
										13.5.1. Nos termos do §4º ao art. 27 da lei 9.514/97, na hipótese de a propriedade do imóvel dado em garantia consolidar-se em nome da PREVI, a indenização por benfeitorias resumir-se-á, sempre, ao saldo que sobejar do preço pago pelo Imóvel, depois de liquidadas as Obrigações Garantidas e as demais despesas e acréscimos legais, sendo certo que, não ocorrendo a venda do imóvel nos leilões extrajudiciais, e extinguindo-se as obrigações ".$dev3." decorrentes deste Contrato, nos termos da cláusula 16.6, não haverá nenhum direito de indenização pelas benfeitorias.</p>
				<p align='justify'><b>						
										CLÁUSULA 14 – QUITAÇÃO DAS OBRIGAÇÕES D".$dev6."</b></p>
				<p align='justify'>						
										No prazo de 30 (trinta) dias, a contar da data do efetivo pagamento final e total das Obrigações Garantidas, a PREVI enviará ".$dev4.", o respectivo termo de quitação, correspondente às obrigações assumidas ".$dev5." neste Contrato, sob pena de multa em favor ".$dev3." equivalente a 0,5% (meio por cento) ao mês, ou fração, sobre o valor do Financiamento.</p>
				<p align='justify'><b>						
										Cancelamento da propriedade fiduciária</b></p>
				<p align='justify'>						
										14.1. Enviado o termo de quitação aqui mencionado, fica ".$dev2." autorizado(a,s) a requerer(em), ao Oficial de Registro de Imóveis competente, o cancelamento do registro da propriedade fiduciária, com a respectiva restituição ".$dev4." da propriedade sobre o Imóvel.</p>
				<p align='justify'>						
										14.1.1. O envio do termo de quitação pela PREVI, nos termos aqui previstos, simbolizará a transferência ".$dev4." da posse indireta exercida pela PREVI sobre o Imóvel, consolidando-se, dessa forma, na pessoa ".$dev3.", a posse plena sobre esse.</p>
				<p align='justify'><b>						
										Pagamento com recursos oriundos do FGTS</b></p>
				<p align='justify'>						
										14.2. Caso ".$dev2." utilize(m) seus recursos do Fundo de Garantia por Tempo de Serviço – FGTS para liquidar as Obrigações Garantidas, tais obrigações apenas considerar-se-ão quitadas após o efetivo recebimento, pela PREVI, dos referidos recursos, os quais lhe serão entregues pela Caixa Econômica Federal – CEF.</p>				<p align='justify'>						
				<p align='justify'><b>						
										CLÁUSULA 15 – MORA E CONSOLIDAÇÃO DA PROPRIEDADE FIDUCIÁRIA</b></p>
				<p align='justify'>						
										Verificada a mora ".$dev3." com relação a qualquer obrigação por ele assumida nos termos deste Contrato, e decorrido o prazo de carência de 90 (noventa) dias, contados da data em que se verificou a mora, sem que haja a sua purgação, a PREVI poderá fazer intimar ".$dev2.", nos termos do art. 26, §1º da Lei nº 9.514/97, fixando o prazo de até 15 (quinze) dias para que purgue a mora, pagando ao Oficial de Registro de Imóveis competente o montante equivalente ao valor de todas as suas obrigações decorrentes deste Contrato que se encontrem vencidas e não pagas, inclusive aquelas que vencerem no curso da intimação, acrescido dos juros e encargos moratórios conforme pactuados neste Contrato e de todos os custos e despesas de intimação, bem como tributos e contribuições condominiais e associativas que porventura se encontrarem vencidos na data da purgação da mora.</p>
				<p align='justify'><b>						
										Pagamento parcial</b></p>
				<p align='justify'>						
										15.1. O pagamento do valor de principal das obrigações em mora sem que haja o respectivo pagamento  de juros e encargos, inclusive moratórios, dos custos e despesas havidos com sua intimação, não exonerará ".$dev2." da responsabilidade de liquidar a totalidade de suas obrigações em mora, sendo certo que o saldo devedor restante de tais obrigações deverá ser pago juntamente com o pagamento da parcela de principal cujo vencimento seja imediatamente subseqüente a tal purgação parcial, sob pena de a PREVI poder requerer ao Oficial de Registro de Imóveis que certifique a não-purgação da mora no prazo assinado e, assim, consolide a propriedade do Imóvel em nome da PREVI.</p>
				<p align='justify'><b>									
										Forma de realização da intimação</b></p>
				<p align='justify'>
										15.2. A realização da intimação ".$dev3.", referida nesta cláusula, caberá ao Oficial de Registro de Imóveis que, a seu critério, poderá fazê-lo: i) pessoalmente; ii) por preposto seu; iii) através do Serviço de Registro de Títulos e Documentos da Comarca da situação do Imóvel ou do domicílio ".$dev3."; ou, ainda, iv) pelo Correio, desde que enviada com Aviso de Recebimento – AR, a ser firmado pessoalmente ".$dev5." ou por seu representante.</p>
				<p align='justify'>						
										15.2.1. O Oficial de Registro de Imóveis providenciará a realização da intimação ".$dev3." após requerimento da PREVI, a qual indicará ao Oficial o valor das obrigações ".$dev3." vencidas e não pagas, acrescidas dos juros e encargos moratórios, incidentes nos termos da cláusula 9.</p>
				<p align='justify'><b>						
										Recebimento da intimação e intimação por edital</b></p>
				<p align='justify'>						
										15.3. A intimação deverá ser recebida pessoalmente ".$dev5." ou por seu representante regularmente constituído, sendo certo que, caso ".$dev2." encontre-se em local incerto e não sabido, assim certificado pelo Oficial de Registro de Imóveis ou pelo Oficial de Títulos e Documentos, conforme o caso, competirá ao primeiro promover a intimação ".$dev3." por edital.</p>
				<p align='justify'>								
										15.3.1. O edital de intimação será publicado por 3 (três) dias, ao menos, consecutivos ou não, em um dos jornais de maior circulação editados no local do Imóvel ou, se no local do Imóvel não houver imprensa com circulação diária, editado em outra comarca de fácil acesso, sendo certo que o prazo de 15 (quinze) dias para a purgação da mora será contado a partir da última publicação do edital.</p>
				<p align='justify'><b>						 
										Purgação da mora ao Oficial de Registro de Imóveis</b></p>
				<p align='justify'>						
										15.4. ".$dev6." poderá(ão) efetuar a purgação da mora aqui referida: i) entregando, em dinheiro, ao Oficial do Registro de Imóveis competente o valor necessário para a purgação da mora; ou ii) entregando, ao Oficial do Registro de Imóveis competente, cheque administrativo, emitido por banco comercial, intransferível por endosso e nominativo à PREVI ou a quem expressamente indicado na intimação, no valor necessário para a purgação da mora. Nessa hipótese, a entrega do cheque ao Oficial do Registro de Imóveis será feita sempre em caráter pro solvendo, de forma que a purgação da mora ficará condicionada ao efetivo pagamento do cheque pela instituição financeira sacada. Recusado o pagamento do cheque, a mora será tida por não purgada, podendo a PREVI requerer que o Oficial do Registro de Imóveis certifique, nos termos do art. 26, §7 da Lei nº 9.514/97, que a mora não restou purgada e promova a consolidação, em nome da PREVI, da propriedade fiduciária.</p>
				<p align='justify'>						
										15.4.1. O Oficial do Registro de Imóveis receberá o pagamento efetuado ".$dev5." por conta da PREVI e entregará a esta as importâncias recebidas.</p>
				<p align='justify'><b>						
										Consolidação da propriedade em nome da PREVI</b></p>
				<p align='justify'>						
										15.5. Caso não haja a purgação da mora no prazo determinado na intimação referida nesta cláusula, a PREVI poderá, com a apresentação do devido recolhimento do Imposto sobre Transmissão de Bens Imóveis – ITBI, requerer ao Oficial de Registro de Imóveis que certifique o decurso in albis do prazo para a purgação da mora e consolide, em nome da PREVI, a propriedade plena do Imóvel, contando, a partir do registro da consolidação, o prazo para a realização dos leilões extrajudiciais previstos na cláusula 16.</p>
				<p align='justify'><b>						
										Desocupação do Imóvel</b></p>
				<p align='justify'>						
										15.6. ".$dev6." deverá(ão) desocupar o Imóvel no dia seguinte ao da consolidação da propriedade plena em nome da PREVI, deixando-o livre e desimpedido de pessoas e coisas, sob pena de pagamento à PREVI, ou àquele que tiver adquirido o imóvel em leilão, de multa diária, não indenizatória, equivalente a 0,033% (trinta e três milésimos por cento) do valor de avaliação do Imóvel estipulado no Item III, sem prejuízo de sua responsabilidade pelo pagamento: a) do foro e das despesas de água, luz e gás referentes ao Imóvel; b) de todas as despesas e contribuições devidas ao condomínio de utilização ou à associação que congregue os moradores do conjunto imobiliário integrado pelo Imóvel; c) de todas as despesas necessárias à reposição do Imóvel ao estado em que o recebeu.</p>
				<p align='justify'>						
										15.6.1. Não ocorrendo a desocupação do Imóvel ".$dev5.", no prazo e forma ajustados nesta cláusula 15.6, independentemente da penalidade estipulada no caput desta cláusula, a PREVI ou o adquirente do Imóvel poderão propor ação de reintegração de posse contra ".$dev2.", sem prejuízo da cobrança e execução do valor da multa diária de ocupação e demais despesas previstas no caput desta cláusula e neste Contrato. ".$dev6." declara(m)-se ciente de que, nos termos do art. 30 da Lei nº 9.514/97, tal reintegração será concedida liminarmente, com ordem judicial para desocupação no prazo máximo de 60 (sessenta) dias.</p>
				<p align='justify'>						
										15.6.2. A penalidade diária referida no caput desta cláusula incidirá a partir do 30º (trigésimo) dia subseqüente ao da consolidação da propriedade plena em nome da PREVI.</p>
				<p align='justify'><b>						
										CLÁUSULA 16 – DO LEILÃO EXTRAJUDICIAL</b></p>
				<p align='justify'>						
										Uma vez consolidada a propriedade do Imóvel em nome da PREVI, esta deverá promover a realização de leilões públicos, extrajudiciais, conforme previsto no art. 27 da Lei nº 9.514, a fim de alienar o Imóvel a terceiros interessados, e utilizar o preço recebido para liquidar as Obrigações Garantidas. Os leilões serão conduzidos por leiloeiro oficial, legalmente habilitado para tanto e eleito pela PREVI, ao qual será devida comissão à taxa que se praticar para esse tipo de leilão no local em que este for realizado.</p>
				<p align='justify'><b>						
										Primeiro público leilão</b></p>
				<p align='justify'>						
										16.1. O primeiro público leilão será realizado no prazo máximo de 30 (trinta) dias, contados da data do registro da consolidação da plena propriedade em nome da PREVI. O preço mínimo de venda do Imóvel, nesse primeiro público leilão, equivalerá ao valor de avaliação do Imóvel, estipulado pelas Partes, no Item III, o qual será atualizado pela mesma taxa de atualização do Saldo Devedor constante da cláusula 4, e que poderá, a critério exclusivo da PREVI, ser revisto por meio de nova avaliação, realizada por companhia idônea a ser indicada pela PREVI, incluindo-se os custos de avaliação no Saldo Devedor</p>
				<p align='justify'>						
										16.1.1. Considera-se incluído no valor do preço mínimo de venda do Imóvel o valor de todas e quaisquer benfeitorias, necessárias, úteis e voluptuárias, executadas ".$dev5." no Imóvel.</p>
				<p align='justify'><b>						
										Segundo público leilão</b></p>
				<p align='justify'>						
										16.2. Não havendo, no primeiro público leilão, oferta em montante igual ou superior ao preço mínimo de venda do Imóvel, conforme a cláusula 16.1, a PREVI deverá promover um segundo público leilão, no prazo de até 15 (quinze) dias, contado da data da realização do primeiro público leilão.</p>
				<p align='justify'>						
										16.2.1. O preço mínimo de venda do Imóvel, no segundo público leilão, equivalerá ao somatório do valor das Obrigações Garantidas, dos juros e encargos moratórios incorridos até a data da realização do segundo público leilão, e do valor das seguintes obrigações ".$dev3.", que se encontrem vencidas e não pagas até a data da realização do segundo leilão: i) prêmios de seguro; ii) contribuições devidas ao condomínio de utilização, ou contribuições devidas a associação de moradores ou entidade assemelhada; iii) despesas de água, luz e gás; iv) Imposto Predial e Territorial Urbano – IPTU, foro e outros tributos ou contribuições eventualmente incidentes sobre a propriedade ou a posse do Imóvel; v) Imposto sobre Transmissão de Bens Imóveis - ITBI e laudêmio, e demais custos e despesas, inclusive despesas de cobrança, eventualmente devidos por força da consolidação da propriedade plena do Imóvel em nome da PREVI; vi) encargos e custas de intimação ".$dev3."; vii) encargos e custas com a publicação do edital de anúncio de ambos os leilões; viii) a comissão devida ao leiloeiro; ix) custos de avaliação do Imóvel; e x) quantias devidas ".$dev5." nos termos das cláusulas 15.6 e 15.6.1.</p>
				<p align='justify'><b>						
										Local de realização dos leilões</b></p>
				<p align='justify'>						
										16.3. Os públicos leilões serão realizados no local da situação do Imóvel, na capital do Estado em que este se localiza, ou no local da sede da PREVI, conforme opção desta, e serão anunciados mediante edital, publicado por 3 (três) vezes, ao menos, devendo mediar, entre a primeira publicação e a realização do leilão nela anunciado, no mínimo, 10 (dez) dias. As publicações do edital serão efetuadas em jornal de grande circulação no local de realização do leilão e no local de situação do Imóvel, elegendo-se, preferencialmente, jornais editados naquelas localidades.</p>
				<p align='justify'><b>					
										Critério para venda do Imóvel</b></p>
				<p align='justify'>						
										16.4. A venda do Imóvel em qualquer dos públicos leilões far-se-á sempre pelo critério de maior lance, respeitado, todavia, o preço mínimo de venda estabelecido conforme as cláusulas 16.1 e 16.2.1.</p>
				<p align='justify'>						
										16.4.1. Realizada a venda do Imóvel em qualquer dos públicos leilões, a PREVI, na qualidade de proprietário pleno e possuidor(a,es) indireto do Imóvel, transferirá ao licitante vencedor toda a propriedade e posse que sobre ele exerce, bem como receberá o preço pago pelo licitante vencedor, e utilizará os valores para liquidar as Obrigações Garantidas acrescidas dos juros e encargos moratórios até a data da realização do leilão em que houve a venda do Imóvel e do valor das obrigações ".$dev3." descritas nos itens “i” a “x” da cláusula 16.2.1, que se encontrem vencidas e não pagas até a data da efetivação da venda do Imóvel.</p>
				<p align='justify'><b>						
										Restituição de quantias ".$dev4." e indenização por benfeitorias</b></p>
				<p align='justify'>						
										16.5. Após liquidadas as obrigações ".$dev3.", mencionadas na cláusula 16.4, a PREVI restituir-lhe-á eventual saldo que sobejar do preço recebido pela venda do Imóvel no prazo de até 5 (cinco) dias úteis após o efetivo pagamento pelo licitante vencedor, por meio de crédito na conta-corrente mantida ".$dev5." junto ao Banco do Brasil S.A. ou por meio de cheque administrativo, nominativo e intransferível, emitido em nome ".$dev3.". Nos termos do §4º ao art. 27 da Lei nº 9.514/97, considerar-se-á incluída no valor restituído ".$dev4." a indenização pelas benfeitorias, úteis, necessárias ou voluptuárias por ele realizadas no Imóvel, não podendo ".$dev2." reclamar o pagamento de qualquer outra quantia, a qualquer título.</p>
				<p align='justify'>						
										16.5.1. Caso não haja saldo a ser restituído, não será devida ".$dev4.", nos termos daquela disposição legal, qualquer indenização pelas benfeitorias, úteis, necessárias ou voluptuárias por ele realizadas no Imóvel.</p>
				<p align='justify'><b>						
										Extinção da dívida e indenização por benfeitorias</b></p>
				<p align='justify'>						
										16.6. Caso no segundo público leilão não haja licitantes ou não seja oferecido lançe que equivalha, pelo menos, ao valor mínimo estipulado na cláusula 16.2.1, considerar-se-ão extintas as obrigações ".$dev3." decorrentes deste Contrato, exonerando-se a PREVI da obrigação de vender o Imóvel por meio de público leilão.</p>
				<p align='justify'>						
										16.6.1. Ocorrendo a extinção da dívida, no prazo de 5 (cinco) dias a contar da realização do segundo leilão, a PREVI entregará ".$dev4." o competente termo de quitação de suas obrigações decorrentes deste Contrato, aplicando-se, nessa hipótese, o disposto na cláusula 14.</p>
				<p align='justify'>						
										16.6.2. Na hipótese prevista na cláusula 16.6, a PREVI não será obrigada a restituir ".$dev4." qualquer quantia, a qualquer título, nem obrigada a indenizá-lo pelas benfeitorias, úteis, necessárias ou voluptuárias por ele realizadas no Imóvel.</p>
				<p align='justify'><b>						
										Pagamento do saldo devedor restante</b></p>
				<p align='justify'>						
										16.7. Caso, na hipótese de venda do Imóvel no primeiro público leilão, o valor apurado não seja suficiente para liquidar as Obrigações Garantidas acrescidas dos juros e encargos moratórios e do valor das obrigações ".$dev3." descritas nos itens “i” a “x” da cláusula 16.2.1, ".$dev2." permanecerá(ão) responsável pelo total e completo pagamento de suas obrigações decorrentes deste Contrato, o qual deverá ser realizado 24 (vinte e quatro) horas após a venda do Imóvel.</p>
				<p align='justify'><b>						
										Prestação de contas</b></p>
				<p align='justify'>						
										16.8. Caso haja a venda do Imóvel em qualquer dos dois públicos leilões previstos na cláusula 16, a PREVI manterá, em sua sede, à disposição ".$dev3.", a correspondente prestação de contas pelo período de 12 (doze) meses, contados da realização do primeiro leilão.</p>
				<p align='justify'><b>						
										CLÁUSULA 17 - CERTIDÕES</b></p>
				<p align='justify'>						
										Conforme a natureza da personalidade jurídica do(a,s) VENDEDOR(A,ES), neste ato são entregues as seguintes certidões, ou prestadas as seguintes declarações: a) se o(a,s) VENDEDOR(A,ES) for pessoa física, declara não ser produtor rural, empregador, nem estar pessoalmente vinculado ao INSS, não estando sujeito à apresentação da CND-INSS, por não ser contribuinte desse órgão; b) se o(a,s) VENDEDOR(A,ES) for pessoa jurídica, apresenta, neste ato, cópia autenticada da Certidão Negativa de Débito – CND-INSS e da Certidão Conjunta expedida pela Receita Federal do Brasil e pela Procuradoria-Geral da Fazenda Nacional, exceto se, conforme assinalado no preâmbulo, estiver dispensado de tal apresentação por ser sociedade que explora, exclusivamente, atividade de compra e venda de imóveis, locação, desmembramento ou loteamento de terrenos, incorporação imobiliária ou construção de imóveis, destinados à venda e que o Imóvel integra contabilmente seu ativo circulante, jamais tendo constado do seu ativo permanente, o que declara sob responsabilidade civil e criminal.  </p>
				<p align='justify'><b>						
										Certidões de ações reais e reipersecutórias</b></p>
				<p align='justify'>						
										17.1. Para lavratura deste Contrato foram apresentadas certidões de ações reais e pessoais reipersecutórias, relativas ao Imóvel e a de ônus reais, expedidas pelo Cartório de Registro de Imóveis competente, bem como os demais documentos cuja apresentação é exigida por Lei, os quais se encontram identificados no Decreto nº 93.240/86, ficando os mesmos arquivados junto a PREVI, em face da obrigação de seus arquivamentos prevista na Lei nº 4.380/64, e em conformidade com o disposto no § 3º do artigo 1º da Lei nº 7.433/85.</p>
				<p align='justify'><b>						
										CLÁUSULA 18 - DECLARAÇÕES D".$dev6."</b></p>
				<p align='justify'>						
										".$dev6." declara(m) expressamente, sob pena de responsabilidade civil e penal, que: i) sendo pessoa física, não está vinculado à Previdência Social, como empregador, e que não é contribuinte da mesma, na qualidade de produtor rural, não estando, portanto, sujeito às obrigações previdenciárias abrangidas pelo INSS – Instituto Nacional do Seguro Social; ii) na hipótese de estar vinculado e/ou ser contribuinte da Previdência Social, será apresentada, por ocasião do registro deste contrato junto ao Cartório de Registro de Imóveis competente, a necessária Certidão Negativa de Débito expedida pelo INSS; iii) não tem nenhuma responsabilidade tutelar, curatelar ou testamentária; iv) vistoriou o Imóvel e o encontrou em perfeita ordem e condições de habitabilidade; v) ".$dev6." declara, para todos os fins e sob as penas da lei, que não contraiu anteriormente nenhum financiamento imobiliário junto à PREVI; vi) ".$dev6." declara(m) não estar respondendo a inquérito administrativo, inquérito judicial trabalhista ou estar em aviso prévio, até a presente data.</p>
				<p align='justify'><b>									
										Declarações concernentes à utilização do Fundo de Garantia por Tempo de Serviço – FGTS</b></p>
				<p align='justify'>						
										18.1. Caso parte do preço de compra do Imóvel seja pago mediante a utilização de recursos do Fundo de Garantia por Tempo de Serviço – FGTS ".$dev3.", este(a,s) declara(m), sob as penas da lei, que:</p>
				<p align='justify'>						
										a) utilizará o Imóvel exclusivamente para residência própria;<br>b) o Imóvel está localizado: i) no município onde exerce a sua ocupação principal, em município a esse limítrofe ou integrante da respectiva região metropolitana; ou, ainda, ii) no município onde resida há, pelo menos, um ano;<br>c) não é proprietário ou promissário comprador de qualquer outro imóvel residencial concluído: i) sito em qualquer parte do território nacional, cuja aquisição ou construção tenha sido financiada no âmbito do Sistema Financeiro da Habitação, em qualquer parte do território nacional; ii) sito no município onde exerça sua ocupação principal, nos municípios a esse limítrofes ou na respectiva região metropolitana; ou iii) sito no atual município de sua residência;<br>d) não é usufrutuário do Imóvel;e) não doou qualquer imóvel residencial a pessoa: i) que esteja sujeita ao seu pátrio poder, ou ii) sobre a qual exerça tutela ou curatela;<br>f) tem conhecimento de que lhe é vedado: i) utilizar o FGTS para aquisição de imóvel que não se destine à sua moradia própria; ii) utilizar o FGTS para aquisição de imóvel comercial ou rural; iii) utilizar o FGTS para aquisição de lotes ou terrenos; iv) utilizar o FGTS para aquisição de imóvel gravado com cláusula que dificulte ou comprometa a sua livre comercialização; v) utilizar o FGTS para aquisição de imóvel residencial concluído que não apresente condições de habitabilidade (bom estado de conservação); vi) utilizar o FGTS para aquisição de imóvel que tenha sido adquirido pelo(a,s) VENDEDOR(A,ES) com a utilização do seu FGTS, há menos de 3 (três) anos.</p>
				<p align='justify'>
										18.1.1. Para os fins da alínea “c”, acima, ".$dev2." não será(ão) considerado(a,s) proprietário(a,s) ou promissário(a,s) comprador(a,es) de imóvel residencial caso detenha fração ideal igual ou inferior a 40% (quarenta por cento) de referido imóvel.</p>
				<p align='justify'>						
										18.1.2. ".$dev6." declara(m) que tem conhecimento de que o Imóvel só poderá ser alienado a outro comprador que pretenda pagar o preço com a utilização de seu FGTS após 3 (três) anos contados do registro da presente venda e compra.</p>
				<p align='justify'>						
										18.1.3. ".$dev6.", neste ato, obriga(m)-se a respeitar(em) e observar(em) as vedações e restrições estabelecidas na letra “f” da cláusula 18.1.</p>
				<p align='justify'><b>									
										CLÁUSULA 19 – DISPOSIÇÕES GERAIS</b></p>
				<p align='justify'>						
										Caso no Item I - “COMPRADOR(A,ES)”, constante do preâmbulo deste Contrato, figurem duas pessoas, ambas declaram-se solidariamente responsáveis por todas as obrigações decorrentes do Financiamento e descritas neste Contrato, entendendo-se as referências feitas neste Contrato ao “DEVEDOR(A,ES)” como abrangendo ambas as referidas pessoas, as quais, mútua e reciprocamente, constituem-se procuradoras uma(a,s) da(a,s) outra(a,s) para fins de receber citações, intimações e interpelações de qualquer procedimento, judicial ou extrajudicial, decorrente deste Contrato, inclusive as intimações mencionadas na cláusula 15, de modo que, realizada a citação ou intimação, na pessoa de qualquer uma delas, estará completo o quadro citatório.</p>
				<p align='justify'><b>									
										Novação, alteração ou renúncia</b></p>
				<p align='justify'>
										19.1. Qualquer pagamento de principal, juros ou demais encargos que sejam efetuados fora dos prazos estabelecidos neste Contrato e ainda assim recebidos pela PREVI, bem como o não-exercício imediato de qualquer direito de que a PREVI seja titular em decorrência deste Contrato ou da lei, inclusive a efetivação da intimação mencionada na cláusula 15, serão considerados mera tolerância. Qualquer novação ou alteração deste Contrato apenas será válida mediante aditivo a este instrumento.</p>
				<p align='justify'><b>						
										Despesas deste Contrato e de registro</b></p>
				<p align='justify'>						
										19.2. ".$dev6." responde(m) por todas as despesas decorrentes da presente compra e venda e do financiamento com alienação fiduciária em garantia, inclusive aquelas relativas a emolumentos e despachante para obtenção das certidões dos distribuidores forenses, da municipalidade e de propriedade, as necessárias à sua efetivação e as demais que se lhe seguirem, inclusive as relativas a emolumentos e custas de Serviço de Notas e de Serviço de Registro de Imóveis, de quitações fiscais e qualquer tributo devido sobre a operação, que venha a ser cobrado ou criado.</p>
				<p align='justify'>						
										19.2.1. Correrão por conta ".$dev3." todas as despesas decorrentes do presente Contrato e de todos os registros e averbações a ele correspondentes, principalmente os referentes ao registro da presente compra e venda do Imóvel e da garantia de alienação fiduciária ora constituída, bem como aquelas decorrentes de qualquer ato ou negócio jurídico praticado com base neste Contrato.</p>
				<p align='justify'><b>						
										Ato jurídico perfeito</b></p>
				<p align='justify'>						
										19.3. As Partes convencionam, como condição essencial deste Contrato, que, em face do princípio constitucional do respeito ao direito adquirido e ao ato jurídico perfeito, não se aplicará a este Contrato qualquer norma superveniente de congelamento ou deflação, total ou parcial, do Saldo Devedor ou do valor de cada prestação.</p>
				<p align='justify'>						
										19.3.1 Na hipótese de a PREVI aceitar temporariamente, por mera liberalidade e sem que tal fato caracterize novação, o congelamento ou deflação do valor de algumas prestações, fica ajustado como condição do presente negócio que: i) o Saldo Devedor continuará sendo atualizado, nos termos da cláusula 4; e ii) a diferença entre o valor real de cada parcela e o valor a menor pago ".$dev5." será cobrada pela PREVI tão logo se encerre, de modo direto ou indireto, o congelamento ou deflação.</p>
				<p align='justify'>						
										19.3.2. Em face do avençado, toda e qualquer quitação conferida pela PREVI acha-se condicionada à apuração posterior de eventual saldo de responsabilidade ".$dev3.", ainda que tal ressalva não conste expressamente do respectivo recibo ou boleto bancário.</p>
				<p align='justify'><b>									
										Alteração de domicílio e Estado Civil</b></p>
				<p align='justify'>						
										19.4. ".$dev6." obriga(m)-se a comunicar à PREVI, imediatamente, qualquer alteração de seu estado civil, bem como qualquer alteração de seu domicílio ou endereço para correspondência.</p>
				<p align='justify'><b>						
										Declaração<br>CLÁUSULA 20 - REGULAMENTO DA CARTEIRA IMOBILIÁRIA DA PREVI<b></p>
				<p align='justify'>						
										Aplica-se subsidiariamente a este Contrato as regras do Regulamento  vigente da Carteira Imobiliária da PREVI (CARIM2007), ao qual ".$dev2." declara expresso conhecimento e concordância.</p>
				<p align='justify'><b>									
										CLÁUSULA 21 - AUTORIZAÇÃO PARA REGISTRO</b></p>
				<p align='justify'>						
										As Partes declaram aceitar o presente Contrato em todas as suas cláusulas, termos e condições, autorizando o Sr. Oficial do Cartório de Registro de Imóveis competente a proceder quaisquer registros ou averbações que se fizerem necessários ao seu fiel cumprimento, inclusive o registro da propriedade resolúvel sobre o Imóvel em favor do PREVI.</p>
				<p align='justify'><b>						
										CLÁUSULA 22 - ELEIÇÃO DE FORO</b></p>
				<p align='justify'>						
										Para dirimir quaisquer dúvidas que porventura surjam em virtude do presente instrumento, as partes elegem o Foro Central da Capital do Estado do Rio de Janeiro, facultado ao autor da ação optar pelo foro de situação do imóvel.</p>
				<br>
				<p align='justify'>								
										E, por estarem assim justos e contratados, assinam o presente em 03 (três) vias de igual teor e valor, na presença de 02 (duas) testemunhas.</p>
														<br>
				<p align='justify'>								
										GARANTIA DE ALIENAÇÃO FIDUCIÁRIA E OUTRAS AVENÇAS Nº ".$id_lstn.".</p>
				<p align='right'><br><br>Curitiba - ".data_extenso($asscontrato).".</p>
				<br>
				<p align='justify'>COMPRADOR(A,ES):</p>
				".$assinatura_dev."
				<br>
				<p align='justify'>VENDEDOR(A,ES):</p><br>
				".$assinatura_vend."
				<br>
				<p align='justify'>FINANCIADOR".$credor."</p><br>
				<table border='0'>
				<tr>
					<td></td>
					<td align='center'><font color='#FFFFFF'>LN</font><br><font color='#FFFFFF'>LN</font><br><font color='#FFFFFF'>LN</font><br><br><br><b>________________________________________________</b></td>
				</tr>
				<tr>
					<td width='200'></td>
					<td width='364' align='center'><b>P.P. CAIXA DE PREVIDÊNCIA DOS FUNCIONÁRIOS DO BANCO DO BRASIL - $nome_procurador</b></td>
				</tr>
			</table>
				<br>
				<p align='justify'>TESTEMUNHAS</p><br>
				<table border='0'>
				<tr>
					<td width='250' align='center'><b>________________________________________</b></td>
					<td width='50'></td>
					<td width='250' align='center'><b>________________________________________</b></td>
				</tr>
				<tr>
					<td align='left'><b>Nome:<br>RG:<br>CPF:</b></td>
					<td></td>
					<td align='left'><b>Nome:<br>RG:<br>CPF:</b></td>
				</tr>
			</table>
			
				";
			
		//Instanciation of inherited class
		$pdf=new HTML2FPDF();
		//$pdf=new FPDF();
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
	
		//$pdf->Cell(0,5,'CÉDULA DE CRÉDITO BANCÁRIO',0,2,'C');
		$pdf->SetFont('Arial','',10);
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
