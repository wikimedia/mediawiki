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

/**
 * html_entity_decode exists in PHP 4.3.0+ but is FATALLY BROKEN even then,
 * with no UTF-8 support.
 *
 * @param string $string String having html entities
 * @param $quote_style
 * @param string $charset Encoding set to use (default 'ISO-8859-1')
 */
function do_html_entity_decode( $string, $quote_style=ENT_COMPAT, $charset='ISO-8859-1' ) {
	$fname = 'do_html_entity_decode';
	wfProfileIn( $fname );
	
	static $trans;
	static $savedCharset;
	static $regexp;
	if( !isset( $trans ) || $savedCharset != $charset ) {
		$trans = array_flip( get_html_translation_table( HTML_ENTITIES, $quote_style ) );
		$savedCharset = $charset;
		
		# Note - mixing latin1 named entities and unicode numbered
		# ones will result in a bad link.
		if( strcasecmp( 'utf-8', $charset ) == 0 ) {
			$trans = array_map( 'utf8_encode', $trans );
		}
		
		/**
		 * Most links will _not_ contain these fun guys,
		 * and on long pages with many links we can get
		 * called a lot.
		 *
		 * A regular expression search is faster than
		 * a strtr or str_replace with a hundred-ish
		 * entries, though it may be slower to actually
		 * replace things.
		 *
		 * They all look like '&xxxx;'...
		 */
		foreach( $trans as $key => $val ) {
			$snip[] = substr( $key, 1, -1 );
		}
		$regexp = '/(&(?:' . implode( '|', $snip ) . ');)/e';
	}

	$out = preg_replace( $regexp, '$trans["$1"]', $string );
	wfProfileOut( $fname );
	return $out;
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
	$rand = number_format( mt_rand() * mt_rand()
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
 * Return the UTF-8 sequence for a given Unicode code point.
 * Currently doesn't work for values outside the Basic Multilingual Plane.
 *
 * @param string $codepoint UTF-8 code point.
 * @return string HTML UTF-8 Entitie such as '&#1234;'.
 */
function wfUtf8Sequence( $codepoint ) {
	if($codepoint <		0x80) return chr($codepoint);
	if($codepoint <    0x800) return chr($codepoint >>	6 & 0x3f | 0xc0) .
									 chr($codepoint		  & 0x3f | 0x80);
	if($codepoint <  0x10000) return chr($codepoint >> 12 & 0x0f | 0xe0) .
									 chr($codepoint >>	6 & 0x3f | 0x80) .
									 chr($codepoint		  & 0x3f | 0x80);
	if($codepoint < 0x110000) return chr($codepoint >> 18 & 0x07 | 0xf0) .
									 chr($codepoint >> 12 & 0x3f | 0x80) .
									 chr($codepoint >>	6 & 0x3f | 0x80) .
									 chr($codepoint		  & 0x3f | 0x80);

	# There should be no assigned code points outside this range, but...
	return "&#$codepoint;";
}

/**
 * Converts numeric character entities to UTF-8
 *
 * @param string $string String to convert.
 * @return string Converted string.
 */
function wfMungeToUtf8( $string ) {
	global $wgInputEncoding; # This is debatable
	#$string = iconv($wgInputEncoding, "UTF-8", $string);
	$string = preg_replace ( '/&#([0-9]+);/e', 'wfUtf8Sequence($1)', $string );
	$string = preg_replace ( '/&#x([0-9a-f]+);/ie', 'wfUtf8Sequence(0x$1)', $string );
	# Should also do named entities here
	return $string;
}

/**
 * Converts a single UTF-8 character into the corresponding HTML character
 * entity (for use with preg_replace_callback)
 *
 * @param array $matches
 *
 */
function wfUtf8Entity( $matches ) {
	$codepoint = utf8ToCodepoint( $matches[0] );
	return "&#$codepoint;";
}

/**
 * Converts all multi-byte characters in a UTF-8 string into the appropriate
 * character entity
 */
function wfUtf8ToHTML($string) {
	return preg_replace_callback( '/[\\xc0-\\xfd][\\x80-\\xbf]*/', 'wfUtf8Entity', $string );
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
		error_log( $text, 3, $wgDebugLogFile );
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
		if($wgUser->getId() == 0)
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
	global $wgReadOnlyFile;

	if ( '' == $wgReadOnlyFile ) {
		return false;
	}
	return is_file( $wgReadOnlyFile );
}


/**
 * Get a message from anywhere, for the UI elements
 */
function wfMsg( $key ) {
	$args = func_get_args();
	array_shift( $args );
	return wfMsgReal( $key, $args, true );
}

/**
 * Get a message from anywhere, for the content
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
	static $replacementKeys = array( '$1', '$2', '$3', '$4', '$5', '$6', '$7', '$8', '$9' );
	global $wgParser, $wgMsgParserOptions;
	global $wgContLang, $wgLanguageCode;
	global $wgMessageCache, $wgLang;
	
	$fname = 'wfMsgReal';
	wfProfileIn( $fname );

	if( is_object( $wgMessageCache ) ) {
		$message = $wgMessageCache->get( $key, $useDB, $forContent );
    }
	else {
		if( $forContent ) {
			$lang = &$wgContLang;
		} else {
			$lang = &$wgLang;
		}

		wfSuppressWarnings();
		$message = $lang->getMessage( $key );
		wfRestoreWarnings();
		if(!$message)
			$message = Language::getMessage($key);
		if(strstr($message, '{{' ) !== false) {
			$message = $wgParser->transformMsg($message, $wgMsgParserOptions);
		}
	}

	# Replace arguments
	if( count( $args ) ) {
		$message = str_replace( $replacementKeys, $args, $message );
	}
	wfProfileOut( $fname );
	return $message;
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
			$file = $bt[$i]['file'];
			$line = $bt[$i]['line'];
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

	if ( function_exists( 'debug_backtrace' ) ) {
		if ( $wgCommandLineMode ) {
			$msg .= "\nBacktrace:\n";
		} else {
			$msg .= "\n<p>Backtrace:</p>\n<ul>\n";
		}
		$backtrace = debug_backtrace();
		foreach( $backtrace as $call ) {
			$f = explode( DIRECTORY_SEPARATOR, $call['file'] );
			$file = $f[count($f)-1];
			if ( $wgCommandLineMode ) {
				$msg .= "$file line {$call['line']} calls ";
			} else {
				$msg .= '<li>' . $file . ' line ' . $call['line'] . ' calls ';
			}
			if( !empty( $call['class'] ) ) $msg .= $call['class'] . '::';
			$msg .= $call['function'] . '()';

			if ( $wgCommandLineMode ) {
				$msg .= "\n";
			} else {
				$msg .= "</li>\n";
			}
		}
	 }
	 die( $msg );
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
		$title =& Title::newFromText( $link );
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
		array( '[',		'|',	  "'",	   'ISBN '	  , '://'	  , "\n=", '{{' ),
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
		return false;
	}

	# Make temporary files
	$td = '/tmp/';
	$oldtextFile = fopen( $oldtextName = tempnam( $td, 'merge-old-' ), 'w' );
	$mytextFile = fopen( $mytextName = tempnam( $td, 'merge-mine-' ), 'w' );
	$yourtextFile = fopen( $yourtextName = tempnam( $td, 'merge-your-' ), 'w' );

	fwrite( $oldtextFile, $old ); fclose( $oldtextFile );
	fwrite( $mytextFile, $mine ); fclose( $mytextFile );
	fwrite( $yourtextFile, $yours ); fclose( $yourtextFile );

	# Check for a conflict
	$cmd = wfEscapeShellArg( $wgDiff3 ) . ' -a --overlap-only ' .
	  wfEscapeShellArg( $mytextName ) . ' ' .
	  wfEscapeShellArg( $oldtextName ) . ' ' .
	  wfEscapeShellArg( $yourtextName );
	$handle = popen( $cmd, 'r' );

	if( fgets( $handle ) ){
		$conflict = true;
	} else {
		$conflict = false;
	}
	pclose( $handle );

	# Merge differences
	$cmd = wfEscapeShellArg( $wgDiff3 ) . ' -a -e --merge ' .
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
	return ! $conflict;
}

/**
 * @todo document
 */
function wfVarDump( $var ) {
	global $wgOut;
	$s = str_replace("\n","<br>\n", var_export( $var, true ) . "\n");
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

	# Don't send content if it's a HEAD request.
	if( $_SERVER['REQUEST_METHOD'] == 'HEAD' ) {
		header( 'Content-type: text/plain' );
		print "$desc\n";
	}
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
 * Convenience function; adds seconds to a MediaWiki timestamp.
 * @return string
 */
function wfTimestampPlus ( $ts, $moreseconds ) {
	return gmdate( 'YmdHis', wfTimestamp( TS_UNIX, $ts ) + $moreseconds );
}

/**
 * Sorting hack for MySQL 3, which doesn't use index sorts for DESC
 */
function wfInvertTimestamp( $ts ) {
	return strtr(
		$ts,
		'0123456789',
		'9876543210'
	);
}

/**
 * Reference-counted warning suppression
 */
function wfSuppressWarnings( $end = false ) {
	static $suppressCount = 0;
	static $originalLevel = false;

	if ( $end ) {
		if ( $suppressCount ) {
			$suppressCount --;
			if ( !$suppressCount ) {
				error_reporting( $originalLevel );
			}
		}
	} else {
		if ( !$suppressCount ) {
			$originalLevel = error_reporting( E_ALL & ~( E_WARNING | E_NOTICE ) );
		}
		$suppressCount++;
	}
}

/**
 * Restore error level to previous value
 */
function wfRestoreWarnings() {
	wfSuppressWarnings( true );
}

# Autodetect, convert and provide timestamps of various types

/** Standard unix timestamp (number of seconds since 1 Jan 1970) */
define('TS_UNIX',0);
/** MediaWiki concatenated string timestamp (yyyymmddhhmmss) */
define('TS_MW',1);	
/** Standard database timestamp (yyyy-mm-dd hh:mm:ss) */
define('TS_DB',2);

/**
 * @todo document
 */
function wfTimestamp($outputtype=TS_UNIX,$ts=0) {
	if (preg_match("/^(\d{4})\-(\d\d)\-(\d\d) (\d\d):(\d\d):(\d\d)$/",$ts,$da)) {
		# TS_DB
		$uts=gmmktime((int)$da[4],(int)$da[5],(int)$da[6],
			    (int)$da[2],(int)$da[3],(int)$da[1]);
	} elseif (preg_match("/^(\d{4})(\d\d)(\d\d)(\d\d)(\d\d)(\d\d)$/",$ts,$da)) {
		# TS_MW
		$uts=gmmktime((int)$da[4],(int)$da[5],(int)$da[6],
			    (int)$da[2],(int)$da[3],(int)$da[1]);
	} elseif (preg_match("/^(\d{1,13})$/",$ts,$datearray)) {
		# TS_UNIX
		$uts=$ts;
	}

	if ($ts==0)
		$uts=time();
 	switch($outputtype) {
	case TS_UNIX:
		return $uts;
		break;
	case TS_MW:
		return gmdate( 'YmdHis', $uts );
		break;
	case TS_DB:
		return gmdate( 'Y-m-d H:i:s', $uts );
		break;
	default:
		return;
	}
}

/**
 * Check where as the operating system is Windows
 *
 * @todo document
 * @return bool True if it's windows, False otherwise.
 */
function wfIsWindows() {   
	if (substr(php_uname(), 0, 7) == 'Windows') {   
		return true;   
	} else {   
		return false;   
	}   
} 

?>
