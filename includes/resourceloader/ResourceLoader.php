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

use MediaWiki\HeaderCallback;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\MediaWikiServices;
use MediaWiki\ResourceLoader\HookRunner;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Wikimedia\DependencyStore\DependencyStore;
use Wikimedia\DependencyStore\KeyValueDependencyStore;
use Wikimedia\Rdbms\DBConnectionError;
use Wikimedia\Timestamp\ConvertibleTimestamp;
use Wikimedia\WrappedString;

/**
 * @defgroup ResourceLoader ResourceLoader
 *
 * For higher level documentation, see <https://www.mediawiki.org/wiki/ResourceLoader/Architecture>.
 */

/**
 * @defgroup ResourceLoaderHooks ResourceLoader Hooks
 * @ingroup ResourceLoader
 * @ingroup Hooks
 */

/**
 * ResourceLoader is a loading system for JavaScript and CSS resources.
 *
 * For higher level documentation, see <https://www.mediawiki.org/wiki/ResourceLoader/Architecture>.
 *
 * @ingroup ResourceLoader
 * @since 1.17
 */
class ResourceLoader implements LoggerAwareInterface {
	/** @var Config */
	protected $config;
	/** @var MessageBlobStore */
	protected $blobStore;
	/** @var DependencyStore */
	protected $depStore;

	/** @var LoggerInterface */
	private $logger;

	/** @var HookContainer */
	private $hookContainer;

	/** @var HookRunner */
	private $hookRunner;

	/** @var ResourceLoaderModule[] Map of (module name => ResourceLoaderModule) */
	protected $modules = [];
	/** @var array[] Map of (module name => associative info array) */
	protected $moduleInfos = [];
	/**
	 * Associative array mapping framework ids to a list of names of test suite modules
	 * like [ 'qunit' => [ 'mediawiki.tests.qunit.suites', 'ext.foo.tests', ... ], ... ]
	 * @var array
	 */
	protected $testModuleNames = [];
	/** @var string[] List of module names that contain QUnit test suites */
	protected $testSuiteModuleNames = [];
	/** @var array Map of (source => path); E.g. [ 'source-id' => 'http://.../load.php' ] */
	protected $sources = [];
	/** @var array Errors accumulated during current respond() call */
	protected $errors = [];
	/** @var string[] Extra HTTP response headers from modules loaded in makeModuleResponse() */
	protected $extraHeaders = [];

	/** @var array Map of (module-variant => buffered DependencyStore updates) */
	private $depStoreUpdateBuffer = [];

	/** @var array Styles that are skin-specific and supplement or replace the
	 * default skinStyles of a FileModule. See $wgResourceModuleSkinStyles.
	 */
	private $moduleSkinStyles = [];

	/** @var bool */
	protected static $debugMode = null;

	/** @var int */
	public const CACHE_VERSION = 8;

	/** @var string */
	private const RL_DEP_STORE_PREFIX = 'ResourceLoaderModule';
	/** @var int Expiry (in seconds) of indirect dependency information for modules */
	private const RL_MODULE_DEP_TTL = BagOStuff::TTL_WEEK;

	/** @var string JavaScript / CSS pragma to disable minification. * */
	public const FILTER_NOMIN = '/*@nomin*/';

