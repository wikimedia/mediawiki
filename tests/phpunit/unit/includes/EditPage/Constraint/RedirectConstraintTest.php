<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Unit\EditPage\Constraint;

use EditConstraintTestTrait;
use MediaWiki\Content\Content;
use MediaWiki\EditPage\Constraint\IEditConstraint;
use MediaWiki\EditPage\Constraint\RedirectConstraint;
use MediaWiki\EditPage\IEditObject;
use MediaWiki\Page\RedirectLookup;
use MediaWiki\Title\Title;
use MediaWikiUnitTestCase;
use StatusValue;

/**
 * Tests the RedirectConstraint
 *
 * @author SomeRandomDeveloper
 *
 * @covers \MediaWiki\EditPage\Constraint\RedirectConstraint
 */
class RedirectConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;

	private function assertRedirectConstraintFailed( RedirectConstraint $constraint, int $statusCode ): void {
		$this->assertSame(
			IEditConstraint::CONSTRAINT_FAILED,
			$constraint->checkConstraint()
		);

		// use status field instead of getLegacyStatus to avoid having to mock more objects used in that function
		$status = $constraint->status;
		$statusValue = StatusValue::newGood( $status );
		$statusValue->fatal( '' );
		$this->assertStatusNotGood( $statusValue );
		$this->assertStatusValue( $statusCode, $statusValue );
	}

	private function getContent( ?Title $target = null ): Content {
		$content = $this->createMock( Content::class );
		$target ?? $this->createMock( Title::class );
		// No $this->once() since only called for the new content
		$content->method( 'isRedirect' )
			->willReturn( true );
		$content->expects( $this->atLeastOnce() )
			->method( 'getRedirectTarget' )
			->willReturn( $target );
		return $content;
	}

	private function createTargetTitle( bool $exists, bool $isRedirect, bool $isValidRedirectTarget = true ): Title {
		$target = $this->createMock( Title::class );
		$target->expects( $this->atLeastOnce() )
			->method( 'equals' )
			->willReturnCallback( static fn ( $otherTitle ) => $otherTitle === $target );
		$target->method( 'isKnown' )->willReturn( $exists );
		$target->method( 'isRedirect' )->willReturn( $isRedirect );
		$target->method( 'isValidRedirectTarget' )->willReturn( $isValidRedirectTarget );
		return $target;
	}

	private function createRedirectConstraint(
		Content $originalContent,
		Content $newContent,
		?Title $title = null,
	): RedirectConstraint {
		return new RedirectConstraint(
			null,
			$newContent,
			$originalContent,
			$title ?? $this->createMock( Title::class ),
			'',
			null,
			$this->createMock( RedirectLookup::class )
		);
	}

	public function testNormalRedirectPass() {
		// old and new content is a normal redirect
		$constraint = $this->createRedirectConstraint(
			$this->getContent( $this->createTargetTitle( true, false ) ),
			$this->getContent( $this->createTargetTitle( true, false ) )
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function testBrokenRedirectPass() {
		// both the old and the new content have a broken redirect pointing to the same title, so no warning
		$target = $this->createTargetTitle( false, false );
		$constraint = $this->createRedirectConstraint(
			$this->getContent( $target ),
			$this->getContent( $target )
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function testBrokenRedirectFailure() {
		// new content is a broken redirect, but existing content is not
		$constraint = $this->createRedirectConstraint(
			$this->getContent(),
			$this->getContent( $this->createTargetTitle( false, false ) )
		);
		$this->assertRedirectConstraintFailed(
			$constraint,
			IEditObject::AS_BROKEN_REDIRECT
		);
	}

	public function testDoubleRedirectPass() {
		// both the old and the new content have a double redirect pointing to the same redirect, so no warning
		$target = $this->createTargetTitle( true, true );
		$constraint = $this->createRedirectConstraint(
			$this->getContent( $target ),
			$this->getContent( $target )
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function testDoubleRedirectFailure() {
		// new content is a double redirect, but existing content is not
		$constraint = $this->createRedirectConstraint(
			$this->getContent(),
			$this->getContent( $this->createTargetTitle( true, true ) )
		);
		$this->assertRedirectConstraintFailed(
			$constraint,
			IEditObject::AS_DOUBLE_REDIRECT
		);
	}

	public function testSelfRedirectPass() {
		// both the old and the new content have a self redirect, so no warning
		$target = $this->createTargetTitle( true, true );
		$constraint = $this->createRedirectConstraint(
			$this->getContent( $target ),
			$this->getContent( $target ),
			$target
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function testSelfRedirectFailure() {
		// new content is a self redirect, but existing content is not
		$target = $this->createTargetTitle( true, true );
		$constraint = $this->createRedirectConstraint(
			$this->getContent(),
			$this->getContent( $target ),
			$target
		);
		$this->assertRedirectConstraintFailed(
			$constraint,
			IEditObject::AS_SELF_REDIRECT
		);
	}

	public function testInvalidRedirectTargetPass() {
		// both the old and the new content have a redirect pointing to an invalid redirect target, so no warning
		$target = $this->createTargetTitle( true, false, false );
		$constraint = $this->createRedirectConstraint(
			$this->getContent( $target ),
			$this->getContent( $target )
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function testInvalidRedirectTargetFailure() {
		// new content is a redirect pointing to an invalid redirect target, but existing content is not
		$constraint = $this->createRedirectConstraint(
			$this->getContent(),
			$this->getContent( $this->createTargetTitle( true, false, false ) )
		);
		$this->assertRedirectConstraintFailed(
			$constraint,
			IEditObject::AS_INVALID_REDIRECT_TARGET
		);
	}

}
