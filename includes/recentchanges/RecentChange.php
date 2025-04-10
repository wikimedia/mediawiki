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

namespace MediaWiki\RecentChanges;

use EmailNotification;
use InvalidArgumentException;
use MediaWiki\ChangeTags\Taggable;
use MediaWiki\Config\Config;
use MediaWiki\Debug\DeprecationHelper;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Json\FormatJson;
use MediaWiki\Logging\PatrolLog;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\RCFeed\RCFeed;
use MediaWiki\Storage\EditResult;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\Utils\MWTimestamp;
use RuntimeException;
use Wikimedia\Assert\Assert;
use Wikimedia\AtEase\AtEase;
use Wikimedia\IPUtils;

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
 *  rc_type         is new entry, used to determine whether updating is necessary
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
 *  rc_new          obsolete, use rc_source=='mw.new'
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
 *  lastTimestamp   timestamp of previous entry, used in WHERE clause during update
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
	public const SRC_EXTERNAL = 'mw.external'; // obsolete
	public const SRC_CATEGORIZE = 'mw.categorize';

	public const PRC_UNPATROLLED = 0;
	public const PRC_PATROLLED = 1;
	public const PRC_AUTOPATROLLED = 2;

	/**
	 * @var bool For save() - save to the database only, without any events.
	 */
	public const SEND_NONE = true;

	/**
	 * @var bool For save() - do emit the change to RCFeeds (usually public).
	 */
	public const SEND_FEED = false;

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

	private const CHANGE_TYPES = [
		'edit' => RC_EDIT,
		'new' => RC_NEW,
		'log' => RC_LOG,
		'external' => RC_EXTERNAL,
		'categorize' => RC_CATEGORIZE,
	];

	# Factory methods

	/**
	 * @param mixed $row
	 * @return RecentChange
	 */
	public static function newFromRow( $row ) {
		$rc = new RecentChange;
		$rc->loadFromRow( $row );

		return $rc;
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
	 * @param int $rcid The rc_id value to retrieve
	 * @return RecentChange|null
	 */
	public static function newFromId( $rcid ) {
		return self::newFromConds( [ 'rc_id' => $rcid ], __METHOD__ );
	}

	/**
	 * Find the first recent change matching some specific conditions
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
		$icp = MediaWikiServices::getInstance()->getConnectionProvider();

		$db = ( $dbType === DB_REPLICA ) ? $icp->getReplicaDatabase() : $icp->getPrimaryDatabase();

		$rcQuery = self::getQueryInfo();
		$row = $db->newSelectQueryBuilder()
			->queryInfo( $rcQuery )
			->where( $conds )
			->caller( $fname )
			->fetchRow();
		if ( $row !== false ) {
			return self::newFromRow( $row );
		} else {
			return null;
		}
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
	 * @return array[] With three keys:
	 *   - tables: (string[]) to include in the `$table` to `IDatabase->select()` or `SelectQueryBuilder::tables`
	 *   - fields: (string[]) to include in the `$vars` to `IDatabase->select()` or `SelectQueryBuilder::fields`
	 *   - joins: (array) to include in the `$join_conds` to `IDatabase->select()` or `SelectQueryBuilder::joinConds`
	 * @phan-return array{tables:string[],fields:string[],joins:array}
	 */
	public static function getQueryInfo() {
		$commentQuery = MediaWikiServices::getInstance()->getCommentStore()->getJoin( 'rc_comment' );
		// Optimizer sometimes refuses to pick up the correct join order (T311360)
		$commentQuery['joins']['comment_rc_comment'][0] = 'STRAIGHT_JOIN';
		return [
			'tables' => [
				'recentchanges',
				'recentchanges_actor' => 'actor'
			] + $commentQuery['tables'],
			'fields' => [
				'rc_id',
				'rc_timestamp',
				'rc_namespace',
				'rc_title',
				'rc_minor',
				'rc_bot',
				'rc_new',
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
			] + $commentQuery['fields'],
			'joins' => [
				'recentchanges_actor' => [ 'STRAIGHT_JOIN', 'actor_id=rc_actor' ]
			] + $commentQuery['joins'],
		];
	}

	public function __construct() {
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
	}

	# Accessors

	/**
	 * @param array $attribs
	 */
	public function setAttribs( $attribs ) {
		$this->mAttribs = $attribs;
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
			//      However, newForCategorization() puts the ID of the categorized page into
			//      rc_cur_id, but the title of the category page into rc_title.
			$this->mPage = new PageReferenceValue(
				(int)$this->mAttribs['rc_namespace'],
				$this->mAttribs['rc_title'],
				PageReference::LOCAL
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
	 * For compatibility reasons, the SEND_ constants internally reference a value
	 * that may seem negated from their purpose (none=true, feed=false). This is
	 * because the parameter used to be called "$noudp", defaulting to false.
	 *
	 * @param bool $send self::SEND_FEED or self::SEND_NONE
	 */
	public function save( $send = self::SEND_FEED ) {
		$services = MediaWikiServices::getInstance();
		$mainConfig = $services->getMainConfig();
		$putIPinRC = $mainConfig->get( MainConfigNames::PutIPinRC );
		$dbw = $services->getConnectionProvider()->getPrimaryDatabase();
		if ( !is_array( $this->mExtra ) ) {
			$this->mExtra = [];
		}

		if ( !$putIPinRC ) {
			$this->mAttribs['rc_ip'] = '';
		}

		# Strict mode fixups (not-NULL fields)
		foreach ( [ 'minor', 'bot', 'new', 'patrolled', 'deleted' ] as $field ) {
			$this->mAttribs["rc_$field"] = (int)$this->mAttribs["rc_$field"];
		}
		# ...more fixups (NULL fields)
		foreach ( [ 'old_len', 'new_len' ] as $field ) {
			$this->mAttribs["rc_$field"] = isset( $this->mAttribs["rc_$field"] )
				? (int)$this->mAttribs["rc_$field"]
				: null;
		}

		$row = $this->mAttribs;

		# Trim spaces on user supplied text
		$row['rc_comment'] = trim( $row['rc_comment'] ?? '' );

		# Fixup database timestamps
		$row['rc_timestamp'] = $dbw->timestamp( $row['rc_timestamp'] );

		# # If we are using foreign keys, an entry of 0 for the page_id will fail, so use NULL
		if ( $row['rc_cur_id'] == 0 ) {
			unset( $row['rc_cur_id'] );
		}

		# Convert mAttribs['rc_comment'] for CommentStore
		$comment = $row['rc_comment'];
		unset( $row['rc_comment'], $row['rc_comment_text'], $row['rc_comment_data'] );
		$row += $services->getCommentStore()->insert( $dbw, 'rc_comment', $comment );

		# Normalize UserIdentity to actor ID
		$user = $this->getPerformerIdentity();
		if ( array_key_exists( 'forImport', $this->mExtra ) && $this->mExtra['forImport'] ) {
			$actorStore = $services->getActorStoreFactory()->getActorStoreForImport();
		} else {
			$actorStore = $services->getActorStoreFactory()->getActorStore();
		}
		$row['rc_actor'] = $actorStore->acquireActorId( $user, $dbw );
		unset( $row['rc_user'], $row['rc_user_text'] );

		# Don't reuse an existing rc_id for the new row, if one happens to be
		# set for some reason.
		unset( $row['rc_id'] );

		# Insert new row
		$dbw->newInsertQueryBuilder()
			->insertInto( 'recentchanges' )
			->row( $row )
			->caller( __METHOD__ )->execute();

		# Set the ID
		$this->mAttribs['rc_id'] = $dbw->insertId();

		# Notify extensions
		$hookRunner = new HookRunner( $services->getHookContainer() );
		$hookRunner->onRecentChange_save( $this );

		// Apply revert tags (if needed)
		if ( $this->editResult !== null && count( $this->editResult->getRevertTags() ) ) {
			MediaWikiServices::getInstance()->getChangeTagsStore()->addTags(
				$this->editResult->getRevertTags(),
				$this->mAttribs['rc_id'],
				$this->mAttribs['rc_this_oldid'],
				$this->mAttribs['rc_logid'],
				FormatJson::encode( $this->editResult ),
				$this
			);
		}

		if ( count( $this->tags ) ) {
			// $this->tags may contain revert tags we already applied above, they will
			// just be ignored.
			MediaWikiServices::getInstance()->getChangeTagsStore()->addTags(
				$this->tags,
				$this->mAttribs['rc_id'],
				$this->mAttribs['rc_this_oldid'],
				$this->mAttribs['rc_logid'],
				null,
				$this
			);
		}

		if ( $send === self::SEND_FEED ) {
			// Emit the change to external applications via RCFeeds.
			$this->notifyRCFeeds();
		}

		# E-mail notifications
		if ( self::isEnotifEnabled( $mainConfig ) ) {
			$userFactory = $services->getUserFactory();
			$editor = $userFactory->newFromUserIdentity( $this->getPerformerIdentity() );
			$title = Title::castFromPageReference( $this->getPage() );

			if ( $title && $hookRunner->onAbortEmailNotification( $editor, $title, $this ) ) {
				// @FIXME: This would be better as an extension hook
				// Send emails or email jobs once this row is safely committed
				$dbw->onTransactionCommitOrIdle(
					function () {
						$enotif = new EmailNotification();
						$enotif->notifyOnPageChange( $this );
					},
					__METHOD__
				);
			}
		}

		$jobs = [];
		// Flush old entries from the `recentchanges` table
		if ( mt_rand( 0, 9 ) == 0 ) {
			$jobs[] = RecentChangesUpdateJob::newPurgeJob();
		}
		// Update the cached list of active users
		if ( $this->mAttribs['rc_user'] > 0 ) {
			$jobs[] = RecentChangesUpdateJob::newCacheUpdateJob();
		}
		$services->getJobQueueGroup()->lazyPush( $jobs );
	}

	/**
	 * Notify all the feeds about the change.
	 * @param array|null $feeds Optional feeds to send to, defaults to $wgRCFeeds
	 */
	public function notifyRCFeeds( ?array $feeds = null ) {
		$feeds ??=
			MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::RCFeeds );

		$performer = $this->getPerformerIdentity();

		foreach ( $feeds as $params ) {
			$params += [
				'omit_bots' => false,
				'omit_anon' => false,
				'omit_user' => false,
				'omit_minor' => false,
				'omit_patrolled' => false,
			];

			if (
				( $params['omit_bots'] && $this->mAttribs['rc_bot'] ) ||
				( $params['omit_anon'] && !$performer->isRegistered() ) ||
				( $params['omit_user'] && $performer->isRegistered() ) ||
				( $params['omit_minor'] && $this->mAttribs['rc_minor'] ) ||
				( $params['omit_patrolled'] && $this->mAttribs['rc_patrolled'] ) ||
				$this->mAttribs['rc_type'] == RC_EXTERNAL
			) {
				continue;
			}

			$actionComment = $this->mExtra['actionCommentIRC'] ?? null;

			$feed = RCFeed::factory( $params );
			$feed->notify( $this, $actionComment );
		}
	}

	/**
	 * Mark this RecentChange as patrolled
	 *
	 * NOTE: Can also return 'rcpatroldisabled', 'hookaborted' and
	 * 'markedaspatrollederror-noautopatrol' as errors
	 *
	 * @deprecated since 1.43 Use markPatrolled() instead
	 *
	 * @param Authority $performer User performing the action
	 * @param bool|null $auto Unused. Passing true logs a warning.
	 * @param string|string[]|null $tags Change tags to add to the patrol log entry
	 *   ($user should be able to add the specified tags before this is called)
	 * @return array[] Array of permissions errors, see PermissionManager::getPermissionErrors()
	 */
	public function doMarkPatrolled( Authority $performer, $auto = null, $tags = null ) {
		wfDeprecated( __METHOD__, '1.43' );
		if ( $auto ) {
			wfWarn( __METHOD__ . ' with $auto = true' );
			return [];
		}
		return $this->markPatrolled( $performer, $tags )->toLegacyErrorArray();
	}

	/**
	 * Mark this RecentChange as patrolled
	 *
	 * NOTE: Can also return 'rcpatroldisabled', 'hookaborted' and
	 * 'markedaspatrollederror-noautopatrol' as errors
	 *
	 * @param Authority $performer User performing the action
	 * @param string|string[]|null $tags Change tags to add to the patrol log entry
	 *   ($user should be able to add the specified tags before this is called)
	 * @return PermissionStatus
	 */
	public function markPatrolled( Authority $performer, $tags = null ): PermissionStatus {
		$services = MediaWikiServices::getInstance();
		$mainConfig = $services->getMainConfig();
		$useRCPatrol = $mainConfig->get( MainConfigNames::UseRCPatrol );
		$useNPPatrol = $mainConfig->get( MainConfigNames::UseNPPatrol );
		$useFilePatrol = $mainConfig->get( MainConfigNames::UseFilePatrol );
		// Fix up $tags so that the MarkPatrolled hook below always gets an array
		if ( $tags === null ) {
			$tags = [];
		} elseif ( is_string( $tags ) ) {
			$tags = [ $tags ];
		}

		$status = PermissionStatus::newEmpty();
		// If recentchanges patrol is disabled, only new pages or new file versions
		// can be patrolled, provided the appropriate config variable is set
		if ( !$useRCPatrol && ( !$useNPPatrol || $this->getAttribute( 'rc_type' ) != RC_NEW ) &&
			( !$useFilePatrol || !( $this->getAttribute( 'rc_type' ) == RC_LOG &&
			$this->getAttribute( 'rc_log_type' ) == 'upload' ) ) ) {
			$status->fatal( 'rcpatroldisabled' );
		}
		$performer->authorizeWrite( 'patrol', $this->getTitle(), $status );
		$user = $services->getUserFactory()->newFromAuthority( $performer );
		$hookRunner = new HookRunner( $services->getHookContainer() );
		if ( !$hookRunner->onMarkPatrolled(
			$this->getAttribute( 'rc_id' ), $user, false, false, $tags )
		) {
			$status->fatal( 'hookaborted' );
		}
		// Users without the 'autopatrol' right can't patrol their own revisions
		if ( $performer->getUser()->getName() === $this->getAttribute( 'rc_user_text' ) &&
			!$performer->isAllowed( 'autopatrol' )
		) {
			$status->fatal( 'markedaspatrollederror-noautopatrol' );
		}
		if ( !$status->isGood() ) {
			return $status;
		}
		// If the change was patrolled already, do nothing
		if ( $this->getAttribute( 'rc_patrolled' ) ) {
			return $status;
		}
		// Attempt to set the 'patrolled' flag in RC database
		$affectedRowCount = $this->reallyMarkPatrolled();

		if ( $affectedRowCount === 0 ) {
			// Query succeeded but no rows change, e.g. another request
			// patrolled the same change just before us.
			// Avoid duplicate log entry (T196182).
			return $status;
		}

		// Log this patrol event
		PatrolLog::record( $this, false, $performer->getUser(), $tags );

		$hookRunner->onMarkPatrolledComplete(
			$this->getAttribute( 'rc_id' ), $user, false, false );

		return $status;
	}

	/**
	 * Mark this RecentChange patrolled, without error checking
	 *
	 * @return int Number of database rows changed, usually 1, but 0 if
	 * another request already patrolled it in the mean time.
	 */
	public function reallyMarkPatrolled() {
		$dbw = MediaWikiServices::getInstance()->getConnectionProvider()->getPrimaryDatabase();
		$dbw->newUpdateQueryBuilder()
			->update( 'recentchanges' )
			->set( [ 'rc_patrolled' => self::PRC_PATROLLED ] )
			->where( [
				'rc_id' => $this->getAttribute( 'rc_id' ),
				'rc_patrolled' => self::PRC_UNPATROLLED,
			] )
			->caller( __METHOD__ )->execute();
		$affectedRowCount = $dbw->affectedRows();
		// The change was patrolled already, do nothing
		if ( $affectedRowCount === 0 ) {
			return 0;
		}
		// Invalidate the page cache after the page has been patrolled
		// to make sure that the Patrol link isn't visible any longer!
		$this->getTitle()->invalidateCache();

		// Enqueue a reverted tag update (in case the edit was a revert)
		$revisionId = $this->getAttribute( 'rc_this_oldid' );
		if ( $revisionId ) {
			$revertedTagUpdateManager =
				MediaWikiServices::getInstance()->getRevertedTagUpdateManager();
			$revertedTagUpdateManager->approveRevertedTagForRevision( $revisionId );
		}

		return $affectedRowCount;
	}

	/**
	 * Makes an entry in the database corresponding to an edit
	 *
	 * @since 1.36 Added $editResult parameter
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
		Assert::parameter( $page->exists(), '$page', 'must represent an existing page' );

		$rc = new RecentChange;
		$rc->mPage = $page;
		$rc->mPerformer = $user;
		$rc->mAttribs = [
			'rc_timestamp' => $timestamp,
			'rc_namespace' => $page->getNamespace(),
			'rc_title' => $page->getDBkey(),
			'rc_type' => RC_EDIT,
			'rc_source' => self::SRC_EDIT,
			'rc_minor' => $minor ? 1 : 0,
			'rc_cur_id' => $page->getId(),
			'rc_user' => $user->getId(),
			'rc_user_text' => $user->getName(),
			'rc_comment' => &$comment,
			'rc_comment_text' => &$comment,
			'rc_comment_data' => null,
			'rc_this_oldid' => (int)$newId,
			'rc_last_oldid' => $oldId,
			'rc_bot' => $bot ? 1 : 0,
			'rc_ip' => self::checkIPAddress( $ip ),
			'rc_patrolled' => intval( $patrol ),
			'rc_new' => 0, # obsolete
			'rc_old_len' => $oldSize,
			'rc_new_len' => $newSize,
			'rc_deleted' => 0,
			'rc_logid' => 0,
			'rc_log_type' => null,
			'rc_log_action' => '',
			'rc_params' => ''
		];

		// TODO: deprecate the 'prefixedDBkey' entry, let callers do the formatting.
		$formatter = MediaWikiServices::getInstance()->getTitleFormatter();

		$rc->mExtra = [
			'prefixedDBkey' => $formatter->getPrefixedDBkey( $page ),
			'lastTimestamp' => $lastTimestamp,
			'oldSize' => $oldSize,
			'newSize' => $newSize,
			'pageStatus' => 'changed'
		];

		$rc->addTags( $tags );
		$rc->setEditResult( $editResult );
		$rc->save();

		return $rc;
	}

	/**
	 * Makes an entry in the database corresponding to page creation
	 * @note $page must reflect the state of the database after the page creation. In particular,
	 *       $page->getId() must return the newly assigned page ID.
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
		Assert::parameter( $page->exists(), '$page', 'must represent an existing page' );

		$rc = new RecentChange;
		$rc->mPage = $page;
		$rc->mPerformer = $user;
		$rc->mAttribs = [
			'rc_timestamp' => $timestamp,
			'rc_namespace' => $page->getNamespace(),
			'rc_title' => $page->getDBkey(),
			'rc_type' => RC_NEW,
			'rc_source' => self::SRC_NEW,
			'rc_minor' => $minor ? 1 : 0,
			'rc_cur_id' => $page->getId(),
			'rc_user' => $user->getId(),
			'rc_user_text' => $user->getName(),
			'rc_comment' => &$comment,
			'rc_comment_text' => &$comment,
			'rc_comment_data' => null,
			'rc_this_oldid' => (int)$newId,
			'rc_last_oldid' => 0,
			'rc_bot' => $bot ? 1 : 0,
			'rc_ip' => self::checkIPAddress( $ip ),
			'rc_patrolled' => intval( $patrol ),
			'rc_new' => 1, # obsolete
			'rc_old_len' => 0,
			'rc_new_len' => $size,
			'rc_deleted' => 0,
			'rc_logid' => 0,
			'rc_log_type' => null,
			'rc_log_action' => '',
			'rc_params' => ''
		];

		// TODO: deprecate the 'prefixedDBkey' entry, let callers do the formatting.
		$formatter = MediaWikiServices::getInstance()->getTitleFormatter();

		$rc->mExtra = [
			'prefixedDBkey' => $formatter->getPrefixedDBkey( $page ),
			'lastTimestamp' => 0,
			'oldSize' => 0,
			'newSize' => $size,
			'pageStatus' => 'created'
		];

		$rc->addTags( $tags );
		$rc->save();

		return $rc;
	}

	/**
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
	 *
	 * @return bool
	 */
	public static function notifyLog( $timestamp,
		$logPage, $user, $actionComment, $ip, $type,
		$action, $target, $logComment, $params, $newId = 0, $actionCommentIRC = ''
	) {
		$logRestrictions = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::LogRestrictions );

		# Don't add private logs to RC!
		if ( isset( $logRestrictions[$type] ) && $logRestrictions[$type] != '*' ) {
			return false;
		}
		$rc = self::newLogEntry( $timestamp,
			$logPage, $user, $actionComment, $ip, $type, $action,
			$target, $logComment, $params, $newId, $actionCommentIRC );
		$rc->save();

		return true;
	}

	/**
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
		global $wgRequest;

		$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();

		# # Get pageStatus for email notification
		switch ( $type . '-' . $action ) {
			case 'delete-delete':
			case 'delete-delete_redir':
			case 'delete-delete_redir2':
				$pageStatus = 'deleted';
				break;
			case 'move-move':
			case 'move-move_redir':
				$pageStatus = 'moved';
				break;
			case 'delete-restore':
				$pageStatus = 'restored';
				break;
			case 'upload-upload':
				$pageStatus = 'created';
				break;
			case 'upload-overwrite':
			default:
				$pageStatus = 'changed';
				break;
		}

		// Allow unpatrolled status for patrollable log entries
		$canAutopatrol = $permissionManager->userHasRight( $user, 'autopatrol' );
		$markPatrolled = $isPatrollable ? $canAutopatrol : true;

		if ( $target instanceof PageIdentity && $target->canExist() ) {
			$pageId = $target->getId();
		} else {
			$pageId = 0;
		}

		if ( $forceBotFlag !== null ) {
			$bot = (int)$forceBotFlag;
		} else {
			$bot = $permissionManager->userHasRight( $user, 'bot' ) ?
				(int)$wgRequest->getBool( 'bot', true ) : 0;
		}

		$rc = new RecentChange;
		$rc->mPage = $target;
		$rc->mPerformer = $user;
		$rc->mAttribs = [
			'rc_timestamp' => $timestamp,
			'rc_namespace' => $target->getNamespace(),
			'rc_title' => $target->getDBkey(),
			'rc_type' => RC_LOG,
			'rc_source' => self::SRC_LOG,
			'rc_minor' => 0,
			'rc_cur_id' => $pageId,
			'rc_user' => $user->getId(),
			'rc_user_text' => $user->getName(),
			'rc_comment' => &$logComment,
			'rc_comment_text' => &$logComment,
			'rc_comment_data' => null,
			'rc_this_oldid' => (int)$revId,
			'rc_last_oldid' => 0,
			'rc_bot' => $bot,
			'rc_ip' => self::checkIPAddress( $ip ),
			'rc_patrolled' => $markPatrolled ? self::PRC_AUTOPATROLLED : self::PRC_UNPATROLLED,
			'rc_new' => 0, # obsolete
			'rc_old_len' => null,
			'rc_new_len' => null,
			'rc_deleted' => 0,
			'rc_logid' => $newId,
			'rc_log_type' => $type,
			'rc_log_action' => $action,
			'rc_params' => $params
		];

		// TODO: deprecate the 'prefixedDBkey' entry, let callers do the formatting.
		$formatter = MediaWikiServices::getInstance()->getTitleFormatter();

		$rc->mExtra = [
			// XXX: This does not correspond to rc_namespace/rc_title/rc_cur_id.
			//      Is that intentional? For all other kinds of RC entries, prefixedDBkey
			//      matches rc_namespace/rc_title. Do we even need $logPage?
			'prefixedDBkey' => $formatter->getPrefixedDBkey( $logPage ),
			'lastTimestamp' => 0,
			'actionComment' => $actionComment, // the comment appended to the action, passed from LogPage
			'pageStatus' => $pageStatus,
			'actionCommentIRC' => $actionCommentIRC
		];

		return $rc;
	}

	/**
	 * Constructs a RecentChange object for the given categorization
	 * This does not call save() on the object and thus does not write to the db
	 *
	 * @since 1.27
	 *
	 * @param string $timestamp Timestamp of the recent change to occur
	 * @param PageIdentity $categoryTitle the category a page is being added to or removed from
	 * @param UserIdentity|null $user User object of the user that made the change
	 * @param string $comment Change summary
	 * @param PageIdentity $pageTitle the page that is being added or removed
	 * @param int $oldRevId Parent revision ID of this change
	 * @param int $newRevId Revision ID of this change
	 * @param string $lastTimestamp Parent revision timestamp of this change
	 * @param bool $bot true, if the change was made by a bot
	 * @param string $ip IP address of the user, if the change was made anonymously
	 * @param int $deleted Indicates whether the change has been deleted
	 * @param bool|null $added true, if the category was added, false for removed
	 * @param bool $forImport Whether the associated revision was imported
	 *
	 * @return RecentChange
	 */
	public static function newForCategorization(
		$timestamp,
		PageIdentity $categoryTitle,
		?UserIdentity $user,
		$comment,
		PageIdentity $pageTitle,
		$oldRevId,
		$newRevId,
		$lastTimestamp,
		$bot,
		$ip = '',
		$deleted = 0,
		$added = null,
		bool $forImport = false
	) {
		// Done in a backwards compatible way.
		$categoryWikiPage = MediaWikiServices::getInstance()->getWikiPageFactory()
			->newFromTitle( $categoryTitle );

		'@phan-var \MediaWiki\Page\WikiCategoryPage $categoryWikiPage';
		$params = [
			'hidden-cat' => $categoryWikiPage->isHidden()
		];
		if ( $added !== null ) {
			$params['added'] = $added;
		}

		if ( !$user ) {
			// XXX: when and why do we need this?
			$user = MediaWikiServices::getInstance()->getActorStore()->getUnknownActor();
		}

		$rc = new RecentChange;
		$rc->mPage = $categoryTitle;
		$rc->mPerformer = $user;
		$rc->mAttribs = [
			'rc_timestamp' => MWTimestamp::convert( TS_MW, $timestamp ),
			'rc_namespace' => $categoryTitle->getNamespace(),
			'rc_title' => $categoryTitle->getDBkey(),
			'rc_type' => RC_CATEGORIZE,
			'rc_source' => self::SRC_CATEGORIZE,
			'rc_minor' => 0,
			// XXX: rc_cur_id does not correspond to rc_namespace/rc_title.
			// It's because when the page (rc_cur_id) is deleted, we want
			// to delete the categorization entries, too (see LinksDeletionUpdate).
			'rc_cur_id' => $pageTitle->getId(),
			'rc_user' => $user->getId(),
			'rc_user_text' => $user->getName(),
			'rc_comment' => &$comment,
			'rc_comment_text' => &$comment,
			'rc_comment_data' => null,
			'rc_this_oldid' => (int)$newRevId,
			'rc_last_oldid' => $oldRevId,
			'rc_bot' => $bot ? 1 : 0,
			'rc_ip' => self::checkIPAddress( $ip ),
			'rc_patrolled' => self::PRC_AUTOPATROLLED, // Always patrolled, just like log entries
			'rc_new' => 0, # obsolete
			'rc_old_len' => null,
			'rc_new_len' => null,
			'rc_deleted' => $deleted,
			'rc_logid' => 0,
			'rc_log_type' => null,
			'rc_log_action' => '',
			'rc_params' => serialize( $params )
		];

		// TODO: deprecate the 'prefixedDBkey' entry, let callers do the formatting.
		$formatter = MediaWikiServices::getInstance()->getTitleFormatter();

		$rc->mExtra = [
			'prefixedDBkey' => $formatter->getPrefixedDBkey( $categoryTitle ),
			'lastTimestamp' => $lastTimestamp,
			'oldSize' => 0,
			'newSize' => 0,
			'pageStatus' => 'changed',
			'forImport' => $forImport,
		];

		return $rc;
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
	 * Gets the end part of the diff URL associated with this object
	 * Blank if no diff link should be displayed
	 * @param bool $forceCur
	 * @return string
	 */
	public function diffLinkTrail( $forceCur ) {
		if ( $this->mAttribs['rc_type'] == RC_EDIT ) {
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

	private static function checkIPAddress( string $ip ): string {
		global $wgRequest;

		if ( $ip ) {
			if ( !IPUtils::isIPAddress( $ip ) ) {
				throw new RuntimeException( "Attempt to write \"" . $ip .
					"\" as an IP address into recent changes" );
			}
		} else {
			$ip = $wgRequest->getIP();
			if ( !$ip ) {
				$ip = '';
			}
		}

		return $ip;
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
	 * Whether e-mail notifications are generally enabled on this wiki.
	 *
	 * This is used for:
	 *
	 * - performance optimization in RecentChange::save().
	 *   After an edit, whether or not we need to use the EmailNotification
	 *   service to determine which EnotifNotifyJob to dispatch.
	 *
	 * - performance optmization in WatchlistManager.
	 *   After using reset ("Mark all pages as seen") on Special:Watchlist,
	 *   whether to only look for user talk data to reset, or whether to look
	 *   at all possible pages for timestamps to reset.
	 *
	 * TODO: Determine whether these optimizations still make sense.
	 *
	 * FIXME: The $wgShowUpdatedMarker variable was added to this condtion
	 * in 2008 (2cf12c973d, SVN r35001) because at the time the per-user
	 * "last seen" marker for watchlist and page history, was managed by
	 * the EmailNotification/UserMailed classes. As of August 2022, this
	 * appears to no longer be the case.
	 *
	 * @since 1.40
	 * @param Config $conf
	 * @return bool
	 */
	public static function isEnotifEnabled( Config $conf ): bool {
		return $conf->get( MainConfigNames::EnotifUserTalk ) ||
			$conf->get( MainConfigNames::EnotifWatchlist ) ||
			$conf->get( MainConfigNames::ShowUpdatedMarker );
	}

	/**
	 * Get the extra URL that is given as part of the notification to RCFeed consumers.
	 *
	 * This is mainly to facilitate patrolling or other content review.
	 *
	 * @since 1.40
	 * @return string|null URL
	 */
	public function getNotifyUrl() {
		$services = MediaWikiServices::getInstance();
		$mainConfig = $services->getMainConfig();
		$useRCPatrol = $mainConfig->get( MainConfigNames::UseRCPatrol );
		$useNPPatrol = $mainConfig->get( MainConfigNames::UseNPPatrol );
		$canonicalServer = $mainConfig->get( MainConfigNames::CanonicalServer );
		$script = $mainConfig->get( MainConfigNames::Script );

		$type = $this->getAttribute( 'rc_type' );
		if ( $type == RC_LOG ) {
			$url = null;
		} else {
			$url = $canonicalServer . $script;
			if ( $type == RC_NEW ) {
				$query = '?oldid=' . $this->getAttribute( 'rc_this_oldid' );
			} else {
				$query = '?diff=' . $this->getAttribute( 'rc_this_oldid' )
					. '&oldid=' . $this->getAttribute( 'rc_last_oldid' );
			}
			if ( $useRCPatrol || ( $this->getAttribute( 'rc_type' ) == RC_NEW && $useNPPatrol ) ) {
				$query .= '&rcid=' . $this->getAttribute( 'rc_id' );
			}

			( new HookRunner( $services->getHookContainer() ) )->onIRCLineURL( $url, $query, $this );
			$url .= $query;
		}

		return $url;
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
}

/** @deprecated class alias since 1.44 */
class_alias( RecentChange::class, 'RecentChange' );
