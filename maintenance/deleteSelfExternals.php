<?php
/**
 * Delete self-references to $wgServer from the externallinks table.
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

/**
 * Maintenance script that deletes self-references to $wgServer
 * from the externallinks table.
 *
 * @ingroup Maintenance
 */
class DeleteSelfExternals extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Delete self-references to $wgServer from externallinks' );
		$this->mBatchSize = 1000;
	}

	public function execute() {
		global $wgServer;
		$this->output( "Deleting self externals from $wgServer\n" );
		$db = $this->getDB( DB_MASTER );
		while ( 1 ) {
			wfWaitForSlaves();
			$this->commitTransaction( $db, __METHOD__ );
			$q = $db->limitResult( "DELETE /* deleteSelfExternals */ FROM externallinks WHERE el_to"
				. $db->buildLike( $wgServer . '/', $db->anyString() ), $this->mBatchSize );
			$this->output( "Deleting a batch\n" );
			$db->query( $q );
			if ( !$db->affectedRows() ) {
				return;
			}
		}
	}
}

$maintClass = "DeleteSelfExternals";
require_once RUN_MAINTENANCE_IF_MAIN;
