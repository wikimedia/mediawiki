<?php

# Rebuild recent changes table.
#

function rebuildRecentChangesTablePass1()
{
	$fname = 'rebuildRecentChangesTablePass1';
	$dbw =& wfGetDB( DB_MASTER );
	extract( $dbw->tableNames( 'recentchanges', 'cur', 'old' ) );

	$dbw->delete( 'recentchanges', '*' );

	print( "Loading from CUR table...\n" );

	$dbw->insertSelect( 'recentchanges', 'cur', 
		array(
			'rc_timestamp' => 'cur_timestamp',
			'rc_cur_time' => 'cur_timestamp',
			'rc_user' => 'cur_user',
			'rc_user_text' => 'cur_user_text',
			'rc_namespace' => 'cur_namespace',
			'rc_title' => 'cur_title',
			'rc_comment' => 'cur_comment',
			'rc_minor' => 'cur_minor_edit',
			'rc_bot' => 0,
			'rc_new' => 'cur_is_new',
			'rc_cur_id' => 'cur_id',
			'rc_this_oldid' => 0,
			'rc_last_oldid' => 0,
			'rc_type' => 'IF(cur_is_new,' . RC_NEW . ',' . RC_EDIT . ')'
		), '*', $fname, array( 'ORDER BY' => 'inverse_timestamp', 'LIMIT' => 5000 )
	);
	
	print( "Loading from OLD table...\n" );

	$sql = "INSERT INTO $recentchanges (rc_timestamp,rc_cur_time,rc_user," .
      "rc_user_text,rc_namespace,rc_title,rc_comment,rc_minor,rc_bot,rc_new," .
      "rc_cur_id,rc_this_oldid,rc_last_oldid) SELECT old_timestamp,cur_timestamp," .
	  "old_user,old_user_text,old_namespace,old_title,old_comment," .
	  "old_minor_edit,0,0,cur_id,old_id,0 FROM $old,$cur " .
	  "WHERE old_namespace=cur_namespace AND old_title=cur_title ORDER BY $old.inverse_timestamp " .
	  "LIMIT 5000";
	$dbw->query( $sql );

	$sql = "SELECT rc_timestamp FROM $recentchanges " .
	  "ORDER BY rc_timestamp DESC LIMIT 5000,1";
	$res = $dbw->query( $sql );
	$obj = $dbw->fetchObject( $res );
	$ts = $obj->rc_timestamp;

	$sql = "DELETE FROM $recentchanges WHERE rc_timestamp < '{$ts}'";
	$dbw->query( $sql );
}

function rebuildRecentChangesTablePass2()
{
	$dbw =& wfGetDB( DB_MASTER );
	extract( $dbw->tableNames( 'recentchanges', 'cur', 'old' ) );

	$ns = $id = $count = 0;
	$title = $ct =  "";

	print( "Updating links...\n" );

	# Fill in the rc_last_oldid field, which points to the previous edit
	#
	$sql = "SELECT rc_cur_id,rc_this_oldid,rc_timestamp FROM $recentchanges " .
	  "ORDER BY rc_cur_id,rc_timestamp";
	$res = $dbw->query( $sql, DB_MASTER );

	$lastCurId = 0;
	$lastOldId = 0;
	while ( $obj = $dbw->fetchObject( $res ) ) {
		$new = 0;
		if( $obj->rc_cur_id != $lastCurId ) {
			# Switch! Look up the previous last edit, if any
			$lastCurId = IntVal( $obj->rc_cur_id );
			$emit = wfInvertTimestamp( $obj->rc_timestamp );
			$sql2 = "SELECT old_id FROM $old,$cur " .
				"WHERE old_namespace=cur_namespace AND old_title=cur_title AND cur_id={$lastCurId} ".
				"AND $old.inverse_timestamp>'{$emit}' ORDER BY $old.inverse_timestamp LIMIT 1";
			$res2 = $dbw->query( $sql2 );
			if( $row = $dbw->fetchObject( $res2 ) ) {
				$lastOldId = IntVal( $row->old_id );
			} else {
				# No previous edit
				$lastOldId = 0;
				$new = 1;
			}
			$dbw->freeResult( $res2 );
		}
		if( $lastCurId == 0 ) {
			print "Uhhh, something wrong? No curid\n";
		} else {
			$sql3 = "UPDATE $recentchanges SET rc_last_oldid=$lastOldId,rc_new=$new,rc_type=$new " .
				"WHERE rc_cur_id={$lastCurId} AND rc_this_oldid={$obj->rc_this_oldid}";
			$dbw->query( $sql3 );
			$lastOldId = IntVal( $obj->rc_this_oldid );
		}
	}
	$dbw->freeResult( $res );
}

?>
