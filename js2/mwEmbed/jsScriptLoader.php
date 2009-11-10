<?php
/**
 * This core jsScriptLoader class provides the script loader functionality
 * @file
 */


//Setup the script local script cache directory (has to be hard coded rather than config based for fast non-mediawiki config hits
$wgScriptCacheDirectory = realpath( dirname( __FILE__ ) ) . '/php/script-cache';

// Check if we are being invoked in a MediaWiki context or stand alone usage:
if ( !defined( 'MEDIAWIKI' ) && !defined( 'MW_CACHE_SCRIPT_CHECK' ) ){
	// Load noMediaWiki helper for quick cache result
	$myScriptLoader = new jsScriptLoader();
	if( $myScriptLoader->outputFromCache() )
		exit();
	//Else load up all the config and do normal stand alone ScriptLoader process:
	require_once( realpath( dirname( __FILE__ ) ) . '/php/noMediaWikiConfig.php' );
	$myScriptLoader->doScriptLoader();
}

class jsScriptLoader {
	var $jsFileList = array();
	var $langCode = '';
	var $jsout = '';
	var $rKey = ''; // the request key
	var $error_msg = '';
	var $debug = false;
	var $jsvarurl = false; // whether we should include generated JS (special class '-')
	var $doProcReqFlag = true;

	function outputFromCache(){
		// Process the request
		$this->rKey = $this->preProcRequestVars();
		// Setup file cache object
		$this->sFileCache = new simpleFileCache( $this->rKey );
		if ( $this->sFileCache->isFileCached() ) {
			// Just output headers so we can use PHP's @readfile::
			$this->outputJsHeaders();
			$this->sFileCache->outputFromFileCache();
			return true;
		}
		return false;
	}

