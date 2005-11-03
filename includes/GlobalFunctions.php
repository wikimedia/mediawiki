<?php

/**
 * Global functions used everywhere
 * @package MediaWiki
 */

/**
 * Some globals and requires needed
 */

/**
 * Total number of articles
 * @global integer $wgNumberOfArticles
 */
$wgNumberOfArticles = -1; # Unset
/**
 * Total number of views
 * @global integer $wgTotalViews
 */
$wgTotalViews = -1;
/**
 * Total number of edits
 * @global integer $wgTotalEdits
 */
$wgTotalEdits = -1;


require_once( 'DatabaseFunctions.php' );
require_once( 'UpdateClasses.php' );
require_once( 'LogPage.php' );
require_once( 'normal/UtfNormalUtil.php' );

/**
 * Compatibility functions
 * PHP <4.3.x is not actively supported; 4.1.x and 4.2.x might or might not work.
 * <4.1.x will not work, as we use a number of features introduced in 4.1.0
 * such as the new autoglobals.
 */
if( !function_exists('iconv') ) {
	# iconv support is not in the default configuration and so may not be present.
	# Assume will only ever use utf-8 and iso-8859-1.
	# This will *not* work in all circumstances.
	function iconv( $from, $to, $string ) {
		if(strcasecmp( $from, $to ) == 0) return $string;
		if(strcasecmp( $from, 'utf-8' ) == 0) return utf8_decode( $string );
		if(strcasecmp( $to, 'utf-8' ) == 0) return utf8_encode( $string );
		return $string;
	}
}

if( !function_exists('file_get_contents') ) {
	# Exists in PHP 4.3.0+
	function file_get_contents( $filename ) {
		return implode( '', file( $filename ) );
	}
}

if( !function_exists('is_a') ) {
	# Exists in PHP 4.2.0+
	function is_a( $object, $class_name ) {
		return
			(strcasecmp( get_class( $object ), $class_name ) == 0) ||
			 is_subclass_of( $object, $class_name );
	}
}

# UTF-8 substr function based on a PHP manual comment
if ( !function_exists( 'mb_substr' ) ) {
	function mb_substr( $str, $start ) {
		preg_match_all( '/./us', $str, $ar );

		if( func_num_args() >= 3 ) {
			$end = func_get_arg( 2 );
			return join( '', array_slice( $ar[0], $start, $end ) );
		} else {
			return join( '', array_slice( $ar[0], $start ) );
		}
	}
}

if( !function_exists( 'floatval' ) ) {
	/**
	 * First defined in PHP 4.2.0
	 * @param mixed $var;
	 * @return float
	 */
	function floatval( $var ) {
		return (float)$var;
	}
}

/**
 * Where as we got a random seed
 * @var bool $wgTotalViews
 */
$wgRandomSeeded = false;

/**
 * Seed Mersenne Twister
 * Only necessary in PHP < 4.2.0
 *
 * @return bool
 */
function wfSeedRandom() {
	global $wgRandomSeeded;

	if ( ! $wgRandomSeeded && version_compare( phpversion(), '4.2.0' ) < 0 ) {
		$seed = hexdec(substr(md5(microtime()),-8)) & 0x7fffffff;
		mt_srand( $seed );
		$wgRandomSeeded = true;
	}
}

/**
 * Get a random decimal value between 0 and 1, in a way
 * not likely to give duplicate values for any realistic
 * number of articles.
 *
 * @return string
 */
function wfRandom() {
	# The maximum random value is "only" 2^31-1, so get two random
	# values to reduce the chance of dupes
	$max = mt_getrandmax();
	$rand = number_format( (mt_rand() * $max + mt_rand())
		/ $max / $max, 12, '.', '' );
	return $rand;
}

/**
 * We want / and : to be included as literal characters in our title URLs.
 * %2F in the page titles seems to fatally break for some reason.
 *
 * @param string $s
 * @return string
*/
function wfUrlencode ( $s ) {
	$s = urlencode( $s );
	$s = preg_replace( '/%3[Aa]/', ':', $s );
	$s = preg_replace( '/%2[Ff]/', '/', $s );

	return $s;
}

/**
 * Sends a line to the debug log if enabled or, optionally, to a comment in output.
 * In normal operation this is a NOP.
 *
 * Controlling globals:
 * $wgDebugLogFile - points to the log file
 * $wgProfileOnly - if set, normal debug messages will not be recorded.
 * $wgDebugRawPage - if false, 'action=raw' hits will not result in debug output.
 * $wgDebugComments - if on, some debug items may appear in comments in the HTML output.
 *
 * @param string $text
 * @param bool $logonly Set true to avoid appearing in HTML when $wgDebugComments is set
 */
function wfDebug( $text, $logonly = false ) {
	global $wgOut, $wgDebugLogFile, $wgDebugComments, $wgProfileOnly, $wgDebugRawPage;

	# Check for raw action using $_GET not $wgRequest, since the latter might not be initialised yet
	if ( isset( $_GET['action'] ) && $_GET['action'] == 'raw' && !$wgDebugRawPage ) {
		return;
	}

	if ( isset( $wgOut ) && $wgDebugComments && !$logonly ) {
		$wgOut->debug( $text );
	}
	if ( '' != $wgDebugLogFile && !$wgProfileOnly ) {
		# Strip unprintables; they can switch terminal modes when binary data
		# gets dumped, which is pretty annoying.
		$text = preg_replace( '![\x00-\x08\x0b\x0c\x0e-\x1f]!', ' ', $text );
		@error_log( $text, 3, $wgDebugLogFile );
	}
}

/**
 * Send a line to a supplementary debug log file, if configured, or main debug log if not.
 * $wgDebugLogGroups[$logGroup] should be set to a filename to send to a separate log.
 *
 * @param string $logGroup
 * @param string $text
 * @param bool $public Whether to log the event in the public log if no private
 *                     log file is specified, (default true)
 */
