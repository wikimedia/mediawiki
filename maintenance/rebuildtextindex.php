<?php
/**
 * Rebuild search index table from scratch.  This may take several
 * hours, depending on the database size and server configuration.
 *
 * Postgres is trigger-based and should never need rebuilding.
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
 * @todo document
 */

require_once __DIR__ . '/Maintenance.php';

use Wikimedia\Rdbms\DatabaseSqlite;

/**
 * Maintenance script that rebuilds search index table from scratch.
 *
 * @ingroup Maintenance
 */
class RebuildTextIndex extends Maintenance {
	const RTI_CHUNK_SIZE = 500;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Rebuild search index table from scratch' );
	}

	public function getDbType() {
		return Maintenance::DB_ADMIN;
	}

	public function execute() {
		// Shouldn't be needed for Postgres
		$dbw = $this->getDB( DB_MASTER );
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
		$dbw = $this->getDB( DB_MASTER );
		$res = $dbw->select( 'page', 'MAX(page_id) AS count' );
		$s = $dbw->fetchObject( $res );
		$count = $s->count;
		$this->output( "Rebuilding index fields for {$count} pages...\n" );
		$n = 0;

		$revQuery = Revision::getQueryInfo( [ 'page' ] );

		while ( $n < $count ) {
			if ( $n ) {
				$this->output( $n . "\n" );
			}
			$end = $n + self::RTI_CHUNK_SIZE - 1;

			$res = $dbw->select(
				$revQuery['tables'],
				$revQuery['fields'],
				[ "page_id BETWEEN $n AND $end", 'page_latest = rev_id' ],
				__METHOD__,
				[],
				$revQuery['joins']
			);

			foreach ( $res as $s ) {
				$title = Title::makeTitle( $s->page_namespace, $s->page_title );
				try {
					$rev = new Revision( $s );
					$content = $rev->getContent();

					$u = new SearchUpdate( $s->page_id, $title, $content );
					$u->doUpdate();
				} catch ( MWContentSerializationException $ex ) {
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
		$dbw = $this->getDB( DB_MASTER );
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
		$dbw = $this->getDB( DB_MASTER );
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
		$dbw = $this->getDB( DB_MASTER );
		$this->output( 'Clearing searchindex table...' );
		$dbw->delete( 'searchindex', '*', __METHOD__ );
		$this->output( "Done\n" );
	}
}

$maintClass = RebuildTextIndex::class;
require_once RUN_MAINTENANCE_IF_MAIN;
