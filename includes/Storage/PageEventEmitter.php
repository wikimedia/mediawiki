<?php

namespace MediaWiki\Storage;

use DeferredUpdates;
use Hooks;
use IDBAccessObject;
use InvalidArgumentException;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\User\UserIdentity;
use MWExceptionHandler;
use Title;
use LinkCache;
use User;
use UserArrayFromResult;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\DBConnRef;
use Wikimedia\Rdbms\DBError;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * FIXME: header
 * FIXME: document!
 */
class PageEventEmitter implements IDBAccessObject {

	/**
	 * Do standard deferred updates after page view (existing or missing page)
	 *
	 * @param User $user The relevant user
	 * @param int $oldid Revision id being viewed; if not given or 0, latest revision is assumed
	 *
	 * @throws \FatalError
	 */
	public function doViewUpdates( PageIdentity $page, User $user, $oldid = 0 ) {
		// FIXME: this doesn't belong into PageStore. But where does it belong?

		if ( wfReadOnly() ) {
			return;
		}

		Hooks::run( 'PageViewUpdates', [ $this, $user ] ); // FIXME: hook signature!
		// Update newtalk / watchlist notification status
		try {
			// TODO: refactor so we don't need User nor Title here.
			$title = $this->makeTitle( $page );
			$user->clearNotification( $title, $oldid );
		} catch ( DBError $e ) {
			// Avoid outage if the master is not reachable
			MWExceptionHandler::logException( $e );
		}
	}

	/**
	 * Perform the actions of a page purging
	 * @return bool
	 * @note In 1.28 (and only 1.28), this took a $flags parameter that
	 *  controlled how much purging was done.
	 */
	public function doPurge() {
		// FIXME: this doesn't belong into PageStore. But where does it belong?

		// Avoid PHP 7.1 warning of passing $this by reference
		$wikiPage = $this;

		if ( !Hooks::run( 'ArticlePurge', [ &$wikiPage ] ) ) {  // FIXME: hook signature!
			return false;
		}

		$this->mTitle->invalidateCache();

		// Clear file cache
		HTMLFileCache::clearFileCache( $this->getTitle() );
		// Send purge after above page_touched update was committed
		DeferredUpdates::addUpdate(
			new CdnCacheUpdate( $this->mTitle->getCdnUrls() ),
			DeferredUpdates::PRESEND
		);

		if ( $this->mTitle->getNamespace() == NS_MEDIAWIKI ) {
			$messageCache = MessageCache::singleton();
			$messageCache->updateMessageOverride( $this->mTitle, $this->getContent() );
		}

		return true;
	}

