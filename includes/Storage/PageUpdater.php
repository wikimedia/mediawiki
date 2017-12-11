<?php

namespace MediaWiki\Storage;

use ApiStashEdit;
use AtomicSectionUpdate;
use BagOStuff;
use Category;
use CategoryMembershipChangeJob;
use CommentStore;
use CommentStoreComment;
use Content;
use ContentHandler;
use DeferredUpdates;
use Exception;
use FatalError;
use Hooks;
use IContextSource;
use IDBAccessObject;
use InfoAction;
use InvalidArgumentException;
use IP;
use JobQueueGroup;
use Language;
use LinksUpdate;
use ManualLogEntry;
use MediaWiki\Edit\PreparedEdit;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\PageIdentity;
use Message;
use MessageCache;
use MessageLocalizer;
use MWException;
use MWNamespace;
use ParserCache;
use ParserOptions;
use Psr\Log\LoggerInterface;
use RecentChange;
use RecentChangesUpdateJob;
use RepoGroup;
use ResourceLoaderWikiModule;
use Revision;
use RuntimeException;
use SearchUpdate;
use SiteStatsUpdate;
use Status;
use Title;
use User;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\DBConnRef;
use Wikimedia\Rdbms\DBUnexpectedError;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LoadBalancer;
use WikiPage;

/**
 * FIXME: header
 * FIXME: document!
 */
class PageUpdater implements IDBAccessObject {

	// TODO: make subclasses for:
	// - null revision (with custom message, convenience for protection, maybe move, etc)
	// - regular edit (or creation?)
	// - deletion
	// - rollback???

	/** @var PreparedEdit Map of cache fields (text, parser output, ect) for a proposed/new edit */
	public $preparedEdit = null;

	/**
	 * @var PageRecord|null
	 */
	private $pageRecord = null;

	/**
	 * @var PageIdentity
	 */
	private $pageIdentity;

	/**
	 * @var PageEventEmitter
	 */
	private $eventEmitter;

	/**
	 * @var string see $wgArticleCountMethod
	 */
	private $articleCountMethod;

	/**
	 * @var ParserCache
	 */
	private $parserCache;

	/**
	 * @var RepoGroup
	 */
	private $repoGroup;

	/**
	 * @var LoadBalancer
	 */
	private $loadBalancer;

	/**
	 * @var string|boolean
	 */
	private $wikiId;

	/**
	 * @var WikiPage|null for compat only; do not use directly, use asWikiPage()
	 */
	private $wikiPage = null;

	/**
	 * @var Title|null for compat only; do not use directly, use getTitle()
	 */
	private $title = null;

	/**
	 * @var RevisionStore
	 */
	private $revisionStore;

	/**
	 * @var PageStore
	 */
	private $pageStore;

	/**
	 * @var PageEventEmitter
	 */
	private $pageEventEmitter;

	/**
	 * @var PageContentComposer
	 */
	private $pageContentComposer;

	/**
	 * @var BagOStuff
	 */
	private $stash;

	/**
	 * Typically LoggerFactory::getInstance( 'SaveParse' );
	 * @var LoggerInterface
	 */
	private $saveParseLogger;

	/**
	 * @var JobQueueGroup
	 */
	private $jobQueueGroup;

	/**
	 * @var int One of the READ_* constants
	 */
	private $loadedFrom = self::READ_NONE;

	/**
	 * @var MessageLocalizer
	 */
	private $localizer;

	/**
	 * @var MessageCache
	 */
	private $messageCache;

	/**
	 * @var boolean
	 */
	private $useAutomaticEditSummaries = false; // TODO: setters, wiring!

	/**
	 * @var boolean,
	 */
	private $useRCPatrol = false; // TODO: setters, wiring!

	/**
	 * @var boolean
	 */
	private $useNPPatrol = false; // TODO: setters, wiring!

	/**
	 * @var FIXME
	 */
	private $ajaxEditStash = false; // TODO: setters, wiring!

	/**
	 * @var Language
	 */
	private $contentLanguage; // FIXME: init!

	/**
	 * @var boolean
	 */
	private $rcWatchCategoryMembership = false;

	/**
	 * @var bool
	 */
	private $contentHandlerUseDB;

	/**
	 * @var int
	 */
	private $commentTableSchemaMigrationStage = MIGRATION_NEW;

	/**
	 * PageUpdater constructor.
	 *
	 * @param PageIdentity $pageIdentity
	 * @param bool|string $wikiId
	 * @param LoadBalancer $loadBalancer
	 * @param RepoGroup $repoGroup
	 * @param ParserCache $parserCache
	 * @param PageEventEmitter $eventEmitter
	 * @param RevisionStore $revisionStore
	 * @param PageStore $pageStore
	 * @param PageEventEmitter $pageEventEmitter
	 * @param PageContentComposer $pageContentComposer
	 * @param BagOStuff $stash
	 * @param JobQueueGroup $jobQueueGroup
	 * @param MessageLocalizer $localizer
	 * @param MessageCache $messageCache
	 */
	public function __construct(
		PageIdentity $pageIdentity,
		$wikiId,
		LoadBalancer $loadBalancer,
		RepoGroup $repoGroup,
		ParserCache $parserCache,
		PageEventEmitter $eventEmitter,
		RevisionStore $revisionStore,
		PageStore $pageStore,
		PageEventEmitter $pageEventEmitter,
		PageContentComposer $pageContentComposer,
		BagOStuff $stash,
		JobQueueGroup $jobQueueGroup,
		MessageLocalizer $localizer,
		MessageCache $messageCache
	) {
		$this->pageIdentity = $pageIdentity;
		$this->wikiId = $wikiId; // XXX: get that from PageIdentity?!
		$this->loadBalancer = $loadBalancer;
		$this->repoGroup = $repoGroup;
		$this->parserCache = $parserCache;
		$this->eventEmitter = $eventEmitter;
		$this->revisionStore = $revisionStore;
		$this->pageStore = $pageStore;
		$this->pageEventEmitter = $pageEventEmitter;
		$this->pageContentComposer = $pageContentComposer;
		$this->stash = $stash;
		$this->jobQueueGroup = $jobQueueGroup;
		$this->localizer = $localizer;
		$this->messageCache = $messageCache;
	}

	/**
	 * Determine whether a page would be suitable for being counted as an
	 * article in the site_stats table based on the title & its content
	 *
	 * @return bool
	 */
	private function isCountable() {

		if ( !MWNamespace::isContent( $this->pageIdentity->getNamespace() )
			|| $this->getPage()->isRedirect()
		) {
			return false;
		}

		if ( !$this->preparedEdit ) {
			return $this->eventEmitter->isCountable( $this->getPage() );
		}

		$content = $this->preparedEdit->pstContent;

		$hasLinks = null;

		if ( $this->articleCountMethod === 'link' ) {
			// ParserOutput::getLinks() is a 2D array of page links, so
			// to be really correct we would need to recurse in the array
			// but the main array should only have items in it if there are
			// links.
			$hasLinks = (bool)count( $this->preparedEdit->output->getLinks() );
		}

		return $content->isCountable( $hasLinks );
	}

	/**
	 * Add row to the redirect table if this is a redirect, remove otherwise.
	 *
	 * @param IDatabase $dbw
	 * @param Title $redirectTitle Title object pointing to the redirect target,
	 *   or NULL if this is not a redirect
	 * @param null|bool $lastRevIsRedirect If given, will optimize adding and
	 *   removing rows in redirect table.
	 * @return bool True on success, false on failure
	 * @private
	 */
	public function updateRedirectOn( $dbw, $redirectTitle, $lastRevIsRedirect = null ) {
		// FIXME: compare PageStore::updateRedirectOn
		// FIXME: make sure we actually trigger this when a redirect is updated!
		// FIXME: move to PageEventEmitter?....

		if ( $this->pageIdentity->getNamespace() == NS_FILE ) {
			$this->repoGroup->getLocalRepo()->invalidateImageRedirect( $this->getTitle() );
		}

		return ( $dbw->affectedRows() != 0 );
	}

	/**
	 * Insert an entry for this page into the redirect table if the content is a redirect
	 *
	 * The database update will be deferred via DeferredUpdates
	 *
	 * Don't call this function directly unless you know what you're doing.
	 *
	 * @param PageRecord $pageIdentity
	 *
	 * @return LinkTarget|null the redirect target or null if not a redirect
	 */
	/*private function insertRedirect() {
		// FIXME: make sure we actually trigger this when a redirect is updated!

		$revision = $this->page->getCurrentRevision();

		if ( !$revision ) {
			return null;
		}

		$content = $revision->getContent( 'main', RevisionRecord::RAW );

		$target = $content ? $content->getUltimateRedirectTarget() : null;

		if ( !$target ) {
			return null;
		}

		// Update the DB post-send if the page has not cached since now
		$latest = $this->page->getLatest();
		DeferredUpdates::addCallableUpdate(
			function () use ( $page, $target, $latest ) {
				$this->insertRedirectEntry( $this->page->getId(), $target, $latest );
			},
			DeferredUpdates::POSTSEND,
			$this->getDBConnectionRef( DB_MASTER )
		);

		return $target;
	}*/

	/**
	 * @param string $slotRole slot role name
	 * @param string|int|null|bool $sectionId Section identifier as a number or string
	 * (e.g. 0, 1 or 'T-1'), null/false or an empty string for the whole page
	 * or 'new' for a new section.
	 * @param Content $sectionContent New content of the section.
	 * @param string $sectionTitle New section's subject, only if $section is "new".
	 * @param string $edittime RevisionRecord timestamp or null to use the current revision.
	 *
	 * @return Content|null
	 * @throws MWException
	 * @since 1.21
	 * @deprecated since 1.24, use replaceSectionAtRev instead
	 */
	public function replaceSectionContent(
		$slotRole, $sectionId, Content $sectionContent, $sectionTitle = '', $edittime = null
	) {
		$baseRevId = null;
		if ( $edittime && $sectionId !== 'new' ) {
			$dbr = wfGetDB( DB_REPLICA );
			$rev = $this->revisionStore->loadRevisionFromTimestamp( $dbr, $this->pageIdentity, $edittime );
			// Try the master if this thread may have just added it.
			// This could be abstracted into a RevisionRecord method, but we don't want
			// to encourage loading of revisions by timestamp.
			if ( !$rev
				&& wfGetLB()->getServerCount() > 1
				&& wfGetLB()->hasOrMadeRecentMasterChanges()
			) {
				$dbw = wfGetDB( DB_MASTER );
				$rev = $this->revisionStore->loadRevisionFromTimestamp( $dbw, $this->pageIdentity, $edittime );
			}
			if ( $rev ) {
				$baseRevId = $rev->getId();
			}
		}

		return $this->replaceSectionAtRev( $slotRole, $sectionId, $sectionContent, $this->pageIdentity, $baseRevId );
	}