	function doScriptLoader() {
		global 	$wgJSAutoloadClasses, $wgJSAutoloadLocalClasses, $IP,
		$wgEnableScriptMinify, $wgUseFileCache, $wgExtensionMessagesFiles;

		//load the ExtensionMessagesFiles
		$wgExtensionMessagesFiles['mwEmbed'] = realpath( dirname( __FILE__ ) ) . '/php/languages/mwEmbed.i18n.php';

		//reset the rKey:
		$this->rKey = '';
		//do the post proc request with configuration vars:
		$this->postProcRequestVars();
		//update the filename (if gzip is on)
		$this->sFileCache->getCacheFileName();

		// Setup script loader header info
		// @@todo we might want to put these into the $mw var per class request set
		// and or include a callback to avoid pulling in old browsers that don't support
		// the onLoad attribute for script elements.
		$this->jsout .= 'var mwSlScript = "' .  $_SERVER['SCRIPT_NAME']  . '";' . "\n";
		$this->jsout .= 'var mwSlGenISODate = "' . date( 'c' ) . '";'  . "\n";
		$this->jsout .= 'var mwSlURID = "' . htmlspecialchars( $this->urid ) . '";'  . "\n";
		$this->jsout .= 'var mwLang = "' . htmlspecialchars( $this->langCode ) . '";' . "\n";
		// Build the output

		// Swap in the appropriate language per js_file
		foreach ( $this->jsFileList as $classKey => $file_name ) {
			//get the script content
			$jstxt = $this->getScriptText($classKey, $file_name);
			if( $jstxt ){
				$this->jsout .= $this->doProcessJs( $jstxt );
			}
		}
		// Check if we should minify the whole thing:
		if ( !$this->debug ) {
			// do the minification and output
			$this->jsout = JSMin::minify( $this->jsout );
		}
		// Save to the file cache
		if ( $wgUseFileCache && !$this->debug ) {
			$status = $this->sFileCache->saveToFileCache( $this->jsout );
			if ( $status !== true )
			$this->error_msg .= $status;
		}
		// Check for an error msg
		if ( $this->error_msg != '' ) {
			//just set the content type (don't send cache header)
			header( 'Content-Type: text/javascript' );
			echo 'alert(\'Error With ScriptLoader.php ::' . str_replace( "\n", '\'+"\n"+' . "\n'", $this->error_msg ) . '\');';
			echo trim( $this->jsout );
		} else {
			// All good, let's output "cache" headers
			$this->outputJsWithHeaders();
		}
	}
	function getScriptText($classKey, $file_name=''){
		$jsout = '';
		// Special case: title classes
		if ( substr( $classKey, 0, 3 ) == 'WT:' ) {
			global $wgUser;
			// Get just the title part
			$title_block = substr( $classKey, 3 );
			if ( $title_block[0] == '-' && strpos( $title_block, '|' ) !== false ) {
				// Special case of "-" title with skin
				$parts = explode( '|', $title_block );
				$title = array_shift( $parts );
				foreach ( $parts as $tparam ) {
					list( $key, $val ) = explode( '=', $tparam );
					if ( $key == 'useskin' ) {
						$skin = $val;
					}
				}
				$sk = $wgUser->getSkin();
				// Make sure the skin name is valid
				$skinNames = Skin::getSkinNames();
				$skinNames = array_keys( $skinNames );
				if ( in_array( strtolower( $skin ), $skinNames ) ) {
					// If in debug mode, add a comment with wiki title and rev:
					if ( $this->debug )
					$jsout .= "\n/**\n* GenerateUserJs: \n*/\n";
					return $jsout . $sk->generateUserJs( $skin ) . "\n";
				}
			} else {
				// Make sure the wiki title ends with .js
				if ( substr( $title_block, - 3 ) != '.js' ) {
					$this->error_msg .= 'WikiTitle includes should end with .js';
					return false;
				}
				// It's a wiki title, append the output of the wikitext:
				$t = Title::newFromText( $title_block );
				$a = new Article( $t );
				// Only get the content if the page is not empty:
				if ( $a->getID() !== 0 ) {
					// If in debug mode, add a comment with wiki title and rev:
					if ( $this->debug )
					$jsout .= "\n/**\n* WikiJSPage: " . htmlspecialchars( $title_block ) . " rev: " . $a->getID() . " \n*/\n";

					return $jsout . $a->getContent() . "\n";
				}
			}
		}else{
			// Dealing with files

			// Check that the filename ends with .js and does not include ../ traversing
			if ( substr( $file_name, - 3 ) != '.js' ) {
				$this->error_msg .= "\nError file name must end with .js: " . htmlspecialchars( $file_name ) . " \n ";
				return false;
			}
			if ( strpos( $file_name, '../' ) !== false ) {
				$this->error_msg .= "\nError file name must not traverse paths: " . htmlspecialchars( $file_name ) . " \n ";
				return false;
			}

			if ( trim( $file_name ) != '' ) {
				if ( $this->debug )
				$jsout .= "\n/**\n* File: " . htmlspecialchars( $file_name ) . "\n*/\n";

				$jsFileStr = $this->doGetJsFile( $file_name ) . "\n";
				if( $jsFileStr ){
					return $jsout . $jsFileStr;
				}else{
					$this->error_msg .= "\nError could not read file: ". htmlspecialchars( $file_name )  ."\n";
					return false;
				}
			}
		}
		//if we did not return some js
		$this->error_msg .= "\nUnknown error\n";
		return false;
	}
	function outputJsHeaders() {
		// Output JS MIME type:
		header( 'Content-Type: text/javascript' );
		header( 'Pragma: public' );
		// Cache for 1 day ( we should always change the request URL
		// based on the SVN or article version.
		$one_day = 60 * 60 * 24;
		header( "Expires: " . gmdate( "D, d M Y H:i:s", time() + $one_day ) . " GM" );
	}

	function outputJsWithHeaders() {
		global $wgUseGzip;
		$this->outputJsHeaders();
		if ( $wgUseGzip ) {
			if ( $this->clientAcceptsGzip() ) {
				header( 'Content-Encoding: gzip' );
				echo gzencode( $this->jsout );
			} else {
				echo $this->jsout;
			}
		} else {
			echo $this->jsout;
		}
	}

