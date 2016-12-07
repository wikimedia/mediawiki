<?php
/**
 * Authentication plugin interface
 *
 * Copyright © 2004 Brion Vibber <brion@pobox.com>
 * https://www.mediawiki.org/
 *
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

/**
 * Authentication plugin interface. Instantiate a subclass of AuthPlugin
 * and set $wgAuth to it to authenticate against some external tool.
 *
 * The default behavior is not to do anything, and use the local user
 * database for all authentication. A subclass can require that all
 * accounts authenticate externally, or use it only as a fallback; also
 * you can transparently create internal wiki accounts the first time
 * someone logs in who can be authenticated externally.
 *
 * @deprecated since 1.27
 */
class AuthPlugin {
	/**
	 * @var string
	 */
	protected $domain;

	/**
	 * Check whether there exists a user account with the given name.
	 * The name will be normalized to MediaWiki's requirements, so
	 * you might need to munge it (for instance, for lowercase initial
	 * letters).
	 *
	 * @param string $username Username.
	 * @return bool
	 */
	public function userExists( $username ) {
		# Override this!
		return false;
	}

	/**
	 * Check if a username+password pair is a valid login.
	 * The name will be normalized to MediaWiki's requirements, so
	 * you might need to munge it (for instance, for lowercase initial
	 * letters).
	 *
	 * @param string $username Username.
	 * @param string $password User password.
	 * @return bool
	 */
	public function authenticate( $username, $password ) {
		# Override this!
		return false;
	}

	/**
	 * Modify options in the login template.
	 *
	 * @param BaseTemplate $template
	 * @param string $type 'signup' or 'login'. Added in 1.16.
	 */
	public function modifyUITemplate( &$template, &$type ) {
		# Override this!
		$template->set( 'usedomain', false );
	}

	/**
	 * Set the domain this plugin is supposed to use when authenticating.
	 *
	 * @param string $domain Authentication domain.
	 */
	public function setDomain( $domain ) {
		$this->domain = $domain;
	}

	/**
	 * Get the user's domain
	 *
	 * @return string
	 */
	public function getDomain() {
		if ( isset( $this->domain ) ) {
			return $this->domain;
		} else {
			return 'invaliddomain';
		}
	}

	/**
	 * Check to see if the specific domain is a valid domain.
	 *
	 * @param string $domain Authentication domain.
	 * @return bool
	 */
	public function validDomain( $domain ) {
		# Override this!
		return true;
	}

	/**
	 * When a user logs in, optionally fill in preferences and such.
	 * For instance, you might pull the email address or real name from the
	 * external user database.
	 *
	 * The User object is passed by reference so it can be modified; don't
	 * forget the & on your function declaration.
	 *
	 * @deprecated since 1.26, use the UserLoggedIn hook instead. And assigning
	 *  a different User object to $user is no longer supported.
	 * @param User $user
	 * @return bool
	 */
	public function updateUser( &$user ) {
		# Override this and do something
		return true;
	}

	/**
	 * Return true if the wiki should create a new local account automatically
	 * when asked to login a user who doesn't exist locally but does in the
	 * external auth database.
	 *
	 * If you don't automatically create accounts, you must still create
	 * accounts in some way. It's not possible to authenticate without
	 * a local account.
	 *
	 * This is just a question, and shouldn't perform any actions.
	 *
	 * @return bool
	 */
	public function autoCreate() {
		return false;
	}

	/**
	 * Allow a property change? Properties are the same as preferences
	 * and use the same keys. 'Realname' 'Emailaddress' and 'Nickname'
	 * all reference this.
	 *
	 * @param string $prop
	 *
	 * @return bool
	 */
	public function allowPropChange( $prop = '' ) {
		if ( $prop == 'realname' && is_callable( [ $this, 'allowRealNameChange' ] ) ) {
			return $this->allowRealNameChange();
		} elseif ( $prop == 'emailaddress' && is_callable( [ $this, 'allowEmailChange' ] ) ) {
			return $this->allowEmailChange();
		} elseif ( $prop == 'nickname' && is_callable( [ $this, 'allowNickChange' ] ) ) {
			return $this->allowNickChange();
		} else {
			return true;
		}
	}

	/**
	 * Can users change their passwords?
	 *
	 * @return bool
	 */
	public function allowPasswordChange() {
		return true;
	}

