<?
# See design.doc

function linkToMathImage ( $tex, $outputhash )
{
    global $wgMathPath;
    return "<img src=\"".$wgMathPath."/".$outputhash.".png\" alt=\"".wfEscapeHTML($tex)."\">";
}

function renderMath( $tex )
{
    global $wgUser, $wgMathDirectory, $wgTmpDirectory, $wgInputEncoding;
    $mf   = wfMsg( "math_failure" );
    $munk = wfMsg( "math_unknown_error" );

    $fname = "renderMath";

    $math = $wgUser->getOption("math");
    if ($math == 3)
	return ('$ '.wfEscapeHTML($tex).' $');

    $md5 = md5($tex);
    $md5_sql = mysql_escape_string(pack("H32", $md5));
    if ($math == 0)
	$sql = "SELECT math_outputhash FROM math WHERE math_inputhash = '".$md5_sql."'";
    else
	$sql = "SELECT math_outputhash,math_html_conservativeness,math_html FROM math WHERE math_inputhash = '".$md5_sql."'";

    $res = wfQuery( $sql, $fname );
    if ( wfNumRows( $res ) == 0 )
    {
	$cmd = "./math/texvc ".escapeshellarg($wgTmpDirectory)." ".
		      escapeshellarg($wgMathDirectory)." ".escapeshellarg($tex)." ".escapeshellarg($wgInputEncoding);
	$contents = `$cmd`;

	if (strlen($contents) == 0)
	    return "<b>".$mf." (".$munk."): ".wfEscapeHTML($tex)."</b>";
	$retval = substr ($contents, 0, 1);
	if (($retval == "C") || ($retval == "M") || ($retval == "L")) {
	    if ($retval == "C")
		$conservativeness = 2;
	    else if ($retval == "M")
		$conservativeness = 1;
	    else
		$conservativeness = 0;
	    $outdata = substr ($contents, 33);

	    $i = strpos($outdata, "\000");

	    $outhtml = substr($outdata, 0, $i);
	    $mathml = substr($outdata, $i+1);

	    $sql_html = "'".mysql_escape_string($outhtml)."'";
	    $sql_mathml = "'".mysql_escape_string($mathml)."'";
	} else if (($retval == "c") || ($retval == "m") || ($retval == "l"))  {
	    $outhtml = substr ($contents, 33);
	    if ($retval == "c")
		$conservativeness = 2;
	    else if ($retval == "m")
		$conservativeness = 1;
	    else
		$conservativeness = 0;
	    $sql_html = "'".mysql_escape_string($outhtml)."'";
	    $mathml = '';
	    $sql_mathml = 'NULL';
	} else if ($retval == "X") {
	    $outhtml = '';
	    $mathml = substr ($contents, 33);
	    $sql_html = 'NULL';
	    $sql_mathml = "'".mysql_escape_string($mathml)."'";
	    $conservativeness = 0;
	} else if ($retval == "+") {
	    $outhtml = '';
	    $mathml = '';
	    $sql_html = 'NULL';
	    $sql_mathml = 'NULL';
	    $conservativeness = 0;
	} else {
	    if ($retval == "E")
		$errmsg = wfMsg( "math_lexing_error" );
	    else if ($retval == "S")
		$errmsg = wfMsg( "math_syntax_error" );
	    else if ($retval == "F")
		$errmsg = wfMsg( "math_unknown_function" );
	    else
		$errmsg = $munk;
	    return "<h3>".$mf." (".$errmsg.substr($contents, 1)."): ".wfEscapeHTML($tex)."</h3>";
	}

	$outmd5 = substr ($contents, 1, 32);
	if (!preg_match("/^[a-f0-9]{32}$/", $outmd5))
	    return "<b>".$mf." (".$munk."): ".wfEscapeHTML($tex)."</b>";

	$outmd5_sql = mysql_escape_string(pack("H32", $outmd5));

	$sql = "REPLACE INTO math VALUES ('".$md5_sql."', '".$outmd5_sql."', ".$conservativeness.", ".$sql_html.", ".$sql_mathml.")";
	
	$res = wfQuery( $sql, $fname );
	# we don't really care if it fails

	if (($math == 0) || ($rpage->math_html == '') || (($math == 1) && ($conservativeness != 2)) || (($math == 4) && ($conservativeness == 0)))
	    return linkToMathImage($tex, $outmd5);
	else
	    return $outhtml;
    } else {
	$rpage = wfFetchObject ( $res );
	$outputhash = unpack( "H32md5", $rpage->math_outputhash . "                " );
	$outputhash = $outputhash ['md5'];
	
	if (($math == 0) || ($rpage->math_html == '') || (($math == 1) && ($rpage->math_html_conservativeness != 2)) || (($math == 4) && ($rpage->math_html_conservativeness == 0)))
	    return linkToMathImage ( $tex, $outputhash );
	else
	    return $rpage->math_html;
    }
}

class OutputPage {
	var $mHeaders, $mCookies, $mMetatags, $mKeywords;
	var $mLinktags, $mPagetitle, $mBodytext, $mDebugtext;
	var $mHTMLtitle, $mRobotpolicy, $mIsarticle, $mPrintable;
	var $mSubtitle, $mRedirect, $mAutonumber, $mHeadtext;
	var $mLastModified;

	var $mDTopen, $mLastSection; # Used for processing DL, PRE
	var $mLanguageLinks, $mSupressQuickbar;

	function OutputPage()
	{
		$this->mHeaders = $this->mCookies = $this->mMetatags =
		$this->mKeywords = $this->mLinktags = array();
		$this->mHTMLtitle = $this->mPagetitle = $this->mBodytext =
		$this->mLastSection = $this->mRedirect = $this->mLastModified =
		$this->mSubtitle = $this->mDebugtext = $this->mRobotpolicy = "";
		$this->mIsarticle = $this->mPrintable = true;
		$this->mSupressQuickbar = $this->mDTopen = $this->mPrintable = false;
		$this->mLanguageLinks = array();
		$this->mAutonumber = 0;
	}

	function addHeader( $name, $val ) { array_push( $this->mHeaders, "$name: $val" ) ; }
	function addCookie( $name, $val ) { array_push( $this->mCookies, array( $name, $val ) ); }
	function redirect( $url ) { $this->mRedirect = $url; }

	# To add an http-equiv meta tag, precede the name with "http:"
	function addMeta( $name, $val ) { array_push( $this->mMetatags, array( $name, $val ) ); }
	function addKeyword( $text ) { array_push( $this->mKeywords, $text ); }
	function addLink( $rel, $rev, $target ) { array_push( $this->mLinktags, array( $rel, $rev, $target ) ); }

