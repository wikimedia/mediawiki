<?php
/**
 * @file
 * @ingroup Maintenance
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	echo "This file is not a valid entry point\n";
	exit( 1 );
}

require_once 'convertLinks.inc';
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

function add_field( $table, $field, $patch, $fullpath = false ) {
	global $wgDatabase;
	if ( !$wgDatabase->tableExists( $table ) ) {
		wfOut( "...$table table does not exist, skipping new field patch\n" );
	} elseif ( $wgDatabase->fieldExists( $table, $field ) ) {
		wfOut( "...have $field field in $table table.\n" );
	} else {
		wfOut( "Adding $field field to table $table..." );
		if ( $fullpath ) {
			$wgDatabase->sourceFile( $patch );
		} else {
			$wgDatabase->sourceFile( archive( $patch ) );
		}
		wfOut( "ok\n" );
	}
}

function add_index( $table, $index, $patch, $fullpath = false ) {
	global $wgDatabase;
	if ( $wgDatabase->indexExists( $table, $index ) ) {
		wfOut( "...$index key already set on $table table.\n" );
	} else {
		wfOut( "Adding $index key to table $table... " );
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

function do_logging_timestamp_index() {
	global $wgDatabase;
	if ( $wgDatabase->indexExists( 'logging', 'times' ) ) {
		wfOut( "...timestamp key on logging already exists.\n" );
	} else {
		wfOut( "Adding timestamp key on logging table... " );
		$wgDatabase->sourceFile( archive( "patch-logging-times-index.sql" ) );
		wfOut( "ok\n" );
	}
}

function do_archive_user_index() {
	global $wgDatabase;
	if ( $wgDatabase->indexExists( 'archive', 'usertext_timestamp' ) ) {
		wfOut( "...usertext,timestamp key on archive already exists.\n" );
	} else {
		wfOut( "Adding usertext,timestamp key on archive table... " );
		$wgDatabase->sourceFile( archive( "patch-archive-user-index.sql" ) );
		wfOut( "ok\n" );
	}
}

function do_image_user_index() {
	global $wgDatabase;
	if ( $wgDatabase->indexExists( 'image', 'img_usertext_timestamp' ) ) {
		wfOut( "...usertext,timestamp key on image already exists.\n" );
	} else {
		wfOut( "Adding usertext,timestamp key on image table... " );
		$wgDatabase->sourceFile( archive( "patch-image-user-index.sql" ) );
		wfOut( "ok\n" );
	}
}

function do_oldimage_user_index() {
	global $wgDatabase;
	if ( $wgDatabase->indexExists( 'oldimage', 'oi_usertext_timestamp' ) ) {
		wfOut( "...usertext,timestamp key on oldimage already exists.\n" );
	} else {
		wfOut( "Adding usertext,timestamp key on oldimage table... " );
		$wgDatabase->sourceFile( archive( "patch-oldimage-user-index.sql" ) );
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

function do_user_update() {
	global $wgDatabase;
	if ( $wgDatabase->fieldExists( 'user', 'user_emailauthenticationtimestamp' ) ) {
		wfOut( "User table contains old email authentication field. Dropping... " );
		$wgDatabase->sourceFile( archive( 'patch-email-authentication.sql' ) );
		wfOut( "ok\n" );
	} else {
		wfOut( "...user table does not contain old email authentication field.\n" );
	}
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

function do_inverse_timestamp() {
	global $wgDatabase;
	if ( $wgDatabase->fieldExists( 'revision', 'inverse_timestamp' ) ) {
		wfOut( "Removing revision.inverse_timestamp and fixing indexes... " );
		$wgDatabase->sourceFile( archive( 'patch-inverse_timestamp.sql' ) );
		wfOut( "ok\n" );
	} else {
		wfOut( "...revision timestamp indexes already up to 2005-03-13\n" );
	}
}

function do_text_id() {
	global $wgDatabase;
	if ( $wgDatabase->fieldExists( 'revision', 'rev_text_id' ) ) {
		wfOut( "...rev_text_id already in place.\n" );
	} else {
		wfOut( "Adding rev_text_id field... " );
		$wgDatabase->sourceFile( archive( 'patch-rev_text_id.sql' ) );
		wfOut( "ok\n" );
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

function do_drop_img_type() {
	global $wgDatabase;

	if ( $wgDatabase->fieldExists( 'image', 'img_type' ) ) {
		wfOut( "Dropping unused img_type field in image table... " );
		$wgDatabase->sourceFile( archive( 'patch-drop_img_type.sql' ) );
		wfOut( "ok\n" );
	} else {
		wfOut( "...no img_type field in image table; Good.\n" );
	}
}

function do_old_links_update() {
	global $wgDatabase;
	if ( $wgDatabase->tableExists( 'pagelinks' ) ) {
		wfOut( "...have pagelinks; skipping old links table updates.\n" );
	} else {
		convertLinks(); flush();
	}
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
	global $wgDBtype, $IP;
	if ( file_exists( "$IP/maintenance/$wgDBtype/archives/$name" ) ) {
		return "$IP/maintenance/$wgDBtype/archives/$name";
	} else {
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
	$patch2 = 'patch-page_restrictions_sortkey.sql';

	if ( $wgDatabase->tableExists( $name ) ) {
		wfOut( "...$name table already exists.\n" );
	} else {
		wfOut( "Creating $name table..." );
		$wgDatabase->sourceFile( archive( $patch ) );
		$wgDatabase->sourceFile( archive( $patch2 ) );
		wfOut( "ok\n" );

		wfOut( "Migrating old restrictions to new table..." );

		$res = $wgDatabase->select( 'page', array( 'page_id', 'page_restrictions' ), array( "page_restrictions!=''", "page_restrictions!='edit=:move='" ), __METHOD__ );

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

			# Figure out what the restrictions are..
			$id = $row->page_id;
			$flatrestrictions = explode( ':', $row->page_restrictions );

			$restrictions = array ();
			foreach ( $flatrestrictions as $restriction ) {
				$thisrestriction = explode( '=', $restriction, 2 );
				if ( count( $thisrestriction ) == 1 ) {
					// Compatibility with old protections from before
					// separate move protection was added.
					list( $level ) = $thisrestriction;
					if ( $level ) {
						$restrictions['edit'] = $level;
						$restrictions['move'] = $level;
					}
				} else {
					list( $type, $level ) = $thisrestriction;
					if ( $level ) {
						$restrictions[$type] = $level;
					}
				}

			$wgDatabase->update( 'page', array ( 'page_restrictions' => '' ), array( 'page_id' => $id ), __METHOD__ );

			}

			foreach ( $restrictions as $type => $level ) {
				$wgDatabase->insert( 'page_restrictions', array ( 'pr_page' => $id,
											'pr_type' => $type,
											'pr_level' => $level,
											'pr_cascade' => 0,
											'pr_expiry' => 'infinity' ),
											__METHOD__ );
			}
		}
		wfOut( "ok\n" );
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

/***********************************************************************
 * Start PG stuff
 * TODO: merge with above
 ***********************************************************************/