	/**
	 * @param string $slotRole the role name of the slot to modify
	 * @param string|int|null|bool $sectionId Section identifier as a number or string
	 * (e.g. 0, 1 or 'T-1'), null/false or an empty string for the whole page
	 * or 'new' for a new section.
	 * @param Content $sectionContent New content of the section.
	 * @param string $sectionTitle New section's subject, only if $section is "new".
	 * @param int|null $baseRevId
	 *
	 * @return Content|null
	 * @throws MWException
	 * @since 1.24
	 */
	public function replaceSectionAtRev( $slotRole, $sectionId, Content $sectionContent,
		$sectionTitle = '', $baseRevId = null
	) {
		if ( strval( $sectionId ) === '' ) {
			// Whole-page edit; let the whole text through
			$newContent = $sectionContent;
		} else {
			$contentHandler = $this->getContentHandler( $slotRole );
			if ( !$contentHandler->supportsSections() ) {
				throw new MWException( "sections not supported for content model " .
					$contentHandler->getModelID() );
			}

			// T32711: always use current version when adding a new section
			if ( is_null( $baseRevId ) || $sectionId === 'new' ) {
				$oldContent = $this->getContentUnchecked( $slotRole );
			} else {
				$rev = $this->revisionStore->getRevisionById( $baseRevId );
				if ( !$rev ) {
					wfDebug( __METHOD__ . " asked for bogus section (page: " .
						$this->pageIdentity->getId() . "; section: $sectionId)\n" );
					return null;
				}

				$oldContent = $rev->getContent( $slotRole );
			}

			if ( !$oldContent ) {
				wfDebug( __METHOD__ . ": no page text\n" );
				return null;
			}

			$newContent = $oldContent->replaceSection( $sectionId, $sectionContent, $sectionTitle );
		}

		return $newContent;
	}

	/**
	 * Check flags and add EDIT_NEW or EDIT_UPDATE to them as needed.
	 * @param int $flags
	 * @return int Updated $flags
	 */
	public function checkFlags( $flags ) {
		if ( !( $flags & EDIT_NEW ) && !( $flags & EDIT_UPDATE ) ) {
			if ( $this->pageIdentity->exists() ) {
				$flags |= EDIT_UPDATE;
			} else {
				$flags |= EDIT_NEW;
			}
		}

		return $flags;
	}
	/**
	 * Change an existing article or create a new article. Updates RC and all necessary caches,
	 * optionally via the deferred update array.
	 *
	 * @param Content[] $content Array of new content, using slot role names as keys.
	 * @param string|Message|CommentStoreComment $summary Edit summary
	 * @param User $user The user doing the edit
	 * @param int $flags Bitfield:
	 *      EDIT_NEW
	 *          Article is known or assumed to be non-existent, create a new one
	 *      EDIT_UPDATE
	 *          Article is known or assumed to be pre-existing, update it
	 *      EDIT_MINOR
	 *          Mark this edit minor, if the user is allowed to do so
	 *      EDIT_SUPPRESS_RC
	 *          Do not log the change in recentchanges
	 *      EDIT_FORCE_BOT
	 *          Mark the edit a "bot" edit regardless of user rights
	 *      EDIT_AUTOSUMMARY
	 *          Fill in blank summaries with generated text where possible
	 *      EDIT_INTERNAL
	 *          Signal that the page retrieve/save cycle happened entirely in this request.
	 *
	 * If neither EDIT_NEW nor EDIT_UPDATE is specified, the status of the
	 * article will be detected. If EDIT_UPDATE is specified and the article
	 * doesn't exist, the function will return an edit-gone-missing error. If
	 * EDIT_NEW is specified and the article does exist, an edit-already-exists
	 * error will be returned. These two conditions are also possible with
	 * auto-detection due to MediaWiki's performance-optimised locking strategy.
	 *
	 * @param bool|int $baseRevId The revision ID this edit was based off, if any.
	 *   This is not the parent revision ID, rather the revision ID for older
	 *   content used as the source for a rollback, for example.
	 * @param array $tags Change tags to apply to this edit
	 * Callers are responsible for permission checks
	 * (with ChangeTags::canAddTagsAccompanyingChange)
	 * @param Int $undidRevId Id of revision that was undone or 0
	 *
	 * @throws MWException
	 * @return Status Possible errors:
	 *     edit-hook-aborted: The ArticleSave hook aborted the edit but didn't
	 *       set the fatal flag of $status.
	 *     edit-gone-missing: In update mode, but the article didn't exist.
	 *     edit-conflict: In update mode, the article changed unexpectedly.
	 *     edit-no-change: Warning that the text was the same as before.
	 *     edit-already-exists: In creation mode, but the article already exists.
	 *
	 *  Extensions may define additional errors.
	 *
	 *  $return->value will contain an associative array with members as follows:
	 *     new: Boolean indicating if the function attempted to create a new article.
	 *     revision: The revision object for the inserted revision, or null.
	 *
	 * @since 1.21
	 * @throws MWException
	 */
	public function doEditContent(
		array $content,
		$summary,
		User $user,
		$flags = 0,
		$baseRevId = false,
		array $tags = [],
		$undidRevId = 0
	) {
		// Defend against mistakes caused by differences with the
		// signature of WikiPage::doEditContent.
		Assert::parameterElementType( 'Content', $content, '$content' );
		Assert::parameterType( 'integer', $flags, '$flags' );
		Assert::parameterType( 'integer|boolean', $baseRevId, '$baseRevId' );
		Assert::parameterType( 'array', $tags, '$tags' );
		Assert::parameterType( 'string|CommentStoreComment', $summary, '$summary' );
		Assert::parameterType( 'integer', $undidRevId, '$undidRevId' );

		if ( ! $summary instanceof CommentStoreComment ) {
			$summary = CommentStoreComment::newUnsavedComment( $summary );
		}

		if ( count( $content ) !== 1 || !isset( $content['main'] ) ) {
			throw new InvalidArgumentException(
				'Only content for the "main" slot is currently supported'
			);
		}

		/** @var Content $content */
		$content = $content['main']; // TODO: MCR!

		// Low-level sanity check
		if ( $this->pageIdentity->getAsLinkTarget()->getText() === '' ) {
			throw new MWException( 'Something is trying to edit an article with an empty title' );
		}
		// Make sure the given content type is allowed for this page
		if ( !$content->getContentHandler()->canBeUsedOn( $this->getTitle() ) ) {
			return Status::newFatal( 'content-not-allowed-here',
				ContentHandler::getLocalizedName( $content->getModel() ),
				$this->getTitle()->getPrefixedText()
			);
		}

		// Load the data from the master database if needed.
		// The caller may already loaded it from the master or even loaded it using
		// SELECT FOR UPDATE, so do not override that using clear().
		$this->load();

		$flags = $this->checkFlags( $flags );

		// Avoid PHP 7.1 warning of passing $this by reference
		$wikiPage = $this;

		// Trigger pre-save hook (using provided edit summary)
		$hookStatus = Status::newGood( [] );
		$hook_args = [ &$wikiPage, &$user, &$content, &$summary,
			$flags & EDIT_MINOR, null, null, &$flags, &$hookStatus ];
		// Check if the hook rejected the attempted save
		// FIXME: hook signature!
		if ( !Hooks::run( 'PageContentSave', $hook_args ) ) {
			if ( $hookStatus->isOK() ) {
				// Hook returned false but didn't call fatal(); use generic message
				$hookStatus->fatal( 'edit-hook-aborted' );
			}

			return $hookStatus;
		}

		$page = $this->getPage();

		$old_revision = $page->getCurrentRevision(); // current revision
		$old_content = $old_revision->getContent( 'main', RevisionRecord::RAW ); // current revision's content

		if ( $old_content && $old_content->getModel() !== $content->getModel() ) {
			$tags[] = 'mw-contentmodelchange';
		}

		// Provide autosummaries if one is not provided and autosummaries are enabled
		if ( $this->useAutomaticEditSummaries && ( $flags & EDIT_AUTOSUMMARY ) && $summary == '' ) {
			$handler = $content->getContentHandler();
			$summary = $handler->getAutosummary( $old_content, $content, $flags );
		}

		// Avoid statsd noise and wasted cycles check the edit stash (T136678)
		if ( ( $flags & EDIT_INTERNAL ) || ( $flags & EDIT_FORCE_BOT ) ) {
			$useCache = false;
		} else {
			$useCache = true;
		}

		// Get the pre-save transform content and final parser output
		$editInfo = $this->prepareContentForEdit( $content, null, $user, $useCache );
		$pstContent = $editInfo->pstContent; // Content object
		$meta = [
			'bot' => ( $flags & EDIT_FORCE_BOT ),
			'minor' => ( $flags & EDIT_MINOR ) && $user->isAllowed( 'minoredit' ),
			'baseRevId' => $baseRevId,
			'oldRevision' => $old_revision,
			'oldContent' => $old_content,
			'oldId' => $page->getLatest(),
			'oldIsRedirect' => $page->isRedirect(),
			'oldCountable' => $this->isCountable(),
			'tags' => ( $tags !== null ) ? (array)$tags : [],
			'undidRevId' => $undidRevId
		];

		// Actually create the revision and create/update the page
		if ( $flags & EDIT_UPDATE ) {
			$status = $this->doModify( $pstContent, $flags, $user, $summary, $meta );
		} else {
			$status = $this->doCreate( $pstContent, $flags, $user, $summary, $meta );
		}

		// Promote user to any groups they meet the criteria for
		DeferredUpdates::addCallableUpdate( function () use ( $user ) {
			$user->addAutopromoteOnceGroups( 'onEdit' );
			$user->addAutopromoteOnceGroups( 'onView' ); // b/c
		} );

		return $status;
	}

