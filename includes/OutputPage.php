<?php
if ( ! defined( 'MEDIAWIKI' ) )
	die( 1 );
/**
 */

/**
 * @todo document
 */
class OutputPage {
	var $mMetatags, $mKeywords;
	var $mLinktags, $mPagetitle, $mBodytext, $mDebugtext;
	var $mHTMLtitle, $mRobotpolicy, $mIsarticle, $mPrintable;
	var $mSubtitle, $mRedirect, $mStatusCode;
	var $mLastModified, $mETag, $mCategoryLinks;
	var $mScripts, $mLinkColours, $mPageLinkTitle;

	var $mAllowUserJs;
	var $mSuppressQuickbar;
	var $mOnloadHandler;
	var $mDoNothing;
	var $mContainsOldMagic, $mContainsNewMagic;
	var $mIsArticleRelated;
	protected $mParserOptions; // lazy initialised, use parserOptions()
	var $mShowFeedLinks = false;
	var $mEnableClientCache = true;
	var $mArticleBodyOnly = false;
	
	var $mNewSectionLink = false;
	var $mNoGallery = false;

	/**
	 * Constructor
	 * Initialise private variables
	 */
	function __construct() {
		global $wgAllowUserJs;
		$this->mAllowUserJs = $wgAllowUserJs;
		$this->mMetatags = $this->mKeywords = $this->mLinktags = array();
		$this->mHTMLtitle = $this->mPagetitle = $this->mBodytext =
		$this->mRedirect = $this->mLastModified =
		$this->mSubtitle = $this->mDebugtext = $this->mRobotpolicy =
		$this->mOnloadHandler = $this->mPageLinkTitle = '';
		$this->mIsArticleRelated = $this->mIsarticle = $this->mPrintable = true;
		$this->mSuppressQuickbar = $this->mPrintable = false;
		$this->mLanguageLinks = array();
		$this->mCategoryLinks = array();
		$this->mDoNothing = false;
		$this->mContainsOldMagic = $this->mContainsNewMagic = 0;
		$this->mParserOptions = null;
		$this->mSquidMaxage = 0;
		$this->mScripts = '';
		$this->mHeadItems = array();
		$this->mETag = false;
		$this->mRevisionId = null;
		$this->mNewSectionLink = false;
		$this->mTemplateIds = array();
	}
	
	public function redirect( $url, $responsecode = '302' ) {
		# Strip newlines as a paranoia check for header injection in PHP<5.1.2
		$this->mRedirect = str_replace( "\n", '', $url );
		$this->mRedirectCode = $responsecode;
	}

	/**
	 * Set the HTTP status code to send with the output.
	 *
	 * @param int $statusCode
	 * @return nothing
	 */
	function setStatusCode( $statusCode ) { $this->mStatusCode = $statusCode; }

	# To add an http-equiv meta tag, precede the name with "http:"
	function addMeta( $name, $val ) { array_push( $this->mMetatags, array( $name, $val ) ); }
	function addKeyword( $text ) { array_push( $this->mKeywords, $text ); }
	function addScript( $script ) { $this->mScripts .= "\t\t".$script; }
	function addStyle( $style ) {
		global $wgStylePath, $wgStyleVersion;
		$this->addLink(
				array(
					'rel' => 'stylesheet',
					'href' => $wgStylePath . '/' . $style . '?' . $wgStyleVersion ) );
	}

	/**
	 * Add a self-contained script tag with the given contents
	 * @param string $script JavaScript text, no <script> tags
	 */
	function addInlineScript( $script ) {
		global $wgJsMimeType;
		$this->mScripts .= "<script type=\"$wgJsMimeType\">/*<![CDATA[*/\n$script\n/*]]>*/</script>";
	}

	function getScript() { 
		return $this->mScripts . $this->getHeadItems(); 
	}

	function getHeadItems() {
		$s = '';
		foreach ( $this->mHeadItems as $item ) {
			$s .= $item;
		}
		return $s;
	}

	function addHeadItem( $name, $value ) {
		$this->mHeadItems[$name] = $value;
	}

	function setETag($tag) { $this->mETag = $tag; }
	function setArticleBodyOnly($only) { $this->mArticleBodyOnly = $only; }
	function getArticleBodyOnly($only) { return $this->mArticleBodyOnly; }

	function addLink( $linkarr ) {
		# $linkarr should be an associative array of attributes. We'll escape on output.
		array_push( $this->mLinktags, $linkarr );
	}

	function addMetadataLink( $linkarr ) {
		# note: buggy CC software only reads first "meta" link
		static $haveMeta = false;
		$linkarr['rel'] = ($haveMeta) ? 'alternate meta' : 'meta';
		$this->addLink( $linkarr );
		$haveMeta = true;
	}

