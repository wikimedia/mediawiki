<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User;

/**
 * Shared interface for rigor levels when dealing with User methods
 *
 * @since 1.36
 * @ingroup User
 * @author DannyS712
 */
interface UserRigorOptions {

	/**
	 * Check that a user name is valid for batch processes, login and account
	 * creation. This does not allow auto-created temporary user patterns.
	 */
	public const RIGOR_CREATABLE = 'creatable';

	/**
	 * Check that a user name is valid for batch processes and login
	 */
	public const RIGOR_USABLE = 'usable';

	/**
	 * Check that a user name is valid for batch processes
	 */
	public const RIGOR_VALID = 'valid';

	/**
	 * No validation at all
	 */
	public const RIGOR_NONE = 'none';

}
