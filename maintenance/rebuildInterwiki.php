<?php
/**
 * Rebuild interwiki table using the file on meta and the language list
 * Wikimedia specific!
 * @todo document
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** */
$oldCwd = getcwd();

$optionsWithArgs = array( "o" );
include_once( "commandLine.inc" );
include_once( "rebuildInterwiki.inc" );

$sql = getRebuildInterwikiSQL();

# Output
if ( isset( $options['o'] ) ) {	
	# To file specified with -o
	chdir( $oldCwd );
	$file = fopen( $options['o'], "w" );
	fwrite( $file, $sql );
	fclose( $file );
} else {
	# To stdout
	print $sql;
}

?>
