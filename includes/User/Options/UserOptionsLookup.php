<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User\Options;

use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserNameUtils;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * Provides access to user options
 * @since 1.35
 */
abstract class UserOptionsLookup {

	/**
	 * Exclude user options that are set to their default value.
	 */
	public const EXCLUDE_DEFAULTS = 1;

	/**
	 * The suffix appended to preference names for the associated preference
	 * that tracks whether they have a local override.
	 * @since 1.43
	 */
	public const LOCAL_EXCEPTION_SUFFIX = '-local-exception';

	private UserNameUtils $userNameUtils;

	public function __construct( UserNameUtils $userNameUtils ) {
		$this->userNameUtils = $userNameUtils;
	}

	/**
	 * Combine the language default options with any site-specific and user-specific defaults
	 * and add the default language variants.
	 *
	 * @param UserIdentity|null $userIdentity User to look the default up for; set to null to
	 * ignore any user-specific defaults (since 1.42)
	 * @return array
	 */
	abstract public function getDefaultOptions( ?UserIdentity $userIdentity = null ): array;

	/**
	 * Get a given default option value.
	 *
	 * @param string $opt Name of option to retrieve
	 * @param UserIdentity|null $userIdentity User to look the defaults up for; set to null to
	 * ignore any user-specific defaults (since 1.42)
	 * @return mixed|null Default option value
	 */
	public function getDefaultOption(
		string $opt,
		?UserIdentity $userIdentity = null
	) {
		$defaultOptions = $this->getDefaultOptions( $userIdentity );
		return $defaultOptions[$opt] ?? null;
	}

	/**
	 * Get the user's current setting for a given option.
	 *
	 * @param UserIdentity $user The user to get the option for
	 * @param string $oname The option to check
	 * @param mixed|null $defaultOverride A default value returned if the option does not exist
	 * @param bool $ignoreHidden Whether to ignore the effects of $wgHiddenPrefs
	 * @param int $queryFlags A bit field composed of READ_XXX flags
	 * @return mixed|null User's current value for the option,
	 *   Note that while option values retrieved from the database are always strings, default
	 *   values and values set within the current request and not yet saved may be of another type.
	 * @see getBoolOption()
	 * @see getIntOption()
	 */
	abstract public function getOption(
		UserIdentity $user,
		string $oname,
		$defaultOverride = null,
		bool $ignoreHidden = false,
		int $queryFlags = IDBAccessObject::READ_NORMAL
	);

	/**
	 * Get all user's options
	 *
	 * @param UserIdentity $user The user to get the option for
	 * @param int $flags Bitwise combination of:
	 *   UserOptionsManager::EXCLUDE_DEFAULTS  Exclude user options that are set
	 *                                         to the default value. Options
	 *                                         that are set to their conditionally
	 *                                         default value are not excluded.
	 * @param int $queryFlags A bit field composed of READ_XXX flags
	 * @return array
	 */
	abstract public function getOptions(
		UserIdentity $user,
		int $flags = 0,
		int $queryFlags = IDBAccessObject::READ_NORMAL
	): array;

	/**
	 * Get the user's current setting for a given option, as a boolean value.
	 *
	 * @param UserIdentity $user The user to get the option for
	 * @param string $oname The option to check
	 * @param int $queryFlags A bit field composed of READ_XXX flags
	 * @return bool User's current value for the option
	 * @see getOption()
	 */
	public function getBoolOption(
		UserIdentity $user,
		string $oname,
		int $queryFlags = IDBAccessObject::READ_NORMAL
	): bool {
		return (bool)$this->getOption(
			$user, $oname, null, false, $queryFlags );
	}

	/**
	 * Get the user's current setting for a given option, as an integer value.
	 *
	 * @param UserIdentity $user The user to get the option for
	 * @param string $oname The option to check
	 * @param int $defaultOverride A default value returned if the option does not exist
	 * @param int $queryFlags A bit field composed of READ_XXX flags
	 * @return int User's current value for the option
	 * @see getOption()
	 */
	public function getIntOption(
		UserIdentity $user,
		string $oname,
		int $defaultOverride = 0,
		int $queryFlags = IDBAccessObject::READ_NORMAL
	): int {
		$val = $this->getOption(
			$user, $oname, $defaultOverride, false, $queryFlags );
		if ( $val == '' ) {
			$val = $defaultOverride;
		}
		return intval( $val );
	}

	/**
	 * Get a cache key for a user
	 * @param UserIdentity $user
	 * @return string
	 */
	protected function getCacheKey( UserIdentity $user ): string {
		$name = $user->getName();
		if ( $this->userNameUtils->isIP( $name ) || $this->userNameUtils->isTemp( $name ) ) {
			// IP and temporary users may not have custom preferences, so they can share a key
			return 'anon';
		} elseif ( $user->isRegistered() ) {
			return "u:{$user->getId()}";
		} else {
			// Allow users with no local account to have preferences provided by alternative
			// UserOptionsStore implementations (e.g. in GlobalPreferences)
			$canonical = $this->userNameUtils->getCanonical( $name ) ?: $name;
			return "a:$canonical";
		}
	}

	/**
	 * Determine if a user option came from a source other than the local store
	 * or the defaults. If this is true, setting the option will be ignored
	 * unless GLOBAL_OVERRIDE or GLOBAL_UPDATE is passed to setOption().
	 *
	 * @param UserIdentity $user
	 * @param string $key
	 * @return bool
	 */
	public function isOptionGlobal( UserIdentity $user, string $key ) {
		return false;
	}

	/**
	 * Get a single option for a batch of users, given their names.
	 *
	 * Results are uncached. Use getOption() to get options with a per-user
	 * cache.
	 *
	 * User names are used because that's what GenderCache has. If you're
	 * calling this and you're not GenderCache, consider adding a method
	 * taking an array of UserIdentity objects instead.
	 *
	 * @since 1.44
	 * @param string[] $users A normalized list of usernames
	 * @param string $key The option to get
	 * @return array The option values, indexed by the provided usernames
	 */
	abstract public function getOptionBatchForUserNames( array $users, string $key );
}
/** @deprecated class alias since 1.42 */
class_alias( UserOptionsLookup::class, 'MediaWiki\\User\\UserOptionsLookup' );
