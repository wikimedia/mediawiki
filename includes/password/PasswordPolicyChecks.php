<?php
/**
 * Password policy checks
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

use MediaWiki\MediaWikiServices;
use Wikimedia\CommonPasswords\CommonPasswords;

/**
 * Functions to check passwords against a policy requirement.
 *
 * $policyVal is the value configured in $wgPasswordPolicy. If the return status is fatal,
 * the user won't be allowed to login. If the status is not good but not fatal, the user
 * will not be allowed to set the given password (on registration or password change),
 * but can still log in after bypassing a warning.
 *
 * @since 1.26
 * @see $wgPasswordPolicy
 */
class PasswordPolicyChecks {

	/**
	 * Check password is longer than minimum, not fatal.
	 * @param int $policyVal minimal length
	 * @param User $user
	 * @param string $password
	 * @return Status error if $password is shorter than $policyVal
	 */
	public static function checkMinimalPasswordLength( $policyVal, User $user, $password ) {
		$status = Status::newGood();
		if ( $policyVal > strlen( $password ) ) {
			$status->error( 'passwordtooshort', $policyVal );
		}
		return $status;
	}

	/**
	 * Check password is longer than minimum, fatal.
	 * Intended for locking out users with passwords too short to trust, requiring them
	 * to recover their account by some other means.
	 * @param int $policyVal minimal length
	 * @param User $user
	 * @param string $password
	 * @return Status fatal if $password is shorter than $policyVal
	 */
	public static function checkMinimumPasswordLengthToLogin( $policyVal, User $user, $password ) {
		$status = Status::newGood();
		if ( $policyVal > strlen( $password ) ) {
			$status->fatal( 'passwordtooshort', $policyVal );
		}
		return $status;
	}

	/**
	 * Check password is shorter than maximum, fatal.
	 * Intended for preventing DoS attacks when using a more expensive password hash like PBKDF2.
	 * @param int $policyVal maximum length
	 * @param User $user
	 * @param string $password
	 * @return Status fatal if $password is shorter than $policyVal
	 */
	public static function checkMaximalPasswordLength( $policyVal, User $user, $password ) {
		$status = Status::newGood();
		if ( $policyVal < strlen( $password ) ) {
			$status->fatal( 'passwordtoolong', $policyVal );
		}
		return $status;
	}

	/**
	 * Check if username and password are a (case-insensitive) match.
	 * @param bool $policyVal true to force compliance.
	 * @param User $user
	 * @param string $password
	 * @return Status error if username and password match, and policy is true
	 */
	public static function checkPasswordCannotMatchUsername( $policyVal, User $user, $password ) {
		$status = Status::newGood();
		$username = $user->getName();
		$contLang = MediaWikiServices::getInstance()->getContentLanguage();
		if (
			$policyVal && hash_equals( $contLang->lc( $username ), $contLang->lc( $password ) )
		) {
			$status->error( 'password-name-match' );
		}
		return $status;
	}

	/**
	 * Check if password is a (case-insensitive) substring within the username.
	 * @param bool $policyVal true to force compliance.
	 * @param User $user
	 * @param string $password
	 * @return Status error if password is a substring within username, and policy is true
	 */
	public static function checkPasswordCannotBeSubstringInUsername(
		$policyVal,
		User $user,
		$password
	) {
		$status = Status::newGood();
		$username = $user->getName();
		if ( $policyVal && stripos( $username, $password ) !== false ) {
			$status->error( 'password-substring-username-match' );
		}
		return $status;
	}

	/**
	 * Check if username and password are on a list of past MediaWiki default passwords.
	 * @param bool $policyVal true to force compliance.
	 * @param User $user
	 * @param string $password
	 * @return Status error if username and password match, and policy is true
	 */
	public static function checkPasswordCannotMatchDefaults( $policyVal, User $user, $password ) {
		static $blockedLogins = [
			// r75589
			'Useruser' => 'Passpass',
			'Useruser1' => 'Passpass1',
			// r75605
			'Apitestsysop' => 'testpass',
			'Apitestuser' => 'testpass',
		];

		$status = Status::newGood();
		$username = $user->getName();
		if ( $policyVal ) {
			if (
				isset( $blockedLogins[$username] ) &&
				hash_equals( $blockedLogins[$username], $password )
			) {
				$status->error( 'password-login-forbidden' );
			}

			// Example from ApiChangeAuthenticationRequest
			if ( hash_equals( 'ExamplePassword', $password ) ) {
				$status->error( 'password-login-forbidden' );
			}
		}
		return $status;
	}

	/**
	 * Ensure the password isn't in the list of common passwords by the
	 * wikimedia/common-passwords library, which contains (as of 0.2.0) the
	 * 100,000 top passwords from SecLists (as a Bloom filter, with an
	 * 0.000001 false positive ratio).
	 *
	 * @param bool $policyVal Whether to apply this policy
	 * @param User $user
	 * @param string $password
	 *
	 * @since 1.33
	 *
	 * @return Status
	 */
	public static function checkPasswordNotInCommonList( $policyVal, User $user, $password ) {
		$status = Status::newGood();
		if ( $policyVal && CommonPasswords::isCommon( $password ) ) {
			$status->error( 'passwordincommonlist' );
		}

		return $status;
	}

}
