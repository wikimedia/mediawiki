<?php
/**
 * Fsck for MediaWiki
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
 * @ingroup Maintenance ExternalStorage
 */

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\Shell\Shell;
use MediaWiki\User\User;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/../Maintenance.php';
// @codeCoverageIgnoreEnd

// ----------------------------------------------------------------------------------

/**
 * Maintenance script to do various checks on external storage.
 *
 * @fixme this should extend the base Maintenance class
 * @ingroup Maintenance ExternalStorage
 */
class CheckStorage extends Maintenance {
	private const CONCAT_HEADER = 'O:27:"concatenatedgziphistoryblob"';

	public array $oldIdMap;
	public array $errors;

	/** @var ExternalStoreDB */
	public $dbStore = null;

	public function __construct() {
		parent::__construct();

		$this->addOption( 'fix', 'Fix errors if possible' );
		$this->addArg( 'xml', 'Path to an XML dump', false );
	}

	public function execute() {
		$fix = $this->hasOption( 'fix' );
		$xml = $this->getArg( 'xml', false );
		$this->check( $fix, $xml );
	}

	/** @var string[] */
	public $errorDescriptions = [
		'restore text' => 'Damaged text, need to be restored from a backup',
		'restore revision' => 'Damaged revision row, need to be restored from a backup',
		'unfixable' => 'Unexpected errors with no automated fixing method',
		'fixed' => 'Errors already fixed',
		'fixable' => 'Errors which would already be fixed if --fix was specified',
	];

