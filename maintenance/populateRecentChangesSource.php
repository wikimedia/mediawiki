<?php
/**
 * Upgrade script to populate the rc_source field
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

use Wikimedia\Rdbms\IDatabase;

/**
 * Maintenance script to populate the rc_source field.
 *
 * @ingroup Maintenance
 * @since 1.22
 */
class PopulateRecentChangesSource extends LoggedUpdateMaintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription(
			'Populates rc_source field of the recentchanges table with the data in rc_type.' );
		$this->setBatchSize( 100 );
	}

	protected function doDBUpdates() {
		$dbw = $this->getDB( DB_MASTER );
		if ( !$dbw->fieldExists( 'recentchanges', 'rc_source' ) ) {
			$this->error( 'rc_source field in recentchanges table does not exist.' );
		}

		$start = $dbw->selectField( 'recentchanges', 'MIN(rc_id)', false, __METHOD__ );
		if ( !$start ) {
			$this->output( "Nothing to do.\n" );

			return true;
		}
		$end = $dbw->selectField( 'recentchanges', 'MAX(rc_id)', false, __METHOD__ );
		$end += $this->mBatchSize - 1;
		$blockStart = $start;
		$blockEnd = $start + $this->mBatchSize - 1;

		$updatedValues = $this->buildUpdateCondition( $dbw );

		while ( $blockEnd <= $end ) {
			$cond = "rc_id BETWEEN $blockStart AND $blockEnd";

			$dbw->update(
				'recentchanges',
				[ $updatedValues ],
				[
					"rc_source = ''",
					"rc_id BETWEEN $blockStart AND $blockEnd"
				],
				__METHOD__
			);

			$this->output( "." );
			wfWaitForSlaves();

			$blockStart += $this->mBatchSize;
			$blockEnd += $this->mBatchSize;
		}

		$this->output( "\nDone.\n" );
	}

	protected function getUpdateKey() {
		return __CLASS__;
	}

	protected function buildUpdateCondition( IDatabase $dbw ) {
		$rcNew = $dbw->addQuotes( RC_NEW );
		$rcSrcNew = $dbw->addQuotes( RecentChange::SRC_NEW );
		$rcEdit = $dbw->addQuotes( RC_EDIT );
		$rcSrcEdit = $dbw->addQuotes( RecentChange::SRC_EDIT );
		$rcLog = $dbw->addQuotes( RC_LOG );
		$rcSrcLog = $dbw->addQuotes( RecentChange::SRC_LOG );
		$rcExternal = $dbw->addQuotes( RC_EXTERNAL );
		$rcSrcExternal = $dbw->addQuotes( RecentChange::SRC_EXTERNAL );

		return "rc_source = CASE
					WHEN rc_type = $rcNew THEN $rcSrcNew
					WHEN rc_type = $rcEdit THEN $rcSrcEdit
					WHEN rc_type = $rcLog THEN $rcSrcLog
					WHEN rc_type = $rcExternal THEN $rcSrcExternal
					ELSE ''
				END";
	}
}

$maintClass = "PopulateRecentChangesSource";
require_once RUN_MAINTENANCE_IF_MAIN;
