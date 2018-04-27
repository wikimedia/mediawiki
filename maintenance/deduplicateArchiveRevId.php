<?php

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that cleans up archive rows with duplicated ar_rev_id,
 * both within archive and between archive and revision.
 *
 * @ingroup Maintenance
 * @since 1.32
 */
class DeduplicateArchiveRevId extends LoggedUpdateMaintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription(
			'Clean up duplicate ar_rev_id, both within archive and between archive and revision.'
		);
		$this->setBatchSize( 10000 );
	}

	protected function getUpdateKey() {
		return __CLASS__;
	}

	protected function doDBUpdates() {
		$this->output( "Deduplicating ar_rev_id...\n" );

		$dbw = $this->getDB( DB_MASTER );

		$deleted = 0;
		$reassigned = 0;

		$maxId = $dbw->selectField( 'archive', 'MAX(ar_rev_id)', [], __METHOD__ );
		$batchSize = $this->getBatchSize();

		$arActorQuery = ActorMigration::newMigration()->getJoin( 'ar_user' );
		$revActorQuery = ActorMigration::newMigration()->getJoin( 'rev_user' );

		$any = false;
		for ( $id = 1; $id <= $maxId; $id += $batchSize ) {
			$endId = min( $maxId, $id + $batchSize - 1 );

			$this->beginTransaction( $dbw, __METHOD__ );

			// Lock the archive and revision table rows for the IDs we're checking
			// to try to prevent deletions or undeletions from confusing things.
			$dbw->selectRowCount(
				'archive',
				1,
				[
					'ar_rev_id >= ' . (int)$id,
					'ar_rev_id <= ' . (int)$endId,
				],
				__METHOD__,
				[ 'FOR UPDATE' ]
			);
			$dbw->selectRowCount(
				'revision',
				1,
				[
					'rev_id >= ' . (int)$id,
					'rev_id <= ' . (int)$endId,
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
					'ar_rev_id <= ' . (int)$endId,
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
				$reassigned += PopulateArchiveRevId::reassignArRevIds( $dbw, $toReassign );
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

}

$maintClass = "DeduplicateArchiveRevId";
require_once RUN_MAINTENANCE_IF_MAIN;
