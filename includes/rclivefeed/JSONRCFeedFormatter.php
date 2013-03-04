<?php
class JSONRCFeedFormatter implements RCFeedFormatter {
	/**
	 * Generates a notification that can be easily interpreted by a machine.
	 * @see RCFeedFormatter::getLine
	 */
	public function getLine( array $feed, RecentChange $rc, $actionComment ) {
		global $wgCanonicalServer, $wgScript, $wgArticlePath;
		$attrib = $rc->getAttributes();

		$packet = array(
			// Usually, RC ID is exposed only for patrolling purposes,
			// but there is no real reason not to expose it in other cases,
			// and I can see how this may be potentially useful for clients.
			'id' => $attrib['rc_id'],
			'type' => $attrib['rc_type'],
			'namespace' => $rc->getTitle()->getNamespace(),
			'title' => $rc->getTitle()->getPrefixedText(),
			'comment' => $attrib['rc_comment'],
			'timestamp' => (int)wfTimestamp( TS_UNIX, $attrib['rc_timestamp'] ),
			'user' => $attrib['rc_user_text'],
			'bot' => (bool)$attrib['rc_bot'],
		);

		if ( isset( $feed['channel'] ) ) {
			$packet['channel'] = $feed['channel'];
		}

		$type = $attrib['rc_type'];
		if ( $type == RC_EDIT || $type == RC_NEW ) {
			global $wgUseRCPatrol, $wgUseNPPatrol;

			$packet['minor'] = $attrib['rc_minor'];
			if ( $wgUseRCPatrol || ( $type == RC_NEW && $wgUseNPPatrol ) ) {
				$packet['patrolled'] = $attrib['rc_patrolled'];
			}
		}

		switch ( $type ) {
			case RC_EDIT:
				$packet['old_len'] = $attrib['rc_old_len'];
				$packet['new_len'] = $attrib['rc_new_len'];
				$packet['old_revision'] = $attrib['rc_last_oldid'];
				$packet['new_revision'] = $attrib['rc_this_oldid'];
				break;

			case RC_NEW:
				$packet['len'] = $attrib['rc_new_len'];
				$packet['revision'] = $attrib['rc_this_oldid'];
				break;

			case RC_LOG:
				$packet['log_type'] = $attrib['rc_log_type'];
				$packet['log_action'] = $attrib['rc_log_action'];
				if ( $attrib['rc_params'] ) {
					$packet['log_params'] = unserialize( $attrib['rc_params'] );
				}
				$packet['log_action_comment'] = $actionComment;
				break;
		}

		$packet['url_server'] = $wgCanonicalServer;
		$packet['url_script_path'] = $wgScript;
		$packet['url_article_path'] = $wgArticlePath;

		return FormatJson::encode( $packet );
	}
}
