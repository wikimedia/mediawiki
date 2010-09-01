<?php
/**
 * @file
 * @ingroup Maintenance
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	echo "This file is not a valid entry point\n";
	exit( 1 );
}

require_once 'userDupes.inc';
# Extension updates
require_once( "$IP/includes/Hooks.php" );

/**
 * @deprecated. Do not use, ever.
 */
$wgUpdates = array();


# For extensions only, should be populated via hooks
# $wgDBtype should be checked to specifiy the proper file
$wgExtNewTables = array(); // table, dir
$wgExtNewFields = array(); // table, column, dir
$wgExtPGNewFields = array(); // table, column, column attributes; for PostgreSQL
$wgExtPGAlteredFields = array(); // table, column, new type, conversion method; for PostgreSQL
$wgExtNewIndexes = array(); // table, index, dir
$wgExtModifiedFields = array(); // table, index, dir

# Helper function: check if the given key is present in the updatelog table.
# Obviously, only use this for updates that occur after the updatelog table was
# created!
function update_row_exists( $key ) {
	global $wgDatabase;
	$row = $wgDatabase->selectRow(
		'updatelog',
		'1',
		array( 'ul_key' => $key ),
		__FUNCTION__
	);
	return (bool)$row;
}

function modify_field( $table, $field, $patch, $fullpath = false ) {
	global $wgDatabase;
	if ( !$wgDatabase->tableExists( $table ) ) {
		wfOut( "...$table table does not exist, skipping modify field patch\n" );
	} elseif ( ! $wgDatabase->fieldExists( $table, $field ) ) {
		wfOut( "...$field field does not exist in $table table, skipping modify field patch\n" );
	} else {
		wfOut( "Modifying $field field of table $table..." );
		if ( $fullpath ) {
			$wgDatabase->sourceFile( $patch );
		} else {
			$wgDatabase->sourceFile( archive( $patch ) );
		}
		wfOut( "ok\n" );
	}
}

function drop_index_if_exists( $table, $index, $patch, $fullpath = false ) {
	global $wgDatabase;
	if ( $wgDatabase->indexExists( $table, $index ) ) {
		wfOut( "Dropping $index from table $table... " );
		if ( $fullpath ) {
			$wgDatabase->sourceFile( $patch );
		} else {
			$wgDatabase->sourceFile( archive( $patch ) );
		}
		wfOut( "ok\n" );
	} else {
		wfOut( "...$index doesn't exist.\n" );
	}
}

function do_all_updates( $shared = false, $purge = true ) {
	global $wgDatabase, $wgDBtype;

	$updater = DatabaseUpdater::newForDb( $wgDatabase, $shared );

	wfRunHooks( 'LoadExtensionSchemaUpdates', array( &$updater ) );

	$updater->doUpdates();

	if ( !defined( 'MW_NO_SETUP' ) ) {
		define( 'MW_NO_SETUP', true );
	}

	foreach( $updater->getPostDatabaseUpdateMaintenance() as $maint ) {
		call_user_func_array( array( new $maint, 'execute' ), array() );
	}

	if ( $wgDBtype === 'postgres' ) {
		return;
	}

	do_stats_init();

	if ( $purge ) {
		purge_cache();
	}
}

function archive( $name ) {
	wfDeprecated( __FUNCTION__ );
	return DatabaseBase::patchPath( $name );
}

function do_interwiki_update() {
	# Check that interwiki table exists; if it doesn't source it
	global $wgDatabase, $IP;
	if ( $wgDatabase->tableExists( "interwiki" ) ) {
		wfOut( "...already have interwiki table\n" );
		return true;
	}
	wfOut( "Creating interwiki table: " );
	$wgDatabase->sourceFile( archive( "patch-interwiki.sql" ) );
	wfOut( "ok\n" );
	wfOut( "Adding default interwiki definitions: " );
	$wgDatabase->sourceFile( "$IP/maintenance/interwiki.sql" );
	wfOut( "ok\n" );
}

function do_index_update() {
	# Check that proper indexes are in place
	global $wgDatabase;
	$meta = $wgDatabase->fieldInfo( "recentchanges", "rc_timestamp" );
	if ( !$meta->isMultipleKey() ) {
		wfOut( "Updating indexes to 20031107: " );
		$wgDatabase->sourceFile( archive( "patch-indexes.sql" ) );
		wfOut( "ok\n" );
		return true;
	}
	wfOut( "...indexes seem up to 20031107 standards\n" );
	return false;
}