	function checkLastModified ( $timestamp )
	{
		global $wgLang, $wgCachePages, $wgUser;
		if( !$wgCachePages ) return;
		if( preg_match( '/MSIE ([1-4]|5\.0)/', $_SERVER["HTTP_USER_AGENT"] ) ) {
			# IE 5.0 has probs with our caching
			#wfDebug( "-- bad client, not caching\n", false );
			return;
		}
		if( $wgUser->getOption( "nocache" ) ) return;

		if( $_SERVER["HTTP_IF_MODIFIED_SINCE"] != "" ) {
			$ismodsince = wfUnix2Timestamp( strtotime( $_SERVER["HTTP_IF_MODIFIED_SINCE"] ) );
			#wfDebug( "-- client send If-Modified-Since: " . $_SERVER["HTTP_IF_MODIFIED_SINCE"] . "\n", false );
			$lastmod = gmdate( "D, j M Y H:i:s", wfTimestamp2Unix(
				max( $timestamp, $wgUser->mTouched ) ) ) . " GMT";
			#wfDebug( "--  we might send Last-Modified : $lastmod\n", false ); 
		
			if( ($ismodsince >= $timestamp ) and $wgUser->validateCache( $ismodsince ) ) {
				# Make sure you're in a place you can leave when you call us!
				header( "HTTP/1.0 304 Not Modified" );
				header( "Expires: Mon, 15 Jan 2001 00:00:00 GMT" ); # Cachers always validate the page!
				header( "Cache-Control: private, must-revalidate, max-age=0" );
				header( "Last-Modified: {$lastmod}" );			
				#wfDebug( "CACHED client: $ismodsince ; user: $wgUser->mTouched ; page: $timestamp\n", false );
				exit;
			} else {
				#wfDebug( "READY  client: $ismodsince ; user: $wgUser->mTouched ; page: $timestamp\n", false );
				$this->mLastModified = $lastmod;
			}
		}
	}

	function setRobotpolicy( $str ) { $this->mRobotpolicy = $str; }
	function setHTMLtitle( $name ) { $this->mHTMLtitle = $name; }
	function setPageTitle( $name ) { $this->mPagetitle = $name; }
	function getPageTitle() { return $this->mPagetitle; }
	function setSubtitle( $str ) { $this->mSubtitle = $str; }
	function getSubtitle() { return $this->mSubtitle; }
	function setArticleFlag( $v ) { $this->mIsarticle = $v; }
	function isArticle() { return $this->mIsarticle; }
	function setPrintable() { $this->mPrintable = true; }
	function isPrintable() { return $this->mPrintable; }

	function getLanguageLinks() {
		global $wgUseNewInterlanguage, $wgTitle, $wgLanguageCode;
		global $wgDBconnection, $wgDBname, $wgDBintlname;

		if ( ! $wgUseNewInterlanguage )
			return $this->mLanguageLinks; 
		
		mysql_select_db( $wgDBintlname, $wgDBconnection ) or die(
			  htmlspecialchars(mysql_error()) );

		$list = array();
		$sql = "SELECT * FROM ilinks WHERE lang_from=\"" .
		  "{$wgLanguageCode}\" AND title_from=\"" . $wgTitle->getDBkey() . "\"";
		$res = mysql_query( $sql, $wgDBconnection );

		while ( $q = mysql_fetch_object ( $res ) ) {
			$list[] = $q->lang_to . ":" . $q->title_to;
		}
		mysql_free_result( $res );
		mysql_select_db( $wgDBname, $wgDBconnection ) or die(
		  htmlspecialchars(mysql_error()) );

		return $list;
	}

	function supressQuickbar() { $this->mSupressQuickbar = true; }
	function isQuickbarSupressed() { return $this->mSupressQuickbar; }

	function addHTML( $text ) { $this->mBodytext .= $text; }
	function addHeadtext( $text ) { $this->mHeadtext .= $text; }
	function debug( $text ) { $this->mDebugtext .= $text; }

	# First pass--just handle <nowiki> sections, pass the rest off
	# to doWikiPass2() which does all the real work.
	#

	function addWikiText( $text, $linestart = true )
	{
		global $wgUseTeX;
		wfProfileIn( "OutputPage::addWikiText" );
		$unique  = "3iyZiyA7iMwg5rhxP0Dcc9oTnj8qD1jm1Sfv4";
		$unique2 = "4LIQ9nXtiYFPCSfitVwDw7EYwQlL4GeeQ7qSO";
		$unique3 = "fPaA8gDfdLBqzj68Yjg9Hil3qEF8JGO0uszIp";
		$nwlist = array();
		$nwsecs = 0;
		$mathlist = array();
		$mathsecs = 0;
		$prelist = array ();
		$presecs = 0;
		$stripped = "";
		$stripped2 = "";
		$stripped3 = "";

		while ( "" != $text ) {
			$p = preg_split( "/<\\s*nowiki\\s*>/i", $text, 2 );
			$stripped .= $p[0];
			if ( ( count( $p ) < 2 ) || ( "" == $p[1] ) ) { $text = ""; }
			else {
				$q = preg_split( "/<\\/\\s*nowiki\\s*>/i", $p[1], 2 );
				++$nwsecs;
				$nwlist[$nwsecs] = wfEscapeHTMLTagsOnly($q[0]);
				$stripped .= $unique;
				$text = $q[1];
			}
		}

		if( $wgUseTeX ) {
			while ( "" != $stripped ) {
				$p = preg_split( "/<\\s*math\\s*>/i", $stripped, 2 );
				$stripped2 .= $p[0];
				if ( ( count( $p ) < 2 ) || ( "" == $p[1] ) ) { $stripped = ""; }
				else {
					$q = preg_split( "/<\\/\\s*math\\s*>/i", $p[1], 2 );
					++$mathsecs;
					$mathlist[$mathsecs] = renderMath($q[0]);
					$stripped2 .= $unique2;
					$stripped = $q[1];
				}
			}
		} else {
			$stripped2 = $stripped;
		}

		while ( "" != $stripped2 ) {
			$p = preg_split( "/<\\s*pre\\s*>/i", $stripped2, 2 );
			$stripped3 .= $p[0];
			if ( ( count( $p ) < 2 ) || ( "" == $p[1] ) ) { $stripped2 = ""; }
			else {
				$q = preg_split( "/<\\/\\s*pre\\s*>/i", $p[1], 2 );
				++$presecs;
				$prelist[$presecs] = "<pre>". wfEscapeHTMLTagsOnly($q[0]). "</pre>";
				$stripped3 .= $unique3;
				$stripped2 = $q[1];
			}
		}

		$text = $this->doWikiPass2( $stripped3, $linestart );
		
		$specialChars = array("\\", "$");
		$escapedChars = array("\\\\", "\\$");
		for ( $i = 1; $i <= $presecs; ++$i ) {
			$text = preg_replace( "/{$unique3}/", str_replace( $specialChars, 
				$escapedChars, $prelist[$i] ), $text, 1 );
		}

		for ( $i = 1; $i <= $mathsecs; ++$i ) {
			$text = preg_replace( "/{$unique2}/", str_replace( $specialChars, 
				$escapedChars, $mathlist[$i] ), $text, 1 );
		}

		for ( $i = 1; $i <= $nwsecs; ++$i ) {
			$text = preg_replace( "/{$unique}/", str_replace( $specialChars, 
				$escapedChars, $nwlist[$i] ), $text, 1 );
		}
		$this->addHTML( $text );
		wfProfileOut();
	}

