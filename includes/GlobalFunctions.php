<?
# Global functions used everywhere

$wgNumberOfArticles = -1; # Unset
$wgTotalViews = -1;
$wgTotalEdits = -1;

include_once( "DatabaseFunctions.php" );
include_once( "UpdateClasses.php" );
include_once( "LogPage.php" );

/*
 * Compatibility functions
 */

# PHP <4.3.x is not actively supported; 4.1.x and 4.2.x might or might not work.
# <4.1.x will not work, as we use a number of features introduced in 4.1.0
# such as the new autoglobals.

if( !function_exists('iconv') ) {
	# iconv support is not in the default configuration and so may not be present.
	# Assume will only ever use utf-8 and iso-8859-1.
	# This will *not* work in all circumstances.
	function iconv( $from, $to, $string ) {
		if(strcasecmp( $from, $to ) == 0) return $string;
		if(strcasecmp( $from, "utf-8" ) == 0) return utf8_decode( $string );
		if(strcasecmp( $to, "utf-8" ) == 0) return utf8_encode( $string );
		return $string;
	}
}

if( !function_exists('file_get_contents') ) {
	# Exists in PHP 4.3.0+
	function file_get_contents( $filename ) {
		return implode( "", file( $filename ) );
	}
}

$wgRandomSeeded = false;

function wfSeedRandom()
{
	global $wgRandomSeeded;

	if ( ! $wgRandomSeeded ) {
		$seed = hexdec(substr(md5(microtime()),-8)) & 0x7fffffff;
		mt_srand( $seed );
		$wgRandomSeeded = true;
	}
}

function wfLocalUrl( $a, $q = "" )
{
	global $wgServer, $wgScript, $wgArticlePath;

	$a = str_replace( " ", "_", $a );
	#$a = wfUrlencode( $a ); # This stuff is _already_ URL-encoded.

	if ( "" == $a ) {
		if( "" == $q ) {
			$a = $wgScript;
		} else {
			$a = "{$wgScript}?{$q}";
		}	
	} else if ( "" == $q ) {
		$a = str_replace( "$1", $a, $wgArticlePath );
	} else {
		$a = "{$wgScript}?title={$a}&{$q}";	
	}
	return $a;
}

function wfLocalUrlE( $a, $q = "" )
{
	return wfEscapeHTML( wfLocalUrl( $a, $q ) );
}

function wfFullUrl( $a, $q = "" ) {
	global $wgServer;
	return $wgServer . wfLocalUrl( $a, $q );
}

function wfFullUrlE( $a, $q = "" ) {
	return wfEscapeHTML( wfFullUrl( $a, $q ) );
}

function wfImageUrl( $img )
{
	global $wgUploadPath;

	$nt = Title::newFromText( $img );
	if( !$nt ) return "";

	$name = $nt->getDBkey();
	$hash = md5( $name );

	$url = "{$wgUploadPath}/" . $hash{0} . "/" .
	  substr( $hash, 0, 2 ) . "/{$name}";
	return wfUrlencode( $url );
}

function wfImageArchiveUrl( $name )
{
	global $wgUploadPath;

	$hash = md5( substr( $name, 15) );
	$url = "{$wgUploadPath}/archive/" . $hash{0} . "/" .
	  substr( $hash, 0, 2 ) . "/{$name}";
	return $url;
}

function wfUrlencode ( $s )
{
	$ulink = urlencode( $s );
	$ulink = preg_replace( "/%3[Aa]/", ":", $ulink );
	$ulink = preg_replace( "/%2[Ff]/", "/", $ulink );
	return $ulink;
}

