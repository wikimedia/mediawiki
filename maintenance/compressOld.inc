<?php
/**
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** */
function compressOldPages( $start = 0 ) {
	$fname = 'compressOldPages';

	$chunksize = 50;
	print "Starting from old_id $start...\n";
	$dbw =& wfGetDB( DB_MASTER );
	$old = $dbw->tableName( 'old' );
	do {
		$end = $start + $chunksize;
		$res = $dbw->select( 'old', array( 'old_id','old_flags','old_namespace','old_title','old_text' ),
			"old_id>=$start", $fname, array( 'ORDER BY' => 'old_id', 'LIMIT' => $chunksize, 'FOR UPDATE' ) );
		if( $dbw->numRows( $res ) == 0 ) {
			break;
		}
		$last = $start;
		while( $row = $dbw->fetchObject( $res ) ) {
			# print "  {$row->old_id} - {$row->old_namespace}:{$row->old_title}\n";
			compressPage( $row );
			$last = $row->old_id;
		}
		$dbw->freeResult( $res );
		$start = $last + 1; # Deletion may leave long empty stretches
		print "$start...\n";
	} while( true );
}

function compressPage( $row ) {
	$fname = 'compressPage';
	if( false !== strpos( $row->old_flags, "gzip" ) ) {
		print "Already compressed row {$row->old_id}?\n";
		return false;
	}
	$dbw =& wfGetDB( DB_MASTER );
	$flags = $row->old_flags ? "{$row->old_flags},gzip" : "gzip";
	$compress = gzdeflate( $row->old_text );
	$dbw->update( 'old', 
		array( /* SET */
			'old_flags' => $flags,
			'old_text' => $compress
		), array( /* WHERE */
			'old_id' => $row->old_id
		), $fname, 'LIMIT 1'
	);
	return true;
}

define( 'LS_INDIVIDUAL', 0 );
define( 'LS_CHUNKED', 1 );

function compressWithConcat( $startId, $maxChunkSize, $maxChunkFactor, $factorThreshold, $beginDate, $endDate )
{
	$fname = 'compressWithConcat';
	$loadStyle = LS_CHUNKED;
	
	$dbw =& wfGetDB( DB_MASTER );

	# First get a list of all pages
	$pageRes = $dbw->select( 'cur', array('cur_namespace', 'cur_title'), false, $fname );

	# For each of those, get a list of revisions which fit the criteria
	$conds = array();
	if ( $beginDate ) {
		$conds[] = "old_timestamp>'" . $beginDate . "'";
	} 
	if ( $endDate )  {
		$conds[] = "old_timestamp<'" . $endDate . "'";
	}
	if ( $startId ) {
		$conds[] = 'old_id>=' . $startId;
	}
	if ( $loadStyle == LS_CHUNKED ) {
		$fields = array( 'old_id', 'old_flags', 'old_text' );
		$revLoadOptions = 'FOR UPDATE';
	} else {
		$fields = array( 'old_id' );
		$revLoadOptions = array();
	}

	while ( $pageRow = $dbw->fetchObject( $pageRes ) ) {
		# Display progress
		$titleObj = Title::makeTitle( $pageRow->cur_namespace, $pageRow->cur_title );
		print $titleObj->getPrefixedDBkey() . " ";

		# Load revisions
		$revRes = $dbw->select( 'old', $fields,
			array( 'old_namespace' => $pageRow->cur_namespace, 'old_title' => $pageRow->cur_title ) + $conds, 
			$fname,
			$revLoadOptions
		);
		$revs = array();
		while ( $revRow = $dbw->fetchObject( $revRes ) ) {
			$revs[] = $revRow;
		}
		
		if ( count( $revs ) < 2) {
			# No revisions matching, no further processing
			print "\n";
			continue;
		}

		# For each chunk
		$i = 0;
		while ( $i < count( $revs ) ) {
			if ( $i < count( $revs ) - $maxChunkSize ) {
				$thisChunkSize = $maxChunkSize;
			} else {
				$thisChunkSize = count( $revs ) - $i;
			}

			$chunk = new ConcatenatedGzipHistoryBlob();
			$stubs = array();
			$dbw->begin();
			$usedChunk = false;
			$primaryOldid = $revs[$i]->old_id;
			
			# Get the text of each revision and add it to the object
			for ( $j = 0; $j < $thisChunkSize && $chunk->isHappy( $maxChunkFactor, $factorThreshold ); $j++ ) {
				$oldid = $revs[$i + $j]->old_id;
				
				# Get text
				if ( $loadStyle == LS_INDIVIDUAL ) {
					$textRow = $dbw->selectRow( 'old', 
						array( 'old_flags', 'old_text' ),
						array( 'old_id' => $oldid ),
						$fname,
						'FOR UPDATE'
					);
					$text = Article::getRevisionText( $textRow );
				} else {
					$text = Article::getRevisionText( $revs[$i + $j] );
				}

				if ( $text === false ) {
					print "\nError, unable to get text in old_id $oldid\n";
					#$dbw->delete( 'old', array( 'old_id' => $oldid ) );
				}

				if ( $j == 0 ) {
					$chunk->setText( $text );
					print '.';
				} else {
					# Don't make a stub if it's going to be longer than the article
					# Stubs are typically about 100 bytes
					if ( strlen( $text ) < 120 ) {
						$stub = false;
						print 'x';
					} else {
						$stub = $chunk->addItem( $text );
						$stub->setLocation( $primaryOldid );
						$hash = $stub->getHash();
						$stub = serialize( $stub );
						print '.';
						$usedChunk = true;
					}
					$stubs[$j] = $stub;
				}
			}
			$thisChunkSize = $j;
			
			# If we couldn't actually use any stubs because the pages were too small, do nothing
			if ( $usedChunk ) {
				# Store the main object
				$dbw->update( 'old',
					array( /* SET */
						'old_text' => serialize( $chunk ),
						'old_flags' => 'object',
					), array( /* WHERE */
						'old_id' => $primaryOldid
					)
				);

				# Store the stub objects
				for ( $j = 1; $j < $thisChunkSize; $j++ ) {
					# Skip if not compressing
					if ( $stubs[$j] !== false ) {
						$dbw->update( 'old',
							array( /* SET */
								'old_text' => $stubs[$j],
								'old_flags' => 'object',
							), array( /* WHERE */
								'old_id' => $revs[$i + $j]->old_id
							)
						);
					}
				}
			}
			# Done, next
			print "/";
			$dbw->commit();
			$i += $thisChunkSize;
		}
		print "\n";
	}
	return true;
}
?>
