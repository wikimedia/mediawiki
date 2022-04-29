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
use MediaWiki\Actions\ActionFactory;
use MediaWiki\Auth\AuthManager;
use MediaWiki\BadFileLookup;
use MediaWiki\Block\BlockActionInfo;
use MediaWiki\Block\BlockErrorFormatter;
use MediaWiki\Block\BlockManager;
use MediaWiki\Block\BlockPermissionCheckerFactory;
use MediaWiki\Block\BlockRestrictionStore;
use MediaWiki\Block\BlockRestrictionStoreFactory;
use MediaWiki\Block\BlockUserFactory;
use MediaWiki\Block\BlockUtils;
use MediaWiki\Block\DatabaseBlockStore;
use MediaWiki\Block\UnblockUserFactory;
use MediaWiki\Block\UserBlockCommandFactory;
use MediaWiki\Cache\BacklinkCacheFactory;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Collation\CollationFactory;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\CommentFormatter\CommentParserFactory;
use MediaWiki\CommentFormatter\RowCommentFormatter;
use MediaWiki\Config\ConfigRepository;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\ContentHandlerFactory;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Content\Renderer\ContentRenderer;
use MediaWiki\Content\Transform\ContentTransformer;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\EditPage\Constraint\EditConstraintFactory;
use MediaWiki\EditPage\SpamChecker;
use MediaWiki\Export\WikiExporterFactory;
use MediaWiki\FileBackend\FSFile\TempFSFileFactory;
use MediaWiki\FileBackend\LockManager\LockManagerGroupFactory;
use MediaWiki\HookContainer\DeprecatedHooks;
use MediaWiki\HookContainer\GlobalHookRegistry;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Http\HttpRequestFactory;
use MediaWiki\Interwiki\ClassicInterwikiLookup;
use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\JobQueue\JobQueueGroupFactory;
use MediaWiki\Json\JsonCodec;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\Languages\LanguageFallback;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Linker\LinkRendererFactory;
use MediaWiki\Linker\LinkTargetLookup;
use MediaWiki\Linker\LinkTargetStore;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Mail\Emailer;
use MediaWiki\Mail\IEmailer;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\MessageFormatterFactory;
use MediaWiki\Page\ContentModelChangeFactory;
use MediaWiki\Page\DeletePageFactory;
use MediaWiki\Page\MergeHistoryFactory;
use MediaWiki\Page\MovePageFactory;
use MediaWiki\Page\PageCommandFactory;
use MediaWiki\Page\PageStore;
use MediaWiki\Page\PageStoreFactory;
use MediaWiki\Page\ParserOutputAccess;
use MediaWiki\Page\RedirectLookup;
use MediaWiki\Page\RedirectStore;
use MediaWiki\Page\RollbackPageFactory;
use MediaWiki\Page\UndeletePageFactory;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Parser\ParserCacheFactory;
use MediaWiki\Parser\ParserObserver;
use MediaWiki\Permissions\GrantsInfo;
use MediaWiki\Permissions\GrantsLocalization;
use MediaWiki\Permissions\GroupPermissionsLookup;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Permissions\RestrictionStore;
use MediaWiki\Preferences\DefaultPreferencesFactory;
use MediaWiki\Preferences\PreferencesFactory;
use MediaWiki\Preferences\SignatureValidator;
use MediaWiki\Preferences\SignatureValidatorFactory;
use MediaWiki\Revision\ArchivedRevisionLookup;
use MediaWiki\Revision\ContributionsLookup;
use MediaWiki\Revision\MainSlotRoleHandler;
use MediaWiki\Revision\RevisionFactory;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRenderer;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\RevisionStoreFactory;
use MediaWiki\Revision\SlotRoleRegistry;
use MediaWiki\Shell\CommandFactory;
use MediaWiki\Shell\ShellboxClientFactory;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Storage\BlobStore;
use MediaWiki\Storage\BlobStoreFactory;
use MediaWiki\Storage\EditResultCache;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Storage\NameTableStoreFactory;
use MediaWiki\Storage\PageEditStash;
use MediaWiki\Storage\PageUpdaterFactory;
use MediaWiki\Storage\RevertedTagUpdateManager;
use MediaWiki\Storage\SqlBlobStore;
use MediaWiki\Tidy\RemexDriver;
use MediaWiki\Tidy\TidyDriverBase;
use MediaWiki\User\ActorNormalization;
use MediaWiki\User\ActorStore;
use MediaWiki\User\ActorStoreFactory;
use MediaWiki\User\BotPasswordStore;
use MediaWiki\User\CentralId\CentralIdLookupFactory;
use MediaWiki\User\DefaultOptionsLookup;
use MediaWiki\User\TalkPageNotificationManager;
use MediaWiki\User\UserEditTracker;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserGroupManagerFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityLookup;
use MediaWiki\User\UserNamePrefixSearch;
use MediaWiki\User\UserNameUtils;
use MediaWiki\User\UserOptionsLookup;
use MediaWiki\User\UserOptionsManager;
use MediaWiki\Watchlist\WatchlistManager;
use Wikimedia\DependencyStore\KeyValueDependencyStore;
use Wikimedia\DependencyStore\SqlModuleDependencyStore;
use Wikimedia\Message\IMessageFormatterFactory;
use Wikimedia\Metrics\MetricsFactory;
use Wikimedia\ObjectFactory\ObjectFactory;
use Wikimedia\RequestTimeout\CriticalSectionProvider;
use Wikimedia\RequestTimeout\RequestTimeout;
use Wikimedia\Services\RecursiveServiceDependencyException;
use Wikimedia\UUID\GlobalIdGenerator;