function wfUtf8Sequence($codepoint) {
	if($codepoint <     0x80) return chr($codepoint);
	if($codepoint <    0x800) return chr($codepoint >>  6 & 0x3f | 0xc0) .
                                     chr($codepoint       & 0x3f | 0x80);
    if($codepoint <  0x10000) return chr($codepoint >> 12 & 0x0f | 0xe0) .
                                     chr($codepoint >>  6 & 0x3f | 0x80) .
                                     chr($codepoint       & 0x3f | 0x80);
	if($codepoint < 0x100000) return chr($codepoint >> 18 & 0x07 | 0xf0) . # Double-check this
	                                 chr($codepoint >> 12 & 0x3f | 0x80) .
                                     chr($codepoint >>  6 & 0x3f | 0x80) .
                                     chr($codepoint       & 0x3f | 0x80);
	# Doesn't yet handle outside the BMP
	return "&#$codepoint;";
}

function wfMungeToUtf8($string) {
	global $wgInputEncoding; # This is debatable
	#$string = iconv($wgInputEncoding, "UTF-8", $string);
	$string = preg_replace ( '/&#([0-9]+);/e', 'wfUtf8Sequence($1)', $string );
	$string = preg_replace ( '/&#x([0-9a-f]+);/ie', 'wfUtf8Sequence(0x$1)', $string );
	# Should also do named entities here
	return $string;
}

function wfDebug( $text, $logonly = false )
{
	global $wgOut, $wgDebugLogFile, $wgDebugComments, $wgProfileOnly;

	if ( $wgDebugComments && !$logonly ) {
		$wgOut->debug( $text );
	}
	if ( "" != $wgDebugLogFile && !$wgProfileOnly ) {
		error_log( $text, 3, $wgDebugLogFile );
	}
}

function logProfilingData()
{
	global $wgRequestTime, $wgDebugLogFile;
	global $wgProfiling, $wgProfileStack, $wgProfileLimit, $wgUser;
	list( $usec, $sec ) = explode( " ", microtime() );
	$now = (float)$sec + (float)$usec;

	list( $usec, $sec ) = explode( " ", $wgRequestTime );
	$start = (float)$sec + (float)$usec;
	$elapsed = $now - $start;
	if ( "" != $wgDebugLogFile ) {
		$prof = wfGetProfilingOutput( $start, $elapsed );
		if( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )
			$forward = " forwarded for " . $_SERVER['HTTP_X_FORWARDED_FOR'];
		if( !empty( $_SERVER['HTTP_CLIENT_IP'] ) )
			$forward .= " client IP " . $_SERVER['HTTP_CLIENT_IP'];
		if( !empty( $_SERVER['HTTP_FROM'] ) )
			$forward .= " from " . $_SERVER['HTTP_FROM'];
		if( $forward )
			$forward = "\t(proxied via {$_SERVER['REMOTE_ADDR']}{$forward})";
		if($wgUser->getId() == 0)
			$forward .= " anon";
		$log = sprintf( "%s\t%04.3f\t%s\n",
		  gmdate( "YmdHis" ), $elapsed,
		  urldecode( $_SERVER['REQUEST_URI'] . $forward ) );
		error_log( "TJOHEJ". $log . $prof, 3, $wgDebugLogFile );
	}
}


function wfReadOnly()
{
	global $wgReadOnlyFile;

	if ( "" == $wgReadOnlyFile ) { return false; }
	return is_file( $wgReadOnlyFile );
}

$wgReplacementKeys = array( "$1", "$2", "$3", "$4", "$5", "$6", "$7", "$8", "$9" );

# Get a message from anywhere
function wfMsg( $key ) {
	$args = func_get_args();
	if ( count( $args ) ) {
		array_shift( $args );
	}
	return wfMsgReal( $key, $args, true );
}

# Get a message from the language file
function wfMsgNoDB( $key ) {
	$args = func_get_args();
	if ( count( $args ) ) {
		array_shift( $args );
	}
	return wfMsgReal( $key, $args, false );
}

