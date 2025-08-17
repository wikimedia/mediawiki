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

namespace MediaWiki\Api;

use MediaWiki\Cache\GenderCache;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Language\Language;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Logging\LogEventsList;
use MediaWiki\Logging\LogFormatterFactory;
use MediaWiki\Logging\LogPage;
use MediaWiki\ParamValidator\TypeDef\UserDef;
use MediaWiki\RecentChanges\ChangesList;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\Watchlist\WatchedItem;
use MediaWiki\Watchlist\WatchedItemQueryService;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;

/**
 * This query action allows clients to retrieve a list of recently modified pages
 * that are part of the logged-in user's watchlist.
 *
 * @ingroup API
 */
class ApiQueryWatchlist extends ApiQueryGeneratorBase {

	private CommentStore $commentStore;
	private WatchedItemQueryService $watchedItemQueryService;
	private Language $contentLanguage;
	private NamespaceInfo $namespaceInfo;
	private GenderCache $genderCache;
	private CommentFormatter $commentFormatter;
	private TempUserConfig $tempUserConfig;
	private LogFormatterFactory $logFormatterFactory;

	public function __construct(
		ApiQuery $query,
		string $moduleName,
		CommentStore $commentStore,
		WatchedItemQueryService $watchedItemQueryService,
		Language $contentLanguage,
		NamespaceInfo $namespaceInfo,
		GenderCache $genderCache,
		CommentFormatter $commentFormatter,
		TempUserConfig $tempUserConfig,
		LogFormatterFactory $logFormatterFactory
	) {
		parent::__construct( $query, $moduleName, 'wl' );
		$this->commentStore = $commentStore;
		$this->watchedItemQueryService = $watchedItemQueryService;
		$this->contentLanguage = $contentLanguage;
		$this->namespaceInfo = $namespaceInfo;
		$this->genderCache = $genderCache;
		$this->commentFormatter = $commentFormatter;
		$this->tempUserConfig = $tempUserConfig;
		$this->logFormatterFactory = $logFormatterFactory;
	}

	public function execute() {
		$this->run();
	}

	/** @inheritDoc */
	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	private bool $fld_ids = false;
	private bool $fld_title = false;
	private bool $fld_patrol = false;
	private bool $fld_flags = false;
	private bool $fld_timestamp = false;
	private bool $fld_user = false;
	private bool $fld_comment = false;
	private bool $fld_parsedcomment = false;
	private bool $fld_sizes = false;
	private bool $fld_notificationtimestamp = false;
	private bool $fld_userid = false;
	private bool $fld_loginfo = false;
	private bool $fld_tags = false;
	private bool $fld_expiry = false;

