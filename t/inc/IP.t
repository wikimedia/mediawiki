#!/usr/bin/env php
<?php

require 'Test.php';

plan( 'no_plan' );

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

$valid = array( '216.17.184.1', '0.0.0.0', '000.000.000.000' );

foreach ( $valid as $v ) {
	ok( IP::isValid( $v ), "$v is a valid IPv4 address" );
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

#is   ('127.0.0.1',	is_loopback_ipv4('127.0.0.1'),		'is_loopback_ipv4 127.0.0.1');
#is   ('192.0.2.9',	is_testnet_ipv4('192.0.2.9'),		'is_testnet_ipv4 192.0.2.9');
#is   ('216.17.184.1',	is_public_ipv4('216.17.184.1'),		'is_public_ipv4 216.17.184.1');
#isnt ('192.168.0.1',	is_public_ipv4('192.168.0.1'),		'is_public_ipv4 192.168.0.1');
#
?>