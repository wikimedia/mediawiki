<?php
/**
 *  Copyright 2016 JetBrains
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Start of mysqli extension stubs v.0.1
 * @link http://php.net/manual/en/book.mysqli.php
 */
/**
 * mysqli_sql_exception
 */
class mysqli_sql_exception extends RuntimeException  {
	/**
	 * The sql state with the error.
	 */
	protected $sqlstate;
}

/**
 * MySQLi Driver.
 * @link http://php.net/manual/en/class.mysqli-driver.php
 */
final class mysqli_driver  {
	/**
	 * @var string
	 */
	public $client_info;
	/**
	 * @var string
	 */
	public $client_version;
	/**
	 * @var string
	 */
	public $driver_version;
	/**
	 * @var string
	 */
	public $embedded;
	/**
	 * @var bool
	 */
	public $reconnect;
	/**
	 * @var int
	 */
	public $report_mode;

}

/**
 * Represents a connection between PHP and a MySQL database.
 * @link http://php.net/manual/en/class.mysqli.php
 */
class mysqli  {
	/**
	 * @var int
	 */
	public $affected_rows;
	/**
	 * @var string
	 */
	public $client_info;
	/**
	 * @var int
	 */
	public $client_version;
	/**
	 * @var string
	 */
	public $connect_errno;
	/**
	 * @var string
	 */
	public $connect_error;
	/**
	 * @var int
	 */
	public $errno;
	/**
	 * @var string
	 */
	public $error;
	/**
	 * @var int
	 */
	public $field_count;
	/**
	 * @var string
	 */
	public $host_info;
	/**
	 * @var string
	 */
	public $info;
	/**
	 * @var mixed
	 */
	public $insert_id;
	/**
	 * @var string
	 */
	public $server_info;
	/**
	 * @var int
	 */
	public $server_version;
	/**
	 * @var string
	 */
	public $sqlstate;
	/**
	 * @var string
	 */
	public $protocol_version;
	/**
	 * @var int
	 */
	public $thread_id;
	/**
	 * @var int
	 */
	public $warning_count;

    /**
     * @var array A list of errors, each as an associative array containing the errno, error, and sqlstate.
     * @link http://www.php.net/manual/en/mysqli.error-list.php
     */
    public $error_list;


	/**
	 * Open a new connection to the MySQL server
	 * </p>
	 * @param string $host [optional] Can be either a host name or an IP address. Passing the NULL value or the string "localhost" to this parameter, the local host is assumed. When possible, pipes will be used instead of the TCP/IP protocol. Prepending host by p: opens a persistent connection. mysqli_change_user() is automatically called on connections opened from the connection pool. Defaults to ini_get("mysqli.default_host")
	 * @param string $username [optional] The MySQL user name. Defaults to ini_get("mysqli.default_user")
	 * @param string $passwd [optional] If not provided or NULL, the MySQL server will attempt to authenticate the user against those user records which have no password only. This allows one username to be used with different permissions (depending on if a password as provided or not). Defaults to ini_get("mysqli.default_pw")
	 * @param string $dbname [optional] If provided will specify the default database to be used when performing queries. Defaults to ""
	 * @param int $port [optional] Specifies the port number to attempt to connect to the MySQL server. Defaults to ini_get("mysqli.default_port")
	 * @param string $socket [optional] Specifies the socket or named pipe that should be used. Defaults to ini_get("mysqli.default_socket")
	 * @since 5.0
	 */
	public function __construct (
		$host,
		$username,
		$passwd,
		$dbname,
		$port,
		$socket
	) {}

	/**
	 * Turns on or off auto-commiting database modifications
	 * @link http://php.net/manual/en/mysqli.autocommit.php
	 * @param bool $mode <p>
	 * Whether to turn on auto-commit or not.
	 * </p>
	 * @return bool true on success or false on failure.
	 * @since 5.0
	 */
	public function autocommit ($mode) {}

    /**
     * Starts a transaction
     * @link http://www.php.net/manual/en/mysqli.begin-transaction.php
     * @param int $flags [optional]
     * @param string $name [optional]
     * @return bool true on success or false on failure.
     * @since 5.5.0
     */
    public function begin_transaction ($flags = 0, $name = null) {}

    /**
	 * Changes the user of the specified database connection
	 * @link http://php.net/manual/en/mysqli.change-user.php
	 * @param string $user <p>
	 * The MySQL user name.
	 * </p>
	 * @param string $password <p>
	 * The MySQL password.
	 * </p>
	 * @param string $database <p>
	 * The database to change to.
	 * </p>
	 * <p>
	 * If desired, the null value may be passed resulting in only changing
	 * the user and not selecting a database. To select a database in this
	 * case use the <b>mysqli_select_db</b> function.
	 * </p>
	 * @return bool true on success or false on failure.
	 * @since 5.0
	 */
	public function change_user ($user, $password, $database) {}

	/**
	 * Returns the default character set for the database connection
	 * @link http://php.net/manual/en/mysqli.character-set-name.php
	 * @return string The default character set for the current connection
	 * @since 5.0
	 */
	public function character_set_name () {}

	/**
	 * @deprecated 5.3 This function has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 5.4.0.
	 */
	public function client_encoding () {}

	/**
	 * Closes a previously opened database connection
	 * @link http://php.net/manual/en/mysqli.close.php
	 * @return bool true on success or false on failure.
	 * @since 5.0
	 */
	public function close () {}

	/**
	 * Commits the current transaction
	 * @link http://php.net/manual/en/mysqli.commit.php
	 * @return bool true on success or false on failure.
	 * @since 5.0
	 */
	public function commit () {}

	/**
	 * @param $host [optional]
	 * @param $user [optional]
	 * @param $password [optional]
	 * @param $database [optional]
	 * @param $port [optional]
	 * @param $socket [optional]
	 */
	public function connect ($host, $user, $password, $database, $port, $socket) {}

	/**
	 * Dump debugging information into the log
	 * @link http://php.net/manual/en/mysqli.dump-debug-info.php
	 * @return bool true on success or false on failure.
	 * @since 5.0
	 */
	public function dump_debug_info () {}

	/**
	 * Performs debugging operations
	 * @link http://php.net/manual/en/mysqli.debug.php
	 * @param string $message <p>
	 * A string representing the debugging operation to perform
	 * </p>
	 * @return bool true.
	 * @since 5.0
	 */
	public function debug ($message) {}

	/**
	 * Returns a character set object
	 * @link http://php.net/manual/en/mysqli.get-charset.php
	 * @return object The function returns a character set object with the following properties:
	 * <i>charset</i>
	 * <p>Character set name</p>
	 * <i>collation</i>
	 * <p>Collation name</p>
	 * <i>dir</i>
	 * <p>Directory the charset description was fetched from (?) or "" for built-in character sets</p>
	 * <i>min_length</i>
	 * <p>Minimum character length in bytes</p>
	 * <i>max_length</i>
	 * <p>Maximum character length in bytes</p>
	 * <i>number</i>
	 * <p>Internal character set number</p>
	 * <i>state</i>
	 * <p>Character set status (?)</p>
	 * @since 5.1.0
	 */
	public function get_charset () {}

	/**
	 * Returns the MySQL client version as a string
	 * @link http://php.net/manual/en/mysqli.get-client-info.php
	 * @return string A string that represents the MySQL client library version
	 * @since 5.0
	 */
	public function get_client_info () {}

	/**
	 * Returns statistics about the client connection
	 * @link http://php.net/manual/en/mysqli.get-connection-stats.php
	 * @return bool an array with connection stats if success, false otherwise.
	 * @since 5.3.0
	 */
	public function get_connection_stats () {}

	/**
	 * An undocumented function equivalent to the $server_info property
	 * @link http://php.net/manual/en/mysqli.get-server-info.php
	 * @return string A character string representing the server version.
 	 */
	public function get_server_info () {}

	/**
	 * Get result of SHOW WARNINGS
	 * @link http://php.net/manual/en/mysqli.get-warnings.php
	 * @return mysqli_warning
	 * @since 5.1.0
	 */
	public function get_warnings () {}

	/**
	 * Initializes MySQLi and returns a resource for use with mysqli_real_connect()
	 * @link http://php.net/manual/en/mysqli.init.php
	 * @return mysqli an object.
	 * @since 5.0
	 */
	public function init () {}

	/**
	 * Asks the server to kill a MySQL thread
	 * @link http://php.net/manual/en/mysqli.kill.php
	 * @param int $processid
	 * @return bool true on success or false on failure.
	 * @since 5.0
	 */
	public function kill ($processid) {}

	/**
	 * Performs a query on the database
	 * @link http://php.net/manual/en/mysqli.multi-query.php
	 * @param string $query <p>
	 * The query, as a string.
	 * </p>
	 * <p>
	 * Data inside the query should be properly escaped.
	 * </p>
	 * @return bool false if the first statement failed.
	 * To retrieve subsequent errors from other statements you have to call
	 * <b>mysqli_next_result</b> first.
	 * @since 5.0
	 */
	public function multi_query ($query) {}

	/**
	 * @param $host [optional]
	 * @param $user [optional]
	 * @param $password [optional]
	 * @param $database [optional]
	 * @param $port [optional]
	 * @param $socket [optional]
	 */
	public function mysqli ($host, $user, $password, $database, $port, $socket) {}

	/**
	 * Check if there are any more query results from a multi query
	 * @link http://php.net/manual/en/mysqli.more-results.php
	 * @return bool true on success or false on failure.
	 * @since 5.0
	 */
	public function more_results () {}

	/**
	 * Prepare next result from multi_query
	 * @link http://php.net/manual/en/mysqli.next-result.php
	 * @return bool true on success or false on failure.
	 * @since 5.0
	 */
	public function next_result () {}

	/**
	 * Set options
	 * @link http://php.net/manual/en/mysqli.options.php
	 * @param int $option <p>
	 * The option that you want to set. It can be one of the following values:
	 * <table>
	 * Valid options
	 * <tr valign="top">
	 * <td>Name</td>
	 * <td>Description</td>
	 * </tr>
	 * <tr valign="top">
	 * <td><b>MYSQLI_OPT_CONNECT_TIMEOUT</b></td>
	 * <td>connection timeout in seconds (supported on Windows with TCP/IP since PHP 5.3.1)</td>
	 * </tr>
	 * <tr valign="top">
	 * <td><b>MYSQLI_OPT_LOCAL_INFILE</b></td>
	 * <td>enable/disable use of LOAD LOCAL INFILE</td>
	 * </tr>
	 * <tr valign="top">
	 * <td><b>MYSQLI_INIT_COMMAND</b></td>
	 * <td>command to execute after when connecting to MySQL server</td>
	 * </tr>
	 * <tr valign="top">
	 * <td><b>MYSQLI_READ_DEFAULT_FILE</b></td>
	 * <td>
	 * Read options from named option file instead of my.cnf
	 * </td>
	 * </tr>
	 * <tr valign="top">
	 * <td><b>MYSQLI_READ_DEFAULT_GROUP</b></td>
	 * <td>
	 * Read options from the named group from my.cnf
	 * or the file specified with <b>MYSQL_READ_DEFAULT_FILE</b>
	 * </td>
	 * </tr>
     * <tr valign="top">
     * <td><b>MYSQLI_SERVER_PUBLIC_KEY</b></td>
     * <td>
     * RSA public key file used with the SHA-256 based authentication.
     * </td>
     * </tr>
	 * </table>
	 * </p>
	 * @param mixed $value <p>
	 * The value for the option.
	 * </p>
	 * @return bool true on success or false on failure.
	 * @since 5.0
	 */
	public function options ($option, $value) {}

