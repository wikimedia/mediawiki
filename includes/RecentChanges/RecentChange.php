<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\RecentChanges;

use InvalidArgumentException;
use MediaWiki\ChangeTags\Taggable;
use MediaWiki\Debug\DeprecationHelper;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Storage\EditResult;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use RuntimeException;
use Wikimedia\AtEase\AtEase;

/**
 * @defgroup RecentChanges Recent changes
 * Discovery and review of recent edits and log events on the wiki.
 *
 * The Recent changes feature stores a temporary copy of the long-term
 * `revision` and `logging` table rows which represent page edits and
 * log actions respectively.
 *
 * Recent changes augments revision and logging rows with additional metadata
 * that empower reviewers to efficiently find edits related to their
 * interest, or edits that warrant a closer look. This includes page
 * namespace, "minor" edit status, and user type. As well as metadata we
 * don't store elsewhere, such the bot flag (rc_bot), edit type (page creation,
 * edit, or something else), and patrolling state (rc_patrolled).
 *
 * The patrolled status facilitates edit review via the "mark as patrolled"
 * button, in combination with filtering by patrol status via SpecialRecentChanges,
 * SpecialWatchlist, and ApiQueryRecentChanges.
 */

/**
 * Utility class for creating and reading rows in the recentchanges table.
 *
 * mAttribs:
 *  rc_id           id of the row in the recentchanges table
 *  rc_timestamp    time the entry was made
 *  rc_namespace    namespace #
 *  rc_title        non-prefixed db key
 *  rc_type         obsolete, use rc_source
 *  rc_source       string representation of change source
 *  rc_minor        is minor
 *  rc_cur_id       page_id of associated page entry
 *  rc_user         user id who made the entry
 *  rc_user_text    user name who made the entry
 *  rc_comment      edit summary
 *  rc_this_oldid   rev_id associated with this entry (or zero)
 *  rc_last_oldid   rev_id associated with the entry before this one (or zero)
 *  rc_bot          is bot, hidden
 *  rc_ip           IP address of the user in dotted quad notation
 *  rc_patrolled    boolean whether or not someone has marked this edit as patrolled
 *  rc_old_len      integer byte length of the text before the edit
 *  rc_new_len      the same after the edit
 *  rc_deleted      partial deletion
 *  rc_logid        the log_id value for this log entry (or zero)
 *  rc_log_type     the log type (or null)
 *  rc_log_action   the log action (or null)
 *  rc_params       log params
 *
 * mExtra:
 *  prefixedDBkey   prefixed db key, used by external app via msg queue
 *  oldSize         text size before the change
 *  newSize         text size after the change
 *  pageStatus      status of the page: created, deleted, moved, restored, changed
 *
 * temporary:       not stored in the database
 *      notificationtimestamp
 *      numberofWatchingusers
 *      watchlistExpiry        for temporary watchlist items
 *
 * @todo Deprecate access to mAttribs (direct or via getAttributes). Right now
 *  we're having to include both rc_comment and rc_comment_text/rc_comment_data
 *  so random crap works right.
 *
 * @ingroup RecentChanges
 */
class RecentChange implements Taggable {
	use DeprecationHelper;

	// Constants for the rc_source field.  Extensions may also have
	// their own source constants.
	public const SRC_EDIT = 'mw.edit';
	public const SRC_NEW = 'mw.new';
	public const SRC_LOG = 'mw.log';
	public const SRC_CATEGORIZE = 'mw.categorize';

	/**
	 * Values of the `rc_source` attribute that may be added by MediaWiki core.
	 * It can be useful in database queries to exclude extension-added RC entries.
	 * If you have a RecentChange object, use the isInternal() method on it instead
	 * of this constant.
	 *
	 * @var array<string>
	 */
	public const INTERNAL_SOURCES = [
		self::SRC_EDIT,
		self::SRC_NEW,
		self::SRC_LOG,
		self::SRC_CATEGORIZE,
	];

	public const PRC_UNPATROLLED = PatrolManager::PRC_UNPATROLLED;
	public const PRC_PATROLLED = PatrolManager::PRC_PATROLLED;
	public const PRC_AUTOPATROLLED = PatrolManager::PRC_AUTOPATROLLED;

