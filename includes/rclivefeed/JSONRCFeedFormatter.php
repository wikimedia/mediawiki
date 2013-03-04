<?php
class JSONRCFeedFormatter implements RCFeedFormatter {
	public function getLine( $feed, $rc, $actionComment ) {
		$attrib = $rc->getAttributes();
		global $wgCanonicalServer, $wgScript, $wgArticlePath;

		$packet = array();

		if ( isset( $feed['channel'] ) ) {
			$packet['channel'] = $feed['channel'];
		}

		// Usually, RC ID is exposed only for patrolling purposes,
		// but there is no real reason not to expose it in other cases,
		// and I can see how this may be potentially useful for clients.
		$packet['id'] = $attrib['rc_id'];
		$packet['type'] = RecentChange::typeToString( $attrib['rc_type'] );

		// While sufficiently complex feed readers should be able to fetch NS
		// information from the API, full_title is provided for those who do not care,
		// allowing simplier client implementation
		$packet['namespace'] = $rc->getTitle()->getNamespace();
		$packet['title'] = $rc->getTitle()->getDBkey();
		$packet['full_title'] = $rc->getTitle()->getPrefixedText();

		$packet['comment'] = $attrib['rc_comment'];
		$packet['timestamp'] = wfTimestamp( TS_ISO_8601, $attrib['rc_timestamp'] );
		$packet['user'] = $attrib['rc_user_text'];
		$packet['bot'] = (bool)$attrib['rc_bot'];

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
