<?php
/**
 * Utility class for creating and accessing recent change entries.
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

/**
 * Utility class for creating new RC entries
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
 *  rc_actor        actor id who made the entry
 *  rc_comment      edit summary
 *  rc_this_oldid   rev_id associated with this entry (or zero)
 *  rc_last_oldid   rev_id associated with the entry before this one (or zero)
 *  rc_bot          is bot, hidden
 *  rc_ip           IP address of the user in dotted quad notation
 *  rc_new          obsolete, use rc_type==RC_NEW
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
 *
 * @todo Deprecate access to mAttribs (direct or via getAttributes). Right now
 *  we're having to include both rc_comment and rc_comment_text/rc_comment_data
 *  so random crap works right.
 */
class RecentChange {
	// Constants for the rc_source field.  Extensions may also have
	// their own source constants.
	const SRC_EDIT = 'mw.edit';
	const SRC_NEW = 'mw.new';
	const SRC_LOG = 'mw.log';
	const SRC_EXTERNAL = 'mw.external'; // obsolete
	const SRC_CATEGORIZE = 'mw.categorize';

	const PRC_UNPATROLLED = 0;
	const PRC_PATROLLED = 1;
	const PRC_AUTOPATROLLED = 2;

	public $mAttribs = [];
	public $mExtra = [];

	/**
	 * @var Title
	 */
	public $mTitle = false;

	/**
	 * @var User
	 */
	private $mPerformer = false;

	public $numberofWatchingusers = 0; # Dummy to prevent error message in SpecialRecentChangesLinked
	public $notificationtimestamp;

	/**
	 * @var int Line number of recent change. Default -1.
	 */
	public $counter = -1;

	/**
	 * @var array List of tags to apply
	 */
	private $tags = [];

	/**
	 * @var array Array of change types
	 */
	private static $changeTypes = [
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
	 * @param string|array $type
	 * @throws MWException
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

		if ( !array_key_exists( $type, self::$changeTypes ) ) {
			throw new MWException( "Unknown type '$type'" );
		}
		return self::$changeTypes[$type];
	}

	/**
	 * Parsing RC_* constants to human-readable test
	 * @since 1.24
	 * @param int $rcType
	 * @return string $type
	 */
	public static function parseFromRCType( $rcType ) {
		return array_search( $rcType, self::$changeTypes, true ) ?: "$rcType";
	}