	/**
	 * @param Content $content Pre-save transform content
	 * @param int $flags
	 * @param User $user
	 * @param CommentStoreComment $comment
	 * @param array $meta
	 * @return Status
	 * @throws DBUnexpectedError
	 * @throws Exception
	 * @throws FatalError
	 * @throws MWException
	 */
	private function doModify(
		Content $content, $flags, User $user, CommentStoreComment $comment, array $meta
	) {
		// Update article, but only if changed.
		$status = Status::newGood( [ 'new' => false, 'revision' => null ] );

		// Convenience variables
		$now = wfTimestampNow(); // TODO: inject?!
		$oldid = $meta['oldId'];
		/** @var Content|null $oldContent */
		$oldContent = $meta['oldContent'];

		if ( !$oldid ) {
			// Article gone missing
			$status->fatal( 'edit-gone-missing' );

			return $status;
		} elseif ( !$oldContent ) {
			// Sanity check for T39225
			throw new MWException( "Could not find text for current revision {$oldid}." );
		}

		$page = $this->getPage(); // FIXME ?!
		$changed = !$content->equals( $oldContent );

		$dbw = wfGetDB( DB_MASTER );

		if ( $changed ) {
			$newRevision = MutableRevisionRecord::newFromParentRevision(
				$this->getCurrentRevision(),
				$comment,
				$user,
				$now
			);

			$newRevision->setMinorEdit( $meta['minor'] );
			$newRevision->setContent( 'main', $content ); // TODO: MCR!

			//FIXME: set content from $meta['serialized'] - or don't!

			$prepStatus = $content->prepareSave( $this->asWikiPage(), $flags, $oldid, $user );
			$status->merge( $prepStatus );
			if ( !$status->isOK() ) {
				return $status;
			}

			$dbw->startAtomic( __METHOD__ );

			// Get the latest page_latest value while locking it.
			// Do a CAS style check to see if it's the same as when this method
			// started. If it changed then bail out before touching the DB.
			$latestNow = $this->pageStore->getLatestForUpdate( $dbw, $this->pageIdentity );
			if ( $latestNow != $oldid ) {
				$dbw->endAtomic( __METHOD__ ); // XXX: don't we want somethinhg like abortAtomic here?
				// Page updated or deleted in the mean time
				$status->fatal( 'edit-conflict' );

				return $status;
			}

			// At this point we are now comitted to returning an OK
			// status unless some DB query error or other exception comes up.
			// This way callers don't have to call rollback() if $status is bad
			// unless they actually try to catch exceptions (which is rare).

			// Save revision content and meta-data
			$revisionId = $this->revisionStore->insertRevisionOn( $newRevision, $dbw );
			// Update page_latest and friends to reflect the new revision
			if ( !$this->pageStore->updatePageOn( $dbw, $newRevision, null, $meta['oldIsRedirect'] ) ) {
				throw new MWException( "Failed to update page row to use new revision." );
			}

			// FIXME: hook signature!
			Hooks::run( 'NewRevisionFromEditComplete',
				[ $this, $newRevision, $meta['baseRevId'], $user ] );

			// Update recentchanges
			if ( !( $flags & EDIT_SUPPRESS_RC ) ) {
				// Mark as patrolled if the user can do so
				$patrolled = $this->useRCPatrol && !count(
						$this->getTitle()->getUserPermissionsErrors( 'autopatrol', $user ) );
				// Add RC row to the DB
				RecentChange::notifyEdit(
					$now,
					$this->getTitle(),
					$newRevision->isMinor(),
					$user,
					$comment->text,
					$oldid,
					$newRevision->getTimestamp(),
					$meta['bot'],
					'',
					$oldContent ? $oldContent->getSize() : 0,
					$newRevision->getSize(),
					$revisionId,
					$patrolled,
					$meta['tags']
				);
			}

			$user->incEditCount();

			$dbw->endAtomic( __METHOD__ );
			$this->mTimestamp = $now;
		} else {
			// T34948: revision ID must be set to page {{REVISIONID}} and
			// related variables correctly. Likewise for {{REVISIONUSER}} (T135261).
			// Since we don't insert a new revision into the database, the least
			// error-prone way is to reuse given old revision.
			$newRevision = $meta['oldRevision'];
		}

		if ( $changed ) {
			// Return the new revision to the caller
			$status->value['revision'] = $newRevision;
		} else {
			$status->warning( 'edit-no-change' );
			// Update page_touched as updateRevisionOn() was not called.
			// Other cache updates are managed in onArticleEdit() via doEditUpdates().
			$this->getTitle()->invalidateCache( $now );
		}

		// Do secondary updates once the main changes have been committed...
		DeferredUpdates::addUpdate(
			new AtomicSectionUpdate(
				$dbw,
				__METHOD__,
				function () use (
					$newRevision, &$user, $content, $comment, &$flags,
					$changed, $meta, &$status
				) {
					// Update links tables, site stats, etc.
					$this->doEditUpdates(
						$newRevision,
						$user,
						[
							'changed' => $changed,
							'oldcountable' => $meta['oldCountable'],
							'oldrevision' => $meta['oldRevision']
						]
					);
					// Avoid PHP 7.1 warning of passing $this by reference
					$wikiPage = $this;
					// Trigger post-save hook
					// FIXME: hook signature!
					$params = [ &$wikiPage, &$user, $content, $comment->text, $flags & EDIT_MINOR,
						null, null, &$flags, $newRevision, &$status, $meta['baseRevId'],
						$meta['undidRevId'] ];
					Hooks::run( 'PageContentSaveComplete', $params );
				}
			),
			DeferredUpdates::PRESEND
		);

		return $status;
	}

	/**
	 * @param Content $content Pre-save transform content
	 * @param int $flags
	 * @param User $user
	 * @param string $summary
	 * @param array $meta
	 * @return Status
	 * @throws DBUnexpectedError
	 * @throws Exception
	 * @throws FatalError
	 * @throws MWException
	 */
	private function doCreate(
		Content $content, $flags, User $user, $summary, array $meta
	) {
		$status = Status::newGood( [ 'new' => true, 'revision' => null ] );

		$now = wfTimestampNow();
		$newsize = $content->getSize();

		$prepStatus = $content->prepareSave( $this->asWikiPage(), $flags, $meta['oldId'], $user );
		$status->merge( $prepStatus );
		if ( !$status->isOK() ) {
			return $status;
		}

		$newRevision = new MutableRevisionRecord(
			$this->pageIdentity,
			$this->wikiId
		);

		$dbw = wfGetDB( DB_MASTER );
		$dbw->startAtomic( __METHOD__ );

		// Add the page record unless one already exists for the title
		$newid = $this->revisionStore->insertRevisionOn( $newRevision, $dbw );
		if ( $newid === false ) {
			$dbw->endAtomic( __METHOD__ ); // nothing inserted
			$status->fatal( 'edit-already-exists' );

			return $status; // nothing done
		}

		// At this point we are now comitted to returning an OK
		// status unless some DB query error or other exception comes up.
		// This way callers don't have to call rollback() if $status is bad
		// unless they actually try to catch exceptions (which is rare).

		// @TODO: pass content object?!
		$revision = $this->revisionStore->newMutableRevisionFromArray( [
			'page'       => $newid,
			'comment'    => $summary,
			'minor_edit' => $meta['minor'],
			'text'       => $meta['serialized'],
			'len'        => $newsize,
			'user'       => $user->getId(),
			'user_text'  => $user->getName(),
			'timestamp'  => $now,
			'content_model' => $content->getModel(),
			'content_format' => $meta['serialFormat'],
		], 0, $this->pageIdentity );

		// Save the revision text...
		$revisionId = $this->revisionStore->insertRevisionOn( $revision, $dbw );
		// Update the page record with revision data
		if ( !$this->pageStore->updatePageOn( $dbw, $revision, 0 ) ) {
			throw new MWException( "Failed to update page row to use new revision." );
		}

		// FIXME: hook signature!
		Hooks::run( 'NewRevisionFromEditComplete', [ $this, $revision, false, $user ] );

		// Update recentchanges
		if ( !( $flags & EDIT_SUPPRESS_RC ) ) {
			// Mark as patrolled if the user can do so
			$patrolled = ( $this->useRCPatrol || $this->useNPPatrol ) &&
				!count( $this->getTitle()->getUserPermissionsErrors( 'autopatrol', $user ) );
			// Add RC row to the DB
			RecentChange::notifyNew(
				$now,
				$this->getTitle(),
				$revision->isMinor(),
				$user,
				$summary,
				$meta['bot'],
				'',
				$newsize,
				$revisionId,
				$patrolled,
				$meta['tags']
			);
		}

		$user->incEditCount();

		$dbw->endAtomic( __METHOD__ );
		$this->mTimestamp = $now;

		// Return the new revision to the caller
		$status->value['revision'] = $revision;

		// Do secondary updates once the main changes have been committed...
		DeferredUpdates::addUpdate(
			new AtomicSectionUpdate(
				$dbw,
				__METHOD__,
				function () use (
					$revision, &$user, $content, $summary, &$flags, $meta, &$status
				) {
					// Update links, etc.
					$this->doEditUpdates( $revision, $user, [ 'created' => true ] );
					// Avoid PHP 7.1 warning of passing $this by reference
					$wikiPage = $this;
					// Trigger post-create hook
					$params = [ &$wikiPage, &$user, $content, $summary,
						$flags & EDIT_MINOR, null, null, &$flags, $revision ];
					// FIXME: hook signature!
					Hooks::run( 'PageContentInsertComplete', $params );
					// Trigger post-save hook
					$params = array_merge( $params, [ &$status, $meta['baseRevId'], 0 ] );
					// FIXME: hook signature!
					Hooks::run( 'PageContentSaveComplete', $params );
				}
			),
			DeferredUpdates::PRESEND
		);

		return $status;
	}

	/**
	 * Get parser options suitable for rendering the primary article wikitext
	 *
	 * @see ContentHandler::makeParserOptions
	 *
	 * @param $slotRole
	 * @param IContextSource|User|string $context One of the following:
	 *        - IContextSource: Use the User and the Language of the provided
	 *          context
	 *        - User: Use the provided User object and $wgLang for the language,
	 *          so use an IContextSource object if possible.
	 *        - 'canonical': Canonical options (anonymous user with default
	 *          preferences and content language).
	 *
	 * @return ParserOptions
	 * @throws MWException
	 */
	private function makeParserOptions( $slotRole, $context ) {
		$options = $this->getContentHandler( $slotRole )->makeParserOptions( $context );

		if ( $this->getTitle()->isConversionTable() ) {
			// @todo ConversionTable should become a separate content model, so
			// we don't need special cases like this one.
			$options->disableContentConversion();
		}

		return $options;
	}