function wfDebugLog( $logGroup, $text, $public = true ) {
	global $wgDebugLogGroups, $wgDBname;
	if( $text{strlen( $text ) - 1} != "\n" ) $text .= "\n";
	if( isset( $wgDebugLogGroups[$logGroup] ) ) {
		@error_log( "$wgDBname: $text", 3, $wgDebugLogGroups[$logGroup] );
	} else if ( $public === true ) {
		wfDebug( $text, true );
	}
}

/**
 * Log for database errors
 * @param string $text Database error message.
 */
function wfLogDBError( $text ) {
	global $wgDBerrorLog;
	if ( $wgDBerrorLog ) {
		$text = date('D M j G:i:s T Y') . "\t".$text;
		error_log( $text, 3, $wgDBerrorLog );
	}
}

/**
 * @todo document
 */
function logProfilingData() {
	global $wgRequestTime, $wgDebugLogFile, $wgDebugRawPage, $wgRequest;
	global $wgProfiling, $wgProfileStack, $wgProfileLimit, $wgUser;
	$now = wfTime();

	list( $usec, $sec ) = explode( ' ', $wgRequestTime );
	$start = (float)$sec + (float)$usec;
	$elapsed = $now - $start;
	if ( $wgProfiling ) {
		$prof = wfGetProfilingOutput( $start, $elapsed );
		$forward = '';
		if( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )
			$forward = ' forwarded for ' . $_SERVER['HTTP_X_FORWARDED_FOR'];
		if( !empty( $_SERVER['HTTP_CLIENT_IP'] ) )
			$forward .= ' client IP ' . $_SERVER['HTTP_CLIENT_IP'];
		if( !empty( $_SERVER['HTTP_FROM'] ) )
			$forward .= ' from ' . $_SERVER['HTTP_FROM'];
		if( $forward )
			$forward = "\t(proxied via {$_SERVER['REMOTE_ADDR']}{$forward})";
		if( $wgUser->isAnon() )
			$forward .= ' anon';
		$log = sprintf( "%s\t%04.3f\t%s\n",
		  gmdate( 'YmdHis' ), $elapsed,
		  urldecode( $_SERVER['REQUEST_URI'] . $forward ) );
		if ( '' != $wgDebugLogFile && ( $wgRequest->getVal('action') != 'raw' || $wgDebugRawPage ) ) {
			error_log( $log . $prof, 3, $wgDebugLogFile );
		}
	}
}

/**
 * Check if the wiki read-only lock file is present. This can be used to lock
 * off editing functions, but doesn't guarantee that the database will not be
 * modified.
 * @return bool
 */
function wfReadOnly() {
	global $wgReadOnlyFile, $wgReadOnly;

	if ( $wgReadOnly ) {
		return true;
	}
	if ( '' == $wgReadOnlyFile ) {
		return false;
	}

	// Set $wgReadOnly and unset $wgReadOnlyFile, for faster access next time
	if ( is_file( $wgReadOnlyFile ) ) {
		$wgReadOnly = file_get_contents( $wgReadOnlyFile );
	} else {
		$wgReadOnly = false;
	}
	$wgReadOnlyFile = '';
	return $wgReadOnly;
}


/**
 * Get a message from anywhere, for the current user language.
 *
 * Use wfMsgForContent() instead if the message should NOT 
 * change depending on the user preferences.
 *
 * Note that the message may contain HTML, and is therefore
 * not safe for insertion anywhere. Some functions such as
 * addWikiText will do the escaping for you. Use wfMsgHtml()
 * if you need an escaped message.
 *
 * @param string lookup key for the message, usually 
 *    defined in languages/Language.php
 */
function wfMsg( $key ) {
	$args = func_get_args();
	array_shift( $args );
	return wfMsgReal( $key, $args, true );
}

/**
 * Get a message from anywhere, for the current global language
 * set with $wgLanguageCode.
 * 
 * Use this if the message should NOT change  dependent on the 
 * language set in the user's preferences. This is the case for 
 * most text written into logs, as well as link targets (such as 
 * the name of the copyright policy page). Link titles, on the 
 * other hand, should be shown in the UI language.
 *
 * Note that MediaWiki allows users to change the user interface 
 * language in their preferences, but a single installation 
 * typically only contains content in one language.
 * 
 * Be wary of this distinction: If you use wfMsg() where you should 
 * use wfMsgForContent(), a user of the software may have to 
 * customize over 70 messages in order to, e.g., fix a link in every
 * possible language.
 *
 * @param string lookup key for the message, usually 
 *    defined in languages/Language.php
 */
function wfMsgForContent( $key ) {
	global $wgForceUIMsgAsContentMsg;
	$args = func_get_args();
	array_shift( $args );
	$forcontent = true;
	if( is_array( $wgForceUIMsgAsContentMsg ) &&
		in_array( $key, $wgForceUIMsgAsContentMsg ) )
		$forcontent = false;
	return wfMsgReal( $key, $args, true, $forcontent );
}

/**
 * Get a message from the language file, for the UI elements
 */
function wfMsgNoDB( $key ) {
	$args = func_get_args();
	array_shift( $args );
	return wfMsgReal( $key, $args, false );
}

/**
 * Get a message from the language file, for the content
 */
function wfMsgNoDBForContent( $key ) {
	global $wgForceUIMsgAsContentMsg;
	$args = func_get_args();
	array_shift( $args );
	$forcontent = true;
	if( is_array( $wgForceUIMsgAsContentMsg ) &&
		in_array( $key,	$wgForceUIMsgAsContentMsg ) )
		$forcontent = false;
	return wfMsgReal( $key, $args, false, $forcontent );
}


/**
 * Really get a message
 */
function wfMsgReal( $key, $args, $useDB, $forContent=false ) {
	$fname = 'wfMsgReal';
	wfProfileIn( $fname );

	$message = wfMsgGetKey( $key, $useDB, $forContent );
	$message = wfMsgReplaceArgs( $message, $args );
	wfProfileOut( $fname );
	return $message;
}

/**
 * Fetch a message string value, but don't replace any keys yet.
 * @param string $key
 * @param bool $useDB
 * @param bool $forContent
 * @return string
 * @access private
 */
