<?php
/**
 * File for articles
 * @package MediaWiki
 */

/**
 * Need the CacheManager to be loaded
 */
require_once( 'CacheManager.php' );

/**
 * Class representing a MediaWiki article and history.
 *
 * See design.txt for an overview.
 * Note: edit user interface and cache support functions have been
 * moved to separate EditPage and CacheManager classes.
 *
 * @package MediaWiki
 */
class Article {
	/**@{{
	 * @private
	 */
	var $mComment;			//!<
	var $mContent;			//!<
	var $mContentLoaded;	//!<
	var $mCounter;			//!<
	var $mForUpdate;		//!<
	var $mGoodAdjustment;	//!<
	var $mLatest;			//!<
	var $mMinorEdit;		//!<
	var $mOldId;			//!<
	var $mRedirectedFrom;	//!<
	var $mRedirectUrl;		//!<
	var $mRevIdFetched;		//!<
	var $mRevision;			//!<
	var $mTimestamp;		//!<
	var $mTitle;			//!<
	var $mTotalAdjustment;	//!<
	var $mTouched;			//!<
	var $mUser;				//!<
	var $mUserText;			//!<
	/**@}}*/

	/**
	 * Constructor and clear the article
	 * @param $title Reference to a Title object.
	 * @param $oldId Integer revision ID, null to fetch from request, zero for current
	 */
	function Article( &$title, $oldId = null ) {
		$this->mTitle =& $title;
		$this->mOldId = $oldId;
		$this->clear();
	}
	
	/**
	 * Tell the page view functions that this view was redirected
	 * from another page on the wiki.
	 * @param $from Title object.
	 */
	function setRedirectedFrom( $from ) {
		$this->mRedirectedFrom = $from;
	}
	
	/**
	 * @return mixed false, Title of in-wiki target, or string with URL
	 */
	function followRedirect() {
		$text = $this->getContent();
		$rt = Title::newFromRedirect( $text );
		
		# process if title object is valid and not special:userlogout
		if( $rt ) {
			if( $rt->getInterwiki() != '' ) {
				if( $rt->isLocal() ) {
					// Offsite wikis need an HTTP redirect.
					//
					// This can be hard to reverse and may produce loops,
					// so they may be disabled in the site configuration.
					
					$source = $this->mTitle->getFullURL( 'redirect=no' );
					return $rt->getFullURL( 'rdfrom=' . urlencode( $source ) );
				}
			} else {
				if( $rt->getNamespace() == NS_SPECIAL ) {
					// Gotta hand redirects to special pages differently:
					// Fill the HTTP response "Location" header and ignore
					// the rest of the page we're on.
					//
					// This can be hard to reverse, so they may be disabled.
					
					if( $rt->getNamespace() == NS_SPECIAL && $rt->getText() == 'Userlogout' ) {
						// rolleyes
					} else {
						return $rt->getFullURL();
					}
				}
				return $rt;
			}
		}
		
		// No or invalid redirect
		return false;
	}

	/**
	 * get the title object of the article
	 */
	function getTitle() {
		return $this->mTitle;
	}

	/**
	  * Clear the object
	  * @private
	  */
	function clear() {
		$this->mDataLoaded    = false;
		$this->mContentLoaded = false;

		$this->mCurID = $this->mUser = $this->mCounter = -1; # Not loaded
		$this->mRedirectedFrom = null; # Title object if set
		$this->mUserText =
		$this->mTimestamp = $this->mComment = '';
		$this->mGoodAdjustment = $this->mTotalAdjustment = 0;
		$this->mTouched = '19700101000000';
		$this->mForUpdate = false;
		$this->mIsRedirect = false;
		$this->mRevIdFetched = 0;
		$this->mRedirectUrl = false;
		$this->mLatest = false;
	}

	/**
	 * Note that getContent/loadContent do not follow redirects anymore.
	 * If you need to fetch redirectable content easily, try
	 * the shortcut in Article::followContent()
	 * FIXME
	 * @todo There are still side-effects in this!
	 *        In general, you should use the Revision class, not Article,
	 *        to fetch text for purposes other than page views.
	 *
	 * @return Return the text of this revision
	*/
	function getContent() {
		global $wgRequest, $wgUser, $wgOut;

		# Get variables from query string :P
		$action = $wgRequest->getText( 'action', 'view' );
		$section = $wgRequest->getText( 'section' );
		$preload = $wgRequest->getText( 'preload' );

		wfProfileIn( __METHOD__ );

		if ( 0 == $this->getID() ) {
			if ( 'edit' == $action ) {
				wfProfileOut( __METHOD__ );

				# If requested, preload some text.
				$text=$this->getPreloadedText($preload);

				# We used to put MediaWiki:Newarticletext here if
				# $text was empty at this point.
				# This is now shown above the edit box instead.
				return $text;
			}
			wfProfileOut( __METHOD__ );
			$wgOut->setRobotpolicy( 'noindex,nofollow' );

			if ( $this->mTitle->getNamespace() == NS_MEDIAWIKI ) {
				$ret = wfMsgWeirdKey ( $this->mTitle->getText() ) ;
			} else {
				$ret = wfMsg( $wgUser->isLoggedIn() ? 'noarticletext' : 'noarticletextanon' );
			}

			return "<div class='noarticletext'>$ret</div>";
		} else {
			$this->loadContent();
			if($action=='edit') {
				if($section!='') {
					if($section=='new') {
						wfProfileOut( __METHOD__ );
						$text=$this->getPreloadedText($preload);
						return $text;
					}

					# strip NOWIKI etc. to avoid confusion (true-parameter causes HTML
					# comments to be stripped as well)
					$rv=$this->getSection($this->mContent,$section);
					wfProfileOut( __METHOD__ );
					return $rv;
				}
			}
			wfProfileOut( __METHOD__ );
			return $this->mContent;
		}
	}

	/**
	 * Get the contents of a page from its title and remove includeonly tags
	 *
	 * @param $preload String: the title of the page.
	 * @return string The contents of the page.
	 */
	function getPreloadedText($preload) {
		if ( $preload === '' )
			return '';
		else {
			$preloadTitle = Title::newFromText( $preload );
			if ( isset( $preloadTitle ) && $preloadTitle->userCanRead() ) {
				$rev=Revision::newFromTitle($preloadTitle);
				if ( is_object( $rev ) ) {
					$text = $rev->getText();
					// TODO FIXME: AAAAAAAAAAA, this shouldn't be implementing
					// its own mini-parser! -ævar
					$text = preg_replace( '~</?includeonly>~', '', $text );
					return $text;
				} else
					return '';
			}
		}
	}

	/**
	 * This function returns the text of a section, specified by a number ($section).
	 * A section is text under a heading like == Heading == or \<h1\>Heading\</h1\>, or
	 * the first section before any such heading (section 0).
	 *
	 * If a section contains subsections, these are also returned.
	 *
	 * @param $text String: text to look in
	 * @param $section Integer: section number
	 * @return string text of the requested section
	 */
	function getSection($text,$section) {
		global $wgParser;
		return $wgParser->getSection( $text, $section );
	}

