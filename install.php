<?php
/**
 * The old installation engine.
 * Replaced by ./config/index.php which provide an online installer instead of
 * command line.
 * It will die with versions >= 1.3.x 
 * @deprecated
 */
 
die("obsolete; remove this file befor 1.3.0 release\n");

# Install software and create new empty database.
#

include_once( "./install-utils.inc" );
install_version_checks();

if ( ! ( is_readable( "./LocalSettings.php" )
  && is_readable( "./AdminSettings.php" ) ) ) {
	print "You must first create the files LocalSettings.php\n" .
	  "and AdminSettings.php based on the samples in the top\n" .
	  "source directory before running this install script.\n";
	exit();
}

$DP = "./includes";
require_once( "./LocalSettings.php" );
require_once( "./AdminSettings.php" );
require_once( "./maintenance/InitialiseMessages.inc" );

if( $wgSitename == "MediaWiki" ) {
	die( "You must set the site name in \$wgSitename before installation.\n\n" );
}

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
copyfile( ".", "index.php", $IP );
copyfile( ".", "redirect.php", $IP );

# compatibility with older versions, can be removed in a year or so
# (written in Feb 2004)
copyfile( ".", "wiki.phtml", $IP );
copyfile( ".", "redirect.phtml", $IP );

copydirectory( "./includes", $IP );
copydirectory( "./stylesheets", $wgStyleSheetDirectory );

copyfile( "./images", "wiki.png", $wgUploadDirectory );
copyfile( "./images", "button_bold.png", $wgUploadDirectory );
copyfile( "./images", "button_extlink.png", $wgUploadDirectory );
copyfile( "./images", "button_headline.png", $wgUploadDirectory );
copyfile( "./images", "button_hr.png", $wgUploadDirectory );
copyfile( "./images", "button_image.png", $wgUploadDirectory );
copyfile( "./images", "button_italic.png", $wgUploadDirectory );
copyfile( "./images", "button_link.png", $wgUploadDirectory );
copyfile( "./images", "button_math.png", $wgUploadDirectory );
copyfile( "./images", "button_media.png", $wgUploadDirectory );
copyfile( "./images", "button_nowiki.png", $wgUploadDirectory );
copyfile( "./images", "button_sig.png", $wgUploadDirectory );
copyfile( "./images", "button_template.png", $wgUploadDirectory );
copyfile( "./images", "magnify-clip.png", $wgUploadDirectory );
copyfile( "./images", "Arr_.png", $wgUploadDirectory );
copyfile( "./images", "Arr_r.png", $wgUploadDirectory );
copyfile( "./images", "Arr_d.png", $wgUploadDirectory );
copyfile( "./images", "Arr_l.png", $wgUploadDirectory );

copyfile( "./languages", "Language.php", $IP );
copyfile( "./languages", "LanguageUtf8.php", $IP );
copyfile( "./languages", "Language" . ucfirst( $wgLanguageCode ) . ".php", $IP );