function pg_describe_table( $table ) {
	global $wgDatabase, $wgDBmwschema;
	$q = <<<END
SELECT attname, attnum FROM pg_namespace, pg_class, pg_attribute
	WHERE pg_class.relnamespace = pg_namespace.oid
	  AND attrelid=pg_class.oid AND attnum > 0
	  AND relname=%s AND nspname=%s
END;
	$res = $wgDatabase->query( sprintf( $q,
			$wgDatabase->addQuotes( $table ),
			$wgDatabase->addQuotes( $wgDBmwschema ) ) );
	if ( !$res ) {
		return null;
	}

	$cols = array();
	while ( $r = $wgDatabase->fetchRow( $res ) ) {
		$cols[] = array(
				"name" => $r[0],
				"ord" => $r[1],
			);
	}
	return $cols;
}

function pg_describe_index( $idx ) {
	global $wgDatabase, $wgDBmwschema;

	// first fetch the key (which is a list of columns ords) and
	// the table the index applies to (an oid)
	$q = <<<END
SELECT indkey, indrelid FROM pg_namespace, pg_class, pg_index
	WHERE nspname=%s
	  AND pg_class.relnamespace = pg_namespace.oid
	  AND relname=%s
	  AND indexrelid=pg_class.oid
END;
	$res = $wgDatabase->query( sprintf( $q,
			$wgDatabase->addQuotes( $wgDBmwschema ),
			$wgDatabase->addQuotes( $idx ) ) );
	if ( !$res ) {
		return null;
	}
	if ( !( $r = $wgDatabase->fetchRow( $res ) ) ) {
		return null;
	}

	$indkey = $r[0];
	$relid = intval( $r[1] );
	$indkeys = explode( " ", $indkey );

	$colnames = array();
	foreach ( $indkeys as $rid ) {
		$query = <<<END
SELECT attname FROM pg_class, pg_attribute
	WHERE attrelid=$relid
	  AND attnum=%d
	  AND attrelid=pg_class.oid
END;
		$r2 = $wgDatabase->query( sprintf( $query, $rid ) );
		if ( !$r2 ) {
			return null;
		}
		if ( !( $row2 = $wgDatabase->fetchRow( $r2 ) ) ) {
			return null;
		}
		$colnames[] = $row2[0];
	}

	return $colnames;
}

function pg_index_exists( $table, $index ) {
	global $wgDatabase, $wgDBmwschema;
	$exists = $wgDatabase->selectField( "pg_indexes", "indexname",
			array(	"indexname" => $index,
				"tablename" => $table,
				"schemaname" => $wgDBmwschema ) );
	return $exists === $index;
}

function pg_fkey_deltype( $fkey ) {
	global $wgDatabase, $wgDBmwschema;
	$q = <<<END
SELECT confdeltype FROM pg_constraint, pg_namespace
	WHERE connamespace=pg_namespace.oid
	  AND nspname=%s
	  AND conname=%s;
END;
	$r = $wgDatabase->query( sprintf( $q,
		$wgDatabase->addQuotes( $wgDBmwschema ),
		$wgDatabase->addQuotes( $fkey ) ) );
	if ( !( $row = $wgDatabase->fetchRow( $r ) ) ) {
		return null;
	}
	return $row[0];
}

function pg_rule_def( $table, $rule ) {
	global $wgDatabase, $wgDBmwschema;
	$q = <<<END
SELECT definition FROM pg_rules
	WHERE schemaname = %s
	  AND tablename = %s
	  AND rulename = %s
END;
	$r = $wgDatabase->query( sprintf( $q,
			$wgDatabase->addQuotes( $wgDBmwschema ),
			$wgDatabase->addQuotes( $table ),
			$wgDatabase->addQuotes( $rule ) ) );
	$row = $wgDatabase->fetchRow( $r );
	if ( !$row ) {
		return null;
	}
	$d = $row[0];
	return $d;
}

