<?
class database {

//
// Class attributes
//
	var $connid;  // resource | boolean: stores connection id when connected into database
	var $dbtype;  // string:             defines database type - see comments on _setDbType() method.
	var $dbuser;  // string:             defines database user
	var $dbpass;  // string:             defines database password
	var $dbname;  // string:             defines the database name
	var $dbhost;  // string:             defines the database name

	var $query;   // string:             query to be executed

	var $qrdata;  // array | boolean:    stores last resultset or false in case of error
	var $qrcount; // int   | boolean:    stores number of rows returned in current resultset

	var $insertId;
	
	var $errno;
	var $errdesc;
	
	function database() {
		$this->_setParameters();
		$this->opendb();
	}
	
//
// Public methods
//
	function query() {
		$GLOBALS["__NUMQUERYS"]++;
		$this->setError((int)0,(string)"");
		$this->setInsertId(false);
		$bReturn = $this->query ? $this->_transQuery() : false;
		$GLOBALS["__EXECQUERYS"][] = $this->query."<br />".mysql_errno()." - ".mysql_error()."<hr />";
		return $bReturn;
	}

	function opendb() {
		return $this->_mysqlOpen();
	}
	
	function foundRows () {
		$this->query = "SELECT FOUND_ROWS() as registros";
		$this->query();
		return $this->qrdata[0]["registros"];
	}
	
	function beginTransaction() {
		return @mysql_query("BEGIN", $this->connid);
	}

	function rollbackTransaction() {
		return @mysql_query("ROLLBACK", $this->connid);
	}

	function commitTransaction() {
		return @mysql_query("COMMIT", $this->connid);
	}

//
// Database Transition Methods
//

	function _transQuery() {
		$this->_setParameters();
		unset($this->qrcount);
		unset($this->qrdata);
		if (!$this->connid) {
			if (!$this->opendb()) {
				return false;
			}
		}

		$queryType = $this->_parseQueryType();

		if ($queryType === false) {
			return false;
		}

		return $this->_mysqlExecute($queryType);
	}

//
// Database specific methods
//

######## MySQL METHODS #########
	function _mysqlExecute($queryType) {
		switch($queryType) {
			case "select":
				return $this->_mysqlQuerySelect();
			break;
			case "insert":
				return $this->_mysqlQueryInsert();
			break;
			case "update":
				return $this->_mysqlQueryUpdate();
			break;
			case "delete":
				return $this->_mysqlQueryDelete();
			break;
			case "replace":
				return $this->_mysqlQueryReplace();
			break;
			case "alter":
				return true;
			break;
			default:
				$this->setError(9999,"Consulta SQL inválida.");
				return false;
			break;
		}
	}

	function _mysqlQuerySelect() {
		unset($dummyQuery);
		if ($this->query) {
			if (!($dummyQuery = @mysql_query($this->query, $this->connid))) {
				$this->qrcount = false;
				$this->setError();
				return false;
			}
			while($resultsArray = @mysql_fetch_assoc($dummyQuery)) {
				$this->qrdata[] = $resultsArray;
			}
			$this->qrcount = is_array($this->qrdata) ? count($this->qrdata) : false;
			return true;
		} else {
			$this->qrcount = false;
			$this->setError(9999,"Consulta SQL inválida.");
			return false;
		}
	}

	function _mysqlQueryInsert() {
		unset($dummyQuery);
		if ($this->query) {
			$dummyResult = mysql_query($this->query, $this->connid);
			if (!$dummyResult) {
				$this->qrcount = false;
				$this->setError();
				return false;
			}
			$this->qrcount = mysql_affected_rows($this->connid);
			$this->setInsertId(mysql_insert_id($this->connid));
			return true;
		} else {
			$this->setError(9999,"Consulta SQL inválida.");
			return false;
		}
	}

	function _mysqlQueryUpdate() {
		unset($dummyQuery);
		if ($this->query) {
			$dummyResult = @mysql_query($this->query, $this->connid);
			if (!$dummyResult) {
				$this->qrcount = false;
				$this->setError();
				return false;
			}
			$this->qrcount = @mysql_affected_rows($this->connid);
			return true;
		} else {
			$this->qrcount = false;
			if (@mysql_errno($this->connid) != "") {
				$this->setError();
				return false;
			}
			return true;
		}
	}
	
