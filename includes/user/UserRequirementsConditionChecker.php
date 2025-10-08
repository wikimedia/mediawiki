<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User;

use InvalidArgumentException;
use LogicException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MainConfigNames;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Permissions\GroupPermissionsLookup;
use Psr\Log\LoggerInterface;
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\IDBAccessObject;

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
		private readonly UserGroupManager $userGroupManager,
		private readonly string|false $wikiId = UserIdentity::LOCAL,
	) {
		$this->hookRunner = new HookRunner( $hookContainer );
	}

	/**
	 * As recursivelyCheckCondition, but *not* recursive. The only valid conditions
	 * are those whose first element is one of APCOND_* defined in Defines.php.
	 * Other types will throw an exception if no extension evaluates them.
	 *
	 * @param array $cond A condition, which must not contain other conditions
	 * @param User $user The user to check the condition against
	 * @return bool Whether the condition is true for the user
	 * @throws InvalidArgumentException if autopromote condition was not recognized.
	 * @throws LogicException if APCOND_BLOCKED is checked again before returning a result.
	 */
	private function checkCondition( array $cond, User $user ): bool {
		if ( count( $cond ) < 1 ) {
			return false;
		}

		switch ( $cond[0] ) {
			case APCOND_EMAILCONFIRMED:
				return Sanitizer::validateEmail( $user->getEmail() ) &&
					( !$this->options->get( MainConfigNames::EmailAuthentication ) ||
						$user->getEmailAuthenticationTimestamp() );
			case APCOND_EDITCOUNT:
				$reqEditCount = $cond[1] ?? $this->options->get( MainConfigNames::AutoConfirmCount );

				// T157718: Avoid edit count lookup if the specified edit count is 0 or invalid
				if ( $reqEditCount <= 0 ) {
					return true;
				}
				return (int)$this->userEditTracker->getUserEditCount( $user ) >= $reqEditCount;
			case APCOND_AGE:
				$reqAge = $cond[1] ?? $this->options->get( MainConfigNames::AutoConfirmAge );
				$age = time() - (int)wfTimestampOrNull( TS_UNIX, $user->getRegistration() );
				return $age >= $reqAge;
			case APCOND_AGE_FROM_EDIT:
				$age = time() - (int)wfTimestampOrNull(
					TS_UNIX,
					$this->userEditTracker->getFirstEditTimestamp( $user )
				);
				return $age >= $cond[1];
			case APCOND_INGROUPS:
				$groups = array_slice( $cond, 1 );
				return count( array_intersect(
						$groups,
						$this->userGroupManager->getUserGroups( $user )
					) ) === count( $groups );
			case APCOND_ISIP:
				return $cond[1] === $user->getRequest()->getIP();
			case APCOND_IPINRANGE:
				return IPUtils::isInRange( $user->getRequest()->getIP(), $cond[1] );
			case APCOND_BLOCKED:
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
				$block = $user->getBlock( IDBAccessObject::READ_LATEST, true );
				$this->recursionMap[$userKey] = false;

				return (bool)$block?->isSitewide();
			case APCOND_ISBOT:
				return in_array( 'bot', $this->groupPermissionsLookup
					->getGroupPermissions(
						$this->userGroupManager->getUserGroups( $user )
					)
				);
			default:
				$result = null;
				$this->hookRunner->onAutopromoteCondition( $cond[0],
					array_slice( $cond, 1 ), $user, $result );
				if ( $result === null ) {
					throw new InvalidArgumentException(
						"Unrecognized condition $cond[0] for autopromotion!"
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
	 * @param User $user The user to check the conditions against
	 *
	 * @return bool Whether the condition is true
	 */
	public function recursivelyCheckCondition( $cond, User $user ): bool {
		if ( is_array( $cond ) && count( $cond ) >= 2 && in_array( $cond[0], self::VALID_OPS ) ) {
			// Recursive condition

			// AND (all conditions pass)
			if ( $cond[0] === '&' ) {
				foreach ( array_slice( $cond, 1 ) as $subcond ) {
					if ( !$this->recursivelyCheckCondition( $subcond, $user ) ) {
						return false;
					}
				}

				return true;
			}

			// OR (at least one condition passes)
			if ( $cond[0] === '|' ) {
				foreach ( array_slice( $cond, 1 ) as $subcond ) {
					if ( $this->recursivelyCheckCondition( $subcond, $user ) ) {
						return true;
					}
				}

				return false;
			}

			// XOR (exactly one condition passes)
			if ( $cond[0] === '^' ) {
				if ( count( $cond ) > 3 ) {
					$this->logger->warning(
						'recCheckCondition() given XOR ("^") condition on three or more conditions.' .
						' Check your $wgAutopromote and $wgAutopromoteOnce settings.'
					);
				}
				return $this->recursivelyCheckCondition( $cond[1], $user )
					xor $this->recursivelyCheckCondition( $cond[2], $user );
			}

			// NOT (no conditions pass)
			if ( $cond[0] === '!' ) {
				foreach ( array_slice( $cond, 1 ) as $subcond ) {
					if ( $this->recursivelyCheckCondition( $subcond, $user ) ) {
						return false;
					}
				}

				return true;
			}
		}
		// If we got here, the array presumably does not contain other conditions;
		// it's not recursive. Pass it off to checkCondition.
		if ( !is_array( $cond ) ) {
			$cond = [ $cond ];
		}

		return $this->checkCondition( $cond, $user );
	}

	/**
	 * Gets a unique key for various caches.
	 */
	private function getCacheKey( UserIdentity $user ): string {
		return $user->isRegistered() ? "u:{$user->getId( $this->wikiId )}" : "anon:{$user->getName()}";
	}
}
