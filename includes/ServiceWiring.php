<?php
/**
 * Default wiring for MediaWiki services.
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
 *
 * This file is loaded by MediaWiki\MediaWikiServices::getInstance() during the
 * bootstrapping of the dependency injection framework.
 *
 * This file returns an array that associates service name with instantiator functions
 * that create the default instances for the services used by MediaWiki core.
 * For every service that MediaWiki core requires, an instantiator must be defined in
 * this file.
 *
 * Note that, ideally, all information used to instantiate service objects should come
 * from configuration. Information derived from the current request is acceptable, but
 * only where there is no feasible alternative. It is preferred that such information
 * (like the client IP, the acting user's identity, requested title, etc) be passed to
 * the service object's methods as parameters. This makes the flow of information more
 * obvious, and makes it easier to understand the behavior of services.
 *
 * @note As of version 1.27, MediaWiki is only beginning to use dependency injection.
 * The services defined here do not yet fully represent all services used by core,
 * much of the code still relies on global state for this accessing services.
 *
 * @since 1.27
 *
 * @see docs/Injection.md for an overview of using dependency injection in the
 *      MediaWiki code base.
 */

use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use MediaWiki\Auth\AuthManager;
use MediaWiki\BadFileLookup;
use MediaWiki\Block\BlockErrorFormatter;
use MediaWiki\Block\BlockManager;
use MediaWiki\Block\BlockPermissionCheckerFactory;
use MediaWiki\Block\BlockRestrictionStore;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Config\ConfigRepository;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\ContentHandlerFactory;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\EditPage\SpamChecker;
use MediaWiki\FileBackend\FSFile\TempFSFileFactory;
use MediaWiki\FileBackend\LockManager\LockManagerGroupFactory;
use MediaWiki\HookContainer\DeprecatedHooks;
use MediaWiki\HookContainer\GlobalHookRegistry;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Http\HttpRequestFactory;
use MediaWiki\Interwiki\ClassicInterwikiLookup;
use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\Languages\LanguageFallback;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Linker\LinkRendererFactory;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Mail\Emailer;
use MediaWiki\Mail\IEmailer;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\MessageFormatterFactory;
use MediaWiki\Page\ContentModelChangeFactory;
use MediaWiki\Page\MergeHistoryFactory;
use MediaWiki\Page\MovePageFactory;
use MediaWiki\Page\PageCommandFactory;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Preferences\DefaultPreferencesFactory;
use MediaWiki\Preferences\PreferencesFactory;
use MediaWiki\Revision\ContributionsLookup;
use MediaWiki\Revision\MainSlotRoleHandler;
use MediaWiki\Revision\RevisionFactory;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRenderer;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\RevisionStoreFactory;
use MediaWiki\Revision\SlotRoleRegistry;
use MediaWiki\Shell\CommandFactory;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Storage\BlobStore;
use MediaWiki\Storage\BlobStoreFactory;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Storage\NameTableStoreFactory;
use MediaWiki\Storage\PageEditStash;
use MediaWiki\Storage\SqlBlobStore;
use MediaWiki\User\DefaultOptionsLookup;
use MediaWiki\User\TalkPageNotificationManager;
use MediaWiki\User\UserEditTracker;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserGroupManagerFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserNameUtils;
use MediaWiki\User\UserOptionsLookup;
use MediaWiki\User\UserOptionsManager;
use MediaWiki\User\WatchlistNotificationManager;
use Wikimedia\DependencyStore\KeyValueDependencyStore;
use Wikimedia\DependencyStore\SqlModuleDependencyStore;
use Wikimedia\Message\IMessageFormatterFactory;
use Wikimedia\ObjectFactory;
use Wikimedia\Services\RecursiveServiceDependencyException;
use Wikimedia\UUID\GlobalIdGenerator;

