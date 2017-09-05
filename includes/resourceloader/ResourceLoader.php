<?php
/**
 * Base class for resource loading system.
 *
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

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use WrappedString\WrappedString;

/**
 * Dynamic JavaScript and CSS resource loading system.
 *
 * Most of the documentation is on the MediaWiki documentation wiki starting at:
 *    https://www.mediawiki.org/wiki/ResourceLoader
 */
class ResourceLoader implements LoggerAwareInterface {
	/** @var int */
	protected static $filterCacheVersion = 7;

	/** @var bool */
	protected static $debugMode = null;

	/** @var array */
	private $lessVars = null;

	/**
	 * Module name/ResourceLoaderModule object pairs
	 * @var array
	 */
	protected $modules = [];

	/**
	 * Associative array mapping module name to info associative array
	 * @var array
	 */
	protected $moduleInfos = [];

	/** @var Config $config */
	private $config;

	/**
	 * Associative array mapping framework ids to a list of names of test suite modules
	 * like array( 'qunit' => array( 'mediawiki.tests.qunit.suites', 'ext.foo.tests', .. ), .. )
	 * @var array
	 */
	protected $testModuleNames = [];

	/**
	 * E.g. array( 'source-id' => 'http://.../load.php' )
	 * @var array
	 */
	protected $sources = [];

	/**
	 * Errors accumulated during current respond() call.
	 * @var array
	 */
	protected $errors = [];

	/**
	 * @var MessageBlobStore
	 */
	protected $blobStore;

	/**
	 * @var LoggerInterface
	 */
	private $logger;

	/** @var string JavaScript / CSS pragma to disable minification. **/
	const FILTER_NOMIN = '/*@nomin*/';

	/**
	 * Load information stored in the database about modules.
	 *
	 * This method grabs modules dependencies from the database and updates modules
	 * objects.
	 *
	 * This is not inside the module code because it is much faster to
	 * request all of the information at once than it is to have each module
	 * requests its own information. This sacrifice of modularity yields a substantial
	 * performance improvement.
	 *
	 * @param array $moduleNames List of module names to preload information for
	 * @param ResourceLoaderContext $context Context to load the information within
	 */
	public function preloadModuleInfo( array $moduleNames, ResourceLoaderContext $context ) {
		if ( !$moduleNames ) {
			// Or else Database*::select() will explode, plus it's cheaper!
			return;
		}
		$dbr = wfGetDB( DB_SLAVE );
		$skin = $context->getSkin();
		$lang = $context->getLanguage();

		// Batched version of ResourceLoaderModule::getFileDependencies
		$vary = "$skin|$lang";
		$res = $dbr->select( 'module_deps', [ 'md_module', 'md_deps' ], [
				'md_module' => $moduleNames,
				'md_skin' => $vary,
			], __METHOD__
		);

		// Prime in-object cache for file dependencies
		$modulesWithDeps = [];
		foreach ( $res as $row ) {
			$module = $this->getModule( $row->md_module );
			if ( $module ) {
				$module->setFileDependencies( $context, ResourceLoaderModule::expandRelativePaths(
					FormatJson::decode( $row->md_deps, true )
				) );
				$modulesWithDeps[] = $row->md_module;
			}
		}
		// Register the absence of a dependency row too
		foreach ( array_diff( $moduleNames, $modulesWithDeps ) as $name ) {
			$module = $this->getModule( $name );
			if ( $module ) {
				$this->getModule( $name )->setFileDependencies( $context, [] );
			}
		}

		// Prime in-object cache for message blobs for modules with messages
		$modules = [];
		foreach ( $moduleNames as $name ) {
			$module = $this->getModule( $name );
			if ( $module && $module->getMessages() ) {
				$modules[$name] = $module;
			}
		}
		$store = $this->getMessageBlobStore();
		$blobs = $store->getBlobs( $modules, $lang );
		foreach ( $blobs as $name => $blob ) {
			$modules[$name]->setMessageBlob( $blob, $lang );
		}
	}

	/**
	 * Run JavaScript or CSS data through a filter, caching the filtered result for future calls.
	 *
	 * Available filters are:
	 *
	 *    - minify-js \see JavaScriptMinifier::minify
	 *    - minify-css \see CSSMin::minify
	 *
	 * If $data is empty, only contains whitespace or the filter was unknown,
	 * $data is returned unmodified.
	 *
	 * @param string $filter Name of filter to run
	 * @param string $data Text to filter, such as JavaScript or CSS text
	 * @param array $options Keys:
	 *  - (bool) cache: Whether to allow caching this data. Default: true.
	 * @return string Filtered data, or a comment containing an error message
	 */
	public static function filter( $filter, $data, array $options = [] ) {
		if ( strpos( $data, ResourceLoader::FILTER_NOMIN ) !== false ) {
			return $data;
		}

		if ( isset( $options['cache'] ) && $options['cache'] === false ) {
			return self::applyFilter( $filter, $data );
		}

		$stats = RequestContext::getMain()->getStats();
		$cache = ObjectCache::getLocalServerInstance( CACHE_ANYTHING );

		$key = $cache->makeGlobalKey(
			'resourceloader',
			'filter',
			$filter,
			self::$filterCacheVersion, md5( $data )
		);

		$result = $cache->get( $key );
		if ( $result === false ) {
			$stats->increment( "resourceloader_cache.$filter.miss" );
			$result = self::applyFilter( $filter, $data );
			$cache->set( $key, $result, 24 * 3600 );
		} else {
			$stats->increment( "resourceloader_cache.$filter.hit" );
		}
		if ( $result === null ) {
			// Cached failure
			$result = $data;
		}

		return $result;
	}

	private static function applyFilter( $filter, $data ) {
		$data = trim( $data );
		if ( $data ) {
			try {
				$data = ( $filter === 'minify-css' )
					? CSSMin::minify( $data )
					: JavaScriptMinifier::minify( $data );
			} catch ( Exception $e ) {
				MWExceptionHandler::logException( $e );
				return null;
			}
		}
		return $data;
	}

	/* Methods */