	function clientAcceptsGzip() {
		$m = array();
		if( preg_match(
			'/\bgzip(?:;(q)=([0-9]+(?:\.[0-9]+)))?\b/',
		$_SERVER['HTTP_ACCEPT_ENCODING'],
		$m ) ) {
			if( isset( $m[2] ) && ( $m[1] == 'q' ) && ( $m[2] == 0 ) )
			return false;
			//no gzip support found
			return true;
		}
		return false;
	}
	/*
	 * postProcRequestVars uses globals, configuration and mediaWiki to test wiki-titles and files exist etc.
	 */
	function postProcRequestVars(){
		global $wgContLanguageCode, $wgEnableScriptMinify, $wgJSAutoloadClasses,
		$wgJSAutoloadLocalClasses, $wgStyleVersion;

		// Set debug flag
		if ( ( isset( $_GET['debug'] ) && $_GET['debug'] == 'true' ) || ( isset( $wgEnableScriptDebug ) && $wgEnableScriptDebug == true ) ) {
			$this->debug = true;
		}

		// Set the urid. Be sure to escape it as it goes into our JS output.
		if ( isset( $_GET['urid'] ) && $_GET['urid'] != '' ) {
			$this->urid = htmlspecialchars( $_GET['urid'] );
		} else {
			// Just give it the current style sheet ID:
			// @@todo read the svn version number
			$this->urid = $wgStyleVersion;
		}

		//get the language code (if not provided use the "default" language
		if ( isset( $_GET['uselang'] ) && $_GET['uselang'] != '' ) {
			//make sure its a valid lang code:
			$this->langCode = preg_replace( "/[^A-Za-z]/", '', $_GET['uselang']);
		}else{
			//set english as default
			$this->langCode = 'en';
		}

		$reqClassList = false;
		if ( isset( $_GET['class'] ) && $_GET['class'] != '' ) {
			$reqClassList = explode( ',', $_GET['class'] );
		}

		// Check for the requested classes
		if ( $reqClassList ) {
			// Clean the class list and populate jsFileList
			foreach ( $reqClassList as $reqClass ) {
				if ( trim( $reqClass ) != '' ) {
					if ( substr( $reqClass, 0, 3 ) == 'WT:' ) {
						$doAddWT = false;
						// Check for special case '-' class for user-generated JS
						if( substr( $reqClass, 3, 1) == '-'){
							$doAddWT = true;
						}else{
							if( strtolower( substr( $reqClass, -3) ) == '.js'){
								//make sure its a valid wikipage before doing processing
								$t = Title::newFromDBkey( substr( $reqClass, 3) );
								if( $t->exists()
								&& ( $t->getNamespace() == NS_MEDIAWIKI
								|| $t->getNamespace() == NS_USER ) ){
									$doAddWT = true;
								}
							}
						}
						if( $doAddWT ){
							$this->jsFileList[$reqClass] = true;
							$this->rKey .= $reqClass;
							$this->jsvarurl = true;
						}
						continue;
					}

					$reqClass = preg_replace( "/[^A-Za-z0-9_\-\.]/", '', $reqClass );

					$jsFilePath = self::getJsPathFromClass( $reqClass );
					if(!$jsFilePath){
						$this->error_msg .= 'Requested class: ' . htmlspecialchars( $reqClass ) . ' not found' . "\n";
					}else{
						$this->jsFileList[ $reqClass ] = $jsFilePath;
						$this->rKey .= $reqClass;
					}
				}
			}
		}


		// Add the language code to the rKey:
		$this->rKey .= '_' . $wgContLanguageCode;

		// Add the unique rid
		$this->rKey .= $this->urid;

		// Add a minify flag
		if ( $wgEnableScriptMinify ) {
			$this->rKey .= '_min';
		}
	}
	/**
	 * Pre-process request variables ~without configuration~ or much utility function~
	 *  This is to quickly get a rKey that we can check against the cache
	 */
	function preProcRequestVars() {
		$rKey = '';
		// Check for debug (won't use the cache)
		if ( ( isset( $_GET['debug'] ) && $_GET['debug'] == 'true' ) ) {
			//we are going to have to run postProcRequest
			return false;
		}

		// Check for the urid. Be sure to escape it as it goes into our JS output.
		if ( isset( $_GET['urid'] ) && $_GET['urid'] != '' ) {
			$urid = htmlspecialchars( $_GET['urid'] );
		}else{
			die( 'missing urid param');
		}

		//get the language code (if not provided use the "default" language
		if ( isset( $_GET['uselang'] ) && $_GET['uselang'] != '' ) {
			//make sure its a valid lang code:
			$langCode = preg_replace( "/[^A-Za-z]/", '', $_GET['uselang']);
		}else{
			//set english as default
			$langCode = 'en';
		}


		$reqClassList = false;
		if ( isset( $_GET['class'] ) && $_GET['class'] != '' ) {
			$reqClassList = explode( ',', $_GET['class'] );
		}

		// Check for the requested classes
		if ( count( $reqClassList ) > 0 ) {
			// Clean the class list and populate jsFileList
			foreach (  $reqClassList as $reqClass ) {
				//do some simple checks:
				if ( trim( $reqClass ) != '' ){
					if( substr( $reqClass, 0, 3 ) == 'WT:'  && strtolower( substr( $reqClass, -3) ) == '.js' ){
						//wiki page requests (must end with .js):
						$rKey .= $reqClass;
					}else if( substr( $reqClass, 0, 3 ) != 'WT:' ){
						//normal class requests:
						$reqClass = preg_replace( "/[^A-Za-z0-9_\-\.]/", '', $reqClass );
						$rKey .= $reqClass;
					}else{
						//not a valid class don't add it
					}
				}
			}
		}
		// Add the language code to the rKey:
		$rKey .= '_' . $langCode;

		// Add the unique rid
		$rKey .= $urid;

		return $rKey;
	}
	public static function getJsPathFromClass( $reqClass ){
		global $wgJSAutoloadLocalClasses, $wgJSAutoloadClasses;
		if ( isset( $wgJSAutoloadLocalClasses[$reqClass] ) ) {
			return $wgJSAutoloadLocalClasses[$reqClass];
		} else if ( isset( $wgJSAutoloadClasses[$reqClass] ) ) {
			return $wgJSAutoloadClasses[$reqClass];
		} else {
			return false;
		}
	}
	function doGetJsFile( $file_path ) {
		global $IP;
		// Load the file
		$str = @file_get_contents( "{$IP}/{$file_path}" );

		if ( $str === false ) {
			// @@todo check PHP error level. Don't want to expose paths if errors are hidden.
			$this->error_msg .= 'Requested File: ' . htmlspecialchars( $file_path ) . ' could not be read' . "\n";
			return false;
		}
		return $str;
	}
	function doProcessJs( $str ){
		global $wgEnableScriptLocalization;
		// Strip out js_log debug lines. Not much luck with this regExp yet:
		if( !$this->debug )
			 $str = preg_replace('/\n\s*js_log\(([^\)]*\))*\s*[\;\n]/U', "\n", $str);

		// Do language swap by index:
		if ( $wgEnableScriptLocalization ){
			$inx = self::getLoadGmIndex( $str );
			if($inx){
				$translated = $this->languageMsgReplace( substr($str, $inx['s'], ($inx['e']-$inx['s']) ));
				//return the final string (without double {})
				return substr($str, 0, $inx['s']-1) . $translated . substr($str, $inx['e']+1);
			}
		}
		//return the js str unmodified if we did not transform with the localisation.
		return $str;
	}
	static public function getLoadGmIndex( $str ){
		$returnIndex = array();
		preg_match('/loadGM\s*\(\s*\{/', $str, $matches, PREG_OFFSET_CAPTURE );
		if( count($matches) == 0){
			return false;
		}
		if( count( $matches ) > 0 ){
			//offset + match str length gives startIndex:
			$returnIndex['s'] = strlen( $matches[0][0] ) + $matches[0][1];
			$foundMatch = true;
		}
		$ignorenext = false;
		$inquote = false;
		//look for closing } not inside quotes::
		for ( $i = $returnIndex['s']; $i < strlen( $str ); $i++ ) {
			$char = $str[$i];
			if ( $ignorenext ) {
				$ignorenext = false;
			} else {
				//search for a close } that is not in quotes or escaped
				switch( $char ) {
					case '"':
						$inquote = !$inquote;
						break;
					case '}':
						if( ! $inquote){
							$returnIndex['e'] =$i;
							return $returnIndex;
						}
						break;
					case '\\':
						if ( $inquote ) $ignorenext = true;
						break;
				}
			}
		}
	}

