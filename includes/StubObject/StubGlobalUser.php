<?php

// phpcs:disable PSR2.Methods.MethodDeclaration.Underscore

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

namespace MediaWiki\StubObject;

use InvalidArgumentException;
use MediaWiki\User\User;

/**
 * Stub object for the global user ($wgUser) that makes it possible to change the
 * relevant underlying object while still ensuring that deprecation warnings will
 * be emitted upon method calls.
 *
 * @internal Will be removed in 1.38
 *
 * @since 1.37
 * @author Danny712
 */
class StubGlobalUser extends StubObject {

	/** @var bool */
	public static $destructorDeprecationDisarmed = false;

	/** @var User */
	public $realUser;

	public function __construct( User $realUser ) {
		parent::__construct( 'wgUser' );
		$this->realUser = $realUser;
	}

	/**
	 * @deprecated since 1.35.
	 * @return User
	 */
	public function _newObject() {
		// Based on MediaWiki\StubObject\DeprecatedGlobal::_newObject
		/*
		 * Put the caller offset for wfDeprecated as 6, as
		 * that gives the function that uses this object, since:
		 *
		 * 1 = this function ( _newObject )
		 * 2 = MediaWiki\StubObject\StubGlobalUser::_unstub
		 * 3 = MediaWiki\StubObject\StubObject::_call
		 * 4 = MediaWiki\StubObject\StubObject::__call
		 * 5 = MediaWiki\StubObject\StubGlobalUser::<method of global called>
		 * 6 = Actual function using the global.
		 * (the same applies to _get/__get or _set/__set instead of _call/__call)
		 *
		 * Of course its theoretically possible to have other call
		 * sequences for this method, but that seems to be
		 * rather unlikely.
		 */
		// Officially deprecated since 1.35
		wfDeprecated( '$wgUser', '1.35', false, 6 );
		return $this->realUser;
	}

	/**
	 * Reset the stub global user to a different "real" user object, while ensuring that
	 * any method calls on that object will still trigger deprecation notices.
	 *
	 * @param StubGlobalUser|User $user
	 */
	public static function setUser( $user ) {
		// This is intended to be interacting with the deprecated global
		// phpcs:ignore MediaWiki.Usage.DeprecatedGlobalVariables.Deprecated$wgUser
		global $wgUser;

		self::$destructorDeprecationDisarmed = true;
		// Supports MediaWiki\StubObject\StubGlobalUser parameter in case something fetched the existing value of
		// $wgUser, set it to something else, and now is trying to restore it
		$realUser = self::getRealUser( $user );
		$wgUser = new self( $realUser );
		self::$destructorDeprecationDisarmed = false;
	}

	/**
	 * Get the relevant "real" user object based on either a User object or a MediaWiki\StubObject\StubGlobalUser
	 * wrapper. Bypasses deprecation notices from converting a MediaWiki\StubObject\StubGlobalUser to an actual
	 * user, and does not change $wgUser.
	 *
	 * @param StubGlobalUser|User $globalUser
	 * @return User
	 */
	public static function getRealUser( $globalUser ): User {
		if ( $globalUser instanceof StubGlobalUser ) {
			return $globalUser->realUser;
		} elseif ( $globalUser instanceof User ) {
			return $globalUser;
		} else {
			throw new InvalidArgumentException(
				'$globalUser must be a User (or MediaWiki\StubObject\StubGlobalUser), got ' .
				get_debug_type( $globalUser )
			);
		}
	}

	/**
	 * This function creates a new object of the real class and replace it in
	 * the global variable.
	 * This is public, for the convenience of external callers wishing to access
	 * properties, e.g. eval.php
	 *
	 * Overriding MediaWiki\StubObject\StubObject::_unstub because for some reason that thinks there is
	 * an unstub loop when trying to use the magic __set() logic, but there isn't
	 * any loop because _newObject() returns a specific instance of User rather
	 * than calling any methods that could then try to use $wgUser. The main difference
	 * between this and the parent method is that we don't try to check for
	 * recursion loops.
	 *
	 * @param string $name Name of the method called in this object.
	 * @param int $level Level to go in the stack trace to get the function
	 *   who called this function.
	 * @return User The unstubbed version
	 */
	public function _unstub( $name = '_unstub', $level = 2 ) {
		if ( !$GLOBALS[$this->global] instanceof self ) {
			return $GLOBALS[$this->global]; // already unstubbed.
		}

		$caller = wfGetCaller( $level );
		wfDebug( "Unstubbing \${$this->global} on call of "
			. "\${$this->global}::$name from $caller" );
		$GLOBALS[$this->global] = $this->_newObject();
		return $GLOBALS[$this->global];
	}

	public function __destruct() {
		if ( !self::$destructorDeprecationDisarmed ) {
			wfDeprecatedMsg( '$wgUser reassignment detected', '1.37', false, 3 );
		}
	}
}
