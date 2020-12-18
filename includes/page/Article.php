<?php
/**
 * User interface for page actions.
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

use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Edit\PreparedEdit;
use MediaWiki\HookContainer\ProtectedHookAccessorTrait;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\IDatabase;

/**
 * Class for viewing MediaWiki article and history.
 *
 * This maintains WikiPage functions for backwards compatibility.
 *
 * @todo Move and rewrite code to an Action class
 *
 * Note: edit user interface and cache support functions have been
 * moved to separate EditPage and HTMLFileCache classes.
 */
class Article implements Page {
	use ProtectedHookAccessorTrait;

	/**
	 * @var IContextSource|null The context this Article is executed in.
	 * If null, RequestContext::getMain() is used.
	 * @deprecated since 1.35, must be private, use {@link getContext}
	 */
	protected $mContext;

	/** @var WikiPage The WikiPage object of this instance */
	protected $mPage;

	/**
	 * @var ParserOptions|null ParserOptions object for $wgUser articles.
	 * Initialized by getParserOptions by calling $this->mPage->makeParserOptions().
	 */
	public $mParserOptions;

	/**
	 * @var Content|null Content of the main slot of $this->mRevisionRecord.
	 * @note This variable is read only, setting it has no effect.
	 *       Extensions that wish to override the output of Article::view should use a hook.
	 * @todo MCR: Remove in 1.33
	 * @deprecated since 1.32
	 * @since 1.21
	 */
	public $mContentObject;

	/**
	 * @var bool Is the target revision loaded? Set by fetchRevisionRecord().
	 *
	 * @deprecated since 1.32. Whether content has been loaded should not be relevant to
	 * code outside this class.
	 */
	public $mContentLoaded = false;

	/**
	 * @var int|null The oldid of the article that was requested to be shown,
	 * 0 for the current revision.
	 * @see $mRevIdFetched
	 */
	public $mOldId;

	/** @var Title|null Title from which we were redirected here, if any. */
	public $mRedirectedFrom = null;

	/** @var string|bool URL to redirect to or false if none */
	public $mRedirectUrl = false;

	/**
	 * @var int Revision ID of revision that was loaded.
	 * @see $mOldId
	 * @deprecated since 1.32, use getRevIdFetched() instead.
	 */
	public $mRevIdFetched = 0;

	/**
	 * @var Status|null represents the outcome of fetchRevisionRecord().
	 * $fetchResult->value is the RevisionRecord object, if the operation was successful.
	 *
	 * The information in $fetchResult is duplicated by the following deprecated public fields:
	 * $mRevIdFetched, $mContentLoaded (and $mContentObject) also typically duplicate
	 * information of the loaded revision, but may be overwritten by extensions or due to errors.
	 */
	private $fetchResult = null;

	/**
	 * @var ParserOutput|null|false The ParserOutput generated for viewing the page,
	 * initialized by view(). If no ParserOutput could be generated, this is set to false.
	 * @deprecated since 1.32
	 */
	public $mParserOutput = null;

	/**
	 * @var bool Whether render() was called. With the way subclasses work
	 * here, there doesn't seem to be any other way to stop calling
	 * OutputPage::enableSectionEditLinks() and still have it work as it did before.
	 */
	protected $viewIsRenderAction = false;

	/**
	 * @var LinkRenderer
	 */
	protected $linkRenderer;

	/**
	 * @var PermissionManager
	 */
	private $permManager;

	/**
	 * @var RevisionStore
	 */
	private $revisionStore;

	/*
	 * @var RevisionRecord|null Revision to be shown
	 *
	 * Initialized by getOldIDFromRequest() or fetchRevisionRecord(). Normally loaded from the
	 * database, but may be replaced by an extension, or be a fake representing an error message
	 * or some such. While the output of Article::view is typically based on this revision,
	 * it may be overwritten by error messages or replaced by extensions.
	 *
	 * Replaced $mRevision, which was public and is provided in a deprecated manner via
	 * __get and __set
	 */
	private $mRevisionRecord = null;

	/**
	 * @param Title $title
	 * @param int|null $oldId Revision ID, null to fetch from request, zero for current
	 */
	public function __construct( Title $title, $oldId = null ) {
		$this->mOldId = $oldId;
		$this->mPage = $this->newPage( $title );

		$services = MediaWikiServices::getInstance();
		$this->linkRenderer = $services->getLinkRenderer();
		$this->permManager = $services->getPermissionManager();
		$this->revisionStore = $services->getRevisionStore();
	}

	/**
	 * @param Title $title
	 * @return WikiPage
	 */
	protected function newPage( Title $title ) {
		return new WikiPage( $title );
	}

	/**
	 * Constructor from a page id
	 * @param int $id Article ID to load
	 * @return Article|null
	 */
	public static function newFromID( $id ) {
		$t = Title::newFromID( $id );
		return $t == null ? null : new static( $t );
	}

	/**
	 * Create an Article object of the appropriate class for the given page.
	 *
	 * @param Title $title
	 * @param IContextSource $context
	 * @return Article
	 */
	public static function newFromTitle( $title, IContextSource $context ) {
		if ( $title->getNamespace() == NS_MEDIA ) {
			// XXX: This should not be here, but where should it go?
			$title = Title::makeTitle( NS_FILE, $title->getDBkey() );
		}

		$page = null;
		Hooks::runner()->onArticleFromTitle( $title, $page, $context );
		if ( !$page ) {
			switch ( $title->getNamespace() ) {
				case NS_FILE:
					$page = new ImagePage( $title );
					break;
				case NS_CATEGORY:
					$page = new CategoryPage( $title );
					break;
				default:
					$page = new Article( $title );
			}
		}
		$page->setContext( $context );

		return $page;
	}

	/**
	 * Create an Article object of the appropriate class for the given page.
	 *
	 * @param WikiPage $page
	 * @param IContextSource $context
	 * @return Article
	 */
	public static function newFromWikiPage( WikiPage $page, IContextSource $context ) {
		$article = self::newFromTitle( $page->getTitle(), $context );
		$article->mPage = $page; // override to keep process cached vars
		return $article;
	}

	/**
	 * Get the page this view was redirected from
	 * @return Title|null
	 * @since 1.28
	 */
	public function getRedirectedFrom() {
		return $this->mRedirectedFrom;
	}

	/**
	 * Tell the page view functions that this view was redirected
	 * from another page on the wiki.
	 * @param Title $from
	 */
	public function setRedirectedFrom( Title $from ) {
		$this->mRedirectedFrom = $from;
	}

	/**
	 * Get the title object of the article
	 *
	 * @return Title Title object of this page
	 */
	public function getTitle() {
		return $this->mPage->getTitle();
	}

	/**
	 * Get the WikiPage object of this instance
	 *
	 * @since 1.19
	 * @return WikiPage
	 */
	public function getPage() {
		return $this->mPage;
	}

	/**
	 * Clear the object
	 */
	public function clear() {
		$this->mContentLoaded = false;

		$this->mRedirectedFrom = null; # Title object if set
		$this->mRevIdFetched = 0;
		$this->mRedirectUrl = false;
		$this->mRevisionRecord = null;
		$this->mContentObject = null;
		$this->fetchResult = null;

		// TODO hard-deprecate direct access to public fields

		$this->mPage->clear();
	}

	/**
	 * Returns a Content object representing the pages effective display content,
	 * not necessarily the revision's content!
	 *
	 * Note that getContent does not follow redirects anymore.
	 * If you need to fetch redirectable content easily, try
	 * the shortcut in WikiPage::getRedirectTarget()
	 *
	 * This function has side effects! Do not use this function if you
	 * only want the real revision text if any.
	 *
	 * @deprecated since 1.32, use fetchRevisionRecord() instead.
	 *
	 * @return Content
	 *
	 * @since 1.21
	 */
	protected function getContentObject() {
		if ( $this->mPage->getId() === 0 ) {
			$content = $this->getSubstituteContent();
		} else {
			$this->fetchContentObject();
			$content = $this->mContentObject;
		}

		return $content;
	}

	/**
	 * Returns Content object to use when the page does not exist.
	 *
	 * @return Content
	 */
	private function getSubstituteContent() {
		# If this is a MediaWiki:x message, then load the messages
		# and return the message value for x.
		if ( $this->getTitle()->getNamespace() == NS_MEDIAWIKI ) {
			$text = $this->getTitle()->getDefaultMessageText();
			if ( $text === false ) {
				$text = '';
			}

			$content = ContentHandler::makeContent( $text, $this->getTitle() );
		} else {
			$message = $this->getContext()->getUser()->isLoggedIn() ? 'noarticletext' : 'noarticletextanon';
			$content = new MessageContent( $message, null );
		}

		return $content;
	}

	/**
	 * Returns ParserOutput to use when a page does not exist. In some cases, we still want to show
	 * "virtual" content, e.g. in the MediaWiki namespace, or in the File namespace for non-local
	 * files.
	 *
	 * @param ParserOptions $options
	 *
	 * @return ParserOutput
	 */
	protected function getEmptyPageParserOutput( ParserOptions $options ) {
		$content = $this->getSubstituteContent();

		return $content->getParserOutput( $this->getTitle(), 0, $options );
	}

	/**
	 * @see getOldIDFromRequest()
	 * @see getRevIdFetched()
	 *
	 * @return int The oldid of the article that is was requested in the constructor or via the
	 *         context's WebRequest.
	 */
	public function getOldID() {
		if ( $this->mOldId === null ) {
			$this->mOldId = $this->getOldIDFromRequest();
		}

		return $this->mOldId;
	}

	/**
	 * Sets $this->mRedirectUrl to a correct URL if the query parameters are incorrect
	 *
	 * @return int The old id for the request
	 */
	public function getOldIDFromRequest() {
		$this->mRedirectUrl = false;

		$request = $this->getContext()->getRequest();
		$oldid = $request->getIntOrNull( 'oldid' );

		if ( $oldid === null ) {
			return 0;
		}

		if ( $oldid !== 0 ) {
			# Load the given revision and check whether the page is another one.
			# In that case, update this instance to reflect the change.
			if ( $oldid === $this->mPage->getLatest() ) {
				$this->mRevisionRecord = $this->mPage->getRevisionRecord();
			} else {
				$this->mRevisionRecord = $this->revisionStore->getRevisionById( $oldid );
				if ( $this->mRevisionRecord !== null ) {
					$revPageId = $this->mRevisionRecord->getPageId();
					// Revision title doesn't match the page title given?
					if ( $this->mPage->getId() != $revPageId ) {
						$function = get_class( $this->mPage ) . '::newFromID';
						$this->mPage = $function( $revPageId );
					}
				}
			}
		}

		$oldRev = $this->mRevisionRecord;
		if ( $request->getVal( 'direction' ) == 'next' ) {
			$nextid = 0;
			if ( $oldRev ) {
				$nextRev = $this->revisionStore->getNextRevision( $oldRev );
				if ( $nextRev ) {
					$nextid = $nextRev->getId();
				}
			}
			if ( $nextid ) {
				$oldid = $nextid;
				$this->mRevisionRecord = null;
			} else {
				$this->mRedirectUrl = $this->getTitle()->getFullURL( 'redirect=no' );
			}
		} elseif ( $request->getVal( 'direction' ) == 'prev' ) {
			$previd = 0;
			if ( $oldRev ) {
				$prevRev = $this->revisionStore->getPreviousRevision( $oldRev );
				if ( $prevRev ) {
					$previd = $prevRev->getId();
				}
			}
			if ( $previd ) {
				$oldid = $previd;
				$this->mRevisionRecord = null;
			}
		}

		$this->mRevIdFetched = $this->mRevisionRecord ? $this->mRevisionRecord->getId() : 0;

		return $oldid;
	}

	/**
	 * Get text content object
	 * Does *NOT* follow redirects.
	 * @todo When is this null?
	 * @deprecated since 1.32, use fetchRevisionRecord() instead.
	 *
	 * @note Code that wants to retrieve page content from the database should
	 * use WikiPage::getContent().
	 *
	 * @return Content|null|bool
	 *
	 * @since 1.21
	 */
	protected function fetchContentObject() {
		if ( !$this->mContentLoaded ) {
			$this->fetchRevisionRecord();
		}

		return $this->mContentObject;
	}