function wfMsgGetKey( $key, $useDB, $forContent = false ) {
	global $wgParser, $wgMsgParserOptions;
	global $wgContLang, $wgLanguageCode;
	global $wgMessageCache, $wgLang;

	if( is_object( $wgMessageCache ) ) {
		$message = $wgMessageCache->get( $key, $useDB, $forContent );
	} else {
		if( $forContent ) {
			$lang = &$wgContLang;
		} else {
			$lang = &$wgLang;
		}

		wfSuppressWarnings();

		if( is_object( $lang ) ) {
			$message = $lang->getMessage( $key );
		} else {
			$message = false;
		}
		wfRestoreWarnings();
		if($message === false)
			$message = Language::getMessage($key);
		if(strstr($message, '{{' ) !== false) {
			$message = $wgParser->transformMsg($message, $wgMsgParserOptions);
		}
	}
	return $message;
}

/**
 * Replace message parameter keys on the given formatted output.
 *
 * @param string $message
 * @param array $args
 * @return string
 * @access private
 */
function wfMsgReplaceArgs( $message, $args ) {
	# Fix windows line-endings
	# Some messages are split with explode("\n", $msg)
	$message = str_replace( "\r", '', $message );

	# Replace arguments
	if( count( $args ) ) {
		foreach( $args as $n => $param ) {
			$replacementKeys['$' . ($n + 1)] = $param;
		}
		$message = strtr( $message, $replacementKeys );
	}
	return $message;
}

/**
 * Return an HTML-escaped version of a message.
 * Parameter replacements, if any, are done *after* the HTML-escaping,
 * so parameters may contain HTML (eg links or form controls). Be sure
 * to pre-escape them if you really do want plaintext, or just wrap
 * the whole thing in htmlspecialchars().
 *
 * @param string $key
 * @param string ... parameters
 * @return string
 */
function wfMsgHtml( $key ) {
	$args = func_get_args();
	array_shift( $args );
	return wfMsgReplaceArgs( htmlspecialchars( wfMsgGetKey( $key, true ) ), $args );
}

/**
 * Return an HTML version of message
 * Parameter replacements, if any, are done *after* parsing the wiki-text message,
 * so parameters may contain HTML (eg links or form controls). Be sure
 * to pre-escape them if you really do want plaintext, or just wrap
 * the whole thing in htmlspecialchars().
 *
 * @param string $key
 * @param string ... parameters
 * @return string
 */
function wfMsgWikiHtml( $key ) {
	global $wgOut;
	$args = func_get_args();
	array_shift( $args );
	return wfMsgReplaceArgs( $wgOut->parse( wfMsgGetKey( $key, true ), /* can't be set to false */ true ), $args );
}

/**
 * Just like exit() but makes a note of it.
 * Commits open transactions except if the error parameter is set
 */
function wfAbruptExit( $error = false ){
	global $wgLoadBalancer;
	static $called = false;
	if ( $called ){
		exit();
	}
	$called = true;

	if( function_exists( 'debug_backtrace' ) ){ // PHP >= 4.3
		$bt = debug_backtrace();
		for($i = 0; $i < count($bt) ; $i++){
			$file = isset($bt[$i]['file']) ? $bt[$i]['file'] : "unknown";
			$line = isset($bt[$i]['line']) ? $bt[$i]['line'] : "unknown";
			wfDebug("WARNING: Abrupt exit in $file at line $line\n");
		}
	} else {
		wfDebug('WARNING: Abrupt exit\n');
	}
	if ( !$error ) {
		$wgLoadBalancer->closeAll();
	}
	exit();
}

/**
 * @todo document
 */
function wfErrorExit() {
	wfAbruptExit( true );
}

/**
 * Die with a backtrace
 * This is meant as a debugging aid to track down where bad data comes from.
 * Shouldn't be used in production code except maybe in "shouldn't happen" areas.
 *
 * @param string $msg Message shown when dieing.
 */
function wfDebugDieBacktrace( $msg = '' ) {
	global $wgCommandLineMode;

	$backtrace = wfBacktrace();
	if ( $backtrace !== false ) {
		if ( $wgCommandLineMode ) {
			$msg .= "\nBacktrace:\n$backtrace";
		} else {
			$msg .= "\n<p>Backtrace:</p>\n$backtrace";
		}
	}
	echo $msg;
	echo wfReportTime()."\n";
	die( -1 );
}

	/**
	 * Returns a HTML comment with the elapsed time since request.
	 * This method has no side effects.
	 * @return string
	 */
	function wfReportTime() {
		global $wgRequestTime;

		$now = wfTime();
		list( $usec, $sec ) = explode( ' ', $wgRequestTime );
		$start = (float)$sec + (float)$usec;
		$elapsed = $now - $start;

		# Use real server name if available, so we know which machine
		# in a server farm generated the current page.
		if ( function_exists( 'posix_uname' ) ) {
			$uname = @posix_uname();
		} else {
			$uname = false;
		}
		if( is_array( $uname ) && isset( $uname['nodename'] ) ) {
			$hostname = $uname['nodename'];
		} else {
			# This may be a virtual server.
			$hostname = $_SERVER['SERVER_NAME'];
		}
		$com = sprintf( "<!-- Served by %s in %01.2f secs. -->",
		  $hostname, $elapsed );
		return $com;
	}