function do_image_index_update() {
	global $wgDatabase;

	$meta = $wgDatabase->fieldInfo( "image", "img_major_mime" );
	if ( !$meta->isMultipleKey() ) {
		wfOut( "Updating indexes to 20050912: " );
		$wgDatabase->sourceFile( archive( "patch-mimesearch-indexes.sql" ) );
		wfOut( "ok\n" );
		return true;
	}
	wfOut( "...indexes seem up to 20050912 standards\n" );
	return false;
}

function do_image_name_unique_update() {
	global $wgDatabase;
	if ( $wgDatabase->indexExists( 'image', 'PRIMARY' ) ) {
		wfOut( "...image primary key already set.\n" );
	} else {
		wfOut( "Making img_name the primary key... " );
		$wgDatabase->sourceFile( archive( "patch-image_name_primary.sql" ) );
		wfOut( "ok\n" );
	}
}

function do_watchlist_update() {
	global $wgDatabase;
	$fname = 'do_watchlist_update';
	if ( $wgDatabase->fieldExists( 'watchlist', 'wl_notificationtimestamp' ) ) {
		wfOut( "...the watchlist table is already set up for email notification.\n" );
	} else {
		wfOut( "Adding wl_notificationtimestamp field for email notification management." );
		/* ALTER TABLE watchlist ADD (wl_notificationtimestamp varchar(14) binary NOT NULL default '0'); */
		$wgDatabase->sourceFile( archive( 'patch-email-notification.sql' ) );
		wfOut( "ok\n" );
	}
	# Check if we need to add talk page rows to the watchlist
	$talk = $wgDatabase->selectField( 'watchlist', 'count(*)', 'wl_namespace & 1', $fname );
	$nontalk = $wgDatabase->selectField( 'watchlist', 'count(*)', 'NOT (wl_namespace & 1)', $fname );
	if ( $talk != $nontalk ) {
		wfOut( "Adding missing watchlist talk page rows... " );
		flush();

		$wgDatabase->insertSelect( 'watchlist', 'watchlist',
			array(
				'wl_user' => 'wl_user',
				'wl_namespace' => 'wl_namespace | 1',
				'wl_title' => 'wl_title',
				'wl_notificationtimestamp' => 'wl_notificationtimestamp'
			), array( 'NOT (wl_namespace & 1)' ), $fname, 'IGNORE' );
		wfOut( "ok\n" );
	} else {
		wfOut( "...watchlist talk page rows already present\n" );
	}
}

function do_copy_newtalk_to_watchlist() {
	global $wgDatabase;

	$res = $wgDatabase->safeQuery( 'SELECT user_id, user_ip FROM !',
		$wgDatabase->tableName( 'user_newtalk' ) );
	$num_newtalks = $wgDatabase->numRows( $res );
	wfOut( "Now converting $num_newtalks user_newtalk entries to watchlist table entries ... \n" );

	$user = new User();
	for ( $i = 1; $i <= $num_newtalks; $i++ ) {
		$wluser = $wgDatabase->fetchObject( $res );
		if ( $wluser->user_id == 0 ) { # anonymous users ... have IP numbers as "names"
			if ( $user->isIP( $wluser->user_ip ) ) { # do only if it really looks like an IP number (double checked)
				$wgDatabase->replace( 'watchlist',
					array( array( 'wl_user', 'wl_namespace', 'wl_title', 'wl_notificationtimestamp' ) ),
					array( 'wl_user' => 0,
						'wl_namespace' => NS_USER_TALK,
						'wl_title' => $wluser->user_ip,
						'wl_notificationtimestamp' => '19700101000000'
						), 'updaters.inc::do_watchlist_update2'
					);
			}
		} else { # normal users ... have user_ids
			$user->setID( $wluser->user_id );
			$wgDatabase->replace( 'watchlist',
				array( array( 'wl_user', 'wl_namespace', 'wl_title', 'wl_notificationtimestamp' ) ),
				array( 'wl_user' => $user->getID(),
					'wl_namespace' => NS_USER_TALK,
					'wl_title' => $user->getName(),
					'wl_notificationtimestamp' => '19700101000000'
					), 'updaters.inc::do_watchlist_update3'
				);
		}
	}
	wfOut( "Done.\n" );
}

