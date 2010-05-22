<?php

/**
 * Backwards-compatibility wrapper for old-style maintenance scripts
 */
require( dirname( __FILE__ ) . '/Maintenance.php' );

if ( !isset( $optionsWithArgs ) ) {
    $optionsWithArgs = array();
}

class CommandLineInc extends Maintenance {
	public function __construct() {
		global $optionsWithArgs;
		parent::__construct();
		foreach ( $optionsWithArgs as $name ) {
			$this->addOption( $name, '', false, true );
		}
	}

	public function getDbType() {
		global $wgUseNormalUser;

		return ( isset( $wgUseNormalUser ) && $wgUseNormalUser ) ?
			Maintenance::DB_STD : Maintenance::DB_ADMIN;
	}

	/**
	 * No help, it would just be misleading since it misses custom options
	 */
	protected function maybeHelp( $force = false ) {
		if ( !$force )
			return;
		parent::maybeHelp( true );
	}

	public function execute() {
		global $args, $options;
		$args = $this->mArgs;
		$options = $this->mOptions;
	}
}

$maintClass = 'CommandLineInc';
require( DO_MAINTENANCE );

