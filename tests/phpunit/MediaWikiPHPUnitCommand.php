<?php

class MediaWikiPHPUnitCommand extends PHPUnit_TextUI_Command {
	
	static $additionalOptions = array( 
		'regex=' => false, 
		'record' => false,
		'file=' => false,
		'keep-uploads' => false,
	);
	
	//Fixme: These aren't shown on the --help menu

	public function __construct() {
		foreach( self::$additionalOptions as $option => $default ) {
			$this->longOptions[$option] = $option . 'Handler';
		}
		
	}
	
	public static function main( $exit = true ) {
        $command = new self;
        $command->run($_SERVER['argv'], $exit);
    }
    
    public function __call( $func, $args ) {
    	
		if( substr( $func, -7 ) == 'Handler' ) {
			if( is_null( $args[0] ) ) $args[0] = true; //Booleans
			self::$additionalOptions[substr( $func, 0, -7 ) ] = $args[0];
		}
	}
	
}
