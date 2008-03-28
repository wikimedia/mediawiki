#!/usr/bin/env php
<?php

require 't/Test.php';

plan( 2 + 255 );

require_ok( 'includes/Defines.php' );

# require_ok() doesn't work for these, find out why
define( 'MEDIAWIKI', 1 );
require 'LocalSettings.php';
require 'includes/DefaultSettings.php';

require_ok( 'includes/Title.php' );

#
# legalChars()
#

$titlechars = Title::legalChars();

foreach ( range( 1, 255 ) as $num ) {
	$chr = chr( $num );
	if ( strpos( "#[]{}<>|", $chr ) !== false || preg_match( "/[\\x00-\\x1f\\x7f]/", $chr ) ) {
		unlike( $chr, "/[$titlechars]/", "chr($num) = $chr is not a valid titlechar" );
	} else {
		like(   $chr, "/[$titlechars]/", "chr($num) = $chr is a valid titlechar" );
	}
}

/* vim: set filetype=php: */
