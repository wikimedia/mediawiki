<?php

namespace MediaWiki\Storage;

use BagOStuff;
use Category;
use CdnCacheUpdate;
use Content;
use DeferrableUpdate;
use DeferredUpdates;
use Exception;
use Hooks;
use HTMLCacheUpdate;
use HTMLFileCache;
use IDBAccessObject;
use InfoAction;
use InvalidArgumentException;
use JobQueueGroup;
use LinkCache;
use LinksUpdate;
use MessageCache;
use MWExceptionHandler;
use MWNamespace;
use ParserOutput;
use RefreshLinksJob;
use RepoGroup;
use ResourceLoaderWikiModule;
use SearchUpdate;
use SiteStatsUpdate;
use Title;
use User;
use Wikimedia\Rdbms\DBError;
use WikiPage;

/**
 * FIXME: header
 * FIXME: document!
 */
class PageEventEmitter implements IDBAccessObject {

	/**
	 * @var MessageCache
	 */
	private $messageCache;

	/**
	 * @var LinkCache
	 */
	private $linkCache;

	/**
	 * @var JobQueueGroup
	 */
	private $jobQueueGroup;

	/**
	 * @var boolean
	 */
	private $miserMode = false;

	/**
	 * @var string see $wgArticleCountMethod
	 */
	private $articleCountMethod = 'link';

	/**
	 * @var BagOStuff
	 */
	private $objectCache;

	/**
	 * @var RepoGroup
	 */
	private $repoGroup;

	/**
	 * PageEventEmitter constructor.
	 *
	 * @param JobQueueGroup $jobQueueGroup
	 * @param RepoGroup $repoGroup
	 * @param MessageCache $messageCache
	 * @param LinkCache $linkCache
	 * @param BagOStuff $objectCache
	 */
	public function __construct(
		JobQueueGroup $jobQueueGroup,
		RepoGroup $repoGroup,
		MessageCache $messageCache,
		LinkCache $linkCache,
		BagOStuff $objectCache
	) {
		$this->jobQueueGroup = $jobQueueGroup;
		$this->repoGroup = $repoGroup;
		$this->messageCache = $messageCache;
		$this->linkCache = $linkCache;
		$this->objectCache = $objectCache;
	}

	/**
	 * @param boolean $miserMode
	 */
	public function setMiserMode( $miserMode ) {
		$this->miserMode = $miserMode;
	}

	/**
	 * @param string $articleCountMethod see $wgArticleCountMethod
	 */
	public function setArticleCountMethod( $articleCountMethod ) {
		$this->articleCountMethod = $articleCountMethod;
	}

	/**
	 * Do standard deferred updates after page view (existing or missing page)
	 *
	 * @param PageIdentity $page
	 * @param User $user The relevant user
	 * @param int $oldid Revision id being viewed; if not given or 0, latest revision is assumed
	 *
	 * @throws \FatalError
	 */
	public function doViewUpdates( PageIdentity $page, User $user, $oldid = 0 ) {
		if ( wfReadOnly() ) {
			return;
		}

		Hooks::run( 'PageViewUpdates', [ $this, $user ] ); // FIXME: hook signature!
		// Update newtalk / watchlist notification status
		try {
			// TODO: refactor so we don't need User nor Title here.
			$title = Title::newFromPageIdentity( $page );
			$user->clearNotification( $title, $oldid );
		} catch ( DBError $e ) {
			// Avoid outage if the master is not reachable
			MWExceptionHandler::logException( $e );
		}
	}

	/**
	 * Perform the actions of a page purging
	 *
	 * @param PageRecord $page
	 *
	 * @return bool
	 * @throws \FatalError
	 * @note In 1.28 (and only 1.28), this took a $flags parameter that
	 *  controlled how much purging was done.
	 */
	public function doPurge( PageRecord $page ) {
		$wikiPage = WikiPage::newFromPageIdentity( $page );
		if ( !Hooks::run( 'ArticlePurge', [ $wikiPage ] ) ) {
			return false;
		}

		$title = Title::newFromPageIdentity( $page );
		$title->invalidateCache();

		// Clear file cache
		HTMLFileCache::clearFileCache( $title );
		// Send purge after above page_touched update was committed
		DeferredUpdates::addUpdate(
			new CdnCacheUpdate( $title->getCdnUrls() ),
			DeferredUpdates::PRESEND
		);

		if ( $title->getNamespace() == NS_MEDIAWIKI ) {
			$revision = $page->getCurrentRevision();
			$content = $revision ? $revision->getContent( 'main' ) : null;
			$this->messageCache->updateMessageOverride( $title, $content );
		}

		return true;
	}

