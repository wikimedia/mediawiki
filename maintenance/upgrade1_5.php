<?php

// Alternate 1.4 -> 1.5 schema upgrade
// This does only the main tables + UTF-8
// and is designed to allow upgrades to interleave
// with other updates on the replication stream so
// that large wikis can be upgraded without disrupting
// other services.
//
// Note: this script DOES NOT apply every update, nor
// will it probably handle much older versions, etc.
// Run this, FOLLOWED BY update.php, for upgrading
// from 1.4.5 release to 1.5.

$options = array( 'step' );

require_once( 'commandLine.inc' );
require_once( 'cleanupDupes.inc' );
require_once( 'userDupes.inc' );
require_once( 'updaters.inc' );

$upgrade = new FiveUpgrade();
$step = isset( $options['step'] ) ? $options['step'] : null;
$upgrade->upgrade( $step );

class FiveUpgrade {
	function FiveUpgrade() {
		global $wgDatabase;
		$this->conversionTables = $this->prepareWindows1252();
		$this->dbw =& $this->newConnection();
		$this->dbr =& $this->newConnection();
		$this->dbr->bufferResults( false );
		$this->cleanupSwaps = array();
	}
	
	function doing( $step ) {
		return is_null( $this->step ) || $step == $this->step;
	}
	
	function upgrade( $step ) {
		$this->step = $step;
		if( $this->doing( 'page' ) )
			$this->upgradePage();
		if( $this->doing( 'links' ) )
			$this->upgradeLinks();
		if( $this->doing( 'user' ) )
			$this->upgradeUser();
		if( $this->doing( 'image' ) )
			$this->upgradeImage();
		if( $this->doing( 'oldimage' ) )
			$this->upgradeOldImage();
		if( $this->doing( 'watchlist' ) )
			$this->upgradeWatchlist();
		if( $this->doing( 'logging' ) )
			$this->upgradeLogging();
		if( $this->doing( 'archive' ) )
			$this->upgradeArchive();
		
		if( $this->doing( 'cleanup' ) )
			$this->upgradeCleanup();
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
		return is_null( $text )
			? null
			: ( $wgUseLatin1
				? strtr( $text, $this->conversionTables )
				: $text );
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
	 * @param string $table to insert on
	 * @param string $fname function name to report in SQL
	 * @access private
	 */
	function setChunkScale( $chunksize, $final, $table, $fname ) {
		$this->chunkSize  = $chunksize;
		$this->chunkFinal = $final;
		$this->chunkCount = 0;
		$this->chunkStartTime = wfTime();
		$this->chunkOptions = array();
		$this->chunkTable = $table;
		$this->chunkFunction = $fname;
	}
	
	/**
	 * Chunked inserts: perform an insert if we've reached the chunk limit.
	 * Prints a progress report with estimated completion time.
	 * @param array &$chunk -- This will be emptied if an insert is done.
	 * @param int $key A key identifier to use in progress estimation in
	 *                 place of the number of rows inserted. Use this if
	 *                 you provided a max key number instead of a count
	 *                 as the final chunk number in setChunkScale()
	 * @access private
	 */
	function addChunk( &$chunk, $key = null ) {
		if( count( $chunk ) >= $this->chunkSize ) {
			$this->insertChunk( $chunk );
		
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
				$this->chunkTable,
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
	 * @param array &$chunk -- This will be emptied if an insert is done.
	 * @access private
	 */
	function lastChunk( &$chunk ) {
		$n = count( $chunk );
		if( $n > 0 ) {
			$this->insertChunk( $chunk );
		}
		$this->log( "100.00% done on $this->chunkTable (last chunk $n rows)." );
	}
	
	/**
	 * Chunked inserts: perform an insert.
	 * @param array &$chunk -- This will be emptied if an insert is done.
	 * @access private
	 */
	function insertChunk( &$chunk ) {
		$this->dbw->insert( $this->chunkTable, $chunk, $this->chunkFunction, $this->chunkOptions );
	}
	
	
	function upgradePage() {
		$fname = "FiveUpgrade::upgradePage";
		$chunksize = 100;
		

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
		$this->setChunkScale( $chunksize, $maxcur, 'old', $fname );
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
			$this->addChunk( $add, $row->cur_id );
		}
		$this->lastChunk( $add );
		$this->dbr->freeResult( $result );
		
		/**
		 * Copy revision metadata from old into revision.
		 * We'll also do UTF-8 conversion of usernames and comments.
		 */
		#$newmaxold = $this->dbw->selectField( 'old', 'max(old_id)', '', $fname );
		#$this->setChunkScale( $chunksize, $newmaxold, 'revision', $fname );
		$countold = $this->dbw->selectField( 'old', 'count(old_id)', '', $fname );
		$this->setChunkScale( $chunksize, $countold, 'revision', $fname );
		
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
			$this->addChunk( $add );
		}
		$this->lastChunk( $add );
		$this->dbr->freeResult( $result );
		

		/**
		 * Copy page metadata from cur into page.
		 * We'll also do UTF-8 conversion of titles.
		 */
		$this->log( "......Setting up page table." );
		$this->setChunkScale( $chunksize, $maxcur, 'page', $fname );
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
			$this->addChunk( $add, $row->cur_id );
		}
		$this->lastChunk( $add );
		$this->dbr->freeResult( $result );
		
		$this->log( "...done with cur/old -> page/revision." );
	}
	
