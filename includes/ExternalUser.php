<?php

# Copyright (C) 2009 Aryeh Gregor
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
 * @defgroup ExternalUser ExternalUser
 */

/**
 * A class intended to supplement, and perhaps eventually replace, AuthPlugin.
 * See: http://www.mediawiki.org/wiki/ExternalAuth
 *
 * The class represents a user whose data is in a foreign database.  The
 * database may have entirely different conventions from MediaWiki, but it's
 * assumed to at least support the concept of a user id (possibly not an
 * integer), a user name (possibly not meeting MediaWiki's username
 * requirements), and a password.
 *
 * @ingroup ExternalUser
 */
abstract class ExternalUser {
	protected function __construct() {}

	/**
	 * Wrappers around initFrom*().
	 */

	/**
	 * @param $name string
	 * @return mixed ExternalUser, or false on failure
	 */
	public static function newFromName( $name ) {
		global $wgExternalAuthType;
		if ( is_null( $wgExternalAuthType ) ) {
			return false;
		}
		$obj = new $wgExternalAuthType;
		if ( !$obj->initFromName( $name ) ) {
			return false;
		}
		return $obj;
	}

	/**
	 * @param $id string
	 * @return mixed ExternalUser, or false on failure
	 */
	public static function newFromId( $id ) {
		global $wgExternalAuthType;
		if ( is_null( $wgExternalAuthType ) ) {
			return false;
		}
		$obj = new $wgExternalAuthType;
		if ( !$obj->initFromId( $id ) ) {
			return false;
		}
		return $obj;
	}

	/**
	 * @return mixed ExternalUser, or false on failure
	 */
	public static function newFromCookie() {
		global $wgExternalAuthType;
		if ( is_null( $wgExternalAuthType ) ) {
			return false;
		}
		$obj = new $wgExternalAuthType;
		if ( !$obj->initFromCookie() ) {
			return false;
		}
		return $obj;
	}

	/**
	 * Creates the object corresponding to the given User object, assuming the
	 * user exists on the wiki and is linked to an external account.  If either
	 * of these is false, this will return false.
	 *
	 * This is a wrapper around newFromId().
	 *
	 * @param $user User
	 * @return mixed ExternalUser or false
	 */
	public static function newFromUser( $user ) {
		global $wgExternalAuthType;
		if ( is_null( $wgExternalAuthType ) ) {
			# Short-circuit to avoid database query in common case so no one
			# kills me
			return false;
		}

		$dbr = wfGetDB( DB_SLAVE );
		$id = $dbr->selectField( 'external_user', 'eu_external_id',
			array( 'eu_local_id' => $user->getId() ), __METHOD__ );
		if ( $id === false ) {
			return false;
		}
		return self::newFromId( $id );
	}

	/**
	 * Given a name, which is a string exactly as input by the user in the
	 * login form but with whitespace stripped, initialize this object to be
	 * the corresponding ExternalUser.  Return true if successful, otherwise
	 * false.
	 *
	 * @param $name string
	 * @return bool Success?
	 */
	protected abstract function initFromName( $name );

	/**
	 * Given an id, which was at some previous point in history returned by
	 * getId(), initialize this object to be the corresponding ExternalUser.
	 * Return true if successful, false otherwise.
	 *
	 * @param $id string
	 * @return bool Success?
	 */
	protected abstract function initFromId( $id );

	/**
	 * Try to magically initialize the user from cookies or similar information
	 * so he or she can be logged in on just viewing the wiki.  If this is
	 * impossible to do, just return false.
	 *
	 * TODO: Actually use this.
	 *
	 * @return bool Success?
	 */
	protected function initFromCookie() {
		return false;
	}

	/**
	 * This must return some identifier that stably, uniquely identifies the
	 * user.  In a typical web application, this could be an integer
	 * representing the "user id".  In other cases, it might be a string.  In
	 * any event, the return value should be a string between 1 and 255
	 * characters in length; must uniquely identify the user in the foreign
	 * database; and, if at all possible, should be permanent.
	 *
	 * This will only ever be used to reconstruct this ExternalUser object via
	 * newFromId().  The resulting object in that case should correspond to the
	 * same user, even if details have changed in the interim (e.g., renames or
	 * preference changes).
	 *
	 * @return string
	 */
	abstract public function getId();