	function sendCacheControl() {
		if( $this->mLastModified != "" ) {
			header( "Cache-Control: private, must-revalidate, max-age=0" );
			header( "Last-modified: {$this->mLastModified}" );
		} else {
			header( "Cache-Control: no-cache" ); # Experimental - see below
			header( "Pragma: no-cache" );
			header( "Last-modified: " . gmdate( "D, j M Y H:i:s" ) . " GMT" );
		}
		header( "Expires: Mon, 15 Jan 2001 00:00:00 GMT" ); # Cachers always validate the page!
	}
	
	# Finally, all the text has been munged and accumulated into
	# the object, let's actually output it:
	#
	function output()
	{
		global $wgUser, $wgLang, $wgDebugComments, $wgCookieExpiration;
		global $wgInputEncoding, $wgOutputEncoding, $wgLanguageCode;
		wfProfileIn( "OutputPage::output" );
		$sk = $wgUser->getSkin();

		wfProfileIn( "OutputPage::output-headers" );
		$this->sendCacheControl();

		header( "Content-type: text/html; charset={$wgOutputEncoding}" );
		header( "Content-language: {$wgLanguageCode}" );
		
		if ( "" != $this->mRedirect ) {
			header( "Location: {$this->mRedirect}" );
			wfProfileOut();
			return;
		}

		$exp = time() + $wgCookieExpiration;
		foreach( $this->mCookies as $name => $val ) {
			setcookie( $name, $val, $exp, "/" );
		}
		wfProfileOut();

		wfProfileIn( "OutputPage::output-middle" );
		$sk->initPage();
		$this->out( $this->headElement() );

		$this->out( "\n<body" );
		$ops = $sk->getBodyOptions();
		foreach ( $ops as $name => $val ) {
			$this->out( " $name='$val'" );
		}
		$this->out( ">\n" );
		if ( $wgDebugComments ) {
			$this->out( "<!-- Wiki debugging output:\n" .
			  $this->mDebugtext . "-->\n" );
		}
		$this->out( $sk->beforeContent() );
		wfProfileOut();
		
		wfProfileIn( "OutputPage::output-bodytext" );
		$this->out( $this->mBodytext );
		wfProfileOut();
		wfProfileIn( "OutputPage::output-after" );
		$this->out( $sk->afterContent() );
		wfProfileOut();

		wfProfileOut(); # A hack - we can't report after here
		$this->out( $this->reportTime() );

		$this->out( "\n</body></html>" );
		flush();
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
		global $HTTP_SERVER_VARS, $wgInputEncoding, $wgOutputEncoding;
		global $wgUser, $wgLang;

		$wgInputEncoding = strtolower( $wgInputEncoding );
		$s = $HTTP_SERVER_VARS['HTTP_ACCEPT_CHARSET'];
		
		if( $wgUser->getOption( 'altencoding' ) ) {
			$wgLang->setAltEncoding();
			return;
		}

		if ( "" == $s ) {
			$wgOutputEncoding = strtolower( $wgOutputEncoding );
			return;
		}
		$a = explode( ",", $s );
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
		$wgOutputEncoding = $wgInputEncoding;
	}

	function reportTime()
	{
		global $wgRequestTime, $wgDebugLogFile, $HTTP_SERVER_VARS;
		global $wgProfiling, $wgProfileStack, $wgUser;

		list( $usec, $sec ) = explode( " ", microtime() );
		$now = (float)$sec + (float)$usec;

		list( $usec, $sec ) = explode( " ", $wgRequestTime );
		$start = (float)$sec + (float)$usec;
		$elapsed = $now - $start;

		if ( "" != $wgDebugLogFile ) {
			$prof = "";
			if( $wgProfiling and count( $wgProfileStack ) ) {
				$lasttime = $start;
				foreach( $wgProfileStack as $ile ) {
					# "foo::bar 99 0.12345 1 0.23456 2"
					if( preg_match( '/^(\S+)\s+([0-9]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)\s+([0-9\.]+)/', $ile, $m ) ) {
						$thisstart = (float)$m[3] + (float)$m[4] - $start;
						$thisend = (float)$m[5] + (float)$m[6] - $start;
						$thiselapsed = $thisend - $thisstart;
						$thispercent = $thiselapsed / $elapsed * 100.0;
						
						$prof .= sprintf( "\tat %04.3f in %04.3f (%2.1f%%) - %s %s\n",
							$thisstart, $thiselapsed, $thispercent,
							str_repeat( "*", $m[2] ), $m[1] );
						$lasttime = $thistime;
						#$prof .= "\t(^ $ile)\n";
					} else {
						$prof .= "\t?broken? $ile\n";
					}
				}
			}
		
			if( $forward = $HTTP_SERVER_VARS['HTTP_X_FORWARDED_FOR'] )
				$forward = " forwarded for $forward";
			if( $client = $HTTP_SERVER_VARS['HTTP_CLIENT_IP'] )
				$forward .= " client IP $client";
			if( $from = $HTTP_SERVER_VARS['HTTP_FROM'] )
				$forward .= " from $from";
			if( $forward )
				$forward = "\t(proxied via {$HTTP_SERVER_VARS['REMOTE_ADDR']}{$forward})";
			if($wgUser->getId() == 0)
				$forward .= " anon";
			$log = sprintf( "%s\t%04.3f\t%s\n",
			  gmdate( "YmdHis" ), $elapsed,
			  urldecode( $HTTP_SERVER_VARS['REQUEST_URI'] . $forward ) );
			error_log( $log . $prof, 3, $wgDebugLogFile );
		}
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
		$this->setHTMLTitle( wfMsg( "errorpagetitle" ) );
		$this->setPageTitle( wfMsg( $title ) );
		$this->setRobotpolicy( "noindex,nofollow" );
		$this->setArticleFlag( false );

		$this->mBodytext = "";
		$this->addHTML( "<p>" . wfMsg( $msg ) . "\n" );
		$this->returnToMain( false );

		$this->output();
		exit;
	}

