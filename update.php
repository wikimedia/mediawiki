<?php

# Update already-installed software
#

include( "./install-utils.inc" );
install_version_checks();

if ( ! ( is_readable( "./LocalSettings.php" )
  && is_readable( "./AdminSettings.php" ) ) ) {
	print "A copy of your installation's LocalSettings.php\n" .
	  "and AdminSettings.php must exist in this source directory.\n";
	exit();
}

$IP = "./includes";
include_once( "./LocalSettings.php" );
include_once( "./AdminSettings.php" );

include( "$IP/Version.php" );

if ( $wgUseTeX && ( ! is_executable( "./math/texvc" ) ) ) {
	print "To use math functions, you must first compile texvc by\n" .
	  "running \"make\" in the math directory.\n";
	exit();
}

#
# Copy files into installation directories
#
do_update_files();

$wgDBuser			= $wgDBadminuser;
$wgDBpassword		= $wgDBadminpassword;

include_once( "{$IP}/Setup.php" );
include_once( "./maintenance/InitialiseMessages.inc" );

$wgTitle = Title::newFromText( "Update script" );

#
# Check the database for things that need to be fixed...
#
print "Checking database for necessary updates...\n";

$wgDatabase = Database::newFromParams( $wgDBserver, $wgDBadminuser, $wgDBadminpassword, $wgDBname, 
	1, false, true, false);
if ( !$wgDatabase->isOpen() ) {
	print "Unable to connect to database: " . $wgDatabase->lastError() . "\n";
	exit();
}

do_revision_updates();

do_ipblocks_update();
do_interwiki_update();
do_index_update();
do_linkscc_update();
do_hitcounter_update();
do_recentchanges_update();

initialiseMessages();

$wgDatabase->close();

print "Done.\n";
exit();

#
#
#

function do_update_files() {
	global $IP, $wgStyleSheetDirectory, $wgUploadDirectory, $wgLanguageCode, $wgDebugLogFile;
	print "Copying files... ";
	
	copyfile( ".", "index.php", $IP );
	copyfile( ".", "redirect.php", $IP );
	# compatibility with older versions, can be removed in a year or so
	# (written in Feb 2004)
	copyfile( ".", "wiki.phtml", $IP );
	copyfile( ".", "redirect.phtml", $IP );
	
	copydirectory( "./includes", $IP );
	copydirectory( "./stylesheets", $wgStyleSheetDirectory );
	
	copyfile( "./images", "wiki.png", $wgUploadDirectory );
	copyfile( "./images", "button_bold.gif", $wgUploadDirectory );
	copyfile( "./images", "button_extlink.gif", $wgUploadDirectory );
	copyfile( "./images", "button_headline.gif", $wgUploadDirectory );
	copyfile( "./images", "button_hr.gif", $wgUploadDirectory );
	copyfile( "./images", "button_image.gif", $wgUploadDirectory );
	copyfile( "./images", "button_italic.gif", $wgUploadDirectory );
	copyfile( "./images", "button_link.gif", $wgUploadDirectory );
	copyfile( "./images", "button_math.gif", $wgUploadDirectory );
	copyfile( "./images", "button_media.gif", $wgUploadDirectory );
	copyfile( "./images", "button_sig.gif", $wgUploadDirectory );
	copyfile( "./images", "button_template.gif", $wgUploadDirectory );

	copyfile( "./languages", "Language.php", $IP );
	copyfile( "./languages", "Language" . ucfirst( $wgLanguageCode ) . ".php", $IP );
	
	if( !empty( $wgDebugLogFile ) ) {
		$fp = fopen( $wgDebugLogFile, "w" );
		if ( false === $fp ) {
			print "Could not create log file \"{$wgDebugLogFile}\".\n";
			exit();
		}
		$d = date( "Y-m-d H:i:s" );
		fwrite( $fp, "Wiki debug log file created {$d}\n\n" );
		fclose( $fp );
	}
	
	if ( $wgUseTeX ) {
		copyfile( "./math", "texvc", "{$IP}/math", 0775 );
		copyfile( "./math", "texvc_test", "{$IP}/math", 0775 );
		copyfile( "./math", "texvc_tex", "{$IP}/math", 0775 );
	}
	
	copyfile( ".", "Version.php", $IP );

	print "ok\n";
}

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
