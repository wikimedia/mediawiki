<?php

namespace MediaWiki\Actions;

use MediaWiki\Cache\HTMLFileCache;
use MediaWiki\Context\RequestContext;
use MediaWiki\Exception\BadTitleError;
use MediaWiki\Exception\ErrorPageError;
use MediaWiki\Exception\HttpError;
use MediaWiki\Exception\MWExceptionRenderer;
use MediaWiki\Exception\PermissionsError;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiEntryPoint;
use MediaWiki\Output\OutputPage;
use MediaWiki\Page\Article;
use MediaWiki\Page\WikiFilePage;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Profiler\ProfilingContext;
use MediaWiki\Request\DerivativeRequest;
use MediaWiki\Request\WebRequest;
use MediaWiki\SpecialPage\RedirectSpecialPage;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\MalformedTitleException;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use Profiler;
use Throwable;
use UnexpectedValueException;
use Wikimedia\Rdbms\DBConnectionError;

/**
 * The index.php entry point for web browser navigations, usually routed to
 * an Action or SpecialPage subclass.
 *
 * @internal For use in index.php
 * @ingroup entrypoint
 */
class ActionEntryPoint extends MediaWikiEntryPoint {

	/**
	 * Overwritten to narrow the return type to RequestContext
	 */
	protected function getContext(): RequestContext {
		/** @var RequestContext $context */
		$context = parent::getContext();

		// @phan-suppress-next-line PhanTypeMismatchReturnSuperType see $context in the constructor
		return $context;
	}

	protected function getOutput(): OutputPage {
		return $this->getContext()->getOutput();
	}

	protected function getUser(): User {
		return $this->getContext()->getUser();
	}

	protected function handleTopLevelError( Throwable $e ) {
		$context = $this->getContext();
		$action = $context->getRequest()->getRawVal( 'action' ) ?? 'view';
		if (
			$e instanceof DBConnectionError &&
			$context->hasTitle() &&
			$context->getTitle()->canExist() &&
			in_array( $action, [ 'view', 'history' ], true ) &&
			HTMLFileCache::useFileCache( $context, HTMLFileCache::MODE_OUTAGE )
		) {
			// Try to use any (even stale) file during outages...
			$cache = new HTMLFileCache( $context->getTitle(), $action );
			if ( $cache->isCached() ) {
				$cache->loadFromFileCache( $context, HTMLFileCache::MODE_OUTAGE );
				$this->print( MWExceptionRenderer::getHTML( $e ) );
				$this->exit();
			}
		}

		parent::handleTopLevelError( $e );
	}

