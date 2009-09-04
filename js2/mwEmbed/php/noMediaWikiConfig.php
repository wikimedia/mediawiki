<?php
//give us true for mediaWiki
define( 'MEDIAWIKI', true );

define('MWEMBED_STANDALONE', true);

//setup the globals: 	(for documentation see: DefaultSettings.php )

$wgJSAutoloadLocalClasses = array();

$IP = realpath(dirname(__FILE__).'/../');

//$wgMwEmbedDirectory becomes the root $IP
$wgMwEmbedDirectory = '';

$wgFileCacheDirectory = realpath(dirname(__FILE__)) . '/script-cache';

$wgUseFileCache = true;

$wgEnableScriptLoaderJsFile = false;

//init our wg Globals
$wgJSAutoloadClasses = array();
$wgJSAutoloadLocalClasses = array();

/*Localization:*/
$wgEnableScriptLocalization = true;
$mwLanguageCode = 'en';

$wgContLanguageCode ='';

$wgStyleVersion = '218';

$wgEnableScriptMinify = true;

$wgUseGzip = true;


/**
 * Default value for chmoding of new directories.
 */
$wgDirectoryMode = 0777;

$wgJsMimeType = 'text/javascript';

//get the autoLoadClasses
require_once( realpath( dirname(__FILE__) ) . '/jsAutoloadLocalClasses.php' );

//get the JSmin class:
require_once( realpath( dirname(__FILE__) ) . '/minify/JSMin.php' );

//some static utility mediaWiki functions that we use:
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
function wfDebug(){
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

	if( strval( $dir ) === '' || file_exists( $dir ) )
		return true;

	if ( is_null( $mode ) )
		$mode = $wgDirectoryMode;

	return @mkdir( $dir, $mode, true );  // PHP5 <3
}
function wfMsgNoTrans($msgKey){
    global $messages, $mwLanguageCode;
    //make sure we have the messages file:
    require_once( realpath( dirname(__FILE__) ) . '/languages/mwEmbed.i18n.php' );

    if(isset($messages[$mwLanguageCode]) && isset($messages[$mwLanguageCode][$msgKey]) ){
        return $messages[$mwLanguageCode][$msgKey];
    }else{
        return '&lt;' . $msgKey . '&gt;';
    }
}
?>