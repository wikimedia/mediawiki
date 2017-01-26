<?php
/**
 * Pouplates change_tag_statistics table
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
 * Maintenance script that makes the required database updates for change_tag_statistics
 * table to be of any use.
 *
 * @ingroup Maintenance
 */
class PopulateTagUsageStatistics extends LoggedUpdateMaintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Populates change_tag_statistics' );
	}

	protected function getUpdateKey() {
		return 'populate change_tag_statistics';
	}

	protected function updateSkippedMessage() {
		return 'change_tag_statistics table already populated.';
	}

	protected function doDBUpdates() {
		$db = $this->getDB( DB_MASTER );
		if ( !$db->tableExists( 'change_tag_statistics' ) ) {
			$this->error( "change_tag_statistics table does not exist" );

			return false;
		}
		$this->output( "Populating change_tag_statistics table\n" );

		// clear table
		$db->delete( 'change_tag_statistics', [ '1=1' ], __METHOD__ );

		// get hitcounts
		$res = $db->select(
			'change_tag',
			[ 'ct_tag', 'hitcount' => 'count(*)' ],
			[],
			__METHOD__,
			[ 'GROUP BY' => 'ct_tag', 'ORDER BY' => 'hitcount DESC' ]
		);

		$count = 0;
		$rows = [];
		foreach ( $res as $row ) {
			$count++;
			$rows[] = [ 'cts_tag' => $row->ct_tag, 'cts_count' => $row->hitcount ];
		}

		// record hitcounts
		$db->insert( 'change_tag_statistics', $rows, __METHOD__, [] );

		$this->output( "change_tag_statistics population complete ... hitcounts added for {$count} tags\n" );

		return true;
	}
}

$maintClass = "PopulateTagUsageStatistics";
require_once RUN_MAINTENANCE_IF_MAIN;
