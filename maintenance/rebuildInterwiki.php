<?php
/**
 * Rebuild interwiki table using the file on meta and the language list
 * Wikimedia specific!
 *
 * @file
 * @todo document
 * @ingroup Maintenance
 */

/** */
$oldCwd = getcwd();

$optionsWithArgs = array( "d" );
require( "commandLine.inc" );
require( "rebuildInterwiki.inc" );
chdir( $oldCwd );

# Output
if ( isset( $options['d'] ) ) {
	$destDir = $options['d'];
} else {
	$destDir = '/home/wikipedia/conf/interwiki/sql';
}

echo "Making new interwiki SQL files in $destDir\n";
makeInterwikiSQL( $destDir );

