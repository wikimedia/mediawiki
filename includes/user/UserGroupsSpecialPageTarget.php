<?php
/**
 * @license GPL-2.0-or-later
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
