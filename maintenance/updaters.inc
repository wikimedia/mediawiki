<?php
/**
 * @package MediaWiki
 * @subpackage Maintenance
 */
 
 /** */
function do_revision_updates() {
	global $wgSoftwareRevision;
	if ( $wgSoftwareRevision < 1001 ) {
		update_passwords();
	}
}

function update_passwords() {
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

function do_ipblocks_update() {
	global $wgDatabase;

	$do1 = $do2 = false;

	if ( !$wgDatabase->fieldExists( "ipblocks", "ipb_id" ) ) {
		$do1 = true;
	}
	if ( !$wgDatabase->fieldExists( "ipblocks", "ipb_expiry" ) ) {
		$do2 = true;
	}

	if ( $do1 || $do2 ) {
		echo "Updating ipblocks table... ";
		if ( $do1 ) {
			dbsource( "maintenance/archives/patch-ipblocks.sql", $wgDatabase );
		}
		if ( $do2 ) {
			dbsource( "maintenance/archives/patch-ipb_expiry.sql", $wgDatabase );
		}
		echo "ok\n";
	} else {
		echo "...ipblocks is up to date.\n";
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

function do_linkscc_update() {
	// Create linkscc if necessary
	global $wgDatabase;
	if( $wgDatabase->tableExists( "linkscc" ) ) {
		echo "...have linkscc table.\n";
	} else {
		echo "Adding linkscc table... ";
		dbsource( "maintenance/archives/patch-linkscc.sql", $wgDatabase );
		echo "ok\n";
	}
}

function do_linkscc_1_3_update() {
	// Update linkscc table to 1.3 schema if necessary
	global $wgDatabase, $wgVersion;
	if( ( strpos( "1.3", $wgVersion ) === 0 ) && $wgDatabase->tableExists( "linkscc" )
		&& $wgDatabase->fieldExists( "linkscc", "lcc_title" ) ) {
		echo "Altering lcc_title field from linkscc table... ";
		dbsource( "maintenance/archives/patch-linkscc-1.3.sql", $wgDatabase );
		echo "ok\n";
	} else {
		echo "...linkscc is up to date, or does not exist. Good.\n";
	}
}

function do_hitcounter_update() {
	// Create hitcounter if necessary
	global $wgDatabase;
	if( $wgDatabase->tableExists( "hitcounter" ) ) {
		echo "...have hitcounter table.\n";
	} else {
		echo "Adding hitcounter table... ";
		dbsource( "maintenance/archives/patch-hitcounter.sql", $wgDatabase );
		echo "ok\n";
	}
}

function do_recentchanges_update() {
	global $wgDatabase;
	if ( !$wgDatabase->fieldExists( "recentchanges", "rc_type" ) ) {
		echo "Adding rc_type, rc_moved_to_ns, rc_moved_to_title...";
		dbsource( "maintenance/archives/patch-rc_type.sql" , $wgDatabase );
		echo "ok\n";
	}
	if ( !$wgDatabase->fieldExists( "recentchanges", "rc_ip" ) ) {
		echo "Adding rc_ip...";
		dbsource( "maintenance/archives/patch-rc_ip.sql", $wgDatabase );
		echo "ok\n";
	}
	if ( !$wgDatabase->fieldExists( "recentchanges", "rc_id" ) ) {
		echo "Adding rc_id...";
		dbsource( "maintenance/archives/patch-rc_id.sql", $wgDatabase );
		echo "ok\n";
	}
	if ( !$wgDatabase->fieldExists( "recentchanges", "rc_patrolled" ) ) {
		echo "Adding rc_patrolled...";
		dbsource( "maintenance/archives/patch-rc-patrol.sql", $wgDatabase );
		echo "ok\n";
	}
}

function do_user_real_name_update() {
	global $wgDatabase;
	if ( $wgDatabase->fieldExists( "user", "user_real_name" ) ) {
		echo "...have user_real_name field in user table.\n";
        } else {
		echo "Adding user_real_name field to table user...";
		dbsource( "maintenance/archives/patch-user-realname.sql" , $wgDatabase );
		echo "ok\n";
	}
}

function do_querycache_update() {
	global $wgDatabase;
	if( $wgDatabase->tableExists( "querycache" ) ) {
		echo "...have special page querycache table.\n";
	} else {
		echo "Adding querycache table for slow special pages... ";
		dbsource( "maintenance/archives/patch-querycache.sql", $wgDatabase );
		echo "ok\n";
	}
}

function do_objectcache_update() {
	global $wgDatabase;
	if( $wgDatabase->tableExists( "objectcache" ) ) {
		echo "...have objectcache table.\n";
	} else {
		echo "Adding objectcache table for message caching... ";
		dbsource( "maintenance/archives/patch-objectcache.sql", $wgDatabase );
		echo "ok\n";
	}
}

function do_categorylinks_update() {
	global $wgDatabase;
	if( $wgDatabase->tableExists( "categorylinks" ) ) {
		echo "...have categorylinks table.\n";
	} else {
		echo "Adding categorylinks table for category management... ";
		dbsource( "maintenance/archives/patch-categorylinks.sql", $wgDatabase );
		echo "ok\n";
	}
}

function do_image_name_unique_update() {
	global $wgDatabase;
	if ( $wgDatabase->indexUnique( 'image', 'img_name' ) ) {
		echo "...img_name already unique.\n";
	} else {
		echo "Making the img_name index unique... ";
		dbsource( "maintenance/archives/patch-image_name_unique.sql", $wgDatabase );
		echo "ok\n";
	}
}

function do_logging_update() {
	global $wgDatabase;
	if ( $wgDatabase->tableExists( 'logging' ) ) {
		echo "...logging table already exists.\n";
	} else {
		echo "Creating logging table and adjusting recentchanges... ";
		dbsource( "maintenance/archives/patch-logging.sql", $wgDatabase );
		echo "ok\n";
	}
}

function do_user_rights_update() {
	global $wgDatabase;
	if ( $wgDatabase->tableExists( 'user_rights' ) ) {
		echo "...user_rights table already exists.\n";
	} else {
		echo 'Creating user rights table...';
		dbsource( 'maintenance/archives/patch-user_rights.sql', $wgDatabase );
		echo "ok\n";
	}
}

?>
