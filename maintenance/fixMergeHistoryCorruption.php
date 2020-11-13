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

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that clears rows of pages corrupted by MergeHistory, those
 * pages 'exist' but have no visible revision.
 *
 * These pages are completely inacessible via the UI due to revision/title mismatch
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
		$this->addOption( 'ns', 'Namespace to restrict the query', true );
		$this->addOption( 'dry-run', 'Run in dry-mode' );
		$this->addOption( 'delete', 'Actually delete the found rows' );
	}

	public function execute() {
		$dbw = $this->getDB( DB_PRIMARY );
		$ns = (int)$this->getOption( 'ns' );

		if ( $this->hasOption( 'dry-run' ) ) {
			$res = $this->query( $dbw, $ns );
			$count = $res->numRows();

			if ( !$count ) {
				$this->output( "Nothing was found, no page matches the criteria.\n" );
				return;
			}

			// Printing them all out since it's believed these pages are not large in number
			$this->output( "Found $count page(s). To actually delete them pass the --delete option.\n" );
			$this->output( "All these pages are in namespace '$ns'. \n" );
			$this->output( str_pad( 'PAGE_NAME', 40 ) . "PAGE_ID\n" );

			foreach ( $res as $row ) {
				$this->output( str_pad( $row->page_title, 40 ) . $row->page_id . "\n" );
			}

		} elseif ( $this->hasOption( 'delete' ) ) {
			$deleted = 0;
			$res = $this->query( $dbw, $ns );

			if ( !$res->numRows() ) {
				$this->output( "Nothing was found, no page matches the criteria.\n" );
				return;
			}

			foreach ( $res as $row ) {
				// Check if there are any revisions that have this $row->page_id as their
				// rev_page and select the largest which should be the newest revision.
				$revId = $dbw->selectField(
					'revision',
					'MAX(rev_id)',
					[ 'rev_page' => $row->page_id ],
					__METHOD__
 );

				if ( !$revId ) {
					$this->output( "Deleting $row->page_title with page_id: $row->page_id\n" );
					$dbw->delete( 'page',  [ 'page_id' => $row->page_id ], __METHOD__ );
					$deleted++;
				} else {
					$this->output( "Found the page's revisions: Updating $row->page_title...\n" );
					$dbw->update(
						'page',
						[ 'page_latest' => $revId ],
						[ 'page_id' => $row->page_id ],
						__METHOD__
					);
				}
			}

			$msg = $deleted ? "Deleted $deleted page row(s).\n" : "Nothing was deleted.\n";
			$this->output( $msg );

		} else {
			$this->showHelp();
		}
	}

	private function query( $dbw, $ns ) {
		$query = $dbw->newSelectQueryBuilder()
			->from( 'page' )
			->join( 'revision', null, 'page_latest=rev_id' )
			->fields( [ 'page_namespace', 'page_title', 'page_id' ] )
			->where( [ 'page_id<>rev_page', 'page_namespace' => $ns ] )
			->caller( __METHOD__ );

		return $query->fetchResultSet();
	}
}

$maintClass = FixMergeHistoryCorruption::class;
require_once RUN_MAINTENANCE_IF_MAIN;