	public function check( bool $fix = false, string|false $xml = '' ) {
		$dbr = $this->getReplicaDB();
		if ( $fix ) {
			print "Checking, will fix errors if possible...\n";
		} else {
			print "Checking...\n";
		}
		$maxRevId = $dbr->newSelectQueryBuilder()
			->select( 'MAX(rev_id)' )
			->from( 'revision' )
			->caller( __METHOD__ )->fetchField();
		$chunkSize = 1000;
		$flagStats = [];
		$objectStats = [];
		$knownFlags = [ 'external', 'gzip', 'object', 'utf-8' ];
		$this->errors = [
			'restore text' => [],
			'restore revision' => [],
			'unfixable' => [],
			'fixed' => [],
			'fixable' => [],
		];

		for ( $chunkStart = 1; $chunkStart < $maxRevId; $chunkStart += $chunkSize ) {
			$chunkEnd = $chunkStart + $chunkSize - 1;
			// print "$chunkStart of $maxRevId\n";

			$this->oldIdMap = [];
			$dbr->ping();

			// Fetch revision rows
			$res = $dbr->newSelectQueryBuilder()
				->select( [ 'slot_revision_id', 'content_address' ] )
				->from( 'slots' )
				->join( 'content', null, 'content_id = slot_content_id' )
				->where( [
					$dbr->expr( 'slot_revision_id', '>=', $chunkStart ),
					$dbr->expr( 'slot_revision_id', '<=', $chunkEnd ),
				] )
				->caller( __METHOD__ )->fetchResultSet();
			/** @var \MediaWiki\Storage\SqlBlobStore $blobStore */
			$blobStore = $this->getServiceContainer()->getBlobStore();
			'@phan-var \MediaWiki\Storage\SqlBlobStore $blobStore';
			foreach ( $res as $row ) {
				$textId = $blobStore->getTextIdFromAddress( $row->content_address );
				if ( $textId ) {
					if ( !isset( $this->oldIdMap[$textId] ) ) {
						$this->oldIdMap[ $textId ] = [ $row->slot_revision_id ];
					} elseif ( !in_array( $row->slot_revision_id, $this->oldIdMap[$textId] ) ) {
						$this->oldIdMap[ $textId ][] = $row->slot_revision_id;
					}
				}
			}

			if ( !count( $this->oldIdMap ) ) {
				continue;
			}

			// Fetch old_flags
			$missingTextRows = $this->oldIdMap;
			$externalRevs = [];
			$objectRevs = [];
			$res = $dbr->newSelectQueryBuilder()
				->select( [ 'old_id', 'old_flags' ] )
				->from( 'text' )
				->where( [ 'old_id' => array_keys( $this->oldIdMap ) ] )
				->caller( __METHOD__ )->fetchResultSet();
			foreach ( $res as $row ) {
				/**
				 * @var int $flags
				 */
				$flags = $row->old_flags;
				$id = $row->old_id;

				// Create flagStats row if it doesn't exist
				$flagStats += [ $flags => 0 ];
				// Increment counter
				$flagStats[$flags]++;

				// Not missing
				unset( $missingTextRows[$row->old_id] );

				// Check for external or object
				if ( $flags == '' ) {
					$flagArray = [];
				} else {
					$flagArray = explode( ',', $flags );
				}
				if ( in_array( 'external', $flagArray ) ) {
					$externalRevs[] = $id;
				} elseif ( in_array( 'object', $flagArray ) ) {
					$objectRevs[] = $id;
				}

				// Check for unrecognised flags
				if ( $flags == '0' ) {
					// This is a known bug from 2004
					// It's safe to just erase the old_flags field
					if ( $fix ) {
						$this->addError( 'fixed', "Warning: old_flags set to 0", $id );
						$dbw = $this->getPrimaryDB();
						$dbw->ping();
						$dbw->newUpdateQueryBuilder()
							->update( 'text' )
							->set( [ 'old_flags' => '' ] )
							->where( [ 'old_id' => $id ] )
							->caller( __METHOD__ )
							->execute();
						echo "Fixed\n";
					} else {
						$this->addError( 'fixable', "Warning: old_flags set to 0", $id );
					}
				} elseif ( count( array_diff( $flagArray, $knownFlags ) ) ) {
					$this->addError( 'unfixable', "Error: invalid flags field \"$flags\"", $id );
				}
			}

			// Output errors for any missing text rows
			foreach ( $missingTextRows as $oldId => $revIds ) {
				$this->addError( 'restore revision', "Error: missing text row", $oldId );
			}

			// Verify external revisions
			$externalConcatBlobs = [];
			$externalNormalBlobs = [];
			if ( count( $externalRevs ) ) {
				$res = $dbr->newSelectQueryBuilder()
					->select( [ 'old_id', 'old_flags', 'old_text' ] )
					->from( 'text' )
					->where( [ 'old_id' => $externalRevs ] )
					->caller( __METHOD__ )->fetchResultSet();
				foreach ( $res as $row ) {
					$urlParts = explode( '://', $row->old_text, 2 );
					if ( count( $urlParts ) !== 2 || $urlParts[1] == '' ) {
						$this->addError( 'restore text', "Error: invalid URL \"{$row->old_text}\"", $row->old_id );
						continue;
					}
					[ $proto, ] = $urlParts;
					if ( $proto != 'DB' ) {
						$this->addError(
							'restore text',
							"Error: invalid external protocol \"$proto\"",
							$row->old_id );
						continue;
					}
					$path = explode( '/', $row->old_text );
					$cluster = $path[2];
					$id = $path[3];
					if ( isset( $path[4] ) ) {
						$externalConcatBlobs[$cluster][$id][] = $row->old_id;
					} else {
						$externalNormalBlobs[$cluster][$id][] = $row->old_id;
					}
				}
			}

			// Check external concat blobs for the right header
			$this->checkExternalConcatBlobs( $externalConcatBlobs );

			// Check external normal blobs for existence
			if ( count( $externalNormalBlobs ) ) {
				if ( $this->dbStore === null ) {
					$esFactory = $this->getServiceContainer()->getExternalStoreFactory();
					$this->dbStore = $esFactory->getStore( 'DB' );
				}
				foreach ( $externalConcatBlobs as $cluster => $xBlobIds ) {
					$blobIds = array_keys( $xBlobIds );
					$extDb = $this->dbStore->getReplica( $cluster );
					$blobsTable = $this->dbStore->getTable( $cluster );
					$res = $extDb->newSelectQueryBuilder()
						->select( [ 'blob_id' ] )
						->from( $blobsTable )
						->where( [ 'blob_id' => $blobIds ] )
						->caller( __METHOD__ )->fetchResultSet();
					foreach ( $res as $row ) {
						unset( $xBlobIds[$row->blob_id] );
					}
					// Print errors for missing blobs rows
					foreach ( $xBlobIds as $blobId => $oldId ) {
						$this->addError(
							'restore text',
							"Error: missing target $blobId for one-part ES URL",
							$oldId );
					}
				}
			}

			// Check local objects
			$dbr->ping();
			$concatBlobs = [];
			$curIds = [];
			if ( count( $objectRevs ) ) {
				$headerLength = 300;
				$res = $dbr->newSelectQueryBuilder()
					->select( [ 'old_id', 'old_flags', "LEFT(old_text, $headerLength) AS header" ] )
					->from( 'text' )
					->where( [ 'old_id' => $objectRevs ] )
					->caller( __METHOD__ )->fetchResultSet();
				foreach ( $res as $row ) {
					$oldId = $row->old_id;
					$matches = [];
					if ( !preg_match( '/^O:(\d+):"(\w+)"/', $row->header, $matches ) ) {
						$this->addError( 'restore text', "Error: invalid object header", $oldId );
						continue;
					}

					$className = strtolower( $matches[2] );
					if ( strlen( $className ) != $matches[1] ) {
						$this->addError(
							'restore text',
							"Error: invalid object header, wrong class name length",
							$oldId
						);
						continue;
					}

					$objectStats += [ $className => 0 ];
					$objectStats[$className]++;

					switch ( $className ) {
						case 'concatenatedgziphistoryblob':
							// Good
							break;
						case 'historyblobstub':
						case 'historyblobcurstub':
							if ( strlen( $row->header ) == $headerLength ) {
								$this->addError( 'unfixable', "Error: overlong stub header", $oldId );
								break;
							}
							$stubObj = unserialize( $row->header );
							if ( !is_object( $stubObj ) ) {
								$this->addError( 'restore text', "Error: unable to unserialize stub object", $oldId );
								break;
							}
							if ( $className == 'historyblobstub' ) {
								$concatBlobs[$stubObj->getLocation()][] = $oldId;
							} else {
								$curIds[$stubObj->mCurId][] = $oldId;
							}
							break;
						default:
							$this->addError( 'unfixable', "Error: unrecognised object class \"$className\"", $oldId );
					}
				}
			}

			// Check local concat blob validity
			$externalConcatBlobs = [];
			if ( count( $concatBlobs ) ) {
				$headerLength = 300;
				$res = $dbr->newSelectQueryBuilder()
					->select( [ 'old_id', 'old_flags', "LEFT(old_text, $headerLength) AS header" ] )
					->from( 'text' )
					->where( [ 'old_id' => array_keys( $concatBlobs ) ] )
					->caller( __METHOD__ )->fetchResultSet();
				foreach ( $res as $row ) {
					$flags = explode( ',', $row->old_flags );
					if ( in_array( 'external', $flags ) ) {
						// Concat blob is in external storage?
						if ( in_array( 'object', $flags ) ) {
							$urlParts = explode( '/', $row->header );
							if ( $urlParts[0] != 'DB:' ) {
								$this->addError(
									'unfixable',
									"Error: unrecognised external storage type \"{$urlParts[0]}",
									$row->old_id
								);
							} else {
								$cluster = $urlParts[2];
								$id = $urlParts[3];
								if ( !isset( $externalConcatBlobs[$cluster][$id] ) ) {
									$externalConcatBlobs[$cluster][$id] = [];
								}
								$externalConcatBlobs[$cluster][$id] = array_merge(
									$externalConcatBlobs[$cluster][$id], $concatBlobs[$row->old_id]
								);
							}
						} else {
							$this->addError(
								'unfixable',
								"Error: invalid flags \"{$row->old_flags}\" on concat bulk row {$row->old_id}",
								$concatBlobs[$row->old_id] );
						}
					} elseif ( strcasecmp(
						substr( $row->header, 0, strlen( self::CONCAT_HEADER ) ),
						self::CONCAT_HEADER
					) ) {
						$this->addError(
							'restore text',
							"Error: Incorrect object header for concat bulk row {$row->old_id}",
							$concatBlobs[$row->old_id]
						);
					}

					unset( $concatBlobs[$row->old_id] );
				}
			}

			// Check targets of unresolved stubs
			$this->checkExternalConcatBlobs( $externalConcatBlobs );
			// next chunk
		}

		print "\n\nErrors:\n";
		foreach ( $this->errors as $name => $errors ) {
			if ( count( $errors ) ) {
				$description = $this->errorDescriptions[$name];
				echo "$description: " . implode( ',', array_keys( $errors ) ) . "\n";
			}
		}

		if ( count( $this->errors['restore text'] ) && $fix ) {
			if ( (string)$xml !== '' ) {
				$this->restoreText( array_keys( $this->errors['restore text'] ), $xml );
			} else {
				echo "Can't fix text, no XML backup specified\n";
			}
		}

		print "\nFlag statistics:\n";
		$total = array_sum( $flagStats );
		foreach ( $flagStats as $flag => $count ) {
			printf( "%-30s %10d %5.2f%%\n", $flag, $count, $count / $total * 100 );
		}
		print "\nLocal object statistics:\n";
		$total = array_sum( $objectStats );
		foreach ( $objectStats as $className => $count ) {
			printf( "%-30s %10d %5.2f%%\n", $className, $count, $count / $total * 100 );
		}
	}

