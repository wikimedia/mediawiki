<?

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

	$rconn = mysql_connect( $wgDBserver, $wgDBadminuser, $wgDBadminpassword );
	mysql_select_db( $wgDBname );

	do_revision_updates();
	
	do_ipblocks_update();
	do_interwiki_update();
	do_index_update();
	do_linkscc_update();

	initialiseMessages();

	mysql_close( $rconn );

print "Done.\n";
exit();

#
#
#

function do_update_files() {
	global $IP, $wgStyleSheetDirectory, $wgUploadDirectory, $wgLanguageCode, $wgDebugLogFile;
	print "Copying files... ";
	
	copyfile( ".", "wiki.phtml", $IP );
	copyfile( ".", "redirect.phtml", $IP );
	copyfile( ".", "texvc.phtml", $IP );
	
	copydirectory( "./includes", $IP );
	copydirectory( "./stylesheets", $wgStyleSheetDirectory );
	
	copyfile( "./images", "wiki.png", $wgUploadDirectory );
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
	$fname = "Update script: update_passwords()";
	print "\nIt appears that you need to update the user passwords in your\n" .
	  "database. If you have already done this (if you've run this update\n" .
	  "script once before, for example), doing so again will make all your\n" .
	  "user accounts inaccessible, so be sure you only do this once.\n" .
	  "Update user passwords? (yes/no)";

	$resp = readconsole();
    if ( ! ( "Y" == $resp{0} || "y" == $resp{0} ) ) { return; }

	$sql = "SELECT user_id,user_password FROM user";
	$source = wfQuery( $sql, DB_READ, fname );

	while ( $row = mysql_fetch_object( $source ) ) {
		$id = $row->user_id;
		$oldpass = $row->user_password;
		$newpass = md5( "{$id}-{$oldpass}" );

		$sql = "UPDATE user SET user_password='{$newpass}' " .
		  "WHERE user_id={$id}";
		wfQuery( $sql, DB_WRITE, $fname );
	}
}

function do_ipblocks_update() {
	if ( wfFieldExists( "ipblocks", "ipb_id" ) ) {
		echo "...ipblocks table is up to date.\n";
	} else {
		echo "Updating ipblocks table... ";
		dbsource( "maintenance/archives/patch-ipblocks.sql" );
		echo "ok\n";
	}
}


function do_interwiki_update() {
	# Check that interwiki table exists; if it doesn't source it
	if( table_exists( "interwiki" ) ) {
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
	$meta = field_info( "recentchanges", "rc_timestamp" );
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
	global $rconn;
	if( table_exists( "linkscc" ) ) {
		echo "...have linkscc table.\n";
	} else {
		echo "Adding linkscc table... ";
		dbsource( "maintenance/archives/patch-linkscc.sql" );
		echo "ok\n";
	}
}


?>
