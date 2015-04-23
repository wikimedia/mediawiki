<?php
/**
 * Password policy checking for a user
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
 * Maps policy statements to the functions that will test them for a user.
 * @since 1.26
 */
class UserPasswordPolicy {

	/**
	 * @var array
	 */
	private $policies;

	/**
	 * Mapping of statements to the function that will test the password for compliance. The
	 * checking functions take the policy value, username, and password, and return a Status
	 * object indicating compliance.
	 * @var array
	 */
	private static $policyCheckFunctions = array(
		'MinimalPasswordLength' => 'UserPasswordPolicy::checkMinimalPasswordLength',
		'MinimumPasswordLengthToLogin' => 'UserPasswordPolicy::checkMinimumPasswordLengthToLogin',
		'PasswordCannotMatchUsername' => 'UserPasswordPolicy::checkPasswordCannotMatchUsername',
		'PasswordCannotMatchBlacklist' => 'UserPasswordPolicy::checkPasswordCannotMatchBlacklist',
		'MaximalPasswordLength' => 'UserPasswordPolicy::checkMaximalPasswordLength',
	);

	/**
	 * @param array $policies
	 */
	public function __construct( array $policies ) {
		if ( count( $policies ) < 1 || !isset( $policies['default'] ) ) {
			throw new InvalidArgumentException(
				'Must include a \'default\' password policy'
			);
		}
		$this->policies = $policies;
	}

	/**
	 * Add statement -> checking-function mappings, so extensions can easily add
	 * their own password validity checks
	 * @param array $mappings between policy statements and functions to check compliance
	 */
	public static function addPolicyCheckFunction( array $mappings ) {
		if ( !is_array( $mappings ) ) {
			throw new InvalidArgumentException(
				'Trying to add non-array password validity mapping'
			);
		}
		self::$policyCheckFunctions += $mappings;
	}

	/**
	 * Get the policy for a user, based on their group membership. Public so
	 * UI elements can access and inform the user.
	 * @param User $user
	 * @return array the effective policy for $user
	 */
	public function getPoliciesForUser( User $user ) {
		$usersGroups = $user->getEffectiveGroups();
		$effectivePolicy = $this->policies['default'];
		foreach ( array_keys( $this->policies ) as $group ) {
			if ( in_array( $group, $usersGroups ) ) {
				$effectivePolicy = self::maxOfPolicies(
					$effectivePolicy,
					$this->policies[$group]
				);
			}
		}
		return $effectivePolicy;
	}

	/**
	 * Check if a passwords meets the effective password policy for a User.
	 * @param User $user who's policy we are checking
	 * @param string $password the password to check
	 * @return Status error to indicate the password didn't meet the policy, or fatal to
	 *	indicate the user shouldn't be allowed to login.
	 */
	public function checkUserPassword( User $user, $password ) {
		$effectivePolicy = $this->getPoliciesForUser( $user );
		$status = Status::newGood();

		foreach ( $effectivePolicy as $policy => $value ) {
			if ( !isset( self::$policyCheckFunctions[$policy] ) ) {
				throw new DomainException( 'Invalid password policy config' );
			}
			$status->merge(
				call_user_func(
					self::$policyCheckFunctions[$policy],
					$value,
					$user->getName(),
					$password
				)
			);
		}

		return $status;
	}

	/**
	 * Check password is longer than minimum, not fatal
	 * @param int $policyVal minimal length
	 * @param string $username
	 * @param string $password
	 * @return Status error if $password is shorter than $policyVal
	 */
	public static function checkMinimalPasswordLength( $policyVal, $username, $password ) {
		$status = Status::newGood();
		if ( $policyVal > strlen( $password ) ) {
			$status->error( 'passwordtooshort', $policyVal );
		}
		return $status;
	}

	/**
	 * Check password is longer than minimum, fatal
	 * @param int $policyVal minimal length
	 * @param string $username
	 * @param string $password
	 * @return Status fatal if $password is shorter than $policyVal
	 */
	public static function checkMinimumPasswordLengthToLogin( $policyVal, $username, $password ) {
		$status = Status::newGood();
		if ( $policyVal > strlen( $password ) ) {
			$status->fatal( 'passwordtooshort', $policyVal );
		}
		return $status;
	}

	/**
	 * Check password is shorter than maximum, fatal
	 * @param int $policyVal maximum length
	 * @param string $username
	 * @param string $password
	 * @return Status fatal if $password is shorter than $policyVal
	 */
	public static function checkMaximalPasswordLength( $policyVal, $username, $password ) {
		$status = Status::newGood();
		if ( $policyVal < strlen( $password ) ) {
			$status->fatal( 'passwordtoolong', $policyVal );
		}
		return $status;
	}

	/**
	 * Check if username and password match
	 * @param int $policyVal 0 to skip this check, any other value to force compliance.
	 * @param string $username
	 * @param string $password
	 * @return Status error if username and password match, and policy is true
	 */
	public static function checkPasswordCannotMatchUsername( $policyVal, $username, $password ) {
		global $wgContLang;
		$status = Status::newGood();
		if ( $policyVal && $wgContLang->lc( $password ) === $wgContLang->lc( $username ) ) {
			$status->error( 'password-name-match' );
		}
		return $status;
	}

	/**
	 * Check if username and password are on a blacklist
	 * @param int $policyVal 0 to skip this check, any other value to force compliance.
	 * @param string $username
	 * @param string $password
	 * @return Status error if username and password match, and policy is true
	 */
	public static function checkPasswordCannotMatchBlacklist( $policyVal, $username, $password ) {
		static $blockedLogins = array(
			'Useruser' => 'Passpass', 'Useruser1' => 'Passpass1', # r75589
			'Apitestsysop' => 'testpass', 'Apitestuser' => 'testpass' # r75605
		);

		$status = Status::newGood();
		if ( $policyVal
			&& isset( $blockedLogins[$username] )
			&& $password == $blockedLogins[$username]
		) {
			$status->error( 'password-login-forbidden' );
		}
		return $status;
	}
	/**
	 * Get a policy that is the most restrictive of $p1 and $p2. For simplicity,
	 * we setup the policy values so a larger value is always more restrictive.
	 * @param array $p1
	 * @param array $p2
	 * @return array containing the more restrictive values of $p1 and $p2
	 */
	public static function maxOfPolicies( $p1, $p2 ) {
		$ret = array();
		$keys = array_merge( array_keys( $p1 ), array_keys( $p2 ) );
		foreach ( $keys as $key ) {
			if ( !isset( $p1[$key] ) ) {
				$ret[$key] = $p2[$key];
			} elseif ( !isset( $p2[$key] ) ) {
				$ret[$key] = $p1[$key];
			} else {
				$ret[$key] = max( $p1[$key], $p2[$key] );
			}
		}
		return $ret;
	}

}

