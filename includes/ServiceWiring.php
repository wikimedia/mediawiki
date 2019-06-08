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
 * @note As of version 1.27, MediaWiki is only beginning to use dependency injection.
 * The services defined here do not yet fully represent all services used by core,
 * much of the code still relies on global state for this accessing services.
 *
 * @since 1.27
 *
 * @see docs/injection.txt for an overview of using dependency injection in the
 *      MediaWiki code base.
 */

use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Block\BlockRestrictionStore;
use MediaWiki\Config\ConfigRepository;
use MediaWiki\Interwiki\ClassicInterwikiLookup;
use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Linker\LinkRendererFactory;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Preferences\PreferencesFactory;
use MediaWiki\Preferences\DefaultPreferencesFactory;
use MediaWiki\Revision\MainSlotRoleHandler;
use MediaWiki\Revision\RevisionFactory;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\SlotRoleRegistry;
use MediaWiki\Revision\RevisionRenderer;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\RevisionStoreFactory;
use MediaWiki\Shell\CommandFactory;
use MediaWiki\Special\SpecialPageFactory;
use MediaWiki\Storage\BlobStore;
use MediaWiki\Storage\BlobStoreFactory;
use MediaWiki\Storage\NameTableStoreFactory;
use MediaWiki\Storage\SqlBlobStore;