	/**
	 * @param string $type
	 * @param string $msg
	 * @param int|int[] $ids
	 */
	private function addError( string $type, string $msg, $ids ) {
		if ( is_array( $ids ) && count( $ids ) == 1 ) {
			$ids = reset( $ids );
		}
		if ( is_array( $ids ) ) {
			$revIds = [];
			foreach ( $ids as $id ) {
				$revIds = array_unique( array_merge( $revIds, $this->oldIdMap[$id] ) );
			}
			print "$msg in text rows " . implode( ', ', $ids ) .
				", revisions " . implode( ', ', $revIds ) . "\n";
		} else {
			$id = $ids;
			$revIds = $this->oldIdMap[$id];
			if ( count( $revIds ) == 1 ) {
				print "$msg in old_id $id, rev_id {$revIds[0]}\n";
			} else {
				print "$msg in old_id $id, revisions " . implode( ', ', $revIds ) . "\n";
			}
		}
		$this->errors[$type] += array_fill_keys( $revIds, true );
	}

	private function checkExternalConcatBlobs( array $externalConcatBlobs ) {
		if ( !count( $externalConcatBlobs ) ) {
			return;
		}

		if ( $this->dbStore === null ) {
			$esFactory = $this->getServiceContainer()->getExternalStoreFactory();
			$this->dbStore = $esFactory->getStore( 'DB' );
		}

		foreach ( $externalConcatBlobs as $cluster => $oldIds ) {
			$blobIds = array_keys( $oldIds );
			$extDb = $this->dbStore->getReplica( $cluster );
			$blobsTable = $this->dbStore->getTable( $cluster );
			$headerLength = strlen( self::CONCAT_HEADER );
			$res = $extDb->newSelectQueryBuilder()
				->select( [ 'blob_id', "LEFT(blob_text, $headerLength) AS header" ] )
				->from( $blobsTable )
				->where( [ 'blob_id' => $blobIds ] )
				->caller( __METHOD__ )->fetchResultSet();
			foreach ( $res as $row ) {
				if ( strcasecmp( $row->header, self::CONCAT_HEADER ) ) {
					$this->addError(
						'restore text',
						"Error: invalid header on target $cluster/{$row->blob_id} of two-part ES URL",
						$oldIds[$row->blob_id]
					);
				}
				unset( $oldIds[$row->blob_id] );
			}

			// Print errors for missing blobs rows
			foreach ( $oldIds as $blobId => $oldIds2 ) {
				$this->addError(
					'restore text',
					"Error: missing target $cluster/$blobId for two-part ES URL",
					$oldIds2
				);
			}
		}
	}