function wfBacktrace() {
	global $wgCommandLineMode;
	if ( !function_exists( 'debug_backtrace' ) ) {
		return false;
	}

	if ( $wgCommandLineMode ) {
		$msg = '';
	} else {
		$msg = "<ul>\n";
	}
	$backtrace = debug_backtrace();
	foreach( $backtrace as $call ) {
		if( isset( $call['file'] ) ) {
			$f = explode( DIRECTORY_SEPARATOR, $call['file'] );
			$file = $f[count($f)-1];
		} else {
			$file = '-';
		}
		if( isset( $call['line'] ) ) {
			$line = $call['line'];
		} else {
			$line = '-';
		}
		if ( $wgCommandLineMode ) {
			$msg .= "$file line $line calls ";
		} else {
			$msg .= '<li>' . $file . ' line ' . $line . ' calls ';
		}
		if( !empty( $call['class'] ) ) $msg .= $call['class'] . '::';
		$msg .= $call['function'] . '()';

		if ( $wgCommandLineMode ) {
			$msg .= "\n";
		} else {
			$msg .= "</li>\n";
		}
	}
	if ( $wgCommandLineMode ) {
		$msg .= "\n";
	} else {
		$msg .= "</ul>\n";
	}

	return $msg;
}


/* Some generic result counters, pulled out of SearchEngine */


/**
 * @todo document
 */
function wfShowingResults( $offset, $limit ) {
	global $wgLang;
	return wfMsg( 'showingresults', $wgLang->formatNum( $limit ), $wgLang->formatNum( $offset+1 ) );
}

/**
 * @todo document
 */
function wfShowingResultsNum( $offset, $limit, $num ) {
	global $wgLang;
	return wfMsg( 'showingresultsnum', $wgLang->formatNum( $limit ), $wgLang->formatNum( $offset+1 ), $wgLang->formatNum( $num ) );
}

/**
 * @todo document
 */
function wfViewPrevNext( $offset, $limit, $link, $query = '', $atend = false ) {
	global $wgUser, $wgLang;
	$fmtLimit = $wgLang->formatNum( $limit );
	$prev = wfMsg( 'prevn', $fmtLimit );
	$next = wfMsg( 'nextn', $fmtLimit );

	if( is_object( $link ) ) {
		$title =& $link;
	} else {
		$title = Title::newFromText( $link );
		if( is_null( $title ) ) {
			return false;
		}
	}

	$sk = $wgUser->getSkin();
	if ( 0 != $offset ) {
		$po = $offset - $limit;
		if ( $po < 0 ) { $po = 0; }
		$q = "limit={$limit}&offset={$po}";
		if ( '' != $query ) { $q .= '&'.$query; }
		$plink = '<a href="' . $title->escapeLocalUrl( $q ) . "\">{$prev}</a>";
	} else { $plink = $prev; }

	$no = $offset + $limit;
	$q = 'limit='.$limit.'&offset='.$no;
	if ( '' != $query ) { $q .= '&'.$query; }

	if ( $atend ) {
		$nlink = $next;
	} else {
		$nlink = '<a href="' . $title->escapeLocalUrl( $q ) . "\">{$next}</a>";
	}
	$nums = wfNumLink( $offset, 20, $title, $query ) . ' | ' .
	  wfNumLink( $offset, 50, $title, $query ) . ' | ' .
	  wfNumLink( $offset, 100, $title, $query ) . ' | ' .
	  wfNumLink( $offset, 250, $title, $query ) . ' | ' .
	  wfNumLink( $offset, 500, $title, $query );

	return wfMsg( 'viewprevnext', $plink, $nlink, $nums );
}

/**
 * @todo document
 */
function wfNumLink( $offset, $limit, &$title, $query = '' ) {
	global $wgUser, $wgLang;
	if ( '' == $query ) { $q = ''; }
	else { $q = $query.'&'; }
	$q .= 'limit='.$limit.'&offset='.$offset;

	$fmtLimit = $wgLang->formatNum( $limit );
	$s = '<a href="' . $title->escapeLocalUrl( $q ) . "\">{$fmtLimit}</a>";
	return $s;
}

/**
 * @todo document
 * @todo FIXME: we may want to blacklist some broken browsers
 *
 * @return bool Whereas client accept gzip compression
 */
function wfClientAcceptsGzip() {
	global $wgUseGzip;
	if( $wgUseGzip ) {
		# FIXME: we may want to blacklist some broken browsers
		if( preg_match(
			'/\bgzip(?:;(q)=([0-9]+(?:\.[0-9]+)))?\b/',
			$_SERVER['HTTP_ACCEPT_ENCODING'],
			$m ) ) {
			if( isset( $m[2] ) && ( $m[1] == 'q' ) && ( $m[2] == 0 ) ) return false;
			wfDebug( " accepts gzip\n" );
			return true;
		}
	}
	return false;
}

/**
 * Yay, more global functions!
 */
function wfCheckLimits( $deflimit = 50, $optionname = 'rclimit' ) {
	global $wgRequest;
	return $wgRequest->getLimitOffset( $deflimit, $optionname );
}

/**
 * Escapes the given text so that it may be output using addWikiText()
 * without any linking, formatting, etc. making its way through. This
 * is achieved by substituting certain characters with HTML entities.
 * As required by the callers, <nowiki> is not used. It currently does
 * not filter out characters which have special meaning only at the
 * start of a line, such as "*".
 *
 * @param string $text Text to be escaped
 */
function wfEscapeWikiText( $text ) {
	$text = str_replace(
		array( '[',		'|',	  '\'',	   'ISBN '	  , '://'	  , "\n=", '{{' ),
		array( '&#91;', '&#124;', '&#39;', 'ISBN&#32;', '&#58;//' , "\n&#61;", '&#123;&#123;' ),
		htmlspecialchars($text) );
	return $text;
}

/**
 * @todo document
 */
function wfQuotedPrintable( $string, $charset = '' ) {
	# Probably incomplete; see RFC 2045
	if( empty( $charset ) ) {
		global $wgInputEncoding;
		$charset = $wgInputEncoding;
	}
	$charset = strtoupper( $charset );
	$charset = str_replace( 'ISO-8859', 'ISO8859', $charset ); // ?

	$illegal = '\x00-\x08\x0b\x0c\x0e-\x1f\x7f-\xff=';
	$replace = $illegal . '\t ?_';
	if( !preg_match( "/[$illegal]/", $string ) ) return $string;
	$out = "=?$charset?Q?";
	$out .= preg_replace( "/([$replace])/e", 'sprintf("=%02X",ord("$1"))', $string );
	$out .= '?=';
	return $out;
}

