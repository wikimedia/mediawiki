<?php
# See design.doc

if($wgUseTeX) require_once( "Math.php" );

class OutputPage {
	var $mHeaders, $mCookies, $mMetatags, $mKeywords;
	var $mLinktags, $mPagetitle, $mBodytext, $mDebugtext;
	var $mHTMLtitle, $mRobotpolicy, $mIsarticle, $mPrintable;
	var $mSubtitle, $mRedirect;
	var $mLastModified, $mCategoryLinks;
	var $mScripts;

	var $mSuppressQuickbar;
	var $mOnloadHandler;
	var $mDoNothing;
	var $mContainsOldMagic, $mContainsNewMagic; 
	var $mIsArticleRelated;
	var $mParserOptions;
	var $mShowFeedLinks = false;
	var $mEnableClientCache = true;
	
	function OutputPage()
	{
		$this->mHeaders = $this->mCookies = $this->mMetatags =
		$this->mKeywords = $this->mLinktags = array();
		$this->mHTMLtitle = $this->mPagetitle = $this->mBodytext =
		$this->mRedirect = $this->mLastModified =
		$this->mSubtitle = $this->mDebugtext = $this->mRobotpolicy = 
		$this->mOnloadHandler = "";
		$this->mIsArticleRelated = $this->mIsarticle = $this->mPrintable = true;
		$this->mSuppressQuickbar = $this->mPrintable = false;
		$this->mLanguageLinks = array();
		$this->mCategoryLinks = array() ;
		$this->mDoNothing = false;
		$this->mContainsOldMagic = $this->mContainsNewMagic = 0;
		$this->mParserOptions = ParserOptions::newFromUser( $temp = NULL );
		$this->mSquidMaxage = 0;
		$this->mScripts = "";
	}

	function addHeader( $name, $val ) { array_push( $this->mHeaders, "$name: $val" ) ; }
	function addCookie( $name, $val ) { array_push( $this->mCookies, array( $name, $val ) ); }
	function redirect( $url, $responsecode = '302' ) { $this->mRedirect = $url; $this->mRedirectCode = $responsecode; }

	# To add an http-equiv meta tag, precede the name with "http:"
	function addMeta( $name, $val ) { array_push( $this->mMetatags, array( $name, $val ) ); }
	function addKeyword( $text ) { array_push( $this->mKeywords, $text ); }
	function addScript( $script ) { $this->mScripts .= $script; }
	function getScript() { return $this->mScripts; }
	
	function addLink( $linkarr ) {
		# $linkarr should be an associative array of attributes. We'll escape on output.
		array_push( $this->mLinktags, $linkarr );
	}

	function addMetadataLink( $linkarr ) {
		# note: buggy CC software only reads first "meta" link
		static $haveMeta = false;
		$linkarr["rel"] = ($haveMeta) ? "alternate meta" : "meta";
		$this->addLink( $linkarr );
		$haveMeta = true;
	}

	# checkLastModified tells the client to use the client-cached page if
	# possible. If sucessful, the OutputPage is disabled so that
	# any future call to OutputPage->output() have no effect. The method
	# returns true iff cache-ok headers was sent. 
	function checkLastModified ( $timestamp )
	{
		global $wgLang, $wgCachePages, $wgUser;
		if( !$wgCachePages ) {
			wfDebug( "CACHE DISABLED\n", false );
			return;
		}
		if( preg_match( '/MSIE ([1-4]|5\.0)/', $_SERVER["HTTP_USER_AGENT"] ) ) {
			# IE 5.0 has probs with our caching
			wfDebug( "-- bad client, not caching\n", false );
			return;
		}
		if( $wgUser->getOption( "nocache" ) ) {
			wfDebug( "USER DISABLED CACHE\n", false );
			return;
		}

		$lastmod = gmdate( "D, j M Y H:i:s", wfTimestamp2Unix( max( $timestamp, $wgUser->mTouched ) ) ) . " GMT";

		if( !empty( $_SERVER["HTTP_IF_MODIFIED_SINCE"] ) ) {
			# IE sends sizes after the date like this:
			# Wed, 20 Aug 2003 06:51:19 GMT; length=5202
			# this breaks strtotime().
			$modsince = preg_replace( '/;.*$/', '', $_SERVER["HTTP_IF_MODIFIED_SINCE"] );
			$ismodsince = wfUnix2Timestamp( strtotime( $modsince ) );
			wfDebug( "-- client send If-Modified-Since: " . $modsince . "\n", false );
			wfDebug( "--  we might send Last-Modified : $lastmod\n", false ); 

			if( ($ismodsince >= $timestamp ) and $wgUser->validateCache( $ismodsince ) ) {
				# Make sure you're in a place you can leave when you call us!
				header( "HTTP/1.0 304 Not Modified" );
				$this->mLastModified = $lastmod;
				$this->sendCacheControl();
				wfDebug( "CACHED client: $ismodsince ; user: $wgUser->mTouched ; page: $timestamp\n", false );
				$this->disable();
				return true;
			} else {
				wfDebug( "READY  client: $ismodsince ; user: $wgUser->mTouched ; page: $timestamp\n", false );
				$this->mLastModified = $lastmod;
			}
		} else {
			wfDebug( "We're confused.\n", false );
			$this->mLastModified = $lastmod;
		}
	}

