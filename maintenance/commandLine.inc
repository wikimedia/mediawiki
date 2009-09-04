<?php

/**
 * Backwards-compatibility wrapper for old-style maintenance scripts
 */
require( dirname(__FILE__) . '/Maintenance.php' );
$optionsWithArgs = array();

class CommandLineInc extends Maintenance {
	public function __construct() {
		global $optionsWithArgs;
		parent::__construct();
		foreach ( $optionsWithArgs as $name ) {
			$this->addOption( $name, '', false, true );
		}

		# No help, it would just be misleading since it misses custom options
		unset( $this->mParams['help'] );
	}

	public function execute() {
		global $args, $options;
		$args = $this->mArgs;
		$options = $this->mOptions;
	}
}

$maintClass = 'CommandLineInc';
require( DO_MAINTENANCE );

