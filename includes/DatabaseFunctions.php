<?php
/**
 * Backwards compatibility wrapper for Database.php
 * 
 * Note: $wgDatabase has ceased to exist. Destroy all references.
 *
 * @package MediaWiki
 */

/**
 * Usually aborts on failure
 * If errors are explicitly ignored, returns success
 * @param string $sql SQL query
 * @param mixed $db database handler
 * @param string $fname name of the php function calling
 */
function wfQuery( $sql, $db, $fname = '' ) {
	global $wgOut;
	if ( !is_numeric( $db ) ) {
		# Someone has tried to call this the old way
		$wgOut->fatalError( wfMsgNoDB( 'wrong_wfQuery_params', $db, $sql ) );
	}
	$c =& wfGetDB( $db );
	if ( $c !== false ) {
		return $c->query( $sql, $fname );
	} else {	
		return false;
	}
}

/**
 *
 * @param string $sql SQL query
 * @param $dbi
 * @param string $fname name of the php function calling
 * @return array first row from the database
 */
function wfSingleQuery( $sql, $dbi, $fname = '' ) {
	$db =& wfGetDB( $dbi );
	$res = $db->query($sql, $fname );
	$row = $db->fetchRow( $res );
	$ret = $row[0];
	$db->freeResult( $res );
	return $ret;
}

/*
 * @todo document function
 */
function &wfGetDB( $db = DB_LAST ) {
	global $wgLoadBalancer;
	return $wgLoadBalancer->getConnection( $db );
}
	
/**
 * Turns buffering of SQL result
 * Sets on (true) or off (false). Default is "on" and it should not be changed
 * without good reasons.
 *
 * @param $newstate
 * @param $dbi
 * @return mixed|NULL Returns the previous state.
*/
function wfBufferSQLResults( $newstate, $dbi = DB_LAST ) {
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->setBufferResults( $newstate );
	} else {
		return NULL;
	}
}

/**
 * Turns on (false) or off (true) the automatic generation and sending
 * of a "we're sorry, but there has been a database error" page on
 * database errors. Default is on (false). When turned off, the
 * code should use wfLastErrno() and wfLastError() to handle the
 * situation as appropriate.
 *
 * @param $newstate
 * @param $dbi
 * @return Returns the previous state.
 */
function wfIgnoreSQLErrors( $newstate, $dbi = DB_LAST ) {
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->ignoreErrors( $newstate );
	} else {
		return NULL;
	}
}

/**#@+
 * @param $res database result handler
 * @param $dbi
*/

/**
 * Free a database result
 * @return bool whether result is sucessful or not
 */
function wfFreeResult( $res, $dbi = DB_LAST ) 
{ 
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		$db->freeResult( $res ); 
		return true;
	} else {	
		return false;
	}
}

/**
 * Get an object from a database result
 * @return object|false object we requested
 */
function wfFetchObject( $res, $dbi = DB_LAST ) { 
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->fetchObject( $res, $dbi = DB_LAST ); 
	} else {	
		return false;
	}
}

/**
 * Get a row from a database result
 * @return object|false row we requested
 */
function wfFetchRow( $res, $dbi = DB_LAST ) {
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->fetchRow ( $res, $dbi = DB_LAST );
	} else {	
		return false;
	}
}

/**
 * Get a number of rows from a database result
 * @return integer|false number of rows
 */
function wfNumRows( $res, $dbi = DB_LAST ) { 
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->numRows( $res, $dbi = DB_LAST ); 
	} else {	
		return false;
	}
}

/**
 * Get the number of fields from a database result
 * @return integer|false number of fields
 */
function wfNumFields( $res, $dbi = DB_LAST ) { 
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->numFields( $res ); 
	} else {	
		return false;
	}
}

/**
 * Return name of a field in a result
 * @param integer $n id of the field
 * @return string|false name of field
 */
function wfFieldName( $res, $n, $dbi = DB_LAST ) 
{ 
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->fieldName( $res, $n, $dbi = DB_LAST ); 
	} else {	
		return false;
	}
}
/**#@-*/

/**
 * @todo document function
 */
function wfInsertId( $dbi = DB_LAST ) { 
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->insertId(); 
	} else {	
		return false;
	}
}

/**
 * @todo document function
 */
function wfDataSeek( $res, $row, $dbi = DB_LAST ) { 
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->dataSeek( $res, $row ); 
	} else {	
		return false;
	}
}

/**
 * @todo document function
 */
function wfLastErrno( $dbi = DB_LAST ) { 
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->lastErrno(); 
	} else {	
		return false;
	}
}

/**
 * @todo document function
 */
function wfLastError( $dbi = DB_LAST ) { 
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->lastError(); 
	} else {	
		return false;
	}
}

/**
 * @todo document function
 */
function wfAffectedRows( $dbi = DB_LAST ) { 
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->affectedRows(); 
	} else {	
		return false;
	}
}

/**
 * @todo document function
 */
function wfLastDBquery( $dbi = DB_LAST ) {
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->lastQuery();
	} else {	
		return false;
	}
}

/**
 * @todo document function
 */
function wfSetSQL( $table, $var, $value, $cond, $dbi = DB_MASTER )
{
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->set( $table, $var, $value, $cond );
	} else {	
		return false;
	}
}


/**
 * @todo document function
 */
function wfGetSQL( $table, $var, $cond='', $dbi = DB_LAST )
{
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->selectField( $table, $var, $cond );
	} else {	
		return false;
	}
}

/**
 * @todo document function
 */
function wfFieldExists( $table, $field, $dbi = DB_LAST ) {
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->fieldExists( $table, $field );
	} else {	
		return false;
	}
}

/**
 * @todo document function
 */
function wfIndexExists( $table, $index, $dbi = DB_LAST ) {
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->indexExists( $table, $index );
	} else {	
		return false;
	}
}

/**
 * @todo document function
 */
function wfInsertArray( $table, $array, $fname = 'wfInsertArray', $dbi = DB_MASTER ) {
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->insert( $table, $array, $fname );
	} else {	
		return false;
	}
}

/**
 * @todo document function
 */
function wfGetArray( $table, $vars, $conds, $fname = 'wfGetArray', $dbi = DB_LAST ) {
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->getArray( $table, $vars, $conds, $fname );
	} else {	
		return false;
	}
}

/**
 * @todo document function
 */
function wfUpdateArray( $table, $values, $conds, $fname = 'wfUpdateArray', $dbi = DB_MASTER ) {
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		$db->update( $table, $values, $conds, $fname );
		return true;
	} else {
		return false;
	}
}

/**
 * @todo document function
 */
function wfTableName( $name, $dbi = DB_LAST ) {
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->tableName( $name );
	} else {
		return false;
	}
}

/**
 * @todo document function
 */
function wfStrencode( $s, $dbi = DB_LAST ) {
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->strencode( $s );
	} else {
		return false;
	}
}

/**
 * @todo document function
 */
function wfNextSequenceValue( $seqName, $dbi = DB_MASTER ) {
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->nextSequenceValue( $seqName );
	} else {
		return false;
	}
}

/**
 * @todo document function
 */
function wfUseIndexClause( $index, $dbi = DB_SLAVE ) {
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->useIndexClause( $index );
	} else {
		return false;
	}
}
?>
