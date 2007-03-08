<?php
/**
 * @addtogroup Maintenance
 */

 /** */

if ( !defined( 'MEDIAWIKI' ) ) {
	echo "This file is not a valid entry point\n";
	exit( 1 );
}

require_once 'convertLinks.inc';
require_once 'userDupes.inc';
require_once 'deleteDefaultMessages.php';

$wgRenamedTables = array(
#           from             to                  patch file
#	array( 'group',         'groups',           'patch-rename-group.sql' ),
);

$wgNewTables = array(
#            table          patch file (in maintenance/archives)
	array( 'hitcounter',    'patch-hitcounter.sql' ),
	array( 'querycache',    'patch-querycache.sql' ),
	array( 'objectcache',   'patch-objectcache.sql' ),
	array( 'categorylinks', 'patch-categorylinks.sql' ),
	array( 'logging',       'patch-logging.sql' ),
	array( 'user_newtalk',  'patch-usernewtalk2.sql' ),
	array( 'transcache',	'patch-transcache.sql' ),
	array( 'trackbacks',	'patch-trackbacks.sql' ),
	array( 'externallinks', 'patch-externallinks.sql' ),
	array( 'job',           'patch-job.sql' ),
	array( 'langlinks',     'patch-langlinks.sql' ),
	array( 'querycache_info', 'patch-querycacheinfo.sql' ),
	array( 'filearchive',   'patch-filearchive.sql' ),
	array( 'querycachetwo',	'patch-querycachetwo.sql' ),
);

$wgNewFields = array(
#           table            field             patch file (in maintenance/archives)
	array( 'ipblocks',      'ipb_id',           'patch-ipblocks.sql' ),
	array( 'ipblocks',      'ipb_expiry',       'patch-ipb_expiry.sql' ),
	array( 'recentchanges', 'rc_type',          'patch-rc_type.sql' ),
	array( 'recentchanges', 'rc_ip',            'patch-rc_ip.sql' ),
	array( 'recentchanges', 'rc_id',            'patch-rc_id.sql' ),
	array( 'recentchanges', 'rc_patrolled',     'patch-rc-patrol.sql' ),
	array( 'recentchanges', 'rc_old_len',       'patch-rc_len.sql' ),
	array( 'user',          'user_real_name',   'patch-user-realname.sql' ),
	array( 'user',          'user_token',       'patch-user_token.sql' ),
	array( 'user',          'user_email_token', 'patch-user_email_token.sql' ),
	array( 'user',          'user_registration','patch-user_registration.sql' ),
 	array( 'logging',       'log_params',       'patch-log_params.sql' ),
 	array( 'archive',       'ar_rev_id',        'patch-archive-rev_id.sql' ),
 	array( 'archive',       'ar_text_id',       'patch-archive-text_id.sql' ),
 	array( 'page',          'page_len',         'patch-page_len.sql' ),
 	array( 'revision',      'rev_deleted',      'patch-rev_deleted.sql' ),
	array( 'image',         'img_width',        'patch-img_width.sql' ),
	array( 'image',         'img_metadata',     'patch-img_metadata.sql' ),
	array( 'image',         'img_media_type',   'patch-img_media_type.sql' ),
	array( 'site_stats',    'ss_total_pages',   'patch-ss_total_articles.sql' ),
	array( 'interwiki',     'iw_trans',         'patch-interwiki-trans.sql' ),
	array( 'ipblocks',      'ipb_range_start',  'patch-ipb_range_start.sql' ),
	array( 'site_stats',    'ss_images',        'patch-ss_images.sql' ),
	array( 'ipblocks',      'ipb_anon_only',    'patch-ipb_anon_only.sql' ),
	array( 'ipblocks',	'ipb_enable_autoblock', 'patch-ipb_optional_autoblock.sql' ),
	array( 'user',          'user_newpass_time','patch-user_newpass_time.sql' ),
	array( 'user',          'user_editcount',   'patch-user_editcount.sql' ),
	array( 'logging',	'log_id',		'patch-log_id.sql' ),
	array( 'page_restrictions', 'pr_id',		'patch-page_restrictions_sortkey.sql' ),
);

function rename_table( $from, $to, $patch ) {
	global $wgDatabase;
	if ( $wgDatabase->tableExists( $from ) ) {
		if ( $wgDatabase->tableExists( $to ) ) {
			echo "...can't move table $from to $to, $to already exists.\n";
		} else {
			echo "Moving table $from to $to...";
			dbsource( archive($patch), $wgDatabase );
			echo "ok\n";
		}
	} else {
		// Source table does not exist
		// Renames are done before creations, so this is typical for a new installation
		// Ignore silently
	}
}

function add_table( $name, $patch ) {
	global $wgDatabase;
	if ( $wgDatabase->tableExists( $name ) ) {
		echo "...$name table already exists.\n";
	} else {
		echo "Creating $name table...";
		dbsource( archive($patch), $wgDatabase );
		echo "ok\n";
	}
}

function add_field( $table, $field, $patch ) {
	global $wgDatabase;
	if ( !$wgDatabase->tableExists( $table ) ) {
		echo "...$table table does not exist, skipping new field patch\n";
	} elseif ( $wgDatabase->fieldExists( $table, $field ) ) {
		echo "...have $field field in $table table.\n";
	} else {
		echo "Adding $field field to table $table...";
		dbsource( archive($patch) , $wgDatabase );
		echo "ok\n";
	}
}

function do_revision_updates() {
	global $wgSoftwareRevision;
	if ( $wgSoftwareRevision < 1001 ) {
		update_passwords();
	}
}

function update_passwords() {
	wfDebugDieBacktrace( "This function needs to be updated or removed.\n" );

	global $wgDatabase;
	$fname = "Update script: update_passwords()";
	print "\nIt appears that you need to update the user passwords in your\n" .
	  "database. If you have already done this (if you've run this update\n" .
	  "script once before, for example), doing so again will make all your\n" .
	  "user accounts inaccessible, so be sure you only do this once.\n" .
	  "Update user passwords? (yes/no)";

	$resp = readconsole();
    if ( ! ( "Y" == $resp{0} || "y" == $resp{0} ) ) { return; }

	$sql = "SELECT user_id,user_password FROM user";
	$source = $wgDatabase->query( $sql, $fname );

	while ( $row = $wgDatabase->fetchObject( $source ) ) {
		$id = $row->user_id;
		$oldpass = $row->user_password;
		$newpass = md5( "{$id}-{$oldpass}" );

		$sql = "UPDATE user SET user_password='{$newpass}' " .
		  "WHERE user_id={$id}";
		$wgDatabase->query( $sql, $fname );
	}
}

