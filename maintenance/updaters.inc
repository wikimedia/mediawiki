<?php
/**
 * @package MediaWiki
 * @subpackage Maintenance
 */
 
 /** */

require_once 'convertLinks.inc';
require_once 'InitialiseMessages.inc';
require_once 'archives/moveCustomMessages.inc';

$wgNewTables = array(
#            table          patch file (in maintenance/archives)
	array( 'linkscc',       'patch-linkscc.sql' ),
	array( 'hitcounter',    'patch-hitcounter.sql' ),
	array( 'querycache',    'patch-querycache.sql' ),
	array( 'objectcache',   'patch-objectcache.sql' ),
	array( 'categorylinks', 'patch-categorylinks.sql' ),
	array( 'logging',       'patch-logging.sql' ),
	array( 'user_rights',   'patch-user_rights.sql' ),
	array( 'user_groups',   'patch-userlevels.sql' ),
);

$wgNewFields = array(
#           table            field             patch file (in maintenance/archives)
	array( 'ipblocks',      'ipb_id',           'patch-ipblocks.sql' ),
	array( 'ipblocks',      'ipb_expiry',       'patch-ipb_expiry.sql' ),
	array( 'recentchanges', 'rc_type',          'patch-rc_type.sql' ),
	array( 'recentchanges', 'rc_ip',            'patch-rc_ip.sql' ),
	array( 'recentchanges', 'rc_id',            'patch-rc_id.sql' ),
	array( 'recentchanges', 'rc_patrolled',     'patch-rc-patrol.sql' ),
	array( 'user',          'user_real_name',   'patch-user-realname.sql' ),
	array( 'user',          'user_token',       'patch-user_token.sql' ),
	array( 'user_rights',   'ur_user',          'patch-rename-user_groups-and_rights.sql' ),
	array( 'group',         'group_rights',     'patch-userlevels-rights.sql' ),
);

function add_table( $name, $patch ) {
	global $wgDatabase;
	if ( $wgDatabase->tableExists( $name ) ) {
		echo "...$name table already exists.\n";
	} else {
		echo "Creating $name table...";
		dbsource( "maintenance/archives/$patch", $wgDatabase );
		echo "ok\n";
	}
}

function add_field( $table, $field, $patch ) {
	global $wgDatabase;
	if ( $wgDatabase->fieldExists( $table, $field ) ) {
		echo "...have $field field in $table table.\n";
	} else {
		echo "Adding $field field to table $table...";
		dbsource( "maintenance/archives/$patch" , $wgDatabase );
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
	global $wgDatabase;
	if( $wgDatabase->tableExists( "interwiki" ) ) {
		echo "...already have interwiki table\n";
		return true;
	}
	echo "Creating interwiki table: ";
	dbsource( "maintenance/archives/patch-interwiki.sql" );
	echo "ok\n";
	echo "Adding default interwiki definitions: ";
	dbsource( "maintenance/interwiki.sql" );
	echo "ok\n";
}

function do_index_update() {
	# Check that proper indexes are in place
	global $wgDatabase;
	$meta = $wgDatabase->fieldInfo( "recentchanges", "rc_timestamp" );
	if( $meta->multiple_key == 0 ) {
		echo "Updating indexes to 20031107: ";
		dbsource( "maintenance/archives/patch-indexes.sql" );
		echo "ok\n";
		return true;
	}
	echo "...indexes seem up to 20031107 standards\n";
	return false;
}

function do_linkscc_1_3_update() {
	// Update linkscc table to 1.3 schema if necessary
	global $wgDatabase, $wgVersion;
	if( $wgDatabase->tableExists( "linkscc" )
		&& $wgDatabase->fieldExists( "linkscc", "lcc_title" ) ) {
		echo "Altering lcc_title field from linkscc table... ";
		dbsource( "maintenance/archives/patch-linkscc-1.3.sql", $wgDatabase );
		echo "ok\n";
	} else {
		echo "...linkscc is up to date, or does not exist. Good.\n";
	}
}

function do_image_name_unique_update() {
	global $wgDatabase;
	if( $wgDatabase->indexExists( 'image', 'PRIMARY' ) ) {
		echo "...image primary key already set.\n";
	} else {
		echo "Making img_name the primary key... ";
		dbsource( "maintenance/archives/patch-image_name_primary.sql", $wgDatabase );
		echo "ok\n";
	}
}

function do_watchlist_update() {
	global $wgDatabase;
	if( $wgDatabase->fieldExists( 'watchlist', 'wl_notificationtimestamp' ) ) {
		echo "ENOTIF: The watchlist table is already set up for email notification.\n";
	} else {
		echo "ENOTIF: Adding wl_notificationtimestamp field for email notification management.";
		/* ALTER TABLE watchlist ADD (wl_notificationtimestamp varchar(14) binary NOT NULL default '0'); */
		dbsource( "maintenance/archives/patch-email-notification.sql", $wgDatabase );
		echo "ok\n";
	}
}

function do_copy_newtalk_to_watchlist() {
	global $wgDatabase;
	global $wgCommandLineMode;	# this needs to be saved while getID() and getName() are called

	$CommandLineMode_save = $wgCommandLineMode;
	$wgCommandLineMode = false;	# otherwise User:loadfromDatabase() early returns, but we need it herein

	if ( $wgDatabase->tableExists( 'user_newtalk' ) ) {
		$res = $wgDatabase->safeQuery( 'SELECT user_id, user_ip FROM !',
			$wgDatabase->tableName( 'user_newtalk' ) );
		$num_newtalks=$wgDatabase->numRows($res);
		echo "ENOTIF: Now converting ".$num_newtalks." user_newtalk entries to watchlist table entries ... \n";

		$user = new User();
		for ( $i = 1; $i <= $num_newtalks; $i++ ) {
			$wluser = $wgDatabase->fetchObject( $res );
			echo 'ENOTIF: <= user_newtalk: user_id='.$wluser->user_id.' user_ip='.$wluser->user_ip."\n";
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
					echo 'ENOTIF: ====> watchlist: user_id=0 '.$wluser->user_ip."\n";
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
				echo 'ENOTIF: ====> watchlist: user_id='.$user->getID().' '.$user->getName()."\n";
			}
		}
		echo "ENOTIF: The watchlist table has got the former user_newtalk entries.\n";
		dbsource( "maintenance/archives/patch-drop-user_newtalk.sql", $wgDatabase );
		echo "ENOTIF: Deleting the user_newtalk table as its entries are now in the watchlist table.\n";
	} else {
		echo "ENOTIF: No user_newtalk table found. Nothing to convert to watchlist table entries.\n";
	}
	$wgCommandLineMode = $CommandLineMode_save;
}


