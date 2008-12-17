#!/usr/bin/env php
<?php

define( 'MEDIAWIKI', true );
require 't/Test.php';

require 'includes/Defines.php';
require 'StartProfiler.php';
require 'includes/AutoLoader.php';
require 'LocalSettings.php';
require 'includes/Setup.php';

plan( 9 );

$db = new Database( $wgDBserver, $wgDBuser, $wgDBpassword );

cmp_ok( $db->addQuotes( NULL ), '==',
	'NULL', 'Add quotes to NULL' );

cmp_ok( $db->addQuotes( 1234 ), '==',
	"'1234'", 'Add quotes to int' );

cmp_ok( $db->addQuotes( 1234.5678 ), '==',
	"'1234.5678'", 'Add quotes to float' );

cmp_ok( $db->addQuotes( 'string' ), '==',
	"'string'", 'Add quotes to string' );
	
cmp_ok( $db->addQuotes( "string's cause trouble" ), '==',
	"'string\'s cause trouble'", 'Add quotes to quoted string' );

$sql = $db->fillPrepared(
	'SELECT * FROM interwiki', array() );
cmp_ok( $sql, '==',
	'SELECT * FROM interwiki', 'FillPrepared empty' );

$sql = $db->fillPrepared(
	'SELECT * FROM cur WHERE cur_namespace=? AND cur_title=?',
	array( 4, "Snicker's_paradox" ) );
cmp_ok( $sql, '==',
	"SELECT * FROM cur WHERE cur_namespace='4' AND cur_title='Snicker\'s_paradox'", 'FillPrepared question' );

$sql = $db->fillPrepared(
	'SELECT user_id FROM ! WHERE user_name=?',
	array( '"user"', "Slash's Dot" ) );
cmp_ok( $sql, '==',
	"SELECT user_id FROM \"user\" WHERE user_name='Slash\'s Dot'", 'FillPrepared quoted' );

$sql = $db->fillPrepared(
	"SELECT * FROM cur WHERE cur_title='This_\\&_that,_WTF\\?\\!'",
	array( '"user"', "Slash's Dot" ) );
cmp_ok( $sql, '==',
	"SELECT * FROM cur WHERE cur_title='This_&_that,_WTF?!'", 'FillPrepared raw' );