	function upgradeLinks() {
		$fname = 'FiveUpgrade::upgradeLinks';
		$chunksize = 200;
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
			$this->setChunkScale( $chunksize, $nlinks, 'pagelinks', $fname );
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
				$this->addChunk( $add );
			}
			$this->lastChunk( $add );
		} else {
			$this->log( 'no links!' );
		}
		
		$this->log( 'Importing brokenlinks -> pagelinks' );
		$nbrokenlinks = $this->dbw->selectField( 'brokenlinks', 'count(*)', '', $fname );
		if( $nbrokenlinks ) {
			$this->setChunkScale( $chunksize, $nbrokenlinks, 'pagelinks', $fname );
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
					$this->addChunk( $add );
				}
			}
			$this->lastChunk( $add );
		} else {
			$this->log( 'no brokenlinks!' );
		}
		
		$this->log( 'Done with links.' );
	}
	
	function upgradeUser() {
		$fname = 'FiveUpgrade::upgradeUser';
		$chunksize = 100;
		$preauth = 0;
		
		// Apply unique index, if necessary:
		$duper = new UserDupes( $this->dbw );
		if( $duper->hasUniqueIndex() ) {
			$this->log( "Already have unique user_name index." );
		} else {
			$this->log( "Clearing user duplicates..." );
			if( !$duper->clearDupes() ) {
				$this->log( "WARNING: Duplicate user accounts, may explode!" );
			}
		}
		
		/** Convert encoding on options, etc */
		extract( $this->dbw->tableNames( 'user', 'user_temp', 'user_old' ) );
		
		$this->log( 'Migrating user table to user_temp...' );
		$this->dbw->query( "CREATE TABLE $user_temp (
  user_id int(5) unsigned NOT NULL auto_increment,
  user_name varchar(255) binary NOT NULL default '',
  user_real_name varchar(255) binary NOT NULL default '',
  user_password tinyblob NOT NULL default '',
  user_newpassword tinyblob NOT NULL default '',
  user_email tinytext NOT NULL default '',
  user_options blob NOT NULL default '',
  user_touched char(14) binary NOT NULL default '',
  user_token char(32) binary NOT NULL default '',
  user_email_authenticated CHAR(14) BINARY,
  user_email_token CHAR(32) BINARY,
  user_email_token_expires CHAR(14) BINARY,

  PRIMARY KEY user_id (user_id),
  UNIQUE INDEX user_name (user_name),
  INDEX (user_email_token)

) TYPE=InnoDB", $fname );

		// Fix encoding for Latin-1 upgrades, and add some fields.
		$numusers = $this->dbw->selectField( 'user', 'count(*)', '', $fname );
		$this->setChunkScale( $chunksize, $numusers, 'user_temp', $fname );
		$result = $this->dbr->select( 'user',
			array(
				'user_id',
				'user_name',
				'user_real_name',
				'user_password',
				'user_newpassword',
				'user_email',
				'user_options',
				'user_touched',
				'user_token' ),
			'',
			$fname );
		$add = array();
		while( $row = $this->dbr->fetchObject( $result ) ) {
			$now = $this->dbw->timestamp();
			$add[] = array(
				'user_id'                  =>              $row->user_id,
				'user_name'                => $this->conv( $row->user_name ),
				'user_real_name'           => $this->conv( $row->user_real_name ),
				'user_password'            =>              $row->user_password,
				'user_newpassword'         =>              $row->user_newpassword,
				'user_email'               => $this->conv( $row->user_email ),
				'user_options'             => $this->conv( $row->user_options ),
				'user_touched'             =>              $now,
				'user_token'               =>              $row->user_token,
				'user_email_authenticated' =>              $preauth ? $now : null,
				'user_email_token'         =>              null,
				'user_email_token_expires' =>              null );
			$this->addChunk( $add );
		}
		$this->lastChunk( $add );
		$this->dbr->freeResult( $result );
		$this->cleanupSwaps[] = 'user';
	}
	
	function upgradeImage() {
		$fname = 'FiveUpgrade::upgradeImage';
		$chunksize = 100;
		
		extract( $this->dbw->tableNames( 'image', 'image_temp', 'image_old' ) );
		$this->log( 'Creating temporary image_temp to merge into...' );
		$this->dbw->query( <<<END
CREATE TABLE $image_temp (
  img_name varchar(255) binary NOT NULL default '',
  img_size int(8) unsigned NOT NULL default '0',
  img_width int(5)  NOT NULL default '0',
  img_height int(5)  NOT NULL default '0',
  img_metadata mediumblob NOT NULL,
  img_bits int(3)  NOT NULL default '0',
  img_media_type ENUM("UNKNOWN", "BITMAP", "DRAWING", "AUDIO", "VIDEO", "MULTIMEDIA", "OFFICE", "TEXT", "EXECUTABLE", "ARCHIVE") default NULL,
  img_major_mime ENUM("unknown", "application", "audio", "image", "text", "video", "message", "model", "multipart") NOT NULL default "unknown",
  img_minor_mime varchar(32) NOT NULL default "unknown",
  img_description tinyblob NOT NULL default '',
  img_user int(5) unsigned NOT NULL default '0',
  img_user_text varchar(255) binary NOT NULL default '',
  img_timestamp char(14) binary NOT NULL default '',
  
  PRIMARY KEY img_name (img_name),
  INDEX img_size (img_size),
  INDEX img_timestamp (img_timestamp)
) TYPE=InnoDB
END
		, $fname);
		
		$numimages = $this->dbw->selectField( 'image', 'count(*)', '', $fname );
		$result = $this->dbr->select( 'image',
			array(
				'img_name',
				'img_size',
				'img_description',
				'img_user',
				'img_user_text',
				'img_timestamp' ),
			'',
			$fname );
		$add = array();
		$this->setChunkScale( $chunksize, $numimages, 'image_temp', $fname );
		while( $row = $this->dbr->fetchObject( $result ) ) {
			// Fill in the new image info fields
			$info = $this->imageInfo( $row->img_name );
			
			// Update and convert encoding
			$add[] = array(
				'img_name'        => $this->conv( $row->img_name ),
				'img_size'        =>              $row->img_size,
				'img_width'       =>              $info['width'],
				'img_height'      =>              $info['height'],
				'img_metadata'    =>              "", // loaded on-demand
				'img_bits'        =>              $info['bits'],
				'img_media_type'  =>              $info['media'],
				'img_major_mime'  =>              $info['major'],
				'img_minor_mime'  =>              $info['minor'],
				'img_description' => $this->conv( $row->img_description ),
				'img_user'        =>              $row->img_user,
				'img_user_text'   => $this->conv( $row->img_user_text ),
				'img_timestamp'   =>              $row->img_timestamp );
			
			// If doing UTF8 conversion the file must be renamed
			$this->renameFile( $row->img_name, 'wfImageDir' );
		}
		$this->lastChunk( $add );
		
		$this->log( 'done with image table.' );
		$this->cleanupSwaps[] = 'image';
	}
	
	function imageInfo( $name, $subdirCallback='wfImageDir', $basename = null ) {
		if( is_null( $basename ) ) $basename = $name;
		$dir = call_user_func( $subdirCallback, $basename );
		$filename = $dir . '/' . $name;
		$info = array(
			'width'  => 0,
			'height' => 0,
			'bits'   => 0,
			'media'  => '',
			'major'  => '',
			'minor'  => '' );
		
		$magic =& wfGetMimeMagic();
		$mime = $magic->guessMimeType( $filename, true );
		list( $info['major'], $info['minor'] ) = explode( '/', $mime );
		
		$info['media'] = $magic->getMediaType( $filename, $mime );
		
		# Height and width
		$gis = false;
		if( $mime == 'image/svg' ) {
			$gis = wfGetSVGsize( $this->imagePath );
		} elseif( $magic->isPHPImageType( $mime ) ) {
			$gis = getimagesize( $filename );
		} else {
			$this->log( "Surprising mime type: $mime" );
		}
		if( $gis ) {
			$info['width' ] = $gis[0];
			$info['height'] = $gis[1];
		}
		if( isset( $gis['bits'] ) ) {
			$info['bits'] = $gis['bits'];
		}
		
		return $info;
	}
	

	/**
	 * Truncate a table.
	 * @param string $table The table name to be truncated
	 */
	function clearTable( $table ) {
		print "Clearing $table...\n";
		$tableName = $this->db->tableName( $table );
		$this->db->query( 'TRUNCATE $tableName' );
	}
	
	/**
	 * Rename a given image or archived image file to the converted filename,
	 * leaving a symlink for URL compatibility.
	 *
	 * @param string $oldname pre-conversion filename
	 * @param string $basename pre-conversion base filename for dir hashing, if an archive
	 * @access private
	 */
	function renameFile( $oldname, $subdirCallback='wfImageDir', $basename=null ) {
		$newname = $this->conv( $oldname );
		if( $newname == $oldname ) {
			// No need to rename; another field triggered this row.
			return;
		}
		
		if( is_null( $basename ) ) $basename = $oldname;
		$ubasename = $this->conv( $basename );
		$oldpath = call_user_func( $subdirCallback, $basename ) . '/' . $oldname;
		$newpath = call_user_func( $subdirCallback, $ubasename ) . '/' . $newname;
		
		$this->log( "$oldpath -> $newpath" );
		if( rename( $oldpath, $newpath ) ) {
			$relpath = $this->relativize( $newpath, dirname( $oldpath ) );
			if( !symlink( $relpath, $oldpath ) ) {
				$this->log( "... symlink failed!" );
			}
		} else {
			$this->log( "... rename failed!" );
		}
	}
	
	/**
	 * Generate a relative path name to the given file.
	 * Assumes Unix-style paths, separators, and semantics.
	 *
	 * @param string $path Absolute destination path including target filename
	 * @param string $from Absolute source path, directory only
	 * @return string
	 * @access private
	 * @static
	 */
	function relativize( $path, $from ) {
		$pieces  = explode( '/', dirname( $path ) );
		$against = explode( '/', $from );
		
		// Trim off common prefix
		while( count( $pieces ) && count( $against )
			&& $pieces[0] == $against[0] ) {
			array_shift( $pieces );
			array_shift( $against );
		}
		
		// relative dots to bump us to the parent
		while( count( $against ) ) {
			array_unshift( $pieces, '..' );
			array_shift( $against );
		}
		
		array_push( $pieces, basename( $path ) );
		
		return implode( '/', $pieces );
	}
	
	function upgradeOldImage() {
		$fname = 'FiveUpgrade::upgradeOldImage';
		$chunksize = 100;
		
		extract( $this->dbw->tableNames( 'oldimage', 'oldimage_temp', 'oldimage_old' ) );
		$this->log( 'Creating temporary oldimage_temp to merge into...' );
		$this->dbw->query( <<<END
CREATE TABLE $oldimage_temp (
  -- Base filename: key to image.img_name
  oi_name varchar(255) binary NOT NULL default '',
  
  -- Filename of the archived file.
  -- This is generally a timestamp and '!' prepended to the base name.
  oi_archive_name varchar(255) binary NOT NULL default '',
  
  -- Other fields as in image...
  oi_size int(8) unsigned NOT NULL default 0,
  oi_width int(5) NOT NULL default 0,
  oi_height int(5) NOT NULL default 0,
  oi_bits int(3) NOT NULL default 0,
  oi_description tinyblob NOT NULL default '',
  oi_user int(5) unsigned NOT NULL default '0',
  oi_user_text varchar(255) binary NOT NULL default '',
  oi_timestamp char(14) binary NOT NULL default '',

  INDEX oi_name (oi_name(10))

) TYPE=InnoDB;
END
		, $fname);
		
		$numimages = $this->dbw->selectField( 'oldimage', 'count(*)', '', $fname );
		$result = $this->dbr->select( 'oldimage',
			array(
				'oi_name',
				'oi_archive_name',
				'oi_size',
				'oi_description',
				'oi_user',
				'oi_user_text',
				'oi_timestamp' ),
			'',
			$fname );
		$add = array();
		$this->setChunkScale( $chunksize, $numimages, 'oldimage_temp', $fname );
		while( $row = $this->dbr->fetchObject( $result ) ) {
			// Fill in the new image info fields
			$info = $this->imageInfo( $row->oi_archive_name, 'wfImageArchiveDir', $row->oi_name );
			
			// Update and convert encoding
			$add[] = array(
				'oi_name'         => $this->conv( $row->oi_name ),
				'oi_archive_name' => $this->conv( $row->oi_archive_name ),
				'oi_size'         =>              $row->oi_size,
				'oi_width'        =>              $info['width'],
				'oi_height'       =>              $info['height'],
				'oi_bits'         =>              $info['bits'],
				'oi_description'  => $this->conv( $row->oi_description ),
				'oi_user'         =>              $row->oi_user,
				'oi_user_text'    => $this->conv( $row->oi_user_text ),
				'oi_timestamp'    =>              $row->oi_timestamp );
			
			// If doing UTF8 conversion the file must be renamed
			$this->renameFile( $row->oi_archive_name, 'wfImageArchiveDir', $row->oi_name );
		}
		$this->lastChunk( $add );
		
		$this->log( 'done with oldimage table.' );
		$this->cleanupSwaps[] = 'oldimage';
	}
	

	function upgradeWatchlist() {
		$fname = 'FiveUpgrade::upgradeWatchlist';
		$chunksize = 100;
		
		extract( $this->dbw->tableNames( 'watchlist', 'watchlist_temp' ) );
		
		$this->log( 'Migrating watchlist table to watchlist_temp...' );
		$this->dbw->query(
"CREATE TABLE $watchlist_temp (
  -- Key to user_id
  wl_user int(5) unsigned NOT NULL,
  
  -- Key to page_namespace/page_title
  -- Note that users may watch patches which do not exist yet,
  -- or existed in the past but have been deleted.
  wl_namespace int NOT NULL default '0',
  wl_title varchar(255) binary NOT NULL default '',
  
  -- Timestamp when user was last sent a notification e-mail;
  -- cleared when the user visits the page.
  -- FIXME: add proper null support etc
  wl_notificationtimestamp varchar(14) binary NOT NULL default '0',
  
  UNIQUE KEY (wl_user, wl_namespace, wl_title),
  KEY namespace_title (wl_namespace,wl_title)

) TYPE=InnoDB;", $fname );

		// Fix encoding for Latin-1 upgrades, add some fields,
		// and double article to article+talk pairs
		$numwatched = $this->dbw->selectField( 'watchlist', 'count(*)', '', $fname );
		
		$this->setChunkScale( $chunksize, $numwatched * 2, 'watchlist_temp', $fname );
		$result = $this->dbr->select( 'watchlist',
			array(
				'wl_user',
				'wl_namespace',
				'wl_title' ),
			'',
			$fname );
		
		$add = array();
		while( $row = $this->dbr->fetchObject( $result ) ) {
			$now = $this->dbw->timestamp();
			$add[] = array(
				'wl_user'      =>                        $row->wl_user,
				'wl_namespace' => Namespace::getSubject( $row->wl_namespace ),
				'wl_title'     =>           $this->conv( $row->wl_title ),
				'wl_notificationtimestamp' =>            '0' );
			$this->addChunk( $add );
			
			$add[] = array(
				'wl_user'      =>                        $row->wl_user,
				'wl_namespace' =>    Namespace::getTalk( $row->wl_namespace ),
				'wl_title'     =>           $this->conv( $row->wl_title ),
				'wl_notificationtimestamp' =>            '0' );
			$this->addChunk( $add );
		}
		$this->lastChunk( $add );
		$this->dbr->freeResult( $result );
		
		$this->log( 'Done converting watchlist.' );
		$this->cleanupSwaps[] = 'watchlist';
	}

	function upgradeLogging() {
		global $wgUseLatin1;
		if( !$wgUseLatin1 ) {
			$this->log( 'Not latin1; no change to logging table.' );
			return;
		}
		
		$fname = 'FiveUpgrade::upgradeLogging';
		$chunksize = 100;
		
		extract( $this->dbw->tableNames( 'logging', 'logging_temp' ) );
		
		$this->log( 'Migrating logging table to logging_temp...' );
		$this->dbw->query(
"CREATE TABLE $logging_temp (
  -- Symbolic keys for the general log type and the action type
  -- within the log. The output format will be controlled by the
  -- action field, but only the type controls categorization.
  log_type char(10) NOT NULL default '',
  log_action char(10) NOT NULL default '',
  
  -- Timestamp. Duh.
  log_timestamp char(14) NOT NULL default '19700101000000',
  
  -- The user who performed this action; key to user_id
  log_user int unsigned NOT NULL default 0,
  
  -- Key to the page affected. Where a user is the target,
  -- this will point to the user page.
  log_namespace int NOT NULL default 0,
  log_title varchar(255) binary NOT NULL default '',
  
  -- Freeform text. Interpreted as edit history comments.
  log_comment varchar(255) NOT NULL default '',
  
  -- LF separated list of miscellaneous parameters
  log_params blob NOT NULL default '',

  KEY type_time (log_type, log_timestamp),
  KEY user_time (log_user, log_timestamp),
  KEY page_time (log_namespace, log_title, log_timestamp)

) TYPE=InnoDB", $fname );

		$numlogged = $this->dbw->selectField( 'logging', 'count(*)', '', $fname );
		$this->setChunkScale( $chunksize, $numlogged, 'logging_temp', $fname );
		
		$result = $this->dbr->select( 'logging',
			array(
				'log_type',
				'log_action',
				'log_timestamp',
				'log_user',
				'log_namespace',
				'log_title',
				'log_comment',
				'log_params' ),
			'',
			$fname );
		
		$add = array();
		while( $row = $this->dbr->fetchObject( $result ) ) {
			$now = $this->dbw->timestamp();
			$add[] = array(
				'log_type'      =>              $row->log_type,
				'log_action'    =>              $row->log_type,
				'log_timestamp' =>              $row->log_timestamp,
				'log_user'      =>              $row->log_user,
				'log_namespace' =>              $row->log_namespace,
				'log_title'     => $this->conv( $row->log_title ),
				'log_comment'   => $this->conv( $row->log_comment ),
				'log_params'    => $this->conv( $row->log_params ) );
			$this->addChunk( $add );
		}
		$this->lastChunk( $add );
		$this->dbr->freeResult( $result );
		
		$this->log( 'Done converting logging.' );
		$this->cleanupSwaps[] = 'logging';
	}

	function upgradeArchive() {
		$fname = 'FiveUpgrade::upgradeArchive';
		$chunksize = 100;
		
		extract( $this->dbw->tableNames( 'archive', 'archive_temp' ) );
		
		$this->log( 'Migrating archive table to archive_temp...' );
		$this->dbw->query(
"CREATE TABLE $archive_temp (
  ar_namespace int NOT NULL default '0',
  ar_title varchar(255) binary NOT NULL default '',
  
  -- Newly deleted pages will not store text in this table,
  -- but will reference the separately existing text rows.
  -- This field is retained for backwards compatibility,
  -- so old archived pages will remain accessible after
  -- upgrading from 1.4 to 1.5.
  ar_text mediumblob NOT NULL default '',
  
  -- Basic revision stuff...
  ar_comment tinyblob NOT NULL default '',
  ar_user int(5) unsigned NOT NULL default '0',
  ar_user_text varchar(255) binary NOT NULL,
  ar_timestamp char(14) binary NOT NULL default '',
  ar_minor_edit tinyint(1) NOT NULL default '0',
  
  -- See ar_text note.
  ar_flags tinyblob NOT NULL default '',
  
  -- When revisions are deleted, their unique rev_id is stored
  -- here so it can be retained after undeletion. This is necessary
  -- to retain permalinks to given revisions after accidental delete
  -- cycles or messy operations like history merges.
  -- 
  -- Old entries from 1.4 will be NULL here, and a new rev_id will
  -- be created on undeletion for those revisions.
  ar_rev_id int(8) unsigned,
  
  -- For newly deleted revisions, this is the text.old_id key to the
  -- actual stored text. To avoid breaking the block-compression scheme
  -- and otherwise making storage changes harder, the actual text is
  -- *not* deleted from the text table, merely hidden by removal of the
  -- page and revision entries.
  --
  -- Old entries deleted under 1.2-1.4 will have NULL here, and their
  -- ar_text and ar_flags fields will be used to create a new text
  -- row upon undeletion.
  ar_text_id int(8) unsigned,
  
  KEY name_title_timestamp (ar_namespace,ar_title,ar_timestamp)

) TYPE=InnoDB", $fname );

		$numarchived = $this->dbw->selectField( 'archive', 'count(*)', '', $fname );
		$this->setChunkScale( $chunksize, $numarchived, 'archive_temp', $fname );
		
		$result = $this->dbr->select( 'archive',
			array(
				'ar_namespace',
				'ar_title',
				'ar_text',
				'ar_comment',
				'ar_user',
				'ar_user_text',
				'ar_timestamp',
				'ar_minor_edit',
				'ar_flags' ),
			'',
			$fname );
		
		$add = array();
		while( $row = $this->dbr->fetchObject( $result ) ) {
			$now = $this->dbw->timestamp();
			$add[] = array(
				'ar_namespace'  =>              $row->ar_namespace,
				'ar_title'      => $this->conv( $row->ar_title ),
				'ar_text'       =>              $row->ar_text,
				'ar_comment'    => $this->conv( $row->ar_comment ),
				'ar_user'       =>              $row->ar_user,
				'ar_user_text'  => $this->conv( $row->ar_user_text ),
				'ar_timestamp'  =>              $row->ar_user_text,
				'ar_minor_edit' =>              $row->ar_minor_edit,
				'ar_flags'      =>              $row->ar_flags,
				'ar_rev_id'     => null,
				'ar_text_id'    => null );
			$this->addChunk( $add );
		}
		$this->lastChunk( $add );
		$this->dbr->freeResult( $result );
		
		$this->log( 'Done converting archive.' );
		$this->cleanupSwaps[] = 'archive';
	}

	/**
	 * Rename all our temporary tables into final place.
	 * We've left things in place so a read-only wiki can continue running
	 * on the old code during all this.
	 */
	function upgradeCleanup() {
		$this->renameTable( 'old', 'text' );
		
		foreach( $this->cleanupSwaps as $table ) {
			$this->swap( $table );
		}
	}
	
	function renameTable( $from, $to ) {
		$this->log( 'Renaming $from to $to...' );

		$fromtable = $this->dbw->tableName( $from );
		$totable   = $this->dbw->tableName( $to );
		$this->dbw->query( "ALTER TABLE $fromtable RENAME TO $totable" );
	}
	
	function swap( $base ) {
		$this->renameTable( $base, "{$base}_old" );
		$this->renameTable( "{$base}_temp", $base );
	}
	
}

?>