	/**
	 * Prepare content which is about to be saved.
	 *
	 * Prior to 1.30, this returned a stdClass object with the same class
	 * members.
	 *
	 * @param Content $content
	 * @param User $user
	 * @param RevisionRecord|null $revision RevisionRecord object.
	 * @param bool $useCache Check shared prepared edit cache
	 *
	 * @return PreparedEdit
	 *
	 * @since 1.21
	 */
	public function prepareContentForEdit(
		// FIXME: for each $slotRole!
		Content $content, User $user = null, RevisionRecord $revision = null,
		$useCache = true
	) {
		$slotRole = 'main'; // TODO: MCR!

		$revid = $revision ? $revision->getId() : 0;

		// XXX: check $user->getId() here???

		// XXX: We can dropthe serialization format here if we don't need it in the database.
		$serialFormat = $content->getContentHandler()->getDefaultFormat();

		if ( $this->preparedEdit
			&& isset( $this->preparedEdit->newContent )
			&& $this->preparedEdit->newContent->equals( $content )
			&& $this->preparedEdit->revid == $revid
			&& $this->preparedEdit->format == $serialFormat
			// XXX: also check $user here?
		) {
			// Already prepared
			return $this->preparedEdit;
		}

		// The edit may have already been prepared via api.php?action=stashedit
		$cachedEdit = $useCache && $this->ajaxEditStash
			? ApiStashEdit::checkCache( $this->getTitle(), $content, $user )
			: false;

		$popts = ParserOptions::newFromUserAndLang( $user, $this->contentLanguage );
		// FIXME: hook signature!
		Hooks::run( 'ArticlePrepareTextForEdit', [ $this, $popts ] );

		$edit = new PreparedEdit();
		if ( $cachedEdit ) {
			$edit->timestamp = $cachedEdit->timestamp;
		} else {
			$edit->timestamp = wfTimestampNow();
		}
		// @note: $cachedEdit is safely not used if the rev ID was referenced in the text
		$edit->revid = $revid;

		if ( $cachedEdit ) {
			$edit->pstContent = $cachedEdit->pstContent;
		} else {
			$edit->pstContent = $content
				? $content->preSaveTransform( $this->getTitle(), $user, $popts )
				: null;
		}

		$edit->format = $serialFormat;
		$edit->popts = $this->makeParserOptions( $slotRole, 'canonical' );
		if ( $cachedEdit ) {
			$edit->output = $cachedEdit->output;
		} else {
			if ( $revision ) {
				// We get here if vary-revision is set. This means that this page references
				// itself (such as via self-transclusion). In this case, we need to make sure
				// that any such self-references refer to the newly-saved revision, and not
				// to the previous one, which could otherwise happen due to replica DB lag.
				$oldCallback = $edit->popts->getCurrentRevisionCallback();
				$edit->popts->setCurrentRevisionCallback(
					function ( Title $title, $parser = false ) use ( $revision, &$oldCallback ) {
						if ( $title->getArticleID() === $revision->getPageId() ) {
							return $revision;
						} else {
							return call_user_func( $oldCallback, $title, $parser );
						}
					}
				);
			} else {
				// Try to avoid a second parse if {{REVISIONID}} is used
				$dbIndex = ( $this->loadedFrom & self::READ_LATEST ) === self::READ_LATEST
					? DB_MASTER // use the best possible guess
					: DB_REPLICA; // T154554

				$edit->popts->setSpeculativeRevIdCallback( function () use ( $dbIndex ) {
					return 1 + (int)wfGetDB( $dbIndex )->selectField(
						'revision',
						'MAX(rev_id)',
						[],
						__METHOD__
					);
				} );
			}
			$edit->output = $edit->pstContent
				? $edit->pstContent->getParserOutput( $this->getTitle(), $revid, $edit->popts )
				: null;
		}

		$edit->newContent = $content;
		$edit->oldContent = $this->getContentUnchecked( $slotRole );

		// NOTE: B/C for hooks! don't use these fields!
		$edit->newText = $edit->newContent
			? ContentHandler::getContentText( $edit->newContent )
			: '';
		$edit->oldText = $edit->oldContent
			? ContentHandler::getContentText( $edit->oldContent )
			: '';
		$edit->pst = $edit->pstContent ? $edit->pstContent->serialize( $serialFormat ) : '';

		if ( $edit->output ) {
			$edit->output->setCacheTime( wfTimestampNow() );
		}

		// Process cache the result
		$this->preparedEdit = $edit;

		return $edit;
	}
	/**
	 * Do standard deferred updates after page edit.
	 * Update links tables, site stats, search index and message cache.
	 * Purges pages that include this page if the text was changed here.
	 * Every 100th edit, prune the recent changes table.
	 *
	 * @param RevisionRecord $revision
	 * @param User $user User object that did the revision
	 * @param array $options Array of options, following indexes are used:
	 * - changed: bool, whether the revision changed the content (default true)
	 * - created: bool, whether the revision created the page (default false)
	 * - moved: bool, whether the page was moved (default false)
	 * - restored: bool, whether the page was undeleted (default false)
	 * - oldrevision: RevisionRecord object for the pre-update revision (default null)
	 * - oldcountable: bool, null, or string 'no-change' (default null):
	 *   - bool: whether the page was counted as an article before that
	 *     revision, only used in changed is true and created is false
	 *   - null: if created is false, don't update the article count; if created
	 *     is true, do update the article count
	 *   - 'no-change': don't update the article count, ever
	 */
	public function doEditUpdates( RevisionRecord $revision, User $user, array $options = [] ) {
		$options += [
			'changed' => true,
			'created' => false,
			'moved' => false,
			'restored' => false,
			'oldrevision' => null,
			'oldcountable' => null
		];
		$content = $revision->getContent( 'main' ); // FIXME: for each slot

		// See if the parser output before $revision was inserted is still valid
		$editInfo = false;
		if ( !$this->preparedEdit ) {
			$this->saveParseLogger->debug( __METHOD__ . ": No prepared edit...\n" );
		} elseif ( $this->preparedEdit->output->getFlag( 'vary-revision' ) ) {
			$this->saveParseLogger->info( __METHOD__ . ": Prepared edit has vary-revision...\n" );
		} elseif ( $this->preparedEdit->output->getFlag( 'vary-revision-id' )
			&& $this->preparedEdit->output->getSpeculativeRevIdUsed() !== $revision->getId()
		) {
			$this->saveParseLogger->info( __METHOD__ . ": Prepared edit has vary-revision-id with wrong ID...\n" );
		} elseif ( $this->preparedEdit->output->getFlag( 'vary-user' ) && !$options['changed'] ) {
			$this->saveParseLogger->info( __METHOD__ . ": Prepared edit has vary-user and is null...\n" );
		} else {
			wfDebug( __METHOD__ . ": Using prepared edit...\n" );
			$editInfo = $this->preparedEdit;
		}

		if ( !$editInfo ) {
			// Parse the text again if needed. Be careful not to do pre-save transform twice:
			// $text is usually already pre-save transformed once. Avoid using the edit stash
			// as any prepared content from there or in doEditContent() was already rejected.
			$editInfo = $this->prepareContentForEdit( $content, $user, $revision, false );
		}

		// Save it to the parser cache.
		// Make sure the cache time matches page_touched to avoid double parsing.
		$this->parserCache->save(
			$editInfo->output, $this->asWikiPage(), $editInfo->popts,
			$revision->getTimestamp(), $editInfo->revid
		);

		// Update the links tables and other secondary data
		if ( $content ) {
			$recursive = $options['changed']; // T52785
			$updates = $content->getSecondaryDataUpdates(
				$this->getTitle(), null, $recursive, $editInfo->output
			);
			foreach ( $updates as $update ) {
				$update->setCause( 'edit-page', $user->getName() );
				if ( $update instanceof LinksUpdate ) {
					$update->setRevision( new Revision( $revision ) );
					$update->setTriggeringUser( $user );
				}
				DeferredUpdates::addUpdate( $update );
			}
			if ( $this->rcWatchCategoryMembership
				&& $content->getContentHandler()->supportsCategories() === true
				&& ( $options['changed'] || $options['created'] )
				&& !$options['restored']
			) {
				// Note: jobs are pushed after deferred updates, so the job should be able to see
				// the recent change entry (also done via deferred updates) and carry over any
				// bot/deletion/IP flags, ect.
				$this->jobQueueGroup->lazyPush( new CategoryMembershipChangeJob(
					$this->getTitle(),
					[
						'pageId' => $this->pageIdentity->getId(),
						'revTimestamp' => $revision->getTimestamp()
					]
				) );
			}
		}

		// Avoid PHP 7.1 warning of passing $this by reference
		$wikiPage = $this;

		// FIXME: hook signature!
		Hooks::run( 'ArticleEditUpdates', [ &$wikiPage, &$editInfo, $options['changed'] ] );

		// FIXME: hook signature!
		if ( Hooks::run( 'ArticleEditUpdatesDeleteFromRecentchanges', [ &$wikiPage ] ) ) {
			// Flush old entries from the `recentchanges` table
			if ( mt_rand( 0, 9 ) == 0 ) {
				$this->jobQueueGroup->lazyPush( RecentChangesUpdateJob::newPurgeJob() );
			}
		}

		if ( !$this->exists() ) {
			return;
		}

		$id = $this->pageIdentity->getId();
		$title = $this->getTitle()->getPrefixedDBkey();
		$shortTitle = $this->getTitle()->getDBkey();

		if ( $options['oldcountable'] === 'no-change' ||
			( !$options['changed'] && !$options['moved'] )
		) {
			$good = 0;
		} elseif ( $options['created'] ) {
			$good = (int)$this->isCountable();
		} elseif ( $options['oldcountable'] !== null ) {
			$good = (int)$this->isCountable() - (int)$options['oldcountable'];
		} else {
			$good = 0;
		}
		$edits = $options['changed'] ? 1 : 0;
		$total = $options['created'] ? 1 : 0;

		DeferredUpdates::addUpdate( new SiteStatsUpdate( 0, $edits, $good, $total ) );
		DeferredUpdates::addUpdate( new SearchUpdate( $id, $title, $content ) );

		// If this is another user's talk page, update newtalk.
		// Don't do this if $options['changed'] = false (null-edits) nor if
		// it's a minor edit and the user doesn't want notifications for those.
		if ( $options['changed']
			&& $this->getTitle()->getNamespace() == NS_USER_TALK
			&& $shortTitle != $user->getTitleKey()
			&& !( $revision->isMinor() && $user->isAllowed( 'nominornewtalk' ) )
		) {
			$recipient = User::newFromName( $shortTitle, false );
			if ( !$recipient ) {
				wfDebug( __METHOD__ . ": invalid username\n" );
			} else {
				// Avoid PHP 7.1 warning of passing $this by reference
				$wikiPage = $this;

				// Allow extensions to prevent user notification
				// when a new message is added to their talk page
				// FIXME: hook signature!
				if ( Hooks::run( 'ArticleEditUpdateNewTalk', [ &$wikiPage, $recipient ] ) ) {
					if ( User::isIP( $shortTitle ) ) {
						// An anonymous user
						$recipient->setNewtalk( true, $revision );
					} elseif ( $recipient->isLoggedIn() ) {
						$recipient->setNewtalk( true, $revision );
					} else {
						wfDebug( __METHOD__ . ": don't need to notify a nonexistent user\n" );
					}
				}
			}
		}

		if ( $this->getTitle()->getNamespace() == NS_MEDIAWIKI ) {
			$this->messageCache->updateMessageOverride( $this->getTitle(), $content );
		}

		if ( $options['created'] ) {
			$this->eventEmitter->onArticleCreate( $this->pageIdentity );
		} elseif ( $options['changed'] ) { // T52785
			$this->eventEmitter->onArticleEdit( $this->pageIdentity, $revision );
		}

		ResourceLoaderWikiModule::invalidateModuleCache(
			$this->getTitle(), $options['oldrevision'], $revision, wfWikiID()
		);
	}

