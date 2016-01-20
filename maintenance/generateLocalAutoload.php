<?php

if ( PHP_SAPI != 'cli' ) {
	die( "This script can only be run from the command line.\n" );
}

require_once __DIR__ . '/../includes/utils/AutoloadGenerator.php';

// Mediawiki installation directory
$base = dirname( __DIR__ );

$generator = new AutoloadGenerator( $base, 'local' );
$generator->initMediaWikiDefault();

// Write out the autoload
$fileinfo = $generator->getTargetFileinfo();
file_put_contents(
	$fileinfo['filename'],
	$generator->getAutoload( 'maintenance/generateLocalAutoload.php' )
);