	/**
	 * checkLastModified tells the client to use the client-cached page if
	 * possible. If sucessful, the OutputPage is disabled so that
	 * any future call to OutputPage->output() have no effect.
	 *
	 * @return bool True iff cache-ok headers was sent.
	 */
	function checkLastModified ( $timestamp ) {
		global $wgCachePages, $wgCacheEpoch, $wgUser, $wgRequest;
		$fname = 'OutputPage::checkLastModified';

		if ( !$timestamp || $timestamp == '19700101000000' ) {
			wfDebug( "$fname: CACHE DISABLED, NO TIMESTAMP\n" );
			return;
		}
		if( !$wgCachePages ) {
			wfDebug( "$fname: CACHE DISABLED\n", false );
			return;
		}
		if( $wgUser->getOption( 'nocache' ) ) {
			wfDebug( "$fname: USER DISABLED CACHE\n", false );
			return;
		}

		$timestamp=wfTimestamp(TS_MW,$timestamp);
		$lastmod = wfTimestamp( TS_RFC2822, max( $timestamp, $wgUser->mTouched, $wgCacheEpoch ) );

		if( !empty( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) ) {
			# IE sends sizes after the date like this:
			# Wed, 20 Aug 2003 06:51:19 GMT; length=5202
			# this breaks strtotime().
			$modsince = preg_replace( '/;.*$/', '', $_SERVER["HTTP_IF_MODIFIED_SINCE"] );
			$modsinceTime = strtotime( $modsince );
			$ismodsince = wfTimestamp( TS_MW, $modsinceTime ? $modsinceTime : 1 );
			wfDebug( "$fname: -- client send If-Modified-Since: " . $modsince . "\n", false );
			wfDebug( "$fname: --  we might send Last-Modified : $lastmod\n", false );
			if( ($ismodsince >= $timestamp ) && $wgUser->validateCache( $ismodsince ) && $ismodsince >= $wgCacheEpoch ) {
				# Make sure you're in a place you can leave when you call us!
				$wgRequest->response()->header( "HTTP/1.0 304 Not Modified" );
				$this->mLastModified = $lastmod;
				$this->sendCacheControl();
				wfDebug( "$fname: CACHED client: $ismodsince ; user: $wgUser->mTouched ; page: $timestamp ; site $wgCacheEpoch\n", false );
				$this->disable();
				
				// Don't output a compressed blob when using ob_gzhandler;
				// it's technically against HTTP spec and seems to confuse
				// Firefox when the response gets split over two packets.
				wfClearOutputBuffers();
				
				return true;
			} else {
				wfDebug( "$fname: READY  client: $ismodsince ; user: $wgUser->mTouched ; page: $timestamp ; site $wgCacheEpoch\n", false );
				$this->mLastModified = $lastmod;
			}
		} else {
			wfDebug( "$fname: client did not send If-Modified-Since header\n", false );
			$this->mLastModified = $lastmod;
		}
	}

	function getPageTitleActionText () {
		global $action;
		switch($action) {
			case 'edit':
			case 'delete':
			case 'protect':
			case 'unprotect':
			case 'watch':
			case 'unwatch':
				// Display title is already customized
				return '';
			case 'history':
				return wfMsg('history_short');
			case 'submit':
				// FIXME: bug 2735; not correct for special pages etc
				return wfMsg('preview');
			case 'info':
				return wfMsg('info_short');
			default:
				return '';
		}
	}

	public function setRobotpolicy( $str ) { $this->mRobotpolicy = $str; }
	public function setHTMLTitle( $name ) {$this->mHTMLtitle = $name; }
	public function setPageTitle( $name ) {
		global $action, $wgContLang;
		$name = $wgContLang->convert($name, true);
		$this->mPagetitle = $name;
		if(!empty($action)) {
			$taction =  $this->getPageTitleActionText();
			if( !empty( $taction ) ) {
				$name .= ' - '.$taction;
			}
		}

		$this->setHTMLTitle( wfMsg( 'pagetitle', $name ) );
	}
	public function getHTMLTitle() { return $this->mHTMLtitle; }
	public function getPageTitle() { return $this->mPagetitle; }
	public function setSubtitle( $str ) { $this->mSubtitle = /*$this->parse(*/$str/*)*/; } // @bug 2514
	public function getSubtitle() { return $this->mSubtitle; }
	public function isArticle() { return $this->mIsarticle; }
	public function setPrintable() { $this->mPrintable = true; }
	public function isPrintable() { return $this->mPrintable; }
	public function setSyndicated( $show = true ) { $this->mShowFeedLinks = $show; }
	public function isSyndicated() { return $this->mShowFeedLinks; }
	public function setOnloadHandler( $js ) { $this->mOnloadHandler = $js; }
	public function getOnloadHandler() { return $this->mOnloadHandler; }
	public function disable() { $this->mDoNothing = true; }

	public function setArticleRelated( $v ) {
		$this->mIsArticleRelated = $v;
		if ( !$v ) {
			$this->mIsarticle = false;
		}
	}
	public function setArticleFlag( $v ) {
		$this->mIsarticle = $v;
		if ( $v ) {
			$this->mIsArticleRelated = $v;
		}
	}

	public function isArticleRelated() { return $this->mIsArticleRelated; }

	public function getLanguageLinks() { return $this->mLanguageLinks; }
	public function addLanguageLinks($newLinkArray) {
		$this->mLanguageLinks += $newLinkArray;
	}
	public function setLanguageLinks($newLinkArray) {
		$this->mLanguageLinks = $newLinkArray;
	}

	public function getCategoryLinks() {
		return $this->mCategoryLinks;
	}

	/**
	 * Add an array of categories, with names in the keys
	 */
	public function addCategoryLinks($categories) {
		global $wgUser, $wgContLang;

		if ( !is_array( $categories ) ) {
			return;
		}
		# Add the links to the link cache in a batch
		$arr = array( NS_CATEGORY => $categories );
		$lb = new LinkBatch;
		$lb->setArray( $arr );
		$lb->execute();

		$sk = $wgUser->getSkin();
		foreach ( $categories as $category => $unused ) {
			$title = Title::makeTitleSafe( NS_CATEGORY, $category );
			$text = $wgContLang->convertHtml( $title->getText() );
			$this->mCategoryLinks[] = $sk->makeLinkObj( $title, $text );
		}
	}

	public function setCategoryLinks($categories) {
		$this->mCategoryLinks = array();
		$this->addCategoryLinks($categories);
	}

	public function suppressQuickbar() { $this->mSuppressQuickbar = true; }
	public function isQuickbarSuppressed() { return $this->mSuppressQuickbar; }

	public function disallowUserJs() { $this->mAllowUserJs = false; }
	public function isUserJsAllowed() { return $this->mAllowUserJs; }

	public function addHTML( $text ) { $this->mBodytext .= $text; }
	public function clearHTML() { $this->mBodytext = ''; }
	public function getHTML() { return $this->mBodytext; }
	public function debug( $text ) { $this->mDebugtext .= $text; }

	/* @deprecated */
	public function setParserOptions( $options ) {
		return $this->parserOptions( $options );
	}

