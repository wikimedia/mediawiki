<?php

use Wikimedia\Rdbms\IDatabase;
use MediaWiki\Linker\LinkTarget;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * Class performing complex database queries related to WatchedItems.
 *
 * @since 1.28
 *
 * @file
 * @ingroup Watchlist
 *
 * @license GNU GPL v2+
 */
class WatchedItemQueryService {

	const DIR_OLDER = 'older';
	const DIR_NEWER = 'newer';

	const INCLUDE_FLAGS = 'flags';
	const INCLUDE_USER = 'user';
	const INCLUDE_USER_ID = 'userid';
	const INCLUDE_COMMENT = 'comment';
	const INCLUDE_PATROL_INFO = 'patrol';
	const INCLUDE_SIZES = 'sizes';
	const INCLUDE_LOG_INFO = 'loginfo';

	// FILTER_* constants are part of public API (are used in ApiQueryWatchlist and
	// ApiQueryWatchlistRaw classes) and should not be changed.
	// Changing values of those constants will result in a breaking change in the API
	const FILTER_MINOR = 'minor';
	const FILTER_NOT_MINOR = '!minor';
	const FILTER_BOT = 'bot';
	const FILTER_NOT_BOT = '!bot';
	const FILTER_ANON = 'anon';
	const FILTER_NOT_ANON = '!anon';
	const FILTER_PATROLLED = 'patrolled';
	const FILTER_NOT_PATROLLED = '!patrolled';
	const FILTER_UNREAD = 'unread';
	const FILTER_NOT_UNREAD = '!unread';
	const FILTER_CHANGED = 'changed';
	const FILTER_NOT_CHANGED = '!changed';

	const SORT_ASC = 'ASC';
	const SORT_DESC = 'DESC';

	/**
	 * @var LoadBalancer
	 */
	private $loadBalancer;

	/** @var WatchedItemQueryServiceExtension[]|null */
	private $extensions = null;

	public function __construct( LoadBalancer $loadBalancer ) {
		$this->loadBalancer = $loadBalancer;
	}

	/**
	 * @return WatchedItemQueryServiceExtension[]
	 */
	private function getExtensions() {
		if ( $this->extensions === null ) {
			$this->extensions = [];
			Hooks::run( 'WatchedItemQueryServiceExtensions', [ &$this->extensions, $this ] );
		}
		return $this->extensions;
	}

	/**
	 * @return IDatabase
	 * @throws MWException
	 */
	private function getConnection() {
		return $this->loadBalancer->getConnectionRef( DB_REPLICA, [ 'watchlist' ] );
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
	 *        'notByUser'           => string do not incluide changes by a specified user
	 *        'dir'                 => string in which direction to enumerate, accepted values:
	 *                                 - DIR_OLDER list newest first
	 *                                 - DIR_NEWER list oldest first
	 *        'start'               => string (format accepted by wfTimestamp) requires 'dir' option,
	 *                                 timestamp to start enumerating from
	 *        'end'                 => string (format accepted by wfTimestamp) requires 'dir' option,
	 *                                 timestamp to end enumerating
	 *        'watchlistOwner'      => User user whose watchlist items should be listed if different
	 *                                 than the one specified with $user param,
	 *                                 requires 'watchlistOwnerToken' option
	 *        'watchlistOwnerToken' => string a watchlist token used to access another user's
	 *                                 watchlist, used with 'watchlistOwnerToken' option
	 *        'limit'               => int maximum numbers of items to return
	 *        'usedInGenerator'     => bool include only RecentChange id field required by the
	 *                                 generator ('rc_cur_id' or 'rc_this_oldid') if true, or all
	 *                                 id fields ('rc_cur_id', 'rc_this_oldid', 'rc_last_oldid')
	 *                                 if false (default)
	 * @param array|null &$startFrom Continuation value: [ string $rcTimestamp, int $rcId ]
	 * @return array of pairs ( WatchedItem $watchedItem, string[] $recentChangeInfo ),
	 *         where $recentChangeInfo contains the following keys:
	 *         - 'rc_id',
	 *         - 'rc_namespace',
	 *         - 'rc_title',
	 *         - 'rc_timestamp',
	 *         - 'rc_type',
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
			!isset( $options['start'] ) && !isset( $options['end'] ) && $startFrom === null
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
			!isset( $startFrom ) || ( is_array( $startFrom ) && count( $startFrom ) === 2 ),
			'$startFrom',
			'must be a two-element array'
		);
		if ( array_key_exists( 'watchlistOwner', $options ) ) {
			Assert::parameterType(
				User::class,
				$options['watchlistOwner'],
				'$options[\'watchlistOwner\']'
			);
			Assert::parameter(
				isset( $options['watchlistOwnerToken'] ),
				'$options[\'watchlistOwnerToken\']',
				'must be provided when providing watchlistOwner option'
			);
		}

