<?php

use Wikimedia\Rdbms\IDatabase;

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that cleans up archive rows with duplicated ar_rev_id.
 *
 * @ingroup Maintenance
 * @since 1.32
 */
class DeduplicateArchiveRevId extends LoggedUpdateMaintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Clean up duplicate ar_rev_id' );
		$this->setBatchSize( 10000 );
	}

	protected function getUpdateKey() {
		return __CLASS__;
	}

	protected function doDBUpdates() {
		$this->output( "Deduplicating ar_rev_id...\n" );

		$dbw = $this->getDB( DB_MASTER );

		$rev = $this->makeDummyRevisionRow( $dbw );
		$deleted = 0;
		$reassigned = 0;

		$maxId = $dbw->selectField( 'archive', 'MAX(ar_rev_id)', [], __METHOD__ );
		$batchSize = $this->getBatchSize();

		$arActorQuery = ActorMigration::newMigration()->getJoin( 'ar_user' );
		$revActorQuery = ActorMigration::newMigration()->getJoin( 'rev_user' );

		$any = false;
		for ( $id = 1; $id <= $maxId; $id += $batchSize ) {
			$endId = min( $maxId, $id + $batchSize );

			$this->beginTransaction( $dbw, __METHOD__ );

			// Lock the archive and revision table rows for the IDs we're checking
			// to try to prevent deletions or undeletions from confusing things.
			$dbw->selectRowCount(
				'archive',
				1,
				[
					'ar_rev_id >= ' . (int)$id,
					'ar_rev_id < ' . (int)$endId,
				],
				__METHOD__,
				[ 'FOR UPDATE' ]
			);
			$dbw->selectRowCount(
				'revision',
				1,
				[
					'rev_id >= ' . (int)$id,
					'rev_id < ' . (int)$endId,
				],
				__METHOD__,
				[ 'LOCK IN SHARE MODE' ]
			);

			// Now select all the data we need
			$res = $dbw->select(
				[ 'archive' ] + $arActorQuery['tables']
					+ [ 'rev' => [ 'revision' ] + $revActorQuery['tables'] ],
				[
					'ar_id',
					'ar_rev_id',
					'ar_namespace',
					'ar_title',
					'ar_timestamp',
					'ar_sha1',
					'rev_id',
					'rev_timestamp',
					'rev_sha1',
				] + $arActorQuery['fields'] + $revActorQuery['fields'],
				[
					'ar_rev_id >= ' . (int)$id,
					'ar_rev_id < ' . (int)$endId,
				],
				__METHOD__,
				[],
				[
					'rev' => [ 'LEFT JOIN', 'ar_rev_id = rev_id' ],
				] + $arActorQuery['joins'] + $revActorQuery['joins']
			);

			// Determine which rows we need to delete or reassign
			$seen = [];
			$toDelete = [];
			$toReassign = [];
			foreach ( $res as $row ) {
				if ( isset( $row->rev_id ) ) {
					// Record the rev_id as seen, so the code below will always delete or reassign.
					if ( !isset( $seen[$row->rev_id] ) ) {
						$seen[$row->rev_id] = [
							'first' => "revision row",
						];
					}

					// If there's a revision-table row, delete the archive row
					// if it seems to be the same regardless of page, because
					// moves.
					if ( $row->ar_timestamp === $row->rev_timestamp &&
						$row->ar_sha1 === $row->rev_sha1 &&
						$row->ar_user === $row->rev_user &&
						$row->ar_user_text === $row->rev_user_text
					) {
						$this->output(
							"Row $row->ar_id duplicates revision row for rev_id $row->rev_id, deleting\n"
						);
						$toDelete[] = $row->ar_id;
						continue;
					}
				}

				$key = $this->getSeenKey( $row );
				if ( !isset( $seen[$row->ar_rev_id] ) ) {
					// This rev_id hasn't even been seen yet, nothing to do besides record it.
					$seen[$row->ar_rev_id] = [
						'first' => "archive row $row->ar_id",
						$key => $row->ar_id,
					];
				} elseif ( !isset( $seen[$row->ar_rev_id][$key] ) ) {
					// The rev_id was seen, but not this particular change. Reassign it.
					$seen[$row->ar_rev_id][$key] = $row->ar_id;
					$this->output(
						"Row $row->ar_id conflicts with {$seen[$row->ar_rev_id]['first']} "
						. "for rev_id $row->ar_rev_id, reassigning\n"
					);
					$toReassign[] = $row->ar_id;
				} else {
					// The rev_id was seen with a row that matches this change. Delete it.
					$this->output(
						"Row $row->ar_id duplicates archive row {$seen[$row->ar_rev_id][$key]} "
						. "for rev_id $row->ar_rev_id, deleting\n"
					);
					$toDelete[] = $row->ar_id;
				}
			}

			// Perform the updates
			if ( $toDelete ) {
				$dbw->delete( 'archive', [ 'ar_id' => $toDelete ], __METHOD__ );
				$deleted += $dbw->affectedRows();
			}
			if ( $toReassign ) {
				$arIds = $toReassign;
				try {
					$updates = $dbw->doAtomicSection( __METHOD__, function ( $dbw, $fname ) use ( $arIds, $rev ) {
						// Create new rev_ids by inserting dummy rows into revision and then deleting them.
						$dbw->insert( 'revision', array_fill( 0, count( $arIds ), $rev ), $fname );
						$revIds = $dbw->selectFieldValues(
							'revision',
							'rev_id',
							[ 'rev_timestamp' => $rev['rev_timestamp'] ],
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
				} catch ( UnexpectedValueException $ex ) {
					$this->fatalError( $ex->getMessage() );
				}

				foreach ( $updates as $arId => $revId ) {
					$dbw->update(
						'archive',
						[ 'ar_rev_id' => $revId ],
						[ 'ar_id' => $arId ],
						__METHOD__
					);
					$reassigned += $dbw->affectedRows();
				}
			}

			$this->output( "... $id-$endId\n" );
			$this->commitTransaction( $dbw, __METHOD__ );
		}

		$this->output(
			"Finished deduplicating ar_rev_id. $deleted rows deleted, $reassigned assigned new IDs.\n"
		);
		return true;
	}

	/**
	 * Make a key identifying a "unique" change from a row
	 * @param object $row
	 * @return string
	 */
	private function getSeenKey( $row ) {
		return implode( "\n", [
			$row->ar_namespace,
			$row->ar_title,
			$row->ar_timestamp,
			$row->ar_sha1,
			$row->ar_user,
			$row->ar_user_text,
		] );
	}

	/**
	 * Construct a dummy revision table row to use for reserving IDs
	 *
	 * The row will have a wildly unlikely timestamp, and possibly a generic
	 * user and comment, but will otherwise be derived from a revision on the
	 * wiki's main page.
	 *
	 * @param IDatabase $dbw
	 * @return array
	 */
	private function makeDummyRevisionRow( IDatabase $dbw ) {
		$ts = $dbw->timestamp( '11111111111111' );
		$mainPage = Title::newMainPage();
		if ( !$mainPage ) {
			$this->fatalError( 'Main page does not exist' );
		}
		$pageId = $mainPage->getArticleId();
		if ( !$pageId ) {
			$this->fatalError( $mainPage->getPrefixedText() . ' has no ID' );
		}
		$rev = $dbw->selectRow(
			'revision',
			'*',
			[ 'rev_page' => $pageId ],
			__METHOD__,
			[ 'ORDER BY' => 'rev_timestamp ASC' ]
		);
		if ( !$rev ) {
			$this->fatalError( $mainPage->getPrefixedText() . ' has no revisions' );
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
			$this->fatalError( "... Why does your database contain a revision dated $ts?" );
		}

		return $rev;
	}
}

$maintClass = "DeduplicateArchiveRevId";
require_once RUN_MAINTENANCE_IF_MAIN;
