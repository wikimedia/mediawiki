<?php
/**
 * Base interface for all DBMS-specific code. At a bare minimum, all of the
 * following must be implemented to support MediaWiki
 *
 * @file
 * @ingroup Database
 */
interface DatabaseType {

	/**
	 * Get the type of the DBMS, as it appears in $wgDBtype.
	 *
	 * @return string
	 */
	public function getType();

	/**
	 * Open a connection to the database. Usually aborts on failure
	 * If the failFunction is set to a non-zero integer, returns success
	 *
	 * @param $server String: database server host
	 * @param $user String: database user name
	 * @param $password String: database user password
	 * @param $dbName String: database name
	 * @return bool
	 * @throws DBConnectionError
	 */
	public function open( $server, $user, $password, $dbName );

	/**
	 * The DBMS-dependent part of query()
	 * @todo @fixme Make this private someday
	 *
	 * @param  $sql String: SQL query.
	 * @return Result object to feed to fetchObject, fetchRow, ...; or false on failure
	 * @private
	 */
	/*private*/ function doQuery( $sql );

	/**
	 * Fetch the next row from the given result object, in object form.
	 * Fields can be retrieved with $row->fieldname, with fields acting like
	 * member variables.
	 *
	 * @param $res SQL result object as returned from DatabaseBase::query(), etc.
	 * @return Row object
	 * @throws DBUnexpectedError Thrown if the database returns an error
	 */
	public function fetchObject( $res );

	/**
	 * Fetch the next row from the given result object, in associative array
	 * form.  Fields are retrieved with $row['fieldname'].
	 *
	 * @param $res SQL result object as returned from DatabaseBase::query(), etc.
	 * @return Row object
	 * @throws DBUnexpectedError Thrown if the database returns an error
	 */
	public function fetchRow( $res );

	/**
	 * Get the number of rows in a result object
	 *
	 * @param $res Mixed: A SQL result
	 * @return int
	 */
	public function numRows( $res );

	/**
	 * Get the number of fields in a result object
	 * @see http://www.php.net/mysql_num_fields
	 *
	 * @param $res Mixed: A SQL result
	 * @return int
	 */
	public function numFields( $res );

	/**
	 * Get a field name in a result object
	 * @see http://www.php.net/mysql_field_name
	 *
	 * @param $res Mixed: A SQL result
	 * @param $n Integer
	 * @return string
	 */
	public function fieldName( $res, $n );

	/**
	 * Get the inserted value of an auto-increment row
	 *
	 * The value inserted should be fetched from nextSequenceValue()
	 *
	 * Example:
	 * $id = $dbw->nextSequenceValue('page_page_id_seq');
	 * $dbw->insert('page',array('page_id' => $id));
	 * $id = $dbw->insertId();
	 *
	 * @return int
	 */
	public function insertId();

	/**
	 * Change the position of the cursor in a result object
	 * @see http://www.php.net/mysql_data_seek
	 *
	 * @param $res Mixed: A SQL result
	 * @param $row Mixed: Either MySQL row or ResultWrapper
	 */
	public function dataSeek( $res, $row );

	/**
	 * Get the last error number
	 * @see http://www.php.net/mysql_errno
	 *
	 * @return int
	 */
	public function lastErrno();

	/**
	 * Get a description of the last error
	 * @see http://www.php.net/mysql_error
	 *
	 * @return string
	 */
	public function lastError();

	/**
	 * mysql_fetch_field() wrapper
	 * Returns false if the field doesn't exist
	 *
	 * @param $table string: table name
	 * @param $field string: field name
	 */
	public function fieldInfo( $table, $field );

	/**
	 * Get the number of rows affected by the last write query
	 * @see http://www.php.net/mysql_affected_rows
	 *
	 * @return int
	 */
	public function affectedRows();

	/**
	 * Wrapper for addslashes()
	 *
	 * @param $s string: to be slashed.
	 * @return string: slashed string.
	 */
	public function strencode( $s );

	/**
	 * Returns a wikitext link to the DB's website, e.g.,
	 *     return "[http://www.mysql.com/ MySQL]";
	 * Should at least contain plain text, if for some reason
	 * your database has no website.
	 *
	 * @return string: wikitext of a link to the server software's web site
	 */
	public static function getSoftwareLink();

	/**
	 * A string describing the current software version, like from
	 * mysql_get_server_info().  Will be listed on Special:Version, etc.
	 *
	 * @return string: Version information from the database
	 */
	public function getServerVersion();
}