	public const SEND_NONE = RecentChangeStore::SEND_NONE;
	public const SEND_FEED = RecentChangeStore::SEND_FEED;

	/** Flag for RecentChange::getQueryInfo() */
	public const STRAIGHT_JOIN_ACTOR = 1;

	/** @var array */
	public $mAttribs = [];
	/** @var array */
	public $mExtra = [];

	/**
	 * @var PageReference|null
	 */
	private $mPage = null;

	/**
	 * @var UserIdentity|null
	 */
	private $mPerformer = null;

	/** @var int */
	public $numberofWatchingusers = 0;
	/** @var bool */
	public $notificationtimestamp;

	/**
	 * @var string|null The expiry time, if this is a temporary watchlist item.
	 */
	public $watchlistExpiry;

	/**
	 * @var int Line number of recent change. Default -1.
	 */
	public $counter = -1;

	/**
	 * @var array List of tags to apply
	 */
	private $tags = [];

	/**
	 * @var EditResult|null EditResult associated with the edit
	 */
	private $editResult = null;

	/**
	 * @var array<string,bool> Highlighted filters, for ChangesList. The key
	 *   is the group name and then a slash and then the filter name.
	 */
	private $highlights = [];

	private const CHANGE_TYPES = [
		'edit' => RC_EDIT,
		'new' => RC_NEW,
		'log' => RC_LOG,
		'external' => RC_EXTERNAL,
		'categorize' => RC_CATEGORIZE,
	];

	public function __construct(
		?PageReference $page = null,
		?UserIdentity $performer = null
	) {
		$this->deprecatePublicPropertyFallback(
			'mTitle',
			'1.37',
			function () {
				return Title::castFromPageReference( $this->mPage );
			},
			function ( ?Title $title ) {
				$this->mPage = $title;
			}
		);

		$this->mPage = $page;
		$this->mPerformer = $performer;
	}

	# Factory methods

	/**
	 * @deprecated since 1.45, use MediaWikiServices::getInstance()
	 *   ->getRecentChangeFactory()->newRecentChangeFromRow() instead.
	 *
	 * @param mixed $row
	 * @return RecentChange
	 */
	public static function newFromRow( $row ) {
		return MediaWikiServices::getInstance()
			->getRecentChangeFactory()
			->newRecentChangeFromRow( $row );
	}

	/**
	 * Parsing text to RC_* constants
	 * @since 1.24
	 * @param string|array $type Callers must make sure that the given types are valid RC types.
	 * @return int|array RC_TYPE
	 */
	public static function parseToRCType( $type ) {
		if ( is_array( $type ) ) {
			$retval = [];
			foreach ( $type as $t ) {
				$retval[] = self::parseToRCType( $t );
			}

			return $retval;
		}

		if ( !array_key_exists( $type, self::CHANGE_TYPES ) ) {
			throw new InvalidArgumentException( "Unknown type '$type'" );
		}
		return self::CHANGE_TYPES[$type];
	}

	/**
	 * Parsing RC_* constants to human-readable test
	 * @since 1.24
	 * @param int $rcType
	 * @return string
	 */
	public static function parseFromRCType( $rcType ) {
		return array_search( $rcType, self::CHANGE_TYPES, true ) ?: "$rcType";
	}

	/**
	 * Get an array of all change types
	 *
	 * @since 1.26
	 *
	 * @return array
	 */
	public static function getChangeTypes() {
		return array_keys( self::CHANGE_TYPES );
	}

	/**
	 * Obtain the recent change with a given rc_id value
	 *
	 * @deprecated since 1.45, use MediaWikiServices::getInstance()
	 *   ->getRecentChangeLookup()->getRecentChangeById() instead.
	 *
	 * @param int $rcid The rc_id value to retrieve
	 * @return RecentChange|null
	 */
	public static function newFromId( $rcid ) {
		return MediaWikiServices::getInstance()
			->getRecentChangeLookup()
			->getRecentChangeById( $rcid );
	}

