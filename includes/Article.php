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
	/**#@-*/

	/**
	 * Constructor and clear the article
	 * @param mixed &$title
	 */
	function Article( &$title ) {
		$this->mTitle =& $title;
		$this->clear();
	}

	/**
	 * get the title object of the article
	 * @public
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
		$this->mRedirectedFrom = $this->mUserText =
		$this->mTimestamp = $this->mComment = $this->mFileCache = '';
		$this->mGoodAdjustment = $this->mTotalAdjustment = 0;
		$this->mTouched = '19700101000000';
		$this->mForUpdate = false;
		$this->mIsRedirect = false;
		$this->mRevIdFetched = 0;
	}

	/**
	 * Note that getContent/loadContent may follow redirects if
	 * not told otherwise, and so may cause a change to mTitle.
	 *
	 * @param $noredir
	 * @return Return the text of this revision
	*/
	function getContent( $noredir ) {
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
			return wfMsg( 'noarticletext' );
		} else {
			$this->loadContent( $noredir );
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
		This function accepts a title string as parameter
		($preload). If this string is non-empty, it attempts
		to fetch the current revision text.
	*/
	function getPreloadedText($preload) {
		if($preload) {
			$preloadTitle=Title::newFromText($preload);
			if(isset($preloadTitle) && $preloadTitle->userCanRead()) {
			$rev=Revision::newFromTitle($preloadTitle);
			if($rev) {
				return $rev->getText();
				}
			}
		}
		return '';
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
		  '/(^=+.+?=+|^<h[1-6].*?' . '>.*?<\/h[1-6].*?' . '>)(?!\S)/mi',
		  $striptext, -1,
		  PREG_SPLIT_DELIM_CAPTURE);
		if($section==0) {
			$rv=$secs[0];
		} else {
			$headline=$secs[$section*2-1];
			preg_match( '/^(=+).+?=+|^<h([1-6]).*?' . '>.*?<\/h[1-6].*?' . '>(?!\S)/mi',$headline,$matches);
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
				preg_match( '/^(=+).+?=+|^<h([1-6]).*?' . '>.*?<\/h[1-6].*?' . '>(?!\S)/mi',$subheadline,$matches);
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
	 * Return an array of the columns of the "cur"-table
	 */
	function getContentFields() {
		return $wgArticleContentFields = array(
		  'old_text','old_flags',
		  'rev_timestamp','rev_user', 'rev_user_text', 'rev_comment','page_counter',
		  'page_namespace', 'page_title', 'page_restrictions','page_touched','page_is_redirect' );
	}

	/**
	 * Return the oldid of the article that is to be shown.
	 * For requests with a "direction", this is not the oldid of the
	 * query
	 */
	function getOldID() {
		global $wgRequest, $wgOut;
		static $lastid;

		if ( isset( $lastid ) ) {
			return $lastid;
		}
		# Query variables :P
		$oldid = $wgRequest->getVal( 'oldid' );
		if ( isset( $oldid ) ) {
			$oldid = IntVal( $oldid );
			if ( $wgRequest->getVal( 'direction' ) == 'next' ) {
				$nextid = $this->mTitle->getNextRevisionID( $oldid );
				if ( $nextid  ) {
					$oldid = $nextid;
				} else {
					$wgOut->redirect( $this->mTitle->getFullURL( 'redirect=no' ) );
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
		return @$oldid;	# "@" to be able to return "unset" without PHP complaining
	}


	/**
	 * Load the revision (including cur_text) into this object
	 */
	function loadContent( $noredir = false ) {
		global $wgOut, $wgRequest;

		if ( $this->mContentLoaded ) return;

		# Query variables :P
		$oldid = $this->getOldID();
		$redirect = $wgRequest->getVal( 'redirect' );

		$fname = 'Article::loadContent';

		# Pre-fill content with error message so that if something
		# fails we'll have something telling us what we intended.

		$t = $this->mTitle->getPrefixedText();

		$noredir = $noredir || ($wgRequest->getVal( 'redirect' ) == 'no')
			|| $wgRequest->getCheck( 'rdfrom' );
		$this->mOldId = $oldid;
		$this->fetchContent( $oldid, $noredir, true );
	}


	/**
	 * Fetch a page record with the given conditions
	 * @param Database $dbr
	 * @param array    $conditions
	 * @access private
	 */
	function pageData( &$dbr, $conditions ) {
		return $dbr->selectRow( 'page',
			array(
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
				'page_len' ),
			$conditions,
			'Article::pageData' );
	}

	function pageDataFromTitle( &$dbr, $title ) {
		return $this->pageData( $dbr, array(
			'page_namespace' => $title->getNamespace(),
			'page_title'     => $title->getDBkey() ) );
	}

	function pageDataFromId( &$dbr, $id ) {
		return $this->pageData( $dbr, array(
			'page_id' => IntVal( $id ) ) );
	}

	/**
	 * Set the general counter, title etc data loaded from
	 * some source.
	 *
	 * @param object $data
	 * @access private
	 */
	function loadPageData( $data ) {
		$this->mTitle->loadRestrictions( $data->page_restrictions );
		$this->mTitle->mRestrictionsLoaded = true;

		$this->mCounter    = $data->page_counter;
		$this->mTouched    = wfTimestamp( TS_MW, $data->page_touched );
		$this->mIsRedirect = $data->page_is_redirect;
		$this->mLatest     = $data->page_latest;

		$this->mDataLoaded = true;
	}

	/**
	 * Get text of an article from database
	 * @param int $oldid 0 for whatever the latest revision is
	 * @param bool $noredir Set to false to follow redirects
	 * @param bool $globalTitle Set to true to change the global $wgTitle object when following redirects or other unexpected title changes
	 * @return string
	 */
	function fetchContent( $oldid = 0, $noredir = true, $globalTitle = false ) {
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
		if( isset( $redirect ) ) {
			$redirect = ($redirect == 'no') ? 'no' : 'yes';
			$t .= ',redirect='.$redirect;
		}
		$this->mContent = wfMsg( 'missingarticle', $t );

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
				wfDebug( "$fname failed to retrieve current page, rev_id $data->page_latest\n" );
				return false;
			}
		}

		# If we got a redirect, follow it (unless we've been told
		# not to by either the function parameter or the query
		if ( !$oldid && !$noredir ) {
			$rt = Title::newFromRedirect( $revision->getText() );
			# process if title object is valid and not special:userlogout
			if ( $rt && ! ( $rt->getNamespace() == NS_SPECIAL && $rt->getText() == 'Userlogout' ) ) {
				# Gotta hand redirects to special pages differently:
				# Fill the HTTP response "Location" header and ignore
				# the rest of the page we're on.
				global $wgDisableHardRedirects;
				if( $globalTitle && !$wgDisableHardRedirects ) {
					global $wgOut;
					if ( $rt->getInterwiki() != '' && $rt->isLocal() ) {
						$source = $this->mTitle->getFullURL( 'redirect=no' );
						$wgOut->redirect( $rt->getFullURL( 'rdfrom=' . urlencode( $source ) ) ) ;
						return false;
					}
					if ( $rt->getNamespace() == NS_SPECIAL ) {
						$wgOut->redirect( $rt->getFullURL() );
						return false;
					}
				}
				if( $rt->getInterwiki() == '' ) {
					$redirData = $this->pageDataFromTitle( $dbr, $rt );
					if( $redirData ) {
						$redirRev = Revision::newFromId( $redirData->page_latest );
						if( !is_null( $redirRev ) ) {
							$this->mRedirectedFrom = $this->mTitle->getPrefixedText();
							$this->mTitle = $rt;
							$data = $redirData;
							$this->loadPageData( $data );
							$revision = $redirRev;
						}
					}
				}
			}
		}

		# if the title's different from expected, update...
		if( $globalTitle ) {
			global $wgTitle;
			if( !$this->mTitle->equals( $wgTitle ) ) {
				$wgTitle = $this->mTitle;
			}
		}

		# Back to the business at hand...
		$this->mContent   = $revision->getText();

		$this->mUser      = $revision->getUser();
		$this->mUserText  = $revision->getUserText();
		$this->mComment   = $revision->getComment();
		$this->mTimestamp = wfTimestamp( TS_MW, $revision->getTimestamp() );

		$this->mRevIdFetched = $revision->getID();
		$this->mContentLoaded = true;
		$this->mRevision =& $revision;

		return $this->mContent;
	}

	/**
	 * Gets the article text without using so many damn globals
	 * Returns false on error
	 *
	 * @param integer $oldid
	 */
	function getContentWithoutUsingSoManyDamnGlobals( $oldid = 0, $noredir = false ) {
		return $this->fetchContent( $oldid, $noredir, false );
	}

	/**
	 * Read/write accessor to select FOR UPDATE
	 */
	function forUpdate( $x = NULL ) {
		return wfSetVar( $this->mForUpdate, $x );
	}

	/**
	 * Get the database which should be used for reads
	 */
	function &getDB() {
		#if ( $this->mForUpdate ) {
			$ret =& wfGetDB( DB_MASTER );
		#} else {
		#	$ret =& wfGetDB( DB_SLAVE );
		#}
		return $ret;
	}

	/**
	 * Get options for all SELECT statements
	 * Can pass an option array, to which the class-wide options will be appended
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
	 * Return the Article ID
	 */
	function getID() {
		if( $this->mTitle ) {
			return $this->mTitle->getArticleID();
		} else {
			return 0;
		}
	}

	/**
	 * Returns true if this article exists in the database.
	 * @return bool
	 */
	function exists() {
		return $this->getId() != 0;
	}

	/**
	 * Get the view count for this article
	 */
	function getCount() {
		if ( -1 == $this->mCounter ) {
			$id = $this->getID();
			$dbr =& $this->getDB();
			$this->mCounter = $dbr->selectField( 'page', 'page_counter', array( 'page_id' => $id ),
				'Article::getCount', $this->getSelectOptions() );
		}
		return $this->mCounter;
	}

	/**
	 * Would the given text make this article a "good" article (i.e.,
	 * suitable for including in the article count)?
	 * @param string $text Text to analyze
	 * @return integer 1 if it can be counted else 0
	 */
	function isCountable( $text ) {
		global $wgUseCommaCount;

		if ( NS_MAIN != $this->mTitle->getNamespace() ) { return 0; }
		if ( $this->isRedirect( $text ) ) { return 0; }
		$token = ($wgUseCommaCount ? ',' : '[[' );
		if ( false === strstr( $text, $token ) ) { return 0; }
		return 1;
	}

	/**
	 * Tests if the article text represents a redirect
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
		global $wgOut;

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
		}
	}

	function getTimestamp() {
		$this->loadLastEdit();
		return $this->mTimestamp;
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
		$dbr =& $this->getDB();
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
		global $wgUser, $wgOut, $wgRequest, $wgOnlySysopsCanPatrol, $wgLang;
		global $wgLinkCache, $IP, $wgEnableParserCache, $wgStylePath, $wgUseRCPatrol;
		global $wgEnotif, $wgParser, $wgParserCache, $wgUseTrackbacks;
		$sk = $wgUser->getSkin();

		$fname = 'Article::view';
		wfProfileIn( $fname );
		# Get variables from query string
		$oldid = $this->getOldID();
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

			$de = new DifferenceEngine( $oldid, $diff, $rcid );
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
			$wgOut->setETag($wgParserCache->getETag($this, $wgUser));

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

		$outputDone = false;
		if ( $pcache ) {
			if ( $wgOut->tryParserCache( $this, $wgUser ) ) {
				$outputDone = true;
			}
		}
		if ( !$outputDone ) {
			$text = $this->getContent( false ); # May change mTitle by following a redirect

			# Another whitelist check in case oldid or redirects are altering the title
			if ( !$this->mTitle->userCanRead() ) {
				$wgOut->loginToUse();
				$wgOut->output();
				exit;
			}

			# We're looking at an old revision

			if ( !empty( $oldid ) ) {
				$this->setOldSubtitle( isset($this->mOldId) ? $this->mOldId : $oldid );
				$wgOut->setRobotpolicy( 'noindex,follow' );
			}
			if ( '' != $this->mRedirectedFrom ) {
				if ( wfRunHooks( 'ArticleViewRedirect', array( &$this ) ) ) {
					$sk = $wgUser->getSkin();
					$redir = $sk->makeKnownLink( $this->mRedirectedFrom, '', 'redirect=no' );
					$s = wfMsg( 'redirectedfrom', $redir );
					$wgOut->setSubtitle( $s );
					# Can't cache redirects
					$pcache = false;
				}
			} elseif ( !empty( $rdfrom ) ) {
				global $wgRedirectSources;
				if( $wgRedirectSources && preg_match( $wgRedirectSources, $rdfrom ) ) {
					$sk = $wgUser->getSkin();
					$redir = $sk->makeExternalLink( $rdfrom, $rdfrom );
					$s = wfMsg( 'redirectedfrom', $redir );
					$wgOut->setSubtitle( $s );
				}
			}

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
				$imageUrl = $wgStylePath.'/common/images/redirect.png';
				$targetUrl = $rt->escapeLocalURL();
				$titleText = htmlspecialchars( $rt->getPrefixedText() );
				$link = $sk->makeLinkObj( $rt );

				$wgOut->addHTML( '<img valign="center" src="'.$imageUrl.'" alt="#REDIRECT" />' .
				  '<span class="redirectText">'.$link.'</span>' );

				$parseout = $wgParser->parse($text, $this->mTitle, ParserOptions::newFromUser($wgUser));
				$catlinks = $parseout->getCategoryLinks();
				$wgOut->addCategoryLinks($catlinks);
				$skin = $wgUser->getSkin();
			} else if ( $pcache ) {
				# Display content and save to parser cache
				$wgOut->addPrimaryWikiText( $text, $this );
			} else {
				# Display content, don't attempt to save to parser cache

				# Don't show section-edit links on old revisions... this way lies madness.
				if( !$this->isCurrent() ) {
					$oldEditSectionSetting = $wgOut->mParserOptions->setEditSection( false );
				}
				$wgOut->addWikiText( $text );

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
		if ( $wgUseRCPatrol
			&& !is_null($rcid)
			&& $rcid != 0
			&& $wgUser->isLoggedIn()
			&& ( $wgUser->isAllowed('patrol') || !$wgOnlySysopsCanPatrol ) )
		{
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

		# Put link titles into the link cache
		$wgOut->transformBuffer();

		# Add link titles as META keywords
		$wgOut->addMetaTags() ;

		$this->viewUpdates();
		wfProfileOut( $fname );
	}

	function addTrackbacks() {
		global $wgOut, $wgUser;

		$dbr = wfGetDB(DB_SLAVE);
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

		$db = wfGetDB(DB_MASTER);
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
	 * @param Revision $revision -- for ID number, and text used to set
	                                length and redirect status fields
	 * @param int $lastRevision -- if given, will not overwrite the page field
	 *                             when different from the currently set value.
	 *                             Giving 0 indicates the new page flag should
	 *                             be set on.
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
			if( $row->rev_timestamp >= $revision->getTimestamp() ) {
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
	 * Theoretically we could defer these whole insert and update
	 * functions for after display, but that's taking a big leap
	 * of faith, and we want to be able to report database
	 * errors at some point.
	 * @private
	 */
	function insertNewArticle( $text, $summary, $isminor, $watchthis, $suppressRC=false, $comment=false ) {
		global $wgOut, $wgUser;
		global $wgUseSquid, $wgDeferredUpdateList, $wgInternalServer;

		$fname = 'Article::insertNewArticle';
		wfProfileIn( $fname );

		$this->mGoodAdjustment = $this->isCountable( $text );
		$this->mTotalAdjustment = 1;

		$ns = $this->mTitle->getNamespace();
		$ttl = $this->mTitle->getDBkey();

		# If this is a comment, add the summary as headline
		if($comment && $summary!="") {
			$text="== {$summary} ==\n\n".$text;
		}
		$text = $this->preSaveTransform( $text );
		$isminor = ( $isminor && $wgUser->isLoggedIn() ) ? 1 : 0;
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
			RecentChange::notifyNew( $now, $this->mTitle, $isminor, $wgUser, $summary, 'default',
			  '', strlen( $text ), $revisionId );
		}

		if ($watchthis) {
			if(!$this->mTitle->userIsWatching()) $this->watch();
		} else {
			if ( $this->mTitle->userIsWatching() ) {
				$this->unwatch();
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
		$this->editUpdates( $text, $summary, $isminor, $now );

		$oldid = 0; # new article
		$this->showArticle( $text, wfMsg( 'newarticle' ), false, $isminor, $now, $summary, $oldid );
		wfProfileOut( $fname );
	}

	function getTextOfLastEditWithSectionReplacedOrAdded($section, $text, $summary = '', $edittime = NULL) {
		$fname = 'Article::getTextOfLastEditWithSectionReplacedOrAdded';
		if ($section != '') {
			if( is_null( $edittime ) ) {
				$rev = Revision::newFromTitle( $this->mTitle );
			} else {
				$dbw =& wfGetDB( DB_MASTER );
				$rev = Revision::loadFromTimestamp( $dbw, $this->mTitle, $edittime );
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
				$secs=preg_split('/(^=+.+?=+|^<h[1-6].*?' . '>.*?<\/h[1-6].*?' . '>)(?!\S)/mi',
				  $oldtext,-1,PREG_SPLIT_DELIM_CAPTURE);
				$secs[$section*2]=$text."\n\n"; // replace with edited

				# section 0 is top (intro) section
				if($section!=0) {

					# headline of old section - we need to go through this section
					# to determine if there are any subsections that now need to
					# be erased, as the mother section has been replaced with
					# the text of all subsections.
					$headline=$secs[$section*2-1];
					preg_match( '/^(=+).+?=+|^<h([1-6]).*?' . '>.*?<\/h[1-6].*?' . '>(?!\S)/mi',$headline,$matches);
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
						 '/^(=+).+?=+|^<h([1-6]).*?' . '>.*?<\/h[1-6].*?' . '>(?!\S)/mi',$subheadline,$matches);
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
		global $wgOut, $wgUser;
		global $wgDBtransactions, $wgMwRedir;
		global $wgUseSquid, $wgInternalServer, $wgPostCommitUpdateList, $wgUseFileCache;

		$fname = 'Article::updateArticle';
		wfProfileIn( $fname );
		$good = true;

		$isminor = ( $minor && $wgUser->isLoggedIn() );
		if ( $this->isRedirect( $text ) ) {
			# Remove all content but redirect
			# This could be done by reconstructing the redirect from a title given by
			# Title::newFromRedirect(), but then we wouldn't know which synonym the user
			# wants to see
			if ( preg_match( "/^((" . $wgMwRedir->getBaseRegex() . ')[^\\n]+)/i', $text, $m ) ) {
				$redir = 1;
				$text = $m[1] . "\n";
			}
		}
		else { $redir = 0; }

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

		$oldtext = $this->getContent( true );
		$oldsize = strlen( $oldtext );
		$newsize = strlen( $text );
		$lastRevision = 0;

		if ( 0 != strcmp( $text, $oldtext ) ) {
			$this->mGoodAdjustment = $this->isCountable( $text )
			  - $this->isCountable( $oldtext );
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
				$bot = (int)($wgUser->isBot() || $forceBot);
				RecentChange::notifyEdit( $now, $this->mTitle, $isminor, $wgUser, $summary,
					$lastRevision, $this->getTimestamp(), $bot, '', $oldsize, $newsize,
					$revisionId );
				Article::onArticleEdit( $this->mTitle );
				$dbw->commit();
			}
		}

		if( !$wgDBtransactions ) {
			ignore_user_abort( $userAbort );
		}

		if ( $good ) {
			if ($watchthis) {
				if (!$this->mTitle->userIsWatching()) $this->watch();
			} else {
				if ( $this->mTitle->userIsWatching() ) {
					$this->unwatch();
				}
			}
			# standard deferred updates
			$this->editUpdates( $text, $summary, $minor, $now );


			$urls = array();
			# Template namespace
			# Purge all articles linking here
			if ( $this->mTitle->getNamespace() == NS_TEMPLATE) {
				$titles = $this->mTitle->getLinksTo();
				Title::touchArray( $titles );
				if ( $wgUseSquid ) {
						foreach ( $titles as $title ) {
							$urls[] = $title->getInternalURL();
						}
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
		wfProfileOut( $fname );
		return $good;
	}

	/**
	 * After we've either updated or inserted the article, update
	 * the link tables and redirect to the new page.
	 */
	function showArticle( $text, $subtitle , $sectionanchor = '', $me2, $now, $summary, $oldid ) {
		global $wgUseDumbLinkUpdate, $wgAntiLockFlags, $wgOut, $wgUser, $wgLinkCache, $wgEnotif;
		global $wgUseEnotif;

		$fname = 'Article::showArticle';
		wfProfileIn( $fname );

		$wgLinkCache = new LinkCache();

		if ( !$wgUseDumbLinkUpdate ) {
			# Preload links to reduce lock time
			if ( $wgAntiLockFlags & ALF_PRELOAD_LINKS ) {
				$wgLinkCache->preFill( $this->mTitle );
				$wgLinkCache->clear();
			}
		}

		# Parse the text and replace links with placeholders
		$wgOut = new OutputPage();

		# Pass the current title along in case we're creating a wiki page
		# which is different than the currently displayed one (e.g. image
		# pages created on file uploads); otherwise, link updates will
		# go wrong.
		$wgOut->addWikiTextWithTitle( $text, $this->mTitle );

		if ( !$wgUseDumbLinkUpdate ) {
			# Move the current links back to the second register
			$wgLinkCache->swapRegisters();

			# Get old version of link table to allow incremental link updates
			# Lock this data now since it is needed for an update
			$wgLinkCache->forUpdate( true );
			$wgLinkCache->preFill( $this->mTitle );

			# Swap this old version back into its rightful place
			$wgLinkCache->swapRegisters();
		}

		if( $this->isRedirect( $text ) )
			$r = 'redirect=no';
		else
			$r = '';
		$wgOut->redirect( $this->mTitle->getFullURL( $r ).$sectionanchor );

		if ( $wgUseEnotif  ) {
			# this would be better as an extension hook
			include_once( "UserMailer.php" );
			$wgEnotif = new EmailNotification ();
			$wgEnotif->notifyOnPageChange( $this->mTitle, $now, $summary, $me2, $oldid );
		}
		wfProfileOut( $fname );
	}

	/**
	 * Mark this particular edit as patrolled
	 */
	function markpatrolled() {
		global $wgOut, $wgRequest, $wgOnlySysopsCanPatrol, $wgUseRCPatrol, $wgUser;
		$wgOut->setRobotpolicy( 'noindex,follow' );

		if ( !$wgUseRCPatrol )
		{
			$wgOut->errorpage( 'rcpatroldisabled', 'rcpatroldisabledtext' );
			return;
		}
		if ( $wgUser->isAnon() )
		{
			$wgOut->loginToUse();
			return;
		}
		if ( $wgOnlySysopsCanPatrol && !$wgUser->isAllowed('patrol') )
		{
			$wgOut->sysopRequired();
			return;
		}
		$rcid = $wgRequest->getVal( 'rcid' );
		if ( !is_null ( $rcid ) )
		{
			RecentChange::markPatrolled( $rcid );
			$wgOut->setPagetitle( wfMsg( 'markedaspatrolled' ) );
			$wgOut->addWikiText( wfMsg( 'markedaspatrolledtext' ) );

			$rcTitle = Title::makeTitle( NS_SPECIAL, 'Recentchanges' );
			$wgOut->returnToMain( false, $rcTitle->getPrefixedText() );
		}
		else
		{
			$wgOut->errorpage( 'markedaspatrollederror', 'markedaspatrollederrortext' );
		}
	}

	/**
	 * Validate function
	 */
	function validate() {
		global $wgOut, $wgUser, $wgRequest, $wgUseValidation;

		if ( !$wgUseValidation ) # Are we using article validation at all?
		{
			$wgOut->errorpage( "nosuchspecialpage", "nospecialpagetext" );
			return ;
		}

		$wgOut->setRobotpolicy( 'noindex,follow' );
		$revision = $wgRequest->getVal( 'revision' );

		include_once ( "SpecialValidate.php" ) ; # The "Validation" class

		$v = new Validation ;
		if ( $wgRequest->getVal ( "mode" , "" ) == "list" )
			$t = $v->showList ( $this ) ;
		else if ( $wgRequest->getVal ( "mode" , "" ) == "details" )
			$t = $v->showDetails ( $this , $wgRequest->getVal( 'revision' ) ) ;
		else
			$t = $v->validatePageForm ( $this , $revision ) ;

		$wgOut->addHTML ( $t ) ;
	}

	/**
	 * Add this page to $wgUser's watchlist
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

		if (wfRunHooks('WatchArticle', array(&$wgUser, &$this))) {

			$wgUser->addWatch( $this->mTitle );
			$wgUser->saveSettings();

			wfRunHooks('WatchArticleComplete', array(&$wgUser, &$this));

			$wgOut->setPagetitle( wfMsg( 'addedwatch' ) );
			$wgOut->setRobotpolicy( 'noindex,follow' );

			$link = $this->mTitle->getPrefixedText();
			$text = wfMsg( 'addedwatchtext', $link );
			$wgOut->addWikiText( $text );
		}

		$wgOut->returnToMain( true, $this->mTitle->getPrefixedText() );
	}

	/**
	 * Stop watching a page
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

		if (wfRunHooks('UnwatchArticle', array(&$wgUser, &$this))) {

			$wgUser->removeWatch( $this->mTitle );
			$wgUser->saveSettings();

			wfRunHooks('UnwatchArticleComplete', array(&$wgUser, &$this));

			$wgOut->setPagetitle( wfMsg( 'removedwatch' ) );
			$wgOut->setRobotpolicy( 'noindex,follow' );

			$link = $this->mTitle->getPrefixedText();
			$text = wfMsg( 'removedwatchtext', $link );
			$wgOut->addWikiText( $text );
		}

		$wgOut->returnToMain( true, $this->mTitle->getPrefixedText() );
	}

	/**
	 * protect a page
	 */
	function protect( $limit = 'sysop' ) {
		global $wgUser, $wgOut, $wgRequest;

		if ( ! $wgUser->isAllowed('protect') ) {
			$wgOut->sysopRequired();
			return;
		}
		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}
		$id = $this->mTitle->getArticleID();
		if ( 0 == $id ) {
			$wgOut->fatalError( wfMsg( 'badarticleerror' ) );
			return;
		}

		$confirm = $wgRequest->wasPosted() &&
			$wgUser->matchEditToken( $wgRequest->getVal( 'wpEditToken' ) );
		$moveonly = $wgRequest->getBool( 'wpMoveOnly' );
		$reason = $wgRequest->getText( 'wpReasonProtect' );

		if ( $confirm ) {
			$dbw =& wfGetDB( DB_MASTER );
			$dbw->update( 'page',
				array( /* SET */
					'page_touched' => $dbw->timestamp(),
					'page_restrictions' => (string)$limit
				), array( /* WHERE */
					'page_id' => $id
				), 'Article::protect'
			);

			$restrictions = "move=" . $limit;
			if( !$moveonly ) {
				$restrictions .= ":edit=" . $limit;
			}
			if (wfRunHooks('ArticleProtect', array(&$this, &$wgUser, $limit == 'sysop', $reason, $moveonly))) {

				$dbw =& wfGetDB( DB_MASTER );
				$dbw->update( 'page',
							  array( /* SET */
									 'page_touched' => $dbw->timestamp(),
									 'page_restrictions' => $restrictions
									 ), array( /* WHERE */
											   'page_id' => $id
											   ), 'Article::protect'
							  );

				wfRunHooks('ArticleProtectComplete', array(&$this, &$wgUser, $limit == 'sysop', $reason, $moveonly));

				$log = new LogPage( 'protect' );
				if ( $limit === '' ) {
					$log->addEntry( 'unprotect', $this->mTitle, $reason );
				} else {
					$log->addEntry( 'protect', $this->mTitle, $reason );
				}
				$wgOut->redirect( $this->mTitle->getFullURL() );
			}
			return;
		} else {
			return $this->confirmProtect( '', '', $limit );
		}
	}

	/**
	 * Output protection confirmation dialog
	 */
	function confirmProtect( $par, $reason, $limit = 'sysop'  ) {
		global $wgOut, $wgUser;

		wfDebug( "Article::confirmProtect\n" );

		$sub = htmlspecialchars( $this->mTitle->getPrefixedText() );
		$wgOut->setRobotpolicy( 'noindex,nofollow' );

		$check = '';
		$protcom = '';
		$moveonly = '';

		if ( $limit === '' ) {
			$wgOut->setPageTitle( wfMsg( 'confirmunprotect' ) );
			$wgOut->setSubtitle( wfMsg( 'unprotectsub', $sub ) );
			$wgOut->addWikiText( wfMsg( 'confirmunprotecttext' ) );
			$protcom = htmlspecialchars( wfMsg( 'unprotectcomment' ) );
			$formaction = $this->mTitle->escapeLocalURL( 'action=unprotect' . $par );
		} else {
			$wgOut->setPageTitle( wfMsg( 'confirmprotect' ) );
			$wgOut->setSubtitle( wfMsg( 'protectsub', $sub ) );
			$wgOut->addWikiText( wfMsg( 'confirmprotecttext' ) );
			$moveonly = wfMsg( 'protectmoveonly' ) ; // add it using addWikiText to prevent xss. bug:3991
			$protcom = htmlspecialchars( wfMsg( 'protectcomment' ) );
			$formaction = $this->mTitle->escapeLocalURL( 'action=protect' . $par );
		}

		$confirm = htmlspecialchars( wfMsg( 'confirm' ) );
		$token = htmlspecialchars( $wgUser->editToken() );

		$wgOut->addHTML( "
<form id='protectconfirm' method='post' action=\"{$formaction}\">
	<table border='0'>
		<tr>
			<td align='right'>
				<label for='wpReasonProtect'>{$protcom}:</label>
			</td>
			<td align='left'>
				<input type='text' size='60' name='wpReasonProtect' id='wpReasonProtect' value=\"" . htmlspecialchars( $reason ) . "\" />
			</td>
		</tr>" );
		if($moveonly != '') {
			$wgOut->AddHTML( "
		<tr>
			<td align='right'>
				<input type='checkbox' name='wpMoveOnly' value='1' id='wpMoveOnly' />
			</td>
			<td align='left'>
				<label for='wpMoveOnly'> ");
			$wgOut->addWikiText( $moveonly ); // bug 3991
			$wgOut->addHTML( "
				</label>
			</td>
		</tr> " );
		}
		$wgOut->addHTML( "
		<tr>
			<td>&nbsp;</td>
			<td>
				<input type='submit' name='wpConfirmProtectB' value=\"{$confirm}\" />
			</td>
		</tr>
	</table>
	<input type='hidden' name='wpEditToken' value=\"{$token}\" />
</form>" );

		$wgOut->returnToMain( false );
	}

	/**
	 * Unprotect the pages
	 */
	function unprotect() {
		return $this->protect( '' );
	}

	/*
	 * UI entry point for page deletion
	 */
	function delete() {
		global $wgUser, $wgOut, $wgMessageCache, $wgRequest;
		$fname = 'Article::delete';
		$confirm = $wgRequest->wasPosted() &&
			$wgUser->matchEditToken( $wgRequest->getVal( 'wpEditToken' ) );
		$reason = $wgRequest->getText( 'wpReason' );

		# This code desperately needs to be totally rewritten

		# Check permissions
		if( ( !$wgUser->isAllowed( 'delete' ) ) ) {
			$wgOut->sysopRequired();
			return;
		}
		if( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}

		# Better double-check that it hasn't been deleted yet!
		$wgOut->setPagetitle( wfMsg( 'confirmdelete' ) );
		if( !$this->mTitle->exists() ) {
			$wgOut->fatalError( wfMsg( 'cannotdelete' ) );
			return;
		}

		if( $confirm ) {
			$this->doDelete( $reason );
			return;
		}

		# determine whether this page has earlier revisions
		# and insert a warning if it does
		# we select the text because it might be useful below
		$dbr =& $this->getDB();
		$ns = $this->mTitle->getNamespace();
		$title = $this->mTitle->getDBkey();
		$revisions = $dbr->select( array( 'page', 'revision' ),
			array( 'rev_id', 'rev_user_text' ),
			array(
				'page_namespace' => $ns,
				'page_title' => $title,
				'rev_page = page_id'
			), $fname, $this->getSelectOptions( array( 'ORDER BY' => 'rev_timestamp DESC' ) )
		);

		if( $dbr->numRows( $revisions ) > 1 && !$confirm ) {
			$skin=$wgUser->getSkin();
			$wgOut->addHTML('<b>'.wfMsg('historywarning'));
			$wgOut->addHTML( $skin->historyLink() .'</b>');
		}

		# Fetch cur_text
		$rev = Revision::newFromTitle( $this->mTitle );

		# Fetch name(s) of contributors
		$rev_name = '';
		$all_same_user = true;
		while( $row = $dbr->fetchObject( $revisions ) ) {
			if( $rev_name != '' && $rev_name != $row->rev_user_text ) {
				$all_same_user = false;
			} else {
				$rev_name = $row->rev_user_text;
			}
		}

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
					if( !$all_same_user ) {
						$reason = wfMsgForContent( 'excontent', $text );
					} else {
						$reason = wfMsgForContent( 'excontentauthor', $text, $rev_name );
					}
				} else {
					$reason = wfMsgForContent( 'exbeforeblank', $text );
				}
			}
		}

		return $this->confirmDelete( '', $reason );
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

		$confirm = htmlspecialchars( wfMsg( 'confirm' ) );
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
		global $wgOut, $wgUser, $wgContLang;
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
		global $wgUser;
		global $wgUseSquid, $wgDeferredUpdateList, $wgInternalServer, $wgPostCommitUpdateList;
		global $wgUseTrackbacks;

		$fname = 'Article::doDeleteArticle';
		wfDebug( $fname."\n" );

		$dbw =& wfGetDB( DB_MASTER );
		$ns = $this->mTitle->getNamespace();
		$t = $this->mTitle->getDBkey();
		$id = $this->mTitle->getArticleID();

		if ( $t == '' || $id == 0 ) {
			return false;
		}

		$u = new SiteStatsUpdate( 0, 1, -$this->isCountable( $this->getContent( true ) ), -1 );
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
		global $wgUser, $wgOut, $wgRequest;
		$fname = 'Article::rollback';

		if ( ! $wgUser->isAllowed('rollback') ) {
			$wgOut->sysopRequired();
			return;
		}
		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage( $this->getContent( true ) );
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
			$wgOut->setPageTitle(wfmsg('rollbackfailed'));
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
		$user = IntVal( $current->getUser() );
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

		if ( $bot ) {
			# Mark all reverted edits as bot
			$dbw->update( 'recentchanges',
				array( /* SET */
					'rc_bot' => 1
				), array( /* WHERE */
					'rc_cur_id'    => $current->getPage(),
					'rc_user_text' => $current->getUserText(),
					"rc_timestamp > '{$s->rev_timestamp}'",
				), $fname
			);
		}

		# Save it!
		$target = Revision::newFromId( $s->rev_id );
		$newcomment = wfMsgForContent( 'revertpage', $target->getUserText(), $from );

		$wgOut->setPagetitle( wfMsg( 'actioncomplete' ) );
		$wgOut->setRobotpolicy( 'noindex,nofollow' );
		$wgOut->addHTML( '<h2>' . htmlspecialchars( $newcomment ) . "</h2>\n<hr />\n" );

		$this->updateArticle( $target->getText(), $newcomment, 1, $this->mTitle->userIsWatching(), $bot );
		Article::onArticleEdit( $this->mTitle );

		$dbw->commit();
		$wgOut->returnToMain( false );
	}


	/**
	 * Do standard deferred updates after page view
	 * @private
	 */
	function viewUpdates() {
		global $wgDeferredUpdateList, $wgUseEnotif;

		if ( 0 != $this->getID() ) {
			global $wgDisableCounters;
			if( !$wgDisableCounters ) {
				Article::incViewCount( $this->getID() );
				$u = new SiteStatsUpdate( 1, 0, 0 );
				array_push( $wgDeferredUpdateList, $u );
			}
		}

		# Update newtalk status if user is reading their own
		# talk page

		if (!wfRunHooks('UserClearNewTalkNotification', array(&$this)))
			return;

		global $wgUser;
		if (wfRunHooks('ArticleEditUpdateNewTalk', array(&$this)) ) {
			if ($this->mTitle->getNamespace() == NS_USER_TALK &&
				$this->mTitle->getText() == $wgUser->getName())
			{

				if ( $wgUseEnotif ) {
					require_once( 'UserTalkUpdate.php' );
					$u = new UserTalkUpdate( 0, $this->mTitle->getNamespace(),
						$this->mTitle->getDBkey(), false, false, false );
				} else {
					$wgUser->setNewtalk(0);
					$wgUser->saveNewtalk();
				}
			} elseif ( $wgUseEnotif ) {
				$wgUser->clearNotification( $this->mTitle );
			}
		}
	}

	/**
	 * Do standard deferred updates after page edit.
	 * Every 1000th edit, prune the recent changes table.
	 * @private
	 * @param string $text
	 */
	function editUpdates( $text, $summary, $minoredit, $timestamp_of_pagechange) {
		global $wgDeferredUpdateList, $wgDBname, $wgMemc;
		global $wgMessageCache, $wgUser, $wgUseEnotif;

		wfSeedRandom();
		if ( 0 == mt_rand( 0, 999 ) ) {
			# Periodically flush old entries from the recentchanges table.
			global $wgRCMaxAge;
			$dbw =& wfGetDB( DB_MASTER );
			$cutoff = $dbw->timestamp( time() - $wgRCMaxAge );
			$recentchanges = $dbw->tableName( 'recentchanges' );
			$sql = "DELETE FROM $recentchanges WHERE rc_timestamp < '{$cutoff}'";
			//$dbw->query( $sql ); // HACK: disabled for now, slowness

			// re-enabled for commit of unrelated live changes -- TS
			$dbw->query( $sql );
		}
		$id = $this->getID();
		$title = $this->mTitle->getPrefixedDBkey();
		$shortTitle = $this->mTitle->getDBkey();

		if ( 0 != $id ) {
			$u = new LinksUpdate( $id, $title );
			array_push( $wgDeferredUpdateList, $u );
			$u = new SiteStatsUpdate( 0, 1, $this->mGoodAdjustment, $this->mTotalAdjustment );
			array_push( $wgDeferredUpdateList, $u );
			$u = new SearchUpdate( $id, $title, $text );
			array_push( $wgDeferredUpdateList, $u );

			# If this is another user's talk page, update newtalk

			if ($this->mTitle->getNamespace() == NS_USER_TALK && $shortTitle != $wgUser->getName()) {
				if ( $wgUseEnotif ) {
					require_once( 'UserTalkUpdate.php' );
					$u = new UserTalkUpdate( 1, $this->mTitle->getNamespace(), $shortTitle, $summary,
					  $minoredit, $timestamp_of_pagechange);
				} else {
					$other = User::newFromName( $shortTitle );
					if( is_null( $other ) && User::isIP( $shortTitle ) ) {
						// An anonymous user
						$other = new User();
						$other->setName( $shortTitle );
					}
					if( $other ) {
						$other->setNewtalk(1);
						$other->saveNewtalk();
					}
				}
			}

			if ( $this->mTitle->getNamespace() == NS_MEDIAWIKI ) {
				$wgMessageCache->replace( $shortTitle, $text );
			}
		}
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
				global $wgOut;
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
	 * Loads cur_touched and returns a value indicating if it should be used
	 *
	 */
	function checkTouched() {
		$fname = 'Article::checkTouched';
		if( !$this->mDataLoaded ) {
			$dbr =& $this->getDB();
			$data = $this->pageDataFromId( $dbr, $this->getId() );
			if( $data ) {
				$this->loadPageData( $data );
			}
		}
		return !$this->mIsRedirect;
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

	function onArticleEdit($title_obj) {
		// This would be an appropriate place to purge caches.
		// Why's this not in here now?
	}

	/**#@-*/

	/**
	 * Info about this page
	 * Called for ?action=info when $wgAllowPageInfo is on.
	 *
	 * @access public
	 */
	function info() {
		global $wgLang, $wgOut, $wgAllowPageInfo;
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
			$wgOut->addHTML( wfMsg('noarticletext') );
		} else {
			$dbr =& $this->getDB( DB_SLAVE );
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

		$dbr =& $this->getDB( DB_SLAVE );

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
	 * Uses the links table to find the templates
	 *
	 * @return array
	 */
	function getUsedTemplates() {
		$result = array();
		$id = $this->mTitle->getArticleID();

		$db =& wfGetDB( DB_SLAVE );
		$res = $db->select( array( 'pagelinks' ),
			array( 'pl_title' ),
			array(
				'pl_from' => $id,
				'pl_namespace' => NS_TEMPLATE ),
			'Article:getUsedTemplates' );
		if ( false !== $res ) {
			if ( $db->numRows( $res ) ) {
				while ( $row = $db->fetchObject( $res ) ) {
					$result[] = $row->pl_title;
				}
			}
		}
		$db->freeResult( $res );
		return $result;
	}
}

?>