function do_interwiki_update() {
	# Check that interwiki table exists; if it doesn't source it
	global $wgDatabase, $IP;
	if( $wgDatabase->tableExists( "interwiki" ) ) {
		echo "...already have interwiki table\n";
		return true;
	}
	echo "Creating interwiki table: ";
	dbsource( archive("patch-interwiki.sql") );
	echo "ok\n";
	echo "Adding default interwiki definitions: ";
	dbsource( "$IP/maintenance/interwiki.sql" );
	echo "ok\n";
}

function do_index_update() {
	# Check that proper indexes are in place
	global $wgDatabase;
	$meta = $wgDatabase->fieldInfo( "recentchanges", "rc_timestamp" );
	if( $meta->multiple_key == 0 ) {
		echo "Updating indexes to 20031107: ";
		dbsource( archive("patch-indexes.sql") );
		echo "ok\n";
		return true;
	}
	echo "...indexes seem up to 20031107 standards\n";
	return false;
}

function do_image_index_update() {
	global $wgDatabase;

	$meta = $wgDatabase->fieldInfo( "image", "img_major_mime" );
	if( $meta->multiple_key == 0 ) {
		echo "Updating indexes to 20050912: ";
		dbsource( archive("patch-mimesearch-indexes.sql") );
		echo "ok\n";
		return true;
	}
	echo "...indexes seem up to 20050912 standards\n";
	return false;
}

function do_image_name_unique_update() {
	global $wgDatabase;
	if( $wgDatabase->indexExists( 'image', 'PRIMARY' ) ) {
		echo "...image primary key already set.\n";
	} else {
		echo "Making img_name the primary key... ";
		dbsource( archive("patch-image_name_primary.sql"), $wgDatabase );
		echo "ok\n";
	}
}

function do_logging_timestamp_index() {
	global $wgDatabase;
	if( $wgDatabase->indexExists( 'logging', 'times' ) ) {
		echo "...timestamp key on logging already exists.\n";
	} else {
		echo "Adding timestamp key on logging table... ";
		dbsource( archive("patch-logging-times-index.sql"), $wgDatabase );
		echo "ok\n";
	}
}


function do_watchlist_update() {
	global $wgDatabase;
	$fname = 'do_watchlist_update';
	if( $wgDatabase->fieldExists( 'watchlist', 'wl_notificationtimestamp' ) ) {
		echo "The watchlist table is already set up for email notification.\n";
	} else {
		echo "Adding wl_notificationtimestamp field for email notification management.";
		/* ALTER TABLE watchlist ADD (wl_notificationtimestamp varchar(14) binary NOT NULL default '0'); */
		dbsource( archive( 'patch-email-notification.sql' ), $wgDatabase );
		echo "ok\n";
	}
	# Check if we need to add talk page rows to the watchlist
	$talk = $wgDatabase->selectField( 'watchlist', 'count(*)', 'wl_namespace & 1', $fname );
	$nontalk = $wgDatabase->selectField( 'watchlist', 'count(*)', 'NOT (wl_namespace & 1)', $fname );
	if ( $talk != $nontalk ) {
		echo "Adding missing watchlist talk page rows... ";
		flush();

		$wgDatabase->insertSelect( 'watchlist', 'watchlist', 
			array(
				'wl_user' => 'wl_user',
				'wl_namespace' => 'wl_namespace | 1',
				'wl_title' => 'wl_title',
				'wl_notificationtimestamp' => 'wl_notificationtimestamp'
			), array( 'NOT (wl_namespace & 1)' ), $fname, 'IGNORE' );
		echo "ok\n";
	} else {
		echo "...watchlist talk page rows already present\n";
	}
}

function do_copy_newtalk_to_watchlist() {
	global $wgDatabase;
	global $wgCommandLineMode;	# this needs to be saved while getID() and getName() are called

	$res = $wgDatabase->safeQuery( 'SELECT user_id, user_ip FROM !',
		$wgDatabase->tableName( 'user_newtalk' ) );
	$num_newtalks=$wgDatabase->numRows($res);
	echo "Now converting ".$num_newtalks." user_newtalk entries to watchlist table entries ... \n";

	$user = new User();
	for ( $i = 1; $i <= $num_newtalks; $i++ ) {
		$wluser = $wgDatabase->fetchObject( $res );
		if ($wluser->user_id == 0) { # anonymous users ... have IP numbers as "names"
			if ($user->isIP($wluser->user_ip)) { # do only if it really looks like an IP number (double checked)
				$wgDatabase->replace( 'watchlist',
					array(array('wl_user','wl_namespace', 'wl_title', 'wl_notificationtimestamp' )),
					  array('wl_user' 			=> 0,
						'wl_namespace' 			=> NS_USER_TALK,
						'wl_title' 			=> $wluser->user_ip,
						'wl_notificationtimestamp' 	=> '19700101000000'
						), 'updaters.inc::do_watchlist_update2'
					);
			}
		} else { # normal users ... have user_ids
			$user->setID($wluser->user_id);
			$wgDatabase->replace( 'watchlist',
				array(array('wl_user','wl_namespace', 'wl_title', 'wl_notificationtimestamp' )),
				  array('wl_user' 			=> $user->getID(),
					'wl_namespace' 			=> NS_USER_TALK,
					'wl_title' 			=> $user->getName(),
					'wl_notificationtimestamp' 	=> '19700101000000'
					), 'updaters.inc::do_watchlist_update3'
				);
		}
	}
	echo "Done.\n";
}


