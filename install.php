<?

# Install software and create new empty database.
#

include( "./install-utils.inc" );
install_version_checks();

if ( ! ( is_readable( "./LocalSettings.php" )
  && is_readable( "./AdminSettings.php" ) ) ) {
	print "You must first create the files LocalSettings.php\n" .
	  "and AdminSettings.php based on the samples in the top\n" .
	  "source directory before running this install script.\n";
	exit();
}

$DP = "./includes";
include_once( "./LocalSettings.php" );
include_once( "./AdminSettings.php" );
include_once( "./maintenance/InitialiseMessages.inc" );

if ( $wgUseTeX && ( ! is_executable( "./math/texvc" ) ) ) {
	print "To use math functions, you must first compile texvc by\n" .
	  "running \"make\" in the math directory.\n";
	exit();
}
if ( is_file( "{$IP}/Version.php" ) ) {
	print "There appears to be an installation of the software\n" .
	  "already present on \"{$IP}\". You may want to run the update\n" .
	  "script instead. If you continue with this installation script,\n" .
	  "that software and all of its data will be overwritten.\n" .
	  "Are you sure you want to do this? (yes/no) ";

	$response = readconsole();
	if ( ! ( "Y" == $response{0} || "y" == $response{0} ) ) { exit(); }
}

#
# Make the necessary directories
#
$dirs = array( $IP, $wgUploadDirectory, $wgStyleSheetDirectory, $wgTmpDirectory );
foreach ( $dirs as $d ) { makedirectory( $d ); }

#
# Copy files into installation directories
#
print "Copying files...\n";

copyfile( ".", "LocalSettings.php", $IP );
copyfile( ".", "Version.php", $IP );
copyfile( ".", "wiki.phtml", $IP );
copyfile( ".", "redirect.phtml", $IP );
copyfile( ".", "texvc.phtml", $IP );

copydirectory( "./includes", $IP );
copydirectory( "./stylesheets", $wgStyleSheetDirectory );

copyfile( "./images", "wiki.png", $wgUploadDirectory );
copyfile( "./languages", "Language.php", $IP );
copyfile( "./languages", "Language" . ucfirst( $wgLanguageCode ) . ".php", $IP );

$fp = fopen( $wgDebugLogFile, "w" );
if ( false === $fp ) {
	print "Could not create log file \"{$wgDebugLogFile}\".\n";
	exit();
}
$d = date( "Y-m-d H:i:s" );
fwrite( $fp, "Wiki debug log file created {$d}\n\n" );
fclose( $fp );

if ( $wgUseTeX ) {
	makedirectory( "{$IP}/math" );
	makedirectory( $wgMathDirectory );
	copyfile( "./math", "texvc", "{$IP}/math", 0775 );
	copyfile( "./math", "texvc_test", "{$IP}/math", 0775 );
	copyfile( "./math", "texvc_tex", "{$IP}/math", 0775 );
}

copyfile( ".", "Version.php", $IP );

#
# Make and initialize database
#
print "\n* * *\nWarning! This script will completely erase the\n" .
  "existing database \"{$wgDBname}\" and all its contents.\n" .
  "Are you sure you want to do this? (yes/no) ";

$response = readconsole();
if ( ! ( "Y" == $response{0} || "y" == $response{0} ) ) { exit(); }

print "\nYou should have already created a root password for the database.\n" .
  "Enter the root password here: ";

$rootpw = readconsole();

$rconn = mysql_connect( $wgDBserver, "root", $rootpw );
if ( false === $rconn ) {
	print "Could not connect to database on \"{$wgDBserver}\" as root.\n";
	exit();
}

# Include rest of code to get things like internationalized messages.
#
include_once( "{$IP}/Setup.php" );
$wgTitle = Title::newFromText( "Installation script" );

# Now do the actual database creation
#
print "Creating database...\n";
dbsource( "./maintenance/database.sql", $rconn );

mysql_select_db( $wgDBname, $rconn );
dbsource( "./maintenance/tables.sql", $rconn );
dbsource( "./maintenance/users.sql", $rconn );
dbsource( "./maintenance/initialdata.sql", $rconn );
dbsource( "./maintenance/interwiki.sql", $rconn );

populatedata(); # Needs internationalized messages

print "Adding indexes...\n";
dbsource( "./maintenance/indexes.sql", $rconn );

print "Done.\nBrowse \"{$wgServer}{$wgScript}\" to test,\n" .
  "or \"run WikiSuite -b -o\" in test suite.\n";
exit();

#
# Functions used above:
#
function makedirectory( $d ) {
	global $wgInstallOwner, $wgInstallGroup;

	if ( is_dir( $d ) ) {
		print "Directory \"{$d}\" exists.\n";
	} else {
		if ( mkdir( $d, 0777 ) ) {
			if ( isset( $wgInstallOwner ) ) { chown( $d, $wgInstallOwner ); }
			if ( isset( $wgInstallGroup ) ) { chgrp( $d, $wgInstallGroup ); }
			print "Directory \"{$d}\" created.\n";
		} else {
			print "Could not create directory \"{$d}\".\n";
			exit();
		}
	}
}


function populatedata() {
	global $wgDBadminpassword;
	$fname = "Installation script: populatedata()";

	$sql = "DELETE FROM site_stats";
	wfQuery( $sql, DB_WRITE, $fname );

	$sql = "INSERT INTO site_stats (ss_row_id,ss_total_views," .
		"ss_total_edits,ss_good_articles) VALUES (1,0,0,0)";
	wfQuery( $sql, DB_WRITE, $fname );

	$sql = "DELETE FROM user";
	wfQuery( $sql, DB_WRITE, $fname );

	$u = User::newFromName( "WikiSysop" );
	if ( 0 == $u->idForName() ) {
		$u->addToDatabase();
		$u->setPassword( $wgDBadminpassword );
		$u->addRight( "sysop" );
		$u->saveSettings();
	}
	$u = User::newFromName( "WikiDeveloper" );
	if ( 0 == $u->idForName() ) {
		$u->addToDatabase();
		$u->setPassword( $wgDBadminpassword );
		$u->addRight( "sysop" );
		$u->addRight( "developer" );
		$u->saveSettings();
	}
	
	$wns = Namespace::getWikipedia();
	$ulp = addslashes( wfMsgNoDB( "uploadlogpage" ) );
	$dlp = addslashes( wfMsgNoDB( "dellogpage" ) );

	$sql = "DELETE FROM cur";
	wfQuery( $sql, DB_WRITE, $fname );

	$sql = "INSERT INTO cur (cur_namespace,cur_title,cur_text," .
	  "cur_restrictions) VALUES ({$wns},'{$ulp}','" .
	  wfStrencode( wfMsgNoDB( "uploadlogpagetext" ) ) . "','sysop')";
	wfQuery( $sql, DB_WRITE, $fname );

	$sql = "INSERT INTO cur (cur_namespace,cur_title,cur_text," .
	  "cur_restrictions) VALUES ({$wns},'{$dlp}','" .
	  wfStrencode( wfMsgNoDB( "dellogpagetext" ) ) . "','sysop')";
	wfQuery( $sql, DB_WRITE, $fname );
	
	$titleobj = Title::newFromText( wfMsgNoDB( "mainpage" ) );
	$title = $titleobj->getDBkey();
	$sql = "INSERT INTO cur (cur_namespace,cur_title,cur_text) " .
	  "VALUES (0,'$title','" .
	  wfStrencode( wfMsgNoDB( "mainpagetext" ) ) . "')";
	wfQuery( $sql, DB_WRITE, $fname );
	
	initialiseMessages();
}

?>