/**
 * Returns an escaped string suitable for inclusion in a string literal
 * for JavaScript source code.
 * Illegal control characters are assumed not to be present.
 *
 * @param string $string
 * @return string
 */
function wfEscapeJsString( $string ) {
	// See ECMA 262 section 7.8.4 for string literal format
	$pairs = array(
		"\\" => "\\\\",
		"\"" => "\\\"",
		'\'' => '\\\'',
		"\n" => "\\n",
		"\r" => "\\r",

		# To avoid closing the element or CDATA section
		"<" => "\\x3c",
		">" => "\\x3e",
	);
	return strtr( $string, $pairs );
}

/**
 * @todo document
 * @return float
 */
function wfTime() {
	$st = explode( ' ', microtime() );
	return (float)$st[0] + (float)$st[1];
}

/**
 * Changes the first character to an HTML entity
 */
function wfHtmlEscapeFirst( $text ) {
	$ord = ord($text);
	$newText = substr($text, 1);
	return "&#$ord;$newText";
}

/**
 * Sets dest to source and returns the original value of dest
 * If source is NULL, it just returns the value, it doesn't set the variable
 */
function wfSetVar( &$dest, $source ) {
	$temp = $dest;
	if ( !is_null( $source ) ) {
		$dest = $source;
	}
	return $temp;
}

/**
 * As for wfSetVar except setting a bit
 */
function wfSetBit( &$dest, $bit, $state = true ) {
	$temp = (bool)($dest & $bit );
	if ( !is_null( $state ) ) {
		if ( $state ) {
			$dest |= $bit;
		} else {
			$dest &= ~$bit;
		}
	}
	return $temp;
}

/**
 * This function takes two arrays as input, and returns a CGI-style string, e.g.
 * "days=7&limit=100". Options in the first array override options in the second.
 * Options set to "" will not be output.
 */
function wfArrayToCGI( $array1, $array2 = NULL )
{
	if ( !is_null( $array2 ) ) {
		$array1 = $array1 + $array2;
	}

	$cgi = '';
	foreach ( $array1 as $key => $value ) {
		if ( '' !== $value ) {
			if ( '' != $cgi ) {
				$cgi .= '&';
			}
			$cgi .= urlencode( $key ) . '=' . urlencode( $value );
		}
	}
	return $cgi;
}

/**
 * This is obsolete, use SquidUpdate::purge()
 * @deprecated
 */
function wfPurgeSquidServers ($urlArr) {
	SquidUpdate::purge( $urlArr );
}

/**
 * Windows-compatible version of escapeshellarg()
 * Windows doesn't recognise single-quotes in the shell, but the escapeshellarg()
 * function puts single quotes in regardless of OS
 */
function wfEscapeShellArg( ) {
	$args = func_get_args();
	$first = true;
	$retVal = '';
	foreach ( $args as $arg ) {
		if ( !$first ) {
			$retVal .= ' ';
		} else {
			$first = false;
		}

		if ( wfIsWindows() ) {
			$retVal .= '"' . str_replace( '"','\"', $arg ) . '"';
		} else {
			$retVal .= escapeshellarg( $arg );
		}
	}
	return $retVal;
}

/**
 * wfMerge attempts to merge differences between three texts.
 * Returns true for a clean merge and false for failure or a conflict.
 */
function wfMerge( $old, $mine, $yours, &$result ){
	global $wgDiff3;

	# This check may also protect against code injection in
	# case of broken installations.
	if(! file_exists( $wgDiff3 ) ){
		wfDebug( "diff3 not found\n" );
		return false;
	}

	# Make temporary files
	$td = wfTempDir();
	$oldtextFile = fopen( $oldtextName = tempnam( $td, 'merge-old-' ), 'w' );
	$mytextFile = fopen( $mytextName = tempnam( $td, 'merge-mine-' ), 'w' );
	$yourtextFile = fopen( $yourtextName = tempnam( $td, 'merge-your-' ), 'w' );

	fwrite( $oldtextFile, $old ); fclose( $oldtextFile );
	fwrite( $mytextFile, $mine ); fclose( $mytextFile );
	fwrite( $yourtextFile, $yours ); fclose( $yourtextFile );

	# Check for a conflict
	$cmd = $wgDiff3 . ' -a --overlap-only ' .
	  wfEscapeShellArg( $mytextName ) . ' ' .
	  wfEscapeShellArg( $oldtextName ) . ' ' .
	  wfEscapeShellArg( $yourtextName );
	$handle = popen( $cmd, 'r' );

	if( fgets( $handle, 1024 ) ){
		$conflict = true;
	} else {
		$conflict = false;
	}
	pclose( $handle );

	# Merge differences
	$cmd = $wgDiff3 . ' -a -e --merge ' .
	  wfEscapeShellArg( $mytextName, $oldtextName, $yourtextName );
	$handle = popen( $cmd, 'r' );
	$result = '';
	do {
		$data = fread( $handle, 8192 );
		if ( strlen( $data ) == 0 ) {
			break;
		}
		$result .= $data;
	} while ( true );
	pclose( $handle );
	unlink( $mytextName ); unlink( $oldtextName ); unlink( $yourtextName );

	if ( $result === '' && $old !== '' && $conflict == false ) {
		wfDebug( "Unexpected null result from diff3. Command: $cmd\n" );
		$conflict = true;
	}
	return ! $conflict;
}

/**
 * @todo document
 */
function wfVarDump( $var ) {
	global $wgOut;
	$s = str_replace("\n","<br />\n", var_export( $var, true ) . "\n");
	if ( headers_sent() || !@is_object( $wgOut ) ) {
		print $s;
	} else {
		$wgOut->addHTML( $s );
	}
}

/**
 * Provide a simple HTTP error.
 */