	/**
	 * Pings a server connection, or tries to reconnect if the connection has gone down
	 * @link http://php.net/manual/en/mysqli.ping.php
	 * @return bool true on success or false on failure.
	 * @since 5.0
	 */
	public function ping () {}

	/**
	 * Prepare an SQL statement for execution
	 * @link http://php.net/manual/en/mysqli.prepare.php
	 * @param string $query <p>
	 * The query, as a string.
	 * </p>
	 * <p>
	 * You should not add a terminating semicolon or \g
	 * to the statement.
	 * </p>
	 * <p>
	 * This parameter can include one or more parameter markers in the SQL
	 * statement by embedding question mark (?) characters
	 * at the appropriate positions.
	 * </p>
	 * <p>
	 * The markers are legal only in certain places in SQL statements.
	 * For example, they are allowed in the VALUES()
	 * list of an INSERT statement (to specify column
	 * values for a row), or in a comparison with a column in a
	 * WHERE clause to specify a comparison value.
	 * </p>
	 * <p>
	 * However, they are not allowed for identifiers (such as table or
	 * column names), in the select list that names the columns to be
	 * returned by a SELECT statement, or to specify both
	 * operands of a binary operator such as the = equal
	 * sign. The latter restriction is necessary because it would be
	 * impossible to determine the parameter type. It's not allowed to
	 * compare marker with NULL by
	 * ? IS NULL too. In general, parameters are legal
	 * only in Data Manipulation Language (DML) statements, and not in Data
	 * Definition Language (DDL) statements.
	 * </p>
	 * @return mysqli_stmt <b>mysqli_prepare</b> returns a statement object or false if an error occurred.
	 * @since 5.0
	 */
	public function prepare ($query) {}

	/**
	 * Performs a query on the database
	 * @link http://php.net/manual/en/mysqli.query.php
	 * @param string $query <p>
	 * The query string.
	 * </p>
	 * <p>
	 * Data inside the query should be properly escaped.
	 * </p>
	 * @param int $resultmode [optional] <p>
	 * Either the constant <b>MYSQLI_USE_RESULT</b> or
	 * <b>MYSQLI_STORE_RESULT</b> depending on the desired
	 * behavior. By default, <b>MYSQLI_STORE_RESULT</b> is used.
	 * </p>
	 * <p>
	 * If you use <b>MYSQLI_USE_RESULT</b> all subsequent calls
	 * will return error Commands out of sync unless you
	 * call <b>mysqli_free_result</b>
	 * </p>
	 * <p>
	 * With <b>MYSQLI_ASYNC</b> (available with mysqlnd), it is
	 * possible to perform query asynchronously.
	 * <b>mysqli_poll</b> is then used to get results from such
	 * queries.
	 * </p>
	 * @return mysqli_result|boolean For successful SELECT, SHOW, DESCRIBE or
	 * EXPLAIN queries <b>mysqli_query</b> will return
	 * a <b>mysqli_result</b> object.For other successful queries <b>mysqli_query</b> will
	 * return true and false on failure.
	 * @since 5.0
	 */
	public function query ($query, $resultmode = MYSQLI_STORE_RESULT) {}

	/**
	 * Opens a connection to a mysql server
	 * @link http://php.net/manual/en/mysqli.real-connect.php
	 * @param string $host [optional] <p>
	 * Can be either a host name or an IP address. Passing the null value
	 * or the string "localhost" to this parameter, the local host is
	 * assumed. When possible, pipes will be used instead of the TCP/IP
	 * protocol.
	 * </p>
	 * @param string $username [optional] <p>
	 * The MySQL user name.
	 * </p>
	 * @param string $passwd [optional] <p>
	 * If provided or null, the MySQL server will attempt to authenticate
	 * the user against those user records which have no password only. This
	 * allows one username to be used with different permissions (depending
	 * on if a password as provided or not).
	 * </p>
	 * @param string $dbname [optional] <p>
	 * If provided will specify the default database to be used when
	 * performing queries.
	 * </p>
	 * @param int $port [optional] <p>
	 * Specifies the port number to attempt to connect to the MySQL server.
	 * </p>
	 * @param string $socket [optional] <p>
	 * Specifies the socket or named pipe that should be used.
	 * </p>
	 * <p>
	 * Specifying the <i>socket</i> parameter will not
	 * explicitly determine the type of connection to be used when
	 * connecting to the MySQL server. How the connection is made to the
	 * MySQL database is determined by the <i>host</i>
	 * parameter.
	 * </p>
	 * @param int $flags [optional] <p>
	 * With the parameter <i>flags</i> you can set different
	 * connection options:
	 * </p>
	 * <table>
	 * Supported flags
	 * <tr valign="top">
	 * <td>Name</td>
	 * <td>Description</td>
	 * </tr>
	 * <tr valign="top">
	 * <td><b>MYSQLI_CLIENT_COMPRESS</b></td>
	 * <td>Use compression protocol</td>
	 * </tr>
	 * <tr valign="top">
	 * <td><b>MYSQLI_CLIENT_FOUND_ROWS</b></td>
	 * <td>return number of matched rows, not the number of affected rows</td>
	 * </tr>
	 * <tr valign="top">
	 * <td><b>MYSQLI_CLIENT_IGNORE_SPACE</b></td>
	 * <td>Allow spaces after function names. Makes all function names reserved words.</td>
	 * </tr>
	 * <tr valign="top">
	 * <td><b>MYSQLI_CLIENT_INTERACTIVE</b></td>
	 * <td>
	 * Allow interactive_timeout seconds (instead of
	 * wait_timeout seconds) of inactivity before closing the connection
	 * </td>
	 * </tr>
	 * <tr valign="top">
	 * <td><b>MYSQLI_CLIENT_SSL</b></td>
	 * <td>Use SSL (encryption)</td>
	 * </tr>
	 * </table>
	 * <p>
	 * For security reasons the <b>MULTI_STATEMENT</b> flag is
	 * not supported in PHP. If you want to execute multiple queries use the
	 * <b>mysqli_multi_query</b> function.
	 * </p>
	 * @return bool true on success or false on failure.
	 * @since 5.0
	 */
	public function real_connect ($host = null, $username = null, $passwd = null, $dbname = null, $port = null, $socket = null, $flags = null) {}

	/**
	 * Escapes special characters in a string for use in an SQL statement, taking into account the current charset of the connection
	 * @link http://php.net/manual/en/mysqli.real-escape-string.php
	 * @param string $escapestr <p>
	 * The string to be escaped.
	 * </p>
	 * <p>
	 * Characters encoded are NUL (ASCII 0), \n, \r, \, ', ", and
	 * Control-Z.
	 * </p>
	 * @return string an escaped string.
	 * @since 5.0
	 */
	public function real_escape_string ($escapestr) {}

	/**
	 * Poll connections
	 * @link http://php.net/manual/en/mysqli.poll.php
	 * @param array $read <p>
	 * </p>
	 * @param array $error <p>
	 * </p>
	 * @param array $reject <p>
	 * </p>
	 * @param int $sec <p>
	 * Number of seconds to wait, must be non-negative.
	 * </p>
	 * @param int $usec [optional] <p>
	 * Number of microseconds to wait, must be non-negative.
	 * </p>
	 * @return int number of ready connections in success, false otherwise.
	 * @since 5.3.0
	 */
	public function poll (array &$read , array &$error , array &$reject , $sec, $usec = null) {}

	/**
	 * Get result from async query
	 * @link http://php.net/manual/en/mysqli.reap-async-query.php
	 * @return mysqli_result mysqli_result in success, false otherwise.
	 * @since 5.3.0
	 */
	public function reap_async_query () {}

	/**
	 * Escapes special characters in a string for use in an SQL statement, taking into account the current charset of the connection
	 * @param string $escapestr The string to be escaped.
	 * Characters encoded are NUL (ASCII 0), \n, \r, \, ', ", and Control-Z.
	 * @return string
	 * @link http://www.php.net/manual/en/mysqli.real-escape-string.php
	 */
	public function escape_string ($escapestr) {}

	/**
	 * Execute an SQL query
	 * @link http://php.net/manual/en/mysqli.real-query.php
	 * @param string $query <p>
	 * The query, as a string.
	 * </p>
	 * <p>
	 * Data inside the query should be properly escaped.
	 * </p>
	 * @return bool true on success or false on failure.
	 * @since 5.0
	 */
	public function real_query ($query) {}

    /**
     * Execute an SQL query
     * @link http://php.net/manual/en/mysqli.release-savepoint.php
     * @param string $name
     * @return bool Returns TRUE on success or FALSE on failure.
     * @since 5.5.0
     */
    public function release_savepoint ($name) {}

	/**
	 * Rolls back current transaction
	 * @link http://php.net/manual/en/mysqli.rollback.php
	 * @return bool true on success or false on failure.
	 * @since 5.0
	 */
	public function rollback () {}

    /**
     * Set a named transaction savepoint
     * @link http://www.php.net/manual/en/mysqli.savepoint.php
     * @param string $name
     * @return bool Returns TRUE on success or FALSE on failure.
     * @since 5.5.0
     */
    public function savepoint ($name) {}

	/**
	 * Selects the default database for database queries
	 * @link http://php.net/manual/en/mysqli.select-db.php
	 * @param string $dbname <p>
	 * The database name.
	 * </p>
	 * @return bool true on success or false on failure.
	 * @since 5.0
	 */
	public function select_db ($dbname) {}

	/**
	 * Sets the default client character set
	 * @link http://php.net/manual/en/mysqli.set-charset.php
	 * @param string $charset <p>
	 * The charset to be set as default.
	 * </p>
	 * @return bool true on success or false on failure.
	 * @since 5.0.5
	 */
	public function set_charset ($charset) {}

	/**
	 * @param $option
	 * @param $value
	 */
	public function set_opt ($option, $value) {}


	/**
	 * Used for establishing secure connections using SSL
	 * @link http://www.php.net/manual/en/mysqli.ssl-set.php
	 * @param $key <p>
	 * The path name to the key file.
	 * </p>
	 * @param $cert <p>
	 * The path name to the certificate file.
	 * </p>
	 * @param $ca <p>
	 * The path name to the certificate authority file.
	 * </p>
	 * @param $capath <p>
	 * The pathname to a directory that contains trusted SSL CA certificates in PEM format.
	 * </p>
	 * @param $cipher <p>
	 * A list of allowable ciphers to use for SSL encryption.
	 * </p>
	 * @return bool This function always returns TRUE value.
	 * @since 5.0
	 */
	public function ssl_set($key , $cert , $ca , $capath , $cipher) {}

