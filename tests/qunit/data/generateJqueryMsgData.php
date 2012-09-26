<?php
/**
 * This PHP script defines the spec that the mediawiki.jqueryMsg module should conform to.
 *
 * It does this by looking up the results of various kinds of string parsing, with various
 * languages, in the current installation of MediaWiki. It then outputs a static specification,
 * mapping expected inputs to outputs, which can be used fed into a unit test framework.
 * (QUnit, Jasmine, anything, it just outputs an object with key/value pairs).
 *
 * This is similar to Michael Dale (mdale@mediawiki.org)'s parser tests, except that it doesn't
 * look up the API results while doing the test, so the test run is much faster (at the cost
 * of being out of date in rare circumstances. But mostly the parsing that we are doing in
 * Javascript doesn't change much).
 */

/*
 * @example QUnit
 * <code>
	QUnit.test( 'Output matches PHP parser', mw.libs.phpParserData.tests.length, function ( assert ) {
		mw.messages.set( mw.libs.phpParserData.messages );
		$.each( mw.libs.phpParserData.tests, function ( i, test ) {
			QUnit.stop();
			getMwLanguage( test.lang, function ( langClass ) {
				var parser = new mw.jqueryMsg.parser( { language: langClass } );
				assert.equal(
					parser.parse( test.key, test.args ).html(),
					test.result,
					test.name
				);
				QUnit.start();
			} );
		} );
	});
 * </code>
 *
 * @example Jasmine
 * <code>
	describe( 'match output to output from PHP parser', function () {
		mw.messages.set( mw.libs.phpParserData.messages );
		$.each( mw.libs.phpParserData.tests, function ( i, test ) {
			it( 'should parse ' + test.name, function () {
				var langClass;
				runs( function () {
					getMwLanguage( test.lang, function ( gotIt ) {
						langClass = gotIt;
					});
				});
				waitsFor( function () {
					return langClass !== undefined;
				}, 'Language class should be loaded', 1000 );
				runs( function () {
					console.log( test.lang, 'running tests' );
					var parser = new mw.jqueryMsg.parser( { language: langClass } );
					expect(
						parser.parse( test.key, test.args ).html()
					).toEqual( test.result );
				} );
			} );
		} );
	} );
 * </code>
 */

require __DIR__ . '/../../../maintenance/Maintenance.php';

class GenerateJqueryMsgData extends Maintenance {

	static $keyToTestArgs = array(
		'undelete_short' => array(
			array( 0 ),
			array( 1 ),
			array( 2 ),
			array( 5 ),
			array( 21 ),
			array( 101 )
		),
		'category-subcat-count' => array(
			array( 0, 10 ),
			array( 1, 1 ),
			array( 1, 2 ),
			array( 3, 30 )
		)
	);

	public function __construct() {
		parent::__construct();
		$this->mDescription = 'Create a specification for message parsing ini JSON format';
		// add any other options here
	}

	public function execute() {
		list( $messages, $tests ) = $this->getMessagesAndTests();
		$this->writeJavascriptFile( $messages, $tests, __DIR__ . '/mediawiki.jqueryMsg.data.js' );
	}

	private function getMessagesAndTests() {
		$messages = array();
		$tests = array();
		foreach ( array( 'en', 'fr', 'ar', 'jp', 'zh' ) as $languageCode ) {
			foreach ( self::$keyToTestArgs as $key => $testArgs ) {
				foreach ( $testArgs as $args ) {
					// Get the raw message, without any transformations.
					$template = wfMessage( $key )->inLanguage( $languageCode )->plain();

					// Get the magic-parsed version with args.
					$result = wfMessage( $key, $args )->inLanguage( $languageCode )->text();

					// Record the template, args, language, and expected result
					// fake multiple languages by flattening them together.
					$langKey = $languageCode . '_' . $key;
					$messages[$langKey] = $template;
					$tests[] = array(
						'name' => $languageCode . ' ' . $key . ' ' . join( ',', $args ),
						'key' => $langKey,
						'args' => $args,
						'result' => $result,
						'lang' => $languageCode
					);
				}
			}
		}
		return array( $messages, $tests );
	}

	private function writeJavascriptFile( $messages, $tests, $dataSpecFile ) {
		$phpParserData = array(
			'messages' => $messages,
			'tests' => $tests,
		);

		$output =
			"// This file stores the output from the PHP parser for various messages, arguments,\n"
				. "// languages, and parser modes. Intended for use by a unit test framework by looping\n"
				. "// through the object and comparing its parser return value with the 'result' property.\n"
				. '// Last generated with ' . basename( __FILE__ ) . ' at ' . gmdate( 'r' ) . "\n"
				// This file will contain unquoted JSON strings as javascript native object literals,
				// flip the quotemark convention for this file.
				. "/*jshint quotmark: double */\n"
				. "\n"
				. 'mediaWiki.libs.phpParserData = ' . FormatJson::encode( $phpParserData, true ) . ";\n";

		$fp = file_put_contents( $dataSpecFile, $output );
		if ( $fp === false ) {
			die( "Couldn't write to $dataSpecFile." );
		}
	}
}

$maintClass = "GenerateJqueryMsgData";
require_once RUN_MAINTENANCE_IF_MAIN;