	/**
	 * Determine and send the response headers and body for this web request
	 */
	protected function execute() {
		// phpcs:ignore MediaWiki.Usage.DeprecatedGlobalVariables.Deprecated$wgTitle
		global $wgTitle;

		// Get title from request parameters,
		// is set on the fly by parseTitle the first time.
		$title = $this->getTitle();
		$wgTitle = $title;

		$request = $this->getContext()->getRequest();
		// Set DB query expectations for this HTTP request
		$trxLimits = $this->getConfig( MainConfigNames::TrxProfilerLimits );
		$trxProfiler = Profiler::instance()->getTransactionProfiler();
		$trxProfiler->setLogger( LoggerFactory::getInstance( 'rdbms' ) );
		$trxProfiler->setStatsFactory( $this->getStatsFactory() );
		$trxProfiler->setRequestMethod( $request->getMethod() );
		if ( $request->hasSafeMethod() ) {
			$trxProfiler->setExpectations( $trxLimits['GET'], __METHOD__ );
		} else {
			$trxProfiler->setExpectations( $trxLimits['POST'], __METHOD__ );
		}

		if ( $this->maybeDoHttpsRedirect() ) {
			return;
		}

		$context = $this->getContext();
		$output = $context->getOutput();

		// NOTE: HTMLFileCache::useFileCache() is not used in WMF production but is
		//       here to provide third-party wikis with a way to enable caching for
		//       "view" and "history" actions. It's triggered by the use of $wgUseFileCache
		//       when set to true in LocalSettings.php.
		if ( $title->canExist() && HTMLFileCache::useFileCache( $context ) ) {
			// getAction() may trigger DB queries, so avoid eagerly initializing it if possible.
			// This reduces the cost of requests that exit early due to tryNormaliseRedirect()
			// or a MediaWikiPerformAction / BeforeInitialize hook handler.
			$action = $this->getAction();
			// Try low-level file cache hit
			$cache = new HTMLFileCache( $title, $action );
			if ( $cache->isCacheGood( /* Assume up to date */ ) ) {
				// Check incoming headers to see if client has this cached
				$timestamp = $cache->cacheTimestamp();
				if ( !$output->checkLastModified( $timestamp ) ) {
					$cache->loadFromFileCache( $context );
				}
				// Do any stats increment/watchlist stuff, assuming user is viewing the
				// latest revision (which should always be the case for file cache)
				$context->getWikiPage()->doViewUpdates( $context->getAuthority() );
				// Tell OutputPage that output is taken care of
				$output->disable();

				return;
			}
		}

		try {
			// Actually do the work of the request and build up any output
			$this->performRequest();
		} catch ( ErrorPageError $e ) {
			// TODO: Should ErrorPageError::report accept a OutputPage parameter?
			$e->report( ErrorPageError::STAGE_OUTPUT );
			$output->considerCacheSettingsFinal();
			// T64091: while exceptions are convenient to bubble up GUI errors,
			// they are not internal application faults. As with normal requests, this
			// should commit, print the output, do deferred updates, jobs, and profiling.
		}

		$this->prepareForOutput();

		// Ask OutputPage/Skin to stage the output (HTTP response body and headers).
		// Flush the output to the client unless an exception occurred.
		// Note that the OutputPage object in $context may have been replaced,
		// so better fetch it again here.
		$output = $context->getOutput();
		$this->outputResponsePayload( $output->output( true ) );
	}

	/**
	 * If the stars are suitably aligned, do an HTTP->HTTPS redirect
	 *
	 * Note: Do this after $wgTitle is setup, otherwise the hooks run from
	 * isRegistered() will do all sorts of weird stuff.
	 *
	 * @return bool True if the redirect was done. Handling of the request
	 *   should be aborted. False if no redirect was done.
	 */
	protected function maybeDoHttpsRedirect() {
		if ( !$this->shouldDoHttpRedirect() ) {
			return false;
		}

		$context = $this->getContext();
		$request = $context->getRequest();
		$oldUrl = $request->getFullRequestURL();
		$redirUrl = preg_replace( '#^http://#', 'https://', $oldUrl );

		if ( $request->wasPosted() ) {
			// This is weird and we'd hope it almost never happens. This
			// means that a POST came in via HTTP and policy requires us
			// redirecting to HTTPS. It's likely such a request is going
			// to fail due to post data being lost, but let's try anyway
			// and just log the instance.

			// @todo FIXME: See if we could issue a 307 or 308 here, need
			// to see how clients (automated & browser) behave when we do
			wfDebugLog( 'RedirectedPosts', "Redirected from HTTP to HTTPS: $oldUrl" );
		}
		// Setup dummy Title, otherwise OutputPage::redirect will fail
		$title = Title::newFromText( 'REDIR', NS_MAIN );
		$context->setTitle( $title );
		// Since we only do this redir to change proto, always send a vary header
		$output = $context->getOutput();
		$output->addVaryHeader( 'X-Forwarded-Proto' );
		$output->redirect( $redirUrl );
		$output->output();

		return true;
	}

	protected function doPrepareForOutput() {
		parent::doPrepareForOutput();

		// If needed, push a deferred update to run jobs after the output is sent
		$this->schedulePostSendJobs();
	}

	protected function schedulePostSendJobs() {
		// Recursion guard for $wgRunJobsAsync
		if ( $this->getTitle()->isSpecial( 'RunJobs' ) ) {
			return;
		}

		parent::schedulePostSendJobs();
	}

