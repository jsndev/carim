<?php
/**
* Contains methods for working with Ini-files
*
* The class represents a API for handling generic-styled Ini-Files
* often used under Unix and Windows
* The following style for the file is required:
* Comments are ignored. Comments required a leading
* ;, //, # or ' for every line of comment
* Key=Value, to mark a key-value-pair. Only one per line. If a key
* exists more then once, only the last value defined in the file will be used.
* [BLOCKNAME] marks a block of key-value-pairs, which belong together.
* If two blocks contains the same key-value-pair, the second value will not override
* the first value
*
* @author      Alexander Merz <alexander.merz@web.de>
* @access      public
* @version     ini.php,v1.0 2000/03/09
* @package     File
*/

require_once "PEAR.php";

class File_Ini {
    /**
    * contains the Commentchars req. for RegExp's
    * @var string
    */
    var $cc = "#|;|\/\/|'";

    /**
    * contains the zero-or-more Commentchars req. for RegExp's
    * @var string
    */
    var $cczm = "#*|;*|\/\/*|'*";

    /**
    * contains the Commentchars in a char-class req. for RegExp's
    * @var string
    */
    var $ccc = "#;\/\/'";

    /**
    * Enable or disable Writecache
    * @var boolean
    */
    var $nocache = TRUE;

    /**
    * Preserves the filecontent
    * @var boolean
    */
    var $preserve = TRUE;

    /**
    * Type of Line-Ending Unix: \n Win: \r\n
    * @var string
    */
    var $eol = "\n";

    /**
    * name of the file
    * @var string
    */
    var $inifile = "";

    /**
    * collection of all key-value-pairs
    * @var array
    */
    var $inicoll = array();

    /**
    * contains the last PEAR_Error
    * @var object PEAR_Error
    */
    var $error;

    /**
    * Constructor
    *
    * The filename must fetch a existing file. If filename contains no path, then
    * "./" will be added.
    *
    * @access public
    * @param string $filename  Name of the Inifile
    * @param string $cc        Commentchar
    * @return object File_Ini
    */
    function File_Ini($filename = "", $cc = "")
    {
        if (!empty($cc)) {
            $this->cc = $cc;
            $this->ccc = $cc;
            $this->cczm = $cc . "*";
        }

        if (!empty($filename)) {
            if (!preg_match('/\/|\\\/', $filename)) {
                $filename = './' . $filename;
            }
            $this->inifile = $filename;

            if (file_exists($this->inifile)) {
                $fp = fopen($this->inifile, 'r');
                if (!$fp) {
                    $this->error = new PEAR_Error("File " . $this->inifile . " couldn't be open", 31, PEAR_ERROR_RETURN, null, null);
                    return 0;
                }
                $ret = $this->parseIniColl();
                if (PEAR::isError($ret)) {
                    $this->error = $ret;
                    return 0;
                }
                fclose($fp);
            } else {
                $this->error = new PEAR_Error("File " . $this->inifile . " not found", 2, PEAR_ERROR_RETURN, null, null);
                return 0;
            }

            if (strpos(PHP_OS, "WIN") !== FALSE) {
                $this->eol = "\r\n";
            } else {
                $this->eol = "\n";
            }
        }
    }

    /**
    * Parses the file and fill the IniColl(ection)
    * Returns 0  - for success or a PEAR_Error-Object
    * @access private
    * @return int
    */
    function parseIniColl()
    {
        $ret = 0;
        $buffer = array();
        $blockname = "";
        $buffer = file($this->inifile);

        for ($line = 0; $line < count($buffer); $line++) {
            if (isset($buffer[$line])) {
                // Killing all Comments
                list($nocom) = preg_split("/(\S)*(" . $this->cc . ")(\S*)/", trim($buffer[$line]), 1);
                if (!empty($nocom)) {
                    // Toogle between Block and KV-Pair
                    if (preg_match("/\[(.*)\]/", $nocom, $match)) {
                        // its a block
                        $blockname = $match[1];
                    } else {
                        list($key, $value) = explode("=", trim($nocom));
                        $key = trim($key);
                        $value = ltrim($value);
                        $this->inicoll["$blockname\n$key"] = $value;
                    }
                }
            }
        }
        if ($line == 0) {
            $ret = new PEAR_Error("File " . $this->inifile . " is empty", 32, PEAR_ERROR_RETURN, null, null);
            $this->error = $ret;
        }
        return $ret;
    }

