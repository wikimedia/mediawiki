<?php
/**
 * @todo document
 * @package MediaWiki
 * @subpackage Maintenance
 */

/**
 * Usage:
 * php dumpHTML.php [options...]
 *
 * -d <dest>      destination directory
 * -s <start>     start ID
 * -e <end>       end ID
 * -k <skin>	  skin to use (defaults to dumphtml)
 * --images       only do image description pages
 * --categories   only do category pages
 * --redirects    only do redirects
 * --special      only do miscellaneous stuff
 * --force-copy   copy commons instead of symlink, needed for Wikimedia
 * --interlang    allow interlanguage links
 */


$optionsWithArgs = array( 's', 'd', 'e', 'k' );

$profiling = false;

if ( $profiling ) {
	define( 'MW_CMDLINE_CALLBACK', 'wfSetupDump' );
	function wfSetupDump() {
		global $wgProfiling, $wgProfileToDatabase, $wgProfileSampleRate;
		$wgProfiling = true;
		$wgProfileToDatabase = false;
		$wgProfileSampleRate = 1;
	}
}

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

$skin = isset( $options['k'] ) ? $options['k'] : 'dumphtml';

$wgHTMLDump = new DumpHTML( array(
	'dest' => $dest,
	'forceCopy' => $options['force-copy'],
	'alternateScriptPath' => $options['interlang'],
	'interwiki' => $options['interlang'],
	'skin' => $skin,
));


if ( $options['special'] ) {
	$wgHTMLDump->doSpecials();
} elseif ( $options['images'] ) {
	$wgHTMLDump->doImageDescriptions();
} elseif ( $options['categories'] ) {
	$wgHTMLDump->doCategories();
} elseif ( $options['redirects'] ) {
	$wgHTMLDump->doRedirects();
} else {
	print("Creating static HTML dump in directory $dest. \n".
		"Starting from page_id $start of $end.\n");

	$dbr =& wfGetDB( DB_SLAVE );
	$server = $dbr->getProperty( 'mServer' );
	print "Using database {$server}\n";

	$wgHTMLDump->doArticles( $start, $end );
	if ( !isset( $options['e'] ) ) {
		$wgHTMLDump->doImageDescriptions();
		$wgHTMLDump->doCategories();
		$wgHTMLDump->doSpecials();
	}

	/*
	if ( $end - $start > CHUNK_SIZE * 2 ) {
		// Split the problem into smaller chunks, run them in different PHP instances
		// This is a memory/resource leak workaround
		print("Creating static HTML dump in directory $dest. \n".
			"Starting from page_id $start of $end.\n");

		chdir( "maintenance" );
		for ( $chunkStart = $start; $chunkStart < $end; $chunkStart += CHUNK_SIZE ) {
			$chunkEnd = $chunkStart + CHUNK_SIZE - 1;
			if ( $chunkEnd > $end ) {
				$chunkEnd = $end;
			}
			passthru( "php dumpHTML.php -d " . wfEscapeShellArg( $dest ) . " -s $chunkStart -e $chunkEnd" );
		}
		chdir( ".." );
		$d->doImageDescriptions();
		$d->doCategories();
		$d->doMainPage( $dest );
	} else {
		$d->doArticles( $start, $end );
	}
	*/
}

if ( isset( $options['debug'] ) ) {
	print_r($GLOBALS);
}

if ( $profiling ) {
	echo $wgProfiler->getOutput();
}

?>
