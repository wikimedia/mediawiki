<?php
/**
 * Print serialized output of MediaWiki config vars
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
 * @author Tim Starling
 * @author Antoine Musso
 */

require_once( __DIR__ . '/Maintenance.php' );

/**
 * Print serialized output of MediaWiki config vars
 *
 * @ingroup Maintenance
 */
class GetConfiguration extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Get serialized MediaWiki site configuration";
		$this->addOption( 'settings', 'Space-separated list of wg* variables', true, true );
		$this->addOption( 'format', 'PHP or JSON', true, true );
		$this->addOption( 'wiki', 'Wiki ID', true, true );
	}

	public function execute() {
		$res = array();
		foreach ( explode( ' ', $this->getOption( 'settings' ) ) as $name ) {
			if ( !preg_match( '/^wg[A-Z]/', $name ) ) {
				throw new MWException( "Variable '$name' does start with 'wg'." );
			} elseif ( !isset( $GLOBALS[$name] ) ) {
				throw new MWException( "Variable '$name' is not set." );
			} elseif ( !$this->isAllowedVariable( $GLOBALS[$name] ) ) {
				throw new MWException( "Variable '$name' includes non-array, non-scalar, items." );
			}
			$res[$name] = $GLOBALS[$name];
		}

		$out = null;
		switch( $this->getOption( 'format' ) ) {
			case 'PHP':
				$out = serialize( $res );
				break;
			case 'JSON':
				$out = FormatJson::encode( $res );
				break;
			default:
				throw new MWException( "Invalid serialization format given." );
		}
		if ( !is_string( $out ) ) {
			throw new MWException( "Failed to serialize the requested settings." );
		}

		$this->output( $out . "\n" );
	}

	private function isAllowedVariable( $value ) {
		if ( is_array( $value ) ) {
			foreach ( $value as $k => $v ) {
				if ( !$this->isAllowedVariable( $v ) ) {
					return false;
				}
			}
			return true;
		} elseif ( is_scalar( $value ) ) {
			return true;
		}
		return false;
	}
}

$maintClass = "GetConfiguration";
require_once( RUN_MAINTENANCE_IF_MAIN );