	function getInlineLoadGMFromClass( $class ){
		$jsmsg = $this->getMsgKeysFromClass( $class );
		if( $jsmsg ){
			self::getMsgKeys ( $jsmsg );
			return 'loadGM(' . FormatJson::encode( $jsmsg ) . ');';
		}else{
			//if could not parse return empty string:
			return '';
		}
	}
	function getMsgKeysFromClass( $class ){
		$file_path = self::getJsPathFromClass( $class );
		$str = $this->getScriptText($class,  $file_path);

		$inx = self::getLoadGmIndex( $str );
		if(!$inx)
		return '';
		return FormatJson::decode( '{' . substr($str, $inx['s'], ($inx['e']-$inx['s'])) . '}', true);
	}

	static public function getMsgKeys(& $jmsg, $langCode = false){
		global $wgContLanguageCode;
		if(!$langCode)
		$langCode = $wgContLanguageCode;
		//get the msg keys for the a json array
		foreach ( $jmsg as $msgKey => $default_en_value ) {
			$jmsg[$msgKey] = wfMsgGetKey( $msgKey, true, $langCode, false );
		}
	}
	function languageMsgReplace( $json_str ) {
		$jmsg = FormatJson::decode( '{' . $json_str . '}', true );

		// Do the language lookup
		if ( $jmsg ) {
			//see if any msgKey has the PLURAL template tag
			//package in PLURAL mapping
			self::getMsgKeys($jmsg, $this->langCode);

			// Return the updated loadGM JSON with updated msgs:
			return FormatJson::encode( $jmsg );
		} else {
			// Could not parse JSON return error: (maybe a alert?)
			//we just make a note in the code, visitors will get the fallback language,
			//developers will read the js source when its not behaving as expected.
			return "\n/*
* Could not parse JSON language messages in this file,
* Please check that loadGM call contains valid JSON (not javascript)
*/\n\n" . $json_str; //include the original fallback loadGM

		}
	}
}

