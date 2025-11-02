<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;
use Wikimedia\Assert\Assert;

/**
 * Back end to process the edit constraints
 *
 * Constraints reflect possible errors that need to be checked
 *
 * @since 1.36
 * @internal
 * @author DannyS712
 */
class EditConstraintRunner {

	private LoggerInterface $logger;

	/**
	 * @var IEditConstraint[]
	 *
	 * Constraints to check.
	 */
	private $constraints = [];

	/**
	 * @var IEditConstraint|false
	 *
	 * The constraint that failed, so that its status can be fetched, or false if none failed.
	 */
	private $failedConstraint = false;

	/**
	 * Create a new runner
	 */
	public function __construct() {
		// TODO allow passing an array here as the starting constraints?
		// TODO consider injecting this?
		$this->logger = LoggerFactory::getInstance( 'EditConstraintRunner' );
	}

	/**
	 * Add a constraint to check
	 *
	 * Not all constraints are applicable to the action api or other methods of submitting
	 * an edit
	 *
	 * For constraints that have dependencies, use the EditConstraintFactory.
	 */
	public function addConstraint( IEditConstraint $constraint ) {
		$this->constraints[] = $constraint;
	}

	/**
	 * Run constraint checks
	 *
	 * Returns true if all constraints pass, false otherwise.
	 * Check getLegacyStatus for the reason
	 */
	public function checkConstraints(): bool {
		foreach ( $this->constraints as $constraint ) {
			$result = $constraint->checkConstraint();
			if ( $result !== IEditConstraint::CONSTRAINT_PASSED ) {
				// Use `info` instead of `debug` for the one constraint that failed
				$this->logger->info(
					'Checked {name}, got result: {result}',
					[
						'name' => $this->getConstraintName( $constraint ),
						'result' => $result
					]
				);

				$this->failedConstraint = $constraint;
				return false;
			}

			// Pass, log at `debug` level
			$this->logger->debug(
				'Checked {name}, got result: {result}',
				[
					'name' => $this->getConstraintName( $constraint ),
					'result' => $result
				]
			);
		}
		return true;
	}

	private function getConstraintName( IEditConstraint $constraint ): string {
		// Used for debug logging
		$fullClassName = explode( '\\', get_class( $constraint ) );
		$constraintName = end( $fullClassName );
		if ( $constraint instanceof PageSizeConstraint ) {
			// TODO "When you need to do this instanceof, it's a code smell"
			// Convert IEditConstraint to abstract class with a getName method
			// once the initial migration to edit constraints is complete
			// PageSizeConstraint is used twice, make sure they can be told apart
			$constraintName .= ' ' . $constraint->getType();
		}
		return $constraintName;
	}

	/**
	 * Get the constraint that failed
	 */
	public function getFailedConstraint(): IEditConstraint {
		Assert::precondition(
			$this->failedConstraint !== false,
			'getFailedConstraint called with no failed constraint'
		);
		return $this->failedConstraint;
	}

}