	/**
	 * Fetches the revision to work on.
	 * The revision is typically loaded from the database, but may also be a fake representing
	 * an error message or content supplied by an extension. Refer to $this->fetchResult for
	 * the revision actually loaded from the database, and any errors encountered while doing
	 * that.
	 *
	 * Public since 1.35
	 *
	 * @return RevisionRecord|null
	 */
	public function fetchRevisionRecord() {
		if ( $this->fetchResult ) {
			return $this->mRevisionRecord;
		}

		$this->mContentLoaded = true;
		$this->mContentObject = null;

		$oldid = $this->getOldID();

		// $this->mRevisionRecord might already be fetched by getOldIDFromRequest()
		if ( !$this->mRevisionRecord ) {
			if ( !$oldid ) {
				$this->mRevisionRecord = $this->mPage->getRevisionRecord();

				if ( !$this->mRevisionRecord ) {
					wfDebug( __METHOD__ . " failed to find page data for title " .
						$this->getTitle()->getPrefixedText() );

					// Just for sanity, output for this case is done by showMissingArticle().
					$this->fetchResult = Status::newFatal( 'noarticletext' );
					$this->applyContentOverride( $this->makeFetchErrorContent() );
					return null;
				}
			} else {
				$this->mRevisionRecord = $this->revisionStore->getRevisionById( $oldid );

				if ( !$this->mRevisionRecord ) {
					wfDebug( __METHOD__ . " failed to load revision, rev_id $oldid" );

					$this->fetchResult = Status::newFatal( 'missing-revision', $oldid );
					$this->applyContentOverride( $this->makeFetchErrorContent() );
					return null;
				}
			}
		}

		$this->mRevIdFetched = $this->mRevisionRecord->getId();
		$this->fetchResult = Status::newGood( $this->mRevisionRecord );

		if ( !RevisionRecord::userCanBitfield(
			$this->mRevisionRecord->getVisibility(),
			RevisionRecord::DELETED_TEXT,
			$this->getContext()->getUser()
		) ) {
			wfDebug( __METHOD__ . " failed to retrieve content of revision " .
				$this->mRevisionRecord->getId() );

			// Just for sanity, output for this case is done by showDeletedRevisionHeader().
			$this->fetchResult = Status::newFatal( 'rev-deleted-text-permission' );
			$this->applyContentOverride( $this->makeFetchErrorContent() );
			return null;
		}

		// For B/C only
		$this->mContentObject = $this->mRevisionRecord->getContent(
			SlotRecord::MAIN,
			RevisionRecord::FOR_THIS_USER,
			$this->getContext()->getUser()
		);

		return $this->mRevisionRecord;
	}

	/**
	 * Returns a Content object representing any error in $this->fetchContent, or null
	 * if there is no such error.
	 *
	 * @return Content|null
	 */
	private function makeFetchErrorContent() {
		if ( !$this->fetchResult || $this->fetchResult->isOK() ) {
			return null;
		}

		return new MessageContent( $this->fetchResult->getMessage() );
	}

	/**
	 * Applies a content override by constructing a fake Revision object and assigning
	 * it to mRevisionRecord. The fake revision will not have a user, timestamp or summary set.
	 *
	 * @todo This mechanism was created mainly to accommodate extensions that use the
	 * ArticleAfterFetchContentObject. fetchRevisionRecord() presently also uses this mechanism
	 * to report errors, but that could be changed to use $this->fetchResult instead.
	 *
	 * @todo the ArticleAfterFetchContentObject hook was removed; check if this is still needed
	 *
	 * @param Content $override Content to be used instead of the actual page content,
	 *        coming from an extension or representing an error message.
	 */
	private function applyContentOverride( Content $override ) {
		// Construct a fake revision
		$rev = new MutableRevisionRecord( $this->getTitle() );
		$rev->setContent( SlotRecord::MAIN, $override );

		$this->mRevisionRecord = $rev;

		// For B/C only
		$this->mContentObject = $override;
	}

	/**
	 * Returns true if the currently-referenced revision is the current edit
	 * to this page (and it exists).
	 * @return bool
	 */
	public function isCurrent() {
		# If no oldid, this is the current version.
		if ( $this->getOldID() == 0 ) {
			return true;
		}

		return $this->mPage->exists() &&
			$this->mRevisionRecord &&
			$this->mRevisionRecord->isCurrent();
	}

	/**
	 * Get the fetched Revision object depending on request parameters or null
	 * on failure. The revision returned may be a fake representing an error message or
	 * wrapping content supplied by an extension. Refer to $this->fetchResult for the
	 * revision actually loaded from the database.
	 *
	 * @deprecated since 1.35
	 *
	 * @since 1.19
	 * @return Revision|null
	 */
	public function getRevisionFetched() {
		wfDeprecated( __METHOD__, '1.35' );
		$revRecord = $this->fetchRevisionRecord();

		return $revRecord ? new Revision( $revRecord ) : null;
	}

	/**
	 * Use this to fetch the rev ID used on page views
	 *
	 * Before fetchRevisionRecord was called, this returns the page's latest revision,
	 * regardless of what getOldID() returns.
	 *
	 * @return int Revision ID of last article revision
	 */
	public function getRevIdFetched() {
		if ( $this->fetchResult && $this->fetchResult->isOK() ) {
			return $this->fetchResult->value->getId();
		} else {
			return $this->mPage->getLatest();
		}
	}

	/**
	 * This is the default action of the index.php entry point: just view the
	 * page of the given title.
	 */
	public function view() {
		global $wgUseFileCache, $wgCdnMaxageStale;

		# Get variables from query string
		# As side effect this will load the revision and update the title
		# in a revision ID is passed in the request, so this should remain
		# the first call of this method even if $oldid is used way below.
		$oldid = $this->getOldID();

		$user = $this->getContext()->getUser();
		# Another whitelist check in case getOldID() is altering the title
		$permErrors = $this->permManager->getPermissionErrors(
			'read',
			$user,
			$this->getTitle()
		);
		if ( count( $permErrors ) ) {
			wfDebug( __METHOD__ . ": denied on secondary read check" );
			throw new PermissionsError( 'read', $permErrors );
		}

		$outputPage = $this->getContext()->getOutput();
		# getOldID() may as well want us to redirect somewhere else
		if ( $this->mRedirectUrl ) {
			$outputPage->redirect( $this->mRedirectUrl );
			wfDebug( __METHOD__ . ": redirecting due to oldid" );

			return;
		}

		# If we got diff in the query, we want to see a diff page instead of the article.
		if ( $this->getContext()->getRequest()->getCheck( 'diff' ) ) {
			wfDebug( __METHOD__ . ": showing diff page" );
			$this->showDiffPage();

			return;
		}

		# Set page title (may be overridden by DISPLAYTITLE)
		$outputPage->setPageTitle( $this->getTitle()->getPrefixedText() );

		$outputPage->setArticleFlag( true );
		# Allow frames by default
		$outputPage->allowClickjacking();

		$parserCache = MediaWikiServices::getInstance()->getParserCache();

		$parserOptions = $this->getParserOptions();
		$poOptions = [];
		# Render printable version, use printable version cache
		if ( $outputPage->isPrintable() ) {
			$parserOptions->setIsPrintable( true );
			$poOptions['enableSectionEditLinks'] = false;
			$outputPage->prependHTML(
				Html::warningBox(
					$outputPage->msg( 'printableversion-deprecated-warning' )->escaped()
				)
			);
		} elseif ( $this->viewIsRenderAction || !$this->isCurrent() ||
			!$this->permManager->quickUserCan( 'edit', $user, $this->getTitle() )
		) {
			$poOptions['enableSectionEditLinks'] = false;
		}

		# Try client and file cache
		if ( $oldid === 0 && $this->mPage->checkTouched() ) {
			# Try to stream the output from file cache
			if ( $wgUseFileCache && $this->tryFileCache() ) {
				wfDebug( __METHOD__ . ": done file cache" );
				# tell wgOut that output is taken care of
				$outputPage->disable();
				$this->mPage->doViewUpdates( $user, $oldid );

				return;
			}
		}

		# Should the parser cache be used?
		$useParserCache = $this->mPage->shouldCheckParserCache( $parserOptions, $oldid );
		wfDebug( 'Article::view using parser cache: ' . ( $useParserCache ? 'yes' : 'no' ) );
		if ( $user->getStubThreshold() ) {
			MediaWikiServices::getInstance()->getStatsdDataFactory()->increment( 'pcache_miss_stub' );
		}

		$this->showRedirectedFromHeader();
		$this->showNamespaceHeader();

		# Iterate through the possible ways of constructing the output text.
		# Keep going until $outputDone is set, or we run out of things to do.
		$pass = 0;
		$outputDone = false;
		$this->mParserOutput = false;

		while ( !$outputDone && ++$pass ) {
			switch ( $pass ) {
				case 1:
					$this->getHookRunner()->onArticleViewHeader( $this, $outputDone, $useParserCache );
					break;
				case 2:
					# Early abort if the page doesn't exist
					if ( !$this->mPage->exists() ) {
						wfDebug( __METHOD__ . ": showing missing article" );
						$this->showMissingArticle();
						$this->mPage->doViewUpdates( $user );
						return;
					}

					# Try the parser cache
					if ( $useParserCache ) {
						$this->mParserOutput = $parserCache->get( $this->getPage(), $parserOptions );

						if ( $this->mParserOutput !== false ) {
							if ( $oldid ) {
								wfDebug( __METHOD__ . ": showing parser cache contents for current rev permalink" );
								$this->setOldSubtitle( $oldid );
							} else {
								wfDebug( __METHOD__ . ": showing parser cache contents" );
							}
							$outputPage->addParserOutput( $this->mParserOutput, $poOptions );
							# Ensure that UI elements requiring revision ID have
							# the correct version information.
							$outputPage->setRevisionId( $this->mPage->getLatest() );
							# Preload timestamp to avoid a DB hit
							$cachedTimestamp = $this->mParserOutput->getTimestamp();
							if ( $cachedTimestamp !== null ) {
								$outputPage->setRevisionTimestamp( $cachedTimestamp );
								$this->mPage->setTimestamp( $cachedTimestamp );
							}
							$outputDone = true;
						}
					}
					break;
				case 3:
					# Are we looking at an old revision
					$rev = $this->fetchRevisionRecord();
					if ( $oldid && $this->fetchResult->isOK() ) {
						$this->setOldSubtitle( $oldid );

						if ( !$this->showDeletedRevisionHeader() ) {
							wfDebug( __METHOD__ . ": cannot view deleted revision" );
							return;
						}
					}

					# Ensure that UI elements requiring revision ID have
					# the correct version information.
					$outputPage->setRevisionId( $this->getRevIdFetched() );
					# Preload timestamp to avoid a DB hit
					$outputPage->setRevisionTimestamp( $this->mPage->getTimestamp() );

					# Pages containing custom CSS or JavaScript get special treatment
					if ( $this->getTitle()->isSiteConfigPage() || $this->getTitle()->isUserConfigPage() ) {
						$dir = $this->getContext()->getLanguage()->getDir();
						$lang = $this->getContext()->getLanguage()->getHtmlCode();

						$outputPage->wrapWikiMsg(
							"<div id='mw-clearyourcache' lang='$lang' dir='$dir' class='mw-content-$dir'>\n$1\n</div>",
							'clearyourcache'
						);
					} elseif ( !$this->getHookRunner()->onArticleRevisionViewCustom(
						$rev,
						$this->getTitle(),
						$oldid,
						$outputPage )
					) {
						// NOTE: sync with hooks called in DifferenceEngine::renderNewRevision()
						// Allow extensions do their own custom view for certain pages
						$outputDone = true;
					}
					break;
				case 4:
					# Run the parse, protected by a pool counter
					wfDebug( __METHOD__ . ": doing uncached parse" );

					$rev = $this->fetchRevisionRecord();
					$error = null;

					if ( $rev ) {
						$poolArticleView = new PoolWorkArticleView(
							$this->getPage(),
							$parserOptions,
							$this->getRevIdFetched(),
							$useParserCache,
							$rev,
							// permission checking was done earlier via showDeletedRevisionHeader()
							RevisionRecord::RAW
						);
						$ok = $poolArticleView->execute();
						$error = $poolArticleView->getError();
						$this->mParserOutput = $poolArticleView->getParserOutput() ?: null;

						# Cache stale ParserOutput object with a short expiry
						if ( $poolArticleView->getIsDirty() ) {
							$outputPage->setCdnMaxage( $wgCdnMaxageStale );
							$outputPage->setLastModified( $this->mParserOutput->getCacheTime() );
							$staleReason = $poolArticleView->getIsFastStale()
								? 'pool contention' : 'pool overload';
							$outputPage->addHTML( "<!-- parser cache is expired, " .
								"sending anyway due to $staleReason-->\n" );
						}
					} else {
						$ok = false;
					}

					if ( !$ok ) {
						if ( $error ) {
							$outputPage->clearHTML(); // for release() errors
							$outputPage->enableClientCache( false );
							$outputPage->setRobotPolicy( 'noindex,nofollow' );

							$errortext = $error->getWikiText(
								false, 'view-pool-error', $this->getContext()->getLanguage()
							);
							$outputPage->wrapWikiTextAsInterface( 'errorbox', $errortext );
						}
						# Connection or timeout error
						return;
					}

					if ( $this->mParserOutput ) {
						$outputPage->addParserOutput( $this->mParserOutput, $poOptions );
					}

					if ( $rev && $this->getRevisionRedirectTarget( $rev ) ) {
						$outputPage->addSubtitle( "<span id=\"redirectsub\">" .
							$this->getContext()->msg( 'redirectpagesub' )->parse() . "</span>" );
					}

					$outputDone = true;
					break;
				# Should be unreachable, but just in case...
				default:
					break 2;
			}
		}

		// Get the ParserOutput actually *displayed* here.
		// Note that $this->mParserOutput is the *current*/oldid version output.
		// Note that the ArticleViewHeader hook is allowed to set $outputDone to a
		// ParserOutput instance.
		$pOutput = ( $outputDone instanceof ParserOutput )
			? $outputDone // object fetched by hook
			: ( $this->mParserOutput ?: null ); // ParserOutput or null, avoid false

		# Adjust title for main page & pages with displaytitle
		if ( $pOutput ) {
			$this->adjustDisplayTitle( $pOutput );
		}

		# For the main page, overwrite the <title> element with the con-
		# tents of 'pagetitle-view-mainpage' instead of the default (if
		# that's not empty).
		# This message always exists because it is in the i18n files
		if ( $this->getTitle()->isMainPage() ) {
			$msg = wfMessage( 'pagetitle-view-mainpage' )->inContentLanguage();
			if ( !$msg->isDisabled() ) {
				$outputPage->setHTMLTitle( $msg->title( $this->getTitle() )->text() );
			}
		}

		# Use adaptive TTLs for CDN so delayed/failed purges are noticed less often.
		# This could use getTouched(), but that could be scary for major template edits.
		$outputPage->adaptCdnTTL( $this->mPage->getTimestamp(), IExpiringStore::TTL_DAY );

		# Check for any __NOINDEX__ tags on the page using $pOutput
		$policy = $this->getRobotPolicy( 'view', $pOutput ?: null );
		$outputPage->setIndexPolicy( $policy['index'] );
		$outputPage->setFollowPolicy( $policy['follow'] ); // FIXME: test this

		$this->showViewFooter();
		$this->mPage->doViewUpdates( $user, $oldid ); // FIXME: test this

		# Load the postEdit module if the user just saved this revision
		# See also EditPage::setPostEditCookie
		$request = $this->getContext()->getRequest();
		$cookieKey = EditPage::POST_EDIT_COOKIE_KEY_PREFIX . $this->getRevIdFetched();
		$postEdit = $request->getCookie( $cookieKey );
		if ( $postEdit ) {
			# Clear the cookie. This also prevents caching of the response.
			$request->response()->clearCookie( $cookieKey );
			$outputPage->addJsConfigVars( 'wgPostEdit', $postEdit );
			$outputPage->addModules( 'mediawiki.action.view.postEdit' ); // FIXME: test this
		}
	}