	function sysopRequired()
	{
		global $wgUser;

		$this->setHTMLTitle( wfMsg( "errorpagetitle" ) );
		$this->setPageTitle( wfMsg( "sysoptitle" ) );
		$this->setRobotpolicy( "noindex,nofollow" );
		$this->setArticleFlag( false );
		$this->mBodytext = "";

		$sk = $wgUser->getSkin();
		$ap = $sk->makeKnownLink( wfMsg( "administrators" ), "" );	
		$text = str_replace( "$1", $ap, wfMsg( "sysoptext" ) );
		$this->addHTML( $text );
		$this->returnToMain();
	}

	function developerRequired()
	{
		global $wgUser;

		$this->setHTMLTitle( wfMsg( "errorpagetitle" ) );
		$this->setPageTitle( wfMsg( "developertitle" ) );
		$this->setRobotpolicy( "noindex,nofollow" );
		$this->setArticleFlag( false );
		$this->mBodytext = "";

		$sk = $wgUser->getSkin();
		$ap = $sk->makeKnownLink( wfMsg( "administrators" ), "" );	
		$text = str_replace( "$1", $ap, wfMsg( "developertext" ) );
		$this->addHTML( $text );
		$this->returnToMain();
	}

	function databaseError( $fname )
	{
		global $wgUser, $wgCommandLineMode;

		$this->setPageTitle( wfMsg( "databaseerror" ) );
		$this->setRobotpolicy( "noindex,nofollow" );
		$this->setArticleFlag( false );

		if ( $wgCommandLineMode ) {
			$msg = wfMsg( "dberrortextcl" );
		} else {
			$msg = wfMsg( "dberrortextcl" );
		}
		$msg = str_replace( "$1", htmlspecialchars( wfLastDBquery() ), $msg );
		$msg = str_replace( "$2", htmlspecialchars( $fname ), $msg );
		$msg = str_replace( "$3", wfLastErrno(), $msg );
		$msg = str_replace( "$4", htmlspecialchars( wfLastError() ), $msg );

		if ( $wgCommandLineMode ) {
			print $msg;
			exit();
		}
		$sk = $wgUser->getSkin();
		$shlink = $sk->makeKnownLink( wfMsg( "searchhelppage" ),
		  wfMsg( "searchingwikipedia" ) );
		$msg = str_replace( "$5", $shlink, $msg );

		$this->mBodytext = $msg;
		$this->output();
		exit();
	}

	function readOnlyPage()
	{
		global $wgUser, $wgReadOnlyFile;

		$this->setPageTitle( wfMsg( "readonly" ) );
		$this->setRobotpolicy( "noindex,nofollow" );
		$this->setArticleFlag( false );

		$reason = implode( "", file( $wgReadOnlyFile ) );
		$text = str_replace( "$1", $reason, wfMsg( "readonlytext" ) );
		$this->addHTML( $text );
		$this->returnToMain( false );
	}

	function fatalError( $message )
	{
		$this->setPageTitle( wfMsg( "internalerror" ) );
		$this->setRobotpolicy( "noindex,nofollow" );
		$this->setArticleFlag( false );

		$this->mBodytext = $message;
		$this->output();
		exit;
	}

	function unexpectedValueError( $name, $val )
	{
		$msg = str_replace( "$1", $name, wfMsg( "unexpected" ) );
		$msg = str_replace( "$2", $val, $msg );
		$this->fatalError( $msg );
	}

	function fileCopyError( $old, $new )
	{
		$msg = str_replace( "$1", $old, wfMsg( "filecopyerror" ) );
		$msg = str_replace( "$2", $new, $msg );
		$this->fatalError( $msg );
	}

	function fileRenameError( $old, $new )
	{
		$msg = str_replace( "$1", $old, wfMsg( "filerenameerror" ) );
		$msg = str_replace( "$2", $new, $msg );
		$this->fatalError( $msg );
	}

	function fileDeleteError( $name )
	{
		$msg = str_replace( "$1", $name, wfMsg( "filedeleteerror" ) );
		$this->fatalError( $msg );
	}

	function fileNotFoundError( $name )
	{
		$msg = str_replace( "$1", $name, wfMsg( "filenotfound" ) );
		$this->fatalError( $msg );
	}

	function returnToMain( $auto = true )
	{
		global $wgUser, $wgOut, $returnto;

		$sk = $wgUser->getSkin();
		if ( "" == $returnto ) {
			$returnto = wfMsg( "mainpage" );
		}
		$link = $sk->makeKnownLink( $returnto, "" );

		$r = str_replace( "$1", $link, wfMsg( "returnto" ) );
		if ( $auto ) {
			$wgOut->addMeta( "http:Refresh", "10;url=" .
			  wfLocalUrlE( wfUrlencode( $returnto ) ) );
		}
		$wgOut->addHTML( "\n<p>$r\n" );
	}

	# Well, OK, it's actually about 14 passes.  But since all the
	# hard lifting is done inside PHP's regex code, it probably
	# wouldn't speed things up much to add a real parser.
	#
	function doWikiPass2( $text, $linestart )
	{
		global $wgUser, $wgLang, $wgUseDynamicDates;
		wfProfileIn( "OutputPage::doWikiPass2" );
		
		$text = $this->removeHTMLtags( $text );
		$text = $this->replaceVariables( $text );

		$text = preg_replace( "/(^|\n)-----*/", "\\1<hr>", $text );
		$text = str_replace ( "<HR>", "<hr>", $text );

		$text = $this->doQuotes( $text );
		$text = $this->doHeadings( $text );
		$text = $this->doBlockLevels( $text, $linestart );
		
		if($wgUseDynamicDates) {
			$text = $wgLang->replaceDates( $text );
		}

		$text = $this->replaceExternalLinks( $text );
		$text = $this->replaceInternalLinks ( $text );

		$text = $this->magicISBN( $text );
		$text = $this->magicRFC( $text );
		$text = $this->formatHeadings( $text );

		$sk = $wgUser->getSkin();
		$text = $sk->transformContent( $text );

		wfProfileOut();
		return $text;
	}

	/* private */ function doQuotes( $text )
	{
		$text = preg_replace( "/'''(.+)'''/mU", "<strong>\$1</strong>", $text );
		$text = preg_replace( "/''(.+)''/mU", "<em>\$1</em>", $text );
		return $text;
	}

	/* private */ function doHeadings( $text )
	{
		for ( $i = 6; $i >= 1; --$i ) {
			$h = substr( "======", 0, $i );
			$text = preg_replace( "/^{$h}([^=]+){$h}(\\s|$)/m",
			  "<h{$i}>\\1</h{$i}>\\2", $text );
		}
		return $text;
	}

	# Note: we have to do external links before the internal ones,
	# and otherwise take great care in the order of things here, so
	# that we don't end up interpreting some URLs twice.