/**
 * 1.4 betas were missing the 'binary' marker from logging.log_title,
 * which causes a collation mismatch error on joins in MySQL 4.1.
 */
function check_bin( $table, $field, $patchFile ) {
	global $wgDatabase, $wgDBtype;
	if ( $wgDBtype != 'mysql' )
		return;
	$tableName = $wgDatabase->tableName( $table );
	$res = $wgDatabase->query( "SELECT $field FROM $tableName LIMIT 0" );
	$flags = explode( ' ', mysql_field_flags( $res->result, 0 ) );

	if ( in_array( 'binary', $flags ) ) {
		wfOut( "...$table table has correct $field encoding.\n" );
	} else {
		wfOut( "Fixing $field encoding on $table table... " );
		$wgDatabase->sourceFile( archive( $patchFile ) );
		wfOut( "ok\n" );
	}
}

function do_schema_restructuring() {
	global $wgDatabase;
	$fname = "do_schema_restructuring";
	if ( $wgDatabase->tableExists( 'page' ) ) {
		wfOut( "...page table already exists.\n" );
	} else {
		wfOut( "...converting from cur/old to page/revision/text DB structure.\n" );
		wfOut( wfTimestamp( TS_DB ) );
		wfOut( "......checking for duplicate entries.\n" );

		list ( $cur, $old, $page, $revision, $text ) = $wgDatabase->tableNamesN( 'cur', 'old', 'page', 'revision', 'text' );

		$rows = $wgDatabase->query( "SELECT cur_title, cur_namespace, COUNT(cur_namespace) AS c
				FROM $cur GROUP BY cur_title, cur_namespace HAVING c>1", $fname );

		if ( $wgDatabase->numRows( $rows ) > 0 ) {
			wfOut( wfTimestamp( TS_DB ) );
			wfOut( "......<b>Found duplicate entries</b>\n" );
			wfOut( sprintf( "<b>      %-60s %3s %5s</b>\n", 'Title', 'NS', 'Count' ) );
			while ( $row = $wgDatabase->fetchObject( $rows ) ) {
				if ( ! isset( $duplicate[$row->cur_namespace] ) ) {
					$duplicate[$row->cur_namespace] = array();
				}
				$duplicate[$row->cur_namespace][] = $row->cur_title;
				wfOut( sprintf( "      %-60s %3s %5s\n", $row->cur_title, $row->cur_namespace, $row->c ) );
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
			wfOut( wfTimestamp( TS_DB ) );
			wfOut( "......<b>Deleted</b> " . $wgDatabase->affectedRows() . " records.\n" );
		}


		wfOut( wfTimestamp( TS_DB ) );
		wfOut( "......Creating tables.\n" );
		$wgDatabase->query( "CREATE TABLE $page (
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
			) ENGINE=InnoDB", $fname );
		$wgDatabase->query( "CREATE TABLE $revision (
			rev_id int(8) unsigned NOT NULL auto_increment,
			rev_page int(8) unsigned NOT NULL,
			rev_comment tinyblob NOT NULL,
			rev_user int(5) unsigned NOT NULL default '0',
			rev_user_text varchar(255) binary NOT NULL default '',
			rev_timestamp char(14) binary NOT NULL default '',
			rev_minor_edit tinyint(1) unsigned NOT NULL default '0',
			rev_deleted tinyint(1) unsigned NOT NULL default '0',
			rev_len int(8) unsigned,
			rev_parent_id int(8) unsigned default NULL,
			PRIMARY KEY rev_page_id (rev_page, rev_id),
			UNIQUE INDEX rev_id (rev_id),
			INDEX rev_timestamp (rev_timestamp),
			INDEX page_timestamp (rev_page,rev_timestamp),
			INDEX user_timestamp (rev_user,rev_timestamp),
			INDEX usertext_timestamp (rev_user_text,rev_timestamp)
			) ENGINE=InnoDB", $fname );

		wfOut( wfTimestamp( TS_DB ) );
		wfOut( "......Locking tables.\n" );
		$wgDatabase->query( "LOCK TABLES $page WRITE, $revision WRITE, $old WRITE, $cur WRITE", $fname );

		$maxold = intval( $wgDatabase->selectField( 'old', 'max(old_id)', '', $fname ) );
		wfOut( wfTimestamp( TS_DB ) );
		wfOut( "......maxold is {$maxold}\n" );

		wfOut( wfTimestamp( TS_DB ) );
		global $wgLegacySchemaConversion;
		if ( $wgLegacySchemaConversion ) {
			// Create HistoryBlobCurStub entries.
			// Text will be pulled from the leftover 'cur' table at runtime.
			wfOut( "......Moving metadata from cur; using blob references to text in cur table.\n" );
			$cur_text = "concat('O:18:\"historyblobcurstub\":1:{s:6:\"mCurId\";i:',cur_id,';}')";
			$cur_flags = "'object'";
		} else {
			// Copy all cur text in immediately: this may take longer but avoids
			// having to keep an extra table around.
			wfOut( "......Moving text from cur.\n" );
			$cur_text = 'cur_text';
			$cur_flags = "''";
		}
		$wgDatabase->query( "INSERT INTO $old (old_namespace, old_title, old_text, old_comment, old_user, old_user_text,
					old_timestamp, old_minor_edit, old_flags)
			SELECT cur_namespace, cur_title, $cur_text, cur_comment, cur_user, cur_user_text, cur_timestamp, cur_minor_edit, $cur_flags
			FROM $cur", $fname );

		wfOut( wfTimestamp( TS_DB ) );
		wfOut( "......Setting up revision table.\n" );
		$wgDatabase->query( "INSERT INTO $revision (rev_id, rev_page, rev_comment, rev_user, rev_user_text, rev_timestamp,
				rev_minor_edit)
			SELECT old_id, cur_id, old_comment, old_user, old_user_text,
				old_timestamp, old_minor_edit
			FROM $old,$cur WHERE old_namespace=cur_namespace AND old_title=cur_title", $fname );

		wfOut( wfTimestamp( TS_DB ) );
		wfOut( "......Setting up page table.\n" );
		$wgDatabase->query( "INSERT INTO $page (page_id, page_namespace, page_title, page_restrictions, page_counter,
			page_is_redirect, page_is_new, page_random, page_touched, page_latest, page_len)
			SELECT cur_id, cur_namespace, cur_title, cur_restrictions, cur_counter, cur_is_redirect, cur_is_new,
				cur_random, cur_touched, rev_id, LENGTH(cur_text)
			FROM $cur,$revision
			WHERE cur_id=rev_page AND rev_timestamp=cur_timestamp AND rev_id > {$maxold}", $fname );

		wfOut( wfTimestamp( TS_DB ) );
		wfOut( "......Unlocking tables.\n" );
		$wgDatabase->query( "UNLOCK TABLES", $fname );

		wfOut( wfTimestamp( TS_DB ) );
		wfOut( "......Renaming old.\n" );
		$wgDatabase->query( "ALTER TABLE $old RENAME TO $text", $fname );

		wfOut( wfTimestamp( TS_DB ) );
		wfOut( "...done.\n" );
	}
}

function do_pagelinks_update() {
	global $wgDatabase;
	if ( $wgDatabase->tableExists( 'pagelinks' ) ) {
		wfOut( "...already have pagelinks table.\n" );
	} else {
		wfOut( "Converting links and brokenlinks tables to pagelinks... " );
		$wgDatabase->sourceFile( archive( 'patch-pagelinks.sql' ) );
		wfOut( "ok\n" );
		flush();

		global $wgCanonicalNamespaceNames;
		foreach ( $wgCanonicalNamespaceNames as $ns => $name ) {
			if ( $ns != 0 ) {
				do_pagelinks_namespace( $ns );
			}
		}
	}
}

function do_pagelinks_namespace( $namespace ) {
	global $wgDatabase, $wgContLang;

	$ns = intval( $namespace );
	wfOut( "Cleaning up broken links for namespace $ns... " );

	$pagelinks = $wgDatabase->tableName( 'pagelinks' );
	$name = $wgContLang->getNsText( $ns );
	$prefix = $wgDatabase->strencode( $name );
	$likeprefix = str_replace( '_', '\\_', $prefix );

	$sql = "UPDATE $pagelinks
	           SET pl_namespace=$ns,
	               pl_title=TRIM(LEADING '$prefix:' FROM pl_title)
	         WHERE pl_namespace=0
	           AND pl_title LIKE '$likeprefix:%'";

	$wgDatabase->query( $sql, 'do_pagelinks_namespace' );
	wfOut( "ok\n" );
}

function do_old_links_update() {
	if( !defined( 'MW_NO_SETUP' ) ) {
		define( 'MW_NO_SETUP', true );
	}
	require( "convertLinks.php" );
	$cl = new ConvertLinks();
	$cl->execute();
}

function fix_ancient_imagelinks() {
	global $wgDatabase;
	$info = $wgDatabase->fieldInfo( 'imagelinks', 'il_from' );
	if ( $info && $info->type() === 'string' ) {
		wfOut( "Fixing ancient broken imagelinks table.\n" );
		wfOut( "NOTE: you will have to run maintenance/refreshLinks.php after this.\n" );
		$wgDatabase->sourceFile( archive( 'patch-fix-il_from.sql' ) );
		wfOut( "ok\n" );
	} else {
		wfOut( "...il_from OK\n" );
	}
}

function do_user_unique_update() {
	global $wgDatabase;
	$duper = new UserDupes( $wgDatabase );
	if ( $duper->hasUniqueIndex() ) {
		wfOut( "...already have unique user_name index.\n" );
	} else {
		if ( !$duper->clearDupes() ) {
			wfOut( "WARNING: This next step will probably fail due to unfixed duplicates...\n" );
		}
		wfOut( "Adding unique index on user_name... " );
		$wgDatabase->sourceFile( archive( 'patch-user_nameindex.sql' ) );
		wfOut( "ok\n" );
	}
}

function do_user_groups_update() {
	$fname = 'do_user_groups_update';
	global $wgDatabase;

	if ( $wgDatabase->tableExists( 'user_groups' ) ) {
		wfOut( "...user_groups table already exists.\n" );
		return do_user_groups_reformat();
	}

	wfOut( "Adding user_groups table... " );
	$wgDatabase->sourceFile( archive( 'patch-user_groups.sql' ) );
	wfOut( "ok\n" );

	if ( !$wgDatabase->tableExists( 'user_rights' ) ) {
		if ( $wgDatabase->fieldExists( 'user', 'user_rights' ) ) {
			wfOut( "Upgrading from a 1.3 or older database? Breaking out user_rights for conversion..." );
			$wgDatabase->sourceFile( archive( 'patch-user_rights.sql' ) );
			wfOut( "ok\n" );
		} else {
			wfOut( "*** WARNING: couldn't locate user_rights table or field for upgrade.\n" );
			wfOut( "*** You may need to manually configure some sysops by manipulating\n" );
			wfOut( "*** the user_groups table.\n" );
			return;
		}
	}

	wfOut( "Converting user_rights table to user_groups... " );
	$result = $wgDatabase->select( 'user_rights',
		array( 'ur_user', 'ur_rights' ),
		array( "ur_rights != ''" ),
		$fname );

	while ( $row = $wgDatabase->fetchObject( $result ) ) {
		$groups = array_unique(
			array_map( 'trim',
				explode( ',', $row->ur_rights ) ) );

		foreach ( $groups as $group ) {
			$wgDatabase->insert( 'user_groups',
				array(
					'ug_user'  => $row->ur_user,
					'ug_group' => $group ),
				$fname );
		}
	}
	wfOut( "ok\n" );
}

function do_user_groups_reformat() {
	# Check for bogus formats from previous 1.5 alpha code.
	global $wgDatabase;
	$info = $wgDatabase->fieldInfo( 'user_groups', 'ug_group' );

	if ( $info->type() == 'int' ) {
		$oldug = $wgDatabase->tableName( 'user_groups' );
		$newug = $wgDatabase->tableName( 'user_groups_bogus' );
		wfOut( "user_groups is in bogus intermediate format. Renaming to $newug... " );
		$wgDatabase->query( "ALTER TABLE $oldug RENAME TO $newug" );
		wfOut( "ok\n" );

		wfOut( "Re-adding fresh user_groups table... " );
		$wgDatabase->sourceFile( archive( 'patch-user_groups.sql' ) );
		wfOut( "ok\n" );

		wfOut( "***\n" );
		wfOut( "*** WARNING: You will need to manually fix up user permissions in the user_groups\n" );
		wfOut( "*** table. Old 1.5 alpha versions did some pretty funky stuff...\n" );
		wfOut( "***\n" );
	} else {
		wfOut( "...user_groups is in current format.\n" );
	}

}

function do_watchlist_null() {
	# Make sure wl_notificationtimestamp can be NULL,
	# and update old broken items.
	global $wgDatabase;
	$info = $wgDatabase->fieldInfo( 'watchlist', 'wl_notificationtimestamp' );

	if ( !$info->nullable() ) {
		wfOut( "Making wl_notificationtimestamp nullable... " );
		$wgDatabase->sourceFile( archive( 'patch-watchlist-null.sql' ) );
		wfOut( "ok\n" );
	} else {
		wfOut( "...wl_notificationtimestamp is already nullable.\n" );
	}

}

/**
 * @bug 3946
 */
function do_page_random_update() {
	global $wgDatabase;

	wfOut( "Setting page_random to a random value on rows where it equals 0..." );

	$page = $wgDatabase->tableName( 'page' );
	$wgDatabase->query( "UPDATE $page SET page_random = RAND() WHERE page_random = 0", 'do_page_random_update' );
	$rows = $wgDatabase->affectedRows();

	wfOut( "changed $rows rows\n" );
}

function do_templatelinks_update() {
	global $wgDatabase;
	$fname = 'do_templatelinks_update';

	if ( $wgDatabase->tableExists( 'templatelinks' ) ) {
		wfOut( "...templatelinks table already exists\n" );
		return;
	}
	wfOut( "Creating templatelinks table...\n" );
	$wgDatabase->sourceFile( archive( 'patch-templatelinks.sql' ) );
	wfOut( "Populating...\n" );
	if ( wfGetLB()->getServerCount() > 1 ) {
		// Slow, replication-friendly update
		$res = $wgDatabase->select( 'pagelinks', array( 'pl_from', 'pl_namespace', 'pl_title' ),
			array( 'pl_namespace' => NS_TEMPLATE ), $fname );
		$count = 0;
		while ( $row = $wgDatabase->fetchObject( $res ) ) {
			$count = ( $count + 1 ) % 100;
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
	wfOut( "Done. Please run maintenance/refreshLinks.php for a more thorough templatelinks update.\n" );
}

// Add index on ( rc_namespace, rc_user_text ) [Jul. 2006]
// Add index on ( rc_user_text, rc_timestamp ) [Nov. 2006]
function do_rc_indices_update() {
	global $wgDatabase;
	wfOut( "Checking for additional recent changes indices...\n" );

	$indexes = array(
		'rc_ns_usertext' => 'patch-recentchanges-utindex.sql',
		'rc_user_text' => 'patch-rc_user_text-index.sql',
	);

	foreach ( $indexes as $index => $patch ) {
		$info = $wgDatabase->indexInfo( 'recentchanges', $index, __METHOD__ );
		if ( !$info ) {
			wfOut( "...index `{$index}` not found; adding..." );
			$wgDatabase->sourceFile( archive( $patch ) );
			wfOut( "done.\n" );
		} else {
			wfOut( "...index `{$index}` seems ok.\n" );
		}
	}
}

function index_has_field( $table, $index, $field ) {
	global $wgDatabase;
	wfOut( "Checking if $table index $index includes field $field...\n" );
	$info = $wgDatabase->indexInfo( $table, $index, __METHOD__ );
	if ( $info ) {
		foreach ( $info as $row ) {
			if ( $row->Column_name == $field ) {
				wfOut( "...index $index on table $table seems to be ok\n" );
				return true;
			}
		}
	}
	wfOut( "...index $index on table $table has no field $field; adding\n" );
	return false;
}

function do_backlinking_indices_update() {
	global $wgDatabase;
	wfOut( "Checking for backlinking indices...\n" );
	if ( !index_has_field( 'pagelinks', 'pl_namespace', 'pl_from' ) ||
		!index_has_field( 'templatelinks', 'tl_namespace', 'tl_from' ) ||
		!index_has_field( 'imagelinks', 'il_to', 'il_from' ) )
	{
		$wgDatabase->sourceFile( archive( 'patch-backlinkindexes.sql' ) );
		wfOut( "...backlinking indices updated\n" );
	}
}

function do_categorylinks_indices_update() {
	global $wgDatabase;
	wfOut( "Checking for categorylinks indices...\n" );
	if ( !index_has_field( 'categorylinks', 'cl_sortkey', 'cl_from' ) )
	{
		$wgDatabase->sourceFile( archive( 'patch-categorylinksindex.sql' ) );
		wfOut( "...categorylinks indices updated\n" );
	}
}

function do_filearchive_indices_update() {
	global $wgDatabase;
	wfOut( "Checking filearchive indices...\n" );
	$info = $wgDatabase->indexInfo( 'filearchive', 'fa_user_timestamp', __METHOD__ );
	if ( !$info )
	{
		$wgDatabase->sourceFile( archive( 'patch-filearchive-user-index.sql' ) );
		wfOut( "...filearchive indices updated\n" );
	}
}

function maybe_do_profiling_memory_update() {
	global $wgDatabase;
	if ( !$wgDatabase->tableExists( 'profiling' ) ) {
		// Simply ignore
	} elseif ( $wgDatabase->fieldExists( 'profiling', 'pf_memory' ) ) {
		wfOut( "...profiling table has pf_memory field.\n" );
	} else {
		wfOut( "Adding pf_memory field to table profiling..." );
		$wgDatabase->sourceFile( archive( 'patch-profiling-memory.sql' ) );
		wfOut( "ok\n" );
	}
}

function do_stats_init() {
	// Sometimes site_stats table is not properly populated.
	global $wgDatabase;
	wfOut( "\nChecking site_stats row..." );
	$row = $wgDatabase->selectRow( 'site_stats', '*', array( 'ss_row_id' => 1 ), __METHOD__ );
	if ( $row === false ) {
		wfOut( "data is missing! rebuilding...\n" );
	} elseif ( isset( $row->site_stats ) && $row->ss_total_pages == -1 ) {
		wfOut( "missing ss_total_pages, rebuilding...\n" );
	} else {
		wfOut( "ok.\n" );
		return;
	}
	SiteStatsInit::doAllAndCommit( false );
}

function do_active_users_init() {
	global $wgDatabase;
	$activeUsers = $wgDatabase->selectField( 'site_stats', 'ss_active_users', false, __METHOD__ );
	if ( $activeUsers == -1 ) {
		$activeUsers = $wgDatabase->selectField( 'recentchanges',
			'COUNT( DISTINCT rc_user_text )',
			array( 'rc_user != 0', 'rc_bot' => 0, "rc_log_type != 'newusers'" ), __METHOD__
		);
		$wgDatabase->update( 'site_stats',
			array( 'ss_active_users' => intval( $activeUsers ) ),
			array( 'ss_row_id' => 1 ), __METHOD__, array( 'LIMIT' => 1 )
		);
	}
	wfOut( "...ss_active_users user count set...\n" );
}

function purge_cache() {
	global $wgDatabase;
	# We can't guarantee that the user will be able to use TRUNCATE,
	# but we know that DELETE is available to us
	wfOut( "Purging caches..." );
	$wgDatabase->delete( 'objectcache', '*', __METHOD__ );
	wfOut( "done.\n" );
}

/**
 * Adding page_restrictions table, obsoleting page.page_restrictions.
 * Migrating old restrictions to new table
 * -- Andrew Garrett, January 2007.
 */
function do_restrictions_update() {
	global $wgDatabase;

	if ( $wgDatabase->tableExists( 'page_restrictions' ) ) {
		wfOut( "...page_restrictions table already exists.\n" );
	} else {
		wfOut( "Creating page_restrictions table..." );
		$wgDatabase->sourceFile( archive( 'patch-page_restrictions.sql' ) );
		$wgDatabase->sourceFile( archive( 'patch-page_restrictions_sortkey.sql' ) );
		wfOut( "ok\n" );

		wfOut( "Migrating old restrictions to new table...\n" );
		require_once( 'updateRestrictions.php' );
		$task = new UpdateRestrictions();
		$task->execute();
	}
}

function do_category_population() {
	if ( update_row_exists( 'populate category' ) ) {
		wfOut( "...category table already populated.\n" );
		return;
	}
	require_once( 'populateCategory.php' );
	wfOut(
		"Populating category table, printing progress markers. " .
		"For large databases, you\n" .
		"may want to hit Ctrl-C and do this manually with maintenance/\n" .
		"populateCategory.php.\n"
	);
	$task = new PopulateCategory();
	$task->execute();
	wfOut( "Done populating category table.\n" );
}

function do_populate_parent_id() {
	if ( update_row_exists( 'populate rev_parent_id' ) ) {
		wfOut( "...rev_parent_id column already populated.\n" );
		return;
	}
	require_once( 'populateParentId.php' );
	$task = new PopulateParentId();
	$task->execute();
}

function do_populate_rev_len() {
	if ( update_row_exists( 'populate rev_len' ) ) {
		wfOut( "...rev_len column already populated.\n" );
		return;
	}
	require_once( 'populateRevisionLength.php' );
	$task = new PopulateRevisionLength();
	$task->execute();
}

function do_collation_update() {
	global $wgDatabase, $wgCollationVersion;
	if ( $wgDatabase->selectField(
		'categorylinks',
		'COUNT(*)',
		'cl_collation != ' . $wgDatabase->addQuotes( $wgCollationVersion ),
		__FUNCTION__
	) == 0 ) {
		wfOut( "...collations up-to-date.\n" );
		return;
	}
	require_once( 'updateCollation.php' );
	$task = new UpdateCollation();
	$task->execute();
}

function sqlite_initial_indexes() {
	global $wgDatabase;
	// initial-indexes.sql fails if the indexes are already present, so we perform a quick check if our database is newer.
	if ( update_row_exists( 'initial_indexes' ) || $wgDatabase->indexExists( 'user', 'user_name' ) ) {
		wfOut( "...have initial indexes\n" );
		return;
	}
	wfOut( "Adding initial indexes..." );
	$wgDatabase->sourceFile( archive( 'initial-indexes.sql' ) );
	wfOut( "done\n" );
}

function sqlite_setup_searchindex() {
	global $wgDatabase;
	$module = $wgDatabase->getFulltextSearchModule();
	$fts3tTable = update_row_exists( 'fts3' );
	if ( $fts3tTable &&  !$module ) {
		wfOut( '...PHP is missing FTS3 support, downgrading tables...' );
		$wgDatabase->sourceFile( archive( 'searchindex-no-fts.sql' ) );
		wfOut( "done\n" );
	} elseif ( !$fts3tTable && $module == 'FTS3' ) {
		wfOut( '...adding FTS3 search capabilities...' );
		$wgDatabase->sourceFile( archive( 'searchindex-fts3.sql' ) );
		wfOut( "done\n" );
	} else {
		wfOut( "...fulltext search table appears to be in order.\n" );
	}
}

function do_unique_pl_tl_il() {
	global $wgDatabase;
	$info = $wgDatabase->indexInfo( 'pagelinks', 'pl_namespace' );
	if ( is_array( $info ) && !$info[0]->Non_unique ) {
		wfOut( "...pl_namespace, tl_namespace, il_to indices are already UNIQUE.\n" );
	} else {
		wfOut( "Making pl_namespace, tl_namespace and il_to indices UNIQUE... " );
		$wgDatabase->sourceFile( archive( 'patch-pl-tl-il-unique.sql' ) );
		wfOut( "ok\n" );
	}
}

function do_log_search_population() {
	if ( update_row_exists( 'populate log_search' ) ) {
		wfOut( "...log_search table already populated.\n" );
		return;
	}
	require_once( 'populateLogSearch.php' );
	wfOut(
"Populating log_search table, printing progress markers. For large\n" .
"databases, you may want to hit Ctrl-C and do this manually with\n" .
"maintenance/populateLogSearch.php.\n" );
	$task = new PopulateLogSearch();
	$task->execute();
	wfOut( "Done populating log_search table.\n" );
}

function rename_eu_wiki_id() {
	global $wgDatabase;
	if ( $wgDatabase->fieldExists( 'external_user', 'eu_local_id' ) ) {
		wfOut( "...eu_wiki_id already renamed to eu_local_id.\n" );
		return;
	}
	wfOut( "Renaming eu_wiki_id -> eu_local_id... " );
	$wgDatabase->sourceFile( archive( 'patch-eu_local_id.sql' ) );
	wfOut( "ok\n" );
}

function do_update_transcache_field() {
	global $wgDatabase;
	if ( update_row_exists( 'convert transcache field' ) ) {
		wfOut( "...transcache tc_time already converted.\n" );
		return;
	} else {
		wfOut( "Converting tc_time from UNIX epoch to MediaWiki timestamp... " );
		$wgDatabase->sourceFile( archive( 'patch-tc-timestamp.sql' ) );
		wfOut( "ok\n" );
	}
}

function do_update_mime_minor_field() {
	if ( update_row_exists( 'mime_minor_length' ) ) {
		wfOut( "...*_mime_minor fields are already long enough.\n" );
	} else {
		global $wgDatabase;
		wfOut( "Altering all *_mime_minor fields to 100 bytes in size ... " );
		$wgDatabase->sourceFile( archive( 'patch-mime_minor_length.sql' ) );
		wfOut( "ok\n" );
	}
}
