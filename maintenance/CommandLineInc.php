<?php
/**
 * Backwards-compatibility wrapper for old-style maintenance scripts.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\Maintenance\Maintenance;

// NO_AUTOLOAD -- unsafe file-scope code

require_once __DIR__ . '/Maintenance.php';

global $optionsWithArgs, $optionsWithoutArgs, $allowUnregisteredOptions;

if ( !isset( $optionsWithArgs ) ) {
	$optionsWithArgs = [];
}
if ( !isset( $optionsWithoutArgs ) ) {
	$optionsWithoutArgs = [];
}
if ( !isset( $allowUnregisteredOptions ) ) {
	$allowUnregisteredOptions = false;
}

class CommandLineInc extends Maintenance {
	public function __construct() {
		global $optionsWithArgs, $optionsWithoutArgs, $allowUnregisteredOptions;

		parent::__construct();

		foreach ( $optionsWithArgs as $name ) {
			$this->addOption( $name, '', false, true );
		}
		foreach ( $optionsWithoutArgs as $name ) {
			$this->addOption( $name, '', false, false );
		}

		$this->setAllowUnregisteredOptions( $allowUnregisteredOptions );
	}

	/**
	 * No help, it would just be misleading since it misses custom options
	 * @param bool $force
	 */
	protected function maybeHelp( $force = false ) {
		if ( !$force ) {
			return;
		}
		parent::maybeHelp( true );
	}

	public function execute() {
		global $args, $options;

		$args = $this->parameters->getArgs();
		$options = $this->parameters->getOptions();
	}
}

// @codeCoverageIgnoreStart
$maintClass = CommandLineInc::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
