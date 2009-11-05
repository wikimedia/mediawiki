#!/usr/bin/env php
<?php

require 't/Test.php';

plan( 4 );

define( 'MEDIAWIKI', 1 );
require 'includes/Defines.php';
require 'includes/ProfilerStub.php';
require 'includes/AutoLoader.php';
require 'LocalSettings.php';

require 't/DatabaseMock.inc';

$wgSearchType = 'MockSearch';

require 'includes/Setup.php';


class MockSearch extends SearchEngine {
	public static $id;
	public static $title;
	public static $text;

	public function __construct( $db ) {
	}

	public function update( $id, $title, $text ) {
		self::$id = $id;
		self::$title = $title;
		self::$text = $text;
	}
}

function update( $text, $title = 'Test', $id = 1 ) {
	$u = new SearchUpdate( $id, $title, $text );
	$u->doUpdate();
	return array( MockSearch::$title, MockSearch::$text );
}

function updateText( $text ) {
	list( $title, $resultText ) = update( $text );
	$resultText = trim( $resultText ); // abstract from some implementation details
	return $resultText;
}

is( updateText( '<div>TeSt</div>' ), 'test', 'HTML stripped, text lowercased' );

is( updateText( <<<EOT
<table style="color:red; font-size:100px">
	<tr class="scary"><td><div>foo</div></td><tr>bar</td></tr>
	<tr><td>boz</td><tr>quux</td></tr>
</table>
EOT
), 'foo bar boz quux', 'Stripping HTML tables' );

is( updateText( 'a > b' ), 'a b', 'Handle unclosed tags' );

$text = str_pad( "foo <barbarbar \n", 10000, 'x' );
ok( updateText( $text ) != '', 'Bug 18609' );