	/**
	 * Register core modules and runs registration hooks.
	 * @param Config $config [optional]
	 * @param LoggerInterface $logger [optional]
	 */
	public function __construct( Config $config = null, LoggerInterface $logger = null ) {
		global $IP;

		$this->logger = $logger ?: new NullLogger();

		if ( !$config ) {
			$this->logger->debug( __METHOD__ . ' was called without providing a Config instance' );
			$config = ConfigFactory::getDefaultInstance()->makeConfig( 'main' );
		}
		$this->config = $config;

		// Add 'local' source first
		$this->addSource( 'local', wfScript( 'load' ) );

		// Add other sources
		$this->addSource( $config->get( 'ResourceLoaderSources' ) );

		// Register core modules
		$this->register( include "$IP/resources/Resources.php" );
		$this->register( include "$IP/resources/ResourcesOOUI.php" );
		// Register extension modules
		$this->register( $config->get( 'ResourceModules' ) );

		// Avoid PHP 7.1 warning from passing $this by reference
		$rl = $this;
		Hooks::run( 'ResourceLoaderRegisterModules', [ &$rl ] );

		if ( $config->get( 'EnableJavaScriptTest' ) === true ) {
			$this->registerTestModules();
		}

		$this->setMessageBlobStore( new MessageBlobStore( $this, $this->logger ) );
	}

	/**
	 * @return Config
	 */
	public function getConfig() {
		return $this->config;
	}

	/**
	 * @since 1.26
	 * @param LoggerInterface $logger
	 */
	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * @since 1.27
	 * @return LoggerInterface
	 */
	public function getLogger() {
		return $this->logger;
	}

	/**
	 * @since 1.26
	 * @return MessageBlobStore
	 */
	public function getMessageBlobStore() {
		return $this->blobStore;
	}

	/**
	 * @since 1.25
	 * @param MessageBlobStore $blobStore
	 */
	public function setMessageBlobStore( MessageBlobStore $blobStore ) {
		$this->blobStore = $blobStore;
	}

	/**
	 * Register a module with the ResourceLoader system.
	 *
	 * @param mixed $name Name of module as a string or List of name/object pairs as an array
	 * @param array $info Module info array. For backwards compatibility with 1.17alpha,
	 *   this may also be a ResourceLoaderModule object. Optional when using
	 *   multiple-registration calling style.
	 * @throws MWException If a duplicate module registration is attempted
	 * @throws MWException If a module name contains illegal characters (pipes or commas)
	 * @throws MWException If something other than a ResourceLoaderModule is being registered
	 * @return bool False if there were any errors, in which case one or more modules were
	 *   not registered
	 */
	public function register( $name, $info = null ) {

		// Allow multiple modules to be registered in one call
		$registrations = is_array( $name ) ? $name : [ $name => $info ];
		foreach ( $registrations as $name => $info ) {
			// Warn on duplicate registrations
			if ( isset( $this->moduleInfos[$name] ) ) {
				// A module has already been registered by this name
				$this->logger->warning(
					'ResourceLoader duplicate registration warning. ' .
					'Another module has already been registered as ' . $name
				);
			}

			// Check $name for validity
			if ( !self::isValidModuleName( $name ) ) {
				throw new MWException( "ResourceLoader module name '$name' is invalid, "
					. "see ResourceLoader::isValidModuleName()" );
			}

			// Attach module
			if ( $info instanceof ResourceLoaderModule ) {
				$this->moduleInfos[$name] = [ 'object' => $info ];
				$info->setName( $name );
				$this->modules[$name] = $info;
			} elseif ( is_array( $info ) ) {
				// New calling convention
				$this->moduleInfos[$name] = $info;
			} else {
				throw new MWException(
					'ResourceLoader module info type error for module \'' . $name .
					'\': expected ResourceLoaderModule or array (got: ' . gettype( $info ) . ')'
				);
			}

			// Last-minute changes

			// Apply custom skin-defined styles to existing modules.
			if ( $this->isFileModule( $name ) ) {
				foreach ( $this->config->get( 'ResourceModuleSkinStyles' ) as $skinName => $skinStyles ) {
					// If this module already defines skinStyles for this skin, ignore $wgResourceModuleSkinStyles.
					if ( isset( $this->moduleInfos[$name]['skinStyles'][$skinName] ) ) {
						continue;
					}

					// If $name is preceded with a '+', the defined style files will be added to 'default'
					// skinStyles, otherwise 'default' will be ignored as it normally would be.
					if ( isset( $skinStyles[$name] ) ) {
						$paths = (array)$skinStyles[$name];
						$styleFiles = [];
					} elseif ( isset( $skinStyles['+' . $name] ) ) {
						$paths = (array)$skinStyles['+' . $name];
						$styleFiles = isset( $this->moduleInfos[$name]['skinStyles']['default'] ) ?
							(array)$this->moduleInfos[$name]['skinStyles']['default'] :
							[];
					} else {
						continue;
					}

					// Add new file paths, remapping them to refer to our directories and not use settings
					// from the module we're modifying, which come from the base definition.
					list( $localBasePath, $remoteBasePath ) =
						ResourceLoaderFileModule::extractBasePaths( $skinStyles );

					foreach ( $paths as $path ) {
						$styleFiles[] = new ResourceLoaderFilePath( $path, $localBasePath, $remoteBasePath );
					}

					$this->moduleInfos[$name]['skinStyles'][$skinName] = $styleFiles;
				}
			}
		}

	}

	/**
	 */
	public function registerTestModules() {
		global $IP;

		if ( $this->config->get( 'EnableJavaScriptTest' ) !== true ) {
			throw new MWException( 'Attempt to register JavaScript test modules '
				. 'but <code>$wgEnableJavaScriptTest</code> is false. '
				. 'Edit your <code>LocalSettings.php</code> to enable it.' );
		}

		// Get core test suites
		$testModules = [];
		$testModules['qunit'] = [];
		// Get other test suites (e.g. from extensions)
		// Avoid PHP 7.1 warning from passing $this by reference
		$rl = $this;
		Hooks::run( 'ResourceLoaderTestModules', [ &$testModules, &$rl ] );

		// Add the testrunner (which configures QUnit) to the dependencies.
		// Since it must be ready before any of the test suites are executed.
		foreach ( $testModules['qunit'] as &$module ) {
			// Make sure all test modules are top-loading so that when QUnit starts
			// on document-ready, it will run once and finish. If some tests arrive
			// later (possibly after QUnit has already finished) they will be ignored.
			$module['position'] = 'top';
			$module['dependencies'][] = 'test.mediawiki.qunit.testrunner';
		}

		$testModules['qunit'] =
			( include "$IP/tests/qunit/QUnitTestResources.php" ) + $testModules['qunit'];

		foreach ( $testModules as $id => $names ) {
			// Register test modules
			$this->register( $testModules[$id] );

			// Keep track of their names so that they can be loaded together
			$this->testModuleNames[$id] = array_keys( $testModules[$id] );
		}

	}

