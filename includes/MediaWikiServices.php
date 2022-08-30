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
 */

namespace MediaWiki;

use ActorMigration;
use BagOStuff;
use CentralIdLookup;
use CommentStore;
use Config;
use ConfigFactory;
use ConfiguredReadOnlyMode;
use CryptHKDF;
use DateFormatterFactory;
use EventRelayerGroup;
use ExternalStoreAccess;
use ExternalStoreFactory;
use FileBackendGroup;
use GenderCache;
use GlobalVarConfig;
use HtmlCacheUpdater;
use IBufferingStatsdDataFactory;
use JobQueueGroup;
use JobRunner;
use Language;
use LinkCache;
use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use LocalisationCache;
use MagicWordFactory;
use MediaHandlerFactory;
use MediaWiki\Actions\ActionFactory;
use MediaWiki\Auth\AuthManager;
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
use MediaWiki\Cache\BacklinkCacheFactory;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Collation\CollationFactory;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\CommentFormatter\RowCommentFormatter;
use MediaWiki\Config\ConfigRepository;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Content\Renderer\ContentRenderer;
use MediaWiki\Content\Transform\ContentTransformer;
use MediaWiki\Edit\ParsoidOutputStash;
use MediaWiki\EditPage\SpamChecker;
use MediaWiki\Export\WikiExporterFactory;
use MediaWiki\FileBackend\FSFile\TempFSFileFactory;
use MediaWiki\FileBackend\LockManager\LockManagerGroupFactory;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Http\HttpRequestFactory;
use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\JobQueue\JobQueueGroupFactory;
use MediaWiki\Json\JsonCodec;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\Languages\LanguageFallback;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Linker\LinkRendererFactory;
use MediaWiki\Linker\LinksMigration;
use MediaWiki\Linker\LinkTargetLookup;
use MediaWiki\Mail\IEmailer;
use MediaWiki\Page\ContentModelChangeFactory;
use MediaWiki\Page\DeletePageFactory;
use MediaWiki\Page\MergeHistoryFactory;
use MediaWiki\Page\MovePageFactory;
use MediaWiki\Page\PageStore;
use MediaWiki\Page\PageStoreFactory;
use MediaWiki\Page\ParserOutputAccess;
use MediaWiki\Page\RedirectLookup;
use MediaWiki\Page\RedirectStore;
use MediaWiki\Page\RollbackPageFactory;
use MediaWiki\Page\UndeletePageFactory;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Parser\ParserCacheFactory;
use MediaWiki\Parser\Parsoid\Config\PageConfigFactory;
use MediaWiki\Parser\Parsoid\HTMLTransformFactory;
use MediaWiki\Parser\Parsoid\ParsoidOutputAccess;
use MediaWiki\Permissions\GrantsInfo;
use MediaWiki\Permissions\GrantsLocalization;
use MediaWiki\Permissions\GroupPermissionsLookup;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Permissions\RateLimiter;
use MediaWiki\Permissions\RestrictionStore;
use MediaWiki\Preferences\PreferencesFactory;
use MediaWiki\Preferences\SignatureValidatorFactory;
use MediaWiki\ResourceLoader\ResourceLoader;
use MediaWiki\Revision\ArchivedRevisionLookup;
use MediaWiki\Revision\ContributionsLookup;
use MediaWiki\Revision\RevisionFactory;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRenderer;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\RevisionStoreFactory;
use MediaWiki\Revision\SlotRoleRegistry;
use MediaWiki\Settings\Config\ConfigSchema;
use MediaWiki\Shell\CommandFactory;
use MediaWiki\Shell\ShellboxClientFactory;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Storage\BlobStore;
use MediaWiki\Storage\BlobStoreFactory;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Storage\NameTableStoreFactory;
use MediaWiki\Storage\PageEditStash;
use MediaWiki\Storage\PageUpdaterFactory;
use MediaWiki\Storage\RevertedTagUpdateManager;
use MediaWiki\Tidy\TidyDriverBase;
use MediaWiki\User\ActorNormalization;
use MediaWiki\User\ActorStore;
use MediaWiki\User\ActorStoreFactory;
use MediaWiki\User\BotPasswordStore;
use MediaWiki\User\CentralId\CentralIdLookupFactory;
use MediaWiki\User\TalkPageNotificationManager;
use MediaWiki\User\TempUser\RealTempUserConfig;
use MediaWiki\User\TempUser\TempUserCreator;
use MediaWiki\User\UserEditTracker;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserGroupManagerFactory;
use MediaWiki\User\UserIdentityLookup;
use MediaWiki\User\UserNamePrefixSearch;
use MediaWiki\User\UserNameUtils;
use MediaWiki\User\UserOptionsLookup;
use MediaWiki\User\UserOptionsManager;
use MediaWiki\Utils\UrlUtils;
use MediaWiki\Watchlist\WatchlistManager;
use MessageCache;
use MimeAnalyzer;
use MWException;
use MWLBFactory;
use NamespaceInfo;
use ObjectCache;
use OldRevisionImporter;
use PageProps;
use Parser;
use ParserCache;
use ParserFactory;
use PasswordFactory;
use PasswordReset;
use ProxyLookup;
use ReadOnlyMode;
use RepoGroup;
use SearchEngine;
use SearchEngineConfig;
use SearchEngineFactory;
use SiteLookup;
use SiteStore;
use SkinFactory;
use TitleFactory;
use TitleFormatter;
use TitleParser;
use TrackingCategories;
use UploadRevisionImporter;
use UserCache;
use VirtualRESTServiceClient;
use WANObjectCache;
use WatchedItemQueryService;
use WatchedItemStoreInterface;
use WikiImporterFactory;
use Wikimedia\Message\IMessageFormatterFactory;
use Wikimedia\Metrics\MetricsFactory;
use Wikimedia\NonSerializable\NonSerializableTrait;
use Wikimedia\ObjectFactory\ObjectFactory;
use Wikimedia\Parsoid\Config\DataAccess;
use Wikimedia\Parsoid\Config\SiteConfig;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\RequestTimeout\CriticalSectionProvider;
use Wikimedia\Services\NoSuchServiceException;
use Wikimedia\Services\SalvageableService;
use Wikimedia\Services\ServiceContainer;
use Wikimedia\UUID\GlobalIdGenerator;
use Wikimedia\WRStats\WRStatsFactory;

/**
 * Service locator for MediaWiki core services.
 *
 * Refer to includes/ServiceWiring.php for the default implementations.
 *
 * @see [Dependency Injection](@ref dependencyinjection) in docs/Injection.md
 * for the principles of DI and how to use it MediaWiki core.
 *
 * @since 1.27
 */
class MediaWikiServices extends ServiceContainer {
	use NonSerializableTrait;

	/**
	 * @var bool
	 */
	private static $globalInstanceAllowed = false;

	/**
	 * @var MediaWikiServices|null
	 */
	private static $instance = null;

