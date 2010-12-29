<?php

class MediaWikiPHPUnitCommand extends PHPUnit_TextUI_Command {

	public function __construct() {
		$this->longOptions['verbose'] = 'verboseHandler';
	}
	
	public static function main( $exit = true ) {
        $command = new self;
        $command->run($_SERVER['argv'], $exit);
    }

	protected function verboseHandler($value) {
		global $additionalMWCLIArgs;
		$additionalMWCLIArgs['verbose'] = true;
	}

}