	/**
	 * This must return the name that the user would normally use for login to
	 * the external database.  It is subject to no particular restrictions
	 * beyond rudimentary sanity, and in particular may be invalid as a
	 * MediaWiki username.  It's used to auto-generate an account name that
	 * *is* valid for MediaWiki, either with or without user input, but
	 * basically is only a hint.
	 *
	 * @return string
	 */
	abstract public function getName();

	/**
	 * Is the given password valid for the external user?  The password is
	 * provided in plaintext.
	 *
	 * @param $password string
	 * @return bool
	 */
	abstract public function authenticate( $password );

	/**
	 * Retrieve the value corresponding to the given preference key.  The most
	 * important values are:
	 *
	 * - emailaddress
	 * - language
	 *
	 * The value must meet MediaWiki's requirements for values of this type,
	 * and will be checked for validity before use.  If the preference makes no
	 * sense for the backend, or it makes sense but is unset for this user, or
	 * is unrecognized, return null.
	 *
	 * $pref will never equal 'password', since passwords are usually hashed
	 * and cannot be directly retrieved.  authenticate() is used for this
	 * instead.
	 *
	 * TODO: Currently this is only called for 'emailaddress'; generalize!  Add
	 * some config option to decide which values are grabbed on user
	 * initialization.
	 *
	 * @param $pref string
	 * @return mixed
	 */
	public function getPref( $pref ) {
		return null;
	}

	/**
	 * Return an array of identifiers for all the foreign groups that this user
	 * has.  The identifiers are opaque objects that only need to be
	 * specifiable by the administrator in LocalSettings.php when configuring
	 * $wgAutopromote.  They may be, for instance, strings or integers.
	 *
	 * TODO: Support this in $wgAutopromote.
	 *
	 * @return array
	 */
	public function getGroups() {
		return array();
	}

	/**
	 * Given a preference key (e.g., 'emailaddress'), provide an HTML message
	 * telling the user how to change it in the external database.  The
	 * administrator has specified that this preference cannot be changed on
	 * the wiki, and may only be changed in the foreign database.  If no
	 * message is available, such as for an unrecognized preference, return
	 * false.
	 *
	 * TODO: Use this somewhere.
	 *
	 * @param $pref string
	 * @return mixed String or false
	 */
	public static function getPrefMessage( $pref ) {
		return false;
	}

	/**
	 * Set the given preference key to the given value.  Two important
	 * preference keys that you might want to implement are 'password' and
	 * 'emailaddress'.  If the set fails, such as because the preference is
	 * unrecognized or because the external database can't be changed right
	 * now, return false.  If it succeeds, return true.
	 *
	 * If applicable, you should make sure to validate the new value against
	 * any constraints the external database may have, since MediaWiki may have
	 * more limited constraints (e.g., on password strength).
	 *
	 * TODO: Untested.
	 *
	 * @param $key string
	 * @param $value string
	 * @return bool Success?
	 */
	public static function setPref( $key, $value ) {
		return false;
	}

	/**
	 * Create a link for future reference between this object and the provided
	 * user_id.  If the user was already linked, the old link will be
	 * overwritten.
	 *
	 * This is part of the core code and is not overridable by specific
	 * plugins.  It's in this class only for convenience.
	 *
	 * @param $id int user_id
	 */
	public final function linkToLocal( $id ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->replace( 'external_user',
			array( 'eu_local_id', 'eu_external_id' ),
			array( 'eu_local_id' => $id,
				   'eu_external_id' => $this->getId() ),
			__METHOD__ );
	}
	
	/**
	 * Check whether this external user id is already linked with
	 * a local user.
	 * @return Mixed User if the account is linked, Null otherwise.
	 */
	public final function getLocalUser(){
		$dbr = wfGetDb( DB_SLAVE );
		$row = $dbr->selectRow(
			'external_user',
			'*',
			array( 'eu_external_id' => $this->getId() )
		);
		return $row
			? User::newFromId( $row->eu_local_id )
			: null;
	}
	
}
