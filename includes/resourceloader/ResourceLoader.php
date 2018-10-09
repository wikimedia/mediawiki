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

use MediaWiki\MediaWikiServices;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Wikimedia\Rdbms\DBConnectionError;
use Wikimedia\WrappedString;

/**
 * Dynamic JavaScript and CSS resource loading system.
 *
 * Most of the documentation is on the MediaWiki documentation wiki starting at:
 *    https://www.mediawiki.org/wiki/ResourceLoader
 */
class ResourceLoader implements LoggerAwareInterface {
	/** @var int */
	const CACHE_VERSION = 8;

	/** @var bool */
	protected static $debugMode = null;

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
	protected $config;

	/**
	 * Associative array mapping framework ids to a list of names of test suite modules
	 * like [ 'qunit' => [ 'mediawiki.tests.qunit.suites', 'ext.foo.tests', ... ], ... ]
	 * @var array
	 */
	protected $testModuleNames = [];

	/**
	 * E.g. [ 'source-id' => 'http://.../load.php' ]
	 * @var array
	 */
	protected $sources = [];

	/**
	 * Errors accumulated during current respond() call.
	 * @var array
	 */
	protected $errors = [];

	/**
	 * List of extra HTTP response headers provided by loaded modules.
	 *
	 * Populated by makeModuleResponse().
	 *
	 * @var array
	 */
	protected $extraHeaders = [];

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
		$dbr = wfGetDB( DB_REPLICA );
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
					json_decode( $row->md_deps, true )
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

		// Batched version of ResourceLoaderWikiModule::getTitleInfo
		ResourceLoaderWikiModule::preloadTitleInfo( $context, $dbr, $moduleNames );

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
		if ( strpos( $data, self::FILTER_NOMIN ) !== false ) {
			return $data;
		}

		if ( isset( $options['cache'] ) && $options['cache'] === false ) {
			return self::applyFilter( $filter, $data );
		}

		$stats = MediaWikiServices::getInstance()->getStatsdDataFactory();
		$cache = ObjectCache::getLocalServerInstance( CACHE_ANYTHING );

