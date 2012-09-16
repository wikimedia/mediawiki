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
 * @todo move and rewrite code to an Action class
 *
 * See design.txt for an overview.
 * Note: edit user interface and cache support functions have been
 * moved to separate EditPage and HTMLFileCache classes.
 *
 * @internal documentation reviewed 15 Mar 2010
 */
class Article extends Page {
	/**@{{
	 * @private
	 */

	/**
	 * The context this Article is executed in
	 * @var IContextSource $mContext
	 */
	protected $mContext;

	/**
	 * The WikiPage object of this instance
	 * @var WikiPage $mPage
	 */
	protected $mPage;

	/**
	 * ParserOptions object for $wgUser articles
	 * @var ParserOptions $mParserOptions
	 */
	public $mParserOptions;

	/**
	 * Content of the revision we are working on
	 * @var string $mContent
	 */
	var $mContent;                    // !<

	/**
	 * Is the content ($mContent) already loaded?
	 * @var bool $mContentLoaded
	 */
	var $mContentLoaded = false;      // !<

	/**
	 * The oldid of the article that is to be shown, 0 for the
	 * current revision
	 * @var int|null $mOldId
	 */
	var $mOldId;                      // !<

	/**
	 * Title from which we were redirected here
	 * @var Title $mRedirectedFrom
	 */
	var $mRedirectedFrom = null;

	/**
	 * URL to redirect to or false if none
	 * @var string|false $mRedirectUrl
	 */
	var $mRedirectUrl = false;        // !<

	/**
	 * Revision ID of revision we are working on
	 * @var int $mRevIdFetched
	 */
	var $mRevIdFetched = 0;           // !<

	/**
	 * Revision we are working on
	 * @var Revision $mRevision
	 */
	var $mRevision = null;

	/**
	 * ParserOutput object
	 * @var ParserOutput $mParserOutput
	 */
	var $mParserOutput;

	/**@}}*/

	/**
	 * Constructor and clear the article
	 * @param $title Title Reference to a Title object.
	 * @param $oldId Integer revision ID, null to fetch from request, zero for current
	 */
	public function __construct( Title $title, $oldId = null ) {
		$this->mOldId = $oldId;
		$this->mPage = $this->newPage( $title );
	}

	/**
	 * @param $title Title
	 * @return WikiPage
	 */
	protected function newPage( Title $title ) {
		return new WikiPage( $title );
	}

