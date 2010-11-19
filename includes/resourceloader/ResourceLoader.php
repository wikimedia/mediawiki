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

	/** Array: List of module name/ResourceLoaderModule object pairs */
	protected $modules = array();

	/* Protected Methods */

	/**
	 * Loads information stored in the database about modules.
	 * 
	 * This method grabs modules dependencies from the database and updates modules 
	 * objects.
	 * 
	 * This is not inside the module code because it's so much more performant to 
	 * request all of the information at once than it is to have each module 
	 * requests its own information. This sacrifice of modularity yields a profound
	 * performance improvement.
	 * 
	 * @param $modules Array: List of module names to preload information for
	 * @param $context ResourceLoaderContext: Context to load the information within
	 */
	protected function preloadModuleInfo( array $modules, ResourceLoaderContext $context ) {
		if ( !count( $modules ) ) {
			return; // or else Database*::select() will explode, plus it's cheaper!
		}
		$dbr = wfGetDB( DB_SLAVE );
		$skin = $context->getSkin();
		$lang = $context->getLanguage();
		
		// Get file dependency information
		$res = $dbr->select( 'module_deps', array( 'md_module', 'md_deps' ), array(
				'md_module' => $modules,
				'md_skin' => $context->getSkin()
			), __METHOD__
		);

		// Set modules' dependecies		
		$modulesWithDeps = array();
		foreach ( $res as $row ) {
			$this->modules[$row->md_module]->setFileDependencies( $skin,
				FormatJson::decode( $row->md_deps, true )
			);
			$modulesWithDeps[] = $row->md_module;
		}

		// Register the absence of a dependency row too
		foreach ( array_diff( $modules, $modulesWithDeps ) as $name ) {
			$this->modules[$name]->setFileDependencies( $skin, array() );
		}
		
		// Get message blob mtimes. Only do this for modules with messages
		$modulesWithMessages = array();
		$modulesWithoutMessages = array();
		foreach ( $modules as $name ) {
			if ( count( $this->modules[$name]->getMessages() ) ) {
				$modulesWithMessages[] = $name;
			} else {
				$modulesWithoutMessages[] = $name;
			}
		}
		if ( count( $modulesWithMessages ) ) {
			$res = $dbr->select( 'msg_resource', array( 'mr_resource', 'mr_timestamp' ), array(
					'mr_resource' => $modulesWithMessages,
					'mr_lang' => $lang
				), __METHOD__
			);
			foreach ( $res as $row ) {
				$this->modules[$row->mr_resource]->setMsgBlobMtime( $lang, $row->mr_timestamp );
			}
		}
		foreach ( $modulesWithoutMessages as $name ) {
			$this->modules[$name]->setMsgBlobMtime( $lang, 0 );
		}
	}

	/**
	 * Runs JavaScript or CSS data through a filter, caching the filtered result for future calls.
	 * 
	 * Available filters are:
	 *  - minify-js \see JSMin::minify
	 *  - minify-css \see CSSMin::minify
	 *  - flip-css \see CSSJanus::transform
	 * 
	 * If $data is empty, only contains whitespace or the filter was unknown, 
	 * $data is returned unmodified.
	 * 
	 * @param $filter String: Name of filter to run
	 * @param $data String: Text to filter, such as JavaScript or CSS text
	 * @return String: Filtered data
	 */
	protected function filter( $filter, $data ) {
		wfProfileIn( __METHOD__ );

		// For empty/whitespace-only data or for unknown filters, don't perform 
		// any caching or processing
		if ( trim( $data ) === '' 
			|| !in_array( $filter, array( 'minify-js', 'minify-css', 'flip-css' ) ) ) 
		{
			wfProfileOut( __METHOD__ );
			return $data;
		}

		// Try for cache hit
		// Use CACHE_ANYTHING since filtering is very slow compared to DB queries
		$key = wfMemcKey( 'resourceloader', 'filter', $filter, md5( $data ) );
		$cache = wfGetCache( CACHE_ANYTHING );
		$cacheEntry = $cache->get( $key );
		if ( is_string( $cacheEntry ) ) {
			wfProfileOut( __METHOD__ );
			return $cacheEntry;
		}

		// Run the filter - we've already verified one of these will work
		try {
			switch ( $filter ) {
				case 'minify-js':
					$result = JSMin::minify( $data );
					break;
				case 'minify-css':
					$result = CSSMin::minify( $data );
					break;
				case 'flip-css':
					$result = CSSJanus::transform( $data, true, false );
					break;
			}
		} catch ( Exception $exception ) {
			throw new MWException( 'ResourceLoader filter error. ' . 
				'Exception was thrown: ' . $exception->getMessage() );
		}

		// Save filtered text to Memcached
		$cache->set( $key, $result );

		wfProfileOut( __METHOD__ );
		
		return $result;
	}

	/* Methods */

	/**
	 * Registers core modules and runs registration hooks.
	 */
	public function __construct() {
		global $IP;
		
		wfProfileIn( __METHOD__ );
		
		// Register core modules
		$this->register( include( "$IP/resources/Resources.php" ) );
		// Register extension modules
		wfRunHooks( 'ResourceLoaderRegisterModules', array( &$this ) );
		
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Registers a module with the ResourceLoader system.
	 * 
	 * @param $name Mixed: Name of module as a string or List of name/object pairs as an array
	 * @param $object ResourceLoaderModule: Module object (optional when using 
	 *     multiple-registration calling style)
	 * @throws MWException: If a duplicate module registration is attempted
	 * @throws MWException: If something other than a ResourceLoaderModule is being registered
	 * @return Boolean: False if there were any errors, in which case one or more modules were not
	 *     registered
	 */
	public function register( $name, ResourceLoaderModule $object = null ) {

		wfProfileIn( __METHOD__ );

		// Allow multiple modules to be registered in one call
		if ( is_array( $name ) && !isset( $object ) ) {
			foreach ( $name as $key => $value ) {
				$this->register( $key, $value );
			}

			wfProfileOut( __METHOD__ );

			return;
		}

		// Disallow duplicate registrations
		if ( isset( $this->modules[$name] ) ) {
			// A module has already been registered by this name
			throw new MWException(
				'ResourceLoader duplicate registration error. ' . 
				'Another module has already been registered as ' . $name
			);
		}

		// Validate the input (type hinting lets null through)
		if ( !( $object instanceof ResourceLoaderModule ) ) {
			throw new MWException( 'ResourceLoader invalid module error. ' . 
				'Instances of ResourceLoaderModule expected.' );
		}

		// Attach module
		$this->modules[$name] = $object;
		$object->setName( $name );
		
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Gets a map of all modules and their options
	 *
	 * @return Array: List of modules keyed by module name
	 */
	public function getModules() {
		return $this->modules;
	}

	/**
	 * Get the ResourceLoaderModule object for a given module name.
	 *
	 * @param $name String: Module name
	 * @return Mixed: ResourceLoaderModule if module has been registered, null otherwise
	 */
	public function getModule( $name ) {
		return isset( $this->modules[$name] ) ? $this->modules[$name] : null;
	}

	/**
	 * Outputs a response to a resource load-request, including a content-type header.
	 *
	 * @param $context ResourceLoaderContext: Context in which a response should be formed
	 */
	public function respond( ResourceLoaderContext $context ) {
		global $wgResourceLoaderMaxage, $wgCacheEpoch;

		wfProfileIn( __METHOD__ );

		// Split requested modules into two groups, modules and missing
		$modules = array();
		$missing = array();
		foreach ( $context->getModules() as $name ) {
			if ( isset( $this->modules[$name] ) ) {
				$modules[$name] = $this->modules[$name];
			} else {
				$missing[] = $name;
			}
		}

		// If a version wasn't specified we need a shorter expiry time for updates 
		// to propagate to clients quickly
		if ( is_null( $context->getVersion() ) ) {
			$maxage  = $wgResourceLoaderMaxage['unversioned']['client'];
			$smaxage = $wgResourceLoaderMaxage['unversioned']['server'];
		}
		// If a version was specified we can use a longer expiry time since changing 
		// version numbers causes cache misses
		else {
			$maxage  = $wgResourceLoaderMaxage['versioned']['client'];
			$smaxage = $wgResourceLoaderMaxage['versioned']['server'];
		}

		// Preload information needed to the mtime calculation below
		$this->preloadModuleInfo( array_keys( $modules ), $context );

		wfProfileIn( __METHOD__.'-getModifiedTime' );

		// To send Last-Modified and support If-Modified-Since, we need to detect 
		// the last modified time
		$mtime = wfTimestamp( TS_UNIX, $wgCacheEpoch );
		foreach ( $modules as $module ) {
			// Bypass squid cache if the request includes any private modules
			if ( $module->getGroup() === 'private' ) {
				$smaxage = 0;
			}
			// Calculate maximum modified time
			$mtime = max( $mtime, $module->getModifiedTime( $context ) );
		}

		wfProfileOut( __METHOD__.'-getModifiedTime' );

		if ( $context->getOnly() === 'styles' ) {
			header( 'Content-Type: text/css' );
		} else {
			header( 'Content-Type: text/javascript' );
		}
		header( 'Last-Modified: ' . wfTimestamp( TS_RFC2822, $mtime ) );
		if ( $context->getDebug() ) {
			header( 'Cache-Control: must-revalidate' );
		} else {
			header( "Cache-Control: public, max-age=$maxage, s-maxage=$smaxage" );
			header( 'Expires: ' . wfTimestamp( TS_RFC2822, min( $maxage, $smaxage ) + time() ) );
		}

		// If there's an If-Modified-Since header, respond with a 304 appropriately
		$ims = $context->getRequest()->getHeader( 'If-Modified-Since' );
		if ( $ims !== false && $mtime <= wfTimestamp( TS_UNIX, $ims ) ) {
			header( 'HTTP/1.0 304 Not Modified' );
			header( 'Status: 304 Not Modified' );
			wfProfileOut( __METHOD__ );
			return;
		}
		
		// Generate a response
		$response = $this->makeModuleResponse( $context, $modules, $missing );

		// Tack on PHP warnings as a comment in debug mode
		if ( $context->getDebug() && strlen( $warnings = ob_get_contents() ) ) {
			$response .= "/*\n$warnings\n*/";
		}

		// Clear any warnings from the buffer
		ob_clean();
		echo $response;

		wfProfileOut( __METHOD__ );
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
		// Pre-fetch blobs
		if ( $context->shouldIncludeMessages() ) {
			$blobs = MessageBlobStore::get( $this, $modules, $context->getLanguage() );
		} else {
			$blobs = array();
		}

		// Generate output
		$out = '';
		foreach ( $modules as $name => $module ) {

			wfProfileIn( __METHOD__ . '-' . $name );

			// Scripts
			$scripts = '';
			if ( $context->shouldIncludeScripts() ) {
				$scripts .= $module->getScript( $context ) . "\n";
			}

			// Styles
			$styles = array();
			if ( $context->shouldIncludeStyles() ) {
				$styles = $module->getStyles( $context );
				// Flip CSS on a per-module basis
				if ( $styles && $this->modules[$name]->getFlip( $context ) ) {
					foreach ( $styles as $media => $style ) {
						$styles[$media] = $this->filter( 'flip-css', $style );
					}
				}
			}

			// Messages
			$messagesBlob = isset( $blobs[$name] ) ? $blobs[$name] : array();

			// Append output
			switch ( $context->getOnly() ) {
				case 'scripts':
					$out .= $scripts;
					break;
				case 'styles':
					$out .= self::makeCombinedStyles( $styles );
					break;
				case 'messages':
					$out .= self::makeMessageSetScript( new XmlJsCode( $messagesBlob ) );
					break;
				default:
					// Minify CSS before embedding in mediaWiki.loader.implement call 
					// (unless in debug mode)
					if ( !$context->getDebug() ) {
						foreach ( $styles as $media => $style ) {
							$styles[$media] = $this->filter( 'minify-css', $style );
						}
					}
					$out .= self::makeLoaderImplementScript( $name, $scripts, $styles, 
						new XmlJsCode( $messagesBlob ) );
					break;
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

		if ( $context->getDebug() ) {
			return $out;
		} else {
			if ( $context->getOnly() === 'styles' ) {
				return $this->filter( 'minify-css', $out );
			} else {
				return $this->filter( 'minify-js', $out );
			}
		}
	}

	/* Static Methods */

	/**
	 * Returns JS code to call to mediaWiki.loader.implement for a module with 
	 * given properties.
	 *
	 * @param $name Module name
	 * @param $scripts Array: List of JavaScript code snippets to be executed after the 
	 *     module is loaded
	 * @param $styles Array: List of CSS strings keyed by media type
	 * @param $messages Mixed: List of messages associated with this module. May either be an 
	 *     associative array mapping message key to value, or a JSON-encoded message blob containing
	 *     the same data, wrapped in an XmlJsCode object.
	 */
	public static function makeLoaderImplementScript( $name, $scripts, $styles, $messages ) {
		if ( is_array( $scripts ) ) {
			$scripts = implode( $scripts, "\n" );
		}
		return Xml::encodeJsCall( 
			'mediaWiki.loader.implement', 
			array(
				$name,
				new XmlJsCode( "function() {{$scripts}}" ),
				(object)$styles,
				(object)$messages
			) );
	}

	/**
	 * Returns JS code which, when called, will register a given list of messages.
	 *
	 * @param $messages Mixed: Either an associative array mapping message key to value, or a
	 *     JSON-encoded message blob containing the same data, wrapped in an XmlJsCode object.
	 */
	public static function makeMessageSetScript( $messages ) {
		return Xml::encodeJsCall( 'mediaWiki.messages.set', array( (object)$messages ) );
	}

	/**
	 * Combines an associative array mapping media type to CSS into a 
	 * single stylesheet with @media blocks.
	 *
	 * @param $styles Array: List of CSS strings keyed by media type
	 */
	public static function makeCombinedStyles( array $styles ) {
		$out = '';
		foreach ( $styles as $media => $style ) {
			$out .= "@media $media {\n" . str_replace( "\n", "\n\t", "\t" . $style ) . "\n}\n";
		}
		return $out;
	}

	/**
	 * Returns a JS call to mediaWiki.loader.state, which sets the state of a 
	 * module or modules to a given value. Has two calling conventions:
	 *
	 *    - ResourceLoader::makeLoaderStateScript( $name, $state ):
	 *         Set the state of a single module called $name to $state
	 *
	 *    - ResourceLoader::makeLoaderStateScript( array( $name => $state, ... ) ):
	 *         Set the state of modules with the given names to the given states
	 */
	public static function makeLoaderStateScript( $name, $state = null ) {
		if ( is_array( $name ) ) {
			return Xml::encodeJsCall( 'mediaWiki.loader.state', array( $name ) );
		} else {
			return Xml::encodeJsCall( 'mediaWiki.loader.state', array( $name, $state ) );
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
	 * @param $script String: JavaScript code
	 */
	public static function makeCustomLoaderScript( $name, $version, $dependencies, $group, $script ) {
		$script = str_replace( "\n", "\n\t", trim( $script ) );
		return Xml::encodeJsCall( 
			"( function( name, version, dependencies, group ) {\n\t$script\n} )",
			array( $name, $version, $dependencies, $group ) );
	}

	/**
	 * Returns JS code which calls mediaWiki.loader.register with the given 
	 * parameters. Has three calling conventions:
	 *
	 *   - ResourceLoader::makeLoaderRegisterScript( $name, $version, $dependencies, $group ):
	 *       Register a single module.
	 *
	 *   - ResourceLoader::makeLoaderRegisterScript( array( $name1, $name2 ) ):
	 *       Register modules with the given names.
	 *
	 *   - ResourceLoader::makeLoaderRegisterScript( array(
	 *        array( $name1, $version1, $dependencies1, $group1 ),
	 *        array( $name2, $version2, $dependencies1, $group2 ),
	 *        ...
	 *     ) ):
	 *        Registers modules with the given names and parameters.
	 *
	 * @param $name String: Module name
	 * @param $version Integer: Module version number as a timestamp
	 * @param $dependencies Array: List of module names on which this module depends
	 * @param $group String: group which the module is in.
	 */
	public static function makeLoaderRegisterScript( $name, $version = null, 
		$dependencies = null, $group = null ) 
	{
		if ( is_array( $name ) ) {
			return Xml::encodeJsCall( 'mediaWiki.loader.register', array( $name ) );
		} else {
			$version = (int) $version > 1 ? (int) $version : 1;
			return Xml::encodeJsCall( 'mediaWiki.loader.register', 
				array( $name, $version, $dependencies, $group ) );
		}
	}

	/**
	 * Returns JS code which runs given JS code if the client-side framework is 
	 * present.
	 *
	 * @param $script String: JavaScript code
	 */
	public static function makeLoaderConditionalScript( $script ) {
		$script = str_replace( "\n", "\n\t", trim( $script ) );
		return "if ( window.mediaWiki ) {\n\t$script\n}\n";
	}

	/**
	 * Returns JS code which will set the MediaWiki configuration array to 
	 * the given value.
	 *
	 * @param $configuration Array: List of configuration values keyed by variable name
	 */
	public static function makeConfigSetScript( array $configuration ) {
		return Xml::encodeJsCall( 'mediaWiki.config.set', array( $configuration ) );
	}
	
	/**
	 * Determine whether debug mode was requested
	 * Order of priority is 1) request param, 2) cookie, 3) $wg setting
	 * @return bool
	 */
	public static function inDebugMode() {
		global $wgRequest, $wgResourceLoaderDebug;
		static $retval = null;
		if ( !is_null( $retval ) )
			return $retval;
		return $retval = $wgRequest->getFuzzyBool( 'debug',
			$wgRequest->getCookie( 'resourceLoaderDebug', '', $wgResourceLoaderDebug ) );
	}
}