		$tables = [ 'recentchanges', 'watchlist' ];
		if ( !$options['allRevisions'] ) {
			$tables[] = 'page';
		}

		$db = $this->getConnection();

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

		$res = $db->select(
			$tables,
			$fields,
			$conds,
			__METHOD__,
			$dbOptions,
			$joinConds
		);

		$limit = isset( $dbOptions['LIMIT'] ) ? $dbOptions['LIMIT'] : INF;
		$items = [];
		$startFrom = null;
		foreach ( $res as $row ) {
			if ( --$limit <= 0 ) {
				$startFrom = [ $row->rc_timestamp, $row->rc_id ];
				break;
			}

			$items[] = [
				new WatchedItem(
					$user,
					new TitleValue( (int)$row->rc_namespace, $row->rc_title ),
					$row->wl_notificationtimestamp
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
	 * @param User $user
	 * @param array $options Allowed keys:
	 *        'sort'         => string optional sorting by namespace ID and title
	 *                          one of the self::SORT_* constants
	 *        'namespaceIds' => int[] optional namespace IDs to filter by (defaults to all namespaces)
	 *        'limit'        => int maximum number of items to return
	 *        'filter'       => string optional filter, one of the self::FILTER_* contants
	 *        'from'         => LinkTarget requires 'sort' key, only return items starting from
	 *                          those related to the link target
	 *        'until'        => LinkTarget requires 'sort' key, only return items until
	 *                          those related to the link target
	 *        'startFrom'    => LinkTarget requires 'sort' key, only return items starting from
	 *                          those related to the link target, allows to skip some link targets
	 *                          specified using the form option
	 * @return WatchedItem[]
	 */
	public function getWatchedItemsForUser( User $user, array $options = [] ) {
		if ( $user->isAnon() ) {
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
			!isset( $options['from'] ) && !isset( $options['until'] ) && !isset( $options['startFrom'] )
			|| isset( $options['sort'] ),
			'$options[\'sort\']',
			'must be provided if any of "from", "until", "startFrom" options is provided'
		);

		$db = $this->getConnection();

		$conds = $this->getWatchedItemsForUserQueryConds( $db, $user, $options );
		$dbOptions = $this->getWatchedItemsForUserQueryDbOptions( $options );

		$res = $db->select(
			'watchlist',
			[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp' ],
			$conds,
			__METHOD__,
			$dbOptions
		);

		$watchedItems = [];
		foreach ( $res as $row ) {
			// todo these could all be cached at some point?
			$watchedItems[] = new WatchedItem(
				$user,
				new TitleValue( (int)$row->wl_namespace, $row->wl_title ),
				$row->wl_notificationtimestamp
			);
		}

		return $watchedItems;
	}

	private function getRecentChangeFieldsFromRow( stdClass $row ) {
		// This can be simplified to single array_filter call filtering by key value,
		// once we stop supporting PHP 5.5
		$allFields = get_object_vars( $row );
		$rcKeys = array_filter(
			array_keys( $allFields ),
			function( $key ) {
				return substr( $key, 0, 3 ) === 'rc_';
			}
		);
		return array_intersect_key( $allFields, array_flip( $rcKeys ) );
	}

	private function getWatchedItemsWithRCInfoQueryFields( array $options ) {
		$fields = [
			'rc_id',
			'rc_namespace',
			'rc_title',
			'rc_timestamp',
			'rc_type',
			'rc_deleted',
			'wl_notificationtimestamp'
		];

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
			$fields[] = 'rc_user_text';
		}
		if ( in_array( self::INCLUDE_USER_ID, $options['includeFields'] ) ) {
			$fields[] = 'rc_user';
		}
		if ( in_array( self::INCLUDE_COMMENT, $options['includeFields'] ) ) {
			$fields[] = 'rc_comment';
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

		return $fields;
	}

	private function getWatchedItemsWithRCInfoQueryConds(
		IDatabase $db,
		User $user,
		array $options
	) {
		$watchlistOwnerId = $this->getWatchlistOwnerId( $user, $options );
		$conds = [ 'wl_user' => $watchlistOwnerId ];

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
			$this->getWatchedItemsWithRCInfoQueryFilterConds( $user, $options )
		);

		$conds = array_merge( $conds, $this->getStartEndConds( $db, $options ) );

		if ( !isset( $options['start'] ) && !isset( $options['end'] ) ) {
			if ( $db->getType() === 'mysql' ) {
				// This is an index optimization for mysql
				$conds[] = 'rc_timestamp > ' . $db->addQuotes( '' );
			}
		}

		$conds = array_merge( $conds, $this->getUserRelatedConds( $db, $user, $options ) );

		$deletedPageLogCond = $this->getExtraDeletedPageLogEntryRelatedCond( $db, $user );
		if ( $deletedPageLogCond ) {
			$conds[] = $deletedPageLogCond;
		}

		return $conds;
	}

	private function getWatchlistOwnerId( User $user, array $options ) {
		if ( array_key_exists( 'watchlistOwner', $options ) ) {
			/** @var User $watchlistOwner */
			$watchlistOwner = $options['watchlistOwner'];
			$ownersToken = $watchlistOwner->getOption( 'watchlisttoken' );
			$token = $options['watchlistOwnerToken'];
			if ( $ownersToken == '' || !hash_equals( $ownersToken, $token ) ) {
				throw ApiUsageException::newWithMessage( null, 'apierror-bad-watchlist-token', 'bad_wltoken' );
			}
			return $watchlistOwner->getId();
		}
		return $user->getId();
	}

	private function getWatchedItemsWithRCInfoQueryFilterConds( User $user, array $options ) {
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

		if ( in_array( self::FILTER_ANON, $options['filters'] ) ) {
			$conds[] = 'rc_user = 0';
		} elseif ( in_array( self::FILTER_NOT_ANON, $options['filters'] ) ) {
			$conds[] = 'rc_user != 0';
		}

		if ( $user->useRCPatrol() || $user->useNPPatrol() ) {
			// TODO: not sure if this should simply ignore patrolled filters if user does not have the patrol
			// right, or maybe rather fail loud at this point, same as e.g. ApiQueryWatchlist does?
			if ( in_array( self::FILTER_PATROLLED, $options['filters'] ) ) {
				$conds[] = 'rc_patrolled != 0';
			} elseif ( in_array( self::FILTER_NOT_PATROLLED, $options['filters'] ) ) {
				$conds[] = 'rc_patrolled = 0';
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

	private function getStartEndConds( IDatabase $db, array $options ) {
		if ( !isset( $options['start'] ) && !isset( $options['end'] ) ) {
			return [];
		}

		$conds = [];

		if ( isset( $options['start'] ) ) {
			$after = $options['dir'] === self::DIR_OLDER ? '<=' : '>=';
			$conds[] = 'rc_timestamp ' . $after . ' ' .
				$db->addQuotes( $db->timestamp( $options['start'] ) );
		}
		if ( isset( $options['end'] ) ) {
			$before = $options['dir'] === self::DIR_OLDER ? '>=' : '<=';
			$conds[] = 'rc_timestamp ' . $before . ' ' .
				$db->addQuotes( $db->timestamp( $options['end'] ) );
		}

		return $conds;
	}

	private function getUserRelatedConds( IDatabase $db, User $user, array $options ) {
		if ( !array_key_exists( 'onlyByUser', $options ) && !array_key_exists( 'notByUser', $options ) ) {
			return [];
		}

		$conds = [];

		if ( array_key_exists( 'onlyByUser', $options ) ) {
			$conds['rc_user_text'] = $options['onlyByUser'];
		} elseif ( array_key_exists( 'notByUser', $options ) ) {
			$conds[] = 'rc_user_text != ' . $db->addQuotes( $options['notByUser'] );
		}

		// Avoid brute force searches (T19342)
		$bitmask = 0;
		if ( !$user->isAllowed( 'deletedhistory' ) ) {
			$bitmask = Revision::DELETED_USER;
		} elseif ( !$user->isAllowedAny( 'suppressrevision', 'viewsuppressed' ) ) {
			$bitmask = Revision::DELETED_USER | Revision::DELETED_RESTRICTED;
		}
		if ( $bitmask ) {
			$conds[] = $db->bitAnd( 'rc_deleted', $bitmask ) . " != $bitmask";
		}

		return $conds;
	}

	private function getExtraDeletedPageLogEntryRelatedCond( IDatabase $db, User $user ) {
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

	private function getStartFromConds( IDatabase $db, array $options, array $startFrom ) {
		$op = $options['dir'] === self::DIR_OLDER ? '<' : '>';
		list( $rcTimestamp, $rcId ) = $startFrom;
		$rcTimestamp = $db->addQuotes( $db->timestamp( $rcTimestamp ) );
		$rcId = (int)$rcId;
		return $db->makeList(
			[
				"rc_timestamp $op $rcTimestamp",
				$db->makeList(
					[
						"rc_timestamp = $rcTimestamp",
						"rc_id $op= $rcId"
					],
					LIST_AND
				)
			],
			LIST_OR
		);
	}

	private function getWatchedItemsForUserQueryConds( IDatabase $db, User $user, array $options ) {
		$conds = [ 'wl_user' => $user->getId() ];
		if ( $options['namespaceIds'] ) {
			$conds['wl_namespace'] = array_map( 'intval', $options['namespaceIds'] );
		}
		if ( isset( $options['filter'] ) ) {
			$filter = $options['filter'];
			if ( $filter === self::FILTER_CHANGED ) {
				$conds[] = 'wl_notificationtimestamp IS NOT NULL';
			} else {
				$conds[] = 'wl_notificationtimestamp IS NULL';
			}
		}

		if ( isset( $options['from'] ) ) {
			$op = $options['sort'] === self::SORT_ASC ? '>' : '<';
			$conds[] = $this->getFromUntilTargetConds( $db, $options['from'], $op );
		}
		if ( isset( $options['until'] ) ) {
			$op = $options['sort'] === self::SORT_ASC ? '<' : '>';
			$conds[] = $this->getFromUntilTargetConds( $db, $options['until'], $op );
		}
		if ( isset( $options['startFrom'] ) ) {
			$op = $options['sort'] === self::SORT_ASC ? '>' : '<';
			$conds[] = $this->getFromUntilTargetConds( $db, $options['startFrom'], $op );
		}

		return $conds;
	}

	/**
	 * Creates a query condition part for getting only items before or after the given link target
	 * (while ordering using $sort mode)
	 *
	 * @param IDatabase $db
	 * @param LinkTarget $target
	 * @param string $op comparison operator to use in the conditions
	 * @return string
	 */
	private function getFromUntilTargetConds( IDatabase $db, LinkTarget $target, $op ) {
		return $db->makeList(
			[
				"wl_namespace $op " . $target->getNamespace(),
				$db->makeList(
					[
						'wl_namespace = ' . $target->getNamespace(),
						"wl_title $op= " . $db->addQuotes( $target->getDBkey() )
					],
					LIST_AND
				)
			],
			LIST_OR
		);
	}

	private function getWatchedItemsWithRCInfoQueryDbOptions( array $options ) {
		$dbOptions = [];

		if ( array_key_exists( 'dir', $options ) ) {
			$sort = $options['dir'] === self::DIR_OLDER ? ' DESC' : '';
			$dbOptions['ORDER BY'] = [ 'rc_timestamp' . $sort, 'rc_id' . $sort ];
		}

		if ( array_key_exists( 'limit', $options ) ) {
			$dbOptions['LIMIT'] = (int)$options['limit'] + 1;
		}

		return $dbOptions;
	}

	private function getWatchedItemsForUserQueryDbOptions( array $options ) {
		$dbOptions = [];
		if ( array_key_exists( 'sort', $options ) ) {
			$dbOptions['ORDER BY'] = [
				"wl_namespace {$options['sort']}",
				"wl_title {$options['sort']}"
			];
			if ( count( $options['namespaceIds'] ) === 1 ) {
				$dbOptions['ORDER BY'] = "wl_title {$options['sort']}";
			}
		}
		if ( array_key_exists( 'limit', $options ) ) {
			$dbOptions['LIMIT'] = (int)$options['limit'];
		}
		return $dbOptions;
	}

	private function getWatchedItemsWithRCInfoQueryJoinConds( array $options ) {
		$joinConds = [
			'watchlist' => [ 'INNER JOIN',
				[
					'wl_namespace=rc_namespace',
					'wl_title=rc_title'
				]
			]
		];
		if ( !$options['allRevisions'] ) {
			$joinConds['page'] = [ 'LEFT JOIN', 'rc_cur_id=page_id' ];
		}
		return $joinConds;
	}

}