	/**
	 * Do some database updates after deletion
	 *
	 * @param PageRecord $page The page being deleted
	 * @param Content[]|null $content Optional page content to be used when determining
	 *   the required updates. This may be needed because $this->getContent()
	 *   may already return null when the page proper was deleted.
	 * @param RevisionRecord|null $revision The latest page revision
	 * @param User|null $user The user that caused the deletion
	 */
	public function doDeleteUpdates(
		PageRecord $page,
		array $content = null,
		RevisionRecord $revision = null,
		User $user = null
	) {
		try {
			$countable = $this->isCountable( $page );
		} catch ( Exception $ex ) {
			// fallback for deleting broken pages for which we cannot load the content for
			// some reason. Note that doDeleteArticleReal() already logged this problem.
			$countable = false;
		}

		// Update site status
		DeferredUpdates::addUpdate( new SiteStatsUpdate( 0, 1, - (int)$countable, -1 ) );

		// Delete pagelinks, update secondary indexes, etc
		$updates = $this->getDeletionUpdates( $page, $content );
		foreach ( $updates as $update ) {
			DeferredUpdates::addUpdate( $update );
		}

		$title = Title::newFromPageIdentity( $page );
		$causeAgent = $user ? $user->getName() : 'unknown';
		// Reparse any pages transcluding this page
		LinksUpdate::queueRecursiveJobsForTable(
			$title, 'templatelinks', 'delete-page', $causeAgent );
		// Reparse any pages including this image
		if ( $title->getNamespace() == NS_FILE ) {
			LinksUpdate::queueRecursiveJobsForTable(
				$title, 'imagelinks', 'delete-page', $causeAgent );
		}

		// Clear caches
		self::onArticleDelete( $page );
		ResourceLoaderWikiModule::invalidateModuleCache(
			$title, $revision, null, wfWikiID()
		);

		// Search engine
		DeferredUpdates::addUpdate( new SearchUpdate( $page->getId(), $title ) );
	}

	/**
	 * The onArticle*() functions are supposed to be a kind of hooks
	 * which should be called whenever any of the specified actions
	 * are done.
	 *
	 * This is a good place to put code to clear caches, for instance.
	 *
	 * This is called on page move and undelete, as well as edit
	 *
	 * @param PageIdentity $page
	 */
	public function onArticleCreate( PageIdentity $page ) {
		// Update existence markers on article/talk tabs...
		$title = Title::newFromPageIdentity( $page );
		$other = $title->getOtherPage();

		$other->purgeSquid();

		$title->touchLinks();
		$title->purgeSquid();
		$title->deleteTitleProtection();

		$this->linkCache->invalidateTitle( $title );

		// Invalidate caches of articles which include this page
		DeferredUpdates::addUpdate(
			new HTMLCacheUpdate( $title, 'templatelinks', 'page-create' )
		);

		if ( $title->getNamespace() == NS_CATEGORY ) {
			// Load the Category object, which will schedule a job to create
			// the category table row if necessary. Checking a replica DB is ok
			// here, in the worst case it'll run an unnecessary recount job on
			// a category that probably doesn't have many members.
			Category::newFromTitle( $title )->getID();
		}
	}
	/**
	 * Clears caches when article is deleted
	 *
	 * @param PageIdentity $page
	 */
	public function onArticleDelete( PageIdentity $page ) {
		// Update existence markers on article/talk tabs...
		$title = Title::newFromPageIdentity( $page );
		$other = $title->getOtherPage();

		$other->purgeSquid();

		$title->touchLinks();
		$title->purgeSquid();

		$this->linkCache->invalidateTitle( $title );

		// File cache
		HTMLFileCache::clearFileCache( $title );
		InfoAction::invalidateCache( $title );

		// Messages
		if ( $title->getNamespace() == NS_MEDIAWIKI ) {
			$this->messageCache->updateMessageOverride( $title, null );
		}

		// Images
		if ( $title->getNamespace() == NS_FILE ) {
			DeferredUpdates::addUpdate(
				new HTMLCacheUpdate( $title, 'imagelinks', 'page-delete' )
			);
		}

		// User talk pages
		if ( $title->getNamespace() == NS_USER_TALK ) {
			$user = User::newFromName( $title->getText(), false );
			if ( $user ) {
				$user->setNewtalk( false );
			}
		}

		// Image redirects
		$this->repoGroup->getLocalRepo()->invalidateImageRedirect( $title );
	}

	/**
	 * Purge caches on page update etc
	 *
	 * @param PageIdentity $page
	 * @param RevisionRecord|null $revision Revision that was just saved, may be null
	 */
	public function onArticleEdit( PageIdentity $page, RevisionRecord $revision = null ) {
		// Invalidate caches of articles which include this page
		$title = Title::newFromPageIdentity( $page );
		DeferredUpdates::addUpdate(
			new HTMLCacheUpdate( $title, 'templatelinks', 'page-edit' )
		);

		// Invalidate the caches of all pages which redirect here
		DeferredUpdates::addUpdate(
			new HTMLCacheUpdate( $title, 'redirect', 'page-edit' )
		);

		$this->linkCache->invalidateTitle( $title );

		// Purge CDN for this page only
		$title->purgeSquid();
		// Clear file cache for this page only
		HTMLFileCache::clearFileCache( $title );

		$revid = $revision ? $revision->getId() : null;
		DeferredUpdates::addCallableUpdate( function () use ( $title, $revid ) {
			InfoAction::invalidateCache( $title, $revid );
		} );
	}

