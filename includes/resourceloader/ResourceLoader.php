<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @author Roan Kattouw
 * @author Trevor Parscal
 */

/**
 * Dynamic JavaScript and CSS resource loading system.
 *
 * Most of the documention is on the MediaWiki documentation wiki starting at:
 *    http://www.mediawiki.org/wiki/ResourceLoader
 */
class ResourceLoader {

	/* Protected Static Members */
	protected static $filterCacheVersion = 7;
	protected static $requiredSourceProperties = array( 'loadScript' );

	/** Array: List of module name/ResourceLoaderModule object pairs */
	protected $modules = array();

	/** Associative array mapping module name to info associative array */
	protected $moduleInfos = array();
 
	/** Associative array mapping framework ids to a list of names of test suite modules */
	/** like array( 'qunit' => array( 'mediawiki.tests.qunit.suites', 'ext.foo.tests', .. ), .. ) */
	protected $testModuleNames = array();

	/** array( 'source-id' => array( 'loadScript' => 'http://.../load.php' ) ) **/
	protected $sources = array();

	/* Protected Methods */

	/**
	 * Loads information stored in the database about modules.
	 *
	 * This method grabs modules dependencies from the database and updates modules
	 * objects.
	 *
	 * This is not inside the module code because it is much faster to
	 * request all of the information at once than it is to have each module
	 * requests its own information. This sacrifice of modularity yields a substantial
	 * performance improvement.
	 *
	 * @param $modules Array: List of module names to preload information for
	 * @param $context ResourceLoaderContext: Context to load the information within
	 */
	public function preloadModuleInfo( array $modules, ResourceLoaderContext $context ) {
		if ( !count( $modules ) ) {
			return; // or else Database*::select() will explode, plus it's cheaper!
		}
		$dbr = wfGetDB( DB_SLAVE );
		$skin = $context->getSkin();
		$lang = $context->getLanguage();

		// Get file dependency information
		$res = $dbr->select( 'module_deps', array( 'md_module', 'md_deps' ), array(
				'md_module' => $modules,
				'md_skin' => $skin
			), __METHOD__
		);

		// Set modules' dependencies
		$modulesWithDeps = array();
		foreach ( $res as $row ) {
			$this->getModule( $row->md_module )->setFileDependencies( $skin,
				FormatJson::decode( $row->md_deps, true )
			);
			$modulesWithDeps[] = $row->md_module;
		}

		// Register the absence of a dependency row too
		foreach ( array_diff( $modules, $modulesWithDeps ) as $name ) {
			$this->getModule( $name )->setFileDependencies( $skin, array() );
		}

		// Get message blob mtimes. Only do this for modules with messages
		$modulesWithMessages = array();
		foreach ( $modules as $name ) {
			if ( count( $this->getModule( $name )->getMessages() ) ) {
				$modulesWithMessages[] = $name;
			}
		}
		$modulesWithoutMessages = array_flip( $modules ); // Will be trimmed down by the loop below
		if ( count( $modulesWithMessages ) ) {
			$res = $dbr->select( 'msg_resource', array( 'mr_resource', 'mr_timestamp' ), array(
					'mr_resource' => $modulesWithMessages,
					'mr_lang' => $lang
				), __METHOD__
			);
			foreach ( $res as $row ) {
				$this->getModule( $row->mr_resource )->setMsgBlobMtime( $lang,
					wfTimestamp( TS_UNIX, $row->mr_timestamp ) );
				unset( $modulesWithoutMessages[$row->mr_resource] );
			}
		}
		foreach ( array_keys( $modulesWithoutMessages ) as $name ) {
			$this->getModule( $name )->setMsgBlobMtime( $lang, 0 );
		}
	}