	/**
	 * Parse the request to get the Title object
	 *
	 * @throws MalformedTitleException If a title has been provided by the user, but is invalid.
	 * @param WebRequest $request
	 * @return Title Title object to be $wgTitle
	 */
	protected function parseTitle( $request ) {
		$curid = $request->getInt( 'curid' );
		$title = $request->getText( 'title' );

		$ret = null;
		if ( $curid ) {
			// URLs like this are generated by RC, because rc_title isn't always accurate
			$ret = Title::newFromID( $curid );
		}
		if ( $ret === null ) {
			$ret = Title::newFromURL( $title );
			if ( $ret !== null ) {
				// Alias NS_MEDIA page URLs to NS_FILE...we only use NS_MEDIA
				// in wikitext links to tell Parser to make a direct file link
				if ( $ret->getNamespace() === NS_MEDIA ) {
					$ret = Title::makeTitle( NS_FILE, $ret->getDBkey() );
				}
				// Check variant links so that interwiki links don't have to worry
				// about the possible different language variants
				$services = $this->getServiceContainer();
				$languageConverter = $services
					->getLanguageConverterFactory()
					->getLanguageConverter( $services->getContentLanguage() );
				if ( $languageConverter->hasVariants() && !$ret->exists() ) {
					$languageConverter->findVariantLink( $title, $ret );
				}
			}
		}

		// If title is not provided, always allow oldid and diff to set the title.
		// If title is provided, allow oldid and diff to override the title, unless
		// we are talking about a special page which might use these parameters for
		// other purposes.
		if ( $ret === null || !$ret->isSpecialPage() ) {
			// We can have urls with just ?diff=,?oldid= or even just ?diff=
			$oldid = $request->getInt( 'oldid' );
			$oldid = $oldid ?: $request->getInt( 'diff' );
			// Allow oldid to override a changed or missing title
			if ( $oldid ) {
				$revRecord = $this->getServiceContainer()
					->getRevisionLookup()
					->getRevisionById( $oldid );
				if ( $revRecord ) {
					$ret = Title::newFromPageIdentity( $revRecord->getPage() );
				}
			}
		}

		if ( $ret === null && $request->getCheck( 'search' ) ) {
			// Compatibility with old search URLs which didn't use Special:Search
			// Just check for presence here, so blank requests still
			// show the search page when using ugly URLs (T10054).
			$ret = SpecialPage::getTitleFor( 'Search' );
		}

		if ( $ret === null || !$ret->isSpecialPage() ) {
			// Compatibility with old URLs for Special:RevisionDelete/Special:EditTags (T323338)
			$actionName = $request->getRawVal( 'action' );
			if (
				$actionName === 'revisiondelete' ||
				( $actionName === 'historysubmit' && $request->getBool( 'revisiondelete' ) )
			) {
				$ret = SpecialPage::getTitleFor( 'Revisiondelete' );
			} elseif (
				$actionName === 'editchangetags' ||
				( $actionName === 'historysubmit' && $request->getBool( 'editchangetags' ) )
			) {
				$ret = SpecialPage::getTitleFor( 'EditTags' );
			}
		}

		// Use the main page as default title if nothing else has been provided
		if ( $ret === null
			&& strval( $title ) === ''
			&& !$request->getCheck( 'curid' )
			&& $request->getRawVal( 'action' ) !== 'delete'
		) {
			$ret = Title::newMainPage();
		}

		if ( $ret === null || ( $ret->getDBkey() == '' && !$ret->isExternal() ) ) {
			// If we get here, we definitely don't have a valid title; throw an exception.
			// Try to get detailed invalid title exception first, fall back to MalformedTitleException.
			Title::newFromTextThrow( $title );
			throw new MalformedTitleException( 'badtitletext', $title );
		}

		return $ret;
	}

	/**
	 * Get the Title object that we'll be acting on, as specified in the WebRequest
	 * @return Title
	 */
	public function getTitle() {
		$context = $this->getContext();

		if ( !$context->hasTitle() ) {
			try {
				$context->setTitle( $this->parseTitle( $context->getRequest() ) );
			} catch ( MalformedTitleException $ex ) {
				$context->setTitle( SpecialPage::getTitleFor( 'Badtitle' ) );
			}
		}
		return $context->getTitle();
	}

