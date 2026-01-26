<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User;

use InvalidArgumentException;
use LogicException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Context\IContextSource;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MainConfigNames;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Permissions\GroupPermissionsLookup;
use MediaWiki\User\Registration\UserRegistrationLookup;
use MediaWiki\WikiMap\WikiMap;
use Psr\Log\LoggerInterface;
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Timestamp\TimestampFormat as TS;

/**
 * @since 1.45
 */
class UserRequirementsConditionChecker {

	/**
	 * Logical operators recognized in $wgAutopromote.
	 *
	 * @since 1.45
	 */
	public const VALID_OPS = [ '&', '|', '^', '!' ];

	/** @internal For use by ServiceWiring */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::AutoConfirmAge,
		MainConfigNames::AutoConfirmCount,
		MainConfigNames::EmailAuthentication,
		MainConfigNames::UserRequirementsPrivateConditions,
	];

	/**
	 * @internal For use preventing an infinite loop when checking APCOND_BLOCKED
	 * @var array An assoc. array mapping the getCacheKey userKey to a bool indicating
	 * an ongoing condition check.
	 */
	private array $recursionMap = [];

	private HookRunner $hookRunner;

	public function __construct(
		private readonly ServiceOptions $options,
		private readonly GroupPermissionsLookup $groupPermissionsLookup,
		HookContainer $hookContainer,
		private readonly LoggerInterface $logger,
		private readonly UserEditTracker $userEditTracker,
		private readonly UserRegistrationLookup $userRegistrationLookup,
		private readonly UserFactory $userFactory,
		private readonly IContextSource $context,
		private readonly UserGroupManager $userGroupManager,
		private readonly string|false $wikiId = UserIdentity::LOCAL,
	) {
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
	}

	/**
	 * As recursivelyCheckCondition, but *not* recursive. The only valid conditions
	 * are those whose first element is one of APCOND_* defined in Defines.php.
	 * Other types will throw an exception if no extension evaluates them.
	 *
	 * @param array $cond A condition, which must not contain other conditions
	 * @param UserIdentity $user The user to check the condition against
	 * @param array<string,bool> $skippedConditions Array whose keys tell which conditions to skip while evaluating
	 * @return ?bool Whether the condition is true for the user. Null if it's a private condition
	 *     and we're not supposed to evaluate these.
	 * @throws InvalidArgumentException if autopromote condition was not recognized.
	 * @throws LogicException if APCOND_BLOCKED is checked again before returning a result.
	 */
	private function checkCondition( array $cond, UserIdentity $user, array $skippedConditions ): ?bool {
		if ( count( $cond ) < 1 ) {
			return false;
		}

		if ( isset( $skippedConditions[$cond[0]] ) ) {
			return null;
		}

		$isPerformingRequest = !defined( 'MW_NO_SESSION' ) && $user->equals( $this->context->getUser() );

		// Some checks depend on hooks or other dynamically-determined state, so we can fetch them only
		// for the local wiki and not for remote users. The latter may require API requests to the remote
		// wiki, which has not been implemented for now due to performance concerns.
		$isCurrentWiki = ( $user->getWikiId() === false ) || WikiMap::isCurrentWikiId( $user->getWikiId() );

		switch ( $cond[0] ) {
			case APCOND_EMAILCONFIRMED:
				if ( !$isCurrentWiki ) {
					return false;
				}
				$userObject = $this->userFactory->newFromUserIdentity( $user );
				return Sanitizer::validateEmail( $userObject->getEmail() ) &&
					( !$this->options->get( MainConfigNames::EmailAuthentication ) ||
						$userObject->getEmailAuthenticationTimestamp() );
			case APCOND_EDITCOUNT:
				$reqEditCount = $cond[1] ?? $this->options->get( MainConfigNames::AutoConfirmCount );

				// T157718: Avoid edit count lookup if the specified edit count is 0 or invalid
				if ( $reqEditCount <= 0 ) {
					return true;
				}
				return (int)$this->userEditTracker->getUserEditCount( $user ) >= $reqEditCount;
			case APCOND_AGE:
				$reqAge = $cond[1] ?? $this->options->get( MainConfigNames::AutoConfirmAge );
				$registration = $this->userRegistrationLookup->getRegistration( $user );
				$age = time() - (int)wfTimestampOrNull( TS::UNIX, $registration );
				return $age >= $reqAge;
			case APCOND_AGE_FROM_EDIT:
				$age = time() - (int)wfTimestampOrNull(
					TS::UNIX,
					$this->userEditTracker->getFirstEditTimestamp( $user )
				);
				return $age >= $cond[1];
			case APCOND_INGROUPS:
				if ( !$isCurrentWiki ) {
					return false;
				}
				$groups = array_slice( $cond, 1 );
				return count( array_intersect(
						$groups,
						$this->userGroupManager->getUserGroups( $user )
					) ) === count( $groups );
			case APCOND_ISIP:
				// Since the IPs are not permanently bound to users, the IP conditions can only be checked
				// for the requesting user. Otherwise, assume the condition is false.
				return $isPerformingRequest && $cond[1] === $this->context->getRequest()->getIP();
			case APCOND_IPINRANGE:
				return $isPerformingRequest && IPUtils::isInRange( $this->context->getRequest()->getIP(), $cond[1] );
			case APCOND_BLOCKED:
				if ( !$isCurrentWiki ) {
					// This condition is more likely to be used as "! APCOND_BLOCKED", so ensure it can't be bypassed
					// when tested from a remote wiki.
					return true;
				}
				// Because checking for ipblock-exempt leads back to here (thus infinite recursion),
				// we if we've been here before for this user without having returned a value.
				// See T270145 and T349608
				$userKey = $this->getCacheKey( $user );
				if ( $this->recursionMap[$userKey] ?? false ) {
					throw new LogicException(
						"Unexpected recursion! APCOND_BLOCKED is being checked during" .
						" an existing APCOND_BLOCKED check for \"{$user->getName()}\"!"
					);
				}
				$this->recursionMap[$userKey] = true;
				// Setting the second parameter here to true prevents us from getting back here
				// during standard MediaWiki core behavior
				$userObject = $this->userFactory->newFromUserIdentity( $user );
				$block = $userObject->getBlock( IDBAccessObject::READ_LATEST, true );
				$this->recursionMap[$userKey] = false;

				return (bool)$block?->isSitewide();
			case APCOND_ISBOT:
				if ( !$isCurrentWiki ) {
					return false;
				}
				return in_array( 'bot', $this->groupPermissionsLookup
					->getGroupPermissions(
						$this->userGroupManager->getUserGroups( $user )
					)
				);
			default:
				$result = null;
				$type = $cond[0];
				$args = array_slice( $cond, 1 );
				$this->hookRunner->onUserRequirementsCondition( $type, $args, $user, $isPerformingRequest, $result );

				if ( $isPerformingRequest && $isCurrentWiki ) {
					// The legacy hook is run only if the tested user is the one performing
					// the request (like for autopromote), and the user is from the local wiki.
					// If any of these conditions is not met, we cannot invoke the hook,
					// as it may produce incorrect results.
					$userObject = $this->userFactory->newFromUserIdentity( $user );
					$this->hookRunner->onAutopromoteCondition( $type, $args, $userObject, $result );
				}

				if ( $result === null ) {
					throw new InvalidArgumentException(
						"Unrecognized condition $type in UserRequirementsCondition!"
					);
				}

				return (bool)$result;
		}
	}

	/**
	 * Recursively check a condition. Conditions are in the form
	 *   [ '&' or '|' or '^' or '!', cond1, cond2, ... ]
	 * where cond1, cond2, ... are themselves conditions; *OR*
	 *   APCOND_EMAILCONFIRMED, *OR*
	 *   [ APCOND_EMAILCONFIRMED ], *OR*
	 *   [ APCOND_EDITCOUNT, number of edits ], *OR*
	 *   [ APCOND_AGE, seconds since registration ], *OR*
	 *   similar constructs defined by extensions.
	 * This function evaluates the former type recursively, and passes off to
	 * checkCondition for evaluation of the latter type.
	 *
	 * If you change the logic of this method, please update
	 * ApiQuerySiteinfo::appendAutoPromote(), as it depends on this method.
	 *
	 * @param mixed $cond A condition, possibly containing other conditions
	 * @param UserIdentity $user The user to check the conditions against
	 * @param bool $usePrivateConditions Whether to evaluate private conditions
	 *
	 * @return ?bool Whether the condition is true; will be null if the condition value depends on any of the
	 *      unevaluated private conditions. Non-null value means that the skipped conditions have no effect
	 *      on the result. Null can be returned only if $usePrivateConditions is false.
	 */
	public function recursivelyCheckCondition( $cond, UserIdentity $user, bool $usePrivateConditions = true ): ?bool {
		$skippedConditions = [];
		if ( !$usePrivateConditions ) {
			$skippedConditions = $this->options->get( MainConfigNames::UserRequirementsPrivateConditions );
			$skippedConditions = array_fill_keys( $skippedConditions, true );
		}

		return $this->recursivelyCheckConditionInternal( $cond, $user, $skippedConditions );
	}

	/**
	 * Internal version of recursivelyCheckCondition, which operates on three-valued logic, for
	 * the purpose of supporting private conditions. The third state, beyond false and true, is
	 * null, which is recognized as an unknown value (e.g., false | null = null, true | null = true).
	 *
	 * @param mixed $cond A condition, possibly containing other conditions
	 * @param UserIdentity $user The user to check the conditions against
	 * @param array<string,bool> $skippedConditions Array whose keys tell which conditions to skip while evaluating
	 * @return ?bool Whether the condition is true; will be null if the condition value depends on any of
	 *     $skippedConditions. Non-null value means that the skipped conditions have no effect on the result.
	 */
	private function recursivelyCheckConditionInternal( $cond, UserIdentity $user, array $skippedConditions ): ?bool {
		if ( is_array( $cond ) && count( $cond ) >= 2 && in_array( $cond[0], self::VALID_OPS ) ) {
			// Recursive condition

			// AND (all conditions pass)
			if ( $cond[0] === '&' ) {
				$hasNulls = false;
				foreach ( array_slice( $cond, 1 ) as $subcond ) {
					$result = $this->recursivelyCheckConditionInternal( $subcond, $user, $skippedConditions );
					if ( $result === false ) {
						return false;
					}
					$hasNulls = $hasNulls || $result === null;
				}

				return $hasNulls ? null : true;
			}

			// OR (at least one condition passes)
			if ( $cond[0] === '|' ) {
				$hasNulls = false;
				foreach ( array_slice( $cond, 1 ) as $subcond ) {
					$result = $this->recursivelyCheckConditionInternal( $subcond, $user, $skippedConditions );
					if ( $result === true ) {
						return true;
					}
					$hasNulls = $hasNulls || $result === null;
				}

				return $hasNulls ? null : false;
			}

			// XOR (exactly one condition passes)
			if ( $cond[0] === '^' ) {
				if ( count( $cond ) > 3 ) {
					$this->logger->warning(
						'recursivelyCheckCondition() given XOR ("^") condition on three or more conditions.' .
						' Check your $wgRestrictedGroups, $wgAutopromote and $wgAutopromoteOnce settings.'
					);
				}
				$result1 = $this->recursivelyCheckConditionInternal( $cond[1], $user, $skippedConditions );
				$result2 = $this->recursivelyCheckConditionInternal( $cond[2], $user, $skippedConditions );
				if ( $result1 === null || $result2 === null ) {
					return null;
				}
				return $result1 xor $result2;
			}

			// NOT (no conditions pass)
			if ( $cond[0] === '!' ) {
				$hasNulls = false;
				foreach ( array_slice( $cond, 1 ) as $subcond ) {
					$result = $this->recursivelyCheckConditionInternal( $subcond, $user, $skippedConditions );
					if ( $result === true ) {
						return false;
					}
					$hasNulls = $hasNulls || $result === null;
				}

				return $hasNulls ? null : true;
			}
		}
		// If we got here, the array presumably does not contain other conditions;
		// it's not recursive. Pass it off to checkCondition.
		if ( !is_array( $cond ) ) {
			$cond = [ $cond ];
		}

		return $this->checkCondition( $cond, $user, $skippedConditions );
	}

	/**
	 * Goes through a condition passed as the input and extracts all private conditions that are used within it.
	 * @param mixed $cond A condition, possibly containing other conditions.
	 * @return list<mixed> A list of unique private conditions present in $cond
	 */
	public function extractPrivateConditions( $cond ): array {
		$privateConditions = $this->options->get( MainConfigNames::UserRequirementsPrivateConditions );
		$result = $this->extractPrivateConditionsInternal( $cond, $privateConditions );
		return array_values( array_unique( $result ) );
	}

	/**
	 * Internal backend for {@see extractPrivateConditions}. It returns a list of all private conditions found
	 * in the input conditions. The result may contain duplicates.
	 * @param mixed $cond
	 * @param list<string> $privateConditions
	 * @return list<mixed>
	 */
	private function extractPrivateConditionsInternal( $cond, array $privateConditions ): array {
		$result = [];
		if ( is_array( $cond ) ) {
			$op = $cond[0];
			if ( in_array( $op, self::VALID_OPS ) ) {
				foreach ( array_slice( $cond, 1 ) as $subcond ) {
					$result = array_merge(
						$result, $this->extractPrivateConditionsInternal( $subcond, $privateConditions ) );
				}
			} elseif ( in_array( $op, $privateConditions ) ) {
				$result[] = $op;
			}
		} elseif ( in_array( $cond, $privateConditions ) ) {
			$result[] = $cond;
		}
		return $result;
	}

	/**
	 * Gets a unique key for various caches.
	 */
	private function getCacheKey( UserIdentity $user ): string {
		return $user->isRegistered() ? "u:{$user->getId( $this->wikiId )}" : "anon:{$user->getName()}";
	}
}