function wfHttpError( $code, $label, $desc ) {
	global $wgOut;
	$wgOut->disable();
	header( "HTTP/1.0 $code $label" );
	header( "Status: $code $label" );
	$wgOut->sendCacheControl();

	header( 'Content-type: text/html' );
	print "<html><head><title>" .
		htmlspecialchars( $label ) .
		"</title></head><body><h1>" .
		htmlspecialchars( $label ) .
		"</h1><p>" .
		htmlspecialchars( $desc ) .
		"</p></body></html>\n";
}

/**
 * Converts an Accept-* header into an array mapping string values to quality
 * factors
 */
function wfAcceptToPrefs( $accept, $def = '*/*' ) {
	# No arg means accept anything (per HTTP spec)
	if( !$accept ) {
		return array( $def => 1 );
	}

	$prefs = array();

	$parts = explode( ',', $accept );

	foreach( $parts as $part ) {
		# FIXME: doesn't deal with params like 'text/html; level=1'
		@list( $value, $qpart ) = explode( ';', $part );
		if( !isset( $qpart ) ) {
			$prefs[$value] = 1;
		} elseif( preg_match( '/q\s*=\s*(\d*\.\d+)/', $qpart, $match ) ) {
			$prefs[$value] = $match[1];
		}
	}

	return $prefs;
}

/**
 * Checks if a given MIME type matches any of the keys in the given
 * array. Basic wildcards are accepted in the array keys.
 *
 * Returns the matching MIME type (or wildcard) if a match, otherwise
 * NULL if no match.
 *
 * @param string $type
 * @param array $avail
 * @return string
 * @access private
 */
function mimeTypeMatch( $type, $avail ) {
	if( array_key_exists($type, $avail) ) {
		return $type;
	} else {
		$parts = explode( '/', $type );
		if( array_key_exists( $parts[0] . '/*', $avail ) ) {
			return $parts[0] . '/*';
		} elseif( array_key_exists( '*/*', $avail ) ) {
			return '*/*';
		} else {
			return NULL;
		}
	}
}

/**
 * Returns the 'best' match between a client's requested internet media types
 * and the server's list of available types. Each list should be an associative
 * array of type to preference (preference is a float between 0.0 and 1.0).
 * Wildcards in the types are acceptable.
 *
 * @param array $cprefs Client's acceptable type list
 * @param array $sprefs Server's offered types
 * @return string
 *
 * @todo FIXME: doesn't handle params like 'text/plain; charset=UTF-8'
 * XXX: generalize to negotiate other stuff
 */
function wfNegotiateType( $cprefs, $sprefs ) {
	$combine = array();

	foreach( array_keys($sprefs) as $type ) {
		$parts = explode( '/', $type );
		if( $parts[1] != '*' ) {
			$ckey = mimeTypeMatch( $type, $cprefs );
			if( $ckey ) {
				$combine[$type] = $sprefs[$type] * $cprefs[$ckey];
			}
		}
	}

	foreach( array_keys( $cprefs ) as $type ) {
		$parts = explode( '/', $type );
		if( $parts[1] != '*' && !array_key_exists( $type, $sprefs ) ) {
			$skey = mimeTypeMatch( $type, $sprefs );
			if( $skey ) {
				$combine[$type] = $sprefs[$skey] * $cprefs[$type];
			}
		}
	}

	$bestq = 0;
	$besttype = NULL;

	foreach( array_keys( $combine ) as $type ) {
		if( $combine[$type] > $bestq ) {
			$besttype = $type;
			$bestq = $combine[$type];
		}
	}

	return $besttype;
}

/**
 * Array lookup
 * Returns an array where the values in the first array are replaced by the
 * values in the second array with the corresponding keys
 *
 * @return array
 */
function wfArrayLookup( $a, $b ) {
	return array_flip( array_intersect( array_flip( $a ), array_keys( $b ) ) );
}

/**
 * Convenience function; returns MediaWiki timestamp for the present time.
 * @return string
 */
function wfTimestampNow() {
	# return NOW
	return wfTimestamp( TS_MW, time() );
}

/**
 * Reference-counted warning suppression
 */
function wfSuppressWarnings( $end = false ) {
	static $suppressCount = 0;
	static $originalLevel = false;

	if ( $end ) {
		if ( $suppressCount ) {
			--$suppressCount;
			if ( !$suppressCount ) {
				error_reporting( $originalLevel );
			}
		}
	} else {
		if ( !$suppressCount ) {
			$originalLevel = error_reporting( E_ALL & ~( E_WARNING | E_NOTICE ) );
		}
		++$suppressCount;
	}
}

/**
 * Restore error level to previous value
 */
function wfRestoreWarnings() {
	wfSuppressWarnings( true );
}

# Autodetect, convert and provide timestamps of various types

/**
 * Unix time - the number of seconds since 1970-01-01 00:00:00 UTC
 */
define('TS_UNIX', 0);

/**
 * MediaWiki concatenated string timestamp (YYYYMMDDHHMMSS)
 */
define('TS_MW', 1);

/**
 * MySQL DATETIME (YYYY-MM-DD HH:MM:SS)
 */
define('TS_DB', 2);

/**
 * RFC 2822 format, for E-mail and HTTP headers
 */
define('TS_RFC2822', 3);

/**
 * ISO 8601 format with no timezone: 1986-02-09T20:00:00Z
 *
 * This is used by Special:Export 
 */
define('TS_ISO_8601', 4);

/**
 * An Exif timestamp (YYYY:MM:DD HH:MM:SS)
 *
 * @link http://exif.org/Exif2-2.PDF The Exif 2.2 spec, see page 28 for the
 *       DateTime tag and page 36 for the DateTimeOriginal and
 *       DateTimeDigitized tags.
 */
define('TS_EXIF', 5);

/**
 * Oracle format time.
 */
define('TS_ORACLE', 6);

/**
 * @param mixed $outputtype A timestamp in one of the supported formats, the
 *                          function will autodetect which format is supplied
                            and act accordingly.
 * @return string Time in the format specified in $outputtype
 */