	/* private */ function replaceExternalLinks( $text )
	{
		wfProfileIn( "OutputPage::replaceExternalLinks" );
		$text = $this->subReplaceExternalLinks( $text, "http", true );
		$text = $this->subReplaceExternalLinks( $text, "https", true );
		$text = $this->subReplaceExternalLinks( $text, "ftp", false );
		$text = $this->subReplaceExternalLinks( $text, "gopher", false );
		$text = $this->subReplaceExternalLinks( $text, "news", false );
		$text = $this->subReplaceExternalLinks( $text, "mailto", false );
		wfProfileOut();
		return $text;
	}
	
	/* private */ function subReplaceExternalLinks( $s, $protocol, $autonumber )
	{
		global $wgUser, $printable;
		global $wgAllowExternalImages;


		$unique = "4jzAfzB8hNvf4sqyO9Edd8pSmk9rE2in0Tgw3";
		$uc = "A-Za-z0-9_\\/~%\\-+&*#?!=()@\\x80-\\xFF";
		
		# this is  the list of separators that should be ignored if they 
		# are the last character of an URL but that should be included
		# if they occur within the URL, e.g. "go to www.foo.com, where .."
		# in this case, the last comma should not become part of the URL,
		# but in "www.foo.com/123,2342,32.htm" it should.
		$sep = ",;\.:";   
		$fnc = "A-Za-z0-9_.,~%\\-+&;#*?!=()@\\x80-\\xFF";
		$images = "gif|png|jpg|jpeg";

		# PLEASE NOTE: The curly braces { } are not part of the regex,
		# they are interpreted as part of the string (used to tell PHP
		# that the content of the string should be inserted there).
		$e1 = "/(^|[^\\[])({$protocol}:)([{$uc}{$sep}]+)\\/([{$fnc}]+)\\." .
		  "((?i){$images})([^{$uc}]|$)/";
		  
		$e2 = "/(^|[^\\[])({$protocol}:)(([".$uc."]|[".$sep."][".$uc."])+)([^". $uc . $sep. "]|[".$sep."]|$)/";
		$sk = $wgUser->getSkin();

		if ( $autonumber and $wgAllowExternalImages) { # Use img tags only for HTTP urls
			$s = preg_replace( $e1, "\\1" . $sk->makeImage( "{$unique}:\\3" .
			  "/\\4.\\5", "\\4.\\5" ) . "\\6", $s );
		}
		$s = preg_replace( $e2, "\\1" . "<a href=\"{$unique}:\\3\"" .
		  $sk->getExternalLinkAttributes( "{$unique}:\\3", wfEscapeHTML(
		  "{$unique}:\\3" ) ) . ">" . wfEscapeHTML( "{$unique}:\\3" ) .
		  "</a>\\5", $s );
		$s = str_replace( $unique, $protocol, $s );

		$a = explode( "[{$protocol}:", " " . $s );
		$s = array_shift( $a );
		$s = substr( $s, 1 );

		$e1 = "/^([{$uc}"."{$sep}]+)](.*)\$/sD";
		$e2 = "/^([{$uc}"."{$sep}]+)\\s+([^\\]]+)](.*)\$/sD";

		foreach ( $a as $line ) {
			if ( preg_match( $e1, $line, $m ) ) {
				$link = "{$protocol}:{$m[1]}";
				$trail = $m[2];
				if ( $autonumber ) { $text = "[" . ++$this->mAutonumber . "]"; }
				else { $text = wfEscapeHTML( $link ); }
			} else if ( preg_match( $e2, $line, $m ) ) {
				$link = "{$protocol}:{$m[1]}";
				$text = $m[2];
				$trail = $m[3];			
			} else {
				$s .= "[{$protocol}:" . $line;
				continue;
			}
			if ( $printable == "yes") $paren = " (<i>" . htmlspecialchars ( $link ) . "</i>)";
			else $paren = "";
			$la = $sk->getExternalLinkAttributes( $link, $text );
			$s .= "<a href='{$link}'{$la}>{$text}</a>{$paren}{$trail}";

		}
		return $s;
	}

	/* private */ function replaceInternalLinks( $s )
	{
		global $wgTitle, $wgUser, $wgLang;
		global $wgLinkCache, $wgInterwikiMagic;
		global $wgNamespacesWithSubpages, $wgLanguageCode;
		wfProfileIn( $fname = "OutputPage::replaceInternalLinks" );

		wfProfileIn( "$fname-setup" );
		$tc = Title::legalChars() . "#";
		$sk = $wgUser->getSkin();

		$a = explode( "[[", " " . $s );
		$s = array_shift( $a );
		$s = substr( $s, 1 );

		$e1 = "/^([{$tc}]+)\\|([^]]+)]](.*)\$/sD";
		$e2 = "/^([{$tc}]+)]](.*)\$/sD";
		wfProfileOut();

		wfProfileIn( "$fname-loop" );
		foreach ( $a as $line ) {
			if ( preg_match( $e1, $line, $m ) ) { # page with alternate text
				
				$text = $m[2];
				$trail = $m[3];				
			
			} else if ( preg_match( $e2, $line, $m ) ) { # page with normal text
			
				$text = "";
				$trail = $m[2];			
			}
			
			else { # Invalid form; output directly
				$s .= "[[" . $line ;
				continue;
			}
			if(substr($m[1],0,1)=="/") { # subpage
				if(substr($m[1],-1,1)=="/") {                 # / at end means we don't want the slash to be shown
					$m[1]=substr($m[1],1,strlen($m[1])-2); 
					$noslash=$m[1];
					
				} else {
					$noslash=substr($m[1],1);
				}
				if($wgNamespacesWithSubpages[$wgTitle->getNamespace()]) { # subpages allowed here
					$link = $wgTitle->getPrefixedText(). "/" . trim($noslash);
					if(!$text) { 						
						$text= $m[1]; 
					} # this might be changed for ugliness reasons
				} else {
					$link = $noslash; # no subpage allowed, use standard link
				}
			} else { # no subpage
				$link = $m[1]; 
			}

			if ( preg_match( "/^((?:i|x|[a-z]{2,3})(?:-[a-z0-9]+)?|[A-Za-z\\x80-\\xff]+):(.*)\$/", $link,  $m ) ) {
				$pre = strtolower( $m[1] );
				$suf = $m[2];
				if ( $wgLang->getNsIndex( $pre ) ==
				  Namespace::getImage() ) {
					$nt = Title::newFromText( $suf );
					$name = $nt->getDBkey();
					if ( "" == $text ) { $text = $nt->GetText(); }

					$wgLinkCache->addImageLink( $name );
					$s .= $sk->makeImageLink( $name,
					  wfImageUrl( $name ), $text );
					$s .= $trail;
				} else if ( "media" == $pre ) {
					$nt = Title::newFromText( $suf );
					$name = $nt->getDBkey();
					if ( "" == $text ) { $text = $nt->GetText(); }

					$wgLinkCache->addImageLink( $name );
					$s .= $sk->makeMediaLink( $name,
					  wfImageUrl( $name ), $text );
					$s .= $trail;
				} else {
					$l = $wgLang->getLanguageName( $pre );
					if ( "" == $l or !$wgInterwikiMagic or
					  Namespace::isTalk( $wgTitle->getNamespace() ) ) {
						if ( "" == $text ) { $text = $link; }
						$s .= $sk->makeLink( $link, $text, "", $trail );
					} else if ( $pre != $wgLanguageCode ) {
						array_push( $this->mLanguageLinks, "$pre:$suf" );
						$s .= $trail;
					}
				}
#			} else if ( 0 == strcmp( "##", substr( $link, 0, 2 ) ) ) {
#				$link = substr( $link, 2 );
#				$s .= "<a name=\"{$link}\">{$text}</a>{$trail}";
			} else {
				if ( "" == $text ) { $text = $link; }
				$s .= $sk->makeLink( $link, $text, "", $trail );
			}
		}
		wfProfileOut();
		wfProfileOut();
		return $s;
	}

