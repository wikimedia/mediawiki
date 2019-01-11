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

use MediaWiki\MediaWikiServices;

if ( !defined( 'MEDIAWIKI' ) ) {
	$optionsWithoutArgs = [ 'fix' ];
	require_once __DIR__ . '/../commandLine.inc';

	$cs = new CheckStorage;
	$fix = isset( $options['fix'] );
	if ( isset( $args[0] ) ) {
		$xml = $args[0];
	} else {
		$xml = false;
	}
	$cs->check( $fix, $xml );
}

// ----------------------------------------------------------------------------------

/**
 * Maintenance script to do various checks on external storage.
 *
 * @fixme this should extend the base Maintenance class
 * @ingroup Maintenance ExternalStorage
 */
class CheckStorage {
	const CONCAT_HEADER = 'O:27:"concatenatedgziphistoryblob"';
	public $oldIdMap, $errors;
	public $dbStore = null;

	public $errorDescriptions = [
		'restore text' => 'Damaged text, need to be restored from a backup',
		'restore revision' => 'Damaged revision row, need to be restored from a backup',
		'unfixable' => 'Unexpected errors with no automated fixing method',
		'fixed' => 'Errors already fixed',
		'fixable' => 'Errors which would already be fixed if --fix was specified',
	];

	function check( $fix = false, $xml = '' ) {
		$dbr = wfGetDB( DB_REPLICA );
		if ( $fix ) {
			print "Checking, will fix errors if possible...\n";
		} else {
			print "Checking...\n";
		}
		$maxRevId = $dbr->selectField( 'revision', 'MAX(rev_id)', '', __METHOD__ );
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

			// Fetch revision rows
			$this->oldIdMap = [];
			$dbr->ping();
			$res = $dbr->select( 'revision', [ 'rev_id', 'rev_text_id' ],
				[ "rev_id BETWEEN $chunkStart AND $chunkEnd" ], __METHOD__ );
			foreach ( $res as $row ) {
				$this->oldIdMap[$row->rev_id] = $row->rev_text_id;
			}
			$dbr->freeResult( $res );

			if ( !count( $this->oldIdMap ) ) {
				continue;
			}

			// Fetch old_flags
			$missingTextRows = array_flip( $this->oldIdMap );
			$externalRevs = [];
			$objectRevs = [];
			$res = $dbr->select(
				'text',
				[ 'old_id', 'old_flags' ],
				[ 'old_id' => $this->oldIdMap ],
				__METHOD__
			);
			foreach ( $res as $row ) {
				/**
				 * @var $flags int
				 */
				$flags = $row->old_flags;
				$id = $row->old_id;

				// Create flagStats row if it doesn't exist
				$flagStats = $flagStats + [ $flags => 0 ];
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
						$dbw = wfGetDB( DB_MASTER );
						$dbw->ping();
						$dbw->update( 'text', [ 'old_flags' => '' ],
							[ 'old_id' => $id ], __METHOD__ );
						echo "Fixed\n";
					} else {
						$this->addError( 'fixable', "Warning: old_flags set to 0", $id );
					}
				} elseif ( count( array_diff( $flagArray, $knownFlags ) ) ) {
					$this->addError( 'unfixable', "Error: invalid flags field \"$flags\"", $id );
				}
			}
			$dbr->freeResult( $res );

			// Output errors for any missing text rows
			foreach ( $missingTextRows as $oldId => $revId ) {
				$this->addError( 'restore revision', "Error: missing text row", $oldId );
			}

			// Verify external revisions
			$externalConcatBlobs = [];
			$externalNormalBlobs = [];
			if ( count( $externalRevs ) ) {
				$res = $dbr->select(
					'text',
					[ 'old_id', 'old_flags', 'old_text' ],
					[ 'old_id' => $externalRevs ],
					__METHOD__
				);
				foreach ( $res as $row ) {
					$urlParts = explode( '://', $row->old_text, 2 );
					if ( count( $urlParts ) !== 2 || $urlParts[1] == '' ) {
						$this->addError( 'restore text', "Error: invalid URL \"{$row->old_text}\"", $row->old_id );
						continue;
					}
					list( $proto, ) = $urlParts;
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
				$dbr->freeResult( $res );
			}

			// Check external concat blobs for the right header
			$this->checkExternalConcatBlobs( $externalConcatBlobs );

			// Check external normal blobs for existence
			if ( count( $externalNormalBlobs ) ) {
				if ( is_null( $this->dbStore ) ) {
					$this->dbStore = new ExternalStoreDB;
				}
				foreach ( $externalConcatBlobs as $cluster => $xBlobIds ) {
					$blobIds = array_keys( $xBlobIds );
					$extDb =& $this->dbStore->getSlave( $cluster );
					$blobsTable = $this->dbStore->getTable( $extDb );
					$res = $extDb->select( $blobsTable,
						[ 'blob_id' ],
						[ 'blob_id' => $blobIds ],
						__METHOD__
					);
					foreach ( $res as $row ) {
						unset( $xBlobIds[$row->blob_id] );
					}
					$extDb->freeResult( $res );
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
				$res = $dbr->select(
					'text',
					[ 'old_id', 'old_flags', "LEFT(old_text, $headerLength) AS header" ],
					[ 'old_id' => $objectRevs ],
					__METHOD__
				);
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

					$objectStats = $objectStats + [ $className => 0 ];
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
								$concatBlobs[$stubObj->mOldId][] = $oldId;
							} else {
								$curIds[$stubObj->mCurId][] = $oldId;
							}
							break;
						default:
							$this->addError( 'unfixable', "Error: unrecognised object class \"$className\"", $oldId );
					}
				}
				$dbr->freeResult( $res );
			}

			// Check local concat blob validity
			$externalConcatBlobs = [];
			if ( count( $concatBlobs ) ) {
				$headerLength = 300;
				$res = $dbr->select(
					'text',
					[ 'old_id', 'old_flags', "LEFT(old_text, $headerLength) AS header" ],
					[ 'old_id' => array_keys( $concatBlobs ) ],
					__METHOD__
				);
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
					} # else good

					unset( $concatBlobs[$row->old_id] );
				}
				$dbr->freeResult( $res );
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

	function addError( $type, $msg, $ids ) {
		if ( is_array( $ids ) && count( $ids ) == 1 ) {
			$ids = reset( $ids );
		}
		if ( is_array( $ids ) ) {
			$revIds = [];
			foreach ( $ids as $id ) {
				$revIds = array_merge( $revIds, array_keys( $this->oldIdMap, $id ) );
			}
			print "$msg in text rows " . implode( ', ', $ids ) .
				", revisions " . implode( ', ', $revIds ) . "\n";
		} else {
			$id = $ids;
			$revIds = array_keys( $this->oldIdMap, $id );
			if ( count( $revIds ) == 1 ) {
				print "$msg in old_id $id, rev_id {$revIds[0]}\n";
			} else {
				print "$msg in old_id $id, revisions " . implode( ', ', $revIds ) . "\n";
			}
		}
		$this->errors[$type] = $this->errors[$type] + array_flip( $revIds );
	}

	function checkExternalConcatBlobs( $externalConcatBlobs ) {
		if ( !count( $externalConcatBlobs ) ) {
			return;
		}

		if ( is_null( $this->dbStore ) ) {
			$this->dbStore = new ExternalStoreDB;
		}

		foreach ( $externalConcatBlobs as $cluster => $oldIds ) {
			$blobIds = array_keys( $oldIds );
			$extDb =& $this->dbStore->getSlave( $cluster );
			$blobsTable = $this->dbStore->getTable( $extDb );
			$headerLength = strlen( self::CONCAT_HEADER );
			$res = $extDb->select( $blobsTable,
				[ 'blob_id', "LEFT(blob_text, $headerLength) AS header" ],
				[ 'blob_id' => $blobIds ],
				__METHOD__
			);
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
			$extDb->freeResult( $res );

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

	function restoreText( $revIds, $xml ) {
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
		passthru( 'mwdumper ' .
			wfEscapeShellArg(
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

		$dbr = wfGetDB( DB_REPLICA );
		$dbw = wfGetDB( DB_MASTER );
		$dbr->ping();
		$dbw->ping();

		$source = new ImportStreamSource( $file );
		$importer = new WikiImporter(
			$source,
			MediaWikiServices::getInstance()->getMainConfig()
		);
		$importer->setRevisionCallback( [ $this, 'importRevision' ] );
		$importer->setNoticeCallback( function ( $msg, $params ) {
			echo wfMessage( $msg, $params )->text() . "\n";
		} );
		$importer->doImport();
	}

	function importRevision( &$revision, &$importer ) {
		$id = $revision->getID();
		$content = $revision->getContent( Revision::RAW );
		$id = $id ? $id : '';

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
		$dbr = wfGetDB( DB_REPLICA );
		$oldId = $dbr->selectField( 'revision', 'rev_text_id', [ 'rev_id' => $id ], __METHOD__ );
		if ( !$oldId ) {
			echo "Missing revision row for rev_id $id\n";

			return;
		}

		// Compress the text
		$flags = Revision::compressRevisionText( $text );

		// Update the text row
		$dbw = wfGetDB( DB_MASTER );
		$dbw->update( 'text',
			[ 'old_flags' => $flags, 'old_text' => $text ],
			[ 'old_id' => $oldId ],
			__METHOD__, [ 'LIMIT' => 1 ]
		);

		// Remove it from the unfixed list and add it to the fixed list
		unset( $this->errors['restore text'][$id] );
		$this->errors['fixed'][$id] = true;
	}
}