	/**
	 * Returns the name of the action that will be executed.
	 *
	 * @note This is public for the benefit of extensions that implement
	 * the BeforeInitialize or MediaWikiPerformAction hooks.
	 *
	 * @return string Action
	 */
	public function getAction(): string {
		return $this->getContext()->getActionName();
	}

	/**
	 * Performs the request.
	 * - bad titles
	 * - read restriction
	 * - local interwiki redirects
	 * - redirect loop
	 * - special pages
	 * - normal pages
	 *
	 * @throws PermissionsError|BadTitleError|HttpError
	 * @return void
	 */
	protected function performRequest() {
		// phpcs:ignore MediaWiki.Usage.DeprecatedGlobalVariables.Deprecated$wgTitle
		global $wgTitle;

		$context = $this->getContext();

		$request = $context->getRequest();
		$output = $context->getOutput();

		if ( $request->getRawVal( 'printable' ) === 'yes' ) {
			$output->setPrintable();
		}

		$user = $context->getUser();
		$title = $context->getTitle();
		$requestTitle = $title;

		$userOptionsLookup = $this->getServiceContainer()->getUserOptionsLookup();
		if ( $userOptionsLookup->getBoolOption( $user, 'forcesafemode' ) ) {
			$request->setVal( 'safemode', '1' );
		}

		$this->getHookRunner()->onBeforeInitialize( $title, null, $output, $user, $request, $this );

		// Invalid titles. T23776: The interwikis must redirect even if the page name is empty.
		if ( $title === null || ( $title->getDBkey() == '' && !$title->isExternal() )
			|| $title->isSpecial( 'Badtitle' )
		) {
			$context->setTitle( SpecialPage::getTitleFor( 'Badtitle' ) );
			try {
				$this->parseTitle( $request );
			} catch ( MalformedTitleException $ex ) {
				throw new BadTitleError( $ex );
			}
			throw new BadTitleError();
		}

		// Check user's permissions to read this page.
		// We have to check here to catch special pages etc.
		// We will check again in Article::view().
		$permissionStatus = PermissionStatus::newEmpty();
		if ( !$context->getAuthority()->authorizeRead( 'read', $title, $permissionStatus ) ) {
			// T34276: allowing the skin to generate output with $wgTitle or
			// $context->title set to the input title would allow anonymous users to
			// determine whether a page exists, potentially leaking private data. In fact, the
			// curid and oldid request  parameters would allow page titles to be enumerated even
			// when they are not guessable. So we reset the title to Special:Badtitle before the
			// permissions error is displayed.

			// The skin mostly uses $context->getTitle() these days, but some extensions
			// still use $wgTitle.
			$badTitle = SpecialPage::getTitleFor( 'Badtitle' );
			$context->setTitle( $badTitle );
			$wgTitle = $badTitle;

			throw new PermissionsError( 'read', $permissionStatus );
		}

		// Interwiki redirects
		if ( $title->isExternal() ) {
			$rdfrom = $request->getVal( 'rdfrom' );
			if ( $rdfrom ) {
				$url = $title->getFullURL( [ 'rdfrom' => $rdfrom ] );
			} else {
				$query = $request->getQueryValues();
				unset( $query['title'] );
				$url = $title->getFullURL( $query );
			}
			// Check for a redirect loop
			if ( $url !== $request->getFullRequestURL() && $title->isLocal() ) {
				// 301 so google et al report the target as the actual url.
				$output->redirect( $url, 301 );
			} else {
				$context->setTitle( SpecialPage::getTitleFor( 'Badtitle' ) );
				try {
					$this->parseTitle( $request );
				} catch ( MalformedTitleException $ex ) {
					throw new BadTitleError( $ex );
				}
				throw new BadTitleError();
			}
			// Handle any other redirects.
			// Redirect loops, titleless URL, $wgUsePathInfo URLs, and URLs with a variant
		} elseif ( !$this->tryNormaliseRedirect( $title ) ) {
			// Prevent information leak via Special:MyPage et al (T109724)
			$spFactory = $this->getServiceContainer()->getSpecialPageFactory();
			if ( $title->isSpecialPage() ) {
				$specialPage = $spFactory->getPage( $title->getDBkey() );
				if ( $specialPage instanceof RedirectSpecialPage ) {
					$specialPage->setContext( $context );
					if ( $this->getConfig( MainConfigNames::HideIdentifiableRedirects )
						&& $specialPage->personallyIdentifiableTarget()
					) {
						[ , $subpage ] = $spFactory->resolveAlias( $title->getDBkey() );
						$target = $specialPage->getRedirect( $subpage );
						// Target can also be true. We let that case fall through to normal processing.
						if ( $target instanceof Title ) {
							if ( $target->isExternal() ) {
								// Handle interwiki redirects
								$target = SpecialPage::getTitleFor(
									'GoToInterwiki',
									'force/' . $target->getPrefixedDBkey()
								);
							}

							$query = $specialPage->getRedirectQuery( $subpage ) ?: [];
							$derivateRequest = new DerivativeRequest( $request, $query );
							$derivateRequest->setRequestURL( $request->getRequestURL() );
							$context->setRequest( $derivateRequest );
							// Do not varnish cache these. May vary even for anons
							$output->lowerCdnMaxage( 0 );
							// NOTE: This also clears any action cache.
							// Action should not have been computed yet, but if it was,
							// we reset it because special pages only support "view".
							$context->setTitle( $target );
							$wgTitle = $target;
							$title = $target;
							$output->addJsConfigVars( [
								'wgInternalRedirectTargetUrl' => $target->getLinkURL( $query ),
							] );
							$output->addModules( 'mediawiki.action.view.redirect' );

							// If the title is invalid, redirect but show the correct bad title error - T297407
							if ( !$target->isValid() ) {
								try {
									$this->getServiceContainer()->getTitleParser()
										->parseTitle( $target->getPrefixedText() );
								} catch ( MalformedTitleException $ex ) {
									throw new BadTitleError( $ex );
								}
								throw new BadTitleError();
							}
						}
					}
				}
			}

			// Special pages ($title may have changed since if statement above)
			if ( $title->isSpecialPage() ) {
				// Actions that need to be made when we have a special pages
				$spFactory->executePath( $title, $context );
			} else {
				// ...otherwise treat it as an article view. The article
				// may still be a wikipage redirect to another article or URL.
				$article = $this->initializeArticle();
				if ( is_object( $article ) ) {
					$this->performAction( $article, $requestTitle );
				} elseif ( is_string( $article ) ) {
					$output->redirect( $article );
				} else {
					throw new UnexpectedValueException( "Shouldn't happen: MediaWiki::initializeArticle()"
						. " returned neither an object nor a URL" );
				}
			}
			$output->considerCacheSettingsFinal();
		}
	}

