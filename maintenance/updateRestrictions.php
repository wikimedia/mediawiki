<?php

/*
 * Makes the required database changes for the CheckUser extension
 */

define( 'BATCH_SIZE', 100 );

require_once 'commandLine.inc';
	
$db =& wfGetDB( DB_MASTER );
if ( !$db->tableExists( 'page_restrictions' ) ) {
	echo "page_restrictions does not exist\n";
	exit( 1 );
}

migrate_page_restrictions( $db );

function migrate_page_restrictions( $db ) {
	
	$start = $db->selectField( 'page', 'MIN(page_id)', false, __FUNCTION__ );
	$end = $db->selectField( 'page', 'MAX(page_id)', false, __FUNCTION__ );
	$blockStart = $start;
	$blockEnd = $start + BATCH_SIZE - 1;
	$encodedExpiry = Block::decodeExpiry('');
	while ( $blockEnd <= $end ) {
		$cond = "page_id BETWEEN $blockStart AND $blockEnd AND page_rescrictions !=''";
		$res = $db->select( 'page', array('page_id', 'page_restrictions'), $cond, __FUNCTION__ );
		$batch = array();
		while ( $row = $db->fetchObject( $res ) ) {
			$oldRestrictions = array();
			foreach( explode( ':', trim( $row->page_restrictions ) ) as $restrict ) {
				$temp = explode( '=', trim( $restrict ) );
				if(count($temp) == 1) {
					// old old format should be treated as edit/move restriction
					$oldRestrictions["edit"] = explode( ',', trim( $temp[0] ) );
					$oldRestrictions["move"] = explode( ',', trim( $temp[0] ) );
				} else {
					$oldRestrictions[$temp[0]] = explode( ',', trim( $temp[1] ) );
				}
			}
			# Update restrictions table
			foreach( $oldRestrictions as $action => $restrictions ) {
				$batch[] = array( 
					'pr_page' => $row->page_id,
					'pr_type' => $action,
					'pr_level' => $restrictions,
					'pr_cascade' => 0,
					'pr_expiry' => $encodedExpiry
				);
			}
			# Update page record
			$db->update( 'page',
				array( /* SET */
					'page_restrictions' => ''
				), array( /* WHERE */
					'page_id' => $row->page_id
				), 'migrate_restrictions'
			);
		}
		# We use insert() and not replace() as Article.php replaces
		# page_restrictions with '' when protected in the restrictions table
		if ( count( $batch ) ) {
			$db->insert( 'page_restictions', $batch, __FUNCTION__ );
		}
		$blockStart += BATCH_SIZE;
		$blockEnd += BATCH_SIZE;
		wfWaitForSlaves( 5 );
	}
}

?>
