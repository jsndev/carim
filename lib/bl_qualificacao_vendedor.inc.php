<?php
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
	//Informações de Procurador
	$query = "SELECT * FROM vendprocurador WHERE cod_ppst='".$aProposta["cod_ppst"]."'";
	$result =mysql_query($query);
	$reg=mysql_fetch_array($result,MYSQL_ASSOC);
	$flgproc_v=$reg['FLGPROC_VPROC'];
	if($flgproc_v=='S')
	{
		$procurador_v=" neste ato representado(a,s) por seu(sua,s) bastante procurador(a,res) ".$reg['PROC_VPROC'].".";
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
	$query = "SELECT * FROM vendedor WHERE cod_ppst='".$aProposta["cod_ppst"]."'";
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
		$vend_cep[$a]		= $utils->formataCep($registro['CEP_VEND']);
		$vend_conta[$a]		= $registro['NRCC_VEND'];
		$vend_digito[$a]	= $registro['DVCC_VEND'];
		$vend_agencia[$a]	= $registro['NRAG_VEND'];
		$vend_bairro[$a]	= $registro['COD_BAIRRO'];
		$bairro_vend[$a]	= $registro['BAIRRO_VEND'];

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
		if($bairro_vend[$a]==''){
		$qrvbairro="SELECT nome_bairro FROM bairro WHERE cod_bairro = '".$vend_bairro[$a]."' ";
		$rsvbairro =mysql_query($qrvbairro);
		$regvbairro = mysql_fetch_array($rsvbairro, MYSQL_ASSOC);
		
		$v_bairro[$a]= $regvbairro['nome_bairro'];
		}else{
		$v_bairro[$a]= $bairro_vend[$a];
		}
		
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
				$vendf_cpf[$a]			= $utils->formataCPF($regvf['CPF_VFISICA']);
				$vendf_sex[$a]			= $regvf['SEXO_VFISICA'];		
				$vendf_natur[$a]		= $regvf['NATUR_VFISICA'];
				$vendf_nrrg[$a]			= $regvf['NRRG_VFISICA'];
				$vendf_orgrg[$a]		= $regvf['ORGRG_VFISICA'];
				$vendf_dtrg[$a]			= $utils->formataDataBRA($regvf['DTRG_VFISICA']);
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
				$vconj_dtcasamento[$a]		= $utils->formataDataBRA($regvfc['DTCASAMENTO_VFCJ']);
				$vconj_nome[$a]				= $regvfc['NOME_VFCJ'];
				$vconj_nacional[$a]			= $regvfc['COD_PAIS'];
				$vconj_civil[$a]			= $regvfc['COD_ESTCIV'];
				$vconj_nrrg[$a]				= $regvfc['NRRG_VFCJ'];
				$vconj_dtrg[$a]				= $utils->formataDataBRA($regvfc['DTRG_VFCJ']);
				$vconj_orgrg[$a]			= $regvfc['ORGRG_VFCJ'];
				$vconj_cpf[$a]				= $utils->formataCPF($regvfc['CPF_PCCJ']);
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
				$vcpacto_data[$a]		= $utils->formataDataBRA($regvfcp['DATA_VCPA']);
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
							
							if($vconj_regime[$a]==4)//Regime de participação final nos Aquestos
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a]." ,casados pelo regime de Participação Final nos Aquestos, em ".$vconj_dtcasamento[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores do Documento de Identificação n°(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ele ".$v_nacional[$a].", ela ".$vc_nacional[$a].", casados pelo regime de Participação Final nos Aquestos, em ".$vconj_dtcasamento[$a].", ele ".$v_profissao[$a].", ela ".$vconj_cargoemp[$a].", portadores do Documento de Identificação n°(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
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
							
														if($vconj_regime[$a]==4)//Regime de participação final nos Aquestos
							{
								if($vendf_nacional[$a]==$vconj_nacional[$a])
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ".$vnacional[$a]." ,casados pelo regime de Participação Final nos Aquestos, em ".$vconj_dtcasamento[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores do Documento de Identificação n°(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
								}else
								{
													$vendedor .="<b>".maiusculo($vend_nome[$a])." e s/m ".maiusculo($vconj_nome[$a])."</b>, ela ".$v_nacional[$a].", ele ".$vc_nacional[$a].", casados pelo regime de Participação Final nos Aquestos, em ".$vconj_dtcasamento[$a].", ela ".$v_profissao[$a].", ele ".$vconj_cargoemp[$a].", portadores do Documento de Identificação n°(s) ".$vendf_nrrg[$a].", emitido por ".maiusculo($vendf_orgrg[$a])." em ".$vendf_dtrg[$a]." e ".$vconj_nrrg[$a].", emitido por ".maiusculo($vconj_orgrg[$a])." em ".$vconj_dtrg[$a].", respectivamente, inscritos no CPF/MF sob os nrs. ".$vendf_cpf[$a]." e ".$vconj_cpf[$a].", residentes e domiciliados no(a) ".$v_endereco[$a].$limite_v;
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
				$vendj_cnpj[$a]				= $utils->formataCnpj($regvj['CNPJ_VJUR']);
				$vendj_versaoestat[$a]		= $regvj['VERSAOESTAT_VJUR'];
				$vendj_dtestatv[$a]			= $utils->formataDataBRA($regvj['DTESTAT_VJUR']);
				$vendj_locestat[$a]			= $regvj['LOCESTAT_VJUR'];
				$vendj_regestat[$a]			= $regvj['NRREGESTAT_VJUR'];
				$vendj_regdata[$a]			= $utils->formataDataBRA($regvj['DTREGESTAT_VJUR']);
				$vendj_tiposoc[$a]			= $regvj['TIPO_SOC_VJUR'];
				$vendj_tiporep[$a]			= $regvj['TIPO_REP_VJUR'];
			
			// Informações dos Sócios do Vendedor PJ
			$qrvjs = "SELECT * FROM vendjursocio WHERE cod_ppst='".$cod_ppst."' and cod_vend='".$cod_vend[$a]."'";
			$rsvjs =mysql_query($qrvjs);
			$b=1;
			while($regvjs = mysql_fetch_array($rsvjs, MYSQL_ASSOC))
			{
				$vjsocio_nome[$a][$b]		= $regvjs['NOME_VJSOC'];
				$vjsocio_cpf[$a][$b]		= $utils->formataCPF($regvjs['CPF_VJSOC']);
				$vjsocio_sexo[$a][$b]		= $regvjs['SEXO_VJSOC'];
				$vjsocio_nacional[$a][$b]	= $regvjs['COD_PAIS'];
				$vjsocio_cidade[$a][$b]		= $regvjs['COD_MUNICIPIO'];
				$vjsocio_endereco[$a][$b]	= $regvjs['ENDERECO_VJSOC'];
				$vjsocio_numero[$a][$b]		= $regvjs['NRENDERECO_VJSOC'];
				$vjsocio_complemento[$a][$b]= $regvjs['CPENDERECO_VJSOC'];
				$vjsocio_logradouro[$a][$b]= $regvjs['COD_LOGR'];
				$vjsocio_bairro[$a][$b]= $regvjs['COD_BAIRRO'];
				$vjsocio_bairro2[$a][$b]= $regvjs['BAIRRO_VJSOC'];
				
				$vjsocio_estciv[$a][$b]		= $regvjs['COD_ESTCIV'];
				$vjsocio_nrrg[$a][$b]		= $regvjs['NRRG_VJSOC'];
				$vjsocio_orgrg[$a][$b]		= $regvjs['ORGRG_VJSOC'];
				$vjsocio_dtrg[$a][$b]		= $utils->formataDataBRA($regvjs['DTRG_VJSOC']);
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
				
				//Endereço do Vendedor
				$qrslograd="SELECT desc_logr FROM logradouro WHERE cod_logr = '".$vjsocio_logradouro[$a][$b]."' ";
				$rsslograd =mysql_query($qrslograd);
				$regslograd = mysql_fetch_array($rsslograd, MYSQL_ASSOC);
					$vjs_lograd[$a][$b]= $regslograd['desc_logr'];
				
				if($vjsocio_bairro2[$a][$b]==''){
					$qrsbairro="SELECT nome_bairro FROM bairro WHERE cod_bairro = '".$vjsocio_bairro[$a][$b]."' ";
					$rssbairro =mysql_query($qrsbairro);
					$regsbairro = mysql_fetch_array($rssbairro, MYSQL_ASSOC);
					
					$vjs_bairro[$a][$b]= $regsbairro['nome_bairro'];
				}else{
				 	$vjs_bairro[$a][$b]=$vjsocio_bairro2[$a][$b];
				}
				
					//COMPLEMENTO;
				if($vjsocio_complemento[$a][$b]!=''){
					$vjsocio_complemento[$a][$b]= $vjsocio_complemento[$a][$b].", ";
				}else{
					$vjsocio_complemento[$a][$b]="";
				}
	
			$vjs_endereco[$a][$b]=$vjs_lograd[$a][$b]." ".$vjsocio_endereco[$a][$b].", nr. ".$vjsocio_numero[$a][$b].", ".$vjsocio_complemento[$a][$b].ucwords(strtolower($vjs_bairro[$a][$b])).", ".ucwords(strtolower($vjs_cidade[$a][$b])).", ".$vjs_uf[$a][$b];	
			
			
			
				//Qualificação de Sócio Masculino
				if($vjsocio_sexo[$a][$b]=='M')
				{
					$ident="seu sócio";
					if($vjsocio_estciv[$a][$b]==1) $vjs_estciv[$a][$b]='solteiro';
					elseif($vjsocio_estciv[$a][$b]==2) $vjs_estciv[$a][$b]='casado';
					elseif($vjsocio_estciv[$a][$b]==3) $vjs_estciv[$a][$b]='separado judicialmente';
					elseif($vjsocio_estciv[$a][$b]==4) $vjs_estciv[$a][$b]='divorciado';
					elseif($vjsocio_estciv[$a][$b]==5) $vjs_estciv[$a][$b]='viúvo';

					$socio .="<b>Sr. ".maiusculo($vjsocio_nome[$a][$b])."</b>, ".$vjs_nacional[$a][$b].", ".$vjs_estciv[$a][$b].", ".$vjsocio_prof[$a][$b].", portador do Documento de Identificação nº. ".$vjsocio_nrrg[$a][$b].", emitido por ".$vjsocio_orgrg[$a][$b]." em ".$vjsocio_dtrg[$a][$b].", inscrito no CPF/MF sob nº. ".$vjsocio_cpf[$a][$b].", residente e domiciliado em ".$vjs_endereco[$a][$b]."; "; 
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
					
					$socio .="<b>Sra. ".maiusculo($vjsocio_nome[$a][$b])."</b>, ".$vjs_nacional[$a][$b].", ".$vjs_estciv[$a][$b].", ".$vjsocio_prof[$a][$b].", portadora do Documento de Identificação nº. ".$vjsocio_nrrg[$a][$b].", emitido por ".$vjsocio_orgrg[$a][$b]." em ".$vjsocio_dtrg[$a][$b].", inscrita no CPF/MF sob nº. ".$vjsocio_cpf[$a][$b].", residente e domiciliada em ".$vjs_endereco[$a][$b];
					$assinatura_socio .="<br>".maiusculo($vjsocio_nome[$a][$b])." - ".$vjsocio_cpf[$a][$b]."";
				}
				$b++;
			}
			//§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§
			#__________________________ QUALIFICAÇÃO(A,s) VENDEDOR(A,ES) PESSOA JURÍDICA _________________________#
			
			//§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§
			
		if($vendj_tiporep[$a]=='S'){
			$representante="representado por ".$ident." ".$socio;
			
		}	
		if($vendj_tiporep[$a]=='P'){
			$representante=$procurador_v;
			
		}	
//echo $representante;

		if($vendj_tiposoc[$a]=='LTDA'){
			if($vendj_versaoestat[$a]!=''){
				$vendedor .= "<b>".maiusculo($vend_nome[$a])."</b>, com sede e foro na(o) ".$v_endereco[$a].", inscrita no CNPJ sob nº. ".$vendj_cnpj[$a].", com seu Contrato Social/consolida&ccedil;&atilde;o e ".$vendj_versaoestat[$a]." Alteração de Contrato Social, datada de ".$vendj_dtestatv[$a].", registrada na(o) ".$vendj_locestat[$a]." sob nº. ".$vendj_regestat[$a].", doravante denominado(a) simplesmente VENDEDOR(A,ES),  ".$representante;
			}else{
				$vendedor .= "<b>".maiusculo($vend_nome[$a])."</b>, com sede e foro na(o) ".$v_endereco[$a].", inscrita no CNPJ sob nº. ".$vendj_cnpj[$a].", com seu Contrato Social/consolida&ccedil;&atilde;o registrada na(o) ".$vendj_locestat[$a]." sob nº. ".$vendj_regestat[$a].", doravante denominado(a) simplesmente VENDEDOR(A,ES),  ".$representante;
			}
		}
		if($vendj_tiposoc[$a]=='SA'){
		
			if($vendj_versaoestat[$a]!=''){
				$vendedor .= "<b>".maiusculo($vend_nome[$a])."</b>, com sede e foro na(o) ".$v_endereco[$a].", inscrita no CNPJ sob nº. ".$vendj_cnpj[$a].", com Estatuto Social consolidado na Ata da  ".$vendj_versaoestat[$a]." Assembléia Geral Extraordinária realizada em ".$vendj_dtestatv[$a]." e registrada na(o) ".$vendj_locestat[$a]." sob nº. ".$vendj_regestat[$a].", em data de ".$vendj_regdata[$a].", doravante denominado(a) simplesmente VENDEDOR(A,ES),  ".$representante;
			}else{
				$vendedor .= "<b>".maiusculo($vend_nome[$a])."</b>, com sede e foro na(o) ".$v_endereco[$a].", inscrita no CNPJ sob nº. ".$vendj_cnpj[$a].", com seu Contrato Social/consolida&ccedil;&atilde;o registrada na(o) ".$vendj_locestat[$a]." sob nº. ".$vendj_regestat[$a].", doravante denominado(a) simplesmente VENDEDOR(A,ES),  ".$representante;
			}
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
	
	
?>
		<input type="hidden" name="qualificacao_vend" id="qualificacao_vend" value="<?php echo $vendedor;?>">