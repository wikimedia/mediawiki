<?php

// Alternate 1.4 -> 1.5 schema upgrade
// This does only the main tables + UTF-8
// and is designed to allow upgrades to interleave
// with other updates on the replication stream so
// that large wikis can be upgraded without disrupting
// other services.

require_once( 'commandLine.inc' );
require_once( 'cleanupDupes.inc' );

$upgrade = new FiveUpgrade();
$upgrade->upgrade();

class FiveUpgrade {
	function FiveUpgrade() {
		global $wgDatabase;
		$this->conversionTables = $this->prepareWindows1252();
		$this->dbw =& $this->newConnection();
		$this->dbr =& $this->newConnection();
		$this->dbr->bufferResults( false );
	}
	
	function upgrade() {
		$this->upgradePage();
		$this->upgradeLinks();
	}
	
	
	/**
	 * Open a second connection to the master server, with buffering off.
	 * This will let us stream large datasets in and write in chunks on the
	 * other end.
	 * @return Database
	 * @access private
	 */
	function &newConnection() {
		global $wgDBadminuser, $wgDBadminpassword;
		global $wgDBserver, $wgDBname;
		$db =& new Database( $wgDBserver, $wgDBadminuser, $wgDBadminpassword, $wgDBname );
		return $db;
	}
	
	/**
	 * Prepare a conversion array for converting Windows Code Page 1252 to
	 * UTF-8. This should provide proper conversion of text that was miscoded
	 * as Windows-1252 by naughty user-agents, and doesn't rely on an outside
	 * iconv library.
	 *
	 * @return array
	 * @access private
	 */
	function prepareWindows1252() {
		# Mappings from:
		# http://www.unicode.org/Public/MAPPINGS/VENDORS/MICSFT/WINDOWS/CP1252.TXT
		static $cp1252 = array(
			0x80 => 0x20AC,	#EURO SIGN
			0x81 => UNICODE_REPLACEMENT,
			0x82 => 0x201A,	#SINGLE LOW-9 QUOTATION MARK
			0x83 => 0x0192,	#LATIN SMALL LETTER F WITH HOOK
			0x84 => 0x201E,	#DOUBLE LOW-9 QUOTATION MARK
			0x85 => 0x2026,	#HORIZONTAL ELLIPSIS
			0x86 => 0x2020,	#DAGGER
			0x87 => 0x2021,	#DOUBLE DAGGER
			0x88 => 0x02C6,	#MODIFIER LETTER CIRCUMFLEX ACCENT
			0x89 => 0x2030,	#PER MILLE SIGN
			0x8A => 0x0160,	#LATIN CAPITAL LETTER S WITH CARON
			0x8B => 0x2039,	#SINGLE LEFT-POINTING ANGLE QUOTATION MARK
			0x8C => 0x0152,	#LATIN CAPITAL LIGATURE OE
			0x8D => UNICODE_REPLACEMENT,
			0x8E => 0x017D,	#LATIN CAPITAL LETTER Z WITH CARON
			0x8F => UNICODE_REPLACEMENT,
			0x90 => UNICODE_REPLACEMENT,
			0x91 => 0x2018,	#LEFT SINGLE QUOTATION MARK
			0x92 => 0x2019,	#RIGHT SINGLE QUOTATION MARK
			0x93 => 0x201C,	#LEFT DOUBLE QUOTATION MARK
			0x94 => 0x201D,	#RIGHT DOUBLE QUOTATION MARK
			0x95 => 0x2022,	#BULLET
			0x96 => 0x2013,	#EN DASH
			0x97 => 0x2014,	#EM DASH
			0x98 => 0x02DC,	#SMALL TILDE
			0x99 => 0x2122,	#TRADE MARK SIGN
			0x9A => 0x0161,	#LATIN SMALL LETTER S WITH CARON
			0x9B => 0x203A,	#SINGLE RIGHT-POINTING ANGLE QUOTATION MARK
			0x9C => 0x0153,	#LATIN SMALL LIGATURE OE
			0x9D => UNICODE_REPLACEMENT,
			0x9E => 0x017E,	#LATIN SMALL LETTER Z WITH CARON
			0x9F => 0x0178,	#LATIN CAPITAL LETTER Y WITH DIAERESIS
			);
		$pairs = array();
		for( $i = 0; $i < 0x100; $i++ ) {
			$unicode = isset( $cp1252[$i] ) ? $cp1252[$i] : $i;
			$pairs[chr( $i )] = codepointToUtf8( $unicode );
		}
		return $pairs;
	}
	
	/**
	 * Convert from 8-bit Windows-1252 to UTF-8 if necessary.
	 * @param string $text
	 * @return string
	 * @access private
	 */
	function conv( $text ) {
		global $wgUseLatin1;
		if( $wgUseLatin1 ) {
			return strtr( $text, $this->conversionTables );
		} else {
			return $text;
		}
	}
	
