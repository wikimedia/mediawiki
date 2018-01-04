<?php
/**
 * Pouplates tag table
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
 * Maintenance script that makes the required database updates for the tag table to be of any use.
 *
 * @ingroup Maintenance
 */
class PopulateTagTable extends LoggedUpdateMaintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Populates tag table' );
	}

	protected function getUpdateKey() {
		return 'populate tag table';
	}

	protected function updateSkippedMessage() {
		return 'tag table already populated.';
	}

	protected function doDBUpdates() {
		global $wgChangeTagsSchemaMigrationStage;
		$db = $this->getDB( DB_MASTER );
		if ( $wgChangeTagsSchemaMigrationStage === MIGRATION_OLD ) {
			$this->error( "\$wgChangeTagsSchemaMigrationStage must not be set to MIGRATION_OLD\n" .
				"Set it to MIGRATION_WRITE_BOTH if this is the first time you're running this script." );
			return false;
		}
		if ( !$db->tableExists( 'tag' ) ) {
			$this->error( "tag table does not exist" );
			return false;
		}
		if ( !$db->fieldExists( 'change_tag', 'ct_tag_id' ) ) {
			$this->error( "change_tag table does not have ct_tag_id field" );
			return false;
		}
		$this->output( "Populating tag table\n" );

		// fetch tags used on the wiki
		$res = $db->select(
			'change_tag',
			'ct_tag',
			[],
			__METHOD__,
			[ 'DISTINCT' ]
		);

		$tags = 0;
		$ctRows = 0;
		foreach ( $res as $row ) {
			$tag = $row->ct_tag;
			$db->startAtomic( __METHOD__ );
			$hitcount = $db->selectRowCount(
				'change_tag',
				'*',
				[ 'ct_tag' => $tag ],
				__METHOD__
			);
			if ( $hitcount ) {
				$tags++;
				// Insert a row with name and hit count (timestamp is not computed)
				$row = [ 'tag_name' => $tag, 'tag_count' => $hitcount ];
				$db->insert( 'tag', $row, __METHOD__, [ 'IGNORE' ] );
				if ( $db->affectedRows() > 0 ) {
					$tagId = $db->insertId();
				} else {
					// The row is already there. Replace it, then query its ID.
					$db->replace( 'tag', [ 'tag_name' ], $row );
					$tagId = $db->selectField( 'tag', 'tag_id', [ 'tag_name' => $tag ] );
				}
			}
			$db->endAtomic( __METHOD__ );
			if ( $hitcount ) {
				// Update the ID in the change_tag rows
				$db->update(
					'change_tag',
					[ 'cts_tag_id' => $tagId ],
					[ 'cts_tag' => $tag ]
				);
				$ctRows += $db->affectedRows();
			}
		}

		$this->output(
			"tag table population complete. {$tags} tags added, {$ctRows} change_tag rows updated\n"
		);

		return true;
	}
}

$maintClass = "PopulateTagTable";
require_once RUN_MAINTENANCE_IF_MAIN;