	public function parserOptions( $options = null ) {
		if ( !$this->mParserOptions ) {
			$this->mParserOptions = new ParserOptions;
		}
		return wfSetVar( $this->mParserOptions, $options );
	}

	/**
	 * Set the revision ID which will be seen by the wiki text parser
	 * for things such as embedded {{REVISIONID}} variable use.
	 * @param mixed $revid an integer, or NULL
	 * @return mixed previous value
	 */
	public function setRevisionId( $revid ) {
		$val = is_null( $revid ) ? null : intval( $revid );
		return wfSetVar( $this->mRevisionId, $val );
	}

	/**
	 * Convert wikitext to HTML and add it to the buffer
	 * Default assumes that the current page title will
	 * be used.
	 *
	 * @param string $text
	 * @param bool   $linestart
	 */
	public function addWikiText( $text, $linestart = true ) {
		global $wgTitle;
		$this->addWikiTextTitle($text, $wgTitle, $linestart);
	}

	public function addWikiTextWithTitle($text, &$title, $linestart = true) {
		$this->addWikiTextTitle($text, $title, $linestart);
	}

	function addWikiTextTitleTidy($text, &$title, $linestart = true) {
		$this->addWikiTextTitle( $text, $title, $linestart, true );
	}

	public function addWikiTextTitle($text, &$title, $linestart, $tidy = false) {
		global $wgParser;

		$fname = 'OutputPage:addWikiTextTitle';
		wfProfileIn($fname);

		wfIncrStats('pcache_not_possible');

		$popts = $this->parserOptions();
		$popts->setTidy($tidy);

		$parserOutput = $wgParser->parse( $text, $title, $popts,
			$linestart, true, $this->mRevisionId );

		$this->addParserOutput( $parserOutput );

		wfProfileOut($fname);
	}

	/**
	 * @todo document
	 * @param ParserOutput object &$parserOutput
	 */
	public function addParserOutputNoText( &$parserOutput ) {
		$this->mLanguageLinks += $parserOutput->getLanguageLinks();
		$this->addCategoryLinks( $parserOutput->getCategories() );
		$this->mNewSectionLink = $parserOutput->getNewSection();
		$this->addKeywords( $parserOutput );
		if ( $parserOutput->getCacheTime() == -1 ) {
			$this->enableClientCache( false );
		}
		if ( $parserOutput->mHTMLtitle != "" ) {
			$this->mPagetitle = $parserOutput->mHTMLtitle ;
		}
		if ( $parserOutput->mSubtitle != '' ) {
			$this->mSubtitle .= $parserOutput->mSubtitle ;
		}
		$this->mNoGallery = $parserOutput->getNoGallery();
		$this->mHeadItems = array_merge( $this->mHeadItems, (array)$parserOutput->mHeadItems );
		// Versioning...
		$this->mTemplateIds += (array)$parserOutput->mTemplateIds;
		
		wfRunHooks( 'OutputPageParserOutput', array( &$this, $parserOutput ) );
	}

	/**
	 * @todo document
	 * @param ParserOutput &$parserOutput
	 */
	function addParserOutput( &$parserOutput ) {
		$this->addParserOutputNoText( $parserOutput );
		$text =	$parserOutput->getText();
		wfRunHooks( 'OutputPageBeforeHTML',array( &$this, &$text ) );
		$this->addHTML( $text );
	}

	/**
	 * Add wikitext to the buffer, assuming that this is the primary text for a page view
	 * Saves the text into the parser cache if possible.
	 *
	 * @param string  $text
	 * @param Article $article
	 * @param bool    $cache
	 * @deprecated Use Article::outputWikitext
	 */
	public function addPrimaryWikiText( $text, $article, $cache = true ) {
		global $wgParser, $wgUser;

		$popts = $this->parserOptions();
		$popts->setTidy(true);
		$parserOutput = $wgParser->parse( $text, $article->mTitle,
			$popts, true, true, $this->mRevisionId );
		$popts->setTidy(false);
		if ( $cache && $article && $parserOutput->getCacheTime() != -1 ) {
			$parserCache =& ParserCache::singleton();
			$parserCache->save( $parserOutput, $article, $wgUser );
		}

		$this->addParserOutput( $parserOutput );
	}

	/**
	 * @deprecated use addWikiTextTidy()
	 */
	public function addSecondaryWikiText( $text, $linestart = true ) {
		global $wgTitle;
		$this->addWikiTextTitleTidy($text, $wgTitle, $linestart);
	}

	/**
	 * Add wikitext with tidy enabled
	 */
	public function addWikiTextTidy(  $text, $linestart = true ) {
		global $wgTitle;
		$this->addWikiTextTitleTidy($text, $wgTitle, $linestart);
	}


	/**
	 * Add the output of a QuickTemplate to the output buffer
	 *
	 * @param QuickTemplate $template
	 */
	public function addTemplate( &$template ) {
		ob_start();
		$template->execute();
		$this->addHTML( ob_get_contents() );
		ob_end_clean();
	}

	/**
	 * Parse wikitext and return the HTML.
	 *
	 * @param string $text
	 * @param bool   $linestart Is this the start of a line?
	 * @param bool   $interface ??
	 */
	public function parse( $text, $linestart = true, $interface = false ) {
		global $wgParser, $wgTitle;
		$popts = $this->parserOptions();
		if ( $interface) { $popts->setInterfaceMessage(true); }
		$parserOutput = $wgParser->parse( $text, $wgTitle, $popts,
			$linestart, true, $this->mRevisionId );
		if ( $interface) { $popts->setInterfaceMessage(false); }
		return $parserOutput->getText();
	}