	/**
	 * Dump timestamp and message to output
	 * @param string $message
	 * @access private
	 */
	function log( $message ) {
		echo wfTimestamp( TS_DB ) . ': ' . $message . "\n";
		flush();
	}
	
	/**
	 * Initialize the chunked-insert system.
	 * Rows will be inserted in chunks of the given number, rather
	 * than in a giant INSERT...SELECT query, to keep the serialized
	 * MySQL database replication from getting hung up. This way other
	 * things can be going on during conversion without waiting for
	 * slaves to catch up as badly.
	 *
	 * @param int $chunksize Number of rows to insert at once
	 * @param int $final Total expected number of rows / id of last row,
	 *                   used for progress reports.
	 * @access private
	 */
	function setChunkScale( $chunksize, $final ) {
		$this->chunkSize  = $chunksize;
		$this->chunkFinal = $final;
		$this->chunkCount = 0;
		$this->chunkStartTime = wfTime();
		$this->chunkOptions = array();
	}
	
	/**
	 * Chunked inserts: perform an insert if we've reached the chunk limit.
	 * Prints a progress report with estimated completion time.
	 * @param string $table
	 * @param array &$chunk -- This will be emptied if an insert is done.
	 * @param string $fname function name to report in SQL
	 * @param int $key A key identifier to use in progress estimation in
	 *                 place of the number of rows inserted. Use this if
	 *                 you provided a max key number instead of a count
	 *                 as the final chunk number in setChunkScale()
	 * @access private
	 */
	function addChunk( $table, &$chunk, $fname, $key = null ) {
		if( count( $chunk ) >= $this->chunkSize ) {
			$this->insertChunk( $table, $chunk, $fname );
		
			$this->chunkCount += count( $chunk );
			$now = wfTime();
			$delta = $now - $this->chunkStartTime;
			$rate = $this->chunkCount / $delta;
			
			if( is_null( $key ) ) {
				$completed = $this->chunkCount;
			} else {
				$completed = $key;
			}
			$portion = $completed / $this->chunkFinal;
			
			$estimatedTotalTime = $delta / $portion;
			$eta = $this->chunkStartTime + $estimatedTotalTime;
			
			printf( "%s: %6.2f%% done on %s; ETA %s [%d/%d] %.2f/sec\n",
				wfTimestamp( TS_DB, intval( $now ) ),
				$portion * 100.0,
				$table,
				wfTimestamp( TS_DB, intval( $eta ) ),
				$completed,
				$this->chunkFinal,
				$rate );
			flush();
			
			$chunk = array();
		}
	}
	
	/**
	 * Chunked inserts: perform an insert unconditionally, at the end, and log.
	 * @param string $table
	 * @param array &$chunk -- This will be emptied if an insert is done.
	 * @param string $fname function name to report in SQL
	 * @access private
	 */
	function lastChunk( $table, &$chunk, $fname ) {
		$n = count( $chunk );
		if( $n > 0 ) {
			$this->insertChunk( $table, $chunk, $fname );
		}
		$this->log( "100.00% done on $table (last chunk $n rows)." );
	}
	
	/**
	 * Chunked inserts: perform an insert.
	 * @param string $table
	 * @param array &$chunk -- This will be emptied if an insert is done.
	 * @param string $fname function name to report in SQL
	 * @access private
	 */
	function insertChunk( $table, &$chunk, $fname ) {
		$this->dbw->insert( $table, $chunk, $fname, $this->chunkOptions );
	}
	
