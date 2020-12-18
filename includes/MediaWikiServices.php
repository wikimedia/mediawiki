<?php

namespace MediaWiki;

use ActorMigration;
use BagOStuff;
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
use JobRunner;
use Language;
use LinkCache;
use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use LocalisationCache;
use MagicWordFactory;
use MediaHandlerFactory;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Block\BlockErrorFormatter;
use MediaWiki\Block\BlockManager;
use MediaWiki\Block\BlockPermissionCheckerFactory;
use MediaWiki\Block\BlockRestrictionStore;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Config\ConfigRepository;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\EditPage\SpamChecker;
use MediaWiki\FileBackend\FSFile\TempFSFileFactory;
use MediaWiki\FileBackend\LockManager\LockManagerGroupFactory;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Http\HttpRequestFactory;
use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\Languages\LanguageFallback;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Linker\LinkRendererFactory;
use MediaWiki\Mail\IEmailer;
use MediaWiki\Page\ContentModelChangeFactory;
use MediaWiki\Page\MergeHistoryFactory;
use MediaWiki\Page\MovePageFactory;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Preferences\PreferencesFactory;
use MediaWiki\Revision\ContributionsLookup;
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
use MediaWiki\User\TalkPageNotificationManager;
use MediaWiki\User\UserEditTracker;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserGroupManagerFactory;
use MediaWiki\User\UserNameUtils;
use MediaWiki\User\UserOptionsLookup;
use MediaWiki\User\UserOptionsManager;
use MediaWiki\User\WatchlistNotificationManager;
use MessageCache;
use MimeAnalyzer;
use MWException;
use NamespaceInfo;
use ObjectCache;
use OldRevisionImporter;
use Parser;
use ParserCache;
use ParserFactory;
use PasswordFactory;
use PasswordReset;
use ProxyLookup;
use ReadOnlyMode;
use RepoGroup;
use ResourceLoader;
use SearchEngine;
use SearchEngineConfig;
use SearchEngineFactory;
use SiteLookup;
use SiteStore;
use SkinFactory;
use TitleFactory;
use TitleFormatter;
use TitleParser;
use UploadRevisionImporter;
use VirtualRESTServiceClient;
use WANObjectCache;
use WatchedItemQueryService;
use WatchedItemStoreInterface;
use Wikimedia\Message\IMessageFormatterFactory;
use Wikimedia\ObjectFactory;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Services\NoSuchServiceException;
use Wikimedia\Services\SalvageableService;
use Wikimedia\Services\ServiceContainer;
use Wikimedia\UUID\GlobalIdGenerator;

/**
 * Service locator for MediaWiki core services.
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
 * @since 1.27
 */

/**
 * MediaWikiServices is the service locator for the application scope of MediaWiki.
 * Its implemented as a simple configurable DI container.
 * MediaWikiServices acts as a top level factory/registry for top level services, and builds
 * the network of service objects that defines MediaWiki's application logic.
 * It acts as an entry point to MediaWiki's dependency injection mechanism.
 *
 * Services are defined in the "wiring" array passed to the constructor,
 * or by calling defineService().
 *
 * @see docs/Injection.md for an overview of using dependency injection in the
 *      MediaWiki code base.
 */
class MediaWikiServices extends ServiceContainer {

	/**
	 * @var MediaWikiServices|null
	 */
	private static $instance = null;

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
	public static function getInstance() : self {
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
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
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
	 * @param Config|null $bootstrapConfig The Config object to be registered as the
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
			$wiringFiles = $bootstrapConfig->get( 'ServiceWiringFiles' );
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
		$this->defineService( 'BootstrapConfig', function () use ( $config ) {
			return $config;
		} );
	}

	// CONVENIENCE GETTERS ////////////////////////////////////////////////////

	/**
	 * @since 1.31
	 * @return ActorMigration
	 */
	public function getActorMigration() : ActorMigration {
		return $this->getService( 'ActorMigration' );
	}

	/**
	 * @since 1.35
	 * @return AuthManager
	 */
	public function getAuthManager() : AuthManager {
		return $this->getService( 'AuthManager' );
	}

	/**
	 * @since 1.34
	 * @return BadFileLookup
	 */
	public function getBadFileLookup() : BadFileLookup {
		return $this->getService( 'BadFileLookup' );
	}

	/**
	 * @since 1.31
	 * @return BlobStore
	 */
	public function getBlobStore() : BlobStore {
		return $this->getService( 'BlobStore' );
	}