	/**
	 * Gets the current system status
	 * @link http://php.net/manual/en/mysqli.stat.php
	 * @return string A string describing the server status. false if an error occurred.
	 * @since 5.0
	 */
	public function stat () {}

	/**
	 * Initializes a statement and returns an object for use with mysqli_stmt_prepare
	 * @link http://php.net/manual/en/mysqli.stmt-init.php
	 * @return mysqli_stmt an object.
	 * @since 5.0
	 */
	public function stmt_init () {}

	/**
	 * Transfers a result set from the last query
	 * @link http://php.net/manual/en/mysqli.store-result.php
	 * @return mysqli_result a buffered result object or false if an error occurred.
	 * </p>
	 * <p>
	 * <b>mysqli_store_result</b> returns false in case the query
	 * didn't return a result set (if the query was, for example an INSERT
	 * statement). This function also returns false if the reading of the
	 * result set failed. You can check if you have got an error by checking
	 * if <b>mysqli_error</b> doesn't return an empty string, if
	 * <b>mysqli_errno</b> returns a non zero value, or if
	 * <b>mysqli_field_count</b> returns a non zero value.
	 * Also possible reason for this function returning false after
	 * successful call to <b>mysqli_query</b> can be too large
	 * result set (memory for it cannot be allocated). If
	 * <b>mysqli_field_count</b> returns a non-zero value, the
	 * statement should have produced a non-empty result set.
	 * @since 5.0
	 */
	public function store_result () {}

	/**
	 * Returns whether thread safety is given or not
	 * @link http://php.net/manual/en/mysqli.thread-safe.php
	 * @return bool true if the client library is thread-safe, otherwise false.
	 * @since 5.0
	 */
	public function thread_safe () {}

	/**
	 * Initiate a result set retrieval
	 * @link http://php.net/manual/en/mysqli.use-result.php
	 * @return mysqli_result an unbuffered result object or false if an error occurred.
	 * @since 5.0
	 */
	public function use_result () {}

	/**
	 * @param $options
	 */
	public function refresh ($options) {}

}

/**
 * Represents one or more MySQL warnings.
 * @link http://php.net/manual/en/class.mysqli-warning.php
 */
final class mysqli_warning  {
	/**
	 * @var string
	 */
	public $message;
	/**
	 * @var string
	 */
	public $sqlstate;
	/**
	 * @var int
	 */
	public $errno;


	/**
	 * The __construct purpose
	 * @link http://php.net/manual/en/mysqli-warning.construct.php
	 */
	protected function __construct () {}

	/**
	 * Move to the next warning
	 * @link http://php.net/manual/en/mysqli-warning.next.php
	 * @return bool True if it successfully moved to the next warning
	 */
	public function next () {}

}

/**
 * Represents the result set obtained from a query against the database.
 * Implements Traversable since 5.4
 * @link http://php.net/manual/en/class.mysqli-result.php
 */
class mysqli_result implements Traversable  {
	/**
	 * @var int
	 */
	public $current_field;
	/**
	 * @var int
	 */
	public $field_count;
	/**
	 * @var array
	 */
	public $lengths;
	/**
	 * @var int
	 */
	public $num_rows;
	/**
	 * @var mixed
	 */
	public $type;

	/**
	 * Constructor (no docs available)
	 */
	public function __construct () {}

	/**
	 * Close (no docs available)
 	 */
	public function close () {}

	/**
	 * Frees the memory associated with a result
	 * @link http://php.net/manual/en/mysqli-result.free.php
	 * @return void
	 * @since 5.0
	 */
	public function free () {}

	/**
	 * Adjusts the result pointer to an arbitary row in the result
	 * @link http://php.net/manual/en/mysqli-result.data-seek.php
	 * @param int $offset <p>
	 * The field offset. Must be between zero and the total number of rows
	 * minus one (0..<b>mysqli_num_rows</b> - 1).
	 * </p>
	 * @return bool true on success or false on failure.
	 * @since 5.0
	 */
	public function data_seek ($offset) {}

	/**
	 * Returns the next field in the result set
	 * @link http://php.net/manual/en/mysqli-result.fetch-field.php
	 * @return object an object which contains field definition information or false
	 * if no field information is available.
	 * </p>
	 * <p>
	 * <table>
	 * Object properties
	 * <tr valign="top">
	 * <td>Property</td>
	 * <td>Description</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>name</td>
	 * <td>The name of the column</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>orgname</td>
	 * <td>Original column name if an alias was specified</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>table</td>
	 * <td>The name of the table this field belongs to (if not calculated)</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>orgtable</td>
	 * <td>Original table name if an alias was specified</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>def</td>
	 * <td>Reserved for default value, currently always ""</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>db</td>
	 * <td>Database (since PHP 5.3.6)</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>catalog</td>
	 * <td>The catalog name, always "def" (since PHP 5.3.6)</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>max_length</td>
	 * <td>The maximum width of the field for the result set.</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>length</td>
	 * <td>The width of the field, as specified in the table definition.</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>charsetnr</td>
	 * <td>The character set number for the field.</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>flags</td>
	 * <td>An integer representing the bit-flags for the field.</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>type</td>
	 * <td>The data type used for this field</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>decimals</td>
	 * <td>The number of decimals used (for integer fields)</td>
	 * </tr>
	 * </table>
	 * @since 5.0
	 */
	public function fetch_field () {}

	/**
	 * Returns an array of objects representing the fields in a result set
	 * @link http://php.net/manual/en/mysqli-result.fetch-fields.php
	 * @return array an array of objects which contains field definition information or
	 * false if no field information is available.
	 * </p>
	 * <p>
	 * <table>
	 * Object properties
	 * <tr valign="top">
	 * <td>Property</td>
	 * <td>Description</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>name</td>
	 * <td>The name of the column</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>orgname</td>
	 * <td>Original column name if an alias was specified</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>table</td>
	 * <td>The name of the table this field belongs to (if not calculated)</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>orgtable</td>
	 * <td>Original table name if an alias was specified</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>def</td>
	 * <td>The default value for this field, represented as a string</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>max_length</td>
	 * <td>The maximum width of the field for the result set.</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>length</td>
	 * <td>The width of the field, as specified in the table definition.</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>charsetnr</td>
	 * <td>The character set number for the field.</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>flags</td>
	 * <td>An integer representing the bit-flags for the field.</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>type</td>
	 * <td>The data type used for this field</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>decimals</td>
	 * <td>The number of decimals used (for integer fields)</td>
	 * </tr>
	 * </table>
	 * @since 5.0
	 */
	public function fetch_fields () {}

	/**
	 * Fetch meta-data for a single field
	 * @link http://php.net/manual/en/mysqli-result.fetch-field-direct.php
	 * @param int $fieldnr <p>
	 * The field number. This value must be in the range from
	 * 0 to number of fields - 1.
	 * </p>
	 * @return object an object which contains field definition information or false
	 * if no field information for specified fieldnr is
	 * available.
	 * </p>
	 * <p>
	 * <table>
	 * Object attributes
	 * <tr valign="top">
	 * <td>Attribute</td>
	 * <td>Description</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>name</td>
	 * <td>The name of the column</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>orgname</td>
	 * <td>Original column name if an alias was specified</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>table</td>
	 * <td>The name of the table this field belongs to (if not calculated)</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>orgtable</td>
	 * <td>Original table name if an alias was specified</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>def</td>
	 * <td>The default value for this field, represented as a string</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>max_length</td>
	 * <td>The maximum width of the field for the result set.</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>length</td>
	 * <td>The width of the field, as specified in the table definition.</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>charsetnr</td>
	 * <td>The character set number for the field.</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>flags</td>
	 * <td>An integer representing the bit-flags for the field.</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>type</td>
	 * <td>The data type used for this field</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>decimals</td>
	 * <td>The number of decimals used (for integer fields)</td>
	 * </tr>
	 * </table>
	 * @since 5.0
	 */
	public function fetch_field_direct ($fieldnr) {}

	/**
	 * Fetches all result rows as an associative array, a numeric array, or both
	 * @link http://php.net/manual/en/mysqli-result.fetch-all.php
	 * @param int $resulttype [optional] <p>
	 * This optional parameter is a constant indicating what type of array
	 * should be produced from the current row data. The possible values for
	 * this parameter are the constants MYSQLI_ASSOC,
	 * MYSQLI_NUM, or MYSQLI_BOTH.
	 * </p>
	 * @return mixed an array of associative or numeric arrays holding result rows.
	 * @since 5.3.0
	 */
	public function fetch_all ($resulttype = null) {}

	/**
	 * Fetch a result row as an associative, a numeric array, or both
	 * @link http://php.net/manual/en/mysqli-result.fetch-array.php
	 * @param int $resulttype [optional] <p>
	 * This optional parameter is a constant indicating what type of array
	 * should be produced from the current row data. The possible values for
	 * this parameter are the constants <b>MYSQLI_ASSOC</b>,
	 * <b>MYSQLI_NUM</b>, or <b>MYSQLI_BOTH</b>.
	 * </p>
	 * <p>
	 * By using the <b>MYSQLI_ASSOC</b> constant this function
	 * will behave identically to the <b>mysqli_fetch_assoc</b>,
	 * while <b>MYSQLI_NUM</b> will behave identically to the
	 * <b>mysqli_fetch_row</b> function. The final option
	 * <b>MYSQLI_BOTH</b> will create a single array with the
	 * attributes of both.
	 * </p>
	 * @return mixed an array of strings that corresponds to the fetched row or null if there
	 * are no more rows in resultset.
	 * @since 5.0
	 */
	public function fetch_array ($resulttype = MYSQLI_BOTH) {}

	/**
	 * Fetch a result row as an associative array
	 * @link http://php.net/manual/en/mysqli-result.fetch-assoc.php
	 * @return array an associative array of strings representing the fetched row in the result
	 * set, where each key in the array represents the name of one of the result
	 * set's columns or null if there are no more rows in resultset.
	 * </p>
	 * <p>
	 * If two or more columns of the result have the same field names, the last
	 * column will take precedence. To access the other column(s) of the same
	 * name, you either need to access the result with numeric indices by using
	 * <b>mysqli_fetch_row</b> or add alias names.
	 * @since 5.0
	 */
	public function fetch_assoc () {}

	/**
	 * Returns the current row of a result set as an object
	 * @link http://php.net/manual/en/mysqli-result.fetch-object.php
	 * @param string $class_name [optional] <p>
	 * The name of the class to instantiate, set the properties of and return.
	 * If not specified, a <b>stdClass</b> object is returned.
	 * </p>
	 * @param array $params [optional] <p>
	 * An optional array of parameters to pass to the constructor
	 * for <i>class_name</i> objects.
	 * </p>
	 * @return stdClass|object an object with string properties that corresponds to the fetched
	 * row or null if there are no more rows in resultset.
	 * @since 5.0
	 */
	public function fetch_object ($class_name = null, array $params = null) {}