	function upgradePage() {
		$fname = "FiveUpgrade::upgradePage";
		$chunksize = 500;
		

		$this->log( "Checking cur table for unique title index and applying if necessary" );
		checkDupes( true );

		$this->log( "...converting from cur/old to page/revision/text DB structure." );
		
		extract( $this->dbw->tableNames( 'cur', 'old', 'page', 'revision', 'text' ) );

		$this->log( "Creating page and revision tables..." );
		$this->dbw->query("CREATE TABLE $page (
  			page_id int(8) unsigned NOT NULL auto_increment,
  			page_namespace int NOT NULL,
  			page_title varchar(255) binary NOT NULL,
  			page_restrictions tinyblob NOT NULL default '',
  			page_counter bigint(20) unsigned NOT NULL default '0',
  			page_is_redirect tinyint(1) unsigned NOT NULL default '0',
  			page_is_new tinyint(1) unsigned NOT NULL default '0',
  			page_random real unsigned NOT NULL,
  			page_touched char(14) binary NOT NULL default '',
  			page_latest int(8) unsigned NOT NULL,
  			page_len int(8) unsigned NOT NULL,

  			PRIMARY KEY page_id (page_id),
  			UNIQUE INDEX name_title (page_namespace,page_title),
  			INDEX (page_random),
  			INDEX (page_len)
			) TYPE=InnoDB", $fname );
		$this->dbw->query("CREATE TABLE $revision (
  			rev_id int(8) unsigned NOT NULL auto_increment,
  			rev_page int(8) unsigned NOT NULL,
  			rev_comment tinyblob NOT NULL default '',
  			rev_user int(5) unsigned NOT NULL default '0',
  			rev_user_text varchar(255) binary NOT NULL default '',
  			rev_timestamp char(14) binary NOT NULL default '',
  			rev_minor_edit tinyint(1) unsigned NOT NULL default '0',
			rev_deleted tinyint(1) unsigned NOT NULL default '0',
  
  			PRIMARY KEY rev_page_id (rev_page, rev_id),
  			UNIQUE INDEX rev_id (rev_id),
  			INDEX rev_timestamp (rev_timestamp),
  			INDEX page_timestamp (rev_page,rev_timestamp),
  			INDEX user_timestamp (rev_user,rev_timestamp),
  			INDEX usertext_timestamp (rev_user_text,rev_timestamp)
			) TYPE=InnoDB", $fname );

		$maxold = $this->dbw->selectField( 'old', 'max(old_id)', '', $fname );
		$this->log( "Last old record is {$maxold}" );

		global $wgLegacySchemaConversion;
		if( $wgLegacySchemaConversion ) {
			// Create HistoryBlobCurStub entries.
			// Text will be pulled from the leftover 'cur' table at runtime.
			echo "......Moving metadata from cur; using blob references to text in cur table.\n";
			$cur_text = "concat('O:18:\"historyblobcurstub\":1:{s:6:\"mCurId\";i:',cur_id,';}')";
			$cur_flags = "'object'";
		} else {
			// Copy all cur text in immediately: this may take longer but avoids
			// having to keep an extra table around.
			echo "......Moving text from cur.\n";
			$cur_text = 'cur_text';
			$cur_flags = "''";
		}

		$maxcur = $this->dbw->selectField( 'cur', 'max(cur_id)', '', $fname );
		$this->log( "Last cur entry is $maxcur" );
		
		/**
		 * Copy placeholder records for each page's current version into old
		 * Don't do any conversion here; text records are converted at runtime
		 * based on the flags (and may be originally binary!) while the meta
		 * fields will be converted in the old -> rev and cur -> page steps.
		 */
		$this->setChunkScale( $chunksize, $maxcur );
		$result = $this->dbr->query(
			"SELECT cur_id, cur_namespace, cur_title, $cur_text AS text, cur_comment,
			cur_user, cur_user_text, cur_timestamp, cur_minor_edit, $cur_flags AS flags
  			FROM $cur
  			ORDER BY cur_id", $fname );
  		$add = array();
		while( $row = $this->dbr->fetchObject( $result ) ) {
			$add[] = array(
				'old_namespace'  => $row->cur_namespace,
				'old_title'      => $row->cur_title,
				'old_text'       => $row->text,
				'old_comment'    => $row->cur_comment,
				'old_user'       => $row->cur_user,
				'old_user_text'  => $row->cur_user_text,
				'old_timestamp'  => $row->cur_timestamp,
				'old_minor_edit' => $row->cur_minor_edit,
				'old_flags'      => $row->flags );
			$this->addChunk( 'old', $add, $fname, $row->cur_id );
		}
		$this->lastChunk( 'old', $add, $fname );
		$this->dbr->freeResult( $result );
		
		/**
		 * Copy revision metadata from old into revision.
		 * We'll also do UTF-8 conversion of usernames and comments.
		 */
		#$newmaxold = $this->dbw->selectField( 'old', 'max(old_id)', '', $fname );
		#$this->setChunkScale( $chunksize, $newmaxold );
		$countold = $this->dbw->selectField( 'old', 'count(old_id)', '', $fname );
		$this->setChunkScale( $chunksize, $countold );
		
		$this->log( "......Setting up revision table." );
		$result = $this->dbr->query(
			"SELECT old_id, cur_id, old_comment, old_user, old_user_text,
			old_timestamp, old_minor_edit
			FROM $old,$cur WHERE old_namespace=cur_namespace AND old_title=cur_title",
			$fname );

		$add = array();
		while( $row = $this->dbr->fetchObject( $result ) ) {
			$add[] = array(
				'rev_id'         =>              $row->old_id,
				'rev_page'       =>              $row->cur_id,
				'rev_comment'    => $this->conv( $row->old_comment ),
				'rev_user'       =>              $row->old_user,
				'rev_user_text'  => $this->conv( $row->old_user_text ),
				'rev_timestamp'  =>              $row->old_timestamp,
				'rev_minor_edit' =>              $row->old_minor_edit );
			$this->addChunk( 'revision', $add, $fname );
		}
		$this->lastChunk( 'revision', $add, $fname );
		$this->dbr->freeResult( $result );
		

		/**
		 * Copy revision metadata from cur into page.
		 * We'll also do UTF-8 conversion of titles.
		 */
		$this->log( "......Setting up page table." );
		$this->setChunkScale( $chunksize, $maxcur );
		$result = $this->dbr->query( "
			SELECT cur_id, cur_namespace, cur_title, cur_restrictions, cur_counter, cur_is_redirect, cur_is_new,
    				cur_random, cur_touched, rev_id, LENGTH(cur_text) AS len
  			FROM $cur,$revision
  			WHERE cur_id=rev_page AND rev_timestamp=cur_timestamp AND rev_id > {$maxold}
  			ORDER BY cur_id", $fname );
		$add = array();
		while( $row = $this->dbr->fetchObject( $result ) ) {
			$add[] = array(
				'page_id'           =>              $row->cur_id,
				'page_namespace'    =>              $row->cur_namespace,
				'page_title'        => $this->conv( $row->cur_title ),
				'page_restrictions' =>              $row->cur_restrictions,
				'page_counter'      =>              $row->cur_counter,
				'page_is_redirect'  =>              $row->cur_is_redirect,
				'page_is_new'       =>              $row->cur_is_new,
				'page_random'       =>              $row->cur_random,
				'page_touched'      =>              $this->dbw->timestamp(),
				'page_latest'       =>              $row->rev_id,
				'page_len'          =>              $row->len );
			$this->addChunk( 'page', $add, $fname, $row->cur_id );
		}
		$this->lastChunk( 'page', $add, $fname );
		$this->dbr->freeResult( $result );

		$this->log( "......Renaming old." );
		$this->dbw->query( "ALTER TABLE $old RENAME TO $text", $fname );
		
		$this->log( "...done." );
	}
	
	function upgradeLinks() {
		$fname = 'FiveUpgrade::upgradeLinks';
		$chunksize = 1000;
		extract( $this->dbw->tableNames( 'links', 'brokenlinks', 'pagelinks', 'page' ) );
		
		$this->log( 'Creating pagelinks table...' );
		$this->dbw->query( "
CREATE TABLE $pagelinks (
  -- Key to the page_id of the page containing the link.
  pl_from int(8) unsigned NOT NULL default '0',
  
  -- Key to page_namespace/page_title of the target page.
  -- The target page may or may not exist, and due to renames
  -- and deletions may refer to different page records as time
  -- goes by.
  pl_namespace int NOT NULL default '0',
  pl_title varchar(255) binary NOT NULL default '',
  
  UNIQUE KEY pl_from(pl_from,pl_namespace,pl_title),
  KEY (pl_namespace,pl_title)

) TYPE=InnoDB" );

		$this->log( 'Importing live links -> pagelinks' );
		$nlinks = $this->dbw->selectField( 'links', 'count(*)', '', $fname );
		if( $nlinks ) {
			$this->setChunkScale( $chunksize, $nlinks );
			$result = $this->dbr->query( "
			  SELECT l_from,page_namespace,page_title
				FROM $links, $page
				WHERE l_to=page_id", $fname );
			$add = array();
			while( $row = $this->dbr->fetchObject( $result ) ) {
				$add[] = array(
					'pl_from'      => $row->l_from,
					'pl_namespace' => $row->page_namespace,
					'pl_title'     => $row->page_title );
				$this->addChunk( 'pagelinks', $add, $fname );
			}
			$this->lastChunk( 'pagelinks', $add, $fname );
		} else {
			$this->log( 'no links!' );
		}
		
		$this->log( 'Importing brokenlinks -> pagelinks' );
		$nbrokenlinks = $this->dbw->selectField( 'brokenlinks', 'count(*)', '', $fname );
		if( $nbrokenlinks ) {
			$this->setChunkScale( $chunksize, $nbrokenlinks );
			$this->chunkOptions = array( 'IGNORE' );
			$result = $this->dbr->query(
				"SELECT bl_from, bl_to FROM $brokenlinks",
				$fname );
			$add = array();
			while( $row = $this->dbr->fetchObject( $result ) ) {
				$pagename = $this->conv( $row->bl_to );
				$title = Title::newFromText( $pagename );
				if( is_null( $title ) ) {
					$this->log( "** invalid brokenlink: $row->bl_from -> '$pagename' (converted from '$row->bl_to')" );
				} else {
					$add[] = array(
						'pl_from'      => $row->bl_from,
						'pl_namespace' => $title->getNamespace(),
						'pl_title'     => $title->getDBkey() );
					$this->addChunk( 'pagelinks', $add, $fname );
				}
			}
			$this->lastChunk( 'pagelinks', $add, $fname );
		} else {
			$this->log( 'no brokenlinks!' );
		}
		
		$this->log( 'Done with links.' );
	}
}

?>