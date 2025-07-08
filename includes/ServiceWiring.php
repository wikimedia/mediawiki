<?php
/**
 * Service implementations for %MediaWiki core.
 *
 * This file returns the array loaded by the MediaWikiServices class
 * for use through `MediaWiki\MediaWikiServices::getInstance()`
 *
 * @see [Dependency Injection](@ref dependencyinjection) in docs/Injection.md
 * for the principles of DI and how to use it MediaWiki core.
 *
 * Reminder:
 *
 * - ServiceWiring is NOT a cache for arbitrary singletons.
 *
 * - Services MUST NOT vary their behaviour on global state, especially not
 *   WebRequest, RequestContext (T218555), or other details of the current
 *   request or CLI process (e.g. "current" user or title). Doing so may
 *   cause a chain reaction and cause serious data corruption.
 *
 *   Refer to [DI Principles](@ref di-principles) in docs/Injection.md for
 *   how and why we avoid this, as well as for limited exemptions to these
 *   principles.
 *
 * -------
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
 */

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use MediaWiki\Actions\ActionFactory;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Auth\Throttler;
use MediaWiki\Block\AutoblockExemptionList;
use MediaWiki\Block\BlockActionInfo;
use MediaWiki\Block\BlockErrorFormatter;
use MediaWiki\Block\BlockManager;
use MediaWiki\Block\BlockPermissionCheckerFactory;
use MediaWiki\Block\BlockRestrictionStore;
use MediaWiki\Block\BlockRestrictionStoreFactory;
use MediaWiki\Block\BlockTargetFactory;
use MediaWiki\Block\BlockUserFactory;
use MediaWiki\Block\BlockUtils;
use MediaWiki\Block\BlockUtilsFactory;
use MediaWiki\Block\CrossWikiBlockTargetFactory;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\DatabaseBlockStore;
use MediaWiki\Block\DatabaseBlockStoreFactory;
use MediaWiki\Block\HideUserUtils;
use MediaWiki\Block\UnblockUserFactory;
use MediaWiki\Block\UserBlockCommandFactory;
use MediaWiki\Cache\BacklinkCache;
use MediaWiki\Cache\BacklinkCacheFactory;
use MediaWiki\Cache\GenderCache;
use MediaWiki\Cache\HTMLCacheUpdater;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Cache\LinkCache;
use MediaWiki\Cache\UserCache;
use MediaWiki\Category\TrackingCategories;
use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\Collation\CollationFactory;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\CommentFormatter\CommentParserFactory;
use MediaWiki\CommentFormatter\RowCommentFormatter;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Config\Config;
use MediaWiki\Config\ConfigException;
use MediaWiki\Config\ConfigFactory;
use MediaWiki\Config\ConfigRepository;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\ContentHandlerFactory;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Content\Renderer\ContentRenderer;
use MediaWiki\Content\Transform\ContentTransformer;
use MediaWiki\Context\RequestContext;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\DomainEvent\DomainEventDispatcher;
use MediaWiki\DomainEvent\DomainEventSource;
use MediaWiki\DomainEvent\EventDispatchEngine;
use MediaWiki\Edit\ParsoidOutputStash;
use MediaWiki\Edit\SimpleParsoidOutputStash;
use MediaWiki\EditPage\Constraint\EditConstraintFactory;
use MediaWiki\EditPage\IntroMessageBuilder;
use MediaWiki\EditPage\PreloadedContentBuilder;
use MediaWiki\EditPage\SpamChecker;
use MediaWiki\Export\WikiExporterFactory;
use MediaWiki\FeatureShutdown;
use MediaWiki\FileBackend\FileBackendGroup;
use MediaWiki\FileBackend\LockManager\LockManagerGroupFactory;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\HookContainer\StaticHookRegistry;
use MediaWiki\Http\HttpRequestFactory;
use MediaWiki\Http\Telemetry;
use MediaWiki\Installer\Pingback;
use MediaWiki\Interwiki\ClassicInterwikiLookup;
use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\JobQueue\JobFactory;
use MediaWiki\JobQueue\JobQueueGroup;
use MediaWiki\JobQueue\JobQueueGroupFactory;
use MediaWiki\JobQueue\JobRunner;
use MediaWiki\Json\JsonCodec;
use MediaWiki\Language\FormatterFactory;
use MediaWiki\Language\Language;
use MediaWiki\Language\LanguageCode;
use MediaWiki\Language\LazyLocalizationContext;
use MediaWiki\Language\MessageParser;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Languages\LanguageEventIngress;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\Languages\LanguageFallback;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Linker\LinkRendererFactory;
use MediaWiki\Linker\LinksMigration;
use MediaWiki\Linker\LinkTargetLookup;
use MediaWiki\Linker\LinkTargetStore;
use MediaWiki\Linker\UserLinkRenderer;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Logging\LogFormatterFactory;
use MediaWiki\Mail\Emailer;
use MediaWiki\Mail\EmailUser;
use MediaWiki\Mail\EmailUserFactory;
use MediaWiki\Mail\IEmailer;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Message\MessageFormatterFactory;
use MediaWiki\Notification\MiddlewareChain;
use MediaWiki\Notification\NotificationService;
use MediaWiki\OutputTransform\DefaultOutputPipelineFactory;
use MediaWiki\OutputTransform\OutputTransformPipeline;
use MediaWiki\Page\ContentModelChangeFactory;
use MediaWiki\Page\DeletePageFactory;
use MediaWiki\Page\File\BadFileLookup;
use MediaWiki\Page\MergeHistoryFactory;
use MediaWiki\Page\MovePageFactory;
use MediaWiki\Page\PageCommandFactory;
use MediaWiki\Page\PageProps;
use MediaWiki\Page\PageStore;
use MediaWiki\Page\PageStoreFactory;
use MediaWiki\Page\ParserOutputAccess;
use MediaWiki\Page\RedirectLookup;
use MediaWiki\Page\RedirectStore;
use MediaWiki\Page\RollbackPageFactory;
use MediaWiki\Page\UndeletePageFactory;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Parser\DateFormatterFactory;
use MediaWiki\Parser\MagicWordFactory;
use MediaWiki\Parser\Parser;
use MediaWiki\Parser\ParserCache;
use MediaWiki\Parser\ParserCacheFactory;
use MediaWiki\Parser\ParserFactory;
use MediaWiki\Parser\ParserObserver;
use MediaWiki\Parser\Parsoid\Config\DataAccess as MWDataAccess;
use MediaWiki\Parser\Parsoid\Config\PageConfigFactory as MWPageConfigFactory;
use MediaWiki\Parser\Parsoid\Config\SiteConfig as MWSiteConfig;
use MediaWiki\Parser\Parsoid\HtmlTransformFactory;
use MediaWiki\Parser\Parsoid\LintErrorChecker;
use MediaWiki\Parser\Parsoid\ParsoidParserFactory;
use MediaWiki\Password\PasswordFactory;
use MediaWiki\Permissions\GrantsInfo;
use MediaWiki\Permissions\GrantsLocalization;
use MediaWiki\Permissions\GroupPermissionsLookup;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Permissions\RateLimiter;
use MediaWiki\Permissions\RestrictionStore;
use MediaWiki\PoolCounter\PoolCounterFactory;
use MediaWiki\Preferences\DefaultPreferencesFactory;
use MediaWiki\Preferences\PreferencesFactory;
use MediaWiki\Preferences\SignatureValidator;
use MediaWiki\Preferences\SignatureValidatorFactory;
use MediaWiki\RecentChanges\ChangeTrackingEventIngress;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\RenameUser\RenameUserFactory;
use MediaWiki\Request\ProxyLookup;
use MediaWiki\Request\WebRequest;
use MediaWiki\ResourceLoader\MessageBlobStore;
use MediaWiki\ResourceLoader\ResourceLoader;
use MediaWiki\ResourceLoader\ResourceLoaderEventIngress;
use MediaWiki\Rest\Handler\Helper\PageRestHelperFactory;
use MediaWiki\Revision\ArchivedRevisionLookup;
use MediaWiki\Revision\MainSlotRoleHandler;
use MediaWiki\Revision\RevisionFactory;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRenderer;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\RevisionStoreFactory;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SlotRoleRegistry;
use MediaWiki\Search\SearchEventIngress;
use MediaWiki\Search\SearchResultThumbnailProvider;
use MediaWiki\Search\TitleMatcher;
use MediaWiki\Session\SessionManager;
use MediaWiki\Settings\Config\ConfigSchema;
use MediaWiki\Settings\SettingsBuilder;
use MediaWiki\Shell\CommandFactory;
use MediaWiki\Shell\ShellboxClientFactory;
use MediaWiki\Site\CachingSiteStore;
use MediaWiki\Site\DBSiteStore;
use MediaWiki\Site\SiteLookup;
use MediaWiki\Site\SiteStore;
use MediaWiki\Skin\SkinApi;
use MediaWiki\Skin\SkinAuthenticationPopup;
use MediaWiki\Skin\SkinFactory;
use MediaWiki\Skin\SkinFallback;
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
use MediaWiki\Telemetry\MediaWikiPropagator;
use MediaWiki\Tidy\RemexDriver;
use MediaWiki\Tidy\TidyDriverBase;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\TitleFactory;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\Title\TitleParser;
use MediaWiki\User\ActorMigration;
use MediaWiki\User\ActorNormalization;
use MediaWiki\User\ActorStore;
use MediaWiki\User\ActorStoreFactory;
use MediaWiki\User\BotPasswordStore;
use MediaWiki\User\CentralId\CentralIdLookup;
use MediaWiki\User\CentralId\CentralIdLookupFactory;
use MediaWiki\User\Options\ConditionalDefaultsLookup;
use MediaWiki\User\Options\DefaultOptionsLookup;
use MediaWiki\User\Options\StaticUserOptionsLookup;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\Options\UserOptionsManager;
use MediaWiki\User\PasswordReset;
use MediaWiki\User\Registration\LocalUserRegistrationProvider;
use MediaWiki\User\Registration\UserRegistrationLookup;
use MediaWiki\User\TalkPageNotificationManager;
use MediaWiki\User\TempUser\RealTempUserConfig;
use MediaWiki\User\TempUser\TempUserCreator;
use MediaWiki\User\TempUser\TempUserDetailsLookup;
use MediaWiki\User\UserEditTracker;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserGroupManagerFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityLookup;
use MediaWiki\User\UserIdentityUtils;
use MediaWiki\User\UserNamePrefixSearch;
use MediaWiki\User\UserNameUtils;
use MediaWiki\Utils\UrlUtils;
use MediaWiki\Watchlist\NoWriteWatchedItemStore;
use MediaWiki\Watchlist\WatchedItemQueryService;
use MediaWiki\Watchlist\WatchedItemStore;
use MediaWiki\Watchlist\WatchlistManager;
use MediaWiki\WikiMap\WikiMap;
use Psr\Http\Client\ClientInterface;
use Wikimedia\DependencyStore\DependencyStore;
use Wikimedia\EventRelayer\EventRelayerGroup;
use Wikimedia\FileBackend\FSFile\TempFSFileFactory;
use Wikimedia\Message\IMessageFormatterFactory;
use Wikimedia\Mime\MimeAnalyzer;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\EmptyBagOStuff;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\ObjectFactory\ObjectFactory;
use Wikimedia\Parsoid\Config\DataAccess;
use Wikimedia\Parsoid\Parsoid;
use Wikimedia\Rdbms\ChronologyProtector;
use Wikimedia\Rdbms\ConfiguredReadOnlyMode;
use Wikimedia\Rdbms\DatabaseFactory;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\ReadOnlyMode;
use Wikimedia\RequestTimeout\CriticalSectionProvider;
use Wikimedia\RequestTimeout\RequestTimeout;
use Wikimedia\Stats\IBufferingStatsdDataFactory;
use Wikimedia\Stats\PrefixingStatsdDataFactoryProxy;
use Wikimedia\Stats\StatsCache;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\Telemetry\Clock;
use Wikimedia\Telemetry\CompositePropagator;
use Wikimedia\Telemetry\NoopTracer;
use Wikimedia\Telemetry\OtlpHttpExporter;
use Wikimedia\Telemetry\ProbabilisticSampler;
use Wikimedia\Telemetry\Tracer;
use Wikimedia\Telemetry\TracerInterface;
use Wikimedia\Telemetry\TracerState;
use Wikimedia\Telemetry\W3CTraceContextPropagator;
use Wikimedia\UUID\GlobalIdGenerator;
use Wikimedia\WRStats\BagOStuffStatsStore;
use Wikimedia\WRStats\WRStatsFactory;