    /**
    * Returns the value of the key, if key not exists, a default value will returned.
    * If a key not exists and no default value is given, FALSE will be returned.
    * If a default value is returned, it is also written to the IniColl using setIniValue
    *
    * @access public
    * @param string $block
    * @param string $key
    * @param string $default
    * @return string
    */
    function getIniValue($block = "", $key, $default = "")
    {
        if (isset($this->inicoll["$block\n$key"])) {
            return $this->inicoll["$block\n$key"];
        } else {
            if (isset($default) && !empty($default)) {
                $this->setIniValue($block, $key, $default);
                return $default;
            }
        }
        return false;
    }

    /**
    * Returns all Ini-KV-pairs in a asoz. Array ( $hash[key] = value ). If no $filename
    * given, $this -> inifile will be used. If $filename exists it will override
    * $this -> inifile. On Error a PEAR_Error will returned.
    *
    * @access public
    * @param string $filename
    * @param string $cc      Commentchar
    * @return array
    */
    function getValues($filename = "", $cc = "")
    {
        if (!empty($filename)) {
            if (!preg_match('/\/|\\\/', $filename)) {
                $filename = './' . $filename;
            }
            $this->inifile = $filename;
            if (!file_exists($this->inifile)) {
                $this->error = new PEAR_Error("File " . $this->inifile . " couldn't be open", 31, PEAR_ERROR_RETURN, null, null);
                return $this->error;
            }
            if (!empty($cc)) {
                $this->setCC($cc);
            }
            $ret = $this->parseIniColl();
        }
        if (PEAR::isError($ret)) {
            $this->error = $ret;
            return $ret;
        }
        $inicoll = $this->inicoll;
        foreach ($inicoll as $key => $value) {
            list(,$key) = split("\n", $key);
            $an[$key] = $value;
        }
        return $an;
    }

    function getAllBlockValues($id_key='', $function_name='')
    {
        $arr = array();
        $blocks = $this->getBlocknames();
        for ($i = 0; $i < count($blocks); $i++) {
            $arr[$i] = $this->getBlockValues($blocks[$i], $function_name);
            if ($id_key != '') {
                $arr[$i][$id_key] = $blocks[$i];
            }
        }
        return $arr;
    }

    function removeBlocks($blocks)
    {
        for ($i = 0; $i < count($blocks); $i++) {
            reset($this->inicoll);
            while (list($key, $value) = each($this->inicoll)) {
                list($block_name, $key_name) = explode("\n", $key);
                if ($block_name === $blocks[$i]) {
                    unset($this->inicoll["$block_name\n$key_name"]);
                }
            }
        }
        if ($this->nocache) {
            $old_option = $this->preserve;
            $this->preserve = false;
            $this->write();
            $this->preserve = $old_option;
        }
    }

    /**
    * Returns all Ini-KV-pairs in a asoz. Array ( $hash[key] = value ) from the
    * block $block.
    *
    * @access public
    * @param string $block
    * @return array
    */
    function getBlockValues($block, $function_name='')
    {
        $an = array();
        foreach ($this->inicoll as $key => $value) {
            list($blk, $key) = split("\n", $key);
            if ($blk == $block) {
                if ($function_name == 'bin2asc') {
                    $an[$key] = $this->_bin2asc($value);
                } else {
                    $an[$key] = $value;
                }
            }
        }
        return $an;
    }

    /**
    * Returns a array containing all Blocknames
    *
    * @access public
    * @return array
    */
    function getBlocknames()
    {
        $an = array();
        $z = 0;
        foreach ($this->inicoll as $key => $value ) {
            list($blk) = split("\n", $key);
            if (!in_array($blk, $an)) {
                $an[$z] = $blk;
                $z++;
            }
        }
        return $an;
    }

    /**
    * Show all KV_pairs in a table -> used it for debug purposes
    *
    * @access public
    */
    function IniInfo()
    {
        echo '<table border="1" align="center" width="75%">';
        echo '<tr><td colspan="2"><h2>FILE_Ini</h2>Version: 0.9a, by Alexander Merz (heavily modified by João Prado Maia)</td></tr>';
        echo '<tr><td align="center" colspan="2" bgcolor="silver"><b>' . $this->inifile . '</b></td></tr>';
        $a = $this->getBlocknames();
        foreach ($a as $v) {
            $ba = $this->getBlockValues($v);
            echo '<tr><td colspan="2" bgcolor="#FFFF80">[<b>' . $v . '</b>]</td></tr>';
            echo '<tr><td align="center">Key</td><td align="center">Value</td></tr>';
            foreach ($ba as $k => $va) {
                echo "<tr><td><i>$k</i></td><td><font color=\"blue\">$va</font></td></tr>";
            }
        }
        echo '</table>';
    }

