<?php
/**
 * This script was used to convert the live Wikimedia wikis from 1.2 to 1.3
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** */
$maintenance = "/home/wikipedia/common/php-new/maintenance";
require_once( "$maintenance/liveCmdLine.inc" );
require_once( "$maintenance/InitialiseMessages.inc" );
require_once( "$maintenance/updaters.inc" );
require_once( "$maintenance/archives/moveCustomMessages.inc" );
require_once( "$maintenance/convertLinks.inc" );
require_once( "$maintenance/../install-utils.inc" );

$wgDatabase = Database::newFromParams( $wgDBserver, $wgDBadminuser, $wgDBadminpassword, $wgDBname );
do_ipblocks_update(); flush();
do_interwiki_update(); flush();
do_index_update(); flush();
do_linkscc_update(); flush();
do_linkscc_1_3_update(); flush();
do_hitcounter_update(); flush();
do_recentchanges_update(); flush();
do_user_real_name_update(); flush();
do_querycache_update(); flush();
do_objectcache_update(); flush();
do_categorylinks_update(); flush();
initialiseMessages(); flush();
moveCustomMessages( 1 );

if ( file_exists( $wgReadOnlyFile ) ) {
	$alreadyExists = true;
} else {
	$file = fopen( $wgReadOnlyFile, "w" );
	fwrite( $file, "The database is temporarily locked for a software upgrade\n" );
	fclose( $file );
	$alreadyExists = false;
}

convertLinks();

if ( !$alreadyExists ) {
	unlink( $wgReadOnlyFile );
}

?>
