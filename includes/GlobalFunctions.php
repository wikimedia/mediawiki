<?php

if ( !defined( 'MEDIAWIKI' ) ) {
	die( "This file is part of MediaWiki, it is not a valid entry point" );
}

/**
 * Global functions used everywhere
 */

require_once dirname(__FILE__) . '/LogPage.php';
require_once dirname(__FILE__) . '/normal/UtfNormalUtil.php';
require_once dirname(__FILE__) . '/XmlFunctions.php';

/**
 * Compatibility functions
 *
 * We more or less support PHP 5.0.x and up.
 * Re-implementations of newer functions or functions in non-standard
 * PHP extensions may be included here.
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

# UTF-8 substr function based on a PHP manual comment
if ( !function_exists( 'mb_substr' ) ) {
	function mb_substr( $str, $start ) {
		$ar = array();
		preg_match_all( '/./us', $str, $ar );

		if( func_num_args() >= 3 ) {
			$end = func_get_arg( 2 );
			return join( '', array_slice( $ar[0], $start, $end ) );
		} else {
			return join( '', array_slice( $ar[0], $start ) );
		}
	}
}

if ( !function_exists( 'mb_strlen' ) ) {
	/**
	 * Fallback implementation of mb_strlen, hardcoded to UTF-8.
	 * @param string $str
	 * @param string $enc optional encoding; ignored
	 * @return int
	 */
	function mb_strlen( $str, $enc="" ) {
		$counts = count_chars( $str );
		$total = 0;

		// Count ASCII bytes
		for( $i = 0; $i < 0x80; $i++ ) {
			$total += $counts[$i];
		}

		// Count multibyte sequence heads
		for( $i = 0xc0; $i < 0xff; $i++ ) {
			$total += $counts[$i];
		}
		return $total;
	}
}

if ( !function_exists( 'array_diff_key' ) ) {
	/**
	 * Exists in PHP 5.1.0+
	 * Not quite compatible, two-argument version only
	 * Null values will cause problems due to this use of isset()
	 */
	function array_diff_key( $left, $right ) {
		$result = $left;
		foreach ( $left as $key => $unused ) {
			if ( isset( $right[$key] ) ) {
				unset( $result[$key] );
			}
		}
		return $result;
	}
}

/**
 * Like array_diff( $a, $b ) except that it works with two-dimensional arrays.
 */
function wfArrayDiff2( $a, $b ) {
	return array_udiff( $a, $b, 'wfArrayDiff2_cmp' );
}
function wfArrayDiff2_cmp( $a, $b ) {
	if ( !is_array( $a ) ) {
		return strcmp( $a, $b );
	} elseif ( count( $a ) !== count( $b ) ) {
		return count( $a ) < count( $b ) ? -1 : 1;
	} else {
		reset( $a );
		reset( $b );
		while( ( list( $keyA, $valueA ) = each( $a ) ) && ( list( $keyB, $valueB ) = each( $b ) ) ) {
			$cmp = strcmp( $valueA, $valueB );
			if ( $cmp !== 0 ) {
				return $cmp;
			}
		}
		return 0;
	}
}

/**
 * Wrapper for clone(), for compatibility with PHP4-friendly extensions.
 * PHP 5 won't let you declare a 'clone' function, even conditionally,
 * so it has to be a wrapper with a different name.
 */
function wfClone( $object ) {
	return clone( $object );
}

/**
 * Seed Mersenne Twister
 * No-op for compatibility; only necessary in PHP < 4.2.0
 */
function wfSeedRandom() {
	/* No-op */
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
	$max = mt_getrandmax() + 1;
	$rand = number_format( (mt_rand() * $max + mt_rand())
		/ $max / $max, 12, '.', '' );
	return $rand;
}

/**
 * We want / and : to be included as literal characters in our title URLs.
 * %2F in the page titles seems to fatally break for some reason.
 *
 * @param $s String:
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
 * @param $text String
 * @param $logonly Bool: set true to avoid appearing in HTML when $wgDebugComments is set
 */
function wfDebug( $text, $logonly = false ) {
	global $wgOut, $wgDebugLogFile, $wgDebugComments, $wgProfileOnly, $wgDebugRawPage;
	static $recursion = 0;

	# Check for raw action using $_GET not $wgRequest, since the latter might not be initialised yet
	if ( isset( $_GET['action'] ) && $_GET['action'] == 'raw' && !$wgDebugRawPage ) {
		return;
	}

	if ( $wgDebugComments && !$logonly ) {
		if ( !isset( $wgOut ) ) {
			return;
		}
		if ( !StubObject::isRealObject( $wgOut ) ) {
			if ( $recursion ) {
				return;
			}
			$recursion++;
			$wgOut->_unstub();
			$recursion--;
		}
		$wgOut->debug( $text );
	}
	if ( '' != $wgDebugLogFile && !$wgProfileOnly ) {
		# Strip unprintables; they can switch terminal modes when binary data
		# gets dumped, which is pretty annoying.
		$text = preg_replace( '![\x00-\x08\x0b\x0c\x0e-\x1f]!', ' ', $text );
		wfErrorLog( $text, $wgDebugLogFile );
	}
}

/**
 * Send a line to a supplementary debug log file, if configured, or main debug log if not.
 * $wgDebugLogGroups[$logGroup] should be set to a filename to send to a separate log.
 *
 * @param $logGroup String
 * @param $text String
 * @param $public Bool: whether to log the event in the public log if no private
 *                     log file is specified, (default true)
 */
function wfDebugLog( $logGroup, $text, $public = true ) {
	global $wgDebugLogGroups;
	if( $text{strlen( $text ) - 1} != "\n" ) $text .= "\n";
	if( isset( $wgDebugLogGroups[$logGroup] ) ) {
		$time = wfTimestamp( TS_DB );
		$wiki = wfWikiID();
		wfErrorLog( "$time $wiki: $text", $wgDebugLogGroups[$logGroup] );
	} else if ( $public === true ) {
		wfDebug( $text, true );
	}
}

/**
 * Log for database errors
 * @param $text String: database error message.
 */
function wfLogDBError( $text ) {
	global $wgDBerrorLog, $wgDBname;
	if ( $wgDBerrorLog ) {
		$host = trim(`hostname`);
		$text = date('D M j G:i:s T Y') . "\t$host\t$wgDBname\t$text";
		wfErrorLog( $text, $wgDBerrorLog );
	}
}

/**
 * Log to a file without getting "file size exceeded" signals
 */
function wfErrorLog( $text, $file ) {
	wfSuppressWarnings();
	$exists = file_exists( $file );
	$size = $exists ? filesize( $file ) : false;
	if ( !$exists || ( $size !== false && $size + strlen( $text ) < 0x7fffffff ) ) {
		error_log( $text, 3, $file );
	}
	wfRestoreWarnings();
}

/**
 * @todo document
 */
