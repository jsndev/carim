<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | phpBoleto v2.0                                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 1999-2001 Pablo Martins F. Costa, Jo�o Prado Maia      |
// +----------------------------------------------------------------------+
// | Este arquivo est� sujeito a vers�o 2 da GNU General Public License,  |
// | que foi adicionada nesse pacote no arquivo COPYING e est� dispon�vel |
// | pela Web em http://www.gnu.org/copyleft/gpl.txt                      |
// | Voc� deve ter recebido uma c�pia da GNU Public License junto com     |
// | esse pacote; se n�o, escreva para:                                   |
// |                                                                      |
// | Free Software Foundation, Inc.                                       |
// | 59 Temple Place - Suite 330                                          |
// | Boston, MA 02111-1307, USA.                                          |
// +----------------------------------------------------------------------+
// | Autores: Jo�o Prado Maia <jpm@phpbrasil.com>                         |
// |          Pablo Martins F. Costa <pablo@users.sourceforge.net>        |
// +----------------------------------------------------------------------+
//
// @(#) $Id: class.comum.php,v 1.1 2007/01/18 19:43:35 leonardo.kopp Exp $
//


/**
 * A classe Boleto_Comum � herdada por todas as outras classes de gera��o de
 * modelos de boletos, e simplesmente disponibiliza alguns m�todos 
 * compartilhados por essas classes, como a gera��o da barra de c�digos e 
 * tamb�m a grava��o / remo��o de imagens tempor�rias usadas na cria��o dos
 * boletos.
 *
 * @version  2
 * @author   Jo�o Prado Maia <jpm@phpbrasil.com>
 */

include_once(BOLETO_INC_PATH . "class.grava_erro.php");

class Boleto_Comum
{
    /**
     * Largura padr�o para o c�digo de barras
     * @var int
     */
    var $largura_barra = 1;

    /**
     * Altura padr�o para o c�digo de barras
     * @var int
     */
    var $altura_barra = 65;

    /**
     * M�todo usado para 'adivinhar' o nome da classe do layout de gera��o de
     * boletos de um banco. Como o nome pode ser qualquer um para o nome de um
     * banco, e queremos evitar criar mais uma limita��o para poss�veis 
     * contribui��es para o c�digo do phpBoleto, preferimos adivinhar o nome da
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
     * M�todo de gera��o da barra de c�digo usada nos v�rios modelos de boleto.
     * Ela ir� por sua vez chamar um m�todo privado para rodar o algoritmo
     * apropriado de cria��o das barras para cada modelo.
     *
     * @access  private
     * @param   string $codigo O c�digo (n�mero de 44 digitos normalmente) que � 
     *                         usado na gera��o da barra de c�digo.
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
     * M�todo usado para gravar uma imagem tempor�ria. Ele � usado na gera��o 
     * de boletos em formato PDF e tamb�m no envio de boletos por email.
     *
     * @access  private
     * @param   string $imagem_tipo O tipo da imagem (JPEG, GIF, PNG, WBMP)
     * @param   array $formatos Vetor possuindo a lista de formatos de imagem
     *                          dispon�veis nessa instala��o do PHP
     * @param   int $imagem Par�metro contendo o identificador de imagem retornado
     *                      por ImageCreate() ou fun��es similares.
     * @return  string O caminho relativo para a imagem tempor�ria
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
            GravaErro::grava("Formato de imagem desconhecido ou n�o suportado ('$sufixo')", __FILE__, __LINE__);
        }
    }

    /**
     * M�todo usado para remover a imagem tempor�ria criada pelo m�todo 
     * _gravaImagemTemporaria().
     *
     * @access  private
     * @param   string $temp_imagem Caminho relativo para a imagem a ser removida
     * @return  void
     */
    function _removeImagemTemporaria($temp_imagem)
    {
        if (!@unlink($temp_imagem)) {
            GravaErro::grava("Remo��o do arquivo '$temp_image' n�o foi completado corretamente", __FILE__, __LINE__);
        }
    }

    /**
     * Inicializa uma vari�vel para um valor pre-determinado se a mesma n�o 
     * existe. Importante para os par�metros opcionais de gera��o din�mica do 
     * boleto, j� que em alguns casos do n�vel do error_reporting() sendo alto,
     * o PHP ir� mostrar uma mensagem de erro para refer�ncias a vari�veis que
     * n�o foram declaradas, o que � exatamente esse caso de par�metros 
     * opcionais.
     *
     * @access  private
     * @param   mixed $variavel Refer�ncia � vari�vel que est� a se checar
     * @param   mixed $valor Valor para a vari�vel se a mesma n�o existir
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
     * M�todo usado pelos v�rias classes de gera��o de boletos para checar
     * por par�metros mandat�rios que devem ser passados ao m�todo principal.
     *
     * @access  private
     * @return  void
     * @see     _mostraErro()
     */
    function _checaParametrosMandatorios()
    {
        if ((!isset($this->opcoes["valor_documento"])) || (empty($this->opcoes["valor_documento"]))) {
            $this->_mostraErro("Erro: Par�metro 'valor_documento' n�o encontrado.");
        }
        if ((!isset($this->opcoes["nosso_numero"])) || (empty($this->opcoes["nosso_numero"]))) {
            $this->_mostraErro("Erro: Par�metro 'nosso_numero' n�o encontrado.");
        }
    }
}
?>