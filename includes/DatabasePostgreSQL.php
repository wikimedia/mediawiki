<?php
# $Id$
#
# DO NOT USE !!!  Unless you want to help developping it.
#
# This file is an attempt to port the mysql database layer to postgreSQL. The
# only thing done so far is s/mysql/pg/ and dieing if function haven't been
# ported.
# 
# As said brion 07/06/2004 :
# "table definitions need to be changed. fulltext index needs to work differently
#  things that use the last insert id need to be changed. Probably other things
#  need to be changed. various semantics may be different."
#
# Hashar

require_once( "Database.php" );

class DatabasePgsql extends Database {
	var $mInsertId = NULL;

	function DatabasePgsql($server = false, $user = false, $password = false, $dbName = false, 
		$failFunction = false, $flags = 0, $tablePrefix = 'get from global' )
	{
		Database::Database( $server, $user, $password, $dbName, $failFunction, $flags, $tablePrefix );
	}

	/* static */ function newFromParams( $server = false, $user = false, $password = false, $dbName = false, 
		$failFunction = false, $flags = 0, $tablePrefix = 'get from global' )
	{
		return new DatabasePgsql( $server, $user, $password, $dbName, $failFunction, $flags, $tablePrefix );
	}

	# Usually aborts on failure
	# If the failFunction is set to a non-zero integer, returns success
	function open( $server, $user, $password, $dbName )
	{
		# Test for PostgreSQL support, to avoid suppressed fatal error
		if ( !function_exists( 'pg_connect' ) ) {
			die( "PostgreSQL functions missing, have you compiled PHP with the --with-pgsql option?\n" );
		}

		$this->close();
		$this->mServer = $server;
		$this->mUser = $user;
		$this->mPassword = $password;
		$this->mDBname = $dbName;
		
		$success = false;
		
		if ( "" != $dbName ) {
			# start a database connection
			@$this->mConn = pg_connect("host=$server dbname=$dbName user=$user password=$password");
			if ( $this->mConn == false ) {
				wfDebug( "DB connection error\n" );
				wfDebug( "Server: $server, Database: $dbName, User: $user, Password: " . substr( $password, 0, 3 ) . "...\n" );
				wfDebug( $this->lastError()."\n" );
			} else { 
				$this->mOpened = true;
			}
		}
		return $this->mConn;
	}
	
	# Closes a database connection, if it is open
	# Returns success, true if already closed
	function close()
	{
		$this->mOpened = false;
		if ( $this->mConn ) {
			return pg_close( $this->mConn );
		} else {
			return true;
		}
	}
	
	function doQuery( $sql ) {
		return pg_query( $this->mConn , $sql);
	}
		
	function queryIgnore( $sql, $fname = "" ) {
		return $this->query( $sql, $fname, true );
	}
	
	function freeResult( $res ) {
		if ( !@pg_free_result( $res ) ) {
			wfDebugDieBacktrace( "Unable to free PostgreSQL result\n" );
		}
	}
	function fetchObject( $res ) {
		@$row = pg_fetch_object( $res );
		# FIXME: HACK HACK HACK HACK debug
		
		# TODO:
		# hashar : not sure if the following test really trigger if the object
		#          fetching failled.
		if( pg_last_error($this->mConn) ) {
			wfDebugDieBacktrace( "SQL error: " . htmlspecialchars( pg_last_error($this->mConn) ) );
		}
		return $row;
	}

	function fetchRow( $res ) {
		@$row = pg_fetch_array( $res );
                if( pg_last_error($this->mConn) ) {
                        wfDebugDieBacktrace( "SQL error: " . htmlspecialchars( pg_last_error($this->mConn) ) );
                }
		return $row;
	}

	function numRows( $res ) {
		@$n = pg_num_rows( $res ); 
		if( pg_last_error($this->mConn) ) {
			wfDebugDieBacktrace( "SQL error: " . htmlspecialchars( pg_last_error($this->mConn) ) );
		}
		return $n;
	}
	function numFields( $res ) { return pg_num_fields( $res ); }
	function fieldName( $res, $n ) { return pg_field_name( $res, $n ); }
	
	# This must be called after nextSequenceVal
	function insertId() { 
		return $this->mInsertId;
	}

	function dataSeek( $res, $row ) { return pg_result_seek( $res, $row ); }
	function lastError() { return pg_last_error(); }
	function lastErrno() { return 1; }

	function affectedRows() { 
		return pg_affected_rows( $this->mLastResult ); 
	}
	
	# Returns information about an index
	# If errors are explicitly ignored, returns NULL on failure
	function indexInfo( $table, $index, $fname = "Database::indexExists" ) 
	{
		$sql = "SELECT indexname FROM pg_indexes WHERE tablename='$table'";
		$res = $this->query( $sql, $fname );
		if ( !$res ) {
			return NULL;
		}
		
		while ( $row = $this->fetchObject( $res ) ) {
			if ( $row->Key_name == $index ) {
				return $row;
			}
		}
		return false;
	}

	function fieldInfo( $table, $field )
	{
		wfDebugDieBacktrace( "Database::fieldInfo() error : mysql_fetch_field() not implemented for postgre" );
		/*
		$res = $this->query( "SELECT * FROM '$table' LIMIT 1" );
		$n = pg_num_fields( $res );
		for( $i = 0; $i < $n; $i++ ) {
			// FIXME
			wfDebugDieBacktrace( "Database::fieldInfo() error : mysql_fetch_field() not implemented for postgre" );
			$meta = mysql_fetch_field( $res, $i );
			if( $field == $meta->name ) {
				return $meta;
			}
		}
		return false;*/
	}