	/**
	 * Should the parser cache be used?
	 *
	 * @param ParserOptions $parserOptions ParserOptions to check
	 * @param int $oldId
	 * @return bool
	 */
	public function shouldCheckParserCache( ParserOptions $parserOptions, $oldId ) {
		return $parserOptions->getStubThreshold() == 0
		&& $this->exists()
		&& ( $oldId === null || $oldId === 0 || $oldId === $this->getPage()->getLatest() )
		&& $this->getContentHandler( 'main' )->isParserCacheSupported(); // FIXME: for all roles!
	}

	///////////////////////////////////////////////////////////////

	/**
	 * Insert a new null revision for this page.
	 *
	 * @param string $revCommentMsg Comment message key for the revision
	 * @param array $limit Set of restriction keys
	 * @param array $expiry Per restriction type expiration
	 * @param int $cascade Set to false if cascading protection isn't allowed.
	 * @param string $reason
	 * @param User $user
	 * @return RevisionRecord|null Null on error
	 */
	public function insertProtectNullRevision( $revCommentMsg, array $limit,
		array $expiry, $cascade, $reason, User $user
	) {
		// FIXME: pull up to application logic!
		$dbw = $this->getDBConnectionRef( DB_MASTER );

		// Prepare a null revision to be added to the history
		$editComment = $this->localizer->msg(
			$revCommentMsg,
			$this->getTitle()->getPrefixedText(),
			$user ? $user->getName() : ''
		)->inContentLanguage()->text();
		if ( $reason ) {
			$editComment .= $this->localizer->msg( 'colon-separator' )->inContentLanguage()->text() . $reason;
		}
		$protectDescription = $this->protectDescription( $limit, $expiry );
		if ( $protectDescription ) {
			$editComment .= $this->localizer->msg( 'word-separator' )->inContentLanguage()->text();
			$editComment .= $this->localizer->msg( 'parentheses' )->params( $protectDescription )
				->inContentLanguage()->text();
		}
		if ( $cascade ) {
			$editComment .= $this->localizer->msg( 'word-separator' )->inContentLanguage()->text();
			$editComment .= $this->localizer->msg( 'brackets' )->params(
				$this->localizer->msg( 'protect-summary-cascade' )->inContentLanguage()->text()
			)->inContentLanguage()->text();
		}

		$nullRev = $this->revisionStore->newNullRevision( $dbw,
			$this->pageIdentity,
			CommentStoreComment::newUnsavedComment( $editComment ),
			true,
			$user
		);
		if ( $nullRev ) {
			$this->revisionStore->insertRevisionOn( $nullRev, $dbw );

			// Update page record and touch page
			$oldLatest = $nullRev->getParentId();
			$this->pageStore->updatePageOn( $dbw, $nullRev, $oldLatest );
		}

		return $nullRev;
	}


	/**
	 * Update the article's restriction field, and leave a log entry.
	 * This works for protection both existing and non-existing pages.
	 *
	 * @param array $limit Set of restriction keys
	 * @param array $expiry Per restriction type expiration
	 * @param int &$cascade Set to false if cascading protection isn't allowed.
	 * @param string $reason
	 * @param User $user The user updating the restrictions
	 * @param string|string[] $tags Change tags to add to the pages and protection log entries
	 *   ($user should be able to add the specified tags before this is called)
	 * @return Status Status object; if action is taken, $status->value is the log_id of the
	 *   protection log entry.
	 */
	public function doUpdateRestrictions( array $limit, array $expiry,
		&$cascade, $reason, User $user, $tags = null
	) {
		// TODO: move to RestrictionStore
		global $wgCascadingRestrictionLevels; // FIXME: inject

		if ( wfReadOnly() ) { // FIXME
			return Status::newFatal( $this->localizer->msg( 'readonlytext', wfReadOnlyReason() ) );
		}

		$this->load();
		$restrictionTypes = $this->getTitle()->getRestrictionTypes();
		$id = $this->pageIdentity->getId();

		if ( !$cascade ) {
			$cascade = false;
		}

		// Take this opportunity to purge out expired restrictions
		Title::purgeExpiredRestrictions();

		// @todo FIXME: Same limitations as described in ProtectionForm.php (line 37);
		// we expect a single selection, but the schema allows otherwise.
		$isProtected = false;
		$protect = false;
		$changed = false;

		$dbw = $this->getDBConnectionRef( DB_MASTER );

		foreach ( $restrictionTypes as $action ) {
			if ( !isset( $expiry[$action] ) || $expiry[$action] === $dbw->getInfinity() ) {
				$expiry[$action] = 'infinity';
			}
			if ( !isset( $limit[$action] ) ) {
				$limit[$action] = '';
			} elseif ( $limit[$action] != '' ) {
				$protect = true;
			}

			// Get current restrictions on $action
			$current = implode( '', $this->getTitle()->getRestrictions( $action ) );
			if ( $current != '' ) {
				$isProtected = true;
			}

			if ( $limit[$action] != $current ) {
				$changed = true;
			} elseif ( $limit[$action] != '' ) {
				// Only check expiry change if the action is actually being
				// protected, since expiry does nothing on an not-protected
				// action.
				if ( $this->getTitle()->getRestrictionExpiry( $action ) != $expiry[$action] ) {
					$changed = true;
				}
			}
		}

		if ( !$changed && $protect && $this->getTitle()->areRestrictionsCascading() != $cascade ) {
			$changed = true;
		}

		// If nothing has changed, do nothing
		if ( !$changed ) {
			return Status::newGood();
		}

		if ( !$protect ) { // No protection at all means unprotection
			$revCommentMsg = 'unprotectedarticle-comment';
			$logAction = 'unprotect';
		} elseif ( $isProtected ) {
			$revCommentMsg = 'modifiedarticleprotection-comment';
			$logAction = 'modify';
		} else {
			$revCommentMsg = 'protectedarticle-comment';
			$logAction = 'protect';
		}

		$logRelationsValues = [];
		$logRelationsField = null;
		$logParamsDetails = [];

		// Null revision (used for change tag insertion)
		$nullRevision = null;

		if ( $id ) { // Protection of existing page
			// Avoid PHP 7.1 warning of passing $this by reference
			$wikiPage = $this;

			// FIXME: hook signature!
			if ( !Hooks::run( 'ArticleProtect', [ &$wikiPage, &$user, $limit, $reason ] ) ) {
				return Status::newGood();
			}

			// Only certain restrictions can cascade...
			$editrestriction = isset( $limit['edit'] )
				? [ $limit['edit'] ]
				: $this->getTitle()->getRestrictions( 'edit' );
			foreach ( array_keys( $editrestriction, 'sysop' ) as $key ) {
				$editrestriction[$key] = 'editprotected'; // backwards compatibility
			}
			foreach ( array_keys( $editrestriction, 'autoconfirmed' ) as $key ) {
				$editrestriction[$key] = 'editsemiprotected'; // backwards compatibility
			}

			$cascadingRestrictionLevels = $wgCascadingRestrictionLevels;
			foreach ( array_keys( $cascadingRestrictionLevels, 'sysop' ) as $key ) {
				$cascadingRestrictionLevels[$key] = 'editprotected'; // backwards compatibility
			}
			foreach ( array_keys( $cascadingRestrictionLevels, 'autoconfirmed' ) as $key ) {
				$cascadingRestrictionLevels[$key] = 'editsemiprotected'; // backwards compatibility
			}

			// The schema allows multiple restrictions
			if ( !array_intersect( $editrestriction, $cascadingRestrictionLevels ) ) {
				$cascade = false;
			}

			// insert null revision to identify the page protection change as edit summary
			$latest = $this->getPage()->getLatest();
			$nullRevision = $this->insertProtectNullRevision(
				$revCommentMsg,
				$limit,
				$expiry,
				$cascade,
				$reason,
				$user
			);

			if ( $nullRevision === null ) {
				return Status::newFatal( 'no-null-revision', $this->getTitle()->getPrefixedText() );
			}

			$logRelationsField = 'pr_id';

			// Update restrictions table
			foreach ( $limit as $action => $restrictions ) {
				$dbw->delete(
					'page_restrictions',
					[
						'pr_page' => $id,
						'pr_type' => $action
					],
					__METHOD__
				);
				if ( $restrictions != '' ) {
					$cascadeValue = ( $cascade && $action == 'edit' ) ? 1 : 0;
					$dbw->insert(
						'page_restrictions',
						[
							'pr_page' => $id,
							'pr_type' => $action,
							'pr_level' => $restrictions,
							'pr_cascade' => $cascadeValue,
							'pr_expiry' => $dbw->encodeExpiry( $expiry[$action] )
						],
						__METHOD__
					);
					$logRelationsValues[] = $dbw->insertId();
					$logParamsDetails[] = [
						'type' => $action,
						'level' => $restrictions,
						'expiry' => $expiry[$action],
						'cascade' => (bool)$cascadeValue,
					];
				}
			}

			// Clear out legacy restriction fields
			$dbw->update(
				'page',
				[ 'page_restrictions' => '' ],
				[ 'page_id' => $id ],
				__METHOD__
			);

			// Avoid PHP 7.1 warning of passing $this by reference
			$wikiPage = $this;

			// FIXME: hook signature!
			Hooks::run( 'NewRevisionFromEditComplete',
				[ $this, $nullRevision, $latest, $user ] );
			Hooks::run( 'ArticleProtectComplete', [ &$wikiPage, &$user, $limit, $reason ] );
		} else { // Protection of non-existing page (also known as "title protection")
			// Cascade protection is meaningless in this case
			$cascade = false;

			if ( $limit['create'] != '' ) {
				$commentFields = CommentStore::newKey( 'pt_reason' )->insert( $dbw, $reason );
				$dbw->replace( 'protected_titles',
					[ [ 'pt_namespace', 'pt_title' ] ],
					[
						'pt_namespace' => $this->getTitle()->getNamespace(),
						'pt_title' => $this->getTitle()->getDBkey(),
						'pt_create_perm' => $limit['create'],
						'pt_timestamp' => $dbw->timestamp(),
						'pt_expiry' => $dbw->encodeExpiry( $expiry['create'] ),
						'pt_user' => $user->getId(),
					] + $commentFields, __METHOD__
				);
				$logParamsDetails[] = [
					'type' => 'create',
					'level' => $limit['create'],
					'expiry' => $expiry['create'],
				];
			} else {
				$dbw->delete( 'protected_titles',
					[
						'pt_namespace' => $this->getTitle()->getNamespace(),
						'pt_title' => $this->getTitle()->getDBkey()
					], __METHOD__
				);
			}
		}

		$this->getTitle()->flushRestrictions();
		InfoAction::invalidateCache( $this->getTitle() );

		if ( $logAction == 'unprotect' ) {
			$params = [];
		} else {
			$protectDescriptionLog = $this->protectDescriptionLog( $limit, $expiry );
			$params = [
				'4::description' => $protectDescriptionLog, // parameter for IRC
				'5:bool:cascade' => $cascade,
				'details' => $logParamsDetails, // parameter for localize and api
			];
		}

		// Update the protection log
		$logEntry = new ManualLogEntry( 'protect', $logAction );
		$logEntry->setTarget( $this->getTitle() );
		$logEntry->setComment( $reason );
		$logEntry->setPerformer( $user );
		$logEntry->setParameters( $params );
		if ( !is_null( $nullRevision ) ) {
			$logEntry->setAssociatedRevId( $nullRevision->getId() );
		}
		$logEntry->setTags( $tags );
		if ( $logRelationsField !== null && count( $logRelationsValues ) ) {
			$logEntry->setRelations( [ $logRelationsField => $logRelationsValues ] );
		}
		$logId = $logEntry->insert();
		$logEntry->publish( $logId );

		return Status::newGood( $logId );
	}

