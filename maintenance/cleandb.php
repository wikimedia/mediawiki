<?php
/**
 * Creating a new empty database; either this or the conversion
 * script from the old format needs to be run, but not both.
 *
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** */
$wgCommandLineMode = true;
require_once( "../LocalSettings.php" );

$sep = strchr( $include_path = ini_get( "include_path" ), ";" ) ? ";" : ":";
ini_set( "include_path", "$IP$sep$include_path" );

require_once( "Setup.php" );

$wgTitle = Title::newFromText( "Database creation script" );
require_once( "./buildTables.inc" );
set_time_limit(0);

#$wgDBname			= "wikidb";
#$wgDBuser			= "wikiadmin";
#$wgDBpassword		= "adminpass";

cleanDatabase();
initializeTables();

print "Done.\n";
exit();

?>
