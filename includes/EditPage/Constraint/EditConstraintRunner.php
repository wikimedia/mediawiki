<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;
use StatusValue;
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
	public function __construct( IEditConstraint ...$constraints ) {
		// TODO consider injecting this?
		$this->logger = LoggerFactory::getInstance( 'EditConstraintRunner' );
		$this->addConstraints( ...$constraints );
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
	 * Add multiple edit constraints to check.
	 * @see addConstraint()
	 */
	public function addConstraints( IEditConstraint ...$constraints ) {
		foreach ( $constraints as $constraint ) {
			$this->addConstraint( $constraint );
		}
	}

	/**
	 * Run constraint checks until one fails.
	 *
	 * @return StatusValue Good if all constraints pass, otherwise the status returned by the constraint that failed.
	 */
	public function checkConstraints(): StatusValue {
		foreach ( $this->constraints as $constraint ) {
			$status = $constraint->checkConstraint();
			$this->logConstraintCheck( $constraint, $status );

			if ( !$status->isOK() ) {
				$this->failedConstraint = $constraint;
				return $status;
			}
		}

		return StatusValue::newGood();
	}

	/**
	 * Run all constraint checks and merge the returned statuses.
	 *
	 * @return StatusValue Good if all constraints pass, otherwise a StatusValue that was merged with all
	 * statuses that were returned by constraint checks.
	 */
	public function checkAllConstraints(): StatusValue {
		$statusValue = StatusValue::newGood();

		foreach ( $this->constraints as $constraint ) {
			$constraintStatus = $constraint->checkConstraint();
			$this->logConstraintCheck( $constraint, $constraintStatus );
			$statusValue->merge( $constraintStatus );
		}

		return $statusValue;
	}

	/**
	 * Log the result of a constraint check.
	 * Passes use the `debug` level; failures use `info`.
	 */
	private function logConstraintCheck( IEditConstraint $constraint, StatusValue $statusValue ): void {
		if ( $statusValue->isOK() ) {
			$this->logger->debug(
				'Check for {name} succeeded',
				[
					'name' => $this->getConstraintName( $constraint ),
				]
			);
		} else {
			$this->logger->info(
				'Check for {name} failed',
				[
					'name' => $this->getConstraintName( $constraint ),
				]
			);
		}
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
