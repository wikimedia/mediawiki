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

namespace MediaWiki\User\Options;

use InvalidArgumentException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Language\LanguageCode;
use MediaWiki\Language\LanguageConverter;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserNameUtils;
use MediaWiki\User\UserTimeCorrection;
use Psr\Log\LoggerInterface;
use Wikimedia\ObjectFactory\ObjectFactory;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * A service class to control user options
 * @since 1.35
 * @ingroup User
 */
class UserOptionsManager extends UserOptionsLookup {

	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::HiddenPrefs,
		MainConfigNames::LocalTZoffset,
	];

	/**
	 * @since 1.39.5, 1.40
	 */
	public const MAX_BYTES_OPTION_VALUE = 65530;

	/**
	 * If the option was set globally, ignore the update.
	 * @since 1.43
	 */
	public const GLOBAL_IGNORE = 'ignore';

	/**
	 * If the option was set globally, add a local override.
	 * @since 1.43
	 */
	public const GLOBAL_OVERRIDE = 'override';

	/**
	 * If the option was set globally, update the global value.
	 * @since 1.43
	 */
	public const GLOBAL_UPDATE = 'update';

	/**
	 * Create a new global preference in the first available global store.
	 * If there are no global stores, update the local value. If there was
	 * already a global preference, update it.
	 * @since 1.44
	 */
	public const GLOBAL_CREATE = 'create';

	private const LOCAL_STORE_KEY = 'local';

	private ServiceOptions $serviceOptions;
	private DefaultOptionsLookup $defaultOptionsLookup;
	private LanguageConverterFactory $languageConverterFactory;
	private IConnectionProvider $dbProvider;
	private UserFactory $userFactory;
	private LoggerInterface $logger;
	private HookRunner $hookRunner;
	private UserNameUtils $userNameUtils;
	private array $storeProviders;

	private ObjectFactory $objectFactory;

	/** @var UserOptionsCacheEntry[] */
	private $cache = [];

	/** @var UserOptionsStore[]|null */
	private $stores;

	/**
	 * @param ServiceOptions $options
	 * @param DefaultOptionsLookup $defaultOptionsLookup
	 * @param LanguageConverterFactory $languageConverterFactory
	 * @param IConnectionProvider $dbProvider
	 * @param LoggerInterface $logger
	 * @param HookContainer $hookContainer
	 * @param UserFactory $userFactory
	 * @param UserNameUtils $userNameUtils
	 * @param ObjectFactory $objectFactory
	 * @param array $storeProviders
	 */
	public function __construct(
		ServiceOptions $options,
		DefaultOptionsLookup $defaultOptionsLookup,
		LanguageConverterFactory $languageConverterFactory,
		IConnectionProvider $dbProvider,
		LoggerInterface $logger,
		HookContainer $hookContainer,
		UserFactory $userFactory,
		UserNameUtils $userNameUtils,
		ObjectFactory $objectFactory,
		array $storeProviders
	) {
		parent::__construct( $userNameUtils );
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->serviceOptions = $options;
		$this->defaultOptionsLookup = $defaultOptionsLookup;
		$this->languageConverterFactory = $languageConverterFactory;
		$this->dbProvider = $dbProvider;
		$this->logger = $logger;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->userFactory = $userFactory;
		$this->userNameUtils = $userNameUtils;
		$this->objectFactory = $objectFactory;
		$this->storeProviders = $storeProviders;
	}

	/**
	 * @inheritDoc
	 */
	public function getDefaultOptions( ?UserIdentity $userIdentity = null ): array {
		return $this->defaultOptionsLookup->getDefaultOptions( $userIdentity );
	}

	/**
	 * @inheritDoc
	 */
	public function getDefaultOption( string $opt, ?UserIdentity $userIdentity = null ) {
		return $this->defaultOptionsLookup->getDefaultOption( $opt, $userIdentity );
	}

	/**
	 * @inheritDoc
	 */
	public function getOption(
		UserIdentity $user,
		string $oname,
		$defaultOverride = null,
		bool $ignoreHidden = false,
		int $queryFlags = IDBAccessObject::READ_NORMAL
	) {
		# We want 'disabled' preferences to always behave as the default value for
		# users, even if they have set the option explicitly in their settings (ie they
		# set it, and then it was disabled removing their ability to change it).  But
		# we don't want to erase the preferences in the database in case the preference
		# is re-enabled again.  So don't touch $mOptions, just override the returned value
		if ( !$ignoreHidden && in_array( $oname, $this->serviceOptions->get( MainConfigNames::HiddenPrefs ) ) ) {
			return $this->defaultOptionsLookup->getDefaultOption( $oname, $user );
		}

		$options = $this->loadUserOptions( $user, $queryFlags );
		if ( array_key_exists( $oname, $options ) ) {
			return $options[$oname];
		}
		return $defaultOverride;
	}

	/**
	 * @inheritDoc
	 */
	public function getOptions(
		UserIdentity $user,
		int $flags = 0,
		int $queryFlags = IDBAccessObject::READ_NORMAL
	): array {
		$options = $this->loadUserOptions( $user, $queryFlags );

		# We want 'disabled' preferences to always behave as the default value for
		# users, even if they have set the option explicitly in their settings (ie they
		# set it, and then it was disabled removing their ability to change it).  But
		# we don't want to erase the preferences in the database in case the preference
		# is re-enabled again.  So don't touch $mOptions, just override the returned value
		foreach ( $this->serviceOptions->get( MainConfigNames::HiddenPrefs ) as $pref ) {
			$default = $this->defaultOptionsLookup->getDefaultOption( $pref, $user );
			if ( $default !== null ) {
				$options[$pref] = $default;
			}
		}

		if ( $flags & self::EXCLUDE_DEFAULTS ) {
			// NOTE: This intentionally ignores conditional defaults, so that `mw.user.options`
			// work correctly for options with conditional defaults.
			$defaultOptions = $this->defaultOptionsLookup->getDefaultOptions( null );
			foreach ( $options as $option => $value ) {
				if ( array_key_exists( $option, $defaultOptions )
					&& self::isValueEqual( $value, $defaultOptions[$option] )
				) {
					unset( $options[$option] );
				}
			}
		}

		return $options;
	}

	public function isOptionGlobal( UserIdentity $user, string $key ) {
		$this->getOptions( $user );
		$source = $this->cache[ $this->getCacheKey( $user ) ]->sources[$key] ?? self::LOCAL_STORE_KEY;
		return $source !== self::LOCAL_STORE_KEY;
	}

	public function getOptionBatchForUserNames( array $users, string $key ) {
		if ( !$users ) {
			return [];
		}

		$exceptionKey = $key . self::LOCAL_EXCEPTION_SUFFIX;
		$results = [];
		$stores = $this->getStores();
		foreach ( $stores as $storeName => $store ) {
			// Check the exception key in the local store, if there is more than one store
			if ( count( $stores ) > 1 && $storeName === self::LOCAL_STORE_KEY ) {
				$storeResults = $store->fetchBatchForUserNames( [ $key, $exceptionKey ], $users );
				$values = $storeResults[$key] ?? [];
				$exceptions = $storeResults[$exceptionKey] ?? [];
				foreach ( $values as $userName => $value ) {
					if ( !empty( $exceptions[$userName] ) || !isset( $results[$userName] ) ) {
						$results[$userName] = $value;
					}
				}
			} else {
				$storeResults = $store->fetchBatchForUserNames( [ $key ], $users );
				$results += $storeResults[$key] ?? [];
			}
		}

		// If $key has a conditional default, DefaultOptionsLookup will be expensive,
		// so it makes sense to only ask for the users without an option set.
		$usersNeedingDefaults = array_diff( $users, array_keys( $results ) );
		if ( $usersNeedingDefaults ) {
			$defaults = $this->defaultOptionsLookup->getOptionBatchForUserNames( $usersNeedingDefaults, $key );
			$results += $defaults;
		}

		return $results;
	}

	/**
	 * Set the given option for a user.
	 *
	 * You need to call saveOptions() to actually write to the database.
	 *
	 * $val should be null or a string. Other types are accepted for B/C with legacy
	 * code but can result in surprising behavior and are discouraged. Values are always
	 * stored as strings in the database, so if you pass a non-string value, it will be
	 * eventually converted; but before the call to saveOptions(), getOption() will return
	 * the passed value from instance cache without any type conversion.
	 *
	 * A null value means resetting the option to its default value (removing the user_properties
	 * row). Passing in the same value as the default value fo the user has the same result.
	 * This behavior supports some level of type juggling - e.g. if the default value is 1,
	 * and you pass in '1', the option will be reset to its default value.
	 *
	 * When an option is reset to its default value, that means whenever the default value
	 * is changed in the site configuration, the user preference for this user will also change.
	 * There is no way to set a user preference to be the same as the default but avoid it
	 * changing when the default changes. You can instead use $wgConditionalUserOptions to
	 * split the default based on user registration date.
	 *
	 * If a global user option exists with the given name, the behaviour depends on the value
	 * of $global.
	 *
	 * @param UserIdentity $user
	 * @param string $oname The option to set
	 * @param mixed $val New value to set.
	 * @param string $global Since 1.43. The global update behaviour, used if
	 *   GlobalPreferences is installed:
	 *   - GLOBAL_IGNORE: If there is a global preference, do nothing. The option remains with
	 *     its previous value.
	 *   - GLOBAL_OVERRIDE: If there is a global preference, add a local override.
	 *   - GLOBAL_UPDATE: If there is a global preference, update it.
	 *   - GLOBAL_CREATE: Create a new global preference, overriding any local value.
	 *   The UI should typically ask for the user's consent before setting a global
	 *   option.
	 */
	public function setOption( UserIdentity $user, string $oname, $val,
		$global = self::GLOBAL_IGNORE
	) {
		// Explicitly NULL values should refer to defaults
		$val ??= $this->defaultOptionsLookup->getDefaultOption( $oname, $user );
		$userKey = $this->getCacheKey( $user );
		$info = $this->cache[$userKey] ??= new UserOptionsCacheEntry;
		$info->modifiedValues[$oname] = $val;
		$info->globalUpdateActions[$oname] = $global;
	}

	/**
	 * Reset a list of options to the site defaults
	 *
	 * @note You need to call saveOptions() to actually write to the database.
	 *
	 * @param UserIdentity $user
	 * @param string[] $optionNames
	 */
	public function resetOptionsByName(
		UserIdentity $user,
		array $optionNames
	) {
		foreach ( $optionNames as $name ) {
			$this->setOption( $user, $name, null );
		}
	}

	/**
	 * Reset all options that were set to a non-default value by the given user
	 *
	 * @note You need to call saveOptions() to actually write to the database.
	 *
	 * @param UserIdentity $user
	 */
	public function resetAllOptions( UserIdentity $user ) {
		foreach ( $this->loadUserOptions( $user ) as $name => $value ) {
			$this->setOption( $user, $name, null );
		}
	}

	/**
	 * Saves the non-default options for this user, as previously set e.g. via
	 * setOption(), in the database's "user_properties" (preferences) table.
	 *
	 * @since 1.38, this method was internal before that.
	 * @param UserIdentity $user
	 */
	public function saveOptions( UserIdentity $user ) {
		$dbw = $this->dbProvider->getPrimaryDatabase();
		$changed = $this->saveOptionsInternal( $user );
		$legacyUser = $this->userFactory->newFromUserIdentity( $user );
		// Before UserOptionsManager, User::saveSettings was used for user options
		// saving. Some extensions might depend on UserSaveSettings hook being run
		// when options are saved, so run this hook for legacy reasons.
		// Once UserSaveSettings hook is deprecated and replaced with a different hook
		// with more modern interface, extensions should use 'SaveUserOptions' hook.
		$this->hookRunner->onUserSaveSettings( $legacyUser );
		if ( $changed ) {
			$dbw->onTransactionCommitOrIdle( static function () use ( $legacyUser ) {
				$legacyUser->checkAndSetTouched();
			}, __METHOD__ );
		}
	}

	/**
	 * Saves the non-default options for this user, as previously set e.g. via
	 * setOption(), in the database's "user_properties" (preferences) table.
	 *
	 * @param UserIdentity $user
	 * @return bool true if options were changed and new options successfully saved.
	 * @internal only public for use in User::saveSettings
	 */
	public function saveOptionsInternal( UserIdentity $user ): bool {
		if ( $this->userNameUtils->isIP( $user->getName() ) || $this->userNameUtils->isTemp( $user->getName() ) ) {
			throw new InvalidArgumentException( __METHOD__ . ' was called on IP or temporary user' );
		}

		$userKey = $this->getCacheKey( $user );
		$cache = $this->cache[$userKey] ?? new UserOptionsCacheEntry;
		$modifiedOptions = $cache->modifiedValues;

		// FIXME: should probably use READ_LATEST here
		$originalOptions = $this->loadOriginalOptions( $user );

		if ( !$this->hookRunner->onSaveUserOptions( $user, $modifiedOptions, $originalOptions ) ) {
			return false;
		}

		$updatesByStore = [];
		foreach ( $modifiedOptions as $key => $value ) {
			// Don't store unchanged or default values
			$defaultValue = $this->defaultOptionsLookup->getDefaultOption( $key, $user );
			if ( $value === null || self::isValueEqual( $value, $defaultValue ) ) {
				$valOrNull = null;
			} else {
				$valOrNull = (string)$value;
			}
			$source = $cache->sources[$key] ?? self::LOCAL_STORE_KEY;
			$updateAction = $cache->globalUpdateActions[$key] ?? self::GLOBAL_IGNORE;

			if ( $source === self::LOCAL_STORE_KEY ) {
				if ( $updateAction === self::GLOBAL_CREATE ) {
					$updatesByStore[$this->getStoreNameForGlobalCreate()][$key] = $valOrNull;
				} else {
					$updatesByStore[self::LOCAL_STORE_KEY][$key] = $valOrNull;
				}
			} else {
				if ( $updateAction === self::GLOBAL_UPDATE || $updateAction === self::GLOBAL_CREATE ) {
					$updatesByStore[$source][$key] = $valOrNull;
				} elseif ( $updateAction === self::GLOBAL_OVERRIDE ) {
					$updatesByStore[self::LOCAL_STORE_KEY][$key] = $valOrNull;
					$updatesByStore[self::LOCAL_STORE_KEY][$key . self::LOCAL_EXCEPTION_SUFFIX] = '1';
				}
			}
		}
		$changed = false;
		$stores = $this->getStores();
		foreach ( $updatesByStore as $source => $updates ) {
			$changed = $stores[$source]->store( $user, $updates ) || $changed;
		}

		if ( !$changed ) {
			return false;
		}

		// Clear the cache and the update queue
		unset( $this->cache[$userKey] );
		return true;
	}

	/**
	 * Loads user options either from cache or from the database.
	 *
	 * @note Query flags are ignored for anons, since they do not have any
	 * options stored in the database. If the UserIdentity was itself
	 * obtained from a replica and doesn't have ID set due to replication lag,
	 * it will be treated as anon regardless of the query flags passed here.
	 *
	 * @internal
	 *
	 * @param UserIdentity $user
	 * @param int $queryFlags
	 * @return array
	 */
	public function loadUserOptions(
		UserIdentity $user,
		int $queryFlags = IDBAccessObject::READ_NORMAL
	): array {
		$userKey = $this->getCacheKey( $user );
		$originalOptions = $this->loadOriginalOptions( $user, $queryFlags );
		$cache = $this->cache[$userKey] ?? null;
		if ( $cache ) {
			return array_merge( $originalOptions, $cache->modifiedValues );
		} else {
			return $originalOptions;
		}
	}

	/**
	 * Clears cached user options.
	 * @internal To be used by User::clearInstanceCache
	 * @param UserIdentity $user
	 */
	public function clearUserOptionsCache( UserIdentity $user ) {
		unset( $this->cache[ $this->getCacheKey( $user ) ] );
	}

	/**
	 * Fetches the options directly from the database with no caches.
	 *
	 * @param UserIdentity $user
	 * @param int $queryFlags a bit field composed of READ_XXX flags
	 * @return array
	 */
	private function loadOptionsFromStore(
		UserIdentity $user,
		int $queryFlags
	): array {
		$this->logger->debug( 'Loading options from database',
			[ 'user_id' => $user->getId(), 'user_name' => $user->getName() ] );
		$mergedOptions = [];
		$cache = $this->cache[ $this->getCacheKey( $user ) ] ??= new UserOptionsCacheEntry;
		foreach ( $this->getStores() as $storeName => $store ) {
			$options = $store->fetch( $user, $queryFlags );
			foreach ( $options as $name => $value ) {
				// Handle a local exception which is the default
				if ( str_ends_with( $name, self::LOCAL_EXCEPTION_SUFFIX ) && $value ) {
					$baseName = substr( $name, 0, -strlen( self::LOCAL_EXCEPTION_SUFFIX ) );
					if ( !isset( $options[$baseName] ) ) {
						// T368595: The source should always be set to local for local exceptions
						$cache->sources[$baseName] = self::LOCAL_STORE_KEY;
						unset( $mergedOptions[$baseName] );
					}
				}

				// Handle a non-default option or non-default local exception
				if ( !isset( $mergedOptions[$name] )
					|| !empty( $options[$name . self::LOCAL_EXCEPTION_SUFFIX] )
				) {
					$cache->sources[$name] = $storeName;
					$mergedOptions[$name] = $this->normalizeValueType( $value );
				}
			}
		}
		return $mergedOptions;
	}

	/**
	 * Convert '0' to 0. PHP's boolean conversion considers them both
	 * false, but e.g. JavaScript considers the former as true.
	 *
	 * @todo T54542 Somehow determine the desired type (string/int/bool)
	 *   and convert all values here.
	 *
	 * @param string $value
	 * @return mixed
	 */
	private function normalizeValueType( $value ) {
		if ( $value === '0' ) {
			$value = 0;
		}
		return $value;
	}

	/**
	 * Loads the original user options from the database and applies various transforms,
	 * like timecorrection. Runs hooks.
	 *
	 * @param UserIdentity $user
	 * @param int $queryFlags
	 * @return array
	 */
	private function loadOriginalOptions(
		UserIdentity $user,
		int $queryFlags = IDBAccessObject::READ_NORMAL
	): array {
		$userKey = $this->getCacheKey( $user );
		$cache = $this->cache[$userKey] ??= new UserOptionsCacheEntry;

		// In case options were already loaded from the database before and no options
		// changes were saved to the database, we can use the cached original options.
		if ( $cache->canUseCachedValues( $queryFlags )
			&& $cache->originalValues !== null
		) {
			return $cache->originalValues;
		}

		$defaultOptions = $this->defaultOptionsLookup->getDefaultOptions( $user );

		if ( $this->userNameUtils->isIP( $user->getName() ) || $this->userNameUtils->isTemp( $user->getName() ) ) {
			// For unlogged-in users, load language/variant options from request.
			// There's no need to do it for logged-in users: they can set preferences,
			// and handling of page content is done by $pageLang->getPreferredVariant() and such,
			// so don't override user's choice (especially when the user chooses site default).
			$variant = $this->languageConverterFactory->getLanguageConverter()->getDefaultVariant();
			$defaultOptions['variant'] = $variant;
			$defaultOptions['language'] = $variant;
			$cache->originalValues = $defaultOptions;
			return $defaultOptions;
		}

		$options = $this->loadOptionsFromStore( $user, $queryFlags ) + $defaultOptions;

		// Replace deprecated language codes
		$options['language'] = LanguageCode::replaceDeprecatedCodes( $options['language'] );
		$options['variant'] = LanguageCode::replaceDeprecatedCodes( $options['variant'] );
		foreach ( LanguageConverter::$languagesWithVariants as $langCode ) {
			$variant = "variant-$langCode";
			if ( isset( $options[$variant] ) ) {
				$options[$variant] = LanguageCode::replaceDeprecatedCodes( $options[$variant] );
			}
		}

		// Fix up timezone offset (Due to DST it can change from what was stored in the DB)
		// ZoneInfo|offset|TimeZoneName
		if ( isset( $options['timecorrection'] ) ) {
			$options['timecorrection'] = ( new UserTimeCorrection(
				$options['timecorrection'],
				null,
				$this->serviceOptions->get( MainConfigNames::LocalTZoffset )
			) )->toString();
		}

		// Need to store what we have so far before the hook to prevent
		// infinite recursion if the hook attempts to reload options
		$cache->originalValues = $options;
		$cache->recency = $queryFlags;
		$this->hookRunner->onLoadUserOptions( $user, $options );
		$cache->originalValues = $options;
		return $options;
	}

	/**
	 * Determines whether two values are sufficiently similar that the database
	 * does not need to be updated to reflect the change. This is basically the
	 * same as comparing the result of Database::addQuotes().
	 *
	 * @since 1.43
	 *
	 * @param mixed $a
	 * @param mixed $b
	 * @return bool
	 */
	public static function isValueEqual( $a, $b ) {
		// null is only equal to another null (T355086)
		if ( $a === null || $b === null ) {
			return $a === $b;
		}

		if ( is_bool( $a ) ) {
			$a = (int)$a;
		}
		if ( is_bool( $b ) ) {
			$b = (int)$b;
		}
		return (string)$a === (string)$b;
	}

	/**
	 * Get the storage backends in descending order of priority
	 *
	 * @return UserOptionsStore[]
	 */
	private function getStores() {
		if ( !$this->stores ) {
			$stores = [
				self::LOCAL_STORE_KEY => new LocalUserOptionsStore( $this->dbProvider, $this->hookRunner )
			];
			foreach ( $this->storeProviders as $name => $spec ) {
				$store = $this->objectFactory->createObject( $spec );
				if ( !$store instanceof UserOptionsStore ) {
					throw new \RuntimeException( "Invalid type for extension store \"$name\"" );
				}
				$stores[$name] = $store;
			}
			// Query global providers first, preserve keys
			$this->stores = array_reverse( $stores, true );
		}
		return $this->stores;
	}

	/**
	 * Get the name of the store to be used when setOption() is called with
	 * GLOBAL_CREATE and there is no existing global preference value.
	 *
	 * @return string
	 */
	private function getStoreNameForGlobalCreate() {
		foreach ( $this->getStores() as $name => $store ) {
			if ( $name !== self::LOCAL_STORE_KEY ) {
				return $name;
			}
		}
		return self::LOCAL_STORE_KEY;
	}
}

/** @deprecated class alias since 1.42 */
class_alias( UserOptionsManager::class, 'MediaWiki\\User\\UserOptionsManager' );
