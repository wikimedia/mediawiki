<?php

# Backwards compatibility wrapper for Database.php

# I imagine this file will eventually become a backwards
# compatibility wrapper around a load balancer object, and
# the load balancer will finally call Database, which will
# represent a single connection

# NB: This file follows a connect on demand scheme. Do 
# not access the $wgDatabase variable directly unless
# you intend to set it. Use wfGetDB().

include_once( "Database.php" );

# Query the database
# $db: DB_READ  = -1    read from slave (or only server)
#      DB_WRITE = -2    write to master (or only server)
#      0,1,2,...        query a database with a specific index
# Replication is not actually implemented just yet
# Usually aborts on failure
# If errors are explicitly ignored, returns success
function wfQuery( $sql, $db, $fname = "" )
{
	global $wgDatabase, $wgDBserver, $wgDBuser, $wgDBpassword, $wgDBname, 
		$wgDebugDumpSql, $wgBufferSQLResults, $wgIgnoreSQLErrors;
	
	if ( !is_numeric( $db ) ) {
		# Someone has tried to call this the old way
		$wgOut->fatalError( wfMsgNoDB( "wrong_wfQuery_params", $db, $sql ) );
	}

	$db =&  wfGetDB();
	return $db->query( $sql, $fname );
}

# Connect on demand
function &wfGetDB()
{
	global $wgDatabase, $wgDBserver, $wgDBuser, $wgDBpassword, $wgDBname, 
		$wgDebugDumpSql, $wgBufferSQLResults, $wgIgnoreSQLErrors;
	if ( !$wgDatabase ) {
		$wgDatabase = Database::newFromParams( $wgDBserver, $wgDBuser, $wgDBpassword, 
			$wgDBname, false, $wgDebugDumpSql, $wgBufferSQLResults, $wgIgnoreSQLErrors );
	}
	return $wgDatabase;
}
	
# Turns buffering of SQL result sets on (true) or off (false). Default is
# "on" and it should not be changed without good reasons. 
# Returns the previous state.

function wfBufferSQLResults( $newstate )
{
	$db =& wfGetDB();
	return $db->setBufferResults( $newstate );
}

# Turns on (false) or off (true) the automatic generation and sending
# of a "we're sorry, but there has been a database error" page on
# database errors. Default is on (false). When turned off, the
# code should use wfLastErrno() and wfLastError() to handle the
# situation as appropriate.
# Returns the previous state.

function wfIgnoreSQLErrors( $newstate )
{
	$db =& wfGetDB();
	return $db->setIgnoreErrors( $newstate );
}

function wfFreeResult( $res ) 
{ 
	$db =& wfGetDB();
	$db->freeResult( $res ); 
}

function wfFetchObject( $res ) 
{ 
	$db =& wfGetDB();
	return $db->fetchObject( $res ); 
}

function wfNumRows( $res ) 
{ 
	$db =& wfGetDB();
	return $db->numRows( $res ); 
}

function wfNumFields( $res ) 
{ 
	$db =& wfGetDB();
	return $db->numFields( $res ); 
}

function wfFieldName( $res, $n ) 
{ 
	$db =& wfGetDB();
	return $db->fieldName( $res, $n ); 
}

function wfInsertId() 
{ 
	$db =& wfGetDB();
	return $db->insertId(); 
}
function wfDataSeek( $res, $row ) 
{ 
	$db =& wfGetDB();
	return $db->dataSeek( $res, $row ); 
}

function wfLastErrno()  
{ 
	$db =& wfGetDB();
	return $db->lastErrno(); 
}

function wfLastError()  
{ 
	$db =& wfGetDB();
	return $db->lastError(); 
}

function wfAffectedRows()
{ 
	$db =& wfGetDB();
	return $db->affectedRows(); 
}

function wfLastDBquery()
{
	$db =& wfGetDB();
	return $db->lastQuery();
}

function wfSetSQL( $table, $var, $value, $cond )
{
	$db =& wfGetDB();
	return $db->set( $table, $var, $value, $cond );
}

function wfGetSQL( $table, $var, $cond )
{
	$db =& wfGetDB();
	return $db->get( $table, $var, $cond );
}

function wfFieldExists( $table, $field )
{
	$db =& wfGetDB();
	return $db->fieldExists( $table, $field );
}

function wfIndexExists( $table, $index ) 
{
	$db =& wfGetDB();
	return $db->indexExists( $table, $index );
}

function wfInsertArray( $table, $array ) 
{
	$db =& wfGetDB();
	return $db->insertArray( $table, $array );
}

function wfGetArray( $table, $vars, $conds, $fname = "wfGetArray" )
{
	$db =& wfGetDB();
	return $db->getArray( $table, $vars, $conds, $fname );
}

?>
