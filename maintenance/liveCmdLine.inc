<?php
/**
 * This is a drop-in replacement for commandLine.inc, for use only on
 * the Wikimedia wikis.
 * Call your command-line script with the language name and site name,
 * e.g. php convertLinks.php aa wikipedia
 *
 * @todo document
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** */
if ( isset( $_SERVER ) && array_key_exists( 'REQUEST_METHOD', $_SERVER ) ) {
	print "This script must be run from the command line\n";
	exit();
}

$wgCommandLineMode = true;
$self = array_shift( $argv );
# Turn off output buffering if it's on
@ob_end_flush();

if ( isset( $argv[0] ) ) {
	$lang = array_shift( $argv );
} else {
	$lang = "aa";
}
if ( isset( $argv[0] ) ) {
	$site = array_shift( $argv );
} else {
	$site = "wikipedia";
}

# This is for the IRC scripts, which now run as the apache user
if ( $_ENV['USER'] != "apache" ) {
	$wgDBadminuser = "wikiadmin";
	$wgDBadminpassword = trim(`wikiadmin_pass`);
}

$sep = strchr( $include_path = ini_get( "include_path" ), ";" ) ? ";" : ":";
putenv( "wikilang=$lang");
$newpath = "/home/wikipedia/common/php-new$sep";

$DP = "../includes";
#ini_set( "include_path", "$newpath$sep$include_path" );
ini_set( "include_path", "/home/wikipedia/common/php-new:/home/wikipedia/common/php-new/includes" );

require_once( "/home/wikipedia/common/php-new/CommonSettings.php" );

$wgUsePHPTal = false;

define("MEDIAWIKI",true);
require_once( "Setup.php" );
require_once( "../install-utils.inc" );
$wgTitle = Title::newFromText( "Command line script" );
$wgCommandLineMode = true;
set_time_limit(0);
?>