	/**
	 * Allows a global service container instance to exist.
	 *
	 * This should be called only after configuration settings have been read and extensions
	 * have been registered. Any change made to configuration after this method has been called
	 * may be ineffective or even harmful.
	 *
	 * @see getInstance()
	 *
	 * @since 1.36
	 */
	public static function allowGlobalInstance() {
		self::$globalInstanceAllowed = true;

		if ( self::$instance ) {
			// TODO: in 1.37, getInstance() should fail if $globalInstanceAllowed is false! (T153256)
			// Until then, we have to reset service instances that have already been created.
			// No need to warn here, getService() has already triggered a deprecation warning.
			self::resetGlobalInstance( null, 'quick' );
		}
	}

	/**
	 * Returns true if an instance has already been initialized. This can be used to avoid accessing
	 * services if it's not safe, such as in unit tests or early setup.
	 *
	 * @return bool
	 */
	public static function hasInstance() {
		return self::$instance !== null;
	}

	/**
	 * Returns the global default instance of the top level service locator.
	 *
	 * @note if called before allowGlobalInstance(), this method will fail.
	 *
	 * @since 1.27
	 *
	 * The default instance is initialized using the service instantiator functions
	 * defined in ServiceWiring.php.
	 *
	 * @note This should only be called by static functions! The instance returned here
	 * should not be passed around! Objects that need access to a service should have
	 * that service injected into the constructor, never a service locator!
	 *
	 * @return MediaWikiServices
	 */
	public static function getInstance(): self {
		// TODO: in 1.37, getInstance() should fail if $globalInstanceAllowed is false! (T153256)
		if ( !self::$globalInstanceAllowed ) {
			wfDeprecatedMsg( 'Premature access to service container', '1.36' );
		}

		if ( self::$instance === null ) {
			// NOTE: constructing GlobalVarConfig here is not particularly pretty,
			// but some information from the global scope has to be injected here,
			// even if it's just a file name or database credentials to load
			// configuration from.
			$bootstrapConfig = new GlobalVarConfig();
			self::$instance = self::newInstance( $bootstrapConfig, 'load' );

			// Provides a traditional hook point to allow extensions to configure services.
			// NOTE: Ideally this would be in newInstance() but it causes an infinite run loop
			$runner = new HookRunner( self::$instance->getHookContainer() );
			$runner->onMediaWikiServices( self::$instance );
		}
		return self::$instance;
	}

	public function getService( $name ) {
		// TODO: in 1.37, getInstance() should fail if $globalInstanceAllowed is false! (T153256)
		if ( !self::$globalInstanceAllowed && $this === self::$instance ) {
			wfDeprecatedMsg( "Premature access to service '$name'", '1.36', false, 3 );
		}

		return parent::getService( $name );
	}

	/**
	 * Replaces the global MediaWikiServices instance.
	 *
	 * @since 1.28
	 *
	 * @note This is for use in PHPUnit tests only!
	 *
	 * @throws MWException if called outside of PHPUnit tests.
	 *
	 * @param MediaWikiServices $services The new MediaWikiServices object.
	 *
	 * @return MediaWikiServices The old MediaWikiServices object, so it can be restored later.
	 */
	public static function forceGlobalInstance( MediaWikiServices $services ) {
		if ( !defined( 'MW_PHPUNIT_TEST' ) && !defined( 'MW_PARSER_TEST' ) ) {
			throw new MWException( __METHOD__ . ' must not be used outside unit tests.' );
		}

		$old = self::getInstance();
		self::$instance = $services;

		return $old;
	}

	/**
	 * Creates a new instance of MediaWikiServices and sets it as the global default
	 * instance. getInstance() will return a different MediaWikiServices object
	 * after every call to resetGlobalInstance().
	 *
	 * @since 1.28
	 *
	 * @warning This should not be used during normal operation. It is intended for use
	 * when the configuration has changed significantly since bootstrap time, e.g.
	 * during the installation process or during testing.
	 *
	 * @warning Calling resetGlobalInstance() may leave the application in an inconsistent
	 * state. Calling this is only safe under the ASSUMPTION that NO REFERENCE to
	 * any of the services managed by MediaWikiServices exist. If any service objects
	 * managed by the old MediaWikiServices instance remain in use, they may INTERFERE
	 * with the operation of the services managed by the new MediaWikiServices.
	 * Operating with a mix of services created by the old and the new
	 * MediaWikiServices instance may lead to INCONSISTENCIES and even DATA LOSS!
	 * Any class implementing LAZY LOADING is especially prone to this problem,
	 * since instances would typically retain a reference to a storage layer service.
	 *
	 * @see forceGlobalInstance()
	 * @see resetGlobalInstance()
	 * @see resetBetweenTest()
	 *
	 * @param Config|null $bootstrapConfig The Config object to be registered as the
	 *        'BootstrapConfig' service. This has to contain at least the information
	 *        needed to set up the 'ConfigFactory' service. If not given, the bootstrap
	 *        config of the old instance of MediaWikiServices will be re-used. If there
	 *        was no previous instance, a new GlobalVarConfig object will be used to
	 *        bootstrap the services.
	 *
	 * @param string $quick Set this to "quick" to allow expensive resources to be re-used.
	 * See SalvageableService for details.
	 *
	 * @throws MWException If called after MW_SERVICE_BOOTSTRAP_COMPLETE has been defined in
	 *         Setup.php (unless MW_PHPUNIT_TEST or MEDIAWIKI_INSTALL or RUN_MAINTENANCE_IF_MAIN
	 *          is defined).
	 */
	public static function resetGlobalInstance( Config $bootstrapConfig = null, $quick = '' ) {
		if ( self::$instance === null ) {
			// no global instance yet, nothing to reset
			return;
		}

		self::failIfResetNotAllowed( __METHOD__ );

		if ( $bootstrapConfig === null ) {
			$bootstrapConfig = self::$instance->getBootstrapConfig();
		}

		$oldInstance = self::$instance;

		self::$instance = self::newInstance( $bootstrapConfig, 'load' );

		// Provides a traditional hook point to allow extensions to configure services.
		$runner = new HookRunner( $oldInstance->getHookContainer() );
		$runner->onMediaWikiServices( self::$instance );

		self::$instance->importWiring( $oldInstance, [ 'BootstrapConfig' ] );

		if ( $quick === 'quick' ) {
			self::$instance->salvage( $oldInstance );
		} else {
			$oldInstance->destroy();
		}
	}

	/** @noinspection PhpDocSignatureInspection */

	/**
	 * Salvages the state of any salvageable service instances in $other.
	 *
	 * @note $other will have been destroyed when salvage() returns.
	 *
	 * @param MediaWikiServices $other
	 */
	private function salvage( self $other ) {
		foreach ( $this->getServiceNames() as $name ) {
			// The service could be new in the new instance and not registered in the
			// other instance (e.g. an extension that was loaded after the instantiation of
			// the other instance. Skip this service in this case. See T143974
			try {
				$oldService = $other->peekService( $name );
			} catch ( NoSuchServiceException $e ) {
				continue;
			}

			if ( $oldService instanceof SalvageableService ) {
				/** @var SalvageableService $newService */
				$newService = $this->getService( $name );
				$newService->salvage( $oldService );
			}
		}

		$other->destroy();
	}