	/**
	 * Do some database updates after deletion
	 *
	 * @param int $id The page_id value of the page being deleted
	 * @param Content|null $content Optional page content to be used when determining
	 *   the required updates. This may be needed because $this->getContent()
	 *   may already return null when the page proper was deleted.
	 * @param Revision|null $revision The latest page revision
	 * @param User|null $user The user that caused the deletion
	 */
	public function doDeleteUpdates(
		$id, Content $content = null, Revision $revision = null, User $user = null
	) {
		// FIXME: this does not belong into the page store. Where does it belong?
		try {
			$countable = $this->isCountable();
		} catch ( Exception $ex ) {
			// fallback for deleting broken pages for which we cannot load the content for
			// some reason. Note that doDeleteArticleReal() already logged this problem.
			$countable = false;
		}

		// Update site status
		DeferredUpdates::addUpdate( new SiteStatsUpdate( 0, 1, - (int)$countable, -1 ) );

		// Delete pagelinks, update secondary indexes, etc
		$updates = $this->getDeletionUpdates( $content );
		foreach ( $updates as $update ) {
			DeferredUpdates::addUpdate( $update );
		}

		$causeAgent = $user ? $user->getName() : 'unknown';
		// Reparse any pages transcluding this page
		LinksUpdate::queueRecursiveJobsForTable(
			$this->mTitle, 'templatelinks', 'delete-page', $causeAgent );
		// Reparse any pages including this image
		if ( $this->mTitle->getNamespace() == NS_FILE ) {
			LinksUpdate::queueRecursiveJobsForTable(
				$this->mTitle, 'imagelinks', 'delete-page', $causeAgent );
		}

		// Clear caches
		self::onArticleDelete( $this->mTitle );
		ResourceLoaderWikiModule::invalidateModuleCache(
			$this->mTitle, $revision, null, wfWikiID()
		);

		// Reset this object and the Title object
		$this->loadFromRow( false, self::READ_LATEST );

		// Search engine
		DeferredUpdates::addUpdate( new SearchUpdate( $id, $this->mTitle ) );
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
	 * @param Title $title
	 */
	public function onArticleCreate( Title $title ) {
		// Update existence markers on article/talk tabs...
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
	 * @param Title $title
	 */
	public function onArticleDelete( Title $title ) {
		// Update existence markers on article/talk tabs...
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
			MessageCache::singleton()->updateMessageOverride( $title, null );
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
		RepoGroup::singleton()->getLocalRepo()->invalidateImageRedirect( $title );
	}

	/**
	 * Purge caches on page update etc
	 *
	 * @param Title $title
	 * @param Revision|null $revision Revision that was just saved, may be null
	 */
	public function onArticleEdit( Title $title, Revision $revision = null ) {
		// Invalidate caches of articles which include this page
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
		$title = $this->makeTitle( $page );
		if ( !Hooks::run( 'OpportunisticLinksUpdate',
			[ $this, $title, $parserOutput ]
		) ) {
			return;
		}

		$config = RequestContext::getMain()->getConfig();

		$params = [
			'isOpportunistic' => true,
			'rootJobTimestamp' => $parserOutput->getCacheTime()
		];

		if ( $title->areRestrictionsCascading() ) {
			// If the page is cascade protecting, the links should really be up-to-date
			JobQueueGroup::singleton()->lazyPush(
				RefreshLinksJob::newPrioritized( $title, $params )
			);
		} elseif ( !$config->get( 'MiserMode' ) && $parserOutput->hasDynamicContent() ) {
			// Assume the output contains "dynamic" time/random based magic words.
			// Only update pages that expired due to dynamic content and NOT due to edits
			// to referenced templates/files. When the cache expires due to dynamic content,
			// page_touched is unchanged. We want to avoid triggering redundant jobs due to
			// views of pages that were just purged via HTMLCacheUpdateJob. In that case, the
			// template/file edit already triggered recursive RefreshLinksJob jobs.
			if ( $page->getLinksTimestamp() > $page->getTouched() ) {
				// If a page is uncacheable, do not keep spamming a job for it.
				// Although it would be de-duplicated, it would still waste I/O.
				$cache = ObjectCache::getLocalClusterInstance();
				$key = $cache->makeKey( 'dynamic-linksupdate', 'last', $page->getId() );
				$ttl = max( $parserOutput->getCacheExpiry(), 3600 );
				if ( $cache->add( $key, time(), $ttl ) ) {
					JobQueueGroup::singleton()->lazyPush(
						RefreshLinksJob::newDynamic( $this->mTitle, $params )
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
	 * @param Content|null $content Optional Content object for determining the
	 *   necessary updates.
	 * @return DeferrableUpdate[]
	 */
	public function getDeletionUpdates( Content $content = null ) {
		if ( !$content ) {
			// load content object, which may be used to determine the necessary updates.
			// XXX: the content may not be needed to determine the updates.
			try {
				$content = $this->getContent( Revision::RAW );
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
			$updates = $content->getDeletionUpdates( $this );
		}

		// FIXME: hook signature!
		Hooks::run( 'WikiPageDeletionUpdates', [ $this, $content, &$updates ] );
		return $updates;
	}

}