	/**
	 * Find the first recent change matching some specific conditions
	 *
	 * @deprecated since 1.45, use MediaWikiServices::getInstance()
	 *   ->getRecentChangeLookup()->getRecentChangeByConds() instead.
	 *
	 * @param array $conds Array of conditions
	 * @param mixed $fname Override the method name in profiling/logs @phan-mandatory-param
	 * @param int $dbType DB_* constant
	 *
	 * @return RecentChange|null
	 */
	public static function newFromConds(
		$conds,
		$fname = __METHOD__,
		$dbType = DB_REPLICA
	) {
		return MediaWikiServices::getInstance()
			->getRecentChangeLookup()
			->getRecentChangeByConds( $conds, $fname, $dbType === DB_PRIMARY );
	}

	/**
	 * Return the tables, fields, and join conditions to be selected to create
	 * a new recentchanges object.
	 *
	 * Since 1.34, rc_user and rc_user_text have not been present in the
	 * database, but they continue to be available in query results as
	 * aliases.
	 *
	 * @since 1.31
	 * @param int $joinFlags May be STRAIGHT_JOIN_ACTOR to use a straight join
	 *   on the actor table, preventing the database from placing the actor
	 *   table first in the join. This is appropriate when there are no
	 *   restrictive conditions on the actor table, and the conditions are
	 *   potentially complex and unindexed, and the query is limited and
	 *   ordered by timestamp. Since 1.45.
	 *
	 * @return array[] With three keys:
	 *   - tables: (string[]) to include in the `$table` to `IDatabase->select()` or `SelectQueryBuilder::tables`
	 *   - fields: (string[]) to include in the `$vars` to `IDatabase->select()` or `SelectQueryBuilder::fields`
	 *   - joins: (array) to include in the `$join_conds` to `IDatabase->select()` or `SelectQueryBuilder::joinConds`
	 * @phan-return array{tables:string[],fields:string[],joins:array}
	 */
	public static function getQueryInfo( int $joinFlags = 0 ) {
		return [
			'tables' => [
				'recentchanges',
				'recentchanges_actor' => 'actor',
				'recentchanges_comment' => 'comment',
			],
			'fields' => [
				'rc_id',
				'rc_timestamp',
				'rc_namespace',
				'rc_title',
				'rc_minor',
				'rc_bot',
				'rc_cur_id',
				'rc_this_oldid',
				'rc_last_oldid',
				'rc_type',
				'rc_source',
				'rc_patrolled',
				'rc_ip',
				'rc_old_len',
				'rc_new_len',
				'rc_deleted',
				'rc_logid',
				'rc_log_type',
				'rc_log_action',
				'rc_params',
				'rc_actor',
				'rc_user' => 'recentchanges_actor.actor_user',
				'rc_user_text' => 'recentchanges_actor.actor_name',
				'rc_comment_text' => 'recentchanges_comment.comment_text',
				'rc_comment_data' => 'recentchanges_comment.comment_data',
				'rc_comment_id' => 'recentchanges_comment.comment_id',
			],
			'joins' => [
				// Optimizer sometimes refuses to pick up the correct join order (T311360)
				'recentchanges_actor' => [
					( $joinFlags & self::STRAIGHT_JOIN_ACTOR ) ? 'STRAIGHT_JOIN' : 'JOIN',
					'actor_id=rc_actor'
				],
				'recentchanges_comment' => [ 'STRAIGHT_JOIN', 'comment_id=rc_comment_id' ],
			],
		];
	}

	# Accessors

	/**
	 * @param array $attribs
	 */
	public function setAttribs( $attribs ) {
		$this->mAttribs = $attribs;
	}

	/**
	 * @since 1.45
	 * @param string $name
	 * @param mixed $value
	 */
	public function setAttribute( $name, $value ) {
		$this->mAttribs[$name] = $value;
	}

	/**
	 * @param array $extra
	 */
	public function setExtra( $extra ) {
		$this->mExtra = $extra;
	}

	/**
	 * @deprecated since 1.37, use getPage() instead.
	 * @return Title
	 */
	public function getTitle() {
		$this->mPage = Title::castFromPageReference( $this->getPage() );
		return $this->mPage ?: Title::makeTitle( NS_SPECIAL, 'BadTitle' );
	}