	/**
	 * Get an array of all change types
	 *
	 * @since 1.26
	 *
	 * @return array
	 */
	public static function getChangeTypes() {
		return array_keys( self::$changeTypes );
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
	 * @param mixed $fname Override the method name in profiling/logs
	 * @param int $dbType DB_* constant
	 *
	 * @return RecentChange|null
	 */
	public static function newFromConds(
		$conds,
		$fname = __METHOD__,
		$dbType = DB_REPLICA
	) {
		$db = wfGetDB( $dbType );
		$rcQuery = self::getQueryInfo();
		$row = $db->selectRow(
			$rcQuery['tables'], $rcQuery['fields'], $conds, $fname, [], $rcQuery['joins']
		);
		if ( $row !== false ) {
			return self::newFromRow( $row );
		} else {
			return null;
		}
	}

	/**
	 * Return the list of recentchanges fields that should be selected to create
	 * a new recentchanges object.
	 * @deprecated since 1.31, use self::getQueryInfo() instead.
	 * @return array
	 */
	public static function selectFields() {
		global $wgActorTableSchemaMigrationStage;

		wfDeprecated( __METHOD__, '1.31' );
		if ( $wgActorTableSchemaMigrationStage > MIGRATION_WRITE_BOTH ) {
			// If code is using this instead of self::getQueryInfo(), there's a
			// decent chance it's going to try to directly access
			// $row->rc_user or $row->rc_user_text and we can't give it
			// useful values here once those aren't being written anymore.
			throw new BadMethodCallException(
				'Cannot use ' . __METHOD__ . ' when $wgActorTableSchemaMigrationStage > MIGRATION_WRITE_BOTH'
			);
		}

		return [
			'rc_id',
			'rc_timestamp',
			'rc_user',
			'rc_user_text',
			'rc_actor' => 'NULL',
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
		] + CommentStore::getStore()->getFields( 'rc_comment' );
	}

	/**
	 * Return the tables, fields, and join conditions to be selected to create
	 * a new recentchanges object.
	 * @since 1.31
	 * @return array With three keys:
	 *   - tables: (string[]) to include in the `$table` to `IDatabase->select()`
	 *   - fields: (string[]) to include in the `$vars` to `IDatabase->select()`
	 *   - joins: (array) to include in the `$join_conds` to `IDatabase->select()`
	 */
	public static function getQueryInfo() {
		$commentQuery = CommentStore::getStore()->getJoin( 'rc_comment' );
		$actorQuery = ActorMigration::newMigration()->getJoin( 'rc_user' );
		return [
			'tables' => [ 'recentchanges' ] + $commentQuery['tables'] + $actorQuery['tables'],
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
			] + $commentQuery['fields'] + $actorQuery['fields'],
			'joins' => $commentQuery['joins'] + $actorQuery['joins'],
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
	 * @param array $extra
	 */
	public function setExtra( $extra ) {
		$this->mExtra = $extra;
	}

	/**
	 * @return Title
	 */
	public function &getTitle() {
		if ( $this->mTitle === false ) {
			$this->mTitle = Title::makeTitle( $this->mAttribs['rc_namespace'], $this->mAttribs['rc_title'] );
		}

		return $this->mTitle;
	}

	/**
	 * Get the User object of the person who performed this change.
	 *
	 * @return User
	 */
	public function getPerformer() {
		if ( $this->mPerformer === false ) {
			if ( !empty( $this->mAttribs['rc_actor'] ) ) {
				$this->mPerformer = User::newFromActorId( $this->mAttribs['rc_actor'] );
			} elseif ( !empty( $this->mAttribs['rc_user'] ) ) {
				$this->mPerformer = User::newFromId( $this->mAttribs['rc_user'] );
			} elseif ( !empty( $this->mAttribs['rc_user_text'] ) ) {
				$this->mPerformer = User::newFromName( $this->mAttribs['rc_user_text'], false );
			} else {
				throw new MWException( 'RecentChange object lacks rc_actor, rc_user, and rc_user_text' );
			}
		}

		return $this->mPerformer;
	}

	/**
	 * Writes the data in this object to the database
	 * @param bool $noudp
	 */
	public function save( $noudp = false ) {
		global $wgPutIPinRC, $wgUseEnotif, $wgShowUpdatedMarker;

		$dbw = wfGetDB( DB_MASTER );
		if ( !is_array( $this->mExtra ) ) {
			$this->mExtra = [];
		}

		if ( !$wgPutIPinRC ) {
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

		# If our database is strict about IP addresses, use NULL instead of an empty string
		$strictIPs = in_array( $dbw->getType(), [ 'oracle', 'postgres' ] ); // legacy
		if ( $strictIPs && $this->mAttribs['rc_ip'] == '' ) {
			unset( $this->mAttribs['rc_ip'] );
		}

		# Trim spaces on user supplied text
		$this->mAttribs['rc_comment'] = trim( $this->mAttribs['rc_comment'] );

		# Fixup database timestamps
		$this->mAttribs['rc_timestamp'] = $dbw->timestamp( $this->mAttribs['rc_timestamp'] );

		# # If we are using foreign keys, an entry of 0 for the page_id will fail, so use NULL
		if ( $this->mAttribs['rc_cur_id'] == 0 ) {
			unset( $this->mAttribs['rc_cur_id'] );
		}

		$row = $this->mAttribs;

		# Convert mAttribs['rc_comment'] for CommentStore
		$comment = $row['rc_comment'];
		unset( $row['rc_comment'], $row['rc_comment_text'], $row['rc_comment_data'] );
		$row += CommentStore::getStore()->insert( $dbw, 'rc_comment', $comment );

		# Convert mAttribs['rc_user'] etc for ActorMigration
		$user = User::newFromAnyId(
			isset( $row['rc_user'] ) ? $row['rc_user'] : null,
			isset( $row['rc_user_text'] ) ? $row['rc_user_text'] : null,
			isset( $row['rc_actor'] ) ? $row['rc_actor'] : null
		);
		unset( $row['rc_user'], $row['rc_user_text'], $row['rc_actor'] );
		$row += ActorMigration::newMigration()->getInsertValues( $dbw, 'rc_user', $user );

		# Don't reuse an existing rc_id for the new row, if one happens to be
		# set for some reason.
		unset( $row['rc_id'] );

		# Insert new row
		$dbw->insert( 'recentchanges', $row, __METHOD__ );

		# Set the ID
		$this->mAttribs['rc_id'] = $dbw->insertId();

		# Notify extensions
		// Avoid PHP 7.1 warning from passing $this by reference
		$rc = $this;
		Hooks::run( 'RecentChange_save', [ &$rc ] );

		if ( count( $this->tags ) ) {
			ChangeTags::addTags( $this->tags, $this->mAttribs['rc_id'],
				$this->mAttribs['rc_this_oldid'], $this->mAttribs['rc_logid'], null, $this );
		}

		# Notify external application via UDP
		if ( !$noudp ) {
			$this->notifyRCFeeds();
		}

		# E-mail notifications
		if ( $wgUseEnotif || $wgShowUpdatedMarker ) {
			$editor = $this->getPerformer();
			$title = $this->getTitle();

			// Never send an RC notification email about categorization changes
			if (
				Hooks::run( 'AbortEmailNotification', [ $editor, $title, $this ] ) &&
				$this->mAttribs['rc_type'] != RC_CATEGORIZE
			) {
				// @FIXME: This would be better as an extension hook
				// Send emails or email jobs once this row is safely committed
				$dbw->onTransactionIdle(
					function () use ( $editor, $title ) {
						$enotif = new EmailNotification();
						$enotif->notifyOnPageChange(
							$editor,
							$title,
							$this->mAttribs['rc_timestamp'],
							$this->mAttribs['rc_comment'],
							$this->mAttribs['rc_minor'],
							$this->mAttribs['rc_last_oldid'],
							$this->mExtra['pageStatus']
						);
					},
					__METHOD__
				);
			}
		}

		// Update the cached list of active users
		if ( $this->mAttribs['rc_user'] > 0 ) {
			JobQueueGroup::singleton()->lazyPush( RecentChangesUpdateJob::newCacheUpdateJob() );
		}
	}

	/**
	 * Notify all the feeds about the change.
	 * @param array $feeds Optional feeds to send to, defaults to $wgRCFeeds
	 */
	public function notifyRCFeeds( array $feeds = null ) {
		global $wgRCFeeds;
		if ( $feeds === null ) {
			$feeds = $wgRCFeeds;
		}

		$performer = $this->getPerformer();

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
				( $params['omit_anon'] && $performer->isAnon() ) ||
				( $params['omit_user'] && !$performer->isAnon() ) ||
				( $params['omit_minor'] && $this->mAttribs['rc_minor'] ) ||
				( $params['omit_patrolled'] && $this->mAttribs['rc_patrolled'] ) ||
				$this->mAttribs['rc_type'] == RC_EXTERNAL
			) {
				continue;
			}

			if ( isset( $this->mExtra['actionCommentIRC'] ) ) {
				$actionComment = $this->mExtra['actionCommentIRC'];
			} else {
				$actionComment = null;
			}

			$feed = RCFeed::factory( $params );
			$feed->notify( $this, $actionComment );
		}
	}

	/**
	 * @since 1.22
	 * @deprecated since 1.29 Use RCFeed::factory() instead
	 * @param string $uri URI to get the engine object for
	 * @param array $params
	 * @return RCFeedEngine The engine object
	 * @throws MWException
	 */
	public static function getEngine( $uri, $params = [] ) {
		// TODO: Merge into RCFeed::factory().
		global $wgRCEngines;
		$scheme = parse_url( $uri, PHP_URL_SCHEME );
		if ( !$scheme ) {
			throw new MWException( "Invalid RCFeed uri: '$uri'" );
		}
		if ( !isset( $wgRCEngines[$scheme] ) ) {
			throw new MWException( "Unknown RCFeedEngine scheme: '$scheme'" );
		}
		if ( defined( 'MW_PHPUNIT_TEST' ) && is_object( $wgRCEngines[$scheme] ) ) {
			return $wgRCEngines[$scheme];
		}
		return new $wgRCEngines[$scheme]( $params );
	}

	/**
	 * Mark a given change as patrolled
	 *
	 * @param RecentChange|int $change RecentChange or corresponding rc_id
	 * @param bool $auto For automatic patrol
	 * @param string|string[] $tags Change tags to add to the patrol log entry
	 *   ($user should be able to add the specified tags before this is called)
	 * @return array See doMarkPatrolled(), or null if $change is not an existing rc_id
	 */
	public static function markPatrolled( $change, $auto = false, $tags = null ) {
		global $wgUser;

		$change = $change instanceof RecentChange
			? $change
			: self::newFromId( $change );

		if ( !$change instanceof RecentChange ) {
			return null;
		}

		return $change->doMarkPatrolled( $wgUser, $auto, $tags );
	}

	/**
	 * Mark this RecentChange as patrolled
	 *
	 * NOTE: Can also return 'rcpatroldisabled', 'hookaborted' and
	 * 'markedaspatrollederror-noautopatrol' as errors
	 * @param User $user User object doing the action
	 * @param bool $auto For automatic patrol
	 * @param string|string[] $tags Change tags to add to the patrol log entry
	 *   ($user should be able to add the specified tags before this is called)
	 * @return array Array of permissions errors, see Title::getUserPermissionsErrors()
	 */
	public function doMarkPatrolled( User $user, $auto = false, $tags = null ) {
		global $wgUseRCPatrol, $wgUseNPPatrol, $wgUseFilePatrol;

		$errors = [];
		// If recentchanges patrol is disabled, only new pages or new file versions
		// can be patrolled, provided the appropriate config variable is set
		if ( !$wgUseRCPatrol && ( !$wgUseNPPatrol || $this->getAttribute( 'rc_type' ) != RC_NEW ) &&
			( !$wgUseFilePatrol || !( $this->getAttribute( 'rc_type' ) == RC_LOG &&
			$this->getAttribute( 'rc_log_type' ) == 'upload' ) ) ) {
			$errors[] = [ 'rcpatroldisabled' ];
		}
		// Automatic patrol needs "autopatrol", ordinary patrol needs "patrol"
		$right = $auto ? 'autopatrol' : 'patrol';
		$errors = array_merge( $errors, $this->getTitle()->getUserPermissionsErrors( $right, $user ) );
		if ( !Hooks::run( 'MarkPatrolled',
					[ $this->getAttribute( 'rc_id' ), &$user, false, $auto ] )
		) {
			$errors[] = [ 'hookaborted' ];
		}
		// Users without the 'autopatrol' right can't patrol their
		// own revisions
		if ( $user->getName() === $this->getAttribute( 'rc_user_text' )
			&& !$user->isAllowed( 'autopatrol' )
		) {
			$errors[] = [ 'markedaspatrollederror-noautopatrol' ];
		}
		if ( $errors ) {
			return $errors;
		}
		// If the change was patrolled already, do nothing
		if ( $this->getAttribute( 'rc_patrolled' ) ) {
			return [];
		}
		// Actually set the 'patrolled' flag in RC
		$this->reallyMarkPatrolled();
		// Log this patrol event
		PatrolLog::record( $this, $auto, $user, $tags );

		Hooks::run(
			'MarkPatrolledComplete',
			[ $this->getAttribute( 'rc_id' ), &$user, false, $auto ]
		);

		return [];
	}

	/**
	 * Mark this RecentChange patrolled, without error checking
	 * @return int Number of affected rows
	 */
	public function reallyMarkPatrolled() {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->update(
			'recentchanges',
			[
				'rc_patrolled' => self::PRC_PATROLLED
			],
			[
				'rc_id' => $this->getAttribute( 'rc_id' )
			],
			__METHOD__
		);
		// Invalidate the page cache after the page has been patrolled
		// to make sure that the Patrol link isn't visible any longer!
		$this->getTitle()->invalidateCache();

		return $dbw->affectedRows();
	}

	/**
	 * Makes an entry in the database corresponding to an edit
	 *
	 * @param string $timestamp
	 * @param Title &$title
	 * @param bool $minor
	 * @param User &$user
	 * @param string $comment
	 * @param int $oldId
	 * @param string $lastTimestamp
	 * @param bool $bot
	 * @param string $ip
	 * @param int $oldSize
	 * @param int $newSize
	 * @param int $newId
	 * @param int $patrol
	 * @param array $tags
	 * @return RecentChange
	 */
	public static function notifyEdit(
		$timestamp, &$title, $minor, &$user, $comment, $oldId, $lastTimestamp,
		$bot, $ip = '', $oldSize = 0, $newSize = 0, $newId = 0, $patrol = 0,
		$tags = []
	) {
		$rc = new RecentChange;
		$rc->mTitle = $title;
		$rc->mPerformer = $user;
		$rc->mAttribs = [
			'rc_timestamp' => $timestamp,
			'rc_namespace' => $title->getNamespace(),
			'rc_title' => $title->getDBkey(),
			'rc_type' => RC_EDIT,
			'rc_source' => self::SRC_EDIT,
			'rc_minor' => $minor ? 1 : 0,
			'rc_cur_id' => $title->getArticleID(),
			'rc_user' => $user->getId(),
			'rc_user_text' => $user->getName(),
			'rc_actor' => $user->getActorId(),
			'rc_comment' => &$comment,
			'rc_comment_text' => &$comment,
			'rc_comment_data' => null,
			'rc_this_oldid' => $newId,
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

		$rc->mExtra = [
			'prefixedDBkey' => $title->getPrefixedDBkey(),
			'lastTimestamp' => $lastTimestamp,
			'oldSize' => $oldSize,
			'newSize' => $newSize,
			'pageStatus' => 'changed'
		];

		DeferredUpdates::addCallableUpdate(
			function () use ( $rc, $tags ) {
				$rc->addTags( $tags );
				$rc->save();
			},
			DeferredUpdates::POSTSEND,
			wfGetDB( DB_MASTER )
		);

		return $rc;
	}

	/**
	 * Makes an entry in the database corresponding to page creation
	 * Note: the title object must be loaded with the new id using resetArticleID()
	 *
	 * @param string $timestamp
	 * @param Title &$title
	 * @param bool $minor
	 * @param User &$user
	 * @param string $comment
	 * @param bool $bot
	 * @param string $ip
	 * @param int $size
	 * @param int $newId
	 * @param int $patrol
	 * @param array $tags
	 * @return RecentChange
	 */
	public static function notifyNew(
		$timestamp, &$title, $minor, &$user, $comment, $bot,
		$ip = '', $size = 0, $newId = 0, $patrol = 0, $tags = []
	) {
		$rc = new RecentChange;
		$rc->mTitle = $title;
		$rc->mPerformer = $user;
		$rc->mAttribs = [
			'rc_timestamp' => $timestamp,
			'rc_namespace' => $title->getNamespace(),
			'rc_title' => $title->getDBkey(),
			'rc_type' => RC_NEW,
			'rc_source' => self::SRC_NEW,
			'rc_minor' => $minor ? 1 : 0,
			'rc_cur_id' => $title->getArticleID(),
			'rc_user' => $user->getId(),
			'rc_user_text' => $user->getName(),
			'rc_actor' => $user->getActorId(),
			'rc_comment' => &$comment,
			'rc_comment_text' => &$comment,
			'rc_comment_data' => null,
			'rc_this_oldid' => $newId,
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

		$rc->mExtra = [
			'prefixedDBkey' => $title->getPrefixedDBkey(),
			'lastTimestamp' => 0,
			'oldSize' => 0,
			'newSize' => $size,
			'pageStatus' => 'created'
		];

		DeferredUpdates::addCallableUpdate(
			function () use ( $rc, $tags ) {
				$rc->addTags( $tags );
				$rc->save();
			},
			DeferredUpdates::POSTSEND,
			wfGetDB( DB_MASTER )
		);

		return $rc;
	}

	/**
	 * @param string $timestamp
	 * @param Title &$title
	 * @param User &$user
	 * @param string $actionComment
	 * @param string $ip
	 * @param string $type
	 * @param string $action
	 * @param Title $target
	 * @param string $logComment
	 * @param string $params
	 * @param int $newId
	 * @param string $actionCommentIRC
	 * @return bool
	 */
	public static function notifyLog( $timestamp, &$title, &$user, $actionComment, $ip, $type,
		$action, $target, $logComment, $params, $newId = 0, $actionCommentIRC = ''
	) {
		global $wgLogRestrictions;

		# Don't add private logs to RC!
		if ( isset( $wgLogRestrictions[$type] ) && $wgLogRestrictions[$type] != '*' ) {
			return false;
		}
		$rc = self::newLogEntry( $timestamp, $title, $user, $actionComment, $ip, $type, $action,
			$target, $logComment, $params, $newId, $actionCommentIRC );
		$rc->save();

		return true;
	}

	/**
	 * @param string $timestamp
	 * @param Title &$title
	 * @param User &$user
	 * @param string $actionComment
	 * @param string $ip
	 * @param string $type
	 * @param string $action
	 * @param Title $target
	 * @param string $logComment
	 * @param string $params
	 * @param int $newId
	 * @param string $actionCommentIRC
	 * @param int $revId Id of associated revision, if any
	 * @param bool $isPatrollable Whether this log entry is patrollable
	 * @return RecentChange
	 */
	public static function newLogEntry( $timestamp, &$title, &$user, $actionComment, $ip,
		$type, $action, $target, $logComment, $params, $newId = 0, $actionCommentIRC = '',
		$revId = 0, $isPatrollable = false ) {
		global $wgRequest;

		# # Get pageStatus for email notification
		switch ( $type . '-' . $action ) {
			case 'delete-delete':
			case 'delete-delete_redir':
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
		$markPatrolled = $isPatrollable ? $user->isAllowed( 'autopatrol' ) : true;

		$rc = new RecentChange;
		$rc->mTitle = $target;
		$rc->mPerformer = $user;
		$rc->mAttribs = [
			'rc_timestamp' => $timestamp,
			'rc_namespace' => $target->getNamespace(),
			'rc_title' => $target->getDBkey(),
			'rc_type' => RC_LOG,
			'rc_source' => self::SRC_LOG,
			'rc_minor' => 0,
			'rc_cur_id' => $target->getArticleID(),
			'rc_user' => $user->getId(),
			'rc_user_text' => $user->getName(),
			'rc_actor' => $user->getActorId(),
			'rc_comment' => &$logComment,
			'rc_comment_text' => &$logComment,
			'rc_comment_data' => null,
			'rc_this_oldid' => $revId,
			'rc_last_oldid' => 0,
			'rc_bot' => $user->isAllowed( 'bot' ) ? (int)$wgRequest->getBool( 'bot', true ) : 0,
			'rc_ip' => self::checkIPAddress( $ip ),
			'rc_patrolled' => $markPatrolled ? self::PRC_PATROLLED : self::PRC_UNPATROLLED,
			'rc_new' => 0, # obsolete
			'rc_old_len' => null,
			'rc_new_len' => null,
			'rc_deleted' => 0,
			'rc_logid' => $newId,
			'rc_log_type' => $type,
			'rc_log_action' => $action,
			'rc_params' => $params
		];

		$rc->mExtra = [
			'prefixedDBkey' => $title->getPrefixedDBkey(),
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
	 * @param Title $categoryTitle Title of the category a page is being added to or removed from
	 * @param User $user User object of the user that made the change
	 * @param string $comment Change summary
	 * @param Title $pageTitle Title of the page that is being added or removed
	 * @param int $oldRevId Parent revision ID of this change
	 * @param int $newRevId Revision ID of this change
	 * @param string $lastTimestamp Parent revision timestamp of this change
	 * @param bool $bot true, if the change was made by a bot
	 * @param string $ip IP address of the user, if the change was made anonymously
	 * @param int $deleted Indicates whether the change has been deleted
	 * @param bool $added true, if the category was added, false for removed
	 *
	 * @return RecentChange
	 */
	public static function newForCategorization(
		$timestamp,
		Title $categoryTitle,
		User $user = null,
		$comment,
		Title $pageTitle,
		$oldRevId,
		$newRevId,
		$lastTimestamp,
		$bot,
		$ip = '',
		$deleted = 0,
		$added = null
	) {
		// Done in a backwards compatible way.
		$params = [
			'hidden-cat' => WikiCategoryPage::factory( $categoryTitle )->isHidden()
		];
		if ( $added !== null ) {
			$params['added'] = $added;
		}

		$rc = new RecentChange;
		$rc->mTitle = $categoryTitle;
		$rc->mPerformer = $user;
		$rc->mAttribs = [
			'rc_timestamp' => $timestamp,
			'rc_namespace' => $categoryTitle->getNamespace(),
			'rc_title' => $categoryTitle->getDBkey(),
			'rc_type' => RC_CATEGORIZE,
			'rc_source' => self::SRC_CATEGORIZE,
			'rc_minor' => 0,
			'rc_cur_id' => $pageTitle->getArticleID(),
			'rc_user' => $user ? $user->getId() : 0,
			'rc_user_text' => $user ? $user->getName() : '',
			'rc_actor' => $user ? $user->getActorId() : null,
			'rc_comment' => &$comment,
			'rc_comment_text' => &$comment,
			'rc_comment_data' => null,
			'rc_this_oldid' => $newRevId,
			'rc_last_oldid' => $oldRevId,
			'rc_bot' => $bot ? 1 : 0,
			'rc_ip' => self::checkIPAddress( $ip ),
			'rc_patrolled' => self::PRC_PATROLLED, // Always patrolled, just like log entries
			'rc_new' => 0, # obsolete
			'rc_old_len' => null,
			'rc_new_len' => null,
			'rc_deleted' => $deleted,
			'rc_logid' => 0,
			'rc_log_type' => null,
			'rc_log_action' => '',
			'rc_params' => serialize( $params )
		];

		$rc->mExtra = [
			'prefixedDBkey' => $categoryTitle->getPrefixedDBkey(),
			'lastTimestamp' => $lastTimestamp,
			'oldSize' => 0,
			'newSize' => 0,
			'pageStatus' => 'changed'
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
		return isset( $params[$name] ) ? $params[$name] : null;
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

		if ( isset( $this->mAttribs['rc_ip'] ) ) {
			// Clean up CIDRs for Postgres per T164898. ("127.0.0.1" casts to "127.0.0.1/32")
			$n = strpos( $this->mAttribs['rc_ip'], '/' );
			if ( $n !== false ) {
				$this->mAttribs['rc_ip'] = substr( $this->mAttribs['rc_ip'], 0, $n );
			}
		}

		$comment = CommentStore::getStore()
			// Legacy because $row may have come from self::selectFields()
			->getCommentLegacy( wfGetDB( DB_REPLICA ), 'rc_comment', $row, true )
			->text;
		$this->mAttribs['rc_comment'] = &$comment;
		$this->mAttribs['rc_comment_text'] = &$comment;
		$this->mAttribs['rc_comment_data'] = null;

		$user = User::newFromAnyId(
			isset( $this->mAttribs['rc_user'] ) ? $this->mAttribs['rc_user'] : null,
			isset( $this->mAttribs['rc_user_text'] ) ? $this->mAttribs['rc_user_text'] : null,
			isset( $this->mAttribs['rc_actor'] ) ? $this->mAttribs['rc_actor'] : null
		);
		$this->mAttribs['rc_user'] = $user->getId();
		$this->mAttribs['rc_user_text'] = $user->getName();
		$this->mAttribs['rc_actor'] = $user->getActorId();
	}

	/**
	 * Get an attribute value
	 *
	 * @param string $name Attribute name
	 * @return mixed
	 */
	public function getAttribute( $name ) {
		if ( $name === 'rc_comment' ) {
			return CommentStore::getStore()
				->getComment( 'rc_comment', $this->mAttribs, true )->text;
		}

		if ( $name === 'rc_user' || $name === 'rc_user_text' || $name === 'rc_actor' ) {
			$user = User::newFromAnyId(
				isset( $this->mAttribs['rc_user'] ) ? $this->mAttribs['rc_user'] : null,
				isset( $this->mAttribs['rc_user_text'] ) ? $this->mAttribs['rc_user_text'] : null,
				isset( $this->mAttribs['rc_actor'] ) ? $this->mAttribs['rc_actor'] : null
			);
			if ( $name === 'rc_user' ) {
				return $user->getId();
			}
			if ( $name === 'rc_user_text' ) {
				return $user->getName();
			}
			if ( $name === 'rc_actor' ) {
				return $user->getActorId();
			}
		}

		return isset( $this->mAttribs[$name] ) ? $this->mAttribs[$name] : null;
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

	private static function checkIPAddress( $ip ) {
		global $wgRequest;
		if ( $ip ) {
			if ( !IP::isIPAddress( $ip ) ) {
				throw new MWException( "Attempt to write \"" . $ip .
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
		global $wgRCMaxAge;

		return wfTimestamp( TS_UNIX, $timestamp ) > time() - $tolerance - $wgRCMaxAge;
	}

	/**
	 * Parses and returns the rc_params attribute
	 *
	 * @since 1.26
	 *
	 * @return mixed|bool false on failed unserialization
	 */
	public function parseParams() {
		$rcParams = $this->getAttribute( 'rc_params' );

		Wikimedia\suppressWarnings();
		$unserializedParams = unserialize( $rcParams );
		Wikimedia\restoreWarnings();

		return $unserializedParams;
	}

	/**
	 * Tags to append to the recent change,
	 * and associated revision/log
	 *
	 * @since 1.28
	 *
	 * @param string|array $tags
	 */
	public function addTags( $tags ) {
		if ( is_string( $tags ) ) {
			$this->tags[] = $tags;
		} else {
			$this->tags = array_merge( $tags, $this->tags );
		}
	}
}