	/**
	 * @param RevisionRecord $revision
	 * @return null|Title
	 */
	private function getRevisionRedirectTarget( RevisionRecord $revision ) {
		// TODO: find a *good* place for the code that determines the redirect target for
		// a given revision!
		// NOTE: Use main slot content. Compare code in DerivedPageDataUpdater::revisionIsRedirect.
		$content = $revision->getContent( SlotRecord::MAIN );
		return $content ? $content->getRedirectTarget() : null;
	}

	/**
	 * Adjust title for pages with displaytitle, -{T|}- or language conversion
	 * @param ParserOutput $pOutput
	 */
	public function adjustDisplayTitle( ParserOutput $pOutput ) {
		$out = $this->getContext()->getOutput();

		# Adjust the title if it was set by displaytitle, -{T|}- or language conversion
		$titleText = $pOutput->getTitleText();
		if ( strval( $titleText ) !== '' ) {
			$out->setPageTitle( $titleText );
			$out->setDisplayTitle( $titleText );
		}
	}

	/**
	 * Show a diff page according to current request variables. For use within
	 * Article::view() only, other callers should use the DifferenceEngine class.
	 */
	protected function showDiffPage() {
		$request = $this->getContext()->getRequest();
		$user = $this->getContext()->getUser();
		$diff = $request->getVal( 'diff' );
		$rcid = $request->getVal( 'rcid' );
		$diffOnly = $request->getBool( 'diffonly', $user->getOption( 'diffonly' ) );
		$purge = $request->getVal( 'action' ) == 'purge';
		$unhide = $request->getInt( 'unhide' ) == 1;
		$oldid = $this->getOldID();

		$rev = $this->fetchRevisionRecord();

		if ( !$rev ) {
			$this->getContext()->getOutput()->setPageTitle( wfMessage( 'errorpagetitle' ) );
			$msg = $this->getContext()->msg( 'difference-missing-revision' )
				->params( $oldid )
				->numParams( 1 )
				->parseAsBlock();
			$this->getContext()->getOutput()->addHTML( $msg );
			return;
		}

		$contentHandler = MediaWikiServices::getInstance()
			->getContentHandlerFactory()
			->getContentHandler(
				$rev->getSlot( SlotRecord::MAIN, RevisionRecord::RAW )->getModel()
			);
		$de = $contentHandler->createDifferenceEngine(
			$this->getContext(),
			$oldid,
			$diff,
			$rcid,
			$purge,
			$unhide
		);
		$de->setSlotDiffOptions( [
			'diff-type' => $request->getVal( 'diff-type' )
		] );

		// DifferenceEngine directly fetched the revision:
		$this->mRevIdFetched = $de->getNewid();
		$de->showDiffPage( $diffOnly );

		// Run view updates for the newer revision being diffed (and shown
		// below the diff if not $diffOnly).
		list( $old, $new ) = $de->mapDiffPrevNext( $oldid, $diff );
		// New can be false, convert it to 0 - this conveniently means the latest revision
		$this->mPage->doViewUpdates( $user, (int)$new );
	}

	/**
	 * Get the robot policy to be used for the current view
	 * @param string $action The action= GET parameter
	 * @param ParserOutput|null $pOutput
	 * @return array The policy that should be set
	 * @todo actions other than 'view'
	 */
	public function getRobotPolicy( $action, ParserOutput $pOutput = null ) {
		global $wgArticleRobotPolicies, $wgNamespaceRobotPolicies, $wgDefaultRobotPolicy;

		$ns = $this->getTitle()->getNamespace();

		# Don't index user and user talk pages for blocked users (T13443)
		if ( ( $ns == NS_USER || $ns == NS_USER_TALK ) && !$this->getTitle()->isSubpage() ) {
			$specificTarget = null;
			$vagueTarget = null;
			$titleText = $this->getTitle()->getText();
			if ( IPUtils::isValid( $titleText ) ) {
				$vagueTarget = $titleText;
			} else {
				$specificTarget = $titleText;
			}
			if ( DatabaseBlock::newFromTarget( $specificTarget, $vagueTarget ) instanceof DatabaseBlock ) {
				return [
					'index' => 'noindex',
					'follow' => 'nofollow'
				];
			}
		}

		if ( $this->mPage->getId() === 0 || $this->getOldID() ) {
			# Non-articles (special pages etc), and old revisions
			return [
				'index' => 'noindex',
				'follow' => 'nofollow'
			];
		} elseif ( $this->getContext()->getOutput()->isPrintable() ) {
			# Discourage indexing of printable versions, but encourage following
			return [
				'index' => 'noindex',
				'follow' => 'follow'
			];
		} elseif ( $this->getContext()->getRequest()->getInt( 'curid' ) ) {
			# For ?curid=x urls, disallow indexing
			return [
				'index' => 'noindex',
				'follow' => 'follow'
			];
		}

		# Otherwise, construct the policy based on the various config variables.
		$policy = self::formatRobotPolicy( $wgDefaultRobotPolicy );

		if ( isset( $wgNamespaceRobotPolicies[$ns] ) ) {
			# Honour customised robot policies for this namespace
			$policy = array_merge(
				$policy,
				self::formatRobotPolicy( $wgNamespaceRobotPolicies[$ns] )
			);
		}
		if ( $this->getTitle()->canUseNoindex() && is_object( $pOutput ) && $pOutput->getIndexPolicy() ) {
			# __INDEX__ and __NOINDEX__ magic words, if allowed. Incorporates
			# a final sanity check that we have really got the parser output.
			$policy = array_merge(
				$policy,
				[ 'index' => $pOutput->getIndexPolicy() ]
			);
		}

		if ( isset( $wgArticleRobotPolicies[$this->getTitle()->getPrefixedText()] ) ) {
			# (T16900) site config can override user-defined __INDEX__ or __NOINDEX__
			$policy = array_merge(
				$policy,
				self::formatRobotPolicy( $wgArticleRobotPolicies[$this->getTitle()->getPrefixedText()] )
			);
		}

		return $policy;
	}

	/**
	 * Converts a String robot policy into an associative array, to allow
	 * merging of several policies using array_merge().
	 * @param array|string $policy Returns empty array on null/false/'', transparent
	 *   to already-converted arrays, converts string.
	 * @return array 'index' => \<indexpolicy\>, 'follow' => \<followpolicy\>
	 */
	public static function formatRobotPolicy( $policy ) {
		if ( is_array( $policy ) ) {
			return $policy;
		} elseif ( !$policy ) {
			return [];
		}

		$policy = explode( ',', $policy );
		$policy = array_map( 'trim', $policy );

		$arr = [];
		foreach ( $policy as $var ) {
			if ( in_array( $var, [ 'index', 'noindex' ] ) ) {
				$arr['index'] = $var;
			} elseif ( in_array( $var, [ 'follow', 'nofollow' ] ) ) {
				$arr['follow'] = $var;
			}
		}

		return $arr;
	}

	/**
	 * If this request is a redirect view, send "redirected from" subtitle to
	 * the output. Returns true if the header was needed, false if this is not
	 * a redirect view. Handles both local and remote redirects.
	 *
	 * @return bool
	 */
	public function showRedirectedFromHeader() {
		global $wgRedirectSources;

		$context = $this->getContext();
		$outputPage = $context->getOutput();
		$request = $context->getRequest();
		$rdfrom = $request->getVal( 'rdfrom' );

		// Construct a URL for the current page view, but with the target title
		$query = $request->getValues();
		unset( $query['rdfrom'] );
		unset( $query['title'] );
		if ( $this->getTitle()->isRedirect() ) {
			// Prevent double redirects
			$query['redirect'] = 'no';
		}
		$redirectTargetUrl = $this->getTitle()->getLinkURL( $query );

		if ( isset( $this->mRedirectedFrom ) ) {
			// This is an internally redirected page view.
			// We'll need a backlink to the source page for navigation.
			if ( $this->getHookRunner()->onArticleViewRedirect( $this ) ) {
				$redir = $this->linkRenderer->makeKnownLink(
					$this->mRedirectedFrom,
					null,
					[],
					[ 'redirect' => 'no' ]
				);

				$outputPage->addSubtitle( "<span class=\"mw-redirectedfrom\">" .
					$context->msg( 'redirectedfrom' )->rawParams( $redir )->parse()
				. "</span>" );

				// Add the script to update the displayed URL and
				// set the fragment if one was specified in the redirect
				$outputPage->addJsConfigVars( [
					'wgInternalRedirectTargetUrl' => $redirectTargetUrl,
				] );
				$outputPage->addModules( 'mediawiki.action.view.redirect' );

				// Add a <link rel="canonical"> tag
				$outputPage->setCanonicalUrl( $this->getTitle()->getCanonicalURL() );

				// Tell the output object that the user arrived at this article through a redirect
				$outputPage->setRedirectedFrom( $this->mRedirectedFrom );

				return true;
			}
		} elseif ( $rdfrom ) {
			// This is an externally redirected view, from some other wiki.
			// If it was reported from a trusted site, supply a backlink.
			if ( $wgRedirectSources && preg_match( $wgRedirectSources, $rdfrom ) ) {
				$redir = Linker::makeExternalLink( $rdfrom, $rdfrom );
				$outputPage->addSubtitle( "<span class=\"mw-redirectedfrom\">" .
					$context->msg( 'redirectedfrom' )->rawParams( $redir )->parse()
				. "</span>" );

				// Add the script to update the displayed URL
				$outputPage->addJsConfigVars( [
					'wgInternalRedirectTargetUrl' => $redirectTargetUrl,
				] );
				$outputPage->addModules( 'mediawiki.action.view.redirect' );

				return true;
			}
		}

		return false;
	}

	/**
	 * Show a header specific to the namespace currently being viewed, like
	 * [[MediaWiki:Talkpagetext]]. For Article::view().
	 */
	public function showNamespaceHeader() {
		if ( $this->getTitle()->isTalkPage() && !wfMessage( 'talkpageheader' )->isDisabled() ) {
			$this->getContext()->getOutput()->wrapWikiMsg(
				"<div class=\"mw-talkpageheader\">\n$1\n</div>",
				[ 'talkpageheader' ]
			);
		}
	}

	/**
	 * Show the footer section of an ordinary page view
	 */
	public function showViewFooter() {
		# check if we're displaying a [[User talk:x.x.x.x]] anonymous talk page
		if ( $this->getTitle()->getNamespace() == NS_USER_TALK
			&& IPUtils::isValid( $this->getTitle()->getText() )
		) {
			$this->getContext()->getOutput()->addWikiMsg( 'anontalkpagetext' );
		}

		// Show a footer allowing the user to patrol the shown revision or page if possible
		$patrolFooterShown = $this->showPatrolFooter();

		$this->getHookRunner()->onArticleViewFooter( $this, $patrolFooterShown );
	}