function do_user_update() {
	global $wgDatabase;
	if( $wgDatabase->fieldExists( 'user', 'user_emailauthenticationtimestamp' ) ) {
		echo "EAUTHENT: The user table is already set up for email authentication.\n";
	} else {
		echo "EAUTHENT: Adding user_emailauthenticationtimestamp field for email authentication management.";
		/* ALTER TABLE user ADD (user_emailauthenticationtimestamp varchar(14) binary NOT NULL default '0'); */
		dbsource( "maintenance/archives/patch-email-authentication.sql", $wgDatabase );
		echo "ok\n";
	}
}

# Assumes that the group table has been added.
function do_group_update() {
	global $wgDatabase;
	$res = $wgDatabase->safeQuery( 'SELECT COUNT(*) AS c FROM !',
		$wgDatabase->tableName( 'group' ) );
	$row = $wgDatabase->fetchObject( $res );
	$wgDatabase->freeResult( $res );
	if( $row->c == 0 ) {
		echo "Adding default group definitions... ";
		dbsource( "maintenance/archives/patch-userlevels-defaultgroups.sql", $wgDatabase );
		echo "ok\n";
	} else {
		echo "...group definitions already in place.\n";
		$res = $wgDatabase->safeQuery( "SELECT COUNT(*) AS n FROM !
		                                 WHERE group_name IN ('Sysops','Bureaucrat')
		                                   AND group_rights NOT LIKE 'sysop'",
		                               $wgDatabase->tableName( 'group' ) );
		$row = $wgDatabase->fetchObject( $res );
		$wgDatabase->freeResult( $res );
		if( $row->n ) {
			echo "Fixing sysops group permissions... ";
			dbsource( "maintenance/archives/patch-group-sysopfix.sql", $wgDatabase );
			echo "ok\n";
		} else {
			echo "...sysop group permissions look ok.\n";
		}
	}
}