    /**
    * Enables ( "On" ) or disable ("Off") the Write-Cache
    * @param string $onoff
    * @access public
    */
    function enableCache($onoff = "On")
    {
        if (strtolower($onoff) == "on") {
            $this->nocache = FALSE;
        } else {
            $this->nocache = TRUE;
        }
    }

    /**
    * Sets a new $value of the $key in the $block
    * if $key and/or $block not exist it will be created. Returns $value or
    * a PEAR_Error-Object.
    *
    * @access public
    * @param string $block
    * @param string $key
    * @param string $value
    * @return string
    */
    function setIniValue($block = "", $key, $value)
    {
        $this->inicoll["$block\n$key"] = $value;
        if ($this->nocache) {;
            $this->write();
        }
        return $value;
    }

    function setIniValuesArray($block, $items)
    {
        if (count($items) > 0) {
            @reset($items);
            while (list($key, $value) = each($items)) {
                $this->inicoll["$block\n" . $key] = trim($value);
            }
            if ($this->nocache) {
                $this->write();
            }
            return true;
        }
        return false;
    }

    /**
    * Writes the IniCollection to $file or is $file not given to $this -> inifile
    * @param string $filename
    * @return object PEAR_Error
    */
    function write($filename = "")
    {
        if ($this->preserve) {
            return $this->writePreserve($filename);
        }
        if (empty($filename)) {
            $filename = $this->inifile;
        }

        $lastblock = "";
        $fh = fopen($filename, "w");
        if (!$fh) {
            $this->error = new PEAR_Error("File " . $this->inifile . " couldn't open for writing", 33, PEAR_ERROR_RETURN, null, null);
            return $this->error;
        }

        foreach ($this->inicoll as $blockkey => $value) {
            if (!empty($blockkey)) {
                list($block, $key) = split("\n", $blockkey);
                if ($block != $lastblock) {
                    $lastblock = $block;
                    fwrite($fh, "[$lastblock]" . $this->eol);
                }
                fwrite($fh, "$key=$value" . $this->eol);
            }
        }
        fclose($fh);

        $file = $this->inifile;
        $this->inifile = $filename;
        if (PEAR::isError($this->parseIniColl())) {
            $this->error = new PEAR_Error("Inifile couldn't be parsed again - seems to be a bug!", 34, PEAR_ERROR_RETURN, null, null);
            return $this->error;
        }
        $this->inifile = $file;
    }

