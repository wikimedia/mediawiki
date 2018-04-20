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

use MediaWiki\Auth\AuthManager;
use MediaWiki\Interwiki\ClassicInterwikiLookup;
use MediaWiki\Linker\LinkRendererFactory;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Preferences\DefaultPreferencesFactory;
use MediaWiki\Shell\CommandFactory;
use MediaWiki\Storage\BlobStoreFactory;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Storage\RevisionStore;
use MediaWiki\Storage\SqlBlobStore;
use Wikimedia\ObjectFactory;

return [
	'DBLoadBalancerFactory' => function ( MediaWikiServices $services ) {
		$mainConfig = $services->getMainConfig();

		$lbConf = MWLBFactory::applyDefaultConfig(
			$mainConfig->get( 'LBFactoryConf' ),
			$mainConfig,
			$services->getConfiguredReadOnlyMode()
		);
		$class = MWLBFactory::getLBFactoryClass( $lbConf );

		$instance = new $class( $lbConf );
		MWLBFactory::setSchemaAliases( $instance, $mainConfig );

		return $instance;
	},

	'DBLoadBalancer' => function ( MediaWikiServices $services ) {
		// just return the default LB from the DBLoadBalancerFactory service
		return $services->getDBLoadBalancerFactory()->getMainLB();
	},

	'SiteStore' => function ( MediaWikiServices $services ) {
		$rawSiteStore = new DBSiteStore( $services->getDBLoadBalancer() );

		// TODO: replace wfGetCache with a CacheFactory service.
		// TODO: replace wfIsHHVM with a capabilities service.
		$cache = wfGetCache( wfIsHHVM() ? CACHE_ACCEL : CACHE_ANYTHING );

		return new CachingSiteStore( $rawSiteStore, $cache );
	},

	'SiteLookup' => function ( MediaWikiServices $services ) {
		$cacheFile = $services->getMainConfig()->get( 'SitesCacheFile' );

		if ( $cacheFile !== false ) {
			return new FileBasedSiteLookup( $cacheFile );
		} else {
			// Use the default SiteStore as the SiteLookup implementation for now
			return $services->getSiteStore();
		}
	},

	'ConfigFactory' => function ( MediaWikiServices $services ) {
		// Use the bootstrap config to initialize the ConfigFactory.
		$registry = $services->getBootstrapConfig()->get( 'ConfigRegistry' );
		$factory = new ConfigFactory();

		foreach ( $registry as $name => $callback ) {
			$factory->register( $name, $callback );
		}
		return $factory;
	},

	'MainConfig' => function ( MediaWikiServices $services ) {
		// Use the 'main' config from the ConfigFactory service.
		return $services->getConfigFactory()->makeConfig( 'main' );
	},

	'InterwikiLookup' => function ( MediaWikiServices $services ) {
		global $wgContLang; // TODO: manage $wgContLang as a service
		$config = $services->getMainConfig();
		return new ClassicInterwikiLookup(
			$wgContLang,
			$services->getMainWANObjectCache(),
			$config->get( 'InterwikiExpiry' ),
			$config->get( 'InterwikiCache' ),
			$config->get( 'InterwikiScopes' ),
			$config->get( 'InterwikiFallbackSite' )
		);
	},

	'StatsdDataFactory' => function ( MediaWikiServices $services ) {
		return new BufferingStatsdDataFactory(
			rtrim( $services->getMainConfig()->get( 'StatsdMetricPrefix' ), '.' )
		);
	},

	'EventRelayerGroup' => function ( MediaWikiServices $services ) {
		return new EventRelayerGroup( $services->getMainConfig()->get( 'EventRelayerConfig' ) );
	},

	'SearchEngineFactory' => function ( MediaWikiServices $services ) {
		return new SearchEngineFactory( $services->getSearchEngineConfig() );
	},

	'SearchEngineConfig' => function ( MediaWikiServices $services ) {
		global $wgContLang;
		return new SearchEngineConfig( $services->getMainConfig(), $wgContLang );
	},

	'SkinFactory' => function ( MediaWikiServices $services ) {
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

	'WatchedItemStore' => function ( MediaWikiServices $services ) {
		$store = new WatchedItemStore(
			$services->getDBLoadBalancer(),
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

	'WatchedItemQueryService' => function ( MediaWikiServices $services ) {
		return new WatchedItemQueryService(
			$services->getDBLoadBalancer(),
			$services->getCommentStore(),
			$services->getActorMigration()
		);
	},

	'CryptRand' => function ( MediaWikiServices $services ) {
		$secretKey = $services->getMainConfig()->get( 'SecretKey' );
		return new CryptRand(
			[
				// To try vary the system information of the state a bit more
				// by including the system's hostname into the state
				'wfHostname',
				// It's mostly worthless but throw the wiki's id into the data
				// for a little more variance
				'wfWikiID',
				// If we have a secret key set then throw it into the state as well
				function () use ( $secretKey ) {
					return $secretKey ?: '';
				}
			],
			// The config file is likely the most often edited file we know should
			// be around so include its stat info into the state.
			// The constant with its location will almost always be defined, as
			// WebStart.php defines MW_CONFIG_FILE to $IP/LocalSettings.php unless
			// being configured with MW_CONFIG_CALLBACK (e.g. the installer).
			defined( 'MW_CONFIG_FILE' ) ? [ MW_CONFIG_FILE ] : [],
			LoggerFactory::getInstance( 'CryptRand' )
		);
	},

	'CryptHKDF' => function ( MediaWikiServices $services ) {
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

		return new CryptHKDF( $secret, $config->get( 'HKDFAlgorithm' ),
			$cache, $context, $services->getCryptRand()
		);
	},

	'MediaHandlerFactory' => function ( MediaWikiServices $services ) {
		return new MediaHandlerFactory(
			$services->getMainConfig()->get( 'MediaHandlers' )
		);
	},

	'MimeAnalyzer' => function ( MediaWikiServices $services ) {
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
						$logger->info( __METHOD__ . ": detected $file as image/vnd.djvu\n" );
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

		// XXX: MimeMagic::singleton currently requires this service to return an instance of MimeMagic
		return new MimeMagic( $params );
	},

	'ProxyLookup' => function ( MediaWikiServices $services ) {
		$mainConfig = $services->getMainConfig();
		return new ProxyLookup(
			$mainConfig->get( 'SquidServers' ),
			$mainConfig->get( 'SquidServersNoPurge' )
		);
	},

	'Parser' => function ( MediaWikiServices $services ) {
		$conf = $services->getMainConfig()->get( 'ParserConf' );
		return ObjectFactory::constructClassInstance( $conf['class'], [ $conf ] );
	},

	'ParserCache' => function ( MediaWikiServices $services ) {
		$config = $services->getMainConfig();
		$cache = ObjectCache::getInstance( $config->get( 'ParserCacheType' ) );
		wfDebugLog( 'caches', 'parser: ' . get_class( $cache ) );

		return new ParserCache(
			$cache,
			$config->get( 'CacheEpoch' )
		);
	},

	'LinkCache' => function ( MediaWikiServices $services ) {
		return new LinkCache(
			$services->getTitleFormatter(),
			$services->getMainWANObjectCache()
		);
	},

	'LinkRendererFactory' => function ( MediaWikiServices $services ) {
		return new LinkRendererFactory(
			$services->getTitleFormatter(),
			$services->getLinkCache()
		);
	},

	'LinkRenderer' => function ( MediaWikiServices $services ) {
		global $wgUser;

		if ( defined( 'MW_NO_SESSION' ) ) {
			return $services->getLinkRendererFactory()->create();
		} else {
			return $services->getLinkRendererFactory()->createForUser( $wgUser );
		}
	},

	'GenderCache' => function ( MediaWikiServices $services ) {
		return new GenderCache();
	},

	'_MediaWikiTitleCodec' => function ( MediaWikiServices $services ) {
		global $wgContLang;

		return new MediaWikiTitleCodec(
			$wgContLang,
			$services->getGenderCache(),
			$services->getMainConfig()->get( 'LocalInterwikis' )
		);
	},

	'TitleFormatter' => function ( MediaWikiServices $services ) {
		return $services->getService( '_MediaWikiTitleCodec' );
	},

	'TitleParser' => function ( MediaWikiServices $services ) {
		return $services->getService( '_MediaWikiTitleCodec' );
	},

	'MainObjectStash' => function ( MediaWikiServices $services ) {
		$mainConfig = $services->getMainConfig();

		$id = $mainConfig->get( 'MainStash' );
		if ( !isset( $mainConfig->get( 'ObjectCaches' )[$id] ) ) {
			throw new UnexpectedValueException(
				"Cache type \"$id\" is not present in \$wgObjectCaches." );
		}

		return \ObjectCache::newFromParams( $mainConfig->get( 'ObjectCaches' )[$id] );
	},

	'MainWANObjectCache' => function ( MediaWikiServices $services ) {
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

	'LocalServerObjectCache' => function ( MediaWikiServices $services ) {
		$mainConfig = $services->getMainConfig();

		if ( function_exists( 'apc_fetch' ) ) {
			$id = 'apc';
		} elseif ( function_exists( 'apcu_fetch' ) ) {
			$id = 'apcu';
		} elseif ( function_exists( 'wincache_ucache_get' ) ) {
			$id = 'wincache';
		} else {
			$id = CACHE_NONE;
		}

		if ( !isset( $mainConfig->get( 'ObjectCaches' )[$id] ) ) {
			throw new UnexpectedValueException(
				"Cache type \"$id\" is not present in \$wgObjectCaches." );
		}

		return \ObjectCache::newFromParams( $mainConfig->get( 'ObjectCaches' )[$id] );
	},

	'VirtualRESTServiceClient' => function ( MediaWikiServices $services ) {
		$config = $services->getMainConfig()->get( 'VirtualRestConfig' );

		$vrsClient = new VirtualRESTServiceClient( new MultiHttpClient( [] ) );
		foreach ( $config['paths'] as $prefix => $serviceConfig ) {
			$class = $serviceConfig['class'];
			// Merge in the global defaults
			$constructArg = isset( $serviceConfig['options'] )
				? $serviceConfig['options']
				: [];
			$constructArg += $config['global'];
			// Make the VRS service available at the mount point
			$vrsClient->mount( $prefix, [ 'class' => $class, 'config' => $constructArg ] );
		}

		return $vrsClient;
	},

	'ConfiguredReadOnlyMode' => function ( MediaWikiServices $services ) {
		return new ConfiguredReadOnlyMode( $services->getMainConfig() );
	},

	'ReadOnlyMode' => function ( MediaWikiServices $services ) {
		return new ReadOnlyMode(
			$services->getConfiguredReadOnlyMode(),
			$services->getDBLoadBalancer()
		);
	},

	'UploadRevisionImporter' => function ( MediaWikiServices $services ) {
		return new ImportableUploadRevisionImporter(
			$services->getMainConfig()->get( 'EnableUploads' ),
			LoggerFactory::getInstance( 'UploadRevisionImporter' )
		);
	},

	'OldRevisionImporter' => function ( MediaWikiServices $services ) {
		return new ImportableOldRevisionImporter(
			true,
			LoggerFactory::getInstance( 'OldRevisionImporter' ),
			$services->getDBLoadBalancer()
		);
	},

	'WikiRevisionOldRevisionImporterNoUpdates' => function ( MediaWikiServices $services ) {
		return new ImportableOldRevisionImporter(
			false,
			LoggerFactory::getInstance( 'OldRevisionImporter' ),
			$services->getDBLoadBalancer()
		);
	},

	'ShellCommandFactory' => function ( MediaWikiServices $services ) {
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

	'ExternalStoreFactory' => function ( MediaWikiServices $services ) {
		$config = $services->getMainConfig();

		return new ExternalStoreFactory(
			$config->get( 'ExternalStores' )
		);
	},

	'RevisionStore' => function ( MediaWikiServices $services ) {
		/** @var SqlBlobStore $blobStore */
		$blobStore = $services->getService( '_SqlBlobStore' );

		$store = new RevisionStore(
			$services->getDBLoadBalancer(),
			$blobStore,
			$services->getMainWANObjectCache(),
			$services->getCommentStore(),
			$services->getActorMigration()
		);

		$store->setLogger( LoggerFactory::getInstance( 'RevisionStore' ) );

		$config = $services->getMainConfig();
		$store->setContentHandlerUseDB( $config->get( 'ContentHandlerUseDB' ) );

		return $store;
	},

	'RevisionLookup' => function ( MediaWikiServices $services ) {
		return $services->getRevisionStore();
	},

	'RevisionFactory' => function ( MediaWikiServices $services ) {
		return $services->getRevisionStore();
	},

	'BlobStoreFactory' => function ( MediaWikiServices $services ) {
		global $wgContLang;
		return new BlobStoreFactory(
			$services->getDBLoadBalancer(),
			$services->getMainWANObjectCache(),
			$services->getMainConfig(),
			$wgContLang
		);
	},

	'BlobStore' => function ( MediaWikiServices $services ) {
		return $services->getService( '_SqlBlobStore' );
	},

	'_SqlBlobStore' => function ( MediaWikiServices $services ) {
		return $services->getBlobStoreFactory()->newSqlBlobStore();
	},

	'ContentModelStore' => function ( MediaWikiServices $services ) {
		return new NameTableStore(
			$services->getDBLoadBalancer(),
			$services->getMainWANObjectCache(),
			LoggerFactory::getInstance( 'NameTableSqlStore' ),
			'content_models',
			'model_id',
			'model_name'
			/**
			 * No strtolower normalization is added to the service as there are examples of
			 * extensions that do not stick to this assumption.
			 * - extensions/examples/DataPages define( 'CONTENT_MODEL_XML_DATA','XML_DATA' );
			 * - extensions/Scribunto define( 'CONTENT_MODEL_SCRIBUNTO', 'Scribunto' );
			 */
		);
	},

	'SlotRoleStore' => function ( MediaWikiServices $services ) {
		return new NameTableStore(
			$services->getDBLoadBalancer(),
			$services->getMainWANObjectCache(),
			LoggerFactory::getInstance( 'NameTableSqlStore' ),
			'slot_roles',
			'role_id',
			'role_name',
			'strtolower'
		);
	},

	'PreferencesFactory' => function ( MediaWikiServices $services ) {
		global $wgContLang;
		$authManager = AuthManager::singleton();
		$linkRenderer = $services->getLinkRendererFactory()->create();
		$config = $services->getMainConfig();
		$factory = new DefaultPreferencesFactory( $config, $wgContLang, $authManager, $linkRenderer );
		$factory->setLogger( LoggerFactory::getInstance( 'preferences' ) );

		return $factory;
	},

	'HttpRequestFactory' => function ( MediaWikiServices $services ) {
		return new \MediaWiki\Http\HttpRequestFactory();
	},

	'CommentStore' => function ( MediaWikiServices $services ) {
		global $wgContLang;
		return new CommentStore(
			$wgContLang,
			$services->getMainConfig()->get( 'CommentTableSchemaMigrationStage' )
		);
	},

	'ActorMigration' => function ( MediaWikiServices $services ) {
		return new ActorMigration(
			$services->getMainConfig()->get( 'ActorTableSchemaMigrationStage' )
		);
	},

	///////////////////////////////////////////////////////////////////////////
	// NOTE: When adding a service here, don't forget to add a getter function
	// in the MediaWikiServices class. The convenience getter should just call
	// $this->getService( 'FooBarService' ).
	///////////////////////////////////////////////////////////////////////////

];
