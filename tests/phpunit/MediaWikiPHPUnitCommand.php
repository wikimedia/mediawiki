<?php

class MediaWikiPHPUnitCommand extends PHPUnit_TextUI_Command {
	static $additionalArgs = array( 'verbose' => false );

	public function __construct() {
		$this->longOptions['verbose'] = 'verboseHandler';
	}
	
	public static function main( $exit = true ) {
        $command = new self;
        $command->run($_SERVER['argv'], $exit);
    }

	protected function verboseHandler($value) {
		self::$additionalArgs['verbose'] = true;
	}

}