	/**
	 * Creates a new MediaWikiServices instance and initializes it according to the
	 * given $bootstrapConfig. In particular, all wiring files defined in the
	 * ServiceWiringFiles setting are loaded, and the MediaWikiServices hook is called.
	 *
	 * @param Config $bootstrapConfig The Config object to be registered as the
	 *        'BootstrapConfig' service.
	 *
	 * @param string $loadWiring set this to 'load' to load the wiring files specified
	 *        in the 'ServiceWiringFiles' setting in $bootstrapConfig.
	 *
	 * @return MediaWikiServices
	 * @throws MWException
	 * @throws \FatalError
	 */
	private static function newInstance( Config $bootstrapConfig, $loadWiring = '' ) {
		$instance = new self( $bootstrapConfig );

		// Load the default wiring from the specified files.
		if ( $loadWiring === 'load' ) {
			$wiringFiles = $bootstrapConfig->get( MainConfigNames::ServiceWiringFiles );
			$instance->loadWiringFiles( $wiringFiles );
		}

		return $instance;
	}

	/**
	 * Disables all storage layer services. After calling this, any attempt to access the
	 * storage layer will result in an error. Use resetGlobalInstance() to restore normal
	 * operation.
	 *
	 * @since 1.28
	 *
	 * @warning This is intended for extreme situations only and should never be used
	 * while serving normal web requests. Legitimate use cases for this method include
	 * the installation process. Test fixtures may also use this, if the fixture relies
	 * on globalState.
	 *
	 * @see resetGlobalInstance()
	 * @see resetChildProcessServices()
	 */
	public static function disableStorageBackend() {
		// TODO: also disable some Caches, JobQueues, etc
		$destroy = [ 'DBLoadBalancer', 'DBLoadBalancerFactory' ];
		$services = self::getInstance();

		foreach ( $destroy as $name ) {
			$services->disableService( $name );
		}

		ObjectCache::clear();
	}

	/**
	 * Resets any services that may have become stale after a child process
	 * returns from after pcntl_fork(). It's also safe, but generally unnecessary,
	 * to call this method from the parent process.
	 *
	 * @since 1.28
	 *
	 * @note This is intended for use in the context of process forking only!
	 *
	 * @see resetGlobalInstance()
	 * @see disableStorageBackend()
	 */
	public static function resetChildProcessServices() {
		// NOTE: for now, just reset everything. Since we don't know the interdependencies
		// between services, we can't do this more selectively at this time.
		self::resetGlobalInstance();

		// Child, reseed because there is no bug in PHP:
		// https://bugs.php.net/bug.php?id=42465
		mt_srand( getmypid() );
	}

	/**
	 * Resets the given service for testing purposes.
	 *
	 * @since 1.28
	 *
	 * @warning This is generally unsafe! Other services may still retain references
	 * to the stale service instance, leading to failures and inconsistencies. Subclasses
	 * may use this method to reset specific services under specific instances, but
	 * it should not be exposed to application logic.
	 *
	 * @note With proper dependency injection used throughout the codebase, this method
	 * should not be needed. It is provided to allow tests that pollute global service
	 * instances to clean up.
	 *
	 * @param string $name
	 * @param bool $destroy Whether the service instance should be destroyed if it exists.
	 *        When set to false, any existing service instance will effectively be detached
	 *        from the container.
	 *
	 * @throws MWException if called outside of PHPUnit tests.
	 */
	public function resetServiceForTesting( $name, $destroy = true ) {
		if ( !defined( 'MW_PHPUNIT_TEST' ) && !defined( 'MW_PARSER_TEST' ) ) {
			throw new MWException( 'resetServiceForTesting() must not be used outside unit tests.' );
		}

		$this->resetService( $name, $destroy );
	}

	/**
	 * Convenience method that throws an exception unless it is called during a phase in which
	 * resetting of global services is allowed. In general, services should not be reset
	 * individually, since that may introduce inconsistencies.
	 *
	 * @since 1.28
	 *
	 * This method will throw an exception if:
	 *
	 * - self::$resetInProgress is false (to allow all services to be reset together
	 *   via resetGlobalInstance)
	 * - and MEDIAWIKI_INSTALL is not defined (to allow services to be reset during installation)
	 * - and MW_PHPUNIT_TEST is not defined (to allow services to be reset during testing)
	 *
	 * This method is intended to be used to safeguard against accidentally resetting
	 * global service instances that are not yet managed by MediaWikiServices. It is
	 * defined here in the MediaWikiServices services class to have a central place
	 * for managing service bootstrapping and resetting.
	 *
	 * @param string $method the name of the caller method, as given by __METHOD__.
	 *
	 * @throws MWException if called outside bootstrap mode.
	 *
	 * @see resetGlobalInstance()
	 * @see forceGlobalInstance()
	 * @see disableStorageBackend()
	 */
	public static function failIfResetNotAllowed( $method ) {
		if ( !defined( 'MW_PHPUNIT_TEST' )
			&& !defined( 'MW_PARSER_TEST' )
			&& !defined( 'MEDIAWIKI_INSTALL' )
			&& !defined( 'RUN_MAINTENANCE_IF_MAIN' )
			&& defined( 'MW_SERVICE_BOOTSTRAP_COMPLETE' )
		) {
			throw new MWException( $method . ' may only be called during bootstrapping and unit tests!' );
		}
	}

	/**
	 * @param Config $config The Config object to be registered as the 'BootstrapConfig' service.
	 *        This has to contain at least the information needed to set up the 'ConfigFactory'
	 *        service.
	 */
	public function __construct( Config $config ) {
		parent::__construct();

		// Register the given Config object as the bootstrap config service.
		$this->defineService( 'BootstrapConfig', static function () use ( $config ) {
			return $config;
		} );
	}

	// CONVENIENCE GETTERS ////////////////////////////////////////////////////

	/**
	 * @since 1.37
	 * @return ActionFactory
	 */
	public function getActionFactory(): ActionFactory {
		return $this->getService( 'ActionFactory' );
	}

	/**
	 * @since 1.31
	 * @return ActorMigration
	 */
	public function getActorMigration(): ActorMigration {
		return $this->getService( 'ActorMigration' );
	}

	/**
	 * @return ActorNormalization
	 * @since 1.36
	 */
	public function getActorNormalization(): ActorNormalization {
		return $this->getService( 'ActorNormalization' );
	}

	/**
	 * @return ActorStore
	 * @since 1.36
	 */
	public function getActorStore(): ActorStore {
		return $this->getService( 'ActorStore' );
	}

	/**
	 * @since 1.36
	 * @return ActorStoreFactory
	 */
	public function getActorStoreFactory(): ActorStoreFactory {
		return $this->getService( 'ActorStoreFactory' );
	}

	/**
	 * @since 1.38
	 * @return ArchivedRevisionLookup
	 */
	public function getArchivedRevisionLookup(): ArchivedRevisionLookup {
		return $this->getService( 'ArchivedRevisionLookup' );
	}

	/**
	 * @since 1.35
	 * @return AuthManager
	 */
	public function getAuthManager(): AuthManager {
		return $this->getService( 'AuthManager' );
	}

	/**
	 * @since 1.37
	 * @return BacklinkCacheFactory
	 */
	public function getBacklinkCacheFactory(): BacklinkCacheFactory {
		return $this->getService( 'BacklinkCacheFactory' );
	}