	/**
	 * Handle redirects for uncanonical title requests.
	 *
	 * Handles:
	 * - Redirect loops.
	 * - No title in URL.
	 * - $wgUsePathInfo URLs.
	 * - URLs with a variant.
	 * - Other non-standard URLs (as long as they have no extra query parameters).
	 *
	 * Behaviour:
	 * - Normalise title values:
	 *   /wiki/Foo%20Bar -> /wiki/Foo_Bar
	 * - Normalise empty title:
	 *   /wiki/ -> /wiki/Main
	 *   /w/index.php?title= -> /wiki/Main
	 * - Don't redirect anything with query parameters other than 'title' or 'action=view'.
	 *
	 * @param Title $title
	 * @return bool True if a redirect was set.
	 * @throws HttpError
	 */
	protected function tryNormaliseRedirect( Title $title ): bool {
		$request = $this->getRequest();
		$output = $this->getOutput();

		if ( ( $request->getRawVal( 'action' ) ?? 'view' ) !== 'view'
			|| $request->wasPosted()
			|| ( $request->getCheck( 'title' )
				&& $title->getPrefixedDBkey() == $request->getText( 'title' ) )
			|| count( $request->getValueNames( [ 'action', 'title' ] ) )
			|| !$this->getHookRunner()->onTestCanonicalRedirect( $request, $title, $output )
		) {
			return false;
		}

		if ( $this->getConfig( MainConfigNames::MainPageIsDomainRoot ) && $request->getRequestURL() === '/' ) {
			return false;
		}

		$services = $this->getServiceContainer();

		if ( $title->isSpecialPage() ) {
			[ $name, $subpage ] = $services->getSpecialPageFactory()
				->resolveAlias( $title->getDBkey() );

			if ( $name ) {
				$title = SpecialPage::getTitleFor( $name, $subpage );
			}
		}
		// Redirect to canonical url, make it a 301 to allow caching
		$targetUrl = (string)$services->getUrlUtils()->expand( $title->getFullURL(), PROTO_CURRENT );
		if ( $targetUrl == $request->getFullRequestURL() ) {
			$message = "Redirect loop detected!\n\n" .
				"This means the wiki got confused about what page was " .
				"requested; this sometimes happens when moving a wiki " .
				"to a new server or changing the server configuration.\n\n";

			if ( $this->getConfig( MainConfigNames::UsePathInfo ) ) {
				$message .= "The wiki is trying to interpret the page " .
					"title from the URL path portion (PATH_INFO), which " .
					"sometimes fails depending on the web server. Try " .
					"setting \"\$wgUsePathInfo = false;\" in your " .
					"LocalSettings.php, or check that \$wgArticlePath " .
					"is correct.";
			} else {
				$message .= "Your web server was detected as possibly not " .
					"supporting URL path components (PATH_INFO) correctly; " .
					"check your LocalSettings.php for a customized " .
					"\$wgArticlePath setting and/or toggle \$wgUsePathInfo " .
					"to true.";
			}
			throw new HttpError( 500, $message );
		}
		$output->setCdnMaxage( 1200 );
		$output->redirect( $targetUrl, '301' );
		return true;
	}