	/**
	 * Get a result row as an enumerated array
	 * @link http://php.net/manual/en/mysqli-result.fetch-row.php
	 * @return mixed mysqli_fetch_row returns an array of strings that corresponds to the fetched row
	 * or &null; if there are no more rows in result set.
	 * @since 5.0
	 */
	public function fetch_row () {}

	/**
	 * Set result pointer to a specified field offset
	 * @link http://php.net/manual/en/mysqli-result.field-seek.php
	 * @param int $fieldnr <p>
	 * The field number. This value must be in the range from
	 * 0 to number of fields - 1.
	 * </p>
	 * @return bool true on success or false on failure.
	 * @since 5.0
	 */
	public function field_seek ($fieldnr) {}

	/**
	 * Free a result set (No docs available)
	 */
	public function free_result () {}

}

/**
 * Represents a prepared statement.
 * @link http://php.net/manual/en/class.mysqli-stmt.php
 */
class mysqli_stmt  {
	/**
	 * @var int
	 */
	public $affected_rows;
	/**
	 * @var int
	 */
	public $insert_id;
	/**
	 * @var int
	 */
	public $num_rows;
	/**
	 * @var int
	 */
	public $param_count;
	/**
	 * @var int
	 */
	public $field_count;
	/**
	 * @var int
	 */
	public $errno;
	/**
	 * @var string
	 */
	public $error;
	/**
	 * @var array
	 */
	public $error_list;
	/**
	 * @var string
	 */
	public $sqlstate;
	/**
	 * @var string
	 */
	public $id;

	/**
	 * mysqli_stmt constructor
	 * @param mysqli $link
	 * @param string $query
	 */
	public function __construct ($link, $query) {}

	/**
	 * Used to get the current value of a statement attribute
	 * @link http://php.net/manual/en/mysqli-stmt.attr-get.php
	 * @param int $attr <p>
	 * The attribute that you want to get.
	 * </p>
	 * @return int false if the attribute is not found, otherwise returns the value of the attribute.
	 * @since 5.0
	 */
	public function attr_get ($attr) {}

	/**
	 * Used to modify the behavior of a prepared statement
	 * @link http://php.net/manual/en/mysqli-stmt.attr-set.php
	 * @param int $attr <p>
	 * The attribute that you want to set. It can have one of the following values:
	 * <table>
	 * Attribute values
	 * <tr valign="top">
	 * <td>Character</td>
	 * <td>Description</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>MYSQLI_STMT_ATTR_UPDATE_MAX_LENGTH</td>
	 * <td>
	 * If set to 1, causes <b>mysqli_stmt_store_result</b> to
	 * update the metadata MYSQL_FIELD->max_length value.
	 * </td>
	 * </tr>
	 * <tr valign="top">
	 * <td>MYSQLI_STMT_ATTR_CURSOR_TYPE</td>
	 * <td>
	 * Type of cursor to open for statement when <b>mysqli_stmt_execute</b>
	 * is invoked. <i>mode</i> can be MYSQLI_CURSOR_TYPE_NO_CURSOR
	 * (the default) or MYSQLI_CURSOR_TYPE_READ_ONLY.
	 * </td>
	 * </tr>
	 * <tr valign="top">
	 * <td>MYSQLI_STMT_ATTR_PREFETCH_ROWS</td>
	 * <td>
	 * Number of rows to fetch from server at a time when using a cursor.
	 * <i>mode</i> can be in the range from 1 to the maximum
	 * value of unsigned long. The default is 1.
	 * </td>
	 * </tr>
	 * </table>
	 * </p>
	 * <p>
	 * If you use the MYSQLI_STMT_ATTR_CURSOR_TYPE option with
	 * MYSQLI_CURSOR_TYPE_READ_ONLY, a cursor is opened for the
	 * statement when you invoke <b>mysqli_stmt_execute</b>. If there
	 * is already an open cursor from a previous <b>mysqli_stmt_execute</b> call,
	 * it closes the cursor before opening a new one. <b>mysqli_stmt_reset</b>
	 * also closes any open cursor before preparing the statement for re-execution.
	 * <b>mysqli_stmt_free_result</b> closes any open cursor.
	 * </p>
	 * <p>
	 * If you open a cursor for a prepared statement, <b>mysqli_stmt_store_result</b>
	 * is unnecessary.
	 * </p>
	 * @param int $mode <p>The value to assign to the attribute.</p>
	 * @return bool
	 * @since 5.0
	 */
	public function attr_set ($attr, $mode) {}

	/**
	 * Binds variables to a prepared statement as parameters
	 * @link http://php.net/manual/en/mysqli-stmt.bind-param.php
	 * @param string $types <p>
	 * A string that contains one or more characters which specify the types
	 * for the corresponding bind variables:
	 * <table>
	 * Type specification chars
	 * <tr valign="top">
	 * <td>Character</td>
	 * <td>Description</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>i</td>
	 * <td>corresponding variable has type integer</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>d</td>
	 * <td>corresponding variable has type double</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>s</td>
	 * <td>corresponding variable has type string</td>
	 * </tr>
	 * <tr valign="top">
	 * <td>b</td>
	 * <td>corresponding variable is a blob and will be sent in packets</td>
	 * </tr>
	 * </table>
	 * </p>
	 * @param mixed $var1 <p>
	 * The number of variables and length of string
	 * types must match the parameters in the statement.
	 * </p>
	 * @param mixed $_ [optional]
	 * @return bool true on success or false on failure.
	 * @since 5.0
	 */
	public function bind_param ($types, &$var1, &$_ = null) {}

	/**
	 * Binds variables to a prepared statement for result storage
	 * @link http://php.net/manual/en/mysqli-stmt.bind-result.php
	 * @param mixed $var1 The variable to be bound.
	 * @param mixed ...$_ The variables to be bound.
	 * @return bool true on success or false on failure.
	 * @since 5.0
	 */
	public function bind_result (&$var1, &...$_) {}

	/**
	 * Closes a prepared statement
	 * @link http://php.net/manual/en/mysqli-stmt.close.php
	 * @return bool true on success or false on failure.
	 * @since 5.0
	 */
	public function close () {}

	/**
	 * Seeks to an arbitrary row in statement result set
	 * @link http://php.net/manual/en/mysqli-stmt.data-seek.php
	 * @param int $offset <p>
	 * Must be between zero and the total number of rows minus one (0..
	 * <b>mysqli_stmt_num_rows</b> - 1).
	 * </p>
	 * @return void
	 * @since 5.0
	 */
	public function data_seek ($offset) {}

	/**
	 * Executes a prepared Query
	 * @link http://php.net/manual/en/mysqli-stmt.execute.php
	 * @return bool true on success or false on failure.
	 * @since 5.0
	 */
	public function execute () {}

	/**
	 * Fetch results from a prepared statement into the bound variables
	 * @link http://php.net/manual/en/mysqli-stmt.fetch.php
	 * @return bool
	 * @since 5.0
	 */
	public function fetch () {}

	/**
	 * Get result of SHOW WARNINGS
	 * @link http://php.net/manual/en/mysqli-stmt.get-warnings.php
	 * @param mysqli_stmt $stmt
	 * @return object
	 * @since 5.1.0
	 */
	public function get_warnings (mysqli_stmt $stmt) {}

	/**
	 * Returns result set metadata from a prepared statement
	 * @link http://php.net/manual/en/mysqli-stmt.result-metadata.php
	 * @return mysqli_result a result object or false if an error occurred.
	 * @since 5.0
	 */
	public function result_metadata () {}

	/**
	 * Check if there are more query results from a multiple query
	 * @link http://php.net/manual/en/mysqli-stmt.more-results.php
	 * @return bool
	 */
	public function more_results () {}

	/**
	 * Reads the next result from a multiple query
	 * @link http://php.net/manual/en/mysqli-stmt.next-result.php
	 * @return bool
	 */
	public function next_result () {}

	/**
	 * Return the number of rows in statements result set
	 * @link http://php.net/manual/en/mysqli-stmt.num-rows.php
	 * @param mysqli_stmt $stmt
	 * @return int An integer representing the number of rows in result set.
	 * @since 5.0
	 */
	public function num_rows (mysqli_stmt $stmt) {}

	/**
	 * Send data in blocks
	 * @link http://php.net/manual/en/mysqli-stmt.send-long-data.php
	 * @param int $param_nr <p>
	 * Indicates which parameter to associate the data with. Parameters are
	 * numbered beginning with 0.
	 * </p>
	 * @param string $data <p>
	 * A string containing data to be sent.
	 * </p>
	 * @return bool true on success or false on failure.
	 * @since 5.0
	 */
	public function send_long_data ($param_nr, $data) {}

	/**
	 * No documentation available
	 * @deprecated 5.3 This function has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 5.4.0.
	 */
	public function stmt () {}

	/**
	 * Frees stored result memory for the given statement handle
	 * @link http://php.net/manual/en/mysqli-stmt.free-result.php
	 * @return void
	 * @since 5.0
	 */
	public function free_result () {}

	/**
	 * Resets a prepared statement
	 * @link http://php.net/manual/en/mysqli-stmt.reset.php
	 * @return bool true on success or false on failure.
	 * @since 5.0
	 */
	public function reset () {}

	/**
	 * Prepare an SQL statement for execution
	 * @link http://php.net/manual/en/mysqli-stmt.prepare.php
	 * @param string $query <p>
	 * The query, as a string. It must consist of a single SQL statement.
	 * </p>
	 * <p>
	 * You can include one or more parameter markers in the SQL statement by
	 * embedding question mark (?) characters at the
	 * appropriate positions.
	 * </p>
	 * <p>
	 * You should not add a terminating semicolon or \g
	 * to the statement.
	 * </p>
	 * <p>
	 * The markers are legal only in certain places in SQL statements.
	 * For example, they are allowed in the VALUES() list of an INSERT statement
	 * (to specify column values for a row), or in a comparison with a column in
	 * a WHERE clause to specify a comparison value.
	 * </p>
	 * <p>
	 * However, they are not allowed for identifiers (such as table or column names),
	 * in the select list that names the columns to be returned by a SELECT statement),
	 * or to specify both operands of a binary operator such as the =
	 * equal sign. The latter restriction is necessary because it would be impossible
	 * to determine the parameter type. In general, parameters are legal only in Data
	 * Manipulation Language (DML) statements, and not in Data Definition Language
	 * (DDL) statements.
	 * </p>
	 * @return mixed true on success or false on failure.
	 * @since 5.0
	 */
	public function prepare ($query) {}

	/**
	 * Transfers a result set from a prepared statement
	 * @link http://php.net/manual/en/mysqli-stmt.store-result.php
	 * @return bool true on success or false on failure.
	 * @since 5.0
	 */
	public function store_result () {}

	/**
	 * Gets a result set from a prepared statement
	 * @link http://php.net/manual/en/mysqli-stmt.get-result.php
	 * @return mysqli_result|bool Returns a resultset or FALSE on failure
 	 */
	public function get_result () {}

}