// A simple version of HTMLFileCache (@@todo abstract shared pieces)
class simpleFileCache {
	var $mFileCache;
	var $filename = null;
	var $rKey = null;

	public function __construct( &$rKey ) {
		$this->rKey = $rKey;
		$this->getCacheFileName();
	}

	public function getCacheFileName() {
		global $wgUseGzip, $wgScriptCacheDirectory;

		$hash = md5( $this->rKey );
		# Avoid extension confusion
		$key = str_replace( '.', '%2E', urlencode( $this->rKey ) );

		$hash1 = substr( $hash, 0, 1 );
		$hash2 = substr( $hash, 0, 2 );
		$this->filename = "{$wgScriptCacheDirectory}/{$hash1}/{$hash2}/{$this->rKey}.js";

		// Check for defined files::
		if( is_file( $this->filename ) )
		return $this->filename;

		if( is_file(  $this->filename .'.gz') ){
			$this->filename.='.gz';
			return $this->filename;
		}
		//check the update the name based on the $wgUseGzip config var
		if ( isset($wgUseGzip) && $wgUseGzip )
		$this->filename.='.gz';
	}

	public function isFileCached() {
		return file_exists( $this->filename );
	}

	public function outputFromFileCache() {
		if ( $this->clientAcceptsGzip() && substr( $this->filename, - 3 ) == '.gz'  ) {
			header( 'Content-Encoding: gzip' );
			readfile( $this->filename );
			return true;
		}
		// Output without gzip:
		if ( substr( $this->filename, - 3 ) == '.gz' ) {
			readgzfile( $this->filename );
		} else {
			readfile( $this->filename );
		}
		return true;
	}
	public function clientAcceptsGzip(){
		$m = array();
		if ( preg_match(
			'/\bgzip(?:;(q)=([0-9]+(?:\.[0-9]+)))?\b/',
		$_SERVER['HTTP_ACCEPT_ENCODING'],
		$m ) ) {
			if ( isset( $m[2] ) && ( $m[1] == 'q' ) && ( $m[2] == 0 ) )
			return false;

			return true;
		}
		return false;
	}
	public function saveToFileCache( &$text ) {
		global $wgUseFileCache, $wgUseGzip;
		if ( !$wgUseFileCache ) {
			return 'Error: Called saveToFileCache with $wgUseFileCache off';
		}
		if ( strcmp( $text, '' ) == 0 )
		return 'saveToFileCache: empty output file';

		if ( $wgUseGzip ) {
			$outputText = gzencode( trim( $text ) );
		} else {
			$outputText = trim( $text );
		}

		// Check the directories. If we could not create them, error out.
		$status = $this->checkCacheDirs();

		if ( $status !== true )
		return $status;
		$f = fopen( $this->filename, 'w' );
		if ( $f ) {
			fwrite( $f, $outputText );
			fclose( $f );
		} else {
			return 'Could not open file for writing. Check your cache directory permissions?';
		}
		return true;
	}

	protected function checkCacheDirs() {
		$mydir2 = substr( $this->filename, 0, strrpos( $this->filename, '/' ) ); # subdirectory level 2
		$mydir1 = substr( $mydir2, 0, strrpos( $mydir2, '/' ) ); # subdirectory level 1

		if ( wfMkdirParents( $mydir1 ) === false || wfMkdirParents( $mydir2 ) === false ) {
			return 'Could not create cache directory. Check your cache directory permissions?';
		} else {
			return true;
		}
	}
}
