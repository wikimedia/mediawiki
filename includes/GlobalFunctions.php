<?
# Global functions used everywhere

$wgNumberOfArticles = -1; # Unset
$wgTotalViews = -1;
$wgTotalEdits = -1;

global $IP;
include_once( "$IP/DatabaseFunctions.php" );
include_once( "$IP/UpdateClasses.php" );
include_once( "$IP/LogPage.php" );

# PHP 4.1+ has array_key_exists, PHP 4.0.6 has key_exists instead, and earlier
# versions of PHP have neither. So we roll our own. Note that this
# function will return false even for keys that exist but whose associated 
# value is NULL.
#
if ( phpversion() == "4.0.6" ) {
	function array_key_exists( $k, $a ) {
		return key_exists( $k, $a );
	}
} else if (phpversion() < "4.1") {
	function array_key_exists( $k, $a ) {
		return isset($a[$k]);
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
	global $wgOut, $wgDebugLogFile;

	if ( ! $logonly ) {
		$wgOut->debug( $text );
	}
	if ( "" != $wgDebugLogFile ) {
		error_log( $text, 3, $wgDebugLogFile );
	}
}

if( !isset( $wgProfiling ) )
	$wgProfiling = false;
$wgProfileStack = array();
$wgProfileWorkStack = array();

if( $wgProfiling ) {
	function wfProfileIn( $functionname )
	{
		global $wgProfileStack, $wgProfileWorkStack;
		array_push( $wgProfileWorkStack, "$functionname " .
			count( $wgProfileWorkStack ) . " " . microtime() );
	}

	function wfProfileOut() {
		global $wgProfileStack, $wgProfileWorkStack;
		$bit = array_pop( $wgProfileWorkStack );
		$bit .= " " . microtime();
		array_push( $wgProfileStack, $bit );
	}
} else {
	function wfProfileIn( $functionname ) { }
	function wfProfileOut( ) { }
}

function wfReadOnly()
{
	global $wgReadOnlyFile;

	if ( "" == $wgReadOnlyFile ) { return false; }
	return is_file( $wgReadOnlyFile );
}

$wgReplacementKeys = array( "$1", "$2", "$3", "$4", "$5", "$6", "$7", "$8", "$9" );
function wfMsg( $key )
{
	global $wgLang, $wgReplacementKeys;
	$ret = $wgLang->getMessage( $key );
	
	if( func_num_args() > 1 ) {
		$reps = func_get_args();
		array_shift( $reps );
		$ret = str_replace( $wgReplacementKeys, $reps, $ret );
	}

	if ( "" == $ret ) {
		user_error( "Couldn't find text for message \"{$key}\"." );
	}
	return $ret;
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

	$validSP = $wgLang->getValidSpecialPages();
	$sysopSP = $wgLang->getSysopSpecialPages();
	$devSP = $wgLang->getDeveloperSpecialPages();

	$wgOut->setArticleFlag( false );
	$wgOut->setRobotpolicy( "noindex,follow" );

	$t = $wgTitle->getDBkey();
	if ( array_key_exists( $t, $validSP ) ||
	  ( $wgUser->isSysop() && array_key_exists( $t, $sysopSP ) ) ||
	  ( $wgUser->isDeveloper() && array_key_exists( $t, $devSP ) ) ) {
		$wgOut->setPageTitle( wfMsg( strtolower( $wgTitle->getText() ) ) );

		$inc = "Special" . $t . ".php";
		include_once( $inc );
		$call = "wfSpecial" . $t;
		$call();
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
	$res = wfQuery( $sql, "wfLoadSiteStats" );

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
	$fname = "wfRecordUpload";

	$sql = "SELECT img_name,img_size,img_timestamp,img_description,img_user," .
	  "img_user_text FROM image WHERE img_name='" . wfStrencode( $name ) . "'";
	$res = wfQuery( $sql, $fname );

	if ( 0 == wfNumRows( $res ) ) {
		$sql = "INSERT INTO image (img_name,img_size,img_timestamp," .
		  "img_description,img_user,img_user_text) VALUES ('" .
		  wfStrencode( $name ) . "',{$size},'" . wfTimestampNow() . "','" .
		  wfStrencode( $desc ) . "', '" . $wgUser->getID() .
		  "', '" . wfStrencode( $wgUser->getName() ) . "')";
		wfQuery( $sql, $fname );

		$sql = "SELECT cur_id,cur_text FROM cur WHERE cur_namespace=" .
		  Namespace::getImage() . " AND cur_title='" .
		  wfStrencode( $name ) . "'";
		$res = wfQuery( $sql, $fname );
		if ( 0 == wfNumRows( $res ) ) {
			$now = wfTimestampNow();
			$won = wfInvertTimestamp( $now );
            $common =
			  Namespace::getImage() . ",'" .
			  wfStrencode( $name ) . "','" .
			  wfStrencode( $desc ) . "','" . $wgUser->getID() . "','" .
			  wfStrencode( $wgUser->getName() ) . "','" . $now .
			  "',1";
			$sql = "INSERT INTO cur (cur_namespace,cur_title," .
			  "cur_comment,cur_user,cur_user_text,cur_timestamp,cur_is_new," .
			  "cur_text,inverse_timestamp) VALUES (" .
			  $common .
			  ",'" . wfStrencode( $desc ) . "','{$won}')";
			wfQuery( $sql, $fname );
			$id = wfInsertId() or 0; # We should throw an error instead
			$sql = "INSERT INTO recentchanges (rc_namespace,rc_title,
				rc_comment,rc_user,rc_user_text,rc_timestamp,rc_new,
				rc_cur_id,rc_cur_time) VALUES ({$common},{$id},'{$now}')";
            wfQuery( $sql, $fname );
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
		wfQuery( $sql, $fname );

		$sql = "UPDATE image SET img_size={$size}," .
		  "img_timestamp='" . wfTimestampNow() . "',img_user='" .
		  $wgUser->getID() . "',img_user_text='" .
		  wfStrencode( $wgUser->getName() ) . "', img_description='" .
		  wfStrencode( $desc ) . "' WHERE img_name='" .
		  wfStrencode( $name ) . "'";
		wfQuery( $sql, $fname );
	}

	$log = new LogPage( wfMsg( "uploadlogpage" ), wfMsg( "uploadlogpagetext" ) );
	$da = str_replace( "$1", "[[:" . $wgLang->getNsText(
	  Namespace::getImage() ) . ":{$name}|{$name}]]",
	  wfMsg( "uploadedimage" ) );
	$ta = str_replace( "$1", $name, wfMsg( "uploadedimage" ) );
	$log->addEntry( $da, $desc, $ta );
}


/* Some generic result counters, pulled out of SearchEngine */

function wfShowingResults( $offset, $limit )
{
	$top = str_replace( "$1", $limit, wfMsg( "showingresults" ) );
	$top = str_replace( "$2", $offset+1, $top );
	return $top;
}

function wfShowingResultsNum( $offset, $limit, $num )
{
	$top = str_replace( "$1", $limit, wfMsg( "showingresultsnum" ) );
	$top = str_replace( "$2", $offset+1, $top );
	$top = str_replace( "$3", $num, $top );
	return $top;
}

function wfViewPrevNext( $offset, $limit, $link, $query = "" )
{
	global $wgUser;
	$prev = str_replace( "$1", $limit, wfMsg( "prevn" ) );
	$next = str_replace( "$1", $limit, wfMsg( "nextn" ) );

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

	$sl = str_replace( "$1", $plink, wfMsg( "viewprevnext" ) );
	$sl = str_replace( "$2", $nlink, $sl );
	$sl = str_replace( "$3", $nums, $sl );
	return $sl;
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

?>