/**
 * (PHP 5)<p>
 * Gets the number of affected rows in a previous MySQL operation
 * @link http://www.php.net/manual/en/mysqli.affected-rows.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @return int An integer greater than zero indicates the number of rows affected or retrieved.
 * Zero indicates that no records where updated for an UPDATE statement,
 * no rows matched the WHERE clause in the query or that no query has yet been executed. -1 indicates that the query returned an error.
 */
function mysqli_affected_rows ($link) {}

/**
 * Turns on or off auto-committing database modifications
 * @link http://www.php.net/manual/en/mysqli.autocommit.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @param bool $mode Whether to turn on auto-commit or not.
 * @return bool
 */
function mysqli_autocommit ($link, $mode) {}

/**
 * Starts a transaction
 * @link http://www.php.net/manual/en/mysqli.begin-transaction.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @param int $flags [optional]
 * @param string $name [optional]
 * @return bool true on success or false on failure.
 * @since 5.5.0
 */
function mysqli_begin_transaction ($link, $flags = 0, $name = null) {}

/**
 * Changes the user of the specified database connection
 * @link http://php.net/manual/en/mysqli.change-user.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @param string $user The MySQL user name.
 * @param string $password The MySQL password.
 * @param string|null $database The database to change to. If desired, the NULL value may be passed resulting in only changing the user and not selecting a database.
 * @return bool
 */
function mysqli_change_user ($link, $user, $password, $database) {}

/**
 * Returns the default character set for the database connection
 * @link http://php.net/manual/en/mysqli.character-set-name.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @return string The default character set for the current connection
 */
function mysqli_character_set_name ($link) {}

/**
 * Closes a previously opened database connection
 * @link http://php.net/manual/en/mysqli.close.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @return bool
 */
function mysqli_close ($link) {}

/**
 * Commits the current transaction
 * @link http://php.net/manual/en/mysqli.commit.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @return bool
 */
function mysqli_commit ($link) {}

/**
 * Open a new connection to the MySQL server
 * Alias of <b>mysqli::__construct</b>
 * @link http://php.net/manual/en/mysqli.construct.php
 * @param string $host Can be either a host name or an IP address. Passing the NULL value or the string "localhost" to this parameter, the local host is assumed. When possible, pipes will be used instead of the TCP/IP protocol.
 * @param string $user The MySQL user name.
 * @param string $password If not provided or NULL, the MySQL server will attempt to authenticate the user against those user records which have no password only.
 * @param string $database If provided will specify the default database to be used when performing queries.
 * @param string $port Specifies the port number to attempt to connect to the MySQL server.
 * @param string $socket Specifies the socket or named pipe that should be used.
 * @return mysqli object which represents the connection to a MySQL Server.
 */
function mysqli_connect ($host = '', $user = '', $password = '', $database = '', $port = '', $socket = '') {}

/**
 * Returns the error code from last connect call
 * @link http://php.net/manual/en/mysqli.connect-errno.php
 * @return int Last error code number from the last call to mysqli_connect(). Zero means no error occurred.
 */
function mysqli_connect_errno () {}

/**
 * Returns a string description of the last connect error
 * @link http://php.net/manual/en/mysqli.connect-error.php
 * @return string Last error message string from the last call to mysqli_connect().
 */
function mysqli_connect_error () {}

/**
 * Adjusts the result pointer to an arbitrary row in the result
 * @param mysqli_result $result A result set identifier returned by mysqli_query(),
 * mysqli_store_result() or mysqli_use_result().
 * @param int $offset
 * @return bool Returns TRUE on success or FALSE on failure.
 */
function mysqli_data_seek ($result, $offset) {}

/**
 * Dump debugging information into the log
 * @link http://php.net/manual/en/mysqli.dump-debug-info.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @return bool
 */
function mysqli_dump_debug_info ($link) {}

/**
 * Performs debugging operations using the Fred Fish debugging library.
 * @link http://php.net/manual/en/mysqli.debug.php
 * @param string $message
 * @return bool
 */
function mysqli_debug ($message) {}

/**
 * Returns the error code for the most recent function call
 * @link http://php.net/manual/en/mysqli.errno.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @return int An error code value for the last call, if it failed. zero means no error occurred.
 */
function mysqli_errno ($link) {}

/**
 * Returns a list of errors from the last command executed
 * PHP > 5.4.0 </br>
 * @link http://php.net/manual/en/mysqli.error-list.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @return array A list of errors, each as an associative array containing the errno, error, and sqlstate.
 */
function mysqli_error_list ($link) {}

/**
 * Returns a list of errors from the last statement executed
 * PHP > 5.4.0 </br>
 * @link http://docs.php.net/manual/da/mysqli-stmt.error-list.php
 * @param mysqli_stmt $stmt A statement identifier returned by mysqli_stmt_init().
 * @return array A list of errors, each as an associative array containing the errno, error, and sqlstate.
 */
function mysqli_stmt_error_list ($stmt) {}

/**
 * Returns a string description of the last error
 * @link http://docs.php.net/manual/da/mysqli.error.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @return string
 */
function mysqli_error ($link) {}

/**
 * Executes a prepared Query
 * @link http://php.net/manual/en/mysqli-stmt.execute.php
 * @param mysqli_stmt $stmt
 * @return bool
 */
function mysqli_stmt_execute ($stmt) {}

/**
 * Executes a prepared Query
 * @since 5.0
 * Alias for <b>mysqli_stmt_execute</b>
 * @link http://php.net/manual/en/function.mysqli-execute.php
 * @param mysqli_stmt $stmt
 * @deprecated
 */
function mysqli_execute ($stmt) {}

/**
 * Returns the next field in the result set
 * @link http://fr2.php.net/manual/en/mysqli-result.fetch-field.php
 * @param mysqli_result $result A result set identifier returned by mysqli_query(),
 * mysqli_store_result() or mysqli_use_result().
 * @return object|bool Returns an object which contains field definition information or FALSE if no field information is available.
 */
function mysqli_fetch_field ($result) {}

/**
 * Returns an array of objects representing the fields in a result set
 * @link http://fr2.php.net/manual/en/mysqli-result.fetch-fields.php
 * @param mysqli_result $result A result set identifier returned by mysqli_query(),
 * mysqli_store_result() or mysqli_use_result().
 * @return array|bool Returns an array of objects which contains field definition information or FALSE if no field information is available.
 */
function mysqli_fetch_fields ($result) {}

/**
 * Fetch meta-data for a single field
 * @link http://fr2.php.net/manual/en/mysqli-result.fetch-field-direct.php
 * @param mysqli_result $result A result set identifier returned by mysqli_query(),
 * mysqli_store_result() or mysqli_use_result().
 * @param int $fieldnr The field number. This value must be in the range from 0 to number of fields - 1.
 * @return object|bool Returns an object which contains field definition information or FALSE if no field information for specified fieldnr is available.
 */
function mysqli_fetch_field_direct ($result, $fieldnr) {}

/**
 * Returns the lengths of the columns of the current row in the result set
 * @link http://php.net/manual/en/mysqli-result.lengths.php
 * @param mysqli_result $result A result set identifier returned by mysqli_query(),
 * mysqli_store_result() or mysqli_use_result().
 * @return array|bool An array of integers representing the size of each column (not including any terminating null characters). FALSE if an error occurred.
 */
function mysqli_fetch_lengths ($result) {}

/**
 * Fetches all result rows as an associative array, a numeric array, or both.
 * Available only with mysqlnd.
 * @link http://php.net/manual/en/mysqli-result.fetch-all.php
 * @param mysqli_result $result A result set identifier returned by mysqli_query(),
 * mysqli_store_result() or mysqli_use_result().
 * @param int $resulttype
 * @return array|null Returns an array of associative or numeric arrays holding result rows.
 */
function mysqli_fetch_all ($result, $resulttype = MYSQLI_NUM) {}

/**
 * Fetch a result row as an associative, a numeric array, or both.
 * @link http://php.net/manual/en/mysqli-result.fetch-array.php
 * @param mysqli_result $result A result set identifier returned by mysqli_query(),
 * mysqli_store_result() or mysqli_use_result().
 * @param int $resulttype
 * @return array|null
 */
function mysqli_fetch_array ($result, $resulttype = MYSQLI_BOTH) {}

/**
 * Fetch a result row as an associative array
 * @link http://php.net/manual/en/mysqli-result.fetch-assoc.php
 * @param mysqli_result $result A result set identifier returned by mysqli_query(),
 * mysqli_store_result() or mysqli_use_result().
 * @return array|null Returns an associative array of strings representing the fetched row in the result set,
 * where each key in the array represents the name of one of the result set's columns or NULL if there are no more rows in resultset.
 * If two or more columns of the result have the same field names, the last column will take precedence.
 * To access the other column(s) of the same name,
 * you either need to access the result with numeric indices by using mysqli_fetch_row() or add alias names.
 */
function mysqli_fetch_assoc ($result) {}

/**
 * Returns the current row of a result set as an object.
 * @link http://php.net/manual/en/mysqli-result.fetch-object.php
 * @param mysqli_result $result A result set identifier returned by mysqli_query(),
 * mysqli_store_result() or mysqli_use_result().
 * @param string $class_name The name of the class to instantiate, set the properties of and return. If not specified, a stdClass object is returned.
 * @param array|null $params An optional array of parameters to pass to the constructor for class_name objects.
 * @return object|null Returns an object with string properties that corresponds to the fetched row or NULL if there are no more rows in resultset.
 * If two or more columns of the result have the same field names, the last column will take precedence.
 * To access the other column(s) of the same name,
 * you either need to access the result with numeric indices by using mysqli_fetch_row() or add alias names.
 */
function mysqli_fetch_object ($result, $class_name = '', $params = null) {}

/**
 * Get a result row as an enumerated array
 * @link http://php.net/manual/en/mysqli-result.fetch-row.php
 * @param mysqli_result $result A result set identifier returned by mysqli_query(),
 * mysqli_store_result() or mysqli_use_result().
 * @link http://php.net/manual/en/mysqli-result.fetch-row.php
 * @return array|null mysqli_fetch_row returns an array of strings that corresponds to the fetched row
 * or &null; if there are no more rows in result set.
 */
function mysqli_fetch_row ($result) {}

/**
 * Returns the number of columns for the most recent query
 * @link http://php.net/manual/en/mysqli.field-count.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @return int An integer representing the number of fields in a result set.
 */
function mysqli_field_count ($link) {}

/**
 * Set result pointer to a specified field offset
 * @link http://php.net/manual/en/mysqli-result.field-seek.php
 * @param mysqli_result $result A result set identifier returned by mysqli_query(),
 * mysqli_store_result() or mysqli_use_result().
 * @param int $fieldnr The field number. This value must be in the range from 0 to number of fields - 1.
 * @return bool
 */
function mysqli_field_seek ($result, $fieldnr) {}

/**
 * Get current field offset of a result pointer
 * @link http://php.net/manual/en/mysqli-result.current-field.php
 * @param mysqli_result $result A result set identifier returned by mysqli_query(),
 * mysqli_store_result() or mysqli_use_result().
 * @return int
 */
