<?php
/**
 *
 *
 * Created on Sep 25, 2006
 *
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
 *
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

use MediaWiki\MediaWikiServices;

/**
 * This query action allows clients to retrieve a list of recently modified pages
 * that are part of the logged-in user's watchlist.
 *
 * @ingroup API
 */
class ApiQueryWatchlist extends ApiQueryGeneratorBase {

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'wl' );
	}

	public function execute() {
		$this->run();
	}

	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	private $fld_ids = false, $fld_title = false, $fld_patrol = false,
		$fld_flags = false, $fld_timestamp = false, $fld_user = false,
		$fld_comment = false, $fld_parsedcomment = false, $fld_sizes = false,
		$fld_notificationtimestamp = false, $fld_userid = false,
		$fld_loginfo = false;

	/**
	 * @param ApiPageSet $resultPageSet
	 * @return void
	 */
	private function run( $resultPageSet = null ) {
		$this->selectNamedDB( 'watchlist', DB_REPLICA, 'watchlist' );

		$params = $this->extractRequestParams();

		$user = $this->getUser();
		$wlowner = $this->getWatchlistUser( $params );

		if ( !is_null( $params['prop'] ) && is_null( $resultPageSet ) ) {
			$prop = array_flip( $params['prop'] );

			$this->fld_ids = isset( $prop['ids'] );
			$this->fld_title = isset( $prop['title'] );
			$this->fld_flags = isset( $prop['flags'] );
			$this->fld_user = isset( $prop['user'] );
			$this->fld_userid = isset( $prop['userid'] );
			$this->fld_comment = isset( $prop['comment'] );
			$this->fld_parsedcomment = isset( $prop['parsedcomment'] );
			$this->fld_timestamp = isset( $prop['timestamp'] );
			$this->fld_sizes = isset( $prop['sizes'] );
			$this->fld_patrol = isset( $prop['patrol'] );
			$this->fld_notificationtimestamp = isset( $prop['notificationtimestamp'] );
			$this->fld_loginfo = isset( $prop['loginfo'] );

			if ( $this->fld_patrol ) {
				if ( !$user->useRCPatrol() && !$user->useNPPatrol() ) {
					$this->dieUsage( 'patrol property is not available', 'patrol' );
				}
			}
		}

		$options = [
			'dir' => $params['dir'] === 'older'
				? WatchedItemQueryService::DIR_OLDER
				: WatchedItemQueryService::DIR_NEWER,
		];

		if ( is_null( $resultPageSet ) ) {
			$options['includeFields'] = $this->getFieldsToInclude();
		} else {
			$options['usedInGenerator'] = true;
		}

		if ( $params['start'] ) {
			$options['start'] = $params['start'];
		}
		if ( $params['end'] ) {
			$options['end'] = $params['end'];
		}

		if ( !is_null( $params['continue'] ) ) {
			$cont = explode( '|', $params['continue'] );
			$this->dieContinueUsageIf( count( $cont ) != 2 );
			$continueTimestamp = $cont[0];
			$continueId = (int)$cont[1];
			$this->dieContinueUsageIf( $continueId != $cont[1] );
			$options['startFrom'] = [ $continueTimestamp, $continueId ];
		}

		if ( $wlowner !== $user ) {
			$options['watchlistOwner'] = $wlowner;
			$options['watchlistOwnerToken'] = $params['token'];
		}

		if ( !is_null( $params['namespace'] ) ) {
			$options['namespaceIds'] = $params['namespace'];
		}

		if ( $params['allrev'] ) {
			$options['allRevisions'] = true;
		}

		if ( !is_null( $params['show'] ) ) {
			$show = array_flip( $params['show'] );

			/* Check for conflicting parameters. */
			if ( $this->showParamsConflicting( $show ) ) {
				$this->dieUsageMsg( 'show' );
			}

			// Check permissions.
			if ( isset( $show[WatchedItemQueryService::FILTER_PATROLLED] )
				|| isset( $show[WatchedItemQueryService::FILTER_NOT_PATROLLED] )
			) {
				if ( !$user->useRCPatrol() && !$user->useNPPatrol() ) {
					$this->dieUsage(
						'You need the patrol right to request the patrolled flag',
						'permissiondenied'
					);
				}
			}

			$options['filters'] = array_keys( $show );
		}

		if ( !is_null( $params['type'] ) ) {
			try {
				$options['rcTypes'] = RecentChange::parseToRCType( $params['type'] );
			} catch ( Exception $e ) {
				ApiBase::dieDebug( __METHOD__, $e->getMessage() );
			}
		}

		if ( !is_null( $params['user'] ) && !is_null( $params['excludeuser'] ) ) {
			$this->dieUsage( 'user and excludeuser cannot be used together', 'user-excludeuser' );
		}
		if ( !is_null( $params['user'] ) ) {
			$options['onlyByUser'] = $params['user'];
		}
		if ( !is_null( $params['excludeuser'] ) ) {
			$options['notByUser'] = $params['excludeuser'];
		}

		$options['limit'] = $params['limit'] + 1;

		$ids = [];
		$count = 0;
		$watchedItemQuery = MediaWikiServices::getInstance()->getWatchedItemQueryService();
		$items = $watchedItemQuery->getWatchedItemsWithRecentChangeInfo( $wlowner, $options );

		foreach ( $items as list ( $watchedItem, $recentChangeInfo ) ) {
			/** @var WatchedItem $watchedItem */
			if ( ++$count > $params['limit'] ) {
				// We've reached the one extra which shows that there are
				// additional pages to be had. Stop here...
				$this->setContinueEnumParameter(
					'continue',
					$recentChangeInfo['rc_timestamp'] . '|' . $recentChangeInfo['rc_id']
				);
				break;
			}

			if ( is_null( $resultPageSet ) ) {
				$vals = $this->extractOutputData( $watchedItem, $recentChangeInfo );
				$fit = $this->getResult()->addValue( [ 'query', $this->getModuleName() ], null, $vals );
				if ( !$fit ) {
					$this->setContinueEnumParameter(
						'continue',
						$recentChangeInfo['rc_timestamp'] . '|' . $recentChangeInfo['rc_id']
					);
					break;
				}
			} else {
				if ( $params['allrev'] ) {
					$ids[] = intval( $recentChangeInfo['rc_this_oldid'] );
				} else {
					$ids[] = intval( $recentChangeInfo['rc_cur_id'] );
				}
			}
		}

		if ( is_null( $resultPageSet ) ) {
			$this->getResult()->addIndexedTagName(
				[ 'query', $this->getModuleName() ],
				'item'
			);
		} elseif ( $params['allrev'] ) {
			$resultPageSet->populateFromRevisionIDs( $ids );
		} else {
			$resultPageSet->populateFromPageIDs( $ids );
		}
	}

	private function getFieldsToInclude() {
		$includeFields = [];
		if ( $this->fld_flags ) {
			$includeFields[] = WatchedItemQueryService::INCLUDE_FLAGS;
		}
		if ( $this->fld_user || $this->fld_userid ) {
			$includeFields[] = WatchedItemQueryService::INCLUDE_USER_ID;
		}
		if ( $this->fld_user ) {
			$includeFields[] = WatchedItemQueryService::INCLUDE_USER;
		}
		if ( $this->fld_comment || $this->fld_parsedcomment ) {
			$includeFields[] = WatchedItemQueryService::INCLUDE_COMMENT;
		}
		if ( $this->fld_patrol ) {
			$includeFields[] = WatchedItemQueryService::INCLUDE_PATROL_INFO;
		}
		if ( $this->fld_sizes ) {
			$includeFields[] = WatchedItemQueryService::INCLUDE_SIZES;
		}
		if ( $this->fld_loginfo ) {
			$includeFields[] = WatchedItemQueryService::INCLUDE_LOG_INFO;
		}
		return $includeFields;
	}

	private function showParamsConflicting( array $show ) {
		return ( isset( $show[WatchedItemQueryService::FILTER_MINOR] )
			&& isset( $show[WatchedItemQueryService::FILTER_NOT_MINOR] ) )
		|| ( isset( $show[WatchedItemQueryService::FILTER_BOT] )
			&& isset( $show[WatchedItemQueryService::FILTER_NOT_BOT] ) )
		|| ( isset( $show[WatchedItemQueryService::FILTER_ANON] )
			&& isset( $show[WatchedItemQueryService::FILTER_NOT_ANON] ) )
		|| ( isset( $show[WatchedItemQueryService::FILTER_PATROLLED] )
			&& isset( $show[WatchedItemQueryService::FILTER_NOT_PATROLLED] ) )
		|| ( isset( $show[WatchedItemQueryService::FILTER_UNREAD] )
			&& isset( $show[WatchedItemQueryService::FILTER_NOT_UNREAD] ) );
	}

	private function extractOutputData( WatchedItem $watchedItem, array $recentChangeInfo ) {
		/* Determine the title of the page that has been changed. */
		$title = Title::makeTitle(
			$watchedItem->getLinkTarget()->getNamespace(),
			$watchedItem->getLinkTarget()->getDBkey()
		);
		$user = $this->getUser();

		/* Our output data. */
		$vals = [];
		$type = intval( $recentChangeInfo['rc_type'] );
		$vals['type'] = RecentChange::parseFromRCType( $type );
		$anyHidden = false;

		/* Create a new entry in the result for the title. */
		if ( $this->fld_title || $this->fld_ids ) {
			// These should already have been filtered out of the query, but just in case.
			if ( $type === RC_LOG && ( $recentChangeInfo['rc_deleted'] & LogPage::DELETED_ACTION ) ) {
				$vals['actionhidden'] = true;
				$anyHidden = true;
			}
			if ( $type !== RC_LOG ||
				LogEventsList::userCanBitfield(
					$recentChangeInfo['rc_deleted'],
					LogPage::DELETED_ACTION,
					$user
				)
			) {
				if ( $this->fld_title ) {
					ApiQueryBase::addTitleInfo( $vals, $title );
				}
				if ( $this->fld_ids ) {
					$vals['pageid'] = intval( $recentChangeInfo['rc_cur_id'] );
					$vals['revid'] = intval( $recentChangeInfo['rc_this_oldid'] );
					$vals['old_revid'] = intval( $recentChangeInfo['rc_last_oldid'] );
				}
			}
		}

		/* Add user data and 'anon' flag, if user is anonymous. */
		if ( $this->fld_user || $this->fld_userid ) {
			if ( $recentChangeInfo['rc_deleted'] & Revision::DELETED_USER ) {
				$vals['userhidden'] = true;
				$anyHidden = true;
			}
			if ( Revision::userCanBitfield(
				$recentChangeInfo['rc_deleted'],
				Revision::DELETED_USER,
				$user
			) ) {
				if ( $this->fld_userid ) {
					$vals['userid'] = (int)$recentChangeInfo['rc_user'];
					// for backwards compatibility
					$vals['user'] = (int)$recentChangeInfo['rc_user'];
				}

				if ( $this->fld_user ) {
					$vals['user'] = $recentChangeInfo['rc_user_text'];
				}

				if ( !$recentChangeInfo['rc_user'] ) {
					$vals['anon'] = true;
				}
			}
		}

		/* Add flags, such as new, minor, bot. */
		if ( $this->fld_flags ) {
			$vals['bot'] = (bool)$recentChangeInfo['rc_bot'];
			$vals['new'] = $recentChangeInfo['rc_type'] == RC_NEW;
			$vals['minor'] = (bool)$recentChangeInfo['rc_minor'];
		}

		/* Add sizes of each revision. (Only available on 1.10+) */
		if ( $this->fld_sizes ) {
			$vals['oldlen'] = intval( $recentChangeInfo['rc_old_len'] );
			$vals['newlen'] = intval( $recentChangeInfo['rc_new_len'] );
		}

		/* Add the timestamp. */
		if ( $this->fld_timestamp ) {
			$vals['timestamp'] = wfTimestamp( TS_ISO_8601, $recentChangeInfo['rc_timestamp'] );
		}

		if ( $this->fld_notificationtimestamp ) {
			$vals['notificationtimestamp'] = ( $watchedItem->getNotificationTimestamp() == null )
				? ''
				: wfTimestamp( TS_ISO_8601, $watchedItem->getNotificationTimestamp() );
		}

		/* Add edit summary / log summary. */
		if ( $this->fld_comment || $this->fld_parsedcomment ) {
			if ( $recentChangeInfo['rc_deleted'] & Revision::DELETED_COMMENT ) {
				$vals['commenthidden'] = true;
				$anyHidden = true;
			}
			if ( Revision::userCanBitfield(
				$recentChangeInfo['rc_deleted'],
				Revision::DELETED_COMMENT,
				$user
			) ) {
				if ( $this->fld_comment && isset( $recentChangeInfo['rc_comment'] ) ) {
					$vals['comment'] = $recentChangeInfo['rc_comment'];
				}

				if ( $this->fld_parsedcomment && isset( $recentChangeInfo['rc_comment'] ) ) {
					$vals['parsedcomment'] = Linker::formatComment( $recentChangeInfo['rc_comment'], $title );
				}
			}
		}

		/* Add the patrolled flag */
		if ( $this->fld_patrol ) {
			$vals['patrolled'] = $recentChangeInfo['rc_patrolled'] == 1;
			$vals['unpatrolled'] = ChangesList::isUnpatrolled( (object)$recentChangeInfo, $user );
		}

		if ( $this->fld_loginfo && $recentChangeInfo['rc_type'] == RC_LOG ) {
			if ( $recentChangeInfo['rc_deleted'] & LogPage::DELETED_ACTION ) {
				$vals['actionhidden'] = true;
				$anyHidden = true;
			}
			if ( LogEventsList::userCanBitfield(
				$recentChangeInfo['rc_deleted'],
				LogPage::DELETED_ACTION,
				$user
			) ) {
				$vals['logid'] = intval( $recentChangeInfo['rc_logid'] );
				$vals['logtype'] = $recentChangeInfo['rc_log_type'];
				$vals['logaction'] = $recentChangeInfo['rc_log_action'];
				$vals['logparams'] = LogFormatter::newFromRow( $recentChangeInfo )->formatParametersForApi();
			}
		}

		if ( $anyHidden && ( $recentChangeInfo['rc_deleted'] & Revision::DELETED_RESTRICTED ) ) {
			$vals['suppressed'] = true;
		}

		return $vals;
	}

	public function getAllowedParams() {
		return [
			'allrev' => false,
			'start' => [
				ApiBase::PARAM_TYPE => 'timestamp'
			],
			'end' => [
				ApiBase::PARAM_TYPE => 'timestamp'
			],
			'namespace' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => 'namespace'
			],
			'user' => [
				ApiBase::PARAM_TYPE => 'user',
			],
			'excludeuser' => [
				ApiBase::PARAM_TYPE => 'user',
			],
			'dir' => [
				ApiBase::PARAM_DFLT => 'older',
				ApiBase::PARAM_TYPE => [
					'newer',
					'older'
				],
				ApiHelp::PARAM_HELP_MSG => 'api-help-param-direction',
			],
			'limit' => [
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			'prop' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DFLT => 'ids|title|flags',
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
				ApiBase::PARAM_TYPE => [
					'ids',
					'title',
					'flags',
					'user',
					'userid',
					'comment',
					'parsedcomment',
					'timestamp',
					'patrol',
					'sizes',
					'notificationtimestamp',
					'loginfo',
				]
			],
			'show' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => [
					WatchedItemQueryService::FILTER_MINOR,
					WatchedItemQueryService::FILTER_NOT_MINOR,
					WatchedItemQueryService::FILTER_BOT,
					WatchedItemQueryService::FILTER_NOT_BOT,
					WatchedItemQueryService::FILTER_ANON,
					WatchedItemQueryService::FILTER_NOT_ANON,
					WatchedItemQueryService::FILTER_PATROLLED,
					WatchedItemQueryService::FILTER_NOT_PATROLLED,
					WatchedItemQueryService::FILTER_UNREAD,
					WatchedItemQueryService::FILTER_NOT_UNREAD,
				]
			],
			'type' => [
				ApiBase::PARAM_DFLT => 'edit|new|log|categorize',
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
				ApiBase::PARAM_TYPE => RecentChange::getChangeTypes()
			],
			'owner' => [
				ApiBase::PARAM_TYPE => 'user'
			],
			'token' => [
				ApiBase::PARAM_TYPE => 'string'
			],
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=query&list=watchlist'
				=> 'apihelp-query+watchlist-example-simple',
			'action=query&list=watchlist&wlprop=ids|title|timestamp|user|comment'
				=> 'apihelp-query+watchlist-example-props',
			'action=query&list=watchlist&wlallrev=&wlprop=ids|title|timestamp|user|comment'
				=> 'apihelp-query+watchlist-example-allrev',
			'action=query&generator=watchlist&prop=info'
				=> 'apihelp-query+watchlist-example-generator',
			'action=query&generator=watchlist&gwlallrev=&prop=revisions&rvprop=timestamp|user'
				=> 'apihelp-query+watchlist-example-generator-rev',
			'action=query&list=watchlist&wlowner=Example&wltoken=123ABC'
				=> 'apihelp-query+watchlist-example-wlowner',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Watchlist';
	}
}
