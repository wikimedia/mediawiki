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

require( dirname( __FILE__ ) . '/Maintenance.php' );

global $optionsWithArgs;
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
require( RUN_MAINTENANCE_IF_MAIN );