	/**
	 * @return int The oldid of the article that is to be shown, 0 for the
	 *             current revision
	 */
	function getOldID() {
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
	function getOldIDFromRequest() {
		global $wgRequest;
		$this->mRedirectUrl = false;
		$oldid = $wgRequest->getVal( 'oldid' );
		if ( isset( $oldid ) ) {
			$oldid = intval( $oldid );
			if ( $wgRequest->getVal( 'direction' ) == 'next' ) {
				$nextid = $this->mTitle->getNextRevisionID( $oldid );
				if ( $nextid  ) {
					$oldid = $nextid;
				} else {
					$this->mRedirectUrl = $this->mTitle->getFullURL( 'redirect=no' );
				}
			} elseif ( $wgRequest->getVal( 'direction' ) == 'prev' ) {
				$previd = $this->mTitle->getPreviousRevisionID( $oldid );
				if ( $previd ) {
					$oldid = $previd;
				} else {
					# TODO
				}
			}
			# unused:
			# $lastid = $oldid;
		}
		if ( !$oldid ) {
			$oldid = 0;
		}
		return $oldid;
	}

	/**
	 * Load the revision (including text) into this object
	 */
	function loadContent() {
		if ( $this->mContentLoaded ) return;

		# Query variables :P
		$oldid = $this->getOldID();

		# Pre-fill content with error message so that if something
		# fails we'll have something telling us what we intended.

		$t = $this->mTitle->getPrefixedText();

		$this->mOldId = $oldid;
		$this->fetchContent( $oldid );
	}


	/**
	 * Fetch a page record with the given conditions
	 * @param Database $dbr
	 * @param array    $conditions
	 * @private
	 */
	function pageData( &$dbr, $conditions ) {
		$fields = array(
				'page_id',
				'page_namespace',
				'page_title',
				'page_restrictions',
				'page_counter',
				'page_is_redirect',
				'page_is_new',
				'page_random',
				'page_touched',
				'page_latest',
				'page_len' ) ;
		wfRunHooks( 'ArticlePageDataBefore', array( &$this , &$fields ) )	;
		$row = $dbr->selectRow( 'page',
			$fields,
			$conditions,
			'Article::pageData' );
		wfRunHooks( 'ArticlePageDataAfter', array( &$this , &$row ) )	;
		return $row ;
	}

	/**
	 * @param Database $dbr
	 * @param Title $title
	 */
	function pageDataFromTitle( &$dbr, $title ) {
		return $this->pageData( $dbr, array(
			'page_namespace' => $title->getNamespace(),
			'page_title'     => $title->getDBkey() ) );
	}

	/**
	 * @param Database $dbr
	 * @param int $id
	 */
	function pageDataFromId( &$dbr, $id ) {
		return $this->pageData( $dbr, array( 'page_id' => $id ) );
	}

	/**
	 * Set the general counter, title etc data loaded from
	 * some source.
	 *
	 * @param object $data
	 * @private
	 */
	function loadPageData( $data = 'fromdb' ) {
		if ( $data === 'fromdb' ) {
			$dbr =& $this->getDB();
			$data = $this->pageDataFromId( $dbr, $this->getId() );
		}
			
		$lc =& LinkCache::singleton();
		if ( $data ) {
			$lc->addGoodLinkObj( $data->page_id, $this->mTitle );

			$this->mTitle->mArticleID = $data->page_id;
			$this->mTitle->loadRestrictions( $data->page_restrictions );
			$this->mTitle->mRestrictionsLoaded = true;

			$this->mCounter     = $data->page_counter;
			$this->mTouched     = wfTimestamp( TS_MW, $data->page_touched );
			$this->mIsRedirect  = $data->page_is_redirect;
			$this->mLatest      = $data->page_latest;
		} else {
			if ( is_object( $this->mTitle ) ) {
				$lc->addBadLinkObj( $this->mTitle );
			}
			$this->mTitle->mArticleID = 0;
		}

		$this->mDataLoaded  = true;
	}

	/**
	 * Get text of an article from database
	 * Does *NOT* follow redirects.
	 * @param int $oldid 0 for whatever the latest revision is
	 * @return string
	 */
	function fetchContent( $oldid = 0 ) {
		if ( $this->mContentLoaded ) {
			return $this->mContent;
		}

		$dbr =& $this->getDB();

		# Pre-fill content with error message so that if something
		# fails we'll have something telling us what we intended.
		$t = $this->mTitle->getPrefixedText();
		if( $oldid ) {
			$t .= ',oldid='.$oldid;
		}
		$this->mContent = wfMsg( 'missingarticle', $t ) ;

		if( $oldid ) {
			$revision = Revision::newFromId( $oldid );
			if( is_null( $revision ) ) {
				wfDebug( __METHOD__." failed to retrieve specified revision, id $oldid\n" );
				return false;
			}
			$data = $this->pageDataFromId( $dbr, $revision->getPage() );
			if( !$data ) {
				wfDebug( __METHOD__." failed to get page data linked to revision id $oldid\n" );
				return false;
			}
			$this->mTitle = Title::makeTitle( $data->page_namespace, $data->page_title );
			$this->loadPageData( $data );
		} else {
			if( !$this->mDataLoaded ) {
				$data = $this->pageDataFromTitle( $dbr, $this->mTitle );
				if( !$data ) {
					wfDebug( __METHOD__." failed to find page data for title " . $this->mTitle->getPrefixedText() . "\n" );
					return false;
				}
				$this->loadPageData( $data );
			}
			$revision = Revision::newFromId( $this->mLatest );
			if( is_null( $revision ) ) {
				wfDebug( __METHOD__." failed to retrieve current page, rev_id {$data->page_latest}\n" );
				return false;
			}
		}

		// FIXME: Horrible, horrible! This content-loading interface just plain sucks.
		// We should instead work with the Revision object when we need it...
		$this->mContent = $revision->userCan( Revision::MW_REV_DELETED_TEXT ) ? $revision->getRawText() : "";
		//$this->mContent   = $revision->getText();

		$this->mUser      = $revision->getUser();
		$this->mUserText  = $revision->getUserText();
		$this->mComment   = $revision->getComment();
		$this->mTimestamp = wfTimestamp( TS_MW, $revision->getTimestamp() );

		$this->mRevIdFetched = $revision->getID();
		$this->mContentLoaded = true;
		$this->mRevision =& $revision;

		wfRunHooks( 'ArticleAfterFetchContent', array( &$this, &$this->mContent ) ) ;

		return $this->mContent;
	}

	/**
	 * Read/write accessor to select FOR UPDATE
	 *
	 * @param $x Mixed: FIXME
	 */
	function forUpdate( $x = NULL ) {
		return wfSetVar( $this->mForUpdate, $x );
	}

	/**
	 * Get the database which should be used for reads
	 *
	 * @return Database
	 */
	function &getDB() {
		$ret =& wfGetDB( DB_MASTER );
		return $ret;
	}

	/**
	 * Get options for all SELECT statements
	 *
	 * @param $options Array: an optional options array which'll be appended to
	 *                       the default
	 * @return Array: options
	 */
	function getSelectOptions( $options = '' ) {
		if ( $this->mForUpdate ) {
			if ( is_array( $options ) ) {
				$options[] = 'FOR UPDATE';
			} else {
				$options = 'FOR UPDATE';
			}
		}
		return $options;
	}

	/**
	 * @return int Page ID
	 */
	function getID() {
		if( $this->mTitle ) {
			return $this->mTitle->getArticleID();
		} else {
			return 0;
		}
	}

	/**
	 * @return bool Whether or not the page exists in the database
	 */
	function exists() {
		return $this->getId() != 0;
	}

	/**
	 * @return int The view count for the page
	 */
	function getCount() {
		if ( -1 == $this->mCounter ) {
			$id = $this->getID();
			if ( $id == 0 ) {
				$this->mCounter = 0;
			} else {
				$dbr =& wfGetDB( DB_SLAVE );
				$this->mCounter = $dbr->selectField( 'page', 'page_counter', array( 'page_id' => $id ),
					'Article::getCount', $this->getSelectOptions() );
			}
		}
		return $this->mCounter;
	}

	/**
	 * Determine whether a page  would be suitable for being counted as an
	 * article in the site_stats table based on the title & its content
	 *
	 * @param $text String: text to analyze
	 * @return bool
	 */
	function isCountable( $text ) {
		global $wgUseCommaCount, $wgContentNamespaces;

		$token = $wgUseCommaCount ? ',' : '[[';
		return
			array_search( $this->mTitle->getNamespace(), $wgContentNamespaces ) !== false
			&& ! $this->isRedirect( $text )
			&& in_string( $token, $text );
	}

	/**
	 * Tests if the article text represents a redirect
	 *
	 * @param $text String: FIXME
	 * @return bool
	 */
	function isRedirect( $text = false ) {
		if ( $text === false ) {
			$this->loadContent();
			$titleObj = Title::newFromRedirect( $this->fetchContent() );
		} else {
			$titleObj = Title::newFromRedirect( $text );
		}
		return $titleObj !== NULL;
	}

	/**
	 * Returns true if the currently-referenced revision is the current edit
	 * to this page (and it exists).
	 * @return bool
	 */
	function isCurrent() {
		return $this->exists() &&
			isset( $this->mRevision ) &&
			$this->mRevision->isCurrent();
	}

	/**
	 * Loads everything except the text
	 * This isn't necessary for all uses, so it's only done if needed.
	 * @private
	 */
	function loadLastEdit() {
		if ( -1 != $this->mUser )
			return;

		# New or non-existent articles have no user information
		$id = $this->getID();
		if ( 0 == $id ) return;

		$this->mLastRevision = Revision::loadFromPageId( $this->getDB(), $id );
		if( !is_null( $this->mLastRevision ) ) {
			$this->mUser      = $this->mLastRevision->getUser();
			$this->mUserText  = $this->mLastRevision->getUserText();
			$this->mTimestamp = $this->mLastRevision->getTimestamp();
			$this->mComment   = $this->mLastRevision->getComment();
			$this->mMinorEdit = $this->mLastRevision->isMinor();
			$this->mRevIdFetched = $this->mLastRevision->getID();
		}
	}

	function getTimestamp() {
		// Check if the field has been filled by ParserCache::get()
		if ( !$this->mTimestamp ) {
			$this->loadLastEdit();
		}
		return wfTimestamp(TS_MW, $this->mTimestamp);
	}

	function getUser() {
		$this->loadLastEdit();
		return $this->mUser;
	}

	function getUserText() {
		$this->loadLastEdit();
		return $this->mUserText;
	}

	function getComment() {
		$this->loadLastEdit();
		return $this->mComment;
	}

	function getMinorEdit() {
		$this->loadLastEdit();
		return $this->mMinorEdit;
	}

	function getRevIdFetched() {
		$this->loadLastEdit();
		return $this->mRevIdFetched;
	}

	/**
	 * @todo Document, fixme $offset never used.
	 * @param $limit Integer: default 0.
	 * @param $offset Integer: default 0.
	 */
	function getContributors($limit = 0, $offset = 0) {
		# XXX: this is expensive; cache this info somewhere.

		$title = $this->mTitle;
		$contribs = array();
		$dbr =& wfGetDB( DB_SLAVE );
		$revTable = $dbr->tableName( 'revision' );
		$userTable = $dbr->tableName( 'user' );
		$encDBkey = $dbr->addQuotes( $title->getDBkey() );
		$ns = $title->getNamespace();
		$user = $this->getUser();
		$pageId = $this->getId();

		$sql = "SELECT rev_user, rev_user_text, user_real_name, MAX(rev_timestamp) as timestamp
			FROM $revTable LEFT JOIN $userTable ON rev_user = user_id
			WHERE rev_page = $pageId
			AND rev_user != $user
			GROUP BY rev_user, rev_user_text, user_real_name
			ORDER BY timestamp DESC";

		if ($limit > 0) { $sql .= ' LIMIT '.$limit; }
		$sql .= ' '. $this->getSelectOptions();

		$res = $dbr->query($sql, __METHOD__);

		while ( $line = $dbr->fetchObject( $res ) ) {
			$contribs[] = array($line->rev_user, $line->rev_user_text, $line->user_real_name);
		}

		$dbr->freeResult($res);
		return $contribs;
	}

	/**
	 * This is the default action of the script: just view the page of
	 * the given title.
	*/
	function view()	{
		global $wgUser, $wgOut, $wgRequest, $wgContLang;
		global $wgEnableParserCache, $wgStylePath, $wgUseRCPatrol, $wgParser;
		global $wgUseTrackbacks, $wgNamespaceRobotPolicies;
		$sk = $wgUser->getSkin();

		wfProfileIn( __METHOD__ );

		$parserCache =& ParserCache::singleton();
		$ns = $this->mTitle->getNamespace(); # shortcut
		
		# Get variables from query string
		$oldid = $this->getOldID();

		# getOldID may want us to redirect somewhere else
		if ( $this->mRedirectUrl ) {
			$wgOut->redirect( $this->mRedirectUrl );
			wfProfileOut( __METHOD__ );
			return;
		}

		$diff = $wgRequest->getVal( 'diff' );
		$rcid = $wgRequest->getVal( 'rcid' );
		$rdfrom = $wgRequest->getVal( 'rdfrom' );

		$wgOut->setArticleFlag( true );
		if ( isset( $wgNamespaceRobotPolicies[$ns] ) ) {
			$policy = $wgNamespaceRobotPolicies[$ns];
		} else {
			$policy = 'index,follow';
		}
		$wgOut->setRobotpolicy( $policy );

		# If we got diff and oldid in the query, we want to see a
		# diff page instead of the article.

		if ( !is_null( $diff ) ) {
			require_once( 'DifferenceEngine.php' );
			$wgOut->setPageTitle( $this->mTitle->getPrefixedText() );

			$de = new DifferenceEngine( $this->mTitle, $oldid, $diff, $rcid );
			// DifferenceEngine directly fetched the revision:
			$this->mRevIdFetched = $de->mNewid;
			$de->showDiffPage();

			if( $diff == 0 ) {
				# Run view updates for current revision only
				$this->viewUpdates();
			}
			wfProfileOut( __METHOD__ );
			return;
		}
		
		if ( empty( $oldid ) && $this->checkTouched() ) {
			$wgOut->setETag($parserCache->getETag($this, $wgUser));

			if( $wgOut->checkLastModified( $this->mTouched ) ){
				wfProfileOut( __METHOD__ );
				return;
			} else if ( $this->tryFileCache() ) {
				# tell wgOut that output is taken care of
				$wgOut->disable();
				$this->viewUpdates();
				wfProfileOut( __METHOD__ );
				return;
			}
		}
		# Should the parser cache be used?
		$pcache = $wgEnableParserCache &&
			intval( $wgUser->getOption( 'stubthreshold' ) ) == 0 &&
			$this->exists() &&
			empty( $oldid );
		wfDebug( 'Article::view using parser cache: ' . ($pcache ? 'yes' : 'no' ) . "\n" );
		if ( $wgUser->getOption( 'stubthreshold' ) ) {
			wfIncrStats( 'pcache_miss_stub' );
		}

		$wasRedirected = false;
		if ( isset( $this->mRedirectedFrom ) ) {
			// This is an internally redirected page view.
			// We'll need a backlink to the source page for navigation.
			if ( wfRunHooks( 'ArticleViewRedirect', array( &$this ) ) ) {
				$sk = $wgUser->getSkin();
				$redir = $sk->makeKnownLinkObj( $this->mRedirectedFrom, '', 'redirect=no' );
				$s = wfMsg( 'redirectedfrom', $redir );
				$wgOut->setSubtitle( $s );
				$wasRedirected = true;
			}
		} elseif ( !empty( $rdfrom ) ) {
			// This is an externally redirected view, from some other wiki.
			// If it was reported from a trusted site, supply a backlink.
			global $wgRedirectSources;
			if( $wgRedirectSources && preg_match( $wgRedirectSources, $rdfrom ) ) {
				$sk = $wgUser->getSkin();
				$redir = $sk->makeExternalLink( $rdfrom, $rdfrom );
				$s = wfMsg( 'redirectedfrom', $redir );
				$wgOut->setSubtitle( $s );
				$wasRedirected = true;
			}
		}
		
		$outputDone = false;
		if ( $pcache ) {
			if ( $wgOut->tryParserCache( $this, $wgUser ) ) {
				$outputDone = true;
			}
		}
		if ( !$outputDone ) {
			$text = $this->getContent();
			if ( $text === false ) {
				# Failed to load, replace text with error message
				$t = $this->mTitle->getPrefixedText();
				if( $oldid ) {
					$t .= ',oldid='.$oldid;
					$text = wfMsg( 'missingarticle', $t );
				} else {
					$text = wfMsg( 'noarticletext', $t );
				}
			}

			# Another whitelist check in case oldid is altering the title
			if ( !$this->mTitle->userCanRead() ) {
				$wgOut->loginToUse();
				$wgOut->output();
				exit;
			}

			# We're looking at an old revision

			if ( !empty( $oldid ) ) {
				$wgOut->setRobotpolicy( 'noindex,nofollow' );
				if( is_null( $this->mRevision ) ) {
					// FIXME: This would be a nice place to load the 'no such page' text.
				} else {
					$this->setOldSubtitle( isset($this->mOldId) ? $this->mOldId : $oldid );
					if( $this->mRevision->isDeleted( Revision::MW_REV_DELETED_TEXT ) ) {
						if( !$this->mRevision->userCan( Revision::MW_REV_DELETED_TEXT ) ) {
							$wgOut->addWikiText( wfMsg( 'rev-deleted-text-permission' ) );
							$wgOut->setPageTitle( $this->mTitle->getPrefixedText() );
							return;
						} else {
							$wgOut->addWikiText( wfMsg( 'rev-deleted-text-view' ) );
							// and we are allowed to see...
						}
					}
				}

			}
		}
		if( !$outputDone ) {
			/**
			 * @fixme: this hook doesn't work most of the time, as it doesn't
			 * trigger when the parser cache is used.
			 */
			wfRunHooks( 'ArticleViewHeader', array( &$this ) ) ;
			$wgOut->setRevisionId( $this->getRevIdFetched() );
			# wrap user css and user js in pre and don't parse
			# XXX: use $this->mTitle->usCssJsSubpage() when php is fixed/ a workaround is found
			if (
				$ns == NS_USER &&
				preg_match('/\\/[\\w]+\\.(css|js)$/', $this->mTitle->getDBkey())
			) {
				$wgOut->addWikiText( wfMsg('clearyourcache'));
				$wgOut->addHTML( '<pre>'.htmlspecialchars($this->mContent)."\n</pre>" );
			} else if ( $rt = Title::newFromRedirect( $text ) ) {
				# Display redirect
				$imageDir = $wgContLang->isRTL() ? 'rtl' : 'ltr';
				$imageUrl = $wgStylePath.'/common/images/redirect' . $imageDir . '.png';
				# Don't overwrite the subtitle if this was an old revision
				if( !$wasRedirected && $this->isCurrent() ) {
					$wgOut->setSubtitle( wfMsgHtml( 'redirectpagesub' ) );
				}
				$targetUrl = $rt->escapeLocalURL();
				# fixme unused $titleText :
				$titleText = htmlspecialchars( $rt->getPrefixedText() );
				$link = $sk->makeLinkObj( $rt );

				$wgOut->addHTML( '<img src="'.$imageUrl.'" alt="#REDIRECT" />' .
				  '<span class="redirectText">'.$link.'</span>' );

				$parseout = $wgParser->parse($text, $this->mTitle, ParserOptions::newFromUser($wgUser));
				$wgOut->addParserOutputNoText( $parseout );
			} else if ( $pcache ) {
				# Display content and save to parser cache
				$wgOut->addPrimaryWikiText( $text, $this );
			} else {
				# Display content, don't attempt to save to parser cache
				# Don't show section-edit links on old revisions... this way lies madness.
				if( !$this->isCurrent() ) {
					$oldEditSectionSetting = $wgOut->mParserOptions->setEditSection( false );
				}
				# Display content and don't save to parser cache
				$wgOut->addPrimaryWikiText( $text, $this, false );

				if( !$this->isCurrent() ) {
					$wgOut->mParserOptions->setEditSection( $oldEditSectionSetting );
				}
			}
		}
		/* title may have been set from the cache */
		$t = $wgOut->getPageTitle();
		if( empty( $t ) ) {
			$wgOut->setPageTitle( $this->mTitle->getPrefixedText() );
		}

		# check if we're displaying a [[User talk:x.x.x.x]] anonymous talk page
		if( $ns == NS_USER_TALK &&
			User::isIP( $this->mTitle->getText() ) ) {
			$wgOut->addWikiText( wfMsg('anontalkpagetext') );
		}

		# If we have been passed an &rcid= parameter, we want to give the user a
		# chance to mark this new article as patrolled.
		if ( $wgUseRCPatrol && !is_null( $rcid ) && $rcid != 0 && $wgUser->isAllowed( 'patrol' ) ) {
			$wgOut->addHTML(
				"<div class='patrollink'>" .
					wfMsg ( 'markaspatrolledlink',
					$sk->makeKnownLinkObj( $this->mTitle, wfMsg('markaspatrolledtext'), "action=markpatrolled&rcid=$rcid" )
			 		) .
				'</div>'
			 );
		}

		# Trackbacks
		if ($wgUseTrackbacks)
			$this->addTrackbacks();

		$this->viewUpdates();
		wfProfileOut( __METHOD__ );
	}

	function addTrackbacks() {
		global $wgOut, $wgUser;

		$dbr =& wfGetDB(DB_SLAVE);
		$tbs = $dbr->select(
				/* FROM   */ 'trackbacks',
				/* SELECT */ array('tb_id', 'tb_title', 'tb_url', 'tb_ex', 'tb_name'),
				/* WHERE  */ array('tb_page' => $this->getID())
		);

		if (!$dbr->numrows($tbs))
			return;

		$tbtext = "";
		while ($o = $dbr->fetchObject($tbs)) {
			$rmvtxt = "";
			if ($wgUser->isAllowed( 'trackback' )) {
				$delurl = $this->mTitle->getFullURL("action=deletetrackback&tbid="
						. $o->tb_id . "&token=" . $wgUser->editToken());
				$rmvtxt = wfMsg('trackbackremove', $delurl);
			}
			$tbtext .= wfMsg(strlen($o->tb_ex) ? 'trackbackexcerpt' : 'trackback',
					$o->tb_title,
					$o->tb_url,
					$o->tb_ex,
					$o->tb_name,
					$rmvtxt);
		}
		$wgOut->addWikitext(wfMsg('trackbackbox', $tbtext));
	}

	function deletetrackback() {
		global $wgUser, $wgRequest, $wgOut, $wgTitle;

		if (!$wgUser->matchEditToken($wgRequest->getVal('token'))) {
			$wgOut->addWikitext(wfMsg('sessionfailure'));
			return;
		}

		if ((!$wgUser->isAllowed('delete'))) {
			$wgOut->sysopRequired();
			return;
		}

		if (wfReadOnly()) {
			$wgOut->readOnlyPage();
			return;
		}

		$db =& wfGetDB(DB_MASTER);
		$db->delete('trackbacks', array('tb_id' => $wgRequest->getInt('tbid')));
		$wgTitle->invalidateCache();
		$wgOut->addWikiText(wfMsg('trackbackdeleteok'));
	}

	function render() {
		global $wgOut;

		$wgOut->setArticleBodyOnly(true);
		$this->view();
	}

	/**
	 * Handle action=purge
	 */
	function purge() {
		global $wgUser, $wgRequest, $wgOut;

		if ( $wgUser->isLoggedIn() || $wgRequest->wasPosted() ) {
			if( wfRunHooks( 'ArticlePurge', array( &$this ) ) ) {
				$this->doPurge();
			}
		} else {
			$msg = $wgOut->parse( wfMsg( 'confirm_purge' ) );
			$action = $this->mTitle->escapeLocalURL( 'action=purge' );
			$button = htmlspecialchars( wfMsg( 'confirm_purge_button' ) );
			$msg = str_replace( '$1',
				"<form method=\"post\" action=\"$action\">\n" .
				"<input type=\"submit\" name=\"submit\" value=\"$button\" />\n" .
				"</form>\n", $msg );

			$wgOut->setPageTitle( $this->mTitle->getPrefixedText() );
			$wgOut->setRobotpolicy( 'noindex,nofollow' );
			$wgOut->addHTML( $msg );
		}
	}
	
	/**
	 * Perform the actions of a page purging
	 */
	function doPurge() {
		global $wgUseSquid;
		// Invalidate the cache
		$this->mTitle->invalidateCache();

		if ( $wgUseSquid ) {
			// Commit the transaction before the purge is sent
			$dbw = wfGetDB( DB_MASTER );
			$dbw->immediateCommit();

			// Send purge
			$update = SquidUpdate::newSimplePurge( $this->mTitle );
			$update->doUpdate();
		}
		$this->view();
	}

	/**
	 * Insert a new empty page record for this article.
	 * This *must* be followed up by creating a revision
	 * and running $this->updateToLatest( $rev_id );
	 * or else the record will be left in a funky state.
	 * Best if all done inside a transaction.
	 *
	 * @param Database $dbw
	 * @param string   $restrictions
	 * @return int     The newly created page_id key
	 * @private
	 */
	function insertOn( &$dbw, $restrictions = '' ) {
		wfProfileIn( __METHOD__ );

		$page_id = $dbw->nextSequenceValue( 'page_page_id_seq' );
		$dbw->insert( 'page', array(
			'page_id'           => $page_id,
			'page_namespace'    => $this->mTitle->getNamespace(),
			'page_title'        => $this->mTitle->getDBkey(),
			'page_counter'      => 0,
			'page_restrictions' => $restrictions,
			'page_is_redirect'  => 0, # Will set this shortly...
			'page_is_new'       => 1,
			'page_random'       => wfRandom(),
			'page_touched'      => $dbw->timestamp(),
			'page_latest'       => 0, # Fill this in shortly...
			'page_len'          => 0, # Fill this in shortly...
		), __METHOD__ );
		$newid = $dbw->insertId();

		$this->mTitle->resetArticleId( $newid );

		wfProfileOut( __METHOD__ );
		return $newid;
	}

	/**
	 * Update the page record to point to a newly saved revision.
	 *
	 * @param Database $dbw
	 * @param Revision $revision For ID number, and text used to set
	                             length and redirect status fields
	 * @param int $lastRevision If given, will not overwrite the page field
	 *                          when different from the currently set value.
	 *                          Giving 0 indicates the new page flag should
	 *                          be set on.
	 * @return bool true on success, false on failure
	 * @private
	 */
	function updateRevisionOn( &$dbw, $revision, $lastRevision = null ) {
		wfProfileIn( __METHOD__ );

		$conditions = array( 'page_id' => $this->getId() );
		if( !is_null( $lastRevision ) ) {
			# An extra check against threads stepping on each other
			$conditions['page_latest'] = $lastRevision;
		}

		$text = $revision->getText();
		$dbw->update( 'page',
			array( /* SET */
				'page_latest'      => $revision->getId(),
				'page_touched'     => $dbw->timestamp(),
				'page_is_new'      => ($lastRevision === 0) ? 1 : 0,
				'page_is_redirect' => Article::isRedirect( $text ) ? 1 : 0,
				'page_len'         => strlen( $text ),
			),
			$conditions,
			__METHOD__ );

		wfProfileOut( __METHOD__ );
		return ( $dbw->affectedRows() != 0 );
	}

	/**
	 * If the given revision is newer than the currently set page_latest,
	 * update the page record. Otherwise, do nothing.
	 *
	 * @param Database $dbw
	 * @param Revision $revision
	 */
	function updateIfNewerOn( &$dbw, $revision ) {
		wfProfileIn( __METHOD__ );

		$row = $dbw->selectRow(
			array( 'revision', 'page' ),
			array( 'rev_id', 'rev_timestamp' ),
			array(
				'page_id' => $this->getId(),
				'page_latest=rev_id' ),
			__METHOD__ );
		if( $row ) {
			if( wfTimestamp(TS_MW, $row->rev_timestamp) >= $revision->getTimestamp() ) {
				wfProfileOut( __METHOD__ );
				return false;
			}
			$prev = $row->rev_id;
		} else {
			# No or missing previous revision; mark the page as new
			$prev = 0;
		}

		$ret = $this->updateRevisionOn( $dbw, $revision, $prev );
		wfProfileOut( __METHOD__ );
		return $ret;
	}

	/**
	 * @return string Complete article text, or null if error
	 */
	function replaceSection($section, $text, $summary = '', $edittime = NULL) {
		wfProfileIn( __METHOD__ );
		
		if( $section == '' ) {
			// Whole-page edit; let the text through unmolested.
		} else {
			if( is_null( $edittime ) ) {
				$rev = Revision::newFromTitle( $this->mTitle );
			} else {
				$dbw =& wfGetDB( DB_MASTER );
				$rev = Revision::loadFromTimestamp( $dbw, $this->mTitle, $edittime );
			}
			if( is_null( $rev ) ) {
				wfDebug( "Article::replaceSection asked for bogus section (page: " .
					$this->getId() . "; section: $section; edittime: $edittime)\n" );
				return null;
			}
			$oldtext = $rev->getText();

			if($section=='new') {
				if($summary) $subject="== {$summary} ==\n\n";
				$text=$oldtext."\n\n".$subject.$text;
			} else {
				global $wgParser;
				$text = $wgParser->replaceSection( $oldtext, $section, $text );
			}
		}

		wfProfileOut( __METHOD__ );
		return $text;
	}

	/**
	 * @deprecated use Article::doEdit()
	 */
	function insertNewArticle( $text, $summary, $isminor, $watchthis, $suppressRC=false, $comment=false ) {
		$flags = EDIT_NEW | EDIT_DEFER_UPDATES |
			( $isminor ? EDIT_MINOR : 0 ) |
			( $suppressRC ? EDIT_SUPPRESS_RC : 0 );

		# If this is a comment, add the summary as headline
		if ( $comment && $summary != "" ) {
			$text = "== {$summary} ==\n\n".$text;
		}
		
		$this->doEdit( $text, $summary, $flags );

		$dbw =& wfGetDB( DB_MASTER );
		if ($watchthis) {
			if (!$this->mTitle->userIsWatching()) {
				$dbw->begin();
				$this->doWatch();
				$dbw->commit();
			}
		} else {
			if ( $this->mTitle->userIsWatching() ) {
				$dbw->begin();
				$this->doUnwatch();
				$dbw->commit();
			}
		}
		$this->doRedirect( $this->isRedirect( $text ) );
	}

	/**
	 * @deprecated use Article::doEdit()
	 */
	function updateArticle( $text, $summary, $minor, $watchthis, $forceBot = false, $sectionanchor = '' ) {
		$flags = EDIT_UPDATE | EDIT_DEFER_UPDATES |
			( $minor ? EDIT_MINOR : 0 ) |
			( $forceBot ? EDIT_FORCE_BOT : 0 );

		$good = $this->doEdit( $text, $summary, $flags );
		if ( $good ) {
			$dbw =& wfGetDB( DB_MASTER );
			if ($watchthis) {
				if (!$this->mTitle->userIsWatching()) {
					$dbw->begin();
					$this->doWatch();
					$dbw->commit();
				}
			} else {
				if ( $this->mTitle->userIsWatching() ) {
					$dbw->begin();
					$this->doUnwatch();
					$dbw->commit();
				}
			}

			$this->doRedirect( $this->isRedirect( $text ), $sectionanchor );
		}
		return $good;
	}

	/**
	 * Article::doEdit()
	 *
	 * Change an existing article or create a new article. Updates RC and all necessary caches, 
	 * optionally via the deferred update array.
	 *
	 * It is possible to call this function from a command-line script, but note that you should
	 * first set $wgUser, and clean up $wgDeferredUpdates after each edit.
	 *
	 * $wgUser must be set before calling this function.
	 *
	 * @param string $text New text
	 * @param string $summary Edit summary
	 * @param integer $flags bitfield:
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
	 *      EDIT_DEFER_UPDATES
	 *          Defer some of the updates until the end of index.php
	 * 
	 * If neither EDIT_NEW nor EDIT_UPDATE is specified, the status of the article will be detected. 
	 * If EDIT_UPDATE is specified and the article doesn't exist, the function will return false. If 
	 * EDIT_NEW is specified and the article does exist, a duplicate key error will cause an exception
	 * to be thrown from the Database. These two conditions are also possible with auto-detection due
	 * to MediaWiki's performance-optimised locking strategy.
	 *
	 * @return bool success
	 */
	function doEdit( $text, $summary, $flags = 0 ) {
		global $wgUser, $wgDBtransactions;

		wfProfileIn( __METHOD__ );
		$good = true;

		if ( !($flags & EDIT_NEW) && !($flags & EDIT_UPDATE) ) {
			$aid = $this->mTitle->getArticleID( GAID_FOR_UPDATE );
			if ( $aid ) {
				$flags |= EDIT_UPDATE;
			} else {
				$flags |= EDIT_NEW;
			}
		}

		if( !wfRunHooks( 'ArticleSave', array( &$this, &$wgUser, &$text,
			&$summary, $flags & EDIT_MINOR,
			null, null, &$flags ) ) ) 
		{
			wfDebug( __METHOD__ . ": ArticleSave hook aborted save!\n" );
			wfProfileOut( __METHOD__ );
			return false;
		}

		# Silently ignore EDIT_MINOR if not allowed
		$isminor = ( $flags & EDIT_MINOR ) && $wgUser->isAllowed('minoredit');
		$bot = $wgUser->isBot() || ( $flags & EDIT_FORCE_BOT );

		$text = $this->preSaveTransform( $text );

		$dbw =& wfGetDB( DB_MASTER );
		$now = wfTimestampNow();
		
		if ( $flags & EDIT_UPDATE ) {
			# Update article, but only if changed.

			# Make sure the revision is either completely inserted or not inserted at all
			if( !$wgDBtransactions ) {
				$userAbort = ignore_user_abort( true );
			}

			$oldtext = $this->getContent();
			$oldsize = strlen( $oldtext );
			$newsize = strlen( $text );
			$lastRevision = 0;
			$revisionId = 0;

			if ( 0 != strcmp( $text, $oldtext ) ) {
				$this->mGoodAdjustment = (int)$this->isCountable( $text )
				  - (int)$this->isCountable( $oldtext );
				$this->mTotalAdjustment = 0;

				$lastRevision = $dbw->selectField(
					'page', 'page_latest', array( 'page_id' => $this->getId() ) );

				if ( !$lastRevision ) {
					# Article gone missing
					wfDebug( __METHOD__.": EDIT_UPDATE specified but article doesn't exist\n" );
					wfProfileOut( __METHOD__ );
					return false;
				}
				
				$revision = new Revision( array(
					'page'       => $this->getId(),
					'comment'    => $summary,
					'minor_edit' => $isminor,
					'text'       => $text
					) );

				$dbw->begin();
				$revisionId = $revision->insertOn( $dbw );

				# Update page
				$ok = $this->updateRevisionOn( $dbw, $revision, $lastRevision );

				if( !$ok ) {
					/* Belated edit conflict! Run away!! */
					$good = false;
					$dbw->rollback();
				} else {
					# Update recentchanges
					if( !( $flags & EDIT_SUPPRESS_RC ) ) {
						$rcid = RecentChange::notifyEdit( $now, $this->mTitle, $isminor, $wgUser, $summary,
							$lastRevision, $this->getTimestamp(), $bot, '', $oldsize, $newsize,
							$revisionId );
							
						# Mark as patrolled if the user can do so and has it set in their options
						if( $wgUser->isAllowed( 'patrol' ) && $wgUser->getOption( 'autopatrol' ) ) {
							RecentChange::markPatrolled( $rcid );
						}
					}
					$dbw->commit();
				}
			} else {
				// Keep the same revision ID, but do some updates on it
				$revisionId = $this->getRevIdFetched();
				// Update page_touched, this is usually implicit in the page update
				// Other cache updates are done in onArticleEdit()
				$this->mTitle->invalidateCache();
			}

			if( !$wgDBtransactions ) {
				ignore_user_abort( $userAbort );
			}

			if ( $good ) {
				# Invalidate cache of this article and all pages using this article 
				# as a template. Partly deferred.
				Article::onArticleEdit( $this->mTitle );
				
				# Update links tables, site stats, etc.
				$this->editUpdates( $text, $summary, $isminor, $now, $revisionId );
			}
		} else {
			# Create new article
			
			# Set statistics members
			# We work out if it's countable after PST to avoid counter drift 
			# when articles are created with {{subst:}}
			$this->mGoodAdjustment = (int)$this->isCountable( $text );
			$this->mTotalAdjustment = 1;

			$dbw->begin();

			# Add the page record; stake our claim on this title!
			# This will fail with a database query exception if the article already exists
			$newid = $this->insertOn( $dbw );

			# Save the revision text...
			$revision = new Revision( array(
				'page'       => $newid,
				'comment'    => $summary,
				'minor_edit' => $isminor,
				'text'       => $text
				) );
			$revisionId = $revision->insertOn( $dbw );

			$this->mTitle->resetArticleID( $newid );

			# Update the page record with revision data
			$this->updateRevisionOn( $dbw, $revision, 0 );

			if( !( $flags & EDIT_SUPPRESS_RC ) ) {
				$rcid = RecentChange::notifyNew( $now, $this->mTitle, $isminor, $wgUser, $summary, $bot,
				  '', strlen( $text ), $revisionId );
				# Mark as patrolled if the user can and has the option set
				if( $wgUser->isAllowed( 'patrol' ) && $wgUser->getOption( 'autopatrol' ) ) {
					RecentChange::markPatrolled( $rcid );
				}
			}
			$dbw->commit();

			# Update links, etc.
			$this->editUpdates( $text, $summary, $isminor, $now, $revisionId );

			# Clear caches
			Article::onArticleCreate( $this->mTitle );

			wfRunHooks( 'ArticleInsertComplete', array( &$this, &$wgUser, $text,
				$summary, $flags & EDIT_MINOR,
				null, null, &$flags ) );
		}

		if ( $good && !( $flags & EDIT_DEFER_UPDATES ) ) {
			wfDoUpdates();
		}

		wfRunHooks( 'ArticleSaveComplete',
			array( &$this, &$wgUser, $text,
			$summary, $flags & EDIT_MINOR,
			null, null, &$flags ) );
		
		wfProfileOut( __METHOD__ );
		return $good;
	}

	/**
	 * @deprecated wrapper for doRedirect
	 */
	function showArticle( $text, $subtitle , $sectionanchor = '', $me2, $now, $summary, $oldid ) {
		$this->doRedirect( $this->isRedirect( $text ), $sectionanchor );
	}

	/**
	 * Output a redirect back to the article.
	 * This is typically used after an edit.
	 *
	 * @param boolean $noRedir Add redirect=no
	 * @param string $sectionAnchor section to redirect to, including "#"
	 */
	function doRedirect( $noRedir = false, $sectionAnchor = '' ) {
		global $wgOut;
		if ( $noRedir ) {
			$query = 'redirect=no';
		} else {
			$query = '';
		}
		$wgOut->redirect( $this->mTitle->getFullURL( $query ) . $sectionAnchor );
	}
		
	/**
	 * Mark this particular edit as patrolled
	 */
	function markpatrolled() {
		global $wgOut, $wgRequest, $wgUseRCPatrol, $wgUser;
		$wgOut->setRobotpolicy( 'noindex,nofollow' );

		# Check RC patrol config. option
		if( !$wgUseRCPatrol ) {
			$wgOut->errorPage( 'rcpatroldisabled', 'rcpatroldisabledtext' );
			return;
		}
		
		# Check permissions
		if( !$wgUser->isAllowed( 'patrol' ) ) {
			$wgOut->permissionRequired( 'patrol' );
			return;
		}
		
		$rcid = $wgRequest->getVal( 'rcid' );
		if ( !is_null ( $rcid ) ) {
			if( wfRunHooks( 'MarkPatrolled', array( &$rcid, &$wgUser, false ) ) ) {
				RecentChange::markPatrolled( $rcid );
				wfRunHooks( 'MarkPatrolledComplete', array( &$rcid, &$wgUser, false ) );
				$wgOut->setPagetitle( wfMsg( 'markedaspatrolled' ) );
				$wgOut->addWikiText( wfMsg( 'markedaspatrolledtext' ) );
			}
			$rcTitle = Title::makeTitle( NS_SPECIAL, 'Recentchanges' );
			$wgOut->returnToMain( false, $rcTitle->getPrefixedText() );
		}
		else {
			$wgOut->showErrorPage( 'markedaspatrollederror', 'markedaspatrollederrortext' );
		}
	}

	/**
	 * User-interface handler for the "watch" action
	 */

	function watch() {

		global $wgUser, $wgOut;

		if ( $wgUser->isAnon() ) {
			$wgOut->showErrorPage( 'watchnologin', 'watchnologintext' );
			return;
		}
		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}
		
		if( $this->doWatch() ) {
			$wgOut->setPagetitle( wfMsg( 'addedwatch' ) );
			$wgOut->setRobotpolicy( 'noindex,nofollow' );

			$link = $this->mTitle->getPrefixedText();
			$text = wfMsg( 'addedwatchtext', $link );
			$wgOut->addWikiText( $text );
		}

		$wgOut->returnToMain( true, $this->mTitle->getPrefixedText() );
	}
	