/** @phpcs-require-sorted-array */
return [
	'ActionFactory' => static function ( MediaWikiServices $services ): ActionFactory {
		return new ActionFactory(
			$services->getMainConfig()->get( 'Actions' ),
			LoggerFactory::getInstance( 'ActionFactory' ),
			$services->getObjectFactory(),
			$services->getHookContainer()
		);
	},

	'ActorMigration' => static function ( MediaWikiServices $services ): ActorMigration {
		return new ActorMigration(
			$services->getMainConfig()->get( 'ActorTableSchemaMigrationStage' ),
			$services->getActorStoreFactory()
		);
	},

	'ActorNormalization' => static function ( MediaWikiServices $services ): ActorNormalization {
		return $services->getActorStoreFactory()->getActorNormalization();
	},

	'ActorStore' => static function ( MediaWikiServices $services ): ActorStore {
		return $services->getActorStoreFactory()->getActorStore();
	},

	'ActorStoreFactory' => static function ( MediaWikiServices $services ): ActorStoreFactory {
		return new ActorStoreFactory(
			new ServiceOptions( ActorStoreFactory::CONSTRUCTOR_OPTIONS, $services->getMainConfig() ),
			$services->getDBLoadBalancerFactory(),
			$services->getUserNameUtils(),
			LoggerFactory::getInstance( 'ActorStore' )
		);
	},

	'ArchivedRevisionLookup' => static function ( MediaWikiServices $services ): ArchivedRevisionLookup {
		return new ArchivedRevisionLookup(
			$services->getDBLoadBalancer(),
			$services->getRevisionStore()
		);
	},

	'AuthManager' => static function ( MediaWikiServices $services ): AuthManager {
		$authManager = new AuthManager(
			RequestContext::getMain()->getRequest(),
			$services->getMainConfig(),
			$services->getObjectFactory(),
			$services->getHookContainer(),
			$services->getReadOnlyMode(),
			$services->getUserNameUtils(),
			$services->getBlockManager(),
			$services->getWatchlistManager(),
			$services->getDBLoadBalancer(),
			$services->getContentLanguage(),
			$services->getLanguageConverterFactory(),
			$services->getBotPasswordStore(),
			$services->getUserFactory(),
			$services->getUserIdentityLookup(),
			$services->getUserOptionsManager()
		);
		$authManager->setLogger( LoggerFactory::getInstance( 'authentication' ) );
		return $authManager;
	},

	'BacklinkCacheFactory' => static function ( MediaWikiServices $services ): BacklinkCacheFactory {
		return new BacklinkCacheFactory( $services->getMainWANObjectCache() );
	},

	'BadFileLookup' => static function ( MediaWikiServices $services ): BadFileLookup {
		return new BadFileLookup(
			static function () {
				return wfMessage( 'bad_image_list' )->inContentLanguage()->plain();
			},
			$services->getLocalServerObjectCache(),
			$services->getRepoGroup(),
			$services->getTitleParser(),
			$services->getHookContainer()
		);
	},

	'BlobStore' => static function ( MediaWikiServices $services ): BlobStore {
		return $services->getService( '_SqlBlobStore' );
	},

	'BlobStoreFactory' => static function ( MediaWikiServices $services ): BlobStoreFactory {
		return new BlobStoreFactory(
			$services->getDBLoadBalancerFactory(),
			$services->getExternalStoreAccess(),
			$services->getMainWANObjectCache(),
			new ServiceOptions( BlobStoreFactory::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig() )
		);
	},

	'BlockActionInfo' => static function ( MediaWikiServices $services ): BlockActionInfo {
		return new BlockActionInfo( $services->getHookContainer() );
	},

	'BlockErrorFormatter' => static function ( MediaWikiServices $services ): BlockErrorFormatter {
		return new BlockErrorFormatter(
			$services->getTitleFormatter()
		);
	},

	'BlockManager' => static function ( MediaWikiServices $services ): BlockManager {
		return new BlockManager(
			new ServiceOptions(
				BlockManager::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			),
			$services->getPermissionManager(),
			$services->getUserFactory(),
			LoggerFactory::getInstance( 'BlockManager' ),
			$services->getHookContainer()
		);
	},

	'BlockPermissionCheckerFactory' => static function (
		MediaWikiServices $services
	): BlockPermissionCheckerFactory {
		return new BlockPermissionCheckerFactory(
			new ServiceOptions(
				BlockPermissionCheckerFactory::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			),
			$services->getBlockUtils()
		);
	},

	'BlockRestrictionStore' => static function ( MediaWikiServices $services ): BlockRestrictionStore {
		return $services->getBlockRestrictionStoreFactory()->getBlockRestrictionStore( WikiAwareEntity::LOCAL );
	},

	'BlockRestrictionStoreFactory' => static function ( MediaWikiServices $services ): BlockRestrictionStoreFactory {
		return new BlockRestrictionStoreFactory(
			$services->getDBLoadBalancerFactory()
		);
	},

	'BlockUserFactory' => static function ( MediaWikiServices $services ): BlockUserFactory {
		return $services->getService( '_UserBlockCommandFactory' );
	},

	'BlockUtils' => static function ( MediaWikiServices $services ): BlockUtils {
		return new BlockUtils(
			new ServiceOptions(
				BlockUtils::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			),
			$services->getUserIdentityLookup(),
			$services->getUserNameUtils()
		);
	},

	'BotPasswordStore' => static function ( MediaWikiServices $services ): BotPasswordStore {
		return new BotPasswordStore(
			new ServiceOptions(
				BotPasswordStore::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			),
			$services->getCentralIdLookup(),
			$services->getDBLoadBalancerFactory()
		);
	},

	'CentralIdLookup' => static function ( MediaWikiServices $services ): CentralIdLookup {
		return $services->getCentralIdLookupFactory()->getLookup();
	},

	'CentralIdLookupFactory' => static function ( MediaWikiServices $services ): CentralIdLookupFactory {
		return new CentralIdLookupFactory(
			new ServiceOptions( CentralIdLookupFactory::CONSTRUCTOR_OPTIONS, $services->getMainConfig() ),
			$services->getObjectFactory(),
			$services->getUserIdentityLookup()
		);
	},

	'ChangeTagDefStore' => static function ( MediaWikiServices $services ): NameTableStore {
		return $services->getNameTableStoreFactory()->getChangeTagDef();
	},

	'CollationFactory' => static function ( MediaWikiServices $services ): CollationFactory {
		return new CollationFactory(
			new ServiceOptions(
				CollationFactory::CONSTRUCTOR_OPTIONS, $services->getMainConfig() ),
			$services->getObjectFactory(),
			$services->getHookContainer()
		);
	},

	'CommentFormatter' => static function ( MediaWikiServices $services ): CommentFormatter {
		$linkRenderer = $services->getLinkRendererFactory()->create( [ 'renderForComment' => true ] );
		$parserFactory = new CommentParserFactory(
			$linkRenderer,
			$services->getLinkBatchFactory(),
			$services->getLinkCache(),
			$services->getRepoGroup(),
			RequestContext::getMain()->getLanguage(),
			$services->getContentLanguage(),
			$services->getTitleParser(),
			$services->getNamespaceInfo(),
			$services->getHookContainer()
		);
		return new CommentFormatter( $parserFactory );
	},

	'CommentStore' => static function ( MediaWikiServices $services ): CommentStore {
		return new CommentStore(
			$services->getContentLanguage(),
			MIGRATION_NEW
		);
	},

	'ConfigFactory' => static function ( MediaWikiServices $services ): ConfigFactory {
		// Use the bootstrap config to initialize the ConfigFactory.
		$registry = $services->getBootstrapConfig()->get( 'ConfigRegistry' );
		$factory = new ConfigFactory();

		foreach ( $registry as $name => $callback ) {
			$factory->register( $name, $callback );
		}
		return $factory;
	},

	'ConfigRepository' => static function ( MediaWikiServices $services ): ConfigRepository {
		return new ConfigRepository( $services->getConfigFactory() );
	},

	'ConfiguredReadOnlyMode' => static function ( MediaWikiServices $services ): ConfiguredReadOnlyMode {
		$config = $services->getMainConfig();
		return new ConfiguredReadOnlyMode(
			$config->get( 'ReadOnly' ),
			$config->get( 'ReadOnlyFile' )
		);
	},

	'ContentHandlerFactory' => static function ( MediaWikiServices $services ): IContentHandlerFactory {
		$contentHandlerConfig = $services->getMainConfig()->get( 'ContentHandlers' );

		return new ContentHandlerFactory(
			$contentHandlerConfig,
			$services->getObjectFactory(),
			$services->getHookContainer(),
			LoggerFactory::getInstance( 'ContentHandler' )
		);
	},

	'ContentLanguage' => static function ( MediaWikiServices $services ): Language {
		return $services->getLanguageFactory()->getLanguage(
			$services->getMainConfig()->get( 'LanguageCode' ) );
	},

	'ContentModelChangeFactory' => static function ( MediaWikiServices $services ): ContentModelChangeFactory {
		return $services->getService( '_PageCommandFactory' );
	},

	'ContentModelStore' => static function ( MediaWikiServices $services ): NameTableStore {
		return $services->getNameTableStoreFactory()->getContentModels();
	},

	'ContentRenderer' => static function ( MediaWikiServices $services ): ContentRenderer {
		return new ContentRenderer( $services->getContentHandlerFactory() );
	},

	'ContentTransformer' => static function ( MediaWikiServices $services ): ContentTransformer {
		return new ContentTransformer( $services->getContentHandlerFactory() );
	},

	'ContributionsLookup' => static function ( MediaWikiServices $services ): ContributionsLookup {
		return new ContributionsLookup(
			$services->getRevisionStore(),
			$services->getLinkRendererFactory(),
			$services->getLinkBatchFactory(),
			$services->getHookContainer(),
			$services->getDBLoadBalancer(),
			$services->getActorMigration(),
			$services->getNamespaceInfo(),
			$services->getCommentFormatter()
		);
	},

	'CriticalSectionProvider' => static function ( MediaWikiServices $services ): CriticalSectionProvider {
		$config = $services->getMainConfig();
		$limit = $config->get( 'CommandLineMode' ) ? INF : $config->get( 'CriticalSectionTimeLimit' );
		return RequestTimeout::singleton()->createCriticalSectionProvider( $limit );
	},

	'CryptHKDF' => static function ( MediaWikiServices $services ): CryptHKDF {
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

	'DatabaseBlockStore' => static function ( MediaWikiServices $services ): DatabaseBlockStore {
		return new DatabaseBlockStore(
			new ServiceOptions(
				DatabaseBlockStore::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			),
			LoggerFactory::getInstance( 'DatabaseBlockStore' ),
			$services->getActorStoreFactory(),
			$services->getBlockRestrictionStore(),
			$services->getCommentStore(),
			$services->getHookContainer(),
			$services->getDBLoadBalancer(),
			$services->getReadOnlyMode(),
			$services->getUserFactory()
		);
	},

	'DateFormatterFactory' => static function ( MediaWikiServices $services ): DateFormatterFactory {
		return new DateFormatterFactory();
	},

	'DBLoadBalancer' => static function ( MediaWikiServices $services ): Wikimedia\Rdbms\ILoadBalancer {
		// just return the default LB from the DBLoadBalancerFactory service
		return $services->getDBLoadBalancerFactory()->getMainLB();
	},

	'DBLoadBalancerFactory' =>
	static function ( MediaWikiServices $services ): Wikimedia\Rdbms\LBFactory {
		$mainConfig = $services->getMainConfig();

		$cpStashType = $mainConfig->get( 'ChronologyProtectorStash' );
		if ( is_string( $cpStashType ) ) {
			$cpStash = ObjectCache::getInstance( $cpStashType );
		} else {
			try {
				$cpStash = ObjectCache::getLocalClusterInstance();
			} catch ( RecursiveServiceDependencyException $e ) {
				$cpStash = new EmptyBagOStuff(); // T141804: handle cases like CACHE_DB
			}
		}
		LoggerFactory::getInstance( 'DBReplication' )->debug(
			'ChronologyProtector using store {class}',
			[ 'class' => get_class( $cpStash ) ]
		);

		try {
			$wanCache = $services->getMainWANObjectCache();
		} catch ( RecursiveServiceDependencyException $e ) {
			$wanCache = WANObjectCache::newEmpty(); // T141804: handle cases like CACHE_DB
		}

		$srvCache = $services->getLocalServerObjectCache();
		if ( $srvCache instanceof EmptyBagOStuff ) {
			// Use process cache if no APCU or other local-server cache (e.g. on CLI)
			$srvCache = new HashBagOStuff( [ 'maxKeys' => 100 ] );
		}

		$lbConf = MWLBFactory::applyDefaultConfig(
			$mainConfig->get( 'LBFactoryConf' ),
			new ServiceOptions( MWLBFactory::APPLY_DEFAULT_CONFIG_OPTIONS, $mainConfig ),
			$services->getConfiguredReadOnlyMode(),
			$cpStash,
			$srvCache,
			$wanCache,
			$services->getCriticalSectionProvider()
		);

		$class = MWLBFactory::getLBFactoryClass( $lbConf );
		$instance = new $class( $lbConf );

		MWLBFactory::setDomainAliases( $instance );

		// NOTE: This accesses ProxyLookup from the MediaWikiServices singleton
		// for non-essential non-nonimal purposes (via WebRequest::getIP).
		// This state is fine (and meant) to be consistent for a given PHP process,
		// even if applied to the service container for a different wiki.
		MWLBFactory::applyGlobalState(
			$instance,
			$mainConfig,
			$services->getStatsdDataFactory()
		);

		return $instance;
	},

	'DeletePageFactory' => static function ( MediaWikiServices $services ): DeletePageFactory {
		return $services->getService( '_PageCommandFactory' );
	},

	'Emailer' => static function ( MediaWikiServices $services ): IEmailer {
		return new Emailer();
	},

	'EventRelayerGroup' => static function ( MediaWikiServices $services ): EventRelayerGroup {
		return new EventRelayerGroup( $services->getMainConfig()->get( 'EventRelayerConfig' ) );
	},

	'ExternalStoreAccess' => static function ( MediaWikiServices $services ): ExternalStoreAccess {
		return new ExternalStoreAccess(
			$services->getExternalStoreFactory(),
			LoggerFactory::getInstance( 'ExternalStore' )
		);
	},

	'ExternalStoreFactory' => static function ( MediaWikiServices $services ): ExternalStoreFactory {
		$config = $services->getMainConfig();
		$writeStores = $config->get( 'DefaultExternalStore' );

		return new ExternalStoreFactory(
			$config->get( 'ExternalStores' ),
			( $writeStores !== false ) ? (array)$writeStores : [],
			$services->getDBLoadBalancer()->getLocalDomainID(),
			LoggerFactory::getInstance( 'ExternalStore' )
		);
	},

	'FileBackendGroup' => static function ( MediaWikiServices $services ): FileBackendGroup {
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
			$cache = new HashBagOStuff();
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

	'GenderCache' => static function ( MediaWikiServices $services ): GenderCache {
		$nsInfo = $services->getNamespaceInfo();
		// Database layer may be disabled, so processing without database connection
		$dbLoadBalancer = $services->isServiceDisabled( 'DBLoadBalancer' )
			? null
			: $services->getDBLoadBalancer();
		return new GenderCache( $nsInfo, $dbLoadBalancer, $services->get( '_DefaultOptionsLookup' ) );
	},

	'GlobalIdGenerator' => static function ( MediaWikiServices $services ): GlobalIdGenerator {
		$mainConfig = $services->getMainConfig();

		return new GlobalIdGenerator(
			$mainConfig->get( 'TmpDirectory' ),
			static function ( $command ) {
				return wfShellExec( $command );
			}
		);
	},

	'GrantsInfo' => static function ( MediaWikiServices $services ): GrantsInfo {
		return new GrantsInfo(
			new ServiceOptions(
				GrantsInfo::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			)
		);
	},

	'GrantsLocalization' => static function ( MediaWikiServices $services ): GrantsLocalization {
		return new GrantsLocalization(
			$services->getGrantsInfo(),
			$services->getLinkRenderer(),
			$services->getLanguageFactory(),
			$services->getContentLanguage()
		);
	},

	'GroupPermissionsLookup' => static function ( MediaWikiServices $services ): GroupPermissionsLookup {
		return new GroupPermissionsLookup(
			new ServiceOptions( GroupPermissionsLookup::CONSTRUCTOR_OPTIONS, $services->getMainConfig() )
		);
	},

	'HookContainer' => static function ( MediaWikiServices $services ): HookContainer {
		$extRegistry = ExtensionRegistry::getInstance();
		$extDeprecatedHooks = $extRegistry->getAttribute( 'DeprecatedHooks' );
		$deprecatedHooks = new DeprecatedHooks( $extDeprecatedHooks );
		$hookRegistry = new GlobalHookRegistry( $extRegistry, $deprecatedHooks );
		return new HookContainer(
			$hookRegistry,
			$services->getObjectFactory()
		);
	},

	'HtmlCacheUpdater' => static function ( MediaWikiServices $services ): HtmlCacheUpdater {
		$config = $services->getMainConfig();

		return new HtmlCacheUpdater(
			$services->getHookContainer(),
			$services->getTitleFactory(),
			$config->get( 'CdnReboundPurgeDelay' ),
			$config->get( 'UseFileCache' ),
			$config->get( 'CdnMaxAge' )
		);
	},

	'HttpRequestFactory' =>
	static function ( MediaWikiServices $services ): HttpRequestFactory {
		return new HttpRequestFactory(
			new ServiceOptions(
				HttpRequestFactory::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			),
			LoggerFactory::getInstance( 'http' )
		);
	},

	'InterwikiLookup' => static function ( MediaWikiServices $services ): InterwikiLookup {
		$config = $services->getMainConfig();
		return new ClassicInterwikiLookup(
			$services->getContentLanguage(),
			$services->getMainWANObjectCache(),
			$services->getHookContainer(),
			$services->getDBLoadBalancer(),
			$config->get( 'InterwikiExpiry' ),
			$config->get( 'InterwikiCache' ),
			$config->get( 'InterwikiScopes' ),
			$config->get( 'InterwikiFallbackSite' )
		);
	},

	'JobQueueGroup' => static function ( MediaWikiServices $services ): JobQueueGroup {
		return $services->getJobQueueGroupFactory()->makeJobQueueGroup();
	},

	'JobQueueGroupFactory' => static function ( MediaWikiServices $services ): JobQueueGroupFactory {
		return new JobQueueGroupFactory(
			new ServiceOptions( JobQueueGroupFactory::CONSTRUCTOR_OPTIONS, $services->getMainConfig() ),
			$services->getConfiguredReadOnlyMode(),
			$services->getStatsdDataFactory(),
			$services->getMainWANObjectCache(),
			$services->getGlobalIdGenerator()
		);
	},

	'JobRunner' => static function ( MediaWikiServices $services ): JobRunner {
		return new JobRunner(
			new ServiceOptions( JobRunner::CONSTRUCTOR_OPTIONS, $services->getMainConfig() ),
			$services->getDBLoadBalancerFactory(),
			$services->getJobQueueGroup(),
			$services->getReadOnlyMode(),
			$services->getLinkCache(),
			$services->getStatsdDataFactory(),
			LoggerFactory::getInstance( 'runJobs' )
		);
	},

	'JsonCodec' => static function ( MediaWikiServices $services ): JsonCodec {
		return new JsonCodec();
	},

	'LanguageConverterFactory' => static function ( MediaWikiServices $services ): LanguageConverterFactory {
		$usePigLatinVariant = $services->getMainConfig()->get( 'UsePigLatinVariant' );
		$isConversionDisabled = $services->getMainConfig()->get( 'DisableLangConversion' );
		$isTitleConversionDisabled = $services->getMainConfig()->get( 'DisableTitleConversion' );
		return new LanguageConverterFactory(
			$services->getObjectFactory(),
			$usePigLatinVariant,
			$isConversionDisabled,
			$isTitleConversionDisabled,
			static function () use ( $services ) {
				return $services->getContentLanguage();
			}
		);
	},

	'LanguageFactory' => static function ( MediaWikiServices $services ): LanguageFactory {
		return new LanguageFactory(
			new ServiceOptions( LanguageFactory::CONSTRUCTOR_OPTIONS, $services->getMainConfig() ),
			$services->getLocalisationCache(),
			$services->getLanguageNameUtils(),
			$services->getLanguageFallback(),
			$services->getLanguageConverterFactory(),
			$services->getHookContainer()
		);
	},

	'LanguageFallback' => static function ( MediaWikiServices $services ): LanguageFallback {
		return new LanguageFallback(
			$services->getMainConfig()->get( 'LanguageCode' ),
			$services->getLocalisationCache(),
			$services->getLanguageNameUtils()
		);
	},

	'LanguageNameUtils' => static function ( MediaWikiServices $services ): LanguageNameUtils {
		return new LanguageNameUtils(
			new ServiceOptions(
				LanguageNameUtils::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			),
			$services->getHookContainer()
		);
	},

	'LinkBatchFactory' => static function ( MediaWikiServices $services ): LinkBatchFactory {
		return new LinkBatchFactory(
			$services->getLinkCache(),
			$services->getTitleFormatter(),
			$services->getContentLanguage(),
			$services->getGenderCache(),
			$services->getDBLoadBalancer(),
			LoggerFactory::getInstance( 'LinkBatch' )
		);
	},

	'LinkCache' => static function ( MediaWikiServices $services ): LinkCache {
		// Database layer may be disabled, so processing without database connection
		$dbLoadBalancer = $services->isServiceDisabled( 'DBLoadBalancer' )
			? null
			: $services->getDBLoadBalancer();
		$linkCache = new LinkCache(
			$services->getTitleFormatter(),
			$services->getMainWANObjectCache(),
			$services->getNamespaceInfo(),
			$dbLoadBalancer
		);
		$linkCache->setLogger( LoggerFactory::getInstance( 'LinkCache' ) );
		return $linkCache;
	},

	'LinkRenderer' => static function ( MediaWikiServices $services ): LinkRenderer {
		return $services->getLinkRendererFactory()->create();
	},

	'LinkRendererFactory' => static function ( MediaWikiServices $services ): LinkRendererFactory {
		return new LinkRendererFactory(
			$services->getTitleFormatter(),
			$services->getLinkCache(),
			$services->getSpecialPageFactory(),
			$services->getHookContainer()
		);
	},

	'LinkTargetLookup' => static function ( MediaWikiServices $services ): LinkTargetLookup {
		return new LinkTargetStore(
			$services->getDBLoadBalancer(),
			$services->getLocalServerObjectCache(),
			$services->getMainWANObjectCache()
		);
	},

	'LocalisationCache' => static function ( MediaWikiServices $services ): LocalisationCache {
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
			[ static function () use ( $services ) {
				// NOTE: Make sure we use the same cache object that is assigned in the
				// constructor of the MessageBlobStore class used by ResourceLoader.
				// T231866: Avoid circular dependency via ResourceLoader.
				MessageBlobStore::clearGlobalCacheEntry( $services->getMainWANObjectCache() );
			} ],
			$services->getLanguageNameUtils(),
			$services->getHookContainer()
		);
	},

	'LocalServerObjectCache' => static function ( MediaWikiServices $services ): BagOStuff {
		return ObjectCache::makeLocalServerCache();
	},

	'LockManagerGroupFactory' => static function ( MediaWikiServices $services ): LockManagerGroupFactory {
		return new LockManagerGroupFactory(
			WikiMap::getCurrentWikiDbDomain()->getId(),
			$services->getMainConfig()->get( 'LockManagers' ),
			$services->getDBLoadBalancerFactory()
		);
	},

	'MagicWordFactory' => static function ( MediaWikiServices $services ): MagicWordFactory {
		return new MagicWordFactory(
			$services->getContentLanguage(),
			$services->getHookContainer()
		);
	},

	'MainConfig' => static function ( MediaWikiServices $services ): Config {
		// Use the 'main' config from the ConfigFactory service.
		return $services->getConfigFactory()->makeConfig( 'main' );
	},

	'MainObjectStash' => static function ( MediaWikiServices $services ): BagOStuff {
		$mainConfig = $services->getMainConfig();

		$id = $mainConfig->get( 'MainStash' );
		if ( !isset( $mainConfig->get( 'ObjectCaches' )[$id] ) ) {
			throw new UnexpectedValueException(
				"Cache type \"$id\" is not present in \$wgObjectCaches." );
		}

		$params = $mainConfig->get( 'ObjectCaches' )[$id];
		$params['stats'] = $services->getStatsdDataFactory();

		$store = ObjectCache::newFromParams( $params, $mainConfig );
		$store->getLogger()->debug( 'MainObjectStash using store {class}', [
			'class' => get_class( $store )
		] );

		return $store;
	},

	'MainWANObjectCache' => static function ( MediaWikiServices $services ): WANObjectCache {
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
		$storeParams['stats'] = $services->getStatsdDataFactory();
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

	'MediaHandlerFactory' => static function ( MediaWikiServices $services ): MediaHandlerFactory {
		return new MediaHandlerFactory(
			LoggerFactory::getInstance( 'MediaHandlerFactory' ),
			$services->getMainConfig()->get( 'MediaHandlers' )
		);
	},

	'MergeHistoryFactory' => static function ( MediaWikiServices $services ): MergeHistoryFactory {
		return $services->getService( '_PageCommandFactory' );
	},

	'MessageCache' => static function ( MediaWikiServices $services ): MessageCache {
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
			$services->getLanguageConverterFactory(),
			$logger,
			[ 'useDB' => $mainConfig->get( 'UseDatabaseMessages' ) ],
			$services->getLanguageFactory(),
			$services->getLocalisationCache(),
			$services->getLanguageNameUtils(),
			$services->getLanguageFallback(),
			$services->getHookContainer()
		);
	},

	'MessageFormatterFactory' => static function ( MediaWikiServices $services ): IMessageFormatterFactory {
		return new MessageFormatterFactory();
	},

	'MetricsFactory' => static function ( MediaWikiServices $services ): MetricsFactory {
		$config = $services->getMainConfig();
		return new MetricsFactory(
			[
				'target' => $config->get( 'MetricsTarget' ),
				'format' => $config->get( 'MetricsFormat' ),
				'prefix' => $config->get( 'MetricsPrefix' ),
			],
			LoggerFactory::getInstance( 'Metrics' )
		);
	},

	'MimeAnalyzer' => static function ( MediaWikiServices $services ): MimeAnalyzer {
		$logger = LoggerFactory::getInstance( 'Mime' );
		$mainConfig = $services->getMainConfig();
		$hookRunner = new HookRunner( $services->getHookContainer() );
		$params = [
			'typeFile' => $mainConfig->get( 'MimeTypeFile' ),
			'infoFile' => $mainConfig->get( 'MimeInfoFile' ),
			'xmlTypes' => $mainConfig->get( 'XMLMimeTypes' ),
			'guessCallback' =>
				static function ( $mimeAnalyzer, &$head, &$tail, $file, &$mime )
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
			'extCallback' => static function ( $mimeAnalyzer, $ext, &$mime ) use ( $hookRunner ) {
				// Media handling extensions can improve the MIME detected
				$hookRunner->onMimeMagicImproveFromExtension( $mimeAnalyzer, $ext, $mime );
			},
			'initCallback' => static function ( $mimeAnalyzer ) use ( $hookRunner ) {
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
			$params['detectCallback'] = static function ( $file ) use ( $detectorCmd, $factory ) {
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

	'MovePageFactory' => static function ( MediaWikiServices $services ): MovePageFactory {
		return $services->getService( '_PageCommandFactory' );
	},

	'NamespaceInfo' => static function ( MediaWikiServices $services ): NamespaceInfo {
		return new NamespaceInfo(
			new ServiceOptions( NamespaceInfo::CONSTRUCTOR_OPTIONS, $services->getMainConfig() ),
			$services->getHookContainer()
		);
	},

	'NameTableStoreFactory' => static function ( MediaWikiServices $services ): NameTableStoreFactory {
		return new NameTableStoreFactory(
			$services->getDBLoadBalancerFactory(),
			$services->getMainWANObjectCache(),
			LoggerFactory::getInstance( 'NameTableSqlStore' )
		);
	},

	'ObjectFactory' => static function ( MediaWikiServices $services ): ObjectFactory {
		return new ObjectFactory( $services );
	},

	'OldRevisionImporter' => static function ( MediaWikiServices $services ): OldRevisionImporter {
		return new ImportableOldRevisionImporter(
			true,
			LoggerFactory::getInstance( 'OldRevisionImporter' ),
			$services->getDBLoadBalancer(),
			$services->getRevisionStore(),
			$services->getSlotRoleRegistry(),
			$services->getWikiPageFactory(),
			$services->getPageUpdaterFactory(),
			$services->getUserFactory()
		);
	},

	'PageEditStash' => static function ( MediaWikiServices $services ): PageEditStash {
		$config = $services->getMainConfig();

		return new PageEditStash(
			ObjectCache::getLocalClusterInstance(),
			$services->getDBLoadBalancer(),
			LoggerFactory::getInstance( 'StashEdit' ),
			$services->getStatsdDataFactory(),
			$services->getUserEditTracker(),
			$services->getUserFactory(),
			$services->getWikiPageFactory(),
			$services->getHookContainer(),
			defined( 'MEDIAWIKI_JOB_RUNNER' ) || $config->get( 'CommandLineMode' )
				? PageEditStash::INITIATOR_JOB_OR_CLI
				: PageEditStash::INITIATOR_USER
		);
	},

	'PageProps' => static function ( MediaWikiServices $services ): PageProps {
		return new PageProps(
			$services->getLinkBatchFactory(),
			$services->getDBLoadBalancer()
		);
	},

	'PageStore' => static function ( MediaWikiServices $services ): PageStore {
		return $services->getPageStoreFactory()->getPageStore();
	},

	'PageStoreFactory' => static function ( MediaWikiServices $services ): PageStoreFactory {
		$options = new ServiceOptions(
			PageStoreFactory::CONSTRUCTOR_OPTIONS,
			$services->getMainConfig()
		);

		return new PageStoreFactory(
			$options,
			$services->getDBLoadBalancerFactory(),
			$services->getNamespaceInfo(),
			$services->getTitleParser(),
			$services->getLinkCache(),
			$services->getStatsdDataFactory()
		);
	},

	'PageUpdaterFactory' => static function (
		MediaWikiServices $services
	): PageUpdaterFactory {
		$editResultCache = new EditResultCache(
			$services->getMainObjectStash(),
			$services->getDBLoadBalancer(),
			new ServiceOptions(
				EditResultCache::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			)
		);

		return new PageUpdaterFactory(
			$services->getRevisionStore(),
			$services->getRevisionRenderer(),
			$services->getSlotRoleRegistry(),
			$services->getParserCache(),
			$services->getJobQueueGroup(),
			$services->getMessageCache(),
			$services->getContentLanguage(),
			$services->getDBLoadBalancerFactory(),
			$services->getContentHandlerFactory(),
			$services->getHookContainer(),
			$editResultCache,
			$services->getUserNameUtils(),
			LoggerFactory::getInstance( 'SavePage' ),
			new ServiceOptions(
				PageUpdaterFactory::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			),
			$services->getUserEditTracker(),
			$services->getUserGroupManager(),
			$services->getTitleFormatter(),
			$services->getContentTransformer(),
			$services->getPageEditStash(),
			$services->getTalkPageNotificationManager(),
			$services->getMainWANObjectCache(),
			$services->getPermissionManager(),
			$services->getWikiPageFactory(),
			ChangeTags::getSoftwareTags()
		);
	},

	'Parser' => static function ( MediaWikiServices $services ): Parser {
		return $services->getParserFactory()->create();
	},

	'ParserCache' => static function ( MediaWikiServices $services ): ParserCache {
		return $services->getParserCacheFactory()
			->getParserCache( ParserCacheFactory::DEFAULT_NAME );
	},

	'ParserCacheFactory' => static function ( MediaWikiServices $services ): ParserCacheFactory {
		$config = $services->getMainConfig();
		$cache = ObjectCache::getInstance( $config->get( 'ParserCacheType' ) );
		$wanCache = $services->getMainWANObjectCache();

		$options = new ServiceOptions( ParserCacheFactory::CONSTRUCTOR_OPTIONS, $config );

		return new ParserCacheFactory(
			$cache,
			$wanCache,
			$services->getHookContainer(),
			$services->getJsonCodec(),
			$services->getStatsdDataFactory(),
			LoggerFactory::getInstance( 'ParserCache' ),
			$options,
			$services->getTitleFactory(),
			$services->getWikiPageFactory()
		);
	},

	'ParserFactory' => static function ( MediaWikiServices $services ): ParserFactory {
		$options = new ServiceOptions( Parser::CONSTRUCTOR_OPTIONS,
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
			$services->getHookContainer(),
			$services->getTidy(),
			$services->getMainWANObjectCache(),
			$services->getUserOptionsLookup(),
			$services->getUserFactory(),
			$services->getTitleFormatter(),
			$services->getHttpRequestFactory(),
			$services->getTrackingCategories()
		);
	},

	'ParserOutputAccess' => static function ( MediaWikiServices $services ): ParserOutputAccess {
		return new ParserOutputAccess(
			$services->getParserCache(),
			$services->getParserCacheFactory()->getRevisionOutputCache( 'rcache' ),
			$services->getRevisionLookup(),
			$services->getRevisionRenderer(),
			$services->getStatsdDataFactory(),
			$services->getDBLoadBalancerFactory(),
			LoggerFactory::getProvider(),
			$services->getWikiPageFactory(),
			$services->getTitleFormatter()
		);
	},

	'PasswordFactory' => static function ( MediaWikiServices $services ): PasswordFactory {
		$config = $services->getMainConfig();
		return new PasswordFactory(
			$config->get( 'PasswordConfig' ),
			$config->get( 'PasswordDefault' )
		);
	},

	'PasswordReset' => static function ( MediaWikiServices $services ): PasswordReset {
		$options = new ServiceOptions( PasswordReset::CONSTRUCTOR_OPTIONS, $services->getMainConfig() );
		return new PasswordReset(
			$options,
			LoggerFactory::getInstance( 'authentication' ),
			$services->getAuthManager(),
			$services->getHookContainer(),
			$services->getDBLoadBalancer(),
			$services->getUserFactory(),
			$services->getUserNameUtils(),
			$services->getUserOptionsLookup()
		);
	},

	'PerDbNameStatsdDataFactory' =>
	static function ( MediaWikiServices $services ): StatsdDataFactoryInterface {
		$config = $services->getMainConfig();
		$wiki = $config->get( 'DBname' );
		return new PrefixingStatsdDataFactoryProxy(
			$services->getStatsdDataFactory(),
			$wiki
		);
	},

	'PermissionManager' => static function ( MediaWikiServices $services ): PermissionManager {
		return new PermissionManager(
			new ServiceOptions(
				PermissionManager::CONSTRUCTOR_OPTIONS, $services->getMainConfig()
			),
			$services->getSpecialPageFactory(),
			$services->getNamespaceInfo(),
			$services->getGroupPermissionsLookup(),
			$services->getUserGroupManager(),
			$services->getBlockErrorFormatter(),
			$services->getHookContainer(),
			$services->getUserCache(),
			$services->getRedirectLookup()
		);
	},

	'PreferencesFactory' => static function ( MediaWikiServices $services ): PreferencesFactory {
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
			$services->getHookContainer(),
			$services->getUserOptionsManager(),
			$services->getLanguageConverterFactory(),
			$services->getParser(),
			$services->getSkinFactory(),
			$services->getUserGroupManager()
		);
		$factory->setLogger( LoggerFactory::getInstance( 'preferences' ) );

		return $factory;
	},

	'ProxyLookup' => static function ( MediaWikiServices $services ): ProxyLookup {
		$mainConfig = $services->getMainConfig();
		return new ProxyLookup(
			$mainConfig->get( 'CdnServers' ),
			$mainConfig->get( 'CdnServersNoPurge' ),
			$services->getHookContainer()
		);
	},

	'ReadOnlyMode' => static function ( MediaWikiServices $services ): ReadOnlyMode {
		return new ReadOnlyMode(
			$services->getConfiguredReadOnlyMode(),
			$services->getDBLoadBalancer()
		);
	},

	'RedirectLookup' => static function ( MediaWikiServices $services ): RedirectLookup {
		return $services->getRedirectStore();
	},

	'RedirectStore' => static function ( MediaWikiServices $services ): RedirectStore {
		return new RedirectStore( $services->getWikiPageFactory() );
	},

	'RepoGroup' => static function ( MediaWikiServices $services ): RepoGroup {
		$config = $services->getMainConfig();
		return new RepoGroup(
			$config->get( 'LocalFileRepo' ),
			$config->get( 'ForeignFileRepos' ),
			$services->getMainWANObjectCache(),
			$services->getMimeAnalyzer()
		);
	},

	'ResourceLoader' => static function ( MediaWikiServices $services ): ResourceLoader {
		// @todo This should not take a Config object, but it's not so easy to remove because it
		// exposes it in a getter, which is actually used.
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
		$baseDir = $config->get( 'BaseDirectory' );
		$rl->register( include "$baseDir/resources/Resources.php" );
		$rl->register( $modules );
		$hookRunner = new \MediaWiki\ResourceLoader\HookRunner( $services->getHookContainer() );
		$hookRunner->onResourceLoaderRegisterModules( $rl );

		$msgPosterAttrib = $extRegistry->getAttribute( 'MessagePosterModule' );
		$rl->register( 'mediawiki.messagePoster', [
			'localBasePath' => $baseDir,
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

	'RestrictionStore' => static function ( MediaWikiServices $services ): RestrictionStore {
		return new RestrictionStore(
			new ServiceOptions(
				RestrictionStore::CONSTRUCTOR_OPTIONS, $services->getMainConfig()
			),
			$services->getMainWANObjectCache(),
			$services->getDBLoadBalancer(),
			$services->getLinkCache(),
			$services->getCommentStore(),
			$services->getHookContainer(),
			$services->getPageStore()
		);
	},

	'RevertedTagUpdateManager' => static function ( MediaWikiServices $services ): RevertedTagUpdateManager {
		$editResultCache = new EditResultCache(
			$services->getMainObjectStash(),
			$services->getDBLoadBalancer(),
			new ServiceOptions(
				EditResultCache::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			)
		);

		return new RevertedTagUpdateManager(
			$editResultCache,
			$services->getJobQueueGroup()
		);
	},

	'RevisionFactory' => static function ( MediaWikiServices $services ): RevisionFactory {
		return $services->getRevisionStore();
	},

	'RevisionLookup' => static function ( MediaWikiServices $services ): RevisionLookup {
		return $services->getRevisionStore();
	},

	'RevisionRenderer' => static function ( MediaWikiServices $services ): RevisionRenderer {
		$renderer = new RevisionRenderer(
			$services->getDBLoadBalancer(),
			$services->getSlotRoleRegistry(),
			$services->getContentRenderer()
		);

		$renderer->setLogger( LoggerFactory::getInstance( 'SaveParse' ) );
		return $renderer;
	},

	'RevisionStore' => static function ( MediaWikiServices $services ): RevisionStore {
		return $services->getRevisionStoreFactory()->getRevisionStore();
	},

	'RevisionStoreFactory' => static function ( MediaWikiServices $services ): RevisionStoreFactory {
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
			$services->getLocalServerObjectCache(),
			$services->getCommentStore(),
			$services->getActorMigration(),
			$services->getActorStoreFactory(),
			LoggerFactory::getInstance( 'RevisionStore' ),
			$services->getContentHandlerFactory(),
			$services->getPageStoreFactory(),
			$services->getTitleFactory(),
			$services->getHookContainer()
		);

		return $store;
	},

	'RollbackPageFactory' => static function ( MediaWikiServices $services ): RollbackPageFactory {
		return $services->get( '_PageCommandFactory' );
	},

	'RowCommentFormatter' => static function ( MediaWikiServices $services ): RowCommentFormatter {
		$parserFactory = new CommentParserFactory(
			$services->getLinkRenderer(),
			$services->getLinkBatchFactory(),
			$services->getLinkCache(),
			$services->getRepoGroup(),
			RequestContext::getMain()->getLanguage(),
			$services->getContentLanguage(),
			$services->getTitleParser(),
			$services->getNamespaceInfo(),
			$services->getHookContainer()
		);
		return new RowCommentFormatter(
			$parserFactory,
			$services->getCommentStore()
		);
	},

	'SearchEngineConfig' => static function ( MediaWikiServices $services ): SearchEngineConfig {
		// @todo This should not take a Config object, but it's not so easy to remove because it
		// exposes it in a getter, which is actually used.
		return new SearchEngineConfig(
			$services->getMainConfig(),
			$services->getContentLanguage(),
			$services->getHookContainer(),
			ExtensionRegistry::getInstance()->getAttribute( 'SearchMappings' )
		);
	},

	'SearchEngineFactory' => static function ( MediaWikiServices $services ): SearchEngineFactory {
		return new SearchEngineFactory(
			$services->getSearchEngineConfig(),
			$services->getHookContainer(),
			$services->getDBLoadBalancer()
		);
	},

	'ShellboxClientFactory' => static function ( MediaWikiServices $services ): ShellboxClientFactory {
		$urls = $services->getMainConfig()->get( 'ShellboxUrls' );
		// TODO: Remove this logic and $wgShellboxUrl configuration in 1.38
		$url = $services->getMainConfig()->get( 'ShellboxUrl' );
		if ( $url !== null ) {
			$urls['default'] = $url;
		}
		return new ShellboxClientFactory(
			$services->getHttpRequestFactory(),
			$urls,
			$services->getMainConfig()->get( 'ShellboxSecretKey' )
		);
	},

	'ShellCommandFactory' => static function ( MediaWikiServices $services ): CommandFactory {
		$config = $services->getMainConfig();

		$limits = [
			'time' => $config->get( 'MaxShellTime' ),
			'walltime' => $config->get( 'MaxShellWallClockTime' ),
			'memory' => $config->get( 'MaxShellMemory' ),
			'filesize' => $config->get( 'MaxShellFileSize' ),
		];
		$cgroup = $config->get( 'ShellCgroup' );
		$restrictionMethod = $config->get( 'ShellRestrictionMethod' );

		$factory = new CommandFactory( $services->getShellboxClientFactory(),
			$limits, $cgroup, $restrictionMethod );
		$factory->setLogger( LoggerFactory::getInstance( 'exec' ) );
		$factory->logStderr();

		return $factory;
	},

	'SignatureValidatorFactory' => static function ( MediaWikiServices $services ): SignatureValidatorFactory {
		return new SignatureValidatorFactory(
			new ServiceOptions(
				SignatureValidator::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			),
			$services->getParser(),
			$services->getSpecialPageFactory(),
			$services->getTitleFactory()
		);
	},

	'SiteLookup' => static function ( MediaWikiServices $services ): SiteLookup {
		// Use SiteStore as the SiteLookup as well. This was originally separated
		// to allow for a cacheable read-only interface, but this was never used.
		// SiteStore has caching (see below).
		return $services->getSiteStore();
	},

	'SiteStore' => static function ( MediaWikiServices $services ): SiteStore {
		$rawSiteStore = new DBSiteStore( $services->getDBLoadBalancer() );

		$cache = $services->getLocalServerObjectCache();
		if ( $cache instanceof EmptyBagOStuff ) {
			$cache = ObjectCache::getLocalClusterInstance();
		}

		return new CachingSiteStore( $rawSiteStore, $cache );
	},

	/** @suppress PhanTypeInvalidCallableArrayKey */
	'SkinFactory' => static function ( MediaWikiServices $services ): SkinFactory {
		$factory = new SkinFactory(
			$services->getObjectFactory(),
			(array)$services->getMainConfig()->get( 'SkipSkins' )
		);

		$names = $services->getMainConfig()->get( 'ValidSkinNames' );

		foreach ( $names as $name => $skin ) {
			if ( is_array( $skin ) ) {
				$spec = $skin;
				$displayName = $skin['displayname'] ?? $name;
				$skippable = $skin['skippable'] ?? null;
			} else {
				$displayName = $skin;
				$skippable = null;
				$spec = [
					'name' => $name,
					'class' => "Skin$skin"
				];
			}
			$factory->register( $name, $displayName, $spec, $skippable );
		}

		// Register a hidden "fallback" skin
		$factory->register( 'fallback', 'Fallback', [
			'class' => SkinFallback::class,
			'args' => [
				[
					'name' => 'fallback',
					'styles' => [ 'mediawiki.skinning.interface' ],
					'templateDirectory' => __DIR__ . '/skins/templates/fallback',
				]
			]
		], true );
		// Register a hidden skin for api output
		$factory->register( 'apioutput', 'ApiOutput', [
			'class' => SkinApi::class,
			'args' => [
				[
					'name' => 'apioutput',
					'styles' => [ 'mediawiki.skinning.interface' ],
					'templateDirectory' => __DIR__ . '/skins/templates/apioutput',
				]
			]
		], true );

		return $factory;
	},

	'SlotRoleRegistry' => static function ( MediaWikiServices $services ): SlotRoleRegistry {
		$registry = new SlotRoleRegistry(
			$services->getSlotRoleStore()
		);

		$config = $services->getMainConfig();
		$contentHandlerFactory = $services->getContentHandlerFactory();
		$hookContainer = $services->getHookContainer();
		$titleFactory = $services->getTitleFactory();
		$registry->defineRole(
			'main',
			static function () use ( $config, $contentHandlerFactory, $hookContainer, $titleFactory ) {
				return new MainSlotRoleHandler(
					$config->get( 'NamespaceContentModels' ),
					$contentHandlerFactory,
					$hookContainer,
					$titleFactory
				);
			}
		);

		return $registry;
	},

	'SlotRoleStore' => static function ( MediaWikiServices $services ): NameTableStore {
		return $services->getNameTableStoreFactory()->getSlotRoles();
	},

	'SpamChecker' => static function ( MediaWikiServices $services ): SpamChecker {
		return new SpamChecker(
			(array)$services->getMainConfig()->get( 'SpamRegex' ),
			(array)$services->getMainConfig()->get( 'SummarySpamRegex' )
		);
	},

	'SpecialPageFactory' => static function ( MediaWikiServices $services ): SpecialPageFactory {
		return new SpecialPageFactory(
			new ServiceOptions(
				SpecialPageFactory::CONSTRUCTOR_OPTIONS, $services->getMainConfig() ),
			$services->getContentLanguage(),
			$services->getObjectFactory(),
			$services->getTitleFactory(),
			$services->getHookContainer()
		);
	},

	'StatsdDataFactory' => static function ( MediaWikiServices $services ): IBufferingStatsdDataFactory {
		return new BufferingStatsdDataFactory(
			rtrim( $services->getMainConfig()->get( 'StatsdMetricPrefix' ), '.' )
		);
	},

	'TalkPageNotificationManager' => static function (
		MediaWikiServices $services
	): TalkPageNotificationManager {
		return new TalkPageNotificationManager(
			new ServiceOptions(
				TalkPageNotificationManager::CONSTRUCTOR_OPTIONS, $services->getMainConfig()
			),
			$services->getDBLoadBalancer(),
			$services->getReadOnlyMode(),
			$services->getRevisionLookup()
		);
	},

	'TempFSFileFactory' => static function ( MediaWikiServices $services ): TempFSFileFactory {
		return new TempFSFileFactory( $services->getMainConfig()->get( 'TmpDirectory' ) );
	},

	'Tidy' => static function ( MediaWikiServices $services ): TidyDriverBase {
		return new RemexDriver(
			new ServiceOptions(
				RemexDriver::CONSTRUCTOR_OPTIONS, $services->getMainConfig()
			)
		);
	},

	'TitleFactory' => static function ( MediaWikiServices $services ): TitleFactory {
		return new TitleFactory();
	},

	'TitleFormatter' => static function ( MediaWikiServices $services ): TitleFormatter {
		return $services->getService( '_MediaWikiTitleCodec' );
	},

	'TitleParser' => static function ( MediaWikiServices $services ): TitleParser {
		return $services->getService( '_MediaWikiTitleCodec' );
	},

	'TrackingCategories' => static function ( MediaWikiServices $services ): TrackingCategories {
		return new TrackingCategories(
			new ServiceOptions(
				TrackingCategories::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			),
			$services->getNamespaceInfo(),
			$services->getTitleParser(),
			LoggerFactory::getInstance( 'TrackingCategories' )
		);
	},

	'UnblockUserFactory' => static function ( MediaWikiServices $services ): UnblockUserFactory {
		return $services->getService( '_UserBlockCommandFactory' );
	},

	'UndeletePageFactory' => static function ( MediaWikiServices $services ): UndeletePageFactory {
		return $services->getService( '_PageCommandFactory' );
	},

	'UploadRevisionImporter' => static function ( MediaWikiServices $services ): UploadRevisionImporter {
		return new ImportableUploadRevisionImporter(
			$services->getMainConfig()->get( 'EnableUploads' ),
			LoggerFactory::getInstance( 'UploadRevisionImporter' )
		);
	},

	'UserCache' => static function ( MediaWikiServices $services ): UserCache {
		return new UserCache(
			LoggerFactory::getInstance( 'UserCache' ),
			$services->getDBLoadBalancer(),
			$services->getLinkBatchFactory()
		);
	},

	'UserEditTracker' => static function ( MediaWikiServices $services ): UserEditTracker {
		return new UserEditTracker(
			$services->getActorMigration(),
			$services->getDBLoadBalancer(),
			$services->getJobQueueGroup()
		);
	},

	'UserFactory' => static function ( MediaWikiServices $services ): UserFactory {
		return new UserFactory(
			$services->getDBLoadBalancer(),
			$services->getUserNameUtils()
		);
	},

	'UserGroupManager' => static function ( MediaWikiServices $services ): UserGroupManager {
		return $services->getUserGroupManagerFactory()->getUserGroupManager();
	},

	'UserGroupManagerFactory' => static function ( MediaWikiServices $services ): UserGroupManagerFactory {
		return new UserGroupManagerFactory(
			new ServiceOptions(
				UserGroupManager::CONSTRUCTOR_OPTIONS, $services->getMainConfig()
			),
			$services->getConfiguredReadOnlyMode(),
			$services->getDBLoadBalancerFactory(),
			$services->getHookContainer(),
			$services->getUserEditTracker(),
			$services->getGroupPermissionsLookup(),
			$services->getJobQueueGroupFactory(),
			LoggerFactory::getInstance( 'UserGroupManager' ),
			[ static function ( UserIdentity $user ) use ( $services ) {
				$services->getPermissionManager()->invalidateUsersRightsCache( $user );
				$services->getUserFactory()->newFromUserIdentity( $user )->invalidateCache();
			} ]
		);
	},

	'UserIdentityLookup' => static function ( MediaWikiServices $services ): UserIdentityLookup {
		return $services->getActorStoreFactory()->getUserIdentityLookup();
	},

	'UserNamePrefixSearch' => static function ( MediaWikiServices $services ): UserNamePrefixSearch {
		return new UserNamePrefixSearch(
			$services->getDBLoadBalancer(),
			$services->getUserNameUtils()
		);
	},

	'UserNameUtils' => static function ( MediaWikiServices $services ): UserNameUtils {
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

	'UserOptionsLookup' => static function ( MediaWikiServices $services ): UserOptionsLookup {
		return $services->getUserOptionsManager();
	},

	'UserOptionsManager' => static function ( MediaWikiServices $services ): UserOptionsManager {
		return new UserOptionsManager(
			new ServiceOptions( UserOptionsManager::CONSTRUCTOR_OPTIONS, $services->getMainConfig() ),
			$services->get( '_DefaultOptionsLookup' ),
			$services->getLanguageConverterFactory(),
			$services->getDBLoadBalancer(),
			LoggerFactory::getInstance( 'UserOptionsManager' ),
			$services->getHookContainer(),
			$services->getUserFactory()
		);
	},

	'VirtualRESTServiceClient' =>
	static function ( MediaWikiServices $services ): VirtualRESTServiceClient {
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
	static function ( MediaWikiServices $services ): WatchedItemQueryService {
		return new WatchedItemQueryService(
			$services->getDBLoadBalancer(),
			$services->getCommentStore(),
			$services->getWatchedItemStore(),
			$services->getHookContainer(),
			$services->getMainConfig()->get( 'WatchlistExpiry' ),
			$services->getMainConfig()->get( 'MaxExecutionTimeForExpensiveQueries' )
		);
	},

	'WatchedItemStore' => static function ( MediaWikiServices $services ): WatchedItemStore {
		$store = new WatchedItemStore(
			new ServiceOptions( WatchedItemStore::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig() ),
			$services->getDBLoadBalancerFactory(),
			$services->getJobQueueGroup(),
			$services->getMainObjectStash(),
			new HashBagOStuff( [ 'maxKeys' => 100 ] ),
			$services->getReadOnlyMode(),
			$services->getNamespaceInfo(),
			$services->getRevisionLookup(),
			$services->getLinkBatchFactory()
		);
		$store->setStatsdDataFactory( $services->getStatsdDataFactory() );

		if ( $services->getMainConfig()->get( 'ReadOnlyWatchedItemStore' ) ) {
			$store = new NoWriteWatchedItemStore( $store );
		}

		return $store;
	},

	'WatchlistManager' => static function ( MediaWikiServices $services ): WatchlistManager {
		return new WatchlistManager(
			new ServiceOptions(
				WatchlistManager::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			),
			$services->getHookContainer(),
			$services->getReadOnlyMode(),
			$services->getRevisionLookup(),
			$services->getTalkPageNotificationManager(),
			$services->getWatchedItemStore(),
			$services->getUserFactory(),
			$services->getNamespaceInfo(),
			$services->getWikiPageFactory()
		);
	},

	'WikiExporterFactory' => static function ( MediaWikiServices $services ): WikiExporterFactory {
		return new WikiExporterFactory(
			$services->getHookContainer(),
			$services->getRevisionStore(),
			$services->getTitleParser()
		);
	},

	'WikiImporterFactory' => static function ( MediaWikiServices $services ): WikiImporterFactory {
		return new WikiImporterFactory(
			$services->getMainConfig(),
			$services->getHookContainer(),
			$services->getContentLanguage(),
			$services->getNamespaceInfo(),
			$services->getTitleFactory(),
			$services->getWikiPageFactory(),
			$services->getWikiRevisionUploadImporter(),
			$services->getPermissionManager(),
			$services->getContentHandlerFactory(),
			$services->getSlotRoleRegistry()
		);
	},

	'WikiPageFactory' => static function ( MediaWikiServices $services ): WikiPageFactory {
		return new WikiPageFactory(
			$services->getTitleFactory(),
			new HookRunner( $services->getHookContainer() ),
			$services->getDBLoadBalancer()
		);
	},

	'WikiRevisionOldRevisionImporterNoUpdates' =>
	static function ( MediaWikiServices $services ): ImportableOldRevisionImporter {
		return new ImportableOldRevisionImporter(
			false,
			LoggerFactory::getInstance( 'OldRevisionImporter' ),
			$services->getDBLoadBalancer(),
			$services->getRevisionStore(),
			$services->getSlotRoleRegistry(),
			$services->getWikiPageFactory(),
			$services->getPageUpdaterFactory(),
			$services->getUserFactory()
		);
	},

	'_DefaultOptionsLookup' => static function ( MediaWikiServices $services ): DefaultOptionsLookup {
		return new DefaultOptionsLookup(
			new ServiceOptions( DefaultOptionsLookup::CONSTRUCTOR_OPTIONS, $services->getMainConfig() ),
			$services->getContentLanguage(),
			$services->getHookContainer(),
			$services->getNamespaceInfo()
		);
	},

	'_EditConstraintFactory' => static function ( MediaWikiServices $services ): EditConstraintFactory {
		// This service is internal and currently only exists because a significant number
		// of dependencies will be needed by different constraints. It is not part of
		// the public interface and has no corresponding method in MediaWikiServices
		return new EditConstraintFactory(
			// Multiple
			new ServiceOptions(
				EditConstraintFactory::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			),
			LoggerFactory::getProvider(),

			// UserBlockConstraint
			$services->getPermissionManager(),

			// EditFilterMergedContentHookConstraint
			$services->getHookContainer(),

			// ReadOnlyConstraint
			$services->getReadOnlyMode(),

			// SpamRegexConstraint
			$services->getSpamChecker()
		);
	},

	'_MediaWikiTitleCodec' => static function ( MediaWikiServices $services ): MediaWikiTitleCodec {
		return new MediaWikiTitleCodec(
			$services->getContentLanguage(),
			$services->getGenderCache(),
			$services->getMainConfig()->get( 'LocalInterwikis' ),
			$services->getInterwikiLookup(),
			$services->getNamespaceInfo()
		);
	},

	'_PageCommandFactory' => static function ( MediaWikiServices $services ): PageCommandFactory {
		return new PageCommandFactory(
			$services->getMainConfig(),
			$services->getDBLoadBalancerFactory(),
			$services->getNamespaceInfo(),
			$services->getWatchedItemStore(),
			$services->getRepoGroup(),
			$services->getReadOnlyMode(),
			$services->getContentHandlerFactory(),
			$services->getRevisionStore(),
			$services->getSpamChecker(),
			$services->getTitleFormatter(),
			$services->getHookContainer(),
			$services->getWikiPageFactory(),
			$services->getUserFactory(),
			$services->getActorMigration(),
			$services->getActorNormalization(),
			$services->getTitleFactory(),
			$services->getUserEditTracker(),
			$services->getCollationFactory(),
			$services->getJobQueueGroup(),
			$services->getCommentStore(),
			$services->getMainObjectStash(),
			WikiMap::getCurrentWikiDbDomain()->getId(),
			WebRequest::getRequestId(),
			$services->getBacklinkCacheFactory(),
			LoggerFactory::getInstance( 'UndeletePage' ),
			$services->getPageUpdaterFactory(),
			$services->getMessageFormatterFactory()->getTextFormatter(
				$services->getContentLanguage()->getCode()
			)
		);
	},

	'_ParserObserver' => static function ( MediaWikiServices $services ): ParserObserver {
		return new ParserObserver( LoggerFactory::getInstance( 'DuplicateParse' ) );
	},

	'_SqlBlobStore' => static function ( MediaWikiServices $services ): SqlBlobStore {
		return $services->getBlobStoreFactory()->newSqlBlobStore();
	},

	'_UserBlockCommandFactory' => static function ( MediaWikiServices $services ): UserBlockCommandFactory {
		return new UserBlockCommandFactory(
			new ServiceOptions( UserBlockCommandFactory::CONSTRUCTOR_OPTIONS, $services->getMainConfig() ),
			$services->getHookContainer(),
			$services->getBlockPermissionCheckerFactory(),
			$services->getBlockUtils(),
			$services->getDatabaseBlockStore(),
			$services->getBlockRestrictionStore(),
			$services->getUserFactory(),
			$services->getUserEditTracker(),
			LoggerFactory::getInstance( 'BlockManager' ),
			$services->getTitleFactory(),
			$services->getBlockActionInfo()
		);
	},

	///////////////////////////////////////////////////////////////////////////
	// NOTE: When adding a service here, don't forget to add a getter function
	// in the MediaWikiServices class. The convenience getter should just call
	// $this->getService( 'FooBarService' ).
	///////////////////////////////////////////////////////////////////////////

];