function wfLogProfilingData() {
	global $wgRequestTime, $wgDebugLogFile, $wgDebugRawPage, $wgRequest;
	global $wgProfiling, $wgUser;
	if ( $wgProfiling ) {
		$now = wfTime();
		$elapsed = $now - $wgRequestTime;
		$prof = wfGetProfilingOutput( $wgRequestTime, $elapsed );
		$forward = '';
		if( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )
			$forward = ' forwarded for ' . $_SERVER['HTTP_X_FORWARDED_FOR'];
		if( !empty( $_SERVER['HTTP_CLIENT_IP'] ) )
			$forward .= ' client IP ' . $_SERVER['HTTP_CLIENT_IP'];
		if( !empty( $_SERVER['HTTP_FROM'] ) )
			$forward .= ' from ' . $_SERVER['HTTP_FROM'];
		if( $forward )
			$forward = "\t(proxied via {$_SERVER['REMOTE_ADDR']}{$forward})";
		// Don't unstub $wgUser at this late stage just for statistics purposes
		if( StubObject::isRealObject($wgUser) && $wgUser->isAnon() )
			$forward .= ' anon';
		$log = sprintf( "%s\t%04.3f\t%s\n",
		  gmdate( 'YmdHis' ), $elapsed,
		  urldecode( $wgRequest->getRequestURL() . $forward ) );
		if ( '' != $wgDebugLogFile && ( $wgRequest->getVal('action') != 'raw' || $wgDebugRawPage ) ) {
			wfErrorLog( $log . $prof, $wgDebugLogFile );
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

	if ( !is_null( $wgReadOnly ) ) {
		return (bool)$wgReadOnly;
	}
	if ( '' == $wgReadOnlyFile ) {
		return false;
	}
	// Set $wgReadOnly for faster access next time
	if ( is_file( $wgReadOnlyFile ) ) {
		$wgReadOnly = file_get_contents( $wgReadOnlyFile );
	} else {
		$wgReadOnly = false;
	}
	return (bool)$wgReadOnly;
}

function wfReadOnlyReason() {
	global $wgReadOnly;
	wfReadOnly();
	return $wgReadOnly;
}

/**
 * Get a message from anywhere, for the current user language.
 *
 * Use wfMsgForContent() instead if the message should NOT
 * change depending on the user preferences.
 *
 * @param $key String: lookup key for the message, usually
 *    defined in languages/Language.php
 * 
 * This function also takes extra optional parameters (not 
 * shown in the function definition), which can by used to 
 * insert variable text into the predefined message.
 */
function wfMsg( $key ) {
	$args = func_get_args();
	array_shift( $args );
	return wfMsgReal( $key, $args, true );
}

/**
 * Same as above except doesn't transform the message
 */
function wfMsgNoTrans( $key ) {
	$args = func_get_args();
	array_shift( $args );
	return wfMsgReal( $key, $args, true, false, false );
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
 * @param $key String: lookup key for the message, usually
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
 * Same as above except doesn't transform the message
 */
function wfMsgForContentNoTrans( $key ) {
	global $wgForceUIMsgAsContentMsg;
	$args = func_get_args();
	array_shift( $args );
	$forcontent = true;
	if( is_array( $wgForceUIMsgAsContentMsg ) &&
		in_array( $key, $wgForceUIMsgAsContentMsg ) )
		$forcontent = false;
	return wfMsgReal( $key, $args, true, $forcontent, false );
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
 * @param $key String: key to get.
 * @param $args
 * @param $useDB Boolean
 * @param $transform Boolean: Whether or not to transform the message.
 * @param $forContent Boolean
 * @return String: the requested message.
 */
function wfMsgReal( $key, $args, $useDB = true, $forContent=false, $transform = true ) {
	wfProfileIn( __METHOD__ );
	$message = wfMsgGetKey( $key, $useDB, $forContent, $transform );
	$message = wfMsgReplaceArgs( $message, $args );
	wfProfileOut( __METHOD__ );
	return $message;
}

/**
 * This function provides the message source for messages to be edited which are *not* stored in the database.
 * @param $key String:
 */
function wfMsgWeirdKey ( $key ) {
	$source = wfMsgGetKey( $key, false, true, false );
	if ( wfEmptyMsg( $key, $source ) )
		return "";
	else
		return $source;
}

/**
 * Fetch a message string value, but don't replace any keys yet.
 * @param string $key
 * @param bool $useDB
 * @param bool $forContent
 * @return string
 * @private
 */
function wfMsgGetKey( $key, $useDB, $forContent = false, $transform = true ) {
	global $wgParser, $wgContLang, $wgMessageCache, $wgLang;

	# If $wgMessageCache isn't initialised yet, try to return something sensible.
	if( is_object( $wgMessageCache ) ) {
		$message = $wgMessageCache->get( $key, $useDB, $forContent );
		if ( $transform ) {
			$message = $wgMessageCache->transform( $message );
		}
	} else {
		if( $forContent ) {
			$lang = &$wgContLang;
		} else {
			$lang = &$wgLang;
		}

		# MessageCache::get() does this already, Language::getMessage() doesn't
		# ISSUE: Should we try to handle "message/lang" here too?
		$key = str_replace( ' ' , '_' , $wgContLang->lcfirst( $key ) );

		if( is_object( $lang ) ) {
			$message = $lang->getMessage( $key );
		} else {
			$message = false;
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
 * @private
 */
function wfMsgReplaceArgs( $message, $args ) {
	# Fix windows line-endings
	# Some messages are split with explode("\n", $msg)
	$message = str_replace( "\r", '', $message );

	// Replace arguments
	if ( count( $args ) ) {
		if ( is_array( $args[0] ) ) {
			$args = array_values( $args[0] );
		}
		$replacementKeys = array();
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
 * Returns message in the requested format
 * @param string $key Key of the message
 * @param array $options Processing rules:
 *  <i>parse</i>: parses wikitext to html
 *  <i>parseinline</i>: parses wikitext to html and removes the surrounding p's added by parser or tidy
 *  <i>escape</i>: filters message through htmlspecialchars
 *  <i>escapenoentities</i>: same, but allows entity references like &nbsp; through
 *  <i>replaceafter</i>: parameters are substituted after parsing or escaping
 *  <i>parsemag</i>: transform the message using magic phrases
 *  <i>content</i>: fetch message for content language instead of interface
 * Behavior for conflicting options (e.g., parse+parseinline) is undefined.
 */
function wfMsgExt( $key, $options ) {
	global $wgOut, $wgParser;

	$args = func_get_args();
	array_shift( $args );
	array_shift( $args );

	if( !is_array($options) ) {
		$options = array($options);
	}

	$forContent = false;
	if( in_array('content', $options) ) {
		$forContent = true;
	}

	$string = wfMsgGetKey( $key, /*DB*/true, $forContent, /*Transform*/false );

	if( !in_array('replaceafter', $options) ) {
		$string = wfMsgReplaceArgs( $string, $args );
	}

	if( in_array('parse', $options) ) {
		$string = $wgOut->parse( $string, true, !$forContent );
	} elseif ( in_array('parseinline', $options) ) {
		$string = $wgOut->parse( $string, true, !$forContent );
		$m = array();
		if( preg_match( '/^<p>(.*)\n?<\/p>\n?$/sU', $string, $m ) ) {
			$string = $m[1];
		}
	} elseif ( in_array('parsemag', $options) ) {
		global $wgMessageCache;
		if ( isset( $wgMessageCache ) ) {
			$string = $wgMessageCache->transform( $string, !$forContent );
		}
	}

	if ( in_array('escape', $options) ) {
		$string = htmlspecialchars ( $string );
	} elseif ( in_array( 'escapenoentities', $options ) ) {
		$string = htmlspecialchars( $string );
		$string = str_replace( '&amp;', '&', $string );
		$string = Sanitizer::normalizeCharReferences( $string );
	}

	if( in_array('replaceafter', $options) ) {
		$string = wfMsgReplaceArgs( $string, $args );
	}

	return $string;
}


/**
 * Just like exit() but makes a note of it.
 * Commits open transactions except if the error parameter is set
 *
 * @deprecated Please return control to the caller or throw an exception
 */
function wfAbruptExit( $error = false ){
	global $wgLoadBalancer;
	static $called = false;
	if ( $called ){
		exit( -1 );
	}
	$called = true;

	$bt = wfDebugBacktrace();
	if( $bt ) {
		for($i = 0; $i < count($bt) ; $i++){
			$file = isset($bt[$i]['file']) ? $bt[$i]['file'] : "unknown";
			$line = isset($bt[$i]['line']) ? $bt[$i]['line'] : "unknown";
			wfDebug("WARNING: Abrupt exit in $file at line $line\n");
		}
	} else {
		wfDebug('WARNING: Abrupt exit\n');
	}

	wfLogProfilingData();

	if ( !$error ) {
		$wgLoadBalancer->closeAll();
	}
	exit( -1 );
}

/**
 * @deprecated Please return control the caller or throw an exception
 */
function wfErrorExit() {
	wfAbruptExit( true );
}

/**
 * Print a simple message and die, returning nonzero to the shell if any.
 * Plain die() fails to return nonzero to the shell if you pass a string.
 * @param string $msg
 */
function wfDie( $msg='' ) {
	echo $msg;
	die( 1 );
}

/**
 * Throw a debugging exception. This function previously once exited the process, 
 * but now throws an exception instead, with similar results.
 *
 * @param string $msg Message shown when dieing.
 */
function wfDebugDieBacktrace( $msg = '' ) {
	throw new MWException( $msg );
}

/**
 * Fetch server name for use in error reporting etc.
 * Use real server name if available, so we know which machine
 * in a server farm generated the current page.
 * @return string
 */
function wfHostname() {
	if ( function_exists( 'posix_uname' ) ) {
		// This function not present on Windows
		$uname = @posix_uname();
	} else {
		$uname = false;
	}
	if( is_array( $uname ) && isset( $uname['nodename'] ) ) {
		return $uname['nodename'];
	} else {
		# This may be a virtual server.
		return $_SERVER['SERVER_NAME'];
	}
}

	/**
	 * Returns a HTML comment with the elapsed time since request.
	 * This method has no side effects.
	 * @return string
	 */
	function wfReportTime() {
		global $wgRequestTime, $wgShowHostnames;

		$now = wfTime();
		$elapsed = $now - $wgRequestTime;

		return $wgShowHostnames
			? sprintf( "<!-- Served by %s in %01.3f secs. -->", wfHostname(), $elapsed )
			: sprintf( "<!-- Served in %01.3f secs. -->", $elapsed );
	}

/**
 * Safety wrapper for debug_backtrace().
 *
 * With Zend Optimizer 3.2.0 loaded, this causes segfaults under somewhat
 * murky circumstances, which may be triggered in part by stub objects
 * or other fancy talkin'.
 *
 * Will return an empty array if Zend Optimizer is detected, otherwise
 * the output from debug_backtrace() (trimmed).
 *
 * @return array of backtrace information
 */
function wfDebugBacktrace() {
	if( extension_loaded( 'Zend Optimizer' ) ) {
		wfDebug( "Zend Optimizer detected; skipping debug_backtrace for safety.\n" );
		return array();
	} else {
		return array_slice( debug_backtrace(), 1 );
	}
}

function wfBacktrace() {
	global $wgCommandLineMode;

	if ( $wgCommandLineMode ) {
		$msg = '';
	} else {
		$msg = "<ul>\n";
	}
	$backtrace = wfDebugBacktrace();
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
	return wfMsgExt( 'showingresults', array( 'parseinline' ), $wgLang->formatNum( $limit ), $wgLang->formatNum( $offset+1 ) );
}

/**
 * @todo document
 */
function wfShowingResultsNum( $offset, $limit, $num ) {
	global $wgLang;
	return wfMsgExt( 'showingresultsnum', array( 'parseinline' ), $wgLang->formatNum( $limit ), $wgLang->formatNum( $offset+1 ), $wgLang->formatNum( $num ) );
}

/**
 * @todo document
 */
function wfViewPrevNext( $offset, $limit, $link, $query = '', $atend = false ) {
	global $wgLang;
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

	if ( 0 != $offset ) {
		$po = $offset - $limit;
		if ( $po < 0 ) { $po = 0; }
		$q = "limit={$limit}&offset={$po}";
		if ( '' != $query ) { $q .= '&'.$query; }
		$plink = '<a href="' . $title->escapeLocalUrl( $q ) . "\" class=\"mw-prevlink\">{$prev}</a>";
	} else { $plink = $prev; }

	$no = $offset + $limit;
	$q = 'limit='.$limit.'&offset='.$no;
	if ( '' != $query ) { $q .= '&'.$query; }

	if ( $atend ) {
		$nlink = $next;
	} else {
		$nlink = '<a href="' . $title->escapeLocalUrl( $q ) . "\" class=\"mw-nextlink\">{$next}</a>";
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
	global $wgLang;
	if ( '' == $query ) { $q = ''; }
	else { $q = $query.'&'; }
	$q .= 'limit='.$limit.'&offset='.$offset;

	$fmtLimit = $wgLang->formatNum( $limit );
	$s = '<a href="' . $title->escapeLocalUrl( $q ) . "\" class=\"mw-numlink\">{$fmtLimit}</a>";
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
		$m = array();
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
 * Obtain the offset and limit values from the request string;
 * used in special pages
 *
 * @param $deflimit Default limit if none supplied
 * @param $optionname Name of a user preference to check against
 * @return array
 * 
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
		array( '[',     '|',      ']',     '\'',    'ISBN ',     'RFC ',     '://',     "\n=",     '{{' ),
		array( '&#91;', '&#124;', '&#93;', '&#39;', 'ISBN&#32;', 'RFC&#32;', '&#58;//', "\n&#61;", '&#123;&#123;' ),
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
 * @todo document
 * @return float
 */
function wfTime() {
	return microtime(true);
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
 * Append a query string to an existing URL, which may or may not already
 * have query string parameters already. If so, they will be combined.
 *
 * @param string $url
 * @param string $query
 * @return string
 */
function wfAppendQuery( $url, $query ) {
	if( $query != '' ) {
		if( false === strpos( $url, '?' ) ) {
			$url .= '?';
		} else {
			$url .= '&';
		}
		$url .= $query;
	}
	return $url;
}

/**
 * Expand a potentially local URL to a fully-qualified URL.
 * Assumes $wgServer is correct. :)
 * @param string $url, either fully-qualified or a local path + query
 * @return string Fully-qualified URL
 */
function wfExpandUrl( $url ) {
	if( substr( $url, 0, 1 ) == '/' ) {
		global $wgServer;
		return $wgServer . $url;
	} else {
		return $url;
	}
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
			// Escaping for an MSVC-style command line parser
			// Ref: http://mailman.lyra.org/pipermail/scite-interest/2002-March/000436.html
			// Double the backslashes before any double quotes. Escape the double quotes.
			$tokens = preg_split( '/(\\\\*")/', $arg, -1, PREG_SPLIT_DELIM_CAPTURE );
			$arg = '';
			$delim = false;
			foreach ( $tokens as $token ) {
				if ( $delim ) {
					$arg .= str_replace( '\\', '\\\\', substr( $token, 0, -1 ) ) . '\\"';
				} else {
					$arg .= $token;
				}
				$delim = !$delim;
			}
			// Double the backslashes before the end of the string, because
			// we will soon add a quote
			$m = array();
			if ( preg_match( '/^(.*?)(\\\\+)$/', $arg, $m ) ) {
				$arg = $m[1] . str_replace( '\\', '\\\\', $m[2] );
			}

			// Add surrounding quotes
			$retVal .= '"' . $arg . '"';
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

	header( 'Content-type: text/html; charset=utf-8' );
	print "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">".
		"<html><head><title>" .
		htmlspecialchars( $label ) .
		"</title></head><body><h1>" .
		htmlspecialchars( $label ) .
		"</h1><p>" .
		nl2br( htmlspecialchars( $desc ) ) .
		"</p></body></html>\n";
}

/**
 * Clear away any user-level output buffers, discarding contents.
 *
 * Suitable for 'starting afresh', for instance when streaming
 * relatively large amounts of data without buffering, or wanting to
 * output image files without ob_gzhandler's compression.
 *
 * The optional $resetGzipEncoding parameter controls suppression of
 * the Content-Encoding header sent by ob_gzhandler; by default it
 * is left. See comments for wfClearOutputBuffers() for why it would
 * be used.
 *
 * Note that some PHP configuration options may add output buffer
 * layers which cannot be removed; these are left in place.
 *
 * @param bool $resetGzipEncoding
 */
function wfResetOutputBuffers( $resetGzipEncoding=true ) {
	if( $resetGzipEncoding ) {
		// Suppress Content-Encoding and Content-Length
		// headers from 1.10+s wfOutputHandler
		global $wgDisableOutputCompression;
		$wgDisableOutputCompression = true;
	}
	while( $status = ob_get_status() ) {
		if( $status['type'] == 0 /* PHP_OUTPUT_HANDLER_INTERNAL */ ) {
			// Probably from zlib.output_compression or other
			// PHP-internal setting which can't be removed.
			//
			// Give up, and hope the result doesn't break
			// output behavior.
			break;
		}
		if( !ob_end_clean() ) {
			// Could not remove output buffer handler; abort now
			// to avoid getting in some kind of infinite loop.
			break;
		}
		if( $resetGzipEncoding ) {
			if( $status['name'] == 'ob_gzhandler' ) {
				// Reset the 'Content-Encoding' field set by this handler
				// so we can start fresh.
				header( 'Content-Encoding:' );
			}
		}
	}
}

/**
 * More legible than passing a 'false' parameter to wfResetOutputBuffers():
 *
 * Clear away output buffers, but keep the Content-Encoding header
 * produced by ob_gzhandler, if any.
 *
 * This should be used for HTTP 304 responses, where you need to
 * preserve the Content-Encoding header of the real result, but
 * also need to suppress the output of ob_gzhandler to keep to spec
 * and avoid breaking Firefox in rare cases where the headers and
 * body are broken over two packets.
 */
function wfClearOutputBuffers() {
	wfResetOutputBuffers( false );
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
		$match = array();
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
 * @private
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
 * @see http://exif.org/Exif2-2.PDF The Exif 2.2 spec, see page 28 for the
 *       DateTime tag and page 36 for the DateTimeOriginal and
 *       DateTimeDigitized tags.
 */
define('TS_EXIF', 5);

/**
 * Oracle format time.
 */
define('TS_ORACLE', 6);

/**
 * Postgres format time.
 */
define('TS_POSTGRES', 7);

/**
 * @param mixed $outputtype A timestamp in one of the supported formats, the
 *                          function will autodetect which format is supplied
 *                          and act accordingly.
 * @return string Time in the format specified in $outputtype
 */
function wfTimestamp($outputtype=TS_UNIX,$ts=0) {
	$uts = 0;
	$da = array();
	if ($ts==0) {
		$uts=time();
	} elseif (preg_match('/^(\d{4})\-(\d\d)\-(\d\d) (\d\d):(\d\d):(\d\d)$/D',$ts,$da)) {
		# TS_DB
		$uts=gmmktime((int)$da[4],(int)$da[5],(int)$da[6],
			    (int)$da[2],(int)$da[3],(int)$da[1]);
	} elseif (preg_match('/^(\d{4}):(\d\d):(\d\d) (\d\d):(\d\d):(\d\d)$/D',$ts,$da)) {
		# TS_EXIF
		$uts=gmmktime((int)$da[4],(int)$da[5],(int)$da[6],
			(int)$da[2],(int)$da[3],(int)$da[1]);
	} elseif (preg_match('/^(\d{4})(\d\d)(\d\d)(\d\d)(\d\d)(\d\d)$/D',$ts,$da)) {
		# TS_MW
		$uts=gmmktime((int)$da[4],(int)$da[5],(int)$da[6],
			    (int)$da[2],(int)$da[3],(int)$da[1]);
	} elseif (preg_match('/^(\d{1,13})$/D',$ts,$da)) {
		# TS_UNIX
		$uts = $ts;
	} elseif (preg_match('/^(\d{1,2})-(...)-(\d\d(\d\d)?) (\d\d)\.(\d\d)\.(\d\d)/', $ts, $da)) {
		# TS_ORACLE
		$uts = strtotime(preg_replace('/(\d\d)\.(\d\d)\.(\d\d)(\.(\d+))?/', "$1:$2:$3",
				str_replace("+00:00", "UTC", $ts)));
	} elseif (preg_match('/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})Z$/', $ts, $da)) {
		# TS_ISO_8601
		$uts=gmmktime((int)$da[4],(int)$da[5],(int)$da[6],
			(int)$da[2],(int)$da[3],(int)$da[1]);
	} elseif (preg_match('/^(\d{4})\-(\d\d)\-(\d\d) (\d\d):(\d\d):(\d\d)[\+\- ](\d\d)$/',$ts,$da)) {
		# TS_POSTGRES
		$uts=gmmktime((int)$da[4],(int)$da[5],(int)$da[6],
		(int)$da[2],(int)$da[3],(int)$da[1]);
	} elseif (preg_match('/^(\d{4})\-(\d\d)\-(\d\d) (\d\d):(\d\d):(\d\d) GMT$/',$ts,$da)) {
		# TS_POSTGRES
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
		case TS_POSTGRES:
			return gmdate( 'Y-m-d H:i:s', $uts) . ' GMT';
		default:
			throw new MWException( 'wfTimestamp() called with illegal output type.');
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
 * Check if the operating system is Windows
 *
 * @return bool True if it's Windows, False otherwise.
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

function wfGetCachedNotice( $name ) {
	global $wgOut, $parserMemc;
	$fname = 'wfGetCachedNotice';
	wfProfileIn( $fname );
	
	$needParse = false;
	
	if( $name === 'default' ) {
		// special case
		global $wgSiteNotice;
		$notice = $wgSiteNotice;
		if( empty( $notice ) ) {
			wfProfileOut( $fname );
			return false;
		}
	} else {
		$notice = wfMsgForContentNoTrans( $name );
		if( wfEmptyMsg( $name, $notice ) || $notice == '-' ) {
			wfProfileOut( $fname );
			return( false );
		}
	}
	
	$cachedNotice = $parserMemc->get( wfMemcKey( $name ) );
	if( is_array( $cachedNotice ) ) {
		if( md5( $notice ) == $cachedNotice['hash'] ) {
			$notice = $cachedNotice['html'];
		} else {
			$needParse = true;
		}
	} else {
		$needParse = true;
	}
	
	if( $needParse ) {
		if( is_object( $wgOut ) ) {
			$parsed = $wgOut->parse( $notice );
			$parserMemc->set( wfMemcKey( $name ), array( 'html' => $parsed, 'hash' => md5( $notice ) ), 600 );
			$notice = $parsed;
		} else {
			wfDebug( 'wfGetCachedNotice called for ' . $name . ' with no $wgOut available' );
			$notice = '';
		}
	}
	
	wfProfileOut( $fname );
	return $notice;
}

function wfGetNamespaceNotice() {
	global $wgTitle;
	
	# Paranoia
	if ( !isset( $wgTitle ) || !is_object( $wgTitle ) )
		return "";

	$fname = 'wfGetNamespaceNotice';
	wfProfileIn( $fname );
	
	$key = "namespacenotice-" . $wgTitle->getNsText();
	$namespaceNotice = wfGetCachedNotice( $key );
	if ( $namespaceNotice && substr ( $namespaceNotice , 0 ,7 ) != "<p>&lt;" ) {
		 $namespaceNotice = '<div id="namespacebanner">' . $namespaceNotice . "</div>";
	} else {
		$namespaceNotice = "";
	}

	wfProfileOut( $fname );
	return $namespaceNotice;
}

function wfGetSiteNotice() {
	global $wgUser, $wgSiteNotice;
	$fname = 'wfGetSiteNotice';
	wfProfileIn( $fname );
	$siteNotice = '';	
	
	if( wfRunHooks( 'SiteNoticeBefore', array( &$siteNotice ) ) ) {
		if( is_object( $wgUser ) && $wgUser->isLoggedIn() ) {
			$siteNotice = wfGetCachedNotice( 'sitenotice' );
		} else {
			$anonNotice = wfGetCachedNotice( 'anonnotice' );
			if( !$anonNotice ) {
				$siteNotice = wfGetCachedNotice( 'sitenotice' );
			} else {
				$siteNotice = $anonNotice;
			}
		}
		if( !$siteNotice ) {
			$siteNotice = wfGetCachedNotice( 'default' );
		}
	}

	wfRunHooks( 'SiteNoticeAfter', array( &$siteNotice ) );
	wfProfileOut( $fname );
	return $siteNotice;
}

/** 
 * BC wrapper for MimeMagic::singleton()
 * @deprecated
 */
function &wfGetMimeMagic() {
	return MimeMagic::singleton();
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
function wfMkdirParents( $fullDir, $mode = 0777 ) {
	if( strval( $fullDir ) === '' )
		return true;
	if( file_exists( $fullDir ) )
		return true;
	return mkdir( str_replace( '/', DIRECTORY_SEPARATOR, $fullDir ), $mode, true );
}

/**
 * Increment a statistics counter
 */
function wfIncrStats( $key ) {
	global $wgStatsMethod;
	
	if( $wgStatsMethod == 'udp' ) {
		global $wgUDPProfilerHost, $wgUDPProfilerPort, $wgDBname;
		static $socket;
		if (!$socket) {
			$socket=socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
			$statline="stats/{$wgDBname} - 1 1 1 1 1 -total\n";
			socket_sendto($socket,$statline,strlen($statline),0,$wgUDPProfilerHost,$wgUDPProfilerPort);
		}
		$statline="stats/{$wgDBname} - 1 1 1 1 1 {$key}\n";
		@socket_sendto($socket,$statline,strlen($statline),0,$wgUDPProfilerHost,$wgUDPProfilerPort);
	} elseif( $wgStatsMethod == 'cache' ) {
		global $wgMemc;
		$key = wfMemcKey( 'stats', $key );
		if ( is_null( $wgMemc->incr( $key ) ) ) {
			$wgMemc->add( $key, 1 );
		}
	} else {
		// Disabled
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
		throw new MWException('GlobalFunctions::wfAppendToArrayIfNotDefault got null');
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
	return $wfMsgOut === htmlspecialchars( "<$msg>" );
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

function wfSpecialList( $page, $details ) {
	global $wgContLang;
	$details = $details ? ' ' . $wgContLang->getDirMark() . "($details)" : "";
	return $page . $details;
}

/**
 * Returns a regular expression of url protocols
 *
 * @return string
 */
function wfUrlProtocols() {
	global $wgUrlProtocols;

	// Support old-style $wgUrlProtocols strings, for backwards compatibility
	// with LocalSettings files from 1.5
	if ( is_array( $wgUrlProtocols ) ) {
		$protocols = array();
		foreach ($wgUrlProtocols as $protocol)
			$protocols[] = preg_quote( $protocol, '/' );

		return implode( '|', $protocols );
	} else {
		return $wgUrlProtocols;
	}
}

/**
 * Safety wrapper around ini_get() for boolean settings.
 * The values returned from ini_get() are pre-normalized for settings
 * set via php.ini or php_flag/php_admin_flag... but *not*
 * for those set via php_value/php_admin_value.
 *
 * It's fairly common for people to use php_value instead of php_flag,
 * which can leave you with an 'off' setting giving a false positive
 * for code that just takes the ini_get() return value as a boolean.
 *
 * To make things extra interesting, setting via php_value accepts
 * "true" and "yes" as true, but php.ini and php_flag consider them false. :)
 * Unrecognized values go false... again opposite PHP's own coercion
 * from string to bool.
 *
 * Luckily, 'properly' set settings will always come back as '0' or '1',
 * so we only have to worry about them and the 'improper' settings.
 *
 * I frickin' hate PHP... :P
 *
 * @param string $setting
 * @return bool
 */
function wfIniGetBool( $setting ) {
	$val = ini_get( $setting );
	// 'on' and 'true' can't have whitespace around them, but '1' can.
	return strtolower( $val ) == 'on'
		|| strtolower( $val ) == 'true'
		|| strtolower( $val ) == 'yes'
		|| preg_match( "/^\s*[+-]?0*[1-9]/", $val ); // approx C atoi() function
}

/**
 * Execute a shell command, with time and memory limits mirrored from the PHP
 * configuration if supported.
 * @param $cmd Command line, properly escaped for shell.
 * @param &$retval optional, will receive the program's exit code.
 *                 (non-zero is usually failure)
 * @return collected stdout as a string (trailing newlines stripped)
 */
function wfShellExec( $cmd, &$retval=null ) {
	global $IP, $wgMaxShellMemory, $wgMaxShellFileSize;
	
	if( wfIniGetBool( 'safe_mode' ) ) {
		wfDebug( "wfShellExec can't run in safe_mode, PHP's exec functions are too broken.\n" );
		$retval = 1;
		return "Unable to run external programs in safe mode.";
	}

	if ( php_uname( 's' ) == 'Linux' ) {
		$time = intval( ini_get( 'max_execution_time' ) );
		$mem = intval( $wgMaxShellMemory );
		$filesize = intval( $wgMaxShellFileSize );

		if ( $time > 0 && $mem > 0 ) {
			$script = "$IP/bin/ulimit4.sh";
			if ( is_executable( $script ) ) {
				$cmd = escapeshellarg( $script ) . " $time $mem $filesize " . escapeshellarg( $cmd );
			}
		}
	} elseif ( php_uname( 's' ) == 'Windows NT' ) {
		# This is a hack to work around PHP's flawed invocation of cmd.exe
		# http://news.php.net/php.internals/21796
		$cmd = '"' . $cmd . '"';
	}
	wfDebug( "wfShellExec: $cmd\n" );
	
	$retval = 1; // error by default?
	ob_start();
	passthru( $cmd, $retval );
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
	
}

/**
 * This function works like "use VERSION" in Perl, the program will die with a
 * backtrace if the current version of PHP is less than the version provided
 *
 * This is useful for extensions which due to their nature are not kept in sync
 * with releases, and might depend on other versions of PHP than the main code
 *
 * Note: PHP might die due to parsing errors in some cases before it ever
 *       manages to call this function, such is life
 *
 * @see perldoc -f use
 *
 * @param mixed $version The version to check, can be a string, an integer, or
 *                       a float
 */
function wfUsePHP( $req_ver ) {
	$php_ver = PHP_VERSION;

	if ( version_compare( $php_ver, (string)$req_ver, '<' ) )
		 throw new MWException( "PHP $req_ver required--this is only $php_ver" );
}

/**
 * This function works like "use VERSION" in Perl except it checks the version
 * of MediaWiki, the program will die with a backtrace if the current version
 * of MediaWiki is less than the version provided.
 *
 * This is useful for extensions which due to their nature are not kept in sync
 * with releases
 *
 * @see perldoc -f use
 *
 * @param mixed $version The version to check, can be a string, an integer, or
 *                       a float
 */
function wfUseMW( $req_ver ) {
	global $wgVersion;

	if ( version_compare( $wgVersion, (string)$req_ver, '<' ) )
		throw new MWException( "MediaWiki $req_ver required--this is only $wgVersion" );
}

/**
 * @deprecated use StringUtils::escapeRegexReplacement
 */
function wfRegexReplacement( $string ) {
	return StringUtils::escapeRegexReplacement( $string );
}

/**
 * Return the final portion of a pathname.
 * Reimplemented because PHP5's basename() is buggy with multibyte text.
 * http://bugs.php.net/bug.php?id=33898
 *
 * PHP's basename() only considers '\' a pathchar on Windows and Netware.
 * We'll consider it so always, as we don't want \s in our Unix paths either.
 * 
 * @param string $path
 * @param string $suffix to remove if present
 * @return string
 */
function wfBaseName( $path, $suffix='' ) {
	$encSuffix = ($suffix == '')
		? ''
		: ( '(?:' . preg_quote( $suffix, '#' ) . ')?' );
	$matches = array();
	if( preg_match( "#([^/\\\\]*?){$encSuffix}[/\\\\]*$#", $path, $matches ) ) {
		return $matches[1];
	} else {
		return '';
	}
}

/**
 * Generate a relative path name to the given file.
 * May explode on non-matching case-insensitive paths,
 * funky symlinks, etc.
 *
 * @param string $path Absolute destination path including target filename
 * @param string $from Absolute source path, directory only
 * @return string
 */
function wfRelativePath( $path, $from ) {
	// Normalize mixed input on Windows...
	$path = str_replace( '/', DIRECTORY_SEPARATOR, $path );
	$from = str_replace( '/', DIRECTORY_SEPARATOR, $from );
	
	// Trim trailing slashes -- fix for drive root
	$path = rtrim( $path, DIRECTORY_SEPARATOR );
	$from = rtrim( $from, DIRECTORY_SEPARATOR );
	
	$pieces  = explode( DIRECTORY_SEPARATOR, dirname( $path ) );
	$against = explode( DIRECTORY_SEPARATOR, $from );
	
	if( $pieces[0] !== $against[0] ) {
		// Non-matching Windows drive letters?
		// Return a full path.
		return $path;
	}

	// Trim off common prefix
	while( count( $pieces ) && count( $against )
		&& $pieces[0] == $against[0] ) {
		array_shift( $pieces );
		array_shift( $against );
	}

	// relative dots to bump us to the parent
	while( count( $against ) ) {
		array_unshift( $pieces, '..' );
		array_shift( $against );
	}

	array_push( $pieces, wfBaseName( $path ) );

	return implode( DIRECTORY_SEPARATOR, $pieces );
}

/**
 * array_merge() does awful things with "numeric" indexes, including
 * string indexes when happen to look like integers. When we want
 * to merge arrays with arbitrary string indexes, we don't want our
 * arrays to be randomly corrupted just because some of them consist
 * of numbers.
 *
 * Fuck you, PHP. Fuck you in the ear!
 *
 * @param array $array1, [$array2, [...]]
 * @return array
 */
function wfArrayMerge( $array1/* ... */ ) {
	$out = $array1;
	for( $i = 1; $i < func_num_args(); $i++ ) {
		foreach( func_get_arg( $i ) as $key => $value ) {
			$out[$key] = $value;
		}
	}
	return $out;
}

/**
 * Make a URL index, appropriate for the el_index field of externallinks.
 */
function wfMakeUrlIndex( $url ) {
	global $wgUrlProtocols; // Allow all protocols defined in DefaultSettings/LocalSettings.php
	wfSuppressWarnings();
	$bits = parse_url( $url );
	wfRestoreWarnings();
	if ( !$bits ) {
		return false;
	}
	// most of the protocols are followed by ://, but mailto: and sometimes news: not, check for it
	$delimiter = '';
	if ( in_array( $bits['scheme'] . '://' , $wgUrlProtocols ) ) {
		$delimiter = '://';
	} elseif ( in_array( $bits['scheme'] .':' , $wgUrlProtocols ) ) {
		$delimiter = ':';
		// parse_url detects for news: and mailto: the host part of an url as path
		// We have to correct this wrong detection
		if ( isset ( $bits['path'] ) ) { 
			$bits['host'] = $bits['path'];
			$bits['path'] = '';
		}
	} else {
		return false;
	}

	// Reverse the labels in the hostname, convert to lower case
	// For emails reverse domainpart only
	if ( $bits['scheme'] == 'mailto' ) {
		$mailparts = explode( '@', $bits['host'], 2 );
		if ( count($mailparts) === 2 ) {
			$domainpart = strtolower( implode( '.', array_reverse( explode( '.', $mailparts[1] ) ) ) );
		} else {
			// No domain specified, don't mangle it
			$domainpart = '';
		}
		$reversedHost = $domainpart . '@' . $mailparts[0];
	} else {
		$reversedHost = strtolower( implode( '.', array_reverse( explode( '.', $bits['host'] ) ) ) );
	}
	// Add an extra dot to the end
	// Why? Is it in wrong place in mailto links?
	if ( substr( $reversedHost, -1, 1 ) !== '.' ) {
		$reversedHost .= '.';
	}
	// Reconstruct the pseudo-URL
	$prot = $bits['scheme'];
	$index = "$prot$delimiter$reversedHost";
	// Leave out user and password. Add the port, path, query and fragment
	if ( isset( $bits['port'] ) )      $index .= ':' . $bits['port'];
	if ( isset( $bits['path'] ) ) {
		$index .= $bits['path'];
	} else {
		$index .= '/';
	}
	if ( isset( $bits['query'] ) )     $index .= '?' . $bits['query'];
	if ( isset( $bits['fragment'] ) )  $index .= '#' . $bits['fragment'];
	return $index;
}

/**
 * Do any deferred updates and clear the list
 * TODO: This could be in Wiki.php if that class made any sense at all
 */
function wfDoUpdates()
{
	global $wgPostCommitUpdateList, $wgDeferredUpdateList;
	foreach ( $wgDeferredUpdateList as $update ) {
		$update->doUpdate();
	}
	foreach ( $wgPostCommitUpdateList as $update ) {
		$update->doUpdate();
	}
	$wgDeferredUpdateList = array();
	$wgPostCommitUpdateList = array();
}

/**
 * @deprecated use StringUtils::explodeMarkup
 */
function wfExplodeMarkup( $separator, $text ) {
	return StringUtils::explodeMarkup( $separator, $text );
}

/**
 * Convert an arbitrarily-long digit string from one numeric base
 * to another, optionally zero-padding to a minimum column width.
 *
 * Supports base 2 through 36; digit values 10-36 are represented
 * as lowercase letters a-z. Input is case-insensitive.
 *
 * @param $input string of digits
 * @param $sourceBase int 2-36
 * @param $destBase int 2-36
 * @param $pad int 1 or greater
 * @param $lowercase bool
 * @return string or false on invalid input
 */
function wfBaseConvert( $input, $sourceBase, $destBase, $pad=1, $lowercase=true ) {
	$input = strval( $input );
	if( $sourceBase < 2 ||
		$sourceBase > 36 ||
		$destBase < 2 ||
		$destBase > 36 ||
		$pad < 1 ||
		$sourceBase != intval( $sourceBase ) ||
		$destBase != intval( $destBase ) ||
		$pad != intval( $pad ) ||
		!is_string( $input ) ||
		$input == '' ) {
		return false;
	}
	$digitChars = ( $lowercase ) ?  '0123456789abcdefghijklmnopqrstuvwxyz' : '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$inDigits = array();
	$outChars = '';
	
	// Decode and validate input string
	$input = strtolower( $input );
	for( $i = 0; $i < strlen( $input ); $i++ ) {
		$n = strpos( $digitChars, $input{$i} );
		if( $n === false || $n > $sourceBase ) {
			return false;
		}
		$inDigits[] = $n;
	}
	
	// Iterate over the input, modulo-ing out an output digit
	// at a time until input is gone.
	while( count( $inDigits ) ) {
		$work = 0;
		$workDigits = array();
		
		// Long division...
		foreach( $inDigits as $digit ) {
			$work *= $sourceBase;
			$work += $digit;
			
			if( $work < $destBase ) {
				// Gonna need to pull another digit.
				if( count( $workDigits ) ) {
					// Avoid zero-padding; this lets us find
					// the end of the input very easily when
					// length drops to zero.
					$workDigits[] = 0;
				}
			} else {
				// Finally! Actual division!
				$workDigits[] = intval( $work / $destBase );
				
				// Isn't it annoying that most programming languages
				// don't have a single divide-and-remainder operator,
				// even though the CPU implements it that way?
				$work = $work % $destBase;
			}
		}
		
		// All that division leaves us with a remainder,
		// which is conveniently our next output digit.
		$outChars .= $digitChars[$work];
		
		// And we continue!
		$inDigits = $workDigits;
	}
	
	while( strlen( $outChars ) < $pad ) {
		$outChars .= '0';
	}
	
	return strrev( $outChars );
}

/**
 * Create an object with a given name and an array of construct parameters
 * @param string $name
 * @param array $p parameters
 */
function wfCreateObject( $name, $p ){
	$p = array_values( $p );
	switch ( count( $p ) ) {
		case 0:
			return new $name;
		case 1:
			return new $name( $p[0] );
		case 2:
			return new $name( $p[0], $p[1] );
		case 3:
			return new $name( $p[0], $p[1], $p[2] );
		case 4:
			return new $name( $p[0], $p[1], $p[2], $p[3] );
		case 5:
			return new $name( $p[0], $p[1], $p[2], $p[3], $p[4] );
		case 6:
			return new $name( $p[0], $p[1], $p[2], $p[3], $p[4], $p[5] );
		default:
			throw new MWException( "Too many arguments to construtor in wfCreateObject" );
	}
}

/**
 * Aliases for modularized functions
 */
function wfGetHTTP( $url, $timeout = 'default' ) { 
	return Http::get( $url, $timeout ); 
}
function wfIsLocalURL( $url ) { 
	return Http::isLocalURL( $url ); 
}

/**
 * Initialise php session
 */
function wfSetupSession() {
	global $wgSessionsInMemcached, $wgCookiePath, $wgCookieDomain, $wgCookieSecure;
	if( $wgSessionsInMemcached ) {
		require_once( 'MemcachedSessions.php' );
	} elseif( 'files' != ini_get( 'session.save_handler' ) ) {
		# If it's left on 'user' or another setting from another
		# application, it will end up failing. Try to recover.
		ini_set ( 'session.save_handler', 'files' );
	}
	session_set_cookie_params( 0, $wgCookiePath, $wgCookieDomain, $wgCookieSecure);
	session_cache_limiter( 'private, must-revalidate' );
	@session_start();
}

/**
 * Get an object from the precompiled serialized directory
 *
 * @return mixed The variable on success, false on failure
 */
function wfGetPrecompiledData( $name ) {
	global $IP;

	$file = "$IP/serialized/$name";
	if ( file_exists( $file ) ) {
		$blob = file_get_contents( $file );
		if ( $blob ) {
			return unserialize( $blob );
		}
	}
	return false;
}

function wfGetCaller( $level = 2 ) {
	$backtrace = wfDebugBacktrace();
	if ( isset( $backtrace[$level] ) ) {
		return wfFormatStackFrame($backtrace[$level]);
	} else {
		$caller = 'unknown';
	}
	return $caller;
}

/** Return a string consisting all callers in stack, somewhat useful sometimes for profiling specific points */
function wfGetAllCallers() {
	return implode('/', array_map('wfFormatStackFrame',array_reverse(wfDebugBacktrace())));
}

/** Return a string representation of frame */
function wfFormatStackFrame($frame) {
	return isset( $frame["class"] )?
		$frame["class"]."::".$frame["function"]:
		$frame["function"];
}

/**
 * Get a cache key
 */
function wfMemcKey( /*... */ ) {
	global $wgDBprefix, $wgDBname;
	$args = func_get_args();
	if ( $wgDBprefix ) {
		$key = "$wgDBname-$wgDBprefix:" . implode( ':', $args );
	} else {
		$key = $wgDBname . ':' . implode( ':', $args );
	}
	return $key;
}

/**
 * Get a cache key for a foreign DB
 */
function wfForeignMemcKey( $db, $prefix /*, ... */ ) {
	$args = array_slice( func_get_args(), 2 );
	if ( $prefix ) {
		$key = "$db-$prefix:" . implode( ':', $args );
	} else {
		$key = $db . ':' . implode( ':', $args );
	}
	return $key;
}

/**
 * Get an ASCII string identifying this wiki
 * This is used as a prefix in memcached keys
 */
function wfWikiID() {
	global $wgDBprefix, $wgDBname;
	if ( $wgDBprefix ) {
		return "$wgDBname-$wgDBprefix";
	} else {
		return $wgDBname;
	}
}

/*
 * Get a Database object
 * @param integer $db Index of the connection to get. May be DB_MASTER for the 
 *                master (for write queries), DB_SLAVE for potentially lagged 
 *                read queries, or an integer >= 0 for a particular server.
 *
 * @param mixed $groups Query groups. An array of group names that this query 
 *              belongs to. May contain a single string if the query is only 
 *              in one group.
 */
function &wfGetDB( $db = DB_LAST, $groups = array() ) {
	global $wgLoadBalancer;
	$ret = $wgLoadBalancer->getConnection( $db, true, $groups );
	return $ret;
}

/**
 * Find a file. 
 * Shortcut for RepoGroup::singleton()->findFile()
 * @param mixed $title Title object or string. May be interwiki.
 * @param mixed $time Requested time for an archived image, or false for the 
 *                    current version. An image object will be returned which 
 *                    existed at the specified time.
 * @return File, or false if the file does not exist
 */
function wfFindFile( $title, $time = false ) {
	return RepoGroup::singleton()->findFile( $title, $time );
}

/**
 * Get an object referring to a locally registered file.
 * Returns a valid placeholder object if the file does not exist.
 */
function wfLocalFile( $title ) {
	return RepoGroup::singleton()->getLocalRepo()->newFile( $title );
}

/**
 * Should low-performance queries be disabled?
 *
 * @return bool
 */
function wfQueriesMustScale() {
	global $wgMiserMode;
	return $wgMiserMode
		|| ( SiteStats::pages() > 100000
		&& SiteStats::edits() > 1000000
		&& SiteStats::users() > 10000 );
}

/**
 * Get the path to a specified script file, respecting file
 * extensions; this is a wrapper around $wgScriptExtension etc.
 *
 * @param string $script Script filename, sans extension
 * @return string
 */
function wfScript( $script = 'index' ) {
	global $wgScriptPath, $wgScriptExtension;
	return "{$wgScriptPath}/{$script}{$wgScriptExtension}";
}

/**
 * Convenience function converts boolean values into "true"
 * or "false" (string) values
 *
 * @param bool $value
 * @return string
 */
function wfBoolToStr( $value ) {
	return $value ? 'true' : 'false';
}

/**
 * Load an extension messages file
 */
function wfLoadExtensionMessages( $extensionName ) {
	global $wgExtensionMessagesFiles, $wgMessageCache;
	if ( !empty( $wgExtensionMessagesFiles[$extensionName] ) ) {
		$wgMessageCache->loadMessagesFile( $wgExtensionMessagesFiles[$extensionName] );
		// Prevent double-loading
		$wgExtensionMessagesFiles[$extensionName] = false;
	}
}

/**
 * Get a platform-independent path to the null file, e.g.
 * /dev/null
 *
 * @return string
 */
function wfGetNull() {
	return wfIsWindows()
		? 'NUL'
		: '/dev/null';
}

/**
 * Displays a maxlag error
 * 
 * @param string $host Server that lags the most
 * @param int $lag Maxlag (actual)
 * @param int $maxLag Maxlag (requested)
 */
function wfMaxlagError( $host, $lag, $maxLag ) {
	global $wgShowHostnames;
	header( 'HTTP/1.1 503 Service Unavailable' );
	header( 'Retry-After: ' . max( intval( $maxLag ), 5 ) );
	header( 'X-Database-Lag: ' . intval( $lag ) );
	header( 'Content-Type: text/plain' );
	if( $wgShowHostnames ) {
		echo "Waiting for $host: $lag seconds lagged\n";
	} else {
		echo "Waiting for a database server: $lag seconds lagged\n";
	}
}

/**
 * Throws an E_USER_NOTICE saying that $function is deprecated
 * @param string $function
 */
function wfDeprecated( $function ) {
	trigger_error( "Use of $function is deprecated", E_USER_NOTICE );
}
