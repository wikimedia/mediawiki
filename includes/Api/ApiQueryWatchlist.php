<?php
/**
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\CommentFormatter\RowCommentFormatter;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Logging\LogEventsList;
use MediaWiki\Logging\LogFormatterFactory;
use MediaWiki\Logging\LogPage;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\ParamValidator\TypeDef\UserDef;
use MediaWiki\RecentChanges\ChangesList;
use MediaWiki\RecentChanges\ChangesListQuery\ChangesListQuery;
use MediaWiki\RecentChanges\ChangesListQuery\ChangesListQueryFactory;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\RecentChanges\RecentChangeLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\User;
use MediaWiki\Watchlist\WatchedItem;
use stdClass;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;

/**
 * This query action allows clients to retrieve a list of recently modified pages
 * that are part of the logged-in user's watchlist.
 *
 * TODO: Factor out a common base class with ApiQueryRecentChanges
 *
 * @ingroup API
 */
class ApiQueryWatchlist extends ApiQueryGeneratorBase {

	private CommentStore $commentStore;
	private RowCommentFormatter $commentFormatter;
	private TempUserConfig $tempUserConfig;
	private LogFormatterFactory $logFormatterFactory;
	private ChangesListQueryFactory $changesListQueryFactory;
	private RecentChangeLookup $recentChangeLookup;
	private TitleFormatter $titleFormatter;

	/** @var string[] */
	private $formattedComments = [];