	# Some functions here used by doBlockLevels()
	#
	/* private */ function closeParagraph()
	{
		$result = "";
		if ( 0 != strcmp( "p", $this->mLastSection ) &&
		  0 != strcmp( "", $this->mLastSection ) ) {
			$result = "</" . $this->mLastSection  . ">";
		}
		$this->mLastSection = "";
		return $result;
	}
	# getCommon() returns the length of the longest common substring
	# of both arguments, starting at the beginning of both.
	#
	/* private */ function getCommon( $st1, $st2 )
	{
		$fl = strlen( $st1 );
		$shorter = strlen( $st2 );
		if ( $fl < $shorter ) { $shorter = $fl; }

		for ( $i = 0; $i < $shorter; ++$i ) {
			if ( $st1{$i} != $st2{$i} ) { break; }
		}
		return $i;
	}
	# These next three functions open, continue, and close the list
	# element appropriate to the prefix character passed into them.
	#
	/* private */ function openList( $char )
    {
		$result = $this->closeParagraph();

		if ( "*" == $char ) { $result .= "<ul><li>"; }
		else if ( "#" == $char ) { $result .= "<ol><li>"; }
		else if ( ":" == $char ) { $result .= "<dl><dd>"; }
		else if ( ";" == $char ) {
			$result .= "<dl><dt>";
			$this->mDTopen = true;
		}
		else { $result = "<!-- ERR 1 -->"; }

		return $result;
	}

	/* private */ function nextItem( $char )
	{
		if ( "*" == $char || "#" == $char ) { return "</li><li>"; }
		else if ( ":" == $char || ";" == $char ) {
			$close = "</dd>";
			if ( $this->mDTopen ) { $close = "</dt>"; }
			if ( ";" == $char ) {
				$this->mDTopen = true;
				return $close . "<dt>";
			} else {
				$this->mDTopen = false;
				return $close . "<dd>";
			}
		}
		return "<!-- ERR 2 -->";
	}

	/* private */function closeList( $char )
	{
		if ( "*" == $char ) { return "</li></ul>"; }
		else if ( "#" == $char ) { return "</li></ol>"; }
		else if ( ":" == $char ) {
			if ( $this->mDTopen ) {
				$this->mDTopen = false;
				return "</dt></dl>";
			} else {
				return "</dd></dl>";
			}
		}
		return "<!-- ERR 3 -->";
	}

	/* private */ function doBlockLevels( $text, $linestart )
	{
		wfProfileIn( "OutputPage::doBlockLevels" );
		# Parsing through the text line by line.  The main thing
		# happening here is handling of block-level elements p, pre,
		# and making lists from lines starting with * # : etc.
		#
		$a = explode( "\n", $text );
		$text = $lastPref = "";
		$this->mDTopen = $inBlockElem = false;

		if ( ! $linestart ) { $text .= array_shift( $a ); }
		foreach ( $a as $t ) {
			if ( "" != $text ) { $text .= "\n"; }

			$oLine = $t;
			$opl = strlen( $lastPref );
			$npl = strspn( $t, "*#:;" );
			$pref = substr( $t, 0, $npl );
			$pref2 = str_replace( ";", ":", $pref );
			$t = substr( $t, $npl );

			if ( 0 != $npl && 0 == strcmp( $lastPref, $pref2 ) ) {
				$text .= $this->nextItem( substr( $pref, -1 ) );

				if ( ";" == substr( $pref, -1 ) ) {
					$cpos = strpos( $t, ":" );
					if ( ! ( false === $cpos ) ) {
						$term = substr( $t, 0, $cpos );
						$text .= $term . $this->nextItem( ":" );
						$t = substr( $t, $cpos + 1 );
					}
				}
			} else if (0 != $npl || 0 != $opl) {
				$cpl = $this->getCommon( $pref, $lastPref );

				while ( $cpl < $opl ) {
					$text .= $this->closeList( $lastPref{$opl-1} );
					--$opl;
				}
				if ( $npl <= $cpl && $cpl > 0 ) {
					$text .= $this->nextItem( $pref{$cpl-1} );
				}
				while ( $npl > $cpl ) {
					$char = substr( $pref, $cpl, 1 );
					$text .= $this->openList( $char );

					if ( ";" == $char ) {
						$cpos = strpos( $t, ":" );
						if ( ! ( false === $cpos ) ) {
							$term = substr( $t, 0, $cpos );
							$text .= $term . $this->nextItem( ":" );
							$t = substr( $t, $cpos + 1 );
						}
					}
					++$cpl;
				}
				$lastPref = $pref2;
			}
			if ( 0 == $npl ) { # No prefix--go to paragraph mode
				if ( preg_match(
				  "/(<table|<blockquote|<h1|<h2|<h3|<h4|<h5|<h6)/i", $t ) ) {
					$text .= $this->closeParagraph();
					$inBlockElem = true;
				}
				if ( ! $inBlockElem ) {
					if ( " " == $t{0} ) {
						$newSection = "pre";
						# $t = wfEscapeHTML( $t );
					}
					else { $newSection = "p"; }

					if ( 0 == strcmp( "", trim( $oLine ) ) ) {
						$text .= $this->closeParagraph();
						$text .= "<" . $newSection . ">";
					} else if ( 0 != strcmp( $this->mLastSection,
					  $newSection ) ) {
						$text .= $this->closeParagraph();
						if ( 0 != strcmp( "p", $newSection ) ) {
							$text .= "<" . $newSection . ">";
						}
					}
					$this->mLastSection = $newSection;
				}
				if ( $inBlockElem &&
				  preg_match( "/(<\\/table|<\\/blockquote|<\\/h1|<\\/h2|<\\/h3|<\\/h4|<\\/h5|<\\/h6)/i", $t ) ) {
					$inBlockElem = false;
				}
			}
			$text .= $t;
		}
		while ( $npl ) {
			$text .= $this->closeList( $pref2{$npl-1} );
			--$npl;
		}
		if ( "" != $this->mLastSection ) {
			if ( "p" != $this->mLastSection ) {
				$text .= "</" . $this->mLastSection . ">";
			}
			$this->mLastSection = "";
		}
		wfProfileOut();
		return $text;
	}

