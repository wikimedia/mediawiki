<?php
/**
 * @todo document
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** */

$optionsWithArgs = array( 's', 'd', 'e' );

require_once( "commandLine.inc" );
require_once( "dumpHTML.inc" );

error_reporting( E_ALL & (~E_NOTICE) );
define( 'CHUNK_SIZE', 50 );

if ( !empty( $options['s'] ) ) {
	$start = $options['s'];
} else {
	$start = 1;
}

if ( !empty( $options['e'] ) ) {
	$end = $options['e'];
} else {
	$dbr =& wfGetDB( DB_SLAVE );
	$end = $dbr->selectField( 'page', 'max(page_id)', false );	
}

if ( !empty( $options['d'] ) ) {
	$dest = $options['d'];
} else {
	$dest = 'static';
}

$d = new DumpHTML( $dest, true, 3 );

if ( $options['special'] ) {
	$d->doSpecials();
} elseif ( $options['images'] ) {
	$d->doImageDescriptions();
} elseif ( $options['categories'] ) {
	$d->doCategories();
} else {
	if ( $end - $start > CHUNK_SIZE * 2 ) {
		// Split the problem into smaller chunks, run them in different PHP instances
		// This is a memory/resource leak workaround
		print("Creating static HTML dump. Starting from page_id $start of $end.\n");
		chdir( "maintenance" );
		for ( $chunkStart = $start; $chunkStart < $end; $chunkStart += CHUNK_SIZE ) {
			$chunkEnd = $chunkStart + CHUNK_SIZE - 1;
			if ( $chunkEnd > $end ) {
				$chunkEnd = $end;
			}
			passthru( "php dumpHTML.php -s $chunkStart -e $chunkEnd" );
		}
		chdir( ".." );
		$d->doImageDescriptions();
		$d->doCategories();
		$d->doMainPage( $dest );
	} else {
		$d->doArticles( $start, $end );
	}
}

exit();

?>
