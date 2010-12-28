<?php
/**
 * MySQL implementation of SQL interface.
 *
 * @author Henrik Kirk
 * @version 1.0
 * @category database
 * @copyright 2009 Henrik Kirk
 */
class MySQL implements SQL {
  /**
   * @var resource the database connection
   */
  private $con;

  /**
   * Constructor.
   * 
   * @global string $db_host database host.
   * @global string $db_username database username.
   * @global string $db_password database user password.
   * @global string $db_name database name.
   */
  public function  __construct() {
    global $db_host, $db_username, $db_password, $db_name;

    $this->sql_connect($db_host, $db_username, $db_password);
    $this->sql_select_db($db_name);
  }
  
  /**
   * Makes mysql_failed node
   * Note: if $print is true, this function print error string as a sideeffect.
   *
   * @param string $sql sql query.
   * @param int $line line number where sql_failed was called from.
   * @param string $file file where sql_failed was called from.
   * @param boolean $print true if this function should print the error
   * statement.
   * @return error string for presentation.
   */
  public function sql_failed($sql, $line, $file, $print=false) {
    $str = _QUERYERR . ": " . htmlentities($sql)
      ."<br/>"
      . mysql_error($this->con) . " "
      . _ERRFROM . " " . $file . " " . _INLINE . " " . $line
      . Content::getLineShift();
    if($print) {
      echo $str;
    }
    return $str;
  }

  /**
   * Makes the SQL query and possibly takes care of the Error handling.
   * @param String $sql query.
   * @param int $line where sql query is called from.
   * @param String $file where sql query is called from
   * @param bool $print true if this method should print error messages.
   * @return mysql result.
   **/
  public function sql_query($sql, $line=null, $file=null, $print=false) {
    $result = mysql_query($sql, $this->con);
    if(!$result && $print) {
      $this->sql_failed($sql, $line, $file, $print);
    } else {
      return $result;
    }
    return false;
  }

  /**
   * Eqscaupe a string, so SQL injection isn't possible.
   * @param String $str sql query.
   * @return SQL eqscaped string.
   */
  public function sql_escape_string($str) {
    return @mysql_escape_string($str);
  }

  /**
   * Returns on row of the result set from the result query.
   * @param SQL result $result of the query you want to fetch a row from.
   * @return associativ array containing a row from query.
   */
  public function sql_fetch_assoc($result) {
    return @mysql_fetch_assoc($result);
  }

  /**
   * Return number of rows in the result set.
   * @param SQL result $result of the query in question.
   * @return int number of rows in result set.
   */
  public function sql_num_rows($result) {
    return @mysql_num_rows($result);
  }

  /**
   * Connection to sql database.
   * @param String $host on which datbase is running.
   * @param String $user username.
   * @param String $passwd password of user.
   */
  public function sql_connect($host, $user, $passwd) {
    $this->con = mysql_connect($host, $user, $passwd);
    if (!$this->con) {
      echo _SERERR;
      exit(-1);
    }

  }

  /**
   * Function that select the relevant database.
   * @param String $database
   * @return array with content from database.
   */
  public function sql_select_db($database) {
    return @mysql_select_db($database, $this->con);
  }

	/**
	 * Return array from the given result query.
	 * @param DB result $result of query.
	 * @return array with content directly from database.
	 */
  public function sql_fetch_array($result) {
    return @mysql_fetch_array($result);
  }

	/**
	 * Closes the connection.
	 */
  public function sql_close() {
    mysql_close($this->con);
  }

	/**
	 * Returns an a row from the given SQL query.
	 * @param DB result $result of query.
	 * @return array where content is given from the query and contains a single
	 * from the query.
	 */
  public function sql_fetch_row($result) {
    return @mysql_fetch_row($result);
  }

  /**
   * Return the number of affected rows;
   * @param $result DB result of given query
   * @return int number of affected rows;
   */
  public function sql_affected_rows($result=null) {
    if($result == null) {
      return mysql_affected_rows();
    } else {
      return mysql_affected_rows($result);
    }
  }

  /**
   * Start a transaction.
   *
   * @param int $line the line number this method is called from.
   * @param string $file the file this method is called from.
   */
  public function startTransaction($line, $file)  {
    $transaction = "START TRANSACTION";
    $this->sql_query($transaction, $line, $file, true);
  }

  /**
   * Commit the queries in a transaction.
   *
   * @param int $line the line number this method is called from.
   * @param string $file the file this method is called from.
   */
  public function commit($line, $file)  {
    $commit = "COMMIT";
    $this->sql_query($commit, $line, $file, true);
  }

  /**
   * Rollback the queries in a transaction.
   *
   * @param int $line the line number this method is called from.
   * @param string $file the file this method is called from.
   */
  public function rollback($line, $file)  {
    $rollback = "ROLLBACK";
    $this->sql_query($rollback, $line, $file, true);
  }

  /**
   * Start a transaction, execute the sql statements, if any statement reports
   * an error, the transaction is rolledback, otherwise it is committed.
   * 
   * @param array $sqls the sql statements to execute in this transaction.
   * @param int $line the line number in $file.
   * @param string $file the file caling this function.
   * @param string &$content pointer to a string.
   * @return boolean true if committed, false otherwise.
   */
  public function transactions($sqls, $line, $file, &$content) {
    $retval = 1;

    $this->startTransaction($line, $file);

    foreach($sqls as $sql) {
      $result = $this->sql_query($sql, $line, $file);
      if(!$result) {
        $content .= $this->sql_failed($sql, $line, $file);
      }
      if($this->sql_affected_rows() == 0) {
        $retval = 0;
      }
    }

    if($retval == 0) {
      $this->rollback($line, $file);
      return false;
    } else {
      $this->commit($line, $file);
      return true;
    }
  }

  /**
   * Converts a unix timestamp (miliseconds since epoke) to a MySQL specific
   * string.
   *
   * @param int $time unix timestamp.
   * @return string $time converted to database
   */
  public function unixTimeToDBTime($time) {
    return date("Y-m-d", $time);
  }

  /**
   * Return the id of the lastest insert query.
   *
   * @return int the last inserted id.
   */
  public function sql_insert_id() {
    return mysql_insert_id($this->con);
  }

}

?>