	/**
	 * Initialize the main Article object for "standard" actions (view, etc)
	 * Create an Article object for the page, following redirects if needed.
	 *
	 * @return Article|string An Article, or a string to redirect to another URL
	 */
	protected function initializeArticle() {
		$context = $this->getContext();

		$title = $context->getTitle();
		$services = $this->getServiceContainer();
		if ( $context->canUseWikiPage() ) {
			// Optimization: Reuse the WikiPage instance from context, to avoid
			// repeat fetching or computation of data already loaded.
			$page = $context->getWikiPage();
		} else {
			// This case should not happen, but just in case.
			// @TODO: remove this or use an exception
			$page = $services->getWikiPageFactory()->newFromTitle( $title );
			$context->setWikiPage( $page );
			wfWarn( "RequestContext::canUseWikiPage() returned false" );
		}

		// Make GUI wrapper for the WikiPage
		$article = Article::newFromWikiPage( $page, $context );

		// Skip some unnecessary code if the content model doesn't support redirects
		// Use the page content model rather than invoking Title::getContentModel()
		// to avoid querying page data twice (T206498)
		if ( !$page->getContentHandler()->supportsRedirects() ) {
			return $article;
		}

		$request = $context->getRequest();

		// Namespace might change when using redirects
		// Check for redirects ...
		$action = $request->getRawVal( 'action' ) ?? 'view';
		$file = ( $page instanceof WikiFilePage ) ? $page->getFile() : null;
		if ( ( $action == 'view' || $action == 'render' ) // ... for actions that show content
			&& !$request->getCheck( 'oldid' ) // ... and are not old revisions
			&& !$request->getCheck( 'diff' ) // ... and not when showing diff
			&& $request->getRawVal( 'redirect' ) !== 'no' // ... unless explicitly told not to
			// ... and the article is not a non-redirect image page with associated file
			&& !( is_object( $file ) && $file->exists() && !$file->getRedirected() )
		) {
			// Give extensions a change to ignore/handle redirects as needed
			$ignoreRedirect = $target = false;

			$this->getHookRunner()->onInitializeArticleMaybeRedirect( $title, $request,
				// @phan-suppress-next-line PhanTypeMismatchArgument Type mismatch on pass-by-ref args
				$ignoreRedirect, $target, $article );
			$page = $article->getPage(); // reflect any hook changes

			// Follow redirects only for... redirects.
			// If $target is set, then a hook wanted to redirect.
			if ( !$ignoreRedirect && ( $target || $page->isRedirect() ) ) {
				// Is the target already set by an extension?
				$target = $target ?: $page->followRedirect();
				if ( is_string( $target ) && !$this->getConfig( MainConfigNames::DisableHardRedirects ) ) {
					// we'll need to redirect
					return $target;
				}
				if ( is_object( $target ) ) {
					// Rewrite environment to redirected article
					$rpage = $services->getWikiPageFactory()->newFromTitle( $target );
					$rpage->loadPageData();
					if ( $rpage->exists() || ( is_object( $file ) && !$file->isLocal() ) ) {
						$rarticle = Article::newFromWikiPage( $rpage, $context );
						$rarticle->setRedirectedFrom( $title );

						$article = $rarticle;
						// NOTE: This also clears any action cache
						$context->setTitle( $target );
						$context->setWikiPage( $article->getPage() );
					}
				}
			}
		}

		return $article;
	}

