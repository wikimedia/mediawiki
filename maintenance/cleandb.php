<?

# Creating a new empty database; either this or the conversion
# script from the old format needs to be run, but not both.

global $IP;
include_once( "../LocalSettings.php" );
include_once( "$IP/Setup.php" );

$wgTitle = Title::newFromText( "Database creation script" );
include_once( "./buildTables.inc" );
set_time_limit(0);

#$wgDBname			= "wikidb";
#$wgDBuser			= "wikiadmin";
#$wgDBpassword		= "adminpass";

cleanDatabase();
initializeTables();

print "Done.\n";
exit();

?>