# Really get a message
function wfMsgReal( $key, $args, $useDB ) {
	global $wgLang, $wgReplacementKeys, $wgMemc, $wgDBname;
	global $wgUseDatabaseMessages, $wgUseMemCached, $wgOut;
	global $wgAllMessagesEn, $wgLanguageCode;

	$fname = "wfMsg";
	wfProfileIn( $fname );
	
	static $messageCache = false;
	$memcKey = "$wgDBname:messages";
	$fname = "wfMsg";
	$message = false;

	# newFromText is too slow!
	$title = ucfirst( $key );
	if ( $messageCache ) {
		$message = $messageCache[$title];
	} elseif ( !$wgUseDatabaseMessages || !$useDB ) {
		$message = $wgLang->getMessage( $key );
	}

	if ( !$message && $wgUseMemCached ) {
		# Try memcached
		if ( !$messageCache ) {
			$messageCache = $wgMemc->get( $memcKey );
		}
		
		# If there's nothing in memcached, load all the messages from the database
		# This should only happen on server reset -- ordinary changes should update
		# memcached in editUpdates()
		if ( !$messageCache ) {
			# Other threads don't need to load the messages if another thread is doing it.
			$wgMemc->set( $memcKey, "loading", time() + 60 );
			$messageCache = wfLoadAllMessages();
			# Save in memcached
			$wgMemc->set( $memcKey, $messageCache, time() + 3600 );
			
			
		}
		if ( is_array( $messageCache ) && array_key_exists( $title, $messageCache ) ) {
			$message = $messageCache[$title];
		} elseif ( $messageCache == "loading" ) {
			$messageCache = false;
		}
	}

	# If there was no MemCached, load each message from the DB individually
	if ( !$message ) {
		if ( $useDB ) {
			$sql = "SELECT cur_text FROM cur WHERE cur_namespace=" . NS_MEDIAWIKI . 
				" AND cur_title='$title'";
			$res = wfQuery( $sql, DB_READ, $fname );

			if ( wfNumRows( $res ) ) {
				$obj = wfFetchObject( $res );
				$message = $obj->cur_text;
				wfFreeResult( $res );
			}
		}
	}

	# Try the array in $wgLang
	if ( !$message ) {
		$message = $wgLang->getMessage( $key );
	} 

	# Try the English array
	if ( !$message && $wgLanguageCode != "en" ) {
		$message = Language::getMessage( $key );
	}
	
	# Replace arguments
	if( count( $args ) ) {
		$message = str_replace( $wgReplacementKeys, $args, $message );
	}
	wfProfileOut( $fname );
	if ( !$message ) {
		# Failed, message does not exist
		return "&lt;$key&gt;";
	}
	return $message;
}

function wfCleanFormFields( $fields )
{
	global $HTTP_POST_VARS;
	global $wgInputEncoding, $wgOutputEncoding, $wgEditEncoding, $wgLang;

	if ( get_magic_quotes_gpc() ) {
		foreach ( $fields as $fname ) {
			if ( isset( $HTTP_POST_VARS[$fname] ) ) {
				$HTTP_POST_VARS[$fname] = stripslashes(
				  $HTTP_POST_VARS[$fname] );
			}
			global ${$fname};
			if ( isset( ${$fname} ) ) {
				${$fname} = stripslashes( ${$fname} );
			}
		}
	}
	$enc = $wgOutputEncoding;
	if( $wgEditEncoding != "") $enc = $wgEditEncoding;
	if ( $enc != $wgInputEncoding ) {
		foreach ( $fields as $fname ) {
			if ( isset( $HTTP_POST_VARS[$fname] ) ) {
				$HTTP_POST_VARS[$fname] = $wgLang->iconv(
				  $wgOutputEncoding, $wgInputEncoding,
				  $HTTP_POST_VARS[$fname] );
			}
			global ${$fname};
			if ( isset( ${$fname} ) ) {
				${$fname} = $wgLang->iconv(
				  $enc, $wgInputEncoding, ${$fname} );
			}
		}
	}
}

function wfMungeQuotes( $in )
{
	$out = str_replace( "%", "%25", $in );
	$out = str_replace( "'", "%27", $out );
	$out = str_replace( "\"", "%22", $out );
	return $out;
}

