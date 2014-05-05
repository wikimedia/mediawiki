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
