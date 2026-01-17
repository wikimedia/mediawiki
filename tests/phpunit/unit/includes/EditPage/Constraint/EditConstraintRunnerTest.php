<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\EditPage\Constraint\EditConstraintRunner;
use MediaWiki\EditPage\Constraint\IEditConstraint;
use MediaWiki\EditPage\Constraint\PageSizeConstraint;
use Wikimedia\Assert\PreconditionException;
use Wikimedia\TestingAccessWrapper;

/**
 * Tests the EditConstraintRunner
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\EditConstraintRunner
 */
class EditConstraintRunnerTest extends MediaWikiUnitTestCase {

	private function getConstraint( StatusValue $result ) {
		$constraint = $this->getMockBuilder( IEditConstraint::class )
			->onlyMethods( [ 'checkConstraint' ] )
			->getMockForAbstractClass();
		$constraint->expects( $this->once() )
			->method( 'checkConstraint' )
			->willReturn( $result );
		return $constraint;
	}

	public function testCheckConstraint_pass() {
		$runner = new EditConstraintRunner();
		$constraint = $this->getConstraint( StatusValue::newGood() );

		$runner->addConstraint( $constraint );
		$this->assertStatusGood( $runner->checkConstraints() );
	}

	public function testCheckConstraint_fail() {
		$runner = new EditConstraintRunner();
		$status = StatusValue::newFatal( 'test-error' );
		$constraint = $this->getConstraint( $status );

		$runner->addConstraint( $constraint );
		$this->assertEquals( $status, $runner->checkConstraints() );
		$this->assertSame(
			$constraint,
			$runner->getFailedConstraint()
		);
	}

	public function testCheckConstraint_multi() {
		$runner = new EditConstraintRunner();
		$constraintPass = $this->getConstraint( StatusValue::newGood() );
		$constraintFail = $this->getConstraint( StatusValue::newFatal( 'test-error' ) );

		$runner->addConstraint( $constraintPass );
		$runner->addConstraint( $constraintFail );
		$this->assertStatusError( 'test-error', $runner->checkConstraints() );
		$this->assertSame(
			$constraintFail,
			$runner->getFailedConstraint()
		);
	}

	public function testCheckAllConstraints_pass() {
		$runner = new EditConstraintRunner();
		$runner->addConstraint( $this->getConstraint( StatusValue::newGood() ) );
		$runner->addConstraint( $this->getConstraint( StatusValue::newGood() ) );

		$this->assertStatusGood( $runner->checkAllConstraints() );
	}

	public function testCheckAllConstraints_fail() {
		$runner = new EditConstraintRunner();
		$runner->addConstraint( $this->getConstraint( StatusValue::newGood()->warning( 'testwarning' ) ) );
		$runner->addConstraint( $this->getConstraint( StatusValue::newFatal( 'testerror' ) ) );
		$runner->addConstraint( $this->getConstraint( StatusValue::newGood() ) );

		$status = $runner->checkAllConstraints();
		$this->assertStatusNotOK( $status );
		$this->assertStatusError( 'testerror', $status );
		$this->assertStatusMessage( 'testwarning', $status );
	}

	public function testGetFailedConstraint_exception() {
		$this->expectException( PreconditionException::class );
		$this->expectExceptionMessage( 'getFailedConstraint called with no failed constraint' );

		$runner = new EditConstraintRunner();
		$runner->getFailedConstraint();
	}

	public function testGetConstraintName() {
		$runner = TestingAccessWrapper::newFromObject( new EditConstraintRunner() );
		$pageSizeConstraint = new PageSizeConstraint(
			1,
			512,
			PageSizeConstraint::BEFORE_MERGE
		);
		$this->assertSame(
			'PageSizeConstraint ' . PageSizeConstraint::BEFORE_MERGE,
			$runner->getConstraintName( $pageSizeConstraint )
		);
	}

}