function wfDemungeQuotes( $in )
{
	$out = str_replace( "%22", "\"", $in );
	$out = str_replace( "%27", "'", $out );
	$out = str_replace( "%25", "%", $out );
	return $out;
}

function wfCleanQueryVar( $var )
{
	global $wgLang;
	if ( get_magic_quotes_gpc() ) {
		$var = stripslashes( $var );
	}
	return $wgLang->recodeInput( $var );
}

function wfSpecialPage()
{
	global $wgUser, $wgOut, $wgTitle, $wgLang;

	/* FIXME: this list probably shouldn't be language-specific, per se */
	$validSP = $wgLang->getValidSpecialPages();
	$sysopSP = $wgLang->getSysopSpecialPages();
	$devSP = $wgLang->getDeveloperSpecialPages();

	$wgOut->setArticleFlag( false );
	$wgOut->setRobotpolicy( "noindex,follow" );

	$par = NULL;
	list($t, $par) = split( "/", $wgTitle->getDBkey(), 2 );

	if ( array_key_exists( $t, $validSP ) ||
	  ( $wgUser->isSysop() && array_key_exists( $t, $sysopSP ) ) ||
	  ( $wgUser->isDeveloper() && array_key_exists( $t, $devSP ) ) ) {
	  	if($par !== NULL)
			$wgTitle = Title::makeTitle( Namespace::getSpecial(), $t );

		$wgOut->setPageTitle( wfMsg( strtolower( $wgTitle->getText() ) ) );

		$inc = "Special" . $t . ".php";
		include_once( $inc );
		$call = "wfSpecial" . $t;
		$call( $par );
	} else if ( array_key_exists( $t, $sysopSP ) ) {
		$wgOut->sysopRequired();
	} else if ( array_key_exists( $t, $devSP ) ) {
		$wgOut->developerRequired();
	} else {
		$wgOut->errorpage( "nosuchspecialpage", "nospecialpagetext" );
	}
}

function wfSearch( $s )
{
	$se = new SearchEngine( wfCleanQueryVar( $s ) );
	$se->showResults();
}

function wfGo( $s )
{ # pick the nearest match
	$se = new SearchEngine( wfCleanQueryVar( $s ) );
	$se->goResult();
}

/* private */ $wgAbruptExitCalled = false;

# Just like exit() but makes a note of it.
function wfAbruptExit(){
	// Safety to avoid infinite recursion in case of (unlikely) bugs somewhere
	global $wgAbruptExitCalled;
	if ( $wgAbruptExitCalled ){
		exit();
	}
	$wgAbruptExitCalled = true;

	$bt = debug_backtrace();
	for($i = 0; $i < count($bt) ; $i++){
		$file = $bt[$i]["file"];
		$line = $bt[$i]["line"];
		wfDebug("WARNING: Abrupt exit in $file at line $line\n");
	}
	exit();
}

function wfNumberOfArticles()
{
	global $wgNumberOfArticles;

	wfLoadSiteStats();
	return $wgNumberOfArticles;
}

/* private */ function wfLoadSiteStats()
{
	global $wgNumberOfArticles, $wgTotalViews, $wgTotalEdits;
	if ( -1 != $wgNumberOfArticles ) return;

	$sql = "SELECT ss_total_views, ss_total_edits, ss_good_articles " .
	  "FROM site_stats WHERE ss_row_id=1";
	$res = wfQuery( $sql, DB_READ, "wfLoadSiteStats" );

	if ( 0 == wfNumRows( $res ) ) { return; }
	else {
		$s = wfFetchObject( $res );
		$wgTotalViews = $s->ss_total_views;
		$wgTotalEdits = $s->ss_total_edits;
		$wgNumberOfArticles = $s->ss_good_articles;
	}
}

function wfEscapeHTML( $in )
{
	return str_replace(
		array( "&", "\"", ">", "<" ),
		array( "&amp;", "&quot;", "&gt;", "&lt;" ),
		$in );
}