	/**
	 * Should MediaWiki store passwords in its local database?
	 *
	 * @return bool
	 */
	public function allowSetLocalPassword() {
		return true;
	}

	/**
	 * Set the given password in the authentication database.
	 * As a special case, the password may be set to null to request
	 * locking the password to an unusable value, with the expectation
	 * that it will be set later through a mail reset or other method.
	 *
	 * Return true if successful.
	 *
	 * @param User $user
	 * @param string $password Password.
	 * @return bool
	 */
	public function setPassword( $user, $password ) {
		return true;
	}

	/**
	 * Update user information in the external authentication database.
	 * Return true if successful.
	 *
	 * @deprecated since 1.26, use the UserSaveSettings hook instead.
	 * @param User $user
	 * @return bool
	 */
	public function updateExternalDB( $user ) {
		return true;
	}

	/**
	 * Update user groups in the external authentication database.
	 * Return true if successful.
	 *
	 * @deprecated since 1.26, use the UserGroupsChanged hook instead.
	 * @param User $user
	 * @param array $addgroups Groups to add.
	 * @param array $delgroups Groups to remove.
	 * @return bool
	 */
	public function updateExternalDBGroups( $user, $addgroups, $delgroups = [] ) {
		return true;
	}

	/**
	 * Check to see if external accounts can be created.
	 * Return true if external accounts can be created.
	 * @return bool
	 */
	public function canCreateAccounts() {
		return false;
	}

	/**
	 * Add a user to the external authentication database.
	 * Return true if successful.
	 *
	 * @param User $user Only the name should be assumed valid at this point
	 * @param string $password
	 * @param string $email
	 * @param string $realname
	 * @return bool
	 */
	public function addUser( $user, $password, $email = '', $realname = '' ) {
		return true;
	}

	/**
	 * Return true to prevent logins that don't authenticate here from being
	 * checked against the local database's password fields.
	 *
	 * This is just a question, and shouldn't perform any actions.
	 *
	 * @return bool
	 */
	public function strict() {
		return false;
	}

	/**
	 * Check if a user should authenticate locally if the global authentication fails.
	 * If either this or strict() returns true, local authentication is not used.
	 *
	 * @param string $username Username.
	 * @return bool
	 */
	public function strictUserAuth( $username ) {
		return false;
	}

	/**
	 * When creating a user account, optionally fill in preferences and such.
	 * For instance, you might pull the email address or real name from the
	 * external user database.
	 *
	 * The User object is passed by reference so it can be modified; don't
	 * forget the & on your function declaration.
	 *
	 * @deprecated since 1.26, use the UserLoggedIn hook instead. And assigning
	 *  a different User object to $user is no longer supported.
	 * @param User $user
	 * @param bool $autocreate True if user is being autocreated on login
	 */
	public function initUser( &$user, $autocreate = false ) {
		# Override this to do something.
	}

	/**
	 * If you want to munge the case of an account name before the final
	 * check, now is your chance.
	 * @param string $username
	 * @return string
	 */
	public function getCanonicalName( $username ) {
		return $username;
	}

	/**
	 * Get an instance of a User object
	 *
	 * @param User $user
	 *
	 * @return AuthPluginUser
	 */
	public function getUserInstance( User &$user ) {
		return new AuthPluginUser( $user );
	}

	/**
	 * Get a list of domains (in HTMLForm options format) used.
	 *
	 * @return array
	 */
	public function domainList() {
		return [];
	}
}

/**
 * @deprecated since 1.27
 */
class AuthPluginUser {
	function __construct( $user ) {
		# Override this!
	}

	public function getId() {
		# Override this!
		return -1;
	}

	/**
	 * Indicate whether the user is locked
	 * @deprecated since 1.26, use the UserIsLocked hook instead.
	 * @return bool
	 */
	public function isLocked() {
		# Override this!
		return false;
	}

	/**
	 * Indicate whether the user is hidden
	 * @deprecated since 1.26, use the UserIsHidden hook instead.
	 * @return bool
	 */
	public function isHidden() {
		# Override this!
		return false;
	}

	/**
	 * @deprecated since 1.28, use SessionManager::invalidateSessionForUser() instead.
	 */
	public function resetAuthToken() {
		# Override this!
		return true;
	}
}
