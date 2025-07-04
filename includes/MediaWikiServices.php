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

use ExternalStoreAccess;
use ExternalStoreFactory;
use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use LocalisationCache;
use LogicException;
use MediaHandlerFactory;
use MediaWiki\Actions\ActionFactory;
use MediaWiki\Auth\AuthManager;
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
use MediaWiki\Block\DatabaseBlockStore;
use MediaWiki\Block\DatabaseBlockStoreFactory;
use MediaWiki\Block\HideUserUtils;
use MediaWiki\Block\UnblockUserFactory;
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
use MediaWiki\Config\ConfigFactory;
use MediaWiki\Config\ConfigRepository;
use MediaWiki\Config\GlobalVarConfig;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Content\Renderer\ContentRenderer;
use MediaWiki\Content\Transform\ContentTransformer;
use MediaWiki\DomainEvent\DomainEventDispatcher;
use MediaWiki\DomainEvent\DomainEventSource;
use MediaWiki\Edit\ParsoidOutputStash;
use MediaWiki\EditPage\IntroMessageBuilder;
use MediaWiki\EditPage\PreloadedContentBuilder;
use MediaWiki\EditPage\SpamChecker;
use MediaWiki\Export\WikiExporterFactory;
use MediaWiki\FileBackend\FileBackendGroup;
use MediaWiki\FileBackend\LockManager\LockManagerGroupFactory;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Http\HttpRequestFactory;
use MediaWiki\Installer\Pingback;
use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\Interwiki\NullInterwikiLookup;
use MediaWiki\JobQueue\JobFactory;
use MediaWiki\JobQueue\JobQueueGroup;
use MediaWiki\JobQueue\JobQueueGroupFactory;
use MediaWiki\JobQueue\JobRunner;
use MediaWiki\Json\JsonCodec;
use MediaWiki\Language\FormatterFactory;
use MediaWiki\Language\Language;
use MediaWiki\Language\LanguageCode;
use MediaWiki\Language\MessageParser;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\Languages\LanguageFallback;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Linker\LinkRendererFactory;
use MediaWiki\Linker\LinksMigration;
use MediaWiki\Linker\LinkTargetLookup;
use MediaWiki\Linker\UserLinkRenderer;
use MediaWiki\Logging\LogFormatterFactory;
use MediaWiki\Mail\EmailUserFactory;
use MediaWiki\Mail\IEmailer;
use MediaWiki\Notification\NotificationService;
use MediaWiki\OutputTransform\OutputTransformPipeline;
use MediaWiki\Page\ContentModelChangeFactory;
use MediaWiki\Page\DeletePageFactory;
use MediaWiki\Page\File\BadFileLookup;
use MediaWiki\Page\MergeHistoryFactory;
use MediaWiki\Page\MovePageFactory;
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
use MediaWiki\Parser\Parsoid\Config\DataAccess;
use MediaWiki\Parser\Parsoid\Config\PageConfigFactory;
use MediaWiki\Parser\Parsoid\Config\SiteConfig;
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
use MediaWiki\Preferences\PreferencesFactory;
use MediaWiki\Preferences\SignatureValidatorFactory;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\RenameUser\RenameUserFactory;
use MediaWiki\Request\ProxyLookup;
use MediaWiki\ResourceLoader\ResourceLoader;
use MediaWiki\Rest\Handler\Helper\PageRestHelperFactory;
use MediaWiki\Revision\ArchivedRevisionLookup;
use MediaWiki\Revision\RevisionFactory;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRenderer;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\RevisionStoreFactory;
use MediaWiki\Revision\SlotRoleRegistry;
use MediaWiki\Search\SearchResultThumbnailProvider;
use MediaWiki\Search\TitleMatcher;
use MediaWiki\Session\SessionManager;
use MediaWiki\Settings\Config\ConfigSchema;
use MediaWiki\Shell\CommandFactory;
use MediaWiki\Shell\ShellboxClientFactory;
use MediaWiki\Site\SiteLookup;
use MediaWiki\Site\SiteStore;
use MediaWiki\Skin\SkinFactory;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Storage\BlobStore;
use MediaWiki\Storage\BlobStoreFactory;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Storage\NameTableStoreFactory;
use MediaWiki\Storage\PageEditStash;
use MediaWiki\Storage\PageUpdaterFactory;
use MediaWiki\Storage\RevertedTagUpdateManager;
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
use MediaWiki\User\Options\StaticUserOptionsLookup;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\Options\UserOptionsManager;
use MediaWiki\User\PasswordReset;
use MediaWiki\User\Registration\UserRegistrationLookup;
use MediaWiki\User\TalkPageNotificationManager;
use MediaWiki\User\TempUser\RealTempUserConfig;
use MediaWiki\User\TempUser\TempUserCreator;
use MediaWiki\User\TempUser\TempUserDetailsLookup;
use MediaWiki\User\UserEditTracker;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserGroupManagerFactory;
use MediaWiki\User\UserIdentityLookup;
use MediaWiki\User\UserIdentityUtils;
use MediaWiki\User\UserNamePrefixSearch;
use MediaWiki\User\UserNameUtils;
use MediaWiki\Utils\UrlUtils;
use MediaWiki\Watchlist\WatchedItemQueryService;
use MediaWiki\Watchlist\WatchedItemStoreInterface;
use MediaWiki\Watchlist\WatchlistManager;
use MessageCache;
use MWLBFactory;
use ObjectCacheFactory;
use OldRevisionImporter;
use SearchEngine;
use SearchEngineConfig;
use SearchEngineFactory;
use UploadRevisionImporter;
use WikiImporterFactory;
use Wikimedia\EventRelayer\EventRelayerGroup;
use Wikimedia\FileBackend\FSFile\TempFSFileFactory;
use Wikimedia\Message\IMessageFormatterFactory;
use Wikimedia\Mime\MimeAnalyzer;
use Wikimedia\NonSerializable\NonSerializableTrait;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\ObjectFactory\ObjectFactory;
use Wikimedia\Rdbms\ChronologyProtector;
use Wikimedia\Rdbms\ConfiguredReadOnlyMode;
use Wikimedia\Rdbms\DatabaseFactory;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\LBFactorySingle;
use Wikimedia\Rdbms\LoadBalancerDisabled;
use Wikimedia\Rdbms\ReadOnlyMode;
use Wikimedia\RequestTimeout\CriticalSectionProvider;
use Wikimedia\Services\NoSuchServiceException;
use Wikimedia\Services\SalvageableService;
use Wikimedia\Services\ServiceContainer;
use Wikimedia\Stats\IBufferingStatsdDataFactory;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\Telemetry\TracerInterface;
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
	 * @var self|null
	 */
	private static $instance = null;

	/**
	 * @see disableStorage()
	 * @var bool
	 */
	private bool $storageDisabled = false;

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
	 * @internal Should only be used in MediaWikiUnitTestCase
	 */
	public static function disallowGlobalInstanceInUnitTests(): void {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new LogicException( 'Can only be called in tests' );
		}
		self::$globalInstanceAllowed = false;
	}

	/**
	 * @internal Should only be used in MediaWikiUnitTestCase
	 */
	public static function allowGlobalInstanceAfterUnitTests(): void {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new LogicException( 'Can only be called in tests' );
		}
		self::$globalInstanceAllowed = true;
	}

	/**
	 * Returns true if an instance has already been initialized and can be
	 * obtained from getInstance(). This can be used to avoid accessing
	 * services if it's not safe or un necessary, e.g. in certain cases
	 * in unit tests or during early setup.
	 */
	public static function hasInstance(): bool {
		// NOTE: an instance could have been set by a call to forceGlobalInstance,
		// but would still be unusable if $globalInstanceAllowed is false.
		// This shouldn't happen, but it can during testing.
		return self::$instance !== null && self::$globalInstanceAllowed;
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
	 */
	public static function getInstance(): self {
		if ( !self::$globalInstanceAllowed ) {
			if ( defined( 'MW_PHPUNIT_TEST' ) ) {
				// Fail hard in PHPUnit tests only
				throw new LogicException( 'Premature access to service container' );
			}
			// TODO: getInstance() should always fail if $globalInstanceAllowed is false! (T153256)
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

	/** @inheritDoc */
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
	 * @param self $services The new MediaWikiServices object.
	 *
	 * @return self The old MediaWikiServices object, so it can be restored later.
	 */
	public static function forceGlobalInstance( self $services ): self {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new LogicException( __METHOD__ . ' must not be used outside unit tests.' );
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
	 * during the installation process or during testing. The method must not be called after
	 * MW_SERVICE_BOOTSTRAP_COMPLETE has been defined in Setup.php, unless MW_PHPUNIT_TEST or
	 * MEDIAWIKI_INSTALL or RUN_MAINTENANCE_IF_MAIN is defined).
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
	 * @param string $mode May be one of:
	 *   - quick: allow expensive resources to be re-used. See SalvageableService for details.
	 *   - reset: discard expensive resources but reuse service wiring (default)
	 *   - reload: discard expensive resources and reload the service wiring
	 */
	public static function resetGlobalInstance( ?Config $bootstrapConfig = null, $mode = 'reset' ) {
		if ( self::$instance === null ) {
			// no global instance yet, nothing to reset
			return;
		}

		self::failIfResetNotAllowed( __METHOD__ );

		$oldInstance = self::$instance;
		self::$instance = self::newInstance(
			$bootstrapConfig ?? self::$instance->getBootstrapConfig(),
			'load'
		);

		// Provides a traditional hook point to allow extensions to configure services.
		$runner = new HookRunner( $oldInstance->getHookContainer() );
		$runner->onMediaWikiServices( self::$instance );

		if ( $mode !== 'reload' ) {
			self::$instance->importWiring( $oldInstance, [ 'BootstrapConfig' ] );
		}

		if ( $mode === 'quick' ) {
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
	 */
	private function salvage( self $other ) {
		foreach ( $this->getServiceNames() as $name ) {
			// The service could be new in the new instance and not registered in the
			// other instance (e.g. an extension that was loaded after the instantiation of
			// the other instance. Skip this service in this case. See T143974
			try {
				$oldService = $other->peekService( $name );
			} catch ( NoSuchServiceException ) {
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
	 * @param string $loadWiring set this to 'load' to load the wiring files specified
	 *        in the 'ServiceWiringFiles' setting in $bootstrapConfig.
	 */
	private static function newInstance( Config $bootstrapConfig, $loadWiring = '' ): self {
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
	 * storage layer will result in an error.
	 *
	 * @since 1.28
	 * @deprecated since 1.40, use disableStorage() instead. Hard deprecated in 1.45.
	 *
	 * @warning This is intended for extreme situations, see the documentation of disableStorage() for details.
	 *
	 * @see resetGlobalInstance()
	 * @see resetChildProcessServices()
	 */
	public static function disableStorageBackend() {
		wfDeprecated( __METHOD__, '1.40' );
		$services = self::getInstance();
		$services->disableStorage();
	}

	/**
	 * Disables all storage layer services. After calling this, any attempt to access the
	 * storage layer will result in an error. Use resetGlobalInstance() with $mode=reload
	 * to restore normal operation.
	 *
	 * @since 1.40
	 *
	 * @warning This is intended for extreme situations only and should never be used
	 * while serving normal web requests. Legitimate use cases for this method include
	 * the installation process. Test fixtures may also use this, if the fixture relies
	 * on globalState.
	 *
	 * @see resetGlobalInstance()
	 * @see resetChildProcessServices()
	 */
	public function disableStorage() {
		if ( $this->storageDisabled ) {
			return;
		}

		$this->redefineService(
			'DBLoadBalancer',
			static function ( self $services ) {
				return new LoadBalancerDisabled();
			}
		);

		$this->redefineService(
			'DBLoadBalancerFactory',
			static function ( self $services ) {
				return LBFactorySingle::newDisabled();
			}
		);

		$this->redefineService(
			'InterwikiLookup',
			static function ( self $services ) {
				return new NullInterwikiLookup();
			}
		);

		$this->redefineService(
			'UserOptionsLookup',
			static function ( self $services ) {
				return new StaticUserOptionsLookup(
					[],
					$services->getMainConfig()->get( MainConfigNames::DefaultUserOptions )
				);
			}
		);

		$this->addServiceManipulator(
			'LocalisationCache',
			static function ( LocalisationCache $cache ) {
				$cache->disableBackend();
			}
		);

		$this->addServiceManipulator(
			'MessageCache',
			static function ( MessageCache $cache ) {
				$cache->disable();
			}
		);

		self::getInstance()->getObjectCacheFactory()->clear();

		$this->storageDisabled = true;
	}

	/**
	 * Returns true if disableStorage() has been called on this MediaWikiServices instance.
	 */
	public function isStorageDisabled(): bool {
		return $this->storageDisabled;
	}

	/**
	 * Resets any services that may have become stale after a child processö
	 * returns from after pcntl_fork(). It's also safe, but generally unnecessary,
	 * to call this method from the parent process.
	 *
	 * @since 1.28
	 *
	 * @note This is intended for use in the context of process forking only!
	 *
	 * @see resetGlobalInstance()
	 * @see disableStorage()
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
	 */
	public function resetServiceForTesting( $name, $destroy = true ) {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new LogicException( 'resetServiceForTesting() must not be used outside unit tests.' );
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
	 * @see resetGlobalInstance()
	 * @see forceGlobalInstance()
	 * @see disableStorage()
	 */
	public static function failIfResetNotAllowed( $method ) {
		if ( !defined( 'MW_PHPUNIT_TEST' )
			&& !defined( 'MEDIAWIKI_INSTALL' )
			&& !defined( 'RUN_MAINTENANCE_IF_MAIN' )
			&& defined( 'MW_SERVICE_BOOTSTRAP_COMPLETE' )
		) {
			throw new LogicException( $method . ' may only be called during bootstrapping and unit tests!' );
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
	 */
	public function getActionFactory(): ActionFactory {
		return $this->getService( 'ActionFactory' );
	}

	/**
	 * @since 1.31
	 */
	public function getActorMigration(): ActorMigration {
		return $this->getService( 'ActorMigration' );
	}

	/**
	 * @since 1.36
	 */
	public function getActorNormalization(): ActorNormalization {
		return $this->getService( 'ActorNormalization' );
	}

	/**
	 * @since 1.36
	 */
	public function getActorStore(): ActorStore {
		return $this->getService( 'ActorStore' );
	}

	/**
	 * @since 1.36
	 */
	public function getActorStoreFactory(): ActorStoreFactory {
		return $this->getService( 'ActorStoreFactory' );
	}

	/**
	 * @since 1.38
	 */
	public function getArchivedRevisionLookup(): ArchivedRevisionLookup {
		return $this->getService( 'ArchivedRevisionLookup' );
	}

	/**
	 * @since 1.35
	 */
	public function getAuthManager(): AuthManager {
		return $this->getService( 'AuthManager' );
	}

	/**
	 * @since 1.42
	 */
	public function getAutoblockExemptionList(): AutoblockExemptionList {
		return $this->getService( 'AutoblockExemptionList' );
	}

	/**
	 * @since 1.37
	 */
	public function getBacklinkCacheFactory(): BacklinkCacheFactory {
		return $this->getService( 'BacklinkCacheFactory' );
	}

	/**
	 * @since 1.34
	 */
	public function getBadFileLookup(): BadFileLookup {
		return $this->getService( 'BadFileLookup' );
	}

	/**
	 * @since 1.31
	 */
	public function getBlobStore(): BlobStore {
		return $this->getService( 'BlobStore' );
	}

	/**
	 * @since 1.31
	 */
	public function getBlobStoreFactory(): BlobStoreFactory {
		return $this->getService( 'BlobStoreFactory' );
	}

	/**
	 * @since 1.37
	 */
	public function getBlockActionInfo(): BlockActionInfo {
		return $this->getService( 'BlockActionInfo' );
	}

	/**
	 * @since 1.35
	 * @deprecated since 1.42, use getFormatterFactory()->getBlockErrorFormatter() instead.
	 */
	public function getBlockErrorFormatter(): BlockErrorFormatter {
		wfDeprecated( __METHOD__, '1.42' );
		return $this->getService( 'BlockErrorFormatter' );
	}

	/**
	 * @since 1.34
	 */
	public function getBlockManager(): BlockManager {
		return $this->getService( 'BlockManager' );
	}

	/**
	 * @since 1.35
	 */
	public function getBlockPermissionCheckerFactory(): BlockPermissionCheckerFactory {
		return $this->getService( 'BlockPermissionCheckerFactory' );
	}

	/**
	 * @since 1.33
	 */
	public function getBlockRestrictionStore(): BlockRestrictionStore {
		return $this->getService( 'BlockRestrictionStore' );
	}

	/**
	 * @since 1.38
	 */
	public function getBlockRestrictionStoreFactory(): BlockRestrictionStoreFactory {
		return $this->getService( 'BlockRestrictionStoreFactory' );
	}

	/**
	 * @since 1.44
	 */
	public function getBlockTargetFactory(): BlockTargetFactory {
		return $this->getService( 'BlockTargetFactory' );
	}

	/**
	 * @since 1.36
	 */
	public function getBlockUserFactory(): BlockUserFactory {
		return $this->getService( 'BlockUserFactory' );
	}

	/**
	 * @deprecated since 1.44
	 * @since 1.36
	 */
	public function getBlockUtils(): BlockUtils {
		return $this->getService( 'BlockUtils' );
	}

	/**
	 * @deprecated since 1.44
	 * @since 1.42
	 */
	public function getBlockUtilsFactory(): BlockUtilsFactory {
		return $this->getService( 'BlockUtilsFactory' );
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
	 */
	public function getBootstrapConfig(): Config {
		return $this->getService( 'BootstrapConfig' );
	}

	/**
	 * @since 1.37
	 */
	public function getBotPasswordStore(): BotPasswordStore {
		return $this->getService( 'BotPasswordStore' );
	}

	/**
	 * @since 1.37
	 */
	public function getCentralIdLookup(): CentralIdLookup {
		return $this->getService( 'CentralIdLookup' );
	}

	/**
	 * @since 1.37
	 */
	public function getCentralIdLookupFactory(): CentralIdLookupFactory {
		return $this->getService( 'CentralIdLookupFactory' );
	}

	/**
	 * @since 1.32
	 */
	public function getChangeTagDefStore(): NameTableStore {
		return $this->getService( 'ChangeTagDefStore' );
	}

	/**
	 * @since 1.41
	 */
	public function getChangeTagsStore(): ChangeTagsStore {
		return $this->getService( 'ChangeTagsStore' );
	}

	/**
	 * @since 1.41
	 */
	public function getChronologyProtector(): ChronologyProtector {
		return $this->getService( 'ChronologyProtector' );
	}

	/**
	 * @since 1.37
	 */
	public function getCollationFactory(): CollationFactory {
		return $this->getService( 'CollationFactory' );
	}

	/**
	 * @since 1.38
	 */
	public function getCommentFormatter(): CommentFormatter {
		return $this->getService( 'CommentFormatter' );
	}

	/**
	 * @since 1.41
	 */
	public function getCommentParserFactory(): CommentParserFactory {
		return $this->getService( 'CommentParserFactory' );
	}

	/**
	 * @since 1.31
	 */
	public function getCommentStore(): CommentStore {
		return $this->getService( 'CommentStore' );
	}

	/**
	 * @since 1.27
	 */
	public function getConfigFactory(): ConfigFactory {
		return $this->getService( 'ConfigFactory' );
	}

	/**
	 * @deprecated since 1.42. Unused.
	 * @since 1.32
	 */
	public function getConfigRepository(): ConfigRepository {
		wfDeprecated( __METHOD__, '1.42' );
		return $this->getService( 'ConfigRepository' );
	}

	/**
	 * @since 1.39
	 */
	public function getConfigSchema(): ConfigSchema {
		return $this->getService( 'ConfigSchema' );
	}

	/**
	 * @since 1.29
	 * @deprecated since 1.41, use ::getReadOnlyMode() service together
	 *   with ::getConfiguredReason() and ::isConfiguredReadOnly() to
	 *   check when a site is set to read-only mode.
	 *
	 *   Hard deprecated in 1.45.
	 */
	public function getConfiguredReadOnlyMode(): ConfiguredReadOnlyMode {
		wfDeprecated( __METHOD__, '1.41' );
		return $this->getService( 'ConfiguredReadOnlyMode' );
	}

	/**
	 * @since 1.42
	 */
	public function getConnectionProvider(): IConnectionProvider {
		return $this->getService( 'ConnectionProvider' );
	}

	/**
	 * @since 1.35
	 */
	public function getContentHandlerFactory(): IContentHandlerFactory {
		return $this->getService( 'ContentHandlerFactory' );
	}

	/**
	 * @since 1.32
	 */
	public function getContentLanguage(): Language {
		return $this->getService( 'ContentLanguage' );
	}

	/**
	 * @since 1.43
	 */
	public function getContentLanguageCode(): LanguageCode {
		return $this->getService( 'ContentLanguageCode' );
	}

	/**
	 * @since 1.35
	 */
	public function getContentModelChangeFactory(): ContentModelChangeFactory {
		return $this->getService( 'ContentModelChangeFactory' );
	}

	/**
	 * @since 1.31
	 */
	public function getContentModelStore(): NameTableStore {
		return $this->getService( 'ContentModelStore' );
	}

	/**
	 * @since 1.38
	 */
	public function getContentRenderer(): ContentRenderer {
		return $this->getService( 'ContentRenderer' );
	}

	/**
	 * @since 1.37
	 */
	public function getContentTransformer(): ContentTransformer {
		return $this->getService( 'ContentTransformer' );
	}

	/**
	 * @since 1.36
	 */
	public function getCriticalSectionProvider(): CriticalSectionProvider {
		return $this->getService( 'CriticalSectionProvider' );
	}

	/**
	 * @since 1.44
	 */
	public function getCrossWikiBlockTargetFactory(): CrossWikiBlockTargetFactory {
		return $this->getService( 'CrossWikiBlockTargetFactory' );
	}

	/**
	 * @since 1.36
	 */
	public function getDatabaseBlockStore(): DatabaseBlockStore {
		return $this->getService( 'DatabaseBlockStore' );
	}

	/**
	 * @since 1.40
	 */
	public function getDatabaseBlockStoreFactory(): DatabaseBlockStoreFactory {
		return $this->getService( 'DatabaseBlockStoreFactory' );
	}

	/**
	 * @since 1.39
	 */
	public function getDatabaseFactory(): DatabaseFactory {
		return $this->getService( 'DatabaseFactory' );
	}

	/**
	 * @since 1.33
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
	 * @note When possible, use {@link getConnectionProvider()} instead.
	 */
	public function getDBLoadBalancerFactory(): LBFactory {
		return $this->getService( 'DBLoadBalancerFactory' );
	}

	/**
	 * @since 1.39
	 */
	public function getDBLoadBalancerFactoryConfigBuilder(): MWLBFactory {
		return $this->getService( 'DBLoadBalancerFactoryConfigBuilder' );
	}

	/**
	 * @return OutputTransformPipeline
	 * @internal
	 */
	public function getDefaultOutputPipeline(): OutputTransformPipeline {
		return $this->getService( 'DefaultOutputPipeline' );
	}

	/**
	 * @since 1.37
	 */
	public function getDeletePageFactory(): DeletePageFactory {
		return $this->getService( 'DeletePageFactory' );
	}

	/**
	 * @since 1.44
	 * @unstable until 1.45
	 */
	public function getDomainEventDispatcher(): DomainEventDispatcher {
		return $this->getService( 'DomainEventDispatcher' );
	}

	/**
	 * @since 1.44
	 * @unstable until 1.45
	 */
	public function getDomainEventSource(): DomainEventSource {
		return $this->getService( 'DomainEventSource' );
	}

	/**
	 * @since 1.35
	 */
	public function getEmailer(): IEmailer {
		return $this->getService( 'Emailer' );
	}

	/**
	 * @since 1.41
	 */
	public function getEmailUserFactory(): EmailUserFactory {
		return $this->getService( 'EmailUserFactory' );
	}

	/**
	 * @since 1.27
	 */
	public function getEventRelayerGroup(): EventRelayerGroup {
		return $this->getService( 'EventRelayerGroup' );
	}

	/**
	 * @since 1.42
	 */
	public function getExtensionRegistry(): ExtensionRegistry {
		return $this->getService( 'ExtensionRegistry' );
	}

	/**
	 * @since 1.34
	 */
	public function getExternalStoreAccess(): ExternalStoreAccess {
		return $this->getService( 'ExternalStoreAccess' );
	}

	/**
	 * @since 1.31
	 */
	public function getExternalStoreFactory(): ExternalStoreFactory {
		return $this->getService( 'ExternalStoreFactory' );
	}

	/**
	 * @since 1.44
	 * @return FeatureShutdown
	 */
	public function getFeatureShutdown(): FeatureShutdown {
		return $this->getService( 'FeatureShutdown' );
	}

	/**
	 * @since 1.35
	 */
	public function getFileBackendGroup(): FileBackendGroup {
		return $this->getService( 'FileBackendGroup' );
	}

	/**
	 * @since 1.41
	 */
	public function getFormatterFactory(): FormatterFactory {
		return $this->getService( 'FormatterFactory' );
	}

	/**
	 * @since 1.28
	 */
	public function getGenderCache(): GenderCache {
		return $this->getService( 'GenderCache' );
	}

	/**
	 * @since 1.35
	 */
	public function getGlobalIdGenerator(): GlobalIdGenerator {
		return $this->getService( 'GlobalIdGenerator' );
	}

	/**
	 * @since 1.38
	 */
	public function getGrantsInfo(): GrantsInfo {
		return $this->getService( 'GrantsInfo' );
	}

	/**
	 * @since 1.38
	 */
	public function getGrantsLocalization(): GrantsLocalization {
		return $this->getService( 'GrantsLocalization' );
	}

	/**
	 * @since 1.36
	 */
	public function getGroupPermissionsLookup(): GroupPermissionsLookup {
		return $this->getService( 'GroupPermissionsLookup' );
	}

	/**
	 * @since 1.42
	 * @return HideUserUtils
	 */
	public function getHideUserUtils(): HideUserUtils {
		return $this->getService( 'HideUserUtils' );
	}

	/**
	 * @since 1.35
	 */
	public function getHookContainer(): HookContainer {
		return $this->getService( 'HookContainer' );
	}

	/**
	 * @since 1.35
	 */
	public function getHtmlCacheUpdater(): HTMLCacheUpdater {
		return $this->getService( 'HtmlCacheUpdater' );
	}

	/**
	 * @since 1.39
	 */
	public function getHtmlTransformFactory(): HtmlTransformFactory {
		return $this->getService( 'HtmlTransformFactory' );
	}

	/**
	 * @since 1.31
	 */
	public function getHttpRequestFactory(): HttpRequestFactory {
		return $this->getService( 'HttpRequestFactory' );
	}

	/**
	 * @since 1.28
	 */
	public function getInterwikiLookup(): InterwikiLookup {
		return $this->getService( 'InterwikiLookup' );
	}

	/**
	 * @since 1.41
	 */
	public function getIntroMessageBuilder(): IntroMessageBuilder {
		return $this->getService( 'IntroMessageBuilder' );
	}

	/**
	 * @since 1.40
	 */
	public function getJobFactory(): JobFactory {
		return $this->getService( 'JobFactory' );
	}

	/**
	 * @since 1.37
	 */
	public function getJobQueueGroup(): JobQueueGroup {
		return $this->getService( 'JobQueueGroup' );
	}

	/**
	 * @since 1.37
	 */
	public function getJobQueueGroupFactory(): JobQueueGroupFactory {
		return $this->getService( 'JobQueueGroupFactory' );
	}

	/**
	 * @since 1.35
	 */
	public function getJobRunner(): JobRunner {
		return $this->getService( 'JobRunner' );
	}

	/**
	 * @since 1.36
	 */
	public function getJsonCodec(): JsonCodec {
		return $this->getService( 'JsonCodec' );
	}

	/**
	 * @since 1.35
	 */
	public function getLanguageConverterFactory(): LanguageConverterFactory {
		return $this->getService( 'LanguageConverterFactory' );
	}

	/**
	 * @since 1.35
	 */
	public function getLanguageFactory(): LanguageFactory {
		return $this->getService( 'LanguageFactory' );
	}

	/**
	 * @since 1.35
	 */
	public function getLanguageFallback(): LanguageFallback {
		return $this->getService( 'LanguageFallback' );
	}

	/**
	 * @since 1.34
	 */
	public function getLanguageNameUtils(): LanguageNameUtils {
		return $this->getService( 'LanguageNameUtils' );
	}

	/**
	 * @since 1.35
	 */
	public function getLinkBatchFactory(): LinkBatchFactory {
		return $this->getService( 'LinkBatchFactory' );
	}

	/**
	 * @since 1.28
	 */
	public function getLinkCache(): LinkCache {
		return $this->getService( 'LinkCache' );
	}

	/**
	 * LinkRenderer instance that can be used
	 * if no custom options are needed
	 *
	 * @since 1.28
	 */
	public function getLinkRenderer(): LinkRenderer {
		return $this->getService( 'LinkRenderer' );
	}

	/**
	 * @since 1.28
	 */
	public function getLinkRendererFactory(): LinkRendererFactory {
		return $this->getService( 'LinkRendererFactory' );
	}

	/**
	 * @since 1.39
	 */
	public function getLinksMigration(): LinksMigration {
		return $this->getService( 'LinksMigration' );
	}

	/**
	 * @since 1.38
	 */
	public function getLinkTargetLookup(): LinkTargetLookup {
		return $this->getService( 'LinkTargetLookup' );
	}

	/**
	 * @since 1.43
	 */
	public function getLintErrorChecker(): LintErrorChecker {
		return $this->getService( 'LintErrorChecker' );
	}

	/**
	 * @since 1.34
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
	 */
	public function getLocalServerObjectCache(): BagOStuff {
		return $this->getService( 'LocalServerObjectCache' );
	}

	/**
	 * @since 1.34
	 */
	public function getLockManagerGroupFactory(): LockManagerGroupFactory {
		return $this->getService( 'LockManagerGroupFactory' );
	}

	/**
	 * @since 1.42
	 */
	public function getLogFormatterFactory(): LogFormatterFactory {
		return $this->getService( 'LogFormatterFactory' );
	}

	/**
	 * @since 1.32
	 */
	public function getMagicWordFactory(): MagicWordFactory {
		return $this->getService( 'MagicWordFactory' );
	}

	/**
	 * Returns the Config object that provides configuration for MediaWiki core.
	 * This may or may not be the same object that is returned by getBootstrapConfig().
	 *
	 * @since 1.27
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
	 */
	public function getMainWANObjectCache(): WANObjectCache {
		return $this->getService( 'MainWANObjectCache' );
	}

	/**
	 * @since 1.28
	 */
	public function getMediaHandlerFactory(): MediaHandlerFactory {
		return $this->getService( 'MediaHandlerFactory' );
	}

	/**
	 * @since 1.35
	 */
	public function getMergeHistoryFactory(): MergeHistoryFactory {
		return $this->getService( 'MergeHistoryFactory' );
	}

	/**
	 * @since 1.34
	 */
	public function getMessageCache(): MessageCache {
		return $this->getService( 'MessageCache' );
	}

	/**
	 * @since 1.34
	 */
	public function getMessageFormatterFactory(): IMessageFormatterFactory {
		return $this->getService( 'MessageFormatterFactory' );
	}

	/**
	 * @since 1.44
	 */
	public function getMessageParser(): MessageParser {
		return $this->getService( 'MessageParser' );
	}

	/**
	 * @since 1.42
	 * @unstable
	 * @return BagOStuff
	 */
	public function getMicroStash(): BagOStuff {
		return $this->getService( 'MicroStash' );
	}

	/**
	 * @since 1.28
	 */
	public function getMimeAnalyzer(): MimeAnalyzer {
		return $this->getService( 'MimeAnalyzer' );
	}

	/**
	 * @since 1.34
	 */
	public function getMovePageFactory(): MovePageFactory {
		return $this->getService( 'MovePageFactory' );
	}

	/**
	 * @since 1.34
	 */
	public function getNamespaceInfo(): NamespaceInfo {
		return $this->getService( 'NamespaceInfo' );
	}

	/**
	 * @since 1.32
	 */
	public function getNameTableStoreFactory(): NameTableStoreFactory {
		return $this->getService( 'NameTableStoreFactory' );
	}

	/**
	 * @since 1.44
	 */
	public function getNotificationService(): NotificationService {
		return $this->getService( 'NotificationService' );
	}

	/**
	 * @since 1.42
	 * @return ObjectCacheFactory
	 */
	public function getObjectCacheFactory(): ObjectCacheFactory {
		return $this->getService( 'ObjectCacheFactory' );
	}

	/**
	 * ObjectFactory is intended for instantiating "handlers" from declarative definitions,
	 * such as Action API modules, special pages, or REST API handlers.
	 *
	 * @since 1.34
	 */
	public function getObjectFactory(): ObjectFactory {
		return $this->getService( 'ObjectFactory' );
	}

	/**
	 * @since 1.32
	 */
	public function getOldRevisionImporter(): OldRevisionImporter {
		return $this->getService( 'OldRevisionImporter' );
	}

	/**
	 * @since 1.34
	 */
	public function getPageEditStash(): PageEditStash {
		return $this->getService( 'PageEditStash' );
	}

	/**
	 * @since 1.36
	 */
	public function getPageProps(): PageProps {
		return $this->getService( 'PageProps' );
	}

	/**
	 * @since 1.40
	 */
	public function getPageRestHelperFactory(): PageRestHelperFactory {
		return $this->getService( 'PageRestHelperFactory' );
	}

	/**
	 * @since 1.36
	 */
	public function getPageStore(): PageStore {
		return $this->getService( 'PageStore' );
	}

	/**
	 * @since 1.36
	 */
	public function getPageStoreFactory(): PageStoreFactory {
		return $this->getService( 'PageStoreFactory' );
	}

	/**
	 * @since 1.37
	 */
	public function getPageUpdaterFactory(): PageUpdaterFactory {
		return $this->getService( 'PageUpdaterFactory' );
	}

	/**
	 * Get the main Parser instance. This is unsafe when the caller is not in
	 * a top-level context, because re-entering the parser will throw an
	 * exception.
	 *
	 * @note Do not use this service for dependency injection or in service wiring.
	 * It is convenience function to get the global instance in global code.
	 * For dependency injection or service wiring code use the parser factory via the
	 * 'ParserFactory' service and call one of the factory functions, preferably
	 * {@link ParserFactory::create}.
	 *
	 * @since 1.29
	 */
	public function getParser(): Parser {
		return $this->getService( 'Parser' );
	}

	/**
	 * @since 1.30
	 */
	public function getParserCache(): ParserCache {
		return $this->getService( 'ParserCache' );
	}

	/**
	 * @since 1.36
	 */
	public function getParserCacheFactory(): ParserCacheFactory {
		return $this->getService( 'ParserCacheFactory' );
	}

	/**
	 * @since 1.32
	 */
	public function getParserFactory(): ParserFactory {
		return $this->getService( 'ParserFactory' );
	}

	/**
	 * @since 1.36
	 */
	public function getParserOutputAccess(): ParserOutputAccess {
		return $this->getService( 'ParserOutputAccess' );
	}

	/**
	 * @since 1.39
	 * @internal
	 */
	public function getParsoidDataAccess(): DataAccess {
		return $this->getService( 'ParsoidDataAccess' );
	}

	/**
	 * @since 1.39
	 * @unstable since 1.39, should be stable before release of 1.39
	 */
	public function getParsoidOutputStash(): ParsoidOutputStash {
		return $this->getService( 'ParsoidOutputStash' );
	}

	/**
	 * @since 1.39
	 * @internal
	 */
	public function getParsoidPageConfigFactory(): PageConfigFactory {
		return $this->getService( 'ParsoidPageConfigFactory' );
	}

	/**
	 * @since 1.41
	 * @internal
	 */
	public function getParsoidParserFactory(): ParsoidParserFactory {
		return $this->getService( 'ParsoidParserFactory' );
	}

	/**
	 * @since 1.39
	 * @internal
	 */
	public function getParsoidSiteConfig(): SiteConfig {
		return $this->getService( 'ParsoidSiteConfig' );
	}

	/**
	 * @since 1.32
	 */
	public function getPasswordFactory(): PasswordFactory {
		return $this->getService( 'PasswordFactory' );
	}

	/**
	 * @since 1.34
	 */
	public function getPasswordReset(): PasswordReset {
		return $this->getService( 'PasswordReset' );
	}

	/**
	 * @since 1.32
	 * @deprecated since 1.44 Use StatsFactory with `setLabel()` instead
	 *
	 * For example:
	 *
	 * ```
	 * $statsFactory
	 *      ->getCounter( 'example_total' )
	 *      ->setLabel( 'wiki', WikiMap::getCurrentWikiId() )
	 * ```
	 */
	public function getPerDbNameStatsdDataFactory(): StatsdDataFactoryInterface {
		return $this->getService( 'PerDbNameStatsdDataFactory' );
	}

	/**
	 * @since 1.33
	 */
	public function getPermissionManager(): PermissionManager {
		return $this->getService( 'PermissionManager' );
	}

	/**
	 * @since 1.41
	 * @internal
	 */
	public function getPingback(): Pingback {
		return $this->getService( 'Pingback' );
	}

	/**
	 * @since 1.40
	 */
	public function getPoolCounterFactory(): PoolCounterFactory {
		return $this->getService( 'PoolCounterFactory' );
	}

	/**
	 * @since 1.31
	 */
	public function getPreferencesFactory(): PreferencesFactory {
		return $this->getService( 'PreferencesFactory' );
	}

	/**
	 * @since 1.41
	 */
	public function getPreloadedContentBuilder(): PreloadedContentBuilder {
		return $this->getService( 'PreloadedContentBuilder' );
	}

	/**
	 * @since 1.28
	 */
	public function getProxyLookup(): ProxyLookup {
		return $this->getService( 'ProxyLookup' );
	}

	/**
	 * @since 1.39
	 */
	public function getRateLimiter(): RateLimiter {
		return $this->getService( 'RateLimiter' );
	}

	/**
	 * @since 1.29
	 */
	public function getReadOnlyMode(): ReadOnlyMode {
		return $this->getService( 'ReadOnlyMode' );
	}

	/**
	 * @since 1.38
	 */
	public function getRedirectLookup(): RedirectLookup {
		return $this->getService( 'RedirectLookup' );
	}

	/**
	 * @since 1.38
	 */
	public function getRedirectStore(): RedirectStore {
		return $this->getService( 'RedirectStore' );
	}

	/**
	 * @since 1.44
	 */
	public function getRenameUserFactory(): RenameUserFactory {
		return $this->getService( 'RenameUserFactory' );
	}

	/**
	 * @since 1.34
	 */
	public function getRepoGroup(): RepoGroup {
		return $this->getService( 'RepoGroup' );
	}

	/**
	 * @since 1.33
	 */
	public function getResourceLoader(): ResourceLoader {
		return $this->getService( 'ResourceLoader' );
	}

	/**
	 * @since 1.37
	 */
	public function getRestrictionStore(): RestrictionStore {
		return $this->getService( 'RestrictionStore' );
	}

	/**
	 * @since 1.36
	 */
	public function getRevertedTagUpdateManager(): RevertedTagUpdateManager {
		return $this->getService( 'RevertedTagUpdateManager' );
	}

	/**
	 * @since 1.31
	 */
	public function getRevisionFactory(): RevisionFactory {
		return $this->getService( 'RevisionFactory' );
	}

	/**
	 * @since 1.31
	 */
	public function getRevisionLookup(): RevisionLookup {
		return $this->getService( 'RevisionLookup' );
	}

	/**
	 * @since 1.32
	 */
	public function getRevisionRenderer(): RevisionRenderer {
		return $this->getService( 'RevisionRenderer' );
	}

	/**
	 * @since 1.31
	 */
	public function getRevisionStore(): RevisionStore {
		return $this->getService( 'RevisionStore' );
	}

	/**
	 * @since 1.32
	 */
	public function getRevisionStoreFactory(): RevisionStoreFactory {
		return $this->getService( 'RevisionStoreFactory' );
	}

	/**
	 * @since 1.37
	 */
	public function getRollbackPageFactory(): RollbackPageFactory {
		return $this->getService( 'RollbackPageFactory' );
	}

	/**
	 * @since 1.38
	 */
	public function getRowCommentFormatter(): RowCommentFormatter {
		return $this->getService( 'RowCommentFormatter' );
	}

	/**
	 * @since 1.27
	 */
	public function newSearchEngine(): SearchEngine {
		// New engine object every time, since they keep state
		return $this->getService( 'SearchEngineFactory' )->create();
	}

	/**
	 * @since 1.27
	 */
	public function getSearchEngineConfig(): SearchEngineConfig {
		return $this->getService( 'SearchEngineConfig' );
	}

	/**
	 * @since 1.27
	 */
	public function getSearchEngineFactory(): SearchEngineFactory {
		return $this->getService( 'SearchEngineFactory' );
	}

	/**
	 * @since 1.40
	 */
	public function getSearchResultThumbnailProvider(): SearchResultThumbnailProvider {
		return $this->getService( 'SearchResultThumbnailProvider' );
	}

	/**
	 * @since 1.44
	 */
	public function getSessionManager(): SessionManager {
		return $this->getService( 'SessionManager' );
	}

	/**
	 * @since 1.36
	 */
	public function getShellboxClientFactory(): ShellboxClientFactory {
		return $this->getService( 'ShellboxClientFactory' );
	}

	/**
	 * @since 1.30
	 */
	public function getShellCommandFactory(): CommandFactory {
		return $this->getService( 'ShellCommandFactory' );
	}

	/**
	 * @since 1.38
	 */
	public function getSignatureValidatorFactory(): SignatureValidatorFactory {
		return $this->getService( 'SignatureValidatorFactory' );
	}

	/**
	 * @since 1.27
	 */
	public function getSiteLookup(): SiteLookup {
		return $this->getService( 'SiteLookup' );
	}

	/**
	 * @since 1.27
	 */
	public function getSiteStore(): SiteStore {
		return $this->getService( 'SiteStore' );
	}

	/**
	 * @since 1.27
	 */
	public function getSkinFactory(): SkinFactory {
		return $this->getService( 'SkinFactory' );
	}

	/**
	 * @since 1.33
	 */
	public function getSlotRoleRegistry(): SlotRoleRegistry {
		return $this->getService( 'SlotRoleRegistry' );
	}

	/**
	 * @since 1.31
	 */
	public function getSlotRoleStore(): NameTableStore {
		return $this->getService( 'SlotRoleStore' );
	}

	/**
	 * @since 1.35
	 */
	public function getSpamChecker(): SpamChecker {
		return $this->getService( 'SpamChecker' );
	}

	/**
	 * @since 1.32
	 */
	public function getSpecialPageFactory(): SpecialPageFactory {
		return $this->getService( 'SpecialPageFactory' );
	}

	/**
	 * @since 1.27
	 */
	public function getStatsdDataFactory(): IBufferingStatsdDataFactory {
		return $this->getService( 'StatsdDataFactory' );
	}

	/**
	 * @since 1.41
	 */
	public function getStatsFactory(): StatsFactory {
		return $this->getService( 'StatsFactory' );
	}

	/**
	 * @since 1.35
	 */
	public function getTalkPageNotificationManager(): TalkPageNotificationManager {
		return $this->getService( 'TalkPageNotificationManager' );
	}

	/**
	 * @since 1.34
	 */
	public function getTempFSFileFactory(): TempFSFileFactory {
		return $this->getService( 'TempFSFileFactory' );
	}

	/**
	 * @since 1.39
	 */
	public function getTempUserConfig(): RealTempUserConfig {
		return $this->getService( 'TempUserConfig' );
	}

	/**
	 * @since 1.39
	 */
	public function getTempUserCreator(): TempUserCreator {
		return $this->getService( 'TempUserCreator' );
	}

	/**
	 * @since 1.44
	 */
	public function getTempUserDetailsLookup(): TempUserDetailsLookup {
		return $this->getService( 'TempUserDetailsLookup' );
	}

	/**
	 * @since 1.36
	 */
	public function getTidy(): TidyDriverBase {
		return $this->getService( 'Tidy' );
	}

	/**
	 * @since 1.35
	 */
	public function getTitleFactory(): TitleFactory {
		return $this->getService( 'TitleFactory' );
	}

	/**
	 * @since 1.28
	 */
	public function getTitleFormatter(): TitleFormatter {
		return $this->getService( 'TitleFormatter' );
	}

	/**
	 * @since 1.40
	 */
	public function getTitleMatcher(): TitleMatcher {
		return $this->getService( 'TitleMatcher' );
	}

	/**
	 * @since 1.28
	 */
	public function getTitleParser(): TitleParser {
		return $this->getService( 'TitleParser' );
	}

	public function getTracer(): TracerInterface {
		return $this->getService( 'Tracer' );
	}

	/**
	 * @since 1.38
	 */
	public function getTrackingCategories(): TrackingCategories {
		return $this->getService( 'TrackingCategories' );
	}

	/**
	 * @since 1.36
	 */
	public function getUnblockUserFactory(): UnblockUserFactory {
		return $this->getService( 'UnblockUserFactory' );
	}

	/**
	 * @since 1.38
	 */
	public function getUndeletePageFactory(): UndeletePageFactory {
		return $this->getService( 'UndeletePageFactory' );
	}

	/**
	 * @since 1.32
	 */
	public function getUploadRevisionImporter(): UploadRevisionImporter {
		return $this->getService( 'UploadRevisionImporter' );
	}

	/**
	 * @since 1.39
	 */
	public function getUrlUtils(): UrlUtils {
		return $this->getService( 'UrlUtils' );
	}

	/**
	 * @since 1.36
	 * @deprecated since 1.43, use ActorStore
	 */
	public function getUserCache(): UserCache {
		return $this->getService( 'UserCache' );
	}

	/**
	 * @since 1.35
	 */
	public function getUserEditTracker(): UserEditTracker {
		return $this->getService( 'UserEditTracker' );
	}

	/**
	 * @since 1.35
	 */
	public function getUserFactory(): UserFactory {
		return $this->getService( 'UserFactory' );
	}

	/**
	 * @since 1.35
	 */
	public function getUserGroupManager(): UserGroupManager {
		return $this->getService( 'UserGroupManager' );
	}

	/**
	 * @since 1.35
	 */
	public function getUserGroupManagerFactory(): UserGroupManagerFactory {
		return $this->getService( 'UserGroupManagerFactory' );
	}

	/**
	 * @since 1.36
	 */
	public function getUserIdentityLookup(): UserIdentityLookup {
		return $this->getService( 'UserIdentityLookup' );
	}

	/**
	 * @since 1.41
	 */
	public function getUserIdentityUtils(): UserIdentityUtils {
		return $this->getService( 'UserIdentityUtils' );
	}

	/**
	 * @since 1.44
	 */
	public function getUserLinkRenderer(): UserLinkRenderer {
		return $this->getService( 'UserLinkRenderer' );
	}

	/**
	 * @since 1.36
	 */
	public function getUserNamePrefixSearch(): UserNamePrefixSearch {
		return $this->getService( 'UserNamePrefixSearch' );
	}

	/**
	 * @since 1.35
	 */
	public function getUserNameUtils(): UserNameUtils {
		return $this->getService( 'UserNameUtils' );
	}

	/**
	 * @since 1.35
	 */
	public function getUserOptionsLookup(): UserOptionsLookup {
		return $this->getService( 'UserOptionsLookup' );
	}

	/**
	 * @since 1.35
	 */
	public function getUserOptionsManager(): UserOptionsManager {
		return $this->getService( 'UserOptionsManager' );
	}

	/**
	 * @since 1.41
	 */
	public function getUserRegistrationLookup(): UserRegistrationLookup {
		return $this->getService( 'UserRegistrationLookup' );
	}

	/**
	 * @since 1.28
	 */
	public function getWatchedItemQueryService(): WatchedItemQueryService {
		return $this->getService( 'WatchedItemQueryService' );
	}

	/**
	 * @since 1.28
	 */
	public function getWatchedItemStore(): WatchedItemStoreInterface {
		return $this->getService( 'WatchedItemStore' );
	}

	/**
	 * @since 1.36
	 */
	public function getWatchlistManager(): WatchlistManager {
		return $this->getService( 'WatchlistManager' );
	}

	/**
	 * @since 1.38
	 */
	public function getWikiExporterFactory(): WikiExporterFactory {
		return $this->getService( 'WikiExporterFactory' );
	}

	/**
	 * @since 1.37
	 */
	public function getWikiImporterFactory(): WikiImporterFactory {
		return $this->getService( 'WikiImporterFactory' );
	}

	/**
	 * @since 1.36
	 */
	public function getWikiPageFactory(): WikiPageFactory {
		return $this->getService( 'WikiPageFactory' );
	}

	/**
	 * @since 1.31
	 */
	public function getWikiRevisionOldRevisionImporter(): OldRevisionImporter {
		return $this->getService( 'OldRevisionImporter' );
	}

	/**
	 * @since 1.31
	 */
	public function getWikiRevisionOldRevisionImporterNoUpdates(): OldRevisionImporter {
		return $this->getService( 'WikiRevisionOldRevisionImporterNoUpdates' );
	}

	/**
	 * @since 1.31
	 */
	public function getWikiRevisionUploadImporter(): UploadRevisionImporter {
		return $this->getService( 'UploadRevisionImporter' );
	}

	/**
	 * @since 1.39
	 */
	public function getWRStatsFactory(): WRStatsFactory {
		return $this->getService( 'WRStatsFactory' );
	}

}