function wfEscapeHTMLTagsOnly( $in ) {
	return str_replace(
		array( "\"", ">", "<" ),
		array( "&quot;", "&gt;", "&lt;" ),
		$in );
}

function wfUnescapeHTML( $in )
{
	$in = str_replace( "&lt;", "<", $in );
	$in = str_replace( "&gt;", ">", $in );
	$in = str_replace( "&quot;", "\"", $in );
	$in = str_replace( "&amp;", "&", $in );
	return $in;
}

function wfImageDir( $fname )
{
	global $wgUploadDirectory;

	$hash = md5( $fname );
	$oldumask = umask(0);
	$dest = $wgUploadDirectory . "/" . $hash{0};
	if ( ! is_dir( $dest ) ) { mkdir( $dest, 0777 ); }
	$dest .= "/" . substr( $hash, 0, 2 );
	if ( ! is_dir( $dest ) ) { mkdir( $dest, 0777 ); }
	
	umask( $oldumask );
	return $dest;
}

function wfImageArchiveDir( $fname )
{
	global $wgUploadDirectory;

	$hash = md5( $fname );
	$oldumask = umask(0);
	$archive = "{$wgUploadDirectory}/archive";
	if ( ! is_dir( $archive ) ) { mkdir( $archive, 0777 ); }
	$archive .= "/" . $hash{0};
	if ( ! is_dir( $archive ) ) { mkdir( $archive, 0777 ); }
	$archive .= "/" . substr( $hash, 0, 2 );
	if ( ! is_dir( $archive ) ) { mkdir( $archive, 0777 ); }

	umask( $oldumask );
	return $archive;
}

