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
<br><br><strong><p align="center">ROTEIRO DE FINANCIAMENTO IMOBILI�RIO</p><br>
</strong><br>
<br>
<a name="1"></a>
&nbsp;&nbsp;&nbsp;&nbsp;<a href="documentos.php?doc=1#1"><strong>1� FASE � An�lise de Cr�dito do Comprador</strong></a><br>
<? if(@$_GET['doc']==1){
?>
	<br>
&nbsp;&nbsp;&nbsp;&nbsp;Documentos b�sicos necess�rios:<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;1.	C�pia simples dos tr�s �ltimos comprovantes de rendimento (aluguel, aposentadoria, holerite);<br>
&nbsp;&nbsp;&nbsp;&nbsp;2.	C�pia simples da declara��o de Imposto de Renda Pessoa F�sica completa do �ltimo exerc�cio, inclusive da p�gina de protocolo de entrega;<br>
&nbsp;&nbsp;&nbsp;&nbsp;3.	Caso o Comprador seja profissional liberal, aut�nomo, s�cio de empresa, c�pia simples do extrato dos �ltimos seis meses da conta-corrente Pessoa F�sica e da conta-corrente Pessoa Jur�dica, em que realiza maior movimenta��o banc�ria;<br>
&nbsp;&nbsp;&nbsp;&nbsp;4.	Caso o Comprador seja s�cio de empresa, c�pia simples do Contrato/Estatuto Social e da �ltima altera��o contratual da empresa, em que conste a participa��o do Comprador, bem como c�pia simples de documento que comprove o faturamento dos �ltimos seis meses da empresa. Caso se trate de empresa prestadora de servi�os, c�pia simples dos contratos de presta��o de servi�os vigentes.<br>
&nbsp;&nbsp;&nbsp;&nbsp;5.	Todos os Compradores dever�o entregar os documentos mencionados, mesmo que um deles n�o comprometa sua renda para a obten��o do financiamento.<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;<em>O Comprador � a pessoa f�sica interessada em adquirir im�vel e financiar parte do valor junto a um agente financeiro. Se esta for casada ou viver em uni�o est�vel, seu c�njuge ou companheiro tamb�m dever� configurar no contrato de compra e venda com financiamento como Comprador, a n�o ser que o casamento seja constitu�do em regime de separa��o total de bens.</em>
	<br><br><br>
<?
}?><br>
<a name="2"></a>
&nbsp;&nbsp;&nbsp;&nbsp;<a href="documentos.php?doc=2#2"><strong>2� FASE � Avalia��o de Garantia</strong></a><br>
<? if(@$_GET['doc']==2){
?>
	<br>
&nbsp;&nbsp;&nbsp;&nbsp;Documentos b�sicos do Im�vel:<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;1.	C�pia autenticada da certid�o atualizada da matr�cula do Im�vel, emitida pelo Cart�rio de Registro de Im�veis competente;<br>
&nbsp;&nbsp;&nbsp;&nbsp;2.	C�pia simples do carn� ou do demonstrativo do IPTU do exerc�cio em curso, que contenha o endere�o do im�vel, seu valor venal e as �reas do terreno e do im�vel. Caso se trate de im�vel novo e ainda n�o tenha sido emitido carn� de IPTU, apresentar a planta do im�vel aprovada na Prefeitura;<br>
&nbsp;&nbsp;&nbsp;&nbsp;3.	Caso se trate de im�vel sob o regime da enfiteuse, c�pia autenticada da Certid�o Autorizativa de Transfer�ncia, emitida pelo Setor de Patrim�nio da Uni�o, que pode ser solicitada pelo site www.spu.planejamento.gov.br e ficha de cadastro e c�pia simples dos comprovantes de pagamento do laud�mio e do foro;<br>
&nbsp;&nbsp;&nbsp;&nbsp;4.	C�pia simples dos comprovantes de pagamento de todas as parcelas do IPTU em exerc�cio, Certid�o Negativa de Tributos Municipais.<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Documenta��o b�sica do Comprador:<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;1.  C�pia simples do RG e do CPF do Comprador;<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;<em>O processo de Avalia��o de Garantia � Avalia��o f�sica e documental da garantia, s� inicia com o recebimento de todos os documentos solicitados neste roteiro e com a comprova��o do pagamento do boleto da Avalia��o de Garantia. A falta de qualquer documento poder� acarretar em atraso na contrata��o.</em><br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Observa��es Importantes:<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;�	Mesmo que ainda n�o tenha sido emitido o carn� de IPTU, a constru��o do im�vel dever� estar averbada no Cart�rio de Registro de Im�veis competente;<br>
&nbsp;&nbsp;&nbsp;&nbsp;�	O im�vel deve estar em bom estado de conserva��o e habitabilidade;<br>
&nbsp;&nbsp;&nbsp;&nbsp;�	N�o deve existir qualquer �nus (hipoteca, aliena��o fiduci�ria, penhora, processo de partilha/invent�rio, etc.) constitu�do sobre o im�vel;<br>
&nbsp;&nbsp;&nbsp;&nbsp;�	Se houver qualquer diverg�ncia na documenta��o (IPTU/matr�cula), como, por exemplo, diverg�ncia de �reas, ser� necess�rio providenciar a regulariza��o;<br>
&nbsp;&nbsp;&nbsp;&nbsp;�	Se o im�vel for uma casa e o Comprador/Vendedor possuir sua planta, esta dever� estar dispon�vel no ato da vistoria do im�vel.<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;<em>Comprovante do estado civil: (i) solteiro: Declara��o assinada; (ii) casado: c�pia simples da certid�o de casamento. Se tiver sido realizado pacto antenupcial, c�pia simples do pacto registrado no Cart�rio de Registro de Im�veis; (iii) desquitado, separado ou divorciado: c�pia simples da certid�o de casamento com averba��o do fato; (iv) vi�vo: c�pia simples da certid�o de casamento com averba��o do �bito ou acompanhada de c�pia simples da certid�o de �bito; (v) uni�o est�vel: assinar declara��o correspondente.<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Obs. Quando houver 2� comprador/c�njuge, dever�o ser anexados ao processo os mesmos documentos exigidos para o 1� comprador.<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;IMPORTANTE: Se qualquer Financiado for representado por procura��o, esta dever� ser lavrada por instrumento p�blico e dever� prever poderes espec�ficos para: praticar todos os atos necess�rios para efetuar a opera��o de financiamento � aquisi��o de im�vel, tais como, estabelecer cl�usulas, condi��es, pre�os, dar sinal, autorizar a constitui��o de gravame sobre o im�vel, confessar d�vida, receber e dar quita��o, assinar escrituras p�blicas ou particulares, contratos particulares ou c�dulas de cr�dito banc�rio, contratar financiamento imobili�rio, satisfazer toda e qualquer exig�ncia referente � documenta��o necess�ria, transacionar por meio de qualquer agente financeiro, representar o outorgante perante qualquer reparti��o p�blica federal, estadual, municipal e autarquias, assinar contrato de cess�o de direitos de cr�ditos oriundos de financiamento imobili�rio em nome do outorgante, na qualidade de anuente, como o objetivo de transferir o financiamento imobili�rio de determinada institui��o financeira para outra, � sua escolha, entre outros. A procura��o dever� ser providenciada em duas vias, juntamente com uma c�pia simples do RG e do CPF do procurador. Uma deve ser entregue junto com a documenta��o e a outra ser� utilizada no momento do Registro do Contrato.</em><br><br>

&nbsp;&nbsp;&nbsp;&nbsp;<strong>Para confec��o da procura��o, verificar o modelo sugerido pelo cart�rio a ser utilizado.</strong><br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Documenta��o do Vendedor (Pessoa F�sica):<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;1.	C�pia simples do RG e do CPF;<br>
&nbsp;&nbsp;&nbsp;&nbsp;2.	Comprovante do estado civil: (i) solteiro: declara��o assinada; (ii) casado: c�pia simples da certid�o de casamento. Se tiver sido realizado pacto antenupcial, c�pia simples do pacto registrado no Cart�rio de Registro de Im�veis; (iii) desquitado, separado ou divorciado: c�pia simples da certid�o de casamento com averba��o do fato; (iv) vi�vo: c�pia simples da certid�o de casamento com averba��o do �bito ou acompanhada de c�pia simples da certid�o de �bito; (v) uni�o est�vel: assinar declara��o correspondente, <br>
&nbsp;&nbsp;&nbsp;&nbsp;3.	C�pias autenticadas ou originais das Certid�es Negativas dos Distribuidores C�veis, englobando executivos fiscais, tutela, curatela e interdi��o, abrangendo o per�odo de dez anos. Caso o Vendedor resida em comarca diferente da qual se localiza o im�vel, apresentar as certid�es de ambas as comarcas;<br>
&nbsp;&nbsp;&nbsp;&nbsp;4.	C�pia autenticada ou original da Certid�o Negativa da Justi�a Federal, abrangendo o per�odo de dez anos. Caso o Vendedor resida em comarca diferente da qual se localiza o im�vel, apresentar as certid�es de ambas as comarcas;<br>
&nbsp;&nbsp;&nbsp;&nbsp;5.	C�pia autenticada ou original da Certid�o de Distribui��es Trabalhistas de compet�ncia da Justi�a do Trabalho;<br>
&nbsp;&nbsp;&nbsp;&nbsp;6.	Caso o Vendedor seja ascendente (pai/m�e) do Comprador, dever� entregar uma declara��o, informando o n�mero de filhos que possui e a qualifica��o de cada filho. Ser� necess�rio apresentar:<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(i)	C�pia simples do RG e do CPF de cada filho e respectivos c�njuges, <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(ii) comprova��o de estado civil de cada filho e c�njuge, conforme o enquadramento feito em uma das op��es constantes do terceiro item desta lista de documentos. Cada filho e respectivo c�njuge dever� apresentar declara��o, com reconhecimento de firma, anuindo � venda do im�vel.<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Documenta��o do Vendedor (Pessoa Jur�dica):<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;1.	Original ou c�pia autenticada da Certid�o Negativa de D�bito do INSS atualizada. A certid�o pode ser obtida nas Ag�ncias da Previd�ncia Social ou pela internet, no site www.mpas.gov.br;<br>
&nbsp;&nbsp;&nbsp;&nbsp;2.	Original ou c�pia autenticada da Certid�o Conjunta da Receita Federal e D�vida Ativa da Uni�o atualizada. A certid�o pode ser obtida na Secretaria da Receita Federal ou pela Internet, no site<br>
&nbsp;&nbsp;&nbsp;&nbsp;3.	www.receita.fazenda.gov.br;<br>
&nbsp;&nbsp;&nbsp;&nbsp;4.	Original ou c�pia autenticada da Certid�o de Regularidade do FGTS atualizada. A certid�o pode ser obtida na Caixa Econ�mica Federal ou pela internet, no site www.caixa.gov.br;<br>
&nbsp;&nbsp;&nbsp;&nbsp;5.	C�pia simples do Estatuto/Contrato Social da empresa, com indica��o da data e n�mero do seu arquivamento na Junta Comercial;<br>
&nbsp;&nbsp;&nbsp;&nbsp;6.	Ata da Assembl�ia de elei��o da atual Diretoria, registrada na Junta Comercial;<br>
&nbsp;&nbsp;&nbsp;&nbsp;7.	Carta informando a data da �ltima altera��o contratual ou estatut�ria com indica��o dos representantes da empresa e qualifica��o destes para assinatura do contrato;<br>
&nbsp;&nbsp;&nbsp;&nbsp;8.	Caso o im�vel esteja localizado no Estado do Rio de Janeiro, c�pia autenticada do Cart�o CNPJ;<br>
&nbsp;&nbsp;&nbsp;&nbsp;9.	C�pias autenticadas ou Originais das Certid�es Negativas dos Distribuidores C�veis, englobando executivos fiscais, fal�ncia e recupera��o judicial, pelo per�odo de dez anos. Caso a sede do Vendedor esteja em comarca diferente da qual se localiza o im�vel, apresentar as certid�es de ambas as comarcas;<br>
&nbsp;&nbsp;&nbsp;&nbsp;10.	C�pia autenticada ou original da Certid�o Negativa da Justi�a Federal, abrangendo o per�odo de dez anos;<br>
&nbsp;&nbsp;&nbsp;&nbsp;11.	C�pia autenticada ou original da Certid�o de Distribui��es Trabalhistas de compet�ncia da Justi�a do Trabalho, referente a a��es em andamento, nominal � empresa e aos s�cios.<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Todos os Vendedores dever�o entregar os documentos acima mencionados.<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;<em>IMPORTANTE: Se qualquer Vendedor for representado por procura��o, esta dever� ser lavrada por instrumento p�blico e dever� prever poderes espec�ficos para: vender o im�vel (descri��o completa do im�vel), praticar todos os atos necess�rios para efetuar a opera��o, tais como, estabelecer cl�usulas, condi��es, pre�os, receber sinal, dar dom�nio, direitos e a��es, receber e dar quita��o, assinar escrituras p�blicas ou particulares de venda e compra, contratos particulares ou c�dulas de cr�dito banc�rio, satisfazer toda e qualquer exig�ncia referente � documenta��o necess�ria, transacionar por meio de qualquer agente financeiro, representar o outorgante perante qualquer reparti��o p�blica federal, estadual, municipal e autarquias, entre outros. A procura��o dever� ser providenciada em duas vias, juntamente com uma c�pia simples do RG e do CPF do procurador. Uma deve ser entregue junto com a documenta��o e a outra ser� utilizada no momento do Registro do Contrato.
Para confec��o da procura��o, verificar o modelo sugerido pelo cart�rio a ser utilizado.</em>
	<br><br><br>
<?
}?><br>
<a name="3"></a>
&nbsp;&nbsp;&nbsp;&nbsp;<a href="documentos.php?doc=3#3"><strong>3� FASE � Emiss�o do Contrato</strong></a><br>
<? if(@$_GET['doc']==3){
?>
	<br>
&nbsp;&nbsp;&nbsp;&nbsp;<em>Conclu�das a 1� e 2� Fases, ser� emitido o instrumento que formalizar� a venda e compra com o financiamento imobili�rio solicitado, o qual ser� assinado pelo Comprador, Vendedor, Interveniente (se houver) e a institui��o financeira, este na qualidade de agente financiador.<br>
&nbsp;&nbsp;&nbsp;&nbsp;Aten��o! Juntamente com o instrumento, ser� entregue pelo agente financeiro uma correspond�ncia contendo as principais informa��es sobre quais provid�ncias dever�o ser tomadas para a conclus�o da venda e compra e do processo de financiamento.</em><br><br>
&nbsp;&nbsp;&nbsp;&nbsp;CUSTOS ENVOLVIDOS NO PROCESSO DE CONTRATA��O<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Tarifa de Avalia��o de Garantia (Avalia��o f�sica e documental da garantia : o valor da tarifa est� de acordo com cada institui��o financeira.<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;<em>IMPORTANTE: Uma vez prestado o servi�o de Avalia��o de Garantia - Avalia��o f�sica e documental da garantia, a taxa correspondente n�o ser� ressarcida, sob qualquer hip�tese. A presta��o de tais servi�os n�o tem qualquer rela��o com a obten��o ou n�o do financiamento imobili�rio.
&nbsp;&nbsp;&nbsp;&nbsp;Ap�s contratado o financiamento, outros custos ser�o devidos:<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(i) ITBI- Imposto Municipal de Transmiss�o de Bens;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(ii) custos cartor�rios de registro das opera��es de venda e compra e da garantia de aliena��o fiduci�ria;<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(iii) tarifa de administra��o � cobran�a e administra��o do contrato, cobrada mensalmente pelo agente financeiro e <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(iv) seguros de morte e invalidez permanente e danos f�sicos ao im�vel.</em><br><br>
&nbsp;&nbsp;&nbsp;&nbsp;INFORMA��ES ESSENCIAIS<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Seguros:<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;�	A contrata��o de seguro contra danos f�sicos ao Im�vel - DFI e seguro contra morte ou invalidez permanente MIP � obrigat�ria, conforme previsto na Lei n� 9.514/97 e na Resolu��o n� 3.347/06 do Conselho Monet�rio Nacional.<br>
&nbsp;&nbsp;&nbsp;&nbsp;�	O seguro MIP - (MORTE E INVALIDEZ PERMANENTE) garante a cobertura do saldo devedor do contrato em caso de morte ou invalidez permanente do(s) adquirente(s), de acordo com a composi��o de renda. A al�quota � incidente sobre o valor do Saldo Devedor e a idade do financiado mais idoso. Em caso de sinistro com o(s) adquirente(s), o seguro ser� indenizado na propor��o da participa��o da renda de cada financiado.<br>
&nbsp;&nbsp;&nbsp;&nbsp;�	DFI (DANOS F�SICOS NO IM�VEL)- garante a cobertura das condi��es do im�vel em caso de sinistro (principais: inc�ndio; explos�o; queda de raio; desmoronamento total; desmoronamento parcial; destrui��o ou desabamento de paredes, vigas ou outro elemento estrutural; amea�a de desmoronamento, devidamente comprovada; destelhamento; inunda��o ou alagamento), limitado ao valor da avalia��o do im�vel. A al�quota � incidente sobre o valor da Avalia��o do Im�vel. Em caso de sinistro com o o(s) adquirente(s), o seguro ser� indenizado na propor��o da participa��o da renda de cada financiado.<br>
&nbsp;&nbsp;&nbsp;&nbsp;�	Para mais informa��es, consulte as ap�lices espec�ficas.<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;INFORMA��ES PARA IM�VEIS LOCALIZADOS NO ESTADO DO RIO DE JANEIRO:<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;<em>As certid�es do Estado do Rio de Janeiro t�m prazo de validade de trinta dias e dever�o ser entregues ao Unibanco em c�pias simples (as originais dever�o ser apresentadas ao Cart�rio de Registro de Im�veis, no ato do registro do contrato de financiamento imobili�rio);<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Informa��es sobre o Interveniente<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Interveniente Quitante: Caso o im�vel esteja hipotecado ou alienado a um terceiro, essa pessoa poder� figurar no instrumento de financiamento imobili�rio como Interveniente Quitante;<br>
&nbsp;&nbsp;&nbsp;&nbsp;Interveniente Garantidor: pessoa, f�sica ou jur�dica, que dar� um im�vel de sua propriedade em garantia do financiamento. Pode ser o pr�prio Comprador ou um terceiro. No caso de terceiro, este n�o ser� respons�vel pelo pagamento da presta��o e nem pelo pagamento do principal financiado e n�o precisar� compor renda com o Comprador. No entanto, � necess�ria a an�lise documental de suas certid�es e documentos, bem como do im�vel dado em garantia.
Devedor solid�rio � respons�vel, juntamente com o Comprador, pelas obriga��es pecuni�rias por ele assumidas no contrato de financiamento. O devedor solid�rio � apenas garantidor da opera��o, pela qual responde com seus bens pessoais. N�o se trata de comprador do im�vel, ou seja, a propriedade do im�vel n�o ser� transferida para seu nome. Em caso de inadimplemento do Comprador, o devedor solid�rio poder� ser acionado para quitar a d�vida. N�o, necessariamente, precisa compor renda, mas se compuser, dever� ser considerado para o c�lculo do seguro MIP, mas � necess�rio que seja realizada a an�lise de seu cr�dito.
Quando o co-devedor for casado, seu c�njuge dever� assinar o contrato, salvo se o regime de casamento for por separa��o total ou legal de bens.<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Outras Informa��es<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Quando o Vendedor ou seu c�njuge/companheiro tiverem tido seus nomes alterados, em um per�odo inferior a dez anos contados do pedido de financiamento imobili�rio, as Certid�es Negativas dos Distribuidores C�veis e Justi�a Federal dever�o ser apresentadas tanto na atual denomina��o como na anterior.</em><br><br>
&nbsp;&nbsp;&nbsp;&nbsp;INFORMA��ES PARA A UTILIZA��O DO FGTS<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;</em>A utiliza��o do FGTS � disciplinada pela Caixa Econ�mica Federal - CEF, por meio do FGTS - Manual da Moradia Pr�pria, na forma da Lei 8.036, de 11/05/90, do Decreto 99.684, de 08/11/90 e das Resolu��es do Conselho Curador do FGTS. A seguir, segue um breve resumo dos aspectos gerais da utiliza��o do FGTS.<br>
&nbsp;&nbsp;&nbsp;&nbsp;Os recursos do FGTS podem ser utilizados, dentre outras op��es, para compra de im�vel residencial conclu�do com financiamento concedido no �mbito do Sistema Financeiro da Habita��o � SFH.</em><br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Condi��es b�sicas para a utiliza��o do FGTS<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;<em>O trabalhador deve contar com o m�nimo de tr�s anos de contribui��o sob o regime do FGTS;
&nbsp;&nbsp;&nbsp;&nbsp;A utiliza��o do FGTS, nas condi��es estabelecidas pela CEF, pode ser efetuada por mais de um trabalhador, desde que ambos sejam co-compradores ou co-propriet�rios do im�vel;<br>
&nbsp;&nbsp;&nbsp;&nbsp;Em regra, s�o considerados co-propriet�rios, os c�njuges casados em regime de comunh�o universal de bens, independentemente da �poca de aquisi��o do im�vel, e os casados em regime de comunh�o parcial de bens, desde que o im�vel tenha sido adquirido ap�s o casamento;<br>
&nbsp;&nbsp;&nbsp;&nbsp;O trabalhador que tenha utilizado recursos do FGTS para aquisi��o de im�vel residencial conclu�do, para amortiza��o extraordin�ria ou liquida��o do saldo devedor ou para abatimento de parte do valor das presta��es, pode utiliz�-lo novamente, para qualquer das outras modalidades, observando, contudo, as condi��es definidas para cada uma delas.</em><br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Condi��es para utiliza��o do FGTS para aquisi��o de im�vel residencial conclu�do<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;�	Requisitos exigidos do im�vel: O im�vel deve preencher todas as condi��es exigidas para financiamento no SFH e atender �s demais exig�ncias estabelecidas pela CEF;<br>
&nbsp;&nbsp;&nbsp;&nbsp;�	Destina��o do im�vel: O im�vel adquirido com recursos do FGTS deve destinar-se � instala��o da resid�ncia do comprador cujos recursos do FGTS est�o sendo utilizados, condi��o que deve ser declarada, por todos os usu�rios, sob as penas da lei;<br>
&nbsp;&nbsp;&nbsp;&nbsp;�	Localiza��o do im�vel: O im�vel deve se localizar no munic�pio onde o comprador exer�a a sua ocupa��o principal, em munic�pio lim�trofe ou integrante da respectiva regi�o metropolitana, ou, ainda, no munic�pio onde o comprador comprovar que reside, h� pelo menos um ano, por meio de 2 comprovantes, conforme manual do FGTS;<br>
&nbsp;&nbsp;&nbsp;&nbsp;�	Nova utiliza��o para o mesmo im�vel: O im�vel adquirido com utiliza��o do FGTS s� pode ser objeto de outra opera��o de compra e venda com recursos do FGTS ap�s decorridos, no m�nimo, 3 anos, contados da data da �ltima negocia��o ou da libera��o da �ltima parcela;<br>
&nbsp;&nbsp;&nbsp;&nbsp;�	Titularidade de outro im�vel pelo trabalhador, seu c�njuge ou 2� comprador: O trabalhador que pretenda utilizar os recursos do FGTS n�o pode ser propriet�rio ou promitente comprador de outro im�vel residencial conclu�do: <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(i) financiado pelo SFH, em qualquer parte do territ�rio nacional; <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(ii) No munic�pio onde exer�a sua ocupa��o principal, nos munic�pios lim�trofes ou na respectiva regi�o metropolitana; ou <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(iii) no atual munic�pio de resid�ncia; e<br>
&nbsp;&nbsp;&nbsp;&nbsp;�	Para utiliza��o do FGTS: O valor de avalia��o do im�vel n�o pode exceder a R$ 350.000,00 e o valor do financiamento n�o pode exceder a R$ 245.000,00 (necess�rio o cumprimento de ambas as condi��es).<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Documenta��o necess�ria<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;1.	Extrato anal�tico do FGTS atualizado obtido junto � Caixa Econ�mica Federal (Validade: 60 dias);<br>
&nbsp;&nbsp;&nbsp;&nbsp;2.	C�pia simples das seguintes folhas da Carteira Profissional: foto, qualifica��o civil, contrato de trabalho, op��o de FGTS e n� do PIS ou PASEP;<br>
&nbsp;&nbsp;&nbsp;&nbsp;3.	Declara��o do Empregador constando o domic�lio profissional do comprador em papel timbrado da Empresa com firma reconhecida, conforme modelo constante do Anexo VI;<br>
&nbsp;&nbsp;&nbsp;&nbsp;4.	Autoriza��o para Movimenta��o de Conta Vinculada do FGTS � Aquisi��o Moradia (providenciar uma autoriza��o para cada trabalhador que for utilizar o FGTS),<br>
&nbsp;&nbsp;&nbsp;&nbsp;5.	Quando houver 2� comprador/c�njuge, e este n�o for usar o FGTS, � necess�rio apresentar o Imposto de Renda. N�o � necess�rio apresentar a Carteira Profissional, Declara��o do empregador, Extratos do FGTS, <br>
&nbsp;&nbsp;&nbsp;&nbsp;6.	C�pia da Declara��o do Imposto de Renda do Comprador e c�njuge. Caso algum deles declare o Imposto de Renda como isento, � necess�rio apresentar ao agente financeiro uma declara��o, elaborada de pr�prio punho, informando, sob as penas da lei, sua condi��o de isento.<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Informa��es Importantes<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Caso o Comprador seja propriet�rio de terreno e pretenda utilizar os recursos do FGTS, faz-se necess�rio que comprove que sobre o terreno n�o h� benfeitorias, por meio da apresenta��o de IPTU e da matr�cula atualizada do terreno.<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;Caso o Comprador seja divorciado ou separado judicialmente, dever� verificar junto � CEF se h� valor bloqueado na conta do FGTS para fins de pens�o aliment�cia.<br>
&nbsp;&nbsp;&nbsp;&nbsp;N�o � considerado Comprador ou propriet�rio de im�vel residencial, quitado ou financiado, aquele que detenha fra��o ideal igual ou inferior a 40% do im�vel.<br>
&nbsp;&nbsp;&nbsp;&nbsp;O valor do FGTS, acrescido do valor de financiamento, n�o pode exceder ao valor de venda ou avalia��o do im�vel, o menor deles.<br>
&nbsp;&nbsp;&nbsp;&nbsp;No caso de utiliza��o dos recursos de FGTS aplicados em Fundos M�tuos de Privatiza��o, o Comprador dever� requerer previamente o resgate dos valores diretamente junto � administradora. A comprova��o do cr�dito caber� ao interessado, mediante apresenta��o do respectivo extrato de conta ao agente financeiro.<br>
	<br><br><br>
<?
}?><br>
</td>
</tr>
</table>
</body>
</html>
