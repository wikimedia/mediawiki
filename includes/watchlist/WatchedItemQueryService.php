<?php

namespace MediaWiki\Watchlist;

use MediaWiki\Api\ApiUsageException;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Logging\LogPage;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\Authority;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Title\TitleValue;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentity;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * Class performing complex database queries related to WatchedItems.
 *
 * @since 1.28
 *
 * @file
 * @ingroup Watchlist
 *
 * @license GPL-2.0-or-later
 */
class WatchedItemQueryService {

	public const DIR_OLDER = 'older';
	public const DIR_NEWER = 'newer';

	public const INCLUDE_FLAGS = 'flags';
	public const INCLUDE_USER = 'user';
	public const INCLUDE_USER_ID = 'userid';
	public const INCLUDE_COMMENT = 'comment';
	public const INCLUDE_PATROL_INFO = 'patrol';
	public const INCLUDE_AUTOPATROL_INFO = 'autopatrol';
	public const INCLUDE_SIZES = 'sizes';
	public const INCLUDE_LOG_INFO = 'loginfo';
	public const INCLUDE_TAGS = 'tags';

	// FILTER_* constants are part of public API (are used in ApiQueryWatchlist and
	// ApiQueryWatchlistRaw classes) and should not be changed.
	// Changing values of those constants will result in a breaking change in the API
	public const FILTER_MINOR = 'minor';
	public const FILTER_NOT_MINOR = '!minor';
	public const FILTER_BOT = 'bot';
	public const FILTER_NOT_BOT = '!bot';
	public const FILTER_ANON = 'anon';
	public const FILTER_NOT_ANON = '!anon';
	public const FILTER_PATROLLED = 'patrolled';
	public const FILTER_NOT_PATROLLED = '!patrolled';
	public const FILTER_AUTOPATROLLED = 'autopatrolled';
	public const FILTER_NOT_AUTOPATROLLED = '!autopatrolled';
	public const FILTER_UNREAD = 'unread';
	public const FILTER_NOT_UNREAD = '!unread';
	public const FILTER_CHANGED = 'changed';
	public const FILTER_NOT_CHANGED = '!changed';

	public const SORT_ASC = 'ASC';
	public const SORT_DESC = 'DESC';

	/**
	 * @var IConnectionProvider
	 */
	private $dbProvider;

	/** @var WatchedItemQueryServiceExtension[]|null */
	private $extensions = null;

	/** @var CommentStore */
	private $commentStore;

	/** @var WatchedItemStoreInterface */
	private $watchedItemStore;

	/** @var HookRunner */
	private $hookRunner;

	/** @var UserOptionsLookup */
	private $userOptionsLookup;

	/** @var TempUserConfig */
	private $tempUserConfig;

	/**
	 * @var bool Correlates to $wgWatchlistExpiry feature flag.
	 */
	private $expiryEnabled;

	/**
	 * @var int Max query execution time
	 */
	private $maxQueryExecutionTime;

	public function __construct(
		IConnectionProvider $dbProvider,
		CommentStore $commentStore,
		WatchedItemStoreInterface $watchedItemStore,
		HookContainer $hookContainer,
		UserOptionsLookup $userOptionsLookup,
		TempUserConfig $tempUserConfig,
		bool $expiryEnabled = false,
		int $maxQueryExecutionTime = 0
	) {
		$this->dbProvider = $dbProvider;
		$this->commentStore = $commentStore;
		$this->watchedItemStore = $watchedItemStore;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->userOptionsLookup = $userOptionsLookup;
		$this->tempUserConfig = $tempUserConfig;
		$this->expiryEnabled = $expiryEnabled;
		$this->maxQueryExecutionTime = $maxQueryExecutionTime;
	}

	/**
	 * @return WatchedItemQueryServiceExtension[]
	 */
	private function getExtensions() {
		if ( $this->extensions === null ) {
			$this->extensions = [];
			$this->hookRunner->onWatchedItemQueryServiceExtensions( $this->extensions, $this );
		}
		return $this->extensions;
	}

