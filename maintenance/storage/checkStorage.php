<?php

/**
 * Fsck for MediaWiki
 */

define( 'CONCAT_HEADER', 'O:27:"concatenatedgziphistoryblob"' );

if ( !defined( 'MEDIAWIKI' ) ) {
	require_once( dirname(__FILE__) . '/../commandLine.inc' );
	require_once('ExternalStore.php');
	require_once( 'ExternalStoreDB.php' );

	checkStorage();
}


//----------------------------------------------------------------------------------

function checkStorage() {
	global $oldIdMap, $brokenRevisions;
	
	$fname = 'checkStorage';
	$dbr =& wfGetDB( DB_SLAVE );
	$maxRevId = $dbr->selectField( 'revision', 'MAX(rev_id)', false, $fname );
	$chunkSize = 1000;
	$flagStats = array();
	$objectStats = array();
	$knownFlags = array( 'external', 'gzip', 'object', 'utf-8' );
	$dbStore = null;
	$brokenRevisions = array();

	for ( $chunkStart = 1 ; $chunkStart < $maxRevId; $chunkStart += $chunkSize ) {
		$chunkEnd = $chunkStart + $chunkSize - 1;
		//print "$chunkStart of $maxRevId\n";

		// Fetch revision rows
		$oldIdMap = array();
		$res = $dbr->select( 'revision', array( 'rev_id', 'rev_text_id' ), 
			array( "rev_id BETWEEN $chunkStart AND $chunkEnd" ), $fname );
		while ( $row = $dbr->fetchObject( $res ) ) {
			$oldIdMap[$row->rev_id] = $row->rev_text_id;
		}
		$dbr->freeResult( $res );

		if ( !count( $oldIdMap ) ) {
			continue;
		}

		// Fetch old_flags
		$missingTextRows = array_flip( $oldIdMap );
		$externalRevs = array();
		$objectRevs = array();
		$flagsFields = array();
		$res = $dbr->select( 'text', array( 'old_id', 'old_flags' ), 
			'old_id IN (' . implode( ',', $oldIdMap ) . ')', $fname );
		while ( $row = $dbr->fetchObject( $res ) ) {
			$flags = $row->old_flags;
			$id = $row->old_id;

			// Create flagStats row if it doesn't exist
			$flagStats = $flagStats + array( $flags => 0 );
			// Increment counter
			$flagStats[$flags]++;

			// Not missing
			unset( $missingTextRows[$row->old_id] );

			// Check for external or object
			if ( $flags == '' ) {
				$flagArray = array();
			} else {
				$flagArray = explode( ',', $flags );
			}
			if ( in_array( 'external', $flagArray ) ) {
				$flagsFields[$id] = $flags; // is this needed?
				$externalRevs[] = $id;
			} elseif ( in_array( 'object', $flagArray ) ) {
				$flagsFields[$id] = $flags; // is this needed?
				$objectRevs[] = $id;
			}

			// Check for unrecognised flags
			if ( count( array_diff( $flagArray, $knownFlags ) ) ) {
				checkError( "Warning: invalid flags field \"$flags\"", $id );
			}
		}
		$dbr->freeResult( $res );

		// Output errors for any missing text rows
		foreach ( $missingTextRows as $oldId => $revId ) {
			print "Error: missing text row $oldId for revision $revId\n";
		}

		// Verify external revisions
		$externalConcatBlobs = array();
		$externalNormalBlobs = array();
		if ( count( $externalRevs ) ) {
			$res = $dbr->select( 'text', array( 'old_id', 'old_flags', 'old_text' ), 
				array( 'old_id IN (' . implode( ',', $externalRevs ) . ')' ), $fname );
			while ( $row = $dbr->fetchObject( $res ) ) {
				$urlParts = explode( '://', $row->old_text, 2 );
				if ( count( $urlParts ) !== 2 || $urlParts[1] == '' ) {
					checkError( "Error: invalid URL \"{$row->old_text}\"", $row->old_id );
					continue;
				}
				list( $proto, $path ) = $urlParts;
				if ( $proto != 'DB' ) {
					checkError( "Error: invalid external protocol \"$proto\"", $row->old_id );
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
		checkExternalConcatBlobs( $externalConcatBlobs );
		

		// Check external normal blobs for existence
		if ( count( $externalNormalBlobs ) ) {
			if ( is_null( $dbStore ) ) {
				$dbStore = new ExternalStoreDB;
			}
			foreach ( $externalConcatBlobs as $cluster => $xBlobIds ) {
				$blobIds = array_keys( $xBlobIds );
				$extDb =& $dbStore->getSlave( $cluster );
				$blobsTable = $dbStore->getTable( $extDb );
				$res = $extDb->select( $blobsTable, 
					array( 'blob_id' ), 
					array( 'blob_id IN( ' . implode( ',', $blobIds ) . ')' ), $fname );
				while ( $row = $extDb->fetchObject( $res ) ) {
					unset( $xBlobIds[$row->blob_id] );
				}
				$extDb->freeResult( $res );
				// Print errors for missing blobs rows
				foreach ( $xBlobIds as $blobId => $oldId ) {
					checkError( "Error: missing target $blobId for one-part ES URL", $oldId );
				}
			}
		}

		// Check local objects
		$dbr->ping();
		$concatBlobs = array();
		$curIds = array();
		if ( count( $objectRevs ) ) {
			$headerLength = 300;
			$res = $dbr->select( 'text', array( 'old_id', 'old_flags', "LEFT(old_text, $headerLength) AS header" ), 
				array( 'old_id IN (' . implode( ',', $objectRevs ) . ')' ), $fname );
			while ( $row = $dbr->fetchObject( $res ) ) {
				$oldId = $row->old_id;
				if ( !preg_match( '/^O:(\d+):"(\w+)"/', $row->header, $matches ) ) {
					checkError( "Error: invalid object header", $oldId );
					continue;
				}

				$className = strtolower( $matches[2] );
				if ( strlen( $className ) != $matches[1] ) {
					checkError( "Error: invalid object header, wrong class name length", $oldId );
					continue;
				}

				$objectStats = $objectStats + array( $className => 0 );
				$objectStats[$className]++;

				switch ( $className ) {
					case 'concatenatedgziphistoryblob':
						// Good
						break;
					case 'historyblobstub':
					case 'historyblobcurstub':
						if ( strlen( $row->header ) == $headerLength ) {
							checkError( "Error: overlong stub header", $oldId );
							continue;
						}
						$stubObj = unserialize( $row->header );
						if ( !is_object( $stubObj ) ) {
							checkError( "Error: unable to unserialize stub object", $oldId );
							continue;
						}
						if ( $className == 'historyblobstub' ) {
							$concatBlobs[$stubObj->mOldId][] = $oldId;
						} else {
							$curIds[$stubObj->mCurId][] = $oldId;
						}
						break;
					default:
						checkError( "Error: unrecognised object class \"$className\"", $oldId );
				}
			}
			$dbr->freeResult( $res );
		}

		// Check local concat blob validity
		$externalConcatBlobs = array();
		if ( count( $concatBlobs ) ) {
			$headerLength = 300;
			$res = $dbr->select( 'text', array( 'old_id', 'old_flags', "LEFT(old_text, $headerLength) AS header" ), 
				array( 'old_id IN (' . implode( ',', array_keys( $concatBlobs ) ) . ')' ), $fname );
			while ( $row = $dbr->fetchObject( $res ) ) {
				$flags = explode( ',', $row->old_flags );
				if ( in_array( 'external', $flags ) ) {
					// Concat blob is in external storage?
					if ( in_array( 'object', $flags ) ) {
						$urlParts = explode( '/', $row->header );
						if ( $urlParts[0] != 'DB:' ) {
							checkError( "Error: unrecognised external storage type \"{$urlParts[0]}", $row->old_id );
						} else {
							$cluster = $urlParts[2];
							$id = $urlParts[3];
							if ( !isset( $externalConcatBlobs[$cluster][$id] ) ) {
								$externalConcatBlobs[$cluster][$id] = array();
							}
							$externalConcatBlobs[$cluster][$id] = array_merge( 
								$externalConcatBlobs[$cluster][$id], $concatBlobs[$row->old_id]
							);
						}
					} else {
						checkError( "Error: invalid flags \"{$row->old_flags}\" on concat bulk row {$row->old_id}",
							$concatBlobs[$row->old_id] );
					}
				} elseif ( strcasecmp( substr( $row->header, 0, strlen( CONCAT_HEADER ) ), CONCAT_HEADER ) ) {
					checkError( "Error: Incorrect object header for concat bulk row {$row->old_id}", 
						$concatBlobs[$row->old_id] );
				} # else good

				unset( $concatBlobs[$row->old_id] );
			}
			$dbr->freeResult( $res );
		}

		// Check targets of unresolved stubs
		checkExternalConcatBlobs( $externalConcatBlobs );
		$dbr->ping();

		// next chunk
	}

	print "\n\n" . count( $brokenRevisions ) . " broken revisions\n";

	print "\nFlag statistics:\n";
	$total = array_sum( $flagStats );
	foreach ( $flagStats as $flag => $count ) {
		printf( "%-30s %10d %5.2f%%\n", $flag, $count, $count / $total * 100 );
	}
	print "\nObject statistics:\n";
	$total = array_sum( $objectStats );
	foreach ( $objectStats as $className => $count ) {
		printf( "%-30s %10d %5.2f%%\n", $className, $count, $count / $total * 100 );
	}
}


function checkError( $msg, $ids ) {
	global $oldIdMap, $brokenRevisions;
	if ( is_array( $ids ) && count( $ids ) == 1 ) {
		$ids = reset( $ids );
	}
	if ( is_array( $ids ) ) {
		$revIds = array();
		foreach ( $ids as $id ) {
			$revIds = array_merge( $revIds, array_keys( $oldIdMap, $id ) );
		}
		print "$msg in text rows " . implode( ', ', $ids ) . 
			", revisions " . implode( ', ', $revIds ) . "\n";
	} else {
		$id = $ids;
		$revIds = array_keys( $oldIdMap, $id );
		if ( count( $revIds ) == 1 ) {
			print "$msg in old_id $id, rev_id {$revIds[0]}\n";
		} else {
			print "$msg in old_id $id, revisions " . implode( ', ', $revIds ) . "\n";
		}
	}
	$brokenRevisions = $brokenRevisions + array_flip( $revIds );
}

function checkExternalConcatBlobs( $externalConcatBlobs ) {
	static $dbStore = null;
	$fname = 'checkExternalConcatBlobs';
	if ( !count( $externalConcatBlobs ) ) {
		return;
	}

	if ( is_null( $dbStore ) ) {
		$dbStore = new ExternalStoreDB;
	}
	
	foreach ( $externalConcatBlobs as $cluster => $oldIds ) {
		$blobIds = array_keys( $oldIds );
		$extDb =& $dbStore->getSlave( $cluster );
		$blobsTable = $dbStore->getTable( $extDb );
		$headerLength = strlen( CONCAT_HEADER );
		$res = $extDb->select( $blobsTable, 
			array( 'blob_id', "LEFT(blob_text, $headerLength) AS header" ), 
			array( 'blob_id IN( ' . implode( ',', $blobIds ) . ')' ), $fname );
		while ( $row = $extDb->fetchObject( $res ) ) {
			if ( strcasecmp( $row->header, CONCAT_HEADER ) ) {
				checkError( "Error: invalid header on target $cluster/{$row->blob_id} of two-part ES URL", 
					$oldIds[$row->blob_id] );
			}
			unset( $oldIds[$row->blob_id] );

		}
		$extDb->freeResult( $res );

		// Print errors for missing blobs rows
		foreach ( $oldIds as $blobId => $oldIds ) {
			checkError( "Error: missing target $cluster/$blobId for two-part ES URL", $oldIds );
		}
	}
}

?>