function mysqli_field_tell ($result) {}

/**
 * Frees the memory associated with a result
 * @link http://php.net/manual/en/mysqli-result.free.php
 * @param mysqli_result $result A result set identifier returned by mysqli_query(),
 * mysqli_store_result() or mysqli_use_result().
 * @return void
 */
function mysqli_free_result ($result) {}

/**
 * Returns client Zval cache statistics
 * @since 5.3.0
 * Available only with mysqlnd.
 * @link http://php.net/manual/en/mysqli.get-cache-stats.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @return array|bool an array with client Zval cache stats if success, false otherwise.
 */
function mysqli_get_cache_stats ($link) {}

/**
 * Returns statistics about the client connection
 * @link http://php.net/manual/en/mysqli.get-connection-stats.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @return array|bool Returns an array with connection stats if successful, FALSE otherwise.
 */
function mysqli_get_connection_stats ($link) {}

/**
 * Returns client per-process statistics
 * @since 5.3.0
 * @link http://php.net/manual/en/mysqli.get-client-stats.php
 * @return array|bool an array with client stats if success, false otherwise.
 */
function mysqli_get_client_stats () {}

/**
 * Returns a character set object
 * @link http://php.net/manual/en/mysqli.get-charset.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @return object
 */
function mysqli_get_charset ($link) {}

/**
 * Get MySQL client info
 * @link http://php.net/manual/en/mysqli.get-client-info.php
 * @return string A string that represents the MySQL client library version
 */
function mysqli_get_client_info () {}

/**
 * Returns the MySQL client version as a string
 * @link http://php.net/manual/en/mysqli.get-client-version.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @return string
 */
function mysqli_get_client_version ($link) {}

/**
 * Returns a string representing the type of connection used
 * @link http://php.net/manual/en/mysqli.get-host-info.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @return string A character string representing the server hostname and the connection type.
 */
function mysqli_get_host_info ($link) {}

/**
 * Returns the version of the MySQL protocol used
 * @link http://php.net/manual/en/mysqli.get-proto-info.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @return int Returns an integer representing the protocol version
 */
function mysqli_get_proto_info ($link) {}

/**
 * Returns the version of the MySQL server
 * @link http://php.net/manual/en/mysqli.get-server-info.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @return string A character string representing the server version.
 */
function mysqli_get_server_info ($link) {}

/**
 * Returns the version of the MySQL server as an integer
 * @link http://php.net/manual/en/mysqli.get-server-version.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @return string An integer representing the server version.
 * The form of this version number is main_version * 10000 + minor_version * 100 + sub_version (i.e. version 4.1.0 is 40100).
 */
function mysqli_get_server_version ($link) {}

/**
 * Get result of SHOW WARNINGS
 * @link http://php.net/manual/en/mysqli.get-warnings.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @return mysqli_warning
 */
function mysqli_get_warnings ($link) {}

/**
 * Initializes MySQLi and returns a resource for use with mysqli_real_connect()
 * @link http://php.net/manual/en/mysqli.init.php
 * @return mysqli
 * @see mysqli_real_connect()
 */
function mysqli_init () {}

/**
 * Retrieves information about the most recently executed query
 * @link http://php.net/manual/en/mysqli.info.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @return string A character string representing additional information about the most recently executed query.
 */
function mysqli_info ($link) {}

/**
 * Returns the auto generated id used in the last query
 * @link http://php.net/manual/en/mysqli.insert-id.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @return int|string The value of the AUTO_INCREMENT field that was updated by the previous query. Returns zero if there was no previous query on the connection or if the query did not update an AUTO_INCREMENT value.
 * If the number is greater than maximal int value, mysqli_insert_id() will return a string.
 */
function mysqli_insert_id ($link) {}

/**
 * Asks the server to kill a MySQL thread
 * @link http://php.net/manual/en/mysqli.kill.php
 * @see mysqli_thread_id()
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @param int $processid
 * @return bool
 */
function mysqli_kill ($link, $processid) {}

/**
 * Unsets user defined handler for load local infile command
 * @link http://php.net/manual/en/mysqli.set-local-infile-default.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @return void
 */
function mysqli_set_local_infile_default ($link) {}

/**
 * Set callback function for LOAD DATA LOCAL INFILE command
 * @link http://php.net/manual/en/mysqli.set-local-infile-handler.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @param callable $read_func
 * @return bool
 */
function mysqli_set_local_infile_handler ($link, $read_func) {}

/**
 * Check if there are any more query results from a multi query
 * @link http://php.net/manual/en/mysqli.more-results.php
 * @see mysqli_multi_query()
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @return bool
 */
function mysqli_more_results ($link) {}

/**
 * Performs a query on the database
 * @link http://php.net/manual/en/mysqli.multi-query.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @param string $query One or more queries which are separated by semicolons.
 * @return bool Returns FALSE if the first statement failed. To retrieve subsequent errors from other statements you have to call mysqli_next_result() first.
 */
function mysqli_multi_query ($link, $query) {}

/**
 * Prepare next result from multi_query
 * @link http://php.net/manual/en/mysqli.next-result.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @return bool
 */
function mysqli_next_result ($link) {}

/**
 * Get the number of fields in a result
 * @link http://php.net/manual/en/mysqli-result.field-count.php
 * @param mysqli_result $result A result set identifier returned by mysqli_query(),
 * mysqli_store_result() or mysqli_use_result().
 * @return int
 */
function mysqli_num_fields ($result) {}

/**
 * Gets the number of rows in a result
 * @link http://php.net/manual/en/mysqli-result.num-rows.php
 * @param mysqli_result $result A result set identifier returned by mysqli_query(),
 * mysqli_store_result() or mysqli_use_result().
 * @return int Returns number of rows in the result set.
 */
function mysqli_num_rows ($result) {}

/**
 * Set options
 * @link http://php.net/manual/en/mysqli.options.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @param int $option
 * @param mixed $value
 * @return bool
 */
function mysqli_options ($link, $option, $value) {}

/**
 * Pings a server connection, or tries to reconnect if the connection has gone down
 * @link http://php.net/manual/en/mysqli.ping.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @return bool
 */
function mysqli_ping ($link) {}

/**
 * Poll connections
 * @link http://php.net/manual/en/mysqli.poll.php
 * @param array $read
 * @param array $write
 * @param array $error
 * @param int $sec
 * @param int $usec
 * @return int|bool Returns number of ready connections upon success, FALSE otherwise.
 */
function mysqli_poll (array &$read = null, array &$write = null, &$error = null, $sec, $usec = 0) {}

/**
 * Prepare an SQL statement for execution
 * @link http://php.net/manual/en/mysqli.prepare.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @param string $query
 * @return mysqli_stmt|bool A statement object or FALSE if an error occurred.
 */
function mysqli_prepare ($link, $query) {}

/**
 * Enables or disables internal report functions
 * @since 5.0
 * @link http://php.net/manual/en/function.mysqli-report.php
 * @param int $flags <p>
 * <table>
 * Supported flags
 * <tr valign="top">
 * <td>Name</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td><b>MYSQLI_REPORT_OFF</b></td>
 * <td>Turns reporting off</td>
 * </tr>
 * <tr valign="top">
 * <td><b>MYSQLI_REPORT_ERROR</b></td>
 * <td>Report errors from mysqli function calls</td>
 * </tr>
 * <tr valign="top">
 * <td><b>MYSQLI_REPORT_STRICT</b></td>
 * <td>
 * Throw <b>mysqli_sql_exception</b> for errors
 * instead of warnings
 * </td>
 * </tr>
 * <tr valign="top">
 * <td><b>MYSQLI_REPORT_INDEX</b></td>
 * <td>Report if no index or bad index was used in a query</td>
 * </tr>
 * <tr valign="top">
 * <td><b>MYSQLI_REPORT_ALL</b></td>
 * <td>Set all options (report all)</td>
 * </tr>
 * </table>
 * </p>
 * @return bool
 */
function mysqli_report ($flags) {}

/**
 * Performs a query on the database
 * @link http://php.net/manual/en/mysqli.query.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @param string $query An SQL query
 * @param int $resultmode
 * @return mysqli_result|bool
 * For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries, mysqli_query() will return a mysqli_result object.
 * For other successful queries mysqli_query() will return TRUE.
 * Returns FALSE on failure.
 */
function mysqli_query ($link, $query, $resultmode = MYSQLI_STORE_RESULT) {}

/**
 * Opens a connection to a mysql server
 * @link http://php.net/manual/en/mysqli.real-connect.php
 * @see mysqli_connect()
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @param string $host
 * @param string $user
 * @param string $password
 * @param string $database
 * @param string $port
 * @param string $socket
 * @param int $flags
 * @return bool
 */
function mysqli_real_connect ($link, $host = '', $user = '', $password = '', $database = '', $port = '', $socket = '', $flags = null) {}

/**
 * Escapes special characters in a string for use in an SQL statement, taking into account the current charset of the connection
 * @link http://php.net/manual/en/mysqli.real-escape-string.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @param string $escapestr The string to be escaped. Characters encoded are NUL (ASCII 0), \n, \r, \, ', ", and Control-Z.
 * @return string
 */
function mysqli_real_escape_string ($link, $escapestr) {}

/**
 * Execute an SQL query
 * @link http://php.net/manual/en/mysqli.real-query.php
 * @see mysqli_field_count()
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @param string $query
 * @return bool
 */
function mysqli_real_query ($link, $query) {}

/**
 * Get result from async query
 * Available only with mysqlnd.
 * @link http://php.net/manual/en/mysqli.reap-async-query.php
 * @see mysqli_poll()
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @return mysqli_result|bool Returns mysqli_result in success, FALSE otherwise.
 */
function mysqli_reap_async_query ($link) {}

/**
 * Set a named transaction savepoint
 * @link http://www.php.net/manual/en/mysqli.release-savepoint.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @param string $name
 * @return bool Returns TRUE on success or FALSE on failure.
 * @since 5.5.0
 */
function mysqli_release_savepoint ($link ,$name) {}

/**
 * Rolls back current transaction
 * @link http://php.net/manual/en/mysqli.rollback.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @return bool
 */
function mysqli_rollback ($link) {}

/**
 * Set a named transaction savepoint
 * @link http://www.php.net/manual/en/mysqli.savepoint.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @param string $name
 * @return bool Returns TRUE on success or FALSE on failure.
 * @since 5.5.0
 */
function mysqli_savepoint ($link ,$name) {}

/**
 * Selects the default database for database queries
 * @link http://php.net/manual/en/mysqli.select-db.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @param string $dbname
 * @return bool
 */
function mysqli_select_db ($link, $dbname) {}

/**
 * Sets the default client character set
 * @link http://php.net/manual/en/mysqli.set-charset.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @param string $charset
 * @return bool
 */
function mysqli_set_charset ($link, $charset) {}

/**
 * Returns the total number of rows changed, deleted, or inserted by the last executed statement
 * @link http://php.net/manual/en/mysqli-stmt.affected-rows.php
 * @param mysqli_stmt $stmt
 * @return int|string If the number of affected rows is greater than maximal PHP int value, the number of affected rows will be returned as a string value.
 */
