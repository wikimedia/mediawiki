<?php

die( "The command-line installer is not recommended, please read INSTALL.\n\n" );

# Update already-installed software
#

include( "./install-utils.inc" );
include_once( "./maintenance/updaters.inc" );
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

if( $wgSitename == "MediaWiki" ) {
	die( "You must set the site name in \$wgSitename before installation.\n\n" );
}

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
	
	copyfile( ".", "LocalSettings.php", $IP );
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

?>