function wfTimestamp($outputtype=TS_UNIX,$ts=0) {
	$uts = 0;
	if ($ts==0) {
		$uts=time();
	} elseif (preg_match("/^(\d{4})\-(\d\d)\-(\d\d) (\d\d):(\d\d):(\d\d)$/",$ts,$da)) {
		# TS_DB
		$uts=gmmktime((int)$da[4],(int)$da[5],(int)$da[6],
			    (int)$da[2],(int)$da[3],(int)$da[1]);
	} elseif (preg_match("/^(\d{4}):(\d\d):(\d\d) (\d\d):(\d\d):(\d\d)$/",$ts,$da)) {
		# TS_EXIF
		$uts=gmmktime((int)$da[4],(int)$da[5],(int)$da[6],
			(int)$da[2],(int)$da[3],(int)$da[1]);
	} elseif (preg_match("/^(\d{4})(\d\d)(\d\d)(\d\d)(\d\d)(\d\d)$/",$ts,$da)) {
		# TS_MW
		$uts=gmmktime((int)$da[4],(int)$da[5],(int)$da[6],
			    (int)$da[2],(int)$da[3],(int)$da[1]);
	} elseif (preg_match("/^(\d{1,13})$/",$ts,$datearray)) {
		# TS_UNIX
		$uts=$ts;
	} elseif (preg_match('/^(\d{1,2})-(...)-(\d\d(\d\d)?) (\d\d)\.(\d\d)\.(\d\d)/', $ts, $da)) {
		# TS_ORACLE
		$uts = strtotime(preg_replace('/(\d\d)\.(\d\d)\.(\d\d)(\.(\d+))?/', "$1:$2:$3",
				str_replace("+00:00", "UTC", $ts)));
	} elseif (preg_match('/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})Z$/', $ts, $da)) {
		# TS_ISO_8601
		$uts=gmmktime((int)$da[4],(int)$da[5],(int)$da[6],
			(int)$da[2],(int)$da[3],(int)$da[1]);
	} else {
		# Bogus value; fall back to the epoch...
		wfDebug("wfTimestamp() fed bogus time value: $outputtype; $ts\n");
		$uts = 0;
	}


 	switch($outputtype) {
		case TS_UNIX:
			return $uts;
		case TS_MW:
			return gmdate( 'YmdHis', $uts );
		case TS_DB:
			return gmdate( 'Y-m-d H:i:s', $uts );
		case TS_ISO_8601:
			return gmdate( 'Y-m-d\TH:i:s\Z', $uts );
		// This shouldn't ever be used, but is included for completeness
		case TS_EXIF:
			return gmdate(  'Y:m:d H:i:s', $uts );
		case TS_RFC2822:
			return gmdate( 'D, d M Y H:i:s', $uts ) . ' GMT';
		case TS_ORACLE:
			return gmdate( 'd-M-y h.i.s A', $uts) . ' +00:00';
		default:
			wfDebugDieBacktrace( 'wfTimestamp() called with illegal output type.');
	}
}

/**
 * Return a formatted timestamp, or null if input is null.
 * For dealing with nullable timestamp columns in the database.
 * @param int $outputtype
 * @param string $ts
 * @return string
 */
function wfTimestampOrNull( $outputtype = TS_UNIX, $ts = null ) {
	if( is_null( $ts ) ) {
		return null;
	} else {
		return wfTimestamp( $outputtype, $ts );
	}
}

/**
 * Check where as the operating system is Windows
 *
 * @return bool True if it's windows, False otherwise.
 */
function wfIsWindows() {
	if (substr(php_uname(), 0, 7) == 'Windows') {
		return true;
	} else {
		return false;
	}
}

/**
 * Swap two variables
 */
function swap( &$x, &$y ) {
	$z = $x;
	$x = $y;
	$y = $z;
}

function wfGetSiteNotice() {
	global $wgSiteNotice, $wgTitle, $wgOut;
	$fname = 'wfGetSiteNotice';
	wfProfileIn( $fname );

	$notice = wfMsg( 'sitenotice' );
	if( $notice == '&lt;sitenotice&gt;' || $notice == '-' ) {
		$notice = '';
	}
	if( $notice == '' ) {
		# We may also need to override a message with eg downtime info
		# FIXME: make this work!
		$notice = $wgSiteNotice;
	}
	if($notice != '-' && $notice != '') {
		$specialparser = new Parser();
		$parserOutput = $specialparser->parse( $notice, $wgTitle, $wgOut->mParserOptions, false );
		$notice = $parserOutput->getText();
	}
	wfProfileOut( $fname );
	return $notice;
}

/**
 * Format an XML element with given attributes and, optionally, text content.
 * Element and attribute names are assumed to be ready for literal inclusion.
 * Strings are assumed to not contain XML-illegal characters; special
 * characters (<, >, &) are escaped but illegals are not touched.
 *
 * @param string $element
 * @param array $attribs Name=>value pairs. Values will be escaped.
 * @param string $contents NULL to make an open tag only; '' for a contentless closed tag (default)
 * @return string
 */
function wfElement( $element, $attribs = null, $contents = '') {
	$out = '<' . $element;
	if( !is_null( $attribs ) ) {
		foreach( $attribs as $name => $val ) {
			$out .= ' ' . $name . '="' . htmlspecialchars( $val ) . '"';
		}
	}
	if( is_null( $contents ) ) {
		$out .= '>';
	} else {
		if( $contents == '' ) {
			$out .= ' />';
		} else {
			$out .= '>';
			$out .= htmlspecialchars( $contents );
			$out .= "</$element>";
		}
	}
	return $out;
}

/**
 * Format an XML element as with wfElement(), but run text through the
 * UtfNormal::cleanUp() validator first to ensure that no invalid UTF-8
 * is passed.
 *
 * @param string $element
 * @param array $attribs Name=>value pairs. Values will be escaped.
 * @param string $contents NULL to make an open tag only; '' for a contentless closed tag (default)
 * @return string
 */
function wfElementClean( $element, $attribs = array(), $contents = '') {
	if( $attribs ) {
		$attribs = array_map( array( 'UtfNormal', 'cleanUp' ), $attribs );
	}
	if( $contents ) {
		$contents = UtfNormal::cleanUp( $contents );
	}
	return wfElement( $element, $attribs, $contents );
}

