<?php

namespace MediaWiki\Storage;

use DeferredUpdates;
use InvalidArgumentException;
use MediaWiki\Edit\PreparedEdit;
use MediaWiki\Linker\LinkTarget;
use MWNamespace;
use ParserCache;
use ParserOptions;
use ParserOutput;
use PoolWorkArticleView;
use RepoGroup;
use Wikimedia\Rdbms\IDatabase;

/**
 * FIXME: header
 * FIXME: document!
 */
class PageUpdater {

	// TODO: make subclasses for:
	// - null revision (with custom message, convenience for protection, maybe move, etc)
	// - regular edit (or creation?)
	// - deletion
	// - rollback???

	/** @var PreparedEdit Map of cache fields (text, parser output, ect) for a proposed/new edit */
	public $preparedEdit = null;

	/**
	 * @var PageRecord
	 */
	private $page;

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
	 * Determine whether a page would be suitable for being counted as an
	 * article in the site_stats table based on the title & its content
	 *
	 * @param PreparedEdit|null $editInfo: object returned by prepareTextForEdit(),
	 *   if null, the current database state will be used.
	 * @return bool
	 */
	private function isCountable() {

		if ( $this->page->isRedirect() || !MWNamespace::isContent( $this->page->getNamespace() ) ) {
			return false;
		}

		if ( !$this->preparedEdit ) {
			return $this->eventEmitter->isCountable( $this->page );
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
	 * Get a ParserOutput for the given ParserOptions and revision ID.
	 *
	 * The parser cache will be used if possible. Cache misses that result
	 * in parser runs are debounced with PoolCounter.
	 *
	 * @since 1.19
	 * @param ParserOptions $parserOptions ParserOptions to use for the parse operation
	 * @param null|int $oldid Revision ID to get the text from, passing null or 0 will
	 *   get the current revision (default value)
	 * @param bool $forceParse Force reindexing, regardless of cache settings
	 * @return bool|ParserOutput ParserOutput or false if the revision was not found
	 */
	public function getParserOutput(
		ParserOptions $parserOptions, $oldid = null, $forceParse = false
	) {
		$useParserCache =
			( !$forceParse ) && $this->shouldCheckParserCache( $parserOptions, $oldid );

		if ( $useParserCache && !$parserOptions->isSafeToCache() ) {
			throw new InvalidArgumentException(
				'The supplied ParserOptions are not safe to cache. Fix the options or set $forceParse = true.'
			);
		}

		wfDebug( __METHOD__ .
			': using parser cache: ' . ( $useParserCache ? 'yes' : 'no' ) . "\n" );
		if ( $parserOptions->getStubThreshold() ) {
			wfIncrStats( 'pcache.miss.stub' );
		}

		if ( $useParserCache ) {
			$parserOutput = $this->parserCache->get( $this, $parserOptions );
			if ( $parserOutput ) {
				return $parserOutput;
			}
		}

		if ( $oldid === null || $oldid === 0 ) {
			$oldid = $this->page->getLatest();
		}

		$pool = new PoolWorkArticleView( $wikiPage, $parserOptions, $oldid, $useParserCache );
		$pool->execute();

		return $pool->getParserOutput();
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

		if ( $this->page->getNamespace() == NS_FILE ) {
			$this->repoGroup->getLocalRepo()->invalidateImageRedirect( $this->page->getTitle() );
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
	 * @param PageRecord $page
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
	 * @param string|int|null|bool $sectionId Section identifier as a number or string
	 * (e.g. 0, 1 or 'T-1'), null/false or an empty string for the whole page
	 * or 'new' for a new section.
	 * @param Content $sectionContent New content of the section.
	 * @param string $sectionTitle New section's subject, only if $section is "new".
	 * @param string $edittime Revision timestamp or null to use the current revision.
	 *
	 * @throws MWException
	 * @return Content|null New complete article content, or null if error.
	 *
	 * @since 1.21
	 * @deprecated since 1.24, use replaceSectionAtRev instead
	 */
	public function replaceSectionContent(
		$sectionId, Content $sectionContent, $sectionTitle = '', $edittime = null
	) {
		$baseRevId = null;
		if ( $edittime && $sectionId !== 'new' ) {
			$dbr = wfGetDB( DB_REPLICA );
			$rev = Revision::loadFromTimestamp( $dbr, $this->mTitle, $edittime );
			// Try the master if this thread may have just added it.
			// This could be abstracted into a Revision method, but we don't want
			// to encourage loading of revisions by timestamp.
			if ( !$rev
				&& wfGetLB()->getServerCount() > 1
				&& wfGetLB()->hasOrMadeRecentMasterChanges()
			) {
				$dbw = wfGetDB( DB_MASTER );
				$rev = Revision::loadFromTimestamp( $dbw, $this->mTitle, $edittime );
			}
			if ( $rev ) {
				$baseRevId = $rev->getId();
			}
		}

		return $this->replaceSectionAtRev( $sectionId, $sectionContent, $sectionTitle, $baseRevId );
	}

	/**
	 * @param string|int|null|bool $sectionId Section identifier as a number or string
	 * (e.g. 0, 1 or 'T-1'), null/false or an empty string for the whole page
	 * or 'new' for a new section.
	 * @param Content $sectionContent New content of the section.
	 * @param string $sectionTitle New section's subject, only if $section is "new".
	 * @param int|null $baseRevId
	 *
	 * @throws MWException
	 * @return Content|null New complete article content, or null if error.
	 *
	 * @since 1.24
	 */
	public function replaceSectionAtRev( $sectionId, Content $sectionContent,
		$sectionTitle = '', $baseRevId = null
	) {
		if ( strval( $sectionId ) === '' ) {
			// Whole-page edit; let the whole text through
			$newContent = $sectionContent;
		} else {
			if ( !$this->supportsSections() ) {
				throw new MWException( "sections not supported for content model " .
					$this->page->getContentHandler()->getModelID() );
			}

			// T32711: always use current version when adding a new section
			if ( is_null( $baseRevId ) || $sectionId === 'new' ) {
				$oldContent = $this->page->getContent();
			} else {
				$rev = Revision::newFromId( $baseRevId );
				if ( !$rev ) {
					wfDebug( __METHOD__ . " asked for bogus section (page: " .
						$this->page->getId() . "; section: $sectionId)\n" );
					return null;
				}

				$oldContent = $rev->getContent();
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
			if ( $this->exists() ) {
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
	 * @param Content $content New content
	 * @param string $summary Edit summary
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
	 * @param User $user The user doing the edit
	 * @param string $serialFormat Format for storing the content in the
	 *   database.
	 * @param array|null $tags Change tags to apply to this edit
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
		Content $content, $summary, $flags = 0, $baseRevId = false,
		User $user = null, $serialFormat = null, $tags = [], $undidRevId = 0
	) {
		global $wgUser, $wgUseAutomaticEditSummaries;

		// Old default parameter for $tags was null
		if ( $tags === null ) {
			$tags = [];
		}

		// Low-level sanity check
		if ( $this->mTitle->getText() === '' ) {
			throw new MWException( 'Something is trying to edit an article with an empty title' );
		}
		// Make sure the given content type is allowed for this page
		if ( !$content->getContentHandler()->canBeUsedOn( $this->mTitle ) ) {
			return Status::newFatal( 'content-not-allowed-here',
				ContentHandler::getLocalizedName( $content->getModel() ),
				$this->mTitle->getPrefixedText()
			);
		}

		// Load the data from the master database if needed.
		// The caller may already loaded it from the master or even loaded it using
		// SELECT FOR UPDATE, so do not override that using clear().
		$this->loadPageData( 'fromdbmaster' );

		$user = $user ?: $wgUser;
		$flags = $this->checkFlags( $flags );

		// Avoid PHP 7.1 warning of passing $this by reference
		$wikiPage = $this;

		// Trigger pre-save hook (using provided edit summary)
		$hookStatus = Status::newGood( [] );
		$hook_args = [ &$wikiPage, &$user, &$content, &$summary,
			$flags & EDIT_MINOR, null, null, &$flags, &$hookStatus ];
		// Check if the hook rejected the attempted save
		if ( !Hooks::run( 'PageContentSave', $hook_args ) ) {
			if ( $hookStatus->isOK() ) {
				// Hook returned false but didn't call fatal(); use generic message
				$hookStatus->fatal( 'edit-hook-aborted' );
			}

			return $hookStatus;
		}

		$old_revision = $this->page->getRevision(); // current revision
		$old_content = $this->page->getContent( Revision::RAW ); // current revision's content

		if ( $old_content && $old_content->getModel() !== $content->getModel() ) {
			$tags[] = 'mw-contentmodelchange';
		}

		// Provide autosummaries if one is not provided and autosummaries are enabled
		if ( $wgUseAutomaticEditSummaries && ( $flags & EDIT_AUTOSUMMARY ) && $summary == '' ) {
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
		$editInfo = $this->prepareContentForEdit( $content, null, $user, $serialFormat, $useCache );
		$pstContent = $editInfo->pstContent; // Content object
		$meta = [
			'bot' => ( $flags & EDIT_FORCE_BOT ),
			'minor' => ( $flags & EDIT_MINOR ) && $user->isAllowed( 'minoredit' ),
			'serialized' => $pstContent->serialize( $serialFormat ),
			'serialFormat' => $serialFormat,
			'baseRevId' => $baseRevId,
			'oldRevision' => $old_revision,
			'oldContent' => $old_content,
			'oldId' => $this->page->getLatest(),
			'oldIsRedirect' => $this->isRedirect(),
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
	 * @param string $summary
	 * @param array $meta
	 * @return Status
	 * @throws DBUnexpectedError
	 * @throws Exception
	 * @throws FatalError
	 * @throws MWException
	 */
	private function doModify(
		Content $content, $flags, User $user, $summary, array $meta
	) {
		global $wgUseRCPatrol;

		// Update article, but only if changed.
		$status = Status::newGood( [ 'new' => false, 'revision' => null ] );

		// Convenience variables
		$now = wfTimestampNow();
		$oldid = $meta['oldId'];
		/** @var Content|null $oldContent */
		$oldContent = $meta['oldContent'];
		$newsize = $content->getSize();

		if ( !$oldid ) {
			// Article gone missing
			$status->fatal( 'edit-gone-missing' );

			return $status;
		} elseif ( !$oldContent ) {
			// Sanity check for T39225
			throw new MWException( "Could not find text for current revision {$oldid}." );
		}

		$changed = !$content->equals( $oldContent );

		$dbw = wfGetDB( DB_MASTER );

		if ( $changed ) {
			// @TODO: pass content object?!
			$revision = new Revision( [
				'page'       => $this->page->getId(),
				'title'      => $this->mTitle, // for determining the default content model
				'comment'    => $summary,
				'minor_edit' => $meta['minor'],
				'text'       => $meta['serialized'],
				'len'        => $newsize,
				'parent_id'  => $oldid,
				'user'       => $user->getId(),
				'user_text'  => $user->getName(),
				'timestamp'  => $now,
				'content_model' => $content->getModel(),
				'content_format' => $meta['serialFormat'],
			] );

			$prepStatus = $content->prepareSave( $this, $flags, $oldid, $user );
			$status->merge( $prepStatus );
			if ( !$status->isOK() ) {
				return $status;
			}

			$dbw->startAtomic( __METHOD__ );
			// Get the latest page_latest value while locking it.
			// Do a CAS style check to see if it's the same as when this method
			// started. If it changed then bail out before touching the DB.
			$latestNow = $this->lockAndGetLatest();
			if ( $latestNow != $oldid ) {
				$dbw->endAtomic( __METHOD__ );
				// Page updated or deleted in the mean time
				$status->fatal( 'edit-conflict' );

				return $status;
			}

			// At this point we are now comitted to returning an OK
			// status unless some DB query error or other exception comes up.
			// This way callers don't have to call rollback() if $status is bad
			// unless they actually try to catch exceptions (which is rare).

			// Save the revision text
			$revisionId = $revision->insertOn( $dbw );
			// Update page_latest and friends to reflect the new revision
			if ( !$this->updateRevisionOn( $dbw, $revision, null, $meta['oldIsRedirect'] ) ) {
				throw new MWException( "Failed to update page row to use new revision." );
			}

			Hooks::run( 'NewRevisionFromEditComplete',
				[ $this, $revision, $meta['baseRevId'], $user ] );

			// Update recentchanges
			if ( !( $flags & EDIT_SUPPRESS_RC ) ) {
				// Mark as patrolled if the user can do so
				$patrolled = $wgUseRCPatrol && !count(
						$this->mTitle->getUserPermissionsErrors( 'autopatrol', $user ) );
				// Add RC row to the DB
				RecentChange::notifyEdit(
					$now,
					$this->mTitle,
					$revision->isMinor(),
					$user,
					$summary,
					$oldid,
					$this->page->getTimestamp(),
					$meta['bot'],
					'',
					$oldContent ? $oldContent->getSize() : 0,
					$newsize,
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
			$revision = $meta['oldRevision'];
		}

		if ( $changed ) {
			// Return the new revision to the caller
			$status->value['revision'] = $revision;
		} else {
			$status->warning( 'edit-no-change' );
			// Update page_touched as updateRevisionOn() was not called.
			// Other cache updates are managed in onArticleEdit() via doEditUpdates().
			$this->mTitle->invalidateCache( $now );
		}

		// Do secondary updates once the main changes have been committed...
		DeferredUpdates::addUpdate(
			new AtomicSectionUpdate(
				$dbw,
				__METHOD__,
				function () use (
					$revision, &$user, $content, $summary, &$flags,
					$changed, $meta, &$status
				) {
					// Update links tables, site stats, etc.
					$this->doEditUpdates(
						$revision,
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
					$params = [ &$wikiPage, &$user, $content, $summary, $flags & EDIT_MINOR,
						null, null, &$flags, $revision, &$status, $meta['baseRevId'],
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
		global $wgUseRCPatrol, $wgUseNPPatrol;

		$status = Status::newGood( [ 'new' => true, 'revision' => null ] );

		$now = wfTimestampNow();
		$newsize = $content->getSize();
		$prepStatus = $content->prepareSave( $this, $flags, $meta['oldId'], $user );
		$status->merge( $prepStatus );
		if ( !$status->isOK() ) {
			return $status;
		}

		$dbw = wfGetDB( DB_MASTER );
		$dbw->startAtomic( __METHOD__ );

		// Add the page record unless one already exists for the title
		$newid = $this->insertOn( $dbw );
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
		$revision = new Revision( [
			'page'       => $newid,
			'title'      => $this->mTitle, // for determining the default content model
			'comment'    => $summary,
			'minor_edit' => $meta['minor'],
			'text'       => $meta['serialized'],
			'len'        => $newsize,
			'user'       => $user->getId(),
			'user_text'  => $user->getName(),
			'timestamp'  => $now,
			'content_model' => $content->getModel(),
			'content_format' => $meta['serialFormat'],
		] );

		// Save the revision text...
		$revisionId = $revision->insertOn( $dbw );
		// Update the page record with revision data
		if ( !$this->updateRevisionOn( $dbw, $revision, 0 ) ) {
			throw new MWException( "Failed to update page row to use new revision." );
		}

		Hooks::run( 'NewRevisionFromEditComplete', [ $this, $revision, false, $user ] );

		// Update recentchanges
		if ( !( $flags & EDIT_SUPPRESS_RC ) ) {
			// Mark as patrolled if the user can do so
			$patrolled = ( $wgUseRCPatrol || $wgUseNPPatrol ) &&
				!count( $this->mTitle->getUserPermissionsErrors( 'autopatrol', $user ) );
			// Add RC row to the DB
			RecentChange::notifyNew(
				$now,
				$this->mTitle,
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
					Hooks::run( 'PageContentInsertComplete', $params );
					// Trigger post-save hook
					$params = array_merge( $params, [ &$status, $meta['baseRevId'], 0 ] );
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
	 * @param IContextSource|User|string $context One of the following:
	 *        - IContextSource: Use the User and the Language of the provided
	 *          context
	 *        - User: Use the provided User object and $wgLang for the language,
	 *          so use an IContextSource object if possible.
	 *        - 'canonical': Canonical options (anonymous user with default
	 *          preferences and content language).
	 * @return ParserOptions
	 */
	public function makeParserOptions( $context ) {
		$options = $this->page->getContentHandler()->makeParserOptions( $context );

		if ( $this->page->getTitle()->isConversionTable() ) {
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
	 * @param Revision|int|null $revision Revision object. For backwards compatibility, a
	 *        revision ID is also accepted, but this is deprecated.
	 * @param User|null $user
	 * @param string|null $serialFormat
	 * @param bool $useCache Check shared prepared edit cache
	 *
	 * @return PreparedEdit
	 *
	 * @since 1.21
	 */
	public function prepareContentForEdit(
		Content $content, $revision = null, User $user = null,
		$serialFormat = null, $useCache = true
	) {
		global $wgContLang, $wgUser, $wgAjaxEditStash;

		if ( is_object( $revision ) ) {
			$revid = $revision->getId();
		} else {
			$revid = $revision;
			// This code path is deprecated, and nothing is known to
			// use it, so performance here shouldn't be a worry.
			if ( $revid !== null ) {
				wfDeprecated( __METHOD__ . ' with $revision = revision ID', '1.25' );
				$revision = Revision::newFromId( $revid, Revision::READ_LATEST );
			} else {
				$revision = null;
			}
		}

		$user = is_null( $user ) ? $wgUser : $user;
		// XXX: check $user->getId() here???

		// Use a sane default for $serialFormat, see T59026
		if ( $serialFormat === null ) {
			$serialFormat = $content->getContentHandler()->getDefaultFormat();
		}

		if ( $this->mPreparedEdit
			&& isset( $this->mPreparedEdit->newContent )
			&& $this->mPreparedEdit->newContent->equals( $content )
			&& $this->mPreparedEdit->revid == $revid
			&& $this->mPreparedEdit->format == $serialFormat
			// XXX: also check $user here?
		) {
			// Already prepared
			return $this->mPreparedEdit;
		}

		// The edit may have already been prepared via api.php?action=stashedit
		$cachedEdit = $useCache && $wgAjaxEditStash
			? ApiStashEdit::checkCache( $this->page->getTitle(), $content, $user )
			: false;

		$popts = ParserOptions::newFromUserAndLang( $user, $wgContLang );
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
				? $content->preSaveTransform( $this->mTitle, $user, $popts )
				: null;
		}

		$edit->format = $serialFormat;
		$edit->popts = $this->makeParserOptions( 'canonical' );
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
						if ( $title->equals( $revision->getTitle() ) ) {
							return $revision;
						} else {
							return call_user_func( $oldCallback, $title, $parser );
						}
					}
				);
			} else {
				// Try to avoid a second parse if {{REVISIONID}} is used
				$dbIndex = ( $this->mDataLoadedFrom & self::READ_LATEST ) === self::READ_LATEST
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
				? $edit->pstContent->getParserOutput( $this->mTitle, $revid, $edit->popts )
				: null;
		}

		$edit->newContent = $content;
		$edit->oldContent = $this->page->getContent( Revision::RAW );

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
		$this->mPreparedEdit = $edit;

		return $edit;
	}
	/**
	 * Do standard deferred updates after page edit.
	 * Update links tables, site stats, search index and message cache.
	 * Purges pages that include this page if the text was changed here.
	 * Every 100th edit, prune the recent changes table.
	 *
	 * @param Revision $revision
	 * @param User $user User object that did the revision
	 * @param array $options Array of options, following indexes are used:
	 * - changed: bool, whether the revision changed the content (default true)
	 * - created: bool, whether the revision created the page (default false)
	 * - moved: bool, whether the page was moved (default false)
	 * - restored: bool, whether the page was undeleted (default false)
	 * - oldrevision: Revision object for the pre-update revision (default null)
	 * - oldcountable: bool, null, or string 'no-change' (default null):
	 *   - bool: whether the page was counted as an article before that
	 *     revision, only used in changed is true and created is false
	 *   - null: if created is false, don't update the article count; if created
	 *     is true, do update the article count
	 *   - 'no-change': don't update the article count, ever
	 */
	public function doEditUpdates( Revision $revision, User $user, array $options = [] ) {
		global $wgRCWatchCategoryMembership;

		$options += [
			'changed' => true,
			'created' => false,
			'moved' => false,
			'restored' => false,
			'oldrevision' => null,
			'oldcountable' => null
		];
		$content = $revision->getContent();

		$logger = LoggerFactory::getInstance( 'SaveParse' );

		// See if the parser output before $revision was inserted is still valid
		$editInfo = false;
		if ( !$this->mPreparedEdit ) {
			$logger->debug( __METHOD__ . ": No prepared edit...\n" );
		} elseif ( $this->mPreparedEdit->output->getFlag( 'vary-revision' ) ) {
			$logger->info( __METHOD__ . ": Prepared edit has vary-revision...\n" );
		} elseif ( $this->mPreparedEdit->output->getFlag( 'vary-revision-id' )
			&& $this->mPreparedEdit->output->getSpeculativeRevIdUsed() !== $revision->getId()
		) {
			$logger->info( __METHOD__ . ": Prepared edit has vary-revision-id with wrong ID...\n" );
		} elseif ( $this->mPreparedEdit->output->getFlag( 'vary-user' ) && !$options['changed'] ) {
			$logger->info( __METHOD__ . ": Prepared edit has vary-user and is null...\n" );
		} else {
			wfDebug( __METHOD__ . ": Using prepared edit...\n" );
			$editInfo = $this->mPreparedEdit;
		}

		if ( !$editInfo ) {
			// Parse the text again if needed. Be careful not to do pre-save transform twice:
			// $text is usually already pre-save transformed once. Avoid using the edit stash
			// as any prepared content from there or in doEditContent() was already rejected.
			$editInfo = $this->prepareContentForEdit( $content, $revision, $user, null, false );
		}

		// Save it to the parser cache.
		// Make sure the cache time matches page_touched to avoid double parsing.
		MediaWikiServices::getInstance()->getParserCache()->save(
			$editInfo->output, $this, $editInfo->popts,
			$revision->getTimestamp(), $editInfo->revid
		);

		// Update the links tables and other secondary data
		if ( $content ) {
			$recursive = $options['changed']; // T52785
			$updates = $content->getSecondaryDataUpdates(
				$this->page->getTitle(), null, $recursive, $editInfo->output
			);
			foreach ( $updates as $update ) {
				$update->setCause( 'edit-page', $user->getName() );
				if ( $update instanceof LinksUpdate ) {
					$update->setRevision( $revision );
					$update->setTriggeringUser( $user );
				}
				DeferredUpdates::addUpdate( $update );
			}
			if ( $wgRCWatchCategoryMembership
				&& $this->page->getContentHandler()->supportsCategories() === true
				&& ( $options['changed'] || $options['created'] )
				&& !$options['restored']
			) {
				// Note: jobs are pushed after deferred updates, so the job should be able to see
				// the recent change entry (also done via deferred updates) and carry over any
				// bot/deletion/IP flags, ect.
				JobQueueGroup::singleton()->lazyPush( new CategoryMembershipChangeJob(
					$this->page->getTitle(),
					[
						'pageId' => $this->page->getId(),
						'revTimestamp' => $revision->getTimestamp()
					]
				) );
			}
		}

		// Avoid PHP 7.1 warning of passing $this by reference
		$wikiPage = $this;

		Hooks::run( 'ArticleEditUpdates', [ &$wikiPage, &$editInfo, $options['changed'] ] );

		if ( Hooks::run( 'ArticleEditUpdatesDeleteFromRecentchanges', [ &$wikiPage ] ) ) {
			// Flush old entries from the `recentchanges` table
			if ( mt_rand( 0, 9 ) == 0 ) {
				JobQueueGroup::singleton()->lazyPush( RecentChangesUpdateJob::newPurgeJob() );
			}
		}

		if ( !$this->exists() ) {
			return;
		}

		$id = $this->page->getId();
		$title = $this->mTitle->getPrefixedDBkey();
		$shortTitle = $this->mTitle->getDBkey();

		if ( $options['oldcountable'] === 'no-change' ||
			( !$options['changed'] && !$options['moved'] )
		) {
			$good = 0;
		} elseif ( $options['created'] ) {
			$good = (int)$this->isCountable( $editInfo );
		} elseif ( $options['oldcountable'] !== null ) {
			$good = (int)$this->isCountable( $editInfo ) - (int)$options['oldcountable'];
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
			&& $this->mTitle->getNamespace() == NS_USER_TALK
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

		if ( $this->mTitle->getNamespace() == NS_MEDIAWIKI ) {
			MessageCache::singleton()->updateMessageOverride( $this->mTitle, $content );
		}

		if ( $options['created'] ) {
			self::onArticleCreate( $this->mTitle );
		} elseif ( $options['changed'] ) { // T52785
			self::onArticleEdit( $this->mTitle, $revision );
		}

		ResourceLoaderWikiModule::invalidateModuleCache(
			$this->mTitle, $options['oldrevision'], $revision, wfWikiID()
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
		&& ( $oldId === null || $oldId === 0 || $oldId === $this->page->getLatest() )
		&& $this->page->getContentHandler()->isParserCacheSupported();
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
	 * @param User|null $user
	 * @return Revision|null Null on error
	 */
	public function insertProtectNullRevision( $revCommentMsg, array $limit,
		array $expiry, $cascade, $reason, $user = null
	) {
		// FIXME: pull up to application logic!
		$dbw = $this->getDBConnectionRef( DB_MASTER );

		// Prepare a null revision to be added to the history
		$editComment = wfMessage(
			$revCommentMsg,
			$this->mTitle->getPrefixedText(),
			$user ? $user->getName() : ''
		)->inContentLanguage()->text();
		if ( $reason ) {
			$editComment .= wfMessage( 'colon-separator' )->inContentLanguage()->text() . $reason;
		}
		$protectDescription = $this->protectDescription( $limit, $expiry );
		if ( $protectDescription ) {
			$editComment .= wfMessage( 'word-separator' )->inContentLanguage()->text();
			$editComment .= wfMessage( 'parentheses' )->params( $protectDescription )
				->inContentLanguage()->text();
		}
		if ( $cascade ) {
			$editComment .= wfMessage( 'word-separator' )->inContentLanguage()->text();
			$editComment .= wfMessage( 'brackets' )->params(
				wfMessage( 'protect-summary-cascade' )->inContentLanguage()->text()
			)->inContentLanguage()->text();
		}

		$nullRev = Revision::newNullRevision( $dbw, $this->page->getId(), $editComment, true, $user );
		if ( $nullRev ) {
			$nullRev->insertOn( $dbw );

			// Update page record and touch page
			$oldLatest = $nullRev->getParentId();
			$this->updateRevisionOn( $dbw, $nullRev, $oldLatest );
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
		// FIXME: move to RestrictionStore
		global $wgCascadingRestrictionLevels;

		if ( wfReadOnly() ) {
			return Status::newFatal( wfMessage( 'readonlytext', wfReadOnlyReason() ) );
		}

		$this->loadPageData( 'fromdbmaster' );
		$restrictionTypes = $this->mTitle->getRestrictionTypes();
		$id = $this->page->getId();

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
			$current = implode( '', $this->mTitle->getRestrictions( $action ) );
			if ( $current != '' ) {
				$isProtected = true;
			}

			if ( $limit[$action] != $current ) {
				$changed = true;
			} elseif ( $limit[$action] != '' ) {
				// Only check expiry change if the action is actually being
				// protected, since expiry does nothing on an not-protected
				// action.
				if ( $this->mTitle->getRestrictionExpiry( $action ) != $expiry[$action] ) {
					$changed = true;
				}
			}
		}

		if ( !$changed && $protect && $this->mTitle->areRestrictionsCascading() != $cascade ) {
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
				: $this->mTitle->getRestrictions( 'edit' );
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
			$latest = $this->page->getLatest();
			$nullRevision = $this->insertProtectNullRevision(
				$revCommentMsg,
				$limit,
				$expiry,
				$cascade,
				$reason,
				$user
			);

			if ( $nullRevision === null ) {
				return Status::newFatal( 'no-null-revision', $this->mTitle->getPrefixedText() );
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
						'pt_namespace' => $this->mTitle->getNamespace(),
						'pt_title' => $this->mTitle->getDBkey(),
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
						'pt_namespace' => $this->mTitle->getNamespace(),
						'pt_title' => $this->mTitle->getDBkey()
					], __METHOD__
				);
			}
		}

		$this->mTitle->flushRestrictions();
		InfoAction::invalidateCache( $this->mTitle );

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
		$logEntry->setTarget( $this->mTitle );
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
		global $wgContLang;

		if ( $expiry != 'infinity' ) {
			return wfMessage(
				'protect-expiring',
				$wgContLang->timeanddate( $expiry, false, false ),
				$wgContLang->date( $expiry, false, false ),
				$wgContLang->time( $expiry, false, false )
			)->inContentLanguage()->text();
		} else {
			return wfMessage( 'protect-expiry-indefinite' )
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
			$actionText = wfMessage( 'restriction-' . $action )->inContentLanguage()->text();
			# $restrictions is one of $wgRestrictionLevels = [ '', 'autoconfirmed', 'sysop' ],
			# with '' filtered out. All possible message keys are listed below:
			# * protect-level-autoconfirmed
			# * protect-level-sysop
			$restrictionsText = wfMessage( 'protect-level-' . $restrictions )
				->inContentLanguage()->text();

			$expiryText = $this->formatExpiry( $expiry[$action] );

			if ( $protectDescription !== '' ) {
				$protectDescription .= wfMessage( 'word-separator' )->inContentLanguage()->text();
			}
			$protectDescription .= wfMessage( 'protect-summary-desc' )
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
		global $wgContLang;
		// FIXME: pull up to application logic
		$protectDescriptionLog = '';

		foreach ( array_filter( $limit ) as $action => $restrictions ) {
			$expiryText = $this->formatExpiry( $expiry[$action] );
			$protectDescriptionLog .= $wgContLang->getDirMark() .
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
	 * @param string $reason Delete reason for deletion log
	 * @param bool $suppress Suppress all revisions and log the deletion in
	 *        the suppression log instead of the deletion log
	 * @param int $u1 Unused
	 * @param bool $u2 Unused
	 * @param array|string &$error Array of errors to append to
	 * @param User $user The deleting user
	 * @return bool True if successful
	 */
	public function doDeleteArticle(
		$reason, $suppress = false, $u1 = null, $u2 = null, &$error = '', User $user = null
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
	 * @param string $reason Delete reason for deletion log
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
		$reason, $suppress = false, $u1 = null, $u2 = null, &$error = '', User $user = null,
		$tags = [], $logsubtype = 'delete'
	) {
		global $wgUser, $wgContentHandlerUseDB, $wgCommentTableSchemaMigrationStage;

		wfDebug( __METHOD__ . "\n" );

		$status = Status::newGood();

		if ( $this->mTitle->getDBkey() === '' ) {
			$status->error( 'cannotdelete',
				wfEscapeWikiText( $this->page->getTitle()->getPrefixedText() ) );
			return $status;
		}

		// Avoid PHP 7.1 warning of passing $this by reference
		$wikiPage = $this;

		$user = is_null( $user ) ? $wgUser : $user;
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

		$this->loadPageData( self::READ_LATEST );
		$id = $this->page->getId();
		// T98706: lock the page from various other updates but avoid using
		// WikiPage::READ_LOCKING as that will carry over the FOR UPDATE to
		// the revisions queries (which also JOIN on user). Only lock the page
		// row and CAS check on page_latest to see if the trx snapshot matches.
		$lockedLatest = $this->lockAndGetLatest();
		if ( $id == 0 || $this->page->getLatest() != $lockedLatest ) {
			$dbw->endAtomic( __METHOD__ );
			// Page not there or trx snapshot is stale
			$status->error( 'cannotdelete',
				wfEscapeWikiText( $this->page->getTitle()->getPrefixedText() ) );
			return $status;
		}

		// Given the lock above, we can be confident in the title and page ID values
		$namespace = $this->page->getTitle()->getNamespace();
		$dbKey = $this->page->getTitle()->getDBkey();

		// At this point we are now comitted to returning an OK
		// status unless some DB query error or other exception comes up.
		// This way callers don't have to call rollback() if $status is bad
		// unless they actually try to catch exceptions (which is rare).

		// we need to remember the old content so we can use it to generate all deletion updates.
		$revision = $this->page->getRevision();
		try {
			$content = $this->page->getContent( Revision::RAW );
		} catch ( Exception $ex ) {
			wfLogWarning( __METHOD__ . ': failed to load content during deletion! '
				. $ex->getMessage() );

			$content = null;
		}

		$revCommentStore = new CommentStore( 'rev_comment' );
		$arCommentStore = new CommentStore( 'ar_comment' );

		$revQuery = Revision::getQueryInfo();
		$bitfield = false;

		// Bitfields to further suppress the content
		if ( $suppress ) {
			$bitfield = Revision::SUPPRESSED_ALL;
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

		/** @var int[] Revision IDs of edits that were made by IPs */
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
			if ( $wgContentHandlerUseDB ) {
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
		$logTitle = clone $this->mTitle;
		$wikiPageBeforeDelete = clone $this;

		// Now that it's safely backed up, delete it
		$dbw->delete( 'page', [ 'page_id' => $id ], __METHOD__ );
		$dbw->delete( 'revision', [ 'rev_page' => $id ], __METHOD__ );
		if ( $wgCommentTableSchemaMigrationStage > MIGRATION_OLD ) {
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

		$this->doDeleteUpdates( $id, $content, $revision, $user );

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
		$cache = MediaWikiServices::getInstance()->getMainObjectStash();
		$key = $cache->makeKey( 'page-recent-delete', md5( $logTitle->getPrefixedText() ) );
		$cache->set( $key, 1, $cache::TTL_DAY );

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
		$editErrors = $this->mTitle->getUserPermissionsErrors( 'edit', $user );
		$rollbackErrors = $this->mTitle->getUserPermissionsErrors( 'rollback', $user );
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
	 * @param array|null $tags Change tags to apply to the rollback
	 * Callers are responsible for permission checks
	 * (with ChangeTags::canAddTagsAccompanyingChange)
	 *
	 * @return array
	 */
	public function commitRollback( $fromP, $summary, $bot,
		&$resultDetails, User $guser, $tags = null
	) {
		global $wgUseRCPatrol, $wgContLang;

		$dbw = $this->getDBConnectionRef( DB_MASTER );

		if ( wfReadOnly() ) {
			return [ [ 'readonlytext' ] ];
		}

		// Get the last editor
		$current = $this->page->getRevision();
		if ( is_null( $current ) ) {
			// Something wrong... no page?
			return [ [ 'notanarticle' ] ];
		}

		$from = str_replace( '_', ' ', $fromP );
		// User name given should match up with the top revision.
		// If the user was deleted then $from should be empty.
		if ( $from != $current->getUserText() ) {
			$resultDetails = [ 'current' => $current ];
			return [ [ 'alreadyrolled',
				htmlspecialchars( $this->mTitle->getPrefixedText() ),
				htmlspecialchars( $fromP ),
				htmlspecialchars( $current->getUserText() )
			] ];
		}

		// Get the last edit not by this person...
		// Note: these may not be public values
		$user = intval( $current->getUser( Revision::RAW ) );
		$user_text = $dbw->addQuotes( $current->getUserText( Revision::RAW ) );
		$s = $dbw->selectRow( 'revision',
			[ 'rev_id', 'rev_timestamp', 'rev_deleted' ],
			[ 'rev_page' => $current->getPage(),
				"rev_user != {$user} OR rev_user_text != {$user_text}"
			], __METHOD__,
			[ 'USE INDEX' => 'page_timestamp',
			  'ORDER BY' => 'rev_timestamp DESC' ]
		);
		if ( $s === false ) {
			// No one else ever edited this page
			return [ [ 'cantrollback' ] ];
		} elseif ( $s->rev_deleted & Revision::DELETED_TEXT
			|| $s->rev_deleted & Revision::DELETED_USER
		) {
			// Only admins can see this text
			return [ [ 'notvisiblerev' ] ];
		}

		// Generate the edit summary if necessary
		$target = Revision::newFromId( $s->rev_id, Revision::READ_LATEST );
		if ( empty( $summary ) ) {
			if ( $from == '' ) { // no public user name
				$summary = wfMessage( 'revertpage-nouser' );
			} else {
				$summary = wfMessage( 'revertpage' );
			}
		}

		// Allow the custom summary to use the same args as the default message
		$args = [
			$target->getUserText(), $from, $s->rev_id,
			$wgContLang->timeanddate( wfTimestamp( TS_MW, $s->rev_timestamp ) ),
			$current->getId(), $wgContLang->timeanddate( $current->getTimestamp() )
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

		$targetContent = $target->getContent();
		$changingContentModel = $targetContent->getModel() !== $current->getContentModel();

		// Actually store the edit
		$status = $this->doEditContent(
			$targetContent,
			$summary,
			$flags,
			$target->getId(),
			$guser,
			null,
			$tags
		);

		// Set patrolling and bot flag on the edits, which gets rollbacked.
		// This is done even on edit failure to have patrolling in that case (T64157).
		$set = [];
		if ( $bot && $guser->isAllowed( 'markbotedits' ) ) {
			// Mark all reverted edits as bot
			$set['rc_bot'] = 1;
		}

		if ( $wgUseRCPatrol ) {
			// Mark all reverted edits as patrolled
			$set['rc_patrolled'] = 1;
		}

		if ( count( $set ) ) {
			$dbw->update( 'recentchanges', $set,
				[ /* WHERE */
					'rc_cur_id' => $current->getPage(),
					'rc_user_text' => $current->getUserText(),
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
		if ( !( $statusRev instanceof Revision ) ) {
			$resultDetails = [ 'current' => $current ];
			return [ [ 'alreadyrolled',
				htmlspecialchars( $this->mTitle->getPrefixedText() ),
				htmlspecialchars( $fromP ),
				htmlspecialchars( $current->getUserText() )
			] ];
		}

		if ( $changingContentModel ) {
			// If the content model changed during the rollback,
			// make sure it gets logged to Special:Log/contentmodel
			$log = new ManualLogEntry( 'contentmodel', 'change' );
			$log->setPerformer( $guser );
			$log->setTarget( $this->mTitle );
			$log->setComment( $summary );
			$log->setParameters( [
				'4::oldmodel' => $current->getContentModel(),
				'5::newmodel' => $targetContent->getModel(),
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

}
