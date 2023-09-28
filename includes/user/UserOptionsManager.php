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

namespace MediaWiki\User;

use DBAccessObjectUtils;
use HTMLCheckMatrix;
use HTMLFormField;
use HTMLMultiSelectField;
use IContextSource;
use InvalidArgumentException;
use LanguageCode;
use LanguageConverter;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
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
	public function getDefaultOptions(): array {
		return $this->defaultOptionsLookup->getDefaultOptions();
	}

	/**
	 * @inheritDoc
	 */
	public function getOption(
		UserIdentity $user,
		string $oname,
		$defaultOverride = null,
		bool $ignoreHidden = false,
		int $queryFlags = self::READ_NORMAL
	) {
		# We want 'disabled' preferences to always behave as the default value for
		# users, even if they have set the option explicitly in their settings (ie they
		# set it, and then it was disabled removing their ability to change it).  But
		# we don't want to erase the preferences in the database in case the preference
		# is re-enabled again.  So don't touch $mOptions, just override the returned value
		if ( !$ignoreHidden && in_array( $oname, $this->serviceOptions->get( MainConfigNames::HiddenPrefs ) ) ) {
			return $this->defaultOptionsLookup->getDefaultOption( $oname );
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
		int $queryFlags = self::READ_NORMAL
	): array {
		$options = $this->loadUserOptions( $user, $queryFlags );

		# We want 'disabled' preferences to always behave as the default value for
		# users, even if they have set the option explicitly in their settings (ie they
		# set it, and then it was disabled removing their ability to change it).  But
		# we don't want to erase the preferences in the database in case the preference
		# is re-enabled again.  So don't touch $mOptions, just override the returned value
		foreach ( $this->serviceOptions->get( MainConfigNames::HiddenPrefs ) as $pref ) {
			$default = $this->defaultOptionsLookup->getDefaultOption( $pref );
			if ( $default !== null ) {
				$options[$pref] = $default;
			}
		}

		if ( $flags & self::EXCLUDE_DEFAULTS ) {
			$defaultOptions = $this->defaultOptionsLookup->getDefaultOptions();
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
	 * @param UserIdentity $user
	 * @param string $oname The option to set
	 * @param mixed $val New value to set
	 */
	public function setOption( UserIdentity $user, string $oname, $val ) {
		// Explicitly NULL values should refer to defaults
		if ( $val === null ) {
			$val = $this->defaultOptionsLookup->getDefaultOption( $oname );
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
		$oldOptions = $this->loadUserOptions( $user, self::READ_LATEST );
		$defaultOptions = $this->defaultOptionsLookup->getDefaultOptions();

		if ( !is_array( $resetKinds ) ) {
			$resetKinds = [ $resetKinds ];
		}

		if ( in_array( 'all', $resetKinds ) ) {
			$newOptions = $defaultOptions + array_fill_keys( array_keys( $oldOptions ), null );
		} else {
			$optionKinds = $this->getOptionKinds( $user, $context );
			$resetKinds = array_intersect( $resetKinds, $this->listOptionKinds() );
			$newOptions = [];

			// Use default values for the options that should be deleted, and
			// copy old values for the ones that shouldn't.
			foreach ( $oldOptions as $key => $value ) {
				if ( in_array( $optionKinds[$key], $resetKinds ) ) {
					if ( array_key_exists( $key, $defaultOptions ) ) {
						$newOptions[$key] = $defaultOptions[$key];
					}
				} else {
					$newOptions[$key] = $value;
				}
			}
		}
		$this->modifiedOptions[$this->getCacheKey( $user )] = $newOptions;
	}

	/**
	 * Return a list of the types of user options currently returned by
	 * UserOptionsManager::getOptionKinds().
	 *
	 * Currently, the option kinds are:
	 * - 'registered' - preferences which are registered in core MediaWiki or
	 *                  by extensions using the UserGetDefaultOptions hook.
	 * - 'registered-multiselect' - as above, using the 'multiselect' type.
	 * - 'registered-checkmatrix' - as above, using the 'checkmatrix' type.
	 * - 'userjs' - preferences with names starting with 'userjs-', intended to
	 *              be used by user scripts.
	 * - 'special' - "preferences" that are not accessible via
	 *              UserOptionsLookup::getOptions or UserOptionsManager::setOptions.
	 * - 'unused' - preferences about which MediaWiki doesn't know anything.
	 *              These are usually legacy options, removed in newer versions.
	 *
	 * The API (and possibly others) use this function to determine the possible
	 * option types for validation purposes, so make sure to update this when a
	 * new option kind is added.
	 *
	 * @see getOptionKinds
	 * @return string[] Option kinds
	 */
	public function listOptionKinds(): array {
		return [
			'registered',
			'registered-multiselect',
			'registered-checkmatrix',
			'userjs',
			'special',
			'unused'
		];
	}

	/**
	 * Return an associative array mapping preferences keys to the kind of a preference they're
	 * used for. Different kinds are handled differently when setting or reading preferences.
	 *
	 * See UserOptionsManager::listOptionKinds for the list of valid option types that can be provided.
	 *
	 * @see UserOptionsManager::listOptionKinds
	 * @param UserIdentity $userIdentity
	 * @param IContextSource $context
	 * @param array|null $options Assoc. array with options keys to check as keys.
	 *   Defaults user options.
	 * @return string[] The key => kind mapping data
	 */
	public function getOptionKinds(
		UserIdentity $userIdentity,
		IContextSource $context,
		$options = null
	): array {
		if ( $options === null ) {
			$options = $this->loadUserOptions( $userIdentity );
		}

		// TODO: injecting the preferences factory creates a cyclic dependency between
		// PreferencesFactory and UserOptionsManager. See T250822
		$preferencesFactory = MediaWikiServices::getInstance()->getPreferencesFactory();
		$user = $this->userFactory->newFromUserIdentity( $userIdentity );
		$prefs = $preferencesFactory->getFormDescriptor( $user, $context );
		$mapping = [];

		// Pull out the "special" options, so they don't get converted as
		// multiselect or checkmatrix.
		$specialOptions = array_fill_keys( $preferencesFactory->getSaveBlacklist(), true );
		foreach ( $specialOptions as $name => $value ) {
			unset( $prefs[$name] );
		}

		// Multiselect and checkmatrix options are stored in the database with
		// one key per option, each having a boolean value. Extract those keys.
		$multiselectOptions = [];
		foreach ( $prefs as $name => $info ) {
			if ( ( isset( $info['type'] ) && $info['type'] == 'multiselect' ) ||
				( isset( $info['class'] ) && $info['class'] == HTMLMultiSelectField::class )
			) {
				$opts = HTMLFormField::flattenOptions( $info['options'] ?? $info['options-messages'] );
				$prefix = $info['prefix'] ?? $name;

				foreach ( $opts as $value ) {
					$multiselectOptions["$prefix$value"] = true;
				}

				unset( $prefs[$name] );
			}
		}
		$checkmatrixOptions = [];
		foreach ( $prefs as $name => $info ) {
			if ( ( isset( $info['type'] ) && $info['type'] == 'checkmatrix' ) ||
				( isset( $info['class'] ) && $info['class'] == HTMLCheckMatrix::class )
			) {
				$columns = HTMLFormField::flattenOptions( $info['columns'] );
				$rows = HTMLFormField::flattenOptions( $info['rows'] );
				$prefix = $info['prefix'] ?? $name;

				foreach ( $columns as $column ) {
					foreach ( $rows as $row ) {
						$checkmatrixOptions["$prefix$column-$row"] = true;
					}
				}

				unset( $prefs[$name] );
			}
		}

		// $value is ignored
		foreach ( $options as $key => $value ) {
			if ( isset( $prefs[$key] ) ) {
				$mapping[$key] = 'registered';
			} elseif ( isset( $multiselectOptions[$key] ) ) {
				$mapping[$key] = 'registered-multiselect';
			} elseif ( isset( $checkmatrixOptions[$key] ) ) {
				$mapping[$key] = 'registered-checkmatrix';
			} elseif ( isset( $specialOptions[$key] ) ) {
				$mapping[$key] = 'special';
			} elseif ( str_starts_with( $key, 'userjs-' ) ) {
				$mapping[$key] = 'userjs';
			} else {
				$mapping[$key] = 'unused';
			}
		}

		return $mapping;
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
			$defaultValue = $this->defaultOptionsLookup->getDefaultOption( $key );
			$oldValue = $this->optionsFromDb[$userKey][$key] ?? null;
			if ( $value === null || $this->isValueEqual( $value, $defaultValue ) ) {
				if ( array_key_exists( $key, $this->optionsFromDb[$userKey] ) ) {
					// Delete the default value from the database
					$keysToDelete[] = $key;
				}
			} elseif ( !$this->isValueEqual( $value, $oldValue ) ) {
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
		int $queryFlags = self::READ_NORMAL,
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
	 * @param int $queryFlags
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
			[ $dbr, $options ] = $this->getDBAndOptionsForQueryFlags( $queryFlags );
			$res = $dbr->newSelectQueryBuilder()
				->select( [ 'up_property', 'up_value' ] )
				->from( 'user_properties' )
				->where( [ 'up_user' => $user->getId() ] )
				->options( $options )
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
		int $queryFlags = self::READ_NORMAL,
		array $data = null
	): array {
		$userKey = $this->getCacheKey( $user );
		$defaultOptions = $this->defaultOptionsLookup->getDefaultOptions();
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
	 * @param int $queryFlags a bit field composed of READ_XXX flags
	 * @return array [ IDatabase $db, array $options ]
	 */
	private function getDBAndOptionsForQueryFlags( $queryFlags ): array {
		[ $mode, $options ] = DBAccessObjectUtils::getDBOptions( $queryFlags );
		return [ DBAccessObjectUtils::getDBFromIndex( $this->dbProvider, $mode ), $options ];
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
		$queryFlagsUsed = $this->queryFlagsUsedForCaching[$userKey] ?? self::READ_NONE;
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
		if ( is_bool( $a ) ) {
			$a = (int)$a;
		}
		if ( is_bool( $b ) ) {
			$b = (int)$b;
		}
		return (string)$a === (string)$b;
	}
}
