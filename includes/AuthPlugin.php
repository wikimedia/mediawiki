<?php
/**
 * @package MediaWiki
 */
# Copyright (C) 2004 Brion Vibber <brion@pobox.com>
# http://www.mediawiki.org/
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
# http://www.gnu.org/copyleft/gpl.html

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
 * This interface is new, and might change a bit before 1.4.0 final is
 * done...
 *
 * @package MediaWiki
 */
class AuthPlugin {
	/**
	 * Check whether there exists a user account with the given name.
	 * The name will be normalized to MediaWiki's requirements, so
	 * you might need to munge it (for instance, for lowercase initial
	 * letters).
	 *
	 * @param string $username
	 * @return bool
	 * @access public
	 */
	function userExists( $username ) {
		# Override this!
		return false;
	}

	/**
	 * Check if a username+password pair is a valid login.
	 * The name will be normalized to MediaWiki's requirements, so
	 * you might need to munge it (for instance, for lowercase initial
	 * letters).
	 *
	 * @param string $username
	 * @param string $password
	 * @return bool
	 * @access public
	 */
	function authenticate( $username, $password ) {
		# Override this!
		return false;
	}

	/**
	 * Modify options in the login template.
	 *
	 * @param UserLoginTemplate $template
	 * @access public
	 */
	function modifyUITemplate( &$template ) {
		# Override this!
		$template->set( 'usedomain', false );
	}

	/**
	 * Set the domain this plugin is supposed to use when authenticating.
	 *
	 * @param string $domain
	 * @access public
	 */
	function setDomain( $domain ) {
		$this->domain = $domain;
	}

	/**
	 * Check to see if the specific domain is a valid domain.
	 *
	 * @param string $domain
	 * @return bool
	 * @access public
	 */
	function validDomain( $domain ) {
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
	 * @param User $user
	 * @access public
	 */
	function updateUser( &$user ) {
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
	 * @access public
	 */
	function autoCreate() {
		return false;
	}

	/**
	 * Set the given password in the authentication database.
	 * Return true if successful.
	 *
	 * @param string $password
	 * @return bool
	 * @access public
	 */
	function setPassword( $password ) {
		return true;
	}

	/**
	 * Update user information in the external authentication database.
	 * Return true if successful.
	 *
	 * @param User $user
	 * @return bool
	 * @access public
	 */
	function updateExternalDB( $user ) {
		return true;
	}

	/**
	 * Check to see if external accounts can be created.
	 * Return true if external accounts can be created.
	 * @return bool
	 * @access public
	 */
	function canCreateAccounts() {
		return false;
	}

	/**
	 * Add a user to the external authentication database.
	 * Return true if successful.
	 *
	 * @param User $user
	 * @param string $password
	 * @return bool
	 * @access public
	 */
	function addUser( $user, $password ) {
		return true;
	}


	/**
	 * Return true to prevent logins that don't authenticate here from being
	 * checked against the local database's password fields.
	 *
	 * This is just a question, and shouldn't perform any actions.
	 *
	 * @return bool
	 * @access public
	 */
	function strict() {
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
	 * @param User $user
	 * @access public
	 */
	function initUser( &$user ) {
		# Override this to do something.
	}

	/**
	 * If you want to munge the case of an account name before the final
	 * check, now is your chance.
	 */
	function getCanonicalName( $username ) {
		return $username;
	}
}

?>