	/**
	 * @param string $expiry 14-char timestamp or "infinity", or false if the input was invalid
	 * @return string
	 */
	protected function formatExpiry( $expiry ) {
		if ( $expiry != 'infinity' ) {
			return $this->localizer->msg(
				'protect-expiring',
				$this->contentLanguage->timeanddate( $expiry, false, false ),
				$this->contentLanguage->date( $expiry, false, false ),
				$this->contentLanguage->time( $expiry, false, false )
			)->inContentLanguage()->text();
		} else {
			return $this->localizer->msg( 'protect-expiry-indefinite' )
				->inContentLanguage()->text();
		}
	}

	/**
	 * Builds the description to serve as comment for the edit.
	 *
	 * @param array $limit Set of restriction keys
	 * @param array $expiry Per restriction type expiration
	 * @return string
	 */
	public function protectDescription( array $limit, array $expiry ) {
		// FIXME: pull up to application logic
		$protectDescription = '';

		foreach ( array_filter( $limit ) as $action => $restrictions ) {
			# $action is one of $wgRestrictionTypes = [ 'create', 'edit', 'move', 'upload' ].
			# All possible message keys are listed here for easier grepping:
			# * restriction-create
			# * restriction-edit
			# * restriction-move
			# * restriction-upload
			$actionText = $this->localizer->msg( 'restriction-' . $action )->inContentLanguage()->text();
			# $restrictions is one of $wgRestrictionLevels = [ '', 'autoconfirmed', 'sysop' ],
			# with '' filtered out. All possible message keys are listed below:
			# * protect-level-autoconfirmed
			# * protect-level-sysop
			$restrictionsText = $this->localizer->msg( 'protect-level-' . $restrictions )
				->inContentLanguage()->text();

			$expiryText = $this->formatExpiry( $expiry[$action] );

			if ( $protectDescription !== '' ) {
				$protectDescription .= $this->localizer->msg( 'word-separator' )->inContentLanguage()->text();
			}
			$protectDescription .= $this->localizer->msg( 'protect-summary-desc' )
				->params( $actionText, $restrictionsText, $expiryText )
				->inContentLanguage()->text();
		}

		return $protectDescription;
	}

	/**
	 * Builds the description to serve as comment for the log entry.
	 *
	 * Some bots may parse IRC lines, which are generated from log entries which contain plain
	 * protect description text. Keep them in old format to avoid breaking compatibility.
	 * TODO: Fix protection log to store structured description and format it on-the-fly.
	 *
	 * @param array $limit Set of restriction keys
	 * @param array $expiry Per restriction type expiration
	 * @return string
	 */
	public function protectDescriptionLog( array $limit, array $expiry ) {
		// FIXME: pull up to application logic
		$protectDescriptionLog = '';

		foreach ( array_filter( $limit ) as $action => $restrictions ) {
			$expiryText = $this->formatExpiry( $expiry[$action] );
			$protectDescriptionLog .= $this->contentLanguage->getDirMark() .
				"[$action=$restrictions] ($expiryText)";
		}

		return trim( $protectDescriptionLog );
	}

	///////////////////////////////////////////////////////////


	/**
	 * Same as doDeleteArticleReal(), but returns a simple boolean. This is kept around for
	 * backwards compatibility, if you care about error reporting you should use
	 * doDeleteArticleReal() instead.
	 *
	 * Deletes the article with database consistency, writes logs, purges caches
	 *
	 * @param string|Message|CommentStoreComment $reason Delete reason for deletion log
	 * @param bool $suppress Suppress all revisions and log the deletion in
	 *        the suppression log instead of the deletion log
	 * @param int $u1 Unused
	 * @param bool $u2 Unused
	 * @param array|string &$error Array of errors to append to
	 * @param User $user The deleting user
	 * @return bool True if successful
	 */
	public function doDeleteArticle(
		$reason, User $user, $suppress = false, &$error = ''
	) {
		$status = $this->doDeleteArticleReal( $reason, $suppress, $u1, $u2, $error, $user );
		return $status->isGood();
	}