    /**
    * Writes the IniCollection to $file or is $file not given to $this -> inifile.
    * Tries(!) to preserve the format of the original file.
    * @param string $filename
    * @return object PEAR_Error
    */
    function writePreserve($filename = "")
    {
        if (empty($filename)) {
            $filename = $this->inifile;
        }

        // $comment = "";
        // $compattern = "/^(;|#|'|\/\/)/";
        $blockname = "";
        if (!file_exists($this->inifile)) {
            $this->error = new PEAR_Error("File " . $this->inifile . " couldn't open for writing", 33, PEAR_ERROR_RETURN, null, null);
            return $this->error;
        }

        $buffer = file($this->inifile);
        $fh = fopen($filename, "w");
        for ($line = 0; $line <= count($buffer); $line++) {
            $zeile = @ltrim($buffer[$line]);

            // Chech the Type of line
            // 3 : blank line
            // 1 : Comment only
            // 2 : Block + opt. Comment
            // 0 : K=V + opt. Comment
            if (preg_match("/^(" . $this->cc . ")(\S*)/", $zeile)) {
                $type = 1;
            } elseif (preg_match("/(((\S*)|(\s*))=((\S*)|(\s*)))((" . $this->cc . ")(\S*))*/", $zeile)) {
                $type = 0;
            } elseif (preg_match("/(\[([\S ]*)\])((" . $this->cc . ")(\S*))*/", $zeile, $match)) {
                $type = 2;
            } elseif (empty($zeile)) {
                $type = 3;
            }

            switch ($type)
            {
              case 0:
                  preg_match("/(\S*)(\s*)=(\s*)([^" . $this->ccc . "]*)(" . $this->cczm . ")(.*)/", $buffer[$line], $match);
                  $z = $match[4];

                  $endl = strlen($z) - strlen(trim($z));
                  $space = "";
                  if ($endl) {
                      $space = str_repeat(" ", $endl);
                  }
                  $key = $blockname . "\n" . $match[1];
                  $tofile = $match[1] . $match[2] . "=" . chop($match[3]) . $this->inicoll[$key] . $space . $match[5] . $match[6];
                  if (isset($match[7])) {
                      $tofile .= $match[7];
                  }
                  $tofile = chop($tofile) . $this->eol; // Don't think about it - its seem to be a bugfix of a eol-bug
                  fwrite($fh, $tofile);
                  unset($this->inicoll[$key]);
                  break;
              case 2:
                  fwrite($fh, chop($buffer[$line]) . $this->eol);
                  // write new keys which are not found in the original file but belong
                  // to a existing block in the file
                  $kv = $this->getBlockValues($blockname);
                  if (count($kv) > 0) {
                      foreach ($kv as $k => $v) {
                          fwrite($fh, $k . "=" . chop($v) . $this->eol);
                          unset($this->inicoll["$blockname\n$k"]);
                      }
                  }
                  $blockname = $match[2];
                  break;
              case 1:
              case 3:
                  if (isset($buffer[$line])) {
                      fwrite($fh, chop($buffer[$line]) . $this->eol);
                  }
                  break;
            }
        }

        // write the rest now (org. file contains didnt' contained the blocks and keys
        // belong to them
        if (count($this->inicoll) > 0) {
            $getBlocknames = $this->getBlocknames();
            foreach ($getBlocknames as $block) {
                fwrite($fh, "[$block]" . $this->eol);
                $kv = $this->getBlockValues($block);
                foreach ($kv as $k => $v) {
                    fwrite($fh, "$k=$v" . $this->eol);
                    unset($this->inicoll["$block\n$k"]);
                }
            }
        }
        fclose($fh);

        $file = $this->inifile;
        $this->inifile = $filename;
        $this->parseIniColl();
        if (PEAR::isError($this->parseIniColl())) {
            $this->error = new PEAR_Error( "Inifile couldn't be parsed again - seems to be a bug!", 34, PEAR_ERROR_RETURN, null, null);
            return $this->error;
        }
        $this->inifile = $file;
    }

    /**
    * Enables ( "On" ) or disable ("Off") the preserving of the Not-KVP in the inifile
    * @param string $onoff
    * @access public
    */
    function enablePreserve($onoff = "On")
    {
        if ($onoff == "On") {
            $this->preserve = TRUE;
        } else {
            $this->preserve = FALSE;
        }
    }

    /**
    * Save the IniColl to $filename (or when not given to $this -> inifile)
    * If $preserve = "on", it will try to preserve the original File format, for not
    * preserving behavoir use "off"
    * @access public
    * @param string $filename
    * @param string $preserve
    * @return object PEAR_Error
    */
    function save($filename = "", $preserve = "on")
    {
        if (empty($filename)) {
            $filename = $this->inifile;
        }
        if ($preserve == "on") {
            if(PEAR::isError($ret = $this->writePreserve($filename))) {
                $this->error = $ret;
                return $ret;
            }
        } else {
            if (PEAR::isError($ret = $this->write($filename))) {
                $this->error = $ret;
                return $ret;
            }
        }
    }

    /**
    * Set a special Comment-Char, only this one will be used
    * Care about correct escaping (ex: '\/' instead of '/')! The $cc will be used in RegExp's.
    * if $only true $this -> inifile will parsed again
    * @access public
    * @param string $cc
    * @param boolean $only
    * @return object PEAR_Error
    */
    function setCC($cc, $only = false)
    {
        $this->cc = $cc;
        $this->ccc = $cc;
        $this->cczm = $cc . "*";

        if ($only) {
            if (PEAR::isError($this->parseIniColl())) {
                $this->error = new PEAR_Error( "Inifile couldn't be parsed again - seems to be a bug!", 34, PEAR_ERROR_RETURN, null, null);
                return $this->error;
            }
        }
    }
    
    /**
    * Returns a PEAR_Error, if there was a Error, else 0
    * @access public
    * @return object PEAR_Error
    */
    function getError()
    {
        return $this->error;
    }

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