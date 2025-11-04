<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Content\Content;
use MediaWiki\EditPage\Constraint\ExistingSectionEditConstraint;
use MediaWiki\EditPage\Constraint\IEditConstraint;

/**
 * Tests the ExistingSectionEditConstraint
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\ExistingSectionEditConstraint
 */
class ExistingSectionEditConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;

	public function testPass() {
		$originalContent = $this->createMock( Content::class );
		$newContent = $this->createMock( Content::class );
		$newContent->expects( $this->once() )
			->method( 'equals' )
			->with( $originalContent )
			->willReturn( false );
		$newContent->expects( $this->once() )
			->method( 'isRedirect' )
			->willReturn( false );
		$constraint = new ExistingSectionEditConstraint(
			'notnew',
			'UserSummary',
			'AutoSummary',
			false,
			$newContent,
			$originalContent
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function testPass_newSection() {
		$constraint = new ExistingSectionEditConstraint(
			'new',
			'UserSummary',
			md5( 'UserSummary' ),
			false,
			$this->createNoOpMock( Content::class ),
			null
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function testFailure_autoSummary() {
		$originalContent = $this->createMock( Content::class );
		$newContent = $this->createMock( Content::class );
		$newContent->expects( $this->once() )
			->method( 'equals' )
			->with( $originalContent )
			->willReturn( false );
		$newContent->expects( $this->once() )
			->method( 'isRedirect' )
			->willReturn( false );
		$constraint = new ExistingSectionEditConstraint(
			'notnew',
			'UserSummary',
			md5( 'UserSummary' ),
			false,
			$newContent,
			$originalContent
		);
		$this->assertConstraintFailed( $constraint, IEditConstraint::AS_SUMMARY_NEEDED );
	}

	public function testFailure_revisionDeleted() {
		$constraint = new ExistingSectionEditConstraint(
			'notnew',
			'UserSummary',
			md5( 'UserSummary' ),
			false,
			$this->createNoOpMock( Content::class ),
			null
		);
		$this->assertConstraintFailed( $constraint, IEditConstraint::AS_REVISION_WAS_DELETED );
	}

}
