<?php
/**
 * Refresh the externallinks table el_index and el_index_60 from el_to
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
 * Maintenance script that refreshes the externallinks table el_index and
 * el_index_60 from el_to
 *
 * @ingroup Maintenance
 * @since 1.32
 */
class RefreshExternallinksIndex extends LoggedUpdateMaintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription(
			'Refresh the externallinks table el_index and el_index_60 from el_to' );
		$this->setBatchSize( 200 );
	}

	protected function getUpdateKey() {
		return 'refresh externallinks v' . LinkFilter::VERSION;
	}

	protected function updateSkippedMessage() {
		return 'externallinks table indexes up to date';
	}

	protected function doDBUpdates() {
		$db = $this->getDB( DB_MASTER );
		if ( !$db->tableExists( 'externallinks' ) ) {
			$this->error( "externallinks table does not exist" );
			return false;
		}
		$this->output( "Updating externallinks table index fields\n" );

		$count = 0;
		$start = 0;
		$last = $db->selectField( 'externallinks', 'MAX(el_id)', '', __METHOD__ );
		while ( $start <= $last ) {
			$end = $start + $this->mBatchSize;
			$this->output( "el_id $start - $end of $last\n" );
			$res = $db->select( 'externallinks', [ 'el_id', 'el_to', 'el_index' ],
				[
					"el_id > $start",
					"el_id <= $end",
				],
				__METHOD__,
				[ 'ORDER BY' => 'el_id' ]
			);
			foreach ( $res as $row ) {
				$newIndexes = LinkFilter::makeIndexes( $row->el_to );
				if ( !$newIndexes ) {
					$this->error( "No new indexes for \"{$row->el_to}\"\n" );
					continue;
				}
				if ( in_array( $row->el_index, $newIndexes, true ) ) {
					continue;
				}

				$count++;
				if ( count( $newIndexes ) === 1 ) {
					$newIndex = $newIndexes[0];
				} else {
					// Assume the scheme is the only difference and shouldn't change
					$newIndex = substr( $row->el_index, 0, strpos( $row->el_index, ':' ) ) .
						substr( $newIndexes[0], strpos( $newIndexes[0], ':' ) );
				}
				$db->update( 'externallinks',
					[
						'el_index' => $newIndex,
						'el_index_60' => substr( $newIndex, 0, 60 ),
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

$maintClass = "RefreshExternallinksIndex";
require_once RUN_MAINTENANCE_IF_MAIN;
