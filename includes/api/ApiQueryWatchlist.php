<?php
/**
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
use MediaWiki\ParamValidator\TypeDef\UserDef;
use MediaWiki\Revision\RevisionRecord;

/**
 * This query action allows clients to retrieve a list of recently modified pages
 * that are part of the logged-in user's watchlist.
 *
 * @ingroup API
 */
class ApiQueryWatchlist extends ApiQueryGeneratorBase {

	/** @var CommentStore */
	private $commentStore;

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
		$fld_loginfo = false, $fld_tags;

	/**
	 * @param ApiPageSet|null $resultPageSet
	 * @return void
	 */
	private function run( $resultPageSet = null ) {
		$this->selectNamedDB( 'watchlist', DB_REPLICA, 'watchlist' );

		$params = $this->extractRequestParams();

		$user = $this->getUser();
		$wlowner = $this->getWatchlistUser( $params );

		if ( $params['prop'] !== null && $resultPageSet === null ) {
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
			$this->fld_tags = isset( $prop['tags'] );

			if ( $this->fld_patrol && !$user->useRCPatrol() && !$user->useNPPatrol() ) {
				$this->dieWithError( 'apierror-permissiondenied-patrolflag', 'patrol' );
			}

			if ( $this->fld_comment || $this->fld_parsedcomment ) {
				$this->commentStore = CommentStore::getStore();
			}
		}

		$options = [
			'dir' => $params['dir'] === 'older'
				? WatchedItemQueryService::DIR_OLDER
				: WatchedItemQueryService::DIR_NEWER,
		];

		if ( $resultPageSet === null ) {
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

		$startFrom = null;
		if ( $params['continue'] !== null ) {
			$cont = explode( '|', $params['continue'] );
			$this->dieContinueUsageIf( count( $cont ) != 2 );
			$continueTimestamp = $cont[0];
			$continueId = (int)$cont[1];
			$this->dieContinueUsageIf( $continueId != $cont[1] );
			$startFrom = [ $continueTimestamp, $continueId ];
		}

		if ( $wlowner !== $user ) {
			$options['watchlistOwner'] = $wlowner;
			$options['watchlistOwnerToken'] = $params['token'];
		}

		if ( $params['namespace'] !== null ) {
			$options['namespaceIds'] = $params['namespace'];
		}

		if ( $params['allrev'] ) {
			$options['allRevisions'] = true;
		}

		if ( $params['show'] !== null ) {
			$show = array_flip( $params['show'] );

			/* Check for conflicting parameters. */
			if ( $this->showParamsConflicting( $show ) ) {
				$this->dieWithError( 'apierror-show' );
			}

			// Check permissions.
			if ( isset( $show[WatchedItemQueryService::FILTER_PATROLLED] )
				|| isset( $show[WatchedItemQueryService::FILTER_NOT_PATROLLED] )
			) {
				if ( !$user->useRCPatrol() && !$user->useNPPatrol() ) {
					$this->dieWithError( 'apierror-permissiondenied-patrolflag', 'permissiondenied' );
				}
			}

			$options['filters'] = array_keys( $show );
		}

		if ( $params['type'] !== null ) {
			try {
				$rcTypes = RecentChange::parseToRCType( $params['type'] );
				if ( $rcTypes ) {
					$options['rcTypes'] = $rcTypes;
				}
			} catch ( Exception $e ) {
				ApiBase::dieDebug( __METHOD__, $e->getMessage() );
			}
		}

		$this->requireMaxOneParameter( $params, 'user', 'excludeuser' );
		if ( $params['user'] !== null ) {
			$options['onlyByUser'] = $params['user'];
		}
		if ( $params['excludeuser'] !== null ) {
			$options['notByUser'] = $params['excludeuser'];
		}

		$options['limit'] = $params['limit'];

		$this->getHookRunner()->onApiQueryWatchlistPrepareWatchedItemQueryServiceOptions(
			$this, $params, $options );

		$ids = [];
		$services = MediaWikiServices::getInstance();
		$watchedItemQuery = $services->getWatchedItemQueryService();
		$items = $watchedItemQuery->getWatchedItemsWithRecentChangeInfo( $wlowner, $options, $startFrom );

		// Get gender information
		if ( $items !== [] && $resultPageSet === null && $this->fld_title &&
			$services->getContentLanguage()->needsGenderDistinction()
		) {
			$nsInfo = $services->getNamespaceInfo();
			$usernames = [];
			foreach ( $items as list( $watchedItem, $recentChangeInfo ) ) {
				/** @var WatchedItem $watchedItem */
				$linkTarget = $watchedItem->getLinkTarget();
				if ( $nsInfo->hasGenderDistinction( $linkTarget->getNamespace() ) ) {
					$usernames[] = $linkTarget->getText();
				}
			}
			if ( $usernames !== [] ) {
				$services->getGenderCache()->doQuery( $usernames, __METHOD__ );
			}
		}

		foreach ( $items as list( $watchedItem, $recentChangeInfo ) ) {
			/** @var WatchedItem $watchedItem */
			if ( $resultPageSet === null ) {
				$vals = $this->extractOutputData( $watchedItem, $recentChangeInfo );
				$fit = $this->getResult()->addValue( [ 'query', $this->getModuleName() ], null, $vals );
				if ( !$fit ) {
					$startFrom = [ $recentChangeInfo['rc_timestamp'], $recentChangeInfo['rc_id'] ];
					break;
				}
			} elseif ( $params['allrev'] ) {
				$ids[] = (int)$recentChangeInfo['rc_this_oldid'];
			} else {
				$ids[] = (int)$recentChangeInfo['rc_cur_id'];
			}
		}

		if ( $startFrom !== null ) {
			$this->setContinueEnumParameter( 'continue', implode( '|', $startFrom ) );
		}

		if ( $resultPageSet === null ) {
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
			$includeFields[] = WatchedItemQueryService::INCLUDE_AUTOPATROL_INFO;
		}
		if ( $this->fld_sizes ) {
			$includeFields[] = WatchedItemQueryService::INCLUDE_SIZES;
		}
		if ( $this->fld_loginfo ) {
			$includeFields[] = WatchedItemQueryService::INCLUDE_LOG_INFO;
		}
		if ( $this->fld_tags ) {
			$includeFields[] = WatchedItemQueryService::INCLUDE_TAGS;
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
		|| ( isset( $show[WatchedItemQueryService::FILTER_AUTOPATROLLED] )
			&& isset( $show[WatchedItemQueryService::FILTER_NOT_AUTOPATROLLED] ) )
		|| ( isset( $show[WatchedItemQueryService::FILTER_AUTOPATROLLED] )
			&& isset( $show[WatchedItemQueryService::FILTER_NOT_PATROLLED] ) )
		|| ( isset( $show[WatchedItemQueryService::FILTER_UNREAD] )
			&& isset( $show[WatchedItemQueryService::FILTER_NOT_UNREAD] ) );
	}

	private function extractOutputData( WatchedItem $watchedItem, array $recentChangeInfo ) {
		/* Determine the title of the page that has been changed. */
		$title = Title::newFromLinkTarget( $watchedItem->getLinkTarget() );
		$user = $this->getUser();

		/* Our output data. */
		$vals = [];
		$type = (int)$recentChangeInfo['rc_type'];
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
					$vals['pageid'] = (int)$recentChangeInfo['rc_cur_id'];
					$vals['revid'] = (int)$recentChangeInfo['rc_this_oldid'];
					$vals['old_revid'] = (int)$recentChangeInfo['rc_last_oldid'];
				}
			}
		}

