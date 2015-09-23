<?php

if ( PHP_SAPI != 'cli' ) {
	die( "This script can only be run from the command line.\n" );
}

require_once __DIR__ . '/../includes/utils/AutoloadGenerator.php';

// Mediawiki installation directory
$base = dirname( __DIR__ );

$generator = new AutoloadGenerator( $base, 'local' );
foreach ( array( 'includes', 'languages', 'maintenance', 'mw-config' ) as $dir ) {
	$generator->readDir( $base . '/' . $dir );
}
foreach ( glob( $base . '/*.php' ) as $file ) {
	$generator->readFile( $file );
}

// This class is not defined, but might be added by the installer
$generator->forceClassPath( 'MyLocalSettingsGenerator', "$base/mw-config/overrides.php" );

// Write out the autoload
$generator->generateAutoload( 'maintenance/generateLocalAutoload.php' );

