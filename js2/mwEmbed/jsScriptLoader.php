<?php
/**
 * This core jsScriptLoader class provides the script loader functionality
 * @file
 */
// check if we are being invoked in MediaWiki context or stand alone usage:
if ( !defined( 'MEDIAWIKI' ) ){
	// load noMediaWiki helper:
	require_once( realpath( dirname( __FILE__ ) ) . '/php/noMediaWikiConfig.php' );

	// run the main action:
	$myScriptLoader = new jsScriptLoader();
	// preset request values via normal $_GET operation:
	$myScriptLoader->doScriptLoader();
} else {
	$wgExtensionMessagesFiles['mwEmbed'] = realpath( dirname( __FILE__ ) ) . '/php/mwEmbed.i18n.php';
}

// setup page output hook
class jsScriptLoader {
	var $jsFileList = array();
	var $jsout = '';
	var $rKey = ''; // the request key
	var $error_msg = '';
	var $debug = false;
	var $jsvarurl = false; // if we should include generated js (special class '-')
	var $doProcReqFlag = true;

	function doScriptLoader(){
		global 	$wgJSAutoloadClasses, $wgJSAutoloadLocalClasses, $wgEnableScriptLoaderJsFile, $IP,
				$wgEnableScriptMinify, $wgUseFileCache;

		// process the request
		$this->procRequestVars();

		// if cache is on and file is present grab it from there:
		if( $wgUseFileCache && !$this->debug ) {
			// setup file cache obj:
			$this->sFileCache = new simpleFileCache( $this->rKey );
			if( $this->sFileCache->isFileCached() ){
				// just output headers so we can use php "efficient" readfile
				$this->outputJsHeaders();
				$this->sFileCache->outputFromFileCache();
				die();
			}
		}

		// setup script loader header info
		$this->jsout .= 'var mwSlScript = "' . htmlspecialchars( $_SERVER['SCRIPT_NAME'] ) . '";' . "\n";
		$this->jsout .= 'var mwSlGenISODate = "' . date( 'c' ) . '";'  ."\n";
		$this->jsout .= 'var mwSlURID = "' . htmlspecialchars( $this->urid ) . '";'  ."\n";
		// Build the output:
		// swap in the appropriate language per js_file
		foreach( $this->jsFileList as $classKey => $file_name ){

			// special case: - title classes:
			if( substr( $classKey, 0, 3 ) == 'WT:' ){
				global $wgUser;
				// get just the tile part:
				$title_block = substr( $classKey, 3 );
				if( $title_block[0] == '-' && strpos( $title_block, '|' ) !== false ){
					// special case of "-" title with skin
					$parts = explode( '|', $title_block );
					$title = array_shift( $parts );
					foreach( $parts as $tparam ){
						list( $key, $val ) = explode( '=', $tparam );
						if( $key == 'useskin' ){
							$skin = $val;
						}
					}
					$sk = $wgUser->getSkin();
					// make sure the skin name is valid
					$skinNames = Skin::getSkinNames();
					// get the lower case skin name (array keys)
					$skinNames = array_keys( $skinNames );
					if( in_array( strtolower( $skin ), $skinNames ) ){
						$this->jsout .= $sk->generateUserJs( $skin ) . "\n";
						// success continue:
						continue;
					}
				} else {
					//make sure the wiki title ends with .js
					if( substr( $title_block, -3 ) != '.js'){
						$this->error_msg .= 'WikiTitle includes should end with .js';
						continue;
					}
					// it's a wikiTitle append the output of the wikitext:
					$t = Title::newFromText( $title_block );
					$a = new Article( $t );
					// only get content if the page is not empty:
					if( $a->getID() !== 0 ){
						$this->jsout .= $a->getContent() . "\n";
					}
					continue;
				}
			}
			//dealing with files::
			//check that the filename ends with .js and does not include ../ traversing
			if( substr( $file_name, -3 ) != '.js'){
				$this->error_msg .= "\nError file name must end with .js: ". htmlspecialchars( $file_name ) . " \n ";
				continue;
			}
			if( strpos($file_name, '../') !== false ){
				$this->error_msg .= "\nError file name must not traverse paths: ". htmlspecialchars( $file_name ) . " \n ";
				continue;
			}

			if( trim( $file_name ) != '' ){
				// if in debug add a comment with the file name:
				if( $this->debug )
					$this->jsout .= "\n/**
* File: ". htmlspecialchars( $file_name ) ."
*/\n";
				$this->jsout .= ( $this->doProccessJsFile( $file_name ) ) . "\n";
			}
		}

		// check if we should minify :
		if( $wgEnableScriptMinify && !$this->debug ){
			// do the minification and output
			$this->jsout = JSMin::minify( $this->jsout);
		}
		// save to the file cache:
		if( $wgUseFileCache && !$this->debug ) {
			$status = $this->sFileCache->saveToFileCache( $this->jsout );
			if( $status !== true )
				$this->error_msg.= $status;
		}
		// check for error msg:
		if( $this->error_msg != ''){
			echo 'alert(\'Error With ScriptLoader.php ::' . str_replace( "\n", '\'+"\n"+'."\n'", $this->error_msg ) . '\');';
			echo trim( $this->jsout );
		} else {
			// all good lets output cache forever headers:
			$this->outputJsWithHeaders();
		}
	}

