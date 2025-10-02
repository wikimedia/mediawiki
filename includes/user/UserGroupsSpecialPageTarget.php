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

/**
 * This class represents a user upon which a special page for group management is being invoked.
 * It encapsulates the actual user object, which might be a {@see UserIdentity} or
 * an extension-managed class, and provides data needed by the special page base.
 */
class UserGroupsSpecialPageTarget {

	/**
	 * @param string $userName The name of the user in a form that will be useful with {{GENDER:}}.
	 * @param mixed $userObject The original user object that the special page acts upon. It can be of
	 *   any type that the page recognizes.
	 */
	public function __construct(
		public readonly string $userName,
		public readonly mixed $userObject
	) {
	}
}