	private function restoreText( array $revIds, string $xml ) {
		global $wgDBname;
		$tmpDir = wfTempDir();

		if ( !count( $revIds ) ) {
			return;
		}

		print "Restoring text from XML backup...\n";

		$revFileName = "$tmpDir/broken-revlist-$wgDBname";
		$filteredXmlFileName = "$tmpDir/filtered-$wgDBname.xml";

		// Write revision list
		if ( !file_put_contents( $revFileName, implode( "\n", $revIds ) ) ) {
			echo "Error writing revision list, can't restore text\n";

			return;
		}

		// Run mwdumper
		echo "Filtering XML dump...\n";
		$exitStatus = 0;
		// phpcs:ignore MediaWiki.Usage.ForbiddenFunctions.passthru
		passthru( 'mwdumper ' .
			Shell::escape(
				"--output=file:$filteredXmlFileName",
				"--filter=revlist:$revFileName",
				$xml
			), $exitStatus
		);

		if ( $exitStatus ) {
			echo "mwdumper died with exit status $exitStatus\n";

			return;
		}

		$file = fopen( $filteredXmlFileName, 'r' );
		if ( !$file ) {
			echo "Unable to open filtered XML file\n";

			return;
		}

		$dbr = $this->getReplicaDB();
		$dbw = $this->getPrimaryDB();
		$dbr->ping();
		$dbw->ping();

		$source = new ImportStreamSource( $file );
		$user = User::newSystemUser( User::MAINTENANCE_SCRIPT_USER, [ 'steal' => true ] );
		$importer = $this->getServiceContainer()
			->getWikiImporterFactory()
			->getWikiImporter( $source, new UltimateAuthority( $user ) );
		$importer->setRevisionCallback( $this->importRevision( ... ) );
		$importer->setNoticeCallback( static function ( $msg, $params ) {
			echo wfMessage( $msg, $params )->text() . "\n";
		} );
		$importer->doImport();
	}

