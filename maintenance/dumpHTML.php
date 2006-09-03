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
 * -d <dest>            destination directory
 * -s <start>           start ID
 * -e <end>             end ID
 * -k <skin>            skin to use (defaults to htmldump)
 * --checkpoint <file>  use a checkpoint file to allow restarting of interrupted dumps
 * --images             only do image description pages
 * --categories         only do category pages
 * --redirects          only do redirects
 * --special            only do miscellaneous stuff
 * --force-copy         copy commons instead of symlink, needed for Wikimedia
 * --interlang          allow interlanguage links
 * --image-snapshot     copy all images used to the destination directory
 */


$optionsWithArgs = array( 's', 'd', 'e', 'k', 'checkpoint' );

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
	$dest = "$IP/static";
}

$skin = isset( $options['k'] ) ? $options['k'] : 'htmldump';

$wgHTMLDump = new DumpHTML( array(
	'dest' => $dest,
	'forceCopy' => $options['force-copy'],
	'alternateScriptPath' => $options['interlang'],
	'interwiki' => $options['interlang'],
	'skin' => $skin,
	'makeSnapshot' => $options['image-snapshot'],
	'checkpointFile' => $options['checkpoint'],
	'startID' => $start,
	'endID' => $end
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
	print "Creating static HTML dump in directory $dest. \n";
	$dbr =& wfGetDB( DB_SLAVE );
	$server = $dbr->getProperty( 'mServer' );
	print "Using database {$server}\n";

	if ( !isset( $options['e'] ) ) {
		$wgHTMLDump->doEverything();
	} else {
		$wgHTMLDump->doArticles();
	}
}

if ( isset( $options['debug'] ) ) {
	print_r($GLOBALS);
}

if ( $profiling ) {
	echo $wgProfiler->getOutput();
}

?>