return [
	'ActorMigration' => function () : ActorMigration {
		return new ActorMigration( SCHEMA_COMPAT_NEW );
	},

	'AuthManager' => function ( MediaWikiServices $services ) : AuthManager {
		$authManager = new AuthManager(
			RequestContext::getMain()->getRequest(),
			$services->getMainConfig(),
			$services->getObjectFactory(),
			$services->getPermissionManager(),
			$services->getHookContainer()
		);
		$authManager->setLogger( LoggerFactory::getInstance( 'authentication' ) );
		return $authManager;
	},

	'BadFileLookup' => function ( MediaWikiServices $services ) : BadFileLookup {
		return new BadFileLookup(
			function () {
				return wfMessage( 'bad_image_list' )->inContentLanguage()->plain();
			},
			$services->getLocalServerObjectCache(),
			$services->getRepoGroup(),
			$services->getTitleParser(),
			$services->getHookContainer()
		);
	},

	'BlobStore' => function ( MediaWikiServices $services ) : BlobStore {
		return $services->getService( '_SqlBlobStore' );
	},

	'BlobStoreFactory' => function ( MediaWikiServices $services ) : BlobStoreFactory {
		return new BlobStoreFactory(
			$services->getDBLoadBalancerFactory(),
			$services->getExternalStoreAccess(),
			$services->getMainWANObjectCache(),
			new ServiceOptions( BlobStoreFactory::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig() )
		);
	},

	'BlockErrorFormatter' => function () : BlockErrorFormatter {
		return new BlockErrorFormatter();
	},

	'BlockManager' => function ( MediaWikiServices $services ) : BlockManager {
		return new BlockManager(
			new ServiceOptions(
				BlockManager::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			),
			$services->getPermissionManager(),
			LoggerFactory::getInstance( 'BlockManager' ),
			$services->getHookContainer()
		);
	},

	'BlockPermissionCheckerFactory' => function (
		MediaWikiServices $services
	) : BlockPermissionCheckerFactory {
		return new BlockPermissionCheckerFactory(
			$services->getPermissionManager()
		);
	},

	'BlockRestrictionStore' => function ( MediaWikiServices $services ) : BlockRestrictionStore {
		return new BlockRestrictionStore(
			$services->getDBLoadBalancer()
		);
	},

	'ChangeTagDefStore' => function ( MediaWikiServices $services ) : NameTableStore {
		return $services->getNameTableStoreFactory()->getChangeTagDef();
	},

	'CommentStore' => function ( MediaWikiServices $services ) : CommentStore {
		return new CommentStore(
			$services->getContentLanguage(),
			MIGRATION_NEW
		);
	},

	'ConfigFactory' => function ( MediaWikiServices $services ) : ConfigFactory {
		// Use the bootstrap config to initialize the ConfigFactory.
		$registry = $services->getBootstrapConfig()->get( 'ConfigRegistry' );
		$factory = new ConfigFactory();

		foreach ( $registry as $name => $callback ) {
			$factory->register( $name, $callback );
		}
		return $factory;
	},

	'ConfigRepository' => function ( MediaWikiServices $services ) : ConfigRepository {
		return new ConfigRepository( $services->getConfigFactory() );
	},

	'ConfiguredReadOnlyMode' => function ( MediaWikiServices $services ) : ConfiguredReadOnlyMode {
		$config = $services->getMainConfig();
		return new ConfiguredReadOnlyMode(
			$config->get( 'ReadOnly' ),
			$config->get( 'ReadOnlyFile' )
		);
	},

	'ContentHandlerFactory' => function ( MediaWikiServices $services ) : IContentHandlerFactory {
		$contentHandlerConfig = $services->getMainConfig()->get( 'ContentHandlers' );

		return new ContentHandlerFactory(
			$contentHandlerConfig,
			$services->getObjectFactory(),
			$services->getHookContainer(),
			LoggerFactory::getInstance( 'ContentHandler' )
		);
	},

	'ContentLanguage' => function ( MediaWikiServices $services ) : Language {
		return $services->getLanguageFactory()->getLanguage(
			$services->getMainConfig()->get( 'LanguageCode' ) );
	},

	'ContentModelChangeFactory' => function ( MediaWikiServices $services ) : ContentModelChangeFactory {
		return $services->getService( '_PageCommandFactory' );
	},

	'ContentModelStore' => function ( MediaWikiServices $services ) : NameTableStore {
		return $services->getNameTableStoreFactory()->getContentModels();
	},

	'ContributionsLookup' => function ( MediaWikiServices $services ) : ContributionsLookup {
		return new ContributionsLookup( $services->getRevisionStore() );
	},

	'CryptHKDF' => function ( MediaWikiServices $services ) : CryptHKDF {
		$config = $services->getMainConfig();

		$secret = $config->get( 'HKDFSecret' ) ?: $config->get( 'SecretKey' );
		if ( !$secret ) {
			throw new RuntimeException( "Cannot use MWCryptHKDF without a secret." );
		}

		// In HKDF, the context can be known to the attacker, but this will
		// keep simultaneous runs from producing the same output.
		$context = [ microtime(), getmypid(), gethostname() ];

		// Setup salt cache. Use APC, or fallback to the main cache if it isn't setup
		$cache = $services->getLocalServerObjectCache();
		if ( $cache instanceof EmptyBagOStuff ) {
			$cache = ObjectCache::getLocalClusterInstance();
		}

		return new CryptHKDF( $secret, $config->get( 'HKDFAlgorithm' ), $cache, $context );
	},

	'DateFormatterFactory' => function () : DateFormatterFactory {
		return new DateFormatterFactory;
	},

	'DBLoadBalancer' => function ( MediaWikiServices $services ) : Wikimedia\Rdbms\ILoadBalancer {
		// just return the default LB from the DBLoadBalancerFactory service
		return $services->getDBLoadBalancerFactory()->getMainLB();
	},

	'DBLoadBalancerFactory' =>
	function ( MediaWikiServices $services ) : Wikimedia\Rdbms\LBFactory {
		$mainConfig = $services->getMainConfig();

		try {
			$stash = $services->getMainObjectStash();
		} catch ( RecursiveServiceDependencyException $e ) {
			$stash = new EmptyBagOStuff(); // T141804: handle cases like CACHE_DB
		}

		if ( $stash instanceof EmptyBagOStuff ) {
			// Use process cache if the main stash is disabled or there was recursion
			$stash = new HashBagOStuff( [ 'maxKeys' => 100 ] );
		}

		try {
			$wanCache = $services->getMainWANObjectCache();
		} catch ( RecursiveServiceDependencyException $e ) {
			$wanCache = WANObjectCache::newEmpty(); // T141804: handle cases like CACHE_DB
		}

		$lbConf = MWLBFactory::applyDefaultConfig(
			$mainConfig->get( 'LBFactoryConf' ),
			new ServiceOptions( MWLBFactory::APPLY_DEFAULT_CONFIG_OPTIONS, $mainConfig ),
			$services->getConfiguredReadOnlyMode(),
			$services->getLocalServerObjectCache(),
			$stash,
			$wanCache
		);

		$class = MWLBFactory::getLBFactoryClass( $lbConf );
		$instance = new $class( $lbConf );

		MWLBFactory::setDomainAliases( $instance );

		return $instance;
	},

	'Emailer' => function ( MediaWikiServices $services ) : IEmailer {
		return new Emailer();
	},

	'EventRelayerGroup' => function ( MediaWikiServices $services ) : EventRelayerGroup {
		return new EventRelayerGroup( $services->getMainConfig()->get( 'EventRelayerConfig' ) );
	},

	'ExternalStoreAccess' => function ( MediaWikiServices $services ) : ExternalStoreAccess {
		return new ExternalStoreAccess(
			$services->getExternalStoreFactory(),
			LoggerFactory::getInstance( 'ExternalStore' )
		);
	},

	'ExternalStoreFactory' => function ( MediaWikiServices $services ) : ExternalStoreFactory {
		$config = $services->getMainConfig();
		$writeStores = $config->get( 'DefaultExternalStore' );

		return new ExternalStoreFactory(
			$config->get( 'ExternalStores' ),
			( $writeStores !== false ) ? (array)$writeStores : [],
			$services->getDBLoadBalancer()->getLocalDomainID(),
			LoggerFactory::getInstance( 'ExternalStore' )
		);
	},

	'FileBackendGroup' => function ( MediaWikiServices $services ) : FileBackendGroup {
		$mainConfig = $services->getMainConfig();

		$ld = WikiMap::getCurrentWikiDbDomain();
		$fallbackWikiId = WikiMap::getWikiIdFromDbDomain( $ld );
		// If the local wiki ID and local domain ID do not match, probably due to a non-default
		// schema, issue a warning. A non-default schema indicates that it might be used to
		// disambiguate different wikis.
		$legacyDomainId = strlen( $ld->getTablePrefix() )
			? "{$ld->getDatabase()}-{$ld->getTablePrefix()}"
			: $ld->getDatabase();
		if ( $ld->getSchema() !== null && $legacyDomainId !== $fallbackWikiId ) {
			wfWarn(
				"Legacy default 'domainId' is '$legacyDomainId' but wiki ID is '$fallbackWikiId'."
			);
		}

		$cache = $services->getLocalServerObjectCache();
		if ( $cache instanceof EmptyBagOStuff ) {
			$cache = new HashBagOStuff;
		}

		return new FileBackendGroup(
			new ServiceOptions( FileBackendGroup::CONSTRUCTOR_OPTIONS, $mainConfig,
				[ 'fallbackWikiId' => $fallbackWikiId ] ),
			$services->getConfiguredReadOnlyMode(),
			$cache,
			$services->getMainWANObjectCache(),
			$services->getMimeAnalyzer(),
			$services->getLockManagerGroupFactory(),
			$services->getTempFSFileFactory(),
			$services->getObjectFactory()
		);
	},

	'GenderCache' => function ( MediaWikiServices $services ) : GenderCache {
		$nsInfo = $services->getNamespaceInfo();
		// Database layer may be disabled, so processing without database connection
		$dbLoadBalancer = $services->isServiceDisabled( 'DBLoadBalancer' )
			? null
			: $services->getDBLoadBalancer();
		return new GenderCache( $nsInfo, $dbLoadBalancer, $services->get( '_DefaultOptionsLookup' ) );
	},

	'GlobalIdGenerator' => function ( MediaWikiServices $services ) : GlobalIdGenerator {
		$mainConfig = $services->getMainConfig();

		return new GlobalIdGenerator(
			$mainConfig->get( 'TmpDirectory' ),
			// Ignore APC-like caches in CLI mode since there is no meaningful persistence.
			// This avoids having counters restart with each script run. The ID generator
			// will fallback to using the disk in those cases.
			$mainConfig->get( 'CommandLineMode' )
				? new EmptyBagOStuff()
				: $services->getLocalServerObjectCache(),
			function ( $command ) {
				return wfShellExec( $command );
			}
		);
	},

	'HookContainer' => function ( MediaWikiServices $services ) : HookContainer {
		$extRegistry = ExtensionRegistry::getInstance();
		$extDeprecatedHooks = $extRegistry->getAttribute( 'DeprecatedHooks' );
		$deprecatedHooks = new DeprecatedHooks( $extDeprecatedHooks );
		$hookRegistry = new GlobalHookRegistry( $extRegistry, $deprecatedHooks );
		return new HookContainer(
			$hookRegistry,
			$services->getObjectFactory()
		);
	},

	'HtmlCacheUpdater' => function ( MediaWikiServices $services ) : HtmlCacheUpdater {
		$config = $services->getMainConfig();

		return new HtmlCacheUpdater(
			$services->getHookContainer(),
			$config->get( 'CdnReboundPurgeDelay' ),
			$config->get( 'UseFileCache' ),
			$config->get( 'CdnMaxAge' )
		);
	},

	'HttpRequestFactory' =>
	function ( MediaWikiServices $services ) : HttpRequestFactory {
		return new HttpRequestFactory(
			new ServiceOptions(
				HttpRequestFactory::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			),
			LoggerFactory::getInstance( 'http' )
		);
	},

	'InterwikiLookup' => function ( MediaWikiServices $services ) : InterwikiLookup {
		$config = $services->getMainConfig();
		return new ClassicInterwikiLookup(
			$services->getContentLanguage(),
			$services->getMainWANObjectCache(),
			$services->getHookContainer(),
			$config->get( 'InterwikiExpiry' ),
			$config->get( 'InterwikiCache' ),
			$config->get( 'InterwikiScopes' ),
			$config->get( 'InterwikiFallbackSite' )
		);
	},

	'JobRunner' => function ( MediaWikiServices $services ) : JobRunner {
		return new JobRunner(
			new ServiceOptions( JobRunner::CONSTRUCTOR_OPTIONS, $services->getMainConfig() ),
			$services->getDBLoadBalancerFactory(),
			JobQueueGroup::singleton(),
			$services->getReadOnlyMode(),
			$services->getLinkCache(),
			$services->getStatsdDataFactory(),
			LoggerFactory::getInstance( 'runJobs' )
		);
	},

	'LanguageConverterFactory' => function ( MediaWikiServices $services ) : LanguageConverterFactory {
		$usePigLatinVariant = $services->getMainConfig()->get( 'UsePigLatinVariant' );
		return new LanguageConverterFactory( $usePigLatinVariant, function () use ( $services ) {
			return $services->getContentLanguage();
		} );
	},

	'LanguageFactory' => function ( MediaWikiServices $services ) : LanguageFactory {
		return new LanguageFactory(
			new ServiceOptions( LanguageFactory::CONSTRUCTOR_OPTIONS, $services->getMainConfig() ),
			$services->getLocalisationCache(),
			$services->getLanguageNameUtils(),
			$services->getLanguageFallback(),
			$services->getLanguageConverterFactory(),
			$services->getHookContainer()
		);
	},

	'LanguageFallback' => function ( MediaWikiServices $services ) : LanguageFallback {
		return new LanguageFallback(
			$services->getMainConfig()->get( 'LanguageCode' ),
			$services->getLocalisationCache(),
			$services->getLanguageNameUtils()
		);
	},

	'LanguageNameUtils' => function ( MediaWikiServices $services ) : LanguageNameUtils {
		return new LanguageNameUtils(
			new ServiceOptions(
				LanguageNameUtils::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			),
			$services->getHookContainer()
		);
	},

	'LinkBatchFactory' => function ( MediaWikiServices $services ) : LinkBatchFactory {
		return new LinkBatchFactory(
			$services->getLinkCache(),
			$services->getTitleFormatter(),
			$services->getContentLanguage(),
			$services->getGenderCache(),
			$services->getDBLoadBalancer()
		);
	},

	'LinkCache' => function ( MediaWikiServices $services ) : LinkCache {
		return new LinkCache(
			$services->getTitleFormatter(),
			$services->getMainWANObjectCache(),
			$services->getNamespaceInfo()
		);
	},

	'LinkRenderer' => function ( MediaWikiServices $services ) : LinkRenderer {
		if ( defined( 'MW_NO_SESSION' ) ) {
			return $services->getLinkRendererFactory()->create();
		} else {
			// Normally information from the current request would not be passed in here;
			// this is an exception. (See also the class documentation.)
			return $services->getLinkRendererFactory()->createForUser(
				RequestContext::getMain()->getUser()
			);
		}
	},

	'LinkRendererFactory' => function ( MediaWikiServices $services ) : LinkRendererFactory {
		return new LinkRendererFactory(
			$services->getTitleFormatter(),
			$services->getLinkCache(),
			$services->getNamespaceInfo(),
			$services->getSpecialPageFactory(),
			$services->getHookContainer()
		);
	},

	'LocalisationCache' => function ( MediaWikiServices $services ) : LocalisationCache {
		$conf = $services->getMainConfig()->get( 'LocalisationCacheConf' );

		$logger = LoggerFactory::getInstance( 'localisation' );

		$store = LocalisationCache::getStoreFromConf(
			$conf, $services->getMainConfig()->get( 'CacheDirectory' ) );
		$logger->debug( 'LocalisationCache using store ' . get_class( $store ) );

		return new $conf['class'](
			new ServiceOptions(
				LocalisationCache::CONSTRUCTOR_OPTIONS,
				// Two of the options are stored in $wgLocalisationCacheConf
				$conf,
				// In case someone set that config variable and didn't reset all keys, set defaults.
				[
					'forceRecache' => false,
					'manualRecache' => false,
				],
				// Some other options come from config itself
				$services->getMainConfig()
			),
			$store,
			$logger,
			[ function () use ( $services ) {
				// NOTE: Make sure we use the same cache object that is assigned in the
				// constructor of the MessageBlobStore class used by ResourceLoader.
				// T231866: Avoid circular dependency via ResourceLoader.
				MessageBlobStore::clearGlobalCacheEntry( $services->getMainWANObjectCache() );
			} ],
			$services->getLanguageNameUtils(),
			$services->getHookContainer()
		);
	},

	'LocalServerObjectCache' => function ( MediaWikiServices $services ) : BagOStuff {
		return ObjectCache::makeLocalServerCache();
	},

	'LockManagerGroupFactory' => function ( MediaWikiServices $services ) : LockManagerGroupFactory {
		return new LockManagerGroupFactory(
			WikiMap::getCurrentWikiDbDomain()->getId(),
			$services->getMainConfig()->get( 'LockManagers' ),
			$services->getDBLoadBalancerFactory()
		);
	},

	'MagicWordFactory' => function ( MediaWikiServices $services ) : MagicWordFactory {
		return new MagicWordFactory(
			$services->getContentLanguage(),
			$services->getHookContainer()
		);
	},

	'MainConfig' => function ( MediaWikiServices $services ) : Config {
		// Use the 'main' config from the ConfigFactory service.
		return $services->getConfigFactory()->makeConfig( 'main' );
	},

	'MainObjectStash' => function ( MediaWikiServices $services ) : BagOStuff {
		$mainConfig = $services->getMainConfig();

		$id = $mainConfig->get( 'MainStash' );
		if ( !isset( $mainConfig->get( 'ObjectCaches' )[$id] ) ) {
			throw new UnexpectedValueException(
				"Cache type \"$id\" is not present in \$wgObjectCaches." );
		}

		$params = $mainConfig->get( 'ObjectCaches' )[$id];

		$store = ObjectCache::newFromParams( $params, $mainConfig );
		$store->getLogger()->debug( 'MainObjectStash using store {class}', [
			'class' => get_class( $store )
		] );

		return $store;
	},

	'MainWANObjectCache' => function ( MediaWikiServices $services ) : WANObjectCache {
		$mainConfig = $services->getMainConfig();

		$wanId = $mainConfig->get( 'MainWANCache' );
		$wanParams = $mainConfig->get( 'WANObjectCaches' )[$wanId] ?? null;
		if ( !$wanParams ) {
			throw new UnexpectedValueException(
				"wgWANObjectCaches must have \"$wanId\" set (via wgMainWANCache)"
			);
		}

		$cacheId = $wanParams['cacheId'];
		$wanClass = $wanParams['class'];
		unset( $wanParams['cacheId'] );
		unset( $wanParams['class'] );

		$storeParams = $mainConfig->get( 'ObjectCaches' )[$cacheId] ?? null;
		if ( !$storeParams ) {
			throw new UnexpectedValueException(
				"wgObjectCaches must have \"$cacheId\" set (via wgWANObjectCaches)"
			);
		}
		$store = ObjectCache::newFromParams( $storeParams, $mainConfig );
		$logger = $store->getLogger();
		$logger->debug( 'MainWANObjectCache using store {class}', [
			'class' => get_class( $store )
		] );

		$wanParams['cache'] = $store;
		$wanParams['logger'] = $logger;
		$wanParams['secret'] = $wanParams['secret'] ?? $mainConfig->get( 'SecretKey' );
		if ( !$mainConfig->get( 'CommandLineMode' ) ) {
			// Send the statsd data post-send on HTTP requests; avoid in CLI mode (T181385)
			$wanParams['stats'] = $services->getStatsdDataFactory();
			// Let pre-emptive refreshes happen post-send on HTTP requests
			$wanParams['asyncHandler'] = [ DeferredUpdates::class, 'addCallableUpdate' ];
		}

		$instance = new $wanClass( $wanParams );

		'@phan-var WANObjectCache $instance';
		return $instance;
	},

	'MediaHandlerFactory' => function ( MediaWikiServices $services ) : MediaHandlerFactory {
		return new MediaHandlerFactory(
			$services->getMainConfig()->get( 'MediaHandlers' )
		);
	},

	'MergeHistoryFactory' => function ( MediaWikiServices $services ) : MergeHistoryFactory {
		return $services->getService( '_PageCommandFactory' );
	},

	'MessageCache' => function ( MediaWikiServices $services ) : MessageCache {
		$mainConfig = $services->getMainConfig();
		$clusterCache = ObjectCache::getInstance( $mainConfig->get( 'MessageCacheType' ) );
		$srvCache = $mainConfig->get( 'UseLocalMessageCache' )
			? $services->getLocalServerObjectCache()
			: new EmptyBagOStuff();

		$logger = LoggerFactory::getInstance( 'MessageCache' );
		$logger->debug( 'MessageCache using store {class}', [
			'class' => get_class( $clusterCache )
		] );

		return new MessageCache(
			$services->getMainWANObjectCache(),
			$clusterCache,
			$srvCache,
			$services->getContentLanguage(),
			$services->getLanguageConverterFactory()->getLanguageConverter(),
			$logger,
			[ 'useDB' => $mainConfig->get( 'UseDatabaseMessages' ) ],
			$services->getLanguageFactory(),
			$services->getLocalisationCache(),
			$services->getLanguageNameUtils(),
			$services->getLanguageFallback(),
			$services->getHookContainer()
		);
	},

	'MessageFormatterFactory' => function () : IMessageFormatterFactory {
		return new MessageFormatterFactory();
	},

	'MimeAnalyzer' => function ( MediaWikiServices $services ) : MimeAnalyzer {
		$logger = LoggerFactory::getInstance( 'Mime' );
		$mainConfig = $services->getMainConfig();
		$hookRunner = new HookRunner( $services->getHookContainer() );
		$params = [
			'typeFile' => $mainConfig->get( 'MimeTypeFile' ),
			'infoFile' => $mainConfig->get( 'MimeInfoFile' ),
			'xmlTypes' => $mainConfig->get( 'XMLMimeTypes' ),
			'guessCallback' =>
				function ( $mimeAnalyzer, &$head, &$tail, $file, &$mime )
				use ( $logger, $hookRunner ) {
					// Also test DjVu
					$deja = new DjVuImage( $file );
					if ( $deja->isValid() ) {
						$logger->info( "Detected $file as image/vnd.djvu\n" );
						$mime = 'image/vnd.djvu';

						return;
					}
					// Some strings by reference for performance - assuming well-behaved hooks
					$hookRunner->onMimeMagicGuessFromContent(
						$mimeAnalyzer, $head, $tail, $file, $mime );
				},
			'extCallback' => function ( $mimeAnalyzer, $ext, &$mime ) use ( $hookRunner ) {
				// Media handling extensions can improve the MIME detected
				$hookRunner->onMimeMagicImproveFromExtension( $mimeAnalyzer, $ext, $mime );
			},
			'initCallback' => function ( $mimeAnalyzer ) use ( $hookRunner ) {
				// Allow media handling extensions adding MIME-types and MIME-info
				$hookRunner->onMimeMagicInit( $mimeAnalyzer );
			},
			'logger' => $logger
		];

		if ( $params['infoFile'] === 'includes/mime.info' ) {
			$params['infoFile'] = MimeAnalyzer::USE_INTERNAL;
		}

		if ( $params['typeFile'] === 'includes/mime.types' ) {
			$params['typeFile'] = MimeAnalyzer::USE_INTERNAL;
		}

		$detectorCmd = $mainConfig->get( 'MimeDetectorCommand' );
		if ( $detectorCmd ) {
			$factory = $services->getShellCommandFactory();
			$params['detectCallback'] = function ( $file ) use ( $detectorCmd, $factory ) {
				$result = $factory->create()
					// $wgMimeDetectorCommand can contain commands with parameters
					->unsafeParams( $detectorCmd )
					->params( $file )
					->execute();
				return $result->getStdout();
			};
		}

		return new MimeAnalyzer( $params );
	},

	'MovePageFactory' => function ( MediaWikiServices $services ) : MovePageFactory {
		return $services->getService( '_PageCommandFactory' );
	},

	'NamespaceInfo' => function ( MediaWikiServices $services ) : NamespaceInfo {
		return new NamespaceInfo(
			new ServiceOptions( NamespaceInfo::CONSTRUCTOR_OPTIONS, $services->getMainConfig() ),
			$services->getHookContainer()
		);
	},

	'NameTableStoreFactory' => function ( MediaWikiServices $services ) : NameTableStoreFactory {
		return new NameTableStoreFactory(
			$services->getDBLoadBalancerFactory(),
			$services->getMainWANObjectCache(),
			LoggerFactory::getInstance( 'NameTableSqlStore' )
		);
	},

	'ObjectFactory' => function ( MediaWikiServices $services ) : ObjectFactory {
		return new ObjectFactory( $services );
	},

	'OldRevisionImporter' => function ( MediaWikiServices $services ) : OldRevisionImporter {
		return new ImportableOldRevisionImporter(
			true,
			LoggerFactory::getInstance( 'OldRevisionImporter' ),
			$services->getDBLoadBalancer(),
			$services->getRevisionStore(),
			$services->getSlotRoleRegistry()
		);
	},

	'PageEditStash' => function ( MediaWikiServices $services ) : PageEditStash {
		$config = $services->getMainConfig();

		return new PageEditStash(
			ObjectCache::getLocalClusterInstance(),
			$services->getDBLoadBalancer(),
			LoggerFactory::getInstance( 'StashEdit' ),
			$services->getStatsdDataFactory(),
			$services->getHookContainer(),
			defined( 'MEDIAWIKI_JOB_RUNNER' ) || $config->get( 'CommandLineMode' )
				? PageEditStash::INITIATOR_JOB_OR_CLI
				: PageEditStash::INITIATOR_USER
		);
	},

	'Parser' => function ( MediaWikiServices $services ) : Parser {
		return $services->getParserFactory()->create();
	},

	'ParserCache' => function ( MediaWikiServices $services ) : ParserCache {
		$config = $services->getMainConfig();
		$cache = ObjectCache::getInstance( $config->get( 'ParserCacheType' ) );
		wfDebugLog( 'caches', 'parser: ' . get_class( $cache ) );

		return new ParserCache(
			$cache,
			$config->get( 'CacheEpoch' ),
			$services->getHookContainer(),
			$services->getStatsdDataFactory()
		);
	},

	'ParserFactory' => function ( MediaWikiServices $services ) : ParserFactory {
		$options = new ServiceOptions( Parser::CONSTRUCTOR_OPTIONS,
			// 'class'
			// Note that this value is ignored by ParserFactory and is always
			// Parser::class for legacy reasons; we'll introduce a new
			// mechanism for selecting an alternate parser in the future
			// (T236809)
			$services->getMainConfig()->get( 'ParserConf' ),
			// Make sure to have defaults in case someone overrode ParserConf with something silly
			[ 'class' => Parser::class ],
			// Plus a buch of actual config options
			$services->getMainConfig()
		);

		return new ParserFactory(
			$options,
			$services->getMagicWordFactory(),
			$services->getContentLanguage(),
			wfUrlProtocols(),
			$services->getSpecialPageFactory(),
			$services->getLinkRendererFactory(),
			$services->getNamespaceInfo(),
			LoggerFactory::getInstance( 'Parser' ),
			$services->getBadFileLookup(),
			$services->getLanguageConverterFactory(),
			$services->getHookContainer()
		);
	},

	'PasswordFactory' => function ( MediaWikiServices $services ) : PasswordFactory {
		$config = $services->getMainConfig();
		return new PasswordFactory(
			$config->get( 'PasswordConfig' ),
			$config->get( 'PasswordDefault' )
		);
	},

	'PasswordReset' => function ( MediaWikiServices $services ) : PasswordReset {
		$options = new ServiceOptions( PasswordReset::CONSTRUCTOR_OPTIONS, $services->getMainConfig() );
		return new PasswordReset(
			$options,
			$services->getAuthManager(),
			$services->getPermissionManager(),
			$services->getDBLoadBalancer(),
			LoggerFactory::getInstance( 'authentication' ),
			$services->getHookContainer()
		);
	},

	'PerDbNameStatsdDataFactory' =>
	function ( MediaWikiServices $services ) : StatsdDataFactoryInterface {
		$config = $services->getMainConfig();
		$wiki = $config->get( 'DBname' );
		return new PrefixingStatsdDataFactoryProxy(
			$services->getStatsdDataFactory(),
			$wiki
		);
	},

	'PermissionManager' => function ( MediaWikiServices $services ) : PermissionManager {
		return new PermissionManager(
			new ServiceOptions(
				PermissionManager::CONSTRUCTOR_OPTIONS, $services->getMainConfig()
			),
			$services->getSpecialPageFactory(),
			$services->getRevisionLookup(),
			$services->getNamespaceInfo(),
			$services->getBlockErrorFormatter(),
			$services->getHookContainer()
		);
	},

	'PreferencesFactory' => function ( MediaWikiServices $services ) : PreferencesFactory {
		$factory = new DefaultPreferencesFactory(
			new ServiceOptions(
				DefaultPreferencesFactory::CONSTRUCTOR_OPTIONS, $services->getMainConfig() ),
			$services->getContentLanguage(),
			$services->getAuthManager(),
			$services->getLinkRendererFactory()->create(),
			$services->getNamespaceInfo(),
			$services->getPermissionManager(),
			$services->getLanguageConverterFactory()->getLanguageConverter(),
			$services->getLanguageNameUtils(),
			$services->getHookContainer()
		);
		$factory->setLogger( LoggerFactory::getInstance( 'preferences' ) );

		return $factory;
	},

	'ProxyLookup' => function ( MediaWikiServices $services ) : ProxyLookup {
		$mainConfig = $services->getMainConfig();
		return new ProxyLookup(
			$mainConfig->get( 'CdnServers' ),
			$mainConfig->get( 'CdnServersNoPurge' )
		);
	},

	'ReadOnlyMode' => function ( MediaWikiServices $services ) : ReadOnlyMode {
		return new ReadOnlyMode(
			$services->getConfiguredReadOnlyMode(),
			$services->getDBLoadBalancer()
		);
	},

	'RepoGroup' => function ( MediaWikiServices $services ) : RepoGroup {
		$config = $services->getMainConfig();
		return new RepoGroup(
			$config->get( 'LocalFileRepo' ),
			$config->get( 'ForeignFileRepos' ),
			$services->getMainWANObjectCache()
		);
	},

	'ResourceLoader' => function ( MediaWikiServices $services ) : ResourceLoader {
		// @todo This should not take a Config object, but it's not so easy to remove because it
		// exposes it in a getter, which is actually used.
		global $IP;
		$config = $services->getMainConfig();

		$rl = new ResourceLoader(
			$config,
			LoggerFactory::getInstance( 'resourceloader' ),
			$config->get( 'ResourceLoaderUseObjectCacheForDeps' )
				? new KeyValueDependencyStore( $services->getMainObjectStash() )
				: new SqlModuleDependencyStore( $services->getDBLoadBalancer() )
		);

		$extRegistry = ExtensionRegistry::getInstance();
		// Attribute has precedence over config
		$modules = $extRegistry->getAttribute( 'ResourceModules' )
			+ $config->get( 'ResourceModules' );
		$moduleSkinStyles = $extRegistry->getAttribute( 'ResourceModuleSkinStyles' )
			+ $config->get( 'ResourceModuleSkinStyles' );

		$rl->setModuleSkinStyles( $moduleSkinStyles );
		$rl->addSource( $config->get( 'ResourceLoaderSources' ) );

		// Core modules, then extension/skin modules
		$rl->register( include "$IP/resources/Resources.php" );
		$rl->register( $modules );
		$hookRunner = new \MediaWiki\ResourceLoader\HookRunner( $services->getHookContainer() );
		$hookRunner->onResourceLoaderRegisterModules( $rl );

		$msgPosterAttrib = $extRegistry->getAttribute( 'MessagePosterModule' );
		$rl->register( 'mediawiki.messagePoster', [
			'localBasePath' => $IP,
			'debugRaw' => false,
			'scripts' => array_merge(
				[
					"resources/src/mediawiki.messagePoster/factory.js",
					"resources/src/mediawiki.messagePoster/MessagePoster.js",
					"resources/src/mediawiki.messagePoster/WikitextMessagePoster.js",
				],
				$msgPosterAttrib['scripts'] ?? []
			),
			'dependencies' => array_merge(
				[
					'oojs',
					'mediawiki.api',
					'mediawiki.ForeignApi',
				],
				$msgPosterAttrib['dependencies'] ?? []
			),
			'targets' => [ 'desktop', 'mobile' ],
		] );

		if ( $config->get( 'EnableJavaScriptTest' ) === true ) {
			$rl->registerTestModules();
		}

		return $rl;
	},

	'RevisionFactory' => function ( MediaWikiServices $services ) : RevisionFactory {
		return $services->getRevisionStore();
	},

	'RevisionLookup' => function ( MediaWikiServices $services ) : RevisionLookup {
		return $services->getRevisionStore();
	},

	'RevisionRenderer' => function ( MediaWikiServices $services ) : RevisionRenderer {
		$renderer = new RevisionRenderer(
			$services->getDBLoadBalancer(),
			$services->getSlotRoleRegistry()
		);

		$renderer->setLogger( LoggerFactory::getInstance( 'SaveParse' ) );
		return $renderer;
	},

	'RevisionStore' => function ( MediaWikiServices $services ) : RevisionStore {
		return $services->getRevisionStoreFactory()->getRevisionStore();
	},

	'RevisionStoreFactory' => function ( MediaWikiServices $services ) : RevisionStoreFactory {
		$config = $services->getMainConfig();

		if ( $config->has( 'MultiContentRevisionSchemaMigrationStage' ) ) {
			if ( $config->get( 'MultiContentRevisionSchemaMigrationStage' ) !== SCHEMA_COMPAT_NEW ) {
				throw new UnexpectedValueException(
					'The MultiContentRevisionSchemaMigrationStage setting is no longer supported!'
				);
			}
		}

		$store = new RevisionStoreFactory(
			$services->getDBLoadBalancerFactory(),
			$services->getBlobStoreFactory(),
			$services->getNameTableStoreFactory(),
			$services->getSlotRoleRegistry(),
			$services->getMainWANObjectCache(),
			$services->getCommentStore(),
			$services->getActorMigration(),
			LoggerFactory::getInstance( 'RevisionStore' ),
			$services->getContentHandlerFactory(),
			$services->getHookContainer()
		);

		return $store;
	},

	'SearchEngineConfig' => function ( MediaWikiServices $services ) : SearchEngineConfig {
		// @todo This should not take a Config object, but it's not so easy to remove because it
		// exposes it in a getter, which is actually used.
		return new SearchEngineConfig(
			$services->getMainConfig(),
			$services->getContentLanguage(),
			$services->getHookContainer(),
			ExtensionRegistry::getInstance()->getAttribute( 'SearchMappings' )
		);
	},

	'SearchEngineFactory' => function ( MediaWikiServices $services ) : SearchEngineFactory {
		return new SearchEngineFactory(
			$services->getSearchEngineConfig(),
			$services->getHookContainer()
		);
	},

	'ShellCommandFactory' => function ( MediaWikiServices $services ) : CommandFactory {
		$config = $services->getMainConfig();

		$limits = [
			'time' => $config->get( 'MaxShellTime' ),
			'walltime' => $config->get( 'MaxShellWallClockTime' ),
			'memory' => $config->get( 'MaxShellMemory' ),
			'filesize' => $config->get( 'MaxShellFileSize' ),
		];
		$cgroup = $config->get( 'ShellCgroup' );
		$restrictionMethod = $config->get( 'ShellRestrictionMethod' );

		$factory = new CommandFactory( $limits, $cgroup, $restrictionMethod );
		$factory->setLogger( LoggerFactory::getInstance( 'exec' ) );
		$factory->logStderr();

		return $factory;
	},

	'SiteLookup' => function ( MediaWikiServices $services ) : SiteLookup {
		// Use SiteStore as the SiteLookup as well. This was originally separated
		// to allow for a cacheable read-only interface, but this was never used.
		// SiteStore has caching (see below).
		return $services->getSiteStore();
	},

	'SiteStore' => function ( MediaWikiServices $services ) : SiteStore {
		$rawSiteStore = new DBSiteStore( $services->getDBLoadBalancer() );

		$cache = $services->getLocalServerObjectCache();
		if ( $cache instanceof EmptyBagOStuff ) {
			$cache = ObjectCache::getLocalClusterInstance();
		}

		return new CachingSiteStore( $rawSiteStore, $cache );
	},

	/** @suppress PhanTypeInvalidCallableArrayKey */
	'SkinFactory' => function ( MediaWikiServices $services ) : SkinFactory {
		$factory = new SkinFactory( $services->getObjectFactory() );

		$names = $services->getMainConfig()->get( 'ValidSkinNames' );

		foreach ( $names as $name => $skin ) {
			if ( is_array( $skin ) ) {
				$spec = $skin;
				$displayName = $skin['displayname'] ?? $name;
			} else {
				$displayName = $skin;
				$spec = [
					'class' => "Skin$skin"
				];
			}
			$factory->register( $name, $displayName, $spec );
		}

		// Register a hidden "fallback" skin
		$factory->register( 'fallback', 'Fallback', [
			'class' => SkinFallback::class,
			'args' => [
				[
					'styles' => [ 'mediawiki.skinning.interface' ],
					'templateDirectory' => __DIR__ . '/skins/templates/fallback',
				]
			]
		] );
		// Register a hidden skin for api output
		$factory->register( 'apioutput', 'ApiOutput', [
			'class' => SkinApi::class,
			'args' => [
				[
					'styles' => [ 'mediawiki.skinning.interface' ],
					'templateDirectory' => __DIR__ . '/skins/templates/apioutput',
				]
			]
		] );

		return $factory;
	},

	'SlotRoleRegistry' => function ( MediaWikiServices $services ) : SlotRoleRegistry {
		$config = $services->getMainConfig();
		$contentHandlerFactory = $services->getContentHandlerFactory();

		$registry = new SlotRoleRegistry(
			$services->getSlotRoleStore()
		);

		$registry->defineRole( 'main', function () use ( $config, $contentHandlerFactory ) {
			return new MainSlotRoleHandler(
				$config->get( 'NamespaceContentModels' ),
				$contentHandlerFactory
			);
		} );

		return $registry;
	},

	'SlotRoleStore' => function ( MediaWikiServices $services ) : NameTableStore {
		return $services->getNameTableStoreFactory()->getSlotRoles();
	},

	'SpamChecker' => function ( MediaWikiServices $services ) : SpamChecker {
		return new SpamChecker(
			(array)$services->getMainConfig()->get( 'SpamRegex' ),
			(array)$services->getMainConfig()->get( 'SummarySpamRegex' )
		);
	},

	'SpecialPageFactory' => function ( MediaWikiServices $services ) : SpecialPageFactory {
		return new SpecialPageFactory(
			new ServiceOptions(
				SpecialPageFactory::CONSTRUCTOR_OPTIONS, $services->getMainConfig() ),
			$services->getContentLanguage(),
			$services->getObjectFactory(),
			$services->getHookContainer()
		);
	},

	'StatsdDataFactory' => function ( MediaWikiServices $services ) : IBufferingStatsdDataFactory {
		return new BufferingStatsdDataFactory(
			rtrim( $services->getMainConfig()->get( 'StatsdMetricPrefix' ), '.' )
		);
	},

	'TalkPageNotificationManager' => function (
		MediaWikiServices $services
	) : TalkPageNotificationManager {
		return new TalkPageNotificationManager(
			new ServiceOptions(
				TalkPageNotificationManager::CONSTRUCTOR_OPTIONS, $services->getMainConfig()
			),
			$services->getDBLoadBalancer(),
			$services->getReadOnlyMode(),
			$services->getRevisionLookup()
		);
	},

	'TempFSFileFactory' => function ( MediaWikiServices $services ) : TempFSFileFactory {
		return new TempFSFileFactory( $services->getMainConfig()->get( 'TmpDirectory' ) );
	},

	'TitleFactory' => function () : TitleFactory {
		return new TitleFactory();
	},

	'TitleFormatter' => function ( MediaWikiServices $services ) : TitleFormatter {
		return $services->getService( '_MediaWikiTitleCodec' );
	},

	'TitleParser' => function ( MediaWikiServices $services ) : TitleParser {
		return $services->getService( '_MediaWikiTitleCodec' );
	},

	'UploadRevisionImporter' => function ( MediaWikiServices $services ) : UploadRevisionImporter {
		return new ImportableUploadRevisionImporter(
			$services->getMainConfig()->get( 'EnableUploads' ),
			LoggerFactory::getInstance( 'UploadRevisionImporter' )
		);
	},

	'UserEditTracker' => function ( MediaWikiServices $services ) : UserEditTracker {
		return new UserEditTracker(
			$services->getActorMigration(),
			$services->getDBLoadBalancer()
		);
	},

	'UserFactory' => function ( MediaWikiServices $services ) : UserFactory {
		return new UserFactory( $services->getUserNameUtils() );
	},

	'UserGroupManager' => function ( MediaWikiServices $services ) : UserGroupManager {
		return $services->getUserGroupManagerFactory()->getUserGroupManager();
	},

	'UserGroupManagerFactory' => function ( MediaWikiServices $services ) : UserGroupManagerFactory {
		return new UserGroupManagerFactory(
			new ServiceOptions(
				UserGroupManager::CONSTRUCTOR_OPTIONS, $services->getMainConfig()
			),
			$services->getConfiguredReadOnlyMode(),
			$services->getDBLoadBalancerFactory(),
			$services->getHookContainer(),
			$services->getUserEditTracker(),
			LoggerFactory::getInstance( 'UserGroupManager' ),
			[ function ( UserIdentity $user ) use ( $services ) {
				$services->getPermissionManager()->invalidateUsersRightsCache( $user );
				User::newFromIdentity( $user )->invalidateCache();
			} ]
		);
	},

	'UserNameUtils' => function ( MediaWikiServices $services ) : UserNameUtils {
		$messageFormatterFactory = new MessageFormatterFactory( Message::FORMAT_PLAIN );
		return new UserNameUtils(
			new ServiceOptions(
				UserNameUtils::CONSTRUCTOR_OPTIONS, $services->getMainConfig()
			),
			$services->getContentLanguage(),
			LoggerFactory::getInstance( 'UserNameUtils' ),
			$services->getTitleParser(),
			$messageFormatterFactory->getTextFormatter(
				$services->getContentLanguage()->getCode()
			),
			$services->getHookContainer()
		);
	},

	'UserOptionsLookup' => function ( MediaWikiServices $services ) : UserOptionsLookup {
		return $services->getUserOptionsManager();
	},

	'UserOptionsManager' => function ( MediaWikiServices $services ) : UserOptionsManager {
		return new UserOptionsManager(
			new ServiceOptions( UserOptionsManager::CONSTRUCTOR_OPTIONS, $services->getMainConfig() ),
			$services->get( '_DefaultOptionsLookup' ),
			$services->getLanguageConverterFactory(),
			$services->getDBLoadBalancer(),
			LoggerFactory::getInstance( 'UserOptionsManager' ),
			$services->getHookContainer()
		);
	},

	'VirtualRESTServiceClient' =>
	function ( MediaWikiServices $services ) : VirtualRESTServiceClient {
		$config = $services->getMainConfig()->get( 'VirtualRestConfig' );

		$vrsClient = new VirtualRESTServiceClient(
			$services->getHttpRequestFactory()->createMultiClient() );
		foreach ( $config['paths'] as $prefix => $serviceConfig ) {
			$class = $serviceConfig['class'];
			// Merge in the global defaults
			$constructArg = $serviceConfig['options'] ?? [];
			$constructArg += $config['global'];
			// Make the VRS service available at the mount point
			$vrsClient->mount( $prefix, [ 'class' => $class, 'config' => $constructArg ] );
		}

		return $vrsClient;
	},

	'WatchedItemQueryService' =>
	function ( MediaWikiServices $services ) : WatchedItemQueryService {
		return new WatchedItemQueryService(
			$services->getDBLoadBalancer(),
			$services->getCommentStore(),
			$services->getActorMigration(),
			$services->getWatchedItemStore(),
			$services->getPermissionManager(),
			$services->getHookContainer(),
			$services->getMainConfig()->get( 'WatchlistExpiry' )
		);
	},

	'WatchedItemStore' => function ( MediaWikiServices $services ) : WatchedItemStore {
		$store = new WatchedItemStore(
			new ServiceOptions( WatchedItemStore::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig() ),
			$services->getDBLoadBalancerFactory(),
			JobQueueGroup::singleton(),
			$services->getMainObjectStash(),
			new HashBagOStuff( [ 'maxKeys' => 100 ] ),
			$services->getReadOnlyMode(),
			$services->getNamespaceInfo(),
			$services->getRevisionLookup(),
			$services->getHookContainer()
		);
		$store->setStatsdDataFactory( $services->getStatsdDataFactory() );

		if ( $services->getMainConfig()->get( 'ReadOnlyWatchedItemStore' ) ) {
			$store = new NoWriteWatchedItemStore( $store );
		}

		return $store;
	},

	'WatchlistNotificationManager' =>
	function ( MediaWikiServices $services ) : WatchlistNotificationManager {
		return new WatchlistNotificationManager(
			new ServiceOptions(
				WatchlistNotificationManager::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			),
			$services->getHookContainer(),
			$services->getPermissionManager(),
			$services->getReadOnlyMode(),
			$services->getRevisionLookup(),
			$services->getTalkPageNotificationManager(),
			$services->getWatchedItemStore()
		);
	},

	'WikiRevisionOldRevisionImporterNoUpdates' =>
	function ( MediaWikiServices $services ) : ImportableOldRevisionImporter {
		return new ImportableOldRevisionImporter(
			false,
			LoggerFactory::getInstance( 'OldRevisionImporter' ),
			$services->getDBLoadBalancer(),
			$services->getRevisionStore(),
			$services->getSlotRoleRegistry()
		);
	},

	'_DefaultOptionsLookup' => function ( MediaWikiServices $services ) : DefaultOptionsLookup {
		return new DefaultOptionsLookup(
			new ServiceOptions( DefaultOptionsLookup::CONSTRUCTOR_OPTIONS, $services->getMainConfig() ),
			$services->getContentLanguage(),
			$services->getHookContainer()
		);
	},

	'_MediaWikiTitleCodec' => function ( MediaWikiServices $services ) : MediaWikiTitleCodec {
		return new MediaWikiTitleCodec(
			$services->getContentLanguage(),
			$services->getGenderCache(),
			$services->getMainConfig()->get( 'LocalInterwikis' ),
			$services->getInterwikiLookup(),
			$services->getNamespaceInfo()
		);
	},

	'_PageCommandFactory' => function ( MediaWikiServices $services ) : PageCommandFactory {
		return new PageCommandFactory(
			new ServiceOptions( PageCommandFactory::CONSTRUCTOR_OPTIONS, $services->getMainConfig() ),
			$services->getDBLoadBalancer(),
			$services->getNamespaceInfo(),
			$services->getWatchedItemStore(),
			$services->getPermissionManager(),
			$services->getRepoGroup(),
			$services->getContentHandlerFactory(),
			$services->getRevisionStore(),
			$services->getSpamChecker(),
			$services->getHookContainer()
		);
	},

	'_SqlBlobStore' => function ( MediaWikiServices $services ) : SqlBlobStore {
		return $services->getBlobStoreFactory()->newSqlBlobStore();
	},

	///////////////////////////////////////////////////////////////////////////
	// NOTE: When adding a service here, don't forget to add a getter function
	// in the MediaWikiServices class. The convenience getter should just call
	// $this->getService( 'FooBarService' ).
	///////////////////////////////////////////////////////////////////////////

];
