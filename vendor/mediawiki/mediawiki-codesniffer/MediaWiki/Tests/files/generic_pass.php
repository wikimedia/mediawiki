<?php

// Many of these code snippets are taken from:
// https://www.mediawiki.org/wiki/Manual:Coding_conventions

/**
 * @param $outputtype
 * @param null $ts
 * @return null
 */
function wfTimestampOrNull( $outputtype = TS_UNIX, $ts = null ) {
	if ( is_null( $ts ) ) {
		return null;
	} else {
		return wfTimestamp( $outputtype, $ts );
	}
}

$wgAutopromote = array(
	'autoconfirmed' => array( '&',
		array( APCOND_EDITCOUNT, &$wgAutoConfirmCount ),
		array( APCOND_AGE, &$wgAutoConfirmAge ),
	),
);

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Special',
	NS_MAIN             => '',
);

class FooBar extends BarBaz implements SomethingSomewhere {

	private $foo = 'halalalalalaa';

	public $var;

	public function iDoCaseStuff( $word ) {
		switch ( $word ) {
			case 'lorem':
			case 'ipsum':
				$bar = 2;
				break;
			case 'dolor':
				$bar = 3;
				break;
			default:
				$bar = 0;
		}
		return strtolower( $bar ) == 'on'
		|| strtolower( $bar ) == 'true'
		|| strtolower( $bar ) == 'yes'
		|| preg_match( "/^\s*[+-]?0*[1-9]/", $bar );
	}

	public function iDoCaseStuffTwo( $word ) {
		switch ( $word ) {
			case 'lorem':
			case 'ipsum':
				$bar = 2;
				break;
			case 'dolor':
				$bar = 3;
				break;
			default:
				$bar = 0;
		}
		return (bool)$bar;
	}

	public function fooBarBaz( $par ) {
		global $wgBarBarBar, $wgUser;

		if ( $par ) {
			return;
		}

		$wgBarBarBar->dobar(
			Xml::fieldset( wfMessage( 'importinterwiki' )->text() ) .
			Xml::openElement( 'form', array( 'method' => 'post', 'action' => $par,
				'id' => 'mw-import-interwiki-form' ) ) .
			wfMessage( 'import-interwiki-text' )->parse() .
			Xml::hidden( 'action', 'submit' ) .
			Xml::hidden( 'source', 'interwiki' ) .
			Xml::hidden( 'editToken', $wgUser->editToken() ),
			'secondArgument'
		);

		$foo = $par;
		return $foo + $wgBarBarBar + $this->foo;
	}

	private function someFunction( FooBar $baz ) {
		$foo = array(
			$baz,
			'memememememememee',
		);
		$cat = array_merge( $foo, array( 'barn', 'door' ) );
		return $cat;
	}
}

// This file has a new line at the end!