function mysqli_stmt_affected_rows ($stmt) {}

/**
 * Get the current value of a statement attribute
 * @link http://php.net/manual/en/mysqli-stmt.attr-get.php
 * @param mysqli_stmt $stmt
 * @param int $attr
 * @return int|bool Returns FALSE if the attribute is not found, otherwise returns the value of the attribute.
 */
function mysqli_stmt_attr_get ($stmt, $attr) {}

/**
 * Modify the behavior of a prepared statement
 * @link http://php.net/manual/en/mysqli-stmt.attr-set.php
 * @param mysqli_stmt $stmt
 * @param int $attr
 * @param int $mode
 * @return bool
 */
function mysqli_stmt_attr_set ($stmt, $attr, $mode) {}

/**
 * Returns the number of fields in the given statement
 * @link http://php.net/manual/en/mysqli-stmt.field-count.php
 * @param mysqli_stmt $stmt
 * @return int
 */
function mysqli_stmt_field_count ($stmt) {}

/**
 * Initializes a statement and returns an object for use with mysqli_stmt_prepare
 * @link http://fr2.php.net/manual/en/mysqli.stmt-init.php
 * @return mysqli_stmt
 */
function mysqli_stmt_init () {}

/**
 * Prepare an SQL statement for execution
 * @link http://php.net/manual/en/mysqli-stmt.prepare.php
 * @param mysqli_stmt $stmt
 * @param string $query
 * @return bool
 */
function mysqli_stmt_prepare ($stmt, $query) {}

/**
 * Returns result set metadata from a prepared statement
 * @link http://php.net/manual/en/mysqli-stmt.result-metadata.php
 * @param mysqli_stmt $stmt
 * @return mysqli_result|bool Returns a result object or FALSE if an error occurred
 */
function mysqli_stmt_result_metadata ($stmt) {}

/**
 * Send data in blocks
 * @link http://php.net/manual/en/mysqli-stmt.send-long-data.php
 * @param mysqli_stmt $stmt
 * @param int $param_nr
 * @param string $data
 * @return bool
 */
function mysqli_stmt_send_long_data ($stmt, $param_nr, $data) {}

/**
 * Binds variables to a prepared statement as parameters
 * @link http://php.net/manual/en/mysqli-stmt.bind-param.php
 * @param mysqli_stmt $stmt
 * @param string $types
 * @param mixed $var1
 * @return bool
 */
function mysqli_stmt_bind_param ($stmt, $types, &$var1) {}

/**
 * Binds variables to a prepared statement for result storage
 * @link http://php.net/manual/en/mysqli-stmt.bind-result.php
 * @param mysqli_stmt $stmt Statement
 * @param mixed $var1 The variable to be bound.
 * @param mixed ...$_ The variables to be bound.
 * @return bool
 */
function mysqli_stmt_bind_result ($stmt, &$var1, &...$_) {}

/**
 * Fetch results from a prepared statement into the bound variables
 * @link http://php.net/manual/en/mysqli-stmt.fetch.php
 * @param mysqli_stmt $stmt
 * @return bool
 */
function mysqli_stmt_fetch ($stmt) {}

/**
 * Frees stored result memory for the given statement handle
 * @link http://php.net/manual/en/mysqli-stmt.free-result.php
 * @param mysqli_stmt $stmt
 * @return void
 */
function mysqli_stmt_free_result ($stmt) {}

/**
 * Gets a result set from a prepared statement
 * @link http://php.net/manual/en/mysqli-stmt.get-result.php
 * @param mysqli_stmt $stmt
 * @return mysqli_result|bool Returns a resultset or FALSE on failure.
 */
function mysqli_stmt_get_result ($stmt) {}

/**
 * Get result of SHOW WARNINGS
 * @link http://php.net/manual/en/mysqli-stmt.get-warnings.php
 * @param mysqli_stmt $stmt
 * @return object (not documented, but it's probably a mysqli_warning object)
 */
function mysqli_stmt_get_warnings ($stmt) {}

/**
 * Get the ID generated from the previous INSERT operation
 * @link http://php.net/manual/en/mysqli-stmt.insert-id.php
 * @param mysqli_stmt $stmt
 * @return mixed
 */
function mysqli_stmt_insert_id ($stmt) {}

/**
 * Resets a prepared statement
 * @link http://php.net/manual/en/mysqli-stmt.reset.php
 * @param mysqli_stmt $stmt
 * @return bool
 */
function mysqli_stmt_reset ($stmt) {}

/**
 * Returns the number of parameter for the given statement
 * @link http://php.net/manual/en/mysqli-stmt.param-count.php
 * @param mysqli_stmt $stmt
 * @return int
 */
function mysqli_stmt_param_count ($stmt) {}

/**
 * Returns the SQLSTATE error from previous MySQL operation
 * @link http://php.net/manual/en/mysqli.sqlstate.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @return string Returns a string containing the SQLSTATE error code for the last error. The error code consists of five characters. '00000' means no error.
 */
function mysqli_sqlstate ($link) {}

/**
 * Gets the current system status
 * @link http://php.net/manual/en/mysqli.stat.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @return string|bool A string describing the server status. FALSE if an error occurred.
 */
function mysqli_stat ($link) {}

/**
 * Closes a prepared statement
 * @link http://php.net/manual/en/mysqli-stmt.close.php
 * @param mysqli_stmt $stmt
 * @return bool
 */
function mysqli_stmt_close ($stmt) {}

/**
 * Seeks to an arbitrary row in statement result set
 * @link http://php.net/manual/en/mysqli-stmt.data-seek.php
 * @param mysqli_stmt $stmt
 * @param int $offset
 * @return void
 */
function mysqli_stmt_data_seek ($stmt, $offset) {}

/**
 * Returns the error code for the most recent statement call
 * @link http://php.net/manual/en/mysqli-stmt.errno.php
 * @param mysqli_stmt $stmt
 * @return bool
 */
function mysqli_stmt_errno ($stmt) {}

/**
 * Returns a string description for last statement error
 * @link http://php.net/manual/en/mysqli-stmt.error.php
 * @param mysqli_stmt $stmt
 * @return string
 */
function mysqli_stmt_error ($stmt) {}

/**
 * Check if there are more query results from a multiple query
 * @link http://php.net/manual/en/mysqli-stmt.more-results.php
 * @param mysqli_stmt $stmt
 * @return bool
 */
function mysqli_stmt_more_results ($stmt) {}

/**
 * Reads the next result from a multiple query
 * @link http://php.net/manual/en/mysqli-stmt.next-result.php
 * @param mysqli_stmt $stmt
 * @return bool
 */
function mysqli_stmt_next_result ($stmt) {}

/**
 * Return the number of rows in statements result set
 * @link http://php.net/manual/en/mysqli-stmt.num-rows.php
 * @param mysqli_stmt $stmt
 * @return int
 */
function mysqli_stmt_num_rows ($stmt) {}

/**
 * Returns SQLSTATE error from previous statement operation
 * @link http://php.net/manual/en/mysqli-stmt.sqlstate.php
 * @param mysqli_stmt $stmt
 * @return string Returns a string containing the SQLSTATE error code for the last error. The error code consists of five characters. '00000' means no error.
 */
function mysqli_stmt_sqlstate ($stmt) {}

/**
 * Transfers a result set from a prepared statement
 * @link http://php.net/manual/en/mysqli-stmt.store-result.php
 * @param mysqli_stmt $stmt
 * @return bool
 */
function mysqli_stmt_store_result ($stmt) {}

/**
 * Transfers a result set from the last query
 * @link http://php.net/manual/en/mysqli.store-result.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @return mysqli_result|bool
 */
function mysqli_store_result ($link) {}

/**
 * Returns the thread ID for the current connection
 * @link http://php.net/manual/en/mysqli.thread-id.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @return int Returns the Thread ID for the current connection.
 */
function mysqli_thread_id ($link) {}

/**
 * Returns whether thread safety is given or not
 * @link http://php.net/manual/en/mysqli.thread-safe.php
 * @return bool
 */
function mysqli_thread_safe () {}

/**
 * Initiate a result set retrieval
 * @link http://php.net/manual/en/mysqli.use-result.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @return mysqli_result|bool
 */
function mysqli_use_result ($link) {}

/**
 * Returns the number of warnings from the last query for the given link
 * @link http://php.net/manual/en/mysqli.warning-count.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @return int
 */
function mysqli_warning_count ($link) {}

/**
 * Flushes tables or caches, or resets the replication server information
 * @link http://php.net/manual/en/mysqli.refresh.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @param int $options
 * @return bool
 */
function mysqli_refresh ($link, $options) {}

/**
 * Alias for <b>mysqli_stmt_bind_param</b>
 * @link http://php.net/manual/en/function.mysqli-bind-param.php
 * @param mysqli_stmt $stmt
 * @param $types
 * @deprecated 5.3 This function has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 5.4.0.
 * @since 5.0
 */
function mysqli_bind_param ($stmt, $types) {}

/**
 * Alias for <b>mysqli_stmt_bind_result</b>
 * @link http://php.net/manual/en/function.mysqli-bind-result.php
 * @param mysqli_stmt $stmt
 * @param string $types
 * @param mixed $var1
 * @deprecated 5.3 This function has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 5.4.0.
 * @since 5.0
 */
function mysqli_bind_result ($stmt, $types, &$var1) {}

/**
 * Alias of <b>mysqli_character_set_name</b>
 * @link http://php.net/manual/en/function.mysqli-client-encoding.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @return string
 * @deprecated 5.3 This function has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 5.4.0.
 * @since 5.0
 */
function mysqli_client_encoding ($link) {}

/**
 * Alias of <b>mysqli_real_escape_string</b>
 * @link http://php.net/manual/en/function.mysqli-escape-string.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @param string $query
 * @return string
 * @since 5.0
 */
function mysqli_escape_string ($link, $query) {}

/**
 * Alias for <b>mysqli_stmt_fetch</b>
 * @link http://php.net/manual/en/function.mysqli-fetch.php
 * @param mysqli_stmt $stmt
 * @return bool
 * @deprecated 5.3 This function has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 5.4.0.
 * @since 5.0
 */
function mysqli_fetch ($stmt) {}

/**
 * Alias for <b>mysqli_stmt_param_count</b>
 * @link http://php.net/manual/en/function.mysqli-param-count.php
 * @param mysqli_stmt $stmt
 * @return int
 * @deprecated 5.3 This function has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 5.4.0.
 * @since 5.0
 */
function mysqli_param_count ($stmt) {}

/**
 * Alias for <b>mysqli_stmt_result_metadata</b>
 * @link http://php.net/manual/en/function.mysqli-get-metadata.php
 * @param mysqli_stmt $stmt
 * @return mysqli_result|bool Returns a result object or FALSE if an error occurred
 * @deprecated 5.3 This function has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 5.4.0.
 * @since 5.0
 */
function mysqli_get_metadata ($stmt) {}