	/**
	 * @param Article $article
	 * @param User    $user
	 *
	 * @return bool True if successful, else false.
	 */
	public function tryParserCache( &$article, $user ) {
		$parserCache =& ParserCache::singleton();
		$parserOutput = $parserCache->get( $article, $user );
		if ( $parserOutput !== false ) {
			$this->addParserOutput( $parserOutput );
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @param int $maxage Maximum cache time on the Squid, in seconds.
	 */
	public function setSquidMaxage( $maxage ) {
		$this->mSquidMaxage = $maxage;
	}

	/**
	 * Use enableClientCache(false) to force it to send nocache headers
	 * @param $state ??
	 */
	public function enableClientCache( $state ) {
		return wfSetVar( $this->mEnableClientCache, $state );
	}

	function uncacheableBecauseRequestvars() {
		global $wgRequest;
		return	$wgRequest->getText('useskin', false) === false
			&& $wgRequest->getText('uselang', false) === false;
	}

	public function sendCacheControl() {
		global $wgUseSquid, $wgUseESI, $wgUseETag, $wgSquidMaxage, $wgRequest;
		$fname = 'OutputPage::sendCacheControl';

		if ($wgUseETag && $this->mETag)
			$wgRequest->response()->header("ETag: $this->mETag");

		# don't serve compressed data to clients who can't handle it
		# maintain different caches for logged-in users and non-logged in ones
		$wgRequest->response()->header( 'Vary: Accept-Encoding, Cookie' );
		if( !$this->uncacheableBecauseRequestvars() && $this->mEnableClientCache ) {
			if( $wgUseSquid && session_id() == '' &&
			  ! $this->isPrintable() && $this->mSquidMaxage != 0 )
			{
				if ( $wgUseESI ) {
					# We'll purge the proxy cache explicitly, but require end user agents
					# to revalidate against the proxy on each visit.
					# Surrogate-Control controls our Squid, Cache-Control downstream caches
					wfDebug( "$fname: proxy caching with ESI; {$this->mLastModified} **\n", false );
					# start with a shorter timeout for initial testing
					# header( 'Surrogate-Control: max-age=2678400+2678400, content="ESI/1.0"');
					$wgRequest->response()->header( 'Surrogate-Control: max-age='.$wgSquidMaxage.'+'.$this->mSquidMaxage.', content="ESI/1.0"');
					$wgRequest->response()->header( 'Cache-Control: s-maxage=0, must-revalidate, max-age=0' );
				} else {
					# We'll purge the proxy cache for anons explicitly, but require end user agents
					# to revalidate against the proxy on each visit.
					# IMPORTANT! The Squid needs to replace the Cache-Control header with
					# Cache-Control: s-maxage=0, must-revalidate, max-age=0
					wfDebug( "$fname: local proxy caching; {$this->mLastModified} **\n", false );
					# start with a shorter timeout for initial testing
					# header( "Cache-Control: s-maxage=2678400, must-revalidate, max-age=0" );
					$wgRequest->response()->header( 'Cache-Control: s-maxage='.$this->mSquidMaxage.', must-revalidate, max-age=0' );
				}
			} else {
				# We do want clients to cache if they can, but they *must* check for updates
				# on revisiting the page.
				wfDebug( "$fname: private caching; {$this->mLastModified} **\n", false );
				$wgRequest->response()->header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', 0 ) . ' GMT' );
				$wgRequest->response()->header( "Cache-Control: private, must-revalidate, max-age=0" );
			}
			if($this->mLastModified) $wgRequest->response()->header( "Last-modified: {$this->mLastModified}" );
		} else {
			wfDebug( "$fname: no caching **\n", false );

			# In general, the absence of a last modified header should be enough to prevent
			# the client from using its cache. We send a few other things just to make sure.
			$wgRequest->response()->header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', 0 ) . ' GMT' );
			$wgRequest->response()->header( 'Cache-Control: no-cache, no-store, max-age=0, must-revalidate' );
			$wgRequest->response()->header( 'Pragma: no-cache' );
		}
	}

	/**
	 * Finally, all the text has been munged and accumulated into
	 * the object, let's actually output it:
	 */
	public function output() {
		global $wgUser, $wgOutputEncoding, $wgRequest;
		global $wgContLanguageCode, $wgDebugRedirects, $wgMimeType;
		global $wgJsMimeType, $wgStylePath, $wgUseAjax, $wgAjaxSearch, $wgAjaxWatch;
		global $wgServer, $wgStyleVersion;

		if( $this->mDoNothing ){
			return;
		}
		$fname = 'OutputPage::output';
		wfProfileIn( $fname );
		$sk = $wgUser->getSkin();

		if ( $wgUseAjax ) {
			$this->addScript( "<script type=\"{$wgJsMimeType}\" src=\"{$wgStylePath}/common/ajax.js?$wgStyleVersion\"></script>\n" );

			wfRunHooks( 'AjaxAddScript', array( &$this ) );

			if( $wgAjaxSearch ) {
				$this->addScript( "<script type=\"{$wgJsMimeType}\" src=\"{$wgStylePath}/common/ajaxsearch.js?$wgStyleVersion\"></script>\n" );
				$this->addScript( "<script type=\"{$wgJsMimeType}\">hookEvent(\"load\", sajax_onload);</script>\n" );
			}

			if( $wgAjaxWatch && $wgUser->isLoggedIn() ) {
				$this->addScript( "<script type=\"{$wgJsMimeType}\" src=\"{$wgStylePath}/common/ajaxwatch.js?$wgStyleVersion\"></script>\n" );
			}
		}

		if ( '' != $this->mRedirect ) {
			if( substr( $this->mRedirect, 0, 4 ) != 'http' ) {
				# Standards require redirect URLs to be absolute
				global $wgServer;
				$this->mRedirect = $wgServer . $this->mRedirect;
			}
			if( $this->mRedirectCode == '301') {
				if( !$wgDebugRedirects ) {
					$wgRequest->response()->header("HTTP/1.1 {$this->mRedirectCode} Moved Permanently");
				}
				$this->mLastModified = wfTimestamp( TS_RFC2822 );
			}

			$this->sendCacheControl();

			$wgRequest->response()->header("Content-Type: text/html; charset=utf-8");
			if( $wgDebugRedirects ) {
				$url = htmlspecialchars( $this->mRedirect );
				print "<html>\n<head>\n<title>Redirect</title>\n</head>\n<body>\n";
				print "<p>Location: <a href=\"$url\">$url</a></p>\n";
				print "</body>\n</html>\n";
			} else {
				$wgRequest->response()->header( 'Location: '.$this->mRedirect );
			}
			wfProfileOut( $fname );
			return;
		}
		elseif ( $this->mStatusCode )
		{
			$statusMessage = array(
				100 => 'Continue',
				101 => 'Switching Protocols',
				102 => 'Processing',
				200 => 'OK',
				201 => 'Created',
				202 => 'Accepted',
				203 => 'Non-Authoritative Information',
				204 => 'No Content',
				205 => 'Reset Content',
				206 => 'Partial Content',
				207 => 'Multi-Status',
				300 => 'Multiple Choices',
				301 => 'Moved Permanently',
				302 => 'Found',
				303 => 'See Other',
				304 => 'Not Modified',
				305 => 'Use Proxy',
				307 => 'Temporary Redirect',
				400 => 'Bad Request',
				401 => 'Unauthorized',
				402 => 'Payment Required',
				403 => 'Forbidden',
				404 => 'Not Found',
				405 => 'Method Not Allowed',
				406 => 'Not Acceptable',
				407 => 'Proxy Authentication Required',
				408 => 'Request Timeout',
				409 => 'Conflict',
				410 => 'Gone',
				411 => 'Length Required',
				412 => 'Precondition Failed',
				413 => 'Request Entity Too Large',
				414 => 'Request-URI Too Large',
				415 => 'Unsupported Media Type',
				416 => 'Request Range Not Satisfiable',
				417 => 'Expectation Failed',
				422 => 'Unprocessable Entity',
				423 => 'Locked',
				424 => 'Failed Dependency',
				500 => 'Internal Server Error',
				501 => 'Not Implemented',
				502 => 'Bad Gateway',
				503 => 'Service Unavailable',
				504 => 'Gateway Timeout',
				505 => 'HTTP Version Not Supported',
				507 => 'Insufficient Storage'
			);

			if ( $statusMessage[$this->mStatusCode] )
				$wgRequest->response()->header( 'HTTP/1.1 ' . $this->mStatusCode . ' ' . $statusMessage[$this->mStatusCode] );
		}

		# Buffer output; final headers may depend on later processing
		ob_start();

		# Disable temporary placeholders, so that the skin produces HTML
		$sk->postParseLinkColour( false );

		$wgRequest->response()->header( "Content-type: $wgMimeType; charset={$wgOutputEncoding}" );
		$wgRequest->response()->header( 'Content-language: '.$wgContLanguageCode );

		if ($this->mArticleBodyOnly) {
			$this->out($this->mBodytext);
		} else {
			wfProfileIn( 'Output-skin' );
			$sk->outputPage( $this );
			wfProfileOut( 'Output-skin' );
		}

		$this->sendCacheControl();
		ob_end_flush();
		wfProfileOut( $fname );
	}

	/**
	 * @todo document
	 * @param string $ins
	 */
	public function out( $ins ) {
		global $wgInputEncoding, $wgOutputEncoding, $wgContLang;
		if ( 0 == strcmp( $wgInputEncoding, $wgOutputEncoding ) ) {
			$outs = $ins;
		} else {
			$outs = $wgContLang->iconv( $wgInputEncoding, $wgOutputEncoding, $ins );
			if ( false === $outs ) { $outs = $ins; }
		}
		print $outs;
	}

	/**
	 * @todo document
	 */
	public static function setEncodings() {
		global $wgInputEncoding, $wgOutputEncoding;
		global $wgUser, $wgContLang;

		$wgInputEncoding = strtolower( $wgInputEncoding );

		if ( empty( $_SERVER['HTTP_ACCEPT_CHARSET'] ) ) {
			$wgOutputEncoding = strtolower( $wgOutputEncoding );
			return;
		}
		$wgOutputEncoding = $wgInputEncoding;
	}

	/**
	 * Deprecated, use wfReportTime() instead.
	 * @return string
	 * @deprecated
	 */
	public function reportTime() {
		$time = wfReportTime();
		return $time;
	}

	/**
	 * Produce a "user is blocked" page.
	 *
	 * @param bool $return Whether to have a "return to $wgTitle" message or not.
	 * @return nothing
	 */
	function blockedPage( $return = true ) {
		global $wgUser, $wgContLang, $wgTitle, $wgLang;

		$this->setPageTitle( wfMsg( 'blockedtitle' ) );
		$this->setRobotpolicy( 'noindex,nofollow' );
		$this->setArticleRelated( false );

		$id = $wgUser->blockedBy();
		$reason = $wgUser->blockedFor();
		$ip = wfGetIP();

		if ( is_numeric( $id ) ) {
			$name = User::whoIs( $id );
		} else {
			$name = $id;
		}
		$link = '[[' . $wgContLang->getNsText( NS_USER ) . ":{$name}|{$name}]]";

		$blockid = $wgUser->mBlock->mId;

		$blockExpiry = $wgUser->mBlock->mExpiry;
		if ( $blockExpiry == 'infinity' ) {
			// Entry in database (table ipblocks) is 'infinity' but 'ipboptions' uses 'infinite' or 'indefinite'
			// Search for localization in 'ipboptions'
			$scBlockExpiryOptions = wfMsg( 'ipboptions' );
			foreach ( explode( ',', $scBlockExpiryOptions ) as $option ) {
				if ( strpos( $option, ":" ) === false )
					continue;
				list( $show, $value ) = explode( ":", $option );
				if ( $value == 'infinite' || $value == 'indefinite' ) {
					$blockExpiry = $show;
					break;
				}
			}
		} else {
			$blockExpiry = $wgLang->timeanddate( wfTimestamp( TS_MW, $blockExpiry ), true );
		}

		if ( $wgUser->mBlock->mAuto ) {
			$msg = 'autoblockedtext';
		} else {
			$msg = 'blockedtext';
		}

		/* $ip returns who *is* being blocked, $intended contains who was meant to be blocked.
		 * This could be a username, an ip range, or a single ip. */
		$intended = $wgUser->mBlock->mAddress;

		$this->addWikiText( wfMsg( $msg, $link, $reason, $ip, $name, $blockid, $blockExpiry, $intended ) );
		
		# Don't auto-return to special pages
		if( $return ) {
			$return = $wgTitle->getNamespace() > -1 ? $wgTitle->getPrefixedText() : NULL;
			$this->returnToMain( false, $return );
		}
	}

	/**
	 * Output a standard error page
	 *
	 * @param string $title Message key for page title
	 * @param string $msg Message key for page text
	 * @param array $params Message parameters
	 */
	public function showErrorPage( $title, $msg, $params = array() ) {
		global $wgTitle;

		$this->mDebugtext .= 'Original title: ' .
		  $wgTitle->getPrefixedText() . "\n";
		$this->setPageTitle( wfMsg( $title ) );
		$this->setHTMLTitle( wfMsg( 'errorpagetitle' ) );
		$this->setRobotpolicy( 'noindex,nofollow' );
		$this->setArticleRelated( false );
		$this->enableClientCache( false );
		$this->mRedirect = '';
		$this->mBodytext = '';
		
		array_unshift( $params, $msg );
		$message = call_user_func_array( 'wfMsg', $params );
		$this->addWikiText( $message );
		
		$this->returnToMain( false );
	}

	/** @deprecated */
	public function errorpage( $title, $msg ) {
		throw new ErrorPageError( $title, $msg );
	}
		
	/**
	 * Display an error page indicating that a given version of MediaWiki is
	 * required to use it
	 *
	 * @param mixed $version The version of MediaWiki needed to use the page
	 */
	public function versionRequired( $version ) {
		$this->setPageTitle( wfMsg( 'versionrequired', $version ) );
		$this->setHTMLTitle( wfMsg( 'versionrequired', $version ) );
		$this->setRobotpolicy( 'noindex,nofollow' );
		$this->setArticleRelated( false );
		$this->mBodytext = '';

		$this->addWikiText( wfMsg( 'versionrequiredtext', $version ) );
		$this->returnToMain();
	}

	/**
	 * Display an error page noting that a given permission bit is required.
	 *
	 * @param string $permission key required
	 */
	public function permissionRequired( $permission ) {
		global $wgGroupPermissions, $wgUser;

		$this->setPageTitle( wfMsg( 'badaccess' ) );
		$this->setHTMLTitle( wfMsg( 'errorpagetitle' ) );
		$this->setRobotpolicy( 'noindex,nofollow' );
		$this->setArticleRelated( false );
		$this->mBodytext = '';

		$groups = array();
		foreach( $wgGroupPermissions as $key => $value ) {
			if( isset( $value[$permission] ) && $value[$permission] == true ) {
				$groupName = User::getGroupName( $key );
				$groupPage = User::getGroupPage( $key );
				if( $groupPage ) {
					$skin = $wgUser->getSkin();
					$groups[] = $skin->makeLinkObj( $groupPage, $groupName );
				} else {
					$groups[] = $groupName;
				}
			}
		}
		$n = count( $groups );
		$groups = implode( ', ', $groups );
		switch( $n ) {
			case 0:
			case 1:
			case 2:
				$message = wfMsgHtml( "badaccess-group$n", $groups );
				break;
			default:
				$message = wfMsgHtml( 'badaccess-groups', $groups );
		}
		$this->addHtml( $message );
		$this->returnToMain( false );
	}

	/**
	 * Use permissionRequired.
	 * @deprecated
	 */
	public function sysopRequired() {
		throw new MWException( "Call to deprecated OutputPage::sysopRequired() method\n" );
	}

	/**
	 * Use permissionRequired.
	 * @deprecated
	 */
	public function developerRequired() {
		throw new MWException( "Call to deprecated OutputPage::developerRequired() method\n" );
	}

	/**
	 * Produce the stock "please login to use the wiki" page
	 */
	public function loginToUse() {
		global $wgUser, $wgTitle, $wgContLang;

		if( $wgUser->isLoggedIn() ) {
			$this->permissionRequired( 'read' );
			return;
		}

		$skin = $wgUser->getSkin();
		
		$this->setPageTitle( wfMsg( 'loginreqtitle' ) );
		$this->setHtmlTitle( wfMsg( 'errorpagetitle' ) );
		$this->setRobotPolicy( 'noindex,nofollow' );
		$this->setArticleFlag( false );
		
		$loginTitle = SpecialPage::getTitleFor( 'Userlogin' );
		$loginLink = $skin->makeKnownLinkObj( $loginTitle, wfMsgHtml( 'loginreqlink' ), 'returnto=' . $wgTitle->getPrefixedUrl() );
		$this->addHtml( wfMsgWikiHtml( 'loginreqpagetext', $loginLink ) );
		$this->addHtml( "\n<!--" . $wgTitle->getPrefixedUrl() . "-->" );
		
		# Don't return to the main page if the user can't read it
		# otherwise we'll end up in a pointless loop
		$mainPage = Title::newMainPage();
		if( $mainPage->userCanRead() )
			$this->returnToMain( true, $mainPage );
	}

	/** @deprecated */
	public function databaseError( $fname, $sql, $error, $errno ) {
		throw new MWException( "OutputPage::databaseError is obsolete\n" );
	}

	/**
	 * @todo document
	 * @param bool  $protected Is the reason the page can't be reached because it's protected?
	 * @param mixed $source
	 */
	public function readOnlyPage( $source = null, $protected = false ) {
		global $wgUser, $wgReadOnlyFile, $wgReadOnly, $wgTitle;
		$skin = $wgUser->getSkin();

		$this->setRobotpolicy( 'noindex,nofollow' );
		$this->setArticleRelated( false );

		if( $protected ) {
			$this->setPageTitle( wfMsg( 'viewsource' ) );
			$this->setSubtitle( wfMsg( 'viewsourcefor', $skin->makeKnownLinkObj( $wgTitle ) ) );

			list( $cascadeSources, /* $restrictions */ ) = $wgTitle->getCascadeProtectionSources();

			# Determine if protection is due to the page being a system message
			# and show an appropriate explanation
			if( $wgTitle->getNamespace() == NS_MEDIAWIKI ) {
				$this->addWikiText( wfMsg( 'protectedinterface' ) );
			} if ( $cascadeSources && count($cascadeSources) > 0 ) {
				$titles = '';
	
				foreach ( $cascadeSources as $title ) {
					$titles .= '* [[:' . $title->getPrefixedText() . "]]\n";
				}

				$notice = wfMsgExt( 'cascadeprotected', array('parsemag'), count($cascadeSources) ) . "\n$titles";

				$this->addWikiText( $notice );
			} else {
				$this->addWikiText( wfMsg( 'protectedpagetext' ) );
			}
		} else {
			$this->setPageTitle( wfMsg( 'readonly' ) );
			if ( $wgReadOnly ) {
				$reason = $wgReadOnly;
			} else {
				$reason = file_get_contents( $wgReadOnlyFile );
			}
			$this->addWikiText( wfMsg( 'readonlytext', $reason ) );
		}

		if( is_string( $source ) ) {
			$this->addWikiText( wfMsg( 'viewsourcetext' ) );
			$rows = $wgUser->getIntOption( 'rows' );
			$cols = $wgUser->getIntOption( 'cols' );
			$text = "\n<textarea name='wpTextbox1' id='wpTextbox1' cols='$cols' rows='$rows' readonly='readonly'>" .
				htmlspecialchars( $source ) . "\n</textarea>";
			$this->addHTML( $text );
		}
		$article = new Article($wgTitle);
		$this->addHTML( $skin->formatTemplates($article->getUsedTemplates()) );

		$this->returnToMain( false );
	}

	/** @deprecated */
	public function fatalError( $message ) {
		throw new FatalError( $message ); 
	}
	
	/** @deprecated */
	public function unexpectedValueError( $name, $val ) {
		throw new FatalError( wfMsg( 'unexpected', $name, $val ) );
	}

	/** @deprecated */
	public function fileCopyError( $old, $new ) {
		throw new FatalError( wfMsg( 'filecopyerror', $old, $new ) );
	}

	/** @deprecated */
	public function fileRenameError( $old, $new ) {
		throw new FatalError( wfMsg( 'filerenameerror', $old, $new ) );
	}

	/** @deprecated */
	public function fileDeleteError( $name ) {
		throw new FatalError( wfMsg( 'filedeleteerror', $name ) );
	}

	/** @deprecated */
	public function fileNotFoundError( $name ) {
		throw new FatalError( wfMsg( 'filenotfound', $name ) );
	}

	public function showFatalError( $message ) {
		$this->setPageTitle( wfMsg( "internalerror" ) );
		$this->setRobotpolicy( "noindex,nofollow" );
		$this->setArticleRelated( false );
		$this->enableClientCache( false );
		$this->mRedirect = '';
		$this->mBodytext = $message;
	}

	public function showUnexpectedValueError( $name, $val ) {
		$this->showFatalError( wfMsg( 'unexpected', $name, $val ) );
	}

	public function showFileCopyError( $old, $new ) {
		$this->showFatalError( wfMsg( 'filecopyerror', $old, $new ) );
	}

	public function showFileRenameError( $old, $new ) {
		$this->showFatalError( wfMsg( 'filerenameerror', $old, $new ) );
	}

	public function showFileDeleteError( $name ) {
		$this->showFatalError( wfMsg( 'filedeleteerror', $name ) );
	}

	public function showFileNotFoundError( $name ) {
		$this->showFatalError( wfMsg( 'filenotfound', $name ) );
	}

	/**
	 * return from error messages or notes
	 * @param $auto automatically redirect the user after 10 seconds
	 * @param $returnto page title to return to. Default is Main Page.
	 */
	public function returnToMain( $auto = true, $returnto = NULL ) {
		global $wgUser, $wgOut, $wgRequest;
		
		if ( $returnto == NULL ) {
			$returnto = $wgRequest->getText( 'returnto' );
		}
		
		if ( '' === $returnto ) {
			$returnto = Title::newMainPage();
		}

		if ( is_object( $returnto ) ) {
			$titleObj = $returnto;
		} else {
			$titleObj = Title::newFromText( $returnto );
		}
		if ( !is_object( $titleObj ) ) {
			$titleObj = Title::newMainPage();
		}

		$sk = $wgUser->getSkin();
		$link = $sk->makeLinkObj( $titleObj, '' );

		$r = wfMsg( 'returnto', $link );
		if ( $auto ) {
			$wgOut->addMeta( 'http:Refresh', '10;url=' . $titleObj->escapeFullURL() );
		}
		$wgOut->addHTML( "\n<p>$r</p>\n" );
	}

	/**
	 * This function takes the title (first item of mGoodLinks), categories, existing and broken links for the page
	 * and uses the first 10 of them for META keywords
	 *
	 * @param ParserOutput &$parserOutput
	 */
	private function addKeywords( &$parserOutput ) {
		global $wgTitle;
		$this->addKeyword( $wgTitle->getPrefixedText() );
		$count = 1;
		$links2d =& $parserOutput->getLinks();
		if ( !is_array( $links2d ) ) {
			return;
		}
		foreach ( $links2d as $dbkeys ) {
			foreach( $dbkeys as $dbkey => $unused ) {
				$this->addKeyword( $dbkey );
				if ( ++$count > 10 ) {
					break 2;
				}
			}
		}
	}

	/**
	 * @return string The doctype, opening <html>, and head element.
	 */
	public function headElement() {
		global $wgDocType, $wgDTD, $wgContLanguageCode, $wgOutputEncoding, $wgMimeType;
		global $wgXhtmlDefaultNamespace, $wgXhtmlNamespaces;
		global $wgUser, $wgContLang, $wgUseTrackbacks, $wgTitle, $wgStyleVersion;

		if( $wgMimeType == 'text/xml' || $wgMimeType == 'application/xhtml+xml' || $wgMimeType == 'application/xml' ) {
			$ret = "<?xml version=\"1.0\" encoding=\"$wgOutputEncoding\" ?>\n";
		} else {
			$ret = '';
		}

		$ret .= "<!DOCTYPE html PUBLIC \"$wgDocType\"\n        \"$wgDTD\">\n";

		if ( '' == $this->getHTMLTitle() ) {
			$this->setHTMLTitle(  wfMsg( 'pagetitle', $this->getPageTitle() ));
		}

		$rtl = $wgContLang->isRTL() ? " dir='RTL'" : '';
		$ret .= "<html xmlns=\"{$wgXhtmlDefaultNamespace}\" ";
		foreach($wgXhtmlNamespaces as $tag => $ns) {
			$ret .= "xmlns:{$tag}=\"{$ns}\" ";
		}
		$ret .= "xml:lang=\"$wgContLanguageCode\" lang=\"$wgContLanguageCode\" $rtl>\n";
		$ret .= "<head>\n<title>" . htmlspecialchars( $this->getHTMLTitle() ) . "</title>\n";
		array_push( $this->mMetatags, array( "http:Content-type", "$wgMimeType; charset={$wgOutputEncoding}" ) );

		$ret .= $this->getHeadLinks();
		global $wgStylePath;
		if( $this->isPrintable() ) {
			$media = '';
		} else {
			$media = "media='print'";
		}
		$printsheet = htmlspecialchars( "$wgStylePath/common/wikiprintable.css?$wgStyleVersion" );
		$ret .= "<link rel='stylesheet' type='text/css' $media href='$printsheet' />\n";

		$sk = $wgUser->getSkin();
		$ret .= $sk->getHeadScripts( $this->mAllowUserJs );
		$ret .= $this->mScripts;
		$ret .= $sk->getUserStyles();
		$ret .= $this->getHeadItems();

		if ($wgUseTrackbacks && $this->isArticleRelated())
			$ret .= $wgTitle->trackbackRDF();

		$ret .= "</head>\n";
		return $ret;
	}

	/**
	 * @return string HTML tag links to be put in the header.
	 */
	public function getHeadLinks() {
		global $wgRequest;
		$ret = '';
		foreach ( $this->mMetatags as $tag ) {
			if ( 0 == strcasecmp( 'http:', substr( $tag[0], 0, 5 ) ) ) {
				$a = 'http-equiv';
				$tag[0] = substr( $tag[0], 5 );
			} else {
				$a = 'name';
			}
			$ret .= "<meta $a=\"{$tag[0]}\" content=\"{$tag[1]}\" />\n";
		}

		$p = $this->mRobotpolicy;
		if( $p !== '' && $p != 'index,follow' ) {
			// http://www.robotstxt.org/wc/meta-user.html
			// Only show if it's different from the default robots policy
			$ret .= "<meta name=\"robots\" content=\"$p\" />\n";
		}

		if ( count( $this->mKeywords ) > 0 ) {
			$strip = array(
				"/<.*?>/" => '',
				"/_/" => ' '
			);
			$ret .= "\t\t<meta name=\"keywords\" content=\"" .
			  htmlspecialchars(preg_replace(array_keys($strip), array_values($strip),implode( ",", $this->mKeywords ))) . "\" />\n";
		}
		foreach ( $this->mLinktags as $tag ) {
			$ret .= "\t\t<link";
			foreach( $tag as $attr => $val ) {
				$ret .= " $attr=\"" . htmlspecialchars( $val ) . "\"";
			}
			$ret .= " />\n";
		}
		if( $this->isSyndicated() ) {
			# FIXME: centralize the mime-type and name information in Feed.php
			$link = $wgRequest->escapeAppendQuery( 'feed=rss' );
			$ret .= "<link rel='alternate' type='application/rss+xml' title='RSS 2.0' href='$link' />\n";
			$link = $wgRequest->escapeAppendQuery( 'feed=atom' );
			$ret .= "<link rel='alternate' type='application/atom+xml' title='Atom 1.0' href='$link' />\n";
		}

		return $ret;
	}

	/**
	 * Turn off regular page output and return an error reponse
	 * for when rate limiting has triggered.
	 * @todo i18n
	 */
	public function rateLimited() {
		global $wgOut;
		$wgOut->disable();
		wfHttpError( 500, 'Internal Server Error',
			'Sorry, the server has encountered an internal error. ' .
			'Please wait a moment and hit "refresh" to submit the request again.' );
	}
	
	/**
	 * Show an "add new section" link?
	 *
	 * @return bool True if the parser output instructs us to add one
	 */
	public function showNewSectionLink() {
		return $this->mNewSectionLink;
	}
	
	/**
	 * Show a warning about slave lag
	 *
	 * If the lag is higher than 30 seconds, then the warning is
	 * a bit more obvious
	 *
	 * @param int $lag Slave lag
	 */
	public function showLagWarning( $lag ) {
		global $wgSlaveLagWarning, $wgSlaveLagCritical;
		
		if ($lag < $wgSlaveLagWarning)
			return;

		$message = ($lag >= $wgSlaveLagCritical) ? 'lag-warn-high' : 'lag-warn-normal';
		$warning = wfMsgHtml( $message, htmlspecialchars( $lag ) );
		$this->addHtml( "<div class=\"mw-{$message}\">\n{$warning}\n</div>\n" );
	}
	
}

?>