	/**
	 * @since 1.34
	 * @return BadFileLookup
	 */
	public function getBadFileLookup(): BadFileLookup {
		return $this->getService( 'BadFileLookup' );
	}

	/**
	 * @since 1.31
	 * @return BlobStore
	 */
	public function getBlobStore(): BlobStore {
		return $this->getService( 'BlobStore' );
	}

	/**
	 * @since 1.31
	 * @return BlobStoreFactory
	 */
	public function getBlobStoreFactory(): BlobStoreFactory {
		return $this->getService( 'BlobStoreFactory' );
	}

	/**
	 * @since 1.37
	 * @return BlockActionInfo
	 */
	public function getBlockActionInfo(): BlockActionInfo {
		return $this->getService( 'BlockActionInfo' );
	}

	/**
	 * @since 1.35
	 * @return BlockErrorFormatter
	 */
	public function getBlockErrorFormatter(): BlockErrorFormatter {
		return $this->getService( 'BlockErrorFormatter' );
	}

	/**
	 * @since 1.34
	 * @return BlockManager
	 */
	public function getBlockManager(): BlockManager {
		return $this->getService( 'BlockManager' );
	}

	/**
	 * @since 1.35
	 * @return BlockPermissionCheckerFactory
	 */
	public function getBlockPermissionCheckerFactory(): BlockPermissionCheckerFactory {
		return $this->getService( 'BlockPermissionCheckerFactory' );
	}

	/**
	 * @since 1.33
	 * @return BlockRestrictionStore
	 */
	public function getBlockRestrictionStore(): BlockRestrictionStore {
		return $this->getService( 'BlockRestrictionStore' );
	}

	/**
	 * @since 1.38
	 * @return BlockRestrictionStoreFactory
	 */
	public function getBlockRestrictionStoreFactory(): BlockRestrictionStoreFactory {
		return $this->getService( 'BlockRestrictionStoreFactory' );
	}

	/**
	 * @since 1.36
	 * @return BlockUserFactory
	 */
	public function getBlockUserFactory(): BlockUserFactory {
		return $this->getService( 'BlockUserFactory' );
	}

	/**
	 * @since 1.36
	 * @return BlockUtils
	 */
	public function getBlockUtils(): BlockUtils {
		return $this->getService( 'BlockUtils' );
	}

	/**
	 * Returns the Config object containing the bootstrap configuration.
	 * Bootstrap configuration would typically include database credentials
	 * and other information that may be needed before the ConfigFactory
	 * service can be instantiated.
	 *
	 * @note This should only be used during bootstrapping, in particular
	 * when creating the MainConfig service. Application logic should
	 * use getMainConfig() to get a Config instances.
	 *
	 * @since 1.27
	 * @return Config
	 */
	public function getBootstrapConfig(): Config {
		return $this->getService( 'BootstrapConfig' );
	}

	/**
	 * @since 1.37
	 * @return BotPasswordStore
	 */
	public function getBotPasswordStore(): BotPasswordStore {
		return $this->getService( 'BotPasswordStore' );
	}

	/**
	 * @since 1.37
	 * @return CentralIdLookup
	 */
	public function getCentralIdLookup(): CentralIdLookup {
		return $this->getService( 'CentralIdLookup' );
	}

	/**
	 * @since 1.37
	 * @return CentralIdLookupFactory
	 */
	public function getCentralIdLookupFactory(): CentralIdLookupFactory {
		return $this->getService( 'CentralIdLookupFactory' );
	}

	/**
	 * @since 1.32
	 * @return NameTableStore
	 */
	public function getChangeTagDefStore(): NameTableStore {
		return $this->getService( 'ChangeTagDefStore' );
	}

	/**
	 * @since 1.37
	 * @return CollationFactory
	 */
	public function getCollationFactory(): CollationFactory {
		return $this->getService( 'CollationFactory' );
	}

	/**
	 * @return CommentFormatter
	 * @since 1.38
	 */
	public function getCommentFormatter(): CommentFormatter {
		return $this->getService( 'CommentFormatter' );
	}

	/**
	 * @since 1.31
	 * @return CommentStore
	 */
	public function getCommentStore(): CommentStore {
		return $this->getService( 'CommentStore' );
	}

	/**
	 * @since 1.27
	 * @return ConfigFactory
	 */
	public function getConfigFactory(): ConfigFactory {
		return $this->getService( 'ConfigFactory' );
	}

	/**
	 * @since 1.32
	 * @return ConfigRepository
	 */
	public function getConfigRepository(): ConfigRepository {
		return $this->getService( 'ConfigRepository' );
	}

	/**
	 * @since 1.39
	 * @return ConfigSchema
	 */
	public function getConfigSchema(): ConfigSchema {
		return $this->getService( 'ConfigSchema' );
	}

	/**
	 * @since 1.29
	 * @return ConfiguredReadOnlyMode
	 */
	public function getConfiguredReadOnlyMode(): ConfiguredReadOnlyMode {
		return $this->getService( 'ConfiguredReadOnlyMode' );
	}

	/**
	 * @since 1.35
	 * @return IContentHandlerFactory
	 */
	public function getContentHandlerFactory(): IContentHandlerFactory {
		return $this->getService( 'ContentHandlerFactory' );
	}

	/**
	 * @since 1.32
	 * @return Language
	 */
	public function getContentLanguage(): Language {
		return $this->getService( 'ContentLanguage' );
	}

	/**
	 * @since 1.35
	 * @return ContentModelChangeFactory
	 */
	public function getContentModelChangeFactory(): ContentModelChangeFactory {
		return $this->getService( 'ContentModelChangeFactory' );
	}

	/**
	 * @since 1.31
	 * @return NameTableStore
	 */
	public function getContentModelStore(): NameTableStore {
		return $this->getService( 'ContentModelStore' );
	}

	/**
	 * @since 1.38
	 * @return ContentRenderer
	 */
	public function getContentRenderer(): ContentRenderer {
		return $this->getService( 'ContentRenderer' );
	}

	/**
	 * @since 1.37
	 * @return ContentTransformer
	 */
	public function getContentTransformer(): ContentTransformer {
		return $this->getService( 'ContentTransformer' );
	}

	/**
	 * @since 1.35
	 * @return ContributionsLookup
	 */
	public function getContributionsLookup(): ContributionsLookup {
		return $this->getService( 'ContributionsLookup' );
	}

	/**
	 * @since 1.36
	 * @return CriticalSectionProvider
	 */
	public function getCriticalSectionProvider(): CriticalSectionProvider {
		return $this->getService( 'CriticalSectionProvider' );
	}

	/**
	 * @since 1.28
	 * @return CryptHKDF
	 */
	public function getCryptHKDF(): CryptHKDF {
		return $this->getService( 'CryptHKDF' );
	}

	/**
	 * @since 1.36
	 * @return DatabaseBlockStore
	 */
	public function getDatabaseBlockStore(): DatabaseBlockStore {
		return $this->getService( 'DatabaseBlockStore' );
	}

	/**
	 * @since 1.33
	 * @return DateFormatterFactory
	 */
	public function getDateFormatterFactory(): DateFormatterFactory {
		return $this->getService( 'DateFormatterFactory' );
	}