	/**
	 * @param ApiPageSet|null $resultPageSet
	 * @return void
	 */
	private function run( $resultPageSet = null ) {
		$params = $this->extractRequestParams();

		$user = $this->getUser();
		$wlowner = $this->getWatchlistUser( $params );

		if ( $params['prop'] !== null && $resultPageSet === null ) {
			$prop = array_fill_keys( $params['prop'], true );

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
			$this->fld_expiry = isset( $prop['expiry'] );

			if ( $this->fld_patrol && !$user->useRCPatrol() && !$user->useNPPatrol() ) {
				$this->dieWithError( 'apierror-permissiondenied-patrolflag', 'patrol' );
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
			$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'string', 'int' ] );
			$startFrom = $cont;
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
			$show = array_fill_keys( $params['show'], true );

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
			$rcTypes = RecentChange::parseToRCType( $params['type'] );
			if ( $rcTypes ) {
				$options['rcTypes'] = $rcTypes;
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
		$items = $this->watchedItemQueryService->getWatchedItemsWithRecentChangeInfo( $wlowner, $options, $startFrom );

		// Get gender information
		if ( $items !== [] && $resultPageSet === null && $this->fld_title &&
			$this->contentLanguage->needsGenderDistinction()
		) {
			$usernames = [];
			foreach ( $items as [ $watchedItem, ] ) {
				/** @var WatchedItem $watchedItem */
				$linkTarget = $watchedItem->getTarget();
				if ( $this->namespaceInfo->hasGenderDistinction( $linkTarget->getNamespace() ) ) {
					$usernames[] = $linkTarget->getText();
				}
			}
			if ( $usernames !== [] ) {
				$this->genderCache->doQuery( $usernames, __METHOD__ );
			}
		}

		foreach ( $items as [ $watchedItem, $recentChangeInfo ] ) {
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

	private function getFieldsToInclude(): array {
		$includeFields = [];
		if ( $this->fld_flags ) {
			$includeFields[] = WatchedItemQueryService::INCLUDE_FLAGS;
		}
		if ( $this->fld_user || $this->fld_userid || $this->fld_loginfo ) {
			$includeFields[] = WatchedItemQueryService::INCLUDE_USER_ID;
		}
		if ( $this->fld_user || $this->fld_loginfo ) {
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

	private function showParamsConflicting( array $show ): bool {
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

	private function extractOutputData( WatchedItem $watchedItem, array $recentChangeInfo ): array {
		/* Determine the title of the page that has been changed. */
		$target = $watchedItem->getTarget();
		if ( $target instanceof LinkTarget ) {
			$title = Title::newFromLinkTarget( $target );
		} else {
			$title = Title::newFromPageIdentity( $target );
		}
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
					$vals['temp'] = $this->tempUserConfig->isTempName(
						$recentChangeInfo['rc_user_text']
					);
				}

				// Whether the user is a logged-out user (IP user). This does
				// not include temporary users, though they are grouped with IP
				// users for FILTER_NOT_ANON and FILTER_ANON, to match the
				// recent changes filters (T343322).
				$vals['anon'] = !$recentChangeInfo['rc_user'];

			}
		}

		/* Add flags, such as new, minor, bot. */
		if ( $this->fld_flags ) {
			$vals['bot'] = (bool)$recentChangeInfo['rc_bot'];
			$vals['new'] = $recentChangeInfo['rc_source'] == RecentChange::SRC_NEW;
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
					$vals['parsedcomment'] = $this->commentFormatter->format( $comment, $title );
				}
			}
		}

		/* Add the patrolled flag */
		if ( $this->fld_patrol ) {
			$vals['patrolled'] = $recentChangeInfo['rc_patrolled'] != RecentChange::PRC_UNPATROLLED;
			$vals['unpatrolled'] = ChangesList::isUnpatrolled( (object)$recentChangeInfo, $user );
			$vals['autopatrolled'] = $recentChangeInfo['rc_patrolled'] == RecentChange::PRC_AUTOPATROLLED;
		}

		if ( $this->fld_loginfo && $recentChangeInfo['rc_source'] == RecentChange::SRC_LOG ) {
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

				$logFormatter = $this->logFormatterFactory->newFromRow( $recentChangeInfo );
				$vals['logparams'] = $logFormatter->formatParametersForApi();
				$vals['logdisplay'] = $logFormatter->getActionText();
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

		if ( $this->fld_expiry ) {
			// Add expiration, T263796
			$expiry = $watchedItem->getExpiry( TS_ISO_8601 );
			$vals['expiry'] = ( $expiry ?? false );
		}

		if ( $anyHidden && ( $recentChangeInfo['rc_deleted'] & RevisionRecord::DELETED_RESTRICTED ) ) {
			$vals['suppressed'] = true;
		}

		$this->getHookRunner()->onApiQueryWatchlistExtractOutputData(
			$this, $watchedItem, $recentChangeInfo, $vals );

		return $vals;
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
			'allrev' => false,
			'start' => [
				ParamValidator::PARAM_TYPE => 'timestamp'
			],
			'end' => [
				ParamValidator::PARAM_TYPE => 'timestamp'
			],
			'namespace' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => 'namespace'
			],
			'user' => [
				ParamValidator::PARAM_TYPE => 'user',
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip', 'temp', 'id', 'interwiki' ],
			],
			'excludeuser' => [
				ParamValidator::PARAM_TYPE => 'user',
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip', 'temp', 'id', 'interwiki' ],
			],
			'dir' => [
				ParamValidator::PARAM_DEFAULT => 'older',
				ParamValidator::PARAM_TYPE => [
					'newer',
					'older'
				],
				ApiBase::PARAM_HELP_MSG => 'api-help-param-direction',
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [
					'newer' => 'api-help-paramvalue-direction-newer',
					'older' => 'api-help-paramvalue-direction-older',
				],
			],
			'limit' => [
				ParamValidator::PARAM_DEFAULT => 10,
				ParamValidator::PARAM_TYPE => 'limit',
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => ApiBase::LIMIT_BIG1,
				IntegerDef::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			'prop' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_DEFAULT => 'ids|title|flags',
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
				ParamValidator::PARAM_TYPE => [
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
					'expiry',
				]
			],
			'show' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => [
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
				ParamValidator::PARAM_DEFAULT => 'edit|new|log|categorize',
				ParamValidator::PARAM_ISMULTI => true,
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
				ParamValidator::PARAM_TYPE => RecentChange::getChangeTypes()
			],
			'owner' => [
				ParamValidator::PARAM_TYPE => 'user',
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name' ],
			],
			'token' => [
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_SENSITIVE => true,
			],
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
		];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=query&list=watchlist'
				=> 'apihelp-query+watchlist-example-simple',
			'action=query&list=watchlist&wlprop=ids|title|timestamp|user|comment'
				=> 'apihelp-query+watchlist-example-props',
			'action=query&list=watchlist&wlprop=ids|title|timestamp|user|comment|expiry'
				=> 'apihelp-query+watchlist-example-expiry',
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

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Watchlist';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryWatchlist::class, 'ApiQueryWatchlist' );