/**
 * Alias for <b>mysqli_stmt_send_long_data</b>
 * @link http://php.net/manual/en/function.mysqli-send-long-data.php
 * @param mysqli_stmt $stmt
 * @param int $param_nr
 * @param string $data
 * @return bool
 * @deprecated 5.3 This function has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 5.4.0.
 * @since 5.0
 */
function mysqli_send_long_data ($stmt, $param_nr, $data) {}

/**
 * Alias of <b>mysqli_options</b>
 * @link http://php.net/manual/en/function.mysqli-set-opt.php
 * @param mysqli $link A link identifier returned by mysqli_connect() or mysqli_init()
 * @param int $option
 * @param mixed $value
 * @return bool
 * @since 5.0
 */
function mysqli_set_opt ($link, $option, $value) {}


/**
 * <p>
 * Read options from the named group from my.cnf
 * or the file specified with <b>MYSQLI_READ_DEFAULT_FILE</b>
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_READ_DEFAULT_GROUP', 5);

/**
 * <p>
 * Read options from the named option file instead of from my.cnf
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_READ_DEFAULT_FILE', 4);

/**
 * <p>
 * Connect timeout in seconds
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_OPT_CONNECT_TIMEOUT', 0);

/**
 * <p>
 * Enables command LOAD LOCAL INFILE
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_OPT_LOCAL_INFILE', 8);

/**
 * <p>
 * RSA public key file used with the SHA-256 based authentication.
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_SERVER_PUBLIC_KEY', 27);

/**
 * <p>
 * Command to execute when connecting to MySQL server. Will automatically be re-executed when reconnecting.
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_INIT_COMMAND', 3);
define ('MYSQLI_OPT_NET_CMD_BUFFER_SIZE', 202);
define ('MYSQLI_OPT_NET_READ_BUFFER_SIZE', 203);
define ('MYSQLI_OPT_INT_AND_FLOAT_NATIVE', 201);

/**
 * <p>
 * Use SSL (encrypted protocol). This option should not be set by application programs;
 * it is set internally in the MySQL client library
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_CLIENT_SSL', 2048);

/**
 * <p>
 * Use compression protocol
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_CLIENT_COMPRESS', 32);

/**
 * <p>
 * Allow interactive_timeout seconds
 * (instead of wait_timeout seconds) of inactivity before
 * closing the connection. The client's session
 * wait_timeout variable will be set to
 * the value of the session interactive_timeout variable.
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_CLIENT_INTERACTIVE', 1024);

/**
 * <p>
 * Allow spaces after function names. Makes all functions names reserved words.
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_CLIENT_IGNORE_SPACE', 256);

/**
 * <p>
 * Don't allow the db_name.tbl_name.col_name syntax.
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_CLIENT_NO_SCHEMA', 16);
define ('MYSQLI_CLIENT_FOUND_ROWS', 2);

/**
 * <p>
 * For using buffered resultsets
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_STORE_RESULT', 0);

/**
 * <p>
 * For using unbuffered resultsets
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_USE_RESULT', 1);
define ('MYSQLI_ASYNC', 8);

/**
 * <p>
 * Columns are returned into the array having the fieldname as the array index.
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_ASSOC', 1);

/**
 * <p>
 * Columns are returned into the array having an enumerated index.
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_NUM', 2);

/**
 * <p>
 * Columns are returned into the array having both a numerical index and the fieldname as the associative index.
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_BOTH', 3);

/**
 * <p>
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_STMT_ATTR_UPDATE_MAX_LENGTH', 0);

/**
 * <p>
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_STMT_ATTR_CURSOR_TYPE', 1);

/**
 * <p>
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_CURSOR_TYPE_NO_CURSOR', 0);

/**
 * <p>
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_CURSOR_TYPE_READ_ONLY', 1);

/**
 * <p>
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_CURSOR_TYPE_FOR_UPDATE', 2);

/**
 * <p>
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_CURSOR_TYPE_SCROLLABLE', 4);

/**
 * <p>
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_STMT_ATTR_PREFETCH_ROWS', 2);

/**
 * <p>
 * Indicates that a field is defined as NOT NULL
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_NOT_NULL_FLAG', 1);

/**
 * <p>
 * Field is part of a primary index
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_PRI_KEY_FLAG', 2);

/**
 * <p>
 * Field is part of a unique index.
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_UNIQUE_KEY_FLAG', 4);

/**
 * <p>
 * Field is part of an index.
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_MULTIPLE_KEY_FLAG', 8);

/**
 * <p>
 * Field is defined as BLOB
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_BLOB_FLAG', 16);

/**
 * <p>
 * Field is defined as UNSIGNED
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_UNSIGNED_FLAG', 32);

/**
 * <p>
 * Field is defined as ZEROFILL
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_ZEROFILL_FLAG', 64);

/**
 * <p>
 * Field is defined as AUTO_INCREMENT
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_AUTO_INCREMENT_FLAG', 512);

/**
 * <p>
 * Field is defined as TIMESTAMP
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_TIMESTAMP_FLAG', 1024);

/**
 * <p>
 * Field is defined as SET
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_SET_FLAG', 2048);

/**
 * <p>
 * Field is defined as NUMERIC
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_NUM_FLAG', 32768);

/**
 * <p>
 * Field is part of an multi-index
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_PART_KEY_FLAG', 16384);

/**
 * <p>
 * Field is part of GROUP BY
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_GROUP_FLAG', 32768);

/**
 * <p>
 * Field is defined as ENUM. Available since PHP 5.3.0.
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_ENUM_FLAG', 256);
define ('MYSQLI_BINARY_FLAG', 128);
define ('MYSQLI_NO_DEFAULT_VALUE_FLAG', 4096);
define ('MYSQLI_ON_UPDATE_NOW_FLAG', 8192);

/**
 * <p>
 * Field is defined as DECIMAL
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_TYPE_DECIMAL', 0);

/**
 * <p>
 * Field is defined as TINYINT
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_TYPE_TINY', 1);

/**
 * <p>
 * Field is defined as SMALLINT
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_TYPE_SHORT', 2);

/**
 * <p>
 * Field is defined as INT
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_TYPE_LONG', 3);

/**
 * <p>
 * Field is defined as FLOAT
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_TYPE_FLOAT', 4);

/**
 * <p>
 * Field is defined as DOUBLE
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_TYPE_DOUBLE', 5);

/**
 * <p>
 * Field is defined as DEFAULT NULL
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_TYPE_NULL', 6);

/**
 * <p>
 * Field is defined as TIMESTAMP
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_TYPE_TIMESTAMP', 7);

/**
 * <p>
 * Field is defined as BIGINT
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_TYPE_LONGLONG', 8);

/**
 * <p>
 * Field is defined as MEDIUMINT
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_TYPE_INT24', 9);

/**
 * <p>
 * Field is defined as DATE
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_TYPE_DATE', 10);

/**
 * <p>
 * Field is defined as TIME
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_TYPE_TIME', 11);

/**
 * <p>
 * Field is defined as DATETIME
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_TYPE_DATETIME', 12);

/**
 * <p>
 * Field is defined as YEAR
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_TYPE_YEAR', 13);

/**
 * <p>
 * Field is defined as DATE
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_TYPE_NEWDATE', 14);

/**
 * <p>
 * Field is defined as ENUM
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_TYPE_ENUM', 247);

/**
 * <p>
 * Field is defined as SET
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_TYPE_SET', 248);

/**
 * <p>
 * Field is defined as TINYBLOB
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_TYPE_TINY_BLOB', 249);

/**
 * <p>
 * Field is defined as MEDIUMBLOB
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_TYPE_MEDIUM_BLOB', 250);

/**
 * <p>
 * Field is defined as LONGBLOB
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_TYPE_LONG_BLOB', 251);

/**
 * <p>
 * Field is defined as BLOB
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_TYPE_BLOB', 252);

/**
 * <p>
 * Field is defined as VARCHAR
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_TYPE_VAR_STRING', 253);

/**
 * <p>
 * Field is defined as STRING
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_TYPE_STRING', 254);

/**
 * <p>
 * Field is defined as CHAR
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_TYPE_CHAR', 1);

/**
 * <p>
 * Field is defined as INTERVAL
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_TYPE_INTERVAL', 247);

/**
 * <p>
 * Field is defined as GEOMETRY
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_TYPE_GEOMETRY', 255);

/**
 * <p>
 * Precision math DECIMAL or NUMERIC field (MySQL 5.0.3 and up)
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_TYPE_NEWDECIMAL', 246);

/**
 * <p>
 * Field is defined as BIT (MySQL 5.0.3 and up)
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_TYPE_BIT', 16);

/**
 * <p>
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_SET_CHARSET_NAME', 7);

/**
 * <p>
 * No more data available for bind variable
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_NO_DATA', 100);

/**
 * <p>
 * Data truncation occurred. Available since PHP 5.1.0 and MySQL 5.0.5.
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_DATA_TRUNCATED', 101);

/**
 * <p>
 * Report if no index or bad index was used in a query.
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_REPORT_INDEX', 4);

/**
 * <p>
 * Report errors from mysqli function calls.
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_REPORT_ERROR', 1);

/**
 * <p>
 * Throw a mysqli_sql_exception for errors instead of warnings.
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_REPORT_STRICT', 2);

/**
 * <p>
 * Set all options on (report all).
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_REPORT_ALL', 255);

/**
 * <p>
 * Turns reporting off.
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_REPORT_OFF', 0);

/**
 * <p>
 * Is set to 1 if <b>mysqli_debug</b> functionality is enabled.
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_DEBUG_TRACE_ENABLED', 1);

/**
 * <p>
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_SERVER_QUERY_NO_GOOD_INDEX_USED', 16);

/**
 * <p>
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_SERVER_QUERY_NO_INDEX_USED', 32);

/**
 * <p>
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_REFRESH_GRANT', 1);

/**
 * <p>
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_REFRESH_LOG', 2);

/**
 * <p>
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_REFRESH_TABLES', 4);

/**
 * <p>
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_REFRESH_HOSTS', 8);

/**
 * <p>
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_REFRESH_STATUS', 16);

/**
 * <p>
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_REFRESH_THREADS', 32);

/**
 * <p>
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_REFRESH_SLAVE', 64);

/**
 * <p>
 * </p>
 * @link http://php.net/manual/en/mysqli.constants.php
 */
define ('MYSQLI_REFRESH_MASTER', 128);


define ('MYSQLI_SERVER_QUERY_WAS_SLOW', 1024);
define ('MYSQLI_REFRESH_BACKUP_LOG', 2097152);

// End of mysqli v.0.1


/** @link http://php.net/manual/en/mysqli.constants.php */
define('MYSQLI_OPT_SSL_VERIFY_SERVER_CERT', 21);
/** @link http://php.net/manual/en/mysqli.constants.php */
define('MYSQLI_SET_CHARSET_DIR', 6);
/** @link http://php.net/manual/en/mysqli.constants.php */
define('MYSQLI_SERVER_PS_OUT_PARAMS', 4096);
