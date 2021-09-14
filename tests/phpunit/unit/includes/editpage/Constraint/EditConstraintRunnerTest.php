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

	private function getConstraint( $result ) {
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
		$constraint = $this->getConstraint( IEditConstraint::CONSTRAINT_PASSED );

		$runner->addConstraint( $constraint );
		$this->assertTrue( $runner->checkConstraints() );
	}

	public function testCheckConstraint_fail() {
		$runner = new EditConstraintRunner();
		$constraint = $this->getConstraint( IEditConstraint::CONSTRAINT_FAILED );

		$runner->addConstraint( $constraint );
		$this->assertFalse( $runner->checkConstraints() );
		$this->assertSame(
			$constraint,
			$runner->getFailedConstraint()
		);
	}

	public function testCheckConstraint_multi() {
		$runner = new EditConstraintRunner();
		$constraintPass = $this->getConstraint( IEditConstraint::CONSTRAINT_PASSED );
		$constraintFail = $this->getConstraint( IEditConstraint::CONSTRAINT_FAILED );

		$runner->addConstraint( $constraintPass );
		$runner->addConstraint( $constraintFail );
		$this->assertFalse( $runner->checkConstraints() );
		$this->assertSame(
			$constraintFail,
			$runner->getFailedConstraint()
		);
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