	/**
	 * @since 1.37
	 * @return ?PageReference
	 */
	public function getPage(): ?PageReference {
		if ( !$this->mPage ) {
			// NOTE: As per the 1.36 release, we always provide rc_title,
			//       even in cases where it doesn't really make sense.
			//       In the future, rc_title may be nullable, or we may use
			//       empty strings in entries that do not refer to a page.
			if ( ( $this->mAttribs['rc_title'] ?? '' ) === '' ) {
				return null;
			}

			// XXX: We could use rc_cur_id to create a PageIdentityValue,
			//      at least if it's not a special page.
			//      However, RecentChangeFactory::createCategorizationRecentChange() puts the ID of the
			//      categorized page into rc_cur_id, but the title of the category page into rc_title.
			$this->mPage = PageReferenceValue::localReference(
				(int)$this->mAttribs['rc_namespace'],
				$this->mAttribs['rc_title']
			);
		}

		return $this->mPage;
	}

	/**
	 * Get the UserIdentity of the client that performed this change.
	 *
	 * @since 1.36
	 *
	 * @return UserIdentity
	 */
	public function getPerformerIdentity(): UserIdentity {
		if ( !$this->mPerformer ) {
			$this->mPerformer = $this->getUserIdentityFromAnyId(
				$this->mAttribs['rc_user'] ?? null,
				$this->mAttribs['rc_user_text'] ?? null,
				$this->mAttribs['rc_actor'] ?? null
			);
		}

		return $this->mPerformer;
	}

	/**
	 * Writes the data in this object to the database
	 *
	 * @deprecated since 1.45, use RecentChangeFactory::insertRecentChange() instead.
	 *
	 * @param bool $send self::SEND_FEED or self::SEND_NONE
	 */
	public function save( $send = self::SEND_FEED ) {
		MediaWikiServices::getInstance()->getRecentChangeFactory()->insertRecentChange( $this, $send );
	}

	/**
	 * Notify all the feeds about the change.
	 *
	 * @deprecated since 1.45, use RecentChangeRCFeedNotifier::notifyRCFeeds() instead.
	 *
	 * @param array|null $feeds Optional feeds to send to, defaults to $wgRCFeeds
	 */
	public function notifyRCFeeds( ?array $feeds = null ) {
		MediaWikiServices::getInstance()->getRecentChangeRCFeedNotifier()->notifyRCFeeds( $this, $feeds );
	}

	/**
	 * Mark this RecentChange as patrolled
	 *
	 * NOTE: Can also return 'rcpatroldisabled', 'hookaborted' and
	 * 'markedaspatrollederror-noautopatrol' as errors
	 *
	 * @deprecated since 1.45, use PatrolManager::markPatrolled() instead.
	 *
	 * @param Authority $performer User performing the action
	 * @param string|string[]|null $tags Change tags to add to the patrol log entry
	 *   ($user should be able to add the specified tags before this is called)
	 * @return PermissionStatus
	 */
	public function markPatrolled( Authority $performer, $tags = null ): PermissionStatus {
		return MediaWikiServices::getInstance()
			->getPatrolManager()
			->markPatrolled( $this, $performer, $tags );
	}

	/**
	 * Mark this RecentChange patrolled, without error checking
	 *
	 * @deprecated since 1.45, use PatrolManager::reallyMarkPatrolled() instead.
	 *
	 * @return int Number of database rows changed, usually 1, but 0 if
	 * another request already patrolled it in the mean time.
	 */
	public function reallyMarkPatrolled() {
		return MediaWikiServices::getInstance()
			->getPatrolManager()
			->reallyMarkPatrolled( $this );
	}

