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
	$dbr =& wfGetDB( DB_SLAVE );
	$old = $dbw->tableName( 'old' );
	do {
		$end = $start + $chunksize;
		$res = $dbr->select( 'old', array( 'old_id','old_flags','old_namespace','old_title','old_text' ),
			"old_id>=$start", $fname, array( 'ORDER BY' => 'old_id', 'LIMIT' => $chunksize, 'FOR UPDATE' ) );
		if( $dbr->numRows( $res ) == 0 ) {
			break;
		}
		$last = $start;
		while( $row = $dbr->fetchObject( $res ) ) {
			# print "  {$row->old_id} - {$row->old_namespace}:{$row->old_title}\n";
			compressPage( $row );
			$last = $row->old_id;
		}
		$dbr->freeResult( $res );
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

function compressWithConcat( $startId, $maxChunkSize, $maxChunkFactor, $factorThreshold, $beginDate, 
  $endDate, $beginTitle, $exclude_ns0, $queryExtra )
{
	global $wgLoadBalancer, $wgDBservers;
	$fname = 'compressWithConcat';
	$loadStyle = LS_CHUNKED;
	
	$dbw =& wfGetDB( DB_MASTER );
	$dbr =& wfGetDB( DB_SLAVE ); 

	# First get a list of all pages
	
	if ( $beginTitle ) {
		$cond = "cur_title >= " . $dbr->addQuotes( $beginTitle );
		print  "Starting from $beginTitle\n";
		if ( $exclude_ns0 ) {
			print "Excluding main namespace\n";
			$cond .= ' AND cur_namespace<>0';
		}

	} else {
		if ( $exclude_ns0 ) {
			print "Excluding main namespace\n";
			$cond = 'cur_namespace<>0';
		} else {
			$cond = false;
		}
	}

	if ( $queryExtra ) {
		if ( strlen($cond) > 0 ) $cond .= " AND ";
                $cond .= $queryExtra;
	}
	
	print "Starting article selection query $cond ...\n";
	$pageRes = $dbr->select( 'cur', array('cur_namespace', 'cur_title'), 
		$cond, $fname, array( 'ORDER BY' => 'cur_title' ) );
	
	print "Found " . $dbr->numRows( $pageRes ) . "\n";

	# For each of those, get a list of revisions which fit the criteria
	# No recompression, use a condition on old_flags
	$conds = array("old_flags NOT LIKE '%object%'");
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

	$oldReadsSinceLastSlaveWait = 0;	#check slave lag periodically
	$totalMatchingRevisions = 0;
	$masterPos = false;
	while ( $pageRow = $dbr->fetchObject( $pageRes ) ) {
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
		
                $revCount = count( $revs );     #Jamesday: this may be slow when there are many and that is also a time-sensitive situation.
		#print $revCount;
		$totalMatchingRevisions = $totalMatchingRevisions + $revCount;
		if ( $revCount < 2) {
			# No revisions matching, no further processing
			print "\n";
			continue;
		}

		#for big retrivals, slaves can time out, ping them and also make sure they aren't lagged before we start.
		if ( !$wgLoadBalancer->pingAll() ) {
			while (!$wgLoadBalancer->pingAll()) {
				print "Disconnected, reconnecting...\n";
				sleep(10);
			}
			
		}
		if ( $revCount > 100 ) {
			// From commandLine.inc
			wfWaitForSlaves( 5 );
		}
#		$oldReadsSinceLastSlaveWait = 0;

		# For each chunk
		$i = 0;
		while ( $i < $revCount ) {
			if ( $i < $revCount - $maxChunkSize ) {
				$thisChunkSize = $maxChunkSize;
			} else {
				$thisChunkSize = $revCount - $i;
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
					$stub = $chunk->addItem( $text );
					$stub->setLocation( $primaryOldid );
					$hash = $stub->getHash();
					$stub = serialize( $stub );
					print '.';
					$usedChunk = true;
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

			#Jamesday: ping slaves periodically.
			$oldReadsSinceLastSlaveWait += $thisChunkSize;
			if ( $oldReadsSinceLastSlaveWait > 50 ) {
				wfWaitForSlaves(5);
				$oldReadsSinceLastSlaveWait = 0;
			}
		}
		print "\n";

#		JamesDay next replaced by the periodic counted checks.
#		# Wait for slaves to catch up to the position of the master (previous article for small numbers, last check for large)
#		$masterPos = waitForSlaves( $masterPos );
	}
	print "total matching revisions = $totalMatchingRevisions\n";
	return true;
}

function waitForSlaves( $pos = false ) {
	global $wgDBservers;
	$master = wfGetDB( 0 );
	$slaves = $wgDBservers;
	array_shift( $slaves );
	if ( $pos == false ) {
		$pos = $master->getMasterPos();
	}
	$first = true;

	foreach ( $wgDBservers as $i => $record ) {
		if ( $first ) {
			print "Waiting for ";
			$first = false;
		}
		print $record['host'] . " ";
		$slave = wfGetDB( $i );
		$slave->masterPosWait( $pos[0], $pos[1], 20 );
	}
	if ( !$first ) {
		print "\n";
	}
	return $master->getMasterPos();
}

?>
