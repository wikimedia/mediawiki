<?php
/**
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

require_once __DIR__ . '/../Maintenance.php';

/**
 * Manage foreign resources registered with ResourceLoader.
 *
 * @ingroup Maintenance
 * @since 1.32
 */
class ManageForeignResources extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( <<<TEXT
Manage foreign resources registered with ResourceLoader.

This helps developers to download, verify and update local copies of upstream
libraries registered as ResourceLoader modules. See also foreign-resources.yaml.

For sources that don't publish an integrity hash, omit "integrity" (or leave empty)
and run the "make-sri" action to compute the missing hashes.

This script runs in dry-run mode by default. Use --update to actually change,
remove, or add files to resources/lib/.
TEXT
		);
		$this->addArg( 'action', 'One of "update", "verify" or "make-sri"', true );
		$this->addArg( 'module', 'Name of a single module (Default: all)', false );
		$this->addOption( 'verbose', 'Be verbose', false, false, 'v' );
	}

	/**
	 * @return bool
	 * @throws Exception
	 */
	public function execute() {
		global $IP;
		$frm = new ForeignResourceManager(
			 __DIR__ . '/foreign-resources.yaml',
			 "{$IP}/resources/lib",
			function ( $text ) {
				$this->output( $text );
			},
			function ( $text ) {
				$this->error( $text );
			},
			function ( $text ) {
				if ( $this->hasOption( 'verbose' ) ) {
					$this->output( $text );
				}
			}
		);

		$action = $this->getArg( 0 );
		$module = $this->getArg( 1, 'all' );
		return $frm->run( $action, $module );
	}
}

$maintClass = ManageForeignResources::class;
require_once RUN_MAINTENANCE_IF_MAIN;