	/**
	 * Add this page to $wgUser's watchlist
	 * @return bool true on successful watch operation
	 */
	function doWatch() {
		global $wgUser;
		if( $wgUser->isAnon() ) {
			return false;
		}
		
		if (wfRunHooks('WatchArticle', array(&$wgUser, &$this))) {
			$wgUser->addWatch( $this->mTitle );
			$wgUser->saveSettings();

			return wfRunHooks('WatchArticleComplete', array(&$wgUser, &$this));
		}
		
		return false;
	}

	/**
	 * User interface handler for the "unwatch" action.
	 */
	function unwatch() {

		global $wgUser, $wgOut;

		if ( $wgUser->isAnon() ) {
			$wgOut->showErrorPage( 'watchnologin', 'watchnologintext' );
			return;
		}
		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}
		
		if( $this->doUnwatch() ) {
			$wgOut->setPagetitle( wfMsg( 'removedwatch' ) );
			$wgOut->setRobotpolicy( 'noindex,nofollow' );

			$link = $this->mTitle->getPrefixedText();
			$text = wfMsg( 'removedwatchtext', $link );
			$wgOut->addWikiText( $text );
		}

		$wgOut->returnToMain( true, $this->mTitle->getPrefixedText() );
	}
	
	/**
	 * Stop watching a page
	 * @return bool true on successful unwatch
	 */
	function doUnwatch() {
		global $wgUser;
		if( $wgUser->isAnon() ) {
			return false;
		}

		if (wfRunHooks('UnwatchArticle', array(&$wgUser, &$this))) {
			$wgUser->removeWatch( $this->mTitle );
			$wgUser->saveSettings();

			return wfRunHooks('UnwatchArticleComplete', array(&$wgUser, &$this));
		}
		
		return false;
	}

	/**
	 * action=protect handler
	 */
	function protect() {
		require_once 'ProtectionForm.php';
		$form = new ProtectionForm( $this );
		$form->show();
	}

	/**
	 * action=unprotect handler (alias)
	 */
	function unprotect() {
		$this->protect();
	}

	/**
	 * Update the article's restriction field, and leave a log entry.
	 *
	 * @param array $limit set of restriction keys
	 * @param string $reason
	 * @return bool true on success
	 */
	function updateRestrictions( $limit = array(), $reason = '' ) {
		global $wgUser, $wgRestrictionTypes, $wgContLang;
		
		$id = $this->mTitle->getArticleID();
		if( !$wgUser->isAllowed( 'protect' ) || wfReadOnly() || $id == 0 ) {
			return false;
		}

		# FIXME: Same limitations as described in ProtectionForm.php (line 37);
		# we expect a single selection, but the schema allows otherwise.
		$current = array();
		foreach( $wgRestrictionTypes as $action )
			$current[$action] = implode( '', $this->mTitle->getRestrictions( $action ) );

		$current = Article::flattenRestrictions( $current );
		$updated = Article::flattenRestrictions( $limit );
		
		$changed = ( $current != $updated );
		$protect = ( $updated != '' );
		
		# If nothing's changed, do nothing
		if( $changed ) {
			if( wfRunHooks( 'ArticleProtect', array( &$this, &$wgUser, $limit, $reason ) ) ) {

				$dbw =& wfGetDB( DB_MASTER );
				
				# Prepare a null revision to be added to the history
				$comment = $wgContLang->ucfirst( wfMsgForContent( $protect ? 'protectedarticle' : 'unprotectedarticle', $this->mTitle->getPrefixedText() ) );
				if( $reason )
					$comment .= ": $reason";
				if( $protect )
					$comment .= " [$updated]";
				$nullRevision = Revision::newNullRevision( $dbw, $id, $comment, true );
				$nullRevId = $nullRevision->insertOn( $dbw );
			
				# Update page record
				$dbw->update( 'page',
					array( /* SET */
						'page_touched' => $dbw->timestamp(),
						'page_restrictions' => $updated,
						'page_latest' => $nullRevId
					), array( /* WHERE */
						'page_id' => $id
					), 'Article::protect'
				);
				wfRunHooks( 'ArticleProtectComplete', array( &$this, &$wgUser, $limit, $reason ) );
	
				# Update the protection log
				$log = new LogPage( 'protect' );
				if( $protect ) {
					$log->addEntry( 'protect', $this->mTitle, trim( $reason . " [$updated]" ) );
				} else {
					$log->addEntry( 'unprotect', $this->mTitle, $reason );
				}
				
			} # End hook
		} # End "changed" check
		
		return true;
	}

	/**
	 * Take an array of page restrictions and flatten it to a string
	 * suitable for insertion into the page_restrictions field.
	 * @param array $limit
	 * @return string
	 * @private
	 */
	function flattenRestrictions( $limit ) {
		if( !is_array( $limit ) ) {
			throw new MWException( 'Article::flattenRestrictions given non-array restriction set' );
		}
		$bits = array();
		ksort( $limit );
		foreach( $limit as $action => $restrictions ) {
			if( $restrictions != '' ) {
				$bits[] = "$action=$restrictions";
			}
		}
		return implode( ':', $bits );
	}

	/*
	 * UI entry point for page deletion
	 */
	function delete() {
		global $wgUser, $wgOut, $wgRequest;
		$confirm = $wgRequest->wasPosted() &&
			$wgUser->matchEditToken( $wgRequest->getVal( 'wpEditToken' ) );
		$reason = $wgRequest->getText( 'wpReason' );

		# This code desperately needs to be totally rewritten

		# Check permissions
		if( $wgUser->isAllowed( 'delete' ) ) {
			if( $wgUser->isBlocked() ) {
				$wgOut->blockedPage();
				return;
			}
		} else {
			$wgOut->permissionRequired( 'delete' );
			return;
		}

		if( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}

		$wgOut->setPagetitle( wfMsg( 'confirmdelete' ) );
		
		# Better double-check that it hasn't been deleted yet!
		$dbw =& wfGetDB( DB_MASTER );
		$conds = $this->mTitle->pageCond();
		$latest = $dbw->selectField( 'page', 'page_latest', $conds, __METHOD__ );
		if ( $latest === false ) {
			$wgOut->showFatalError( wfMsg( 'cannotdelete' ) );
			return;
		}

		if( $confirm ) {
			$this->doDelete( $reason );
			return;
		}

		# determine whether this page has earlier revisions
		# and insert a warning if it does
		$maxRevisions = 20;
		$authors = $this->getLastNAuthors( $maxRevisions, $latest );
		
		if( count( $authors ) > 1 && !$confirm ) {
			$skin=$wgUser->getSkin();
			$wgOut->addHTML( '<strong>' . wfMsg( 'historywarning' ) . ' ' . $skin->historyLink() . '</strong>' );
		}

		# If a single user is responsible for all revisions, find out who they are
		if ( count( $authors ) == $maxRevisions ) {
			// Query bailed out, too many revisions to find out if they're all the same
			$authorOfAll = false;
		} else {
			$authorOfAll = reset( $authors );
			foreach ( $authors as $author ) {
				if ( $authorOfAll != $author ) {
					$authorOfAll = false;
					break;
				}
			}
		}
		# Fetch article text
		$rev = Revision::newFromTitle( $this->mTitle );

		if( !is_null( $rev ) ) {
			# if this is a mini-text, we can paste part of it into the deletion reason
			$text = $rev->getText();

			#if this is empty, an earlier revision may contain "useful" text
			$blanked = false;
			if( $text == '' ) {
				$prev = $rev->getPrevious();
				if( $prev ) {
					$text = $prev->getText();
					$blanked = true;
				}
			}

			$length = strlen( $text );

			# this should not happen, since it is not possible to store an empty, new
			# page. Let's insert a standard text in case it does, though
			if( $length == 0 && $reason === '' ) {
				$reason = wfMsgForContent( 'exblank' );
			}

			if( $length < 500 && $reason === '' ) {
				# comment field=255, let's grep the first 150 to have some user
				# space left
				global $wgContLang;
				$text = $wgContLang->truncate( $text, 150, '...' );

				# let's strip out newlines
				$text = preg_replace( "/[\n\r]/", '', $text );

				if( !$blanked ) {
					if( $authorOfAll === false ) {
						$reason = wfMsgForContent( 'excontent', $text );
					} else {
						$reason = wfMsgForContent( 'excontentauthor', $text, $authorOfAll );
					}
				} else {
					$reason = wfMsgForContent( 'exbeforeblank', $text );
				}
			}
		}

		return $this->confirmDelete( '', $reason );
	}

	/**
	 * Get the last N authors
	 * @param int $num Number of revisions to get
	 * @param string $revLatest The latest rev_id, selected from the master (optional)
	 * @return array Array of authors, duplicates not removed
	 */
	function getLastNAuthors( $num, $revLatest = 0 ) {
		wfProfileIn( __METHOD__ );

		// First try the slave
		// If that doesn't have the latest revision, try the master
		$continue = 2;
		$db =& wfGetDB( DB_SLAVE );
		do {
			$res = $db->select( array( 'page', 'revision' ),
				array( 'rev_id', 'rev_user_text' ),
				array(
					'page_namespace' => $this->mTitle->getNamespace(),
					'page_title' => $this->mTitle->getDBkey(),
					'rev_page = page_id'
				), __METHOD__, $this->getSelectOptions( array(
					'ORDER BY' => 'rev_timestamp DESC',
					'LIMIT' => $num
				) )
			);
			if ( !$res ) {
				wfProfileOut( __METHOD__ );
				return array();
			}
			$row = $db->fetchObject( $res );
			if ( $continue == 2 && $revLatest && $row->rev_id != $revLatest ) {
				$db =& wfGetDB( DB_MASTER );
				$continue--;
			} else {
				$continue = 0;
			}
		} while ( $continue );

		$authors = array( $row->rev_user_text );
		while ( $row = $db->fetchObject( $res ) ) {
			$authors[] = $row->rev_user_text;
		}
		wfProfileOut( __METHOD__ );
		return $authors;
	}
	
	/**
	 * Output deletion confirmation dialog
	 */
	function confirmDelete( $par, $reason ) {
		global $wgOut, $wgUser;

		wfDebug( "Article::confirmDelete\n" );

		$sub = htmlspecialchars( $this->mTitle->getPrefixedText() );
		$wgOut->setSubtitle( wfMsg( 'deletesub', $sub ) );
		$wgOut->setRobotpolicy( 'noindex,nofollow' );
		$wgOut->addWikiText( wfMsg( 'confirmdeletetext' ) );

		$formaction = $this->mTitle->escapeLocalURL( 'action=delete' . $par );

		$confirm = htmlspecialchars( wfMsg( 'deletepage' ) );
		$delcom = htmlspecialchars( wfMsg( 'deletecomment' ) );
		$token = htmlspecialchars( $wgUser->editToken() );

		$wgOut->addHTML( "
<form id='deleteconfirm' method='post' action=\"{$formaction}\">
	<table border='0'>
		<tr>
			<td align='right'>
				<label for='wpReason'>{$delcom}:</label>
			</td>
			<td align='left'>
				<input type='text' size='60' name='wpReason' id='wpReason' value=\"" . htmlspecialchars( $reason ) . "\" />
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<input type='submit' name='wpConfirmB' value=\"{$confirm}\" />
			</td>
		</tr>
	</table>
	<input type='hidden' name='wpEditToken' value=\"{$token}\" />
</form>\n" );

		$wgOut->returnToMain( false );
	}


	/**
	 * Perform a deletion and output success or failure messages
	 */
	function doDelete( $reason ) {
		global $wgOut, $wgUser;
		wfDebug( __METHOD__."\n" );

		if (wfRunHooks('ArticleDelete', array(&$this, &$wgUser, &$reason))) {
			if ( $this->doDeleteArticle( $reason ) ) {
				$deleted = $this->mTitle->getPrefixedText();

				$wgOut->setPagetitle( wfMsg( 'actioncomplete' ) );
				$wgOut->setRobotpolicy( 'noindex,nofollow' );

				$loglink = '[[Special:Log/delete|' . wfMsg( 'deletionlog' ) . ']]';
				$text = wfMsg( 'deletedtext', $deleted, $loglink );

				$wgOut->addWikiText( $text );
				$wgOut->returnToMain( false );
				wfRunHooks('ArticleDeleteComplete', array(&$this, &$wgUser, $reason));
			} else {
				$wgOut->showFatalError( wfMsg( 'cannotdelete' ) );
			}
		}
	}

	/**
	 * Back-end article deletion
	 * Deletes the article with database consistency, writes logs, purges caches
	 * Returns success
	 */
	function doDeleteArticle( $reason ) {
		global $wgUseSquid, $wgDeferredUpdateList;
		global $wgPostCommitUpdateList, $wgUseTrackbacks;

		wfDebug( __METHOD__."\n" );

		$dbw =& wfGetDB( DB_MASTER );
		$ns = $this->mTitle->getNamespace();
		$t = $this->mTitle->getDBkey();
		$id = $this->mTitle->getArticleID();

		if ( $t == '' || $id == 0 ) {
			return false;
		}

		$u = new SiteStatsUpdate( 0, 1, -(int)$this->isCountable( $this->getContent() ), -1 );
		array_push( $wgDeferredUpdateList, $u );

		// For now, shunt the revision data into the archive table.
		// Text is *not* removed from the text table; bulk storage
		// is left intact to avoid breaking block-compression or
		// immutable storage schemes.
		//
		// For backwards compatibility, note that some older archive
		// table entries will have ar_text and ar_flags fields still.
		//
		// In the future, we may keep revisions and mark them with
		// the rev_deleted field, which is reserved for this purpose.
		$dbw->insertSelect( 'archive', array( 'page', 'revision' ),
			array(
				'ar_namespace'  => 'page_namespace',
				'ar_title'      => 'page_title',
				'ar_comment'    => 'rev_comment',
				'ar_user'       => 'rev_user',
				'ar_user_text'  => 'rev_user_text',
				'ar_timestamp'  => 'rev_timestamp',
				'ar_minor_edit' => 'rev_minor_edit',
				'ar_rev_id'     => 'rev_id',
				'ar_text_id'    => 'rev_text_id',
			), array(
				'page_id' => $id,
				'page_id = rev_page'
			), __METHOD__
		);

		# Now that it's safely backed up, delete it
		$dbw->delete( 'revision', array( 'rev_page' => $id ), __METHOD__ );
		$dbw->delete( 'page', array( 'page_id' => $id ), __METHOD__);

		if ($wgUseTrackbacks)
			$dbw->delete( 'trackbacks', array( 'tb_page' => $id ), __METHOD__ );

 		# Clean up recentchanges entries...
		$dbw->delete( 'recentchanges', array( 'rc_namespace' => $ns, 'rc_title' => $t ), __METHOD__ );

		# Finally, clean up the link tables
		$t = $this->mTitle->getPrefixedDBkey();

		# Clear caches
		Article::onArticleDelete( $this->mTitle );

		# Delete outgoing links
		$dbw->delete( 'pagelinks', array( 'pl_from' => $id ) );
		$dbw->delete( 'imagelinks', array( 'il_from' => $id ) );
		$dbw->delete( 'categorylinks', array( 'cl_from' => $id ) );
		$dbw->delete( 'templatelinks', array( 'tl_from' => $id ) );
		$dbw->delete( 'externallinks', array( 'el_from' => $id ) );
		$dbw->delete( 'langlinks', array( 'll_from' => $id ) );

		# Log the deletion
		$log = new LogPage( 'delete' );
		$log->addEntry( 'delete', $this->mTitle, $reason );

		# Clear the cached article id so the interface doesn't act like we exist
		$this->mTitle->resetArticleID( 0 );
		$this->mTitle->mArticleID = 0;
		return true;
	}

	/**
	 * Revert a modification
	 */
	function rollback() {
		global $wgUser, $wgOut, $wgRequest, $wgUseRCPatrol;

		if( $wgUser->isAllowed( 'rollback' ) ) {
			if( $wgUser->isBlocked() ) {
				$wgOut->blockedPage();
				return;
			}
		} else {
			$wgOut->permissionRequired( 'rollback' );
			return;
		}

		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage( $this->getContent() );
			return;
		}
		if( !$wgUser->matchEditToken( $wgRequest->getVal( 'token' ),
			array( $this->mTitle->getPrefixedText(),
				$wgRequest->getVal( 'from' ) )  ) ) {
			$wgOut->setPageTitle( wfMsg( 'rollbackfailed' ) );
			$wgOut->addWikiText( wfMsg( 'sessionfailure' ) );
			return;
		}
		$dbw =& wfGetDB( DB_MASTER );

		# Enhanced rollback, marks edits rc_bot=1
		$bot = $wgRequest->getBool( 'bot' );

		# Replace all this user's current edits with the next one down
		$tt = $this->mTitle->getDBKey();
		$n = $this->mTitle->getNamespace();

		# Get the last editor
		$current = Revision::newFromTitle( $this->mTitle );
		if( is_null( $current ) ) {
			# Something wrong... no page?
			$wgOut->addHTML( wfMsg( 'notanarticle' ) );
			return;
		}

		$from = str_replace( '_', ' ', $wgRequest->getVal( 'from' ) );
		if( $from != $current->getUserText() ) {
			$wgOut->setPageTitle( wfMsg('rollbackfailed') );
			$wgOut->addWikiText( wfMsg( 'alreadyrolled',
				htmlspecialchars( $this->mTitle->getPrefixedText()),
				htmlspecialchars( $from ),
				htmlspecialchars( $current->getUserText() ) ) );
			if( $current->getComment() != '') {
				$wgOut->addHTML(
					wfMsg( 'editcomment',
					htmlspecialchars( $current->getComment() ) ) );
			}
			return;
		}

		# Get the last edit not by this guy
		$user = intval( $current->getUser() );
		$user_text = $dbw->addQuotes( $current->getUserText() );
		$s = $dbw->selectRow( 'revision',
			array( 'rev_id', 'rev_timestamp' ),
			array(
				'rev_page' => $current->getPage(),
				"rev_user <> {$user} OR rev_user_text <> {$user_text}"
			), __METHOD__,
			array(
				'USE INDEX' => 'page_timestamp',
				'ORDER BY'  => 'rev_timestamp DESC' )
			);
		if( $s === false ) {
			# Something wrong
			$wgOut->setPageTitle(wfMsg('rollbackfailed'));
			$wgOut->addHTML( wfMsg( 'cantrollback' ) );
			return;
		}

		$set = array();
		if ( $bot ) {
			# Mark all reverted edits as bot
			$set['rc_bot'] = 1;
		}
		if ( $wgUseRCPatrol ) {
			# Mark all reverted edits as patrolled
			$set['rc_patrolled'] = 1;
		}

		if ( $set ) {
			$dbw->update( 'recentchanges', $set,
				array( /* WHERE */
					'rc_cur_id'    => $current->getPage(),
					'rc_user_text' => $current->getUserText(),
					"rc_timestamp > '{$s->rev_timestamp}'",
				), __METHOD__
			);
		}

		# Get the edit summary
		$target = Revision::newFromId( $s->rev_id );
		$newComment = wfMsgForContent( 'revertpage', $target->getUserText(), $from );
		$newComment = $wgRequest->getText( 'summary', $newComment );

		# Save it!
		$wgOut->setPagetitle( wfMsg( 'actioncomplete' ) );
		$wgOut->setRobotpolicy( 'noindex,nofollow' );
		$wgOut->addHTML( '<h2>' . htmlspecialchars( $newComment ) . "</h2>\n<hr />\n" );

		$this->updateArticle( $target->getText(), $newComment, 1, $this->mTitle->userIsWatching(), $bot );

		$wgOut->returnToMain( false );
	}


	/**
	 * Do standard deferred updates after page view
	 * @private
	 */
	function viewUpdates() {
		global $wgDeferredUpdateList;

		if ( 0 != $this->getID() ) {
			global $wgDisableCounters;
			if( !$wgDisableCounters ) {
				Article::incViewCount( $this->getID() );
				$u = new SiteStatsUpdate( 1, 0, 0 );
				array_push( $wgDeferredUpdateList, $u );
			}
		}

		# Update newtalk / watchlist notification status
		global $wgUser;
		$wgUser->clearNotification( $this->mTitle );
	}

	/**
	 * Do standard deferred updates after page edit.
	 * Update links tables, site stats, search index and message cache.
	 * Every 1000th edit, prune the recent changes table.
	 * 
	 * @private
	 * @param string $text
	 */
	function editUpdates( $text, $summary, $minoredit, $timestamp_of_pagechange, $newid) {
		global $wgDeferredUpdateList, $wgMessageCache, $wgUser, $wgParser;

		wfProfileIn( __METHOD__ );

		# Parse the text
		$options = new ParserOptions;
		$options->setTidy(true);
		$poutput = $wgParser->parse( $text, $this->mTitle, $options, true, true, $newid );

		# Save it to the parser cache
		$parserCache =& ParserCache::singleton();
		$parserCache->save( $poutput, $this, $wgUser );

		# Update the links tables
		$u = new LinksUpdate( $this->mTitle, $poutput );
		$u->doUpdate();

		if ( wfRunHooks( 'ArticleEditUpdatesDeleteFromRecentchanges', array( &$this ) ) ) {
			wfSeedRandom();
			if ( 0 == mt_rand( 0, 999 ) ) {
				# Periodically flush old entries from the recentchanges table.
				global $wgRCMaxAge;

				$dbw =& wfGetDB( DB_MASTER );
				$cutoff = $dbw->timestamp( time() - $wgRCMaxAge );
				$recentchanges = $dbw->tableName( 'recentchanges' );
				$sql = "DELETE FROM $recentchanges WHERE rc_timestamp < '{$cutoff}'";
				$dbw->query( $sql );
			}
		}

		$id = $this->getID();
		$title = $this->mTitle->getPrefixedDBkey();
		$shortTitle = $this->mTitle->getDBkey();

		if ( 0 == $id ) {
			wfProfileOut( __METHOD__ );
			return;
		}

		$u = new SiteStatsUpdate( 0, 1, $this->mGoodAdjustment, $this->mTotalAdjustment );
		array_push( $wgDeferredUpdateList, $u );
		$u = new SearchUpdate( $id, $title, $text );
		array_push( $wgDeferredUpdateList, $u );

		# If this is another user's talk page, update newtalk

		if ($this->mTitle->getNamespace() == NS_USER_TALK && $shortTitle != $wgUser->getName()) {
			if (wfRunHooks('ArticleEditUpdateNewTalk', array(&$this)) ) {
				$other = User::newFromName( $shortTitle );
				if( is_null( $other ) && User::isIP( $shortTitle ) ) {
					// An anonymous user
					$other = new User();
					$other->setName( $shortTitle );
				}
				if( $other ) {
					$other->setNewtalk( true );
				}
			}
		}

		if ( $this->mTitle->getNamespace() == NS_MEDIAWIKI ) {
			$wgMessageCache->replace( $shortTitle, $text );
		}

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Generate the navigation links when browsing through an article revisions
	 * It shows the information as:
	 *   Revision as of \<date\>; view current revision
	 *   \<- Previous version | Next Version -\>
	 *
	 * @private
	 * @param string $oldid		Revision ID of this article revision
	 */
	function setOldSubtitle( $oldid=0 ) {
		global $wgLang, $wgOut, $wgUser;

		$current = ( $oldid == $this->mLatest );
		$td = $wgLang->timeanddate( $this->mTimestamp, true );
		$sk = $wgUser->getSkin();
		$lnk = $current
			? wfMsg( 'currentrevisionlink' )
			: $lnk = $sk->makeKnownLinkObj( $this->mTitle, wfMsg( 'currentrevisionlink' ) );
		$prev = $this->mTitle->getPreviousRevisionID( $oldid ) ;
		$prevlink = $prev
			? $sk->makeKnownLinkObj( $this->mTitle, wfMsg( 'previousrevision' ), 'direction=prev&oldid='.$oldid )
			: wfMsg( 'previousrevision' );
		$nextlink = $current
			? wfMsg( 'nextrevision' )
			: $sk->makeKnownLinkObj( $this->mTitle, wfMsg( 'nextrevision' ), 'direction=next&oldid='.$oldid );
		$r = wfMsg( 'revisionasofwithlink', $td, $lnk, $prevlink, $nextlink );
		$wgOut->setSubtitle( $r );
	}

	/**
	 * This function is called right before saving the wikitext,
	 * so we can do things like signatures and links-in-context.
	 *
	 * @param string $text
	 */
	function preSaveTransform( $text ) {
		global $wgParser, $wgUser;
		return $wgParser->preSaveTransform( $text, $this->mTitle, $wgUser, ParserOptions::newFromUser( $wgUser ) );
	}

	/* Caching functions */

	/**
	 * checkLastModified returns true if it has taken care of all
	 * output to the client that is necessary for this request.
	 * (that is, it has sent a cached version of the page)
	 */
	function tryFileCache() {
		static $called = false;
		if( $called ) {
			wfDebug( " tryFileCache() -- called twice!?\n" );
			return;
		}
		$called = true;
		if($this->isFileCacheable()) {
			$touched = $this->mTouched;
			$cache = new CacheManager( $this->mTitle );
			if($cache->isFileCacheGood( $touched )) {
				wfDebug( " tryFileCache() - about to load\n" );
				$cache->loadFromFileCache();
				return true;
			} else {
				wfDebug( " tryFileCache() - starting buffer\n" );
				ob_start( array(&$cache, 'saveToFileCache' ) );
			}
		} else {
			wfDebug( " tryFileCache() - not cacheable\n" );
		}
	}

	/**
	 * Check if the page can be cached
	 * @return bool
	 */
	function isFileCacheable() {
		global $wgUser, $wgUseFileCache, $wgShowIPinHeader, $wgRequest;
		extract( $wgRequest->getValues( 'action', 'oldid', 'diff', 'redirect', 'printable' ) );

		return $wgUseFileCache
			and (!$wgShowIPinHeader)
			and ($this->getID() != 0)
			and ($wgUser->isAnon())
			and (!$wgUser->getNewtalk())
			and ($this->mTitle->getNamespace() != NS_SPECIAL )
			and (empty( $action ) || $action == 'view')
			and (!isset($oldid))
			and (!isset($diff))
			and (!isset($redirect))
			and (!isset($printable))
			and (!$this->mRedirectedFrom);
	}

	/**
	 * Loads page_touched and returns a value indicating if it should be used
	 *
	 */
	function checkTouched() {
		if( !$this->mDataLoaded ) {
			$this->loadPageData();
		}
		return !$this->mIsRedirect;
	}

	/**
	 * Get the page_touched field
	 */
	function getTouched() {
		# Ensure that page data has been loaded
		if( !$this->mDataLoaded ) {
			$this->loadPageData();
		}
		return $this->mTouched;
	}

	/**
	 * Get the page_latest field
	 */
	function getLatest() {
		if ( !$this->mDataLoaded ) {
			$this->loadPageData();
		}
		return $this->mLatest;
	}

	/**
	 * Edit an article without doing all that other stuff
	 * The article must already exist; link tables etc
	 * are not updated, caches are not flushed.
	 *
	 * @param string $text text submitted
	 * @param string $comment comment submitted
	 * @param bool $minor whereas it's a minor modification
	 */
	function quickEdit( $text, $comment = '', $minor = 0 ) {
		wfProfileIn( __METHOD__ );

		$dbw =& wfGetDB( DB_MASTER );
		$dbw->begin();
		$revision = new Revision( array(
			'page'       => $this->getId(),
			'text'       => $text,
			'comment'    => $comment,
			'minor_edit' => $minor ? 1 : 0,
			) );
		# fixme : $revisionId never used
		$revisionId = $revision->insertOn( $dbw );
		$this->updateRevisionOn( $dbw, $revision );
		$dbw->commit();

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Used to increment the view counter
	 *
	 * @static
	 * @param integer $id article id
	 */
	function incViewCount( $id ) {
		$id = intval( $id );
		global $wgHitcounterUpdateFreq, $wgDBtype;

		$dbw =& wfGetDB( DB_MASTER );
		$pageTable = $dbw->tableName( 'page' );
		$hitcounterTable = $dbw->tableName( 'hitcounter' );
		$acchitsTable = $dbw->tableName( 'acchits' );

		if( $wgHitcounterUpdateFreq <= 1 ){ //
			$dbw->query( "UPDATE $pageTable SET page_counter = page_counter + 1 WHERE page_id = $id" );
			return;
		}

		# Not important enough to warrant an error page in case of failure
		$oldignore = $dbw->ignoreErrors( true );

		$dbw->query( "INSERT INTO $hitcounterTable (hc_id) VALUES ({$id})" );

		$checkfreq = intval( $wgHitcounterUpdateFreq/25 + 1 );
		if( (rand() % $checkfreq != 0) or ($dbw->lastErrno() != 0) ){
			# Most of the time (or on SQL errors), skip row count check
			$dbw->ignoreErrors( $oldignore );
			return;
		}

		$res = $dbw->query("SELECT COUNT(*) as n FROM $hitcounterTable");
		$row = $dbw->fetchObject( $res );
		$rown = intval( $row->n );
		if( $rown >= $wgHitcounterUpdateFreq ){
			wfProfileIn( 'Article::incViewCount-collect' );
			$old_user_abort = ignore_user_abort( true );

			if ($wgDBtype == 'mysql')
				$dbw->query("LOCK TABLES $hitcounterTable WRITE");
			$tabletype = $wgDBtype == 'mysql' ? "ENGINE=HEAP " : '';
			$dbw->query("CREATE TEMPORARY TABLE $acchitsTable $tabletype".
				"SELECT hc_id,COUNT(*) AS hc_n FROM $hitcounterTable ".
				'GROUP BY hc_id');
			$dbw->query("DELETE FROM $hitcounterTable");
			if ($wgDBtype == 'mysql')
				$dbw->query('UNLOCK TABLES');
			$dbw->query("UPDATE $pageTable,$acchitsTable SET page_counter=page_counter + hc_n ".
				'WHERE page_id = hc_id');
			$dbw->query("DROP TABLE $acchitsTable");

			ignore_user_abort( $old_user_abort );
			wfProfileOut( 'Article::incViewCount-collect' );
		}
		$dbw->ignoreErrors( $oldignore );
	}

	/**#@+
	 * The onArticle*() functions are supposed to be a kind of hooks
	 * which should be called whenever any of the specified actions
	 * are done.
	 *
	 * This is a good place to put code to clear caches, for instance.
	 *
	 * This is called on page move and undelete, as well as edit
	 * @static
	 * @param $title_obj a title object
	 */

	static function onArticleCreate($title) {
		# The talk page isn't in the regular link tables, so we need to update manually:
		if ( $title->isTalkPage() ) {
			$other = $title->getSubjectPage();
		} else {
			$other = $title->getTalkPage();
		}
		$other->invalidateCache();
		$other->purgeSquid();

		$title->touchLinks();
		$title->purgeSquid();
	}

	static function onArticleDelete( $title ) {
		global $wgUseFileCache, $wgMessageCache;

		$title->touchLinks();
		$title->purgeSquid();
		
		# File cache
		if ( $wgUseFileCache ) {
			$cm = new CacheManager( $title );
			@unlink( $cm->fileCacheName() );
		}

		if( $title->getNamespace() == NS_MEDIAWIKI) {
			$wgMessageCache->replace( $title->getDBkey(), false );
		}
	}

	/**
	 * Purge caches on page update etc
	 */
	static function onArticleEdit( $title ) {
		global $wgDeferredUpdateList, $wgUseFileCache;

		$urls = array();

		// Invalidate caches of articles which include this page
		$update = new HTMLCacheUpdate( $title, 'templatelinks' );
		$wgDeferredUpdateList[] = $update;

		# Purge squid for this page only
		$title->purgeSquid();

		# Clear file cache
		if ( $wgUseFileCache ) {
			$cm = new CacheManager( $title );
			@unlink( $cm->fileCacheName() );
		}
	}

	/**#@-*/

	/**
	 * Info about this page
	 * Called for ?action=info when $wgAllowPageInfo is on.
	 *
	 * @public
	 */
	function info() {
		global $wgLang, $wgOut, $wgAllowPageInfo, $wgUser;

		if ( !$wgAllowPageInfo ) {
			$wgOut->showErrorPage( 'nosuchaction', 'nosuchactiontext' );
			return;
		}

		$page = $this->mTitle->getSubjectPage();

		$wgOut->setPagetitle( $page->getPrefixedText() );
		$wgOut->setSubtitle( wfMsg( 'infosubtitle' ));

		# first, see if the page exists at all.
		$exists = $page->getArticleId() != 0;
		if( !$exists ) {
			if ( $this->mTitle->getNamespace() == NS_MEDIAWIKI ) {
				$wgOut->addHTML(wfMsgWeirdKey ( $this->mTitle->getText() ) );
			} else {
				$wgOut->addHTML(wfMsg( $wgUser->isLoggedIn() ? 'noarticletext' : 'noarticletextanon' ) );
			}
		} else {
			$dbr =& wfGetDB( DB_SLAVE );
			$wl_clause = array(
				'wl_title'     => $page->getDBkey(),
				'wl_namespace' => $page->getNamespace() );
			$numwatchers = $dbr->selectField(
				'watchlist',
				'COUNT(*)',
				$wl_clause,
				__METHOD__,
				$this->getSelectOptions() );

			$pageInfo = $this->pageCountInfo( $page );
			$talkInfo = $this->pageCountInfo( $page->getTalkPage() );

			$wgOut->addHTML( "<ul><li>" . wfMsg("numwatchers", $wgLang->formatNum( $numwatchers ) ) . '</li>' );
			$wgOut->addHTML( "<li>" . wfMsg('numedits', $wgLang->formatNum( $pageInfo['edits'] ) ) . '</li>');
			if( $talkInfo ) {
				$wgOut->addHTML( '<li>' . wfMsg("numtalkedits", $wgLang->formatNum( $talkInfo['edits'] ) ) . '</li>');
			}
			$wgOut->addHTML( '<li>' . wfMsg("numauthors", $wgLang->formatNum( $pageInfo['authors'] ) ) . '</li>' );
			if( $talkInfo ) {
				$wgOut->addHTML( '<li>' . wfMsg('numtalkauthors', $wgLang->formatNum( $talkInfo['authors'] ) ) . '</li>' );
			}
			$wgOut->addHTML( '</ul>' );

		}
	}

	/**
	 * Return the total number of edits and number of unique editors
	 * on a given page. If page does not exist, returns false.
	 *
	 * @param Title $title
	 * @return array
	 * @private
	 */
	function pageCountInfo( $title ) {
		$id = $title->getArticleId();
		if( $id == 0 ) {
			return false;
		}

		$dbr =& wfGetDB( DB_SLAVE );

		$rev_clause = array( 'rev_page' => $id );

		$edits = $dbr->selectField(
			'revision',
			'COUNT(rev_page)',
			$rev_clause,
			__METHOD__,
			$this->getSelectOptions() );

		$authors = $dbr->selectField(
			'revision',
			'COUNT(DISTINCT rev_user_text)',
			$rev_clause,
			__METHOD__,
			$this->getSelectOptions() );

		return array( 'edits' => $edits, 'authors' => $authors );
	}

	/**
	 * Return a list of templates used by this article.
	 * Uses the templatelinks table
	 *
	 * @return array Array of Title objects
	 */
	function getUsedTemplates() {
		$result = array();
		$id = $this->mTitle->getArticleID();
		if( $id == 0 ) {
			return array();
		}

		$dbr =& wfGetDB( DB_SLAVE );
		$res = $dbr->select( array( 'templatelinks' ),
			array( 'tl_namespace', 'tl_title' ),
			array( 'tl_from' => $id ),
			'Article:getUsedTemplates' );
		if ( false !== $res ) {
			if ( $dbr->numRows( $res ) ) {
				while ( $row = $dbr->fetchObject( $res ) ) {
					$result[] = Title::makeTitle( $row->tl_namespace, $row->tl_title );
				}
			}
		}
		$dbr->freeResult( $res );
		return $result;
	}
}

?>
