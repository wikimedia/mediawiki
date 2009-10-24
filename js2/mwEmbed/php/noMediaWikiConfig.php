<?php

// Give us true for MediaWiki
define( 'MEDIAWIKI', true );

define( 'MWEMBED_STANDALONE', true );

// Setup the globals: 	(for documentation see: DefaultSettings.php )

$wgJSAutoloadLocalClasses = array();

$IP = realpath( dirname( __FILE__ ) . '/../' );

// $wgMwEmbedDirectory becomes the root $IP
$wgMwEmbedDirectory = '';

$wgUseFileCache = true;

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
function wfMsgGetKey( $msgKey ) {
    global $messages, $mwLanguageCode;
    // Make sure we have the messages file:
    require_once( realpath( dirname( __FILE__ ) ) . '/languages/mwEmbed.i18n.php' );

    if ( isset( $messages[$mwLanguageCode] ) && isset( $messages[$mwLanguageCode][$msgKey] ) ) {
        return $messages[$mwLanguageCode][$msgKey];
    } else {
        return '&lt;' . $msgKey . '&gt;';
    }
}
/* mediaWiki abstracts the json functions with fallbacks
* here we just map directly to the call */
class FormatJson{
	public static function encode($value, $isHtml=false){
		return json_encode($value);
	}
	public static function decode( $value, $assoc=false ){
		return json_decode( $value, $assoc );
	}
}

?>