	/**
	 * Runs JavaScript or CSS data through a filter, caching the filtered result for future calls.
	 *
	 * Available filters are:
	 *  - minify-js \see JavaScriptMinifier::minify
	 *  - minify-css \see CSSMin::minify
	 *
	 * If $data is empty, only contains whitespace or the filter was unknown,
	 * $data is returned unmodified.
	 *
	 * @param $filter String: Name of filter to run
	 * @param $data String: Text to filter, such as JavaScript or CSS text
	 * @return String: Filtered data, or a comment containing an error message
	 */
	protected function filter( $filter, $data ) {
		global $wgResourceLoaderMinifierStatementsOnOwnLine, $wgResourceLoaderMinifierMaxLineLength;
		wfProfileIn( __METHOD__ );

		// For empty/whitespace-only data or for unknown filters, don't perform
		// any caching or processing
		if ( trim( $data ) === ''
			|| !in_array( $filter, array( 'minify-js', 'minify-css' ) ) )
		{
			wfProfileOut( __METHOD__ );
			return $data;
		}

		// Try for cache hit
		// Use CACHE_ANYTHING since filtering is very slow compared to DB queries
		$key = wfMemcKey( 'resourceloader', 'filter', $filter, self::$filterCacheVersion, md5( $data ) );
		$cache = wfGetCache( CACHE_ANYTHING );
		$cacheEntry = $cache->get( $key );
		if ( is_string( $cacheEntry ) ) {
			wfProfileOut( __METHOD__ );
			return $cacheEntry;
		}

		$result = '';
		// Run the filter - we've already verified one of these will work
		try {
			switch ( $filter ) {
				case 'minify-js':
					$result = JavaScriptMinifier::minify( $data,
						$wgResourceLoaderMinifierStatementsOnOwnLine,
						$wgResourceLoaderMinifierMaxLineLength
					);
					$result .= "\n\n/* cache key: $key */\n";
					break;
				case 'minify-css':
					$result = CSSMin::minify( $data );
					$result .= "\n\n/* cache key: $key */\n";
					break;
			}

			// Save filtered text to Memcached
			$cache->set( $key, $result );
		} catch ( Exception $exception ) {
			// Return exception as a comment
			$result = $this->makeComment( $exception->__toString() );
		}

		wfProfileOut( __METHOD__ );

		return $result;
	}

	/* Methods */

	/**
	 * Registers core modules and runs registration hooks.
	 */
	public function __construct() {
		global $IP, $wgResourceModules, $wgResourceLoaderSources, $wgLoadScript, $wgEnableJavaScriptTest;

		wfProfileIn( __METHOD__ );

		// Add 'local' source first
		$this->addSource( 'local', array( 'loadScript' => $wgLoadScript, 'apiScript' => wfScript( 'api' ) ) );

		// Add other sources
		$this->addSource( $wgResourceLoaderSources );

		// Register core modules
		$this->register( include( "$IP/resources/Resources.php" ) );
		// Register extension modules
		wfRunHooks( 'ResourceLoaderRegisterModules', array( &$this ) );
		$this->register( $wgResourceModules );

		if ( $wgEnableJavaScriptTest === true ) {
			$this->registerTestModules();
		}


		wfProfileOut( __METHOD__ );
	}

	/**
	 * Registers a module with the ResourceLoader system.
	 *
	 * @param $name Mixed: Name of module as a string or List of name/object pairs as an array
	 * @param $info Module info array. For backwards compatibility with 1.17alpha,
	 *   this may also be a ResourceLoaderModule object. Optional when using
	 *   multiple-registration calling style.
	 * @throws MWException: If a duplicate module registration is attempted
	 * @throws MWException: If a module name contains illegal characters (pipes or commas)
	 * @throws MWException: If something other than a ResourceLoaderModule is being registered
	 * @return Boolean: False if there were any errors, in which case one or more modules were not
	 *     registered
	 */
	public function register( $name, $info = null ) {
		wfProfileIn( __METHOD__ );

		// Allow multiple modules to be registered in one call
		$registrations = is_array( $name ) ? $name : array( $name => $info );
		foreach ( $registrations as $name => $info ) {
			// Disallow duplicate registrations
			if ( isset( $this->moduleInfos[$name] ) ) {
				// A module has already been registered by this name
				throw new MWException(
					'ResourceLoader duplicate registration error. ' .
					'Another module has already been registered as ' . $name
				);
			}

			// Check $name for illegal characters
			if ( preg_match( '/[|,!]/', $name ) ) {
				throw new MWException( "ResourceLoader module name '$name' is invalid. Names may not contain pipes (|), commas (,) or exclamation marks (!)" );
			}

			// Attach module
			if ( is_object( $info ) ) {
				// Old calling convention
				// Validate the input
				if ( !( $info instanceof ResourceLoaderModule ) ) {
					throw new MWException( 'ResourceLoader invalid module error. ' .
						'Instances of ResourceLoaderModule expected.' );
				}

				$this->moduleInfos[$name] = array( 'object' => $info );
				$info->setName( $name );
				$this->modules[$name] = $info;
			} else {
				// New calling convention
				$this->moduleInfos[$name] = $info;
			}
		}

		wfProfileOut( __METHOD__ );
	}

