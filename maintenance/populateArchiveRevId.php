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
			throw new UnexpectedValueException( 'No revisions are available to copy' );
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