	function outputJsHeaders(){
		global $wgJsMimeType;
		// output js mime type:
		header( 'Content-type: ' . $wgJsMimeType );
		header( 'Pragma: public' );
		// cache forever:
		// (the point is we never have to revalidate since we should always change the request url based on the svn or article version)
		$one_year = 60*60*24*365;
		header( "Expires: " . gmdate( "D, d M Y H:i:s", time() + $one_year ) . " GM" );
	}

	function outputJsWithHeaders(){
		global $wgUseGzip;
		$this->outputJsHeaders();
		if( $wgUseGzip ) {
			if( wfClientAcceptsGzip() ) {
				header( 'Content-Encoding: gzip' );
				echo gzencode( $this->jsout );
			} else {
				echo $this->jsout;
			}
		} else {
			echo $this->jsout;
		}
	}

	/**
	 * updates the proc Request
	 */
	function procRequestVars(){
		global $wgContLanguageCode, $wgEnableScriptMinify, $wgJSAutoloadClasses,
		$wgJSAutoloadLocalClasses, $wgStyleVersion, $wgEnableScriptLoaderJsFile;

		// set debug flag:
		if( ( isset( $_GET['debug'] ) && $_GET['debug'] == 'true' ) || ( isset( $wgEnableScriptDebug ) && $wgEnableScriptDebug == true ) ){
			$this->debug = true;
		}

		// set the urid: (be sure to escape it as it goes into our js output)
		if( isset( $_GET['urid'] ) && $_GET['urid'] !=''){
			$this->urid = htmlspecialchars( $_GET['urid'] );
		} else {
			// just give it the current style sheet id:
			// @@todo read the svn version number
			$this->urid = $wgStyleVersion;
		}

		$reqClassList = false;
		if( isset( $_GET['class'] ) && $_GET['class'] != '' ){
			$reqClassList = explode( ',', $_GET['class'] );
		}

		// check for the requested classes
		if( $reqClassList ){
			// clean the class list and populate jsFileList
			foreach( $reqClassList as $reqClass ){
				if( trim( $reqClass ) != '' ){
					// check for special case '-' class for user generated js
					if( substr( $reqClass, 0, 3 ) == 'WT:' ){
						$this->jsFileList[$reqClass] = true;
						$this->rKey .= $reqClass;
						$this->jsvarurl = true;
						continue;
					}

					$reqClass = preg_replace("/[^A-Za-z0-9_\-\.]/", '', $reqClass );

					if( isset( $wgJSAutoloadLocalClasses[$reqClass] ) ){
						$this->jsFileList[$reqClass] = $wgJSAutoloadLocalClasses[$reqClass];
						$this->rKey.= $reqClass;
					} else if( isset( $wgJSAutoloadClasses[$reqClass] ) ) {
						$this->jsFileList[$reqClass] = $wgJSAutoloadClasses[$reqClass];
						$this->rKey.= $reqClass;
					} else {
						$this->error_msg.= 'Requested class: ' . htmlspecialchars( $reqClass ) . ' not found' . "\n";
					}
				}
			}
		}

		// check for requested files if enabled:
		if( $wgEnableScriptLoaderJsFile ){
			if( isset( $_GET['files'] ) ){
				$reqFileList = explode( ',', isset( $_GET['files'] ) );
				// clean the file list and populate jsFileList
				foreach( $reqFileList as $reqFile ){
					// no jumping dirs:
					$reqFile = str_replace( '../', '', $reqFile );
					// only allow alphanumeric underscores periods and ending with .js
					$reqFile = ereg_replace( "[^A-Za-z0-9_\-\/\.]", '', $reqFile );
					if( substr( $reqFile, -3 ) == '.js' ){
						// don't add it twice:
						if( !in_array( $reqFile, $jsFileList ) ) {
		 					$this->jsFileList[] = $IP . $reqFile;
		 					$this->rKey.= $reqFile;
		 				}
					} else {
						$this->error_msg.= 'Not valid requsted JavaScript file' . "\n";
					}
				}
			}
		}

		// add the language code to the rKey:
		$this->rKey .= '_' . $wgContLanguageCode;

		// add the unique rid to the rKey
		$this->rKey .= $this->urid;

		// add a min flag:
		if( $wgEnableScriptMinify ){
			$this->rKey.= '_min';
		}
	}

