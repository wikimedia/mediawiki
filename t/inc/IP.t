#!/usr/bin/env php
<?php

require 't/Test.php';

plan( 1120 );

require_ok( 'includes/IP.php' );

# some of this test data was taken from Data::Validate::IP

#
# isValid()
#

foreach ( range( 0, 255 ) as $i ) {
	$a = sprintf( "%03d", $i );
	$b = sprintf( "%02d", $i );
	$c = sprintf( "%01d", $i );
	foreach ( array_unique( array( $a, $b, $c ) ) as $f ) {
		$ip = "$f.$f.$f.$f";
		ok( IP::isValid( $ip ), "$ip is a valid IPv4 address" );
	}
}

# A bit excessive perhaps? meh..
foreach ( range( 256, 999 ) as $i ) {
	$a = sprintf( "%03d", $i );
	$b = sprintf( "%02d", $i );
	$c = sprintf( "%01d", $i );
	foreach ( array_unique( array( $a, $b, $c ) ) as $f ) {
		$ip = "$f.$f.$f.$f";
		ok( ! IP::isValid( $ip ), "$ip is not a valid IPv4 address" );
	}
}

$invalid = array(
	'www.xn--var-xla.net',
	'216.17.184.G',
	'216.17.184.1.',
	'216.17.184',
	'216.17.184.',
	'256.17.184.1'
);

foreach ( $invalid as $i ) {
	ok( ! IP::isValid( $i ), "$i is an invalid IPv4 address" );
}

#
# isPublic()
#

$private = array( '10.0.0.1', '172.16.0.1', '192.168.0.1' );

foreach ( $private as $p ) {
	ok( ! IP::isPublic( $p ), "$p is not a public IP address" ); 
}

/* vim: set filetype=php: */
