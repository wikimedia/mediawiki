<?php
/**
 * @deprecated
 * @package MediaWiki
 * @subpackage MaintenanceArchive
 */

/** */

# Move "custom messages" from the MediaWiki namespace to the Template namespace
# Usage: php moveCustomMessages.php [<lang>] [phase]

# Script works in three phases:
#   1. Create redirects from Template to MediaWiki namespace. Skip if you don't want them
#   2. Move pages from MediaWiki to Template namespace.
#   3. Convert the text to suit the new syntax

chdir( ".." );
require_once( "liveCmdLine.inc" );
require_once( "moveCustomMessages.inc" );

$phase = 0;
if ( is_numeric( @$argv[3] ) && $argv[3] > 0) {
	$phase = intval($argv[3]);
}

moveCustomMessages( $phase );

?>
