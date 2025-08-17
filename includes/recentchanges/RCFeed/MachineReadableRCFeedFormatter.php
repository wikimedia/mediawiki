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

namespace MediaWiki\RCFeed;

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\WikiMap\WikiMap;

/**
 * Abstract class so there can be multiple formatters outputting the same data
 *
 * @since 1.23
 * @ingroup RecentChanges
 */
abstract class MachineReadableRCFeedFormatter implements RCFeedFormatter {

	/**
	 * Take the packet and return the formatted string
	 *
	 * @param array $packet
	 *
	 * @return string
	 */
	abstract protected function formatArray( array $packet );

	/**
	 * Generates a notification that can be easily interpreted by a machine.
	 *
	 * @see RCFeedFormatter::getLine
	 *
	 * @param array $feed
	 * @param RecentChange $rc
	 * @param string|null $actionComment
	 *
	 * @return string|null
	 */
	public function getLine( array $feed, RecentChange $rc, $actionComment ) {
		$mainConfig = MediaWikiServices::getInstance()->getMainConfig();
		$canonicalServer = $mainConfig->get( MainConfigNames::CanonicalServer );
		$serverName = $mainConfig->get( MainConfigNames::ServerName );
		$scriptPath = $mainConfig->get( MainConfigNames::ScriptPath );
		$packet = [
			// Usually, RC ID is exposed only for patrolling purposes,
			// but there is no real reason not to expose it in other cases,
			// and I can see how this may be potentially useful for clients.
			'id' => $rc->getAttribute( 'rc_id' ),
			'type' => RecentChange::parseFromRCType( $rc->getAttribute( 'rc_type' ) ),
			'namespace' => $rc->getTitle()->getNamespace(),
			'title' => $rc->getTitle()->getPrefixedText(),
			'title_url' => $rc->getTitle()->getCanonicalURL(),
			'comment' => $rc->getAttribute( 'rc_comment' ),
			'timestamp' => (int)wfTimestamp( TS_UNIX, $rc->getAttribute( 'rc_timestamp' ) ),
			'user' => $rc->getAttribute( 'rc_user_text' ),
			'bot' => (bool)$rc->getAttribute( 'rc_bot' ),
			'notify_url' => $rc->getNotifyUrl(),
		];

		if ( isset( $feed['channel'] ) ) {
			$packet['channel'] = $feed['channel'];
		}

		$source = $rc->getAttribute( 'rc_source' );
		if ( $source == RecentChange::SRC_EDIT || $source == RecentChange::SRC_NEW ) {
			$useRCPatrol = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::UseRCPatrol );
			$useNPPatrol = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::UseNPPatrol );
			$packet['minor'] = (bool)$rc->getAttribute( 'rc_minor' );
			if ( $useRCPatrol || ( $source == RecentChange::SRC_NEW && $useNPPatrol ) ) {
				$packet['patrolled'] = (bool)$rc->getAttribute( 'rc_patrolled' );
			}
		}

		switch ( $source ) {
			case RecentChange::SRC_EDIT:
				$packet['length'] = [
					'old' => $rc->getAttribute( 'rc_old_len' ),
					'new' => $rc->getAttribute( 'rc_new_len' )
				];
				$packet['revision'] = [
					'old' => $rc->getAttribute( 'rc_last_oldid' ),
					'new' => $rc->getAttribute( 'rc_this_oldid' )
				];
				break;

			case RecentChange::SRC_NEW:
				$packet['length'] = [ 'old' => null, 'new' => $rc->getAttribute( 'rc_new_len' ) ];
				$packet['revision'] = [ 'old' => null, 'new' => $rc->getAttribute( 'rc_this_oldid' ) ];
				break;

			case RecentChange::SRC_LOG:
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
							if ( !str_contains( $key, ':' ) ) {
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

		$packet['server_url'] = $canonicalServer;
		$packet['server_name'] = $serverName;

		$packet['server_script_path'] = $scriptPath ?: '/';
		$packet['wiki'] = WikiMap::getCurrentWikiId();

		return $this->formatArray( $packet );
	}
}
/** @deprecated class alias since 1.43 */
class_alias( MachineReadableRCFeedFormatter::class, 'MachineReadableRCFeedFormatter' );