	/**
	 * @param WikiRevision $revision
	 */
	private function importRevision( $revision ) {
		$id = $revision->getID();
		$content = $revision->getContent();
		$id = $id ?: '';

		if ( $content === null ) {
			echo "Revision $id is broken, we have no content available\n";

			return;
		}

		$text = $content->serialize();
		if ( $text === '' ) {
			// This is what happens if the revision was broken at the time the
			// dump was made. Unfortunately, it also happens if the revision was
			// legitimately blank, so there's no way to tell the difference. To
			// be safe, we'll skip it and leave it broken

			echo "Revision $id is blank in the dump, may have been broken before export\n";

			return;
		}

		if ( !$id ) {
			// No ID, can't import
			echo "No id tag in revision, can't import\n";

			return;
		}

		// Find text row again
		$dbr = $this->getReplicaDB();
		$res = $dbr->newSelectQueryBuilder()
			->select( [ 'content_address' ] )
			->from( 'slots' )
			->join( 'content', null, 'content_id = slot_content_id' )
			->where( [ 'slot_revision_id' => $id ] )
			->caller( __METHOD__ )->fetchRow();

		$blobStore = $this->getServiceContainer()
			->getBlobStoreFactory()
			->newSqlBlobStore();
		$oldId = $blobStore->getTextIdFromAddress( $res->content_address );

		if ( !$oldId ) {
			echo "Missing revision row for rev_id $id\n";
			return;
		}

		// Compress the text
		$flags = $blobStore->compressData( $text );

		// Update the text row
		$dbw = $this->getPrimaryDB();
		$dbw->newUpdateQueryBuilder()
			->update( 'text' )
			->set( [ 'old_flags' => $flags, 'old_text' => $text ] )
			->where( [ 'old_id' => $oldId ] )
			->caller( __METHOD__ )
			->execute();

		// Remove it from the unfixed list and add it to the fixed list
		unset( $this->errors['restore text'][$id] );
		$this->errors['fixed'][$id] = true;
	}

}

// @codeCoverageIgnoreStart
$maintClass = CheckStorage::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