	function getPageTitleActionText () {
		global $action;
		switch($action) {
			case 'edit':
				return 	wfMsg('edit');
			case 'history':
				return wfMsg('history_short');
			case 'protect':
				return wfMsg('unprotect');
			case 'unprotect':
				return wfMsg('unprotect');
			case 'delete':
				return wfMsg('delete');
			case 'watch':
				return wfMsg('watch');
			case 'unwatch':
				return wfMsg('unwatch');
			case 'submit':
				return wfMsg('preview');
			default:
				return '';
		}
	}
	function setRobotpolicy( $str ) { $this->mRobotpolicy = $str; }
	function setHTMLTitle( $name ) {$this->mHTMLtitle = $name; }
	function setPageTitle( $name ) {
		global $action;
		$this->mPagetitle = $name;
		if(!empty($action)) {
			$taction =  $this->getPageTitleActionText();
			if( !empty( $taction ) ) {
				$name .= " - $taction";
			}
		}
		$this->setHTMLTitle( $name . " - " . wfMsg( "wikititlesuffix" ) );
	}
	function getHTMLTitle() { return $this->mHTMLtitle; }
	function getPageTitle() { return $this->mPagetitle; }
	function setSubtitle( $str ) { $this->mSubtitle = $str; }
	function getSubtitle() { return $this->mSubtitle; }
	function isArticle() { return $this->mIsarticle; }
	function setPrintable() { $this->mPrintable = true; }
	function isPrintable() { return $this->mPrintable; }
	function setSyndicated( $show = true ) { $this->mShowFeedLinks = $show; }
	function isSyndicated() { return $this->mShowFeedLinks; }
	function setOnloadHandler( $js ) { $this->mOnloadHandler = $js; }
	function getOnloadHandler() { return $this->mOnloadHandler; }
	function disable() { $this->mDoNothing = true; }