function do_schema_restructuring() {
	global $wgDatabase;
	$fname="do_schema_restructuring";
	if ( $wgDatabase->tableExists( 'page' ) ) {
		echo "...page table already exists.\n";
	} else {
		echo "...converting from cur/old to page/revision/text DB structure.\n"; flush();
		echo "......checking for duplicate entries.\n"; flush();
		$rows = $wgDatabase->query( 'SELECT cur_title, cur_namespace, COUNT(cur_namespace) AS c
				FROM cur GROUP BY cur_title, cur_namespace HAVING c>1', $fname );

		if ( $wgDatabase->numRows( $rows ) > 0 ) {
			echo "......<b>Found duplicate entries</b>\n";
			echo ( sprintf( "<b>      %-60s %3s %5s</b>\n", 'Title', 'NS', 'Count' ) );
			while ( $row = $wgDatabase->fetchObject( $rows ) ) {
				if ( ! isset( $duplicate[$row->cur_namespace] ) ) {
					$duplicate[$row->cur_namespace] = array();
				}
				$duplicate[$row->cur_namespace][] = $row->cur_title;
				echo ( sprintf( "      %-60s %3s %5s\n", $row->cur_title, $row->cur_namespace, $row->c ) );
			}
			$sql = 'SELECT cur_title, cur_namespace, cur_id, cur_timestamp FROM cur WHERE ';
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
			$sql = 'DELETE FROM cur WHERE cur_id IN ( ' . join( ',', $deleteId ) . ')';
			$rows = $wgDatabase->query( $sql, $fname );
			echo "......<b>Deleted</b> ".$wgDatabase->affectedRows()." records.\n";
		}
		

		echo "......Creating tables.\n";
		$wgDatabase->query(" CREATE TABLE page (
  			page_id int(8) unsigned NOT NULL auto_increment,
  			page_namespace tinyint NOT NULL,
  			page_title varchar(255) binary NOT NULL,
  			page_restrictions tinyblob NOT NULL default '',
  			page_counter bigint(20) unsigned NOT NULL default '0',
  			page_is_redirect tinyint(1) unsigned NOT NULL default '0',
  			page_is_new tinyint(1) unsigned NOT NULL default '0',
  			page_random real unsigned NOT NULL,
  			page_touched char(14) binary NOT NULL default '',
  			page_latest int(8) unsigned NOT NULL,

  			PRIMARY KEY page_id (page_id),
  			UNIQUE INDEX name_title (page_namespace,page_title),
  			INDEX (page_random)
			)", $fname );
		$wgDatabase->query("CREATE TABLE revision (
  			rev_id int(8) unsigned NOT NULL auto_increment,
  			rev_page int(8) unsigned NOT NULL,
  			rev_comment tinyblob NOT NULL default '',
  			rev_user int(5) unsigned NOT NULL default '0',
  			rev_user_text varchar(255) binary NOT NULL default '',
  			rev_timestamp char(14) binary NOT NULL default '',
  			rev_minor_edit tinyint(1) unsigned NOT NULL default '0',
  			inverse_timestamp char(14) binary NOT NULL default '',
  
  			PRIMARY KEY rev_page_id (rev_page, rev_id),
  			UNIQUE INDEX rev_id (rev_id),
  			INDEX rev_timestamp (rev_timestamp),
  			INDEX page_timestamp (rev_page,inverse_timestamp),
  			INDEX user_timestamp (rev_user,inverse_timestamp),
  			INDEX usertext_timestamp (rev_user_text,inverse_timestamp)
			)", $fname );

		echo "......Locking tables.\n";
		$wgDatabase->query( 'LOCK TABLES page WRITE, revision WRITE, old WRITE, cur WRITE', $fname );

		$maxold = $wgDatabase->selectField( 'old', 'max(old_id)', '', $fname );
		echo "......maxold is {$maxold}\n";

		echo "......Moving text from cur.\n";
		$wgDatabase->query( "INSERT INTO old (old_namespace, old_title, old_text, old_comment, old_user, old_user_text,
    				old_timestamp, old_minor_edit, old_flags, inverse_timestamp)
  			SELECT cur_namespace, cur_title, cur_text, cur_comment, cur_user, cur_user_text, cur_timestamp, cur_minor_edit,
    				'', inverse_timestamp
  			FROM cur", $fname );

		echo "......Setting up revision table.\n";
		$wgDatabase->query( "INSERT INTO revision (rev_id, rev_page, rev_comment, rev_user, rev_user_text, rev_timestamp,
    				inverse_timestamp, rev_minor_edit)
			SELECT old_id, cur_id, old_comment, old_user, old_user_text,
    				old_timestamp, old.inverse_timestamp, old_minor_edit
			FROM old,cur WHERE old_namespace=cur_namespace AND old_title=cur_title", $fname );

		echo "......Setting up page table.\n";
		$wgDatabase->query( "INSERT INTO page (page_id, page_namespace, page_title, page_restrictions, page_counter,
    				page_is_redirect, page_is_new, page_random, page_touched, page_latest)
  			SELECT cur_id, cur_namespace, cur_title, cur_restrictions, cur_counter, cur_is_redirect, cur_is_new,
    				cur_random, cur_touched, rev_id
  			FROM cur,revision
  			WHERE cur_id=rev_page AND rev_timestamp=cur_timestamp AND rev_id > {$maxold}", $fname );

		echo "......Unlocking tables.\n";
		$wgDatabase->query( "UNLOCK TABLES", $fname );

		echo "......Renaming old.\n";
		$wgDatabase->query( "ALTER TABLE old RENAME TO text", $fname );
		echo "...done.\n";
	}
}

function do_all_updates() {
	global $wgNewTables, $wgNewFields;
	
	# Add missing tables
	foreach ( $wgNewTables as $tableRecord ) {
		add_table( $tableRecord[0], $tableRecord[1] );
		flush();
	}

	# Add missing fields
	foreach ( $wgNewFields as $fieldRecord ) {
		add_field( $fieldRecord[0], $fieldRecord[1], $fieldRecord[2] );
		flush();
	}
	
	# Add default group data
	do_group_update(); flush();

	# Do schema updates which require special handling
	do_interwiki_update(); flush();
	do_index_update(); flush();
	do_linkscc_1_3_update(); flush();
	convertLinks(); flush();
	do_image_name_unique_update(); flush();
	do_watchlist_update(); flush();
	do_copy_newtalk_to_watchlist(); flush();
	do_user_update(); flush();

	do_schema_restructuring(); flush();

	if ( isTemplateInitialised() ) {
		print "Template namespace already initialised\n";
	} else {
		moveCustomMessages( 1 ); flush();
		moveCustomMessages( 2 ); flush();
		moveCustomMessages( 3 ); flush();
	}

	initialiseMessages(); flush();
}

?>
