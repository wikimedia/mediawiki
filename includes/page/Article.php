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

/**
 * Class for viewing MediaWiki article and history.
 *
 * This maintains WikiPage functions for backwards compatibility.
 *
 * @todo Move and rewrite code to an Action class
 *
 * See design.txt for an overview.
 * Note: edit user interface and cache support functions have been
 * moved to separate EditPage and HTMLFileCache classes.
 */
class Article implements Page {
	/** @var IContextSource The context this Article is executed in */
	protected $mContext;

	/** @var WikiPage The WikiPage object of this instance */
	protected $mPage;

	/** @var ParserOptions ParserOptions object for $wgUser articles */
	public $mParserOptions;

	/**
	 * @var string Text of the revision we are working on
	 * @todo BC cruft
	 */
	public $mContent;

	/**
	 * @var Content Content of the revision we are working on
	 * @since 1.21
	 */
	public $mContentObject;

	/** @var bool Is the content ($mContent) already loaded? */
	public $mContentLoaded = false;

	/** @var int|null The oldid of the article that is to be shown, 0 for the current revision */
	public $mOldId;

	/** @var Title Title from which we were redirected here */
	public $mRedirectedFrom = null;

	/** @var string|bool URL to redirect to or false if none */
	public $mRedirectUrl = false;

	/** @var int Revision ID of revision we are working on */
	public $mRevIdFetched = 0;

	/** @var Revision Revision we are working on */
	public $mRevision = null;

	/** @var ParserOutput */
	public $mParserOutput;

	/**
	 * Constructor and clear the article
	 * @param Title $title Reference to a Title object.
	 * @param int $oldId Revision ID, null to fetch from request, zero for current
	 */
	public function __construct( Title $title, $oldId = null ) {
		$this->mOldId = $oldId;
		$this->mPage = $this->newPage( $title );
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
		# @todo FIXME: Doesn't inherit right
		return $t == null ? null : new self( $t );
		# return $t == null ? null : new static( $t ); // PHP 5.3
	}

	/**
	 * Create an Article object of the appropriate class for the given page.
	 *
	 * @param Title $title
	 * @param IContextSource $context
	 * @return Article
	 */
	public static function newFromTitle( $title, IContextSource $context ) {
		if ( NS_MEDIA == $title->getNamespace() ) {
			// FIXME: where should this go?
			$title = Title::makeTitle( NS_FILE, $title->getDBkey() );
		}

		$page = null;
		Hooks::run( 'ArticleFromTitle', array( &$title, &$page, $context ) );
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

		$this->mPage->clear();
	}

	/**
	 * Note that getContent/loadContent do not follow redirects anymore.
	 * If you need to fetch redirectable content easily, try
	 * the shortcut in WikiPage::getRedirectTarget()
	 *
	 * This function has side effects! Do not use this function if you
	 * only want the real revision text if any.
	 *
	 * @deprecated since 1.21; use WikiPage::getContent() instead
	 *
	 * @return string Return the text of this revision
	 */
	public function getContent() {
		ContentHandler::deprecated( __METHOD__, '1.21' );
		$content = $this->getContentObject();
		return ContentHandler::getContentText( $content );
	}

	/**
	 * Returns a Content object representing the pages effective display content,
	 * not necessarily the revision's content!
	 *
	 * Note that getContent/loadContent do not follow redirects anymore.
	 * If you need to fetch redirectable content easily, try
	 * the shortcut in WikiPage::getRedirectTarget()
	 *
	 * This function has side effects! Do not use this function if you
	 * only want the real revision text if any.
	 *
	 * @return Content Return the content of this revision
	 *
	 * @since 1.21
	 */
	protected function getContentObject() {

		if ( $this->mPage->getID() === 0 ) {
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
				$content = new MessageContent( $message, null, 'parsemag' );
			}
		} else {
			$this->fetchContentObject();
			$content = $this->mContentObject;
		}

