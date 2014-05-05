<?php

class MediaWikiPHPUnitCommand extends PHPUnit_TextUI_Command {

	public static $additionalOptions = array(
		'regex=' => false,
		'file=' => false,
		'use-filebackend=' => false,
		'use-bagostuff=' => false,
		'use-jobqueue=' => false,
		'keep-uploads' => false,
		'use-normal-tables' => false,
		'reuse-db' => false,
		'wiki=' => false,
	);

	public function __construct() {
		foreach ( self::$additionalOptions as $option => $default ) {
			$this->longOptions[$option] = $option . 'Handler';
		}
	}

	public static function main( $exit = true ) {
		$command = new self;
		$command->run( $_SERVER['argv'], $exit );
	}

	public function __call( $func, $args ) {

		if ( substr( $func, -7 ) == 'Handler' ) {
			if ( is_null( $args[0] ) ) {
				$args[0] = true;
			} //Booleans
			self::$additionalOptions[substr( $func, 0, -7 )] = $args[0];
		}
	}

	public function run( array $argv, $exit = true ) {
		wfProfileIn( __METHOD__ );

		$ret = parent::run( $argv, false );

		wfProfileOut( __METHOD__ );

		// Return to real wiki db, so profiling data is preserved
		MediaWikiTestCase::teardownTestDB();

		// Log profiling data, e.g. in the database or UDP
		wfLogProfilingData();

		if ( $exit ) {
			exit( $ret );
		} else {
			return $ret;
		}
	}

	public function showHelp() {
		parent::showHelp();

		print <<<EOT

ParserTest-specific options:
  --regex="<regex>"        Only run parser tests that match the given regex
  --file="<filename>"      File describing parser tests
  --keep-uploads           Re-use the same upload directory for each test, don't delete it

Database options:
  --use-normal-tables      Use normal DB tables.
  --reuse-db               Init DB only if tables are missing and keep after finish.

EOT;
	}
}
