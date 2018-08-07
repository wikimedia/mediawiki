<?php
/**
 * Backwards-compatibility wrapper for old-style maintenance scripts.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

// phpcs:ignore MediaWiki.NamingConventions.ValidGlobalName.wgPrefix
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
		// phpcs:ignore MediaWiki.NamingConventions.ValidGlobalName.wgPrefix
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
		// phpcs:ignore MediaWiki.NamingConventions.ValidGlobalName.wgPrefix
		global $args, $options;

		$args = $this->mArgs;
		$options = $this->mOptions;
	}
}

$maintClass = CommandLineInc::class;
require RUN_MAINTENANCE_IF_MAIN;