/** @phpcs-require-sorted-array */
return [
	'ActionFactory' => static function ( MediaWikiServices $services ): ActionFactory {
		return new ActionFactory(
			$services->getMainConfig()->get( MainConfigNames::Actions ),
			LoggerFactory::getInstance( 'ActionFactory' ),
			$services->getObjectFactory(),
			$services->getHookContainer(),
			$services->getContentHandlerFactory()
		);
	},

	'ActorMigration' => static function ( MediaWikiServices $services ): ActorMigration {
		return new ActorMigration(
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
			$services->getTempUserConfig(),
			LoggerFactory::getInstance( 'ActorStore' ),
			$services->getHideUserUtils()
		);
	},

	'ArchivedRevisionLookup' => static function ( MediaWikiServices $services ): ArchivedRevisionLookup {
		return new ArchivedRevisionLookup(
			$services->getConnectionProvider(),
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
			$services->getUserOptionsManager(),
			$services->getNotificationService()
		);
		$authManager->setLogger( LoggerFactory::getInstance( 'authentication' ) );
		return $authManager;
	},

	'AutoblockExemptionList' => static function ( MediaWikiServices $services ): AutoblockExemptionList {
		$messageFormatterFactory = new MessageFormatterFactory( Message::FORMAT_PLAIN );
		return new AutoblockExemptionList(
			new ServiceOptions(
				AutoblockExemptionList::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig(),
			),
			LoggerFactory::getInstance( 'AutoblockExemptionList' ),
			$messageFormatterFactory->getTextFormatter(
				$services->getContentLanguageCode()->toString()
			)
		);
	},

	'BacklinkCacheFactory' => static function ( MediaWikiServices $services ): BacklinkCacheFactory {
		return new BacklinkCacheFactory(
			new ServiceOptions(
				BacklinkCache::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			),
			$services->getLinksMigration(),
			$services->getMainWANObjectCache(),
			$services->getHookContainer(),
			$services->getConnectionProvider(),
			LoggerFactory::getInstance( 'BacklinkCache' )
		);
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
		return $services->getFormatterFactory()->getBlockErrorFormatter(
			new LazyLocalizationContext( static function () {
				return RequestContext::getMain();
			} )
		);
	},

	'BlockManager' => static function ( MediaWikiServices $services ): BlockManager {
		return new BlockManager(
			new ServiceOptions(
				BlockManager::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			),
			$services->getUserFactory(),
			$services->getUserIdentityUtils(),
			LoggerFactory::getInstance( 'BlockManager' ),
			$services->getHookContainer(),
			$services->getDatabaseBlockStore(),
			$services->getBlockTargetFactory(),
			$services->getProxyLookup()
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
			$services->getBlockTargetFactory()
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

	'BlockTargetFactory' => static function ( MediaWikiServices $services ): BlockTargetFactory {
		return $services->getCrossWikiBlockTargetFactory()->getFactory();
	},

	'BlockUserFactory' => static function ( MediaWikiServices $services ): BlockUserFactory {
		return $services->getService( '_UserBlockCommandFactory' );
	},

	'BlockUtils' => static function ( MediaWikiServices $services ): BlockUtils {
		return $services->getBlockUtilsFactory()->getBlockUtils();
	},

	'BlockUtilsFactory' => static function ( MediaWikiServices $services ): BlockUtilsFactory {
		return new BlockUtilsFactory(
			$services->getCrossWikiBlockTargetFactory()
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
			$services->getUserIdentityLookup(),
			$services->getUserFactory()
		);
	},

	'ChangeTagDefStore' => static function ( MediaWikiServices $services ): NameTableStore {
		return $services->getNameTableStoreFactory()->getChangeTagDef();
	},

	'ChangeTagsStore' => static function ( MediaWikiServices $services ): ChangeTagsStore {
		return new ChangeTagsStore(
			$services->getConnectionProvider(),
			$services->getChangeTagDefStore(),
			$services->getMainWANObjectCache(),
			$services->getHookContainer(),
			LoggerFactory::getInstance( 'ChangeTags' ),
			$services->getUserFactory(),
			new ServiceOptions(
				ChangeTagsStore::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			)
		);
	},

	'ChronologyProtector' => static function ( MediaWikiServices $services ): ChronologyProtector {
		$mainConfig = $services->getMainConfig();
		$microStashIsDatabase = $services->getObjectCacheFactory()->isDatabaseId(
			$mainConfig->get( MainConfigNames::MicroStashType )
		);
		$cpStash = $microStashIsDatabase
			? new EmptyBagOStuff()
			: $services->getMicroStash();

		$chronologyProtector = new ChronologyProtector(
			$cpStash,
			$mainConfig->get( MainConfigNames::ChronologyProtectorSecret ),
			MW_ENTRY_POINT === 'cli',
			LoggerFactory::getInstance( 'rdbms' )
		);

		// Use the global WebRequest singleton. The main reason for using this
		// is to call WebRequest::getIP() which is non-trivial to reproduce statically
		// because it needs $wgUsePrivateIPs, as well as ProxyLookup and HookRunner services.
		// TODO: Create a static version of WebRequest::getIP that accepts these three
		// as dependencies, and then call that here. The other uses of $req below can
		// trivially use $_COOKIES, $_GET and $_SERVER instead.
		$req = RequestContext::getMain()->getRequest();

		// Set user IP/agent information for agent session consistency purposes
		$reqStart = (int)( $_SERVER['REQUEST_TIME_FLOAT'] ?? time() );
		$cpPosInfo = ChronologyProtector::getCPInfoFromCookieValue(
		// The cookie has no prefix and is set by MediaWiki::preOutputCommit()
			$req->getCookie( 'cpPosIndex', '' ),
			// Mitigate broken client-side cookie expiration handling (T190082)
			$reqStart - ChronologyProtector::POSITION_COOKIE_TTL
		);
		$chronologyProtector->setRequestInfo( [
			'IPAddress' => $req->getIP(),
			'UserAgent' => $req->getHeader( 'User-Agent' ),
			'ChronologyPositionIndex' => $req->getInt( 'cpPosIndex', $cpPosInfo['index'] ),
			'ChronologyClientId' => $cpPosInfo['clientId'] ?? null,
		] );
		return $chronologyProtector;
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
		return new CommentFormatter(
			$services->getCommentParserFactory()
		);
	},

	'CommentParserFactory' => static function ( MediaWikiServices $services ): CommentParserFactory {
		return new CommentParserFactory(
			$services->getLinkRendererFactory()->create( [ 'renderForComment' => true ] ),
			$services->getLinkBatchFactory(),
			$services->getLinkCache(),
			$services->getRepoGroup(),
			RequestContext::getMain()->getLanguage(),
			$services->getContentLanguage(),
			$services->getTitleParser(),
			$services->getNamespaceInfo(),
			$services->getHookContainer()
		);
	},

	'CommentStore' => static function ( MediaWikiServices $services ): CommentStore {
		return new CommentStore( $services->getContentLanguage() );
	},

	'ConfigFactory' => static function ( MediaWikiServices $services ): ConfigFactory {
		// Use the bootstrap config to initialize the ConfigFactory.
		$registry = $services->getBootstrapConfig()->get( MainConfigNames::ConfigRegistry );
		$factory = new ConfigFactory();

		foreach ( $registry as $name => $callback ) {
			$factory->register( $name, $callback );
		}
		return $factory;
	},

	'ConfigRepository' => static function ( MediaWikiServices $services ): ConfigRepository {
		return new ConfigRepository( $services->getConfigFactory() );
	},

	'ConfigSchema' => static function ( MediaWikiServices $services ): ConfigSchema {
		/** @var SettingsBuilder $settings */
		$settings = $services->get( '_SettingsBuilder' );
		return $settings->getConfigSchema();
	},

	'ConfiguredReadOnlyMode' => static function ( MediaWikiServices $services ): ConfiguredReadOnlyMode {
		$config = $services->getMainConfig();
		return new ConfiguredReadOnlyMode(
			$config->get( MainConfigNames::ReadOnly ),
			$config->get( MainConfigNames::ReadOnlyFile )
		);
	},

	'ConnectionProvider' => static function ( MediaWikiServices $services ): IConnectionProvider {
		return $services->getDBLoadBalancerFactory();
	},

	'ContentHandlerFactory' => static function ( MediaWikiServices $services ): IContentHandlerFactory {
		$contentHandlerConfig = $services->getMainConfig()->get( MainConfigNames::ContentHandlers );

		return new ContentHandlerFactory(
			$contentHandlerConfig,
			$services->getObjectFactory(),
			$services->getHookContainer(),
			LoggerFactory::getInstance( 'ContentHandler' )
		);
	},

	'ContentLanguage' => static function ( MediaWikiServices $services ): Language {
		return $services->getLanguageFactory()->getLanguage(
			$services->getMainConfig()->get( MainConfigNames::LanguageCode ) );
	},

	'ContentLanguageCode' => static function ( MediaWikiServices $services ): LanguageCode {
		return $services->getLanguageFactory()->getLanguageCode(
			$services->getMainConfig()->get( MainConfigNames::LanguageCode ) );
	},

	'ContentModelChangeFactory' => static function ( MediaWikiServices $services ): ContentModelChangeFactory {
		return $services->getService( '_PageCommandFactory' );
	},

	'ContentModelStore' => static function ( MediaWikiServices $services ): NameTableStore {
		return $services->getNameTableStoreFactory()->getContentModels();
	},

	'ContentRenderer' => static function ( MediaWikiServices $services ): ContentRenderer {
		return new ContentRenderer(
			$services->getContentHandlerFactory(),
			$services->getGlobalIdGenerator()
		);
	},

	'ContentTransformer' => static function ( MediaWikiServices $services ): ContentTransformer {
		return new ContentTransformer( $services->getContentHandlerFactory() );
	},

	'CriticalSectionProvider' => static function ( MediaWikiServices $services ): CriticalSectionProvider {
		$config = $services->getMainConfig();
		$limit = MW_ENTRY_POINT === 'cli' ? INF : $config->get( MainConfigNames::CriticalSectionTimeLimit );
		return RequestTimeout::singleton()->createCriticalSectionProvider( $limit );
	},

	'CrossWikiBlockTargetFactory' => static function ( MediaWikiServices $services ): CrossWikiBlockTargetFactory {
		return new CrossWikiBlockTargetFactory(
			new ServiceOptions( CrossWikiBlockTargetFactory::CONSTRUCTOR_OPTIONS, $services->getMainConfig() ),
			$services->getActorStoreFactory(),
			$services->getUserNameUtils()
		);
	},

	'DatabaseBlockStore' => static function ( MediaWikiServices $services ): DatabaseBlockStore {
		return $services->getDatabaseBlockStoreFactory()->getDatabaseBlockStore( DatabaseBlock::LOCAL );
	},

	'DatabaseBlockStoreFactory' => static function ( MediaWikiServices $services ): DatabaseBlockStoreFactory {
		return new DatabaseBlockStoreFactory(
			new ServiceOptions(
				DatabaseBlockStoreFactory::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			),
			LoggerFactory::getInstance( 'DatabaseBlockStore' ),
			$services->getActorStoreFactory(),
			$services->getBlockRestrictionStoreFactory(),
			$services->getCommentStore(),
			$services->getHookContainer(),
			$services->getDBLoadBalancerFactory(),
			$services->getReadOnlyMode(),
			$services->getUserFactory(),
			$services->getTempUserConfig(),
			$services->getCrossWikiBlockTargetFactory(),
			$services->getAutoblockExemptionList()
		);
	},

	'DatabaseFactory' => static function ( MediaWikiServices $services ): DatabaseFactory {
		return new DatabaseFactory(
			[
				'debugSql' => $services->getMainConfig()->get( MainConfigNames::DebugDumpSql ),
				'tracer' => $services->getTracer(),
			]
		);
	},

	'DateFormatterFactory' => static function ( MediaWikiServices $services ): DateFormatterFactory {
		return new DateFormatterFactory();
	},

	'DBLoadBalancer' => static function ( MediaWikiServices $services ): Wikimedia\Rdbms\ILoadBalancer {
		// just return the default LB from the DBLoadBalancerFactory service
		return $services->getDBLoadBalancerFactory()->getMainLB();
	},

	'DBLoadBalancerFactory' => static function ( MediaWikiServices $services ): Wikimedia\Rdbms\LBFactory {
		$mainConfig = $services->getMainConfig();
		$lbFactoryConfigBuilder = $services->getDBLoadBalancerFactoryConfigBuilder();

		$lbConf = $lbFactoryConfigBuilder->applyDefaultConfig(
			$mainConfig->get( MainConfigNames::LBFactoryConf )
		);

		$class = $lbFactoryConfigBuilder->getLBFactoryClass( $lbConf );
		$instance = new $class( $lbConf );

		$lbFactoryConfigBuilder->setDomainAliases( $instance );

		return $instance;
	},

	'DBLoadBalancerFactoryConfigBuilder' => static function ( MediaWikiServices $services ): MWLBFactory {
		$mainConfig = $services->getMainConfig();
		if ( $services->getObjectCacheFactory()->isDatabaseId(
			$mainConfig->get( MainConfigNames::MainCacheType )
		) ) {
			$wanCache = WANObjectCache::newEmpty();
		} else {
			$wanCache = $services->getMainWANObjectCache();
		}
		$srvCache = $services->getLocalServerObjectCache();
		if ( $srvCache instanceof EmptyBagOStuff ) {
			// Use process cache if no APCU or other local-server cache (e.g. on CLI)
			$srvCache = new HashBagOStuff( [ 'maxKeys' => 100 ] );
		}

		return new MWLBFactory(
			new ServiceOptions( MWLBFactory::APPLY_DEFAULT_CONFIG_OPTIONS, $services->getMainConfig() ),
			new ConfiguredReadOnlyMode(
				$mainConfig->get( MainConfigNames::ReadOnly ),
				$mainConfig->get( MainConfigNames::ReadOnlyFile )
			),
			$services->getChronologyProtector(),
			$srvCache,
			$wanCache,
			$services->getCriticalSectionProvider(),
			$services->getStatsFactory(),
			ExtensionRegistry::getInstance()->getAttribute( 'DatabaseVirtualDomains' ),
			$services->getTracer(),
		);
	},

	'DefaultOutputPipeline' => static function ( MediaWikiServices $services ): OutputTransformPipeline {
		return ( new DefaultOutputPipelineFactory(
			new ServiceOptions(
				DefaultOutputPipelineFactory::CONSTRUCTOR_OPTIONS, $services->getMainConfig()
			),
			$services->getMainConfig(),
			LoggerFactory::getInstance( 'Parser' ),
			$services->getObjectFactory()
		) )->buildPipeline();
	},

	'DeletePageFactory' => static function ( MediaWikiServices $services ): DeletePageFactory {
		return $services->getService( '_PageCommandFactory' );
	},

	'DomainEventDispatcher' => static function ( MediaWikiServices $services ): DomainEventDispatcher {
		return $services->getService( '_DomainEventDispatcher' );
	},

	'DomainEventSource' => static function ( MediaWikiServices $services ): DomainEventSource {
		return $services->getService( '_DomainEventDispatcher' );
	},

	'Emailer' => static function ( MediaWikiServices $services ): IEmailer {
		return new Emailer();
	},

	'EmailUserFactory' => static function ( MediaWikiServices $services ): EmailUserFactory {
		return new EmailUserFactory(
			new ServiceOptions( EmailUser::CONSTRUCTOR_OPTIONS, $services->getMainConfig() ),
			$services->getHookContainer(),
			$services->getUserOptionsLookup(),
			$services->getCentralIdLookup(),
			$services->getUserFactory(),
			$services->getEmailer(),
			$services->getMessageFormatterFactory(),
			$services->getMessageFormatterFactory()->getTextFormatter(
				$services->getContentLanguageCode()->toString()
			)
		);
	},

	'EventRelayerGroup' => static function ( MediaWikiServices $services ): EventRelayerGroup {
		return new EventRelayerGroup( $services->getMainConfig()->get( MainConfigNames::EventRelayerConfig ) );
	},

	'ExtensionRegistry' => static function ( MediaWikiServices $services ): ExtensionRegistry {
		return ExtensionRegistry::getInstance();
	},

	'ExternalStoreAccess' => static function ( MediaWikiServices $services ): ExternalStoreAccess {
		return new ExternalStoreAccess(
			$services->getExternalStoreFactory(),
			LoggerFactory::getInstance( 'ExternalStore' )
		);
	},

	'ExternalStoreFactory' => static function ( MediaWikiServices $services ): ExternalStoreFactory {
		$config = $services->getMainConfig();
		$writeStores = $config->get( MainConfigNames::DefaultExternalStore );

		return new ExternalStoreFactory(
			$config->get( MainConfigNames::ExternalStores ),
			( $writeStores !== false ) ? (array)$writeStores : [],
			$services->getDBLoadBalancer()->getLocalDomainID(),
			LoggerFactory::getInstance( 'ExternalStore' )
		);
	},

	'FeatureShutdown' => static function ( MediaWikiServices $services ): FeatureShutdown {
		return new FeatureShutdown(
			new ServiceOptions(
				FeatureShutdown::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			)
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
			$services->getReadOnlyMode(),
			$cache,
			$services->getMainWANObjectCache(),
			$services->getMimeAnalyzer(),
			$services->getLockManagerGroupFactory(),
			$services->getTempFSFileFactory(),
			$services->getObjectFactory()
		);
	},

	'FormatterFactory' => static function ( MediaWikiServices $services ): FormatterFactory {
		return new FormatterFactory(
			$services->getMessageParser(),
			$services->getTitleFormatter(),
			$services->getHookContainer(),
			$services->getUserIdentityUtils(),
			$services->getLanguageFactory(),
			LoggerFactory::getInstance( 'status' )
		);
	},

	'GenderCache' => static function ( MediaWikiServices $services ): GenderCache {
		$nsInfo = $services->getNamespaceInfo();
		// If there is no database, use defaults
		if ( $services->isServiceDisabled( 'DBLoadBalancer' ) ) {
			$userOptionsLookup = new StaticUserOptionsLookup(
				[],
				$services->getMainConfig()->get( MainConfigNames::DefaultUserOptions )
			);
		} else {
			$userOptionsLookup = $services->getUserOptionsLookup();
		}
		return new GenderCache( $nsInfo, $userOptionsLookup );
	},

	'GlobalIdGenerator' => static function ( MediaWikiServices $services ): GlobalIdGenerator {
		$mainConfig = $services->getMainConfig();

		return new GlobalIdGenerator(
			$mainConfig->get( MainConfigNames::TmpDirectory ),
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

	'HideUserUtils' => static function ( MediaWikiServices $services ): HideUserUtils {
		return new HideUserUtils();
	},

	'HookContainer' => static function ( MediaWikiServices $services ): HookContainer {
		// NOTE: This is called while $services is being initialized, in order to call the
		//       MediaWikiServices hook.

		$configHooks = $services->getBootstrapConfig()->get( MainConfigNames::Hooks );

		$extRegistry = ExtensionRegistry::getInstance();
		$extHooks = $extRegistry->getAttribute( 'Hooks' );
		$extDeprecatedHooks = $extRegistry->getAttribute( 'DeprecatedHooks' );

		$hookRegistry = new StaticHookRegistry( $configHooks, $extHooks, $extDeprecatedHooks );
		$hookContainer = new HookContainer(
			$hookRegistry,
			$services->getObjectFactory()
		);

		return $hookContainer;
	},

	'HtmlCacheUpdater' => static function ( MediaWikiServices $services ): HTMLCacheUpdater {
		$config = $services->getMainConfig();

		return new HTMLCacheUpdater(
			$services->getHookContainer(),
			$services->getTitleFactory(),
			$config->get( MainConfigNames::CdnReboundPurgeDelay ),
			$config->get( MainConfigNames::UseFileCache ),
			$config->get( MainConfigNames::CdnMaxAge )
		);
	},

	'HtmlTransformFactory' => static function ( MediaWikiServices $services ): HtmlTransformFactory {
		return new HtmlTransformFactory(
			$services->getService( '_Parsoid' ),
			$services->getMainConfig()->get( MainConfigNames::ParsoidSettings ),
			$services->getParsoidPageConfigFactory(),
			$services->getContentHandlerFactory(),
			$services->getParsoidSiteConfig(),
			$services->getTitleFactory(),
			$services->getLanguageConverterFactory(),
			$services->getLanguageFactory()
		);
	},

	'HttpRequestFactory' => static function ( MediaWikiServices $services ): HttpRequestFactory {
		return new HttpRequestFactory(
			new ServiceOptions(
				HttpRequestFactory::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			),
			LoggerFactory::getInstance( 'http' ),
			$services->getTracer()
		);
	},

	'InterwikiLookup' => static function ( MediaWikiServices $services ): InterwikiLookup {
		return new ClassicInterwikiLookup(
			new ServiceOptions(
				ClassicInterwikiLookup::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig(),
				[ 'wikiId' => WikiMap::getCurrentWikiId() ]
			),
			$services->getContentLanguage(),
			$services->getMainWANObjectCache(),
			$services->getHookContainer(),
			$services->getConnectionProvider(),
			$services->getLanguageNameUtils()
		);
	},

	'IntroMessageBuilder' => static function ( MediaWikiServices $services ): IntroMessageBuilder {
		return new IntroMessageBuilder(
			$services->getMainConfig(),
			$services->getLinkRenderer(),
			$services->getPermissionManager(),
			$services->getUserNameUtils(),
			$services->getTempUserCreator(),
			$services->getUserFactory(),
			$services->getRestrictionStore(),
			$services->getDatabaseBlockStore(),
			$services->getReadOnlyMode(),
			$services->getSpecialPageFactory(),
			$services->getRepoGroup(),
			$services->getNamespaceInfo(),
			$services->getSkinFactory(),
			$services->getConnectionProvider(),
			$services->getUrlUtils()
		);
	},

	'JobFactory' => static function ( MediaWikiServices $services ): JobFactory {
		return new JobFactory(
			$services->getObjectFactory(),
			$services->getMainConfig()->get( MainConfigNames::JobClasses )
		);
	},

	'JobQueueGroup' => static function ( MediaWikiServices $services ): JobQueueGroup {
		return $services->getJobQueueGroupFactory()->makeJobQueueGroup();
	},

	'JobQueueGroupFactory' => static function ( MediaWikiServices $services ): JobQueueGroupFactory {
		return new JobQueueGroupFactory(
			new ServiceOptions( JobQueueGroupFactory::CONSTRUCTOR_OPTIONS, $services->getMainConfig() ),
			$services->getReadOnlyMode(),
			$services->getStatsFactory(),
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
			$services->getStatsFactory(),
			LoggerFactory::getInstance( 'runJobs' )
		);
	},

	'JsonCodec' => static function ( MediaWikiServices $services ): JsonCodec {
		return new JsonCodec( $services );
	},

	'LanguageConverterFactory' => static function ( MediaWikiServices $services ): LanguageConverterFactory {
		return new LanguageConverterFactory(
			new ServiceOptions( LanguageConverterFactory::CONSTRUCTOR_OPTIONS, $services->getMainConfig() ),
			$services->getObjectFactory(),
			static function () use ( $services ) {
				return $services->getContentLanguage();
			}
		);
	},

	'LanguageFactory' => static function ( MediaWikiServices $services ): LanguageFactory {
		return new LanguageFactory(
			new ServiceOptions( LanguageFactory::CONSTRUCTOR_OPTIONS, $services->getMainConfig() ),
			$services->getNamespaceInfo(),
			$services->getLocalisationCache(),
			$services->getLanguageNameUtils(),
			$services->getLanguageFallback(),
			$services->getLanguageConverterFactory(),
			$services->getHookContainer(),
			$services->getMainConfig()
		);
	},

	'LanguageFallback' => static function ( MediaWikiServices $services ): LanguageFallback {
		return new LanguageFallback(
			$services->getMainConfig()->get( MainConfigNames::LanguageCode ),
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
			$services->getConnectionProvider(),
			$services->getLinksMigration(),
			$services->getTempUserDetailsLookup(),
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

	'LinksMigration' => static function ( MediaWikiServices $services ): LinksMigration {
		return new LinksMigration(
			$services->getMainConfig(),
			$services->getLinkTargetLookup()
		);
	},

	'LinkTargetLookup' => static function ( MediaWikiServices $services ): LinkTargetLookup {
		return new LinkTargetStore(
			$services->getConnectionProvider(),
			$services->getLocalServerObjectCache(),
			$services->getMainWANObjectCache()
		);
	},

	'LintErrorChecker' => static function ( MediaWikiServices $services ): LintErrorChecker {
		return new LintErrorChecker(
			$services->get( '_Parsoid' ),
			$services->getParsoidPageConfigFactory(),
			$services->getTitleFactory(),
			ExtensionRegistry::getInstance(),
			$services->getMainConfig(),
		 );
	},

	'LocalisationCache' => static function ( MediaWikiServices $services ): LocalisationCache {
		$conf = $services->getMainConfig()->get( MainConfigNames::LocalisationCacheConf );

		$logger = LoggerFactory::getInstance( 'localisation' );

		$store = LocalisationCache::getStoreFromConf(
			$conf, $services->getMainConfig()->get( MainConfigNames::CacheDirectory ) );
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
		return $services->getObjectCacheFactory()->getInstance( CACHE_ACCEL );
	},

	'LockManagerGroupFactory' => static function ( MediaWikiServices $services ): LockManagerGroupFactory {
		return new LockManagerGroupFactory(
			WikiMap::getCurrentWikiDbDomain()->getId(),
			$services->getMainConfig()->get( MainConfigNames::LockManagers )
		);
	},

	'LogFormatterFactory' => static function ( MediaWikiServices $services ): LogFormatterFactory {
		return new LogFormatterFactory(
			new ServiceOptions( LogFormatterFactory::SERVICE_OPTIONS, $services->getMainConfig() ),
			$services->getObjectFactory(),
			$services->getHookContainer(),
			$services->getLinkRenderer(),
			$services->getContentLanguage(),
			$services->getCommentFormatter(),
			$services->getUserEditTracker()
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

		$id = $mainConfig->get( MainConfigNames::MainStash );
		$store = $services->getObjectCacheFactory()->getInstance( $id );
		$store->getLogger()->debug( 'MainObjectStash using store {class}', [
			'class' => get_class( $store )
		] );

		return $store;
	},

	'MainWANObjectCache' => static function ( MediaWikiServices $services ): WANObjectCache {
		$mainConfig = $services->getMainConfig();

		$store = $services->getObjectCacheFactory()->getLocalClusterInstance();
		$logger = $store->getLogger();
		$logger->debug( 'MainWANObjectCache using store {class}', [
			'class' => get_class( $store )
		] );

		$wanParams = $mainConfig->get( MainConfigNames::WANObjectCache ) + [
			'cache' => $store,
			'logger' => $logger,
			'tracer' => $services->getTracer(),
		];
		if ( MW_ENTRY_POINT !== 'cli' ) {
			// Send the statsd data post-send on HTTP requests; avoid in CLI mode (T181385)
			$wanParams['stats'] = $services->getStatsFactory();
			// Let pre-emptive refreshes happen post-send on HTTP requests
			$wanParams['asyncHandler'] = [ DeferredUpdates::class, 'addCallableUpdate' ];
		}
		return new WANObjectCache( $wanParams );
	},

	'MediaHandlerFactory' => static function ( MediaWikiServices $services ): MediaHandlerFactory {
		return new MediaHandlerFactory(
			LoggerFactory::getInstance( 'MediaHandlerFactory' ),
			$services->getMainConfig()->get( MainConfigNames::MediaHandlers )
		);
	},

	'MergeHistoryFactory' => static function ( MediaWikiServices $services ): MergeHistoryFactory {
		return $services->getService( '_PageCommandFactory' );
	},

	'MessageCache' => static function ( MediaWikiServices $services ): MessageCache {
		$mainConfig = $services->getMainConfig();
		$mainCache = $services->getObjectCacheFactory()
			->getInstance( $mainConfig->get( MainConfigNames::MessageCacheType ) );
		$srvCache = $mainConfig->get( MainConfigNames::UseLocalMessageCache )
			? $services->getLocalServerObjectCache()
			: new EmptyBagOStuff();

		$logger = LoggerFactory::getInstance( 'MessageCache' );
		$logger->debug( 'MessageCache using store {class}', [
			'class' => get_class( $mainCache )
		] );

		$options = new ServiceOptions( MessageCache::CONSTRUCTOR_OPTIONS, $mainConfig );

		return new MessageCache(
			$services->getMainWANObjectCache(),
			$mainCache,
			$srvCache,
			$services->getContentLanguage(),
			$services->getLanguageConverterFactory(),
			$logger,
			$options,
			$services->getLocalisationCache(),
			$services->getLanguageNameUtils(),
			$services->getLanguageFallback(),
			$services->getHookContainer(),
			$services->getMessageParser()
		);
	},

	'MessageFormatterFactory' => static function ( MediaWikiServices $services ): IMessageFormatterFactory {
		return new MessageFormatterFactory();
	},

	'MessageParser' => static function ( MediaWikiServices $services ): MessageParser {
		return new MessageParser(
			$services->getParserFactory(),
			$services->getDefaultOutputPipeline(),
			$services->getLanguageFactory(),
			LoggerFactory::getInstance( 'MessageParser' )
		);
	},

	'MicroStash' => static function ( MediaWikiServices $services ): BagOStuff {
		$mainConfig = $services->getMainConfig();

		$id = $mainConfig->get( MainConfigNames::MicroStashType );
		$store = $services->getObjectCacheFactory()->getInstance( $id );

		$store->getLogger()->debug( 'MicroStash using store {class}', [
			'class' => get_class( $store )
		] );

		return $store;
	},

	'MimeAnalyzer' => static function ( MediaWikiServices $services ): MimeAnalyzer {
		$logger = LoggerFactory::getInstance( 'Mime' );
		$mainConfig = $services->getMainConfig();
		$hookRunner = new HookRunner( $services->getHookContainer() );
		$params = [
			'typeFile' => $mainConfig->get( MainConfigNames::MimeTypeFile ),
			'infoFile' => $mainConfig->get( MainConfigNames::MimeInfoFile ),
			'xmlTypes' => $mainConfig->get( MainConfigNames::XMLMimeTypes ),
			'guessCallback' => static function (
				$mimeAnalyzer, &$head, &$tail, $file, &$mime
			) use ( $logger, $hookRunner ) {
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

		$detectorCmd = $mainConfig->get( MainConfigNames::MimeDetectorCommand );
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
			$services->getHookContainer(),
			ExtensionRegistry::getInstance()->getAttribute( 'ExtensionNamespaces' ),
			ExtensionRegistry::getInstance()->getAttribute( 'ImmovableNamespaces' )
		);
	},

	'NameTableStoreFactory' => static function ( MediaWikiServices $services ): NameTableStoreFactory {
		return new NameTableStoreFactory(
			$services->getDBLoadBalancerFactory(),
			$services->getMainWANObjectCache(),
			LoggerFactory::getInstance( 'NameTableSqlStore' )
		);
	},

	'NotificationService' => static function ( MediaWikiServices $services ): NotificationService {
		$handlers = ExtensionRegistry::getInstance()->getAttribute( 'NotificationHandlers' );
		// Inject default MediaWiki handlers
		$handlers[] = NotificationService::RECENT_CHANGE_HANDLER_SPEC;

		return new NotificationService(
			LoggerFactory::getInstance( 'Notification' ),
			$services->getObjectFactory(),
			$services->getService( '_NotificationMiddlewareChain' ),
			$handlers
		);
	},

	'ObjectCacheFactory' => static function ( MediaWikiServices $services ): ObjectCacheFactory {
		return new ObjectCacheFactory(
			new ServiceOptions(
				ObjectCacheFactory::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			),
			$services->getStatsFactory(),
			LoggerFactory::getProvider(),
			// Prevent a recursive service instantiation on DBLoadBalancerFactory
			// and ensure the service keeps working when DB storage is disabled.
			static function () use ( $services ) {
				return $services->getDBLoadBalancerFactory();
			},
			WikiMap::getCurrentWikiDbDomain()->getId(),
			$services->getTracer()
		);
	},

	'ObjectFactory' => static function ( MediaWikiServices $services ): ObjectFactory {
		return new ObjectFactory( $services );
	},

	'OldRevisionImporter' => static function ( MediaWikiServices $services ): OldRevisionImporter {
		return new ImportableOldRevisionImporter(
			true,
			LoggerFactory::getInstance( 'OldRevisionImporter' ),
			$services->getConnectionProvider(),
			$services->getRevisionStoreFactory()->getRevisionStoreForImport(),
			$services->getSlotRoleRegistry(),
			$services->getWikiPageFactory(),
			$services->getPageUpdaterFactory(),
			$services->getUserFactory()
		);
	},

	'PageEditStash' => static function ( MediaWikiServices $services ): PageEditStash {
		return new PageEditStash(
			$services->getObjectCacheFactory()->getLocalClusterInstance(),
			$services->getConnectionProvider(),
			LoggerFactory::getInstance( 'StashEdit' ),
			$services->getStatsFactory(),
			$services->getUserEditTracker(),
			$services->getUserFactory(),
			$services->getWikiPageFactory(),
			$services->getHookContainer(),
			defined( 'MEDIAWIKI_JOB_RUNNER' ) || MW_ENTRY_POINT === 'cli'
				? PageEditStash::INITIATOR_JOB_OR_CLI
				: PageEditStash::INITIATOR_USER
		);
	},

	'PageProps' => static function ( MediaWikiServices $services ): PageProps {
		return new PageProps(
			$services->getLinkBatchFactory(),
			$services->getConnectionProvider()
		);
	},

	'PageRestHelperFactory' => static function ( MediaWikiServices $services ): PageRestHelperFactory {
		return new PageRestHelperFactory(
			new ServiceOptions( PageRestHelperFactory::CONSTRUCTOR_OPTIONS, $services->getMainConfig() ),
			$services->getRevisionLookup(),
			$services->getRevisionRenderer(),
			$services->getTitleFormatter(),
			$services->getPageStore(),
			$services->getParsoidOutputStash(),
			$services->getParserOutputAccess(),
			$services->getParsoidSiteConfig(),
			$services->getHtmlTransformFactory(),
			$services->getContentHandlerFactory(),
			$services->getLanguageFactory(),
			$services->getRedirectStore(),
			$services->getLanguageConverterFactory(),
			$services->getTitleFactory(),
			$services->getConnectionProvider(),
			$services->getChangeTagsStore(),
			$services->getStatsFactory()
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
			$services->getStatsFactory()
		);
	},

	'PageUpdaterFactory' => static function (
		MediaWikiServices $services
	): PageUpdaterFactory {
		$editResultCache = new EditResultCache(
			$services->getMainObjectStash(),
			$services->getConnectionProvider(),
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
			$services->getContentLanguage(),
			$services->getDBLoadBalancerFactory(),
			$services->getContentHandlerFactory(),
			$services->getDomainEventDispatcher(),
			$services->getHookContainer(),
			$editResultCache,
			LoggerFactory::getInstance( 'SavePage' ),
			new ServiceOptions(
				PageUpdaterFactory::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			),
			$services->getUserGroupManager(),
			$services->getTitleFormatter(),
			$services->getContentTransformer(),
			$services->getPageEditStash(),
			$services->getMainWANObjectCache(),
			$services->getWikiPageFactory(),
			$services->getChangeTagsStore(),
			$services->getChangeTagsStore()->getSoftwareTags()
		);
	},

	'Parser' => static function ( MediaWikiServices $services ): Parser {
		// This service exists as convenience function to get the global parser in global code.
		// Do not use this service for dependency injection or in service wiring (T343070).
		// Use the 'ParserFactory' service instead.
		return $services->getParserFactory()->getMainInstance();
	},

	'ParserCache' => static function ( MediaWikiServices $services ): ParserCache {
		return $services->getParserCacheFactory()
			->getParserCache( ParserCacheFactory::DEFAULT_NAME );
	},

	'ParserCacheFactory' => static function ( MediaWikiServices $services ): ParserCacheFactory {
		$config = $services->getMainConfig();
		$cache = $services->getObjectCacheFactory()->getInstance( $config->get( MainConfigNames::ParserCacheType ) );
		$wanCache = $services->getMainWANObjectCache();

		$options = new ServiceOptions( ParserCacheFactory::CONSTRUCTOR_OPTIONS, $config );

		return new ParserCacheFactory(
			$cache,
			$wanCache,
			$services->getHookContainer(),
			$services->getJsonCodec(),
			$services->getStatsFactory(),
			LoggerFactory::getInstance( 'ParserCache' ),
			$options,
			$services->getTitleFactory(),
			$services->getWikiPageFactory(),
			$services->getGlobalIdGenerator()
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
			$services->getUrlUtils(),
			$services->getSpecialPageFactory(),
			$services->getLinkRendererFactory(),
			$services->getNamespaceInfo(),
			LoggerFactory::getInstance( 'Parser' ),
			$services->getBadFileLookup(),
			$services->getLanguageConverterFactory(),
			$services->getLanguageNameUtils(),
			$services->getHookContainer(),
			$services->getTidy(),
			$services->getMainWANObjectCache(),
			$services->getUserOptionsLookup(),
			$services->getUserFactory(),
			$services->getTitleFormatter(),
			$services->getHttpRequestFactory(),
			$services->getTrackingCategories(),
			$services->getSignatureValidatorFactory(),
			$services->getUserNameUtils()
		);
	},

	'ParserOutputAccess' => static function ( MediaWikiServices $services ): ParserOutputAccess {
		$poa = new ParserOutputAccess(
			$services->getParserCacheFactory(),
			$services->getRevisionLookup(),
			$services->getRevisionRenderer(),
			$services->getStatsFactory(),
			$services->getChronologyProtector(),
			$services->getWikiPageFactory(),
			$services->getTitleFormatter(),
			$services->getTracer(),
			$services->getPoolCounterFactory()
		);

		$poa->setLogger( LoggerFactory::getInstance( 'ParserOutputAccess' ) );
		return $poa;
	},

	'ParsoidDataAccess' => static function ( MediaWikiServices $services ): DataAccess {
		$mainConfig = $services->getMainConfig();
		return new MWDataAccess(
			new ServiceOptions( MWDataAccess::CONSTRUCTOR_OPTIONS, $mainConfig ),
			$services->getRepoGroup(),
			$services->getBadFileLookup(),
			$services->getHookContainer(),
			$services->getContentTransformer(),
			$services->getTrackingCategories(),
			$services->getReadOnlyMode(),
			$services->getParserFactory(), // *legacy* parser factory
			$services->getLinkBatchFactory()
		);
	},

	'ParsoidOutputStash' => static function ( MediaWikiServices $services ): ParsoidOutputStash {
		// TODO: Determine storage requirements and config options for stashing parsoid
		//       output for VE edits (T309016).
		$config = $services->getMainConfig()->get( MainConfigNames::ParsoidCacheConfig );
		$backend = $config['StashType']
			? $services->getObjectCacheFactory()->getInstance( $config['StashType'] )
			: $services->getMainObjectStash();

		return new SimpleParsoidOutputStash(
			$services->getContentHandlerFactory(),
			$backend,
			$config['StashDuration']
		);
	},

	'ParsoidPageConfigFactory' => static function ( MediaWikiServices $services ): MWPageConfigFactory {
		return new MWPageConfigFactory(
			$services->getRevisionStore(),
			$services->getSlotRoleRegistry(),
			$services->getLanguageFactory()
		);
	},

	'ParsoidParserFactory' => static function ( MediaWikiServices $services ): ParsoidParserFactory {
		return new ParsoidParserFactory(
			$services->getParsoidSiteConfig(),
			$services->getParsoidDataAccess(),
			$services->getParsoidPageConfigFactory(),
			$services->getLanguageConverterFactory(),
			$services->getParserFactory()
		);
	},

	'ParsoidSiteConfig' => static function ( MediaWikiServices $services ): MWSiteConfig {
		$mainConfig = $services->getMainConfig();
		$parsoidSettings = $mainConfig->get( MainConfigNames::ParsoidSettings );
		return new MWSiteConfig(
			new ServiceOptions( MWSiteConfig::CONSTRUCTOR_OPTIONS, $mainConfig ),
			$parsoidSettings,
			$services->getObjectFactory(),
			$services->getContentLanguage(),
			$services->getStatsdDataFactory(),
			$services->getStatsFactory(),
			$services->getMagicWordFactory(),
			$services->getNamespaceInfo(),
			$services->getSpecialPageFactory(),
			$services->getInterwikiLookup(),
			$services->getUserOptionsLookup(),
			$services->getLanguageFactory(),
			$services->getLanguageConverterFactory(),
			$services->getLanguageNameUtils(),
			$services->getUrlUtils(),
			$services->getContentHandlerFactory(),
			ExtensionRegistry::getInstance()->getAttribute( 'ParsoidModules' ),
			// These arguments are temporary and will be removed once
			// better solutions are found.
			$services->getParserFactory(), // T268776
			$mainConfig, // T268777
			ExtensionRegistry::getInstance()->isLoaded( 'TimedMediaHandler' )
		);
	},

	'PasswordFactory' => static function ( MediaWikiServices $services ): PasswordFactory {
		$config = $services->getMainConfig();
		return new PasswordFactory(
			$config->get( MainConfigNames::PasswordConfig ),
			$config->get( MainConfigNames::PasswordDefault )
		);
	},

	'PasswordReset' => static function ( MediaWikiServices $services ): PasswordReset {
		$options = new ServiceOptions( PasswordReset::CONSTRUCTOR_OPTIONS, $services->getMainConfig() );
		return new PasswordReset(
			$options,
			LoggerFactory::getInstance( 'authentication' ),
			$services->getAuthManager(),
			$services->getHookContainer(),
			$services->getUserIdentityLookup(),
			$services->getUserFactory(),
			$services->getUserNameUtils(),
			$services->getUserOptionsLookup()
		);
	},

	'PerDbNameStatsdDataFactory' => static function ( MediaWikiServices $services ): StatsdDataFactoryInterface {
		$config = $services->getMainConfig();
		$wiki = $config->get( MainConfigNames::DBname );
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
			$services->getBlockManager(),
			$services->getFormatterFactory()->getBlockErrorFormatter(
				new LazyLocalizationContext( static function () {
					return RequestContext::getMain();
				} )
			),
			$services->getHookContainer(),
			$services->getUserIdentityLookup(),
			$services->getRedirectLookup(),
			$services->getRestrictionStore(),
			$services->getTitleFormatter(),
			$services->getTempUserConfig(),
			$services->getUserFactory(),
			$services->getActionFactory()
		);
	},

	'Pingback' => static function ( MediaWikiServices $services ): Pingback {
		return new Pingback(
			$services->getMainConfig(),
			$services->getConnectionProvider(),
			$services->getObjectCacheFactory()->getLocalClusterInstance(),
			$services->getHttpRequestFactory(),
			LoggerFactory::getInstance( 'Pingback' )
		);
	},

	'PoolCounterFactory' => static function ( MediaWikiServices $services ): PoolCounterFactory {
		$mainConfig = $services->getMainConfig();
		return new PoolCounterFactory(
			$mainConfig->get( MainConfigNames::PoolCounterConf ),
			$mainConfig->get( MainConfigNames::PoolCountClientConf ),
			LoggerFactory::getInstance( 'poolcounter' ),
			$services->getTracer()
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
			$services->getParserFactory(),
			$services->getSkinFactory(),
			$services->getUserGroupManager(),
			$services->getSignatureValidatorFactory()
		);
		$factory->setLogger( LoggerFactory::getInstance( 'preferences' ) );

		return $factory;
	},

	'PreloadedContentBuilder' => static function ( MediaWikiServices $services ): PreloadedContentBuilder {
		return new PreloadedContentBuilder(
			$services->getContentHandlerFactory(),
			$services->getWikiPageFactory(),
			$services->getRedirectLookup(),
			$services->getSpecialPageFactory(),
			$services->getContentTransformer(),
			$services->getHookContainer(),
		);
	},

	'ProxyLookup' => static function ( MediaWikiServices $services ): ProxyLookup {
		$mainConfig = $services->getMainConfig();
		return new ProxyLookup(
			$mainConfig->get( MainConfigNames::CdnServers ),
			$mainConfig->get( MainConfigNames::CdnServersNoPurge ),
			$services->getHookContainer()
		);
	},

	'RateLimiter' => static function ( MediaWikiServices $services ): RateLimiter {
		$rateLimiter = new RateLimiter(
			new ServiceOptions( RateLimiter::CONSTRUCTOR_OPTIONS, $services->getMainConfig() ),
			$services->getWRStatsFactory(),
			$services->getCentralIdLookupFactory()->getNonLocalLookup(),
			$services->getUserFactory(),
			$services->getUserGroupManager(),
			$services->getHookContainer()
		);

		$rateLimiter->setStats( $services->getStatsFactory() );

		return $rateLimiter;
	},

	'ReadOnlyMode' => static function ( MediaWikiServices $services ): ReadOnlyMode {
		return new ReadOnlyMode(
			new ConfiguredReadOnlyMode(
				$services->getMainConfig()->get( MainConfigNames::ReadOnly ),
				$services->getMainConfig()->get( MainConfigNames::ReadOnlyFile )
			),
			$services->getDBLoadBalancerFactory()
		);
	},

	'RedirectLookup' => static function ( MediaWikiServices $services ): RedirectLookup {
		return $services->getRedirectStore();
	},

	'RedirectStore' => static function ( MediaWikiServices $services ): RedirectStore {
		return new RedirectStore(
			$services->getConnectionProvider(),
			$services->getPageStore(),
			$services->getTitleParser(),
			$services->getRepoGroup(),
			LoggerFactory::getInstance( 'RedirectStore' )
		);
	},

	'RenameUserFactory' => static function ( MediaWikiServices $services ): RenameUserFactory {
		return new RenameUserFactory(
			new ServiceOptions( RenameUserFactory::CONSTRUCTOR_OPTIONS, $services->getMainConfig() ),
			$services->getCentralIdLookupFactory(),
			$services->getJobQueueGroupFactory(),
			$services->getMovePageFactory(),
			$services->getUserFactory(),
			$services->getUserNameUtils(),
			$services->getPermissionManager(),
			$services->getTitleFactory(),
		);
	},

	'RepoGroup' => static function ( MediaWikiServices $services ): RepoGroup {
		$config = $services->getMainConfig();
		return new RepoGroup(
			$config->get( MainConfigNames::LocalFileRepo ),
			$config->get( MainConfigNames::ForeignFileRepos ),
			$services->getMainWANObjectCache(),
			$services->getMimeAnalyzer()
		);
	},

	'ResourceLoader' => static function ( MediaWikiServices $services ): ResourceLoader {
		$config = $services->getMainConfig();

		$maxage = $config->get( MainConfigNames::ResourceLoaderMaxage );
		$rl = new ResourceLoader(
			$config,
			LoggerFactory::getInstance( 'resourceloader' ),
			new DependencyStore( $services->getMainObjectStash() ),
			[
				'loadScript' => $config->get( MainConfigNames::LoadScript ),
				'maxageVersioned' => $maxage['versioned'] ?? null,
				'maxageUnversioned' => $maxage['unversioned'] ?? null,
			]
		);

		$extRegistry = ExtensionRegistry::getInstance();
		// Attribute has precedence over config
		$modules = $extRegistry->getAttribute( 'ResourceModules' )
			+ $config->get( MainConfigNames::ResourceModules );
		$moduleSkinStyles = $extRegistry->getAttribute( 'ResourceModuleSkinStyles' )
			+ $config->get( MainConfigNames::ResourceModuleSkinStyles );

		$rl->setModuleSkinStyles( $moduleSkinStyles );
		$rl->addSource( $config->get( MainConfigNames::ResourceLoaderSources ) );

		// Core modules, then extension/skin modules
		$rl->register( include MW_INSTALL_PATH . '/resources/Resources.php' );
		$rl->register( $modules );
		$hookRunner = new \MediaWiki\ResourceLoader\HookRunner( $services->getHookContainer() );
		$hookRunner->onResourceLoaderRegisterModules( $rl );

		$msgPosterAttrib = $extRegistry->getAttribute( 'MessagePosterModule' );
		$rl->register( 'mediawiki.messagePoster', [
			'localBasePath' => MW_INSTALL_PATH,
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
		] );

		if ( $config->get( MainConfigNames::EnableJavaScriptTest ) === true ) {
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
			$services->getLinksMigration(),
			$services->getCommentStore(),
			$services->getHookContainer(),
			$services->getPageStore()
		);
	},

	'RevertedTagUpdateManager' => static function ( MediaWikiServices $services ): RevertedTagUpdateManager {
		$editResultCache = new EditResultCache(
			$services->getMainObjectStash(),
			$services->getConnectionProvider(),
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
		return new RevisionStoreFactory(
			$services->getDBLoadBalancerFactory(),
			$services->getBlobStoreFactory(),
			$services->getNameTableStoreFactory(),
			$services->getSlotRoleRegistry(),
			$services->getMainWANObjectCache(),
			$services->getLocalServerObjectCache(),
			$services->getCommentStore(),
			$services->getActorStoreFactory(),
			LoggerFactory::getInstance( 'RevisionStore' ),
			$services->getContentHandlerFactory(),
			$services->getPageStoreFactory(),
			$services->getTitleFactory(),
			$services->getHookContainer()
		);
	},

	'RollbackPageFactory' => static function ( MediaWikiServices $services ): RollbackPageFactory {
		return $services->get( '_PageCommandFactory' );
	},

	'RowCommentFormatter' => static function ( MediaWikiServices $services ): RowCommentFormatter {
		return new RowCommentFormatter(
			$services->getCommentParserFactory(),
			$services->getCommentStore()
		);
	},

	'SearchEngineConfig' => static function ( MediaWikiServices $services ): SearchEngineConfig {
		return new SearchEngineConfig(
			new ServiceOptions(
				SearchEngineConfig::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			),
			$services->getContentLanguage(),
			$services->getHookContainer(),
			ExtensionRegistry::getInstance()->getAttribute( 'SearchMappings' ),
			$services->getUserOptionsLookup()
		);
	},

	'SearchEngineFactory' => static function ( MediaWikiServices $services ): SearchEngineFactory {
		return new SearchEngineFactory(
			$services->getSearchEngineConfig(),
			$services->getHookContainer(),
			$services->getConnectionProvider()
		);
	},

	'SearchResultThumbnailProvider' => static function ( MediaWikiServices $services ): SearchResultThumbnailProvider {
		return new SearchResultThumbnailProvider(
			$services->getRepoGroup(),
			$services->getHookContainer()
		);
	},

	'SessionManager' => static function ( MediaWikiServices $services ): SessionManager {
		// TODO use proper dependency injection
		return SessionManager::singleton();
	},

	'ShellboxClientFactory' => static function ( MediaWikiServices $services ): ShellboxClientFactory {
		$urls = $services->getMainConfig()->get( MainConfigNames::ShellboxUrls );

		return new ShellboxClientFactory(
			$services->getHttpRequestFactory(),
			$urls,
			$services->getMainConfig()->get( MainConfigNames::ShellboxSecretKey )
		);
	},

	'ShellCommandFactory' => static function ( MediaWikiServices $services ): CommandFactory {
		$config = $services->getMainConfig();

		$limits = [
			'time' => $config->get( MainConfigNames::MaxShellTime ),
			'walltime' => $config->get( MainConfigNames::MaxShellWallClockTime ),
			'memory' => $config->get( MainConfigNames::MaxShellMemory ),
			'filesize' => $config->get( MainConfigNames::MaxShellFileSize ),
		];
		$cgroup = $config->get( MainConfigNames::ShellCgroup );
		$restrictionMethod = $config->get( MainConfigNames::ShellRestrictionMethod );

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
			// Use closures for these to avoid a circular dependency on Parser
			static function () use ( $services ) {
				return $services->getParserFactory();
			},
			static function () use ( $services ) {
				return $services->getLintErrorChecker();
			},
			$services->getSpecialPageFactory(),
			$services->getTitleFactory(),
		);
	},

	'SiteLookup' => static function ( MediaWikiServices $services ): SiteLookup {
		// Use SiteStore as the SiteLookup as well. This was originally separated
		// to allow for a cacheable read-only interface, but this was never used.
		// SiteStore has caching (see below).
		return $services->getSiteStore();
	},

	'SiteStore' => static function ( MediaWikiServices $services ): SiteStore {
		$rawSiteStore = new DBSiteStore( $services->getConnectionProvider() );

		// If php-apcu is not installed, then CachingSiteStore still avoids
		// repeat DB queries in the same request through an in-process cache.
		$cache = $services->getLocalServerObjectCache();

		return new CachingSiteStore( $rawSiteStore, $cache );
	},

	/** @suppress PhanTypeInvalidCallableArrayKey */
	'SkinFactory' => static function ( MediaWikiServices $services ): SkinFactory {
		$factory = new SkinFactory(
			$services->getObjectFactory(),
			(array)$services->getMainConfig()->get( MainConfigNames::SkipSkins )
		);

		$names = $services->getMainConfig()->get( MainConfigNames::ValidSkinNames );

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
					'styles' => [ 'mediawiki.skinning.interface', 'mediawiki.codex.messagebox.styles' ],
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
		// Register a hidden skin for Special:UserLogin and Special:CreateAccount
		$factory->register( 'authentication-popup', 'Authentication popup', [
			'class' => SkinAuthenticationPopup::class,
			'args' => [
				[
					'name' => 'authentication-popup',
					'styles' => [
						'mediawiki.skinning.interface',
						'mediawiki.special.userlogin.authentication-popup',
					],
					'bodyClasses' => [ 'mw-authentication-popup' ],
					'responsive' => true,
					'messages' => [
						'sitesubtitle',
						'sitetitle',
					],
					'templateDirectory' => __DIR__ . '/skins/templates/authentication-popup',
				]
			]
		], true );
		// Register a hidden skin for outputting skin json
		$factory->register( 'json', 'SkinJSON', [
			'class' => SkinApi::class,
			'args' => [
				[
					'name' => 'json',
					'styles' => [],
					'format' => 'json',
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
			SlotRecord::MAIN,
			static function () use ( $config, $contentHandlerFactory, $hookContainer, $titleFactory ) {
				return new MainSlotRoleHandler(
					$config->get( MainConfigNames::NamespaceContentModels ),
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
			(array)$services->getMainConfig()->get( MainConfigNames::SpamRegex ),
			(array)$services->getMainConfig()->get( MainConfigNames::SummarySpamRegex )
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
		return new NullStatsdDataFactory();
	},

	'StatsFactory' => static function ( MediaWikiServices $services ): StatsFactory {
		$config = $services->getMainConfig();
		$format = \Wikimedia\Stats\OutputFormats::getFormatFromString(
			$config->get( MainConfigNames::StatsFormat ) ?? 'null'
		);
		$cache = new StatsCache;
		$emitter = \Wikimedia\Stats\OutputFormats::getNewEmitter(
			$config->get( MainConfigNames::StatsPrefix ),
			$cache,
			\Wikimedia\Stats\OutputFormats::getNewFormatter( $format ),
			$config->get( MainConfigNames::StatsTarget )
		);
		return new StatsFactory( $cache, $emitter, LoggerFactory::getInstance( 'Stats' ) );
	},

	'TalkPageNotificationManager' => static function (
		MediaWikiServices $services
	): TalkPageNotificationManager {
		return new TalkPageNotificationManager(
			new ServiceOptions(
				TalkPageNotificationManager::CONSTRUCTOR_OPTIONS, $services->getMainConfig()
			),
			$services->getConnectionProvider(),
			$services->getReadOnlyMode(),
			$services->getRevisionLookup(),
			$services->getHookContainer(),
			$services->getUserFactory()
		);
	},

	'TempFSFileFactory' => static function ( MediaWikiServices $services ): TempFSFileFactory {
		return new TempFSFileFactory( $services->getMainConfig()->get( MainConfigNames::TmpDirectory ) );
	},

	'TempUserConfig' => static function ( MediaWikiServices $services ): RealTempUserConfig {
		return new RealTempUserConfig(
			$services->getMainConfig()->get( MainConfigNames::AutoCreateTempUser )
		);
	},

	'TempUserCreator' => static function ( MediaWikiServices $services ): TempUserCreator {
		return new TempUserCreator(
			$services->getTempUserConfig(),
			$services->getObjectFactory(),
			$services->getUserFactory(),
			$services->getAuthManager(),
			$services->getCentralIdLookup(),
			// This is supposed to match ThrottlePreAuthenticationProvider
			new Throttler(
				$services->getMainConfig()->get( MainConfigNames::TempAccountCreationThrottle ),
				[
					'type' => 'tempacctcreate',
					'cache' => $services->getObjectCacheFactory()->getLocalClusterInstance(),
				]
			),
			new Throttler(
				$services->getMainConfig()->get( MainConfigNames::TempAccountNameAcquisitionThrottle ),
				[
					'type' => 'tempacctnameacquisition',
					'cache' => $services->getObjectCacheFactory()->getLocalClusterInstance(),
				]
			)
		);
	},

	'TempUserDetailsLookup' => static function ( MediaWikiServices $services ): TempUserDetailsLookup {
		return new TempUserDetailsLookup(
			$services->getTempUserConfig(),
			$services->getUserRegistrationLookup()
		);
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
		return new TitleFormatter(
			$services->getContentLanguage(),
			$services->getGenderCache(),
			$services->getNamespaceInfo()
		);
	},

	'TitleMatcher' => static function ( MediaWikiServices $services ): TitleMatcher {
		return new TitleMatcher(
			new ServiceOptions(
				TitleMatcher::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			),
			$services->getContentLanguage(),
			$services->getLanguageConverterFactory(),
			$services->getHookContainer(),
			$services->getWikiPageFactory(),
			$services->getUserNameUtils(),
			$services->getRepoGroup(),
			$services->getTitleFactory()
		);
	},

	'TitleParser' => static function ( MediaWikiServices $services ): TitleParser {
		return new TitleParser(
			$services->getContentLanguage(),
			$services->getInterwikiLookup(),
			$services->getNamespaceInfo(),
			$services->getMainConfig()->get( MainConfigNames::LocalInterwikis )
		);
	},

	'Tracer' => static function ( MediaWikiServices $services ): TracerInterface {
		$xReqIdPropagator = new MediaWikiPropagator( Telemetry::getInstance() );
		$otelConfig = $services->getMainConfig()->get( MainConfigNames::OpenTelemetryConfig );
		if ( $otelConfig === null || ( wfIsCLI() && !defined( 'MW_PHPUNIT_TEST' ) ) ) {
			return new NoopTracer( $xReqIdPropagator );
		}

		$tracerState = TracerState::getInstance();
		$exporter = new OtlpHttpExporter(
			$services->getService( '_TracerHTTPClient' ),
			new HttpFactory(),
			LoggerFactory::getInstance( 'tracing' ),
			$otelConfig['endpoint'],
			$otelConfig['serviceName'],
			wfHostname()
		);

		return new Tracer(
			new Clock(),
			new ProbabilisticSampler( $otelConfig['samplingProbability'] ),
			$exporter,
			$tracerState,
			new CompositePropagator( [ $xReqIdPropagator, new W3CTraceContextPropagator() ] )
		);
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
			$services->getMainConfig()->get( MainConfigNames::EnableUploads ),
			LoggerFactory::getInstance( 'UploadRevisionImporter' )
		);
	},

	'UrlUtils' => static function ( MediaWikiServices $services ): UrlUtils {
		$config = $services->getMainConfig();
		return new UrlUtils( [
			UrlUtils::SERVER => $config->get( MainConfigNames::Server ),
			UrlUtils::CANONICAL_SERVER => $config->get( MainConfigNames::CanonicalServer ),
			UrlUtils::INTERNAL_SERVER => $config->get( MainConfigNames::InternalServer ),
			UrlUtils::FALLBACK_PROTOCOL => RequestContext::getMain()->getRequest()->getProtocol(),
			UrlUtils::HTTPS_PORT => $config->get( MainConfigNames::HttpsPort ),
			UrlUtils::VALID_PROTOCOLS => $config->get( MainConfigNames::UrlProtocols ),
		] );
	},

	'UserCache' => static function ( MediaWikiServices $services ): UserCache {
		return new UserCache(
			LoggerFactory::getInstance( 'UserCache' ),
			$services->getConnectionProvider(),
			$services->getLinkBatchFactory()
		);
	},

	'UserEditTracker' => static function ( MediaWikiServices $services ): UserEditTracker {
		return new UserEditTracker(
			$services->getActorNormalization(),
			$services->getConnectionProvider(),
			$services->getJobQueueGroup()
		);
	},

	'UserFactory' => static function ( MediaWikiServices $services ): UserFactory {
		return new UserFactory(
			new ServiceOptions(
				UserFactory::CONSTRUCTOR_OPTIONS, $services->getMainConfig()
			),
			$services->getDBLoadBalancerFactory(),
			$services->getUserNameUtils(),
			$services->getTempUserConfig()
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
			$services->getReadOnlyMode(),
			$services->getDBLoadBalancerFactory(),
			$services->getHookContainer(),
			$services->getUserEditTracker(),
			$services->getGroupPermissionsLookup(),
			$services->getJobQueueGroupFactory(),
			LoggerFactory::getInstance( 'UserGroupManager' ),
			$services->getTempUserConfig(),
			[ static function ( UserIdentity $user ) use ( $services ) {
				if ( $user->getWikiId() === UserIdentity::LOCAL ) {
					$services->getPermissionManager()->invalidateUsersRightsCache( $user );
				}
				$services->getUserFactory()->invalidateCache( $user );
			} ]
		);
	},

	'UserIdentityLookup' => static function ( MediaWikiServices $services ): UserIdentityLookup {
		return $services->getActorStoreFactory()->getUserIdentityLookup();
	},

	'UserIdentityUtils' => static function ( MediaWikiServices $services ): UserIdentityUtils {
		return new UserIdentityUtils(
			$services->getTempUserConfig()
		);
	},

	'UserLinkRenderer' => static function ( MediaWikiServices $services ): UserLinkRenderer {
		return new UserLinkRenderer(
			$services->getHookContainer(),
			$services->getTempUserConfig(),
			$services->getSpecialPageFactory(),
			$services->getLinkRenderer(),
			$services->getTempUserDetailsLookup(),
			$services->getUserIdentityLookup()
		);
	},

	'UserNamePrefixSearch' => static function ( MediaWikiServices $services ): UserNamePrefixSearch {
		return new UserNamePrefixSearch(
			$services->getConnectionProvider(),
			$services->getUserNameUtils(),
			$services->getHideUserUtils()
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
				$services->getContentLanguageCode()->toString()
			),
			$services->getHookContainer(),
			$services->getTempUserConfig()
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
			$services->getConnectionProvider(),
			LoggerFactory::getInstance( 'UserOptionsManager' ),
			$services->getHookContainer(),
			$services->getUserFactory(),
			$services->getUserNameUtils(),
			$services->getObjectFactory(),
			ExtensionRegistry::getInstance()->getAttribute( 'UserOptionsStoreProviders' )
		);
	},

	'UserRegistrationLookup' => static function ( MediaWikiServices $services ): UserRegistrationLookup {
		$lookup = new UserRegistrationLookup(
			new ServiceOptions( UserRegistrationLookup::CONSTRUCTOR_OPTIONS, $services->getMainConfig() ),
			$services->getObjectFactory()
		);
		if ( !$lookup->isRegistered( LocalUserRegistrationProvider::TYPE ) ) {
			throw new ConfigException( 'UserRegistrationLookup: Local provider is required' );
		}
		return $lookup;
	},

	'WatchedItemQueryService' => static function ( MediaWikiServices $services ): WatchedItemQueryService {
		return new WatchedItemQueryService(
			$services->getConnectionProvider(),
			$services->getCommentStore(),
			$services->getWatchedItemStore(),
			$services->getHookContainer(),
			$services->getUserOptionsLookup(),
			$services->getTempUserConfig(),
			$services->getMainConfig()->get( MainConfigNames::WatchlistExpiry ),
			$services->getMainConfig()->get( MainConfigNames::MaxExecutionTimeForExpensiveQueries )
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
			$services->getLinkBatchFactory(),
			$services->getStatsFactory()
		);

		if ( $services->getMainConfig()->get( MainConfigNames::ReadOnlyWatchedItemStore ) ) {
			$store = new NoWriteWatchedItemStore( $store );
		}

		return $store;
	},

	'WatchlistManager' => static function ( MediaWikiServices $services ): WatchlistManager {
		return new WatchlistManager(
			[
				WatchlistManager::OPTION_ENOTIF =>
					RecentChange::isEnotifEnabled( $services->getMainConfig() ),
			],
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
			$services->getTitleParser(),
			$services->getCommentStore()
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
			$services->getContentHandlerFactory(),
			$services->getSlotRoleRegistry()
		);
	},

	'WikiPageFactory' => static function ( MediaWikiServices $services ): WikiPageFactory {
		return new WikiPageFactory(
			$services->getTitleFactory(),
			new HookRunner( $services->getHookContainer() ),
			$services->getDBLoadBalancerFactory()
		);
	},

	'WikiRevisionOldRevisionImporterNoUpdates' => static function (
		MediaWikiServices $services
	): ImportableOldRevisionImporter {
		return new ImportableOldRevisionImporter(
			false,
			LoggerFactory::getInstance( 'OldRevisionImporter' ),
			$services->getConnectionProvider(),
			$services->getRevisionStoreFactory()->getRevisionStoreForImport(),
			$services->getSlotRoleRegistry(),
			$services->getWikiPageFactory(),
			$services->getPageUpdaterFactory(),
			$services->getUserFactory()
		);
	},

	'WRStatsFactory' => static function ( MediaWikiServices $services ): WRStatsFactory {
		return new WRStatsFactory(
			new BagOStuffStatsStore( $services->getMicroStash() )
		);
	},

	'_ConditionalDefaultsLookup' => static function (
		MediaWikiServices $services
	): ConditionalDefaultsLookup {
		$extraConditions = [];
		return new ConditionalDefaultsLookup(
			new HookRunner( $services->getHookContainer() ),
			new ServiceOptions(
				ConditionalDefaultsLookup::CONSTRUCTOR_OPTIONS, $services->getMainConfig()
			),
			$services->getUserRegistrationLookup(),
			$services->getUserIdentityUtils(),
			static function () use ( $services ) {
				return $services->getUserGroupManager();
			}
		);
	},

	'_DefaultOptionsLookup' => static function ( MediaWikiServices $services ): DefaultOptionsLookup {
		return new DefaultOptionsLookup(
			new ServiceOptions( DefaultOptionsLookup::CONSTRUCTOR_OPTIONS, $services->getMainConfig() ),
			$services->getContentLanguageCode(),
			$services->getHookContainer(),
			$services->getNamespaceInfo(),
			$services->get( '_ConditionalDefaultsLookup' ),
			$services->getUserIdentityLookup(),
			$services->getUserNameUtils()
		);
	},

	'_DomainEventDispatcher' => static function ( MediaWikiServices $services ): EventDispatchEngine {
		$dispatcher = new EventDispatchEngine(
			$services->getObjectFactory()
		);

		// Core event wiring.
		// TODO: move this to a more prominent location? A separate file?

		// Establish the propagation of events to various components
		$dispatcher->registerSubscriber( ChangeTrackingEventIngress::OBJECT_SPEC );
		$dispatcher->registerSubscriber( SearchEventIngress::OBJECT_SPEC );
		$dispatcher->registerSubscriber( LanguageEventIngress::OBJECT_SPEC );
		$dispatcher->registerSubscriber( ResourceLoaderEventIngress::OBJECT_SPEC );

		$extensionRegistry = $services->getExtensionRegistry();
		$dispatcher->registerSubscriber( $extensionRegistry );

		return $dispatcher;
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

			// EditFilterMergedContentHookConstraint
			$services->getHookContainer(),

			// ReadOnlyConstraint
			$services->getReadOnlyMode(),

			// SpamRegexConstraint
			$services->getSpamChecker(),

			// LinkPurgeRateLimitConstraint
			$services->getRateLimiter()
		);
	},

	'_NotificationMiddlewareChain' => static function ( MediaWikiServices $services ): MiddlewareChain {
		return new MiddlewareChain(
			$services->getObjectFactory(),
			ExtensionRegistry::getInstance()->getAttribute( 'NotificationMiddleware' )
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
			$services->getRevisionStoreFactory(),
			$services->getSpamChecker(),
			$services->getTitleFormatter(),
			$services->getHookContainer(),
			$services->getDomainEventDispatcher(),
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
				$services->getContentLanguageCode()->toString()
			),
			$services->getArchivedRevisionLookup(),
			$services->getRestrictionStore(),
			$services->getLinkTargetLookup(),
			$services->getRedirectStore(),
			$services->getLogFormatterFactory()
		);
	},

	'_ParserObserver' => static function ( MediaWikiServices $services ): ParserObserver {
		return new ParserObserver( LoggerFactory::getInstance( 'DuplicateParse' ) );
	},

	'_Parsoid' => static function ( MediaWikiServices $services ): Parsoid {
		return new Parsoid(
			$services->getParsoidSiteConfig(),
			$services->getParsoidDataAccess()
		);
	},

	'_SettingsBuilder' => static function ( MediaWikiServices $services ): SettingsBuilder {
		return SettingsBuilder::getInstance();
	},

	'_SqlBlobStore' => static function ( MediaWikiServices $services ): SqlBlobStore {
		return $services->getBlobStoreFactory()->newSqlBlobStore();
	},

	'_TracerHTTPClient' => static function (): ClientInterface {
		return new Client( [ 'http_errors' => false ] );
	},

	'_UserBlockCommandFactory' => static function ( MediaWikiServices $services ): UserBlockCommandFactory {
		return new UserBlockCommandFactory(
			new ServiceOptions( UserBlockCommandFactory::CONSTRUCTOR_OPTIONS, $services->getMainConfig() ),
			$services->getHookContainer(),
			$services->getBlockPermissionCheckerFactory(),
			$services->getBlockTargetFactory(),
			$services->getDatabaseBlockStore(),
			$services->getBlockRestrictionStore(),
			$services->getUserFactory(),
			$services->getUserEditTracker(),
			LoggerFactory::getInstance( 'BlockManager' ),
			$services->getTitleFactory(),
			$services->getBlockActionInfo()
		);
	}

	///////////////////////////////////////////////////////////////////////////
	// NOTE: When adding a service here, don't forget to add a getter function
	// in the MediaWikiServices class. The convenience getter should just call
	// $this->getService( 'FooBarService' ).
	///////////////////////////////////////////////////////////////////////////

];
