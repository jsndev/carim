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
<br><br><strong><p align="center">ROTEIRO DE FINANCIAMENTO IMOBILIÁRIO</p><br>
</strong><br>
<br>
<a name="1"></a>
&nbsp;&nbsp;&nbsp;&nbsp;<a href="documentos.php?doc=1#1"><strong>1ª FASE – Análise de Crédito do Comprador</strong></a><br>
<? if(@$_GET['doc']==1){
?>
	<br>
&nbsp;&nbsp;&nbsp;&nbsp;Documentos básicos necessários:<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;1.	Cópia simples dos três últimos comprovantes de rendimento (aluguel, aposentadoria, holerite);<br>
&nbsp;&nbsp;&nbsp;&nbsp;2.	Cópia simples da declaração de Imposto de Renda Pessoa Física completa do último exercício, inclusive da página de protocolo de entrega;<br>
&nbsp;&nbsp;&nbsp;&nbsp;3.	Caso o Comprador seja profissional liberal, autônomo, sócio de empresa, cópia simples do extrato dos últimos seis meses da conta-corrente Pessoa Física e da conta-corrente Pessoa Jurídica, em que realiza maior movimentação bancária;<br>
&nbsp;&nbsp;&nbsp;&nbsp;4.	Caso o Comprador seja sócio de empresa, cópia simples do Contrato/Estatuto Social e da última alteração contratual da empresa, em que conste a participação do Comprador, bem como cópia simples de documento que comprove o faturamento dos últimos seis meses da empresa. Caso se trate de empresa prestadora de serviços, cópia simples dos contratos de prestação de serviços vigentes.<br>
&nbsp;&nbsp;&nbsp;&nbsp;5.	Todos os Compradores deverão entregar os documentos mencionados, mesmo que um deles não comprometa sua renda para a obtenção do financiamento.<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;<em>O Comprador é a pessoa física interessada em adquirir imóvel e financiar parte do valor junto a um agente financeiro. Se esta for casada ou viver em união estável, seu cônjuge ou companheiro também deverá configurar no contrato de compra e venda com financiamento como Comprador, a não ser que o casamento seja constituído em regime de separação total de bens.</em>
	<br><br><br>
<?
}?><br>
<a name="2"></a>
&nbsp;&nbsp;&nbsp;&nbsp;<a href="documentos.php?doc=2#2"><strong>2ª FASE – Avaliação de Garantia</strong></a><br>
<? if(@$_GET['doc']==2){
?>
	<br>
&nbsp;&nbsp;&nbsp;&nbsp;Documentos básicos do Imóvel:<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;1.	Cópia autenticada da certidão atualizada da matrícula do Imóvel, emitida pelo Cartório de Registro de Imóveis competente;<br>
&nbsp;&nbsp;&nbsp;&nbsp;2.	Cópia simples do carnê ou do demonstrativo do IPTU do exercício em curso, que contenha o endereço do imóvel, seu valor venal e as áreas do terreno e do imóvel. Caso se trate de imóvel novo e ainda não tenha sido emitido carnê de IPTU, apresentar a planta do imóvel aprovada na Prefeitura;<br>
&nbsp;&nbsp;&nbsp;&nbsp;3.	Caso se trate de imóvel sob o regime da enfiteuse, cópia autenticada da Certidão Autorizativa de Transferência, emitida pelo Setor de Patrimônio da União, que pode ser solicitada pelo site www.spu.planejamento.gov.br e ficha de cadastro e cópia simples dos comprovantes de pagamento do laudêmio e do foro;<br>
&nbsp;&nbsp;&nbsp;&nbsp;4.	Cópia simples dos comprovantes de pagamento de todas as parcelas do IPTU em exercício, Certidão Negativa de Tributos Municipais.<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Documentação básica do Comprador:<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;1.  Cópia simples do RG e do CPF do Comprador;<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;<em>O processo de Avaliação de Garantia – Avaliação física e documental da garantia, só inicia com o recebimento de todos os documentos solicitados neste roteiro e com a comprovação do pagamento do boleto da Avaliação de Garantia. A falta de qualquer documento poderá acarretar em atraso na contratação.</em><br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Observações Importantes:<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;•	Mesmo que ainda não tenha sido emitido o carnê de IPTU, a construção do imóvel deverá estar averbada no Cartório de Registro de Imóveis competente;<br>
&nbsp;&nbsp;&nbsp;&nbsp;•	O imóvel deve estar em bom estado de conservação e habitabilidade;<br>
&nbsp;&nbsp;&nbsp;&nbsp;•	Não deve existir qualquer ônus (hipoteca, alienação fiduciária, penhora, processo de partilha/inventário, etc.) constituído sobre o imóvel;<br>
&nbsp;&nbsp;&nbsp;&nbsp;•	Se houver qualquer divergência na documentação (IPTU/matrícula), como, por exemplo, divergência de áreas, será necessário providenciar a regularização;<br>
&nbsp;&nbsp;&nbsp;&nbsp;•	Se o imóvel for uma casa e o Comprador/Vendedor possuir sua planta, esta deverá estar disponível no ato da vistoria do imóvel.<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;<em>Comprovante do estado civil: (i) solteiro: Declaração assinada; (ii) casado: cópia simples da certidão de casamento. Se tiver sido realizado pacto antenupcial, cópia simples do pacto registrado no Cartório de Registro de Imóveis; (iii) desquitado, separado ou divorciado: cópia simples da certidão de casamento com averbação do fato; (iv) viúvo: cópia simples da certidão de casamento com averbação do óbito ou acompanhada de cópia simples da certidão de óbito; (v) união estável: assinar declaração correspondente.<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Obs. Quando houver 2º comprador/cônjuge, deverão ser anexados ao processo os mesmos documentos exigidos para o 1º comprador.<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;IMPORTANTE: Se qualquer Financiado for representado por procuração, esta deverá ser lavrada por instrumento público e deverá prever poderes específicos para: praticar todos os atos necessários para efetuar a operação de financiamento à aquisição de imóvel, tais como, estabelecer cláusulas, condições, preços, dar sinal, autorizar a constituição de gravame sobre o imóvel, confessar dívida, receber e dar quitação, assinar escrituras públicas ou particulares, contratos particulares ou cédulas de crédito bancário, contratar financiamento imobiliário, satisfazer toda e qualquer exigência referente à documentação necessária, transacionar por meio de qualquer agente financeiro, representar o outorgante perante qualquer repartição pública federal, estadual, municipal e autarquias, assinar contrato de cessão de direitos de créditos oriundos de financiamento imobiliário em nome do outorgante, na qualidade de anuente, como o objetivo de transferir o financiamento imobiliário de determinada instituição financeira para outra, à sua escolha, entre outros. A procuração deverá ser providenciada em duas vias, juntamente com uma cópia simples do RG e do CPF do procurador. Uma deve ser entregue junto com a documentação e a outra será utilizada no momento do Registro do Contrato.</em><br><br>

&nbsp;&nbsp;&nbsp;&nbsp;<strong>Para confecção da procuração, verificar o modelo sugerido pelo cartório a ser utilizado.</strong><br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Documentação do Vendedor (Pessoa Física):<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;1.	Cópia simples do RG e do CPF;<br>
&nbsp;&nbsp;&nbsp;&nbsp;2.	Comprovante do estado civil: (i) solteiro: declaração assinada; (ii) casado: cópia simples da certidão de casamento. Se tiver sido realizado pacto antenupcial, cópia simples do pacto registrado no Cartório de Registro de Imóveis; (iii) desquitado, separado ou divorciado: cópia simples da certidão de casamento com averbação do fato; (iv) viúvo: cópia simples da certidão de casamento com averbação do óbito ou acompanhada de cópia simples da certidão de óbito; (v) união estável: assinar declaração correspondente, <br>
&nbsp;&nbsp;&nbsp;&nbsp;3.	Cópias autenticadas ou originais das Certidões Negativas dos Distribuidores Cíveis, englobando executivos fiscais, tutela, curatela e interdição, abrangendo o período de dez anos. Caso o Vendedor resida em comarca diferente da qual se localiza o imóvel, apresentar as certidões de ambas as comarcas;<br>
&nbsp;&nbsp;&nbsp;&nbsp;4.	Cópia autenticada ou original da Certidão Negativa da Justiça Federal, abrangendo o período de dez anos. Caso o Vendedor resida em comarca diferente da qual se localiza o imóvel, apresentar as certidões de ambas as comarcas;<br>
&nbsp;&nbsp;&nbsp;&nbsp;5.	Cópia autenticada ou original da Certidão de Distribuições Trabalhistas de competência da Justiça do Trabalho;<br>
&nbsp;&nbsp;&nbsp;&nbsp;6.	Caso o Vendedor seja ascendente (pai/mãe) do Comprador, deverá entregar uma declaração, informando o número de filhos que possui e a qualificação de cada filho. Será necessário apresentar:<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(i)	Cópia simples do RG e do CPF de cada filho e respectivos cônjuges, <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(ii) comprovação de estado civil de cada filho e cônjuge, conforme o enquadramento feito em uma das opções constantes do terceiro item desta lista de documentos. Cada filho e respectivo cônjuge deverá apresentar declaração, com reconhecimento de firma, anuindo à venda do imóvel.<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Documentação do Vendedor (Pessoa Jurídica):<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;1.	Original ou cópia autenticada da Certidão Negativa de Débito do INSS atualizada. A certidão pode ser obtida nas Agências da Previdência Social ou pela internet, no site www.mpas.gov.br;<br>
&nbsp;&nbsp;&nbsp;&nbsp;2.	Original ou cópia autenticada da Certidão Conjunta da Receita Federal e Dívida Ativa da União atualizada. A certidão pode ser obtida na Secretaria da Receita Federal ou pela Internet, no site<br>
&nbsp;&nbsp;&nbsp;&nbsp;3.	www.receita.fazenda.gov.br;<br>
&nbsp;&nbsp;&nbsp;&nbsp;4.	Original ou cópia autenticada da Certidão de Regularidade do FGTS atualizada. A certidão pode ser obtida na Caixa Econômica Federal ou pela internet, no site www.caixa.gov.br;<br>
&nbsp;&nbsp;&nbsp;&nbsp;5.	Cópia simples do Estatuto/Contrato Social da empresa, com indicação da data e número do seu arquivamento na Junta Comercial;<br>
&nbsp;&nbsp;&nbsp;&nbsp;6.	Ata da Assembléia de eleição da atual Diretoria, registrada na Junta Comercial;<br>
&nbsp;&nbsp;&nbsp;&nbsp;7.	Carta informando a data da última alteração contratual ou estatutária com indicação dos representantes da empresa e qualificação destes para assinatura do contrato;<br>
&nbsp;&nbsp;&nbsp;&nbsp;8.	Caso o imóvel esteja localizado no Estado do Rio de Janeiro, cópia autenticada do Cartão CNPJ;<br>
&nbsp;&nbsp;&nbsp;&nbsp;9.	Cópias autenticadas ou Originais das Certidões Negativas dos Distribuidores Cíveis, englobando executivos fiscais, falência e recuperação judicial, pelo período de dez anos. Caso a sede do Vendedor esteja em comarca diferente da qual se localiza o imóvel, apresentar as certidões de ambas as comarcas;<br>
&nbsp;&nbsp;&nbsp;&nbsp;10.	Cópia autenticada ou original da Certidão Negativa da Justiça Federal, abrangendo o período de dez anos;<br>
&nbsp;&nbsp;&nbsp;&nbsp;11.	Cópia autenticada ou original da Certidão de Distribuições Trabalhistas de competência da Justiça do Trabalho, referente a ações em andamento, nominal à empresa e aos sócios.<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Todos os Vendedores deverão entregar os documentos acima mencionados.<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;<em>IMPORTANTE: Se qualquer Vendedor for representado por procuração, esta deverá ser lavrada por instrumento público e deverá prever poderes específicos para: vender o imóvel (descrição completa do imóvel), praticar todos os atos necessários para efetuar a operação, tais como, estabelecer cláusulas, condições, preços, receber sinal, dar domínio, direitos e ações, receber e dar quitação, assinar escrituras públicas ou particulares de venda e compra, contratos particulares ou cédulas de crédito bancário, satisfazer toda e qualquer exigência referente à documentação necessária, transacionar por meio de qualquer agente financeiro, representar o outorgante perante qualquer repartição pública federal, estadual, municipal e autarquias, entre outros. A procuração deverá ser providenciada em duas vias, juntamente com uma cópia simples do RG e do CPF do procurador. Uma deve ser entregue junto com a documentação e a outra será utilizada no momento do Registro do Contrato.
Para confecção da procuração, verificar o modelo sugerido pelo cartório a ser utilizado.</em>
	<br><br><br>
<?
}?><br>
<a name="3"></a>
&nbsp;&nbsp;&nbsp;&nbsp;<a href="documentos.php?doc=3#3"><strong>3ª FASE – Emissão do Contrato</strong></a><br>
<? if(@$_GET['doc']==3){
?>
	<br>
&nbsp;&nbsp;&nbsp;&nbsp;<em>Concluídas a 1ª e 2ª Fases, será emitido o instrumento que formalizará a venda e compra com o financiamento imobiliário solicitado, o qual será assinado pelo Comprador, Vendedor, Interveniente (se houver) e a instituição financeira, este na qualidade de agente financiador.<br>
&nbsp;&nbsp;&nbsp;&nbsp;Atenção! Juntamente com o instrumento, será entregue pelo agente financeiro uma correspondência contendo as principais informações sobre quais providências deverão ser tomadas para a conclusão da venda e compra e do processo de financiamento.</em><br><br>
&nbsp;&nbsp;&nbsp;&nbsp;CUSTOS ENVOLVIDOS NO PROCESSO DE CONTRATAÇÃO<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Tarifa de Avaliação de Garantia (Avaliação física e documental da garantia : o valor da tarifa está de acordo com cada instituição financeira.<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;<em>IMPORTANTE: Uma vez prestado o serviço de Avaliação de Garantia - Avaliação física e documental da garantia, a taxa correspondente não será ressarcida, sob qualquer hipótese. A prestação de tais serviços não tem qualquer relação com a obtenção ou não do financiamento imobiliário.
&nbsp;&nbsp;&nbsp;&nbsp;Após contratado o financiamento, outros custos serão devidos:<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(i) ITBI- Imposto Municipal de Transmissão de Bens;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(ii) custos cartorários de registro das operações de venda e compra e da garantia de alienação fiduciária;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(iii) tarifa de administração – cobrança e administração do contrato, cobrada mensalmente pelo agente financeiro e <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(iv) seguros de morte e invalidez permanente e danos físicos ao imóvel.</em><br><br>
&nbsp;&nbsp;&nbsp;&nbsp;INFORMAÇÕES ESSENCIAIS<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Seguros:<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;•	A contratação de seguro contra danos físicos ao Imóvel - DFI e seguro contra morte ou invalidez permanente MIP é obrigatória, conforme previsto na Lei nº 9.514/97 e na Resolução nº 3.347/06 do Conselho Monetário Nacional.<br>
&nbsp;&nbsp;&nbsp;&nbsp;•	O seguro MIP - (MORTE E INVALIDEZ PERMANENTE) garante a cobertura do saldo devedor do contrato em caso de morte ou invalidez permanente do(s) adquirente(s), de acordo com a composição de renda. A alíquota é incidente sobre o valor do Saldo Devedor e a idade do financiado mais idoso. Em caso de sinistro com o(s) adquirente(s), o seguro será indenizado na proporção da participação da renda de cada financiado.<br>
&nbsp;&nbsp;&nbsp;&nbsp;•	DFI (DANOS FÍSICOS NO IMÓVEL)- garante a cobertura das condições do imóvel em caso de sinistro (principais: incêndio; explosão; queda de raio; desmoronamento total; desmoronamento parcial; destruição ou desabamento de paredes, vigas ou outro elemento estrutural; ameaça de desmoronamento, devidamente comprovada; destelhamento; inundação ou alagamento), limitado ao valor da avaliação do imóvel. A alíquota é incidente sobre o valor da Avaliação do Imóvel. Em caso de sinistro com o o(s) adquirente(s), o seguro será indenizado na proporção da participação da renda de cada financiado.<br>
&nbsp;&nbsp;&nbsp;&nbsp;•	Para mais informações, consulte as apólices específicas.<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;INFORMAÇÕES PARA IMÓVEIS LOCALIZADOS NO ESTADO DO RIO DE JANEIRO:<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;<em>As certidões do Estado do Rio de Janeiro têm prazo de validade de trinta dias e deverão ser entregues ao Unibanco em cópias simples (as originais deverão ser apresentadas ao Cartório de Registro de Imóveis, no ato do registro do contrato de financiamento imobiliário);<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Informações sobre o Interveniente<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Interveniente Quitante: Caso o imóvel esteja hipotecado ou alienado a um terceiro, essa pessoa poderá figurar no instrumento de financiamento imobiliário como Interveniente Quitante;<br>
&nbsp;&nbsp;&nbsp;&nbsp;Interveniente Garantidor: pessoa, física ou jurídica, que dará um imóvel de sua propriedade em garantia do financiamento. Pode ser o próprio Comprador ou um terceiro. No caso de terceiro, este não será responsável pelo pagamento da prestação e nem pelo pagamento do principal financiado e não precisará compor renda com o Comprador. No entanto, é necessária a análise documental de suas certidões e documentos, bem como do imóvel dado em garantia.
Devedor solidário – responsável, juntamente com o Comprador, pelas obrigações pecuniárias por ele assumidas no contrato de financiamento. O devedor solidário é apenas garantidor da operação, pela qual responde com seus bens pessoais. Não se trata de comprador do imóvel, ou seja, a propriedade do imóvel não será transferida para seu nome. Em caso de inadimplemento do Comprador, o devedor solidário poderá ser acionado para quitar a dívida. Não, necessariamente, precisa compor renda, mas se compuser, deverá ser considerado para o cálculo do seguro MIP, mas é necessário que seja realizada a análise de seu crédito.
Quando o co-devedor for casado, seu cônjuge deverá assinar o contrato, salvo se o regime de casamento for por separação total ou legal de bens.<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Outras Informações<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Quando o Vendedor ou seu cônjuge/companheiro tiverem tido seus nomes alterados, em um período inferior a dez anos contados do pedido de financiamento imobiliário, as Certidões Negativas dos Distribuidores Cíveis e Justiça Federal deverão ser apresentadas tanto na atual denominação como na anterior.</em><br><br>
&nbsp;&nbsp;&nbsp;&nbsp;INFORMAÇÕES PARA A UTILIZAÇÃO DO FGTS<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;</em>A utilização do FGTS é disciplinada pela Caixa Econômica Federal - CEF, por meio do FGTS - Manual da Moradia Própria, na forma da Lei 8.036, de 11/05/90, do Decreto 99.684, de 08/11/90 e das Resoluções do Conselho Curador do FGTS. A seguir, segue um breve resumo dos aspectos gerais da utilização do FGTS.<br>
&nbsp;&nbsp;&nbsp;&nbsp;Os recursos do FGTS podem ser utilizados, dentre outras opções, para compra de imóvel residencial concluído com financiamento concedido no âmbito do Sistema Financeiro da Habitação – SFH.</em><br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Condições básicas para a utilização do FGTS<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;<em>O trabalhador deve contar com o mínimo de três anos de contribuição sob o regime do FGTS;
&nbsp;&nbsp;&nbsp;&nbsp;A utilização do FGTS, nas condições estabelecidas pela CEF, pode ser efetuada por mais de um trabalhador, desde que ambos sejam co-compradores ou co-proprietários do imóvel;<br>
&nbsp;&nbsp;&nbsp;&nbsp;Em regra, são considerados co-proprietários, os cônjuges casados em regime de comunhão universal de bens, independentemente da época de aquisição do imóvel, e os casados em regime de comunhão parcial de bens, desde que o imóvel tenha sido adquirido após o casamento;<br>
&nbsp;&nbsp;&nbsp;&nbsp;O trabalhador que tenha utilizado recursos do FGTS para aquisição de imóvel residencial concluído, para amortização extraordinária ou liquidação do saldo devedor ou para abatimento de parte do valor das prestações, pode utilizá-lo novamente, para qualquer das outras modalidades, observando, contudo, as condições definidas para cada uma delas.</em><br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Condições para utilização do FGTS para aquisição de imóvel residencial concluído<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;•	Requisitos exigidos do imóvel: O imóvel deve preencher todas as condições exigidas para financiamento no SFH e atender às demais exigências estabelecidas pela CEF;<br>
&nbsp;&nbsp;&nbsp;&nbsp;•	Destinação do imóvel: O imóvel adquirido com recursos do FGTS deve destinar-se à instalação da residência do comprador cujos recursos do FGTS estão sendo utilizados, condição que deve ser declarada, por todos os usuários, sob as penas da lei;<br>
&nbsp;&nbsp;&nbsp;&nbsp;•	Localização do imóvel: O imóvel deve se localizar no município onde o comprador exerça a sua ocupação principal, em município limítrofe ou integrante da respectiva região metropolitana, ou, ainda, no município onde o comprador comprovar que reside, há pelo menos um ano, por meio de 2 comprovantes, conforme manual do FGTS;<br>
&nbsp;&nbsp;&nbsp;&nbsp;•	Nova utilização para o mesmo imóvel: O imóvel adquirido com utilização do FGTS só pode ser objeto de outra operação de compra e venda com recursos do FGTS após decorridos, no mínimo, 3 anos, contados da data da última negociação ou da liberação da última parcela;<br>
&nbsp;&nbsp;&nbsp;&nbsp;•	Titularidade de outro imóvel pelo trabalhador, seu cônjuge ou 2º comprador: O trabalhador que pretenda utilizar os recursos do FGTS não pode ser proprietário ou promitente comprador de outro imóvel residencial concluído: <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(i) financiado pelo SFH, em qualquer parte do território nacional; <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(ii) No município onde exerça sua ocupação principal, nos municípios limítrofes ou na respectiva região metropolitana; ou <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(iii) no atual município de residência; e<br>
&nbsp;&nbsp;&nbsp;&nbsp;•	Para utilização do FGTS: O valor de avaliação do imóvel não pode exceder a R$ 350.000,00 e o valor do financiamento não pode exceder a R$ 245.000,00 (necessário o cumprimento de ambas as condições).<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Documentação necessária<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;1.	Extrato analítico do FGTS atualizado obtido junto à Caixa Econômica Federal (Validade: 60 dias);<br>
&nbsp;&nbsp;&nbsp;&nbsp;2.	Cópia simples das seguintes folhas da Carteira Profissional: foto, qualificação civil, contrato de trabalho, opção de FGTS e nº do PIS ou PASEP;<br>
&nbsp;&nbsp;&nbsp;&nbsp;3.	Declaração do Empregador constando o domicílio profissional do comprador em papel timbrado da Empresa com firma reconhecida, conforme modelo constante do Anexo VI;<br>
&nbsp;&nbsp;&nbsp;&nbsp;4.	Autorização para Movimentação de Conta Vinculada do FGTS – Aquisição Moradia (providenciar uma autorização para cada trabalhador que for utilizar o FGTS),<br>
&nbsp;&nbsp;&nbsp;&nbsp;5.	Quando houver 2º comprador/cônjuge, e este não for usar o FGTS, é necessário apresentar o Imposto de Renda. Não é necessário apresentar a Carteira Profissional, Declaração do empregador, Extratos do FGTS, <br>
&nbsp;&nbsp;&nbsp;&nbsp;6.	Cópia da Declaração do Imposto de Renda do Comprador e cônjuge. Caso algum deles declare o Imposto de Renda como isento, é necessário apresentar ao agente financeiro uma declaração, elaborada de próprio punho, informando, sob as penas da lei, sua condição de isento.<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Informações Importantes<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Caso o Comprador seja proprietário de terreno e pretenda utilizar os recursos do FGTS, faz-se necessário que comprove que sobre o terreno não há benfeitorias, por meio da apresentação de IPTU e da matrícula atualizada do terreno.<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Caso o Comprador seja divorciado ou separado judicialmente, deverá verificar junto à CEF se há valor bloqueado na conta do FGTS para fins de pensão alimentícia.<br>
&nbsp;&nbsp;&nbsp;&nbsp;Não é considerado Comprador ou proprietário de imóvel residencial, quitado ou financiado, aquele que detenha fração ideal igual ou inferior a 40% do imóvel.<br>
&nbsp;&nbsp;&nbsp;&nbsp;O valor do FGTS, acrescido do valor de financiamento, não pode exceder ao valor de venda ou avaliação do imóvel, o menor deles.<br>
&nbsp;&nbsp;&nbsp;&nbsp;No caso de utilização dos recursos de FGTS aplicados em Fundos Mútuos de Privatização, o Comprador deverá requerer previamente o resgate dos valores diretamente junto à administradora. A comprovação do crédito caberá ao interessado, mediante apresentação do respectivo extrato de conta ao agente financeiro.<br>
	<br><br><br>
<?
}?><br>
</td>
</tr>
</table>
</body>
</html>
