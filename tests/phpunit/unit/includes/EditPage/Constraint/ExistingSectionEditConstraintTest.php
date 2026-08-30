<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Unit\EditPage\Constraint;

use MediaWiki\Content\Content;
use MediaWiki\EditPage\Constraint\EditConstraint;
use MediaWiki\EditPage\Constraint\ExistingSectionEditConstraint;
use MediaWiki\Language\RawMessage;
use MediaWiki\PageEdit\PageEditAttemptState;
use MediaWikiUnitTestCase;

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
			true,
			$newContent,
			$originalContent,
			new RawMessage( '' ),
			new PageEditAttemptState(),
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function testPass_newSection() {
		$constraint = new ExistingSectionEditConstraint(
			'new',
			'UserSummary',
			md5( 'UserSummary' ),
			true,
			$this->createNoOpMock( Content::class ),
			null,
			new RawMessage( '' ),
			new PageEditAttemptState(),
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
		$editAttemptState = new PageEditAttemptState();
		$constraint = new ExistingSectionEditConstraint(
			'notnew',
			'UserSummary',
			md5( 'UserSummary' ),
			true,
			$newContent,
			$originalContent,
			new RawMessage( '' ),
			$editAttemptState,
		);
		$status = $this->assertConstraintFailed( $constraint, EditConstraint::AS_SUMMARY_NEEDED );
		$this->assertTrue( $editAttemptState->shouldAllowBlankSummary() );
		$this->assertTrue( $status->canResubmit() );
	}

}
