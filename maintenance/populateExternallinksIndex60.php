<?php
/**
 * Populates the el_index_60 field in the externallinks table.
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
 * Maintenance script that populates the el_index_60 field in the externallinks
 * table.
 *
 * @ingroup Maintenance
 * @since 1.29
 */
class PopulateExternallinksIndex60 extends LoggedUpdateMaintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription(
			'Populates the el_index_60 field in the externallinks table' );
		$this->setBatchSize( 200 );
	}

	protected function getUpdateKey() {
		return 'populate externallinks.el_index_60';
	}

	protected function updateSkippedMessage() {
		return 'externallinks.el_index_60 already populated.';
	}

	protected function doDBUpdates() {
		$db = $this->getDB( DB_MASTER );
		if ( !$db->tableExists( 'externallinks' ) ) {
			$this->error( "externallinks table does not exist" );
			return false;
		}
		$this->output( "Populating externallinks.el_index_60...\n" );

		$count = 0;
		$start = 0;
		$last = $db->selectField( 'externallinks', 'MAX(el_id)', false, __METHOD__ );
		while ( $start <= $last ) {
			$end = $start + $this->mBatchSize;
			$this->output( "el_id $start - $end of $last\n" );
			$res = $db->select( 'externallinks', [ 'el_id', 'el_index' ],
				[
					"el_id > $start",
					"el_id <= $end",
					'el_index_60' => '',
				],
				__METHOD__,
				[ 'ORDER BY' => 'el_id' ]
			);
			foreach ( $res as $row ) {
				$count++;
				$db->update( 'externallinks',
					[
						'el_index_60' => substr( $row->el_index, 0, 60 ),
					],
					[
						'el_id' => $row->el_id,
					], __METHOD__, [ 'IGNORE' ]
				);
			}
			wfWaitForSlaves();
			$start = $end;
		}
		$this->output( "Done, $count rows updated.\n" );

		return true;
	}
}

$maintClass = "PopulateExternallinksIndex60";
require_once RUN_MAINTENANCE_IF_MAIN;
