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

use DBAccessObjectUtils;
use IDBAccessObject;
use InvalidArgumentException;
use LanguageCode;
use LanguageConverter;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Context\IContextSource;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserNameUtils;
use MediaWiki\User\UserTimeCorrection;
use Psr\Log\LoggerInterface;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDatabase;

/**
 * A service class to control user options
 * @since 1.35
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

	private ServiceOptions $serviceOptions;
	private DefaultOptionsLookup $defaultOptionsLookup;
	private LanguageConverterFactory $languageConverterFactory;
	private IConnectionProvider $dbProvider;
	private UserFactory $userFactory;
	private LoggerInterface $logger;

	/** @var array options modified within this request */
	private $modifiedOptions = [];

	/**
	 * @var array Cached original user options with all the adjustments
	 *            like time correction and hook changes applied.
	 *            Ready to be returned.
	 */
	private $originalOptionsCache = [];

	/**
	 * @var array Cached original user options as fetched from database,
	 *            no adjustments applied.
	 */
	private $optionsFromDb = [];

	private HookRunner $hookRunner;

	/** @var array Query flags used to retrieve options from database */
	private $queryFlagsUsedForCaching = [];

	private UserNameUtils $userNameUtils;

	/**
	 * @param ServiceOptions $options
	 * @param DefaultOptionsLookup $defaultOptionsLookup
	 * @param LanguageConverterFactory $languageConverterFactory
	 * @param IConnectionProvider $dbProvider
	 * @param LoggerInterface $logger
	 * @param HookContainer $hookContainer
	 * @param UserFactory $userFactory
	 * @param UserNameUtils $userNameUtils
	 */
	public function __construct(
		ServiceOptions $options,
		DefaultOptionsLookup $defaultOptionsLookup,
		LanguageConverterFactory $languageConverterFactory,
		IConnectionProvider $dbProvider,
		LoggerInterface $logger,
		HookContainer $hookContainer,
		UserFactory $userFactory,
		UserNameUtils $userNameUtils
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->serviceOptions = $options;
		$this->defaultOptionsLookup = $defaultOptionsLookup;
		$this->languageConverterFactory = $languageConverterFactory;
		$this->dbProvider = $dbProvider;
		$this->logger = $logger;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->userFactory = $userFactory;
		$this->userNameUtils = $userNameUtils;
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
					&& $this->isValueEqual( $value, $defaultOptions[$option] )
				) {
					unset( $options[$option] );
				}
			}
		}

		return $options;
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
	 * @param UserIdentity $user
	 * @param string $oname The option to set
	 * @param mixed $val New value to set.
	 */
	public function setOption( UserIdentity $user, string $oname, $val ) {
		// Explicitly NULL values should refer to defaults
		if ( $val === null ) {
			$val = $this->defaultOptionsLookup->getDefaultOption( $oname, $user );
		}
		$this->modifiedOptions[$this->getCacheKey( $user )][$oname] = $val;
	}

	/**
	 * Reset certain (or all) options to the site defaults
	 *
	 * The optional parameter determines which kinds of preferences will be reset.
	 * Supported values are everything that can be reported by getOptionKinds()
	 * and 'all', which forces a reset of *all* preferences and overrides everything else.
	 *
	 * @note You need to call saveOptions() to actually write to the database.
	 *
	 * @deprecated since 1.43 use resetOptionsByName() with PreferencesFactory::getOptionNamesForReset()
	 *
	 * @param UserIdentity $user
	 * @param IContextSource $context Context source used when $resetKinds does not contain 'all'.
	 * @param array|string $resetKinds Which kinds of preferences to reset.
	 *  Defaults to [ 'registered', 'registered-multiselect', 'registered-checkmatrix', 'unused' ]
	 */
	public function resetOptions(
		UserIdentity $user,
		IContextSource $context,
		$resetKinds = [ 'registered', 'registered-multiselect', 'registered-checkmatrix', 'unused' ]
	) {
		wfDeprecated( __METHOD__, '1.43' );
		$preferencesFactory = MediaWikiServices::getInstance()->getPreferencesFactory();
		$optionsToReset = $preferencesFactory->getOptionNamesForReset(
			$this->userFactory->newFromUserIdentity( $user ), $context, $resetKinds );
		$this->resetOptionsByName( $user, $optionsToReset );
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
	 * @deprecated since 1.43 use PreferencesFactory::listResetKinds()
	 *
	 * @return string[] Option kinds
	 */
	public function listOptionKinds(): array {
		wfDeprecated( __METHOD__, '1.43' );
		$preferencesFactory = MediaWikiServices::getInstance()->getPreferencesFactory();
		return $preferencesFactory->listResetKinds();
	}

	/**
	 * @deprecated since 1.43 use PreferencesFactory::getResetKinds
	 *
	 * @param UserIdentity $userIdentity
	 * @param IContextSource $context
	 * @param array|null $options
	 * @return string[]
	 */
	public function getOptionKinds(
		UserIdentity $userIdentity,
		IContextSource $context,
		$options = null
	): array {
		wfDeprecated( __METHOD__, '1.43' );
		$user = $this->userFactory->newFromUserIdentity( $userIdentity );
		$preferencesFactory = MediaWikiServices::getInstance()->getPreferencesFactory();
		return $preferencesFactory->getResetKinds( $user, $context, $options );
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
		$changed = $this->saveOptionsInternal( $user, $dbw );
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
	 * @param IDatabase $dbw
	 * @return bool true if options were changed and new options successfully saved.
	 * @internal only public for use in User::saveSettings
	 */
	public function saveOptionsInternal( UserIdentity $user, IDatabase $dbw ): bool {
		if ( !$user->isRegistered() || $this->userNameUtils->isTemp( $user->getName() ) ) {
			throw new InvalidArgumentException( __METHOD__ . ' was called on anon or temporary user' );
		}

		$userKey = $this->getCacheKey( $user );
		$modifiedOptions = $this->modifiedOptions[$userKey] ?? [];
		$originalOptions = $this->loadOriginalOptions( $user );
		if ( !$this->hookRunner->onSaveUserOptions( $user, $modifiedOptions, $originalOptions ) ) {
			return false;
		}

		$rowsToInsert = [];
		$keysToDelete = [];
		foreach ( $modifiedOptions as $key => $value ) {
			// Don't store unchanged or default values
			$defaultValue = $this->defaultOptionsLookup->getDefaultOption( $key, $user );
			$oldDbValue = $this->optionsFromDb[$userKey][$key] ?? null;
			if ( $value === null || $this->isValueEqual( $value, $defaultValue ) ) {
				if ( array_key_exists( $key, $this->optionsFromDb[$userKey] ) ) {
					// Delete the default value from the database
					$keysToDelete[] = $key;
				}
			} elseif ( !$this->isValueEqual( $value, $oldDbValue ) ) {
				// Update by deleting (if old value exists) and reinserting
				$rowsToInsert[] = [
					'up_user' => $user->getId(),
					'up_property' => $key,
					'up_value' => mb_strcut( $value, 0, self::MAX_BYTES_OPTION_VALUE ),
				];
				if ( array_key_exists( $key, $this->optionsFromDb[$userKey] ) ) {
					$keysToDelete[] = $key;
				}
			}
		}

		if ( !count( $keysToDelete ) && !count( $rowsToInsert ) ) {
			// Nothing to do
			return false;
		}

		// Do the DELETE
		if ( $keysToDelete ) {
			$dbw->newDeleteQueryBuilder()
				->deleteFrom( 'user_properties' )
				->where( [ 'up_user' => $user->getId() ] )
				->andWhere( [ 'up_property' => $keysToDelete ] )
				->caller( __METHOD__ )->execute();
		}
		if ( $rowsToInsert ) {
			// Insert the new preference rows
			$dbw->newInsertQueryBuilder()
				->insertInto( 'user_properties' )
				->ignore()
				->rows( $rowsToInsert )
				->caller( __METHOD__ )->execute();
		}

		// It's pretty cheap to recalculate new original later
		// to apply whatever adjustments we apply when fetching from DB
		// and re-merge with the defaults.
		unset( $this->originalOptionsCache[$userKey] );
		// And nothing is modified anymore
		unset( $this->modifiedOptions[$userKey] );
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
	 * @param UserIdentity $user
	 * @param int $queryFlags
	 * @param array|null $data associative array of non-default options.
	 * @return array
	 * @internal To be called by User loading code to provide the $data
	 */
	public function loadUserOptions(
		UserIdentity $user,
		int $queryFlags = IDBAccessObject::READ_NORMAL,
		array $data = null
	): array {
		$userKey = $this->getCacheKey( $user );
		$originalOptions = $this->loadOriginalOptions( $user, $queryFlags, $data );
		return array_merge( $originalOptions, $this->modifiedOptions[$userKey] ?? [] );
	}

	/**
	 * Clears cached user options.
	 * @internal To be used by User::clearInstanceCache
	 * @param UserIdentity $user
	 */
	public function clearUserOptionsCache( UserIdentity $user ) {
		$cacheKey = $this->getCacheKey( $user );
		unset( $this->modifiedOptions[$cacheKey] );
		unset( $this->optionsFromDb[$cacheKey] );
		unset( $this->originalOptionsCache[$cacheKey] );
		unset( $this->queryFlagsUsedForCaching[$cacheKey] );
	}

	/**
	 * Fetches the options directly from the database with no caches.
	 *
	 * @param UserIdentity $user
	 * @param int $queryFlags a bit field composed of READ_XXX flags
	 * @param array|null $prefetchedOptions
	 * @return array
	 */
	private function loadOptionsFromDb(
		UserIdentity $user,
		int $queryFlags,
		array $prefetchedOptions = null
	): array {
		if ( $prefetchedOptions === null ) {
			$this->logger->debug( 'Loading options from database', [ 'user_id' => $user->getId() ] );
			$dbr = DBAccessObjectUtils::getDBFromRecency( $this->dbProvider, $queryFlags );
			$res = $dbr->newSelectQueryBuilder()
				->select( [ 'up_property', 'up_value' ] )
				->from( 'user_properties' )
				->where( [ 'up_user' => $user->getId() ] )
				->recency( $queryFlags )
				->caller( __METHOD__ )->fetchResultSet();
		} else {
			$res = [];
			foreach ( $prefetchedOptions as $name => $value ) {
				$res[] = [
					'up_property' => $name,
					'up_value' => $value,
				];
			}
		}
		return $this->setOptionsFromDb( $user, $queryFlags, $res );
	}

	/**
	 * Builds associative options array from rows fetched from DB.
	 *
	 * @param UserIdentity $user
	 * @param int $queryFlags
	 * @param iterable<object|array> $rows
	 * @return array
	 */
	private function setOptionsFromDb(
		UserIdentity $user,
		int $queryFlags,
		iterable $rows
	): array {
		$userKey = $this->getCacheKey( $user );
		$options = [];
		foreach ( $rows as $row ) {
			$row = (object)$row;
			// Convert '0' to 0. PHP's boolean conversion considers them both
			// false, but e.g. JavaScript considers the former as true.
			// @todo: T54542 Somehow determine the desired type (string/int/bool)
			//  and convert all values here.
			if ( $row->up_value === '0' ) {
				$row->up_value = 0;
			}
			$options[$row->up_property] = $row->up_value;
		}
		$this->optionsFromDb[$userKey] = $options;
		$this->queryFlagsUsedForCaching[$userKey] = $queryFlags;
		return $options;
	}

	/**
	 * Loads the original user options from the database and applies various transforms,
	 * like timecorrection. Runs hooks.
	 *
	 * @param UserIdentity $user
	 * @param int $queryFlags
	 * @param array|null $data associative array of non-default options
	 * @return array
	 */
	private function loadOriginalOptions(
		UserIdentity $user,
		int $queryFlags = IDBAccessObject::READ_NORMAL,
		array $data = null
	): array {
		$userKey = $this->getCacheKey( $user );
		$defaultOptions = $this->defaultOptionsLookup->getDefaultOptions( $user );
		if ( !$user->isRegistered() || $this->userNameUtils->isTemp( $user->getName() ) ) {
			// For unlogged-in users, load language/variant options from request.
			// There's no need to do it for logged-in users: they can set preferences,
			// and handling of page content is done by $pageLang->getPreferredVariant() and such,
			// so don't override user's choice (especially when the user chooses site default).
			$variant = $this->languageConverterFactory->getLanguageConverter()->getDefaultVariant();
			$defaultOptions['variant'] = $variant;
			$defaultOptions['language'] = $variant;
			$this->originalOptionsCache[$userKey] = $defaultOptions;
			return $defaultOptions;
		}

		// In case options were already loaded from the database before and no options
		// changes were saved to the database, we can use the cached original options.
		if ( $this->canUseCachedValues( $user, $queryFlags )
			&& isset( $this->originalOptionsCache[$userKey] )
		) {
			return $this->originalOptionsCache[$userKey];
		}

		$options = $this->loadOptionsFromDb( $user, $queryFlags, $data ) + $defaultOptions;

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
		$this->originalOptionsCache[$userKey] = $options;
		$this->queryFlagsUsedForCaching[$userKey] = $queryFlags;
		$this->hookRunner->onLoadUserOptions( $user, $options );
		$this->originalOptionsCache[$userKey] = $options;
		return $options;
	}

	/**
	 * Gets a key for various caches.
	 * @param UserIdentity $user
	 * @return string
	 */
	private function getCacheKey( UserIdentity $user ): string {
		if ( !$user->isRegistered() || $this->userNameUtils->isTemp( $user->getName() ) ) {
			return 'anon';
		} else {
			return "u:{$user->getId()}";
		}
	}

	/**
	 * Determines if it's ok to use cached options values for a given user and query flags
	 * @param UserIdentity $user
	 * @param int $queryFlags
	 * @return bool
	 */
	private function canUseCachedValues( UserIdentity $user, int $queryFlags ): bool {
		if ( !$user->isRegistered() || $this->userNameUtils->isTemp( $user->getName() ) ) {
			// Anon & temp users don't have options stored in the database,
			// so $queryFlags are ignored.
			return true;
		}
		$userKey = $this->getCacheKey( $user );
		$queryFlagsUsed = $this->queryFlagsUsedForCaching[$userKey] ?? IDBAccessObject::READ_NONE;
		return $queryFlagsUsed >= $queryFlags;
	}

	/**
	 * Determines whether two values are sufficiently similar that the database
	 * does not need to be updated to reflect the change. This is basically the
	 * same as comparing the result of Database::addQuotes().
	 *
	 * @param mixed $a
	 * @param mixed $b
	 * @return bool
	 */
	private function isValueEqual( $a, $b ) {
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
}

/** @deprecated class alias since 1.41 */
class_alias( UserOptionsManager::class, 'MediaWiki\\User\\UserOptionsManager' );
