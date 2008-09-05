#!/usr/bin/env php
<?php

define( 'MEDIAWIKI', true );
require 't/Test.php';

require 'includes/Defines.php';
require 'LocalSettings.php';

plan( 48 );

require_ok( 'includes/ProfilerStub.php' );
require_ok( 'includes/GlobalFunctions.php' );

$wgReadOnly = null;
$wgReadOnlyFile = tempnam(wfTempDir(), "mwtest_readonly");
unlink( $wgReadOnlyFile );

isnt( wfRandom(), wfRandom(), "Two differents random" );

is( wfUrlencode( "\xE7\x89\xB9\xE5\x88\xA5:Contributions/Foobar" ),
	"%E7%89%B9%E5%88%A5:Contributions/Foobar", 'Urlencode' );

is( wfReadOnly(), false, 'Empty read only' );

is( wfReadOnly(), false, 'Empty read only, second time' );

$f = fopen( $wgReadOnlyFile, "wt" );
fwrite( $f, 'Message' );
fclose( $f );
$wgReadOnly = null;

is( wfReadOnly(), true, 'Read only file set' );

is( wfReadOnly(), true, 'Read only file set, second time' );

unlink( $wgReadOnlyFile );
$wgReadOnly = null;

is( wfReadOnly(), false, 'Read only reset' );

is( wfReadOnly(), false, 'Read only reset, second time' );


is( wfQuotedPrintable( "\xc4\x88u legebla?", "UTF-8" ), 
	"=?UTF-8?Q?=C4=88u=20legebla=3F?=", 'Quoted printable' );

$start = wfTime();
is( gettype( $start ), 'float', 'Time (type)' );
$end = wfTime();
cmp_ok( $end, '>', $start, 'Time (compare)' );

$arr = wfArrayToCGI(
	array( 'baz' => 'AT&T', 'ignore' => '' ),
	array( 'foo' => 'bar', 'baz' => 'overridden value' ) );
is( $arr, "baz=AT%26T&foo=bar", 'Array to CGI' );

$mime = mimeTypeMatch( 'text/html', array(
	'application/xhtml+xml' => 1.0,
	'text/html'  => 0.7,
	'text/plain' => 0.3
) );
is( $mime, 'text/html', 'Mime (1)' );

$mime = mimeTypeMatch( 'text/html', array(
	'image/*' => 1.0,
	'text/*'  => 0.5
) );
is( $mime, 'text/*', 'Mime (2)' );

$mime = mimeTypeMatch( 'text/html', array( '*/*' => 1.0 ) );
is( $mime, '*/*', 'Mime (3)' );

$mime = mimeTypeMatch( 'text/html', array(
	'image/png'     => 1.0,
	'image/svg+xml' => 0.5
) );
is( $mime, null, 'Mime (4)' );

$mime = wfNegotiateType(
	array( 'application/xhtml+xml' => 1.0,
	       'text/html'             => 0.7,
	       'text/plain'            => 0.5,
	       'text/*'                => 0.2 ),
	array( 'text/html'             => 1.0 ) );
is( $mime, 'text/html', 'Negotiate Mime (1)' );

$mime = wfNegotiateType(
	array( 'application/xhtml+xml' => 1.0,
	       'text/html'             => 0.7,
	       'text/plain'            => 0.5,
	       'text/*'                => 0.2 ),
	array( 'application/xhtml+xml' => 1.0,
	       'text/html'             => 0.5 ) );
is( $mime, 'application/xhtml+xml', 'Negotiate Mime (2)' );

$mime = wfNegotiateType(
	array( 'text/html'             => 1.0,
	       'text/plain'            => 0.5,
	       'text/*'                => 0.5,
	       'application/xhtml+xml' => 0.2 ),
	array( 'application/xhtml+xml' => 1.0,
	       'text/html'             => 0.5 ) );
is( $mime, 'text/html', 'Negotiate Mime (3)' );

$mime = wfNegotiateType(
	array( 'text/*'                => 1.0,
	       'image/*'               => 0.7,
	       '*/*'                   => 0.3 ),
	array( 'application/xhtml+xml' => 1.0,
	       'text/html'             => 0.5 ) );
is( $mime, 'text/html', 'Negotiate Mime (4)' );

$mime = wfNegotiateType(
	array( 'text/*'                => 1.0 ),
	array( 'application/xhtml+xml' => 1.0 ) );
is( $mime, null, 'Negotiate Mime (5)' );

$t = gmmktime( 12, 34, 56, 1, 15, 2001 );
is( wfTimestamp( TS_MW, $t ), '20010115123456', 'TS_UNIX to TS_MW' );
is( wfTimestamp( TS_UNIX, $t ), 979562096, 'TS_UNIX to TS_UNIX' );
is( wfTimestamp( TS_DB, $t ), '2001-01-15 12:34:56', 'TS_UNIX to TS_DB' );
$t = '20010115123456';
is( wfTimestamp( TS_MW, $t ), '20010115123456', 'TS_MW to TS_MW' );
is( wfTimestamp( TS_UNIX, $t ), 979562096, 'TS_MW to TS_UNIX' );
is( wfTimestamp( TS_DB, $t ), '2001-01-15 12:34:56', 'TS_MW to TS_DB' );
$t = '2001-01-15 12:34:56';
is( wfTimestamp( TS_MW, $t ), '20010115123456', 'TS_DB to TS_MW' );
is( wfTimestamp( TS_UNIX, $t ), 979562096, 'TS_DB to TS_UNIX' );
is( wfTimestamp( TS_DB, $t ), '2001-01-15 12:34:56', 'TS_DB to TS_DB' );

$sets = array(
	'' => '',
	'/' => '',
	'\\' => '',
	'//' => '',
	'\\\\' => '',
	'a' => 'a',
	'aaaa' => 'aaaa',
	'/a' => 'a',
	'\\a' => 'a',
	'/aaaa' => 'aaaa',
	'\\aaaa' => 'aaaa',
	'/aaaa/' => 'aaaa',
	'\\aaaa\\' => 'aaaa',
	'\\aaaa\\' => 'aaaa',
	'/mnt/upload3/wikipedia/en/thumb/8/8b/Zork_Grand_Inquisitor_box_cover.jpg/93px-Zork_Grand_Inquisitor_box_cover.jpg' => '93px-Zork_Grand_Inquisitor_box_cover.jpg',
	'C:\\Progra~1\\Wikime~1\\Wikipe~1\\VIEWER.EXE' => 'VIEWER.EXE',
	'Östergötland_coat_of_arms.png' => 'Östergötland_coat_of_arms.png',
);
foreach( $sets as $from => $to ) {
	is( $to, wfBaseName( $from ),
		"wfBaseName('$from') => '$to'");
}