function wfRecordUpload( $name, $oldver, $size, $desc )
{
	global $wgUser, $wgLang, $wgTitle, $wgOut, $wgDeferredUpdateList;
	global $wgUseCopyrightUpload , $wpUploadCopyStatus , $wpUploadSource ;
	
	$fname = "wfRecordUpload";

	$sql = "SELECT img_name,img_size,img_timestamp,img_description,img_user," .
	  "img_user_text FROM image WHERE img_name='" . wfStrencode( $name ) . "'";
	$res = wfQuery( $sql, DB_READ, $fname );

	$now = wfTimestampNow();
	$won = wfInvertTimestamp( $now );
	$size = IntVal( $size );
	
	if ( $wgUseCopyrightUpload )
	  {
	    $textdesc = "== " . wfMsg ( "filedesc" ) . " ==\n" . $desc . "\n" .
	      "== " . wfMsg ( "filestatus" ) . " ==\n" . $wpUploadCopyStatus . "\n" .
	      "== " . wfMsg ( "filesource" ) . " ==\n" . $wpUploadSource ;
	  }
	else $textdesc = $desc ;

	$now = wfTimestampNow();
	$won = wfInvertTimestamp( $now );
	
	if ( 0 == wfNumRows( $res ) ) {
		$sql = "INSERT INTO image (img_name,img_size,img_timestamp," .
		  "img_description,img_user,img_user_text) VALUES ('" .
		  wfStrencode( $name ) . "',$size,'{$now}','" .
		  wfStrencode( $desc ) . "', '" . $wgUser->getID() .
		  "', '" . wfStrencode( $wgUser->getName() ) . "')";
		wfQuery( $sql, DB_WRITE, $fname );

		$sql = "SELECT cur_id,cur_text FROM cur WHERE cur_namespace=" .
		  Namespace::getImage() . " AND cur_title='" .
		  wfStrencode( $name ) . "'";
		$res = wfQuery( $sql, DB_READ, $fname );
		if ( 0 == wfNumRows( $res ) ) {
            $common =
			  Namespace::getImage() . ",'" .
			  wfStrencode( $name ) . "','" .
			  wfStrencode( $desc ) . "','" . $wgUser->getID() . "','" .
			  wfStrencode( $wgUser->getName() ) . "','" . $now .
			  "',1";
			$sql = "INSERT INTO cur (cur_namespace,cur_title," .
			  "cur_comment,cur_user,cur_user_text,cur_timestamp,cur_is_new," .
			  "cur_text,inverse_timestamp,cur_touched) VALUES (" .
			  $common .
			  ",'" . wfStrencode( $textdesc ) . "','{$won}','{$now}')";
			wfQuery( $sql, DB_WRITE, $fname );
			$id = wfInsertId() or 0; # We should throw an error instead
			$sql = "INSERT INTO recentchanges (rc_namespace,rc_title,
				rc_comment,rc_user,rc_user_text,rc_timestamp,rc_new,
				rc_cur_id,rc_cur_time) VALUES ({$common},{$id},'{$now}')";
            wfQuery( $sql, DB_WRITE, $fname );
			$u = new SearchUpdate( $id, $name, $desc );
			$u->doUpdate();
		}
	} else {
		$s = wfFetchObject( $res );

		$sql = "INSERT INTO oldimage (oi_name,oi_archive_name,oi_size," .
		  "oi_timestamp,oi_description,oi_user,oi_user_text) VALUES ('" .
		  wfStrencode( $s->img_name ) . "','" .
		  wfStrencode( $oldver ) .
		  "',{$s->img_size},'{$s->img_timestamp}','" .
		  wfStrencode( $s->img_description ) . "','" .
		  wfStrencode( $s->img_user ) . "','" .
		  wfStrencode( $s->img_user_text) . "')";
		wfQuery( $sql, DB_WRITE, $fname );

		$sql = "UPDATE image SET img_size={$size}," .
		  "img_timestamp='" . wfTimestampNow() . "',img_user='" .
		  $wgUser->getID() . "',img_user_text='" .
		  wfStrencode( $wgUser->getName() ) . "', img_description='" .
		  wfStrencode( $desc ) . "' WHERE img_name='" .
		  wfStrencode( $name ) . "'";
		wfQuery( $sql, DB_WRITE, $fname );
		
		$sql = "UPDATE cur SET cur_touched='{$now}' WHERE cur_namespace=" .
		  Namespace::getImage() . " AND cur_title='" .
		  wfStrencode( $name ) . "'";
		wfQuery( $sql, DB_WRITE, $fname );
	}

	$log = new LogPage( wfMsg( "uploadlogpage" ), wfMsg( "uploadlogpagetext" ) );
	$da = wfMsg( "uploadedimage", "[[:" . $wgLang->getNsText(
	  Namespace::getImage() ) . ":{$name}|{$name}]]" );
	$ta = wfMsg( "uploadedimage", $name );
	$log->addEntry( $da, $desc, $ta );
}


/* Some generic result counters, pulled out of SearchEngine */

function wfShowingResults( $offset, $limit )
{
	return wfMsg( "showingresults", $limit, $offset+1 );
}

function wfShowingResultsNum( $offset, $limit, $num )
{
	return wfMsg( "showingresultsnum", $limit, $offset+1, $num );
}

function wfViewPrevNext( $offset, $limit, $link, $query = "" )
{
	global $wgUser;
	$prev = wfMsg( "prevn", $limit );
	$next = wfMsg( "nextn", $limit );

	$sk = $wgUser->getSkin();
	if ( 0 != $offset ) {
		$po = $offset - $limit;
		if ( $po < 0 ) { $po = 0; }
		$q = "limit={$limit}&offset={$po}";
		if ( "" != $query ) { $q .= "&{$query}"; }
		$plink = "<a href=\"" . wfLocalUrlE( $link, $q ) . "\">{$prev}</a>";
	} else { $plink = $prev; }

	$no = $offset + $limit;
	$q = "limit={$limit}&offset={$no}";
	if ( "" != $query ) { $q .= "&{$query}"; }

	$nlink = "<a href=\"" . wfLocalUrlE( $link, $q ) . "\">{$next}</a>";
	$nums = wfNumLink( $offset, 20, $link , $query ) . " | " .
	  wfNumLink( $offset, 50, $link, $query ) . " | " .
	  wfNumLink( $offset, 100, $link, $query ) . " | " .
	  wfNumLink( $offset, 250, $link, $query ) . " | " .
	  wfNumLink( $offset, 500, $link, $query );

	return wfMsg( "viewprevnext", $plink, $nlink, $nums );
}

