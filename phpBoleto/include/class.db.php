<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | phpBoleto v2.0                                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 1999-2001 Pablo Martins F. Costa, João Prado Maia      |
// +----------------------------------------------------------------------+
// | Este arquivo está sujeito a versão 2 da GNU General Public License,  |
// | que foi adicionada nesse pacote no arquivo COPYING e está disponível |
// | pela Web em http://www.gnu.org/copyleft/gpl.txt                      |
// | Você deve ter recebido uma cópia da GNU Public License junto com     |
// | esse pacote; se não, escreva para:                                   |
// |                                                                      |
// | Free Software Foundation, Inc.                                       |
// | 59 Temple Place - Suite 330                                          |
// | Boston, MA 02111-1307, USA.                                          |
// +----------------------------------------------------------------------+
// | Autores: João Prado Maia <jpm@phpbrasil.com>                         |
// +----------------------------------------------------------------------+
//
// @(#) $Id: class.db.php,v 1.1 2007/01/18 19:43:34 leonardo.kopp Exp $
//


/**
 * A classe "DB" cria uma API para acesso aos dados armazenados nos vários
 * bancos de dados 'virtuais', seja num servidor de banco de dados real, ou 
 * em arquivos INI ou XML. Ela serve como uma classe intermediária ao acesso
 * real ao banco de dados onde as informações sobre o Boleto estão armazenadas.
 *
 * Essa classe atua como um "class factory", incluindo e criando os objetos
 * apropriados automaticamente dependendo da variável $sistema.
 *
 * DB            A classe DB principal. Ela é somente uma classe simples 
 *               para chamar dinâmicamente o objeto correto do modelo de 
 *               boleto, com o parâmetro de banco de dados.
 *
 * DB_Comum      A base para cada implementação da API de acesso a banco de 
 * |             dados, onde alguns métodos que são compartilhados por alguns 
 * |             modelos.
 * |
 * +-DB_Banco    A implementação da API com o acesso ao servidor de banco de 
 *               dados. Ela na verdade é mais uma classe intermediária à 
 *               biblioteca de abstração de banco de dados PEAR::DB. Leia mais
 *               sobre esse pacote de abstração em http://pear.php.net ou até
 *               um tutorial sobre PEAR::DB em 
 *               http://vulcanonet.com/soft/?pack=pear_tut
 *
 * @version  2
 * @author   João Prado Maia <jpm@phpbrasil.com>
 */

include_once(BOLETO_INC_PATH . "class.grava_erro.php");

class Boleto_DB
{
    /**
     * Cria um objeto com a implementação específica da API de acesso a banco
     * de dados do phpBoleto. Opções disponíveis atualmente para o tipo de
     * armazenamento de dados incluem:
     *
     * "banco" -> Acesso ao servidor de banco de dados (ex: MySQL, PostgreSQL)
     * "ini"   -> Acesso ao banco de dados em arquivos INI
     *
     * @access  public
     * @param   string $sistema 
     * @return  object Um objeto contendo a API completa de acesso de dados
     */
    function &conectar($sistema)
    {
        $arquivo_classe = BOLETO_INC_PATH . "db" . BOLETO_SEPARADOR . "class." . strtolower($sistema) . ".php";
        if (!@include_once($arquivo_classe)) {
            GravaErro::grava("Classe não pôde ser incluída ('$arquivo_classe')", __FILE__, __LINE__);
            return false;
        } else {
            $nome_classe = "Boleto_DB_${sistema}";
            $objeto =& new $nome_classe;
        }
        return $objeto;
    }
}
?>