	/**
	 * If patrol is possible, output a patrol UI box. This is called from the
	 * footer section of ordinary page views. If patrol is not possible or not
	 * desired, does nothing.
	 *
	 * Side effect: When the patrol link is build, this method will call
	 * OutputPage::preventClickjacking() and load a JS module.
	 *
	 * @return bool
	 */
	public function showPatrolFooter() {
		global $wgUseNPPatrol, $wgUseRCPatrol, $wgUseFilePatrol;

		// Allow hooks to decide whether to not output this at all
		if ( !$this->getHookRunner()->onArticleShowPatrolFooter( $this ) ) {
			return false;
		}

		$outputPage = $this->getContext()->getOutput();
		$user = $this->getContext()->getUser();
		$title = $this->getTitle();
		$rc = false;

		if ( !$this->permManager->quickUserCan( 'patrol', $user, $title )
			|| !( $wgUseRCPatrol || $wgUseNPPatrol
				|| ( $wgUseFilePatrol && $title->inNamespace( NS_FILE ) ) )
		) {
			// Patrolling is disabled or the user isn't allowed to
			return false;
		}

		if ( $this->mRevisionRecord
			&& !RecentChange::isInRCLifespan( $this->mRevisionRecord->getTimestamp(), 21600 )
		) {
			// The current revision is already older than what could be in the RC table
			// 6h tolerance because the RC might not be cleaned out regularly
			return false;
		}

		// Check for cached results
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		$key = $cache->makeKey( 'unpatrollable-page', $title->getArticleID() );
		if ( $cache->get( $key ) ) {
			return false;
		}

		$dbr = wfGetDB( DB_REPLICA );
		$oldestRevisionTimestamp = $dbr->selectField(
			'revision',
			'MIN( rev_timestamp )',
			[ 'rev_page' => $title->getArticleID() ],
			__METHOD__
		);

		// New page patrol: Get the timestamp of the oldest revison which
		// the revision table holds for the given page. Then we look
		// whether it's within the RC lifespan and if it is, we try
		// to get the recentchanges row belonging to that entry
		// (with rc_new = 1).
		$recentPageCreation = false;
		if ( $oldestRevisionTimestamp
			&& RecentChange::isInRCLifespan( $oldestRevisionTimestamp, 21600 )
		) {
			// 6h tolerance because the RC might not be cleaned out regularly
			$recentPageCreation = true;
			$rc = RecentChange::newFromConds(
				[
					'rc_new' => 1,
					'rc_timestamp' => $oldestRevisionTimestamp,
					'rc_namespace' => $title->getNamespace(),
					'rc_cur_id' => $title->getArticleID()
				],
				__METHOD__
			);
			if ( $rc ) {
				// Use generic patrol message for new pages
				$markPatrolledMsg = wfMessage( 'markaspatrolledtext' );
			}
		}

		// File patrol: Get the timestamp of the latest upload for this page,
		// check whether it is within the RC lifespan and if it is, we try
		// to get the recentchanges row belonging to that entry
		// (with rc_type = RC_LOG, rc_log_type = upload).
		$recentFileUpload = false;
		if ( ( !$rc || $rc->getAttribute( 'rc_patrolled' ) ) && $wgUseFilePatrol
			&& $title->getNamespace() === NS_FILE ) {
			// Retrieve timestamp of most recent upload
			$newestUploadTimestamp = $dbr->selectField(
				'image',
				'MAX( img_timestamp )',
				[ 'img_name' => $title->getDBkey() ],
				__METHOD__
			);
			if ( $newestUploadTimestamp
				&& RecentChange::isInRCLifespan( $newestUploadTimestamp, 21600 )
			) {
				// 6h tolerance because the RC might not be cleaned out regularly
				$recentFileUpload = true;
				$rc = RecentChange::newFromConds(
					[
						'rc_type' => RC_LOG,
						'rc_log_type' => 'upload',
						'rc_timestamp' => $newestUploadTimestamp,
						'rc_namespace' => NS_FILE,
						'rc_cur_id' => $title->getArticleID()
					],
					__METHOD__
				);
				if ( $rc ) {
					// Use patrol message specific to files
					$markPatrolledMsg = wfMessage( 'markaspatrolledtext-file' );
				}
			}
		}

		if ( !$recentPageCreation && !$recentFileUpload ) {
			// Page creation and latest upload (for files) is too old to be in RC

			// We definitely can't patrol so cache the information
			// When a new file version is uploaded, the cache is cleared
			$cache->set( $key, '1' );

			return false;
		}

		if ( !$rc ) {
			// Don't cache: This can be hit if the page gets accessed very fast after
			// its creation / latest upload or in case we have high replica DB lag. In case
			// the revision is too old, we will already return above.
			return false;
		}

		if ( $rc->getAttribute( 'rc_patrolled' ) ) {
			// Patrolled RC entry around

			// Cache the information we gathered above in case we can't patrol
			// Don't cache in case we can patrol as this could change
			$cache->set( $key, '1' );

			return false;
		}

		if ( $rc->getPerformer()->equals( $user ) ) {
			// Don't show a patrol link for own creations/uploads. If the user could
			// patrol them, they already would be patrolled
			return false;
		}

		$outputPage->preventClickjacking();
		if ( $this->permManager->userHasRight( $user, 'writeapi' ) ) {
			$outputPage->addModules( 'mediawiki.misc-authed-curate' );
		}

		$link = $this->linkRenderer->makeKnownLink(
			$title,
			$markPatrolledMsg->text(),
			[],
			[
				'action' => 'markpatrolled',
				'rcid' => $rc->getAttribute( 'rc_id' ),
			]
		);

		$outputPage->addHTML(
			"<div class='patrollink' data-mw='interface'>" .
				wfMessage( 'markaspatrolledlink' )->rawParams( $link )->escaped() .
			'</div>'
		);

		return true;
	}

	/**
	 * Purge the cache used to check if it is worth showing the patrol footer
	 * For example, it is done during re-uploads when file patrol is used.
	 * @param int $articleID ID of the article to purge
	 * @since 1.27
	 */
	public static function purgePatrolFooterCache( $articleID ) {
		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		$cache->delete( $cache->makeKey( 'unpatrollable-page', $articleID ) );
	}

	/**
	 * Show the error text for a missing article. For articles in the MediaWiki
	 * namespace, show the default message text. To be called from Article::view().
	 */
	public function showMissingArticle() {
		global $wgSend404Code;

		$outputPage = $this->getContext()->getOutput();
		// Whether the page is a root user page of an existing user (but not a subpage)
		$validUserPage = false;

		$title = $this->getTitle();

		$services = MediaWikiServices::getInstance();

		# Show info in user (talk) namespace. Does the user exist? Is he blocked?
		if ( $title->getNamespace() == NS_USER
			|| $title->getNamespace() == NS_USER_TALK
		) {
			$rootPart = explode( '/', $title->getText() )[0];
			$user = User::newFromName( $rootPart, false /* allow IP users */ );
			$ip = User::isIP( $rootPart );
			$block = DatabaseBlock::newFromTarget( $user, $user );

			if ( $user && $user->isLoggedIn() && $user->isHidden() &&
				!$services->getPermissionManager()
					->userHasRight( $this->getContext()->getUser(), 'hideuser' )
			) {
				// T120883 if the user is hidden and the viewer cannot see hidden
				// users, pretend like it does not exist at all.
				$user = false;
			}
			if ( !( $user && $user->isLoggedIn() ) && !$ip ) { # User does not exist
				$outputPage->wrapWikiMsg( "<div class=\"mw-userpage-userdoesnotexist error\">\n\$1\n</div>",
					[ 'userpage-userdoesnotexist-view', wfEscapeWikiText( $rootPart ) ] );
			} elseif (
				$block !== null &&
				$block->getType() != DatabaseBlock::TYPE_AUTO &&
				( $block->isSitewide() || $user->isBlockedFrom( $title ) )
			) {
				// Show log extract if the user is sitewide blocked or is partially
				// blocked and not allowed to edit their user page or user talk page
				LogEventsList::showLogExtract(
					$outputPage,
					'block',
					$services->getNamespaceInfo()->getCanonicalName( NS_USER ) . ':' .
						$block->getTarget(),
					'',
					[
						'lim' => 1,
						'showIfEmpty' => false,
						'msgKey' => [
							'blocked-notice-logextract',
							$user->getName() # Support GENDER in notice
						]
					]
				);
				$validUserPage = !$title->isSubpage();
			} else {
				$validUserPage = !$title->isSubpage();
			}
		}

		$this->getHookRunner()->onShowMissingArticle( $this );

		# Show delete and move logs if there were any such events.
		# The logging query can DOS the site when bots/crawlers cause 404 floods,
		# so be careful showing this. 404 pages must be cheap as they are hard to cache.
		$dbCache = ObjectCache::getInstance( 'db-replicated' );
		$key = $dbCache->makeKey( 'page-recent-delete', md5( $title->getPrefixedText() ) );
		$loggedIn = $this->getContext()->getUser()->isLoggedIn();
		$sessionExists = $this->getContext()->getRequest()->getSession()->isPersistent();
		if ( $loggedIn || $dbCache->get( $key ) || $sessionExists ) {
			$logTypes = [ 'delete', 'move', 'protect' ];

			$dbr = wfGetDB( DB_REPLICA );

			$conds = [ 'log_action != ' . $dbr->addQuotes( 'revision' ) ];
			// Give extensions a chance to hide their (unrelated) log entries
			$this->getHookRunner()->onArticle__MissingArticleConditions( $conds, $logTypes );
			LogEventsList::showLogExtract(
				$outputPage,
				$logTypes,
				$title,
				'',
				[
					'lim' => 10,
					'conds' => $conds,
					'showIfEmpty' => false,
					'msgKey' => [ $loggedIn || $sessionExists
						? 'moveddeleted-notice'
						: 'moveddeleted-notice-recent'
					]
				]
			);
		}

		if ( !$this->mPage->hasViewableContent() && $wgSend404Code && !$validUserPage ) {
			// If there's no backing content, send a 404 Not Found
			// for better machine handling of broken links.
			$this->getContext()->getRequest()->response()->statusHeader( 404 );
		}

		// Also apply the robot policy for nonexisting pages (even if a 404 was used for sanity)
		$policy = $this->getRobotPolicy( 'view' );
		$outputPage->setIndexPolicy( $policy['index'] );
		$outputPage->setFollowPolicy( $policy['follow'] );

		$hookResult = $this->getHookRunner()->onBeforeDisplayNoArticleText( $this );

		if ( !$hookResult ) {
			return;
		}

		# Show error message
		$oldid = $this->getOldID();
		$pm = $this->permManager;
		if ( !$oldid && $title->getNamespace() === NS_MEDIAWIKI && $title->hasSourceText() ) {
			// use fake Content object for system message
			$parserOptions = ParserOptions::newCanonical( 'canonical' );
			$outputPage->addParserOutput( $this->getEmptyPageParserOutput( $parserOptions ) );
		} else {
			if ( $oldid ) {
				$text = wfMessage( 'missing-revision', $oldid )->plain();
			} elseif ( $pm->quickUserCan( 'create', $this->getContext()->getUser(), $title ) &&
				$pm->quickUserCan( 'edit', $this->getContext()->getUser(), $title )
			) {
				$message = $this->getContext()->getUser()->isLoggedIn() ? 'noarticletext' : 'noarticletextanon';
				$text = wfMessage( $message )->plain();
			} else {
				$text = wfMessage( 'noarticletext-nopermission' )->plain();
			}

			$dir = $this->getContext()->getLanguage()->getDir();
			$lang = $this->getContext()->getLanguage()->getHtmlCode();
			$outputPage->addWikiTextAsInterface( Xml::openElement( 'div', [
				'class' => "noarticletext mw-content-$dir",
				'dir' => $dir,
				'lang' => $lang,
			] ) . "\n$text\n</div>" );
		}
	}

