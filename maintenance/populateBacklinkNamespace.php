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
		$this->mDescription = "Populate the *_from_namespace fields";
		$this->addOption( 'lastUpdatedId', "Highest page_id with updated links", false, true );
		$this->addOption( 'tables', 'Comma separated list of tables to populate', false, true );
	}

	protected function getUpdateKey() {
		return 'populate *_from_namespace2';
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
			$start = $db->selectField( 'page', 'MIN(page_id)', false, __METHOD__ );
		}
		if ( !$start ) {
			$this->output( "Nothing to do." );
			return false;
		}
		$end = $db->selectField( 'page', 'MAX(page_id)', false, __METHOD__ );

		$tables = $this->getOption( 'tables' );
		if ( $tables ) {
			$tables = explode( ',', $tables );
		} else {
			$tables = array( 'pagelinks', 'templatelinks', 'imagelinks', 'externallinks' );
		}
		$tables = array_flip( $tables );

		# Do remaining chunk
		$end += $this->mBatchSize - 1;
		$blockStart = $start;
		$blockEnd = $start + $this->mBatchSize - 1;
		while ( $blockEnd <= $end ) {
			$this->output( "...doing page_id from $blockStart to $blockEnd\n" );
			$cond = "page_id BETWEEN $blockStart AND $blockEnd";
			$res = $db->select( 'page', array( 'page_id', 'page_namespace' ), $cond, __METHOD__ );
			foreach ( $res as $row ) {
				if ( isset( $tables['pagelinks'] ) ) {
					$db->update( 'pagelinks',
						array( 'pl_from_namespace' => $row->page_namespace ),
						array( 'pl_from' => $row->page_id ),
						__METHOD__
					);
				}
				if ( isset( $tables['templatelinks'] ) ) {
					$db->update( 'templatelinks',
						array( 'tl_from_namespace' => $row->page_namespace ),
						array( 'tl_from' => $row->page_id ),
						__METHOD__
					);
				}
				if ( isset( $tables['imagelinks'] ) ) {
					$db->update( 'imagelinks',
						array( 'il_from_namespace' => $row->page_namespace ),
						array( 'il_from' => $row->page_id ),
						__METHOD__
					);
				}
				if ( isset( $tables['externallinks'] ) ) {
					$db->update( 'externallinks',
						array( 'el_from_namespace' => $row->page_namespace ),
						array( 'el_from' => $row->page_id ),
						__METHOD__
					);
				}
			}
			$blockStart += $this->mBatchSize - 1;
			$blockEnd += $this->mBatchSize - 1;
			wfWaitForSlaves();
		}
		return true;
	}
}

$maintClass = "PopulateBacklinkNamespace";
require_once RUN_MAINTENANCE_IF_MAIN;
