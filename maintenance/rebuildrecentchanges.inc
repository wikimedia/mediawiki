<?php

# Rebuild recent changes table.
#

function rebuildRecentChangesTablePass1()
{
	$sql = "DELETE FROM recentchanges";
	wfQuery( $sql, DB_WRITE );

	print( "Loading from CUR table...\n" );

	$sql = "INSERT INTO recentchanges (rc_timestamp,rc_cur_time,rc_user," .
	  "rc_user_text,rc_namespace,rc_title,rc_comment,rc_minor,rc_bot,rc_new," .
	  "rc_cur_id,rc_this_oldid,rc_last_oldid) SELECT cur_timestamp," .
	  "cur_timestamp,cur_user,cur_user_text,cur_namespace,cur_title," .
	  "cur_comment,cur_minor_edit,0,cur_is_new,cur_id,0,0 FROM cur " .
	  "ORDER BY inverse_timestamp LIMIT 5000";
	wfQuery( $sql, DB_WRITE );

	print( "Loading from OLD table...\n" );

	$sql = "INSERT INTO recentchanges (rc_timestamp,rc_cur_time,rc_user," .
      "rc_user_text,rc_namespace,rc_title,rc_comment,rc_minor,rc_bot,rc_new," .
      "rc_cur_id,rc_this_oldid,rc_last_oldid) SELECT old_timestamp,cur_timestamp," .
	  "old_user,old_user_text,old_namespace,old_title,old_comment," .
	  "old_minor_edit,0,0,cur_id,old_id,0 FROM old,cur " .
	  "WHERE old_namespace=cur_namespace AND old_title=cur_title ORDER BY old.inverse_timestamp " .
	  "LIMIT 5000";
	wfQuery( $sql, DB_WRITE );

	$sql = "SELECT rc_timestamp FROM recentchanges " .
	  "ORDER BY rc_timestamp DESC LIMIT 5000,1";
	$res = wfQuery( $sql, DB_WRITE );
	$obj = wfFetchObject( $res );
	$ts = $obj->rc_timestamp;

	$sql = "DELETE FROM recentchanges WHERE rc_timestamp < '{$ts}'";
	wfQuery( $sql, DB_WRITE );
}

function rebuildRecentChangesTablePass2()
{
	$ns = $id = $count = 0;
	$title = $ct =  "";

	print( "Updating links...\n" );

	# Fill in the rc_last_oldid field, which points to the previous edit
	#
	$sql = "SELECT rc_cur_id,rc_this_oldid,rc_timestamp FROM recentchanges " .
	  "ORDER BY rc_cur_id,rc_timestamp";
	$res = wfQuery( $sql, DB_WRITE );

	$lastCurId = 0;
	$lastOldId = 0;
	while ( $obj = wfFetchObject( $res ) ) {
		$new = 0;
		if( $obj->rc_cur_id != $lastCurId ) {
			# Switch! Look up the previous last edit, if any
			$lastCurId = IntVal( $obj->rc_cur_id );
			$emit = wfInvertTimestamp( $obj->rc_timestamp );
			$sql2 = "SELECT old_id FROM old,cur " .
				"WHERE old_namespace=cur_namespace AND old_title=cur_title AND cur_id={$lastCurId} ".
				"AND old.inverse_timestamp>'{$emit}' ORDER BY old.inverse_timestamp LIMIT 1";
			$res2 = wfQuery( $sql2, DB_WRITE );
			if( $row = wfFetchObject( $res2 ) ) {
				$lastOldId = IntVal( $row->old_id );
			} else {
				# No previous edit
				$lastOldId = 0;
				$new = 1;
			}
			wfFreeResult( $res2 );
		}
		if( $lastCurId == 0 ) {
			print "Uhhh, something wrong? No curid\n";
		} else {
			$sql3 = "UPDATE recentchanges SET rc_last_oldid=$lastOldId,rc_new=$new,rc_type=$new WHERE rc_cur_id={$lastCurId} AND rc_this_oldid={$obj->rc_this_oldid}";
			wfQuery( $sql3, DB_WRITE );
			$lastOldId = IntVal( $obj->rc_this_oldid );
		}
	}
	wfFreeResult( $res );
}

?>
