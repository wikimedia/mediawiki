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

use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Context\RequestContext;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\JobQueue\JobQueueGroup;
use MediaWiki\Json\FormatJson;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Storage\EditResult;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\User\ActorStoreFactory;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\Utils\MWTimestamp;
use RuntimeException;
use Wikimedia\Assert\Assert;
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * @since 1.45
 */
class RecentChangeStore implements RecentChangeFactory, RecentChangeLookup {

	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::LogRestrictions,
		MainConfigNames::PutIPinRC,
		MainConfigNames::EnotifUserTalk,
		MainConfigNames::EnotifWatchlist,
		MainConfigNames::ShowUpdatedMarker,
	];

	private ActorStoreFactory $actorStoreFactory;
	private ChangeTagsStore $changeTagsStore;
	private IConnectionProvider $connectionProvider;
	private CommentStore $commentStore;
	private HookContainer $hookContainer;
	private JobQueueGroup $jobQueueGroup;
	private PermissionManager $permissionManager;
	private ServiceOptions $options;
	private TitleFormatter $titleFormatter;
	private WikiPageFactory $wikiPageFactory;
	private UserFactory $userFactory;

	public function __construct(
		ActorStoreFactory $actorStoreFactory,
		ChangeTagsStore $changeTagsStore,
		IConnectionProvider $connectionProvider,
		CommentStore $commentStore,
		HookContainer $hookContainer,
		JobQueueGroup $jobQueueGroup,
		PermissionManager $permissionManager,
		ServiceOptions $options,
		TitleFormatter $titleFormatter,
		WikiPageFactory $wikiPageFactory,
		UserFactory $userFactory
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->actorStoreFactory = $actorStoreFactory;
		$this->changeTagsStore = $changeTagsStore;
		$this->connectionProvider = $connectionProvider;
		$this->commentStore = $commentStore;
		$this->hookContainer = $hookContainer;
		$this->jobQueueGroup = $jobQueueGroup;
		$this->permissionManager = $permissionManager;
		$this->options = $options;
		$this->titleFormatter = $titleFormatter;
		$this->wikiPageFactory = $wikiPageFactory;
		$this->userFactory = $userFactory;
	}

	/**
	 * @inheritDoc
	 */
	public function newRecentChangeFromRow( $row ) {
		$rc = new RecentChange;
		$rc->loadFromRow( $row );
		return $rc;
	}

	/**
	 * @inheritDoc
	 */
	public function getRecentChangeById( $rcid ) {
		return $this->getRecentChangeByConds( [ 'rc_id' => $rcid ], __METHOD__ );
	}

	/**
	 * @inheritDoc
	 */
	public function getRecentChangeByConds( $conds, $fname = __METHOD__, $fromPrimary = false ) {
		if ( $fromPrimary ) {
			$db = $this->connectionProvider->getPrimaryDatabase();
		} else {
			$db = $this->connectionProvider->getReplicaDatabase();
		}

		$row = $db->newSelectQueryBuilder()
			->select( '*' )
			->from( 'recentchanges' )
			->where( $conds )
			->caller( $fname )
			->fetchRow();

		if ( $row ) {
			return $this->newRecentChangeFromRow( $row );
		}
		return null;
	}

	/**
	 * @inheritDoc
	 */
	public function insertRecentChange( RecentChange $recentChange, bool $send = self::SEND_FEED ) {
		$putIPinRC = $this->options->get( MainConfigNames::PutIPinRC );
		$dbw = $this->connectionProvider->getPrimaryDatabase();
		if ( !is_array( $recentChange->getExtras() ) ) {
			$recentChange->setExtra( [] );
		}

		if ( !$putIPinRC ) {
			$recentChange->setAttribute( 'rc_ip', '' );
		}

		// Strict mode fixups (not-NULL fields)
		foreach ( [ 'minor', 'bot', 'patrolled', 'deleted' ] as $field ) {
			$recentChange->setAttribute( "rc_$field", (int)$recentChange->getAttribute( "rc_$field" ) );
		}
		// ...more fixups (NULL fields)
		foreach ( [ 'old_len', 'new_len' ] as $field ) {
			$recentChange->setAttribute( "rc_$field", $recentChange->getAttribute( "rc_$field" ) !== null
				? (int)$recentChange->getAttribute( "rc_$field" )
				: null );
		}

		// rc_new is deprecated, but we still support it for compatibility reasons.
		if ( $recentChange->getAttribute( 'rc_new' ) !== null ) {
			$recentChange->setAttribute( 'rc_new', (int)$recentChange->getAttribute( 'rc_new' ) );
		}

		$row = $recentChange->getAttributes();

		// Trim spaces on user supplied text
		$row['rc_comment'] = trim( $row['rc_comment'] ?? '' );

		// Fixup database timestamps
		$row['rc_timestamp'] = $dbw->timestamp( $row['rc_timestamp'] );

		// If we are using foreign keys, an entry of 0 for the page_id will fail, so use NULL
		if ( $row['rc_cur_id'] == 0 ) {
			unset( $row['rc_cur_id'] );
		}

		// Convert mAttribs['rc_comment'] for CommentStore
		$comment = $row['rc_comment'];
		unset( $row['rc_comment'], $row['rc_comment_text'], $row['rc_comment_data'] );
		$row += $this->commentStore->insert( $dbw, 'rc_comment', $comment );

		// Normalize UserIdentity to actor ID
		$user = $recentChange->getPerformerIdentity();
		if ( array_key_exists( 'forImport', $recentChange->getExtras() ) && $recentChange->getExtra( 'forImport' ) ) {
			$actorStore = $this->actorStoreFactory->getActorStoreForImport();
		} else {
			$actorStore = $this->actorStoreFactory->getActorStore();
		}
		$row['rc_actor'] = $actorStore->acquireActorId( $user, $dbw );
		unset( $row['rc_user'], $row['rc_user_text'] );

		// Don't reuse an existing rc_id for the new row, if one happens to be
		// set for some reason.
		unset( $row['rc_id'] );

		// Insert new row
		$dbw->newInsertQueryBuilder()
			->insertInto( 'recentchanges' )
			->row( $row )
			->caller( __METHOD__ )->execute();

		// Set the ID
		$recentChange->setAttribute( 'rc_id', $dbw->insertId() );

		// Notify extensions
		$hookRunner = new HookRunner( $this->hookContainer );
		$hookRunner->onRecentChange_save( $recentChange );

		// Apply revert tags (if needed)
		$editResult = $recentChange->getEditResult();
		if ( $editResult !== null && count( $editResult->getRevertTags() ) ) {
			$this->changeTagsStore->addTags(
				$editResult->getRevertTags(),
				$recentChange->getAttribute( 'rc_id' ),
				$recentChange->getAttribute( 'rc_this_oldid' ),
				$recentChange->getAttribute( 'rc_logid' ),
				FormatJson::encode( $editResult ),
				$recentChange
			);
		}

		if ( count( $recentChange->getTags() ) ) {
			// $this->tags may contain revert tags we already applied above, they will
			// just be ignored.
			$this->changeTagsStore->addTags(
				$recentChange->getTags(),
				$recentChange->getAttribute( 'rc_id' ),
				$recentChange->getAttribute( 'rc_this_oldid' ),
				$recentChange->getAttribute( 'rc_logid' ),
				null,
				$recentChange
			);
		}

		if ( $send === self::SEND_FEED ) {
			// Emit the change to external applications via RCFeeds.
			$recentChange->notifyRCFeeds();
		}

		// E-mail notifications
		if ( self::isEnotifEnabled( $this->options ) ) {
			$editor = $this->userFactory->newFromUserIdentity( $recentChange->getPerformerIdentity() );
			$title = Title::castFromPageReference( $recentChange->getPage() );

			if ( $title && $hookRunner->onAbortEmailNotification( $editor, $title, $recentChange ) ) {
				// @FIXME: This would be better as an extension hook
				// Send emails or email jobs once this row is safely committed
				$dbw->onTransactionCommitOrIdle(
					static function () use ( $recentChange ) {
						$notifier = new RecentChangeNotifier();
						$notifier->notifyOnPageChange( $recentChange );
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
		if ( $recentChange->getAttribute( 'rc_user' ) > 0 ) {
			$jobs[] = RecentChangesUpdateJob::newCacheUpdateJob();
		}
		$this->jobQueueGroup->lazyPush( $jobs );
	}

	/**
	 * Whether e-mail notifications are generally enabled on this wiki.
	 *
	 * This is used for:
	 *
	 * - performance optimization in RecentChangeStore::insertRecentChange().
	 *   After an edit, whether or not we need to use the RecentChangeNotifier
	 *   to determine which RecentChangeNotifyJob to dispatch.
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
	 * the RecentChangeNotifier/UserMailer classes. As of August 2022, this
	 * appears to no longer be the case.
	 *
	 * @param ServiceOptions $options
	 * @return bool
	 */
	public static function isEnotifEnabled( ServiceOptions $options ): bool {
		return $options->get( MainConfigNames::EnotifUserTalk ) ||
			$options->get( MainConfigNames::EnotifWatchlist ) ||
			$options->get( MainConfigNames::ShowUpdatedMarker );
	}

	/**
	 * @inheritDoc
	 */
	public function createEditRecentChange(
		$timestamp,
		$page,
		$minor,
		$user,
		$comment,
		$oldId,
		$lastTimestamp,
		$bot,
		$ip = '',
		$oldSize = 0,
		$newSize = 0,
		$newId = 0,
		$patrol = 0,
		$tags = [],
		?EditResult $editResult = null
	): RecentChange {
		Assert::parameter( $page->exists(), '$page', 'must represent an existing page' );

		$rc = new RecentChange( $page, $user );
		$rc->setAttribs( [
			'rc_timestamp' => $timestamp,
			'rc_namespace' => $page->getNamespace(),
			'rc_title' => $page->getDBkey(),
			'rc_type' => RC_EDIT,
			'rc_source' => RecentChange::SRC_EDIT,
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
		] );

		$rc->setExtra( [
			// TODO: deprecate the 'prefixedDBkey' entry, let callers do the formatting.
			'prefixedDBkey' => $this->titleFormatter->getPrefixedDBkey( $page ),
			'lastTimestamp' => $lastTimestamp,
			'oldSize' => $oldSize,
			'newSize' => $newSize,
			'pageStatus' => 'changed'
		] );

		$rc->addTags( $tags );
		$rc->setEditResult( $editResult );

		return $rc;
	}

	/**
	 * @inheritDoc
	 */
	public function createNewPageRecentChange(
		$timestamp,
		$page,
		$minor,
		$user,
		$comment,
		$bot,
		$ip = '',
		$size = 0,
		$newId = 0,
		$patrol = 0,
		$tags = []
	): RecentChange {
		Assert::parameter( $page->exists(), '$page', 'must represent an existing page' );

		$rc = new RecentChange( $page, $user );
		$rc->setAttribs( [
			'rc_timestamp' => $timestamp,
			'rc_namespace' => $page->getNamespace(),
			'rc_title' => $page->getDBkey(),
			'rc_type' => RC_NEW,
			'rc_source' => RecentChange::SRC_NEW,
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
		] );

		$rc->setExtra( [
			// TODO: deprecate the 'prefixedDBkey' entry, let callers do the formatting.
			'prefixedDBkey' => $this->titleFormatter->getPrefixedDBkey( $page ),
			'lastTimestamp' => 0,
			'oldSize' => 0,
			'newSize' => $size,
			'pageStatus' => 'created'
		] );

		$rc->addTags( $tags );

		return $rc;
	}

	/**
	 * @inheritDoc
	 */
	public function createLogRecentChange(
		$timestamp,
		$logPage,
		$user,
		$actionComment,
		$ip,
		$type,
		$action,
		$target,
		$logComment,
		$params,
		$newId = 0,
		$actionCommentIRC = '',
		$revId = 0,
		$isPatrollable = false,
		$forceBotFlag = null
	): RecentChange {
		// Get pageStatus for email notification
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
		$canAutopatrol = $this->permissionManager->userHasRight( $user, 'autopatrol' );
		$markPatrolled = $isPatrollable ? $canAutopatrol : true;

		if ( $target instanceof PageIdentity && $target->canExist() ) {
			$pageId = $target->getId();
		} else {
			$pageId = 0;
		}

		if ( $forceBotFlag !== null ) {
			$bot = (int)$forceBotFlag;
		} else {
			$bot = $this->permissionManager->userHasRight( $user, 'bot' ) ?
				(int)RequestContext::getMain()->getRequest()->getBool( 'bot', true ) : 0;
		}

		$rc = new RecentChange( $target, $user );
		$rc->setAttribs( [
			'rc_timestamp' => $timestamp,
			'rc_namespace' => $target->getNamespace(),
			'rc_title' => $target->getDBkey(),
			'rc_type' => RC_LOG,
			'rc_source' => RecentChange::SRC_LOG,
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
			'rc_patrolled' => $markPatrolled ? RecentChange::PRC_AUTOPATROLLED : RecentChange::PRC_UNPATROLLED,
			'rc_new' => 0, # obsolete
			'rc_old_len' => null,
			'rc_new_len' => null,
			'rc_deleted' => 0,
			'rc_logid' => $newId,
			'rc_log_type' => $type,
			'rc_log_action' => $action,
			'rc_params' => $params
		] );

		$rc->setExtra( [
			// XXX: This does not correspond to rc_namespace/rc_title/rc_cur_id.
			//	  Is that intentional? For all other kinds of RC entries, prefixedDBkey
			//	  matches rc_namespace/rc_title. Do we even need $logPage?
			// TODO: deprecate the 'prefixedDBkey' entry, let callers do the formatting.
			'prefixedDBkey' => $this->titleFormatter->getPrefixedDBkey( $logPage ),
			'lastTimestamp' => 0,
			'actionComment' => $actionComment, // the comment appended to the action, passed from LogPage
			'pageStatus' => $pageStatus,
			'actionCommentIRC' => $actionCommentIRC
		] );

		return $rc;
	}

	/**
	 * @inheritDoc
	 */
	public function createCategorizationRecentChange(
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
		$forImport = false
	): RecentChange {
		// Done in a backwards compatible way.
		$categoryWikiPage = $this->wikiPageFactory->newFromTitle( $categoryTitle );

		'@phan-var \MediaWiki\Page\WikiCategoryPage $categoryWikiPage';
		$params = [
			'hidden-cat' => $categoryWikiPage->isHidden()
		];
		if ( $added !== null ) {
			$params['added'] = $added;
		}

		if ( !$user ) {
			// XXX: when and why do we need this?
			$user = $this->actorStoreFactory->getActorStore()->getUnknownActor();
		}

		$rc = new RecentChange( $categoryTitle, $user );
		$rc->setAttribs( [
			'rc_timestamp' => MWTimestamp::convert( TS_MW, $timestamp ),
			'rc_namespace' => $categoryTitle->getNamespace(),
			'rc_title' => $categoryTitle->getDBkey(),
			'rc_type' => RC_CATEGORIZE,
			'rc_source' => RecentChange::SRC_CATEGORIZE,
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
			'rc_patrolled' => RecentChange::PRC_AUTOPATROLLED, // Always patrolled, just like log entries
			'rc_new' => 0, # obsolete
			'rc_old_len' => null,
			'rc_new_len' => null,
			'rc_deleted' => $deleted,
			'rc_logid' => 0,
			'rc_log_type' => null,
			'rc_log_action' => '',
			'rc_params' => serialize( $params )
		] );

		$rc->setExtra( [
			// TODO: deprecate the 'prefixedDBkey' entry, let callers do the formatting.
			'prefixedDBkey' => $this->titleFormatter->getPrefixedDBkey( $categoryTitle ),
			'lastTimestamp' => $lastTimestamp,
			'oldSize' => 0,
			'newSize' => 0,
			'pageStatus' => 'changed',
			'forImport' => $forImport,
		] );

		return $rc;
	}

	private static function checkIPAddress( string $ip ): string {
		if ( $ip ) {
			if ( !IPUtils::isIPAddress( $ip ) ) {
				throw new RuntimeException( "Attempt to write \"" . $ip .
					"\" as an IP address into recent changes" );
			}
		} else {
			$ip = RequestContext::getMain()->getRequest()->getIP();
			if ( !$ip ) {
				$ip = '';
			}
		}

		return $ip;
	}
}
