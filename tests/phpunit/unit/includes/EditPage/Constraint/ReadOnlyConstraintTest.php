<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\EditPage\Constraint\IEditConstraint;
use MediaWiki\EditPage\Constraint\ReadOnlyConstraint;
use Wikimedia\Rdbms\ReadOnlyMode;

/**
 * Tests the ReadOnlyConstraint
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\ReadOnlyConstraint
 */
class ReadOnlyConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;

	public function testPass() {
		$readOnlyMode = $this->createMock( ReadOnlyMode::class );
		$readOnlyMode->method( 'isReadOnly' )->willReturn( false );
		$constraint = new ReadOnlyConstraint( $readOnlyMode );
		$this->assertConstraintPassed( $constraint );
	}

	public function testFailure() {
		$readOnlyMode = $this->createMock( ReadOnlyMode::class );
		$readOnlyMode->method( 'isReadOnly' )->willReturn( true );
		$constraint = new ReadOnlyConstraint( $readOnlyMode );
		$this->assertConstraintFailed( $constraint, IEditConstraint::AS_READ_ONLY_PAGE );
	}

}
