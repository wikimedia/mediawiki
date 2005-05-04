<?php
/**
 * @package MediaWiki
 * @subpackage Maintenance
 */
 
 /** */

require_once 'convertLinks.inc';
require_once 'InitialiseMessages.inc';

$wgNewTables = array(
#            table          patch file (in maintenance/archives)
	array( 'linkscc',       'patch-linkscc.sql' ),
	array( 'hitcounter',    'patch-hitcounter.sql' ),
	array( 'querycache',    'patch-querycache.sql' ),
	array( 'objectcache',   'patch-objectcache.sql' ),
	array( 'categorylinks', 'patch-categorylinks.sql' ),
	array( 'logging',       'patch-logging.sql' ),
	array( 'user_rights',   'patch-user_rights.sql' ),
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
	array( 'user_rights',   'ur_user',          'patch-rename-user_rights.sql' ),
	array( 'logging',       'log_params',       'patch-log_params.sql' ),
	array( 'site_stats',    'ss_total_pages',   'patch-ss_total_articles.sql' ),
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

# Arbitrary script execution vulnerability white knight repair
function do_vulnerability_patch_update() {
	global $wgDatabase;
	if ( $wgDatabase->tableExists( 'user_vulnerable' ) ) {
		echo "...removing security lockdown\n";
		extract( $wgDatabase->tableNames( 'user_vulnerable', 'user' ) );
		$wgDatabase->query( "RENAME TABLE $user_vulnerable TO $user" );
	}
}

/**
 * 1.4 betas were missing the 'binary' marker from logging.log_title,
 * which causes a collation mismatch error on joins in MySQL 4.1.
 */
function do_logging_encoding() {
	global $wgDatabase;
	$logging = $wgDatabase->tableName( 'logging' );
	$res = $wgDatabase->query( "SELECT log_title FROM $logging LIMIT 0" );
	$flags = explode( ' ', mysql_field_flags( $res, 0 ) );
	$wgDatabase->freeResult( $res );
	
	if( in_array( 'binary', $flags ) ) {
		echo "Logging table has correct title encoding.\n";
	} else {
		echo "Fixing title encoding on logging table... ";
		dbsource( 'maintenance/archives/patch-logging-title.sql', $wgDatabase );
		echo "ok\n";
	}
}


function do_all_updates() {
	global $wgNewTables, $wgNewFields;
	
	# Add missing tables
	foreach ( $wgNewTables as $tableRecord ) {
		add_table( $tableRecord[0], $tableRecord[1] );
		flush();
	}

	do_vulnerability_patch_update(); flush();

	# Add missing fields
	foreach ( $wgNewFields as $fieldRecord ) {
		add_field( $fieldRecord[0], $fieldRecord[1], $fieldRecord[2] );
		flush();
	}
	
	# Do schema updates which require special handling
	do_interwiki_update(); flush();
	do_index_update(); flush();
	do_linkscc_1_3_update(); flush();
	convertLinks(); flush();
	do_image_name_unique_update(); flush();

	do_logging_encoding(); flush();

	initialiseMessages(); flush();
}

?>
