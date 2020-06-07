<?php
/**
 * Makes the required database updates for Special:ProtectedPages
 * to show all protected pages, even ones before the page restrictions
 * schema change. All remaining page_restriction column values are moved
 * to the new table.
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
 * Maintenance script that updates page_restrictions table from
 * old page_restriction column.
 *
 * @ingroup Maintenance
 */
class UpdateRestrictions extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Updates page_restrictions table from old page_restriction column' );
		$this->setBatchSize( 1000 );
	}

	public function execute() {
		$dbw = $this->getDB( DB_MASTER );
		$batchSize = $this->getBatchSize();

		if ( !$dbw->tableExists( 'page_restrictions', __METHOD__ ) ) {
			$this->fatalError( "page_restrictions table does not exist" );
		}

		$encodedExpiry = $dbw->getInfinity();

		$maxPageId = $dbw->selectField( 'page', 'MAX(page_id)', '', __METHOD__ );
		$escapedEmptyBlobValue = $dbw->addQuotes( '' );

		$batchMinPageId = 0;

		do {
			$batchMaxPageId = $batchMinPageId + $batchSize;

			$this->output( "...processing page IDs from $batchMinPageId to $batchMaxPageId.\n" );

			$res = $dbw->select(
				'page',
				[ 'page_id', 'page_restrictions' ],
				[
					"page_restrictions != $escapedEmptyBlobValue",
					'page_id > ' . $dbw->addQuotes( $batchMinPageId ),
					'page_id <= ' . $dbw->addQuotes( $batchMaxPageId ),
				],
				__METHOD__
			);

			// No pages have legacy protection settings in the current batch
			if ( !$res->numRows() ) {
				$batchMinPageId = $batchMaxPageId;
				continue;
			}

			$batch = [];
			$pageIds = [];

			foreach ( $res as $row ) {
				$pageIds[] = $row->page_id;

				$restrictionsByAction = $this->mapLegacyRestrictionBlob( $row->page_restrictions );

				# Update restrictions table
				foreach ( $restrictionsByAction as $action => $restrictions ) {
					$batch[] = [
						'pr_page' => $row->page_id,
						'pr_type' => $action,
						'pr_level' => $restrictions,
						'pr_cascade' => 0,
						'pr_expiry' => $encodedExpiry
					];
				}
			}

			$this->beginTransaction( $dbw, __METHOD__ );

			// Insert new format protection settings for the pages in the current batch.
			// Use INSERT IGNORE to ignore conflicts with new format settings that might exist for the page
			$dbw->insert(
				'page_restrictions',
				$batch,
				__METHOD__,
				[ 'IGNORE' ]
			);

			// Clear out the legacy page.page_restrictions blob for this batch
			$dbw->update( 'page', [ 'page_restrictions' => '' ], [ 'page_id' => $pageIds ], __METHOD__ );

			$this->commitTransaction( $dbw, __METHOD__ );

			$batchMinPageId = $batchMaxPageId;
		} while ( $batchMaxPageId < $maxPageId );

		$this->output( "...Done!\n" );
	}

	/**
	 * Convert a legacy restriction specification from the page.page_restrictions blob to
	 * a map of action names to restriction levels.
	 *
	 * @param string $legacyBlob Legacy page.page_restrictions blob,
	 * e.g. "sysop" or "edit=sysop:move=autoconfirmed"
	 * @return string[] array of restriction levels keyed by action names
	 */
	private function mapLegacyRestrictionBlob( $legacyBlob ) {
		$oldRestrictions = [];

		foreach ( explode( ':', trim( $legacyBlob ) ) as $restrict ) {
			$temp = explode( '=', trim( $restrict ) );

			// Treat old old format without action name as edit/move restriction
			if ( count( $temp ) == 1 ) {
				$level = trim( $temp[0] );

				$oldRestrictions['edit'] = $level;
				$oldRestrictions['move'] = $level;
			} else {
				$restriction = trim( $temp[1] );
				// Some old entries are empty
				if ( $restriction != '' ) {
					$oldRestrictions[$temp[0]] = $restriction;
				}
			}
		}

		return $oldRestrictions;
	}
}

$maintClass = UpdateRestrictions::class;
require_once RUN_MAINTENANCE_IF_MAIN;
