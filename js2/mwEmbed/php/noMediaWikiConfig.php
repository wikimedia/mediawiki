<?php

// Give us true for MediaWiki
define( 'MEDIAWIKI', true );

define( 'MWEMBED_STANDALONE', true );

// Setup the globals: 	(for documentation see: DefaultSettings.php )

$wgJSAutoloadLocalClasses = array();

$IP = realpath( dirname( __FILE__ ) . '/../' );

// $wgMwEmbedDirectory becomes the root $IP
$wgMwEmbedDirectory = '';

$wgFileCacheDirectory = realpath( dirname( __FILE__ ) ) . '/script-cache';

$wgUseFileCache = true;

$wgEnableScriptLoaderJsFile = false;

// Init our wg Globals
$wgJSAutoloadClasses = array();
$wgJSAutoloadLocalClasses = array();

/*Localization:*/
$wgEnableScriptLocalization = true;
$mwLanguageCode = 'en';

$wgContLanguageCode = '';

$wgStyleVersion = '218';

$wgEnableScriptMinify = true;

$wgUseGzip = true;


/**
 * Default value for chmoding of new directories.
 */
$wgDirectoryMode = 0777;

$wgJsMimeType = 'text/javascript';

// Get the autoload classes
require_once( realpath( dirname( __FILE__ ) ) . '/jsAutoloadLocalClasses.php' );

// Get the JSmin class:
require_once( realpath( dirname( __FILE__ ) ) . '/minify/JSMin.php' );

// Some static utility MediaWiki functions that we use:
function wfClientAcceptsGzip() {
	global $wgUseGzip;
	if ( $wgUseGzip ) {
		# FIXME: we may want to blacklist some broken browsers
		$m = array();
		if ( preg_match(
			'/\bgzip(?:;(q)=([0-9]+(?:\.[0-9]+)))?\b/',
			$_SERVER['HTTP_ACCEPT_ENCODING'],
			$m ) ) {
			if ( isset( $m[2] ) && ( $m[1] == 'q' ) && ( $m[2] == 0 ) ) return false;
			wfDebug( " accepts gzip\n" );
			return true;
		}
	}
	return false;
}
function wfDebug() {
    return false;
}

/**
 * Make directory, and make all parent directories if they don't exist
 *
 * @param string $dir Full path to directory to create
 * @param int $mode Chmod value to use, default is $wgDirectoryMode
 * @param string $caller Optional caller param for debugging.
 * @return bool
 */
function wfMkdirParents( $dir, $mode = null, $caller = null ) {
	global $wgDirectoryMode;

	if ( !is_null( $caller ) ) {
		wfDebug( "$caller: called wfMkdirParents($dir)" );
	}

	if ( strval( $dir ) === '' || file_exists( $dir ) )
		return true;

	if ( is_null( $mode ) )
		$mode = $wgDirectoryMode;

	return @mkdir( $dir, $mode, true );  // PHP5 <3
}
function wfMsgNoTrans( $msgKey ) {
    global $messages, $mwLanguageCode;
    // Make sure we have the messages file:
    require_once( realpath( dirname( __FILE__ ) ) . '/languages/mwEmbed.i18n.php' );

    if ( isset( $messages[$mwLanguageCode] ) && isset( $messages[$mwLanguageCode][$msgKey] ) ) {
        return $messages[$mwLanguageCode][$msgKey];
    } else {
        return '&lt;' . $msgKey . '&gt;';
    }
}
class FormatJson{
	public static function encode($value, $isHtml=false){
		// Some versions of PHP have a broken json_encode, see PHP bug
		// 46944. Test encoding an affected character (U+20000) to
		// avoid this.
		if (!function_exists('json_encode') || $isHtml || strtolower(json_encode("\xf0\xa0\x80\x80")) != '\ud840\udc00') {
			$json = new Services_JSON();
			return $json->encode($value, $isHtml) ;
		} else {
			return json_encode($value);
		}
	}
	public static function decode( $value, $assoc=false ){
		if (!function_exists('json_decode') ) {
			$json = new Services_JSON();
			$jsonDec = $json->decode( $value );
			if( $assoc )
				$jsonDec = wfObjectToArray( $jsonDec );
			return $jsonDec;
		} else {
			return json_decode( $value, $assoc );
		}
	}
}

?>
