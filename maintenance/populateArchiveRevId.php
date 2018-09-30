<?php
/**
 * Populate ar_rev_id in pre-1.5 rows
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

use Wikimedia\Rdbms\DBQueryError;
use Wikimedia\Rdbms\IDatabase;

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that populares archive.ar_rev_id in old rows
 *
 * @ingroup Maintenance
 * @since 1.31
 */
class PopulateArchiveRevId extends LoggedUpdateMaintenance {

	/** @var array|null Dummy revision row */
	private static $dummyRev = null;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Populate ar_rev_id in pre-1.5 rows' );
		$this->setBatchSize( 100 );
	}

	protected function getUpdateKey() {
		return __CLASS__;
	}

	protected function doDBUpdates() {
		$this->output( "Populating ar_rev_id...\n" );
		$dbw = $this->getDB( DB_MASTER );
		self::checkMysqlAutoIncrementBug( $dbw );

		// Quick exit if there are no rows needing updates.
		$any = $dbw->selectField(
			'archive',
			'ar_id',
			[ 'ar_rev_id' => null ],
			__METHOD__
		);
		if ( !$any ) {
			$this->output( "Completed ar_rev_id population, 0 rows updated.\n" );
			return true;
		}

		$count = 0;
		while ( true ) {
			wfWaitForSlaves();

			$arIds = $dbw->selectFieldValues(
				'archive',
				'ar_id',
				[ 'ar_rev_id' => null ],
				__METHOD__,
				[ 'LIMIT' => $this->getBatchSize(), 'ORDER BY' => [ 'ar_id' ] ]
			);
			if ( !$arIds ) {
				$this->output( "Completed ar_rev_id population, $count rows updated.\n" );
				return true;
			}

			$count += self::reassignArRevIds( $dbw, $arIds, [ 'ar_rev_id' => null ] );

			$min = min( $arIds );
			$max = max( $arIds );
			$this->output( " ... $min-$max\n" );
		}
	}

	/**
	 * Check for (and work around) a MySQL auto-increment bug
	 *
	 * (T202032) MySQL until 8.0 and MariaDB until some version after 10.1.34
	 * don't save the auto-increment value to disk, so on server restart it
	 * might reuse IDs from deleted revisions. We can fix that with an insert
	 * with an explicit rev_id value, if necessary.
	 *
	 * @param IDatabase $dbw
	 */
	public static function checkMysqlAutoIncrementBug( IDatabase $dbw ) {
		if ( $dbw->getType() !== 'mysql' ) {
			return;
		}

		if ( !self::$dummyRev ) {
			self::$dummyRev = self::makeDummyRevisionRow( $dbw );
		}

		$ok = false;
		while ( !$ok ) {
			try {
				$dbw->doAtomicSection( __METHOD__, function ( $dbw, $fname ) {
					$dbw->insert( 'revision', self::$dummyRev, $fname );
					$id = $dbw->insertId();
					$toDelete[] = $id;

					$maxId = max(
						(int)$dbw->selectField( 'archive', 'MAX(ar_rev_id)', [], $fname ),
						(int)$dbw->selectField( 'slots', 'MAX(slot_revision_id)', [], $fname )
					);
					if ( $id <= $maxId ) {
						$dbw->insert( 'revision', [ 'rev_id' => $maxId + 1 ] + self::$dummyRev, $fname );
						$toDelete[] = $maxId + 1;
					}

					$dbw->delete( 'revision', [ 'rev_id' => $toDelete ], $fname );
				} );
				$ok = true;
			} catch ( DBQueryError $e ) {
				if ( $e->errno != 1062 ) { // 1062 is "duplicate entry", ignore it and retry
					throw $e;
				}
			}
		}
	}

	/**
	 * Assign new ar_rev_ids to a set of ar_ids.
	 * @param IDatabase $dbw
	 * @param int[] $arIds
	 * @param array $conds Extra conditions for the update
	 * @return int Number of updated rows
	 */
	public static function reassignArRevIds( IDatabase $dbw, array $arIds, array $conds = [] ) {
		if ( !self::$dummyRev ) {
			self::$dummyRev = self::makeDummyRevisionRow( $dbw );
		}

		$updates = $dbw->doAtomicSection( __METHOD__, function ( $dbw, $fname ) use ( $arIds ) {
			// Create new rev_ids by inserting dummy rows into revision and then deleting them.
			$dbw->insert( 'revision', array_fill( 0, count( $arIds ), self::$dummyRev ), $fname );
			$revIds = $dbw->selectFieldValues(
				'revision',
				'rev_id',
				[ 'rev_timestamp' => self::$dummyRev['rev_timestamp'] ],
				$fname
			);
			if ( !is_array( $revIds ) ) {
				throw new UnexpectedValueException( 'Failed to insert dummy revisions' );
			}
			if ( count( $revIds ) !== count( $arIds ) ) {
				throw new UnexpectedValueException(
					'Tried to insert ' . count( $arIds ) . ' dummy revisions, but found '
					. count( $revIds ) . ' matching rows.'
				);
			}
			$dbw->delete( 'revision', [ 'rev_id' => $revIds ], $fname );

			return array_combine( $arIds, $revIds );
		} );

		$count = 0;
		foreach ( $updates as $arId => $revId ) {
			$dbw->update(
				'archive',
				[ 'ar_rev_id' => $revId ],
				[ 'ar_id' => $arId ] + $conds,
				__METHOD__
			);
			$count += $dbw->affectedRows();
		}
		return $count;
	}

	/**
	 * Construct a dummy revision table row to use for reserving IDs
	 *
	 * The row will have a wildly unlikely timestamp, and possibly a generic
	 * user and comment, but will otherwise be derived from a revision on the
	 * wiki's main page or some other revision in the database.
	 *
	 * @param IDatabase $dbw
	 * @return array
	 */
	private static function makeDummyRevisionRow( IDatabase $dbw ) {
		$ts = $dbw->timestamp( '11111111111111' );
		$rev = null;

		$mainPage = Title::newMainPage();
		$pageId = $mainPage ? $mainPage->getArticleId() : null;
		if ( $pageId ) {
			$rev = $dbw->selectRow(
				'revision',
				'*',
				[ 'rev_page' => $pageId ],
				__METHOD__,
				[ 'ORDER BY' => 'rev_timestamp ASC' ]
			);
		}

		if ( !$rev ) {
			// No main page? Let's see if there are any revisions at all
			$rev = $dbw->selectRow(
				'revision',
				'*',
				[],
				__METHOD__,
				[ 'ORDER BY' => 'rev_timestamp ASC' ]
			);
		}
		if ( !$rev ) {
			// Since no revisions are available to copy, generate a dummy
			// revision to a dummy page, then rollback the commit
			wfDebug( __METHOD__ . ": No revisions are available to copy\n" );

			$dbw->begin();

			// Make a title and revision and insert them
			$title = Title::newFromText( "PopulateArchiveRevId_4b05b46a81e29" );
			$page = WikiPage::factory( $title );
			$updater = $page->newPageUpdater(
				User::newSystemUser( 'Maintenance script', [ 'steal' => true ] )
			);
			$updater->setContent(
				'main',
				ContentHandler::makeContent( "Content for dummy rev", $title )
			);
			$updater->saveRevision(
				CommentStoreComment::newUnsavedComment( 'dummy rev summary' ),
				EDIT_NEW | EDIT_SUPPRESS_RC
			);

			// get the revision row just inserted
			$rev = $dbw->selectRow(
				'revision',
				'*',
				[],
				__METHOD__,
				[ 'ORDER BY' => 'rev_timestamp ASC' ]
			);

			$dbw->rollback();
		}
		if ( !$rev ) {
			// This should never happen.
			throw new UnexpectedValueException(
				'No revisions are available to copy, and one couldn\'t be created'
			);
		}

		unset( $rev->rev_id );
		$rev = (array)$rev;
		$rev['rev_timestamp'] = $ts;
		if ( isset( $rev['rev_user'] ) ) {
			$rev['rev_user'] = 0;
			$rev['rev_user_text'] = '0.0.0.0';
		}
		if ( isset( $rev['rev_comment'] ) ) {
			$rev['rev_comment'] = 'Dummy row';
		}

		$any = $dbw->selectField(
			'revision',
			'rev_id',
			[ 'rev_timestamp' => $ts ],
			__METHOD__
		);
		if ( $any ) {
			throw new UnexpectedValueException( "... Why does your database contain a revision dated $ts?" );
		}

		return $rev;
	}
}

$maintClass = "PopulateArchiveRevId";
require_once RUN_MAINTENANCE_IF_MAIN;
