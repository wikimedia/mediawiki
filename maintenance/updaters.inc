<?php

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
		$res = $wgDatabase->query( "SELECT lcc_title FROM linkscc WHERE 0 LIMIT 1" );
		$flags = mysql_field_flags( $res, 0 );
		if( strpos( $flags, "binary" ) !== false ) {
			echo "...have linkscc table.\n";
			return;
		} else {
			echo "...linkscc table possibly corrupt, redefining... ";
			# fall through
		}
	}
	echo "Adding linkscc table... ";
	dbsource( "maintenance/archives/patch-linkscc.sql", $wgDatabase );
	echo "ok\n";
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
}

?>