function wfNumLink( $offset, $limit, $link, $query = "" )
{
	global $wgUser;
	if ( "" == $query ) { $q = ""; }
	else { $q = "{$query}&"; }
	$q .= "limit={$limit}&offset={$offset}";

	$s = "<a href=\"" . wfLocalUrlE( $link, $q ) . "\">{$limit}</a>";
	return $s;
}

function wfClientAcceptsGzip() {
	global $wgUseGzip;
	if( $wgUseGzip ) {
		# FIXME: we may want to blacklist some broken browsers
		if( preg_match(
			'/\bgzip(?:;(q)=([0-9]+(?:\.[0-9]+)))?\b/',
			$_SERVER["HTTP_ACCEPT_ENCODING"],
			$m ) ) {
			if( ( $m[1] == "q" ) && ( $m[2] == 0 ) ) return false;
			wfDebug( " accepts gzip\n" );
			return true;
		}
	}
	return false;
}

# Yay, more global functions!
function wfCheckLimits( $deflimit = 50, $optionname = "rclimit" ) {
	global $wgUser;
	
	$limit = (int)$_REQUEST['limit'];
	if( $limit < 0 ) $limit = 0;
	if( ( $limit == 0 ) && ( $optionname != "" ) ) {
		$limit = (int)$wgUser->getOption( $optionname );
	}
	if( $limit <= 0 ) $limit = $deflimit;
	if( $limit > 5000 ) $limit = 5000; # We have *some* limits...
	
	$offset = (int)$_REQUEST['offset'];
	$offset = (int)$offset;
	if( $offset < 0 ) $offset = 0;
	if( $offset > 65000 ) $offset = 65000; # do we need a max? what?
	
	return array( $limit, $offset );
}

# Escapes the given text so that it may be output using addWikiText() 
# without any linking, formatting, etc. making its way through. This 
# is achieved by substituting certain characters with HTML entities.
# As required by the callers, <nowiki> is not used. It currently does
# not filter out characters which have special meaning only at the
# start of a line, such as "*".
function wfEscapeWikiText( $text )
{
	$text = str_replace( 
		array( '[',     "'",     'ISBN '    , '://'     , "\n=" ),
		array( '&#91;', '&#39;', 'ISBN&#32;', '&#58;//' , "\n&#61;" ),
		htmlspecialchars($text) );
	return $text;
}

# Loads the entire MediaWiki namespace, returns the array
function wfLoadAllMessages()
{
	$sql = "SELECT cur_title,cur_text FROM cur WHERE cur_namespace=" . NS_MEDIAWIKI;
	$res = wfQuery( $sql, DB_READ, $fname );
	
	$messages = array();
	for ( $row = wfFetchObject( $res ); $row; $row = wfFetchObject( $res ) ) {
		$messages[$row->cur_title] = $row->cur_text;
	}
	wfFreeResult( $res );
	return $messages;
}

function wfQuotedPrintable( $string, $charset = "" ) 
{
	# Probably incomplete; see RFC 2045
	if( empty( $charset ) ) {
		global $wgInputEncoding;
		$charset = $wgInputEncoding;
	}
	$charset = strtoupper( $charset );
	$charset = str_replace( "ISO-8859", "ISO8859", $charset ); // ?

	$illegal = '\x00-\x08\x0b\x0c\x0e-\x1f\x7f-\xff=';
	$replace = $illegal . '\t ?_';
	if( !preg_match( "/[$illegal]/", $string ) ) return $string;
	$out = "=?$charset?Q?";
	$out .= preg_replace( "/([$replace])/e", 'sprintf("=%02X",ord("$1"))', $string );
	$out .= "?=";
	return $out;
}


?>