	/**
	 * @since 1.28
	 * @return ILoadBalancer The main DB load balancer for the local wiki.
	 */
	public function getDBLoadBalancer(): ILoadBalancer {
		return $this->getService( 'DBLoadBalancer' );
	}

	/**
	 * @since 1.28
	 * @return LBFactory
	 */
	public function getDBLoadBalancerFactory(): LBFactory {
		return $this->getService( 'DBLoadBalancerFactory' );
	}

	/**
	 * @since 1.39
	 * @return MWLBFactory
	 */
	public function getDBLoadBalancerFactoryConfigBuilder(): MWLBFactory {
		return $this->getService( 'DBLoadBalancerFactoryConfigBuilder' );
	}

	/**
	 * @since 1.37
	 * @return DeletePageFactory
	 */
	public function getDeletePageFactory(): DeletePageFactory {
		return $this->getService( 'DeletePageFactory' );
	}

	/**
	 * @since 1.35
	 * @return IEmailer
	 */
	public function getEmailer(): IEmailer {
		return $this->getService( 'Emailer' );
	}

	/**
	 * @since 1.27
	 * @return EventRelayerGroup
	 */
	public function getEventRelayerGroup(): EventRelayerGroup {
		return $this->getService( 'EventRelayerGroup' );
	}

	/**
	 * @since 1.34
	 * @return ExternalStoreAccess
	 */
	public function getExternalStoreAccess(): ExternalStoreAccess {
		return $this->getService( 'ExternalStoreAccess' );
	}

	/**
	 * @since 1.31
	 * @return ExternalStoreFactory
	 */
	public function getExternalStoreFactory(): ExternalStoreFactory {
		return $this->getService( 'ExternalStoreFactory' );
	}

	/**
	 * @since 1.35
	 * @return FileBackendGroup
	 */
	public function getFileBackendGroup(): FileBackendGroup {
		return $this->getService( 'FileBackendGroup' );
	}

	/**
	 * @since 1.28
	 * @return GenderCache
	 */
	public function getGenderCache(): GenderCache {
		return $this->getService( 'GenderCache' );
	}

	/**
	 * @since 1.35
	 * @return GlobalIdGenerator
	 */
	public function getGlobalIdGenerator(): GlobalIdGenerator {
		return $this->getService( 'GlobalIdGenerator' );
	}

	/**
	 * @since 1.38
	 * @return GrantsInfo
	 */
	public function getGrantsInfo(): GrantsInfo {
		return $this->getService( 'GrantsInfo' );
	}

	/**
	 * @since 1.38
	 * @return GrantsLocalization
	 */
	public function getGrantsLocalization(): GrantsLocalization {
		return $this->getService( 'GrantsLocalization' );
	}

	/**
	 * @since 1.36
	 * @return GroupPermissionsLookup
	 */
	public function getGroupPermissionsLookup(): GroupPermissionsLookup {
		return $this->getService( 'GroupPermissionsLookup' );
	}

	/**
	 * @since 1.35
	 * @return HookContainer
	 */
	public function getHookContainer(): HookContainer {
		return $this->getService( 'HookContainer' );
	}

	/**
	 * @since 1.35
	 * @return HtmlCacheUpdater
	 */
	public function getHtmlCacheUpdater(): HtmlCacheUpdater {
		return $this->getService( 'HtmlCacheUpdater' );
	}

	/**
	 * @return HTMLTransformFactory
	 * @since 1.39
	 */
	public function getHTMLTransformFactory(): HTMLTransformFactory {
		return $this->getService( 'HTMLTransformFactory' );
	}

	/**
	 * @since 1.31
	 * @return HttpRequestFactory
	 */
	public function getHttpRequestFactory(): HttpRequestFactory {
		return $this->getService( 'HttpRequestFactory' );
	}

	/**
	 * @since 1.28
	 * @return InterwikiLookup
	 */
	public function getInterwikiLookup(): InterwikiLookup {
		return $this->getService( 'InterwikiLookup' );
	}

	/**
	 * @since 1.37
	 * @return JobQueueGroup
	 */
	public function getJobQueueGroup(): JobQueueGroup {
		return $this->getService( 'JobQueueGroup' );
	}

	/**
	 * @since 1.37
	 * @return JobQueueGroupFactory
	 */
	public function getJobQueueGroupFactory(): JobQueueGroupFactory {
		return $this->getService( 'JobQueueGroupFactory' );
	}

	/**
	 * @since 1.35
	 * @return JobRunner
	 */
	public function getJobRunner(): JobRunner {
		return $this->getService( 'JobRunner' );
	}

	/**
	 * @since 1.36
	 * @return JsonCodec
	 */
	public function getJsonCodec(): JsonCodec {
		return $this->getService( 'JsonCodec' );
	}

	/**
	 * @since 1.35
	 * @return LanguageConverterFactory
	 */
	public function getLanguageConverterFactory(): LanguageConverterFactory {
		return $this->getService( 'LanguageConverterFactory' );
	}

	/**
	 * @since 1.35
	 * @return LanguageFactory
	 */
	public function getLanguageFactory(): LanguageFactory {
		return $this->getService( 'LanguageFactory' );
	}

	/**
	 * @since 1.35
	 * @return LanguageFallback
	 */
	public function getLanguageFallback(): LanguageFallback {
		return $this->getService( 'LanguageFallback' );
	}

	/**
	 * @since 1.34
	 * @return LanguageNameUtils
	 */
	public function getLanguageNameUtils(): LanguageNameUtils {
		return $this->getService( 'LanguageNameUtils' );
	}

	/**
	 * @since 1.35
	 * @return LinkBatchFactory
	 */
	public function getLinkBatchFactory(): LinkBatchFactory {
		return $this->getService( 'LinkBatchFactory' );
	}

	/**
	 * @since 1.28
	 * @return LinkCache
	 */
	public function getLinkCache(): LinkCache {
		return $this->getService( 'LinkCache' );
	}

	/**
	 * LinkRenderer instance that can be used
	 * if no custom options are needed
	 *
	 * @since 1.28
	 * @return LinkRenderer
	 */
	public function getLinkRenderer(): LinkRenderer {
		return $this->getService( 'LinkRenderer' );
	}

	/**
	 * @since 1.28
	 * @return LinkRendererFactory
	 */
	public function getLinkRendererFactory(): LinkRendererFactory {
		return $this->getService( 'LinkRendererFactory' );
	}

	/**
	 * @since 1.39
	 * @return LinksMigration
	 */
	public function getLinksMigration(): LinksMigration {
		return $this->getService( 'LinksMigration' );
	}

	/**
	 * @since 1.38
	 * @return LinkTargetLookup
	 */
	public function getLinkTargetLookup(): LinkTargetLookup {
		return $this->getService( 'LinkTargetLookup' );
	}

	/**
	 * @since 1.34
	 * @return LocalisationCache
	 */
	public function getLocalisationCache(): LocalisationCache {
		return $this->getService( 'LocalisationCache' );
	}