	/**
	 * Constructor from a page id
	 * @param $id Int article ID to load
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
	 * @param $title Title
	 * @param $context IContextSource
	 * @return Article object
	 */
	public static function newFromTitle( $title, IContextSource $context ) {
		if ( NS_MEDIA == $title->getNamespace() ) {
			// FIXME: where should this go?
			$title = Title::makeTitle( NS_FILE, $title->getDBkey() );
		}

		$page = null;
		wfRunHooks( 'ArticleFromTitle', array( &$title, &$page ) );
		if ( !$page ) {
			switch( $title->getNamespace() ) {
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
	 * @param $page WikiPage
	 * @param $context IContextSource
	 * @return Article object
	 */
	public static function newFromWikiPage( WikiPage $page, IContextSource $context ) {
		$article = self::newFromTitle( $page->getTitle(), $context );
		$article->mPage = $page; // override to keep process cached vars
		return $article;
	}

	/**
	 * Tell the page view functions that this view was redirected
	 * from another page on the wiki.
	 * @param $from Title object.
	 */
	public function setRedirectedFrom( Title $from ) {
		$this->mRedirectedFrom = $from;
	}

	/**
	 * Get the title object of the article
	 *
	 * @return Title object of this page
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
	 * @return string Return the text of this revision
	 */
	public function getContent() {
		wfProfileIn( __METHOD__ );

		if ( $this->mPage->getID() === 0 ) {
			# If this is a MediaWiki:x message, then load the messages
			# and return the message value for x.
			if ( $this->getTitle()->getNamespace() == NS_MEDIAWIKI ) {
				$text = $this->getTitle()->getDefaultMessageText();
				if ( $text === false ) {
					$text = '';
				}
			} else {
				$message = $this->getContext()->getUser()->isLoggedIn() ? 'noarticletext' : 'noarticletextanon';
				$text = wfMessage( $message )->text();
			}
			wfProfileOut( __METHOD__ );

			return $text;
		} else {
			$this->fetchContent();
			wfProfileOut( __METHOD__ );

			return $this->mContent;
		}
	}

	/**
	 * @return int The oldid of the article that is to be shown, 0 for the
	 *             current revision
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
	 * @deprecated in 1.19; use fetchContent()
	 */
	function loadContent() {
		wfDeprecated( __METHOD__, '1.19' );
		$this->fetchContent();
	}

	/**
	 * Get text of an article from database
	 * Does *NOT* follow redirects.
	 *
	 * @return mixed string containing article contents, or false if null
	 */
	function fetchContent() {
		if ( $this->mContentLoaded ) {
			return $this->mContent;
		}

		wfProfileIn( __METHOD__ );

		$this->mContentLoaded = true;

		$oldid = $this->getOldID();

		# Pre-fill content with error message so that if something
		# fails we'll have something telling us what we intended.
		$this->mContent = wfMessage( 'missing-revision', $oldid )->plain();

		if ( $oldid ) {
			# $this->mRevision might already be fetched by getOldIDFromRequest()
			if ( !$this->mRevision ) {
				$this->mRevision = Revision::newFromId( $oldid );
				if ( !$this->mRevision ) {
					wfDebug( __METHOD__ . " failed to retrieve specified revision, id $oldid\n" );
					wfProfileOut( __METHOD__ );
					return false;
				}
			}
		} else {
			if ( !$this->mPage->getLatest() ) {
				wfDebug( __METHOD__ . " failed to find page data for title " . $this->getTitle()->getPrefixedText() . "\n" );
				wfProfileOut( __METHOD__ );
				return false;
			}

			$this->mRevision = $this->mPage->getRevision();
			if ( !$this->mRevision ) {
				wfDebug( __METHOD__ . " failed to retrieve current page, rev_id " . $this->mPage->getLatest() . "\n" );
				wfProfileOut( __METHOD__ );
				return false;
			}
		}

		// @todo FIXME: Horrible, horrible! This content-loading interface just plain sucks.
		// We should instead work with the Revision object when we need it...
		$this->mContent = $this->mRevision->getText( Revision::FOR_THIS_USER ); // Loads if user is allowed
		$this->mRevIdFetched = $this->mRevision->getId();

		wfRunHooks( 'ArticleAfterFetchContent', array( &$this, &$this->mContent ) );

		wfProfileOut( __METHOD__ );

		return $this->mContent;
	}

	/**
	 * No-op
	 * @deprecated since 1.18
	 */
	public function forUpdate() {
		wfDeprecated( __METHOD__, '1.18' );
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
		$this->fetchContent();

		return $this->mRevision;
	}

	/**
	 * Use this to fetch the rev ID used on page views
	 *
	 * @return int revision ID of last article revision
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
		global $wgParser, $wgUseFileCache, $wgUseETag, $wgDebugToolbar;

		wfProfileIn( __METHOD__ );

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
			wfProfileOut( __METHOD__ );
			throw new PermissionsError( 'read', $permErrors );
		}

		$outputPage = $this->getContext()->getOutput();
		# getOldID() may as well want us to redirect somewhere else
		if ( $this->mRedirectUrl ) {
			$outputPage->redirect( $this->mRedirectUrl );
			wfDebug( __METHOD__ . ": redirecting due to oldid\n" );
			wfProfileOut( __METHOD__ );

			return;
		}

		# If we got diff in the query, we want to see a diff page instead of the article.
		if ( $this->getContext()->getRequest()->getCheck( 'diff' ) ) {
			wfDebug( __METHOD__ . ": showing diff page\n" );
			$this->showDiffPage();
			wfProfileOut( __METHOD__ );

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

			# Is it client cached?
			if ( $outputPage->checkLastModified( $this->mPage->getTouched() ) ) {
				wfDebug( __METHOD__ . ": done 304\n" );
				wfProfileOut( __METHOD__ );

				return;
			# Try file cache
			} elseif ( $wgUseFileCache && $this->tryFileCache() ) {
				wfDebug( __METHOD__ . ": done file cache\n" );
				# tell wgOut that output is taken care of
				$outputPage->disable();
				$this->mPage->doViewUpdates( $user );
				wfProfileOut( __METHOD__ );

				return;
			}
		}

		# Should the parser cache be used?
		$useParserCache = $this->mPage->isParserCacheUsed( $parserOptions, $oldid );
		wfDebug( 'Article::view using parser cache: ' . ( $useParserCache ? 'yes' : 'no' ) . "\n" );
		if ( $user->getStubThreshold() ) {
			wfIncrStats( 'pcache_miss_stub' );
		}

		$this->showRedirectedFromHeader();
		$this->showNamespaceHeader();

		# Iterate through the possible ways of constructing the output text.
		# Keep going until $outputDone is set, or we run out of things to do.
		$pass = 0;
		$outputDone = false;
		$this->mParserOutput = false;

		while ( !$outputDone && ++$pass ) {
			switch( $pass ) {
				case 1:
					wfRunHooks( 'ArticleViewHeader', array( &$this, &$outputDone, &$useParserCache ) );
					break;
				case 2:
					# Early abort if the page doesn't exist
					if ( !$this->mPage->exists() ) {
						wfDebug( __METHOD__ . ": showing missing article\n" );
						$this->showMissingArticle();
						wfProfileOut( __METHOD__ );
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
					$this->fetchContent();

					# Are we looking at an old revision
					if ( $oldid && $this->mRevision ) {
						$this->setOldSubtitle( $oldid );

						if ( !$this->showDeletedRevisionHeader() ) {
							wfDebug( __METHOD__ . ": cannot view deleted revision\n" );
							wfProfileOut( __METHOD__ );
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
					} elseif( !wfRunHooks( 'ArticleViewCustom', array( $this->mContent, $this->getTitle(), $outputPage ) ) ) {
						# Allow extensions do their own custom view for certain pages
						$outputDone = true;
					} else {
						$text = $this->getContent();
						$rt = Title::newFromRedirectArray( $text );
						if ( $rt ) {
							wfDebug( __METHOD__ . ": showing redirect=no page\n" );
							# Viewing a redirect page (e.g. with parameter redirect=no)
							$outputPage->addHTML( $this->viewRedirect( $rt ) );
							# Parse just to get categories, displaytitle, etc.
							$this->mParserOutput = $wgParser->parse( $text, $this->getTitle(), $parserOptions );
							$outputPage->addParserOutputNoText( $this->mParserOutput );
							$outputDone = true;
						}
					}
					break;
				case 4:
					# Run the parse, protected by a pool counter
					wfDebug( __METHOD__ . ": doing uncached parse\n" );

					$poolArticleView = new PoolWorkArticleView( $this, $parserOptions,
						$this->getRevIdFetched(), $useParserCache, $this->getContent() );

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
						wfProfileOut( __METHOD__ );
						return;
					}

					$this->mParserOutput = $poolArticleView->getParserOutput();
					$outputPage->addParserOutput( $this->mParserOutput );

					# Don't cache a dirty ParserOutput object
					if ( $poolArticleView->getIsDirty() ) {
						$outputPage->setSquidMaxage( 0 );
						$outputPage->addHTML( "<!-- parser cache is expired, sending anyway due to pool overload-->\n" );
					}

					$outputDone = true;
					break;
				# Should be unreachable, but just in case...
				default:
					break 2;
			}
		}

		# Get the ParserOutput actually *displayed* here.
		# Note that $this->mParserOutput is the *current* version output.
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
		$this->mPage->doViewUpdates( $user );

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Adjust title for pages with displaytitle, -{T|}- or language conversion
	 * @param $pOutput ParserOutput
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
	 */
	public function showDiffPage() {
		$request = $this->getContext()->getRequest();
		$user = $this->getContext()->getUser();
		$diff = $request->getVal( 'diff' );
		$rcid = $request->getVal( 'rcid' );
		$diffOnly = $request->getBool( 'diffonly', $user->getOption( 'diffonly' ) );
		$purge = $request->getVal( 'action' ) == 'purge';
		$unhide = $request->getInt( 'unhide' ) == 1;
		$oldid = $this->getOldID();

		$de = new DifferenceEngine( $this->getContext(), $oldid, $diff, $rcid, $purge, $unhide );
		// DifferenceEngine directly fetched the revision:
		$this->mRevIdFetched = $de->mNewid;
		$de->showDiffPage( $diffOnly );

		if ( $diff == 0 || $diff == $this->mPage->getLatest() ) {
			# Run view updates for current revision only
			$this->mPage->doViewUpdates( $user );
		}
	}

	/**
	 * Show a page view for a page formatted as CSS or JavaScript. To be called by
	 * Article::view() only.
	 *
	 * This is hooked by SyntaxHighlight_GeSHi to do syntax highlighting of these
	 * page views.
	 */
	protected function showCssOrJsPage() {
		$dir = $this->getContext()->getLanguage()->getDir();
		$lang = $this->getContext()->getLanguage()->getCode();

		$outputPage = $this->getContext()->getOutput();
		$outputPage->wrapWikiMsg( "<div id='mw-clearyourcache' lang='$lang' dir='$dir' class='mw-content-$dir'>\n$1\n</div>",
			'clearyourcache' );

		// Give hooks a chance to customise the output
		if ( wfRunHooks( 'ShowRawCssJs', array( $this->mContent, $this->getTitle(), $outputPage ) ) ) {
			// Wrap the whole lot in a <pre> and don't parse
			$m = array();
			preg_match( '!\.(css|js)$!u', $this->getTitle()->getText(), $m );
			$outputPage->addHTML( "<pre class=\"mw-code mw-{$m[1]}\" dir=\"ltr\">\n" );
			$outputPage->addHTML( htmlspecialchars( $this->mContent ) );
			$outputPage->addHTML( "\n</pre>\n" );
		}
	}

	/**
	 * Get the robot policy to be used for the current view
	 * @param $action String the action= GET parameter
	 * @param $pOutput ParserOutput
	 * @return Array the policy that should be set
	 * TODO: actions other than 'view'
	 */
	public function getRobotPolicy( $action, $pOutput ) {
		global $wgArticleRobotPolicies, $wgNamespaceRobotPolicies, $wgDefaultRobotPolicy;

		$ns = $this->getTitle()->getNamespace();

		if ( $ns == NS_USER || $ns == NS_USER_TALK ) {
			# Don't index user and user talk pages for blocked users (bug 11443)
			if ( !$this->getTitle()->isSubpage() ) {
				if ( Block::newFromTarget( null, $this->getTitle()->getText() ) instanceof Block ) {
					return array(
						'index'  => 'noindex',
						'follow' => 'nofollow'
					);
				}
			}
		}

		if ( $this->mPage->getID() === 0 || $this->getOldID() ) {
			# Non-articles (special pages etc), and old revisions
			return array(
				'index'  => 'noindex',
				'follow' => 'nofollow'
			);
		} elseif ( $this->getContext()->getOutput()->isPrintable() ) {
			# Discourage indexing of printable versions, but encourage following
			return array(
				'index'  => 'noindex',
				'follow' => 'follow'
			);
		} elseif ( $this->getContext()->getRequest()->getInt( 'curid' ) ) {
			# For ?curid=x urls, disallow indexing
			return array(
				'index'  => 'noindex',
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
	 * @param $policy Mixed, returns empty array on null/false/'', transparent
	 *            to already-converted arrays, converts String.
	 * @return Array: 'index' => \<indexpolicy\>, 'follow' => \<followpolicy\>
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
	 * @return boolean
	 */
	public function showRedirectedFromHeader() {
		global $wgRedirectSources;
		$outputPage = $this->getContext()->getOutput();

		$rdfrom = $this->getContext()->getRequest()->getVal( 'rdfrom' );

		if ( isset( $this->mRedirectedFrom ) ) {
			// This is an internally redirected page view.
			// We'll need a backlink to the source page for navigation.
			if ( wfRunHooks( 'ArticleViewRedirect', array( &$this ) ) ) {
				$redir = Linker::linkKnown(
					$this->mRedirectedFrom,
					null,
					array(),
					array( 'redirect' => 'no' )
				);

				$outputPage->addSubtitle( wfMessage( 'redirectedfrom' )->rawParams( $redir ) );

				// Set the fragment if one was specified in the redirect
				if ( strval( $this->getTitle()->getFragment() ) != '' ) {
					$fragment = Xml::escapeJsString( $this->getTitle()->getFragmentForURL() );
					$outputPage->addInlineScript( "redirectToFragment(\"$fragment\");" );
				}

				// Add a <link rel="canonical"> tag
				$outputPage->addLink( array( 'rel' => 'canonical',
					'href' => $this->getTitle()->getLocalURL() )
				);

				// Tell the output object that the user arrived at this article through a redirect
				$outputPage->setRedirectedFrom( $this->mRedirectedFrom );

				return true;
			}
		} elseif ( $rdfrom ) {
			// This is an externally redirected view, from some other wiki.
			// If it was reported from a trusted site, supply a backlink.
			if ( $wgRedirectSources && preg_match( $wgRedirectSources, $rdfrom ) ) {
				$redir = Linker::makeExternalLink( $rdfrom, $rdfrom );
				$outputPage->addSubtitle( wfMessage( 'redirectedfrom' )->rawParams( $redir ) );

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
				$this->getContext()->getOutput()->wrapWikiMsg( "<div class=\"mw-talkpageheader\">\n$1\n</div>", array( 'talkpageheader' ) );
			}
		}
	}

	/**
	 * Show the footer section of an ordinary page view
	 */
	public function showViewFooter() {
		# check if we're displaying a [[User talk:x.x.x.x]] anonymous talk page
		if ( $this->getTitle()->getNamespace() == NS_USER_TALK && IP::isValid( $this->getTitle()->getText() ) ) {
			$this->getContext()->getOutput()->addWikiMsg( 'anontalkpagetext' );
		}

		# If we have been passed an &rcid= parameter, we want to give the user a
		# chance to mark this new article as patrolled.
		$this->showPatrolFooter();

		wfRunHooks( 'ArticleViewFooter', array( $this ) );

	}

	/**
	 * If patrol is possible, output a patrol UI box. This is called from the
	 * footer section of ordinary page views. If patrol is not possible or not
	 * desired, does nothing.
	 */
	public function showPatrolFooter() {
		$request = $this->getContext()->getRequest();
		$outputPage = $this->getContext()->getOutput();
		$user = $this->getContext()->getUser();
		$rcid = $request->getVal( 'rcid' );

		if ( !$rcid || !$this->getTitle()->quickUserCan( 'patrol', $user ) ) {
			return;
		}

		$token = $user->getEditToken( $rcid );
		$outputPage->preventClickjacking();

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
	}

	/**
	 * Show the error text for a missing article. For articles in the MediaWiki
	 * namespace, show the default message text. To be called from Article::view().
	 */
	public function showMissingArticle() {
		global $wgSend404Code;
		$outputPage = $this->getContext()->getOutput();

		# Show info in user (talk) namespace. Does the user exist? Is he blocked?
		if ( $this->getTitle()->getNamespace() == NS_USER || $this->getTitle()->getNamespace() == NS_USER_TALK ) {
			$parts = explode( '/', $this->getTitle()->getText() );
			$rootPart = $parts[0];
			$user = User::newFromName( $rootPart, false /* allow IP users*/ );
			$ip = User::isIP( $rootPart );

			if ( !($user && $user->isLoggedIn()) && !$ip ) { # User does not exist
				$outputPage->wrapWikiMsg( "<div class=\"mw-userpage-userdoesnotexist error\">\n\$1\n</div>",
					array( 'userpage-userdoesnotexist-view', wfEscapeWikiText( $rootPart ) ) );
			} elseif ( $user->isBlocked() ) { # Show log extract if the user is currently blocked
				LogEventsList::showLogExtract(
					$outputPage,
					'block',
					$user->getUserPage(),
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
			}
		}

		wfRunHooks( 'ShowMissingArticle', array( $this ) );

		# Show delete and move logs
		LogEventsList::showLogExtract( $outputPage, array( 'delete', 'move' ), $this->getTitle(), '',
			array(  'lim' => 10,
				'conds' => array( "log_action != 'revision'" ),
				'showIfEmpty' => false,
				'msgKey' => array( 'moveddeleted-notice' ) )
		);

		if ( !$this->mPage->hasViewableContent() && $wgSend404Code ) {
			// If there's no backing content, send a 404 Not Found
			// for better machine handling of broken links.
			$this->getContext()->getRequest()->response()->header( "HTTP/1.1 404 Not Found" );
		}

		$hookResult = wfRunHooks( 'BeforeDisplayNoArticleText', array( $this ) );

		if ( ! $hookResult ) {
			return;
		}

		# Show error message
		$oldid = $this->getOldID();
		if ( $oldid ) {
			$text = wfMessage( 'missing-revision', $oldid )->plain();
		} elseif ( $this->getTitle()->getNamespace() === NS_MEDIAWIKI ) {
			// Use the default message text
			$text = $this->getTitle()->getDefaultMessageText();
		} elseif ( $this->getTitle()->quickUserCan( 'create', $this->getContext()->getUser() )
			&& $this->getTitle()->quickUserCan( 'edit', $this->getContext()->getUser() )
		) {
			$text = wfMessage( 'noarticletext' )->plain();
		} else {
			$text = wfMessage( 'noarticletext-nopermission' )->plain();
		}
		$text = "<div class='noarticletext'>\n$text\n</div>";

		$outputPage->addWikiText( $text );
	}

	/**
	 * If the revision requested for view is deleted, check permissions.
	 * Send either an error message or a warning header to the output.
	 *
	 * @return boolean true if the view is allowed, false if not.
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
			$link = $this->getTitle()->getFullUrl( "oldid={$oldid}&unhide=1" );
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
	 * @param $oldid int: revision ID of this article revision
	 */
	public function setOldSubtitle( $oldid = 0 ) {
		if ( !wfRunHooks( 'DisplayOldSubtitle', array( &$this, &$oldid ) ) ) {
			return;
		}

		$unhide = $this->getContext()->getRequest()->getInt( 'unhide' ) == 1;

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
		$language = $this->getContext()->getLanguage();
		$user = $this->getContext()->getUser();

		$td = $language->userTimeAndDate( $timestamp, $user );
		$tddate = $language->userDate( $timestamp, $user );
		$tdtime = $language->userTime( $timestamp, $user );

		# Show user links if allowed to see them. If hidden, then show them only if requested...
		$userlinks = Linker::revUserTools( $revision, !$unhide );

		$infomsg = $current && !wfMessage( 'revision-info-current' )->isDisabled()
			? 'revision-info-current'
			: 'revision-info';

		$outputPage = $this->getContext()->getOutput();
		$outputPage->addSubtitle( "<div id=\"mw-{$infomsg}\">" . wfMessage( $infomsg,
			$td )->rawParams( $userlinks )->params( $revision->getID(), $tddate,
			$tdtime, $revision->getUser() )->parse() . "</div>" );

		$lnk = $current
			? wfMessage( 'currentrevisionlink' )->escaped()
			: Linker::linkKnown(
				$this->getTitle(),
				wfMessage( 'currentrevisionlink' )->escaped(),
				array(),
				$extraParams
			);
		$curdiff = $current
			? wfMessage( 'diff' )->escaped()
			: Linker::linkKnown(
				$this->getTitle(),
				wfMessage( 'diff' )->escaped(),
				array(),
				array(
					'diff' => 'cur',
					'oldid' => $oldid
				) + $extraParams
			);
		$prev = $this->getTitle()->getPreviousRevisionID( $oldid ) ;
		$prevlink = $prev
			? Linker::linkKnown(
				$this->getTitle(),
				wfMessage( 'previousrevision' )->escaped(),
				array(),
				array(
					'direction' => 'prev',
					'oldid' => $oldid
				) + $extraParams
			)
			: wfMessage( 'previousrevision' )->escaped();
		$prevdiff = $prev
			? Linker::linkKnown(
				$this->getTitle(),
				wfMessage( 'diff' )->escaped(),
				array(),
				array(
					'diff' => 'prev',
					'oldid' => $oldid
				) + $extraParams
			)
			: wfMessage( 'diff' )->escaped();
		$nextlink = $current
			? wfMessage( 'nextrevision' )->escaped()
			: Linker::linkKnown(
				$this->getTitle(),
				wfMessage( 'nextrevision' )->escaped(),
				array(),
				array(
					'direction' => 'next',
					'oldid' => $oldid
				) + $extraParams
			);
		$nextdiff = $current
			? wfMessage( 'diff' )->escaped()
			: Linker::linkKnown(
				$this->getTitle(),
				wfMessage( 'diff' )->escaped(),
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
			wfMessage( 'revision-nav' )->rawParams(
				$prevdiff, $prevlink, $lnk, $curdiff, $nextlink, $nextdiff
			)->escaped() . "</div>" );
	}

	/**
	 * View redirect
	 *
	 * @param $target Title|Array of destination(s) to redirect
	 * @param $appendSubtitle Boolean [optional]
	 * @param $forceKnown Boolean: should the image be shown as a bluelink regardless of existence?
	 * @return string containing HMTL with redirect link
	 */
	public function viewRedirect( $target, $appendSubtitle = true, $forceKnown = false ) {
		global $wgStylePath;

		if ( !is_array( $target ) ) {
			$target = array( $target );
		}

		$lang = $this->getTitle()->getPageLanguage();
		$imageDir = $lang->getDir();

		if ( $appendSubtitle ) {
			$out = $this->getContext()->getOutput();
			$out->addSubtitle( wfMessage( 'redirectpagesub' )->escaped() );
		}

		// the loop prepends the arrow image before the link, so the first case needs to be outside

		/**
		 * @var $title Title
		 */
		$title = array_shift( $target );

		if ( $forceKnown ) {
			$link = Linker::linkKnown( $title, htmlspecialchars( $title->getFullText() ) );
		} else {
			$link = Linker::link( $title, htmlspecialchars( $title->getFullText() ) );
		}

		$nextRedirect = $wgStylePath . '/common/images/nextredirect' . $imageDir . '.png';
		$alt = $lang->isRTL() ? '←' : '→';
		// Automatically append redirect=no to each link, since most of them are redirect pages themselves.
		foreach ( $target as $rt ) {
			$link .= Html::element( 'img', array( 'src' => $nextRedirect, 'alt' => $alt ) );
			if ( $forceKnown ) {
				$link .= Linker::linkKnown( $rt, htmlspecialchars( $rt->getFullText(), array(), array( 'redirect' => 'no' ) ) );
			} else {
				$link .= Linker::link( $rt, htmlspecialchars( $rt->getFullText() ), array(), array( 'redirect' => 'no' ) );
			}
		}

		$imageUrl = $wgStylePath . '/common/images/redirect' . $imageDir . '.png';
		return '<div class="redirectMsg">' .
			Html::element( 'img', array( 'src' => $imageUrl, 'alt' => '#REDIRECT' ) ) .
			'<span class="redirectText">' . $link . '</span></div>';
	}

	/**
	 * Handle action=render
	 */
	public function render() {
		$this->getContext()->getOutput()->setArticleBodyOnly( true );
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
		$user = $this->getContext()->getUser();

		# Check permissions
		$permission_errors = $title->getUserPermissionsErrors( 'delete', $user );
		if ( count( $permission_errors ) ) {
			throw new PermissionsError( 'delete', $permission_errors );
		}

		# Read-only check...
		if ( wfReadOnly() ) {
			throw new ReadOnlyError;
		}

		# Better double-check that it hasn't been deleted yet!
		$this->mPage->loadPageData( 'fromdbmaster' );
		if ( !$this->mPage->exists() ) {
			$deleteLogPage = new LogPage( 'delete' );
			$outputPage = $this->getContext()->getOutput();
			$outputPage->setPageTitle( wfMessage( 'cannotdelete-title', $title->getPrefixedText() ) );
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

		$request = $this->getContext()->getRequest();
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
			array( 'delete', $this->getTitle()->getPrefixedText() ) ) )
		{
			# Flag to hide all contents of the archived revisions
			$suppress = $request->getVal( 'wpSuppress' ) && $user->isAllowed( 'suppressrevision' );

			$this->doDelete( $reason, $suppress );

			if ( $user->isLoggedIn() && $request->getCheck( 'wpWatch' ) != $user->isWatched( $title ) ) {
				if ( $request->getCheck( 'wpWatch' ) ) {
					WatchAction::doWatch( $title, $user );
				} else {
					WatchAction::doUnwatch( $title, $user );
				}
			}

			return;
		}

		// Generate deletion reason
		$hasHistory = false;
		if ( !$reason ) {
			$reason = $this->generateReason( $hasHistory );
		}

		// If the page has a history, insert a warning
		if ( $hasHistory ) {
			$revisions = $this->mTitle->estimateRevisionCount();
			// @todo FIXME: i18n issue/patchwork message
			$this->getContext()->getOutput()->addHTML( '<strong class="mw-delete-warning-revisions">' .
				wfMessage( 'historywarning' )->numParams( $revisions )->parse() .
				wfMessage( 'word-separator' )->plain() . Linker::linkKnown( $title,
					wfMessage( 'history' )->escaped(),
					array( 'rel' => 'archives' ),
					array( 'action' => 'history' ) ) .
				'</strong>'
			);

			if ( $this->mTitle->isBigDeletion() ) {
				global $wgDeleteRevisionsLimit;
				$this->getContext()->getOutput()->wrapWikiMsg( "<div class='error'>\n$1\n</div>\n",
					array( 'delete-warning-toobig', $this->getContext()->getLanguage()->formatNum( $wgDeleteRevisionsLimit ) ) );
			}
		}

		$this->confirmDelete( $reason );
	}

	/**
	 * Output deletion confirmation dialog
	 * @todo FIXME: Move to another file?
	 * @param $reason String: prefilled reason
	 */
	public function confirmDelete( $reason ) {
		wfDebug( "Article::confirmDelete\n" );

		$outputPage = $this->getContext()->getOutput();
		$outputPage->setPageTitle( wfMessage( 'delete-confirm', $this->getTitle()->getPrefixedText() ) );
		$outputPage->addBacklinkSubtitle( $this->getTitle() );
		$outputPage->setRobotPolicy( 'noindex,nofollow' );
		$outputPage->addWikiMsg( 'confirmdeletetext' );

		wfRunHooks( 'ArticleConfirmDelete', array( $this, $outputPage, &$reason ) );

		$user = $this->getContext()->getUser();

		if ( $user->isAllowed( 'suppressrevision' ) ) {
			$suppress = "<tr id=\"wpDeleteSuppressRow\">
					<td></td>
					<td class='mw-input'><strong>" .
						Xml::checkLabel( wfMessage( 'revdelete-suppress' )->text(),
							'wpSuppress', 'wpSuppress', false, array( 'tabindex' => '4' ) ) .
					"</strong></td>
				</tr>";
		} else {
			$suppress = '';
		}
		$checkWatch = $user->getBoolOption( 'watchdeletion' ) || $user->isWatched( $this->getTitle() );

		$form = Xml::openElement( 'form', array( 'method' => 'post',
			'action' => $this->getTitle()->getLocalURL( 'action=delete' ), 'id' => 'deleteconfirm' ) ) .
			Xml::openElement( 'fieldset', array( 'id' => 'mw-delete-table' ) ) .
			Xml::tags( 'legend', null, wfMessage( 'delete-legend' )->escaped() ) .
			Xml::openElement( 'table', array( 'id' => 'mw-deleteconfirm-table' ) ) .
			"<tr id=\"wpDeleteReasonListRow\">
				<td class='mw-label'>" .
					Xml::label( wfMessage( 'deletecomment' )->text(), 'wpDeleteReasonList' ) .
				"</td>
				<td class='mw-input'>" .
					Xml::listDropDown( 'wpDeleteReasonList',
						wfMessage( 'deletereason-dropdown' )->inContentLanguage()->text(),
						wfMessage( 'deletereasonotherlist' )->inContentLanguage()->text(), '', 'wpReasonDropDown', 1 ) .
				"</td>
			</tr>
			<tr id=\"wpDeleteReasonRow\">
				<td class='mw-label'>" .
					Xml::label( wfMessage( 'deleteotherreason' )->text(), 'wpReason' ) .
				"</td>
				<td class='mw-input'>" .
				Html::input( 'wpReason', $reason, 'text', array(
					'size' => '60',
					'maxlength' => '255',
					'tabindex' => '2',
					'id' => 'wpReason',
					'autofocus'
				) ) .
				"</td>
			</tr>";

		# Disallow watching if user is not logged in
		if ( $user->isLoggedIn() ) {
			$form .= "
			<tr>
				<td></td>
				<td class='mw-input'>" .
					Xml::checkLabel( wfMessage( 'watchthis' )->text(),
						'wpWatch', 'wpWatch', $checkWatch, array( 'tabindex' => '3' ) ) .
				"</td>
			</tr>";
		}

		$form .= "
			$suppress
			<tr>
				<td></td>
				<td class='mw-submit'>" .
					Xml::submitButton( wfMessage( 'deletepage' )->text(),
						array( 'name' => 'wpConfirmB', 'id' => 'wpConfirmB', 'tabindex' => '5' ) ) .
				"</td>
			</tr>" .
			Xml::closeElement( 'table' ) .
			Xml::closeElement( 'fieldset' ) .
			Html::hidden( 'wpEditToken', $user->getEditToken( array( 'delete', $this->getTitle()->getPrefixedText() ) ) ) .
			Xml::closeElement( 'form' );

			if ( $user->isAllowed( 'editinterface' ) ) {
				$title = Title::makeTitle( NS_MEDIAWIKI, 'Deletereason-dropdown' );
				$link = Linker::link(
					$title,
					wfMessage( 'delete-edit-reasonlist' )->escaped(),
					array(),
					array( 'action' => 'edit' )
				);
				$form .= '<p class="mw-delete-editreasons">' . $link . '</p>';
			}

		$outputPage->addHTML( $form );

		$deleteLogPage = new LogPage( 'delete' );
		$outputPage->addHTML( Xml::element( 'h2', null, $deleteLogPage->getName()->text() ) );
		LogEventsList::showLogExtract( $outputPage, 'delete',
			$this->getTitle()
		);
	}

	/**
	 * Perform a deletion and output success or failure messages
	 * @param $reason
	 * @param $suppress bool
	 */
	public function doDelete( $reason, $suppress = false ) {
		$error = '';
		$outputPage = $this->getContext()->getOutput();
		$status = $this->mPage->doDeleteArticleReal( $reason, $suppress, 0, true, $error );
		if ( $status->isGood() ) {
			$deleted = $this->getTitle()->getPrefixedText();

			$outputPage->setPageTitle( wfMessage( 'actioncomplete' ) );
			$outputPage->setRobotPolicy( 'noindex,nofollow' );

			$loglink = '[[Special:Log/delete|' . wfMessage( 'deletionlog' )->text() . ']]';

			$outputPage->addWikiMsg( 'deletedtext', wfEscapeWikiText( $deleted ), $loglink );
			$outputPage->returnToMain( false );
		} else {
			$outputPage->setPageTitle( wfMessage( 'cannotdelete-title', $this->getTitle()->getPrefixedText() ) );
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
	 * @return boolean true if cached version send, false otherwise
	 */
	protected function tryFileCache() {
		static $called = false;

		if ( $called ) {
			wfDebug( "Article::tryFileCache(): called twice!?\n" );
			return false;
		}

		$called = true;
		if ( $this->isFileCacheable() ) {
			$cache = HTMLFileCache::newFromTitle( $this->getTitle(), 'view' );
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
				$cacheable = wfRunHooks( 'IsFileCacheable', array( &$this ) );
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
	 * @param $oldid mixed integer Revision ID or null
	 * @param $user User The relevant user
	 * @return ParserOutput or false if the given revsion ID is not found
	 */
	public function getParserOutput( $oldid = null, User $user = null ) {
		if ( $user === null ) {
			$parserOptions = $this->getParserOptions();
		} else {
			$parserOptions = $this->mPage->makeParserOptions( $user );
		}

		return $this->mPage->getParserOutput( $parserOptions, $oldid );
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
	 * @param $context IContextSource
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
			wfDebug( __METHOD__ . " called and \$mContext is null. Return RequestContext::getMain(); for sanity\n" );
			return RequestContext::getMain();
		}
	}

	/**
	 * Info about this page
	 * @deprecated since 1.19
	 */
	public function info() {
		wfDeprecated( __METHOD__, '1.19' );
		Action::factory( 'info', $this )->show();
	}

	/**
	 * Mark this particular edit/page as patrolled
	 * @deprecated since 1.18
	 */
	public function markpatrolled() {
		wfDeprecated( __METHOD__, '1.18' );
		Action::factory( 'markpatrolled', $this )->show();
	}

	/**
	 * Handle action=purge
	 * @deprecated since 1.19
	 * @return Action|bool|null false if the action is disabled, null if it is not recognised
	 */
	public function purge() {
		return Action::factory( 'purge', $this )->show();
	}

	/**
	 * Handle action=revert
	 * @deprecated since 1.19
	 */
	public function revert() {
		wfDeprecated( __METHOD__, '1.19' );
		Action::factory( 'revert', $this )->show();
	}

	/**
	 * Handle action=rollback
	 * @deprecated since 1.19
	 */
	public function rollback() {
		wfDeprecated( __METHOD__, '1.19' );
		Action::factory( 'rollback', $this )->show();
	}

	/**
	 * User-interface handler for the "watch" action.
	 * Requires Request to pass a token as of 1.18.
	 * @deprecated since 1.18
	 */
	public function watch() {
		wfDeprecated( __METHOD__, '1.18' );
		Action::factory( 'watch', $this )->show();
	}

	/**
	 * Add this page to the current user's watchlist
	 *
	 * This is safe to be called multiple times
	 *
	 * @return bool true on successful watch operation
	 * @deprecated since 1.18
	 */
	public function doWatch() {
		wfDeprecated( __METHOD__, '1.18' );
		return WatchAction::doWatch( $this->getTitle(), $this->getContext()->getUser() );
	}

	/**
	 * User interface handler for the "unwatch" action.
	 * Requires Request to pass a token as of 1.18.
	 * @deprecated since 1.18
	 */
	public function unwatch() {
		wfDeprecated( __METHOD__, '1.18' );
		Action::factory( 'unwatch', $this )->show();
	}

	/**
	 * Stop watching a page
	 * @return bool true on successful unwatch
	 * @deprecated since 1.18
	 */
	public function doUnwatch() {
		wfDeprecated( __METHOD__, '1.18' );
		return WatchAction::doUnwatch( $this->getTitle(), $this->getContext()->getUser() );
	}

	/**
	 * Output a redirect back to the article.
	 * This is typically used after an edit.
	 *
	 * @deprecated in 1.18; call OutputPage::redirect() directly
	 * @param $noRedir Boolean: add redirect=no
	 * @param $sectionAnchor String: section to redirect to, including "#"
	 * @param $extraQuery String: extra query params
	 */
	public function doRedirect( $noRedir = false, $sectionAnchor = '', $extraQuery = '' ) {
		wfDeprecated( __METHOD__, '1.18' );
		if ( $noRedir ) {
			$query = 'redirect=no';
			if ( $extraQuery )
				$query .= "&$extraQuery";
		} else {
			$query = $extraQuery;
		}

		$this->getContext()->getOutput()->redirect( $this->getTitle()->getFullURL( $query ) . $sectionAnchor );
	}

	/**
	 * Use PHP's magic __get handler to handle accessing of
	 * raw WikiPage fields for backwards compatibility.
	 *
	 * @param $fname String Field name
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
	 * @param $fname String Field name
	 * @param $fvalue mixed New value
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
	 * @param $fname String Name of called method
	 * @param $args Array Arguments to the method
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
	 * @param $limit array
	 * @param $expiry array
	 * @param $cascade bool
	 * @param $reason string
	 * @param $user User
	 * @return Status
	 */
	public function doUpdateRestrictions( array $limit, array $expiry, &$cascade, $reason, User $user ) {
		return $this->mPage->doUpdateRestrictions( $limit, $expiry, $cascade, $reason, $user );
	}

	/**
	 * @param $limit array
	 * @param $reason string
	 * @param $cascade int
	 * @param $expiry array
	 * @return bool
	 */
	public function updateRestrictions( $limit = array(), $reason = '', &$cascade = 0, $expiry = array() ) {
		return $this->mPage->updateRestrictions( $limit, $reason, $cascade, $expiry );
	}

	/**
	 * @param $reason string
	 * @param $suppress bool
	 * @param $id int
	 * @param $commit bool
	 * @param $error string
	 * @return bool
	 */
	public function doDeleteArticle( $reason, $suppress = false, $id = 0, $commit = true, &$error = '' ) {
		return $this->mPage->doDeleteArticle( $reason, $suppress, $id, $commit, $error );
	}

	/**
	 * @param $fromP
	 * @param $summary
	 * @param $token
	 * @param $bot
	 * @param $resultDetails
	 * @param $user User
	 * @return array
	 */
	public function doRollback( $fromP, $summary, $token, $bot, &$resultDetails, User $user = null ) {
		$user = is_null( $user ) ? $this->getContext()->getUser() : $user;
		return $this->mPage->doRollback( $fromP, $summary, $token, $bot, $resultDetails, $user );
	}

	/**
	 * @param $fromP
	 * @param $summary
	 * @param $bot
	 * @param $resultDetails
	 * @param $guser User
	 * @return array
	 */
	public function commitRollback( $fromP, $summary, $bot, &$resultDetails, User $guser = null ) {
		$guser = is_null( $guser ) ? $this->getContext()->getUser() : $guser;
		return $this->mPage->commitRollback( $fromP, $summary, $bot, $resultDetails, $guser );
	}

	/**
	 * @param $hasHistory bool
	 * @return mixed
	 */
	public function generateReason( &$hasHistory ) {
		return $this->mPage->getAutoDeleteReason( $hasHistory );
	}

	// ****** B/C functions for static methods ( __callStatic is PHP>=5.3 ) ****** //

	/**
	 * @return array
	 */
	public static function selectFields() {
		return WikiPage::selectFields();
	}

	/**
	 * @param $title Title
	 */
	public static function onArticleCreate( $title ) {
		WikiPage::onArticleCreate( $title );
	}

	/**
	 * @param $title Title
	 */
	public static function onArticleDelete( $title ) {
		WikiPage::onArticleDelete( $title );
	}

	/**
	 * @param $title Title
	 */
	public static function onArticleEdit( $title ) {
		WikiPage::onArticleEdit( $title );
	}

	/**
	 * @param $oldtext
	 * @param $newtext
	 * @param $flags
	 * @return string
	 */
	public static function getAutosummary( $oldtext, $newtext, $flags ) {
		return WikiPage::getAutosummary( $oldtext, $newtext, $flags );
	}
	// ******
}