	function _mysqlQueryReplace() {
		unset($dummyQuery);
		if ($this->query) {
			$dummyResult = @mysql_query($this->query, $this->connid);
			if (!$dummyResult) {
				$this->qrcount = false;
				$this->setError();
				return false;
			}
			$this->qrcount = @mysql_affected_rows($this->connid);
			return true;
		} else {
			$this->qrcount = false;
			if (@mysql_errno($this->connid) != "") {
				$this->setError();
				return false;
			}
			return true;
		}
	}

	function _mysqlQueryDelete() {
		unset($dummyQuery);
		if ($this->query) {
			$dummyResult = @mysql_query($this->query, $this->connid);
			if (!$dummyResult) {
				$this->setError();
				return false;
			}
			$this->qrcount = @mysql_affected_rows($this->connid);
			return true;
		} else {
			$this->qrcount = false;
			$this->setError(9999,"Consulta SQL inválida.");
			return false;
		}
	}

	function _mysqlOpen() {
		global $___GBConn;
		if (!$___GBConn) {
			$___GBConn = @mysql_connect($this->dbhost, $this->dbuser, $this->dbpass);
		}
		
		$ret = mysql_select_db($this->dbname, $___GBConn);
		$this->connid = $___GBConn;
		if (!$ret) {
			$this->setError();
		}
		//return (mysql_select_db($this->dbname)) ? true : false;
		return $ret;
	}

#######################################
//
// Commom methods
//
	function _parseQueryType() {
		if ($this->query) {
			$tmpcount = true;
			while($tmpcount){
				if (ord(substr($this->query, 0,1)) == 9 || ord(substr($this->query, 0,1)) == 10 || ord(substr($this->query, 0,1)) == 13 || ord(substr($this->query, 0,1)) == 32) {
					$this->query = substr($this->query, 1);
				} else {
					$tmpcount = false;
				}
			} // while

			if (eregi("^SELECT", $this->query)) {
				return "select";
			} elseif (eregi("^INSERT", strtoupper($this->query))) {
				return "insert";
			} elseif (eregi("^UPDATE", strtoupper($this->query))) {
				return "update";
			} elseif (eregi("^DELETE", strtoupper($this->query))) {
				return "delete";
			} elseif (eregi("^ALTER", strtoupper($this->query))) {
				return "alter";
			} elseif (eregi("^REPLACE", strtoupper($this->query))) {
				return "replace";
			} else {
				$this->setError(9999,"Consulta SQL inválida.");
				return false;
			}
		} else {
			$this->setError(9999,"Consulta SQL inválida.");
			return false;
		}
	}

	function _setParameters() { // set parameters for this class
		// includes default configuration file
		if (file_exists("class/db.config.php")) {
			include_once "class/db.config.php";
		}

		// defines dbuser and dbpass
		$this->_setDbUser();
		// defines dbname
		$this->_setDbName();
		// defines dbhost
		$this->_setDbHost();
	}

	function _setDbUser() {
		$this->dbuser = $this->dbuser ? $this->dbuser : (dbuser ? dbuser : "");
		$this->dbpass = $this->dbpass ? $this->dbpass : (dbpass ? dbpass : "");
		return true;
	}

	function _setDbName() {
		$this->dbname = $this->dbname ? $this->dbname : (dbname ? dbname : "");
		return true;
	}

	function _setDbHost() {
		$this->dbhost = $this->dbhost ? $this->dbhost : (dbhost ? dbhost : "");
		return true;
	}
	
	function setError($num = false, $desc = false) {
		if ($num || $desc) {
			$this->errno = $num;
			$this->errdesc = $desc;
		} else {
			$this->errno = mysql_errno();
			$this->errdesc = mysql_error();
		}
	}

	function getErrNo() {
		return $this->errno;
	}
	function getErrDesc() {
		return $this->errdesc;
	}
	
	function setInsertId($insertId) {
		$this->insertId = $insertId;
	}
	
	function getInsertId() {
		return $this->insertId;
	}
}
?>
