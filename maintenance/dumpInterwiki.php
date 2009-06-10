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

$optionsWithArgs = array( "o" );
require( "commandLine.inc" );
require( "dumpInterwiki.inc" );
chdir( $oldCwd );

# Output
if ( isset( $options['o'] ) ) {
    # To database specified with -o
    $dbFile = dba_open( $options['o'], "n", "cdb_make" );
} 

getRebuildInterwikiDump();