	/**
	 * @since 1.31
	 * @return BlobStoreFactory
	 */
	public function getBlobStoreFactory() : BlobStoreFactory {
		return $this->getService( 'BlobStoreFactory' );
	}

	/**
	 * @since 1.35
	 * @return BlockErrorFormatter
	 */
	public function getBlockErrorFormatter() : BlockErrorFormatter {
		return $this->getService( 'BlockErrorFormatter' );
	}

	/**
	 * @since 1.34
	 * @return BlockManager
	 */
	public function getBlockManager() : BlockManager {
		return $this->getService( 'BlockManager' );
	}

	/**
	 * @since 1.35
	 * @return BlockPermissionCheckerFactory
	 */
	public function getBlockPermissionCheckerFactory() : BlockPermissionCheckerFactory {
		return $this->getService( 'BlockPermissionCheckerFactory' );
	}

	/**
	 * @since 1.33
	 * @return BlockRestrictionStore
	 */
	public function getBlockRestrictionStore() : BlockRestrictionStore {
		return $this->getService( 'BlockRestrictionStore' );
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
	public function getBootstrapConfig() : Config {
		return $this->getService( 'BootstrapConfig' );
	}

	/**
	 * @since 1.32
	 * @return NameTableStore
	 */
	public function getChangeTagDefStore() : NameTableStore {
		return $this->getService( 'ChangeTagDefStore' );
	}

	/**
	 * @since 1.31
	 * @return CommentStore
	 */
	public function getCommentStore() : CommentStore {
		return $this->getService( 'CommentStore' );
	}

	/**
	 * @since 1.27
	 * @return ConfigFactory
	 */
	public function getConfigFactory() : ConfigFactory {
		return $this->getService( 'ConfigFactory' );
	}

	/**
	 * @since 1.32
	 * @return ConfigRepository
	 */
	public function getConfigRepository() : ConfigRepository {
		return $this->getService( 'ConfigRepository' );
	}

	/**
	 * @since 1.29
	 * @return ConfiguredReadOnlyMode
	 */
	public function getConfiguredReadOnlyMode() : ConfiguredReadOnlyMode {
		return $this->getService( 'ConfiguredReadOnlyMode' );
	}

	/**
	 * @since 1.35
	 * @return IContentHandlerFactory
	 */
	public function getContentHandlerFactory() : IContentHandlerFactory {
		return $this->getService( 'ContentHandlerFactory' );
	}

	/**
	 * @since 1.32
	 * @return Language
	 */
	public function getContentLanguage() : Language {
		return $this->getService( 'ContentLanguage' );
	}

	/**
	 * @since 1.35
	 * @return ContentModelChangeFactory
	 */
	public function getContentModelChangeFactory() : ContentModelChangeFactory {
		return $this->getService( 'ContentModelChangeFactory' );
	}

	/**
	 * @since 1.31
	 * @return NameTableStore
	 */
	public function getContentModelStore() : NameTableStore {
		return $this->getService( 'ContentModelStore' );
	}

	/**
	 * @since 1.35
	 * @return ContributionsLookup
	 */
	public function getContributionsLookup() : ContributionsLookup {
		return $this->getService( 'ContributionsLookup' );
	}

	/**
	 * @since 1.28
	 * @return CryptHKDF
	 */
	public function getCryptHKDF() : CryptHKDF {
		return $this->getService( 'CryptHKDF' );
	}

	/**
	 * @since 1.33
	 * @return DateFormatterFactory
	 */
	public function getDateFormatterFactory() : DateFormatterFactory {
		return $this->getService( 'DateFormatterFactory' );
	}

	/**
	 * @since 1.28
	 * @return ILoadBalancer The main DB load balancer for the local wiki.
	 */
	public function getDBLoadBalancer() : ILoadBalancer {
		return $this->getService( 'DBLoadBalancer' );
	}

	/**
	 * @since 1.28
	 * @return LBFactory
	 */
	public function getDBLoadBalancerFactory() : LBFactory {
		return $this->getService( 'DBLoadBalancerFactory' );
	}

	/**
	 * @since 1.35
	 * @return IEmailer
	 */
	public function getEmailer() : IEmailer {
		return $this->getService( 'Emailer' );
	}

	/**
	 * @since 1.27
	 * @return EventRelayerGroup
	 */
	public function getEventRelayerGroup() : EventRelayerGroup {
		return $this->getService( 'EventRelayerGroup' );
	}

	/**
	 * @since 1.34
	 * @return ExternalStoreAccess
	 */
	public function getExternalStoreAccess() : ExternalStoreAccess {
		return $this->getService( 'ExternalStoreAccess' );
	}

	/**
	 * @since 1.31
	 * @return ExternalStoreFactory
	 */
	public function getExternalStoreFactory() : ExternalStoreFactory {
		return $this->getService( 'ExternalStoreFactory' );
	}

	/**
	 * @since 1.35
	 * @return FileBackendGroup
	 */
	public function getFileBackendGroup() : FileBackendGroup {
		return $this->getService( 'FileBackendGroup' );
	}

	/**
	 * @since 1.28
	 * @return GenderCache
	 */
	public function getGenderCache() : GenderCache {
		return $this->getService( 'GenderCache' );
	}

	/**
	 * @since 1.35
	 * @return GlobalIdGenerator
	 */
	public function getGlobalIdGenerator() : GlobalIdGenerator {
		return $this->getService( 'GlobalIdGenerator' );
	}

	/**
	 * @since 1.35
	 * @return HookContainer
	 */
	public function getHookContainer() : HookContainer {
		return $this->getService( 'HookContainer' );
	}

	/**
	 * @since 1.35
	 * @return HtmlCacheUpdater
	 */
	public function getHtmlCacheUpdater() : HtmlCacheUpdater {
		return $this->getService( 'HtmlCacheUpdater' );
	}

	/**
	 * @since 1.31
	 * @return HttpRequestFactory
	 */
	public function getHttpRequestFactory() : HttpRequestFactory {
		return $this->getService( 'HttpRequestFactory' );
	}

	/**
	 * @since 1.28
	 * @return InterwikiLookup
	 */
	public function getInterwikiLookup() : InterwikiLookup {
		return $this->getService( 'InterwikiLookup' );
	}

	/**
	 * @since 1.35
	 * @return JobRunner
	 */
	public function getJobRunner() : JobRunner {
		return $this->getService( 'JobRunner' );
	}

	/**
	 * @since 1.35
	 * @return LanguageConverterFactory
	 */
	public function getLanguageConverterFactory() : LanguageConverterFactory {
		return $this->getService( 'LanguageConverterFactory' );
	}

	/**
	 * @since 1.35
	 * @return LanguageFactory
	 */
	public function getLanguageFactory() : LanguageFactory {
		return $this->getService( 'LanguageFactory' );
	}

	/**
	 * @since 1.35
	 * @return LanguageFallback
	 */
	public function getLanguageFallback() : LanguageFallback {
		return $this->getService( 'LanguageFallback' );
	}

	/**
	 * @since 1.34
	 * @return LanguageNameUtils
	 */
	public function getLanguageNameUtils() {
		return $this->getService( 'LanguageNameUtils' );
	}

	/**
	 * @since 1.35
	 * @return LinkBatchFactory
	 */
	public function getLinkBatchFactory() : LinkBatchFactory {
		return $this->getService( 'LinkBatchFactory' );
	}

	/**
	 * @since 1.28
	 * @return LinkCache
	 */
	public function getLinkCache() : LinkCache {
		return $this->getService( 'LinkCache' );
	}

	/**
	 * LinkRenderer instance that can be used
	 * if no custom options are needed
	 *
	 * @since 1.28
	 * @return LinkRenderer
	 */
	public function getLinkRenderer() : LinkRenderer {
		return $this->getService( 'LinkRenderer' );
	}

	/**
	 * @since 1.28
	 * @return LinkRendererFactory
	 */
	public function getLinkRendererFactory() : LinkRendererFactory {
		return $this->getService( 'LinkRendererFactory' );
	}

	/**
	 * @since 1.34
	 * @return LocalisationCache
	 */
	public function getLocalisationCache() : LocalisationCache {
		return $this->getService( 'LocalisationCache' );
	}

	/**
	 * @since 1.28
	 * @return BagOStuff
	 */
	public function getLocalServerObjectCache() : BagOStuff {
		return $this->getService( 'LocalServerObjectCache' );
	}

	/**
	 * @since 1.34
	 * @return LockManagerGroupFactory
	 */
	public function getLockManagerGroupFactory() : LockManagerGroupFactory {
		return $this->getService( 'LockManagerGroupFactory' );
	}

	/**
	 * @since 1.32
	 * @return MagicWordFactory
	 */
	public function getMagicWordFactory() : MagicWordFactory {
		return $this->getService( 'MagicWordFactory' );
	}

	/**
	 * Returns the Config object that provides configuration for MediaWiki core.
	 * This may or may not be the same object that is returned by getBootstrapConfig().
	 *
	 * @since 1.27
	 * @return Config
	 */
	public function getMainConfig() : Config {
		return $this->getService( 'MainConfig' );
	}

	/**
	 * @since 1.28
	 * @return BagOStuff
	 */
	public function getMainObjectStash() : BagOStuff {
		return $this->getService( 'MainObjectStash' );
	}

	/**
	 * @since 1.28
	 * @return WANObjectCache
	 */
	public function getMainWANObjectCache() : WANObjectCache {
		return $this->getService( 'MainWANObjectCache' );
	}

	/**
	 * @since 1.28
	 * @return MediaHandlerFactory
	 */
	public function getMediaHandlerFactory() : MediaHandlerFactory {
		return $this->getService( 'MediaHandlerFactory' );
	}

	/**
	 * @since 1.35
	 * @return MergeHistoryFactory
	 */
	public function getMergeHistoryFactory() : MergeHistoryFactory {
		return $this->getService( 'MergeHistoryFactory' );
	}

	/**
	 * @since 1.34
	 * @return MessageCache
	 */
	public function getMessageCache() : MessageCache {
		return $this->getService( 'MessageCache' );
	}

	/**
	 * @since 1.34
	 * @return IMessageFormatterFactory
	 */
	public function getMessageFormatterFactory() : IMessageFormatterFactory {
		return $this->getService( 'MessageFormatterFactory' );
	}

	/**
	 * @since 1.28
	 * @return MimeAnalyzer
	 */
	public function getMimeAnalyzer() : MimeAnalyzer {
		return $this->getService( 'MimeAnalyzer' );
	}

	/**
	 * @since 1.34
	 * @return MovePageFactory
	 */
	public function getMovePageFactory() : MovePageFactory {
		return $this->getService( 'MovePageFactory' );
	}

	/**
	 * @since 1.34
	 * @return NamespaceInfo
	 */
	public function getNamespaceInfo() : NamespaceInfo {
		return $this->getService( 'NamespaceInfo' );
	}

	/**
	 * @since 1.32
	 * @return NameTableStoreFactory
	 */
	public function getNameTableStoreFactory() : NameTableStoreFactory {
		return $this->getService( 'NameTableStoreFactory' );
	}

	/**
	 * ObjectFactory is intended for instantiating "handlers" from declarative definitions,
	 * such as Action API modules, special pages, or REST API handlers.
	 *
	 * @since 1.34
	 * @return ObjectFactory
	 */
	public function getObjectFactory() : ObjectFactory {
		return $this->getService( 'ObjectFactory' );
	}

	/**
	 * @since 1.32
	 * @return OldRevisionImporter
	 */
	public function getOldRevisionImporter() : OldRevisionImporter {
		return $this->getService( 'OldRevisionImporter' );
	}

	/**
	 * @return PageEditStash
	 * @since 1.34
	 */
	public function getPageEditStash() : PageEditStash {
		return $this->getService( 'PageEditStash' );
	}

	/**
	 * @since 1.29
	 * @return Parser
	 */
	public function getParser() : Parser {
		return $this->getService( 'Parser' );
	}

	/**
	 * @since 1.30
	 * @return ParserCache
	 */
	public function getParserCache() : ParserCache {
		return $this->getService( 'ParserCache' );
	}

	/**
	 * @since 1.32
	 * @return ParserFactory
	 */
	public function getParserFactory() : ParserFactory {
		return $this->getService( 'ParserFactory' );
	}

	/**
	 * @since 1.32
	 * @return PasswordFactory
	 */
	public function getPasswordFactory() : PasswordFactory {
		return $this->getService( 'PasswordFactory' );
	}

	/**
	 * @since 1.34
	 * @return PasswordReset
	 */
	public function getPasswordReset() : PasswordReset {
		return $this->getService( 'PasswordReset' );
	}

	/**
	 * @since 1.32
	 * @return StatsdDataFactoryInterface
	 */
	public function getPerDbNameStatsdDataFactory() : StatsdDataFactoryInterface {
		return $this->getService( 'PerDbNameStatsdDataFactory' );
	}

	/**
	 * @since 1.33
	 * @return PermissionManager
	 */
	public function getPermissionManager() : PermissionManager {
		return $this->getService( 'PermissionManager' );
	}

	/**
	 * @since 1.31
	 * @return PreferencesFactory
	 */
	public function getPreferencesFactory() : PreferencesFactory {
		return $this->getService( 'PreferencesFactory' );
	}

	/**
	 * @since 1.28
	 * @return ProxyLookup
	 */
	public function getProxyLookup() : ProxyLookup {
		return $this->getService( 'ProxyLookup' );
	}

	/**
	 * @since 1.29
	 * @return ReadOnlyMode
	 */
	public function getReadOnlyMode() : ReadOnlyMode {
		return $this->getService( 'ReadOnlyMode' );
	}

	/**
	 * @since 1.34
	 * @return RepoGroup
	 */
	public function getRepoGroup() : RepoGroup {
		return $this->getService( 'RepoGroup' );
	}

	/**
	 * @since 1.33
	 * @return ResourceLoader
	 */
	public function getResourceLoader() : ResourceLoader {
		return $this->getService( 'ResourceLoader' );
	}

	/**
	 * @since 1.31
	 * @return RevisionFactory
	 */
	public function getRevisionFactory() : RevisionFactory {
		return $this->getService( 'RevisionFactory' );
	}

	/**
	 * @since 1.31
	 * @return RevisionLookup
	 */
	public function getRevisionLookup() : RevisionLookup {
		return $this->getService( 'RevisionLookup' );
	}

	/**
	 * @since 1.32
	 * @return RevisionRenderer
	 */
	public function getRevisionRenderer() : RevisionRenderer {
		return $this->getService( 'RevisionRenderer' );
	}

	/**
	 * @since 1.31
	 * @return RevisionStore
	 */
	public function getRevisionStore() : RevisionStore {
		return $this->getService( 'RevisionStore' );
	}

	/**
	 * @since 1.32
	 * @return RevisionStoreFactory
	 */
	public function getRevisionStoreFactory() : RevisionStoreFactory {
		return $this->getService( 'RevisionStoreFactory' );
	}

	/**
	 * @since 1.27
	 * @return SearchEngine
	 */
	public function newSearchEngine() : SearchEngine {
		// New engine object every time, since they keep state
		return $this->getService( 'SearchEngineFactory' )->create();
	}

	/**
	 * @since 1.27
	 * @return SearchEngineConfig
	 */
	public function getSearchEngineConfig() : SearchEngineConfig {
		return $this->getService( 'SearchEngineConfig' );
	}

	/**
	 * @since 1.27
	 * @return SearchEngineFactory
	 */
	public function getSearchEngineFactory() : SearchEngineFactory {
		return $this->getService( 'SearchEngineFactory' );
	}

	/**
	 * @since 1.30
	 * @return CommandFactory
	 */
	public function getShellCommandFactory() : CommandFactory {
		return $this->getService( 'ShellCommandFactory' );
	}

	/**
	 * @since 1.27
	 * @return SiteLookup
	 */
	public function getSiteLookup() : SiteLookup {
		return $this->getService( 'SiteLookup' );
	}

	/**
	 * @since 1.27
	 * @return SiteStore
	 */
	public function getSiteStore() : SiteStore {
		return $this->getService( 'SiteStore' );
	}

	/**
	 * @since 1.27
	 * @return SkinFactory
	 */
	public function getSkinFactory() : SkinFactory {
		return $this->getService( 'SkinFactory' );
	}

	/**
	 * @since 1.33
	 * @return SlotRoleRegistry
	 */
	public function getSlotRoleRegistry() : SlotRoleRegistry {
		return $this->getService( 'SlotRoleRegistry' );
	}

	/**
	 * @since 1.31
	 * @return NameTableStore
	 */
	public function getSlotRoleStore() : NameTableStore {
		return $this->getService( 'SlotRoleStore' );
	}

	/**
	 * @since 1.35
	 * @return SpamChecker
	 */
	public function getSpamChecker() : SpamChecker {
		return $this->getService( 'SpamChecker' );
	}

	/**
	 * @since 1.32
	 * @return SpecialPageFactory
	 */
	public function getSpecialPageFactory() : SpecialPageFactory {
		return $this->getService( 'SpecialPageFactory' );
	}

	/**
	 * @since 1.27
	 * @return IBufferingStatsdDataFactory
	 */
	public function getStatsdDataFactory() : IBufferingStatsdDataFactory {
		return $this->getService( 'StatsdDataFactory' );
	}

	/**
	 * @since 1.35
	 * @return TalkPageNotificationManager
	 */
	public function getTalkPageNotificationManager() : TalkPageNotificationManager {
		return $this->getService( 'TalkPageNotificationManager' );
	}

	/**
	 * @since 1.34
	 * @return TempFSFileFactory
	 */
	public function getTempFSFileFactory() : TempFSFileFactory {
		return $this->getService( 'TempFSFileFactory' );
	}

	/**
	 * @since 1.35
	 * @return TitleFactory
	 */
	public function getTitleFactory() : TitleFactory {
		return $this->getService( 'TitleFactory' );
	}

	/**
	 * @since 1.28
	 * @return TitleFormatter
	 */
	public function getTitleFormatter() : TitleFormatter {
		return $this->getService( 'TitleFormatter' );
	}

	/**
	 * @since 1.28
	 * @return TitleParser
	 */
	public function getTitleParser() : TitleParser {
		return $this->getService( 'TitleParser' );
	}

	/**
	 * @since 1.32
	 * @return UploadRevisionImporter
	 */
	public function getUploadRevisionImporter() : UploadRevisionImporter {
		return $this->getService( 'UploadRevisionImporter' );
	}

	/**
	 * @since 1.35
	 * @return UserEditTracker
	 */
	public function getUserEditTracker() : UserEditTracker {
		return $this->getService( 'UserEditTracker' );
	}

	/**
	 * @since 1.35
	 * @return UserFactory
	 */
	public function getUserFactory() : UserFactory {
		return $this->getService( 'UserFactory' );
	}

	/**
	 * @since 1.35
	 * @return UserGroupManager
	 */
	public function getUserGroupManager() : UserGroupManager {
		return $this->getService( 'UserGroupManager' );
	}

	/**
	 * @since 1.35
	 * @return UserGroupManagerFactory
	 */
	public function getUserGroupManagerFactory() : UserGroupManagerFactory {
		return $this->getService( 'UserGroupManagerFactory' );
	}

	/**
	 * @since 1.35
	 * @return UserNameUtils
	 */
	public function getUserNameUtils() : UserNameUtils {
		return $this->getService( 'UserNameUtils' );
	}

	/**
	 * @since 1.35
	 * @return UserOptionsLookup
	 */
	public function getUserOptionsLookup() : UserOptionsLookup {
		return $this->getService( 'UserOptionsLookup' );
	}

	/**
	 * @since 1.35
	 * @return UserOptionsManager
	 */
	public function getUserOptionsManager() : UserOptionsManager {
		return $this->getService( 'UserOptionsManager' );
	}

	/**
	 * @since 1.28
	 * @return VirtualRESTServiceClient
	 */
	public function getVirtualRESTServiceClient() : VirtualRESTServiceClient {
		return $this->getService( 'VirtualRESTServiceClient' );
	}

	/**
	 * @since 1.28
	 * @return WatchedItemQueryService
	 */
	public function getWatchedItemQueryService() : WatchedItemQueryService {
		return $this->getService( 'WatchedItemQueryService' );
	}

	/**
	 * @since 1.28
	 * @return WatchedItemStoreInterface
	 */
	public function getWatchedItemStore() : WatchedItemStoreInterface {
		return $this->getService( 'WatchedItemStore' );
	}

	/**
	 * @since 1.35
	 * @return WatchlistNotificationManager
	 */
	public function getWatchlistNotificationManager() : WatchlistNotificationManager {
		return $this->getService( 'WatchlistNotificationManager' );
	}

	/**
	 * @since 1.31
	 * @return OldRevisionImporter
	 */
	public function getWikiRevisionOldRevisionImporter() : OldRevisionImporter {
		return $this->getService( 'OldRevisionImporter' );
	}

	/**
	 * @since 1.31
	 * @return OldRevisionImporter
	 */
	public function getWikiRevisionOldRevisionImporterNoUpdates() : OldRevisionImporter {
		return $this->getService( 'WikiRevisionOldRevisionImporterNoUpdates' );
	}

	/**
	 * @since 1.31
	 * @return UploadRevisionImporter
	 */
	public function getWikiRevisionUploadImporter() : UploadRevisionImporter {
		return $this->getService( 'UploadRevisionImporter' );
	}

}