	public function __construct(
		ApiQuery $query,
		string $moduleName,
		CommentStore $commentStore,
		ChangesListQueryFactory $changesListQueryFactory,
		RowCommentFormatter $commentFormatter,
		TempUserConfig $tempUserConfig,
		LogFormatterFactory $logFormatterFactory,
		RecentChangeLookup $recentChangeLookup,
		TitleFormatter $titleFormatter,
	) {
		parent::__construct( $query, $moduleName, 'wl' );
		$this->commentStore = $commentStore;
		$this->changesListQueryFactory = $changesListQueryFactory;
		$this->commentFormatter = $commentFormatter;
		$this->tempUserConfig = $tempUserConfig;
		$this->logFormatterFactory = $logFormatterFactory;
		$this->recentChangeLookup = $recentChangeLookup;
		$this->titleFormatter = $titleFormatter;
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

		$query = $this->changesListQueryFactory->newQuery()
			->caller( __METHOD__ )
			->watchlistUser( $wlowner );

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

		$query->orderBy(
			$params['dir'] === 'older'
			? ChangesListQuery::SORT_TIMESTAMP_DESC
			: ChangesListQuery::SORT_TIMESTAMP_ASC
		);

		$query->fields( [
			'rc_id',
			'rc_namespace',
			'rc_title',
			'rc_timestamp',
			'rc_type',
			'rc_source',
			'rc_deleted',
			'wl_notificationtimestamp'
		] );

		$rcIdFields = [
			'rc_cur_id',
			'rc_this_oldid',
			'rc_last_oldid',
		];
		if ( $resultPageSet !== null ) {
			if ( $params['allrev'] ) {
				$rcIdFields = [ 'rc_this_oldid' ];
			} else {
				$rcIdFields = [ 'rc_cur_id' ];
			}
		} else {
			$this->addFieldsToQuery( $query );
		}
		$query->fields( $rcIdFields );

		if ( $params['start'] ) {
			$query->startAt( $params['start'] );
		}
		if ( $params['end'] ) {
			$query->endAt( $params['end'] );
		}

		if ( $params['continue'] !== null ) {
			$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'string', 'int' ] );
			$query->startAt( $cont[0], $cont[1] );
		}

		if ( $params['namespace'] !== null ) {
			$query->requireNamespaces( $params['namespace'] );
		}

		if ( !$params['allrev'] ) {
			$query->excludeOldRevisions();
		}

		$watchTypes = [ 'watchedold', 'watchednew' ];
		if ( $params['show'] !== null ) {
			$show = array_fill_keys( $params['show'], true );

			/* Check for conflicting parameters. */
			if ( $this->showParamsConflicting( $show ) ) {
				$this->dieWithError( 'apierror-show' );
			}

			// Check permissions.
			if ( isset( $show['patrolled'] ) || isset( $show['!patrolled'] ) ) {
				if ( !$user->useRCPatrol() && !$user->useNPPatrol() ) {
					$this->dieWithError( 'apierror-permissiondenied-patrolflag', 'permissiondenied' );
				}
			}

			$showActions = [
				'minor' => [ 'require', 'minor', true ],
				'!minor' => [ 'exclude', 'minor', true ],
				'bot' => [ 'require', 'bot', true ],
				'!bot' => [ 'exclude', 'bot', true ],
				'anon' => [ 'exclude', 'named' ],
				'!anon' => [ 'require', 'named' ],
				'patrolled' => [ 'exclude', 'patrolled', RecentChange::PRC_UNPATROLLED ],
				'!patrolled' => [ 'require', 'patrolled', RecentChange::PRC_UNPATROLLED ],
				'autopatrolled' => [ 'require', 'patrolled', RecentChange::PRC_AUTOPATROLLED ],
				'!autopatrolled' => [ 'exclude', 'patrolled', RecentChange::PRC_AUTOPATROLLED ],
			];
			foreach ( $show as $name => $unused ) {
				if ( isset( $showActions[$name] ) ) {
					$query->applyAction( ...$showActions[$name] );
				}
			}

			if ( isset( $show['unread'] ) ) {
				$watchTypes = [ 'watchednew' ];
			}
			if ( isset( $show['!unread'] ) ) {
				$watchTypes = [ 'watchedold' ];
			}
		}

		$query->requireWatched( $watchTypes );

		if ( $params['type'] !== null ) {
			$sources = $this->recentChangeLookup->convertTypeToSources( $params['type'] );
			if ( $sources ) {
				$query->requireSources( $sources );
			}
		}

		$this->requireMaxOneParameter( $params, 'user', 'excludeuser' );
		if ( $params['user'] !== null ) {
			$query->requireUser( $params['user'] );
		}
		if ( $params['excludeuser'] !== null ) {
			$query->excludeUser( $params['excludeuser'] );
		}

		// Paranoia: avoid brute force searches (T19342)
		if ( $params['user'] !== null || $params['excludeuser'] !== null ) {
			$query->excludeDeletedUser();
		}
		$query->excludeDeletedLogAction();

		$query->limit( $params['limit'] + 1 );

		$hookData = [];
		if ( $this->getHookContainer()->isRegistered( 'ApiQueryBaseBeforeQuery' ) ) {
			$query->legacyMutator(
				function ( &$tables, &$fields, &$conds, &$options, &$join_conds ) use ( &$hookData ) {
					$this->getHookRunner()->onApiQueryBaseBeforeQuery(
						$this, $tables, $fields, $conds,
						$options, $join_conds, $hookData );
				}
			);
		}

		$ids = [];
		$res = $query->fetchResult()->getResultWrapper();

		$this->getHookRunner()->onApiQueryBaseAfterQuery( $this, $res, $hookData );

		// Do batch queries
		if ( $this->fld_title && $resultPageSet === null ) {
			$this->executeGenderCacheFromResultWrapper( $res, __METHOD__, 'rc' );
		}
		if ( $this->fld_parsedcomment ) {
			$this->formattedComments = $this->commentFormatter->formatItems(
				$this->commentFormatter->rows( $res )
					->indexField( 'rc_id' )
					->commentKey( 'rc_comment' )
					->namespaceField( 'rc_namespace' )
					->titleField( 'rc_title' )
			);
		}

		$count = 0;
		foreach ( $res as $row ) {
			if ( ++$count > $params['limit'] ) {
				// We've reached the one extra which shows that there are
				// additional pages to be had. Stop here...
				$this->setContinueEnumParameter( 'continue', "$row->rc_timestamp|$row->rc_id" );
				break;
			}

			if ( $resultPageSet === null ) {
				$vals = $this->extractOutputData( $row, $wlowner );
				$this->processRow( $row, $vals, $hookData );
				$fit = $this->getResult()->addValue( [ 'query', $this->getModuleName() ], null, $vals );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'continue', "$row->rc_timestamp|$row->rc_id" );
					break;
				}
			} elseif ( $params['allrev'] ) {
				$ids[] = (int)$row->rc_this_oldid;
			} else {
				$ids[] = (int)$row->rc_cur_id;
			}
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

	private function addFieldsToQuery( ChangesListQuery $query ) {
		$fields = [];
		if ( $this->fld_expiry ) {
			$query->maybeAddWatchlistExpiryField();
		}
		if ( $this->fld_flags ) {
			$fields[] = 'rc_minor';
			$fields[] = 'rc_bot';
		}
		if ( $this->fld_user || $this->fld_userid || $this->fld_loginfo ) {
			$query->rcUserFields();
		}
		if ( $this->fld_comment || $this->fld_parsedcomment ) {
			$query->commentFields();
		}
		if ( $this->fld_patrol ) {
			$fields[] = 'rc_patrolled';
			$fields[] = 'rc_log_type';
		}
		if ( $this->fld_sizes ) {
			$fields[] = 'rc_old_len';
			$fields[] = 'rc_new_len';
		}
		if ( $this->fld_loginfo ) {
			$fields[] = 'rc_logid';
			$fields[] = 'rc_log_type';
			$fields[] = 'rc_log_action';
			$fields[] = 'rc_params';
		}
		if ( $this->fld_tags ) {
			$query->addChangeTagSummaryField();
		}
		$query->fields( $fields );
	}

	private function showParamsConflicting( array $show ): bool {
		return ( isset( $show['minor'] ) && isset( $show['!minor'] ) )
		|| ( isset( $show['bot'] ) && isset( $show['!bot'] ) )
		|| ( isset( $show['anon'] ) && isset( $show['!anon'] ) )
		|| ( isset( $show['patrolled'] ) && isset( $show['!patrolled'] ) )
		|| ( isset( $show['autopatrolled'] ) && isset( $show['!autopatrolled'] ) )
		|| ( isset( $show['autopatrolled'] ) && isset( $show['!patrolled'] ) )
		|| ( isset( $show['unread'] ) && isset( $show['!unread'] ) );
	}

	private function extractOutputData( stdClass $row, User $wlowner ): array {
		$user = $this->getUser();
		$title = PageReferenceValue::localReference( (int)$row->rc_namespace, $row->rc_title );

		/* Our output data. */
		$vals = [];
		$vals['type'] = $this->recentChangeLookup->convertSourceToType( $row->rc_source );
		$isLog = $row->rc_source === RecentChange::SRC_LOG;
		$anyHidden = false;

		/* Create a new entry in the result for the title. */
		if ( $this->fld_title || $this->fld_ids ) {
			// These should already have been filtered out of the query, but just in case.
			if ( $isLog && ( $row->rc_deleted & LogPage::DELETED_ACTION ) ) {
				$vals['actionhidden'] = true;
				$anyHidden = true;
			}
			if ( !$isLog ||
				LogEventsList::userCanBitfield(
					$row->rc_deleted,
					LogPage::DELETED_ACTION,
					$user
				)
			) {
				if ( $this->fld_title ) {
					$vals['ns'] = $title->getNamespace();
					$vals['title'] = $this->titleFormatter->getPrefixedText( $title );
				}
				if ( $this->fld_ids ) {
					$vals['pageid'] = (int)$row->rc_cur_id;
					$vals['revid'] = (int)$row->rc_this_oldid;
					$vals['old_revid'] = (int)$row->rc_last_oldid;
				}
			}
		}

		if ( $this->fld_user || $this->fld_userid ) {
			if ( $row->rc_deleted & RevisionRecord::DELETED_USER ) {
				$vals['userhidden'] = true;
				$anyHidden = true;
			}
			if ( RevisionRecord::userCanBitfield(
				$row->rc_deleted,
				RevisionRecord::DELETED_USER,
				$user
			) ) {
				if ( $this->fld_userid ) {
					$vals['userid'] = (int)$row->rc_user;
					// for backwards compatibility
					$vals['user'] = (int)$row->rc_user;
				}

				if ( $this->fld_user ) {
					$vals['user'] = $row->rc_user_text;
					$vals['temp'] = $this->tempUserConfig->isTempName(
						$row->rc_user_text
					);
				}

				// Whether the user is a logged-out user (IP user). This does
				// not include temporary users, though they are grouped with IP
				// users for FILTER_NOT_ANON and FILTER_ANON, to match the
				// recent changes filters (T343322).
				$vals['anon'] = !$row->rc_user;
			}
		}

		/* Add flags, such as new, minor, bot. */
		if ( $this->fld_flags ) {
			$vals['bot'] = (bool)$row->rc_bot;
			$vals['new'] = $row->rc_source == RecentChange::SRC_NEW;
			$vals['minor'] = (bool)$row->rc_minor;
		}

		/* Add sizes of each revision. */
		if ( $this->fld_sizes ) {
			$vals['oldlen'] = (int)$row->rc_old_len;
			$vals['newlen'] = (int)$row->rc_new_len;
		}

		/* Add the timestamp. */
		if ( $this->fld_timestamp ) {
			$vals['timestamp'] = wfTimestamp( TS_ISO_8601, $row->rc_timestamp );
		}

		if ( $this->fld_notificationtimestamp ) {
			$vals['notificationtimestamp'] = ( $row->wl_notificationtimestamp == null )
				? ''
				: wfTimestamp( TS_ISO_8601, $row->wl_notificationtimestamp );
		}

		/* Add edit summary / log summary. */
		if ( $this->fld_comment || $this->fld_parsedcomment ) {
			if ( $row->rc_deleted & RevisionRecord::DELETED_COMMENT ) {
				$vals['commenthidden'] = true;
				$anyHidden = true;
			}
			if ( RevisionRecord::userCanBitfield(
				$row->rc_deleted,
				RevisionRecord::DELETED_COMMENT,
				$user
			) ) {
				$comment = $this->commentStore->getComment( 'rc_comment', $row )->text;
				if ( $this->fld_comment ) {
					$vals['comment'] = $comment;
				}

				if ( $this->fld_parsedcomment ) {
					$vals['parsedcomment'] = $this->formattedComments[$row->rc_id];
				}
			}
		}

		/* Add the patrolled flag */
		if ( $this->fld_patrol ) {
			$vals['patrolled'] = $row->rc_patrolled != RecentChange::PRC_UNPATROLLED;
			$vals['unpatrolled'] = ChangesList::isUnpatrolled( $row, $user );
			$vals['autopatrolled'] = $row->rc_patrolled == RecentChange::PRC_AUTOPATROLLED;
		}

		if ( $this->fld_loginfo && $row->rc_source == RecentChange::SRC_LOG ) {
			if ( $row->rc_deleted & LogPage::DELETED_ACTION ) {
				$vals['actionhidden'] = true;
				$anyHidden = true;
			}
			if ( LogEventsList::userCanBitfield(
				$row->rc_deleted,
				LogPage::DELETED_ACTION,
				$user
			) ) {
				$vals['logid'] = (int)$row->rc_logid;
				$vals['logtype'] = $row->rc_log_type;
				$vals['logaction'] = $row->rc_log_action;

				$logFormatter = $this->logFormatterFactory->newFromRow( $row );
				$vals['logparams'] = $logFormatter->formatParametersForApi();
				$vals['logdisplay'] = $logFormatter->getActionText();
			}
		}

		if ( $this->fld_tags ) {
			if ( $row->ts_tags ) {
				$tags = explode( ',', $row->ts_tags );
				ApiResult::setIndexedTagName( $tags, 'tag' );
				$vals['tags'] = $tags;
			} else {
				$vals['tags'] = [];
			}
		}

		if ( $this->fld_expiry ) {
			// Add expiration, T263796
			$expiryString = $row->we_expiry ?? null;
			if ( $expiryString ) {
				$vals['expiry'] = wfTimestamp( TS_ISO_8601, $expiryString );
			} else {
				$vals['expiry'] = false;
			}
		}

		if ( $anyHidden && ( $row->rc_deleted & RevisionRecord::DELETED_RESTRICTED ) ) {
			$vals['suppressed'] = true;
		}

		if ( $this->getHookContainer()->isRegistered( 'ApiQueryWatchlistExtractOutputData' ) ) {
			$watchedItem = new WatchedItem(
				$wlowner,
				$title,
				$row->wl_notificationtimestamp,
				$row->we_expiry ?? null
			);
			$recentChangeInfo = (array)$row;
			$this->getHookRunner()->onApiQueryWatchlistExtractOutputData(
				$this, $watchedItem, $recentChangeInfo, $vals );
		}

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
				UserDef::PARAM_RETURN_OBJECT => true,
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip', 'temp', 'id', 'interwiki' ],
			],
			'excludeuser' => [
				ParamValidator::PARAM_TYPE => 'user',
				UserDef::PARAM_RETURN_OBJECT => true,
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
					'minor',
					'!minor',
					'bot',
					'!bot',
					'anon',
					'!anon',
					'patrolled',
					'!patrolled',
					'autopatrolled',
					'!autopatrolled',
					'unread',
					'!unread',
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