function do_postgres_updates() {
	global $wgDatabase, $wgDBmwschema, $wgDBts2schema, $wgShowExceptionDetails, $wgDBuser;

	# # Gather version numbers in case we need them
	$version = $wgDatabase->getServerVersion(); # # long string
	$numver = $wgDatabase->numeric_version; # # X.Y e.g. 8.3

	# Just in case their LocalSettings.php does not have this:
	if ( !isset( $wgDBmwschema ) ) {
		$wgDBmwschema = 'mediawiki';
	}

	# Verify that this user is configured correctly
	$safeuser = $wgDatabase->addQuotes( $wgDBuser );
	$SQL = "SELECT array_to_string(useconfig,'*') FROM pg_catalog.pg_user WHERE usename = $safeuser";
	$config = pg_fetch_result( $wgDatabase->doQuery( $SQL ), 0, 0 );
	$conf = array();
	foreach ( explode( '*', $config ) as $c ) {
		list( $x, $y ) = explode( '=', $c );
		$conf[$x] = $y;
	}
	if ( !array_key_exists( 'search_path', $conf ) ) {
		$search_path = '';
	} else {
		$search_path = $conf['search_path'];
	}
	if ( strpos( $search_path, $wgDBmwschema ) === false ) {
		wfOut( "Adding in schema \"$wgDBmwschema\" to search_path for user \"$wgDBuser\"\n" );
		$search_path = "$wgDBmwschema, $search_path";
	}
	if ( strpos( $search_path, $wgDBts2schema ) === false ) {
		wfOut( "Adding in schema \"$wgDBts2schema\" to search_path for user \"$wgDBuser\"\n" );
		$search_path = "$search_path, $wgDBts2schema";
	}
	$search_path = str_replace( ', ,', ',', $search_path );
	if ( array_key_exists( 'search_path', $conf ) === false || $search_path != $conf['search_path'] ) {
		$wgDatabase->doQuery( "ALTER USER $wgDBuser SET search_path = $search_path" );
		$wgDatabase->doQuery( "SET search_path = $search_path" );
	} else {
		$path = $conf['search_path'];
		wfOut( "... search_path for user \"$wgDBuser\" looks correct ($path)\n" );
	}
	$goodconf = array(
		'client_min_messages' => 'error',
		'DateStyle'           => 'ISO, YMD',
		'TimeZone'            => 'GMT'
	);
	foreach ( array_keys( $goodconf ) AS $key ) {
		$value = $goodconf[$key];
		if ( !array_key_exists( $key, $conf ) or $conf[$key] !== $value ) {
			wfOut( "Setting $key to '$value' for user \"$wgDBuser\"\n" );
			$wgDatabase->doQuery( "ALTER USER $wgDBuser SET $key = '$value'" );
			$wgDatabase->doQuery( "SET $key = '$value'" );
		} else {
			wfOut( "... default value of \"$key\" is correctly set to \"$value\" for user \"$wgDBuser\"\n" );
		}
	}

	$newsequences = array(
		"logging_log_id_seq",
		"page_restrictions_pr_id_seq",
	);

	$renamed_sequences = array(
		array('ipblocks_ipb_id_val', 'ipblocks_ipb_id_seq'),
		array('rev_rev_id_val', 'revision_rev_id_seq'),
		array('text_old_id_val', 'text_old_id_seq'),
		array('category_id_seq', 'category_cat_id_seq'),
		array('rc_rc_id_seq', 'recentchanges_rc_id_seq'),
		array('log_log_id_seq', 'logging_log_id_seq'),
		array('pr_id_val', 'page_restrictions_pr_id_seq'),
	);

	$newtables = array(
		array( "category",          "patch-category.sql" ),
		array( "mwuser",            "patch-mwuser.sql" ),
		array( "pagecontent",       "patch-pagecontent.sql" ),
		array( "querycachetwo",     "patch-querycachetwo.sql" ),
		array( "page_props",        "patch-page_props.sql" ),
		array( "page_restrictions", "patch-page_restrictions.sql" ),
		array( "profiling",         "patch-profiling.sql" ),
		array( "protected_titles",  "patch-protected_titles.sql" ),
		array( "redirect",          "patch-redirect.sql" ),
		array( "updatelog",         "patch-updatelog.sql" ),
		array( 'change_tag',        'patch-change_tag.sql' ),
		array( 'tag_summary',       'patch-change_tag.sql' ),
		array( 'valid_tag',         'patch-change_tag.sql' ),
		array( 'user_properties',   'patch-user_properties.sql' ),
		array( 'log_search',        'patch-log_search.sql' ),
		array( 'l10n_cache',        'patch-l10n_cache.sql' ),
		array( 'iwlinks',           'patch-iwlinks.sql' ),
	);

	$newcols = array(
		array( "archive",       "ar_deleted",           "SMALLINT NOT NULL DEFAULT 0" ),
		array( "archive",       "ar_len",               "INTEGER" ),
		array( "archive",       "ar_page_id",           "INTEGER" ),
		array( "archive",       "ar_parent_id",         "INTEGER" ),
		array( "image",         "img_sha1",             "TEXT NOT NULL DEFAULT ''" ),
		array( "ipblocks",      "ipb_allow_usertalk",   "SMALLINT NOT NULL DEFAULT 0" ),
		array( "ipblocks",      "ipb_anon_only",        "SMALLINT NOT NULL DEFAULT 0" ),
		array( "ipblocks",      "ipb_by_text",          "TEXT NOT NULL DEFAULT ''" ),
		array( "ipblocks",      "ipb_block_email",      "SMALLINT NOT NULL DEFAULT 0" ),
		array( "ipblocks",      "ipb_create_account",   "SMALLINT NOT NULL DEFAULT 1" ),
		array( "ipblocks",      "ipb_deleted",          "SMALLINT NOT NULL DEFAULT 0" ),
		array( "ipblocks",      "ipb_enable_autoblock", "SMALLINT NOT NULL DEFAULT 1" ),
		array( "filearchive",   "fa_deleted",           "SMALLINT NOT NULL DEFAULT 0" ),
		array( "logging",       "log_deleted",          "SMALLINT NOT NULL DEFAULT 0" ),
		array( "logging",       "log_id",               "INTEGER NOT NULL PRIMARY KEY DEFAULT nextval('logging_log_id_seq')" ),
		array( "logging",       "log_params",           "TEXT" ),
		array( "mwuser",        "user_editcount",       "INTEGER" ),
		array( "mwuser",        "user_hidden",          "SMALLINT NOT NULL DEFAULT 0" ),
		array( "mwuser",        "user_newpass_time",    "TIMESTAMPTZ" ),
		array( "oldimage",      "oi_deleted",           "SMALLINT NOT NULL DEFAULT 0" ),
		array( "oldimage",      "oi_major_mime",        "TEXT NOT NULL DEFAULT 'unknown'" ),
		array( "oldimage",      "oi_media_type",        "TEXT" ),
		array( "oldimage",      "oi_metadata",          "BYTEA NOT NULL DEFAULT ''" ),
		array( "oldimage",      "oi_minor_mime",        "TEXT NOT NULL DEFAULT 'unknown'" ),
		array( "oldimage",      "oi_sha1",              "TEXT NOT NULL DEFAULT ''" ),
		array( "page_restrictions", "pr_id",            "INTEGER NOT NULL UNIQUE DEFAULT nextval('page_restrictions_pr_id_val')" ),
		array( "profiling",     "pf_memory",            "NUMERIC(18,10) NOT NULL DEFAULT 0" ),
		array( "recentchanges", "rc_deleted",           "SMALLINT NOT NULL DEFAULT 0" ),
		array( "recentchanges", "rc_log_action",        "TEXT" ),
		array( "recentchanges", "rc_log_type",          "TEXT" ),
		array( "recentchanges", "rc_logid",             "INTEGER NOT NULL DEFAULT 0" ),
		array( "recentchanges", "rc_new_len",           "INTEGER" ),
		array( "recentchanges", "rc_old_len",           "INTEGER" ),
		array( "recentchanges", "rc_params",            "TEXT" ),
		array( "redirect",      "rd_interwiki",         "TEXT NULL" ),
		array( "redirect",      "rd_fragment",          "TEXT NULL" ),
		array( "revision",      "rev_deleted",          "SMALLINT NOT NULL DEFAULT 0" ),
		array( "revision",      "rev_len",              "INTEGER" ),
		array( "revision",      "rev_parent_id",        "INTEGER DEFAULT NULL" ),
		array( "site_stats",    "ss_active_users",      "INTEGER DEFAULT '-1'" ),
		array( "user_newtalk",  "user_last_timestamp",  "TIMESTAMPTZ" ),
		array( "logging",       "log_user_text",        "TEXT NOT NULL DEFAULT ''" ),
		array( "logging",       "log_page",             "INTEGER" ),
		array( "interwiki",     "iw_api",               "TEXT NOT NULL DEFAULT ''"),
		array( "interwiki",     "iw_wikiid",            "TEXT NOT NULL DEFAULT ''"),
	);


	# table, column, desired type, USING clause if needed (with new default if needed)
	$typechanges = array(
		array( "archive",      "ar_deleted",      "smallint", "" ),
		array( "archive",      "ar_minor_edit",   "smallint", "ar_minor_edit::smallint DEFAULT 0" ),
		array( "filearchive",  "fa_deleted",      "smallint", "" ),
		array( "filearchive",  "fa_height",       "integer",  "" ),
		array( "filearchive",  "fa_metadata",     "bytea",    "decode(fa_metadata,'escape')" ),
		array( "filearchive",  "fa_size",         "integer",  "" ),
		array( "filearchive",  "fa_width",        "integer",  "" ),
		array( "filearchive",  "fa_storage_group", "text",     "" ),
		array( "filearchive",  "fa_storage_key",  "text",     "" ),
		array( "image",        "img_metadata",    "bytea",    "decode(img_metadata,'escape')" ),
		array( "image",        "img_size",        "integer",  "" ),
		array( "image",        "img_width",       "integer",  "" ),
		array( "image",        "img_height",      "integer",  "" ),
		array( "interwiki",    "iw_local",        "smallint", "iw_local::smallint DEFAULT 0" ),
		array( "interwiki",    "iw_trans",        "smallint", "iw_trans::smallint DEFAULT 0" ),
		array( "ipblocks",     "ipb_auto",        "smallint", "ipb_auto::smallint DEFAULT 0" ),
		array( "ipblocks",     "ipb_anon_only",   "smallint", "CASE WHEN ipb_anon_only=' ' THEN 0 ELSE ipb_anon_only::smallint END DEFAULT 0" ),
		array( "ipblocks",     "ipb_create_account", "smallint", "CASE WHEN ipb_create_account=' ' THEN 0 ELSE ipb_create_account::smallint END DEFAULT 1" ),
		array( "ipblocks",     "ipb_enable_autoblock", "smallint", "CASE WHEN ipb_enable_autoblock=' ' THEN 0 ELSE ipb_enable_autoblock::smallint END DEFAULT 1" ),
		array( "ipblocks",     "ipb_block_email", "smallint", "CASE WHEN ipb_block_email=' ' THEN 0 ELSE ipb_block_email::smallint END DEFAULT 0" ),
		array( "ipblocks",     "ipb_address",     "text",     "ipb_address::text" ),
		array( "ipblocks",     "ipb_deleted",     "smallint", "ipb_deleted::smallint DEFAULT 0" ),
		array( "math",         "math_inputhash",  "bytea",    "decode(math_inputhash,'escape')" ),
		array( "math",         "math_outputhash", "bytea",    "decode(math_outputhash,'escape')" ),
		array( "mwuser",       "user_token",      "text",     "" ),
		array( "mwuser",       "user_email_token", "text",     "" ),
		array( "objectcache",  "keyname",         "text",     "" ),
		array( "oldimage",     "oi_height",       "integer",  "" ),
		array( "oldimage",     "oi_metadata",     "bytea",    "decode(img_metadata,'escape')" ),
		array( "oldimage",     "oi_size",         "integer",  "" ),
		array( "oldimage",     "oi_width",        "integer",  "" ),
		array( "page",         "page_is_redirect", "smallint", "page_is_redirect::smallint DEFAULT 0" ),
		array( "page",         "page_is_new",     "smallint", "page_is_new::smallint DEFAULT 0" ),
		array( "querycache",   "qc_value",        "integer",  "" ),
		array( "querycachetwo", "qcc_value",       "integer",  "" ),
		array( "recentchanges", "rc_bot",          "smallint", "rc_bot::smallint DEFAULT 0" ),
		array( "recentchanges", "rc_deleted",      "smallint", "" ),
		array( "recentchanges", "rc_minor",        "smallint", "rc_minor::smallint DEFAULT 0" ),
		array( "recentchanges", "rc_new",          "smallint", "rc_new::smallint DEFAULT 0" ),
		array( "recentchanges", "rc_type",         "smallint", "rc_type::smallint DEFAULT 0" ),
		array( "recentchanges", "rc_patrolled",    "smallint", "rc_patrolled::smallint DEFAULT 0" ),
		array( "revision",     "rev_deleted",     "smallint", "rev_deleted::smallint DEFAULT 0" ),
		array( "revision",     "rev_minor_edit",  "smallint", "rev_minor_edit::smallint DEFAULT 0" ),
		array( "templatelinks", "tl_namespace",    "smallint", "tl_namespace::smallint" ),
		array( "user_newtalk", "user_ip",         "text",     "host(user_ip)" ),
	);

	# table, column, nullability
	$nullchanges = array(
		array( "oldimage", "oi_bits",       "NULL" ),
		array( "oldimage", "oi_timestamp",  "NULL" ),
		array( "oldimage", "oi_major_mime", "NULL" ),
		array( "oldimage", "oi_minor_mime", "NULL" ),
	);

	$newindexes = array(
		array( "archive",       "archive_user_text",      "(ar_user_text)" ),
		array( "image",         "img_sha1",               "(img_sha1)" ),
		array( "oldimage",      "oi_sha1",                "(oi_sha1)" ),
		array( "page",          "page_mediawiki_title",   "(page_title) WHERE page_namespace = 8" ),
		array( "revision",      "rev_text_id_idx",        "(rev_text_id)" ),
		array( "recentchanges", "rc_timestamp_bot",       "(rc_timestamp) WHERE rc_bot = 0" ),
		array( "templatelinks", "templatelinks_from",     "(tl_from)" ),
		array( "watchlist",     "wl_user",                "(wl_user)" ),
		array( "logging",       "logging_user_type_time", "(log_user, log_type, log_timestamp)" ),
		array( "logging",       "logging_page_id_time",   "(log_page,log_timestamp)" ),
		array( "iwlinks",       "iwl_prefix_title_from",  "(iwl_prefix, iwl_title, iwl_from)" ),
	);

	$newrules = array(
	);

	# # All FK columns should be deferred
	$deferredcols = array(
		array( "archive",          "ar_user",         "mwuser(user_id) ON DELETE SET NULL" ),
		array( "categorylinks",    "cl_from",         "page(page_id) ON DELETE CASCADE" ),
		array( "externallinks",    "el_from",         "page(page_id) ON DELETE CASCADE" ),
		array( "filearchive",      "fa_deleted_user", "mwuser(user_id) ON DELETE SET NULL" ),
		array( "filearchive",      "fa_user",         "mwuser(user_id) ON DELETE SET NULL" ),
		array( "image",            "img_user",        "mwuser(user_id) ON DELETE SET NULL" ),
		array( "imagelinks",       "il_from",         "page(page_id) ON DELETE CASCADE" ),
		array( "ipblocks",         "ipb_by",          "mwuser(user_id) ON DELETE CASCADE" ),
		array( "ipblocks",         "ipb_user",        "mwuser(user_id) ON DELETE SET NULL" ),
		array( "langlinks",        "ll_from",         "page(page_id) ON DELETE CASCADE" ),
		array( "logging",          "log_user",        "mwuser(user_id) ON DELETE SET NULL" ),
		array( "oldimage",         "oi_name",         "image(img_name) ON DELETE CASCADE ON UPDATE CASCADE" ),
		array( "oldimage",         "oi_user",         "mwuser(user_id) ON DELETE SET NULL" ),
		array( "pagelinks",        "pl_from",         "page(page_id) ON DELETE CASCADE" ),
		array( "page_props",       "pp_page",         "page (page_id) ON DELETE CASCADE" ),
		array( "page_restrictions", "pr_page",         "page(page_id) ON DELETE CASCADE" ),
		array( "protected_titles", "pt_user",         "mwuser(user_id) ON DELETE SET NULL" ),
		array( "recentchanges",    "rc_cur_id",       "page(page_id) ON DELETE SET NULL" ),
		array( "recentchanges",    "rc_user",         "mwuser(user_id) ON DELETE SET NULL" ),
		array( "redirect",         "rd_from",         "page(page_id) ON DELETE CASCADE" ),
		array( "revision",         "rev_page",        "page (page_id) ON DELETE CASCADE" ),
		array( "revision",         "rev_user",        "mwuser(user_id) ON DELETE RESTRICT" ),
		array( "templatelinks",    "tl_from",         "page(page_id) ON DELETE CASCADE" ),
		array( "trackbacks",       "tb_page",         "page(page_id) ON DELETE CASCADE" ),
		array( "user_groups",      "ug_user",         "mwuser(user_id) ON DELETE CASCADE" ),
		array( "user_newtalk",     "user_id",         "mwuser(user_id) ON DELETE CASCADE" ),
		array( "user_properties",  "up_user",         "mwuser(user_id) ON DELETE CASCADE" ),
		array( "watchlist",        "wl_user",         "mwuser(user_id) ON DELETE CASCADE" ),
	);

	# Create new sequences
	foreach ( $newsequences as $ns ) {
		if ( ! $wgDatabase->sequenceExists( $ns ) ) {
			wfOut( "Creating sequence $ns\n" );
			$wgDatabase->query( "CREATE SEQUENCE $ns" );
		}
	}

	# Rename sequences
	foreach ( $renamed_sequences as $ren ) {
		if ( $wgDatabase->sequenceExists( $ren[0] ) ) {
			wfOut( "Renaming sequence $ren[0] to $ren[1]\n" );
			$wgDatabase->query( "ALTER SEQUENCE $ren[0] RENAME TO $ren[1]" );
		}
	}

	# Create new tables
	foreach ( $newtables as $nt ) {
		if ( $wgDatabase->tableExists( $nt[0] ) ) {
			wfOut( "... table \"$nt[0]\" already exists\n" );
			continue;
		}

		wfOut( "Creating table \"$nt[0]\"\n" );
		$wgDatabase->sourceFile( archive( $nt[1] ) );
	}

	# Needed before newcols
	if ( $wgDatabase->tableExists( "archive2" ) ) {
		wfOut( "Converting \"archive2\" back to normal archive table\n" );
		if ( $wgDatabase->ruleExists( "archive", "archive_insert" ) ) {
			wfOut( "Dropping rule \"archive_insert\"\n" );
			$wgDatabase->query( "DROP RULE archive_insert ON archive" );
		}
		if ( $wgDatabase->ruleExists( "archive", "archive_delete" ) ) {
			wfOut( "Dropping rule \"archive_delete\"\n" );
			$wgDatabase->query( "DROP RULE archive_delete ON archive" );
		}
		$wgDatabase->sourceFile( archive( "patch-remove-archive2.sql" ) );
	}
	else
		wfOut( "... obsolete table \"archive2\" does not exist\n" );

	foreach ( $newcols as $nc ) {
		$fi = $wgDatabase->fieldInfo( $nc[0], $nc[1] );
		if ( !is_null( $fi ) ) {
			wfOut( "... column \"$nc[0].$nc[1]\" already exists\n" );
			continue;
		}

		wfOut( "Adding column \"$nc[0].$nc[1]\"\n" );
		$wgDatabase->query( "ALTER TABLE $nc[0] ADD $nc[1] $nc[2]" );
	}

	foreach ( $typechanges as $tc ) {
		$fi = $wgDatabase->fieldInfo( $tc[0], $tc[1] );
		if ( is_null( $fi ) ) {
			wfOut( "... error: expected column $tc[0].$tc[1] to exist\n" );
			exit( 1 );
		}

		if ( $fi->type() === $tc[2] )
			wfOut( "... column \"$tc[0].$tc[1]\" is already of type \"$tc[2]\"\n" );
		else {
			wfOut( "Changing column type of \"$tc[0].$tc[1]\" from \"{$fi->type()}\" to \"$tc[2]\"\n" );
			$sql = "ALTER TABLE $tc[0] ALTER $tc[1] TYPE $tc[2]";
			if ( strlen( $tc[3] ) ) {
				$default = array();
				if ( preg_match( '/DEFAULT (.+)/', $tc[3], $default ) ) {
					$sqldef = "ALTER TABLE $tc[0] ALTER $tc[1] SET DEFAULT $default[1]";
					$wgDatabase->query( $sqldef );
					$tc[3] = preg_replace( '/\s*DEFAULT .+/', '', $tc[3] );
				}
				$sql .= " USING $tc[3]";
			}
			$sql .= ";\nCOMMIT;\n";
			$wgDatabase->query( $sql );
		}
	}

	foreach ( $nullchanges as $nc ) {
		$fi = $wgDatabase->fieldInfo( $nc[0], $nc[1] );
		if ( is_null( $fi ) ) {
			wfOut( "... error: expected column $nc[0].$nc[1] to exist\n" );
			exit( 1 );
		}
		if ( $fi->nullable() ) {
			# # It's NULL - does it need to be NOT NULL?
			if ( 'NOT NULL' === $nc[2] ) {
				wfOut( "Changing \"$nc[0].$nc[1]\" to not allow NULLs\n" );
				$wgDatabase->query( "ALTER TABLE $nc[0] ALTER $nc[1] SET NOT NULL" );
			} else {
				wfOut( "... column \"$nc[0].$nc[1]\" is already set as NULL\n" );
			}
		} else {
			# # It's NOT NULL - does it need to be NULL?
			if ( 'NULL' === $nc[2] ) {
				wfOut( "Changing \"$nc[0].$nc[1]\" to allow NULLs\n" );
				$wgDatabase->query( "ALTER TABLE $nc[0] ALTER $nc[1] DROP NOT NULL" );
			}
			else {
				wfOut( "... column \"$nc[0].$nc[1]\" is already set as NOT NULL\n" );
			}
		}
	}

	if ( $wgDatabase->fieldInfo( 'oldimage', 'oi_deleted' )->type() !== 'smallint' ) {
		wfOut( "Changing \"oldimage.oi_deleted\" to type \"smallint\"\n" );
		$wgDatabase->query( "ALTER TABLE oldimage ALTER oi_deleted DROP DEFAULT" );
		$wgDatabase->query( "ALTER TABLE oldimage ALTER oi_deleted TYPE SMALLINT USING (oi_deleted::smallint)" );
		$wgDatabase->query( "ALTER TABLE oldimage ALTER oi_deleted SET DEFAULT 0" );
	} else {
		wfOut( "... column \"oldimage.oi_deleted\" is already of type \"smallint\"\n" );
	}

	foreach ( $newindexes as $ni ) {
		if ( pg_index_exists( $ni[0], $ni[1] ) ) {
			wfOut( "... index \"$ni[1]\" on table \"$ni[0]\" already exists\n" );
			continue;
		}
		wfOut( "Creating index \"$ni[1]\" on table \"$ni[0]\" $ni[2]\n" );
		$wgDatabase->query( "CREATE INDEX $ni[1] ON $ni[0] $ni[2]" );
	}

	foreach ( $newrules as $nr ) {
		if ( $wgDatabase->ruleExists( $nr[0], $nr[1] ) ) {
			wfOut( "... rule \"$nr[1]\" on table \"$nr[0]\" already exists\n" );
			continue;
		}
		wfOut( "Adding rule \"$nr[1]\" to table \"$nr[0]\"\n" );
		$wgDatabase->sourceFile( archive( $nr[2] ) );
	}

	if ( $wgDatabase->hasConstraint( "oldimage_oi_name_fkey_cascaded" ) ) {
		wfOut( "... table \"oldimage\" has correct cascading delete/update foreign key to image\n" );
	} else {
		if ( $wgDatabase->hasConstraint( "oldimage_oi_name_fkey" ) ) {
			$wgDatabase->query( "ALTER TABLE oldimage DROP CONSTRAINT oldimage_oi_name_fkey" );
		}
		if ( $wgDatabase->hasConstraint( "oldimage_oi_name_fkey_cascade" ) ) {
			$wgDatabase->query( "ALTER TABLE oldimage DROP CONSTRAINT oldimage_oi_name_fkey_cascade" );
		}
		wfOut( "Making foreign key on table \"oldimage\" (to image) a cascade delete/update\n" );
		$wgDatabase->query( "ALTER TABLE oldimage ADD CONSTRAINT oldimage_oi_name_fkey_cascaded " .
			"FOREIGN KEY (oi_name) REFERENCES image(img_name) ON DELETE CASCADE ON UPDATE CASCADE" );
	}

	if ( !$wgDatabase->triggerExists( "page", "page_deleted" ) ) {
		wfOut( "Adding function and trigger \"page_deleted\" to table \"page\"\n" );
		$wgDatabase->sourceFile( archive( 'patch-page_deleted.sql' ) );
	} else {
		wfOut( "... table \"page\" has \"page_deleted\" trigger\n" );
	}

	$fi = $wgDatabase->fieldInfo( "recentchanges", "rc_cur_id" );
	if ( !$fi->nullable() ) {
		wfOut( "Removing NOT NULL constraint from \"recentchanges.rc_cur_id\"\n" );
		$wgDatabase->sourceFile( archive( 'patch-rc_cur_id-not-null.sql' ) );
	} else {
		wfOut( "... column \"recentchanges.rc_cur_id\" has a NOT NULL constraint\n" );
	}

	$pu = pg_describe_index( "pagelink_unique" );
	if ( !is_null( $pu ) && ( $pu[0] != "pl_from" || $pu[1] != "pl_namespace" || $pu[2] != "pl_title" ) ) {
		wfOut( "Dropping obsolete version of index \"pagelink_unique index\"\n" );
		$wgDatabase->query( "DROP INDEX pagelink_unique" );
		$pu = null;
	} else {
		wfOut( "... obsolete version of index \"pagelink_unique index\" does not exist\n" );
	}

	if ( is_null( $pu ) ) {
		wfOut( "Creating index \"pagelink_unique index\"\n" );
		$wgDatabase->query( "CREATE UNIQUE INDEX pagelink_unique ON pagelinks (pl_from,pl_namespace,pl_title)" );
	} else {
		wfOut( "... index \"pagelink_unique_index\" already exists\n" );
	}

	if ( pg_fkey_deltype( "revision_rev_user_fkey" ) == 'r' ) {
		wfOut( "... constraint \"revision_rev_user_fkey\" is ON DELETE RESTRICT\n" );
	} else {
		wfOut( "Changing constraint \"revision_rev_user_fkey\" to ON DELETE RESTRICT\n" );
		$wgDatabase->sourceFile( archive( 'patch-revision_rev_user_fkey.sql' ) );
	}

	# Fix ipb_address index
	if ( pg_index_exists( 'ipblocks', 'ipb_address' ) ) {
		wfOut( "Removing deprecated index 'ipb_address'...\n" );
		$wgDatabase->query( 'DROP INDEX ipb_address' );
	}
	if ( pg_index_exists( 'ipblocks', 'ipb_address_unique' ) ) {
		wfOut( "... have ipb_address_unique\n" );
	} else {
		wfOut( "Adding ipb_address_unique index\n" );
		$wgDatabase->sourceFile( archive( 'patch-ipb_address_unique.sql' ) );
	}
	
	# Fix iwlinks index
	if ( pg_index_exists( 'iwlinks', 'iwl_prefix' ) ) {
		wfOut( "Replacing index 'iwl_prefix' with 'iwl_prefix_from_title'...\n" );
		$wgDatabase->sourceFile( archive( 'patch-rename-iwl_prefix.sql' ) );
	}

	global $wgExtNewTables, $wgExtPGNewFields, $wgExtPGAlteredFields, $wgExtNewIndexes;
	# Add missing extension tables
	foreach ( $wgExtNewTables as $nt ) {
		if ( $wgDatabase->tableExists( $nt[0] ) ) {
			wfOut( "... table \"$nt[0]\" already exists\n" );
			continue;
		}
		wfOut( "Creating table \"$nt[0]\"\n" );
		$wgDatabase->sourceFile( $nt[1] );
	}
	# Add missing extension fields
	foreach ( $wgExtPGNewFields as $nc ) {
		$fi = $wgDatabase->fieldInfo( $nc[0], $nc[1] );
		if ( !is_null( $fi ) ) {
			wfOut( "... column \"$nc[0].$nc[1]\" already exists\n" );
			continue;
		}
		wfOut( "Adding column \"$nc[0].$nc[1]\"\n" );
		$wgDatabase->query( "ALTER TABLE $nc[0] ADD $nc[1] $nc[2]" );
	}
	# Change altered columns
	foreach ( $wgExtPGAlteredFields as $nc ) {
		$fi = $wgDatabase->fieldInfo( $nc[0], $nc[1] );
		if ( is_null( $fi ) ) {
			wfOut( "WARNING! Column \"$nc[0].$nc[1]\" does not exist but had an alter request! Please report this.\n" );
			continue;
		}
		$oldtype = $fi->type();
		$newtype = strtolower( $nc[2] );
		if ( $oldtype === $newtype ) {
			wfOut( "... column \"$nc[0].$nc[1]\" has correct type of \"$newtype\"\n" );
			continue;
		}
		$command = "ALTER TABLE $nc[0] ALTER $nc[1] TYPE $nc[2]";
		if ( isset( $nc[3] ) ) {
			$command .= " USING $nc[3]";
		}
		wfOut( "Altering column \"$nc[0].$nc[1]\" from type \"$oldtype\" to \"$newtype\"\n" );
		$wgDatabase->query( $command );
	}
	# Add missing extension indexes
	foreach ( $wgExtNewIndexes as $ni ) {
		if ( pg_index_exists( $ni[0], $ni[1] ) ) {
			wfOut( "... index \"$ni[1]\" on table \"$ni[0]\" already exists\n" );
			continue;
		}
		wfOut( "Creating index \"$ni[1]\" on table \"$ni[0]\"\n" );
		if ( preg_match( '/^\(/', $ni[2] ) ) {
			$wgDatabase->query( "CREATE INDEX $ni[1] ON $ni[0] $ni[2]" );
		}
		else {
			$wgDatabase->sourceFile( $ni[2] );
		}
	}

	foreach ( $deferredcols AS $dc ) {
		$fi = $wgDatabase->fieldInfo( $dc[0], $dc[1] );
		if ( is_null( $fi ) ) {
			wfOut( "WARNING! Column \"$dc[0].$dc[1]\" does not exist but it should! Please report this.\n" );
			continue;
		}
		if ( $fi->is_deferred() && $fi->is_deferrable() ) {
			continue;
		}
		wfOut( "Altering column \"$dc[0].$dc[1]\" to be DEFERRABLE INITIALLY DEFERRED\n" );
		$conname = $fi->conname();
		$clause = $dc[2];
		$command = "ALTER TABLE $dc[0] DROP CONSTRAINT $conname";
		$wgDatabase->query( $command );
		$command = "ALTER TABLE $dc[0] ADD CONSTRAINT $conname FOREIGN KEY ($dc[1]) REFERENCES $clause DEFERRABLE INITIALLY DEFERRED";
		$wgDatabase->query( $command );
	}

	# Tweak the page_title tsearch2 trigger to filter out slashes
	# This is create or replace, so harmless to call if not needed
	$wgDatabase->sourceFile( archive( 'patch-ts2pagetitle.sql' ) );

	# # If the server is 8.3 or higher, rewrite the tsearch2 triggers
	# # in case they have the old 'default' versions
	if ( $numver >= 8.3 ) {
		$wgDatabase->sourceFile( archive( 'patch-tsearch2funcs.sql' ) );
	}
	return;
}
