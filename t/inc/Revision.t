#!/usr/bin/env php
<?php

define( 'MEDIAWIKI', true );
require 't/Test.php';

plan( 19 );

require_ok( 'includes/Defines.php' );
require_ok( 'includes/ProfilerStub.php' );
require_ok( 'includes/GlobalFunctions.php' );
require_ok( 'languages/Language.php' );
require_ok( 'includes/Revision.php' );

$wgContLang = Language::factory( 'en' );
$wgLegacyEncoding = false;
$wgCompressRevisions = false;
$wgInputEncoding = 'utf-8';
$wgOutputEncoding = 'utf-8';

$row = new stdClass;
$row->old_flags = '';
$row->old_text = 'This is a bunch of revision text.';
cmp_ok( Revision::getRevisionText( $row ), '==',
	'This is a bunch of revision text.', 'Get revision text' );

$row = new stdClass;
$row->old_flags = 'gzip';
$row->old_text = gzdeflate( 'This is a bunch of revision text.' );
cmp_ok( Revision::getRevisionText( $row ), '==',
	'This is a bunch of revision text.', 'Get revision text with gzip compression' );

$wgLegacyEncoding = 'iso-8859-1';

$row = new stdClass;
$row->old_flags = 'utf-8';
$row->old_text = "Wiki est l'\xc3\xa9cole superieur !";
cmp_ok( Revision::getRevisionText( $row ), '==',
	"Wiki est l'\xc3\xa9cole superieur !", 'Get revision text utf-8 native' );

$row = new stdClass;
$row->old_flags = '';
$row->old_text = "Wiki est l'\xe9cole superieur !";
cmp_ok( Revision::getRevisionText( $row ), '==',
	"Wiki est l'\xc3\xa9cole superieur !", 'Get revision text utf-8 legacy' );

$row = new stdClass;
$row->old_flags = 'gzip,utf-8';
$row->old_text = gzdeflate( "Wiki est l'\xc3\xa9cole superieur !" );
cmp_ok( Revision::getRevisionText( $row ), '==',
	"Wiki est l'\xc3\xa9cole superieur !", 'Get revision text utf-8 native and gzip' );

$row = new stdClass;
$row->old_flags = 'gzip';
$row->old_text = gzdeflate( "Wiki est l'\xe9cole superieur !" );
cmp_ok( Revision::getRevisionText( $row ), '==',
	"Wiki est l'\xc3\xa9cole superieur !", 'Get revision text utf-8 native and gzip' );

$row = new stdClass;
$row->old_text = "Wiki est l'\xc3\xa9cole superieur !";
$row->old_flags = Revision::compressRevisionText( $row->old_text );
like( $row->old_flags, '/utf-8/', "Flags should contain 'utf-8'" );
unlike( $row->old_flags, '/gzip/', "Flags should not contain 'gzip'" );
cmp_ok( $row->old_text, '==',
	"Wiki est l'\xc3\xa9cole superieur !", "Direct check" );
cmp_ok( Revision::getRevisionText( $row ), '==',
	"Wiki est l'\xc3\xa9cole superieur !", "getRevisionText" );
	
$wgCompressRevisions = true;

$row = new stdClass;
$row->old_text = "Wiki est l'\xc3\xa9cole superieur !";
$row->old_flags = Revision::compressRevisionText( $row->old_text );
like( $row->old_flags, '/utf-8/', "Flags should contain 'utf-8'" );
like( $row->old_flags, '/gzip/', "Flags should contain 'gzip'" );
cmp_ok( gzinflate( $row->old_text ), '==',
	"Wiki est l'\xc3\xa9cole superieur !", "Direct check" );
cmp_ok( Revision::getRevisionText( $row ), '==',
	"Wiki est l'\xc3\xa9cole superieur !", "getRevisionText" );
