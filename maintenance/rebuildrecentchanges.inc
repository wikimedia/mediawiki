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
	$dbw = wfGetDB( DB_MASTER );
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
		array( 'ORDER BY' => 'rev_timestamp DESC', 'LIMIT' => 5000 ) // SELECT options
	);
}

function rebuildRecentChangesTablePass2()
{
	$dbw = wfGetDB( DB_MASTER );
	list ($recentchanges, $revision) = $dbw->tableNamesN( 'recentchanges', 'revision' );

	print( "Updating links and size differences...\n" );

	# Fill in the rc_last_oldid field, which points to the previous edit
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
			$sql2 = "SELECT rev_id, rev_len, rev_text_id FROM $revision " .
				"WHERE rev_page={$lastCurId} ".
				"AND rev_timestamp<'{$emit}' ORDER BY rev_timestamp DESC LIMIT 1";
			$res2 = $dbw->query( $sql2 );
			if( $row = $dbw->fetchObject( $res2 ) ) {
				$lastOldId = intval( $row->rev_id );
				$lastTextId = intval( $row->rev_text_id );
				$lastSize = $row->rev_len; # Grab the last text size
			} else {
				# No previous edit
				$lastOldId = 0;
				$lastTextId = 0;
				$lastSize = NULL;
				$new = 1;
			}
			$dbw->freeResult( $res2 );
		}
		if( $lastCurId == 0 ) {
			print "Uhhh, something wrong? No curid\n";
		} else {
			# Check the text if not in rev_len for the last entry's text size
			if( !$lastSize ) {
				$lastText = $dbw->selectField( 'text', 'old_text', array('old_id' => $lastTextId ) );
				$lastSize = $lastText ? strlen($lastText) : 'NULL';
			}
			# Grab the entry's text size
			$res3 = $dbw->select( 'revision', array('rev_len','rev_text_id'), array('rev_id' => $obj->rc_this_oldid ) );
			if( $row = $dbw->fetchObject( $res3 ) ) {
				$textId = $row->rev_text_id;
				$size = $row->rev_len;
			} else {
				$textId = 0;
				$size = NULL;
			}
			# Check the text if not in rev_len for the entry's text size
			if( !$size ) {
				$text = $dbw->selectField( 'text', 'old_text', array('old_id' => $textId ) );
				$size = $text ? strlen($text) : 'NULL';
			}
			
			$sql3 = "UPDATE $recentchanges SET rc_last_oldid=$lastOldId,rc_new=$new,rc_type=$new," .
				"rc_old_len='$lastSize',rc_new_len='$size' " .
				"WHERE rc_cur_id={$lastCurId} AND rc_this_oldid={$obj->rc_this_oldid}";
			$dbw->query( $sql3 );
			
			$lastOldId = intval( $obj->rc_this_oldid );
		}
	}
	$dbw->freeResult( $res );
}

function rebuildRecentChangesTablePass3()
{
	global $wgGroupPermissions, $wgUseRCPatrol;
			
	$dbw = wfGetDB( DB_MASTER );
	
	list ($recentchanges, $usergroups) = $dbw->tableNamesN( 'recentchanges', 'user_groups' );

	$botgroups = $autopatrolgroups = array();
	foreach( $wgGroupPermissions as $group => $rights ) {
		if( isset( $rights['bot'] ) && $rights['bot'] == true ) {
			$botgroups[] = "'" . $dbw->strencode( $group ) . "'";
		}
		if( $wgUseRCPatrol && isset( $rights['autopatrol'] ) && $rights['autopatrol'] == true ) {
			$autopatrolgroups[] = "'" . $dbw->strencode( $group ) . "'";
		}
	}
	# Flag our recent bot edits
	if( !empty($botgroups) ) {
		$botwhere = implode(',',$botgroups);
		$botusers = array();

		print( "Flagging bot account edits...\n" );

		# Find all users in RC that are bots
		$sql = "SELECT DISTINCT rc_user FROM $recentchanges " .
			"LEFT JOIN $usergroups ON rc_user=ug_user " . 
			"WHERE ug_group IN($botwhere)";
		$res = $dbw->query( $sql, DB_MASTER );

		while( $obj = $dbw->fetchObject( $res ) ) {
			$botusers[] = $obj->rc_user;
		}
		# Fill in the rc_bot field
		if( !empty($botusers) ) {
			$botwhere = implode(',',$botusers);
			$sql2 = "UPDATE $recentchanges SET rc_bot=1 " .
				"WHERE rc_user IN($botwhere)";
			$dbw->query( $sql2 );
		}
	}
	# Flag our recent autopatrolled edits
	if( !empty($autopatrolgroups) ) {
		$patrolwhere = implode(',',$autopatrolgroups);
		$patrolusers = array();

		print( "Flagging auto-patrolled edits...\n" );

		# Find all users in RC with autopatrol rights
		$sql = "SELECT DISTINCT rc_user FROM $recentchanges " .
			"LEFT JOIN $usergroups ON rc_user=ug_user " . 
			"WHERE ug_group IN($patrolwhere)";
		$res = $dbw->query( $sql, DB_MASTER );

		while( $obj = $dbw->fetchObject( $res ) ) {
			$patrolusers[] = $obj->rc_user;
		}
		
		# Fill in the rc_patrolled field
		if( !empty($patrolusers) ) {
			$patrolwhere = implode(',',$patrolusers);
			$sql2 = "UPDATE $recentchanges SET rc_patrolled=1 " .
				"WHERE rc_user IN($patrolwhere)";
			$dbw->query( $sql2 );
		}
	}
	
	$dbw->freeResult( $res );
}

?>