	/* private */ function replaceVariables( $text )
	{
		global $wgLang;
		wfProfileIn( "OutputPage:replaceVariables" );

		/* As with sigs, use server's local time --
		   ensure this is appropriate for your audience! */
		$v = date( "m" );
		$text = str_replace( "{{CURRENTMONTH}}", $v, $text );
		$v = $wgLang->getMonthName( date( "n" ) );
		$text = str_replace( "{{CURRENTMONTHNAME}}", $v, $text );
		$v = $wgLang->getMonthNameGen( date( "n" ) );
		$text = str_replace( "{{CURRENTMONTHNAMEGEN}}", $v, $text );
		$v = date( "j" );
		$text = str_replace( "{{CURRENTDAY}}", $v, $text );
		$v = $wgLang->getWeekdayName( date( "w" )+1 );
		$text = str_replace( "{{CURRENTDAYNAME}}", $v, $text );
		$v = date( "Y" );
		$text = str_replace( "{{CURRENTYEAR}}", $v, $text );
		$v = $wgLang->time( wfTimestampNow(), false );
		$text = str_replace( "{{CURRENTTIME}}", $v, $text );

		if ( false !== strstr( $text, "{{NUMBEROFARTICLES}}" ) ) {
			$v = wfNumberOfArticles();
			$text = str_replace( "{{NUMBEROFARTICLES}}", $v, $text );
		}
		wfProfileOut();
		return $text;
	}

	/* private */ function removeHTMLtags( $text )
	{
		wfProfileIn( "OutputPage::removeHTMLtags" );
		$htmlpairs = array( # Tags that must be closed
			"b", "i", "u", "font", "big", "small", "sub", "sup", "h1",
			"h2", "h3", "h4", "h5", "h6", "cite", "code", "em", "s",
			"strike", "strong", "tt", "var", "div", "center",
			"blockquote", "ol", "ul", "dl", "table", "caption", "pre",
			"ruby", "rt" , "rb" , "rp"
		);
		$htmlsingle = array(
			"br", "p", "hr", "li", "dt", "dd"
		);
		$htmlnest = array( # Tags that can be nested--??
			"table", "tr", "td", "th", "div", "blockquote", "ol", "ul",
			"dl", "font", "big", "small", "sub", "sup"
		);
		$tabletags = array( # Can only appear inside table
			"td", "th", "tr"
		);

		$htmlsingle = array_merge( $tabletags, $htmlsingle );
		$htmlelements = array_merge( $htmlsingle, $htmlpairs );

		$htmlattrs = array( # Allowed attributes--no scripting, etc.
			"title", "align", "lang", "dir", "width", "height",
			"bgcolor", "clear", /* BR */ "noshade", /* HR */
			"cite", /* BLOCKQUOTE, Q */ "size", "face", "color",
			/* FONT */ "type", "start", "value", "compact",
			/* For various lists, mostly deprecated but safe */
			"summary", "width", "border", "frame", "rules",
			"cellspacing", "cellpadding", "valign", "char",
			"charoff", "colgroup", "col", "span", "abbr", "axis",
			"headers", "scope", "rowspan", "colspan", /* Tables */
			"id", "class", "name", "style" /* For CSS */
		);

		# Remove HTML comments
		$text = preg_replace( "/<!--.*-->/sU", "", $text );

		$bits = explode( "<", $text );
		$text = array_shift( $bits );
		$tagstack = array(); $tablestack = array();

		foreach ( $bits as $x ) {
			$prev = error_reporting( E_ALL & ~( E_NOTICE | E_WARNING ) );
			preg_match( "/^(\\/?)(\\w+)([^>]*)(\\/{0,1}>)([^<]*)$/",
			  $x, $regs );
			list( $qbar, $slash, $t, $params, $brace, $rest ) = $regs;
			error_reporting( $prev );

			$badtag = 0 ;
			if ( in_array( $t = strtolower( $t ), $htmlelements ) ) {
				# Check our stack
				if ( $slash ) {
					# Closing a tag...
					if ( ! in_array( $t, $htmlsingle ) &&
					  ( $ot = array_pop( $tagstack ) ) != $t ) {
						array_push( $tagstack, $ot );
						$badtag = 1;
					} else {
						if ( $t == "table" ) {
							$tagstack = array_pop( $tablestack );
						}
						$newparams = "";
					}
				} else {
					# Keep track for later
					if ( in_array( $t, $tabletags ) &&
					  ! in_array( "table", $tagstack ) ) {
						$badtag = 1;
					} else if ( in_array( $t, $tagstack ) &&
					  ! in_array ( $t , $htmlnest ) ) {
						$badtag = 1 ;
					} else if ( ! in_array( $t, $htmlsingle ) ) {
						if ( $t == "table" ) {
							array_push( $tablestack, $tagstack );
							$tagstack = array();
						}
						array_push( $tagstack, $t );
					}
					# Strip non-approved attributes from the tag
					$newparams = preg_replace(
					  "/(\\w+)(\\s*=\\s*([^\\s\">]+|\"[^\">]*\"))?/e",
					  "(in_array(strtolower(\"\$1\"),\$htmlattrs)?(\"\$1\".((\"x\$3\" != \"x\")?\"=\$3\":'')):'')",
					  $params);
				}
				if ( ! $badtag ) {
					$rest = str_replace( ">", "&gt;", $rest );
					$text .= "<$slash$t$newparams$brace$rest";
					continue;
				}
			}
			$text .= "&lt;" . str_replace( ">", "&gt;", $x);
		}
		# Close off any remaining tags
		while ( $t = array_pop( $tagstack ) ) {
			$text .= "</$t>\n";
			if ( $t == "table" ) { $tagstack = array_pop( $tablestack ); }
		}
		wfProfileOut();
		return $text;
	}


/* 
 * 
 * This function accomplishes several tasks:
 * 1) Auto-number headings if that option is enabled
 * 2) Add an [edit] link to sections for logged in users who have enabled the option
 * 3) Add a Table of contents on the top for users who have enabled the option
 * 4) Auto-anchor headings
 *
 * It loops through all headlines, collects the necessary data, then splits up the
 * string and re-inserts the newly formatted headlines.
 *
 * */
	/* private */ function formatHeadings( $text )
	{
		global $wgUser,$wgArticle,$wgTitle,$wpPreview;
		$nh=$wgUser->getOption( "numberheadings" );
		$st=$wgUser->getOption( "showtoc" );
		$es=$wgUser->getID() && $wgUser->getOption( "editsection" );
		if($wgTitle->getPrefixedText()==wfMsg("mainpage")) {$st=0;}

		$sk=$wgUser->getSkin();
		preg_match_all("/<H([1-6])(.*?>)(.*?)<\/H[1-6]>/i",$text,$matches);

		$c=0;

		foreach($matches[3] as $headline) {
			if($level) { $prevlevel=$level;}
			$level=$matches[1][$c];
			if(($nh||$st) && $prevlevel && $level>$prevlevel) { 
							
				$h[$level]=0; // reset when we enter a new level				
				$toc.=$sk->tocIndent($level-$prevlevel);
				$toclevel+=$level-$prevlevel;
			
			} 
			if(($nh||$st) && $level<$prevlevel) {
				$h[$level+1]=0; // reset when we step back a level
				$toc.=$sk->tocUnindent($prevlevel-$level);
				$toclevel-=$prevlevel-$level;

			}
			$h[$level]++; // count number of headlines for each level
			
			if($nh||$st) {
				for($i=1;$i<=$level;$i++) {
					if($h[$i]) {
						if($dot) {$numbering.=".";}
						$numbering.=$h[$i];
						$dot=1;					
					}
				}
			}

			
			$canonized_headline=preg_replace("/<.*?>/","",$headline); // strip out HTML
			$tocline=$canonized_headline;
			$canonized_headline=str_replace('"',"",$canonized_headline);
			$canonized_headline=str_replace(" ","_",trim($canonized_headline));			
			$refer[$c]=$canonized_headline;
			$refers[$canonized_headline]++;  // count how many in assoc. array so we can track dupes in anchors
			$refcount[$c]=$refers[$canonized_headline];
			if($nh||$st) {
				$tocline=$numbering ." ". $tocline;
				if($nh) {
					$headline=$numbering . " " . $headline; // the two are different if the line contains a link
				}				
			}
			$anchor=$canonized_headline;
			if($refcount[$c]>1) {$anchor.="_".$refcount[$c];}
			if($st) {
				$toc.=$sk->tocLine($anchor,$tocline,$toclevel);
			}
			if($es && !isset($wpPreview)) {
				$head[$c].=$sk->editSectionLink($c+1);
			}
			$head[$c].="<H".$level.$matches[2][$c]
			 ."<a name=\"".$anchor."\">"
			 .$headline
			 ."</a>"
			 ."</H".$level.">";
			$numbering="";
			$c++;
			$dot=0;
		}		

		if($st) {
			$toclines=$c;
			$toc.=$sk->tocUnindent($toclevel);
			$toc=$sk->tocTable($toc);
		}

		// split up and insert constructed headlines
		
		$blocks=preg_split("/<H[1-6].*?>.*?<\/H[1-6]>/i",$text);
		$i=0;


		foreach($blocks as $block) {
			if($es && !isset($wpPreview) && $c>0 && $i==0) {
				$full.=$sk->editSectionLink(0);				
			}
			$full.=$block;
			if($st && $toclines>3 && !$i) {
				$full="<a name=\"top\"></a>".$full.$toc;
			}

			$full.=$head[$i];
			$i++;
		}
		return $full;
	}

