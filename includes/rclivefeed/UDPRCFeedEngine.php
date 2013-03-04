<?php
class UDPRCFeedEngine extends RCFeedEngine {
	public function send( $line, $feed, $prefix ) {
		# Notify external application via UDP
		$conn = socket_create( AF_INET, SOCK_DGRAM, SOL_UDP );
		if ( $conn ) {
			$line = $prefix . $line;
			wfDebug( __METHOD__ . ": sending UDP line: $line\n" );
			socket_sendto( $conn, $line, strlen( $line ), 0, $feed['address'], $feed['port'] );
			socket_close( $conn );
			return true;
		} else {
			wfDebug( __METHOD__ . ": failed to create UDP socket\n" );
		}
		return false;
	}

	public function getLine( $feed, $rc, $actionComment ) {
		if ( !isset( $feed['format'] ) || $feed['format'] == 'ircpretty' ) {
			return $this->getColourfulIRCLine( $feed, $rc, $actionComment );
		} elseif ( $feed['format'] == 'json' ) {
			return $this->getJSONLine( $feed, $rc, $actionComment );
		}
	}

	public function getColourfulIRCLine( $feed, $rc, $actionComment ) {
		$attribs = $rc->getAttributes();
		global $wgUseRCPatrol, $wgUseNPPatrol, $wgLocalInterwiki,
			$wgCanonicalServer, $wgScript;
		if ( $attribs['rc_type'] == RC_LOG ) {
			// Don't use SpecialPage::getTitleFor, backwards compatibility with
			// IRC API which expects "Log".
			$titleObj = Title::newFromText( 'Log/' . $attribs['rc_log_type'], NS_SPECIAL );
		} else {
			$titleObj =& $rc->getTitle();
		}
		$title = $titleObj->getPrefixedText();
		$title = self::cleanupForIRC( $title );

		if ( $attribs['rc_type'] == RC_LOG ) {
			$url = '';
		} else {
			$url = $wgCanonicalServer . $wgScript;
			if ( $attribs['rc_type'] == RC_NEW ) {
				$query = '?oldid=' . $attribs['rc_this_oldid'];
			} else {
				$query = '?diff=' . $attribs['rc_this_oldid'] . '&oldid=' . $attribs['rc_last_oldid'];
			}
			if ( $wgUseRCPatrol || ( $attribs['rc_type'] == RC_NEW && $wgUseNPPatrol ) ) {
				$query .= '&rcid=' . $attribs['rc_id'];
			}
			// HACK: We need this hook for WMF's secure server setup
			wfRunHooks( 'IRCLineURL', array( &$url, &$query ) );
			$url .= $query;
		}

		if ( $attribs['rc_old_len'] !== null && $attribs['rc_new_len'] !== null ) {
			$szdiff = $attribs['rc_new_len'] - $attribs['rc_old_len'];
			if ( $szdiff < -500 ) {
				$szdiff = "\002$szdiff\002";
			} elseif ( $szdiff >= 0 ) {
				$szdiff = '+' . $szdiff;
			}
			// @todo i18n with parentheses in content language?
			$szdiff = '(' . $szdiff . ')';
		} else {
			$szdiff = '';
		}

		$user = self::cleanupForIRC( $attribs['rc_user_text'] );

		if ( $attribs['rc_type'] == RC_LOG ) {
			$targetText = $rc->getTitle()->getPrefixedText();
			$comment = self::cleanupForIRC( str_replace( "[[$targetText]]", "[[\00302$targetText\00310]]", $actionComment ) );
			$flag = $attribs['rc_log_action'];
		} else {
			$comment = self::cleanupForIRC( $attribs['rc_comment'] );
			$flag = '';
			if ( !$attribs['rc_patrolled'] && ( $wgUseRCPatrol || $attribs['rc_type'] == RC_NEW && $wgUseNPPatrol ) ) {
				$flag .= '!';
			}
			$flag .= ( $attribs['rc_type'] == RC_NEW ? "N" : "" ) . ( $attribs['rc_minor'] ? "M" : "" ) . ( $attribs['rc_bot'] ? "B" : "" );
		}

		if ( $feed['interwiki_prefix'] === true && $wgLocalInterwiki !== false ) {
			$prefix = $wgLocalInterwiki;
		} elseif ( $feed['interwiki_prefix'] ) {
			$prefix = $feed['interwiki_prefix'];
		} else {
			$prefix = false;
		}
		if ( $prefix !== false ) {
			$titleString = "\00314[[\00303$prefix:\00307$title\00314]]";
		} else {
			$titleString = "\00314[[\00307$title\00314]]";
		}

		# see http://www.irssi.org/documentation/formats for some colour codes. prefix is \003,
		# no colour (\003) switches back to the term default
		$fullString = "$titleString\0034 $flag\00310 " .
			"\00302$url\003 \0035*\003 \00303$user\003 \0035*\003 $szdiff \00310$comment\003\n";

		return $fullString;
	}

	public function getJSONLine( $feed, $rc, $actionComment ) {
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

	/**
	 * Remove newlines, carriage returns and decode html entites
	 * @param $text string
	 * @return string
	 */
	public static function cleanupForIRC( $text ) {
		return Sanitizer::decodeCharReferences( str_replace(
			array( "\n", "\r" ),
			array( " ", "" ),
			$text
		) );
	}
}
