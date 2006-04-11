<?php
/**
 * File for articles
 * @package MediaWiki
 */

/**
 * Need the CacheManager to be loaded
 */
require_once( 'CacheManager.php' );
require_once( 'Revision.php' );

$wgArticleCurContentFields = false;
$wgArticleOldContentFields = false;

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
	/**#@+
	 * @access private
	 */
	var $mContent, $mContentLoaded;
	var $mUser, $mTimestamp, $mUserText;
	var $mCounter, $mComment, $mGoodAdjustment, $mTotalAdjustment;
	var $mMinorEdit, $mRedirectedFrom;
	var $mTouched, $mFileCache, $mTitle;
	var $mId, $mTable;
	var $mForUpdate;
	var $mOldId;
	var $mRevIdFetched;
	var $mRevision;
	var $mRedirectUrl;
	var $mLatest;
	/**#@-*/

	/**
	 * Constructor and clear the article
	 * @param Title &$title
	 * @param integer $oldId Revision ID, null to fetch from request, zero for current
	 */
	function Article( &$title, $oldId = null ) {
		$this->mTitle =& $title;
		$this->mOldId = $oldId;
		$this->clear();
	}
	
	/**
	 * Tell the page view functions that this view was redirected
	 * from another page on the wiki.
	 * @param Title $from
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
	  * @access private
	  */
	function clear() {
		$this->mDataLoaded    = false;
		$this->mContentLoaded = false;

		$this->mCurID = $this->mUser = $this->mCounter = -1; # Not loaded
		$this->mRedirectedFrom = null; # Title object if set
		$this->mUserText =
		$this->mTimestamp = $this->mComment = $this->mFileCache = '';
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
	 *
	 * @fixme There are still side-effects in this!
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

		$fname =  'Article::getContent';
		wfProfileIn( $fname );

		if ( 0 == $this->getID() ) {
			if ( 'edit' == $action ) {
				wfProfileOut( $fname );

				# If requested, preload some text.
				$text=$this->getPreloadedText($preload);

				# We used to put MediaWiki:Newarticletext here if
				# $text was empty at this point.
				# This is now shown above the edit box instead.
				return $text;
			}
			wfProfileOut( $fname );
			$wgOut->setRobotpolicy( 'noindex,nofollow' );

			if ( $this->mTitle->getNamespace() == NS_MEDIAWIKI ) {
				$ret = wfMsgWeirdKey ( $this->mTitle->getText() ) ;
			} else {
				$ret = wfMsg( $wgUser->isLoggedIn() ? 'noarticletext' : 'noarticletextanon' );
			}

			return "<div class='noarticletext'>$ret</div>";
		} else {
			$this->loadContent();
			# check if we're displaying a [[User talk:x.x.x.x]] anonymous talk page
			if ( $this->mTitle->getNamespace() == NS_USER_TALK &&
			  $wgUser->isIP($this->mTitle->getText()) &&
			  $action=='view'
			) {
				wfProfileOut( $fname );
				return $this->mContent . "\n" .wfMsg('anontalkpagetext');
			} else {
				if($action=='edit') {
					if($section!='') {
						if($section=='new') {
							wfProfileOut( $fname );
							$text=$this->getPreloadedText($preload);
							return $text;
						}

						# strip NOWIKI etc. to avoid confusion (true-parameter causes HTML
						# comments to be stripped as well)
						$rv=$this->getSection($this->mContent,$section);
						wfProfileOut( $fname );
						return $rv;
					}
				}
				wfProfileOut( $fname );
				return $this->mContent;
			}
		}
	}

	/**
	 * Get the contents of a page from its title and remove includeonly tags
	 *
	 * @param string The title of the page
	 * @return string The contents of the page
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
	 * A section is text under a heading like == Heading == or <h1>Heading</h1>, or
	 * the first section before any such heading (section 0).
	 *
	 * If a section contains subsections, these are also returned.
	 *
	 * @param string $text text to look in
	 * @param integer $section section number
	 * @return string text of the requested section
	 */
	function getSection($text,$section) {

		# strip NOWIKI etc. to avoid confusion (true-parameter causes HTML
		# comments to be stripped as well)
		$striparray=array();
		$parser=new Parser();
		$parser->mOutputType=OT_WIKI;
		$parser->mOptions = new ParserOptions();
		$striptext=$parser->strip($text, $striparray, true);

		# now that we can be sure that no pseudo-sections are in the source,
		# split it up by section
		$secs =
		  preg_split(
		  '/(^=+.+?=+|^<h[1-6].*?>.*?<\/h[1-6].*?>)(?!\S)/mi',
		  $striptext, -1,
		  PREG_SPLIT_DELIM_CAPTURE);
		if($section==0) {
			$rv=$secs[0];
		} else {
			$headline=$secs[$section*2-1];
			preg_match( '/^(=+).+?=+|^<h([1-6]).*?>.*?<\/h[1-6].*?>(?!\S)/mi',$headline,$matches);
			$hlevel=$matches[1];

			# translate wiki heading into level
			if(strpos($hlevel,'=')!==false) {
				$hlevel=strlen($hlevel);
			}

			$rv=$headline. $secs[$section*2];
			$count=$section+1;

			$break=false;
			while(!empty($secs[$count*2-1]) && !$break) {

				$subheadline=$secs[$count*2-1];
				preg_match( '/^(=+).+?=+|^<h([1-6]).*?>.*?<\/h[1-6].*?>(?!\S)/mi',$subheadline,$matches);
				$subhlevel=$matches[1];
				if(strpos($subhlevel,'=')!==false) {
					$subhlevel=strlen($subhlevel);
				}
				if($subhlevel > $hlevel) {
					$rv.=$subheadline.$secs[$count*2];
				}
				if($subhlevel <= $hlevel) {
					$break=true;
				}
				$count++;

			}
		}
		# reinsert stripped tags
		$rv=$parser->unstrip($rv,$striparray);
		$rv=$parser->unstripNoWiki($rv,$striparray);
		$rv=trim($rv);
		return $rv;

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
			$lastid = $oldid;
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

		$fname = 'Article::loadContent';

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
	 * @access private
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
	 * @access private
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
		$fname = 'Article::fetchContent';

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
				wfDebug( "$fname failed to retrieve specified revision, id $oldid\n" );
				return false;
			}
			$data = $this->pageDataFromId( $dbr, $revision->getPage() );
			if( !$data ) {
				wfDebug( "$fname failed to get page data linked to revision id $oldid\n" );
				return false;
			}
			$this->mTitle = Title::makeTitle( $data->page_namespace, $data->page_title );
			$this->loadPageData( $data );
		} else {
			if( !$this->mDataLoaded ) {
				$data = $this->pageDataFromTitle( $dbr, $this->mTitle );
				if( !$data ) {
					wfDebug( "$fname failed to find page data for title " . $this->mTitle->getPrefixedText() . "\n" );
					return false;
				}
				$this->loadPageData( $data );
			}
			$revision = Revision::newFromId( $this->mLatest );
			if( is_null( $revision ) ) {
				wfDebug( "$fname failed to retrieve current page, rev_id {$data->page_latest}\n" );
				return false;
			}
		}

		// FIXME: Horrible, horrible! This content-loading interface just plain sucks.
		// We should instead work with the Revision object when we need it...
		$this->mContent = $revision->userCan( MW_REV_DELETED_TEXT ) ? $revision->getRawText() : "";
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
	 * @param mixed $x
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
	 * @param array $options an optional options array which'll be appended to
	 *                       the default
	 * @return array Options
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
	 * @param string $text Text to analyze
	 * @return bool
	 */
	function isCountable( $text ) {
		global $wgUseCommaCount;

		$token = $wgUseCommaCount ? ',' : '[[';
		return
			$this->mTitle->getNamespace() == NS_MAIN
			&& ! $this->isRedirect( $text )
			&& in_string( $token, $text );
	}

	/**
	 * Tests if the article text represents a redirect
	 *
	 * @param string $text
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
	 * @access private
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

	function getContributors($limit = 0, $offset = 0) {
		$fname = 'Article::getContributors';

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

		$res = $dbr->query($sql, $fname);

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
		global $wgUseTrackbacks;
		$sk = $wgUser->getSkin();

		$fname = 'Article::view';
		wfProfileIn( $fname );
		$parserCache =& ParserCache::singleton();
		# Get variables from query string
		$oldid = $this->getOldID();

		# getOldID may want us to redirect somewhere else
		if ( $this->mRedirectUrl ) {
			$wgOut->redirect( $this->mRedirectUrl );
			wfProfileOut( $fname );
			return;
		}

		$diff = $wgRequest->getVal( 'diff' );
		$rcid = $wgRequest->getVal( 'rcid' );
		$rdfrom = $wgRequest->getVal( 'rdfrom' );

		$wgOut->setArticleFlag( true );
		$wgOut->setRobotpolicy( 'index,follow' );
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
			wfProfileOut( $fname );
			return;
		}
		
		if ( empty( $oldid ) && $this->checkTouched() ) {
			$wgOut->setETag($parserCache->getETag($this, $wgUser));

			if( $wgOut->checkLastModified( $this->mTouched ) ){
				wfProfileOut( $fname );
				return;
			} else if ( $this->tryFileCache() ) {
				# tell wgOut that output is taken care of
				$wgOut->disable();
				$this->viewUpdates();
				wfProfileOut( $fname );
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
					if( $this->mRevision->isDeleted( MW_REV_DELETED_TEXT ) ) {
						if( !$this->mRevision->userCan( MW_REV_DELETED_TEXT ) ) {
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
				$this->mTitle->getNamespace() == NS_USER &&
				preg_match('/\\/[\\w]+\\.(css|js)$/', $this->mTitle->getDBkey())
			) {
				$wgOut->addWikiText( wfMsg('clearyourcache'));
				$wgOut->addHTML( '<pre>'.htmlspecialchars($this->mContent)."\n</pre>" );
			} else if ( $rt = Title::newFromRedirect( $text ) ) {
				# Display redirect
				$imageDir = $wgContLang->isRTL() ? 'rtl' : 'ltr';
				$imageUrl = $wgStylePath.'/common/images/redirect' . $imageDir . '.png';
				if( !$wasRedirected ) {
					$wgOut->setSubtitle( wfMsgHtml( 'redirectpagesub' ) );
				}
				$targetUrl = $rt->escapeLocalURL();
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
		wfProfileOut( $fname );
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
			if ($wgUser->isSysop()) {
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
	 * @access private
	 */
	function insertOn( &$dbw, $restrictions = '' ) {
		$fname = 'Article::insertOn';
		wfProfileIn( $fname );

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
		), $fname );
		$newid = $dbw->insertId();

		$this->mTitle->resetArticleId( $newid );

		wfProfileOut( $fname );
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
	 * @access private
	 */
	function updateRevisionOn( &$dbw, $revision, $lastRevision = null ) {
		$fname = 'Article::updateToRevision';
		wfProfileIn( $fname );

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
			$fname );

		wfProfileOut( $fname );
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
		$fname = 'Article::updateIfNewerOn';
		wfProfileIn( $fname );

		$row = $dbw->selectRow(
			array( 'revision', 'page' ),
			array( 'rev_id', 'rev_timestamp' ),
			array(
				'page_id' => $this->getId(),
				'page_latest=rev_id' ),
			$fname );
		if( $row ) {
			if( wfTimestamp(TS_MW, $row->rev_timestamp) >= $revision->getTimestamp() ) {
				wfProfileOut( $fname );
				return false;
			}
			$prev = $row->rev_id;
		} else {
			# No or missing previous revision; mark the page as new
			$prev = 0;
		}

		$ret = $this->updateRevisionOn( $dbw, $revision, $prev );
		wfProfileOut( $fname );
		return $ret;
	}

	/**
	 * Insert a new article into the database
	 * @access private
	 */
	function insertNewArticle( $text, $summary, $isminor, $watchthis, $suppressRC=false, $comment=false ) {
		global $wgUser;

		$fname = 'Article::insertNewArticle';
		wfProfileIn( $fname );

		if( !wfRunHooks( 'ArticleSave', array( &$this, &$wgUser, &$text,
			&$summary, &$isminor, &$watchthis, NULL ) ) ) {
			wfDebug( "$fname: ArticleSave hook aborted save!\n" );
			wfProfileOut( $fname );
			return false;
		}

		$this->mGoodAdjustment = (int)$this->isCountable( $text );
		$this->mTotalAdjustment = 1;

		$ns = $this->mTitle->getNamespace();
		$ttl = $this->mTitle->getDBkey();

		# If this is a comment, add the summary as headline
		if($comment && $summary!="") {
			$text="== {$summary} ==\n\n".$text;
		}
		$text = $this->preSaveTransform( $text );

		/* Silently ignore minoredit if not allowed */
		$isminor = $isminor && $wgUser->isAllowed('minoredit');
		$now = wfTimestampNow();

		$dbw =& wfGetDB( DB_MASTER );

		# Add the page record; stake our claim on this title!
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

		Article::onArticleCreate( $this->mTitle );
		if(!$suppressRC) {
			require_once( 'RecentChange.php' );
			$rcid = RecentChange::notifyNew( $now, $this->mTitle, $isminor, $wgUser, $summary, 'default',
			  '', strlen( $text ), $revisionId );
			# Mark as patrolled if the user can and has the option set
			if( $wgUser->isAllowed( 'patrol' ) && $wgUser->getOption( 'autopatrol' ) ) {
				RecentChange::markPatrolled( $rcid );
			}
		}

		if ($watchthis) {
			if(!$this->mTitle->userIsWatching()) $this->doWatch();
		} else {
			if ( $this->mTitle->userIsWatching() ) {
				$this->doUnwatch();
			}
		}

		# The talk page isn't in the regular link tables, so we need to update manually:
		$talkns = $ns ^ 1; # talk -> normal; normal -> talk
		$dbw->update( 'page',
			array( 'page_touched' => $dbw->timestamp($now) ),
			array( 'page_namespace' => $talkns,
			       'page_title' => $ttl ),
			$fname );

		# standard deferred updates
		$this->editUpdates( $text, $summary, $isminor, $now, $revisionId );

		$oldid = 0; # new article
		$this->showArticle( $text, wfMsg( 'newarticle' ), false, $isminor, $now, $summary, $oldid );

		wfRunHooks( 'ArticleInsertComplete', array( &$this, &$wgUser, $text,
			$summary, $isminor,
			$watchthis, NULL ) );
		wfRunHooks( 'ArticleSaveComplete', array( &$this, &$wgUser, $text,
			$summary, $isminor,
			$watchthis, NULL ) );
		wfProfileOut( $fname );
	}

	function getTextOfLastEditWithSectionReplacedOrAdded($section, $text, $summary = '', $edittime = NULL) {
		$this->replaceSection( $section, $text, $summary, $edittime );
	}

	/**
	 * @return string Complete article text, or null if error
	 */
	function replaceSection($section, $text, $summary = '', $edittime = NULL) {
		$fname = 'Article::replaceSection';
		wfProfileIn( $fname );

		if ($section != '') {
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

				# strip NOWIKI etc. to avoid confusion (true-parameter causes HTML
				# comments to be stripped as well)
				$striparray=array();
				$parser=new Parser();
				$parser->mOutputType=OT_WIKI;
				$parser->mOptions = new ParserOptions();
				$oldtext=$parser->strip($oldtext, $striparray, true);

				# now that we can be sure that no pseudo-sections are in the source,
				# split it up
				# Unfortunately we can't simply do a preg_replace because that might
				# replace the wrong section, so we have to use the section counter instead
				$secs=preg_split('/(^=+.+?=+|^<h[1-6].*?>.*?<\/h[1-6].*?>)(?!\S)/mi',
				  $oldtext,-1,PREG_SPLIT_DELIM_CAPTURE);
				$secs[$section*2]=$text."\n\n"; // replace with edited

				# section 0 is top (intro) section
				if($section!=0) {

					# headline of old section - we need to go through this section
					# to determine if there are any subsections that now need to
					# be erased, as the mother section has been replaced with
					# the text of all subsections.
					$headline=$secs[$section*2-1];
					preg_match( '/^(=+).+?=+|^<h([1-6]).*?>.*?<\/h[1-6].*?>(?!\S)/mi',$headline,$matches);
					$hlevel=$matches[1];

					# determine headline level for wikimarkup headings
					if(strpos($hlevel,'=')!==false) {
						$hlevel=strlen($hlevel);
					}

					$secs[$section*2-1]=''; // erase old headline
					$count=$section+1;
					$break=false;
					while(!empty($secs[$count*2-1]) && !$break) {

						$subheadline=$secs[$count*2-1];
						preg_match(
						 '/^(=+).+?=+|^<h([1-6]).*?>.*?<\/h[1-6].*?>(?!\S)/mi',$subheadline,$matches);
						$subhlevel=$matches[1];
						if(strpos($subhlevel,'=')!==false) {
							$subhlevel=strlen($subhlevel);
						}
						if($subhlevel > $hlevel) {
							// erase old subsections
							$secs[$count*2-1]='';
							$secs[$count*2]='';
						}
						if($subhlevel <= $hlevel) {
							$break=true;
						}
						$count++;

					}

				}
				$text=join('',$secs);
				# reinsert the stuff that we stripped out earlier
				$text=$parser->unstrip($text,$striparray);
				$text=$parser->unstripNoWiki($text,$striparray);
			}

		}
		wfProfileOut( $fname );
		return $text;
	}

	/**
	 * Change an existing article. Puts the previous version back into the old table, updates RC
	 * and all necessary caches, mostly via the deferred update array.
	 *
	 * It is possible to call this function from a command-line script, but note that you should
	 * first set $wgUser, and clean up $wgDeferredUpdates after each edit.
	 */
	function updateArticle( $text, $summary, $minor, $watchthis, $forceBot = false, $sectionanchor = '' ) {
		global $wgUser, $wgDBtransactions, $wgUseSquid;
		global $wgPostCommitUpdateList, $wgUseFileCache;

		$fname = 'Article::updateArticle';
		wfProfileIn( $fname );
		$good = true;

		if( !wfRunHooks( 'ArticleSave', array( &$this, &$wgUser, &$text,
			&$summary, &$minor,
			&$watchthis, &$sectionanchor ) ) ) {
			wfDebug( "$fname: ArticleSave hook aborted save!\n" );
			wfProfileOut( $fname );
			return false;
		}

		$isminor = $minor && $wgUser->isAllowed('minoredit');
		$redir = (int)$this->isRedirect( $text );

		$text = $this->preSaveTransform( $text );
		$dbw =& wfGetDB( DB_MASTER );
		$now = wfTimestampNow();

		# Update article, but only if changed.

		# It's important that we either rollback or complete, otherwise an attacker could
		# overwrite cur entries by sending precisely timed user aborts. Random bored users
		# could conceivably have the same effect, especially if cur is locked for long periods.
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
			$now = wfTimestampNow();

			$lastRevision = $dbw->selectField(
				'page', 'page_latest', array( 'page_id' => $this->getId() ) );

			$revision = new Revision( array(
				'page'       => $this->getId(),
				'comment'    => $summary,
				'minor_edit' => $isminor,
				'text'       => $text
				) );

			$dbw->immediateCommit();
			$dbw->begin();
			$revisionId = $revision->insertOn( $dbw );

			# Update page
			$ok = $this->updateRevisionOn( $dbw, $revision, $lastRevision );

			if( !$ok ) {
				/* Belated edit conflict! Run away!! */
				$good = false;
				$dbw->rollback();
			} else {
				# Update recentchanges and purge cache and whatnot
				require_once( 'RecentChange.php' );
				$bot = (int)($wgUser->isBot() || $forceBot);
				$rcid = RecentChange::notifyEdit( $now, $this->mTitle, $isminor, $wgUser, $summary,
					$lastRevision, $this->getTimestamp(), $bot, '', $oldsize, $newsize,
					$revisionId );
					
				# Mark as patrolled if the user can do so and has it set in their options
				if( $wgUser->isAllowed( 'patrol' ) && $wgUser->getOption( 'autopatrol' ) ) {
					RecentChange::markPatrolled( $rcid );
				}
					
				$dbw->commit();

				// Update caches outside the main transaction
				Article::onArticleEdit( $this->mTitle );
			}
		} else {
			// Keep the same revision ID, but do some updates on it
			$revisionId = $this->getRevIdFetched();
		}

		if( !$wgDBtransactions ) {
			ignore_user_abort( $userAbort );
		}

		if ( $good ) {
			if ($watchthis) {
				if (!$this->mTitle->userIsWatching()) {
					$dbw->immediateCommit();
					$dbw->begin();
					$this->doWatch();
					$dbw->commit();
				}
			} else {
				if ( $this->mTitle->userIsWatching() ) {
					$dbw->immediateCommit();
					$dbw->begin();
					$this->doUnwatch();
					$dbw->commit();
				}
			}
			# standard deferred updates
			$this->editUpdates( $text, $summary, $minor, $now, $revisionId );


			$urls = array();
			# Invalidate caches of all articles using this article as a template

			# Template namespace
			# Purge all articles linking here
			$titles = $this->mTitle->getTemplateLinksTo();
			Title::touchArray( $titles );
			if ( $wgUseSquid ) {
					foreach ( $titles as $title ) {
						$urls[] = $title->getInternalURL();
					}
			}

			# Squid updates
			if ( $wgUseSquid ) {
				$urls = array_merge( $urls, $this->mTitle->getSquidURLs() );
				$u = new SquidUpdate( $urls );
				array_push( $wgPostCommitUpdateList, $u );
			}

			# File cache
			if ( $wgUseFileCache ) {
				$cm = new CacheManager($this->mTitle);
				@unlink($cm->fileCacheName());
			}

			$this->showArticle( $text, wfMsg( 'updated' ), $sectionanchor, $isminor, $now, $summary, $lastRevision );
		}
		wfRunHooks( 'ArticleSaveComplete',
			array( &$this, &$wgUser, $text,
			$summary, $minor,
			$watchthis, $sectionanchor ) );
		wfProfileOut( $fname );
		return $good;
	}

	/**
	 * After we've either updated or inserted the article, update
	 * the link tables and redirect to the new page.
	 */
	function showArticle( $text, $subtitle , $sectionanchor = '', $me2, $now, $summary, $oldid ) {
		global $wgOut;

		$fname = 'Article::showArticle';
		wfProfileIn( $fname );

		# Output the redirect
		if( $this->isRedirect( $text ) )
			$r = 'redirect=no';
		else
			$r = '';
		$wgOut->redirect( $this->mTitle->getFullURL( $r ).$sectionanchor );

		wfProfileOut( $fname );
	}

	/**
	 * Mark this particular edit as patrolled
	 */
	function markpatrolled() {
		global $wgOut, $wgRequest, $wgUseRCPatrol, $wgUser;
		$wgOut->setRobotpolicy( 'noindex,follow' );

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
				require_once( 'RecentChange.php' );
				RecentChange::markPatrolled( $rcid );
				wfRunHooks( 'MarkPatrolledComplete', array( &$rcid, &$wgUser, false ) );
				$wgOut->setPagetitle( wfMsg( 'markedaspatrolled' ) );
				$wgOut->addWikiText( wfMsg( 'markedaspatrolledtext' ) );
			}
			$rcTitle = Title::makeTitle( NS_SPECIAL, 'Recentchanges' );
			$wgOut->returnToMain( false, $rcTitle->getPrefixedText() );
		}
		else {
			$wgOut->errorpage( 'markedaspatrollederror', 'markedaspatrollederrortext' );
		}
	}

	/**
	 * User-interface handler for the "watch" action
	 */

	function watch() {

		global $wgUser, $wgOut;

		if ( $wgUser->isAnon() ) {
			$wgOut->errorpage( 'watchnologin', 'watchnologintext' );
			return;
		}
		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}
		
		if( $this->doWatch() ) {
			$wgOut->setPagetitle( wfMsg( 'addedwatch' ) );
			$wgOut->setRobotpolicy( 'noindex,follow' );

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
			$wgOut->errorpage( 'watchnologin', 'watchnologintext' );
			return;
		}
		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}
		
		if( $this->doUnwatch() ) {
			$wgOut->setPagetitle( wfMsg( 'removedwatch' ) );
			$wgOut->setRobotpolicy( 'noindex,follow' );

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
		global $wgUser;

		if ( !$wgUser->isAllowed( 'protect' ) ) {
			return false;
		}

		if( wfReadOnly() ) {
			return false;
		}

		$id = $this->mTitle->getArticleID();
		if ( 0 == $id ) {
			return false;
		}

		$flat = Article::flattenRestrictions( $limit );
		$protecting = ($flat != '');

		if( wfRunHooks( 'ArticleProtect', array( &$this, &$wgUser,
			$limit, $reason ) ) ) {

			$dbw =& wfGetDB( DB_MASTER );
			$dbw->update( 'page',
				array( /* SET */
					'page_touched' => $dbw->timestamp(),
					'page_restrictions' => $flat
				), array( /* WHERE */
					'page_id' => $id
				), 'Article::protect'
			);

			wfRunHooks( 'ArticleProtectComplete', array( &$this, &$wgUser,
				$limit, $reason ) );

			$log = new LogPage( 'protect' );
			if( $protecting ) {
				$log->addEntry( 'protect', $this->mTitle, trim( $reason . " [$flat]" ) );
			} else {
				$log->addEntry( 'unprotect', $this->mTitle, $reason );
			}
		}
		return true;
	}

	/**
	 * Take an array of page restrictions and flatten it to a string
	 * suitable for insertion into the page_restrictions field.
	 * @param array $limit
	 * @return string
	 * @access private
	 */
	function flattenRestrictions( $limit ) {
		if( !is_array( $limit ) ) {
			wfDebugDieBacktrace( 'Article::flattenRestrictions given non-array restriction set' );
		}
		$bits = array();
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
		$fname = 'Article::delete';
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
		$latest = $dbw->selectField( 'page', 'page_latest', $conds, $fname );
		if ( $latest === false ) {
			$wgOut->fatalError( wfMsg( 'cannotdelete' ) );
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
		$fname = 'Article::getLastNAuthors';
		wfProfileIn( $fname );

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
				), $fname, $this->getSelectOptions( array(
					'ORDER BY' => 'rev_timestamp DESC',
					'LIMIT' => $num
				) )
			);
			if ( !$res ) {
				wfProfileOut( $fname );
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
		wfProfileOut( $fname );
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
		$fname = 'Article::doDelete';
		wfDebug( $fname."\n" );

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
				$wgOut->fatalError( wfMsg( 'cannotdelete' ) );
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

		$fname = 'Article::doDeleteArticle';
		wfDebug( $fname."\n" );

		$dbw =& wfGetDB( DB_MASTER );
		$ns = $this->mTitle->getNamespace();
		$t = $this->mTitle->getDBkey();
		$id = $this->mTitle->getArticleID();

		if ( $t == '' || $id == 0 ) {
			return false;
		}

		$u = new SiteStatsUpdate( 0, 1, -(int)$this->isCountable( $this->getContent() ), -1 );
		array_push( $wgDeferredUpdateList, $u );

		$linksTo = $this->mTitle->getLinksTo();

		# Squid purging
		if ( $wgUseSquid ) {
			$urls = array(
				$this->mTitle->getInternalURL(),
				$this->mTitle->getInternalURL( 'history' )
			);

			$u = SquidUpdate::newFromTitles( $linksTo, $urls );
			array_push( $wgPostCommitUpdateList, $u );

		}

		# Client and file cache invalidation
		Title::touchArray( $linksTo );


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
			), $fname
		);

		# Now that it's safely backed up, delete it
		$dbw->delete( 'revision', array( 'rev_page' => $id ), $fname );
		$dbw->delete( 'page', array( 'page_id' => $id ), $fname);

		if ($wgUseTrackbacks)
			$dbw->delete( 'trackbacks', array( 'tb_page' => $id ), $fname );

 		# Clean up recentchanges entries...
		$dbw->delete( 'recentchanges', array( 'rc_namespace' => $ns, 'rc_title' => $t ), $fname );

		# Finally, clean up the link tables
		$t = $this->mTitle->getPrefixedDBkey();

		Article::onArticleDelete( $this->mTitle );

		# Delete outgoing links
		$dbw->delete( 'pagelinks', array( 'pl_from' => $id ) );
		$dbw->delete( 'imagelinks', array( 'il_from' => $id ) );
		$dbw->delete( 'categorylinks', array( 'cl_from' => $id ) );
		$dbw->delete( 'templatelinks', array( 'tl_from' => $id ) );
		$dbw->delete( 'externallinks', array( 'el_from' => $id ) );

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
		$fname = 'Article::rollback';

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

		# Get the last editor, lock table exclusively
		$dbw->begin();
		$current = Revision::newFromTitle( $this->mTitle );
		if( is_null( $current ) ) {
			# Something wrong... no page?
			$dbw->rollback();
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
			), $fname,
			array(
				'USE INDEX' => 'page_timestamp',
				'ORDER BY'  => 'rev_timestamp DESC' )
			);
		if( $s === false ) {
			# Something wrong
			$dbw->rollback();
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
				), $fname
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
		Article::onArticleEdit( $this->mTitle );

		$dbw->commit();
		$wgOut->returnToMain( false );
	}


	/**
	 * Do standard deferred updates after page view
	 * @access private
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
	 * Every 1000th edit, prune the recent changes table.
	 * @access private
	 * @param string $text
	 */
	function editUpdates( $text, $summary, $minoredit, $timestamp_of_pagechange, $newid) {
		global $wgDeferredUpdateList, $wgMessageCache, $wgUser, $wgParser;

		$fname = 'Article::editUpdates';
		wfProfileIn( $fname );

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
			wfProfileOut( $fname );
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

		wfProfileOut( $fname );
	}

	/**
	 * Generate the navigation links when browsing through an article revisions
	 * It shows the information as:
	 *   Revision as of <date>; view current revision
	 *   <- Previous version | Next Version ->
	 *
	 * @access private
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
		$fname = 'Article::checkTouched';
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
		$fname = 'Article::quickEdit';
		wfProfileIn( $fname );

		$dbw =& wfGetDB( DB_MASTER );
		$dbw->begin();
		$revision = new Revision( array(
			'page'       => $this->getId(),
			'text'       => $text,
			'comment'    => $comment,
			'minor_edit' => $minor ? 1 : 0,
			) );
		$revisionId = $revision->insertOn( $dbw );
		$this->updateRevisionOn( $dbw, $revision );
		$dbw->commit();

		wfProfileOut( $fname );
	}

	/**
	 * Used to increment the view counter
	 *
	 * @static
	 * @param integer $id article id
	 */
	function incViewCount( $id ) {
		$id = intval( $id );
		global $wgHitcounterUpdateFreq;

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

			$dbw->query("LOCK TABLES $hitcounterTable WRITE");
			$dbw->query("CREATE TEMPORARY TABLE $acchitsTable TYPE=HEAP ".
				"SELECT hc_id,COUNT(*) AS hc_n FROM $hitcounterTable ".
				'GROUP BY hc_id');
			$dbw->query("DELETE FROM $hitcounterTable");
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

	function onArticleCreate($title_obj) {
		global $wgUseSquid, $wgPostCommitUpdateList;

		$title_obj->touchLinks();
		$titles = $title_obj->getLinksTo();

		# Purge squid
		if ( $wgUseSquid ) {
			$urls = $title_obj->getSquidURLs();
			foreach ( $titles as $linkTitle ) {
				$urls[] = $linkTitle->getInternalURL();
			}
			$u = new SquidUpdate( $urls );
			array_push( $wgPostCommitUpdateList, $u );
		}
	}

	function onArticleDelete( $title ) {
		global $wgMessageCache;

		$title->touchLinks();

		if( $title->getNamespace() == NS_MEDIAWIKI) {
			$wgMessageCache->replace( $title->getDBkey(), false );
		}
	}

	/**
	 * Purge caches on page update etc
	 */
	function onArticleEdit( $title ) {
		global $wgUseSquid, $wgPostCommitUpdateList, $wgUseFileCache;

		$urls = array();

		// Template namespace? Purge all articles linking here.
		// FIXME: When a templatelinks table arrives, use it for all includes.
		if ( $title->getNamespace() == NS_TEMPLATE) {
			$titles = $title->getLinksTo();
			Title::touchArray( $titles );
			if ( $wgUseSquid ) {
				foreach ( $titles as $link ) {
					$urls[] = $link->getInternalURL();
				}
			}
		}

		# Squid updates
		if ( $wgUseSquid ) {
			$urls = array_merge( $urls, $title->getSquidURLs() );
			$u = new SquidUpdate( $urls );
			array_push( $wgPostCommitUpdateList, $u );
		}

		# File cache
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
	 * @access public
	 */
	function info() {
		global $wgLang, $wgOut, $wgAllowPageInfo, $wgUser;
		$fname = 'Article::info';

		if ( !$wgAllowPageInfo ) {
			$wgOut->errorpage( 'nosuchaction', 'nosuchactiontext' );
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
				$fname,
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
	 * @access private
	 */
	function pageCountInfo( $title ) {
		$id = $title->getArticleId();
		if( $id == 0 ) {
			return false;
		}

		$dbr =& wfGetDB( DB_SLAVE );

		$rev_clause = array( 'rev_page' => $id );
		$fname = 'Article::pageCountInfo';

		$edits = $dbr->selectField(
			'revision',
			'COUNT(rev_page)',
			$rev_clause,
			$fname,
			$this->getSelectOptions() );

		$authors = $dbr->selectField(
			'revision',
			'COUNT(DISTINCT rev_user_text)',
			$rev_clause,
			$fname,
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