return [
	'ActorMigration' => function ( MediaWikiServices $services ) : ActorMigration {
		return new ActorMigration(
			$services->getMainConfig()->get( 'ActorTableSchemaMigrationStage' )
		);
	},

	'BlobStore' => function ( MediaWikiServices $services ) : BlobStore {
		return $services->getService( '_SqlBlobStore' );
	},

	'BlobStoreFactory' => function ( MediaWikiServices $services ) : BlobStoreFactory {
		return new BlobStoreFactory(
			$services->getDBLoadBalancerFactory(),
			$services->getMainWANObjectCache(),
			$services->getMainConfig(),
			$services->getContentLanguage()
		);
	},

	'BlockRestrictionStore' => function ( MediaWikiServices $services ) : BlockRestrictionStore {
		return new BlockRestrictionStore(
			$services->getDBLoadBalancer()
		);
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
		return new ConfiguredReadOnlyMode( $services->getMainConfig() );
	},

	'ContentLanguage' => function ( MediaWikiServices $services ) : Language {
		return Language::factory( $services->getMainConfig()->get( 'LanguageCode' ) );
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

	'CryptRand' => function () : CryptRand {
		return new CryptRand();
	},

	'DBLoadBalancer' => function ( MediaWikiServices $services ) : Wikimedia\Rdbms\LoadBalancer {
		// just return the default LB from the DBLoadBalancerFactory service
		return $services->getDBLoadBalancerFactory()->getMainLB();
	},

	'DBLoadBalancerFactory' =>
	function ( MediaWikiServices $services ) : Wikimedia\Rdbms\LBFactory {
		$mainConfig = $services->getMainConfig();

		$lbConf = MWLBFactory::applyDefaultConfig(
			$mainConfig->get( 'LBFactoryConf' ),
			$mainConfig,
			$services->getConfiguredReadOnlyMode(),
			$services->getLocalServerObjectCache(),
			$services->getMainObjectStash(),
			$services->getMainWANObjectCache()
		);
		$class = MWLBFactory::getLBFactoryClass( $lbConf );

		$instance = new $class( $lbConf );
		MWLBFactory::setSchemaAliases( $instance, $mainConfig );

		return $instance;
	},

	'EventRelayerGroup' => function ( MediaWikiServices $services ) : EventRelayerGroup {
		return new EventRelayerGroup( $services->getMainConfig()->get( 'EventRelayerConfig' ) );
	},

	'ExternalStoreFactory' => function ( MediaWikiServices $services ) : ExternalStoreFactory {
		$config = $services->getMainConfig();

		return new ExternalStoreFactory(
			$config->get( 'ExternalStores' )
		);
	},

	'GenderCache' => function ( MediaWikiServices $services ) : GenderCache {
		return new GenderCache();
	},

	'HttpRequestFactory' =>
	function ( MediaWikiServices $services ) : \MediaWiki\Http\HttpRequestFactory {
		return new \MediaWiki\Http\HttpRequestFactory();
	},

	'InterwikiLookup' => function ( MediaWikiServices $services ) : InterwikiLookup {
		$config = $services->getMainConfig();
		return new ClassicInterwikiLookup(
			$services->getContentLanguage(),
			$services->getMainWANObjectCache(),
			$config->get( 'InterwikiExpiry' ),
			$config->get( 'InterwikiCache' ),
			$config->get( 'InterwikiScopes' ),
			$config->get( 'InterwikiFallbackSite' )
		);
	},

	'LinkCache' => function ( MediaWikiServices $services ) : LinkCache {
		return new LinkCache(
			$services->getTitleFormatter(),
			$services->getMainWANObjectCache()
		);
	},

	'LinkRenderer' => function ( MediaWikiServices $services ) : LinkRenderer {
		if ( defined( 'MW_NO_SESSION' ) ) {
			return $services->getLinkRendererFactory()->create();
		} else {
			return $services->getLinkRendererFactory()->createForUser(
				RequestContext::getMain()->getUser()
			);
		}
	},

	'LinkRendererFactory' => function ( MediaWikiServices $services ) : LinkRendererFactory {
		return new LinkRendererFactory(
			$services->getTitleFormatter(),
			$services->getLinkCache()
		);
	},

	'LocalServerObjectCache' => function ( MediaWikiServices $services ) : BagOStuff {
		$cacheId = \ObjectCache::detectLocalServerCache();
		return \ObjectCache::newFromId( $cacheId );
	},

	'MagicWordFactory' => function ( MediaWikiServices $services ) : MagicWordFactory {
		return new MagicWordFactory( $services->getContentLanguage() );
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

		return \ObjectCache::newFromParams( $mainConfig->get( 'ObjectCaches' )[$id] );
	},

	'MainWANObjectCache' => function ( MediaWikiServices $services ) : WANObjectCache {
		$mainConfig = $services->getMainConfig();

		$id = $mainConfig->get( 'MainWANCache' );
		if ( !isset( $mainConfig->get( 'WANObjectCaches' )[$id] ) ) {
			throw new UnexpectedValueException(
				"WAN cache type \"$id\" is not present in \$wgWANObjectCaches." );
		}

		$params = $mainConfig->get( 'WANObjectCaches' )[$id];
		$objectCacheId = $params['cacheId'];
		if ( !isset( $mainConfig->get( 'ObjectCaches' )[$objectCacheId] ) ) {
			throw new UnexpectedValueException(
				"Cache type \"$objectCacheId\" is not present in \$wgObjectCaches." );
		}
		$params['store'] = $mainConfig->get( 'ObjectCaches' )[$objectCacheId];

		return \ObjectCache::newWANCacheFromParams( $params );
	},

	'MediaHandlerFactory' => function ( MediaWikiServices $services ) : MediaHandlerFactory {
		return new MediaHandlerFactory(
			$services->getMainConfig()->get( 'MediaHandlers' )
		);
	},

	'MimeAnalyzer' => function ( MediaWikiServices $services ) : MimeAnalyzer {
		$logger = LoggerFactory::getInstance( 'Mime' );
		$mainConfig = $services->getMainConfig();
		$params = [
			'typeFile' => $mainConfig->get( 'MimeTypeFile' ),
			'infoFile' => $mainConfig->get( 'MimeInfoFile' ),
			'xmlTypes' => $mainConfig->get( 'XMLMimeTypes' ),
			'guessCallback' =>
				function ( $mimeAnalyzer, &$head, &$tail, $file, &$mime ) use ( $logger ) {
					// Also test DjVu
					$deja = new DjVuImage( $file );
					if ( $deja->isValid() ) {
						$logger->info( "Detected $file as image/vnd.djvu\n" );
						$mime = 'image/vnd.djvu';

						return;
					}
					// Some strings by reference for performance - assuming well-behaved hooks
					Hooks::run(
						'MimeMagicGuessFromContent',
						[ $mimeAnalyzer, &$head, &$tail, $file, &$mime ]
					);
				},
			'extCallback' => function ( $mimeAnalyzer, $ext, &$mime ) {
				// Media handling extensions can improve the MIME detected
				Hooks::run( 'MimeMagicImproveFromExtension', [ $mimeAnalyzer, $ext, &$mime ] );
			},
			'initCallback' => function ( $mimeAnalyzer ) {
				// Allow media handling extensions adding MIME-types and MIME-info
				Hooks::run( 'MimeMagicInit', [ $mimeAnalyzer ] );
			},
			'logger' => $logger
		];

		if ( $params['infoFile'] === 'includes/mime.info' ) {
			$params['infoFile'] = __DIR__ . "/libs/mime/mime.info";
		}

		if ( $params['typeFile'] === 'includes/mime.types' ) {
			$params['typeFile'] = __DIR__ . "/libs/mime/mime.types";
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

	'NameTableStoreFactory' => function ( MediaWikiServices $services ) : NameTableStoreFactory {
		return new NameTableStoreFactory(
			$services->getDBLoadBalancerFactory(),
			$services->getMainWANObjectCache(),
			LoggerFactory::getInstance( 'NameTableSqlStore' )
		);
	},

	'OldRevisionImporter' => function ( MediaWikiServices $services ) : OldRevisionImporter {
		return new ImportableOldRevisionImporter(
			true,
			LoggerFactory::getInstance( 'OldRevisionImporter' ),
			$services->getDBLoadBalancer()
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
			$config->get( 'CacheEpoch' )
		);
	},

	'ParserFactory' => function ( MediaWikiServices $services ) : ParserFactory {
		return new ParserFactory(
			$services->getMainConfig()->get( 'ParserConf' ),
			$services->getMagicWordFactory(),
			$services->getContentLanguage(),
			wfUrlProtocols(),
			$services->getSpecialPageFactory(),
			$services->getMainConfig(),
			$services->getLinkRendererFactory()
		);
	},

	'PasswordFactory' => function ( MediaWikiServices $services ) : PasswordFactory {
		$config = $services->getMainConfig();
		return new PasswordFactory(
			$config->get( 'PasswordConfig' ),
			$config->get( 'PasswordDefault' )
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
		$config = $services->getMainConfig();
		return new PermissionManager(
			$services->getSpecialPageFactory(),
			$config->get( 'WhitelistRead' ),
			$config->get( 'WhitelistReadRegexp' ),
			$config->get( 'EmailConfirmToEdit' ),
			$config->get( 'BlockDisablesLogin' ) );
	},

	'PreferencesFactory' => function ( MediaWikiServices $services ) : PreferencesFactory {
		$factory = new DefaultPreferencesFactory(
			$services->getMainConfig(),
			$services->getContentLanguage(),
			AuthManager::singleton(),
			$services->getLinkRendererFactory()->create()
		);
		$factory->setLogger( LoggerFactory::getInstance( 'preferences' ) );

		return $factory;
	},

	'ProxyLookup' => function ( MediaWikiServices $services ) : ProxyLookup {
		$mainConfig = $services->getMainConfig();
		return new ProxyLookup(
			$mainConfig->get( 'SquidServers' ),
			$mainConfig->get( 'SquidServersNoPurge' )
		);
	},

	'ReadOnlyMode' => function ( MediaWikiServices $services ) : ReadOnlyMode {
		return new ReadOnlyMode(
			$services->getConfiguredReadOnlyMode(),
			$services->getDBLoadBalancer()
		);
	},

	'ResourceLoader' => function ( MediaWikiServices $services ) : ResourceLoader {
		global $IP;
		$config = $services->getMainConfig();

		$rl = new ResourceLoader(
			$config,
			LoggerFactory::getInstance( 'resourceloader' )
		);
		$rl->addSource( $config->get( 'ResourceLoaderSources' ) );
		$rl->register( include "$IP/resources/Resources.php" );

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
		$store = new RevisionStoreFactory(
			$services->getDBLoadBalancerFactory(),
			$services->getBlobStoreFactory(),
			$services->getNameTableStoreFactory(),
			$services->getSlotRoleRegistry(),
			$services->getMainWANObjectCache(),
			$services->getCommentStore(),
			$services->getActorMigration(),
			$config->get( 'MultiContentRevisionSchemaMigrationStage' ),
			LoggerFactory::getProvider(),
			$config->get( 'ContentHandlerUseDB' )
		);

		return $store;
	},

	'SearchEngineConfig' => function ( MediaWikiServices $services ) : SearchEngineConfig {
		return new SearchEngineConfig( $services->getMainConfig(),
			$services->getContentLanguage() );
	},

	'SearchEngineFactory' => function ( MediaWikiServices $services ) : SearchEngineFactory {
		return new SearchEngineFactory( $services->getSearchEngineConfig() );
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
		// to allow for a cacheable read-only interface (using FileBasedSiteLookup),
		// but this was never used. SiteStore has caching (see below).
		return $services->getSiteStore();
	},

	'SiteStore' => function ( MediaWikiServices $services ) : SiteStore {
		$rawSiteStore = new DBSiteStore( $services->getDBLoadBalancer() );

		// TODO: replace wfGetCache with a CacheFactory service.
		// TODO: replace wfIsHHVM with a capabilities service.
		$cache = wfGetCache( wfIsHHVM() ? CACHE_ACCEL : CACHE_ANYTHING );

		return new CachingSiteStore( $rawSiteStore, $cache );
	},

	'SkinFactory' => function ( MediaWikiServices $services ) : SkinFactory {
		$factory = new SkinFactory();

		$names = $services->getMainConfig()->get( 'ValidSkinNames' );

		foreach ( $names as $name => $skin ) {
			$factory->register( $name, $skin, function () use ( $name, $skin ) {
				$class = "Skin$skin";
				return new $class( $name );
			} );
		}
		// Register a hidden "fallback" skin
		$factory->register( 'fallback', 'Fallback', function () {
			return new SkinFallback;
		} );
		// Register a hidden skin for api output
		$factory->register( 'apioutput', 'ApiOutput', function () {
			return new SkinApi;
		} );

		return $factory;
	},

	'SlotRoleRegistry' => function ( MediaWikiServices $services ) : SlotRoleRegistry {
		$config = $services->getMainConfig();

		$registry = new SlotRoleRegistry(
			$services->getNameTableStoreFactory()->getSlotRoles()
		);

		$registry->defineRole( 'main', function () use ( $config ) {
			return new MainSlotRoleHandler(
				$config->get( 'NamespaceContentModels' )
			);
		} );

		return $registry;
	},

	'SpecialPageFactory' => function ( MediaWikiServices $services ) : SpecialPageFactory {
		return new SpecialPageFactory(
			$services->getMainConfig(),
			$services->getContentLanguage()
		);
	},

	'StatsdDataFactory' => function ( MediaWikiServices $services ) : IBufferingStatsdDataFactory {
		return new BufferingStatsdDataFactory(
			rtrim( $services->getMainConfig()->get( 'StatsdMetricPrefix' ), '.' )
		);
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

	'VirtualRESTServiceClient' =>
	function ( MediaWikiServices $services ) : VirtualRESTServiceClient {
		$config = $services->getMainConfig()->get( 'VirtualRestConfig' );

		$vrsClient = new VirtualRESTServiceClient( new MultiHttpClient( [] ) );
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
			$services->getWatchedItemStore()
		);
	},

	'WatchedItemStore' => function ( MediaWikiServices $services ) : WatchedItemStore {
		$store = new WatchedItemStore(
			$services->getDBLoadBalancerFactory(),
			JobQueueGroup::singleton(),
			$services->getMainObjectStash(),
			new HashBagOStuff( [ 'maxKeys' => 100 ] ),
			$services->getReadOnlyMode(),
			$services->getMainConfig()->get( 'UpdateRowsPerQuery' )
		);
		$store->setStatsdDataFactory( $services->getStatsdDataFactory() );

		if ( $services->getMainConfig()->get( 'ReadOnlyWatchedItemStore' ) ) {
			$store = new NoWriteWatchedItemStore( $store );
		}

		return $store;
	},

	'WikiRevisionOldRevisionImporterNoUpdates' =>
	function ( MediaWikiServices $services ) : ImportableOldRevisionImporter {
		return new ImportableOldRevisionImporter(
			false,
			LoggerFactory::getInstance( 'OldRevisionImporter' ),
			$services->getDBLoadBalancer()
		);
	},

	'_MediaWikiTitleCodec' => function ( MediaWikiServices $services ) : MediaWikiTitleCodec {
		return new MediaWikiTitleCodec(
			$services->getContentLanguage(),
			$services->getGenderCache(),
			$services->getMainConfig()->get( 'LocalInterwikis' ),
			$services->getInterwikiLookup()
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
