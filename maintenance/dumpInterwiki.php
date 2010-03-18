<?php
/**
 * Rebuild interwiki table using the file on meta and the language list
 * Wikimedia specific!
 *
 * @file
 * @todo document
 * @ingroup Maintenance
 * @ingroup Wikimedia
 */

/** */
$oldCwd = getcwd();

$optionsWithArgs = array( "o" );
require_once( dirname(__FILE__) . '/commandLine.inc' );
require( dirname(__FILE__)."/dumpInterwiki.inc" );
chdir( $oldCwd );

# Output
if ( isset( $options['o'] ) ) {
    # To database specified with -o
    $dbFile = CdbWriter::open( $options['o'] );
} 

getRebuildInterwikiDump();

