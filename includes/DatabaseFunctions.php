<?php
# $Id$

/**
 * Backwards compatibility wrapper for Database.php
 * 
 * Note: $wgDatabase has ceased to exist. Destroy all references.
 */

/**
 * Usually aborts on failure
 * If errors are explicitly ignored, returns success
 */
function wfQuery( $sql, $db, $fname = '' )
{
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

function wfSingleQuery( $sql, $dbi, $fname = '' )
{
	$db =& wfGetDB( $dbi );
	$res = $db->query($sql, $fname );
	$row = $db->fetchRow( $res );
	$ret = $row[0];
	$db->freeResult( $res );
	return $ret;
}

function &wfGetDB( $db = DB_LAST )
{
	global $wgLoadBalancer;
	return $wgLoadBalancer->getConnection( $db );
}
	
# Turns buffering of SQL result sets on (true) or off (false). Default is
# "on" and it should not be changed without good reasons. 
# Returns the previous state.

function wfBufferSQLResults( $newstate, $dbi = DB_LAST )
{
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->setBufferResults( $newstate );
	} else {
		return NULL;
	}
}

# Turns on (false) or off (true) the automatic generation and sending
# of a "we're sorry, but there has been a database error" page on
# database errors. Default is on (false). When turned off, the
# code should use wfLastErrno() and wfLastError() to handle the
# situation as appropriate.
# Returns the previous state.

function wfIgnoreSQLErrors( $newstate, $dbi = DB_LAST )
{
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->ignoreErrors( $newstate );
	} else {
		return NULL;
	}
}

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

function wfFetchObject( $res, $dbi = DB_LAST ) 
{ 
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->fetchObject( $res, $dbi = DB_LAST ); 
	} else {	
		return false;
	}
}

function wfFetchRow( $res, $dbi = DB_LAST )
{
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->fetchRow ( $res, $dbi = DB_LAST );
	} else {	
		return false;
	}
}

function wfNumRows( $res, $dbi = DB_LAST ) 
{ 
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->numRows( $res, $dbi = DB_LAST ); 
	} else {	
		return false;
	}
}

function wfNumFields( $res, $dbi = DB_LAST ) 
{ 
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->numFields( $res ); 
	} else {	
		return false;
	}
}

function wfFieldName( $res, $n, $dbi = DB_LAST ) 
{ 
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->fieldName( $res, $n, $dbi = DB_LAST ); 
	} else {	
		return false;
	}
}

function wfInsertId( $dbi = DB_LAST ) 
{ 
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->insertId(); 
	} else {	
		return false;
	}
}

function wfDataSeek( $res, $row, $dbi = DB_LAST ) 
{ 
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->dataSeek( $res, $row ); 
	} else {	
		return false;
	}
}

function wfLastErrno( $dbi = DB_LAST )  
{ 
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->lastErrno(); 
	} else {	
		return false;
	}
}

function wfLastError( $dbi = DB_LAST )  
{ 
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->lastError(); 
	} else {	
		return false;
	}
}

function wfAffectedRows( $dbi = DB_LAST )
{ 
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->affectedRows(); 
	} else {	
		return false;
	}
}

function wfLastDBquery( $dbi = DB_LAST )
{
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->lastQuery();
	} else {	
		return false;
	}
}

function wfSetSQL( $table, $var, $value, $cond, $dbi = DB_MASTER )
{
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->set( $table, $var, $value, $cond );
	} else {	
		return false;
	}
}

function wfGetSQL( $table, $var, $cond='', $dbi = DB_LAST )
{
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->getField( $table, $var, $cond );
	} else {	
		return false;
	}
}

function wfFieldExists( $table, $field, $dbi = DB_LAST )
{
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->fieldExists( $table, $field );
	} else {	
		return false;
	}
}

function wfIndexExists( $table, $index, $dbi = DB_LAST ) 
{
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->indexExists( $table, $index );
	} else {	
		return false;
	}
}

function wfInsertArray( $table, $array, $fname = 'wfInsertArray', $dbi = DB_MASTER ) 
{
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->insertArray( $table, $array, $fname );
	} else {	
		return false;
	}
}

function wfGetArray( $table, $vars, $conds, $fname = 'wfGetArray', $dbi = DB_LAST )
{
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->getArray( $table, $vars, $conds, $fname );
	} else {	
		return false;
	}
}

function wfUpdateArray( $table, $values, $conds, $fname = 'wfUpdateArray', $dbi = DB_MASTER )
{
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		$db->updateArray( $table, $values, $conds, $fname );
		return true;
	} else {
		return false;
	}
}

function wfTableName( $name, $dbi = DB_LAST ) {
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->tableName( $name );
	} else {
		return false;
	}
}

function wfStrencode( $s, $dbi = DB_LAST )
{
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->strencode( $s );
	} else {
		return false;
	}
}

function wfNextSequenceValue( $seqName, $dbi = DB_MASTER ) {
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->nextSequenceValue( $seqName );
	} else {
		return false;
	}
}

function wfUseIndexClause( $index, $dbi = DB_SLAVE ) {
	$db =& wfGetDB( $dbi );
	if ( $db !== false ) {
		return $db->useIndexClause( $index );
	} else {
		return false;
	}
}
?>
