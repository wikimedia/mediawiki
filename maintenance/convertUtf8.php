<?php
/**
 * @package MediaWiki
 * @subpackage Maintenance
 */

die("This file is not complete; it's checked in so I don't forget it.");

/*
UTF-8 conversion of DOOOOOOOM

1. Lock the wiki
2. Make a convertlist of all pages
3. Enable CONVERTLOCK mode and switch to UTF-8
4. As quick as possible, convert the cur, images, *links, user, etc tables. Clear cache tables.
5. Unlock the wiki. Attempts to access pages on the convertlist will be trapped to read-only.
6. Go through the list, fixing up old revisions. Remove pages from the convertlist.
*/


class UtfUpdater {
	function UtfUpdater() {
		$this->db =& wfGetDB( DB_MASTER );
	}
	
	function toUtf8( $string ) {
		if( function_exists( 'iconv' ) ) {
			# There are likely to be Windows code page 1252 chars in there.
			# Convert them to the proper UTF-8 chars if possible.
			return iconv( 'CP1252', 'UTF-8', $string );
		} else {
			# Will work from plain iso 8859-1 and may corrupt these chars
			return utf8_encode( $string );
		}
	}

	function clearTable( $table ) {
		print "Clearing $table...\n";
		$tableName = $this->db->tableName( $table );
		$this->db->query( 'TRUNCATE $tableName' );
	}
	
	/**
	 * @param string $table Table to be converted
	 * @param string $key Primary key, to identify fields in the UPDATE. If NULL, all fields will be used to match.
	 * @param array $fields List of all fields to grab and convert. If null, will assume you want the $key, and will ask for DISTINCT.
	 * @param array $timestamp A field which should be updated to the current timestamp on changed records.
	 * @access private
	 */
	function convertTable( $table, $key, $fields = null, $timestamp = null ) {
		$fname = 'UtfUpdater::convertTable';
		if( $fields ) {
			$distinct = '';
		} else {
			# If working on one key only, there will be multiple rows.
			# Use DISTINCT to return only one and save us some trouble.
			$fields = array( $key );
			$distinct = 'DISTINCT';
		}
		$condition = '';
		foreach( $fields as $field ) {
			if( $condition ) $condition .= ' OR ';
			$condition .= "$field RLIKE '[\x80-\xff]'";
		}
		$res = $this->db->selectArray(
			$table,
			array_merge( $fields, array( $key ) ),
			$condition,
			$fname,
			$distinct );
		print "Converting " . $this->db->numResults( $res ) . " rows from $table:\n";
		$n = 0;
		while( $s = $this->db->fetchObject( $res ) ) {
			$set = array();
			foreach( $fields as $field ) {
				$set[] = $this->toUtf8( $s->$field );
			}
			if( $timestamp ) {
				$set[$timestamp] = $this->db->timestamp();
			}
			if( $key ) {
				$keyCond = array( $key, $s->$key );
			} else {
				$keyCond = array();
				foreach( $fields as $field ) {
					$keyCond[$field] = $s->$field;
				}
			}
			$this->db->updateArray(
				$table,
				$set,
				$keyCond,
				$fname );
			if( ++$n % 100 == 0 ) echo "$n\n";
		}
		echo "$n done.\n";
		$this->db->freeResult( $res );
	}
	
	function lockTables( $tables ) {
		$query = '';
		foreach( $tables as $table ) {
			$tableName = $this->db->tableName( $table );
			if( $query ) $query .= ', ';
			$query .= '$tableName WRITE';
		}
		$this->db->query( 'LOCK TABLES ' . $query );
	}
	
	function updateAll() {
		$this->lockTables( array(
			'linkscc', 'objectcache', 'searchindex', 'querycache',
			'ipblocks', 'user', 'page', 'revision', 'recentchanges',
			'brokenlinks', 'categorylinks', 'imagelinks', 'watchlist',
			'image', 'oldimage', 'archive' ) );
		
		# These are safe to clear out:
		$this->clearTable( 'linkscc' );
		$this->clearTable( 'objectcache' );

		# These need to be rebuild if used:
		$this->clearTable( 'searchindex' );
		$this->clearTable( 'querycache' );

		# And convert the rest...
		$this->convertTable( 'ipblocks', 'ipb_id', array( 'ipb_reason' ) );
		$this->convertTable( 'user', 'user_id', 
			array( 'user_name', 'user_real_name', 'user_options' ),
			'user_touched' );
		$this->convertTable( 'page', 'page_id',
			array( 'page_title' ), 'page_touched' );
		$this->convertTable( 'revision', 'rev_id',
			array( 'rev_user_text', 'rev_comment' ) );
		
		$this->convertTable( 'recentchanges', 'rc_id',
			array( 'rc_user_text', 'rc_title', 'rc_comment' ) );
		
		$this->convertTable( 'brokenlinks', 'bl_to' );
		$this->convertTable( 'categorylinks', 'cl_to' );
		$this->convertTable( 'imagelinks', 'il_to' );
		$this->convertTable( 'watchlist', 'wl_title' );
		
		# FIXME: We'll also need to change the files.
		$this->convertTable( 'image', 'img_name',
			array( 'img_name', 'img_description', 'img_user_text' ) );
		$this->convertTable( 'oldimage', 'archive_name',
			array( 'oi_name', 'oi_archive_name', 'oi_description', 'oi_user_text' ) );
		
		# Don't change the ar_text entries; use $wgLegacyEncoding to read them at runtime
		$this->convertTable( 'archive', null,
			array( 'ar_title', 'ar_comment', 'ar_user_text' ) );
		echo "Not converting text table: be sure to set \$wgLegacyEncoding!\n";
		
		$this->db->query( 'UNLOCK TABLES' );
	}
	
}

?>
