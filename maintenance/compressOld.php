<?php
/**
 * Compress the old table, old_flags=gzip
 *
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** */

/**
 * Usage: 
 *
 * Non-wikimedia
 * php compressOld.php [-t <type>] [-c <chunk-size>] [-b <begin-date>] [-e <end-date>] [-s <start-id>]
 *
 * Wikimedia
 * php compressOld.php <database> [-t <type>] [-c <chunk-size>] [-b <begin-date>] [-e <end-date>] [-s <start-id>]
 *     [-f <max-factor>] [-h <factor-threshold>]
 *
 * <type> is either:
 *   gzip: compress revisions independently
 *   concat: concatenate revisions and compress in chunks (default)
 * 
 * <start-id> is the old_id to start from
 * 
 * The following options apply only to the concat type:
 *    <begin-date> is the earliest date to check for uncompressed revisions
 *    <end-date> is the latest revision date to compress
 *    <chunk-size> is the maximum number of revisions in a concat chunk
 *    <max-factor> is the maximum ratio of compressed chunk bytes to uncompressed avg. revision bytes
 *    <factor-threshold> is a minimum number of KB, where <max-factor> cuts in
 *
 */
 
$optionsWithArgs = array( 't', 'c', 's', 'f', 'h' );
require_once( "commandLine.inc" );
require_once( "compressOld.inc" );

if( !function_exists( "gzdeflate" ) ) {
	print "You must enable zlib support in PHP to compress old revisions!\n";
	print "Please see http://www.php.net/manual/en/ref.zlib.php\n\n";
	die();
}

$defaults = array( 
	't' => 'concat',
	'c' => 20,
	's' => 0,
	'f' => 3,
	'h' => 100,
	'b' => '',
	'e' => '',
);

$args = $args + $defaults;

if ( $args['t'] != 'concat' && $args['t'] != 'gzip' ) {
	print "Type \"{$args['t']}\" not supported\n";
}

print "Depending on the size of your database this may take a while!\n";
print "If you abort the script while it's running it shouldn't harm anything,\n";
print "but if you haven't backed up your data, you SHOULD abort now!\n\n";
print "Press control-c to abort first (will proceed automatically in 5 seconds)\n";
#sleep(5);

$success = true;
if ( $args['t'] == 'concat' ) {
	$success = compressWithConcat( $args['s'], $args['c'], $args['f'], $args['h'], $args['b'], $args['e'] );
} else {
	compressOldPages( $args['s'] );
} 

if ( $success ) {
	print "Done.\n";
}

exit();

?>
