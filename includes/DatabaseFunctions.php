<?php
# $Id$

# Backwards compatibility wrapper for Database.php

# I imagine this file will eventually become a backwards
# compatibility wrapper around a load balancer object, and
# the load balancer will finally call Database, which will
# represent a single connection

# NB: This file follows a connect on demand scheme. Do 
# not access the $wgDatabase variable directly unless
# you intend to set it. Use wfGetDB().
$wgDatabase = false;

$wgIsMySQL=false;
$wgIsPg=false;

if ($wgDBtype=="mysql") {
    require_once( "Database.php" );
    $wgIsMySQL=true;
} elseif ($wgDBtype=="pgsql") {
    require_once( "DatabasePostgreSQL.php" );
    $wgIsPg=true;
} 


# Replication is not actually implemented just yet
# Usually aborts on failure
# If errors are explicitly ignored, returns success
function wfQuery( $sql, $db, $fname = "" )
{
	if ( !is_numeric( $db ) ) {
		# Someone has tried to call this the old way
		$wgOut->fatalError( wfMsgNoDB( "wrong_wfQuery_params", $db, $sql ) );
	}
	$c =& wfGetDB( $db );
	return $c->query( $sql, $fname );
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
	return $db->setBufferResults( $newstate );
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
	return $db->setIgnoreErrors( $newstate );
}

function wfFreeResult( $res, $dbi = DB_LAST ) 
{ 
	$db =& wfGetDB( $dbi );
	$db->freeResult( $res ); 
}

function wfFetchObject( $res, $dbi = DB_LAST ) 
{ 
	$db =& wfGetDB( $dbi );
	return $db->fetchObject( $res, $dbi = DB_LAST ); 
}

function wfFetchRow( $res, $dbi = DB_LAST )
{
	$db =& wfGetDB( $dbi );
	return $db->fetchRow ( $res, $dbi = DB_LAST );
}

function wfNumRows( $res, $dbi = DB_LAST ) 
{ 
	$db =& wfGetDB( $dbi );
	return $db->numRows( $res, $dbi = DB_LAST ); 
}

function wfNumFields( $res, $dbi = DB_LAST ) 
{ 
	$db =& wfGetDB( $dbi );
	return $db->numFields( $res ); 
}

function wfFieldName( $res, $n, $dbi = DB_LAST ) 
{ 
	$db =& wfGetDB( $dbi );
	return $db->fieldName( $res, $n, $dbi = DB_LAST ); 
}

function wfInsertId( $dbi = DB_LAST ) 
{ 
	$db =& wfGetDB( $dbi );
	return $db->insertId(); 
}
function wfDataSeek( $res, $row, $dbi = DB_LAST ) 
{ 
	$db =& wfGetDB( $dbi );
	return $db->dataSeek( $res, $row ); 
}

function wfLastErrno( $dbi = DB_LAST )  
{ 
	$db =& wfGetDB( $dbi );
	return $db->lastErrno(); 
}

function wfLastError( $dbi = DB_LAST )  
{ 
	$db =& wfGetDB( $dbi );
	return $db->lastError(); 
}

function wfAffectedRows( $dbi = DB_LAST )
{ 
	$db =& wfGetDB( $dbi );
	return $db->affectedRows(); 
}

function wfLastDBquery( $dbi = DB_LAST )
{
	$db =& wfGetDB( $dbi );
	return $db->lastQuery();
}

function wfSetSQL( $table, $var, $value, $cond, $dbi = DB_WRITE )
{
	$db =& wfGetDB( $dbi );
	return $db->set( $table, $var, $value, $cond );
}

function wfGetSQL( $table, $var, $cond="", $dbi = DB_LAST )
{
	$db =& wfGetDB( $dbi );
	return $db->get( $table, $var, $cond );
}

function wfFieldExists( $table, $field, $dbi = DB_LAST )
{
	$db =& wfGetDB( $dbi );
	return $db->fieldExists( $table, $field );
}

function wfIndexExists( $table, $index, $dbi = DB_LAST ) 
{
	$db =& wfGetDB( $dbi );
	return $db->indexExists( $table, $index );
}

function wfInsertArray( $table, $array, $fname = "wfInsertArray", $dbi = DB_WRITE ) 
{
	$db =& wfGetDB( $dbi );
	return $db->insertArray( $table, $array, $fname );
}

function wfGetArray( $table, $vars, $conds, $fname = "wfGetArray", $dbi = DB_LAST )
{
	$db =& wfGetDB( $dbi );
	return $db->getArray( $table, $vars, $conds, $fname );
}

function wfUpdateArray( $table, $values, $conds, $fname = "wfUpdateArray", $dbi = DB_WRITE )
{
	$db =& wfGetDB( $dbi );
	$db->updateArray( $table, $values, $conds, $fname );
}

?>