	/**
	 * Load information stored in the database and dependency tracking store about modules
	 *
	 * @param string[] $moduleNames Module names
	 * @param ResourceLoaderContext $context ResourceLoader-specific context of the request
	 */
	public function preloadModuleInfo( array $moduleNames, ResourceLoaderContext $context ) {
		// Load all tracked indirect file dependencies for the modules
		$vary = ResourceLoaderModule::getVary( $context );
		$entitiesByModule = [];
		foreach ( $moduleNames as $moduleName ) {
			$entitiesByModule[$moduleName] = "$moduleName|$vary";
		}
		$depsByEntity = $this->depStore->retrieveMulti(
			self::RL_DEP_STORE_PREFIX,
			$entitiesByModule
		);
		// Inject the indirect file dependencies for all the modules
		foreach ( $moduleNames as $moduleName ) {
			$module = $this->getModule( $moduleName );
			if ( $module ) {
				$entity = $entitiesByModule[$moduleName];
				$deps = $depsByEntity[$entity];
				$paths = ResourceLoaderModule::expandRelativePaths( $deps['paths'] );
				$module->setFileDependencies( $context, $paths );
			}
		}

		// Batched version of ResourceLoaderWikiModule::getTitleInfo
		$dbr = wfGetDB( DB_REPLICA );
		ResourceLoaderWikiModule::preloadTitleInfo( $context, $dbr, $moduleNames );

		// Prime in-object cache for message blobs for modules with messages
		$modulesWithMessages = [];
		foreach ( $moduleNames as $moduleName ) {
			$module = $this->getModule( $moduleName );
			if ( $module && $module->getMessages() ) {
				$modulesWithMessages[$moduleName] = $module;
			}
		}
		// Prime in-object cache for message blobs for modules with messages
		$lang = $context->getLanguage();
		$store = $this->getMessageBlobStore();
		$blobs = $store->getBlobs( $modulesWithMessages, $lang );
		foreach ( $blobs as $moduleName => $blob ) {
			$modulesWithMessages[$moduleName]->setMessageBlob( $blob, $lang );
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
	 * @param array<string,bool> $options Keys:
	 *  - (bool) cache: Whether to allow caching this data. Default: true.
	 * @return string Filtered data or unfiltered data
	 */
	public static function filter( $filter, $data, array $options = [] ) {
		if ( strpos( $data, self::FILTER_NOMIN ) !== false ) {
			return $data;
		}

		if ( isset( $options['cache'] ) && $options['cache'] === false ) {
			return self::applyFilter( $filter, $data ) ?? $data;
		}

		$stats = MediaWikiServices::getInstance()->getStatsdDataFactory();
		$cache = ObjectCache::getLocalServerInstance( CACHE_ANYTHING );

		$key = $cache->makeGlobalKey(
			'resourceloader-filter',
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

	/**
	 * @param string $filter
	 * @param string $data
	 * @return string|null
	 */
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
	 * @param Config|null $config
	 * @param LoggerInterface|null $logger [optional]
	 * @param DependencyStore|null $tracker [optional]
	 */
	public function __construct(
		Config $config = null,
		LoggerInterface $logger = null,
		DependencyStore $tracker = null
	) {
		$this->logger = $logger ?: new NullLogger();
		$services = MediaWikiServices::getInstance();

		if ( !$config ) {
			wfDeprecated( __METHOD__ . ' without a Config instance', '1.34' );
			$config = $services->getMainConfig();
		}
		$this->config = $config;

		$this->hookContainer = $services->getHookContainer();
		$this->hookRunner = new HookRunner( $this->hookContainer );

		// Add 'local' source first
		$this->addSource( 'local', $config->get( 'LoadScript' ) );

		// Special module that always exists
		$this->register( 'startup', [ 'class' => ResourceLoaderStartUpModule::class ] );

		$this->setMessageBlobStore(
			new MessageBlobStore( $this, $this->logger, $services->getMainWANObjectCache() )
		);

		$tracker = $tracker ?: new KeyValueDependencyStore( new HashBagOStuff() );
		$this->setDependencyStore( $tracker );
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
	 * @since 1.35
	 * @param DependencyStore $tracker
	 */
	public function setDependencyStore( DependencyStore $tracker ) {
		$this->depStore = $tracker;
	}

	/**
	 * @internal For use by ServiceWiring.php
	 * @param array $moduleSkinStyles
	 */
	public function setModuleSkinStyles( array $moduleSkinStyles ) {
		$this->moduleSkinStyles = $moduleSkinStyles;
	}

	/**
	 * Register a module with the ResourceLoader system.
	 *
	 * @param string|array[] $name Module name as a string or, array of module info arrays
	 *  keyed by name.
	 * @param array|null $info Module info array. When using the first parameter to register
	 *  multiple modules at once, this parameter is optional.
	 * @throws InvalidArgumentException If a module name contains illegal characters (pipes or commas)
	 * @throws InvalidArgumentException If the module info is not an array
	 */
	public function register( $name, array $info = null ) {
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

			// Check validity
			if ( !self::isValidModuleName( $name ) ) {
				throw new InvalidArgumentException( "ResourceLoader module name '$name' is invalid, "
					. "see ResourceLoader::isValidModuleName()" );
			}
			if ( !is_array( $info ) ) {
				throw new InvalidArgumentException(
					'Invalid module info for "' . $name . '": expected array, got ' . gettype( $info )
				);
			}

			// Attach module
			$this->moduleInfos[$name] = $info;

			// Last-minute changes
			// Apply custom skin-defined styles to existing modules.
			if ( $this->isFileModule( $name ) ) {
				foreach ( $this->moduleSkinStyles as $skinName => $skinStyles ) {
					// If this module already defines skinStyles for this skin, ignore ResourceModuleSkinStyles.
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
	 * @internal For use by ServiceWiring only
	 * @codeCoverageIgnore
	 */
	public function registerTestModules() : void {
		global $IP;

		if ( $this->config->get( 'EnableJavaScriptTest' ) !== true ) {
			throw new MWException( 'Attempt to register JavaScript test modules '
				. 'but <code>$wgEnableJavaScriptTest</code> is false. '
				. 'Edit your <code>LocalSettings.php</code> to enable it.' );
		}

		// This has a 'qunit' key for compat with the below hook.
		$testModulesMeta = [ 'qunit' => [] ];

		$this->hookRunner->onResourceLoaderTestModules( $testModulesMeta, $this );
		$extRegistry = ExtensionRegistry::getInstance();
		// In case of conflict, the deprecated hook has precedence.
		$testModules = $testModulesMeta['qunit']
			+ $extRegistry->getAttribute( 'QUnitTestModules' );

		$testSuiteModuleNames = [];
		foreach ( $testModules as $name => &$module ) {
			// Turn any single-module dependency into an array
			if ( isset( $module['dependencies'] ) && is_string( $module['dependencies'] ) ) {
				$module['dependencies'] = [ $module['dependencies'] ];
			}

			// Ensure the testrunner loads before any test suites
			$module['dependencies'][] = 'mediawiki.qunit-testrunner';

			// Keep track of the test suites to load on SpecialJavaScriptTest
			$testSuiteModuleNames[] = $name;
		}

		// Core test suites (their names have further precedence).
		$testModules = ( include "$IP/tests/qunit/QUnitTestResources.php" ) + $testModules;
		$testSuiteModuleNames[] = 'test.MediaWiki';

		$this->register( $testModules );
		$this->testSuiteModuleNames = $testSuiteModuleNames;
	}

	/**
	 * Add a foreign source of modules.
	 *
	 * Source IDs are typically the same as the Wiki ID or database name (e.g. lowercase a-z).
	 *
	 * @param array|string $sources Source ID (string), or [ id1 => loadUrl, id2 => loadUrl, ... ]
	 * @param string|array|null $loadUrl load.php url (string), or array with loadUrl key for
	 *  backwards-compatibility.
	 * @throws InvalidArgumentException If array-form $loadUrl lacks a 'loadUrl' key.
	 */
	public function addSource( $sources, $loadUrl = null ) {
		if ( !is_array( $sources ) ) {
			$sources = [ $sources => $loadUrl ];
		}
		foreach ( $sources as $id => $source ) {
			// Disallow duplicates
			if ( isset( $this->sources[$id] ) ) {
				throw new RuntimeException( 'Cannot register source ' . $id . ' twice' );
			}

			// Support: MediaWiki 1.24 and earlier
			if ( is_array( $source ) ) {
				if ( !isset( $source['loadScript'] ) ) {
					throw new InvalidArgumentException( 'Each source must have a "loadScript" key' );
				}
				$source = $source['loadScript'];
			}

			$this->sources[$id] = $source;
		}
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
	 * Get a list of module names with QUnit test suites.
	 *
	 * @internal For use by SpecialJavaScriptTest only
	 * @return array
	 * @codeCoverageIgnore
	 */
	public function getTestSuiteModuleNames() {
		return $this->testSuiteModuleNames;
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
			// Construct the requested module object
			$info = $this->moduleInfos[$name];
			if ( isset( $info['factory'] ) ) {
				/** @var ResourceLoaderModule $object */
				$object = call_user_func( $info['factory'], $info );
			} else {
				$class = $info['class'] ?? ResourceLoaderFileModule::class;
				/** @var ResourceLoaderModule $object */
				$object = new $class( $info );
			}
			$object->setConfig( $this->getConfig() );
			$object->setLogger( $this->logger );
			$object->setHookContainer( $this->hookContainer );
			$object->setName( $name );
			$object->setDependencyAccessCallbacks(
				[ $this, 'loadModuleDependenciesInternal' ],
				[ $this, 'saveModuleDependenciesInternal' ]
			);
			$this->modules[$name] = $object;
		}

		return $this->modules[$name];
	}

	/**
	 * @internal Exposed for letting getModule() pass the callable to DependencyStore
	 * @param string $moduleName Module name
	 * @param string $variant Language/skin variant
	 * @return string[] List of absolute file paths
	 */
	public function loadModuleDependenciesInternal( $moduleName, $variant ) {
		$deps = $this->depStore->retrieve( self::RL_DEP_STORE_PREFIX, "$moduleName|$variant" );

		return ResourceLoaderModule::expandRelativePaths( $deps['paths'] );
	}

	/**
	 * @internal Exposed for letting getModule() pass the callable to DependencyStore
	 * @param string $moduleName Module name
	 * @param string $variant Language/skin variant
	 * @param string[] $paths List of relative paths referenced during computation
	 * @param string[] $priorPaths List of relative paths tracked in the dependency store
	 */
	public function saveModuleDependenciesInternal( $moduleName, $variant, $paths, $priorPaths ) {
		$hasPendingUpdate = (bool)$this->depStoreUpdateBuffer;
		$entity = "$moduleName|$variant";

		if ( array_diff( $paths, $priorPaths ) || array_diff( $priorPaths, $paths ) ) {
			// Dependency store needs to be updated with the new path list
			if ( $paths ) {
				$deps = $this->depStore->newEntityDependencies( $paths, time() );
				$this->depStoreUpdateBuffer[$entity] = $deps;
			} else {
				$this->depStoreUpdateBuffer[$entity] = null;
			}
		} elseif ( $priorPaths ) {
			// Dependency store needs to store the existing path list for longer
			$this->depStoreUpdateBuffer[$entity] = '*';
		}

		// Use a DeferrableUpdate to flush the buffered dependency updates...
		if ( !$hasPendingUpdate ) {
			DeferredUpdates::addCallableUpdate( function () {
				$updatesByEntity = $this->depStoreUpdateBuffer;
				$this->depStoreUpdateBuffer = []; // consume
				$cache = ObjectCache::getLocalClusterInstance();

				$scopeLocks = [];
				$depsByEntity = [];
				$entitiesUnreg = [];
				$entitiesRenew = [];
				foreach ( $updatesByEntity as $entity => $update ) {
					$lockKey = $cache->makeKey( 'rl-deps', $entity );
					$scopeLocks[$entity] = $cache->getScopedLock( $lockKey, 0 );
					if ( !$scopeLocks[$entity] ) {
						// avoid duplicate write request slams (T124649)
						// the lock must be specific to the current wiki (T247028)
						continue;
					} elseif ( $update === null ) {
						$entitiesUnreg[] = $entity;
					} elseif ( $update === '*' ) {
						$entitiesRenew[] = $entity;
					} else {
						$depsByEntity[$entity] = $update;
					}
				}

				$ttl = self::RL_MODULE_DEP_TTL;
				$this->depStore->storeMulti( self::RL_DEP_STORE_PREFIX, $depsByEntity, $ttl );
				$this->depStore->remove( self::RL_DEP_STORE_PREFIX, $entitiesUnreg );
				$this->depStore->renew( self::RL_DEP_STORE_PREFIX, $entitiesRenew, $ttl );
			} );
		}
	}

	/**
	 * Whether the module is a ResourceLoaderFileModule or subclass thereof.
	 *
	 * @param string $name Module name
	 * @return bool
	 */
	protected function isFileModule( $name ) {
		if ( !isset( $this->moduleInfos[$name] ) ) {
			return false;
		}
		$info = $this->moduleInfos[$name];
		return !isset( $info['factory'] ) && (
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
	 * Get the URL to the load.php endpoint for the given ResourceLoader source.
	 *
	 * @since 1.24
	 * @param string $source Source ID
	 * @return string
	 * @throws UnexpectedValueException If the source ID was not registered
	 */
	public function getLoadScript( $source ) {
		if ( !isset( $this->sources[$source] ) ) {
			throw new UnexpectedValueException( "Unknown source '$source'" );
		}
		return $this->sources[$source];
	}

	/**
	 * @internal For use by ResourceLoaderStartUpModule only.
	 */
	public const HASH_LENGTH = 5;

	/**
	 * Create a hash for module versioning purposes.
	 *
	 * This hash is used in three ways:
	 *
	 * - To differentiate between the current version and a past version
	 *   of a module by the same name.
	 *
	 *   In the cache key of localStorage in the browser (mw.loader.store).
	 *   This store keeps only one version of any given module. As long as the
	 *   next version the client encounters has a different hash from the last
	 *   version it saw, it will correctly discard it in favour of a network fetch.
	 *
	 *   A browser may evict a site's storage container for any reason (e.g. when
	 *   the user hasn't visited a site for some time, and/or when the device is
	 *   low on storage space). Anecdotally it seems devices rarely keep unused
	 *   storage beyond 2 weeks on mobile devices and 4 weeks on desktop.
	 *   But, there is no hard limit or expiration on localStorage.
	 *   ResourceLoader's Client also clears localStorage when the user changes
	 *   their language preference or when they (temporarily) use Debug Mode.
	 *
	 *   The only hard factors that reduce the range of possible versions are
	 *   1) the name and existence of a given module, and
	 *   2) the TTL for mw.loader.store, and
	 *   3) the `$wgResourceLoaderStorageVersion` configuration variable.
	 *
	 * - To identify a batch response of modules from load.php in an HTTP cache.
	 *
	 *   When fetching modules in a batch from load.php, a combined hash
	 *   is created by the JS code, and appended as query parameter.
	 *
	 *   In cache proxies (e.g. Varnish, Nginx) and in the browser's HTTP cache,
	 *   these urls are used to identify other previously cached responses.
	 *   The range of possible versions a given version has to be unique amongst
	 *   is determined by the maximum duration each response is stored for, which
	 *   is controlled by `$wgResourceLoaderMaxage['versioned']`.
	 *
	 * - To detect race conditions between multiple web servers in a MediaWiki
	 *   deployment of which some have the newer version and some still the older
	 *   version.
	 *
	 *   An HTTP request from a browser for the Startup manifest may be responded
	 *   to by a server with the newer version. The browser may then use that to
	 *   request a given module, which may then be responded to by a server with
	 *   the older version. To avoid caching this for too long (which would pollute
	 *   all other users without repairing itself), the combined hash that the JS
	 *   client adds to the url is verified by the server (in ::sendResponseHeaders).
	 *   If they don't match, we instruct cache proxies and clients to not cache
	 *   this response as long as they normally would. This is also the reason
	 *   that the algorithm used here in PHP must match the one used in JS.
	 *
	 * The fnv132 digest creates a 32-bit integer, which goes upto 4 Giga and
	 * needs up to 7 chars in base 36.
	 * Within 7 characters, base 36 can count up to 78,364,164,096 (78 Giga),
	 * (but with fnv132 we'd use very little of this range, mostly padding).
	 * Within 6 characters, base 36 can count up to 2,176,782,336 (2 Giga).
	 * Within 5 characters, base 36 can count up to 60,466,176 (60 Mega).
	 *
	 * @since 1.26
	 * @param string $value
	 * @return string Hash
	 */
	public static function makeHash( $value ) {
		$hash = hash( 'fnv132', $value );
		// The base_convert will pad it (if too short),
		// then substr() will trim it (if too long).
		return substr(
			Wikimedia\base_convert( $hash, 16, 36, self::HASH_LENGTH ),
			0,
			self::HASH_LENGTH
		);
	}

	/**
	 * Add an error to the 'errors' array and log it.
	 *
	 * @internal For use by ResourceLoaderStartUpModule.
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
	 * @param string[]|null $modules
	 * @return string Hash
	 */
	public function makeVersionQuery( ResourceLoaderContext $context, array $modules = null ) {
		if ( $modules === null ) {
			wfDeprecated( __METHOD__ . ' without $modules', '1.34' );
			$modules = $context->getModules();
		}
		// As of MediaWiki 1.28, the server and client use the same algorithm for combining
		// version hashes. There is no technical reason for this to be same, and for years the
		// implementations differed. If getCombinedVersion in PHP (used for StartupModule and
		// E-Tag headers) differs in the future from getCombinedVersion in JS (used for 'version'
		// query parameter), then this method must continue to match the JS one.
		$filtered = [];
		foreach ( $modules as $name ) {
			if ( !$this->getModule( $name ) ) {
				// If a versioned request contains a missing module, the version is a mismatch
				// as the client considered a module (and version) we don't have.
				return '';
			}
			$filtered[] = $name;
		}
		return $this->getCombinedVersion( $context, $filtered );
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
					// Not a serious error, just means something is trying to access it (T101806)
					$this->logger->debug( "Request for private module '$name' denied" );
					$this->errors[] = "Cannot build private module \"$name\"";
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
		} else {
			$fileCache = null;
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

		// Consider saving the response to file cache (unless there are errors).
		if ( $fileCache &&
			!$this->errors &&
			$missing === [] &&
			ResourceFileCache::useFileCache( $context )
		) {
			if ( $fileCache->isCacheWorthy() ) {
				// There were enough hits, save the response to the cache
				$fileCache->saveText( $response );
			} else {
				$fileCache->incrMissesRecent( $context->getRequest() );
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
				$errorResponse .= 'if (window.console && console.error) { console.error('
					. $context->encodeJson( $errorText )
					. "); }\n";
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
	 */
	protected function sendResponseHeaders(
		ResourceLoaderContext $context, $etag, $errors, array $extra = []
	) : void {
		HeaderCallback::warnIfHeadersSent();
		$rlMaxage = $this->config->get( 'ResourceLoaderMaxage' );
		// Use a short cache expiry so that updates propagate to clients quickly, if:
		// - No version specified (shared resources, e.g. stylesheets)
		// - There were errors (recover quickly)
		// - Version mismatch (T117587, T47877)
		if ( $context->getVersion() === null
			|| $errors
			|| $context->getVersion() !== $this->makeVersionQuery( $context, $context->getModules() )
		) {
			$maxage = $rlMaxage['unversioned'];
		// If a version was specified we can use a longer expiry time since changing
		// version numbers causes cache misses
		} else {
			$maxage = $rlMaxage['versioned'];
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
			header( "Cache-Control: public, max-age=$maxage, s-maxage=$maxage" );
			header( 'Expires: ' . ConvertibleTimestamp::convert( TS_RFC2822, time() + $maxage ) );
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
		$maxage = $context->getVersion() === null
			? $rlMaxage['unversioned']
			: $rlMaxage['versioned'];
		// Minimum timestamp the cache file must have
		$minTime = time() - $maxage;
		$good = $fileCache->isCacheGood( ConvertibleTimestamp::convert( TS_MW, $minTime ) );
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
	 * @param Throwable $e Exception to be shown to the user
	 * @return string Sanitized text in a CSS/JS comment that can be returned to the user
	 */
	public static function formatException( Throwable $e ) {
		return self::makeComment( self::formatExceptionNoComment( $e ) );
	}

	/**
	 * Handle exception display.
	 *
	 * @since 1.25
	 * @param Throwable $e Exception to be shown to the user
	 * @return string Sanitized text that can be returned to the user
	 */
	protected static function formatExceptionNoComment( Throwable $e ) {
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

		if ( $modules === [] && $missing === [] ) {
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
							// ...except when $scripts is an array of URLs or an associative array
							$strContent = self::makeLoaderImplementScript(
								$context,
								$implementKey,
								$scripts,
								[],
								[],
								[]
							);
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
								if ( !$context->getDebug() ) {
									$scripts = self::filter( 'minify-js', $scripts ); // T107377
								}
							} else {
								$scripts = new XmlJsCode( $scripts );
							}
						}
						$strContent = self::makeLoaderImplementScript(
							$context,
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
				} else {
					// In debug mode, separate each response by a new line.
					// For example, between 'mw.loader.implement();' statements.
					$strContent = $this->ensureNewline( $strContent );
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
		}

		// Update module states
		if ( $context->shouldIncludeScripts() && !$context->getRaw() ) {
			if ( $modules && $context->getOnly() === 'scripts' ) {
				// Set the state of modules loaded as only scripts to ready as
				// they don't have an mw.loader.implement wrapper that sets the state
				foreach ( $modules as $name => $module ) {
					$states[$name] = 'ready';
				}
			}

			// Set the state of modules we didn't respond to with mw.loader.implement
			if ( $states ) {
				$stateScript = self::makeLoaderStateScript( $context, $states );
				if ( !$context->getDebug() ) {
					$stateScript = self::filter( 'minify-js', $stateScript );
				}
				// Use a linebreak between module script and state script (T162719)
				$out = $this->ensureNewline( $out ) . $stateScript;
			}
		} elseif ( $states ) {
			$this->errors[] = 'Problematic modules: '
				. $context->encodeJson( $states );
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
		if ( $end === false || $end === '' || $end === "\n" ) {
			return $str;
		}
		return $str . "\n";
	}

	/**
	 * Get names of modules that use a certain message.
	 *
	 * @param string $messageKey
	 * @return string[] List of module names
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
	 * @param ResourceLoaderContext $context
	 * @param string $name Module name or implement key (format "`[name]@[version]`")
	 * @param XmlJsCode|array|string $scripts Code as XmlJsCode (to be wrapped in a closure),
	 *  list of URLs to JavaScript files, string of JavaScript for `$.globalEval`, or array with
	 *  'files' and 'main' properties (see ResourceLoaderModule::getScript())
	 * @param mixed $styles Array of CSS strings keyed by media type, or an array of lists of URLs
	 *   to CSS files keyed by media type
	 * @param mixed $messages List of messages associated with this module. May either be an
	 *   associative array mapping message key to value, or a JSON-encoded message blob containing
	 *   the same data, wrapped in an XmlJsCode object.
	 * @param array $templates Keys are name of templates and values are the source of
	 *   the template.
	 * @return string JavaScript code
	 */
	private static function makeLoaderImplementScript(
		ResourceLoaderContext $context, $name, $scripts, $styles, $messages, $templates
	) {
		if ( $scripts instanceof XmlJsCode ) {
			if ( $scripts->value === '' ) {
				$scripts = null;
			} elseif ( $context->getDebug() ) {
				$scripts = new XmlJsCode( "function ( $, jQuery, require, module ) {\n{$scripts->value}\n}" );
			} else {
				$scripts = new XmlJsCode( 'function($,jQuery,require,module){' . $scripts->value . '}' );
			}
		} elseif ( is_array( $scripts ) && isset( $scripts['files'] ) ) {
			$files = $scripts['files'];
			foreach ( $files as $path => &$file ) {
				// $file is changed (by reference) from a descriptor array to the content of the file
				// All of these essentially do $file = $file['content'];, some just have wrapping around it
				if ( $file['type'] === 'script' ) {
					// Multi-file modules only get two parameters ($ and jQuery are being phased out)
					if ( $context->getDebug() ) {
						$file = new XmlJsCode( "function ( require, module ) {\n{$file['content']}\n}" );
					} else {
						$file = new XmlJsCode( 'function(require,module){' . $file['content'] . '}' );
					}
				} else {
					$file = $file['content'];
				}
			}
			$scripts = XmlJsCode::encodeObject( [
				'main' => $scripts['main'],
				'files' => XmlJsCode::encodeObject( $files, $context->getDebug() )
			], $context->getDebug() );
		} elseif ( !is_string( $scripts ) && !is_array( $scripts ) ) {
			throw new InvalidArgumentException( 'Script must be a string or an array of URLs' );
		}

		// mw.loader.implement requires 'styles', 'messages' and 'templates' to be objects (not
		// arrays). json_encode considers empty arrays to be numerical and outputs "[]" instead
		// of "{}". Force them to objects.
		$module = [
			$name,
			$scripts,
			(object)$styles,
			(object)$messages,
			(object)$templates
		];
		self::trimArray( $module );

		return Xml::encodeJsCall( 'mw.loader.implement', $module, $context->getDebug() );
	}

	/**
	 * Returns JS code which, when called, will register a given list of messages.
	 *
	 * @param mixed $messages Associative array mapping message key to value.
	 * @return string JavaScript code
	 */
	public static function makeMessageSetScript( $messages ) {
		return 'mw.messages.set('
			. self::encodeJsonForScript( (object)$messages )
			. ');';
	}

	/**
	 * Combines an associative array mapping media type to CSS into a
	 * single stylesheet with "@media" blocks.
	 *
	 * @param array<string,string|string[]> $stylePairs Map from media type to CSS string(s)
	 * @return string[] CSS strings
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
	 * @internal For use within ResourceLoader classes only
	 * @since 1.32
	 * @param mixed $data
	 * @return string|false JSON string, false on error
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
	 * Returns a JS call to mw.loader.state, which sets the state of modules
	 * to a given value:
	 *
	 *    - ResourceLoader::makeLoaderStateScript( $context, [ $name => $state, ... ] ):
	 *         Set the state of modules with the given names to the given states
	 *
	 * @internal For use by ResourceLoaderStartUpModule
	 * @param ResourceLoaderContext $context
	 * @param array<string,string> $states
	 * @return string JavaScript code
	 */
	public static function makeLoaderStateScript(
		ResourceLoaderContext $context, array $states
	) {
		return 'mw.loader.state('
			. $context->encodeJson( $states )
			. ');';
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
	 * - new stdClass()
	 * - (object)[]
	 *
	 * @param array &$array
	 */
	private static function trimArray( array &$array ) : void {
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
	 * Format JS code which calls `mw.loader.register()` with the given parameters.
	 *
	 * @par Example
	 * @code
	 *
	 *     ResourceLoader::makeLoaderRegisterScript( $context, [
	 *        [ $name1, $version1, $dependencies1, $group1, $source1, $skip1 ],
	 *        [ $name2, $version2, $dependencies1, $group2, $source2, $skip2 ],
	 *        ...
	 *     ] ):
	 * @endcode
	 *
	 * @internal For use by ResourceLoaderStartUpModule only
	 * @param ResourceLoaderContext $context
	 * @param array[] $modules Array of module registration arrays, each containing
	 *  - string: module name
	 *  - string: module version
	 *  - array|null: List of dependencies (optional)
	 *  - string|null: Module group (optional)
	 *  - string|null: Name of foreign module source, or 'local' (optional)
	 *  - string|null: Script body of a skip function (optional)
	 * @codingStandardsIgnoreStart
	 * @phan-param array<int,array{0:string,1:string,2?:?array,3?:?string,4?:?string,5?:?string}> $modules
	 * @codingStandardsIgnoreEnd
	 * @return string JavaScript code
	 */
	public static function makeLoaderRegisterScript(
		ResourceLoaderContext $context, array $modules
	) {
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

		array_walk( $modules, [ self::class, 'trimArray' ] );

		return 'mw.loader.register('
			. $context->encodeJson( $modules )
			. ');';
	}

	/**
	 * Format JS code which calls `mw.loader.addSource()` with the given parameters.
	 *
	 *   - ResourceLoader::makeLoaderSourcesScript( $context,
	 *         [ $id1 => $loadUrl, $id2 => $loadUrl, ... ]
	 *     );
	 *       Register sources with the given IDs and properties.
	 *
	 * @internal For use by ResourceLoaderStartUpModule only
	 * @param ResourceLoaderContext $context
	 * @param array<string,string> $sources
	 * @return string JavaScript code
	 */
	public static function makeLoaderSourcesScript(
		ResourceLoaderContext $context, array $sources
	) {
		return 'mw.loader.addSource('
			. $context->encodeJson( $sources )
			. ');';
	}

	/**
	 * Wrap JavaScript code to run after the startup module.
	 *
	 * @param string $script JavaScript code
	 * @return string JavaScript code
	 */
	public static function makeLoaderConditionalScript( $script ) {
		// Adds a function to lazy-created RLQ
		return '(RLQ=window.RLQ||[]).push(function(){' .
			trim( $script ) . '});';
	}

	/**
	 * Wrap JavaScript code to run after a required module.
	 *
	 * @since 1.32
	 * @param string|string[] $modules Module name(s)
	 * @param string $script JavaScript code
	 * @return string JavaScript code
	 */
	public static function makeInlineCodeWithModule( $modules, $script ) {
		// Adds an array to lazy-created RLQ
		return '(RLQ=window.RLQ||[]).push(['
			. self::encodeJsonForScript( $modules ) . ','
			. 'function(){' . trim( $script ) . '}'
			. ']);';
	}

	/**
	 * Make an HTML script that runs given JS code after startup and base modules.
	 *
	 * The code will be wrapped in a closure, and it will be executed by ResourceLoader's
	 * startup module if the client has adequate support for MediaWiki JavaScript code.
	 *
	 * @param string $script JavaScript code
	 * @param string|null $nonce Content-Security-Policy nonce
	 *  (from `OutputPage->getCSP()->getNonce()`)
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
			"<script$escNonce>(RLQ=window.RLQ||[]).push(function(){",
			'});</script>'
		);
	}

	/**
	 * Return JS code which will set the MediaWiki configuration array to
	 * the given value.
	 *
	 * @param array $configuration List of configuration values keyed by variable name
	 * @return string JavaScript code
	 * @throws Exception
	 */
	public static function makeConfigSetScript( array $configuration ) {
		$json = self::encodeJsonForScript( $configuration );
		if ( $json === false ) {
			$e = new Exception(
				'JSON serialization of config data failed. ' .
				'This usually means the config data is not valid UTF-8.'
			);
			MWExceptionHandler::logException( $e );
			return 'mw.log.error(' . self::encodeJsonForScript( $e->__toString() ) . ');';
		}
		return "mw.config.set($json);";
	}

	/**
	 * Convert an array of module names to a packed query string.
	 *
	 * For example, `[ 'foo.bar', 'foo.baz', 'bar.baz', 'bar.quux' ]`
	 * becomes `'foo.bar,baz|bar.baz,quux'`.
	 *
	 * This process is reversed by ResourceLoader::expandModuleNames().
	 * See also mw.loader#buildModulesString() which is a port of this, used
	 * on the client-side.
	 *
	 * @param string[] $modules List of module names (strings)
	 * @return string Packed query string
	 */
	public static function makePackedModulesString( array $modules ) {
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
	 * Expand a string of the form `jquery.foo,bar|jquery.ui.baz,quux` to
	 * an array of module names like `[ 'jquery.foo', 'jquery.bar',
	 * 'jquery.ui.baz', 'jquery.ui.quux' ]`.
	 *
	 * This process is reversed by ResourceLoader::makePackedModulesString().
	 *
	 * @since 1.33
	 * @param string $modules Packed module name list
	 * @return string[] Array of module names
	 */
	public static function expandModuleNames( $modules ) {
		$retval = [];
		$exploded = explode( '|', $modules );
		foreach ( $exploded as $group ) {
			if ( strpos( $group, ',' ) === false ) {
				// This is not a set of modules in foo.bar,baz notation
				// but a single module
				$retval[] = $group;
			} else {
				// This is a set of modules in foo.bar,baz notation
				$pos = strrpos( $group, '.' );
				if ( $pos === false ) {
					// Prefixless modules, i.e. without dots
					$retval = array_merge( $retval, explode( ',', $group ) );
				} else {
					// We have a prefix and a bunch of suffixes
					$prefix = substr( $group, 0, $pos ); // 'foo'
					$suffixes = explode( ',', substr( $group, $pos + 1 ) ); // [ 'bar', 'baz' ]
					foreach ( $suffixes as $suffix ) {
						$retval[] = "$prefix.$suffix";
					}
				}
			}
		}
		return $retval;
	}

	/**
	 * Determine whether debug mode is on.
	 *
	 * Order of priority is:
	 * - 1) Request parameter,
	 * - 2) Cookie,
	 * - 3) Site configuration.
	 *
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
		array $extraQuery = []
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
	protected static function createLoaderQuery(
		ResourceLoaderContext $context, array $extraQuery = []
	) {
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
	 * @param string[] $modules
	 * @param string $lang
	 * @param string $skin
	 * @param string|null $user
	 * @param string|null $version
	 * @param bool $debug
	 * @param string|null $only
	 * @param bool $printable
	 * @param bool $handheld
	 * @param array $extraQuery
	 * @return array
	 */
	public static function makeLoaderQuery( array $modules, $lang, $skin, $user = null,
		$version = null, $debug = false, $only = null, $printable = false,
		$handheld = false, array $extraQuery = []
	) {
		$query = [
			'modules' => self::makePackedModulesString( $modules ),
		];
		// Keep urls short by omitting query parameters that
		// match the defaults assumed by ResourceLoaderContext.
		// Note: This relies on the defaults either being insignificant or forever constant,
		// as otherwise cached urls could change in meaning when the defaults change.
		if ( $lang !== ResourceLoaderContext::DEFAULT_LANG ) {
			$query['lang'] = $lang;
		}
		if ( $skin !== ResourceLoaderContext::DEFAULT_SKIN ) {
			$query['skin'] = $skin;
		}
		if ( $debug === true ) {
			$query['debug'] = 'true';
		}
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
		$len = strlen( $moduleName );
		return $len <= 255 && strcspn( $moduleName, '!,|', 0, $len ) === $len;
	}

	/**
	 * Return a LESS compiler that is set up for use with MediaWiki.
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
	 * Get site configuration settings to expose to JavaScript on all pages via `mw.config`.
	 *
	 * @internal Exposed for use from Resources.php
	 * @param ResourceLoaderContext $context
	 * @param Config $conf
	 * @return array
	 */
	public static function getSiteConfigSettings(
		ResourceLoaderContext $context, Config $conf
	) : array {
		// Namespace related preparation
		// - wgNamespaceIds: Key-value pairs of all localized, canonical and aliases for namespaces.
		// - wgCaseSensitiveNamespaces: Array of namespaces that are case-sensitive.
		$contLang = MediaWikiServices::getInstance()->getContentLanguage();
		$namespaceIds = $contLang->getNamespaceIds();
		$caseSensitiveNamespaces = [];
		$nsInfo = MediaWikiServices::getInstance()->getNamespaceInfo();
		foreach ( $nsInfo->getCanonicalNamespaces() as $index => $name ) {
			$namespaceIds[$contLang->lc( $name )] = $index;
			if ( !$nsInfo->isCapitalized( $index ) ) {
				$caseSensitiveNamespaces[] = $index;
			}
		}

		$illegalFileChars = $conf->get( 'IllegalFileChars' );

		// Build list of variables
		$skin = $context->getSkin();

		// Start of supported and stable config vars (for use by extensions/gadgets).
		$vars = [
			'debug' => $context->getDebug(),
			'skin' => $skin,
			'stylepath' => $conf->get( 'StylePath' ),
			'wgArticlePath' => $conf->get( 'ArticlePath' ),
			'wgScriptPath' => $conf->get( 'ScriptPath' ),
			'wgScript' => $conf->get( 'Script' ),
			'wgSearchType' => $conf->get( 'SearchType' ),
			'wgVariantArticlePath' => $conf->get( 'VariantArticlePath' ),
			'wgServer' => $conf->get( 'Server' ),
			'wgServerName' => $conf->get( 'ServerName' ),
			'wgUserLanguage' => $context->getLanguage(),
			'wgContentLanguage' => $contLang->getCode(),
			'wgVersion' => MW_VERSION,
			'wgFormattedNamespaces' => $contLang->getFormattedNamespaces(),
			'wgNamespaceIds' => $namespaceIds,
			'wgContentNamespaces' => $nsInfo->getContentNamespaces(),
			'wgSiteName' => $conf->get( 'Sitename' ),
			'wgDBname' => $conf->get( 'DBname' ),
			'wgWikiID' => WikiMap::getCurrentWikiId(),
			'wgCaseSensitiveNamespaces' => $caseSensitiveNamespaces,
			'wgCommentByteLimit' => null,
			'wgCommentCodePointLimit' => CommentStore::COMMENT_CHARACTER_LIMIT,
			'wgExtensionAssetsPath' => $conf->get( 'ExtensionAssetsPath' ),
		];
		// End of stable config vars.

		// Internal variables for use by MediaWiki core and/or ResourceLoader.
		$vars += [
			// @internal For mediawiki.widgets
			'wgUrlProtocols' => wfUrlProtocols(),
			// @internal For mediawiki.page.watch
			// Force object to avoid "empty" associative array from
			// becoming [] instead of {} in JS (T36604)
			'wgActionPaths' => (object)$conf->get( 'ActionPaths' ),
			// @internal For mediawiki.language
			'wgTranslateNumerals' => $conf->get( 'TranslateNumerals' ),
			// @internal For mediawiki.Title
			'wgExtraSignatureNamespaces' => $conf->get( 'ExtraSignatureNamespaces' ),
			// @internal For mediawiki.cookie
			'wgCookiePrefix' => $conf->get( 'CookiePrefix' ),
			'wgCookieDomain' => $conf->get( 'CookieDomain' ),
			'wgCookiePath' => $conf->get( 'CookiePath' ),
			'wgCookieExpiration' => $conf->get( 'CookieExpiration' ),
			// @internal For mediawiki.Title
			'wgLegalTitleChars' => Title::convertByteClassToUnicodeClass( Title::legalChars() ),
			'wgIllegalFileChars' => Title::convertByteClassToUnicodeClass( $illegalFileChars ),
			// @internal For mediawiki.ForeignUpload
			'wgForeignUploadTargets' => $conf->get( 'ForeignUploadTargets' ),
			'wgEnableUploads' => $conf->get( 'EnableUploads' ),
		];

		Hooks::runner()->onResourceLoaderGetConfigVars( $vars, $skin, $conf );

		return $vars;
	}
}
