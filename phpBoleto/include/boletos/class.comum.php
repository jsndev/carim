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
// |          Pablo Martins F. Costa <pablo@users.sourceforge.net>        |
// +----------------------------------------------------------------------+
//
// @(#) $Id: class.comum.php,v 1.1 2007/01/18 19:43:35 leonardo.kopp Exp $
//


/**
 * A classe Boleto_Comum é herdada por todas as outras classes de geração de
 * modelos de boletos, e simplesmente disponibiliza alguns métodos 
 * compartilhados por essas classes, como a geração da barra de códigos e 
 * também a gravação / remoção de imagens temporárias usadas na criação dos
 * boletos.
 *
 * @version  2
 * @author   João Prado Maia <jpm@phpbrasil.com>
 */

include_once(BOLETO_INC_PATH . "class.grava_erro.php");

class Boleto_Comum
{
    /**
     * Largura padrão para o código de barras
     * @var int
     */
    var $largura_barra = 1;

    /**
     * Altura padrão para o código de barras
     * @var int
     */
    var $altura_barra = 65;

    /**
     * Método usado para 'adivinhar' o nome da classe do layout de geração de
     * boletos de um banco. Como o nome pode ser qualquer um para o nome de um
     * banco, e queremos evitar criar mais uma limitação para possíveis 
     * contribuições para o código do phpBoleto, preferimos adivinhar o nome da
     * classe do que pedir ao desenvolvedor ter que manter uma tabela separada
     * mapeando o nome do arquivo de layout <=> nome da classe de layout.
     *
     * @access  private
     * @return  string O nome da classe
     * @see     get_declared_classes()
     */
    function _pegaLayoutClasse()
    {
        $classes = get_declared_classes();
        foreach ($classes as $classe) {
            if ((stristr($classe, "Boleto_Banco_")) && ($classe != "Boleto_Banco_Comum")) {
                $classe_layout = $classe;
            }
        }
        return $classe_layout;
    }

    /**
     * Método de geração da barra de código usada nos vários modelos de boleto.
     * Ela irá por sua vez chamar um método privado para rodar o algoritmo
     * apropriado de criação das barras para cada modelo.
     *
     * @access  private
     * @param   string $codigo O código (número de 44 digitos normalmente) que é 
     *                         usado na geração da barra de código.
     * @return  void
     * @see     _adicionaBarra()
     */
    function _geraBarra($codigo)
    {
        $repr_numerica = array(
            "00110", /* 0 */
            "10001", /* 1 */
            "01001", /* 2 */
            "11000", /* 3 */
            "00101", /* 4 */
            "10100", /* 5 */
            "01100", /* 6 */
            "00011", /* 7 */
            "10010", /* 8 */
            "01010"  /* 9 */
        );
        $var1 = substr((string) $codigo, 0, 1);
        $var2 = substr((string) $codigo, 1, 1);

        for ($i = 0; $i < 5; $i++) {
            if (substr($repr_numerica[$var1], $i, 1)) {
                $this->_adicionaBarra($this->cores["preto"], $this->config["tamanho_largo"]);
            } else {
                $this->_adicionaBarra($this->cores["preto"], $this->config["tamanho_fino"]);
            }
            if (substr($repr_numerica[$var2], $i, 1)) {
                $this->_adicionaBarra($this->cores["branco"], $this->config["tamanho_largo"]);
            } else {
                $this->_adicionaBarra($this->cores["branco"], $this->config["tamanho_fino"]);
            }
        }
    }

    /**
     * Método usado para gravar uma imagem temporária. Ele é usado na geração 
     * de boletos em formato PDF e também no envio de boletos por email.
     *
     * @access  private
     * @param   string $imagem_tipo O tipo da imagem (JPEG, GIF, PNG, WBMP)
     * @param   array $formatos Vetor possuindo a lista de formatos de imagem
     *                          disponíveis nessa instalação do PHP
     * @param   int $imagem Parâmetro contendo o identificador de imagem retornado
     *                      por ImageCreate() ou funções similares.
     * @return  string O caminho relativo para a imagem temporária
     */
    function _gravaImagemTemporaria($imagem_tipo, $formatos, $imagem)
    {
        $temp_imagem = BOLETO_TEMP_PATH . md5(microtime()) . "." . $imagem_tipo;
        $sufixo = strtoupper($imagem_tipo);
        if ((isset($formatos[$imagem_tipo])) && ($formatos[$imagem_tipo])) {
            $nome_func = "Image" . $sufixo;
            $nome_func($imagem, $temp_imagem);
            return $temp_imagem;
        } else {
            GravaErro::grava("Formato de imagem desconhecido ou não suportado ('$sufixo')", __FILE__, __LINE__);
        }
    }

    /**
     * Método usado para remover a imagem temporária criada pelo método 
     * _gravaImagemTemporaria().
     *
     * @access  private
     * @param   string $temp_imagem Caminho relativo para a imagem a ser removida
     * @return  void
     */
    function _removeImagemTemporaria($temp_imagem)
    {
        if (!@unlink($temp_imagem)) {
            GravaErro::grava("Remoção do arquivo '$temp_image' não foi completado corretamente", __FILE__, __LINE__);
        }
    }

    /**
     * Inicializa uma variável para um valor pre-determinado se a mesma não 
     * existe. Importante para os parâmetros opcionais de geração dinâmica do 
     * boleto, já que em alguns casos do nível do error_reporting() sendo alto,
     * o PHP irá mostrar uma mensagem de erro para referências a variáveis que
     * não foram declaradas, o que é exatamente esse caso de parâmetros 
     * opcionais.
     *
     * @access  private
     * @param   mixed $variavel Referência à variável que está a se checar
     * @param   mixed $valor Valor para a variável se a mesma não existir
     * @return  mixed
     */
    function _inicializar(&$variavel, $valor)
    {
        if ((!isset($variavel)) || (empty($variavel))) {
            return $valor;
        } else {
            return $variavel;
        }
    }

    /**
     * Método usado pelos várias classes de geração de boletos para checar
     * por parâmetros mandatórios que devem ser passados ao método principal.
     *
     * @access  private
     * @return  void
     * @see     _mostraErro()
     */
    function _checaParametrosMandatorios()
    {
        if ((!isset($this->opcoes["valor_documento"])) || (empty($this->opcoes["valor_documento"]))) {
            $this->_mostraErro("Erro: Parâmetro 'valor_documento' não encontrado.");
        }
        if ((!isset($this->opcoes["nosso_numero"])) || (empty($this->opcoes["nosso_numero"]))) {
            $this->_mostraErro("Erro: Parâmetro 'nosso_numero' não encontrado.");
        }
    }
}
?>