	/**
	 * If the revision requested for view is deleted, check permissions.
	 * Send either an error message or a warning header to the output.
	 *
	 * @return bool True if the view is allowed, false if not.
	 */
	public function showDeletedRevisionHeader() {
		if ( !$this->mRevisionRecord->isDeleted( RevisionRecord::DELETED_TEXT ) ) {
			// Not deleted
			return true;
		}

		$outputPage = $this->getContext()->getOutput();
		$user = $this->getContext()->getUser();
		// If the user is not allowed to see it...
		if ( !RevisionRecord::userCanBitfield(
			$this->mRevisionRecord->getVisibility(),
			RevisionRecord::DELETED_TEXT,
			$user
		) ) {
			$outputPage->wrapWikiMsg( "<div class='mw-warning plainlinks'>\n$1\n</div>\n",
				'rev-deleted-text-permission' );

			return false;
		// If the user needs to confirm that they want to see it...
		} elseif ( $this->getContext()->getRequest()->getInt( 'unhide' ) != 1 ) {
			# Give explanation and add a link to view the revision...
			$oldid = intval( $this->getOldID() );
			$link = $this->getTitle()->getFullURL( "oldid={$oldid}&unhide=1" );
			$msg = $this->mRevisionRecord->isDeleted( RevisionRecord::DELETED_RESTRICTED ) ?
				'rev-suppressed-text-unhide' : 'rev-deleted-text-unhide';
			$outputPage->wrapWikiMsg( "<div class='mw-warning plainlinks'>\n$1\n</div>\n",
				[ $msg, $link ] );

			return false;
		// We are allowed to see...
		} else {
			$msg = $this->mRevisionRecord->isDeleted( RevisionRecord::DELETED_RESTRICTED ) ?
				'rev-suppressed-text-view' : 'rev-deleted-text-view';
			$outputPage->wrapWikiMsg( "<div class='mw-warning plainlinks'>\n$1\n</div>\n", $msg );

			return true;
		}
	}

	/**
	 * Generate the navigation links when browsing through an article revisions
	 * It shows the information as:
	 *   Revision as of \<date\>; view current revision
	 *   \<- Previous version | Next Version -\>
	 *
	 * @param int $oldid Revision ID of this article revision
	 */
	public function setOldSubtitle( $oldid = 0 ) {
		if ( !$this->getHookRunner()->onDisplayOldSubtitle( $this, $oldid ) ) {
			return;
		}

		$context = $this->getContext();
		$unhide = $context->getRequest()->getInt( 'unhide' ) == 1;

		# Cascade unhide param in links for easy deletion browsing
		$extraParams = [];
		if ( $unhide ) {
			$extraParams['unhide'] = 1;
		}

		if ( $this->mRevisionRecord && $this->mRevisionRecord->getId() === $oldid ) {
			$revisionRecord = $this->mRevisionRecord;
		} else {
			$revisionRecord = $this->revisionStore->getRevisionById( $oldid );
		}

		$timestamp = $revisionRecord->getTimestamp();

		$current = ( $oldid == $this->mPage->getLatest() );
		$language = $context->getLanguage();
		$user = $context->getUser();

		$td = $language->userTimeAndDate( $timestamp, $user );
		$tddate = $language->userDate( $timestamp, $user );
		$tdtime = $language->userTime( $timestamp, $user );

		# Show user links if allowed to see them. If hidden, then show them only if requested...
		$userlinks = Linker::revUserTools( $revisionRecord, !$unhide );

		$infomsg = $current && !$context->msg( 'revision-info-current' )->isDisabled()
			? 'revision-info-current'
			: 'revision-info';

		$outputPage = $context->getOutput();
		$revisionUser = $revisionRecord->getUser();
		$revisionInfo = "<div id=\"mw-{$infomsg}\">" .
			$context->msg( $infomsg, $td )
				->rawParams( $userlinks )
				->params(
					$revisionRecord->getId(),
					$tddate,
					$tdtime,
					$revisionUser ? $revisionUser->getName() : ''
				)
				->rawParams( Linker::revComment(
					$revisionRecord,
					true,
					true
				) )
				->parse() .
			"</div>";

		$lnk = $current
			? $context->msg( 'currentrevisionlink' )->escaped()
			: $this->linkRenderer->makeKnownLink(
				$this->getTitle(),
				$context->msg( 'currentrevisionlink' )->text(),
				[],
				$extraParams
			);
		$curdiff = $current
			? $context->msg( 'diff' )->escaped()
			: $this->linkRenderer->makeKnownLink(
				$this->getTitle(),
				$context->msg( 'diff' )->text(),
				[],
				[
					'diff' => 'cur',
					'oldid' => $oldid
				] + $extraParams
			);
		$prevExist = (bool)$this->revisionStore->getPreviousRevision( $revisionRecord );
		$prevlink = $prevExist
			? $this->linkRenderer->makeKnownLink(
				$this->getTitle(),
				$context->msg( 'previousrevision' )->text(),
				[],
				[
					'direction' => 'prev',
					'oldid' => $oldid
				] + $extraParams
			)
			: $context->msg( 'previousrevision' )->escaped();
		$prevdiff = $prevExist
			? $this->linkRenderer->makeKnownLink(
				$this->getTitle(),
				$context->msg( 'diff' )->text(),
				[],
				[
					'diff' => 'prev',
					'oldid' => $oldid
				] + $extraParams
			)
			: $context->msg( 'diff' )->escaped();
		$nextlink = $current
			? $context->msg( 'nextrevision' )->escaped()
			: $this->linkRenderer->makeKnownLink(
				$this->getTitle(),
				$context->msg( 'nextrevision' )->text(),
				[],
				[
					'direction' => 'next',
					'oldid' => $oldid
				] + $extraParams
			);
		$nextdiff = $current
			? $context->msg( 'diff' )->escaped()
			: $this->linkRenderer->makeKnownLink(
				$this->getTitle(),
				$context->msg( 'diff' )->text(),
				[],
				[
					'diff' => 'next',
					'oldid' => $oldid
				] + $extraParams
			);

		$cdel = Linker::getRevDeleteLink(
			$user,
			$revisionRecord,
			$this->getTitle()
		);
		if ( $cdel !== '' ) {
			$cdel .= ' ';
		}

		// the outer div is need for styling the revision info and nav in MobileFrontend
		$outputPage->addSubtitle( "<div class=\"mw-revision warningbox\">" . $revisionInfo .
			"<div id=\"mw-revision-nav\">" . $cdel .
			$context->msg( 'revision-nav' )->rawParams(
				$prevdiff, $prevlink, $lnk, $curdiff, $nextlink, $nextdiff
			)->escaped() . "</div></div>" );
	}

	/**
	 * Return the HTML for the top of a redirect page
	 *
	 * Chances are you should just be using the ParserOutput from
	 * WikitextContent::getParserOutput instead of calling this for redirects.
	 *
	 * @param Title|array $target Destination(s) to redirect
	 * @param bool $appendSubtitle [optional]
	 * @param bool $forceKnown Should the image be shown as a bluelink regardless of existence?
	 * @return string Containing HTML with redirect link
	 *
	 * @deprecated since 1.30
	 */
	public function viewRedirect( $target, $appendSubtitle = true, $forceKnown = false ) {
		$lang = $this->getTitle()->getPageLanguage();
		$out = $this->getContext()->getOutput();
		if ( $appendSubtitle ) {
			$out->addSubtitle( wfMessage( 'redirectpagesub' ) );
		}
		$out->addModuleStyles( 'mediawiki.action.view.redirectPage' );
		return static::getRedirectHeaderHtml( $lang, $target, $forceKnown );
	}

	/**
	 * Return the HTML for the top of a redirect page
	 *
	 * Chances are you should just be using the ParserOutput from
	 * WikitextContent::getParserOutput instead of calling this for redirects.
	 *
	 * @since 1.23
	 * @param Language $lang
	 * @param Title|Title[] $target Destination(s) to redirect
	 * @param bool $forceKnown Should the image be shown as a bluelink regardless of existence?
	 * @return string Containing HTML with redirect link
	 */
	public static function getRedirectHeaderHtml( Language $lang, $target, $forceKnown = false ) {
		if ( !is_array( $target ) ) {
			$target = [ $target ];
		}

		$linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();

		$html = '<ul class="redirectText">';
		/** @var Title $title */
		foreach ( $target as $title ) {
			if ( $forceKnown ) {
				$link = $linkRenderer->makeKnownLink(
					$title,
					$title->getFullText(),
					[],
					// Make sure wiki page redirects are not followed
					$title->isRedirect() ? [ 'redirect' => 'no' ] : []
				);
			} else {
				$link = $linkRenderer->makeLink(
					$title,
					$title->getFullText(),
					[],
					// Make sure wiki page redirects are not followed
					$title->isRedirect() ? [ 'redirect' => 'no' ] : []
				);
			}
			$html .= '<li>' . $link . '</li>';
		}
		$html .= '</ul>';

		$redirectToText = wfMessage( 'redirectto' )->inLanguage( $lang )->escaped();

		return '<div class="redirectMsg">' .
			'<p>' . $redirectToText . '</p>' .
			$html .
			'</div>';
	}

	/**
	 * Adds help link with an icon via page indicators.
	 * Link target can be overridden by a local message containing a wikilink:
	 * the message key is: 'namespace-' + namespace number + '-helppage'.
	 * @param string $to Target MediaWiki.org page title or encoded URL.
	 * @param bool $overrideBaseUrl Whether $url is a full URL, to avoid MW.o.
	 * @since 1.25
	 */
	public function addHelpLink( $to, $overrideBaseUrl = false ) {
		$msg = wfMessage(
			'namespace-' . $this->getTitle()->getNamespace() . '-helppage'
		);

		$out = $this->getContext()->getOutput();
		if ( !$msg->isDisabled() ) {
			$helpUrl = Skin::makeUrl( $msg->plain() );
			$out->addHelpLink( $helpUrl, true );
		} else {
			$out->addHelpLink( $to, $overrideBaseUrl );
		}
	}

	/**
	 * Handle action=render
	 */
	public function render() {
		$this->getContext()->getRequest()->response()->header( 'X-Robots-Tag: noindex' );
		$this->getContext()->getOutput()->setArticleBodyOnly( true );
		// We later set 'enableSectionEditLinks=false' based on this; also used by ImagePage
		$this->viewIsRenderAction = true;
		$this->view();
	}

	/**
	 * action=protect handler
	 */
	public function protect() {
		$form = new ProtectionForm( $this );
		$form->execute();
	}

	/**
	 * action=unprotect handler (alias)
	 */
	public function unprotect() {
		$this->protect();
	}

	/**
	 * UI entry point for page deletion
	 */
	public function delete() {
		# This code desperately needs to be totally rewritten

		$title = $this->getTitle();
		$context = $this->getContext();
		$user = $context->getUser();
		$request = $context->getRequest();

		# Check permissions
		$permissionErrors = $this->permManager->getPermissionErrors( 'delete', $user, $title );
		if ( count( $permissionErrors ) ) {
			throw new PermissionsError( 'delete', $permissionErrors );
		}

		# Read-only check...
		if ( wfReadOnly() ) {
			throw new ReadOnlyError;
		}

		# Better double-check that it hasn't been deleted yet!
		$this->mPage->loadPageData(
			$request->wasPosted() ? WikiPage::READ_LATEST : WikiPage::READ_NORMAL
		);
		if ( !$this->mPage->exists() ) {
			$deleteLogPage = new LogPage( 'delete' );
			$outputPage = $context->getOutput();
			$outputPage->setPageTitle( $context->msg( 'cannotdelete-title', $title->getPrefixedText() ) );
			$outputPage->wrapWikiMsg( "<div class=\"error mw-error-cannotdelete\">\n$1\n</div>",
					[ 'cannotdelete', wfEscapeWikiText( $title->getPrefixedText() ) ]
				);
			$outputPage->addHTML(
				Xml::element( 'h2', null, $deleteLogPage->getName()->text() )
			);
			LogEventsList::showLogExtract(
				$outputPage,
				'delete',
				$title
			);

			return;
		}

		$deleteReasonList = $request->getText( 'wpDeleteReasonList', 'other' );
		$deleteReason = $request->getText( 'wpReason' );

		if ( $deleteReasonList == 'other' ) {
			$reason = $deleteReason;
		} elseif ( $deleteReason != '' ) {
			// Entry from drop down menu + additional comment
			$colonseparator = wfMessage( 'colon-separator' )->inContentLanguage()->text();
			$reason = $deleteReasonList . $colonseparator . $deleteReason;
		} else {
			$reason = $deleteReasonList;
		}

		if ( $request->wasPosted() && $user->matchEditToken( $request->getVal( 'wpEditToken' ),
			[ 'delete', $this->getTitle()->getPrefixedText() ] )
		) {
			# Flag to hide all contents of the archived revisions

			$suppress = $request->getCheck( 'wpSuppress' ) &&
				$this->permManager->userHasRight( $user, 'suppressrevision' );

			$this->doDelete( $reason, $suppress );

			WatchAction::doWatchOrUnwatch( $request->getCheck( 'wpWatch' ), $title, $user );

			return;
		}

		// Generate deletion reason
		$hasHistory = false;
		if ( !$reason ) {
			try {
				$reason = $this->getPage()
					->getAutoDeleteReason( $hasHistory );
			} catch ( Exception $e ) {
				# if a page is horribly broken, we still want to be able to
				# delete it. So be lenient about errors here.
				wfDebug( "Error while building auto delete summary: $e" );
				$reason = '';
			}
		}

		// If the page has a history, insert a warning
		if ( $hasHistory ) {
			$title = $this->getTitle();

			// The following can use the real revision count as this is only being shown for users
			// that can delete this page.
			// This, as a side-effect, also makes sure that the following query isn't being run for
			// pages with a larger history, unless the user has the 'bigdelete' right
			// (and is about to delete this page).
			$dbr = wfGetDB( DB_REPLICA );
			$revisions = $edits = (int)$dbr->selectField(
				'revision',
				'COUNT(rev_page)',
				[ 'rev_page' => $title->getArticleID() ],
				__METHOD__
			);

			// @todo i18n issue/patchwork message
			$context->getOutput()->addHTML(
				'<strong class="mw-delete-warning-revisions">' .
				$context->msg( 'historywarning' )->numParams( $revisions )->parse() .
				$context->msg( 'word-separator' )->escaped() . $this->linkRenderer->makeKnownLink(
					$title,
					$context->msg( 'history' )->text(),
					[],
					[ 'action' => 'history' ] ) .
				'</strong>'
			);

			if ( $title->isBigDeletion() ) {
				global $wgDeleteRevisionsLimit;
				$context->getOutput()->wrapWikiMsg( "<div class='error'>\n$1\n</div>\n",
					[
						'delete-warning-toobig',
						$context->getLanguage()->formatNum( $wgDeleteRevisionsLimit )
					]
				);
			}
		}

		$this->confirmDelete( $reason );
	}