	/**
	 * Back-end article deletion
	 * Deletes the article with database consistency, writes logs, purges caches
	 *
	 * @since 1.19
	 *
	 * @param string|Message|CommentStoreComment $reason Delete reason for deletion log
	 * @param bool $suppress Suppress all revisions and log the deletion in
	 *   the suppression log instead of the deletion log
	 * @param int $u1 Unused
	 * @param bool $u2 Unused
	 * @param array|string &$error Array of errors to append to
	 * @param User $user The deleting user
	 * @param array $tags Tags to apply to the deletion action
	 * @param string $logsubtype
	 * @return Status Status object; if successful, $status->value is the log_id of the
	 *   deletion log entry. If the page couldn't be deleted because it wasn't
	 *   found, $status is a non-fatal 'cannotdelete' error
	 */
	public function doDeleteArticleReal(
		$reason, User $user, $suppress = false, &$error = '',
		array $tags = [], $logsubtype = 'delete'
	) {
		$slotRole = 'main'; // TODO: MCR!

		wfDebug( __METHOD__ . "\n" );

		if ( ! $reason instanceof CommentStoreComment ) {
			$reason = CommentStoreComment::newUnsavedComment( $reason );
		}

		$status = Status::newGood();

		if ( $this->getTitle()->getDBkey() === '' ) {
			$status->error( 'cannotdelete',
				wfEscapeWikiText( $this->getTitle()->getPrefixedText() ) );
			return $status;
		}

		// Avoid PHP 7.1 warning of passing $this by reference
		$wikiPage = $this;

		// FIXME: hook signature!
		if ( !Hooks::run( 'ArticleDelete',
			[ &$wikiPage, &$user, &$reason, &$error, &$status, $suppress ]
		) ) {
			if ( $status->isOK() ) {
				// Hook aborted but didn't set a fatal status
				$status->fatal( 'delete-hook-aborted' );
			}
			return $status;
		}

		$dbw = $this->getDBConnectionRef( DB_MASTER );
		$dbw->startAtomic( __METHOD__ );
		$this->load();

		$id = $this->pageIdentity->getId();
		$latest = $this->getCurrentRevision()->getId(); // FIXME: revision may be null
		// T98706: lock the page from various other updates but avoid using
		// WikiPage::READ_LOCKING as that will carry over the FOR UPDATE to
		// the revisions queries (which also JOIN on user). Only lock the page
		// row and CAS check on page_latest to see if the trx snapshot matches.
		$lockedLatest = $this->pageStore->getLatestForUpdate( $dbw, $this->pageIdentity );
		if ( $id == 0 || $latest != $lockedLatest ) { // FIXME: make sure this is still right
			$dbw->endAtomic( __METHOD__ );
			// Page not there or trx snapshot is stale
			$status->error( 'cannotdelete',
				wfEscapeWikiText( strval( $this->pageIdentity ) ) );
			return $status;
		}

		// Given the lock above, we can be confident in the title and page ID values
		$namespace = $this->pageIdentity->getNamespace();
		$dbKey = $this->pageIdentity->getTitleDBkey();

		// At this point we are now comitted to returning an OK
		// status unless some DB query error or other exception comes up.
		// This way callers don't have to call rollback() if $status is bad
		// unless they actually try to catch exceptions (which is rare).

		// we need to remember the old content so we can use it to generate all deletion updates.
		$revision = $this->getCurrentRevision();
		try {
			$content = $this->getContentUnchecked( $slotRole ); // FIXME: slots
		} catch ( Exception $ex ) {
			wfLogWarning( __METHOD__ . ': failed to load content during deletion! '
				. $ex->getMessage() );

			$content = null;
		}

		$revCommentStore = new CommentStore( 'rev_comment' );
		$arCommentStore = new CommentStore( 'ar_comment' );

		$revQuery = $this->revisionStore->getQueryInfo();
		$bitfield = false;

		// Bitfields to further suppress the content
		if ( $suppress ) {
			$bitfield = RevisionRecord::SUPPRESSED_ALL;
			$revQuery['fields'] = array_diff( $revQuery['fields'], [ 'rev_deleted' ] );
		}

		// For now, shunt the revision data into the archive table.
		// Text is *not* removed from the text table; bulk storage
		// is left intact to avoid breaking block-compression or
		// immutable storage schemes.
		// In the future, we may keep revisions and mark them with
		// the rev_deleted field, which is reserved for this purpose.

		// Get all of the page revisions
		$res = $dbw->select(
			$revQuery['tables'],
			$revQuery['fields'],
			[ 'rev_page' => $id ],
			__METHOD__,
			'FOR UPDATE',
			$revQuery['joins']
		);

		// Build their equivalent archive rows
		$rowsInsert = [];
		$revids = [];

		/** @var int[] RevisionRecord IDs of edits that were made by IPs */
		$ipRevIds = [];

		foreach ( $res as $row ) {
			$comment = $revCommentStore->getComment( $row );
			$rowInsert = [
					'ar_namespace'  => $namespace,
					'ar_title'      => $dbKey,
					'ar_user'       => $row->rev_user,
					'ar_user_text'  => $row->rev_user_text,
					'ar_timestamp'  => $row->rev_timestamp,
					'ar_minor_edit' => $row->rev_minor_edit,
					'ar_rev_id'     => $row->rev_id,
					'ar_parent_id'  => $row->rev_parent_id,
					'ar_text_id'    => $row->rev_text_id,
					'ar_text'       => '',
					'ar_flags'      => '',
					'ar_len'        => $row->rev_len,
					'ar_page_id'    => $id,
					'ar_deleted'    => $suppress ? $bitfield : $row->rev_deleted,
					'ar_sha1'       => $row->rev_sha1,
				] + $arCommentStore->insert( $dbw, $comment );
			if ( $this->contentHandlerUseDB ) {
				$rowInsert['ar_content_model'] = $row->rev_content_model;
				$rowInsert['ar_content_format'] = $row->rev_content_format;
			}
			$rowsInsert[] = $rowInsert;
			$revids[] = $row->rev_id;

			// Keep track of IP edits, so that the corresponding rows can
			// be deleted in the ip_changes table.
			if ( (int)$row->rev_user === 0 && IP::isValid( $row->rev_user_text ) ) {
				$ipRevIds[] = $row->rev_id;
			}
		}
		// Copy them into the archive table
		$dbw->insert( 'archive', $rowsInsert, __METHOD__ );
		// Save this so we can pass it to the ArticleDeleteComplete hook.
		$archivedRevisionCount = $dbw->affectedRows();

		// Clone the title and wikiPage, so we have the information we need when
		// we log and run the ArticleDeleteComplete hook.
		$logTitle = clone $this->getTitle();
		$wikiPageBeforeDelete = clone $this;

		// Now that it's safely backed up, delete it
		$dbw->delete( 'page', [ 'page_id' => $id ], __METHOD__ );
		$dbw->delete( 'revision', [ 'rev_page' => $id ], __METHOD__ );
		if ( $this->commentTableSchemaMigrationStage > MIGRATION_OLD ) {
			$dbw->delete( 'revision_comment_temp', [ 'revcomment_rev' => $revids ], __METHOD__ );
		}

		// Also delete records from ip_changes as applicable.
		if ( count( $ipRevIds ) > 0 ) {
			$dbw->delete( 'ip_changes', [ 'ipc_rev_id' => $ipRevIds ], __METHOD__ );
		}

		// Log the deletion, if the page was suppressed, put it in the suppression log instead
		$logtype = $suppress ? 'suppress' : 'delete';

		$logEntry = new ManualLogEntry( $logtype, $logsubtype );
		$logEntry->setPerformer( $user );
		$logEntry->setTarget( $logTitle );
		$logEntry->setComment( $reason );
		$logEntry->setTags( $tags );
		$logid = $logEntry->insert();

		$dbw->onTransactionPreCommitOrIdle(
			function () use ( $dbw, $logEntry, $logid ) {
				// T58776: avoid deadlocks (especially from FileDeleteForm)
				$logEntry->publish( $logid );
			},
			__METHOD__
		);

		$dbw->endAtomic( __METHOD__ );

		$this->eventEmitter->doDeleteUpdates( $this->getPage(), $content, $revision, $user );

		// FIXME: hook signature!
		Hooks::run( 'ArticleDeleteComplete', [
			&$wikiPageBeforeDelete,
			&$user,
			$reason,
			$id,
			$content,
			$logEntry,
			$archivedRevisionCount
		] );
		$status->value = $logid;

		// Show log excerpt on 404 pages rather than just a link
		$key = $this->stash->makeKey( 'page-recent-delete', md5( $logTitle->getPrefixedText() ) );
		$this->stash->set( $key, 1, BagOStuff::TTL_DAY );

		return $status;
	}

	//////////////////////////////////////////////////////////////////////////
	/**
	 * Roll back the most recent consecutive set of edits to a page
	 * from the same user; fails if there are no eligible edits to
	 * roll back to, e.g. user is the sole contributor. This function
	 * performs permissions checks on $user, then calls commitRollback()
	 * to do the dirty work
	 *
	 * @todo Separate the business/permission stuff out from backend code
	 * @todo Remove $token parameter. Already verified by RollbackAction and ApiRollback.
	 *
	 * @param string $fromP Name of the user whose edits to rollback.
	 * @param string $summary Custom summary. Set to default summary if empty.
	 * @param string $token Rollback token.
	 * @param bool $bot If true, mark all reverted edits as bot.
	 *
	 * @param array &$resultDetails Array contains result-specific array of additional values
	 *    'alreadyrolled' : 'current' (rev)
	 *    success        : 'summary' (str), 'current' (rev), 'target' (rev)
	 *
	 * @param User $user The user performing the rollback
	 * @param array|null $tags Change tags to apply to the rollback
	 * Callers are responsible for permission checks
	 * (with ChangeTags::canAddTagsAccompanyingChange)
	 *
	 * @return array Array of errors, each error formatted as
	 *   array(messagekey, param1, param2, ...).
	 * On success, the array is empty.  This array can also be passed to
	 * OutputPage::showPermissionsErrorPage().
	 */
	public function doRollback(
		$fromP, $summary, $token, $bot, &$resultDetails, User $user, $tags = null
	) {
		$resultDetails = null;

		// Check permissions
		$editErrors = $this->getTitle()->getUserPermissionsErrors( 'edit', $user );
		$rollbackErrors = $this->getTitle()->getUserPermissionsErrors( 'rollback', $user );
		$errors = array_merge( $editErrors, wfArrayDiff2( $rollbackErrors, $editErrors ) );

		if ( !$user->matchEditToken( $token, 'rollback' ) ) {
			$errors[] = [ 'sessionfailure' ];
		}

		if ( $user->pingLimiter( 'rollback' ) || $user->pingLimiter() ) {
			$errors[] = [ 'actionthrottledtext' ];
		}

		// If there were errors, bail out now
		if ( !empty( $errors ) ) {
			return $errors;
		}

		return $this->commitRollback( $fromP, $summary, $bot, $resultDetails, $user, $tags );
	}