// Shortcuts
function wfOpenElement( $element ) { return "<$element>"; }
function wfCloseElement( $element ) { return "</$element>"; }

/**
 * Create a namespace selector
 *
 * @param mixed $selected The namespace which should be selected, default ''
 * @param string $allnamespaces Value of a special item denoting all namespaces. Null to not include (default)
 * @return Html string containing the namespace selector
 */
function &HTMLnamespaceselector($selected = '', $allnamespaces = null) {
	global $wgContLang;
	if( $selected !== '' ) {
		if( is_null( $selected ) ) {
			// No namespace selected; let exact match work without hitting Main
			$selected = '';
		} else {
			// Let input be numeric strings without breaking the empty match.
			$selected = intval( $selected );
		}
	}
	$s = "<select name='namespace' class='namespaceselector'>\n\t";
	$arr = $wgContLang->getFormattedNamespaces();
	if( !is_null($allnamespaces) ) {
		$arr = array($allnamespaces => wfMsgHtml('namespacesall')) + $arr;
	}
	foreach ($arr as $index => $name) {
		if ($index < NS_MAIN) continue;

		$name = $index !== 0 ? $name : wfMsgHtml('blanknamespace');

		if ($index === $selected) {
			$s .= wfElement("option",
					array("value" => $index, "selected" => "selected"),
					$name);
		} else {
			$s .= wfElement("option", array("value" => $index), $name);
		}
	}
	$s .= "\n</select>\n";
	return $s;
}

/** Global singleton instance of MimeMagic. This is initialized on demand,
* please always use the wfGetMimeMagic() function to get the instance.
*
* @private
*/
$wgMimeMagic= NULL;

/** Factory functions for the global MimeMagic object.
* This function always returns the same singleton instance of MimeMagic.
* That objects will be instantiated on the first call to this function.
* If needed, the MimeMagic.php file is automatically included by this function.
* @return MimeMagic the global MimeMagic objects.
*/
function &wfGetMimeMagic() {
	global $wgMimeMagic;

	if (!is_null($wgMimeMagic)) {
		return $wgMimeMagic;
	}

	if (!class_exists("MimeMagic")) {
		#include on demand
		require_once("MimeMagic.php");
	}

	$wgMimeMagic= new MimeMagic();

	return $wgMimeMagic;
}


/**
 * Tries to get the system directory for temporary files.
 * The TMPDIR, TMP, and TEMP environment variables are checked in sequence,
 * and if none are set /tmp is returned as the generic Unix default.
 *
 * NOTE: When possible, use the tempfile() function to create temporary
 * files to avoid race conditions on file creation, etc.
 *
 * @return string
 */
function wfTempDir() {
	foreach( array( 'TMPDIR', 'TMP', 'TEMP' ) as $var ) {
		$tmp = getenv( $var );
		if( $tmp && file_exists( $tmp ) && is_dir( $tmp ) && is_writable( $tmp ) ) {
			return $tmp;
		}
	}
	# Hope this is Unix of some kind!
	return '/tmp';
}

/**
 * Make directory, and make all parent directories if they don't exist
 */
function wfMkdirParents( $fullDir, $mode ) {
	$parts = explode( '/', $fullDir );
	$path = '';
	
	foreach ( $parts as $dir ) {
		$path .= $dir . '/';
		if ( !is_dir( $path ) ) {
			if ( !mkdir( $path, $mode ) ) {
				return false;
			}
		}
	}
	return true;
}

/**
 * Increment a statistics counter
 */
function wfIncrStats( $key ) {
	global $wgDBname, $wgMemc;
	$key = "$wgDBname:stats:$key";
	if ( is_null( $wgMemc->incr( $key ) ) ) {
		$wgMemc->add( $key, 1 );
	}
}

/**
 * @param mixed $nr The number to format
 * @param int $acc The number of digits after the decimal point, default 2
 * @param bool $round Whether or not to round the value, default true
 * @return float
 */
function wfPercent( $nr, $acc = 2, $round = true ) {
	$ret = sprintf( "%.${acc}f", $nr );
	return $round ? round( $ret, $acc ) . '%' : "$ret%";
}

/**
 * Encrypt a username/password.
 *
 * @param string $userid ID of the user
 * @param string $password Password of the user
 * @return string Hashed password
 */
function wfEncryptPassword( $userid, $password ) {
	global $wgPasswordSalt;
	$p = md5( $password);

	if($wgPasswordSalt)
		return md5( "{$userid}-{$p}" );
	else
		return $p;
}

/**
 * Appends to second array if $value differs from that in $default
 */
function wfAppendToArrayIfNotDefault( $key, $value, $default, &$changed ) {
	if ( is_null( $changed ) ) {
		wfDebugDieBacktrace('GlobalFunctions::wfAppendToArrayIfNotDefault got null');
	}
	if ( $default[$key] !== $value ) {
		$changed[$key] = $value;
	}
}

/**
 * Since wfMsg() and co suck, they don't return false if the message key they
 * looked up didn't exist but a XHTML string, this function checks for the
 * nonexistance of messages by looking at wfMsg() output
 *
 * @param $msg      The message key looked up
 * @param $wfMsgOut The output of wfMsg*()
 * @return bool
 */
function wfEmptyMsg( $msg, $wfMsgOut ) {
	return $wfMsgOut === "&lt;$msg&gt;";
}

/**
 * Find out whether or not a mixed variable exists in a string
 *
 * @param mixed  needle
 * @param string haystack
 * @return bool
 */
function in_string( $needle, $str ) {
	return strpos( $str, $needle ) !== false;
}

/**
 * Returns a regular expression of url protocols
 *
 * @return string
 */
function wfUrlProtocols() {
	global $wgUrlProtocols;

	$x = array();
	foreach ($wgUrlProtocols as $protocol)
		$x[] = preg_quote( $protocol, '/' );
	
	return implode( '|', $x );
}

?>
