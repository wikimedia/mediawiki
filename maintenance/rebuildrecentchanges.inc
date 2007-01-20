<?php
/**
 * Rebuild recent changes table.
 *
 * @todo document
 * @addtogroup Maintenance
 */

/** */
function rebuildRecentChangesTablePass1()
{
	$fname = 'rebuildRecentChangesTablePass1';
	$dbw =& wfGetDB( DB_MASTER );
	extract( $dbw->tableNames( 'recentchanges', 'cur', 'old' ) );

	$dbw->delete( 'recentchanges', '*' );

	print( "Loading from page and revision tables...\n" );

	global $wgRCMaxAge;
	$cutoff = time() - $wgRCMaxAge;
	$dbw->insertSelect( 'recentchanges', array( 'page', 'revision' ),
		array(
			'rc_timestamp'  => 'rev_timestamp',
			'rc_cur_time'   => 'rev_timestamp',
			'rc_user'       => 'rev_user',
			'rc_user_text'  => 'rev_user_text',
			'rc_namespace'  => 'page_namespace',
			'rc_title'      => 'page_title',
			'rc_comment'    => 'rev_comment',
			'rc_minor'      => 'rev_minor_edit',
			'rc_bot'        => 0,
			'rc_new'        => 'page_is_new',
			'rc_cur_id'     => 'page_id',
			'rc_this_oldid' => 'rev_id',
			'rc_last_oldid' => 0, // is this ok?
			'rc_type'       => $dbw->conditional( 'page_is_new != 0', RC_NEW, RC_EDIT ),
		), array(
			'rev_timestamp > ' . $dbw->addQuotes( $dbw->timestamp( $cutoff ) ),
			'rev_page=page_id'
		), $fname,
		array(), // INSERT options
		array( 'ORDER BY' => 'rev_timestamp', 'LIMIT' => 5000 ) // SELECT options
	);
}

function rebuildRecentChangesTablePass2()
{
	$dbw =& wfGetDB( DB_MASTER );
	list ($recentchanges, $revision) = $dbw->tableNamesN( 'recentchanges', 'revision' );

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
			$lastCurId = intval( $obj->rc_cur_id );
			$emit = $obj->rc_timestamp;
			$sql2 = "SELECT rev_id FROM $revision " .
				"WHERE rev_page={$lastCurId} ".
				"AND rev_timestamp<'{$emit}' ORDER BY rev_timestamp DESC LIMIT 1";
			$res2 = $dbw->query( $sql2 );
			if( $row = $dbw->fetchObject( $res2 ) ) {
				$lastOldId = intval( $row->rev_id );
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
			$lastOldId = intval( $obj->rc_this_oldid );
		}
	}
	$dbw->freeResult( $res );
}

?>