	/**
	 * Returns the main server-local cache, yielding EmptyBagOStuff if there is none
	 *
	 * In web request mode, the cache should at least be shared among web workers.
	 * In CLI mode, the cache should at least be shared among processes run by the same user.
	 *
	 * @since 1.28
	 * @return BagOStuff
	 */
	public function getLocalServerObjectCache(): BagOStuff {
		return $this->getService( 'LocalServerObjectCache' );
	}

	/**
	 * @since 1.34
	 * @return LockManagerGroupFactory
	 */
	public function getLockManagerGroupFactory(): LockManagerGroupFactory {
		return $this->getService( 'LockManagerGroupFactory' );
	}

	/**
	 * @since 1.32
	 * @return MagicWordFactory
	 */
	public function getMagicWordFactory(): MagicWordFactory {
		return $this->getService( 'MagicWordFactory' );
	}

	/**
	 * Returns the Config object that provides configuration for MediaWiki core.
	 * This may or may not be the same object that is returned by getBootstrapConfig().
	 *
	 * @since 1.27
	 * @return Config
	 */
	public function getMainConfig(): Config {
		return $this->getService( 'MainConfig' );
	}

	/**
	 * Returns the main object stash, yielding EmptyBagOStuff if there is none
	 *
	 * The stash should be shared among all datacenters
	 *
	 * @since 1.28
	 * @return BagOStuff
	 */
	public function getMainObjectStash(): BagOStuff {
		return $this->getService( 'MainObjectStash' );
	}

	/**
	 * Returns the main WAN cache, yielding EmptyBagOStuff if there is none
	 *
	 * The cache should relay any purge operations to all datacenters
	 *
	 * @since 1.28
	 * @return WANObjectCache
	 */
	public function getMainWANObjectCache(): WANObjectCache {
		return $this->getService( 'MainWANObjectCache' );
	}

	/**
	 * @since 1.28
	 * @return MediaHandlerFactory
	 */
	public function getMediaHandlerFactory(): MediaHandlerFactory {
		return $this->getService( 'MediaHandlerFactory' );
	}

	/**
	 * @since 1.35
	 * @return MergeHistoryFactory
	 */
	public function getMergeHistoryFactory(): MergeHistoryFactory {
		return $this->getService( 'MergeHistoryFactory' );
	}

	/**
	 * @since 1.34
	 * @return MessageCache
	 */
	public function getMessageCache(): MessageCache {
		return $this->getService( 'MessageCache' );
	}

	/**
	 * @since 1.34
	 * @return IMessageFormatterFactory
	 */
	public function getMessageFormatterFactory(): IMessageFormatterFactory {
		return $this->getService( 'MessageFormatterFactory' );
	}

	/**
	 * @since 1.38
	 * @return MetricsFactory
	 */
	public function getMetricsFactory(): MetricsFactory {
		return $this->getService( 'MetricsFactory' );
	}

	/**
	 * @since 1.28
	 * @return MimeAnalyzer
	 */
	public function getMimeAnalyzer(): MimeAnalyzer {
		return $this->getService( 'MimeAnalyzer' );
	}

	/**
	 * @since 1.34
	 * @return MovePageFactory
	 */
	public function getMovePageFactory(): MovePageFactory {
		return $this->getService( 'MovePageFactory' );
	}

	/**
	 * @since 1.34
	 * @return NamespaceInfo
	 */
	public function getNamespaceInfo(): NamespaceInfo {
		return $this->getService( 'NamespaceInfo' );
	}

	/**
	 * @since 1.32
	 * @return NameTableStoreFactory
	 */
	public function getNameTableStoreFactory(): NameTableStoreFactory {
		return $this->getService( 'NameTableStoreFactory' );
	}

	/**
	 * ObjectFactory is intended for instantiating "handlers" from declarative definitions,
	 * such as Action API modules, special pages, or REST API handlers.
	 *
	 * @since 1.34
	 * @return ObjectFactory
	 */
	public function getObjectFactory(): ObjectFactory {
		return $this->getService( 'ObjectFactory' );
	}

	/**
	 * @since 1.32
	 * @return OldRevisionImporter
	 */
	public function getOldRevisionImporter(): OldRevisionImporter {
		return $this->getService( 'OldRevisionImporter' );
	}

	/**
	 * @return PageEditStash
	 * @since 1.34
	 */
	public function getPageEditStash(): PageEditStash {
		return $this->getService( 'PageEditStash' );
	}

	/**
	 * @return PageProps
	 * @since 1.36
	 */
	public function getPageProps(): PageProps {
		return $this->getService( 'PageProps' );
	}

	/**
	 * @return PageStore
	 * @since 1.36
	 */
	public function getPageStore(): PageStore {
		return $this->getService( 'PageStore' );
	}

	/**
	 * @return PageStoreFactory
	 * @since 1.36
	 */
	public function getPageStoreFactory(): PageStoreFactory {
		return $this->getService( 'PageStoreFactory' );
	}

	/**
	 * @since 1.37
	 * @return PageUpdaterFactory
	 */
	public function getPageUpdaterFactory(): PageUpdaterFactory {
		return $this->getService( 'PageUpdaterFactory' );
	}

	/**
	 * Get the main Parser instance. This is unsafe when the caller is not in
	 * a top-level context, because re-entering the parser will throw an
	 * exception.
	 *
	 * @since 1.29
	 * @return Parser
	 */
	public function getParser(): Parser {
		return $this->getService( 'Parser' );
	}

	/**
	 * @since 1.30
	 * @return ParserCache
	 */
	public function getParserCache(): ParserCache {
		return $this->getService( 'ParserCache' );
	}

	/**
	 * @since 1.36
	 * @return ParserCacheFactory
	 */
	public function getParserCacheFactory(): ParserCacheFactory {
		return $this->getService( 'ParserCacheFactory' );
	}

	/**
	 * @since 1.32
	 * @return ParserFactory
	 */
	public function getParserFactory(): ParserFactory {
		return $this->getService( 'ParserFactory' );
	}

	/**
	 * @return ParserOutputAccess
	 * @since 1.36
	 */
	public function getParserOutputAccess(): ParserOutputAccess {
		return $this->getService( 'ParserOutputAccess' );
	}

	/**
	 * @return DataAccess
	 * @since 1.39
	 */
	public function getParsoidDataAccess(): DataAccess {
		return $this->getService( 'ParsoidDataAccess' );
	}

	/**
	 * @return ParsoidOutputAccess
	 * @since 1.39
	 * @unstable
	 */
	public function getParsoidOutputAccess(): ParsoidOutputAccess {
		return $this->getService( 'ParsoidOutputAccess' );
	}

	/**
	 * @return ParsoidOutputStash
	 * @since 1.39
	 * @unstable since 1.39, should be stable before release of 1.39
	 */
	public function getParsoidOutputStash(): ParsoidOutputStash {
		return $this->getService( 'ParsoidOutputStash' );
	}

	/**
	 * @return PageConfigFactory
	 * @since 1.39
	 */
	public function getParsoidPageConfigFactory(): PageConfigFactory {
		return $this->getService( 'ParsoidPageConfigFactory' );
	}

	/**
	 * @return SiteConfig
	 * @since 1.39
	 */
	public function getParsoidSiteConfig(): SiteConfig {
		return $this->getService( 'ParsoidSiteConfig' );
	}