if ( $wgDebugLogFile ) {
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
print "\n* * *\nWarning! This script will completely erase any\n" .
  "existing database \"{$wgDBname}\" and all its contents.\n" .
  "Are you sure you want to do this? (yes/no) ";

$response = readconsole();
if ( ! ( "Y" == $response{0} || "y" == $response{0} ) ) { exit(); }

print "Please enter your root password for the database server now.\n";
print "It is used to do the following:\n";
print "1) Create the database\n";
print "2) Create the users specified in AdminSettings.php and LocalSettings.php\n\n";
print "You do not need to create any user accounts yourself!\n\n";
print "MySQL root password (typing will be visible): ";
$rootpw=readconsole();

# Include rest of code to get things like internationalized messages.
#
$wgUseDatabaseMessages = false; # no DB yet

require_once( "{$IP}/Setup.php" );
$wgTitle = Title::newFromText( "Installation script" );

$wgDatabase = Database::newFromParams( $wgDBserver, "root", $rootpw, "", 1 );
if ( !$wgDatabase->isOpen() ) {
	print "Could not connect to database on \"{$wgDBserver}\" as root.\n";
	exit();
}

# Now do the actual database creation
#
print "Creating database...\n";
dbsource( "./maintenance/database.sql", $wgDatabase );

$wgDatabase->selectDB( $wgDBname );
dbsource( "./maintenance/tables.sql", $wgDatabase );
dbsource( "./maintenance/users.sql", $wgDatabase );
dbsource( "./maintenance/initialdata.sql", $wgDatabase );
dbsource( "./maintenance/interwiki.sql", $wgDatabase );

populatedata(); # Needs internationalized messages

print "Adding indexes...\n";
dbsource( "./maintenance/indexes.sql", $wgDatabase );

print "Done.\nBrowse \"{$wgServer}{$wgScript}\" to test.\n";
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
	global $wgDBadminpassword, $wgDatabase;
	$fname = "Installation script: populatedata()";

	$sql = "DELETE FROM site_stats";
	$wgDatabase->query( $sql, $fname );

	$sql = "INSERT INTO site_stats (ss_row_id,ss_total_views," .
		"ss_total_edits,ss_good_articles) VALUES (1,0,0,0)";
	$wgDatabase->query( $sql, $fname );

	$sql = "DELETE FROM user";
	$wgDatabase->query( $sql, $fname );

	print "Do you want to create a sysop account? A sysop can protect,\n";
	print "delete and undelete pages and ban users. Recomended. [Y/n] ";
	$response = readconsole();
	if(strtolower($response)!="n") {
		print "Enter the username [Sysop]: ";
		$sysop_user=readconsole();
		if(!$sysop_user) { $sysop_user="Sysop"; }
		while(!$sysop_password) {
			print "Enter the password: ";
			$sysop_password=readconsole();
		}
		$u = User::newFromName( $sysop_user );
		if ( 0 == $u->idForName() ) {
			$u->addToDatabase();
			$u->setPassword( $sysop_password );
			$u->addRight( "sysop" );
			$u->saveSettings();
		} else {
			print "Could not create user - already exists!\n";
		}
	}
	print "Do you want to create a sysop+developer account? A developer\n";
	print "can switch the database to read-only mode and run any type of\n";
	print "query through a web interface. [Y/n] ";
	$response=readconsole();
	if(strtolower($response)!="n") {
		print "Enter the username [Developer]: ";
		$developer_user=readconsole();
		if(!$developer_user) { $developer_user="Developer"; }
		while (!$developer_password) {
			print "Enter the password: ";
			$developer_password=readconsole();
		}		
		$u = User::newFromName( $developer_user );
		if ( 0 == $u->idForName() ) {
			$u->addToDatabase();
			$u->setPassword( $developer_password );
			$u->addRight( "sysop" );
			$u->addRight( "developer" );
			$u->saveSettings();
		} else {
			print "Could not create user - already exists!\n";
		}
	}
	
	$wns = Namespace::getWikipedia();
	$ulp = addslashes( wfMsgNoDB( "uploadlogpage" ) );
	$dlp = addslashes( wfMsgNoDB( "dellogpage" ) );

	$sql = "DELETE FROM cur";
	$wgDatabase->query( $sql, $fname );

	$now = wfTimestampNow();
	$won = wfInvertTimestamp( $now );
	
	$sql = "INSERT INTO cur (cur_namespace,cur_title,cur_text," .
	  "cur_restrictions,cur_timestamp,inverse_timestamp,cur_touched) VALUES ({$wns},'{$ulp}','" .
	  wfStrencode( wfMsg( "uploadlogpagetext" ) ) . "','sysop','$now','$won','$now')";
	$wgDatabase->query( $sql, $fname );

	$sql = "INSERT INTO cur (cur_namespace,cur_title,cur_text," .
	  "cur_restrictions,cur_timestamp,inverse_timestamp,cur_touched) VALUES ({$wns},'{$dlp}','" .
	  wfStrencode( wfMsg( "dellogpagetext" ) ) . "','sysop','$now','$won','$now')";
	$wgDatabase->query( $sql, $fname );
	
	$titleobj = Title::newFromText( wfMsgNoDB( "mainpage" ) );
	$title = $titleobj->getDBkey();
	$sql = "INSERT INTO cur (cur_namespace,cur_title,cur_text,cur_timestamp,inverse_timestamp,cur_touched) " .
	  "VALUES (0,'$title','" .
	  wfStrencode( wfMsg( "mainpagetext" ) ) . "','$now','$won','$now')";
	$wgDatabase->query( $sql, $fname );
	
	initialiseMessages();
}

?>