	/**
	 * Output deletion confirmation dialog
	 * @todo Move to another file?
	 * @param string $reason Prefilled reason
	 */
	public function confirmDelete( $reason ) {
		wfDebug( "Article::confirmDelete" );

		$title = $this->getTitle();
		$ctx = $this->getContext();
		$outputPage = $ctx->getOutput();
		$outputPage->setPageTitle( wfMessage( 'delete-confirm', $title->getPrefixedText() ) );
		$outputPage->addBacklinkSubtitle( $title );
		$outputPage->setRobotPolicy( 'noindex,nofollow' );
		$outputPage->addModules( 'mediawiki.action.delete' );

		$backlinkCache = $title->getBacklinkCache();
		if ( $backlinkCache->hasLinks( 'pagelinks' ) || $backlinkCache->hasLinks( 'templatelinks' ) ) {
			$outputPage->wrapWikiMsg( "<div class='mw-warning plainlinks'>\n$1\n</div>\n",
				'deleting-backlinks-warning' );
		}

		$subpageQueryLimit = 51;
		$subpages = $title->getSubpages( $subpageQueryLimit );
		$subpageCount = count( $subpages );
		if ( $subpageCount > 0 ) {
			$outputPage->wrapWikiMsg( "<div class='mw-warning plainlinks'>\n$1\n</div>\n",
				[ 'deleting-subpages-warning', Message::numParam( $subpageCount ) ] );
		}
		$outputPage->addWikiMsg( 'confirmdeletetext' );

		$this->getHookRunner()->onArticleConfirmDelete( $this, $outputPage, $reason );

		$user = $this->getContext()->getUser();
		$checkWatch = $user->getBoolOption( 'watchdeletion' ) || $user->isWatched( $title );

		$outputPage->enableOOUI();

		$fields = [];

		$suppressAllowed = $this->permManager->userHasRight( $user, 'suppressrevision' );
		$dropDownReason = $ctx->msg( 'deletereason-dropdown' )->inContentLanguage()->text();
		// Add additional specific reasons for suppress
		if ( $suppressAllowed ) {
			$dropDownReason .= "\n" . $ctx->msg( 'deletereason-dropdown-suppress' )
				->inContentLanguage()->text();
		}

		$options = Xml::listDropDownOptions(
			$dropDownReason,
			[ 'other' => $ctx->msg( 'deletereasonotherlist' )->inContentLanguage()->text() ]
		);
		$options = Xml::listDropDownOptionsOoui( $options );

		$fields[] = new OOUI\FieldLayout(
			new OOUI\DropdownInputWidget( [
				'name' => 'wpDeleteReasonList',
				'inputId' => 'wpDeleteReasonList',
				'tabIndex' => 1,
				'infusable' => true,
				'value' => '',
				'options' => $options
			] ),
			[
				'label' => $ctx->msg( 'deletecomment' )->text(),
				'align' => 'top',
			]
		);

		// HTML maxlength uses "UTF-16 code units", which means that characters outside BMP
		// (e.g. emojis) count for two each. This limit is overridden in JS to instead count
		// Unicode codepoints.
		$fields[] = new OOUI\FieldLayout(
			new OOUI\TextInputWidget( [
				'name' => 'wpReason',
				'inputId' => 'wpReason',
				'tabIndex' => 2,
				'maxLength' => CommentStore::COMMENT_CHARACTER_LIMIT,
				'infusable' => true,
				'value' => $reason,
				'autofocus' => true,
			] ),
			[
				'label' => $ctx->msg( 'deleteotherreason' )->text(),
				'align' => 'top',
			]
		);

		if ( $user->isLoggedIn() ) {
			$fields[] = new OOUI\FieldLayout(
				new OOUI\CheckboxInputWidget( [
					'name' => 'wpWatch',
					'inputId' => 'wpWatch',
					'tabIndex' => 3,
					'selected' => $checkWatch,
				] ),
				[
					'label' => $ctx->msg( 'watchthis' )->text(),
					'align' => 'inline',
					'infusable' => true,
				]
			);
		}
		if ( $suppressAllowed ) {
			$fields[] = new OOUI\FieldLayout(
				new OOUI\CheckboxInputWidget( [
					'name' => 'wpSuppress',
					'inputId' => 'wpSuppress',
					'tabIndex' => 4,
				] ),
				[
					'label' => $ctx->msg( 'revdelete-suppress' )->text(),
					'align' => 'inline',
					'infusable' => true,
				]
			);
		}

		$fields[] = new OOUI\FieldLayout(
			new OOUI\ButtonInputWidget( [
				'name' => 'wpConfirmB',
				'inputId' => 'wpConfirmB',
				'tabIndex' => 5,
				'value' => $ctx->msg( 'deletepage' )->text(),
				'label' => $ctx->msg( 'deletepage' )->text(),
				'flags' => [ 'primary', 'destructive' ],
				'type' => 'submit',
			] ),
			[
				'align' => 'top',
			]
		);

		$fieldset = new OOUI\FieldsetLayout( [
			'label' => $ctx->msg( 'delete-legend' )->text(),
			'id' => 'mw-delete-table',
			'items' => $fields,
		] );

		$form = new OOUI\FormLayout( [
			'method' => 'post',
			'action' => $title->getLocalURL( 'action=delete' ),
			'id' => 'deleteconfirm',
		] );
		$form->appendContent(
			$fieldset,
			new OOUI\HtmlSnippet(
				Html::hidden( 'wpEditToken', $user->getEditToken( [ 'delete', $title->getPrefixedText() ] ) )
			)
		);

		$outputPage->addHTML(
			new OOUI\PanelLayout( [
				'classes' => [ 'deletepage-wrapper' ],
				'expanded' => false,
				'padded' => true,
				'framed' => true,
				'content' => $form,
			] )
		);

		if ( $this->permManager->userHasRight( $user, 'editinterface' ) ) {
			$link = '';
			if ( $suppressAllowed ) {
				$link .= $this->linkRenderer->makeKnownLink(
					$ctx->msg( 'deletereason-dropdown-suppress' )->inContentLanguage()->getTitle(),
					$ctx->msg( 'delete-edit-reasonlist-suppress' )->text(),
					[],
					[ 'action' => 'edit' ]
				);
				$link .= $ctx->msg( 'pipe-separator' )->escaped();
			}
			$link .= $this->linkRenderer->makeKnownLink(
				$ctx->msg( 'deletereason-dropdown' )->inContentLanguage()->getTitle(),
				$ctx->msg( 'delete-edit-reasonlist' )->text(),
				[],
				[ 'action' => 'edit' ]
			);
			$outputPage->addHTML( '<p class="mw-delete-editreasons">' . $link . '</p>' );
		}

		$deleteLogPage = new LogPage( 'delete' );
		$outputPage->addHTML( Xml::element( 'h2', null, $deleteLogPage->getName()->text() ) );
		LogEventsList::showLogExtract( $outputPage, 'delete', $title );
	}

	/**
	 * Perform a deletion and output success or failure messages
	 * @param string $reason
	 * @param bool $suppress
	 * @param bool $immediate false allows deleting over time via the job queue
	 * @throws FatalError
	 * @throws MWException
	 */
	public function doDelete( $reason, $suppress = false, $immediate = false ) {
		$error = '';
		$context = $this->getContext();
		$outputPage = $context->getOutput();
		$user = $context->getUser();
		$status = $this->mPage->doDeleteArticleReal(
			$reason, $user, $suppress, null, $error,
			null, [], 'delete', $immediate
		);

		if ( $status->isOK() ) {
			$deleted = $this->getTitle()->getPrefixedText();

			$outputPage->setPageTitle( wfMessage( 'actioncomplete' ) );
			$outputPage->setRobotPolicy( 'noindex,nofollow' );

			if ( $status->isGood() ) {
				$loglink = '[[Special:Log/delete|' . wfMessage( 'deletionlog' )->text() . ']]';
				$outputPage->addWikiMsg( 'deletedtext', wfEscapeWikiText( $deleted ), $loglink );
				$this->getHookRunner()->onArticleDeleteAfterSuccess( $this->getTitle(), $outputPage );
			} else {
				$outputPage->addWikiMsg( 'delete-scheduled', wfEscapeWikiText( $deleted ) );
			}

			$outputPage->returnToMain( false );
		} else {
			$outputPage->setPageTitle(
				wfMessage( 'cannotdelete-title',
					$this->getTitle()->getPrefixedText() )
			);

			if ( $error == '' ) {
				$outputPage->wrapWikiTextAsInterface(
					'error mw-error-cannotdelete',
					$status->getWikiText( false, false, $context->getLanguage() )
				);
				$deleteLogPage = new LogPage( 'delete' );
				$outputPage->addHTML( Xml::element( 'h2', null, $deleteLogPage->getName()->text() ) );

				LogEventsList::showLogExtract(
					$outputPage,
					'delete',
					$this->getTitle()
				);
			} else {
				$outputPage->addHTML( $error );
			}
		}
	}

	/* Caching functions */

	/**
	 * checkLastModified returns true if it has taken care of all
	 * output to the client that is necessary for this request.
	 * (that is, it has sent a cached version of the page)
	 *
	 * @return bool True if cached version send, false otherwise
	 */
	protected function tryFileCache() {
		static $called = false;

		if ( $called ) {
			wfDebug( "Article::tryFileCache(): called twice!?" );
			return false;
		}

		$called = true;
		if ( $this->isFileCacheable() ) {
			$cache = new HTMLFileCache( $this->getTitle(), 'view' );
			if ( $cache->isCacheGood( $this->mPage->getTouched() ) ) {
				wfDebug( "Article::tryFileCache(): about to load file" );
				$cache->loadFromFileCache( $this->getContext() );
				return true;
			} else {
				wfDebug( "Article::tryFileCache(): starting buffer" );
				ob_start( [ &$cache, 'saveToFileCache' ] );
			}
		} else {
			wfDebug( "Article::tryFileCache(): not cacheable" );
		}

		return false;
	}

	/**
	 * Check if the page can be cached
	 * @param int $mode One of the HTMLFileCache::MODE_* constants (since 1.28)
	 * @return bool
	 */
	public function isFileCacheable( $mode = HTMLFileCache::MODE_NORMAL ) {
		$cacheable = false;

		if ( HTMLFileCache::useFileCache( $this->getContext(), $mode ) ) {
			$cacheable = $this->mPage->getId()
				&& !$this->mRedirectedFrom && !$this->getTitle()->isRedirect();
			// Extension may have reason to disable file caching on some pages.
			if ( $cacheable ) {
				$cacheable = $this->getHookRunner()->onIsFileCacheable( $this );
			}
		}

		return $cacheable;
	}

	/** #@- */

	/**
	 * Lightweight method to get the parser output for a page, checking the parser cache
	 * and so on. Doesn't consider most of the stuff that Article::view() is forced to
	 * consider, so it's not appropriate to use there.
	 *
	 * @since 1.16 (r52326) for LiquidThreads
	 *
	 * @param int|null $oldid Revision ID or null
	 * @param User|null $user The relevant user
	 * @return ParserOutput|bool ParserOutput or false if the given revision ID is not found
	 */
	public function getParserOutput( $oldid = null, User $user = null ) {
		// XXX: bypasses mParserOptions and thus setParserOptions()

		if ( $user === null ) {
			$parserOptions = $this->getParserOptions();
		} else {
			$parserOptions = $this->mPage->makeParserOptions( $user );
		}

		return $this->mPage->getParserOutput( $parserOptions, $oldid );
	}

	/**
	 * Override the ParserOptions used to render the primary article wikitext.
	 *
	 * @param ParserOptions $options
	 * @throws MWException If the parser options where already initialized.
	 */
	public function setParserOptions( ParserOptions $options ) {
		if ( $this->mParserOptions ) {
			throw new MWException( "can't change parser options after they have already been set" );
		}

		// clone, so if $options is modified later, it doesn't confuse the parser cache.
		$this->mParserOptions = clone $options;
	}

