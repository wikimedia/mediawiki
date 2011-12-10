<?php
/**
 * File for articles
 * @file
 */

/**
 * Class for viewing MediaWiki article and history.
 *
 * This maintains WikiPage functions for backwards compatibility.
 *
 * @TODO: move and rewrite code to an Action class
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
	 * @var IContextSource
	 */
	protected $mContext;

	/**
	 * @var WikiPage
	 */
	protected $mPage;

	/**
	 * @var ParserOptions: ParserOptions object for $wgUser articles
	 */
	public $mParserOptions;

	var $mContent;                    // !<
	var $mContentLoaded = false;      // !<
	var $mOldId;                      // !<

	/**
	 * @var Title
	 */
	var $mRedirectedFrom = null;

	/**
	 * @var mixed: boolean false or URL string
	 */
	var $mRedirectUrl = false;        // !<
	var $mRevIdFetched = 0;           // !<

	/**
	 * @var Revision
	 */
	var $mRevision = null;

	/**
	 * @var ParserOutput
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
	 * @return Title object of this page
	 */
	public function getTitle() {
		return $this->mPage->getTitle();
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
	 * the shortcut in Article::followRedirect()
	 *
	 * This function has side effects! Do not use this function if you
	 * only want the real revision text if any.
	 *
	 * @return Return the text of this revision
	 */
	public function getContent() {
		global $wgUser;

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
				$text = wfMsgExt( $wgUser->isLoggedIn() ? 'noarticletext' : 'noarticletextanon', 'parsemag' );
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
		global $wgRequest;

		$this->mRedirectUrl = false;

		$oldid = $wgRequest->getIntOrNull( 'oldid' );

		if ( $oldid === null ) {
			return 0;
		}

		if ( $oldid !== 0 ) {
			# Load the given revision and check whether the page is another one.
			# In that case, update this instance to reflect the change.
			$this->mRevision = Revision::newFromId( $oldid );
			if ( $this->mRevision !== null ) {
				// Revision title doesn't match the page title given?
				if ( $this->mPage->getID() != $this->mRevision->getPage() ) {
					$function = array( get_class( $this->mPage ), 'newFromID' );
					$this->mPage = call_user_func( $function, $this->mRevision->getPage() );
				}
			}
		}

		if ( $wgRequest->getVal( 'direction' ) == 'next' ) {
			$nextid = $this->getTitle()->getNextRevisionID( $oldid );
			if ( $nextid ) {
				$oldid = $nextid;
			} else {
				$this->mRedirectUrl = $this->getTitle()->getFullURL( 'redirect=no' );
			}
		} elseif ( $wgRequest->getVal( 'direction' ) == 'prev' ) {
			$previd = $this->getTitle()->getPreviousRevisionID( $oldid );
			if ( $previd ) {
				$oldid = $previd;
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
		$t = $this->getTitle()->getPrefixedText();
		$d = $oldid ? wfMsgExt( 'missingarticle-rev', array( 'escape' ), $oldid ) : '';
		$this->mContent = wfMsgNoTrans( 'missing-article', $t, $d ) ;

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
		wfDeprecated( __METHOD__ );
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
		global $wgUser, $wgOut, $wgRequest, $wgParser;
		global $wgUseFileCache, $wgUseETag, $wgDebugToolbar;

		wfProfileIn( __METHOD__ );

		# Get variables from query string
		# As side effect this will load the revision and update the title
		# in a revision ID is passed in the request, so this should remain
		# the first call of this method even if $oldid is used way below.
		$oldid = $this->getOldID();

		# Another whitelist check in case getOldID() is altering the title
		$permErrors = $this->getTitle()->getUserPermissionsErrors( 'read', $wgUser );
		if ( count( $permErrors ) ) {
			wfDebug( __METHOD__ . ": denied on secondary read check\n" );
			wfProfileOut( __METHOD__ );
			throw new PermissionsError( 'read', $permErrors );
		}

		# getOldID() may as well want us to redirect somewhere else
		if ( $this->mRedirectUrl ) {
			$wgOut->redirect( $this->mRedirectUrl );
			wfDebug( __METHOD__ . ": redirecting due to oldid\n" );
			wfProfileOut( __METHOD__ );

			return;
		}

		# If we got diff in the query, we want to see a diff page instead of the article.
		if ( $wgRequest->getCheck( 'diff' ) ) {
			wfDebug( __METHOD__ . ": showing diff page\n" );
			$this->showDiffPage();
			wfProfileOut( __METHOD__ );

			return;
		}

		# Set page title (may be overridden by DISPLAYTITLE)
		$wgOut->setPageTitle( $this->getTitle()->getPrefixedText() );

		$wgOut->setArticleFlag( true );
		# Allow frames by default
		$wgOut->allowClickjacking();

		$parserCache = ParserCache::singleton();

		$parserOptions = $this->getParserOptions();
		# Render printable version, use printable version cache
		if ( $wgOut->isPrintable() ) {
			$parserOptions->setIsPrintable( true );
			$parserOptions->setEditSection( false );
		} elseif ( !$this->getTitle()->quickUserCan( 'edit' ) ) {
			$parserOptions->setEditSection( false );
		}

		# Try client and file cache
		if ( !$wgDebugToolbar && $oldid === 0 && $this->mPage->checkTouched() ) {
			if ( $wgUseETag ) {
				$wgOut->setETag( $parserCache->getETag( $this, $parserOptions ) );
			}

			# Is it client cached?
			if ( $wgOut->checkLastModified( $this->mPage->getTouched() ) ) {
				wfDebug( __METHOD__ . ": done 304\n" );
				wfProfileOut( __METHOD__ );

				return;
			# Try file cache
			} elseif ( $wgUseFileCache && $this->tryFileCache() ) {
				wfDebug( __METHOD__ . ": done file cache\n" );
				# tell wgOut that output is taken care of
				$wgOut->disable();
				$this->mPage->viewUpdates();
				wfProfileOut( __METHOD__ );

				return;
			}
		}

		# Should the parser cache be used?
		$useParserCache = $this->mPage->isParserCacheUsed( $parserOptions, $oldid );
		wfDebug( 'Article::view using parser cache: ' . ( $useParserCache ? 'yes' : 'no' ) . "\n" );
		if ( $wgUser->getStubThreshold() ) {
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
							$wgOut->addParserOutput( $this->mParserOutput );
							# Ensure that UI elements requiring revision ID have
							# the correct version information.
							$wgOut->setRevisionId( $this->mPage->getLatest() );
							# Preload timestamp to avoid a DB hit
							$wgOut->setRevisionTimestamp( $this->mParserOutput->getTimestamp() );
							$this->mPage->setTimestamp( $this->mParserOutput->getTimestamp() );
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
					$wgOut->setRevisionId( $this->getRevIdFetched() );
					# Preload timestamp to avoid a DB hit
					$wgOut->setRevisionTimestamp( $this->getTimestamp() );

					# Pages containing custom CSS or JavaScript get special treatment
					if ( $this->getTitle()->isCssOrJsPage() || $this->getTitle()->isCssJsSubpage() ) {
						wfDebug( __METHOD__ . ": showing CSS/JS source\n" );
						$this->showCssOrJsPage();
						$outputDone = true;
					} elseif( !wfRunHooks( 'ArticleViewCustom', array( $this->mContent, $this->getTitle(), $wgOut ) ) ) {
						# Allow extensions do their own custom view for certain pages
						$outputDone = true;
					} else {
						$text = $this->getContent();
						$rt = Title::newFromRedirectArray( $text );
						if ( $rt ) {
							wfDebug( __METHOD__ . ": showing redirect=no page\n" );
							# Viewing a redirect page (e.g. with parameter redirect=no)
							$wgOut->addHTML( $this->viewRedirect( $rt ) );
							# Parse just to get categories, displaytitle, etc.
							$this->mParserOutput = $wgParser->parse( $text, $this->getTitle(), $parserOptions );
							$wgOut->addParserOutputNoText( $this->mParserOutput );
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
							$wgOut->clearHTML(); // for release() errors
							$wgOut->enableClientCache( false );
							$wgOut->setRobotPolicy( 'noindex,nofollow' );

							$errortext = $error->getWikiText( false, 'view-pool-error' );
							$wgOut->addWikiText( '<div class="errorbox">' . $errortext . '</div>' );
						}
						# Connection or timeout error
						wfProfileOut( __METHOD__ );
						return;
					}

					$this->mParserOutput = $poolArticleView->getParserOutput();
					$wgOut->addParserOutput( $this->mParserOutput );

					# Don't cache a dirty ParserOutput object
					if ( $poolArticleView->getIsDirty() ) {
						$wgOut->setSquidMaxage( 0 );
						$wgOut->addHTML( "<!-- parser cache is expired, sending anyway due to pool overload-->\n" );
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
				$wgOut->setHTMLTitle( $msg->title( $this->getTitle() )->text() );
			}
		}

		# Check for any __NOINDEX__ tags on the page using $pOutput
		$policy = $this->getRobotPolicy( 'view', $pOutput );
		$wgOut->setIndexPolicy( $policy['index'] );
		$wgOut->setFollowPolicy( $policy['follow'] );

		$this->showViewFooter();
		$this->mPage->viewUpdates();

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Adjust title for pages with displaytitle, -{T|}- or language conversion
	 * @param $pOutput ParserOutput
	 */
	public function adjustDisplayTitle( ParserOutput $pOutput ) {
		global $wgOut;
		# Adjust the title if it was set by displaytitle, -{T|}- or language conversion
		$titleText = $pOutput->getTitleText();
		if ( strval( $titleText ) !== '' ) {
			$wgOut->setPageTitle( $titleText );
		}
	}

	/**
	 * Show a diff page according to current request variables. For use within
	 * Article::view() only, other callers should use the DifferenceEngine class.
	 */
	public function showDiffPage() {
		global $wgRequest, $wgUser;

		$diff = $wgRequest->getVal( 'diff' );
		$rcid = $wgRequest->getVal( 'rcid' );
		$diffOnly = $wgRequest->getBool( 'diffonly', $wgUser->getOption( 'diffonly' ) );
		$purge = $wgRequest->getVal( 'action' ) == 'purge';
		$unhide = $wgRequest->getInt( 'unhide' ) == 1;
		$oldid = $this->getOldID();

		$de = new DifferenceEngine( $this->getContext(), $oldid, $diff, $rcid, $purge, $unhide );
		// DifferenceEngine directly fetched the revision:
		$this->mRevIdFetched = $de->mNewid;
		$de->showDiffPage( $diffOnly );

		if ( $diff == 0 || $diff == $this->mPage->getLatest() ) {
			# Run view updates for current revision only
			$this->mPage->viewUpdates();
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
		global $wgOut;

		$dir = $this->getContext()->getLanguage()->getDir();
		$lang = $this->getContext()->getLanguage()->getCode();

		$wgOut->wrapWikiMsg( "<div id='mw-clearyourcache' lang='$lang' dir='$dir' class='mw-content-$dir'>\n$1\n</div>",
			'clearyourcache' );

		// Give hooks a chance to customise the output
		if ( wfRunHooks( 'ShowRawCssJs', array( $this->mContent, $this->getTitle(), $wgOut ) ) ) {
			// Wrap the whole lot in a <pre> and don't parse
			$m = array();
			preg_match( '!\.(css|js)$!u', $this->getTitle()->getText(), $m );
			$wgOut->addHTML( "<pre class=\"mw-code mw-{$m[1]}\" dir=\"ltr\">\n" );
			$wgOut->addHTML( htmlspecialchars( $this->mContent ) );
			$wgOut->addHTML( "\n</pre>\n" );
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
		global $wgOut, $wgArticleRobotPolicies, $wgNamespaceRobotPolicies;
		global $wgDefaultRobotPolicy, $wgRequest;

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
		} elseif ( $wgOut->isPrintable() ) {
			# Discourage indexing of printable versions, but encourage following
			return array(
				'index'  => 'noindex',
				'follow' => 'follow'
			);
		} elseif ( $wgRequest->getInt( 'curid' ) ) {
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
	 * @return Array: 'index' => <indexpolicy>, 'follow' => <followpolicy>
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
	 * $wgOut. Returns true if the header was needed, false if this is not a
	 * redirect view. Handles both local and remote redirects.
	 *
	 * @return boolean
	 */
	public function showRedirectedFromHeader() {
		global $wgOut, $wgRequest, $wgRedirectSources;

		$rdfrom = $wgRequest->getVal( 'rdfrom' );

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

				$wgOut->addSubtitle( wfMessage( 'redirectedfrom' )->rawParams( $redir ) );

				// Set the fragment if one was specified in the redirect
				if ( strval( $this->getTitle()->getFragment() ) != '' ) {
					$fragment = Xml::escapeJsString( $this->getTitle()->getFragmentForURL() );
					$wgOut->addInlineScript( "redirectToFragment(\"$fragment\");" );
				}

				// Add a <link rel="canonical"> tag
				$wgOut->addLink( array( 'rel' => 'canonical',
					'href' => $this->getTitle()->getLocalURL() )
				);

				// Tell $wgOut the user arrived at this article through a redirect
				$wgOut->setRedirectedFrom( $this->mRedirectedFrom );

				return true;
			}
		} elseif ( $rdfrom ) {
			// This is an externally redirected view, from some other wiki.
			// If it was reported from a trusted site, supply a backlink.
			if ( $wgRedirectSources && preg_match( $wgRedirectSources, $rdfrom ) ) {
				$redir = Linker::makeExternalLink( $rdfrom, $rdfrom );
				$wgOut->addSubtitle( wfMessage( 'redirectedfrom' )->rawParams( $redir ) );

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
		global $wgOut;

		if ( $this->getTitle()->isTalkPage() ) {
			if ( !wfMessage( 'talkpageheader' )->isDisabled() ) {
				$wgOut->wrapWikiMsg( "<div class=\"mw-talkpageheader\">\n$1\n</div>", array( 'talkpageheader' ) );
			}
		}
	}

	/**
	 * Show the footer section of an ordinary page view
	 */
	public function showViewFooter() {
		global $wgOut;

		# check if we're displaying a [[User talk:x.x.x.x]] anonymous talk page
		if ( $this->getTitle()->getNamespace() == NS_USER_TALK && IP::isValid( $this->getTitle()->getText() ) ) {
			$wgOut->addWikiMsg( 'anontalkpagetext' );
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
		global $wgOut, $wgRequest, $wgUser;

		$rcid = $wgRequest->getVal( 'rcid' );

		if ( !$rcid || !$this->getTitle()->quickUserCan( 'patrol' ) ) {
			return;
		}

		$token = $wgUser->getEditToken( $rcid );
		$wgOut->preventClickjacking();

		$wgOut->addHTML(
			"<div class='patrollink'>" .
				wfMsgHtml(
					'markaspatrolledlink',
					Linker::link(
						$this->getTitle(),
						wfMsgHtml( 'markaspatrolledtext' ),
						array(),
						array(
							'action' => 'markpatrolled',
							'rcid' => $rcid,
							'token' => $token,
						),
						array( 'known', 'noclasses' )
					)
				) .
			'</div>'
		);
	}

	/**
	 * Show the error text for a missing article. For articles in the MediaWiki
	 * namespace, show the default message text. To be called from Article::view().
	 */
	public function showMissingArticle() {
		global $wgOut, $wgRequest, $wgUser, $wgSend404Code;

		# Show info in user (talk) namespace. Does the user exist? Is he blocked?
		if ( $this->getTitle()->getNamespace() == NS_USER || $this->getTitle()->getNamespace() == NS_USER_TALK ) {
			$parts = explode( '/', $this->getTitle()->getText() );
			$rootPart = $parts[0];
			$user = User::newFromName( $rootPart, false /* allow IP users*/ );
			$ip = User::isIP( $rootPart );

			if ( !($user && $user->isLoggedIn()) && !$ip ) { # User does not exist
				$wgOut->wrapWikiMsg( "<div class=\"mw-userpage-userdoesnotexist error\">\n\$1\n</div>",
					array( 'userpage-userdoesnotexist-view', wfEscapeWikiText( $rootPart ) ) );
			} elseif ( $user->isBlocked() ) { # Show log extract if the user is currently blocked
				LogEventsList::showLogExtract(
					$wgOut,
					'block',
					$user->getUserPage()->getPrefixedText(),
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
		LogEventsList::showLogExtract( $wgOut, array( 'delete', 'move' ), $this->getTitle()->getPrefixedText(), '',
			array(  'lim' => 10,
				'conds' => array( "log_action != 'revision'" ),
				'showIfEmpty' => false,
				'msgKey' => array( 'moveddeleted-notice' ) )
		);

		# Show error message
		$oldid = $this->getOldID();
		if ( $oldid ) {
			$text = wfMsgNoTrans( 'missing-article',
				$this->getTitle()->getPrefixedText(),
				wfMsgNoTrans( 'missingarticle-rev', $oldid ) );
		} elseif ( $this->getTitle()->getNamespace() === NS_MEDIAWIKI ) {
			// Use the default message text
			$text = $this->getTitle()->getDefaultMessageText();
		} else {
			$createErrors = $this->getTitle()->getUserPermissionsErrors( 'create', $wgUser );
			$editErrors = $this->getTitle()->getUserPermissionsErrors( 'edit', $wgUser );
			$errors = array_merge( $createErrors, $editErrors );

			if ( !count( $errors ) ) {
				$text = wfMsgNoTrans( 'noarticletext' );
			} else {
				$text = wfMsgNoTrans( 'noarticletext-nopermission' );
			}
		}
		$text = "<div class='noarticletext'>\n$text\n</div>";

		if ( !$this->mPage->hasViewableContent() && $wgSend404Code ) {
			// If there's no backing content, send a 404 Not Found
			// for better machine handling of broken links.
			$wgRequest->response()->header( "HTTP/1.1 404 Not Found" );
		}

		$wgOut->addWikiText( $text );
	}

	/**
	 * If the revision requested for view is deleted, check permissions.
	 * Send either an error message or a warning header to $wgOut.
	 *
	 * @return boolean true if the view is allowed, false if not.
	 */
	public function showDeletedRevisionHeader() {
		global $wgOut, $wgRequest;

		if ( !$this->mRevision->isDeleted( Revision::DELETED_TEXT ) ) {
			// Not deleted
			return true;
		}

		// If the user is not allowed to see it...
		if ( !$this->mRevision->userCan( Revision::DELETED_TEXT ) ) {
			$wgOut->wrapWikiMsg( "<div class='mw-warning plainlinks'>\n$1\n</div>\n",
				'rev-deleted-text-permission' );

			return false;
		// If the user needs to confirm that they want to see it...
		} elseif ( $wgRequest->getInt( 'unhide' ) != 1 ) {
			# Give explanation and add a link to view the revision...
			$oldid = intval( $this->getOldID() );
			$link = $this->getTitle()->getFullUrl( "oldid={$oldid}&unhide=1" );
			$msg = $this->mRevision->isDeleted( Revision::DELETED_RESTRICTED ) ?
				'rev-suppressed-text-unhide' : 'rev-deleted-text-unhide';
			$wgOut->wrapWikiMsg( "<div class='mw-warning plainlinks'>\n$1\n</div>\n",
				array( $msg, $link ) );

			return false;
		// We are allowed to see...
		} else {
			$msg = $this->mRevision->isDeleted( Revision::DELETED_RESTRICTED ) ?
				'rev-suppressed-text-view' : 'rev-deleted-text-view';
			$wgOut->wrapWikiMsg( "<div class='mw-warning plainlinks'>\n$1\n</div>\n", $msg );

			return true;
		}
	}

	/**
	 * Generate the navigation links when browsing through an article revisions
	 * It shows the information as:
	 *   Revision as of \<date\>; view current revision
	 *   \<- Previous version | Next Version -\>
	 *
	 * @param $oldid String: revision ID of this article revision
	 */
	public function setOldSubtitle( $oldid = 0 ) {
		global $wgLang, $wgOut, $wgUser, $wgRequest;

		if ( !wfRunHooks( 'DisplayOldSubtitle', array( &$this, &$oldid ) ) ) {
			return;
		}

		$unhide = $wgRequest->getInt( 'unhide' ) == 1;

		# Cascade unhide param in links for easy deletion browsing
		$extraParams = array();
		if ( $wgRequest->getVal( 'unhide' ) ) {
			$extraParams['unhide'] = 1;
		}

		$revision = Revision::newFromId( $oldid );
		$timestamp = $revision->getTimestamp();

		$current = ( $oldid == $this->mPage->getLatest() );
		$td = $wgLang->timeanddate( $timestamp, true );
		$tddate = $wgLang->date( $timestamp, true );
		$tdtime = $wgLang->time( $timestamp, true );

		# Show user links if allowed to see them. If hidden, then show them only if requested...
		$userlinks = Linker::revUserTools( $revision, !$unhide );

		$infomsg = $current && !wfMessage( 'revision-info-current' )->isDisabled()
			? 'revision-info-current'
			: 'revision-info';

		$wgOut->addSubtitle( "<div id=\"mw-{$infomsg}\">" . wfMessage( $infomsg,
			$td )->rawParams( $userlinks )->params( $revision->getID(), $tddate,
			$tdtime, $revision->getUser() )->parse() . "</div>" );

		$lnk = $current
			? wfMsgHtml( 'currentrevisionlink' )
			: Linker::link(
				$this->getTitle(),
				wfMsgHtml( 'currentrevisionlink' ),
				array(),
				$extraParams,
				array( 'known', 'noclasses' )
			);
		$curdiff = $current
			? wfMsgHtml( 'diff' )
			: Linker::link(
				$this->getTitle(),
				wfMsgHtml( 'diff' ),
				array(),
				array(
					'diff' => 'cur',
					'oldid' => $oldid
				) + $extraParams,
				array( 'known', 'noclasses' )
			);
		$prev = $this->getTitle()->getPreviousRevisionID( $oldid ) ;
		$prevlink = $prev
			? Linker::link(
				$this->getTitle(),
				wfMsgHtml( 'previousrevision' ),
				array(),
				array(
					'direction' => 'prev',
					'oldid' => $oldid
				) + $extraParams,
				array( 'known', 'noclasses' )
			)
			: wfMsgHtml( 'previousrevision' );
		$prevdiff = $prev
			? Linker::link(
				$this->getTitle(),
				wfMsgHtml( 'diff' ),
				array(),
				array(
					'diff' => 'prev',
					'oldid' => $oldid
				) + $extraParams,
				array( 'known', 'noclasses' )
			)
			: wfMsgHtml( 'diff' );
		$nextlink = $current
			? wfMsgHtml( 'nextrevision' )
			: Linker::link(
				$this->getTitle(),
				wfMsgHtml( 'nextrevision' ),
				array(),
				array(
					'direction' => 'next',
					'oldid' => $oldid
				) + $extraParams,
				array( 'known', 'noclasses' )
			);
		$nextdiff = $current
			? wfMsgHtml( 'diff' )
			: Linker::link(
				$this->getTitle(),
				wfMsgHtml( 'diff' ),
				array(),
				array(
					'diff' => 'next',
					'oldid' => $oldid
				) + $extraParams,
				array( 'known', 'noclasses' )
			);

		$cdel = '';

		// User can delete revisions or view deleted revisions...
		$canHide = $wgUser->isAllowed( 'deleterevision' );
		if ( $canHide || ( $revision->getVisibility() && $wgUser->isAllowed( 'deletedhistory' ) ) ) {
			if ( !$revision->userCan( Revision::DELETED_RESTRICTED ) ) {
				$cdel = Linker::revDeleteLinkDisabled( $canHide ); // rev was hidden from Sysops
			} else {
				$query = array(
					'type'   => 'revision',
					'target' => $this->getTitle()->getPrefixedDbkey(),
					'ids'    => $oldid
				);
				$cdel = Linker::revDeleteLink( $query, $revision->isDeleted( File::DELETED_RESTRICTED ), $canHide );
			}
			$cdel .= ' ';
		}

		$wgOut->addSubtitle( "<div id=\"mw-revision-nav\">" . $cdel .
			wfMsgExt( 'revision-nav', array( 'escapenoentities', 'parsemag', 'replaceafter' ),
			$prevdiff, $prevlink, $lnk, $curdiff, $nextlink, $nextdiff ) . "</div>" );
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
		global $wgOut, $wgStylePath;

		if ( !is_array( $target ) ) {
			$target = array( $target );
		}

		$lang = $this->getTitle()->getPageLanguage();
		$imageDir = $lang->getDir();

		if ( $appendSubtitle ) {
			$wgOut->appendSubtitle( wfMsgHtml( 'redirectpagesub' ) );
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
		global $wgOut;

		$wgOut->setArticleBodyOnly( true );
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
		global $wgOut, $wgRequest, $wgLang;

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
		$dbw = wfGetDB( DB_MASTER );
		$conds = $title->pageCond();
		$latest = $dbw->selectField( 'page', 'page_latest', $conds, __METHOD__ );
		if ( $latest === false ) {
			$wgOut->setPageTitle( wfMessage( 'cannotdelete-title', $title->getPrefixedText() ) );
			$wgOut->wrapWikiMsg( "<div class=\"error mw-error-cannotdelete\">\n$1\n</div>",
					array( 'cannotdelete', wfEscapeWikiText( $title->getPrefixedText() ) )
				);
			$wgOut->addHTML( Xml::element( 'h2', null, LogPage::logName( 'delete' ) ) );
			LogEventsList::showLogExtract(
				$wgOut,
				'delete',
				$title->getPrefixedText()
			);

			return;
		}

		# Hack for big sites
		$bigHistory = $this->mPage->isBigDeletion();
		if ( $bigHistory && !$title->userCan( 'bigdelete' ) ) {
			global $wgDeleteRevisionsLimit;

			$wgOut->setPageTitle( wfMessage( 'cannotdelete-title', $title->getPrefixedText() ) );
			$wgOut->wrapWikiMsg( "<div class='error'>\n$1\n</div>\n",
				array( 'delete-toobig', $wgLang->formatNum( $wgDeleteRevisionsLimit ) ) );

			return;
		}

		$deleteReasonList = $wgRequest->getText( 'wpDeleteReasonList', 'other' );
		$deleteReason = $wgRequest->getText( 'wpReason' );

		if ( $deleteReasonList == 'other' ) {
			$reason = $deleteReason;
		} elseif ( $deleteReason != '' ) {
			// Entry from drop down menu + additional comment
			$reason = $deleteReasonList . wfMsgForContent( 'colon-separator' ) . $deleteReason;
		} else {
			$reason = $deleteReasonList;
		}

		if ( $wgRequest->wasPosted() && $user->matchEditToken( $wgRequest->getVal( 'wpEditToken' ),
			array( 'delete', $this->getTitle()->getPrefixedText() ) ) )
		{
			# Flag to hide all contents of the archived revisions
			$suppress = $wgRequest->getVal( 'wpSuppress' ) && $user->isAllowed( 'suppressrevision' );

			$this->doDelete( $reason, $suppress );

			if ( $wgRequest->getCheck( 'wpWatch' ) && $user->isLoggedIn() ) {
				$this->doWatch();
			} elseif ( $title->userIsWatching() ) {
				$this->doUnwatch();
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
			$revisions = $this->mPage->estimateRevisionCount();
			// @todo FIXME: i18n issue/patchwork message
			$wgOut->addHTML( '<strong class="mw-delete-warning-revisions">' .
				wfMsgExt( 'historywarning', array( 'parseinline' ), $wgLang->formatNum( $revisions ) ) .
				wfMsgHtml( 'word-separator' ) . Linker::link( $title,
					wfMsgHtml( 'history' ),
					array( 'rel' => 'archives' ),
					array( 'action' => 'history' ) ) .
				'</strong>'
			);

			if ( $bigHistory ) {
				global $wgDeleteRevisionsLimit;
				$wgOut->wrapWikiMsg( "<div class='error'>\n$1\n</div>\n",
					array( 'delete-warning-toobig', $wgLang->formatNum( $wgDeleteRevisionsLimit ) ) );
			}
		}

		return $this->confirmDelete( $reason );
	}

	/**
	 * Output deletion confirmation dialog
	 * @todo FIXME: Move to another file?
	 * @param $reason String: prefilled reason
	 */
	public function confirmDelete( $reason ) {
		global $wgOut;

		wfDebug( "Article::confirmDelete\n" );

		$wgOut->setPageTitle( wfMessage( 'delete-confirm', $this->getTitle()->getPrefixedText() ) );
		$wgOut->addBacklinkSubtitle( $this->getTitle() );
		$wgOut->setRobotPolicy( 'noindex,nofollow' );
		$wgOut->addWikiMsg( 'confirmdeletetext' );

		wfRunHooks( 'ArticleConfirmDelete', array( $this, $wgOut, &$reason ) );

		$user = $this->getContext()->getUser();

		if ( $user->isAllowed( 'suppressrevision' ) ) {
			$suppress = "<tr id=\"wpDeleteSuppressRow\">
					<td></td>
					<td class='mw-input'><strong>" .
						Xml::checkLabel( wfMsg( 'revdelete-suppress' ),
							'wpSuppress', 'wpSuppress', false, array( 'tabindex' => '4' ) ) .
					"</strong></td>
				</tr>";
		} else {
			$suppress = '';
		}
		$checkWatch = $user->getBoolOption( 'watchdeletion' ) || $this->getTitle()->userIsWatching();

		$form = Xml::openElement( 'form', array( 'method' => 'post',
			'action' => $this->getTitle()->getLocalURL( 'action=delete' ), 'id' => 'deleteconfirm' ) ) .
			Xml::openElement( 'fieldset', array( 'id' => 'mw-delete-table' ) ) .
			Xml::tags( 'legend', null, wfMsgExt( 'delete-legend', array( 'parsemag', 'escapenoentities' ) ) ) .
			Xml::openElement( 'table', array( 'id' => 'mw-deleteconfirm-table' ) ) .
			"<tr id=\"wpDeleteReasonListRow\">
				<td class='mw-label'>" .
					Xml::label( wfMsg( 'deletecomment' ), 'wpDeleteReasonList' ) .
				"</td>
				<td class='mw-input'>" .
					Xml::listDropDown( 'wpDeleteReasonList',
						wfMsgForContent( 'deletereason-dropdown' ),
						wfMsgForContent( 'deletereasonotherlist' ), '', 'wpReasonDropDown', 1 ) .
				"</td>
			</tr>
			<tr id=\"wpDeleteReasonRow\">
				<td class='mw-label'>" .
					Xml::label( wfMsg( 'deleteotherreason' ), 'wpReason' ) .
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
					Xml::checkLabel( wfMsg( 'watchthis' ),
						'wpWatch', 'wpWatch', $checkWatch, array( 'tabindex' => '3' ) ) .
				"</td>
			</tr>";
		}

		$form .= "
			$suppress
			<tr>
				<td></td>
				<td class='mw-submit'>" .
					Xml::submitButton( wfMsg( 'deletepage' ),
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
					wfMsgHtml( 'delete-edit-reasonlist' ),
					array(),
					array( 'action' => 'edit' )
				);
				$form .= '<p class="mw-delete-editreasons">' . $link . '</p>';
			}

		$wgOut->addHTML( $form );
		$wgOut->addHTML( Xml::element( 'h2', null, LogPage::logName( 'delete' ) ) );
		LogEventsList::showLogExtract( $wgOut, 'delete',
			$this->getTitle()->getPrefixedText()
		);
	}

	/**
	 * Perform a deletion and output success or failure messages
	 * @param $reason
	 * @param $suppress bool
	 */
	public function doDelete( $reason, $suppress = false ) {
		global $wgOut;

		$id = $this->getTitle()->getArticleID( Title::GAID_FOR_UPDATE );

		$error = '';
		if ( $this->mPage->doDeleteArticle( $reason, $suppress, $id, true, $error ) ) {
			$deleted = $this->getTitle()->getPrefixedText();

			$wgOut->setPageTitle( wfMessage( 'actioncomplete' ) );
			$wgOut->setRobotPolicy( 'noindex,nofollow' );

			$loglink = '[[Special:Log/delete|' . wfMsgNoTrans( 'deletionlog' ) . ']]';

			$wgOut->addWikiMsg( 'deletedtext', wfEscapeWikiText( $deleted ), $loglink );
			$wgOut->returnToMain( false );
		} else {
			$wgOut->setPageTitle( wfMessage( 'cannotdelete-title', $this->getTitle()->getPrefixedText() ) );
			if ( $error == '' ) {
				$wgOut->wrapWikiMsg( "<div class=\"error mw-error-cannotdelete\">\n$1\n</div>",
					array( 'cannotdelete', wfEscapeWikiText( $this->getTitle()->getPrefixedText() ) )
				);
				$wgOut->addHTML( Xml::element( 'h2', null, LogPage::logName( 'delete' ) ) );

				LogEventsList::showLogExtract(
					$wgOut,
					'delete',
					$this->getTitle()->getPrefixedText()
				);
			} else {
				$wgOut->addHTML( $error );
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
		global $wgUser;

		$user = is_null( $user ) ? $wgUser : $user;
		$parserOptions = $this->mPage->makeParserOptions( $user );

		return $this->mPage->getParserOutput( $parserOptions, $oldid );
	}

	/**
	 * Get parser options suitable for rendering the primary article wikitext
	 * @return ParserOptions|false
	 */
	public function getParserOptions() {
		global $wgUser;
		if ( !$this->mParserOptions ) {
			$this->mParserOptions = $this->mPage->makeParserOptions( $wgUser );
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
		Action::factory( 'info', $this )->show();
	}

	/**
	 * Mark this particular edit/page as patrolled
	 * @deprecated since 1.18
	 */
	public function markpatrolled() {
		Action::factory( 'markpatrolled', $this )->show();
	}

	/**
	 * Handle action=purge
	 * @deprecated since 1.19
	 */
	public function purge() {
		return Action::factory( 'purge', $this )->show();
	}

	/**
	 * Handle action=revert
	 * @deprecated since 1.19
	 */
	public function revert() {
		Action::factory( 'revert', $this )->show();
	}

	/**
	 * Handle action=rollback
	 * @deprecated since 1.19
	 */
	public function rollback() {
		Action::factory( 'rollback', $this )->show();
	}

	/**
	 * User-interface handler for the "watch" action.
	 * Requires Request to pass a token as of 1.18.
	 * @deprecated since 1.18
	 */
	public function watch() {
		Action::factory( 'watch', $this )->show();
	}

	/**
	 * Add this page to $wgUser's watchlist
	 *
	 * This is safe to be called multiple times
	 *
	 * @return bool true on successful watch operation
	 * @deprecated since 1.18
	 */
	public function doWatch() {
		global $wgUser;
		return WatchAction::doWatch( $this->getTitle(), $wgUser );
	}

	/**
	 * User interface handler for the "unwatch" action.
	 * Requires Request to pass a token as of 1.18.
	 * @deprecated since 1.18
	 */
	public function unwatch() {
		Action::factory( 'unwatch', $this )->show();
	}

	/**
	 * Stop watching a page
	 * @return bool true on successful unwatch
	 * @deprecated since 1.18
	 */
	public function doUnwatch() {
		global $wgUser;
		return WatchAction::doUnwatch( $this->getTitle(), $wgUser );
	}

	/**
	 * Output a redirect back to the article.
	 * This is typically used after an edit.
	 *
	 * @deprecated in 1.18; call $wgOut->redirect() directly
	 * @param $noRedir Boolean: add redirect=no
	 * @param $sectionAnchor String: section to redirect to, including "#"
	 * @param $extraQuery String: extra query params
	 */
	public function doRedirect( $noRedir = false, $sectionAnchor = '', $extraQuery = '' ) {
		wfDeprecated( __METHOD__ );
		global $wgOut;

		if ( $noRedir ) {
			$query = 'redirect=no';
			if ( $extraQuery )
				$query .= "&$extraQuery";
		} else {
			$query = $extraQuery;
		}

		$wgOut->redirect( $this->getTitle()->getFullURL( $query ) . $sectionAnchor );
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
		global $wgUser;
		$user = is_null( $user ) ? $wgUser : $user;
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
		global $wgUser;
		$guser = is_null( $guser ) ? $wgUser : $guser;
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