	/**
	 * Makes an entry in the database corresponding to an edit
	 *
	 * @since 1.36 Added $editResult parameter
	 * @deprecated since 1.45, use RecentChangeFactory::createEditRecentChange() instead to create
	 * the log entry, then use RecentChangeFactory::insertRecentChange() to insert it into the database.
	 *
	 * @param string $timestamp
	 * @param PageIdentity $page
	 * @param bool $minor
	 * @param UserIdentity $user
	 * @param string $comment
	 * @param int $oldId
	 * @param string $lastTimestamp
	 * @param bool $bot
	 * @param string $ip
	 * @param int $oldSize
	 * @param int $newSize
	 * @param int $newId
	 * @param int $patrol
	 * @param string[] $tags
	 * @param EditResult|null $editResult EditResult associated with this edit. Can be safely
	 *  skipped if the edit is not a revert. Used only for marking revert tags.
	 *
	 * @return RecentChange
	 */
	public static function notifyEdit(
		$timestamp, $page, $minor, $user, $comment, $oldId, $lastTimestamp,
		$bot, $ip = '', $oldSize = 0, $newSize = 0, $newId = 0, $patrol = 0,
		$tags = [], ?EditResult $editResult = null
	) {
		$rc = MediaWikiServices::getInstance()->getRecentChangeFactory()
			->createEditRecentChange(
				$timestamp, $page, $minor, $user, $comment, $oldId,
				$bot, $ip, $oldSize, $newSize, $newId, $patrol, $tags, $editResult
			);
		$rc->save();
		return $rc;
	}

	/**
	 * Makes an entry in the database corresponding to page creation
	 * @note $page must reflect the state of the database after the page creation. In particular,
	 *       $page->getId() must return the newly assigned page ID.
	 *
	 * @deprecated since 1.45, use RecentChangeFactory::createNewPageRecentChange() instead to create
	 * the log entry, then use RecentChangeFactory::insertRecentChange() to insert it into the database.
	 *
	 * @param string $timestamp
	 * @param PageIdentity $page
	 * @param bool $minor
	 * @param UserIdentity $user
	 * @param string $comment
	 * @param bool $bot
	 * @param string $ip
	 * @param int $size
	 * @param int $newId
	 * @param int $patrol
	 * @param string[] $tags
	 *
	 * @return RecentChange
	 */
	public static function notifyNew(
		$timestamp,
		$page, $minor, $user, $comment, $bot,
		$ip = '', $size = 0, $newId = 0, $patrol = 0, $tags = []
	) {
		$rc = MediaWikiServices::getInstance()->getRecentChangeFactory()
			->createNewPageRecentChange(
				$timestamp, $page, $minor, $user, $comment, $bot,
				$ip, $size, $newId, $patrol, $tags
			);
		$rc->save();
		return $rc;
	}

	/**
	 * @deprecated since 1.45, use RecentChangeFactory::createLogRecentChange() instead
	 *
	 * @param string $timestamp
	 * @param PageReference $logPage
	 * @param UserIdentity $user
	 * @param string $actionComment
	 * @param string $ip
	 * @param string $type
	 * @param string $action
	 * @param PageReference $target
	 * @param string $logComment
	 * @param string $params
	 * @param int $newId
	 * @param string $actionCommentIRC
	 * @param int $revId Id of associated revision, if any
	 * @param bool $isPatrollable Whether this log entry is patrollable
	 * @param bool|null $forceBotFlag Override the default behavior and set bot flag to
	 * 	the value of the argument. When omitted or null, it falls back to the global state.
	 *
	 * @return RecentChange
	 */
	public static function newLogEntry( $timestamp,
		$logPage, $user, $actionComment, $ip,
		$type, $action, $target, $logComment, $params, $newId = 0, $actionCommentIRC = '',
		$revId = 0, $isPatrollable = false, $forceBotFlag = null
	) {
		return MediaWikiServices::getInstance()->getRecentChangeFactory()
			->createLogRecentChange(
				$timestamp, $logPage, $user, $actionComment, $ip,
				$type, $action, $target, $logComment, $params, $newId, $actionCommentIRC,
				$revId, $isPatrollable, $forceBotFlag
			);
	}

	/**
	 * Get a parameter value
	 *
	 * @since 1.27
	 *
	 * @param string $name parameter name
	 * @return mixed
	 */
	public function getParam( $name ) {
		$params = $this->parseParams();
		return $params[$name] ?? null;
	}

