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

namespace MediaWiki\ResourceLoader;

use Exception;
use HttpStatus;
use InvalidArgumentException;
use Less_Environment;
use Less_Parser;
use LogicException;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Config\Config;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Html\Html;
use MediaWiki\Html\HtmlJsCode;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Output\OutputPage;
use MediaWiki\Profiler\ProfilingContext;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\Request\HeaderCallback;
use MediaWiki\Request\WebRequest;
use MediaWiki\Title\Title;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\WikiMap\WikiMap;
use MWExceptionHandler;
use MWExceptionRenderer;
use Net_URL2;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RuntimeException;
use stdClass;
use Throwable;
use UnexpectedValueException;
use Wikimedia\DependencyStore\DependencyStore;
use Wikimedia\DependencyStore\KeyValueDependencyStore;
use Wikimedia\Minify\CSSMin;
use Wikimedia\Minify\IdentityMinifierState;
use Wikimedia\Minify\IndexMap;
use Wikimedia\Minify\IndexMapOffset;
use Wikimedia\Minify\JavaScriptMapperState;
use Wikimedia\Minify\JavaScriptMinifier;
use Wikimedia\Minify\JavaScriptMinifierState;
use Wikimedia\Minify\MinifierState;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\RequestTimeout\TimeoutException;
use Wikimedia\ScopedCallback;
use Wikimedia\Stats\StatsFactory;
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
	/** @var int */
	public const CACHE_VERSION = 9;
	/** @var string JavaScript / CSS pragma to disable minification. * */
	public const FILTER_NOMIN = '/*@nomin*/';

	/** @var string */
	private const RL_DEP_STORE_PREFIX = 'ResourceLoaderModule';
	/** @var int How long to preserve indirect dependency metadata in our backend store. */
	private const RL_MODULE_DEP_TTL = BagOStuff::TTL_YEAR;
	/** @var int */
	private const MAXAGE_RECOVER = 60;

	/** @var int|null */
	protected static $debugMode = null;

	/** @var Config */
	private $config;
	/** @var MessageBlobStore */
	private $blobStore;
	/** @var DependencyStore */
	private $depStore;
	/** @var LoggerInterface */
	private $logger;
	/** @var HookContainer */
	private $hookContainer;
	/** @var BagOStuff */
	private $srvCache;
	/** @var StatsFactory */
	private $statsFactory;
	/** @var int */
	private $maxageVersioned;
	/** @var int */
	private $maxageUnversioned;

	/** @var Module[] Map of (module name => Module) */
	private $modules = [];
	/** @var array[] Map of (module name => associative info array) */
	private $moduleInfos = [];
	/** @var string[] List of module names that contain QUnit tests */
	private $testModuleNames = [];
	/** @var string[] Map of (source => path); E.g. [ 'source-id' => 'http://.../load.php' ] */
	private $sources = [];
	/** @var array Errors accumulated during a respond() call. Exposed for testing. */
	protected $errors = [];
	/**
	 * @var string[] Buffer for extra response headers during a makeModuleResponse() call.
	 * Exposed for testing.
	 */
	protected $extraHeaders = [];
	/** @var array Map of (module-variant => buffered DependencyStore updates) */
	private $depStoreUpdateBuffer = [];
	/**
	 * @var array Styles that are skin-specific and supplement or replace the
	 * default skinStyles of a FileModule. See $wgResourceModuleSkinStyles.
	 */
	private $moduleSkinStyles = [];

	/**
	 * @internal For ServiceWiring only (TODO: Make stable as part of T32956).
	 * @param Config $config Generic pass-through for use by extension callbacks
	 *  and other MediaWiki-specific module classes.
	 * @param LoggerInterface|null $logger [optional]
	 * @param DependencyStore|null $tracker [optional]
	 * @param array $params [optional]
	 *  - loadScript: URL path to the load.php entrypoint.
	 *    Default: `'/load.php'`.
	 *  - maxageVersioned: HTTP cache max-age in seconds for URLs with a "version" parameter.
	 *    This applies to most load.php responses, and may have a long duration (e.g. weeks or
	 *    months), because a change in the module bundle will naturally produce a different URL
	 *    and thus automatically bust the CDN and web browser caches.
	 *    Default: 30 days.
	 *  - maxageUnversioned: HTTP cache max-age in seconds for URLs without a "version" parameter.
	 *    This should have a short duration (e.g. minutes), and affects the startup manifest which
	 *    controls how quickly changes (in the module registry, dependency tree, or module content)
	 *    will propagate to clients.
	 *    Default: 5 minutes.
	 */
	public function __construct(
		Config $config,
		?LoggerInterface $logger = null,
		?DependencyStore $tracker = null,
		array $params = []
	) {
		$this->maxageVersioned = $params['maxageVersioned'] ?? 30 * 24 * 60 * 60;
		$this->maxageUnversioned = $params['maxageUnversioned'] ?? 5 * 60;

		$this->config = $config;
		$this->logger = $logger ?: new NullLogger();

		$services = MediaWikiServices::getInstance();
		$this->hookContainer = $services->getHookContainer();

		$this->srvCache = $services->getLocalServerObjectCache();
		$this->statsFactory = $services->getStatsFactory();

		// Add 'local' source first
		$this->addSource( 'local', $params['loadScript'] ?? '/load.php' );

		// Special module that always exists
		$this->register( 'startup', [ 'class' => StartUpModule::class ] );

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
	 * @see $wgResourceModules for the available options.
	 * @param string|array[] $name Module name as a string or, array of module info arrays
	 *  keyed by name.
	 * @param array|null $info Module info array. When using the first parameter to register
	 *  multiple modules at once, this parameter is optional.
	 * @throws InvalidArgumentException If a module name contains illegal characters (pipes or commas)
	 * @throws InvalidArgumentException If the module info is not an array
	 */
	public function register( $name, ?array $info = null ) {
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
					'Invalid module info for "' . $name . '": expected array, got ' . get_debug_type( $info )
				);
			}

			// Attach module
			$this->moduleInfos[$name] = $info;
		}
	}

	/**
	 * @internal For use by ServiceWiring only
	 * @codeCoverageIgnore
	 */
	public function registerTestModules(): void {
		$extRegistry = ExtensionRegistry::getInstance();
		$testModules = $extRegistry->getAttribute( 'QUnitTestModules' );

		$testModuleNames = [];
		foreach ( $testModules as $name => &$module ) {
			// Turn any single-module dependency into an array
			if ( isset( $module['dependencies'] ) && is_string( $module['dependencies'] ) ) {
				$module['dependencies'] = [ $module['dependencies'] ];
			}

			// Ensure the testrunner loads before any tests
			$module['dependencies'][] = 'mediawiki.qunit-testrunner';

			// Keep track of the modules to load on SpecialJavaScriptTest
			$testModuleNames[] = $name;
		}

		// Core test modules (their names have further precedence).
		$testModules = ( include MW_INSTALL_PATH . '/tests/qunit/QUnitTestResources.php' ) + $testModules;
		$testModuleNames[] = 'test.MediaWiki';

		$this->register( $testModules );
		$this->testModuleNames = $testModuleNames;
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
	 * @return string[]
	 */
	public function getModuleNames() {
		return array_keys( $this->moduleInfos );
	}

	/**
	 * Get a list of modules with QUnit tests.
	 *
	 * @internal For use by SpecialJavaScriptTest only
	 * @return string[]
	 * @codeCoverageIgnore
	 */
	public function getTestSuiteModuleNames() {
		return $this->testModuleNames;
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
	 * Get the Module object for a given module name.
	 *
	 * If an array of module parameters exists but a Module object has not yet
	 * been instantiated, this method will instantiate and cache that object such that
	 * subsequent calls simply return the same object.
	 *
	 * @param string $name Module name
	 * @return Module|null If module has been registered, return a
	 *  Module instance. Otherwise, return null.
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
				/** @var Module $object */
				$object = call_user_func( $info['factory'], $info );
			} else {
				$class = $info['class'] ?? FileModule::class;
				/** @var Module $object */
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
			$object->setSkinStylesOverride( $this->moduleSkinStyles );
			$this->modules[$name] = $object;
		}

		return $this->modules[$name];
	}

	/**
	 * Load information stored in the database and dependency tracking store about modules
	 *
	 * @param string[] $moduleNames
	 * @param Context $context ResourceLoader-specific context of the request
	 */
	public function preloadModuleInfo( array $moduleNames, Context $context ) {
		// Load all tracked indirect file dependencies for the modules
		$vary = Module::getVary( $context );
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
				$paths = Module::expandRelativePaths( $deps['paths'] );
				$module->setFileDependencies( $context, $paths );
			}
		}

		WikiModule::preloadTitleInfo( $context, $moduleNames );

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
	 * @internal Exposed for letting getModule() pass the callable to DependencyStore
	 * @param string $moduleName
	 * @param string $variant Language/skin variant
	 * @return string[] List of absolute file paths
	 */
	public function loadModuleDependenciesInternal( $moduleName, $variant ) {
		$deps = $this->depStore->retrieve( self::RL_DEP_STORE_PREFIX, "$moduleName|$variant" );

		return Module::expandRelativePaths( $deps['paths'] );
	}

	/**
	 * @internal Exposed for letting getModule() pass the callable to DependencyStore
	 * @param string $moduleName
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
		}

		// If paths were unchanged, leave the dependency store unchanged also.
		// The entry will eventually expire, after which we will briefly issue an incomplete
		// version hash for a 5-min startup window, the module then recomputes and rediscovers
		// the paths and arrive at the same module version hash once again. It will churn
		// part of the browser cache once, for clients connecting during that window.

		if ( !$hasPendingUpdate ) {
			DeferredUpdates::addCallableUpdate( function () {
				$updatesByEntity = $this->depStoreUpdateBuffer;
				$this->depStoreUpdateBuffer = [];
				$cache = MediaWikiServices::getInstance()
					->getObjectCacheFactory()->getLocalClusterInstance();

				$scopeLocks = [];
				$depsByEntity = [];
				$entitiesUnreg = [];
				foreach ( $updatesByEntity as $entity => $update ) {
					$lockKey = $cache->makeKey( 'rl-deps', $entity );
					$scopeLocks[$entity] = $cache->getScopedLock( $lockKey, 0 );
					if ( !$scopeLocks[$entity] ) {
						// avoid duplicate write request slams (T124649)
						// the lock must be specific to the current wiki (T247028)
						continue;
					}
					if ( $update === null ) {
						$entitiesUnreg[] = $entity;
					} else {
						$depsByEntity[$entity] = $update;
					}
				}

				$ttl = self::RL_MODULE_DEP_TTL;
				$this->depStore->storeMulti( self::RL_DEP_STORE_PREFIX, $depsByEntity, $ttl );
				$this->depStore->remove( self::RL_DEP_STORE_PREFIX, $entitiesUnreg );
			} );
		}
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
	 * @internal For use by StartUpModule only.
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
			\Wikimedia\base_convert( $hash, 16, 36, self::HASH_LENGTH ),
			0,
			self::HASH_LENGTH
		);
	}

	/**
	 * Add an error to the 'errors' array and log it.
	 *
	 * @internal For use by StartUpModule.
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
	 * @param Context $context
	 * @param string[] $moduleNames List of known module names
	 * @return string Hash
	 */
	public function getCombinedVersion( Context $context, array $moduleNames ) {
		if ( !$moduleNames ) {
			return '';
		}
		$hashes = [];
		foreach ( $moduleNames as $module ) {
			try {
				$hash = $this->getModule( $module )->getVersionHash( $context );
			} catch ( TimeoutException $e ) {
				throw $e;
			} catch ( Exception $e ) {
				// If modules fail to compute a version, don't fail the request (T152266)
				// and still compute versions of other modules.
				$this->outputErrorAndLog( $e,
					'Calculating version for "{module}" failed: {exception}',
					[
						'module' => $module,
					]
				);
				$hash = '';
			}
			$hashes[] = $hash;
		}
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
	 * @param Context $context
	 * @param string[] $modules
	 * @return string Hash
	 */
	public function makeVersionQuery( Context $context, array $modules ) {
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
	 * @param Context $context Context in which a response should be formed
	 */
	public function respond( Context $context ) {
		// Buffer output to catch warnings. Normally we'd use ob_clean() on the
		// top-level output buffer to clear warnings, but that breaks when ob_gzhandler
		// is used: ob_clean() will clear the GZIP header in that case and it won't come
		// back for subsequent output, resulting in invalid GZIP. So we have to wrap
		// the whole thing in our own output buffer to be sure the active buffer
		// doesn't use ob_gzhandler.
		// See https://bugs.php.net/bug.php?id=36514
		ob_start();

		$this->errors = [];
		$responseTime = $this->measureResponseTime();
		ProfilingContext::singleton()->init( MW_ENTRY_POINT, 'respond' );

		// Find out which modules are missing and instantiate the others
		$modules = [];
		$missing = [];
		foreach ( $context->getModules() as $name ) {
			$module = $this->getModule( $name );
			if ( $module ) {
				// Do not allow private modules to be loaded from the web.
				// This is a security issue, see T36907.
				if ( $module->getGroup() === Module::GROUP_PRIVATE ) {
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
		} catch ( TimeoutException $e ) {
			throw $e;
		} catch ( Exception $e ) {
			$this->outputErrorAndLog( $e, 'Preloading module info failed: {exception}' );
		}

		// Combine versions to propagate cache invalidation
		$versionHash = $this->getCombinedVersion( $context, array_keys( $modules ) );

		// See RFC 2616 § 3.11 Entity Tags
		// https://www.w3.org/Protocols/rfc2616/rfc2616-sec3.html#sec3.11
		$etag = 'W/"' . $versionHash . '"';

		// Try the client-side cache first
		if ( $this->tryRespondNotModified( $context, $etag ) ) {
			return; // output handled (buffers cleared)
		}

		if ( $context->isSourceMap() ) {
			// In source map mode, a version mismatch should be a 404
			if ( $context->getVersion() !== null && $versionHash !== $context->getVersion() ) {
				ob_end_clean();
				$this->sendSourceMapVersionMismatch( $versionHash );
				return;
			}
			// No source maps for images, only=styles requests, or debug mode
			if ( $context->getImage()
				|| $context->getOnly() === 'styles'
				|| $context->getDebug()
			) {
				ob_end_clean();
				$this->sendSourceMapTypeNotImplemented();
				return;
			}
		}
		// Emit source map header if supported (inverse of the above check)
		if ( $this->config->get( MainConfigNames::ResourceLoaderEnableSourceMapLinks )
			&& !$context->getImageObj()
			&& !$context->isSourceMap()
			&& $context->shouldIncludeScripts()
			&& !$context->getDebug()
		) {
			$this->extraHeaders[] = 'SourceMap: ' . $this->getSourceMapUrl( $context, $versionHash );
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
				// Append the error info to the response
				// We used to prepend it, but that would corrupt the source map
				$response .= $errorResponse;
			} else {
				// For styles we can still prepend
				$response = $errorResponse . $response;
			}
		}

		echo $response;
	}

	/**
	 * Send stats about the time used to build the response
	 * @return ScopedCallback
	 */
	protected function measureResponseTime() {
		$statStart = $_SERVER['REQUEST_TIME_FLOAT'];
		return new ScopedCallback( function () use ( $statStart ) {
			$statTiming = microtime( true ) - $statStart;

			$this->statsFactory->getTiming( 'resourceloader_response_time_seconds' )
				->copyToStatsdAt( 'resourceloader.responseTime' )
				->observe( 1000 * $statTiming );
		} );
	}

	/**
	 * Send main response headers to the client.
	 *
	 * Deals with Content-Type, CORS (for stylesheets), and caching.
	 *
	 * @param Context $context
	 * @param string $etag ETag header value
	 * @param bool $errors Whether there are errors in the response
	 * @param string[] $extra Array of extra HTTP response headers
	 */
	protected function sendResponseHeaders(
		Context $context, $etag, $errors, array $extra = []
	): void {
		HeaderCallback::warnIfHeadersSent();

		if ( $errors ) {
			$maxage = self::MAXAGE_RECOVER;
		} elseif (
			$context->getVersion() !== null
			&& $context->getVersion() !== $this->makeVersionQuery( $context, $context->getModules() )
		) {
			// If we need to self-correct, set a very short cache expiry
			// to basically just debounce CDN traffic. This applies to:
			// - Internal errors, e.g. due to misconfiguration.
			// - Version mismatch, e.g. due to deployment race (T117587, T47877).
			$this->logger->debug( 'Client and server registry version out of sync' );
			$maxage = self::MAXAGE_RECOVER;
		} elseif ( $context->getVersion() === null ) {
			// Resources that can't set a version, should have their updates propagate to
			// clients quickly. This applies to shared resources linked from HTML, such as
			// the startup module and stylesheets.
			$maxage = $this->maxageUnversioned;
		} else {
			// When a version is set, use a long expiry because changes
			// will naturally miss the cache by using a different URL.
			$maxage = $this->maxageVersioned;
		}
		if ( $context->getImageObj() ) {
			// Output different headers if we're outputting textual errors.
			if ( $errors ) {
				header( 'Content-Type: text/plain; charset=utf-8' );
			} else {
				$context->getImageObj()->sendResponseHeaders( $context );
			}
		} elseif ( $context->isSourceMap() ) {
			header( 'Content-Type: application/json' );
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
		} else {
			// T132418: When a resource expires mid-way a browsing session, prefer to renew it in
			// the background instead of blocking the next page load (eg. startup module, or CSS).
			$staleDirective = ( $maxage > self::MAXAGE_RECOVER
				? ", stale-while-revalidate=" . min( 60, intval( $maxage / 2 ) )
				: ''
			);
			header( "Cache-Control: public, max-age=$maxage, s-maxage=$maxage" . $staleDirective );
			header( 'Expires: ' . ConvertibleTimestamp::convert( TS_RFC2822, time() + $maxage ) );
		}
		foreach ( $extra as $header ) {
			header( $header );
		}
	}

	/**
	 * Respond with HTTP 304 Not Modified if appropriate.
	 *
	 * If there's an If-None-Match header, respond with a 304 appropriately
	 * and clear out the output buffer. If the client cache is too old then do nothing.
	 *
	 * @param Context $context
	 * @param string $etag ETag header value
	 * @return bool True if HTTP 304 was sent and output handled
	 */
	protected function tryRespondNotModified( Context $context, $etag ) {
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
	 * Get the URL which will deliver the source map for the current response.
	 *
	 * @param Context $context
	 * @param string $version The combined version hash
	 * @return string
	 */
	private function getSourceMapUrl( Context $context, $version ) {
		return $this->createLoaderURL( 'local', $context, [
			'sourcemap' => '1',
			'version' => $version
		] );
	}

	/**
	 * Send an error page for a source map version mismatch
	 *
	 * @param string $currentVersion
	 */
	private function sendSourceMapVersionMismatch( $currentVersion ) {
		HttpStatus::header( 404 );
		header( 'Content-Type: text/plain; charset=utf-8' );
		header( 'X-Content-Type-Options: nosniff' );
		echo "Can't deliver a source map for the requested version " .
			"since the version is now '$currentVersion'\n";
	}

	/**
	 * Send an error page when a source map is requested but there is no
	 * support for the specified content type
	 */
	private function sendSourceMapTypeNotImplemented() {
		HttpStatus::header( 404 );
		header( 'Content-Type: text/plain; charset=utf-8' );
		header( 'X-Content-Type-Options: nosniff' );
		echo "Can't make a source map for this content type\n";
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
	 * @since 1.25
	 * @param Throwable $e Exception to be shown to the user
	 * @return string Sanitized text for a CSS/JS comment that can be returned to the user
	 */
	protected static function formatExceptionNoComment( Throwable $e ) {
		if ( !MWExceptionRenderer::shouldShowExceptionDetails() ) {
			return MWExceptionHandler::getPublicLogMessage( $e );
		}

		// Like MWExceptionHandler::getLogMessage but without $url and $id.
		// - Long load.php URL would push the actual error message off-screen into
		//   scroll overflow in browser devtools.
		// - reqId is redundant with X-Request-Id header, plus usually no need to
		//   correlate the reqId since the backtrace is already included below.
		$type = get_class( $e );
		$message = $e->getMessage();

		return "$type: $message" .
			"\nBacktrace:\n" .
			MWExceptionHandler::getRedactedTraceAsString( $e );
	}

	/**
	 * Generate code for a response.
	 *
	 * Calling this method also populates the `errors` and `headers` members,
	 * later used by respond().
	 *
	 * @param Context $context Context in which to generate a response
	 * @param Module[] $modules List of module objects keyed by module name
	 * @param string[] $missing List of requested module names that are unregistered (optional)
	 * @return string Response data
	 */
	public function makeModuleResponse( Context $context,
		array $modules, array $missing = []
	) {
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

		$states = [];
		foreach ( $missing as $name ) {
			$states[$name] = 'missing';
		}

		$only = $context->getOnly();
		$debug = (bool)$context->getDebug();
		if ( $context->isSourceMap() && count( $modules ) > 1 ) {
			$indexMap = new IndexMap;
		} else {
			$indexMap = null;
		}

		$out = '';
		foreach ( $modules as $name => $module ) {
			try {
				[ $response, $offset ] = $this->getOneModuleResponse( $context, $name, $module );
				if ( $indexMap ) {
					$indexMap->addEncodedMap( $response, $offset );
				} else {
					$out .= $response;
				}
			} catch ( TimeoutException $e ) {
				throw $e;
			} catch ( Exception $e ) {
				$this->outputErrorAndLog( $e, 'Generating module package failed: {exception}' );

				// Respond to client with error-state instead of module implementation
				$states[$name] = 'error';
				unset( $modules[$name] );
			}
		}

		// Update module states
		if ( $context->shouldIncludeScripts() && !$context->getRaw() ) {
			if ( $modules && $only === 'scripts' ) {
				// Set the state of modules loaded as only scripts to ready as
				// they don't have an mw.loader.impl wrapper that sets the state
				foreach ( $modules as $name => $module ) {
					$states[$name] = 'ready';
				}
			}

			// Set the state of modules we didn't respond to with mw.loader.impl
			if ( $states && !$context->isSourceMap() ) {
				$stateScript = self::makeLoaderStateScript( $context, $states );
				if ( !$debug ) {
					$stateScript = self::filter( 'minify-js', $stateScript );
				}
				// Use a linebreak between module script and state script (T162719)
				$out = self::ensureNewline( $out ) . $stateScript;
			}
		} elseif ( $states ) {
			$this->errors[] = 'Problematic modules: '
				// Silently ignore invalid UTF-8 injected via 'modules' query
				// Don't issue server-side warnings for client errors. (T331641)
				// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
				. @$context->encodeJson( $states );
		}

		if ( $indexMap ) {
			return $indexMap->getMap();
		} else {
			return $out;
		}
	}

	/**
	 * Get the response of a single module
	 *
	 * @param Context $context
	 * @param string $name
	 * @param Module $module
	 * @return array{string,IndexMapOffset|null}
	 */
	private function getOneModuleResponse( Context $context, $name, Module $module ) {
		$only = $context->getOnly();
		// Important: Do not cache minifications of embedded modules
		// This is especially for the private 'user.options' module,
		// which varies on every pageview and would explode the cache (T84960)
		$shouldCache = !$module->shouldEmbedModule( $context );
		if ( $only === 'styles' ) {
			$minifier = new IdentityMinifierState;
			$this->addOneModuleResponse( $context, $minifier, $name, $module, $this->extraHeaders );
			// NOTE: This is not actually "minified". IdentityMinifierState is a no-op wrapper
			// to ease code reuse. The filter() call below performs CSS minification.
			$styles = $minifier->getMinifiedOutput();
			if ( $context->getDebug() ) {
				return [ $styles, null ];
			}
			return [
				self::filter( 'minify-css', $styles,
					[ 'cache' => $shouldCache ] ),
				null
			];
		}

		$minifier = new IdentityMinifierState;
		$this->addOneModuleResponse( $context, $minifier, $name, $module, $this->extraHeaders );
		$plainContent = $minifier->getMinifiedOutput();
		if ( $context->getDebug() ) {
			return [ $plainContent, null ];
		}

		$isHit = true;
		$callback = function () use ( $context, $name, $module, &$isHit ) {
			$isHit = false;
			if ( $context->isSourceMap() ) {
				$minifier = ( new JavaScriptMapperState )
					->outputFile( $this->createLoaderURL( 'local', $context, [
						'modules' => self::makePackedModulesString( $context->getModules() ),
						'only' => $context->getOnly()
					] ) );
			} else {
				$minifier = new JavaScriptMinifierState;
			}
			// We only need to add one set of headers, and we did that for the identity response
			$discardedHeaders = null;
			$this->addOneModuleResponse( $context, $minifier, $name, $module, $discardedHeaders );
			if ( $context->isSourceMap() ) {
				$sourceMap = $minifier->getRawSourceMap();
				$generated = $minifier->getMinifiedOutput();
				$offset = IndexMapOffset::newFromText( $generated );
				return [ $sourceMap, $offset->toArray() ];
			} else {
				return [ $minifier->getMinifiedOutput(), null ];
			}
		};

		if ( $shouldCache ) {
			[ $response, $offsetArray ] = $this->srvCache->getWithSetCallback(
				$this->srvCache->makeGlobalKey(
					'resourceloader-mapped',
					self::CACHE_VERSION,
					$name,
					$context->isSourceMap() ? '1' : '0',
					md5( $plainContent )
				),
				BagOStuff::TTL_DAY,
				$callback
			);

			$mapType = $context->isSourceMap() ? 'map-js' : 'minify-js';
			$statsdNamespace = implode( '.', [
				"resourceloader_cache", $mapType, $isHit ? 'hit' : 'miss'
			] );
			$this->statsFactory->getCounter( 'resourceloader_cache_total' )
				->setLabel( 'type', $mapType )
				->setLabel( 'status', $isHit ? 'hit' : 'miss' )
				->copyToStatsdAt( [ $statsdNamespace ] )
				->increment();
		} else {
			[ $response, $offsetArray ] = $callback();
		}
		$offset = $offsetArray ? IndexMapOffset::newFromArray( $offsetArray ) : null;

		return [ $response, $offset ];
	}

	/**
	 * Add the response of a single module to the MinifierState
	 *
	 * @param Context $context
	 * @param MinifierState $minifier
	 * @param string $name
	 * @param Module $module
	 * @param array|null &$headers Array of headers. If it is not null, the
	 *   module's headers will be appended to this array.
	 */
	private function addOneModuleResponse(
		Context $context, MinifierState $minifier, $name, Module $module, &$headers
	) {
		$only = $context->getOnly();
		$debug = (bool)$context->getDebug();
		$content = $module->getModuleContent( $context );
		$version = $module->getVersionHash( $context );

		if ( $headers !== null && isset( $content['headers'] ) ) {
			$headers = array_merge( $headers, $content['headers'] );
		}

		// Append output
		switch ( $only ) {
			case 'scripts':
				$scripts = $content['scripts'];
				if ( !is_array( $scripts ) ) {
					// Formerly scripts was usually a string, but now it is
					// normalized to an array by buildContent().
					throw new InvalidArgumentException( 'scripts must be an array' );
				}
				if ( isset( $scripts['plainScripts'] ) ) {
					// Add plain scripts
					$this->addPlainScripts( $minifier, $name, $scripts['plainScripts'] );
				} elseif ( isset( $scripts['files'] ) ) {
					// Add implement call if any
					$this->addImplementScript(
						$minifier,
						$name,
						$version,
						$scripts,
						[],
						null,
						[],
						$content['deprecationWarning'] ?? null
					);
				}
				break;
			case 'styles':
				$styles = $content['styles'];
				// We no longer separate into media, they are all combined now with
				// custom media type groups into @media .. {} sections as part of the css string.
				// Module returns either an empty array or a numerical array with css strings.
				if ( isset( $styles['css'] ) ) {
					$minifier->addOutput( implode( '', $styles['css'] ) );
				}
				break;
			default:
				$scripts = $content['scripts'] ?? '';
				if ( ( $name === 'site' || $name === 'user' )
					&& isset( $scripts['plainScripts'] )
				) {
					// Legacy scripts that run in the global scope without a closure.
					// mw.loader.impl will use eval if scripts is a string.
					// Minify manually here, because general response minification is
					// not effective due it being a string literal, not a function.
					$scripts = self::concatenatePlainScripts( $scripts['plainScripts'] );
					if ( !$debug ) {
						$scripts = self::filter( 'minify-js', $scripts ); // T107377
					}
				}
				$this->addImplementScript(
					$minifier,
					$name,
					$version,
					$scripts,
					$content['styles'] ?? [],
					isset( $content['messagesBlob'] ) ? new HtmlJsCode( $content['messagesBlob'] ) : null,
					$content['templates'] ?? [],
					$content['deprecationWarning'] ?? null
				);
				break;
		}
		$minifier->ensureNewline();
	}

	/**
	 * Ensure the string is either empty or ends in a line break
	 * @internal
	 * @param string $str
	 * @return string
	 */
	public static function ensureNewline( $str ) {
		$end = substr( $str, -1 );
		// @phan-suppress-next-line PhanImpossibleTypeComparison
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
	 * Generate JS code that calls mw.loader.impl with given module properties
	 * and add it to the MinifierState.
	 *
	 * @param MinifierState $minifier The minifier to which output should be appended
	 * @param string $moduleName The module name
	 * @param string $version The module version hash
	 * @param array|string|string[] $scripts
	 *  - array: Package files array containing strings for individual JS files,
	 *    as produced by Module::getScript().
	 *  - string: Script contents to eval in global scope (for site/user scripts).
	 *  - string[]: List of URLs (for debug mode).
	 * @param array<string,string|array<string,string[]>> $styles
	 *   Under optional key "css", there is a concatenated CSS string.
	 *   Under optional key "url", there is an array by media type withs URLs to stylesheets (for debug mode).
	 *   These come from Module::getStyles(), formatted by Module:buildContent().
	 * @param HtmlJsCode|null $messages An already JSON-encoded map from message keys to values,
	 *   wrapped in an HtmlJsCode object.
	 * @param array<string,string> $templates Map from template name to template source.
	 * @param string|null $deprecationWarning
	 */
	private function addImplementScript( MinifierState $minifier,
		$moduleName, $version, $scripts, $styles, $messages, $templates, $deprecationWarning
	) {
		$implementKey = "$moduleName@$version";
		// Plain functions are used instead of arrow functions to avoid
		// defeating lazy compilation on Chrome. (T343407)
		$minifier->addOutput( "mw.loader.impl(function(){return[" .
			Html::encodeJsVar( $implementKey ) . "," );

		// Scripts
		if ( is_string( $scripts ) ) {
			// user/site script
			$minifier->addOutput( Html::encodeJsVar( $scripts ) );
		} elseif ( is_array( $scripts ) ) {
			if ( isset( $scripts['files'] ) ) {
				$minifier->addOutput(
					"{\"main\":" .
					Html::encodeJsVar( $scripts['main'] ) .
					",\"files\":" );
				$this->addFiles( $minifier, $moduleName, $scripts['files'] );
				$minifier->addOutput( "}" );
			} elseif ( isset( $scripts['plainScripts'] ) ) {
				if ( $this->isEmptyFileInfos( $scripts['plainScripts'] ) ) {
					$minifier->addOutput( 'null' );
				} else {
					$minifier->addOutput( "function($,jQuery,require,module){" );
					$this->addPlainScripts( $minifier, $moduleName, $scripts['plainScripts'] );
					$minifier->addOutput( "}" );
				}
			} elseif ( $scripts === [] || isset( $scripts[0] ) ) {
				// Array of URLs
				$minifier->addOutput( Html::encodeJsVar( $scripts ) );
			} else {
				throw new InvalidArgumentException( 'Invalid script array: ' .
					'must contain files, plainScripts or be an array of URLs' );
			}
		} else {
			throw new InvalidArgumentException( 'Script must be a string or array' );
		}

		// mw.loader.impl requires 'styles', 'messages' and 'templates' to be objects (not
		// arrays). json_encode considers empty arrays to be numerical and outputs "[]" instead
		// of "{}". Force them to objects.
		$extraArgs = [
			(object)$styles,
			$messages ?? (object)[],
			(object)$templates,
			$deprecationWarning
		];
		self::trimArray( $extraArgs );
		foreach ( $extraArgs as $arg ) {
			$minifier->addOutput( ',' . Html::encodeJsVar( $arg ) );
		}
		$minifier->addOutput( "];});" );
	}

	/**
	 * Extract the contents of an array of package files, and convert it to a
	 * JavaScript array. Add the array to the minifier state.
	 *
	 * Package files can contain JSON data.
	 *
	 * @param MinifierState $minifier
	 * @param string $moduleName
	 * @param array $files
	 */
	private function addFiles( MinifierState $minifier, $moduleName, $files ) {
		$first = true;
		$minifier->addOutput( "{" );
		foreach ( $files as $fileName => $file ) {
			if ( $first ) {
				$first = false;
			} else {
				$minifier->addOutput( "," );
			}
			$minifier->addOutput( Html::encodeJsVar( $fileName ) . ':' );
			$this->addFileContent( $minifier, $moduleName, 'packageFile', $fileName, $file );
		}
		$minifier->addOutput( "}" );
	}

	/**
	 * Add a package file to a MinifierState
	 *
	 * @param MinifierState $minifier
	 * @param string $moduleName
	 * @param string $sourceType
	 * @param string|int $sourceIndex
	 * @param array $file The expanded file info array
	 */
	private function addFileContent( MinifierState $minifier,
		$moduleName, $sourceType, $sourceIndex, array $file
	) {
		$isScript = ( $file['type'] ?? 'script' ) === 'script';
		/** @var FilePath|null $filePath */
		$filePath = $file['filePath'] ?? $file['virtualFilePath'] ?? null;
		if ( $filePath !== null && $filePath->getRemoteBasePath() !== null ) {
			$url = $filePath->getRemotePath();
		} else {
			$ext = $isScript ? 'js' : 'json';
			$scriptPath = $this->config->has( MainConfigNames::ScriptPath )
				? $this->config->get( MainConfigNames::ScriptPath ) : '';
			$url = "$scriptPath/virtual-resource/$moduleName-$sourceType-$sourceIndex.$ext";
		}
		$content = $file['content'];
		if ( $isScript ) {
			if ( $sourceType === 'packageFile' ) {
				// Provide CJS `exports` (in addition to CJS2 `module.exports`) to package modules (T284511).
				// $/jQuery are simply used as globals instead.
				// TODO: Remove $/jQuery param from traditional module closure too (and bump caching)
				$minifier->addOutput( "function(require,module,exports){" );
				$minifier->addSourceFile( $url, $content, true );
				$minifier->ensureNewline();
				$minifier->addOutput( "}" );
			} else {
				$minifier->addSourceFile( $url, $content, true );
				$minifier->ensureNewline();
			}
		} else {
			$content = Html::encodeJsVar( $content, true );
			$minifier->addSourceFile( $url, $content, true );
		}
	}

	/**
	 * Combine a plainScripts array like [ [ 'content' => '...' ] ] into a
	 * single string.
	 *
	 * @param array[] $plainScripts
	 * @return string
	 */
	private static function concatenatePlainScripts( $plainScripts ) {
		$s = '';
		foreach ( $plainScripts as $script ) {
			// Make the script safe to concatenate by making sure there is at least one
			// trailing new line at the end of the content (T29054, T162719)
			$s .= self::ensureNewline( $script['content'] );
		}
		return $s;
	}

	/**
	 * Add contents from a plainScripts array like [ [ 'content' => '...' ]
	 * to a MinifierState
	 *
	 * @param MinifierState $minifier
	 * @param string $moduleName
	 * @param array[] $plainScripts
	 */
	private function addPlainScripts( MinifierState $minifier, $moduleName, $plainScripts ) {
		foreach ( $plainScripts as $index => $file ) {
			$this->addFileContent( $minifier, $moduleName, 'script', $index, $file );
		}
	}

	/**
	 * Determine whether an array of file info arrays has empty content
	 *
	 * @param array $infos
	 * @return bool
	 */
	private function isEmptyFileInfos( $infos ) {
		$len = 0;
		foreach ( $infos as $info ) {
			$len += strlen( $info['content'] ?? '' );
		}
		return $len === 0;
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
			// FileModule::getStyle can return the styles as a string or an
			// array of strings. This is to allow separation in the front-end.
			$styles = (array)$styles;
			foreach ( $styles as $style ) {
				$style = trim( $style );
				// Don't output an empty "@media print { }" block (T42498)
				if ( $style === '' ) {
					continue;
				}
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
		return $out;
	}

	/**
	 * Wrapper around json_encode that avoids needless escapes,
	 * and pretty-prints in debug mode.
	 *
	 * @param mixed $data
	 * @return string|false JSON string, false on error
	 */
	private static function encodeJsonForScript( $data ) {
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
	 * Format a JS call to mw.loader.state()
	 *
	 * @internal For use by StartUpModule
	 * @param Context $context
	 * @param array<string,string> $states
	 * @return string JavaScript code
	 */
	public static function makeLoaderStateScript(
		Context $context, array $states
	) {
		return 'mw.loader.state('
			// Silently ignore invalid UTF-8 injected via 'modules' query
			// Don't issue server-side warnings for client errors. (T331641)
			// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
			. @$context->encodeJson( $states )
			. ');';
	}

	private static function isEmptyObject( stdClass $obj ) {
		foreach ( $obj as $value ) {
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
	 * - new HtmlJsCode( '{}' )
	 * - new stdClass()
	 * - (object)[]
	 *
	 * @param array &$array
	 */
	private static function trimArray( array &$array ): void {
		$i = count( $array );
		while ( $i-- ) {
			if ( $array[$i] === null
				|| $array[$i] === []
				|| ( $array[$i] instanceof HtmlJsCode && $array[$i]->value === '{}' )
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
	 * @internal For use by StartUpModule only
	 * @param Context $context
	 * @param array[] $modules Array of module registration arrays, each containing
	 *  - string: module name
	 *  - string: module version
	 *  - array|null: List of dependencies (optional)
	 *  - string|null: Module group (optional)
	 *  - string|null: Name of foreign module source, or 'local' (optional)
	 *  - string|null: Script body of a skip function (optional)
	 * @phan-param array<int,array{0:string,1:string,2?:?array,3?:?string,4?:?string,5?:?string}> $modules
	 * @return string JavaScript code
	 */
	public static function makeLoaderRegisterScript(
		Context $context, array $modules
	) {
		// Optimisation: Transform dependency names into indexes when possible
		// to produce smaller output. They are expanded by mw.loader.register on
		// the other end.
		$index = [];
		foreach ( $modules as $i => $module ) {
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
			self::trimArray( $module );
		}

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
	 * @internal For use by StartUpModule only
	 * @param Context $context
	 * @param array<string,string> $sources
	 * @return string JavaScript code
	 */
	public static function makeLoaderSourcesScript(
		Context $context, array $sources
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
	 * @param string|null $nonce Unused
	 * @return string|WrappedString HTML
	 */
	public static function makeInlineScript( $script, $nonce = null ) {
		$js = self::makeLoaderConditionalScript( $script );
		return new WrappedString(
			Html::inlineScript( $js ),
			"<script>(RLQ=window.RLQ||[]).push(function(){",
			'});</script>'
		);
	}

	/**
	 * Return JS code which will set the MediaWiki configuration array to
	 * the given value.
	 *
	 * @param array $configuration List of configuration values keyed by variable name
	 * @return string JavaScript code
	 * @throws LogicException
	 */
	public static function makeConfigSetScript( array $configuration ) {
		$json = self::encodeJsonForScript( $configuration );
		if ( $json === false ) {
			$e = new LogicException(
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
				continue;
			}
			// This is a set of modules in foo.bar,baz notation
			$pos = strrpos( $group, '.' );
			if ( $pos === false ) {
				// Prefixless modules, i.e. without dots
				$retval = array_merge( $retval, explode( ',', $group ) );
				continue;
			}
			// We have a prefix and a bunch of suffixes
			$prefix = substr( $group, 0, $pos ); // 'foo'
			$suffixes = explode( ',', substr( $group, $pos + 1 ) ); // [ 'bar', 'baz' ]
			foreach ( $suffixes as $suffix ) {
				$retval[] = "$prefix.$suffix";
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
	 * @return int
	 */
	public static function inDebugMode() {
		if ( self::$debugMode === null ) {
			global $wgRequest;

			$resourceLoaderDebug = MediaWikiServices::getInstance()->getMainConfig()->get(
				MainConfigNames::ResourceLoaderDebug );
			$str = $wgRequest->getRawVal( 'debug' ) ??
				$wgRequest->getCookie( 'resourceLoaderDebug', '', $resourceLoaderDebug ? 'true' : '' );
			self::$debugMode = Context::debugFromString( $str );
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
	 * @param Context $context
	 * @param array $extraQuery
	 * @return string URL to load.php. May be protocol-relative if $wgLoadScript is, too.
	 */
	public function createLoaderURL( $source, Context $context,
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
	 * @param Context $context
	 * @param array $extraQuery
	 * @return array
	 */
	protected static function createLoaderQuery(
		Context $context, array $extraQuery = []
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
			null,
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
	 * @param int $debug
	 * @param string|null $only
	 * @param bool $printable
	 * @param bool|null $handheld Unused as of MW 1.38
	 * @param array $extraQuery
	 * @return array
	 */
	public static function makeLoaderQuery( array $modules, $lang, $skin, $user = null,
		$version = null, $debug = Context::DEBUG_OFF, $only = null,
		$printable = false, $handheld = null, array $extraQuery = []
	) {
		$query = [
			'modules' => self::makePackedModulesString( $modules ),
		];
		// Keep urls short by omitting query parameters that
		// match the defaults assumed by Context.
		// Note: This relies on the defaults either being insignificant or forever constant,
		// as otherwise cached urls could change in meaning when the defaults change.
		if ( $lang !== Context::DEFAULT_LANG ) {
			$query['lang'] = $lang;
		}
		if ( $skin !== Context::DEFAULT_SKIN ) {
			$query['skin'] = $skin;
		}
		if ( $debug !== Context::DEBUG_OFF ) {
			$query['debug'] = strval( $debug );
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
		foreach ( $extraQuery as $name => $value ) {
			$query[$name] = $value;
		}

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
	 * @param array $importDirs Additional directories to look in for @import (since 1.36)
	 * @return Less_Parser
	 */
	public function getLessCompiler( array $vars = [], array $importDirs = [] ) {
		// When called from the installer, it is possible that a required PHP extension
		// is missing (at least for now; see T49564). If this is the case, throw an
		// exception (caught by the installer) to prevent a fatal error later on.
		if ( !class_exists( Less_Parser::class ) ) {
			throw new RuntimeException( 'MediaWiki requires the less.php parser' );
		}

		$importDirs[] = MW_INSTALL_PATH . '/resources/src/mediawiki.less';

		$parser = new Less_Parser;
		$parser->ModifyVars( $vars );
		$parser->SetOption( 'relativeUrls', false );
		$parser->SetOption( 'math', 'always' );

		// SetImportDirs expects an array like [ 'path1' => '', 'path2' => '' ]
		$formattedImportDirs = array_fill_keys( $importDirs, '' );

		// Add a callback to the import dirs array for path remapping
		$codexDevDir = $this->getConfig()->get( MainConfigNames::CodexDevelopmentDir );
		$formattedImportDirs[] = static function ( $path ) use ( $codexDevDir ) {
			// For each of the Codex import paths, use CodexDevelopmentDir if it's set
			$importMap = [
				'@wikimedia/codex-icons/' => $codexDevDir !== null ?
					"$codexDevDir/packages/codex-icons/dist/" :
					MW_INSTALL_PATH . '/resources/lib/codex-icons/',
				'mediawiki.skin.codex/' => $codexDevDir !== null ?
					"$codexDevDir/packages/codex/dist/" :
					MW_INSTALL_PATH . '/resources/lib/codex/',
				'mediawiki.skin.codex-design-tokens/' => $codexDevDir !== null ?
					"$codexDevDir/packages/codex-design-tokens/dist/" :
					MW_INSTALL_PATH . '/resources/lib/codex-design-tokens/',
				'@wikimedia/codex-design-tokens/' => /** @return never */ static function ( $unused_path ) {
					throw new RuntimeException(
						'Importing from @wikimedia/codex-design-tokens is not supported. ' .
						"To use the Codex tokens, use `@import 'mediawiki.skin.variables.less';` instead."
					);
				}
			];
			foreach ( $importMap as $importPath => $substPath ) {
				if ( str_starts_with( $path, $importPath ) ) {
					$restOfPath = substr( $path, strlen( $importPath ) );
					if ( is_callable( $substPath ) ) {
						$resolvedPath = call_user_func( $substPath, $restOfPath );
					} else {
						$filePath = $substPath . $restOfPath;

						$resolvedPath = null;
						if ( file_exists( $filePath ) ) {
							$resolvedPath = $filePath;
						} elseif ( file_exists( "$filePath.less" ) ) {
							$resolvedPath = "$filePath.less";
						}
					}

					if ( $resolvedPath !== null ) {
						return [
							Less_Environment::normalizePath( $resolvedPath ),
							Less_Environment::normalizePath( dirname( $path ) )
						];
					} else {
						break;
					}
				}
			}
			return [ null, null ];
		};
		$parser->SetImportDirs( $formattedImportDirs );

		return $parser;
	}

	/**
	 * Resolve a possibly relative URL against a base URL.
	 *
	 * The base URL must have a server and should have a protocol.
	 * A protocol-relative base expands to HTTPS.
	 *
	 * This is a standalone version of MediaWiki's UrlUtils::expand (T32956).
	 *
	 * @internal For use by core ResourceLoader classes only
	 * @param string $base
	 * @param string $url
	 * @return string URL
	 */
	public function expandUrl( string $base, string $url ): string {
		// Net_URL2::resolve() doesn't allow protocol-relative URLs, but we do.
		$isProtoRelative = strpos( $base, '//' ) === 0;
		if ( $isProtoRelative ) {
			$base = "https:$base";
		}
		// Net_URL2::resolve() takes care of throwing if $base doesn't have a server.
		$baseUrl = new Net_URL2( $base );
		$ret = $baseUrl->resolve( $url );
		if ( $isProtoRelative ) {
			$ret->setScheme( false );
		}
		return $ret->getURL();
	}

	/**
	 * Run JavaScript or CSS data through a filter, caching the filtered result for future calls.
	 *
	 * Available filters are:
	 *
	 *    - minify-js
	 *    - minify-css
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

		$statsFactory = MediaWikiServices::getInstance()->getStatsFactory();
		$cache = MediaWikiServices::getInstance()->getObjectCacheFactory()
			->getLocalServerInstance( CACHE_ANYTHING );

		$key = $cache->makeGlobalKey(
			'resourceloader-filter',
			$filter,
			self::CACHE_VERSION,
			md5( $data )
		);

		$status = 'hit';
		$incKey = "resourceloader_cache.$filter.$status";
		$result = $cache->getWithSetCallback(
			$key,
			BagOStuff::TTL_DAY,
			static function () use ( $filter, $data, &$incKey, &$status ) {
				$status = 'miss';
				$incKey = "resourceloader_cache.$filter.$status";
				return self::applyFilter( $filter, $data );
			}
		);
		$statsFactory->getCounter( 'resourceloader_cache_total' )
			->setLabel( 'type', $filter )
			->setLabel( 'status', $status )
			->copyToStatsdAt( [ $incKey ] )
			->increment();

		// Use $data on cache failure
		return $result ?? $data;
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
			} catch ( TimeoutException $e ) {
				throw $e;
			} catch ( Exception $e ) {
				MWExceptionHandler::logException( $e );
				return null;
			}
		}
		return $data;
	}

	/**
	 * Get user default options to expose to JavaScript on all pages via `mw.user.options`.
	 *
	 * @internal Exposed for use from Resources.php
	 *
	 * @param Context $context
	 * @param HookContainer $hookContainer
	 * @param UserOptionsLookup $userOptionsLookup
	 *
	 * @return array
	 */
	public static function getUserDefaults(
		Context $context,
		HookContainer $hookContainer,
		UserOptionsLookup $userOptionsLookup
	): array {
		$defaultOptions = $userOptionsLookup->getDefaultOptions();
		$keysToExclude = [];
		$hookRunner = new HookRunner( $hookContainer );
		$hookRunner->onResourceLoaderExcludeUserOptions( $keysToExclude, $context );
		foreach ( $keysToExclude as $excludedKey ) {
			unset( $defaultOptions[ $excludedKey ] );
		}
		return $defaultOptions;
	}

	/**
	 * Get site configuration settings to expose to JavaScript on all pages via `mw.config`.
	 *
	 * @internal Exposed for use from Resources.php
	 * @param Context $context
	 * @param Config $conf
	 * @return array
	 */
	public static function getSiteConfigSettings(
		Context $context, Config $conf
	): array {
		$services = MediaWikiServices::getInstance();
		// Namespace related preparation
		// - wgNamespaceIds: Key-value pairs of all localized, canonical and aliases for namespaces.
		// - wgCaseSensitiveNamespaces: Array of namespaces that are case-sensitive.
		$contLang = $services->getContentLanguage();
		$namespaceIds = $contLang->getNamespaceIds();
		$caseSensitiveNamespaces = [];
		$nsInfo = $services->getNamespaceInfo();
		foreach ( $nsInfo->getCanonicalNamespaces() as $index => $name ) {
			$namespaceIds[$contLang->lc( $name )] = $index;
			if ( !$nsInfo->isCapitalized( $index ) ) {
				$caseSensitiveNamespaces[] = $index;
			}
		}

		$illegalFileChars = $conf->get( MainConfigNames::IllegalFileChars );

		// Build list of variables
		$skin = $context->getSkin();

		// Start of supported and stable config vars (for use by extensions/gadgets).
		$vars = [
			'debug' => $context->getDebug(),
			'skin' => $skin,
			'stylepath' => $conf->get( MainConfigNames::StylePath ),
			'wgArticlePath' => $conf->get( MainConfigNames::ArticlePath ),
			'wgScriptPath' => $conf->get( MainConfigNames::ScriptPath ),
			'wgScript' => $conf->get( MainConfigNames::Script ),
			'wgSearchType' => $conf->get( MainConfigNames::SearchType ),
			'wgVariantArticlePath' => $conf->get( MainConfigNames::VariantArticlePath ),
			'wgServer' => $conf->get( MainConfigNames::Server ),
			'wgServerName' => $conf->get( MainConfigNames::ServerName ),
			'wgUserLanguage' => $context->getLanguage(),
			'wgContentLanguage' => $contLang->getCode(),
			'wgVersion' => MW_VERSION,
			'wgFormattedNamespaces' => $contLang->getFormattedNamespaces(),
			'wgNamespaceIds' => $namespaceIds,
			'wgContentNamespaces' => $nsInfo->getContentNamespaces(),
			'wgSiteName' => $conf->get( MainConfigNames::Sitename ),
			'wgDBname' => $conf->get( MainConfigNames::DBname ),
			'wgWikiID' => WikiMap::getCurrentWikiId(),
			'wgCaseSensitiveNamespaces' => $caseSensitiveNamespaces,
			'wgCommentCodePointLimit' => CommentStore::COMMENT_CHARACTER_LIMIT,
			'wgExtensionAssetsPath' => $conf->get( MainConfigNames::ExtensionAssetsPath ),
		];
		// End of stable config vars.

		// Internal variables for use by MediaWiki core and/or ResourceLoader.
		$vars += [
			// @internal For mediawiki.widgets
			'wgUrlProtocols' => $services->getUrlUtils()->validProtocols(),
			// @internal For mediawiki.page.watch
			// Force object to avoid "empty" associative array from
			// becoming [] instead of {} in JS (T36604)
			'wgActionPaths' => (object)$conf->get( MainConfigNames::ActionPaths ),
			// @internal For mediawiki.language
			'wgTranslateNumerals' => $conf->get( MainConfigNames::TranslateNumerals ),
			// @internal For mediawiki.Title
			'wgExtraSignatureNamespaces' => $conf->get( MainConfigNames::ExtraSignatureNamespaces ),
			'wgLegalTitleChars' => Title::convertByteClassToUnicodeClass( Title::legalChars() ),
			'wgIllegalFileChars' => Title::convertByteClassToUnicodeClass( $illegalFileChars ),
		];

		( new HookRunner( $services->getHookContainer() ) )
			->onResourceLoaderGetConfigVars( $vars, $skin, $conf );

		return $vars;
	}

	/**
	 * @internal For testing
	 * @return array
	 */
	public function getErrors() {
		return $this->errors;
	}
}
