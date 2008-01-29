<?php
/**
 * Rebuild interwiki table using the file on meta and the language list
 * Wikimedia specific!
 * @todo document
 * @addtogroup Maintenance
 */

/** */
$oldCwd = getcwd();

$optionsWithArgs = array( "d" );
include_once( "commandLine.inc" );
include_once( "rebuildInterwiki.inc" );
chdir( $oldCwd );

# Output
if ( isset( $options['d'] ) ) {
	$destDir = $options['d'];
} else {
	$destDir = '/home/wikipedia/conf/interwiki/sql';
}

echo "Making new interwiki SQL files in $destDir\n";
makeInterwikiSQL( $destDir );