	/**
	 * Initialises the members of this object from a mysql row object
	 *
	 * @param mixed $row
	 */
	public function loadFromRow( $row ) {
		$this->mAttribs = get_object_vars( $row );
		$this->mAttribs['rc_timestamp'] = wfTimestamp( TS_MW, $this->mAttribs['rc_timestamp'] );
		// rc_deleted MUST be set
		$this->mAttribs['rc_deleted'] = $row->rc_deleted;

		$dbr = MediaWikiServices::getInstance()->getConnectionProvider()->getReplicaDatabase();
		$comment = MediaWikiServices::getInstance()->getCommentStore()
			// Legacy because $row may have come from self::selectFields()
			->getCommentLegacy( $dbr, 'rc_comment', $row, true )
			->text;
		$this->mAttribs['rc_comment'] = &$comment;
		$this->mAttribs['rc_comment_text'] = &$comment;
		$this->mAttribs['rc_comment_data'] = null;

		$this->mPerformer = $this->getUserIdentityFromAnyId(
			$row->rc_user ?? null,
			$row->rc_user_text ?? null,
			$row->rc_actor ?? null
		);
		$this->mAttribs['rc_user'] = $this->mPerformer->getId();
		$this->mAttribs['rc_user_text'] = $this->mPerformer->getName();

		// Watchlist expiry.
		if ( isset( $row->we_expiry ) && $row->we_expiry ) {
			$this->watchlistExpiry = wfTimestamp( TS_MW, $row->we_expiry );
		}
	}

	/**
	 * Check if the change is internal (edit, new, log, categorize). External changes are
	 * those that are defined by external sources, such as extensions.
	 *
	 * @since 1.45
	 * @return bool
	 */
	public function isInternal() {
		return in_array( $this->getAttribute( 'rc_source' ), self::INTERNAL_SOURCES );
	}

	/**
	 * Get an attribute value
	 *
	 * @param string $name Attribute name
	 * @return mixed
	 */
	public function getAttribute( $name ) {
		if ( $name === 'rc_comment' ) {
			return MediaWikiServices::getInstance()->getCommentStore()
				->getComment( 'rc_comment', $this->mAttribs, true )->text;
		}

		if ( $name === 'rc_user' || $name === 'rc_user_text' || $name === 'rc_actor' ) {
			$user = $this->getPerformerIdentity();

			if ( $name === 'rc_user' ) {
				return $user->getId();
			}
			if ( $name === 'rc_user_text' ) {
				return $user->getName();
			}
			if ( $name === 'rc_actor' ) {
				// NOTE: rc_actor exists in the database, but application logic should not use it.
				wfDeprecatedMsg( 'Accessing deprecated field rc_actor', '1.36' );
				$actorStore = MediaWikiServices::getInstance()->getActorStore();
				$db = MediaWikiServices::getInstance()->getConnectionProvider()->getReplicaDatabase();
				return $actorStore->findActorId( $user, $db );
			}
		}

		return $this->mAttribs[$name] ?? null;
	}

	/**
	 * @return array
	 */
	public function getAttributes() {
		return $this->mAttribs;
	}

	/**
	 * @internal
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function getExtra( $name ) {
		return $this->mExtra[$name] ?? null;
	}

	/**
	 * @internal
	 *
	 * @return array
	 */
	public function getExtras() {
		return $this->mExtra;
	}

	/**
	 * Gets the end part of the diff URL associated with this object
	 * Blank if no diff link should be displayed
	 * @param bool $forceCur
	 * @return string
	 */
	public function diffLinkTrail( $forceCur ) {
		if ( $this->mAttribs['rc_source'] == self::SRC_EDIT ) {
			$trail = "curid=" . (int)( $this->mAttribs['rc_cur_id'] ) .
				"&oldid=" . (int)( $this->mAttribs['rc_last_oldid'] );
			if ( $forceCur ) {
				$trail .= '&diff=0';
			} else {
				$trail .= '&diff=' . (int)( $this->mAttribs['rc_this_oldid'] );
			}
		} else {
			$trail = '';
		}

		return $trail;
	}

	/**
	 * Returns the change size (HTML).
	 * The lengths can be given optionally.
	 * @param int $old
	 * @param int $new
	 * @return string
	 */
	public function getCharacterDifference( $old = 0, $new = 0 ) {
		if ( $old === 0 ) {
			$old = $this->mAttribs['rc_old_len'];
		}
		if ( $new === 0 ) {
			$new = $this->mAttribs['rc_new_len'];
		}
		if ( $old === null || $new === null ) {
			return '';
		}

		return ChangesList::showCharacterDifference( $old, $new );
	}

