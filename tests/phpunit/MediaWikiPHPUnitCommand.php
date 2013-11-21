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
		'debug-tests' => false,
	);

	public function __construct() {
		foreach ( self::$additionalOptions as $option => $default ) {
			$this->longOptions[$option] = $option . 'Handler';
		}
	}

	protected function handleArguments( array $argv ) {
		parent::handleArguments( $argv );

		if ( !isset( $this->arguments['listeners'] ) ) {
			$this->arguments['listeners'] = array();
		}

		foreach ( $this->options[0] as $option ) {
			switch ( $option[0] ) {
				case '--debug-tests':
					$this->arguments['listeners'][] = new MediaWikiPHPUnitTestListener( 'PHPUnitCommand' );
					break;
			}
		}
	}

	public static function main( $exit = true ) {
		$command = new self;

		if ( wfIsWindows() ) {
			# Windows does not come anymore with ANSI.SYS loaded by default
			# PHPUnit uses the suite.xml parameters to enable/disable colors
			# which can be then forced to be enabled with --colors.
			# The below code inject a parameter just like if the user called
			# phpunit with a --no-color option (which does not exist). It
			# overrides the suite.xml setting.
			# Probably fix bug 29226
			$command->arguments['colors'] = false;
		}

		# Makes MediaWiki PHPUnit directory includable so the PHPUnit will
		# be able to resolve relative files inclusion such as suites/*
		# PHPUnit uses stream_resolve_include_path() internally
		# See bug 32022
		set_include_path(
			__DIR__
				. PATH_SEPARATOR
				. get_include_path()
		);

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

Debugging options:
  --debug-tests            Log testing activity to the PHPUnitCommand log channel.

EOT;
	}
}
