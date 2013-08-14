<?php

class JSONRCFeedFormatter implements RCFeedFormatter {
	/**
	 * Generates a notification that can be easily interpreted by a machine.
	 * @see RCFeedFormatter::getLine
	 */
	public function getLine( array $feed, RecentChange $rc, $actionComment ) {
		global $wgCanonicalServer, $wgScriptPath, $wgDBname;
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
				$packet['length'] = array( 'old' => $attrib['rc_old_len'], 'new' => $attrib['rc_new_len'] );
				$packet['revision'] = array( 'old' => $attrib['rc_last_oldid'], 'new' => $attrib['rc_this_oldid'] );
				break;

			case RC_NEW:
				$packet['length'] = array( 'old' => NULL, 'new' => $attrib['rc_new_len'] );
				$packet['revision'] = array( 'old' => NULL, 'new' => $attrib['rc_this_oldid'] );
				break;

			case RC_LOG:
				$packet['log_type'] = $attrib['rc_log_type'];
				$packet['log_action'] = $attrib['rc_log_action'];
				if ( $attrib['rc_params'] ) {
					wfSuppressWarnings();
					$params = unserialize( $attrib['rc_params'] );
					wfRestoreWarnings();
					if (
						// If it's an actual serialised false...
						$attrib['rc_params'] == serialize( false ) ||
						// Or if we did not get false back when trying to unserialise
						$params !== false
					) {
						// From ApiQueryLogEvents::addLogParams
						$logParams = array();
						// Keys like "4::paramname" can't be used for output so we change them to "paramname"
						foreach ( $params as $key => $value ) {
							if ( strpos( $key, ':' ) === false ) {
								$logParams[$key] = $value;
								continue;
							}
							$logParam = explode( ':', $key, 3 );
							$logParams[$logParam[2]] = $value;
						}
						$packet['log_params'] = $logParams;
					} else {
						$packet['log_params'] = explode( "\n", $attrib['rc_params'] );
					}
				}
				$packet['log_action_comment'] = $actionComment;
				break;
		}

		$packet['server_url'] = $wgCanonicalServer;
		$packet['server_script_path'] = $wgScriptPath ?: '/';
		$packet['wiki'] = $wgDBname;

		return FormatJson::encode( $packet );
	}
}