	/**
	 * Add a foreign source of modules.
	 *
	 * Source IDs are typically the same as the Wiki ID or database name (e.g. lowercase a-z).
	 *
	 * @param array|string $id Source ID (string), or array( id1 => loadUrl, id2 => loadUrl, ... )
	 * @param string|array $loadUrl load.php url (string), or array with loadUrl key for
	 *  backwards-compatibility.
	 * @throws MWException
	 */
	public function addSource( $id, $loadUrl = null ) {
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

		// Pre 1.24 backwards-compatibility
		if ( is_array( $loadUrl ) ) {
			if ( !isset( $loadUrl['loadScript'] ) ) {
				throw new MWException(
					__METHOD__ . ' was passed an array with no "loadScript" key.'
				);
			}

			$loadUrl = $loadUrl['loadScript'];
		}

		$this->sources[$id] = $loadUrl;
	}

	/**
	 * Get a list of module names.
	 *
	 * @return array List of module names
	 */
	public function getModuleNames() {
		return array_keys( $this->moduleInfos );
	}

	/**
	 * Get a list of test module names for one (or all) frameworks.
	 *
	 * If the given framework id is unknkown, or if the in-object variable is not an array,
	 * then it will return an empty array.
	 *
	 * @param string $framework Get only the test module names for one
	 *   particular framework (optional)
	 * @return array
	 */
	public function getTestModuleNames( $framework = 'all' ) {
		/** @todo api siteinfo prop testmodulenames modulenames */
		if ( $framework == 'all' ) {
			return $this->testModuleNames;
		} elseif ( isset( $this->testModuleNames[$framework] )
			&& is_array( $this->testModuleNames[$framework] )
		) {
			return $this->testModuleNames[$framework];
		} else {
			return [];
		}
	}

	/**
	 * Check whether a ResourceLoader module is registered
	 *
	 * @since 1.25
	 * @param string $name
	 * @return bool
	 */
	public function isModuleRegistered( $name ) {
		return isset( $this->moduleInfos[$name] );
	}

	/**
	 * Get the ResourceLoaderModule object for a given module name.
	 *
	 * If an array of module parameters exists but a ResourceLoaderModule object has not
	 * yet been instantiated, this method will instantiate and cache that object such that
	 * subsequent calls simply return the same object.
	 *
	 * @param string $name Module name
	 * @return ResourceLoaderModule|null If module has been registered, return a
	 *  ResourceLoaderModule instance. Otherwise, return null.
	 */
	public function getModule( $name ) {
		if ( !isset( $this->modules[$name] ) ) {
			if ( !isset( $this->moduleInfos[$name] ) ) {
				// No such module
				return null;
			}
			// Construct the requested object
			$info = $this->moduleInfos[$name];
			/** @var ResourceLoaderModule $object */
			if ( isset( $info['object'] ) ) {
				// Object given in info array
				$object = $info['object'];
			} else {
				if ( !isset( $info['class'] ) ) {
					$class = 'ResourceLoaderFileModule';
				} else {
					$class = $info['class'];
				}
				/** @var ResourceLoaderModule $object */
				$object = new $class( $info );
				$object->setConfig( $this->getConfig() );
				$object->setLogger( $this->logger );
			}
			$object->setName( $name );
			$this->modules[$name] = $object;
		}

		return $this->modules[$name];
	}