	/**
	 * Check whether the given timestamp is new enough to have a RC row with a given tolerance
	 * as the recentchanges table might not be cleared out regularly (so older entries might exist)
	 * or rows which will be deleted soon shouldn't be included.
	 *
	 * @param mixed $timestamp MWTimestamp compatible timestamp
	 * @param int $tolerance Tolerance in seconds
	 * @return bool
	 */
	public static function isInRCLifespan( $timestamp, $tolerance = 0 ) {
		$rcMaxAge =
			MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::RCMaxAge );

		return (int)wfTimestamp( TS_UNIX, $timestamp ) > time() - $tolerance - $rcMaxAge;
	}

	/**
	 * Parses and returns the rc_params attribute
	 *
	 * @since 1.26
	 * @return mixed|bool false on failed unserialization
	 */
	public function parseParams() {
		$rcParams = $this->getAttribute( 'rc_params' );

		AtEase::suppressWarnings();
		$unserializedParams = unserialize( $rcParams );
		AtEase::restoreWarnings();

		return $unserializedParams;
	}

	/**
	 * @internal
	 *
	 * @return string[]
	 */
	public function getTags(): array {
		return $this->tags;
	}

	/**
	 * Tags to append to the recent change,
	 * and associated revision/log
	 *
	 * @since 1.28
	 *
	 * @param string|string[] $tags
	 */
	public function addTags( $tags ) {
		if ( is_string( $tags ) ) {
			$this->tags[] = $tags;
		} else {
			$this->tags = array_merge( $tags, $this->tags );
		}
	}

	/**
	 * @internal
	 *
	 * @return EditResult|null
	 */
	public function getEditResult(): ?EditResult {
		return $this->editResult;
	}

	/**
	 * Sets the EditResult associated with the edit.
	 *
	 * @since 1.36
	 *
	 * @param EditResult|null $editResult
	 */
	public function setEditResult( ?EditResult $editResult ) {
		$this->editResult = $editResult;
	}

	/**
	 * @param string|int|null $userId
	 * @param string|null $userName
	 * @param string|int|null $actorId
	 *
	 * @return UserIdentity
	 */
	private function getUserIdentityFromAnyId(
		$userId,
		$userName,
		$actorId = null
	): UserIdentity {
		// XXX: Is this logic needed elsewhere? Should it be reusable?

		$userId = $userId !== null ? (int)$userId : null;
		$actorId = $actorId !== null ? (int)$actorId : 0;

		$actorStore = MediaWikiServices::getInstance()->getActorStore();
		if ( $userName && $actorId ) {
			// Likely the fields are coming from a join on actor table,
			// so can definitely build a UserIdentityValue.
			return $actorStore->newActorFromRowFields( $userId, $userName, $actorId );
		}
		if ( $userId !== null ) {
			if ( $userName !== null ) {
				// NOTE: For IPs and external users, $userId will be 0.
				$user = new UserIdentityValue( $userId, $userName );
			} else {
				$user = $actorStore->getUserIdentityByUserId( $userId );

				if ( !$user ) {
					throw new RuntimeException( "User not found by ID: $userId" );
				}
			}
		} elseif ( $actorId > 0 ) {
			$db = MediaWikiServices::getInstance()->getConnectionProvider()->getReplicaDatabase();
			$user = $actorStore->getActorById( $actorId, $db );

			if ( !$user ) {
				throw new RuntimeException( "User not found by actor ID: $actorId" );
			}
		} elseif ( $userName !== null ) {
			$user = $actorStore->getUserIdentityByName( $userName );

			if ( !$user ) {
				throw new RuntimeException( "User not found by name: $userName" );
			}
		} else {
			throw new RuntimeException( 'At least one of user ID, actor ID or user name must be given' );
		}

		return $user;
	}

	/**
	 * @param array<string,bool> $highlights
	 */
	public function setHighlights( array $highlights ) {
		$this->highlights = $highlights;
	}

	/**
	 * @return array<string,bool>
	 */
	public function getHighlights() {
		return $this->highlights;
	}

	public function isHighlighted( string $key ): bool {
		return $this->highlights[$key] ?? false;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( RecentChange::class, 'RecentChange' );
