<?php
/**
 * Optional upgrade script to populate *_from_namespace fields
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
 * Maintenance script to populate *_from_namespace fields
 *
 * @ingroup Maintenance
 */
class PopulateBacklinkNamespace extends LoggedUpdateMaintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Populate the *_from_namespace fields' );
		$this->addOption( 'lastUpdatedId', "Highest page_id with updated links", false, true );
	}

	protected function getUpdateKey() {
		return 'populate *_from_namespace';
	}

	protected function updateSkippedMessage() {
		return '*_from_namespace column of backlink tables already populated.';
	}

	public function doDBUpdates() {
		$force = $this->getOption( 'force' );

		$db = $this->getDB( DB_MASTER );

		$this->output( "Updating *_from_namespace fields in links tables.\n" );

		$start = $this->getOption( 'lastUpdatedId' );
		if ( !$start ) {
			$start = $db->selectField( 'page', 'MIN(page_id)', '', __METHOD__ );
		}
		if ( !$start ) {
			$this->output( "Nothing to do." );
			return false;
		}
		$end = $db->selectField( 'page', 'MAX(page_id)', '', __METHOD__ );
		$batchSize = $this->getBatchSize();

		# Do remaining chunk
		$end += $batchSize - 1;
		$blockStart = $start;
		$blockEnd = $start + $batchSize - 1;
		while ( $blockEnd <= $end ) {
			$this->output( "...doing page_id from $blockStart to $blockEnd\n" );
			$cond = "page_id BETWEEN " . (int)$blockStart . " AND " . (int)$blockEnd;
			$res = $db->select( 'page', [ 'page_id', 'page_namespace' ], $cond, __METHOD__ );
			foreach ( $res as $row ) {
				$db->update( 'pagelinks',
					[ 'pl_from_namespace' => $row->page_namespace ],
					[ 'pl_from' => $row->page_id ],
					__METHOD__
				);
				$db->update( 'templatelinks',
					[ 'tl_from_namespace' => $row->page_namespace ],
					[ 'tl_from' => $row->page_id ],
					__METHOD__
				);
				$db->update( 'imagelinks',
					[ 'il_from_namespace' => $row->page_namespace ],
					[ 'il_from' => $row->page_id ],
					__METHOD__
				);
			}
			$blockStart += $batchSize - 1;
			$blockEnd += $batchSize - 1;
			wfWaitForSlaves();
		}
		return true;
	}
}

$maintClass = PopulateBacklinkNamespace::class;
require_once RUN_MAINTENANCE_IF_MAIN;
