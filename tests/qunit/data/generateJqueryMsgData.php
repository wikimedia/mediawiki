<?php
/**
 * This PHP script defines the spec that the mediawiki.jqueryMsg module should conform to.
 *
 * It does this by looking up the results of various kinds of string parsing, with various
 * languages, in the current installation of MediaWiki. It then outputs a static specification,
 * mapping expected inputs to outputs, which is used then run by QUnit.
 */

require __DIR__ . '/../../../maintenance/Maintenance.php';

class GenerateJqueryMsgData extends Maintenance {

	private static $keyToTestArgs = [
		'undelete_short' => [
			[ 0 ],
			[ 1 ],
			[ 2 ],
			[ 5 ],
			[ 21 ],
			[ 101 ]
		],
		'category-subcat-count' => [
			[ 0, 10 ],
			[ 1, 1 ],
			[ 1, 2 ],
			[ 3, 30 ]
		]
	];

	private static $testLangs = [ 'en', 'fr', 'ar', 'jp', 'zh', 'nl', 'ml', 'hi' ];

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Create a specification for message parsing ini JSON format' );
		// add any other options here
	}

	public function execute() {
		$data = $this->getData();
		$this->writeJsonFile( $data, __DIR__ . '/mediawiki.jqueryMsg.data.json' );
	}

	private function getData() {
		$messages = [];
		$tests = [];
		$jsData = [];
		foreach ( self::$testLangs as $languageCode ) {
			$jsData[$languageCode] = ResourceLoaderLanguageDataModule::getData( $languageCode );
			foreach ( self::$keyToTestArgs as $key => $testArgs ) {
				foreach ( $testArgs as $args ) {
					// Get the raw message, without any transformations.
					$template = wfMessage( $key )->useDatabase( false )
						->inLanguage( $languageCode )->plain();

					// Get the magic-parsed version with args.
					$result = wfMessage( $key, ...$args )->useDatabase( false )
						->inLanguage( $languageCode )->text();

					// Record the template, args, language, and expected result
					// fake multiple languages by flattening them together.
					$langKey = $languageCode . '_' . $key;
					$messages[$langKey] = $template;
					$tests[] = [
						'name' => $languageCode . ' ' . $key . ' ' . implode( ',', $args ),
						'key' => $langKey,
						'args' => $args,
						'result' => $result,
						'lang' => $languageCode
					];
				}
			}
		}
		return [
			'messages' => $messages,
			'tests' => $tests,
			'jsData' => $jsData,
		];
	}

	private function writeJsonFile( array $data, $dataSpecFile ) {
		$phpParserData = [
			'@' => 'Last generated with ' . basename( __FILE__ ) . ' at ' . gmdate( 'r' ),
		] + $data;

		$output = FormatJson::encode( $phpParserData, true ) . "\n";
		$fp = file_put_contents( $dataSpecFile, $output );
		if ( $fp === false ) {
			die( "Couldn't write to $dataSpecFile." );
		}
	}
}

$maintClass = GenerateJqueryMsgData::class;
require_once RUN_MAINTENANCE_IF_MAIN;
