#!/usr/bin/env php
<?php

define( 'MEDIAWIKI', true );
require 't/Test.php';

require 'includes/Defines.php';
require 'includes/ProfilerStub.php';
require 'LocalSettings.php';
require 'includes/Setup.php';

/**
 * These tests should work regardless of $wgCapitalLinks
 */

$info = array(
	'name' => 'test',
	'directory' => '/testdir',
	'url' => '/testurl',
	'hashLevels' => 2,
	'transformVia404' => false,
);

plan( 35 );

$repo_hl0 = new LocalRepo( array( 'hashLevels' => 0 ) + $info );
$repo_hl2 = new LocalRepo( array( 'hashLevels' => 2 ) + $info );
$repo_lc = new LocalRepo( array( 'initialCapital' => false ) + $info );

$file_hl0 = $repo_hl0->newFile( 'test!' );
$file_hl2 = $repo_hl2->newFile( 'test!' );
$file_lc = $repo_lc->newFile( 'test!' );

is( $file_hl0->getHashPath(), '', 'Get hash path, hasLev 0' );
is( $file_hl2->getHashPath(), 'a/a2/', 'Get hash path, hasLev 2' );
is( $file_lc->getHashPath(), 'c/c4/', 'Get hash path, lc first' );

is( $file_hl0->getRel(), 'Test!', 'Get rel path, hasLev 0' );
is( $file_hl2->getRel(), 'a/a2/Test!', 'Get rel path, hasLev 2' );
is( $file_lc->getRel(), 'c/c4/test!', 'Get rel path, lc first' );

is( $file_hl0->getUrlRel(), 'Test%21', 'Get rel url, hasLev 0' );
is( $file_hl2->getUrlRel(), 'a/a2/Test%21', 'Get rel url, hasLev 2' );
is( $file_lc->getUrlRel(), 'c/c4/test%21', 'Get rel url, lc first' );

is( $file_hl0->getArchivePath(), '/testdir/archive', 'Get archive path, hasLev 0' );
is( $file_hl2->getArchivePath(), '/testdir/archive/a/a2', 'Get archive path, hasLev 2' );
is( $file_hl0->getArchivePath( '!' ), '/testdir/archive/!', 'Get archive path, hasLev 0' );
is( $file_hl2->getArchivePath( '!' ), '/testdir/archive/a/a2/!', 'Get archive path, hasLev 2' );

is( $file_hl0->getThumbPath(), '/testdir/thumb/Test!', 'Get thumb path, hasLev 0' );
is( $file_hl2->getThumbPath(), '/testdir/thumb/a/a2/Test!', 'Get thumb path, hasLev 2' );
is( $file_hl0->getThumbPath( 'x' ), '/testdir/thumb/Test!/x', 'Get thumb path, hasLev 0' );
is( $file_hl2->getThumbPath( 'x' ), '/testdir/thumb/a/a2/Test!/x', 'Get thumb path, hasLev 2' );

is( $file_hl0->getArchiveUrl(), '/testurl/archive', 'Get archive url, hasLev 0' );
is( $file_hl2->getArchiveUrl(), '/testurl/archive/a/a2', 'Get archive url, hasLev 2' );
is( $file_hl0->getArchiveUrl( '!' ), '/testurl/archive/%21', 'Get archive url, hasLev 0' );
is( $file_hl2->getArchiveUrl( '!' ), '/testurl/archive/a/a2/%21', 'Get archive url, hasLev 2' );

is( $file_hl0->getThumbUrl(), '/testurl/thumb/Test%21', 'Get thumb url, hasLev 0' );
is( $file_hl2->getThumbUrl(), '/testurl/thumb/a/a2/Test%21', 'Get thumb url, hasLev 2' );
is( $file_hl0->getThumbUrl( 'x' ), '/testurl/thumb/Test%21/x', 'Get thumb url, hasLev 0' );
is( $file_hl2->getThumbUrl( 'x' ), '/testurl/thumb/a/a2/Test%21/x', 'Get thumb url, hasLev 2' );

is( $file_hl0->getArchiveVirtualUrl(), 'mwrepo://test/public/archive', 'Get archive virtual url, hasLev 0' );
is( $file_hl2->getArchiveVirtualUrl(), 'mwrepo://test/public/archive/a/a2', 'Get archive virtual url, hasLev 2' );
is( $file_hl0->getArchiveVirtualUrl( '!' ), 'mwrepo://test/public/archive/%21', 'Get archive virtual url, hasLev 0' );
is( $file_hl2->getArchiveVirtualUrl( '!' ), 'mwrepo://test/public/archive/a/a2/%21', 'Get archive virtual url, hasLev 2' );

is( $file_hl0->getThumbVirtualUrl(), 'mwrepo://test/public/thumb/Test%21', 'Get thumb virtual url, hasLev 0' );
is( $file_hl2->getThumbVirtualUrl(), 'mwrepo://test/public/thumb/a/a2/Test%21', 'Get thumb virtual url, hasLev 2' );
is( $file_hl0->getThumbVirtualUrl( '!' ), 'mwrepo://test/public/thumb/Test%21/%21', 'Get thumb virtual url, hasLev 0' );
is( $file_hl2->getThumbVirtualUrl( '!' ), 'mwrepo://test/public/thumb/a/a2/Test%21/%21', 'Get thumb virtual url, hasLev 2' );

is( $file_hl0->getUrl(), '/testurl/Test%21', 'Get url, hasLev 0' );
is( $file_hl2->getUrl(), '/testurl/a/a2/Test%21', 'Get url, hasLev 2' );