	/**
	 * Backend implementation of doRollback(), please refer there for parameter
	 * and return value documentation
	 *
	 * NOTE: This function does NOT check ANY permissions, it just commits the
	 * rollback to the DB. Therefore, you should only call this function direct-
	 * ly if you want to use custom permissions checks. If you don't, use
	 * doRollback() instead.
	 * @param string $fromP Name of the user whose edits to rollback.
	 * @param string $summary Custom summary. Set to default summary if empty.
	 * @param bool $bot If true, mark all reverted edits as bot.
	 *
	 * @param array &$resultDetails Contains result-specific array of additional values
	 * @param User $guser The user performing the rollback
	 * @param array $tags Change tags to apply to the rollback
	 * Callers are responsible for permission checks
	 * (with ChangeTags::canAddTagsAccompanyingChange)
	 *
	 * @return array
	 */
	public function commitRollback( $fromP, $summary, $bot,
		&$resultDetails, User $guser, array $tags = []
	) {
		$dbw = $this->getDBConnectionRef( DB_MASTER );

		$slotRole = 'main'; // FIXME: MCR!

		if ( wfReadOnly() ) { // TODO: inject!
			return [ [ 'readonlytext' ] ];
		}

		// Get the last editor
		$current = $this->getPage()->getCurrentRevision();
		if ( is_null( $current ) ) {
			// Something wrong... no page?
			return [ [ 'notanarticle' ] ];
		}

		$from = str_replace( '_', ' ', $fromP );
		$user = $current->getUser( RevisionRecord::RAW );

		// User name given should match up with the top revision.
		// If the user was deleted then $from should be empty.
		if ( $from != $user->getName() ) {
			$resultDetails = [ 'current' => $current ];
			return [ [ 'alreadyrolled',
				htmlspecialchars( $this->getTitle()->getPrefixedText() ),
				htmlspecialchars( $fromP ),
				htmlspecialchars( $user->getName() )
			] ];
		}

		// Get the last edit not by this person...
		// Note: these may not be public values
		$s = $dbw->selectRow( 'revision',
			[ 'rev_id', 'rev_timestamp', 'rev_deleted' ],
			[ 'rev_page' => $current->getPageId(),
				"rev_user != {$user->getId()} OR rev_user_text != {$user->getName()}"
			], __METHOD__,
			[ 'USE INDEX' => 'page_timestamp',
			  'ORDER BY' => 'rev_timestamp DESC' ]
		);
		if ( $s === false ) {
			// No one else ever edited this page
			return [ [ 'cantrollback' ] ];
		} elseif ( $s->rev_deleted & RevisionRecord::DELETED_TEXT
			|| $s->rev_deleted & RevisionRecord::DELETED_USER
		) {
			// Only admins can see this text
			return [ [ 'notvisiblerev' ] ];
		}

		// Generate the edit summary if necessary
		$target = $this->revisionStore->getRevisionById( $s->rev_id, RevisionStore::READ_LATEST );
		if ( empty( $summary ) ) {
			if ( $from == '' ) { // no public user name
				$summary = $this->localizer->msg( 'revertpage-nouser' );
			} else {
				$summary = $this->localizer->msg( 'revertpage' );
			}
		}

		// Allow the custom summary to use the same args as the default message
		$args = [
			$target->getUser( RevisionRecord::RAW )->getName(), $from, $s->rev_id,
			$this->contentLanguage->timeanddate( wfTimestamp( TS_MW, $s->rev_timestamp ) ),
			$current->getId(), $this->contentLanguage->timeanddate( $current->getTimestamp() )
		];
		if ( $summary instanceof Message ) {
			$summary = $summary->params( $args )->inContentLanguage()->text();
		} else {
			$summary = wfMsgReplaceArgs( $summary, $args );
		}

		// Trim spaces on user supplied text
		$summary = trim( $summary );

		// Save
		$flags = EDIT_UPDATE | EDIT_INTERNAL;

		if ( $guser->isAllowed( 'minoredit' ) ) {
			$flags |= EDIT_MINOR;
		}

		if ( $bot && ( $guser->isAllowedAny( 'markbotedits', 'bot' ) ) ) {
			$flags |= EDIT_FORCE_BOT;
		}

		$targetContent = $target->getAllContent( RevisionRecord::RAW ); // FIXME
		$targetModel = $target->getSlot( $slotRole )->getModel();
		$currentModel = $current->getSlot( $slotRole )->getModel();
		$changingContentModel = $targetModel !== $currentModel;

		// Actually store the edit
		$status = $this->doEditContent(
			$targetContent,
			$summary,
			$guser,
			$flags,
			$target->getId(),
			$tags
		);

		// Set patrolling and bot flag on the edits, which gets rollbacked.
		// This is done even on edit failure to have patrolling in that case (T64157).
		$set = [];
		if ( $bot && $guser->isAllowed( 'markbotedits' ) ) {
			// Mark all reverted edits as bot
			$set['rc_bot'] = 1;
		}

		if ( $this->useRCPatrol ) {
			// Mark all reverted edits as patrolled
			$set['rc_patrolled'] = 1;
		}

		if ( count( $set ) ) {
			$dbw->update( 'recentchanges', $set,
				[ /* WHERE */
					'rc_cur_id' => $current->getPageId(),
					'rc_user_text' => $user->getName(),
					'rc_timestamp > ' . $dbw->addQuotes( $s->rev_timestamp ),
				],
				__METHOD__
			);
		}

		if ( !$status->isOK() ) {
			return $status->getErrorsArray();
		}

		// raise error, when the edit is an edit without a new version
		$statusRev = isset( $status->value['revision'] )
			? $status->value['revision']
			: null;
		if ( !( $statusRev instanceof RevisionRecord ) ) {
			$resultDetails = [ 'current' => $current ];
			return [ [ 'alreadyrolled',
				htmlspecialchars( $this->getTitle()->getPrefixedText() ),
				htmlspecialchars( $fromP ),
				htmlspecialchars( $user->getName() )
			] ];
		}

		if ( $changingContentModel ) {
			// If the content model changed during the rollback,
			// make sure it gets logged to Special:Log/contentmodel
			$log = new ManualLogEntry( 'contentmodel', 'change' );
			$log->setPerformer( $guser );
			$log->setTarget( $this->getTitle() );
			$log->setComment( $summary );
			$log->setParameters( [
				'4::oldmodel' => $currentModel,
				'5::newmodel' => $targetModel,
			] );

			$logId = $log->insert( $dbw );
			$log->publish( $logId );
		}

		$revId = $statusRev->getId();

		// FIXME: hook signature!
		Hooks::run( 'ArticleRollbackComplete', [ $this, $guser, $target, $current ] );

		$resultDetails = [
			'summary' => $summary,
			'current' => $current,
			'target' => $target,
			'newid' => $revId
		];

		return [];
	}


	/**
	 * Update all the appropriate counts in the category table, given that
	 * we've added the categories $added and deleted the categories $deleted.
	 *
	 * This should only be called from deferred updates or jobs to avoid contention.
	 *
	 * @param PageIdentity $page
	 * @param array $added The names of categories that were added
	 * @param array $deleted The names of categories that were deleted
	 */
	public function updateCategoryCounts( PageIdentity $page, array $added, array $deleted ) {
		$id = $page->getId();
		$ns = $page->getNamespace();

		$addFields = [ 'cat_pages = cat_pages + 1' ];
		$removeFields = [ 'cat_pages = cat_pages - 1' ];
		if ( $ns == NS_CATEGORY ) {
			$addFields[] = 'cat_subcats = cat_subcats + 1';
			$removeFields[] = 'cat_subcats = cat_subcats - 1';
		} elseif ( $ns == NS_FILE ) {
			$addFields[] = 'cat_files = cat_files + 1';
			$removeFields[] = 'cat_files = cat_files - 1';
		}

		$dbw = $this->getDBConnectionRef( DB_MASTER );

		if ( count( $added ) ) {
			$existingAdded = $dbw->selectFieldValues(
				'category',
				'cat_title',
				[ 'cat_title' => $added ],
				__METHOD__
			);

			// For category rows that already exist, do a plain
			// UPDATE instead of INSERT...ON DUPLICATE KEY UPDATE
			// to avoid creating gaps in the cat_id sequence.
			if ( count( $existingAdded ) ) {
				$dbw->update(
					'category',
					$addFields,
					[ 'cat_title' => $existingAdded ],
					__METHOD__
				);
			}

			$missingAdded = array_diff( $added, $existingAdded );
			if ( count( $missingAdded ) ) {
				$insertRows = [];
				foreach ( $missingAdded as $cat ) {
					$insertRows[] = [
						'cat_title'   => $cat,
						'cat_pages'   => 1,
						'cat_subcats' => ( $ns == NS_CATEGORY ) ? 1 : 0,
						'cat_files'   => ( $ns == NS_FILE ) ? 1 : 0,
					];
				}
				$dbw->upsert(
					'category',
					$insertRows,
					[ 'cat_title' ],
					$addFields,
					__METHOD__
				);
			}
		}

		if ( count( $deleted ) ) {
			$dbw->update(
				'category',
				$removeFields,
				[ 'cat_title' => $deleted ],
				__METHOD__
			);
		}

		foreach ( $added as $catName ) {
			$cat = Category::newFromName( $catName );
			// FIXME: hook signature!
			Hooks::run( 'CategoryAfterPageAdded', [ $cat, $this ] );
		}

		foreach ( $deleted as $catName ) {
			$cat = Category::newFromName( $catName );
			// FIXME: hook signature!
			Hooks::run( 'CategoryAfterPageRemoved', [ $cat, $this, $id ] );
		}

		// Refresh counts on categories that should be empty now, to
		// trigger possible deletion. Check master for the most
		// up-to-date cat_pages.
		if ( count( $deleted ) ) {
			$rows = $dbw->select(
				'category',
				[ 'cat_id', 'cat_title', 'cat_pages', 'cat_subcats', 'cat_files' ],
				[ 'cat_title' => $deleted, 'cat_pages <= 0' ],
				__METHOD__
			);
			foreach ( $rows as $row ) {
				$cat = Category::newFromRow( $row );
				// T166757: do the update after this DB commit
				DeferredUpdates::addCallableUpdate( function () use ( $cat ) {
					$cat->refreshCounts();
				} );
			}
		}
	}

	/**
	 * @param int $mode DB_MASTER or DB_REPLICA
	 *
	 * @return DBConnRef
	 */
	private function getDBConnectionRef( $mode ) {
		return $this->loadBalancer->getConnectionRef( $mode, [], $this->wikiId );
	}

	/**
	 * @deprecated Use of this method indicates code that should be ported from using WikiPage to
	 *   using PageRecord.
	 *
	 * @return WikiPage
	 */
	private function asWikiPage() {
		if ( !$this->wikiPage ) {
			$this->wikiPage = WikiPage::newFromPageIdentity( $this->pageIdentity );
		}
		return $this->wikiPage;
	}

	/**
	 * @deprecated Use of this method indicates code that should be ported from using WikiPage to
	 *   using PageRecord, PageIdentity or LinkTarget.
	 *
	 * @return Title
	 */
	private function getTitle() {
		if ( !$this->title ) {
			$this->title = Title::newFromPageIdentity( $this->pageIdentity );
		}
		return $this->title;
	}

	/**
	 * @param string $role
	 *
	 * @return ContentHandler
	 */
	private function getContentHandler( $role ) {
		if ( !$this->pageIdentity->exists() ) {
			return ContentHandler::getDefaultModelFor( $this->getTitle() );
		}

		$revision = $this->getCurrentRevision();
		$slot = $revision->getSlot( $role );

		return ContentHandler::getForModelID( $slot->getModel() );
	}

	/**
	 * @param string $role
	 *
	 * @return Content|null
	 */
	private function getContentUnchecked( $role ) {
		if ( !$this->pageIdentity->exists() ) {
			return null;
		}

		$revision = $this->getCurrentRevision();
		return $revision->getContent( $role, RevisionRecord::RAW );
	}

	/**
	 * @param int $from
	 */
	public function load( $from = self::READ_LATEST ) {
		if ( $from <= $this->loadedFrom ) {
			return;
		}

		if ( $this->pageIdentity->exists() ) {
			if ( $this->pageIdentity instanceof PageRecord && $from <= self::READ_NORMAL ) {
				// we already have a PageRecord, that's good enough for now
				// XXX: does this ever happen?
				$this->pageRecord = $this->pageIdentity;
			} else {
				$this->pageRecord = $this->pageStore->loadPageRecordFromID(
					$this->pageIdentity->getId(),
					$from
				);
			}

			$this->loadedFrom = $from;

			if ( $this->pageRecord ) {
				// FIXME: check wiki ID!

				// force loading of all meta-data, but not content blobs
				$revision = $this->pageRecord->getCurrentRevision();
				$revision->getSlotRoles(); // XXX: do we need RevisionRecord::load()?
			}
		}
	}

	/**
	 * @param int $from
	 * @return PageRecord|null
	 */
	private function getPage( $from = self::READ_LATEST ) {
		$this->load( $from );

		if ( !$this->pageRecord ) {
			throw new RuntimeException( 'Page does not exist or could not be loaded! '
				. $this->pageIdentity
			);
		}

		return $this->pageRecord;
	}

	/**
	 * @param int $from
	 * @return RevisionStoreRecord|null
	 */
	private function getCurrentRevision( $from = self::READ_LATEST ) {
		$this->getPage( $from )->getCurrentRevision();
	}

	private function exists() {
		return $this->pageRecord ? $this->pageRecord->exists() : $this->pageIdentity->exists();
	}

}
