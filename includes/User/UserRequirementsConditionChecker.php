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
use MediaWiki\WikiMap\WikiMap;

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

	/** @internal For use by UserRequirementsConditionCheckerFactory */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::UserRequirementsPrivateConditions,
	];

	private HookRunner $hookRunner;

	public function __construct(
		private readonly ServiceOptions $options,
		HookContainer $hookContainer,
		private readonly UserFactory $userFactory,
		private readonly IContextSource $context,
		private readonly UserRequirementsConditionValidator $userRequirementsConditionValidator,
		/** @var UserRequirementsConditionEvaluatorBase[] */
		private readonly array $evaluators = [],
	) {
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
	}

	/**
	 * As recursivelyCheckCondition, but *not* recursive. The only valid conditions
	 * are those whose first element is one of APCOND_* defined in Defines.php.
	 * Other types will throw an exception if no extension evaluates them.
	 *
	 * @param array $cond A condition, which must not contain other conditions. This array must contain at least
	 *     one item, which is the condition type.
	 * @param UserIdentity $user The user to check the condition against
	 * @return ?bool Whether the condition is true for the user. Null if it's a private condition
	 *     and we're not supposed to evaluate these.
	 * @throws InvalidArgumentException if autopromote condition was not recognized.
	 * @throws LogicException if APCOND_BLOCKED is checked again before returning a result.
	 */
	protected function checkCondition( array $cond, UserIdentity $user ): ?bool {
		$isPerformingRequest = $this->context->getUser()->isSafeToLoad() &&
			$user->equals( $this->context->getUser() );

		$conditionType = $cond[0];
		$args = array_slice( $cond, 1 );

		foreach ( $this->evaluators as $evaluator ) {
			$result = $evaluator->checkCondition( $conditionType, $args, $user, $isPerformingRequest );
			if ( $result !== null ) {
				return $result;
			}
		}

		$result = null;
		$type = $cond[0];
		$args = array_slice( $cond, 1 );
		$this->hookRunner->onUserRequirementsCondition( $conditionType, $args, $user, $isPerformingRequest, $result );

		$isCurrentWiki = ( $user->getWikiId() === false ) || WikiMap::isCurrentWikiId( $user->getWikiId() );
		if ( $isPerformingRequest && $isCurrentWiki ) {
			// The legacy hook is run only if the tested user is the one performing
			// the request (like for autopromote), and the user is from the local wiki.
			// If any of these conditions is not met, we cannot invoke the hook,
			// as it may produce incorrect results.
			$userObject = $this->userFactory->newFromUserIdentity( $user );
			$this->hookRunner->onAutopromoteCondition( $conditionType, $args, $userObject, $result );
		}

		if ( $result === null ) {
			throw new InvalidArgumentException(
				"Unrecognized condition $type in UserRequirementsCondition!"
			);
		}

		return (bool)$result;
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
	 * If the passed condition is invalid, false is returned without evaluating it.
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
		if ( !$this->userRequirementsConditionValidator->isValid( $cond ) ) {
			return false;
		}

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

		// Ensure the condition makes sense at all
		if ( count( $cond ) < 1 ) {
			return false;
		}

		if ( isset( $skippedConditions[$cond[0]] ) ) {
			return null;
		}

		return $this->checkCondition( $cond, $user );
	}

	/**
	 * Goes through a condition passed as the input and extracts all private conditions that are used within it.
	 * @param mixed $cond A condition, possibly containing other conditions.
	 * @return list<mixed> A list of unique private conditions present in $cond
	 * @since 1.46
	 */
	public function extractPrivateConditions( $cond ): array {
		$allPrivateConditions = $this->options->get( MainConfigNames::UserRequirementsPrivateConditions );
		$allConditionsUsed = $this->extractConditions( $cond );
		$privateConditionsUsed = array_intersect( $allPrivateConditions, $allConditionsUsed );
		return array_values( $privateConditionsUsed );
	}

	/**
	 * Goes through a condition passed as the input and extracts all simple conditions that are used within it.
	 *
	 * Simple condition is any condition that is not a logical operator, for example APCOND_EDITCOUNT is
	 * a simple condition.
	 * @param mixed $cond A condition, possibly containing other conditions.
	 * @return list<mixed> A list of unique private conditions present in $cond
	 * @since 1.46
	 */
	public function extractConditions( $cond ): array {
		$result = $this->extractConditionsInternal( $cond );
		return array_values( array_unique( $result ) );
	}

	/**
	 * Internal backend for {@see extractConditions}. It returns a list of all simple conditions found
	 * in the input conditions. The result may contain duplicates.
	 * @param mixed $cond
	 * @return list<mixed>
	 */
	private function extractConditionsInternal( $cond ): array {
		if ( $cond === [] ) {
			return [];
		}

		$result = [];
		if ( is_array( $cond ) ) {
			$op = $cond[0];
			if ( in_array( $op, self::VALID_OPS ) ) {
				foreach ( array_slice( $cond, 1 ) as $subcond ) {
					$result = array_merge(
						$result, $this->extractConditionsInternal( $subcond ) );
				}
			} else {
				$result[] = $op;
			}
		} else {
			$result[] = $cond;
		}
		return $result;
	}
}
