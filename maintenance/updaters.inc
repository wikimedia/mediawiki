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
