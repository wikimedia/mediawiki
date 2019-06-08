<?php

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

/**
 * Abstract class so there can be multiple formatters outputting the same data
 *
 * @since 1.23
 */
abstract class MachineReadableRCFeedFormatter implements RCFeedFormatter {

	/**
	 * Take the packet and return the formatted string
	 * @param array $packet
	 * @return string
	 */
	abstract protected function formatArray( array $packet );

	/**
	 * Generates a notification that can be easily interpreted by a machine.
	 * @see RCFeedFormatter::getLine
	 * @param array $feed
	 * @param RecentChange $rc
	 * @param string|null $actionComment
	 * @return string|null
	 */
	public function getLine( array $feed, RecentChange $rc, $actionComment ) {
		global $wgCanonicalServer, $wgServerName, $wgScriptPath;

		$packet = [
			// Usually, RC ID is exposed only for patrolling purposes,
			// but there is no real reason not to expose it in other cases,
			// and I can see how this may be potentially useful for clients.
			'id' => $rc->getAttribute( 'rc_id' ),
			'type' => RecentChange::parseFromRCType( $rc->getAttribute( 'rc_type' ) ),
			'namespace' => $rc->getTitle()->getNamespace(),
			'title' => $rc->getTitle()->getPrefixedText(),
			'comment' => $rc->getAttribute( 'rc_comment' ),
			'timestamp' => (int)wfTimestamp( TS_UNIX, $rc->getAttribute( 'rc_timestamp' ) ),
			'user' => $rc->getAttribute( 'rc_user_text' ),
			'bot' => (bool)$rc->getAttribute( 'rc_bot' ),
		];

		if ( isset( $feed['channel'] ) ) {
			$packet['channel'] = $feed['channel'];
		}

		$type = $rc->getAttribute( 'rc_type' );
		if ( $type == RC_EDIT || $type == RC_NEW ) {
			global $wgUseRCPatrol, $wgUseNPPatrol;

			$packet['minor'] = (bool)$rc->getAttribute( 'rc_minor' );
			if ( $wgUseRCPatrol || ( $type == RC_NEW && $wgUseNPPatrol ) ) {
				$packet['patrolled'] = (bool)$rc->getAttribute( 'rc_patrolled' );
			}
		}

		switch ( $type ) {
			case RC_EDIT:
				$packet['length'] = [
					'old' => $rc->getAttribute( 'rc_old_len' ),
					'new' => $rc->getAttribute( 'rc_new_len' )
				];
				$packet['revision'] = [
					'old' => $rc->getAttribute( 'rc_last_oldid' ),
					'new' => $rc->getAttribute( 'rc_this_oldid' )
				];
				break;

			case RC_NEW:
				$packet['length'] = [ 'old' => null, 'new' => $rc->getAttribute( 'rc_new_len' ) ];
				$packet['revision'] = [ 'old' => null, 'new' => $rc->getAttribute( 'rc_this_oldid' ) ];
				break;

			case RC_LOG:
				$packet['log_id'] = $rc->getAttribute( 'rc_logid' );
				$packet['log_type'] = $rc->getAttribute( 'rc_log_type' );
				$packet['log_action'] = $rc->getAttribute( 'rc_log_action' );
				if ( $rc->getAttribute( 'rc_params' ) ) {
					$params = $rc->parseParams();
					if (
						// If it's an actual serialised false...
						$rc->getAttribute( 'rc_params' ) == serialize( false ) ||
						// Or if we did not get false back when trying to unserialise
						$params !== false
					) {
						// From ApiQueryLogEvents::addLogParams
						$logParams = [];
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
						$packet['log_params'] = explode( "\n", $rc->getAttribute( 'rc_params' ) );
					}
				}
				$packet['log_action_comment'] = $actionComment;
				break;
		}

		$packet['server_url'] = $wgCanonicalServer;
		$packet['server_name'] = $wgServerName;

		$packet['server_script_path'] = $wgScriptPath ?: '/';
		$packet['wiki'] = WikiMap::getWikiIdFromDbDomain( WikiMap::getCurrentWikiDbDomain() );

		return $this->formatArray( $packet );
	}
}