	/**
	 * @since 1.32
	 * @return PasswordFactory
	 */
	public function getPasswordFactory(): PasswordFactory {
		return $this->getService( 'PasswordFactory' );
	}

	/**
	 * @since 1.34
	 * @return PasswordReset
	 */
	public function getPasswordReset(): PasswordReset {
		return $this->getService( 'PasswordReset' );
	}

	/**
	 * @since 1.32
	 * @return StatsdDataFactoryInterface
	 */
	public function getPerDbNameStatsdDataFactory(): StatsdDataFactoryInterface {
		return $this->getService( 'PerDbNameStatsdDataFactory' );
	}

	/**
	 * @since 1.33
	 * @return PermissionManager
	 */
	public function getPermissionManager(): PermissionManager {
		return $this->getService( 'PermissionManager' );
	}

	/**
	 * @since 1.31
	 * @return PreferencesFactory
	 */
	public function getPreferencesFactory(): PreferencesFactory {
		return $this->getService( 'PreferencesFactory' );
	}

	/**
	 * @since 1.28
	 * @return ProxyLookup
	 */
	public function getProxyLookup(): ProxyLookup {
		return $this->getService( 'ProxyLookup' );
	}

	/**
	 * @since 1.39
	 * @return RateLimiter
	 */
	public function getRateLimiter(): RateLimiter {
		return $this->getService( 'RateLimiter' );
	}

	/**
	 * @since 1.29
	 * @return ReadOnlyMode
	 */
	public function getReadOnlyMode(): ReadOnlyMode {
		return $this->getService( 'ReadOnlyMode' );
	}

	/**
	 * @since 1.38
	 * @return RedirectLookup
	 */
	public function getRedirectLookup(): RedirectLookup {
		return $this->getService( 'RedirectLookup' );
	}

	/**
	 * @since 1.38
	 * @return RedirectStore
	 */
	public function getRedirectStore(): RedirectStore {
		return $this->getService( 'RedirectStore' );
	}

	/**
	 * @since 1.34
	 * @return RepoGroup
	 */
	public function getRepoGroup(): RepoGroup {
		return $this->getService( 'RepoGroup' );
	}

	/**
	 * @since 1.33
	 * @return ResourceLoader
	 */
	public function getResourceLoader(): ResourceLoader {
		return $this->getService( 'ResourceLoader' );
	}

	/**
	 * @since 1.37
	 * @return RestrictionStore
	 */
	public function getRestrictionStore(): RestrictionStore {
		return $this->getService( 'RestrictionStore' );
	}

	/**
	 * @since 1.36
	 * @return RevertedTagUpdateManager
	 */
	public function getRevertedTagUpdateManager(): RevertedTagUpdateManager {
		return $this->getService( 'RevertedTagUpdateManager' );
	}

	/**
	 * @since 1.31
	 * @return RevisionFactory
	 */
	public function getRevisionFactory(): RevisionFactory {
		return $this->getService( 'RevisionFactory' );
	}

	/**
	 * @since 1.31
	 * @return RevisionLookup
	 */
	public function getRevisionLookup(): RevisionLookup {
		return $this->getService( 'RevisionLookup' );
	}

	/**
	 * @since 1.32
	 * @return RevisionRenderer
	 */
	public function getRevisionRenderer(): RevisionRenderer {
		return $this->getService( 'RevisionRenderer' );
	}

	/**
	 * @since 1.31
	 * @return RevisionStore
	 */
	public function getRevisionStore(): RevisionStore {
		return $this->getService( 'RevisionStore' );
	}

	/**
	 * @since 1.32
	 * @return RevisionStoreFactory
	 */
	public function getRevisionStoreFactory(): RevisionStoreFactory {
		return $this->getService( 'RevisionStoreFactory' );
	}

	/**
	 * @since 1.37
	 * @return RollbackPageFactory
	 */
	public function getRollbackPageFactory(): RollbackPageFactory {
		return $this->getService( 'RollbackPageFactory' );
	}

	/**
	 * @since 1.38
	 * @return RowCommentFormatter
	 */
	public function getRowCommentFormatter(): RowCommentFormatter {
		return $this->getService( 'RowCommentFormatter' );
	}

	/**
	 * @since 1.27
	 * @return SearchEngine
	 */
	public function newSearchEngine(): SearchEngine {
		// New engine object every time, since they keep state
		return $this->getService( 'SearchEngineFactory' )->create();
	}

	/**
	 * @since 1.27
	 * @return SearchEngineConfig
	 */
	public function getSearchEngineConfig(): SearchEngineConfig {
		return $this->getService( 'SearchEngineConfig' );
	}

	/**
	 * @since 1.27
	 * @return SearchEngineFactory
	 */
	public function getSearchEngineFactory(): SearchEngineFactory {
		return $this->getService( 'SearchEngineFactory' );
	}

	/**
	 * @since 1.36
	 * @return ShellboxClientFactory
	 */
	public function getShellboxClientFactory(): ShellboxClientFactory {
		return $this->getService( 'ShellboxClientFactory' );
	}

	/**
	 * @since 1.30
	 * @return CommandFactory
	 */
	public function getShellCommandFactory(): CommandFactory {
		return $this->getService( 'ShellCommandFactory' );
	}

	/**
	 * @since 1.38
	 * @return SignatureValidatorFactory
	 */
	public function getSignatureValidatorFactory(): SignatureValidatorFactory {
		return $this->getService( 'SignatureValidatorFactory' );
	}

	/**
	 * @since 1.27
	 * @return SiteLookup
	 */
	public function getSiteLookup(): SiteLookup {
		return $this->getService( 'SiteLookup' );
	}

	/**
	 * @since 1.27
	 * @return SiteStore
	 */
	public function getSiteStore(): SiteStore {
		return $this->getService( 'SiteStore' );
	}

	/**
	 * @since 1.27
	 * @return SkinFactory
	 */
	public function getSkinFactory(): SkinFactory {
		return $this->getService( 'SkinFactory' );
	}

	/**
	 * @since 1.33
	 * @return SlotRoleRegistry
	 */
	public function getSlotRoleRegistry(): SlotRoleRegistry {
		return $this->getService( 'SlotRoleRegistry' );
	}

	/**
	 * @since 1.31
	 * @return NameTableStore
	 */
	public function getSlotRoleStore(): NameTableStore {
		return $this->getService( 'SlotRoleStore' );
	}

	/**
	 * @since 1.35
	 * @return SpamChecker
	 */
	public function getSpamChecker(): SpamChecker {
		return $this->getService( 'SpamChecker' );
	}

	/**
	 * @since 1.32
	 * @return SpecialPageFactory
	 */
	public function getSpecialPageFactory(): SpecialPageFactory {
		return $this->getService( 'SpecialPageFactory' );
	}

	/**
	 * @since 1.27
	 * @return IBufferingStatsdDataFactory
	 */
	public function getStatsdDataFactory(): IBufferingStatsdDataFactory {
		return $this->getService( 'StatsdDataFactory' );
	}

