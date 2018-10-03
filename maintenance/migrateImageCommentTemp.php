<?php
/**
 * Merge `image_comment_temp` into the `image` table
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
 * Maintenance script that merges `image_comment_temp` into the `image` table
 *
 * @ingroup Maintenance
 * @since 1.32
 */
class MigrateImageCommentTemp extends LoggedUpdateMaintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription(
			'Merges image_comment_temp into the image table'
		);
	}

	/**
	 * Sets whether a run of this maintenance script has the force parameter set
	 * @param bool $forced
	 */
	public function setForce( $forced = true ) {
		$this->mOptions['force'] = $forced;
	}

	protected function getUpdateKey() {
		return __CLASS__;
	}

	protected function doDBUpdates() {
		$batchSize = $this->getBatchSize();

		$dbw = $this->getDB( DB_MASTER );
		if ( !$dbw->fieldExists( 'image', 'img_description_id', __METHOD__ ) ) {
			$this->output( "Run update.php to create img_description_id.\n" );
			return false;
		}
		if ( !$dbw->tableExists( 'image_comment_temp', __METHOD__ ) ) {
			$this->output( "image_comment_temp does not exist, so nothing to do.\n" );
			return true;
		}

		$this->output( "Merging image_comment_temp into the image table...\n" );
		$conds = [];
		$updated = 0;
		$deleted = 0;
		while ( true ) {
			$this->beginTransaction( $dbw, __METHOD__ );

			$res = $dbw->select(
				[ 'image_comment_temp', 'image' ],
				[
					'name' => 'imgcomment_name',
					'oldid' => 'imgcomment_description_id',
					'newid' => 'img_description_id',
				],
				$conds,
				__METHOD__,
				[ 'LIMIT' => $batchSize, 'ORDER BY' => [ 'name' ] ],
				[ 'image' => [ 'JOIN', 'img_name = imgcomment_name' ] ]
			);
			$numRows = $res->numRows();

			$toDelete = [];
			$last = null;
			foreach ( $res as $row ) {
				$last = $row->name;
				$toDelete[] = $row->name;
				if ( !$row->newid ) {
					$dbw->update(
						'image',
						[ 'img_description_id' => $row->oldid ],
						[ 'img_name' => $row->name ],
						__METHOD__
					);
					$updated++;
				} elseif ( $row->newid !== $row->oldid ) {
					$this->error(
						"Image \"$row->name\" has img_description_id = $row->newid and "
						. "imgcomment_description_id = $row->oldid. Ignoring the latter."
					);
				}
			}
			if ( $toDelete ) {
				$dbw->delete( 'image_comment_temp', [ 'imgcomment_name' => $toDelete ], __METHOD__ );
				$deleted += count( $toDelete );
			}

			$this->commitTransaction( $dbw, __METHOD__ );

			if ( $numRows < $batchSize ) {
				// We must have reached the end
				break;
			}

			$this->output( "... $last, updated $updated, deleted $deleted\n" );
			$conds = [ 'imgcomment_name > ' . $dbw->addQuotes( $last ) ];
		}

		// This should be 0, so it should be very fast
		$count = (int)$dbw->selectField( 'image_comment_temp', 'COUNT(*)', [], __METHOD__ );
		if ( $count !== 0 ) {
			$this->error( "Ignoring $count orphaned image_comment_temp row(s)." );
		}

		$this->output(
			"Completed merge of image_comment_temp into the image table, "
			. "$updated image rows updated, $deleted image_comment_temp rows deleted.\n"
		);

		return true;
	}
}

$maintClass = MigrateImageCommentTemp::class;
require_once RUN_MAINTENANCE_IF_MAIN;
