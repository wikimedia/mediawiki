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
 * @author DannyS712
 */

namespace MediaWiki\User;

/**
 * Shared interface for rigor levels when dealing with User methods
 *
 * @since 1.36
 */
interface UserRigorOptions {

	/**
	 * @var string
	 *
	 * Check that a user name is valid for batch processes, login and account
	 * creation. This does not allow auto-created temporary user patterns.
	 */
	public const RIGOR_CREATABLE = 'creatable';

	/**
	 * @var string
	 *
	 * Check that a user name is valid for batch processes and login
	 */
	public const RIGOR_USABLE = 'usable';

	/**
	 * @var string
	 *
	 * Check that a user name is valid for batch processes
	 */
	public const RIGOR_VALID = 'valid';

	/**
	 * @var string
	 *
	 * No validation at all
	 */
	public const RIGOR_NONE = 'none';

}
