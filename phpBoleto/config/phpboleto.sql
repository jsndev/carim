DROP TABLE IF EXISTS bancos;
CREATE TABLE bancos (
   bnid smallint(3) unsigned NOT NULL auto_increment,
   layout varchar(40) NOT NULL,
   nome varchar(20) NOT NULL,
   codigo int(4) unsigned DEFAULT '0' NOT NULL,
   uso_do_banco varchar(50) NOT NULL,
   PRIMARY KEY (bnid),
   UNIQUE nome (nome),
   KEY bnid (bnid)
);
INSERT INTO bancos (bnid, layout, nome, codigo, uso_do_banco) VALUES ( '1', 'class.banco.bradesco.php', 'Bradesco', '234', 'verdade!');
DROP TABLE IF EXISTS boletos;
CREATE TABLE boletos (
   bid int(4) unsigned NOT NULL auto_increment,
   bnid int(4) unsigned DEFAULT '0' NOT NULL,
   cid int(4) unsigned DEFAULT '0' NOT NULL,
   titulo varchar(30) NOT NULL,
   agencia varchar(10) NOT NULL,
   cedente varchar(255) NOT NULL,
   conta_cedente varchar(20) NOT NULL,
   especie_documento varchar(10) NOT NULL,
   codigo varchar(40) NOT NULL,
   sacado varchar(50) NOT NULL,
   cpf varchar(20) NOT NULL,
   local_pagamento varchar(255) NOT NULL,
   sacador varchar(50) NOT NULL,
   carteira varchar(30) NOT NULL,
   instrucoes_linha1 varchar(100) NOT NULL,
   instrucoes_linha2 varchar(100) NOT NULL,
   instrucoes_linha3 varchar(100) NOT NULL,
   instrucoes_linha4 varchar(100) NOT NULL,
   instrucoes_linha5 varchar(100) NOT NULL,
   PRIMARY KEY (bid),
   UNIQUE titulo (titulo),
   KEY cid (cid)
);
INSERT INTO boletos (bid, bnid, cid, titulo, agencia, cedente, conta_cedente, especie_documento, codigo, sacado, cpf, local_pagamento, sacador, carteira, instrucoes_linha1, instrucoes_linha2, instrucoes_linha3, instrucoes_linha4, instrucoes_linha5) VALUES ( '1', '1', '1', 'teste 1 2 3', '', 'Empresa S.A.', '', 'ESP', '0123-4    123.456-7', 'XXXXXXXXXXXXX', '12345678', 'Pagável em qualquer banco até o vencimento', 'cliente !', '12345', 'linha 1', 'linha 2', 'linha 3', 'linha 4', 'linha 5');
INSERT INTO boletos (bid, bnid, cid, titulo, agencia, cedente, conta_cedente, especie_documento, codigo, sacado, cpf, local_pagamento, sacador, carteira, instrucoes_linha1, instrucoes_linha2, instrucoes_linha3, instrucoes_linha4, instrucoes_linha5) VALUES ( '2', '1', '1', 'booooo', '0436', 'hello', '0404392', 'REC', '12345', 'hello', '1234566778899909', 'ipanema', 'sou eu mesmo', '123', 'linha1', 'linha2', 'linha3', 'linha4', 'linha5');
DROP TABLE IF EXISTS config;
CREATE TABLE config (
   cid smallint(3) unsigned NOT NULL auto_increment,
   titulo varchar(30) NOT NULL,
   enviar_email tinyint(1) unsigned DEFAULT '0' NOT NULL,
   remetente varchar(50) NOT NULL,
   remetente_email varchar(255) NOT NULL,
   assunto varchar(50) NOT NULL,
   servidor_smtp varchar(80) NOT NULL,
   servidor_http varchar(80) NOT NULL,
   imagem_tipo varchar(4) NOT NULL,
   usar_truetype tinyint(1) unsigned DEFAULT '1' NOT NULL,
   enviar_pdf tinyint(1) unsigned DEFAULT '0' NOT NULL,
   mensagem_texto longtext NOT NULL,
   mensagem_html longtext NOT NULL,
   PRIMARY KEY (cid),
   UNIQUE titulo (titulo)
);
INSERT INTO config (cid, titulo, enviar_email, remetente, remetente_email, assunto, servidor_smtp, servidor_http, imagem_tipo, usar_truetype, enviar_pdf, mensagem_texto, mensagem_html) VALUES ( '1', 'default', '0', 'impleo llc', 'support@impleo.net', 'cool stuff !', 'mail.impleo.net', 'www.impleo.net', 'png', '1', '0', 'mensagem legal', '<b>mensagem boa</b>');