function do_user_update() {
	global $wgDatabase;
	if( $wgDatabase->fieldExists( 'user', 'user_emailauthenticationtimestamp' ) ) {
		echo "User table contains old email authentication field. Dropping... ";
		dbsource( archive( 'patch-email-authentication.sql' ), $wgDatabase );
		echo "ok\n";
	} else {
		echo "...user table does not contain old email authentication field.\n";
	}
}

/**
 * 1.4 betas were missing the 'binary' marker from logging.log_title,
 * which causes a collation mismatch error on joins in MySQL 4.1.
 */
function do_logging_encoding() {
	global $wgDatabase, $wgDBtype;
	if ($wgDBtype != 'mysql')
		return;
	$logging = $wgDatabase->tableName( 'logging' );
	$res = $wgDatabase->query( "SELECT log_title FROM $logging LIMIT 0" );
	$flags = explode( ' ', mysql_field_flags( $res, 0 ) );
	$wgDatabase->freeResult( $res );

	if( in_array( 'binary', $flags ) ) {
		echo "Logging table has correct title encoding.\n";
	} else {
		echo "Fixing title encoding on logging table... ";
		dbsource( archive( 'patch-logging-title.sql' ), $wgDatabase );
		echo "ok\n";
	}
}

function do_schema_restructuring() {
	global $wgDatabase;
	$fname="do_schema_restructuring";
	if ( $wgDatabase->tableExists( 'page' ) ) {
		echo "...page table already exists.\n";
	} else {
		echo "...converting from cur/old to page/revision/text DB structure.\n"; flush();
		echo wfTimestamp( TS_DB );
		echo "......checking for duplicate entries.\n"; flush();

		list ($cur, $old, $page, $revision, $text) = $wgDatabase->tableNamesN( 'cur', 'old', 'page', 'revision', 'text' );

		$rows = $wgDatabase->query( "SELECT cur_title, cur_namespace, COUNT(cur_namespace) AS c
				FROM $cur GROUP BY cur_title, cur_namespace HAVING c>1", $fname );

		if ( $wgDatabase->numRows( $rows ) > 0 ) {
			echo wfTimestamp( TS_DB );
			echo "......<b>Found duplicate entries</b>\n";
			echo ( sprintf( "<b>      %-60s %3s %5s</b>\n", 'Title', 'NS', 'Count' ) );
			while ( $row = $wgDatabase->fetchObject( $rows ) ) {
				if ( ! isset( $duplicate[$row->cur_namespace] ) ) {
					$duplicate[$row->cur_namespace] = array();
				}
				$duplicate[$row->cur_namespace][] = $row->cur_title;
				echo ( sprintf( "      %-60s %3s %5s\n", $row->cur_title, $row->cur_namespace, $row->c ) );
			}
			$sql = "SELECT cur_title, cur_namespace, cur_id, cur_timestamp FROM $cur WHERE ";
			$firstCond = true;
			foreach ( $duplicate as $ns => $titles ) {
				if ( $firstCond ) {
					$firstCond = false;
				} else {
					$sql .= ' OR ';
				}
				$sql .= "( cur_namespace = {$ns} AND cur_title in (";
				$first = true;
				foreach ( $titles as $t ) {
					if ( $first ) {
						$sql .= $wgDatabase->addQuotes( $t );
						$first = false;
					} else {
						$sql .= ', ' . $wgDatabase->addQuotes( $t );
					}
				}
				$sql .= ") ) \n";
			}
			# By sorting descending, the most recent entry will be the first in the list.
			# All following entries will be deleted by the next while-loop.
			$sql .= 'ORDER BY cur_namespace, cur_title, cur_timestamp DESC';

			$rows = $wgDatabase->query( $sql, $fname );

			$prev_title = $prev_namespace = false;
			$deleteId = array();

			while ( $row = $wgDatabase->fetchObject( $rows ) ) {
				if ( $prev_title == $row->cur_title && $prev_namespace == $row->cur_namespace ) {
					$deleteId[] = $row->cur_id;
				}
				$prev_title     = $row->cur_title;
				$prev_namespace = $row->cur_namespace;
			}
			$sql = "DELETE FROM $cur WHERE cur_id IN ( " . join( ',', $deleteId ) . ')';
			$rows = $wgDatabase->query( $sql, $fname );
			echo wfTimestamp( TS_DB );
			echo "......<b>Deleted</b> ".$wgDatabase->affectedRows()." records.\n";
		}


		echo wfTimestamp( TS_DB );
		echo "......Creating tables.\n";
		$wgDatabase->query("CREATE TABLE $page (
  			page_id int(8) unsigned NOT NULL auto_increment,
  			page_namespace int NOT NULL,
  			page_title varchar(255) binary NOT NULL,
  			page_restrictions tinyblob NOT NULL,
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
		$wgDatabase->query("CREATE TABLE $revision (
  			rev_id int(8) unsigned NOT NULL auto_increment,
  			rev_page int(8) unsigned NOT NULL,
  			rev_comment tinyblob NOT NULL,
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

		echo wfTimestamp( TS_DB );
		echo "......Locking tables.\n";
		$wgDatabase->query( "LOCK TABLES $page WRITE, $revision WRITE, $old WRITE, $cur WRITE", $fname );

		$maxold = intval( $wgDatabase->selectField( 'old', 'max(old_id)', '', $fname ) );
		echo wfTimestamp( TS_DB );
		echo "......maxold is {$maxold}\n";

		echo wfTimestamp( TS_DB );
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
		$wgDatabase->query( "INSERT INTO $old (old_namespace, old_title, old_text, old_comment, old_user, old_user_text,
    				old_timestamp, old_minor_edit, old_flags)
  			SELECT cur_namespace, cur_title, $cur_text, cur_comment, cur_user, cur_user_text, cur_timestamp, cur_minor_edit, $cur_flags
  			FROM $cur", $fname );

		echo wfTimestamp( TS_DB );
		echo "......Setting up revision table.\n";
		$wgDatabase->query( "INSERT INTO $revision (rev_id, rev_page, rev_comment, rev_user, rev_user_text, rev_timestamp,
    				rev_minor_edit)
			SELECT old_id, cur_id, old_comment, old_user, old_user_text,
    				old_timestamp, old_minor_edit
			FROM $old,$cur WHERE old_namespace=cur_namespace AND old_title=cur_title", $fname );

		echo wfTimestamp( TS_DB );
		echo "......Setting up page table.\n";
		$wgDatabase->query( "INSERT INTO $page (page_id, page_namespace, page_title, page_restrictions, page_counter,
    				page_is_redirect, page_is_new, page_random, page_touched, page_latest, page_len)
  			SELECT cur_id, cur_namespace, cur_title, cur_restrictions, cur_counter, cur_is_redirect, cur_is_new,
    				cur_random, cur_touched, rev_id, LENGTH(cur_text)
  			FROM $cur,$revision
  			WHERE cur_id=rev_page AND rev_timestamp=cur_timestamp AND rev_id > {$maxold}", $fname );

		echo wfTimestamp( TS_DB );
		echo "......Unlocking tables.\n";
		$wgDatabase->query( "UNLOCK TABLES", $fname );

		echo wfTimestamp( TS_DB );
		echo "......Renaming old.\n";
		$wgDatabase->query( "ALTER TABLE $old RENAME TO $text", $fname );

		echo wfTimestamp( TS_DB );
		echo "...done.\n";
	}
}

function do_inverse_timestamp() {
	global $wgDatabase;
	if( $wgDatabase->fieldExists( 'revision', 'inverse_timestamp' ) ) {
		echo "Removing revision.inverse_timestamp and fixing indexes... ";
		dbsource( archive( 'patch-inverse_timestamp.sql' ), $wgDatabase );
		echo "ok\n";
	} else {
		echo "revision timestamp indexes already up to 2005-03-13\n";
	}
}

function do_text_id() {
	global $wgDatabase;
	if( $wgDatabase->fieldExists( 'revision', 'rev_text_id' ) ) {
		echo "...rev_text_id already in place.\n";
	} else {
		echo "Adding rev_text_id field... ";
		dbsource( archive( 'patch-rev_text_id.sql' ), $wgDatabase );
		echo "ok\n";
	}
}

function do_namespace_size() {
	$tables = array(
		'page'          => 'page',
		'archive'       => 'ar',
		'recentchanges' => 'rc',
		'watchlist'     => 'wl',
		'querycache'    => 'qc',
		'logging'       => 'log',
	);
	foreach( $tables as $table => $prefix ) {
		do_namespace_size_on( $table, $prefix );
		flush();
	}
}

function do_namespace_size_on( $table, $prefix ) {
	global $wgDatabase, $wgDBtype;
	if ($wgDBtype != 'mysql')
		return;
	$field = $prefix . '_namespace';

	$tablename = $wgDatabase->tableName( $table );
	$result = $wgDatabase->query( "SHOW COLUMNS FROM $tablename LIKE '$field'" );
	$info = $wgDatabase->fetchObject( $result );
	$wgDatabase->freeResult( $result );

	if( substr( $info->Type, 0, 3 ) == 'int' ) {
		echo "...$field is already a full int ($info->Type).\n";
	} else {
		echo "Promoting $field from $info->Type to int... ";

		$sql = "ALTER TABLE $tablename MODIFY $field int NOT NULL";
		$wgDatabase->query( $sql );

		echo "ok\n";
	}
}

function do_pagelinks_update() {
	global $wgDatabase;
	if( $wgDatabase->tableExists( 'pagelinks' ) ) {
		echo "...already have pagelinks table.\n";
	} else {
		echo "Converting links and brokenlinks tables to pagelinks... ";
		dbsource( archive( 'patch-pagelinks.sql' ), $wgDatabase );
		echo "ok\n";
		flush();

		global $wgCanonicalNamespaceNames;
		foreach( $wgCanonicalNamespaceNames as $ns => $name ) {
			if( $ns != 0 ) {
				do_pagelinks_namespace( $ns );
			}
		}
	}
}

function do_pagelinks_namespace( $namespace ) {
	global $wgDatabase, $wgContLang;

	$ns = intval( $namespace );
	echo "Cleaning up broken links for namespace $ns... ";

	$pagelinks = $wgDatabase->tableName( 'pagelinks' );
	$name = $wgContLang->getNsText( $ns );
	$prefix = $wgDatabase->strencode( $name );
	$likeprefix = str_replace( '_', '\\_', $prefix);

	$sql = "UPDATE $pagelinks
	           SET pl_namespace=$ns,
	               pl_title=TRIM(LEADING '$prefix:' FROM pl_title)
	         WHERE pl_namespace=0
	           AND pl_title LIKE '$likeprefix:%'";

	$wgDatabase->query( $sql, 'do_pagelinks_namespace' );
	echo "ok\n";
}

function do_drop_img_type() {
	global $wgDatabase;

	if( $wgDatabase->fieldExists( 'image', 'img_type' ) ) {
		echo "Dropping unused img_type field in image table... ";
		dbsource( archive( 'patch-drop_img_type.sql' ), $wgDatabase );
		echo "ok\n";
	} else {
		echo "No img_type field in image table; Good.\n";
	}
}

function do_old_links_update() {
	global $wgDatabase;
	if( $wgDatabase->tableExists( 'pagelinks' ) ) {
		echo "Already have pagelinks; skipping old links table updates.\n";
	} else {
		convertLinks(); flush();
	}
}

function do_user_unique_update() {
	global $wgDatabase;
	$duper = new UserDupes( $wgDatabase );
	if( $duper->hasUniqueIndex() ) {
		echo "Already have unique user_name index.\n";
	} else {
		if( !$duper->clearDupes() ) {
			echo "WARNING: This next step will probably fail due to unfixed duplicates...\n";
		}
		echo "Adding unique index on user_name... ";
		dbsource( archive( 'patch-user_nameindex.sql' ), $wgDatabase );
		echo "ok\n";
	}
}

function do_user_groups_update() {
	$fname = 'do_user_groups_update';
	global $wgDatabase;

	if( $wgDatabase->tableExists( 'user_groups' ) ) {
		echo "...user_groups table already exists.\n";
		return do_user_groups_reformat();
	}

	echo "Adding user_groups table... ";
	dbsource( archive( 'patch-user_groups.sql' ), $wgDatabase );
	echo "ok\n";

	if( !$wgDatabase->tableExists( 'user_rights' ) ) {
		if( $wgDatabase->fieldExists( 'user', 'user_rights' ) ) {
			echo "Upgrading from a 1.3 or older database? Breaking out user_rights for conversion...";
			dbsource( archive( 'patch-user_rights.sql' ), $wgDatabase );
			echo "ok\n";
		} else {
			echo "*** WARNING: couldn't locate user_rights table or field for upgrade.\n";
			echo "*** You may need to manually configure some sysops by manipulating\n";
			echo "*** the user_groups table.\n";
			return;
		}
	}

	echo "Converting user_rights table to user_groups... ";
	$result = $wgDatabase->select( 'user_rights',
		array( 'ur_user', 'ur_rights' ),
		array( "ur_rights != ''" ),
		$fname );

	while( $row = $wgDatabase->fetchObject( $result ) ) {
		$groups = array_unique(
			array_map( 'trim',
				explode( ',', $row->ur_rights ) ) );

		foreach( $groups as $group ) {
			$wgDatabase->insert( 'user_groups',
				array(
					'ug_user'  => $row->ur_user,
					'ug_group' => $group ),
				$fname );
		}
	}
	$wgDatabase->freeResult( $result );
	echo "ok\n";
}

function do_user_groups_reformat() {
	# Check for bogus formats from previous 1.5 alpha code.
	global $wgDatabase;
	$info = $wgDatabase->fieldInfo( 'user_groups', 'ug_group' );

	if( $info->type == 'int' ) {
		$oldug = $wgDatabase->tableName( 'user_groups' );
		$newug = $wgDatabase->tableName( 'user_groups_bogus' );
		echo "user_groups is in bogus intermediate format. Renaming to $newug... ";
		$wgDatabase->query( "ALTER TABLE $oldug RENAME TO $newug" );
		echo "ok\n";

		echo "Re-adding fresh user_groups table... ";
		dbsource( archive( 'patch-user_groups.sql' ), $wgDatabase );
		echo "ok\n";

		echo "***\n";
		echo "*** WARNING: You will need to manually fix up user permissions in the user_groups\n";
		echo "*** table. Old 1.5 alpha versions did some pretty funky stuff...\n";
		echo "***\n";
	} else {
		echo "...user_groups is in current format.\n";
	}

}

function do_watchlist_null() {
	# Make sure wl_notificationtimestamp can be NULL,
	# and update old broken items.
	global $wgDatabase;
	$info = $wgDatabase->fieldInfo( 'watchlist', 'wl_notificationtimestamp' );

	if( $info->not_null ) {
		echo "Making wl_notificationtimestamp nullable... ";
		dbsource( archive( 'patch-watchlist-null.sql' ), $wgDatabase );
		echo "ok\n";
	} else {
		echo "...wl_notificationtimestamp is already nullable.\n";
	}

}

/**
 * @bug 3946
 */
function do_page_random_update() {
	global $wgDatabase;

	echo "Setting page_random to a random value on rows where it equals 0...";

	$page = $wgDatabase->tableName( 'page' );
	$wgDatabase->query( "UPDATE $page SET page_random = RAND() WHERE page_random = 0", 'do_page_random_update' );
	$rows = $wgDatabase->affectedRows();

	echo "changed $rows rows\n";
}

function do_templatelinks_update() {
	global $wgDatabase, $wgLoadBalancer;
	$fname = 'do_templatelinks_update';

	if ( $wgDatabase->tableExists( 'templatelinks' ) ) {
		echo "...templatelinks table already exists\n";
		return;
	}
	echo "Creating templatelinks table...\n";
	dbsource( archive('patch-templatelinks.sql'), $wgDatabase );
	echo "Populating...\n";
	if ( isset( $wgLoadBalancer ) && $wgLoadBalancer->getServerCount() > 1 ) {
		// Slow, replication-friendly update
		$res = $wgDatabase->select( 'pagelinks', array( 'pl_from', 'pl_namespace', 'pl_title' ),
			array( 'pl_namespace' => NS_TEMPLATE ), $fname );
		$count = 0;
		while ( $row = $wgDatabase->fetchObject( $res ) ) {
			$count = ($count + 1) % 100;
			if ( $count == 0 ) {
				if ( function_exists( 'wfWaitForSlaves' ) ) {
					wfWaitForSlaves( 10 );
				} else {
					sleep( 1 );
				}
			}
			$wgDatabase->insert( 'templatelinks',
				array(
					'tl_from' => $row->pl_from,
					'tl_namespace' => $row->pl_namespace,
					'tl_title' => $row->pl_title,
				), $fname
			);

		}
		$wgDatabase->freeResult( $res );
	} else {
		// Fast update
		$wgDatabase->insertSelect( 'templatelinks', 'pagelinks',
			array(
				'tl_from' => 'pl_from',
				'tl_namespace' => 'pl_namespace',
				'tl_title' => 'pl_title'
			), array(
				'pl_namespace' => 10
			), $fname
		);
	}
	echo "Done. Please run maintenance/refreshLinks.php for a more thorough templatelinks update.\n";
}

# July 2006
# Add ( rc_namespace, rc_user_text ) index [R. Church]
function do_rc_indices_update() {
	global $wgDatabase;
	echo( "Checking for additional recent changes indices...\n" );
	# See if we can find the index we want
	$info = $wgDatabase->indexInfo( 'recentchanges', 'rc_ns_usertext', __METHOD__ );
	if( !$info ) {
		# None, so create
		echo( "...index on ( rc_namespace, rc_user_text ) not found; creating\n" );
		dbsource( archive( 'patch-recentchanges-utindex.sql' ) );
	} else {
		# Index seems to exist
		echo( "...index on ( rc_namespace, rc_user_text ) seems to be ok\n" );
	}

	#Add (rc_user_text, rc_timestamp) index [A. Garrett], November 2006
	# See if we can find the index we want
	$info = $wgDatabase->indexInfo( 'recentchanges', 'rc_user_text', __METHOD__ );
	if( !$info ) {
		# None, so create
		echo( "...index on ( rc_user_text, rc_timestamp ) not found; creating\n" );
		dbsource( archive( 'patch-rc_user_text-index.sql' ) );
	} else {
		# Index seems to exist
		echo( "...index on ( rc_user_text, rc_timestamp ) seems to be ok\n" );
	}
}

function index_has_field($table, $index, $field) {
	global $wgDatabase;
	echo( "Checking if $table index $index includes field $field...\n" );
	$info = $wgDatabase->indexInfo( $table, $index, __METHOD__ );
	if( $info ) {
		foreach($info as $row) {
			if($row->Column_name == $field) {
				echo( "...index $index on table $table seems to be ok\n" );
				return true;
			}
		}
	}
	echo( "...index $index on table $table has no field $field; adding\n" );
	return false;
}

function do_backlinking_indices_update() {
	echo( "Checking for backlinking indices...\n" );
	if (!index_has_field('pagelinks', 'pl_namespace', 'pl_from') ||
		!index_has_field('templatelinks', 'tl_namespace', 'tl_from') ||
		!index_has_field('imagelinks', 'il_to', 'il_from'))
	{	
		dbsource( archive( 'patch-backlinkindexes.sql' ) );
	}
}

function do_stats_init() {
	// Sometimes site_stats table is not properly populated.
	global $wgDatabase;
	echo "Checking site_stats row...";
	$row = $wgDatabase->selectRow( 'site_stats', '*', array( 'ss_row_id' => 1 ), __METHOD__ );
	if( $row === false ) {
		echo "data is missing! rebuilding...\n";
		
		global $IP;
		require_once "$IP/maintenance/initStats.inc";
		wfInitStats();
	} else {
		echo "ok.\n";
	}
}

function purge_cache() {
	global $wgDatabase;
	# We can't guarantee that the user will be able to use TRUNCATE,
	# but we know that DELETE is available to us
	echo( "Purging caches..." );
	$wgDatabase->delete( 'objectcache', '*', __METHOD__ );
	echo( "done.\n" );
}

function do_all_updates( $shared = false, $purge = true ) {
	global $wgNewTables, $wgNewFields, $wgRenamedTables, $wgSharedDB, $wgDatabase, $wgDBtype, $IP;

	$doUser = !$wgSharedDB || $shared;

	if ($wgDBtype === 'postgres') {
		do_postgres_updates();
		return;
	}

	# Rename tables
	foreach ( $wgRenamedTables as $tableRecord ) {
		rename_table( $tableRecord[0], $tableRecord[1], $tableRecord[2] );
	}

	# Add missing tables
	foreach ( $wgNewTables as $tableRecord ) {
		add_table( $tableRecord[0], $tableRecord[1] );
		flush();
	}

	# Add missing fields
	foreach ( $wgNewFields as $fieldRecord ) {
		if ( $fieldRecord[0] != 'user' || $doUser ) {
			add_field( $fieldRecord[0], $fieldRecord[1], $fieldRecord[2] );
		}
		flush();
	}

	# Do schema updates which require special handling
	do_interwiki_update(); flush();
	do_index_update(); flush();
	do_old_links_update(); flush();
	do_image_name_unique_update(); flush();
	do_watchlist_update(); flush();
	if ( $doUser ) {
		do_user_update(); flush();
	}
######	do_copy_newtalk_to_watchlist(); flush();
	do_logging_encoding(); flush();

	do_schema_restructuring(); flush();
	do_inverse_timestamp(); flush();
	do_text_id(); flush();
	do_namespace_size(); flush();

	do_pagelinks_update(); flush();
	do_templatelinks_update(); flush(); // after pagelinks

	do_drop_img_type(); flush();

	if ( $doUser ) {
		do_user_unique_update(); flush();
	}
	do_user_groups_update(); flush();

	do_watchlist_null(); flush();

	//do_image_index_update(); flush();

	do_logging_timestamp_index(); flush();

	do_page_random_update(); flush();
	
	do_rc_indices_update(); flush();

	add_table( 'redirect', 'patch-redirect.sql' );

	do_backlinking_indices_update(); flush();

	do_restrictions_update(); flush ();

	echo "Deleting old default messages (this may take a long time!)..."; flush();
	deleteDefaultMessages();
	echo "Done\n"; flush();
	
	do_stats_init(); flush();
	
	if( $purge ) {
		purge_cache();
		flush();
	}
}

function archive($name) {
	global $wgDBtype, $IP;
	switch ($wgDBtype) {
	case "postgres":
		return "$IP/maintenance/postgres/archives/$name";
	default:
		return "$IP/maintenance/archives/$name";
	}
}

function do_restrictions_update() {
	# Adding page_restrictions table, obsoleting page.page_restrictions.
	#  Migrating old restrictions to new table
	# -- Andrew Garrett, January 2007.

	global $wgDatabase;

	$name = 'page_restrictions';
	$patch = 'patch-page_restrictions.sql';

	if ( $wgDatabase->tableExists( $name ) ) {
		echo "...$name table already exists.\n";
	} else {
		echo "Creating $name table...";
		dbsource( archive($patch), $wgDatabase );
		echo "ok\n";

		echo "Migrating old restrictions to new table...";

		$res = $wgDatabase->select( 'page', array( 'page_id', 'page_restrictions' ), array("page_restrictions!=''", "page_restrictions!='edit=:move='"), __METHOD__ );

		$count = 0;

		while ($row = $wgDatabase->fetchObject($res) ) {
			$count = ($count + 1) % 100;

			if ($count == 0) {
				if ( function_exists( 'wfWaitForSlaves' ) ) {
					wfWaitForSlaves( 10 );
				} else {
					sleep( 1 );
				}
			}

			# Figure out what the restrictions are..
			$id = $row->page_id;
			$flatrestrictions = explode( ':', $row->page_restrictions );

			$restrictions = array ();
			foreach( $flatrestrictions as $restriction ) {
				$thisrestriction = explode( '=', $restriction, 2 );
				if( count( $thisrestriction ) == 1 ) {
					// Compatibility with old protections from before
					// separate move protection was added.
					list( $level ) = $thisrestriction;
					if( $level ) {
						$restrictions['edit'] = $level;
						$restrictions['move'] = $level;
					}
				} else {
					list( $type, $level ) = $thisrestriction;
					if( $level ) {
						$restrictions[$type] = $level;
					}
				}

			$wgDatabase->update( 'page', array ( 'page_restrictions' => ''), array( 'page_id' => $id ), __METHOD__ );

			}
			
			foreach( $restrictions as $type => $level ) {
				$wgDatabase->insert( 'page_restrictions', array ( 'pr_page' => $id,
											'pr_type' => $type,
											'pr_level' => $level,
											'pr_cascade' => 0 ),
											__METHOD__ );
			}
		}
		print "ok\n";
	}
	
}

function
pg_column_has_type($table, $column, $wanttype)
{
global	$wgDatabase, $wgDBname, $wgDBmwschema;

	$q = <<<END
SELECT typname FROM pg_class, pg_namespace, pg_attribute, pg_type
	WHERE relnamespace=pg_namespace.oid AND relkind='r'
	      AND attrelid=pg_class.oid AND atttypid=pg_type.oid
	      AND nspname=%s AND relname=%s AND attname=%s;
END;
	$res = $wgDatabase->query(sprintf($q,
			$wgDatabase->addQuotes($wgDBmwschema),
			$wgDatabase->addQuotes($table),
			$wgDatabase->addQuotes($column)));
	$row = $wgDatabase->fetchRow($res);
	$istype = false;
	if ($row)
		$istype = $row[0] === $wanttype;
	$wgDatabase->freeResult($res);
	return $istype;
}

function
pg_column_exists($table, $column)
{
global	$wgDatabase, $wgDBname, $wgDBmwschema;

	$q = <<<END
SELECT 1 FROM pg_class, pg_namespace, pg_attribute
	WHERE relnamespace=pg_namespace.oid AND relkind='r'
	      AND attrelid=pg_class.oid
	      AND nspname=%s AND relname=%s AND attname=%s;
END;
	$res = $wgDatabase->query(sprintf($q,
			$wgDatabase->addQuotes($wgDBmwschema),
			$wgDatabase->addQuotes($table),
			$wgDatabase->addQuotes($column)));
	$row = $wgDatabase->fetchRow($res);
	$exists = !!$row;
	$wgDatabase->freeResult($res);
	return $exists;
}

function
pg_table_exists($table)
{
global	$wgDatabase, $wgDBname, $wgDBmwschema;

	$q = <<<END
SELECT 1 FROM pg_class, pg_namespace
	WHERE relnamespace=pg_namespace.oid AND relkind='r'
	      AND nspname=%s AND relname=%s
END;
	$res = $wgDatabase->query(sprintf($q,
			$wgDatabase->addQuotes($wgDBmwschema),
			$wgDatabase->addQuotes($table)));
	$row = $wgDatabase->fetchRow($res);
	$exists = !!$row;
	$wgDatabase->freeResult($res);
	return $exists;
}

function
pg_trigger_exists($table, $trigger)
{
global	$wgDatabase, $wgDBname, $wgDBmwschema;

	$q = <<<END
SELECT 1 FROM pg_class, pg_namespace, pg_trigger
	WHERE relnamespace=pg_namespace.oid AND relkind='r'
	      AND tgrelid=pg_class.oid
	      AND nspname=%s AND relname=%s AND tgname=%s
END;
	$res = $wgDatabase->query(sprintf($q,
			$wgDatabase->addQuotes($wgDBmwschema),
			$wgDatabase->addQuotes($table),
			$wgDatabase->addQuotes($trigger)));
	$row = $wgDatabase->fetchRow($res);
	$exists = !!$row;
	$wgDatabase->freeResult($res);
	return $exists;
}


function
pg_index_exists($table, $index)
{
global	$wgDatabase, $wgDBmwschema;
	$exists = $wgDatabase->selectField("pg_indexes", "indexname",
			array(	"indexname" => $index,
				"tablename" => $table,
				"schemaname" => $wgDBmwschema));
	return $exists === $index;
}

function
pg_rule_exists($table, $rule)
{
global	$wgDatabase, $wgDBmwschema;
	$exists = $wgDatabase->selectField("pg_rules", "rulename",
			array(	"rulename" => $rule,
				"tablename" => $table,
				"schemaname" => $wgDBmwschema));
	return $exists === $rule;
}

function do_postgres_updates() {
	global $wgDatabase, $wgVersion, $wgDBmwschema;

	# Just in case their LocalSetings.php does not have this:
	if ( !isset( $wgDBmwschema ))
		$wgDBmwschema = 'mediawiki';

	## Default to the oldest supported version
	$version = 1.7;

	$thisver = array();
	if ($wgDatabase->tableExists("mediawiki_version")) {
		$version = "1.8";
		$sql = "SELECT mw_version FROM mediawiki_version ORDER BY cdate DESC LIMIT 1";
		$tempversion = pg_fetch_result($wgDatabase->doQuery($sql),0,0);
		if (preg_match('/(\d+\.\d+)/', $tempversion, $thisver)) {
			$version = $thisver[1];
		}
	}

	print " Detected version: $version\n";

	## Transform to a standard format
	if (! preg_match('/(\d+)\.(\d+)/', $version, $thisver)) {
		die ("Sorry, could not figure out what version \"$version\" means");
	}
	$oldversion = $version;
	$version = sprintf("%d%03d",$thisver[1],$thisver[2]);
	print " Standardized version: $version\n";

	$upgrade = '';

	$typechanges = array(
		array("oldimage",     "oi_size",    "integer"),
		array("oldimage",     "oi_width",   "integer"),
		array("oldimage",     "oi_height",  "integer"),
		array("image",        "img_size",   "integer"),
		array("image",        "img_width",  "integer"),
		array("image",        "img_height", "integer"),
	);

	$newcols = array(
		array("mwuser",        "user_newpass_time",    "TIMESTAMPTZ"),
		array("mwuser",        "user_editcount",       "INTEGER"),
		array("ipblocks",      "ipb_anon_only",        "CHAR NOT NULL DEFAULT '0'"),
		array("ipblocks",      "ipb_create_account",   "CHAR NOT NULL DEFAULT '1'"),
		array("ipblocks",      "ipb_enable_autoblock", "CHAR NOT NULL DEFAULT '1'"),
		array("recentchanges", "rc_old_len",           "INT"),
		array("recentchanges", "rc_new_len",           "INT")
	);

	$newtables = array(
		array("mwuser",            "patch-mwuser.sql"),
		array("pagecontent",       "patch-pagecontent.sql"),
		array("querycachetwo",     "patch-querycachetwo.sql"),
		array("redirect",          "patch-redirect.sql"),
		array("page_restrictions", "patch-page_restrictions.sql"),
		array("profiling",         "patch-profiling.sql"),
		array("mediawiki_version", "patch-mediawiki_version.sql"),
		array("archive2",          "patch-archive2.sql")
	);

	$newindexes = array(
		array("revision", "rev_text_id_idx",          "patch-rev_text_id_idx.sql")
	);

	$newrules = array(
		array("archive", "archive_delete", "patch-archive_delete.sql")
	);

	foreach ($newtables as $nt) {
		if (pg_table_exists($nt[0])) {
			echo "... table $nt[0] already exists\n";
			continue;
		}

		echo "... create table $nt[0]\n";
		dbsource(archive($nt[1]));
	}

	foreach ($newcols as $nc) {
		if (pg_column_exists($nc[0], $nc[1])) {
			echo "... column $nc[0].$nc[1] already exists\n";
			continue;
		}

		echo "... add column $nc[0].$nc[1]\n";
		$wgDatabase->query("ALTER TABLE $nc[0] ADD $nc[1] $nc[2]");
	}

	foreach ($typechanges as $tc) {
		if (!pg_column_exists($tc[0], $tc[1])) {
			echo "... error: expected column $tc[0].$tc[1] to exist\n";
			exit(1);
		}

		if (pg_column_has_type($tc[0], $tc[1], $tc[2]))
			echo "... $tc[0].$tc[1] is already $tc[2]\n";
		else {
			echo "... change $tc[0].$tc[1] to $tc[2]\n";
			$wgDatabase->query("ALTER TABLE $tc[0] ALTER $tc[1] TYPE $tc[2];\nCOMMIT;\n");
		}
	}

	if (!pg_column_has_type("user_newtalk", "user_ip", "text")) {
		echo "... convert user_newtalk.user_ip to text\n";
		$wgDatabase->query("ALTER TABLE user_newtalk ALTER user_ip TYPE TEXT USING host(user_ip)");
	}

	foreach ($newindexes as $ni) {
		if (pg_index_exists($ni[0], $ni[1])) {
			echo "... index $ni[1] on $ni[0] already exists\n";
			continue;
		}
		dbsource(archive($ni[2]));
	}

	foreach ($newrules as $nr) {
		if (pg_rule_exists($nr[0], $nr[1])) {
			echo "... rule $nr[1] on $nr[0] already exists\n";
			continue;
		}
		dbsource(archive($nr[2]));
	}

	if (!pg_trigger_exists("page", "page_deleted")) {
		echo "... create page_deleted trigger\n";
		dbsource(archive('patch-page_deleted.sql'));
	}

	## 1.8 Updater
	if ($version < 1008) {
		$upgrade .= <<<PGEND

-- Constraint tweaking:
ALTER TABLE recentchanges ALTER rc_cur_id DROP NOT NULL;

-- Index order rearrangements:
DROP INDEX pagelink_unique;
CREATE UNIQUE INDEX pagelink_unique ON pagelinks (pl_from,pl_namespace,pl_title);

-- Note this upgrade
INSERT INTO mediawiki_version (type,mw_version,notes)
VALUES ('Upgrade','MWVERSION','Upgrade from older pre 1.8 version THISVERSION aka SVERSION');

PGEND;

	} ## end version 1.8

	## 1.9 Updater
	if ($version < 1009) {
		$upgrade = <<<PGEND

-- Tighten up restrictions on the revision table so we don't lose data:
ALTER TABLE revision DROP CONSTRAINT revision_rev_user_fkey;
ALTER TABLE revision ADD CONSTRAINT revision_rev_user_fkey
  FOREIGN KEY (rev_user) REFERENCES mwuser(user_id) ON DELETE RESTRICT;

-- Despite it's name, ipb_address does not necessarily contain IP addresses :)
ALTER TABLE ipblocks ALTER ipb_address TYPE TEXT USING ipb_address::TEXT;

-- Note this upgrade
INSERT INTO mediawiki_version (type,mw_version,notes)
VALUES ('Upgrade','MWVERSION','Upgrade from older pre 1.9 version THISVERSION aka SVERSION');

PGEND;

	} ## end version 1.9

	## 1.10 updater
	if ($version <= 1010) {
		$upgrade = <<<PGEND

-- Fix the archive rule ar_timestamp field
CREATE OR REPLACE RULE archive_insert AS ON INSERT TO archive
DO INSTEAD INSERT INTO archive2 VALUES (
  NEW.ar_namespace, NEW.ar_title, NEW.ar_text, NEW.ar_comment, NEW.ar_user, NEW.ar_user_text, 
  TO_TIMESTAMP(NEW.ar_timestamp, 'YYYYMMDDHH24MISS'),
  NEW.ar_minor_edit, NEW.ar_flags, NEW.ar_rev_id, NEW.ar_text_id
);

-- Note this upgrade
INSERT INTO mediawiki_version (type,mw_version,notes)
VALUES ('Upgrade','MWVERSION','Upgrade from older pre 1.10 version THISVERSION aka SVERSION');


PGEND;
	} ## end version 1.10

	if ( !strlen($upgrade)) {
		print "No updates needed for this version ($oldversion)\n";
		return;
	}

	$upgrade = str_replace( 'MWVERSION', $wgVersion, $upgrade );
	$upgrade = str_replace( 'THISVERSION', $oldversion, $upgrade );
	$upgrade = str_replace( 'SVERSION', $version, $upgrade );
	$wgDatabase->query("BEGIN;\n\n $upgrade\n\nCOMMIT;\n");

	return;
}

?>