		/* Add user data and 'anon' flag, if user is anonymous. */
		if ( $this->fld_user || $this->fld_userid ) {
			if ( $recentChangeInfo['rc_deleted'] & RevisionRecord::DELETED_USER ) {
				$vals['userhidden'] = true;
				$anyHidden = true;
			}
			if ( RevisionRecord::userCanBitfield(
				$recentChangeInfo['rc_deleted'],
				RevisionRecord::DELETED_USER,
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
			$vals['oldlen'] = (int)$recentChangeInfo['rc_old_len'];
			$vals['newlen'] = (int)$recentChangeInfo['rc_new_len'];
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
			if ( $recentChangeInfo['rc_deleted'] & RevisionRecord::DELETED_COMMENT ) {
				$vals['commenthidden'] = true;
				$anyHidden = true;
			}
			if ( RevisionRecord::userCanBitfield(
				$recentChangeInfo['rc_deleted'],
				RevisionRecord::DELETED_COMMENT,
				$user
			) ) {
				$comment = $this->commentStore->getComment( 'rc_comment', $recentChangeInfo )->text;
				if ( $this->fld_comment ) {
					$vals['comment'] = $comment;
				}

				if ( $this->fld_parsedcomment ) {
					$vals['parsedcomment'] = Linker::formatComment( $comment, $title );
				}
			}
		}

		/* Add the patrolled flag */
		if ( $this->fld_patrol ) {
			$vals['patrolled'] = $recentChangeInfo['rc_patrolled'] != RecentChange::PRC_UNPATROLLED;
			$vals['unpatrolled'] = ChangesList::isUnpatrolled( (object)$recentChangeInfo, $user );
			$vals['autopatrolled'] = $recentChangeInfo['rc_patrolled'] == RecentChange::PRC_AUTOPATROLLED;
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
				$vals['logid'] = (int)$recentChangeInfo['rc_logid'];
				$vals['logtype'] = $recentChangeInfo['rc_log_type'];
				$vals['logaction'] = $recentChangeInfo['rc_log_action'];
				$vals['logparams'] = LogFormatter::newFromRow( $recentChangeInfo )->formatParametersForApi();
			}
		}

		if ( $this->fld_tags ) {
			if ( $recentChangeInfo['rc_tags'] ) {
				$tags = explode( ',', $recentChangeInfo['rc_tags'] );
				ApiResult::setIndexedTagName( $tags, 'tag' );
				$vals['tags'] = $tags;
			} else {
				$vals['tags'] = [];
			}
		}

		if ( $anyHidden && ( $recentChangeInfo['rc_deleted'] & RevisionRecord::DELETED_RESTRICTED ) ) {
			$vals['suppressed'] = true;
		}

		$this->getHookRunner()->onApiQueryWatchlistExtractOutputData(
			$this, $watchedItem, $recentChangeInfo, $vals );

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
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip', 'id', 'interwiki' ],
			],
			'excludeuser' => [
				ApiBase::PARAM_TYPE => 'user',
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip', 'id', 'interwiki' ],
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
					'tags',
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
					WatchedItemQueryService::FILTER_AUTOPATROLLED,
					WatchedItemQueryService::FILTER_NOT_AUTOPATROLLED,
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
				ApiBase::PARAM_TYPE => 'user',
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name' ],
			],
			'token' => [
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_SENSITIVE => true,
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
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Watchlist';
	}
}
