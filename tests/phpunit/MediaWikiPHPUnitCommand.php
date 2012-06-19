<?php

class MediaWikiPHPUnitCommand extends PHPUnit_TextUI_Command {

	static $additionalOptions = array(
		'regex=' => false,
		'file=' => false,
		'use-filebackend=' => false,
		'keep-uploads' => false,
		'use-normal-tables' => false,
		'reuse-db' => false,
	);

	public function __construct() {
		foreach( self::$additionalOptions as $option => $default ) {
			$this->longOptions[$option] = $option . 'Handler';
		}

	}

	public static function main( $exit = true ) {
		$command = new self;

		if( wfIsWindows() ) {
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
			dirname( __FILE__ )
			.PATH_SEPARATOR
			. get_include_path()
		);

		$command->run($_SERVER['argv'], $exit);
	}

	public function __call( $func, $args ) {

		if( substr( $func, -7 ) == 'Handler' ) {
			if( is_null( $args[0] ) ) $args[0] = true; //Booleans
			self::$additionalOptions[substr( $func, 0, -7 ) ] = $args[0];
		}
	}

	public function showHelp() {
		parent::showHelp();

		print <<<EOT

ParserTest-specific options:

  --regex="<regex>"        Only run parser tests that match the given regex
  --file="<filename>"      Prints the version and exits.
  --keep-uploads           Re-use the same upload directory for each test, don't delete it


Database options:
  --use-normal-tables      Use normal DB tables.
  --reuse-db               Init DB only if tables are missing and keep after finish.


EOT;
	}

}
