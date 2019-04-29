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
// @(#) $Id: class.comum.php,v 1.1 2007/01/18 19:43:35 leonardo.kopp Exp $
//

/**
 * A classe Boleto_DB_Comum é herdada por todas as outras classes de acesso a
 * banco de dados, para disponibilizar alguns métodos compartilhados por essas
 * classes.
 *
 * @version  2
 * @author   João Prado Maia <jpm@phpbrasil.com>
 */

class Boleto_DB_Comum
{
    /**
     * Método usado para setar o caminho do diretório das configurações
     * do phpBoleto.
     *
     * @access  private
     * @return  void
     */
    function _pegaCaminhoConfiguracao()
    {
        $this->ini_path = BOLETO_CONF_PATH;
    }

    /**
     * Pega a configuração geral sobre a conexão ao banco de dados, como o
     * parâmetro de tipo de servidor ("ini", "mysql", "pgsql", etc).
     *
     * @access  private
     * @return  array Vetor com os parâmetros de configuração do banco de dados
     * @see     File_Ini(), getBlockValues()
     */
    function _pegaConfiguracaoINI()
    {
        include_once(BOLETO_INC_PATH . "class.ini.php");
        // infelizmente é necessário essa checagem para não 
        // quebrar o módulo de administração
        $this->inidb = new File_Ini($this->ini_path . "phpboleto.ini.php", "#");
        return $this->inidb->getBlockValues("Banco de Dados");
    }

    /**
     * Método usado para pegar o título real para o layout do boleto. Ele
     * retorna um array com a lista de arquivos.
     *
     * @access  public
     * @return  array Vetor associativo com os dados sobre os layouts de boletos
     * @see     getBlockValues(), _bin2asc()
     */
    function listaLayouts()
    {
        $bancos = array();
        // loop entre os layouts
        $d = dir(BOLETO_INC_PATH . "boletos/bancos");
        while ($arquivo = $d->read()) {
            if (($arquivo != ".") && ($arquivo != "..") && ($arquivo != "CVS")) {
                $bancos[$arquivo] = $arquivo;
            }
        }
        $d->close();
        return $bancos;
    }

    /**
     * Método usado para converter strings armazenadas em código
     * binário para formato ASCII.
     *
     * @access  private
     * @param   string $binary Valor em código binário
     * @return  string Valor convertido de binário para ASCII
     */
    function _bin2asc($binary)
    {
        $ascii = "";
        $i = 0;
        while (strlen($binary) > 3) {
            $byte[$i] = substr($binary, 0, 8);
            $byte[$i] = base_convert($byte[$i], 2, 10);
            $byte[$i] = chr($byte[$i]);
            $binary = substr($binary, 8);
            $ascii = "$ascii$byte[$i]";
        }
        return $ascii;
    }
}
?>
