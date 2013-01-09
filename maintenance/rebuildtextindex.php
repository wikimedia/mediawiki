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

require_once( __DIR__ . '/Maintenance.php' );

/**
 * Maintenance script that rebuilds search index table from scratch.
 *
 * @ingroup Maintenance
 */
class RebuildTextIndex extends Maintenance {
	const RTI_CHUNK_SIZE = 500;

	/**
	 * @var DatabaseBase
	 */
	private $db;

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Rebuild search index table from scratch";
	}

	public function getDbType() {
		return Maintenance::DB_ADMIN;
	}

	public function execute() {
		global $wgTitle;

		// Shouldn't be needed for Postgres
		$this->db = wfGetDB( DB_MASTER );
		if ( $this->db->getType() == 'postgres' ) {
			$this->error( "This script is not needed when using Postgres.\n", true );
		}

		$this->db = wfGetDB( DB_MASTER );
		if ( $this->db->getType() == 'sqlite' ) {
			if ( !DatabaseSqlite::getFulltextSearchModule() ) {
				$this->error( "Your version of SQLite module for PHP doesn't support full-text search (FTS3).\n", true );
			}
			if ( !$this->db->checkForEnabledSearch() ) {
				$this->error( "Your database schema is not configured for full-text search support. Run update.php.\n", true );
			}
		}

		$wgTitle = Title::newFromText( "Rebuild text index script" );

		if ( $this->db->getType() == 'mysql' ) {
			$this->dropMysqlTextIndex();
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
		$res = $this->db->select( 'page', 'MAX(page_id) AS count' );
		$s = $this->db->fetchObject( $res );
		$count = $s->count;
		$this->output( "Rebuilding index fields for {$count} pages...\n" );
		$n = 0;

		$fields = array_merge(
			Revision::selectPageFields(),
			Revision::selectFields(),
			Revision::selectTextFields()
		);

		while ( $n < $count ) {
			if ( $n ) {
				$this->output( $n . "\n" );
			}
			$end = $n + self::RTI_CHUNK_SIZE - 1;

			$res = $this->db->select( array( 'page', 'revision', 'text' ), $fields,
				array( "page_id BETWEEN $n AND $end", 'page_latest = rev_id', 'rev_text_id = old_id' ),
				__METHOD__
			);

			foreach ( $res as $s ) {
				try {
					$title = Title::makeTitle( $s->page_namespace, $s->page_title );

					$rev = new Revision( $s );
					$content = $rev->getContent();
					$text = $content->getTextForSearchIndex();

					$u = new SearchUpdate( $s->page_id, $title, $text );
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
		$searchindex = $this->db->tableName( 'searchindex' );
		if ( $this->db->indexExists( 'searchindex', 'si_title', __METHOD__ ) ) {
			$this->output( "Dropping index...\n" );
			$sql = "ALTER TABLE $searchindex DROP INDEX si_title, DROP INDEX si_text";
			$this->db->query( $sql, __METHOD__ );
		}
	}

	/**
	 * (MySQL only) Adds back fulltext index after populating the table.
	 */
	private function createMysqlTextIndex() {
		$searchindex = $this->db->tableName( 'searchindex' );
		$this->output( "\nRebuild the index...\n" );
		$sql = "ALTER TABLE $searchindex ADD FULLTEXT si_title (si_title), " .
		  "ADD FULLTEXT si_text (si_text)";
		$this->db->query( $sql, __METHOD__ );
	}

	/**
	 * Deletes everything from search index.
	 */
	private function clearSearchIndex() {
		$this->output( 'Clearing searchindex table...' );
		$this->db->delete( 'searchindex', '*', __METHOD__ );
		$this->output( "Done\n" );
	}
}

$maintClass = "RebuildTextIndex";
require_once( RUN_MAINTENANCE_IF_MAIN );
