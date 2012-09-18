<?php

/**
 * This PHP script defines the spec that the Javascript message parser should conform to.
 *
 * It does this by looking up the results of various string kinds of string parsing, with various languages,
 * in the current installation of MediaWiki. It then outputs a static specification, mapping expected inputs to outputs,
 * which can be used with the JasmineBDD framework. This specification can then be used by simply including it into
 * the SpecRunner.html file.
 *
 * This is similar to Michael Dale (mdale@mediawiki.org)'s parser tests, except that it doesn't look up the
 * API results while doing the test, so the Jasmine run is much faster(at the cost of being out of date in rare
 * circumstances. But mostly the parsing that we are doing in Javascript doesn't change much.)
 *
 */

$maintenanceDir = dirname( dirname( dirname( __DIR__ ) ) ) . '/maintenance';

require( "$maintenanceDir/Maintenance.php" );

class MakeLanguageSpec extends Maintenance {

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
                $this->mDescription = "Create a JasmineBDD-compatible specification for message parsing";
                // add any other options here
        }

	public function execute() {
		list( $messages, $tests ) = $this->getMessagesAndTests();
		$this->writeJavascriptFile( $messages, $tests, "spec/mediawiki.language.parser.spec.data.js" );
	}

	private function getMessagesAndTests() {
		$messages = array();
		$tests = array();
		foreach ( array( 'en', 'fr', 'ar', 'jp', 'zh' ) as $languageCode ) {
			foreach ( self::$keyToTestArgs as $key => $testArgs ) {
				foreach ($testArgs as $args) {
					// get the raw template, without any transformations
					$template = wfMessage( $key )->inLanguage( $languageCode )->plain();

					$result = wfMessage( $key, $args )->inLanguage( $languageCode )->text();

					// record the template, args, language, and expected result
					// fake multiple languages by flattening them together
					$langKey = $languageCode . '_' . $key;
					$messages[ $langKey ] = $template;
					$tests[] = array(
						'name' => $languageCode . " " . $key . " " . join( ",", $args ),
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
		global $argv;
		$arguments = count($argv) ? $argv : $_SERVER[ 'argv' ];

		$json = new Services_JSON;
		$json->pretty = true;
		$javascriptPrologue = "// This file stores the results from the PHP parser for certain messages and arguments,\n"
				      . "// so we can test the equivalent Javascript libraries.\n"
				      . '// Last generated with ' . join(' ', $arguments) . ' at ' . gmdate('c') . "\n\n";
		$javascriptMessages = "mediaWiki.messages.set( " . $json->encode( $messages, true ) . " );\n";
		$javascriptTests = 'var jasmineMsgSpec = ' . $json->encode( $tests, true ) . ";\n";

		$fp = fopen( $dataSpecFile, 'w' );
		if ( !$fp ) {
			die( "couldn't open $dataSpecFile for writing" );
		}
		$success = fwrite( $fp, $javascriptPrologue . $javascriptMessages . $javascriptTests );
		if ( !$success ) { 
			die( "couldn't write to $dataSpecFile" );
		}
		$success = fclose( $fp );
		if ( !$success ) {
			die( "couldn't close $dataSpecFile" );
		}
	}
}

$maintClass = "MakeLanguageSpec";
require_once( "$maintenanceDir/doMaintenance.php" );



