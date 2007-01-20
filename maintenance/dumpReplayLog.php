<?php
/**
 * @addtogroup Maintenance
 */
error_reporting(E_ALL);

/** */
require_once( "commandLine.inc" );
require_once( 'includes/SpecialExport.php' );

/** */
function dumpReplayLog( $start ) {
	$dbw =& wfGetDB( DB_MASTER );
	$recentchanges = $dbw->tableName( 'recentchanges' );
	$result =& $dbw->safeQuery( "SELECT * FROM $recentchanges WHERE rc_timestamp >= "
		. $dbw->timestamp( $start ) . ' ORDER BY rc_timestamp');

	global $wgInputEncoding;
	echo '<' . '?xml version="1.0" encoding="' . $wgInputEncoding . '" ?' . ">\n";
	echo "<wikilog version='experimental'>\n";
	echo "<!-- Do not use this script for any purpose. It's scary. -->\n";
	while( $row = $dbw->fetchObject( $result ) ) {
		echo dumpReplayEntry( $row );
	}
	echo "</wikilog>\n";
	$dbw->freeResult( $result );
}

/** */
function dumpReplayEntry( $row ) {
	$title = Title::MakeTitle( $row->rc_namespace, $row->rc_title );
	switch( $row->rc_type ) {
	case RC_EDIT:
	case RC_NEW:
		# Edit
		$dbr =& wfGetDB( DB_MASTER );

		$out = "  <edit>\n";
		$out .= "    <title>" . xmlsafe( $title->getPrefixedText() ) . "</title>\n";

		# Get previous edit timestamp
		if( $row->rc_last_oldid ) {
			$s = $dbr->selectRow( 'old',
				array( 'old_timestamp' ),
				array( 'old_id' => $row->rc_last_oldid ) );
			$out .= "    <lastedit>" . wfTimestamp2ISO8601( $s->old_timestamp ) . "</lastedit>\n";
		} else {
			$out .= "    <newpage/>\n";
		}

		if( $row->rc_this_oldid ) {
			$s = $dbr->selectRow( 'old', array( 'old_id as id','old_timestamp as timestamp',
				'old_user as user', 'old_user_text as user_text', 'old_comment as comment',
				'old_text as text', 'old_flags as flags' ),
				array( 'old_id' => $row->rc_this_oldid ) );
			$out .= revision2xml( $s, true, false );
		} else {
			$s = $dbr->selectRow( 'cur', array( 'cur_id as id','cur_timestamp as timestamp','cur_user as user',
				'cur_user_text as user_text', 'cur_restrictions as restrictions','cur_comment as comment',
				'cur_text as text' ),
				array( 'cur_id' => $row->rc_cur_id ) );
			$out .= revision2xml( $s, true, true );
		}
		$out .= "  </edit>\n";
		break;
	case RC_LOG:
		$dbr =& wfGetDB( DB_MASTER );
		$s = $dbr->selectRow( 'logging',
			array( 'log_type', 'log_action', 'log_timestamp', 'log_user',
				'log_namespace', 'log_title', 'log_comment' ),
			array( 'log_timestamp' => $row->rc_timestamp,
			       'log_user'      => $row->rc_user ) );
		$ts = wfTimestamp2ISO8601( $row->rc_timestamp );
		$target = Title::MakeTitle( $s->log_namespace, $s->log_title );
		$out  = "  <log>\n";
		$out .= "    <type>" . xmlsafe( $s->log_type ) . "</type>\n";
		$out .= "    <action>" . xmlsafe( $s->log_action ) . "</action>\n";
		$out .= "    <timestamp>" . $ts . "</timestamp>\n";
		$out .= "    <contributor><username>" . xmlsafe( $row->rc_user_text ) . "</username></contributor>\n";
		$out .= "    <target>" . xmlsafe( $target->getPrefixedText() ) . "</target>\n";
		$out .= "    <comment>" . xmlsafe( $s->log_comment ) . "</comment>\n";
		$out .= "  </log>\n";
		break;
	case RC_MOVE:
	case RC_MOVE_OVER_REDIRECT:
		$target = Title::MakeTitle( $row->rc_moved_to_ns, $row->rc_moved_to_title );
		$out  = "  <move>\n";
		$out .= "    <title>" . xmlsafe( $title->getPrefixedText() ) . "</title>\n";
		$out .= "    <target>" . xmlsafe( $target->getPrefixedText() ) . "</target>\n";
		if( $row->rc_type == RC_MOVE_OVER_REDIRECT ) {
			$out .= "    <override/>\n";
		}
		$ts = wfTimestamp2ISO8601( $row->rc_timestamp );
		$out .= "    <id>$row->rc_cur_id</id>\n";
		$out .= "    <timestamp>$ts</timestamp>\n";
		if($row->rc_user_text) {
			$u = "<username>" . xmlsafe( $row->rc_user_text ) . "</username>";
			$u .= "<id>$row->rc_user</id>";
		} else {
			$u = "<ip>" . xmlsafe( $row->rc_user_text ) . "</ip>";
		}
		$out .= "    <contributor>$u</contributor>\n";
		$out .= "  </move>\n";
	}
	return $out;
}


if( isset( $options['start'] ) ) {
	$start = wfTimestamp( TS_MW, $options['start'] );
	dumpReplayLog( $start );
} else {
	echo "This is an experimental script to encapsulate data from recent edits.\n";
	echo "Usage:  php dumpReplayLog.php --start=20050118032544\n";
}

?>