		return $content;
	}

	/**
	 * @return int The oldid of the article that is to be shown, 0 for the current revision
	 */
	public function getOldID() {
		if ( is_null( $this->mOldId ) ) {
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
				$this->mRevision = $this->mPage->getRevision();
			} else {
				$this->mRevision = Revision::newFromId( $oldid );
				if ( $this->mRevision !== null ) {
					// Revision title doesn't match the page title given?
					if ( $this->mPage->getID() != $this->mRevision->getPage() ) {
						$function = array( get_class( $this->mPage ), 'newFromID' );
						$this->mPage = call_user_func( $function, $this->mRevision->getPage() );
					}
				}
			}
		}

		if ( $request->getVal( 'direction' ) == 'next' ) {
			$nextid = $this->getTitle()->getNextRevisionID( $oldid );
			if ( $nextid ) {
				$oldid = $nextid;
				$this->mRevision = null;
			} else {
				$this->mRedirectUrl = $this->getTitle()->getFullURL( 'redirect=no' );
			}
		} elseif ( $request->getVal( 'direction' ) == 'prev' ) {
			$previd = $this->getTitle()->getPreviousRevisionID( $oldid );
			if ( $previd ) {
				$oldid = $previd;
				$this->mRevision = null;
			}
		}

		return $oldid;
	}

	/**
	 * Load the revision (including text) into this object
	 *
	 * @deprecated since 1.19; use fetchContent()
	 */
	function loadContent() {
		wfDeprecated( __METHOD__, '1.19' );
		$this->fetchContent();
	}

	/**
	 * Get text of an article from database
	 * Does *NOT* follow redirects.
	 *
	 * @protected
	 * @note This is really internal functionality that should really NOT be
	 * used by other functions. For accessing article content, use the WikiPage
	 * class, especially WikiBase::getContent(). However, a lot of legacy code
	 * uses this method to retrieve page text from the database, so the function
	 * has to remain public for now.
	 *
	 * @return string|bool String containing article contents, or false if null
	 * @deprecated since 1.21, use WikiPage::getContent() instead
	 */
	function fetchContent() {
		// BC cruft!

		ContentHandler::deprecated( __METHOD__, '1.21' );

		if ( $this->mContentLoaded && $this->mContent ) {
			return $this->mContent;
		}

		$content = $this->fetchContentObject();

		if ( !$content ) {
			return false;
		}

		// @todo Get rid of mContent everywhere!
		$this->mContent = ContentHandler::getContentText( $content );
		ContentHandler::runLegacyHooks( 'ArticleAfterFetchContent', array( &$this, &$this->mContent ) );

		return $this->mContent;
	}

	/**
	 * Get text content object
	 * Does *NOT* follow redirects.
	 * @todo When is this null?
	 *
	 * @note Code that wants to retrieve page content from the database should
	 * use WikiPage::getContent().
	 *
	 * @return Content|null|bool
	 *
	 * @since 1.21
	 */
	protected function fetchContentObject() {
		if ( $this->mContentLoaded ) {
			return $this->mContentObject;
		}

		$this->mContentLoaded = true;
		$this->mContent = null;

		$oldid = $this->getOldID();

		# Pre-fill content with error message so that if something
		# fails we'll have something telling us what we intended.
		//XXX: this isn't page content but a UI message. horrible.
		$this->mContentObject = new MessageContent( 'missing-revision', array( $oldid ) );

		if ( $oldid ) {
			# $this->mRevision might already be fetched by getOldIDFromRequest()
			if ( !$this->mRevision ) {
				$this->mRevision = Revision::newFromId( $oldid );
				if ( !$this->mRevision ) {
					wfDebug( __METHOD__ . " failed to retrieve specified revision, id $oldid\n" );
					return false;
				}
			}
		} else {
			$oldid = $this->mPage->getLatest();
			if ( !$oldid ) {
				wfDebug( __METHOD__ . " failed to find page data for title " .
					$this->getTitle()->getPrefixedText() . "\n" );
				return false;
			}

			# Update error message with correct oldid
			$this->mContentObject = new MessageContent( 'missing-revision', array( $oldid ) );

			$this->mRevision = $this->mPage->getRevision();

			if ( !$this->mRevision ) {
				wfDebug( __METHOD__ . " failed to retrieve current page, rev_id $oldid\n" );
				return false;
			}
		}

		// @todo FIXME: Horrible, horrible! This content-loading interface just plain sucks.
		// We should instead work with the Revision object when we need it...
		// Loads if user is allowed
		$content = $this->mRevision->getContent(
			Revision::FOR_THIS_USER,
			$this->getContext()->getUser()
		);

		if ( !$content ) {
			wfDebug( __METHOD__ . " failed to retrieve content of revision " .
				$this->mRevision->getId() . "\n" );
			return false;
		}

		$this->mContentObject = $content;
		$this->mRevIdFetched = $this->mRevision->getId();

		Hooks::run( 'ArticleAfterFetchContentObject', array( &$this, &$this->mContentObject ) );

		return $this->mContentObject;
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

		return $this->mPage->exists() && $this->mRevision && $this->mRevision->isCurrent();
	}

	/**
	 * Get the fetched Revision object depending on request parameters or null
	 * on failure.
	 *
	 * @since 1.19
	 * @return Revision|null
	 */
	public function getRevisionFetched() {
		$this->fetchContentObject();

		return $this->mRevision;
	}

	/**
	 * Use this to fetch the rev ID used on page views
	 *
	 * @return int Revision ID of last article revision
	 */
	public function getRevIdFetched() {
		if ( $this->mRevIdFetched ) {
			return $this->mRevIdFetched;
		} else {
			return $this->mPage->getLatest();
		}
	}

	/**
	 * This is the default action of the index.php entry point: just view the
	 * page of the given title.
	 */
	public function view() {
		global $wgUseFileCache, $wgUseETag, $wgDebugToolbar, $wgMaxRedirects;

		# Get variables from query string
		# As side effect this will load the revision and update the title
		# in a revision ID is passed in the request, so this should remain
		# the first call of this method even if $oldid is used way below.
		$oldid = $this->getOldID();

		$user = $this->getContext()->getUser();
		# Another whitelist check in case getOldID() is altering the title
		$permErrors = $this->getTitle()->getUserPermissionsErrors( 'read', $user );
		if ( count( $permErrors ) ) {
			wfDebug( __METHOD__ . ": denied on secondary read check\n" );
			throw new PermissionsError( 'read', $permErrors );
		}

		$outputPage = $this->getContext()->getOutput();
		# getOldID() may as well want us to redirect somewhere else
		if ( $this->mRedirectUrl ) {
			$outputPage->redirect( $this->mRedirectUrl );
			wfDebug( __METHOD__ . ": redirecting due to oldid\n" );

			return;
		}

		# If we got diff in the query, we want to see a diff page instead of the article.
		if ( $this->getContext()->getRequest()->getCheck( 'diff' ) ) {
			wfDebug( __METHOD__ . ": showing diff page\n" );
			$this->showDiffPage();

			return;
		}

		# Set page title (may be overridden by DISPLAYTITLE)
		$outputPage->setPageTitle( $this->getTitle()->getPrefixedText() );

		$outputPage->setArticleFlag( true );
		# Allow frames by default
		$outputPage->allowClickjacking();

		$parserCache = ParserCache::singleton();

		$parserOptions = $this->getParserOptions();
		# Render printable version, use printable version cache
		if ( $outputPage->isPrintable() ) {
			$parserOptions->setIsPrintable( true );
			$parserOptions->setEditSection( false );
		} elseif ( !$this->isCurrent() || !$this->getTitle()->quickUserCan( 'edit', $user ) ) {
			$parserOptions->setEditSection( false );
		}

		# Try client and file cache
		if ( !$wgDebugToolbar && $oldid === 0 && $this->mPage->checkTouched() ) {
			if ( $wgUseETag ) {
				$outputPage->setETag( $parserCache->getETag( $this, $parserOptions ) );
			}

			# Use the greatest of the page's timestamp or the timestamp of any
			# redirect in the chain (bug 67849)
			$timestamp = $this->mPage->getTouched();
			if ( isset( $this->mRedirectedFrom ) ) {
				$timestamp = max( $timestamp, $this->mRedirectedFrom->getTouched() );

				# If there can be more than one redirect in the chain, we have
				# to go through the whole chain too in case an intermediate
				# redirect was changed.
				if ( $wgMaxRedirects > 1 ) {
					$titles = Revision::newFromTitle( $this->mRedirectedFrom )
						->getContent( Revision::FOR_THIS_USER, $user )
						->getRedirectChain();
					$thisTitle = $this->getTitle();
					foreach ( $titles as $title ) {
						if ( Title::compare( $title, $thisTitle ) === 0 ) {
							break;
						}
						$timestamp = max( $timestamp, $title->getTouched() );
					}
				}
			}

			# Is it client cached?
			if ( $outputPage->checkLastModified( $timestamp ) ) {
				wfDebug( __METHOD__ . ": done 304\n" );

				return;
			# Try file cache
			} elseif ( $wgUseFileCache && $this->tryFileCache() ) {
				wfDebug( __METHOD__ . ": done file cache\n" );
				# tell wgOut that output is taken care of
				$outputPage->disable();
				$this->mPage->doViewUpdates( $user, $oldid );

				return;
			}
		}

		# Should the parser cache be used?
		$useParserCache = $this->mPage->shouldCheckParserCache( $parserOptions, $oldid );
		wfDebug( 'Article::view using parser cache: ' . ( $useParserCache ? 'yes' : 'no' ) . "\n" );
		if ( $user->getStubThreshold() ) {
			$this->getContext()->getStats()->increment( 'pcache_miss_stub' );
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
					Hooks::run( 'ArticleViewHeader', array( &$this, &$outputDone, &$useParserCache ) );
					break;
				case 2:
					# Early abort if the page doesn't exist
					if ( !$this->mPage->exists() ) {
						wfDebug( __METHOD__ . ": showing missing article\n" );
						$this->showMissingArticle();
						$this->mPage->doViewUpdates( $user );
						return;
					}

					# Try the parser cache
					if ( $useParserCache ) {
						$this->mParserOutput = $parserCache->get( $this, $parserOptions );

						if ( $this->mParserOutput !== false ) {
							if ( $oldid ) {
								wfDebug( __METHOD__ . ": showing parser cache contents for current rev permalink\n" );
								$this->setOldSubtitle( $oldid );
							} else {
								wfDebug( __METHOD__ . ": showing parser cache contents\n" );
							}
							$outputPage->addParserOutput( $this->mParserOutput );
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
					# This will set $this->mRevision if needed
					$this->fetchContentObject();

					# Are we looking at an old revision
					if ( $oldid && $this->mRevision ) {
						$this->setOldSubtitle( $oldid );

						if ( !$this->showDeletedRevisionHeader() ) {
							wfDebug( __METHOD__ . ": cannot view deleted revision\n" );
							return;
						}
					}

					# Ensure that UI elements requiring revision ID have
					# the correct version information.
					$outputPage->setRevisionId( $this->getRevIdFetched() );
					# Preload timestamp to avoid a DB hit
					$outputPage->setRevisionTimestamp( $this->getTimestamp() );

					# Pages containing custom CSS or JavaScript get special treatment
					if ( $this->getTitle()->isCssOrJsPage() || $this->getTitle()->isCssJsSubpage() ) {
						wfDebug( __METHOD__ . ": showing CSS/JS source\n" );
						$this->showCssOrJsPage();
						$outputDone = true;
					} elseif ( !Hooks::run( 'ArticleContentViewCustom',
							array( $this->fetchContentObject(), $this->getTitle(), $outputPage ) ) ) {

						# Allow extensions do their own custom view for certain pages
						$outputDone = true;
					} elseif ( !ContentHandler::runLegacyHooks( 'ArticleViewCustom',
							array( $this->fetchContentObject(), $this->getTitle(), $outputPage ) ) ) {

						# Allow extensions do their own custom view for certain pages
						$outputDone = true;
					}
					break;
				case 4:
					# Run the parse, protected by a pool counter
					wfDebug( __METHOD__ . ": doing uncached parse\n" );

					$content = $this->getContentObject();
					$poolArticleView = new PoolWorkArticleView( $this->getPage(), $parserOptions,
						$this->getRevIdFetched(), $useParserCache, $content );

					if ( !$poolArticleView->execute() ) {
						$error = $poolArticleView->getError();
						if ( $error ) {
							$outputPage->clearHTML(); // for release() errors
							$outputPage->enableClientCache( false );
							$outputPage->setRobotPolicy( 'noindex,nofollow' );

							$errortext = $error->getWikiText( false, 'view-pool-error' );
							$outputPage->addWikiText( '<div class="errorbox">' . $errortext . '</div>' );
						}
						# Connection or timeout error
						return;
					}

					$this->mParserOutput = $poolArticleView->getParserOutput();
					$outputPage->addParserOutput( $this->mParserOutput );
					if ( $content->getRedirectTarget() ) {
						$outputPage->addSubtitle( "<span id=\"redirectsub\">" .
							$this->getContext()->msg( 'redirectpagesub' )->parse() . "</span>" );
					}

					# Don't cache a dirty ParserOutput object
					if ( $poolArticleView->getIsDirty() ) {
						$outputPage->setSquidMaxage( 0 );
						$outputPage->addHTML( "<!-- parser cache is expired, " .
							"sending anyway due to pool overload-->\n" );
					}

					$outputDone = true;
					break;
				# Should be unreachable, but just in case...
				default:
					break 2;
			}
		}

		# Get the ParserOutput actually *displayed* here.
		# Note that $this->mParserOutput is the *current*/oldid version output.
		$pOutput = ( $outputDone instanceof ParserOutput )
			? $outputDone // object fetched by hook
			: $this->mParserOutput;

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

		# Check for any __NOINDEX__ tags on the page using $pOutput
		$policy = $this->getRobotPolicy( 'view', $pOutput );
		$outputPage->setIndexPolicy( $policy['index'] );
		$outputPage->setFollowPolicy( $policy['follow'] );

		$this->showViewFooter();
		$this->mPage->doViewUpdates( $user, $oldid );

		$outputPage->addModules( 'mediawiki.action.view.postEdit' );

	}

	/**
	 * Adjust title for pages with displaytitle, -{T|}- or language conversion
	 * @param ParserOutput $pOutput
	 */
	public function adjustDisplayTitle( ParserOutput $pOutput ) {
		# Adjust the title if it was set by displaytitle, -{T|}- or language conversion
		$titleText = $pOutput->getTitleText();
		if ( strval( $titleText ) !== '' ) {
			$this->getContext()->getOutput()->setPageTitle( $titleText );
		}
	}

	/**
	 * Show a diff page according to current request variables. For use within
	 * Article::view() only, other callers should use the DifferenceEngine class.
	 *
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

		$rev = $this->getRevisionFetched();

		if ( !$rev ) {
			$this->getContext()->getOutput()->setPageTitle( wfMessage( 'errorpagetitle' ) );
			$msg = $this->getContext()->msg( 'difference-missing-revision' )
				->params( $oldid )
				->numParams( 1 )
				->parseAsBlock();
			$this->getContext()->getOutput()->addHtml( $msg );
			return;
		}

		$contentHandler = $rev->getContentHandler();
		$de = $contentHandler->createDifferenceEngine(
			$this->getContext(),
			$oldid,
			$diff,
			$rcid,
			$purge,
			$unhide
		);

		// DifferenceEngine directly fetched the revision:
		$this->mRevIdFetched = $de->mNewid;
		$de->showDiffPage( $diffOnly );

		// Run view updates for the newer revision being diffed (and shown
		// below the diff if not $diffOnly).
		list( $old, $new ) = $de->mapDiffPrevNext( $oldid, $diff );
		// New can be false, convert it to 0 - this conveniently means the latest revision
		$this->mPage->doViewUpdates( $user, (int)$new );
	}

	/**
	 * Show a page view for a page formatted as CSS or JavaScript. To be called by
	 * Article::view() only.
	 *
	 * This exists mostly to serve the deprecated ShowRawCssJs hook (used to customize these views).
	 * It has been replaced by the ContentGetParserOutput hook, which lets you do the same but with
	 * more flexibility.
	 *
	 * @param bool $showCacheHint Whether to show a message telling the user
	 *   to clear the browser cache (default: true).
	 */
	protected function showCssOrJsPage( $showCacheHint = true ) {
		$outputPage = $this->getContext()->getOutput();

		if ( $showCacheHint ) {
			$dir = $this->getContext()->getLanguage()->getDir();
			$lang = $this->getContext()->getLanguage()->getHtmlCode();

			$outputPage->wrapWikiMsg(
				"<div id='mw-clearyourcache' lang='$lang' dir='$dir' class='mw-content-$dir'>\n$1\n</div>",
				'clearyourcache'
			);
		}

		$this->fetchContentObject();

		if ( $this->mContentObject ) {
			// Give hooks a chance to customise the output
			if ( ContentHandler::runLegacyHooks(
				'ShowRawCssJs',
				array( $this->mContentObject, $this->getTitle(), $outputPage ) )
			) {
				// If no legacy hooks ran, display the content of the parser output, including RL modules,
				// but excluding metadata like categories and language links
				$po = $this->mContentObject->getParserOutput( $this->getTitle() );
				$outputPage->addParserOutputContent( $po );
			}
		}
	}

	/**
	 * Get the robot policy to be used for the current view
	 * @param string $action The action= GET parameter
	 * @param ParserOutput|null $pOutput
	 * @return array The policy that should be set
	 * @todo actions other than 'view'
	 */
	public function getRobotPolicy( $action, $pOutput = null ) {
		global $wgArticleRobotPolicies, $wgNamespaceRobotPolicies, $wgDefaultRobotPolicy;

		$ns = $this->getTitle()->getNamespace();

		# Don't index user and user talk pages for blocked users (bug 11443)
		if ( ( $ns == NS_USER || $ns == NS_USER_TALK ) && !$this->getTitle()->isSubpage() ) {
			$specificTarget = null;
			$vagueTarget = null;
			$titleText = $this->getTitle()->getText();
			if ( IP::isValid( $titleText ) ) {
				$vagueTarget = $titleText;
			} else {
				$specificTarget = $titleText;
			}
			if ( Block::newFromTarget( $specificTarget, $vagueTarget ) instanceof Block ) {
				return array(
					'index' => 'noindex',
					'follow' => 'nofollow'
				);
			}
		}

		if ( $this->mPage->getID() === 0 || $this->getOldID() ) {
			# Non-articles (special pages etc), and old revisions
			return array(
				'index' => 'noindex',
				'follow' => 'nofollow'
			);
		} elseif ( $this->getContext()->getOutput()->isPrintable() ) {
			# Discourage indexing of printable versions, but encourage following
			return array(
				'index' => 'noindex',
				'follow' => 'follow'
			);
		} elseif ( $this->getContext()->getRequest()->getInt( 'curid' ) ) {
			# For ?curid=x urls, disallow indexing
			return array(
				'index' => 'noindex',
				'follow' => 'follow'
			);
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
				array( 'index' => $pOutput->getIndexPolicy() )
			);
		}

		if ( isset( $wgArticleRobotPolicies[$this->getTitle()->getPrefixedText()] ) ) {
			# (bug 14900) site config can override user-defined __INDEX__ or __NOINDEX__
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
			return array();
		}

		$policy = explode( ',', $policy );
		$policy = array_map( 'trim', $policy );

		$arr = array();
		foreach ( $policy as $var ) {
			if ( in_array( $var, array( 'index', 'noindex' ) ) ) {
				$arr['index'] = $var;
			} elseif ( in_array( $var, array( 'follow', 'nofollow' ) ) ) {
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
			if ( Hooks::run( 'ArticleViewRedirect', array( &$this ) ) ) {
				$redir = Linker::linkKnown(
					$this->mRedirectedFrom,
					null,
					array(),
					array( 'redirect' => 'no' )
				);

				$outputPage->addSubtitle( "<span class=\"mw-redirectedfrom\">" .
					$context->msg( 'redirectedfrom' )->rawParams( $redir )->parse()
				. "</span>" );

				// Add the script to update the displayed URL and
				// set the fragment if one was specified in the redirect
				$outputPage->addJsConfigVars( array(
					'wgInternalRedirectTargetUrl' => $redirectTargetUrl,
				) );
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
				$outputPage->addJsConfigVars( array(
					'wgInternalRedirectTargetUrl' => $redirectTargetUrl,
				) );
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
		if ( $this->getTitle()->isTalkPage() ) {
			if ( !wfMessage( 'talkpageheader' )->isDisabled() ) {
				$this->getContext()->getOutput()->wrapWikiMsg(
					"<div class=\"mw-talkpageheader\">\n$1\n</div>",
					array( 'talkpageheader' )
				);
			}
		}
	}

	/**
	 * Show the footer section of an ordinary page view
	 */
	public function showViewFooter() {
		# check if we're displaying a [[User talk:x.x.x.x]] anonymous talk page
		if ( $this->getTitle()->getNamespace() == NS_USER_TALK
			&& IP::isValid( $this->getTitle()->getText() )
		) {
			$this->getContext()->getOutput()->addWikiMsg( 'anontalkpagetext' );
		}

		// Show a footer allowing the user to patrol the shown revision or page if possible
		$patrolFooterShown = $this->showPatrolFooter();

		Hooks::run( 'ArticleViewFooter', array( $this, $patrolFooterShown ) );
	}

	/**
	 * If patrol is possible, output a patrol UI box. This is called from the
	 * footer section of ordinary page views. If patrol is not possible or not
	 * desired, does nothing.
	 * Side effect: When the patrol link is build, this method will call
	 * OutputPage::preventClickjacking() and load mediawiki.page.patrol.ajax.
	 *
	 * @return bool
	 */
	public function showPatrolFooter() {
		global $wgUseNPPatrol, $wgUseRCPatrol, $wgEnableAPI, $wgEnableWriteAPI;

		$outputPage = $this->getContext()->getOutput();
		$user = $this->getContext()->getUser();
		$cache = wfGetMainCache();
		$rc = false;

		if ( !$this->getTitle()->quickUserCan( 'patrol', $user )
			|| !( $wgUseRCPatrol || $wgUseNPPatrol )
		) {
			// Patrolling is disabled or the user isn't allowed to
			return false;
		}

		// New page patrol: Get the timestamp of the oldest revison which
		// the revision table holds for the given page. Then we look
		// whether it's within the RC lifespan and if it is, we try
		// to get the recentchanges row belonging to that entry
		// (with rc_new = 1).

		if ( $this->mRevision
			&& !RecentChange::isInRCLifespan( $this->mRevision->getTimestamp(), 21600 )
		) {
			// The current revision is already older than what could be in the RC table
			// 6h tolerance because the RC might not be cleaned out regularly
			return false;
		}

		// Check for cached results
		$key = wfMemcKey( 'NotPatrollablePage', $this->getTitle()->getArticleID() );
		if ( $cache->get( $key ) ) {
			return false;
		}

		$dbr = wfGetDB( DB_SLAVE );
		$oldestRevisionTimestamp = $dbr->selectField(
			'revision',
			'MIN( rev_timestamp )',
			array( 'rev_page' => $this->getTitle()->getArticleID() ),
			__METHOD__
		);

		if ( $oldestRevisionTimestamp
			&& RecentChange::isInRCLifespan( $oldestRevisionTimestamp, 21600 )
		) {
			// 6h tolerance because the RC might not be cleaned out regularly
			$rc = RecentChange::newFromConds(
				array(
					'rc_new' => 1,
					'rc_timestamp' => $oldestRevisionTimestamp,
					'rc_namespace' => $this->getTitle()->getNamespace(),
					'rc_cur_id' => $this->getTitle()->getArticleID()
				),
				__METHOD__,
				array( 'USE INDEX' => 'new_name_timestamp' )
			);
		} else {
			// Cache the information we gathered above in case we can't patrol
			// Don't cache in case we can patrol as this could change
			$cache->set( $key, '1' );
		}

		if ( !$rc ) {
			// Don't cache: This can be hit if the page gets accessed very fast after
			// its creation or in case we have high slave lag. In case the revision is
			// too old, we will already return above.
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
			// Don't show a patrol link for own creations. If the user could
			// patrol them, they already would be patrolled
			return false;
		}

		$rcid = $rc->getAttribute( 'rc_id' );

		$token = $user->getEditToken( $rcid );

		$outputPage->preventClickjacking();
		if ( $wgEnableAPI && $wgEnableWriteAPI && $user->isAllowed( 'writeapi' ) ) {
			$outputPage->addModules( 'mediawiki.page.patrol.ajax' );
		}

		$link = Linker::linkKnown(
			$this->getTitle(),
			wfMessage( 'markaspatrolledtext' )->escaped(),
			array(),
			array(
				'action' => 'markpatrolled',
				'rcid' => $rcid,
				'token' => $token,
			)
		);

		$outputPage->addHTML(
			"<div class='patrollink'>" .
				wfMessage( 'markaspatrolledlink' )->rawParams( $link )->escaped() .
			'</div>'
		);

		return true;
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

		# Show info in user (talk) namespace. Does the user exist? Is he blocked?
		if ( $title->getNamespace() == NS_USER
			|| $title->getNamespace() == NS_USER_TALK
		) {
			$parts = explode( '/', $title->getText() );
			$rootPart = $parts[0];
			$user = User::newFromName( $rootPart, false /* allow IP users*/ );
			$ip = User::isIP( $rootPart );
			$block = Block::newFromTarget( $user, $user );

			if ( !( $user && $user->isLoggedIn() ) && !$ip ) { # User does not exist
				$outputPage->wrapWikiMsg( "<div class=\"mw-userpage-userdoesnotexist error\">\n\$1\n</div>",
					array( 'userpage-userdoesnotexist-view', wfEscapeWikiText( $rootPart ) ) );
			} elseif ( !is_null( $block ) && $block->getType() != Block::TYPE_AUTO ) {
				# Show log extract if the user is currently blocked
				LogEventsList::showLogExtract(
					$outputPage,
					'block',
					MWNamespace::getCanonicalName( NS_USER ) . ':' . $block->getTarget(),
					'',
					array(
						'lim' => 1,
						'showIfEmpty' => false,
						'msgKey' => array(
							'blocked-notice-logextract',
							$user->getName() # Support GENDER in notice
						)
					)
				);
				$validUserPage = !$title->isSubpage();
			} else {
				$validUserPage = !$title->isSubpage();
			}
		}

		Hooks::run( 'ShowMissingArticle', array( $this ) );

		# Show delete and move logs if there were any such events.
		# The logging query can DOS the site when bots/crawlers cause 404 floods,
		# so be careful showing this. 404 pages must be cheap as they are hard to cache.
		$cache = ObjectCache::getMainStashInstance();
		$key = wfMemcKey( 'page-recent-delete', md5( $title->getPrefixedText() ) );
		$loggedIn = $this->getContext()->getUser()->isLoggedIn();
		if ( $loggedIn || $cache->get( $key ) ) {
			$logTypes = array( 'delete', 'move' );
			$conds = array( "log_action != 'revision'" );
			// Give extensions a chance to hide their (unrelated) log entries
			Hooks::run( 'Article::MissingArticleConditions', array( &$conds, $logTypes ) );
			LogEventsList::showLogExtract(
				$outputPage,
				$logTypes,
				$title,
				'',
				array(
					'lim' => 10,
					'conds' => $conds,
					'showIfEmpty' => false,
					'msgKey' => array( $loggedIn
						? 'moveddeleted-notice'
						: 'moveddeleted-notice-recent'
					)
				)
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

		$hookResult = Hooks::run( 'BeforeDisplayNoArticleText', array( $this ) );

		if ( !$hookResult ) {
			return;
		}

		# Show error message
		$oldid = $this->getOldID();
		if ( !$oldid && $title->getNamespace() === NS_MEDIAWIKI && $title->hasSourceText() ) {
			$outputPage->addParserOutput( $this->getContentObject()->getParserOutput( $title ) );
		} else {
			if ( $oldid ) {
				$text = wfMessage( 'missing-revision', $oldid )->plain();
			} elseif ( $title->quickUserCan( 'create', $this->getContext()->getUser() )
				&& $title->quickUserCan( 'edit', $this->getContext()->getUser() )
			) {
				$message = $this->getContext()->getUser()->isLoggedIn() ? 'noarticletext' : 'noarticletextanon';
				$text = wfMessage( $message )->plain();
			} else {
				$text = wfMessage( 'noarticletext-nopermission' )->plain();
			}

			$dir = $this->getContext()->getLanguage()->getDir();
			$lang = $this->getContext()->getLanguage()->getCode();
			$outputPage->addWikiText( Xml::openElement( 'div', array(
				'class' => "noarticletext mw-content-$dir",
				'dir' => $dir,
				'lang' => $lang,
			) ) . "\n$text\n</div>" );
		}
	}

	/**
	 * If the revision requested for view is deleted, check permissions.
	 * Send either an error message or a warning header to the output.
	 *
	 * @return bool True if the view is allowed, false if not.
	 */
	public function showDeletedRevisionHeader() {
		if ( !$this->mRevision->isDeleted( Revision::DELETED_TEXT ) ) {
			// Not deleted
			return true;
		}

		$outputPage = $this->getContext()->getOutput();
		$user = $this->getContext()->getUser();
		// If the user is not allowed to see it...
		if ( !$this->mRevision->userCan( Revision::DELETED_TEXT, $user ) ) {
			$outputPage->wrapWikiMsg( "<div class='mw-warning plainlinks'>\n$1\n</div>\n",
				'rev-deleted-text-permission' );

			return false;
		// If the user needs to confirm that they want to see it...
		} elseif ( $this->getContext()->getRequest()->getInt( 'unhide' ) != 1 ) {
			# Give explanation and add a link to view the revision...
			$oldid = intval( $this->getOldID() );
			$link = $this->getTitle()->getFullURL( "oldid={$oldid}&unhide=1" );
			$msg = $this->mRevision->isDeleted( Revision::DELETED_RESTRICTED ) ?
				'rev-suppressed-text-unhide' : 'rev-deleted-text-unhide';
			$outputPage->wrapWikiMsg( "<div class='mw-warning plainlinks'>\n$1\n</div>\n",
				array( $msg, $link ) );

			return false;
		// We are allowed to see...
		} else {
			$msg = $this->mRevision->isDeleted( Revision::DELETED_RESTRICTED ) ?
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
		if ( !Hooks::run( 'DisplayOldSubtitle', array( &$this, &$oldid ) ) ) {
			return;
		}

		$context = $this->getContext();
		$unhide = $context->getRequest()->getInt( 'unhide' ) == 1;

		# Cascade unhide param in links for easy deletion browsing
		$extraParams = array();
		if ( $unhide ) {
			$extraParams['unhide'] = 1;
		}

		if ( $this->mRevision && $this->mRevision->getId() === $oldid ) {
			$revision = $this->mRevision;
		} else {
			$revision = Revision::newFromId( $oldid );
		}

		$timestamp = $revision->getTimestamp();

		$current = ( $oldid == $this->mPage->getLatest() );
		$language = $context->getLanguage();
		$user = $context->getUser();

		$td = $language->userTimeAndDate( $timestamp, $user );
		$tddate = $language->userDate( $timestamp, $user );
		$tdtime = $language->userTime( $timestamp, $user );

		# Show user links if allowed to see them. If hidden, then show them only if requested...
		$userlinks = Linker::revUserTools( $revision, !$unhide );

		$infomsg = $current && !$context->msg( 'revision-info-current' )->isDisabled()
			? 'revision-info-current'
			: 'revision-info';

		$outputPage = $context->getOutput();
		$outputPage->addSubtitle( "<div id=\"mw-{$infomsg}\">" .
			$context->msg( $infomsg, $td )
				->rawParams( $userlinks )
				->params( $revision->getID(), $tddate, $tdtime, $revision->getUserText() )
				->rawParams( Linker::revComment( $revision, true, true ) )
				->parse() .
			"</div>"
		);

		$lnk = $current
			? $context->msg( 'currentrevisionlink' )->escaped()
			: Linker::linkKnown(
				$this->getTitle(),
				$context->msg( 'currentrevisionlink' )->escaped(),
				array(),
				$extraParams
			);
		$curdiff = $current
			? $context->msg( 'diff' )->escaped()
			: Linker::linkKnown(
				$this->getTitle(),
				$context->msg( 'diff' )->escaped(),
				array(),
				array(
					'diff' => 'cur',
					'oldid' => $oldid
				) + $extraParams
			);
		$prev = $this->getTitle()->getPreviousRevisionID( $oldid );
		$prevlink = $prev
			? Linker::linkKnown(
				$this->getTitle(),
				$context->msg( 'previousrevision' )->escaped(),
				array(),
				array(
					'direction' => 'prev',
					'oldid' => $oldid
				) + $extraParams
			)
			: $context->msg( 'previousrevision' )->escaped();
		$prevdiff = $prev
			? Linker::linkKnown(
				$this->getTitle(),
				$context->msg( 'diff' )->escaped(),
				array(),
				array(
					'diff' => 'prev',
					'oldid' => $oldid
				) + $extraParams
			)
			: $context->msg( 'diff' )->escaped();
		$nextlink = $current
			? $context->msg( 'nextrevision' )->escaped()
			: Linker::linkKnown(
				$this->getTitle(),
				$context->msg( 'nextrevision' )->escaped(),
				array(),
				array(
					'direction' => 'next',
					'oldid' => $oldid
				) + $extraParams
			);
		$nextdiff = $current
			? $context->msg( 'diff' )->escaped()
			: Linker::linkKnown(
				$this->getTitle(),
				$context->msg( 'diff' )->escaped(),
				array(),
				array(
					'diff' => 'next',
					'oldid' => $oldid
				) + $extraParams
			);

		$cdel = Linker::getRevDeleteLink( $user, $revision, $this->getTitle() );
		if ( $cdel !== '' ) {
			$cdel .= ' ';
		}

		$outputPage->addSubtitle( "<div id=\"mw-revision-nav\">" . $cdel .
			$context->msg( 'revision-nav' )->rawParams(
				$prevdiff, $prevlink, $lnk, $curdiff, $nextlink, $nextdiff
			)->escaped() . "</div>" );
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
	 * @param Title|array $target Destination(s) to redirect
	 * @param bool $forceKnown Should the image be shown as a bluelink regardless of existence?
	 * @return string Containing HTML with redirect link
	 */
	public static function getRedirectHeaderHtml( Language $lang, $target, $forceKnown = false ) {
		if ( !is_array( $target ) ) {
			$target = array( $target );
		}

		$html = '<ul class="redirectText">';
		/** @var Title $title */
		foreach ( $target as $title ) {
			$html .= '<li>' . Linker::link(
				$title,
				htmlspecialchars( $title->getFullText() ),
				array(),
				// Automatically append redirect=no to each link, since most of them are
				// redirect pages themselves.
				array( 'redirect' => 'no' ),
				( $forceKnown ? array( 'known', 'noclasses' ) : array() )
			) . '</li>';
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
		$this->getContext()->getOutput()->enableSectionEditLinks( false );
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

		# Check permissions
		$permissionErrors = $title->getUserPermissionsErrors( 'delete', $user );
		if ( count( $permissionErrors ) ) {
			throw new PermissionsError( 'delete', $permissionErrors );
		}

		# Read-only check...
		if ( wfReadOnly() ) {
			throw new ReadOnlyError;
		}

		# Better double-check that it hasn't been deleted yet!
		$this->mPage->loadPageData( 'fromdbmaster' );
		if ( !$this->mPage->exists() ) {
			$deleteLogPage = new LogPage( 'delete' );
			$outputPage = $context->getOutput();
			$outputPage->setPageTitle( $context->msg( 'cannotdelete-title', $title->getPrefixedText() ) );
			$outputPage->wrapWikiMsg( "<div class=\"error mw-error-cannotdelete\">\n$1\n</div>",
					array( 'cannotdelete', wfEscapeWikiText( $title->getPrefixedText() ) )
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

		$request = $context->getRequest();
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
			array( 'delete', $this->getTitle()->getPrefixedText() ) )
		) {
			# Flag to hide all contents of the archived revisions
			$suppress = $request->getVal( 'wpSuppress' ) && $user->isAllowed( 'suppressrevision' );

			$this->doDelete( $reason, $suppress );

			WatchAction::doWatchOrUnwatch( $request->getCheck( 'wpWatch' ), $title, $user );

			return;
		}

		// Generate deletion reason
		$hasHistory = false;
		if ( !$reason ) {
			try {
				$reason = $this->generateReason( $hasHistory );
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
			$dbr = wfGetDB( DB_SLAVE );
			$revisions = $edits = (int)$dbr->selectField(
				'revision',
				'COUNT(rev_page)',
				array( 'rev_page' => $title->getArticleID() ),
				__METHOD__
			);

			// @todo FIXME: i18n issue/patchwork message
			$context->getOutput()->addHTML(
				'<strong class="mw-delete-warning-revisions">' .
				$context->msg( 'historywarning' )->numParams( $revisions )->parse() .
				$context->msg( 'word-separator' )->escaped() . Linker::linkKnown( $title,
					$context->msg( 'history' )->escaped(),
					array(),
					array( 'action' => 'history' ) ) .
				'</strong>'
			);

			if ( $title->isBigDeletion() ) {
				global $wgDeleteRevisionsLimit;
				$context->getOutput()->wrapWikiMsg( "<div class='error'>\n$1\n</div>\n",
					array(
						'delete-warning-toobig',
						$context->getLanguage()->formatNum( $wgDeleteRevisionsLimit )
					)
				);
			}
		}

		$this->confirmDelete( $reason );
	}

	/**
	 * Output deletion confirmation dialog
	 * @todo FIXME: Move to another file?
	 * @param string $reason Prefilled reason
	 */
	public function confirmDelete( $reason ) {
		wfDebug( "Article::confirmDelete\n" );

		$title = $this->getTitle();
		$ctx = $this->getContext();
		$outputPage = $ctx->getOutput();
		$useMediaWikiUIEverywhere = $ctx->getConfig()->get( 'UseMediaWikiUIEverywhere' );
		$outputPage->setPageTitle( wfMessage( 'delete-confirm', $title->getPrefixedText() ) );
		$outputPage->addBacklinkSubtitle( $title );
		$outputPage->setRobotPolicy( 'noindex,nofollow' );
		$backlinkCache = $title->getBacklinkCache();
		if ( $backlinkCache->hasLinks( 'pagelinks' ) || $backlinkCache->hasLinks( 'templatelinks' ) ) {
			$outputPage->wrapWikiMsg( "<div class='mw-warning plainlinks'>\n$1\n</div>\n",
				'deleting-backlinks-warning' );
		}
		$outputPage->addWikiMsg( 'confirmdeletetext' );

		Hooks::run( 'ArticleConfirmDelete', array( $this, $outputPage, &$reason ) );

		$user = $this->getContext()->getUser();

		if ( $user->isAllowed( 'suppressrevision' ) ) {
			$suppress = Html::openElement( 'div', array( 'id' => 'wpDeleteSuppressRow' ) ) .
				Xml::checkLabel( wfMessage( 'revdelete-suppress' )->text(),
					'wpSuppress', 'wpSuppress', false, array( 'tabindex' => '4' ) ) .
				Html::closeElement( 'div' );
		} else {
			$suppress = '';
		}
		$checkWatch = $user->getBoolOption( 'watchdeletion' ) || $user->isWatched( $title );

		$form = Html::openElement( 'form', array( 'method' => 'post',
			'action' => $title->getLocalURL( 'action=delete' ), 'id' => 'deleteconfirm' ) ) .
			Html::openElement( 'fieldset', array( 'id' => 'mw-delete-table' ) ) .
			Html::element( 'legend', null, wfMessage( 'delete-legend' )->text() ) .
			Html::openElement( 'div', array( 'id' => 'mw-deleteconfirm-table' ) ) .
			Html::openElement( 'div', array( 'id' => 'wpDeleteReasonListRow' ) ) .
			Html::label( wfMessage( 'deletecomment' )->text(), 'wpDeleteReasonList' ) .
			'&nbsp;' .
			Xml::listDropDown(
				'wpDeleteReasonList',
				wfMessage( 'deletereason-dropdown' )->inContentLanguage()->text(),
				wfMessage( 'deletereasonotherlist' )->inContentLanguage()->text(),
				'',
				'wpReasonDropDown',
				1
			) .
			Html::closeElement( 'div' ) .
			Html::openElement( 'div', array( 'id' => 'wpDeleteReasonRow' ) ) .
			Html::label( wfMessage( 'deleteotherreason' )->text(), 'wpReason' ) .
			'&nbsp;' .
			Html::input( 'wpReason', $reason, 'text', array(
				'size' => '60',
				'maxlength' => '255',
				'tabindex' => '2',
				'id' => 'wpReason',
				'class' => 'mw-ui-input-inline',
				'autofocus'
			) ) .
			Html::closeElement( 'div' );

		# Disallow watching if user is not logged in
		if ( $user->isLoggedIn() ) {
			$form .=
					Xml::checkLabel( wfMessage( 'watchthis' )->text(),
						'wpWatch', 'wpWatch', $checkWatch, array( 'tabindex' => '3' ) );
		}

		$form .=
				Html::openElement( 'div' ) .
				$suppress .
					Xml::submitButton( wfMessage( 'deletepage' )->text(),
						array(
							'name' => 'wpConfirmB',
							'id' => 'wpConfirmB',
							'tabindex' => '5',
							'class' => $useMediaWikiUIEverywhere ? 'mw-ui-button mw-ui-destructive' : '',
						)
					) .
				Html::closeElement( 'div' ) .
			Html::closeElement( 'div' ) .
			Xml::closeElement( 'fieldset' ) .
			Html::hidden(
				'wpEditToken',
				$user->getEditToken( array( 'delete', $title->getPrefixedText() ) )
			) .
			Xml::closeElement( 'form' );

			if ( $user->isAllowed( 'editinterface' ) ) {
				$link = Linker::linkKnown(
					$ctx->msg( 'deletereason-dropdown' )->inContentLanguage()->getTitle(),
					wfMessage( 'delete-edit-reasonlist' )->escaped(),
					array(),
					array( 'action' => 'edit' )
				);
				$form .= '<p class="mw-delete-editreasons">' . $link . '</p>';
			}

		$outputPage->addHTML( $form );

		$deleteLogPage = new LogPage( 'delete' );
		$outputPage->addHTML( Xml::element( 'h2', null, $deleteLogPage->getName()->text() ) );
		LogEventsList::showLogExtract( $outputPage, 'delete', $title );
	}

	/**
	 * Perform a deletion and output success or failure messages
	 * @param string $reason
	 * @param bool $suppress
	 */
	public function doDelete( $reason, $suppress = false ) {
		$error = '';
		$context = $this->getContext();
		$outputPage = $context->getOutput();
		$user = $context->getUser();
		$status = $this->mPage->doDeleteArticleReal( $reason, $suppress, 0, true, $error, $user );

		if ( $status->isGood() ) {
			$deleted = $this->getTitle()->getPrefixedText();

			$outputPage->setPageTitle( wfMessage( 'actioncomplete' ) );
			$outputPage->setRobotPolicy( 'noindex,nofollow' );

			$loglink = '[[Special:Log/delete|' . wfMessage( 'deletionlog' )->text() . ']]';

			$outputPage->addWikiMsg( 'deletedtext', wfEscapeWikiText( $deleted ), $loglink );

			Hooks::run( 'ArticleDeleteAfterSuccess', array( $this->getTitle(), $outputPage ) );

			$outputPage->returnToMain( false );
		} else {
			$outputPage->setPageTitle(
				wfMessage( 'cannotdelete-title',
					$this->getTitle()->getPrefixedText() )
			);

			if ( $error == '' ) {
				$outputPage->addWikiText(
					"<div class=\"error mw-error-cannotdelete\">\n" . $status->getWikiText() . "\n</div>"
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
			wfDebug( "Article::tryFileCache(): called twice!?\n" );
			return false;
		}

		$called = true;
		if ( $this->isFileCacheable() ) {
			$cache = new HTMLFileCache( $this->getTitle(), 'view' );
			if ( $cache->isCacheGood( $this->mPage->getTouched() ) ) {
				wfDebug( "Article::tryFileCache(): about to load file\n" );
				$cache->loadFromFileCache( $this->getContext() );
				return true;
			} else {
				wfDebug( "Article::tryFileCache(): starting buffer\n" );
				ob_start( array( &$cache, 'saveToFileCache' ) );
			}
		} else {
			wfDebug( "Article::tryFileCache(): not cacheable\n" );
		}

		return false;
	}

	/**
	 * Check if the page can be cached
	 * @return bool
	 */
	public function isFileCacheable() {
		$cacheable = false;

		if ( HTMLFileCache::useFileCache( $this->getContext() ) ) {
			$cacheable = $this->mPage->getID()
				&& !$this->mRedirectedFrom && !$this->getTitle()->isRedirect();
			// Extension may have reason to disable file caching on some pages.
			if ( $cacheable ) {
				$cacheable = Hooks::run( 'IsFileCacheable', array( &$this ) );
			}
		}

		return $cacheable;
	}

	/**#@-*/

	/**
	 * Lightweight method to get the parser output for a page, checking the parser cache
	 * and so on. Doesn't consider most of the stuff that WikiPage::view is forced to
	 * consider, so it's not appropriate to use there.
	 *
	 * @since 1.16 (r52326) for LiquidThreads
	 *
	 * @param int|null $oldid Revision ID or null
	 * @param User $user The relevant user
	 * @return ParserOutput|bool ParserOutput or false if the given revision ID is not found
	 */
	public function getParserOutput( $oldid = null, User $user = null ) {
		//XXX: bypasses mParserOptions and thus setParserOptions()

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
				"Return RequestContext::getMain(); for sanity\n" );
			return RequestContext::getMain();
		}
	}

	/**
	 * Use PHP's magic __get handler to handle accessing of
	 * raw WikiPage fields for backwards compatibility.
	 *
	 * @param string $fname Field name
	 * @return mixed
	 */
	public function __get( $fname ) {
		if ( property_exists( $this->mPage, $fname ) ) {
			#wfWarn( "Access to raw $fname field " . __CLASS__ );
			return $this->mPage->$fname;
		}
		trigger_error( 'Inaccessible property via __get(): ' . $fname, E_USER_NOTICE );
	}

	/**
	 * Use PHP's magic __set handler to handle setting of
	 * raw WikiPage fields for backwards compatibility.
	 *
	 * @param string $fname Field name
	 * @param mixed $fvalue New value
	 */
	public function __set( $fname, $fvalue ) {
		if ( property_exists( $this->mPage, $fname ) ) {
			#wfWarn( "Access to raw $fname field of " . __CLASS__ );
			$this->mPage->$fname = $fvalue;
		// Note: extensions may want to toss on new fields
		} elseif ( !in_array( $fname, array( 'mContext', 'mPage' ) ) ) {
			$this->mPage->$fname = $fvalue;
		} else {
			trigger_error( 'Inaccessible property via __set(): ' . $fname, E_USER_NOTICE );
		}
	}

	/**
	 * Use PHP's magic __call handler to transform instance calls to
	 * WikiPage functions for backwards compatibility.
	 *
	 * @param string $fname Name of called method
	 * @param array $args Arguments to the method
	 * @return mixed
	 */
	public function __call( $fname, $args ) {
		if ( is_callable( array( $this->mPage, $fname ) ) ) {
			#wfWarn( "Call to " . __CLASS__ . "::$fname; please use WikiPage instead" );
			return call_user_func_array( array( $this->mPage, $fname ), $args );
		}
		trigger_error( 'Inaccessible function via __call(): ' . $fname, E_USER_ERROR );
	}

	// ****** B/C functions to work-around PHP silliness with __call and references ****** //

	/**
	 * @param array $limit
	 * @param array $expiry
	 * @param bool $cascade
	 * @param string $reason
	 * @param User $user
	 * @return Status
	 */
	public function doUpdateRestrictions( array $limit, array $expiry, &$cascade,
		$reason, User $user
	) {
		return $this->mPage->doUpdateRestrictions( $limit, $expiry, $cascade, $reason, $user );
	}

	/**
	 * @param array $limit
	 * @param string $reason
	 * @param int $cascade
	 * @param array $expiry
	 * @return bool
	 */
	public function updateRestrictions( $limit = array(), $reason = '',
		&$cascade = 0, $expiry = array()
	) {
		return $this->mPage->doUpdateRestrictions(
			$limit,
			$expiry,
			$cascade,
			$reason,
			$this->getContext()->getUser()
		);
	}

	/**
	 * @param string $reason
	 * @param bool $suppress
	 * @param int $id
	 * @param bool $commit
	 * @param string $error
	 * @return bool
	 */
	public function doDeleteArticle( $reason, $suppress = false, $id = 0,
		$commit = true, &$error = ''
	) {
		return $this->mPage->doDeleteArticle( $reason, $suppress, $id, $commit, $error );
	}

	/**
	 * @param string $fromP
	 * @param string $summary
	 * @param string $token
	 * @param bool $bot
	 * @param array $resultDetails
	 * @param User|null $user
	 * @return array
	 */
	public function doRollback( $fromP, $summary, $token, $bot, &$resultDetails, User $user = null ) {
		$user = is_null( $user ) ? $this->getContext()->getUser() : $user;
		return $this->mPage->doRollback( $fromP, $summary, $token, $bot, $resultDetails, $user );
	}

	/**
	 * @param string $fromP
	 * @param string $summary
	 * @param bool $bot
	 * @param array $resultDetails
	 * @param User|null $guser
	 * @return array
	 */
	public function commitRollback( $fromP, $summary, $bot, &$resultDetails, User $guser = null ) {
		$guser = is_null( $guser ) ? $this->getContext()->getUser() : $guser;
		return $this->mPage->commitRollback( $fromP, $summary, $bot, $resultDetails, $guser );
	}

	/**
	 * @param bool $hasHistory
	 * @return mixed
	 */
	public function generateReason( &$hasHistory ) {
		$title = $this->mPage->getTitle();
		$handler = ContentHandler::getForTitle( $title );
		return $handler->getAutoDeleteReason( $title, $hasHistory );
	}

	// ****** B/C functions for static methods ( __callStatic is PHP>=5.3 ) ****** //

	/**
	 * @return array
	 *
	 * @deprecated since 1.24, use WikiPage::selectFields() instead
	 */
	public static function selectFields() {
		wfDeprecated( __METHOD__, '1.24' );
		return WikiPage::selectFields();
	}

	/**
	 * @param Title $title
	 *
	 * @deprecated since 1.24, use WikiPage::onArticleCreate() instead
	 */
	public static function onArticleCreate( $title ) {
		wfDeprecated( __METHOD__, '1.24' );
		WikiPage::onArticleCreate( $title );
	}

	/**
	 * @param Title $title
	 *
	 * @deprecated since 1.24, use WikiPage::onArticleDelete() instead
	 */
	public static function onArticleDelete( $title ) {
		wfDeprecated( __METHOD__, '1.24' );
		WikiPage::onArticleDelete( $title );
	}

	/**
	 * @param Title $title
	 *
	 * @deprecated since 1.24, use WikiPage::onArticleEdit() instead
	 */
	public static function onArticleEdit( $title ) {
		wfDeprecated( __METHOD__, '1.24' );
		WikiPage::onArticleEdit( $title );
	}

	/**
	 * @param string $oldtext
	 * @param string $newtext
	 * @param int $flags
	 * @return string
	 * @deprecated since 1.21, use ContentHandler::getAutosummary() instead
	 */
	public static function getAutosummary( $oldtext, $newtext, $flags ) {
		return WikiPage::getAutosummary( $oldtext, $newtext, $flags );
	}
	// ******
}
