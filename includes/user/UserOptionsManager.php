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
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\MediaWikiServices;
use Psr\Log\LoggerInterface;
use User;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * A service class to control user options
 * @since 1.35
 */
class UserOptionsManager extends UserOptionsLookup {

	public const CONSTRUCTOR_OPTIONS = [
		'HiddenPrefs'
	];

	/** @var ServiceOptions */
	private $serviceOptions;

	/** @var DefaultOptionsLookup */
	private $defaultOptionsLookup;

	/** @var LanguageConverterFactory */
	private $languageConverterFactory;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var LoggerInterface */
	private $logger;

	/** @var array Cached options by user */
	private $optionsCache = [];

	/** @var array Cached original user options fetched from database */
	private $originalOptionsCache = [];

	/** @var HookRunner */
	private $hookRunner;

	/** @var array Query flags used to retrieve options from database */
	private $queryFlagsUsedForCaching = [];

	/**
	 * @param ServiceOptions $options
	 * @param DefaultOptionsLookup $defaultOptionsLookup
	 * @param LanguageConverterFactory $languageConverterFactory
	 * @param ILoadBalancer $loadBalancer
	 * @param LoggerInterface $logger
	 * @param HookContainer $hookContainer
	 */
	public function __construct(
		ServiceOptions $options,
		DefaultOptionsLookup $defaultOptionsLookup,
		LanguageConverterFactory $languageConverterFactory,
		ILoadBalancer $loadBalancer,
		LoggerInterface $logger,
		HookContainer $hookContainer
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->serviceOptions = $options;
		$this->defaultOptionsLookup = $defaultOptionsLookup;
		$this->languageConverterFactory = $languageConverterFactory;
		$this->loadBalancer = $loadBalancer;
		$this->logger = $logger;
		$this->hookRunner = new HookRunner( $hookContainer );
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
	public function getDefaultOption( string $opt ) {
		return $this->defaultOptionsLookup->getDefaultOption( $opt );
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
		if ( !$ignoreHidden && in_array( $oname, $this->serviceOptions->get( 'HiddenPrefs' ) ) ) {
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
		foreach ( $this->serviceOptions->get( 'HiddenPrefs' ) as $pref ) {
			$default = $this->defaultOptionsLookup->getDefaultOption( $pref );
			if ( $default !== null ) {
				$options[$pref] = $default;
			}
		}

		if ( $flags & self::EXCLUDE_DEFAULTS ) {
			$options = array_diff_assoc( $options, $this->defaultOptionsLookup->getDefaultOptions() );
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
		// In case the options are modified, we need to refetch
		// latest options in case they were not fetched from master
		// so that we don't get a race condition trying to save modified options.
		$this->loadUserOptions( $user, self::READ_LATEST );

		// Explicitly NULL values should refer to defaults
		if ( $val === null ) {
			$val = $this->defaultOptionsLookup->getDefaultOption( $oname );
		}

		$userKey = $this->getCacheKey( $user );
		$this->optionsCache[$userKey][$oname] = $val;
	}

	/**
	 * Reset certain (or all) options to the site defaults
	 *
	 * The optional parameter determines which kinds of preferences will be reset.
	 * Supported values are everything that can be reported by getOptionKinds()
	 * and 'all', which forces a reset of *all* preferences and overrides everything else.
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
			$newOptions = $defaultOptions;
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

		// TODO: Deprecate passing full user to the hook
		$this->hookRunner->onUserResetAllOptions(
			User::newFromIdentity( $user ), $newOptions, $oldOptions, $resetKinds
		);

		$this->optionsCache[$this->getCacheKey( $user )] = $newOptions;
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
	 * - 'special' - "preferences" that are not accessible via User::getOptions
	 *               or UserOptionsManager::setOptions.
	 * - 'unused' - preferences about which MediaWiki doesn't know anything.
	 *              These are usually legacy options, removed in newer versions.
	 *
	 * The API (and possibly others) use this function to determine the possible
	 * option types for validation purposes, so make sure to update this when a
	 * new option kind is added.
	 *
	 * @see getOptionKinds
	 * @return array Option kinds
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
	 * @return array The key => kind mapping data
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
		$user = User::newFromIdentity( $userIdentity );
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
				$opts = HTMLFormField::flattenOptions( $info['options'] );
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
			} elseif ( substr( $key, 0, 7 ) === 'userjs-' ) {
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
	 * Usually used via saveSettings().
	 * @param UserIdentity $user
	 * @internal
	 */
	public function saveOptions( UserIdentity $user ) {
		if ( !$user->isRegistered() ) {
			throw new InvalidArgumentException( __METHOD__ . ' was called on anon user' );
		}

		$userKey = $this->getCacheKey( $user );
		// Not using getOptions(), to keep hidden preferences in database
		$saveOptions = $this->loadUserOptions( $user, self::READ_LATEST );
		$originalOptions = $this->originalOptionsCache[$userKey] ?? [];

		// Allow hooks to abort, for instance to save to a global profile.
		// Reset options to default state before saving.
		// TODO: Deprecate passing User to the hook.
		if ( !$this->hookRunner->onUserSaveOptions(
			User::newFromIdentity( $user ), $saveOptions, $originalOptions )
		) {
			return;
		}
		// In case options were modified by the hook
		$this->optionsCache[$userKey] = $saveOptions;

		$userId = $user->getId();
		$insert_rows = []; // all the new preference rows
		foreach ( $saveOptions as $key => $value ) {
			// Don't bother storing default values
			$defaultOption = $this->defaultOptionsLookup->getDefaultOption( $key );
			if ( ( $defaultOption === null && $value !== false && $value !== null )
				|| $value != $defaultOption
			) {
				$insert_rows[] = [
					'up_user' => $userId,
					'up_property' => $key,
					'up_value' => $value,
				];
			}
		}

		$dbw = $this->loadBalancer->getConnectionRef( DB_MASTER );

		$res = $dbw->select(
			'user_properties',
			[ 'up_property', 'up_value' ],
			[ 'up_user' => $userId ],
			__METHOD__
		);

		// Find prior rows that need to be removed or updated. These rows will
		// all be deleted (the latter so that INSERT IGNORE applies the new values).
		$keysDelete = [];
		foreach ( $res as $row ) {
			if ( !isset( $saveOptions[$row->up_property] ) ||
				$saveOptions[$row->up_property] !== $row->up_value
			) {
				$keysDelete[] = $row->up_property;
			}
		}

		if ( !count( $keysDelete ) && !count( $insert_rows ) ) {
			return;
		}
		$this->originalOptionsCache[$userKey] = null;

		if ( count( $keysDelete ) ) {
			// Do the DELETE by PRIMARY KEY for prior rows.
			// In the past a very large portion of calls to this function are for setting
			// 'rememberpassword' for new accounts (a preference that has since been removed).
			// Doing a blanket per-user DELETE for new accounts with no rows in the table
			// caused gap locks on [max user ID,+infinity) which caused high contention since
			// updates would pile up on each other as they are for higher (newer) user IDs.
			// It might not be necessary these days, but it shouldn't hurt either.
			$dbw->delete(
				'user_properties',
				[
					'up_user' => $userId,
					'up_property' => $keysDelete
				],
				__METHOD__
			);
		}
		// Insert the new preference rows
		$dbw->insert(
			'user_properties',
			$insert_rows,
			__METHOD__,
			[ 'IGNORE' ]
		);
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
	 * @param array|null $data preloaded row from the user_properties table
	 * @return array
	 * @internal To be called by User loading code to provide the $data
	 */
	public function loadUserOptions(
		UserIdentity $user,
		int $queryFlags = self::READ_NORMAL,
		array $data = null
	): array {
		$userKey = $this->getCacheKey( $user );
		if ( $this->canUseCachedValues( $user, $queryFlags )
			&& isset( $this->optionsCache[$userKey] )
		) {
			return $this->optionsCache[$userKey];
		}

		$options = $this->defaultOptionsLookup->getDefaultOptions();

		if ( !$user->isRegistered() ) {
			// For unlogged-in users, load language/variant options from request.
			// There's no need to do it for logged-in users: they can set preferences,
			// and handling of page content is done by $pageLang->getPreferredVariant() and such,
			// so don't override user's choice (especially when the user chooses site default).
			$variant = $this->languageConverterFactory->getLanguageConverter()->getDefaultVariant();
			$options['variant'] = $variant;
			$options['language'] = $variant;
			$this->optionsCache[$userKey] = $options;
			return $options;
		}

		// In case options were already loaded from the database before and no options
		// changes were saved to the database, we can use the cached original options.
		if ( $this->canUseCachedValues( $user, $queryFlags )
			&& isset( $this->originalOptionsCache[$userKey] )
		) {
			$this->logger->debug( 'Loading options from override cache', [
				'user_id' => $user->getId()
			] );
			return $this->originalOptionsCache[$userKey];
		} else {
			if ( !is_array( $data ) ) {
				$this->logger->debug( 'Loading options from database', [
					'user_id' => $user->getId()
				] );

				$dbr = $this->getDBForQueryFlags( $queryFlags );
				$res = $dbr->select(
					'user_properties',
					[ 'up_property', 'up_value' ],
					[ 'up_user' => $user->getId() ],
					__METHOD__
				);

				$data = [];
				foreach ( $res as $row ) {
					// Convert '0' to 0. PHP's boolean conversion considers them both
					// false, but e.g. JavaScript considers the former as true.
					// @todo: T54542 Somehow determine the desired type (string/int/bool)
					//  and convert all values here.
					if ( $row->up_value === '0' ) {
						$row->up_value = 0;
					}
					$data[$row->up_property] = $row->up_value;
				}
			}

			foreach ( $data as $property => $value ) {
				$options[$property] = $value;
			}
		}

		// Replace deprecated language codes
		$options['language'] = LanguageCode::replaceDeprecatedCodes( $options['language'] );
		// Need to store what we have so far before the hook to prevent
		// infinite recursion if the hook attempts to reload options
		$this->originalOptionsCache[$userKey] = $options;
		$this->queryFlagsUsedForCaching[$userKey] = $queryFlags;
		// TODO: Deprecate passing full User object into the hook.
		$this->hookRunner->onUserLoadOptions(
			User::newFromIdentity( $user ), $options
		);

		$this->originalOptionsCache[$userKey] = $options;
		$this->optionsCache[$userKey] = $options;

		return $this->optionsCache[$userKey];
	}

	/**
	 * Clears cached user options.
	 * @internal To be used by User::clearInstanceCache
	 * @param UserIdentity $user
	 */
	public function clearUserOptionsCache( UserIdentity $user ) {
		$cacheKey = $this->getCacheKey( $user );
		$this->optionsCache[$cacheKey] = null;
		$this->originalOptionsCache[$cacheKey] = null;
		$this->queryFlagsUsedForCaching[$cacheKey] = null;
	}

	/**
	 * Gets a key for various caches.
	 * @param UserIdentity $user
	 * @return string
	 */
	private function getCacheKey( UserIdentity $user ): string {
		return $user->isRegistered() ? "u:{$user->getId()}" : 'anon';
	}

	/**
	 * @param int $queryFlags a bit field composed of READ_XXX flags
	 * @return IDatabase
	 */
	private function getDBForQueryFlags( $queryFlags ): IDatabase {
		list( $mode, ) = DBAccessObjectUtils::getDBOptions( $queryFlags );
		return $this->loadBalancer->getConnectionRef( $mode, [] );
	}

	/**
	 * Determines if it's ok to use cached options values for a given user and query flags
	 * @param UserIdentity $user
	 * @param int $queryFlags
	 * @return bool
	 */
	private function canUseCachedValues( UserIdentity $user, int $queryFlags ) : bool {
		if ( !$user->isRegistered() ) {
			// Anon users don't have options stored in the database,
			// so $queryFlags are ignored.
			return true;
		}
		if ( $queryFlags >= self::READ_LOCKING ) {
			return false;
		}
		$userKey = $this->getCacheKey( $user );
		$queryFlagsUsed = $this->queryFlagsUsedForCaching[$userKey] ?? self::READ_NONE;
		return $queryFlagsUsed >= $queryFlags;
	}
}