	/**
	 * Get parser options suitable for rendering the primary article wikitext
	 * @return ParserOptions
	 */
	public function getParserOptions() {
		if ( !$this->mParserOptions ) {
			$this->mParserOptions = $this->mPage->makeParserOptions( $this->getContext() );
		}
		// Clone to allow modifications of the return value without affecting cache
		return clone $this->mParserOptions;
	}

	/**
	 * Sets the context this Article is executed in
	 *
	 * @param IContextSource $context
	 * @since 1.18
	 */
	public function setContext( $context ) {
		$this->mContext = $context;
	}

	/**
	 * Gets the context this Article is executed in
	 *
	 * @return IContextSource
	 * @since 1.18
	 */
	public function getContext() {
		if ( $this->mContext instanceof IContextSource ) {
			return $this->mContext;
		} else {
			wfDebug( __METHOD__ . " called and \$mContext is null. " .
				"Return RequestContext::getMain(); for sanity" );
			return RequestContext::getMain();
		}
	}

	/**
	 * @deprecated since 1.35, use Article::getPage() instead
	 *
	 * Use PHP's magic __get handler to handle accessing of
	 * raw WikiPage fields for backwards compatibility, as well as the deprecated $mRevision
	 *
	 * @param string $fname Field name
	 * @return mixed
	 */
	public function __get( $fname ) {
		wfDeprecatedMsg( "Accessing Article::\$$fname is deprecated since MediaWiki 1.35",
			'1.35' );

		if ( $fname === 'mRevision' ) {
			$record = $this->fetchRevisionRecord(); // Ensure that it is loaded
			return $record ? new Revision( $record ) : null;
		}

		if ( property_exists( $this->mPage, $fname ) ) {
			return $this->mPage->$fname;
		}
		trigger_error( 'Inaccessible property via __get(): ' . $fname, E_USER_NOTICE );
	}

	/**
	 * @deprecated since 1.35, use Article::getPage() instead
	 *
	 * Use PHP's magic __set handler to handle setting of
	 * raw WikiPage fields for backwards compatibility, as well as the deprecated $mRevision
	 *
	 * @param string $fname Field name
	 * @param mixed $fvalue New value
	 */
	public function __set( $fname, $fvalue ) {
		wfDeprecatedMsg( "Setting Article::\$$fname is deprecated since MediaWiki 1.35",
			'1.35' );

		if ( $fname === 'mRevision' ) {
			$this->mRevisionRecord = $fvalue ?
				$fvalue->getRevisionRecord() :
				null;
			return;
		}

		if ( property_exists( $this->mPage, $fname ) ) {
			$this->mPage->$fname = $fvalue;
		// Note: extensions may want to toss on new fields
		} elseif ( !in_array( $fname, [ 'mContext', 'mPage' ] ) ) {
			$this->mPage->$fname = $fvalue;
		} else {
			trigger_error( 'Inaccessible property via __set(): ' . $fname, E_USER_NOTICE );
		}
	}

	/**
	 * Call to WikiPage function for backwards compatibility.
	 * @deprecated since 1.35, use WikiPage::exists() instead,
	 *  or simply omit the EDIT_UPDATE and EDIT_NEW flags.
	 *  To protect against race conditions,
	 *  use PageUpdater::grabParentRevision.
	 *
	 * @see WikiPage::checkFlags
	 * @param int $flags
	 * @return int
	 */
	public function checkFlags( $flags ) {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->checkFlags( $flags );
	}

	/**
	 * @deprecated since 1.35, use WikiPage::checkTouched instead
	 * @see WikiPage::checkTouched
	 * @return bool
	 */
	public function checkTouched() {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->checkTouched();
	}

	/**
	 * Call to WikiPage function for backwards compatibility.
	 * @deprecated since 1.35, use WikiPage::clearPreparedEdit instead
	 * @see WikiPage::clearPreparedEdit
	 */
	public function clearPreparedEdit() {
		wfDeprecated( __METHOD__, '1.35' );
		$this->mPage->clearPreparedEdit();
	}

	/**
	 * Call to WikiPage function for backwards compatibility.
	 * @deprecated since 1.35
	 * @see WikiPage::doDeleteArticleReal
	 * @param string $reason
	 * @param bool $suppress
	 * @param int|null $u1
	 * @param bool|null $u2
	 * @param array|string &$error
	 * @param User|null $user
	 * @param array $tags
	 * @param bool $immediate
	 * @return Status
	 */
	public function doDeleteArticleReal(
		$reason, $suppress = false, $u1 = null, $u2 = null, &$error = '', User $user = null,
		$tags = [], $immediate = false
	) {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->doDeleteArticleReal(
			$reason, $suppress, $u1, $u2, $error, $user, $tags, 'delete', $immediate
		);
	}

	/**
	 * @deprecated since 1.35, use WikiPage::doDeleteUpdates instead
	 * @see WikiPage::doDeleteUpdates
	 * @param int $id
	 * @param Content|null $content
	 * @param Revision|null $revision
	 * @param User|null $user
	 */
	public function doDeleteUpdates(
		$id,
		Content $content = null,
		$revision = null,
		User $user = null
	) {
		wfDeprecated( __METHOD__, '1.35' );
		$this->mPage->doDeleteUpdates( $id, $content, $revision, $user );
	}

	/**
	 * Call to WikiPage function for backwards compatibility.
	 * @deprecated since 1.35, use PageUpdater::doUpdates instead.
	 *
	 * @see WikiPage::doEditUpdates
	 * @param Revision $revision
	 * @param User $user
	 * @param array $options
	 */
	public function doEditUpdates( Revision $revision, User $user, array $options = [] ) {
		wfDeprecated( __METHOD__, '1.35' );
		$this->mPage->doEditUpdates( $revision, $user, $options );
	}

	/**
	 * @deprecated since 1.35, use WikiPage::doPurge instead
	 * @see WikiPage::doPurge
	 * @note In 1.28 (and only 1.28), this took a $flags parameter that
	 *  controlled how much purging was done.
	 * @return bool
	 */
	public function doPurge() {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->doPurge();
	}

	/**
	 * @deprecated since 1.35, use WikiPage::doViewUpdates instead
	 * @see WikiPage::doViewUpdates
	 * @param User $user
	 * @param int $oldid
	 */
	public function doViewUpdates( User $user, $oldid = 0 ) {
		wfDeprecated( __METHOD__, '1.35' );
		$this->mPage->doViewUpdates( $user, $oldid );
	}

	/**
	 * @deprecated since 1.35, use WikiPage::exists instead
	 * @see WikiPage::exists
	 * @return bool
	 */
	public function exists() {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->exists();
	}

	/**
	 * @deprecated since 1.35, use WikiPage::followRedirect instead
	 * @see WikiPage::followRedirect
	 * @return bool|Title|string
	 */
	public function followRedirect() {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->followRedirect();
	}

	/**
	 * Call to WikiPage function for backwards compatibility.
	 * @see ContentHandler::getActionOverrides
	 * @return array
	 */
	public function getActionOverrides() {
		return $this->mPage->getActionOverrides();
	}

	/**
	 * @deprecated since 1.35, use WikiPage::getAutoDeleteReason instead
	 * @see WikiPage::getAutoDeleteReason
	 * @param bool &$hasHistory
	 * @return string|bool
	 */
	public function getAutoDeleteReason( &$hasHistory ) {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->getAutoDeleteReason( $hasHistory );
	}

	/**
	 * @deprecated since 1.35, use WikiPage::getCategories instead
	 * @see WikiPage::getCategories
	 * @return TitleArray
	 */
	public function getCategories() {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->getCategories();
	}

	/**
	 * Call to WikiPage function for backwards compatibility.
	 * @deprecated since 1.35
	 * @see WikiPage::getComment
	 * @param int $audience
	 * @param User|null $user
	 * @return string|null
	 */
	public function getComment( $audience = RevisionRecord::FOR_PUBLIC, User $user = null ) {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->getComment( $audience, $user );
	}

	/**
	 * @deprecated since 1.35, use WikiPage::getContentHandler instead
	 * @see WikiPage::getContentHandler
	 * @return ContentHandler
	 */
	public function getContentHandler() {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->getContentHandler();
	}

	/**
	 * @deprecated since 1.35, use WikiPage::getContentModel instead
	 * @see WikiPage::getContentModel
	 * @return string
	 */
	public function getContentModel() {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->getContentModel();
	}

	/**
	 * @deprecated since 1.35, use WikiPage::getContributors instead
	 * @see WikiPage::getContributors
	 * @return UserArrayFromResult
	 */
	public function getContributors() {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->getContributors();
	}

	/**
	 * Call to WikiPage function for backwards compatibility.
	 * @deprecated since 1.35
	 * @see WikiPage::getCreator
	 * @param int $audience
	 * @param User|null $user
	 * @return User|null
	 */
	public function getCreator( $audience = RevisionRecord::FOR_PUBLIC, User $user = null ) {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->getCreator( $audience, $user );
	}

	/**
	 * @deprecated since 1.35, use WikiPage::getDeletionUpdates instead
	 * @see WikiPage::getDeletionUpdates
	 * @param Content|null $content
	 * @return DeferrableUpdate[]
	 */
	public function getDeletionUpdates( Content $content = null ) {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->getDeletionUpdates( $content );
	}

	/**
	 * @deprecated since 1.35, use WikiPage::getHiddenCategories instead
	 * @see WikiPage::getHiddenCategories
	 * @return array
	 */
	public function getHiddenCategories() {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->getHiddenCategories();
	}

	/**
	 * @deprecated since 1.35, use WikiPage::getId instead
	 * @see WikiPage::getId
	 * @return int
	 */
	public function getId() {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->getId();
	}

	/**
	 * @deprecated since 1.35, use WikiPage::getLatest instead
	 * @see WikiPage::getLatest
	 * @return int
	 */
	public function getLatest() {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->getLatest();
	}

	/**
	 * @deprecated since 1.35, use WikiPage::getLinksTimestamp instead
	 * @see WikiPage::getLinksTimestamp
	 * @return string|null
	 */
	public function getLinksTimestamp() {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->getLinksTimestamp();
	}

	/**
	 * @deprecated since 1.35, use WikiPage::getMinorEdit instead
	 * @see WikiPage::getMinorEdit
	 * @return bool
	 */
	public function getMinorEdit() {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->getMinorEdit();
	}

	/**
	 * @deprecated since 1.35, use RevisionStore::getFirstRevision
	 * @return Revision|null
	 */
	public function getOldestRevision() {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->getOldestRevision();
	}

	/**
	 * @deprecated since 1.35, use WikiPage::getRedirectTarget instead
	 * @see WikiPage::getRedirectTarget
	 * @return Title|null
	 */
	public function getRedirectTarget() {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->getRedirectTarget();
	}

	/**
	 * @deprecated since 1.35, use WikiPage::getRedirectURL instead
	 * @see WikiPage::getRedirectURL
	 * @param Title $rt
	 * @return bool|Title|string
	 */
	public function getRedirectURL( $rt ) {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->getRedirectURL( $rt );
	}

	/**
	 * Call to WikiPage function for backwards compatibility.
	 * @deprecated since 1.35
	 * @see WikiPage::getRevision
	 * @return Revision|null
	 */
	public function getRevision() {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->getRevision();
	}

	/**
	 * @deprecated since 1.35, use WikiPage::getTimestamp instead
	 * @see WikiPage::getTimestamp
	 * @return string
	 */
	public function getTimestamp() {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->getTimestamp();
	}

	/**
	 * @deprecated since 1.35, use WikiPage::getTouched instead
	 * @see WikiPage::getTouched
	 * @return string
	 */
	public function getTouched() {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->getTouched();
	}

	/**
	 * Call to WikiPage function for backwards compatibility.
	 * @deprecated since 1.35
	 * @see WikiPage::getUndoContent
	 * @param Revision $undo
	 * @param Revision|null $undoafter
	 * @return Content|bool
	 */
	public function getUndoContent( Revision $undo, Revision $undoafter = null ) {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->getUndoContent( $undo, $undoafter );
	}

	/**
	 * @deprecated since 1.35, use WikiPage::getUser instead
	 * @see WikiPage::getUser
	 * @param int $audience
	 * @param User|null $user
	 * @return int
	 */
	public function getUser( $audience = RevisionRecord::FOR_PUBLIC, User $user = null ) {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->getUser( $audience, $user );
	}

	/**
	 * @deprecated since 1.35, use WikiPage::getUserText instead
	 * @see WikiPage::getUserText
	 * @param int $audience
	 * @param User|null $user
	 * @return string
	 */
	public function getUserText( $audience = RevisionRecord::FOR_PUBLIC, User $user = null ) {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->getUserText( $audience, $user );
	}

	/**
	 * @deprecated since 1.35, use WikiPage::hasViewableContent instead
	 * @see WikiPage::hasViewableContent
	 * @return bool
	 */
	public function hasViewableContent() {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->hasViewableContent();
	}

