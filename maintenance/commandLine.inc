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

// @codingStandardsIgnoreStart MediaWiki.NamingConventions.ValidGlobalName.wgPrefix
global $optionsWithArgs;
global $optionsWithoutArgs;
// @codingStandardsIgnoreEnd
if ( !isset( $optionsWithArgs ) ) {
	$optionsWithArgs = [];
}
if ( !isset( $optionsWithoutArgs ) ) {
	$optionsWithoutArgs = [];
}

class CommandLineInc extends Maintenance {
	public function __construct() {
		// @codingStandardsIgnoreStart MediaWiki.NamingConventions.ValidGlobalName.wgPrefix
		global $optionsWithArgs, $optionsWithoutArgs;
		// @codingStandardsIgnoreEnd
		parent::__construct();
		foreach ( $optionsWithArgs as $name ) {
			$this->addOption( $name, '', false, true );
		}
		foreach ( $optionsWithoutArgs as $name ) {
			$this->addOption( $name, '', false, false );
		}
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
		// @codingStandardsIgnoreStart MediaWiki.NamingConventions.ValidGlobalName.wgPrefix
		global $args, $options;
		// @codingStandardsIgnoreEnd
		$args = $this->mArgs;
		$options = $this->mOptions;
	}
}

$maintClass = 'CommandLineInc';
require RUN_MAINTENANCE_IF_MAIN;
