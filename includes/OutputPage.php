<?php
/**
 * @package MediaWiki
 */

/**
 * This is not a valid entry point, perform no further processing unless MEDIAWIKI is defined
 */
if( defined( 'MEDIAWIKI' ) ) {

# See design.txt

if($wgUseTeX) require_once( 'Math.php' );

/**
 * @todo document
 * @package MediaWiki
 */
class OutputPage {
	var $mHeaders, $mMetatags, $mKeywords;
	var $mLinktags, $mPagetitle, $mBodytext, $mDebugtext;
	var $mHTMLtitle, $mRobotpolicy, $mIsarticle, $mPrintable;
	var $mSubtitle, $mRedirect, $mStatusCode;
	var $mLastModified, $mETag, $mCategoryLinks;
	var $mScripts, $mLinkColours;

	var $mSuppressQuickbar;
	var $mOnloadHandler;
	var $mDoNothing;
	var $mContainsOldMagic, $mContainsNewMagic;
	var $mIsArticleRelated;
	var $mParserOptions;
	var $mShowFeedLinks = false;
	var $mEnableClientCache = true;
	var $mArticleBodyOnly = false;

	/**
	 * Constructor
	 * Initialise private variables
	 */
	function OutputPage() {
		$this->mHeaders = $this->mMetatags =
		$this->mKeywords = $this->mLinktags = array();
		$this->mHTMLtitle = $this->mPagetitle = $this->mBodytext =
		$this->mRedirect = $this->mLastModified =
		$this->mSubtitle = $this->mDebugtext = $this->mRobotpolicy =
		$this->mOnloadHandler = '';
		$this->mIsArticleRelated = $this->mIsarticle = $this->mPrintable = true;
		$this->mSuppressQuickbar = $this->mPrintable = false;
		$this->mLanguageLinks = array();
		$this->mCategoryLinks = array() ;
		$this->mDoNothing = false;
		$this->mContainsOldMagic = $this->mContainsNewMagic = 0;
		$this->mParserOptions = ParserOptions::newFromUser( $temp = NULL );
		$this->mSquidMaxage = 0;
		$this->mScripts = '';
		$this->mETag = false;
		$this->mRevisionId = null;
	}

	function addHeader( $name, $val ) { array_push( $this->mHeaders, $name.': '.$val ) ; }
	function redirect( $url, $responsecode = '302' ) { $this->mRedirect = $url; $this->mRedirectCode = $responsecode; }
	function setStatusCode( $statusCode ) { $this->mStatusCode = $statusCode; }
	
	# To add an http-equiv meta tag, precede the name with "http:"
	function addMeta( $name, $val ) { array_push( $this->mMetatags, array( $name, $val ) ); }
	function addKeyword( $text ) { array_push( $this->mKeywords, $text ); }
	function addScript( $script ) { $this->mScripts .= $script; }
	function getScript() { return $this->mScripts; }

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
	 * any future call to OutputPage->output() have no effect. The method
	 * returns true iff cache-ok headers was sent.
	 */
	function checkLastModified ( $timestamp ) {
		global $wgCachePages, $wgUser;
		if ( !$timestamp || $timestamp == '19700101000000' ) {
			wfDebug( "CACHE DISABLED, NO TIMESTAMP\n" );
			return;
		}
		if( !$wgCachePages ) {
			wfDebug( "CACHE DISABLED\n", false );
			return;
		}
		if( $wgUser->getOption( 'nocache' ) ) {
			wfDebug( "USER DISABLED CACHE\n", false );
			return;
		}

		$timestamp=wfTimestamp(TS_MW,$timestamp);
		$lastmod = wfTimestamp( TS_RFC2822, max( $timestamp, $wgUser->mTouched ) );

		if( !empty( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) ) {
			# IE sends sizes after the date like this:
			# Wed, 20 Aug 2003 06:51:19 GMT; length=5202
			# this breaks strtotime().
			$modsince = preg_replace( '/;.*$/', '', $_SERVER["HTTP_IF_MODIFIED_SINCE"] );
			$modsinceTime = strtotime( $modsince );
			$ismodsince = wfTimestamp( TS_MW, $modsinceTime ? $modsinceTime : 1 );
			wfDebug( "-- client send If-Modified-Since: " . $modsince . "\n", false );
			wfDebug( "--  we might send Last-Modified : $lastmod\n", false );
			if( ($ismodsince >= $timestamp ) && $wgUser->validateCache( $ismodsince ) ) {
				# Make sure you're in a place you can leave when you call us!
				header( "HTTP/1.0 304 Not Modified" );
				$this->mLastModified = $lastmod;
				$this->sendCacheControl();
				wfDebug( "CACHED client: $ismodsince ; user: $wgUser->mTouched ; page: $timestamp\n", false );
				$this->disable();
				@ob_end_clean(); // Don't output compressed blob
				return true;
			} else {
				wfDebug( "READY  client: $ismodsince ; user: $wgUser->mTouched ; page: $timestamp\n", false );
				$this->mLastModified = $lastmod;
			}
		} else {
			wfDebug( "client did not send If-Modified-Since header\n", false );
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

	function setRobotpolicy( $str ) { $this->mRobotpolicy = $str; }
	function setHTMLTitle( $name ) {$this->mHTMLtitle = $name; }
	function setPageTitle( $name ) {
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
	function getHTMLTitle() { return $this->mHTMLtitle; }
	function getPageTitle() { return $this->mPagetitle; }
	function setSubtitle( $str ) { $this->mSubtitle = /*$this->parse(*/$str/*)*/; } // @bug 2514
	function getSubtitle() { return $this->mSubtitle; }
	function isArticle() { return $this->mIsarticle; }
	function setPrintable() { $this->mPrintable = true; }
	function isPrintable() { return $this->mPrintable; }
	function setSyndicated( $show = true ) { $this->mShowFeedLinks = $show; }
	function isSyndicated() { return $this->mShowFeedLinks; }
	function setOnloadHandler( $js ) { $this->mOnloadHandler = $js; }
	function getOnloadHandler() { return $this->mOnloadHandler; }
	function disable() { $this->mDoNothing = true; }

	function setArticleRelated( $v ) {
		$this->mIsArticleRelated = $v;
		if ( !$v ) {
			$this->mIsarticle = false;
		}
	}
	function setArticleFlag( $v ) {
		$this->mIsarticle = $v;
		if ( $v ) {
			$this->mIsArticleRelated = $v;
		}
	}

	function isArticleRelated() { return $this->mIsArticleRelated; }

	function getLanguageLinks() { return $this->mLanguageLinks; }
	function addLanguageLinks($newLinkArray) {
		$this->mLanguageLinks += $newLinkArray;
	}
	function setLanguageLinks($newLinkArray) {
		$this->mLanguageLinks = $newLinkArray;
	}

	function getCategoryLinks() {
		return $this->mCategoryLinks;
	}

	/**
	 * Add an array of categories, with names in the keys
	 */
	function addCategoryLinks($categories) {
		global $wgUser, $wgContLang;

		# Add the links to the link cache in a batch
		$arr = array( NS_CATEGORY => $categories );
		$lb = new LinkBatch;
		$lb->setArray( $arr );
		$lb->execute();

		$sk =& $wgUser->getSkin();
		foreach ( $categories as $category => $arbitrary ) {
			$title = Title::makeTitleSafe( NS_CATEGORY, $category );
			$text = $wgContLang->convertHtml( $title->getText() );
			$this->mCategoryLinks[] = $sk->makeLinkObj( $title, $text );
		}
	}

	function setCategoryLinks($categories) {
		$this->mCategoryLinks = array();
		$this->addCategoryLinks($categories);
	}

	function suppressQuickbar() { $this->mSuppressQuickbar = true; }
	function isQuickbarSuppressed() { return $this->mSuppressQuickbar; }

	function addHTML( $text ) { $this->mBodytext .= $text; }
	function clearHTML() { $this->mBodytext = ''; }
	function getHTML() { return $this->mBodytext; }
	function debug( $text ) { $this->mDebugtext .= $text; }

	/* @deprecated */
	function setParserOptions( $options ) {
		return $this->ParserOptions( $options );
	}

	function ParserOptions( $options = null ) {
		return wfSetVar( $this->mParserOptions, $options );
	}
	
	/**
	 * Set the revision ID which will be seen by the wiki text parser
	 * for things such as embedded {{REVISIONID}} variable use.
	 * @param mixed $revid an integer, or NULL
	 * @return mixed previous value
	 */
	function setRevisionId( $revid ) {
		$val = is_null( $revid ) ? null : intval( $revid );
		return wfSetVar( $this->mRevisionId, $val );
	}

	/**
	 * Convert wikitext to HTML and add it to the buffer
	 * Default assumes that the current page title will
	 * be used.
	 */
	function addWikiText( $text, $linestart = true ) {
		global $wgTitle;
		$this->addWikiTextTitle($text, $wgTitle, $linestart);
	}

	function addWikiTextWithTitle($text, &$title, $linestart = true) {
		$this->addWikiTextTitle($text, $title, $linestart);
	}

	function addWikiTextTitle($text, &$title, $linestart) {
		global $wgParser;
		$parserOutput = $wgParser->parse( $text, $title, $this->mParserOptions,
			$linestart, true, $this->mRevisionId );
		$this->addParserOutput( $parserOutput );
	}

	function addParserOutputNoText( &$parserOutput ) {
		$this->mLanguageLinks += $parserOutput->getLanguageLinks();
		$this->addCategoryLinks( $parserOutput->getCategories() );
		$this->addKeywords( $parserOutput );
		if ( $parserOutput->getCacheTime() == -1 ) {
			$this->enableClientCache( false );
		}
	}
	
	function addParserOutput( &$parserOutput ) {
		$this->addParserOutputNoText( $parserOutput );
		$this->addHTML( $parserOutput->getText() );
	}

	/**
	 * Add wikitext to the buffer, assuming that this is the primary text for a page view
	 * Saves the text into the parser cache if possible
	 */
	function addPrimaryWikiText( $text, $article, $cache = true ) {
		global $wgParser, $wgParserCache, $wgUser;

		$parserOutput = $wgParser->parse( $text, $article->mTitle,
			$this->mParserOptions, true, true, $this->mRevisionId );

		if ( $article && $parserOutput->getCacheTime() != -1 ) {
			$wgParserCache->save( $parserOutput, $article, $wgUser );
		}

		$this->addParserOutput( $parserOutput );
	}

	/**
	 * Add the output of a QuickTemplate to the output buffer
	 * @param QuickTemplate $template
	 */
	function addTemplate( &$template ) {
		ob_start();
		$template->execute();
		$this->addHTML( ob_get_contents() );
		ob_end_clean();
	}

	/**
	 * Parse wikitext and return the HTML. This is for special pages that add the text later
	 */
	function parse( $text, $linestart = true ) {
		global $wgParser, $wgTitle;
		$parserOutput = $wgParser->parse( $text, $wgTitle, $this->mParserOptions,
			$linestart, true, $this->mRevisionId );
		return $parserOutput->getText();
	}

	/**
	 * @param $article
	 * @param $user
	 *
	 * @return bool
	 */
	function tryParserCache( $article, $user ) {
		global $wgParserCache;
		$parserOutput = $wgParserCache->get( $article, $user );
		if ( $parserOutput !== false ) {
			$this->mLanguageLinks += $parserOutput->getLanguageLinks();
			$this->addCategoryLinks( $parserOutput->getCategories() );
			$this->addKeywords( $parserOutput );
			$this->addHTML( $parserOutput->getText() );
			$t = $parserOutput->getTitleText();
			if( !empty( $t ) ) {
				$this->setPageTitle( $t );
 			}
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Set the maximum cache time on the Squid in seconds
	 * @param $maxage
	 */
	function setSquidMaxage( $maxage ) {
		$this->mSquidMaxage = $maxage;
	}

	/**
	 * Use enableClientCache(false) to force it to send nocache headers
	 * @param $state
	 */
	function enableClientCache( $state ) {
		return wfSetVar( $this->mEnableClientCache, $state );
	}

	function uncacheableBecauseRequestvars() {
		global $wgRequest;
		return	$wgRequest->getText('useskin', false) === false
			&& $wgRequest->getText('uselang', false) === false;
	}

	function sendCacheControl() {
		global $wgUseSquid, $wgUseESI;

		if ($this->mETag)
			header("ETag: $this->mETag");

		# don't serve compressed data to clients who can't handle it
		# maintain different caches for logged-in users and non-logged in ones
		header( 'Vary: Accept-Encoding, Cookie' );
		if( !$this->uncacheableBecauseRequestvars() && $this->mEnableClientCache ) {
			if( $wgUseSquid && ! isset( $_COOKIE[ini_get( 'session.name') ] ) &&
			  ! $this->isPrintable() && $this->mSquidMaxage != 0 )
			{
				if ( $wgUseESI ) {
					# We'll purge the proxy cache explicitly, but require end user agents
					# to revalidate against the proxy on each visit.
					# Surrogate-Control controls our Squid, Cache-Control downstream caches
					wfDebug( "** proxy caching with ESI; {$this->mLastModified} **\n", false );
					# start with a shorter timeout for initial testing
					# header( 'Surrogate-Control: max-age=2678400+2678400, content="ESI/1.0"');
					header( 'Surrogate-Control: max-age='.$wgSquidMaxage.'+'.$this->mSquidMaxage.', content="ESI/1.0"');
					header( 'Cache-Control: s-maxage=0, must-revalidate, max-age=0' );
				} else {
					# We'll purge the proxy cache for anons explicitly, but require end user agents
					# to revalidate against the proxy on each visit.
					# IMPORTANT! The Squid needs to replace the Cache-Control header with
					# Cache-Control: s-maxage=0, must-revalidate, max-age=0
					wfDebug( "** local proxy caching; {$this->mLastModified} **\n", false );
					# start with a shorter timeout for initial testing
					# header( "Cache-Control: s-maxage=2678400, must-revalidate, max-age=0" );
					header( 'Cache-Control: s-maxage='.$this->mSquidMaxage.', must-revalidate, max-age=0' );
				}
			} else {
				# We do want clients to cache if they can, but they *must* check for updates
				# on revisiting the page.
				wfDebug( "** private caching; {$this->mLastModified} **\n", false );
				header( "Expires: -1" );
				header( "Cache-Control: private, must-revalidate, max-age=0" );
			}
			if($this->mLastModified) header( "Last-modified: {$this->mLastModified}" );
		} else {
			wfDebug( "** no caching **\n", false );

			# In general, the absence of a last modified header should be enough to prevent
			# the client from using its cache. We send a few other things just to make sure.
			header( 'Expires: -1' );
			header( 'Cache-Control: no-cache, no-store, max-age=0, must-revalidate' );
			header( 'Pragma: no-cache' );
		}
	}

	/**
	 * Finally, all the text has been munged and accumulated into
	 * the object, let's actually output it:
	 */
	function output() {
		global $wgUser, $wgOutputEncoding;
		global $wgContLanguageCode, $wgDebugRedirects, $wgMimeType, $wgProfiler;

		if( $this->mDoNothing ){
			return;
		}
		$fname = 'OutputPage::output';
		wfProfileIn( $fname );
		$sk = $wgUser->getSkin();

		if ( '' != $this->mRedirect ) {
			if( substr( $this->mRedirect, 0, 4 ) != 'http' ) {
				# Standards require redirect URLs to be absolute
				global $wgServer;
				$this->mRedirect = $wgServer . $this->mRedirect;
			}
			if( $this->mRedirectCode == '301') {
				if( !$wgDebugRedirects ) {
					header("HTTP/1.1 {$this->mRedirectCode} Moved Permanently");
				}
				$this->mLastModified = wfTimestamp( TS_RFC2822 );
			}

			$this->sendCacheControl();

			if( $wgDebugRedirects ) {
				$url = htmlspecialchars( $this->mRedirect );
				print "<html>\n<head>\n<title>Redirect</title>\n</head>\n<body>\n";
				print "<p>Location: <a href=\"$url\">$url</a></p>\n";
				print "</body>\n</html>\n";
			} else {
				header( 'Location: '.$this->mRedirect );
			}
			if ( isset( $wgProfiler ) ) { wfDebug( $wgProfiler->getOutput() ); }
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
				header( 'HTTP/1.1 ' . $this->mStatusCode . ' ' . $statusMessage[$this->mStatusCode] );
		}

		# Buffer output; final headers may depend on later processing
		ob_start();

		# Disable temporary placeholders, so that the skin produces HTML
		$sk->postParseLinkColour( false );

		header( "Content-type: $wgMimeType; charset={$wgOutputEncoding}" );
		header( 'Content-language: '.$wgContLanguageCode );

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

	function out( $ins ) {
		global $wgInputEncoding, $wgOutputEncoding, $wgContLang;
		if ( 0 == strcmp( $wgInputEncoding, $wgOutputEncoding ) ) {
			$outs = $ins;
		} else {
			$outs = $wgContLang->iconv( $wgInputEncoding, $wgOutputEncoding, $ins );
			if ( false === $outs ) { $outs = $ins; }
		}
		print $outs;
	}

	function setEncodings() {
		global $wgInputEncoding, $wgOutputEncoding;
		global $wgUser, $wgContLang;

		$wgInputEncoding = strtolower( $wgInputEncoding );

		if( $wgUser->getOption( 'altencoding' ) ) {
			$wgContLang->setAltEncoding();
			return;
		}

		if ( empty( $_SERVER['HTTP_ACCEPT_CHARSET'] ) ) {
			$wgOutputEncoding = strtolower( $wgOutputEncoding );
			return;
		}
		$wgOutputEncoding = $wgInputEncoding;
	}

	/**
	 * Returns a HTML comment with the elapsed time since request.
	 * This method has no side effects.
	 * Use wfReportTime() instead.
	 * @return string
	 * @deprecated
	 */
	function reportTime() {
		$time = wfReportTime();
		return $time;
	}

	/**
	 * Note: these arguments are keys into wfMsg(), not text!
	 */
	function errorpage( $title, $msg ) {
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
		$this->addWikiText( wfMsg( $msg ) );
		$this->returnToMain( false );

		$this->output();
		wfErrorExit();
	}

	/**
	 * Display an error page indicating that a given version of MediaWiki is
	 * required to use it
	 *
	 * @param mixed $version The version of MediaWiki needed to use the page
	 */
	function versionRequired( $version ) {
		global $wgUser;

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
	 * This should generally replace the sysopRequired, developerRequired etc.
	 * @param string $permission key required
	 */
	function permissionRequired( $permission ) {
		global $wgUser;

		$this->setPageTitle( wfMsg( 'badaccess' ) );
		$this->setHTMLTitle( wfMsg( 'errorpagetitle' ) );
		$this->setRobotpolicy( 'noindex,nofollow' );
		$this->setArticleRelated( false );
		$this->mBodytext = '';

		$sk = $wgUser->getSkin();
		$ap = $sk->makeKnownLink( wfMsgForContent( 'administrators' ) );
		$this->addHTML( wfMsgHtml( 'badaccesstext', $ap, $permission ) );
		$this->returnToMain();
	}

	/**
	 * @deprecated
	 */
	function sysopRequired() {
		global $wgUser;

		$this->setPageTitle( wfMsg( 'sysoptitle' ) );
		$this->setHTMLTitle( wfMsg( 'errorpagetitle' ) );
		$this->setRobotpolicy( 'noindex,nofollow' );
		$this->setArticleRelated( false );
		$this->mBodytext = '';

		$sk = $wgUser->getSkin();
		$ap = $sk->makeKnownLink( wfMsgForContent( 'administrators' ), '' );
		$this->addHTML( wfMsgHtml( 'sysoptext', $ap ) );
		$this->returnToMain();
	}

	/**
	 * @deprecated
	 */
	function developerRequired() {
		global $wgUser;

		$this->setPageTitle( wfMsg( 'developertitle' ) );
		$this->setHTMLTitle( wfMsg( 'errorpagetitle' ) );
		$this->setRobotpolicy( 'noindex,nofollow' );
		$this->setArticleRelated( false );
		$this->mBodytext = '';

		$sk = $wgUser->getSkin();
		$ap = $sk->makeKnownLink( wfMsgForContent( 'administrators' ), '' );
		$this->addHTML( wfMsgHtml( 'developertext', $ap ) );
		$this->returnToMain();
	}

	function loginToUse() {
		global $wgUser, $wgTitle, $wgContLang;

		$this->setPageTitle( wfMsg( 'loginreqtitle' ) );
		$this->setHTMLTitle( wfMsg( 'errorpagetitle' ) );
		$this->setRobotpolicy( 'noindex,nofollow' );
		$this->setArticleFlag( false );
		$this->mBodytext = '';
		$loginpage = Title::makeTitle(NS_SPECIAL, 'Userlogin');
		$sk = $wgUser->getSkin();
		$loginlink = $sk->makeKnownLinkObj($loginpage, wfMsg('loginreqlink'), 
			'returnto=' . htmlspecialchars($wgTitle->getPrefixedDBkey()));
		$this->addHTML( wfMsgHtml( 'loginreqpagetext', $loginlink ) );

		# We put a comment in the .html file so a Sysop can diagnose the page the
		# user can't see.
		$this->addHTML( "\n<!--" .
						$wgContLang->getNsText( $wgTitle->getNamespace() ) .
						':' .
						$wgTitle->getDBkey() . '-->' );
		$this->returnToMain();		# Flip back to the main page after 10 seconds.
	}

	function databaseError( $fname, $sql, $error, $errno ) {
		global $wgUser, $wgCommandLineMode, $wgShowSQLErrors;

		$this->setPageTitle( wfMsgNoDB( 'databaseerror' ) );
		$this->setRobotpolicy( 'noindex,nofollow' );
		$this->setArticleRelated( false );
		$this->enableClientCache( false );
		$this->mRedirect = '';

		if( !$wgShowSQLErrors ) {
			$sql = wfMsg( 'sqlhidden' );
		}

		if ( $wgCommandLineMode ) {
			$msg = wfMsgNoDB( 'dberrortextcl', htmlspecialchars( $sql ),
						htmlspecialchars( $fname ), $errno, htmlspecialchars( $error ) );
		} else {
			$msg = wfMsgNoDB( 'dberrortext', htmlspecialchars( $sql ),
						htmlspecialchars( $fname ), $errno, htmlspecialchars( $error ) );
		}

		if ( $wgCommandLineMode || !is_object( $wgUser )) {
			print $msg."\n";
			wfErrorExit();
		}
		$this->mBodytext = $msg;
		$this->output();
		wfErrorExit();
	}

	function readOnlyPage( $source = null, $protected = false ) {
		global $wgUser, $wgReadOnlyFile, $wgReadOnly;

		$this->setRobotpolicy( 'noindex,nofollow' );
		$this->setArticleRelated( false );

		if( $protected ) {
			$this->setPageTitle( wfMsg( 'viewsource' ) );
			$this->addWikiText( wfMsg( 'protectedtext' ) );
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
			if( strcmp( $source, '' ) == 0 ) {
				global $wgTitle ;
				if ( $wgTitle->getNamespace() == NS_MEDIAWIKI ) {
					$source = wfMsgWeirdKey ( $wgTitle->getText() ) ;
				} else {
					$source = wfMsg( $wgUser->isLoggedIn() ? 'noarticletext' : 'noarticletextanon' );
				}
			}
			$rows = $wgUser->getOption( 'rows' );
			$cols = $wgUser->getOption( 'cols' );
			$text = "\n<textarea cols='$cols' rows='$rows' readonly='readonly'>" .
				htmlspecialchars( $source ) . "\n</textarea>";
			$this->addHTML( $text );
		}

		$this->returnToMain( false );
	}

	function fatalError( $message ) {
		$this->setPageTitle( wfMsg( "internalerror" ) );
		$this->setRobotpolicy( "noindex,nofollow" );
		$this->setArticleRelated( false );
		$this->enableClientCache( false );
		$this->mRedirect = '';

		$this->mBodytext = $message;
		$this->output();
		wfErrorExit();
	}

	function unexpectedValueError( $name, $val ) {
		$this->fatalError( wfMsg( 'unexpected', $name, $val ) );
	}

	function fileCopyError( $old, $new ) {
		$this->fatalError( wfMsg( 'filecopyerror', $old, $new ) );
	}

	function fileRenameError( $old, $new ) {
		$this->fatalError( wfMsg( 'filerenameerror', $old, $new ) );
	}

	function fileDeleteError( $name ) {
		$this->fatalError( wfMsg( 'filedeleteerror', $name ) );
	}

	function fileNotFoundError( $name ) {
		$this->fatalError( wfMsg( 'filenotfound', $name ) );
	}

	/**
	 * return from error messages or notes
	 * @param $auto automatically redirect the user after 10 seconds
	 * @param $returnto page title to return to. Default is Main Page.
	 */
	function returnToMain( $auto = true, $returnto = NULL ) {
		global $wgUser, $wgOut, $wgRequest;

		if ( $returnto == NULL ) {
			$returnto = $wgRequest->getText( 'returnto' );
		}
		$returnto = htmlspecialchars( $returnto );

		$sk = $wgUser->getSkin();
		if ( '' == $returnto ) {
			$returnto = wfMsgForContent( 'mainpage' );
		}
		$link = $sk->makeKnownLink( $returnto, '' );

		$r = wfMsg( 'returnto', $link );
		if ( $auto ) {
			$titleObj = Title::newFromText( $returnto );
			$wgOut->addMeta( 'http:Refresh', '10;url=' . $titleObj->escapeFullURL() );
		}
		$wgOut->addHTML( "\n<p>$r</p>\n" );
	}

	/**
	 * This function takes the title (first item of mGoodLinks), categories, existing and broken links for the page
	 * and uses the first 10 of them for META keywords
	 */
	function addKeywords( &$parserOutput ) {
		global $wgTitle;
		$this->addKeyword( $wgTitle->getPrefixedText() );
		$count = 1;
		$links2d =& $parserOutput->getLinks();
		foreach ( $links2d as $ns => $dbkeys ) {
			foreach( $dbkeys as $dbkey => $id ) {
				$this->addKeyword( $dbkey );
				if ( ++$count > 10 ) {
					break 2;
				}
			}
		}
	}

	/**
 	 * @private
	 * @return string
	 */
	function headElement() {
		global $wgDocType, $wgDTD, $wgContLanguageCode, $wgOutputEncoding, $wgMimeType;
		global $wgUser, $wgContLang, $wgRequest, $wgUseTrackbacks, $wgTitle;

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
		$ret .= "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"$wgContLanguageCode\" lang=\"$wgContLanguageCode\" $rtl>\n";
		$ret .= "<head>\n<title>" . htmlspecialchars( $this->getHTMLTitle() ) . "</title>\n";
		array_push( $this->mMetatags, array( "http:Content-type", "$wgMimeType; charset={$wgOutputEncoding}" ) );

		$ret .= $this->getHeadLinks();
		global $wgStylePath;
		if( $this->isPrintable() ) {
			$media = '';
		} else {
			$media = "media='print'";
		}
		$printsheet = htmlspecialchars( "$wgStylePath/common/wikiprintable.css" );
		$ret .= "<link rel='stylesheet' type='text/css' $media href='$printsheet' />\n";

		$sk = $wgUser->getSkin();
		$ret .= $sk->getHeadScripts();
		$ret .= $this->mScripts;
		$ret .= $sk->getUserStyles();

		if ($wgUseTrackbacks && $this->isArticleRelated())
			$ret .= $wgTitle->trackbackRDF();

		$ret .= "</head>\n";
		return $ret;
	}

	function getHeadLinks() {
		global $wgRequest, $wgStylePath;
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
			$ret .= "<meta name=\"keywords\" content=\"" .
			  htmlspecialchars(preg_replace(array_keys($strip), array_values($strip),implode( ",", $this->mKeywords ))) . "\" />\n";
		}
		foreach ( $this->mLinktags as $tag ) {
			$ret .= '<link';
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
			$ret .= "<link rel='alternate' type='application/atom+xml' title='Atom 0.3' href='$link' />\n";
		}

		return $ret;
	}

	/**
	 * Turn off regular page output and return an error reponse
	 * for when rate limiting has triggered.
	 * @todo i18n
	 * @access public
	 */
	function rateLimited() {
		global $wgOut;
		$wgOut->disable();
		wfHttpError( 500, 'Internal Server Error',
			'Sorry, the server has encountered an internal error. ' .
			'Please wait a moment and hit "refresh" to submit the request again.' );
	}

}

} // MediaWiki

?>