	function doProccessJsFile( $file_name ){
		global $IP, $wgEnableScriptLocalization, $IP;

		// load the file:
		$str = @file_get_contents( "{$IP}/{$file_name}" );

		if( $str === false ){
			// @@todo check php error level (don't want to expose paths if errors are hidden)
			$this->error_msg.= 'Requested File: ' . htmlspecialchars( $file_name ) . ' could not be read' . "\n";
			return '';
		}
		$this->cur_file = $file_name;

		// strip out js_log debug lines not much luck with this regExp yet:
		//if( !$this->debug )
		//	 $str = preg_replace('/\n\s*js_log\s*\([^\)]([^;]|\n])*;/', "\n", $str);

		// do language swap
		if( $wgEnableScriptLocalization )
			$str = preg_replace_callback(
				'/loadGM\s*\(\s*{(.*)}\s*\)\s*/siU',	// @@todo fix: will break down if someone does }) in their msg text
				array( $this, 'languageMsgReplace' ),
				$str
			);

		return $str;
	}

	function languageMsgReplace( $jvar ){
		if( !isset( $jvar[1] ) )
			return;

		$jmsg = json_decode( '{' . $jvar[1] . '}', true );
		// do the language lookup:
		if( $jmsg ){
			foreach( $jmsg as $msgKey => $default_en_value ){
				$jmsg[$msgKey] = wfMsgNoTrans( $msgKey );
			}
			//return the updated loadGM json with fixed new lines:
			return 'loadGM( ' . json_encode( $jmsg ) . ')';
		} else {
			$this->error_msg.= "Could not parse JSON language msg in File:\n" .
								htmlspecialchars ( $this->cur_file ) . "\n";
		}
		// could not parse json (throw error?)
		return $jvar[0];
	}
}

//a simple version of HTMLFileCache (@@todo abstract shared pieces)
class simpleFileCache {
	var $mFileCache;
	var $filename = null;
	var $rKey = null;

	public function __construct( &$rKey ) {
		$this->rKey = $rKey;
		$this->filename = $this->fileCacheName(); // init name
	}

	public function fileCacheName() {
		global $wgUseGzip;
		if( !$this->mFileCache ) {
			global $wgFileCacheDirectory;

			$hash = md5( $this->rKey );
			# Avoid extension confusion
			$key = str_replace( '.', '%2E', urlencode( $this->rKey ) );

			$hash1 = substr( $hash, 0, 1 );
			$hash2 = substr( $hash, 0, 2 );
			$this->mFileCache = "{$wgFileCacheDirectory}/{$hash1}/{$hash2}/{$this->rKey}.js";

			if( $wgUseGzip )
				$this->mFileCache .= '.gz';

			wfDebug( " fileCacheName() - {$this->mFileCache}\n" );
		}
		return $this->mFileCache;
	}

	public function isFileCached() {
		return file_exists( $this->filename );
	}

	public function outputFromFileCache(){
		global $wgUseGzip;
		if( $wgUseGzip ) {
			if( wfClientAcceptsGzip() ) {
				header( 'Content-Encoding: gzip' );
				readfile( $this->filename );
			} else {
				/* Send uncompressed  (check if fileCache is in compressed state (ends with .gz)
				* (unlikely to execute this since $wgUseGzip would have created a new file above.. but just in case:
				*/
				if( substr( $this->filename, -3 ) == '.gz' ){
					readgzfile( $this->filename );
				} else {
					readfile( $this->filename );
				}
			}
		} else {
			// just output the file
			readfile( $this->filename );
		}
		//return true
		return true;
	}

	public function saveToFileCache( &$text ) {
		global $wgUseFileCache, $wgUseGzip;
		if( !$wgUseFileCache ) {
			return 'Error: Called saveToFileCache with $wgUseFileCache off';
		}
		if( strcmp( $text, '' ) == 0 ) return 'saveToFileCache: empty output file';

		// check the directories if we could not create them error out:
		$status = $this->checkCacheDirs();

		if( $wgUseGzip ){
			$outputText = gzencode( trim( $text ) );
		} else {
			$outputText = trim( $text );
		}

		if( $status !== true )
			return $status;
		$f = fopen( $this->filename, 'w' );
		if( $f ) {
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

		if( wfMkdirParents( $mydir1 ) === false || wfMkdirParents( $mydir2 ) === false ){
			return 'Could not create cache directory. Check your cache directory permissions?';
		} else {
			return true;
		}
	}
}