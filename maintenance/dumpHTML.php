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
 * --no-overwrite       skip existing HTML files
 * --checkpoint <file>  use a checkpoint file to allow restarting of interrupted dumps
 * --slice <n/m>        split the job into m segments and do the n'th one
 * --images             only do image description pages
 * --shared-desc        only do shared (commons) image description pages
 * --no-shared-desc     don't do shared image description pages
 * --categories         only do category pages
 * --redirects          only do redirects
 * --special            only do miscellaneous stuff
 * --force-copy         copy commons instead of symlink, needed for Wikimedia
 * --interlang          allow interlanguage links
 * --image-snapshot     copy all images used to the destination directory
 * --compress           generate compressed version of the html pages
 */


$optionsWithArgs = array( 's', 'd', 'e', 'k', 'checkpoint', 'slice' );

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

if ( $options['slice'] ) {
	$bits = explode( '/', $options['slice'] );
	if ( count( $bits ) != 2 || $bits[0] < 1 || $bits[0] > $bits[1] ) {
		print "Invalid slice specification";
		exit;
	}
	$sliceNumerator = $bits[0];
	$sliceDenominator = $bits[1];
} else {
	$sliceNumerator = $sliceDenominator = 1;
}

$wgHTMLDump = new DumpHTML( array(
	'dest' => $dest,
	'forceCopy' => $options['force-copy'],
	'alternateScriptPath' => $options['interlang'],
	'interwiki' => $options['interlang'],
	'skin' => $skin,
	'makeSnapshot' => $options['image-snapshot'],
	'checkpointFile' => $options['checkpoint'],
	'startID' => $start,
	'endID' => $end,
	'sliceNumerator' => $sliceNumerator,
	'sliceDenominator' => $sliceDenominator,
	'noOverwrite' => $options['no-overwrite'],
	'compress' => $options['compress'],
	'noSharedDesc' => $options['no-shared-desc'],
));


if ( $options['special'] ) {
	$wgHTMLDump->doSpecials();
} elseif ( $options['images'] ) {
	$wgHTMLDump->doImageDescriptions();
} elseif ( $options['categories'] ) {
	$wgHTMLDump->doCategories();
} elseif ( $options['redirects'] ) {
	$wgHTMLDump->doRedirects();
} elseif ( $options['shared-desc'] ) {
	$wgHTMLDump->doSharedImageDescriptions();
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
	#print_r($GLOBALS);
	# Workaround for bug #36957
	$globals = array_keys( $GLOBALS );
	#sort( $globals );
	$sizes = array();
	foreach ( $globals as $name ) {
		 $sizes[$name] = strlen( serialize( $GLOBALS[$name] ) );
	}
	arsort($sizes);
	$sizes = array_slice( $sizes, 0, 20 );
	foreach ( $sizes as $name => $size ) {
		printf( "%9d %s\n", $size, $name );
	}
}

if ( $profiling ) {
	echo $wgProfiler->getOutput();
}

?>