	function insertArray( $table, $a, $fname = "Database::insertArray", $options = array() ) {
		# PostgreSQL doesn't support options
		# We have a go at faking one of them
		# TODO: DELAYED, LOW_PRIORITY 

		# IGNORE is performed using single-row inserts, ignoring errors in each
		if ( in_array( 'IGNORE', $options ) ) {
			# FIXME: need some way to distiguish between key collision and other types of error
			$oldIgnore = $this->ignoreErrors( true );
			if ( !is_array( reset( $a ) ) ) {
				$a = array( $a );
			}
			foreach ( $a as $row ) {
				parent::insertArray( $table, $row, $fname, array() );
			}
			$this->ignoreErrors( $oldIgnore );
			$retVal = true;
		} else {
			$retVal = parent::insertArray( $table, $a, $fname, array() );
		}
		return $retVal;
	}
	
	function startTimer( $timeout )
	{
		global $IP;
		wfDebugDieBacktrace( "Database::startTimer() error : mysql_thread_id() not implemented for postgre" );
		/*$tid = mysql_thread_id( $this->mConn );
		exec( "php $IP/killthread.php $timeout $tid &>/dev/null &" );*/
	}

	function tableName( $name ) {
		# First run any transformations from the parent object
		$name = parent::tableName( $name );

		# Now quote PG reserved keywords
		switch( $name ) {
			case 'user':
				return '"user"';
			case 'old':
				return '"old"';
			default:
				return $name;
		}
	}

	function strencode( $s ) {
		return pg_escape_string( $s );
	}

	# Return the next in a sequence, save the value for retrieval via insertId()
	function nextSequenceValue( $seqName ) {
		$value = $this->getField(""," nextval('" . $seqName . "')");
		$this->mInsertId = $value;
		return $value;
	}

	# USE INDEX clause
	# PostgreSQL doesn't have them and returns ""
	function useIndexClause( $index ) {
		return '';
	}

	# REPLACE query wrapper
	# PostgreSQL simulates this with a DELETE followed by INSERT
	# $row is the row to insert, an associative array
	# $uniqueIndexes is an array of indexes. Each element may be either a 
	# field name or an array of field names
	#
	# It may be more efficient to leave off unique indexes which are unlikely to collide. 
	# However if you do this, you run the risk of encountering errors which wouldn't have 
	# occurred in MySQL
	function replace( $table, $uniqueIndexes, $rows, $fname = "Database::replace" ) {
		$table = $this->tableName( $table );

		# Single row case
		if ( !is_array( reset( $rows ) ) ) {
			$rows = array( $rows );
		}

		foreach( $rows as $row ) {
			# Delete rows which collide
			if ( $uniqueIndexes ) {
				$sql = "DELETE FROM $table WHERE (";
				$first = true;
				foreach ( $uniqueIndexes as $index ) {
					if ( $first ) {
						$first = false;
					} else {
						$sql .= ") OR (";
					}
					if ( is_array( $index ) ) {
						$first2 = true;
						$sql .= "(";
						foreach ( $index as $col ) {
							if ( $first2 ) { 
								$first2 = false;
							} else {
								$sql .= " AND ";
							}
							$sql .= "$col=" . $this->addQuotes( $row[$col] );
						}
				} else {
						$sql .= "$index=" . $this->addQuotes( $row[$index] );
				}
				}
				$sql .= ")";
				$this->query( $sql, $fname );
			}

			# Now insert the row
			$sql = "INSERT INTO $table (" . $this->makeList( array_flip( $row ) ) .') VALUES (' .
				$this->makeList( $row, LIST_COMMA ) . ')';
			$this->query( $sql, $fname );
		}
	}

	# DELETE where the condition is a join
	function deleteJoin( $delTable, $joinTable, $delVar, $joinVar, $conds, $fname = "Database::deleteJoin" ) {
		if ( !$conds ) {
			wfDebugDieBacktrace( 'Database::deleteJoin() called with empty $conds' );
		}

		$delTable = $this->tableName( $delTable );
		$joinTable = $this->tableName( $joinTable );
		$sql = "DELETE FROM $delTable WHERE $delVar IN (SELECT $joinVar FROM $joinTable ";
		if ( $conds != '*' ) {
			$sql .= "WHERE " . $this->makeList( $conds, LIST_AND );
		}
		$sql .= ")";

		$this->query( $sql, $fname );
	}

	# Returns the size of a text field, or -1 for "unlimited"
	function textFieldSize( $table, $field ) {
		$table = $this->tableName( $table );
		$res = $this->query( "SELECT $field FROM $table LIMIT 1", "Database::textFieldLength" );
		$size = pg_field_size( $res, 0 );
		$this->freeResult( $res );
		return $size;
	}
	
	function lowPriorityOption() {
		return '';
	}

	function limitResult($limit,$offset) {
        	return " LIMIT $limit ".(is_numeric($offset)?" OFFSET {$offset} ":"");
	}

	# FIXME: actually detecting deadlocks might be nice
	function wasDeadlock() {
		return false;
	}
}

# Just an alias.
class DatabasePostgreSQL extends DatabasePgsql {
}

?>
