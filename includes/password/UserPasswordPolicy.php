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
 * Check if a user's password complies with any password policies that apply to that
 * user, based on the user's group membership.
 * @since 1.26
 */
class UserPasswordPolicy {

	/**
	 * @var array
	 */
	private $policies;

	/**
	 * Mapping of statements to the function that will test the password for compliance. The
	 * checking functions take the policy value, the user, and password, and return a Status
	 * object indicating compliance.
	 * @var array
	 */
	private $policyCheckFunctions;

	/**
	 * @param array $policies
	 * @param array $checks mapping statement to its checking function. Checking functions are
	 * called with the policy value for this user, the user object, and the password to check.
	 */
	public function __construct( array $policies, array $checks ) {
		if ( !isset( $policies['default'] ) ) {
			throw new InvalidArgumentException(
				'Must include a \'default\' password policy'
			);
		}
		$this->policies = $policies;

		foreach ( $checks as $statement => $check ) {
			if ( !is_callable( $check ) ) {
				throw new InvalidArgumentException(
					"Policy check functions must be callable. '$statement' isn't callable."
				);
			}
			$this->policyCheckFunctions[$statement] = $check;
		}
	}

	/**
	 * Check if a passwords meets the effective password policy for a User.
	 * @param User $user who's policy we are checking
	 * @param string $password the password to check
	 * @return Status error to indicate the password didn't meet the policy, or fatal to
	 * 	indicate the user shouldn't be allowed to login.
	 */
	public function checkUserPassword( User $user, $password ) {
		$effectivePolicy = $this->getPoliciesForUser( $user );
		return $this->checkPolicies(
			$user,
			$password,
			$effectivePolicy,
			$this->policyCheckFunctions
		);
	}

	/**
	 * Check if a passwords meets the effective password policy for a User, using a set
	 * of groups they may or may not belong to. This function does not use the DB, so can
	 * be used in the installer.
	 * @param User $user who's policy we are checking
	 * @param string $password the password to check
	 * @param array $groups list of groups to which we assume the user belongs
	 * @return Status error to indicate the password didn't meet the policy, or fatal to
	 * 	indicate the user shouldn't be allowed to login.
	 */
	public function checkUserPasswordForGroups( User $user, $password, array $groups ) {
		$effectivePolicy = self::getPoliciesForGroups(
			$this->policies,
			$groups,
			$this->policies['default']
		);
		return $this->checkPolicies(
			$user,
			$password,
			$effectivePolicy,
			$this->policyCheckFunctions
		);
	}

	/**
	 * @param User $user
	 * @param string $password
	 * @param array $policies
	 * @param array $policyCheckFunctions
	 * @return Status
	 */
	private function checkPolicies( User $user, $password, $policies, $policyCheckFunctions ) {
		$status = Status::newGood();
		foreach ( $policies as $policy => $value ) {
			if ( !isset( $policyCheckFunctions[$policy] ) ) {
				throw new DomainException( "Invalid password policy config. No check defined for '$policy'." );
			}
			$status->merge(
				call_user_func(
					$policyCheckFunctions[$policy],
					$value,
					$user,
					$password
				)
			);
		}
		return $status;
	}

	/**
	 * Get the policy for a user, based on their group membership. Public so
	 * UI elements can access and inform the user.
	 * @param User $user
	 * @return array the effective policy for $user
	 */
	public function getPoliciesForUser( User $user ) {
		$effectivePolicy = self::getPoliciesForGroups(
			$this->policies,
			$user->getEffectiveGroups(),
			$this->policies['default']
		);

		Hooks::run( 'PasswordPoliciesForUser', [ $user, &$effectivePolicy ] );

		return $effectivePolicy;
	}

	/**
	 * Utility function to get the effective policy from a list of policies, based
	 * on a list of groups.
	 * @param array $policies list of policies to consider
	 * @param array $userGroups the groups from which we calculate the effective policy
	 * @param array $defaultPolicy the default policy to start from
	 * @return array effective policy
	 */
	public static function getPoliciesForGroups( array $policies, array $userGroups,
		array $defaultPolicy
	) {
		$effectivePolicy = $defaultPolicy;
		foreach ( $policies as $group => $policy ) {
			if ( in_array( $group, $userGroups ) ) {
				$effectivePolicy = self::maxOfPolicies(
					$effectivePolicy,
					$policies[$group]
				);
			}
		}

		return $effectivePolicy;
	}

	/**
	 * Utility function to get a policy that is the most restrictive of $p1 and $p2. For
	 * simplicity, we setup the policy values so the maximum value is always more restrictive.
	 * @param array $p1
	 * @param array $p2
	 * @return array containing the more restrictive values of $p1 and $p2
	 */
	public static function maxOfPolicies( array $p1, array $p2 ) {
		$ret = [];
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
