<?php
/**
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

	/** @var LoggerInterface */
	private $logger;

	/**
	 * @var IEditConstraint[]
	 *
	 * Constraints to check.
	 */
	private $constraints = [];

	/**
	 * @var IEditConstraint|bool
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
	 *
	 * @param IEditConstraint $constraint
	 */
	public function addConstraint( IEditConstraint $constraint ) {
		$this->constraints[] = $constraint;
	}

	/**
	 * Run constraint checks
	 *
	 * Returns true if all constraints pass, false otherwise.
	 * Check getLegacyStatus for the reason
	 *
	 * @return bool
	 */
	public function checkConstraints() : bool {
		foreach ( $this->constraints as $constraint ) {
			$result = $constraint->checkConstraint();
			$fullClassName = explode( '\\', get_class( $constraint ) );
			$this->logger->debug(
				'Checked {class}, got result: {result}',
				[
					'class' => end( $fullClassName ),
					'result' => $result
				]
			);

			if ( $result !== IEditConstraint::CONSTRAINT_PASSED ) {
				$this->failedConstraint = $constraint;
				return false;
			}
		}
		return true;
	}

	/**
	 * Get the constraint that failed
	 *
	 * @return IEditConstraint
	 */
	public function getFailedConstraint() : IEditConstraint {
		Assert::precondition(
			$this->failedConstraint !== false,
			'getFailedConstraint called with no failed constraint'
		);
		return $this->failedConstraint;
	}

}