	/**
	 * Perform one of the "standard" actions
	 *
	 * @param Article $article
	 * @param Title $requestTitle The original title, before any redirects were applied
	 */
	protected function performAction( Article $article, Title $requestTitle ) {
		$request = $this->getRequest();
		$output = $this->getOutput();
		$title = $this->getTitle();
		$user = $this->getUser();

		if ( !$this->getHookRunner()->onMediaWikiPerformAction(
			$output, $article, $title, $user, $request, $this )
		) {
			return;
		}

		$t = microtime( true );
		$actionName = $this->getAction();
		$services = $this->getServiceContainer();

		$action = $services->getActionFactory()->getAction( $actionName, $article, $this->getContext() );
		if ( $action instanceof Action ) {
			ProfilingContext::singleton()->init( MW_ENTRY_POINT, $actionName );

			// Check read permissions
			if ( $action->needsReadRights() && !$user->isAllowed( 'read' ) ) {
				throw new PermissionsError( 'read' );
			}

			// Narrow DB query expectations for this HTTP request
			if ( $request->wasPosted() && !$action->doesWrites() ) {
				$trxProfiler = Profiler::instance()->getTransactionProfiler();
				$trxLimits = $this->getConfig( MainConfigNames::TrxProfilerLimits );
				$trxProfiler->setExpectations( $trxLimits['POST-nonwrite'], __METHOD__ );
			}

			// Let CDN cache things if we can purge them.
			// Also unconditionally cache page views.
			if ( $this->getConfig( MainConfigNames::UseCdn ) ) {
				$htmlCacheUpdater = $services->getHtmlCacheUpdater();
				if ( $request->matchURLForCDN( $htmlCacheUpdater->getUrls( $requestTitle ) ) ) {
					$output->setCdnMaxage( $this->getConfig( MainConfigNames::CdnMaxAge ) );
				} elseif ( $action instanceof ViewAction ) {
					$output->setCdnMaxage( 3600 );
				}
			}

			$action->show();

			$runTime = microtime( true ) - $t;

			$statAction = strtr( $actionName, '.', '_' );
			$services->getStatsFactory()->getTiming( 'action_executeTiming_seconds' )
				->setLabel( 'action', $statAction )
				->copyToStatsdAt( 'action.' . $statAction . '.executeTiming' )
				->observe( 1000 * $runTime );

			return;
		}

		// If we've not found out which action it is by now, it's unknown
		$output->setStatusCode( 404 );
		$output->showErrorPage( 'nosuchaction', 'nosuchactiontext' );
	}
}