	/**
	 * Return whether the definition of a module corresponds to a simple ResourceLoaderFileModule.
	 *
	 * @param string $name Module name
	 * @return bool
	 */
	protected function isFileModule( $name ) {
		if ( !isset( $this->moduleInfos[$name] ) ) {
			return false;
		}
		$info = $this->moduleInfos[$name];
		if ( isset( $info['object'] ) || isset( $info['class'] ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Get the list of sources.
	 *
	 * @return array Like array( id => load.php url, .. )
	 */
	public function getSources() {
		return $this->sources;
	}

	/**
	 * Get the URL to the load.php endpoint for the given
	 * ResourceLoader source
	 *
	 * @since 1.24
	 * @param string $source
	 * @throws MWException On an invalid $source name
	 * @return string
	 */
	public function getLoadScript( $source ) {
		if ( !isset( $this->sources[$source] ) ) {
			throw new MWException( "The $source source was never registered in ResourceLoader." );
		}
		return $this->sources[$source];
	}

	/**
	 * @since 1.26
	 * @param string $value
	 * @return string Hash
	 */
	public static function makeHash( $value ) {
		// Use base64 to output more entropy in a more compact string (default hex is only base16).
		// The first 8 chars of a base64 encoded digest represent the same binary as
		// the first 12 chars of a hex encoded digest.
		return substr( base64_encode( sha1( $value, true ) ), 0, 8 );
	}

	/**
	 * Helper method to get and combine versions of multiple modules.
	 *
	 * @since 1.26
	 * @param ResourceLoaderContext $context
	 * @param array $modules List of ResourceLoaderModule objects
	 * @return string Hash
	 */
	public function getCombinedVersion( ResourceLoaderContext $context, array $modules ) {
		if ( !$modules ) {
			return '';
		}
		$hashes = array_map( function ( $module ) use ( $context ) {
			return $this->getModule( $module )->getVersionHash( $context );
		}, $modules );
		return self::makeHash( implode( $hashes ) );
	}

	/**
	 * Output a response to a load request, including the content-type header.
	 *
	 * @param ResourceLoaderContext $context Context in which a response should be formed
	 */
	public function respond( ResourceLoaderContext $context ) {
		// Buffer output to catch warnings. Normally we'd use ob_clean() on the
		// top-level output buffer to clear warnings, but that breaks when ob_gzhandler
		// is used: ob_clean() will clear the GZIP header in that case and it won't come
		// back for subsequent output, resulting in invalid GZIP. So we have to wrap
		// the whole thing in our own output buffer to be sure the active buffer
		// doesn't use ob_gzhandler.
		// See http://bugs.php.net/bug.php?id=36514
		ob_start();

		// Find out which modules are missing and instantiate the others
		$modules = [];
		$missing = [];
		foreach ( $context->getModules() as $name ) {
			$module = $this->getModule( $name );
			if ( $module ) {
				// Do not allow private modules to be loaded from the web.
				// This is a security issue, see bug 34907.
				if ( $module->getGroup() === 'private' ) {
					$this->logger->debug( "Request for private module '$name' denied" );
					$this->errors[] = "Cannot show private module \"$name\"";
					continue;
				}
				$modules[$name] = $module;
			} else {
				$missing[] = $name;
			}
		}

		try {
			// Preload for getCombinedVersion() and for batch makeModuleResponse()
			$this->preloadModuleInfo( array_keys( $modules ), $context );
		} catch ( Exception $e ) {
			MWExceptionHandler::logException( $e );
			$this->logger->warning( 'Preloading module info failed: {exception}', [
				'exception' => $e
			] );
			$this->errors[] = self::formatExceptionNoComment( $e );
		}

		// Combine versions to propagate cache invalidation
		$versionHash = '';
		try {
			$versionHash = $this->getCombinedVersion( $context, array_keys( $modules ) );
		} catch ( Exception $e ) {
			MWExceptionHandler::logException( $e );
			$this->logger->warning( 'Calculating version hash failed: {exception}', [
				'exception' => $e
			] );
			$this->errors[] = self::formatExceptionNoComment( $e );
		}

		// See RFC 2616 § 3.11 Entity Tags
		// http://www.w3.org/Protocols/rfc2616/rfc2616-sec3.html#sec3.11
		$etag = 'W/"' . $versionHash . '"';

		// Try the client-side cache first
		if ( $this->tryRespondNotModified( $context, $etag ) ) {
			return; // output handled (buffers cleared)
		}

		// Use file cache if enabled and available...
		if ( $this->config->get( 'UseFileCache' ) ) {
			$fileCache = ResourceFileCache::newFromContext( $context );
			if ( $this->tryRespondFromFileCache( $fileCache, $context, $etag ) ) {
				return; // output handled
			}
		}

		// Generate a response
		$response = $this->makeModuleResponse( $context, $modules, $missing );

		// Capture any PHP warnings from the output buffer and append them to the
		// error list if we're in debug mode.
		if ( $context->getDebug() ) {
			$warnings = ob_get_contents();
			if ( strlen( $warnings ) ) {
				$this->errors[] = $warnings;
			}
		}

		// Save response to file cache unless there are errors
		if ( isset( $fileCache ) && !$this->errors && !count( $missing ) ) {
			// Cache single modules and images...and other requests if there are enough hits
			if ( ResourceFileCache::useFileCache( $context ) ) {
				if ( $fileCache->isCacheWorthy() ) {
					$fileCache->saveText( $response );
				} else {
					$fileCache->incrMissesRecent( $context->getRequest() );
				}
			}
		}

		$this->sendResponseHeaders( $context, $etag, (bool)$this->errors );

		// Remove the output buffer and output the response
		ob_end_clean();

		if ( $context->getImageObj() && $this->errors ) {
			// We can't show both the error messages and the response when it's an image.
			$response = implode( "\n\n", $this->errors );
		} elseif ( $this->errors ) {
			$errorText = implode( "\n\n", $this->errors );
			$errorResponse = self::makeComment( $errorText );
			if ( $context->shouldIncludeScripts() ) {
				$errorResponse .= 'if (window.console && console.error) {'
					. Xml::encodeJsCall( 'console.error', [ $errorText ] )
					. "}\n";
			}

			// Prepend error info to the response
			$response = $errorResponse . $response;
		}

		$this->errors = [];
		echo $response;

	}

	/**
	 * Send main response headers to the client.
	 *
	 * Deals with Content-Type, CORS (for stylesheets), and caching.
	 *
	 * @param ResourceLoaderContext $context
	 * @param string $etag ETag header value
	 * @param bool $errors Whether there are errors in the response
	 * @return void
	 */
	protected function sendResponseHeaders( ResourceLoaderContext $context, $etag, $errors ) {
		$rlMaxage = $this->config->get( 'ResourceLoaderMaxage' );
		// If a version wasn't specified we need a shorter expiry time for updates
		// to propagate to clients quickly
		// If there were errors, we also need a shorter expiry time so we can recover quickly
		if ( is_null( $context->getVersion() ) || $errors ) {
			$maxage = $rlMaxage['unversioned']['client'];
			$smaxage = $rlMaxage['unversioned']['server'];
		// If a version was specified we can use a longer expiry time since changing
		// version numbers causes cache misses
		} else {
			$maxage = $rlMaxage['versioned']['client'];
			$smaxage = $rlMaxage['versioned']['server'];
		}
		if ( $context->getImageObj() ) {
			// Output different headers if we're outputting textual errors.
			if ( $errors ) {
				header( 'Content-Type: text/plain; charset=utf-8' );
			} else {
				$context->getImageObj()->sendResponseHeaders( $context );
			}
		} elseif ( $context->getOnly() === 'styles' ) {
			header( 'Content-Type: text/css; charset=utf-8' );
			header( 'Access-Control-Allow-Origin: *' );
		} else {
			header( 'Content-Type: text/javascript; charset=utf-8' );
		}
		// See RFC 2616 § 14.19 ETag
		// http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.19
		header( 'ETag: ' . $etag );
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
	 * Respond with HTTP 304 Not Modified if appropiate.
	 *
	 * If there's an If-None-Match header, respond with a 304 appropriately
	 * and clear out the output buffer. If the client cache is too old then do nothing.
	 *
	 * @param ResourceLoaderContext $context
	 * @param string $etag ETag header value
	 * @return bool True if HTTP 304 was sent and output handled
	 */
	protected function tryRespondNotModified( ResourceLoaderContext $context, $etag ) {
		// See RFC 2616 § 14.26 If-None-Match
		// http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.26
		$clientKeys = $context->getRequest()->getHeader( 'If-None-Match', WebRequest::GETHEADER_LIST );
		// Never send 304s in debug mode
		if ( $clientKeys !== false && !$context->getDebug() && in_array( $etag, $clientKeys ) ) {
			// There's another bug in ob_gzhandler (see also the comment at
			// the top of this function) that causes it to gzip even empty
			// responses, meaning it's impossible to produce a truly empty
			// response (because the gzip header is always there). This is
			// a problem because 304 responses have to be completely empty
			// per the HTTP spec, and Firefox behaves buggily when they're not.
			// See also http://bugs.php.net/bug.php?id=51579
			// To work around this, we tear down all output buffering before
			// sending the 304.
			wfResetOutputBuffers( /* $resetGzipEncoding = */ true );

			HttpStatus::header( 304 );

			$this->sendResponseHeaders( $context, $etag, false );
			return true;
		}
		return false;
	}

	/**
	 * Send out code for a response from file cache if possible.
	 *
	 * @param ResourceFileCache $fileCache Cache object for this request URL
	 * @param ResourceLoaderContext $context Context in which to generate a response
	 * @param string $etag ETag header value
	 * @return bool If this found a cache file and handled the response
	 */
	protected function tryRespondFromFileCache(
		ResourceFileCache $fileCache,
		ResourceLoaderContext $context,
		$etag
	) {
		$rlMaxage = $this->config->get( 'ResourceLoaderMaxage' );
		// Buffer output to catch warnings.
		ob_start();
		// Get the maximum age the cache can be
		$maxage = is_null( $context->getVersion() )
			? $rlMaxage['unversioned']['server']
			: $rlMaxage['versioned']['server'];
		// Minimum timestamp the cache file must have
		$good = $fileCache->isCacheGood( wfTimestamp( TS_MW, time() - $maxage ) );
		if ( !$good ) {
			try { // RL always hits the DB on file cache miss...
				wfGetDB( DB_SLAVE );
			} catch ( DBConnectionError $e ) { // ...check if we need to fallback to cache
				$good = $fileCache->isCacheGood(); // cache existence check
			}
		}
		if ( $good ) {
			$ts = $fileCache->cacheTimestamp();
			// Send content type and cache headers
			$this->sendResponseHeaders( $context, $etag, false );
			$response = $fileCache->fetchText();
			// Capture any PHP warnings from the output buffer and append them to the
			// response in a comment if we're in debug mode.
			if ( $context->getDebug() ) {
				$warnings = ob_get_contents();
				if ( strlen( $warnings ) ) {
					$response = self::makeComment( $warnings ) . $response;
				}
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

	/**
	 * Generate a CSS or JS comment block.
	 *
	 * Only use this for public data, not error message details.
	 *
	 * @param string $text
	 * @return string
	 */
	public static function makeComment( $text ) {
		$encText = str_replace( '*/', '* /', $text );
		return "/*\n$encText\n*/\n";
	}

	/**
	 * Handle exception display.
	 *
	 * @param Exception $e Exception to be shown to the user
	 * @return string Sanitized text in a CSS/JS comment that can be returned to the user
	 */
	public static function formatException( $e ) {
		return self::makeComment( self::formatExceptionNoComment( $e ) );
	}

	/**
	 * Handle exception display.
	 *
	 * @since 1.25
	 * @param Exception $e Exception to be shown to the user
	 * @return string Sanitized text that can be returned to the user
	 */
	protected static function formatExceptionNoComment( $e ) {
		global $wgShowExceptionDetails;

		if ( !$wgShowExceptionDetails ) {
			return MWExceptionHandler::getPublicLogMessage( $e );
		}

		return MWExceptionHandler::getLogMessage( $e );
	}

	/**
	 * Generate code for a response.
	 *
	 * @param ResourceLoaderContext $context Context in which to generate a response
	 * @param ResourceLoaderModule[] $modules List of module objects keyed by module name
	 * @param string[] $missing List of requested module names that are unregistered (optional)
	 * @return string Response data
	 */
	public function makeModuleResponse( ResourceLoaderContext $context,
		array $modules, array $missing = []
	) {
		$out = '';
		$states = [];

		if ( !count( $modules ) && !count( $missing ) ) {
			return <<<MESSAGE
/* This file is the Web entry point for MediaWiki's ResourceLoader:
   <https://www.mediawiki.org/wiki/ResourceLoader>. In this request,
   no modules were requested. Max made me put this here. */
MESSAGE;
		}

		$image = $context->getImageObj();
		if ( $image ) {
			$data = $image->getImageData( $context );
			if ( $data === false ) {
				$data = '';
				$this->errors[] = 'Image generation failed';
			}
			return $data;
		}

		foreach ( $missing as $name ) {
			$states[$name] = 'missing';
		}

		// Generate output
		$isRaw = false;

		$filter = $context->getOnly() === 'styles' ? 'minify-css' : 'minify-js';

		foreach ( $modules as $name => $module ) {
			try {
				$content = $module->getModuleContent( $context );
				$strContent = '';

				// Append output
				switch ( $context->getOnly() ) {
					case 'scripts':
						$scripts = $content['scripts'];
						if ( is_string( $scripts ) ) {
							// Load scripts raw...
							$strContent = $scripts;
						} elseif ( is_array( $scripts ) ) {
							// ...except when $scripts is an array of URLs
							$strContent = self::makeLoaderImplementScript( $name, $scripts, [], [], [] );
						}
						break;
					case 'styles':
						$styles = $content['styles'];
						// We no longer seperate into media, they are all combined now with
						// custom media type groups into @media .. {} sections as part of the css string.
						// Module returns either an empty array or a numerical array with css strings.
						$strContent = isset( $styles['css'] ) ? implode( '', $styles['css'] ) : '';
						break;
					default:
						$strContent = self::makeLoaderImplementScript(
							$name,
							isset( $content['scripts'] ) ? $content['scripts'] : '',
							isset( $content['styles'] ) ? $content['styles'] : [],
							isset( $content['messagesBlob'] ) ? new XmlJsCode( $content['messagesBlob'] ) : [],
							isset( $content['templates'] ) ? $content['templates'] : []
						);
						break;
				}

				if ( !$context->getDebug() ) {
					$strContent = self::filter( $filter, $strContent );
				}

				$out .= $strContent;

			} catch ( Exception $e ) {
				MWExceptionHandler::logException( $e );
				$this->logger->warning( 'Generating module package failed: {exception}', [
					'exception' => $e
				] );
				$this->errors[] = self::formatExceptionNoComment( $e );

				// Respond to client with error-state instead of module implementation
				$states[$name] = 'error';
				unset( $modules[$name] );
			}
			$isRaw |= $module->isRaw();
		}

		// Update module states
		if ( $context->shouldIncludeScripts() && !$context->getRaw() && !$isRaw ) {
			if ( count( $modules ) && $context->getOnly() === 'scripts' ) {
				// Set the state of modules loaded as only scripts to ready as
				// they don't have an mw.loader.implement wrapper that sets the state
				foreach ( $modules as $name => $module ) {
					$states[$name] = 'ready';
				}
			}

			// Set the state of modules we didn't respond to with mw.loader.implement
			if ( count( $states ) ) {
				$stateScript = self::makeLoaderStateScript( $states );
				if ( !$context->getDebug() ) {
					$stateScript = self::filter( 'minify-js', $stateScript );
				}
				$out .= $stateScript;
			}
		} else {
			if ( count( $states ) ) {
				$this->errors[] = 'Problematic modules: ' .
					FormatJson::encode( $states, ResourceLoader::inDebugMode() );
			}
		}

		return $out;
	}

	/**
	 * Get names of modules that use a certain message.
	 *
	 * @param string $messageKey
	 * @return array List of module names
	 */
	public function getModulesByMessage( $messageKey ) {
		$moduleNames = [];
		foreach ( $this->getModuleNames() as $moduleName ) {
			$module = $this->getModule( $moduleName );
			if ( in_array( $messageKey, $module->getMessages() ) ) {
				$moduleNames[] = $moduleName;
			}
		}
		return $moduleNames;
	}

	/* Static Methods */

	/**
	 * Return JS code that calls mw.loader.implement with given module properties.
	 *
	 * @param string $name Module name
	 * @param mixed $scripts List of URLs to JavaScript files or String of JavaScript code
	 * @param mixed $styles Array of CSS strings keyed by media type, or an array of lists of URLs
	 *   to CSS files keyed by media type
	 * @param mixed $messages List of messages associated with this module. May either be an
	 *   associative array mapping message key to value, or a JSON-encoded message blob containing
	 *   the same data, wrapped in an XmlJsCode object.
	 * @param array $templates Keys are name of templates and values are the source of
	 *   the template.
	 * @throws MWException
	 * @return string
	 */
	public static function makeLoaderImplementScript(
		$name, $scripts, $styles, $messages, $templates
	) {
		if ( is_string( $scripts ) ) {
			// Site and user module are a legacy scripts that run in the global scope (no closure).
			// Transportation as string instructs mw.loader.implement to use globalEval.
			if ( $name === 'site' || $name === 'user' ) {
				// Minify manually because the general makeModuleResponse() minification won't be
				// effective here due to the script being a string instead of a function. (T107377)
				if ( !ResourceLoader::inDebugMode() ) {
					$scripts = self::filter( 'minify-js', $scripts );
				}
			} else {
				$scripts = new XmlJsCode( "function ( $, jQuery, require, module ) {\n{$scripts}\n}" );
			}
		} elseif ( !is_array( $scripts ) ) {
			throw new MWException( 'Invalid scripts error. Array of URLs or string of code expected.' );
		}
		// mw.loader.implement requires 'styles', 'messages' and 'templates' to be objects (not
		// arrays). json_encode considers empty arrays to be numerical and outputs "[]" instead
		// of "{}". Force them to objects.
		$module = [
			$name,
			$scripts,
			(object)$styles,
			(object)$messages,
			(object)$templates,
		];
		self::trimArray( $module );

		return Xml::encodeJsCall( 'mw.loader.implement', $module, ResourceLoader::inDebugMode() );
	}

	/**
	 * Returns JS code which, when called, will register a given list of messages.
	 *
	 * @param mixed $messages Either an associative array mapping message key to value, or a
	 *   JSON-encoded message blob containing the same data, wrapped in an XmlJsCode object.
	 * @return string
	 */
	public static function makeMessageSetScript( $messages ) {
		return Xml::encodeJsCall(
			'mw.messages.set',
			[ (object)$messages ],
			ResourceLoader::inDebugMode()
		);
	}

	/**
	 * Combines an associative array mapping media type to CSS into a
	 * single stylesheet with "@media" blocks.
	 *
	 * @param array $stylePairs Array keyed by media type containing (arrays of) CSS strings
	 * @return array
	 */
	public static function makeCombinedStyles( array $stylePairs ) {
		$out = [];
		foreach ( $stylePairs as $media => $styles ) {
			// ResourceLoaderFileModule::getStyle can return the styles
			// as a string or an array of strings. This is to allow separation in
			// the front-end.
			$styles = (array)$styles;
			foreach ( $styles as $style ) {
				$style = trim( $style );
				// Don't output an empty "@media print { }" block (bug 40498)
				if ( $style !== '' ) {
					// Transform the media type based on request params and config
					// The way that this relies on $wgRequest to propagate request params is slightly evil
					$media = OutputPage::transformCssMedia( $media );

					if ( $media === '' || $media == 'all' ) {
						$out[] = $style;
					} elseif ( is_string( $media ) ) {
						$out[] = "@media $media {\n" . str_replace( "\n", "\n\t", "\t" . $style ) . "}";
					}
					// else: skip
				}
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
	 * @param string $name
	 * @param string $state
	 * @return string
	 */
	public static function makeLoaderStateScript( $name, $state = null ) {
		if ( is_array( $name ) ) {
			return Xml::encodeJsCall(
				'mw.loader.state',
				[ $name ],
				ResourceLoader::inDebugMode()
			);
		} else {
			return Xml::encodeJsCall(
				'mw.loader.state',
				[ $name, $state ],
				ResourceLoader::inDebugMode()
			);
		}
	}

	/**
	 * Returns JS code which calls the script given by $script. The script will
	 * be called with local variables name, version, dependencies and group,
	 * which will have values corresponding to $name, $version, $dependencies
	 * and $group as supplied.
	 *
	 * @param string $name Module name
	 * @param string $version Module version hash
	 * @param array $dependencies List of module names on which this module depends
	 * @param string $group Group which the module is in.
	 * @param string $source Source of the module, or 'local' if not foreign.
	 * @param string $script JavaScript code
	 * @return string
	 */
	public static function makeCustomLoaderScript( $name, $version, $dependencies,
		$group, $source, $script
	) {
		$script = str_replace( "\n", "\n\t", trim( $script ) );
		return Xml::encodeJsCall(
			"( function ( name, version, dependencies, group, source ) {\n\t$script\n} )",
			[ $name, $version, $dependencies, $group, $source ],
			ResourceLoader::inDebugMode()
		);
	}

	private static function isEmptyObject( stdClass $obj ) {
		foreach ( $obj as $key => $value ) {
			return false;
		}
		return true;
	}

	/**
	 * Remove empty values from the end of an array.
	 *
	 * Values considered empty:
	 *
	 * - null
	 * - array()
	 * - new XmlJsCode( '{}' )
	 * - new stdClass() // (object) array()
	 *
	 * @param Array $array
	 */
	private static function trimArray( array &$array ) {
		$i = count( $array );
		while ( $i-- ) {
			if ( $array[$i] === null
				|| $array[$i] === []
				|| ( $array[$i] instanceof XmlJsCode && $array[$i]->value === '{}' )
				|| ( $array[$i] instanceof stdClass && self::isEmptyObject( $array[$i] ) )
			) {
				unset( $array[$i] );
			} else {
				break;
			}
		}
	}

	/**
	 * Returns JS code which calls mw.loader.register with the given
	 * parameters. Has three calling conventions:
	 *
	 *   - ResourceLoader::makeLoaderRegisterScript( $name, $version,
	 *        $dependencies, $group, $source, $skip
	 *     ):
	 *        Register a single module.
	 *
	 *   - ResourceLoader::makeLoaderRegisterScript( array( $name1, $name2 ) ):
	 *        Register modules with the given names.
	 *
	 *   - ResourceLoader::makeLoaderRegisterScript( array(
	 *        array( $name1, $version1, $dependencies1, $group1, $source1, $skip1 ),
	 *        array( $name2, $version2, $dependencies1, $group2, $source2, $skip2 ),
	 *        ...
	 *     ) ):
	 *        Registers modules with the given names and parameters.
	 *
	 * @param string $name Module name
	 * @param string $version Module version hash
	 * @param array $dependencies List of module names on which this module depends
	 * @param string $group Group which the module is in
	 * @param string $source Source of the module, or 'local' if not foreign
	 * @param string $skip Script body of the skip function
	 * @return string
	 */
	public static function makeLoaderRegisterScript( $name, $version = null,
		$dependencies = null, $group = null, $source = null, $skip = null
	) {
		if ( is_array( $name ) ) {
			// Build module name index
			$index = [];
			foreach ( $name as $i => &$module ) {
				$index[$module[0]] = $i;
			}

			// Transform dependency names into indexes when possible, they will be resolved by
			// mw.loader.register on the other end
			foreach ( $name as &$module ) {
				if ( isset( $module[2] ) ) {
					foreach ( $module[2] as &$dependency ) {
						if ( isset( $index[$dependency] ) ) {
							$dependency = $index[$dependency];
						}
					}
				}
			}

			array_walk( $name, [ 'self', 'trimArray' ] );

			return Xml::encodeJsCall(
				'mw.loader.register',
				[ $name ],
				ResourceLoader::inDebugMode()
			);
		} else {
			$registration = [ $name, $version, $dependencies, $group, $source, $skip ];
			self::trimArray( $registration );
			return Xml::encodeJsCall(
				'mw.loader.register',
				$registration,
				ResourceLoader::inDebugMode()
			);
		}
	}

	/**
	 * Returns JS code which calls mw.loader.addSource() with the given
	 * parameters. Has two calling conventions:
	 *
	 *   - ResourceLoader::makeLoaderSourcesScript( $id, $properties ):
	 *       Register a single source
	 *
	 *   - ResourceLoader::makeLoaderSourcesScript( array( $id1 => $loadUrl, $id2 => $loadUrl, ... ) );
	 *       Register sources with the given IDs and properties.
	 *
	 * @param string $id Source ID
	 * @param array $properties Source properties (see addSource())
	 * @return string
	 */
	public static function makeLoaderSourcesScript( $id, $properties = null ) {
		if ( is_array( $id ) ) {
			return Xml::encodeJsCall(
				'mw.loader.addSource',
				[ $id ],
				ResourceLoader::inDebugMode()
			);
		} else {
			return Xml::encodeJsCall(
				'mw.loader.addSource',
				[ $id, $properties ],
				ResourceLoader::inDebugMode()
			);
		}
	}

	/**
	 * Returns JS code which runs given JS code if the client-side framework is
	 * present.
	 *
	 * @deprecated since 1.25; use makeInlineScript instead
	 * @param string $script JavaScript code
	 * @return string
	 */
	public static function makeLoaderConditionalScript( $script ) {
		return '(window.RLQ=window.RLQ||[]).push(function(){' .
			trim( $script ) . '});';
	}

	/**
	 * Construct an inline script tag with given JS code.
	 *
	 * The code will be wrapped in a closure, and it will be executed by ResourceLoader
	 * only if the client has adequate support for MediaWiki JavaScript code.
	 *
	 * @param string $script JavaScript code
	 * @return WrappedString HTML
	 */
	public static function makeInlineScript( $script ) {
		$js = self::makeLoaderConditionalScript( $script );
		return new WrappedString(
			Html::inlineScript( $js ),
			'<script>(window.RLQ=window.RLQ||[]).push(function(){',
			'});</script>'
		);
	}

	/**
	 * Returns JS code which will set the MediaWiki configuration array to
	 * the given value.
	 *
	 * @param array $configuration List of configuration values keyed by variable name
	 * @return string
	 */
	public static function makeConfigSetScript( array $configuration ) {
		return Xml::encodeJsCall(
			'mw.config.set',
			[ $configuration ],
			ResourceLoader::inDebugMode()
		);
	}

	/**
	 * Convert an array of module names to a packed query string.
	 *
	 * For example, array( 'foo.bar', 'foo.baz', 'bar.baz', 'bar.quux' )
	 * becomes 'foo.bar,baz|bar.baz,quux'
	 * @param array $modules List of module names (strings)
	 * @return string Packed query string
	 */
	public static function makePackedModulesString( $modules ) {
		$groups = []; // array( prefix => array( suffixes ) )
		foreach ( $modules as $module ) {
			$pos = strrpos( $module, '.' );
			$prefix = $pos === false ? '' : substr( $module, 0, $pos );
			$suffix = $pos === false ? $module : substr( $module, $pos + 1 );
			$groups[$prefix][] = $suffix;
		}

		$arr = [];
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
		if ( self::$debugMode === null ) {
			global $wgRequest, $wgResourceLoaderDebug;
			self::$debugMode = $wgRequest->getFuzzyBool( 'debug',
				$wgRequest->getCookie( 'resourceLoaderDebug', '', $wgResourceLoaderDebug )
			);
		}
		return self::$debugMode;
	}

	/**
	 * Reset static members used for caching.
	 *
	 * Global state and $wgRequest are evil, but we're using it right
	 * now and sometimes we need to be able to force ResourceLoader to
	 * re-evaluate the context because it has changed (e.g. in the test suite).
	 */
	public static function clearCache() {
		self::$debugMode = null;
	}

	/**
	 * Build a load.php URL
	 *
	 * @since 1.24
	 * @param string $source Name of the ResourceLoader source
	 * @param ResourceLoaderContext $context
	 * @param array $extraQuery
	 * @return string URL to load.php. May be protocol-relative if $wgLoadScript is, too.
	 */
	public function createLoaderURL( $source, ResourceLoaderContext $context,
		$extraQuery = []
	) {
		$query = self::createLoaderQuery( $context, $extraQuery );
		$script = $this->getLoadScript( $source );

		return wfAppendQuery( $script, $query );
	}

	/**
	 * Build a load.php URL
	 * @deprecated since 1.24 Use createLoaderURL() instead
	 * @param array $modules Array of module names (strings)
	 * @param string $lang Language code
	 * @param string $skin Skin name
	 * @param string|null $user User name. If null, the &user= parameter is omitted
	 * @param string|null $version Versioning timestamp
	 * @param bool $debug Whether the request should be in debug mode
	 * @param string|null $only &only= parameter
	 * @param bool $printable Printable mode
	 * @param bool $handheld Handheld mode
	 * @param array $extraQuery Extra query parameters to add
	 * @return string URL to load.php. May be protocol-relative if $wgLoadScript is, too.
	 */
	public static function makeLoaderURL( $modules, $lang, $skin, $user = null,
		$version = null, $debug = false, $only = null, $printable = false,
		$handheld = false, $extraQuery = []
	) {
		global $wgLoadScript;

		$query = self::makeLoaderQuery( $modules, $lang, $skin, $user, $version, $debug,
			$only, $printable, $handheld, $extraQuery
		);

		return wfAppendQuery( $wgLoadScript, $query );
	}

	/**
	 * Helper for createLoaderURL()
	 *
	 * @since 1.24
	 * @see makeLoaderQuery
	 * @param ResourceLoaderContext $context
	 * @param array $extraQuery
	 * @return array
	 */
	public static function createLoaderQuery( ResourceLoaderContext $context, $extraQuery = [] ) {
		return self::makeLoaderQuery(
			$context->getModules(),
			$context->getLanguage(),
			$context->getSkin(),
			$context->getUser(),
			$context->getVersion(),
			$context->getDebug(),
			$context->getOnly(),
			$context->getRequest()->getBool( 'printable' ),
			$context->getRequest()->getBool( 'handheld' ),
			$extraQuery
		);
	}

	/**
	 * Build a query array (array representation of query string) for load.php. Helper
	 * function for makeLoaderURL().
	 *
	 * @param array $modules
	 * @param string $lang
	 * @param string $skin
	 * @param string $user
	 * @param string $version
	 * @param bool $debug
	 * @param string $only
	 * @param bool $printable
	 * @param bool $handheld
	 * @param array $extraQuery
	 *
	 * @return array
	 */
	public static function makeLoaderQuery( $modules, $lang, $skin, $user = null,
		$version = null, $debug = false, $only = null, $printable = false,
		$handheld = false, $extraQuery = []
	) {
		$query = [
			'modules' => self::makePackedModulesString( $modules ),
			'lang' => $lang,
			'skin' => $skin,
			'debug' => $debug ? 'true' : 'false',
		];
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

	/**
	 * Check a module name for validity.
	 *
	 * Module names may not contain pipes (|), commas (,) or exclamation marks (!) and can be
	 * at most 255 bytes.
	 *
	 * @param string $moduleName Module name to check
	 * @return bool Whether $moduleName is a valid module name
	 */
	public static function isValidModuleName( $moduleName ) {
		return strcspn( $moduleName, '!,|', 0, 255 ) === strlen( $moduleName );
	}

	/**
	 * Returns LESS compiler set up for use with MediaWiki
	 *
	 * @since 1.27
	 * @param array $extraVars Associative array of extra (i.e., other than the
	 *   globally-configured ones) that should be used for compilation.
	 * @throws MWException
	 * @return Less_Parser
	 */
	public function getLessCompiler( $extraVars = [] ) {
		// When called from the installer, it is possible that a required PHP extension
		// is missing (at least for now; see bug 47564). If this is the case, throw an
		// exception (caught by the installer) to prevent a fatal error later on.
		if ( !class_exists( 'Less_Parser' ) ) {
			throw new MWException( 'MediaWiki requires the less.php parser' );
		}

		$parser = new Less_Parser;
		$parser->ModifyVars( array_merge( $this->getLessVars(), $extraVars ) );
		$parser->SetImportDirs(
			array_fill_keys( $this->config->get( 'ResourceLoaderLESSImportPaths' ), '' )
		);
		$parser->SetOption( 'relativeUrls', false );
		$parser->SetCacheDir( $this->config->get( 'CacheDirectory' ) ?: wfTempDir() );

		return $parser;
	}

	/**
	 * Get global LESS variables.
	 *
	 * @since 1.27
	 * @return array Map of variable names to string CSS values.
	 */
	public function getLessVars() {
		if ( !$this->lessVars ) {
			$lessVars = $this->config->get( 'ResourceLoaderLESSVars' );
			Hooks::run( 'ResourceLoaderGetLessVars', [ &$lessVars ] );
			$this->lessVars = $lessVars;
		}
		return $this->lessVars;
	}
}
