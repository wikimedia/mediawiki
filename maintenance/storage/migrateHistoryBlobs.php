<?php

use MediaWiki\ExternalStore\DiffMultiContentBlob;
use MediaWiki\ExternalStore\ConcatenatedMultiContentBlob;

require_once __DIR__ . '/../Maintenance.php';

/**
 * Migrate revision storage from pre-1.31 HistoryBlob objects
 * @ingroup Maintenance
 */
class MigrateHistoryBlobs extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Migrates revision storage from pre-1.31 HistoryBlob objects' );
		$this->addOption(
			'for-real', 'Actually perform the migration (otherwise, just prints what it would do)',
			false, false
		);
		$this->addOption( 'external', 'Migrate an ExternalStoreDB cluster, rather than the text table',
			false, true );
		$this->addOption( 'unknown-ok', 'Handle unknown object types', false, false );
		$this->addOption( 'no-stubs', 'Don\'t check for HistoryBlobStub', false, false );
		$this->setBatchSize( 1000 );
	}

	public function execute() {
		$dryRun = !$this->hasOption( 'for-real' );
		$unknownOk = $this->hasOption( 'unknown-ok' );

		$isExt = $this->hasOption( 'external' );
		if ( $isExt ) {
			$cluster = $this->getOption( 'external', null );
			$lb = wfGetLBFactory()->getExternalLB( $cluster );
			$dbr = $lb->getConnection( DB_REPLICA );
			$table = $dbr->getLBInfo( 'blobs table' );
			if ( $table === null ) {
				$table = 'blobs';
			}
			if ( !$dbr->tableExists( $table ) ) {
				$this->fatal( "No blobs table on cluster $cluster" );
			}

			if ( $dryRun ) {
				$dbw = null;
			} else {
				$dbw = $lb->getConnection( DB_MASTER );
				$table2 = $dbw->getLBInfo( 'blobs table' );
				if ( $table2 === null ) {
					$table2 = 'blobs';
				}
				if ( $table2 !== $table ) {
					$this->fatal(
						"Master handle says the blobs table is \"$table2\", but replica says it's \"$table\""
					);
				}
				if ( !$dbw->tableExists( $table ) ) {
					$this->fatal( "No blobs table on cluster $cluster master handle" );
				}
			}

			$fields = [ 'id' => 'blob_id', 'txt' => 'blob_text' ];
			$conds = [ 'blob_text' . $dbr->buildLike( 'O:', $dbr->anyString() ) ];
			$this->output(
				"Beginning HistoryBlob migration of ExternalStore $cluster $table.{$fields['txt']}\n"
			);
		} else {
			$dbw = $dryRun ? null : wfGetDB( DB_MASTER );
			$dbr = wfGetDB( DB_REPLICA, 'vslow' );
			$table = 'text';
			$fields = [ 'id' => 'old_id', 'txt' => 'old_text', 'flags' => 'old_flags' ];
			$conds = [ 'old_flags' . $dbr->buildLike( $dbr->anyString(), 'object', $dbr->anyString() ) ];
			$this->output( "Beginning HistoryBlob migration of $table.{$fields['txt']}\n" );
		}

		$idField = $fields['id'];
		wfWaitForSlaves();

		$maxId = (int)$dbr->selectField( $table, "MAX( $idField )", [], __METHOD__ );

		$migrated = 0;
		$errors = 0;

		// We need to do two passes, one for HistoryBlobStub and one for the rest.
		// Otherwise if the stub's target comes before the HistoryBlobStub, it
		// would be overwritten by the time we see the stub making the stub invalid.
		for ( $stage = 0; $stage <= 1; $stage++ ) {
			if ( $stage === 0 ) {
				if ( $isExt || $this->hasOption( 'no-stubs' ) ) {
					continue;
				}
				$this->output( "... Checking for HistoryBlobStub\n" );
			} else {
				$this->output( "... Checking for HistoryBlob\n" );
			}

			$startId = 0;
			while ( $startId < $maxId ) {
				$endId = min( $maxId, $startId + $this->getBatchSize() );
				$this->output( " ... $startId - $endId\n" );
				$res = $dbr->select(
					$table,
					$fields,
					array_merge( $conds, [
						"$idField > $startId",
						"$idField <= $endId",
					] ),
					__METHOD__
				);

				foreach ( $res as $row ) {
					// Get the serialized text from the row, with some validation.
					if ( $isExt ) {
						if ( !preg_match( '/^O:(\d+):"([^"]+)"/', $row->txt, $m ) ) {
							continue;
						}

						// Validate the class name before unserializing below,
						// otherwise someone could have created a page with
						// contents being a somehow-malicious object. Normal
						// access doesn't have this problem because it knows
						// from the text table whether it's supposed to be an
						// object or not.
						//
						// Note this will break things if someone somehow made
						// a page with content that is a valid serialized
						// DiffHistoryBlob or ConcatenatedGzipHistoryBlob.
						// Nothing we can do about that.
						if ( strlen( $m[2] ) !== (int)$m[1] ) {
							// Either it's corrupted or it's a very weird page.
							continue;
						}
						if ( !is_a( $m[2], DiffHistoryBlob::class, true ) &&
							!is_a( $m[2], ConcatenatedGzipHistoryBlob::class, true )
						) {
							$this->error( "Row $row->id has unsupported class {$m[2]}" );
							$errors++;
							continue;
						}
					} else {
						$flags = explode( ',', $row->flags );
						if ( !in_array( 'object', $flags, true ) ) {
							// Should never happen, but doesn't hurt to check.
							continue;
						}
						if ( in_array( 'external', $flags, true ) ) {
							// Crazy, but possible. Hack things up so the code
							// below does the right thing.

							// First, load the external object
							$url = $row->txt;
							$parts = explode( '://', $url, 2 );
							if ( count( $parts ) == 1 || $parts[1] == '' ) {
								$this->error( "Row $row->id is crazy: flags=$row->flags text=$row->txt" );
								$errors++;
								continue;
							}
							$text = ExternalStore::fetchFromURL( $url, [ 'wiki' => $wiki ] );
							if ( !$text ) {
								$this->error( "Row $row->id refers to an external object that doesn't exist" );
								$errors++;
								continue;
							}
							$obj = unserialize( $text );
							if ( !$text ) {
								$this->error( "Row $row->id refers to an external object that can't be unserialzied" );
								$errors++;
								continue;
							}

							// If it's a DiffHistoryBlob or ConcatenatedGzipHistoryBlob,
							// extract the existing "default" item ID, rebuild the
							// URL, and put it in a new ConcatenatedGzipHistoryBlob
							// that'll output the right external URL. Otherwise,
							// just pretend the external object was in the row
							// directly without the 'external' flag if --unknown-ok.
							if ( $obj instanceof DiffHistoryBlob ) {
								$url .= '/' . $obj->mDefaultKey;
								$obj = new ConcatenatedGzipHistoryBlob();
								$obj->setText( $url );
								$row->txt = serialize( $obj );
							} elseif ( $obj instanceof ConcatenatedGzipHistoryBlob ) {
								$url .= '/' . $obj->mDefaultHash;
								$obj = new ConcatenatedGzipHistoryBlob();
								$obj->setText( $url );
								$row->txt = serialize( $obj );
							} elseif ( $unknownOk ) {
								$row->txt = $text;
								$flags = array_diff( $flags, [ 'external' ] );
							} else {
								$this->error(
									"Row $row->id refers to an external object of unknown class " . get_class( $obj )
								);
								$errors++;
								continue;
							}
						}
					}

					// Unserialize it.
					$obj = unserialize( $row->txt );
					if ( !$obj ) {
						$this->error( "Row $row->id could not be unserialized" );
						$errors++;
						continue;
					}

					// Decide what to do with it.
					$values = [];

					if ( $stage === 0 ) {
						// Stage 0 can only happen with !$isExt. Process only HistoryBlobStub.

						if ( !$obj instanceof HistoryBlobStub ) {
							continue;
						}

						// The target row might be an external object. If so,
						// construct the proper target URL to use directly here.
						// Otherwise, just extract the text (the stage-1 pass
						// will do the same to the target, eventually).
						$externalRow = $dbr->selectRow(
							'text',
							[ 'old_text' ],
							[
								'old_id' => $obj->mOldId,
								'old_flags' . $dbr->buildLike( $dbr->anyString(), 'external', $dbr->anyString() )
							],
							__METHOD__
						);
						if ( $externalRow ) {
							$values['old_text'] = $externalRow->old_text . '/' . $obj->mHash;
							$flags[] = 'external';
						} else {
							$values['old_text'] = $obj->getText();
						}
						$values['old_flags'] = join( ',', array_diff( $flags, [ 'object' ] ) );
					} else {
						// Stage 1: Replace all non-HistoryBlobStub objects.

						if ( $isExt ) {
							if ( $obj instanceof DiffHistoryBlob ) {
								$values['blob_text'] = DiffMultiContentBlob::newFromDiffHistoryBlob( $obj )->encode();
							} elseif ( $obj instanceof ConcatenatedGzipHistoryBlob ) {
								$values['blob_text'] =
									ConcatenatedMultiContentBlob::newFromConcatenatedGzipHistoryBlob( $obj )->encode();
							} else {
								// Should never get here, but it doesn't hurt to check.
								$this->error( "Row $row->id has unsupported object " . get_class( $obj ) );
								$errors++;
								continue;
							}
						} else {
							if ( $obj instanceof DiffHistoryBlob ||
								$obj instanceof ConcatenatedGzipHistoryBlob ||
								$obj instanceof HistoryBlobCurStub ||
								$unknownOk
							) {
								$values['old_text'] = $obj->getText();
								$values['old_flags'] = join( ',', array_diff( $flags, [ 'object' ] ) );
							} else {
								$this->error( "Row $row->id has unsupported object " . get_class( $obj ) );
								$errors++;
								continue;
							}
						}
					}

					// Update (or not if it's a dry run)
					if ( $dbw ) {
						$dbw->update(
							$table,
							$values,
							[ $idField => $row->id ],
							__METHOD__
						);
						$migrated++;
					} else {
						$this->output( " ... Would update row $row->id of class " . get_class( $obj ) . "\n" );
					}
				}

				$startId = $endId;
				wfWaitForSlaves();
			}
		}

		$this->output( "Completed migration, updated $migrated row(s) with $errors error(s)\n" );
		return true;
	}

}

$maintClass = 'MigrateHistoryBlobs';
require_once RUN_MAINTENANCE_IF_MAIN;