	/**
	 */
	public function registerTestModules() {
		global $IP, $wgEnableJavaScriptTest;

		if ( $wgEnableJavaScriptTest !== true ) {
			throw new MWException( 'Attempt to register JavaScript test modules but <tt>$wgEnableJavaScriptTest</tt> is false. Edit your <tt>LocalSettings.php</tt> to enable it.' );
		}

		wfProfileIn( __METHOD__ );

		// Get core test suites
		$testModules = array();
		$testModules['qunit'] = include( "$IP/tests/qunit/QUnitTestResources.php" );
		// Get other test suites (e.g. from extensions)
		wfRunHooks( 'ResourceLoaderTestModules', array( &$testModules, &$this ) );

		// Add the testrunner (which configures QUnit) to the dependencies.
		// Since it must be ready before any of the test suites are executed.
		foreach( $testModules['qunit'] as $moduleName => $moduleProps ) {
			$testModules['qunit'][$moduleName]['dependencies'][] = 'mediawiki.tests.qunit.testrunner';
		}

		foreach( $testModules as $id => $names ) {
			// Register test modules
			$this->register( $testModules[$id] );

			// Keep track of their names so that they can be loaded together
			$this->testModuleNames[$id] = array_keys( $testModules[$id] );
		}

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Add a foreign source of modules.
	 *
	 * Source properties:
	 * 'loadScript': URL (either fully-qualified or protocol-relative) of load.php for this source
	 *
	 * @param $id Mixed: source ID (string), or array( id1 => props1, id2 => props2, ... )
	 * @param $properties Array: source properties
	 */
	public function addSource( $id, $properties = null) {
		// Allow multiple sources to be registered in one call
		if ( is_array( $id ) ) {
			foreach ( $id as $key => $value ) {
				$this->addSource( $key, $value );
			}
			return;
		}

		// Disallow duplicates
		if ( isset( $this->sources[$id] ) ) {
			throw new MWException(
				'ResourceLoader duplicate source addition error. ' .
				'Another source has already been registered as ' . $id
			);
		}

		// Validate properties
		foreach ( self::$requiredSourceProperties as $prop ) {
			if ( !isset( $properties[$prop] ) ) {
				throw new MWException( "Required property $prop missing from source ID $id" );
			}
		}

		$this->sources[$id] = $properties;
	}

	/**
	 * Get a list of module names
	 *
	 * @return Array: List of module names
	 */
	public function getModuleNames() {
		return array_keys( $this->moduleInfos );
	}
 
 	/**
	 * Get a list of test module names for one (or all) frameworks.
	 * If the given framework id is unknkown, or if the in-object variable is not an array,
	 * then it will return an empty array.
	 *
	 * @param $framework String: Optional. Get only the test module names for one
	 * particular framework.
	 * @return Array
	 */
	public function getTestModuleNames( $framework = 'all' ) {
		if ( $framework == 'all' ) {
			return $this->testModuleNames;
		} elseif ( isset( $this->testModuleNames[$framework] ) && is_array( $this->testModuleNames[$framework] ) ) {
			return $this->testModuleNames[$framework];
		} else {
			return array();
		}
	}

	/**
	 * Get the ResourceLoaderModule object for a given module name.
	 *
	 * @param $name String: Module name
	 * @return ResourceLoaderModule if module has been registered, null otherwise
	 */
	public function getModule( $name ) {
		if ( !isset( $this->modules[$name] ) ) {
			if ( !isset( $this->moduleInfos[$name] ) ) {
				// No such module
				return null;
			}
			// Construct the requested object
			$info = $this->moduleInfos[$name];
			if ( isset( $info['object'] ) ) {
				// Object given in info array
				$object = $info['object'];
			} else {
				if ( !isset( $info['class'] ) ) {
					$class = 'ResourceLoaderFileModule';
				} else {
					$class = $info['class'];
				}
				$object = new $class( $info );
			}
			$object->setName( $name );
			$this->modules[$name] = $object;
		}

		return $this->modules[$name];
	}

	/**
	 * Get the list of sources
	 *
	 * @return Array: array( id => array of properties, .. )
	 */
	public function getSources() {
		return $this->sources;
	}

	/**
	 * Outputs a response to a resource load-request, including a content-type header.
	 *
	 * @param $context ResourceLoaderContext: Context in which a response should be formed
	 */
	public function respond( ResourceLoaderContext $context ) {
		global $wgCacheEpoch, $wgUseFileCache;

		// Use file cache if enabled and available...
		if ( $wgUseFileCache ) {
			$fileCache = ResourceFileCache::newFromContext( $context );
			if ( $this->tryRespondFromFileCache( $fileCache, $context ) ) {
				return; // output handled
			}
		}

		// Buffer output to catch warnings. Normally we'd use ob_clean() on the
		// top-level output buffer to clear warnings, but that breaks when ob_gzhandler
		// is used: ob_clean() will clear the GZIP header in that case and it won't come
		// back for subsequent output, resulting in invalid GZIP. So we have to wrap
		// the whole thing in our own output buffer to be sure the active buffer
		// doesn't use ob_gzhandler.
		// See http://bugs.php.net/bug.php?id=36514
		ob_start();

		wfProfileIn( __METHOD__ );
		$errors = '';

		// Split requested modules into two groups, modules and missing
		$modules = array();
		$missing = array();
		foreach ( $context->getModules() as $name ) {
			if ( isset( $this->moduleInfos[$name] ) ) {
				$module = $this->getModule( $name );
				// Do not allow private modules to be loaded from the web.
				// This is a security issue, see bug 34907.
				if ( $module->getGroup() === 'private' ) {
					$errors .= $this->makeComment( "Cannot show private module \"$name\"" );
					continue;
				}
				$modules[$name] = $this->getModule( $name );
			} else {
				$missing[] = $name;
			}
		}

		// Preload information needed to the mtime calculation below
		try {
			$this->preloadModuleInfo( array_keys( $modules ), $context );
		} catch( Exception $e ) {
			// Add exception to the output as a comment
			$errors .= $this->makeComment( $e->__toString() );
		}

		wfProfileIn( __METHOD__.'-getModifiedTime' );

		// To send Last-Modified and support If-Modified-Since, we need to detect
		// the last modified time
		$mtime = wfTimestamp( TS_UNIX, $wgCacheEpoch );
		foreach ( $modules as $module ) {
			/**
			 * @var $module ResourceLoaderModule
			 */
			try {
				// Calculate maximum modified time
				$mtime = max( $mtime, $module->getModifiedTime( $context ) );
			} catch ( Exception $e ) {
				// Add exception to the output as a comment
				$errors .= $this->makeComment( $e->__toString() );
			}
		}

		wfProfileOut( __METHOD__.'-getModifiedTime' );

		// Send content type and cache related headers
		$this->sendResponseHeaders( $context, $mtime );

		// If there's an If-Modified-Since header, respond with a 304 appropriately
		if ( $this->tryRespondLastModified( $context, $mtime ) ) {
			wfProfileOut( __METHOD__ );
			return; // output handled (buffers cleared)
		}

		// Generate a response
		$response = $this->makeModuleResponse( $context, $modules, $missing );

		// Prepend comments indicating exceptions
		$response = $errors . $response;

		// Capture any PHP warnings from the output buffer and append them to the
		// response in a comment if we're in debug mode.
		if ( $context->getDebug() && strlen( $warnings = ob_get_contents() ) ) {
			$response = $this->makeComment( $warnings ) . $response;
		}

		// Remove the output buffer and output the response
		ob_end_clean();
		echo $response;

		// Save response to file cache unless there are errors
		if ( isset( $fileCache ) && !$errors && !$missing ) {
			// Cache single modules...and other requests if there are enough hits
			if ( ResourceFileCache::useFileCache( $context ) ) {
				if ( $fileCache->isCacheWorthy() ) {
					$fileCache->saveText( $response );
				} else {
					$fileCache->incrMissesRecent( $context->getRequest() );
				}
			}
		}

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Send content type and last modified headers to the client.
	 * @param $context ResourceLoaderContext
	 * @param $mtime string TS_MW timestamp to use for last-modified
	 * @return void
	 */
	protected function sendResponseHeaders( ResourceLoaderContext $context, $mtime ) {
		global $wgResourceLoaderMaxage;
		// If a version wasn't specified we need a shorter expiry time for updates
		// to propagate to clients quickly
		if ( is_null( $context->getVersion() ) ) {
			$maxage  = $wgResourceLoaderMaxage['unversioned']['client'];
			$smaxage = $wgResourceLoaderMaxage['unversioned']['server'];
		// If a version was specified we can use a longer expiry time since changing
		// version numbers causes cache misses
		} else {
			$maxage  = $wgResourceLoaderMaxage['versioned']['client'];
			$smaxage = $wgResourceLoaderMaxage['versioned']['server'];
		}
		if ( $context->getOnly() === 'styles' ) {
			header( 'Content-Type: text/css; charset=utf-8' );
		} else {
			header( 'Content-Type: text/javascript; charset=utf-8' );
		}
		header( 'Last-Modified: ' . wfTimestamp( TS_RFC2822, $mtime ) );
		if ( $context->getDebug() ) {
			// Do not cache debug responses
			header( 'Cache-Control: private, no-cache, must-revalidate' );
			header( 'Pragma: no-cache' );
		} else {
			header( "Cache-Control: public, max-age=$maxage, s-maxage=$smaxage" );
			$exp = min( $maxage, $smaxage );
			header( 'Expires: ' . wfTimestamp( TS_RFC2822, $exp + time() ) );
		}
	}

	/**
	 * If there's an If-Modified-Since header, respond with a 304 appropriately
	 * and clear out the output buffer. If the client cache is too old then do nothing.
	 * @param $context ResourceLoaderContext
	 * @param $mtime string The TS_MW timestamp to check the header against
	 * @return bool True iff 304 header sent and output handled
	 */
	protected function tryRespondLastModified( ResourceLoaderContext $context, $mtime ) {
		// If there's an If-Modified-Since header, respond with a 304 appropriately
		// Some clients send "timestamp;length=123". Strip the part after the first ';'
		// so we get a valid timestamp.
		$ims = $context->getRequest()->getHeader( 'If-Modified-Since' );
		// Never send 304s in debug mode
		if ( $ims !== false && !$context->getDebug() ) {
			$imsTS = strtok( $ims, ';' );
			if ( $mtime <= wfTimestamp( TS_UNIX, $imsTS ) ) {
				// There's another bug in ob_gzhandler (see also the comment at
				// the top of this function) that causes it to gzip even empty
				// responses, meaning it's impossible to produce a truly empty
				// response (because the gzip header is always there). This is
				// a problem because 304 responses have to be completely empty
				// per the HTTP spec, and Firefox behaves buggily when they're not.
				// See also http://bugs.php.net/bug.php?id=51579
				// To work around this, we tear down all output buffering before
				// sending the 304.
				// On some setups, ob_get_level() doesn't seem to go down to zero
				// no matter how often we call ob_get_clean(), so instead of doing
				// the more intuitive while ( ob_get_level() > 0 ) ob_get_clean();
				// we have to be safe here and avoid an infinite loop.
				for ( $i = 0; $i < ob_get_level(); $i++ ) {
					ob_end_clean();
				}

				header( 'HTTP/1.0 304 Not Modified' );
				header( 'Status: 304 Not Modified' );
				return true;
			}
		}
		return false;
	}

	/**
	 * Send out code for a response from file cache if possible
	 *
	 * @param $fileCache ObjectFileCache: Cache object for this request URL
	 * @param $context ResourceLoaderContext: Context in which to generate a response
	 * @return bool If this found a cache file and handled the response
	 */
	protected function tryRespondFromFileCache(
		ResourceFileCache $fileCache, ResourceLoaderContext $context
	) {
		global $wgResourceLoaderMaxage;
		// Buffer output to catch warnings.
		ob_start();
		// Get the maximum age the cache can be
		$maxage = is_null( $context->getVersion() )
			? $wgResourceLoaderMaxage['unversioned']['server']
			: $wgResourceLoaderMaxage['versioned']['server'];
		// Minimum timestamp the cache file must have
		$good = $fileCache->isCacheGood( wfTimestamp( TS_MW, time() - $maxage ) );
		if ( !$good ) {
			try { // RL always hits the DB on file cache miss...
				wfGetDB( DB_SLAVE );
			} catch( DBConnectionError $e ) { // ...check if we need to fallback to cache
				$good = $fileCache->isCacheGood(); // cache existence check
			}
		}
		if ( $good ) {
			$ts = $fileCache->cacheTimestamp();
			// Send content type and cache headers
			$this->sendResponseHeaders( $context, $ts, false );
			// If there's an If-Modified-Since header, respond with a 304 appropriately
			if ( $this->tryRespondLastModified( $context, $ts ) ) {
				return false; // output handled (buffers cleared)
			}
			$response = $fileCache->fetchText();
			// Capture any PHP warnings from the output buffer and append them to the
			// response in a comment if we're in debug mode.
			if ( $context->getDebug() && strlen( $warnings = ob_get_contents() ) ) {
				$response = "/*\n$warnings\n*/\n" . $response;
			}
			// Remove the output buffer and output the response
			ob_end_clean();
			echo $response . "\n/* Cached {$ts} */";
			return true; // cache hit
		}
		// Clear buffer
		ob_end_clean();

		return false; // cache miss
	}

	protected function makeComment( $text ) {
		$encText = str_replace( '*/', '* /', $text );
		return "/*\n$encText\n*/\n";
	}

	/**
	 * Generates code for a response
	 *
	 * @param $context ResourceLoaderContext: Context in which to generate a response
	 * @param $modules Array: List of module objects keyed by module name
	 * @param $missing Array: List of unavailable modules (optional)
	 * @return String: Response data
	 */
	public function makeModuleResponse( ResourceLoaderContext $context,
		array $modules, $missing = array() )
	{
		$out = '';
		$exceptions = '';
		if ( $modules === array() && $missing === array() ) {
			return '/* No modules requested. Max made me put this here */';
		}

		wfProfileIn( __METHOD__ );
		// Pre-fetch blobs
		if ( $context->shouldIncludeMessages() ) {
			try {
				$blobs = MessageBlobStore::get( $this, $modules, $context->getLanguage() );
			} catch ( Exception $e ) {
				// Add exception to the output as a comment
				$exceptions .= $this->makeComment( $e->__toString() );
			}
		} else {
			$blobs = array();
		}

		// Generate output
		foreach ( $modules as $name => $module ) {
			/**
			 * @var $module ResourceLoaderModule
			 */

			wfProfileIn( __METHOD__ . '-' . $name );
			try {
				$scripts = '';
				if ( $context->shouldIncludeScripts() ) {
					// If we are in debug mode, we'll want to return an array of URLs if possible
					// However, we can't do this if the module doesn't support it
					// We also can't do this if there is an only= parameter, because we have to give
					// the module a way to return a load.php URL without causing an infinite loop
					if ( $context->getDebug() && !$context->getOnly() && $module->supportsURLLoading() ) {
						$scripts = $module->getScriptURLsForDebug( $context );
					} else {
						$scripts = $module->getScript( $context );
						if ( is_string( $scripts ) ) {
							// bug 27054: Append semicolon to prevent weird bugs
							// caused by files not terminating their statements right
							$scripts .= ";\n";
						}
					}
				}
				// Styles
				$styles = array();
				if ( $context->shouldIncludeStyles() ) {
					// If we are in debug mode, we'll want to return an array of URLs
					// See comment near shouldIncludeScripts() for more details
					if ( $context->getDebug() && !$context->getOnly() && $module->supportsURLLoading() ) {
						$styles = $module->getStyleURLsForDebug( $context );
					} else {
						$styles = $module->getStyles( $context );
					}
				}

				// Messages
				$messagesBlob = isset( $blobs[$name] ) ? $blobs[$name] : '{}';

				// Append output
				switch ( $context->getOnly() ) {
					case 'scripts':
						if ( is_string( $scripts ) ) {
							// Load scripts raw...
							$out .= $scripts;
						} elseif ( is_array( $scripts ) ) {
							// ...except when $scripts is an array of URLs
							$out .= self::makeLoaderImplementScript( $name, $scripts, array(), array() );
						}
						break;
					case 'styles':
						$out .= self::makeCombinedStyles( $styles );
						break;
					case 'messages':
						$out .= self::makeMessageSetScript( new XmlJsCode( $messagesBlob ) );
						break;
					default:
						// Minify CSS before embedding in mw.loader.implement call
						// (unless in debug mode)
						if ( !$context->getDebug() ) {
							foreach ( $styles as $media => $style ) {
								if ( is_string( $style ) ) {
									$styles[$media] = $this->filter( 'minify-css', $style );
								}
							}
						}
						$out .= self::makeLoaderImplementScript( $name, $scripts, $styles,
							new XmlJsCode( $messagesBlob ) );
						break;
				}
			} catch ( Exception $e ) {
				// Add exception to the output as a comment
				$exceptions .= $this->makeComment( $e->__toString() );

				// Register module as missing
				$missing[] = $name;
				unset( $modules[$name] );
			}
			wfProfileOut( __METHOD__ . '-' . $name );
		}

		// Update module states
		if ( $context->shouldIncludeScripts() ) {
			// Set the state of modules loaded as only scripts to ready
			if ( count( $modules ) && $context->getOnly() === 'scripts'
				&& !isset( $modules['startup'] ) )
			{
				$out .= self::makeLoaderStateScript(
					array_fill_keys( array_keys( $modules ), 'ready' ) );
			}
			// Set the state of modules which were requested but unavailable as missing
			if ( is_array( $missing ) && count( $missing ) ) {
				$out .= self::makeLoaderStateScript( array_fill_keys( $missing, 'missing' ) );
			}
		}

		if ( !$context->getDebug() ) {
			if ( $context->getOnly() === 'styles' ) {
				$out = $this->filter( 'minify-css', $out );
			} else {
				$out = $this->filter( 'minify-js', $out );
			}
		}

		wfProfileOut( __METHOD__ );
		return $exceptions . $out;
	}

	/* Static Methods */

	/**
	 * Returns JS code to call to mw.loader.implement for a module with
	 * given properties.
	 *
	 * @param $name Module name
	 * @param $scripts Mixed: List of URLs to JavaScript files or String of JavaScript code
	 * @param $styles Mixed: List of CSS strings keyed by media type, or list of lists of URLs to
	 * CSS files keyed by media type
	 * @param $messages Mixed: List of messages associated with this module. May either be an
	 *     associative array mapping message key to value, or a JSON-encoded message blob containing
	 *     the same data, wrapped in an XmlJsCode object.
	 *
	 * @return string
	 */
	public static function makeLoaderImplementScript( $name, $scripts, $styles, $messages ) {
		if ( is_string( $scripts ) ) {
			$scripts = new XmlJsCode( "function( $ ) {{$scripts}}" );
		} elseif ( !is_array( $scripts ) ) {
			throw new MWException( 'Invalid scripts error. Array of URLs or string of code expected.' );
		}
		return Xml::encodeJsCall(
			'mw.loader.implement',
			array(
				$name,
				$scripts,
				(object)$styles,
				(object)$messages
			) );
	}

	/**
	 * Returns JS code which, when called, will register a given list of messages.
	 *
	 * @param $messages Mixed: Either an associative array mapping message key to value, or a
	 *     JSON-encoded message blob containing the same data, wrapped in an XmlJsCode object.
	 *
	 * @return string
	 */
	public static function makeMessageSetScript( $messages ) {
		return Xml::encodeJsCall( 'mw.messages.set', array( (object)$messages ) );
	}

	/**
	 * Combines an associative array mapping media type to CSS into a
	 * single stylesheet with @media blocks.
	 *
	 * @param $styles Array: List of CSS strings keyed by media type
	 *
	 * @return string
	 */
	public static function makeCombinedStyles( array $styles ) {
		$out = '';
		foreach ( $styles as $media => $style ) {
			// Transform the media type based on request params and config
			// The way that this relies on $wgRequest to propagate request params is slightly evil
			$media = OutputPage::transformCssMedia( $media );

			if ( $media === null ) {
				// Skip
			} elseif ( $media === '' || $media == 'all' ) {
				// Don't output invalid or frivolous @media statements
				$out .= "$style\n";
			} else {
				$out .= "@media $media {\n" . str_replace( "\n", "\n\t", "\t" . $style ) . "\n}\n";
			}
		}
		return $out;
	}

	/**
	 * Returns a JS call to mw.loader.state, which sets the state of a
	 * module or modules to a given value. Has two calling conventions:
	 *
	 *    - ResourceLoader::makeLoaderStateScript( $name, $state ):
	 *         Set the state of a single module called $name to $state
	 *
	 *    - ResourceLoader::makeLoaderStateScript( array( $name => $state, ... ) ):
	 *         Set the state of modules with the given names to the given states
	 *
	 * @param $name string
	 * @param $state
	 *
	 * @return string
	 */
	public static function makeLoaderStateScript( $name, $state = null ) {
		if ( is_array( $name ) ) {
			return Xml::encodeJsCall( 'mw.loader.state', array( $name ) );
		} else {
			return Xml::encodeJsCall( 'mw.loader.state', array( $name, $state ) );
		}
	}

	/**
	 * Returns JS code which calls the script given by $script. The script will
	 * be called with local variables name, version, dependencies and group,
	 * which will have values corresponding to $name, $version, $dependencies
	 * and $group as supplied.
	 *
	 * @param $name String: Module name
	 * @param $version Integer: Module version number as a timestamp
	 * @param $dependencies Array: List of module names on which this module depends
	 * @param $group String: Group which the module is in.
	 * @param $source String: Source of the module, or 'local' if not foreign.
	 * @param $script String: JavaScript code
	 *
	 * @return string
	 */
	public static function makeCustomLoaderScript( $name, $version, $dependencies, $group, $source, $script ) {
		$script = str_replace( "\n", "\n\t", trim( $script ) );
		return Xml::encodeJsCall(
			"( function( name, version, dependencies, group, source ) {\n\t$script\n} )",
			array( $name, $version, $dependencies, $group, $source ) );
	}

	/**
	 * Returns JS code which calls mw.loader.register with the given
	 * parameters. Has three calling conventions:
	 *
	 *   - ResourceLoader::makeLoaderRegisterScript( $name, $version, $dependencies, $group, $source ):
	 *       Register a single module.
	 *
	 *   - ResourceLoader::makeLoaderRegisterScript( array( $name1, $name2 ) ):
	 *       Register modules with the given names.
	 *
	 *   - ResourceLoader::makeLoaderRegisterScript( array(
	 *        array( $name1, $version1, $dependencies1, $group1, $source1 ),
	 *        array( $name2, $version2, $dependencies1, $group2, $source2 ),
	 *        ...
	 *     ) ):
	 *        Registers modules with the given names and parameters.
	 *
	 * @param $name String: Module name
	 * @param $version Integer: Module version number as a timestamp
	 * @param $dependencies Array: List of module names on which this module depends
	 * @param $group String: group which the module is in.
	 * @param $source String: source of the module, or 'local' if not foreign
	 *
	 * @return string
	 */
	public static function makeLoaderRegisterScript( $name, $version = null,
		$dependencies = null, $group = null, $source = null )
	{
		if ( is_array( $name ) ) {
			return Xml::encodeJsCall( 'mw.loader.register', array( $name ) );
		} else {
			$version = (int) $version > 1 ? (int) $version : 1;
			return Xml::encodeJsCall( 'mw.loader.register',
				array( $name, $version, $dependencies, $group, $source ) );
		}
	}

	/**
	 * Returns JS code which calls mw.loader.addSource() with the given
	 * parameters. Has two calling conventions:
	 *
	 *   - ResourceLoader::makeLoaderSourcesScript( $id, $properties ):
	 *       Register a single source
	 *
	 *   - ResourceLoader::makeLoaderSourcesScript( array( $id1 => $props1, $id2 => $props2, ... ) );
	 *       Register sources with the given IDs and properties.
	 *
	 * @param $id String: source ID
	 * @param $properties Array: source properties (see addSource())
	 *
	 * @return string
	 */
	public static function makeLoaderSourcesScript( $id, $properties = null ) {
		if ( is_array( $id ) ) {
			return Xml::encodeJsCall( 'mw.loader.addSource', array( $id ) );
		} else {
			return Xml::encodeJsCall( 'mw.loader.addSource', array( $id, $properties ) );
		}
	}

	/**
	 * Returns JS code which runs given JS code if the client-side framework is
	 * present.
	 *
	 * @param $script String: JavaScript code
	 *
	 * @return string
	 */
	public static function makeLoaderConditionalScript( $script ) {
		return "if(window.mw){\n".trim( $script )."\n}";
	}

	/**
	 * Returns JS code which will set the MediaWiki configuration array to
	 * the given value.
	 *
	 * @param $configuration Array: List of configuration values keyed by variable name
	 *
	 * @return string
	 */
	public static function makeConfigSetScript( array $configuration ) {
		return Xml::encodeJsCall( 'mw.config.set', array( $configuration ) );
	}

	/**
	 * Convert an array of module names to a packed query string.
	 *
	 * For example, array( 'foo.bar', 'foo.baz', 'bar.baz', 'bar.quux' )
	 * becomes 'foo.bar,baz|bar.baz,quux'
	 * @param $modules array of module names (strings)
	 * @return string Packed query string
	 */
	public static function makePackedModulesString( $modules ) {
		$groups = array(); // array( prefix => array( suffixes ) )
		foreach ( $modules as $module ) {
			$pos = strrpos( $module, '.' );
			$prefix = $pos === false ? '' : substr( $module, 0, $pos );
			$suffix = $pos === false ? $module : substr( $module, $pos + 1 );
			$groups[$prefix][] = $suffix;
		}

		$arr = array();
		foreach ( $groups as $prefix => $suffixes ) {
			$p = $prefix === '' ? '' : $prefix . '.';
			$arr[] = $p . implode( ',', $suffixes );
		}
		$str = implode( '|', $arr );
		return $str;
	}

	/**
	 * Determine whether debug mode was requested
	 * Order of priority is 1) request param, 2) cookie, 3) $wg setting
	 * @return bool
	 */
	public static function inDebugMode() {
		global $wgRequest, $wgResourceLoaderDebug;
		static $retval = null;
		if ( !is_null( $retval ) ) {
			return $retval;
		}
		return $retval = $wgRequest->getFuzzyBool( 'debug',
			$wgRequest->getCookie( 'resourceLoaderDebug', '', $wgResourceLoaderDebug ) );
	}

	/**
	 * Build a load.php URL
	 * @param $modules array of module names (strings)
	 * @param $lang string Language code
	 * @param $skin string Skin name
	 * @param $user string|null User name. If null, the &user= parameter is omitted
	 * @param $version string|null Versioning timestamp
	 * @param $debug bool Whether the request should be in debug mode
	 * @param $only string|null &only= parameter
	 * @param $printable bool Printable mode
	 * @param $handheld bool Handheld mode
	 * @param $extraQuery array Extra query parameters to add
	 * @return string URL to load.php. May be protocol-relative (if $wgLoadScript is procol-relative)
	 */
	public static function makeLoaderURL( $modules, $lang, $skin, $user = null, $version = null, $debug = false, $only = null,
			$printable = false, $handheld = false, $extraQuery = array() ) {
		global $wgLoadScript;
		$query = self::makeLoaderQuery( $modules, $lang, $skin, $user, $version, $debug,
			$only, $printable, $handheld, $extraQuery
		);

		// Prevent the IE6 extension check from being triggered (bug 28840)
		// by appending a character that's invalid in Windows extensions ('*')
		return wfExpandUrl( wfAppendQuery( $wgLoadScript, $query ) . '&*', PROTO_RELATIVE );
	}

	/**
	 * Build a query array (array representation of query string) for load.php. Helper
	 * function for makeLoaderURL().
	 * @return array
	 */
	public static function makeLoaderQuery( $modules, $lang, $skin, $user = null, $version = null, $debug = false, $only = null,
			$printable = false, $handheld = false, $extraQuery = array() ) {
		$query = array(
			'modules' => self::makePackedModulesString( $modules ),
			'lang' => $lang,
			'skin' => $skin,
			'debug' => $debug ? 'true' : 'false',
		);
		if ( $user !== null ) {
			$query['user'] = $user;
		}
		if ( $version !== null ) {
			$query['version'] = $version;
		}
		if ( $only !== null ) {
			$query['only'] = $only;
		}
		if ( $printable ) {
			$query['printable'] = 1;
		}
		if ( $handheld ) {
			$query['handheld'] = 1;
		}
		$query += $extraQuery;

		// Make queries uniform in order
		ksort( $query );
		return $query;
	}
}
