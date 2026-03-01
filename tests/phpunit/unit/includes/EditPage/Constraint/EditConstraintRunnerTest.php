<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\EditPage\Constraint\EditConstraint;
use MediaWiki\EditPage\Constraint\EditConstraintRunner;
use MediaWiki\EditPage\EditPageStatus;
use Wikimedia\TestingAccessWrapper;

/**
 * Tests the EditConstraintRunner
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\EditConstraintRunner
 */
class EditConstraintRunnerTest extends MediaWikiUnitTestCase {

	private function getConstraint( EditPageStatus $result ) {
		$constraint = $this->getMockBuilder( EditConstraint::class )
			->onlyMethods( [ 'checkConstraint' ] )
			->getMockForAbstractClass();
		$constraint->expects( $this->once() )
			->method( 'checkConstraint' )
			->willReturn( $result );
		return $constraint;
	}

	public function testCheckConstraint_pass() {
		$runner = new EditConstraintRunner();
		$constraint = $this->getConstraint( EditPageStatus::newGood() );

		$runner->addConstraint( $constraint );
		$this->assertStatusGood( $runner->checkConstraints() );
	}

	public function testCheckConstraint_fail() {
		$runner = new EditConstraintRunner();
		$testStatus = EditPageStatus::newFatal( 'test-error' );
		$constraint = $this->getConstraint( $testStatus );

		$runner->addConstraint( $constraint );
		$status = $runner->checkConstraints();
		$this->assertEquals( $testStatus, $status );
		$this->assertSame(
			$constraint,
			$status->getFailedConstraint()
		);
	}

	public function testCheckConstraint_multi() {
		$runner = new EditConstraintRunner();
		$constraintPass = $this->getConstraint( EditPageStatus::newGood() );
		$constraintFail = $this->getConstraint( EditPageStatus::newFatal( 'test-error' ) );

		$runner->addConstraint( $constraintPass );
		$runner->addConstraint( $constraintFail );
		$status = $runner->checkConstraints();
		$this->assertStatusError( 'test-error', $status );
		$this->assertSame(
			$constraintFail,
			$status->getFailedConstraint()
		);
	}

	public function testCheckAllConstraints_pass() {
		$runner = new EditConstraintRunner();
		$runner->addConstraint( $this->getConstraint( EditPageStatus::newGood() ) );
		$runner->addConstraint( $this->getConstraint( EditPageStatus::newGood() ) );

		$this->assertStatusGood( $runner->checkAllConstraints() );
	}

	public function testCheckAllConstraints_fail() {
		$runner = new EditConstraintRunner();
		$runner->addConstraint( $this->getConstraint( EditPageStatus::newGood()->warning( 'testwarning' ) ) );
		$runner->addConstraint( $this->getConstraint( EditPageStatus::newFatal( 'testerror' ) ) );
		$runner->addConstraint( $this->getConstraint( EditPageStatus::newGood() ) );

		$status = $runner->checkAllConstraints();
		$this->assertStatusNotOK( $status );
		$this->assertStatusError( 'testerror', $status );
		$this->assertStatusMessage( 'testwarning', $status );
	}

	public function testConstructor() {
		$runner = TestingAccessWrapper::newFromObject( new EditConstraintRunner(
			$this->createMock( EditConstraint::class ),
			$this->createMock( EditConstraint::class ),
		) );
		$this->assertCount( 2, $runner->constraints );
	}

}
