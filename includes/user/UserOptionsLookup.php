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

use IDBAccessObject;

/**
 * Provides access to user options
 * @since 1.35
 */
abstract class UserOptionsLookup implements IDBAccessObject {

	/**
	 * Exclude user options that are set to their default value.
	 */
	public const EXCLUDE_DEFAULTS = 1;

	/**
	 * Combine the language default options with any site-specific options
	 * and add the default language variants.
	 *
	 * @return array Array of String options
	 */
	abstract public function getDefaultOptions(): array;

	/**
	 * Get a given default option value.
	 *
	 * @param string $opt Name of option to retrieve
	 * @return string|null Default option value
	 */
	abstract public function getDefaultOption( string $opt );

	/**
	 * Get the user's current setting for a given option.
	 *
	 * @param UserIdentity $user The user to get the option for
	 * @param string $oname The option to check
	 * @param mixed|null $defaultOverride A default value returned if the option does not exist
	 * @param bool $ignoreHidden Whether to ignore the effects of $wgHiddenPrefs
	 * @param int $queryFlags A bit field composed of READ_XXX flags
	 * @return mixed|null User's current value for the option
	 * @see getBoolOption()
	 * @see getIntOption()
	 */
	abstract public function getOption(
		UserIdentity $user,
		string $oname,
		$defaultOverride = null,
		bool $ignoreHidden = false,
		int $queryFlags = self::READ_NORMAL
	);

	/**
	 * Get all user's options
	 *
	 * @param UserIdentity $user The user to get the option for
	 * @param int $flags Bitwise combination of:
	 *   UserOptionsManager::EXCLUDE_DEFAULTS  Exclude user options that are set
	 *                                         to the default value.
	 * @param int $queryFlags A bit field composed of READ_XXX flags
	 * @return array
	 */
	abstract public function getOptions(
		UserIdentity $user,
		int $flags = 0,
		int $queryFlags = self::READ_NORMAL
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
		int $queryFlags = self::READ_NORMAL
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
		int $queryFlags = self::READ_NORMAL
	): int {
		$val = $this->getOption(
			$user, $oname, $defaultOverride, false, $queryFlags );
		if ( $val == '' ) {
			$val = $defaultOverride;
		}
		return intval( $val );
	}
}