	/**
	 * @param User $user
	 * @param array $options Allowed keys:
	 *        'includeFields'       => string[] RecentChange fields to be included in the result,
	 *                                 self::INCLUDE_* constants should be used
	 *        'filters'             => string[] optional filters to narrow down resulted items
	 *        'namespaceIds'        => int[] optional namespace IDs to filter by
	 *                                 (defaults to all namespaces)
	 *        'allRevisions'        => bool return multiple revisions of the same page if true,
	 *                                 only the most recent if false (default)
	 *        'rcTypes'             => int[] which types of RecentChanges to include
	 *                                 (defaults to all types), allowed values: RC_EDIT, RC_NEW,
	 *                                 RC_LOG, RC_EXTERNAL, RC_CATEGORIZE
	 *        'onlyByUser'          => string only list changes by a specified user
	 *        'notByUser'           => string do not include changes by a specified user
	 *        'dir'                 => string in which direction to enumerate, accepted values:
	 *                                 - DIR_OLDER list newest first
	 *                                 - DIR_NEWER list oldest first
	 *        'start'               => string (format accepted by wfTimestamp) requires 'dir' option,
	 *                                 timestamp to start enumerating from
	 *        'end'                 => string (format accepted by wfTimestamp) requires 'dir' option,
	 *                                 timestamp to end enumerating
	 *        'watchlistOwner'      => UserIdentity user whose watchlist items should be listed if different
	 *                                 than the one specified with $user param, requires
	 *                                 'watchlistOwnerToken' option
	 *        'watchlistOwnerToken' => string a watchlist token used to access another user's
	 *                                 watchlist, used with 'watchlistOwnerToken' option
	 *        'limit'               => int maximum numbers of items to return
	 *        'usedInGenerator'     => bool include only RecentChange id field required by the
	 *                                 generator ('rc_cur_id' or 'rc_this_oldid') if true, or all
	 *                                 id fields ('rc_cur_id', 'rc_this_oldid', 'rc_last_oldid')
	 *                                 if false (default)
	 * @param array|null &$startFrom Continuation value: [ string $rcTimestamp, int $rcId ]
	 * @return array[] Array of pairs ( WatchedItem $watchedItem, string[] $recentChangeInfo ),
	 *         where $recentChangeInfo contains the following keys:
	 *         - 'rc_id',
	 *         - 'rc_namespace',
	 *         - 'rc_title',
	 *         - 'rc_timestamp',
	 *         - 'rc_type',
	 *         - 'rc_source',
	 *         - 'rc_deleted',
	 *         Additional keys could be added by specifying the 'includeFields' option
	 */
	public function getWatchedItemsWithRecentChangeInfo(
		User $user, array $options = [], &$startFrom = null
	) {
		$options += [
			'includeFields' => [],
			'namespaceIds' => [],
			'filters' => [],
			'allRevisions' => false,
			'usedInGenerator' => false
		];

		Assert::parameter(
			!isset( $options['rcTypes'] )
				|| !array_diff( $options['rcTypes'], [ RC_EDIT, RC_NEW, RC_LOG, RC_EXTERNAL, RC_CATEGORIZE ] ),
			'$options[\'rcTypes\']',
			'must be an array containing only: RC_EDIT, RC_NEW, RC_LOG, RC_EXTERNAL and/or RC_CATEGORIZE'
		);
		Assert::parameter(
			!isset( $options['dir'] ) || in_array( $options['dir'], [ self::DIR_OLDER, self::DIR_NEWER ] ),
			'$options[\'dir\']',
			'must be DIR_OLDER or DIR_NEWER'
		);
		Assert::parameter(
			( !isset( $options['start'] ) && !isset( $options['end'] ) && $startFrom === null )
				|| isset( $options['dir'] ),
			'$options[\'dir\']',
			'must be provided when providing the "start" or "end" options or the $startFrom parameter'
		);
		Assert::parameter(
			!isset( $options['startFrom'] ),
			'$options[\'startFrom\']',
			'must not be provided, use $startFrom instead'
		);
		Assert::parameter(
			$startFrom === null || ( is_array( $startFrom ) && count( $startFrom ) === 2 ),
			'$startFrom',
			'must be a two-element array'
		);
		if ( array_key_exists( 'watchlistOwner', $options ) ) {
			Assert::parameterType(
				UserIdentity::class,
				$options['watchlistOwner'],
				'$options[\'watchlistOwner\']'
			);
			Assert::parameter(
				isset( $options['watchlistOwnerToken'] ),
				'$options[\'watchlistOwnerToken\']',
				'must be provided when providing watchlistOwner option'
			);
		}

		$db = $this->dbProvider->getReplicaDatabase();

		$tables = $this->getWatchedItemsWithRCInfoQueryTables( $options );
		$fields = $this->getWatchedItemsWithRCInfoQueryFields( $options );
		$conds = $this->getWatchedItemsWithRCInfoQueryConds( $db, $user, $options );
		$dbOptions = $this->getWatchedItemsWithRCInfoQueryDbOptions( $options );
		$joinConds = $this->getWatchedItemsWithRCInfoQueryJoinConds( $options );

		if ( $startFrom !== null ) {
			$conds[] = $this->getStartFromConds( $db, $options, $startFrom );
		}

		foreach ( $this->getExtensions() as $extension ) {
			$extension->modifyWatchedItemsWithRCInfoQuery(
				$user, $options, $db,
				$tables,
				$fields,
				$conds,
				$dbOptions,
				$joinConds
			);
		}

		$res = $db->newSelectQueryBuilder()
			->tables( $tables )
			->fields( $fields )
			->conds( $conds )
			->caller( __METHOD__ )
			->options( $dbOptions )
			->joinConds( $joinConds )
			->fetchResultSet();

		$limit = $dbOptions['LIMIT'] ?? INF;
		$items = [];
		$startFrom = null;
		foreach ( $res as $row ) {
			if ( --$limit <= 0 ) {
				$startFrom = [ $row->rc_timestamp, $row->rc_id ];
				break;
			}

			$target = new TitleValue( (int)$row->rc_namespace, $row->rc_title );
			$items[] = [
				new WatchedItem(
					$user,
					$target,
					$this->watchedItemStore->getLatestNotificationTimestamp(
						$row->wl_notificationtimestamp, $user, $target
					),
					$row->we_expiry ?? null
				),
				$this->getRecentChangeFieldsFromRow( $row )
			];
		}

		foreach ( $this->getExtensions() as $extension ) {
			$extension->modifyWatchedItemsWithRCInfo( $user, $options, $db, $items, $res, $startFrom );
		}

		return $items;
	}