		$key = $cache->makeGlobalKey(
			'resourceloader',
			'filter',
			$filter,
			self::CACHE_VERSION,
			md5( $data )
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

	/**
	 * Register core modules and runs registration hooks.
	 * @param Config|null $config [optional]
	 * @param LoggerInterface|null $logger [optional]
	 */
	public function __construct( Config $config = null, LoggerInterface $logger = null ) {
		global $IP;

		$this->logger = $logger ?: new NullLogger();

		if ( !$config ) {
			$this->logger->debug( __METHOD__ . ' was called without providing a Config instance' );
			$config = MediaWikiServices::getInstance()->getMainConfig();
		}
		$this->config = $config;

		// Add 'local' source first
		$this->addSource( 'local', $config->get( 'LoadScript' ) );

		// Add other sources
		$this->addSource( $config->get( 'ResourceLoaderSources' ) );

		// Register core modules
		$this->register( include "$IP/resources/Resources.php" );
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
	 * @param array|null $info Module info array. For backwards compatibility with 1.17alpha,
	 *   this may also be a ResourceLoaderModule object. Optional when using
	 *   multiple-registration calling style.
	 * @throws MWException If a duplicate module registration is attempted
	 * @throws MWException If a module name contains illegal characters (pipes or commas)
	 * @throws MWException If something other than a ResourceLoaderModule is being registered
	 * @return bool False if there were any errors, in which case one or more modules were
	 *   not registered
	 */
	public function register( $name, $info = null ) {
		$moduleSkinStyles = $this->config->get( 'ResourceModuleSkinStyles' );

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
				foreach ( $moduleSkinStyles as $skinName => $skinStyles ) {
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
	 * @param array|string $id Source ID (string), or [ id1 => loadUrl, id2 => loadUrl, ... ]
	 * @param string|array|null $loadUrl load.php url (string), or array with loadUrl key for
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
			} elseif ( isset( $info['factory'] ) ) {
				$object = call_user_func( $info['factory'], $info );
				$object->setConfig( $this->getConfig() );
				$object->setLogger( $this->logger );
			} else {
				if ( !isset( $info['class'] ) ) {
					$class = ResourceLoaderFileModule::class;
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
	 * Whether the module is a ResourceLoaderFileModule (including subclasses).
	 *
	 * @param string $name Module name
	 * @return bool
	 */
	protected function isFileModule( $name ) {
		if ( !isset( $this->moduleInfos[$name] ) ) {
			return false;
		}
		$info = $this->moduleInfos[$name];
		if ( isset( $info['object'] ) ) {
			return false;
		}
		return (
			// The implied default for 'class' is ResourceLoaderFileModule
			!isset( $info['class'] ) ||
			// Explicit default
			$info['class'] === ResourceLoaderFileModule::class ||
			is_subclass_of( $info['class'], ResourceLoaderFileModule::class )
		);
	}

	/**
	 * Get the list of sources.
	 *
	 * @return array Like [ id => load.php url, ... ]
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
		$hash = hash( 'fnv132', $value );
		return Wikimedia\base_convert( $hash, 16, 36, 7 );
	}

	/**
	 * Add an error to the 'errors' array and log it.
	 *
	 * @private For internal use by ResourceLoader and ResourceLoaderStartUpModule.
	 * @since 1.29
	 * @param Exception $e
	 * @param string $msg
	 * @param array $context
	 */
	public function outputErrorAndLog( Exception $e, $msg, array $context = [] ) {
		MWExceptionHandler::logException( $e );
		$this->logger->warning(
			$msg,
			$context + [ 'exception' => $e ]
		);
		$this->errors[] = self::formatExceptionNoComment( $e );
	}

	/**
	 * Helper method to get and combine versions of multiple modules.
	 *
	 * @since 1.26
	 * @param ResourceLoaderContext $context
	 * @param string[] $moduleNames List of known module names
	 * @return string Hash
	 */
	public function getCombinedVersion( ResourceLoaderContext $context, array $moduleNames ) {
		if ( !$moduleNames ) {
			return '';
		}
		$hashes = array_map( function ( $module ) use ( $context ) {
			try {
				return $this->getModule( $module )->getVersionHash( $context );
			} catch ( Exception $e ) {
				// If modules fail to compute a version, don't fail the request (T152266)
				// and still compute versions of other modules.
				$this->outputErrorAndLog( $e,
					'Calculating version for "{module}" failed: {exception}',
					[
						'module' => $module,
					]
				);
				return '';
			}
		}, $moduleNames );
		return self::makeHash( implode( '', $hashes ) );
	}

	/**
	 * Get the expected value of the 'version' query parameter.
	 *
	 * This is used by respond() to set a short Cache-Control header for requests with
	 * information newer than the current server has. This avoids pollution of edge caches.
	 * Typically during deployment. (T117587)
	 *
	 * This MUST match return value of `mw.loader#getCombinedVersion()` client-side.
	 *
	 * @since 1.28
	 * @param ResourceLoaderContext $context
	 * @return string Hash
	 */
	public function makeVersionQuery( ResourceLoaderContext $context ) {
		// As of MediaWiki 1.28, the server and client use the same algorithm for combining
		// version hashes. There is no technical reason for this to be same, and for years the
		// implementations differed. If getCombinedVersion in PHP (used for StartupModule and
		// E-Tag headers) differs in the future from getCombinedVersion in JS (used for 'version'
		// query parameter), then this method must continue to match the JS one.
		$moduleNames = [];
		foreach ( $context->getModules() as $name ) {
			if ( !$this->getModule( $name ) ) {
				// If a versioned request contains a missing module, the version is a mismatch
				// as the client considered a module (and version) we don't have.
				return '';
			}
			$moduleNames[] = $name;
		}
		return $this->getCombinedVersion( $context, $moduleNames );
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
		// See https://bugs.php.net/bug.php?id=36514
		ob_start();

		$this->measureResponseTime( RequestContext::getMain()->getTiming() );

		// Find out which modules are missing and instantiate the others
		$modules = [];
		$missing = [];
		foreach ( $context->getModules() as $name ) {
			$module = $this->getModule( $name );
			if ( $module ) {
				// Do not allow private modules to be loaded from the web.
				// This is a security issue, see T36907.
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
			$this->outputErrorAndLog( $e, 'Preloading module info failed: {exception}' );
		}

		// Combine versions to propagate cache invalidation
		$versionHash = '';
		try {
			$versionHash = $this->getCombinedVersion( $context, array_keys( $modules ) );
		} catch ( Exception $e ) {
			$this->outputErrorAndLog( $e, 'Calculating version hash failed: {exception}' );
		}

		// See RFC 2616 § 3.11 Entity Tags
		// https://www.w3.org/Protocols/rfc2616/rfc2616-sec3.html#sec3.11
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

		$this->sendResponseHeaders( $context, $etag, (bool)$this->errors, $this->extraHeaders );

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

	protected function measureResponseTime( Timing $timing ) {
		DeferredUpdates::addCallableUpdate( function () use ( $timing ) {
			$measure = $timing->measure( 'responseTime', 'requestStart', 'requestShutdown' );
			if ( $measure !== false ) {
				$stats = MediaWikiServices::getInstance()->getStatsdDataFactory();
				$stats->timing( 'resourceloader.responseTime', $measure['duration'] * 1000 );
			}
		} );
	}

	/**
	 * Send main response headers to the client.
	 *
	 * Deals with Content-Type, CORS (for stylesheets), and caching.
	 *
	 * @param ResourceLoaderContext $context
	 * @param string $etag ETag header value
	 * @param bool $errors Whether there are errors in the response
	 * @param string[] $extra Array of extra HTTP response headers
	 * @return void
	 */
	protected function sendResponseHeaders(
		ResourceLoaderContext $context, $etag, $errors, array $extra = []
	) {
		\MediaWiki\HeaderCallback::warnIfHeadersSent();
		$rlMaxage = $this->config->get( 'ResourceLoaderMaxage' );
		// Use a short cache expiry so that updates propagate to clients quickly, if:
		// - No version specified (shared resources, e.g. stylesheets)
		// - There were errors (recover quickly)
		// - Version mismatch (T117587, T47877)
		if ( is_null( $context->getVersion() )
			|| $errors
			|| $context->getVersion() !== $this->makeVersionQuery( $context )
		) {
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
		// https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.19
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
		foreach ( $extra as $header ) {
			header( $header );
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
		// https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.26
		$clientKeys = $context->getRequest()->getHeader( 'If-None-Match', WebRequest::GETHEADER_LIST );
		// Never send 304s in debug mode
		if ( $clientKeys !== false && !$context->getDebug() && in_array( $etag, $clientKeys ) ) {
			// There's another bug in ob_gzhandler (see also the comment at
			// the top of this function) that causes it to gzip even empty
			// responses, meaning it's impossible to produce a truly empty
			// response (because the gzip header is always there). This is
			// a problem because 304 responses have to be completely empty
			// per the HTTP spec, and Firefox behaves buggily when they're not.
			// See also https://bugs.php.net/bug.php?id=51579
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
				wfGetDB( DB_REPLICA );
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

		return MWExceptionHandler::getLogMessage( $e ) .
			"\nBacktrace:\n" .
			MWExceptionHandler::getRedactedTraceAsString( $e );
	}

	/**
	 * Generate code for a response.
	 *
	 * Calling this method also populates the `errors` and `headers` members,
	 * later used by respond().
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
				$implementKey = $name . '@' . $module->getVersionHash( $context );
				$strContent = '';

				if ( isset( $content['headers'] ) ) {
					$this->extraHeaders = array_merge( $this->extraHeaders, $content['headers'] );
				}

				// Append output
				switch ( $context->getOnly() ) {
					case 'scripts':
						$scripts = $content['scripts'];
						if ( is_string( $scripts ) ) {
							// Load scripts raw...
							$strContent = $scripts;
						} elseif ( is_array( $scripts ) ) {
							// ...except when $scripts is an array of URLs
							$strContent = self::makeLoaderImplementScript( $implementKey, $scripts, [], [], [] );
						}
						break;
					case 'styles':
						$styles = $content['styles'];
						// We no longer separate into media, they are all combined now with
						// custom media type groups into @media .. {} sections as part of the css string.
						// Module returns either an empty array or a numerical array with css strings.
						$strContent = isset( $styles['css'] ) ? implode( '', $styles['css'] ) : '';
						break;
					default:
						$scripts = $content['scripts'] ?? '';
						if ( is_string( $scripts ) ) {
							if ( $name === 'site' || $name === 'user' ) {
								// Legacy scripts that run in the global scope without a closure.
								// mw.loader.implement will use globalEval if scripts is a string.
								// Minify manually here, because general response minification is
								// not effective due it being a string literal, not a function.
								if ( !self::inDebugMode() ) {
									$scripts = self::filter( 'minify-js', $scripts ); // T107377
								}
							} else {
								$scripts = new XmlJsCode( $scripts );
							}
						}
						$strContent = self::makeLoaderImplementScript(
							$implementKey,
							$scripts,
							$content['styles'] ?? [],
							isset( $content['messagesBlob'] ) ? new XmlJsCode( $content['messagesBlob'] ) : [],
							$content['templates'] ?? []
						);
						break;
				}

				if ( !$context->getDebug() ) {
					$strContent = self::filter( $filter, $strContent );
				}

				if ( $context->getOnly() === 'scripts' ) {
					// Use a linebreak between module scripts (T162719)
					$out .= $this->ensureNewline( $strContent );
				} else {
					$out .= $strContent;
				}

			} catch ( Exception $e ) {
				$this->outputErrorAndLog( $e, 'Generating module package failed: {exception}' );

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
				// Use a linebreak between module script and state script (T162719)
				$out = $this->ensureNewline( $out ) . $stateScript;
			}
		} else {
			if ( $states ) {
				$this->errors[] = 'Problematic modules: '
					. self::encodeJsonForScript( $states );
			}
		}

		return $out;
	}

	/**
	 * Ensure the string is either empty or ends in a line break
	 * @param string $str
	 * @return string
	 */
	private function ensureNewline( $str ) {
		$end = substr( $str, -1 );
		if ( $end === false || $end === "\n" ) {
			return $str;
		}
		return $str . "\n";
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

	/**
	 * Return JS code that calls mw.loader.implement with given module properties.
	 *
	 * @param string $name Module name or implement key (format "`[name]@[version]`")
	 * @param XmlJsCode|array|string $scripts Code as XmlJsCode (to be wrapped in a closure),
	 *  list of URLs to JavaScript files, or a string of JavaScript for `$.globalEval`.
	 * @param mixed $styles Array of CSS strings keyed by media type, or an array of lists of URLs
	 *   to CSS files keyed by media type
	 * @param mixed $messages List of messages associated with this module. May either be an
	 *   associative array mapping message key to value, or a JSON-encoded message blob containing
	 *   the same data, wrapped in an XmlJsCode object.
	 * @param array $templates Keys are name of templates and values are the source of
	 *   the template.
	 * @throws MWException
	 * @return string JavaScript code
	 */
	protected static function makeLoaderImplementScript(
		$name, $scripts, $styles, $messages, $templates
	) {
		if ( $scripts instanceof XmlJsCode ) {
			if ( self::inDebugMode() ) {
				$scripts = new XmlJsCode( "function ( $, jQuery, require, module ) {\n{$scripts->value}\n}" );
			} else {
				$scripts = new XmlJsCode( 'function($,jQuery,require,module){' . $scripts->value . '}' );
			}
		} elseif ( !is_string( $scripts ) && !is_array( $scripts ) ) {
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

		return Xml::encodeJsCall( 'mw.loader.implement', $module, self::inDebugMode() );
	}

	/**
	 * Returns JS code which, when called, will register a given list of messages.
	 *
	 * @param mixed $messages Either an associative array mapping message key to value, or a
	 *   JSON-encoded message blob containing the same data, wrapped in an XmlJsCode object.
	 * @return string JavaScript code
	 */
	public static function makeMessageSetScript( $messages ) {
		return Xml::encodeJsCall(
			'mw.messages.set',
			[ (object)$messages ],
			self::inDebugMode()
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
				// Don't output an empty "@media print { }" block (T42498)
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
	 * Wrapper around json_encode that avoids needless escapes,
	 * and pretty-prints in debug mode.
	 *
	 * @internal
	 * @since 1.32
	 * @param bool|string|array $data
	 * @return string JSON
	 */
	public static function encodeJsonForScript( $data ) {
		// Keep output as small as possible by disabling needless escape modes
		// that PHP uses by default.
		// However, while most module scripts are only served on HTTP responses
		// for JavaScript, some modules can also be embedded in the HTML as inline
		// scripts. This, and the fact that we sometimes need to export strings
		// containing user-generated content and labels that may genuinely contain
		// a sequences like "</script>", we need to encode either '/' or '<'.
		// By default PHP escapes '/'. Let's escape '<' instead which is less common
		// and allows URLs to mostly remain readable.
		$jsonFlags = JSON_UNESCAPED_SLASHES |
			JSON_UNESCAPED_UNICODE |
			JSON_HEX_TAG |
			JSON_HEX_AMP;
		if ( self::inDebugMode() ) {
			$jsonFlags |= JSON_PRETTY_PRINT;
		}
		return json_encode( $data, $jsonFlags );
	}

	/**
	 * Returns a JS call to mw.loader.state, which sets the state of one
	 * ore more modules to a given value. Has two calling conventions:
	 *
	 *    - ResourceLoader::makeLoaderStateScript( $name, $state ):
	 *         Set the state of a single module called $name to $state
	 *
	 *    - ResourceLoader::makeLoaderStateScript( [ $name => $state, ... ] ):
	 *         Set the state of modules with the given names to the given states
	 *
	 * @param array|string $states
	 * @param string|null $state
	 * @return string JavaScript code
	 */
	public static function makeLoaderStateScript( $states, $state = null ) {
		if ( !is_array( $states ) ) {
			$states = [ $states => $state ];
		}
		return Xml::encodeJsCall(
			'mw.loader.state',
			[ $states ],
			self::inDebugMode()
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
	 * - []
	 * - new XmlJsCode( '{}' )
	 * - new stdClass() // (object) []
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
	 * parameter.
	 *
	 * @par Example
	 * @code
	 *
	 *     ResourceLoader::makeLoaderRegisterScript( [
	 *        [ $name1, $version1, $dependencies1, $group1, $source1, $skip1 ],
	 *        [ $name2, $version2, $dependencies1, $group2, $source2, $skip2 ],
	 *        ...
	 *     ] ):
	 * @endcode
	 *
	 * @internal
	 * @since 1.32
	 * @param array $modules Array of module registration arrays, each containing
	 *  - string: module name
	 *  - string: module version
	 *  - array|null: List of dependencies (optional)
	 *  - string|null: Module group (optional)
	 *  - string|null: Name of foreign module source, or 'local' (optional)
	 *  - string|null: Script body of a skip function (optional)
	 * @return string JavaScript code
	 */
	public static function makeLoaderRegisterScript( array $modules ) {
		// Optimisation: Transform dependency names into indexes when possible
		// to produce smaller output. They are expanded by mw.loader.register on
		// the other end using resolveIndexedDependencies().
		$index = [];
		foreach ( $modules as $i => &$module ) {
			// Build module name index
			$index[$module[0]] = $i;
		}
		foreach ( $modules as &$module ) {
			if ( isset( $module[2] ) ) {
				foreach ( $module[2] as &$dependency ) {
					if ( isset( $index[$dependency] ) ) {
						// Replace module name in dependency list with index
						$dependency = $index[$dependency];
					}
				}
			}
		}

		array_walk( $modules, [ 'self', 'trimArray' ] );

		return Xml::encodeJsCall(
			'mw.loader.register',
			[ $modules ],
			self::inDebugMode()
		);
	}

	/**
	 * Returns JS code which calls mw.loader.addSource() with the given
	 * parameters. Has two calling conventions:
	 *
	 *   - ResourceLoader::makeLoaderSourcesScript( $id, $properties ):
	 *       Register a single source
	 *
	 *   - ResourceLoader::makeLoaderSourcesScript( [ $id1 => $loadUrl, $id2 => $loadUrl, ... ] );
	 *       Register sources with the given IDs and properties.
	 *
	 * @param string|array $sources Source ID
	 * @param string|null $loadUrl load.php url
	 * @return string JavaScript code
	 */
	public static function makeLoaderSourcesScript( $sources, $loadUrl = null ) {
		if ( !is_array( $sources ) ) {
			$sources = [ $sources => $loadUrl ];
		}
		return Xml::encodeJsCall(
			'mw.loader.addSource',
			[ $sources ],
			self::inDebugMode()
		);
	}

	/**
	 * Wraps JavaScript code to run after the startup module.
	 *
	 * @param string $script JavaScript code
	 * @return string JavaScript code
	 */
	public static function makeLoaderConditionalScript( $script ) {
		// Adds a function to lazy-created RLQ
		return '(window.RLQ=window.RLQ||[]).push(function(){' .
			trim( $script ) . '});';
	}

	/**
	 * Wraps JavaScript code to run after a required module.
	 *
	 * @since 1.32
	 * @param string|string[] $modules Module name(s)
	 * @param string $script JavaScript code
	 * @return string JavaScript code
	 */
	public static function makeInlineCodeWithModule( $modules, $script ) {
		// Adds an array to lazy-created RLQ
		return '(window.RLQ=window.RLQ||[]).push(['
			. self::encodeJsonForScript( $modules ) . ','
			. 'function(){' . trim( $script ) . '}'
			. ']);';
	}

	/**
	 * Returns an HTML script tag that runs given JS code after startup and base modules.
	 *
	 * The code will be wrapped in a closure, and it will be executed by ResourceLoader's
	 * startup module if the client has adequate support for MediaWiki JavaScript code.
	 *
	 * @param string $script JavaScript code
	 * @param string|null $nonce [optional] Content-Security-Policy nonce
	 *  (from OutputPage::getCSPNonce)
	 * @return string|WrappedString HTML
	 */
	public static function makeInlineScript( $script, $nonce = null ) {
		$js = self::makeLoaderConditionalScript( $script );
		$escNonce = '';
		if ( $nonce === null ) {
			wfWarn( __METHOD__ . " did not get nonce. Will break CSP" );
		} elseif ( $nonce !== false ) {
			// If it was false, CSP is disabled, so no nonce attribute.
			// Nonce should be only base64 characters, so should be safe,
			// but better to be safely escaped than sorry.
			$escNonce = ' nonce="' . htmlspecialchars( $nonce ) . '"';
		}

		return new WrappedString(
			Html::inlineScript( $js, $nonce ),
			"<script$escNonce>(window.RLQ=window.RLQ||[]).push(function(){",
			'});</script>'
		);
	}

	/**
	 * Returns JS code which will set the MediaWiki configuration array to
	 * the given value.
	 *
	 * @param array $configuration List of configuration values keyed by variable name
	 * @return string JavaScript code
	 * @throws Exception
	 */
	public static function makeConfigSetScript( array $configuration ) {
		$js = Xml::encodeJsCall(
			'mw.config.set',
			[ $configuration ],
			self::inDebugMode()
		);
		if ( $js === false ) {
			throw new Exception(
				'JSON serialization of config data failed. ' .
				'This usually means the config data is not valid UTF-8.'
			);
		}
		return $js;
	}

	/**
	 * Convert an array of module names to a packed query string.
	 *
	 * For example, `[ 'foo.bar', 'foo.baz', 'bar.baz', 'bar.quux' ]`
	 * becomes `'foo.bar,baz|bar.baz,quux'`.
	 *
	 * This process is reversed by ResourceLoaderContext::expandModuleNames().
	 * See also mw.loader#buildModulesString() which is a port of this, used
	 * on the client-side.
	 *
	 * @param array $modules List of module names (strings)
	 * @return string Packed query string
	 */
	public static function makePackedModulesString( $modules ) {
		$moduleMap = []; // [ prefix => [ suffixes ] ]
		foreach ( $modules as $module ) {
			$pos = strrpos( $module, '.' );
			$prefix = $pos === false ? '' : substr( $module, 0, $pos );
			$suffix = $pos === false ? $module : substr( $module, $pos + 1 );
			$moduleMap[$prefix][] = $suffix;
		}

		$arr = [];
		foreach ( $moduleMap as $prefix => $suffixes ) {
			$p = $prefix === '' ? '' : $prefix . '.';
			$arr[] = $p . implode( ',', $suffixes );
		}
		return implode( '|', $arr );
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
	 *
	 * @internal For use by unit tests
	 * @codeCoverageIgnore
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
	 * Helper for createLoaderURL()
	 *
	 * @since 1.24
	 * @see makeLoaderQuery
	 * @param ResourceLoaderContext $context
	 * @param array $extraQuery
	 * @return array
	 */
	protected static function createLoaderQuery( ResourceLoaderContext $context, $extraQuery = [] ) {
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
	 * function for createLoaderURL().
	 *
	 * @param array $modules
	 * @param string $lang
	 * @param string $skin
	 * @param string|null $user
	 * @param string|null $version
	 * @param bool $debug
	 * @param string|null $only
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
	 * @param array $vars Associative array of variables that should be used
	 *  for compilation. Since 1.32, this method no longer automatically includes
	 *  global LESS vars from ResourceLoader::getLessVars (T191937).
	 * @throws MWException
	 * @return Less_Parser
	 */
	public function getLessCompiler( $vars = [] ) {
		global $IP;
		// When called from the installer, it is possible that a required PHP extension
		// is missing (at least for now; see T49564). If this is the case, throw an
		// exception (caught by the installer) to prevent a fatal error later on.
		if ( !class_exists( 'Less_Parser' ) ) {
			throw new MWException( 'MediaWiki requires the less.php parser' );
		}

		$parser = new Less_Parser;
		$parser->ModifyVars( $vars );
		$parser->SetImportDirs( [
			"$IP/resources/src/mediawiki.less/" => '',
		] );
		$parser->SetOption( 'relativeUrls', false );

		return $parser;
	}

	/**
	 * Get global LESS variables.
	 *
	 * @since 1.27
	 * @deprecated since 1.32 Use ResourceLoderModule::getLessVars() instead.
	 * @return array Map of variable names to string CSS values.
	 */
	public function getLessVars() {
		return [];
	}
}