	/* private */ function magicISBN( $text )
	{
		global $wgLang;

		$a = split( "ISBN ", " $text" );
		if ( count ( $a ) < 2 ) return $text;
		$text = substr( array_shift( $a ), 1);
        $valid = "0123456789-ABCDEFGHIJKLMNOPQRSTUVWXYZ";

		foreach ( $a as $x ) {
			$isbn = $blank = "" ;
			while ( " " == $x{0} ) {
                $blank .= " ";
                $x = substr( $x, 1 );
			}
            while ( strstr( $valid, $x{0} ) != false ) {
				$isbn .= $x{0};
				$x = substr( $x, 1 );
			}
            $num = str_replace( "-", "", $isbn );
            $num = str_replace( " ", "", $num );

            if ( "" == $num ) {
				$text .= "ISBN $blank$x";
            } else {
				$text .= "<a href=\"" . wfLocalUrlE( $wgLang->specialPage(
				  "Booksources"), "isbn={$num}" ) . "\" CLASS=\"internal\">ISBN $isbn</a>";
				$text .= $x;
			}
		}
        return $text;
	}

	/* private */ function magicRFC( $text )
	{
		return $text;
	}

	/* private */ function headElement()
	{
		global $wgDocType, $wgDTD, $wgUser, $wgLanguageCode, $wgOutputEncoding;

		$ret = "<!DOCTYPE HTML PUBLIC \"$wgDocType\"\n        \"$wgDTD\">\n";

		if ( "" == $this->mHTMLtitle ) {
			$this->mHTMLtitle = $this->mPagetitle;
		}
		$ret .= "<html lang=\"$wgLanguageCode\"><head><title>{$this->mHTMLtitle}</title>\n";
		array_push( $this->mMetatags, array( "http:Content-type", "text/html; charset={$wgOutputEncoding}" ) );
		foreach ( $this->mMetatags as $tag ) {
			if ( 0 == strcasecmp( "http:", substr( $tag[0], 0, 5 ) ) ) {
				$a = "http-equiv";
				$tag[0] = substr( $tag[0], 5 );
			} else {
				$a = "name";
			}
			$ret .= "<meta $a=\"{$tag[0]}\" content=\"{$tag[1]}\">\n";
		}
		$p = $this->mRobotpolicy;
		if ( "" == $p ) { $p = "index,follow"; }
		$ret .= "<meta name=\"robots\" content=\"$p\">\n";

		if ( count( $this->mKeywords ) > 0 ) {
			$ret .= "<meta name=\"keywords\" content=\"" .
			  implode( ",", $this->mKeywords ) . "\">\n";
		}
		foreach ( $this->mLinktags as $tag ) {
			$ret .= "<link ";
			if ( "" != $tag[0] ) { $ret .= "rel=\"{$tag[0]}\" "; }
			if ( "" != $tag[1] ) { $ret .= "rev=\"{$tag[1]}\" "; }
			$ret .= "href=\"{$tag[2]}\">\n";
		}
		$sk = $wgUser->getSkin();
		$ret .= $sk->getHeadScripts();
		$ret .= $sk->getUserStyles();

		$ret .= "</head>\n";
		return $ret;
	}
}

?>
