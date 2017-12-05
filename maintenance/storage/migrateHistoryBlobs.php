<?php

use MediaWiki\ExternalStore\ConcatenatedMultiContentBlob;
use MediaWiki\ExternalStore\MultiContentBlob;
use MediaWiki\ExternalStore\XdiffMultiContentBlob;

require_once __DIR__ . '/../Maintenance.php';

/**
 * Migrate revision storage from pre-1.31 HistoryBlob objects
 * @ingroup Maintenance ExternalStorage
 */
class MigrateHistoryBlobs extends Maintenance {
	private $esInfo = [];
	private $dryRun = true;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Migrates revision storage from pre-1.31 HistoryBlob objects' );
		$this->addOption(
			'for-real', 'Actually perform the migration (otherwise, just prints what it would do)',
			false, false
		);
		$this->addOption( 'unknown-ok', 'Handle unknown object types', false, false );
		$this->addOption(
			'write-es-cluster',
			'Write modified ExternalStore blobs to this cluster, instead of modifying in place',
			false, true
		);
		$this->setBatchSize( 1000 );
	}

	public function execute() {
		global $wgDefaultExternalStore;

		$esCache = [];

		$this->dryRun = !$this->hasOption( 'for-real' );
		$writeCluster = $this->getOption( 'write-es-cluster', null );
		$unknownOk = $this->hasOption( 'unknown-ok' );

		if ( !$writeCluster && $writeCluster !== null ) {
			$this->fatalError( 'Cannot use a --write-es-cluster that is falsey, sorry' );
		}

		$dbr = wfGetDB( DB_REPLICA, 'vslow' );
		$dbw = $this->dryRun ? null : wfGetDB( DB_MASTER );

		$passes = [
			1 => 'HistoryBlobStub',
			2 => 'external objects',
			3 => 'all other HistoryBlobs',
		];
		$passConds = [
			1 => $dbr->makeList( [
				'old_flags' . $dbr->buildLike( $dbr->anyString(), 'object', $dbr->anyString() ),
				'old_text' . $dbr->buildLike( 'O:15:"', $dbr->anyString() ),
			], LIST_AND ),
			2 => $dbr->makeList( [
				'old_flags' . $dbr->buildLike( $dbr->anyString(), 'object', $dbr->anyString() ),
				'old_flags' . $dbr->buildLike( $dbr->anyString(), 'external', $dbr->anyString() ),
			], LIST_AND ),
			3 => $dbr->makeList( [
				'old_flags' . $dbr->buildLike( $dbr->anyString(), 'object', $dbr->anyString() ),
				$dbr->makeList( [
					'old_flags' . $dbr->buildLike( $dbr->anyString(), 'external', $dbr->anyString() ),
					'old_text' . $dbr->buildLike(
						'DB://', $dbr->anyString(), '/', $dbr->anyString(), '/', $dbr->anyString()
					),
				], LIST_AND ),
			], LIST_OR ),
		];

		$migrated = 0;
		$errors = 0;

		$startId = 0;
		$maxId = (int)$dbr->selectField( 'text', "MAX( old_id )", [], __METHOD__ );
		while ( $startId < $maxId ) {
			$endId = min( $maxId, $startId + $this->getBatchSize() );
			$this->output( " ... $startId - $endId\n" );

			// We need to do three passes:
			// * HistoryBlobStub, which has to be done before anything it points to is updated.
			// * External objects, which have to be done before the target blob is updated
			// * and one for the rest. Otherwise if the stub's target comes
			//   before the HistoryBlobStub, it would be overwritten by the time we
			//   see the stub making the stub invalid.
			foreach ( $passes as $pass => $description ) {
				$res = $dbr->select(
					'text',
					[ 'old_id', 'old_flags', 'old_text' ],
					[
						$passConds[$pass],
						"old_id > $startId",
						"old_id <= $endId",
					],
					__METHOD__
				);

				foreach ( $res as $row ) {
					// Get the serialized text from the row, with some validation.
					$id = $row->old_id;
					$text = $row->old_text;
					$flags = explode( ',', $row->old_flags );

					// Decide what to do with it.
					$wdbw = $dbw;
					$table = 'text';
					$values = [];
					$where = [ 'old_id' => $id ];

					if ( strtolower( substr( $text, 0, 22 ) ) === 'o:15:"historyblobstub"' ) {
						$obj = unserialize( $text );
						if ( !$obj ) {
							$this->error( "Row $id could not be unserialized" );
							$errors++;
							continue;
						}

						// The target row might be an external object. If so,
						// construct the proper target URL to use directly here.
						// Otherwise, just extract the text (a later pass
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
					} elseif ( $pass === 1 ) {
						// Skip the rest when on pass 1.
						continue;
					} elseif ( in_array( 'external', $flags, true ) && in_array( 'object', $flags, true ) ) {
						// An external object is probably a DiffHistoryBlob
						// or ConcatenatedGzipHistoryBlob using the default
						// hash/key via the getText() method. We can update
						// it to use a 3-part ES URL that is accessed via
						// getItem().
						// If it's some other external object, just resave it
						// if --unknown-ok was specified.

						$url = $text;
						$parts = explode( '://', $url, 2 );
						if ( count( $parts ) == 1 || $parts[1] == '' ) {
							$this->error( "Row $id has a bogus external URL: flags=$row->old_flags text=$text" );
							$errors++;
							continue;
						}
						$text = ExternalStore::fetchFromURL( $url );
						if ( !$text ) {
							$this->error( "Row $id refers to an external object that doesn't exist" );
							$errors++;
							continue;
						}
						$obj = unserialize( $text );
						if ( !$text ) {
							$this->error( "Row $id refers to an external object that can't be unserialzied" );
							$errors++;
							continue;
						}

						if ( $obj instanceof DiffHistoryBlob ) {
							$values['old_text'] = $url . '/' . $obj->mDefaultKey;
							$values['old_flags'] = join( ',', array_diff( $flags, [ 'object' ] ) );
						} elseif ( $obj instanceof ConcatenatedGzipHistoryBlob ) {
							$values['old_text'] = $url . '/' . $obj->mDefaultHash;
							$values['old_flags'] = join( ',', array_diff( $flags, [ 'object' ] ) );
						} elseif ( $unknownOk ) {
							$values['old_text'] = $obj->getText();
							$values['old_flags'] = join( ',', array_diff( $flags, [ 'external', 'object' ] ) );
						} else {
							$this->error(
								"Row $id refers to an external object of unknown class " . get_class( $obj )
							);
							$errors++;
							continue;
						}
					} elseif ( $pass === 2 ) {
						// Skip the rest when on pass 2.
						continue;
					} elseif ( in_array( 'external', $flags, true ) ) {
						// A 3-part non-object external reference means we have
						// to update the ES row to use MultiContentBlob instead
						// of HistoryBlob.

						$parts = explode( '://', $text, 2 );
						if ( count( $parts ) !== 2 || $parts[0] !== 'DB' ) {
							$this->error(
								"Row $id has an unsupported external URL scheme: flags=$row->old_flags text=$text"
							);
							$errors++;
							continue;
						}
						$parts = explode( '/', $parts[1] );
						if ( count( $parts ) !== 3 ) {
							$this->error( "Row $id has a bad external URL path: flags=$row->old_flags text=$text" );
							$errors++;
							continue;
						}
						list( $cluster, $blobId, $hash ) = $parts;

						list( $blobTable, $bdbr, $bdbw ) = $this->getClusterInfo( $cluster );
						$blob = $bdbr->selectField( $blobTable, 'blob_text', [ 'blob_id' => $blobId ], __METHOD__ );
						if ( $blob === false ) {
							$this->error( "Row $id refers to an external object that doesn't exist" );
							$errors++;
							continue;
						}
						if ( !preg_match( '/^O:\d+:"/', $blob ) ) {
							if ( !MultiContentBlob::checkHeader( $blob ) ) {
								$this->error( "Row $id refers to an external object that isn't an object" );
								$errors++;
							}
							continue;
						}
						$obj = unserialize( $blob );
						if ( $obj instanceof DiffHistoryBlob ) {
							$newObj = XdiffMultiContentBlob::newFromDiffHistoryBlob( $obj );
						} elseif ( $obj instanceof ConcatenatedGzipHistoryBlob ) {
							$newObj = ConcatenatedMultiContentBlob::newFromConcatenatedGzipHistoryBlob( $obj );
						} else {
							$this->error( "Row $id has unsupported external object " . get_class( $obj ) );
							$errors++;
							continue;
						}

						if ( $writeCluster && $writeCluster !== $cluster ) {
							// If we're supposed to write to a different ES cluster,
							// insert it there and update the text table row.
							if ( $this->dryRun ) {
								$this->output( "ExternalStore::insert( \"DB://$writeCluster\", "
									. substr( $newObj->encode(), 0, 30 ) . "... )\n" );
								$url = "DB://$writeCluster/#ID#/$hash";
							} else {
								if ( !isset( $esCache[$cluster][$blobId] ) ) {
									$url = ExternalStore::insert( "DB://$writeCluster", $newObj->encode() );
									if ( !$url ) {
										$this->fatalError( "Failed to write to ES cluster $writeCluster" );
									}
									$esCache[$cluster][$blobId] = $url;
								}
								$url = $esCache[$cluster][$blobId] . "/$hash";
							}
							$values = [ 'old_text' => $url ];
						} else {
							// Otherwise, update the ES blob row.
							$wdbw = $bdbw;
							$table = $blobTable;
							$values = [ 'blob_text' => $newObj->encode() ];
							$where = [ 'blob_id' => $blobId ];
						}
					} else {
						// Otherwise it's a local object. We just resave these.

						$obj = unserialize( $text );
						if ( !$obj ) {
							$this->error( "Row $id could not be unserialized" );
							$errors++;
							continue;
						}
						$values = [];
						if ( $obj instanceof HistoryBlobStub ) {
							// This should never happen since HistoryBlobStub was
							// supposed to be handled above. If it does, complain loudly.
							$this->fatalError( "Unexpected HistoryBlobStub in row $id! Check for data corruption." );
						} elseif ( $obj instanceof DiffHistoryBlob ||
							$obj instanceof ConcatenatedGzipHistoryBlob ||
							$obj instanceof HistoryBlobCurStub ||
							$unknownOk
						) {
							$values['old_text'] = $obj->getText();
							$values['old_flags'] = join( ',', array_diff( $flags, [ 'object' ] ) );
						} else {
							$this->error( "Row $id has unsupported object " . get_class( $obj ) );
							$errors++;
							continue;
						}
					}

					// DWIM: If the update is going to write significant content
					// into the text table and the wiki is configured to use ES,
					// put that content into ES instead.
					if ( $table === 'text' && isset( $values['old_flags'] ) && isset( $values['old_text'] ) &&
						$wgDefaultExternalStore &&
						strlen( $values['old_text'] ) > 100 && // "100" is arbitrary
						!in_array( 'external', explode( ',', $values['old_flags'] ), true )
					) {
						if ( $this->dryRun ) {
							$this->output( "ExternalStore::insertToDefault( "
								. substr( $values['old_text'], 0, 30 ) . "... )\n" );
							$url = "DB://#CLUSTER#/#ID#";
						} else {
							$url = ExternalStore::insertToDefault( $values['old_text'] );
							if ( !$url ) {
								$this->fatalError( 'Failed to write content to default ExternalStore' );
							}
						}
						if ( $values['old_flags'] ) {
							$values['old_flags'] .= ',';
						}
						$values['old_flags'] .= 'external';
						$values['old_text'] = $url;
					}

					// Update (or not if it's a dry run)
					if ( $this->dryRun ) {
						$values = array_map( function ( $v ) {
							return strlen( $v ) > 30 ? substr( $v, 0, 30 ) . '...' : $v;
						}, $values );
						$this->output(
							" ... UPDATE $table"
							. " SET " . $dbr->makeList( $values, LIST_SET )
							. " WHERE " . $dbr->makeList( $where, LIST_AND )
							. "\n"
						);
					} else {
						$wdbw->update( $table, $values, $where, __METHOD__ );
						$migrated++;
					}
				}
			}

			$startId = $endId;
			if ( !$this->dryRun ) {
				$this->esInfo = [];
				wfWaitForSlaves();
			}
		}

		$this->output( "Completed migration, updated $migrated row(s) with $errors error(s)\n" );
		return true;
	}

	private function getClusterInfo( $cluster ) {
		if ( !isset( $this->esInfo[$cluster] ) ) {
			$lb = wfGetLBFactory()->getExternalLB( $cluster );
			$dbr = $lb->getConnectionRef( DB_REPLICA );
			$table = $dbr->getLBInfo( 'blobs table' );
			if ( $table === null ) {
				$table = 'blobs';
			}
			if ( !$dbr->tableExists( $table ) ) {
				$this->fatalError( "No blobs table $table on cluster $cluster" );
			}

			if ( $this->dryRun ) {
				$dbw = null;
			} else {
				$dbw = $lb->getConnectionRef( DB_MASTER );
				$table2 = $dbw->getLBInfo( 'blobs table' );
				if ( $table2 === null ) {
					$table2 = 'blobs';
				}
				if ( $table2 !== $table ) {
					$this->fatalError(
						"Master handle says the blobs table is \"$table2\", but replica says it's \"$table\""
					);
				}
				if ( !$dbw->tableExists( $table ) ) {
					$this->fatalError( "No blobs table on cluster $cluster master handle" );
				}
			}

			$this->esInfo[$cluster] = [ $table, $dbr, $dbw ];
		}

		return $this->esInfo[$cluster];
	}

}

$maintClass = 'MigrateHistoryBlobs';
require_once RUN_MAINTENANCE_IF_MAIN;
