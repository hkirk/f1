<?php
/**
 * Description of SQL
 *
 * @author Henrik Kirk
 * @version 1.0
 * @category database
 * @copyright 2009 Henrik Kirk
 */
interface SQL {
  /**
   * Makes sql failed note
   * 
   * @return string
   **/
  public function sql_failed($sql, $line, $file);

  /**
   * Makes the SQL query and possibly takes care of the Error handling.
   * @param String $sql query.
   * @param Connection $con sql connection.
   * @param int $line where sql query is called from.
   * @param String $file where sql query is called from
   * @param bool $print true if this method should print error messages.
   * @return sql result.
   **/
  public function sql_query($sql, $line=null, $file=null, $print=false);

  /**
   * Eqscaupe a string, so SQL injection isn't possible.
   * @param String $str sql query.
   * @return SQL eqscaped string.
   */
  public function sql_escape_string($str);

  /**
   * Returns on row of the result set from the result query.
   * @param SQL result $result of the query you want to fetch a row from.
   * @return associativ array containing a row from query.
   */
  public function sql_fetch_assoc($result);

  /**
   * Return number of rows in the result set.
   * @param SQL result $result of the query in question.
   * @return int number of rows in result set.
   */
  public function sql_num_rows($result);

  /**
   * Connection to sql database.
   * @param String $host on which datbase is running.
   * @param String $user username.
   * @param String $passwd password of user.
   */
  public function sql_connect($host, $user, $passwd);

  /**
   * Function that select the relevant database.
   * @param String $database
   */
  public function sql_select_db($database);

  /**
	 * Return array from the given result query.
	 * @param DB result $result of query.
	 * @return array with content directly from database.
	 */
  public function sql_fetch_array($result);

  /**
	 * Closes the connection.
	 */
  public function sql_close();

  /**
	 * Returns an a row from the given SQL query.
	 * @param DB result $result of query.
	 * @return array where content is given from the query and contains a single
	 * from the query.
	 */
  public function sql_fetch_row($result);

  /**
   * Return the number of affected rows;
   * @param $result DB result of given query
   * @return int number of affected rows;
   */
  public function sql_affected_rows($result=null);

  public function startTransaction($line, $file);

  public function commit($line, $file);

  public function rollback($line, $file);

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
  public function transactions($sqls, $line, $file, &$content);

  /**
   * Converts a unix timestamp (miliseconds since epoke) to a database specific
   * string.
   *
   * @param int $time unix timestamp.
   * @return string $time converted to database
   */
  public function unixTimeToDBTime($time);

  /**
   * Return the id of the lastest insert query.
   * 
   * @return int the last inserted id.
   */
  public function sql_insert_id();
}
?>
