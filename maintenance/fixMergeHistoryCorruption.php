<?php
/**
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

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

use MediaWiki\Title\Title;

/**
 * Maintenance script that clears rows of pages corrupted by MergeHistory, those
 * pages 'exist' but have no visible revision.
 *
 * These pages are completely inaccessible via the UI due to revision/title mismatch
 * exceptions in RevisionStore and elsewhere.
 *
 * These are rows in page_table that have 'page_latest' entry with corresponding
 * 'rev_id' but no associated 'rev_page' entry in revision table. Such rows create
 * ghost pages because their 'page_latest' is actually living on different pages
 * (which possess the associated 'rev_page' on revision table now).
 *
 * @see https://phabricator.wikimedia.org/T263340
 * @see https://phabricator.wikimedia.org/T259022
 */
class FixMergeHistoryCorruption extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Delete pages corrupted by MergeHistory' );
		$this->addOption( 'ns', 'Namespace to restrict the query', false, true );
		$this->addOption( 'dry-run', 'Run in dry-mode' );
		$this->addOption( 'delete', 'Actually delete the found rows' );
	}

	public function execute() {
		$dbr = $this->getReplicaDB();
		$dbw = $this->getPrimaryDB();

		$dryRun = true;
		if ( $this->hasOption( 'dry-run' ) && $this->hasOption( 'delete' ) ) {
			$this->fatalError( 'Cannot do both --dry-run and --delete.' );
		} elseif ( $this->hasOption( 'delete' ) ) {
			$dryRun = false;
		} elseif ( !$this->hasOption( 'dry-run' ) ) {
			$this->fatalError( 'Either --dry-run or --delete must be specified.' );
		}

		$conds = [ 'page_id<>rev_page' ];
		if ( $this->hasOption( 'ns' ) ) {
			$conds['page_namespace'] = (int)$this->getOption( 'ns' );
		}

		$res = $dbr->newSelectQueryBuilder()
			->from( 'page' )
			->join( 'revision', null, 'page_latest=rev_id' )
			->fields( [ 'page_namespace', 'page_title', 'page_id' ] )
			->where( $conds )
			->caller( __METHOD__ )
			->fetchResultSet();

		$count = $res->numRows();

		if ( !$count ) {
			$this->output( "Nothing was found, no page matches the criteria.\n" );
			return;
		}

		$numDeleted = 0;
		$numUpdated = 0;

		foreach ( $res as $row ) {
			$title = Title::makeTitleSafe( $row->page_namespace, $row->page_title );
			if ( !$title ) {
				$this->output( "Skipping invalid title with page_id: $row->page_id\n" );
				continue;
			}
			$titleText = $title->getPrefixedDBkey();

			// Check if there are any revisions that have this $row->page_id as their
			// rev_page and select the largest which should be the newest revision.
			$revId = $dbr->newSelectQueryBuilder()
				->select( 'MAX(rev_id)' )
				->from( 'revision' )
				->where( [ 'rev_page' => $row->page_id ] )
				->caller( __METHOD__ )->fetchField();

			if ( !$revId ) {
				if ( $dryRun ) {
					$this->output( "Would delete $titleText with page_id: $row->page_id\n" );
				} else {
					$this->output( "Deleting $titleText with page_id: $row->page_id\n" );
					$dbw->newDeleteQueryBuilder()
						->deleteFrom( 'page' )
						->where( [ 'page_id' => $row->page_id ] )
						->caller( __METHOD__ )->execute();
				}
				$numDeleted++;
			} else {
				if ( $dryRun ) {
					$this->output( "Would update page_id $row->page_id to page_latest $revId\n" );
				} else {
					$this->output( "Updating page_id $row->page_id to page_latest $revId\n" );
					$dbw->newUpdateQueryBuilder()
						->update( 'page' )
						->set( [ 'page_latest' => $revId ] )
						->where( [ 'page_id' => $row->page_id ] )
						->caller( __METHOD__ )->execute();
				}
				$numUpdated++;
			}
		}

		if ( !$dryRun ) {
			$this->output( "Updated $numUpdated row(s), deleted $numDeleted row(s)\n" );
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = FixMergeHistoryCorruption::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