	/**
	 * Opportunistically enqueue link update jobs given fresh parser output if useful
	 *
	 * @param PageRecord $page
	 * @param ParserOutput $parserOutput Current version page output
	 *
	 * @throws \FatalError
	 * @since 1.25
	 */
	public function triggerOpportunisticLinksUpdate( PageRecord $page, ParserOutput $parserOutput ) {
		if ( wfReadOnly() ) {
			return;
		}

		// FIXME: hook signature!
		$title = Title::newFromPageIdentity( $page );
		if ( !Hooks::run( 'OpportunisticLinksUpdate',
			[ $this, $title, $parserOutput ]
		) ) {
			return;
		}

		$params = [
			'isOpportunistic' => true,
			'rootJobTimestamp' => $parserOutput->getCacheTime()
		];

		if ( $title->areRestrictionsCascading() ) {
			// If the page is cascade protecting, the links should really be up-to-date
			$this->jobQueueGroup->lazyPush(
				RefreshLinksJob::newPrioritized( $title, $params )
			);
		} elseif ( !$this->miserMode && $parserOutput->hasDynamicContent() ) {
			// Assume the output contains "dynamic" time/random based magic words.
			// Only update pages that expired due to dynamic content and NOT due to edits
			// to referenced templates/files. When the cache expires due to dynamic content,
			// page_touched is unchanged. We want to avoid triggering redundant jobs due to
			// views of pages that were just purged via HTMLCacheUpdateJob. In that case, the
			// template/file edit already triggered recursive RefreshLinksJob jobs.
			if ( $page->getLinksTimestamp() > $page->getTouched() ) {
				// If a page is uncacheable, do not keep spamming a job for it.
				// Although it would be de-duplicated, it would still waste I/O.
				$key = $this->objectCache->makeKey( 'dynamic-linksupdate', 'last', $page->getId() );
				$ttl = max( $parserOutput->getCacheExpiry(), 3600 );
				if ( $this->objectCache->add( $key, time(), $ttl ) ) {
					$this->jobQueueGroup->lazyPush(
						RefreshLinksJob::newDynamic( $title, $params )
					);
				}
			}
		}
	}

	/**
	 * Returns a list of updates to be performed when this page is deleted. The
	 * updates should remove any information about this page from secondary data
	 * stores such as links tables.
	 *
	 * @param PageRecord $page
	 * @param Content[]|null $content Content object for determining the
	 *   necessary updates.
	 *
	 * @return \DeferrableUpdate[]
	 * @throws \FatalError
	 */
	public function getDeletionUpdates( PageRecord $page, array $content = null ) {
		if ( !$content ) {
			// load content object, which may be used to determine the necessary updates.
			// XXX: the content may not be needed to determine the updates.
			try {
				$content = $page->getCurrentRevision()->getContent( 'main', RevisionRecord::RAW );
			} catch ( Exception $ex ) {
				// If we can't load the content, something is wrong. Perhaps that's why
				// the user is trying to delete the page, so let's not fail in that case.
				// Note that doDeleteArticleReal() will already have logged an issue with
				// loading the content.
			}
		}

		if ( !$content ) {
			$updates = [];
		} else {
			$wikiPage = WikiPage::newFromPageIdentity( $page );
			$updates = $content->getDeletionUpdates( $wikiPage );
		}

		// FIXME: hook signature!
		Hooks::run( 'WikiPageDeletionUpdates', [ $this, $content, &$updates ] );
		return $updates;
	}

	/**
	 * Determine whether a page would be suitable for being counted as an
	 * article in the site_stats table based on the title & its content
	 *
	 * @todo Logically, this does not fit into PageEventEmitter very well.
	 * Pragmatically, this is a good place for now, but we should find a better place.
	 *
	 * @param PageRecord $page
	 *
	 * @return bool
	 * @throws \Wikimedia\Rdbms\DBUnexpectedError
	 */
	public function isCountable( PageRecord $page ) {
		if ( $page->isRedirect() || !MWNamespace::isContent( $page->getNamespace() ) ) {
			return false;
		}

		$revision = $page->getCurrentRevision();
		$content = $revision ? $revision->getContent( 'main' ) : null;

		if ( !$content ) {
			return false;
		}

		$hasLinks = null;

		if ( $this->articleCountMethod === 'link' ) {
			$hasLinks = (bool)wfGetDB( DB_REPLICA )->selectField( 'pagelinks', 1,
				[ 'pl_from' => $page->getId() ], __METHOD__ );
		}

		return $content->isCountable( $hasLinks );
	}

}
