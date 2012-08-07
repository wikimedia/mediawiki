<?php
/**
 * MediaWiki Password storage cryptography and validation system
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
 * @author Daniel Friesen <mediawiki@danielfriesen.name>
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @ingroup Password
 */

/**
 * @defgroup Password Password
 */

/**
 * @ingroup Password
 * @since 1.20
 */
class PasswordDataError extends Exception {

	protected $msgObj;

	public function __construct( $msgObj, $code = 0, Exception $previous = null ) {
		$this->msgObj = clone $msgObj;
		parent::__construct( $msgObj->inLanguage( 'en' )->plain(), $code, $previous );
	}

	public function getMessageObj() {
		return clone $this->msgObj;
	}

}

/**
 * @ingroup Password
 */
class Password {

	/**
	 * Map of registered PasswordTypes
	 */
	protected static $types = array();

	/**
	 * The preferred PasswordType
	 */
	protected static $preferredType;

	/**
	 * Initialize the class
	 * - Register password types
	 * - Pick the preferred type
	 * - Run a hook for extensions to register new password types
	 */
	protected static function init() {
		if ( isset( self::$preferredType ) ) {
			return;
		}
		self::registerType( 'A', 'Password_TypeA' );
		self::registerType( 'B', 'Password_TypeB' );
		self::registerType( 'PBKHM', 'Password_TypePBKHM' );

		// If wgPasswordSalt is set the preferred type is the best implementation we have now, otherwise it's type A.
		global $wgPasswordSalt;
		if( $wgPasswordSalt ) {
			$preferredType = 'PBKHM';
		} else {
			$preferredType = 'A';
		}

		// Run a hook that'll let extensions register types and changes the preferred type
		wfRunHooks( 'PasswordClassInit', array( &$preferredType ) );

		self::$preferredType = $preferredType;
	}

	/**
	 * Register a password class type
	 *
	 * @param $type The name of the type. Core uses names like 'A', 'B', ...
	 *              extensions should use more specific names.
	 * @param $className The class implementing this password type. The class
	 *                   must implement the PasswordType interface.
	 */
	public static function registerType( $type, $className ) {
		self::$types[$type] = $className;
	}

	/**
	 * Return a new instance of a password class type
	 *
	 * @param $type string The password type to return. If left out will return the preferred type.
	 * @return mixed A PasswordType implementing class, or null.
	 */
	public static function getType( $type = null ) {
		self::init();
		if ( is_null( $type ) ) {
			$type = self::$preferredType;
		}
		if ( isset( self::$types[$type] ) ) {
			$className = self::$types[$type];
			$cryptType = new $className( $type );
			if ( $cryptType instanceof PasswordType ) {
				return $cryptType;
			}
			wfWarn( __METHOD__ . ": Password crypt type $type class $className is not a PasswordType." );
			return null;
		}
		wfWarn( __METHOD__ . ": Password crypt type $type does not exist." );
		return null;
	}

	/**
	 * Create database ready password storage data we can store in the database given a user's plaintext password.
	 *
	 * @param $password The plaintext password
	 * @return string The raw database ready password storage data along with parameters and a type.
	 */
	public static function crypt( $password ) {
		$cryptType = self::getType();
		return ':' . $cryptType->getName() . ':' . $cryptType->crypt( $password );
	}

	/**
	 * Parse the type and rad data out of database ready password data.
	 * Used by verify() and isPreferredFormat() to avoid repeating common parsing code.
	 *
	 * @param $data string The raw database format password data with all params and types stuck on the front.
	 * @return Array An array containing a PasswordType class and the remaining portion of $data
	 * @throws PasswordDataError
	 */
	protected static function parseType( $data ) {
		$params = explode( ':', $data, 3 );

		// Shift off the blank (When ":A:..." is split the first : should mean the first element is '')
		$blank = array_shift( $params );
		if ( $blank !== '' ) {
			// If the first piece is not '' then this is invalid
			// Note that old style passwords (oldCrypt) are handled by User internally since they require
			// data which we do not have.
			throw new PasswordDataError( wfMessage( 'password-crypt-invalid' ) );
		}
		$type = array_shift( $params );
		if ( !$type ) {
			// A type was not specified
			throw new PasswordDataError( wfMessage( 'password-crypt-invalid' ) );
		}

		$cryptType = self::getType( $type );
		if ( !$cryptType ) {
			// Crypt type does not exist
			throw new PasswordDataError( wfMessage( 'password-crypt-notype', $type ) );
		}

		return array( $cryptType, $params[0] );
	}

	/**
	 * Verify a plaintext password against the database ready password data of a password
	 * to see if the password is correct.
	 *
	 * @param $data string The raw database ready password data with all params and types stuck on the front.
	 * @param $password The plaintext password
	 * @return boolean true for a password match, false for a bad password
	 * @throws PasswordDataError if the password data was badly formed or there was some issue with
	 *           comparing the passwords which is not the user's fault.
	 */
	public static function verify( $data, $password ) {
		list( $cryptType, $remainingData ) = self::parseType( $data );
		return $cryptType->verify( $remainingData, $password );
	}

	/**
	 * Check and see if the database ready password data of a password is in preferred format.
	 * This may return false when the password type is not the same as the specified preferred type
	 * or when the password type implementation says that some of the parameters are different than
	 * what is preferred.
	 *
	 * When this method returns false the User's password may be 'upgraded' by calling
	 * crypt() again to generate new database ready password data for the password.
	 *
	 * @param $data string The raw database ready password data with all params and types stuck on the front.
	 * @return bool
	 */
	public static function needsUpdate( $data ) {
		try {
			list( $cryptType, $remainingData ) = self::parseType( $data );
		} catch( PasswordDataError $e ) {
			// If parseType had issues then this naturally needs an update
			return true;
		}
		
		if ( $cryptType->getName() !== self::$preferredType ) {
			// If cryptType's name does not match the preferred type it needs an update
			return true;
		}

		try {
			// Ask cryptType if an update is needed
			return (bool)$cryptType->needsUpdate( $remainingData );
		} catch( PasswordDataError $e ) {
			// If there was an issue with the data so it needs an update
			return true;
		}

		// If everything looked fine, then it's preferred
		return false;
	}

}
