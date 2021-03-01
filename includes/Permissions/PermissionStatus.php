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

namespace MediaWiki\Permissions;

use StatusValue;

/**
 * A StatusValue for permission errors.
 *
 * @todo Add compat code for PermissionManager::getPermissionErrors
 *       and additional info about user blocks.
 *
 * @unstable
 * @since 1.36
 */
class PermissionStatus extends StatusValue {

	/**
	 * @return static
	 */
	public static function newEmpty() {
		return new static();
	}

	/**
	 * Returns this permission status in legacy error array format.
	 *
	 * @see PermissionManager::getPermissionErrors()
	 *
	 * @return array
	 */
	public function toLegacyErrorArray(): array {
		return $this->getStatusArray();
	}

}
