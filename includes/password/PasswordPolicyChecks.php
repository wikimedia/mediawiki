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

use \Cdb\Reader as CdbReader;

/**
 * Functions to check passwords against a policy requirement
 * @since 1.26
 */
class PasswordPolicyChecks {

	/**
	 * Check password is longer than minimum, not fatal
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
	 * Check password is longer than minimum, fatal
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
	 * Check password is shorter than maximum, fatal
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
	 * Check if username and password match
	 * @param bool $policyVal true to force compliance.
	 * @param User $user
	 * @param string $password
	 * @return Status error if username and password match, and policy is true
	 */
	public static function checkPasswordCannotMatchUsername( $policyVal, User $user, $password ) {
		global $wgContLang;
		$status = Status::newGood();
		$username = $user->getName();
		if ( $policyVal && $wgContLang->lc( $password ) === $wgContLang->lc( $username ) ) {
			$status->error( 'password-name-match' );
		}
		return $status;
	}

	/**
	 * Check if username and password are on a blacklist
	 * @param bool $policyVal true to force compliance.
	 * @param User $user
	 * @param string $password
	 * @return Status error if username and password match, and policy is true
	 */
	public static function checkPasswordCannotMatchBlacklist( $policyVal, User $user, $password ) {
		static $blockedLogins = [
			'Useruser' => 'Passpass', 'Useruser1' => 'Passpass1', # r75589
			'Apitestsysop' => 'testpass', 'Apitestuser' => 'testpass' # r75605
		];

		$status = Status::newGood();
		$username = $user->getName();
		if ( $policyVal ) {
			if ( isset( $blockedLogins[$username] ) && $password == $blockedLogins[$username] ) {
				$status->error( 'password-login-forbidden' );
			}

			// Example from ApiChangeAuthenticationRequest
			if ( $password === 'ExamplePassword' ) {
				$status->error( 'password-login-forbidden' );
			}
		}
		return $status;
	}

	/**
	 * Ensure that password isn't in top X most popular passwords
	 *
	 * @param int $policyVal Cut off to use. Will automatically shrink to the max
	 *   supported for error messages if set to more than max number of passwords on file,
	 *   so you can use the PHP_INT_MAX constant here safely.
	 * @param User $user
	 * @param string $password
	 * @since 1.27
	 * @return Status
	 */
	public static function checkPopularPasswordBlacklist( $policyVal, User $user, $password ) {
		global $wgPopularPasswordFile, $wgSitename;
		$status = Status::newGood();
		if ( $policyVal > 0 ) {
			$langEn = Language::factory( 'en' );
			$passwordKey = $langEn->lc( trim( $password ) );

			// People often use the name of the current site, which won't be
			// in the common password file. Also check '' for people who use
			// just whitespace.
			$sitename = $langEn->lc( trim( $wgSitename ) );
			$hardcodedCommonPasswords = [ '', 'wiki', 'mediawiki', $sitename ];
			if ( in_array( $passwordKey, $hardcodedCommonPasswords ) ) {
				$status->error( 'passwordtoopopular' );
				return $status;
			}

			// This could throw an exception, but there's not a good way
			// of failing gracefully, if say the file is missing, so just
			// let the exception fall through.
			// Format of cdb file is mapping password => popularity rank.
			// See maintenance/createCommonPasswordCdb.php
			$db = CdbReader::open( $wgPopularPasswordFile );

			$res = $db->get( $passwordKey );
			if ( $res && (int)$res <= $policyVal ) {
				// Note: If you want to find the true number of common
				// passwords stored (for reporting the error), you have to take
				// the max of the policyVal and $db->get( '_TOTALENTRIES' ).
				$status->error( 'passwordtoopopular' );
			}
		}
		return $status;
	}

}
