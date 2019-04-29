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
$conexao = mysql_connect($BD_SERVIDOR,$BD_USUARIO,$BD_SENHA) or die("ERRO: conex�o n�o realizada");
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
	$tipo_ppst="C"; // Proposta em Condom�nio (Com mais de um participante)
}

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
# ___________________________________ Pr�ximo M�s _______________________________________________
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
# ___________________________________ Pr�ximo Mes Ano _______________________________________________
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
################################# QUALIFICA��O DE COMPRADOR(A,ES)  #######################################
//��������������������������������������������������������������������������������������������������
#____________________________QUALIFICA��O DE PARTICIPANTE DE PROPOSTA SIMPLES_______________________
//��������������������������������������������������������������������������������������������������
if($tipo_ppst=='S'){ // Qualifica��o do Participante de Proposta Simples

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
	// ENDERE�O
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

	//______________________  Dados de Usu�rio _________________________________________________
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

	//______________________ Dados de DEVEDOR(A,ES) Solid�rio __________________
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
		$habens="-Registro Auxiliar do  ".$prop_habenscart."o. Cart�rio de Registro de Im�veis de ".$prop_habensloccart.", em ".$prop_habensdata."";
	}
	
	
	// ____________Qualifica��o Credor Quitante _________________________________________
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
			$desc_crd="Caixa de Previd�ncia dos Funcion�rios do Banco do Brasil";
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
		$gravame_crd= "n�o h�<br><font color='#FFFFFF'>LN</font><br>";
		$reg_crd= "n�o h�<br><font color='#FFFFFF'>LN</font><br>";
		$cart_crd= "n�o h�<br><font color='#FFFFFF'>LN</font><br>";
		$rec_crd= "n�o h�<br><font color='#FFFFFF'>LN</font><br>";
		$credor="";
		$desc_crd="n�o h�<br><font color='#FFFFFF'>LN</font><br>";		
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
	//Qualifica��o de Contas do DEVEDOR(A,ES)
	$query="Select * from retornofgts where participante='".$id_lstn."'";
	$result=mysql_query($query);
	$registro=mysql_fetch_array($result,MYSQL_ASSOC);
	$prop_agencia	= $registro['nragencia'];
	$prop_conta		= $registro['nrconta'];
	$prop_renda		= $registro['rendabruta'];

	$prop_contas	= strtoupper($prop_nome).": Banco do Brasil S/A, Ag�ncia n� ".$prop_agencia.", Conta n� ".formataConta($prop_conta);
	
	//Clausulas fixas
	/*
	if($prop_cidade==4076)
	{
		$infofixa="<p align='justify'>Foram apresentadas em nome do Vendedor as certid�es exigidas pela Lei 7.433, de 18/12/85, quais sejam: Certid�es dos 1�, 2�, 3�, 4� e 9� Of�cios de Distribui��o, 1� e 2� Of�cios de Interdi��es e Tutelas, situa��o enfit�utica, quita��o fiscal, 9� Of�cio � Executivos Fiscais e declara��o de quita��o condominial relativas ao im�vel. O im�vel objeto do presente contrato n�o � foreiro, conforme certid�o expedida pela Prefeitura do Munic�pio do Rio de Janeiro. Quando for a hip�tese de im�vel foreiro, a certid�o apresentada, bem como o recolhimento de laud�mio encontrar-se-� mencionado no campo Informa��es Adicionais.</p>";
	}elseif($prop_cidade==3172)
	{
		$infofixa="<p align='justify'>Declara o Vendedor que n�o existem quaisquer d�bitos que recaiam sobre  o im�vel objeto deste contrato, conforme Certid�o Negativa de Tributos Municipais, expedida pela Prefeitura Municipal de Niter�i. Foram apresentadas as certid�es exigidas pela Lei 7.433, de 18/12/85, relativamente a Interdi��o, Tutela e Curatela, expedida pelo Registro Civil da Primeira Zona Judici�ria de Niter�i, 1� Distribuidor de Niter�i e Justi�a Federal, em nome do Vendedor.</p>";
	}elseif($prop_cidade==1445)
	{
		$infofixa="<p align='justify'>Na conformidade da Lei n� 7.433, de 18/12/1985, do Decreto n� 93.240, de 09/09/1986, da Corregedoria da Justi�a do Estado do Paran�, foram apresentadas as Certid�es exigidas, inclusive a de feitos ajuizados, do conhecimento das partes. Declara ainda o outorgante n�o existirem a��es reais e pessoais reipersecut�rias relativas ao im�vel objeto da presente Escritura, e de outros �nus reais incidentes sobre o mesmo, conforme Decreto n� 93.240, de 09/09/86, Art. 1�, inc. V, par�grafo 3�.</p>";
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
  ############################# QUALIFICA��O DE PARTICIPANTE MASCULINO ###################################

	// Comprador do sexo MASCULINO
	if($prop_sex=='M')
	{
		if($prop_civil==2)//EST. CIVIL CASADO
		{
			if($prop_reg==1)// Comunh�o Parcial de Bens antes da lei
			{
				if($prop_nacional==$conj_nacional)
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional.", casados  pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores dos Documentos de Identifica��o n�(s) ".$prop_numdoc.", expedido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", expedido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}else
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$emit_nacional.", ela ".$conjemit_nacional.", casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores dos Documentos de Identifica��o n�(s) ".$prop_numdoc.", expedido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", expedido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}
			}
			if($prop_reg==7)//Comunh�o Parcial de Bens depois da lei
			{
				if($prop_nacional==$conj_nacional)
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional.", casados  pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcasamento.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores dos Documentos de Identifica��o n�(s) ".$prop_numdoc.", expedido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", expedido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}else
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$emit_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcasamento.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores dos Documentos de Identifica��o n�(s) ".$prop_numdoc.", expedido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", expedido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}
	
			}
			if($prop_reg==2)// Comunh�o Universal de Bens antes da lei
			{
					if($prop_nacional==$conj_nacional)
					{
										$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional.", casados pelo regime de Comunh�o Universal de Bens, anteriormente a Lei n�  6.515/77,  em ".$prop_dtcasamento.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores dos Documentos de Identifica��o n�(s) ".$prop_numdoc.", expedido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", expedido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
					}else
					{
										$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$emit_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunh�o Universal de Bens, anteriormente a Lei n�  6.515/77,  em ".$prop_dtcasamento.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores dos Documentos de Identifica��o n�(s) ".$prop_numdoc.", expedido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", expedido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
					}
			}
			if($prop_reg==3)//Comunh�o Universal de Bens depois da lei
			{
				if($prop_nacional==$conj_nacional)
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." , casados pelo regime de Comunh�oooo Universal de Bens, na vig�ncia da Lei n�  6.515/77,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores dos Documentos de Identifica��o n�(s) ".$prop_numdoc.", expedido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", expedido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}else
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$emit_nacional.", ela ".$conj_nacional.", casados pelo regime de Comunh�o Universal de Bens, na vig�ncia da Lei n�  6.515/77,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores dos Documentos de Identifica��o n�(s) ".$prop_numdoc.", expedido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", expedido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}
			}
			if($prop_reg==5)//Separa��o de Bens com pacto (n�o obrigat�ria)
			{
				if($prop_nacional==$conj_nacional)
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional.", casados  pelo regime de Separa��o de Bens,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores dos Documentos de Identifica��o n�(s) ".$prop_numdoc.", expedido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", expedido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}else
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$emit_nacional.", ela ".$conj_nacional.", casados pelo regime de Separa��o de Bens de Bens,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores dos Documentos de Identifica��o n�(s) ".$prop_numdoc.", expedido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", expedido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}
			}
	 
			if($prop_reg==6)//Separa��o de Bens obrigat�ria
			{
				if($prop_nacional==$conj_nacional)
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional.", casados  pelo regime de Separa��o Obrigat�ria de bens, nos termos do artigo 1641 do C�digo Civil Brasileiro,  em ".$prop_dtcasamento.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores dos Documentos de Identifica��o n�(s) ".$prop_numdoc.", expedido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", expedido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}else
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ele ".$emit_nacional.", ela ".$conj_nacional.", casados pelo regime de Separa��o Obrigat�ria de bens, nos termos do artigo 1641 do C�digo Civil Brasileiro,  em ".$prop_dtcasamento.", ele ".$prop_prof.", ela ".$conj_profissao.", portadores dos Documentos de Identifica��o n�(s) ".$prop_numdoc.", expedido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", expedido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}
			}
		}//fim de Estado civil Casado
		
		// EST.  DIFERENTE DE CASADO
		if($prop_civil==1) $estciv='solteiro';
		if($prop_civil==3) $estciv='separado judicialmente';
		if($prop_civil==4) $estciv='divorciado';
		if($prop_civil==5) $estciv='vi�vo';
					
		if($conj_civil==1) $pc_estciv='solteira';
		elseif($conj_civil==3) $pc_estciv='separada judicialmente';
		elseif($conj_civil==4) $pc_estciv='divorciada';
		elseif($conj_civil==5) $pc_estciv='vi�va';

		if($prop_civil!=2 && $flguniest=='S')
		{
		$maior=($prop_civil==1)?' maior,':'';
				if($flgpacto=='S'){			
				$emitente="<b>".maiusculo($prop_nome)."</b>, ".$emit_nacional.", ".$prop_prof.", ".$estciv.",$maior portador do Documento de identifica��o n� ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc.", inscrito no CPF/MF sob n� ".$prop_cpf."  <b> e ".maiusculo($conj_nome)."</b>, ".$conjemit_nacional.", ".$conj_profissao.", ".$pc_estciv.", portador do Documento de Identifica��o n� ".$conj_numdoc.", emitido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", inscrito no CPF/MF sob n� ".$conj_cpf.", convivendo em uni�o est�vel, nos termos da Lei n�. 9.278/96 e altera��es do art. 1.723 do C�digo Civil Brasileiro, conforme escritura de declara��o lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
					
				}else
				{
					$emitente="<b>".maiusculo($prop_nome)."</b>, ".$emit_nacional.", ".$prop_prof.", ".$estciv.",$maior portador do Documento de identifica��o n� ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc.", inscrito no CPF/MF sob n� ".$prop_cpf."  <b> e ".maiusculo($conj_nome)."</b>, ".$conjemit_nacional.", ".$conj_profissao.", ".$pc_estciv.", portador do Documento de Identifica��o n� ".$conj_numdoc.", emitido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", inscrito no CPF/MF sob n� ".$conj_cpf.", convivendo em uni�o est�vel, nos termos da Lei n�. 9.278/96 e altera��es do art. 1.723 do C�digo Civil Brasileiro, residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}
				
		}elseif($prop_civil!=2 && ($flguniest=='N' || $flguniest==''))
		{
				$emitente="<b>".maiusculo($prop_nome)."</b>, ".$emit_nacional.", ".$prop_prof.", ".$estciv.", maior, portador do Documento de identifica��o n� ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc.", inscrito no CPF/MF sob o nr. ".$prop_cpf.", residente e domiciliado no(a) ".$endereco.", ".$dev1.$procurador;
		}
	}//fim if ($prop_sex=='M')
  
  ############################# QUALIFICA��O DE PARTICIPANTE FEMININO ###################################
  
    // Compra do Sexo FEMININO 
	if($prop_sex=='F')
	{
		if($prop_civil==2)//EST. CIVIL CASADO
		{
			if($prop_reg==1)//Comunh�o Parcial de Bens antes da lei
			{
				if($prop_nacional==$conj_nacional)
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional.", casados  pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores dos Documentos de Identifica��o n�(s) ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", emitido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco." <br>".$procurador;
				}else
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$emit_nacional.", ele ".$conjemit_nacional.", casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores dos Documentos de Identifica��o n�(s) ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", emitido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco." <br>".$procurador;
				}
			}
			if($prop_reg==7)//Comunh�o Parcial de Bens depois da lei
			{
				if($prop_nacional==$conj_nacional)
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional.", casados  pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcasamento.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores dos Documentos de Identifica��o n�(s) ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", emitido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}else
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$emit_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunh�o Parcial de Bens,  em ".$prop_dtcasamento.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores dos Documentos de Identifica��o n�(s) ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", emitido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}
	
			}
			if($prop_reg==2)//Comunh�o Universal de Bens antes da lei
			{
					if($prop_nacional==$conj_nacional)
					{
										$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional.", casados pelo regime de Comunh�o Universal de Bens, anteriormente a Lei n�  6.515/77,  em ".$prop_dtcasamento.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores dos Documentos de Identifica��o n�(s) ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", emitido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
					}else
					{
										$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$emit_nacional.", ele ".$conj_nacional.", casados pelo regime de Comunh�o Universal de Bens, anteriormente a Lei n�  6.515/77,  em ".$prop_dtcasamento.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores dos Documentos de Identifica��o n�(s) ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", emitido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
					}
			}
			if($prop_reg==3)//Comunh�o Universal de Bens depois da lei
			{
				if($prop_nacional==$conj_nacional)
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional." , casados pelo regime de Comunh�o Universal de Bens, na vig�ncia da Lei n�  6.515/77,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores dos Documentos de Identifica��o n�(s) ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", emitido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}else
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$emit_nacional.", ele ".$conjemit_nacional.", casados pelo regime de Comunh�o Universal de Bens, na vig�ncia da Lei n�  6.515/77,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores dos Documentos de Identifica��o n�(s) ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", emitido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}
			}
			if($prop_reg==5)//Separa��o de Bens com pacto (n�o obrigat�ria)
			{
				if($prop_nacional==$conj_nacional)
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional.", casados  pelo regime de Separa��o de Bens,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores dos Documentos de Identifica��o n�(s) ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", emitido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}else
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$emit_nacional.", ele ".$conj_nacional.", casados pelo regime de Separa��o de Bens de Bens,  em ".$prop_dtcasamento.", conforme escritura de pacto antenupcial lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro." ".$habens.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores dos Documentos de Identifica��o n�(s) ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", emitido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}
			}
			if($prop_reg==6)//Separa��o de Bens obrigat�ria
			{
				if($prop_nacional==$conj_nacional)
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ".$nacional.", casados  pelo regime de Separa��o Obrigat�ria de bens, nos termos do artigo 1641 do C�digo Civil Brasileiro,  em ".$prop_dtcasamento.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores dos Documentos de Identifica��o n�(s) ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", emitido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}else
				{
									$emitente="<b>".maiusculo($prop_nome)." e s/m ".maiusculo($conj_nome)."</b>, ela ".$emit_nacional.", ele ".$conj_nacional.", casados pelo regime de Separa��o Obrigat�ria de bens, nos termos do artigo 1641 do C�digo Civil Brasileiro,  em ".$prop_dtcasamento.", ela ".$prop_prof.", ele ".$conj_profissao.", portadores dos Documentos de Identifica��o n�(s) ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc." e ".$conj_numdoc.", emitido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", respectivamente, inscritos no CPF/MF sob os nrs. ".$prop_cpf." e ".$conj_cpf.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}
			}
		}//fim de Estado Civil Casado de comprador feminino 

		// EST.  DIFERENTE DE CASADO
		if($prop_civil==1) $estciv='solteira';
		if($prop_civil==3) $estciv='separada judicialmente';
		if($prop_civil==4) $estciv='divorciada';
		if($prop_civil==5) $estciv='vi�va';

		if($conj_civil==1) $pc_estciv='solteiro';
		elseif($conj_civil==3) $pc_estciv='separado judicialmente';
		elseif($conj_civil==4) $pc_estciv='divorciado';
		elseif($conj_civil==5) $pc_estciv='vi�vo';

		if($prop_civil!=2 && $flguniest=='S')
		{
		$maior=($prop_civil==1)?' maior,':'';
				if($flgpacto=='S'){			
				$emitente="<b>".maiusculo($prop_nome)."</b>, ".$emit_nacional.", ".$prop_prof.", ".$estciv.",$maior portador do Documento de Identifica��o n� ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc.", inscrito no CPF/MF sob n� ".$prop_cpf."  <b> e ".maiusculo($conj_nome)."</b>, ".$conjemit_nacional.", ".$conj_profissao.", ".$pc_estciv.", portador do Documento de Identifica��o n� ".$conj_numdoc.", emitido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", inscrito no CPF/MF sob n� ".$conj_cpf.", convivendo em uni�o est�vel, nos termos da Lei n�. 9.278/96 e altera��es do art. 1.723 do C�digo Civil Brasileiro, conforme escritura de declara��o lavrada no ".$prop_loclavrado.", no Livro ".$prop_livro.", Folhas ".$prop_folha.", em ".$prop_dtlavrado.", registrada sob o nr. ".$prop_numregistro.", residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
					
				}else
				{
					$emitente="<b>".maiusculo($prop_nome)."</b>, ".$emit_nacional.", ".$prop_prof.", ".$estciv.",$maior portador do Documento de Identifica��o n� ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc.", inscrito no CPF/MF sob n� ".$prop_cpf."  <b> e ".maiusculo($conj_nome)."</b>, ".$conjemit_nacional.", ".$conj_profissao.", ".$pc_estciv.", portador do Documento de Identifica��o n� ".$conj_numdoc.", emitido por ".maiusculo($conj_emissor)." em ".$conj_dtdoc.", inscrito no CPF/MF sob n� ".$conj_cpf.", convivendo em uni�o est�vel, nos termos da Lei n�. 9.278/96 e altera��es do art. 1.723 do C�digo Civil Brasileiro, residentes e domiciliados no(a) ".$endereco.", ".$dev1.$procurador;
				}
		}elseif($prop_civil!=2 && ($flguniest=='N' || $flguniest==''))
		{
				$emitente="<b>".maiusculo($prop_nome)."</b>, ".$emit_nacional.", ".$prop_prof.", ".$estciv.", maior, portadora do Documento de Identifica��o n� ".$prop_numdoc.", emitido por ".maiusculo($prop_emissor)." em ".$prop_dtdoc.", inscrita no CPF/MF sob o nr. ".$prop_cpf.", residente e domiciliada no(a) ".$endereco.", ".$dev1.$procurador;
		}
	}//fim Comprador Feminino
} // Fim de Proposta Simples



#########################################################################################################
#################################### QUALIFICA��O DE VENDEDOR(A,ES) ##################################################
	//Informa��es de Procurador
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
	//Informa��es do Vendedor
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
		
		//Endere�o do Vendedor
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
			$vend_contas .=strtoupper($vend_nome[$a]).": Banco do Brasil S/A, Ag�ncia n� ".$vend_agencia[$a].", Conta Corrente n� ".$vend_conta[$a]."-".$vend_digito[$a]."-   ".round($vend_perctual[$a],2)."%<br>";
		}else{
			$vend_contas .=strtoupper($vend_nome[$a]).": ".$vend_banco2[$a].", Ag�ncia n� ".$vend_agencia2[$a].", Conta Corrente n� ".$vend_conta2[$a]."-".$vend_digito2[$a]."-   ".round($vend_perctual[$a],2)."%<br>";
		}
		
		// Vendedor Pessoa F�sica
		if($vend_tipo[$a]==1){
			
			// Informa��es de Pessoa F�sica do Vendedor
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
				//Profiss�o do Vendedor PF
				//$qrvfprof="Select * from profissao where cod_prof='".$vendf_profissao[$a]."'";
				//$rsvfprof =mysql_query($qrvfprof);
				//$regvfprof = mysql_fetch_array($rsvfprof, MYSQL_ASSOC);
					//$v_profissao[$a] = strtolower($regvfprof['DESC_PROF']);
					//echo $v_profissao[$a];
			// Informa��es do Conjuge de Vendedor Pessoa F�sica
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

			// Informa��es de Pacto Antenupcial de Conjuge e Vendedor Pessoa F�sica
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
			//����������������������������������������������������������������������������������������
			#__________________________ QUALIFICA��O(A,s) VENDEDOR(A,ES) PESSOA F�SICA _________________________#
			//����������������������������������������������������������������������������������������
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
							if($vconj_regime[$a]==1)//Comunh�o Parcial de Bens antes da lei
				
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a]." ,casados pelo regime de Comunh�o Parcial de Bens,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpacto_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vcpacto_reg[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores do Documento de Identifica��o n�(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ele ".$v_nacional[$a].", ela ".$vc_nacional[$a].", casados pelo regime de Comunh�o Parcial de Bens,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpacto_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vcpacto_reg[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores do Documento de Identifica��o n�(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}
							}
							if($vconj_regime[$a]==7)//Comunh�o Parcial de Bens depois da lei
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a]." ,casados pelo regime de Comunh�o Parcial de Bens,  em ".$vconj_dtcasamento[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores do Documento de Identifica��o n�(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ele ".$v_nacional[$a].", ela ".$vc_nacional[$a].", casados pelo regime de Comunh�o Parcial de Bens,  em ".$vconj_dtcasamento[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores do Documento de Identifica��o n�(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}
					
							}
							if($vconj_regime[$a]==2)//Comunh�o Universal de Bens antes da lei
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a].", casados pelo regime de Comunh�o Universal de Bens, anteriormente a Lei n�  6.515/77,  em ".$vconj_dtcasamento[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores do Documento de Identifica��o n�(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ele ".$v_nacional[$a].", ela ".$vc_nacional[$a].", casados pelo regime de Comunh�o Universal de Bens, anteriormente a Lei n�  6.515/77,  em ".$vconj_dtcasamento[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores do Documento de Identifica��o n�(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}
							}
							if($vconj_regime[$a]==3)//Comunh�o Universal de Bens depois da lei
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a].", casados pelo regime de Comunh�o Universal de Bens, na vig�ncia da Lei n�  6.515/77,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpacto_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vcpacto_reg[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores do Documento de Identifica��o n�(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ele ".$v_nacional[$a].", ela ".$vc_nacional[$a].", casados pelo regime de Comunh�o Universal de Bens, na vig�ncia da Lei n�  6.515/77,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpacto_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vcpacto_reg[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores do Documento de Identifica��o n�(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}
							}
							if($vconj_regime[$a]==5)//Separa��o de Bens com pacto
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a]." ,casados pelo regime de Separa��o de Bens,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpacto_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vcpacto_reg[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores do Documento de Identifica��o n�(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ele ".$v_nacional[$a].", ela ".$vc_nacional[$a].", casados pelo regime de Separa��o de Bens de Bens,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpacto_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vcpacto_reg[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores do Documento de Identifica��o n�(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}
							}
							if($vconj_regime[$a]==6)//Separa��o de Bens obrigat�rioa
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a]." ,casados pelo regime de Separa��o Obrigat�ria de bens, nos termos do artigo 1641 do C�digo Civil Brasileiro,  em ".$vconj_dtcasamento[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores do Documento de Identifica��o n�(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ele ".$v_nacional[$a].", ela ".$vc_nacional[$a].", casados pelo regime de Separa��o Obrigat�ria de bens, nos termos do artigo 1641 do C�digo Civil Brasileiro,  em ".$vconj_dtcasamento[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores do Documento de Identifica��o n�(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}
							}
					}//Fim de Vendedor PF Casado
		
					// EST. CIVIL DIFERENTE DE CASADO
					
					if($vendf_estciv[$a]==1) $v_estciv[$a]='solteiro';
					elseif($vendf_estciv[$a]==3) $v_estciv[$a]='separado judicialmente';
					elseif($vendf_estciv[$a]==4) $v_estciv[$a]='divorciado';
					elseif($vendf_estciv[$a]==5) $v_estciv[$a]='vi�vo';
					
					if($vconj_civil[$a]==1) $vcj_estciv[$a]='solteira';
					elseif($vconj_civil[$a]==3) $vcj_estciv[$a]='separada judicialmente';
					elseif($vconj_civil[$a]==4) $vcj_estciv[$a]='divorciada';
					elseif($vconj_civil[$a]==5) $vcj_estciv[$a]='vi�va';
					
					
					if($vendf_estciv[$a]!=2 && $vendf_flguniest[$a]=='S')
					{
							
					$vendedor.="<b>".maiusculo($vend_nome[$a])."</b>, ".$v_nacional[$a].", ".$v_profissao[$a].", ".$v_estciv[$a].", portador do Documento de Identifica��o n� ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a].", inscrito no CPF/MF sob n� ".$vendf_cpf[$a]."  <b> e ".$vconj_nome[$a]."</b>, ".$vc_nacional[$a].", ".$vconj_cargoemp[$a].", ".$vcj_estciv[$a].", portador do Documento de Identifica��o n� ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", inscrito no CPF/MF sob n� ".$vconj_cpf[$a].", convivendo em uni�o est�vel, nos termos da Lei n�. 9.278/96 e altera��es do art. 1.723 do C�digo Civil Brasileiro, residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
					
					}elseif($vendf_estciv[$a]!=2 && ($vendf_flguniest[$a]=='N' || $vendf_flguniest[$a]==''))
					{
					$vendedor .="<b>".maiusculo($vend_nome[$a])."</b>, ".$v_nacional[$a].", ".$v_profissao[$a].", ".$v_estciv[$a].", maior, portador do Documento de Identifica��o n� ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a].", inscrito no CPF/MF sob o nr. ".$vendf_cpf[$a].", residente e domiciliado no(a) ".$v_endereco[$a].$limite_v;
					}
				}//Fim Vendedor PF Masculino
					if($vendf_sex[$a]=='F')// Vendedor PF Feminino
					{
						if($vendf_estciv[$a]==2)//EST. CIVIL CASADO
						{
							if($vconj_regime[$a]==1)//Comunh�o Parcial de Bens antes da lei
				
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a]." ,casados pelo regime de Comunh�o Parcial de Bens,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpactio_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vpacto_reg[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores do Documento de Identifica��o n�(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ela ".$v_nacional[$a].", ele ".$vc_nacional[$a].", casados pelo regime de Comunh�o Parcial de Bens,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpactio_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vpacto_reg[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores do Documento de Identifica��o n�(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}
							}
							if($vconj_regime[$a]==7)//Comunh�o Parcial de Bens depois da lei
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a]." ,casados pelo regime de Comunh�o Parcial de Bens,  em ".$vconj_dtcasamento[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores do Documento de Identifica��o n�(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ela ".$v_nacional[$a].", ele ".$vc_nacional[$a].", casados pelo regime de Comunh�o Parcial de Bens,  em ".$vconj_dtcasamento[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores do Documento de Identifica��o n�(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}
					
							}
							if($vconj_regime[$a]==2)//Comunh�o Universal de Bens antes da lei
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a].", casados pelo regime de Comunh�o Universal de Bens, anteriormente a Lei n�  6.515/77,  em ".$vconj_dtcasamento[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores do Documento de Identifica��o n�(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ela ".$v_nacional[$a].", ele ".$vc_nacional[$a].", casados pelo regime de Comunh�o Universal de Bens, anteriormente a Lei n�  6.515/77,  em ".$vconj_dtcasamento[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores do Documento de Identifica��o n�(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}
							}
							if($vconj_regime[$a]==3)//Comunh�o Universal de Bens depois da lei
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a].", casados pelo regime de Comunh�o Universal de Bens, na vig�ncia da Lei n�  6.515/77,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpacto_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vcpacto_reg[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores do Documento de Identifica��o n�(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ela ".$v_nacional[$a].", ele ".$vc_nacional[$a].", casados pelo regime de Comunh�o Universal de Bens, na vig�ncia da Lei n�  6.515/77,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpacto_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vcpacto_reg[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores do Documento de Identifica��o n�(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}
							}
							if($vconj_regime[$a]==5)//Separa��o de Bens com pacto
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a]." ,casados pelo regime de Separa��o de Bens,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpacto_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vcpacto_reg[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores do Documento de Identifica��o n�(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ela ".$v_nacional[$a].", ele ".$vc_nacional[$a].", casados pelo regime de Separa��o de Bens de Bens,  em ".$vconj_dtcasamento[$a].", conforme escritura de pacto antenupcial lavrada no ".$vcpacto_loc[$a].", no Livro ".$vcpacto_livro[$a].", Folhas ".$vcpacto_folha[$a].", em ".$vcpacto_data[$a].", registrada sob o nr. ".$vcpacto_reg[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores do Documento de Identifica��o n�(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}
							}
							if($vconj_regime[$a]==6)//Separa��o de Bens obrigat�rioa
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a]." ,casados pelo regime de Separa��o Obrigat�ria de bens, nos termos do artigo 1641 do C�digo Civil Brasileiro,  em ".$vconj_dtcasamento[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores do Documento de Identifica��o n�(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ela ".$v_nacional[$a].", ele ".$vc_nacional[$a].", casados pelo regime de Separa��o Obrigat�ria de bens, nos termos do artigo 1641 do C�digo Civil Brasileiro,  em ".$vconj_dtcasamento[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores do Documento de Identifica��o n�(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}
							}
					}//Fim de Vendedor PF Casado
		
					// EST. CIVIL DIFERENTE DE CASADO
					if($vendf_estciv[$a]==1) $v_estciv[$a]='solteira';
					elseif($vendf_estciv[$a]==3) $v_estciv[$a]='separada judicialmente';
					elseif($vendf_estciv[$a]==4) $v_estciv[$a]='divorciada';
					elseif($vendf_estciv[$a]==5) $v_estciv[$a]='vi�va';
					
					if($vconj_civil[$a]==1) $vcj_estciv[$a]='solteiro';
					elseif($vconj_civil[$a]==3) $vcj_estciv[$a]='separado judicialmente';
					elseif($vconj_civil[$a]==4) $vcj_estciv[$a]='divorciado';
					elseif($vconj_civil[$a]==5) $vcj_estciv[$a]='vi�vo';
			
					if($vendf_estciv[$a]!=2 && $vendf_flguniest[$a]=='S')
					{		
					$vendedor.="<b>".maiusculo($vend_nome[$a])."</b>, ".$v_nacional[$a].", ".$v_profissao[$a].", ".$v_estciv[$a].", portador do Documento de Identifica��o n� ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a].", inscrito no CPF/MF sob n� ".$vendf_cpf[$a]."  <b> e ".$vconj_nome[$a]."</b>, ".$vc_nacional[$a].", ".$vconj_cargoemp[$a].", ".$vcj_estciv[$a].", portador do Documento de Identifica��o n� ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", inscrito no CPF/MF sob n� ".$vconj_cpf[$a].", convivendo em uni�o est�vel, nos termos da Lei n�. 9.278/96 e altera��es do art. 1.723 do C�digo Civil Brasileiro, residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;

					
					}elseif($vendf_estciv[$a]!=2 && ($vendf_flguniest[$a]=='N' || $vendf_flguniest[$a]==''))
					{
					$vendedor .="<b>".maiusculo($vend_nome[$a])."</b>, ".$v_nacional[$a].", ".$v_profissao[$a].", ".$v_estciv[$a].", maior, portadora do Documento de Identifica��o n� ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a].", inscrita no CPF/MF sob o nr. ".$vendf_cpf[$a].", residente e domiciliada no(a) ".$v_endereco[$a].$limite_v;
					}
				}//Fim Vendedor PF Feminino
				if($linhas_v==$a){
					$vendedor .=" doravante denominado(a,s) VENDEDOR(A,ES)".$procurador_v;
				}

		}//Fim Vendedor Pessoa F�sica

		//Vendedor Pessoa Jur�dica
		if($vend_tipo[$a]==2){
			
			$qrvj = "SELECT * FROM vendjur WHERE cod_ppst='".$cod_ppst."' and cod_vend='".$cod_vend[$a]."'";
			$rsvj =mysql_query($qrvj);
			$regvj = mysql_fetch_array($rsvj, MYSQL_ASSOC);
				$vendj_cnpj[$a]				= formataCnpj($regvj['CNPJ_VJUR']);
				$vendj_versaoestat[$a]		= $regvj['VERSAOESTAT_VJUR'];
				$vendj_dtestatv[$a]			= formataDataBRA($regvj['DTESTAT_VJUR']);
				$vendj_locestat[$a]			= $regvj['LOCESTAT_VJUR'];
				$vendj_regestat[$a]			= $regvj['NRREGESTAT_VJUR'];
			
			// Informa��es dos S�cios do Vendedor PJ
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
				
				//Qualifica��o de S�cio Masculino
				if($vjsocio_sexo[$a][$b]=='M')
				{
					$ident="seu s�cio";
					if($vjsocio_estciv[$a][$b]==1) $vjs_estciv[$a][$b]='solteiro';
					elseif($vjsocio_estciv[$a][$b]==2) $vjs_estciv[$a][$b]='casado';
					elseif($vjsocio_estciv[$a][$b]==3) $vjs_estciv[$a][$b]='separado judicialmente';
					elseif($vjsocio_estciv[$a][$b]==4) $vjs_estciv[$a][$b]='divorciado';
					elseif($vjsocio_estciv[$a][$b]==5) $vjs_estciv[$a][$b]='vi�vo';

					$socio .="<b>Sr. ".maiusculo($vjsocio_nome[$a][$b])."</b>, ".$vjs_nacional[$a][$b].", ".$vjs_estciv[$a][$b].", ".$vjsocio_prof[$a][$b].", portador do Documento de Identifica��o n�. ".$vjsocio_nrrg[$a][$b].", emitido por ".$vjsocio_orgrg[$a][$b]." em ".$vjsocio_dtrg[$a][$b].", inscrito no CPF/MF sob n�. ".$vjsocio_cpf[$a][$b].", residente e domiciliado em ".$vjs_cidade[$a][$b]."-".$vjs_uf[$a][$b]."; "; 
				}
				//Qualifica��o de S�cio Feminino
				if($vjsocio_sexo[$a][$b]=='F')
				{
					$ident="sua s�cia";
					if($vjsocio_estciv[$a][$b]==1) $vjs_estciv[$a][$b]='solteira';
					elseif($vjsocio_estciv[$a][$b]==2) $vjs_estciv[$a][$b]='casada';
					elseif($vjsocio_estciv[$a][$b]==3) $vjs_estciv[$a][$b]='separada judicialmente';
					elseif($vjsocio_estciv[$a][$b]==4) $vjs_estciv[$a][$b]='divorciada';
					elseif($vjsocio_estciv[$a][$b]==5) $vjs_estciv[$a][$b]='vi�va';
					
					$socio .="<b>Sra. ".maiusculo($vjsocio_nome[$a][$b])."</b>, ".$vjs_nacional[$a][$b].", ".$vjs_estciv[$a][$b].", ".$vjsocio_prof[$a][$b].", portadora do Documento de Identifica��o n�. ".$vjsocio_nrrg[$a][$b].", emitido por ".$vjsocio_orgrg[$a][$b]." em ".$vjsocio_dtrg[$a][$b].", inscrita no CPF/MF sob n�. ".$vjsocio_cpf[$a][$b].", residente e domiciliada em ".$vjs_cidade[$a][$b]."-".$vjs_uf[$a][$b];
					$assinatura_socio .="<br>".maiusculo($vjsocio_nome[$a][$b])." - ".$vjsocio_cpf[$a][$b]."";
				}
				$b++;
			}
			//����������������������������������������������������������������������������������������
			#__________________________ QUALIFICA��O(A,s) VENDEDOR(A,ES) PESSOA JUR�DICA _________________________#
			//����������������������������������������������������������������������������������������
			if($vendj_versaoestat[$a]!=''){
				$vendedor .= "<b>".maiusculo($vend_nome[$a])."</b>, com sede e foro na(o) ".$v_endereco[$a].", inscrita no CNPJ sob n�. ".$vendj_cnpj[$a].", com seu Contrato Social/consolida��o e ".$vendj_versaoestat[$a]." Altera��o de Contrato Social, datada de ".$vendj_dtestatv[$a].", registrada na(o) ".$vendj_locestat[$a]." sob n�. ".$vendj_regestat[$a].", doravante denominado(a) simplesmente VENDEDOR(A,ES), representado por ".$ident." ".$socio.$procurador_v;
			}else{
				$vendedor .= "<b>".maiusculo($vend_nome[$a])."</b>, com sede e foro na(o) ".$v_endereco[$a].", inscrita no CNPJ sob n�. ".$vendj_cnpj[$a].", com seu Contrato Social/consolida��o registrada na(o) ".$vendj_locestat[$a]." sob n�. ".$vendj_regestat[$a].", doravante denominado(a) simplesmente VENDEDOR(A,ES), representado por ".$ident." ".$socio.$procurador_v;
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
	}// Fim de Informa��es de Vendedor
		$assinatura_vend .="</table>";
		
		if($qualificacao_vend!=''){
			$vendedor=$qualificacao_vend;
		}
################################ QUALIFICA��O DO IM�VEL ##################################################
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
						<td align='center'><b> CONTRATO DE COMPRA E VENDA DE IM�VEL COM FINANCIAMENTO, ALIENA��O FIDUCI�RIA DE IM�VEL E OUTRAS AVEN�AS - N� ".$id_lstn."<b></td>
					</tr>
				</table><br>";
		$quadro_um="<b>PRE�MBULO</b><br><br>
		
					<b>I � PARTES</b>
					
					<p align='justify'>COMPRADOR(A,ES):<br>".$emitente."</p>
					<p align='justify'>VENDEDOR(A,ES):<br>".$vendedor."</p>
					<p align='justify'>FINANCIADOR".$credor.":<br><b>CAIXA DE PREVID�NCIA DOS FUNCION�RIOS DO BANCO DO BRASIL - PREVI </b>- entidade fechada de previd�ncia complementar constitu�da na forma de sociedade civil sem fins lucrativos, integrante do Sistema Financeiro da Habita��o nos termos da Resolu��o n� 3157 do Banco Central, inscrita no CNPJ/MF sob o n.� 33.754.482/0001-24 , com sede na Praia de Botafogo, n� 501, 3� e 4� pavimento na cidade do Rio de Janeiro � RJ, doravante denominada PREVI, neste ato representada por sua bastante procuradora <b>ATHOS GEST�O E SERVI�OS LTDA.</b>, inscrita no CNPJ/MF sob o n� 00.839.032/0001-85 - <b>Matriz</b>, com sede na Rua Jos� de Alencar n� 60, Curitiba-PR e <b>Filial</b> na Rua Am�lia de Noronha n� 159, Jardim Am�rica, S�o Paulo-SP, inscrita no CNPJ/MF sob o n� 00.839.032/0002-66, neste ato representada por <b>$nome_procurador</b>, $dados_procurador</p>".$interveniente_quitante;
		$quadro_dois="<b>II � IM�VEL OBJETO DESTE CONTRATO</b>".formataTexto($qualificacao_imov);
		$quadro_tres="<b>
					III � PRE�O DA COMPRA E VENDA</b><br>					
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
							<td colspan='3'>VALOR DE AVALIA��O: R$ ".formataMoeda($vlavaliacao)."</td>
						</tr>
						
					</table><br>";
		$quadro_quatro="<b>
					IV � ORIGEM DOS RECURSOS PARA PAGAMENTO DA COMPRA E VENDA</b><br>					
					<table border='1' width='728'>
						<tr>
							<td>1. Recursos pr�prios ".$dev3.": R$ ".formataMoeda($prop_vlentrada)."</td>
						</tr>
						<tr>
							<td>2. Recursos de FGTS: R$ ".formataMoeda($vlfgts)."</td>
						</tr>
						<tr>
							<td>3. Recursos pr�prios de DEVEDOR(A,ES) a t�tulo de sinal a ser ressarcido pela PREVI e inclu�do no financiamento: R$ ".formataMoeda($prop_vlsinal)."</td>
						</tr>
						<tr>
							<td>4. Recursos do Financiamento: R$ ".formataMoeda($vltotfinan)."</td>
						</tr>
					</table><br>";
		$quadro_cinco="<b>
					V � GRAVAMES EXISTENTES SOBRE O IM�VEL</b><br>					
					<table border='1' width='728'>
						<tr>
							<td width='182' align='center'>GRAVAME</td>
							<td width='182' align='center'>FAVORECIDO</td>
							<td width='182' align='center'>N� DO REGISTRO OU INSCRI��O</td>
							<td width='182' align='center'>CART�RIO DE REGISTRO DE IM�VEIS</td>
						</tr>
						<tr>
							<td align='center'>".$gravame_crd."</td>
							<td align='center'>".$desc_crd."</td>
							<td align='center'>".$reg_crd."</td>
							<td align='center'>".$cart_crd."</td>
						</tr>
						
					</table><br>";
		$quadro_seis="<b>
					VI � FORMA DE LIBERA��O DOS RECURSOS DO FINANCIAMENTO</b><br>					
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
							<td>4. Cr�dito em conta-corrente:<br>
								4.1 VENDEDOR(A,ES): ".$vend_contas."
								4.2 COMPRADOR(A,ES): ".$prop_contas."
								
						</tr>
					</table><br>";
		$quadro_sete="<b>
					VII � CONDI��ES DO FINANCIAMENTO</b><br>					
					<table border='1' width='728'>
						<tr>
							<td>1. Valor do Financiamento: R$".formataMoeda($vltotfinan)."</td>
						</tr>
						<tr>
							<td>2. Prazo total do Financiamento: ".$prazo_finan." meses</td>
						</tr>
						<tr>
							<td>3. �ndice de Atualiza��o Monet�ria: INPC</td>
						</tr>
						<tr>
							<td>4. Taxa Efetiva Anual de Juros: 5,00 %<br>5. Taxa Efetiva Mensal de Juros: 0,407%</td>
						</tr>
						<tr>
							<td>6. Fundo de Hedge (FH): %<br>
								6.1 - Fundo de Liquidez (FL): 0,24% a.a<br>
								6.2 - Fundo de Quita��o por Morte (FQM): 0,25% a.a. at� 60 anos  ou 1,80% a.a. a partir de 60 anos</td>
						</tr>
						<tr>
							<td>7. Data de Vencimento do primeiro pagamento mensal: ";
							if($asscontrato!=''){ 
							$quadro_sete.="20/".proxMes($asscontrato)."/".proxMesAno($asscontrato);
							}
				$quadro_sete.="</td>
						</tr>
						<tr>
							<td>8. Forma de pagamento: consigna��o em folha de pagamento do BB, PREVI ou INSS</td>
						</tr>
					</table><br>";
		$quadro_oito="<b>
					VIII � ENCARGOS MENSAIS</b><br>					
					<table border='1' width='728'>
						<tr>
							<td>1. Presta��o contratual mensal (principal, juros e fundo de hedge): R$ ".formataMoeda($parcela_finan)."</td>
						</tr>
						<tr>
							<td>2. Taxa de Administra��o Mensal: R$ 19,00</td>
						</tr>
					</table><br>";
		$quadro_nove="<b>
					IX � SEGURO DE DANOS F�SICOS AO IM�VEL</b><br>					
					<table border='1' width='728'>
						<tr>
							<td>1. Valor do primeiro pr�mio anual: R$ ".formataMoeda($vl_premio)."</td>
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
					X � RENDA BRUTA BASE DO(S) DEVEDOR(A,ES)</b><br>					
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
					XI � RESPONSABILIDADE DO(S) DEVEDOR(A,ES) NO PAGAMENTO DAS PRESTA��ES</b><br>					
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
					INFORMA��ES ADICIONAIS</b><br><br>".$infofort."
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
										RE�NEM-SE as Partes, anteriormente nomeadas e qualificadas, para, de m�tuo acordo, celebrar, por meio deste instrumento particular com for�a de escritura p�blica, nos termos do artigo 61, � 5� da Lei n� 4.380 de 21/08/64, com as altera��es introduzidas pela Lei n� 5.049, de 29.06.66, o  presente CONTRATO DE COMPRA E VENDA DE IM�VEL COM FINANCIAMENTO, GARANTIA DE ALIENA��O FIDUCI�RIA E OUTRAS AVEN�AS (�Contrato�), integrado para todos os efeitos de direito pelos Itens que se encontram preenchidos no pre�mbulo, garantido por aliena��o fiduci�ria de im�vel, constitu�da nos termos da Lei n� 9.514/97, e regido pelas cl�usulas, termos e condi��es a seguir:</p>
				<p align='justify'><b>
										CL�USULA 1 � COMPRA E VENDA DO IM�VEL</b></p>
				<p align='justify'>
										O(A,s) VENDEDOR(A,ES), na qualidade de propriet�rio(a,s) e leg�timo(a,s) possuidor(a,es) do im�vel descrito e caracterizado no Item II (�Im�vel�), pelo presente Contrato, vende e transfere ".$dev4.", que compra(m) e adquire(m) referido Im�vel, pelo pre�o certo e ajustado constante do Item III, na forma mencionada no Item VI, por meio dos recursos mencionados no Item IV.</p>
				<p align='justify'><b>
										Declara��es do(a,s) VENDEDOR(A,ES)</b></p>
			    <p align='justify'>
										1.1. O(A,s) VENDEDOR(A,ES) declara(m) que: i) o Im�vel encontra-se livre e desembara�ado de quaisquer �nus, gravames ou restri��es, pessoais ou reais, de qualquer origem, inclusive fiscal, judicial ou negocial extrajudicial, exceto os gravames descritos no Item V, os quais s�o liberados conforme previsto na cl�usula 2 deste Contrato; ii) n�o existem, contra si ou contra qualquer dos antigos propriet�rios do Im�vel, a��es reais ou pessoais reipersecut�rias, conforme certid�es expedidas pelo Registro de Im�veis competente; iii) sobre o Im�vel n�o pesam d�bitos fiscais, condominiais ou de contribui��es devidas a associa��o que congregue os moradores do conjunto imobili�rio a que pertence o Im�vel.</p>
				<p align='justify'>
										1.1.1. O(A,s) VENDEDOR(A,ES) declara(m), ainda, que o seu estado civil � aquele que se encontra descrito no Item I. Caso viva em uni�o est�vel, seu(sua) companheiro(a), qualificado(a) tamb�m no Item I, assina este Contrato, expressando sua integral anu�ncia com a presente compra e venda, sem que tal concord�ncia tenha qualquer reflexo de car�ter registr�rio, pois n�o infringidos os princ�pios da especialidade subjetiva e da continuidade.</p>
				<p align='justify'><b>
										Quita��o do pre�o do Im�vel</b></p>
				<p align='justify'>
										1.2. Considerando que receber� o pagamento do pre�o da presente compra e venda diretamente da PREVI, � exce��o dos valores j� pagos a t�tulo de sinal ".$dev5.", conforme descrito no Item IV, nos termos deste Contrato, o(a,s) VENDEDOR(A,ES), neste ato, d�(d�o) ".$dev4." a mais plena, geral, rasa e irrevog�vel quita��o do pre�o do Im�vel, para dele nada mais reclamar com respeito a tal pre�o, podendo, t�o somente, reclamar da PREVI a libera��o dos recursos do Financiamento e do Fundo de Garantia por Tempo de Servi�o - FGTS, na forma do Item VI, que ocorrer� conforme os termos e condi��es deste Contrato.</p>
				<p align='justify'><b>
										Transfer�ncia da posse sobre o Im�vel</b></p>
				<p align='justify'>						
										1.3. Em decorr�ncia da compra e venda e da transfer�ncia do dom�nio sobre o Im�vel, efetuadas na forma do caput desta cl�usula, o(a,s) VENDEDOR(A,ES) cede(m) e transfere(m), neste ato, ".$dev4.", que os adquire, toda a posse que exerce sobre o Im�vel, bem como todos os direitos, pretens�es e a��es, inclusive possess�rias, de sua titularidade, relativas ao Im�vel, para que ".$dev2." dele use, goze e livremente disponha, como propriet�rio exclusivo que passa a ser.</p>
				<p align='justify'><b>				
										Responsabilidade pela evic��o</b></p>
				<p align='justify'>
										1.4. O(A,s) VENDEDOR(A,ES) obriga(m)-se por si, seus herdeiros e sucessores, a qualquer t�tulo, a fazer esta venda sempre boa, firme e valiosa e a responder pela evic��o de direito na forma da lei.</p>
				<p align='justify'><b>				
										Responsabilidade por tributos e contribui��es</b></p>
				<p align='justify'>
										1.5. Por for�a da aquisi��o do Im�vel, correr�o por conta exclusiva ".$dev3." todos os tributos e contribui��es que, a partir desta data, venham a incidir sobre a propriedade, posse ou utiliza��o do Im�vel.</p>
				<p align='justify'>				
										1.5.1. Caso o Im�vel integre condom�nio de utiliza��o ou qualquer conjunto imobili�rio administrado por associa��o de moradores, todas as contribui��es relativas ao condom�nio de utiliza��o ou contribui��es devidas � referida associa��o de moradores, a partir desta data, passam a ser de responsabilidade ".$dev3.".</p>
				<p align='justify'><b>				
										Imposto sobre Transmiss�o de Bens Im�veis � ITBI</b></p>
				<p align='justify'>
										1.6. � anexada � primeira via deste Contrato a guia de recolhimento do Imposto sobre Transmiss�o de Bens Im�veis � ITBI, devidamente paga ".$dev5.", referente � transmiss�o do Im�vel por for�a da presente compra e venda.</p>
				<p align='justify'><b>
										CL�USULA 2 � DO GRAVAME SOBRE O IM�VEL</b></p>
				<p align='justify'>
										Caso, nesta data, exista gravame hipotec�rio ou de aliena��o fiduci�ria sobre o Im�vel, conforme mencionado no Item V, constitu�do em favor do CREDOR QUITANTE para garantir o cumprimento de obriga��o do(a,s) VENDEDOR(A,ES), este(a,s), neste ato, autoriza(m) expressamente a PREVI a entregar ao CREDOR QUITANTE, do montante do financiamento ora concedido, o valor mencionado no n�mero 2 do Item VI, quitando as obriga��es do(a,s) VENDEDOR(A,ES) perante o CREDOR QUITANTE, e remindo, dessa forma, o gravame existente sobre o Im�vel. O montante entregue ao CREDOR QUITANTE ser� deduzido do valor a ser entregue ao(s) VENDEDOR(A,ES) por for�a do financiamento ora concedido, conforme disposto na cl�usula 3.2.</p>
				<p align='justify'><b>				
										Quita��o das obriga��es e libera��o do gravame</b></p>
				<p align='justify'>						
										2.1. O CREDOR QUITANTE, concordando com a compra e venda do Im�vel ora contratada e recebendo da PREVI, neste ato, por meio de pagamento em cheque conforme descrito no Item VI, os recursos correspondentes ao pagamento de seu cr�dito, d� ao(s) VENDEDOR(A,ES) a mais plena, rasa e irrevog�vel quita��o com rela��o a tal d�vida e autoriza expressamente o Sr. Oficial do Cart�rio de Registro de Im�veis competente a proceder o cancelamento do referido gravame existente sobre o Im�vel, desde que o fa�a concomitantemente com registro da propriedade fiduci�ria constitu�da em favor da PREVI nos termos da cl�usula 13.</p>
				<p align='justify'><b>				
										Custos e despesas com o cancelamento</b></p>
				<p align='justify'>
										2.2. ".$dev6." arcar�(�o) com todos os custos e despesas referentes ao cancelamento do gravame existente sobre o Im�vel mencionado nesta cl�usula, pagando-os diretamente ao Oficial do Cart�rio de Registro de Im�veis competente.</p>
				<p align='justify'><b>			
										Condi��o suspensiva</b></p>
				<p align='justify'>
										2.3. Al�m das condi��es estipuladas na cl�usula 3.1, a libera��o dos recursos referentes ao Financiamento conforme descrito nos itens 1 e 3 do Item VI encontra-se suspensivamente condicionada � comprova��o � PREVI, por meio da entrega da ficha de matr�cula atualizada do Im�vel, do efetivo cancelamento do gravame existente sobre o Im�vel em favor do CREDOR QUITANTE.</p>
				<p align='justify'><b>										
										CL�USULA 3 � FINANCIAMENTO</b></p>
				<p align='justify'>
										Para possibilitar o pagamento do pre�o do Im�vel, a PREVI, neste ato, concede ".$dev4." um financiamento, no valor total estipulado no Item VII (�Financiamento�), utilizando-se, para tanto, de recursos pr�prios, oriundos dos recursos garantidores do Plano de Benef�cios 01, administrado pela PREVI.</p>
				<p align='justify'><b>				
										Condi��o suspensiva</b></p>
				<p align='justify'>
										3.1. Sem preju�zo das demais condi��es estipuladas neste Contrato, a entrega dos recursos do Financiamento ao(s) VENDEDOR(A,ES) e ".$dev4.", no caso de ressarcimento de sinal, fica suspensivamente condicionada � comprova��o � PREVI do efetivo e perfeito registro, junto � matr�cula do Im�vel, da garantia de aliena��o fiduci�ria constitu�da nos termos da cl�usula 13, o qual ser� realizado pelo Oficial de Registro de Im�veis competente. A comprova��o do registro referido nesta cl�usula dar-se-� pela entrega da ficha de matr�cula atualizada do Im�vel.</p>
				<p align='justify'><b>								
										Verifica��o da condi��o suspensiva</b></p>
				<p align='justify'>
										3.2. Verificada a condi��o suspensiva estipulada na cl�usula 3.1, a PREVI liberar� os recursos referentes ao Financiamento, entregando-os diretamente ao(s) VENDEDOR(A,ES), a t�tulo de pagamento do pre�o do Im�vel, observado sempre o disposto na cl�usula 2.1.</p>
				<p align='justify'>				
										3.2.1. Caso parte do pre�o do Im�vel seja pago pela utiliza��o de recursos do Fundo de Garantia por Tempo de Servi�o � FGTS ".$dev3.", a PREVI liberar� tais recursos diretamente ao(s) VENDEDOR(A,ES) ou ao CREDOR QUITANTE, ap�s satisfeita a condi��o suspensiva estipulada na cl�usula 2.3, acima, ou ap�s t�-los recebidos da Caixa Econ�mica Federal � CEF, o que ocorrer depois.</p>
				<p align='justify'><b>				
										CL�USULA 4 � ATUALIZA��O DO SALD".$dev6."</b></p>
				<p align='justify'>
										O montante total do Saldo Devedor (conforme definido na cl�usula 4.1.) ser� atualizado, desde a presente data e com periodicidade mensal pela aplica��o da varia��o mensal do �ndice atuarial utilizado para a remunera��o b�sica dos recursos garantidores do Plano de Benef�cios 01 da PREVI, com defasagem de 2 (dois) meses da data de atualiza��o do saldo ".$dev3."</p>
				<p align='justify'><b>								
										Saldo Devedor</b></p>
				<p align='justify'>			
										4.1. Para os fins deste Contrato, �Saldo Devedor� significa o valor de principal do Financiamento ainda n�o amortizado, atualizado na forma prevista na cl�usula 4.</p>
				<p align='justify'><b>				
										Forma da atualiza��o do Saldo Devedor</b></p>
				<p align='justify'>
										4.2. O �ndice de atualiza��o monet�ria, definido no  n�mero 3 do Item VII , incidir� sobre o Saldo Devedor antes da aplica��o, sobre este, dos juros e encargos incorridos naquele m�s, e antes da imputa��o dos pagamentos efetuados ".$dev5." naquele m�s.</p>
				<p align='justify'>				
										4.2.1. Para todos os efeitos deste Contrato, as quantias devidas por for�a da atualiza��o do Saldo Devedor ser�o acrescidas ao valor do principal do Financiamento.</p>
				<p align='justify'><b>								
										Substitui��o do �ndice</b></p>
				<p align='justify'>
										4.3. Caso o �ndice mencionado no n�mero 3 do Item VII venha a ser substitu�do por outro �ndice de cunho nacional, e que se enquadre como referencial para a reavalia��o atuarial do Plano de Benef�cios 01 da PREVI, este novo �ndice ser� utilizado para efeito de atualiza��o monet�ria, na forma e periodicidade estabelecida neste instrumento, a partir de sua ado��o.</p>
				<p align='justify'>				
										4.3.1. No caso de substitui��o do �ndice mencionado no n�mero 3 do Item VII, a PREVI divulgar�, por meio de seus canais de comunica��o institucionais, o novo �ndice a ser utilizado. </p>
				<p align='justify'><b>
										CL�USULA 5 � JUROS</b></p>
				<p align='justify'>
										Sobre o Saldo Devedor (conforme definido na cl�usula 4.1.), acrescido da atualiza��o monet�ria nos termos da cl�usula 4, incidir�o, desde a presente data, e com periodicidade mensal, juros � taxa efetiva mensal estipulada no n�mero 5 do Item VII deste Contrato correspondentes aos juros atuariais e previstos no Regulamento do Plano de Benef�cios 01, da PREVI, os quais dever�o ser pagos mensalmente, conforme previsto na cl�usula 7.</p>
				<p align='justify'><b>				
										Altera��o da taxa efetiva de juros</b></p>
				<p align='justify'>
										5.1. Caso a taxa de juros atuarial do Plano de Benef�cios 01 da PREVI, ao qual ".$dev2." encontra(m)-se vinculado(a,s), venha a ser alterada, esta nova taxa de juros ser� aplicada sobre o Saldo Devedor, nos termos da cl�usula 4, a partir de sua ado��o.</p>
				<p align='justify'>				
										5.2 A taxa de juros a que se refere a cl�usula 5 ser� acrescida de 2%a.a. (dois por cento ao ano) se ".$dev2." desligar(em)-se do Plano de Benef�cios 01, administrado pela PREVI, e deixar de receber proventos da Patrocinadora do respectivo Plano de Benef�cios, adequando-se o valor da presta��o � nova condi��o contratual.</p>
				<p align='justify'><b>				
										CL�USULA 6 - FUNDO DE HEDGE</b></p>
				<p align='justify'>			
										Ser� tamb�m cobrado mensalmente, a t�tulo de contribui��o ao Fundo de Hedge, composto pelo Fundo de Liquidez e pelo Fundo de Quita��o por Morte, percentual sobre o Saldo Devedor, diferenciado de acordo com a idade ".$dev3."</p>
				<p align='justify'><b>				
										Fundo de Liquidez</b></p>
				<p align='justify'>
										6.1 O Fundo de Liquidez ser� formado por contribui��es mensais calculadas sobre o valor do saldo devedor atualizado,  pela aplica��o do percentual definido no n�mero 6.1 do Item VII,  e ser�o destinadas a quitar eventual res�duo do Saldo Devedor existente ap�s o pagamento da �ltima presta��o, desde que o referido res�duo n�o tenha sido causado por inadimplemento ".$dev3."</p>
				<p align='justify'><b>				
										Fundo de Quita��o por Morte</b></p>
				<p align='justify'>						
										6.2 O Fundo de Quita��o por Morte (FQM) ser� formado por contribui��es mensais calculadas sobre o valor do Saldo Devedor atualizado e em percentual definido em fun��o da idade ".$dev3.", pela aplica��o dos percentuais definidos no n�mero 6.2 do Item VII e se destinar� a quitar todas as obriga��es vincendas em caso de morte ".$dev3."</p>
				<p align='justify'>
										6.2.1 - O percentual relativo ao FQM ser� alterado automaticamente,  durante a vig�ncia do contrato,  em fun��o da mudan�a de idade ".$dev3.", conforme definido no n�mero 6.2 do Item VII. </p>
				<p align='justify'>				
										6.2.2 Havendo mais de um DEVEDOR(A,ES) o FQM quitar� apenas as parcelas vincendas relativas ".$dev4." falecido(a,s), na propor��o indicada no Item XI. </p>
				<p align='justify'><b>				
										Altera��o dos percentuais devidos ao Fundo de Hedge</b></p>
				<p align='justify'>
										6.3 A PREVI poder� rever, periodicamente, em virtude da ocorr�ncia de altera��o do risco a ser coberto, as taxas que comp�e o Fundo de Hedge, visando manter seu equil�brio.   Ser� dada ampla divulga��o, por meio dos canais  de comunica��o institucionais da PREVI, a mudan�a do percentual aplicado, visto que esta poder� resultar em altera��o do valor da presta��o mensal.</p>				
				<p align='justify'><b>
										CL�USULA 7 � PAGAMENTO</b></p>
				<p align='justify'>
										".$dev6." obriga(m)-se a: i) reembolsar(em) a PREVI os pr�mios de seguro por ela pagos, na forma da cl�usula 11; ii) pagar(em) mensalmente as despesas de administra��o referidas na cl�usula 12 ; e iii) pagar(em) a presta��o que vencer� em cada m�s, calculada na forma da cl�usula 7.1.2 . </p>
				<p align='justify'><b>				
										Presta��o  mensal</b></p>
				<p align='justify'>
										7.1. A presta��o, composta pela soma dos valores relativos a amortiza��o do capital, juros e contribui��es ao Fundo de Hedge, dever� ser paga em parcelas mensais, consecutivas e postecipadas, sendo a primeira venc�vel no dia 20 do m�s seguinte ao da celebra��o do contrato e as demais no dia 20 dos meses subseq�entes.</p>
				<p align='justify'>				
										7.1.1  No pagamento da primeira presta��o ser�o cobrados, pro-rata dia, os encargos constantes nas cl�usulas 5 e 6,  devidos no per�odo compreendido entre a data do contrato e a data do vencimento da primeira presta��o.</p>
				<p align='justify'>				
										7.1.2 O valor das presta��es ser� recalculado anualmente, no m�s de anivers�rio do contrato, de acordo com a f�rmula abaixo:</p>
				
				<p>
							a=( b x ( 1 + d ) ) x ( 1/c + ( e + f ) )</p>
							<p>Onde:<br><br>
							a = presta��o recalculada<br>
							b = saldo devedor na data do rec�lculo<br>
							c = prazo remanescente em meses<br>
							d = taxa do �ndice atuarial projetado para os pr�ximos 12 (doze) meses<br>
							e = taxa mensal equivalente aos juros atuariais  estabelecidos para o Plano de Benef�cios 01da PREVI<br>
							f = taxa mensal equivalente do Fundo de Hedge
						</p>
				<p align='justify'><b>				
										Vencimento pelo decurso do prazo</b></p>
				<p align='justify'>
										7.2. O vencimento das obriga��es ".$dev3." decorrentes deste Contrato dar-se-� nas datas estipuladas nesta cl�usula, independentemente de qualquer comunica��o, notifica��o ou interpela��o, aplicando-se o previsto no art. 397 do C�digo Civil.</p>
				<p align='justify'>
										7.2.1. O vencimento da primeira presta��o, bem como da taxa de administra��o e do pr�mio de seguro, dar-se-�o na data mencionada na cl�usula 7.1, ainda que, at� essa data, n�o tenham sido liberados, total ou parcialmente, ao(s) VENDEDOR(A,ES) ou ao CREDOR QUITANTE os recursos referentes ao Financiamento, uma vez que: i) por for�a da quita��o dada pelo(a,s) VENDEDOR(A,ES) relativa ao pre�o da compra e venda, a PREVI j� est�, desde esta data, obrigada a liberar ao(s) VENDEDOR(A,ES) os recursos do Financiamento, tendo reservado em sua tesouraria tais recursos; e ii) a posse direta, bem como o uso e gozo do Im�vel j� foram transferidos ".$dev4.", fruindo este plenamente, desde j�, os efeitos econ�micos deste Financiamento.</p>
				<p align='justify'><b>
										Forma de pagamento</b></p>
				<p align='justify'>
										7.3 ".$dev6." obriga(m)-se a pagar todas as obriga��es decorrentes deste Contrato por meio de consigna��o em folha de pagamento de sal�rios ou benef�cios pagos pelo Banco do Brasil S.A., PREVI e/ou INSS, ficando a PREVI, desde j�, em car�ter irrevog�vel e irretrat�vel, autorizada a consignar na folha de pagamento ".$dev3.", conforme mencionado no n�mero 8 do Item VII, quaisquer obriga��es  decorrentes deste Contrato.</p>
				<p align='justify'>
										7.3.1. Caso ".$dev2.", no curso deste Contrato, receba(m) sal�rio insuficiente e/ou deixe de receber sal�rio ou benef�cio do Banco do Brasil S.A., PREVI e/ou INSS a forma de pagamento das obriga��es ".$dev3." prevista no n�mero 8 do Item VII, ser� alterada, de forma que tais pagamentos passem a ser efetuados por meio de d�bito na conta corrente indicada ".$dev5." no n�mero 4.2 do Item VI. Dessa forma, ".$dev2.", desde logo, autoriza(m), em car�ter irrevog�vel e irretrat�vel, para todos os efeitos legais e contratuais, que o Banco do Brasil S.A., sob pedido da PREVI, efetue o d�bito em sua conta corrente de todo e qualquer valor decorrente das obriga��es assumidas.</p>
				<p align='justify'>				
										7.3.2 Para efeito do disposto na cl�usula 7.3.1 ".$dev2." obriga(m)-se a manter conta-corrente no Banco do Brasil S.A., cabendo a ele informar � PREVI ag�ncia e o n�mero da conta corrente  quando houver qualquer altera��o do n�mero da mesma.</p>
				<p align='justify'>				
										7.3.3 A PREVI, a seu crit�rio, poder� alterar a forma de pagamento para liquida��o por meio de boleto de cobran�a banc�ria. Neste caso, a PREVI passar� a enviar os respectivos boletos ".$dev4.", os quais dever�o ser liquidados na forma neles estabelecida. A falta de recebimento de qualquer dos boletos n�o eximir� ".$dev2." de realizar(em) os pagamentos na data em que forem devidos, devendo ser realizados na forma indicada pela PREVI.</p>
				<p align='justify'>
										7.3.4 ".$dev6." que, por qualquer motivo, deixar(em) de receber os benef�cios do INSS por meio da folha de pagamentos da PREVI, neste ato expressamente autoriza(m) a PREVI a consignar o desconto das presta��es mensais, no todo ou em parte, diretamente na folha daquele Instituto.</p>
				<p align='justify'><b>
										Imputa��o do pagamento</b></p>
				<p align='justify'>
										7.4. Os pagamentos realizados ".$dev5." imputar-se-�o nas obriga��es devidas na seguinte ordem: i) a taxa de administra��o nos termos da cl�usula 12; ii) o reembolso dos pr�mios de seguro pagos pela PREVI, nos termos da cl�usula 11; iii) a contribui��o devida ao Fundo de Hedge; iv) a liquida��o dos juros remunerat�rios ; v) a liquida��o dos juros e encargos morat�rios, eventualmente devidos; e vi) a amortiza��o do principal.</p>
				<p align='justify'><b>
										Limita��o do valor das presta��es</b></p>
				<p align='justify'>
										7.5 O valor da presta��o mensal, conforme definido na cl�usula 7.1 ficar� limitado a, no m�ximo, 30% (trinta por cento) dos proventos brutos mensais contidos na folha de pagamentos do m�s anterior � data do vencimento. A presente limita��o n�o se estende �s obriga��es acess�rias, como a taxa de administra��o e as despesas de seguros.</p>
				<p align='justify'>						
										7.5.1 Para ".$dev2." aposentado(a,s) ou pensionista(s) considera-se como proventos brutos a soma dos benef�cios recebidos da PREVI e do INSS. Caso ".$dev2." n�o receba(m) os benef�cios do INSS via folha de pagamentos da PREVI e na aus�ncia de comprova��o do benef�cio recebido por aquele Instituto, ser� utilizado para compor o total de proventos brutos, para os fins desta cl�usula, o valor do teto vigente de benef�cios definido pelo INSS.</p>
				<p align='justify'>						
										7.5.2 Para ".$dev2." aposentado(a,s) ou pensionista(s) que receba apenas o benef�cio do INSS pela PREVI, ser� considerado como proventos brutos, para fins da limita��o de que trata esta cl�usula, a renda bruta que serviu de base para a concess�o do financiamento, devidamente atualizada pelo �ndice previsto na cl�usula4.</p>
				<p align='justify'>						
										7.5.3 A".$dev2." que recebe(m) da PREVI a antecipa��o da complementa��o de aposentadoria, sem o benef�cio do INSS, considera-se como proventos brutos apenas os valores pagos pela PREVI, at� a concess�o de aposentadoria pelo INSS, ap�s este evento ser� aplicado o disposto na cl�usula 7.5.1.</p>
				<p align='justify'>						
										7.5.4 Se ".$dev2.", por qualquer motivo, deixar de receber(em) proventos do Banco do Brasil S.A., da PREVI ou do INSS ser� considerada como renda bruta, para fins da limita��o prevista na cl�usula 7.5 a renda bruta que serviu de base para a concess�o do financiamento, devidamente atualizada pelo �ndice previsto na cl�usula 4.</p>
				<p align='justify'>						
										7.5.5 Caso ".$dev2." que tiver(em) rompido o v�nculo empregat�cio com o Banco do Brasil S.A. e cancelado sua inscri��o junto � PREVI vier a reingressar nos quadros do Banco do Brasil S.A., independentemente de nova ades�o � PREVI, ser� considerada, para os efeitos de limita��o previstos na cl�usula 7.5 a renda bruta que serviu de base para a concess�o do financiamento, devidamente atualizada pelo �ndice previsto na cl�usula 4.</p>
				<p align='justify'>						
										7.5.6 Eventual res�duo existente ao final do contrato decorrente da limita��o tratada nesta cl�usula ser� liquidado com recursos do Fundo de Liquidez, previsto na cl�usula 6.1.</p>
				<p align='justify'>						
										7.5.7 A limita��o dos 30% (trinta por cento) n�o se estende aos valores cobrados relativos a obriga��es de compet�ncias anteriores � vigente, em decorr�ncia de reprocessamento ou acerto.</p>
				<p align='justify'><b>									
										CL�USULA 8 � PAGAMENTOS ANTECIPADOS</b></p>
				<p align='justify'>						
										".$dev6." que n�o se encontrar(em) em mora com qualquer de suas obriga��es decorrentes deste Contrato poder� realizar amortiza��es extraordin�rias do Saldo Devedor, mediante solicita��o por escrito � PREVI, desde que o valor a ser amortizado n�o seja inferior a uma presta��o mensal vigente � �poca em que se realizar a amortiza��o.</p>
				<p align='justify'><b>						
										Juros pro rata die</b></p>
				<p align='justify'>						
										8.1. Caso a data da realiza��o da amortiza��o extraordin�ria n�o coincida com a data de vencimento de qualquer das presta��es, prevista na cl�usula 7, ao Saldo Devedor a ser amortizado ser�o acrescidos, para todos os efeitos desta cl�usula, a atualiza��o, na forma da cl�usula 4, o Fundo de Hedge e  os juros incorridos desde a data de vencimento da parcela de principal imediatamente anterior � amortiza��o extraordin�ria at� a data em que essa se realizar, calculados pelo crit�rio pro rata die.</p>
				<p align='justify'><b>						
										Imputa��o das amortiza��es extraordin�rias</b></p>
				<p align='justify'>
										8.2. Os valores efetivamente pagos ".$dev5." a t�tulo de amortiza��o extraordin�ria ser�o deduzidos do Saldo Devedor total, acrescido dos montantes referidos na cl�usula 8.1, mantendo-se o prazo original do Financiamento e reduzindo-se, dessa forma, proporcionalmente, o valor da presta��o mensal.</b>
				<p align='justify'>						
										8.2.1. N�o obstante o disposto na cl�usula 8.2, ".$dev2." poder�(�o) solicitar a manuten��o do valor da presta��o e a redu��o do prazo total do financiamento. Caso ".$dev2." n�o efetue(m) a solicita��o aqui prevista, aplicar-se-� a regra mencionada na cl�usula 8.2.</p>
				<p align='justify'>						
										8.2.2 Se ".$dev2." estiver(em) com a presta��o do financiamento limitada nos termos da cl�usula 7.5 e optar pela redu��o do prazo do financiamento, ser� utilizado para fins de rec�lculo do novo prazo remanescente, em decorr�ncia da amortiza��o extraordin�ria, o valor da presta��o com a limita��o citada. </p>
				<p align='justify'>						
										8.3 Os rec�lculos mencionados nesta cl�usula ser�o realizados de forma independente do rec�lculo anual previsto na cl�usula 7.1.2.</p>
				<p align='justify'>						
										8.4 Caso ".$dev2." venha(m) a romper(em) o v�nculo empregat�cio com o Banco do Brasil S.A. e cancelar sua inscri��o junto � PREVI, ser� utilizado para quitar ou amortizar o presente financiamento imobili�rio o saldo correspondente � Diferen�a de Reserva Matem�tica. Caso este saldo n�o seja suficiente para liquida��o da d�vida, poder� ser utilizado o saldo da Reserva Pessoal de Poupan�a. ".$dev6.", neste ato expressamente autoriza(m) a utiliza��o destes valores para compensa��o com a d�vida oriunda do financiamento imobili�rio.</p>
				<p align='justify'>						
										8.5 Caso, em fun��o de evento de perda de renda, a PREVI tenha se abstido de consignar o valor integral da presta��o devida para respeitar a limita��o m�xima de 30% (trinta por cento) prevista na cl�usula 7.5. e ".$dev2." venha(m) a manifestar(em) inten��o de quitar antecipadamente sua d�vida, o Fundo de Hedge, nesta hip�tese, n�o poder� ser invocado por este para cobrir saldo das diferen�as geradas pelo evento. </p>
				<p align='justify'><b>
										CL�USULA 9 � JUROS E ENCARGOS MORAT�RIOS  </b></p>
				<p align='justify'>
										Caso ".$dev2." n�o pague(m), na data de seu vencimento, qualquer obriga��o pecuni�ria, de qualquer natureza, principal ou acess�ria, ser�o devidos � PREVI: i) atualiza��o monet�ria dos valores n�o pagos pelo �ndice previsto no n�mero 3 do  Item VII;  ii) juros contratuais previstos no n�mero 4 do Item VII; iii) multa n�o indenizat�ria de 2% (dois por cento) e juros morat�rios de 1% a.m. (um por cento ao m�s) sobre os valores em atraso  atualizados acrescidos dos juros definidos no n�mero 4 do Item VII; e iv) despesas de cobran�a e honor�rios advocat�cios.</p>
				<p align='justify'>						
										9.1. No caso da excuss�o da garantia de aliena��o fiduci�ria ora constitu�da, ".$dev2." arcar�(�o) com todos os custos e despesas dela decorrentes  e demais comina��es legais e convencionais.</p>
				<p align='justify'><b>						
										CL�USULA 10 � VENCIMENTO ANTECIPADO</b></p>
				<p align='justify'>						
										A PREVI poder� considerar antecipadamente vencidas e imediatamente exig�veis todas as obriga��es ".$dev3." decorrentes deste Contrato, caso ocorra qualquer das seguintes hip�teses:</p>
				<p align='justify'>						
										I � se ".$dev2." ceder(em) ou transferir(em) a terceiros os seus direitos e obriga��es decorrentes deste Contrato, ou vender ou prometer vender, por qualquer outra forma, o Im�vel, ou sobre ele constituir quaisquer �nus ou gravames, sem pr�vio e expresso consentimento da PREVI;</p>
				<p align='justify'>						
										II � se ".$dev2." incorrer(em) em mora, total ou parcial, com rela��o ao pagamento de qualquer obriga��o decorrente deste Contrato e o referido inadimplemento n�o for saldado dentro de 90 (noventa) dias;</p>
				<p align='justify'>						
										III � se contra ".$dev2." for movida qualquer a��o ou execu��o real ou reipersecut�ria cujo objeto seja o Im�vel, ou caso este seja objeto de qualquer medida constritiva, judicial ou administrativa, tais como penhora, seq�estro ou arresto;</p>
				<p align='justify'>						
										IV � se ".$dev2." tiver sua insolv�ncia civil decretada ou, se for empres�rio, requerer recupera��o judicial ou extrajudicial, ou fal�ncia, ou tiver sua fal�ncia requerida por terceiros;</p>
				<p align='justify'>						
										V � se qualquer das declara��es feitas ".$dev5." ou pelo(a,s) VENDEDOR(A,ES) neste Contrato revelar-se err�nea, enganosa, falsa ou inver�dica;</p>
				<p align='justify'>						
										VI � se houver o descumprimento ".$dev5." de qualquer obriga��o por ele(A,ES) assumida neste Contrato, inclusive daquelas relativas � garantia de aliena��o fiduci�ria ora constitu�da;</p>
				<p align='justify'>						
										VII � se ".$dev2." deixar(em) de apresentar � PREVI anualmente, ou quando solicitado para tanto, os recibos comprobat�rios do pagamento dos impostos e taxas, despesas condominiais, bem como quaisquer outros tributos incidentes sobre o Im�vel;</p>
				<p align='justify'>						
										VIII � se o Im�vel for desapropriado, no todo ou em parte;</p>
				<p align='justify'>						
										IX � se ".$dev2." n�o mantiver(em) o Im�vel em perfeito estado de conserva��o, seguran�a e habitabilidade, ou nele realizar, sem o pr�vio e expresso consentimento da PREVI, obras de demoli��o, altera��o ou acr�scimo;</p>
				<p align='justify'>						
										X � se ocorrer qualquer das hip�teses previstas no artigo  333 do C�digo Civil;</p>
				<p align='justify'>						
										XI � se houver utiliza��o indevida da indeniza��o do seguro conforme especificado na cl�usula 11.7</p>
				<p align='justify'>						
										XII � se, por qualquer forma, se constatar que ".$dev2." se furtou � finalidade a que o financiamento objetivou, dando ao im�vel outra destina��o que n�o seja a sua ocupa��o residencial.</p>
				<p align='justify'>									
										10.1 Na hip�tese de im�vel financiado para mais de um DEVEDOR, conforme previsto na cl�usula 19, o vencimento antecipado se dar� em rela��o a todos os DEVEDORES. </p>
				<p align='justify'><b>						
										Pagamento no caso de vencimento antecipado</b></p>
				<p align='justify'>						
										10.2. Ocorrendo o vencimento antecipado de suas obriga��es, nos termos aqui previstos, e caso a PREVI n�o tenha iniciado, ainda, o procedimento de excuss�o da garantia, fazendo intimar ".$dev2." nos termos da cl�usula 15, ".$dev2." dever�(�o) pagar � PREVI a totalidade do Saldo Devedor, acrescido dos juros, contribui��es para o Fundo de Hedge, taxa de administra��o e pr�mio de seguro at� ent�o incorridos, 24 (vinte e quatro) horas ap�s ser extrajudicialmente notificado para tanto, por simples carta enviada com Aviso de Recebimento ou por qualquer outro meio h�bil, sob pena de incorrer em mora com rela��o a tais quantias, passando a incidir sobre elas os juros e encargos morat�rios previstos na cl�usula 9, e sob pena de consolida��o da propriedade do Im�vel em nome da PREVI, nos termos da cl�usula 15.</p>
				<p align='justify'><b>						
										CL�USULA 11 � SEGURO </b></p>
				<p align='justify'>						
										Durante a vig�ncia deste contrato e at� a amortiza��o definitiva da d�vida, ".$dev2." autoriza(m) a PREVI a contratar, junto � companhia seguradora de primeira linha, seguro contra danos f�sicos ao im�vel, conforme condi��es das coberturas do seguro, anexas ao contrato, figurando a PREVI como �nica e exclusiva benefici�ria do seguro, podendo exigir e receber as respectivas indeniza��es.</p>
				<p align='justify'>						
										11.1 A PREVI n�o se responsabiliza por danos causados ao im�vel em decorr�ncia de riscos n�o cobertos pelo seguro contratado.</p>
				<p align='justify'>						
										11.2 A cobertura do seguro se dar� a partir da assinatura deste instrumento, regendo-se pelas cl�usulas e condi��es constantes da Ap�lice estipulada pela PREVI.</p>
				<p align='justify'>						
										11.3 O seguro contra morte ".$dev3." fica, para este financiamento, substitu�do por contribui��es ao Fundo de Quita��o por Morte, previsto na cl�usula 6.2, deste Contrato. </p>
				<p align='justify'>						
										11.4 N�o ser� exigida ".$dev3." a contrata��o de seguro para cobertura de invalidez permanente, enquanto este mantiver contratado com a PREVI plano de aposentadoria que assegure a complementa��o do sal�rio na situa��o de aposentadoria por invalidez permanente.  Entretanto, ".$dev2." autoriza a PREVI a contratar em seu nome,  seguro para cobertura de invalidez permanente com cl�usula benefici�ria � PREVI, na hip�tese de desvincula��o ".$dev3." do plano de aposentadoria contratado com a PREVI.</p>
				<p align='justify'><b>						
										Pagamento dos pr�mios do seguro</b></p>
				<p align='justify'>						
										11.5 ".$dev6.", neste ato, autoriza(m) a PREVI a pagar, em seu nome, diretamente � companhia seguradora, o pr�mio do seguro contratado, nos termos desta cl�usula, obrigando-se a reembols�-la dos montantes pagos, sendo certo que tal reembolso dever� ser feito juntamente com a presta��o mensal no m�s subseq�ente � assinatura do contrato, e todo o m�s de novembro nas renova��es durante o per�odo do financiamento, sendo certo que esse valor considerar-se-� automaticamente alterado quando, por qualquer motivo, for modificado pela companhia seguradora.</p>
				<p align='justify'>						
										11.6 No primeiro ano de vig�ncia do contrato, o seguro ser� cobrado, pro-rata dia, desde a data da contrata��o do financiamento at� a data do vencimento da ap�lice em vigor. </p>
				<p align='justify'><b>						
										Utiliza��o do seguro </b></p>
				<p align='justify'>						
										11.7 Em caso de sinistro, as partes obrigam-se a utilizar os montantes recebidos da companhia seguradora, conforme definido nas condi��es gerais da ap�lice, para repor o Im�vel ao estado em que este se encontrava anteriormente � ocorr�ncia de tais danos ou, caso tal reposi��o n�o seja poss�vel, a indeniza��o dever� ser utilizada para amortizar ou liquidar todas as obriga��es oriundas deste Contrato, restituindo-se ".$dev4." o montante que, eventualmente, sobejar.</p>
				<p align='justify'>										
										11.7.1 Caso venha a seguradora, na indeniza��o de seguro de natureza material, optar pelo pagamento em esp�cie, a PREVI n�o assumir� qualquer obriga��o de financiar poss�vel diferen�a entre o custo or�ado na nova obra e o valor da indeniza��o recebida.</p>
				<p align='justify'><b>										
										Obriga��es ".$dev3." referentes ao seguro</b></p>
				<p align='justify'>						
										11.8. S�o obriga��es ".$dev3." em rela��o aos seguros contratados nos termos desta cl�usula:</p>
				<p align='justify'>						
										a. formalizar comunica��o � companhia seguradora e � PREVI, imediatamente, a ocorr�ncia de sinistro coberto pela respectiva ap�lice, relatando todos os fatos a ele relacionados de modo a permitir sua completa elucida��o;</p>
				<p align='justify'>						
										b. tomar todas as provid�ncias necess�rias para a limita��o das conseq��ncias do sinistro;</p>
				<p align='justify'>						
										c. caso o sinistro seja imput�vel a terceiros, ".$dev2." dever�(�o) fornecer os documentos necess�rios para que a companhia seguradora exer�a os seus direitos contra tais terceiros, inclusive com outorga de mandato com os necess�rios poderes para tal fim;</p>
				<p align='justify'>						
										d. dar conhecimento aos seus descendentes, ascendentes, c�njuge ou companheiro(a), da exist�ncia dos seguros aqui referidos e da obrigatoriedade de comunica��o imediata � companhia seguradora e � PREVI caso ocorra qualquer sinistro coberto por tais seguros.</b>
				<p align='justify'>						
										11.9 ".$dev6." declara(�o) que recebeu , juntamente com o presente instrumento c�pia da Ap�lice de seguro estipulada pela PREVI, tomando ci�ncia das condi��es pactuadas.</p>
				<p align='justify'><b>				
										CL�USULA 12 � TAXA DE ADMINISTRA��O</b></p>
				<p align='justify'>						
										".$dev6." pagar�(�o), � PREVI, juntamente com a presta��o mensal, a Taxa de Administra��o mencionada no n�mero 2 do Item VIII, a t�tulo de ressarcimento dos custos pela administra��o, gest�o da cobran�a do Financiamento e de todos os processos a eles vinculados, nos termos da Resolu��o CMN 3.121/03,  cujo valor poder� ser revisto periodicamente pela PREVI.</p>
				<p align='justify'><b>						
										CL�USULA 13 � ALIENA��O FIDUCI�RIA</b></p>
				<p align='justify'>						
										Para garantir o cumprimento de todas e quaisquer obriga��es principais e acess�rias, inclusive as referentes � restitui��o de principal e ao pagamento de juros, encargos, comiss�es, tarifas, reembolso dos pr�mios de seguro pagos na forma da cl�usula 11.5, multas e encargos morat�rios, por si assumidas neste Contrato (�Obriga��es Garantidas�), ".$dev2.", neste ato, nos termos e para os efeitos dos arts. 22 e seguintes da Lei n� 9.514/97, transfere � PREVI, em car�ter fiduci�rio, a propriedade resol�vel e a posse indireta sobre o Im�vel, que foi adquirido ".$dev5." por compra e venda, nos termos deste Contrato. ".$dev6.", enquanto adimplente, manter� consigo a posse direta sobre o Im�vel, podendo utiliz�-lo livremente, por sua conta e risco.</p>
				<p align='justify'><b>						
										Compreens�o e extin��o da propriedade resol�vel da PREVI</b></p>
				<p align='justify'>						
										13.1. Por for�a da aliena��o fiduci�ria ora contratada, a PREVI passa a deter a propriedade resol�vel e a posse indireta sobre o Im�vel e todas as acess�es, melhoramentos, constru��es e instala��es nele existentes e que a ele forem acrescidas. A propriedade fiduci�ria detida pela PREVI sobre o Im�vel ser� eficaz at� o final e total pagamento de todas as Obriga��es Garantidas, e resolver-se-� de pleno direito com o cancelamento do registro da propriedade fiduci�ria, o qual ser� feito pelo Oficial de Registro de Im�veis competente, mediante a exibi��o de termo de quita��o, entregue pela PREVI ".$dev4.", nos termos da cl�usula 14.</p>
				<p align='justify'><b>										
										Impostos e contribui��es</b></p>
				<p align='justify'>						
										13.2. ".$dev6." obriga(m)-se a pagar pontualmente todos os impostos, taxas e quaisquer outras contribui��es ou encargos que incidam ou venham a incidir sobre a posse ou sobre a propriedade resol�vel do Im�vel, tais como Imposto Predial e Territorial Urbano � IPTU, contribui��es devidas ao condom�nio de utiliza��o do edif�cio ou a associa��o que congregue os moradores do conjunto imobili�rio respectivo, exibindo os respectivos comprovantes � PREVI, anualmente, ou quando solicitado.</p>
				<p align='justify'>						
										13.2.1. Caso ".$dev2." n�o pague(m) em dia todos os impostos e demais tributos que incidam ou venham a incidir sobre o Im�vel, poder� a PREVI faz�-lo, ficando ".$dev2." obrigado(a,s) a reembols�-la das quantias despendidas no prazo de 24 (vinte e quatro) horas ap�s recebimento de notifica��o encaminhada por esta, sob pena de, sobre tais quantias, incidirem os juros e encargos morat�rios estipulados na cl�usula 9. O reembolso devido � PREVI ".$dev5.", nos termos desta cl�usula, fica garantido pela presente aliena��o fiduci�ria.</p>
				<p align='justify'><b>						
										Conserva��o do Im�vel</b></p>
				<p align='justify'>						
										13.3. ".$dev6." compromete(m)-se a manter e conservar o Im�vel em perfeito estado de seguran�a e habitabilidade, bem como a realizar �s suas custas, dentro do prazo que lhe for determinado para tanto, as obras e os reparos julgados necess�rios, ficando vedada a realiza��o de qualquer obra de modifica��o ou acr�scimo no Im�vel sem o pr�vio consentimento da PREVI. O cumprimento dessa obriga��o poder� ser fiscalizado pela PREVI, obrigando-se ".$dev2." a permitir o ingresso de pessoa credenciada para executar as vistorias peri�dicas.</p>
				<p align='justify'><b>						
										Desapropria��o do Im�vel</b></p>
				<p align='justify'>						
										13.4. ".$dev6.", desde j�, de forma irrevog�vel e irretrat�vel, autoriza(m) a PREVI a receber, em seu nome, todas as quantias referentes a indeniza��es pagas pelo poder expropriante por for�a de desapropria��o, integral ou parcial, do Im�vel, por qualquer forma ou motivo, aplicando tais valores na amortiza��o ou liquida��o das Obriga��es Garantidas, colocando o remanescente, se houver, � disposi��o ".$dev3.", na forma prevista na cl�usula 16.5.</p>
				<p align='justify'>						
										13.4.1. ".$dev6.", pelo presente Contrato e na melhor forma de direito, nomeia(m) e constitui(em) a PREVI sua procuradora, na forma do artigo 684 do C�digo Civil, com amplos e irrevog�veis poderes para, em ju�zo ou fora dele, represent�-lo junto aos �rg�os p�blicos federais, municipais ou estaduais, bancos, autarquias e demais entidades p�blicas e privadas, bem como perante Agentes Financeiros ou companhias de seguros em todos os assuntos referentes � desapropria��o e aos seguros, para receber import�ncias em casos de sinistros ou desapropria��o amig�vel ou judicial, total ou parcial, decorrentes de pagamento de seu cr�dito, podendo, ainda, assinar, reconhecer, aceitar, dar quita��o, receber, endossar, requerer, impugnar, concordar, recorrer, desistir, transigir, firmar compromissos e substabelecer. A presente outorga de poderes ser� eficaz at� o pagamento final e total das Obriga��es Garantidas.</p>
				<p align='justify'><b>						
										Reten��o e indeniza��o por benfeitorias</b></p>
				<p align='justify'>						
										13.5. Nos termos do disposto nos par�grafos 4� e 5� do artigo 27 da Lei 9.514/97, jamais haver� direito de reten��o por benfeitorias realizadas ".$dev5." no Im�vel, mesmo que tenham car�ter de necess�rias ou �teis, ou que tenham sido autorizadas pela PREVI.</p>
				<p align='justify'>					
										13.5.1. Nos termos do �4� ao art. 27 da lei 9.514/97, na hip�tese de a propriedade do im�vel dado em garantia consolidar-se em nome da PREVI, a indeniza��o por benfeitorias resumir-se-�, sempre, ao saldo que sobejar do pre�o pago pelo Im�vel, depois de liquidadas as Obriga��es Garantidas e as demais despesas e acr�scimos legais, sendo certo que, n�o ocorrendo a venda do im�vel nos leil�es extrajudiciais, e extinguindo-se as obriga��es ".$dev3." decorrentes deste Contrato, nos termos da cl�usula 16.6, n�o haver� nenhum direito de indeniza��o pelas benfeitorias.</p>
				<p align='justify'><b>						
										CL�USULA 14 � QUITA��O DAS OBRIGA��ES D".$dev6."</b></p>
				<p align='justify'>						
										No prazo de 30 (trinta) dias, a contar da data do efetivo pagamento final e total das Obriga��es Garantidas, a PREVI enviar� ".$dev4.", o respectivo termo de quita��o, correspondente �s obriga��es assumidas ".$dev5." neste Contrato, sob pena de multa em favor ".$dev3." equivalente a 0,5% (meio por cento) ao m�s, ou fra��o, sobre o valor do Financiamento.</p>
				<p align='justify'><b>						
										Cancelamento da propriedade fiduci�ria</b></p>
				<p align='justify'>						
										14.1. Enviado o termo de quita��o aqui mencionado, fica ".$dev2." autorizado(a,s) a requerer(em), ao Oficial de Registro de Im�veis competente, o cancelamento do registro da propriedade fiduci�ria, com a respectiva restitui��o ".$dev4." da propriedade sobre o Im�vel.</p>
				<p align='justify'>						
										14.1.1. O envio do termo de quita��o pela PREVI, nos termos aqui previstos, simbolizar� a transfer�ncia ".$dev4." da posse indireta exercida pela PREVI sobre o Im�vel, consolidando-se, dessa forma, na pessoa ".$dev3.", a posse plena sobre esse.</p>
				<p align='justify'><b>						
										Pagamento com recursos oriundos do FGTS</b></p>
				<p align='justify'>						
										14.2. Caso ".$dev2." utilize(m) seus recursos do Fundo de Garantia por Tempo de Servi�o � FGTS para liquidar as Obriga��es Garantidas, tais obriga��es apenas considerar-se-�o quitadas ap�s o efetivo recebimento, pela PREVI, dos referidos recursos, os quais lhe ser�o entregues pela Caixa Econ�mica Federal � CEF.</p>				<p align='justify'>						
				<p align='justify'><b>						
										CL�USULA 15 � MORA E CONSOLIDA��O DA PROPRIEDADE FIDUCI�RIA</b></p>
				<p align='justify'>						
										Verificada a mora ".$dev3." com rela��o a qualquer obriga��o por ele assumida nos termos deste Contrato, e decorrido o prazo de car�ncia de 90 (noventa) dias, contados da data em que se verificou a mora, sem que haja a sua purga��o, a PREVI poder� fazer intimar ".$dev2.", nos termos do art. 26, �1� da Lei n� 9.514/97, fixando o prazo de at� 15 (quinze) dias para que purgue a mora, pagando ao Oficial de Registro de Im�veis competente o montante equivalente ao valor de todas as suas obriga��es decorrentes deste Contrato que se encontrem vencidas e n�o pagas, inclusive aquelas que vencerem no curso da intima��o, acrescido dos juros e encargos morat�rios conforme pactuados neste Contrato e de todos os custos e despesas de intima��o, bem como tributos e contribui��es condominiais e associativas que porventura se encontrarem vencidos na data da purga��o da mora.</p>
				<p align='justify'><b>						
										Pagamento parcial</b></p>
				<p align='justify'>						
										15.1. O pagamento do valor de principal das obriga��es em mora sem que haja o respectivo pagamento  de juros e encargos, inclusive morat�rios, dos custos e despesas havidos com sua intima��o, n�o exonerar� ".$dev2." da responsabilidade de liquidar a totalidade de suas obriga��es em mora, sendo certo que o saldo devedor restante de tais obriga��es dever� ser pago juntamente com o pagamento da parcela de principal cujo vencimento seja imediatamente subseq�ente a tal purga��o parcial, sob pena de a PREVI poder requerer ao Oficial de Registro de Im�veis que certifique a n�o-purga��o da mora no prazo assinado e, assim, consolide a propriedade do Im�vel em nome da PREVI.</p>
				<p align='justify'><b>									
										Forma de realiza��o da intima��o</b></p>
				<p align='justify'>
										15.2. A realiza��o da intima��o ".$dev3.", referida nesta cl�usula, caber� ao Oficial de Registro de Im�veis que, a seu crit�rio, poder� faz�-lo: i) pessoalmente; ii) por preposto seu; iii) atrav�s do Servi�o de Registro de T�tulos e Documentos da Comarca da situa��o do Im�vel ou do domic�lio ".$dev3."; ou, ainda, iv) pelo Correio, desde que enviada com Aviso de Recebimento � AR, a ser firmado pessoalmente ".$dev5." ou por seu representante.</p>
				<p align='justify'>						
										15.2.1. O Oficial de Registro de Im�veis providenciar� a realiza��o da intima��o ".$dev3." ap�s requerimento da PREVI, a qual indicar� ao Oficial o valor das obriga��es ".$dev3." vencidas e n�o pagas, acrescidas dos juros e encargos morat�rios, incidentes nos termos da cl�usula 9.</p>
				<p align='justify'><b>						
										Recebimento da intima��o e intima��o por edital</b></p>
				<p align='justify'>						
										15.3. A intima��o dever� ser recebida pessoalmente ".$dev5." ou por seu representante regularmente constitu�do, sendo certo que, caso ".$dev2." encontre-se em local incerto e n�o sabido, assim certificado pelo Oficial de Registro de Im�veis ou pelo Oficial de T�tulos e Documentos, conforme o caso, competir� ao primeiro promover a intima��o ".$dev3." por edital.</p>
				<p align='justify'>								
										15.3.1. O edital de intima��o ser� publicado por 3 (tr�s) dias, ao menos, consecutivos ou n�o, em um dos jornais de maior circula��o editados no local do Im�vel ou, se no local do Im�vel n�o houver imprensa com circula��o di�ria, editado em outra comarca de f�cil acesso, sendo certo que o prazo de 15 (quinze) dias para a purga��o da mora ser� contado a partir da �ltima publica��o do edital.</p>
				<p align='justify'><b>						 
										Purga��o da mora ao Oficial de Registro de Im�veis</b></p>
				<p align='justify'>						
										15.4. ".$dev6." poder�(�o) efetuar a purga��o da mora aqui referida: i) entregando, em dinheiro, ao Oficial do Registro de Im�veis competente o valor necess�rio para a purga��o da mora; ou ii) entregando, ao Oficial do Registro de Im�veis competente, cheque administrativo, emitido por banco comercial, intransfer�vel por endosso e nominativo � PREVI ou a quem expressamente indicado na intima��o, no valor necess�rio para a purga��o da mora. Nessa hip�tese, a entrega do cheque ao Oficial do Registro de Im�veis ser� feita sempre em car�ter pro solvendo, de forma que a purga��o da mora ficar� condicionada ao efetivo pagamento do cheque pela institui��o financeira sacada. Recusado o pagamento do cheque, a mora ser� tida por n�o purgada, podendo a PREVI requerer que o Oficial do Registro de Im�veis certifique, nos termos do art. 26, �7 da Lei n� 9.514/97, que a mora n�o restou purgada e promova a consolida��o, em nome da PREVI, da propriedade fiduci�ria.</p>
				<p align='justify'>						
										15.4.1. O Oficial do Registro de Im�veis receber� o pagamento efetuado ".$dev5." por conta da PREVI e entregar� a esta as import�ncias recebidas.</p>
				<p align='justify'><b>						
										Consolida��o da propriedade em nome da PREVI</b></p>
				<p align='justify'>						
										15.5. Caso n�o haja a purga��o da mora no prazo determinado na intima��o referida nesta cl�usula, a PREVI poder�, com a apresenta��o do devido recolhimento do Imposto sobre Transmiss�o de Bens Im�veis � ITBI, requerer ao Oficial de Registro de Im�veis que certifique o decurso in albis do prazo para a purga��o da mora e consolide, em nome da PREVI, a propriedade plena do Im�vel, contando, a partir do registro da consolida��o, o prazo para a realiza��o dos leil�es extrajudiciais previstos na cl�usula 16.</p>
				<p align='justify'><b>						
										Desocupa��o do Im�vel</b></p>
				<p align='justify'>						
										15.6. ".$dev6." dever�(�o) desocupar o Im�vel no dia seguinte ao da consolida��o da propriedade plena em nome da PREVI, deixando-o livre e desimpedido de pessoas e coisas, sob pena de pagamento � PREVI, ou �quele que tiver adquirido o im�vel em leil�o, de multa di�ria, n�o indenizat�ria, equivalente a 0,033% (trinta e tr�s mil�simos por cento) do valor de avalia��o do Im�vel estipulado no Item III, sem preju�zo de sua responsabilidade pelo pagamento: a) do foro e das despesas de �gua, luz e g�s referentes ao Im�vel; b) de todas as despesas e contribui��es devidas ao condom�nio de utiliza��o ou � associa��o que congregue os moradores do conjunto imobili�rio integrado pelo Im�vel; c) de todas as despesas necess�rias � reposi��o do Im�vel ao estado em que o recebeu.</p>
				<p align='justify'>						
										15.6.1. N�o ocorrendo a desocupa��o do Im�vel ".$dev5.", no prazo e forma ajustados nesta cl�usula 15.6, independentemente da penalidade estipulada no caput desta cl�usula, a PREVI ou o adquirente do Im�vel poder�o propor a��o de reintegra��o de posse contra ".$dev2.", sem preju�zo da cobran�a e execu��o do valor da multa di�ria de ocupa��o e demais despesas previstas no caput desta cl�usula e neste Contrato. ".$dev6." declara(m)-se ciente de que, nos termos do art. 30 da Lei n� 9.514/97, tal reintegra��o ser� concedida liminarmente, com ordem judicial para desocupa��o no prazo m�ximo de 60 (sessenta) dias.</p>
				<p align='justify'>						
										15.6.2. A penalidade di�ria referida no caput desta cl�usula incidir� a partir do 30� (trig�simo) dia subseq�ente ao da consolida��o da propriedade plena em nome da PREVI.</p>
				<p align='justify'><b>						
										CL�USULA 16 � DO LEIL�O EXTRAJUDICIAL</b></p>
				<p align='justify'>						
										Uma vez consolidada a propriedade do Im�vel em nome da PREVI, esta dever� promover a realiza��o de leil�es p�blicos, extrajudiciais, conforme previsto no art. 27 da Lei n� 9.514, a fim de alienar o Im�vel a terceiros interessados, e utilizar o pre�o recebido para liquidar as Obriga��es Garantidas. Os leil�es ser�o conduzidos por leiloeiro oficial, legalmente habilitado para tanto e eleito pela PREVI, ao qual ser� devida comiss�o � taxa que se praticar para esse tipo de leil�o no local em que este for realizado.</p>
				<p align='justify'><b>						
										Primeiro p�blico leil�o</b></p>
				<p align='justify'>						
										16.1. O primeiro p�blico leil�o ser� realizado no prazo m�ximo de 30 (trinta) dias, contados da data do registro da consolida��o da plena propriedade em nome da PREVI. O pre�o m�nimo de venda do Im�vel, nesse primeiro p�blico leil�o, equivaler� ao valor de avalia��o do Im�vel, estipulado pelas Partes, no Item III, o qual ser� atualizado pela mesma taxa de atualiza��o do Saldo Devedor constante da cl�usula 4, e que poder�, a crit�rio exclusivo da PREVI, ser revisto por meio de nova avalia��o, realizada por companhia id�nea a ser indicada pela PREVI, incluindo-se os custos de avalia��o no Saldo Devedor</p>
				<p align='justify'>						
										16.1.1. Considera-se inclu�do no valor do pre�o m�nimo de venda do Im�vel o valor de todas e quaisquer benfeitorias, necess�rias, �teis e voluptu�rias, executadas ".$dev5." no Im�vel.</p>
				<p align='justify'><b>						
										Segundo p�blico leil�o</b></p>
				<p align='justify'>						
										16.2. N�o havendo, no primeiro p�blico leil�o, oferta em montante igual ou superior ao pre�o m�nimo de venda do Im�vel, conforme a cl�usula 16.1, a PREVI dever� promover um segundo p�blico leil�o, no prazo de at� 15 (quinze) dias, contado da data da realiza��o do primeiro p�blico leil�o.</p>
				<p align='justify'>						
										16.2.1. O pre�o m�nimo de venda do Im�vel, no segundo p�blico leil�o, equivaler� ao somat�rio do valor das Obriga��es Garantidas, dos juros e encargos morat�rios incorridos at� a data da realiza��o do segundo p�blico leil�o, e do valor das seguintes obriga��es ".$dev3.", que se encontrem vencidas e n�o pagas at� a data da realiza��o do segundo leil�o: i) pr�mios de seguro; ii) contribui��es devidas ao condom�nio de utiliza��o, ou contribui��es devidas a associa��o de moradores ou entidade assemelhada; iii) despesas de �gua, luz e g�s; iv) Imposto Predial e Territorial Urbano � IPTU, foro e outros tributos ou contribui��es eventualmente incidentes sobre a propriedade ou a posse do Im�vel; v) Imposto sobre Transmiss�o de Bens Im�veis - ITBI e laud�mio, e demais custos e despesas, inclusive despesas de cobran�a, eventualmente devidos por for�a da consolida��o da propriedade plena do Im�vel em nome da PREVI; vi) encargos e custas de intima��o ".$dev3."; vii) encargos e custas com a publica��o do edital de an�ncio de ambos os leil�es; viii) a comiss�o devida ao leiloeiro; ix) custos de avalia��o do Im�vel; e x) quantias devidas ".$dev5." nos termos das cl�usulas 15.6 e 15.6.1.</p>
				<p align='justify'><b>						
										Local de realiza��o dos leil�es</b></p>
				<p align='justify'>						
										16.3. Os p�blicos leil�es ser�o realizados no local da situa��o do Im�vel, na capital do Estado em que este se localiza, ou no local da sede da PREVI, conforme op��o desta, e ser�o anunciados mediante edital, publicado por 3 (tr�s) vezes, ao menos, devendo mediar, entre a primeira publica��o e a realiza��o do leil�o nela anunciado, no m�nimo, 10 (dez) dias. As publica��es do edital ser�o efetuadas em jornal de grande circula��o no local de realiza��o do leil�o e no local de situa��o do Im�vel, elegendo-se, preferencialmente, jornais editados naquelas localidades.</p>
				<p align='justify'><b>					
										Crit�rio para venda do Im�vel</b></p>
				<p align='justify'>						
										16.4. A venda do Im�vel em qualquer dos p�blicos leil�es far-se-� sempre pelo crit�rio de maior lance, respeitado, todavia, o pre�o m�nimo de venda estabelecido conforme as cl�usulas 16.1 e 16.2.1.</p>
				<p align='justify'>						
										16.4.1. Realizada a venda do Im�vel em qualquer dos p�blicos leil�es, a PREVI, na qualidade de propriet�rio pleno e possuidor(a,es) indireto do Im�vel, transferir� ao licitante vencedor toda a propriedade e posse que sobre ele exerce, bem como receber� o pre�o pago pelo licitante vencedor, e utilizar� os valores para liquidar as Obriga��es Garantidas acrescidas dos juros e encargos morat�rios at� a data da realiza��o do leil�o em que houve a venda do Im�vel e do valor das obriga��es ".$dev3." descritas nos itens �i� a �x� da cl�usula 16.2.1, que se encontrem vencidas e n�o pagas at� a data da efetiva��o da venda do Im�vel.</p>
				<p align='justify'><b>						
										Restitui��o de quantias ".$dev4." e indeniza��o por benfeitorias</b></p>
				<p align='justify'>						
										16.5. Ap�s liquidadas as obriga��es ".$dev3.", mencionadas na cl�usula 16.4, a PREVI restituir-lhe-� eventual saldo que sobejar do pre�o recebido pela venda do Im�vel no prazo de at� 5 (cinco) dias �teis ap�s o efetivo pagamento pelo licitante vencedor, por meio de cr�dito na conta-corrente mantida ".$dev5." junto ao Banco do Brasil S.A. ou por meio de cheque administrativo, nominativo e intransfer�vel, emitido em nome ".$dev3.". Nos termos do �4� ao art. 27 da Lei n� 9.514/97, considerar-se-� inclu�da no valor restitu�do ".$dev4." a indeniza��o pelas benfeitorias, �teis, necess�rias ou voluptu�rias por ele realizadas no Im�vel, n�o podendo ".$dev2." reclamar o pagamento de qualquer outra quantia, a qualquer t�tulo.</p>
				<p align='justify'>						
										16.5.1. Caso n�o haja saldo a ser restitu�do, n�o ser� devida ".$dev4.", nos termos daquela disposi��o legal, qualquer indeniza��o pelas benfeitorias, �teis, necess�rias ou voluptu�rias por ele realizadas no Im�vel.</p>
				<p align='justify'><b>						
										Extin��o da d�vida e indeniza��o por benfeitorias</b></p>
				<p align='justify'>						
										16.6. Caso no segundo p�blico leil�o n�o haja licitantes ou n�o seja oferecido lan�e que equivalha, pelo menos, ao valor m�nimo estipulado na cl�usula 16.2.1, considerar-se-�o extintas as obriga��es ".$dev3." decorrentes deste Contrato, exonerando-se a PREVI da obriga��o de vender o Im�vel por meio de p�blico leil�o.</p>
				<p align='justify'>						
										16.6.1. Ocorrendo a extin��o da d�vida, no prazo de 5 (cinco) dias a contar da realiza��o do segundo leil�o, a PREVI entregar� ".$dev4." o competente termo de quita��o de suas obriga��es decorrentes deste Contrato, aplicando-se, nessa hip�tese, o disposto na cl�usula 14.</p>
				<p align='justify'>						
										16.6.2. Na hip�tese prevista na cl�usula 16.6, a PREVI n�o ser� obrigada a restituir ".$dev4." qualquer quantia, a qualquer t�tulo, nem obrigada a indeniz�-lo pelas benfeitorias, �teis, necess�rias ou voluptu�rias por ele realizadas no Im�vel.</p>
				<p align='justify'><b>						
										Pagamento do saldo devedor restante</b></p>
				<p align='justify'>						
										16.7. Caso, na hip�tese de venda do Im�vel no primeiro p�blico leil�o, o valor apurado n�o seja suficiente para liquidar as Obriga��es Garantidas acrescidas dos juros e encargos morat�rios e do valor das obriga��es ".$dev3." descritas nos itens �i� a �x� da cl�usula 16.2.1, ".$dev2." permanecer�(�o) respons�vel pelo total e completo pagamento de suas obriga��es decorrentes deste Contrato, o qual dever� ser realizado 24 (vinte e quatro) horas ap�s a venda do Im�vel.</p>
				<p align='justify'><b>						
										Presta��o de contas</b></p>
				<p align='justify'>						
										16.8. Caso haja a venda do Im�vel em qualquer dos dois p�blicos leil�es previstos na cl�usula 16, a PREVI manter�, em sua sede, � disposi��o ".$dev3.", a correspondente presta��o de contas pelo per�odo de 12 (doze) meses, contados da realiza��o do primeiro leil�o.</p>
				<p align='justify'><b>						
										CL�USULA 17 - CERTID�ES</b></p>
				<p align='justify'>						
										Conforme a natureza da personalidade jur�dica do(a,s) VENDEDOR(A,ES), neste ato s�o entregues as seguintes certid�es, ou prestadas as seguintes declara��es: a) se o(a,s) VENDEDOR(A,ES) for pessoa f�sica, declara n�o ser produtor rural, empregador, nem estar pessoalmente vinculado ao INSS, n�o estando sujeito � apresenta��o da CND-INSS, por n�o ser contribuinte desse �rg�o; b) se o(a,s) VENDEDOR(A,ES) for pessoa jur�dica, apresenta, neste ato, c�pia autenticada da Certid�o Negativa de D�bito � CND-INSS e da Certid�o Conjunta expedida pela Receita Federal do Brasil e pela Procuradoria-Geral da Fazenda Nacional, exceto se, conforme assinalado no pre�mbulo, estiver dispensado de tal apresenta��o por ser sociedade que explora, exclusivamente, atividade de compra e venda de im�veis, loca��o, desmembramento ou loteamento de terrenos, incorpora��o imobili�ria ou constru��o de im�veis, destinados � venda e que o Im�vel integra contabilmente seu ativo circulante, jamais tendo constado do seu ativo permanente, o que declara sob responsabilidade civil e criminal.  </p>
				<p align='justify'><b>						
										Certid�es de a��es reais e reipersecut�rias</b></p>
				<p align='justify'>						
										17.1. Para lavratura deste Contrato foram apresentadas certid�es de a��es reais e pessoais reipersecut�rias, relativas ao Im�vel e a de �nus reais, expedidas pelo Cart�rio de Registro de Im�veis competente, bem como os demais documentos cuja apresenta��o � exigida por Lei, os quais se encontram identificados no Decreto n� 93.240/86, ficando os mesmos arquivados junto a PREVI, em face da obriga��o de seus arquivamentos prevista na Lei n� 4.380/64, e em conformidade com o disposto no � 3� do artigo 1� da Lei n� 7.433/85.</p>
				<p align='justify'><b>						
										CL�USULA 18 - DECLARA��ES D".$dev6."</b></p>
				<p align='justify'>						
										".$dev6." declara(m) expressamente, sob pena de responsabilidade civil e penal, que: i) sendo pessoa f�sica, n�o est� vinculado � Previd�ncia Social, como empregador, e que n�o � contribuinte da mesma, na qualidade de produtor rural, n�o estando, portanto, sujeito �s obriga��es previdenci�rias abrangidas pelo INSS � Instituto Nacional do Seguro Social; ii) na hip�tese de estar vinculado e/ou ser contribuinte da Previd�ncia Social, ser� apresentada, por ocasi�o do registro deste contrato junto ao Cart�rio de Registro de Im�veis competente, a necess�ria Certid�o Negativa de D�bito expedida pelo INSS; iii) n�o tem nenhuma responsabilidade tutelar, curatelar ou testament�ria; iv) vistoriou o Im�vel e o encontrou em perfeita ordem e condi��es de habitabilidade; v) ".$dev6." declara, para todos os fins e sob as penas da lei, que n�o contraiu anteriormente nenhum financiamento imobili�rio junto � PREVI; vi) ".$dev6." declara(m) n�o estar respondendo a inqu�rito administrativo, inqu�rito judicial trabalhista ou estar em aviso pr�vio, at� a presente data.</p>
				<p align='justify'><b>									
										Declara��es concernentes � utiliza��o do Fundo de Garantia por Tempo de Servi�o � FGTS</b></p>
				<p align='justify'>						
										18.1. Caso parte do pre�o de compra do Im�vel seja pago mediante a utiliza��o de recursos do Fundo de Garantia por Tempo de Servi�o � FGTS ".$dev3.", este(a,s) declara(m), sob as penas da lei, que:</p>
				<p align='justify'>						
										a) utilizar� o Im�vel exclusivamente para resid�ncia pr�pria;<br>b) o Im�vel est� localizado: i) no munic�pio onde exerce a sua ocupa��o principal, em munic�pio a esse lim�trofe ou integrante da respectiva regi�o metropolitana; ou, ainda, ii) no munic�pio onde resida h�, pelo menos, um ano;<br>c) n�o � propriet�rio ou promiss�rio comprador de qualquer outro im�vel residencial conclu�do: i) sito em qualquer parte do territ�rio nacional, cuja aquisi��o ou constru��o tenha sido financiada no �mbito do Sistema Financeiro da Habita��o, em qualquer parte do territ�rio nacional; ii) sito no munic�pio onde exer�a sua ocupa��o principal, nos munic�pios a esse lim�trofes ou na respectiva regi�o metropolitana; ou iii) sito no atual munic�pio de sua resid�ncia;<br>d) n�o � usufrutu�rio do Im�vel;e) n�o doou qualquer im�vel residencial a pessoa: i) que esteja sujeita ao seu p�trio poder, ou ii) sobre a qual exer�a tutela ou curatela;<br>f) tem conhecimento de que lhe � vedado: i) utilizar o FGTS para aquisi��o de im�vel que n�o se destine � sua moradia pr�pria; ii) utilizar o FGTS para aquisi��o de im�vel comercial ou rural; iii) utilizar o FGTS para aquisi��o de lotes ou terrenos; iv) utilizar o FGTS para aquisi��o de im�vel gravado com cl�usula que dificulte ou comprometa a sua livre comercializa��o; v) utilizar o FGTS para aquisi��o de im�vel residencial conclu�do que n�o apresente condi��es de habitabilidade (bom estado de conserva��o); vi) utilizar o FGTS para aquisi��o de im�vel que tenha sido adquirido pelo(a,s) VENDEDOR(A,ES) com a utiliza��o do seu FGTS, h� menos de 3 (tr�s) anos.</p>
				<p align='justify'>
										18.1.1. Para os fins da al�nea �c�, acima, ".$dev2." n�o ser�(�o) considerado(a,s) propriet�rio(a,s) ou promiss�rio(a,s) comprador(a,es) de im�vel residencial caso detenha fra��o ideal igual ou inferior a 40% (quarenta por cento) de referido im�vel.</p>
				<p align='justify'>						
										18.1.2. ".$dev6." declara(m) que tem conhecimento de que o Im�vel s� poder� ser alienado a outro comprador que pretenda pagar o pre�o com a utiliza��o de seu FGTS ap�s 3 (tr�s) anos contados do registro da presente venda e compra.</p>
				<p align='justify'>						
										18.1.3. ".$dev6.", neste ato, obriga(m)-se a respeitar(em) e observar(em) as veda��es e restri��es estabelecidas na letra �f� da cl�usula 18.1.</p>
				<p align='justify'><b>									
										CL�USULA 19 � DISPOSI��ES GERAIS</b></p>
				<p align='justify'>						
										Caso no Item I - �COMPRADOR(A,ES)�, constante do pre�mbulo deste Contrato, figurem duas pessoas, ambas declaram-se solidariamente respons�veis por todas as obriga��es decorrentes do Financiamento e descritas neste Contrato, entendendo-se as refer�ncias feitas neste Contrato ao �DEVEDOR(A,ES)� como abrangendo ambas as referidas pessoas, as quais, m�tua e reciprocamente, constituem-se procuradoras uma(a,s) da(a,s) outra(a,s) para fins de receber cita��es, intima��es e interpela��es de qualquer procedimento, judicial ou extrajudicial, decorrente deste Contrato, inclusive as intima��es mencionadas na cl�usula 15, de modo que, realizada a cita��o ou intima��o, na pessoa de qualquer uma delas, estar� completo o quadro citat�rio.</p>
				<p align='justify'><b>									
										Nova��o, altera��o ou ren�ncia</b></p>
				<p align='justify'>
										19.1. Qualquer pagamento de principal, juros ou demais encargos que sejam efetuados fora dos prazos estabelecidos neste Contrato e ainda assim recebidos pela PREVI, bem como o n�o-exerc�cio imediato de qualquer direito de que a PREVI seja titular em decorr�ncia deste Contrato ou da lei, inclusive a efetiva��o da intima��o mencionada na cl�usula 15, ser�o considerados mera toler�ncia. Qualquer nova��o ou altera��o deste Contrato apenas ser� v�lida mediante aditivo a este instrumento.</p>
				<p align='justify'><b>						
										Despesas deste Contrato e de registro</b></p>
				<p align='justify'>						
										19.2. ".$dev6." responde(m) por todas as despesas decorrentes da presente compra e venda e do financiamento com aliena��o fiduci�ria em garantia, inclusive aquelas relativas a emolumentos e despachante para obten��o das certid�es dos distribuidores forenses, da municipalidade e de propriedade, as necess�rias � sua efetiva��o e as demais que se lhe seguirem, inclusive as relativas a emolumentos e custas de Servi�o de Notas e de Servi�o de Registro de Im�veis, de quita��es fiscais e qualquer tributo devido sobre a opera��o, que venha a ser cobrado ou criado.</p>
				<p align='justify'>						
										19.2.1. Correr�o por conta ".$dev3." todas as despesas decorrentes do presente Contrato e de todos os registros e averba��es a ele correspondentes, principalmente os referentes ao registro da presente compra e venda do Im�vel e da garantia de aliena��o fiduci�ria ora constitu�da, bem como aquelas decorrentes de qualquer ato ou neg�cio jur�dico praticado com base neste Contrato.</p>
				<p align='justify'><b>						
										Ato jur�dico perfeito</b></p>
				<p align='justify'>						
										19.3. As Partes convencionam, como condi��o essencial deste Contrato, que, em face do princ�pio constitucional do respeito ao direito adquirido e ao ato jur�dico perfeito, n�o se aplicar� a este Contrato qualquer norma superveniente de congelamento ou defla��o, total ou parcial, do Saldo Devedor ou do valor de cada presta��o.</p>
				<p align='justify'>						
										19.3.1 Na hip�tese de a PREVI aceitar temporariamente, por mera liberalidade e sem que tal fato caracterize nova��o, o congelamento ou defla��o do valor de algumas presta��es, fica ajustado como condi��o do presente neg�cio que: i) o Saldo Devedor continuar� sendo atualizado, nos termos da cl�usula 4; e ii) a diferen�a entre o valor real de cada parcela e o valor a menor pago ".$dev5." ser� cobrada pela PREVI t�o logo se encerre, de modo direto ou indireto, o congelamento ou defla��o.</p>
				<p align='justify'>						
										19.3.2. Em face do aven�ado, toda e qualquer quita��o conferida pela PREVI acha-se condicionada � apura��o posterior de eventual saldo de responsabilidade ".$dev3.", ainda que tal ressalva n�o conste expressamente do respectivo recibo ou boleto banc�rio.</p>
				<p align='justify'><b>									
										Altera��o de domic�lio e Estado Civil</b></p>
				<p align='justify'>						
										19.4. ".$dev6." obriga(m)-se a comunicar � PREVI, imediatamente, qualquer altera��o de seu estado civil, bem como qualquer altera��o de seu domic�lio ou endere�o para correspond�ncia.</p>
				<p align='justify'><b>						
										Declara��o<br>CL�USULA 20 - REGULAMENTO DA CARTEIRA IMOBILI�RIA DA PREVI<b></p>
				<p align='justify'>						
										Aplica-se subsidiariamente a este Contrato as regras do Regulamento  vigente da Carteira Imobili�ria da PREVI (CARIM2007), ao qual ".$dev2." declara expresso conhecimento e concord�ncia.</p>
				<p align='justify'><b>									
										CL�USULA 21 - AUTORIZA��O PARA REGISTRO</b></p>
				<p align='justify'>						
										As Partes declaram aceitar o presente Contrato em todas as suas cl�usulas, termos e condi��es, autorizando o Sr. Oficial do Cart�rio de Registro de Im�veis competente a proceder quaisquer registros ou averba��es que se fizerem necess�rios ao seu fiel cumprimento, inclusive o registro da propriedade resol�vel sobre o Im�vel em favor do PREVI.</p>
				<p align='justify'><b>						
										CL�USULA 22 - ELEI��O DE FORO</b></p>
				<p align='justify'>						
										Para dirimir quaisquer d�vidas que porventura surjam em virtude do presente instrumento, as partes elegem o Foro Central da Capital do Estado do Rio de Janeiro, facultado ao autor da a��o optar pelo foro de situa��o do im�vel.</p>
				<br>
				<p align='justify'>								
										E, por estarem assim justos e contratados, assinam o presente em 03 (tr�s) vias de igual teor e valor, na presen�a de 02 (duas) testemunhas.</p>
														<br>
				<p align='justify'>								
										GARANTIA DE ALIENA��O FIDUCI�RIA E OUTRAS AVEN�AS N� ".$id_lstn.".</p>
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
					<td width='364' align='center'><b>P.P. CAIXA DE PREVID�NCIA DOS FUNCION�RIOS DO BANCO DO BRASIL - $nome_procurador</b></td>
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
	
		//$pdf->Cell(0,5,'C�DULA DE CR�DITO BANC�RIO',0,2,'C');
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