	function setArticleRelated( $v )
	{
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

	function isArticleRelated()
	{
		return $this->mIsArticleRelated;
	}
	
	function getLanguageLinks() {
		global $wgTitle, $wgLanguageCode;
		global $wgDBconnection, $wgDBname;
		return $this->mLanguageLinks;
	}
	function suppressQuickbar() { $this->mSuppressQuickbar = true; }
	function isQuickbarSuppressed() { return $this->mSuppressQuickbar; }

	function addHTML( $text ) { $this->mBodytext .= $text; }
	function debug( $text ) { $this->mDebugtext .= $text; }

	function setParserOptions( $options )
	{
		return wfSetVar( $this->mParserOptions, $options );
	}

	# First pass--just handle <nowiki> sections, pass the rest off
	# to doWikiPass2() which does all the real work.
	#
	# $cacheArticle - assume this text is the main text for the given article
	#
	function addWikiText( $text, $linestart = true, $cacheArticle = NULL )
	{
		global $wgParser, $wgParserCache, $wgUser, $wgTitle;

		$parserOutput = false;
		if ( $cacheArticle ) {
			$parserOutput = $wgParserCache->get( $cacheArticle, $wgUser );
		}

		if ( $parserOutput === false ) {
			$parserOutput = $wgParser->parse( $text, $wgTitle, $this->mParserOptions, $linestart );
			if ( $cacheArticle ) {
				$wgParserCache->save( $parserOutput, $cacheArticle, $wgUser );
			}
		}
		
		$this->mLanguageLinks += $parserOutput->getLanguageLinks();
		$this->mCategoryLinks += $parserOutput->getCategoryLinks();
		
		$this->addHTML( $parserOutput->getText() );
		
	}

	# Set the maximum cache time on the Squid in seconds
	function setSquidMaxage( $maxage ) {
		$this->mSquidMaxage = $maxage;
	}
	
	# Use enableClientCache(false) to force it to send nocache headers
	function enableClientCache( $state ) {
		return wfSetVar( $this->mEnableClientCache, $state );
	}
	
	function sendCacheControl() {
		global $wgUseSquid, $wgUseESI;
		# FIXME: This header may cause trouble with some versions of Internet Explorer
		header( "Vary: Accept-Encoding, Cookie" );
		if( $this->mEnableClientCache ) {
			if( $wgUseSquid && ! isset( $_COOKIE[ini_get( "session.name") ] ) && 
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
			header( "Expires: -1" );
			header( "Cache-Control: no-cache, no-store, max-age=0, must-revalidate" );
			header( "Pragma: no-cache" );
		}
	}
	
	# Finally, all the text has been munged and accumulated into
	# the object, let's actually output it:
	#
	function output()
	{
		global $wgUser, $wgLang, $wgDebugComments, $wgCookieExpiration;
		global $wgInputEncoding, $wgOutputEncoding, $wgLanguageCode;
		global $wgDebugRedirects, $wgMimeType;
		if( $this->mDoNothing ){
			return;
		}
		$fname = "OutputPage::output";
		wfProfileIn( $fname );
		
		$sk = $wgUser->getSkin();

		if ( "" != $this->mRedirect ) {
			if( substr( $this->mRedirect, 0, 4 ) != "http" ) {
				# Standards require redirect URLs to be absolute
				global $wgServer;
				$this->mRedirect = $wgServer . $this->mRedirect;
			}
			if( $this->mRedirectCode == '301') {
				if( !$wgDebugRedirects ) {
					header("HTTP/1.1 {$this->mRedirectCode} Moved Permanently");
				}
				$this->mLastModified = gmdate( "D, j M Y H:i:s" ) . " GMT";
			}
                        
			$this->sendCacheControl();
			
			if( $wgDebugRedirects ) {
				$url = htmlspecialchars( $this->mRedirect );
				print "<html>\n<head>\n<title>Redirect</title>\n</head>\n<body>\n";
				print "<p>Location: <a href=\"$url\">$url</a></p>\n";
				print "</body>\n</html>\n";
			} else {
				header( "Location: {$this->mRedirect}" );
			}
			return;
		}
		
		
		$this->sendCacheControl();
		
		header( "Content-type: $wgMimeType; charset={$wgOutputEncoding}" );
		header( "Content-language: {$wgLanguageCode}" );

		$exp = time() + $wgCookieExpiration;
		foreach( $this->mCookies as $name => $val ) {
			setcookie( $name, $val, $exp, "/" );
		}
		
		$sk->outputPage( $this );
		# flush();
	}

	function out( $ins )
	{
		global $wgInputEncoding, $wgOutputEncoding, $wgLang;
		if ( 0 == strcmp( $wgInputEncoding, $wgOutputEncoding ) ) {
			$outs = $ins;
		} else {
			$outs = $wgLang->iconv( $wgInputEncoding, $wgOutputEncoding, $ins );
			if ( false === $outs ) { $outs = $ins; }
		}
		print $outs;
	}

	function setEncodings()
	{
		global $wgInputEncoding, $wgOutputEncoding;
		global $wgUser, $wgLang;

		$wgInputEncoding = strtolower( $wgInputEncoding );
		
		if( $wgUser->getOption( 'altencoding' ) ) {
			$wgLang->setAltEncoding();
			return;
		}

		if ( empty( $_SERVER['HTTP_ACCEPT_CHARSET'] ) ) {
			$wgOutputEncoding = strtolower( $wgOutputEncoding );
			return;
		}
		
		/*
		# This code is unused anyway!
		# Commenting out. --bv 2003-11-15
		
		$a = explode( ",", $_SERVER['HTTP_ACCEPT_CHARSET'] );
		$best = 0.0;
		$bestset = "*";

		foreach ( $a as $s ) {
			if ( preg_match( "/(.*);q=(.*)/", $s, $m ) ) {
				$set = $m[1];
				$q = (float)($m[2]);
			} else {
				$set = $s;
				$q = 1.0;
			}
			if ( $q > $best ) {
				$bestset = $set;
				$best = $q;
			}
		}
		#if ( "*" == $bestset ) { $bestset = "iso-8859-1"; }
		if ( "*" == $bestset ) { $bestset = $wgOutputEncoding; }
		$wgOutputEncoding = strtolower( $bestset );

# Disable for now
#
		*/
		$wgOutputEncoding = $wgInputEncoding;
	}

	# Returns a HTML comment with the elapsed time since request. 
	# This method has no side effects.
	function reportTime()
	{
		global $wgRequestTime;

		$now = wfTime();
		list( $usec, $sec ) = explode( " ", $wgRequestTime );
		$start = (float)$sec + (float)$usec;
		$elapsed = $now - $start;
		$com = sprintf( "<!-- Time since request: %01.2f secs. -->",
		  $elapsed );
		return $com;
	}

	# Note: these arguments are keys into wfMsg(), not text!
	#
	function errorpage( $title, $msg )
	{
		global $wgTitle;

		$this->mDebugtext .= "Original title: " .
		  $wgTitle->getPrefixedText() . "\n";
		$this->setPageTitle( wfMsg( $title ) );
		$this->setHTMLTitle( wfMsg( "errorpagetitle" ) );
		$this->setRobotpolicy( "noindex,nofollow" );
		$this->setArticleRelated( false );
		$this->enableClientCache( false );

		$this->mBodytext = "";
		$this->addHTML( "<p>" . wfMsg( $msg ) . "</p>\n" );
		$this->returnToMain( false );

		$this->output();
		wfAbruptExit();
	}

	function sysopRequired()
	{
		global $wgUser;

		$this->setPageTitle( wfMsg( "sysoptitle" ) );
		$this->setHTMLTitle( wfMsg( "errorpagetitle" ) );
		$this->setRobotpolicy( "noindex,nofollow" );
		$this->setArticleRelated( false );
		$this->mBodytext = "";

		$sk = $wgUser->getSkin();
		$ap = $sk->makeKnownLink( wfMsg( "administrators" ), "" );	
		$this->addHTML( wfMsg( "sysoptext", $ap ) );
		$this->returnToMain();
	}

	function developerRequired()
	{
		global $wgUser;

		$this->setPageTitle( wfMsg( "developertitle" ) );
		$this->setHTMLTitle( wfMsg( "errorpagetitle" ) );
		$this->setRobotpolicy( "noindex,nofollow" );
		$this->setArticleRelated( false );
		$this->mBodytext = "";

		$sk = $wgUser->getSkin();
		$ap = $sk->makeKnownLink( wfMsg( "administrators" ), "" );	
		$this->addHTML( wfMsg( "developertext", $ap ) );
		$this->returnToMain();
	}

	function loginToUse()
	{
		global $wgUser, $wgTitle, $wgLang;

		$this->setPageTitle( wfMsg( "loginreqtitle" ) );
		$this->setHTMLTitle( wfMsg( "errorpagetitle" ) );
		$this->setRobotpolicy( "noindex,nofollow" );
		$this->setArticleFlag( false );
		$this->mBodytext = "";
		$this->addWikiText( wfMsg( "loginreqtext" ) );

		# We put a comment in the .html file so a Sysop can diagnose the page the
		# user can't see.
		$this->addHTML( "\n<!--" . 
						$wgLang->getNsText( $wgTitle->getNamespace() ) . 
						":" . 
						$wgTitle->getDBkey() . "-->" );
		$this->returnToMain();		# Flip back to the main page after 10 seconds.
	}

	function databaseError( $fname, $sql, $error, $errno )
	{
		global $wgUser, $wgCommandLineMode;

		$this->setPageTitle( wfMsgNoDB( "databaseerror" ) );
		$this->setRobotpolicy( "noindex,nofollow" );
		$this->setArticleRelated( false );
		$this->enableClientCache( false );

		if ( $wgCommandLineMode ) {
			$msg = wfMsgNoDB( "dberrortextcl" );
		} else {
			$msg = wfMsgNoDB( "dberrortext" );
		}

		$msg = str_replace( "$1", htmlspecialchars( $sql ), $msg );
		$msg = str_replace( "$2", htmlspecialchars( $fname ), $msg );
		$msg = str_replace( "$3", $errno, $msg );
		$msg = str_replace( "$4", htmlspecialchars( $error ), $msg );
		
		if ( $wgCommandLineMode || !is_object( $wgUser )) {
			print "$msg\n";
			wfAbruptExit();
		}
		$sk = $wgUser->getSkin();
		$shlink = $sk->makeKnownLink( wfMsgNoDB( "searchhelppage" ),
		  wfMsgNoDB( "searchingwikipedia" ) );
		$msg = str_replace( "$5", $shlink, $msg );
		$this->mBodytext = $msg;
		$this->output();
		wfAbruptExit();
	}

	function readOnlyPage( $source = null, $protected = false )
	{
		global $wgUser, $wgReadOnlyFile;

		$this->setRobotpolicy( "noindex,nofollow" );
		$this->setArticleRelated( false );

		if( $protected ) {
			$this->setPageTitle( wfMsg( "viewsource" ) );
			$this->addWikiText( wfMsg( "protectedtext" ) );
		} else {
			$this->setPageTitle( wfMsg( "readonly" ) );
			$reason = file_get_contents( $wgReadOnlyFile );
			$this->addWikiText( wfMsg( "readonlytext", $reason ) );
		}
		
		if( is_string( $source ) ) {
			if( strcmp( $source, "" ) == 0 ) {
				$source = wfMsg( "noarticletext" );
			}
			$rows = $wgUser->getOption( "rows" );
			$cols = $wgUser->getOption( "cols" );
			$text = "\n<textarea cols='$cols' rows='$rows' readonly='readonly'>" .
				htmlspecialchars( $source ) . "\n</textarea>";
			$this->addHTML( $text );
		}
		
		$this->returnToMain( false );
	}

	function fatalError( $message )
	{
		$this->setPageTitle( wfMsg( "internalerror" ) );
		$this->setRobotpolicy( "noindex,nofollow" );
		$this->setArticleRelated( false );
		$this->enableClientCache( false );

		$this->mBodytext = $message;
		$this->output();
		wfAbruptExit();
	}

	function unexpectedValueError( $name, $val )
	{
		$this->fatalError( wfMsg( "unexpected", $name, $val ) );
	}

	function fileCopyError( $old, $new )
	{
		$this->fatalError( wfMsg( "filecopyerror", $old, $new ) );
	}

	function fileRenameError( $old, $new )
	{
		$this->fatalError( wfMsg( "filerenameerror", $old, $new ) );
	}

	function fileDeleteError( $name )
	{
		$this->fatalError( wfMsg( "filedeleteerror", $name ) );
	}

	function fileNotFoundError( $name )
	{
		$this->fatalError( wfMsg( "filenotfound", $name ) );
	}

	// return from error messages or notes
	//   auto: 	automatically redirect the user after 10 seconds
	//   returnto:	page title to return to. Default is Main Page.
	function returnToMain( $auto = true, $returnto = NULL )
	{
		global $wgUser, $wgOut, $wgRequest;
		
		if ( $returnto == NULL ) {
			$returnto = $wgRequest->getText( 'returnto' );
		}

		$sk = $wgUser->getSkin();
		if ( "" == $returnto ) {
			$returnto = wfMsg( "mainpage" );
		}
		$link = $sk->makeKnownLink( $returnto, "" );

		$r = wfMsg( "returnto", $link );
		if ( $auto ) {
			$titleObj = Title::newFromText( $returnto );
			$wgOut->addMeta( "http:Refresh", "10;url=" . $titleObj->escapeFullURL() );
		}
		$wgOut->addHTML( "\n<p>$r</p>\n" );
	}

	# This function takes the existing and broken links for the page
	# and uses the first 10 of them for META keywords
	function addMetaTags ()
	{
		global $wgLinkCache , $wgOut ;
		$good = array_keys ( $wgLinkCache->mGoodLinks ) ;
		$bad = array_keys ( $wgLinkCache->mBadLinks ) ;
		$a = array_merge ( $good , $bad ) ;
		$a = array_slice ( $a , 0 , 10 ) ; # 10 keywords max
		$a = implode ( "," , $a ) ;
		$strip = array(
			"/<.*?>/" => '',
			"/[_]/" => ' '
		);
		$a = htmlspecialchars(preg_replace(array_keys($strip), array_values($strip),$a ));
		
		$wgOut->addMeta ( "KEYWORDS" , $a ) ;
	}

	/* private */ function headElement()
	{
		global $wgDocType, $wgDTD, $wgLanguageCode, $wgOutputEncoding, $wgMimeType;
		global $wgUser, $wgLang, $wgRequest;

		$xml = ($wgMimeType == 'text/xml');
		if( $xml ) {
			$ret = "<" . "?xml version=\"1.0\" encoding=\"$wgOutputEncoding\" ?" . ">\n";
		} else {
			$ret = "";
		}
		
		$ret .= "<!DOCTYPE html PUBLIC \"$wgDocType\"\n        \"$wgDTD\">\n";

		if ( "" == $this->mHTMLtitle ) {
			$this->mHTMLtitle = wfMsg( "pagetitle", $this->mPagetitle );
		}
		if( $xml ) {
			$xmlbits = "xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\"";
		} else {
			$xmlbits = "";
		}
		$rtl = $wgLang->isRTL() ? " dir='RTL'" : "";
		$ret .= "<html $xmlbits lang=\"$wgLanguageCode\" $rtl>\n";
		$ret .= "<head>\n<title>" . htmlspecialchars( $this->mHTMLtitle ) . "</title>\n";
		array_push( $this->mMetatags, array( "http:Content-type", "$wgMimeType; charset={$wgOutputEncoding}" ) );
		
		$ret .= $this->getHeadLinks();
		global $wgStylePath;
		if( $this->isPrintable() ) {
			$media = "";
		} else {
			$media = "media='print'";
		}
		$printsheet = htmlspecialchars( "$wgStylePath/wikiprintable.css" );
		$ret .= "<link rel='stylesheet' type='text/css' $media href='$printsheet' />\n";

		$sk = $wgUser->getSkin();
		$ret .= $sk->getHeadScripts();
		$ret .= $this->mScripts;
		$ret .= $sk->getUserStyles();

		$ret .= "</head>\n";
		return $ret;
	}
	
	function getHeadLinks() {
		global $wgRequest, $wgStylePath;
		$ret = "";
		foreach ( $this->mMetatags as $tag ) {
			if ( 0 == strcasecmp( "http:", substr( $tag[0], 0, 5 ) ) ) {
				$a = "http-equiv";
				$tag[0] = substr( $tag[0], 5 );
			} else {
				$a = "name";
			}
			$ret .= "<meta $a=\"{$tag[0]}\" content=\"{$tag[1]}\" />\n";
		}
		$p = $this->mRobotpolicy;
		if ( "" == $p ) { $p = "index,follow"; }
		$ret .= "<meta name=\"robots\" content=\"$p\" />\n";

		if ( count( $this->mKeywords ) > 0 ) {
			$strip = array(
				"/<.*?>/" => '',
				"/[_]/" => ' '
			);
			$ret .= "<meta name=\"keywords\" content=\"" .
			  htmlspecialchars(preg_replace(array_keys($strip), array_values($strip),implode( ",", $this->mKeywords ))) . "\" />\n";
		}
		foreach ( $this->mLinktags as $tag ) {
			$ret .= "<link";
			foreach( $tag as $attr => $val ) {
				$ret .= " $attr=\"" . htmlspecialchars( $val ) . "\"";
			}
			$ret .= " />\n";
		}
		if( $this->isSyndicated() ) {
			# FIXME: centralize the mime-type and name information in Feed.php
			$link = $wgRequest->escapeAppendQuery( "feed=rss" );
			$ret .= "<link rel='alternate' type='application/rss+xml' title='RSS 2.0' href='$link' />\n";
			$link = $wgRequest->escapeAppendQuery( "feed=atom" );
			$ret .= "<link rel='alternate' type='application/rss+atom' title='Atom 0.3' href='$link' />\n";
		}
		# FIXME: get these working
		# $fix = htmlspecialchars( $wgStylePath . "/ie-png-fix.js" );
		# $ret .= "<!--[if gte IE 5.5000]><script type='text/javascript' src='$fix'></script><![endif]-->";
		return $ret;
	}
}
?>
