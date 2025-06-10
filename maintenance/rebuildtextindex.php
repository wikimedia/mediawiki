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
 */

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

use MediaWiki\Exception\MWContentSerializationException;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Search\SearchUpdate;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\DatabaseSqlite;

/**
 * Rebuild search index table from scratch.
 *
 * This may take several hours, depending on the database size and server configuration.
 * Postgres is trigger-based and should never need rebuilding.
 *
 * @ingroup Search
 * @ingroup Maintenance
 */
class RebuildTextIndex extends Maintenance {
	private const RTI_CHUNK_SIZE = 500;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Rebuild search index table from scratch' );
	}

	/** @inheritDoc */
	public function getDbType() {
		return Maintenance::DB_ADMIN;
	}

	public function execute() {
		// Shouldn't be needed for Postgres
		$dbw = $this->getPrimaryDB();
		if ( $dbw->getType() == 'postgres' ) {
			$this->fatalError( "This script is not needed when using Postgres.\n" );
		}

		if ( $dbw->getType() == 'sqlite' ) {
			if ( !DatabaseSqlite::getFulltextSearchModule() ) {
				$this->fatalError( "Your version of SQLite module for PHP doesn't "
					. "support full-text search (FTS3).\n" );
			}
		}

		if ( $dbw->getType() == 'mysql' ) {
			$this->dropMysqlTextIndex();
			$this->clearSearchIndex();
			$this->populateSearchIndex();
			$this->createMysqlTextIndex();
		} else {
			$this->clearSearchIndex();
			$this->populateSearchIndex();
		}

		$this->output( "Done.\n" );
	}

	/**
	 * Populates the search index with content from all pages
	 */
	protected function populateSearchIndex() {
		$dbw = $this->getPrimaryDB();
		$res = $dbw->newSelectQueryBuilder()
			->select( [ 'count' => 'MAX(page_id)' ] )
			->from( 'page' )
			->caller( __METHOD__ )->fetchResultSet();
		$s = $res->fetchObject();
		$count = $s->count;
		$this->output( "Rebuilding index fields for {$count} pages...\n" );
		$n = 0;

		$revStore = $this->getServiceContainer()->getRevisionStore();
		$queryBuilderTemplate = $revStore->newSelectQueryBuilder( $dbw )
			->joinPage()
			->joinComment();

		while ( $n < $count ) {
			if ( $n ) {
				$this->output( $n . "\n" );
			}
			$end = $n + self::RTI_CHUNK_SIZE - 1;
			$queryBuilder = clone $queryBuilderTemplate;
			$res = $queryBuilder->where( [
					$dbw->expr( 'page_id', '>=', $n )->and( 'page_id', '<=', $end ),
					'page_latest = rev_id'
				] )->caller( __METHOD__ )->fetchResultSet();

			foreach ( $res as $s ) {

				// T268673 Prevent failure of WikiPage.php: Invalid or virtual namespace -1 given
				if ( $s->page_namespace < 0 ) {
					continue;
				}

				$title = Title::makeTitle( $s->page_namespace, $s->page_title );
				try {
					$revRecord = $revStore->newRevisionFromRow( $s );
					$content = $revRecord->getContent( SlotRecord::MAIN );

					$u = new SearchUpdate( $s->page_id, $title, $content );
					$u->doUpdate();
				} catch ( MWContentSerializationException ) {
					$this->output( "Failed to deserialize content of revision {$s->rev_id} of page "
						. "`" . $title->getPrefixedDBkey() . "`!\n" );
				}
			}
			$n += self::RTI_CHUNK_SIZE;
		}
	}

	/**
	 * (MySQL only) Drops fulltext index before populating the table.
	 */
	private function dropMysqlTextIndex() {
		$dbw = $this->getDB( DB_PRIMARY );
		$searchindex = $dbw->tableName( 'searchindex' );
		if ( $dbw->indexExists( 'searchindex', 'si_title', __METHOD__ ) ) {
			$this->output( "Dropping index...\n" );
			$sql = "ALTER TABLE $searchindex DROP INDEX si_title, DROP INDEX si_text";
			$dbw->query( $sql, __METHOD__ );
		}
	}

	/**
	 * (MySQL only) Adds back fulltext index after populating the table.
	 */
	private function createMysqlTextIndex() {
		$dbw = $this->getPrimaryDB();
		$searchindex = $dbw->tableName( 'searchindex' );
		$this->output( "\nRebuild the index...\n" );
		foreach ( [ 'si_title', 'si_text' ] as $field ) {
			$sql = "ALTER TABLE $searchindex ADD FULLTEXT $field ($field)";
			$dbw->query( $sql, __METHOD__ );
		}
	}

	/**
	 * Deletes everything from search index.
	 */
	private function clearSearchIndex() {
		$dbw = $this->getPrimaryDB();
		$this->output( 'Clearing searchindex table...' );
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'searchindex' )
			->where( '*' )
			->caller( __METHOD__ )->execute();
		$this->output( "Done\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = RebuildTextIndex::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