	/**
	 * For simple listing of user's watchlist items, see WatchedItemStore::getWatchedItemsForUser
	 *
	 * @param UserIdentity $user
	 * @param array $options Allowed keys:
	 *        'sort'         => string optional sorting by namespace ID and title
	 *                          one of the self::SORT_* constants
	 *        'namespaceIds' => int[] optional namespace IDs to filter by (defaults to all namespaces)
	 *        'limit'        => int maximum number of items to return
	 *        'filter'       => string optional filter, one of the self::FILTER_* constants
	 *        'from'         => LinkTarget requires 'sort' key, only return items starting from
	 *                          those related to the link target
	 *        'until'        => LinkTarget requires 'sort' key, only return items until
	 *                          those related to the link target
	 *        'startFrom'    => LinkTarget requires 'sort' key, only return items starting from
	 *                          those related to the link target, allows to skip some link targets
	 *                          specified using the form option
	 * @return WatchedItem[]
	 */
	public function getWatchedItemsForUser( UserIdentity $user, array $options = [] ) {
		if ( !$user->isRegistered() ) {
			// TODO: should this just return an empty array or rather complain loud at this point
			// as e.g. ApiBase::getWatchlistUser does?
			return [];
		}

		$options += [ 'namespaceIds' => [] ];

		Assert::parameter(
			!isset( $options['sort'] ) || in_array( $options['sort'], [ self::SORT_ASC, self::SORT_DESC ] ),
			'$options[\'sort\']',
			'must be SORT_ASC or SORT_DESC'
		);
		Assert::parameter(
			!isset( $options['filter'] ) || in_array(
				$options['filter'], [ self::FILTER_CHANGED, self::FILTER_NOT_CHANGED ]
			),
			'$options[\'filter\']',
			'must be FILTER_CHANGED or FILTER_NOT_CHANGED'
		);
		Assert::parameter(
			( !isset( $options['from'] ) && !isset( $options['until'] ) && !isset( $options['startFrom'] ) )
				|| isset( $options['sort'] ),
			'$options[\'sort\']',
			'must be provided if any of "from", "until", "startFrom" options is provided'
		);

		$db = $this->dbProvider->getReplicaDatabase();

		$queryBuilder = $db->newSelectQueryBuilder()
			->select( [ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp' ] )
			->from( 'watchlist' )
			->caller( __METHOD__ );
		$this->addQueryCondsForWatchedItemsForUser( $db, $user, $options, $queryBuilder );
		$this->addQueryDbOptionsForWatchedItemsForUser( $options, $queryBuilder );

		if ( $this->expiryEnabled ) {
			// If expiries are enabled, join with the watchlist_expiry table and exclude expired items.
			$queryBuilder->leftJoin( 'watchlist_expiry', null, 'wl_id = we_item' )
				->andWhere( $db->expr( 'we_expiry', '>', $db->timestamp() )->or( 'we_expiry', '=', null ) );
		}
		$res = $queryBuilder->fetchResultSet();

		$watchedItems = [];
		foreach ( $res as $row ) {
			$target = new TitleValue( (int)$row->wl_namespace, $row->wl_title );
			// todo these could all be cached at some point?
			$watchedItems[] = new WatchedItem(
				$user,
				$target,
				$this->watchedItemStore->getLatestNotificationTimestamp(
					$row->wl_notificationtimestamp, $user, $target
				),
				$row->we_expiry ?? null
			);
		}

		return $watchedItems;
	}

	private function getRecentChangeFieldsFromRow( \stdClass $row ): array {
		return array_filter(
			get_object_vars( $row ),
			static function ( $key ) {
				return str_starts_with( $key, 'rc_' );
			},
			ARRAY_FILTER_USE_KEY
		);
	}

	private function getWatchedItemsWithRCInfoQueryTables( array $options ): array {
		$tables = [ 'recentchanges', 'watchlist' ];

		if ( $this->expiryEnabled ) {
			$tables[] = 'watchlist_expiry';
		}

		if ( !$options['allRevisions'] ) {
			$tables[] = 'page';
		}
		if ( in_array( self::INCLUDE_COMMENT, $options['includeFields'] ) ) {
			$tables += $this->commentStore->getJoin( 'rc_comment' )['tables'];
		}
		if ( in_array( self::INCLUDE_USER, $options['includeFields'] ) ||
			in_array( self::INCLUDE_USER_ID, $options['includeFields'] ) ||
			in_array( self::FILTER_ANON, $options['filters'] ) ||
			in_array( self::FILTER_NOT_ANON, $options['filters'] ) ||
			array_key_exists( 'onlyByUser', $options ) || array_key_exists( 'notByUser', $options )
		) {
			$tables['watchlist_actor'] = 'actor';
		}
		return $tables;
	}

	private function getWatchedItemsWithRCInfoQueryFields( array $options ): array {
		$fields = [
			'rc_id',
			'rc_namespace',
			'rc_title',
			'rc_timestamp',
			'rc_type',
			'rc_source',
			'rc_deleted',
			'wl_notificationtimestamp'
		];

		if ( $this->expiryEnabled ) {
			$fields[] = 'we_expiry';
		}

		$rcIdFields = [
			'rc_cur_id',
			'rc_this_oldid',
			'rc_last_oldid',
		];
		if ( $options['usedInGenerator'] ) {
			if ( $options['allRevisions'] ) {
				$rcIdFields = [ 'rc_this_oldid' ];
			} else {
				$rcIdFields = [ 'rc_cur_id' ];
			}
		}
		$fields = array_merge( $fields, $rcIdFields );

		if ( in_array( self::INCLUDE_FLAGS, $options['includeFields'] ) ) {
			$fields = array_merge( $fields, [ 'rc_type', 'rc_minor', 'rc_bot' ] );
		}
		if ( in_array( self::INCLUDE_USER, $options['includeFields'] ) ) {
			$fields['rc_user_text'] = 'watchlist_actor.actor_name';
		}
		if ( in_array( self::INCLUDE_USER_ID, $options['includeFields'] ) ) {
			$fields['rc_user'] = 'watchlist_actor.actor_user';
		}
		if ( in_array( self::INCLUDE_COMMENT, $options['includeFields'] ) ) {
			$fields += $this->commentStore->getJoin( 'rc_comment' )['fields'];
		}
		if ( in_array( self::INCLUDE_PATROL_INFO, $options['includeFields'] ) ) {
			$fields = array_merge( $fields, [ 'rc_patrolled', 'rc_log_type' ] );
		}
		if ( in_array( self::INCLUDE_SIZES, $options['includeFields'] ) ) {
			$fields = array_merge( $fields, [ 'rc_old_len', 'rc_new_len' ] );
		}
		if ( in_array( self::INCLUDE_LOG_INFO, $options['includeFields'] ) ) {
			$fields = array_merge( $fields, [ 'rc_logid', 'rc_log_type', 'rc_log_action', 'rc_params' ] );
		}
		if ( in_array( self::INCLUDE_TAGS, $options['includeFields'] ) ) {
			// prefixed with rc_ to include the field in getRecentChangeFieldsFromRow
			$fields['rc_tags'] = MediaWikiServices::getInstance()->getChangeTagsStore()
				->makeTagSummarySubquery( 'recentchanges' );
		}

		return $fields;
	}

	private function getWatchedItemsWithRCInfoQueryConds(
		IReadableDatabase $db,
		User $user,
		array $options
	): array {
		$watchlistOwnerId = $this->getWatchlistOwnerId( $user, $options );
		$conds = [ 'wl_user' => $watchlistOwnerId ];

		if ( $this->expiryEnabled ) {
			$conds[] = $db->expr( 'we_expiry', '=', null )->or( 'we_expiry', '>', $db->timestamp() );
		}

		if ( !$options['allRevisions'] ) {
			$conds[] = $db->makeList(
				[ 'rc_this_oldid=page_latest', 'rc_type=' . RC_LOG ],
				LIST_OR
			);
		}

		if ( $options['namespaceIds'] ) {
			$conds['wl_namespace'] = array_map( 'intval', $options['namespaceIds'] );
		}

		if ( array_key_exists( 'rcTypes', $options ) ) {
			$conds['rc_type'] = array_map( 'intval', $options['rcTypes'] );
		}

		$conds = array_merge(
			$conds,
			$this->getWatchedItemsWithRCInfoQueryFilterConds( $db, $user, $options )
		);

		$conds = array_merge( $conds, $this->getStartEndConds( $db, $options ) );

		if ( !isset( $options['start'] ) && !isset( $options['end'] ) && $db->getType() === 'mysql' ) {
			// This is an index optimization for mysql
			$conds[] = $db->expr( 'rc_timestamp', '>', '' );
		}

		$conds = array_merge( $conds, $this->getUserRelatedConds( $db, $user, $options ) );

		$deletedPageLogCond = $this->getExtraDeletedPageLogEntryRelatedCond( $db, $user );
		if ( $deletedPageLogCond ) {
			$conds[] = $deletedPageLogCond;
		}

		return $conds;
	}

	private function getWatchlistOwnerId( UserIdentity $user, array $options ): int {
		if ( array_key_exists( 'watchlistOwner', $options ) ) {
			/** @var UserIdentity $watchlistOwner */
			$watchlistOwner = $options['watchlistOwner'];
			$ownersToken =
				$this->userOptionsLookup->getOption( $watchlistOwner, 'watchlisttoken' );
			$token = $options['watchlistOwnerToken'];
			if ( $ownersToken == '' || !hash_equals( $ownersToken, $token ) ) {
				throw ApiUsageException::newWithMessage( null, 'apierror-bad-watchlist-token', 'bad_wltoken' );
			}
			return $watchlistOwner->getId();
		}
		return $user->getId();
	}

	private function getWatchedItemsWithRCInfoQueryFilterConds(
		IReadableDatabase $dbr,
		User $user,
		array $options
	): array {
		$conds = [];

		if ( in_array( self::FILTER_MINOR, $options['filters'] ) ) {
			$conds[] = 'rc_minor != 0';
		} elseif ( in_array( self::FILTER_NOT_MINOR, $options['filters'] ) ) {
			$conds[] = 'rc_minor = 0';
		}

		if ( in_array( self::FILTER_BOT, $options['filters'] ) ) {
			$conds[] = 'rc_bot != 0';
		} elseif ( in_array( self::FILTER_NOT_BOT, $options['filters'] ) ) {
			$conds[] = 'rc_bot = 0';
		}

		// Treat temporary users as 'anon', to match ChangesListSpecialPage
		if ( in_array( self::FILTER_ANON, $options['filters'] ) ) {
			if ( $this->tempUserConfig->isKnown() ) {
				$conds[] = $dbr->expr( 'watchlist_actor.actor_user', '=', null )
					->orExpr( $this->tempUserConfig->getMatchCondition( $dbr,
						'watchlist_actor.actor_name', IExpression::LIKE ) );
			} else {
				$conds[] = 'watchlist_actor.actor_user IS NULL';
			}
		} elseif ( in_array( self::FILTER_NOT_ANON, $options['filters'] ) ) {
			$conds[] = 'watchlist_actor.actor_user IS NOT NULL';
			if ( $this->tempUserConfig->isKnown() ) {
				$conds[] = $this->tempUserConfig->getMatchCondition( $dbr,
					'watchlist_actor.actor_name', IExpression::NOT_LIKE );
			}
		}

		if ( $user->useRCPatrol() || $user->useNPPatrol() ) {
			// TODO: not sure if this should simply ignore patrolled filters if user does not have the patrol
			// right, or maybe rather fail loud at this point, same as e.g. ApiQueryWatchlist does?
			if ( in_array( self::FILTER_PATROLLED, $options['filters'] ) ) {
				$conds[] = 'rc_patrolled != ' . RecentChange::PRC_UNPATROLLED;
			} elseif ( in_array( self::FILTER_NOT_PATROLLED, $options['filters'] ) ) {
				$conds['rc_patrolled'] = RecentChange::PRC_UNPATROLLED;
			}

			if ( in_array( self::FILTER_AUTOPATROLLED, $options['filters'] ) ) {
				$conds['rc_patrolled'] = RecentChange::PRC_AUTOPATROLLED;
			} elseif ( in_array( self::FILTER_NOT_AUTOPATROLLED, $options['filters'] ) ) {
				$conds[] = 'rc_patrolled != ' . RecentChange::PRC_AUTOPATROLLED;
			}
		}

		if ( in_array( self::FILTER_UNREAD, $options['filters'] ) ) {
			$conds[] = 'rc_timestamp >= wl_notificationtimestamp';
		} elseif ( in_array( self::FILTER_NOT_UNREAD, $options['filters'] ) ) {
			// TODO: should this be changed to use Database::makeList?
			$conds[] = 'wl_notificationtimestamp IS NULL OR rc_timestamp < wl_notificationtimestamp';
		}

		return $conds;
	}

	private function getStartEndConds( IReadableDatabase $db, array $options ): array {
		if ( !isset( $options['start'] ) && !isset( $options['end'] ) ) {
			return [];
		}

		$conds = [];
		if ( isset( $options['start'] ) ) {
			$after = $options['dir'] === self::DIR_OLDER ? '<=' : '>=';
			$conds[] = $db->expr( 'rc_timestamp', $after, $db->timestamp( $options['start'] ) );
		}
		if ( isset( $options['end'] ) ) {
			$before = $options['dir'] === self::DIR_OLDER ? '>=' : '<=';
			$conds[] = $db->expr( 'rc_timestamp', $before, $db->timestamp( $options['end'] ) );
		}

		return $conds;
	}

	private function getUserRelatedConds( IReadableDatabase $db, Authority $user, array $options ): array {
		if ( !array_key_exists( 'onlyByUser', $options ) && !array_key_exists( 'notByUser', $options ) ) {
			return [];
		}

		$conds = [];

		if ( array_key_exists( 'onlyByUser', $options ) ) {
			$conds['watchlist_actor.actor_name'] = $options['onlyByUser'];
		} elseif ( array_key_exists( 'notByUser', $options ) ) {
			$conds[] = $db->expr( 'watchlist_actor.actor_name', '!=', $options['notByUser'] );
		}

		// Avoid brute force searches (T19342)
		$bitmask = 0;
		if ( !$user->isAllowed( 'deletedhistory' ) ) {
			$bitmask = RevisionRecord::DELETED_USER;
		} elseif ( !$user->isAllowedAny( 'suppressrevision', 'viewsuppressed' ) ) {
			$bitmask = RevisionRecord::DELETED_USER | RevisionRecord::DELETED_RESTRICTED;
		}
		if ( $bitmask ) {
			$conds[] = $db->bitAnd( 'rc_deleted', $bitmask ) . " != $bitmask";
		}

		return $conds;
	}

	private function getExtraDeletedPageLogEntryRelatedCond( IReadableDatabase $db, Authority $user ): string {
		// LogPage::DELETED_ACTION hides the affected page, too. So hide those
		// entirely from the watchlist, or someone could guess the title.
		$bitmask = 0;
		if ( !$user->isAllowed( 'deletedhistory' ) ) {
			$bitmask = LogPage::DELETED_ACTION;
		} elseif ( !$user->isAllowedAny( 'suppressrevision', 'viewsuppressed' ) ) {
			$bitmask = LogPage::DELETED_ACTION | LogPage::DELETED_RESTRICTED;
		}
		if ( $bitmask ) {
			return $db->makeList( [
				'rc_type != ' . RC_LOG,
				$db->bitAnd( 'rc_deleted', $bitmask ) . " != $bitmask",
			], LIST_OR );
		}
		return '';
	}

	private function getStartFromConds( IReadableDatabase $db, array $options, array $startFrom ): string {
		$op = $options['dir'] === self::DIR_OLDER ? '<=' : '>=';
		[ $rcTimestamp, $rcId ] = $startFrom;
		$rcTimestamp = $db->timestamp( $rcTimestamp );
		$rcId = (int)$rcId;
		return $db->buildComparison( $op, [
			'rc_timestamp' => $rcTimestamp,
			'rc_id' => $rcId,
		] );
	}

	private function addQueryCondsForWatchedItemsForUser(
		IReadableDatabase $db, UserIdentity $user, array $options, SelectQueryBuilder $queryBuilder
	) {
		$queryBuilder->where( [ 'wl_user' => $user->getId() ] );
		if ( $options['namespaceIds'] ) {
			$queryBuilder->where( [ 'wl_namespace' => array_map( 'intval', $options['namespaceIds'] ) ] );
		}
		if ( isset( $options['filter'] ) ) {
			$filter = $options['filter'];
			if ( $filter === self::FILTER_CHANGED ) {
				$queryBuilder->where( 'wl_notificationtimestamp IS NOT NULL' );
			} else {
				$queryBuilder->where( 'wl_notificationtimestamp IS NULL' );
			}
		}

		if ( isset( $options['from'] ) ) {
			$op = $options['sort'] === self::SORT_ASC ? '>=' : '<=';
			$queryBuilder->where( $this->getFromUntilTargetConds( $db, $options['from'], $op ) );
		}
		if ( isset( $options['until'] ) ) {
			$op = $options['sort'] === self::SORT_ASC ? '<=' : '>=';
			$queryBuilder->where( $this->getFromUntilTargetConds( $db, $options['until'], $op ) );
		}
		if ( isset( $options['startFrom'] ) ) {
			$op = $options['sort'] === self::SORT_ASC ? '>=' : '<=';
			$queryBuilder->where( $this->getFromUntilTargetConds( $db, $options['startFrom'], $op ) );
		}
	}

	/**
	 * Creates a query condition part for getting only items before or after the given link target
	 * (while ordering using $sort mode)
	 *
	 * @param IReadableDatabase $db
	 * @param LinkTarget $target
	 * @param string $op comparison operator to use in the conditions
	 * @return string
	 */
	private function getFromUntilTargetConds( IReadableDatabase $db, LinkTarget $target, $op ) {
		return $db->buildComparison( $op, [
			'wl_namespace' => $target->getNamespace(),
			'wl_title' => $target->getDBkey(),
		] );
	}

	private function getWatchedItemsWithRCInfoQueryDbOptions( array $options ): array {
		$dbOptions = [];

		if ( array_key_exists( 'dir', $options ) ) {
			$sort = $options['dir'] === self::DIR_OLDER ? ' DESC' : '';
			$dbOptions['ORDER BY'] = [ 'rc_timestamp' . $sort, 'rc_id' . $sort ];
		}

		if ( array_key_exists( 'limit', $options ) ) {
			$dbOptions['LIMIT'] = (int)$options['limit'] + 1;
		}
		if ( $this->maxQueryExecutionTime ) {
			$dbOptions['MAX_EXECUTION_TIME'] = $this->maxQueryExecutionTime;
		}
		return $dbOptions;
	}

	private function addQueryDbOptionsForWatchedItemsForUser( array $options, SelectQueryBuilder $queryBuilder ) {
		if ( array_key_exists( 'sort', $options ) ) {
			if ( count( $options['namespaceIds'] ) !== 1 ) {
				$queryBuilder->orderBy( 'wl_namespace', $options['sort'] );
			}
			$queryBuilder->orderBy( 'wl_title', $options['sort'] );
		}
		if ( array_key_exists( 'limit', $options ) ) {
			$queryBuilder->limit( (int)$options['limit'] );
		}
		if ( $this->maxQueryExecutionTime ) {
			$queryBuilder->setMaxExecutionTime( $this->maxQueryExecutionTime );
		}
	}

	private function getWatchedItemsWithRCInfoQueryJoinConds( array $options ): array {
		$joinConds = [
			'watchlist' => [ 'JOIN',
				[
					'wl_namespace=rc_namespace',
					'wl_title=rc_title'
				]
			]
		];

		if ( $this->expiryEnabled ) {
			$joinConds['watchlist_expiry'] = [ 'LEFT JOIN', 'wl_id = we_item' ];
		}

		if ( !$options['allRevisions'] ) {
			$joinConds['page'] = [ 'LEFT JOIN', 'rc_cur_id=page_id' ];
		}
		if ( in_array( self::INCLUDE_COMMENT, $options['includeFields'] ) ) {
			$joinConds += $this->commentStore->getJoin( 'rc_comment' )['joins'];
		}
		if ( in_array( self::INCLUDE_USER, $options['includeFields'] ) ||
			in_array( self::INCLUDE_USER_ID, $options['includeFields'] ) ||
			in_array( self::FILTER_ANON, $options['filters'] ) ||
			in_array( self::FILTER_NOT_ANON, $options['filters'] ) ||
			array_key_exists( 'onlyByUser', $options ) || array_key_exists( 'notByUser', $options )
		) {
			$joinConds['watchlist_actor'] = [ 'JOIN', 'actor_id=rc_actor' ];
		}
		return $joinConds;
	}

}
/** @deprecated class alias since 1.43 */
class_alias( WatchedItemQueryService::class, 'WatchedItemQueryService' );