	/**
	 * @deprecated since 1.35, use WikiPage::insertOn instead
	 * @see WikiPage::insertOn
	 * @param IDatabase $dbw
	 * @param int|null $pageId
	 * @return bool|int
	 */
	public function insertOn( $dbw, $pageId = null ) {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->insertOn( $dbw, $pageId );
	}

	/**
	 * @see WikiPage::insertProtectNullRevision
	 * @deprecated since 1.35, use WikiPage::insertNullProtectionRevision instead
	 * @param string $revCommentMsg
	 * @param array $limit
	 * @param array $expiry
	 * @param int $cascade
	 * @param string $reason
	 * @param User|null $user
	 * @return Revision|null
	 */
	public function insertProtectNullRevision( $revCommentMsg, array $limit,
		array $expiry, $cascade, $reason, $user = null
	) {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->insertProtectNullRevision( $revCommentMsg, $limit,
			$expiry, $cascade, $reason, $user
		);
	}

	/**
	 * @deprecated since 1.35, use WikiPage::insertRedirect instead
	 * @see WikiPage::insertRedirect
	 * @return Title|null
	 */
	public function insertRedirect() {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->insertRedirect();
	}

	/**
	 * @deprecated since 1.35, use WikiPage::insertRedirectEntry instead
	 * @see WikiPage::insertRedirectEntry
	 * @param Title $rt
	 * @param int|null $oldLatest
	 * @return bool
	 */
	public function insertRedirectEntry( Title $rt, $oldLatest = null ) {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->insertRedirectEntry( $rt, $oldLatest );
	}

	/**
	 * @deprecated since 1.35, use WikiPage::isCountable instead
	 * @see WikiPage::isCountable
	 * @param PreparedEdit|bool $editInfo
	 * @return bool
	 */
	public function isCountable( $editInfo = false ) {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->isCountable( $editInfo );
	}

	/**
	 * @deprecated since 1.35, use WikiPage::isRedirect instead
	 * @see WikiPage::isRedirect
	 * @return bool
	 */
	public function isRedirect() {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->isRedirect();
	}

	/**
	 * @deprecated since 1.35, use WikiPage::loadFromRow instead
	 * @see WikiPage::loadFromRow
	 * @param object|bool $data
	 * @param string|int $from
	 */
	public function loadFromRow( $data, $from ) {
		wfDeprecated( __METHOD__, '1.35' );
		$this->mPage->loadFromRow( $data, $from );
	}

	/**
	 * @deprecated since 1.35, use WikiPage::loadPageData instead
	 * @see WikiPage::loadPageData
	 * @param object|string|int $from
	 */
	public function loadPageData( $from = 'fromdb' ) {
		wfDeprecated( __METHOD__, '1.35' );
		$this->mPage->loadPageData( $from );
	}

	/**
	 * @deprecated since 1.35, use WikiPage::lockAndGetLatest instead
	 * @see WikiPage::lockAndGetLatest
	 * @return int
	 */
	public function lockAndGetLatest() {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->lockAndGetLatest();
	}

	/**
	 * @deprecated since 1.35, use WikiPage::makeParserOptions instead
	 * @see WikiPage::makeParserOptions
	 * @param IContextSource|User|string $context
	 * @return ParserOptions
	 */
	public function makeParserOptions( $context ) {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->makeParserOptions( $context );
	}

	/**
	 * @deprecated since 1.35, use WikiPage::pageDataFromId instead
	 * @see WikiPage::pageDataFromId
	 * @param IDatabase $dbr
	 * @param int $id
	 * @param array $options
	 * @return object|bool
	 */
	public function pageDataFromId( $dbr, $id, $options = [] ) {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->pageDataFromId( $dbr, $id, $options );
	}

	/**
	 * @deprecated since 1.35, use WikiPage::pageDataFromTitle instead
	 * @see WikiPage::pageDataFromTitle
	 * @param IDatabase $dbr
	 * @param Title $title
	 * @param array $options
	 * @return object|bool
	 */
	public function pageDataFromTitle( $dbr, $title, $options = [] ) {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->pageDataFromTitle( $dbr, $title, $options );
	}

	/**
	 * Call to WikiPage function for backwards compatibility.
	 * @deprecated since 1.35 with PreparedEdit.
	 *             use @see \MediaWiki\Storage\DerivedPageDataUpdater instead.
	 *
	 * @see WikiPage::prepareContentForEdit
	 * @param Content $content
	 * @param Revision|RevisionRecord|null $revision
	 * @param User|null $user
	 * @param string|null $serialFormat
	 * @param bool $useCache
	 * @return PreparedEdit
	 */
	public function prepareContentForEdit(
		Content $content, $revision = null, User $user = null,
		$serialFormat = null, $useCache = true
	) {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->prepareContentForEdit(
			$content, $revision, $user,
			$serialFormat, $useCache
		);
	}

	/**
	 * @deprecated since 1.35, use WikiPage::protectDescription instead
	 * @see WikiPage::protectDescription
	 * @param array $limit
	 * @param array $expiry
	 * @return string
	 */
	public function protectDescription( array $limit, array $expiry ) {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->protectDescription( $limit, $expiry );
	}

	/**
	 * @deprecated since 1.35, use WikiPage::protectDescriptionLog instead
	 * @see WikiPage::protectDescriptionLog
	 * @param array $limit
	 * @param array $expiry
	 * @return string
	 */
	public function protectDescriptionLog( array $limit, array $expiry ) {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->protectDescriptionLog( $limit, $expiry );
	}

	/**
	 * @deprecated since 1.35, use WikiPage::replaceSectionAtRev instead
	 * @see WikiPage::replaceSectionAtRev
	 * @param string|int|null|bool $sectionId
	 * @param Content $sectionContent
	 * @param string $sectionTitle
	 * @param int|null $baseRevId
	 * @return Content|null
	 */
	public function replaceSectionAtRev( $sectionId, Content $sectionContent,
		$sectionTitle = '', $baseRevId = null
	) {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->replaceSectionAtRev( $sectionId, $sectionContent,
			$sectionTitle, $baseRevId
		);
	}

	/**
	 * Call to WikiPage function for backwards compatibility.
	 * @deprecated since 1.35, use WikiPage::replaceSectionAtRev instead
	 *
	 * @see WikiPage::replaceSectionContent
	 * @param string|int|null|bool $sectionId
	 * @param Content $sectionContent
	 * @param string $sectionTitle
	 * @param string|null $edittime
	 * @return Content|null
	 */
	public function replaceSectionContent(
		$sectionId, Content $sectionContent, $sectionTitle = '', $edittime = null
	) {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->replaceSectionContent(
			$sectionId, $sectionContent, $sectionTitle, $edittime
		);
	}

	/**
	 * @deprecated since 1.35, use WikiPage::setTimestamp instead
	 * @see WikiPage::setTimestamp
	 * @param string $ts
	 */
	public function setTimestamp( $ts ) {
		wfDeprecated( __METHOD__, '1.35' );
		$this->mPage->setTimestamp( $ts );
	}

	/**
	 * @deprecated since 1.35, use WikiPage::shouldCheckParserCache instead
	 * @see WikiPage::shouldCheckParserCache
	 * @param ParserOptions $parserOptions
	 * @param int $oldId
	 * @return bool
	 */
	public function shouldCheckParserCache( ParserOptions $parserOptions, $oldId ) {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->shouldCheckParserCache( $parserOptions, $oldId );
	}

	/**
	 * @deprecated since 1.35, use WikiPage::supportsSections instead
	 * @see WikiPage::supportsSections
	 * @return bool
	 */
	public function supportsSections() {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->supportsSections();
	}

	/**
	 * @deprecated since 1.35, use WikiPage::triggerOpportunisticLinksUpdate instead
	 * @see WikiPage::triggerOpportunisticLinksUpdate
	 * @param ParserOutput $parserOutput
	 */
	public function triggerOpportunisticLinksUpdate( ParserOutput $parserOutput ) {
		wfDeprecated( __METHOD__, '1.35' );
		$this->mPage->triggerOpportunisticLinksUpdate( $parserOutput );
	}

	/**
	 * @deprecated since 1.35, use WikiPage::updateCategoryCounts instead
	 * @see WikiPage::updateCategoryCounts
	 * @param array $added
	 * @param array $deleted
	 * @param int $id
	 */
	public function updateCategoryCounts( array $added, array $deleted, $id = 0 ) {
		wfDeprecated( __METHOD__, '1.35' );
		$this->mPage->updateCategoryCounts( $added, $deleted, $id );
	}

	/**
	 * Call to WikiPage function for backwards compatibility.
	 * @deprecated since 1.35
	 * @see WikiPage::updateIfNewerOn
	 * @param IDatabase $dbw
	 * @param Revision $revision
	 * @return bool
	 */
	public function updateIfNewerOn( $dbw, $revision ) {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->updateIfNewerOn( $dbw, $revision );
	}

	/**
	 * @deprecated since 1.35, use WikiPage::updateRedirectOn instead
	 * @see WikiPage::updateRedirectOn
	 * @param IDatabase $dbw
	 * @param Title|null $redirectTitle
	 * @param null|bool $lastRevIsRedirect
	 * @return bool
	 */
	public function updateRedirectOn( $dbw, $redirectTitle, $lastRevIsRedirect = null ) {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->updateRedirectOn( $dbw, $redirectTitle, $lastRevIsRedirect );
	}

	/**
	 * @deprecated since 1.35, use WikiPage::updateRevisionOn instead
	 * @see WikiPage::updateRevisionOn
	 * @param IDatabase $dbw
	 * @param Revision $revision
	 * @param int|null $lastRevision
	 * @param bool|null $lastRevIsRedirect
	 * @return bool
	 */
	public function updateRevisionOn( $dbw, $revision, $lastRevision = null,
		$lastRevIsRedirect = null
	) {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->updateRevisionOn( $dbw, $revision, $lastRevision,
			$lastRevIsRedirect
		);
	}

	/**
	 * @deprecated since 1.35, use WikiPage::doUpdateRestrictions instead
	 * @param array $limit
	 * @param array $expiry
	 * @param bool &$cascade
	 * @param string $reason
	 * @param User $user
	 * @return Status
	 */
	public function doUpdateRestrictions( array $limit, array $expiry, &$cascade,
		$reason, User $user
	) {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->doUpdateRestrictions( $limit, $expiry, $cascade, $reason, $user );
	}

	/**
	 * @deprecated since 1.35, use WikiPage::updateRestrictions
	 * @param array $limit
	 * @param string $reason
	 * @param int &$cascade
	 * @param array $expiry
	 * @return bool
	 */
	public function updateRestrictions( $limit = [], $reason = '',
		&$cascade = 0, $expiry = []
	) {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->doUpdateRestrictions(
			$limit,
			$expiry,
			$cascade,
			$reason,
			$this->getContext()->getUser()
		);
	}

	/**
	 * @deprecated since 1.35, use WikiPage::doDeleteArticleReal instead
	 * @param string $reason
	 * @param bool $suppress
	 * @param int|null $u1 Unused
	 * @param bool|null $u2 Unused
	 * @param string &$error
	 * @param bool $immediate false allows deleting over time via the job queue
	 * @return bool
	 * @throws FatalError
	 * @throws MWException
	 */
	public function doDeleteArticle(
		$reason, $suppress = false, $u1 = null, $u2 = null, &$error = '', $immediate = false
	) {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->mPage->doDeleteArticle( $reason, $suppress, $u1, $u2, $error,
			null, $immediate );
	}

	/**
	 * @deprecated since 1.35
	 * @param string $fromP
	 * @param string $summary
	 * @param string $token
	 * @param bool $bot
	 * @param array &$resultDetails
	 * @param User|null $user
	 * @return array[]
	 */
	public function doRollback(
		$fromP,
		$summary,
		$token,
		$bot,
		&$resultDetails,
		User $user = null
	) {
		wfDeprecated( __METHOD__, '1.35' );
		if ( !$user ) {
			$user = $this->getContext()->getUser();
		}

		return $this->mPage->doRollback( $fromP, $summary, $token, $bot, $resultDetails, $user );
	}

	/**
	 * @deprecated since 1.35
	 * @internal
	 * @param string $fromP
	 * @param string $summary
	 * @param bool $bot
	 * @param array &$resultDetails
	 * @param User|null $guser
	 * @return array
	 */
	public function commitRollback( $fromP, $summary, $bot, &$resultDetails, User $guser = null ) {
		wfDeprecated( __METHOD__, '1.35' );
		if ( !$guser ) {
			$guser = $this->getContext()->getUser();
		}

		return $this->mPage->commitRollback( $fromP, $summary, $bot, $resultDetails, $guser );
	}

	/**
	 * @deprecated since 1.35, use WikiPage::getAutoDeleteReason instead
	 *
	 * @param bool &$hasHistory
	 * @return mixed
	 */
	public function generateReason( &$hasHistory ) {
		wfDeprecated( __METHOD__, '1.35' );
		return $this->getPage()->getAutoDeleteReason( $hasHistory );
	}
}