	/**
	 * @since 1.35
	 * @return TalkPageNotificationManager
	 */
	public function getTalkPageNotificationManager(): TalkPageNotificationManager {
		return $this->getService( 'TalkPageNotificationManager' );
	}

	/**
	 * @since 1.34
	 * @return TempFSFileFactory
	 */
	public function getTempFSFileFactory(): TempFSFileFactory {
		return $this->getService( 'TempFSFileFactory' );
	}

	/**
	 * @since 1.39
	 * @return RealTempUserConfig
	 */
	public function getTempUserConfig(): RealTempUserConfig {
		return $this->getService( 'TempUserConfig' );
	}

	/**
	 * @since 1.39
	 * @return TempUserCreator
	 */
	public function getTempUserCreator(): TempUserCreator {
		return $this->getService( 'TempUserCreator' );
	}

	/**
	 * @since 1.36
	 * @return TidyDriverBase
	 */
	public function getTidy(): TidyDriverBase {
		return $this->getService( 'Tidy' );
	}

	/**
	 * @since 1.35
	 * @return TitleFactory
	 */
	public function getTitleFactory(): TitleFactory {
		return $this->getService( 'TitleFactory' );
	}

	/**
	 * @since 1.28
	 * @return TitleFormatter
	 */
	public function getTitleFormatter(): TitleFormatter {
		return $this->getService( 'TitleFormatter' );
	}

	/**
	 * @since 1.28
	 * @return TitleParser
	 */
	public function getTitleParser(): TitleParser {
		return $this->getService( 'TitleParser' );
	}

	/**
	 * @since 1.38
	 * @return TrackingCategories
	 */
	public function getTrackingCategories(): TrackingCategories {
		return $this->getService( 'TrackingCategories' );
	}

	/**
	 * @since 1.36
	 * @return UnblockUserFactory
	 */
	public function getUnblockUserFactory(): UnblockUserFactory {
		return $this->getService( 'UnblockUserFactory' );
	}

	/**
	 * @since 1.38
	 * @return UndeletePageFactory
	 */
	public function getUndeletePageFactory(): UndeletePageFactory {
		return $this->getService( 'UndeletePageFactory' );
	}

	/**
	 * @since 1.32
	 * @return UploadRevisionImporter
	 */
	public function getUploadRevisionImporter(): UploadRevisionImporter {
		return $this->getService( 'UploadRevisionImporter' );
	}

	/**
	 * @since 1.39
	 * @return UrlUtils
	 */
	public function getUrlUtils(): UrlUtils {
		return $this->getService( 'UrlUtils' );
	}

	/**
	 * @since 1.36
	 * @return UserCache
	 */
	public function getUserCache(): UserCache {
		return $this->getService( 'UserCache' );
	}

	/**
	 * @since 1.35
	 * @return UserEditTracker
	 */
	public function getUserEditTracker(): UserEditTracker {
		return $this->getService( 'UserEditTracker' );
	}

	/**
	 * @since 1.35
	 * @return UserFactory
	 */
	public function getUserFactory(): UserFactory {
		return $this->getService( 'UserFactory' );
	}

	/**
	 * @since 1.35
	 * @return UserGroupManager
	 */
	public function getUserGroupManager(): UserGroupManager {
		return $this->getService( 'UserGroupManager' );
	}

	/**
	 * @since 1.35
	 * @return UserGroupManagerFactory
	 */
	public function getUserGroupManagerFactory(): UserGroupManagerFactory {
		return $this->getService( 'UserGroupManagerFactory' );
	}

	/**
	 * @since 1.36
	 * @return UserIdentityLookup
	 */
	public function getUserIdentityLookup(): UserIdentityLookup {
		return $this->getService( 'UserIdentityLookup' );
	}

	/**
	 * @since 1.36
	 * @return UserNamePrefixSearch
	 */
	public function getUserNamePrefixSearch(): UserNamePrefixSearch {
		return $this->getService( 'UserNamePrefixSearch' );
	}

	/**
	 * @since 1.35
	 * @return UserNameUtils
	 */
	public function getUserNameUtils(): UserNameUtils {
		return $this->getService( 'UserNameUtils' );
	}

	/**
	 * @since 1.35
	 * @return UserOptionsLookup
	 */
	public function getUserOptionsLookup(): UserOptionsLookup {
		return $this->getService( 'UserOptionsLookup' );
	}

	/**
	 * @since 1.35
	 * @return UserOptionsManager
	 */
	public function getUserOptionsManager(): UserOptionsManager {
		return $this->getService( 'UserOptionsManager' );
	}

	/**
	 * @since 1.28
	 * @return VirtualRESTServiceClient
	 */
	public function getVirtualRESTServiceClient(): VirtualRESTServiceClient {
		return $this->getService( 'VirtualRESTServiceClient' );
	}

	/**
	 * @since 1.28
	 * @return WatchedItemQueryService
	 */
	public function getWatchedItemQueryService(): WatchedItemQueryService {
		return $this->getService( 'WatchedItemQueryService' );
	}

	/**
	 * @since 1.28
	 * @return WatchedItemStoreInterface
	 */
	public function getWatchedItemStore(): WatchedItemStoreInterface {
		return $this->getService( 'WatchedItemStore' );
	}

	/**
	 * @since 1.35
	 * @return WatchlistManager
	 * @deprecated since 1.36 use getWatchlistManager() instead
	 */
	public function getWatchlistNotificationManager(): WatchlistManager {
		wfDeprecated( __METHOD__, '1.36' );
		return $this->getWatchlistManager();
	}

	/**
	 * @since 1.36
	 * @return WatchlistManager
	 */
	public function getWatchlistManager(): WatchlistManager {
		return $this->getService( 'WatchlistManager' );
	}

	/**
	 * @since 1.38
	 * @return WikiExporterFactory
	 */
	public function getWikiExporterFactory(): WikiExporterFactory {
		return $this->getService( 'WikiExporterFactory' );
	}

	/**
	 * @since 1.37
	 * @return WikiImporterFactory
	 */
	public function getWikiImporterFactory(): WikiImporterFactory {
		return $this->getService( 'WikiImporterFactory' );
	}

	/**
	 * @since 1.36
	 * @return WikiPageFactory
	 */
	public function getWikiPageFactory(): WikiPageFactory {
		return $this->getService( 'WikiPageFactory' );
	}

	/**
	 * @since 1.31
	 * @return OldRevisionImporter
	 */
	public function getWikiRevisionOldRevisionImporter(): OldRevisionImporter {
		return $this->getService( 'OldRevisionImporter' );
	}

	/**
	 * @since 1.31
	 * @return OldRevisionImporter
	 */
	public function getWikiRevisionOldRevisionImporterNoUpdates(): OldRevisionImporter {
		return $this->getService( 'WikiRevisionOldRevisionImporterNoUpdates' );
	}

	/**
	 * @since 1.31
	 * @return UploadRevisionImporter
	 */
	public function getWikiRevisionUploadImporter(): UploadRevisionImporter {
		return $this->getService( 'UploadRevisionImporter' );
	}

	/**
	 * @since 1.39
	 * @return WRStatsFactory
	 */
	public function getWRStatsFactory(): WRStatsFactory {
		return $this->getService( 'WRStatsFactory' );
	}

}
